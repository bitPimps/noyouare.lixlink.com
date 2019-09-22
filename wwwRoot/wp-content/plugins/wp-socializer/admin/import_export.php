<?php
/**
  * Import/export admin page
  *
  **/
  
class wpsr_admin_import_export{
    
    function __construct(){
        
        add_filter( 'wpsr_register_admin_page', array( $this, 'register' ) );
        
    }
    
    function register( $pages ){
        
        $pages[ 'import_export' ] = array(
            'name' => __( 'Import/Export', 'wpsr' ),
            'page_callback' => array( $this, 'page' ),
            'banner' => WPSR_ADMIN_URL . '/images/banners/import-export.png',
            'feature' => false,
            'form' => array(
                'id' => 'import_export',
                'name' => 'import_export',
                'callback' => ''
            )
        );
        
        return $pages;
        
    }
    
    function page(){
        
        echo '<div class="notice notice-success inline hidden"><p>' . __( 'Successfull imported data !', 'wpsr' ) . '</p></div>';
        echo '<div class="notice notice-error inline hidden"><p>' . __( 'Failed to import data, please re-import the data !', 'wpsr' ) . '</p></div>';
        
        echo '<form id="import_form">';
        
        $section1 = array(
            array( __( 'Import data', 'wpsr' ), WPSR_Admin::field( 'textarea', array(
                'name' => 'import_data',
                'value' => '',
                'helper' => __( 'Paste the exported data into the text box above.', 'wpsr' ),
                'rows' => '3',
                'cols' => '100'
            )))
        );

        WPSR_Admin::build_table( $section1, __( 'Import data', 'wpsr' ), __( 'Import the already exported WP Socializer data using the field below. Please note that importing will <b>overwrite</b> all the existing buttons created and the settings.', 'wpsr' ) );
        
        wp_nonce_field( 'wpsr_import_nonce' );
        
        echo '<p align="right"><input type="submit" class="import_form_submit button button-primary" value="' . __( 'Import', 'wpsr' ) . '" /></p>';
        echo '</form>';
        
        $section2 = array(
            array( __( 'Export data', 'wpsr' ), WPSR_Admin::field( 'textarea', array(
                'name' => 'export_data',
                'value' => WPSR_Import_Export::export(),
                'helper' => __( 'Copy the data above, save it and import it later', 'wpsr' ),
                'rows' => '3',
                'cols' => '100',
                'custom' => 'onClick="this.select();"'
            )))
        );

        WPSR_Admin::build_table( $section2, __( 'Export', 'wpsr' ) );
        
    }
    
    function validation( $input ){
        return $input;
    }
    
}

new wpsr_admin_import_export();

?>