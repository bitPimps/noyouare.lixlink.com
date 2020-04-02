<?php
/**
  * Floating sharebar settings page
  *
  **/
  
class wpsr_admin_floating_sharebar{
    
    function __construct(){
        
        add_filter( 'wpsr_register_admin_page', array( $this, 'register' ) );
        
    }
    
    function register( $pages ){
        
        $pages[ 'floating_sharebar' ] = array(
            'name' => 'Floating sharebar',
            'page_callback' => array( $this, 'page' ),
            'banner' => WPSR_ADMIN_URL . '/images/banners/floating-sharebar-2.svg',
            'feature' => true,
            'class' => 'new_ft',
            'form' => array(
                'id' => 'floating_sharebar_settings',
                'name' => 'floating_sharebar_settings',
                'callback' => array( $this, 'form_fields' ),
                'validation' => array( $this, 'validation' ),
            )
        );
        
        return $pages;
        
    }

    function form_fields( $values ){

        $values = WPSR_Lists::set_defaults( $values, WPSR_Lists::defaults( 'floating_sharebar' ) );

        $section0 = array(
            array( __( 'Select to enable or disable floating sharebar feature', 'wpsr' ), WPSR_Admin::field( 'select', array(
                'name' => 'ft_status',
                'value' => $values[ 'ft_status' ],
                'list' => array(
                    'enable' => __( 'Enable floating sharebar', 'wpsr' ),
                    'disable' => __( 'Disable floating sharebar', 'wpsr' )
                ),
            )), 'class="ft_table"' ),
        );

        WPSR_Admin::build_table( $section0, __( 'Enable/disable floating sharebar', 'wpsr' ), '', false, '1' );

        $icon_settings = array( 'icon', 'hover_text', 'html' );
        WPSR_Icons_Editor::commons( $icon_settings );

        echo '<div class="feature_wrap">';

        WPSR_Admin::box_wrap( 'open', __( 'Choose the social icons', 'wpsr' ), __( 'Add social icons to the template, re-arrange them and configure individual icon settings.', 'wpsr' ), '2' );
        WPSR_Icons_Editor::editor( $values[ 'selected_icons' ], 'selected_icons' );
        WPSR_Admin::box_wrap( 'close' );

        $customization = array(

            array(__( 'Icon size', 'wpsr' ), WPSR_Admin::field( 'image_select', array(
                'name' => 'icon_size',
                'value' => $values['icon_size'],
                'list' => array(
                    '32px' => array( '32px', 'size.svg', '32px' ),
                    '40px' => array( '40px', 'size.svg', '40px' ),
                    '48px' => array( '48px', 'size.svg', '48px' ),
                    '64px' => array( '64px', 'size.svg', '64px' ),
                ),
            ))),

            array( __( 'Icon shape', 'wpsr' ), WPSR_Admin::field( 'image_select', array(
                'name' => 'icon_shape',
                'value' => $values['icon_shape'],
                'class' => 'setting_shape',
                'list' => array(
                    '' => array( 'Sqaure', 'shape-square.svg', '32px' ),
                    'circle' => array( 'Circle', 'shape-circle.svg', '32px' ),
                    'squircle' => array( 'Squircle', 'shape-squircle.svg', '32px' ),
                    'squircle-2' => array( 'Squircle 2', 'shape-squircle-2.svg', '32px' ),
                    'diamond' => array( 'Diamond*', 'shape-diamond.svg', '32px' ),
                    'ribbon' => array( 'Ribbon*', 'shape-ribbon.svg', '32px' ),
                    'drop' => array( 'Drop*', 'shape-drop.svg', '32px' ),
                ),
                'helper' => 'Note: Shapes marked * might not react well to certain hover effects and share counter styles.'
            ))),

            array( __( 'Hover effects', 'wpsr' ), WPSR_Admin::field( 'select', array(
                'name' => 'hover_effect',
                'value' => $values['hover_effect'],
                'list' => array(
                    '' => __( 'None', 'wpsr' ),
                    'opacity' => 'Opacity',
                    'rotate' => 'Rotate',
                    'zoom' => 'Zoom',
                    'shrink' => 'Shrink',
                    'float' => 'Float',
                    'sink' => 'Sink',
                    'fade-white' => 'Fade to white',
                    'fade-black' => 'Fade to black'
                ),
            ))),

            array( __( 'Icon color', 'wpsr' ), WPSR_Admin::field( 'text', array(
                'name' => 'icon_color',
                'value' => $values['icon_color'],
                'class' => 'color_picker',
                'helper' => __( 'Set empty value to use brand color', 'wpsr' )
            ))),

            array( __( 'Icon background color', 'wpsr' ), WPSR_Admin::field( 'text', array(
                'name' => 'icon_bg_color',
                'value' => $values['icon_bg_color'],
                'class' => 'color_picker',
                'helper' => __( 'Set empty value to use brand color', 'wpsr' )
            ))),

            array( __( 'Space between the icons', 'wpsr' ), WPSR_Admin::field( 'select', array(
                'name' => 'padding',
                'value' => $values['padding'],
                'list' => array(
                    '' => 'No',
                    'pad' => 'Yes'
                ),
                'helper' => __( 'Select to add padding between the icons', 'wpsr' ),
            )), 'data-conditioner data-condr-input=".setting_shape" data-condr-value="" data-condr-action="simple?show:hide" data-condr-events="change"'),

            array('STYLE', ''),

            array( __( 'Sharebar style', 'wpsr' ), WPSR_Admin::field( 'image_select', array(
                'name' => 'style',
                'value' => $values['style'],
                'class' => 'setting_sb_style',
                'list' => array(
                    '' => array( 'Simple', 'layout-vertical.svg', '64px' ),
                    'enclosed' => array( 'Enclosed', 'fsb-enclosed.svg', '64px' ),
                ),
            ))),

            array( __( 'Sharebar background color', 'wpsr' ), WPSR_Admin::field( 'text', array(
                'name' => 'sb_bg_color',
                'value' => $values['sb_bg_color'],
                'class' => 'color_picker',
            )), 'data-conditioner data-condr-input=".setting_sb_style" data-condr-value="enclosed" data-condr-action="simple?show:hide" data-condr-events="change"'),

            array('POSITION', ''),

            array( __( 'Position of the sharebar', 'wpsr' ), WPSR_Admin::field( 'select', array(
                'name' => 'sb_position',
                'value' => $values['sb_position'],
                'class' => 'setting_sb_position',
                'list' => array(
                    'wleft' => 'Left of the page',
                    'wright' => 'Right of the page',
                    'scontent' => 'Stick to the content'
                ),
            ))),

            array( __( 'ID or class name of the content to stick with', 'wpsr' ), WPSR_Admin::field( 'text', array(
                'name' => 'stick_element',
                'value' => $values['stick_element'],
                'placeholder' => 'Ex: #content',
                'qtip' => 'https://www.youtube.com/watch?v=GQ1YO0xZ7WA'
            )), 'data-conditioner data-condr-input=".setting_sb_position" data-condr-value="scontent" data-condr-action="simple?show:hide" data-condr-events="change"' ),

            array( __( 'Offset from the window', 'wpsr' ), WPSR_Admin::field( 'text', array(
                'name' => 'offset',
                'value' => $values[ 'offset' ],
                'class' => '',
                'helper' => __( 'Example: 20px (or) 10%' )
            ))),

            array( __( 'Sharebar movement', 'wpsr' ), WPSR_Admin::field( 'select', array(
                'name' => 'movement',
                'value' => $values['movement'], 
                'list' => array(
                    'move' => __( 'Move when page scrolls', 'wpsr' ),
                    'static' => __( 'Static, no movement', 'wpsr' )
                ),
            ))),

            array('SHARE COUNTER', ''),

            array( __( 'Share counter', 'wpsr' ), WPSR_Admin::field( 'select', array(
                'name' => 'share_counter',
                'value' => $values['share_counter'],
                'class' => 'setting_share_counter',
                'list' => array(
                    '' => 'No share count',
                    'individual' => 'Individual count',
                    'total' => 'Total count only',
                    'total-individual' => 'Both individual and total counts',
                ),
            ))),

            array( __( 'Share counter style', 'wpsr' ), WPSR_Admin::field( 'image_select', array(
                'name' => 'sc_style',
                'value' => $values['sc_style'],
                'list' => array(
                    'count-1' => array( 'Style 1', 'counter-1.svg', '60px' ),
                    'count-2' => array( 'Style 2', 'counter-2.svg', '70px' ),
                    'count-3' => array( 'Style 3', 'counter-3.svg', '70px' ),
                ),
                'helper' => __( 'To show count, in the same page under icons list, select an icon and enable gear icon &gt; show count', 'wpsr' )
            )), 'data-conditioner data-condr-input=".setting_share_counter" data-condr-value="individual" data-condr-action="pattern?show:hide" data-condr-events="change"'),

            array( __( 'Total share count position', 'wpsr' ), WPSR_Admin::field( 'select', array(
                'name' => 'sc_total_position',
                'value' => $values['sc_total_position'],
                'list' => array(
                    'top' => 'Above the icons',
                    'bottom' => 'Below the icons'
                ),
            )), 'data-conditioner data-condr-input=".setting_share_counter" data-condr-value="total" data-condr-action="pattern?show:hide" data-condr-events="change"'),

            array( __( 'Total share count color', 'wpsr' ), WPSR_Admin::field( 'text', array(
                'name' => 'sc_total_color',
                'value' => $values['sc_total_color'],
                'class' => 'color_picker',
                'helper' => 'Leave blank to use default color'
            )), 'data-conditioner data-condr-input=".setting_share_counter" data-condr-value="total" data-condr-action="pattern?show:hide" data-condr-events="change"'),

            array('RESPONSIVENESS', ''),

            array( __( 'Responsive width', 'wpsr' ), WPSR_Admin::field( 'text', array(
                'name' => 'responsive_width',
                'value' => $values[ 'responsive_width' ],
                'type' => 'number',
                'class' => 'setting_responsive_width',
                'helper' => __( 'The screen size below which the sharebar will switch the below responsive mode. Value should be in pixels. Set 0 to disable this.' )
            ))),

            array( __( 'Responsive action', 'wpsr' ), WPSR_Admin::field( 'select', array(
                'name' => 'responsive_action',
                'value' => $values['responsive_action'],
                'list' => array(
                    'minimize' => 'Minimize the share bar',
                    'hide' => 'Hide the share bar'
                ),
            )), 'data-conditioner data-condr-input=".setting_responsive_width" data-condr-value="0" data-condr-action="simple?hide:show" data-condr-events="keyup change"'),

            array('MISC', ''),

            array( __( 'Initial state', 'wpsr' ), WPSR_Admin::field( 'select', array(
                'name' => 'init_state',
                'value' => $values['init_state'], 
                'list' => array(
                    'open' => __( 'Opened', 'wpsr' ),
                    'close' => __( 'Closed', 'wpsr' )
                ),
            ))),

            array( __( 'No of icons in the last to group', 'wpsr' ), WPSR_Admin::field( 'select', array(
                'name' => 'more_icons',
                'value' => $values['more_icons'],
                'list' => array(
                    '0' => 'No grouping',
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                    '7' => '7',
                    '8' => '8',
                    '9' => '9',
                    '10' => '10',
                ),
                'helper' => __( 'The last icons grouped will be displayed in a "More" icons menu', 'wpsr' )
            ))),

        );

        WPSR_Admin::build_table( $customization, __( 'Customization', 'wpsr' ), '', false, '3' );

        // Location rules
        WPSR_Admin::box_wrap( 'open', __( 'Conditions to display the sharebar', 'wpsr' ), __( 'Choose the below options to select the pages which will display the sharebar.', 'wpsr' ), '4' );
        WPSR_Location_Rules::display_rules( "loc_rules", $values['loc_rules'] );
        WPSR_Admin::box_wrap( 'close' );

        echo '</div>';

    }
    
    
    function page(){
        
        WPSR_Admin::settings_form( 'floating_sharebar' );
        
    }
    
    function validation( $input ){

        if( $input['ft_status'] == 'enable' ){
            $sb_settings = get_option( 'wpsr_sharebar_settings' );
            $sb_settings[ 'ft_status' ] = 'disable';
            update_option( 'wpsr_sharebar_settings', $sb_settings );
        }

        return $input;
    }
    
}

new wpsr_admin_floating_sharebar();

?>