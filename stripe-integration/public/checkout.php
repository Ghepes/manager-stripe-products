<?php
    function stripe_integration_handle_checkout() {
      if (isset($_SESSION['cart'])) {
        $api_key = get_option('stripe_integration_settings')['stripe_api_key'];
        $stripe = new \Stripe\StripeClient($api_key);

        $cart = $_SESSION['cart'];
        $line_items = [];

        foreach ($cart as $product_id => $quantity) {
          $product = $stripe->products->retrieve($product_id);
          $price = $stripe->prices->retrieve($product->default_price);
          $line_items[] = [
            'price' => $price->id,
            'quantity' => $quantity,
          ];
        }

        try {
          $session = $stripe->checkout->sessions->create([
            'payment_method_types' => ['card'],
            'line_items' => $line_items,
            'mode' => 'payment',
            'success_url' => home_url('/checkout-success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => home_url('/checkout-cancel'),
          ]);

          wp_send_json_success(['redirect_url' => $session->url]);
        } catch (\Stripe\Exception\ApiErrorException $e) {
          wp_send_json_error(['message' => $e->getMessage()]);
        }
      } else {
        wp_send_json_error(['message' => 'Cart is empty']);
      }
    }
    ?>
