<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'DPWC_WC_Email_Customer_Low_Balance_Notification_Api', false ) ) :

	/**
	 * Customer Completed Order Email.
	 *
	 * Order complete emails are sent to the customer when the order is marked complete and usual indicates that the order has been shipped.
	 *
	 * @class       WC_Email_Customer_Completed_Order
	 * @version     2.0.0
	 * @package     WooCommerce\Classes\Emails
	 * @extends     WC_Email
	 */
	class DPWC_WC_Email_Customer_Low_Balance_Notification_Api extends WC_Email {

		/**
		 * Constructor.
		 */
		public function __construct() {
			$this->id             = 'low_balance_notification_api';
			//$this->customer_email = true;
			$this->title          = __( 'Low Balance Alert', 'dataplans' );
			$this->description    = __( 'When the balance is less than the settings given of its, then this notification will be sent.', 'dataplans' );
			//$this->template_html  = 'emails/customer-completed-order.php';
			//$this->template_plain = 'emails/plain/customer-completed-order.php';
			$this->placeholders   = array(
				'{order_date}'   => '',
				'{order_number}' => '',
			);

			// Triggers for this email.
			//add_action( 'woocommerce_order_status_completed_notification', array( $this, 'trigger' ), 10, 2 );

			// Call parent constructor.
			parent::__construct();

			// if none was entered, just use the WP admin email as a fallback
			if ( ! $this->recipient )
            $this->recipient = get_option( 'admin_email' );
		}

		/**
		 * Trigger the sending of this email.
		 *
		 * @param int            $order_id The order ID.
		 * @param WC_Order|false $order Order object.
		 */
		public function trigger( $order_id, $order = false ) {
			$this->setup_locale();

			if ( $order_id && ! is_a( $order, 'WC_Order' ) ) {
				$order = wc_get_order( $order_id );
			}

			if ( is_a( $order, 'WC_Order' ) ) {
				$this->object                         = $order;
				$this->recipient                      = get_option( 'admin_email' );
				$this->placeholders['{order_date}']   = wc_format_datetime( $this->object->get_date_created() );
				$this->placeholders['{order_number}'] = $this->object->get_order_number();
			}

			if ( $this->is_enabled() && $this->get_recipient() ) {
				$this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
			}

			$this->restore_locale();
		}

		/**
		 * Get email subject.
		 *
		 * @since  3.1.0
		 * @return string
		 */
		public function get_default_subject() {
			return __( 'Your {site_title} order is now complete', 'woocommerce' );
		}

		/**
		 * Get email heading.
		 *
		 * @since  3.1.0
		 * @return string
		 */
		public function get_default_heading() {
			return __( 'Thanks for shopping with us', 'woocommerce' );
		}

		/**
		 * Get content html.
		 *
		 * @return string
		 */
		
		public function get_content_html() {
			return wc_get_template_html('../../'.DPWC_PDN.'/admin/template-customer-api-low-balance-notification.php',
				array(
					'order'              => $this->object,
					'email_heading'      => "Low Balance Alert!",
					'additional_content' => $this->get_additional_content(),
					'sent_to_admin'      => false,
					'plain_text'         => false,
					'email'              => $this,
				)
			);
		}

		/**
		 * Get content plain.
		 *
		 * @return string
		 */
		public function get_content_plain() {
			return wc_get_template_html(
				$this->template_plain,
				array(
					'order'              => $this->object,
					'email_heading'      => $this->get_heading(),
					'additional_content' => $this->get_additional_content(),
					'sent_to_admin'      => false,
					'plain_text'         => true,
					'email'              => $this,
				)
			);
		}

		/**
		 * Default content to show below main email content.
		 *
		 * @since 3.7.0
		 * @return string
		 */
		public function get_default_additional_content() {
			return __( 'Thanks for shopping with us.', 'dataplans' );
		}
	}

endif;

return new DPWC_WC_Email_Customer_Low_Balance_Notification_Api();
