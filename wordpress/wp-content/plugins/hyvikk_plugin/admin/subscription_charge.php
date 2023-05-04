<?php
    if(isset($_POST['save_charge'])){
        $subscription_charge = esc_sql($_POST['subscription_charge']);
        $tax_rate = esc_sql($_POST['tax_rate']);
        
       echo  $subscription_charge;
       echo   $tax_rate;
        if(get_option('subscription_charge',-1)==-1){
            add_option('subscription_charge', $subscription_charge);
        } else {
            update_option('subscription_charge', $subscription_charge);
        }
        
        if(get_option('subscription_tax', -1) == -1){
            add_option('subscription_tax', $tax_rate);
        } else {
            update_option('subscription_tax', $tax_rate);
        }
    }
?>

<div class="wrap">
    <h2>Subscription charges</h2>
    <form action="edit.php?post_type=product&page=subscription-charge" method="post">
        <ul>
            <?php 
                $subscription_charge = get_option('subscription_charge');
                $tax_rate =  get_option('subscription_tax');
                
                
                echo "<li>
                        <label>Enter amount (excluding tax)</label>
                        <input type='number' name='subscription_charge' value='$subscription_charge' min='0'>
                    </li>
                    <li>
                        <label>Enter tax rate (e.g. 0.10 for 10%)</label>
                        <input type='number' name='tax_rate' value='$tax_rate' min='0' max='1' step='0.01'>
                    </li>
                   
                    ";
            ?>
        </ul>
        <br>
        <input type="submit" value="save" name="save_charge">
    </form>
    <br>
    <?php 
        $tax_amount = $subscription_charge * $tax_rate;
        $total_charge = $subscription_charge + $tax_amount;
    ?>
    <li>Tax amount : <?php echo $tax_amount ?></li>
    <li>Total charges : <?php echo $total_charge ?> </li>
</div>
