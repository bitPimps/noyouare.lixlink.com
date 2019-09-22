<?php
/**
  * Help admin page
  *
  **/
  
class wpsr_admin_help{
    
    function __construct(){
        
        WPSR_Admin::add_tab( 'help', array(
            'name' => __( 'Help', 'wpsr' ),
            'page_callback' => array( $this, 'page' ),
            'form' => array(
                'id' => 'help',
                'name' => 'help',
                'callback' => ''
            )
        ));
        
    }
    
    function page(){
        
        echo '<div class="help_sec style_ele">';
        $help_res = wp_remote_get( WPSR_Lists::ext_res( 'help' ) );
        
        if( is_wp_error( $help_res ) ){
            echo '<p>Error retreiving help information</p>';
        }else{
            echo wp_remote_retrieve_body( $help_res );
        }
        
        echo '</div><br/>';
        
        WPSR_Admin::admin_footer();
        
    }
    
    function validation( $input ){
        return $input;
    }
    
}

new wpsr_admin_help();

?>