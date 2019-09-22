<?php
/**
  * Text sharebar settings page
  *
  **/
  
class wpsr_admin_text_sharebar{
    
    function __construct(){
        
        add_filter( 'wpsr_register_admin_page', array( $this, 'register' ) );
        
    }
    
    function register( $pages ){
        
        $pages[ 'text_sharebar' ] = array(
            'name' => 'Text sharebar',
            'page_callback' => array( $this, 'page' ),
            'banner' => WPSR_ADMIN_URL . '/images/banners/text-sharebar.svg',
            'feature' => true,
            'form' => array(
                'id' => 'text_sharebar_settings',
                'name' => 'text_sharebar_settings',
                'callback' => array( $this, 'form_fields' ),
                'validation' => array( $this, 'validation' ),
            )
        );
        
        return $pages;
        
    }
    
    function form_fields( $values ){
        
        $values = WPSR_Lists::set_defaults( $values, WPSR_Lists::defaults( 'text_sharebar' ) );
        
        $section0 = array(
            array( __( 'Select to enable or disable text sharebar feature', 'wpsr' ), WPSR_Admin::field( 'select', array(
                'name' => 'ft_status',
                'value' => $values[ 'ft_status' ],
                'list' => array(
                    'enable' => __( 'Enable text sharebar', 'wpsr' ),
                    'disable' => __( 'Disable text sharebar', 'wpsr' )
                ),
            )), 'class="ft_table"' ),
        );
        
        WPSR_Admin::build_table( $section0, __( 'Enable/disable text sharebar', 'wpsr' ), '', false, '1' );
        
        echo '<div class="feature_wrap">';
        
        $sb_sites = WPSR_Lists::social_icons();
        
        WPSR_Admin::box_wrap( 'open', __( 'Add buttons to text sharebar', 'wpsr' ), __( 'Select buttons from the list below and add it to the selected list.', 'wpsr' ), '2' );
        echo '<table class="form-table ssb_tbl"><tr><td width="90%">';
        echo '<select class="ssb_list widefat">';
        foreach( $sb_sites as $id=>$prop ){
            if( in_array( 'for_tsb', $prop[ 'features' ] ) ){
                echo '<option value="' . $id . '" data-color="' . $prop['colors'][0] . '">' . $prop[ 'name' ] . '</option>';
            }
        }
        echo '</select>';
        echo '</td><td>';
        echo '<button class="button button-primary ssb_add">' . __( 'Add button', 'wpsr' ) . '</button>';
        echo '</td></tr></table>';
        
        echo '<h4>' . __( 'Selected buttons', 'wpsr' ) . '</h4>';
        
        $decoded = base64_decode( $values[ 'template' ] );
        $tsb_btns = json_decode( $decoded );
        
        if( !is_array( $tsb_btns ) ){
            $tsb_btns = array();
        }
        
        echo '<ul class="ssb_selected_list clearfix">';
        if( count( $tsb_btns ) > 0 ){
            foreach( $tsb_btns as $tsb_item ){
                $sb_info = $sb_sites[ $tsb_item ];
                echo '<li title="' . $sb_info[ 'name' ] . '" data-id="' . $tsb_item . '" style="background-color:' . $sb_info['colors'][0] . '"><i class="' . $sb_info[ 'icon' ] . '"></i> <span class="ssb_remove" title="' . __( 'Delete button', 'wpsr' ) . '">x</span></li>';
            }
        }else{
            echo '<span class="ssb_empty">' . __( 'No buttons are selected for text sharebar', 'wpsr' ) . '</span>';
        }
        echo '</ul>';
        echo '<input type="hidden" name="template" class="ssb_template" value="' . $values[ 'template' ] . '"/>';
        
        WPSR_Admin::box_wrap( 'close' );
        
        # Settings form
        $section2 = array(
            
            array( __( 'ID or class name of the content to show text sharebar', 'wpsr' ), WPSR_Admin::field( 'text', array(
                'name' => 'content',
                'value' => $values['content'],
                'placeholder' => 'Ex: .entry-content',
                'qtip' => 'https://www.youtube.com/watch?v=GQ1YO0xZ7WA'
            ))),
            
            array( __( 'Button size', 'wpsr' ), WPSR_Admin::field( 'image_select', array(
                'name' => 'size',
                'value' => $values['size'], 
                'list' => array(
                    '32px' => array( '32px', 'size.svg', '32px' ),
                    '40px' => array( '40px', 'size.svg', '40px' ),
                    '48px' => array( '48px', 'size.svg', '48px' ),
                    '64px' => array( '64px', 'size.svg', '64px' ),
                ),
                'tip' => WPSR_ADMIN_URL . '/images/tips/tsb-sizes.png'
            ))),
            
            array( __( 'Background color', 'wpsr' ), WPSR_Admin::field( 'text', array(
                'name' => 'bg_color',
                'value' => $values['bg_color'],
                'class' => 'color_picker'
            ))),
            
            array( __( 'Icon color', 'wpsr' ), WPSR_Admin::field( 'text', array(
                'name' => 'icon_color',
                'value' => $values['icon_color'],
                'class' => 'color_picker'
            ))),
            
            array( __( 'Maximum word count to quote', 'wpsr' ), WPSR_Admin::field( 'text', array(
                'type' => 'number',
                'name' => 'text_count',
                'value' => $values['text_count'],
                'helper' => __( 'Set value to 0 to include all the selected text', 'wpsr' )
            ))),
            
        );
        WPSR_Admin::build_table( $section2, __( 'Settings', 'wpsr' ), '', false, '3' );
        
        // Location rules
        WPSR_Admin::box_wrap( 'open', __( 'Conditions to display the text sharebar', 'wpsr' ), __( 'Choose the below options to select the pages which will display the text sharebar.', 'wpsr' ), '4' );
        WPSR_Location_Rules::display_rules( "loc_rules", $values['loc_rules'] );
        WPSR_Admin::box_wrap( 'close' );
        
        echo '</div>';
        
        echo '<script>';
        echo 'var sb_sites = ' . json_encode( $sb_sites ) . ';';
        echo '</script>';
        
    }
    
    
    function page(){
        
        WPSR_Admin::settings_form( 'text_sharebar' );
        
    }
    
    function validation( $input ){
        return $input;
    }
    
}

new wpsr_admin_text_sharebar();

?>