<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WC_Recaptcha_For_WooCommerce_Backend' ) ) {
    /**
     * Plugin Back End
     */
    class WC_Recaptcha_For_WooCommerce_Backend {
        protected static $_instance = null;

        protected function __construct() {
            $this->includes();
            $this->hooks();
            $this->init();

            do_action( 'wc_recaptcha_for_woocommerce_backend_loaded', $this );
        }

        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }

            return self::$_instance;
        }

        protected function includes() {
        }

        protected function hooks() {
            add_action( 'admin_enqueue_scripts', array( $this, 'admin_assets' ) );

            add_filter( 'plugin_action_links_' . plugin_basename( WC_RECAPTCHA_FOR_WOOCOMMERCE_PLUGIN_FILE ), array(
                $this,
                'plugin_action_links'
            ) );

            add_filter( 'woocommerce_get_settings_pages', array( $this, 'admin_settings_page' ), 11 );
            add_action( 'admin_menu', array( $this, 'admin_settings_menu' ) );
        }

        protected function init() {
        }

        /**
         * Admin Scripts
         */
        public function admin_assets() {
            wp_enqueue_style( 'recaptcha-for-woocommerce-admin-styles', untrailingslashit( plugin_dir_url( __FILE__ ) ) . '/admin/assets/css/admin-styles.css', '1.0.0' );

            wp_enqueue_script( 'recaptcha-for-woocommerce-admin-scripts', untrailingslashit( plugin_dir_url( __FILE__ ) ) . '/admin/assets/js/admin-scripts.js', array( 'jquery' ), '1.0.0', true );
            wp_enqueue_script( 'recaptcha-for-woocommerce-form-field-dependency', untrailingslashit( plugin_dir_url( __FILE__ ) ) . '/admin/assets/js/rfw-form-field-dependency.js', array( 'jquery' ), '2.0.0', true );
        }

        /**
         * Plugin Action Links
         */
        public function plugin_action_links( $links ) {
            $new_links     = array();
            $settings_link = esc_url( add_query_arg( array(
                'page' => 'wc-settings',
                'tab'  => 'recaptcha-for-woocommerce',
            ), admin_url( 'admin.php' ) ) );

            $new_links['settings'] = sprintf( '<a href="%1$s" title="%2$s">%2$s</a>', $settings_link, esc_attr__( 'Settings', 'recaptcha-for-woocommerce' ) );

            return array_merge( $new_links, $links );
        }

        /**
         * Admin Settings Page
         */
        public function admin_settings_page( $settings ) {
            $settings = include dirname( __FILE__ ) . '/admin/class-wc-recaptcha-for-woocommerce-settings.php';

            return $settings;
        }

        /**
         * Admin Settings Menu
         */
        public function admin_settings_menu() {
            $page_title = esc_html__( 'reCaptcha For WooCommerce Settings', 'recaptcha-for-woocommerce' );
            $menu_title = esc_html__( 'reCaptcha', 'recaptcha-for-woocommerce' );

            $settings_link = esc_url( add_query_arg(
                array(
                    'page' => 'wc-settings',
                    'tab'  => 'recaptcha-for-woocommerce',
                ),
                admin_url( 'admin.php' )
            ) );

            add_menu_page(
				$page_title,
				$menu_title,
				'manage_options',
				$settings_link,
				'',
	            'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz4KPHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB3aWR0aD0iMjBweCIgaGVpZ2h0PSIyMHB4IiB2aWV3Qm94PSIwIDAgMjAgMTkiIHZlcnNpb249IjEuMSI+CjxnIGlkPSJzdXJmYWNlMSI+CjxwYXRoIHN0eWxlPSIgc3Ryb2tlOm5vbmU7ZmlsbC1ydWxlOm5vbnplcm87ZmlsbDpyZ2IoMy4xMzcyNTUlLDMuMTM3MjU1JSwzLjEzNzI1NSUpO2ZpbGwtb3BhY2l0eToxOyIgZD0iTSAyMCA5LjQ4ODI4MSBDIDIwIDkuMzUxNTYyIDE5Ljk5NjA5NCA5LjIxNDg0NCAxOS45ODgyODEgOS4wNzgxMjUgTCAxOS45ODgyODEgMS4zNjcxODggTCAxNy43NSAzLjUgQyAxNS45MjE4NzUgMS4zNjMyODEgMTMuMTM2NzE5IDAgMTAuMDE1NjI1IDAgQyA2Ljc2OTUzMSAwIDMuODgyODEyIDEuNDc2NTYyIDIuMDU4NTk0IDMuNzYxNzE5IEwgNS43MjY1NjIgNy4yOTI5NjkgQyA2LjA4NTkzOCA2LjY2NDA2MiA2LjU5Mzc1IDYuMTE3MTg4IDcuMjEwOTM4IDUuNzA3MDMxIEMgNy44NTE1NjIgNS4yMzA0NjkgOC43NjE3MTkgNC44Mzk4NDQgMTAuMDE1NjI1IDQuODM5ODQ0IEMgMTAuMTY3OTY5IDQuODM5ODQ0IDEwLjI4NTE1NiA0Ljg1OTM3NSAxMC4zNzEwOTQgNC44OTA2MjUgQyAxMS45MjU3ODEgNS4wMDc4MTIgMTMuMjczNDM4IDUuODI0MjE5IDE0LjA2NjQwNiA3LjAxMTcxOSBMIDExLjQ2ODc1IDkuNDg0Mzc1IEMgMTQuNzU3ODEyIDkuNDcyNjU2IDE4LjQ3MjY1NiA5LjQ2NDg0NCAyMCA5LjQ4NDM3NSAiLz4KPHBhdGggc3R5bGU9IiBzdHJva2U6bm9uZTtmaWxsLXJ1bGU6bm9uemVybztmaWxsOnJnYigxLjU2ODYyNyUsMS41Njg2MjclLDEuNTY4NjI3JSk7ZmlsbC1vcGFjaXR5OjE7IiBkPSJNIDkuOTU3MDMxIDAgQyA5LjgxMjUgMCA5LjY3MTg3NSAwLjAwMzkwNjI1IDkuNTI3MzQ0IDAuMDExNzE4OCBMIDEuNDM3NSAwLjAxMTcxODggTCAzLjY3MTg3NSAyLjE0MDYyNSBDIDEuNDMzNTk0IDMuODg2NzE5IDAgNi41MzkwNjIgMCA5LjUxMTcxOSBDIDAgMTIuNjA1NDY5IDEuNTUwNzgxIDE1LjM1NTQ2OSAzLjk0OTIxOSAxNy4wOTM3NSBMIDcuNjU2MjUgMTMuNTk3NjU2IEMgNi45OTIxODggMTMuMjU3ODEyIDYuNDIxODc1IDEyLjc3MzQzOCA1Ljk4ODI4MSAxMi4xODM1OTQgQyA1LjQ4ODI4MSAxMS41NzQyMTkgNS4wODIwMzEgMTAuNzEwOTM4IDUuMDgyMDMxIDkuNTE1NjI1IEMgNS4wODIwMzEgOS4zNzEwOTQgNS4wOTc2NTYgOS4yNTc4MTIgNS4xMzI4MTIgOS4xNzU3ODEgQyA1LjI1MzkwNiA3LjY5NTMxMiA2LjExMzI4MSA2LjQxMDE1NiA3LjM1OTM3NSA1LjY1NjI1IEwgOS45NTMxMjUgOC4xMjg5MDYgQyA5Ljk0MTQwNiA0Ljk5NjA5NCA5LjkzMzU5NCAxLjQ1NzAzMSA5Ljk1NzAzMSAwICIvPgo8cGF0aCBzdHlsZT0iIHN0cm9rZTpub25lO2ZpbGwtcnVsZTpub256ZXJvO2ZpbGw6cmdiKDEuOTYwNzg0JSwxLjk2MDc4NCUsMS45NjA3ODQlKTtmaWxsLW9wYWNpdHk6MTsiIGQ9Ik0gMCA5LjUxMTcxOSBDIDAgOS42NDg0MzggMC4wMDM5MDYyNSA5Ljc4NTE1NiAwLjAxMTcxODggOS45MjE4NzUgTCAwLjAxMTcxODggMTcuNjMyODEyIEwgMi4yNSAxNS41IEMgNC4wNzgxMjUgMTcuNjM2NzE5IDYuODYzMjgxIDE5IDkuOTg0Mzc1IDE5IEMgMTMuMjMwNDY5IDE5IDE2LjExNzE4OCAxNy41MjM0MzggMTcuOTQxNDA2IDE1LjIzODI4MSBMIDE0LjI3MzQzOCAxMS43MDcwMzEgQyAxMy45MTQwNjIgMTIuMzM5ODQ0IDEzLjQwMjM0NCAxMi44ODI4MTIgMTIuNzg5MDYyIDEzLjI5Mjk2OSBDIDEyLjE0ODQzOCAxMy43Njk1MzEgMTEuMjQyMTg4IDE0LjE2MDE1NiA5Ljk4NDM3NSAxNC4xNjAxNTYgQyA5LjgzMjAzMSAxNC4xNjAxNTYgOS43MTQ4NDQgMTQuMTQwNjI1IDkuNjI4OTA2IDE0LjEwOTM3NSBDIDguMDc0MjE5IDEzLjk5MjE4OCA2LjcyNjU2MiAxMy4xNzU3ODEgNS45MzM1OTQgMTEuOTg4MjgxIEwgOC41MzEyNSA5LjUxNTYyNSBDIDUuMjQyMTg4IDkuNTI3MzQ0IDEuNTI3MzQ0IDkuNTM1MTU2IDAgOS41MTU2MjUgIi8+CjxwYXRoIHN0eWxlPSIgc3Ryb2tlOm5vbmU7ZmlsbC1ydWxlOm5vbnplcm87ZmlsbDpyZ2IoMTAwJSwxMDAlLDEwMCUpO2ZpbGwtb3BhY2l0eToxOyIgZD0iTSA5Ljk0MTQwNiAtMC4wMDc4MTI1IEwgMTAuMDc4MTI1IC0wLjAwNzgxMjUgTCAxMC4wNzgxMjUgNC44Mzk4NDQgTCA5Ljk0MTQwNiA0LjgzOTg0NCBaIE0gOS45NDE0MDYgLTAuMDA3ODEyNSAiLz4KPHBhdGggc3R5bGU9IiBzdHJva2U6bm9uZTtmaWxsLXJ1bGU6bm9uemVybztmaWxsOnJnYigxMDAlLDEwMCUsMTAwJSk7ZmlsbC1vcGFjaXR5OjE7IiBkPSJNIC0wLjAwMzkwNjI1IDkuNDAyMzQ0IEwgNS4wODU5MzggOS40MDIzNDQgTCA1LjA4NTkzOCA5LjUyNzM0NCBMIC0wLjAwMzkwNjI1IDkuNTI3MzQ0IFogTSAtMC4wMDM5MDYyNSA5LjQwMjM0NCAiLz4KPC9nPgo8L3N2Zz4K',
				35 );
        }
    }
}