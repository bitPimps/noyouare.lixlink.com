<?php
/**
  * Google Plus service for WP Socializer
  *
  */

class wpsr_service_google_plus{
    
    function __construct(){
        
        WPSR_Services::register( 'google_plus', array(
            'name' => 'Google Plus',
            'icons' => WPSR_ADMIN_URL . '/images/icons/google-plus.png',
            'desc' => __( 'Create Google Plus +1 buttons', 'wpsr' ),
            'settings' => array( 'size' => '500x280' ),
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
            'size' => 'standard',
            'annotation' => 'inline',
            'width' => '',
        );
        
    }

    function output( $settings = array(), $page_info = array() ){
        
        $out = array();
        $settings = WPSR_Lists::set_defaults( $settings, $this->default_values );
        
        $width = ( $settings[ 'annotation' ] == 'inline' ) ? 'data-width="' . esc_attr( $settings[ 'width' ] ) . '"' : '';
        
        $out['html'] = '<div class="g-plusone" data-size="' . esc_attr( $settings[ 'size' ] ) . '" data-annotation="' . esc_attr( $settings['annotation'] ) . '" ' . $width . '></div>';

        $out['includes'] = array( 'google_plus_main_js' );
        return $out;
        
    }
    
    function includes(){
        
        $gs = WPSR_Lists::set_defaults( get_option( 'wpsr_general_settings' ), WPSR_Lists::defaults( 'gsettings_googleplus' ) );
        $lang_code = $gs[ 'googleplus_lang' ];
        
        $lang_var = '';
        if( $lang_code != 'en-US' ){
            $lang_var = '{lang: "' . $lang_code . '"}';
        }
        
        $includes = array(
            'google_plus_main_js' => array(
                'type' => 'js',
                'code' => '<script src="https://apis.google.com/js/platform.js" async defer>' . $lang_var . '</script>'
            )
        );
        
        return $includes;
        
    }

    function settings( $values ){
        
        $values = WPSR_Lists::set_defaults( $values, $this->default_values );
        
        $section1 = array(
            array( __( 'Button size', 'wpsr' ), WPSR_Admin::field( 'select', array(
                'name' => 'o[size]',
                'value' => $values['size'],
                'list' => array(
                    'small' => 'Small',
                    'medium' => 'Medium',
                    'standard' => 'Standard',
                    'tall' => 'Tall'
                ),
                'tip' => WPSR_ADMIN_URL . '/images/tips/googleplus-sizes.png'
            ))),
            
            array( __( 'Bubble type', 'wpsr' ), WPSR_Admin::field( 'select', array(
                'name' => 'o[annotation]',
                'value' => $values['annotation'], 
                'class' => 'gp_bubble_type',
                'list' => array(
                    'bubble' => 'Normal',
                    'inline' => 'Full',
                    'none' => 'None'
                )
            ))),
            
            array( __( 'Width of button', 'wpsr' ), WPSR_Admin::field( 'text', array(
                'type' => 'number',
                'name' => 'o[width]',
                'value' => $values['width'], 
                'placeholder' => __( 'Width in pixels', 'wpsr' )
            )), 'data-conditioner data-condr-input=".gp_bubble_type" data-condr-value="inline" data-condr-action="simple?show:hide" data-condr-events="click"'),
            
        );

        WPSR_Admin::build_table( $section1, '', '', true);
        
        echo '<script>if( jQuery.fn.conditioner ) jQuery("[data-conditioner]").conditioner();</script>';
        
    }

    function validation( $values ){
        
        return $values;
        
    }
    
    function general_settings( $values ){
        
        $values = WPSR_Lists::set_defaults( $values, WPSR_Lists::defaults( 'gsettings_googleplus' ) );
        
        $section1 = array(
            array( __( 'Google Plus language', 'wpsr' ), WPSR_Admin::field( 'select', array(
                'name' => 'googleplus_lang',
                'value' => $values['googleplus_lang'], 
                'list' => WPSR_Lists::lang_codes( 'google_plus' )
            ))),
        );

        WPSR_Admin::build_table( $section1, 'Google Plus settings');
        
    }
    
    function general_settings_validation( $values ){
        return $values;
    }
    
}

new wpsr_service_google_plus();

/**
 * Google Plus widget
 */

class wpsr_widget_googleplus{
    
    function __construct(){
        
        WPSR_Widgets::register( 'googleplus', array(
            'name' => 'Google Plus badge',
            'banner' => WPSR_ADMIN_URL . '/images/widgets/google-plus.png',
            'description' => __( 'The native Google Plus widget to promote a Google Plus page or person', 'wpsr' ),
            'callbacks' => array(
                'widget' => array( $this, 'widget' ),
                'form' => array( $this, 'form' ),
                'update' => array( $this, 'update' )
            )
        ));
        
        $this->defaults = array(
            'gp_widget_url' => '',
            'gp_widget_layout' => 'potrait',
            'gp_widget_width' => '300',
            'gp_widget_theme' => 'light',
            'gp_widget_cover_photo' => 'true',
            'gp_widget_tag_line' => 'true',
            'gp_widget_type' => 'person'
        );
        
    }
    
    function widget( $args, $instance ){
        
        $instance = WPSR_Lists::set_defaults( $instance, $this->defaults );
        
        echo '<div class="g-' . $instance[ 'gp_widget_type' ] . '" data-width="' . $instance[ 'gp_widget_width' ] . '" data-href="' . $instance[ 'gp_widget_url' ] . '" data-theme="' . $instance[ 'gp_widget_theme' ] . '" data-layout="' . $instance[ 'gp_widget_layout' ] . '" data-showtagline="' . $instance[ 'gp_widget_tag_line' ] . '" data-showcoverphoto="' . $instance[ 'gp_widget_cover_photo' ] . '" data-rel="publisher"></div>';
        
        WPSR_Includes::add_active_includes( array( 'google_plus_main_js' ) );
        
    }
    
    function form( $obj, $instance ){
        
        $instance = WPSR_Lists::set_defaults( $instance, $this->defaults );
        $fields = new WPSR_Widget_Form_Fields( $obj, $instance );
        
        $yesno = array(
            'true' => __( 'Yes', 'wpsr' ),
            'false' => __( 'No', 'wpsr' )
        );
        
        echo '<h4>' . __( 'Google Plus widget settings', 'wpsr' ) . '</h4>';
        $fields->text( 'gp_widget_url', 'Enter a Google+ profile or page URL', array( 'placeholder' => 'Ex: https://plus.google.com/u/0/101375276491818686057' ) );
        $fields->select( 'gp_widget_type', 'Widget type', array( 'person' => 'Person', 'page' => 'Page' ), array( 'class' => 'smallfat' ) );
        $fields->number( 'gp_widget_width', 'Widget width ( in pixels )' );
        $fields->select( 'gp_widget_theme', 'Widget theme', array( 'light' => 'Light', 'dark' => 'Dark' ), array( 'class' => 'smallfat' ) );
        $fields->select( 'gp_widget_layout', 'Widget layout', array( 'potrait' => 'Potrait', 'landscape' => 'Landscape' ), array( 'class' => 'smallfat' ) );
        
        echo '<h5>If layout is potrait</h5>';
        
        $fields->select( 'gp_widget_cover_photo', 'Show cover photo', $yesno, array( 'class' => 'smallfat' ) );
        $fields->select( 'gp_widget_tag_line', 'Show tag line', $yesno, array( 'class' => 'smallfat' ) );
        
    }
    
    function update( $instance ){
        return $instance;
    }
    
}

new wpsr_widget_googleplus();

?>