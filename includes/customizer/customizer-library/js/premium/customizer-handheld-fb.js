/**
 * StoreCustomizer Ajax Search Custom JS
 */
( function( $ ) {
    $( document ).ready( function () {

        wcz_handheld_showsearch_select();
        $( '#customize-control-wcz-handheld-search-display select' ).on( 'change', function() {
            wcz_handheld_showsearch_select();
        });
        function wcz_handheld_showsearch_select() {
            if ( 'default' == $( '#customize-control-wcz-handheld-search-display select' ).val() ) {
                console.log('ONE', $( '#customize-control-wcz-handheld-search-display select' ).val());
                $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-search-shortcode' ).hide();
            } else {
                console.log('TWOOO', $( '#customize-control-wcz-handheld-search-display select' ).val());
                $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-search-shortcode' ).show();
            }
        }
        // Custom Link - One
        wcz_handheld_fb_link_one();
        $( '#customize-control-wcz-add-handheld-link-one input[type=checkbox]' ).on( 'change', function() {
            wcz_handheld_fb_link_one();
        });
        function wcz_handheld_fb_link_one() {
            if ( $( '#customize-control-wcz-add-handheld-link-one input[type=checkbox]' ).is( ':checked' ) ) {
                $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-link-icon-one' ).show();
                $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-link-title-one' ).show();
                $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-link-page-one-to' ).show();
            } else {
                $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-link-icon-one' ).hide();
                $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-link-title-one' ).hide();
                $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-link-page-one-to' ).hide();
            }
        }
        wcz_handheld_fb_link_one_select();
        $( '#customize-control-wcz-handheld-link-page-one-to select, #customize-control-wcz-add-handheld-link-one input[type=checkbox]' ).on( 'change', function() {
            wcz_handheld_fb_link_one_select();
        });
        function wcz_handheld_fb_link_one_select() {
            if ( $( '#customize-control-wcz-add-handheld-link-one input[type=checkbox]' ).is( ':checked' ) ) {
                if ( 'custom' == $( '#customize-control-wcz-handheld-link-page-one-to select' ).val() ) {
                    $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-link-page-one' ).hide();
                    $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-link-page-one-url' ).show();
                    $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-link-page-one-newtab' ).show();
                } else {
                    $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-link-page-one' ).show();
                    $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-link-page-one-url' ).hide();
                    $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-link-page-one-newtab' ).hide();
                }
            } else {
                $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-link-page-one' ).hide();
                $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-link-page-one-url' ).hide();
                $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-link-page-one-newtab' ).hide();
            }
        }

        // Custom Link - Two
        wcz_handheld_fb_link_two();
        $( '#customize-control-wcz-add-handheld-link-two input[type=checkbox]' ).on( 'change', function() {
            wcz_handheld_fb_link_two();
        });
        function wcz_handheld_fb_link_two() {
            if ( $( '#customize-control-wcz-add-handheld-link-two input[type=checkbox]' ).is( ':checked' ) ) {
                $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-link-icon-two' ).show();
                $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-link-title-two' ).show();
                $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-link-page-two-to' ).show();
            } else {
                $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-link-icon-two' ).hide();
                $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-link-title-two' ).hide();
                $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-link-page-two-to' ).hide();
            }
        }
        wcz_handheld_fb_link_two_select();
        $( '#customize-control-wcz-handheld-link-page-two-to select, #customize-control-wcz-add-handheld-link-two input[type=checkbox]' ).on( 'change', function() {
            wcz_handheld_fb_link_two_select();
        });
        function wcz_handheld_fb_link_two_select() {
            if ( $( '#customize-control-wcz-add-handheld-link-two input[type=checkbox]' ).is( ':checked' ) ) {
                if ( 'custom' == $( '#customize-control-wcz-handheld-link-page-two-to select' ).val() ) {
                    $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-link-page-two' ).hide();
                    $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-link-page-two-url' ).show();
                    $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-link-page-two-newtab' ).show();
                } else {
                    $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-link-page-two' ).show();
                    $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-link-page-two-url' ).hide();
                    $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-link-page-two-newtab' ).hide();
                }
            } else {
                $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-link-page-two' ).hide();
                $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-link-page-two-url' ).hide();
                $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-link-page-two-newtab' ).hide();
            }
        }
        // Custom Link - Three
        wcz_handheld_fb_link_three();
        $( '#customize-control-wcz-add-handheld-link-three input[type=checkbox]' ).on( 'change', function() {
            wcz_handheld_fb_link_three();
        });
        function wcz_handheld_fb_link_three() {
            if ( $( '#customize-control-wcz-add-handheld-link-three input[type=checkbox]' ).is( ':checked' ) ) {
                $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-link-icon-three' ).show();
                $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-link-title-three' ).show();
                $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-link-page-three-to' ).show();
            } else {
                $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-link-icon-three' ).hide();
                $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-link-title-three' ).hide();
                $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-link-page-three-to' ).hide();
            }
        }
        wcz_handheld_fb_link_three_select();
        $( '#customize-control-wcz-handheld-link-page-three-to select, #customize-control-wcz-add-handheld-link-three input[type=checkbox]' ).on( 'change', function() {
            wcz_handheld_fb_link_three_select();
        });
        function wcz_handheld_fb_link_three_select() {
            if ( $( '#customize-control-wcz-add-handheld-link-three input[type=checkbox]' ).is( ':checked' ) ) {
                if ( 'custom' == $( '#customize-control-wcz-handheld-link-page-three-to select' ).val() ) {
                    $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-link-page-three' ).hide();
                    $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-link-page-three-url' ).show();
                    $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-link-page-three-newtab' ).show();
                } else {
                    $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-link-page-three' ).show();
                    $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-link-page-three-url' ).hide();
                    $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-link-page-three-newtab' ).hide();
                }
            } else {
                $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-link-page-three' ).hide();
                $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-link-page-three-url' ).hide();
                $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-link-page-three-newtab' ).hide();
            }
        }
        // Custom Link - Four
        wcz_handheld_fb_link_four();
        $( '#customize-control-wcz-add-handheld-link-four input[type=checkbox]' ).on( 'change', function() {
            wcz_handheld_fb_link_four();
        });
        function wcz_handheld_fb_link_four() {
            if ( $( '#customize-control-wcz-add-handheld-link-four input[type=checkbox]' ).is( ':checked' ) ) {
                $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-link-icon-four' ).show();
                $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-link-title-four' ).show();
                $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-link-page-four-to' ).show();
            } else {
                $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-link-icon-four' ).hide();
                $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-link-title-four' ).hide();
                $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-link-page-four-to' ).hide();
            }
        }
        wcz_handheld_fb_link_four_select();
        $( '#customize-control-wcz-handheld-link-page-four-to select, #customize-control-wcz-add-handheld-link-four input[type=checkbox]' ).on( 'change', function() {
            wcz_handheld_fb_link_four_select();
        });
        function wcz_handheld_fb_link_four_select() {
            if ( $( '#customize-control-wcz-add-handheld-link-four input[type=checkbox]' ).is( ':checked' ) ) {
                if ( 'custom' == $( '#customize-control-wcz-handheld-link-page-four-to select' ).val() ) {
                    $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-link-page-four' ).hide();
                    $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-link-page-four-url' ).show();
                    $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-link-page-four-newtab' ).show();
                } else {
                    $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-link-page-four' ).show();
                    $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-link-page-four-url' ).hide();
                    $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-link-page-four-newtab' ).hide();
                }
            } else {
                $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-link-page-four' ).hide();
                $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-link-page-four-url' ).hide();
                $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-link-page-four-newtab' ).hide();
            }
        }

        // Cart Link Option
        wcz_handheld_cart_no();
        $( '#customize-control-wcz-handheld-remove-cart input[type=checkbox]' ).on( 'change', function() {
            wcz_handheld_cart_no();
        });
        function wcz_handheld_cart_no() {
            if ( $( '#customize-control-wcz-handheld-remove-cart input[type=checkbox]' ).is( ':checked' ) ) {
                $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-add-cart-count' ).hide();
            } else {
                $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-add-cart-count' ).show();
            }
        }

        // Search Options
        wcz_handheld_search_use();
        $( '#customize-control-wcz-handheld-use-wcz-search input[type=checkbox]' ).on( 'change', function() {
            wcz_handheld_search_use();
        });
        function wcz_handheld_search_use() {
            if ( $( '#customize-control-wcz-handheld-use-wcz-search input[type=checkbox]' ).is( ':checked' ) ) {
                $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-search-placeholder' ).show();
            } else {
                $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-search-placeholder' ).hide();
            }
        }

        // Edit Design
        wcz_handheld_colors();
        $( '#customize-control-wcz-handheld-edit-design input[type=checkbox]' ).on( 'change', function() {
            wcz_handheld_colors();
        });
        function wcz_handheld_colors() {
            if ( $( '#customize-control-wcz-handheld-edit-design input[type=checkbox]' ).is( ':checked' ) ) {
                $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-title-size' ).show();
                $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-titles-uppercase' ).show();
                $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-bar-bgcolor' ).show();
                $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-bar-fontcolor' ).show();
                $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-bar-hovercolor' ).show();
                $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-icon-size' ).show();
            } else {
                $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-title-size' ).hide();
                $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-titles-uppercase' ).hide();
                $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-bar-bgcolor' ).hide();
                $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-bar-fontcolor' ).hide();
                $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-bar-hovercolor' ).hide();
                $( '#sub-accordion-section-wcz-panel-wcz-handheld-fb #customize-control-wcz-handheld-icon-size' ).hide();
            }
        }

    });
} )( jQuery );
