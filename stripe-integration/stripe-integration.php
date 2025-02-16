<?php
    /*
    Plugin Name: Stripe Integration
    Description: A plugin to integrate Stripe products into WordPress.
    Version: 1.0
    Author: Codeuiapp
    */

    if (!defined('ABSPATH')) {
      exit; // Exit if accessed directly
    }

    // Include necessary files
    require_once plugin_dir_path(__FILE__) . 'admin/settings.php';
    require_once plugin_dir_path(__FILE__) . 'admin/products.php';
    require_once plugin_dir_path(__FILE__) . 'public/products.php';
    require_once plugin_dir_path(__FILE__) . 'public/cart.php';
    require_once plugin_dir_path(__FILE__) . 'public/checkout.php';

    // Register settings and admin pages
    function stripe_integration_admin_menu() {
      add_menu_page(
        'Stripe Integration',
        'Stripe Integration',
        'manage_options',
        'stripe-integration',
        'stripe_integration_settings_page',
        'dashicons-cart',
        6
      );

      add_submenu_page(
        'stripe-integration',
        'Add Products',
        'Add Products',
        'manage_options',
        'stripe-integration-add-products',
        'stripe_integration_add_products_page'
      );
    }
    add_action('admin_menu', 'stripe_integration_admin_menu');

    // Enqueue scripts and styles
    function stripe_integration_enqueue_scripts() {
      wp_enqueue_script('stripe-js', 'https://js.stripe.com/v3/', array(), null, true);
      wp_enqueue_script('stripe-integration-js', plugin_dir_url(__FILE__) . 'public/js/stripe-integration.js', array('jquery'), null, true);
      wp_enqueue_style('stripe-integration-css', plugin_dir_url(__FILE__) . 'public/css/stripe-integration.css');
    }
    add_action('wp_enqueue_scripts', 'stripe_integration_enqueue_scripts');

    // AJAX actions
    add_action('wp_ajax_stripe_add_to_cart', 'stripe_integration_add_to_cart');
    add_action('wp_ajax_nopriv_stripe_add_to_cart', 'stripe_integration_add_to_cart');

    add_action('wp_ajax_stripe_remove_from_cart', 'stripe_integration_remove_from_cart');
    add_action('wp_ajax_nopriv_stripe_remove_from_cart', 'stripe_integration_remove_from_cart');

    add_action('wp_ajax_stripe_handle_checkout', 'stripe_integration_handle_checkout');
    add_action('wp_ajax_nopriv_stripe_handle_checkout', 'stripe_integration_handle_checkout');

    // Add to cart
    function stripe_integration_add_to_cart() {
      if (isset($_POST['product_id'])) {
        $product_id = sanitize_text_field($_POST['product_id']);
        if (!isset($_SESSION['cart'])) {
          $_SESSION['cart'] = [];
        }
        if (isset($_SESSION['cart'][$product_id])) {
          $_SESSION['cart'][$product_id]++;
        } else {
          $_SESSION['cart'][$product_id] = 1;
        }
        wp_send_json_success();
      } else {
        wp_send_json_error();
      }
    }

    // Remove from cart
    function stripe_integration_remove_from_cart() {
      if (isset($_POST['product_id'])) {
        $product_id = sanitize_text_field($_POST['product_id']);
        if (isset($_SESSION['cart'][$product_id])) {
          unset($_SESSION['cart'][$product_id]);
          wp_send_json_success();
        } else {
          wp_send_json_error();
        }
      } else {
        wp_send_json_error();
      }
    }
    ?>
