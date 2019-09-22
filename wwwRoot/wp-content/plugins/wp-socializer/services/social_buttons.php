<?php
/**
  * Social buttons service for WP Socializer
  *
  */

class wpsr_service_social_buttons{
    
    var $sites;
    
    function __construct(){
        
        WPSR_Services::register( 'social_buttons', array(
            'name' => 'Social Icons',
            'icons' => WPSR_ADMIN_URL . '/images/icons/social_buttons.png',
            'desc' => __( 'Create social buttons with 35 sites and combiniations of different shape, size and colors.', 'wpsr' ),
            'settings' => array( 'size' => 'popup' ),
            'callbacks' => array(
                'output' => array( $this, 'output' ),
                'includes' => array( $this, 'includes' ),
                'settings' => array( $this, 'settings' ),
                'validation' => array( $this, 'validation' ),
                'general_settings' => array( $this, 'general_settings' ),
                'general_settings_validation' => array( $this, 'general_settings_validation' ),
            )
        ));
        
        $this->sites = WPSR_Lists::social_buttons();
        
        $this->default_values = array(
            'selected_sites' => '',
            'sr-sizes' => '32px',
            'sr-shapes' => '',
            'sr-hover' => 'opacity',
            'sr-layout' => '',
            'sr-font-size' => '',
            'sr-icon-display' => '',
            'sr-icon-color' => 'icon-white',
            'sr-border-width' => '',
            'sr-border-color' => '',
            'sr-background-color' => '',
            'sr-count-style' => 'count-1',
            'sr-shadow' => '',
            'sr-pad' => '',
            'text-styles' => '',
            'more_sites' => '0',
            'open_popup' => ''
        );
        
    }

    function output( $settings = array(), $page_info = array() ){
        
        $out = array();
        $settings = WPSR_Lists::set_defaults( $settings, $this->default_values );
        
        $classes = array( 'socializer' );
        foreach( $settings as $key => $val ){
            if( substr( $key, 0, 3 ) == 'sr-' && $val != '' ){
                array_push( $classes, 'sr-' . $val );
            }
        }
        
        $sites_clist = array();
        $sel_sites = json_decode( base64_decode( $settings[ 'selected_sites' ] ) );
        $is_mobile = wp_is_mobile();
        $text_style = $settings[ 'text-styles' ];
        $def_site_options = array(
            'count' => 0,
            'icon' => '',
            'text' => 0,
            'custom_text' => '',
            'custom_url' => '',
        );
        
        foreach( $sel_sites as $site ){
            $id = key( $site );
            $opts = WPSR_Lists::set_defaults( (array) $site->$id, $def_site_options );
            $props = $this->sites[ $id ];
            
            if( !$is_mobile && in_array( 'mobile_only', $props[ 'features' ] ) )
                continue;
            
            $temp_url = ( $opts[ 'custom_url' ] == '' ) ? $props[ 'link' ] : $opts[ 'custom_url' ];
            $url = $this->get_url( $temp_url, $page_info );
            
            $onclick = isset( $props[ 'onclick' ] ) ? 'onclick="' . $props[ 'onclick' ] . '"' : '';
            
            $text_class = '';
            $count_tag = '';
            
            if( $opts[ 'count' ] == 1 && in_array( 'count', $props[ 'options' ] ) ){
                $count_holder = WPSR_Share_Counter::placeholder( $page_info[ 'url' ], $id );
                $count_tag = '<span class="ctext">' . $count_holder . '</span>';
                $text_class = 'sr-text-in';
            }
            
            $text_in = '';
            $text_out = '';
            $the_text = '';
            
            if( intval( $opts[ 'text' ] ) == 1 ){
                $the_text = empty( $opts[ 'custom_text' ] ) ? $props[ 'name' ] : $opts[ 'custom_text' ];
                $text_class = 'sr-text-' . $text_style;
                if( $text_style == 'in' ){
                    $text_in = '<span class="text">' . $the_text . '</span>';
                }else{
                    $text_out = '<span class="text">' . $the_text . '</span>';
                }
            }
            
            $icon = ( $opts[ 'icon' ] == '' ) ? '<i class="fa fa-' . esc_attr( $props[ 'icon' ] ) . '"></i>' : '<img src="' . esc_attr( $opts[ 'icon' ] ) . '" alt="' . esc_attr( $id ) . '" />';
            
            $chtml = '<span class="sr-' . esc_attr( $id ) . ' ' . esc_attr( $text_class ) . '"><a rel="nofollow" href="' . esc_attr( $url ) . '" target="_blank" ' . $onclick . ' title="' . esc_attr( $props[ 'title' ] ) . '">' . $icon . $text_in . $count_tag . '</a>' . $text_out . '</span>';
            
            array_push( $sites_clist, $chtml );
        }
        
        $more_html = '';
        if( intval( $settings[ 'more_sites' ] ) > 0 ){
            $more_count = intval( $settings[ 'more_sites' ] );
            $more_sites = array_slice( $sites_clist, -$more_count, $more_count );
            $more_html = '<span class="sr-more"><a href="#" target="_blank" title="More sites"><i class="fa fa-share-alt"></i></a><ul class="socializer">' . implode( "\n", $more_sites ) . '</ul></span>';
            $sites_clist = array_slice( $sites_clist, 0, -$more_count );
            array_push( $sites_clist, $more_html );
        }
        
        if( $settings[ 'open_popup' ] == '' ){
            array_push( $classes, 'sr-popup' );
        }
        
        $html = '<div class="' . implode( " ", $classes ) . '">' . implode( "\n", $sites_clist ) . '</div>';
        
        return $out = array(
            'html' => $html,
            'includes' => array( 'sb_css', 'sb_fa_css' )
        );
        
    }
    
    function includes(){
        
        return array(
            'sb_fa_css' => array(
                'type' => 'css',
                'link' => WPSR_Lists::ext_res( 'font-awesome' )
            ),
            
            'sb_css' => array(
                'type' => 'css',
                'link' => WPSR_Lists::ext_res( 'socializer-css' )
            ),
        );
        
    }
    
    function settings( $values ){
        
        $values = WPSR_Lists::set_defaults( $values, $this->default_values );
        
        if( is_array( $values ) ){
            extract( $values );
        }
        
        $site_options = array(
            'count' => array(
                'type' => 'checkbox',
                'helper' => __( 'Show count', 'wpsr' ),
                'placeholder' => __( 'Select to show the share count of the service', 'wpsr' )
            ),
            'icon' => array(
                'type' => 'text',
                'helper' => __( 'Icon URL', 'wpsr' ),
                'placeholder' => __( 'Enter a custom icon URL for this site, starting with http://. Leave blank to use default icon', 'wpsr' )
            ),
            'text' => array(
                'type' => 'checkbox',
                'helper' => __( 'Show site title', 'wpsr' ),
                'placeholder' => __( 'Select to show site title next to icon', 'wpsr' )
            ),
            'custom_text' => array(
                'type' => 'text',
                'helper' => __( 'Custom site title', 'wpsr' ),
                'placeholder' => __( 'The custom text to show if "show site title" is selected. Leave blank to use the default site title', 'wpsr' )
            ),
            'custom_url' => array(
                'type' => 'text',
                'helper' => __( 'Custom URL', 'wpsr' ),
                'placeholder' => __( 'Enter a custom URL for this site, starting with http://. Leave blank to use default. Use {url}, {title} to use active page details in URL if needed.', 'wpsr' )
            ),
        );
        
        $site_features = array(
            'all' => array(
                'title' => __( 'All buttons', 'wpsr' ),
                'desc' => __( 'All social media buttons', 'wpsr' )
            ),
            'for_share' => array(
                'title' => __( 'Link sharing buttons', 'wpsr' ),
                'desc' => __( 'Buttons used for sharing page links', 'wpsr' )
            ),
            'for_profile' => array(
                'title' => __( 'Social profile buttons', 'wpsr' ),
                'desc' => __( 'Buttons used for sharing social media profiles', 'wpsr' )
            ),
            'mobile_only' => array(
                'title' => __( 'Mobile only buttons', 'wpsr' ),
                'desc' => __( 'Buttons which will be displayed only on mobile devices', 'wpsr' )
            ),
        );
        
        $saved = array();
        $sel_sites = json_decode( base64_decode( $values[ 'selected_sites' ] ) );

        foreach( $sel_sites as $site ){
            $id = key( $site );
            $opts = (array) $site->$id;
            array_push( $saved, array( $id => $opts ) );
        }

        ?>

<h3><?php _e( 'Select social buttons', 'wpsr' ); ?></h3>
<button class="mini_section_select"><span class="dashicons dashicons-plus"></span> <?php _e( 'Click to open the list of buttons', 'wpsr' ); ?><span class="dashicons dashicons-arrow-down fright"></span></button>

    <div class="mini_section">
        <p><?php _e( 'Drag and drop social buttons into the box below', 'wpsr' ); ?></p>
        <div class="mini_filters"><select class="sb_features_list" data-list=".list_available" ><?php
        foreach( $site_features as $k=>$v ){ echo '<option value="' . $k . '">' . $v[ 'title' ] . '</options>'; }
        ?></select><input type="text" class="list_search" data-list=".list_available" placeholder="<?php _e( 'Search', 'wpsr' ); ?> ..." /></div>

        <ul class="mini_btn_list list_available clearfix">
        <?php
        foreach( $this->sites as $site => $config ){
            $datas = ' data-id="' . $site . '" data-opt_text="false" data-opt_custom_url="" data-opt_icon="" data-opt_custom_text="" ';
            
            foreach( $config[ 'options' ] as $opt ){
                $datas .= 'data-opt_' . $opt . '="false"';
            }
            
            echo '<li' . $datas . ' style="background-color: ' . $config[ 'colors' ][0] . ';" data-features="' . implode( ',', $config[ 'features' ] ) . '">';
                echo '<i class="fa fa-' . $config[ 'icon' ] . ' item_icon" ></i> ';
                echo '<span>' . $config[ 'name' ] . '</span>';
                echo '<i class="fa fa-trash-o item_action item_delete" title="' . __( 'Delete button', 'wpsr' ) . '"></i> ';
                echo '<i class="fa fa-cog item_action item_settings" title="' . __( 'Button settings', 'wpsr' ) . '"></i> ';
            echo '</li>';
        }
        ?>
        </ul>
    </div>

<ul class="mini_btn_list list_selected clearfix" data-callback="wpsr_sb_process_list" data-input=".sb_selected_list"><?php

foreach( $saved as $i ){
    foreach( $i as $site => $opts ){
        
        $datas = ' data-id="' . $site . '"';
        $site_prop = $this->sites[ $site ];
        
        foreach( $opts as $k => $v ){
            $datas .= ' data-opt_' . $k . '="' . $v . '"';
        }
        
        echo '<li' . $datas . ' style="background-color: ' . $site_prop[ 'colors' ][0] . ';">';
            echo '<i class="fa fa-' . $site_prop[ 'icon' ] . ' item_icon"></i>';
            echo '<span>' . $site_prop[ 'name' ] . '</span>';
            echo '<i class="fa fa-trash-o item_action item_delete" title="' . __( 'Delete button', 'wpsr' ) . '"></i>';
            echo '<i class="fa fa-cog item_action item_settings" title="' . __( 'Button settings', 'wpsr' ) . '"></i>';
        echo '</li>';
        
    }
    
}
?></ul>

<input type="hidden" name="o[selected_sites]" class="sb_selected_list" value="<?php echo $values[ 'selected_sites' ]; ?>" />

<div class="item_popup">
    <h4></h4>
    <i class="fa fa-times item_popup_close" title="<?php _e( 'Close', 'wpsr' ); ?>"></i>
    <div class="item_popup_cnt"></div>
    <button class="button button-primary item_popup_save"><?php _e( 'Save button settings', 'wpsr' ); ?></button>
</div>

<script>
var sb_sites = <?php echo json_encode( $this->sites ); ?>;
var sb_site_options = <?php echo json_encode( $site_options ); ?>;
wpsr_list_selector_init( 'wpsr_sb_process_list', '.sb_selected_list' );
if( jQuery ){
    jQuery(function() {
        jQuery( '.sb_features_list' ).val( 'for_share' ).trigger( 'change' );
    });
}
</script>

<!--
<textarea cols="130">
<?php

$a[ $values['title'] ] = array(
    'service' => 'social_buttons',
    'settings'=> $values
);
echo json_encode( $a );

?>
</textarea>
-->

<h3>Settings</h3>
        <?php
        
        $section1 = array(
            
            array( __( 'Design of the buttons', 'wpsr' ), '<button class="button button-primary scr_open_template">Select a design template for the buttons</button>'),
            
            array(__( 'Icon size', 'wpsr' ), WPSR_Admin::field( 'select', array(
                'name' => 'o[sr-sizes]',
                'value' => $values['sr-sizes'],
                'list' => array(
                    '32px' => '32px',
                    '16px' => '16px',
                    '48px' => '48px',
                    '64px' => '64px',
                ),
                'custom' => 'data-scr-settings="sizes"',
                'tip' => WPSR_ADMIN_URL . '/images/tips/btn-sizes.png'
            ))),
            
            array( __( 'Icon shape', 'wpsr' ), WPSR_Admin::field( 'select', array(
                'name' => 'o[sr-shapes]',
                'value' => $values['sr-shapes'],
                'list' => array(
                    '' => 'Square',
                    'circle' => 'Circle',
                    'squircle' => 'Squircle',
                    'squircle-2' => 'Squircle 2',
                    'diamond' => 'Diamond',
                    'ribbon' => 'Ribbon',
                    'drop' => 'Drop',
                ),
                'custom' => 'data-scr-settings="shapes"',
                'tip' => WPSR_ADMIN_URL . '/images/tips/btn-shapes.png'
            ))),
            
            array( __( 'Hover effects', 'wpsr' ), WPSR_Admin::field( 'select', array(
                'name' => 'o[sr-hover]',
                'value' => $values['sr-hover'],
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
                'custom' => 'data-scr-settings="hover"'
            ))),
            
        );
        
        $section2 = array(
            
            array( __( 'Button layout', 'wpsr' ), WPSR_Admin::field( 'select', array(
                'name' => 'o[sr-layout]',
                'value' => $values['sr-layout'],
                'list' => array(
                    '' => 'Normal',
                    'fluid' => 'Fluid',
                    'vertical' => __( 'Vertical', 'wpsr' )
                ),
                'custom' => 'data-scr-settings="layouts"',
                'tip' => WPSR_ADMIN_URL . '/images/tips/btn-layouts.png'
            ))),
            
            array( __( 'Text styles', 'wpsr' ), WPSR_Admin::field( 'select', array(
                'name' => 'o[text-styles]',
                'value' => $values['text-styles'],
                'list' => array(
                    'in' => __( 'Besides icon', 'wpsr' ),
                    'out' => __( 'Besides icon 2', 'wpsr' ),
                    'below' => __( 'Below icon', 'wpsr' ),
                    'hover' => __( 'Text on hover', 'wpsr' )
                ),
                'custom' => 'data-scr-settings="text-styles"',
                'tip' => WPSR_ADMIN_URL . '/images/tips/btn-text-types.png'
            ))),
            
            array( __( 'Font size', 'wpsr' ), WPSR_Admin::field( 'select', array(
                'name' => 'o[sr-font-size]',
                'value' => $values['sr-font-size'],
                'list' => array(
                    '' => 'Normal',
                    'font-sm' => 'Small',
                    'font-lg' => 'Large'
                ),
                'custom' => 'data-scr-settings="font-size"'
            ))),
            
            array( __( 'Icon display', 'wpsr' ), WPSR_Admin::field( 'select', array(
                'name' => 'o[sr-icon-display]',
                'value' => $values['sr-icon-display'],
                'list' => array(
                    '' => __( 'Show icon', 'wpsr' ),
                    'no-icon' => __( 'Hide icon', 'wpsr' )
                )
            ))),
        
        );
        
        $section3 = array(
            
            array( __( 'Icon color', 'wpsr' ), WPSR_Admin::field( 'select', array(
                'name' => 'o[sr-icon-color]',
                'value' => $values['sr-icon-color'],
                'list' => array(
                    'icon-white' => 'White icon',
                    'icon-dark' => 'Dark icon',
                    'icon-grey' => 'Grey icon',
                    '' => 'Color icon',
                ),
                'custom' => 'data-scr-settings="icon-color"'
            ))),
            
            array( __( 'Border width', 'wpsr' ), WPSR_Admin::field( 'select', array(
                'name' => 'o[sr-border-width]',
                'value' => $values['sr-border-width'],
                'list' => array(
                    '' => 'No border',
                    'bdr-sm' => 'Small size border',
                    'bdr-md' => 'Medium size border',
                    'bdr-lg' => 'Large size border'
                ),
                'custom' => 'data-scr-settings="border-width"'
            ))),
            
            array( __( 'Border color', 'wpsr' ), WPSR_Admin::field( 'select', array(
                'name' => 'o[sr-border-color]',
                'value' => $values['sr-border-color'],
                'list' => array(
                    '' =>  'Color border',
                    'bdr-white' => 'White border',
                    'bdr-dark' => 'Dark border',
                    'bdr-grey' => 'Grey border'
                ),
                'custom' => 'data-scr-settings="border-color"'
            ))),
            
            array( __( 'Background color', 'wpsr' ), WPSR_Admin::field( 'select', array(
                'name' => 'o[sr-background-color]',
                'value' => $values['sr-background-color'],
                'list' => array(
                    '' => 'Color background',
                    'bg-none' => 'Transparent background',
                    'bg-white' => 'White background',
                    'bg-dark' => 'Dark background',
                    'bg-grey' => 'Grey background'
                ),
                'custom' => 'data-scr-settings="background-color"'
            ))),
            
            array( __( 'Share counter style', 'wpsr' ), WPSR_Admin::field( 'select', array(
                'name' => 'o[sr-count-style]',
                'value' => $values['sr-count-style'],
                'list' => array(
                    'count-1' => 'Style 1',
                    'count-2' => 'Style 2',
                    'count-3' => 'Style 3',
                    'count-4' => 'Style 4',
                    'bb-1' => 'Bubble style 1',
                ),
                'custom' => 'data-scr-settings="count-style"'
            ))),
            
            array( __( 'Shadow type', 'wpsr' ), WPSR_Admin::field( 'select', array(
                'name' => 'o[sr-shadow]',
                'value' => $values['sr-shadow'],
                'list' => array(
                    '' =>  'No shadow',
                    'sw-1' => 'Type 1',
                    'sw-2' => 'Type 2',
                    'sw-3' => 'Type 3',
                    'sw-icon-1' => 'Icon shadow 1'
                ),
                'custom' => 'data-scr-settings="shadow"'
            ))),
            
        );
        
        $section4 = array(
            
            array( __( 'Gutters', 'wpsr' ), WPSR_Admin::field( 'select', array(
                'name' => 'o[sr-pad]',
                'value' => $values['sr-pad'],
                'list' => array(
                    '' =>  'No',
                    'pad' =>  'yes'
                ),
                'helper' => __( 'Select to add space between buttons', 'wpsr' ),
                'custom' => 'data-scr-settings="pad"'
            ))),
            
            array( __( 'No of buttons in the last to group', 'wpsr' ), WPSR_Admin::field( 'select', array(
                'name' => 'o[more_sites]',
                'value' => $values['more_sites'],
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
                'helper' => __( 'The last buttons grouped will be displayed in a "More" buttons menu', 'wpsr' )
            ))),
            
            array( __( 'Open links in popup', 'wpsr' ), WPSR_Admin::field( 'select', array(
                'name' => 'o[open_popup]',
                'value' => $values['open_popup'],
                'list' => array(
                    '' =>  'yes',
                    'no' =>  'No',
                ),
            ))),
            
        );
        
        echo '<div class="scr_templates_wrap"><i class="fa fa-times item_popup_close" title="Close"></i><ul class="scr_templates"></ul></div>';
        
        echo '<div class="scr_swrap">';
        echo '<h4 class="collapse_head">' . __( 'Size, shape and hover effects', 'wpsr' ) . '</h4>';
        WPSR_Admin::build_table( $section1, '', '', true);
        
        echo '<h4 class="collapse_head">' . __( 'Layouts, text and icon', 'wpsr' ) . '</h4>';
        WPSR_Admin::build_table( $section2, '', '', true);
        
        echo '<h4 class="collapse_head">' . __( 'Styles and colors', 'wpsr' ) . '</h4>';
        WPSR_Admin::build_table( $section3, '', '', true);
        
        echo '<h4 class="collapse_head">' . __( 'Other settings', 'wpsr' ) . '</h4>';
        WPSR_Admin::build_table( $section4, '', '', true);
        echo '</div>';
    }
    
    function get_url( $url, $pinfo ){
        
        $g_settings = get_option( 'wpsr_general_settings' );
        $g_settings = WPSR_Lists::set_defaults( $g_settings, WPSR_Lists::defaults( 'gsettings_twitter' ) );
        $t_username = ( $g_settings[ 'twitter_username' ] != '' ) ? '@' . $g_settings[ 'twitter_username' ] : '';
        
        $search = array(
            '{url}',
            '{title}',
            '{excerpt}',
            '{s-url}',
            '{rss-url}',
            '{image}',
            '{twitter-username}',
        );
        
        $replace = array(
            $pinfo[ 'url' ],
            $pinfo[ 'title' ],
            $pinfo[ 'excerpt' ],
            $pinfo[ 'short_url' ],
            $pinfo[ 'rss_url' ],
            $pinfo[ 'post_image' ],
            $t_username,
        );
        
        return str_replace( $search, $replace, $url );
    }
    
    function validation( $values ){
        
        return $values;
        
    }
    
    function general_settings( $values ){
        
        $values = WPSR_Lists::set_defaults( $values, WPSR_Lists::defaults( 'gsettings_socialbuttons' ) );
        
        $section1 = array(
            array( __( 'ID or class name of the comment section', 'wpsr' ), WPSR_Admin::field( 'text', array(
                'name' => 'sb_comment_sec',
                'value' => $values['sb_comment_sec'],
                'placeholder' => 'Ex: #comments',
                'helper' => __( 'Enter the class name or ID of the comment section in the page.', 'wpsr' ),
                'qtip' => 'https://www.youtube.com/watch?v=GQ1YO0xZ7WA'
            )))
        );

        WPSR_Admin::build_table( $section1, 'Social buttons settings' );
        
    }
    
    function general_settings_validation( $values ){
        return $values;
    }
    
}

new wpsr_service_social_buttons();

?>