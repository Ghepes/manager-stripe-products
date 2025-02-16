<?php
    function stripe_integration_display_products() {
      $api_key = get_option('stripe_integration_settings')['stripe_api_key'];
      $stripe = new \Stripe\StripeClient($api_key);

      try {
        $products = $stripe->products->all(['limit' => 10]);

        echo '<div class="stripe-products">';
        foreach ($products->data as $product) {
          $price = $stripe->prices->retrieve($product->default_price);
          echo '<div class="stripe-product">';
          echo '<img src="' . esc_url($product->images[0]) . '" alt="' . esc_attr($product->name) . '" />';
          echo '<h2>' . esc_html($product->name) . '</h2>';
          echo '<p>' . esc_html($product->description) . '</p>';
          echo '<p>Price: $' . esc_html($price->unit_amount_decimal) . '</p>';
          echo '<button class="add-to-cart" data-product-id="' . esc_attr($product->id) . '">Add to Cart</button>';
          echo '</div>';
        }
        echo '</div>';
      } catch (\Stripe\Exception\ApiErrorException $e) {
        echo '<div class="notice notice-error is-dismissible"><p>Error fetching products: ' . esc_html($e->getMessage()) . '</p></div>';
      }
    }
    add_shortcode('stripe_products', 'stripe_integration_display_products');
