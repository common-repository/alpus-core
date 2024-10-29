<?php
/**
 * Header compare template
 *
 * @author     D-THEMES
 *
 * @since      1.0
 */
defined( 'ABSPATH' ) || die;

extract( // @codingStandardsIgnoreLine
    shortcode_atts(
        array(
            'type'        => 'block',
            'show_icon'   => true,
            'icon_pos'    => 'yes',
            'show_badge'  => true,
            'show_label'  => true,
            'icon'        => ALPUS_ICON_PREFIX . '-icon-compare',
            'label'       => esc_html( 'Compare', 'alpus-core' ),
            'minicompare' => '',
        ),
        $atts
    )
);

$minicompare = ! empty( $minicompare ) ? $minicompare : '';

$badge    = 0;
$prod_ids = array();

if ( ! class_exists( 'Alpus_Product_Compare' ) ) {
    return;
}

$cookie_name = Alpus_Product_Compare::get_instance()->compare_cookie_name();

if ( isset( $_COOKIE[ $cookie_name ] ) ) {
    $prod_ids = json_decode( sanitize_text_field( wp_unslash( $_COOKIE[ $cookie_name ] ) ), true );

    $badge = count( $prod_ids );
}

if ( $minicompare ) {
    echo '<div class="dropdown compare-dropdown mini-basket-box ' . ( 'offcanvas' == $minicompare ? 'offcanvas ' : ' ' ) . esc_attr( $minicompare ) . '-type" data-minicompare-type="' . esc_attr( $minicompare ) . '">';
}

if ( '' === $type ) {
    $type = 'inline';
}
?>

<a class="offcanvas-open<?php echo esc_attr( $type ? ( ' ' . $type . '-type' ) : '' ); ?>" href="<?php echo esc_url( get_permalink( wc_get_page_id( 'compare' ) ) ); ?>">
	<?php if ( $show_icon ) { ?>
		<?php if ( 'block' === $type || 'yes' === $icon_pos ) { ?>
			<i class="<?php echo esc_attr( $icon ); ?>">
			<?php if ( $show_badge ) { ?>
				<span class="compare-count"><?php echo absint( $badge ); ?></span>
			<?php } ?>
			</i>
		<?php } ?>
	<?php } ?>
	<?php if ( $show_label ) { ?>
		<span><?php echo esc_html( $label ); ?></span>		
	<?php } ?>
	<?php if ( $show_icon ) { ?>
		<?php if ( '' === $icon_pos && 'inline' === $type ) { ?>
			<i class="<?php echo esc_attr( $icon ); ?>">
				<?php if ( $show_badge ) { ?>
				<span class="compare-count"><?php echo absint( $badge ); ?></span>
				<?php } ?>
			</i>
		<?php } ?>
	<?php } ?>
</a>

<?php
if ( $minicompare ) {
    if ( 'offcanvas' == $minicompare ) {
        ?>
		<div class="offcanvas-overlay"></div>
	<?php } ?>

	<div class="<?php echo 'offcanvas' === $minicompare ? 'offcanvas-content' : 'dropdown-box'; ?>">
		<?php
        if ( 'offcanvas' == $minicompare ) {
            echo '<div class="popup-header"><h3>' . esc_html__( 'Compare', 'alpus-core' ) . '</h3><a class="btn btn-link btn-icon-right btn-close" href="#">' . esc_html__( 'close', 'alpus-core' ) . '<i class="' . ALPUS_ICON_PREFIX . '-icon-long-arrow-' . ( is_rtl() ? 'left' : 'right' ) . '"></i></a></div>';
        }
    ?>
		<div class="widget_compare_content">
		<?php if ( empty( $prod_ids ) ) { ?>
			<p class="empty-msg"><?php esc_html_e( 'No products in compare list.', 'alpus-core' ); ?></p>
		<?php } else { ?>
			<ul class="scrollable mini-list compare-list">
				<?php
            foreach ( $prod_ids as $id ) {
                $product = wc_get_product( $id );

                if ( $product ) {
                    $product_name      = $product->get_data()['name'];
                    $thumbnail         = $product->get_image( 'alpus-product-thumbnail', array( 'class' => 'do-not-lazyload' ) );
                    $product_price     = $product->get_price_html();
                    $product_permalink = $product->is_visible() ? $product->get_permalink() : '';

                    if ( ! $product_price ) {
                        $product_price = '';
                    }

                    echo '<li class="mini-item compare-item">';

                    echo '<div class="mini-item-meta">';

                    if ( empty( $product_permalink ) ) {
                        echo alpus_escaped( $product_name );
                    } else {
                        echo '<a href="' . esc_url( $product_permalink ) . '">' . alpus_escaped( $product_name ) . '</a>';
                    }
                    echo '<span class="quantity">' . alpus_escaped( $product_price ) . '</span>';

                    echo '</div>';

                    if ( empty( $product_permalink ) ) {
                        echo alpus_escaped( $thumbnail );
                    } else {
                        echo '<a href="' . esc_url( $product_permalink ) . '">' . alpus_escaped( $thumbnail ) . '</a>';
                    }

                    echo '<a href="#" class="remove remove_from_compare" data-product_id="' . absint( $id ) . '"><i class="fas fa-times"></i></a>';

                    echo '</li>';
                }
            }
		    ?>
			</ul>
			<p class="compare-buttons buttons">
				<a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'compare' ) ) ); ?>" class="btn btn-dark btn-md btn-block"><?php esc_html_e( 'Go To Compare List', 'alpus-core' ); ?></a>
			</p>
		<?php } ?>

		<?php
            // print templates for js work
            ob_start();
    ?>
			<p class="empty-msg"><?php esc_html_e( 'No products in compare list.', 'alpus-core' ); ?></p>
			<?php
        echo '<script ' . apply_filters( 'alpus_script_tag', true ) . ' class="alpus-minicompare-no-item-html">' . ob_get_clean() . '</script>';
    ?>
		</div>
	</div>
</div>
	<?php
}
