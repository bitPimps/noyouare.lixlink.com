<?php
/**
  * Share this service for WP Socializer
  *
  */

class wpsr_service_sharethis{
    
    function __construct(){
        
        add_filter( 'wpsr_register_service', array( $this, 'register' ) );
        
    }
    
    function register( $services ){
        
        $services[ 'sharethis' ] = array(
            'name' => 'Share this',
            'icons' => WPSR_ADMIN_URL . '/images/icons/sharethis.png',
            'desc' => __( 'Create Sharethis buttons like social icons with counter', 'wpsr' ),
            'settings' => array( 'size' => '500x300' ),
            'callbacks' => array(
                'output' => array( $this, 'output' ),
                'includes' => array( $this, 'includes' ),
                'settings' => array( $this, 'settings' ),
                'validation' => array( $this, 'validation' ),
                'general_settings' => array( $this, 'general_settings' ),
                'general_settings_validation' => array( $this, 'general_settings_validation' ),
                'templates' => array( $this, 'templates' )
            )
        );
        
        return $services;
        
    }
    
    function output( $settings = array(), $page_info = array() ){
        
        $html = '<div class="sharethis-inline-share-buttons" data-url="' . $page_info[ 'url' ] . '" data-title="' . $page_info[ 'title' ] . '" data-description="' . $page_info[ 'excerpt' ] . '" data-image="' . $page_info[ 'post_image' ] . '"></div>';
        
        $out['html'] = $html;
        $out['includes'] = array( 'sharethis_main_js' );
        return $out;
        
    }
    
    function includes(){
        
        $gs = WPSR_Lists::set_defaults( get_option( 'wpsr_general_settings' ), WPSR_Lists::defaults( 'gsettings_sharethis' ) );
        $prop_id = esc_attr( $gs[ 'st_prop_id' ] );
        
        $includes = array(
            'sharethis_main_js' => array(
                'type' => 'js',
                'code' => '<script type="text/javascript" src="//platform-api.sharethis.com/js/sharethis.js#property=' . $prop_id . '&product=inline-share-buttons" async="async"></script>'
            )
        );
        
        return $includes;
        
    }

    function settings( $values ){
        
        $gs = WPSR_Lists::set_defaults( get_option( 'wpsr_general_settings' ), WPSR_Lists::defaults( 'gsettings_sharethis' ) );
        $prop_id = esc_attr( $gs[ 'st_prop_id' ] );
        
        echo '<div class="notice notice-warning"><p>' . __( 'Note: Sharethis is upgraded to the latest version. Old sharethis buttons are deprecated by Sharethis itself. Customize the new Sharethis button below.', 'wpsr' ) . '</p></div>';
        
        if( trim( $prop_id ) == '' ){
            echo '<div class="notice notice-error"><p>' . __( 'Note: Sharethis property ID is empty. Please update it <a href="admin.php?page=wp_socializer&tab=general_settings">in this page</a>', 'wpsr' ) . '</p></div>';
        }
        
        echo '<br/><p align="center"><a class="button button-primary" href="https://platform.sharethis.com/inline-share-buttons" target="_blank"><i class="fa fa-cog"></i> ' . __( 'Click here to customize Sharethis inline buttons' ) . '</a></p>';
    }

    function validation( $values ){
        
        return $values;
        
    }
    
    function general_settings( $values ){
        
        $values = WPSR_Lists::set_defaults( $values, WPSR_Lists::defaults( 'gsettings_sharethis' ) );
        
        $section1 = array(
            array( __( 'Property ID', 'wpsr' ), WPSR_Admin::field( 'text', array(
                'name' => 'st_prop_id',
                'value' => $values['st_prop_id'],
                'helper' => sprintf( __( 'Property ID can be found <a href="%s" target="_blank">in this page</a>.','wpsr' ), 'https://platform.sharethis.com/settings' )
            )))
        );

        WPSR_Admin::build_table( $section1, 'Sharethis settings');
    }
    
    function general_settings_validation( $values ){
        return $values;
    }
    
    function templates(){
        
        return array();
        
    }
    
}

new wpsr_service_sharethis();

?>