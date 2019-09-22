window.wpsr_helpers = {
    addClass: function( ele, className ){
        if ( ele.classList )
          ele.classList.add( className );
        else
          ele.className += ' ' + className;
    },
    
    removeClass: function( ele, className ){
        if (ele.classList)
            ele.classList.remove(className);
        else
            ele.className = ele.className.replace(new RegExp('(^|\\b)' + className.split(' ').join('|') + '(\\b|$)', 'gi'), ' ');
    },
    
    popup: function( url, target, w, h ){
        var left = ( screen.width/2 )-( w/2 );
        var top = ( screen.height/2 )-( h/2 );
        return window.open( url, target, 'toolbar=no,location=no,menubar=no,scrollbars=yes,width='+w+',height='+h+',top='+top+',left='+left );
    },
    
    ajax: function( ajax_url, method, send, callback, props ){
        
        var request = new XMLHttpRequest();
        request.props = props;
        request.open( method, ajax_url, true );
        
        if ( method == 'POST' )
            request.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8' );
        
        request.onreadystatechange = function(){
            if ( request.readyState == 4 && request.status == 200 ){
                return callback( request );
            }
        };
        
        request.send( send );
        
    },
    
    format_num: function( num ){
        
        if( num < 1000 )
            return num;
        
        var suffixes = ['k', 'm', 'b', 't' ];
        var final_no = num;

        for( var i=0; i< suffixes.length; i++ ){
            num = num/1000;
            
            if( num > 1000 ){
                continue;
            }else{
                final_no = (Math.round( num*100 )/100) + suffixes[i];
                break;
            }
        }
        
        return final_no;
        
    }
    
};

document.addEventListener( 'DOMContentLoaded', function(){
    
    // Socializer links
    scr_links = document.querySelectorAll( '.socializer.sr-popup a' );
    
    for( i=0; i<scr_links.length; i++ ){
        var atag = scr_links[i];
        atag.addEventListener( 'click', function(e){
            var href = this.getAttribute( 'href' );
            if( !( href == '#' || this.hasAttribute( 'onclick' ) ) ){
                wpsr_helpers.popup( href, '_blank', 800, 500 );
            }
            e.preventDefault();
        });
    }
    
    // Sharebar
    var the_sb = document.querySelector( '.wpsr-sharebar' );
    
    if( the_sb ){
        
        var hide_class = 'wpsr-mow';
        
        var sb_resize = function(){
            vlsb = document.querySelector( '.wpsr-sb-vl-scontent' );
            if( vlsb ){
                stick = vlsb.getAttribute( 'data-stickto' );
                stick_ele = document.querySelector( stick );
                if( stick_ele ){
                    vlsb.style.left = stick_ele.offsetLeft + 'px';
                }
            }
            
            if( the_sb && window.innerWidth <= 800 ){
                wpsr_helpers.addClass( the_sb, hide_class );
            }
        }
        
        sb_resize();
        window.addEventListener( 'resize', sb_resize );
        
        sb_close_btn = the_sb.querySelector( '.wpsr-sb-close' );
        sb_close_btn.addEventListener( 'click', function(){
            if( the_sb.classList.contains( hide_class ) ){
                wpsr_helpers.removeClass( the_sb, hide_class );
            }else{
                wpsr_helpers.addClass( the_sb, hide_class );
            }
        });
    }
    
    // Text sharebar
    tsb = document.querySelector( '.wpsr-text-sb' );
    
    if( tsb ){
        
        window.wpsr_tsb = {
            stext: '',
            startx: 0,
            starty: 0
        };
        
        var tsb_attr = {
            ptitle: tsb.getAttribute( 'data-title' ),
            purl: tsb.getAttribute( 'data-url' ),
            psurl: tsb.getAttribute( 'data-surl' ),
            ptuname: tsb.getAttribute( 'data-tuname' ),
            cnt_sel: tsb.getAttribute( 'data-content' ),
            word_count: tsb.getAttribute( 'data-tcount' ) 
        };
        
        var get_selection_text = function() {
            var text = '';
            if( window.getSelection ){
                text = window.getSelection().toString();
            }else if( document.selection && document.selection.type != 'Control' ){
                text = document.selection.createRange().text;
            }
            return text;
        };
        
        var tsb_show = function( x, y ){
            tsb.style.left = x + 'px';
            tsb.style.top = y + 'px';
            wpsr_helpers.addClass( tsb, 'wpsr-tsb-active' );
        };
        
        var tsb_hide = function(){
            wpsr_helpers.removeClass( tsb, 'wpsr-tsb-active' );
        };
        
        var sel_link_text = function(){
            var sel_text = wpsr_tsb.stext;
            var wcount = parseInt( tsb_attr.word_count );
            
            if( wcount == 0 ){
                return sel_text;
            }else{
                return sel_text.split( ' ' ).slice( 0, wcount ).join( ' ' );
            }
        };
        
        var replace_link = function( link ){
            var to_replace = {
                '{title}': tsb_attr.ptitle,
                '{url}': tsb_attr.purl,
                '{s-url}': tsb_attr.psurl,
                '{twitter-username}': tsb_attr.ptuname,
                '{excerpt}': sel_link_text()
            };
            
            for( var key in to_replace ){
                if( to_replace.hasOwnProperty( key ) ){
                    link = link.replace( RegExp( key, "g" ), to_replace[ key ] );
                }
            }
            
            return link;
            
        }
        
        if( tsb_attr.cnt_sel != '' ){
            
            var tsb_cnt_sel = tsb_attr.cnt_sel.replace( /[\[\]<>"'/\\=&%]/g,'' );
            var tsb_content = document.querySelectorAll( tsb_cnt_sel );
            
            for( var i = 0; i < tsb_content.length; i++ ){
                
                var content = tsb_content[i];
                
                content.addEventListener( 'mousedown', function(e){
                    wpsr_tsb.startx = e.pageX;
                    wpsr_tsb.starty = e.pageY;
                });
                
                content.addEventListener( 'mouseup', function(e){
                    var sel_text = get_selection_text();
                    
                    if( sel_text != '' ){
                        
                        tsb_x = ( e.pageX + parseInt( wpsr_tsb.startx ) )/2;
                        tsb_y = Math.min( wpsr_tsb.starty, e.pageY );
                        
                        if( sel_text != wpsr_tsb.stext ){
                            tsb_show( tsb_x, tsb_y );
                            wpsr_tsb.stext = sel_text;
                        }else{
                            tsb_hide();
                        }
                        
                    }else{
                        
                        tsb_hide();
                        
                    }
                });
            }
        }
        
        document.body.addEventListener( 'mousedown', function(e){
            tsb_hide();
        });
        
        tsb.addEventListener( 'mousedown', function(e){
            e.stopPropagation();
        });
        
        var atags = tsb.querySelectorAll( 'a' );
        for( var i = 0; i < atags.length; i++ ){
            var atag = atags[i];
            atag.addEventListener( 'click', function(e){
                var alink = this.getAttribute( 'data-link' );
                
                if( alink != '#' ){
                    rep_link = replace_link( alink );
                    wpsr_helpers.popup( rep_link, 800, 500 );
                }
                
                e.preventDefault();
            });
        }
        
    }
    
    // Mobile sharebar
    msb = document.querySelector( '.wpsr-mobile-sb' );
    if( msb ){
        if( msb.classList.contains( 'wpsr-mobile-hos' ) ){
            var lastScrollTop = 0;
            window.addEventListener( 'scroll', function(){
                var st = window.pageYOffset || document.documentElement.scrollTop;
                if ( st > lastScrollTop ){
                   wpsr_helpers.addClass( msb, 'wpsr-msb-hide' );
                } else {
                   wpsr_helpers.removeClass( msb, 'wpsr-msb-hide' );
                }
                lastScrollTop = st;
            }, false );
        }
    }
    
    // Followbar
    var fwb = document.querySelector( '.wpsr-followbar' );
    if( fwb ){
        fb_close_btn = fwb.querySelector( '.wpsr-fb-close' );
        fb_close_btn.addEventListener( 'click', function(){
            if( fwb.classList.contains( 'wpsr-mow' ) ){
                wpsr_helpers.removeClass( fwb, 'wpsr-mow' );
            }else{
                wpsr_helpers.addClass( fwb, 'wpsr-mow' );
            }
        });
    }
    
    // Features with minimize on width enabled
    mow = document.querySelectorAll( '[data-minonwidth]' );
    
    var mow_resize = function( ele, window_width ){
        var min_on_width = ele.getAttribute( 'data-minonwidth' );
        if( min_on_width > 0 ){
            if( window_width <= min_on_width ){
                wpsr_helpers.addClass( ele, 'wpsr-mow' );
            }else{
                wpsr_helpers.removeClass( ele, 'wpsr-mow' );
            }
        }
    }
    
    if( mow.length > 0 ){
        
        [ 'resize', 'load' ].forEach(function( the_event ){
            window.addEventListener( the_event, function(){
                for( i = 0; i < mow.length; i++ ){
                    var mowEle = mow[ i ];
                    mow_resize( mowEle, window.innerWidth );
                }
            });
        });
        
    }
    
    // Ajax share count
    if( wpsr_ajax_url ){
        
        var share_count = document.querySelectorAll( '[data-wpsrs]' );
        
        if( share_count.length > 0 ){
            
            var data = {};
            var ajax_url = wpsr_ajax_url + '?action=wpsr_share_count';
            
            for( i = 0; i < share_count.length; i++ ){
                var sEle = share_count[ i ];
                var url = sEle.getAttribute( 'data-wpsrs' );
                var services = sEle.getAttribute( 'data-wpsrs-svcs' ).split( ',' );
                
                if( !( url in data ) ){
                    data[ url ] = [];
                }
                
                for( j = 0; j < services.length; j++ ){
                    if( data[ url ].indexOf( services[j] ) === -1 ){
                        data[ url ].push( services[j] );
                    }
                }
                
            }
            
            var ajax_res = function( req ){
                
                var out = JSON.parse( req.responseText );
                var ph = document.querySelectorAll( '[data-wpsrs="' + req.props.forURL + '"]' );
                
                for( i = 0; i < ph.length; i++ ){
                    var phEle = ph[i];
                    var services = phEle.getAttribute( 'data-wpsrs-svcs' ).split( ',' );
                    var count = 0;
                    for( j = 0; j < services.length; j++ ){
                        var svc = services[j];
                        if( svc in out ){
                            count += parseInt( out[ svc ] ) || 0;
                        }
                    }
                    phEle.innerHTML = wpsr_helpers.format_num( count );
                }
                
            }
            
            for( var url in data ){
                if( data.hasOwnProperty( url ) ){
                    send_data = {
                        'url': url,
                        'services': data[ url ]
                    };
                    to_send = 'data=' + JSON.stringify( send_data );
                    
                    wpsr_helpers.ajax( ajax_url, 'POST', to_send, ajax_res, { forURL: url } );
                    
                }
            }
            
        }
        
    }
});

function socializer_addbookmark( e ){
    var ua = navigator.userAgent.toLowerCase();
    var isMac = (ua.indexOf('mac') != -1), str = '';
    e.preventDefault();
    str = (isMac ? 'Command/Cmd' : 'CTRL') + ' + D';
    alert('Press ' + str + ' to bookmark this page');
}

function socializer_shortlink( e, t ){
    e.preventDefault();
    link = t.getAttribute( 'href' );
    if( link != '#' )
        prompt( 'Short link', link );
}