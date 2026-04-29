-- ============================================================
-- E-Commerce MVP Schema
-- Database: ecommerce
-- ============================================================

CREATE DATABASE IF NOT EXISTS `ecommerce`
    DEFAULT CHARACTER SET utf8mb4
    DEFAULT COLLATE utf8mb4_unicode_ci;

USE `ecommerce`;

-- ------------------------------------------------------------
-- products
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `products` (
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`        VARCHAR(255) NOT NULL,
    `description` TEXT,
    `price`       DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    `image`       VARCHAR(255) NOT NULL DEFAULT 'placeholder.jpg',
    `stock`       INT UNSIGNED NOT NULL DEFAULT 0,
    `created_at`  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- orders
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `orders` (
    `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`         VARCHAR(255) NOT NULL,
    `email`        VARCHAR(255) NOT NULL,
    `phone`        VARCHAR(50)  DEFAULT NULL,
    `address`      VARCHAR(255) NOT NULL,
    `city`         VARCHAR(100) NOT NULL,
    `state`        VARCHAR(100) NOT NULL,
    `zip`          VARCHAR(20)  NOT NULL,
    `total`        DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    `created_at`   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- order_items
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `order_items` (
    `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `order_id`   INT UNSIGNED NOT NULL,
    `product_id` INT UNSIGNED NOT NULL,
    `name`       VARCHAR(255) NOT NULL,
    `price`      DECIMAL(10, 2) NOT NULL,
    `quantity`   INT UNSIGNED NOT NULL DEFAULT 1,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_order_items_order`
        FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_order_items_product`
        FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Seed products
-- ------------------------------------------------------------
INSERT INTO `products` (`name`, `description`, `price`, `image`, `stock`) VALUES
('Wireless Bluetooth Headphones',
 'Premium over-ear headphones with active noise cancellation, 30-hour battery life, and foldable design. Compatible with all Bluetooth devices.',
 79.99, 'headphones.jpg', 50),

('USB-C Laptop Stand',
 'Ergonomic aluminum laptop stand with adjustable height and angle. Improves posture and keeps your desk tidy. Folds flat for portability.',
 34.99, 'laptop-stand.jpg', 120),

('Mechanical Keyboard',
 'Compact TKL mechanical keyboard with Cherry MX Brown switches. RGB backlighting, detachable USB-C cable, and durable PBT keycaps.',
 129.99, 'keyboard.jpg', 35),

('Wireless Mouse',
 'Ergonomic wireless mouse with silent clicks, 18-month battery life, and a nano USB receiver. Works on virtually any surface.',
 24.99, 'mouse.jpg', 200),

('4K Webcam',
 'Ultra-HD webcam with built-in ring light, dual microphones with noise reduction, and universal clip mount. Plug-and-play USB.',
 89.99, 'webcam.jpg', 45),

('Smart Phone Stand',
 'Adjustable aluminium phone holder for desk. 360° rotation, compatible with all smartphones and small tablets.',
 14.99, 'phone-stand.jpg', 300),

('Cable Management Kit',
 'Set of 60 reusable velcro cable ties + 10 adhesive cable clips to keep your workspace organized.',
 9.99, 'cable-kit.jpg', 500),

('Portable Charger 20000mAh',
 'High-capacity power bank with USB-C PD 65W and dual USB-A ports. Fast-charges laptops, phones, and tablets simultaneously.',
 49.99, 'powerbank.jpg', 80);
