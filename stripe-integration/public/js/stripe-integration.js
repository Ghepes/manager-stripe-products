document.addEventListener('DOMContentLoaded', function() {
      const addToCartButtons = document.querySelectorAll('.add-to-cart');
      const removeFromCartButtons = document.querySelectorAll('.remove-from-cart');
      const checkoutButton = document.querySelector('.checkout');

      addToCartButtons.forEach(button => {
        button.addEventListener('click', function() {
          const productId = this.getAttribute('data-product-id');
          fetch('/wp-admin/admin-ajax.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'action=stripe_add_to_cart&product_id=' + encodeURIComponent(productId),
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              alert('Product added to cart!');
              location.reload();
            } else {
              alert('Error adding product to cart.');
            }
          });
        });
      });

      removeFromCartButtons.forEach(button => {
        button.addEventListener('click', function() {
          const productId = this.getAttribute('data-product-id');
          fetch('/wp-admin/admin-ajax.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'action=stripe_remove_from_cart&product_id=' + encodeURIComponent(productId),
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              alert('Product removed from cart!');
              location.reload();
            } else {
              alert('Error removing product from cart.');
            }
          });
        });
      });

      if (checkoutButton) {
        checkoutButton.addEventListener('click', function() {
          fetch('/wp-admin/admin-ajax.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'action=stripe_handle_checkout',
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              window.location.href = data.redirect_url;
            } else {
              alert('Error processing checkout.');
            }
          });
        });
      }
    });
