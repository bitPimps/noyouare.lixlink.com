<?php
/**
  * Buttons selection page
  *
  **/
  
class wpsr_admin_sharebar{
    
    function __construct(){
        
        add_filter( 'wpsr_register_admin_page', array( $this, 'register' ) );
        
    }
    
    function register( $pages ){
        
        $pages[ 'sharebar' ] = array(
            'name' => 'Floating sharebar',
            'banner' => WPSR_ADMIN_URL . '/images/banners/floating-sharebar.svg',
            'page_callback' => array( $this, 'page' ),
            'feature' => true,
            'form' => array(
                'id' => 'sharebar_settings',
                'name' => 'sharebar_settings',
                'callback' => array( $this, 'form_fields' ),
                'validation' => array( $this, 'validation' ),
            )
        );
        
        return $pages;
        
    }
    
    function form_fields( $values ){
        
        $values = WPSR_Lists::set_defaults( $values, WPSR_Lists::defaults( 'sharebar' ) );
        
        $feature = array(
            'name' => 'sharebar',
            'hide_services' => array()
        );
        
        $section0 = array(
            array( __( 'Select to enable or disable sharebar feature', 'wpsr' ), WPSR_Admin::field( 'select', array(
                'name' => 'ft_status',
                'value' => $values[ 'ft_status' ],
                'list' => array(
                    'enable' => __( 'Enable sharebar', 'wpsr' ),
                    'disable' => __( 'Disable sharebar', 'wpsr' )
                ),
            )), 'class="ft_table"' ),
        );
        
        WPSR_Admin::build_table( $section0, __( 'Enable/disable sharebar', 'wpsr' ), '', false, '1' );
        
        echo '<div class="feature_wrap">';

        $sb_types = array(
            'vertical' => array( __( 'Vertical', 'wpsr' ), 'sb-vertical.png', '100px' ),
            'horizontal' => array( __( 'Horizontal', 'wpsr' ), 'sb-horizontal.png', '100px' )
        );
        
        $sb_bar_positions = array(
            'vertical' => array(
                'scontent' => __( 'Stick to content', 'wpsr' ),
                'wleft' => __( 'Left of the window', 'wpsr' ),
                'wright' => __( 'Right of the window', 'wpsr' )
            ),
            'horizontal' => array(
                'wbottom' => __( 'Bottom of the window', 'wpsr' ),
                'wtop' => __( 'Top of the window', 'wpsr' )
            )
        );
        
        $section1 = array(
            array( __( 'Sharebar type', 'wpsr' ), WPSR_Admin::field( 'image_select', array(
                'name' => 'type',
                'value' => $values['type'], 
                'list' => $sb_types
            ))),
            
            array( __( 'Vertical sharebar position', 'wpsr' ), WPSR_Admin::field( 'select', array(
                'name' => 'vl_position',
                'value' => $values['vl_position'], 
                'list' => $sb_bar_positions[ 'vertical' ], 
                'helper' => __( 'The position of the vertical sharebar in the page', 'wpsr' )
            )), 'data-conditioner data-condr-input="[name=type]" data-condr-value="vertical" data-condr-action="simple?show:hide" data-condr-events="change"'),
            
            array( __( 'Horizontal sharebar position', 'wpsr' ), WPSR_Admin::field( 'select', array(
                'name' => 'hl_position',
                'value' => $values['hl_position'], 
                'list' => $sb_bar_positions[ 'horizontal' ], 
                'helper' => __( 'The position of the horizontal sharebar in the page', 'wpsr' )
            )), 'data-conditioner data-condr-input="[name=type]" data-condr-value="horizontal" data-condr-action="simple?show:hide" data-condr-events="change"'),
            
            array( __( 'ID or class name of the content to stick with', 'wpsr' ), WPSR_Admin::field( 'text', array(
                'name' => 'stick_element',
                'value' => $values['stick_element'],
                'placeholder' => 'Ex: #content',
                'qtip' => 'https://www.youtube.com/watch?v=GQ1YO0xZ7WA'
            )), 'class="tbl_row_stick_cnt"' ),
            
        );
        
        WPSR_Admin::build_table( $section1, __( 'Sharebar type', 'wpsr' ), '', false, '2' );
        
        WPSR_Admin::box_wrap( 'open', __( 'Add buttons to the sharebar', 'wpsr' ), __( 'Add sharing buttons to the sharebar template below', 'wpsr' ), '3' );
        
        WPSR_Admin::buttons_veditor( "buttons", $values['buttons'], false, false );
        WPSR_Buttons_Picker::print_buttons_picker($feature);
        
        WPSR_Admin::box_wrap( 'close' );
        
        $themes = array(
            'simple' => __( 'Simple', 'wpsr' ),
            'simple-sq' => __( 'Simple - Square edges', 'wpsr' ),
            'simple-ns' => __( 'Simple - No Shadow', 'wpsr' ),
            'simple-icon-counter' => __( 'Simple - Sharebar with icons and counter', 'wpsr' ),
        );
        
        $mv_sharebar = array(
            'move' => __( 'Move when page scrolls', 'wpsr' ),
            'static' => __( 'Static, no movement', 'wpsr' )
        );
        
        $section2 = array(
            
            array( __( 'Theme', 'wpsr' ), WPSR_Admin::field( 'select', array(
                'name' => 'theme',
                'value' => $values['theme'], 
                'list' => apply_filters( 'wpsr_mod_sharebar_themes', $themes ),
            ))),
            
            /*
            array( 'Horizontal sharebar width', WPSR_Admin::field( 'text', array(
                'name' => 'hl_width',
                'value' => $values['hl_width'],
                'placeholder' => 'Enter width of the sharebar. Ex: 800px',
            )), 'data-conditioner data-condr-input="[name=type]" data-condr-value="horizontal" data-condr-action="simple?show:hide" data-condr-events="change" '),
            */
            
            array( __( 'Sharebar movement', 'wpsr' ), WPSR_Admin::field( 'select', array(
                'name' => 'vl_movement',
                'value' => $values['vl_movement'], 
                'list' => $mv_sharebar,
            )), 'data-conditioner data-condr-input="[name=type]" data-condr-value="vertical" data-condr-action="simple?show:hide" data-condr-events="change" '),
            
            array( __( 'Background color', 'wpsr' ), WPSR_Admin::field( 'text', array(
                'name' => 'bg_color',
                'value' => $values['bg_color'],
                'class' => 'color_picker',
                'helper' => __( 'Set empty value for transparency', 'wpsr' ),
            ))),
            
            array( __( 'Border color', 'wpsr' ), WPSR_Admin::field( 'text', array(
                'name' => 'border_color',
                'value' => $values['border_color'],
                'class' => 'color_picker',
            ))),
            
            array( __( 'Initial state', 'wpsr' ), WPSR_Admin::field( 'select', array(
                'name' => 'init_state',
                'value' => $values['init_state'], 
                'list' => array(
                    'open' => __( 'Opened', 'wpsr' ),
                    'close' => __( 'Closed', 'wpsr' )
                ),
            ))),
            
            array( __( 'Offset from window/content', 'wpsr' ), WPSR_Admin::field( 'text', array(
                'name' => 'offset',
                'value' => $values['offset'],
                'tip' => __( 'Pixels to offset the sharebar. You can also use the "adjust" button to edit visually.', 'wpsr' ),
            )) . ' <button class="button button-primary sharebar_preview_btn" title="' . __( 'Edit the offset of the sharebar visually', 'wpsr' ) . '"><i class="fa fa-cog"></i> ' . __( 'Adjust position visually', 'wpsr' ) . '</button><div class="sharebar_preview_iwrap"><iframe src=""></iframe></div>' ),
            
            array( __( 'Additional CSS styles', 'wpsr' ), WPSR_Admin::field( 'text', array(
                'name' => 'css_class',
                'value' => $values['css_class'],
                'placeholder' => __( 'Enter CSS class name separated by comma', 'wpsr' ),
            ))),
            
            array( __( 'Minimize when screen width is less than', 'wpsr' ), WPSR_Admin::field( 'text', array(
                'name' => 'min_on_width',
                'value' => $values['min_on_width'],
                'type' => 'number',
                'helper' => __( 'in pixels. 0 to disable.' )
            ))),
            
        );
        
        WPSR_Admin::build_table( $section2, __( 'Customization', 'wpsr' ), '', false, '4' );
        
        // Location rules
        WPSR_Admin::box_wrap( 'open', __( 'Conditions to display the sharebar', 'wpsr' ), __( 'Choose the below options to select the pages which will display the sharebar.', 'wpsr' ), '5' );
        WPSR_Location_Rules::display_rules( "loc_rules", $values['loc_rules'] );
        WPSR_Admin::box_wrap( 'close' );
        
        echo '<script>jQuery(document).ready(function(){
            jQuery( ".tbl_row_stick_cnt" ).conditioner({
                conditions: [
                    {
                        input: "[name=type]",
                        type: "simple",
                        value: "vertical"
                    },
                    {
                        input: "[name=vl_position]",
                        type: "simple",
                        value: "scontent"
                    }
                ],
                events: "click",
                onTrue: function(){  jQuery(this).show();  },
                onFalse: function(){  jQuery(this).hide();  }
            });
        });</script>';
        
        echo '</div>';
        
    }
    
    
    function page(){
        
        WPSR_Admin::settings_form( 'sharebar' );
        
    }
    
    function validation( $input ){
        return $input;
    }
    
}

new wpsr_admin_sharebar();

?>