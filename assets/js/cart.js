/**
 * cart.js — lightweight cart UX helpers
 *
 * Features:
 *  • Auto-submit quantity input on change (cart.php update form).
 *  • Confirm before removing an item.
 *  • Update cart-count badge in the navbar without a full reload
 *    (relies on data-product-id attributes set by PHP).
 */

document.addEventListener('DOMContentLoaded', function () {

    // ------------------------------------------------------------------
    // Auto-submit quantity spinners on cart page
    // ------------------------------------------------------------------
    document.querySelectorAll('.qty-input').forEach(function (input) {
        input.addEventListener('change', function () {
            var form = this.closest('form');
            if (form) {
                form.submit();
            }
        });
    });

    // ------------------------------------------------------------------
    // Confirm item removal
    // ------------------------------------------------------------------
    document.querySelectorAll('.btn-remove-item').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            if (!confirm('Remove this item from your cart?')) {
                e.preventDefault();
            }
        });
    });

    // ------------------------------------------------------------------
    // Quantity +/- buttons (optional enhanced UX)
    // ------------------------------------------------------------------
    document.querySelectorAll('.btn-qty-dec').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var input = document.querySelector(this.dataset.target);
            if (input) {
                var val = parseInt(input.value, 10);
                if (val > 1) {
                    input.value = val - 1;
                    input.dispatchEvent(new Event('change'));
                }
            }
        });
    });

    document.querySelectorAll('.btn-qty-inc').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var input = document.querySelector(this.dataset.target);
            if (input) {
                var val = parseInt(input.value, 10);
                input.value = val + 1;
                input.dispatchEvent(new Event('change'));
            }
        });
    });

    // ------------------------------------------------------------------
    // Client-side checkout form validation feedback
    // ------------------------------------------------------------------
    var checkoutForm = document.getElementById('checkout-form');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function (e) {
            if (!checkoutForm.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            checkoutForm.classList.add('was-validated');
        });
    }
});
