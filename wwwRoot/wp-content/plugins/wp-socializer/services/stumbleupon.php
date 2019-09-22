<?php
/**
  * StumbleUpon service for WP Socializer
  *
  */

class wpsr_service_stumbleupon{
    
    function __construct(){
        
        WPSR_Services::register( 'stumbleupon', array(
            'name' => 'StumbleUpon',
            'icons' => WPSR_ADMIN_URL . '/images/icons/stumbleupon.png',
            'desc' => __( 'Create StumbleUpon buttons', 'wpsr' ),
            'settings' => array( 'size' => '500x200' ),
            'callbacks' => array(
                'output' => array( $this, 'output' ),
                'includes' => array( $this, 'includes' ),
                'settings' => array( $this, 'settings' ),
                'validation' => array( $this, 'validation' ),
                'general_settings' => array( $this, 'general_settings' ),
                'general_settings_validation' => array( $this, 'general_settings_validation' ),
            )
        ));
        
        $this->default_values = array(
            'type' => '1'
        );
        
    }

    function output( $settings = array(), $page_info = array() ){
        
        $out = array();
        $settings = WPSR_Lists::set_defaults( $settings, $this->default_values );
        
        $out['html'] = '<su:badge layout="' . esc_attr( $settings[ 'type' ] ) . '" location="' . esc_attr( $page_info[ 'url' ] ) . '"></su:badge>';
        $out['includes'] = array( 'stumbleupon_main_js' );
        return $out;
        
    }
    
    function includes(){
        
        $includes = array(
            'stumbleupon_main_js' => array(
                'type' => 'js',
                'code' => "<script type='text/javascript'>
  (function() {
    var li = document.createElement('script'); li.type = 'text/javascript'; li.async = true;
    li.src = ('https:' == document.location.protocol ? 'https:' : 'http:') + '//platform.stumbleupon.com/1/widgets.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(li, s);
  })();
</script>"
            )
        );
        
        return $includes;
        
    }

    function settings( $values ){
        
        $values = WPSR_Lists::set_defaults( $values, $this->default_values );
        
        $section1 = array(
            array( __( 'Button type', 'wpsr' ), WPSR_Admin::field( 'select', array(
                'name' => 'o[type]',
                'value' => $values['type'],
                'list' => array(
                    '1' => 'Small, count on right, square box',
                    '2' => 'Small, count on right, round box',
                    '3' => 'Small, count on right, no box',
                    '4' => 'Small, no count',
                    '5' => 'Big, count on top, square box',
                    '6' => 'Big, no count',
                ),
                'tip' => WPSR_ADMIN_URL . '/images/tips/stumbleupon-types.png'
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
    
}

new wpsr_service_stumbleupon();

?>