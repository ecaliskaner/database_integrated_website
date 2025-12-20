import express from "express";
import { WebSocketServer } from "ws";

const app = express();
app.use(express.urlencoded({ extended: true }));

// Twilio webhook endpoint
app.post("/voice", (req, res) => {
  const twiml = `
    <Response>
      <Start>
        <Stream url="wss://localhost:3001/ai-audio" />
      </Start>
      <Say>Merhaba! AI asistanına bağlandınız.</Say>
    </Response>
  `;
  res.type("text/xml");
  res.send(twiml);
});

// WebSocket server (AI audio stream)
const wss = new WebSocketServer({ port: 3001 });
wss.on("connection", ws => {
  console.log("✅ Twilio Media Stream connected!");

  ws.on("message", msg => {
    console.log("🎧 Audio chunk received:", msg.length, "bytes");
  });

  ws.on("close", () => console.log("❌ Stream closed"));
});

app.listen(3000, () => console.log("🚀 Server running on port 3000"));
