<?php
/**
  * Twitter service for WP Socializer
  *
  */

class wpsr_service_twitter{
    
    function __construct(){
        
        add_filter( 'wpsr_register_service', array( $this, 'register' ) );
        
        $gs = get_option( 'wpsr_general_settings' );
        $gs = WPSR_Lists::set_defaults( $gs, WPSR_Lists::defaults( 'gsettings_twitter' ) );
        
        $this->default_values = array(
            'type' => 'share',
            'count' => 'false',
            'username' => $gs[ 'twitter_username' ],
            'recommend' => '',
            'hashtag' => '',
            'size' => 'small'
        );
        
    }
    
    function register( $services ){
        
        $services[ 'twitter' ] = array(
            'name' => 'Twitter',
            'icons' => WPSR_ADMIN_URL . '/images/icons/twitter.png',
            'desc' => __( 'Create Twitter buttons like share, follow, hashtag and mention', 'wpsr' ),
            'settings' => array( 'size' => '500x350' ),
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
        $href = '';
        $count = '';
        $text = '';
        
        if( $settings[ 'type' ] == 'share' ){
            $href = 'https://twitter.com/share';
            $text = 'Tweet';
        }
        
        if( $settings[ 'type' ] == 'follow' ){
            $href = 'https://twitter.com/' . $settings[ 'username' ];
            $text = ( $settings[ 'username' ] != '' ) ? 'Follow @' . $settings[ 'username' ] : 'Follow';
        }
        
        if( $settings[ 'type' ] == 'hashtag' ){
            $href = 'https://twitter.com/intent/tweet?button_hashtag=' . $settings[ 'hashtag' ];
            $text = ( $settings[ 'hashtag' ] != '' ) ? 'Tweet #' . $settings[ 'hashtag' ] : 'Tweet';
        }
        
        if( $settings[ 'type' ] == 'mention' ){
            $href = 'https://twitter.com/intent/tweet?screen_name=' . $settings[ 'username' ];
            $text = ( $settings[ 'username' ] != '' ) ? 'Tweet to @' . $settings[ 'username' ] : 'Tweet';
        }       
        
        $out['html'] = '<a href="' . esc_attr( $href ) . '" class="twitter-' . $settings[ 'type' ] . '-button" data-show-count="' . esc_attr( $settings[ 'count' ] ) . '" data-related="' . esc_attr( $settings[ 'recommend' ] ) . '" data-url="' . esc_attr( $page_info[ 'url' ] ) . '" data-size="' . esc_attr( $settings[ 'size' ] ) . '" data-via="' . esc_attr( $settings[ 'username' ] ) . '" data-hashtags="' . esc_attr( $settings[ 'hashtag' ] ) . '">' . $text . '</a>';

        $out['includes'] = array( 'twitter_main_js' );
        return $out;
        
    }
    
    function includes(){
        
        $includes = array(
            'twitter_main_js' => array(
                'type' => 'js',
                'code' => "<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>"
            )
        );
        
        return $includes;
        
    }

    function settings( $values ){
        
        $values = WPSR_Lists::set_defaults( $values, $this->default_values );
        
        $section1 = array(
            array( __( 'Button type', 'wpsr' ), WPSR_Admin::field( 'select', array(
                'name' => 'o[type]',
                'value' => $values['type'],
                'list' => array(
                    'share' => 'Share button',
                    'follow' => 'Follow button',
                    'hashtag' => 'Hashtag button',
                    'mention' => 'Mention button'
                )
            ))),
            
            /*
            array( 'Show count', WPSR_Admin::field( 'select', array(
                'name' => 'o[count]',
                'value' => $values['count'],
                'list' => array(
                    'true' => 'Yes',
                    'false' => 'No'
                )
            ))),
            */
            
            array( __( 'Twitter username', 'wpsr' ), WPSR_Admin::field( 'text', array(
                'name' => 'o[username]',
                'value' => $values['username'],
                'helper' => __( 'Your twitter username without @ sign', 'wpsr' )
            ))),
            
            array( 'Recommend', WPSR_Admin::field( 'text', array(
                'name' => 'o[recommend]',
                'value' => $values['recommend'],
                'helper' => __( 'Enter other twitter accounts to recommend without @ sign separated by comma.', 'wpsr' )
            ))),
            
            array( 'Hashtag in tweet', WPSR_Admin::field( 'text', array(
                'name' => 'o[hashtag]',
                'value' => $values['hashtag'],
                'helper' => __( 'Enter hashtag to place in tweet without # sign.', 'wpsr' )
            ))),
            
            array( __( 'Button size', 'wpsr' ), WPSR_Admin::field( 'select', array(
                'name' => 'o[size]',
                'value' => $values['size'],
                'list' => array( 'small' => 'Normal', 'large' => 'Large')
            ))),
            
        );
        
        WPSR_Admin::build_table( $section1, '', '', true);
        
        echo '<script>if( jQuery.fn.conditioner ) jQuery("[data-conditioner]").conditioner();</script>';
        
    }

    function validation( $values ){
        
        $values['color'] .= 'formatted';
        return $values;
        
    }
    
    function general_settings( $values ){
        
        $values = WPSR_Lists::set_defaults( $values, WPSR_Lists::defaults( 'gsettings_twitter' ) );
        
        $section1 = array(
            array( __( 'Twitter username', 'wpsr' ), WPSR_Admin::field( 'text', array(
                'name' => 'twitter_username',
                'value' => $values['twitter_username'], 
                'helper' => __( 'Your twitter username without @ sign', 'wpsr' ),
            ))),
        );

        WPSR_Admin::build_table( $section1, 'Twitter settings');
    
    }
    
    function general_settings_validation( $values ){
        return $values;
    }
    
    function templates(){
        
        return array(
            'twitter_hl'=> array(
                'settings'=> array()
            ),
            'twitter_hl_lg'=> array(
                'settings'=> array(
                    'size'=> 'large'
                )
            )
        );
        
    }
    
}

new wpsr_service_twitter();

/**
 * Twitter widget
 */

class wpsr_widget_twitter{
    
    function __construct(){
        
        WPSR_Widgets::register( 'twitter', array(
            'name' => 'Twitter timeline widget',
            'banner' => WPSR_ADMIN_URL . '/images/widgets/twitter.png',
            'description' => __( 'The native Twitter timeline widget to display Twitter feed and other Twitter features.', 'wpsr' ),
            'callbacks' => array(
                'widget' => array( $this, 'widget' ),
                'form' => array( $this, 'form' ),
                'update' => array( $this, 'update' )
            )
        ));
        
        $this->defaults = array(
            'twitter_widget_url' => '',
            'twitter_widget_height' => '600',
            'twitter_widget_theme' => 'light',
            'twitter_widget_link_color' => '#2B7BB9',
        );
        
    }
    
    function widget( $args, $instance ){
        
        $instance = WPSR_Lists::set_defaults( $instance, $this->defaults );
        
        echo '<a class="twitter-timeline" data-height="' . $instance[ 'twitter_widget_height' ] . '" data-theme="' . $instance[ 'twitter_widget_theme' ] . '" data-link-color="' . $instance[ 'twitter_widget_link_color' ] . '" href="' . $instance[ 'twitter_widget_url' ] . '">Twitter</a>';
        
        WPSR_Includes::add_active_includes( array( 'twitter_main_js' ) );
        
    }
    
    function form( $obj, $instance ){
        
        $instance = WPSR_Lists::set_defaults( $instance, $this->defaults );
        $fields = new WPSR_Widget_Form_Fields( $obj, $instance );
        
        echo '<h4>' . __( 'Twitter Widget settings', 'wpsr' ) . '</h4>';
        $fields->text( 'twitter_widget_url', 'Enter a twitter URL to embed', array( 'placeholder' => 'Ex: https://twitter.com/vaakash' ) );
        
        echo '<h5>Examples:</h5>';
        echo '<ul>';
        echo '<li><code>Likes</code> - https://twitter.com/TwitterDev/likes</li>';
        echo '<li><code>List</code> - https://twitter.com/TwitterDev/lists/national-parks</li>';
        echo '<li><code>Profile</code> - https://twitter.com/TwitterDev</li>';
        echo '<li><code>Collection</code> - https://twitter.com/TwitterDev/timelines/539487832448843776</li>';
        echo '</ul>';
        
        $fields->number( 'twitter_widget_height', 'Widget height ( in pixels )' );
        $fields->select( 'twitter_widget_theme', 'Widget theme', array( 'light' => 'Light', 'dark' => 'Dark' ), array( 'class' => 'smallfat' ) );
        $fields->text( 'twitter_widget_link_color', 'Link color', array( 'class' => 'smallfat wpsr-color-picker' ) );
        
    }
    
    function update( $instance ){
        return $instance;
    }
    
}

new wpsr_widget_twitter();

?>