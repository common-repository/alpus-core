<?php
/**
 * Alpus Studio Blocks Wrapper Template
 *
 * @author     D-THEMES
 *
 * @since      1.0
 */
defined( 'ABSPATH' ) || die;

$is_ajax = alpus_doing_ajax();
?>
<script <?php echo apply_filters( 'alpus_script_tag', true ); ?> id="alpus_studio_blocks_wrapper_template">
	<div class="blocks-overlay closed"></div>
	<div class="blocks-wrapper closed">
		<button title="<?php esc_attr_e( 'Close (Esc)', 'alpus-core' ); ?>" type="button" class="mfp-close"><i class="close-icon"></i></button>
		<div class="blocks-section-switch">
			<a href="#studio-section" class="section-switch active"><?php esc_html_e( 'Studio', 'alpus-core' ); ?></a>
			<a href="#layout-section" class="section-switch"><?php esc_html_e( 'Layout', 'alpus-core' ); ?></a>
		</div>
		<div class="blocks-section-content">
			<div class="blocks-section-pane active" id="studio-section">
				<div class="category-list">
					<?php /* translators: %s represents theme name.*/ ?>
					<h3>
						<figure>
							<img src="<?php echo ALPUS_CORE_URI; ?>/assets/images/logo-studio.png" alt="<?php printf( esc_attr__( '%s Studio', 'alpus-core' ), ALPUS_DISPLAY_NAME ); ?>" width="206" height="73" />
						</figure>
						<?php printf( esc_html__( '%1$s %2$sStudio%3$s', 'alpus-core' ), ALPUS_DISPLAY_NAME, '<span style="color: var(--alpus-primary-color)">', '</span>' ); ?>
					</h3>
					<ul>
						<li class="filtered"><a href="#" data-filter-by="0" data-total-page="<?php echo (int) $args['total_pages']; ?>"></a></li>
						<li>
							<a href="#" class="all active">
							<img src="<?php echo ALPUS_CORE_URI; ?>/assets/images/add-on/studio/icon-all.svg">
								<?php esc_html_e( 'All', 'alpus-core' ); ?>
								<span>(<?php echo (int) $args['total_count']; ?>)</span>
							</a>
						</li>

				<?php
                foreach ( $args['big_categories'] as $big_category ) {
                    if ( in_array( $big_category, $args['has_children'] ) ) {
                        $children = '';

                        ob_start();

                        foreach ( $args['categories'] as $category ) {
                            if ( in_array( $category['title'], $args[ $big_category . '_categories' ] ) && $category['count'] > 0 ) {
                                ?>
								<li>
									<a href="#" class="block-category-<?php echo esc_attr( $category['title'] ); ?>" data-title="<?php echo esc_attr( $category['title'] ); ?>" data-filter-by="<?php echo (int) $category['id']; ?>" data-total-page="<?php echo (int) ( $category['total'] ); ?>">
										<?php echo esc_html( $args['studio']->get_category_title( $category['title'] ) ); ?>
										<?php echo ' <span>(' . (int) $category['count'] . ')</span>'; ?>
									</a>
								</li>
								<?php
                            }
                        }

                        $children = ob_get_clean();

                        if ( $children ) {
                            ?>
							<li class="category-has-children">
								<?php
                                $big_category_filter = '';

                            foreach ( $args['categories'] as $category ) {
                                if ( in_array( $category['title'], $args[ $big_category . '_categories' ] ) && $category['count'] > 0 ) {
                                    $big_category_filter = $category['id'];
                                    break;
                                }
                            }

                            $big_category_count = 0;

                            foreach ( $args['categories'] as $category ) {
                                if ( in_array( $category['title'], $args[ $big_category . '_categories' ] ) ) {
                                    $big_category_count += (int) $category['count'];
                                }
                            }
                            ?>

								<a href="#" class="block-category-<?php echo esc_attr( $big_category ); ?>" <?php echo ! $big_category_filter ? '' : ( 'data-filter-by="' . esc_attr( $big_category_filter ) . '"' ); ?> data-total-page="<?php echo (int) $args['blocks_pages']; ?>">
									<img src="<?php echo ALPUS_CORE_URI; ?>/assets/images/add-on/studio/icon-<?php echo esc_attr( $big_category ); ?>.svg">
									<?php echo esc_html( $args['studio']->get_category_title( $big_category ) ); ?><i class="<?php echo esc_attr( ALPUS_ICON_PREFIX . '-icon-angle-down' ); ?>" data-toggle="<?php echo esc_attr( ALPUS_ICON_PREFIX . '-icon-angle-down ' . ALPUS_ICON_PREFIX . '-icon-angle-up' ); ?>"></i>
									<?php echo ' <span>(' . (int) $big_category_count . ')</span>'; ?>
								</a>
								<ul><?php echo alpus_strip_script_tags( $children ); ?></ul>
							</li>
							<?php
                        }
                    } else {
                        foreach ( $args['categories'] as $category ) {
                            if ( $category['title'] == $big_category ) {
                                if ( 'favourites' == $big_category || 'my-templates' == $big_category ) {
                                    ?>

									<li>
										<a href="#" class="block-category-<?php echo esc_attr( $category['title'] ); ?>" data-title="<?php echo esc_attr( $category['title'] ); ?>" data-filter-by="<?php echo esc_attr( $category['id'] ); ?>" data-total-page="<?php echo (int) ( $category['total'] ); ?>">
											<img src="<?php echo ALPUS_CORE_URI; ?>/assets/images/add-on/studio/icon-<?php echo esc_attr( $big_category ); ?>.svg">
											<?php
                                            echo esc_html( $args['studio']->get_category_title( $category['title'] ) );
                                    echo ' <span>(' . (int) $category['count'] . ')</span>';
                                    ?>
										</a>
									</li>
									<?php
                                } else {
                                    ?>

								<li>
									<a href="#" class="block-category-<?php echo esc_attr( $big_category ); ?>" data-title="<?php echo esc_attr( $category['title'] ); ?>" data-filter-by="<?php echo esc_attr( $category['id'] ); ?>" data-total-page="<?php echo (int) ( $category['total'] ); ?>">
										<img src="<?php echo ALPUS_CORE_URI; ?>/assets/images/add-on/studio/icon-<?php echo esc_attr( $big_category ); ?>.svg">
										<?php echo esc_html( $args['studio']->get_category_title( $big_category ) ); ?>
										<?php echo ' <span>(' . (int) $category['count'] . ')</span>'; ?>
									</a>
								</li>
									<?php
                                }
                            }
                        }
                    }
                }
?>
					</ul>
				</div>
				<div class="blocks-section">
					<div class="blocks-section-inner">
						<div class="blocks-row">
							<div class="blocks-title">
								<h3><?php esc_html_e( 'All in One Library', 'alpus-core' ); ?></h3>
								<p><?php esc_html_e( 'Choose any type of template from our library.', 'alpus-core' ); ?></p>
							</div>
							<div class="demo-filter">
								<form action="#" class="input-wrapper">
									<input type="search" name="search" placeholder="<?php echo esc_attr_e( 'Search Your Keyword', 'alpus-core' ); ?>" />
									<button class="btn btn-search" aria-label="<?php esc_attr_e( 'Search Button', 'alpus-core' ); ?>" type="submit">
										<i class="<?php echo esc_attr( ALPUS_ICON_PREFIX . '-icon-search' ); ?>"></i>
									</button>
								</form>
							</div>
						</div>
							<?php if ( ! $is_ajax ) { ?>
							<div class="block-categories">
								<?php
                foreach ( $args['front_categories'] as $front_category ) {
                    ?>
									<a href="#" class="block-category" data-category="<?php echo esc_attr( $front_category ); ?>">
										<h4><?php echo esc_html( $args['studio']->get_category_title( $front_category ) ); ?></h4>
										<img src="<?php echo ALPUS_CORE_URI; ?>/assets/images/add-on/studio/<?php echo esc_attr( $front_category ); ?>.jpg">
									</a>
									<?php
                }
							    ?>
							</div>
						<?php } ?>
						<div class="blocks-list column-3"></div>
						<div class="alpus-loading"></div>
					</div>
				</div>
			</div>
			<div class="blocks-section-pane" id="layout-section">
				<iframe src="<?php echo esc_url( admin_url( 'admin.php?page=alpus-layout-builder&is_elementor_preview=true&noheader=true' ) ); ?>"></iframe>
			</div>
		</div>
		<div class="alpus-loading"></div>
	</div>
</script>
