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

    $settings_arr = get_option("dpio_options");

    $fromdate = isset($settings_arr['dp_history_daterange_from']) && trim($settings_arr['dp_history_daterange_from']) != '' ? date('Y-m-d H:i:s',strtotime($settings_arr['dp_history_daterange_from'])) : date('Y-m-d H:i:s', strtotime('-7 days'));
    $tilldate = isset($settings_arr['dp_history_daterange_till']) && trim($settings_arr['dp_history_daterange_till']) != '' ? date('Y-m-d H:i:s',strtotime($settings_arr['dp_history_daterange_till'])) : date('Y-m-d H:i:s');
    
    global $wpdb;
    $records_perpage = 1000;
    $viewedPageNum = isset($_GET['bulk']) && $_GET['bulk'] >= 1 ? $_GET['bulk'] : 1 ;
    $recordsFrom = ($records_perpage * $viewedPageNum) - $records_perpage;

    $all_orders = $wpdb->get_col("SELECT p.ID FROM $wpdb->posts p,$wpdb->postmeta pm WHERE p.post_type='shop_order' AND p.post_status != 'wc-cancelled' AND p.post_status != 'wc-failed' AND p.post_status != 'wc-refunded' AND p.post_date >= '$fromdate' AND p.post_date <= '$tilldate' AND p.ID=pm.post_id AND pm.meta_key='selected_api_product_plan_purchase_array' ORDER BY p.ID DESC LIMIT $recordsFrom, $records_perpage");
  
?>

<div class="wrap dataplans">
	<?php settings_errors(); ?>
    <div class="dpio-content">
        <h1><?php _e('DataPlans.io History', DataplansConst::I18N_NAME) ?></h1>
        <p>
            <a class="button button-primary" href="<?php echo admin_url('admin.php?page=dpio-history&bulk='.($viewedPageNum > 1 ? $viewedPageNum-1 : 1))?>"> << Previous Bulk Records </a>&nbsp;&nbsp; | &nbsp;&nbsp;<a class="button button-primary" href="<?php echo admin_url('admin.php?page=dpio-history&bulk='.($viewedPageNum+1))?>"> Next Bulk Records >> </a>
        </p>
        <table id="api_dataplans_orders_list" style="width:100%">
            <thead>
                <tr>
                    <th><?php esc_html_e("Date",'dataplans')?></th>
                    <th><?php esc_html_e("WC OID",'dataplans')?></th>
                    <th><?php esc_html_e("Status",'dataplans')?></th>
                    <th><?php esc_html_e("Plan",'dataplans')?></th>
                    <th><?php esc_html_e("Price",'dataplans')?></th>
                    <th><?php esc_html_e("Phone",'dataplans')?></th>
                    <th><?php esc_html_e("Serial",'dataplans')?></th>
                    <th><?php esc_html_e("Balance",'dataplans')?></th>
                    <th><?php esc_html_e("Action",'dataplans')?></th>
                </tr>
            </thead>
            <tbody>
        <?php
            if(isset($all_orders[0])){
                foreach ($all_orders as $order_id) {
                    $product_plan_purchase_arr = get_metadata('post',$order_id,'selected_api_product_plan_purchase_array',true);
                    if(isset($product_plan_purchase_arr->purchase)){
                        $order = wc_get_order( $order_id );
                        $get_status = $order->get_status();
                ?>
                        <tr>
                            <?php printf('<td style="text-align:center">%s</td>',date('Y-m-d h:i a',strtotime($product_plan_purchase_arr->purchase->purchasedAt)))?>
                            <?php printf('<td style="text-align:center"><a title="'.__('View Order','dataplans').'" href="./post.php?action=edit&post=%s">%s</a></td>',$order_id,$order_id)?>
                            <?php printf('<td style="text-align:center">%s</td>',ucfirst($get_status))?>
                            <?php printf('<td style="text-align:center">%s</td>',$product_plan_purchase_arr->purchase->planName)?>
                            <?php printf('<td style="text-align:center">%s</td>',$product_plan_purchase_arr->purchase->currency.' '.$product_plan_purchase_arr->purchase->paid)?>
                            <?php printf('<td style="text-align:center">%s</td>',$product_plan_purchase_arr->purchase->esim->phone)?>
                            <?php printf('<td style="text-align:center">%s</td>',$product_plan_purchase_arr->purchase->esim->serial)?>
                            <?php printf('<td style="text-align:center">%s</td>',$product_plan_purchase_arr->availableBalance)?>
                            <?php printf('<td style="text-align:center"><a title="'.__('Resend Order Email','dataplans').'" href="?page=dpio-history&dataplan_action=resend_email&oid=%s"><span class="dashicons dashicons-email"></span></a>  <a title="'.__('View QR Code').'" target="_blank" href="%s"><span class="dashicons dashicons-media-code"></span></a> <a title="'.__('View Purchase','dataplans').'" target="_blank" href="https://esims.dataplans.io/dashboard/purchases/%s"><span class="dashicons dashicons-welcome-view-site"></span></a></td>',$order_id,$product_plan_purchase_arr->purchase->esim->qrCodeDataUrl,$product_plan_purchase_arr->purchase->purchaseId)?>
                        </tr>
        <?php
                    } // if(product_plan_purchase_arr->purchase)
                } // foreach ($all_orders as $order_id) {
            } //if(isset($all_orders[0])){

                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th><?php esc_html_e("WC OID",'dataplans')?></th>
                    <th><?php esc_html_e("Date",'dataplans')?></th>
                    <th><?php esc_html_e("Status",'dataplans')?></th>
                    <th><?php esc_html_e("Plan Slug",'dataplans')?></th>
                    <th><?php esc_html_e("Price",'dataplans')?></th>
                    <th><?php esc_html_e("Phone",'dataplans')?></th>
                    <th><?php esc_html_e("Serial",'dataplans')?></th>
                    <th><?php esc_html_e("Balance",'dataplans')?></th>
                    <th><?php esc_html_e("Action",'dataplans')?></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>