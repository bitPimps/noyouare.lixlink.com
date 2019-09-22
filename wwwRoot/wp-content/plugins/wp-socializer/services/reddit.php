<?php
/**
  * Reddit service for WP Socializer
  *
  */

class wpsr_service_reddit{
    
    function __construct(){
        
        add_filter( 'wpsr_register_service', array( $this, 'register' ) );
        
        $this->default_values = array(
            'type' => 'button1',
        );
        
    }
    
    function register( $services ){
        
        $services[ 'reddit' ] = array(
            'name' => 'Reddit',
            'icons' => WPSR_ADMIN_URL . '/images/icons/reddit.png',
            'desc' => __( 'Create Reddit share buttons', 'wpsr' ),
            'settings' => array( 'size' => '500x200' ),
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
        
        $out = array();
        $settings = WPSR_Lists::set_defaults( $settings, $this->default_values );
        $html = '';
        
        $html = '<script type="text/javascript">reddit_url = "' . esc_attr( $page_info[ 'url' ] ) . '"; reddit_title = "' . esc_attr( $page_info[ 'title' ] ) . '"; reddit_newwindow="1"</script>';
        $html .= '<script type="text/javascript" src="//www.redditstatic.com/button/' . esc_attr( $settings[ 'type' ] ) . '.js"></script>';
        
        $out['html'] = $html;
        $out['includes'] = array( '' );
        return $out;
        
    }
    
    function includes(){
        
        $includes = array();
        
        return $includes;
        
    }

    function settings( $values ){
        
        $values = WPSR_Lists::set_defaults( $values, $this->default_values );
        
        $section1 = array(
            array( __( 'Button type', 'wpsr' ), WPSR_Admin::field( 'select', array(
                'name' => 'o[type]',
                'value' => $values['type'],
                'list' => array(
                    'button1' => 'Horizontal',
                    'button2' => 'Vertical - Type 1',
                    'button3' => 'Vertical - Type 2',
                ),
                'tip' => WPSR_ADMIN_URL . '/images/tips/reddit-types.png'
            ))),
        );

        WPSR_Admin::build_table( $section1, '', '', true);
        
    }

    function validation( $values ){
        
        return $values;
        
    }
    
    function general_settings( $values ){
    
    }
    
    function general_settings_validation( $values ){
        return $values;
    }
    
    function templates(){
        
        return array(
            'reddit_hl'=> array(
                'settings'=> array()
            ),
            'reddit_vl'=> array(
                'settings'=> array(
                    'type'=> 'button2'
                )
            )
        );
        
    }
    
}

new wpsr_service_reddit();

?>