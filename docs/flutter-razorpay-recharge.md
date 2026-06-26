# Flutter — Razorpay Ride-Recharge Integration (what the app must implement)

The backend now **verifies every payment server-side before granting anything**.
The old flow (just tell the backend "I paid" and it unlocks the recharge) is gone —
those endpoints now **require the Razorpay result and reject unverified calls (HTTP 422)**.

The app must follow the standard **3-step Razorpay flow** for every recharge / top-up:

```
1. create order (backend)  →  2. open Razorpay checkout (SDK)  →  3. verify (backend)
```

Only after step 3 returns `success: true` is the recharge actually active.

---

## 0. Setup

- Add the official package: `razorpay_flutter`.
- **Do NOT hardcode any key.** Get the **public key** from the create-order response
  field `key_id` (or from `GET /payment-settings` → `razorpay.razorpayKey`).
  The **secret is no longer returned by the API** and must never be in the app.
- All `/api/driver/*` and `/api/customer/*` calls need the Bearer auth token as today.

---

## 1. Return Ride recharge (unlock Return Ride feature) — PRIMARY

### Step 1 — create order
`POST /api/driver/wallet/razorpay/create-order`
```json
{ "amount": 99, "purpose": "return_ride_recharge", "recharge_plan_id": 2 }
```
Response `data`:
```json
{ "id": "order_Abc123", "amount": 9900, "currency": "INR", "key_id": "rzp_live_xxx", ... }
```
> `amount` here is the price in **rupees** (99). Razorpay returns the order `amount` in **paise** (9900).

### Step 2 — open checkout
Open `razorpay_flutter` with:
```dart
{
  'key': data['key_id'],
  'order_id': data['id'],     // REQUIRED — must use the order id from step 1
  'amount': data['amount'],   // paise, from the order response
  'currency': 'INR',
  'name': 'Coming Bro',
  'description': 'Return Ride Recharge',
  'prefill': { 'contact': driverPhone, 'email': driverEmail },
}
```

### Step 3 — verify (this is what activates the recharge)
On `PaymentSuccessResponse`, call:
`POST /api/driver/return-rides/recharge`
```json
{
  "razorpay_order_id":   "<response.orderId>",
  "razorpay_payment_id": "<response.paymentId>",
  "razorpay_signature":  "<response.signature>"
}
```
- `success: true` → recharge active; read `data.expires_at`.
- `422` → verification failed; **do not** treat as recharged, show the `message`.

---

## 2. Ride-plan purchase (ride quota: "10 Rides" etc.)

### Step 1 — create order
`POST /api/driver/wallet/razorpay/create-order`
```json
{ "amount": 49, "purpose": "wallet_recharge", "recharge_plan_id": 1 }
```
→ same response shape (`id`, `amount` in paise, `key_id`).

### Step 2 — open checkout (same as above, with the plan price).

### Step 3 — verify + credit rides
`POST /api/driver/wallet/transactions`
```json
{
  "amount": 49,
  "order_type": "wallet_recharge",
  "recharge_plan_id": 1,
  "payment_type": "razorpay",
  "razorpay_order_id":   "<orderId>",
  "razorpay_payment_id": "<paymentId>",
  "razorpay_signature":  "<signature>"
}
```
- `success: true` → response includes `remaining_rides` / `total_rides` (already credited).
- `422` → not paid/verified; rides NOT credited.

---

## 3. Customer wallet top-up

### Step 1 — create order
`POST /api/customer/wallet/razorpay/create-order`  →  `{ "amount": 200, "purpose": "wallet" }`

### Step 2 — open checkout (same pattern).

### Step 3 — verify + credit wallet
`POST /api/customer/wallet/transactions`
```json
{
  "amount": 200,
  "order_type": "wallet_recharge",
  "payment_type": "razorpay",
  "razorpay_order_id":   "<orderId>",
  "razorpay_payment_id": "<paymentId>",
  "razorpay_signature":  "<signature>"
}
```
- Response includes the new `wallet_amount` (already credited from the **captured** amount).
- **Remove the old separate `PUT /wallet/update` call for online top-ups** — the verified
  `/wallet/transactions` call now credits the wallet itself. (Calling `/wallet/update` would
  double-credit.)

---

## 4. Error & reliability handling (important)

- **Payment failed / user cancelled** (`PaymentFailureResponse`): do nothing, let the user retry.
- **Verify call returns 422**: show the server `message`; the recharge is NOT active.
- **App killed / network drops AFTER a successful payment but BEFORE step 3**:
  - Re-send the same step-3 verify call with the same three `razorpay_*` values — the
    backend is **idempotent** and will return success without double-granting.
  - As a safety net, the backend also has a **Razorpay webhook** that fulfils the recharge
    server-side, so the driver/customer still gets it even if the app never calls step 3.
    (No app work needed for the webhook.)
- Always trust the **backend response**, never the Razorpay SDK callback alone, to decide
  whether the recharge/rides/wallet were granted.

---

## 5. Field reference (what each step-3 endpoint requires)

| Endpoint | Required `razorpay_*` fields | Also send |
|---|---|---|
| `POST /driver/return-rides/recharge` | order_id, payment_id, signature | — |
| `POST /driver/wallet/transactions` | order_id, payment_id, signature | amount, order_type=`wallet_recharge`, recharge_plan_id, payment_type=`razorpay` |
| `POST /customer/wallet/transactions` | order_id, payment_id, signature | amount, order_type=`wallet_recharge`, payment_type=`razorpay` |

> The three `razorpay_order_id` / `razorpay_payment_id` / `razorpay_signature` come directly
> from the `razorpay_flutter` `PaymentSuccessResponse` (`orderId`, `paymentId`, `signature`).

---

## 6. Backend ops checklist (not app work, but required to go live)
- Replace the **TEST** key (`rzp_test_…`) with **LIVE** keys in Admin → Settings → Payment.
- In Razorpay Dashboard → Webhooks, add `https://<domain>/api/razorpay/webhook`, enable
  events `payment.captured` and `order.paid`, and save the **webhook secret** into
  Settings → Payment → `razorpay → razorpayWebhookSecret`.
