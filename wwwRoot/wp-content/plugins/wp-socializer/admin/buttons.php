<?php
/**
  * Buttons selection page
  *
  **/
  
class wpsr_admin_buttons{
    
    function __construct(){
        
        add_filter( 'wpsr_register_admin_page', array( $this, 'register' ) );
        
    }
    
    function register( $pages ){
        
        $pages[ 'buttons' ] = array(
            'name' => 'Share buttons',
            'banner' => WPSR_ADMIN_URL . '/images/banners/share-buttons.svg',
            'page_callback' => array( $this, 'page' ),
            'feature' => true,
            'form' => array(
                'id' => 'button_settings',
                'name' => 'button_settings',
                'callback' => array( $this, 'form_fields' ),
                'validation' => array( $this, 'validation' ),
            )
        );
        
        return $pages;
        
    }
    
    function section_templates( $values, $i ){
        
        if( !isset( $values[ 'tmpl' ][ $i ] ) ){
            $values[ 'tmpl' ][ $i ] = WPSR_Lists::defaults( 'buttons_template' );
        }else{
            $values[ 'tmpl' ][ $i ] = WPSR_Lists::set_defaults( $values[ 'tmpl' ][ $i ], WPSR_Lists::defaults( 'buttons_template' ) );
        }
        
        echo '<div class="template_wrap" data-id="' . $i . '">';

        WPSR_Admin::box_wrap( 'open', __( 'Add buttons to the template', 'wpsr' ), __( 'Add buttons to the template using the add sharing button below. Click "+" to add a new row. Click and drag row to rearrange the order.', 'wpsr' ), '2' );
        WPSR_Admin::buttons_veditor( "tmpl[$i][content]", $values['tmpl'][$i]['content'], true, 'wpsr_preview_template_buttons' );
        WPSR_Admin::box_wrap( 'close' );
        
        $positions = array(
            'above_posts' => __( 'Above posts', 'wpsr' ),
            'below_posts' => __( 'Below posts', 'wpsr' ),
            'above_below_posts' => __( 'Both above and below posts', 'wpsr' )
        );
        
        // Position rules
        WPSR_Admin::box_wrap( 'open', __( 'Position of template in the page', 'wpsr' ), __( 'Choose the below options to select the position the template in a page.', 'wpsr' ), '3' );
        
        echo WPSR_Admin::field( 'radio', array(
            'name' => 'tmpl[' . $i . '][position]',
            'list' => $positions,
            'value' => $values[ 'tmpl' ][ $i ][ 'position' ],
            'default' => 'above_below_post',
        ));
        
        echo '<hr/><p>' . __( 'Select whether to show this template in excerpts', 'wpsr' ) . '</p>';
        
        echo WPSR_Admin::field( 'radio', array(
            'name' => 'tmpl[' . $i . '][in_excerpt]',
            'list' => array( 'show' => __( 'Show in excerpt', 'wpsr' ), 'hide' => __( 'Hide in excerpt', 'wpsr' ) ),
            'value' => $values[ 'tmpl' ][ $i ][ 'in_excerpt' ],
            'default' => 'hide',
        ));
        
        echo '<hr/><p>' . __( 'Hide template when screen width is less than the width below', 'wpsr' ) . '</p>';
        
        echo WPSR_Admin::field( 'text', array(
            'type' => 'number',
            'name' => 'tmpl[' . $i . '][min_on_width]',
            'value' => $values[ 'tmpl' ][ $i ][ 'min_on_width' ],
            'class' => '',
            'helper' => __( 'in pixels. 0 to disable.' )
        ));
        
        WPSR_Admin::box_wrap( 'close' );
        
        // Location rules
        WPSR_Admin::box_wrap( 'open', __( 'Conditions to display the template', 'wpsr' ), __( 'Choose the below options to select the pages which will display the template.', 'wpsr' ), '4' );
        WPSR_Location_Rules::display_rules( "tmpl[$i][loc_rules]", $values['tmpl'][$i]['loc_rules'] );
        WPSR_Admin::box_wrap( 'close' );
        
        echo '</div>'; // template_wrap
        
    }
    
    function form_fields( $values ){
        
        $values = WPSR_Lists::set_defaults( $values, array(
            'ft_status' => 'enable',
            'tmpl' => array()
        ));
        
        $feature = array(
            'name' => 'buttons',
            'hide_services' => array()
        );
        
        WPSR_Buttons_Picker::print_buttons_picker($feature);
        
        $section0 = array(
            array( __( 'Select to enable or disable share buttons feature', 'wpsr' ), WPSR_Admin::field( 'select', array(
                'name' => 'ft_status',
                'value' => $values[ 'ft_status' ],
                'list' => array(
                    'enable' => __( 'Enable sharebar', 'wpsr' ),
                    'disable' => __( 'Disable sharebar', 'wpsr' )
                ),
            )), 'class="ft_table"' ),
        );
        
        WPSR_Admin::build_table( $section0, __( 'Enable/disable share buttons', 'wpsr' ), '', false, '1' );
        
        echo '<div class="feature_wrap">';
        
        $template_count = 3;
        
        echo '<ul class="template_tab">';
        for( $i = 1; $i <= $template_count; $i++ ){
            echo '<li>Template ' . $i . '</li>';
        }
        echo '</ul>';

        for( $i=1; $i<=$template_count; $i++ ){
            $this->section_templates( $values, $i );
        }
        
        echo '</div>';
        
    }
    
    
    function page(){
        
        // Add settings form
        WPSR_Admin::settings_form( 'buttons' );
        
    }
    
    function validation( $input ){
        return $input;
    }
    
}

new wpsr_admin_buttons();

?>