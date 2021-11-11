<?php
/**
 * Enqueue WCD Catalogue Mode scripts.
 */
function wcz_load_frontend_pb_scripts() {
    wp_enqueue_style( 'wcz-product-badges', WCD_PLUGIN_URL . '/assets/css/premium/product-badges.css', array(), WCD_PLUGIN_VERSION );

    if ( is_shop() || is_product_category() || is_product_tag() || is_product() || is_cart() ) {
        wp_enqueue_script( 'wcz-product-badges', WCD_PLUGIN_URL . '/assets/js/premium/product-badges.js', array( 'jquery' ), WCD_PLUGIN_VERSION, true );
    }

    if ( is_product() ) {
        wp_enqueue_script( 'wcz-product-badges-single', WCD_PLUGIN_URL . '/assets/js/premium/product-badges-single.js', array( 'jquery', 'flexslider' ), WCD_PLUGIN_VERSION, true );
    }
}
add_action( 'wp_enqueue_scripts', 'wcz_load_frontend_pb_scripts' );
/**
 * Enqueue admin styling.
 */
function wcz_pb_admin_scripts() {
    global $post;
    global $pagenow;
    // Load in Badge Post Type list screen
    if ( $pagenow == 'edit.php' && ( isset( $post->post_type ) && $post->post_type == 'wcz-badges' ) ) :
        wp_enqueue_style( 'wcz-product-badges-css', WCD_PLUGIN_URL . "/assets/css/premium/product-badges.css", array(), WCD_PLUGIN_VERSION );
        $wcz_badge_cols_css = 'th#wcz-badge-col, th.column-wcz-badge-col, td.column-wcz-badge-col, th#wcz-def-badge-col, th.column-wcz-def-badge-col, td.column-wcz-def-badge-col { width: 200px; text-align: center; }
                            .wcz-badge-col-inner { display: flex; align-items: center; justify-content: center; height: 80px; }
                            .wcz-badge-col-inner img { width: 70px; height: auto; }
                            th#date { width: 200px; }
                            .wcz-badge-col-inner.wcz-pbadge { position: relative; }
                            span.wcz-is-default { width: 15px; height: 15px; background-color: #1873aa; border-radius: 15px; }';
        wp_register_style( 'wcz-pbadges-cols', false );
        wp_enqueue_style( 'wcz-pbadges-cols' );
        wp_add_inline_style( 'wcz-pbadges-cols', $wcz_badge_cols_css );
    endif;
    // Load in Dashboard Post Type Edit screen
    if ( ( $pagenow == 'post-new.php' || $pagenow == 'post.php' ) && ( isset( $post->post_type ) && $post->post_type == 'wcz-badges' ) ) :
        wp_enqueue_media();
		wp_enqueue_style( 'wp-color-picker');
        wp_enqueue_script( 'wp-color-picker');
        wp_enqueue_style( 'wcz-product-badges-css', WCD_PLUGIN_URL . "/assets/css/premium/product-badges.css", array(), WCD_PLUGIN_VERSION );
        wp_enqueue_style( 'wcz-product-badges-admin-css', WCD_PLUGIN_URL . '/assets/css/premium/product-badges-admin.css', array( 'wp-color-picker' ), WCD_PLUGIN_VERSION );
        // Load WayPoints for Badge Preview in dashboard
        wp_enqueue_script( 'wcz-product-badges-waypoints', WCD_PLUGIN_URL . '/assets/js/premium/waypoints/jquery.waypoints.min.js', array( 'jquery' ), WCD_PLUGIN_VERSION );
        wp_enqueue_script( 'wcz-product-badges-waypoints-sticky', WCD_PLUGIN_URL . '/assets/js/premium/waypoints/jquery.waypoints-sticky.min.js', array( 'wcz-product-badges-waypoints' ), WCD_PLUGIN_VERSION );
        wp_enqueue_script( 'wcz-product-badges-admin-js', WCD_PLUGIN_URL . '/assets/js/premium/product-badges-admin.js', array( 'jquery', 'wp-color-picker', 'wcz-product-badges-waypoints-sticky' ), WCD_PLUGIN_VERSION );
    endif;
}
add_action( 'admin_enqueue_scripts', 'wcz_pb_admin_scripts' );

/* -- Custom Post Types -- */
function create_post_type() {
    register_post_type( 'wcz-badges',
        array(
            'labels' => array(
                'name' => __( 'Product Badges', 'woocustomizer' ),
                'singular_name' => __( 'Product Badges', 'woocustomizer' )
            ),
            'public' => true,
            'show_in_menu' => 'edit.php?post_type=product',
            'menu_icon' => 'dashicons-editor-paste-text',
            'rewrite' => array( 'slug' => 'wcz-badges' ),
            'show_in_rest' => true,
            'supports' => array( 'title' ),
        )
    );
}
add_action( 'init', 'create_post_type' );

/**
 * Create Badges Post Meta Boxes
 */
function wcz_add_pbadge_metabox() {
    add_meta_box( 'wcz-pbadge-mbox', __( 'Product Badge Settings', 'woocustomizer' ), 'wcz_pbadge_settings', 'wcz-badges', 'normal', null );
    add_meta_box( 'wcz-pbadge-helper', __( 'Steps to adding a badge', 'woocustomizer' ), 'wcz_pbadge_help_metabox', 'wcz-badges', 'side', null );
    add_meta_box( 'wcz-pbadge-preview', __( 'Product Preview', 'woocustomizer' ), 'wcz_pbadge_preview', 'wcz-badges', 'side', null );
}
add_action( 'add_meta_boxes', 'wcz_add_pbadge_metabox' );

/**
 * Create the Settings box
 */
function wcz_pbadge_settings( $object ) {
    
    wp_nonce_field( basename( __FILE__ ), 'wcz-pbadges-nonce' );
    
    $wcz_pbadge_design = get_post_meta( $object->ID, 'wcz-pbadge-design', true );
    $wcz_pbadge_color = get_post_meta( $object->ID, 'wcz_pbadge_color', true );
    $wcz_pbadge_font_color = get_post_meta( $object->ID, 'wcz_pbadge_font_color', true );
    $wcz_pbadge_text = get_post_meta( $object->ID, 'wcz-pbadge-text', true );
    $wcz_pbadge_position = get_post_meta( $object->ID, 'wcz-pbadge-position', true );
    $wcz_pbadge_prod_position = get_post_meta( $object->ID, 'wcz-pbadge-prod-position', true );
    $wcz_pbadge_belement = get_post_meta( $object->ID, 'wcz-pbadge-belement', true );
    $wcz_pbadge_bselement = get_post_meta( $object->ID, 'wcz-pbadge-bselement', true );
    
    $wcz_pbadge_horiz_shopoffset = get_post_meta( $object->ID, 'wcz-pbadge-horiz-shop-offset', true ) ? get_post_meta( $object->ID, 'wcz-pbadge-horiz-shop-offset', true ) : 'right|0';
    $wcz_pbadge_horiz_shopoffset_arr = explode( '|', $wcz_pbadge_horiz_shopoffset );
    $wcz_pbadge_vert_shopoffset = get_post_meta( $object->ID, 'wcz-pbadge-vert-shop-offset', true ) ? get_post_meta( $object->ID, 'wcz-pbadge-vert-shop-offset', true ) : 'top|0';
    $wcz_pbadge_vert_shopoffset_arr = explode( '|', $wcz_pbadge_vert_shopoffset );

    $wcz_pbadge_horiz_prodoffset = get_post_meta( $object->ID, 'wcz-pbadge-horiz-prod-offset', true ) ? get_post_meta( $object->ID, 'wcz-pbadge-horiz-prod-offset', true ) : 'right|0';
    $wcz_pbadge_horiz_prodoffset_arr = explode( '|', $wcz_pbadge_horiz_prodoffset );
    $wcz_pbadge_vert_prodoffset = get_post_meta( $object->ID, 'wcz-pbadge-vert-prod-offset', true ) ? get_post_meta( $object->ID, 'wcz-pbadge-vert-prod-offset', true ) : 'top|0';
    $wcz_pbadge_vert_prodoffset_arr = explode( '|', $wcz_pbadge_vert_prodoffset );

    $wcz_pbadge_switch = get_post_meta( $object->ID, 'wcz-pbadge-switch', true );
    $wcz_pbadge_prod_switch = get_post_meta( $object->ID, 'wcz-pbadge-prod-switch', true );
    
    $wcz_uploaded_badge = get_post_meta( $object->ID, 'wcz-upmedia', true );
    $wcz_pbadge_mwidth = get_post_meta( $object->ID, 'wcz-pbadge-mwidth', true ); ?>

    <div class="wcz-pbadge-settings">

        <input type="hidden" id="wcz-saved-badge"
            data-design="<?php echo $wcz_pbadge_design ? esc_attr( $wcz_pbadge_design ) : 'one'; ?>"
            data-text="<?php echo $wcz_pbadge_text ? esc_attr( $wcz_pbadge_text ) : esc_attr( 'Discount!' ); ?>"
            data-bcolor="<?php echo esc_attr( $wcz_pbadge_color ); ?>"
            data-fcolor="<?php echo esc_attr( $wcz_pbadge_font_color ); ?>"
            data-position="<?php echo esc_attr( $wcz_pbadge_position ); ?>"
            data-horizpos="<?php echo esc_attr( $wcz_pbadge_horiz_shopoffset_arr[0] ); ?>"
            data-horizno="<?php echo esc_attr( $wcz_pbadge_horiz_shopoffset_arr[1] ); ?>"
            data-vertpos="<?php echo esc_attr( $wcz_pbadge_vert_shopoffset_arr[0] ); ?>"
            data-vertno="<?php echo esc_attr( $wcz_pbadge_vert_shopoffset_arr[1] ); ?>"
            data-cimg="<?php echo $wcz_uploaded_badge ? esc_attr( $wcz_uploaded_badge ) : ''; ?>"
            data-mwidth="<?php echo $wcz_pbadge_mwidth ? esc_attr( $wcz_pbadge_mwidth ) : '120'; ?>" />

        <label><?php esc_html_e( "Choose a Badge Design:", 'woocustomizer' ); ?></label>
        <div class="pbadges-blocks">
            <div class="pbadges-block <?php echo ( 'one' == $wcz_pbadge_design || !$wcz_pbadge_design ) ? sanitize_html_class( 'active' ) : ''; ?>" data-badge="one">

                <div class="wczbadge bfc2 bbgc1 one"><div class="wczbadge-inner"><span>Discount!</span></div></div>

            </div>
            <div class="pbadges-block <?php echo 'two' == $wcz_pbadge_design ? sanitize_html_class( 'active' ) : ''; ?>" data-badge="two">
                
                <div class="wczbadge bcbc1 bfc2 two"><div class="wczbadge-inner"><span>On Sale!</span></div></div>

            </div>
            <div class="pbadges-block canswitch <?php echo 'three' == $wcz_pbadge_design ? sanitize_html_class( 'active' ) : ''; ?>" data-badge="three">
                
                <div class="wczbadge bfc2 three"><div class="wczbblk bbrc1 bblc1"></div><div class="wczbadge-inner"><span>NEW PRODUCT!</span></div></div>

            </div>
            <div class="pbadges-block <?php echo 'four' == $wcz_pbadge_design ? sanitize_html_class( 'active' ) : ''; ?>" data-badge="four">
                
                <div class="wczbadge bfc2 bbbc1 four"><div class="wczbadge-inner"><span>SALE !</span></div></div>

            </div>
            <div class="pbadges-block <?php echo 'five' == $wcz_pbadge_design ? sanitize_html_class( 'active' ) : ''; ?>" data-badge="five">
                
                <div class="wczbadge bfc2 bbgc1 five"><div class="wczbadge-inner"><span>New !</span></div></div>

            </div>
            <div class="pbadges-block canswitch <?php echo 'six' == $wcz_pbadge_design ? sanitize_html_class( 'active' ) : ''; ?>" data-badge="six">

                <div class="wczbadge bfc2 bbgc1 six"><div class="wczbadge-inner"><div class="wczbblk bbrc1 bblc1"></div><span>Banner!</span><div class="wczablk"></div></div></div>

            </div>
            <div class="pbadges-block <?php echo 'seven' == $wcz_pbadge_design ? sanitize_html_class( 'active' ) : ''; ?>" data-badge="seven">
                
                <div class="wczbadge bfc2 bbtc1 seven"><div class="wczbadge-inner"><span>SALE !</span></div></div>

            </div>
            <div class="pbadges-block <?php echo 'eight' == $wcz_pbadge_design ? sanitize_html_class( 'active' ) : ''; ?>" data-badge="eight">
                
                <div class="wczbadge bfc2 bbtc1 eight"><div class="wczbadge-inner"><span>FEATURED!</span></div></div>

            </div>
            <div class="pbadges-block <?php echo 'nine' == $wcz_pbadge_design ? sanitize_html_class( 'active' ) : ''; ?>" data-badge="nine">
                
                <div class="wczbadge bbc1 bfc2 bbgc1 nine"><div class="wczbadge-inner"><span>VIEW!</span></div></div>

            </div>
            <div class="pbadges-block <?php echo 'ten' == $wcz_pbadge_design ? sanitize_html_class( 'active' ) : ''; ?>" data-badge="ten">
                
                <div class="wczbadge bbc1 bfc2 bbgc1 ten"><div class="wczbadge-inner"><span>New!</span></div></div>

            </div>
            <div class="pbadges-block notxt <?php echo 'eleven' == $wcz_pbadge_design ? sanitize_html_class( 'active' ) : ''; ?>" data-badge="eleven">
                
                <div class="wczbadge eleven"><div class="wczbblk bbgc1"></div><div class="wczbadge-inner"></div><div class="wczablk bbgc1"></div></div>

            </div>
            <div class="pbadges-block <?php echo 'twelve' == $wcz_pbadge_design ? sanitize_html_class( 'active' ) : ''; ?>" data-badge="twelve">
                
                <div class="wczbadge bfc2 bbgc1 twelve"><div class="wczbblk bbbc1"></div><div class="wczbadge-inner"><span>Now on Sale!</span></div><div class="wczablk bbbc1"></div></div>

            </div>
            <div class="pbadges-block <?php echo 'thirteen' == $wcz_pbadge_design ? sanitize_html_class( 'active' ) : ''; ?>" data-badge="thirteen">
                
                <div class="wczbadge bfc2 bbgc1 thirteen"><div class="wczbadge-inner"><span>View Product!</span></div></div>

            </div>
            <div class="pbadges-block <?php echo 'fourteen' == $wcz_pbadge_design ? sanitize_html_class( 'active' ) : ''; ?>" data-badge="fourteen">
                
                <div class="wczbadge bfc2 bbgc1 fourteen"><div class="wczbblk bbtc1"></div><div class="wczbadge-inner"><span>Featured Product</span></div></div>

            </div>
            <div class="pbadges-block canswitch <?php echo 'fiveteen' == $wcz_pbadge_design ? sanitize_html_class( 'active' ) : ''; ?>" data-badge="fiveteen">
                
                <div class="wczbadge bfc2 fiveteen"><div class="wczbadge-inner bbgc1"><span>NEW !</span></div></div>

            </div>
            <div class="pbadges-block canswitch <?php echo 'sixteen' == $wcz_pbadge_design ? sanitize_html_class( 'active' ) : ''; ?>" data-badge="sixteen">
                
                <div class="wczbadge bfc2 sixteen"><div class="wczbblk bbrc1 bblc1"></div><div class="wczbadge-inner bbgc1"><span>NEW !</span></div><div class="wczablk bbrc1 bblc1"></div></div>

            </div>
            <div class="pbadges-block canswitch notxt <?php echo 'seventeen' == $wcz_pbadge_design ? sanitize_html_class( 'active' ) : ''; ?>" data-badge="seventeen">
                
                <div class="wczbadge bbrc1 bblc1 seventeen"><div class="wczbadge-inner"></div><div class="wczablk bbtc1 bbbc1"></div></div>

            </div>
            <div class="pbadges-block <?php echo 'eightteen' == $wcz_pbadge_design ? sanitize_html_class( 'active' ) : ''; ?>" data-badge="eightteen">
                
                <div class="wczbadge bfc2 eightteen"><div class="wczbblk bbbc1"></div><div class="wczbadge-inner"><span>BOOM !</span></div><div class="wczablk bbtc1"></div></div>

            </div>
            <div class="pbadges-block custom <?php echo 'custom' == $wcz_pbadge_design ? sanitize_html_class( 'active' ) : ''; ?>" data-badge="custom">
                
                <img src="<?php echo esc_url( WCD_PLUGIN_URL . '/assets/images/upload-icon.png' ); ?>" alt="Upload" />
                <div class="custom-badge-in">Custom Badge</div>

            </div>
        </div>
        <input type="hidden" name="wcz-pbadge-design" id="wcz-pbadge-design" value="<?php echo $wcz_pbadge_design ? $wcz_pbadge_design : 'one'; ?>" />


        <div class="wcz-meta-setting wcz-custom">
            <div class="wcz-meta-left">
                <?php esc_html_e( 'Upload a Custom Badge', 'woocustomizer' ); ?>
            </div>
            <div class="wcz-meta-right">
                <button type="button" class="button wcz-custom-upload <?php echo $wcz_uploaded_badge ? sanitize_html_class( 'hide' ) : ''; ?>" data-media-uploader-target="#wcz-upmedia"><?php esc_html_e( 'Upload Custom Badge', 'woocustomizer' )?></button>
                <div class="wcz-upload-img">
                    <?php if ( $wcz_uploaded_badge ) :
                        $img = wp_get_attachment_image_src( $wcz_uploaded_badge, 'full' ); ?>
                        <div class="wcz-upload-image"><img src="<?php echo esc_url( $img[0] ); ?>" /></div>
                    <?php else : ?>
                        <div class="wcz-upload-image"></div>
                    <?php endif; ?>
                    <input type="hidden" class="wcz-pbadge-custom" name="wcz-upmedia" id="wcz-upmedia" value="<?php echo esc_attr( $wcz_uploaded_badge ); ?>">
                    <a href="#" id="wcz-custom-rm" class="wcz-custom-rm <?php echo $wcz_uploaded_badge ? '' : sanitize_html_class( 'hide' ); ?>"><?php esc_html_e( 'Remove', 'woocustomizer' )?></a>
                </div>
            </div>
        </div>
        <div class="wcz-meta-setting wcz-custom">
            <div class="wcz-meta-left">
                <label><?php esc_html_e( 'Max Width', 'woocustomizer' ); ?></label>
            </div>
            <div class="wcz-meta-right">
                <input class="wcz-pbadge-mwidth" type="number" name="wcz-pbadge-mwidth" value="<?php echo $wcz_pbadge_mwidth ? esc_attr( $wcz_pbadge_mwidth ) : '120'; ?>"/>
            </div>
        </div>

        <div class="wcz-meta-setting wcz-normal wcz-notxt">
            <div class="wcz-meta-left align">
                <label><?php esc_html_e( 'Badge Text', 'woocustomizer' ); ?></label>
            </div>
            <div class="wcz-meta-right">
                <input class="wcz-pbadge-text" type="text" name="wcz-pbadge-text" value="<?php echo esc_attr( $wcz_pbadge_text ); ?>"/>
                <p class="wcz-pbadge-desc"><?php esc_html_e( 'Use these shortcodes to display discount amounts:', 'woocustomizer' ); ?> <span>[percent]</span>, <span>[amount]</span><br /><?php esc_html_e( 'This badge preview on the right will use 10% for [percent] and $10 for [amount] as an example.', 'woocustomizer' ); ?></p>
            </div>
        </div>

        <div class="wcz-meta-setting wcz-normal">
            <div class="wcz-meta-left">
                <?php esc_html_e( 'Badge Color', 'woocustomizer' ); ?>
            </div>
            <div class="wcz-meta-right">
                <input class="wcz-pbadge-bcolor" type="text" name="wcz_pbadge_color" value="<?php esc_attr_e( $wcz_pbadge_color ); ?>" />
            </div>
        </div>

        <div class="wcz-meta-setting wcz-normal wcz-notxt">
            <div class="wcz-meta-left">
                <?php esc_html_e( 'Text Color', 'woocustomizer' ); ?>
            </div>
            <div class="wcz-meta-right">
                <input class="wcz-pbadge-fcolor" type="text" name="wcz_pbadge_font_color" value="<?php esc_attr_e( $wcz_pbadge_font_color ); ?>"/>
            </div>
        </div>
        
        <!-- Shop Page Settings -->
        <h5><?php esc_html_e( 'Shop Page Badge', 'woocustomizer' ); ?></h5>
        <p class="wcz-pbadge-section-desc"><?php esc_html_e( 'These settings apply to the badge displayed on the Shop / Catalogue page. Preview on the right ->', 'woocustomizer' ); ?></p>

        <div class="wcz-meta-setting">
            <div class="wcz-meta-left">
                <label for="wcz-pbadge-position"><?php esc_html_e( 'Badge Position', 'woocustomizer' ); ?></label>
            </div>
            <div class="wcz-meta-right">
                <select name="wcz-pbadge-position" id="wcz-pbadge-position">
                    <option value="topright" <?php selected( $wcz_pbadge_position, 'topright' ); ?>><?php esc_html_e( 'Top Right', 'woocustomizer' ); ?></option>
                    <option value="topcenter" <?php selected( $wcz_pbadge_position, 'topcenter' ); ?>><?php esc_html_e( 'Top Center', 'woocustomizer' ); ?></option>
                    <option value="topleft" <?php selected( $wcz_pbadge_position, 'topleft' ); ?>><?php esc_html_e( 'Top Left', 'woocustomizer' ); ?></option>
                    <option value="middleright" <?php selected( $wcz_pbadge_position, 'middleright' ); ?>><?php esc_html_e( 'Middle Right', 'woocustomizer' ); ?></option>
                    <option value="middlecenter" <?php selected( $wcz_pbadge_position, 'middlecenter' ); ?>><?php esc_html_e( 'Middle Center', 'woocustomizer' ); ?></option>
                    <option value="middleleft" <?php selected( $wcz_pbadge_position, 'middleleft' ); ?>><?php esc_html_e( 'Middle Left', 'woocustomizer' ); ?></option>
                    <option value="bottomright" <?php selected( $wcz_pbadge_position, 'bottomright' ); ?>><?php esc_html_e( 'Bottom Right', 'woocustomizer' ); ?></option>
                    <option value="bottomcenter" <?php selected( $wcz_pbadge_position, 'bottomcenter' ); ?>><?php esc_html_e( 'Bottom Center', 'woocustomizer' ); ?></option>
                    <option value="bottomleft" <?php selected( $wcz_pbadge_position, 'bottomleft' ); ?>><?php esc_html_e( 'Bottom Left', 'woocustomizer' ); ?></option>
                </select>
            </div>
        </div>

        <div class="wcz-meta-setting wcz-switch">
            <div class="wcz-meta-left">
                <label for="wcz-pbadge-switch"><?php esc_html_e( 'Switch Badge Alignment', 'woocustomizer' ); ?></label>
            </div>
            <div class="wcz-meta-right">
                <?php if ( $wcz_pbadge_switch == '' ) : ?>
                    <input name="wcz-pbadge-switch" id="wcz-pbadge-switch" type="checkbox" value="true">
                <?php elseif ( $wcz_pbadge_switch == "true" ) : ?>
                    <input name="wcz-pbadge-switch" id="wcz-pbadge-switch" type="checkbox" value="true" checked>
                <?php endif; ?>
            </div>
        </div>

        <div class="wcz-meta-setting">
            <div class="wcz-meta-left align">
                <label for="wcz-pbadge-horiz-shop-offset-pos"><?php esc_html_e( 'Horizontal Offset', 'woocustomizer' ); ?></label>
            </div>
            <div class="wcz-meta-right wcz-pbadge-offpos">
                <?php
                $wcz_pbadge_horiz_shopoffset_arr = explode( '|', $wcz_pbadge_horiz_shopoffset );
                $wcz_pbadge_horiz_shopoffset_pos = $wcz_pbadge_horiz_shopoffset_arr[0];
                $wcz_pbadge_horiz_shopoffset_no = $wcz_pbadge_horiz_shopoffset_arr[1]; ?>
                <select name="wcz-pbadge-horiz-shop-offset-pos" id="wcz-pbadge-horiz-shop-offset-pos">
                    <option value="left" <?php selected( $wcz_pbadge_horiz_shopoffset_pos, 'left' ); ?>><?php esc_html_e( 'Left', 'woocustomizer' ); ?></option>
                    <option value="right" <?php selected( $wcz_pbadge_horiz_shopoffset_pos, 'right' ); ?>><?php esc_html_e( 'Right', 'woocustomizer' ); ?></option>
                </select>:
                <input class="wcz-pbadge-horiz-shop-offset-no" type="number" name="wcz-pbadge-horiz-shop-offset-no" value="<?php echo $wcz_pbadge_horiz_shopoffset_no ? esc_attr( $wcz_pbadge_horiz_shopoffset_no ) : '0'; ?>"/>px
                <p class="wcz-pbadge-desc"><?php esc_html_e( 'You can also use negative numbers (-) to position the badge.', 'woocustomizer' ); ?></p>
            </div>
        </div>
        <div class="wcz-meta-setting">
            <div class="wcz-meta-left align">
                <label for="wcz-pbadge-vert-shop-offset-pos"><?php esc_html_e( 'Vertical Offset', 'woocustomizer' ); ?></label>
            </div>
            <div class="wcz-meta-right wcz-pbadge-offpos">
                <?php
                $wcz_pbadge_vert_shopoffset_arr = explode( '|', $wcz_pbadge_vert_shopoffset );
                $wcz_pbadge_vert_shopoffset_pos = $wcz_pbadge_vert_shopoffset_arr[0];
                $wcz_pbadge_vert_shopoffset_no = $wcz_pbadge_vert_shopoffset_arr[1]; ?>
                <select name="wcz-pbadge-vert-shop-offset-pos" id="wcz-pbadge-vert-shop-offset-pos">
                    <option value="top" <?php selected( $wcz_pbadge_vert_shopoffset_pos, 'top' ); ?>><?php esc_html_e( 'Top', 'woocustomizer' ); ?></option>
                    <option value="bottom" <?php selected( $wcz_pbadge_vert_shopoffset_pos, 'bottom' ); ?>><?php esc_html_e( 'Bottom', 'woocustomizer' ); ?></option>
                </select>:
                <input class="wcz-pbadge-vert-shop-offset-no" type="number" name="wcz-pbadge-vert-shop-offset-no" value="<?php echo $wcz_pbadge_vert_shopoffset_no ? esc_attr( $wcz_pbadge_vert_shopoffset_no ) : '0'; ?>"/>px
                <p class="wcz-pbadge-desc"><?php esc_html_e( 'You can also use negative numbers (-) to position the badge.', 'woocustomizer' ); ?></p>
            </div>
        </div>

        <h6><?php esc_html_e( 'Attach badge to a custom element', 'woocustomizer' ); ?></h6>
        <p class="wcz-pbadge-desc space">
            <?php esc_html_e( 'You can attach this badge to a custom element on the shop page by adding the elements class name here.', 'woocustomizer' ); ?>
            <a href="<?php echo esc_url( 'https://storecustomizer.com/documentation/product-badges-to-a-custom-class/' ); ?>" target="_blank"><?php esc_html_e( 'Read more on using this feature', 'woocustomizer' ); ?></a>
        </p>

        <div class="wcz-meta-setting">
            <div class="wcz-meta-left">
                <label><?php esc_html_e( 'Attach to', 'woocustomizer' ); ?></label>
            </div>
            <div class="wcz-meta-right">
                <input class="wcz-pbadge-belement" type="text" name="wcz-pbadge-belement" value="<?php echo esc_attr( $wcz_pbadge_belement ); ?>" placeholder=".element-class-name" />
            </div>
        </div>
        
        <!-- Product Page Settings -->
        <h5><?php esc_html_e( 'Product Page Badge', 'woocustomizer' ); ?></h5>
        <p class="wcz-pbadge-section-desc">
            <?php esc_html_e( 'These settings apply to the badge displayed on the product single page.', 'woocustomizer' ); ?><br />
            <?php esc_html_e( 'To get the position exact, please open a product page with this badge on it to view these changes.', 'woocustomizer' ); ?>
        </p>

        <div class="wcz-meta-setting">
            <div class="wcz-meta-left">
                <label for="wcz-pbadge-prod-position"><?php esc_html_e( 'Badge Position', 'woocustomizer' ); ?></label>
            </div>
            <div class="wcz-meta-right">
                <select name="wcz-pbadge-prod-position" id="wcz-pbadge-prod-position">
                    <option value="" <?php selected( $wcz_pbadge_prod_position, '' ); ?>><?php esc_html_e( 'Same As Shop', 'woocustomizer' ); ?></option>
                    <option value="topright" <?php selected( $wcz_pbadge_prod_position, 'topright' ); ?>><?php esc_html_e( 'Top Right', 'woocustomizer' ); ?></option>
                    <option value="topcenter" <?php selected( $wcz_pbadge_prod_position, 'topcenter' ); ?>><?php esc_html_e( 'Top Center', 'woocustomizer' ); ?></option>
                    <option value="topleft" <?php selected( $wcz_pbadge_prod_position, 'topleft' ); ?>><?php esc_html_e( 'Top Left', 'woocustomizer' ); ?></option>
                    <option value="middleright" <?php selected( $wcz_pbadge_prod_position, 'middleright' ); ?>><?php esc_html_e( 'Middle Right', 'woocustomizer' ); ?></option>
                    <option value="middlecenter" <?php selected( $wcz_pbadge_prod_position, 'middlecenter' ); ?>><?php esc_html_e( 'Middle Center', 'woocustomizer' ); ?></option>
                    <option value="middleleft" <?php selected( $wcz_pbadge_prod_position, 'middleleft' ); ?>><?php esc_html_e( 'Middle Left', 'woocustomizer' ); ?></option>
                    <option value="bottomright" <?php selected( $wcz_pbadge_prod_position, 'bottomright' ); ?>><?php esc_html_e( 'Bottom Right', 'woocustomizer' ); ?></option>
                    <option value="bottomcenter" <?php selected( $wcz_pbadge_prod_position, 'bottomcenter' ); ?>><?php esc_html_e( 'Bottom Center', 'woocustomizer' ); ?></option>
                    <option value="bottomleft" <?php selected( $wcz_pbadge_prod_position, 'bottomleft' ); ?>><?php esc_html_e( 'Bottom Left', 'woocustomizer' ); ?></option>
                </select>
            </div>
        </div>
        
        <div class="wcz-meta-setting wcz-switch">
            <div class="wcz-meta-left">
                <label for="wcz-pbadge-prod-switch"><?php esc_html_e( 'Switch Badge Alignment', 'woocustomizer' ); ?></label>
            </div>
            <div class="wcz-meta-right">
                <select name="wcz-pbadge-prod-switch" id="wcz-pbadge-prod-switch">
                    <option value="inherit" <?php selected( $wcz_pbadge_prod_switch, 'inherit' ); ?>><?php esc_html_e( 'Same as Shop', 'woocustomizer' ); ?></option>
                    <option value="false" <?php selected( $wcz_pbadge_prod_switch, 'false' ); ?>><?php esc_html_e( 'Normal', 'woocustomizer' ); ?></option>
                    <option value="true" <?php selected( $wcz_pbadge_prod_switch, 'true' ); ?>><?php esc_html_e( 'Switch', 'woocustomizer' ); ?></option>
                </select>
            </div>
        </div>

        <div class="wcz-meta-setting">
            <div class="wcz-meta-left">
                <label for="wcz-pbadge-horiz-prod-offset-pos"><?php esc_html_e( 'Horizontal Offset', 'woocustomizer' ); ?></label>
            </div>
            <div class="wcz-meta-right wcz-pbadge-offpos">
                <?php
                $wcz_pbadge_horiz_prodoffset_arr = explode( '|', $wcz_pbadge_horiz_prodoffset );
                $wcz_pbadge_horiz_prodoffset_pos = $wcz_pbadge_horiz_prodoffset_arr[0];
                $wcz_pbadge_horiz_prodoffset_no = $wcz_pbadge_horiz_prodoffset_arr[1]; ?>
                <select name="wcz-pbadge-horiz-prod-offset-pos" id="wcz-pbadge-horiz-prod-offset-pos">
                    <option value="left" <?php selected( $wcz_pbadge_horiz_prodoffset_pos, 'left' ); ?>><?php esc_html_e( 'Left', 'woocustomizer' ); ?></option>
                    <option value="right" <?php selected( $wcz_pbadge_horiz_prodoffset_pos, 'right' ); ?>><?php esc_html_e( 'Right', 'woocustomizer' ); ?></option>
                </select>:
                <input class="wcz-pbadge-horiz-prod-offset-no" type="number" name="wcz-pbadge-horiz-prod-offset-no" value="<?php echo $wcz_pbadge_horiz_prodoffset_no ? esc_attr( $wcz_pbadge_horiz_prodoffset_no ) : '0'; ?>"/>px
                <p class="wcz-pbadge-desc"><?php esc_html_e( 'You can also use negative numbers (-) to position the badge.', 'woocustomizer' ); ?></p>
            </div>
        </div>
        <div class="wcz-meta-setting">
            <div class="wcz-meta-left align">
                <label for="wcz-pbadge-vert-prod-offset-pos"><?php esc_html_e( 'Vertical Offset', 'woocustomizer' ); ?></label>
            </div>
            <div class="wcz-meta-right wcz-pbadge-offpos">
                <?php
                $wcz_pbadge_vert_prodoffset_arr = explode( '|', $wcz_pbadge_vert_prodoffset );
                $wcz_pbadge_vert_prodoffset_pos = $wcz_pbadge_vert_prodoffset_arr[0];
                $wcz_pbadge_vert_prodoffset_no = $wcz_pbadge_vert_prodoffset_arr[1]; ?>
                <select name="wcz-pbadge-vert-prod-offset-pos" id="wcz-pbadge-vert-prod-offset-pos">
                    <option value="top" <?php selected( $wcz_pbadge_vert_prodoffset_pos, 'top' ); ?>><?php esc_html_e( 'Top', 'woocustomizer' ); ?></option>
                    <option value="bottom" <?php selected( $wcz_pbadge_vert_prodoffset_pos, 'bottom' ); ?>><?php esc_html_e( 'Bottom', 'woocustomizer' ); ?></option>
                </select>:
                <input class="wcz-pbadge-vert-prod-offset-no" type="number" name="wcz-pbadge-vert-prod-offset-no" value="<?php echo $wcz_pbadge_vert_prodoffset_no ? esc_attr( $wcz_pbadge_vert_prodoffset_no ) : '0'; ?>"/>px
                <p class="wcz-pbadge-desc"><?php esc_html_e( 'You can also use negative numbers (-) to position the badge.', 'woocustomizer' ); ?></p>
            </div>
        </div>
        
        <h6><?php esc_html_e( 'Attach badge to a custom element', 'woocustomizer' ); ?></h6>
        <p class="wcz-pbadge-desc space">
            <?php esc_html_e( 'You can attach this badge to a custom element on the product page by adding the elements class name here.', 'woocustomizer' ); ?>
            <a href="<?php echo esc_url( 'https://storecustomizer.com/documentation/product-badges-to-a-custom-class/' ); ?>" target="_blank"><?php esc_html_e( 'Read more on using this feature', 'woocustomizer' ); ?></a>
        </p>

        <div class="wcz-meta-setting">
            <div class="wcz-meta-left align">
                <label><?php esc_html_e( 'Attach to', 'woocustomizer' ); ?></label>
            </div>
            <div class="wcz-meta-right">
                <input class="wcz-pbadge-bselement" type="text" name="wcz-pbadge-bselement" value="<?php echo esc_attr( $wcz_pbadge_bselement ); ?>" placeholder=".element-class-name" />
            </div>
        </div>

        <p class="wcz-pbadge-note">
            <?php
            /* translators: 1: 'Contact Us'. */
            printf( esc_html__( 'Have you edited the badge and something is not aligning correctly? Or want slight extra tweaks? %1$s for some extra CSS to make it look better.', 'woocustomizer' ), wp_kses( __( '<a href="https://storecustomizer.com/#anchor-contact" target="_blank">Contact Us</a>', 'woocustomizer' ), array( 'a' => array( 'href' => array(), 'target' => array() ) ) ) ); ?>
        </p>
    </div>
<?php
}

/**
 * Help adding a badge
 */
function wcz_pbadge_help_metabox() { ?>
    <div class="wcz-pbadge-help">
        <p><?php esc_html_e( 'Follow these steps to add a custom badge to your products.', 'woocustomizer' ); ?></p>
        <ul>
            <li><?php esc_html_e( 'Create and customize your new badge on this screen.', 'woocustomizer' ); ?></li>
            <li>
                <?php
                /* translators: 1: 'your Products', 2: 'Product Categories'. */
                printf( esc_html__( 'Go to %1$s or %2$s and select to edit the product or category that you want to add this badge to.', 'woocustomizer' ), wp_kses( __( '<a href="' . admin_url( '/edit.php?post_type=product' ) . '" target="_blank">your Products</a>', 'woocustomizer' ), array( 'a' => array( 'href' => array(), 'target' => array() ) ) ), wp_kses( __( '<a href="' . admin_url( '/edit-tags.php?taxonomy=product_cat&post_type=product' ) . '" target="_blank">Product Categories</a>', 'woocustomizer' ), array( 'a' => array( 'href' => array(), 'target' => array() ) ) ) ); ?>
            </li>
            <li><?php esc_html_e( 'For a product, add the badge under "Product Data -> Product Badges", and for a category, edit the category and "Select Product Badge(s)".', 'woocustomizer' ); ?></li>
            <li><?php esc_html_e( 'Add the bagde & click Update.', 'woocustomizer' ); ?></li>
        </ul>
    </div><?php
}

/**
 * Create the Badge Preview
 */
function wcz_pbadge_preview( $object ) {
    $wcz_pbadge_design = get_post_meta( $object->ID, 'wcz-pbadge-design', true );
    $wcz_pbadge_position = get_post_meta( $object->ID, 'wcz-pbadge-position', true ) ? get_post_meta( $object->ID, 'wcz-pbadge-position', true ) : 'topright';
    $wcz_pbadge_switch = get_post_meta( $object->ID, 'wcz-pbadge-switch', true ); ?>
    <div class="wcz-pbadge-preview">
        <div class="wcz-pbadge-product">
            <div class="wcz-pbadge-product-img">
                <div class="wcz-pbadge <?php echo sanitize_html_class( $wcz_pbadge_position ); ?> <?php echo 'true' == $wcz_pbadge_switch ? sanitize_html_class( 'switch' ) : ''; ?>">
                    <div class="wcz-pbadge-in" data-badge="<?php echo $wcz_pbadge_design ? esc_attr( $wcz_pbadge_design ) : 'one'; ?>"></div>
                </div>
                <img src="<?php echo esc_url( WCD_PLUGIN_URL . '/assets/images/storecustomizer-placeholder.jpg' ); ?>" alt="StoreCustomizer" />
            </div>
            <h3 class="title"><?php echo esc_html( 'StoreCustomizer' ); ?></h3>
            <div class="price"><?php echo esc_html( '$39.00' ); ?></div>
            <div class="fake-button"><?php echo esc_html__( 'Add to cart', 'woocustomizer' ); ?></div>
            <div class="wcz-linkto-products-lists">
                <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=product' ) ); ?>" class="wcz-linkto" target="_blank"><?php echo esc_html__( 'Go to Products List', 'woocustomizer' ); ?></a>
                <a href="<?php echo esc_url( admin_url( 'edit-tags.php?taxonomy=product_cat&post_type=product' ) ); ?>" class="wcz-linkto" target="_blank"><?php echo esc_html__( 'Go to Products Categories', 'woocustomizer' ); ?></a>
            </div>
        </div>
    </div><?php
}

/**
 * Save Page metabox data
 */
function wcz_pb_save_page_settings_metabox( $post_id, $post, $update ) {
	
    if ( !isset( $_POST['wcz-pbadges-nonce'] ) || !wp_verify_nonce( $_POST['wcz-pbadges-nonce'], basename( __FILE__ ) ) )
        return $post_id;

    if ( !current_user_can( "edit_post", $post_id ) )
        return $post_id;

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
        return $post_id;

    $slug = 'wcz-badges';
    if ( $slug != $post->post_type )
        return $post_id;

    $wcz_pbadge_design = '';
    $wcz_pbadge_text = '';
    $wcz_pbadge_belement = '';
    $wcz_pbadge_bselement = '';
    $wcz_pbadge_switch = '';
    
    // Badge Main Settings
    if ( isset( $_POST['wcz-pbadge-design'] ) ) {
        $wcz_pbadge_design = $_POST['wcz-pbadge-design'];
    }   
    update_post_meta( $post_id, 'wcz-pbadge-design', $wcz_pbadge_design );

    // Color
    $wcz_pbadge_color = ( isset( $_POST['wcz_pbadge_color'] ) && $_POST['wcz_pbadge_color'] != '' ) ? $_POST['wcz_pbadge_color'] : '';
    update_post_meta( $post_id, 'wcz_pbadge_color', $wcz_pbadge_color );
    
    // Font Color
    $wcz_pbadge_font_color = ( isset( $_POST['wcz_pbadge_font_color'] ) && $_POST['wcz_pbadge_font_color'] != '' ) ? $_POST['wcz_pbadge_font_color'] : '';
	update_post_meta( $post_id, 'wcz_pbadge_font_color', $wcz_pbadge_font_color );

    // Badge Text
    if ( isset( $_POST['wcz-pbadge-text'] ) ) {
        $wcz_pbadge_text = $_POST['wcz-pbadge-text'];
    }
    update_post_meta( $post_id, 'wcz-pbadge-text', $wcz_pbadge_text );

    // Shop Position
    if ( array_key_exists( 'wcz-pbadge-position', $_POST ) ) {
        update_post_meta( $post_id, 'wcz-pbadge-position', $_POST['wcz-pbadge-position'] );
    }

    // Product Page Position
    if ( array_key_exists( 'wcz-pbadge-prod-position', $_POST ) ) {
        update_post_meta( $post_id, 'wcz-pbadge-prod-position', $_POST['wcz-pbadge-prod-position'] );
    }

    // Shop Page Offset
    if ( array_key_exists( 'wcz-pbadge-horiz-shop-offset-pos', $_POST ) && isset( $_POST['wcz-pbadge-horiz-shop-offset-no'] ) ) {
        $wcz_pbadge_horiz_shop_offset = $_POST['wcz-pbadge-horiz-shop-offset-pos'] . '|' . $_POST['wcz-pbadge-horiz-shop-offset-no'];
        update_post_meta( $post_id, 'wcz-pbadge-horiz-shop-offset', $wcz_pbadge_horiz_shop_offset );
    }

    if ( array_key_exists( 'wcz-pbadge-vert-shop-offset-pos', $_POST ) && isset( $_POST['wcz-pbadge-vert-shop-offset-no'] ) ) {
        $wcz_pbadge_vert_shop_offset = $_POST['wcz-pbadge-vert-shop-offset-pos'] . '|' . $_POST['wcz-pbadge-vert-shop-offset-no'];
        update_post_meta( $post_id, 'wcz-pbadge-vert-shop-offset', $wcz_pbadge_vert_shop_offset );
    }

    // Shop Page Switch
    if ( isset( $_POST['wcz-pbadge-switch'] ) ) {
        $wcz_pbadge_switch = $_POST['wcz-pbadge-switch'];
    }   
    update_post_meta( $post_id, 'wcz-pbadge-switch', $wcz_pbadge_switch );

    // Shop Page - Custom Element
    $wcz_pbadge_belement = $_POST['wcz-pbadge-belement'] ? $_POST['wcz-pbadge-belement'] : '';
    update_post_meta( $post_id, 'wcz-pbadge-belement', $wcz_pbadge_belement );

    // Product Page Offset
    if ( array_key_exists( 'wcz-pbadge-horiz-prod-offset-pos', $_POST ) && isset( $_POST['wcz-pbadge-horiz-prod-offset-no'] ) ) {
        $wcz_pbadge_horiz_prod_offset = $_POST['wcz-pbadge-horiz-prod-offset-pos'] . '|' . $_POST['wcz-pbadge-horiz-prod-offset-no'];
        update_post_meta( $post_id, 'wcz-pbadge-horiz-prod-offset', $wcz_pbadge_horiz_prod_offset );
    }

    if ( array_key_exists( 'wcz-pbadge-vert-prod-offset-pos', $_POST ) && isset( $_POST['wcz-pbadge-vert-prod-offset-no'] ) ) {
        $wcz_pbadge_vert_prod_offset = $_POST['wcz-pbadge-vert-prod-offset-pos'] . '|' . $_POST['wcz-pbadge-vert-prod-offset-no'];
        update_post_meta( $post_id, 'wcz-pbadge-vert-prod-offset', $wcz_pbadge_vert_prod_offset );
    }

    // Product Page Switch
    if ( array_key_exists( 'wcz-pbadge-prod-switch', $_POST ) ) {
        update_post_meta( $post_id, 'wcz-pbadge-prod-switch', $_POST['wcz-pbadge-prod-switch'] );
    }

    // Product Page - Custom Element
    $wcz_pbadge_bselement = $_POST['wcz-pbadge-bselement'] ? $_POST['wcz-pbadge-bselement'] : '';
    update_post_meta( $post_id, 'wcz-pbadge-bselement', $wcz_pbadge_bselement );

    
    // Custom Badge Settings
    if ( isset( $_POST['wcz-upmedia'] ) ) {
        $wcz_uploaded_badge = $_POST['wcz-upmedia'];
    }
    update_post_meta( $post_id, 'wcz-upmedia', $wcz_uploaded_badge );

    if ( isset( $_POST['wcz-pbadge-mwidth'] ) ) {
        $wcz_pbadge_mwidth = $_POST['wcz-pbadge-mwidth'];
    }
    update_post_meta( $post_id, 'wcz-pbadge-mwidth', $wcz_pbadge_mwidth );

}
add_action( 'save_post', 'wcz_pb_save_page_settings_metabox', 10, 3 );

/**
 * Product Badges Settings in Product.
 */
function wcz_add_pb_page_tab( $tabs ) {
 
    $tabs['wcz_pb_tab'] = array(
        'label'    => 'Product Badges',
        'target'   => 'wcz_pb_product_data',
        // 'class'    => array( 'show_if_simple' ),
        // 'priority' => 21,
    );

    return $tabs;
 
}
add_filter( 'woocommerce_product_data_tabs', 'wcz_add_pb_page_tab' );

/**
 * Product Badges Product Tab Settings.
 */
function wcz_pb_product_settings() {
    // Only continue IF Custom Thank You Pages is enabled on WCZ Settings Page
    if ( ! get_option( 'wcz_set_enable_product_badges', woocustomizer_library_get_default( 'wcz_set_enable_product_badges' ) ) )
        return;
 
    echo '<div id="wcz_pb_product_data" class="panel woocommerce_options_panel hidden">';
        
        // Multi-Select Badges Post Type
        $wcz_args = array(
            //'p' => $post,
            'post_type' => 'wcz-badges'
        );
        $wcz_pbquery = new WP_Query( $wcz_args );
        $posts = $wcz_pbquery->posts;

        $wcz_pboptions = array();
        if ( $posts ) :
            foreach( $posts as $post ) :
                $wcz_pboptions[$post->ID] = $post->post_title;
            endforeach;
        endif;

        // Select Badges
        woocommerce_wp_select( array(
            'id' => 'wcz_pb_selected_badges',
            'name' => 'wcz_pb_selected_badges[]',
            'label' => __( 'Select Product Badge(s)', 'woocustomizer' ),
            'default' => '',
            'desc_tip' => true,
            'description' => 'Select the badge(s) you want to display on this Product',
            'class' => 'wc-enhanced-select select2',
            'options' => $wcz_pboptions,
            'custom_attributes' => array( 'multiple' => 'multiple' )
        ) );


        // echo '<div style="border-top: 1px solid rgba(0, 0, 0, 0.08)"><h4 style="margin: 15px 0 10px 12px;">' . __( 'Schedule', 'woocustomizer' ) . '</h4>';
        // echo '<p style="margin: 0;padding: 0 0 0 12px;">' . __( 'This is used for if your theme is overiding the WooCommerce templates.', 'woocustomizer' ) . '</p>';

            // Start Date
            // woocommerce_wp_text_input( array(
            //     'id'          => 'wcz_pb_start_date',
            //     'value'       => get_post_meta( get_the_ID(), 'wcz_pb_start_date', true ) ? get_post_meta( get_the_ID(), 'wcz_pb_start_date', true ) : 0,
            //     'type'        => 'date',
            //     'label'       => __( 'Start Date', 'woocustomizer' ),
            //     'desc_tip'    => false,
            //     'class' => 'short hasDatepicker',
            //     'placeholder' => 'From... MM-DD-YYYY'
            //     // 'description' => __( 'Select the start date of when you want the badge to show', 'woocustomizer' ),
            // ) );

            // End Date
            // woocommerce_wp_text_input( array(
            //     'id'          => 'wcz_pb_end_date',
            //     'value'       => get_post_meta( get_the_ID(), 'wcz_pb_end_date', true ) ? get_post_meta( get_the_ID(), 'wcz_pb_end_date', true ) : 0,
            //     'type'        => 'date',
            //     'label'       => __( 'End Date', 'woocustomizer' ),
            //     'desc_tip'    => false,
            //     'class' => 'short hasDatepicker',
            //     'placeholder' => 'To... MM-DD-YYYY'
            //     // 'description' => __( 'Select the end date of when you want the badge to stop showing', 'woocustomizer' ),
            // ) );
        
        // echo '</div>';

    echo '</div>';

}
add_action( 'woocommerce_product_data_panels', 'wcz_pb_product_settings' );

/**
 * Save Product Tab Settings.
 */
function wcz_pb_save_data( $id, $post ) {
    // Selected badges
    update_post_meta( $id, 'wcz_pb_selected_badges', $_POST['wcz_pb_selected_badges'] );

    // Checkbox Option
	// $wcz_rempb_shop = isset( $_POST['wcz_rem_pb_shop'] ) ? 'yes' : '';
    // update_post_meta( $id, 'wcz_rem_pb_shop', $wcz_rempb_shop );

    // Schedule dates
    update_post_meta( $id, 'wcz_pb_start_date', $_POST['wcz_pb_start_date'] );
    update_post_meta( $id, 'wcz_pb_end_date', $_POST['wcz_pb_end_date'] );
}
add_action( 'woocommerce_process_product_meta', 'wcz_pb_save_data', 10, 2 );

/**
 * Add Product Badges to the WooCommmerce Products.
 */
function wcz_add_product_badges() {
    global $woocommerce_loop;

    // Get badges as array from Product Data, and if none then create empty array
    $wcz_badges = get_post_meta( get_the_ID(), 'wcz_pb_selected_badges', true ) ? get_post_meta( get_the_ID(), 'wcz_pb_selected_badges', true ) : array();

    // Get this products categories
    $wcz_pcats = wc_get_product_term_ids( get_the_ID(), 'product_cat' );
    // If categories have badges selected then add the ids to the badges array
    if ( $wcz_pcats ) {
        foreach ( $wcz_pcats as $wcz_pcat ) {
            $wcz_badgecats = get_term_meta( $wcz_pcat, 'wcz_product_category_badges', true );
            if ( $wcz_badgecats ) {
                foreach ( $wcz_badgecats as $wcz_badgecat ) {
                    array_push( $wcz_badges, $wcz_badgecat );
                }
            }
        }
    }

    if ( empty( $wcz_badges ) )
        return;

    if ( !empty( $wcz_badges ) ) {
        foreach ( $wcz_badges as $wcz_badge ) {
            wcz_badge( $wcz_badge );
        }
    }
}
add_action( 'woocommerce_before_shop_loop_item_title', 'wcz_add_product_badges' );
add_action( 'woocommerce_single_product_summary', 'wcz_add_product_badges' );

/**
 * Adding the badge - Used in wcz_add_product_badges() & wcz_pb_replace_default_sale_banner()
 */
function wcz_badge( $wcz_badge ) {
    global $product;
    global $woocommerce_loop;
    $badgeid = $wcz_badge;

    // Check & Get Product Sale discount in % or amount
    $discount_percent = '';
    $discount_amount = '';
    if ( $product->is_on_sale() ) {
        if ( $product->is_type( 'simple' ) || $product->is_type( 'external' ) ) {
            $discount_percent = ( ( $product->get_regular_price() - $product->get_sale_price() ) / $product->get_regular_price() ) * 100;
            $discount_amount = $product->get_regular_price() - $product->get_sale_price();
            // Round amount to 0 decimal
            $discount_percent = round( $discount_percent, 0 );
            $discount_amount = round( $discount_amount, 0 );
        } elseif ( $product->is_type( 'variable' ) ) {
            $percentage = '';
            $discount_percent = 0;
            $amount = '';
            $discount_amount = 0;

            foreach ( $product->get_children() as $child_id ) {
                $variation = wc_get_product( $child_id );
                $price = $variation->get_regular_price();
                $sale = $variation->get_sale_price();

                if ( $price != 0 && ! empty( $sale ) ) {
                    $percentage = ( $price - $sale ) / $price * 100;
                    $amount = $price - $sale;
                }

                if ( $percentage > $discount_percent ) {
                    $discount_percent = round( $percentage, 0 );
                }
                if ( $amount > $discount_amount ) {
                    $discount_amount = $amount;
                }
            }
        }
        // if ( $discount_percent > 0 ) echo "<div class='sale-perc'>-" . round( $discount_percent ) . "%</div>";
        // if ( $discount_amount > 0 ) echo "<div class='sale-doll'>-$" . round( $discount_amount ) . "</div>";
    }

    if ( !get_post_status( $badgeid ) ) return;

    $wcz_pbadge_design = get_post_meta( $badgeid, 'wcz-pbadge-design', true );

    $wcz_pbadge_position = is_product() && '' != get_post_meta( $badgeid, 'wcz-pbadge-prod-position', true ) ? get_post_meta( $badgeid, 'wcz-pbadge-prod-position', true ) : get_post_meta( $badgeid, 'wcz-pbadge-position', true );
    $wcz_pbadge_belement = is_product() ? get_post_meta( $badgeid, 'wcz-pbadge-bselement', true ) : get_post_meta( $badgeid, 'wcz-pbadge-belement', true );
    if ( is_product() && $woocommerce_loop['name'] == 'related' ) {
        $wcz_pbadge_position = get_post_meta( $badgeid, 'wcz-pbadge-position', true );
        $wcz_pbadge_belement = get_post_meta( $badgeid, 'wcz-pbadge-belement', true );
    }
    
    $wcz_pbadge_color = get_post_meta( $badgeid, 'wcz_pbadge_color', true );
    $wcz_pbadge_font_color = get_post_meta( $badgeid, 'wcz_pbadge_font_color', true );
    
    $wcz_pbadge_text = get_post_meta( $badgeid, 'wcz-pbadge-text', true );
    $wcz_pbadge_text2 = str_replace( '[percent]', $discount_percent . '%', $wcz_pbadge_text );
    $wcz_pbadge_text3 = str_replace( '[amount]', get_woocommerce_currency_symbol() . $discount_amount, $wcz_pbadge_text2 );

    if ( is_product() ) {
        if ( $woocommerce_loop['name'] == 'related' ) {
            $wcz_pbadge_horiz_offset = get_post_meta( $badgeid, 'wcz-pbadge-horiz-shop-offset', true ) ? get_post_meta( $badgeid, 'wcz-pbadge-horiz-shop-offset', true ) : 'right|0';
            $wcz_pbadge_horiz_offset_arr = explode( '|', $wcz_pbadge_horiz_offset );
            $wcz_pbadge_vert_offset = get_post_meta( $badgeid, 'wcz-pbadge-vert-shop-offset', true ) ? get_post_meta( $badgeid, 'wcz-pbadge-vert-shop-offset', true ) : 'top|0';
            $wcz_pbadge_vert_offset_arr = explode( '|', $wcz_pbadge_vert_offset );
        } else {
            $wcz_pbadge_horiz_offset = get_post_meta( $badgeid, 'wcz-pbadge-horiz-prod-offset', true ) ? get_post_meta( $badgeid, 'wcz-pbadge-horiz-prod-offset', true ) : 'right|0';
            $wcz_pbadge_horiz_offset_arr = explode( '|', $wcz_pbadge_horiz_offset );
            $wcz_pbadge_vert_offset = get_post_meta( $badgeid, 'wcz-pbadge-vert-prod-offset', true ) ? get_post_meta( $badgeid, 'wcz-pbadge-vert-prod-offset', true ) : 'top|0';
            $wcz_pbadge_vert_offset_arr = explode( '|', $wcz_pbadge_vert_offset );
        }
    } else {
        $wcz_pbadge_horiz_offset = get_post_meta( $badgeid, 'wcz-pbadge-horiz-shop-offset', true ) ? get_post_meta( $badgeid, 'wcz-pbadge-horiz-shop-offset', true ) : 'right|0';
        $wcz_pbadge_horiz_offset_arr = explode( '|', $wcz_pbadge_horiz_offset );
        $wcz_pbadge_vert_offset = get_post_meta( $badgeid, 'wcz-pbadge-vert-shop-offset', true ) ? get_post_meta( $badgeid, 'wcz-pbadge-vert-shop-offset', true ) : 'top|0';
        $wcz_pbadge_vert_offset_arr = explode( '|', $wcz_pbadge_vert_offset );
    }

    $wcz_pbadge_switch = is_product() && 'inherit' !== get_post_meta( $badgeid, 'wcz-pbadge-prod-switch', true ) ? get_post_meta( $badgeid, 'wcz-pbadge-prod-switch', true ) : get_post_meta( $badgeid, 'wcz-pbadge-switch', true );
    if ( is_product() && $woocommerce_loop['name'] == 'related' ) {
        $wcz_pbadge_switch = get_post_meta( $badgeid, 'wcz-pbadge-switch', true );
    }
    $wcz_uploaded_badge = get_post_meta( $badgeid, 'wcz-upmedia', true );
    $img = wp_get_attachment_image_src( $wcz_uploaded_badge, 'full' );
    $wcz_pbadge_mwidth = get_post_meta( $badgeid, 'wcz-pbadge-mwidth', true );

    ob_start(); ?>
        <div class="wcz-pbadge <?php echo is_product() && $woocommerce_loop['name'] !== 'related' ? sanitize_html_class( 'mbadge' ) : ''; ?> badge-<?php echo sanitize_html_class( $badgeid ); ?> <?php echo sanitize_html_class( $wcz_pbadge_position ); ?> <?php echo 'true' == $wcz_pbadge_switch ? sanitize_html_class( 'switch' ) : ''; ?>" data-posval="<?php echo esc_attr( $wcz_pbadge_position ); ?>" data-belement="<?php echo esc_attr( $wcz_pbadge_belement ); ?>">
            <?php if ( 'custom' == $wcz_pbadge_design ) : ?>
                <div class="wcz-pbadge-in" data-badge="<?php echo esc_attr( $wcz_pbadge_design ); ?>" style="max-width: <?php echo esc_attr( $wcz_pbadge_mwidth ); ?>px; <?php echo esc_attr( $wcz_pbadge_horiz_offset_arr[0] ); ?>: <?php echo esc_attr( $wcz_pbadge_horiz_offset_arr[1] ) . 'px  !important'; ?>; <?php echo esc_attr( $wcz_pbadge_vert_offset_arr[0] ); ?>: <?php esc_attr( $wcz_pbadge_vert_offset_arr[1] ) . 'px !important'; ?>;">
                    <img src="<?php echo esc_url( $img[0] ); ?>" alt="<?php echo esc_attr( $wcz_pbadge_text2 ); ?>"  />
                </div>
            <?php else : ?>
                <div class="wcz-pbadge-in" data-badge="<?php echo esc_attr( $wcz_pbadge_design ); ?>" style="<?php echo esc_attr( $wcz_pbadge_horiz_offset_arr[0] ); ?>: <?php echo esc_attr( $wcz_pbadge_horiz_offset_arr[1] ) . 'px !important'; ?>; <?php echo esc_attr( $wcz_pbadge_vert_offset_arr[0] ); ?>: <?php echo esc_attr( $wcz_pbadge_vert_offset_arr[1] ) . 'px !important'; ?>;">
                    <?php wcz_get_badge( $wcz_pbadge_design, $wcz_pbadge_text3 ); ?>
                </div>
            <?php endif; ?>
        </div><?php
    echo ob_get_clean();
}

/**
 * The badge html
 */
function wcz_get_badge( $wcz_pbadge_design, $wcz_pbadge_text ) {
    switch ( $wcz_pbadge_design ) {
        case 'two': ?>
            <div class="wczbadge bcbc1 bfc2 two"><div class="wczbadge-inner"><span><?php echo esc_html( $wcz_pbadge_text ); ?></span></div></div><?php
            break;
        case 'three': ?>
            <div class="wczbadge bfc2 three"><div class="wczbblk bbrc1 bblc1"></div><div class="wczbadge-inner"><span><?php echo esc_html( $wcz_pbadge_text ); ?></span></div></div><?php
            break;
        case 'four': ?>
            <div class="wczbadge bfc2 bbbc1 four"><div class="wczbadge-inner"><span><?php echo esc_html( $wcz_pbadge_text ); ?></span></div></div><?php
            break;
        case 'five': ?>
            <div class="wczbadge bfc2 bbgc1 five"><div class="wczbadge-inner"><span><?php echo esc_html( $wcz_pbadge_text ); ?></span></div></div><?php
            break;
        case 'six': ?>
            <div class="wczbadge bfc2 bbgc1 six"><div class="wczbadge-inner"><div class="wczbblk bbrc1 bblc1"></div><span><?php echo esc_html( $wcz_pbadge_text ); ?></span><div class="wczablk"></div></div></div><?php
            break;
        case 'seven': ?>
            <div class="wczbadge bfc2 bbtc1 seven"><div class="wczbadge-inner"><span><?php echo esc_html( $wcz_pbadge_text ); ?></span></div></div><?php
            break;
        case 'eight': ?>
            <div class="wczbadge bfc2 bbtc1 eight"><div class="wczbadge-inner"><span><?php echo esc_html( $wcz_pbadge_text ); ?></span></div></div><?php
            break;
        case 'nine': ?>
            <div class="wczbadge bbc1 bfc2 bbgc1 nine"><div class="wczbadge-inner"><span><?php echo esc_html( $wcz_pbadge_text ); ?></span></div></div><?php
            break;
        case 'ten': ?>
            <div class="wczbadge bbc1 bfc2 bbgc1 ten"><div class="wczbadge-inner"><span><?php echo esc_html( $wcz_pbadge_text ); ?></span></div></div><?php
            break;
        case 'eleven': ?>
            <div class="wczbadge eleven"><div class="wczbblk bbgc1"></div><div class="wczbadge-inner"></div><div class="wczablk bbgc1"></div></div><?php
            break;
        case 'twelve': ?>
            <div class="wczbadge bfc2 bbgc1 twelve"><div class="wczbblk bbbc1"></div><div class="wczbadge-inner"><span><?php echo esc_html( $wcz_pbadge_text ); ?></span></div><div class="wczablk bbbc1"></div></div><?php
            break;
        case 'thirteen': ?>
            <div class="wczbadge bfc2 bbgc1 thirteen"><div class="wczbadge-inner"><span><?php echo esc_html( $wcz_pbadge_text ); ?></span></div></div><?php
            break;
        case 'fourteen': ?>
            <div class="wczbadge bfc2 bbgc1 fourteen"><div class="wczbblk bbtc1"></div><div class="wczbadge-inner"><span><?php echo esc_html( $wcz_pbadge_text ); ?></span></div></div><?php
            break;
        case 'fiveteen': ?>
            <div class="wczbadge bfc2 fiveteen"><div class="wczbadge-inner bbgc1"><span><?php echo esc_html( $wcz_pbadge_text ); ?></span></div></div><?php
            break;
        case 'sixteen': ?>
            <div class="wczbadge bfc2 sixteen"><div class="wczbblk bbrc1 bblc1"></div><div class="wczbadge-inner bbgc1"><span><?php echo esc_html( $wcz_pbadge_text ); ?></span></div><div class="wczablk bbrc1 bblc1"></div></div><?php
            break;
        case 'seventeen': ?>
            <div class="wczbadge bbrc1 bblc1 seventeen"><div class="wczbadge-inner"></div><div class="wczablk bbtc1 bbbc1"></div></div><?php
            break;
        case 'eightteen': ?>
            <div class="wczbadge bfc2 eightteen"><div class="wczbblk bbbc1"></div><div class="wczbadge-inner"><span><?php echo esc_html( $wcz_pbadge_text ); ?></span></div><div class="wczablk bbtc1"></div></div><?php
            break;
        default: ?>
            <div class="wczbadge bfc2 bbgc1 one"><div class="wczbadge-inner"><span><?php echo esc_html( $wcz_pbadge_text ); ?></span></div></div><?php
    }
}

/**
 * Add Product Badges CSS to footer.
 */
function wcz_product_badges_css() {
    // Get Product Badges
    $wczpbargs = array(
        'post_type'=> 'wcz-badges',
        'post_status' => 'publish',
        'posts_per_page' => -1,
    );              
    
    $wcz_badge_css = '';
    $wcz_badges = new WP_Query( $wczpbargs );
        if( $wcz_badges->have_posts() ) : 
            while ( $wcz_badges->have_posts() ) : $wcz_badges->the_post();
                
                $badge_id = get_the_ID();
                $wcz_pbadge_bcolor = get_post_meta( $badge_id, 'wcz_pbadge_color', true );
                $wcz_pbadge_fcolor = get_post_meta( $badge_id, 'wcz_pbadge_font_color', true );

                if ( $wcz_pbadge_bcolor ) {
                    $wcz_badge_css .= '.wcz-pbadge.badge-' . esc_attr( $badge_id ) . ' .bbgc1 { background-color: ' . esc_attr( $wcz_pbadge_bcolor ) . ' !important; }
                    .wcz-pbadge.badge-' . esc_attr( $badge_id ) . ' .bbc1 { box-shadow: 0 0 0 1px ' . esc_attr( $wcz_pbadge_bcolor ) . ' !important; }
                    .wcz-pbadge.badge-' . esc_attr( $badge_id ) . ' .bblc1 { border-left-color: ' . esc_attr( $wcz_pbadge_bcolor ) . ' !important; }
                    .wcz-pbadge.badge-' . esc_attr( $badge_id ) . ' .bbrc1 { border-right-color: ' . esc_attr( $wcz_pbadge_bcolor ) . ' !important; }
                    .wcz-pbadge.badge-' . esc_attr( $badge_id ) . ' .bbtc1 { border-top-color: ' . esc_attr( $wcz_pbadge_bcolor ) . ' !important; }
                    .wcz-pbadge.badge-' . esc_attr( $badge_id ) . ' .bbbc1 { border-bottom-color: ' . esc_attr( $wcz_pbadge_bcolor ) . ' !important; }
                    .wcz-pbadge.badge-' . esc_attr( $badge_id ) . ' .bcbc1 { border-color: ' . esc_attr( $wcz_pbadge_bcolor ) . ' !important; color: ' . esc_attr( $wcz_pbadge_bcolor ) . ' !important; }';
                }
                if ( $wcz_pbadge_fcolor ) {
                    $wcz_badge_css .= '.wcz-pbadge.badge-' . esc_attr( $badge_id ) . ' .bfc2 {
                        color: ' . esc_attr( $wcz_pbadge_fcolor ) . ' !important;
                    }';
                }

            endwhile; 
            wp_reset_postdata(); 
        else: 
    endif;

    if ( !empty( $wcz_badge_css ) ) {
        wp_register_style( 'wcz-pbadges', false );
        wp_enqueue_style( 'wcz-pbadges' );
        wp_add_inline_style( 'wcz-pbadges', $wcz_badge_css );
    }
}
add_action( 'wp_footer', 'wcz_product_badges_css' );

/**
 * Add Custom Admin Columns for Product Badges.
 */
function wcz_add_badge_preview_column( $columns ) {
    $columns = array(
        'cb' => $columns['cb'],
        'title' => __( 'Title', 'woocustomizer' ),
        'wcz-def-badge-col' => 'id-default' !== get_option( 'wcz_set_default_sale_badge', woocustomizer_library_get_default( 'wcz_set_default_sale_badge' ) ) ? __( 'Set as default badge', 'woocustomizer' ) : '',
        'wcz-badge-col' => __( 'Badge Preview', 'woocustomizer' ),
        'date' => __( 'Date', 'woocustomizer' ),
      );
    return $columns;
}
add_filter( 'manage_wcz-badges_posts_columns', 'wcz_add_badge_preview_column' );

/**
 * Build Custom Column Content.
 */
function wcz_display_column_badge( $column, $post_id ) {
    switch ( $column ) {
        case 'wcz-badge-col' :
            $wcz_pbadge_design = get_post_meta( get_the_ID(), 'wcz-pbadge-design', true );
            $wcz_pbadge_switch = get_post_meta( get_the_ID(), 'wcz-pbadge-switch', true );

            $wcz_pbadge_text = get_post_meta( get_the_ID(), 'wcz-pbadge-text', true );
            $wcz_pbadge_text2 = str_replace( '[percent]', '10%', $wcz_pbadge_text );
            $wcz_pbadge_text3 = str_replace( '[amount]', get_woocommerce_currency_symbol().'10', $wcz_pbadge_text2 );
            
            $wcz_pbadge_bcolor = get_post_meta( get_the_ID(), 'wcz_pbadge_color', true );
            $wcz_pbadge_fcolor = get_post_meta( get_the_ID(), 'wcz_pbadge_font_color', true ); ?>
            <div class="wcz-badge-col-inner wcz-pbadge badge-<?php echo get_the_ID(); ?> <?php echo 'true' == $wcz_pbadge_switch ? sanitize_html_class( 'switch' ) : ''; ?>">
                <?php
                if ( 'custom' == $wcz_pbadge_design ) {
                    $wcz_uploaded_badge = get_post_meta( get_the_ID(), 'wcz-upmedia', true );
                    $img = wp_get_attachment_image_src( $wcz_uploaded_badge, 'full' );
                    if ( $img[0] ) {
                        echo '<img src="' . esc_url( $img[0] ) . '" />';
                    } else {
                        echo '<span class="dashicons dashicons-format-image"></span>';
                    }
                } else {
                    wcz_get_badge( $wcz_pbadge_design, $wcz_pbadge_text3 );
                } ?>
            </div>
            <?php
            $wcz_adbadge_css = '';
            if ( $wcz_pbadge_bcolor ) {
                $wcz_adbadge_css .= '.wcz-badge-col-inner.badge-' . esc_attr( get_the_ID() ) . ' .bbgc1 { background-color: ' . esc_attr( $wcz_pbadge_bcolor ) . ' !important; }
                .wcz-badge-col-inner.badge-' . esc_attr( get_the_ID() ) . ' .bbc1 { box-shadow: 0 0 0 1px ' . esc_attr( $wcz_pbadge_bcolor ) . ' !important; }
                .wcz-badge-col-inner.badge-' . esc_attr( get_the_ID() ) . ' .bblc1 { border-left-color: ' . esc_attr( $wcz_pbadge_bcolor ) . ' !important; }
                .wcz-badge-col-inner.badge-' . esc_attr( get_the_ID() ) . ' .bbrc1 { border-right-color: ' . esc_attr( $wcz_pbadge_bcolor ) . ' !important; }
                .wcz-badge-col-inner.badge-' . esc_attr( get_the_ID() ) . ' .bbtc1 { border-top-color: ' . esc_attr( $wcz_pbadge_bcolor ) . ' !important; }
                .wcz-badge-col-inner.badge-' . esc_attr( get_the_ID() ) . ' .bbbc1 { border-bottom-color: ' . esc_attr( $wcz_pbadge_bcolor ) . ' !important; }
                .wcz-badge-col-inner.badge-' . esc_attr( get_the_ID() ) . ' .bcbc1 { border-color: ' . esc_attr( $wcz_pbadge_bcolor ) . ' !important; color: ' . esc_attr( $wcz_pbadge_bcolor ) . ' !important; }';
            }
            if ( $wcz_pbadge_fcolor ) {
                $wcz_adbadge_css .= '.wcz-badge-col-inner.badge-' . esc_attr( get_the_ID() ) . ' .bfc2 { color: ' . esc_attr( $wcz_pbadge_fcolor ) . ' !important; }';
            }

            wp_register_style( 'wcz-adpbadges-' . get_the_ID() , false );
            wp_enqueue_style( 'wcz-adpbadges-' . get_the_ID() );
            wp_add_inline_style( 'wcz-adpbadges-' . get_the_ID(), $wcz_adbadge_css );
            break;

        case 'wcz-def-badge-col' :
            $wcz_def_badge = substr( get_option( 'wcz_set_default_sale_badge', woocustomizer_library_get_default( 'wcz_set_default_sale_badge' ) ), 3 );

            if ( 'default' !== $wcz_def_badge && $post_id == $wcz_def_badge ) {
                echo '<div class="wcz-badge-col-inner"><span class="wcz-is-default" title="' . __( 'This badge is set as the new default Sale badge', 'woocustomizer' ) . '"></span></div>';
            }
    }
}
add_action( 'manage_wcz-badges_posts_custom_column' , 'wcz_display_column_badge', 10, 2 );

/**
 * Search for Badges.
 */
function wcz_get_badges_ajax() {
    $return = array();

    $search_results = new WP_Query( array(
        's'=> $_GET['q'],
        'post_type' => 'wcz-badges',
        'post_status' => 'publish',
        'ignore_sticky_posts' => 1,
        'posts_per_page' => 20
    ) );

    if ( $search_results->have_posts() ) :
        while( $search_results->have_posts() ) : $search_results->the_post();
            // shorten the title a little
            $title = ( mb_strlen( $search_results->post->post_title ) > 50 ) ? mb_substr( $search_results->post->post_title, 0, 49 ) . '...' : $search_results->post->post_title;
            $return[] = array( $search_results->post->ID, $title ); // array( Post ID, Post Title )
        endwhile;
    endif;

    wp_send_json( $return );
}
add_action( 'wp_ajax_wczbadgesearch', 'wcz_get_badges_ajax' );

/**
 * Badges selector using ajax.
 */
function wcz_categories_select_badges( $taxonomy ) {
    // Nonce field to validate form request came from current site
    wp_nonce_field( basename( __FILE__ ), '_feature_post_nonce' ); ?>
        <tr class="form-field">
            <th scope="row" valign="top"><label for="catshort_button_type"><?php esc_html_e( 'Select Product Badge(s) for this category', 'woocustomizer' ); ?></label></th>
            <td>
                <select id="wcz_product_category_badges" name="wcz_product_category_badges[]" multiple="multiple" style="width:95%;max-width: 542px;"><?php
                    $term_id = isset($_GET['tag_ID']) ? $_GET['tag_ID'] : '';

                    if ( $wcz_badge_ids = get_term_meta( $term_id, 'wcz_product_category_badges', true ) ) {
                        foreach ( $wcz_badge_ids as $post_id ) {
                            $title = get_the_title( $post_id );
                            $title = ( mb_strlen( $title ) > 50 ) ? mb_substr( $title, 0, 49 ) . '...' : $title; ?>
                            <option value="<?php echo esc_attr( $post_id ); ?>" selected="selected"><?php echo esc_html( $title ); ?></option><?php
                        }
                    } ?>
                </select>
            </td>
        </tr>
        <style type="text/css">iframe#description_ifr {min-height:220px !important;}</style>
        <script>
        // multiple select with AJAX search
        jQuery(function($) {
            $( '#wcz_product_category_badges' ).select2({
                ajax: {
                    url: ajaxurl, // AJAX URL is predefined in WordPress admin
                    dataType: 'json',
                    delay: 250, // delay in ms while typing when to perform a AJAX search
                    data: function ( params ) {
                        return {
                            q: params.term, // search query
                            action: 'wczbadgesearch' // AJAX action for admin-ajax.php
                        };
                    },
                    processResults: function( data ) {
                        var options = [];
                        if ( data ) {
                            // data is the array of arrays, and each of them contains ID and the Label of the option
                            $.each( data, function( index, text ) { // 'index' is just auto incremented value
                                options.push( { id: text[0], text: text[1]  } );
                            });
                        }
                        return {
                            results: options
                        };
                    },
                    cache: true
                },
                minimumInputLength: 2 // the minimum of symbols to input before perform a search
            });
        });
    </script><?php
}
add_action( 'product_cat_add_form_fields', 'wcz_categories_select_badges', 10, 1 );
add_action( 'product_cat_edit_form_fields', 'wcz_categories_select_badges', 10, 1 );

// Save extra taxonomy fields callback function.
function wcz_categories_select_badges_save( $term_id, $term_taxonomy_id ) {

    $wcz_cat_badges = $_POST['wcz_product_category_badges'] ? $_POST['wcz_product_category_badges'] : '';
    update_term_meta( $term_id, 'wcz_product_category_badges', $wcz_cat_badges );

    // if ( isset( $_POST['wcz_product_category_badges'] ) && $wcz_badge_ids = $_POST['wcz_product_category_badges'] )
    //     update_term_meta( $term_id, 'wcz_product_category_badges', $_POST['wcz_product_category_badges'] );
}
add_action( 'edited_product_cat', 'wcz_categories_select_badges_save', 10, 2 );
add_action( 'create_product_cat', 'wcz_categories_select_badges_save', 10, 2 );

// Edit Sale Banner text for shop / product pages
function wcz_pb_replace_default_sale_banner() {
	global $woocommerce_loop;

    if ( 'on' == get_option( 'wcz_set_enable_product_badges', woocustomizer_library_get_default( 'wcz_set_enable_product_badges' ) ) && 'id-default' == get_option( 'wcz_set_default_sale_badge', woocustomizer_library_get_default( 'wcz_set_default_sale_badge' ) ) ) {
        
        if ( is_product() ) {
            if ( $woocommerce_loop['name'] == 'related' ) {
                $setting = 'wcz-shop-sale-txt';
            } else {
                $setting = 'wcz-product-sale-txt';
            }
        } else {
            $setting = 'wcz-shop-sale-txt';
        }
        $mod = get_option( $setting, woocustomizer_library_get_default( $setting ) );
        return '<span class="onsale">' . esc_html( $mod ) . '</span>';

    } else {

        $sale_badge = substr( get_option( 'wcz_set_default_sale_badge', woocustomizer_library_get_default( 'wcz_set_default_sale_badge' ) ), 3 );

        $_product = wc_get_product( get_the_ID() );

        wcz_badge( $sale_badge );

    }

}
add_filter( 'woocommerce_sale_flash', 'wcz_pb_replace_default_sale_banner', 11, 3 );
