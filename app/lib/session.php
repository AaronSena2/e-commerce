<?php
/**
 * Session and cart helper functions.
 *
 * Cart structure stored in $_SESSION['cart']:
 *   [
 *     product_id => [
 *       'product_id' => int,
 *       'name'       => string,
 *       'price'      => float,
 *       'quantity'   => int,
 *       'image'      => string,
 *     ],
 *     ...
 *   ]
 */

if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

// ---------------------------------------------------------------------------
// Cart helpers
// ---------------------------------------------------------------------------

/**
 * Returns the entire cart array (keyed by product_id).
 */
function cart_get(): array
{
    return $_SESSION['cart'] ?? [];
}

/**
 * Adds a product to the cart or increments its quantity.
 */
function cart_add(int $product_id, string $name, float $price, string $image, int $qty = 1): void
{
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += $qty;
    } else {
        $_SESSION['cart'][$product_id] = [
            'product_id' => $product_id,
            'name'       => $name,
            'price'      => $price,
            'quantity'   => $qty,
            'image'      => $image,
        ];
    }
}

/**
 * Updates the quantity for a cart item.
 * Removes the item if quantity <= 0.
 */
function cart_update(int $product_id, int $qty): void
{
    if ($qty <= 0) {
        cart_remove($product_id);
        return;
    }

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $qty;
    }
}

/**
 * Removes a product from the cart.
 */
function cart_remove(int $product_id): void
{
    unset($_SESSION['cart'][$product_id]);
}

/**
 * Clears the entire cart.
 */
function cart_clear(): void
{
    $_SESSION['cart'] = [];
}

/**
 * Returns the total number of items (sum of quantities) in the cart.
 */
function cart_count(): int
{
    $count = 0;
    foreach (cart_get() as $item) {
        $count += (int) $item['quantity'];
    }
    return $count;
}

/**
 * Returns the cart grand total (price × quantity for all items).
 */
function cart_total(): float
{
    $total = 0.0;
    foreach (cart_get() as $item) {
        $total += (float) $item['price'] * (int) $item['quantity'];
    }
    return $total;
}
