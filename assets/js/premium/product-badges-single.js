/**
 *  @package StoreCustomizer/JS
 */
( function( $ ) {
    var thisPage = jQuery( 'body.single-product div.product' );

    // jQuery( document ).ready( function ( e ) {
        
    // });
    
    jQuery( window ).on('load',function () {
        jQuery( '.woocommerce-product-gallery__wrapper' ).before( '<div class="wcz-pbwrap"></div>' );

        thisPage.find( '.wcz-pbadge.mbadge' ).each( function( index, value ) {
            var pBadge = jQuery( this );
            var bsElement = pBadge.data( 'belement' ) ? pBadge.data( 'belement' ) : '.wcz-pbwrap';

            // console.log( bsElement );

            setTimeout(function () {
                pBadge.clone().prependTo( bsElement );
                if ( '.wcz-pbwrap' !== bsElement ) {
                    thisPage.find( '.wcz-pbadge.mbadge' ).parent().css( 'position', 'relative' );
                }
                jQuery( '.wcz-pbwrap' ).height( thisPage.parent().find( '.flex-viewport' ).height() );
            }, 200);
            pBadge.remove();
        });

        setTimeout(function () {
            jQuery( '.wcz-pbadge.mbadge' ).each( function( index, value ) {
                var thisBadge = jQuery( this );
                var posValue = thisBadge.data( 'posval' );
                
                if ( 'topcenter' == posValue || 'middlecenter' == posValue || 'bottomcenter' == posValue ) {
                    thisBadge.css( 'margin-right', '-' + ( thisBadge.outerWidth() / 2 ) + 'px' );
                } else {
                    thisBadge.css( 'margin-right', '0' );
                }
                if ( 'middleleft' == posValue || 'middlecenter' == posValue || 'middleright' == posValue ) {
                    thisBadge.css( 'margin-top', '-' + ( thisBadge.outerHeight() / 2 ) + 'px' );
                } else {
                    thisBadge.css( 'margin-top', '0' );
                }
            });
        }, 200);
        
        setTimeout(function () {
            jQuery( 'body' ).removeClass( 'wcz-pbhide' );
        }, 600);
    });
    jQuery( window ).resize(function() {
        jQuery( '.wcz-pbwrap' ).height( thisPage.parent().find( '.flex-viewport' ).height() );
    });
} )( jQuery );
