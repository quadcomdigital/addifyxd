
<table class="wp-list-table widefat fixed striped table-view-list addf_gr_be_table <?php echo 'existing-gift-registry-data-table_replace_data' . esc_attr( $addf_gr_post_id_tobe_updated ); ?> ">
	<thead>
		<tr>
			<th class="addf_gr_bk_img_td">
				<?php echo esc_html__( 'Product Image', 'addf_giftr' ); ?>
			</th>
			<th class="addf_gr_bk_name">
				<?php echo esc_html__( 'Product Name', 'addf_giftr' ); ?>
			</th>
			<th  class="addf_gr_bk_prc">
				<?php echo esc_html__( 'Product Price', 'addf_giftr' ); ?>
			</th>
			<th class="addf_gr_bk_d_qty">
				<?php echo esc_html__( 'Desired Quantity', 'addf_giftr' ); ?>
			</th>
			<th class="addf_gr_bk_rd_qty">
				<?php echo esc_html__( 'Recieved Quantity', 'addf_giftr' ); ?>
			</th>
			<th class="addf_gr_bk_rd_qty"></th>
		</tr>
	</thead>
	<tbody>
		<?php
		$addf_gift_registry_product = get_post_meta( $addf_gr_post_id_tobe_updated, 'addf_gift_registry_product', true );
		if ( is_array( $addf_gift_registry_product ) ) {

			foreach ( $addf_gift_registry_product as $key => $addf_g_r_single_product ) {

				$product = wc_get_product( $addf_g_r_single_product );
				if ( empty( $addf_g_r_single_product ) || ! $product ) {
					continue;
				}

				?>
				<tr>
					<td class="addf_gr_bk_img_td">
						<?php
						$addf_single_product_image = wp_get_attachment_image_src( get_post_thumbnail_id( $addf_g_r_single_product ), 'single-post-thumbnail' );
						if ( '' == $addf_single_product_image ) {
							if ( $product->is_type( 'variation' ) ) {
								$addf_single_product_image = wp_get_attachment_image_src( get_post_thumbnail_id( $product->get_parent_id() ), 'single-post-thumbnail' );
							}
						}
						$image_src = $addf_single_product_image && $addf_single_product_image[0] ? $addf_single_product_image[0] : wc_placeholder_img_src(); 
						?>
						  
						<img src="<?php echo esc_url( $image_src ); ?>" width="90" height="50"  data-id="<?php echo esc_attr( $addf_g_r_single_product ); ?>">
					</td>
					<td  class="addf_gr_bk_name">
						<?php
						$addf_gr_variation_selection_verify_vales = get_post_meta( $addf_gr_post_id_tobe_updated, 'addf_gr_variation_selection_verify_vales', true );
						$addf_gr_extend_link                      = '';
						$addf_gr_all_attr_prod                    = $product->get_attributes();
						$addf_gr_text_attr_1                      = '';
						$addf_gr_text_attr_2                      = '';
						$addf_gr_text_attr_check                  = true;
						foreach ( $addf_gr_all_attr_prod as $main_key => $main_value ) {
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
						if ( array_key_exists( $key, $addf_gr_variation_selection_verify_vales ) ) {
							foreach ( $addf_gr_variation_selection_verify_vales[ $key ] as $key_1 => $value ) {
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
						?>
						<a href="<?php echo esc_url( get_the_permalink( $addf_g_r_single_product ) . $addf_gr_extend_link ); ?>">
							<?php
							echo esc_html__( get_the_title( $addf_g_r_single_product ), 'addf_giftr' );
							?>
						</a>
						<?php
						if ( wc_get_product( $addf_g_r_single_product )->is_type( 'variation' ) ) {
							$addf_gr_product_all_attr = ( wc_get_product( $addf_g_r_single_product ) )->get_variation_attributes();
							foreach ( $addf_gr_product_all_attr as $key_of_main_attr => $value_of_main_attr ) {
								if ( '' != $value_of_main_attr ) {
									?>
									<br>
									<label for="<?php echo esc_attr( $key_of_main_attr ); ?>">
										<strong>
											<?php
											$key_of_main_attr = str_replace( 'attribute_', '', $key_of_main_attr );
											$key_of_main_attr = str_replace( 'pa_', '', $key_of_main_attr );
											echo esc_html__( ucfirst( $key_of_main_attr ) . ' : ', 'addf_giftr' );
											?>
										</strong>
										&nbsp;
									</label>
									<span><?php echo esc_html__( ucfirst( $value_of_main_attr ), 'addf_giftr' ); ?></span>
									<?php
								}
							}
						}
						if ( array_key_exists( $key, $addf_gr_variation_selection_verify_vales ) ) {
							foreach ( $addf_gr_variation_selection_verify_vales[ $key ] as $key_1 => $value ) {
								if ( '' == $value ) {
									continue;
								}
								?>
								<br>
								<label for="<?php echo esc_attr( $key_1 ); ?>">
									<strong>
										<?php echo esc_html__( ucfirst( str_replace( 'pa_', '', $key_1 ) ) . ' : ', 'addf_giftr' ); ?>
									</strong>
									&nbsp;
								</label>
								<span><?php echo esc_html__( ucfirst( $value ), 'addf_giftr' ); ?></span>
								<?php
							}
						}
						?>
						<input type="hidden" name="addf_gr_post_id" value="<?php echo esc_attr( $addf_gr_post_id_tobe_updated ); ?>"  >
					</td>
					<td  class="addf_gr_bk_prc">
						<?php
						$_product = wc_get_product( $addf_g_r_single_product );
						echo wp_kses_post( $_product->get_price_html() );
						?>
					</td>
					<td class="addf_gr_bk_d_qty">
						<!-- desired product -->
						<?php
						$var_for_singlr_product_rec = get_post_meta( $addf_gr_post_id_tobe_updated, 'addf_gr_product_quantity_recieved', true );
						$var_for_singlr_product     = get_post_meta( $addf_gr_post_id_tobe_updated, 'addf_gr_product_quantity', true );
						if ( array_key_exists( $key, (array) $var_for_singlr_product_rec ) ) {
							$addf_gr_min_desired_qty = $var_for_singlr_product_rec[ $key ];
						} else {
							$addf_gr_min_desired_qty = 0;
						}
						?>

						<input class="addf-gr-desire-product" min="<?php echo esc_attr( $addf_gr_min_desired_qty ); ?>" max="" value="<?php echo esc_attr( $var_for_singlr_product[ $key ] ); ?>" type="number" name="addf_gr_product_quantity[<?php echo esc_attr( $key ); ?>]" >
					</td>
					<td  class="addf_gr_bk_rd_qty">
						<!-- Recieved Products -->
						<?php

						if ( ! array_key_exists( $key, (array) $var_for_singlr_product_rec ) ) {
							$var_for_singlr_product_rec[ $key ] = 0;
						}
						echo esc_attr( $var_for_singlr_product_rec[ $key ] );
						?>
					</td>
					<td class="addf_gr_bk_rd_qty del_addf_single_product">
						<input type="hidden" readonly name="" class="addf-delete-product-from-registry_post_id_val" value="<?php echo esc_attr( $addf_gr_post_id_tobe_updated ); ?>" >
						<span class=" addf-delete-product-from-registry"  data-id="<?php echo esc_attr( $key ) . ':' . esc_attr( $addf_gr_post_id_tobe_updated ); ?>"><span class="fa fa-trash"></span> </span>
					</td>
				</tr>
				<?php
			}
		}
		?>
	</tbody>
</table>
<?php
