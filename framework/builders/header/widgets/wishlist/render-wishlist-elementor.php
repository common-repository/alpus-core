<?php
/**
 * Header wishlist template
 *
 * @author     D-THEMES
 *
 * @since      1.0
 */
defined( 'ABSPATH' ) || die;

extract( // @codingStandardsIgnoreLine
    shortcode_atts(
        array(
            'type'         => 'block',
            'show_label'   => true,
            'show_count'   => false,
            'show_icon'    => true,
            'icon_pos'     => true,
            'label'        => esc_html__( 'Wishlist', 'alpus-core' ),
            'icon'         => ALPUS_ICON_PREFIX . '-icon-heart',
            'miniwishlist' => '',
        ),
        $atts
    )
);

if ( class_exists( 'YITH_WCWL' ) ) {
    $wc_link  = YITH_WCWL()->get_wishlist_url();
    $wc_count = yith_wcwl_count_products();

    $wishlist       = YITH_WCWL_Wishlist_Factory::get_current_wishlist( array() );
    $wishlist_items = array();

    if ( $wishlist && $wishlist->has_items() ) {
        $wishlist_items = $wishlist->get_items();
    }

    echo '<div class="dropdown wishlist-dropdown mini-basket-box' . ( $miniwishlist ? ( ( 'offcanvas' == $miniwishlist ? ' offcanvas ' : ' ' ) . esc_attr( $miniwishlist ) . '-type" data-miniwishlist-type="' . esc_attr( $miniwishlist ) ) : '' ) . '">';

    if ( '' === $type ) {
        $type = 'inline';
    }

    ?>
	<a aria-label="Wishlist" class="wishlist offcanvas-open <?php echo esc_attr( $type . '-type' ); ?>" href="<?php echo esc_url( $wc_link ); ?>">
	<?php if ( $icon_pos || 'block' === $type ) { ?>
			<?php if ( $show_icon ) { ?>
			<i class="<?php echo esc_attr( $icon ); ?>">
				<?php if ( $show_count ) { ?>
					<span class="wish-count"><?php echo esc_html( $wc_count ); ?></span>
				<?php } ?>
			</i>
				<?php
			}
	}
    ?>
		<?php if ( $show_label ) { ?>
		<span><?php echo esc_html( $label ); ?></span>
		<?php } ?>
		<?php if ( ! $icon_pos && 'inline' === $type ) { ?>
			<?php if ( $show_icon ) { ?>
			<i class="<?php echo esc_attr( $icon ); ?>">
				<?php if ( $show_count ) { ?>
				<span class="wish-count"><?php echo esc_html( $wc_count ); ?></span>
			<?php } ?>
			</i>
				<?php
			}
		}
    ?>
	</a>
	<?php
    if ( $miniwishlist ) {
        if ( 'offcanvas' == $miniwishlist ) {
            echo '<div class="offcanvas-overlay"></div>';
        }
        ?>

		<div class="<?php echo 'offcanvas' === $miniwishlist ? 'offcanvas-content' : 'dropdown-box'; ?>">
			<?php
            if ( 'offcanvas' == $miniwishlist ) {
                echo '<div class="popup-header"><h3>' . esc_html__( 'Wishlist', 'alpus-core' ) . '</h3><a class="btn btn-link btn-icon-after btn-close" href="#">' . esc_html__( 'close', 'alpus-core' ) . '<i class="' . ALPUS_ICON_PREFIX . '-icon-long-arrow-' . ( is_rtl() ? 'left' : 'right' ) . '"></i></a></div>';
            }
        ?>
			<div class="widget_wishlist_content">
			<?php if ( empty( $wishlist_items ) ) { ?>
				<p class="empty-msg"><?php esc_html_e( 'No products in wishlist.', 'alpus-core' ); ?></p>
			<?php } else { ?>
				<ul class="scrollable mini-list wish-list">
				<?php
            foreach ( $wishlist_items as $item ) {
                $product = $item->get_product();

                if ( $product ) {
                    $id                = $product->get_ID();
                    $product_name      = $product->get_data()['name'];
                    $thumbnail         = $product->get_image( 'alpus-product-thumbnail', array( 'class' => 'do-not-lazyload' ) );
                    $product_price     = $product->get_price_html();
                    $product_permalink = $product->is_visible() ? $product->get_permalink() : '';

                    if ( ! $product_price ) {
                        $product_price = '';
                    }

                    echo '<li class="mini-item wishlist-item">';

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

                    echo '<a href="#" class="remove remove_from_wishlist" data-product_id="' . alpus_escaped( $id ) . '"><i class="fas fa-times"></i></a>';

                    echo '</li>';
                }
            }
			    ?>
				</ul>
				<p class="wishlist-buttons buttons">
					<a href="<?php echo esc_url( $wc_link ); ?>" class="btn btn-dark btn-md btn-block"><?php esc_html_e( 'Go To Wishlist', 'alpus-core' ); ?></a>
				</p>
			<?php } ?>

			<?php
			    // print templates for js work
			    ob_start();
        ?>
				<p class="empty-msg"><?php esc_html_e( 'No products in wishlist.', 'alpus-core' ); ?></p>
				<?php
            echo '<script ' . apply_filters( 'alpus_script_tag', true ) . ' class="alpus-miniwishlist-no-item-html">' . ob_get_clean() . '</script>';
        ?>

			</div>
		</div>
		<?php
    }
    ?>
	</div>
	<?php
} else {
    esc_html_e( 'Install YITH WooCommerce Wishlist plugin.', 'alpus-core' );
}
