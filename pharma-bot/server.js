require("dotenv").config();

const express = require("express");
const cors = require("cors");
const rateLimit = require("express-rate-limit");

const app = express();

// ---------- Config (.env se aata hai) ----------
const PORT = process.env.PORT || 3000;
const GROQ_API_KEY = process.env.GROQ_API_KEY;
const MODEL = process.env.GROQ_MODEL || "llama-3.3-70b-versatile";
const LARAVEL_URL = process.env.LARAVEL_URL || "http://127.0.0.1:8000";
const DEBUG_ERRORS = process.env.DEBUG_ERRORS === "true";
const ALLOWED_ORIGINS = (
  process.env.ALLOWED_ORIGINS ||
  "http://127.0.0.1:8000,http://localhost:8000"
)
  .split(",")
  .map((o) => o.trim());

if (!GROQ_API_KEY) {
  throw new Error("GROQ_API_KEY is missing in .env");
}

// ---------- Middleware ----------
app.use(cors({ origin: ALLOWED_ORIGINS }));
app.use(express.json({ limit: "20kb" }));

app.use(
  "/chat",
  rateLimit({
    windowMs: 60 * 1000,
    max: 20,
    message: { reply: "Too many messages. Please wait a minute and try again." }
  })
);

// ---------- Products cache ----------
const CACHE_TTL_MS = 60 * 1000; // 1 minute
let productsCache = { data: [], time: 0 };

async function getProducts() {
  const now = Date.now();

  if (productsCache.time && now - productsCache.time < CACHE_TTL_MS) {
    return productsCache.data;
  }

  try {
    const response = await fetch(`${LARAVEL_URL}/api/chatbot/products`, {
      signal: AbortSignal.timeout(5000)
    });

    if (!response.ok) {
      throw new Error("Laravel API error: " + response.status);
    }

    const json = await response.json();
    productsCache = { data: json.products || [], time: now };
    return productsCache.data;
  } catch (err) {
    if (productsCache.time) {
      console.warn("Laravel fetch failed, using old cache:", err.message);
      return productsCache.data;
    }
    throw err;
  }
}

// ---------- Groq call (OpenAI-compatible API) ----------
function getStatus(error) {
  return error.statusCode || error.status || 500;
}

async function callGroq(systemPrompt, chatMessages) {
  const response = await fetch("https://api.groq.com/openai/v1/chat/completions", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      Authorization: `Bearer ${GROQ_API_KEY}`
    },
    body: JSON.stringify({
      model: MODEL,
      messages: [{ role: "system", content: systemPrompt }, ...chatMessages],
      temperature: 0.3,
      max_tokens: 1500
    }),
    signal: AbortSignal.timeout(30000)
  });

  if (!response.ok) {
    const text = await response.text();
    const err = new Error(`Groq ${response.status}: ${text.slice(0, 300)}`);
    err.statusCode = response.status;
    throw err;
  }

  const json = await response.json();
  return json.choices?.[0]?.message?.content || "";
}

// Sirf 503 (temporary overload) par retry
async function generateWithRetry(systemPrompt, chatMessages) {
  for (let attempt = 0; attempt < 3; attempt++) {
    try {
      return await callGroq(systemPrompt, chatMessages);
    } catch (error) {
      if (getStatus(error) !== 503 || attempt === 2) {
        throw error;
      }
      const delay = 1500 * Math.pow(2, attempt);
      console.log(`Groq busy (503). Retrying in ${delay}ms...`);
      await new Promise((resolve) => setTimeout(resolve, delay));
    }
  }
}

const SYSTEM_PROMPT = `
You are PharmaHelper, the official assistant for our pharma company.

Rules:
- Answer using only company information supplied in context.
- Never invent products, prices or availability.
- If information is missing, ask customers to contact company support.
- Never provide medical diagnosis, dosage or treatment advice.
- Direct medical questions to a qualified doctor or pharmacist.
- Reply in Hindi, English or Hinglish according to the customer.
- Keep answers friendly and concise.
`;

// ---------- Routes ----------
app.get("/health", (req, res) => res.json({ ok: true }));

app.post("/chat", async (req, res) => {
  try {
    const { messages } = req.body;

    if (!Array.isArray(messages) || messages.length === 0) {
      return res.status(400).json({ reply: "Please enter a message." });
    }

    console.log("CHAT REQUEST RECEIVED");

    // 1. Products
    const products = await getProducts();
    console.log("Products available:", products.length);

    // 2. Conversation history (OpenAI-style messages)
    const chatMessages = messages.slice(-20).map((m) => ({
      role: m && m.role === "assistant" ? "assistant" : "user",
      content: String((m && m.content) || "").slice(0, 2000)
    }));

    // 3. System prompt with product data
    const fullSystemPrompt = `
${SYSTEM_PROMPT}

WEBSITE PRODUCT INFORMATION:

The following products are DEMO products from our Laravel website.
Do not present them as verified real medicines.

${JSON.stringify(products)}

Additional rules:
- Answer product questions using only the supplied product data.
- Never invent products, prices, brands or availability.
- If a product is not found, say so.
- Never recommend medicines, dosages or treatments.
`;

    // 4. Groq response
    const reply = await generateWithRetry(fullSystemPrompt, chatMessages);

    // 5. Reply
    res.json({ reply: reply || "Sorry, I could not generate a response." });
  } catch (error) {
    // Asli error server console mein
    console.error("CHATBOT ERROR:", error);

    const status = getStatus(error);

    if (status === 429) {
      return res.status(429).json({
        reply:
          "Our assistant is receiving too many requests right now. Please try again in a minute."
      });
    }

    if (status === 503) {
      return res.status(503).json({
        reply: "Our AI assistant is temporarily busy. Please try again shortly."
      });
    }

    res.status(500).json({
      reply: DEBUG_ERRORS
        ? "DEBUG: " + (error.message || String(error))
        : "Something went wrong. Please try again later."
    });
  }
});

app.listen(PORT, () => {
  console.log(`Pharma chatbot running at http://localhost:${PORT}`);
});