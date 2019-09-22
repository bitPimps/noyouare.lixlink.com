<?php
/**
  * WP Socializer button picker
  *
  **/

class WPSR_Buttons_Picker{
    
    public static function init(){
        
        // Register the action for admin ajax features
        add_action( 'wp_ajax_wpsr_buttons_picker', array( __CLASS__, 'list_buttons' ) );
        
        add_action( 'wp_ajax_wpsr_create_button', array( __CLASS__, 'create_button' ) );
        
    }
    
    public static function create_button(){
        
        check_ajax_referer('wpsr_create_button');
        
        if(!isset($_GET['btn_tmpl_id']) || !isset($_GET['feature']) || !isset($_GET['type']) || !isset($_GET['service'])){
            die(0);
        }
        
        $feature_id = wp_kses_post( $_GET[ 'feature' ] );
        $service_id = wp_kses_post( $_GET[ 'service' ] );
        $btn_tmpl_id = wp_kses_post( $_GET[ 'btn_tmpl_id' ] );
        $type = wp_kses_post( $_GET[ 'type' ] );
        
        $buttons = WPSR_Buttons::list_all();
        $services = WPSR_Services::list_all();
        
        if($type == 'new'){
            $buttons_tmpl = WPSR_Services::list_templates( $feature_id );
            
            if(!array_key_exists($btn_tmpl_id, $buttons_tmpl) && $btn_tmpl_id != 'default'){
                die(0);
            }
            
            $tmpl_props = $buttons_tmpl[$btn_tmpl_id];
            $btn_id = WPSR_Buttons::generate_button_id( $service_id );
            $btn_settings = ($btn_tmpl_id == 'default') ? array() : $tmpl_props['settings'];
            $btn_settings['title'] = isset($_GET['title']) ? wp_kses_post($_GET[ 'title' ]) : '';
            
            $buttons[$btn_id] = array(
                'service' => $service_id,
                'feature' => $feature_id,
                'settings' => $btn_settings
            );
            
            update_option('wpsr_buttons', $buttons);
            
            $buttons = WPSR_Buttons::list_all();
            $btn_tmpl_id = $btn_id;
        }
        
        if(!array_key_exists($btn_tmpl_id, $buttons)){
            die(0);
        }
        
        $btn_props = $buttons[$btn_tmpl_id];
        
        echo self::tmpl_veditor_button($btn_tmpl_id, $btn_props, $services[$service_id]);
        
        die(0);
        
    }
    
    public static function get_feature_services( $feature = array() ){
        
        $services = WPSR_Services::list_all();
        $feature = self::validate_feature( $feature );
        $buttons_list = array();
        $services_filtered = array();
        
        foreach( $services as $id => $config ){
            if( !in_array( $feature[ 'name' ], $config[ 'hide_in_feature' ] ) && !in_array( $id, $feature[ 'hide_services' ] ) ){
                $services_filtered[$id] = $config;
            }
        }
        
        return $services_filtered;
        
    }
    
    public static function get_feature_created_buttons( $feature, $service ){
        
        $buttons = WPSR_Buttons::list_all();
        $buttons_list = array(); // Holds all the button ids
        $feature = self::validate_feature( $feature );

        foreach( $buttons as $id => $config ){
            
            if( !array_key_exists( 'feature', $config ) ){
                $config[ 'feature' ] = $feature[ 'name' ];
            }
            
            if( $config[ 'feature' ] == $feature[ 'name' ] && $config['service'] == $service ){
                $buttons_list[$id] = $config;
            }
            
        }
        
        return $buttons_list;
        
    }
    
    public static function print_buttons_picker( $feature ){
        
        $services = self::get_feature_services( $feature );
        $nonce = wp_create_nonce( 'wpsr_create_button' );
        
        echo '<div class="bp_wrap" data-feature="' . $feature['name'] . '" data-nonce="' . $nonce . '">';
        echo '<h1 class="bp_head">Select button to insert <span data-htip="You can customize the buttons after inserting them in the editor"><i class="fa fa-info-circle pointer"></i></span> <span class="bp_close"><i class="fa fa-times"></i></span></h1>';
        
        echo '<div class="bp_cnt">';
        echo '<ul class="bp_slist">';
        foreach($services as $id => $config){
            echo '<li data-sid="' . $id . '">';
            echo '<img src="' . $config['icons'] . '" class="bp_sicon" />';
            echo $config['name'] . '</li>';
        }
        echo '</ul>';
        
        echo '<div class="bp_blist">';
        foreach($services as $id => $config){
            
            $buttons = self::get_feature_created_buttons( $feature, $id );
            $service_name = $config['name'];
            
            echo '<div class="bp_sbox" data-sid="' . $id . '">';
            echo '</div>';
        }
        echo '</div>';
        
        echo '</div><!--bp_cnt-->';
        echo '</div><!--bp_wrap-->';
    }
    
    public static function print_veditor_buttons( $buttons_list ){
        
        $buttons = WPSR_Buttons::list_all();
        $services = WPSR_Services::list_all();
        
        echo '<ul class="btn_list clearfix" data-empty="No buttons created">';
        foreach($buttons_list as $id){
            
            if( !isset( $buttons[ $id ] ) )
                continue;
            
            $config = $buttons[ $id ];
            
            if( !isset( $services[ $config[ 'service' ] ] ) )
                continue;
            
            $service = $services[ $config[ 'service' ] ];
            
            echo self::tmpl_veditor_button($id, $config, $service);
            
        }
        echo '</ul>';
        
    }
    
    public static function list_buttons(){
        
        if(!isset($_GET['service']) || !isset($_GET['feature'])){
            die(0);
        }
        
        $service = wp_kses_post( $_GET[ 'service' ] );
        $feature = wp_kses_post( $_GET[ 'feature' ] );
        
        $buttons = WPSR_Buttons::list_all();
        $buttons_tmpl = WPSR_Services::list_templates( $feature );
        $buttons_feature = array();
        
        echo '<!doctype html><html lang="en"><head><meta charset="utf-8"><title></title>';
        echo '<link href="' . WPSR_ADMIN_URL . 'css/button_picker_preview.css" rel="stylesheet" type="text/css" media="all" />';
        echo '</head><body>';
        
        foreach($buttons as $id=>$config){
            if($config['service'] == $service && $config['feature'] == $feature){
                $buttons_feature[$id] = $config;
            }
        }
        
        if(count($buttons_feature) > 0){
            echo '<h3>Created buttons</h3>';
            echo '<div class="clearfix">';
            
            foreach($buttons_feature as $id=>$config){
                echo self::tmpl_list_button($id, $config, 'created', $service);
            }
            
            echo '</div>';
        }
        
        echo '<h3>Create new button</h3>';
        echo '<div class="clearfix">';
        
        echo self::tmpl_list_button('default', array(), 'new', $service);
        
        WPSR_Buttons::$temp_buttons = $buttons_tmpl;
        foreach($buttons_tmpl as $id=>$config){
            if($config['service'] == $service){
                echo self::tmpl_list_button($id, $config, 'new', $service);
            }
        }
        WPSR_Buttons::$temp_buttons = array();
        
        echo '</div>';
        
        WPSR_Includes::preview_print_includes();
        
        echo '</body></html>';
        
        die(0);
        
    }
    
    public static function tmpl_list_button($id, $config, $type, $service ){
        
        $page_info = array(
            'title' => 'Google',
            'url' => 'https://www.google.com',
            'excerpt' => 'Dummy excerpt for WP Socializer preview',
            'short_url' => 'https://goo.gl/lightsaber',
            'comments_count' => '45',
            'post_id' => 1
        );
        
        $html = '';
        
        if($id == 'default'){
            $title = 'Create button with default settings';
            $btn_html = '<i class="fa fa-plus default_icon"></i>';
        }else{
            $title = isset($config['settings']['title']) ? $config['settings']['title'] : '';
            $desc = isset($config['desc']) ? $config['desc'] : '';
            $btn_html = WPSR_Buttons::get_button($id, $page_info);
        }
        
        $html .= '<div class="bt_wrap" data-id="' . $id . '" data-type="' . $type . '" data-service="' . $service . '">';
        
        if($type == 'created'){
            $html .= '<span class="bt_delete" title="Delete"><i class="fa fa-trash-alt"></i></span>';
        }
        
        $html .= '<div class="bt_inner">';
        $html .= $btn_html;
        $html .= '</div>';
        
        if(!empty($title) || !empty($desc)){
            $html .= '<div class="tooltip">';
            if(!empty($title)) $html .= '<span>' . $title . '</span>';
            if(!empty($desc)) $html .= '<span>' . $desc . '</span>';
            $html .= '</div>';
        }
        
        $html .= '</div>';
        
        return $html;
    }
    
    public static function tmpl_veditor_button($id, $config, $service){
        $html = '';
        $html .= '<li data-service="' . $config[ 'service' ] . '" data-id="' . $id . '" class="ui_btn">';
            $html .= '<span class="btn_icon"><img src="' . $service['icons'] . '" /></span>';
            $html .= '<span class="btn_name"' . ( isset( $config[ 'settings' ][ 'title' ] ) ? 'data-title="' . $config[ 'settings' ][ 'title' ] . '"' : '' ) . '>' . $service['name'] . '</span>';
            $html .= '<i class="btn_action btn_delete fa fa-trash-alt" title="' . __( 'Delete button', 'wpsr' ) . '"></i>';
            $html .= '<i class="btn_action btn_edit fa fa-cog" title="' . __( 'Settings', 'wpsr' ) . '"></i>';
        $html .= '</li>';
        return $html;
    }
    
    public static function validate_feature( $feature ){
        
        return WPSR_Lists::set_defaults( $feature, array(
            'name' => 'none',
            'hide_services' => array()
        ));
        
    }
    
}

WPSR_Buttons_Picker::init();

?>