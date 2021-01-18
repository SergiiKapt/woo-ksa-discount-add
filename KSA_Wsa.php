<?php
if (!class_exists('KSA_Wsa')) :

    class KSA_Wsa
    {
        public static $discountName = 'woocommerce_discount_percentage_num_decimals';
        public static $discountVal = 1;
        public static $outSiteDiscountName = 'woocommerce_discount_percentage_outsite_num_decimals';
        public static $outSiteDiscountVal = 1;

        public static function init_core()
        {
            add_filter('plugin_action_links', [__CLASS__, 'add_settings_link'], 10, 2);
            add_action('woocommerce_get_settings_pages', [__CLASS__, 'add_setting_page']);
            self::init_discount();

            if(self::$discountVal !== 1 || self::$outSiteDiscountVal !==1 )
                self::apply_discount();

            add_action('wp_enqueue_scripts', [__CLASS__, 'init_scripts']);
        }

        public static function add_settings_link($links, $file)
        {
            $links = (array)$links;
            $url = admin_url('admin.php?page=wc-settings&tab=ksa_wsa');
            $links[] = sprintf('<a href="%s">%s</a>', $url, __('Settings'));

            return $links;
        }

        public function add_setting_page()
        {
            $settings[] = include_once 'KSA_Wsa_WC_Settings_Tab.php';

            return $settings;
        }

        function init_scripts()
        {
            session_start();
            if($_SESSION['ksa_wsa_out_discount'] != 'Discount'){
                wp_enqueue_style('ksa-wsa', KSA_WSA_P_URI . 'assets/css/style.css');
                wp_enqueue_script('ksa-wsa', KSA_WSA_P_URI . 'assets/js/script.js', ['jquery'], null, true);
                wp_localize_script(
                    'ksa-wsa',
                    'ksaSwa',
                    array(
                        'url'   => admin_url( 'admin-ajax.php' ),
                        'nonce' => wp_create_nonce( "process_reservation_nonce" ),
                        'outDiscount' => get_option(self::$outSiteDiscountName)
                    )
                );
            }
        }

        public function init_discount()
        {
            self::set_discount_val();
            add_action ('wp_ajax_nopriv_process_reservation', [__CLASS__,'process_reservation']);
            add_action ('wp_ajax_process_reservation', [__CLASS__,'process_reservation']);
        }

        public static function set_discount_val()
        {
            session_start();
            if($_SESSION['ksa_wsa_out_discount'] == 'Discount') {
                if ($discount = get_option(self::$outSiteDiscountName)) {
                    self::$outSiteDiscountVal = 1 - $discount / 100;
                }
            }
            if (is_user_logged_in()) {
                if ($discount = get_option(self::$discountName)) {
                    self::$discountVal = 1 - $discount  / 100;
                }
            }
        }

        public static function apply_discount()
        {
            add_filter('woocommerce_product_get_price', [__CLASS__, 'custom_price'], 99, 2);
            add_filter('woocommerce_product_get_regular_price', [__CLASS__, 'custom_price'], 99, 2);
            // Variations
            add_filter('woocommerce_product_variation_get_regular_price', [__CLASS__, 'custom_price'], 99, 2);
            add_filter('woocommerce_product_variation_get_price', [__CLASS__, 'custom_price'], 99, 2);

            add_filter('woocommerce_variation_prices_price', [__CLASS__, 'custom_variable_price'], 99, 3);
            add_filter('woocommerce_variation_prices_regular_price', [__CLASS__, 'custom_variable_price'], 99, 3);

            add_filter('woocommerce_get_variation_prices_hash', [__CLASS__, 'add_price_multiplier_to_variation_prices_hash'], 99, 3);
        }

        // Simple, grouped and external products
        public function custom_price($price, $product)
        {
            return (float)$price* self::$discountVal * self::$outSiteDiscountVal;
        }

        // Variable (price range)
        function custom_variable_price($price, $variation, $product)
        {
            return (float)$price * self::$discountVal * self::$outSiteDiscountVal;
        }

        //Handling price caching (see explanations at the end)
        function add_price_multiplier_to_variation_prices_hash($price_hash, $product, $for_display)
        {
            $price_hash[] = self::$discountVal * self::$outSiteDiscountVal;

            return $price_hash;
        }

        public function process_reservation($dd)
        {
            check_ajax_referer( 'process_reservation_nonce', 'nonce' );

            if( true ){
                session_start();
                $_SESSION['ksa_wsa_out_discount'] = 'Discount';
                wp_send_json_success( 'success' );
                }
            else
                wp_send_json_error( array( 'error' => $custom_error ) );
        }

    }
endif;