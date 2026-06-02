

    class WPH_Class  {
        
            constructor() {
                this.SiteScanProgress_interval = false;
                this.AJAX_data  =   false;
                
                this.adminInit();  
            }
            
            adminInit() {
                
                var self = this;
                
                jQuery( document ).ready( function() {
                    if ( jQuery( '.submenu.wph-highlight').length > 0 )
                        jQuery( '.submenu.wph-highlight').closest( 'li' ).addClass('wph-current');
                        
                    
                    jQuery(document).on('click', '.gc-header', function(e) {
                            self.wphToggle(e.currentTarget);
                        });
                    
                    jQuery('#wph-toggle-all').on('click', function(e) { self.wphToggleAll(e.currentTarget); });
 
                })
                
            }
            
            selectText(node) 
                {
                    
                    node = document.getElementById(node);

                    if (document.body.createTextRange) {
                        const range = document.body.createTextRange();
                        range.moveToElementText(node);
                        range.select();
                    } else if (window.getSelection) {
                        const selection = window.getSelection();
                        const range = document.createRange();
                        range.selectNodeContents(node);
                        selection.removeAllRanges();
                        selection.addRange(range);
                    } else {
                        console.warn("Could not select text in node: Unsupported browser.");
                    }
                }
                
            showAdvanced( element )
                {
                    jQuery( element ).closest('.wph_input').find('div.advanced').show('fast');
                    jQuery( element ).closest('.wph_anotice').slideUp('fast', function() { jQuery(this).hide()  });
                    
                    
                }
                
            randomWord( element, extension ) 
                {
                    var length  =   7;
                    var consonants = 'bcdfghjlmnpqrstv',
                        vowels = 'aeiou',
                        rand = function(limit) {
                            return Math.floor(Math.random()*limit);
                        },
                        i, word='', length = parseInt(length,10),
                        consonants = consonants.split(''),
                        vowels = vowels.split('');
                        
                    for (i=0;i<length/2;i++) 
                        {
                            var randConsonant = consonants[rand(consonants.length)],
                                randVowel = vowels[rand(vowels.length)];
                            word += randConsonant;
                            word += i*2<length-1 ? randVowel : '';
                        }
                    
                    if ( extension != '' )
                        word    =   word.concat( '.' + extension );
                    
                    jQuery( element ).closest('.wph_input').find('.entry input.text').val( word );                    
                }
                
            
            clear ( element )
                {
                    jQuery( element ).closest('.wph_input').find('.entry input.text').val( '' );    
                }
                
                
            confirm_sample_setup()
                {
                    
                    var agree   =   confirm( wph_vars.confirm_message );
                    if (agree)
                        {
                            jQuery('#wph-run-sample-setup').submit();
                        }
                        else
                        {
                            return false ;
                        }        
                }
                
                
            check_headers( nonce )
                {
                    jQuery('#wph-check-headers .spinner').css( 'visibility', 'visible');
                    
                    jQuery('#wph-headers-container').html('');
                    jQuery('#wph-graph .wph-graph-data').html( 'Loading..' );
                    jQuery('#wph-graph .wph-graph-progress').css( 'transform', 'rotate(0deg)')
                    
                    jQuery.ajax({
                        type: 'POST',
                        url: ajaxurl,
                        dataType: "json",
                        data: {
                            'action':'wph_check_headers',
                            'nonce' : nonce
                        },
                        success:function(data) {
                            jQuery('#wph-check-headers .spinner').css( 'visibility', 'hidden');
                            jQuery('#wph-headers-container').html( data.html );
                            jQuery('#wph-graph .wph-graph-data').html( data.graph.message );
                            jQuery('#wph-graph .wph-graph-progress').css( 'transform', 'rotate(' +  data.graph.value +'deg)')
                        },  
                        error: function(errorThrown){
                            jQuery('#wph-check-headers .spinner').css( 'visibility', 'hidden');
                            jQuery('#wph-headers-container').html( 'Unable to call AJAX.' );
                            jQuery('#wph-graph .wph-graph-data').html( data.graph.message );
                            jQuery('#wph-graph .wph-graph-progress').css( 'transform', 'rotate(' + data.graph.value + 'deg);')
                        }
                    });
                }
                
                
            runSampleHeaders ()
                {
                    var agree   =   confirm( wph_vars.run_sample_headers );
                    if ( !agree )
                        return false;
                        
                    document.getElementById("wph-form").submit();  
                    
                }
                
                
            
            site_scan( nonce )
                {
                    if ( jQuery('#wph-site-scan-button').hasClass( 'disabled' ) )
                        return;
                    
                    jQuery('#wph-site-scan-button').addClass( 'disabled' );
                    jQuery('#security-scan #scan_overview .spinner').css( 'visibility', 'visible');
                    jQuery('#security-scan #scan_overview .working').css( 'display', 'inline-block');
                    
                    jQuery('#wph-scan-score .passed span').html('0');
                    jQuery('#wph-scan-score .failed span').html('0');
                    
                    /*
                    jQuery('#wph-graph .wph-graph-progress' ).css( 'transform', 'rotate(0deg)' );
                    jQuery('#wph-graph .wph-graph-data b' ).html( '0%' );
                    jQuery('#scan_overview .protection' ).html( 'Unknown' );
                    */
                    WPH.setGraph( 0 );
                    jQuery('#scan_overview .protection' ).html( 'Unknown' );
                    
                    jQuery('#security-scan #all-scann-items .gc-header .spinner').css( 'visibility', 'visible');
                    jQuery('#security-scan #all-scann-items .gc-header .gc-item .status').removeClass( 'status-fail', 'status-ignore', 'status-pass' ).addClass( 'status-unknown' ).html( 'Unknow' );
                                        
                    jQuery('#all-scann-items div.gc').not('.ajax_updated').each ( function ( ) {
                        jQuery(this).addClass('unknown');
                        jQuery(this).find('.issue_info').html('');
                        jQuery(this).find('.issue_description').html('');
                        jQuery(this).find('.issue_actions').html('');
                    })
                    
                    WPH.wphToggleAll( jQuery('#wph-toggle-all'), 'close');

                    var Response        =   '';
                    
                    jQuery.ajax({
                        type: 'POST',
                        url: ajaxurl,
                        dataType: "html",
                        data: {
                            'action':'wph_site_scan',
                            'nonce' : nonce
                        },
                        success:function(data) {

                            //jQuery('#wph-site-scan-button').removeClass( 'disabled' );
                            jQuery('#security-scan #scan_overview  p.new-items').removeClass( 'new-items' );
                            jQuery('#security-scan #scan_overview .spinner').css( 'visibility', 'hidden');
                            jQuery('#security-scan #scan_overview .working').css( 'display', 'none');

                            setTimeout ( function(){ location.reload(); }, 2000);
                            
                        },  
                        error: function(errorThrown){
                            //jQuery('#wph-site-scan-button').removeClass( 'disabled' );
                            jQuery('#security-scan #scan_overview .spinner').css( 'visibility', 'hidden');
                            jQuery('#security-scan #scan_overview .working').css( 'display', 'none');
                            
                            clearInterval( WPH.SiteScanProgress_interval );
                        }
                    });
                    
                    setTimeout( function() { WPH.site_scan_progress_start( nonce ) }, 3000 );
                    
                }
            
            site_scan_progress_start ( nonce )
                {
                    this.SiteScanProgress_interval =   setInterval( function() { WPH.site_scan_progres( nonce ) }, 2000);
                }    
                
            site_scan_progres ( nonce )
                {
                    
                    jQuery.ajax({
                        type: 'POST',
                        url: ajaxurl,
                        dataType: "json",
                        data: {
                            'action':'wph_site_scan_progress',
                            'nonce' : nonce
                        },
                        success:function(data) {
                            
                            WPH.AJAX_data  =   data;
                            
                            jQuery('#security-scan #scan_overview .working .progress' ).html( data.items_progress );
                            jQuery('#wph-scan-score .passed span').html( data.success );
                            jQuery('#wph-scan-score .failed span').html( data.failed );
                            
                            WPH.setGraph( data.progress );
                            jQuery('#scan_overview .protection' ).html( data.protection );
                            
                            if ( data.scann_in_progress  ==   false )
                                clearInterval( WPH.SiteScanProgress_interval );
                                
                            jQuery('#all-scann-items div.gc').not('.ajax_updated').each ( function ( ) {
                                var item_id =   jQuery(this).attr('id');
                                var el_item_id     =   item_id.replace("item-", "")
                                if ( eval ( "WPH.AJAX_data.results." + el_item_id  )  != undefined )    
                                    {
                                        var item_response   =   eval ( "WPH.AJAX_data.results." + el_item_id  );
                                        
                                        jQuery('#' + item_id ).removeClass('valid-item');
                                        
                                        if ( item_response.status  != undefined )
                                            {    
                                                jQuery('#' + item_id ).addClass( item_response.status );
                                                
                                                jQuery('#' + item_id ).removeClass( 'unknown' );
                                                
                                                if ( item_response.status == true )
                                                    {
                                                        jQuery('#' + item_id ).addClass('item-pass');
                                                        jQuery('#' + item_id ).removeClass('item-fail');
                                                        
                                                        jQuery('#' + item_id + ' .gc-header').removeClass('status-fail');
                                                        jQuery('#' + item_id + ' .gc-header').addClass('status-pass');
                                                        jQuery('#' + item_id + ' .gc-header span.status').removeClass('status-fail');
                                                        jQuery('#' + item_id + ' .gc-header span.status').addClass('status-pass');
                                                        jQuery('#' + item_id + ' .gc-header span.status').text( 'Passed' );
                                                    }
                                                else if ( item_response.status == false )
                                                    {
                                                        jQuery('#' + item_id ).addClass('item-fail');
                                                        jQuery('#' + item_id ).removeClass('item-pass');
                                                        
                                                        jQuery('#' + item_id + ' .gc-header').addClass('status-fail');
                                                        jQuery('#' + item_id + ' .gc-header').removeClass('status-pass');
                                                        jQuery('#' + item_id + ' .gc-header span.status').removeClass('status-pass');
                                                        jQuery('#' + item_id + ' .gc-header span.status').addClass('status-fail');
                                                        jQuery('#' + item_id + ' .gc-header span.status').text( 'Failed' );
                                                    }
                                            }
                                        
                                        jQuery('#' + item_id + " .issue_info").html( '' );
                                        if ( item_response.info  != undefined )
                                            {    
                                                jQuery('#' + item_id + " .issue_info").html( item_response.info );
                                            }
                                        
                                        jQuery('#' + item_id + " .issue_description").html( '' );
                                        if ( item_response.description  != undefined )
                                            {    
                                                jQuery('#' + item_id + " .issue_description").html( item_response.description );
                                            }
                                            
                                        jQuery('#' + item_id + " .issue_actions").html( '' );
                                        if ( item_response.actions  != undefined )
                                            {    
                                                jQuery('#' + item_id + " .issue_actions").html( item_response.actions );
                                            }
                                            
                                        jQuery('#' + item_id ).addClass('ajax_updated');
                                        jQuery('#' + item_id + ' .gc-header .spinner').css( 'visibility', 'hidden');
                                                                                
                                    }
                                
                            })
                            
                        },  
                        error: function(errorThrown){
                            jQuery('#scan_overview .wph_results')
                                .find('p.error').remove()
                                .end()
                                .append('<p class="error">Error while retrieving the AJAX update.</p>');
                        }
                    });
                }
                
                
            scan_ignore_item ( item_id, nonce )
                {
                    jQuery('#item-' + item_id + ' .gc-header .spinner').css( 'visibility', 'visible');
                    
                    jQuery.ajax({
                        type: 'POST',
                        url: ajaxurl,
                        dataType: "json",
                        data: {
                            'action'    :   'wph_site_scan_ignore',
                            'item_id'   :   item_id,
                            'nonce'     :   nonce
                        },
                        success:function( data ) {
                            
                            jQuery('#item-' + data.item_id ).appendTo("#hidden-items");
                            jQuery('#scan_overview .protection' ).html( data.protection );
                            WPH.setGraph( data.progress );
                            jQuery('#wph-scan-score .passed span' ).html( data.success );
                            jQuery('#wph-scan-score .failed span' ).html( data.failed );
                            
                            jQuery('#item-' + item_id + ' .gc-header .spinner').css( 'visibility', 'hidden');
                            
                            jQuery('html, body').animate({
                                scrollTop: jQuery("#scan_overview").offset().top - 200
                            }, 500);
                        },  
                        error: function(errorThrown){
                            jQuery('#item-' + item_id + ' .gc-header .spinner').css( 'visibility', 'hidden');
                        }
                    });
                    
                }
                
                
            scan_restore_item ( item_id, nonce )
                {
                    jQuery('#item-' + item_id + ' .gc-header .spinner').css( 'visibility', 'visible');
                    
                    jQuery.ajax({
                        type: 'POST',
                        url: ajaxurl,
                        dataType: "json",
                        data: {
                            'action'    :   'wph_site_scan_restore',
                            'item_id'   :   item_id,
                            'nonce'     :   nonce
                        },
                        success:function( data ) {
                                                        
                            var $newItem = jQuery('#item-' + data.item_id);
                            var $lastFail = jQuery('#scann-items .gc.item-fail').last();
                            if ($lastFail.length) {
                                $newItem.insertAfter($lastFail);
                            } else {
                                // If no failed items, append at the beginning or end
                                jQuery('#item-' + data.item_id ).appendTo("#scann-items");
                            }
                            
                            
                            jQuery('#wph-graph .wph-graph-data' ).html("<b>" + data.progress + "%</b><br>" + data.protection );
                            jQuery('#scan_overview .protection' ).html( data.protection );
                            WPH.setGraph( data.progress );
                            jQuery('#wph-scan-score .passed span' ).html( data.success );
                            jQuery('#wph-scan-score .failed span' ).html( data.failed );
                            
                            jQuery('#item-' + item_id + ' .gc-header span.status-ignore').remove();
                            
                            jQuery('#item-' + item_id + ' .gc-header .spinner').css( 'visibility', 'hidden');
                            
                            jQuery('html, body').animate({
                                scrollTop: jQuery("#scan_overview").offset().top - 200
                            }, 500);
                        },  
                        error: function(errorThrown){
                            jQuery('#item-' + item_id + ' .gc-header .spinner').css( 'visibility', 'hidden');
                        }
                    });
                    
                }
                
            setGraph ( percent ) 
                {
                  const progress = document.querySelector('.progress');
                  const text = document.querySelector('.value');

                  const offset = 100 - percent;

                  progress.style.strokeDashoffset = offset;
                  text.textContent = percent + '%';
                }    
            
                
            captcha_test () 
                {
                    jQuery( '#api_test' ).val( 'true' );
                    jQuery( '#api_test' ).closest('form').requestSubmit();
                }
                
            
            wphToggle( element )
                {
                    var $toggle = jQuery(element);
                    var $content = $toggle.closest('.gc').find('.gc-body');
                    var $icon = $toggle.find('.gc-item.toggle .dashicons');

                    $content.slideToggle(200);
                    $icon.toggleClass('dashicons-arrow-down-alt2 dashicons-arrow-up-alt2');
                }
                
            wphToggleAll ( element, force )
                {
                    var area    = jQuery('.gc-area');
                    var items   =   jQuery( area ).find('.gc');
                    var first_element_toggle   =   jQuery( items ).first().find('.gc-header .wph-toggle span');
                    
                    var doClose = ( force === 'close' ) ? true
                                : ( force === 'open'  ) ? false
                                : jQuery( first_element_toggle ).hasClass( 'dashicons-arrow-up-alt2');
                    
                    if ( doClose )
                        {
                            //close all
                            jQuery( items ).find('.gc-header .wph-toggle span.dashicons').removeClass(function(index, className) {
                                                                                                                                return className
                                                                                                                                    .split(/\s+/)
                                                                                                                                    .filter(c => c !== 'dashicons')
                                                                                                                                    .join(' ');
                                                                                                                            });
                            jQuery( items ).find('.gc-header .wph-toggle span.dashicons').addClass('dashicons-arrow-down-alt2');
                            jQuery( items ).find('.gc-body').slideUp(200);
                        }
                        else
                        {
                            //open all
                            jQuery( items ).find('.gc-header .wph-toggle span.dashicons').removeClass(function(index, className) {
                                                                                                                                return className
                                                                                                                                    .split(/\s+/)
                                                                                                                                    .filter(c => c !== 'dashicons')
                                                                                                                                    .join(' ');
                                                                                                                            });
                            jQuery( items ).find('.gc-header .wph-toggle span.dashicons').addClass('dashicons-arrow-up-alt2');
                            jQuery( items ).find('.gc-body').slideDown(200);
                        }
                }
            
    }
    
    var WPH = new WPH_Class();
    
    
    jQuery( document ).ready( function() {
        if (jQuery.fn.tipsy) {
            jQuery('.tips').tipsy({fade: false, gravity: 's', html: true });    
        }
    })
