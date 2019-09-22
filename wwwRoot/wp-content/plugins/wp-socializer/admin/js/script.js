(function($){
jQuery(document).ready(function(){
    
    var init = function(){
        
        if( $.fn.draggable ){
            $( ".btns_created li" ).draggable( draggable_opts );
        }
        
        if( $.fn.sortable ){
            $( ".veditor ul" ).sortable( vedit_sortable_btns );
        
            $( ".veditor" ).sortable({
                axis: 'y',
                start: function( e, ui ){
                    ui.placeholder.height( ui.item.height() );
                    wpsr_admin_tooltip_close();
                }
            });
            
            $( '.fb_selected' ).sortable({
                axis: 'y',
                handle: 'h4',
                placeholder: 'ui-sortable-placeholder'
            });
            
            $( '.ssb_selected_list' ).sortable({
                stop: process_tsb_editor
            });
            
        }
        
        loc_sub_criteria();
        
        loc_generate_rules();
        
        feature_toggle();
        
        feature_toggle_btn();
        
        $( '.loc_page' ).each( function(){
            loc_update_rule_helper( $(this) );
        });
        
        if( $.fn.conditioner ){
            $('[data-conditioner]').conditioner();
        }
        
        if( $.fn.wpColorPicker ){
            $('.color_picker').wpColorPicker();
        }
        
        $('.template_wrap').hide().first().show();
        $('.template_tab li').first().addClass( 'templ_tab_active' );
        
        if( window.wpsr_show ){
            if( wpsr_show[ 'changelog' ] != 'false' ){
                changelog_show( window.wpsr_show[ 'changelog' ] );
            }
            if( wpsr_show[ 'setup' ] != 'false' ){
                setup_show();
            }
        }
        
        wpsr_init_image_selects();
        
    }
    
    var loc_generate_rules = function(){
    
        $( '.loc_rules_wrap' ).each(function(){
            
            tval = new Array();
            $wrap = $(this);
            $tinfo = $wrap.find( '.loc_rule_info' );
            $gadd = $wrap.find( '.loc_group_add' );
            $rule_box = $wrap.find( '.loc_rule_value' );
            $tgrp = $wrap.find( '.loc_rules_box .loc_group_wrap' );
            
            i = 0;
            $( $tgrp ).each(function(){
                $trle = $(this).find( '.loc_rule_wrap' );
                j = 0;
                tval[i] = new Array();
                $( $trle ).each(function(){
                    
                    tval[i][j] = [
                        $(this).find( '.loc_page' ).val(),
                        $(this).find( '.loc_operator' ).val(),
                        $(this).find( '.loc_value' ).val()
                    ];
                    
                    j++;
                });
                i++;
            });
            
            $rule_box.val( btoa( JSON.stringify( tval ) ) );
            
            if( $tgrp.length == 0 ){
                $tinfo.show();
                $gadd.text( 'Add new rules' );
            }else{
                $tinfo.hide();
                $gadd.text( ' Add another page ' );
            }
            
        });
    }
    
    var loc_sub_criteria = function(){
        $('.loc_group_wrap').each(function(){
            $master_rule = $(this).find( '.loc_rule_wrap:first-child' );
            master = $master_rule.find( '.loc_page' ).val();
            
            $( this ).find( '.loc_rule_wrap' ).each(function(){
                if( $(this).index() == 0 )
                    return true;
                $(this).find( '.loc_page option' ).each(function(){
                    if( $.inArray( $(this).val(), wpsr.loc_rules[ master ][ 'children' ] ) == -1 ){
                        $(this).remove();
                    }
                });
            });
            
            if( 'children' in wpsr.loc_rules[ master ] && wpsr.loc_rules[ master ][ 'children' ].length > 0 ){
                $master_rule.find( '.loc_rule_add' ).show()
            }else{
                $master_rule.find( '.loc_rule_add' ).hide()
            }
            
        });
        
        $( '.loc_page' ).each(function(){
            loc_update_rule_helper( $(this) );
        });
    }
    
    var loc_add_rule = function( group, btn ){
        grp_temp = $( '.loc_rules_temp' ).html();
        rule_temp = $( '.loc_rules_temp .loc_group_wrap').html();
        
        if( group ){
            btn.closest( '.loc_rules_wrap' ).find('.loc_rules_box').append( grp_temp );
        }else{
            btn.closest( '.loc_group_wrap' ).append( rule_temp );
        }
        
        loc_sub_criteria();
        loc_generate_rules();
    }


    var loc_remove_rule = function( btn ){
        $rule = btn.parent();
        $grp = $rule.parent();

        if( $rule.index() == 0 ){
            $grp.empty();
        }
        
        $rule.remove();
        
        if( $grp.children().length == 0 ){
            $grp.remove();
        }
        
        loc_generate_rules();
    }

    var loc_update_rule_helper = function( pageBtn ){
        
        helper = pageBtn.find( 'option:selected' ).attr( 'data-helper' );
        
        if( helper == 0 ){
            pageBtn.siblings( '.loc_operator, .loc_value' ).hide();
        }else{
            pageBtn.siblings( '.loc_operator, .loc_value' ).show();
        }
        
        placeholder = pageBtn.find( 'option:selected' ).attr( 'data-placeholder' );
        if( placeholder ){
            pageBtn.siblings( '.loc_value' ).attr( 'placeholder', placeholder );
        }
        
    }
    
    var process_vedit = function(){
        $( '.vedit_wrap' ).each(function(){
            
            $cnt_box = $(this).find( '.veditor_content' );
            $vedit_rows = $(this).find( '.veditor ul' );
            cnt = {};
            row = 1;
            
            if( $vedit_rows.length > 0 ){
                $vedit_rows.each(function(){
                    cnt[row] = {
                        'properties' : {},
                        'buttons': []
                    }
                    
                    $(this).children('li').each(function(){
                        temp = {};
                        bid = $(this).attr( 'data-id' );
                        temp[ bid ] = {};
                        cnt[row]['buttons'].push( temp );
                    });
                    
                    row++;
                });
                
                $cnt_box.val( btoa( JSON.stringify( cnt ) ) );
            }
            
        });
    }
    
    var draggable_opts = {
        revert: 'invalid',
        helper: 'clone',
        cursor: 'move',
        connectToSortable: '.veditor ul',
        containment: 'document',
        start: function( e, ui ){
            $( '.veditor ul' ).addClass( 'drop_target' );
            wpsr_admin_tooltip_close();
        },
        stop: function(){
            $( '.veditor ul' ).removeClass( 'drop_target' );
        }
    };
    
    var vedit_sortable_btns = {
        connectWith: ".veditor ul",
        placeholder: 'ui-state-placeholder',
        items: '> li',
        containment: 'document',
        start: function( e, ui ){
            ui.placeholder.width( ui.item.width() );
            ui.item.width( parseInt( ui.item.width() ) + 1 );
            wpsr_admin_tooltip_close();
        }
    }
    
    var feature_toggle = function(){
        var $ft_wrap = $( '.feature_wrap' );
        
        if( $( '[name="ft_status"]' ).val() == 'enable' ){
            $ft_wrap.removeClass( 'ft_disable' );
        }else{
            $ft_wrap.addClass( 'ft_disable' );
        }
    }
    
    var feature_toggle_btn = function(){
        $( '[name="ft_status"]' ).each(function(){
            $btn = $( '<i class="fa ft_toggle_btn" title="Toggle feature"></i>' );
            if( $(this).val() == 'enable' ){
                $btn.addClass( 'fa-toggle-on' );
            }else{
                $btn.addClass( 'fa-toggle-off' );
            }
            $(this).after( $btn );
        });
    }
    
    var changelog_show = function( version ){
        vFile = wpsr.ext_res[ 'wp-socializer-cl' ] + version + '.html';
        $wcPopup = $( '.welcome_wrap' );
        $.get( vFile, function(data){
            $wcPopup.find( 'section' ).html( data );
            $wcPopup.fadeIn( 'fast' );
        });
        window.changelog_on = true;
    }
    
    var changelog_hide = function(){
        var url = wpsr.ajaxurl + '?action=wpsr_admin_ajax&do=close_changelog';
        $.get( url, function( data ){
            if( data.search( /done/g ) == -1 ){
                $( '.welcome_wrap section' ).html( 'Failed to close window. <a href="' + url + '" target="_blank">Please click here to close</a>' );
            }else{
                $( '.welcome_wrap' ).fadeOut();
            }
        });
        
        window.changelog_on = false;
        if( window.wpsr_show && window.wpsr_show[ 'setup' ] != 'false' ){
            setup_show();
        }
        
    }
    
    var setup_show = function(){
        if( !window.changelog_on ){
            $setupWin = $( '.setup_intro' );
            $setupWin.fadeIn( 'fast' );
        }
    }
    
    var setup_hide = function(){
        var url = wpsr.ajaxurl + '?action=wpsr_admin_ajax&do=close_setup';
        $.get( url, function( data ){
            if( data.search( /done/g ) == -1 ){
                $( '.setup_intro' ).html( 'Failed to close window. <a href="' + url + '" target="_blank">Please click here to close</a>' );
            }else{
                $( '.setup_intro' ).fadeOut();
            }
        });
    }
    
    var bp_change_tab = function(service){
        $('.bp_slist li').removeClass('active');
        $('.bp_slist li[data-sid="' + service + '"]').addClass('active');
        $('.bp_sbox').removeClass('active');
        
        var $bp_wrap = $('.bp_wrap');
        var $sbox = $('.bp_sbox[data-sid="' + service + '"]');
        var feature = $bp_wrap.data('feature');
        var nonce = $bp_wrap.data('nonce');
        
        window.wpsr_bp_hit = true;
        $sbox.addClass('active');
        
        if(!$sbox.hasClass('init_done')){
            var url = wpsr.ajaxurl + '?action=wpsr_buttons_picker&service=' + service + '&feature=' + feature;
            $sbox.append('<iframe src="' + url + '" class="bp_sel_box" frameborder="0" scrolling="yes"></iframe>');
            $sbox.addClass('loading');
            $sbox.addClass('init_done');
        }
        
        $iframe = $sbox.find('iframe');
        $iframe.load(function(){
            
            var $iframe_cnts = $iframe.contents();
            
            $iframe_cnts.find('.bt_wrap').click(function(){
                bp_btn_insert($(this), $iframe, feature, nonce);
            });
            
            $iframe_cnts.find('.bt_delete').click(function(e){
                e.stopPropagation();
                bp_btn_delete($(this).parent(), service);
            });
            
            $sbox.removeClass('loading');
        });
        
    }
    
    var bp_btn_insert = function($btn, $iframe, feature, nonce){
        
        var id = $btn.data('id');
        var type = $btn.data('type');
        var service = $btn.data('service');
        var title = '';
        
        if(type == 'new'){
            var title = prompt('Name of the button for identification');
        }
        
        $btn.addClass('inserting');
        
        $.get( wpsr.ajaxurl, {
        
            action: 'wpsr_create_button',
            btn_tmpl_id: id,
            feature: feature,
            type: type,
            service: service,
            title: (title == null ? '' : title),
            _wpnonce: nonce
            
        }).done(function(data){
            
            if(data == ''){
                return false;
            }
            
            if(window.wpsr_bp){
                $vedit_wrap = window.wpsr_bp;
                $vedit_row = $vedit_wrap.find('.veditor ul:last-child');
                
                $li = $(data);
                if($li.data('id') !== undefined){
                    $vedit_row.append(data);
                }
            }
            
            $btn.removeClass('inserting');
            bp_close();
            
            if(type == 'new'){
                $iframe.attr('src', function(i, val){return val;});
            }
            
        }).fail(function(data){
            console.log('WPSR create button failed: ' + data);
            return false;
        });
        
    }
    
    var bp_btn_delete = function($btn, service){
        reply = confirm( wpsr.js_texts.del_btn );
        if( reply ){
            btn_id = $btn.data('id');
            
            $btn.addClass('inserting');
            
            $.ajax({
                url: wpsr.ajaxurl,
                data: {
                    action: 'wpsr_service',
                    do: 'delete',
                    service_id: service,
                    button_id: btn_id
                }
            }).done(function(d){
                if(d == '1'){
                    $btn.fadeOut('slow', function(){
                        $btn.remove();
                        $('.veditor .ui_btn[data-id="' + btn_id + '"]').remove();
                    });
                }else{
                    console.info( 'Delete button failed: ' + d );
                }
            });
        }
    }
    
    var bp_open = function($veditor){
        $('.bp_wrap').fadeIn();
        $('.bp_wrap').addClass('open');
        $('body').addClass('hide_scrollbar');
        
        window.wpsr_bp = $veditor;
        
        if(!window.wpsr_bp_hit){
            var first_svc = $('.bp_slist li:first-child').data('sid');
            bp_change_tab(first_svc);
        }
        
    }
    
    var bp_close = function(){
        $('.bp_wrap').fadeOut();
        $('.bp_wrap').removeClass('open');
        $('body').removeClass('hide_scrollbar');
        window.wpsr_bp = false;
    }
    
    // Attach the events
    
    $(document).on( 'click', '.btn_selector .sbox_action', function(){
        $parent = $(this).closest( '.sbox_wrap' ).addClass( 'sbox_loading' );
        $sel = $parent.find( '.sbox_val .btn_liname' );
        
        if( $sel.length == 0 || $('.btns_created').length == 0 )
            alert( wpsr.js_texts.sel_btn );
        
        $.get( wpsr.ajaxurl, {
        
            action: 'wpsr_service',
            do: 'new',
            service_id: $sel.attr( 'data-service' ),
            feature: $sel.attr( 'data-feature' )
            
        }).done(function( data ){
            
            $parent.removeClass( 'sbox_loading' );
            $( data['html'] ).hide().appendTo( '.btns_created' ).fadeTo( 'slow', 1 ).draggable( draggable_opts );
            
        });

    });

    // Button edit
    $(document).on( 'click', '.btn_edit', function(){
        
        $parent = $(this).parent();
        service = $parent.attr( 'data-service' );
        wpsr_service = wpsr.services[ service ];
        
        // Tooltip properties
        tt_props = {
            parent: $parent,
            content: {
                url: wpsr.ajaxurl,
                data: {
                    action: 'wpsr_service',
                    do: 'edit',
                    service_id: service,
                    button_id: $parent.attr( 'data-id' )
                }
            }
        };
        
        if( typeof wpsr_service[ 'settings' ] !== 'undefined' && typeof wpsr_service[ 'settings' ][ 'size' ] !== 'undefined' ){
            ss = wpsr.services[ service ][ 'settings' ][ 'size' ];
            if ( ss.match( /x/i ) != null ){
                tt_props[ 'width' ] = ss.split( 'x' )[0];
                tt_props[ 'height' ] = ss.split( 'x' )[1];
            }else{
                tt_props[ 'class' ] = 'wpsr_tooltip_popup';
                tt_props[ 'name' ] = wpsr_service[ 'name' ];
            }
        }
        
        // Show the tooltip
        wpsr_admin_tooltip(tt_props);
    });
    
    $(document).on( 'submit', '.wpsr_tooltip_wrap form', function(e){
        e.preventDefault();
        
        $tt = $(this).parent().parent().addClass( 'loading' );
        post_url = $(this).attr( 'action' );
        button_title = $(this).find( 'input[name="o[title]"]' ).val()
        button_id = $(this).find( 'input[name=button_id]' ).val()
        
        $callbacks = $( this ).find( '[data-callback]' );
        if( $callbacks.length > 0 ){
            $callbacks.each(function(){
                
                $ele = $( this );
                cb = $ele.data( 'callback' );
                
                if( typeof window[ cb ] !== 'undefined' ){
                    window[ cb ]( $ele );
                }
                
            });
        }
        
        $.post( post_url, $(this).serialize() ).done(function( data ){
            
            $( '.ui_btn[data-id="' + button_id + '"] .btn_name' ).attr( 'data-title', button_title );
            $tt.removeClass( 'loading' );
            $( '.btn_settings_status' ).fadeIn().delay( 5000 ).fadeOut();
            
        });
        
    });
    
    $(document).on('submit', function(e){
        process_vedit();
        loc_generate_rules();
    });
    
    $(document).on('click', '.vedit_add_button', function(e){
        e.preventDefault();
        var $veditor = $(this).closest('.vedit_wrap');
        bp_open($veditor);
    });
    
    $(document).on( 'click', '.vedit_preview_btn', function(e){
        e.preventDefault();
        
        $vedit = $(this).closest( '.vedit_wrap' );
        $vedit_preview = $('.veditor_preview');
        $iframe = $vedit.find( '.vedit_preview_iframe' );
        $iframe.fadeTo( 'slow', 0.5 );
        $(this).text( $(this).data('refresh') );
        action = $(this).data( 'action' );
        
        process_vedit()
        
        template = $vedit.find( '.veditor_content' ).val();
        $iframe.attr( 'src', wpsr.ajaxurl + '?action=' + action + '&template=' + template );
        $iframe.load(function(){
            $(this).fadeTo( 'slow', 1 );
        });
        $vedit_preview.show();
    });
    
    $(document).on( 'change', '.loc_rule_select', function(e){
        
        $parent = $(this).parent();
        $parent.find( '.loc_rule_selector, .loc_btn_menu' ).remove();
        
        $.get( wpsr.ajaxurl, {
        
            action: 'wpsr_location_rules',
            rule_id: $(this).val()
            
        }).done(function( data ){
            
            $parent.append( '<span class="loc_rule_selector">' + data + '</span>' );
            
        });
        
    });
    
    $(document).on( 'click', '.loc_rules_menu', function(){
        $(this).siblings('.loc_rule_selector').fadeToggle('fast');
    });
    
    $(document).on( 'click', '.loc_rules_remove', function(){
        $(this).parent().remove();
    });
    
    $(document).on( 'click', '.add_loc_rule', function(e){
        e.preventDefault();
        
        rule = $('.loc_rules_temp').html();
        rule = rule.replace( '%rule_id%', $(this).attr( 'data-id' ) );
        $(this).siblings( '.loc_rules_list' ).append( '<li>' + rule + '</li>' );
        
    });
    
    $( document ).on( 'click', '.btn_settings_save', function(){
        $( '.wpsr_tooltip_cnt form' ).submit();
    });
    
    $( document ).on( 'click', '.vedit_add_row', function(e){
        e.preventDefault();
        $('<ul></ul>').sortable( vedit_sortable_btns ).appendTo( $(this).closest( '.vedit_wrap' ).find( '.veditor' ) );
    });
    
    $(document).on( 'click', '.vedit_delete_row', function(e){
        $(this).closest( 'ul' ).remove();
    });
    
    $( document ).on( 'click', '.veditor .btn_delete', function(){
        $(this).parent().remove();
    });
    
    $( document ).on( 'mouseenter', '.veditor > ul', function(){
        
        $vedit_row_menu = $('.vedit_row_menu').clone().show();
        if( $(this).parent().children().length == 1 ){
            $vedit_row_menu.find( '.vedit_delete_row' ).remove();
        }
        $vedit_row_menu.appendTo($(this));
    });
    
    $( document ).on( 'mouseleave', '.veditor > ul', function(){
        $(this).find( '.vedit_row_menu' ).remove();
    });
    
    $(document).on( 'click', '.loc_group_add', function(e){
        e.preventDefault();
        loc_add_rule( true, $(this) );
    });
    
    $(document).on( 'click', '.loc_rule_add', function(e){
        e.preventDefault();
        loc_add_rule( false, $(this) );
    });
    
    $(document).on( 'click', '.loc_rule_remove', function(e){
        e.preventDefault();
        loc_remove_rule( $(this) );
    });

    $(document).on( 'click', '.loc_value', function(e){
        $list = $(this).siblings( '.loc_page' )
        val = $list.val();
        helper = $list.find( 'option:selected' ).attr( 'data-helper' );
        
        if( helper == "1" ){
            wpsr_admin_tooltip({
                parent: $(this),
                class: 'loc_rules_tt',
                height: '200px',
                content: {
                    url: wpsr.ajaxurl,
                    data: {
                        action: 'wpsr_location_rules',
                        rule_id: val,
                        selected: $(this).val()
                    }
                }
            });
        }
    });
    
    $(document).on( 'click', '.loc_rules_tt input[type="checkbox"]', function(e){
        temp = [];
        $(this).closest( '.loc_rules_tt' ).find( 'input[type="checkbox"]' ).each(function(){
            if( $(this).is(':checked') )
                temp.push( $(this).val() );
        });
        document.wpsr_tt_parent.val( temp );
    });
    
    $(document).on( 'change', '.loc_page', function(e){
        loc_update_rule_helper( $(this) );
        wpsr_admin_tooltip_close();
        $(this).siblings( '.loc_value' ).val( '' );
        
        if( $(this).closest( '.loc_rule_wrap' ).index() == 0 ){
            $(this).closest( '.loc_group_wrap' ).children().not(':first-child' ).remove();
            loc_sub_criteria();
        }
        
    });
    
    $(document).on('click', '.bp_slist li', function(){
        var sid = $(this).data('sid');
        bp_change_tab(sid);
    });
    
    $(document).on('click', '.bp_close', function(){
        bp_close();
    });
    
    $(document).on( 'click', '.sbox_inner', function(){
        $(this).toggleClass( 'sbox_open' );
    });
    
    $(document).on( 'change', '[name="ft_status"]', function(){
        feature_toggle();
    });
    
    $(document).on( 'click', '.sharebar_preview_btn', function(e){
        e.preventDefault();
        
        process_vedit();
        
        $iwrap = $( '.sharebar_preview_iwrap' );
        $iframe = $( '.sharebar_preview_iwrap > iframe' );
        form_data = $( '#sharebar_settings' ).serialize();
        
        $( 'body' ).addClass( 'hide_scrollbar' );
        $iwrap.show();
        $iframe.attr( 'src', wpsr.ajaxurl + '?action=wpsr_preview_template_sharebar&' + form_data );
        
    });
    
    $(document).on( 'click', '.sharebar_preview_close', function(){
        wpsr_sharebar_preview_close();
    });
    
    //social icons item properties
    $current_item = '';
    
    $( document ).on( 'click', '.item_delete', function(){
        $(this).parent().remove();
    });
    
    $( document ).on( 'click', '.item_popup_close', function(){
        $(this).parent().fadeOut();
    });
    
    $( document ).on( 'click', '.item_settings', function(){
        
        $item = $(this).parent();
        $data = $item.data();
        $current_item = $item;
        //console.log( $data );
        
        $popup = $( '.item_popup' ).show();
        $popup.find( 'h4' ).text( sb_sites[ $item.data( 'id' ) ][ 'name' ] + ' icon (Advanced settings)' );
        $cnt = $( '.item_popup_cnt' ).empty().append( '<table class="form-table"></table>' );
        $tbl = $cnt.find( 'table' );
        
        for( opt in $data ){
            
            if( opt.search( 'opt' ) != -1 ){
                
                opt_val = $data[ opt ];
                the_opt = opt.replace( 'opt_', '' );
                
                if( typeof sb_site_options[ the_opt ] === 'undefined' )
                    continue;
                
                $wrap = $( '<tr><th></th><td></td></tr>' );
                $checkbox = $( '<input type="checkbox" value="1" />' );
                $text = $( '<input type="text" class="widefat" />' );
                
                helper = sb_site_options[ the_opt ][ 'helper' ];
                type = sb_site_options[ the_opt ][ 'type' ];
                placeholder = ( 'placeholder' in sb_site_options[ the_opt ] ) ? sb_site_options[ the_opt ][ 'placeholder' ] : '';
                
                $the_input = $( '<i/>' );
                
                if( type == 'checkbox' ){
                    if( opt_val == '1' || opt_val == 'true' )
                        $checkbox.attr('checked', 'checked');
                    
                    $the_input = $checkbox.attr( 'data-id', the_opt );
                }
                
                if( type == 'text' ){
                    $the_input = $text.val( opt_val ).attr( 'data-id', the_opt );
                }
                
                $wrap.find( 'th' ).append( helper );
                $wrap.find( 'td' ).append( $the_input );
                
                if( placeholder != '' )
                    $wrap.find( 'td' ).append( '<small>' + placeholder + '</small>' );
                
                $tbl.append( $wrap );
                
            }
        }
        
    });
    
    $( document ).on( 'click', '.item_popup_save', function( e ){
        e.preventDefault();
        if( $( $current_item ).length ){
            $item = $( $current_item );
            $popup = $( this ).closest( '.item_popup' );
            $inputs = $popup.find( 'tr input' );
            
            $inputs.each( function(){
                
                $i = $( this );
                id = $i.data( 'id' );
                type = $i.attr( 'type' );
                value = '';
                
                if( type == 'checkbox' && $i.is( ':checked' ) )
                    value = '1';
                
                if( type == 'text' )
                    value = $i.val();
                
                $item.data( 'opt_' + id, value );
            });
            
            $popup.fadeOut();
            
        }
    });
    
    $( document ).on( 'keyup', '.list_search', function( e ){
        $list = $( $( this ).data( 'list' ) );
        if( $list.length > 0 ){
            val = $( this ).val();
            
            $list.children().each(function(){
                $item = $(this);
                text = $item.text().toLowerCase();
                if( text.search( val.toLowerCase() ) == -1 ){
                    $item.hide();
                }else{
                    $item.show();
                }
            });
        }
    });
    
    $( document ).on( 'change', '.sb_features_list', function( e ){
        $list = $( $( this ).data( 'list' ) );
        
        if( $list.length > 0 ){
            val = $( this ).val();
            
            $list.children().each(function(){
                $item = $(this);
                features_split = $item.data( 'features' ).split( ',' );

                if( $.inArray( val, features_split ) > -1 || val == 'all' ){
                    $item.show();
                }else{
                    $item.hide();
                }
            });
        }
    });
    
    $( document ).on( 'click', '.mini_section_select', function( e ){
        $parent = $(this).parent();
        $parent.find( '.mini_section' ).slideToggle();
        e.preventDefault();
    });
    
    $( document ).on( 'click', '.btn_shortcode', function(){
        this.select();
    });
    
    $( document ).on( 'click', '.fb_add', function(){
        $sel_list = $( '.fb_selected' );
        sel_val = $( '.fb_list' ).val();
        props = social_icons[ sel_val ];
        li_tmpl = window.li_template;
        
        li_tmpl = li_tmpl.replace( /%id%/g, sel_val );
        li_tmpl = li_tmpl.replace( /%color%/g, props[ 'colors' ][0] );
        li_tmpl = li_tmpl.replace( /%name%/g, props[ 'name' ] );
        li_tmpl = li_tmpl.replace( /%icon%/g, props[ 'icon' ] );
        li_tmpl = li_tmpl.replace( /%url%/g, '' );
        li_tmpl = li_tmpl.replace( /%iurl%/g, '' );
        li_tmpl = li_tmpl.replace( /%text%/g, '' );
        
        $sel_list.append( li_tmpl );
        
    });
    
    $( document ).on( 'click', '.fb_item_edit', function(e){
        e.preventDefault();
        var $to_open = $(this).closest( 'li' ).find( 'div' );
        $( '.fb_selected li > div' ).not($to_open).hide();
        $to_open.slideToggle();
    });
    
    var process_fb_editor = function(){
        
        cnt = [];
        prev = '';
        
        $( '.fb_selected li' ).each(function(){
           sid = $(this).data( 'id' );
           burl = $(this).find( '.fb_item_url' ).val();
           iurl = $(this).find( '.fb_icon_url' ).val();
           text = $(this).find( '.fb_btn_text' ).val();
           btn = {};
           
           btn[ sid ] = {
               'url': burl,
               'icon': iurl,
               'text': encodeURIComponent( text )
           };
           
           cnt.push( btn );
           
           // For preview
           pcolor = social_icons[ sid ][ 'colors' ][0];
           pname = social_icons[ sid ][ 'name' ];
           picon = social_icons[ sid ][ 'icon' ];
           
           prev += '<li style="background-color:' + pcolor + '" title="' + pname + '"><i class="' + picon + '"></i></li>';
           
        });
        
        template = btoa( JSON.stringify( cnt ) );
        $( '.fb_template' ).val( template );
        
        if( prev == '' && window.wpsr ){
            prev = '<span>' + window.wpsr.js_texts.fb_empty + '</span>';
        }
        
        return '<ul class="fb_preview">' + prev + '</ul>';
    }
    
    $( document ).on( 'click', '.fb_item_remove', function(e){
        e.preventDefault();
        $(this).closest( 'li' ).remove();
    });
    
    var process_tsb_editor = function(){
        selected = [];
        
        $( '.ssb_selected_list li' ).each(function(){
            selected.push( $(this).data( 'id' ) );
        });
        
        $( '.ssb_template' ).val( btoa( JSON.stringify( selected ) ) );
        
    }
    
    $( document ).on( 'click', '.ssb_add', function(e){
        e.preventDefault();
        $slist = $( '.ssb_selected_list' );
        $list = $( '.ssb_list' );
        var sel_val = $list.val();
        var color = $list.find('option:selected').data('color');
        
        $slist.find( '.ssb_empty' ).remove();
        $slist.append( '<li title="' + sb_sites[ sel_val ][ 'name' ] + '" data-id="' + sel_val + '" style="background-color:' + color + '"><i class="' + sb_sites[ sel_val ][ 'icon' ] + '"></i><span class="ssb_remove">x</span></li>' );
        
        process_tsb_editor();
        
    });
    
    $( document ).on( 'click', '.ssb_remove', function(){
        $(this).parent().remove();
        process_tsb_editor();
    });
    
    $( document ).on( 'click', '.fb_preview li', function(){
        alert( 'Please click "open editor" to rearrange the buttons' );
    });
    
    // Htip
    $( document ).on( 'mouseover', '[data-htip]', function(){
        $parent = $(this);
        $htip = $( '<div class="htip"></div>' );
        html = $parent.data( 'htip' );
        
        if( html.search( '://' ) != -1 ){
            $htip.html( '<img src="' + $parent.data( 'htip' ) + '"/>' );
        }else{
            $htip.html( $parent.data( 'htip' ) );
        }
        
        $htip.appendTo( 'body' ).hide();
        
        var set_position = function( $parent, $htip ){
            offset = $parent.offset();
            calc_left = offset.left - ( $htip.outerWidth()/2 ) + ($parent.outerWidth()/2);
            calc_top = offset.top - $htip.outerHeight() - ($parent.outerHeight()/2);
            
            $htip.css({
                'left': calc_left,
                'top': calc_top
            }).show();
        };
        
        if( $htip.find( 'img' ).length > 0 ){
            $htip.find( 'img' ).load(function(){
                set_position( $parent, $htip );
            });
        }else{
            set_position( $parent, $htip );
        }
        
    });
    
    $( document ).on( 'mouseleave', '[data-htip]', function(){
        $( '.htip' ).remove();
    });
    
    // Import data
    $( document ).on( 'submit', '#import_form', function( e ){
        e.preventDefault();
        
        var import_val = $(this).find( '[name="import_data"]' ).val();
        
        $.ajax({
            url: wpsr.ajaxurl,
            method: 'POST',
            data: {
                action: 'wpsr_import_ajax',
                import_data: import_val,
                _wpnonce: $(this).find( '[name="_wpnonce"]' ).val(),
            }
            
        }).done(function(d){
            if( d.search( /import_success/g ) != -1 ){
                $( '.notice-success' ).fadeIn();
            }
            if( d.search( /import_failed|auth_error/g ) != -1 ){
                $( '.notice-error' ).fadeIn();
            }
        });
    });
    
    //Button selector events
    $(document).on( 'click', '.sbox_inner li', function(){
        $wrap = $(this).closest( '.sbox_wrap' );
        $wrap.find('.sbox_val').html( $(this).html() );
        $wrap.find('.sbox_field').val( $(this).attr( 'data-val' ) );
        $(this).parent().hide();
    });
    
    $( document ).on( 'click', '.template_tab li', function(){
        id = $(this).index() + 1;
        $('.template_tab li').removeClass( 'templ_tab_active' );
        $('.template_wrap').hide();
        $('.template_wrap[data-id="' + id + '"]').fadeIn( 'slow' );
        $(this).addClass( 'templ_tab_active' );
    });
    
    $( document ).on( 'click', '.postbox h3', function(){
        $(this).next().fadeToggle( 'fast' );
        $(this).toggleClass( 'pbclosed' );
    });
    
    // Popup editor on click events
    $( document ).on( 'click', '.wpsr_ppe_save', function(){
        mode = $(this).data( 'mode' );
        
        if( self != top ){
            
            close_popup = true;
            
            cnt_id = $(this).data( 'cnt-id' );
            prev_id = $(this).data( 'prev-id' );
            
            cnt_val = '';
            prev_val = '';
            
            if( mode == 'widget' ){
                process_vedit();
                
                cnt_val = $( '#wpsr_pp_editor .veditor_content' ).val();
                prev_val = $( '#wpsr_pp_editor .veditor' )[0].outerHTML;
                
            }
            
            if( mode == 'followbar' ){
                
                $( '.fb_selected li' ).each(function(){
                   sid = $(this).data( 'id' );
                   burl = $(this).find( '.fb_item_url' ).val();
                   if( burl == '' ){
                       var uprompt = confirm( sid + ' does not have any URL set to follow. Please enter an URL by clicking edit.' );
                       close_popup = !uprompt;
                       $(this).addClass( 'not_set' );
                   }else{
                       $(this).removeClass( 'not_set' );
                   }
                });
                
                prev_val = process_fb_editor();
                cnt_val = $( '.fb_template' ).val();
            }
            
            window.parent.document.getElementById( cnt_id ).value = cnt_val;
            window.parent.document.getElementById( prev_id ).innerHTML = prev_val;
            
            if( window.parent.wpsr_ipopup_close && close_popup ){
                window.parent.wpsr_ipopup_close();
            }
            
        }
    });
    
    $( document ).on( 'click', '.wpsr_ppe_cancel', function(){
        if( window.parent.wpsr_ipopup_close ){
            window.parent.wpsr_ipopup_close();
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
    
    $( document ).on( 'click', '.close_changelog_btn', function(e){
        e.preventDefault();
        changelog_hide();
    });
    
    $( document ).on( 'click', '.close_setup_intro_btn', function(e){
        e.preventDefault();
        setup_hide();
    });
    
    $( document ).on( 'click', '.tblr_btn', function(){
        var id = $(this).data('id');
        $('[data-tglr="' + id + '"]').toggle();
    });
    
    $( document ).on( 'click', '.ft_toggle_btn', function(){
        var on = 'fa-toggle-on';
        var off = 'fa-toggle-off';
        var $btn = $(this);
        var $sel = $(this).prev();
        
        if( $btn.hasClass( on ) ){
            $btn.removeClass( on );
            $btn.addClass( off );
            $sel.val( 'disable' );
        }else{
            $btn.removeClass( off );
            $btn.addClass( on );
            $sel.val( 'enable' );
        }
        feature_toggle();
    });
    
    $( document ).on( 'change', '.coffee_amt', function(){
        var btn = $( '.buy_coffee_btn' );
        btn.attr( 'href', btn.data( 'link' ) + $(this).val() );
    });
    
    // Initinitinitinitinit
    init();
    
});
})( jQuery );

function wpsr_admin_tooltip( o ){
    
    if( !o.parent.is( document.wpsr_tt_parent ) ){
        wpsr_admin_tooltip_close();
    }else{
        return false;
    }
    
    $tt = jQuery('<div class="wpsr_tooltip_wrap"><i class="fa fa-times wpsr_tooltip_close" title="' + wpsr.js_texts.close + '"></i><div class="wpsr_tooltip_cnt"></div></div>');
    
    $parent = o.parent;
    document.wpsr_tt_parent = $parent;
    
    if( o.class ) $tt.addClass( o.class );
    if( o.width ) $tt.width( o.width );
    if( o.height ) $tt.height( o.height );
    if( o.name ) $tt.attr( 'data-name', o.name );
    
    $tt.css({
        position: 'absolute',
        top: $parent.offset().top + $parent.outerHeight(),
        left: $parent.offset().left
    });
    
    $tt.appendTo( 'body' );
    
    if( typeof o.content == 'object' ){
        
        $tt.addClass( 'loading' );
        
        jQuery.ajax(o.content).done(function(data){
            $tt.removeClass( 'loading' );
            $tt.find('.wpsr_tooltip_cnt').html( data );
            
            $footer = $tt.find( '.btn_settings_footer' );
            if( $footer.length > 0 ){
                $footer.appendTo( '.wpsr_tooltip_wrap' );
                $tt.find('.wpsr_tooltip_cnt').addClass( 'tt_has_footer' );
            }
            
            if( jQuery.fn.wpColorPicker ){
                jQuery( '.wp-color' ).wpColorPicker();
            }
            
            wpsr_init_image_selects();
            
        });
        
    }else{
        
        $tt.find('.wpsr_tooltip_cnt').html( o.content );
        
    }
    
    if( o.class && o.class.search( 'wpsr_tooltip_popup' ) != -1 )
        jQuery( 'body' ).addClass( 'hide_scrollbar' );
    
    // Positioning adjust
    winwid = jQuery(window).width();
    ttwid = $tt.offset().left + $tt.outerWidth();
    
    if( winwid < ttwid  ){
        $tt.css( 'margin-left', -(ttwid+70-winwid));
    }
    
    jQuery('.wpsr_tooltip_close').click(function(){
        wpsr_admin_tooltip_close();
    });
    
}

function wpsr_admin_tooltip_close(){
    jQuery('.wpsr_tooltip_close').off( 'click' );
    jQuery('.wpsr_tooltip_wrap').remove();
    jQuery( 'body' ).removeClass( 'hide_scrollbar' );
    document.wpsr_tt_parent = false;
}

function wpsr_list_selector_init(){
    
    item_width = jQuery( '.list_available li' ).first().width();
    
    jQuery( '.list_available li' ).draggable({
        revert: 'invalid',
        helper: 'clone',
        connectToSortable: '.list_selected',
        start: function(){
            jQuery( '.list_selected' ).addClass( 'drop_target' );
        },
        stop: function( e, ui ){
            jQuery( '.list_selected' ).removeClass( 'drop_target' );
            ui.helper.width( 'auto' );
        }
    });
    jQuery( '.list_selected' ).sortable().disableSelection();
    
}

function wpsr_sb_process_list( $list ){
    $input = jQuery( $list.data( 'input' ) );
    temp = [];
    
    $list.children().each(function(){
        $item = jQuery( this );
        id = $item.data( 'id' );
        data = $item.data();
        temp_item = {};
        temp_item[ id ] = {};
        
        for( d in data ){
            if( d.search( 'opt_' ) != -1 ){
                opt = d.replace( 'opt_', '' );
                val = encodeURIComponent(data[ d ]);
                
                temp_item[ id ][ opt ] = val;
            }
        }
        temp.push( temp_item );
    });
    
    //console.log( temp );
    $input.val( btoa( JSON.stringify( temp ) ) );
    
}

function wpsr_st_process_list( $list ){
    $input = jQuery( $list.data( 'input' ) );
    temp = [];
    
    $list.children().each(function(){
        temp.push( jQuery( this ).data( 'id' ) );
    });
    
    console.log( temp );
    $input.val( btoa( JSON.stringify( temp ) ) );
}

function wpsr_load_css( id, css ){
    if( !document.getElementById( id ) ){
        var head = document.getElementsByTagName('head')[0];
        var link = document.createElement('link');
        link.id = id;
        link.rel = 'stylesheet';
        link.type = 'text/css';
        link.href = css;
        link.media = 'all';
        head.appendChild(link);
    }
}

function wpsr_load_js( id, js, callback ){
    if( !jQuery.fn.trumbowyg ){
        jQuery.getScript( js, function(){
            callback();
        });
    }else{
        callback();
    }
}

function wpsr_sharebar_preview_close(){
    jQuery( '.sharebar_preview_iwrap' ).hide();
    jQuery( 'body' ).removeClass( 'hide_scrollbar' );
}

function wpsr_open_popup( url, title, w, h ){
    var left = ( screen.width/2 )-( w/2 ),
        top = ( screen.height/2 )-( h/2 );
        
    return window.open( url, title, 'toolbar=no,location=no,menubar=no,scrollbars=no,width='+w+',height='+h+',top='+top+',left='+left );
}

function wpsr_init_image_selects(){
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