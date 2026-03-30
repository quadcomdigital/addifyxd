<?php
global $addf_gift_registry_count_add_t0_cart;
if ( ! $addf_gift_registry_count_add_t0_cart ) {
	$addf_gift_registry_count_add_t0_cart = '0';
}

$addf_gift_registry_product            = get_post_meta( $addf_gr_post_id_tobe_updated, 'addf_gift_registry_product', true );
$var_for_singlr_product_rec_all       = get_post_meta( $addf_gr_post_id_tobe_updated, 'addf_gr_product_quantity_recieved', true );
$var_for_singlr_product_all           = get_post_meta( $addf_gr_post_id_tobe_updated, 'addf_gr_product_quantity', true );
$addf_gr_variation_selection_all      = (array) get_post_meta( $addf_gr_post_id_tobe_updated, 'addf_gr_variation_selection_verify_vales', true );

?>

<div class="<?php echo 'existing-gift-registry-data-table_replace_data' . esc_attr( $addf_gr_post_id_tobe_updated ); ?> addf-gr-product-grid" role="region" aria-label="<?php echo esc_attr__( 'Gift registry products', 'addf_giftr' ); ?>">
	<?php if ( is_array( $addf_gift_registry_product ) ) : ?>
		<?php
		$has_products = false;
		foreach ( $addf_gift_registry_product as $key => $addf_g_r_single_product ) {
			if ( ! get_the_title( $addf_g_r_single_product ) || ' ' == $addf_g_r_single_product || ! wc_get_product( $addf_g_r_single_product ) ) {
				continue;
			}

			$has_products = true;
			$var_for_singlr_product_rec_single_product = array_key_exists( $key, (array) $var_for_singlr_product_rec_all ) ? $var_for_singlr_product_rec_all[ $key ] : 0;
			if ( ! array_key_exists( $key, (array) $var_for_singlr_product_all ) ) {
				$var_for_singlr_product_all[ $key ] = 0;
			}

			$desired_qty   = (int) $var_for_singlr_product_all[ $key ];
			$received_qty  = array_key_exists( $key, (array) $var_for_singlr_product_rec_all ) ? (int) $var_for_singlr_product_rec_all[ $key ] : 0;
			$remaining_qty = $desired_qty - $received_qty;
			if ( $remaining_qty < 0 ) {
				$remaining_qty = 0;
			}

			$addf_single_product_image = wp_get_attachment_image_src( get_post_thumbnail_id( $addf_g_r_single_product ), 'single-post-thumbnail' );
			if ( '' == $addf_single_product_image ) {
				$product_for_image = wc_get_product( $addf_g_r_single_product );
				if ( $product_for_image && ( 'variable' == $product_for_image->get_type() || 'variation' == $product_for_image->get_type() ) ) {
					$addf_single_product_image = wp_get_attachment_image_src( get_post_thumbnail_id( $product_for_image->get_parent_id() ), 'single-post-thumbnail' );
				}
			}
			$image_src = $addf_single_product_image && $addf_single_product_image[0] ? $addf_single_product_image[0] : wc_placeholder_img_src();

			$addf_gr_extend_link     = '';
			$product                 = wc_get_product( $addf_g_r_single_product );
			$addf_gr_all_attr_prod   = $product->get_attributes();
			$addf_gr_text_attr_1     = '';
			$addf_gr_text_attr_2     = '';
			$addf_gr_text_attr_check = true;

			foreach ( $addf_gr_all_attr_prod as $main_value ) {
				if ( '' != $main_value ) {
					$addf_gr_text_attr_check = false;
				}
			}

			if ( $addf_gr_text_attr_check ) {
				$addf_gr_text_attr_1 = 'attribute_';
				$addf_gr_text_attr_2 = '&';
			} else {
				$addf_gr_text_attr_0 = '';
				$addf_gr_text_attr_1 = '&attribute_';
				$addf_gr_text_attr_2 = '';
			}

			$addf_gr_inc = 0;
			if ( array_key_exists( $key, $addf_gr_variation_selection_all ) ) {
				foreach ( $addf_gr_variation_selection_all[ $key ] as $key_1 => $value ) {
					if ( $addf_gr_text_attr_check && ( 0 == $addf_gr_inc ) ) {
						$addf_gr_text_attr_0 = '?';
					} else {
						$addf_gr_text_attr_0 = '';
					}
					if ( $value ) {
						$addf_gr_extend_link .= $addf_gr_text_attr_0 . $addf_gr_text_attr_1 . $key_1 . '=' . $value . $addf_gr_text_attr_2;
					}
					++$addf_gr_inc;
				}
			}

			$_product = wc_get_product( $addf_g_r_single_product );
			$addf_gr_current_user                = wp_get_current_user();
			$adff_gr_session_greeting_message_id = 'addf_gr_greeting_message_' . $addf_g_r_single_product . '_' . $addf_gr_current_user->ID;
			$greeting_message                    = WC()->session->get( $adff_gr_session_greeting_message_id );
			?>

			<article class="addf-gr-card <?php echo 0 === $remaining_qty ? 'addf-gr-card--purchased' : ''; ?>">
				<div class="addf-gr-card__thumb-wrap">
					<img class="addf-gr-card__thumb" src="<?php echo esc_url( $image_src ); ?>" alt="<?php echo esc_attr( get_the_title( $addf_g_r_single_product ) ); ?>" data-id="<?php echo esc_attr( $addf_g_r_single_product ); ?>">
					<?php if ( 0 === $remaining_qty ) : ?>
						<span class="addf-gr-card__badge addf-gr-card__badge--purchased"><?php echo esc_html__( 'Purchased', 'addf_giftr' ); ?></span>
					<?php else : ?>
						<span class="addf-gr-card__badge addf-gr-card__badge--active"><?php echo esc_html__( 'Available', 'addf_giftr' ); ?></span>
					<?php endif; ?>
				</div>

				<div class="addf-gr-card__body">
					<h3 class="addf-gr-card__title">
						<a href="<?php echo esc_url( get_the_permalink( $addf_g_r_single_product ) . $addf_gr_extend_link ); ?>">
							<?php echo esc_html( get_the_title( $addf_g_r_single_product ) ); ?>
						</a>
					</h3>
					<div class="addf-gr-card__price"><?php echo wp_kses_post( $_product->get_price_html() ); ?></div>

					<?php if ( wc_get_product( $addf_g_r_single_product )->is_type( 'variation' ) ) : ?>
						<?php $addf_gr_product_all_attr = wc_get_product( $addf_g_r_single_product )->get_variation_attributes(); ?>
						<ul class="addf-gr-card__attrs">
							<?php foreach ( $addf_gr_product_all_attr as $key_of_main_attr => $value_of_main_attr ) : ?>
								<?php if ( '' !== $value_of_main_attr ) : ?>
									<?php
									$key_of_main_attr_text = str_replace( 'attribute_', '', $key_of_main_attr );
									$key_of_main_attr_text = str_replace( 'pa_', '', $key_of_main_attr_text );
									?>
									<li><strong><?php echo esc_html( ucfirst( $key_of_main_attr_text ) ); ?>:</strong> <?php echo esc_html( ucfirst( $value_of_main_attr ) ); ?></li>
								<?php endif; ?>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>

					<?php if ( array_key_exists( $key, $addf_gr_variation_selection_all ) ) : ?>
						<ul class="addf-gr-card__attrs">
							<?php foreach ( $addf_gr_variation_selection_all[ $key ] as $key_1 => $value ) : ?>
								<?php if ( '' !== $value ) : ?>
									<li><strong><?php echo esc_html( ucfirst( str_replace( 'pa_', '', $key_1 ) ) ); ?>:</strong> <?php echo esc_html( ucfirst( $value ) ); ?></li>
								<?php endif; ?>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>

					<div class="addf-gr-card__quantities">
						<span><strong><?php echo esc_html__( 'Desired', 'addf_giftr' ); ?>:</strong> <?php echo esc_html( $desired_qty ); ?></span>
						<span><strong><?php echo esc_html__( 'Received', 'addf_giftr' ); ?>:</strong> <?php echo esc_html( $received_qty ); ?></span>
						<span><strong><?php echo esc_html__( 'Remaining', 'addf_giftr' ); ?>:</strong> <?php echo esc_html( $remaining_qty ); ?></span>
					</div>

					<input type="hidden" name="addf_gr_post_id" value="<?php echo esc_attr( $addf_gr_post_id_tobe_updated ); ?>">
				</div>

				<?php if ( 0 < $remaining_qty ) : ?>
					<div class="addf-gr-card__actions">
						<div class="addf-gr-card__qty-wrap">
							<label class="addf-gr-card__label" for="addf-gr-qty-<?php echo esc_attr( $key ); ?>"><?php echo esc_html__( 'Qty', 'addf_giftr' ); ?></label>
							<input id="addf-gr-qty-<?php echo esc_attr( $key ); ?>" class="addf-gr-desire-product_public addf-gr-desire-product<?php echo esc_attr( $key ); ?>" data-product_id="<?php echo esc_attr( $key ); ?>" min="1" max="<?php echo esc_attr( $remaining_qty ); ?>" value="1" type="number" aria-label="<?php echo esc_attr__( 'Quantity to add to cart', 'addf_giftr' ); ?>">
						</div>

						<div class="addf-gr-card__message">
							<?php if ( $greeting_message ) : ?>
								<p class="addf-gr-card__message-text"><?php echo esc_html( $greeting_message ); ?></p>
								<div class='addf_gr_action_icon_container'>
									<i class="fa fa-pencil button addf_gr_edit_message_button addf_gr_edit_message<?php echo esc_attr( $key ); ?>" role="button" tabindex="0" aria-label="<?php echo esc_attr__( 'Edit greeting message', 'addf_giftr' ); ?>" data-product_id="<?php echo esc_attr( $addf_g_r_single_product ); ?>" data-gr_post_id="<?php echo esc_attr( $addf_gr_post_id_tobe_updated ); ?>" data-gr_greeting_message="<?php echo esc_attr( $greeting_message ); ?>"></i>
									<i class="fa fa-trash button addf_gr_delete_message_button addf_gr_delete_message<?php echo esc_attr( $key ); ?>" role="button" tabindex="0" aria-label="<?php echo esc_attr__( 'Delete greeting message', 'addf_giftr' ); ?>" data-product_id="<?php echo esc_attr( $addf_g_r_single_product ); ?>" data-gr_post_id="<?php echo esc_attr( $addf_gr_post_id_tobe_updated ); ?>"></i>
								</div>
							<?php else : ?>
								<button class="button addf_gr_add_message_button addf_gr_add_message<?php echo esc_attr( $key ); ?>" data-product_id="<?php echo esc_attr( $addf_g_r_single_product ); ?>" data-gr_post_id="<?php echo esc_attr( $addf_gr_post_id_tobe_updated ); ?>" aria-label="<?php echo esc_attr__( 'Add greeting message', 'addf_giftr' ); ?>"><?php echo esc_html__( 'Add Message', 'addf_giftr' ); ?></button>
							<?php endif; ?>
						</div>

						<div class="addf-gr-card__cta-row">
							<button class="button addf_gr_add_cart_btn ajax_add_to_cart_public_addf_g_r ajax_add_to_cart<?php echo esc_attr( $key ); ?>" data-product_id="<?php echo esc_attr( $key ); ?>" data-gr_post_id="<?php echo esc_attr( $addf_gr_post_id_tobe_updated ); ?>" aria-label="<?php echo esc_attr__( 'Add this product to cart', 'addf_giftr' ); ?>"><?php echo esc_html__( 'Add to Cart', 'addf_giftr' ); ?></button>
							<label class="addf-gr-card__bulk-select">
								<input type="checkbox" class="add_to_cart_from_addf_g_r_checkbox_bulk" data-product_ids="0-0" data-product_id="<?php echo esc_attr( $key ); ?>" name="" aria-label="<?php echo esc_attr__( 'Select product for bulk add to cart', 'addf_giftr' ); ?>">
								<span><?php echo esc_html__( 'Select for bulk add', 'addf_giftr' ); ?></span>
							</label>
						</div>
					</div>
				<?php else : ?>
					<div class="addf-gr-card__purchased-note"><?php echo esc_html__( 'Product purchased', 'addf_giftr' ); ?></div>
				<?php endif; ?>
			</article>
		<?php } ?>

		<?php if ( ! $has_products ) : ?>
			<div class="addf-align-center addf-gr-product-grid__empty"><?php echo esc_html__( 'No product to show', 'addf_giftr' ); ?></div>
		<?php endif; ?>
	<?php else : ?>
		<div class="addf-align-center addf-gr-product-grid__empty"><?php echo esc_html__( 'No product to show', 'addf_giftr' ); ?></div>
	<?php endif; ?>
</div>

