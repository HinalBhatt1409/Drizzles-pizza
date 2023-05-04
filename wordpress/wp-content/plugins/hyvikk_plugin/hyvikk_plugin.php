<?php

/**
 


*Plugin Name:Hyvikk plugin
*Plugin URI: localhost/demo-plugin.com/plugin
*Description: Hyvikk_plugin used for add extra subscripton fees and without login it can not access any page  of website.
*Version: 1.0.0

*Author: Hyvikk
*Author URI: https://hyvikk.com

*/

if(!defined('ABSPATH'))
{
 
  die("");
}

/**
 * Removes some menus by page.
 */
if ( ! function_exists( 'deregister_post_type' ) ) {
    function wpdocs_remove_menus(){
      remove_menu_page( 'index.php' );                  //Dashboard
    //   remove_menu_page( 'jetpack' );                    //Jetpack* 
      remove_menu_page( 'edit.php' );                   //Posts
      remove_menu_page( 'upload.php' );                 //Media
      remove_menu_page( 'edit.php?post_type=page' );    //Pages
      remove_menu_page( 'edit-comments.php' );          //Comments
      //remove_menu_page( 'themes.php' );                 //Appearance
      //remove_menu_page( 'plugins.php' );                //Plugins
    //   remove_menu_page( 'users.php' );                  //Users
      remove_menu_page( 'tools.php' );                  //Tools
    //   remove_menu_page( 'options-general.php' );        //Settings
    }
   }
add_action( 'admin_menu', 'wpdocs_remove_menus' );


   function my_remove_admin_menus($features) {
    //    $analytics = array_search('analytics', $features);
    //    unset($features[$analytics]);
       
       $marketing = array_search('marketing', $features);
       unset($features[$marketing]);
       
       return $features;	 	 
    } 
add_filter( 'woocommerce_admin_features', 'my_remove_admin_menus' );



function restrict_access_to_shop_and_cart_pages() {
    if ( ( is_cart() || is_checkout() || is_shop() ) && ! is_user_logged_in() ) {
        wp_redirect( home_url("my-account"));
        exit;
    }
}
add_action( 'template_redirect', 'restrict_access_to_shop_and_cart_pages' );
  
function redirect_after_logout()
{
  wp_redirect(site_url('my-account'));
  exit;

}
 add_action('wp_logout','redirect_after_logout');

 function remove_admin_bar_items() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('wp-logo'); // Remove the WordPress logo
    $wp_admin_bar->remove_menu('updates'); // Remove the Updates menu
    $wp_admin_bar->remove_menu('comments'); // Remove the Comments menu
    $wp_admin_bar->remove_menu('new-content'); // Remove the New menu
    $wp_admin_bar->remove_menu('customize');
   //  $wp_admin_bar->remove_menu('edit');
    $wp_admin_bar->remove_menu('search');
}
add_action('wp_before_admin_bar_render', 'remove_admin_bar_items');

// Remove Screen Options dropdown
function remove_screen_options() {
    return false;
}
add_filter('screen_options_show_screen', 'remove_screen_options');

function remove_help_tab() {
    // Remove Help dropdown
    $screen = get_current_screen();
    $screen->remove_help_tabs();
}
add_action('admin_head', 'remove_help_tab');

function remove_admin_footer () {
    echo '';
}
add_filter('admin_footer_text', 'remove_admin_footer');
function remove_version() {
    return '';
}
add_filter('update_footer', 'remove_version', 9999);

    function custom_login_redirect( $user ) {
        
        $is_user_logged_in = is_user_logged_in();
        if ( ! $is_user_logged_in && (is_shop() || is_cart() )) {
            // Get the login page URL
            $login_page_url = wp_login_url( get_permalink() );
            // Redirect to the login page URL
            wp_redirect( $login_page_url );
            exit;
            
        }
       
    }
// add_action( 'template_redirect', 'custom_login_redirect');
    

    function update_login_url($login_url, $redirect) {

        return home_url("my-account");
      }
add_filter("login_url", "update_login_url", 10, 2);


function remove_account_links( $menu_links ){
   
   // Remove the "Downloads" link
   unset( $menu_links['downloads'] );
   
   // Remove the "Account Details" link
   unset( $menu_links['edit-account'] );
   
   return $menu_links;
}
add_filter( 'woocommerce_account_menu_items', 'remove_account_links' );




function add_products_menu_entry() {
   add_submenu_page(
       'edit.php?post_type=product',
       __( 'Subscription Charge' ),
       __( 'Subscription Charge' ),
       'manage_woocommerce', // Required user capability
       'subscription-charge',
       'generate_subscription_charge_page'
   );
}

function generate_subscription_charge_page() {
   include 'admin/subscription_charge.php';
}
add_action( 'admin_menu', 'add_products_menu_entry', 100 );

add_action( 'woocommerce_cart_calculate_fees', 'add_custom_fee', 10, 1 );
function add_custom_fee( $cart ) {
   if ( is_admin() && ! defined( 'DOING_AJAX' ) ) return;
   
   $fee_name = 'Subscription Charges'; // Change this to the name of your fee.
   $subscription_charge = get_option( 'subscription_charge' );
   $tax_rate = get_option( 'subscription_tax' );
   $tax_amount = $subscription_charge * $tax_rate;
   $total_charge = $subscription_charge + $tax_amount;
   // Change this to the amount you want to charge.
   
   $last_order_date = get_last_order_date(); // Get the date of the last order.
   $minutes_since_last_order = ( time() - strtotime( $last_order_date ) ) / 60; // Calculate minutes since last order.
   
   if ( $minutes_since_last_order <= 5 ) {
      $cart->remove_coupon( $fee_name ); // Remove the fee if the last order was placed within the last 5 minutes.
   } else {
      $cart->add_fee( $fee_name, $total_charge, true, '' ); // Add the fee if the last order was placed more than 5 minutes ago.
   }
}

function get_last_order_date() {
   global $wpdb;
   $last_order_date = $wpdb->get_var( "
      SELECT post_date
      FROM {$wpdb->posts}
      WHERE post_type = 'shop_order'
      AND post_status IN ( 'wc-completed', 'wc-processing' )
      ORDER BY post_date DESC
      LIMIT 1
   " );
   return $last_order_date;
}

function my_login_redirect( $user_login, $user ) {
   wp_redirect( home_url() );
   exit;
}
add_action( 'wp_login', 'my_login_redirect', 10, 2 );





   ?>

