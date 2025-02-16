<?php
    function stripe_integration_display_cart() {
      if (isset($_SESSION['cart'])) {
        $cart = $_SESSION['cart'];
        $api_key = get_option('stripe_integration_settings')['stripe_api_key'];
        $stripe = new \Stripe\StripeClient($api_key);

        echo '<div class="stripe-cart">';
        echo '<h2>Your Cart</h2>';
        echo '<ul>';
        $total = 0;
        foreach ($cart as $product_id => $quantity) {
          $product = $stripe->products->retrieve($product_id);
          $price = $stripe->prices->retrieve($product->default_price);
          $item_total = $price->unit_amount_decimal * $quantity;
          $total += $item_total;
          echo '<li>';
          echo '<img src="' . esc_url($product->images[0]) . '" alt="' . esc_attr($product->name) . '" />';
          echo '<h3>' . esc_html($product->name) . '</h3>';
          echo '<p>' . esc_html($product->description) . '</p>';
          echo '<p>Price: $' . esc_html($price->unit_amount_decimal) . '</p>';
          echo '<p>Quantity: ' . esc_html($quantity) . '</p>';
          echo '<p>Total: $' . esc_html($item_total) . '</p>';
          echo '<button class="remove-from-cart" data-product-id="' . esc_attr($product_id) . '">Remove</button>';
          echo '</li>';
        }
        echo '</ul>';
        echo '<h3>Total: $' . esc_html($total) . '</h3>';
        echo '<button class="checkout">Checkout</button>';
        echo '</div>';
      } else {
        echo '<div class="stripe-cart">';
        echo '<h2>Your Cart is empty</h2>';
        echo '</div>';
      }
    }
    add_shortcode('stripe_cart', 'stripe_integration_display_cart');
    ?>
