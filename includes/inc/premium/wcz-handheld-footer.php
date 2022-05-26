<?php
/**
 * Enqueue WCD Menu Cart styling.
 */
function wcz_load_frontend_hhfb_scripts() {
    wp_enqueue_style( 'dashicons' );
    wp_enqueue_style( 'wcz-handheld-footer-css', WCD_PLUGIN_URL . "/assets/css/premium/handheld-footerbar.css", array(), WCD_PLUGIN_VERSION );
    wp_enqueue_script( 'wcz-handheld-footer-js', WCD_PLUGIN_URL . "/assets/js/premium/handheld-footerbar.js", array( 'jquery' ), WCD_PLUGIN_VERSION );
}
add_action( 'wp_enqueue_scripts', 'wcz_load_frontend_hhfb_scripts' );

/**
 * Enqueue WCD Ajax Search customizer scripts.
 */
function wcz_load_customizer_hhfb_scripts() {
	wp_enqueue_script( 'wcz-customizer-handheld-footer-js', WCD_PLUGIN_URL . "/includes/customizer/customizer-library/js/premium/customizer-handheld-fb.js", array('jquery'), WCD_PLUGIN_VERSION, true );
}
add_action( 'customize_controls_enqueue_scripts', 'wcz_load_customizer_hhfb_scripts' );

/**
 * Menu Cart.
 */
if ( ! function_exists( 'wcz_hhmb_link_fragment' ) ) {
	/**
	 * Cart Fragments.
	 *
	 * Ensure cart contents update when products are added to the cart via AJAX.
	 *
	 * @param array $fragments Fragments to refresh via AJAX.
	 * @return array Fragments to refresh via AJAX.
	 */
	function wcz_hhmb_link_fragment( $fragments ) {
		ob_start();
		wcz_woocommerce_cart_count();
		$fragments['span.wcz-hhmbcart'] = ob_get_clean();

		return $fragments;
	}
}
add_filter( 'woocommerce_add_to_cart_fragments', 'wcz_hhmb_link_fragment' );

if ( ! function_exists( 'wcz_woocommerce_cart_count' ) ) {
	/**
	 * Cart Link.
	 *
	 * Displayed a link to the cart including the number of items present and the cart total.
	 *
	 * @return void
	 */
	function wcz_woocommerce_cart_count() {
        $cart_itemno = WC()->cart ? WC()->cart->get_cart_contents_count() : 0; ?>
        <span class="wcz-hhmbcart">
            <?php echo esc_html( '(' . $cart_itemno . ')' ); ?>
        </span><?php
	}
}

/**
 * Add Handheld Footer Bar to site.
 */
function wcz_add_handheld_footer_bar() {
    if (get_option( 'wcz-handheld-only-wc', woocustomizer_library_get_default( 'wcz-handheld-only-wc' ) ) && !is_woocommerce()) return;

    if (is_admin()) return; ?>
    <div class="wcz-handheld-footerbar <?php echo get_option( 'wcz-handheld-footerbar-on', woocustomizer_library_get_default( 'wcz-handheld-footerbar-on' ) ); ?>">
        <div class="wcz-handheld-footerbar-inner">
            <?php if ( !get_option( 'wcz-handheld-remove-account', woocustomizer_library_get_default( 'wcz-handheld-remove-account' ) ) ) : ?>
                <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="wcz-handheld-link">
                    <span class="dashicons dashicons-admin-users"></span>
                    <?php if ( get_option( 'wcz-handheld-add-titles', woocustomizer_library_get_default( 'wcz-handheld-add-titles' ) ) ) : ?>
                        <div class="wcz-handheld-title">
                            <?php echo esc_html( get_the_title( wc_get_page_id( 'myaccount' ) ) ); ?>
                        </div>
                    <?php endif; ?>
                </a>
            <?php endif; ?>
            
            <?php if ( !get_option( 'wcz-handheld-remove-search', woocustomizer_library_get_default( 'wcz-handheld-remove-search' ) ) ) : ?>
                <div class="wcz-handheld-link wcz-handheld-search">
                    <span class="dashicons dashicons-search"></span>
                    <?php if ( get_option( 'wcz-handheld-add-titles', woocustomizer_library_get_default( 'wcz-handheld-add-titles' ) ) ) : ?>
                        <div class="wcz-handheld-title">
                            <?php echo esc_html_e( 'Search', 'woocustomizer' ); ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php
            if ( !get_option( 'wcz-handheld-remove-cart', woocustomizer_library_get_default( 'wcz-handheld-remove-cart' ) ) ) :
                $cart_itemno = WC()->cart ? WC()->cart->get_cart_contents_count() : 0; ?>
                <a href="<?php echo esc_url( wc_get_page_permalink( 'cart' ) ); ?>" class="wcz-handheld-link">
                    <?php if ( get_option( 'wcz-handheld-add-cart-count', woocustomizer_library_get_default( 'wcz-handheld-add-cart-count' ) ) ) : ?>
                        <div class="wcz-handheld-cart">
                            <span class="wcz-handheld-no wcz-hhmbcart"><?php echo esc_html( '(' . $cart_itemno . ')' ); ?></span>
                            <span class="dashicons dashicons-cart"></span>
                        </div>
                    <?php else: ?>
                        <span class="dashicons dashicons-cart"></span>
                    <?php endif; ?>
                    <?php if ( get_option( 'wcz-handheld-add-titles', woocustomizer_library_get_default( 'wcz-handheld-add-titles' ) ) ) : ?>
                        <div class="wcz-handheld-title">
                            <?php echo esc_html( get_the_title( wc_get_page_id( 'cart' ) ) ); ?>
                        </div>
                    <?php endif; ?>
                </a>
            <?php endif; ?>

            <?php
            if ( get_option( 'wcz-add-handheld-link-one', woocustomizer_library_get_default( 'wcz-add-handheld-link-one' ) ) ) :
                $wcz_hhpl_one = get_option( 'wcz-handheld-link-page-one', get_option( 'page_on_front' ) );
                $wcz_hhpl_one_nt = get_option( 'wcz-handheld-link-page-one-newtab', woocustomizer_library_get_default( 'wcz-handheld-link-page-one-newtab' ) ) ? '_blank' : '_self'; ?>
                <a href="<?php echo 'custom' == get_option( 'wcz-handheld-link-page-one-to', woocustomizer_library_get_default( 'wcz-handheld-link-page-one-to' ) ) && '' != get_option( 'wcz-handheld-link-page-one-url', woocustomizer_library_get_default( 'wcz-handheld-link-page-one-url' ) ) ? esc_url( get_option( 'wcz-handheld-link-page-one-url', woocustomizer_library_get_default( 'wcz-handheld-link-page-one-url' ) ) ) : esc_url( get_page_link( $wcz_hhpl_one ) ); ?>" class="wcz-handheld-link" target="<?php echo esc_attr( $wcz_hhpl_one_nt ); ?>">
                    <span class="dashicons dashicons-<?php echo sanitize_html_class( get_option( 'wcz-handheld-link-icon-one', woocustomizer_library_get_default( 'wcz-handheld-link-icon-one' ) ) ); ?>"></span>
                    <?php if ( get_option( 'wcz-handheld-add-titles', woocustomizer_library_get_default( 'wcz-handheld-add-titles' ) ) ) : ?>
                        <div class="wcz-handheld-title">
                            <?php echo esc_html( get_option( 'wcz-handheld-link-title-one', woocustomizer_library_get_default( 'wcz-handheld-link-title-one' ) ) ); ?>
                        </div>
                    <?php endif; ?>
                </a>
            <?php endif; ?>

            <?php
            if ( get_option( 'wcz-add-handheld-link-two', woocustomizer_library_get_default( 'wcz-add-handheld-link-two' ) ) ) :
                $wcz_hhpl_two = get_option( 'wcz-handheld-link-page-two', get_option( 'page_on_front' ) );
                $wcz_hhpl_two_nt = get_option( 'wcz-handheld-link-page-two-newtab', woocustomizer_library_get_default( 'wcz-handheld-link-page-two-newtab' ) ) ? '_blank' : '_self'; ?>
                <a href="<?php echo 'custom' == get_option( 'wcz-handheld-link-page-two-to', woocustomizer_library_get_default( 'wcz-handheld-link-page-two-to' ) ) && '' != get_option( 'wcz-handheld-link-page-two-url', woocustomizer_library_get_default( 'wcz-handheld-link-page-two-url' ) ) ? esc_url( get_option( 'wcz-handheld-link-page-two-url', woocustomizer_library_get_default( 'wcz-handheld-link-page-two-url' ) ) ) : esc_url( get_page_link( $wcz_hhpl_two ) ); ?>" class="wcz-handheld-link" target="<?php echo esc_attr( $wcz_hhpl_two_nt ); ?>">
                    <span class="dashicons dashicons-<?php echo sanitize_html_class( get_option( 'wcz-handheld-link-icon-two', woocustomizer_library_get_default( 'wcz-handheld-link-icon-two' ) ) ); ?>"></span>
                    <?php if ( get_option( 'wcz-handheld-add-titles', woocustomizer_library_get_default( 'wcz-handheld-add-titles' ) ) ) : ?>
                        <div class="wcz-handheld-title">
                            <?php echo esc_html( get_option( 'wcz-handheld-link-title-two', woocustomizer_library_get_default( 'wcz-handheld-link-title-two' ) ) ); ?>
                        </div>
                    <?php endif; ?>
                </a>
            <?php endif; ?>

            <?php
            if ( get_option( 'wcz-add-handheld-link-three', woocustomizer_library_get_default( 'wcz-add-handheld-link-three' ) ) ) :
                $wcz_hhpl_three = get_option( 'wcz-handheld-link-page-three', get_option( 'page_on_front' ) );
                $wcz_hhpl_three_nt = get_option( 'wcz-handheld-link-page-three-newtab', woocustomizer_library_get_default( 'wcz-handheld-link-page-three-newtab' ) ) ? '_blank' : '_self'; ?>
                <a href="<?php echo 'custom' == get_option( 'wcz-handheld-link-page-three-to', woocustomizer_library_get_default( 'wcz-handheld-link-page-three-to' ) ) && '' != get_option( 'wcz-handheld-link-page-three-url', woocustomizer_library_get_default( 'wcz-handheld-link-page-three-url' ) ) ? esc_url( get_option( 'wcz-handheld-link-page-three-url', woocustomizer_library_get_default( 'wcz-handheld-link-page-three-url' ) ) ) : esc_url( get_page_link( $wcz_hhpl_three ) ); ?>" class="wcz-handheld-link" target="<?php echo esc_attr( $wcz_hhpl_three_nt ); ?>">
                    <span class="dashicons dashicons-<?php echo sanitize_html_class( get_option( 'wcz-handheld-link-icon-three', woocustomizer_library_get_default( 'wcz-handheld-link-icon-three' ) ) ); ?>"></span>
                    <?php if ( get_option( 'wcz-handheld-add-titles', woocustomizer_library_get_default( 'wcz-handheld-add-titles' ) ) ) : ?>
                        <div class="wcz-handheld-title">
                            <?php echo esc_html( get_option( 'wcz-handheld-link-title-three', woocustomizer_library_get_default( 'wcz-handheld-link-title-three' ) ) ); ?>
                        </div>
                    <?php endif; ?>
                </a>
            <?php endif; ?>

            <?php
            if ( get_option( 'wcz-add-handheld-link-four', woocustomizer_library_get_default( 'wcz-add-handheld-link-four' ) ) ) :
                $wcz_hhpl_four = get_option( 'wcz-handheld-link-page-four', get_option( 'page_on_front' ) );
                $wcz_hhpl_four_nt = get_option( 'wcz-handheld-link-page-four-newtab', woocustomizer_library_get_default( 'wcz-handheld-link-page-four-newtab' ) ) ? '_blank' : '_self'; ?>
                <a href="<?php echo 'custom' == get_option( 'wcz-handheld-link-page-four-to', woocustomizer_library_get_default( 'wcz-handheld-link-page-four-to' ) ) && '' != get_option( 'wcz-handheld-link-page-four-url', woocustomizer_library_get_default( 'wcz-handheld-link-page-four-url' ) ) ? esc_url( get_option( 'wcz-handheld-link-page-four-url', woocustomizer_library_get_default( 'wcz-handheld-link-page-four-url' ) ) ) : esc_url( get_page_link( $wcz_hhpl_four ) ); ?>" class="wcz-handheld-link" target="<?php echo esc_attr( $wcz_hhpl_four_nt ); ?>">
                    <span class="dashicons dashicons-<?php echo sanitize_html_class( get_option( 'wcz-handheld-link-icon-four', woocustomizer_library_get_default( 'wcz-handheld-link-icon-four' ) ) ); ?>"></span>
                    <?php if ( get_option( 'wcz-handheld-add-titles', woocustomizer_library_get_default( 'wcz-handheld-add-titles' ) ) ) : ?>
                        <div class="wcz-handheld-title">
                            <?php echo esc_html( get_option( 'wcz-handheld-link-title-four', woocustomizer_library_get_default( 'wcz-handheld-link-title-four' ) ) ); ?>
                        </div>
                    <?php endif; ?>
                </a>
            <?php endif; ?>
        </div>

        <?php if ( !get_option( 'wcz-handheld-remove-search', woocustomizer_library_get_default( 'wcz-handheld-remove-search' ) ) ) : ?>
            <div class="wcz-handheld-searchbar">
                <?php
                $wcz_hhfb_placeholder = get_option( 'wcz-handheld-search-placeholder', woocustomizer_library_get_default( 'wcz-handheld-search-placeholder' ) );
                if ( get_option( 'wcz-handheld-use-wcz-search', woocustomizer_library_get_default( 'wcz-handheld-use-wcz-search' ) ) ) :
                    echo do_shortcode( '[woocustomizer_ajax_search placeholder="' . esc_attr( $wcz_hhfb_placeholder ) . '" ]' );
                else :
                    get_search_form();
                endif; ?>
            </div>
        <?php endif; ?>
    </div><?php
}
add_action( 'wp_footer', 'wcz_add_handheld_footer_bar' );

/**
 * Remove Storefront Theme handheld menu bar.
 */
function wcz_remove_storefront_handheld_footerbar() {
    if ( 'storefront' == get_option( 'template' ) ) {
        remove_action( 'storefront_footer', 'storefront_handheld_footer_bar', 999 );
    }
}
add_action( 'init', 'wcz_remove_storefront_handheld_footerbar' );
