// SIP Trunk AI Server
import express from "express";
import dotenv from "dotenv";
import Srf from "drachtio-srf";
import fs from "fs";
import path from "path";
import axios from "axios";
import FormData from "form-data";
import { spawn } from "child_process";
import dgram from "dgram";
import mulaw from "mu-law";
import { v4 as uuidv4 } from "uuid";
import ffmpegPath from "ffmpeg-static";

dotenv.config();
const PORT = process.env.PORT || 3000;
const DRACHTIO_HOST = process.env.DRACHTIO_HOST || "127.0.0.1";
const DRACHTIO_PORT = process.env.DRACHTIO_PORT || 9022;
const DRACHTIO_SECRET = process.env.DRACHTIO_SECRET || "cymru";

// SIP Trunk credentials
const SIP_USERNAME = process.env.SIP_USERNAME || "8503027918";
const SIP_PASSWORD = process.env.SIP_PASSWORD || "7JX6ZeuxRDMS";
const SIP_SERVER = process.env.SIP_SERVER || "sip.netgsm.com.tr";
const SIP_PORT = process.env.SIP_PORT || 5060;
const SIP_TRANSPORT = process.env.SIP_TRANSPORT || "udp";

const app = express();
app.use(express.urlencoded({ extended: true }));
app.use(express.json());

// --- Basic env validation & debug endpoints ---
const requiredEnv = [
  "OPENAI_API_KEY",
  "ELEVENLABS_API_KEY",
  "PUBLIC_HOST",
];
const missing = requiredEnv.filter((k) => !process.env[k] || String(process.env[k]).trim() === "");
if (missing.length) {
  console.warn("⚠️ Missing env vars:", missing.join(", "));
}
app.get("/health", (req, res) => {
  res.json({ ok: true, uptime: process.uptime() });
});
app.get("/env-check", (req, res) => {
  res.json({
    OPENAI_API_KEY: !!process.env.OPENAI_API_KEY,
    ELEVENLABS_API_KEY: !!process.env.ELEVENLABS_API_KEY,
    PUBLIC_HOST: process.env.PUBLIC_HOST || null,
    DRACHTIO_HOST: DRACHTIO_HOST,
    DRACHTIO_PORT: DRACHTIO_PORT,
    SIP_USERNAME: SIP_USERNAME ? "***" : null, // Hide password
    SIP_SERVER: SIP_SERVER || null,
    SIP_PORT: SIP_PORT,
    SIP_TRANSPORT: SIP_TRANSPORT,
  });
});

// statik TTS dosyalarını servis et
const ttsDir = path.join(process.cwd(), "tts_files");
if (!fs.existsSync(ttsDir)) fs.mkdirSync(ttsDir);
app.use("/tts", express.static(ttsDir));

// Initialize Drachtio SIP Server
const srf = new Srf();
const opts = {
  host: DRACHTIO_HOST,
  port: DRACHTIO_PORT,
  secret: DRACHTIO_SECRET,
};

// Connect to Drachtio server
srf.connect(opts);
srf.on("connect", (err, hostport) => {
  if (err) {
    console.error("❌ Error connecting to Drachtio:", err);
    console.error("   Make sure Drachtio server is running on", `${DRACHTIO_HOST}:${DRACHTIO_PORT}`);
    console.error("   Install Drachtio server from: https://drachtio.org/");
    return;
  }
  console.log(`✅ Connected to Drachtio at ${hostport}`);

  // Register with SIP trunk provider if server is configured
  if (SIP_SERVER) {
    registerSIPTrunk();
  } else {
    console.log("ℹ️  SIP_SERVER not configured - skipping registration");
    console.log("   Incoming calls will be handled if your SIP trunk routes to Drachtio");
  }
});

srf.on("error", (err) => {
  console.error("❌ Drachtio connection error:", err);
});

srf.on("disconnect", () => {
  console.warn("⚠️  Disconnected from Drachtio server");
});

// Register with SIP trunk provider
function registerSIPTrunk() {
  if (!SIP_SERVER) {
    console.warn("⚠️  SIP_SERVER not configured, cannot register");
    return;
  }

  const register = () => {
    const uri = `sip:${SIP_SERVER}`;
    const contact = `sip:${SIP_USERNAME}@${process.env.PUBLIC_IP || "127.0.0.1"}:5060`;

    srf.request({
      uri: uri,
      method: "REGISTER",
      headers: {
        "Contact": `<${contact}>;expires=3600`,
        "From": `<sip:${SIP_USERNAME}@${SIP_SERVER}>`,
        "To": `<sip:${SIP_USERNAME}@${SIP_SERVER}>`,
        "Call-ID": uuidv4(),
        "CSeq": "1 REGISTER",
        "User-Agent": "Drachtio/1.0"
      },
      auth: {
        username: SIP_USERNAME,
        password: SIP_PASSWORD
      }
    }, (err, req) => {
      if (err) {
        console.error("❌ Registration error:", err);
      } else {
        req.on("response", (res) => {
          if (res.status === 200) {
            console.log("✅ Successfully registered with SIP provider");
            console.log(`   Expires: ${res.get("Expires") || "3600"}`);
          } else {
            console.warn(`⚠️ Registration failed: ${res.status} ${res.reason}`);
          }
        });
      }
    });
  };

  // Register immediately and then every 45 seconds to keep NAT open
  register();
  setInterval(register, 45 * 1000);
}

// Per-call buffers and metadata
const calls = {};

const PROCESS_AFTER_MS = 4000;
const MAX_CHUNKS_BEFORE_PROCESS = 80;

// Handle incoming SIP INVITE (incoming call)
srf.invite((req, res) => {
  const callId = req.get("Call-ID");
  const from = req.getParsedHeader("From");
  const caller = from?.uri?.user || from?.name || "unknown";

  console.log(`📞 Incoming SIP call from: ${caller}, Call-ID: ${callId}`);

  // 1. Create a UDP socket for RTP
  const rtpSocket = dgram.createSocket("udp4");
  const rtpPort = 10000 + Math.floor(Math.random() * 10000); // Random port 10000-20000

  rtpSocket.bind(rtpPort, "0.0.0.0", () => {
    console.log(`🎧 RTP socket listening on port ${rtpPort} for call ${callId}`);
  });

  // 2. Generate local SDP
  const publicIp = process.env.PUBLIC_IP || "127.0.0.1"; // You MUST set PUBLIC_IP in .env
  const localSdp = `v=0
o=- ${Date.now()} ${Date.now()} IN IP4 ${publicIp}
s=Drachtio
c=IN IP4 ${publicIp}
t=0 0
m=audio ${rtpPort} RTP/AVP 0 101
a=rtpmap:0 PCMU/8000
a=rtpmap:101 telephone-event/8000
a=fmtp:101 0-16
a=sendrecv`;

  // Initialize call tracking
  calls[callId] = {
    chunks: [],
    lastReceived: Date.now(),
    processing: false,
    req: req,
    dialog: null,
    rtpSocket: rtpSocket,
    remoteRtpIp: null,
    remoteRtpPort: null,
    rtpPort: rtpPort
  };

  // 3. Answer the call with our SDP
  srf.createUAS(req, res, {
    localSdp: localSdp
  }, (err, dialog) => {
    if (err) {
      console.error("❌ Error creating UAS:", err);
      rtpSocket.close();
      delete calls[callId];
      return;
    }

    console.log("✅ Call answered, dialog created:", dialog.id);
    calls[callId].dialog = dialog;

    // Parse remote SDP to get their RTP IP/Port
    const remoteSdp = req.body;
    const ipMatch = remoteSdp.match(/c=IN IP4 ([\d\.]+)/);
    const portMatch = remoteSdp.match(/m=audio (\d+)/);

    if (ipMatch && portMatch) {
      calls[callId].remoteRtpIp = ipMatch[1];
      calls[callId].remoteRtpPort = parseInt(portMatch[1]);
      console.log(`📡 Remote RTP target: ${calls[callId].remoteRtpIp}:${calls[callId].remoteRtpPort}`);
    }

    // Start media processing
    startMediaProcessing(callId, dialog);

    // Play greeting
    playGreeting(callId, dialog);
  });
});

// Start RTP media processing for a call
// Start RTP media processing for a call
function startMediaProcessing(callId, dialog) {
  const callMeta = calls[callId];
  if (!callMeta) return;

  const rtpSocket = callMeta.rtpSocket;

  // Handle incoming RTP packets
  rtpSocket.on("message", (msg) => {
    // Simple RTP parsing (Header is usually 12 bytes)
    // Byte 0: V=2, P, X, CC
    // Byte 1: M, PT (Payload Type)
    // Byte 2-3: Sequence Number
    // Byte 4-7: Timestamp
    // Byte 8-11: SSRC

    if (msg.length > 12) {
      const payload = msg.subarray(12);
      // Assuming PCMU (G.711 u-law) - PT=0
      // Convert u-law to PCM16
      const pcm = mulaw.decode(payload);

      callMeta.chunks.push(Buffer.from(pcm));
      callMeta.lastReceived = Date.now();

      // Check for silence/processing trigger
      if (callMeta.chunks.length >= MAX_CHUNKS_BEFORE_PROCESS) {
        processCallAudio(callId).catch(console.error);
      }
    }
  });

  dialog.on("destroy", () => {
    console.log(`📴 Call ${callId} ended`);
    if (callMeta.chunks.length > 0 && !callMeta.processing) {
      processCallAudio(callId).catch(console.error);
    }
    try {
      rtpSocket.close();
    } catch (e) { /* ignore */ }
    delete calls[callId];
  });

  // Silence detection / inactivity timer
  const audioCheckInterval = setInterval(() => {
    if (!calls[callId]) {
      clearInterval(audioCheckInterval);
      return;
    }

    const now = Date.now();
    if (callMeta.chunks.length > 0 && !callMeta.processing && (now - callMeta.lastReceived) > PROCESS_AFTER_MS) {
      console.log("Silence detected, processing audio...");
      processCallAudio(callId).catch(console.error);
    }
  }, 500);
}

// Play greeting message
async function playGreeting(callId, dialog) {
  try {
    const greetingText = "Merhaba. Lütfen konuşun, sizi dinliyorum.";

    // Generate TTS for greeting
    const timestamp = Date.now();
    const ttsFileName = `tts_greeting_${callId}_${timestamp}.mp3`;
    const ttsPath = path.join(ttsDir, ttsFileName);

    try {
      await generateElevenLabsTTS(greetingText, ttsPath);
      const publicHost = process.env.PUBLIC_HOST;
      if (publicHost) {
        const audioUrl = `http://${publicHost}/tts/${ttsFileName}`;
        // Play audio via SIP (using Playback or similar)
        playAudio(dialog, audioUrl);
      } else {
        // Fallback: Use text-to-speech via SIP if available
        console.log("⚠️ PUBLIC_HOST not set, cannot play audio");
      }
    } catch (err) {
      console.warn("⚠️ Could not generate greeting TTS:", err);
    }
  } catch (err) {
    console.error("Error playing greeting:", err);
  }
}

// Play audio file via SIP dialog using RTP
// Play audio file via RTP
async function playAudio(dialog, audioUrl) {
  const callId = Object.keys(calls).find(id => calls[id].dialog === dialog);
  if (!callId) {
    console.warn("Call not found for dialog, cannot play audio");
    return;
  }
  const callMeta = calls[callId];
  if (!callMeta.remoteRtpIp || !callMeta.remoteRtpPort) {
    console.warn("Remote RTP details unknown, cannot play audio");
    return;
  }

  try {
    console.log(`🔊 Playing audio: ${audioUrl}`);

    // Download the audio file
    const response = await axios.get(audioUrl, { responseType: 'arraybuffer' });
    const mp3Buffer = Buffer.from(response.data);

    // We need to decode MP3 to PCM, then encode to u-law
    // Since we don't have a full mp3 decoder in pure JS easily available without heavy deps (like lame),
    // we will assume the input is WAV or try to use a simple decoder if possible.
    // For this environment, let's assume we can get WAV from ElevenLabs or convert it.
    // ElevenLabs output is MP3 by default.

    // CRITICAL: For this to work without ffmpeg/lame, we should request PCM/WAV from ElevenLabs if possible,
    // or use a library. `mu-law` only handles PCM -> u-law.
    // Let's assume we can use a system command to convert mp3 to pcm if needed, or just send raw if it was pcm.

    // For now, let's try to decode the MP3 using a simple trick or just warn.
    // Actually, ElevenLabs API allows `output_format: "pcm_16000"` (or 8000).
    // Let's update the ElevenLabs call to request PCM if possible, or use ffmpeg.

    // Since we can't easily change the ElevenLabs call in this function, let's use ffmpeg to convert to PCM 8000Hz mono.
    // We'll write to tmp, convert, read back.

    const tmpMp3 = path.join(process.cwd(), `tmp_play_${callId}_${Date.now()}.mp3`);
    const tmpPcm = path.join(process.cwd(), `tmp_play_${callId}_${Date.now()}.pcm`);

    fs.writeFileSync(tmpMp3, mp3Buffer);

    // Use ffmpeg to convert to pcm_mulaw or pcm_s16le
    // ffmpeg -i input.mp3 -f s16le -acodec pcm_s16le -ar 8000 -ac 1 output.pcm
    await new Promise((resolve, reject) => {
      const ffmpeg = spawn(ffmpegPath, [
        "-i", tmpMp3,
        "-f", "s16le",
        "-acodec", "pcm_s16le",
        "-ar", "8000",
        "-ac", "1",
        tmpPcm
      ]);
      ffmpeg.on("close", (code) => {
        if (code === 0) resolve();
        else reject(new Error(`ffmpeg exited with code ${code}`));
      });
    });

    const pcmData = fs.readFileSync(tmpPcm);

    // Packetize and send RTP
    const packetSize = 160; // 20ms at 8000Hz
    let sequenceNumber = Math.floor(Math.random() * 65535);
    let timestamp = Math.floor(Math.random() * 4294967295);
    const ssrc = Math.floor(Math.random() * 4294967295);

    for (let i = 0; i < pcmData.length; i += packetSize * 2) { // 16-bit = 2 bytes per sample
      const chunk = pcmData.subarray(i, i + packetSize * 2);
      if (chunk.length < packetSize * 2) break; // Drop last partial chunk

      // Encode to u-law
      const ulawChunk = mulaw.encode(chunk);

      // Construct RTP header
      const rtpHeader = Buffer.alloc(12);
      rtpHeader[0] = 0x80; // V=2
      rtpHeader[1] = 0x00; // PT=0 (PCMU)
      rtpHeader.writeUInt16BE(sequenceNumber, 2);
      rtpHeader.writeUInt32BE(timestamp, 4);
      rtpHeader.writeUInt32BE(ssrc, 8);

      const rtpPacket = Buffer.concat([rtpHeader, Buffer.from(ulawChunk)]);

      callMeta.rtpSocket.send(rtpPacket, callMeta.remoteRtpPort, callMeta.remoteRtpIp);

      sequenceNumber = (sequenceNumber + 1) % 65536;
      timestamp = (timestamp + 160) % 4294967296;

      // Wait 20ms
      await new Promise(r => setTimeout(r, 20));
    }

    // Cleanup
    fs.unlinkSync(tmpMp3);
    fs.unlinkSync(tmpPcm);

  } catch (err) {
    console.error("Error playing audio:", err);
  }
}

// Handle incoming RTP audio (simplified - in production integrate with RTP parser)
function handleRTPPacket(callId, rtpPacket) {
  const callMeta = calls[callId];
  if (!callMeta) return;

  // Extract PCM audio from RTP packet
  // This is simplified - in production you'd use an RTP parser
  // For now, we'll need to integrate with your SIP trunk's RTP handling

  // Example: Convert RTP payload to PCM16 buffer
  // const pcmBuffer = rtpToPCM(rtpPacket);
  // callMeta.chunks.push(pcmBuffer);
  // callMeta.lastReceived = Date.now();
}

// Called to turn raw chunks into a WAV file, transcribe, get reply, TTS, and play via SIP
async function processCallAudio(callId) {
  const meta = calls[callId];
  if (!meta || meta.processing) return;
  if (!meta.chunks || meta.chunks.length === 0) return;
  meta.processing = true;

  try {
    console.log(`🔁 Processing audio for ${callId}, ${meta.chunks.length} chunks`);

    const audioBuffer = Buffer.concat(meta.chunks);
    const wav = pcm16ToWav(audioBuffer, { sampleRate: 8000, numChannels: 1 });

    const timestamp = Date.now();
    const wavPath = path.join(process.cwd(), `tmp_${callId}_${timestamp}.wav`);
    fs.writeFileSync(wavPath, wav);

    // 1) Send to OpenAI Whisper (transcription)
    console.log("🟦 Sending audio to OpenAI Whisper for transcription...");
    const transcript = await transcribeWithOpenAI(wavPath);
    console.log("📝 Transcript:", transcript);
    meta.chunks = []; // clear chunks immediately after transcription file created

    // 2) Send transcript to GPT (chat completion)
    const replyText = await askGPT(transcript);
    console.log("🤖 GPT reply:", replyText);

    // 3) Try ElevenLabs TTS
    const publicHost = process.env.PUBLIC_HOST;
    let audioUrl = null;
    try {
      const ttsFileName = `tts_${callId}_${timestamp}.mp3`;
      const ttsPath = path.join(ttsDir, ttsFileName);
      console.log("🔊 Generating TTS via ElevenLabs...");
      await generateElevenLabsTTS(replyText, ttsPath);
      if (!publicHost) throw new Error("PUBLIC_HOST missing for audio playback");
      audioUrl = `http://${publicHost}/tts/${ttsFileName}`;
    } catch (ttsErr) {
      console.warn("🟡 ElevenLabs failed:", ttsErr?.message || ttsErr);
    }

    // 4) Play response via SIP
    if (meta.dialog && audioUrl) {
      await playAudio(meta.dialog, audioUrl);
      console.log("✅ Playing audio response:", audioUrl);

      // Play continuation message
      const continueText = "Devam edebilirsiniz.";
      const continueTtsPath = path.join(ttsDir, `tts_continue_${callId}_${timestamp}.mp3`);
      try {
        await generateElevenLabsTTS(continueText, continueTtsPath);
        const continueAudioUrl = `http://${publicHost}/tts/tts_continue_${callId}_${timestamp}.mp3`;
        await playAudio(meta.dialog, continueAudioUrl);
      } catch (err) {
        console.warn("Could not play continuation message:", err);
      }
    } else {
      console.warn("⚠️ Cannot play audio - dialog or audioUrl missing");
    }

    // cleanup
    fs.unlinkSync(wavPath);
  } catch (err) {
    console.error("Error in processing audio:", err);
  } finally {
    meta.processing = false;
  }
}

// Utility: create a WAV buffer from PCM16LE raw buffer
function pcm16ToWav(pcmBuffer, opts = {}) {
  const numChannels = opts.numChannels || 1;
  const sampleRate = opts.sampleRate || 8000;
  const bitsPerSample = 16;
  const byteRate = (sampleRate * numChannels * bitsPerSample) / 8;
  const blockAlign = (numChannels * bitsPerSample) / 8;
  const dataSize = pcmBuffer.length;
  const buffer = Buffer.alloc(44 + dataSize);

  // RIFF header
  buffer.write("RIFF", 0);
  buffer.writeUInt32LE(36 + dataSize, 4);
  buffer.write("WAVE", 8);
  // fmt chunk
  buffer.write("fmt ", 12);
  buffer.writeUInt32LE(16, 16); // subchunk1Size
  buffer.writeUInt16LE(1, 20); // PCM format
  buffer.writeUInt16LE(numChannels, 22);
  buffer.writeUInt32LE(sampleRate, 24);
  buffer.writeUInt32LE(byteRate, 28);
  buffer.writeUInt16LE(blockAlign, 32);
  buffer.writeUInt16LE(bitsPerSample, 34);
  // data chunk
  buffer.write("data", 36);
  buffer.writeUInt32LE(dataSize, 40);
  // pcm data
  pcmBuffer.copy(buffer, 44);

  return buffer;
}

// --- OpenAI transcription (Whisper) ---
async function transcribeWithOpenAI(wavPath) {
  try {
    const form = new FormData();
    form.append("file", fs.createReadStream(wavPath));
    form.append("model", "whisper-1");
    form.append("language", "tr");

    const resp = await axios.post("https://api.openai.com/v1/audio/transcriptions", form, {
      headers: {
        Authorization: `Bearer ${process.env.OPENAI_API_KEY}`,
        ...form.getHeaders(),
      },
      maxContentLength: Infinity,
      maxBodyLength: Infinity,
    });
    return resp.data.text || "";
  } catch (e) {
    console.error("OpenAI transcription error:", e.response?.status, e.response?.data || e.message);
    return "";
  }
}

// --- GPT ask ---
async function askGPT(userText) {
  try {
    if (!userText || userText.trim() === "") return "Üzgünüm, seni anlayamadım. Tekrar eder misin?";

    const messages = [
      { role: "system", content: "Sen, bir telefon görüşmesinde insanlara yardımcı olan, nazik ve kısa cevaplar veren bir AI asistansın. Cevaplarını Türkçe olarak sınırla ve kısa tut." },
      { role: "user", content: userText }
    ];

    const resp = await axios.post("https://api.openai.com/v1/chat/completions", {
      model: "gpt-4o-mini",
      messages: messages,
      max_tokens: 300,
    }, {
      headers: { Authorization: `Bearer ${process.env.OPENAI_API_KEY}` }
    });
    const reply = resp.data.choices?.[0]?.message?.content || "Üzgünüm, cevap üretemedim.";
    return reply;
  } catch (e) {
    console.error("GPT error:", e.response?.status, e.response?.data || e.message);
    return "Üzgünüm, bir hata oluştu.";
  }
}

// --- ElevenLabs TTS ---
async function generateElevenLabsTTS(text, outPath) {
  try {
    const voiceId = process.env.ELEVENLABS_VOICE_ID || "21m00Tcm4TlvDq8ikWAM";

    const url = `https://api.elevenlabs.io/v1/text-to-speech/${voiceId}`;
    const resp = await axios.post(url, {
      text: text,
      model_id: "eleven_multilingual_v2",
      voice_settings: {
        stability: 0.5,
        similarity_boost: 0.8
      }
    }, {
      headers: {
        "xi-api-key": process.env.ELEVENLABS_API_KEY,
        "Content-Type": "application/json"
      },
      responseType: "arraybuffer"
    });
    fs.writeFileSync(outPath, resp.data);
    console.log("Saved TTS to", outPath);
  } catch (e) {
    console.error("ElevenLabs error:", e.response?.status, e.response?.data || e.message);
    throw e;
  }
}

// escape XML special chars for <Say>
function escapeXml(str) {
  return String(str)
    .replaceAll("&", "&amp;")
    .replaceAll("<", "&lt;")
    .replaceAll(">", "&gt;")
    .replaceAll('"', "&quot;")
    .replaceAll("'", "&apos;");
}

// Start Express server
const server = app.listen(PORT, () => {
  console.log(`🚀 HTTP server running on port ${PORT}`);
  console.log(`📞 SIP server configured to connect to Drachtio at ${DRACHTIO_HOST}:${DRACHTIO_PORT}`);
  console.log(`📋 SIP Trunk Configuration:`);
  console.log(`   Username: ${SIP_USERNAME}`);
  console.log(`   Server: ${SIP_SERVER || "Not configured (using direct routing)"}`);
  console.log(`   Port: ${SIP_PORT}`);
  console.log(`   Transport: ${SIP_TRANSPORT}`);
  console.log(`💡 Make sure Drachtio server is running and accessible`);
  if (SIP_SERVER) {
    console.log(`💡 Configure Drachtio to register with SIP provider if required`);
  }
});