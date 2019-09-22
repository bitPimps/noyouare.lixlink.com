<?php
/**
 * Facebook service for WP Socializer
 *
 */

class wpsr_service_facebook{
    
    function __construct(){
        
        add_filter( 'wpsr_register_service', array( $this, 'register' ) );
        
        $this->default_values = array(
            'type' => 'like',
            'like_type' => 'box_count',
            'like_share_btn' => 'false',
            'like_size' => 'small',
            'like_width' => '',
            'like_action' => 'like',
            'like_faces' => 'false',
            'share_type' => 'box_count'
        );
        
    }
    
    function register( $services ){
        
        $services[ 'facebook' ] = array(
            'name' => 'Facebook',
            'icons' => WPSR_ADMIN_URL . '/images/icons/facebook.png',
            'desc' => __( 'Create Facebook like, share and send buttons', 'wpsr' ),
            'settings' => array( 'size' => '500x450' ),
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
        $html = '';
        $settings = WPSR_Lists::set_defaults( $settings, $this->default_values );
        
        if( $settings[ 'type' ] == 'like' ){
            $html = '<div class="fb-like" data-href="' . esc_attr( $page_info[ 'url' ] ) . '" data-width="' . esc_attr( $settings[ 'like_width' ] ) . '" data-layout="' . esc_attr( $settings[ 'like_type' ] ) . '" data-action="' . esc_attr( $settings[ 'like_action' ] ) . '" data-show-faces="' . esc_attr( $settings[ 'like_faces' ] ) . '" data-share="' . esc_attr( $settings[ 'like_share_btn' ] ) . '" data-size="' . esc_attr( $settings[ 'like_size' ] ) . '"></div>';
        }
        
        if( $settings[ 'type' ] == 'share' ){
            $html = '<div class="fb-share-button" data-href="' . esc_attr( $page_info[ 'url' ] ) . '" data-layout="' . esc_attr( $settings[ 'share_type' ] ) . '"></div>';
        }
        
        if( $settings[ 'type' ] == 'send' ){
            $html = '<div class="fb-send" data-href="' . esc_attr( $page_info[ 'url' ] ) . '"></div>';
        }
        
        $out['html'] = $html;

        $out['includes'] = array( 'facebook_main_js' );
        return $out;
        
    }
    
    function includes(){
        
        $gs = WPSR_Lists::set_defaults( get_option( 'wpsr_general_settings' ), WPSR_Lists::defaults( 'gsettings_facebook' ) );
        $lang_code = $gs[ 'facebook_lang' ];
        
        $includes = array(
            'facebook_main_js' => array(
                'type' => 'js',
                'code' => '<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/' . $lang_code . '/sdk.js#xfbml=1&version=v2.8";
  fjs.parentNode.insertBefore(js, fjs);
}(document, "script", "facebook-jssdk"));</script>'
            )
        );
        
        return $includes;
        
    }

    function settings( $values ){
        
        $values = WPSR_Lists::set_defaults( $values, $this->default_values );
        
        $yes_no = array(
            'true' => __( 'Yes', 'wpsr' ),
            'false' => __( 'No', 'wpsr' )
        );
        
        $section1 = array(
            array( __( 'Button type', 'wpsr' ), WPSR_Admin::field( 'select', array(
                'name' => 'o[type]',
                'value' => $values['type'],
                'class' => 'fb_btn_type',
                'list' => array(
                    'like' => __( 'Like button', 'wpsr' ),
                    'share' => __( 'Share button', 'wpsr' ),
                    'send' => __( 'Send button', 'wpsr' )
                )
            ))),
        );

        WPSR_Admin::build_table( $section1, '', '', true);
        
        $section_like = array(
            
            array( __( 'Like button type', 'wpsr' ), WPSR_Admin::field( 'select', array(
                'name' => 'o[like_type]',
                'value' => $values['like_type'],
                'list' => array(
                    'box_count' => 'Box count',
                    'button_count' => 'Button count',
                    'button' => 'Button',
                    'standard' => 'Standard',
                ),
                'tip' => WPSR_ADMIN_URL . '/images/tips/fb-types.png'
            ))),
            
            array( __( 'Show share button', 'wpsr' ), WPSR_Admin::field( 'radio', array(
                'name' => 'o[like_share_btn]',
                'value' => $values['like_share_btn'], 
                'list' => $yes_no,
            ))),
            
            array( __( 'Button size', 'wpsr' ), WPSR_Admin::field( 'select', array(
                'name' => 'o[like_size]',
                'value' => $values['like_size'],
                'list' => array( 'small' => 'Normal', 'large' => 'Large')
            ))),
            
            array( __( 'Width of the button', 'wpsr' ), WPSR_Admin::field( 'text', array(
                'type' => 'number',
                'name' => 'o[like_width]',
                'value' => $values['like_width'],
                'placeholder' => __( 'Width in pixels', 'wpsr' )
            ))),
            
            array( __( 'Action type', 'wpsr' ), WPSR_Admin::field( 'select', array(
                'name' => 'o[like_action]',
                'value' => $values['like_action'], 
                'list' => array( 'like' => 'Like', 'recommend' => 'Recommend'),
            ))),
            
            array( __( 'Show Friends` faces', 'wpsr' ), WPSR_Admin::field( 'radio', array(
                'name' => 'o[like_faces]',
                'value' => $values['like_faces'], 
                'list' => $yes_no,
            ))),
            
        );
        
        echo '<div data-conditioner data-condr-input=".fb_btn_type" data-condr-value="like" data-condr-action="simple?show:hide" data-condr-events="click">';
        echo '<h4>' . __( 'Like button settings', 'wpsr' ) . '</h4>';
        WPSR_Admin::build_table( $section_like, '', '', true);
        echo '</div>';
        
        
        $section_share = array(
            array( __( 'Share button type', 'wpsr' ), WPSR_Admin::field( 'select', array(
                'name' => 'o[share_type]',
                'value' => $values['share_type'],
                'list' => array(
                    'box_count' => 'Box count',
                    'button_count' => 'Button count',
                    'button' => 'Button'
                )
            ))),
        );
        
        echo '<div data-conditioner data-condr-input=".fb_btn_type" data-condr-value="share" data-condr-action="simple?show:hide" data-condr-events="click">';
        echo '<h4>' . __( 'Share button settings', 'wpsr' ) . '</h4>';
        WPSR_Admin::build_table( $section_share, '', '', true);
        echo '</div>';
        
        echo '<script>if( jQuery.fn.conditioner ) jQuery("[data-conditioner]").conditioner();</script>';
    }

    function validation( $values ){
        
        return $values;
        
    }
    
    function general_settings( $values ){
        
        $values = WPSR_Lists::set_defaults( $values, WPSR_Lists::defaults( 'gsettings_facebook' ) );
        
        $section1 = array(
        
            array( __( 'Facebook App ID', 'wpsr' ), WPSR_Admin::field( 'text', array(
                'name' => 'facebook_app_id',
                'value' => $values['facebook_app_id'],
            ))),
            
            array( __( 'Facebook App secret', 'wpsr' ), WPSR_Admin::field( 'text', array(
                'name' => 'facebook_app_secret',
                'value' => $values['facebook_app_secret'],
                'helper' => 'You can find your facebook app ID and secret in <a href="https://developers.facebook.com/apps/" target="_blank">this page</a> under your app --> Settings --> Basic. Please create a new facebook app if there are no apps. This is used to retrieve facebook share/like count on the URLs.'
            ))),
            
            array( __( 'Facebook SDK language', 'wpsr' ), WPSR_Admin::field( 'select', array(
                'name' => 'facebook_lang',
                'value' => $values['facebook_lang'], 
                'list' => WPSR_Lists::lang_codes( 'facebook' )
            ))),
        );

        WPSR_Admin::build_table( $section1, 'Facebook settings');
    
    }
    
    function general_settings_validation( $values ){
        return $values;
    }
    
    function templates(){
        
        return array(
            'fb_hl'=> array(
                'desc' => 'Small facebook like button with counter',
                'settings'=> array(
                    'like_type'=> 'button_count'
                )
            ),
            'fb_vl'=> array(
                'desc' => 'Tall facebook like button with counter',
                'settings'=> array(
                    'like_type'=> 'box_count'
                )
            ),
            'fb_hl_lg'=> array(
                'desc' => 'Large facebook like button with counter',
                'settings'=> array(
                    'like_type'=> 'button_count',
                    'like_size'=> 'large'
                )
            ),
            'fb_hl_nc'=> array(
                'desc' => 'Small facebook like button without counter',
                'settings'=> array(
                    'like_type'=> 'button'
                )
            )
        );
        
    }
    
}

new wpsr_service_facebook();

/**
 * Facebook widget
 */

class wpsr_widget_facebook{
    
    function __construct(){
        
        WPSR_Widgets::register( 'facebook', array(
            'name' => 'Facebook & messenger widget',
            'banner' => WPSR_ADMIN_URL . '/images/widgets/facebook.png',
            'description' => __( 'The native Facebook widget to promote a Facebook page', 'wpsr' ),
            'callbacks' => array(
                'widget' => array( $this, 'widget' ),
                'form' => array( $this, 'form' ),
                'update' => array( $this, 'update' )
            )
        ));
        
        $this->defaults = array(
            'fb_page_url' => '',
            'fb_page_tabs' => 'timeline',
            'fb_page_width' => '400',
            'fb_page_height' => '400',
            'fb_page_small_header' => 'false',
            'fb_page_hide_cover' => 'false',
            'fb_page_show_faces' => 'true',
        );
        
    }
    
    function widget( $args, $instance ){
        
        $instance = WPSR_Lists::set_defaults( $instance, $this->defaults );
        
        echo '<div class="fb-page" data-href="' . $instance[ 'fb_page_url' ] . '"
                data-tabs="' . $instance[ 'fb_page_tabs' ] . '"
                data-width="' . $instance[ 'fb_page_width' ] . '" 
                data-height="' . $instance[ 'fb_page_height' ] . '" 
                data-hide-cover="' . $instance[ 'fb_page_hide_cover' ] . '"
                data-show-facepile="' . $instance[ 'fb_page_show_faces' ] . '"
                data-small-header="' . $instance[ 'fb_page_small_header' ] . '"></div>';
        
        WPSR_Includes::add_active_includes( array( 'facebook_main_js' ) );
        
    }
    
    function form( $obj, $instance ){
        
        $instance = WPSR_Lists::set_defaults( $instance, $this->defaults );
        $fields = new WPSR_Widget_Form_Fields( $obj, $instance );
        
        $yesno = array(
            'true' => __( 'Yes', 'wpsr' ),
            'false' => __( 'No', 'wpsr' )
        );
        
        echo '<h4>' . __( 'Facebook Widget settings', 'wpsr' ) . '</h4>';
        $fields->text( 'fb_page_url', 'Facebook page URL', array( 'placeholder' => 'Ex: https://facebook.com/facebook' ) );
        $fields->text( 'fb_page_tabs', 'Tabs to display ( Enter tab names separated by comma. Example: timeline,events,messages )' );
        $fields->number( 'fb_page_width', 'Widget width ( in pixels )' );
        $fields->number( 'fb_page_height', 'Widget height ( in pixels )' );
        
        $fields->select( 'fb_page_small_header', 'Display small header', $yesno, array( 'class' => 'smallfat' ) );
        $fields->select( 'fb_page_hide_cover', 'Hide cover photo', $yesno, array( 'class' => 'smallfat' ) );
        $fields->select( 'fb_page_show_faces', 'Show friend\'s faces', $yesno, array( 'class' => 'smallfat' ) );
        
    }
    
    function update( $instance ){
        return $instance;
    }
    
}

new wpsr_widget_facebook();

?>