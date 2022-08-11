(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	///////// START admin-history-display.php //////
	 jQuery(document).ready(function() {
		jQuery('.wc-email-settings-table-name').find('a').each(function(index, value) {
			var resendemail_indexoff = (jQuery(value).attr('href').indexOf('DPWC_WC_Email_Customer_Completed_Order_Api'));
			var lowbalemail_indexoff = (jQuery(value).attr('href').indexOf('DPWC_WC_Email_Customer_Low_Balance_Notification_Api'));

			if(resendemail_indexoff > 0)
				jQuery(value).parent().parent().remove();

			if(lowbalemail_indexoff > 0)
				jQuery(value).parent().parent().remove();
				
		});
		
		jQuery('#api_dataplans_orders_list').DataTable({
	order: [[1, 'desc']]
});
		setTimeout(function(){jQuery(".dataTables_length").attr("style","display:none")}, 100);
		setTimeout(function(){jQuery(".dataTables_length").attr("style","display:none")}, 500);
		setTimeout(function(){jQuery(".dataTables_length").attr("style","display:none")}, 1000);
		setTimeout(function(){jQuery(".dataTables_length").attr("style","display:none")}, 5000);
	}); // ready

		///////// END admin-history-display.php //////
})( jQuery );
