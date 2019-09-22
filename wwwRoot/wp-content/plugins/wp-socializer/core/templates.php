<?php
/**
  * Templates processor
  * 
  */

class WPSR_Template_Buttons{
    
    public static function init(){
        
        add_action( 'init', array( __CLASS__, 'output' ) );
        
        add_action( 'wp_ajax_wpsr_preview_template_buttons', array( __CLASS__, 'preview' ) );
        
    }
    
    public static function output(){
        
        $btn_settings = WPSR_Lists::set_defaults( get_option( 'wpsr_button_settings' ), WPSR_Lists::defaults( 'buttons' ) );
        $btn_templates = $btn_settings[ 'tmpl' ];
        
        if($btn_settings[ 'ft_status' ] != 'disable'){
            foreach( $btn_templates as $tmpl ){
                
                $content_obj = new wpsr_template_button_handler( $tmpl, 'content' );
                $excerpt_obj = new wpsr_template_button_handler( $tmpl, 'excerpt' );
                
                add_filter( 'the_content', array( $content_obj, 'print_template' ), 10 );
                add_filter( 'the_excerpt', array( $excerpt_obj, 'print_template' ), 10 );
                
            }
        }
    }
    
    public static function html( $template, $page_info = array(), $min_on_width = '0' ){
        
        $decoded = base64_decode( $template );
        $rows = json_decode( $decoded );
        $html = '';
        $row_buttons = array();
        $buttons = WPSR_Buttons::list_all();
        
        if( !is_object( $rows ) || empty( $rows ) )
            return array(
                'html' => '',
                'buttons' => ''
            );
        
        $min_on_width = ( $min_on_width > 0 ) ? ' data-minonwidth="' . $min_on_width . '" ' : '';
        
        // Apply filters on the template rows
        $rows = apply_filters( 'wpsr_mod_buttons_template', $rows );
        $row_id = 1;
        
        foreach( $rows as $row ){
            if( count( $row->buttons ) > 0 ){
                $html .= '<div class="wp-socializer wpsr-buttons wpsr-row-' . $row_id . '"' . $min_on_width . '>';
                foreach( $row->buttons as $button ){
                    $btn_id = key( ( array )$button );
                    array_push( $row_buttons, $btn_id );
                    
                    if( !array_key_exists( $btn_id, $buttons ) ){
                        continue;
                    }
                    
                    $wrap_tag = 1;
                    $btn_settings = $buttons[ $btn_id ][ 'settings' ];
                    $service_id = $buttons[ $btn_id ][ 'service' ];
                    if ( isset( $btn_settings[ '_wrap_tag' ] ) && $btn_settings[ '_wrap_tag' ] == 0 ){
                        $wrap_tag = 0;
                    }
                    
                    $classes = apply_filters( 'wpsr_mod_button_classes', array( 'wpsr-btn', 'wpsr-srvc-' . $service_id, 'wpsr-btn-' . $btn_id ) );
                    $classes = join( ' ', $classes );
                    
                    $btn_html = WPSR_Buttons::get_button( $btn_id, $page_info );
                    $html .= $wrap_tag ? '<div class="' . esc_attr( $classes ) . '">' . $btn_html . '</div>' : $btn_html;
                }
                $html .= '</div>';
            }
            $row_id += 1;
        }
        
        $out = array(
            'html' => $html,
            'buttons' => $row_buttons
        );
        
        return apply_filters( 'wpsr_mod_buttons_template_html', $out );
        
    }
    
    public static function preview( $template ){
        
        if( !isset( $_GET[ 'template' ] ) ){
            die(0);
        }
        
        $template = wp_kses_post( $_GET[ 'template' ] );
        $page_info = array(
            'title' => 'Google',
            'url' => 'https://www.google.com',
            'excerpt' => 'Dummy excerpt for WP Socializer preview',
            'short_url' => 'https://goo.gl/lightsaber',
            'comments_count' => '45',
            'post_id' => 1,
            'post_image' => '',
            'rss_url' => ''
        );
        
        // Generate HTML for the template
        $gen_out = self::html( $template, $page_info );
        
        echo '<!doctype html><html lang="en"><head><meta charset="utf-8"><title>' . __( 'WP Socializer template preview', 'wpsr' ) . '</title>';
        
        echo '</head><body>' . $gen_out[ 'html' ];
        
        WPSR_Includes::preview_print_includes();
        
        echo '</body></html>';
        
        die(0);
        
    }
    
}

class WPSR_Template_Sharebar{
    
    public static function init(){
        
        add_action( 'wp_footer', array( __CLASS__, 'output' ) );
        
        add_action( 'wp_ajax_wpsr_preview_template_sharebar', array( __CLASS__, 'preview' ) );
        
    }
    
    public static function output(){

        $sb_settings = WPSR_Lists::set_defaults( get_option( 'wpsr_sharebar_settings' ), WPSR_Lists::defaults( 'sharebar' ) );
        $loc_rules_answer = WPSR_Location_Rules::check_rule( $sb_settings[ 'loc_rules' ] );
        
        if( $sb_settings[ 'ft_status' ] != 'disable' && $loc_rules_answer ){
            $gen_out = self::html( $sb_settings );
            echo $gen_out[ 'html' ];
            do_action( 'wpsr_do_sharebar_print_template_end' );
        }
        
    }
    
    public static function html( $opts, $page_info = array() ){
        
        $html = '';
        $buttons = array();
        $classes = array();
        $styles = array();
        
        $decoded = base64_decode( $opts[ 'buttons' ] );
        $rows = json_decode( $decoded );
        
        if( !is_object( $rows ) || empty( $rows ) )
            return '';
        
        if( $opts[ 'type' ] == 'horizontal' ){
            array_push( $classes, 'wpsr-sb-hl-' . $opts[ 'hl_position' ] );
            #array_push( $styles, 'width:' . $opts[ 'hl_width' ] );
            array_push( $styles, ( $opts[ 'hl_position' ] == 'wbottom' ? 'bottom' : 'top' ) . ':' . $opts[ 'offset' ] );
        }else{
            array_push( $classes, 'wpsr-sb-vl-' . $opts[ 'vl_position' ] );
            array_push( $classes, 'wpsr-sb-vl-' . $opts[ 'vl_movement' ] );
            array_push( $styles, ( $opts[ 'vl_position' ] == 'wleft' ? 'left' : ( $opts[ 'vl_position' ] == 'scontent' ) ? 'margin-left' : 'right' ) . ':' . $opts[ 'offset' ] );
        }
        
        array_push( $classes, 'wpsr-sb-' . ( $opts[ 'type' ] == 'horizontal' ? 'hl' : 'vl' ) );
        array_push( $classes, 'wpsr-sb-' . $opts[ 'theme' ] );
        $classes = array_merge( $classes, explode( ',', $opts[ 'css_class' ] ) );
        
        if( $opts[ 'bg_color' ] != '' )
            array_push( $styles, 'background-color:' . $opts[ 'bg_color' ] );
        
        if( $opts[ 'border_color' ] != '' )
            array_push( $styles, 'border-color:' . $opts[ 'border_color' ] );
        
        if( $opts[ 'init_state' ] == 'close' )
            array_push( $classes, 'wpsr-mow' );
        
        $classes = implode( ' ', $classes );
        $styles = implode( ';', $styles );
        $min_on_width = ( $opts[ 'min_on_width' ] > 0 ) ? ' data-minonwidth="' . $opts[ 'min_on_width' ] . '" ' : '';
        
        // Apply filters
        $rows = apply_filters( 'wpsr_mod_sharebar_template', $rows );
        
        $open_icon = WPSR_Lists::public_icons( 'sb_open' );
        $close_icon = WPSR_Lists::public_icons( 'sb_close' );
        
        foreach( $rows as $row ){
            if( count( $row->buttons ) > 0 ){
                $html .= '<div class="wp-socializer wpsr-sharebar ' . esc_attr( $classes ) . '" style="' . esc_attr( $styles ) . '" data-stickto="' . esc_attr( $opts[ 'stick_element' ] ) . '"' . $min_on_width . '>';
                $html .= '<div class="wpsr-sb-inner">';
                
                foreach( $row->buttons as $button ){
                    $btn = key( ( array )$button );
                    array_push( $buttons, $btn );
                    $html .= '<span class="wpsr-btn">' . WPSR_Buttons::get_button( $btn, $page_info ) . '</span>';
                }
                $html .= '</div>';
                $html .= '<div class="wpsr-sb-close" title="Open or close sharebar"><span class="wpsr-bar-icon">' . $open_icon . $close_icon . '</span></div>';
                $html .= '</div>';
            }
            break;
        }
        
        $out = array(
            'html' => $html,
            'buttons' => $buttons
        );
        
        return apply_filters( 'wpsr_mod_sharebar_template_html', $out );
        
    }
    
    public static function preview(){
        
        $g = $_GET;
        
        $page_info = array(
            'title' => 'Google',
            'url' => 'https://www.google.com',
            'excerpt' => 'Dummy excerpt for WP Socializer preview',
            'short_url' => 'https://goo.gl/lightsaber',
            'comments_count' => '45',
            'post_id' => 1
        );
        $g[ 'stick_element' ] = '#main_content';
        
        // Generate HTML for the template
        $gen_out = self::html( $g, $page_info );
        
        echo '<!doctype html><html lang="en"><head><meta charset="utf-8"><title>WP Socializer template preview</title>';
        echo '<script src="' . WPSR_Lists::ext_res( 'jquery' ) . '"></script>';
        echo '<script src="' . WPSR_ADMIN_URL . 'js/sharebar_preview.js"></script>';
        echo '<link href="' . WPSR_ADMIN_URL . 'css/sharebar_preview.css" rel="stylesheet" type="text/css" media="all" />';
        echo '<meta name="viewport" content="width=device-width, initial-scale=1" />';
        
        $dmap = array(
            'vertical' => array(
                'tdec' => __( 'left', 'wpsr' ),
                'tinc' => __( 'right', 'wpsr' ),
            ),
            'horizontal' => array(
                'tdec' => __( 'up', 'wpsr' ),
                'tinc' => __( 'down', 'wpsr' )
            ),
        );
        
        $change_offset = '';
        $utext = array();
        if( $g[ 'type' ] == 'horizontal' ){
            $change_offset = ( $g[ 'hl_position' ] == 'wbottom' ) ? 'bottom' : 'top';
            $utext = $dmap[ 'horizontal' ];
        }else{
            $change_offset = ( $g[ 'vl_position' ] == 'wleft' ) ? 'left' : ( $g[ 'vl_position' ] == 'scontent' ) ? 'margin-left': 'right';
            $utext = $dmap[ 'vertical' ];
        }
        
        echo '</head>';
        echo '<body>' . $gen_out[ 'html' ];
        echo '
        <div id="main_content"></div>
        <div class="adjust_bar">
            <h3>' . __( 'Preview and Settings', 'wpsr' ) . '</h3>
            <h4>' . __( 'Adjust sharebar position from window', 'wpsr' ) . '</h4>
            <p>' . sprintf( __( 'Drag the slider below to adjust the sharebar position %s or %s', 'wpsr' ), $utext[ 'tdec' ], $utext[ 'tinc' ] ) . '</p>
            <div class="adj_btn_wrap"><input type="range" class="offset_drag" min="-500" max="500" step="1" width="100%" value="' . intval( $g[ 'offset' ] ) . '" data-coffset="' . $change_offset . '"/><input type="text" class="offset_val" value="' . intval( $g[ 'offset' ] ) . 'px" /></div>
            <div class="adj_footer"><button class="save_btn">' . __( 'Save and close', 'wpsr' ) . '</button> <button class="close_btn">' . __( 'Close without saving', 'wpsr' ) . '</button></div>
        </div>';
        
        WPSR_Includes::preview_print_includes();
        
        echo '</body></html>';
        
        die(0);
    }
    
}

class WPSR_Template_Followbar{
    
    public static function init(){
        
        add_action( 'wp_footer', array( __CLASS__, 'output' ) );
                
    }
    
    public static function output(){

        $fb_settings = WPSR_Lists::set_defaults( get_option( 'wpsr_followbar_settings' ), WPSR_Lists::defaults( 'followbar' ) );
        $loc_rules_answer = WPSR_Location_Rules::check_rule( $fb_settings[ 'loc_rules' ] );
        
        if( $fb_settings[ 'ft_status' ] != 'disable' && $loc_rules_answer ){
            $gen_html = self::html( $fb_settings );
            echo $gen_html;
            do_action( 'wpsr_do_followbar_print_template_end' );
        }
        
    }
    
    public static function html( $opts, $floating = True ){
        
        $opts = WPSR_Lists::set_defaults( $opts, WPSR_Lists::defaults( 'followbar' ) );
        $template = $opts[ 'template' ];
        $decoded = base64_decode( $template );
        $btns = json_decode( $decoded );
        $sb_sites = WPSR_Lists::social_icons();
        $html = '';
        
        if( !is_array( $btns ) || empty( $btns ) ){
            return '';
        }
        
        $styles = array();
        if ( $opts[ 'bg_color' ] != '' ) array_push( $styles, 'background-color: ' . $opts[ 'bg_color' ] . ';border-color: ' . $opts[ 'bg_color' ] );
        if ( $opts[ 'icon_color' ] != '' ) array_push( $styles, 'color: ' . $opts[ 'icon_color' ] );
        $style = join( ';', $styles );
        
        foreach( $btns as $btn_obj ){
            $id = key( (array) $btn_obj );
            
            if(!array_key_exists($id, $sb_sites)){
                continue;
            }
            
            $prop = $sb_sites[ $id ];
            
            $cicon = '';
            if ( $btn_obj->$id->icon != '' ){
                $cicon = 'sr-cicon';
                $style = '';
            }
            
            $iclasses = array( 'sr-' . $id, $cicon );
            $onclick = array_key_exists( 'onclick', $prop ) ? 'onclick="' . esc_attr( $prop[ 'onclick' ] ) . '"' : '';
            $title = ( ( $btn_obj->$id->text == '' ) ? $prop[ 'name' ] : urldecode( $btn_obj->$id->text ) );
            
            $html .= '<span class="' . esc_attr( join( ' ', $iclasses ) ) . '">';
            $html .= '<a rel="nofollow" href="' . esc_attr( $btn_obj->$id->url ) . '" target="_blank" title="' . esc_attr( $title ) . '" style="' . esc_attr( $style ) . '" ' . $onclick . '>';
            
            if( $btn_obj->$id->icon == '' ){
                $html .= '<i class="' . esc_attr( $prop[ 'icon' ] ) . '"></i>';
            }else{
                $html .= '<img src="' . esc_attr( $btn_obj->$id->icon ) . '" alt="' . esc_attr( $prop[ 'name' ] ) . '" />';
            }
            
            $html .= '</a>';
            
            $html .= '</span>';
            
        }
        
        $classes = array( 'socializer', 'sr-followbar', 'sr-' . $opts[ 'size' ] );
        
        if( $opts[ 'shape' ] != '' ) array_push( $classes, 'sr-' . $opts[ 'shape' ] );
        if( $opts[ 'hover' ] != '' ) array_push( $classes, 'sr-' . $opts[ 'hover' ] );
        if( $opts[ 'pad' ] != '' ) array_push( $classes, 'sr-' . $opts[ 'pad' ] );
        if( $opts[ 'orientation' ] == 'vertical' ) array_push( $classes, 'sr-vertical' );
        if( $opts[ 'open_popup' ] == '' ) array_push( $classes, 'sr-popup' );
        if( !$floating ) array_push( $classes, 'sr-multiline' );
        
        $classes = join( ' ', $classes );
        
        $html = '<div class="' . $classes . '">' . $html . '</div>';
        
        $open_icon = WPSR_Lists::public_icons( 'fb_open' );
        $close_icon = WPSR_Lists::public_icons( 'fb_close' );
        
        if( $floating ){
            $min_on_width = ( $opts[ 'min_on_width' ] > 0 ) ? ' data-minonwidth="' . $opts[ 'min_on_width' ] . '" ' : '';
            $title = ( $opts[ 'title' ] != '' ) ? '<div class="sr-fb-title">' . $opts[ 'title' ] . '</div>' : '';
            $close_btn = '<div class="wpsr-fb-close" title="Open or close followbar"><span class="wpsr-bar-icon">' . $open_icon . $close_icon . '</span></div>';
            $orientation = ( $opts[ 'orientation' ] == 'horizontal' ) ? 'sr-fb-hl' : 'sr-fb-vl';
            $fb_classes = array( 'wpsr-followbar', 'sr-fb-' . $opts[ 'position' ], $orientation );
            
            if( $opts[ 'init_state' ] == 'close' )
                array_push( $fb_classes, 'wpsr-mow' );
            
            $html = '<div class="' . esc_attr( join( ' ', $fb_classes ) ) . '"' . $min_on_width . '>' . $title . $html . $close_btn . '</div>';
        }
        
        if( !$floating && isset( $opts[ 'profile_text' ] ) && trim( $opts[ 'profile_text' ] ) != '' ){
            $html = '<p>' . $opts[ 'profile_text' ] . '</p>' . $html;
        }
        
        return $html;
        
    }
    
}

class WPSR_Template_Text_Sharebar{
    
    public static function init(){
        
        add_action( 'wp_footer', array( __CLASS__, 'output' ) );
        
    }
    
    public static function output(){

        $tsb_settings = WPSR_Lists::set_defaults( get_option( 'wpsr_text_sharebar_settings' ), WPSR_Lists::defaults( 'text_sharebar' ) );
        $loc_rules_answer = WPSR_Location_Rules::check_rule( $tsb_settings[ 'loc_rules' ] );
        
        if( $tsb_settings[ 'ft_status' ] != 'disable' && $loc_rules_answer && !wp_is_mobile() ){
            $gen_html = self::html( $tsb_settings );
            echo $gen_html;
            do_action( 'wpsr_do_text_sharebar_print_template_end' );
        }
        
    }
    
    public static function html( $opts ){
        
        $opts = WPSR_Lists::set_defaults( $opts, WPSR_Lists::defaults( 'text_sharebar' ) );
        $template = $opts[ 'template' ];
        $decoded = base64_decode( $template );
        $btns = json_decode( $decoded );
        $sb_sites = WPSR_Lists::social_icons();
        $page_info = WPSR_Metadata::metadata();
        $html = '';
        
        $g_settings = get_option( 'wpsr_general_settings' );
        $g_settings = WPSR_Lists::set_defaults( $g_settings, WPSR_Lists::defaults( 'gsettings_twitter' ) );
        $t_username = ( $g_settings[ 'twitter_username' ] != '' ) ? '@' . $g_settings[ 'twitter_username' ] : '';
        
        if( !is_array( $btns ) || empty( $btns ) ){
            return '';
        }
        
        foreach( $btns as $btn ){
            $sb_info = $sb_sites[ $btn ];
            $link = array_key_exists( 'link_tsb', $sb_info ) ? $sb_info[ 'link_tsb' ] : $sb_info[ 'link' ];
            $onclick = array_key_exists( 'onclick', $sb_info ) ? 'onclick="' . esc_attr( $sb_info[ 'onclick' ] ) . '"' : '';
            
            $html .= '<li><a href="#" title="' . esc_attr( $sb_info[ 'title' ] ) . '" data-link="' . esc_attr( $link ) . '" style="color: ' . esc_attr( $opts[ 'icon_color' ] ) . '" ' . $onclick . '><i class="' . esc_attr( $sb_info[ 'icon' ] ) . '"></i></a></li>';
        }
        
        $html = '<ul class="wpsr-text-sb wpsr-tsb-' . esc_attr( $opts[ 'size' ] ) . ' wpsr-clearfix" data-content="' . esc_attr( $opts[ 'content' ] ) . '" data-tcount="' . esc_attr( $opts[ 'text_count' ] ) . '" style="background-color: ' . esc_attr( $opts[ 'bg_color' ] ) . '" data-url="' . esc_attr( $page_info[ 'url' ] ) . '" data-title="' . esc_attr( $page_info[ 'title' ] ) . '" data-surl="' . esc_attr( $page_info[ 'short_url' ] ) . '" data-tuname="' . esc_attr( $t_username ) . '">' . $html . '</ul>';
        
        return $html;
        
    }
    
}

class WPSR_Template_Mobile_Sharebar{
    
    public static function init(){
        
        add_action( 'wp_footer', array( __CLASS__, 'output' ) );
        
    }
    
    public static function output(){

        $msb_settings = WPSR_Lists::set_defaults( get_option( 'wpsr_mobile_sharebar_settings' ), WPSR_Lists::defaults( 'mobile_sharebar' ) );
        $loc_rules_answer = WPSR_Location_Rules::check_rule( $msb_settings[ 'loc_rules' ] );
        
        if( $msb_settings[ 'ft_status' ] != 'disable' && $loc_rules_answer ){
            $gen_html = self::html( $msb_settings );
            echo $gen_html;
            do_action( 'wpsr_do_mobile_sharebar_print_template_end' );
        }
        
    }
    
    public static function html( $opts ){
        
        $opts = WPSR_Lists::set_defaults( $opts, WPSR_Lists::defaults( 'mobile_sharebar' ) );
        $template = $opts[ 'template' ];
        $decoded = base64_decode( $template );
        $btns = json_decode( $decoded );
        $sb_sites = WPSR_Lists::social_icons();
        $page_info = WPSR_Metadata::metadata();
        $html = '';
        
        if( !is_array( $btns ) || empty( $btns ) ){
            return '';
        }
        
        $styles = array();
        if ( $opts[ 'bg_color' ] != '' ) array_push( $styles, 'background-color: ' . $opts[ 'bg_color' ] );
        if ( $opts[ 'icon_color' ] != '' ) array_push( $styles, 'color: ' . $opts[ 'icon_color' ] );
        $style = join( ';', $styles );
        
        foreach( $btns as $id ){
            
            if(!array_key_exists($id, $sb_sites)){
                continue;
            }
            
            $sb_info = $sb_sites[ $id ];
            $link = self::get_url( $sb_info[ 'link' ], $page_info );
            $onclick = array_key_exists( 'onclick', $sb_info ) ? 'onclick="' . esc_attr( $sb_info[ 'onclick' ] ) . '"' : '';
            
            $html .= '<span class="sr-' . $id . '"><a rel="nofollow" target="_blank" href="' . esc_attr( $link ) . '" title="' . esc_attr( $sb_info[ 'title' ] ) . '" style="' . $style . '" ' . $onclick . '><i class="' . esc_attr( $sb_info[ 'icon' ] ) . '" tar></i></a></span>';
        }
        
        $classes = array( 'socializer', 'wpsr-mobile-sb', 'sr-' . $opts[ 'size' ], 'sr-fluid', 'sr-opacity', 'sr-popup' );
        
        if( $opts[ 'pad' ] != '' ) array_push( $classes, 'sr-' . $opts[ 'pad' ] );
        if( $opts[ 'hide_on_scroll' ] == 'yes' ) array_push( $classes, 'wpsr-mobile-hos' );
        
        $classes = join( ' ', $classes );
        
        $html = '<div class="' . $classes . '">' . $html . '</div>';
        
        return $html;
        
    }
    
    public static function get_url( $url, $pinfo ){
        
        $g_settings = get_option( 'wpsr_general_settings' );
        $g_settings = WPSR_Lists::set_defaults( $g_settings, WPSR_Lists::defaults( 'gsettings_twitter' ) );
        $t_username = ( $g_settings[ 'twitter_username' ] != '' ) ? '@' . $g_settings[ 'twitter_username' ] : '';
        
        $search = array(
            '{url}',
            '{title}',
            '{excerpt}',
            '{s-url}',
            '{rss-url}',
            '{twitter-username}',
        );
        
        $replace = array(
            $pinfo[ 'url' ],
            $pinfo[ 'title' ],
            $pinfo[ 'excerpt' ],
            $pinfo[ 'short_url' ],
            $pinfo[ 'rss_url' ],
            $t_username
        );
        
        return str_replace( $search, $replace, $url );
    }
    
}

WPSR_Template_Buttons::init();
WPSR_Template_Sharebar::init();
WPSR_Template_Followbar::init();
WPSR_Template_Text_Sharebar::init();
WPSR_Template_Mobile_Sharebar::init();

class wpsr_template_button_handler{
    
    private $props;
    private $type;
    
    function __construct( $properties, $type ){
        
        $this->props = WPSR_Lists::set_defaults( $properties, WPSR_Lists::defaults( 'buttons_template' ) );
        $this->type = $type;
        
    }
    
    function print_template( $content ){
        
        $call_from_excerpt = 0;
        $call_stack = debug_backtrace();
        
        foreach( $call_stack as $call ){
            if( $call['function'] == 'the_excerpt' || $call['function'] == 'get_the_excerpt' ){
                $call_from_excerpt = 1;
            }
        }
        
        $loc_rules_answer = WPSR_Location_Rules::check_rule( $this->props[ 'loc_rules' ] );
        $rule_in_excerpt = ( $this->props[ 'in_excerpt' ] == 'show' );
        $output = $content;
        
        if( $loc_rules_answer ){
            
            if( ( $this->type == 'content' && $call_from_excerpt != 1 ) || ( $this->type == 'excerpt' && $rule_in_excerpt == 1 ) ){
                
                $gen_out = WPSR_Template_Buttons::html( $this->props[ 'content' ], array(), $this->props[ 'min_on_width' ] );
                
                if( !empty( $gen_out[ 'html' ] ) ){
                
                    $final_template = $gen_out[ 'html' ];
                    
                    if( $this->props[ 'position' ] == 'above_below_posts' )
                        $output = $final_template . $content . $final_template;
                    
                    if( $this->props[ 'position' ] == 'above_posts' )
                        $output = $final_template . $content;
                    
                    if( $this->props[ 'position' ] == 'below_posts' )
                        $output = $content . $final_template;
                    
                }
            }
            
            do_action( 'wpsr_do_buttons_print_template_end' );
            
        }
        
        return $output;
        
    }
    
}

?>