<?php
/**
 * Plugin Name:       WP Socializer
 * Plugin URI:        https://www.aakashweb.com/wordpress-plugins/wp-socializer/
 * Description:       WP Socializer is an all in one complete social media plugin to add native social media buttons, icons, floating sharebar, follow us buttons, profile icons, mobile sharebar and selected text share popups easily with complete control and customization.
 * Version:           4.1.6
 * Author:            Aakash Chakravarthy
 * Author URI:        https://www.aakashweb.com
 * Text Domain:       wpsr
 * Domain Path:       /languages
 */

define( 'WPSR_VERSION', '4.1.6' );
define( 'WPSR_PATH', plugin_dir_path( __FILE__ ) ); // All have trailing slash
define( 'WPSR_URL', plugin_dir_url( __FILE__ ) );
define( 'WPSR_ADMIN_URL', trailingslashit( plugin_dir_url( __FILE__ ) . 'admin' ) );
define( 'WPSR_BASE_NAME', plugin_basename( __FILE__ ) );
define( 'WPSR_SETUP_VERSION', '1.0' );

//error_reporting(E_ALL);

final class WP_Socializer{
    
    function __construct(){
        
        add_action( 'plugins_loaded', array( $this, 'load_text_domain' ) );
        
        $this->includes();
        
    }

    function includes(){
        
        // Core
        include_once( WPSR_PATH . 'core/services.php' );
        include_once( WPSR_PATH . 'core/lists.php' );
        include_once( WPSR_PATH . 'core/metadata.php' );
        include_once( WPSR_PATH . 'core/buttons.php' );
        include_once( WPSR_PATH . 'core/templates.php' );
        include_once( WPSR_PATH . 'core/location_rules.php' );
        include_once( WPSR_PATH . 'core/includes.php' );
        include_once( WPSR_PATH . 'core/share_counter.php' );
        include_once( WPSR_PATH . 'core/widgets.php' );
        include_once( WPSR_PATH . 'core/import_export.php' );
        
        // Services
        include_once( WPSR_PATH . 'services/twitter.php' );
        include_once( WPSR_PATH . 'services/social_icons.php' );
        include_once( WPSR_PATH . 'services/facebook.php' );
        include_once( WPSR_PATH . 'services/linkedin.php' );
        include_once( WPSR_PATH . 'services/reddit.php' );
        include_once( WPSR_PATH . 'services/sharethis.php' );
        include_once( WPSR_PATH . 'services/pinterest.php' );
        include_once( WPSR_PATH . 'services/pocket.php' );
        include_once( WPSR_PATH . 'services/html.php' );
        include_once( WPSR_PATH . 'services/share_counter.php' );
        
        // Admin
        include_once( WPSR_PATH . 'admin/admin.php' );
        include_once( WPSR_PATH . 'admin/buttons.php' );
        include_once( WPSR_PATH . 'admin/sharebar.php' );
        include_once( WPSR_PATH . 'admin/followbar.php' );
        include_once( WPSR_PATH . 'admin/text_sharebar.php' );
        include_once( WPSR_PATH . 'admin/mobile_sharebar.php' );
        include_once( WPSR_PATH . 'admin/widgets.php' );
        include_once( WPSR_PATH . 'admin/import_export.php' );
        include_once( WPSR_PATH . 'admin/general_settings.php' );
        include_once( WPSR_PATH . 'admin/buttons_picker.php' );
        include_once( WPSR_PATH . 'admin/setup.php' );
        
    }
    
    function load_text_domain(){
        load_plugin_textdomain( 'wpsr', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
    }
    
}

$wpsr = new WP_Socializer();

?>