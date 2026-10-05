const express = require("express");
const cors = require("cors");
const crypto = require("crypto");
const Razorpay = require("razorpay");

const app = express();
app.use(express.json({ limit: "200kb" }));

const allowedOrigins = [
  "https://uplekhpal-creator.github.io",
  "http://localhost:3000",
  "http://localhost:5500"
];

app.use(cors({
  origin: (origin, callback) => {
    if (!origin || allowedOrigins.includes(origin)) return callback(null, true);
    return callback(new Error("Origin not allowed by CORS"));
  }
}));

const keyId = process.env.RAZORPAY_KEY_ID;
const keySecret = process.env.RAZORPAY_KEY_SECRET;

if (!keyId || !keySecret) {
  console.warn("WARNING: RAZORPAY_KEY_ID / RAZORPAY_KEY_SECRET are not set.");
}

const razorpay = new Razorpay({
  key_id: keyId,
  key_secret: keySecret
});

app.get("/", (req, res) => {
  res.json({ ok: true, service: "UPLekhpal Razorpay payment backend" });
});

app.get("/health", (req, res) => {
  res.json({ ok: true });
});

// Create a fixed ₹100 Razorpay order.
// The amount is controlled on the server, not by the browser.
app.post("/api/create-order", async (req, res) => {
  try {
    if (!keyId || !keySecret) {
      return res.status(500).json({ ok: false, error: "Razorpay server keys are not configured." });
    }

    const order = await razorpay.orders.create({
      amount: 10000,
      currency: "INR",
      receipt: `upfd_${Date.now()}`,
      notes: {
        service: "UP Forest Department Application Assistance 2026"
      }
    });

    res.json({
      ok: true,
      keyId,
      orderId: order.id,
      amount: order.amount,
      currency: order.currency
    });
  } catch (err) {
    console.error("create-order error:", err);
    res.status(500).json({ ok: false, error: "Unable to create payment order." });
  }
});

// Verify Checkout signature and then verify the payment directly with Razorpay.
app.post("/api/verify-payment", async (req, res) => {
  try {
    const { orderId, paymentId, signature } = req.body || {};

    if (!orderId || !paymentId || !signature) {
      return res.status(400).json({ ok: false, verified: false, error: "Payment verification data is incomplete." });
    }

    const expectedSignature = crypto
      .createHmac("sha256", keySecret)
      .update(`${orderId}|${paymentId}`)
      .digest("hex");

    const signatureOk = crypto.timingSafeEqual(
      Buffer.from(expectedSignature, "utf8"),
      Buffer.from(signature, "utf8")
    );

    if (!signatureOk) {
      return res.status(400).json({ ok: false, verified: false, error: "Invalid payment signature." });
    }

    const payment = await razorpay.payments.fetch(paymentId);

    if (
      payment.order_id !== orderId ||
      Number(payment.amount) !== 10000 ||
      payment.currency !== "INR" ||
      payment.status !== "captured"
    ) {
      return res.status(400).json({
        ok: false,
        verified: false,
        error: "Payment could not be verified as a captured ₹100 payment."
      });
    }

    const digest = crypto
      .createHash("sha256")
      .update(payment.id)
      .digest("hex")
      .slice(0, 8)
      .toUpperCase();

    const registrationNumber = `UPFD-${new Date().getFullYear()}-${digest}`;

    res.json({
      ok: true,
      verified: true,
      paymentId: payment.id,
      orderId: payment.order_id,
      amount: payment.amount,
      currency: payment.currency,
      status: payment.status,
      registrationNumber
    });
  } catch (err) {
    console.error("verify-payment error:", err);
    res.status(500).json({ ok: false, verified: false, error: "Payment verification failed." });
  }
});

const port = process.env.PORT || 10000;
app.listen(port, "0.0.0.0", () => {
  console.log(`UPLekhpal Razorpay backend listening on port ${port}`);
});
