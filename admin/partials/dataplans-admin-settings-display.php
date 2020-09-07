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
?>

<div class="wrap dataplans">
    <div class="dpio-content">
        <h1><?php _e('DataPlans.io Settings', DataplansConst::I18N_NAME) ?></h1>
	    <?php settings_errors(); ?>
        <form id="dpio-settings" class="dpio-form" action="options.php" method="POST">
		    <?php
		    settings_fields( 'dpio-settings-group' );
		    do_settings_sections( 'dpio_options_page_slug' );
		    ?>
		    <?php submit_button(); ?>
        </form>
    </div>
</div>