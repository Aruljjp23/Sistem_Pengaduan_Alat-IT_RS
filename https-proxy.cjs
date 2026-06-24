const https      = require("https");
const httpProxy  = require("http-proxy");
const selfsigned = require("selfsigned");
const os         = require("os");

const LARAVEL_HOST = "127.0.0.1";
const LARAVEL_PORT = 8000;
const HTTPS_PORT   = 8443;
const BIND_HOST    = "0.0.0.0";

function getLocalIP() {
    const ifaces = os.networkInterfaces();
    for (const name of Object.keys(ifaces)) {
        for (const i of ifaces[name]) {
            if (i.family === "IPv4" && !i.internal) return i.address;
        }
    }
    return "localhost";
}

const localIP = getLocalIP();
const pems = selfsigned.generate(
    [{ name: "commonName", value: localIP }],
    { keySize: 2048, days: 3650, algorithm: "sha256",
      extensions: [{ name: "subjectAltName", altNames: [{ type: 7, ip: localIP }, { type: 2, value: "localhost" }] }] }
);

const proxy = httpProxy.createProxyServer({ target: `http://${LARAVEL_HOST}:${LARAVEL_PORT}`, changeOrigin: true });
proxy.on("error", (err, req, res) => {
    if (res && !res.headersSent) { res.writeHead(502); res.end("Laravel tidak jalan di port " + LARAVEL_PORT); }
});

const server = https.createServer({ key: pems.private, cert: pems.cert }, (req, res) => proxy.web(req, res));
server.on("upgrade", (req, socket, head) => proxy.ws(req, socket, head));
server.listen(HTTPS_PORT, BIND_HOST, () => {
    console.log("─────────────────────────────────────────");
    console.log("✅ HTTPS Proxy aktif!");
    console.log("   PC  : https://localhost:" + HTTPS_PORT);
    console.log("   HP  : https://" + localIP + ":" + HTTPS_PORT);
    console.log("─────────────────────────────────────────");
    console.log("⚠️  Pertama kali: klik Advanced → Proceed");
});
server.on("error", e => { console.error("ERROR:", e.message); process.exit(1); });
