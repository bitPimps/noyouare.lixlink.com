<?php
/**
  * Share counter service for WP Socializer
  *
  */

class wpsr_service_share_counter{
    
    function __construct(){
        
        add_filter( 'wpsr_register_service', array( $this, 'register' ) );
        
        $this->default_values = array(
            'text' => 'Shares',
            'counter_color' => '#000',
            'add_services' => array(),
            'orientation' => 'vl'
        );
        
    }
    
    function register( $services ){
        
        $services[ 'share_counter' ] = array(
            'name' => 'Share Counter',
            'icons' => WPSR_ADMIN_URL . '/images/icons/share_counter.png',
            'desc' => __( 'Display share counter', 'wpsr' ),
            'settings' => array( 'size' => '500x350' ),
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
        
        $total_holder = WPSR_Share_Counter::placeholder( $page_info[ 'url' ], $settings[ 'add_services' ] );
        
        $html .= '<div class="wpsr-counter ' . ( ( $settings[ 'orientation' ] == 'hl' ) ? 'wpsrc-hl' : '' ) . '">';
        $html .= '<span class="scount" style="color:' . esc_attr( $settings[ 'counter_color' ] ) . '">' . $total_holder . '</span>';
        $html .= '<small class="stext">' . $settings[ 'text' ] . '</small>';
        $html .= '</div>';
        
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
        
        $counter_services = WPSR_Share_Counter::counter_services();
        $available_counters = array();
        
        foreach( $counter_services as $cid => $cprop ){
            $available_counters[ $cid ] = $cprop[ 'name' ];
        }
        
        $section1 = array(
            array( __( 'Share counts to add', 'wpsr' ), WPSR_Admin::field( 'checkboxes', array(
                'name' => 'o[add_services]',
                'value' => $values['add_services'],
                'list' => $available_counters
            ))),
            
            array( __( 'Counter text', 'wpsr' ), WPSR_Admin::field( 'text', array(
                'name' => 'o[text]',
                'value' => $values['text']
            ))),
            
            array( __( 'Counter color', 'wpsr' ), WPSR_Admin::field( 'text', array(
                'name' => 'o[counter_color]',
                'value' => $values['counter_color'],
                'class' => 'wp-color'
            ))),
            
            array( 'Counter orientation', WPSR_Admin::field( 'select', array(
                'name' => 'o[orientation]',
                'value' => $values['orientation'],
                'list' => array(
                    'vl' => 'Vertical',
                    'hl' => 'Horizontal'
                ),
            ))),
            
        );

        WPSR_Admin::build_table( $section1, '', '', true);
        
    }

    function validation( $values ){
        
        return $values;
        
    }
    
    function general_settings( $values ){
        
        $values = WPSR_Lists::set_defaults( $values, WPSR_Share_Counter::$default_settings );
        
        $section1 = array(
            array( __( 'Counter cache expiration duration', 'wpsr' ), WPSR_Admin::field( 'text', array(
                'name' => 'counter_expiration',
                'value' => $values['counter_expiration'], 
                'helper' => __( 'Enter the number of seconds to cache the social service counter in DB. Note: If duration is small, counter synchronization will be frequent and site can slow down. Ideal duration to cache the entries would be 86400 seconds ( 1 day ). Min duration which can be set is 1800 secs ( 1/2 hrs )', 'wpsr' ),
            ))),
            
            array( __( 'Load share count for both http and https URL of the page ?', 'wpsr' ), WPSR_Admin::field( 'select', array(
                'name' => 'counter_both_protocols',
                'value' => $values['counter_both_protocols'], 
                'list' => array(
                    'no' => 'No',
                    'yes' => 'Yes'
                ),
                'helper' => __( 'Note: This may slow down the first ajax request made by the page after the counter cache has expired as per the above setting', 'wpsr' )
            ))),
            
        );

        WPSR_Admin::build_table( $section1, 'Counter settings' );
    
    }
    
    function general_settings_validation( $values ){
        
        if( intval( $values[ 'counter_expiration' ] ) < 1800 ){
            $values[ 'counter_expiration' ] = 1800;
        }
        
        return $values;
    }
    
    function templates(){
        
        return array(
            'share_counter_vl'=> array(
              'settings'=> array(
                'add_services'=> [
                  'facebook',
                  'googleplus',
                  'linkedin',
                  'pinterest',
                  'stumbleupon'
                ],
                'text'=> 'Shares',
                'counter_color'=> '#000'
              )
            ),

            'share_counter_hl'=> array(
              'settings'=> array(
                'add_services'=> [
                  'facebook',
                  'googleplus',
                  'linkedin',
                  'pinterest',
                  'stumbleupon'
                ],
                'text'=> 'Shares',
                'counter_color'=> '#000',
                'orientation'=> 'hl'
              )
            )
        );
        
    }
    
}

new wpsr_service_share_counter();

?>