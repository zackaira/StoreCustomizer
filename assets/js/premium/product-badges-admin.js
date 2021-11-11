/**
 * Product Badges admin js.
 */
( function( $ ) {
	jQuery( document ).ready( function ( e ) {
        var findActiveBadge = jQuery( '.pbadges-blocks' ).find( '.active' );
        var getSavedBadge = jQuery( '#wcz-saved-badge' );
        var previewInner = jQuery( '.wcz-pbadge-in' );
        var inBadge = jQuery( '.wcz-pbadge' );

        // Show / hide function according to badge selection
        wczBadgeType( findActiveBadge );

        // Set badge to Preview
        if ( 'custom' == previewInner.attr( 'data-badge') ) {
            var imgsrc = jQuery( '.wcz-upload-image img' ).attr( 'src' );
            if ( imgsrc ) {
                previewInner.html( '<img src="' + imgsrc + '">' );
            } else {
                previewInner.html( '<span class="dashicons dashicons-format-image wcz-custom-upload"></span>' );
            }
        } else {
            previewInner.html( findActiveBadge.html() );
            var txt = getSavedBadge.data( 'text' ).replace( '[amount]', '$10' );
            var newtxt = txt.replace( '[percent]', '10%' );
            jQuery( '.wcz-pbadge-in .wczbadge-inner span' ).html( newtxt );
        }

        // Color 1
        jQuery( '.wcz-pbadge-preview .bbgc1' ).css( 'background-color', getSavedBadge.data( 'bcolor' ) );
        jQuery( '.wcz-pbadge-preview .bbc1' ).css( 'box-shadow', '0 0 0 1px ' + getSavedBadge.data( 'bcolor' ) );
        jQuery( '.wcz-pbadge-preview .bblc1' ).css( 'border-left-color', getSavedBadge.data( 'bcolor' ) );
        jQuery( '.wcz-pbadge-preview .bbrc1' ).css( 'border-right-color', getSavedBadge.data( 'bcolor' ) );
        jQuery( '.wcz-pbadge-preview .bbtc1' ).css( 'border-top-color', getSavedBadge.data( 'bcolor' ) );
        jQuery( '.wcz-pbadge-preview .bbbc1' ).css( 'border-bottom-color', getSavedBadge.data( 'bcolor' ) );
        jQuery( '.wcz-pbadge-preview .bcbc1' ).css( { 'border-color' : getSavedBadge.data( 'bcolor' ), 'color' : getSavedBadge.data( 'bcolor' ) } );
        
        // Color 2
        jQuery( '.wcz-pbadge-preview .bfc2' ).css( 'color', getSavedBadge.data( 'fcolor' ) );

        if ( 'custom' == jQuery( '#wcz-pbadge-design' ).val() ) {
            previewInner.css( 'max-width', getSavedBadge.data( 'mwidth' ) + 'px' );
        }

        // Badge Position
        previewInner.css( getSavedBadge.data( 'horizpos' ), getSavedBadge.data( 'horizno' ) + 'px' );
        previewInner.css( getSavedBadge.data( 'vertpos' ), getSavedBadge.data( 'vertno' ) + 'px' );
        // Set Offset Position
        var posValue = jQuery( '#wcz-pbadge-position' ).val();
        wczBadgePosition( posValue );
        
        // Badge Selector
        jQuery( '.pbadges-block' ).on( 'click', function (e) {
            var blck = jQuery(this);
            jQuery( '.pbadges-block' ).removeClass(  'active'  );
            jQuery( '#wcz-pbadge-design' ).val( blck.data( 'badge' ) );
            blck.addClass( 'active' );

            wczBadgeType( blck );

            if ( blck.hasClass( 'custom' ) ) {
                previewInner.html('<img src="' + jQuery( '.wcz-upload-image img' ).attr( 'src' ) + '">');
            } else {
                // Set the defaults
                previewInner.html( blck.html() );
            }
            jQuery( '.wcz-pbadge-text' ).val( blck.find( 'span' ).html() );
            if ( 'custom' == jQuery( '#wcz-pbadge-design' ).val() ) {
                previewInner.css( 'max-width', getSavedBadge.data( 'mwidth' ) + 'px' );
            }
        });

        // Color Pickers
        jQuery( '.wcz-pbadge-bcolor' ).each( function(){
            jQuery( this ).wpColorPicker({
                change: function( event, ui ) {
                    var newColor = ui.color.toString();
                    getSavedBadge.attr( 'data-bcolor', newColor );
                    jQuery( '.wcz-pbadge-preview .bbgc1' ).css( 'background-color', newColor );
                    jQuery( '.wcz-pbadge-preview .bbc1' ).css( 'box-shadow', '0 0 0 1px ' + newColor );
                    jQuery( '.wcz-pbadge-preview .bbrc1' ).css( 'border-right-color', newColor );
                    jQuery( '.wcz-pbadge-preview .bblc1' ).css( 'border-left-color', newColor );
                    jQuery( '.wcz-pbadge-preview .bbtc1' ).css( 'border-top-color', newColor );
                    jQuery( '.wcz-pbadge-preview .bbbc1' ).css( 'border-bottom-color', newColor );
                    jQuery( '.wcz-pbadge-preview .bcbc1' ).css( { 'border-color' : newColor, 'color' : newColor } );
                }
            });
        });
        jQuery( '.wcz-pbadge-fcolor' ).each( function(){
            jQuery( this ).wpColorPicker({
                change: function( event, ui ) {
                    var newColor = ui.color.toString();
                    jQuery( '.wcz-pbadge-preview .bfc2' ).css( 'color', newColor );
                }
            });
        });

        // Edit Badge Text
        jQuery( '.wcz-pbadge-text' ).on('change keyup paste', function () {
            var txt = jQuery( this ).val().replace( '[amount]', '$10' );
            var newtxt = txt.replace( '[percent]', '10%' );
            jQuery( '.wcz-pbadge .wczbadge-inner span' ).html( newtxt );
            posValue = jQuery( '#wcz-pbadge-position' ).val();
            wczBadgePosition( posValue );
        });
        // Edit Badge Position
        jQuery( '#wcz-pbadge-position' ).on('change', function () {
            inBadge.removeClass( 'topright topcenter topleft middleright middlecenter middleleft bottomright bottomcenter bottomleft' );
            inBadge.addClass( jQuery( this ).val() );
            wczBadgePosition( jQuery( this ).val() );
        });
        // Edit Badge Horizontal Offset
        jQuery( '#wcz-pbadge-horiz-shop-offset-pos, .wcz-pbadge-horiz-shop-offset-no' ).on('change', function () {
            var badgehpos = jQuery( '#wcz-pbadge-horiz-shop-offset-pos' ).val();
            var badgehno = jQuery( '.wcz-pbadge-horiz-shop-offset-no' ).val();

            previewInner.css( { 'left' : '', 'right' : '' } );
            previewInner.css( badgehpos, badgehno + 'px' );
        });
        // Edit Badge Vertical Offset
        jQuery( '#wcz-pbadge-vert-shop-offset-pos, .wcz-pbadge-vert-shop-offset-no' ).on('change', function () {
            var badgevpos = jQuery( '#wcz-pbadge-vert-shop-offset-pos' ).val();
            var badgevno = jQuery( '.wcz-pbadge-vert-shop-offset-no' ).val();

            previewInner.css( { 'top' : '', 'bottom' : '' } );
            previewInner.css( badgevpos, badgevno + 'px' );
        });
        // Edit Badge Switch
        jQuery( '#wcz-pbadge-switch' ).on('change', function () {
            inBadge.toggleClass( 'switch' );
        });

        // Upload button click
        jQuery('body').on( 'click', '.wcz-custom-upload', function(e){
            e.preventDefault();
            var img = jQuery('.wcz-upload-image'),
            custom_uploader = wp.media({
                title: 'Insert image',
                library : {
                    // uploadedTo : wp.media.view.settings.post.id, // attach to the current post?
                    type : 'image'
                },
                button: {
                    text: 'Use this image' // button label text
                },
                multiple: false
            }).on('select', function() { // it also has "open" and "close" events
                var attachment = custom_uploader.state().get('selection').first().toJSON();
                jQuery('.wcz-custom-upload').hide();
                img.html('<img src="' + attachment.url + '">').next().val( attachment.id ).next().show();
                previewInner.html('<img src="' + attachment.url + '">');
            }).open();
        });
        // Remove button click
        jQuery('body').on('click', '#wcz-custom-rm', function(e){
            e.preventDefault();
            var img = jQuery('.wcz-upload-image');
            img.html( '' ).next().val( '' ).next().hide();
            previewInner.html( '<span class="dashicons dashicons-format-image wcz-custom-upload"></span>' );
            jQuery('.wcz-custom-upload').show();
        });
        // Uploaded Max-Width
        jQuery( '.wcz-pbadge-mwidth' ).on('change', function () {
            var newWidth = jQuery(this).val();
            if ( 'custom' == jQuery( '#wcz-pbadge-design' ).val() ) {
                previewInner.css( 'max-width', newWidth + 'px' );
            }
        });

        // Sticky Preview in ADMIN area
        var sticky = new Waypoint.Sticky({
            element: $( '#wcz-pbadge-preview' )[0],
            handler: function( direction ) {
                if ( direction == 'up' ) {
                    $( '#wcz-pbadge-preview' ).removeClass( 'wcz-stick-preview' ).css( 'width', $( '#wcz-pbadge-preview' ).parent().width() );
                } else {
                    $( '#wcz-pbadge-preview' ).addClass( 'wcz-stick-preview' ).css( 'width', $( '#wcz-pbadge-preview' ).parent().width() );
                }
            },
            offset: 55
        })
    });

    function wczBadgePosition( posValue ) {
        var inBadge = jQuery( '.wcz-pbadge' );
        // Inline CSS for badge position
        if ( 'topcenter' == posValue || 'middlecenter' == posValue || 'bottomcenter' == posValue ) {
            inBadge.css( 'margin-right', '-' + ( inBadge.outerWidth() / 2 ) + 'px' );
        } else {
            inBadge.css( 'margin-right', '0' );
        }
        if ( 'middleleft' == posValue || 'middlecenter' == posValue || 'middleright' == posValue ) {
            inBadge.css( 'margin-top', '-' + ( inBadge.outerHeight() / 2 ) + 'px' );
        } else {
            inBadge.css( 'margin-top', '0' );
        }
    }
    function wczBadgeType( typeVal ) {
        // Show / Hide Settings according to badge type
        if ( typeVal.hasClass( 'custom' ) ) {
            jQuery( '.wcz-custom' ).show();
            jQuery( '.wcz-normal' ).hide();
            jQuery( '.wcz-switch' ).hide();
        } else if ( typeVal.hasClass( 'canswitch' ) ) {
            jQuery( '.wcz-custom' ).hide();
            jQuery( '.wcz-normal' ).show();
            jQuery( '.wcz-switch' ).show();
            if ( typeVal.hasClass( 'notxt' ) ) {
                jQuery( '.wcz-notxt' ).hide();
            }
        } else {
            jQuery( '.wcz-custom' ).hide();
            jQuery( '.wcz-normal' ).show();
            jQuery( '.wcz-switch' ).hide();
            if ( typeVal.hasClass( 'notxt' ) ) {
                jQuery( '.wcz-notxt' ).hide();
            }
        }
    }

} )( jQuery );
