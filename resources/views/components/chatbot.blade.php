<style>
  #pb-launcher {
    position: fixed; bottom: 20px; right: 20px; z-index: 9999;
    width: 58px; height: 58px; border-radius: 50%; border: 0; padding: 0;
    background: #0d6efd; color: #fff; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 4px 14px rgba(0,0,0,.25);
  }
  #pb-launcher:hover { background: #0b5ed7; }

  #pb-chat {
    position: fixed; bottom: 90px; right: 20px; z-index: 9999;
    width: min(350px, calc(100vw - 40px));
    height: min(460px, calc(100vh - 120px));
    background: #fff; border: 1px solid #ddd; border-radius: 12px;
    box-shadow: 0 6px 24px rgba(0,0,0,.2);
    font-family: Inter, ui-sans-serif, system-ui, sans-serif;
    display: none; flex-direction: column; overflow: hidden;
  }
  #pb-chat.pb-open { display: flex; }

  #pb-head {
    background: #0d6efd; color: #fff; padding: 12px 14px;
    display: flex; align-items: center; justify-content: space-between;
    font-weight: bold;
  }
  #pb-close {
    background: transparent; border: 0; color: #fff; font-size: 22px;
    line-height: 1; cursor: pointer; padding: 0 4px;
  }

  #pb-messages {
    flex: 1; overflow-y: auto; padding: 12px; background: #f7f8fa;
    display: flex; flex-direction: column; gap: 8px; font-size: 14px;
  }
  .pb-msg {
    max-width: 85%; padding: 8px 12px; border-radius: 12px;
    line-height: 1.4; white-space: pre-wrap; word-wrap: break-word;
  }
  .pb-user { align-self: flex-end; background: #0d6efd; color: #fff; border-bottom-right-radius: 3px; }
  .pb-bot  { align-self: flex-start; background: #fff; color: #222; border: 1px solid #e3e6ea; border-bottom-left-radius: 3px; }

  #pb-form { display: flex; border-top: 1px solid #ddd; background: #fff; }
  #pb-input { flex: 1; border: 0; padding: 12px; outline: 0; font-size: 14px; min-width: 0; }
  #pb-send { border: 0; background: #0d6efd; color: #fff; padding: 0 16px; cursor: pointer; }
  #pb-send:disabled { background: #9bbcf5; cursor: default; }
</style>

<button id="pb-launcher" type="button" aria-label="Open chat"></button>

<div id="pb-chat" role="dialog" aria-label="PharmaHelper chat">
  <div id="pb-head">
    <span>PharmaHelper</span>
    <button id="pb-close" type="button" aria-label="Close chat">&times;</button>
  </div>
  <div id="pb-messages"></div>
  <form id="pb-form">
    <input id="pb-input" autocomplete="off" placeholder="Apna sawal likhein...">
    <button id="pb-send" type="submit">Send</button>
  </form>
</div>

<script>
(function () {
  // Bot ka address. Live hosting par isse apni bot domain se badalna.
  var CHAT_URL = "http://" + location.hostname + ":3000/chat";

  var CHAT_ICON  = '<svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor"><path d="M20 2H4a2 2 0 0 0-2 2v18l4-4h14a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2z"/></svg>';
  var CLOSE_ICON = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M5 5l14 14M19 5L5 19"/></svg>';

  var launcher = document.getElementById("pb-launcher");
  var chat     = document.getElementById("pb-chat");
  var box      = document.getElementById("pb-messages");
  var form     = document.getElementById("pb-form");
  var input    = document.getElementById("pb-input");
  var sendBtn  = document.getElementById("pb-send");
  var closeBtn = document.getElementById("pb-close");

  var chatHistory = [];
  var greeted = false;
  var busy = false;

  launcher.innerHTML = CHAT_ICON;

  function addMsg(type, text) {
    var div = document.createElement("div");
    div.className = "pb-msg " + (type === "user" ? "pb-user" : "pb-bot");
    div.textContent = text;   // textContent: HTML execute nahi hota (safe)
    box.appendChild(div);
    box.scrollTop = box.scrollHeight;
    return div;
  }

  function openChat() {
    chat.classList.add("pb-open");
    launcher.innerHTML = CLOSE_ICON;
    launcher.setAttribute("aria-label", "Close chat");
    if (!greeted) {
      addMsg("bot", "Namaste! Main PharmaHelper hoon. Aap hamare products ke baare mein kuch bhi pooch sakte hain.");
      greeted = true;
    }
    input.focus();
  }

  function closeChat() {
    chat.classList.remove("pb-open");
    launcher.innerHTML = CHAT_ICON;
    launcher.setAttribute("aria-label", "Open chat");
  }

  launcher.addEventListener("click", function () {
    chat.classList.contains("pb-open") ? closeChat() : openChat();
  });
  closeBtn.addEventListener("click", closeChat);
  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") closeChat();
  });

  form.addEventListener("submit", async function (e) {
    e.preventDefault();
    var text = input.value.trim();
    if (!text || busy) return;

    input.value = "";
    addMsg("user", text);
    chatHistory.push({ role: "user", content: text });

    busy = true;
    sendBtn.disabled = true;
    var typing = addMsg("bot", "Typing...");

    try {
      var res = await fetch(CHAT_URL, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ messages: chatHistory })
      });
      var data = await res.json();
      typing.textContent = data.reply;
      chatHistory.push({ role: "assistant", content: data.reply });
    } catch (err) {
      typing.textContent = "Server se connect nahi ho paya.";
    }

    busy = false;
    sendBtn.disabled = false;
    box.scrollTop = box.scrollHeight;
    input.focus();
  });
})();
</script>