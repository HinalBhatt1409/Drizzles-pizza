<?php
add_action( 'show_user_profile', 'add_user_profile_royalty_field' );
add_action( 'edit_user_profile', 'add_user_profile_royalty_field' );
function add_user_profile_royalty_field( $user ) {
?>
    <h3><?php _e("Royalty Amount", "blank"); ?></h3>
    <table class="form-table">
        <tr>
            <th><label for="royalty_amount"><?php _e("Amount", "blank"); ?></label></th>
            <td>
                <input type="number" name="royalty_amount" id="royalty_amount" value="<?php echo esc_attr( get_the_author_meta( 'royalty_amount', $user->ID ) ); ?>" class="regular-text" /><br />
                <span class="description"><?php _e("Please enter the royalty amount for this user.", "blank"); ?></span>
            </td>
        </tr>
    </table>
<?php
}
add_action( 'personal_options_update', 'save_user_profile_royalty_field' );
add_action( 'edit_user_profile_update', 'save_user_profile_royalty_field' );
function save_user_profile_royalty_field( $user_id ) {
    if ( !current_user_can( 'edit_user', $user_id ) ) { 
        return false; 
    }
    update_user_meta( $user_id, 'royalty_amount', $_POST['royalty_amount'] );
}

add_action( 'woocommerce_cart_calculate_fees', 'add_royalty', 10, 1 );
function add_royalty($cart){
    $Royalty_Charges = 'Royalty Charges';
    $user_id = get_current_user_id();
    $royalty_amount_str = get_user_meta( $user_id, 'royalty_amount', true );
    $royalty_amount = floatval( $royalty_amount_str );
    $royalty_tax_rate = 0.18; // 18% tax rate
    $royalty_tax_amount = $royalty_amount * $royalty_tax_rate;
    $royalty_total_amount = $royalty_amount + $royalty_tax_amount;
    $customer_id = get_current_user_id(); // Get the current customer ID.
    
    
    $last_order_date = get_royalty_last_order_date( $customer_id ); // Get the date of the last order for the current customer.
    if ( $last_order_date === false ) {
       // If the customer has never placed an order, add the fee.
       $cart->add_fee( $Royalty_Charges, $royalty_total_amount, true, '' );
       return;
    }// Get the date of the last order.
   $minutes_since_last_order = ( time() - strtotime( $last_order_date ) ) / 60;// Calculate minutes since last order.

   if ( $minutes_since_last_order <= 5  ) {
    $cart->remove_coupon( $Royalty_Charges ); // Remove the fee if the last order was placed within the last 5 minutes.
 } else if($last_order_date == '' ){
    $cart->add_fee( $Royalty_Charges, $royalty_total_amount, true, '' ); // Add the fee if the last order was placed more than 30 days ago.

 }
 else {
    $cart->add_fee( $Royalty_Charges, $royalty_total_amount, true, '' );  // Add the fee if the last order was placed more than 5 minutes ago.
 }

}
function get_royalty_last_order_date($customer_id) {
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

?>