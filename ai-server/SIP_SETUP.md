# SIP Trunk Setup Guide

This application has been migrated from Twilio to use SIP trunks directly. Here's what you need to configure:

## Requirements

1. **Drachtio Server** - A SIP server that handles SIP signaling
2. **Media Server** (optional but recommended) - For RTP handling (FreeSWITCH, rtpengine, etc.)
3. **SIP Trunk Information** from your provider

## SIP Trunk Configuration

You'll need the following information from your SIP trunk provider:

- **SIP Server/Host**: The SIP server address (e.g., `sip.provider.com` or IP address)
- **Port**: SIP port (usually 5060 for UDP, 5061 for TLS)
- **Transport**: UDP, TCP, or TLS
- **Username/Auth ID**: Your SIP authentication username
- **Password**: Your SIP authentication password
- **DID/Phone Number**: The phone number associated with your trunk

## Environment Variables

Add these to your `.env` file:

```env
# Drachtio Configuration
DRACHTIO_HOST=127.0.0.1
DRACHTIO_PORT=9022
DRACHTIO_SECRET=cymru

# SIP Trunk Configuration
# Note: Default values are already configured in code, but you can override via .env
SIP_USERNAME=8503027918
SIP_PASSWORD=7JX6ZeuxRDMS
SIP_SERVER=your.sip.provider.com  # Required if registration needed
SIP_PORT=5060
SIP_TRANSPORT=udp
SIP_DID=your_phone_number

# Existing configuration
OPENAI_API_KEY=your_openai_key
ELEVENLABS_API_KEY=your_elevenlabs_key
PUBLIC_HOST=your_public_domain.com
PORT=3000
```

**Note**: SIP credentials are already configured with defaults in the code:
- Username: `8503027918`
- Password: `7JX6ZeuxRDMS`

You only need to set `SIP_SERVER` if your provider requires registration. If your trunk routes calls directly to your Drachtio server, you may not need `SIP_SERVER`.

## Drachtio Server Setup

1. **Install Drachtio Server**: Follow instructions at https://drachtio.org/

2. **Configure Drachtio** to listen for connections:
   ```yaml
   # drachtio.conf
   contact: sip:your-server@your-domain.com
   max-sessions: 1000
   application: sip:127.0.0.1:3000
   ```

3. **Start Drachtio Server**:
   ```bash
   drachtio --external-ip YOUR_PUBLIC_IP
   ```

## Media Handling

For RTP audio handling, you have two options:

### Option 1: Use FreeSWITCH (Recommended)
- Install FreeSWITCH
- Configure it to work with Drachtio
- FreeSWITCH handles RTP to PCM conversion

### Option 2: Direct RTP Handling
- Implement RTP packet parsing
- Convert RTP payload to PCM16
- Feed PCM to your audio processing pipeline

### Option 3: Use Media Server Middleware
- Use rtpengine or similar
- Handles RTP streaming and conversion

## Testing

1. Start Drachtio server
2. Start this Node.js application: `npm start`
3. Make a test call to your SIP trunk's DID
4. Verify calls are being received and processed

## SIP Trunk Provider Setup

Configure your SIP trunk provider to:
- Route incoming calls to your Drachtio server's IP address
- Use the authentication credentials you've configured
- Send RTP media to your media server or Drachtio server

## Troubleshooting

- **No incoming calls**: Check firewall, verify SIP trunk routing
- **Audio issues**: Ensure RTP ports are open (typically UDP 10000-20000)
- **Connection errors**: Verify Drachtio server is running and accessible

## Notes

The current implementation provides a foundation for SIP trunk integration. You may need to:
1. Add RTP packet handling if not using a media server
2. Configure SDP negotiation based on your trunk's capabilities
3. Adjust audio codec handling (G.711, G.729, etc.)
4. Implement proper SIP authentication if required

