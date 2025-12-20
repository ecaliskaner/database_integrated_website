import express from "express";
import bodyParser from "body-parser";
import dotenv from "dotenv";
import axios from "axios";

dotenv.config();

const app = express();
app.use(bodyParser.urlencoded({ extended: false }));

// 1️⃣ Twilio webhook (gelen çağrı)
app.post("/voice", async (req, res) => {
  console.log("📞 Incoming call from:", req.body.From);

  // Twilio'ya XML (TwiML) döndürür: sesi okuyacak
  const twiml = `
    <Response>
      <Say voice="Polly.Aditi-Neural">Merhaba! Yağızın anasını sikiyorum.</Say>
      <Start>
        <Stream url="wss://${req.headers.host}/media-stream" />
      </Start>
    </Response>
  `;
  res.type("text/xml");
  res.send(twiml);
});

// 2️⃣ Media Stream (Twilio WebSocket)
import { WebSocketServer } from "ws";
const wss = new WebSocketServer({ noServer: true });

wss.on("connection", (ws) => {
  console.log("✅ Twilio Media Stream connected!");

  ws.on("message", async (message) => {
    const data = JSON.parse(message.toString());
    if (data.event === "media") {
      // Burada gelen ses verisini işleyebiliriz
      console.log("🎧 Audio chunk received:", data.media.payload.slice(0, 20));
    }
  });

  ws.on("close", () => console.log("❌ Stream closed"));
});

// Express ile WebSocket bağla
const server = app.listen(3000, () => console.log("🚀 Server running on port 3000"));
server.on("upgrade", (request, socket, head) => {
  if (request.url === "/media-stream") {
    wss.handleUpgrade(request, socket, head, (ws) => {
      wss.emit("connection", ws, request);
    });
  } else socket.destroy();
});
