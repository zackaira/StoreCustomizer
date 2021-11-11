<?php
/**
 * Enqueue WC Sticky AddToCart styling.
 */
function wcz_load_frontend_satc_scripts() {
    if ( !is_product() || !get_option( 'wcz-enable-stickcart', woocustomizer_library_get_default( 'wcz-enable-stickcart' ) ) )
        return;

    wp_enqueue_style( 'wcz-sticky-addtocart-css', WCD_PLUGIN_URL . "/assets/css/premium/sticky-addtocart.css", array(), WCD_PLUGIN_VERSION );
    wp_enqueue_script( 'wcz-sticky-addtocart-js', WCD_PLUGIN_URL . "/assets/js/premium/sticky-addtocart.js", array(), WCD_PLUGIN_VERSION );
}
add_action( 'wp_enqueue_scripts', 'wcz_load_frontend_satc_scripts' );

/**
 * Add Sticky AddToCart to Product Page.
 */
function wcz_add_sticky_atc() {
    if ( !is_product() || !get_option( 'wcz-enable-stickcart', woocustomizer_library_get_default( 'wcz-enable-stickcart' ) ) )
        return;
    
    $product = wc_get_product( get_the_ID() );

    $show_satc = false;
    if ( $product->is_purchasable() && $product->is_in_stock() ) {
        $show_satc = true;
    } elseif ( $product->is_type( 'external' ) ) {
        $show_satc = true;
    }

    if ( ! $show_satc )
        return;
    
    $wczatc_layout = get_option( 'wcz-stickcart-layout', woocustomizer_library_get_default( 'wcz-stickcart-layout' ) ); ?>
    <section class="wcz-sticky-addtocart wcz_sticky_addtocart-hide <?php echo sanitize_html_class( $wczatc_layout ); ?> <?php echo sanitize_html_class( get_option( 'wcz-stickcart-position', woocustomizer_library_get_default( 'wcz-stickcart-position' ) ) ); ?>">
        <div class="wcz-sticky-addtocart-inner">
            <div class="wcz-sticky-addtocart-left">
                <?php if ( !get_option( 'wcz-stickcart-remimg', woocustomizer_library_get_default( 'wcz-stickcart-remimg' ) ) ) : ?>
                    <?php if ( 'wcz-stickcart-one' == $wczatc_layout ) : ?>
                        <div class="wcz-sticky-addtocart-img">
                            <?php echo wp_kses_post( woocommerce_get_product_thumbnail() ); ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
                <div class="wcz-sticky-addtocart-info">
                    <span class="wcz-sticky-addtocart-title">
                        <strong><?php the_title(); ?></strong>
                    </span>
                    <?php if ( !get_option( 'wcz-stickcart-remprice', woocustomizer_library_get_default( 'wcz-stickcart-remprice' ) ) ) : ?>
                        <?php if ( 'wcz-stickcart-one' == $wczatc_layout ) : ?>
                            <span class="wcz-sticky-addtocart-price"><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php echo wp_kses_post( wc_get_rating_html( $product->get_average_rating() ) ); ?>
                </div>
            </div>
            <div class="wcz-sticky-addtocart-right">
                <?php if ( !get_option( 'wcz-stickcart-remprice', woocustomizer_library_get_default( 'wcz-stickcart-remprice' ) ) ) : ?>
                    <?php if ( 'wcz-stickcart-two' == $wczatc_layout ) : ?>
                        <span class="wcz-sticky-addtocart-price"><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
                    <?php endif; ?>
                <?php endif; ?>
                <a class="wcz-sticky-addtocart-button button alt" rel="nofollow">
                    <?php echo esc_attr( $product->add_to_cart_text() ); ?>
                </a>
                <?php if ( !get_option( 'wcz-stickcart-remimg', woocustomizer_library_get_default( 'wcz-stickcart-remimg' ) ) ) : ?>
                    <?php if ( 'wcz-stickcart-two' == $wczatc_layout ) : ?>
                        <div class="wcz-sticky-addtocart-img">
                            <?php echo wp_kses_post( woocommerce_get_product_thumbnail() ); ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </section><?php
}
add_action( 'wp_footer', 'wcz_add_sticky_atc' );
