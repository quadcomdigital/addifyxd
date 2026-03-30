<?php
global $addf_gr_email_confirmation;
echo esc_html__( $addf_gr_email_confirmation, 'addf_giftr' );

wp_nonce_field( 'addify_gift_registry_nonce', 'addify_gift_registry_nonce' );
$nonce = isset( $_POST['addify_gift_registry_nonce'] ) ? sanitize_text_field( $_POST['addify_gift_registry_nonce'] ) : '';

$addf_gr_enable_edit_delete_expired_registry = get_option('wc_settings_tab_gift_registry_notify_gift_registry_enable_edit_delete_for_expired_registry', true);

if ( isset( $_POST['submit-edit-registry'] ) && ! wp_verify_nonce( $nonce, 'addify_gift_registry_nonce' ) ) {

	wp_die( esc_html__( 'Security Violated!', 'addf_giftr' ) );

}
?>
<div class="addf_gr_registry_delete_success_error"></div>



<div class="existing-gift-registry-data" >
	<?php
	if ( isset( $_POST['submit-edit-registry'] ) ) {



		if ( isset( $_POST['gift-registry-title'] ) ) {
			$post_data = array(
				'ID'         => get_the_ID(),
				'post_title' => sanitize_text_field( $_POST['gift-registry-title'] ),
			);
			wp_update_post( $post_data );
		}
		?>
		<p class="woocommerce-info"><?php echo esc_html__( ' Registry Updated', 'addf_giftr' ); ?></p>
		<?php
		if ( isset( $_POST['gift-registry-registrant-first-name'] ) ) {
			$addf_insert_first_name = sanitize_text_field( $_POST['gift-registry-registrant-first-name'] );
			update_post_meta( get_the_ID(), 'gift-registry-registrant-first-name', $addf_insert_first_name );
		}
		if ( isset( $_POST['gift-registry-registrant-last-name'] ) ) {
			$addf_insert_last_name = sanitize_text_field( $_POST['gift-registry-registrant-last-name'] );
			update_post_meta( get_the_ID(), 'gift-registry-registrant-last-name', $addf_insert_last_name );
		}
		if ( isset( $_POST['addf-gift-registry-visibility'] ) ) {
			$addf_gift_registry_visibility_pri = sanitize_text_field( $_POST['addf-gift-registry-visibility'] );
			update_post_meta( get_the_ID(), 'addf-gift-registry-visibility', $addf_gift_registry_visibility_pri );
		}
		if ( isset( $_POST['addf-gift-registry-visibility-private-pass'] ) ) {
			$addf_gift_registry_visibility_pri_pass = sanitize_text_field( $_POST['addf-gift-registry-visibility-private-pass'] );
			update_post_meta( get_the_ID(), 'addf-gift-registry-visibility-private-pass', $addf_gift_registry_visibility_pri_pass );
		}
		if ( isset( $_POST['gift-registry-registrant-email'] ) ) {
			$addf_insert_email = sanitize_text_field( $_POST['gift-registry-registrant-email'] );
			update_post_meta( get_the_ID(), 'gift-registry-registrant-email', $addf_insert_email );
				// Insert co registrant information
		}
		if ( isset( $_POST['gift-registry-co-registrant-first'] ) ) {
			$addf_insert_first_name_co = sanitize_text_field( $_POST['gift-registry-co-registrant-first'] );
			update_post_meta( get_the_ID(), 'gift-registry-co-registrant-first', $addf_insert_first_name_co );
		}
		if ( isset( $_POST['gift-registry-co-registrant-last-name'] ) ) {
			$addf_insert_last_name_co = sanitize_text_field( $_POST['gift-registry-co-registrant-last-name'] );
			update_post_meta( get_the_ID(), 'gift-registry-co-registrant-last-name', $addf_insert_last_name_co );
		}
		if ( isset( $_POST['gift-registry-co-registrant-email'] ) ) {
			$addf_insert_email_co = sanitize_text_field( $_POST['gift-registry-co-registrant-email'] );
			update_post_meta( get_the_ID(), 'gift-registry-co-registrant-email', $addf_insert_email_co );
				// Insert evant information
		}
		if ( isset( $_POST['gift-registry-event-info-date'] ) ) {
			$gift_registry_info_date = sanitize_text_field( $_POST['gift-registry-event-info-date'] );
			$addf_gr_today           = new DateTime(); // Current date/time
			$addf_gr_check_date      = new DateTime($gift_registry_info_date);
			if ($addf_gr_check_date > $addf_gr_today) {
				update_post_meta( get_the_ID(), 'gift-registry-event-info-expire-date', 'active' );
			} else {
				update_post_meta( get_the_ID(), 'gift-registry-event-info-expire-date', 'expired' );
			}
			update_post_meta( get_the_ID(), 'gift-registry-event-info-date', $gift_registry_info_date );
		}
		if ( isset( $_POST['gift-registry-event-info-location'] ) ) {
			$gift_registry_event_location = sanitize_text_field( $_POST['gift-registry-event-info-location'] );
			update_post_meta( get_the_ID(), 'gift-registry-event-info-location', $gift_registry_event_location );
		}
		if ( isset( $_POST['gift-registry-event-info-message'] ) ) {
			$gift_registry_event_message = sanitize_text_field( $_POST['gift-registry-event-info-message'] );
			update_post_meta( get_the_ID(), 'gift-registry-event-info-message', $gift_registry_event_message );
		}
			// shipping address
		if ( isset( $_POST['gift_registry_guest_ship_f_name'] ) ) {
			update_post_meta( get_the_ID(), 'gift_registry_guest_ship_f_name', sanitize_meta( '', wp_unslash( $_POST['gift_registry_guest_ship_f_name'] ), 'post' ) );
		}
		if ( isset( $_POST['gift_registry_guest_ship_l_name'] ) ) {
			update_post_meta( get_the_ID(), 'gift_registry_guest_ship_l_name', sanitize_meta( '', wp_unslash( $_POST['gift_registry_guest_ship_l_name'] ), 'post' ) );
		}
		if ( isset( $_POST['gift_registry_guest_ship_comp_name'] ) ) {
			update_post_meta( get_the_ID(), 'gift_registry_guest_ship_comp_name', sanitize_meta( '', wp_unslash( $_POST['gift_registry_guest_ship_comp_name'] ), 'post' ) );
		}
		if ( isset( $_POST['gift_registry_guest_shipping_address'] ) ) {
			update_post_meta( get_the_ID(), 'gift_registry_guest_shipping_address', sanitize_meta( '', wp_unslash( $_POST['gift_registry_guest_shipping_address'] ), 'post' ) );
		}
		if ( isset( $_POST['gift_registry_guest_shipping_city'] ) ) {
			update_post_meta( get_the_ID(), 'gift_registry_guest_shipping_city', sanitize_meta( '', wp_unslash( $_POST['gift_registry_guest_shipping_city'] ), 'post' ) );
		}
		if ( isset( $_POST['gift_registry_guest_shipping_post_code'] ) ) {
			update_post_meta( get_the_ID(), 'gift_registry_guest_shipping_post_code', sanitize_meta( '', wp_unslash( $_POST['gift_registry_guest_shipping_post_code'] ), 'post' ) );
		}
		if ( isset( $_POST['gift_registry_guest_shipping_country'] ) ) {
			update_post_meta( get_the_ID(), 'gift_registry_guest_shipping_country', sanitize_meta( '', wp_unslash( $_POST['gift_registry_guest_shipping_country'] ), 'post' ) );
		}
		if ( isset( $_POST['gift_registry_guest_shipping_state'] ) ) {
			update_post_meta( get_the_ID(), 'gift_registry_guest_shipping_state', sanitize_meta( '', wp_unslash( $_POST['gift_registry_guest_shipping_state'] ), 'post' ) );
		}
	}
	// if ( 'expired' != $addf_gr_active_visibility ) {
	// code commented after new update[feature: allow edit and delete for expiry registries]
	?>
	
		<form action="" class="addf_gr_edit_registry_new" method="post" style="display:none;">
				<?php wp_nonce_field( 'addify_gift_registry_nonce', 'addify_gift_registry_nonce' ); ?>
			<table  class="existing-gift-registry-data-table ">
				<tr>
					<td class="addf-align-center" colspan="3">
						<h2> <?php echo esc_html__( 'Edit Gift Registry', 'addf_giftr' ); ?></h2>
					</td>
				</tr>
				<tr>
					<td>
						<h3><?php echo esc_html__( 'Title', 'addf_giftr' ); ?><span class="red">&nbsp;*</span></h3>
					</td>
					<td colspan="2">
						<input type="text" class="title-gift-registry-input" required name="gift-registry-title" value="<?php echo esc_html__( the_title(), 'addf_giftr' ); ?>">
					</td>
				</tr>
				<tr>
					<td>
						<h3><?php echo esc_html__( 'Visibility', 'addf_giftr' ); ?><span class="red">&nbsp;*</span></h3>
					</td>
					<td colspan="2">
						<?php
						$addf_gr_visibility_pri = get_post_meta( get_the_ID(), 'addf-gift-registry-visibility', true );
						?>
						<input type="radio" name="addf-gift-registry-visibility" value="1" <?php checked( $addf_gr_visibility_pri, '1' ); ?> class="addf-gift-registry-visibility-pub">
						<label for="addf-gift-registry-visibility-pub"><?php echo esc_html__( ' Public', 'addf_giftr' ); ?></label>
						<input type="radio" name="addf-gift-registry-visibility" value="2" <?php checked( $addf_gr_visibility_pri, '2' ); ?> class="addf-gift-registry-visibility-pri">
						<label for="addf-gift-registry-visibility-pri"><?php echo esc_html__( ' Password Protected', 'addf_giftr' ); ?></label>
					</td>
				</tr>
				<tr class="addf-gift-registry-visibility-private-tr">
					<td>
						<h3><?php echo esc_html__( 'Visibility', 'addf_giftr' ); ?></h3>
					</td>
					<td>
						<input type="text" value="<?php echo esc_html__( get_post_meta( get_the_ID(), 'addf-gift-registry-visibility-private-pass', true ), 'addf_giftr' ); ?>" class="title-gift-registry-input addf-gift-registry-visibility-private-pass" name="addf-gift-registry-visibility-private-pass" placeholder="<?php echo esc_html__( 'Enter Password', 'addf_giftr' ); ?>">
					</td>
				</tr>
				<tr>
					<td>
						<h3><?php echo esc_html__( 'Registrant First Name', 'addf_giftr' ); ?><span class="red">&nbsp;*</span></h3>
					</td>
					<td colspan="2">
						<input type="text" class="title-gift-registry-input" required name="gift-registry-registrant-first-name" value="<?php echo esc_html__( get_post_meta( get_the_ID(), 'gift-registry-registrant-first-name', true ), 'addf_giftr' ); ?>">
					</td>
				</tr>
				<tr>
					<td>
						<h3><?php echo esc_html__( 'Registrant Last Name', 'addf_giftr' ); ?></h3>
					</td>
					<td colspan="2">
						<input type="text" class="title-gift-registry-input" name="gift-registry-registrant-last-name" value="<?php echo esc_html__( get_post_meta( get_the_ID(), 'gift-registry-registrant-last-name', true ), 'addf_giftr' ); ?>">
					</td>
				</tr>
				<tr>
					<td>
						<h3><?php echo esc_html__( 'Registrant Email', 'addf_giftr' ); ?><span class="red">&nbsp;*</span></h3>
					</td>
					<td colspan="2">
						<input type="email" class="title-gift-registry-input" required name="gift-registry-registrant-email" value="<?php echo esc_html__( get_post_meta( get_the_ID(), 'gift-registry-registrant-email', true ), 'addf_giftr' ); ?>">
					</td>
				</tr>
				<tr>
					<td>
						<h3><?php echo esc_html__( 'Co Registrant First Name', 'addf_giftr' ); ?></h3>
					</td>
					<td colspan="2">
						<input type="text" class="title-gift-registry-input" name="gift-registry-co-registrant-first" value="<?php echo esc_html__( get_post_meta( get_the_ID(), 'gift-registry-co-registrant-first', true ), 'addf_giftr' ); ?>">
					</td>
				</tr>
				<tr>
					<td>
						<h3><?php echo esc_html__( 'Co Registrant Last Name', 'addf_giftr' ); ?></h3>
					</td>
					<td colspan="2">
						<input type="text" class="title-gift-registry-input" name="gift-registry-co-registrant-last-name" value="<?php echo esc_html__( get_post_meta( get_the_ID(), 'gift-registry-co-registrant-last-name', true ), 'addf_giftr' ); ?>">
					</td>
				</tr>
				<tr>
					<td>
						<h3><?php echo esc_html__( 'Co Registrant Email', 'addf_giftr' ); ?></h3>
					</td>
					<td colspan="2">
						<input type="email" class="title-gift-registry-input" name="gift-registry-co-registrant-email" value="<?php echo esc_html__( get_post_meta( get_the_ID(), 'gift-registry-co-registrant-email', true ), 'addf_giftr' ); ?>">
					</td>
				</tr>
				<tr>
					<td>
						<h3><?php echo esc_html__( 'Event Date', 'addf_giftr' ); ?><span class="red">&nbsp;*</span></h3>
					</td>
					<td colspan="2">
						<input type="date" required class="addf-gift-registry-event-date" name="gift-registry-event-info-date" value="<?php echo esc_html__( get_post_meta( get_the_ID(), 'gift-registry-event-info-date', true ), 'addf_giftr' ); ?>">
					</td>
				</tr>
				<tr>
					<td>
						<h3><?php echo esc_html__( 'Event Location', 'addf_giftr' ); ?></h3>
					</td>
					<td colspan="2">
						<input type="text" class="title-gift-registry-input" name="gift-registry-event-info-location" value="<?php echo esc_html__( get_post_meta( get_the_ID(), 'gift-registry-event-info-location', true ), 'addf_giftr' ); ?>">
					</td>
				</tr>
				<tr>
					<td>
						<h3><?php echo esc_html__( 'Event Detail', 'addf_giftr' ); ?></h3>
					</td>
					<td colspan="2">
						<input type="text" class="title-gift-registry-input" name="gift-registry-event-info-message" value="<?php echo esc_html__( get_post_meta( get_the_ID(), 'gift-registry-event-info-message', true ), 'addf_giftr' ); ?>">
					</td>
				</tr>
				<?php
				// if ( ! is_user_logged_in() ) {
					$shipping_first_name = get_post_meta( get_the_ID(), 'gift_registry_guest_ship_f_name', true );
					$shipping_last_name  = get_post_meta( get_the_ID(), 'gift_registry_guest_ship_l_name', true );
					$shipping_company    = get_post_meta( get_the_ID(), 'gift_registry_guest_ship_comp_name', true );
					$shipping_address_1  = get_post_meta( get_the_ID(), 'gift_registry_guest_shipping_address', true );
					$shipping_city       = get_post_meta( get_the_ID(), 'gift_registry_guest_shipping_city', true );
					$shipping_postcode   = get_post_meta( get_the_ID(), 'gift_registry_guest_shipping_post_code', true );
					$shipping_country    = get_post_meta( get_the_ID(), 'gift_registry_guest_shipping_country', true );
					$shipping_state      = get_post_meta( get_the_ID(), 'gift_registry_guest_shipping_state', true );
				?>
					<tr >
						<td colspan="3" class="addf-align-center">
							<h3><?php echo esc_html__( 'Shipping Address', 'addf_giftr' ); ?></h3>
						</td>
					</tr>
					<tr >
						<td>
							<h3><?php echo esc_html__( 'First Name', 'addf_giftr' ); ?><span class="red">&nbsp;*</span></h3>
						</td>
						<td colspan="2">
							<input type="text" required name="gift_registry_guest_ship_f_name" value="<?php echo esc_html__( $shipping_first_name, 'addf_giftr' ); ?>" class="title-gift-registry-input">
						</td>
					</tr>
					<tr >
						<td>
							<h3><?php echo esc_html__( 'Last Name', 'addf_giftr' ); ?><span class="red">&nbsp;*</span></h3>
						</td>
						<td colspan="2">
							<input type="text" required name="gift_registry_guest_ship_l_name" value="<?php echo esc_html__( $shipping_last_name, 'addf_giftr' ); ?>" class="title-gift-registry-input">
						</td>
					</tr>
					<tr >
						<td>
							<h3><?php echo esc_html__( 'Company', 'addf_giftr' ); ?></h3>
						</td>
						<td colspan="2">
							<input type="text"  name="gift_registry_guest_ship_comp_name" value="<?php echo esc_html__( $shipping_company, 'addf_giftr' ); ?>" class="title-gift-registry-input">
						</td>
					</tr>
					<tr >
						<td>
							<h3><?php echo esc_html__( 'Address', 'addf_giftr' ); ?><span class="red">&nbsp;*</span></h3>
						</td>
						<td colspan="2">
							<input type="text" required name="gift_registry_guest_shipping_address" value="<?php echo esc_html__( $shipping_address_1, 'addf_giftr' ); ?>" class="title-gift-registry-input">
						</td>
					</tr>
					<tr >
						<td>
							<h3><?php echo esc_html__( 'City', 'addf_giftr' ); ?><span class="red">&nbsp;*</span></h3>
						</td>
						<td colspan="2">
							<input type="text" required name="gift_registry_guest_shipping_city" value="<?php echo esc_html__( $shipping_city, 'addf_giftr' ); ?>" class="title-gift-registry-input">
						</td>
					</tr>
					<tr >
						<td>
							<h3><?php echo esc_html__( 'Postcode', 'addf_giftr' ); ?><span class="red">&nbsp;*</span></h3>
						</td>
						<td colspan="2">
							<input type="text" required name="gift_registry_guest_shipping_post_code" value="<?php echo esc_html__( $shipping_postcode, 'addf_giftr' ); ?>" class="title-gift-registry-input">
						</td>
					</tr>
					<tr >
						<td>
							<h3><?php echo esc_html__( 'Country', 'addf_giftr' ); ?><span class="red">&nbsp;*</span></h3>
						</td>
						<td colspan="2">
							<select required name="gift_registry_guest_shipping_country" required class="addf_gr_countries_field_height addf_gr_countries_billing_front title-gift-registry-input" >
								<option value="" selected hidden ><?php echo esc_html__( 'Select your country', 'addf_giftr' ); ?></option>
								<?php
								$addf_gr_country_obj = new WC_Countries();
								$addf_gr_countries   = $addf_gr_country_obj->__get( 'countries' );
								foreach ( $addf_gr_countries as $key => $country_name ) {
									?>
									<option value="<?php echo esc_html( $key ); ?>" <?php selected( $shipping_country, $key, true ); ?>>
										<?php echo esc_html__( $country_name, 'addf_giftr' ); ?>
									</option>
									<?php
								}
								?>
							</select>
							<input type=hidden class="addf_state_value" value="<?php echo esc_attr($shipping_state); ?>"/>
						</td>
					</tr>
					<tr>
						<td>
							<h3><?php echo esc_html__( 'State', 'addf_giftr' ); ?><span class="red">&nbsp;*</span></h3>
						</td>
						<td colspan="2">
							<div class="addf_gr_guest_state">
								<?php
								if ( '' == $shipping_country ) {
									?>
									<select required name="gift_registry_guest_shipping_state"  class="addf_gr_countries_field_height title-gift-registry-input gift_registry_guest_shipping_state">
										<option value="" selected> <?php echo esc_html__( 'Select your country first', 'addf_giftr' ); ?></option>
									</select>
									<?php
								} else {
									$addf_gr_state = '';
									if ( '' != $shipping_country ) {
										$addf_gr_state = $addf_gr_country_obj->get_states( $shipping_country );
									}
									if ( ! empty( $addf_gr_state ) ) {
										?>
										
										<select required name="gift_registry_guest_shipping_state" class="addf_gr_countries_field_height title-gift-registry-input gift_registry_guest_shipping_state">
											<?php
											foreach ( $addf_gr_state as $value ) {
												?>
												<option <?php selected( $shipping_state, $value, true ); ?>><?php echo esc_html__( $value ); ?> </option>
												<?php
											}
											?>
										</select>
										
										<?php
									} else {
										?>
										<input type="Text" required name="gift_registry_guest_shipping_state" required class="addf_gr_countries_field_height title-gift-registry-input gift_registry_guest_shipping_state " value="<?php echo esc_html__( $shipping_state, 'addf_giftr' ); ?>" placeholder="<?php echo esc_html__( 'Enter State', 'addf_giftr' ); ?>">
										<?php
									}
								}
								?>
							</div>
						</td>
						<?php
				// }
						?>
					<tr>
						<td  class="addf-align-center" colspan="3">
							<button type="submit" name="submit-edit-registry"><?php echo esc_html__( 'Update Registry', 'addf_giftr' ); ?></button> 
							<input type="button" class="addf_gr_close_edit_btn" value="<?php echo esc_html__( 'Cancel', 'addf_giftr' ); ?>">
						</td>
					</tr>
				</table>
			</form>
			<?php
	// }
			?>
			
		<table  class="existing-gift-registry-data-table addf_gr_edit_registry addf_gr_max_width_table">
			<tr>
				<td class="addf-align-center" colspan="2">
					<h2> <?php echo esc_html__( the_title(), 'addf_giftr' ); ?></h2>
				</td>
			</tr>
			<?php
			// code commented after new update[feature: allow edit and delete for expiry registries]
			// if ( 'expired' == $addf_gr_active_visibility ) {
			if ( 'expired' == get_post_meta( get_the_ID(), 'gift-registry-event-info-expire-date', true ) ) {
				?>
				<tr>
					<td class="addf-align-center" colspan="2">
						<h3> <?php echo esc_html__( 'Registry Expired', 'addf_giftr' ); ?></h3>
					</td>
				</tr>
				<?php
			}
			?>
			<tr>
				<td>
					<h3><?php echo esc_html__( ' Visibility', 'addf_giftr' ); ?></h3>
				</td>
				<td>
					<?php
					$addf_gr_visibility_pri = get_post_meta( get_the_ID(), 'addf-gift-registry-visibility', true );
					if ( '2' === $addf_gr_visibility_pri ) {
						echo esc_html__( ' Password protected', 'addf_giftr' );
						echo '<br>' . esc_html__( ' Password: ', 'addf_giftr' ) . esc_html__( get_post_meta( get_the_ID(), 'addf-gift-registry-visibility-private-pass', true ), 'addf_giftr' );
					} else {
						echo esc_html__( ' Public', 'addf_giftr' );
					}
					?>
				</td>
			</tr>
			<tr>
				<td>
					<h3><?php echo esc_html__( ' Registrant Name', 'addf_giftr' ); ?></h3>
				</td>
				<td>
					<?php echo esc_html__( get_post_meta( get_the_ID(), 'gift-registry-registrant-first-name', true ), 'addf_giftr' ) . '&nbsp;' . esc_html__( get_post_meta( get_the_ID(), 'gift-registry-registrant-last-name', true ), 'addf_giftr' ); ?>
					<br>
				</td>
			</tr>
			<tr>
				<td>
					<h3><?php echo esc_html__( 'Registrant Email', 'addf_giftr' ); ?></h3>
				</td>
				<td>
					<?php echo esc_html__( get_post_meta( get_the_ID(), 'gift-registry-registrant-email', true ), 'addf_giftr' ); ?>
				</td>
			</tr>
			<tr>
				<td>
					<h3><?php echo esc_html__( 'Co Registrant Name', 'addf_giftr' ); ?></h3>
				</td>
				<td>
					<?php echo esc_html__( get_post_meta( get_the_ID(), 'gift-registry-co-registrant-first', true ), 'addf_giftr' ) . '&nbsp;' . esc_html__( get_post_meta( get_the_ID(), 'gift-registry-co-registrant-last-name', true ), 'addf_giftr' ); ?>
				</td>
			</tr>
			<tr>
				<td>
					<h3><?php echo esc_html__( 'Co Registrant Email', 'addf_giftr' ); ?></h3>
				</td>
				<td>
					<?php echo esc_html__( get_post_meta( get_the_ID(), 'gift-registry-co-registrant-email', true ), 'addf_giftr' ); ?>
				</td>
			</tr>
			<tr>
				<td>
					<h3><?php echo esc_html__( 'Event Date', 'addf_giftr' ); ?></h3>
				</td>
				<td>
					<?php echo esc_html__( get_post_meta( get_the_ID(), 'gift-registry-event-info-date', true ), 'addf_giftr' ); ?>
				</td>
			</tr>
			<tr>
				<td>
					<h3><?php echo esc_html__( 'Event Location', 'addf_giftr' ); ?></h3>
				</td>
				<td>
					<?php echo esc_html__( get_post_meta( get_the_ID(), 'gift-registry-event-info-location', true ), 'addf_giftr' ); ?>
				</td>
			</tr>
			<tr>
				<td>
					<h3><?php echo esc_html__( 'Event Detail', 'addf_giftr' ); ?></h3>
				</td>
				<td>
					<?php echo esc_html__( get_post_meta( get_the_ID(), 'gift-registry-event-info-message', true ), 'addf_giftr' ); ?>
				</td>
			</tr>
			<?php
				// shipping address
			// if ( ! is_user_logged_in() ) {
				$shipping_first_name = get_post_meta( get_the_ID(), 'gift_registry_guest_ship_f_name', true );
				$shipping_last_name  = get_post_meta( get_the_ID(), 'gift_registry_guest_ship_l_name', true );
				$shipping_company    = get_post_meta( get_the_ID(), 'gift_registry_guest_ship_comp_name', true );
				$shipping_address_1  = get_post_meta( get_the_ID(), 'gift_registry_guest_shipping_address', true );
				$shipping_city       = get_post_meta( get_the_ID(), 'gift_registry_guest_shipping_city', true );
				$shipping_postcode   = get_post_meta( get_the_ID(), 'gift_registry_guest_shipping_post_code', true );
				$shipping_country    = get_post_meta( get_the_ID(), 'gift_registry_guest_shipping_country', true );
				$shipping_state      = get_post_meta( get_the_ID(), 'gift_registry_guest_shipping_state', true );
			?>
				<tr >
					<td colspan="3" class="addf-align-center">
						<h3><?php echo esc_html__( 'Shipping Address', 'addf_giftr' ); ?></h3>
					</td>
				</tr>
				<tr >
					<td>
						<h3><?php echo esc_html__( 'First Name', 'addf_giftr' ); ?></h3>
					</td>
					<td colspan="2">
						<?php echo esc_html__( $shipping_first_name, 'addf_giftr' ); ?>
					</td>
				</tr>
				<tr >
					<td>
						<h3><?php echo esc_html__( 'Last Name', 'addf_giftr' ); ?></h3>
					</td>
					<td colspan="2">
						<?php echo esc_html__( $shipping_last_name, 'addf_giftr' ); ?>
					</td>
				</tr>
				<tr >
					<td>
						<h3><?php echo esc_html__( 'Company', 'addf_giftr' ); ?></h3>
					</td>
					<td colspan="2">
						<?php echo esc_html__( $shipping_company, 'addf_giftr' ); ?>
					</td>
				</tr>
				<tr >
					<td>
						<h3><?php echo esc_html__( 'Address', 'addf_giftr' ); ?></h3>
					</td>
					<td colspan="2">
						<?php echo esc_html__( $shipping_address_1, 'addf_giftr' ); ?>
					</td>
				</tr>
				<tr >
					<td>
						<h3><?php echo esc_html__( 'City', 'addf_giftr' ); ?></h3>
					</td>
					<td colspan="2">
						<?php echo esc_html__( $shipping_city, 'addf_giftr' ); ?>
					</td>
				</tr>
				<tr >
					<td>
						<h3><?php echo esc_html__( 'Postcode', 'addf_giftr' ); ?></h3>
					</td>
					<td colspan="2">
						<?php echo esc_html__( $shipping_postcode, 'addf_giftr' ); ?>
					</td>
				</tr>
				<tr >
					<td>
						<h3><?php echo esc_html__( 'Country', 'addf_giftr' ); ?></h3>
					</td>
					<td colspan="2">
						<?php
						$addf_gr_country_obj = new WC_Countries();
						$addf_gr_countries   = $addf_gr_country_obj->__get( 'countries' );
						$shipping_country_ts = '';
						foreach ( $addf_gr_countries as $key => $country_name ) {
							if ( $key == $shipping_country ) {
								$shipping_country_ts = $country_name;
								break;
							}
						}
						echo esc_html__( $shipping_country_ts, 'addf_giftr' );
						?>
					</td>
				</tr>
				<tr >
					<td>
						<h3><?php echo esc_html__( 'State', 'addf_giftr' ); ?></h3>
					</td>
					<td colspan="2">
						<?php echo esc_html__( $shipping_state, 'addf_giftr' ); ?>
					</td>
				</tr>
				<?php
			// }
			// code commented after new update[feature: allow edit and delete for expiry registries]
			// if ( 'expired' != $addf_gr_active_visibility ) {
				if ( 'expired' != get_post_meta( get_the_ID(), 'gift-registry-event-info-expire-date', true ) ) {

					if ( ( 'yes' === get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_email_share' ) ) || ( 'yes' === get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_twitter_share' ) ) || ( 'yes' === get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_fb_share' ) ) || ( 'yes' === get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_email_share_link_private' ) ) ) {
						?>
						<?php
						$addf_gr_enable_copy_link = get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_email_share_link_private' );
						if ( 'yes' == $addf_gr_enable_copy_link ) {
							if ( 'yes' === get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_email_share_link_private' ) ) {
								?>
							<td>
								<h3>
									<?php
									echo esc_html__( 'Copy Link', 'addf_giftr' );
									?>
								</h3>
							</td>
							<td>
								<input type="text" class="width_full" readonly value="<?php echo esc_url( the_permalink() ); ?>" name="" id="">
							</td>
								<?php

							}
						}
						?>
					<tr>

					</tr>
					<tr>
						<td>
							<h3>
								<?php
								$addf_gr_visibility_pri   = get_post_meta( get_the_ID(), 'addf-gift-registry-visibility', true );
								$addf_gr_enable_copy_link = get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_email_share_link_private' );
								if ( '1' === $addf_gr_visibility_pri ) {
									if ( ( 'yes' === get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_email_share' ) ) || ( 'yes' === get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_twitter_share' ) ) || ( 'yes' === get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_fb_share' ) ) ) {
										echo esc_html__( 'Share Via', 'addf_giftr' );
									}
								}
								?>
							</h3>
						</td>
						<td colspan="2">
								<?php
								$addf_gr_visibility_pri = get_post_meta( get_the_ID(), 'addf-gift-registry-visibility', true );
								if ( '1' === $addf_gr_visibility_pri ) {
									?>
									<?php
									if ( 'yes' === get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_email_share' ) ) {
										?>
									<i class="fas fa-envelope addf_gift_registry_social_font_style addf_gift_registry_send_mail" data-user_id="<?php echo esc_attr( get_post_meta( get_the_ID(), 'gift-registry-registrant-user_is_wp', true ) ); ?>" data-addf_g_r_post_id="<?php echo esc_attr( get_the_ID() ); ?>"></i>&nbsp;&nbsp;
									<?php
									}
									?>
									<?php
									if ( 'yes' === get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_twitter_share' ) ) {
										?>
									&nbsp; 
									<a href="https://twitter.com/share?url=<?php echo esc_url( the_permalink() ); ?>" 
										class="twitter-share-button" data-show-count="false"> <span class="fa"> <i class="fab fa-twitter addf_gift_registry_social_font_style"></i></span></a>
										<?php
										wp_enqueue_style( 'addf_gr_twitter', 'https://platform.twitter.com/widgets.js,false', '1.1', 'charset="utf-8" ' );
										?>
										<?php
									}
									?>
									<?php
									if ( 'yes' === get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_fb_share' ) ) {
										?>
										&nbsp;&nbsp;&nbsp;
										<iframe src="https://www.facebook.com/plugins/share_button.php?href=<?php echo esc_url( the_permalink() ); ?>
										&layout=button&size=small&width=67&height=20&appId" width="67" height="20" 
										style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" 
										allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>
										<?php
									}
								}
								?>
							</td>
						</tr>
							<?php
					}
				}
			// code commented after new update[feature: allow edit and delete for expiry registries]
			// if ( 'expired' != $addf_gr_active_visibility ) {
				if ( ( 'expired' == get_post_meta( get_the_ID(), 'gift-registry-event-info-expire-date', true ) && 'yes' == $addf_gr_enable_edit_delete_expired_registry ) || 'expired' != get_post_meta( get_the_ID(), 'gift-registry-event-info-expire-date', true )  ) {
					?>
					<tr>
						<td class="align-right" colspan="2">
							<span class="addf_prc_edit_btn addf_gr_edit_r ">
							<?php echo esc_html__( 'Edit', 'addf_giftr' ); ?>
							</span>
							<span class="addf_prc_delete_btn addf_gr_delete_r ">
							<?php echo esc_html__( 'Delete', 'addf_giftr' ); ?>
							</span>
							<input type="hidden" id="addf_gr_current_registry_id" data-registry-id="<?php echo esc_attr( get_the_ID() ); ?>">
						</td>
					</tr>
					<?php
				}
				?>
				<tr style="display:none;" class="addf-deetion-ajax-error-tr">
					<td style="display:none;" class="addf-deetion-ajax-error-tr" colspan="2">
						<span class="addf-deetion-ajax-error"></span>
					</td>
				</tr>
			</table>
			<form action="" method="post">
				<div class="existing-gift-registry-data-table">
					<div class="addf_gr_product_scrollable_div">
						<table  class="<?php echo esc_attr( 'existing-gift-registry-data-table_replace_data' . get_the_ID() ); ?>">
							<tr>
								<th>
									<?php echo esc_html__( 'Product Image', 'addf_giftr' ); ?>
								</th>
								<th>
									<?php echo esc_html__( 'Product Name', 'addf_giftr' ); ?>
								</th>
								<th>
									<?php echo esc_html__( 'Product Price', 'addf_giftr' ); ?>
								</th>
								<th>
									<?php echo esc_html__( 'Desired Quantity', 'addf_giftr' ); ?>
								</th>
								<th>
									<?php echo esc_html__( 'Recieved Quantity', 'addf_giftr' ); ?>
								</th>
								<?php
								if ( 'expired' != get_post_meta( get_the_ID(), 'gift-registry-event-info-expire-date', true ) ) {
									?>
									<th></th>
									<?php
								}
								?>
							</tr>
							<?php
							$addf_gift_registry_product = get_post_meta( get_the_ID(), 'addf_gift_registry_product', true );
							if ( is_array( $addf_gift_registry_product ) ) {
								foreach ( $addf_gift_registry_product as $key => $addf_g_r_single_product ) {
									if ( ' ' == $addf_g_r_single_product ) {
										continue;
									}
									if ( ! wc_get_product( $addf_g_r_single_product ) ) {
										continue;
									}
									?>
									<tr>
										<td>
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
										<td>
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

											<input type="hidden" name="addf_gr_post_id" value="<?php echo esc_attr( get_the_ID() ); ?>"  >
										</td>
										<td>
											<?php
											$_product = wc_get_product( $addf_g_r_single_product );
											echo wp_kses_post( $_product->get_price_html() );
											?>
										</td>
										<td>
											<!-- desired product -->
											<?php
											$var_for_singlr_product     = get_post_meta( get_the_ID(), 'addf_gr_product_quantity', true );
											$var_for_singlr_product_rec = get_post_meta( get_the_ID(), 'addf_gr_product_quantity_recieved', true );
											if ( array_key_exists( $key, (array) $var_for_singlr_product_rec ) ) {
												$addf_gr_min_desired_qty = $var_for_singlr_product_rec[ $key ];
											} else {
												$addf_gr_min_desired_qty = 0;
											}
											?>

											<input class="addf_gr_add_cart_input_field addf-gr-desire-product" min="<?php echo esc_attr( $addf_gr_min_desired_qty ); ?>" max="" value="<?php echo esc_attr( $var_for_singlr_product[ $key ] ); ?>" type="number" name="addf_gr_product_quantity[<?php echo esc_attr( $key ); ?>]" 
											<?php
											if ( 'expired' === get_post_meta( get_the_ID(), 'gift-registry-event-info-expire-date', true ) ) {
												echo 'readonly';}
											?>
												>
											</td>
											<td >
												<!-- Recieved Products -->
												<?php
												$var_for_singlr_product_rec = get_post_meta( get_the_ID(), 'addf_gr_product_quantity_recieved', true );
												if ( ! array_key_exists( $key, (array) $var_for_singlr_product_rec ) ) {
													echo esc_attr( 0 );
												} else {
													echo esc_attr( $var_for_singlr_product_rec[ $key ] );
												}
												?>
											</td>
											<?php
											if ( 'expired' != get_post_meta( get_the_ID(), 'gift-registry-event-info-expire-date', true ) ) {
												?>
												<td class="del_addf_single_product">
													<input type="hidden" readonly name="" class="addf-delete-product-from-registry_post_id_val" value="<?php echo esc_attr( get_the_ID() ); ?>" >                                            
													<span class=" addf-delete-product-from-registry"  data-id="<?php echo esc_attr( $key ) . ':' . esc_attr( get_the_ID() ); ?>"><span class="fa fa-trash"></span> </span>
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
										<td colspan="6" class="addf-align-center">
										<?php echo esc_html__( 'No product to show', 'addf_giftr' ); ?>
										</td>
									</tr>
									<?php
							}
							?>

							</table>
						</div>
						<?php
						if ( 'expired' != get_post_meta( get_the_ID(), 'gift-registry-event-info-expire-date', true ) ) {
							?>
							<div class="addf-gr-submit-btn">
								<input type="button" name="" class=" addf-add-product-from-registry" value="Add a Product" data-id="<?php echo esc_attr( get_the_ID() ); ?>">
								<button type="submit" name="addf-gift-registry-update-btn"><?php echo esc_html__( 'Save changes', 'addf_giftr' ); ?></button>
							</div>
						<?php } ?>
					</div>
				</form>
			</div>
			<?php
