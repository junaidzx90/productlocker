<?php
 /**
 *
 * @link              https://github.com/
 * @since             1.0.0
 * @package           productlocker
 *
 * @wordpress-plugin
 * Plugin Name:       ProductLocker
 * Plugin URI:        https://github.com/junaidzx90/productlocker
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Junayedzx90
 * Author URI:        https://www.fiverr.com/junaidzx90
 * Text Domain:       productlocker
 * Domain Path:       /languages
 */

// If this file is called directly, abort.

define( 'PRODLCR_NAME', 'productlocker' );
define( 'PRODLCR_PATH', plugin_dir_path( __FILE__ ) );

if ( ! defined( 'WPINC' ) && ! defined('PRODLCR_NAME') && ! defined('PRODLCR_PATH')) {
	die;
}

function productlocker_admin_noticess(){
    $message = sprintf(
        /* translators: 1: Plugin Name 2: Elementor */
        print_r( '%1$s requires <a href="https://wordpress.org/plugins/woocommerce/"> %2$s </a> to be installed and activated.', 'productlocker' ),
        '<strong>' . esc_html__( 'ProductLocker', 'productlocker' ) . '</strong>',
        '<strong>' . esc_html__( 'WooCommerce', 'productlocker' ) . '</strong>'
    );

    printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
}

add_action( 'plugins_loaded', 'productlocker_init' );
function productlocker_init() {
    if(!class_exists('WooCommerce')){
        add_action( 'admin_notices', 'productlocker_admin_noticess' );
    }else{
        load_plugin_textdomain( 'productlocker', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

        add_action('init', 'productlocker_run');
    }
}

// Main Function iitialize
function productlocker_run(){
    /**
     * {---CUSTOM FIELD FOR PRODUCT SETUP---}
     */
    require_once PRODLCR_PATH.'inc/productlocker-product-setup.php';

    register_activation_hook( __FILE__, 'activate_productlocker_cplgn' );
    register_deactivation_hook( __FILE__, 'deactivate_productlocker_cplgn' );

    // Activision function
    function activate_productlocker_cplgn(){
        // global $wpdb;
        // require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        // $productlocker_v1 = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}productlocker_v1` (
        //     `ID` INT NOT NULL AUTO_INCREMENT,
        //     `user_id` INT NOT NULL,
        //     `product_id` INT NOT NULL,
        //     `validity` INT NOT NULL,
        //     PRIMARY KEY (`ID`)) ENGINE = InnoDB";
        //     dbDelta($productlocker_v1);
    }

    // Dectivision function
    function deactivate_productlocker_cplgn(){
        // Nothing For Now
    }

    // Admin Enqueue Scripts
    add_action('admin_enqueue_scripts',function(){
        wp_register_style( PRODLCR_NAME, plugin_dir_url( __FILE__ ).'admin/css/productlocker-admin.css', array(), microtime(), 'all' );
        wp_enqueue_style(PRODLCR_NAME);

        wp_register_script( PRODLCR_NAME, plugin_dir_url( __FILE__ ).'admin/js/productlocker-admin.js', array(), 
        microtime(), true );
        wp_enqueue_script(PRODLCR_NAME);
        // I recommend to add additional conditions just to not to load the scipts on each page
 
        if ( ! did_action( 'wp_enqueue_media' ) ) {
            wp_enqueue_media();
        }
        wp_localize_script( PRODLCR_NAME, 'admin_ajax_action', array(
            'ajaxurl' => admin_url( 'admin-ajax.php' )
        ) );
    });

    // WP Enqueue Scripts
    add_action('wp_enqueue_scripts',function(){
        wp_register_style( PRODLCR_NAME, plugin_dir_url( __FILE__ ).'public/css/productlocker-public.css', array(), microtime(), 'all' );
        wp_enqueue_style(PRODLCR_NAME);

        wp_register_script( PRODLCR_NAME, plugin_dir_url( __FILE__ ).'public/js/productlocker-public.js', array(), 
        microtime(), true );
        wp_enqueue_script(PRODLCR_NAME);
        wp_localize_script( PRODLCR_NAME, 'public_ajax_action', array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'nonce' => wp_create_nonce( 'nonces' )
        ) );
    });

    add_shortcode( 'lcrplacebtn', 'productshow_callback' );
    function productshow_callback($content){
        global $post;
        ob_start();
        ?>
        <div id="payment_form"></div>
        <div class="lockedview">
            <img width="300" src="<?php echo plugin_dir_url(__FILE__); ?>images/locked.png" alt="">

            <div class="locked_button_wrap">
               <?php echo do_shortcode( '[wppayform id="154"]' ); ?>
            </div>
        </div>
        
        <?php
        return ob_get_clean();
    }

    // Menu callback funnction
    function productlocker_menupage_display(){
        if(class_exists('WC_Subscriptions')){
            wp_enqueue_script(PRODLCR_NAME);
            ?>
            <h1>Menu page</h1>
            <?php
        }
    }
}