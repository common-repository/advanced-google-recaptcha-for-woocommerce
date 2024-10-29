<?php 
defined( 'ABSPATH' ) || exit;

/**
* Settings for API.
*/
if ( class_exists( 'WC_Recaptcha_For_WooCommerce_Settings' ) ) {
	return new WC_Recaptcha_For_WooCommerce_Settings();
}

class WC_Recaptcha_For_WooCommerce_Settings extends WC_Settings_Page {
	public function __construct() {
		$this->id    = 'recaptcha-for-woocommerce';
		$this->label = esc_html__( 'reCaptcha', 'recaptcha-for-woocommerce' );

		parent::__construct();
	}

    /**
	 * Get own sections.
	 *
	 * @return array
	 */
	protected function get_own_sections() {
		return array(
			''   =>  __( 'General', 'recaptcha-for-woocommerce' ),
			'woocommerce'    =>  __( 'WooCommerce', 'recaptcha-for-woocommerce' )
		);
	}

	public function output() {
		global $current_section, $hide_save_button;
		
        $settings = $this->get_settings( $current_section );

        $this->output_fields( $settings );
	}

    public function get_settings_for_default_section() {
        $settings = array(
            array(
                'type' => 'title',
                'id'   => 'wc_recaptcha_default_options',
                'title' => esc_html__( 'reCaptcha for WooCommerce Settings', 'recaptcha-for-woocommerce' ),
                'desc'  => '<p>' . esc_html__( 'The following options control the "reCaptcha for WooCommerce" extension.', 'recaptcha-for-woocommerce' ) . '</p>'
            ),

            array(
                'id'       => 'wc_recaptcha_version',
                'type'     => 'select',
                'class'    => 'wc-enhanced-select',
                'title'    => esc_html__( 'reCaptcha version', 'recaptcha-for-woocommerce' ),
                'desc_tip' => esc_html__( 'reCaptcha v3 does not show any challenge to solve like I am not robot etc. ReCaptcha v3 uses a behind-the-scenes scoring system to detect abusive traffic, and lets you decide the minimum passing score.', 'recaptcha-for-woocommerce' ),
                'default'  => 'v2',
                'options'  => array(
                    'v2' => esc_html__( 'reCaptcha v2 (checkbox)', 'recaptcha-for-woocommerce' ),
                    'v3' => esc_html__( 'reCaptcha v3 (no checkbox)', 'recaptcha-for-woocommerce' ),
                ),
            ),

            array(
                'id'      => 'wc_recaptcha_site_key_v2',
                'type'    => 'text',
                'title'   => esc_html__( 'Site Key v2', 'recaptcha-for-woocommerce' ),
                'desc'   => __( 'Get the site key from <a href="https://www.google.com/recaptcha/admin/create" target="_blank">here.</a>', 'recaptcha-for-woocommerce' ),
                'default' => '',
                'require'  => $this->dependency_required_attribute( array(
                    'wc_recaptcha_version' => array(
                        'type'  => '==',
                        'value' => 'v2'
                    )
                ) ),
            ),

            array(
                'id'      => 'wc_recaptcha_secret_key_v2',
                'type'    => 'text',
                'title'   => esc_html__( 'Secret Key v2', 'recaptcha-for-woocommerce' ),
                'desc'    => __( 'Get the secret key from <a href="https://www.google.com/recaptcha/admin/create" target="_blank">here.</a>', 'recaptcha-for-woocommerce' ),
                'default' => '',
                'require'  => $this->dependency_required_attribute( array(
                    'wc_recaptcha_version' => array(
                        'type'  => '==',
                        'value' => 'v2'
                    )
                ) ),
            ),

            array(
                'id'      => 'wc_recaptcha_site_key_v3',
                'type'    => 'text',
                'title'   => esc_html__( 'Site Key v3', 'recaptcha-for-woocommerce' ),
                'desc'   => __( 'Get the site key from <a href="https://www.google.com/recaptcha/admin/create" target="_blank">here.</a>', 'recaptcha-for-woocommerce' ),
                'default' => '',
                'require'  => $this->dependency_required_attribute( array(
                    'wc_recaptcha_version' => array(
                        'type'  => '==',
                        'value' => 'v3'
                    )
                ) ),
            ),

            array(
                'id'      => 'wc_recaptcha_secret_key_v3',
                'type'    => 'text',
                'title'   => esc_html__( 'Secret Key v3', 'recaptcha-for-woocommerce' ),
                'desc'    => __( 'Get the secret key from <a href="https://www.google.com/recaptcha/admin/create" target="_blank">here.</a>', 'recaptcha-for-woocommerce' ),
                'default' => '',
                'require'  => $this->dependency_required_attribute( array(
                    'wc_recaptcha_version' => array(
                        'type'  => '==',
                        'value' => 'v3'
                    )
                ) ),
            ),

            array(
                'id'       => 'wc_recaptcha_theme',
                'type'     => 'select',
                'class'    => 'wc-enhanced-select',
                'title'    => esc_html__( 'reCaptcha theme', 'recaptcha-for-woocommerce' ),
                'default'  => 'light',
                'options'  => array(
                    'light' => esc_html__( 'Light', 'recaptcha-for-woocommerce' ),
                    'dark'  => esc_html__( 'Dark', 'recaptcha-for-woocommerce' ),
                ),
                'require'  => $this->dependency_required_attribute( array(
                    'wc_recaptcha_version' => array(
                        'type'  => '==',
                        'value' => 'v2'
                    )
                ) ),
            ),

            array(
                'id'       => 'wc_recaptcha_size',
                'type'     => 'select',
                'class'    => 'wc-enhanced-select',
                'title'    => esc_html__( 'reCaptcha size', 'recaptcha-for-woocommerce' ),
                'default'  => 'normal',
                'options'  => array(
                    'normal'  => esc_html__( 'Normal', 'recaptcha-for-woocommerce' ),
                    'compact' => esc_html__( 'Compact', 'recaptcha-for-woocommerce' ),
                ),
                'require'  => $this->dependency_required_attribute( array(
                    'wc_recaptcha_version' => array(
                        'type'  => '==',
                        'value' => 'v2'
                    )
                ) ),
            ),

            array(
                'id'       => 'wc_recaptcha_language',
                'type'     => 'select',
                'class'    => 'wc-enhanced-select',
                'title'    => esc_html__( 'reCaptcha language', 'recaptcha-for-woocommerce' ),
                'default'  => '',
                'options'  => $this->get_langs()
            ),

            // Section End
            array(
                'type' => 'sectionend',
                'id'   => 'wc_recaptcha_default_options'
            ),
        );

        $settings = apply_filters( 'wc_recaptcha_default_settings_fields', $settings );
        return apply_filters( 'wc_recaptcha_default_settings', $settings );
    }

    public function get_settings_for_woocommerce_section() {
        $settings = array(
            // WooCommerce login
            array(
                'type' => 'title',
                'id'   => 'wc_recaptcha_login_options',
                'title' => esc_html__( 'WooCommerce Login', 'recaptcha-for-woocommerce' ),
            ),

            array(
                'id'      => 'wc_enable_login_recaptcha',
                'type'    => 'checkbox',
                'title'   => esc_html__( 'Enable Login reCaptcha', 'recaptcha-for-woocommerce' ),
                'default' => 'yes',
            ),

            // Section End
            array(
                'type' => 'sectionend',
                'id'   => 'wc_recaptcha_login_options'
            ),

            // WooCommerce register
            array(
                'type' => 'title',
                'id'   => 'wc_recaptcha_register_options',
                'title' => esc_html__( 'WooCommerce Register', 'recaptcha-for-woocommerce' ),
            ),

            array(
                'id'      => 'wc_enable_register_recaptcha',
                'type'    => 'checkbox',
                'title'   => esc_html__( 'Enable Register reCaptcha', 'recaptcha-for-woocommerce' ),
                'default' => 'yes',
            ),

            // Section End
            array(
                'type' => 'sectionend',
                'id'   => 'wc_recaptcha_register_options'
            ),


            // WooCommerce password reset
            array(
                'type' => 'title',
                'id'   => 'wc_recaptcha_password_reset_options',
                'title' => esc_html__( 'WooCommerce Password Reset', 'recaptcha-for-woocommerce' ),
            ),

            array(
                'id'      => 'wc_enable_password_reset_recaptcha',
                'type'    => 'checkbox',
                'title'   => esc_html__( 'Enable Password Reset reCaptcha', 'recaptcha-for-woocommerce' ),
                'default' => 'yes',
            ),

            // Section End
            array(
                'type' => 'sectionend',
                'id'   => 'wc_recaptcha_password_reset_options'
            ),


            // WooCommerce Checkout
            array(
                'type' => 'title',
                'id'   => 'wc_recaptcha_checkout_options',
                'title' => esc_html__( 'WooCommerce Checkout', 'recaptcha-for-woocommerce' ),
            ),

            array(
                'id'      => 'wc_enable_checkout_recaptcha',
                'type'    => 'checkbox',
                'title'   => esc_html__( 'Enable Checkout reCaptcha', 'recaptcha-for-woocommerce' ),
                'default' => 'yes',
            ),

            // Section End
            array(
                'type' => 'sectionend',
                'id'   => 'wc_recaptcha_checkout_options'
            ),
        );

        $settings = apply_filters( 'wc_recaptcha_styles_settings_fields', $settings );
        return apply_filters( 'wc_recaptcha_styles_settings', $settings );
    }

    /**
    * Escape JSON for use on HTML or attribute text nodes.
    *
    * @param string $json JSON to escape.
    * @param bool   $html True if escaping for HTML text node, false for attributes. Determines how quotes are handled.
    *
    * @return string Escaped JSON.
    */
    public static function esc_json( $json, $html = false ) {
        return _wp_specialchars(
            $json,
            $html ? ENT_NOQUOTES : ENT_QUOTES, // Escape quotes in attribute nodes only.
            'UTF-8',                           // json_encode() outputs UTF-8 (really just ASCII), not the blog's charset.
            true                               // Double escape entities: `&amp;` -> `&amp;amp;`.
        );
    }

    public static function dependency_attribute( $value ){
        if( $value && isset( $value['require'] ) ) {
            return sprintf('data-rfw_dependency="%s"', self::esc_json( wp_json_encode( $value['require'] ) ) );
        }

        return '';
    }

    public function output_fields( $options ) {
        foreach ( $options as $value ) {
            if ( ! isset( $value['type'] ) ) {
                continue;
            }
            if ( ! isset( $value['id'] ) ) {
                $value['id'] = '';
            }
            if ( ! isset( $value['title'] ) ) {
                $value['title'] = isset( $value['name'] ) ? $value['name'] : '';
            }
            if ( ! isset( $value['class'] ) ) {
                $value['class'] = '';
            }
            if ( ! isset( $value['css'] ) ) {
                $value['css'] = '';
            }
            if ( ! isset( $value['default'] ) ) {
                $value['default'] = '';
            }
            if ( ! isset( $value['desc'] ) ) {
                $value['desc'] = '';
            }
            if ( ! isset( $value['desc_tip'] ) ) {
                $value['desc_tip'] = false;
            }
            if ( ! isset( $value['placeholder'] ) ) {
                $value['placeholder'] = '';
            }
            if ( ! isset( $value['suffix'] ) ) {
                $value['suffix'] = '';
            }
            if ( ! isset( $value['is_pro'] ) ) {
                $value['is_pro'] = false;
            }

            if ( ! isset( $value['value'] ) ) {
                $value['value'] = WC_Admin_Settings::get_option( $value['id'], $value['default'] );
            }

            $classes = array();

            if ( ! function_exists( 'recaptcha_for_woocommerce_pro' ) ) {
                if( $value['is_pro'] ){
                    $classes[] = 'is-pro';
                }
            }

            $class =  implode( ' ', array_values( array_unique( $classes ) ) );

            // Custom attribute handling.
            $custom_attributes = array();

            if ( ! empty( $value['custom_attributes'] ) && is_array( $value['custom_attributes'] ) ) {
                foreach ( $value['custom_attributes'] as $attribute => $attribute_value ) {
                    $custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
                }
            }

            $custom_attributes = implode( ' ', $custom_attributes );

            // Description handling.
            $field_description = WC_Admin_Settings::get_field_description( $value );
            $description       = $field_description['description'];
            $tooltip_html      = $field_description['tooltip_html'];

            // Switch based on type.
            switch ( $value['type'] ) {

                // Section Titles.
                case 'title':
                    if ( ! empty( $value['title'] ) ) {
                        echo '<h2>' . esc_html( $value['title'] ) . '</h2>';
                    }
                    if ( ! empty( $value['desc'] ) ) {
                        echo '<div id="' . esc_attr( sanitize_title( $value['id'] ) ) . '-description">';
                        echo wp_kses_post( wpautop( wptexturize( $value['desc'] ) ) );
                        echo '</div>';
                    }
                    echo '<table class="form-table woo-recaptcha-form-table">' . "\n\n";
                    if ( ! empty( $value['id'] ) ) {
                        do_action( 'woocommerce_settings_' . sanitize_title( $value['id'] ) );
                    }
                    break;

                // Section Ends.
                case 'sectionend':
                    if ( ! empty( $value['id'] ) ) {
                        do_action( 'woocommerce_settings_' . sanitize_title( $value['id'] ) . '_end' );
                    }
                    echo '</table>';
                    if ( ! empty( $value['id'] ) ) {
                        do_action( 'woocommerce_settings_' . sanitize_title( $value['id'] ) . '_after' );
                    }
                    break;

                // Standard text inputs and subtypes like 'number'.
                case 'text':
                case 'password':
                case 'datetime':
                case 'datetime-local':
                case 'date':
                case 'month':
                case 'time':
                case 'week':
                case 'number':
                case 'email':
                case 'url':
                case 'tel':
                    $option_value = $value['value'];
                    ?>
                    <tr class="<?php echo esc_attr( $class ) ?>" <?php echo wp_kses_data(self::dependency_attribute($value)); ?>>
                        <th scope="row" class="titledesc">
                            <label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?> <?php echo wp_kses_post( $tooltip_html ); ?></label>
                        </th>
                        <td class="forminp forminp-<?php echo esc_attr( sanitize_title( $value['type'] ) ); ?>">
                            <input
                                name="<?php echo esc_attr( $value['id'] ); ?>"
                                id="<?php echo esc_attr( $value['id'] ); ?>"
                                type="<?php echo esc_attr( $value['type'] ); ?>"
                                style="<?php echo esc_attr( $value['css'] ); ?>"
                                value="<?php echo esc_attr( $option_value ); ?>"
                                class="<?php echo esc_attr( $value['class'] ); ?>"
                                placeholder="<?php echo esc_attr( $value['placeholder'] ); ?>"
                                <?php echo wp_kses_data( $custom_attributes ); ?>
                                /><span class="suffix"><?php echo esc_html( $value['suffix'] ); ?></span> <?php echo wp_kses_post( $description ); ?>
                        </td>
                    </tr>
                    <?php
                    break;

                // Color picker.
                case 'color':
                    $option_value = $value['value'];
                    ?>
                    <tr class="<?php echo esc_attr( $class ) ?>" <?php echo wp_kses_data(self::dependency_attribute($value)); ?>>
                        <th scope="row" class="titledesc">
                            <label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?> <?php echo wp_kses_post( $tooltip_html ); ?></label>
                        </th>
                        <td class="forminp forminp-<?php echo esc_attr( sanitize_title( $value['type'] ) ); ?>">&lrm;
                            <span class="colorpickpreview" style="background: <?php echo esc_attr( $option_value ); ?>">&nbsp;</span>
                            <input
                                name="<?php echo esc_attr( $value['id'] ); ?>"
                                id="<?php echo esc_attr( $value['id'] ); ?>"
                                type="text"
                                dir="ltr"
                                style="<?php echo esc_attr( $value['css'] ); ?> width: 80px;"
                                value="<?php echo esc_attr( $option_value ); ?>"
                                class="<?php echo esc_attr( $value['class'] ); ?>colorpick"
                                placeholder="<?php echo esc_attr( $value['placeholder'] ); ?>"
                                <?php echo wp_kses_data( $custom_attributes ); ?>
                                />&lrm; <?php echo wp_kses_post( $description ); ?>
                                <div id="colorPickerDiv_<?php echo esc_attr( $value['id'] ); ?>" class="colorpickdiv" style="z-index: 100;background:#eee;border:1px solid #ccc;position:absolute;display:none;"></div>
                        </td>
                    </tr>
                    <?php
                    break;

                // Textarea.
                case 'textarea':
                    $option_value = $value['value'];
                    ?>
                    <tr class="<?php echo esc_attr( $class ) ?>" <?php echo wp_kses_data(self::dependency_attribute($value)); ?>>
                        <th scope="row" class="titledesc">
                            <label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?> <?php echo wp_kses_post( $tooltip_html ); ?></label>
                        </th>
                        <td class="forminp forminp-<?php echo esc_attr( sanitize_title( $value['type'] ) ); ?>">
                            <?php echo wp_kses_post( $description ); ?>

                            <textarea
                                name="<?php echo esc_attr( $value['id'] ); ?>"
                                id="<?php echo esc_attr( $value['id'] ); ?>"
                                style="<?php echo esc_attr( $value['css'] ); ?>"
                                class="<?php echo esc_attr( $value['class'] ); ?>"
                                placeholder="<?php echo esc_attr( $value['placeholder'] ); ?>"
                                <?php echo wp_kses_data( $custom_attributes ); ?>
                                ><?php echo esc_textarea( $option_value ); // WPCS: XSS ok. ?></textarea>
                        </td>
                    </tr>
                    <?php
                    break;

                // Select boxes.
                case 'select':
                case 'multiselect':
                    $option_value = $value['value'];
                    ?>
                    <tr class="<?php echo esc_attr( $class ) ?>" <?php echo wp_kses_data(self::dependency_attribute($value)); ?>>
                        <th scope="row" class="titledesc">
                            <label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?> <?php echo wp_kses_post( $tooltip_html ); ?></label>
                        </th>
                        <td class="forminp forminp-<?php echo esc_attr( sanitize_title( $value['type'] ) ); ?>">
                            <select
                                name="<?php echo esc_attr( $value['id'] ); ?><?php echo ( 'multiselect' === $value['type'] ) ? '[]' : ''; ?>"
                                id="<?php echo esc_attr( $value['id'] ); ?>"
                                style="<?php echo esc_attr( $value['css'] ); ?>"
                                class="<?php echo esc_attr( $value['class'] ); ?>"
                                <?php echo wp_kses_data( $custom_attributes ); ?>
                                <?php echo 'multiselect' === $value['type'] ? 'multiple="multiple"' : ''; ?>>
                                <?php
                                foreach ( $value['options'] as $key => $val ) {
                                    ?>
                                    <option value="<?php echo esc_attr( $key ); ?>"
                                        <?php
                                        if ( is_array( $option_value ) ) {
                                            selected( in_array( (string) $key, $option_value, true ), true );
                                        } else {
                                            selected( $option_value, (string) $key );
                                        }
                                        ?>
                                    ><?php echo esc_html( $val ); ?></option>
                                    <?php
                                }
                                ?>
                            </select><span class="suffix"><?php echo esc_html( $value['suffix'] ); ?></span> <?php echo wp_kses_post( $description ); ?>
                        </td>
                    </tr>
                    <?php
                    break;

                // Radio inputs.
                case 'radio':
                    $option_value = $value['value'];
                    ?>
                    <tr class="<?php echo esc_attr( $class ) ?>" <?php echo wp_kses_data(self::dependency_attribute($value)); ?>>
                        <th scope="row" class="titledesc">
                            <label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?> <?php echo wp_kses_post( $tooltip_html ); ?></label>
                        </th>
                        <td class="forminp forminp-<?php echo esc_attr( sanitize_title( $value['type'] ) ); ?>">
                            <fieldset>
                                <?php echo wp_kses_post( $description ); ?>
                                <ul>
                                <?php
                                foreach ( $value['options'] as $key => $val ) {
                                    ?>
                                    <li>
                                        <label><input
                                            name="<?php echo esc_attr( $value['id'] ); ?>"
                                            value="<?php echo esc_attr( $key ); ?>"
                                            type="radio"
                                            style="<?php echo esc_attr( $value['css'] ); ?>"
                                            class="<?php echo esc_attr( $value['class'] ); ?>"
                                            <?php echo wp_kses_data( $custom_attributes ); ?>
                                            <?php checked( $key, $option_value ); ?>
                                            /> <?php echo esc_html( $val ); ?></label>
                                    </li>
                                    <?php
                                }
                                ?>
                                </ul>
                            </fieldset>
                        </td>
                    </tr>
                    <?php
                    break;

                // Checkbox input.
                case 'checkbox':
                    $option_value     = $value['value'];
                    $visibility_class = array();

                    if ( ! isset( $value['hide_if_checked'] ) ) {
                        $value['hide_if_checked'] = false;
                    }
                    if ( ! isset( $value['show_if_checked'] ) ) {
                        $value['show_if_checked'] = false;
                    }
                    if ( 'yes' === $value['hide_if_checked'] || 'yes' === $value['show_if_checked'] ) {
                        $visibility_class[] = 'hidden_option';
                    }
                    if ( 'option' === $value['hide_if_checked'] ) {
                        $visibility_class[] = 'hide_options_if_checked';
                    }
                    if ( 'option' === $value['show_if_checked'] ) {
                        $visibility_class[] = 'show_options_if_checked';
                    }

                    if ( ! isset( $value['checkboxgroup'] ) || 'start' === $value['checkboxgroup'] ) {
                        ?>
                            <tr class="<?php echo esc_attr( implode( ' ', $visibility_class ) ); ?> <?php echo esc_attr( $class ) ?>" <?php echo wp_kses_data(self::dependency_attribute($value)); ?>>
                                <th scope="row" class="titledesc"><?php echo esc_html( $value['title'] ); ?></th>
                                <td class="forminp forminp-checkbox">
                                    <fieldset>
                        <?php
                    } else {
                        ?>
                            <fieldset class="<?php echo esc_attr( implode( ' ', $visibility_class ) ); ?>">
                        <?php
                    }

                    if ( ! empty( $value['title'] ) ) {
                        ?>
                            <legend class="screen-reader-text"><span><?php echo esc_html( $value['title'] ); ?></span></legend>
                        <?php
                    }

                    ?>
                        <label for="<?php echo esc_attr( $value['id'] ); ?>">
                            <input
                                name="<?php echo esc_attr( $value['id'] ); ?>"
                                id="<?php echo esc_attr( $value['id'] ); ?>"
                                type="checkbox"
                                class="<?php echo esc_attr( isset( $value['class'] ) ? $value['class'] : '' ); ?>"
                                value="1"
                                <?php checked( $option_value, 'yes' ); ?>
                                <?php echo wp_kses_data( $custom_attributes ); ?>
                            /> <?php echo wp_kses_post( $description ); ?>
                        </label> <?php echo wp_kses_post( $tooltip_html ); ?>
                    <?php

                    if ( ! isset( $value['checkboxgroup'] ) || 'end' === $value['checkboxgroup'] ) {
                        ?>
                                    </fieldset>
                                </td>
                            </tr>
                        <?php
                    } else {
                        ?>
                            </fieldset>
                        <?php
                    }
                    break;

                case 'dimensions':
                    $option_value = $value['value'];
                    ?>

                    <tr class="<?php echo esc_attr( $class ) ?>" <?php echo wp_kses_data(self::dependency_attribute($value)); ?>>
                        <th scope="row" class="titledesc">
                            <label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?> <?php echo wp_kses_post( $tooltip_html ); ?></label>
                        </th>

                        <td class="forminp forminp-<?php echo esc_attr( sanitize_title( $value['type'] ) ); ?>">
                            <label for="<?php echo esc_attr( $value['id'] ); ?>_top" style="display: inline-block">Top
                                <input
                                    name="<?php echo esc_attr( $value['id'] ); ?>[top]"
                                    id="<?php echo esc_attr( $value['id'] ); ?>_top"
                                    type="number"
                                    style="width: 60px; display: block"
                                    value="<?php echo esc_attr( $option_value['top'] ); ?>"
                                    class="<?php echo esc_attr( $value['class'] ); ?>"
                                    placeholder="<?php echo esc_attr( $value['placeholder'] ); ?>"
                                    step="1"
                                    min="0"
                                />
                            </label>

                            <label for="<?php echo esc_attr( $value['id'] ); ?>_right" style="display: inline-block">Right
                                <input
                                    name="<?php echo esc_attr( $value['id'] ); ?>[right]"
                                    id="<?php echo esc_attr( $value['id'] ); ?>_right"
                                    type="number"
                                    style="width: 60px; display: block"
                                    value="<?php echo esc_attr( $option_value['right'] ); ?>"
                                    class="<?php echo esc_attr( $value['class'] ); ?>"
                                    placeholder="<?php echo esc_attr( $value['placeholder'] ); ?>"
                                    step="1"
                                    min="0"
                                />
                            </label>

                            <label for="<?php echo esc_attr( $value['id'] ); ?>_bottom" style="display: inline-block">Bottom
                                <input
                                    name="<?php echo esc_attr( $value['id'] ); ?>[bottom]"
                                    id="<?php echo esc_attr( $value['id'] ); ?>_bottom"
                                    type="number"
                                    style="width: 60px; display: block"
                                    value="<?php echo esc_attr( $option_value['bottom'] ); ?>"
                                    class="<?php echo esc_attr( $value['class'] ); ?>"
                                    placeholder="<?php echo esc_attr( $value['placeholder'] ); ?>"
                                    step="1"
                                    min="0"
                                />
                            </label>

                            <label for="<?php echo esc_attr( $value['id'] ); ?>_left" style="display: inline-block">Left
                                <input
                                    name="<?php echo esc_attr( $value['id'] ); ?>[left]"
                                    id="<?php echo esc_attr( $value['id'] ); ?>_left"
                                    type="number"
                                    style="width: 60px; display: block"
                                    value="<?php echo esc_attr( $option_value['left'] ); ?>"
                                    class="<?php echo esc_attr( $value['class'] ); ?>"
                                    placeholder="<?php echo esc_attr( $value['placeholder'] ); ?>"
                                    step="1"
                                    min="0"
                                />
                            </label>

                            <?php echo wp_kses_post( $description ); ?>
                        </td>
                    </tr>

                    <?php
                    break;

                // Default: run an action.
                default:
                    do_action( 'woocommerce_admin_field_' . $value['type'], $value );
                    do_action( 'woo-recaptcha_admin_field', $value );
                    break;
            }
        }
    }

    public function save() {
        global $current_section;

        $settings = $this->get_settings( $current_section );
        WC_Admin_Settings::save_fields( $settings );

        if ( $current_section ) {
            do_action( 'woocommerce_update_options_' . $this->id . '_' . $current_section );
            do_action( 'woocommerce_update_options_recaptcha-for-woocommerce', $current_section );
        }
    }

    public function dependency_required_attribute( $require = array() ) {
        $array = array();

        foreach ( $require as $id => $value ) {
            $array[ sprintf( '#%s', $id ) ] = $value;
        }

        return array( $array );
    }

    public function get_pro_link_html() {
        if ( ! function_exists( 'recaptcha_for_woocommerce_pro' ) ) {
            $html = ' | ' . sprintf('<a href="%1$s" target="_blank" style="color:#d63638"><b>%2$s</b></a>', esc_url('https://wpxpress.net/docs/recaptcha-for-woocommerce/'), __( 'Get Pro Features', 'recaptcha-for-woocommerce' ) );

            return $html;
        }

        return '';
    }

    public function get_langs() {
        $arr = array(
            ''         => __('Auto Detect', 'recaptcha-for-woocommerce'),
            'ar'       => __('Arabic', 'recaptcha-for-woocommerce'),
            'af'       => __('Afrikaans', 'recaptcha-for-woocommerce'),
            'am'       => __('Amharic', 'recaptcha-for-woocommerce'),
            'hy'       => __('Armenian', 'recaptcha-for-woocommerce'),
            'az'       => __('Azerbaijani', 'recaptcha-for-woocommerce'),
            'eu'       => __('Basque', 'recaptcha-for-woocommerce'),
            'bn'       => __('Bengali', 'recaptcha-for-woocommerce'),
            'bg'       => __('Bulgarian', 'recaptcha-for-woocommerce'),
            'ca'       => __('Catalan', 'recaptcha-for-woocommerce'),
            'zh-HK'    => __('Chinese (Hong Kong)', 'recaptcha-for-woocommerce'),
            'zh-CN'    => __('Chinese (Simplified)', 'recaptcha-for-woocommerce'),
            'zh-TW'    => __('Chinese (Traditional)', 'recaptcha-for-woocommerce'),
            'hr'       => __('Croatian', 'recaptcha-for-woocommerce'),
            'cs'       => __('Czech', 'recaptcha-for-woocommerce'),
            'da'       => __('Danish', 'recaptcha-for-woocommerce'),
            'nl'       => __('Dutch', 'recaptcha-for-woocommerce'),
            'en-GB'    => __('English (UK)', 'recaptcha-for-woocommerce'),
            'en'       => __('English (US)', 'recaptcha-for-woocommerce'),
            'et'       => __('Estonian', 'recaptcha-for-woocommerce'),
            'fil'      => __('Filipino', 'recaptcha-for-woocommerce'),
            'fil'      => __('Finnish', 'recaptcha-for-woocommerce'),
            'fr'       => __('French', 'recaptcha-for-woocommerce'),
            'fr-CA'    => __('French (Canadian)', 'recaptcha-for-woocommerce'),
            'gl'       => __('Galician', 'recaptcha-for-woocommerce'),
            'ka'       => __('Georgian', 'recaptcha-for-woocommerce'),
            'de'       => __('German', 'recaptcha-for-woocommerce'),
            'de-AT'    => __('German (Austria)', 'recaptcha-for-woocommerce'),
            'de-CH'    => __('German (Switzerland)', 'recaptcha-for-woocommerce'),
            'el'       => __('Greek', 'recaptcha-for-woocommerce'),
            'gu'       => __('Gujarati', 'recaptcha-for-woocommerce'),
            'iw'       => __('Hebrew', 'recaptcha-for-woocommerce'),
            'hi'       => __('Hindi', 'recaptcha-for-woocommerce'),
            'hu'       => __('Hungarain', 'recaptcha-for-woocommerce'),
            'is'       => __('Icelandic', 'recaptcha-for-woocommerce'),
            'id'       => __('Indonesian', 'recaptcha-for-woocommerce'),
            'it'       => __('Italian', 'recaptcha-for-woocommerce'),
            'ja'       => __('Japanese', 'recaptcha-for-woocommerce'),
            'kn'       => __('Kannada', 'recaptcha-for-woocommerce'),
            'ko'       => __('Korean', 'recaptcha-for-woocommerce'),
            'lo'       => __('Laothian', 'recaptcha-for-woocommerce'),
            'lv'       => __('Latvian', 'recaptcha-for-woocommerce'),
            'lt'       => __('Lithuanian', 'recaptcha-for-woocommerce'),
            'ms'       => __('Malay', 'recaptcha-for-woocommerce'),
            'ml'       => __('Malayalam', 'recaptcha-for-woocommerce'),
            'mr'       => __('Marathi', 'recaptcha-for-woocommerce'),
            'mn'       => __('Mongolian', 'recaptcha-for-woocommerce'),
            'no'       => __('Norwegian', 'recaptcha-for-woocommerce'),
            'fa'       => __('Persian', 'recaptcha-for-woocommerce'),
            'pl'       => __('Polish', 'recaptcha-for-woocommerce'),
            'pt'       => __('Portuguese', 'recaptcha-for-woocommerce'),
            'pt-BR'    => __('Portuguese (Brazil)', 'recaptcha-for-woocommerce'),
            'pt-PT'    => __('Portuguese (Portugal)', 'recaptcha-for-woocommerce'),
            'ro'       => __('Romanian', 'recaptcha-for-woocommerce'),
            'ru'       => __('Russian', 'recaptcha-for-woocommerce'),
            'sr'       => __('Serbian', 'recaptcha-for-woocommerce'),
            'si'       => __('Sinhalese', 'recaptcha-for-woocommerce'),
            'sk'       => __('Slovak', 'recaptcha-for-woocommerce'),
            'sl'       => __('Slovenian', 'recaptcha-for-woocommerce'),
            'es'       => __('Spanish', 'recaptcha-for-woocommerce'),
            'es-419'   => __('Spanish (Latin America)', 'recaptcha-for-woocommerce'),
            'sw'       => __('Swahili', 'recaptcha-for-woocommerce'),
            'sv'       => __('Swedish', 'recaptcha-for-woocommerce'),
            'ta'       => __('Tamil', 'recaptcha-for-woocommerce'),
            'te'       => __('Telugu', 'recaptcha-for-woocommerce'),
            'th'       => __('Thai', 'recaptcha-for-woocommerce'),
            'tr'       => __('Turkish', 'recaptcha-for-woocommerce'),
            'uk'       => __('Ukrainian', 'recaptcha-for-woocommerce'),
            'ur'       => __('Urdu', 'recaptcha-for-woocommerce'),
            'vi'       => __('Vietnamese', 'recaptcha-for-woocommerce'),
            'zu'       => __('Zulu', 'recaptcha-for-woocommerce')
        );

        return apply_filters('wc_recaptcha_language_list', $arr);
    }
}

return new WC_Recaptcha_For_WooCommerce_Settings();