# E-Commerce Platform

A Jumia-style multi-sided marketplace built with **Laravel 12**, **MySQL**, **Bootstrap 5**, and **Alpine.js**.

## Tech Stack

| Layer       | Technology                                   |
|-------------|----------------------------------------------|
| Backend     | PHP 8.3, Laravel 12 (modular monolith)       |
| Frontend    | Bootstrap 5, Alpine.js (via Vite)            |
| Auth        | Laravel Breeze (Blade)                       |
| Database    | MySQL 8+                                     |
| Build tool  | Vite 6                                       |

---

## Local Setup

### 1. Prerequisites

- PHP ≥ 8.2 with extensions: `pdo_mysql`, `mbstring`, `xml`, `bcmath`, `curl`
- Composer ≥ 2
- Node.js ≥ 18 + npm
- MySQL 8+

### 2. Clone & install dependencies

```bash
git clone https://github.com/your-org/e-commerce.git
cd e-commerce

composer install
npm install
```

### 3. Environment configuration

```bash
cp .env.example .env
php artisan key:generate
```

Open `.env` and configure your MySQL connection:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ecommerce
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
```

Create the database in MySQL:

```sql
CREATE DATABASE ecommerce CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 4. Run migrations & seeders

```bash
php artisan migrate
php artisan db:seed
```

Seeders will populate:
- **Countries**: Uganda (UG), Kenya (KE), Tanzania (TZ)
- **Currencies**: UGX, KES, TZS
- **Roles**: customer, vendor, admin, content_reviewer, logistics_partner, agent

### 5. Build frontend assets

```bash
npm run build
# or for development with HMR:
npm run dev
```

### 6. Create an admin user

```bash
php artisan app:create-admin
```

You will be prompted for name, email, and password. Alternatively pass flags:

```bash
php artisan app:create-admin --name="Admin" --email="admin@example.com" --password="your_secure_password"
```

> **Do not commit passwords.** This command is for local/CI use only.

### 7. Start the development server

```bash
php artisan serve
```

Visit [http://localhost:8000](http://localhost:8000).

---

## Database Schema Overview

The schema is built around the following domain groups:

| Domain               | Tables                                                                                     |
|----------------------|--------------------------------------------------------------------------------------------|
| Multi-country        | `countries`, `currencies`, `regions`, `tax_rules`                                          |
| Identity & Access    | `users`, `roles`, `user_roles`                                                             |
| Vendor & KYC         | `vendors`, `vendor_kyc_documents`, `commission_plans`                                      |
| Catalog              | `categories`, `products`, `product_images`, `skus`, `listing_reviews`                     |
| Inventory            | `locations`, `inventory_balances`, `inventory_movements`                                   |
| Orders & Payments    | `orders`, `order_items`, `payments`, `cod_requests`                                        |
| Logistics            | `carriers`, `shipments`, `shipment_items`, `tracking_events`                               |
| Warehouse (Express)  | `warehouse_pick_waves`, `warehouse_pick_tasks`, `warehouse_pack_tasks`                     |
| Financials           | `escrow_holds`, `ledger_accounts`, `ledger_entries`, `settlement_batches`, `settlement_lines` |
| Returns              | `return_requests`, `refunds`                                                               |
| JForce Agents        | `agent_profiles`, `assisted_orders`, `agent_commissions`                                   |
| LaaS                 | `laas_customers`, `laas_shipments`, `invoices`, `invoice_lines`                            |

### Money fields convention

All monetary values are stored as **integer minor units** (e.g., `price_minor`).  
Currency is always stored as a `currency_code` (ISO 4217, e.g., `KES`).

| Example          | Stored as     |
|------------------|---------------|
| KES 1,500.00     | `150000`      |
| UGX 50,000       | `50000`       |

---

## Business Model Highlights

### Fulfillment types

| Type       | Description                                    | Speed    |
|------------|------------------------------------------------|----------|
| `express`  | Consignment stock stored at Jumia warehouse    | ~24h     |
| `dropship` | Vendor-owned stock; vendor ships to hub        | 48–72h   |

### Order-to-Cash flow

1. Customer places order → `orders` + `order_items` created
2. Payment authorized → `payments` record created; CoD → `cod_requests` risk-scored
3. Express items → `warehouse_pick_waves` / `warehouse_pick_tasks` created  
   Drop-ship items → vendor sees pending shipment
4. Shipment scans update `tracking_events`
5. On delivery → `escrow_holds.delivery_date = T`, `release_date = T+7`
6. Nightly cron releases holds → `settlement_batches` created with deductions (commission, shipping, ads fees)

### Escrow model (T+7)

Funds are held in escrow from payment capture until **7 days after confirmed delivery**. The `escrow_holds` table tracks each order's hold status and release date.

---

## Roles

| Role               | Description                                     |
|--------------------|-------------------------------------------------|
| `customer`         | End consumer                                    |
| `vendor`           | Merchant listing products                       |
| `admin`            | Full platform access                            |
| `content_reviewer` | Reviews/approves product listings               |
| `logistics_partner`| Third-party carrier/logistics access            |
| `agent`            | JForce O2O sales agent (offline-to-online)      |

---

## Key Artisan Commands

```bash
# Create admin user (interactive)
php artisan app:create-admin

# Run all migrations fresh with seed data
php artisan migrate:fresh --seed
```

---

## Roadmap

- [ ] Vendor Center UI (KYC upload, product listing, order management)
- [ ] Customer storefront (browse, cart, checkout)
- [ ] Admin panel (KYC review queue, listing quality gate, logistics monitor)
- [ ] CoD risk scoring engine
- [ ] Escrow release cron job + settlement payout calculation
- [ ] JForce agent assisted-ordering flow
- [ ] LaaS quote API + label purchase
- [ ] Search (Elasticsearch/OpenSearch integration)
