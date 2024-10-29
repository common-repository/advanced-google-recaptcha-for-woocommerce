<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WC_Recaptcha_For_WooCommerce' ) ) {
    class WC_Recaptcha_For_WooCommerce {
        protected static $_instance = null;

        public function __construct() {
            $this->includes();
            $this->hooks();
            $this->init();

            do_action( 'wc_recaptcha_for_woocommerce_loaded', $this );
        }

        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }

            return self::$_instance;
        }

        public function version() {
            return esc_attr( WC_RECAPTCHA_FOR_WOOCOMMERCE_PLUGIN_VERSION );
        }

        protected function define( $name, $value ) {
            if ( ! defined( $name ) ) {
                define( $name, $value );
            }
        }

        public function includes() {
            require_once dirname( __FILE__ ) . '/class-wc-recaptcha-for-woocommerce-frontend.php';
            require_once dirname( __FILE__ ) . '/class-wc-recaptcha-for-woocommerce-backend.php';
        }

        public function get_frontend() {
            return WC_Recaptcha_For_WooCommerce_Frontend::instance();
        }

        public function get_backend() {
            return WC_Recaptcha_For_WooCommerce_Backend::instance();
        }

        public function hooks() {
            add_action( 'init', array( $this, 'language' ), 1 );
        }

        public function init() {
            $this->get_frontend();
            $this->get_backend();
        }

        public function language() {
            load_plugin_textdomain( 'recaptcha-for-woocommerce', false, plugin_basename( dirname( WC_RECAPTCHA_FOR_WOOCOMMERCE_PLUGIN_FILE ) ) . '/languages' );
        }

        public function basename() {
            return basename( dirname( WC_RECAPTCHA_FOR_WOOCOMMERCE_PLUGIN_FILE ) );
        }

        public function plugin_basename() {
            return plugin_basename( WC_RECAPTCHA_FOR_WOOCOMMERCE_PLUGIN_FILE );
        }

        public function plugin_dirname() {
            return dirname( plugin_basename( WC_RECAPTCHA_FOR_WOOCOMMERCE_PLUGIN_FILE ) );
        }

        public function plugin_path() {
            return untrailingslashit( plugin_dir_path( WC_RECAPTCHA_FOR_WOOCOMMERCE_PLUGIN_FILE ) );
        }

        public function plugin_url() {
            return untrailingslashit( plugins_url( '/', WC_RECAPTCHA_FOR_WOOCOMMERCE_PLUGIN_FILE ) );
        }

        public function include_path( $file = '' ) {
            return untrailingslashit( plugin_dir_path( WC_RECAPTCHA_FOR_WOOCOMMERCE_PLUGIN_FILE ) . 'includes' ) . $file;
        }

        public function is_pro() {
            return false;
        }
    }
}
