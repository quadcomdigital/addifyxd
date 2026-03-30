<?php
		$addf_g_r_owner_fname     = get_user_meta( $addf_gift_registry_meta_user, 'first_name', true );
		$addf_g_r_owner_lname     = get_user_meta( $addf_gift_registry_meta_user, 'last_name', true );
		$addf_g_r_owner_address_1 = get_user_meta( $addf_gift_registry_meta_user, 'billing_address_1', true );
		$addf_g_r_owner_address_2 = get_user_meta( $addf_gift_registry_meta_user, 'billing_address_2', true );
		$addf_g_r_owner_city      = get_user_meta( $addf_gift_registry_meta_user, 'billing_city', true );
		$addf_g_r_owner_postcode  = get_user_meta( $addf_gift_registry_meta_user, 'billing_postcode', true );



?>
			
			<div class="addf_gr_restrict_cart_have_items"  ></div>
		<div class="existing-gift-registry-data">
			<input type="hidden" id="addf_gift_registry_pb_side_for_public_users" value="1">
			<input type="hidden" id="addf_gift_registry_pb_first_name" value="<?php echo esc_attr( $addf_g_r_owner_fname ); ?>">
			<input type="hidden" id="addf_gift_registry_pb_last_name" value="<?php echo esc_attr( $addf_g_r_owner_lname ); ?>">
			<input type="hidden" id="addf_gift_registry_pb_address_1" value="<?php echo esc_attr( $addf_g_r_owner_address_1 ); ?>">
			<input type="hidden" id="addf_gift_registry_pb_address_2" value="<?php echo esc_attr( $addf_g_r_owner_address_2 ); ?>">
			<input type="hidden" id="addf_gift_registry_pb_city" value="<?php echo esc_attr( $addf_g_r_owner_city ); ?>">
			<input type="hidden" id="addf_gift_registry_pb_post_code" value="<?php echo esc_attr( $addf_g_r_owner_postcode ); ?>">
				<table  class="existing-gift-registry-data-table">
					<tr>
						<td class="addf-align-center" colspan="2">
								<?php echo esc_html__( the_title(), 'addf_giftr' ); ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo esc_html__( ' Registrant Name', 'addf_giftr' ); ?>
						</td>
						<td>
							<?php echo esc_html__( get_post_meta( get_the_ID(), 'gift-registry-registrant-first-name', true ), 'addf_giftr' ) . '&nbsp;' . esc_html__( get_post_meta( get_the_ID(), 'gift-registry-registrant-last-name', true ), 'addf_giftr' ); ?>
							<br>
						</td>
					</tr>
					<tr>
						<td>
						<?php echo esc_html__( 'Event Date', 'addf_giftr' ); ?>
						</td>
						<td>
							<?php echo esc_html__( get_post_meta( get_the_ID(), 'gift-registry-event-info-date', true ), 'addf_giftr' ); ?>
						</td>
					</tr>
					<tr>
						<td>
						<?php echo esc_html__( 'Event Location', 'addf_giftr' ); ?>
						</td>
						<td>
							<?php echo esc_html__( get_post_meta( get_the_ID(), 'gift-registry-event-info-location', true ), 'addf_giftr' ); ?>
						</td>
					</tr>
					<tr>
						<td>
						<?php echo esc_html__( 'Event Detail', 'addf_giftr' ); ?>
						</td>
						<td>
							<?php echo esc_html__( get_post_meta( get_the_ID(), 'gift-registry-event-info-message', true ), 'addf_giftr' ); ?>
						</td>
					</tr>
					<tr>
							<td colspan="2">
								<span class="addf-deetion-ajax-error"></span>
							</td>
						</tr>	
				</table>
				<div class="existing-gift-registry-data-table">
										   
					<div class="addf_gr_product_scrollable_div">
					<table  class="<?php echo esc_attr( 'existing-gift-registry-data-table_replace_data' . get_the_ID() ); ?>">
						<tr>
							<th class="addf_gr_img_class">
							<?php echo esc_html__( 'Product Image', 'addf_giftr' ); ?>
							</th>
							<th class="addf_gr_name_class">
							<?php echo esc_html__( 'Product Name', 'addf_giftr' ); ?>
							</th>
							<th class="addf_gr_qty_class">
							<?php echo esc_html__( 'Desired Quantity', 'addf_giftr' ); ?>
							</th>
							<th class="addf_gr_qty_class">
							<?php echo esc_html__( 'Received Quantity', 'addf_giftr' ); ?>
							</th>
							<th class="addf_gr_sqty_class">
							<?php echo esc_html__( 'No of Qty', 'addf_giftr' ); ?>
							</th>
							<th class="addf_gr_message_class">
							<?php echo esc_html__( 'Greeting Message', 'addf_giftr' ); ?>
							</th>
							<th  class="addf_gr_cart_class">
							</th>
							<th class="addf_gr_cb_class">
							</th>
						</tr>
						<?php
						global $addf_gift_registry_count_add_t0_cart;
						if ( ! $addf_gift_registry_count_add_t0_cart ) {
							$addf_gift_registry_count_add_t0_cart = '0';
						}
						$addf_gift_registry_product = get_post_meta( get_the_ID(), 'addf_gift_registry_product', true );
						if ( is_array( $addf_gift_registry_product ) ) {
							foreach ( $addf_gift_registry_product as $key => $addf_g_r_single_product ) {
								if ( ! get_the_title( $addf_g_r_single_product ) ) {
									continue;
								}
								if ( ' ' == $addf_g_r_single_product ) {
									continue;
								}
								if ( ! wc_get_product( $addf_g_r_single_product ) ) {
									continue;
								}
								$var_for_singlr_product_rec = get_post_meta( get_the_ID(), 'addf_gr_product_quantity_recieved', true );
								$var_for_singlr_product     = get_post_meta( get_the_ID(), 'addf_gr_product_quantity', true );
								if ( ! array_key_exists( $key, (array) $var_for_singlr_product_rec ) ) {
									$var_for_singlr_product_rec_single_product = 0;
								} else {
									$var_for_singlr_product_rec_single_product = $var_for_singlr_product_rec[ $key ];
								}
								if ( ! array_key_exists( $key, (array) $var_for_singlr_product ) ) {
									$var_for_singlr_product[ $key ] = 0;
								}
								?>
							<tr>
								<td class="addf_gr_img_class">
									<?php
									$addf_single_product_image = wp_get_attachment_image_src( get_post_thumbnail_id( $addf_g_r_single_product ), 'single-post-thumbnail' );
									if ( '' == $addf_single_product_image ) {
										$product = wc_get_product( $addf_g_r_single_product );
										if ( ( 'variable' == $product->get_type() ) || ( 'variation' == $product->get_type() ) ) {
											$addf_single_product_image = wp_get_attachment_image_src( get_post_thumbnail_id( $product->get_parent_id() ), 'single-post-thumbnail' );
										}
									}
									$image_src = $addf_single_product_image && $addf_single_product_image[0] ? $addf_single_product_image[0] : wc_placeholder_img_src();   
									?>
										<img src="<?php echo esc_url( $image_src ); ?>" width="90" height="50"  data-id="<?php echo esc_attr( $addf_g_r_single_product ); ?>">
									
								</td>
								<td class="addf_gr_name_class">
								<?php
										$addf_gr_variation_selection_verify_vales = (array) get_post_meta( get_the_ID(), 'addf_gr_variation_selection_verify_vales', true );
										$addf_gr_extend_link                      = '';
										$product                                  = wc_get_product( $addf_g_r_single_product );
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
									<?php $_product = wc_get_product( $addf_g_r_single_product ); ?>
									<a href="<?php echo esc_url( get_the_permalink( $addf_g_r_single_product ) . $addf_gr_extend_link ); ?>">
									<?php
										echo esc_html__( get_the_title( $addf_g_r_single_product ), 'addf_giftr' );
									?>
									</a>
									<br>
									<span>
										<?php echo wp_kses_post( $_product->get_price_html() ); ?>
									</span>
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
									<input type="hidden" name="addf_gr_post_id" value="<?php echo esc_attr( get_the_ID() ); ?>">
								</td>
									<!-- desired product -->
									<td class="addf_gr_qty_class">
									<?php
										$var_for_singlr_product_rec = get_post_meta( get_the_ID(), 'addf_gr_product_quantity_recieved', true );
									if ( ! array_key_exists( $key, (array) $var_for_singlr_product_rec ) ) {
										$var_for_singlr_product_rec[ $key ] = 0;
									}
										echo esc_attr( $var_for_singlr_product[ $key ] );
									?>
									</td>
										<?php
										if ( 0 < ( $var_for_singlr_product[ $key ] - $var_for_singlr_product_rec_single_product ) ) {
											?>
									<td class="addf_gr_qty_class">
											<?php
											if ( 0 < ( $var_for_singlr_product[ $key ] - $var_for_singlr_product_rec_single_product ) ) {
												$var_for_singlr_product_rec_single_product = ( $var_for_singlr_product[ $key ] - $var_for_singlr_product_rec_single_product );
											}
											echo esc_attr( $var_for_singlr_product_rec[ $key ] );
											$addf_gr_user_max_qty = ( $var_for_singlr_product[ $key ] - $var_for_singlr_product_rec[ $key ] );
											if ( $addf_gr_user_max_qty < 0 ) {
												$addf_gr_user_max_qty = 0;
											}
											?>
									</td>
										<td class="addf_gr_sqty_class">
											<!-- select qualtity public side -->
											<input class="addf-gr-desire-product_public addf-gr-desire-product<?php echo esc_attr( $key ); ?>" data-product_id="<?php echo esc_attr( $key ); ?>" min="1" max="<?php echo esc_attr( $addf_gr_user_max_qty ); ?>"  value="1" type="number"   >
										</td>
										<?php
										$addf_gr_current_user                    = wp_get_current_user();
											$adff_gr_session_greeting_message_id = 'addf_gr_greeting_message_' . $addf_g_r_single_product . '_' . $addf_gr_current_user->ID;                                   
											if (WC()->session->get( $adff_gr_session_greeting_message_id )) {
												?>
											<td class="addf_gr_message_class">
												<?php
												echo esc_attr(WC()->session->get( $adff_gr_session_greeting_message_id ));
												?>
											<div class='addf_gr_action_icon_container'>
												<i class="fa fa-pencil button addf_gr_edit_message_button addf_gr_edit_message<?php echo esc_attr( $key ); ?>"  data-product_id="<?php echo esc_attr( $addf_g_r_single_product ); ?>" data-gr_post_id="<?php echo esc_attr( get_the_ID() ); ?>" data-gr_greeting_message="<?php echo esc_attr(WC()->session->get( $adff_gr_session_greeting_message_id )); ?>"></i>
												<i class="fa fa-trash button addf_gr_delete_message_button addf_gr_delete_message<?php echo esc_attr( $key ); ?>"  data-product_id="<?php echo esc_attr( $addf_g_r_single_product ); ?>" data-gr_post_id="<?php echo esc_attr( get_the_ID() ); ?>"></i>
											</div>
											</td>
										
											<?php
											} else {
												?>
											<td class="addf_gr_message_class">
											<button  class=" button addf_gr_add_message_button addf_gr_add_message<?php echo esc_attr( $key ); ?>"  data-product_id="<?php echo esc_attr( $addf_g_r_single_product ); ?>" data-gr_post_id="<?php echo esc_attr( get_the_ID() ); ?>"  ><?php echo esc_html__( 'Add Message', 'addf_giftr' ); ?></button>
											</td>
											<?php
										
											}


											?>
										
									
										<td class="addf_gr_cart_class">
											<button  class=" button addf_gr_add_cart_btn ajax_add_to_cart_public_addf_g_r ajax_add_to_cart<?php echo esc_attr( $key ); ?>"  data-product_id="<?php echo esc_attr( $key ); ?>" data-gr_post_id="<?php echo esc_attr( get_the_ID() ); ?>"  ><?php echo esc_html__( 'Add to Cart', 'addf_giftr' ); ?></button>
										</td>
										<td class="addf_gr_cb_class">
											<input type="checkbox" class="add_to_cart_from_addf_g_r_checkbox_bulk" data-product_ids="0-0" data-product_id="<?php echo esc_attr( $key ); ?>" name="" >
										</td>
											<?php
										} else {
											?>
										<td colspan="4" class="addf_gr_prod_purchased">
											<?php echo esc_html__( 'Product purchased', 'addf_giftr' ); ?>
										</td>
											<?php
										}
										?>
							</tr>
							
									<?php
							}
						} else {
							?>
							<tr>
								<td colspan="7" class="addf-align-center">
									<?php echo esc_html__( 'No product to show', 'addf_giftr' ); ?>
								</td>
							</tr>
							<?php
						}
						?>
						</table>
						</div>
						<table>
							<tr>
								<td colspan="7" class="align-right">
									<input type="hidden" value="0" class="product_type_simple_add_to_cart_bulk_button_all_ids_add_to_cart">
									<input type="hidden" value="0" class="product_type_simple_add_to_cart_bulk_button_all_quantities_add_to_cart">

									<button data-gr_post_id="<?php echo esc_attr( get_the_ID() ); ?>" class=" button product_type_simple_add_to_cart_bulk_button" ><?php echo esc_html__( 'Bulk Add to Cart', 'addf_giftr' ); ?></button>
								</td>
							</tr>
					</table>
					
				</div>
			</div>
		<?php
