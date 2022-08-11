<?php
/**
 * Customer completed order email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-completed-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 3.7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<?php /* translators: %s: Customer first name */ 
		$settings_arr = get_option("dpio_options");

		if($settings_arr['environment'] == 1)
			$url = "https://app.dataplans.io/api/v1/accountBalance";
		else
			$url = "https://sandbox.dataplans.io/api/v1/accountBalance";
		
			$args = array(
				'headers'     => array(
					'Authorization' => $settings_arr['api_access_token']
				),
			); 
	
				$http = _wp_http_get_object();
		
				$result = $http->get( $url, $args );

				if(isset($result->errors))
				return;

				if(isset($result['response']['code']) && $result['response']['code'] != '200')
					return;

				$result = json_decode($result['body']);

		if(isset($result->availableBalance))
			$dplan_curbalance = $result->availableBalance;
		else
			$dplan_curbalance = 'N/A';
		
			curl_close($curl);

?>
<p><?php printf( esc_html__( 'Hi %s,', 'dataplans' ), esc_html( get_option("blogname") ) ); ?></p>
<p><?php esc_html_e( 'Low Balance Alert. The Balance Must be greater than '.$settings_arr['balancelimit_alert'], 'dataplans' ); ?></p>
<h3><?php esc_html_e( 'The Current Balance is: '.$dplan_curbalance, 'dataplans' ); ?></h3>

<?php
if ( $additional_content ) {
	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
}

/*
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action( 'woocommerce_email_footer', $email );
