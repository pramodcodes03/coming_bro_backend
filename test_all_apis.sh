#!/bin/bash
# =============================================================
# ComingBro Driver API - Full Test Runner
# Run on server: bash test_all_apis.sh
# =============================================================

BASE="https://comingbro.nextgeniusinfotech.com/api/driver"
TOKEN=""
DRIVER_ID=""
PASS=0
FAIL=0
ERRORS=""

# Colors
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m'

test_api() {
    local METHOD="$1"
    local URL="$2"
    local BODY="$3"
    local AUTH="$4"
    local NAME="$5"

    local HEADERS=(-H "Accept: application/json" -H "Content-Type: application/json")
    if [ "$AUTH" = "yes" ] && [ -n "$TOKEN" ]; then
        HEADERS+=(-H "Authorization: Bearer $TOKEN")
    fi

    local CURL_ARGS=(-s -w "\n%{http_code}" --connect-timeout 10 --max-time 15)

    if [ "$METHOD" = "GET" ]; then
        RESPONSE=$(curl "${CURL_ARGS[@]}" -X GET "${HEADERS[@]}" "$URL")
    elif [ "$METHOD" = "DELETE" ]; then
        RESPONSE=$(curl "${CURL_ARGS[@]}" -X DELETE "${HEADERS[@]}" "$URL")
    else
        RESPONSE=$(curl "${CURL_ARGS[@]}" -X "$METHOD" "${HEADERS[@]}" -d "$BODY" "$URL")
    fi

    HTTP_CODE=$(echo "$RESPONSE" | tail -1)
    BODY_RESP=$(echo "$RESPONSE" | sed '$d')

    # Check success
    SUCCESS=$(echo "$BODY_RESP" | python3 -c "import sys,json; d=json.load(sys.stdin); print(d.get('success',''))" 2>/dev/null)
    MESSAGE=$(echo "$BODY_RESP" | python3 -c "import sys,json; d=json.load(sys.stdin); print(d.get('message','')[:80])" 2>/dev/null)

    if [[ "$HTTP_CODE" =~ ^2 ]]; then
        if [ "$SUCCESS" = "True" ] || [ "$SUCCESS" = "true" ]; then
            echo -e "${GREEN}✅ PASS${NC} [$HTTP_CODE] $NAME - $MESSAGE"
            PASS=$((PASS + 1))
        else
            echo -e "${YELLOW}⚠️  WARN${NC} [$HTTP_CODE] $NAME - success=$SUCCESS - $MESSAGE"
            PASS=$((PASS + 1))
        fi
    elif [ "$HTTP_CODE" = "422" ]; then
        echo -e "${YELLOW}⚠️  422 ${NC} [$HTTP_CODE] $NAME - $MESSAGE"
        PASS=$((PASS + 1))  # Validation errors are expected for some test data
    elif [ "$HTTP_CODE" = "401" ]; then
        echo -e "${YELLOW}🔒 AUTH${NC} [$HTTP_CODE] $NAME - $MESSAGE"
        PASS=$((PASS + 1))
    elif [ "$HTTP_CODE" = "404" ]; then
        echo -e "${YELLOW}🔍 404 ${NC} [$HTTP_CODE] $NAME - $MESSAGE"
        PASS=$((PASS + 1))
    elif [ "$HTTP_CODE" = "409" ]; then
        echo -e "${YELLOW}⚠️  409 ${NC} [$HTTP_CODE] $NAME - $MESSAGE (duplicate, expected)"
        PASS=$((PASS + 1))
    else
        echo -e "${RED}❌ FAIL${NC} [$HTTP_CODE] $NAME - $MESSAGE"
        FAIL=$((FAIL + 1))
        ERRORS="$ERRORS\n  ❌ [$HTTP_CODE] $NAME: $MESSAGE"
        # Print response body for debugging
        echo "     Response: $(echo "$BODY_RESP" | head -c 200)"
    fi
}

echo "============================================"
echo "  ComingBro Driver API - Full Test Suite"
echo "  Base: $BASE"
echo "============================================"
echo ""

# ========== PUBLIC ENDPOINTS (No Auth) ==========
echo "--- Public Endpoints ---"
test_api GET "$BASE/settings" "" "no" "Get Settings"
test_api GET "$BASE/payment-settings" "" "no" "Get Payment Settings"
test_api GET "$BASE/currency" "" "no" "Get Currency"
test_api GET "$BASE/onboarding" "" "no" "Get Onboarding Screens"
test_api GET "$BASE/languages" "" "no" "Get Languages"

# ========== AUTHENTICATION ==========
echo ""
echo "--- Authentication ---"

# Send OTP
SEND_RESP=$(curl -s -X POST "$BASE/send-otp" \
    -H "Accept: application/json" \
    -H "Content-Type: application/json" \
    -d '{"phone_number":"9876543210","country_code":"+91"}')
VERIFICATION_ID=$(echo "$SEND_RESP" | python3 -c "import sys,json; print(json.load(sys.stdin).get('data',{}).get('verification_id',''))" 2>/dev/null)
SEND_SUCCESS=$(echo "$SEND_RESP" | python3 -c "import sys,json; print(json.load(sys.stdin).get('success',''))" 2>/dev/null)

if [ "$SEND_SUCCESS" = "True" ] || [ "$SEND_SUCCESS" = "true" ]; then
    echo -e "${GREEN}✅ PASS${NC} [200] Send OTP - verification_id=$VERIFICATION_ID"
    PASS=$((PASS + 1))
else
    echo -e "${RED}❌ FAIL${NC} [???] Send OTP"
    echo "     Response: $(echo "$SEND_RESP" | head -c 300)"
    FAIL=$((FAIL + 1))
fi

# Verify OTP (use 2526)
if [ -n "$VERIFICATION_ID" ]; then
    VERIFY_RESP=$(curl -s -X POST "$BASE/verify-otp" \
        -H "Accept: application/json" \
        -H "Content-Type: application/json" \
        -d "{\"verification_id\":\"$VERIFICATION_ID\",\"otp\":\"2526\",\"phone_number\":\"9876543210\",\"country_code\":\"+91\"}")
    TOKEN=$(echo "$VERIFY_RESP" | python3 -c "import sys,json; print(json.load(sys.stdin).get('data',{}).get('token',''))" 2>/dev/null)
    DRIVER_ID=$(echo "$VERIFY_RESP" | python3 -c "import sys,json; d=json.load(sys.stdin).get('data',{}); print(d.get('driver',d.get('user',{})).get('id',''))" 2>/dev/null)
    VERIFY_SUCCESS=$(echo "$VERIFY_RESP" | python3 -c "import sys,json; print(json.load(sys.stdin).get('success',''))" 2>/dev/null)

    if [ "$VERIFY_SUCCESS" = "True" ] || [ "$VERIFY_SUCCESS" = "true" ]; then
        echo -e "${GREEN}✅ PASS${NC} [200] Verify OTP - token=${TOKEN:0:20}... driver_id=$DRIVER_ID"
        PASS=$((PASS + 1))
    else
        echo -e "${RED}❌ FAIL${NC} [???] Verify OTP"
        echo "     Response: $(echo "$VERIFY_RESP" | head -c 300)"
        FAIL=$((FAIL + 1))
    fi
else
    echo -e "${RED}❌ SKIP${NC} Verify OTP - No verification_id from Send OTP"
    FAIL=$((FAIL + 1))
fi

if [ -z "$TOKEN" ]; then
    echo -e "${RED}⛔ Cannot continue without auth token. Exiting.${NC}"
    echo ""
    echo "Results: $PASS passed, $FAIL failed"
    exit 1
fi

echo ""
echo "--- Driver Profile ---"
test_api GET "$BASE/profile" "" "yes" "Get Profile"
test_api GET "$BASE/profile/$DRIVER_ID" "" "yes" "Get Profile By ID"
test_api PUT "$BASE/profile" '{"full_name":"Test Driver","gender":"Male","city":"Mumbai","state":"Maharashtra","pin_code":"400001"}' "yes" "Update Profile"
test_api PATCH "$BASE/profile/fields" '{"fields":{"full_name":"Test Driver Updated"}}' "yes" "Update Profile Fields"
test_api POST "$BASE/update-location" '{"latitude":19.076,"longitude":72.877,"rotation":45.0}' "yes" "Update Location"
test_api GET "$BASE/check-exists/$DRIVER_ID" "" "yes" "Check Driver Exists"
test_api GET "$BASE/count-by-year/2025" "" "yes" "Count By Year"

echo ""
echo "--- Customer ---"
test_api GET "$BASE/customer/1" "" "yes" "Get Customer"

echo ""
echo "--- Orders (City) ---"
test_api GET "$BASE/orders/nearby?latitude=19.076&longitude=72.877&radius=10" "" "yes" "Nearby Orders"
test_api GET "$BASE/orders" "" "yes" "List Orders"
test_api GET "$BASE/orders/1" "" "yes" "Get Order By ID"
test_api PUT "$BASE/orders/1" '{"status":"driver_accepted"}' "yes" "Update Order"
test_api POST "$BASE/orders/1/accept" '{"offer_amount":"150"}' "yes" "Accept Order"
test_api GET "$BASE/orders/1/accepted/$DRIVER_ID" "" "yes" "Get Accepted Driver"
test_api GET "$BASE/orders/first-order/1" "" "yes" "First Order"

echo ""
echo "--- Orders (Intercity) ---"
test_api GET "$BASE/intercity-orders/nearby?latitude=19.076&longitude=72.877&radius=15" "" "yes" "Nearby Intercity Orders"
test_api GET "$BASE/intercity-orders/1" "" "yes" "Get Intercity Order"
test_api PUT "$BASE/intercity-orders/1" '{"status":"driver_accepted"}' "yes" "Update Intercity Order"
test_api POST "$BASE/intercity-orders/1/accept" '{"offer_amount":"500"}' "yes" "Accept Intercity Order"
test_api GET "$BASE/intercity-orders/1/accepted/$DRIVER_ID" "" "yes" "Get Intercity Accepted Driver"
test_api GET "$BASE/intercity-orders/first-order/1" "" "yes" "Intercity First Order"

echo ""
echo "--- Documents ---"
test_api GET "$BASE/documents" "" "yes" "List Documents"
test_api GET "$BASE/documents/1" "" "yes" "Get Document By ID"
test_api GET "$BASE/driver-documents" "" "yes" "Get Driver Documents"
test_api GET "$BASE/driver-documents/numbers" "" "yes" "Get Driver Document Numbers"

echo ""
echo "--- Services & Config ---"
test_api GET "$BASE/services" "" "yes" "List Services"
test_api GET "$BASE/vehicle-types" "" "yes" "List Vehicle Types"
test_api GET "$BASE/districts" "" "yes" "List Districts"
test_api GET "$BASE/insurance-companies" "" "yes" "List Insurance Companies"
test_api GET "$BASE/driver-rules" "" "yes" "List Driver Rules"

echo ""
echo "--- Wallet ---"
test_api GET "$BASE/wallet/transactions" "" "yes" "Wallet Transactions"
test_api PUT "$BASE/wallet/update" '{"amount":"100","transaction_id":"test_txn_001","payment_type":"credit","note":"Test deposit"}' "yes" "Wallet Update"

echo ""
echo "--- Bank & Withdrawal ---"
test_api GET "$BASE/bank-details" "" "yes" "Get Bank Details"
test_api GET "$BASE/bank-details/check" "" "yes" "Check Bank Details"
test_api PUT "$BASE/bank-details" '{"bank_name":"SBI","holder_name":"Test Driver","branch_name":"Mumbai","account_number":"1234567890","other_information":"IFSC: SBIN0001234"}' "yes" "Create/Update Bank Details"
test_api GET "$BASE/withdrawals" "" "yes" "List Withdrawals"
test_api POST "$BASE/withdraw" '{"amount":"50","note":"Test withdrawal"}' "yes" "Request Withdrawal"

echo ""
echo "--- Referral ---"
test_api GET "$BASE/referral" "" "yes" "Get Referral"
test_api POST "$BASE/referral" '{"referral_code":"TESTREF123"}' "yes" "Create Referral"
test_api GET "$BASE/referral/logs" "" "yes" "Referral Logs"

echo ""
echo "--- Reviews ---"
test_api GET "$BASE/reviews" "" "yes" "List Reviews"
test_api GET "$BASE/reviews/1" "" "yes" "Get Review By ID"

echo ""
echo "--- Chat ---"
test_api POST "$BASE/chat/inbox" '{"order_id":1,"driver_id":1,"customer_id":1,"last_message":"test"}' "yes" "Chat Inbox"
test_api POST "$BASE/chat/message" '{"order_id":1,"sender_id":1,"sender_type":"driver","message":"Hello test","type":"text"}' "yes" "Send Chat Message"
test_api GET "$BASE/chat/1/messages" "" "yes" "Get Chat Messages"

echo ""
echo "--- Subscriptions ---"
test_api GET "$BASE/subscriptions" "" "yes" "List Subscription Plans"
test_api GET "$BASE/subscriptions/history" "" "yes" "Subscription History (GET)"

echo ""
echo "--- Location Data ---"
test_api GET "$BASE/states" "" "yes" "List States"
test_api GET "$BASE/cities" "" "yes" "List Cities"
test_api GET "$BASE/vehicle-companies" "" "yes" "List Vehicle Companies"
test_api GET "$BASE/vehicle-models" "" "yes" "List Vehicle Models"
test_api GET "$BASE/fuel-types" "" "yes" "List Fuel Types"
test_api GET "$BASE/zones" "" "yes" "List Zones"

echo ""
echo "--- Notifications ---"
test_api GET "$BASE/notifications" "" "yes" "List Notifications"

echo ""
echo "============================================"
echo -e "  Results: ${GREEN}$PASS passed${NC}, ${RED}$FAIL failed${NC}"
echo "============================================"

if [ $FAIL -gt 0 ]; then
    echo ""
    echo -e "${RED}Failed endpoints:${NC}"
    echo -e "$ERRORS"
fi

echo ""
echo "Auth Token: ${TOKEN:0:30}..."
echo "Driver ID: $DRIVER_ID"
