<?php
/**
 * Plugin Name: Test PayPal SHA-256
 * Description: Test the IPN endpoint to see if your server can connect.  PayPal changes will be implemented Sept 30, 2015.
 * Version: 1.0.0
 * Author: Jpop @ Gravitate
 * Requires at least: 4.1
 * Tested up to: 4.3.1
 */
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'wp_loaded', 'grav_paypal_sandbox_ipn_tester' );
add_action( 'admin_bar_menu', 'grav_add_test_button', 999 );

function grav_paypal_sandbox_ipn_tester() {

    if ( !empty( $_GET['ipn-test'] ) && current_user_can( 'manage_options' ) ) {

      $response = wp_safe_remote_post( 'https://www.sandbox.paypal.com/cgi-bin/webscr', array(
        'body'       => array(
            'test_ipn' => 1,
            'cmd'      => '_notify-validate'
        )
      ) );


    if ( !is_wp_error( $response ) ) {
        wp_die( 'Test successful!  No changes need to be made.' );
      } else {
        wp_die( 'Test failed.  You will need to update your hosting environment.  Failure response: ' . $response->get_error_message() );
      }
    }

}



function grav_add_test_button() {
  global $wp_admin_bar;
  if ( !is_super_admin() || !is_admin_bar_showing() )
      return;

    $wp_admin_bar->add_node(
        array( 'id' => 'ipn-test',
            'title' => __('Test PayPal SHA-256'),
            'href' => '?ipn-test=1'
        )
    );

}
