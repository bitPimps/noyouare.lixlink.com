<?php
/**
  * General settings admin page
  *
  **/
  
class wpsr_admin_settings{
    
    function __construct(){
        
        add_filter( 'wpsr_register_admin_page', array( $this, 'register' ) );
        
        add_action( 'wpsr_form_general_settings', array( $this, 'misc_general_settings' ), 10, 1 );
        
    }
    
    function register( $pages ){
        
        $pages[ 'general_settings' ] = array(
            'name' => 'Settings',
            'page_callback' => array( $this, 'page' ),
            'banner' => WPSR_ADMIN_URL . '/images/banners/settings.png',
            'feature' => false,
            'form' => array(
                'id' => 'general_settings',
                'name' => 'general_settings',
                'callback' => ''
            )
        );
        
        return $pages;
        
    }
    
    function page(){
        
        WPSR_Admin::settings_form( 'general_settings' );
    }
    
    function validation( $input ){
        return $input;
    }
    
    function misc_general_settings( $values ){
        
        $values = WPSR_Lists::set_defaults( $values, WPSR_Lists::defaults( 'gsettings_misc' ) );
        
        $font_icons = WPSR_Lists::font_icons();
        $font_icons_list = array();
        
        foreach( $font_icons as $id => $prop ){
            $font_icons_list[$id] = $prop['name'];
        }
        
        $inc_list = WPSR_Includes::list_all();
        $inc_text = '<code>' . implode('</code>, <code>', array_keys($inc_list) ) . '</code>';
        
        $section1 = array(

            array( __( 'Font icon', 'wpsr' ), WPSR_Admin::field( 'select', array(
                'name' => 'font_icon',
                'value' => $values['font_icon'], 
                'list' => $font_icons_list
            ))),
        
            array( __( 'Additional CSS rules', 'wpsr' ), WPSR_Admin::field( 'textarea', array(
                'name' => 'misc_additional_css',
                'value' => $values['misc_additional_css'],
                'helper' => __( 'Enter custom CSS rules to customize without the style tag', 'wpsr' ),
                'rows' => '3',
                'cols' => '100'
            ))),
            
            array( __( 'CSS/JS to not to load in any page', 'wpsr' ), WPSR_Admin::field( 'text', array(
                'name' => 'skip_res_load',
                'value' => $values['skip_res_load'],
                'helper' => __( 'Enter the ID of the CSS/JS resources to not to load in any page. <a href="#" class="tblr_btn" data-id="res_info_box">Click here</a> to see the list of resources. <div class="hidden" data-tglr="res_info_box"><p>' . $inc_text . '</p> <p>Enter the IDs separated by comma. <b>Note: Many of the resources are intelligently loaded based on buttons used in the page. Please use this field only after discussion with the developer.</b></p></div>', 'wpsr' )
            )))
            
        );

        WPSR_Admin::build_table( $section1, __( 'Miscellaneous settings', 'wpsr' ) );
    }
    
}

new wpsr_admin_settings();

?>