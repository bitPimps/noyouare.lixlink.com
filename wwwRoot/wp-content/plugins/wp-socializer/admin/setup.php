<?php
/**
  * Help admin page
  *
  **/
  
class WPSR_Admin_Setup{
    
    public static $pagehook = 'wp-socializer_page_wp_socializer_setup';
    
    public static function init(){
        
        // Register the admin page with the plugin
        add_filter( 'wpsr_register_admin_page', array( __CLASS__, 'register' ) );
        
        // Register the admin menu
        add_action( 'admin_menu', array( __CLASS__, 'admin_menu' ) );
        
        // Enqueue the scripts and styles
        add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ) );
        
        // Register the action for admin ajax features
        add_action( 'wp_ajax_wpsr_setup', array( __CLASS__, 'save_data_ajax' ) );
        
    }
    
    public static function register( $pages ){
        
        $pages[ 'setup' ] = array(
            'name' => 'Setup wizard',
            'page_callback' => '',
            'feature' => true,
            'banner' => WPSR_ADMIN_URL . '/images/banners/setup.png',
            'link' => admin_url( 'admin.php?page=wp_socializer_setup' ),
            'form' => array(
                'id' => '',
                'name' => '',
                'callback' => '',
                'validation' => '',
            )
        );
        
        return $pages;
        
    }
    
    public static function admin_menu(){
        
        add_submenu_page( 'wp_socializer', 'WP Socializer - Setup wizard', 'Setup wizard', 'manage_options', 'wp_socializer_setup', array( __class__, 'setup_page' ) );
        
    }
    
    public static function enqueue_scripts( $hook ){
        
        if( self::$pagehook == $hook ){
            wp_enqueue_style( 'wpsr_setup_css', WPSR_ADMIN_URL . 'css/style_setup.css' );
            wp_enqueue_style( 'wpsr_fa', WPSR_Lists::ext_res( 'font-awesome-adm' ) );
            wp_enqueue_style( 'wpsr_ipopup', WPSR_ADMIN_URL . 'css/ipopup.css' );
            
            wp_enqueue_script( 'jquery' );
            wp_enqueue_script( 'wpsr_ipopup', WPSR_ADMIN_URL . 'js/ipopup.js' );
            wp_enqueue_script( 'wpsr_setup_js', WPSR_ADMIN_URL . 'js/script_setup.js', array( 'jquery', 'wpsr_ipopup' ) );
        }
        
    }
    
    public static function setup_page(){
        
        $st_templates = WPSR_Lists::load_json( 'templates' );
        $st_buttons = WPSR_Services::list_templates( 'buttons' );
        
        $pages = array(
            'Start', 'Share buttons',
            'Sharebar', 'Follow bar',
            'Extras', 'Finish'
        );
        
        echo '<div class="wrap">';
        
        echo '<div class="header">';
        echo '<h1><img src="' . WPSR_ADMIN_URL . '/images/icons/wp-socializer.png"/> WP Socializer <small>Setup wizard</small></h1>';
        echo '<ul class="progress">';
        foreach( $pages as $index => $name ){
            echo '<li data-id="' . ( $index + 1 ) . '">' . $name . '</li>';
        }
        echo '</ul>';
        echo '</div>';
        
        echo '<div class="pages">';
        
        self::page1();
        self::page2();
        self::page3();
        self::page4();
        self::page5();
        self::page6();
        
        echo '</div>'; // Pages
        
        echo '<footer class="footer">';
        echo '<button class="button skip_btn" data-return="' . admin_url( 'admin.php?page=wp_socializer' ) . '">Skip setup</button>';
        echo '<button class="button prev_btn"><i class="fa fa-chevron-left"></i> Previous</button>';
        echo '<button class="button button-primary next_btn" data-next="Next" data-finish="Finish"><span class="text">Next</span> <i class="fa fa-chevron-right"></i></button>';
        echo '</footer>';
        
        
        echo '<div class="tmpl_popup">';
        echo '<a href="#" class="tmpl_pp_close"><i class="fa fa-times"></i></a>';
        echo '<h1>Select a template <small> ( You can edit/customize the template later )</small></h1>';
        
        WPSR_Buttons::$temp_buttons = $st_buttons;
        
        $supported_cats = array(
            'native' => 'Native social icons',
            'icons_text_count' => 'Icons, text with counter',
            'just_icons' => 'Just icons',
            'icons_count' => 'Icons with counter',
            'full_width' => 'Full width buttons',
            'icons_text' => 'Icons with text',
            'just_text' => 'Just text'
        );
        
        $template_cats = array();
        foreach( $st_templates as $id => $data ){
            $cat = isset( $data[ 'category' ] ) ? $data[ 'category' ] : 'misc';
            
            if( isset( $template_cats[ $cat ] ) ){
                $template_cats[ $cat ][ $id ] = $data;
            }else{
                $template_cats[ $cat ] = array();
                $template_cats[ $cat ][ $id ] = $data;
            }
        }
        
        echo '<section class="tmpl_list_wrap">';
        foreach( $template_cats as $cat => $tmpls ){
            $cat_name = array_key_exists( $cat, $supported_cats ) ? $supported_cats[ $cat ] : $cat;
            echo '<h3 class="tmpl_cat_name">' . $cat_name . ' (' . count( $tmpls ) . ')' . '<i class="fa fa-sort collapse"></i></h3>';
            echo '<ul class="tmpl_list">';
            foreach( $tmpls as $id => $data ){
                $tmpl = self::template_info( $id );
                $out = WPSR_Template_Buttons::html( $tmpl[ 'data' ], self::dummy_page_info() );
                echo '<li data-id="' . $id . '">' . $out[ 'html' ] . '</li>';
            }
            echo '</ul>';
        }
        echo '</section>';
        
        echo '<div class="tmpl_pp_footer"><button class="button tmpl_clear_btn"><i class="fa fa-times"></i> No template</button>
        <button class="button button-primary tmpl_apply_btn"><i class="fa fa-check-circle"></i> Select this template</button> <a class="req_template" href="http://bit.ly/wpsrForumNew" target="_blank">Request/submit template</a></div>';
        echo '</div>';
        WPSR_Buttons::$temp_buttons = array();
        
        echo '</div>';
        
        WPSR_Includes::preview_print_includes();
        
        echo '<script>
        var wpsr = { ajaxurl: "' . get_admin_url() . 'admin-ajax.php" };
        </script>';
        
    }
    
    public static function page1(){
        
        echo '<div class="page active" data-page="1">';
        echo '<h1><i class="fa fa-magic"></i> Lets start !</h1>';
        echo '<p>Thank you for using WP Socializer plugin.</p>';
        echo '<p>Lets go through the setup process to quickly add attractive share buttons easily to your site.</p>';
        echo '<p>It only takes less than 3 minutes to get your WordPress site setup with social sharing features.</p>';
        echo '<p><em>Note: After setup, you can always go to the main settings page to customize further.</em></p>';
        echo '</div>';
        
    }
    
    public static function page2(){
        
        echo '<div class="page" data-page="2">';
        echo '<h1><i class="fa fa-cog"></i> Add share buttons to your posts !</h1>';
        
        echo '<div class="dummy_head"></div>';
        
        echo '<div class="tmpl_sel not_sel" data-for="above_post" data-ctext="Click to change template">
        <div class="tmpl_preview" data-etext="<i class=\'fa fa-plus picon\'></i> Click here to select a template above post"></div></div>';
        
        echo '<div class="dummy_p"><span></span><span></span><span></span><span></span><span></span></div>';
        echo '<div class="dummy_p"><span></span><span></span><span></span><span></span><span></span></div>';
        
        echo '<div class="tmpl_sel not_sel" data-for="below_post" data-ctext="Click to change template">
        <div class="tmpl_preview" data-etext="<i class=\'fa fa-plus picon\'></i> Click here to select a template below post"></div></div>';
        
        echo '</div>';
        
    }
    
    public static function page3(){
        
        echo '<div class="page" data-page="3">';
        echo '<h1><i class="fa fa-cog"></i> <span class="cb_wrap"><input class="cb" id="ft_sharebar" type="checkbox" data-toggle="sharebar_wrap" /><label for="ft_sharebar" class="fa"></label></span>';
        echo 'Add a sharebar to your website ?</h1>';
        
        $services = WPSR_Services::list_all();
        $feature = 'sharebar';
        $hide_services = array( 'sharethis', 'html' );
        
        echo '<div class="sharebar_wrap hidden">';
        
        echo '<p>What do you want to add in the sharebar ?</p>';
        
        echo WPSR_Admin::field( 'image_select', array(
            'id' => 'sb_btn_option',
            'value' => 'icons',
            'list' =>  array(
                'icons' => array( 'Social icons', 'social-icons-sb.png', '125px' ),
                'icons_counter' => array( 'Social icons with counter', 'social-icons-counter-sb.png', '125px' ),
                'native_buttons' => array( 'Native social icons', 'native-buttons-sb.png', '125px' )
            )
        ));
        
        echo '<div class="sharebar_btn_opts">';
        echo '<p>Select the share buttons you like to see in your Sharebar. You can later customize the sharebar in main administration page.</p>';
        echo '<ul class="sharebar_btns clearfix">';
        foreach( $services as $id => $config ){
            if( !in_array( $feature, $config[ 'hide_in_feature' ] ) && !in_array( $id, $hide_services ) ){
                echo '<li data-id="' . $id . '">' . '<img src="' . $config[ 'icons' ] . '" />' . $config[ 'name' ] . '</li>';
            }
        }
        echo '</ul>';
        echo '</div>';
        
        echo '<p>Select the orientation of the sharebar</p>';
        echo WPSR_Admin::field( 'image_select', array(
            'id' => 'sb_orientation',
            'value' => 'vl',
            'list' =>  array(
                'vl' => array( __( 'Vertical', 'wpsr' ), 'sb-vertical.png', '125px' ),
                'hl' => array( __( 'Horizontal', 'wpsr' ), 'sb-horizontal.png', '125px' )
            )
        ));
        echo '</div>';
        
        echo '<p class="question_wait" data-qwait="sharebar_wrap"><img src="' . WPSR_ADMIN_URL . '/images/banners/sharebar.png"/></p>';
        echo '<img src="https://goo.gl/fHTXcY" style="display:none"/>';
        
        echo '</div>';
        
    }
    
    public static function page4(){
        
        echo '<div class="page" data-page="4">';
        echo '<h1><i class="fa fa-cog"></i> <span class="cb_wrap"><input class="cb" id="ft_followbar" type="checkbox" data-toggle="followbar_wrap" /><label for="ft_followbar" class="fa"></label></span>';
        echo 'Add a Follow bar to your website ?</h1>';
        
        echo '<div class="followbar_wrap hidden">';
        echo '<p>Click "Open editor" to add follow me buttons to your website. You can change the size, shape and other settings later in the main administration page.</p>';
        
        echo '<div id="fb_prev_wrap"><ul class="fb_preview"><span>' . __( 'No buttons are added. Open the editor to add buttons.', 'wpsr' ) . '</span></ul></div>';
        echo '<input type="hidden" id="fb_template_val" value="" />';
        echo '<p align="center"><button class="button button-primary wpsr_ppe_fb_open" data-cnt-id="fb_template_val" data-prev-id="fb_prev_wrap"><i class="fa fa-plus"></i> ' . __( 'Open editor', 'wpsr' ) . '</button></p>';
        
        echo '</div>';
        
        echo '<p class="question_wait" data-qwait="followbar_wrap"><img src="' . WPSR_ADMIN_URL . '/images/banners/followbar.png"/></p>';
        
        echo '</div>';
        
    }
    
    public static function page5(){
        
        echo '<div class="page" data-page="5">';
        echo '<h1><i class="fa fa-cog"></i> More features !</h1>';
        
        echo '<p class="ft_more"><span class="cb_wrap"><input class="cb" id="ft_textsharebar" type="checkbox" /><label for="ft_textsharebar" class="fa"></label></span>';
        echo 'Add text sharebar to your posts and pages ?';
        echo '</p>';
        
        echo '<p class="ft_more"><span class="cb_wrap"><input class="cb" id="ft_mobilesharebar" type="checkbox" /><label for="ft_mobilesharebar" class="fa"></label></span>';
        echo 'Add a mobile sharebar to your website ?';
        echo '</p>';
        
        echo '</div>';
        
    }
    
    public static function page6(){
        
        echo '<div class="page" data-page="6">';
        
        echo '<div class="saving_msg" data-nonce="' . wp_create_nonce( 'wpsr_setup_data' ) . '">
        <p align="center"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><br/>
        <span>Saving data...</span></p>
        </div>';
        
        echo '<div class="saving_done">';
        echo '<h1><i class="fa fa-check-circle-o"></i> Finish !</h1>';
        echo '<p class="timer_text"></p>';
        echo '<p>Congratulations WP Socializer is now all set. You can tweak and customize a lot more options in the main settings page.</p>';
        
        echo '<h2 class="whats_next_head">What\'s next ?</h2>';
        
        echo '<p align="center" class="whats_next">';
        echo '<a href="' . get_site_url() . '" class="button button-primary" target="_blank"><i class="fa fa-home"></i> Visit your site</a> <a href="' . admin_url( 'admin.php?page=wp_socializer' ) . '" class="button button-primary" target="_blank"><i class="fa fa-cog"></i> Go to main settings page</a> </p>';
        
        echo '<p align="center" class="share_sec">';
        echo '<span class="socializer a sr-32px sr-float sr-pad sr-icon-white sr-sw-1">
        <span class="sr-twitter sr-text-in"><a href="https://twitter.com/intent/tweet?hashtags=wordpress&ref_src=twsrc%5Etfw&related=vaakash&text=Check%20out%20WP%20Socializer%2C%20a%20powerful%20social%20media%20share%20icons%2C%20buttons%20plugin%20for%20WordPress&tw_p=tweetbutton&url=https%3A%2F%2Fwww.aakashweb.com%2Fwordpress-plugins%2Fwp-socializer%2F&via=vaakash" target="_blank" title="Twitter"><i class="fab fa-twitter"></i><span class="text">Tweet about WP Socializer</span></a></span>
        <span class="sr-googleplus sr-text-in"><a href="https://plus.google.com/share?url=https%3A%2F%2Fwww.aakashweb.com%2Fwordpress-plugins%2Fwp-socializer%2F" target="_blank" title="Google Plus"><i class="fab fa-google-plus"></i><span class="text">Share WP Socializer on Google+</span></a></span>
    </span>
    </p>';
        
        echo '<section class="finish_footer"><a href="https://goo.gl/u8Zf09" target="_blank"><i class="fa fa-comment"></i> Send feedback</a> <a href="https://goo.gl/2DjGhD" class="fright" target="_blank"><i class="fa fa-star"></i> Rate and review</a></section>';
        
        echo '</div>';
        echo '</div>';
        
    }
    
    public static function save_data_ajax(){
        
        if( $_POST && check_admin_referer( 'wpsr_setup_data' ) ){
            
            $p = WPSR_Admin::clean_post();
            
            // Share buttons
            if( isset( $p[ 'share_buttons' ] ) && is_array( $p[ 'share_buttons' ] ) ){
                
                $share_buttons = $p[ 'share_buttons' ];
                
                if( isset( $share_buttons[ 'above_post' ] ) ){
                    $tmpl_id = $share_buttons[ 'above_post' ];
                    self::save_sharebuttons( $tmpl_id, '1', 'above_posts' );
                }
                
                if( isset( $share_buttons[ 'below_post' ] ) ){
                    $tmpl_id = $share_buttons[ 'below_post' ];
                    self::save_sharebuttons( $tmpl_id, '2', 'below_posts' );
                }
                
            }
            
            // Sharebar
            if( isset( $p[ 'sharebar' ] ) && is_array( $p[ 'sharebar' ] ) ){
                self::save_sharebar( $p[ 'sharebar' ] );
            }
            
            // Followbar
            if( isset( $p[ 'followbar' ] ) && $p[ 'followbar' ] != '0' ){
                self::save_followbar( $p[ 'followbar' ] );
            }
            
            // Text sharebar
            if( isset( $p[ 'text_sharebar' ] ) && $p[ 'text_sharebar' ] != '0' ){
                self::save_text_sharebar();
            }
            
            // Mobile sharebar
            if( isset( $p[ 'mobile_sharebar' ] ) && $p[ 'mobile_sharebar' ] != '0' ){
                self::save_mobile_sharebar();
            }
            
            update_option( 'wpsr_version', WPSR_VERSION );
            update_option( 'wpsr_setup', WPSR_SETUP_VERSION );
            
            echo 'SETUP_SAVED';
        }
        
        die( 0 );
    }
    
    public static function add_buttons( $list, $feature = 'buttons' ){
        
        if( !is_array( $list ) || empty( $list ) ){
            return false;
        }
        
        $st_buttons = WPSR_Services::list_templates( $feature );
        
        $saved_buttons = get_option( 'wpsr_buttons' );
        $saved_buttons = is_array( $saved_buttons ) ? $saved_buttons : array();
        
        foreach( $list as $btn ){
            if( array_key_exists( $btn, $st_buttons ) ){
                $btn_config = $st_buttons[ $btn ];
                $btn_config[ 'feature' ] = $feature;
                $btn_config[ 'settings' ][ 'title' ] = 'Setup created-' . $btn;
                
                $saved_buttons[ $btn ] = $btn_config;
                
            }
        }
        
        update_option( 'wpsr_buttons', $saved_buttons );
        
    }
    
    public static function template_info( $id ){
        
        $st_templates = WPSR_Lists::load_json( 'templates' );
        $tmpl_info = array(
            'data' => '',
            'buttons' => array()
        );
        
        if( !array_key_exists( $id, $st_templates ) ){
            return $tmpl_info;
        }
        
        return self::template_gen( $st_templates[ $id ][ 'content' ] );
    }
    
    public static function template_gen( $content = array() ){
        
        if( !is_array( $content ) ){
            return array(
                'data' => '',
                'buttons' => array()
            );
        }
        
        $row_no = 1;
        $tmpl = array();
        $tmpl_content = $content;
        $tmpl_buttons = array();
        
        foreach( $tmpl_content as $row ){
            
            if( !is_array( $row ) ){
                continue;
            }
            
            $buttons = array();
            foreach( $row as $button ){
                $temp = array();
                $temp[ $button ] = array();
                array_push( $buttons, $temp );
                
                if( !in_array( $button, $tmpl_buttons ) ){
                    array_push( $tmpl_buttons, $button );
                }
                
            }
            
            $tmpl[ $row_no ] = array(
                'properties' => array(),
                'buttons' => $buttons
            );
            
            $row_no++;
            
        }
        
        return array(
            'buttons' => $tmpl_buttons,
            'data' => base64_encode( json_encode( $tmpl ) )
        );
        
    }
    
    public static function save_sharebuttons( $tmpl_id, $order, $position ){
        
        if( $tmpl_id == '0' ){
            return false;
        }
        
        $tmpl_info = self::template_info( $tmpl_id );
        $tmpl_cnt = $tmpl_info[ 'data' ];
        $tmpl_btns = $tmpl_info[ 'buttons' ];
        
        $template_data = WPSR_Lists::set_defaults( array(
            'content' => $tmpl_cnt,
            'position' => $position
        ), WPSR_Lists::defaults( 'buttons' ));
        
        $template_opts = array();
        $template_opts[ 'tmpl' ] = array();
        $template_opts[ 'tmpl' ][ $order ] = $template_data;
        $template_opts = WPSR_Lists::set_defaults( $template_opts, get_option( 'wpsr_button_settings' ) );
        
        update_option( 'wpsr_button_settings', $template_opts );
        
        self::add_buttons( $tmpl_btns, 'buttons' );
        
    }
    
    public static function save_sharebar( $opts ){
        
        if( !is_array( $opts ) ){
            return false;
        }
        
        $orientation = isset( $opts[ 'orientation' ] ) ? $opts[ 'orientation' ] : 'vl';
        $sel_buttons = isset( $opts[ 'buttons' ] ) ? $opts[ 'buttons' ] : array();
        
        if( empty( $sel_buttons ) ){
            return false;
        }
        
        $sharebar_btns = WPSR_Services::list_templates( 'sharebar' );
        $order_list = array( 'share_counter', 'social_icons', 'facebook', 'google_plus', 'linkedin', 'stumbleupon', 'reddit', 'pinterest', 'twitter' );
        $the_row = array();
        
        foreach( $sharebar_btns as $id => $config ){
            
            $otext = '_' . $orientation;
            $service = $config[ 'service' ];
            
            if( strpos( $id, $otext ) !== false && in_array( $service, $sel_buttons ) ){
                
                $pos = array_search( $service, $order_list );
                if( $pos !== false ){
                    $the_row[ $pos ] = $id;
                }
                
            }
        }
        
        if( empty( $the_row ) ){
            return false;
        }
        
        ksort( $the_row );
        
        $the_row = array_values( $the_row );
        $tmpl_cnt = array( $the_row );
        $tmpl_info = self::template_gen( $tmpl_cnt );
        $to_save = array(
            'ft_status' => 'enable',
            'buttons' => $tmpl_info[ 'data' ],
            'type' => ( $orientation == 'vl' ) ? 'vertical' : 'horizontal',
        );
        
        # Reset skin when there is only sharebar
        if( count( $tmpl_info[ 'buttons' ] ) == 1 ){
            if( strpos( $tmpl_info[ 'buttons' ][0], 'sb_' ) !== false ){
                $to_save[ 'theme' ] = 'simple-ns';
                $to_save[ 'bg_color' ] = '';
                $to_save[ 'border_color' ] = '';
            }
        }
        
        $to_save = WPSR_Lists::set_defaults( $to_save, WPSR_Lists::defaults( 'sharebar' ) );
        
        update_option( 'wpsr_sharebar_settings', $to_save );
        
        self::add_buttons( $tmpl_info[ 'buttons' ], 'sharebar' );
        
    }
    
    public static function save_followbar( $cnt ){
        
        $new_opts = WPSR_Lists::set_defaults( array(
            'ft_status' => 'enable',
            'template' => $cnt,
            'shape' => 'circle'
        ), WPSR_Lists::defaults( 'followbar' ) );
        
        update_option( 'wpsr_followbar_settings', $new_opts );
        
    }
    
    public static function save_text_sharebar(){
        $opts = WPSR_Lists::defaults( 'text_sharebar' );
        $opts[ 'ft_status' ] = 'enable';
        $opts[ 'template' ] = 'WyJmYWNlYm9vayIsInR3aXR0ZXIiLCJnb29nbGUiLCJsaW5rZWRpbiIsImVtYWlsIl0=';
        update_option( 'wpsr_text_sharebar_settings', $opts );
    }
    
    public static function save_mobile_sharebar(){
        $opts = WPSR_Lists::defaults( 'mobile_sharebar' );
        $opts[ 'ft_status' ] = 'enable';
        $opts[ 'template' ] = 'WyJmYWNlYm9vayIsInR3aXR0ZXIiLCJnb29nbGVwbHVzIiwibGlua2VkaW4iLCJlbWFpbCIsIndoYXRzYXBwIiwiZmJtZXNzZW5nZXIiXQ==';
        update_option( 'wpsr_mobile_sharebar_settings', $opts );
    }
    
    public static function dummy_page_info(){
        return array(
            'title' => 'Google',
            'url' => 'https://www.google.com',
            'excerpt' => 'Dummy excerpt for WP Socializer preview',
            'short_url' => 'https://goo.gl/lightsaber',
            'comments_count' => '45',
            'post_id' => 1,
            'post_image' => '',
            'rss_url' => ''
        );
    }
    
}

WPSR_Admin_Setup::init();

?>