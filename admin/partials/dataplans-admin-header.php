<?php
$mode = empty($mode) ? 'sandbox' : $mode;
$status = empty($status) ? 'offline' : $status;
$balance = empty($balance) ? 'N/A' : $balance;
?>
<div class="dpio-header">
    <div class="dpio-logo"></div>
    <div class="dpio-account">
        <div class="dpio-info dpio-mode <?php esc_attr_e($status); ?>">
            <h4><?php esc_html_e("DataPlans Mode",'dataplans')?></h4>
            <strong><?php _e( $mode, DataplansConst::I18N_NAME ); ?></strong>
        </div>
        <div class="dpio-info dpio-status <?php esc_attr_e($status); ?>">
            <h4><?php esc_html_e("Connection Status",'dataplans')?></h4>
            <strong><?php _e( $status, DataplansConst::I18N_NAME ); ?></strong>
        </div>
        <div class="dpio-info dpio-balance">
            <h4><?php esc_html_e("Current Balance",'dataplans')?></h4>
            <strong><?php esc_html_e($balance); ?></strong>
        </div>
    </div>
</div>



