<?php
/*
 * Plugin Name: reCaptcha for WooCommerce
 * Plugin URI: https://wordpress.org/plugins/advanced-google-recaptcha-for-woocommerce/
 * Description: Enable Google reCaptcha to protect your eCommerce site against spam.
 * Author: Tanvirul Haque
 * Version: 1.0.5
 * Author URI: http://wpxpress.net
 * Text Domain: recaptcha-for-woocommerce
 * Domain Path: /languages
 * Requires PHP: 7.4
 * Requires at least: 4.8
 * Tested up to: 6.6
 * WC tested up to: 9.3
 * WC requires at least: 6.0
 * License: GPLv2+
*/

defined( 'ABSPATH' ) or die( 'Keep Silent' );

if ( ! defined( 'WC_RECAPTCHA_FOR_WOOCOMMERCE_PLUGIN_VERSION' ) ) {
	define( 'WC_RECAPTCHA_FOR_WOOCOMMERCE_PLUGIN_VERSION', '1.0.5' );
}

if ( ! defined( 'WC_RECAPTCHA_FOR_WOOCOMMERCE_PLUGIN_FILE' ) ) {
	define( 'WC_RECAPTCHA_FOR_WOOCOMMERCE_PLUGIN_FILE', __FILE__ );
}

/**
 * Include plugin main class
 */
if ( ! class_exists( 'WC_Recaptcha_For_WooCommerce', false ) ) {
	require_once dirname( __FILE__ ) . '/includes/class-wc-recaptcha-for-woocommerce.php';
}

/**
 * Require WooCommerce admin message
 */
function wc_recaptcha_for_woocommerce_wc_requirement_notice() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		$link = add_query_arg(
			array(
				'tab'       => 'plugin-information',
				'plugin'    => 'woocommerce',
				'TB_iframe' => 'true',
				'width'     => '640',
				'height'    => '500',
			),
			admin_url( 'plugin-install.php' )
		);

		$message = __( "<strong>reCaptcha for WooCommerce</strong> is an add-on of ", "recaptcha-for-woocommerce" );

		printf( '<div class="%1$s"><p>%2$s <a class="thickbox open-plugin-details-modal" href="%3$s"><strong>%4$s</strong></a></p></div>', 'notice notice-error', wp_kses_post( $message ), esc_url( $link ), esc_html__( 'WooCommerce', 'recaptcha-for-woocommerce' ) );
	}
}

add_action( 'admin_notices', 'wc_recaptcha_for_woocommerce_wc_requirement_notice' );

/**
 * Returns the main instance
 */
function wc_recaptcha_for_woocommerce() {
	if ( ! class_exists( 'WooCommerce', false ) ) {
		return false;
	}

	return WC_Recaptcha_For_WooCommerce::instance();
}

add_action( 'plugins_loaded', 'wc_recaptcha_for_woocommerce' );

/**
 * HPOS Compatibility
 */
function wc_recaptcha_for_woocommerce_hpos_compatibility() {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
}

add_action( 'before_woocommerce_init', 'wc_recaptcha_for_woocommerce_hpos_compatibility' );
