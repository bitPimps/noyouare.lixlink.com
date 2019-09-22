<?php
/**
 * Widget class for WP Socializer
 * 
 */

class WPSR_Widgets{
    
    private static $widgets = array();
    
    public static function init(){
        
        add_action( 'admin_enqueue_scripts', array( __class__, 'print_widget_scripts' ) );
        
    }
    
    public static function register( $id, $details ){
        
        $defaults = array(
            'name' => '',
            'callbacks' => array(
                'widget' => '',
                'form' => '',
                'update' => ''
            )
        );
        
        $details[ 'callbacks' ] = WPSR_Lists::set_defaults( $details[ 'callbacks' ], $defaults[ 'callbacks' ] );
        $details = WPSR_Lists::set_defaults( $details, $defaults );
        
        self::$widgets[ $id ] = $details;
        
    }
    
    public static function widget( $id, $args, $instance ){
        
        if( !self::check_callable( $id, 'widget' ) ){
            return '';
        }
        
        return call_user_func( self::$widgets[ $id ][ 'callbacks' ][ 'widget' ], $args, $instance );
        
    }
    
    public static function form( $id, $widget_obj, $instance ){
        
        if( !self::check_callable( $id, 'form' ) ){
            return '';
        }
        
        call_user_func( self::$widgets[ $id ][ 'callbacks' ][ 'form' ], $widget_obj, $instance );
        
    }
    
    public static function update( $id, $instance ){
        
        if( !self::check_callable( $id, 'update' ) ){
            return $instance;
        }
        
        return call_user_func( self::$widgets[ $id ][ 'callbacks' ][ 'update' ], $instance );
        
    }
    
    public static function check_callable( $id, $callback ){
        
        $widgets = self::$widgets;
        
        if( array_key_exists( $id, $widgets ) ){
            $widget = $widgets[ $id ];
            if( array_key_exists( $callback, $widget[ 'callbacks' ] ) && !empty( $widget[ 'callbacks' ][ $callback ] ) && is_callable( $widget[ 'callbacks' ][ $callback ] ) ){
                return 1;
            }
        }
        
        return 0;
        
    }
    
    public static function list_all(){
        
        $widgets_temp = self::$widgets;
        
        foreach( $widgets_temp as $id => $config ){
            unset( $widgets_temp[ $id ][ 'callbacks' ] );
        }
        
        return apply_filters( 'wpsr_mod_widgets_list', $widgets_temp );
        
    }
    
    public static function print_widget_scripts( $hook ){
        if( $hook == 'widgets.php' ){
            
            echo '<script>window.wpsr_ppe_ajax = "' . esc_attr( get_admin_url() . 'admin-ajax.php' ) . '"; </script>';
            
            wp_enqueue_style( 'wpsr_admin_widget_css', WPSR_ADMIN_URL . 'css/style_widgets.css' );
            wp_enqueue_script( 'wpsr_admin_widget_js', WPSR_ADMIN_URL . 'js/script_widgets.js', array( 'jquery' ) );
            wp_enqueue_script( 'wp-color-picker' );
            wp_enqueue_style( 'wp-color-picker' );
            wp_enqueue_style( 'wpsr_fa', WPSR_Lists::ext_res( 'font-awesome-adm' ) );
            
            wp_enqueue_style( 'wpsr_ipopup', WPSR_ADMIN_URL . 'css/ipopup.css' );
            wp_enqueue_script( 'wpsr_ipopup', WPSR_ADMIN_URL . 'js/ipopup.js' );
        }
    }
    
}

WPSR_Widgets::init();

/**
 * Widget form API
 */
 
class WPSR_Widget_Form_Fields{
    
    function __construct( $widget_obj, $instance ){
        $this->obj = $widget_obj;
        $this->instance = $instance;
    }
    
    function text( $id, $name, $opts = array() ){
        
        $opts = WPSR_Lists::set_defaults( $opts, array(
            'class' => 'widefat',
            'helper' => '',
            'placeholder' => '',
            'custom' => ''
        ));
        
        echo '<p>';
        echo '<label for="' . esc_attr( $this->obj->get_field_id( $id ) ) . '">' . $name . '</label>';
        echo WPSR_Admin::field( 'text', array(
            'name' => $this->obj->get_field_name( $id ),
            'id' => $this->obj->get_field_id( $id ),
            'value' => $this->instance[ $id ],
            'class' => $opts[ 'class' ],
            'helper' => $opts[ 'helper' ],
            'placeholder' => $opts[ 'placeholder' ],
            'custom' => $opts[ 'custom' ]
        ));
        echo '</p>';
    }
    
    function select( $id, $name, $list, $opts = array() ){
        
        $opts = WPSR_Lists::set_defaults( $opts, array(
            'class' => 'widefat',
            'helper' => '',
            'placeholder' => '',
            'custom' => ''
        ));
        
        echo '<p>';
        echo '<label for="' . esc_attr( $this->obj->get_field_id( $id ) ) . '">' . $name . '</label>';
        echo WPSR_Admin::field( 'select', array(
            'name' => $this->obj->get_field_name( $id ),
            'id' => $this->obj->get_field_id( $id ),
            'value' => $this->instance[ $id ],
            'list' => $list,
            'class' => $opts[ 'class' ],
            'helper' => $opts[ 'helper' ],
            'placeholder' => $opts[ 'placeholder' ],
            'custom' => $opts[ 'custom' ]
        ));
        echo '</p>';
    }
    
    function number( $id, $name, $opts = array() ){
        
        $opts = WPSR_Lists::set_defaults( $opts, array(
            'class' => 'smallfat',
            'helper' => '',
            'placeholder' => '',
            'custom' => ''
        ));
        
        echo '<p>';
        echo '<label for="' . esc_attr( $this->obj->get_field_id( $id ) ) . '">' . $name . '</label>';
        echo WPSR_Admin::field( 'text', array(
            'name' => $this->obj->get_field_name( $id ),
            'id' => $this->obj->get_field_id( $id ),
            'value' => $this->instance[ $id ],
            'class' => $opts[ 'class' ],
            'helper' => $opts[ 'helper' ],
            'placeholder' => $opts[ 'placeholder' ],
            'type' => 'number',
            'custom' => $opts[ 'custom' ]
        ));
        echo '</p>';
    }
    
}

/**
 * Main WP Socializer widget
 *
 */
  
class WPSR_Main_Widget extends WP_Widget{
    
    function __construct(){
        parent::__construct(
            'wpsr_main_widget',
            'WP Socializer',
            array( 'description' => esc_html__( 'Use this widget to add Facebook widget, Twitter widget, follow me icons, Google Plus widget &amp; social media buttons', 'wpsr' ), ),
            array('width' => 500, 'height' => 500)
        );
    }
    
    public function widget( $args, $instance ){
        
        echo $args[ 'before_widget' ];
        if ( !empty( $instance[ 'title' ] ) ) {
            echo $args[ 'before_title' ] . apply_filters( 'widget_title', $instance[ 'title' ] ) . $args[ 'after_title' ];
        }
        
        $widgets = WPSR_Widgets::list_all();
        $selected_widget = $instance[ 'widget' ];
        
        if( array_key_exists( $selected_widget, $widgets ) ){
            WPSR_Widgets::widget( $selected_widget, $args, $instance );
        }
        
        echo $args['after_widget'];
    }
    
    public function form( $instance ){
        
        $widgets = WPSR_Widgets::list_all();
        $instance = WPSR_Lists::set_defaults( $instance, array(
            'title' => '',
            'widget' => ''
        ));
        
        $selected_widget = $instance[ 'widget' ];
        $fields = new WPSR_Widget_Form_Fields( $this, $instance );
        
        $widgets_list = array( '' => 'Select a widget to display' );
        foreach( $widgets as $w_id => $w_info ){
            $widgets_list[ $w_id ] = $w_info[ 'name' ];
        }
        
        $fields->text( 'title', 'Title' );
        $fields->select( 'widget', 'Widget', $widgets_list, array( 'class' => 'widefat wpsr_widget_selector' ) );
        
        if( array_key_exists( $selected_widget, $widgets ) ){
            echo '<div class="wpsr_widget_wrap">';
            WPSR_Widgets::form( $selected_widget, $this, $instance );
            echo '</div>';
        }else{
            echo '<div class="notice notice-error inline"><p>' . __( 'Please select a widget and click "Save" to load settings', 'wpsr' ) . '</p></div>';
        }
        
    }
    
    public function update( $new_instance, $old_instance ){
        
        $widgets = WPSR_Widgets::list_all();
        $selected_widget = $new_instance[ 'widget' ];
        
        if( array_key_exists( $selected_widget, $widgets ) ){
            return WPSR_Widgets::update( $selected_widget, $new_instance );
        }else{
            return $new_instance;
        }
    }
    
}

function wpsr_register_main_widget(){
    register_widget( 'WPSR_Main_Widget' );
}
add_action( 'widgets_init', 'wpsr_register_main_widget' );

/**
 * All social media buttons widget
 */

class wpsr_widget_buttons{
    
    function __construct(){
        
        WPSR_Widgets::register( 'buttons_widget', array(
            'name' => 'All social media buttons widget',
            'banner' => WPSR_ADMIN_URL . '/images/widgets/share-buttons.png',
            'description' => __( 'A widget to insert all kinds of native social share buttons to the sidebar.', 'wpsr' ),
            'callbacks' => array(
                'widget' => array( $this, 'widget' ),
                'form' => array( $this, 'form' ),
                'update' => array( $this, 'update' )
            )
        ));
        
        $this->defaults = array(
            'buttons_template' => ''
        );
        
        // Custom widget template widget
        add_action( 'wp_ajax_wpsr_widget_buttons', array( $this, 'buttons_editor' ) );
        
    }
    
    function buttons_editor(){
        
        global $hook_suffix;
        $hook_suffix = WPSR_Admin::$pagehook;
        set_current_screen( $hook_suffix );
        
        iframe_header( 'WP Socializer Widget template editor' );
        
        if( !isset( $_GET[ 'template' ] ) || !isset( $_GET[ 'cnt_id' ] ) || !isset( $_GET[ 'prev_id' ] ) ){
            echo '<p align="center">Incomplete info to load editor !</p></body></html>';
            die( 0 );
        }
        
        $feature = array(
            'name' => 'widgets',
            'hide_services' => array()
        );
        
        WPSR_Buttons_Picker::print_buttons_picker($feature);
        
        echo '<div id="wpsr_pp_editor">';
        echo '<h3>WP Socializer - widget template editor</h3>';
        
        WPSR_Admin::box_wrap( 'open', __( 'Add buttons to the template', 'wpsr' ), __( 'Add buttons to the template using the add sharing button below. Click "+" to add a new row. Click and drag row to rearrange the order.', 'wpsr' ), '1' );
        WPSR_Admin::buttons_veditor( 'widget_buttons_template', esc_attr( $_GET[ 'template' ] ), true, 'wpsr_preview_template_buttons' );
        WPSR_Admin::box_wrap( 'close' );
        
        echo '<p class="wpsr_ppe_footer" align="center"><button class="button button-primary wpsr_ppe_save" data-mode="widget" data-cnt-id="' . esc_attr( $_GET[ 'cnt_id' ] ) . '" data-prev-id="' . esc_attr( $_GET[ 'prev_id' ] ) . '">Apply settings</button> <button class="button wpsr_ppe_cancel">Cancel</button></p>';
        
        echo '</div>';
        
        iframe_footer();
        die( 0 );
    }
    
    function widget( $args, $instance ){
        
        $instance = WPSR_Lists::set_defaults( $instance, $this->defaults );
        
        $out = WPSR_Template_Buttons::html( $instance[ 'buttons_template' ] );
        echo $out[ 'html' ];
        
    }
    
    function form( $obj, $instance ){
        
        echo '<h4>' . __( 'Social media buttons widget', 'wpsr' ) . '</h4>';
        
        $instance = WPSR_Lists::set_defaults( $instance, $this->defaults );
        $fields = new WPSR_Widget_Form_Fields( $obj, $instance );
        
        $wtmpl_val = esc_attr( $instance[ 'buttons_template' ] );
        $wtmpl_cnt_id = $obj->get_field_id( 'buttons_template' );
        $wtmpl_prev_id = $obj->get_field_id( 'wtmpl_prev' );
        
        echo '<div class="hidden">';
        $fields->text( 'buttons_template', '' );
        echo '</div>';
        
        echo '<div class="wpsr_wtmpl_wrap clearfix" id="' . $wtmpl_prev_id . '">';
        if( $wtmpl_val != '' ){
            WPSR_Admin::buttons_veditor( 'widget_buttons_template', $wtmpl_val, true, 'wpsr_wtmpl_dummy' );
        }else{
            echo '<p align="center">' . __( 'No buttons are added to template. Open the editor to add buttons', 'wpsr' ) . '</p>';
        }
        echo '</div>';
        
        echo '<br/>';
        echo '<p align="center"><button class="button button-primary wpsr_ppe_widget_open" data-wtmpl-cnt-id="' . $wtmpl_cnt_id . '" data-wtmpl-prev-id="' . $wtmpl_prev_id . '">' . __( 'Open editor', 'wpsr' ) . '</button></p>';
        
    }
    
    function update( $instance ){
        return $instance;
    }
    
}

new wpsr_widget_buttons();

?>