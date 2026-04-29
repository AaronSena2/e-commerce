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

This creates the `products`, `orders`, and `order_items` tables and inserts 8 sample products.

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
│   │   ├── header.php          # Bootstrap navbar partial
│   │   └── footer.php          # Footer + JS scripts partial
│   ├── lib/
│   │   └── session.php         # Session start + cart helpers
│   └── models/
│       ├── Product.php         # product_get_all(), product_get_by_id()
│       └── Order.php           # order_create(), order_get_by_id(), order_get_items()
├── assets/
│   ├── css/
│   │   └── styles.css          # Minimal custom styles (Bootstrap via CDN)
│   └── js/
│       └── cart.js             # Qty auto-submit, remove confirm, checkout validation
├── public/                     # ← Apache/XAMPP document root for this app
│   ├── index.php               # Product listing grid
│   ├── product.php             # Product detail + add-to-cart
│   ├── cart.php                # Cart view / update / remove
│   ├── checkout.php            # Checkout form → creates order
│   └── order_success.php       # Order confirmation
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
