$ = jQuery
jQuery(document).ready(function( $ ) {
    
    var $sb = $( '.wpsr-sharebar' );
    mousedown = false,
    parent = window.parent;
    
    $(document).on( 'input change', '.offset_drag', function(){
        var cur_val = parseInt( $(this).val() );
        var off_change = $(this).attr( 'data-coffset' );
        var $off_val = $( '.offset_val' );
        
        cur_val = cur_val * ( ( off_change == 'right' || off_change == 'bottom' ) ? -1 : 1 );
        
        //$sb.attr( 'style', '' );
        $sb.css( off_change, cur_val );
        $off_val.val( cur_val + 'px' );
    });
    
    $(document).on( 'change', '.offset_val', function(){
        $( '.offset_drag' ).val( parseInt( $(this).val() ) ).change();
    });
    
    $(document).on( 'click', '.save_btn', function(){
        var $off_val = $( '.offset_val' );
        $( parent.document ).find( '[name="offset"]' ).val( $off_val.val() );
        parent.wpsr_sharebar_preview_close();
    });
    
    $(document).on( 'click', '.close_btn', function(){
        parent.wpsr_sharebar_preview_close();
    });
    
});

