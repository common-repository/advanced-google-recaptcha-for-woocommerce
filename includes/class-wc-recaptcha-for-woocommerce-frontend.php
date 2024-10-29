<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WC_Recaptcha_For_WooCommerce_Frontend' ) ) {
	/**
	 * Plugin Front End
	 */
	class WC_Recaptcha_For_WooCommerce_Frontend {
		private $api_version;
		private $site_key;
		private $secret_key;
		private $language;
		private $theme;
		private $size;

		protected static $_instance = null;

		protected function __construct() {
			$this->api_version = ( get_option( 'wc_recaptcha_version', 'v2' ) == 'v2' ) ? 'v2' : 'v3';
			$this->site_key    = get_option( 'wc_recaptcha_site_key_' . $this->api_version );
			$this->secret_key  = get_option( 'wc_recaptcha_secret_key_' . $this->api_version );
			$this->language    = ! empty( get_option( 'wc_recaptcha_language', '' ) ) ? "&hl=" . esc_attr( get_option( 'wc_recaptcha_language' ) ) : "&hl=" . get_locale();
			$this->theme       = get_option( 'wc_recaptcha_theme', 'dark' );
			$this->size        = get_option( 'wc_recaptcha_size', 'compact' );

			$this->includes();
			$this->hooks();
			$this->init();

			do_action( 'wc_recaptcha_for_woocommerce_frontend_loaded', $this );
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
			if ( ! empty( $this->site_key ) && ! empty( $this->secret_key ) ) {
				// WooCommerce login
				if ( 'yes' == get_option( 'wc_enable_login_recaptcha', 'yes' ) ) {
					add_action( 'woocommerce_login_form', array( $this, 'get_woo_login_recaptcha_field' ) );
					add_filter( 'woocommerce_process_login_errors', array( $this, 'woo_login_validation' ), 10, 1 );
				}

				// WooCommerce register
				if ( 'yes' == get_option( 'wc_enable_register_recaptcha', 'yes' ) ) {
					add_action( 'woocommerce_register_form', array( $this, 'get_woo_register_recaptcha_field' ) );
					add_filter( 'woocommerce_registration_errors', array( $this, 'woo_register_validation' ), 10, 1 );
				}

				// WooCommerce password reset
				if ( 'yes' == get_option( 'wc_enable_password_reset_recaptcha', 'yes' ) ) {
					add_action( 'woocommerce_lostpassword_form', array( $this, 'get_woo_lostpassword_recaptcha_field' ) );
					add_action( 'woocommerce_resetpassword_form', array( $this, 'get_woo_lostpassword_recaptcha_field' ) );
					add_action( 'validate_password_reset', array( $this, 'woo_password_reset_validation' ), 10, 1 );
					add_action( 'lostpassword_post', array( $this, 'woo_password_reset_validation' ), 10, 1 );
				}

				// WooCommerce checkout
				if ( 'yes' == get_option( 'wc_enable_checkout_recaptcha', 'yes' ) ) {
					add_action( 'woocommerce_review_order_before_payment', array( $this, 'get_woo_checkout_recaptcha_field' ) );
					add_action( 'woocommerce_checkout_process', array( $this, 'woo_checkout_validation' ), 10, 1 );
				}
			}
		}

		protected function init() {
		}

		/**
		 * Get Recaptcha V2 field
		 */
		public function get_recaptcha_field_v2( $id ) {
			echo '<div class="g-recaptcha" id="' . esc_attr( $id ) . '" data-sitekey="' . esc_attr( $this->site_key ) . '" data-theme="' . esc_attr( $this->theme ) . '" data-size="' . esc_attr( $this->size ) . '"></div><br/>';

			wp_print_script_tag( array( "src" => "https://www.google.com/recaptcha/api.js?explicit" . esc_attr( $this->language ) ) );
		}

		/**
		 * Get Recaptcha V3 field
		 */
		public function get_recaptcha_field_v3( $id ) {
			echo '<input type="hidden" id="' . esc_attr( $id ) . '" name="g-recaptcha"/>';

			wp_print_script_tag( array( "src" => "https://www.google.com/recaptcha/api.js?render=" . esc_attr( $this->site_key ) . esc_attr( $this->language ) ) );

			wp_print_inline_script_tag( "
				grecaptcha.ready(function() {
                    grecaptcha.execute(
                        '" . esc_attr( $this->site_key ) . "',
                        { action: 'woo_recaptcha_v3' }
                    ).then(function(token) {
                        document.getElementById('" . esc_attr( $id ) . "').value=token;
                    });
                });
            ", [ 'type' => 'text/javascript' ] );
		}

		/**
		 * Get WooCommerce Login Recaptcha Field
		 */
		public function get_woo_login_recaptcha_field() {
			if ( $this->api_version == 'v2' ) {
				$this->get_recaptcha_field_v2( 'woo-login-recaptcha' );
			} else {
				$this->get_recaptcha_field_v3( 'woo-login-recaptcha' );
			}
		}

		/**
		 * Get WooCommerce Register Recaptcha Field
		 */
		public function get_woo_register_recaptcha_field() {
			if ( $this->api_version == 'v2' ) {
				$this->get_recaptcha_field_v2( 'woo-register-recaptcha' );
			} else {
				$this->get_recaptcha_field_v3( 'woo-register-recaptcha' );
			}
		}

		/**
		 * Get WooCommerce LostPassword Recaptcha Field
		 */
		public function get_woo_lostpassword_recaptcha_field() {
			if ( $this->api_version == 'v2' ) {
				$this->get_recaptcha_field_v2( 'woo-lostpassword-recaptcha' );
			} else {
				$this->get_recaptcha_field_v3( 'woo-lostpassword-recaptcha' );
			}
		}

		/**
		 * Get WooCommerce Checkout Recaptcha Field
		 */
		public function get_woo_checkout_recaptcha_field() {
			if ( $this->api_version == 'v2' ) {
				$this->get_recaptcha_field_v2( 'woo-checkout-recaptcha' );
			} else {
				$this->get_recaptcha_field_v3( 'woo-checkout-recaptcha' );
			}
		}

		/**
		 * Check the reCaptcha on submit
		 */
		public function verify_recaptcha( $data ) {
			if ( ! empty( $this->site_key ) && ! empty( $this->secret_key ) ) {
				$g_response         = ! empty( $data ) ? sanitize_text_field( $data ) : '';
				$verify             = wp_remote_get( 'https://www.google.com/recaptcha/api/siteverify?secret=' . esc_attr( $this->secret_key ) . '&response=' . esc_attr( $g_response ) );
				$verify             = wp_remote_retrieve_body( $verify );
				$response           = json_decode( $verify, true );
				$results['success'] = false;

				// Success
				if ( isset( $response['success'] ) && ! empty( $response['success'] ) ) {
					if ( $this->api_version == 'v3' ) {
						$score              = ( isset( $response['score'] ) && ! empty( $response['score'] ) ) ? (float) $response['score'] : 0;
						$results['success'] = $score >= 0.5 ? $response['success'] : false;
					} else {
						$results['success'] = $response['success'];
					}
				}

				// Error
				if ( isset( $response['error-codes'] ) && is_array( $response['error-codes'] ) ) {
					foreach ( $response['error-codes'] as $key => $error_val ) {
						$results['error_code'] = $error_val;
					}
				}

				return $results;
			} else {
				return false;
			}
		}

		/**
		 * WooCommerce login check
		 */
		public function woo_login_validation( $validation_error ) {
			if ( isset( $_POST['woocommerce-login-nonce'] ) ) {
				$nonce_value = sanitize_text_field( wc_get_var( $_REQUEST['woocommerce-login-nonce'], wc_get_var( $_REQUEST['_wpnonce'], '' ) ) ); // @codingStandardsIgnoreLine.

				if ( ! wp_verify_nonce( $nonce_value, 'woocommerce-login' ) ) {
					return;
				}

				$postkey  = $this->api_version == 'v2' ? 'g-recaptcha-response' : 'g-recaptcha';
				$postdata = isset( $_POST[ $postkey ] ) && ! empty( $_POST[ $postkey ] ) ? sanitize_text_field( $_POST[ $postkey ] ) : '';
				$verify   = $this->verify_recaptcha( $postdata );
				$success  = isset( $verify['success'] ) ? $verify['success'] : false;

				if ( $success != true ) {
					$validation_error->add( 'invalid_login_recaptcha', __( 'Please complete the reCaptcha to verify that you are not a robot.', 'recaptcha-for-woocommerce' ) );
				}

				return $validation_error;
			}
		}

		/**
		 * WooCommerce register check
		 */
		public function woo_register_validation( $validation_error ) {
			if ( is_checkout() ) {
				return;
			}

			if ( isset( $_POST['woocommerce-register-nonce'] ) ) {
				$nonce_value = sanitize_text_field( wc_get_var( $_REQUEST['woocommerce-register-nonce'], wc_get_var( $_REQUEST['_wpnonce'], '' ) ) ); // @codingStandardsIgnoreLine.

				if ( ! wp_verify_nonce( $nonce_value, 'woocommerce-register' ) ) {
					return;
				}

				$postkey  = $this->api_version == 'v2' ? 'g-recaptcha-response' : 'g-recaptcha';
				$postdata = isset( $_POST[ $postkey ] ) && ! empty( $_POST[ $postkey ] ) ? sanitize_text_field( $_POST[ $postkey ] ) : '';
				$verify   = $this->verify_recaptcha( $postdata );
				$success  = isset( $verify['success'] ) ? $verify['success'] : false;

				if ( $success != true ) {
					$validation_error->add( 'invalid_register_recaptcha', __( 'Please complete the reCaptcha to verify that you are not a robot.', 'recaptcha-for-woocommerce' ) );
				}

				return $validation_error;
			}
		}

		/**
		 * WooCommerce password reset check
		 */
		public function woo_password_reset_validation( $validation_error ) {
			if ( isset( $_POST['woocommerce-lost-password-nonce'] ) || isset( $_POST['woocommerce-reset-password-nonce'] ) ) {
				$lost_pass_nonce_value  = sanitize_text_field( wc_get_var( $_REQUEST['woocommerce-lost-password-nonce'], wc_get_var( $_REQUEST['_wpnonce'], '' ) ) ); // @codingStandardsIgnoreLine.
				$reset_pass_nonce_value = sanitize_text_field( wc_get_var( $_REQUEST['woocommerce-reset-password-nonce'], wc_get_var( $_REQUEST['_wpnonce'], '' ) ) ); // @codingStandardsIgnoreLine.

				if ( wp_verify_nonce( $lost_pass_nonce_value, 'lost_password' ) || wp_verify_nonce( $reset_pass_nonce_value, 'reset_password' ) ) {
					$postkey  = $this->api_version == 'v2' ? 'g-recaptcha-response' : 'g-recaptcha';
					$postdata = isset( $_POST[ $postkey ] ) && ! empty( $_POST[ $postkey ] ) ? sanitize_text_field( $_POST[ $postkey ] ) : '';
					$verify   = $this->verify_recaptcha( $postdata );
					$success  = isset( $verify['success'] ) ? $verify['success'] : false;

					if ( $success != true ) {
						$validation_error->add( 'invalid_reset_recaptcha', __( '<strong>Error:</strong> Please complete the reCaptcha to verify that you are not a robot.', 'recaptcha-for-woocommerce' ) );
					}

					return $validation_error;
				}
			}
		}

		/**
		 * WooCommerce checkout validation
		 */
		public function woo_checkout_validation() {
			if ( isset( $_POST['woocommerce-process-checkout-nonce'] ) ) {
				$nonce_value = sanitize_text_field( wc_get_var( $_REQUEST['woocommerce-process-checkout-nonce'], wc_get_var( $_REQUEST['_wpnonce'], '' ) ) ); // @codingStandardsIgnoreLine.

				if ( ! wp_verify_nonce( $nonce_value, 'woocommerce-process_checkout' ) ) {
					return;
				}

				$postkey  = $this->api_version == 'v2' ? 'g-recaptcha-response' : 'g-recaptcha';
				$postdata = isset( $_POST[ $postkey ] ) && ! empty( $_POST[ $postkey ] ) ? sanitize_text_field( $_POST[ $postkey ] ) : '';
				$verify   = $this->verify_recaptcha( $postdata );
				$success  = isset( $verify['success'] ) ? $verify['success'] : false;

				if ( $success != true ) {
					wc_add_notice( __( '<strong>Error:</strong> Please complete the reCaptcha to verify that you are not a robot.', 'recaptcha-for-woocommerce' ), 'error' );
				}
			}
		}
	}
}