<?php
/**
  * Social icons settings page
  *
  **/
  
class wpsr_admin_social_icons{
    
    function __construct(){
        
        add_filter( 'wpsr_register_admin_page', array( $this, 'register' ) );
        
    }
    
    function register( $pages ){
        
        $pages[ 'social_icons' ] = array(
            'name' => 'Social icons',
            'page_callback' => array( $this, 'page' ),
            'banner' => WPSR_ADMIN_URL . '/images/banners/social-icons.svg',
            'feature' => true,
            'class' => 'new_ft',
            'form' => array(
                'id' => 'social_icons_settings',
                'name' => 'social_icons_settings',
                'callback' => array( $this, 'form_fields' ),
                'validation' => array( $this, 'validation' ),
            )
        );
        
        return $pages;
        
    }

    function template( $values, $i ){

        if( !isset( $values[ 'tmpl' ][ $i ] ) ){
            $values[ 'tmpl' ][ $i ] = WPSR_Lists::defaults( 'social_icons_template' );
            if( $i == 2 ){
                $values[ 'tmpl' ][ $i ][ 'selected_icons' ] = 'W10=';
                $values[ 'tmpl' ][ $i ][ 'share_counter' ] = '';
                $values[ 'tmpl' ][ $i ][ 'heading' ] = '';
            }
        }else{
            $values[ 'tmpl' ][ $i ] = WPSR_Lists::set_defaults( $values[ 'tmpl' ][ $i ], WPSR_Lists::defaults( 'social_icons_template' ) );
        }

        echo '<div class="template_wrap" data-id="' . $i . '">';

        WPSR_Admin::box_wrap( 'open', __( 'Choose the social icons', 'wpsr' ), __( 'Add social icons to the template, re-arrange them and configure individual icon settings.', 'wpsr' ), '2' );
        WPSR_Icons_Editor::editor( $values[ 'tmpl' ][ $i ][ 'selected_icons' ], 'tmpl[' . $i . '][selected_icons]' );
        WPSR_Admin::box_wrap( 'close' );

        $customization = array(

            array( __( 'Icon layout', 'wpsr' ), WPSR_Admin::field( 'image_select', array(
                'name' => 'tmpl[' . $i . '][layout]',
                'value' => $values[ 'tmpl' ][ $i ]['layout'],
                'class' => 'setting_btn_layout' . $i,
                'list' => array(
                    '' => array( 'Normal', 'layout-horizontal.svg', '64px' ),
                    'fluid' => array( 'Full width', 'layout-fluid.svg', '64px' ),
                ),
            ))),

            array(__( 'Icon size', 'wpsr' ), WPSR_Admin::field( 'image_select', array(
                'name' => 'tmpl[' . $i . '][icon_size]',
                'value' => $values[ 'tmpl' ][ $i ]['icon_size'],
                'list' => array(
                    '32px' => array( '32px', 'size.svg', '32px' ),
                    '40px' => array( '40px', 'size.svg', '40px' ),
                    '48px' => array( '48px', 'size.svg', '48px' ),
                    '64px' => array( '64px', 'size.svg', '64px' ),
                ),
            ))),

            array( __( 'Icon shape', 'wpsr' ), WPSR_Admin::field( 'image_select', array(
                'name' => 'tmpl[' . $i . '][icon_shape]',
                'value' => $values[ 'tmpl' ][ $i ]['icon_shape'],
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
            )), 'data-conditioner data-condr-input=".setting_btn_layout' . $i . '" data-condr-value="" data-condr-action="simple?show:hide" data-condr-events="change"'),

            array( __( 'Hover effects', 'wpsr' ), WPSR_Admin::field( 'select', array(
                'name' => 'tmpl[' . $i . '][hover_effect]',
                'value' => $values[ 'tmpl' ][ $i ]['hover_effect'],
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
                'name' => 'tmpl[' . $i . '][icon_color]',
                'value' => $values[ 'tmpl' ][ $i ]['icon_color'],
                'class' => 'color_picker',
                'helper' => __( 'Set empty value to use brand color', 'wpsr' )
            ))),

            array( __( 'Icon background color', 'wpsr' ), WPSR_Admin::field( 'text', array(
                'name' => 'tmpl[' . $i . '][icon_bg_color]',
                'value' => $values[ 'tmpl' ][ $i ]['icon_bg_color'],
                'class' => 'color_picker',
                'helper' => __( 'Set empty value to use brand color', 'wpsr' )
            ))),

            array( __( 'Gutters', 'wpsr' ), WPSR_Admin::field( 'select', array(
                'name' => 'tmpl[' . $i . '][padding]',
                'value' => $values[ 'tmpl' ][ $i ]['padding'],
                'list' => array(
                    '' => 'No',
                    'pad' => 'Yes'
                ),
                'helper' => __( 'Select to add space between icons', 'wpsr' ),
            ))),

            array('SHARE COUNTER', ''),

            array( __( 'Share counter', 'wpsr' ), WPSR_Admin::field( 'select', array(
                'name' => 'tmpl[' . $i . '][share_counter]',
                'value' => $values[ 'tmpl' ][ $i ]['share_counter'],
                'class' => 'setting_share_counter' . $i,
                'list' => array(
                    '' => 'No share count',
                    'individual' => 'Individual count',
                    'total' => 'Total count only',
                    'total-individual' => 'Both individual and total counts',
                ),
            ))),

            array( __( 'Share counter style', 'wpsr' ), WPSR_Admin::field( 'image_select', array(
                'name' => 'tmpl[' . $i . '][sc_style]',
                'value' => $values[ 'tmpl' ][ $i ]['sc_style'],
                'list' => array(
                    'count-1' => array( 'Style 1', 'counter-1.svg', '60px' ),
                    'count-2' => array( 'Style 2', 'counter-2.svg', '70px' ),
                    'count-3' => array( 'Style 3', 'counter-3.svg', '70px' ),
                ),
                'helper' => __( 'To show count, in the same page under icons list, select an icon and enable gear icon &gt; show count', 'wpsr' )
            )), 'data-conditioner data-condr-input=".setting_share_counter' . $i . '" data-condr-value="individual" data-condr-action="pattern?show:hide" data-condr-events="change"'),

            array( __( 'Total share count position', 'wpsr' ), WPSR_Admin::field( 'select', array(
                'name' => 'tmpl[' . $i . '][sc_total_position]',
                'value' => $values[ 'tmpl' ][ $i ]['sc_total_position'],
                'list' => array(
                    'left' => 'Left to the icons',
                    'right' => 'Right to the icons'
                ),
            )), 'data-conditioner data-condr-input=".setting_share_counter' . $i . '" data-condr-value="total" data-condr-action="pattern?show:hide" data-condr-events="change"'),

            array('MISC', ''),

            array( __( 'No of icons in the last to group', 'wpsr' ), WPSR_Admin::field( 'select', array(
                'name' => 'tmpl[' . $i . '][more_icons]',
                'value' => $values[ 'tmpl' ][ $i ]['more_icons'],
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

            array( __( 'Center the icons', 'wpsr' ), WPSR_Admin::field( 'select', array(
                'name' => 'tmpl[' . $i . '][center_icons]',
                'value' => $values[ 'tmpl' ][ $i ]['center_icons'],
                'list' => array(
                    '' => 'No',
                    'yes' => 'Yes'
                )
            )), 'data-conditioner data-condr-input=".setting_btn_layout' . $i . '" data-condr-value="" data-condr-action="simple?show:hide" data-condr-events="change"'),

            array( __( 'Heading text', 'wpsr' ), WPSR_Admin::field( 'text', array(
                'name' => 'tmpl[' . $i . '][heading]',
                'value' => $values[ 'tmpl' ][ $i ][ 'heading' ],
                'class' => '',
                'helper' => __( 'Heading to show above the share buttons. HTML is allowed.' )
            ))),

            array( __( 'Custom HTML above and below icons', 'wpsr' ), WPSR_Admin::field( 'textarea', array(
                'name' => 'tmpl[' . $i . '][custom_html_above]',
                'value' => $values[ 'tmpl' ][ $i ][ 'custom_html_above' ],
                'class' => 'inline_field',
                'placeholder' => 'Above'
            )) . WPSR_Admin::field( 'textarea', array(
                'name' => 'tmpl[' . $i . '][custom_html_below]',
                'value' => $values[ 'tmpl' ][ $i ][ 'custom_html_below' ],
                'class' => '',
                'placeholder' => 'Below'
            ))),

        );

        WPSR_Admin::build_table( $customization, __( 'Customization', 'wpsr' ), '', false, '3' );

        $positions = array(
            'above_posts' => __( 'Above posts', 'wpsr' ),
            'below_posts' => __( 'Below posts', 'wpsr' ),
            'above_below_posts' => __( 'Both above and below posts', 'wpsr' )
        );

        // Position rules
        WPSR_Admin::box_wrap( 'open', __( 'Position of template in the page', 'wpsr' ), __( 'Choose the below options to select the position the template in a page.', 'wpsr' ), '4' );

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

        WPSR_Admin::box_wrap( 'close' );

        // Location rules
        WPSR_Admin::box_wrap( 'open', __( 'Conditions to display the template', 'wpsr' ), __( 'Choose the below options to select the pages which will display the template.', 'wpsr' ), '5' );
        WPSR_Location_Rules::display_rules( "tmpl[$i][loc_rules]", $values['tmpl'][$i]['loc_rules'] );
        WPSR_Admin::box_wrap( 'close' );

        echo '</div>';

    }

    function form_fields( $values ){

        $values = WPSR_Lists::set_defaults( $values, WPSR_Lists::defaults( 'social_icons' ) );

        $section0 = array(
            array( __( 'Select to enable or disable social icons feature', 'wpsr' ), WPSR_Admin::field( 'select', array(
                'name' => 'ft_status',
                'value' => $values[ 'ft_status' ],
                'list' => array(
                    'enable' => __( 'Enable social icons', 'wpsr' ),
                    'disable' => __( 'Disable social icons', 'wpsr' )
                ),
            )), 'class="ft_table"' ),
        );

        WPSR_Admin::build_table( $section0, __( 'Enable/disable social icons', 'wpsr' ), '', false, '1' );

        $icon_settings = array( 'icon', 'text', 'hover_text', 'html' );
        WPSR_Icons_Editor::commons( $icon_settings );

        echo '<div class="feature_wrap">';

        $template_count = 2;

        echo '<ul class="template_tab">';
        for( $i = 1; $i <= $template_count; $i++ ){
            echo '<li>Template ' . $i . '</li>';
        }
        echo '</ul>';

        for( $i=1; $i<=$template_count; $i++ ){
            $this->template( $values, $i );
        }

        echo '</div>';

    }
    
    
    function page(){
        
        WPSR_Admin::settings_form( 'social_icons' );
        
    }
    
    function validation( $input ){

        if( $input['ft_status'] == 'enable' ){
            $btn_settings = get_option( 'wpsr_button_settings' );
            $btn_settings[ 'ft_status' ] = 'disable';
            update_option( 'wpsr_button_settings', $btn_settings );
        }

        return $input;
    }
    
}

new wpsr_admin_social_icons();

?>