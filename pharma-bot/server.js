
require("dotenv").config();

const express = require("express");
const cors = require("cors");
const rateLimit = require("express-rate-limit");
const { GoogleGenAI } = require("@google/genai");

const app = express();

app.use(cors());
app.use(express.json({ limit: "20kb" }));
app.use(express.static(__dirname));

app.use(
  "/chat",
  rateLimit({
    windowMs: 60 * 1000,
    max: 20
  })
);

if (!process.env.GEMINI_API_KEY) {
  throw new Error("GEMINI_API_KEY is missing in .env");
}

const ai = new GoogleGenAI({
  apiKey: process.env.GEMINI_API_KEY
});

// Retry Gemini when temporary errors occur
async function generateWithRetry(systemInstruction, input) {
  for (let attempt = 0; attempt < 3; attempt++) {
    try {
      return await ai.interactions.create({
        model: "gemini-3.8-flash",
        system_instruction: systemInstruction,
        input: input
      });

    } catch (error) {
      const message = error.message || "";

      const temporaryError =
        error.status === 503 ||
        error.status === 429 ||
        /503|429/.test(message);

      if (!temporaryError || attempt === 2) {
        throw error;
      }

      const delay = 1500 * Math.pow(2, attempt);

      console.log(
        `Gemini temporarily unavailable. Retrying in ${delay}ms...`
      );

      await new Promise(resolve => setTimeout(resolve, delay));
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

app.post("/chat", async (req, res) => {
  try {
    const { messages } = req.body;

    if (!Array.isArray(messages) || messages.length === 0) {
      return res.status(400).json({
        reply: "Please enter a message."
      });
    }

    console.log("CHAT REQUEST RECEIVED");

    // 1. Fetch products from Laravel
    const productsResponse = await fetch(
      "http://127.0.0.1:8000/api/chatbot/products"
    );

    if (!productsResponse.ok) {
      throw new Error(
        "Laravel API error: " + productsResponse.status
      );
    }

    const productData = await productsResponse.json();
    const products = productData.products || [];

    console.log("Products fetched:", products.length);

    // 2. Prepare conversation history
    const conversation = messages
      .slice(-20)
      .map((m) => {
        const role =
          m.role === "assistant" ? "Assistant" : "Customer";

        return `${role}: ${String(m.content || "").slice(0, 2000)}`;
      })
      .join("\n\n");

    // 3. Prepare website product context
    const websiteContext = JSON.stringify(products);

    const fullSystemPrompt = `
${SYSTEM_PROMPT}

WEBSITE PRODUCT INFORMATION:

The following products are DEMO products from our Laravel website.
Do not present them as verified real medicines.

${websiteContext}

Additional rules:
- Answer product questions using only the supplied product data.
- Never invent products, prices, brands or availability.
- If a product is not found, say so.
- Never recommend medicines, dosages or treatments.
`;

    // 4. Generate Gemini response with automatic retry
    const interaction = await generateWithRetry(
      fullSystemPrompt,
      conversation
    );

    // 5. Return response
    res.json({
      reply: interaction.output_text ||
        "Sorry, I could not generate a response."
    });

  } catch (error) {
    console.error("CHATBOT ERROR:", error);

    const status =
      error.status === 503 || error.status === 429
        ? error.status
        : 500;

    res.status(status).json({
      reply:
        status === 503 || status === 429
          ? "Our AI assistant is temporarily busy. Please try again shortly."
          : "Something went wrong: " + error.message
    });
  }
});

const PORT = process.env.PORT || 3000;

app.listen(PORT, () => {
  console.log(`Pharma chatbot running at http://localhost:${PORT}`);
});
