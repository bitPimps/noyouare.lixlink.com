<?php
/**
  * Main API class for WP Socializer plugin
  * 
  */

class WPSR_Services{
    
    private static $services = array();
    private static $active_services = array();
    
    public static function init(){
       
       add_action( 'init', array( __CLASS__, 'register' ) );
       
    }
    
    public static function register(){
        
        $init_services = apply_filters( 'wpsr_register_service', array() );
        
        $defaults = array(
            'name' => '',
            'icons' => '',
            'desc' => '',
            'settings' => array(),
            'hide_in_feature' => array(),
            'callbacks' => array(
                'output' => '',
                'includes' => '',
                'settings' => '',
                'validation' => '',
                'general_settings' => '',
                'general_settings_validation' => '',
                'templates' => ''
            )
        );
        
        foreach( $init_services as $id => $config ){
            
            $config[ 'callbacks' ] = WPSR_Lists::set_defaults( $config[ 'callbacks' ], $defaults[ 'callbacks' ] );
            $config = WPSR_Lists::set_defaults( $config, $defaults );
            
            apply_filters( 'wpsr_mod_service_config', $config );
            
            self::$services[ $id ] = $config;
            
            if( $config[ 'callbacks' ][ 'validation' ] != '' ){
                add_filter( 'wpsr_service_validation_' . $id, $config[ 'callbacks' ][ 'validation' ], 10, 1 );
            }
            
            if( $config[ 'callbacks' ][ 'general_settings' ] != '' ){
                add_action( 'wpsr_form_general_settings', $config[ 'callbacks' ][ 'general_settings' ], 10, 1 );
                add_filter( 'wpsr_form_validation_general_settings', $config[ 'callbacks' ][ 'general_settings_validation' ], 10, 1 );
            }
            
            self::register_includes( $id );
            
        }
        
    }
    
    public static function list_all( $remove_callbacks = true ){
        
        $services_temp = self::$services;
        
        if( $remove_callbacks ){
            foreach( $services_temp as $id => $config ){
                unset( $services_temp[ $id ][ 'callbacks' ] );
            }
        }
        
        return apply_filters( 'wpsr_mod_services_list', $services_temp );
        
    }
    
    public static function list_templates($feature=''){
        
        $services = self::list_all();
        $all_templates = array();
        
        foreach($services as $id => $config){
            $templates = self::templates($id);
            foreach($templates as $tid => $tdata){
                $supported_features = isset($tdata['features']) ? $tdata['features'] : array();
                if(count($supported_features) > 0 ){
                    if(!in_array($feature, $supported_features) || in_array('!'.$feature, $supported_features)){
                        continue;
                    }
                }
                
                $tdata['service'] = $id;
                $all_templates[$tid] = $tdata;
            }
        }
        
        return $all_templates;
        
    }
    
    public static function output( $id, $settings = array(), $page_info = array() ){
        
        $services = self::list_all( false );
        
        if( !self::check_callable( $id, 'output' ) ){
            return '';
        }
        
        return call_user_func( $services[ $id ][ 'callbacks' ][ 'output' ], $settings, $page_info );
        
    }
    
    public static function settings( $id, $values ){
        
        $services = self::list_all( false );
        
        if( !self::check_callable( $id, 'settings' ) ){
            return '';
        }
        
        return call_user_func( $services[ $id ][ 'callbacks' ][ 'settings' ], $values );
        
    }
    
    public static function templates( $id ){
        
        $services = self::list_all( false );
        
        if( !self::check_callable( $id, 'templates' ) ){
            return array();
        }
        
        return call_user_func( $services[ $id ][ 'callbacks' ][ 'templates' ] );
        
    }
    
    public static function register_includes( $id ){
        
        $services = self::list_all( false );
        
        if( !self::check_callable( $id, 'includes' ) ){
            return '';
        }
        
        $service_includes = call_user_func( $services[ $id ][ 'callbacks' ][ 'includes' ] );
        
        WPSR_Includes::register( $service_includes );
        
    }
    
    public static function check_callable( $id, $callback ){
        
        $services = self::list_all( false );
        
        if( array_key_exists( $id, $services ) ){
            $service = $services[ $id ];
            if( array_key_exists( $callback, $service[ 'callbacks' ] ) && !empty( $service[ 'callbacks' ][ $callback ] ) && is_callable( $service[ 'callbacks' ][ $callback ] ) ){
                return 1;
            }
        }
        
        return 0;
        
    }
    
    public static function add_active_service( $id ){
        
        $services_active = self::$active_services;
        
        if( !empty( $id ) && !in_array( $id, self::$active_services ) ){
            array_push( self::$active_services, $id );
        }
        
    }
    
    public static function active_services(){
        
        return apply_filters( 'wpsr_mod_active_services_list', self::$active_services );
        
    }
}

WPSR_Services::init();

?>