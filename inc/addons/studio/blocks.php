<?php
/**
 * Alpus Studio Blocks List Template
 *
 * @author     AlpusTheme
 *
 * @since      1.0
 */
defined( 'ABSPATH' ) || die;

foreach ( $args['blocks'] as $block ) {
    if ( $block instanceof WP_Post ) {
        $template_type = get_post_meta( $block->ID, ALPUS_NAME . '_template_type', true );

        if ( 'shop_layout' == $template_type ) {
            $template_type = 'shop';
        }
        ?>
		<div class="block block-template">
			<div class="block-category">
				<h4 class="block-title"><?php echo esc_html( $block->post_title ); ?></h4>
				<img src="<?php echo ALPUS_CORE_URI; ?>/assets/images/add-on/studio/<?php echo esc_attr( $template_type ); ?>.jpg">
				<div class="block-actions" data-id="<?php echo esc_attr( $block->ID ); ?>" data-category="<?php echo esc_attr( $template_type ); ?>">
					<button class="btn <?php echo boolval( $args['studio']->new_template_mode ) ? 'select' : 'import'; ?>">
						<i class="fas fa-download"></i>
					</button>
				</div>
			</div>
		</div>
		<?php
    } else {
        $class = 'block block-online';

        if ( isset( $args['favourites_map'][ $block['block_id'] ] ) ) {
            $class .= ' favour';
        }
        ?>
		<div class="<?php echo esc_attr( $class ); ?>">
			<div class="block-img-wrapper">
				<div class="block-inner-img-wrapper" style="background-image: url(<?php echo esc_url( ALPUS_SERVER_URI . '/alpus/dummy/images/studio/' . intval( isset( $block['s'] ) ? $block['s'] : $block['block_id'] ) . '.jpg' ); ?>)">
					<img src="<?php echo esc_url( ALPUS_SERVER_URI . 'alpus/dummy/images/studio/' . intval( isset( $block['s'] ) ? $block['s'] : $block['block_id'] ) . '.jpg' ); ?>" alt="<?php echo esc_attr( $block['t'] ); ?>"<?php echo isset( $block['w'] ) && $block['w'] ? ' width="' . intval( $block['w'] ) . '"' : '', isset( $block['h'] ) && $block['h'] ? ' height="' . intval( $block['h'] ) . '"' : ''; ?>>
					<h5 class="block-title"><?php echo esc_html( $block['t'] ); ?></h5>
					<div class="block-actions" data-id="<?php echo esc_attr( $block['block_id'] ); ?>" data-category="<?php echo esc_attr( $block['c'] ); ?>">
						<button class="btn favourite"><i class="far fa-heart"></i></button>
						<button class="btn <?php echo boolval( $args['studio']->new_template_mode ) ? 'select' : 'import'; ?>">
							<i class="fas fa-download"></i>
						</button>
					</div>
				</div>
			</div>
		</div>
		<?php
    }
}
