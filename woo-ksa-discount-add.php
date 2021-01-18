<?php
/**
 * KSASK Discount addon
 *
 * Plugin Name: Woocommerse KSASK Discount addon
 * Plugin URI:  https://ksask.net
 * Description: Woocommerse KSASK Discount addon
 * Version:     1.0
 * Author:      Sergii Kapt
 * Author URI:  https://ksask.net
 */

if (!defined('ABSPATH')) {
    exit;
}

if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
    require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
}
// update to the plugin you are checking for
if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
    function require_acf_network_plugin(){?>
        <div class="notice notice-error" >
        <p><?php echo __('Please Enable WooCommerce Plugin before using Woocommerse KSASK Discount addon.') ?></p>
        </div><?php
        @trigger_error(__('Please Enable WooCommerce Plugin before using Woocommerse KSASK Discount addon.'), E_USER_ERROR);
    }

    add_action('admin_notices','require_acf_network_plugin');
    register_activation_hook(__FILE__, 'require_acf_network_plugin');
}

define( 'KSA_WSA', 1);
define( 'KSA_WSA_P', __FILE__);
define( 'KSA_WSA_P_BASENAME', plugin_basename( KSA_WSA_P ) );
define( 'KSA_WSA_P_DIR', untrailingslashit(dirname(KSA_WSA_P)));
define( 'KSA_WSA_P_URI', plugin_dir_url( __FILE__ ) );

require_once KSA_WSA_P_DIR . '/KSA_Wsa.php';

add_action('plugins_loaded', array('KSA_Wsa', 'init_core'));





