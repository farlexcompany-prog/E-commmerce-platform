<div id="pb-chat" style="position:fixed;bottom:20px;right:20px;width:330px;background:#fff;border:1px solid #ccc;border-radius:10px;font-family:sans-serif;box-shadow:0 4px 14px rgba(0,0,0,.15);z-index:9999">
  <div style="background:#0d6efd;color:#fff;padding:10px;border-radius:10px 10px 0 0">PharmaHelper</div>
  <div id="pb-messages" style="height:260px;overflow-y:auto;padding:10px;font-size:14px"></div>
  <form id="pb-form" style="display:flex;border-top:1px solid #ddd">
    <input id="pb-input" autocomplete="off" placeholder="Apna sawal likhein..." style="flex:1;border:0;padding:10px;outline:0">
    <button style="border:0;background:#0d6efd;color:#fff;padding:0 14px">Send</button>
  </form>
</div>

<script>
(function () {
  // Bot ka address. Port badla ho to yahan bhi badlo.
  var CHAT_URL = "http://localhost:3000/chat";

  var chatHistory = [];
  var box = document.getElementById("pb-messages");

  function addMsg(who, text) {
    var p = document.createElement("p");
    var b = document.createElement("b");
    b.textContent = who + ": ";
    p.appendChild(b);
    p.appendChild(document.createTextNode(text));
    box.appendChild(p);
    box.scrollTop = box.scrollHeight;
  }

  document.getElementById("pb-form").addEventListener("submit", async function (e) {
    e.preventDefault();
    var input = document.getElementById("pb-input");
    var text = input.value.trim();
    if (!text) return;
    input.value = "";

    addMsg("You", text);
    chatHistory.push({ role: "user", content: text });

    try {
      var res = await fetch(CHAT_URL, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ messages: chatHistory })
      });
      var data = await res.json();
      addMsg("Bot", data.reply);
      chatHistory.push({ role: "assistant", content: data.reply });
    } catch (err) {
      addMsg("Bot", "Server se connect nahi ho paya.");
    }
  });
})();
</script>