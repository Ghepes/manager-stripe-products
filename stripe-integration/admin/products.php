<?php
    function stripe_integration_add_products_page() {
      ?>
      <div class="wrap">
        <h1>Add Products to Stripe</h1>
        <form method="post" action="">
          <table class="form-table">
            <tr valign="top">
              <th scope="row">Product Name</th>
              <td><input type="text" name="product_name" /></td>
            </tr>
            <tr valign="top">
              <th scope="row">Product Description</th>
              <td><textarea name="product_description"></textarea></td>
            </tr>
            <tr valign="top">
              <th scope="row">Product Price (in cents)</th>
              <td><input type="number" name="product_price" /></td>
            </tr>
            <tr valign="top">
              <th scope="row">Product Image URL</th>
              <td><input type="text" name="product_image" /></td>
            </tr>
            <tr valign="top">
              <th scope="row">Product Code</th>
              <td><input type="text" name="product_code" /></td>
            </tr>
          </table>
          <?php submit_button(); ?>
        </form>
      </div>
      <?php
    }

    function stripe_integration_add_product() {
      if (isset($_POST['product_name'])) {
        $api_key = get_option('stripe_integration_settings')['stripe_api_key'];
        $product_name = sanitize_text_field($_POST['product_name']);
        $product_description = sanitize_textarea_field($_POST['product_description']);
        $product_price = intval($_POST['product_price']);
        $product_image = esc_url_raw($_POST['product_image']);
        $product_code = sanitize_text_field($_POST['product_code']);

        $stripe = new \Stripe\StripeClient($api_key);

        try {
          $product = $stripe->products->create([
            'name' => $product_name,
            'description' => $product_description,
            'images' => [$product_image],
            'metadata' => [
              'product_code' => $product_code,
            ],
          ]);

          $stripe->prices->create([
            'product' => $product->id,
            'unit_amount' => $product_price,
            'currency' => 'usd',
          ]);

          echo '<div class="notice notice-success is-dismissible"><p>Product added successfully!</p></div>';
        } catch (\Stripe\Exception\ApiErrorException $e) {
          echo '<div class="notice notice-error is-dismissible"><p>Error adding product: ' . esc_html($e->getMessage()) . '</p></div>';
        }
      }
    }
    add_action('admin_post_nopriv_stripe_integration_add_product', 'stripe_integration_add_product');
    add_action('admin_post_stripe_integration_add_product', 'stripe_integration_add_product');
