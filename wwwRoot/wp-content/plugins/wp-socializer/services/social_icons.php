<?php
/**
  * social icons service for WP Socializer
  *
  */

class wpsr_service_social_icons{
    
    var $sites;
    
    function __construct(){
        
        add_filter( 'wpsr_register_service', array( $this, 'register' ) );
        
        $this->sites = WPSR_Lists::social_icons();
        
        $this->default_values = array(
            'selected_sites' => '',
            'sr-sizes' => '32px',
            'sr-shapes' => '',
            'sr-hover' => 'opacity',
            'sr-layout' => '',
            'sr-font-size' => '',
            'sr-icon-display' => '',
            'sr-border-width' => '',
            'sr-count-style' => 'count-1',
            'sr-shadow' => '',
            'sr-pad' => '',
            'text-styles' => '',
            'icon_color' => '#fff',
            'bg_color' => '',
            'border_color' => '',
            'more_sites' => '0',
            'open_popup' => ''
        );
        
    }
    
    function register( $services ){
        
        $services[ 'social_icons' ] = array(
            'name' => 'Social Icons',
            'icons' => WPSR_ADMIN_URL . '/images/icons/social_icons.png',
            'desc' => __( 'Create social icons with 35 sites and combiniations of different shape, size and colors.', 'wpsr' ),
            'settings' => array( 'size' => 'popup' ),
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
            'custom_hover_text' => ''
        );
        
        $styles = array();
        if ( $settings[ 'bg_color' ] != '' ) array_push( $styles, 'background-color: ' . $settings[ 'bg_color' ] );
        if ( $settings[ 'icon_color' ] != '' ) array_push( $styles, 'color: ' . $settings[ 'icon_color' ] );
        if ( $settings[ 'border_color' ] != '' ) array_push( $styles, 'border-color: ' . $settings[ 'border_color' ] );
        $style = join( ';', $styles );
        
        $style = ( $style != '' ) ? ' style="' . $style . '"' : '';
        
        foreach( $sel_sites as $site ){
            $id = key( $site );
            
            if(!array_key_exists($id, $this->sites)){
                continue;
            }
            
            if( $id == 'googleplus' ){
                continue;
            }
            
            $opts = WPSR_Lists::set_defaults( (array) $site->$id, $def_site_options );
            $props = $this->sites[ $id ];
            
            if( !$is_mobile && in_array( 'mobile_only', $props[ 'features' ] ) )
                continue;
            
            $temp_url = ( $opts[ 'custom_url' ] == '' ) ? $props[ 'link' ] : urldecode( $opts[ 'custom_url' ] );
            $url = $this->get_url( $temp_url, $page_info );
            
            $onclick = isset( $props[ 'onclick' ] ) ? 'onclick="' . $props[ 'onclick' ] . '"' : '';
            
            $text_class = '';
            $count_tag = '';
            
            if( $opts[ 'count' ] == 1 && in_array( 'count', $props[ 'options' ] ) ){
                $count_holder = WPSR_Share_Counter::placeholder( $page_info[ 'url' ], $id );
                $count_tag = '<span class="ctext">' . $count_holder . '</span>';
                $text_class = ( isset( $settings['sr-count-style'] ) && $settings['sr-count-style'] == 'count-1' ) ? '' : 'sr-text-in';
            }
            
            $text_in = '';
            $text_out = '';
            $the_text = '';
            
            if( intval( $opts[ 'text' ] ) == 1 ){
                $the_text = empty( $opts[ 'custom_text' ] ) ? $props[ 'name' ] : urldecode($opts[ 'custom_text' ]);
                $text_class = 'sr-text-' . $text_style;
                if( $text_style == 'in' ){
                    $text_in = '<span class="text">' . $the_text . '</span>';
                }else{
                    $text_out = '<span class="text">' . $the_text . '</span>';
                }
            }
            
            $icon = '';
            if( $opts[ 'icon' ] == '' ){
                $icon = '<i class="' . esc_attr( $props[ 'icon' ] ) . '"></i>';
            }else{
                $icon_val = urldecode( $opts[ 'icon' ] );
                if (strpos( $opts[ 'icon' ], 'http' ) === 0) {
                    $icon = '<img src="' . esc_attr( $icon_val ) . '" alt="' . esc_attr( $id ) . '" height="50%" />';
                }else{
                    $icon = '<i class="' . esc_attr( $icon_val ) . '"></i>';
                }
            }
            
            $title = '';
            if( $opts[ 'custom_hover_text' ] == '' ){
                $title = $props[ 'title' ];
            }else{
                $title = urldecode( $opts[ 'custom_hover_text' ] );
            }
            
            $chtml = '<span class="sr-' . esc_attr( $id ) . ' ' . esc_attr( $text_class ) . '"><a rel="nofollow" href="' . esc_attr( $url ) . '" target="_blank" ' . $onclick . ' title="' . esc_attr( $title ) . '" ' . $style . '>' . $icon . $text_in . $count_tag . '</a>' . $text_out . '</span>';
            
            array_push( $sites_clist, $chtml );
        }
        
        $more_html = '';
        if( intval( $settings[ 'more_sites' ] ) > 0 ){
            $more_count = intval( $settings[ 'more_sites' ] );
            $more_sites = array_slice( $sites_clist, -$more_count, $more_count );
            $more_html = '<span class="sr-more"><a href="#" target="_blank" title="More sites" ' . $style . '><i class="fa fa-share-alt"></i></a><ul class="socializer">' . implode( "\n", $more_sites ) . '</ul></span>';
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
            'sb_icon_css' => WPSR_Lists::get_font_icon()['prop']
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
                'helper' => __( 'Show sharing count', 'wpsr' ),
                'placeholder' => __( 'Select to show the share count of the service', 'wpsr' )
            ),
            'icon' => array(
                'type' => 'text',
                'helper' => __( 'Icon', 'wpsr' ),
                'placeholder' => __( 'Enter a custom icon URL for this site, starting with <code>http://</code>. You can also use class name of the icon font Example: <code>fa fa-star</code> Leave blank to use default icon', 'wpsr' )
            ),
            'text' => array(
                'type' => 'checkbox',
                'helper' => __( 'Show name of the site', 'wpsr' ),
                'placeholder' => __( 'Select to show name of the site next to the icon', 'wpsr' )
            ),
            'custom_text' => array(
                'type' => 'text',
                'helper' => __( 'Name of the site', 'wpsr' ),
                'placeholder' => __( 'Enter custom text to appear to next to the icon. This text will be shown if "Show name of the site" is enabled. Leave blank to use the default site text.', 'wpsr' )
            ),
            'custom_url' => array(
                'type' => 'text',
                'helper' => __( 'Share URL', 'wpsr' ),
                'placeholder' => __( 'Enter custom URL for this site, starting with <code>http://</code>. Leave blank to use default. Use <code>{url}</code>, <code>{title}</code> to use active page details in URL if needed.', 'wpsr' )
            ),
            'custom_hover_text' => array(
                'type' => 'text',
                'helper' => __( 'Hover text', 'wpsr' ),
                'placeholder' => __( 'Enter custom text to appear when the icon is hovered.', 'wpsr' )
            ),
        );
        
        $site_features = array(
            'all' => array(
                'title' => __( 'All social icons', 'wpsr' ),
                'desc' => __( 'All social media icons', 'wpsr' )
            ),
            'for_share' => array(
                'title' => __( 'Link sharing social icons', 'wpsr' ),
                'desc' => __( 'Social icons used for sharing page links', 'wpsr' )
            ),
            'for_profile' => array(
                'title' => __( 'Social profile icons', 'wpsr' ),
                'desc' => __( 'Icons used for sharing social media profiles', 'wpsr' )
            ),
            'mobile_only' => array(
                'title' => __( 'Mobile only icons', 'wpsr' ),
                'desc' => __( 'Icons which will be displayed only on mobile devices', 'wpsr' )
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

<h3><?php _e( 'Select social icons', 'wpsr' ); ?></h3>
<button class="mini_section_select"><i class="fa fa-plus"></i> <?php _e( 'Click to open the list of buttons', 'wpsr' ); ?><i class="fa fa-chevron-down fright"></i></button>

    <div class="mini_section">
        <p><?php _e( 'Drag and drop social icons into the box below', 'wpsr' ); ?></p>
        <div class="mini_filters"><select class="sb_features_list" data-list=".list_available" ><?php
        foreach( $site_features as $k=>$v ){ echo '<option value="' . $k . '">' . $v[ 'title' ] . '</options>'; }
        ?></select><input type="text" class="list_search" data-list=".list_available" placeholder="<?php _e( 'Search', 'wpsr' ); ?> ..." /></div>

        <ul class="mini_btn_list list_available clearfix">
        <?php
        foreach( $this->sites as $site => $config ){
            $datas = ' data-id="' . $site . '" data-opt_text="false" data-opt_custom_url="" data-opt_icon="" data-opt_custom_text="" data-opt_custom_hover_text=""';
            
            foreach( $config[ 'options' ] as $opt ){
                $datas .= 'data-opt_' . $opt . '="false"';
            }
            
            echo '<li' . $datas . ' style="background-color: ' . $config[ 'colors' ][0] . ';" data-features="' . implode( ',', $config[ 'features' ] ) . '">';
                echo '<i class="' . $config[ 'icon' ] . ' item_icon" ></i> ';
                echo '<span>' . $config[ 'name' ] . '</span>';
                echo '<i class="fa fa-trash-alt item_action item_delete" title="' . __( 'Delete icon', 'wpsr' ) . '"></i> ';
                echo '<i class="fa fa-cog item_action item_settings" title="' . __( 'Advanced icon settings', 'wpsr' ) . '"></i> ';
            echo '</li>';
        }
        ?>
        </ul>
    </div>

<ul class="mini_btn_list list_selected clearfix" data-callback="wpsr_sb_process_list" data-input=".sb_selected_list"><?php

foreach( $saved as $i ){
    foreach( $i as $site => $opts ){
        
        if( !array_key_exists( $site, $this->sites ) ){
            continue;
        }
        
        $datas = ' data-id="' . $site . '"';
        $site_prop = $this->sites[ $site ];
        
        foreach( $opts as $k => $v ){
            $datas .= ' data-opt_' . $k . '="' . urldecode($v) . '"';
        }
        
        echo '<li' . $datas . ' style="background-color: ' . $site_prop[ 'colors' ][0] . ';">';
            echo '<i class="' . $site_prop[ 'icon' ] . ' item_icon"></i>';
            echo '<span>' . $site_prop[ 'name' ] . '</span>';
            echo '<i class="fa fa-trash-alt item_action item_delete" title="' . __( 'Delete icon', 'wpsr' ) . '"></i>';
            echo '<i class="fa fa-cog item_action item_settings" title="' . __( 'Advanced icon settings', 'wpsr' ) . '"></i>';
        echo '</li>';
        
    }
    
}
?></ul>

<input type="hidden" name="o[selected_sites]" class="sb_selected_list" value="<?php echo $values[ 'selected_sites' ]; ?>" />

<div class="item_popup">
    <h4></h4>
    <i class="fa fa-times item_popup_close" title="<?php _e( 'Close', 'wpsr' ); ?>"></i>
    <div class="item_popup_cnt"></div>
    <button class="button button-primary item_popup_save"><?php _e( 'Save icon settings', 'wpsr' ); ?></button>
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
    'service' => 'social_icons',
    'settings'=> $values
);
echo json_encode( $a );

?>
</textarea>
-->

<h3>Settings</h3>
        <?php
        
        $section1 = array(
            
            array(__( 'Icon size', 'wpsr' ), WPSR_Admin::field( 'image_select', array(
                'name' => 'o[sr-sizes]',
                'value' => $values['sr-sizes'],
                'list' => array(
                    '32px' => array( '32px', 'size.svg', '32px' ),
                    '40px' => array( '40px', 'size.svg', '40px' ),
                    '48px' => array( '48px', 'size.svg', '48px' ),
                    '64px' => array( '64px', 'size.svg', '64px' ),
                ),
                'custom' => 'data-scr-settings="sizes"'
            ))),
            
            array( __( 'Icon shape', 'wpsr' ), WPSR_Admin::field( 'image_select', array(
                'name' => 'o[sr-shapes]',
                'value' => $values['sr-shapes'],
                'list' => array(
                    '' => array( 'Sqaure', 'shape-square.svg', '32px' ),
                    'circle' => array( 'Circle', 'shape-circle.svg', '32px' ),
                    'squircle' => array( 'Squircle', 'shape-squircle.svg', '32px' ),
                    'squircle-2' => array( 'Squircle 2', 'shape-squircle-2.svg', '32px' ),
                    'diamond' => array( 'Diamond', 'shape-diamond.svg', '32px' ),
                    'ribbon' => array( 'Ribbon', 'shape-ribbon.svg', '32px' ),
                    'drop' => array( 'Drop', 'shape-drop.svg', '32px' ),
                ),
                'custom' => 'data-scr-settings="shapes"'
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
            
            array( __( 'Button layout', 'wpsr' ), WPSR_Admin::field( 'image_select', array(
                'name' => 'o[sr-layout]',
                'value' => $values['sr-layout'],
                'list' => array(
                    '' => array( 'Horizontal', 'layout-horizontal.svg', '64px' ),
                    'fluid' => array( 'Fluid', 'layout-fluid.svg', '64px' ),
                    'vertical' => array( 'Vertical', 'layout-vertical.svg', '64px' ),
                ),
                'custom' => 'data-scr-settings="layouts"'
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
            
            array( __( 'Icon color', 'wpsr' ), WPSR_Admin::field( 'text', array(
                'name' => 'o[icon_color]',
                'value' => $values['icon_color'],
                'class' => 'wp-color',
                'helper' => __( 'Set empty value to use brand color', 'wpsr' )
            ))),
            
            array( __( 'Background color', 'wpsr' ), WPSR_Admin::field( 'text', array(
                'name' => 'o[bg_color]',
                'value' => $values['bg_color'],
                'class' => 'wp-color',
                'helper' => __( 'Set empty value to use brand color', 'wpsr' )
            ))),
            
            array( __( 'Border color', 'wpsr' ), WPSR_Admin::field( 'text', array(
                'name' => 'o[border_color]',
                'value' => $values['border_color'],
                'class' => 'wp-color',
                'helper' => __( 'Set empty value to use brand color', 'wpsr' )
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
            
            array( __( 'Share counter style', 'wpsr' ), WPSR_Admin::field( 'image_select', array(
                'name' => 'o[sr-count-style]',
                'value' => $values['sr-count-style'],
                'list' => array(
                    'count-1' => array( 'Style 1', 'counter-1.svg', '60px' ),
                    'count-2' => array( 'Style 2', 'counter-2.svg', '70px' ),
                    'count-3' => array( 'Style 3', 'counter-3.svg', '70px' ),
                    'bb-1' => array( 'Bubble style 1', 'bb-1.svg', '70px' ),
                ),
                'custom' => 'data-scr-settings="count-style"',
                'helper' => __( 'To show count, in the same page under icons list, select an icon and enable gear icon &gt; show count', 'wpsr' )
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
                'helper' => __( 'Select to add space between icons', 'wpsr' ),
                'custom' => 'data-scr-settings="pad"'
            ))),
            
            array( __( 'No of icons in the last to group', 'wpsr' ), WPSR_Admin::field( 'select', array(
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
                'helper' => __( 'The last icons grouped will be displayed in a "More" icons menu', 'wpsr' )
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
        
        $pinfo = wp_parse_args( $pinfo, array(
            'url' => '',
            'title' => '',
            'excerpt' => '',
            'short_url' => '',
            'rss_url' => '',
            'post_image' => ''
        ));
        
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

        WPSR_Admin::build_table( $section1, 'Social icons settings' );
        
    }
    
    function general_settings_validation( $values ){
        return $values;
    }
    
    function templates(){
        return WPSR_Lists::load_json('buttons');
    }
    
}

new wpsr_service_social_icons();

?>