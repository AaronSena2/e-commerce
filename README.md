# ShopMVP — E-Commerce Starter

A simple, no-login e-commerce MVP built with **HTML + Bootstrap 5 + PHP + MySQL + JavaScript**, ready to run on **XAMPP** (or any Apache + MySQL/MariaDB stack).

---

## Features

| Feature | Details |
|---|---|
| Product listing | Grid view, in-stock badge, live count |
| Product detail | Description, price, stock level, add-to-cart |
| Cart | Session-based; update qty, remove items, running total |
| Checkout | Name, email, phone (opt.), address, city, state, ZIP |
| Order confirmation | Summary table, shipping address, order total |
| **Quote requests** | Clients can request a quote for any product; stored in DB |
| **Admin dashboard** | Metrics cards, recent orders, recent quote requests |
| **Quote management** | Admin can list, filter and update the status of every quote request |
| Security | PDO prepared statements, server-side validation, `htmlspecialchars` output escaping |

---

## Requirements

- [XAMPP](https://www.apachefriends.org/) (Apache 2.4+ & MySQL/MariaDB 5.7+)
- PHP 7.4 or later (PHP 8.x recommended)
- A modern browser

---

## Quick-start (XAMPP on Windows)

### 1. Clone / place the project

```
C:\xampp\htdocs\e-commerce\
```

Either clone directly:

```bash
git clone https://github.com/AaronSena2/e-commerce.git C:\xampp\htdocs\e-commerce
```

Or download the ZIP and extract it to the path above.

### 2. Start XAMPP services

Open the **XAMPP Control Panel** and start:

- **Apache**
- **MySQL**

### 3. Create the database

1. Open [http://localhost/phpmyadmin](http://localhost/phpmyadmin) in your browser.
2. Click **New** (left sidebar).
3. Name the database **`ecommerce`** → **Create**.

### 4. Import the schema + seed data

Inside phpMyAdmin, with the `ecommerce` database selected:

1. Click the **Import** tab.
2. Click **Choose File** → navigate to `C:\xampp\htdocs\e-commerce\sql\schema.sql`.
3. Click **Go**.

This creates the `products`, `orders`, `order_items`, and `quote_requests` tables and inserts 8 sample products.

### 5. Configure database credentials

Open `app/config/config.php` and edit the constants to match your setup:

```php
define('DB_HOST', 'localhost');   // usually localhost
define('DB_NAME', 'ecommerce');   // the DB you just created
define('DB_USER', 'root');        // XAMPP default
define('DB_PASS', '');            // XAMPP default (empty password)
```

> **Note:** If you have set a MySQL root password, enter it for `DB_PASS`.

### 6. Browse to the app

```
http://localhost/e-commerce/public/
```

---

## Project Structure

```
e-commerce/
├── app/
│   ├── config/
│   │   └── config.php          # DB constants + PDO connection helper
│   ├── includes/
│   │   ├── header.php          # Bootstrap navbar partial (Admin dropdown included)
│   │   └── footer.php          # Footer + JS scripts partial
│   ├── lib/
│   │   └── session.php         # Session start + cart helpers
│   └── models/
│       ├── Product.php         # product_get_all(), product_get_by_id()
│       ├── Order.php           # order_create(), order_get_by_id(), order_get_items()
│       └── QuoteRequest.php    # quote_request_create/get_all/get_by_id/update_status/…
├── assets/
│   ├── css/
│   │   └── styles.css          # Minimal custom styles (Bootstrap via CDN)
│   └── js/
│       └── cart.js             # Qty auto-submit, remove confirm, checkout validation
├── public/                     # ← Apache/XAMPP document root for this app
│   ├── index.php               # Product listing grid
│   ├── product.php             # Product detail + add-to-cart + Request a Quote link
│   ├── cart.php                # Cart view / update / remove
│   ├── checkout.php            # Checkout form → creates order
│   ├── order_success.php       # Order confirmation
│   ├── quote_request.php       # Quote request form (accepts ?product_id=)
│   ├── quote_success.php       # Quote request confirmation page
│   └── admin/
│       ├── index.php           # Admin dashboard (metrics + recent activity)
│       └── quotes.php          # Quote requests list with filter + status update
├── sql/
│   └── schema.sql              # CREATE TABLE statements + seed products
└── README.md
```

---

## Database Schema

| Table | Key columns |
|---|---|
| `products` | `id`, `name`, `description`, `price`, `image`, `stock` |
| `orders` | `id`, `name`, `email`, `phone`, `address`, `city`, `state`, `zip`, `total` |
| `order_items` | `id`, `order_id` (FK), `product_id` (FK), `name`, `price`, `quantity` |
| `quote_requests` | `id`, `product_id` (FK), `name`, `email`, `phone`, `quantity`, `message`, `status`, `created_at` |

> **Upgrading an existing database:** If you already imported the old schema, run the following SQL snippet in phpMyAdmin to add the new table:
>
> ```sql
> CREATE TABLE IF NOT EXISTS `quote_requests` (
>     `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
>     `product_id` INT UNSIGNED NOT NULL,
>     `name`       VARCHAR(255)  DEFAULT NULL,
>     `email`      VARCHAR(255)  NOT NULL,
>     `phone`      VARCHAR(50)   DEFAULT NULL,
>     `quantity`   INT UNSIGNED  DEFAULT 1,
>     `message`    TEXT          DEFAULT NULL,
>     `status`     ENUM('new','processing','quoted','closed') NOT NULL DEFAULT 'new',
>     `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
>     PRIMARY KEY (`id`),
>     INDEX `idx_qr_product_id` (`product_id`),
>     INDEX `idx_qr_status`     (`status`),
>     INDEX `idx_qr_created_at` (`created_at`),
>     CONSTRAINT `fk_quote_requests_product`
>         FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT
> ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
> ```

---

## Requesting a Quote

Clients can request a custom quotation for any product without creating an account:

1. Open any product detail page (`/public/product.php?id=…`).
2. Click the **"Request a Quote"** button below the Add-to-Cart form.
3. Fill in the quote request form — **email is required**; name, phone, quantity and message are optional.
4. Click **Submit Request**. The request is saved in the `quote_requests` table and a confirmation page is shown.

---

## Admin Dashboard & Quote Management

| URL | Purpose |
|---|---|
| `http://localhost/e-commerce/public/admin/index.php` | Dashboard — totals, status counts, recent quotes & orders |
| `http://localhost/e-commerce/public/admin/quotes.php` | Full list of quote requests with filter + status update |

The **Admin** dropdown in the navbar provides quick access to both pages.

To change the status of a quote request, open the Quotes page and use the dropdown + **Save** button on any row.

> **Note:** There is no login for the admin area (consistent with the no-auth MVP design). Add an authentication layer before deploying to a public server.

---

## Customisation Tips

- **Add product images** — put image files in `assets/img/` and update the `image` column in the `products` table. Then swap the placeholder `<i>` tag in the card template for an `<img>` tag.
- **Change the shop name** — search for `ShopMVP` in `app/includes/header.php` and `footer.php`.
- **Add more products** — insert rows into the `products` table via phpMyAdmin or extend `schema.sql`.

---

## Security Notes

- All database queries use **PDO prepared statements** — no SQL injection risk.
- All user-supplied data displayed in HTML is passed through **`htmlspecialchars()`**.
- Server-side validation is performed on the checkout form before inserting any data.
- This is an MVP with **no authentication**. Do not expose this app on a public server without adding proper access controls.
