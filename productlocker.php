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

define( 'PLCR_NAME', 'productlocker' );
define( 'PLCR_PATH', plugin_dir_path( __FILE__ ) );

if ( ! defined( 'WPINC' ) && ! defined('PLCR_NAME') && ! defined('PLCR_PATH')) {
	die;
}

add_action( 'plugins_loaded', 'productlocker_init' );
function productlocker_init() {
    if(!class_exists('WC_Subscriptions')){
        add_action( 'admin_notices', 'productlocker_admin_noticess' );
    }

    load_plugin_textdomain( 'productlocker', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

    add_action('init', 'productlocker_run');
}

// Main Function iitialize
function productlocker_run(){

    function productlocker_admin_noticess(){
        $message = sprintf(
            /* translators: 1: Plugin Name 2: Elementor */
            esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'productlocker' ),
            '<strong>' . esc_html__( 'productlocker', 'productlocker' ) . '</strong>',
            '<strong>' . esc_html__( 'Woocommerce Subscriptions', 'productlocker' ) . '</strong>'
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }

    register_activation_hook( __FILE__, 'activate_productlocker_cplgn' );
    register_deactivation_hook( __FILE__, 'deactivate_productlocker_cplgn' );

    // Activision function
    function activate_productlocker_cplgn(){
        // global $wpdb;
        // require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        // $productlocker_v1 = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}productlocker__v1` (
        //     `ID` INT NOT NULL AUTO_INCREMENT,
        //     `user_id` INT NOT NULL,
        //     `username` VARCHAR(255) NOT NULL,
        //     `account1` INT NOT NULL,
        //     `account2` INT NOT NULL,
        //     PRIMARY KEY (`ID`)) ENGINE = InnoDB";
        //     dbDelta($productlocker_v1);
    }

    // Dectivision function
    function deactivate_productlocker_cplgn(){
        // Nothing For Now
    }

    // Admin Enqueue Scripts
    add_action('admin_enqueue_scripts',function(){
        wp_register_script( PLCR_NAME, plugin_dir_url( __FILE__ ).'admin/js/productlocker-admin.js', array(), 
        microtime(), true );
        wp_enqueue_script(PLCR_NAME);
        wp_localize_script( PLCR_NAME, 'admin_ajax_action', array(
            'ajaxurl' => admin_url( 'admin-ajax.php' )
        ) );
    });

    // WP Enqueue Scripts
    add_action('wp_enqueue_scripts',function(){
        wp_register_style( PLCR_NAME, plugin_dir_url( __FILE__ ).'public/css/productlocker-public.css', array(), microtime(), 'all' );
        wp_enqueue_style(PLCR_NAME);

        wp_register_script( PLCR_NAME, plugin_dir_url( __FILE__ ).'public/js/productlocker-public.js', array(), 
        microtime(), true );
        wp_enqueue_script(PLCR_NAME);
        wp_localize_script( PLCR_NAME, 'public_ajax_action', array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'nonce' => wp_create_nonce( 'nonces' )
        ) );
    });

    // Register Menu
    add_action('admin_menu', function(){
        add_menu_page( 'ProductLocker', 'ProductLocker', 'manage_options', 'productlocker', 'productlocker_menupage_display', 'dashicons-admin-network', 45 );
    
        // // For colors
        // add_settings_section( 'productlocker_colors_section', 'Activation Colors', '', 'productlocker_colors' );
    
        // //Activate Button
        // add_settings_field( 'productlocker_activate_button', 'Activate Button', 'productlocker_activate_button_func', 'productlocker_colors', 'productlocker_colors_section');
        // register_setting( 'productlocker_colors_section', 'productlocker_activate_button');
        
    });

    // activate Button colors
    // function productlocker_activate_button_func(){
        
    // }

    // productlocker_reset_colors
    // add_action("wp_ajax_productlocker_reset_colors", "productlocker_reset_colors");
    // add_action("wp_ajax_nopriv_productlocker_reset_colors", "productlocker_reset_colors");

    // Menu callback funnction
    function productlocker_menupage_display(){
        if(class_exists('WC_Subscriptions')){
            wp_enqueue_script(PLCR_NAME);
            ?>
            <h1>Menu page</h1>
            <?php
            // echo '<form action="options.php" method="post" id="productlocker_colors">';
            // echo '<h1>Activation Colors</h1><hr>';
            // echo '<table class="form-table">';

            // settings_fields( 'productlocker_colors_section' );
            // do_settings_fields( 'productlocker_colors', 'productlocker_colors_section' );
            
            // echo '</table>';
            // submit_button();
            // echo '<button id="rest_color">Reset</button>';
            // echo '</form>';
        }
    }
}