<?php
    function stripe_integration_settings_page() {
      ?>
      <div class="wrap">
        <h1>Stripe Integration Settings</h1>
        <form method="post" action="options.php">
          <?php
          settings_fields('stripe_integration_settings');
          do_settings_sections('stripe-integration-settings');
          submit_button();
          ?>
        </form>
      </div>
      <?php
    }

    function stripe_integration_settings_init() {
      register_setting('stripe_integration_settings', 'stripe_integration_settings');

      add_settings_section(
        'stripe_integration_settings_section',
        __('Stripe API Settings', 'stripe-integration'),
        'stripe_integration_settings_section_callback',
        'stripe-integration-settings'
      );

      add_settings_field(
        'stripe_api_key',
        __('API Key', 'stripe-integration'),
        'stripe_integration_api_key_callback',
        'stripe-integration-settings',
        'stripe_integration_settings_section'
      );
    }
    add_action('admin_init', 'stripe_integration_settings_init');

    function stripe_integration_settings_section_callback() {
      echo __('Enter your Stripe API settings below:', 'stripe-integration');
    }

    function stripe_integration_api_key_callback() {
      $options = get_option('stripe_integration_settings');
      ?>
      <input type="text" name="stripe_integration_settings[stripe_api_key]" value="<?php echo esc_attr($options['stripe_api_key']); ?>" />
      <?php
    }
