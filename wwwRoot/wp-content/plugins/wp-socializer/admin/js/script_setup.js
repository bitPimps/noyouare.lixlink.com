(function($){
jQuery(document).ready(function(){
    
    var currentPage = 1;
    var setupTime = 0;
    var $tmplTarget = false;
    
    var setupData = {
        'share_buttons': {
            'above_post': 0,
            'below_post': 0
        },
        'sharebar': 0,
        'followbar': 0,
        'text_sharebar': 0,
        'mobile_sharebar': 0
    };
    
    var init = function(){
        
        afterPageChange();
        tmplPreviewCheck();
        startTimer();
        init_image_selects();
        
        $( '#sb_btn_option' ).trigger( 'change' );
        
    }
    
    var afterPageChange = function(){
        
        $activePage = $( '.page.active' );
        $nextBtn = $( '.next_btn' );
        $prevBtn = $( '.prev_btn' );
        $skipBtn = $( '.skip_btn' );
        
        currentPage = $activePage.data( 'page' );
        totalPages = $( '.pages .page' ).length;
        
        if( currentPage == totalPages - 1 ){
            $nextBtn.find( '.text' ).text( $nextBtn.data( 'finish' ) );
        }else{
            $nextBtn.find( '.text' ).text( $nextBtn.data( 'next' ) );
        }
        
        if( $activePage.prev().length == 0 ){
            $prevBtn.hide();
        }else{
            $prevBtn.show();
            $skipBtn.hide();
        }
        
        if( $activePage.next().length == 0 ){
            $nextBtn.hide();
        }else{
            $nextBtn.show();
        }
        
        $( '.progress li' ).removeClass( 'active' );
        $( '.progress li[data-id="' + currentPage + '"]' ).addClass( 'active' );
        
        // Last page
        if( currentPage == totalPages ){
            saveSetup();
        }
        
    }
    
    var goNextPage = function(){
        
        var $activePage = $( '.page.active' );
        var $nextPage = $activePage.next();
        
        $activePage.removeClass( 'active' );
        $nextPage.addClass( 'active' ).fadeTo( 'fast', 0 ).fadeTo( 'slow', 1 );
        
        afterPageChange();
    }
    
    var goPrevPage = function(){
        
        var $activePage = $( '.page.active' );
        var $prevPage = $activePage.prev();
        
        $activePage.removeClass( 'active' );
        $prevPage.addClass( 'active' ).fadeTo( 'fast', 0 ).fadeTo( 'slow', 1 );
        
        afterPageChange();
    }
    
    var saveSetup = function(){
        
        $smsg = $( '.saving_msg' );
        $sdone = $( '.saving_done' );
        
        $smsg.show();
        $sdone.hide();
        
        // Sharebar
        if( $( '#ft_sharebar' ).is( ':checked' ) ){
            var sharebar_list = [];
            $( '.sharebar_btns li.active' ).each(function(){
                sharebar_list.push( $(this).data( 'id' ) );
            });
            setupData[ 'sharebar' ] = {
                'orientation': $( '#sb_orientation' ).val(),
                'buttons': sharebar_list
            };
        }
        
        // Followbar
        if( $( '#ft_followbar' ).is( ':checked' ) ){
            var fb_template = $( '#fb_template_val' ).val();
            if( fb_template != '' ){
                setupData[ 'followbar' ] = fb_template;
            }
        }
        
        // Text sharebar
        if( $( '#ft_textsharebar' ).is( ':checked' ) ){
            setupData[ 'text_sharebar' ] = 1;
        }
        
        // Mobile sharebar
        if( $( '#ft_mobilesharebar' ).is( ':checked' ) ){
            setupData[ 'mobile_sharebar' ] = 1;
        }
        
        setupData[ '_wpnonce' ] = $smsg.data( 'nonce' );
        
        if( wpsr.ajaxurl ){
            var queryString = '?action=wpsr_setup';
            var postLocation = wpsr.ajaxurl + queryString;
            
            var failed = function( d ){
                alert( 'Failed to save the data, please try again !' );
                goPrevPage();
                console.log( d );
            };
            
            var ajax = $.post( postLocation, setupData );
            
            ajax.done(function( res ){
                if( res.search( 'SETUP_SAVED' ) != -1 ){
                    $smsg.hide();
                    $sdone.slideDown();
                    displayTime();
                }else{
                    failed( res );
                }
            });
            
            ajax.fail(function( d ){
                failed( d );
            });
        }
        
        console.log( setupData );
        
    }
    
    var tmplPreviewCheck = function(){
        
        var $tmplPreview = $( '.tmpl_preview' );
        
        $tmplPreview.each(function(){
            if( $(this).is( ':empty' ) ){
                $(this).html( '<span>' + $(this).data( 'etext' ) + '</span>' );
            }
        });
    }
    
    var tmplPreviewClose = function(){
        $( '.tmpl_popup' ).hide();
        $( '.tmpl_list li' ).removeClass( 'selected' );
        tmplPreviewCheck();
    }
    
    var startTimer = function(){
        window.setupTimerID = setInterval(function(){
            setupTime += 1;
        }, 1000 );
    }
    
    var displayTime = function(){
        clearInterval( window.setupTimerID );
        var timeTook = Math.floor( setupTime/60 );
        var the_time = 0;
        
        if( setupTime < 60 ){
            the_time = setupTime + ' seconds';
        }else if( timeTook == 1 ){
            the_time = '1 minute';
        }else if ( timeTook > 1 ){
            the_time = timeTook + ' minutes';
        }
        
        if ( timeTook < 8 ){
            $( '.timer_text' ).html( 'You took only <b>' + the_time + '</b> to setup social sharing buttons using WP Socializer !' );
        }else{
            $( '.timer_text' ).hide();
        }
    }
    
    var init_image_selects = function(){
        jQuery( '.img_select_list li' ).each(function(){
            $li = jQuery(this);
            if( $li.attr( 'data-init' ) == 'false' ){
                $li.on( 'click', function(){
                    $the_li = jQuery(this);
                    $parent = $the_li.parent();
                    $org = $parent.prev();
                    $parent.find( 'li' ).removeClass( 'img_opt_selected' );
                    $the_li.addClass( 'img_opt_selected' );
                    $org.val( $the_li.attr( 'data-value' ) );
                    $org.trigger( 'change' );
                });
                $li.attr( 'data-init', 'true' );
            }
        });
    }
    
    $( document ).on( 'click', '.next_btn', function(){
        goNextPage();
    });
    
    $( document ).on( 'click', '.prev_btn', function(){
        goPrevPage();
    });
    
    $( document ).on( 'click', '.tmpl_sel', function(){
        $( '.tmpl_popup' ).fadeIn();
        $tmplTarget = $(this);
    });
    
    $( document ).on( 'click', '.tmpl_list li', function(){
        if( $(this).hasClass( 'selected' ) ){
            $(this).removeClass( 'selected' );
        }else{
            $( '.tmpl_list li' ).removeClass( 'selected' );
            $(this).addClass( 'selected' );
        }
    });
    
    $( document ).on( 'click', '.tmpl_pp_close', function(e){
        e.preventDefault();
        tmplPreviewClose();
    });
    
    $( document ).on( 'click', '.tmpl_apply_btn', function(){
        
        if( $tmplTarget ){
            
            var tmplHTML = '';
            var tmplFor = $tmplTarget.data( 'for' );
            var $tmplSelected = $( '.tmpl_list li.selected' );
            var tmplSelID = $tmplSelected.data( 'id' );
            
            if( $tmplSelected.length > 0 && $tmplSelected.data( 'id' ) != '0' ){
                tmplHTML = $tmplSelected.html();
                setupData[ 'share_buttons' ][ tmplFor ] = tmplSelID;
            }else{
                setupData[ 'share_buttons' ][ tmplFor ] = 0;
            }
            
            $tmplTarget.find( '.tmpl_preview' ).html( tmplHTML );
            
            if( tmplHTML != '' ){
                $tmplTarget.removeClass( 'not_sel' ).addClass( 'preview_active' );
            }else{
                $tmplTarget.addClass( 'not_sel' ).removeClass( 'preview_active' );
            }
            
        }
        
        tmplPreviewClose();
        
    });
    
    $( document ).on( 'click', '.tmpl_clear_btn', function(){
        if( $tmplTarget ){
            var tmplFor = $tmplTarget.data( 'for' );
            setupData[ 'share_buttons' ][ tmplFor ] = 0;
            $tmplTarget.find( '.tmpl_preview' ).html( '' );
            $tmplTarget.addClass( 'not_sel' ).removeClass( 'preview_active' );
        }
        tmplPreviewClose();
    });
    
    $( document ).on( 'click', '.cb[data-toggle]', function(){
        var targetClass = $(this).data( 'toggle' );
        $( '.' + targetClass ).slideToggle();
        $( '[data-qwait="' + targetClass + '"]' ).slideToggle();
    });
    
    $( document ).on( 'click', '.sharebar_btns li', function(){
        $(this).toggleClass( 'active' );
        
        var selCount = $( '.sharebar_btns li.active' ).length;
        if( selCount > 0 ){
            $(this).parent().addClass( 'btns_selected' );
        }else{
            $(this).parent().removeClass( 'btns_selected' );
        }
        
    });
    
    $( document ).on( 'click', '.wpsr_ppe_fb_open', function(e){
        e.preventDefault();
        
        if( wpsr.ajaxurl ){
            
            cnt_id = $( this ).attr( 'data-cnt-id' );
            cnt = $( '#' + cnt_id ).val();
            prev_id = $( this ).attr( 'data-prev-id' );
            qstring = 'action=wpsr_followbar_editor&template=' + cnt + '&cnt_id=' + cnt_id + '&prev_id=' + prev_id;
            
            wpsr_ipopup_show( wpsr.ajaxurl + '?' + qstring, '500px', '80%' );
            
        }
    });
    
    $( document ).on( 'change', '#sb_btn_option', function(){
        
        var sb_btns = $(this).val();
        $( '.sharebar_btns li' ).removeClass( 'active' );
        
        if( sb_btns == 'native_buttons' ){
            $( '.sharebar_btn_opts' ).slideDown();
        }else if( sb_btns == 'icons' ){
            $( '.sharebar_btn_opts' ).slideUp();
            $( '.sharebar_btns li[data-id="social_icons"]' ).addClass( 'active' );
        }else if( sb_btns = 'icons_counter' ){
            $( '.sharebar_btn_opts' ).slideUp();
            $( '.sharebar_btns li[data-id="social_icons"]' ).addClass( 'active' );
            $( '.sharebar_btns li[data-id="share_counter"]' ).addClass( 'active' );
        }
    });
    
    $( document ).on( 'click', '.skip_btn', function(){
        var $btn = $(this);
        if( wpsr.ajaxurl ){
            var url = wpsr.ajaxurl + '?action=wpsr_admin_ajax&do=close_setup';
            $.get( url, function( data ){
                if( data.search( /done/g ) == -1 ){
                    $( '[data-page="1"]' ).html( 'Failed to close window. <a href="' + url + '" target="_blank">Please click here to close</a>' );
                }else{
                    window.location = $btn.data( 'return' );
                }
            });
        }
    });
    
    $( document ).on( 'click', '.tmpl_cat_name', function(){
        $( '.tmpl_list' ).slideUp();
        $list = $(this).next();
        if( $list.is( ':visible' ) ){
            $list.slideUp();
        }else{
            $list.slideDown();
        }
    });
    
    init();
    
});
})( jQuery );