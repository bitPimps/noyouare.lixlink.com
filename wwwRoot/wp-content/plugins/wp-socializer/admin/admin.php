<?php
/**
  * Main entry point class for admin page
  * 
  **/

class WPSR_Admin{
    
    public static $pages = array();
    public static $pagehook = 'toplevel_page_wp_socializer';
    public static $current_page = 'home';
    
    public static function init(){
        
        // Register the admin menu
        add_action( 'admin_menu', array( __CLASS__, 'admin_menu' ) );
        
        // Load all the admin pages
        add_action( 'init', array( __CLASS__, 'register_pages' ) );
        
        // Enqueue the scripts and styles
        add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ) );
        
        // Register action to include admin scripts
        add_action( 'admin_print_scripts', array( __CLASS__, 'inline_scripts' ) );
        
        // Register the action for admin ajax features
        add_action( 'wp_ajax_wpsr_admin_ajax', array( __CLASS__, 'admin_ajax' ) );
        
        // Register the action links in plugin list page
        add_filter( 'plugin_action_links_' . WPSR_BASE_NAME, array( __CLASS__, 'action_links' ) );
        
        // Register the admin notice to inform new version features
        add_action( 'admin_notices', array( __CLASS__, 'admin_notices' ) );
        
        add_action( 'plugins_loaded', array( __CLASS__, 'on_activate' ) );
        
        register_activation_hook( WPSR_BASE_NAME, array( __CLASS__, 'on_activate' ) );
        
    }
    
    public static function admin_menu(){
        
        $pages = self::get_pages();
        $icon = WPSR_ADMIN_URL . 'images/icons/wp-socializer-sm.png';
        
        add_menu_page( 'WP Socializer - Admin page', 'WP Socializer', 'manage_options', 'wp_socializer', array( __CLASS__, 'admin_page' ), $icon );
        
        add_submenu_page( 'wp_socializer', 'WP Socializer - Admin page', 'Home', 'manage_options', 'wp_socializer', array( __CLASS__, 'admin_page' ) );
        
        foreach( $pages as $id=>$config ){
            if( !isset( $config[ 'link' ] ) ){
                add_submenu_page( 'wp_socializer', 'WP Socializer - ' . $config[ 'name' ], $config[ 'name' ], 'manage_options', 'wp_socializer&tab="' . $id . '"', array( __CLASS__, 'admin_page' ) );
            }
        }
    }
    
    public static function register_pages(){
        
        $init_pages = apply_filters( 'wpsr_register_admin_page', array() );
        
        foreach( $init_pages as $id => $config ){
            
            self::$pages[ $id ] = $config;
            
            // Register the validation filter for the form
            if( isset( $config[ 'form' ][ 'validation' ] ) ){
                add_filter( 'wpsr_form_validation_' . $config[ 'form' ][ 'name' ], $config[ 'form' ][ 'validation' ] );
            }
            
        }
        
    }
    
    public static function get_pages(){
        return apply_filters( 'wpsr_mod_admin_pages', self::$pages );
    }
    
    public static function admin_page(){
        
        if( !current_user_can( 'manage_options' ) ){
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        }
        
        $pages = self::get_pages();
        self::$current_page = isset( $_GET['tab'] ) ? $_GET['tab'] : '';
        
        // Set default page
        if( empty( self::$current_page ) || !array_key_exists( self::$current_page, $pages ) ){
            self::$current_page = 'home';
        }
        
        echo '<div class="wrap page_' . self::$current_page . '">';
        
            self::admin_header();
            
            echo '<div id="content">';
                if(self::$current_page == 'home'){
                    
                    echo '<h1><i class="fa fa-star-of-life"></i>Features</h1>';
                    self::admin_pages_list();
                    
                    echo '<h1><i class="fa fa-wrench"></i>Settings</h1>';
                    self::admin_pages_list(false);
                    
                    self::intro_popups();
                    
                    self::admin_footer();
                    
                }else{
                    call_user_func( self::$pages[ self::$current_page ][ 'page_callback' ] );
                }
            echo '</div>';
            
        echo '</div>';
        
    }
    
    public static function admin_header(){
        
        echo '<header id="wpsr_header">';
        echo '<hgroup>';
        echo '<h1 class="wpsr_title">WP Socializer 
        <span class="title-count">' . WPSR_VERSION . '</span><a href="admin.php?page=wp_socializer" class="back_btn"><i class="fa fa-chevron-left"></i>
 Back</a></h1>';
        self::admin_links();
        echo '</hgroup>';
        echo '</header>';
        
    }
    
    public static function admin_pages_list($only_features=true){
        
        echo '<div class="admin_pages_list clearfix">';
        
        $pages = self::get_pages();
        
        foreach( $pages as $id => $config ){
            
            // Apply default config
            $config = WPSR_Lists::set_defaults( $config, array(
                'name' => '',
                'banner' => '',
                'page_callback' => '',
                'feature' => true,
                'link' => '',
                'form' => array(
                    'id' => '',
                    'name' => '',
                    'callback' => '',
                    'validation' => '',
                )
            ));
            
            $is_feature_page = $config['feature'];
            $is_feat_active = false;
            $has_feat_status = $is_feature_page && !empty($config['form']['callback']);
            $link = empty($config['link']) ? ('admin.php?page=wp_socializer&tab=' . $id) : $config['link'];
            
            if($has_feat_status){
                $feat_settings = get_option('wpsr_' . $config['form']['name'], array());
                $feat_settings = WPSR_Lists::set_defaults($feat_settings, WPSR_Lists::defaults($id));
                $is_feat_active = (isset($feat_settings['ft_status']) && $feat_settings['ft_status'] == 'enable') ? true : false;
            }
            
            if(($only_features && !$is_feature_page) || (!$only_features && $is_feature_page)){
                continue;
            }
            
            $card_class = array(
                'page_card',
                'card_' . $id,
                (!$only_features ? 'card_others' : ''),
                ($is_feat_active ? 'active' : '')
            );
            
            echo '<a class="' . implode(' ', $card_class) . '" href="' . $link . '" style="background-image: url(' . $config['banner'] . ')">';
            echo '<h3>' . $config[ 'name' ] . '</h3>';
            
            if($has_feat_status){
                echo '<div class="page_feat_status">' . ($is_feat_active ? '<i class="fa fa-check" aria-hidden="true"></i> Enabled' : 'Disabled') . '</div>';
            }else{
                echo '<div class="page_feat_status">Open</div>';
            }
            
            echo '</a>';
            
        }
        echo '</div>';
        
    }
    
    public static function settings_form( $id = '' ){
        
        if( empty( $id ) )
            return;
        
        $form_id = self::$pages[ $id ][ 'form' ][ 'id' ];
        $form_name = self::$pages[ $id ][ 'form' ][ 'name' ];
        $form_callback = self::$pages[ $id ][ 'form' ][ 'callback' ];
        
        $option = 'wpsr_' . $form_name;
        $nonce = 'wpsr_nonce_' . $form_name . '_submit';
        $form_fields = 'wpsr_form_' . $form_name;
        $validation_filter = 'wpsr_form_validation_' . $form_name;
        
        // Form post
        if( $_POST && check_admin_referer( $nonce ) ){
            
            $post = self::clean_post();
            $post_value = apply_filters( $validation_filter, $post );
            
            update_option( $option, $post_value );
            
            echo '<div class="notice notice-success inline is-dismissible"><p>' . __( 'Settings saved !', 'wpsr' ) . '</p></div>';
        }
        
        // Get saved details
        $saved_settings = get_option( $option );
        
        echo '<form method="post" id="' . $form_id . '" class="main_form">';
            
            // Execute all hooked form fields from services
            if( is_callable( $form_callback ) ){
                call_user_func( $form_callback, $saved_settings );
            }
            
            do_action( 'wpsr_form_' . $form_name, $saved_settings );
            
            wp_nonce_field( $nonce );
        
        echo '<div class="main_form_footer postbox"><input type="submit" value="Save Settings" class="button button-primary" /></div>';
        
        echo '</form>';
        
        self::intro_popups();
        
        self::admin_footer();
        
    }
    
    public static function enqueue_scripts( $hook ){
        
        if( self::$pagehook == $hook ){
            wp_enqueue_style( 'wpsr_css', WPSR_ADMIN_URL . 'css/style.css', array(), WPSR_VERSION );
            wp_enqueue_style( 'wp-color-picker' );
            wp_enqueue_style( 'wpsr_ipopup', WPSR_ADMIN_URL . 'css/ipopup.css', array(), WPSR_VERSION );
            wp_enqueue_style( 'wpsr_fa', WPSR_Lists::ext_res( 'font-awesome-adm' ), array(), WPSR_VERSION );
            
            wp_enqueue_script( 'jquery' );
            wp_enqueue_script( 'jquery-conditioner', WPSR_ADMIN_URL . 'js/jquery.conditioner.js', array( 'jquery' ) );
            wp_enqueue_script( 'wp-color-picker' );
            wp_enqueue_script( 'jquery-ui-sortable' );
            wp_enqueue_script( 'jquery-ui-draggable' );
            wp_enqueue_script( 'wpsr_ipopup', WPSR_ADMIN_URL . 'js/ipopup.js', array(), WPSR_VERSION );
            wp_enqueue_script( 'wpsr_js', WPSR_ADMIN_URL . 'js/script.js', array( 'jquery', 'jquery-ui-sortable', 'jquery-ui-draggable', 'jquery-conditioner', 'wp-color-picker', 'wpsr_ipopup' ), WPSR_VERSION );
            
        }
        
    }
    
    public static function inline_scripts(){
        
        $screen = get_current_screen();
        
        if( self::$pagehook == $screen->id ){
            
            $services = WPSR_Services::list_all();
            $loc_rules = WPSR_Location_Rules::rules_list();
            
            $js_texts = array(
                'sel_btn' => __( 'Please select a service to create button for !', 'wpsr' ),
                'del_btn' => __( 'Are you sure want to delete this button ?', 'wpsr' ),
                'close' => __( 'Close', 'wpsr' ),
                'fb_empty' => __( 'No buttons are added. Open the editor to add buttons.', 'wpsr' )
            );
            
            echo '<script>
            var wpsr = {
                ajaxurl: "' . get_admin_url() . 'admin-ajax.php",
                services: ' . wp_json_encode( $services ) . ',
                loc_rules: ' . wp_json_encode( $loc_rules ) . ',
                js_texts: ' . wp_json_encode( $js_texts ) . ',
                ext_res: ' . wp_json_encode( WPSR_Lists::ext_res() ) . ',
            };
            </script>';
            
            echo '<script>
            var wpsr_show = {
                changelog: "' . ( ( get_option( 'wpsr_last_changelog' ) != WPSR_VERSION ) ? WPSR_VERSION : 'false'  ) . '",
                setup: "' . ( ( get_option( 'wpsr_setup' ) != WPSR_SETUP_VERSION ) ? WPSR_SETUP_VERSION : 'false' ) . '"
            }
            </script>';
        }
        
    }
    
    public static function on_activate(){
        
        $prev_version = get_option( 'wpsr_version' );
        
        if( version_compare( WPSR_VERSION, $prev_version, '>' ) ){
            
            $buttons = WPSR_Buttons::list_all();
            
            foreach( $buttons as $id => $config ){
                $service = $config[ 'service' ];
                
                // Rename social_buttons service name to social_icons
                if( $service == 'social_buttons' ){
                    $buttons[ $id ][ 'service' ] = 'social_icons';
                    
                    unset( $buttons[ $id ][ 'settings' ][ 'sr-icon-color' ] );
                    unset( $buttons[ $id ][ 'settings' ][ 'sr-border-color' ] );
                    unset( $buttons[ $id ][ 'settings' ][ 'sr-background-color' ] );
                }
                
                // Delete stumbleupon service buttons
                if( $service == 'stumbleupon' ){
                    unset( $buttons[ $id ] );
                }
                
                // Add feature property to the button config for older settings
                if( !array_key_exists( 'feature', $config ) && array_key_exists( 'flags', $config ) ){
                    $flags = $config[ 'flags' ];
                    $feature = ( $flags == 1 ) ? 'buttons' : 'sharebar';
                    $buttons[ $id ][ 'feature' ] = $feature;
                    unset( $buttons[ $id ][ 'flags' ] );
                }
                
            }
            
            update_option( 'wpsr_buttons', $buttons );
            
        }
        
        if( WPSR_VERSION != $prev_version ){
            update_option( 'wpsr_version', WPSR_VERSION );
        }
        
    }
    
    public static function box_wrap( $type, $title = '' , $desc = '', $step = '', $class = '' ){
        
        $title = !empty( $step ) ? '<span class="step" data-step="' . $step . '">' . $title . '</span>' : $title;
        
        if( $type == 'open' ){
            echo '<h3 class="hndle">' . $title . '</h3>';
            echo '<div class="postbox ' . $class . '">';
            echo '<div class="inside">';
            if( !empty( $desc ) ) echo '<p>' . $desc . '</p>';
        }
        
        if( $type == 'close' ){
            echo '</div></div> <!-- postbox, inside -->';
        }

    }
    
    public static function build_table( $input, $title = '', $desc = '', $mini = false, $step = '', $class = '' ){
        
        //$input = array( array( 'Desc', 'field' ), array( 'Desc2', 'field2' ) );
        
        if( !is_array( $input ) )
            return '';
        
        if( !empty( $title ) && $mini == false ){
            WPSR_Admin::box_wrap( 'open', $title, $desc, $step, $class );
        }else if ( $mini == true ){
            echo !empty( $title ) || !empty( $desc ) ? '<div class="sec_title_wrap">' : '';
            echo !empty( $title ) ? '<h4>' . $title . '</h4>' : '';
            echo !empty( $desc ) ? '<p>' . $desc . '</p>' : '';
            echo !empty( $title ) || !empty( $desc ) ? '</div>' : '';
        }
        
        echo '<table class="form-table">';
        foreach( $input as $r ){
            echo '<tr ' . ( isset( $r[2] ) ? $r[2]  : '' ) . '>';
                echo '<th>' . $r[0] . '</th>';
                echo '<td>' . $r[1] . '</td>';
            echo '</tr>';
        }
        echo '</table>';
        
        if( !empty( $title ) && $mini == false ){
            echo '</div></div>';
        }
        
    }

    public static function field( $field_type, $params = array() ){
        
        $defaults = array(
        
            'text' => array(
                'type' => 'text',
                'value' => '',
                'default' => '',
                'id' => '',
                'class' => 'regular-text',
                'name' => '',
                'placeholder' => '',
                'required' => '',
                'helper' => '',
                'custom' => '',
                'tip' => '',
                'qtip' => ''
            ),
            
            'select' => array(
                'id' => '',
                'class' => '',
                'name' => '',
                'list' => array(),
                'value' => '',
                'default' => '',
                'helper' => '',
                'custom' => '',
                'tip' => '',
                'qtip' => ''
            ),
            
            'image_select' => array(
                'id' => '',
                'class' => '',
                'name' => '',
                'list' => array(),
                'value' => '',
                'default' => '',
                'helper' => '',
                'custom' => '',
                'tip' => '',
                'qtip' => ''
            ),
            
            'radio' => array(
                'id' => '',
                'class' => '',
                'name' => '',
                'list' => array(),
                'value' => '',
                'default' => '',
                'helper' => '',
                'custom' => '',
                'tip' => '',
                'qtip' => ''
            ),
            
            'checkbox' => array(
                'id' => '',
                'class' => '',
                'name' => '',
                'value' => '',
                'default' => '',
                'helper' => '',
                'custom' => '',
                'tip' => '',
                'qtip' => ''
            ),
            
            'checkboxes' => array(
                'id' => '',
                'class' => '',
                'name' => '',
                'list' => array(),
                'value' => array(),
                'default' => array(),
                'helper' => '',
                'custom' => '',
                'tip' => '',
                'qtip' => ''
            ),
            
            'textarea' => array(
                'type' => 'text',
                'value' => '',
                'name' => '',
                'default' => '',
                'id' => '',
                'class' => '',
                'placeholder' => '',
                'rows' => '',
                'cols' => '',
                'helper' => '',
                'custom' => '',
                'tip' => '',
                'qtip' => ''
            )
            
        );
        
        $params = WPSR_Lists::set_defaults( $params, $defaults[ $field_type ] );
        $field_html = '';
        
        $params = self::clean_attr( $params );
        extract( $params, EXTR_SKIP );
        
        $tip = !empty( $tip ) ? ' data-htip="' . esc_attr($tip) . '" ' : '';
        
        switch( $field_type ){
            case 'text':
                $field_html = "<input type='$type' class='$class' id='$id' name='$name' value='$value' placeholder='$placeholder' " . ( $required ? "required='$required'" : "" ) . "  $custom $tip />";
            break;
            
            case 'select':
                $field_html .= "<select name='$name' class='$class' id='$id' $custom $tip>";
                foreach( $list as $k => $v ){
                    $field_html .= "<option value='$k' " . selected( $value, $k, false ) . ">$v</option>";
                }
                $field_html .= "</select>";
            break;
            
            case 'image_select':
                $field_html .= "<select name='$name' class='$class img_select' id='$id' $custom $tip>";
                foreach( $list as $k => $v ){
                    $opt_name = ( count( $v ) >= 2 ) ? $v[0] : $v;
                    $field_html .= "<option value='$k' " . selected( $value, $k, false ) . ">$opt_name</option>";
                }
                $field_html .= "</select>";
                $field_html .= "<ul class='img_select_list clearfix'>";
                foreach( $list as $k => $v ){
                    $is_selected = ( $value == $k ) ? 'img_opt_selected' : '';
                    $img = 'default_image.png';
                    $opt_name = '';
                    if( count( $v ) >= 2 ){
                        $opt_name = $v[0];
                        $img = $v[1];
                    }else{
                        $opt_name = $v;
                    }
                    $img = ( substr( $img, 0, 4 ) !== 'http' ) ? ( WPSR_ADMIN_URL . 'images/select_images/' . $img ) : $img;
                    $width = ( is_array( $v ) && isset( $v[2] ) ) ? "style='width:" . $v[2] . "'" : "";
                    $field_html .= "<li data-value='$k' data-init='false' class='" . $is_selected . "' $width><img src='" . $img . "' /><span>" . $opt_name . "</span></li>";
                }
                $field_html .= "</ul>";
            break;
            
            case 'radio':
                foreach( $list as $k => $v ){
                    $field_html .= "<label class='lbl_margin' $custom $tip><input type='radio' name='$name' class='$class' value='$k' id='$id' " . checked( $value, $k, false ) . " />&nbsp;$v </label>";
                }
            break;
            
            case 'checkbox':
                $checked = ( $value == '1' ) ? 'checked="checked"' : '';
                $field_html .= "<input type='checkbox' name='$name' class='$class' value='1' id='$id' $checked $custom $tip />";
            break;
            
            case 'checkboxes':
                foreach( $list as $k => $v ){
                    $checked = ( in_array( $k,(array) $value ) ) ? 'checked="checked"' : '';
                    $field_html .= "<label $custom $tip><input type='checkbox' name='" . $name . "[]' class='$class' value='$k' id='$id' $checked />&nbsp;$v </label>&nbsp;&nbsp;";
                }
            break;
            
            case 'textarea':
                $field_html .= "<textarea id='$id' name='$name' class='$class' placeholder='$placeholder' rows='$rows' cols='$cols' $custom $tip>$value</textarea>";
            break;
        }
        
        if( !empty( $qtip ) )
            $field_html .= "<a href='$qtip' class='qtip_icon' title='" . __( 'Click to view help', 'wpsr' ) . "' target='_blank'><i class='fa fa-question-circle'></i></a>";
        
        if( !empty( $helper ) )
            $field_html .= "<p class='description'>$helper</p>";
        
        return $field_html;
        
    }
    
    public static function buttons_veditor( $name, $content, $multiple_rows = true, $preview = false ){
        
        echo '<div class="vedit_wrap">';
        echo '<div class="veditor">';
        
        $tmpl = base64_decode( $content );
        $tmpl_cnt = ( empty( $tmpl ) || !isset( $tmpl ) ) ? '{"1":{"properties":{},"buttons":{}}}' : $tmpl ;
        $tmpl_cnt_obj = json_decode( $tmpl_cnt );
        $services = WPSR_Services::list_all();
        $buttons = WPSR_Buttons::list_all();
        
        foreach( $tmpl_cnt_obj as $k => $o ){
            $buttons_row = array();
            foreach( $o->buttons as $bid ){ // Iterate through array of button objects
                $bkey = key((array)$bid); // Get the key of the button obj
                array_push( $buttons_row, $bkey );
            }
            WPSR_Buttons_Picker::print_veditor_buttons( $buttons_row );
        }

        echo '</div>';
        echo '<input type="hidden" class="veditor_content" name="' . $name . '" />';
        
        echo '<div class="vedit_menu">';
        
        echo '<button class="button button-primary vedit_add_button"><i class="fa fa-plus-circle"></i> Add sharing button</button>';
        
        if( $preview ){
            echo '<button class="button vedit_preview_btn" data-action="' . $preview . '" title="' . __( 'Click to show preview for the above template', 'wpsr' ) . '"  data-refresh="' . __( 'Refresh preview', 'wpsr' ) . '"><i class="fa fa-eye"></i> ' . __( 'Show preview', 'wpsr' ) . '</button>';
        }
        
        if( $multiple_rows ){
            echo '<button class="button vedit_add_row"><i class="fa fa-plus"></i> Add a new row</button>';
        }
        
        echo '</div>';
        
        
        if( $preview ){
            echo '<div class="veditor_preview">';
            echo '<iframe src="" width="100%" class="vedit_preview_iframe"></iframe>';
            echo '</div>';
        }
        
        echo '</div><!-- veditor_wrap -->';
        
        if( $multiple_rows ){
            echo '<div class="vedit_row_menu">
            <a class="vedit_delete_row" title="' . __( 'Delete row', 'wpsr' ) . '"><i class="fa fa-times"></i></a>
            </div>';
        }
        
    }
    
    public static function admin_ajax(){
        
        $get = self::clean_get();
        $do = $get[ 'do' ];
        
        if( $do == 'close_changelog' ){
            update_option( 'wpsr_last_changelog', WPSR_VERSION );
            echo 'done';
        }
        
        if( $do == 'close_setup' ){
            update_option( 'wpsr_setup', WPSR_SETUP_VERSION );
            echo 'done';
        }
        
        die( 0 );
        
    }
    
    public static function clean_post(){
        
        return stripslashes_deep( $_POST );
        
    }
    
    public static function clean_attr( $a ){
        
        foreach( $a as $k=>$v ){
            if( is_array( $v ) ){
                $a[ $k ] = self::clean_attr( $v );
            }else{
                
                if( in_array( $k, array( 'custom', 'tip', 'helper' ) ) )
                    continue;
                
                $a[ $k ] = esc_attr( $v );
            }
        }
        
        return $a;
    }
    
    public static function clean_get(){
        
        foreach( $_GET as $k=>$v ){
            $_GET[$k] = sanitize_text_field( $v );
        }

        return $_GET;
    }
    
    public static function action_links( $links ){
        array_unshift( $links, '<a href="'. esc_url( admin_url( 'admin.php?page=wp_socializer') ) .'">Settings</a>' );
        return $links;
    }
    
    public static function admin_notices(){
        
        $pages_display = array( 'plugins', 'update-core', 'dashboard' );
        
        if( in_array( get_current_screen()->id, $pages_display ) ){
            if( version_compare( WPSR_VERSION, get_option( 'wpsr_last_changelog' ), '>' ) ){
                echo '<div class="notice notice-success is-dismissible">
                    <p>' . sprintf( __( '<b>WP Socializer</b> is updated to latest version. Please visit the %ssettings%s page to see all the new features and the change log.', 'wpsr' ), '<a href="' . esc_url( admin_url( 'admin.php?page=wp_socializer') ) . '">', '</a>' ) .  '</p>
                </div>';
            }
        }
    }
    
    public static function admin_links(){
        echo '<div class="admin_links">
            <a href="https://www.paypal.me/vaakash/6" target="_blank"><i class="fa fa-coffee cdarkred"></i>Buy me a coffee !</a>
            <a href="https://wordpress.org/support/plugin/wp-socializer/reviews/?rate=5#new-post" target="_blank"><i class="fa fa-star corange"></i>Rate 5 stars</a>
            <a href="https://www.aakashweb.com/forum/discuss/wordpress-plugins/wp-socializer/#new-post" target="_blank"><i class="fa fa-bug cred"></i>Any issues, ideas ?</a>
            <a href="https://twitter.com/intent/tweet?hashtags=wordpress&ref_src=twsrc%5Etfw&related=vaakash&text=Check%20out%20WP%20Socializer%2C%20a%20powerful%20social%20media%20share%20icons%2C%20buttons%20plugin%20for%20WordPress&tw_p=tweetbutton&url=https%3A%2F%2Fwww.aakashweb.com%2Fwordpress-plugins%2Fwp-socializer%2F&via=vaakash" target="_blank"><i class="fab fa-twitter ctwitter"></i>Share this plugin</a>
        </div>';
    }
    
    public static function admin_footer(){
        
        echo '
        <div class="coffee_box">
        
        <div class="coffee_amt_wrap">
        <p><select class="coffee_amt">
            <option value="2">$2</option>
            <option value="3">$3</option>
            <option value="4">$4</option>
            <option value="5" selected="selected">$5</option>
            <option value="6">$6</option>
            <option value="7">$7</option>
            <option value="8">$8</option>
            <option value="9">$9</option>
            <option value="10">$10</option>
            <option value="11">$11</option>
            <option value="12">$12</option>
            <option value="">Custom</option>
        </select></p>
        <a class="button button-primary buy_coffee_btn" href="https://www.paypal.me/vaakash/5" data-link="https://www.paypal.me/vaakash/" target="_blank">Buy me a coffee !</a>
        </div>
        
        <h2>Buy me a coffee !</h2>
        <p>Thank you for using WP Socializer. If you found the plugin useful buy me a coffee ! Your donation will motivate and make me happy for all the efforts. You can donate via PayPal.</p>
        
        </div>
        ';
        
        echo '<div class="page_footer">
        <p><img src="' . WPSR_ADMIN_URL . '/images/icons/aakash-web.png" /> Created by <a href="https://goo.gl/aHKnsM" target="_blank">Aakash Chakravarthy</a> - Follow me on <a href="https://twitter.com/vaakash", target="_blank">Twitter</a>, <a href="https://fb.com/aakashweb" target="_blank">Facebook</a>, <a href="https://www.linkedin.com/in/vaakash/" target="_blank">LinkedIn</a>. Check out <a href="https://goo.gl/OAxx4l" target="_blank">my other works</a>.</p>
        </div>';
        
    }
    
    public static function intro_popups(){
        echo '<div class="welcome_wrap intro_popup style_ele">
        <section></section>
        <footer><button class="button button-primary close_changelog_btn">' . __( 'Start using WP Socializer', 'wpsr' ) . '</button></footer>
        </div>';
        
        echo '<div class="setup_intro intro_popup style_ele">
        <section>
            <h1 align="center"><i class="fa fa-magic"></i> Quick and easy setup wizard</h1>
            <p class="setup_hint">1 <i class="fa fa-caret-right"></i> 2 <i class="fa fa-caret-right"></i> <i class="fa fa-check-circle"></i></p>
            <p>WP Socializer has a setup wizard to quickly add social sharing buttons and icons.</p>
            <p>There are 50+ hand picked social icons and icons design template to choose from the setup.</p>
            <p>Open the setup wizard to begin with.</p>
        </section>
        <footer><a href="#" class="button close_setup_intro_btn">' . __( 'Skip setup', 'wpsr' ) . '</a> <a href="' . admin_url( 'admin.php?page=wp_socializer_setup' ) . '" class="button button-primary">' . __( 'Open setup wizard', 'wpsr' ) . '</a></footer>
        </div>';
        
    }
    
}

WPSR_Admin::init();

?>