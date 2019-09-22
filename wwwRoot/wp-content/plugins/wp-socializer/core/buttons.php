<?php
/**
  * Contains core functions for the working of the buttons
  * 
  */

class WPSR_Buttons{
    
    public static function init(){
        
        // Register all services ajax action
        add_action( 'wp_ajax_wpsr_service', array( __CLASS__, 'service_action' ) );
        
        // Register filter to get the button for given ID
        add_action( 'wpsr_print_button', array( __CLASS__, 'print_button' ), 10, 2 );
        
        // Add the common shortcode for the service
        add_shortcode( 'wpsr_button', array( __CLASS__, 'add_shortcode' ) );
        
        // Return empty value for v2.x shortcodes
        self::old_shortcodes();
        
    }
    
    public static function service_action(){
        
        $get = self::clean_get();
        $services = WPSR_Services::list_all();
        $service_id = $get[ 'service_id' ];
        $buttons = WPSR_Buttons::list_all();
        $do = $get[ 'do' ];

        if( !array_key_exists( $service_id, $services ) )
            die( 'Invalid WP Socializer service !' );
        
        switch( $do ){
            
            // Create a new button
            case 'new':
                
                // Generate a new button id
                $button_id = self::generate_button_id( $service_id );
                
                $buttons[ $button_id ] = array(
                    'service' => $service_id,
                    'feature' => $get[ 'feature' ],
                    'settings' => array()
                );

                update_option( 'wpsr_buttons', $buttons );
                
                // Create compiled array of details
                $temp = $buttons[ $button_id ];
                $temp[ 'id' ] = $button_id;
                $temp[ 'settings_size' ] = $button_id;
                $html = '';

                $service = $services[ $service_id ];
                $html .= '<li data-service="' . esc_attr( $service_id ) . '" data-id="' . esc_attr( $button_id ) . '" class="ui_btn">';
                $html .= '<span class="btn_icon"><img src="' . esc_attr( $service['icons'] ) . '" /></span>';
                $html .= '<span class="btn_name">' . $service['name'] . '</span>';
                $html .= '<i class="btn_action btn_delete fa fa-trash-alt" title="' . __( 'Delete button', 'wpsr' ) . '"></i>';
                $html .= '<i class="btn_action btn_edit fa fa-cog" title="' . __( 'Settings', 'wpsr') . '"></i>';
                $html .= '</li>';
                $temp[ 'html' ] = $html;
                
                header('Content-Type: application/json');
                echo json_encode( $temp );
                
            break;
            
            // Edit existing button
            case 'edit':
                
                // Get the button ID from request
                $button_id = $get[ 'button_id' ];
                
                // Update Section
                if( $_POST && check_admin_referer( 'wpsr_nonce_service_submit' ) ){
                    
                    $post = self::clean_post();
                    
                    $buttons[ $button_id ] = array(
                        'service' => $service_id,
                        'feature' => $post[ 'feature' ],
                        'settings' => apply_filters( 'wpsr_service_validation_' . $service_id, $post[ 'o' ] )
                    );
                    
                    update_option( 'wpsr_buttons', $buttons );
                    
                }
            
                // View Section
                if( !empty( $button_id ) && array_key_exists( $button_id, $buttons ) ){
                
                    $button = array(
                        'id' => $button_id,
                        'values' => $buttons[ $button_id ][ 'settings' ],
                        'feature' => $buttons[ $button_id ][ 'feature' ]
                    );
                    
                }else{
                
                    die( 'Invalid button ID !');
                    
                }

                // Display the service settings HTML
                self::settings_html( $service_id, $button );
                    
            break;
            
            // Delete button
            case 'delete':
                
                // Get the button ID from request
                $button_id = $get[ 'button_id' ];
                
                if( !empty( $button_id ) && array_key_exists( $button_id, $buttons ) ){
        
                    unset( $buttons [ $button_id ] );
                    
                    update_option( 'wpsr_buttons', $buttons );
                    
                    // Print the success character
                    echo '1';
                    
                }else{
                
                    die( 'Invalid button ID !');
                    
                }
            
            break;

        }

        die(0);
        
    }
    
    public static function settings_html( $service_id, $button ){
        
        if( empty( $button[ 'id' ] ) ){
            $save_button = __( 'Create button', 'wpsr' );
        }else{
            $save_button = __( 'Save button settings', 'wpsr' );
        }
        
        $form_action = get_admin_url() . "admin-ajax.php?action=wpsr_service&do=edit&service_id=$service_id&button_id=$button[id]&feature=$button[feature]";
        
        // Inject feature to view
        $button[ 'values' ][ '_feature' ] = $button[ 'feature' ];
        
        // Start HTML output for settings
        echo '<form method="post" action="' . $form_action . '" class="btn_settings_form">';
            
            // The title for every button
            echo '<input type="text" placeholder="' . __( 'Name the button for identification', 'wpsr' ) . '" title="' . __( 'Click to edit title', 'wpsr' ) . '" name="o[title]" class="tt_name" value="' . ( isset ( $button[ 'values' ][ 'title' ] ) ? esc_attr( $button[ 'values' ][ 'title' ] ) : '' ). '" />';
            
            WPSR_Services::settings( $service_id, $button[ 'values' ] );

            echo '<input type="hidden" name="button_id" value="' . $button[ 'id' ] . '" />';
            echo '<input type="hidden" name="feature" value="' . $button[ 'feature' ] . '" />';
            
            echo '<div class="btn_settings_footer">
                <input class="btn_shortcode" title="' . __( 'Copy shortcode', 'wpsr' ) . '" type="text" value=\'[wpsr_button id="' . $button[ 'id' ] . '"]\' />
                <span class="btn_settings_status">' . __( 'Button settings saved !', 'wpsr' ) . '</span>
                <input type="submit" name="submit" value="' . $save_button .'" class="button button-primary btn_settings_save" />
            </div>';
            
            wp_nonce_field( 'wpsr_nonce_service_submit' );
                
        echo '</form>';
    }
    
    public static function get_button( $id = '', $page_info = array() ){
        
        if( empty( $id ) )
            return '';
        
        $services = WPSR_Services::list_all();
        $buttons = WPSR_Buttons::list_all();
        
        if( !array_key_exists( $id, $buttons ) ){
            return '';
        }
        
        $button = $buttons[ $id ];
        $service_id = $button[ 'service' ];
        
        if( !array_key_exists( $service_id, $services ) ){
            return '';
        }
        
        $service = $services[ $service_id ];
        
        // Inject feature name for processing
        $button[ 'settings' ][ '_feature' ] = $button[ 'feature' ];
        
        // Get the page info
        $page_info = empty( $page_info ) ? WPSR_Metadata::metadata() : $page_info;
        
        // Call the service output function
        $out = WPSR_Services::output( $service_id, $button[ 'settings' ], $page_info );
        
        // Apply filters on the output includes and html
        $out = apply_filters( 'wpsr_mod_service_output', $out );
        
        // Add to active services list
        WPSR_Services::add_active_service( $service_id );
        
        // Add to includes as active
        WPSR_Includes::add_active_includes( $out[ 'includes' ] );
        
        return $out[ 'html' ];
        
    }
    
    public static function print_button( $id, $page_info = array() ){
        
        echo self::get_button( $id, $page_info );
        
    }
    
    public static function add_shortcode( $atts ){
        
        $att = shortcode_atts( array(
            'id' => ''
        ), $atts );

        if ( empty( $att[ 'id' ] ) ){
            return '';
        }

        return self::get_button( $att[ 'id' ], '' );
    }
    
    public static function list_all(){
        
        $buttons = get_option( 'wpsr_buttons', array() );
        
        if( !empty( self::$temp_buttons ) ){
            $buttons = self::get_temp_buttons();
        }
        
        return apply_filters( 'wpsr_mod_buttons_list', $buttons );
    }
    
    public static function generate_button_id( $service_id ){
        
        $digits = 4;
        $randId = rand( pow( 10, $digits-1 ), pow( 10, $digits ) - 1 );
        return $randId;
        
    }
    
    public static function clean_get(){

        foreach( $_GET as $k=>$v ){
            $_GET[$k] = sanitize_text_field( $v );
        }

        return $_GET;
    }
    
    public static function clean_post(){
        
        return stripslashes_deep( $_POST );
        
    }
    
    public static function old_shortcodes(){
        
        // Returning empty for the old v2.x shortcodes
        
        add_shortcode( 'wpsr_socialbts', array( __CLASS__, 'old_buttons' ) );
        add_shortcode( 'wpsr_addthis', array( __CLASS__, 'old_buttons' ) );
        add_shortcode( 'wpsr_sharethis', array( __CLASS__, 'old_buttons' ));
        add_shortcode( 'wpsr_retweet', array( __CLASS__, 'old_buttons' ) );
        add_shortcode( 'wpsr_buzz', array( __CLASS__, 'old_buttons' ) );
        add_shortcode( 'wpsr_plusone', array( __CLASS__, 'old_buttons' ) );
        add_shortcode( 'wpsr_digg', array( __CLASS__, 'old_buttons' ) );
        add_shortcode( 'wpsr_facebook', array( __CLASS__, 'old_buttons' ) );
        add_shortcode( 'wpsr_stumbleupon', array( __CLASS__, 'old_buttons' ) );
        add_shortcode( 'wpsr_reddit', array( __CLASS__, 'old_buttons' ) );
        add_shortcode( 'wpsr_linkedin', array( __CLASS__, 'old_buttons' ) );
        add_shortcode( 'wpsr_pinterest', array( __CLASS__, 'old_buttons' ) );
        add_shortcode( 'wpsr_commentsbt', array( __CLASS__, 'old_buttons' ) );
        
    }
    
    public static function old_buttons(){
        return '';
    }
    
    public static $temp_buttons = array();
    
    public static function get_temp_buttons(){
        
        $btns = self::$temp_buttons;
        
        if( is_array( $btns ) ){
            foreach( $btns as $id => $config ){
                
                if( !isset( $config[ 'feature' ] ) )
                    $btns[ $id ][ 'feature' ] = 'temp_feature';
                
                if( !isset( $config[ 'settings' ] ) )
                    $btns[ $id ][ 'settings' ] = array();
                
                if( !isset( $config[ 'service' ] ) )
                    $btns[ $id ][ 'service' ] = 'temp_service';
                
            }
        }
        
        return $btns;
        
    }
    
}

WPSR_Buttons::init();

?>