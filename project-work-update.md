# ComingBro - Project Progress Report

**Project Name:** ComingBro - Ride-Sharing & Logistics Platform  
**Document Type:** Work Completion & Progress Update  
**Date:** April 11, 2026  
**Prepared By:** Development Team

---

## 1. Project Overview

ComingBro is a full-featured ride-sharing and logistics platform designed to connect drivers with customers for city rides, intercity travel, and freight services. The system consists of three core components:

- **Admin Panel** -- A comprehensive web-based management dashboard for complete platform administration
- **Driver Mobile App APIs** -- A robust RESTful API layer powering the driver-side mobile application
- **Public Website** -- A customer-facing web presence with dynamic CMS-managed content

### Technology Stack

| Component           | Technology                          |
|---------------------|-------------------------------------|
| Backend Framework   | Laravel 13.0 (PHP 8.3+)            |
| Frontend            | Blade Templates + Tailwind CSS 3.4  |
| Build Tool          | Vite 5.4                            |
| API Authentication  | Laravel Sanctum 4.0                 |
| Payment Gateway     | Razorpay Integration                |
| Database            | MySQL                               |
| Session & Cache     | Database-backed                     |
| Queue System        | Database Queue Driver               |
| API Documentation   | Postman Collection (included)       |

---

## 2. Work Completed -- Summary

| Area                        | Status       | Details                                      |
|-----------------------------|--------------|----------------------------------------------|
| Admin Panel                 | Completed    | 33+ controllers, 60+ views, full CRUD        |
| Driver App APIs             | Completed    | 18 controllers, 70+ endpoints                |
| Database Schema             | Completed    | 53+ migrations, 49 models, 28 seeders        |
| Authentication System       | Completed    | OTP, Social Login, Sanctum tokens             |
| Payment Integration         | Completed    | Razorpay order creation & wallet system       |
| Public Website              | Completed    | Home, About, Contact, CMS pages               |
| Postman API Documentation   | Completed    | Full collection with auto-token management    |

---

## 3. Admin Panel -- Completed Modules

The admin panel is a fully functional web dashboard providing complete control over every aspect of the platform. All modules listed below include Create, Read, Update, Delete (CRUD) operations, search functionality, pagination, and status toggle (enable/disable) capabilities.

### 3.1 Dashboard

- Real-time statistics display: Total Customers, Total Drivers, Total Orders, Active Orders, Completed Orders, Cancelled Orders
- Recent orders listing (latest 10 orders)
- Quick-glance metrics for platform health monitoring

### 3.2 User Management

#### Customers Module
- Full customer listing with search (by name, email, or phone number)
- Create, edit, and delete customer records
- Toggle customer active/inactive status
- Paginated view (15 records per page)

#### Drivers Module
- Comprehensive driver listing with search capability
- Detailed driver profile view with related data (bank details, documents, reviews, orders)
- Edit driver information: personal details, vehicle details, service assignment, city/state
- Document verification toggle
- Online/offline status management
- Paginated view with search by name, phone, or email

#### Driver Documents Module
- Define required document types (e.g., license, registration certificate)
- Configure front/back side requirements per document
- Set expiration date tracking
- Enable/disable document types

#### Driver Rules & Guidelines
- Create and manage driver conduct rules
- Upload rule images for visual reference
- Enable/disable individual rules

### 3.3 Order Management

#### City Orders
- View all city ride orders with search by Order ID
- Filter orders by status (placed, accepted, arriving, arrived, in_progress, ongoing, completed, cancelled)
- View full order details including customer info, driver info, and accepted drivers list
- Update order status with validation
- Delete orders when necessary

#### Intercity Orders
- Complete intercity order listing and management
- Same status workflow as city orders
- Intercity-specific tracking and details (source city, destination city, passenger count, parcel info)
- Driver and customer association views

### 3.4 Service Management

#### City Services
- Define ride services with custom pricing:
  - Basic fare charges and base kilometers
  - Per-kilometer charge
  - Ride time fare (per minute)
  - Holding charge configuration
- Configure admin commission per service
- Offer rate toggle
- Service image upload

#### Intercity Services
- Separate service definitions for intercity travel
- AC/Non-AC charge configuration
- Independent pricing structure from city services
- Admin commission management

### 3.5 Vehicle Management

| Sub-Module            | Features                                                      |
|-----------------------|---------------------------------------------------------------|
| Vehicle Types         | Define vehicle categories (sedan, SUV, etc.) with images, linked to services |
| Vehicle Companies     | Manage vehicle manufacturers                                  |
| Vehicle Models        | Manage models linked to companies                             |
| Freight Vehicles      | Cargo vehicles with dimensions (L/W/H/weight), pricing, loading/unloading charges |
| Fuel Types            | Define fuel type options                                      |
| Insurance Companies   | Manage insurance provider records                             |

### 3.6 Location Management

| Sub-Module  | Features                                                        |
|-------------|-----------------------------------------------------------------|
| States      | State/province management with enable/disable                   |
| Cities      | City records linked to states                                   |
| Districts   | District/area management with publish/unpublish                 |
| Zones       | Service zone definitions with geographic coordinates (lat/lng) and polygon area data |
| Airports    | Airport records with name, city, state, country, and coordinates |

### 3.7 Pricing & Tax Management

- **Taxes:** Define tax rules with title, type (percentage), rate, and country association
- **Coupons:** Create discount codes with amount/percentage type, validity dates, public/private visibility, and unique code validation
- **Currencies:** Configure currency code, symbol, symbol position (left/right), and decimal digit precision

### 3.8 Subscription & Recharge Plans

#### Subscription Plans
- Create plans with name, amount, duration, and description
- Configure GST and TDS amounts
- Set ride limits per plan
- Upload plan banner/image

#### Recharge Plans
- Define wallet recharge options with price and original price (for showing discounts)
- Discount percentage calculation
- "Best Value" badge flag
- Sort order configuration
- Dynamic benefits list (icon, title, subtitle per benefit)
- Terms and conditions with title, footer, and bullet points

### 3.9 Content Management

| Sub-Module          | Features                                                        |
|---------------------|-----------------------------------------------------------------|
| Banners             | Manage promotional banners with title, description, image, redirect URL, and position |
| CMS Pages           | Dynamic content pages with rich HTML editor, auto-slug generation, and publish control |
| FAQs                | Frequently asked questions with title and description           |
| Onboarding Screens  | App onboarding content with image, title, description, display order, and target audience (customer/driver/both) |
| Languages           | Language management with code, name, flag image, and RTL support |

### 3.10 Financial Management

- **Payout Requests:** View and manage driver withdrawal requests with status workflow (pending, approved, rejected, completed) and admin notes
- **Driver Wallet Transactions:** View all driver wallet activity with search by driver name
- **Customer Wallet Transactions:** View all customer wallet activity with search by customer name

### 3.11 SOS / Emergency Contacts

- Manage emergency SOS contact numbers (name and phone)
- Dynamic contact list stored in application settings

### 3.12 Application Settings

- **Global Settings:** Platform-wide configuration (contact info, privacy policy, terms & conditions)
- **Payment Gateway Configuration:** Multi-gateway support with per-gateway settings (enable/disable, sandbox mode, API keys)
- **Logo & Branding:** Platform logo and image management
- **Referral Program Settings:** Configure referral bonus amounts
- **Notification Settings:** Push notification configuration
- **Admin Commission:** Platform commission settings
- **Localization/Globalization:** Regional settings management

### 3.13 Admin UI & Layout

- Professional sidebar navigation with collapsible menu sections
- Dark/Light theme toggle
- Active route highlighting for current page
- Responsive design
- Header with navigation controls
- Clean, consistent layout across all modules

---

## 4. Driver Mobile App APIs -- Completed Modules

A complete RESTful API layer has been built to power the driver-side mobile application. All endpoints follow REST conventions, return consistent JSON responses, and are documented in the included Postman collection.

**Total API Controllers:** 18  
**Total API Endpoints:** 70+  
**Authentication:** Laravel Sanctum (Bearer Token)

### 4.1 Authentication & Onboarding APIs

| Endpoint                  | Method | Description                              |
|---------------------------|--------|------------------------------------------|
| `/api/driver/send-otp`    | POST   | Send OTP to driver's phone number        |
| `/api/driver/verify-otp`  | POST   | Verify OTP and issue authentication token |
| `/api/driver/social-login`| POST   | Google/Apple social login                |
| `/api/driver/logout`      | POST   | Revoke authentication token              |
| `/api/driver/delete-account`| DELETE | Permanently delete driver account       |
| `/api/driver/onboarding`  | GET    | Retrieve onboarding screens for new drivers |
| `/api/driver/languages`   | GET    | Get available language options            |

- OTP-based phone authentication with configurable expiry (10 minutes)
- Automatic token generation via Sanctum on successful verification
- New user detection (is_new_user flag) for conditional app flows
- Social login support for Google and Apple accounts

### 4.2 Driver Profile APIs

| Endpoint                              | Method | Description                              |
|---------------------------------------|--------|------------------------------------------|
| `/api/driver/profile`                 | GET    | Get complete driver profile with relations |
| `/api/driver/profile`                 | PUT    | Full profile update                      |
| `/api/driver/profile/fields`          | PATCH  | Partial field-level update               |
| `/api/driver/profile/{id}`            | GET    | Get driver by ID                         |
| `/api/driver/update-location`         | POST   | Update real-time GPS location            |
| `/api/driver/upload-file`             | POST   | Upload profile/vehicle images (max 10MB) |
| `/api/driver/check-exists/{uid}`      | GET    | Check driver existence by UID            |

- Profile includes related data: bank details, documents, referral info
- Real-time location tracking with latitude, longitude, and rotation
- GeoHash-based position indexing for efficient nearby queries

### 4.3 Dashboard API

| Endpoint                  | Method | Description                                        |
|---------------------------|--------|----------------------------------------------------|
| `/api/driver/dashboard`   | GET    | Aggregated dashboard data for driver home screen   |

Returns in a single call:
- Today's earnings and total ride count
- Average rating and total reviews
- Current wallet balance
- Active subscription status and remaining rides
- Driver tip settings
- Active promotional banners
- Support contact information

### 4.4 City Order (Ride Request) APIs

| Endpoint                                          | Method | Description                            |
|---------------------------------------------------|--------|----------------------------------------|
| `/api/driver/orders/nearby`                       | GET    | Get nearby ride requests               |
| `/api/driver/orders`                              | GET    | List driver's orders (filterable)      |
| `/api/driver/orders/{id}`                         | GET    | Get order details with relations       |
| `/api/driver/orders/{id}`                         | PUT    | Update order status/details            |
| `/api/driver/orders/{orderId}/accept`             | POST   | Accept a ride request                  |
| `/api/driver/orders/{orderId}/accepted/{driverId}`| GET    | Get accepted driver info               |
| `/api/driver/orders/first-order/{userId}`         | GET    | Check if first order (loyalty logic)   |

- **Nearby Order Matching:** Uses the Haversine formula for accurate distance calculation based on GPS coordinates
- Configurable search radius (default: 10 km)
- Optional filtering by service_id and zone_ids
- Order status workflow: placed → accepted → arriving → arrived → in_progress → ongoing → completed
- Support for offer amounts, suggested times, and ride OTP verification

### 4.5 Intercity Order APIs

| Endpoint                                                      | Method | Description                             |
|---------------------------------------------------------------|--------|-----------------------------------------|
| `/api/driver/intercity-orders/nearby`                         | GET    | Get nearby intercity ride requests      |
| `/api/driver/intercity-orders/{id}`                           | GET    | Get intercity order details             |
| `/api/driver/intercity-orders/{id}`                           | PUT    | Update intercity order                  |
| `/api/driver/intercity-orders/{orderId}/accept`               | POST   | Accept intercity ride                   |
| `/api/driver/intercity-orders/{orderId}/accepted/{driverId}`  | GET    | Get accepted driver info                |
| `/api/driver/intercity-orders/first-order/{userId}`           | GET    | Check if first intercity order          |

- Supports multi-city routing (source city to destination city)
- Parcel and freight handling with dimension/weight tracking
- Passenger count and scheduling (date/time selection)

### 4.6 Document Management APIs

| Endpoint                                  | Method | Description                              |
|-------------------------------------------|--------|------------------------------------------|
| `/api/driver/documents`                   | GET    | List all required documents              |
| `/api/driver/documents/{id}`              | GET    | Get document type details                |
| `/api/driver/driver-documents`            | GET    | Get driver's uploaded documents          |
| `/api/driver/driver-documents/numbers`    | GET    | Get document reference numbers           |
| `/api/driver/driver-documents/upload`     | POST   | Upload/update a document                 |
| `/api/driver/notifications`               | GET    | Get document expiry notifications        |

- Front and back side image upload support
- Document number and expiry date tracking
- Automated expiry notification system

### 4.7 Wallet & Payment APIs

| Endpoint                                      | Method | Description                              |
|-----------------------------------------------|--------|------------------------------------------|
| `/api/driver/wallet/transactions`             | GET    | Get wallet transaction history           |
| `/api/driver/wallet/transactions`             | POST   | Record a new wallet transaction          |
| `/api/driver/wallet/update`                   | PUT    | Increment wallet balance                 |
| `/api/driver/wallet/razorpay/create-order`    | POST   | Create Razorpay payment order            |

- Complete wallet ledger with transaction types and notes
- Razorpay payment gateway integration for wallet top-up
- Transaction tracking with payment type, order type, and user type

### 4.8 Bank Details & Withdrawal APIs

| Endpoint                          | Method | Description                              |
|-----------------------------------|--------|------------------------------------------|
| `/api/driver/bank-details`        | GET    | Get saved bank account details           |
| `/api/driver/bank-details`        | PUT    | Create/update bank details               |
| `/api/driver/bank-details/check`  | GET    | Check if bank details exist              |
| `/api/driver/withdraw`            | POST   | Submit withdrawal request                |
| `/api/driver/withdrawals`         | GET    | Get withdrawal history                   |

- Fields: bank name, account holder name, account number, IFSC code, branch name
- Withdrawal request with minimum amount validation
- Complete withdrawal history tracking

### 4.9 Referral System APIs

| Endpoint                                          | Method | Description                              |
|---------------------------------------------------|--------|------------------------------------------|
| `/api/driver/referral`                            | GET    | Get referral data and code               |
| `/api/driver/referral`                            | PUT    | Update referral record                   |
| `/api/driver/referral`                            | POST   | Apply a referral code                    |
| `/api/driver/referral/logs`                       | GET    | Get referral activity logs               |
| `/api/driver/referral/update-amount`              | POST   | Update city referral bonus amount        |
| `/api/driver/referral/update-intercity-amount`    | POST   | Update intercity referral bonus amount   |

- Unique referral code per driver
- Separate bonus tracking for city and intercity referrals
- Full referral log with scan timestamps

### 4.10 Reviews & Ratings APIs

| Endpoint                      | Method | Description                    |
|-------------------------------|--------|--------------------------------|
| `/api/driver/reviews`         | GET    | List reviews for driver        |
| `/api/driver/reviews`         | POST   | Submit a new review            |
| `/api/driver/reviews/{id}`    | GET    | Get review details             |

- 1-5 star rating scale
- Comment support with review type classification
- Reviews linked to both driver and customer

### 4.11 In-App Chat APIs

| Endpoint                                  | Method | Description                          |
|-------------------------------------------|--------|--------------------------------------|
| `/api/driver/chat/inbox`                  | POST   | Create or update chat conversation   |
| `/api/driver/chat/message`               | POST   | Send a chat message                  |
| `/api/driver/chat/{orderId}/messages`    | GET    | Get chat messages for an order       |

- Order-specific chat between driver and customer
- Message pagination (configurable per_page, default 50)
- Sender type tracking (driver/customer)
- Support for text and media message types

### 4.12 Subscription APIs

| Endpoint                                  | Method | Description                          |
|-------------------------------------------|--------|--------------------------------------|
| `/api/driver/subscriptions`               | GET    | Get available subscription plans     |
| `/api/driver/subscriptions/history`       | GET    | Get subscription history             |
| `/api/driver/subscriptions/history`       | POST   | Record subscription purchase         |

- Plan details: name, amount, GST, duration, ride limit
- Remaining days and rides tracking
- Full subscription history log

### 4.13 Recharge Plans APIs

| Endpoint                              | Method | Description                        |
|---------------------------------------|--------|------------------------------------|
| `/api/driver/recharge-plans`          | GET    | Get all active recharge plans      |
| `/api/driver/recharge-plans/{id}`     | GET    | Get specific plan details          |

### 4.14 Settings & Configuration APIs

| Endpoint                              | Method | Description                            |
|---------------------------------------|--------|----------------------------------------|
| `/api/driver/settings`                | GET    | Get application settings               |
| `/api/driver/payment-settings`        | GET    | Get payment gateway configuration      |
| `/api/driver/currency`                | GET    | Get active currency                    |
| `/api/driver/states`                  | GET    | Get all states                         |
| `/api/driver/cities`                  | GET    | Get cities (filterable by state)       |
| `/api/driver/zones`                   | GET    | Get published service zones            |
| `/api/driver/vehicle-companies`       | GET    | Get vehicle manufacturers              |
| `/api/driver/vehicle-models`          | GET    | Get vehicle models (filterable by company) |
| `/api/driver/fuel-types`              | GET    | Get fuel type options                  |
| `/api/driver/services`                | GET    | Get available services                 |
| `/api/driver/vehicle-types`           | GET    | Get vehicle types                      |
| `/api/driver/districts`               | GET    | Get districts                          |
| `/api/driver/insurance-companies`     | GET    | Get insurance companies                |
| `/api/driver/driver-rules`            | GET    | Get driver conduct rules               |

---

## 5. Authentication & Security

### 5.1 Admin Panel Authentication
- Session-based authentication using a dedicated `Admin` model and `admin` guard
- Secure login with username and password
- Session invalidation on logout
- Remember me functionality
- All admin routes protected by `auth:admin` middleware

### 5.2 Driver App Authentication
- **OTP-Based Login:** Phone number verification with configurable OTP expiry (10 minutes)
- **Social Login:** Google and Apple sign-in support
- **Token Management:** Laravel Sanctum bearer tokens for all authenticated API requests
- **Account Deletion:** Full account and token revocation support
- **Verification ID Tracking:** Unique verification IDs for each OTP session

### 5.3 Security Measures
- CSRF protection on all web routes
- Sanctum token-based authentication for API routes
- Input validation via Laravel Form Requests on all endpoints
- Foreign key constraints enforced at the database level
- Cascade delete rules to maintain data integrity

---

## 6. Database Architecture

### 6.1 Overview

| Metric               | Count  |
|-----------------------|--------|
| Migration Files       | 53+    |
| Database Tables       | 53+    |
| Eloquent Models       | 49     |
| Database Seeders      | 28     |
| Model Relationships   | 20+    |

### 6.2 Database Tables by Domain

**User & Authentication (3 tables)**
- `admins` -- Admin user accounts
- `driver_users` -- Driver profiles with 70+ fields (personal, vehicle, subscription, location, insurance)
- `customers` -- Customer profiles with wallet and review aggregates

**Vehicle & Service Management (6 tables)**
- `services` -- City ride service definitions with full pricing structure
- `intercity_services` -- Intercity service definitions with AC/Non-AC options
- `freight_vehicles` -- Freight vehicle types with dimensions and pricing
- `vehicle_companies` -- Vehicle manufacturers
- `vehicle_models` -- Vehicle models linked to companies
- `vehicle_types` -- Vehicle categories linked to services

**Location & Geography (5 tables)**
- `states` -- States/provinces
- `cities` -- Cities linked to states
- `districts` -- Administrative districts
- `zones` -- Service zones with polygon area data (JSON) and coordinates
- `airports` -- Airport locations with GPS coordinates

**Order Management (3 tables)**
- `orders` -- City ride orders with full trip data, pricing, tax, coupon, and status tracking
- `orders_intercity` -- Intercity orders with parcel, freight, and scheduling support
- `accepted_drivers` -- Order acceptance records with offer amounts and timestamps

**Financial & Wallet (5 tables)**
- `bank_details` -- Driver bank account information
- `wallet_transactions` -- Wallet ledger entries
- `withdrawal_history` -- Payout request records with admin review workflow
- `coupons` -- Discount codes with type (fixed/percentage) and validity
- `taxes` -- Tax rules by country

**Subscription System (2 tables)**
- `subscription_plans` -- Plan definitions with GST, TDS, and ride limits
- `subscription_history` -- Subscription purchase and usage records

**Driver Operations (5 tables)**
- `documents` -- Required document type definitions
- `driver_documents` -- Driver-uploaded documents (JSON structure)
- `driver_referrals` -- Referral code and bonus tracking
- `driver_rules` -- Conduct guidelines
- `document_expiry_notifications` -- Automated expiry alerts

**Communication (2 tables)**
- `chat_inboxes` -- Chat conversation channels per order
- `chat_messages` -- Individual messages with sender type and media support

**Reviews & Referrals (3 tables)**
- `reviews` -- Ratings and comments linked to driver and customer
- `referrals` -- Referral program records
- `referral_logs` -- Referral activity tracking

**Content & Configuration (9 tables)**
- `settings` -- Key-value application settings (JSON values)
- `currencies` -- Currency definitions with symbol positioning
- `languages` -- Language options with RTL support
- `cms_pages` -- Dynamic content pages
- `banners` -- Promotional banners with redirect URLs
- `faqs` -- Frequently asked questions
- `onboarding_screens` -- App onboarding content
- `rate_settings` -- Pricing configuration
- `recharge_plans` -- Wallet recharge options with benefits and terms

**Other (3 tables)**
- `otps` -- OTP records with verification IDs and expiry
- `insurance_companies` -- Insurance providers
- `fuel_types` -- Fuel options

### 6.3 Data Seeding

28 seeders have been implemented to populate the database with essential reference data:

- Admin account setup
- Geographic data: States, Cities, Districts, Zones, Airports
- Service definitions: City Services, Intercity Services, Freight Vehicles, Rate Settings
- Vehicle data: Companies, Models, Types, Fuel Types
- Financial: Currencies, Taxes, Coupons, Subscription Plans, Recharge Plans
- Configuration: Settings, Documents, Driver Rules, Insurance Companies
- Content: CMS Pages, Banners, Onboarding Screens, FAQs, Languages

---

## 7. Public Website

A customer-facing website has been built with the following pages:

| Page                   | Route                    | Description                          |
|------------------------|--------------------------|--------------------------------------|
| Home                   | `/`                      | Landing page with CMS-driven content |
| About Us               | `/about`                 | Company information page             |
| Contact Us             | `/contact`               | Contact information page             |
| Privacy Policy         | `/privacy-policy`        | Privacy policy (from settings)       |
| Terms & Conditions     | `/terms-and-conditions`  | Terms of service (from settings)     |
| Dynamic CMS Pages      | `/page/{slug}`           | Any CMS-managed content page         |

All public pages pull content dynamically from the database (CMS pages and settings), making them fully manageable from the admin panel without code changes.

---

## 8. API Documentation

A comprehensive **Postman Collection** has been included in the project (`postman_collection.json`) covering all 70+ API endpoints. The collection includes:

- Organized folder structure mirroring the API module layout
- Pre-configured request bodies with sample data
- Auto-token saving scripts for seamless authenticated testing
- Environment variable support for base URL and token management

> **Note:** The Postman collection can be imported directly into Postman for immediate API testing and team collaboration.

---

## 9. Firebase Data Migration

The project includes a complete data migration from a previous Firebase-based implementation. The `firebase_collection/` directory contains exported JSON files for all major data collections:

- User data (drivers, customers)
- Service and vehicle configurations
- Order records (city and intercity)
- Financial data (wallet transactions, bank details, referrals)
- Settings and configuration
- Content (banners, CMS pages, FAQs)

This data has been mapped and migrated into the new Laravel/MySQL database structure, ensuring continuity from the previous system.

---

## 10. Key Backend Logic & Business Rules

### Ride Matching Algorithm
- Haversine formula-based distance calculation for finding nearby rides
- Configurable search radius (default 10 km)
- Filtering by service type and zone boundaries
- Real-time driver location tracking with GeoHash indexing

### Order Lifecycle Management
- Complete status workflow: placed → accepted → arriving → arrived → in_progress → ongoing → completed/cancelled
- Multi-driver acceptance support (drivers submit offers, customer selects)
- OTP verification for ride start
- Separate workflows for city, intercity, and freight orders

### Wallet & Payment System
- Dual wallet system (driver and customer)
- Razorpay payment gateway integration for wallet top-up
- Transaction ledger with full audit trail
- Withdrawal request workflow with admin approval process

### Subscription Engine
- Plan-based subscription with configurable duration and ride limits
- GST and TDS calculation
- Remaining rides and days tracking
- Subscription history logging

### Referral Program
- Unique referral code generation per driver
- Separate bonus pools for city and intercity referrals
- Referral activity logging with timestamps
- Configurable bonus amounts via admin settings

### Commission & Pricing
- Configurable admin commission per service (stored as JSON for flexibility)
- Multi-tier pricing: base fare + per-km charge + time fare + holding charges
- Tax application with country-specific rules
- Coupon discount system (fixed amount or percentage)

### Document Verification Pipeline
- Configurable document requirements (front/back sides, expiry tracking)
- Driver document upload with admin verification workflow
- Automated expiry notification system

---

## 11. Project Structure & Code Quality

```
comingbro/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── Admin/          → 33 controllers (admin panel)
│   │       └── Api/Driver/     → 18 controllers (mobile APIs)
│   ├── Models/                 → 49 Eloquent models
│   └── Providers/              → Service providers
├── database/
│   ├── migrations/             → 53+ migration files
│   ├── seeders/                → 28 seeders
│   └── factories/              → Model factories
├── resources/
│   └── views/
│       ├── admin/              → 60+ admin panel views
│       ├── auth/               → Authentication views
│       ├── components/admin/   → Reusable UI components
│       └── pages/              → Public website pages
├── routes/
│   ├── admin.php               → Admin panel routes
│   ├── api.php                 → Driver API routes
│   └── web.php                 → Public website routes
├── firebase_collection/        → Firebase data exports (migration source)
└── postman_collection.json     → API documentation
```

---

## 12. Screenshots

> **Note to team:** Screenshots of the following completed sections should be captured and added to this document for client presentation:

- [ ] Admin Dashboard (with statistics)
- [ ] Customer Management listing
- [ ] Driver Management listing and detail view
- [ ] City Orders listing and order detail view
- [ ] Intercity Orders listing
- [ ] Service configuration form
- [ ] Vehicle management modules
- [ ] Location management (Zones, States, Cities)
- [ ] Coupon and Tax management
- [ ] Subscription and Recharge Plan forms
- [ ] Banner and CMS Page management
- [ ] Wallet Transactions view
- [ ] Payout Requests management
- [ ] Application Settings page
- [ ] Public website pages (Home, About, Contact)
- [ ] Driver mobile app screens (if available)

---

## 13. Pending / In-Progress Items

| Item                                  | Status        | Notes                                           |
|---------------------------------------|---------------|--------------------------------------------------|
| Customer Mobile App APIs              | Pending       | APIs for the customer-facing mobile app          |
| Push Notification Delivery            | Pending       | FCM token stored; notification dispatch pending  |
| Real-time Order Tracking (WebSocket)  | Pending       | Location updates exist; live map tracking pending|
| Email/SMS Notification System         | Pending       | Mail currently configured for logging only       |
| Payment Verification Callbacks        | Pending       | Razorpay order creation done; webhook handling pending |
| Automated Testing Suite               | Pending       | Test infrastructure exists; test cases to be written |
| Rate Limiting & Throttling            | Pending       | API rate limiting configuration                  |
| Admin Role-Based Access Control       | Pending       | Single admin role currently; multi-role support pending |
| Reporting & Analytics Dashboard       | Pending       | Advanced reporting and data export features      |
| Multi-language Admin Panel            | Pending       | Language data exists; admin UI translation pending|

---

## 14. Conclusion

The ComingBro platform has reached a significant milestone with the completion of the core backend infrastructure, full admin panel, and driver mobile app APIs. The system is built on a solid architectural foundation using Laravel 13 with clean separation of concerns, proper database design with referential integrity, and secure API authentication.

**Key achievements:**
- **33+ admin controllers** powering a fully functional management dashboard
- **70+ REST API endpoints** serving the driver mobile application
- **53+ database tables** with comprehensive schema design and seeding
- **49 Eloquent models** with proper relationships and business logic
- **Complete Postman documentation** for API testing and team onboarding
- **Firebase-to-Laravel migration** preserving all historical data
- **Payment gateway integration** with Razorpay for wallet operations
- **Real-time location matching** using geographic algorithms

The platform is well-positioned for the next phase of development, which will focus on customer-side APIs, real-time notification delivery, and advanced analytics.

---

*This document reflects the actual state of the codebase as of April 11, 2026. All features listed have been verified against the implemented code.*
