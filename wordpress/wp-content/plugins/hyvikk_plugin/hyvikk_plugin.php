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

require_once plugin_dir_path( __FILE__ ) . 'includes/royalty_charges.php';


/**
 * Removes some menus by page.
 */
if ( ! function_exists( 'deregister_post_type' ) ) {
    function wpdocs_remove_menus(){

      $user = wp_get_current_user();

      if( in_array('shop_manager', $user->roles)){
        remove_menu_page( 'edit.php?post_type=product' ); 
        remove_menu_page( 'users.php' ); // hide Users menu item
      }
      remove_menu_page( 'index.php' );                  //Dashboard
    //   remove_menu_page( 'jetpack' );                    //Jetpack* 
      remove_menu_page( 'edit.php' );                   //Posts
     // remove_menu_page( 'upload.php' );                 //Media
     // remove_menu_page( 'edit.php?post_type=page' );    //Pages
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

// add_action( 'woocommerce_cart_calculate_fees', 'add_custom_fee', 10, 1 );
// function add_custom_fee( $cart ) {
//    if ( is_admin() && ! defined( 'DOING_AJAX' ) ) return;
   
//    $fee_name = 'Subscription Charges'; // Change this to the name of your fee.
//    $subscription_charge = get_option( 'subscription_charge' );
//    $tax_rate = get_option( 'subscription_tax' );
//    $tax_amount = $subscription_charge * $tax_rate;
//    $total_charge = $subscription_charge + $tax_amount;
//    // Change this to the amount you want to charge.
   
//    $last_order_date = get_last_order_date(); // Get the date of the last order.
//    $minutes_since_last_order = ( time() - strtotime( $last_order_date ) ) / 60; // Calculate minutes since last order.
   
//    if ( $minutes_since_last_order <= 5 ) {
//       $cart->remove_coupon( $fee_name ); // Remove the fee if the last order was placed within the last 5 minutes.
//    } else {
//       $cart->add_fee( $fee_name, $total_charge, true, '' ); // Add the fee if the last order was placed more than 5 minutes ago.
//    }
// }

// function get_last_order_date() {
//    global $wpdb;
//    $last_order_date = $wpdb->get_var( "
//       SELECT post_date
//       FROM {$wpdb->posts}
//       WHERE post_type = 'shop_order'
//       AND post_status IN ( 'wc-completed', 'wc-processing' )
//       ORDER BY post_date DESC
//       LIMIT 1
//    " );
//    return $last_order_date;
// }

add_action( 'woocommerce_cart_calculate_fees', 'add_custom_fee', 10, 1 );
function add_custom_fee( $cart ) {
   if ( is_admin() && ! defined( 'DOING_AJAX' ) ) return;

   $fee_name = 'Subscription Charges'; // Change this to the name of your fee.
   $subscription_charge = get_option( 'subscription_charge' );
   $tax_rate = get_option( 'subscription_tax' );
   $tax_amount = $subscription_charge * $tax_rate;
   $total_charge = $subscription_charge + $tax_amount;
   // Change this to the amount you want to charge.

   $customer_id = get_current_user_id(); // Get the current customer ID.
   $last_order_date = get_last_order_date($customer_id); // Get the date of the last order for the current customer.
   $days_since_last_order = ( time() - strtotime( $last_order_date ) ) / 60; // Calculate days since last order.

   if ( $days_since_last_order <= 5 ) {
      $cart->remove_coupon( $fee_name ); // Remove the fee if the last order was placed within the last 30 days or if it's the customer's first order.
   } else if($last_order_date == '' ){
      $cart->add_fee( $fee_name, $total_charge, true, '' ); // Add the fee if the last order was placed more than 30 days ago.
   }
   else{
      $cart->add_fee( $fee_name, $total_charge, true, '' );
   }
}

function get_last_order_date($customer_id) {
   global $wpdb;
   $last_order_date = $wpdb->get_var( $wpdb->prepare( "
      SELECT post_date
      FROM {$wpdb->posts} p
      JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
      WHERE p.post_type = 'shop_order'
      AND p.post_status IN ( 'wc-completed', 'wc-processing' )
      AND pm.meta_key = '_customer_user'
      AND pm.meta_value = %d
      ORDER BY p.post_date DESC
      LIMIT 1
   ", $customer_id ) );
   return $last_order_date;
}

// add_action( 'woocommerce_cart_calculate_fees', 'add_custom_fee', 10, 1 );
// function add_custom_fee( $cart ) {
//    if ( is_admin() && ! defined( 'DOING_AJAX' ) ) return;
   
//    $fee_name = 'Subscription Charges'; // Change this to the name of your fee.
//    $subscription_charge = get_option( 'subscription_charge' );
//    $tax_rate = get_option( 'subscription_tax' );
//    $tax_amount = $subscription_charge * $tax_rate;
//    $total_charge = $subscription_charge + $tax_amount;
//    // Change this to the amount you want to charge.
   
//    $customer_id = get_current_user_id(); // Get the current customer ID.
//    if ( $customer_id === 0 ) {
//       // If the customer is not logged in, add the fee.
//       $cart->add_fee( $fee_name, $total_charge, true, '' );
//       return;
//    }
   
//    $last_order_date = get_last_order_date( $customer_id ); // Get the date of the last order for the current customer.
//    if ( $last_order_date === false ) {
//       // If the customer has never placed an order, add the fee.
//       $cart->add_fee( $fee_name, $total_charge, true, '' );
//       return;
//    }
   
//    $minutes_since_last_order = ( time() - strtotime( $last_order_date ) ) / 60; // Calculate minutes since last order.
   
//    if ( $minutes_since_last_order <= 5 || $last_order_date == '' ) {
//       $cart->remove_coupon( $fee_name ); // Remove the fee if the last order was placed within the last 5 minutes.
    
//    } else {
//       $cart->add_fee( $fee_name, $total_charge, true, '' ); // Add the fee if the last order was placed more than 5 minutes ago.
//    }
// }

// function get_last_order_date( $customer_id ) {
//    global $wpdb;
//    $last_order_date = $wpdb->get_var( $wpdb->prepare( "
//       SELECT post_date
//       FROM {$wpdb->posts}
//       WHERE post_type = 'shop_order'
//       AND post_status IN ( 'wc-completed', 'wc-processing' )
//       AND post_author = %d
//       ORDER BY post_date DESC
//       LIMIT 1
//    ", $customer_id ) );
//    return $last_order_date ? $last_order_date : false;
// }

// $fee_name = 'Subscription Charges'; // Change this to the name of your fee.
// $subscription_charge = get_option( 'subscription_charge' );
// $tax_rate = get_option( 'subscription_tax' );
// $tax_amount = $subscription_charge * $tax_rate;
// $total_charge = $subscription_charge + $tax_amount;

// $customer_id = get_current_user_id(); 
// $args = array(
//   'status'         => array( 'wc-completed', 'wc-processing', 'pending', 'on-hold', 'failed', 'cancelled' ), // Set the order statuses
//   'type'           => 'shop_order', // Set the order type
//   'date_created'   => '>=' . (time() - 300), // Set the time range for the orders (in seconds)
//   'limit'          => -1, // Retrieve all orders
//   'return'         => 'ids', // Retrieve only order IDs
//   'customer_id'    => $customer_id // Set a default customer ID
// );

// $query = new WP_Query( $args );

// // If there is an order placed within the last 5 minutes, remove the fee from the cart
// if ( $query->have_posts() ) {
//     $cart->remove_fee( $fee_name );
// } else {
//     // Otherwise, add the fee to the cart
//     $cart->add_fee( $fee_name, $total_charge, true, '' );
// }




function my_login_redirect( $user_login, $user ) {
   wp_redirect( home_url() );
   exit;
}
add_action( 'wp_login', 'my_login_redirect', 10, 2 );

//remove coupon functionality
add_filter( 'woocommerce_coupons_enabled', '__return_false' );


//FOR INCLUDE JS FILE
function remove_update_cart_js()
{
  $path_script=plugins_url('assets/js/remove_update_cart.js',__FILE__);
  
  $dep=array('jquery');
        //   include js only for cart page
      if(is_page('cart')){
        wp_enqueue_script('remove-update-cart-js',$path_script,$dep,'',true);

      }

}
add_action('wp_enqueue_scripts','remove_update_cart_js'); //for fronend page



    

function bootstrap_css_cdn()
{
    echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">';
}
add_action('wp_head', 'bootstrap_css_cdn');


function bootstrap_js_cdn()
{
    
    
	echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>';
}
add_action('wp_footer', 'bootstrap_js_cdn',5);

add_filter( 'woocommerce_order_button_text', 'change_place_order_button_text', 10, 1 );

function change_place_order_button_text( $button_text ) {
    $button_text = __( 'Make Payment and Click to Continue', 'woocommerce' );
    return $button_text;
}

//FOR ADD CSS FILE
function place_order_btn()
{
  $path_style_place_order_btn=plugins_url('assets/css/place_order_btn.css',__FILE__);
  wp_enqueue_style('place-order-btn-style',$path_style_place_order_btn,false);

 

}
add_action('wp_enqueue_scripts','place_order_btn');

   ?>

