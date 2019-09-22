<?php
/**
  * Pinterest service for WP Socializer
  *
  */

class wpsr_service_pinterest{
    
    function __construct(){
        
        add_filter( 'wpsr_register_service', array( $this, 'register' ) );
        
        $this->default_values = array(
            'type' => 'save',
            'save_shape' => 'normal',
            'save_size' => 'normal'
        );
        
    }
    
    function register( $services ){
        
        $services[ 'pinterest' ] = array(
            'name' => 'Pinterest',
            'icons' => WPSR_ADMIN_URL . '/images/icons/pinterest.png',
            'desc' => 'A simple description for the Pinterest button',
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
        
        $out = array();
        $settings = WPSR_Lists::set_defaults( $settings, $this->default_values );
        $html = '';
        
        $btn_type = $settings[ 'type' ];
        $out['includes'] = array();
        
        if ( $btn_type == 'save' ){
            $pin_save = ( $settings[ 'save_shape' ] == 'round' ) ? ' data-pin-round="true" data-pin-save="false"' : ' data-pin-save="true"';
            $pin_size = ( $settings[ 'save_size' ] == 'large' ) ? ' data-pin-tall="true"' : '';
            
            $html = '<a data-pin-do="buttonBookmark" ' . $pin_save . $pin_size . ' href="https://www.pinterest.com/pin/create/button/"><img src="//assets.pinterest.com/images/pidgets/pinit_fg_en_round_red_16.png" /></a>';
            
            $out['includes'] = array( 'pinterest_main_js' );
        }
        
        if ( $btn_type == 'follow' ){
            $html = '<a data-pin-do="buttonFollow" href="' . esc_attr( $settings[ 'follow_url' ] ) . '">' . $settings[ 'follow_name' ] . '</a>';
            $out['includes'] = array( 'pinterest_main_js' );
        }
        
        if ( $btn_type == 'image_hover' ){
            $html = '';
            $out['includes'] = array( 'pinterest_image_hover_js' );
        }
        
        $out['html'] = $html;
        return $out;
        
    }
    
    function includes(){
        
        $includes = array(
            'pinterest_main_js' => array(
                'type' => 'js',
                'code' => '<script async defer src="//assets.pinterest.com/js/pinit.js"></script>'
            ),
            'pinterest_image_hover_js' => array(
                'type' => 'js',
                'code' => '<script async defer data-pin-hover="true" data-pin-round="true" data-pin-save="false" src="//assets.pinterest.com/js/pinit.js"></script>'
            )
        );
        
        return $includes;
        
    }

    function settings( $values ){
        
        $values = WPSR_Lists::set_defaults( $values, $this->default_values );
        
        $section1 = array(
            array( 'Button type', WPSR_Admin::field( 'select', array(
                'name' => 'o[type]',
                'value' => $values['type'],
                'list' => array(
                    'save' => 'Save Button',
                    'follow' => 'Follow button',
                    'image_hover' => 'Image hover button',
                ),
                'class' => 'pt_btn_type'
            ))),
        );

        WPSR_Admin::build_table( $section1, '', '', true);
        
        echo '<div data-conditioner data-condr-input=".pt_btn_type" data-condr-value="save" data-condr-action="simple?show:hide" data-condr-events="click">';
        
        $section2 = array(
            array( 'Save button size', WPSR_Admin::field( 'select', array(
                'name' => 'o[save_size]',
                'value' => $values['save_size'],
                'list' => array(
                    'normal' => 'Normal',
                    'large' => 'Large'
                ),
            ))),
            
            array( 'Save button Shape', WPSR_Admin::field( 'select', array(
                'name' => 'o[save_shape]',
                'value' => $values['save_shape'],
                'list' => array(
                    'normal' => 'Normal',
                    'round' => 'Round'
                ),
            ))),
            
        );

        WPSR_Admin::build_table( $section2, '', '', true);
        
        echo '</div>';
        
        echo '<div data-conditioner data-condr-input=".pt_btn_type" data-condr-value="follow" data-condr-action="simple?show:hide" data-condr-events="click">';
        
        $section3 = array(
            array( 'Pinterest URL', WPSR_Admin::field( 'text', array(
                'name' => 'o[follow_url]',
                'value' => $values['follow_url'],
                'placeholder' => 'URL of the pinterest profile'
            ))),
            
            array( 'Name on button', WPSR_Admin::field( 'text', array(
                'name' => 'o[follow_name]',
                'value' => $values['follow_name'],
                'placeholder' => 'Name to be displayed on follow button'
            ))),
            
        );

        WPSR_Admin::build_table( $section3, '', '', true);
        
        echo '</div>';
        
        echo '<script>if( jQuery.fn.conditioner ) jQuery("[data-conditioner]").conditioner();</script>';
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
            'pinterest_hl'=> array(
                'settings'=> array()
            ),
            'pinterest_hl_lg'=> array(
                'settings'=> array(
                    'save_size'=> 'large'
                )
            )
        );
        
    }
    
}

new wpsr_service_pinterest();

?>