<?php

/**
 * Provide a plugin setting area view for the plugin
 *
 * @link       https://dataplans.io/
 * @since      1.0.0
 *
 * @package    Dataplans
 * @subpackage Dataplans/admin/partials
 */
    
    global $wpdb;
    $all_orders = $wpdb->get_col("SELECT ID FROM $wpdb->posts WHERE post_type='shop_order' AND post_status != 'wc-cancelled' AND post_status != 'wc-failed' AND post_status != 'wc-refunded'");                       
    //$email = WC()->mailer()->emails['WC_Email_Customer_Completed_Order_Api'];
		//$email->trigger( 36, wc_get_order(36) );
   // WC()->mailer()->emails['WC_Email_Customer_Completed_Order_Api']->trigger( "36", wc_get_order(36) );
?>
        <script type='text/javascript' src='https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js'></script>
        <link rel='stylesheet' href='https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css' media='all' />
    
<div class="wrap dataplans">
	<?php settings_errors(); ?>
    <div class="dpio-content">
        <h1><?php _e('DataPlans.io History', DataplansConst::I18N_NAME) ?></h1>


        <table id="api_dataplans_orders_list" style="width:100%">
            <thead>
                <tr>
                    <th>Purchase</th>
                    <th>Date</th>
                    <th>WC OID</th>
                    <th>Status</th>
                    <th>Plan</th>
                    <th>Price</th>
                    <th>Phone</th>
                    <th>Serial</th>
                    <th>Balance</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
        <?php 
            if(isset($all_orders[0])){
                //var_dump(count($all_orders));
                foreach ($all_orders as $order_id) {
                    $product_plan_purchase_arr = get_metadata('post',$order_id,'selected_api_product_plan_purchase_array',true);
                   // echo $order_id.' before condition<pre>';print_r($product_plan_purchase_arr);echo'</pre>';
                    if(isset($product_plan_purchase_arr->purchase)){
                      //  echo $order_id.' IN condition<pre>';print_r($product_plan_purchase_arr);echo'</pre>';
                        $order = wc_get_order( $order_id );
                        $get_status = $order->get_status();
                       // $order_get_items = $order->get_items();
                ?>
                        <tr>
                            <td style="text-align:center"><a target="_blank" href="https://esims.dataplans.io/dashboard/purchases/<?php echo $product_plan_purchase_arr->purchase->purchaseId ?>">View Purchase</a></td>
                            <td style="text-align:center"><?php echo date('Y-m-d h:i a',strtotime($product_plan_purchase_arr->purchase->purchasedAt))?></td>
                            <td style="text-align:center"><?php echo $order_id ?></td>
                            <td style="text-align:center"><?php echo ucfirst($get_status) ?></td>
                            <td style="text-align:center"><?php echo $product_plan_purchase_arr->purchase->planName ?></td>
                            <td style="text-align:center"><?php echo $product_plan_purchase_arr->purchase->currency.' '.$product_plan_purchase_arr->purchase->paid ?></td>
                            <td style="text-align:center"><?php echo $product_plan_purchase_arr->purchase->esim->phone ?></td>
                            <td style="text-align:center"><?php echo $product_plan_purchase_arr->purchase->esim->serial ?></td>
                            <td style="text-align:center"><?php echo $product_plan_purchase_arr->availableBalance?></td>
                            <td style="text-align:center"><a title="Resend Order Email" href="?page=dpio-history&dataplan_action=resend_email&oid=<?php echo $order_id ?>"><span class="dashicons dashicons-email"></span></a>  <a title="View QR Code" target="_blank" href="<?php echo $product_plan_purchase_arr->purchase->esim->qrCodeDataUrl ?>><span class="dashicons dashicons-media-code"></span></a></td>
                        </tr>
        <?php 
                    } // if(product_plan_purchase_arr->purchase)
                } // foreach ($all_orders as $order_id) {
            } //if(isset($all_orders[0])){
                
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Purchase</th>
                    <th>WC OID</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Plan Slug</th>
                    <th>Price</th>
                    <th>Phone</th>
                    <th>Serial</th>
                    <th>Balance</th>
                    <th>Action</th>
                </tr>
            </tfoot>
        </table>

    <script>
        jQuery(document).ready(function() {
            jQuery('#api_dataplans_orders_list').DataTable();
            setTimeout(function(){jQuery(".dataTables_length").attr("style","display:none")}, 100);
            setTimeout(function(){jQuery(".dataTables_length").attr("style","display:none")}, 500);
            setTimeout(function(){jQuery(".dataTables_length").attr("style","display:none")}, 1000);
            setTimeout(function(){jQuery(".dataTables_length").attr("style","display:none")}, 5000);
        }); // ready
    </script>

    </div>
</div>