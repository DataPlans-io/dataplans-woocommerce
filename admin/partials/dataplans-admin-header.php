<?php
$mode = empty($mode) ? 'sandbox' : $mode;
$status = empty($status) ? 'offline' : $status;
$balance = empty($balance) ? 'N/A' : $balance;
?>
<div class="dpio-header">
    <div class="dpio-logo"></div>
    <div class="dpio-account">
        <div class="dpio-info dpio-mode <?php echo $status; ?>">
            <h4>DataPlans Mode</h4>
            <strong><?php _e( $mode, DataplansConst::I18N_NAME ); ?></strong>
        </div>
        <div class="dpio-info dpio-status <?php echo $status; ?>">
            <h4>Connection Status</h4>
            <strong><?php _e( $status, DataplansConst::I18N_NAME ); ?></strong>
        </div>
        <div class="dpio-info dpio-balance">
            <h4>Current Balance</h4>
            <strong><?php echo $balance; ?></strong>
        </div>
    </div>
</div>



