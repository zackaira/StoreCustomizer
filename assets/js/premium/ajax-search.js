/**
 * Plugin Template Ajax Search js.
 *
 *  @package StoreCustomizer/JS
 */
( function( $ ) {
    jQuery( document ).ready( function () {
        
        jQuery( '.wcz-ajax-search-block' ).each(function (s) {
            var wczas_id = jQuery( this );
            var wczas_s_id = 'wcz-as-id-'+s;
            wczas_id.attr( 'id', wczas_s_id );

            var wczas_input = wczas_id.find( '.wcz-s' );
            var wcz_minchars = wczas_id.data( 'minchars' );

            if ( ! jQuery( '.wcz-search-results-block' ).length ) {
                jQuery( '.wcz-ajax-search-block' ).append( '<div class="wcz-search-results-block"></div>' );
            }

            wczas_id.focusin( function() {

                jQuery( '#' + wczas_s_id ).on( 'keyup', this, function (e) {
                    var thisAs = jQuery( this );

                    if ( e.which <= 90 && e.which >= 48 || e.which >= 96 && e.which <= 105 || e.which == 8 ) { // Only character keys & numbers
                        
                        var wcz_as_val = wczas_input.val();

                        if ( wcz_as_val.length >= wcz_minchars ) {
                            // Start loading functionality

                            jQuery.ajax({
                                type: 'POST',
                                url: wcz_ajaxsearch.ajax_url,
                                dataType: 'html',
                                data: {
                                    'action': 'wcz_ajax_search_get_products',
                                    'search_for': wcz_as_val,
                                },
                                beforeSend: function ( result ) {

                                    thisAs.find( '.wcz-search-results-block' ).addClass( 'wcz-as-loading' );
                        
                                },
                                success: function ( result ) {

                                    thisAs.find( '.wcz-search-results-block' ).removeClass( 'wcz-as-loading' );
                        
                                    thisAs.find( '.wcz-search-results-block' ).html( result );
                        
                                },
                                error: function () {
                                    // console.log( "No Posts retrieved" );
                                }
                            }); // End of ajax function
                        
                        } else {
                            // Remove loading functionality
                            thisAs.find( '.wcz-search-results-block' ).html( '' );
                        }

                    } // Only character keys & numbers - if(
                    
                }); // End .on( 'keyup' )

            }).focusout( function() {
                setTimeout(function () {
                    jQuery( '.wcz-ajax-search-block' ).find( '.wcz-search-results-block' ).html( '' );
                }, 200);
            });
            
        });

        jQuery( '.wcz-s-submit' ).on( 'click', function () {
            var form = $( this ).closest( 'form' );
            if ( form.find( '.wcz-s' ).val() == '' ) {
                return false;
            }
            return true;
        });

    });

    // jQuery( window ).on('load',function () {});

} )( jQuery );
