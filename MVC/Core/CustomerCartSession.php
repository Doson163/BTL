<?php
/** Keep the active cart isolated from other customer accounts in the same browser session. */
class CustomerCartSession {
    public static function sync($customerId = null) {
        if (!isset($_SESSION['shop_carts']) || !is_array($_SESSION['shop_carts'])) {
            $_SESSION['shop_carts'] = [];
        }

        $customerId = $customerId === null ? null : (string)$customerId;
        $owner = isset($_SESSION['shop_cart_owner']) ? (string)$_SESSION['shop_cart_owner'] : null;
        $currentCart = isset($_SESSION['shop_cart']) && is_array($_SESSION['shop_cart'])
            ? $_SESSION['shop_cart']
            : [];

        if ($owner !== null && $owner !== '') {
            $_SESSION['shop_carts'][$owner] = $currentCart;
        }

        if ($customerId === null || $customerId === '') {
            $_SESSION['shop_cart'] = [];
            unset($_SESSION['shop_cart_owner']);
            return;
        }

        if ($owner !== $customerId) {
            // Do not assign an unowned legacy session cart to the next account.
            $_SESSION['shop_cart'] = $_SESSION['shop_carts'][$customerId] ?? [];
            $_SESSION['shop_cart_owner'] = $customerId;
        } else {
            $_SESSION['shop_cart'] = $currentCart;
        }
    }

    public static function saveActive() {
        $owner = (string)($_SESSION['shop_cart_owner'] ?? '');
        if ($owner === '') return;
        if (!isset($_SESSION['shop_carts']) || !is_array($_SESSION['shop_carts'])) {
            $_SESSION['shop_carts'] = [];
        }
        $_SESSION['shop_carts'][$owner] = isset($_SESSION['shop_cart']) && is_array($_SESSION['shop_cart'])
            ? $_SESSION['shop_cart']
            : [];
    }

    public static function activate($customerId) {
        self::sync($customerId);
    }

    public static function count() {
        return array_sum(isset($_SESSION['shop_cart']) && is_array($_SESSION['shop_cart']) ? $_SESSION['shop_cart'] : []);
    }
}
