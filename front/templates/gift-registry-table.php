<?php

$addf_gr_enable_gift_registry_search = get_option('wc_settings_tab_gift_registry_notify_gift_registry_enable_gift_registry_search');
$addf_gr_gift_registry_search_page   = get_option('wc_settings_tab_gift_registry_search_visibility_page_option');

$addf_gr_allow_guests       = get_option( 'wc_settings_tab_gift_registry_notify_registrant_allow_gest' );
$addf_gr_rest_sh_addr_check = get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_restriction' );

$shipping_address_1     = get_user_meta( get_current_user_id(), 'shipping_address_1', true );
$shipping_city          = get_user_meta( get_current_user_id(), 'shipping_city', true );
$shipping_postcode      = get_user_meta( get_current_user_id(), 'shipping_postcode', true );
$shipping_country       = get_user_meta( get_current_user_id(), 'shipping_country', true );
$shipping_state         = get_user_meta( get_current_user_id(), 'shipping_state', true );
$addf_gr_shpin_addr_msg = get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_shiping_addr' );

if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
	$addf_gr_curr_ip = sanitize_meta( '', wp_unslash( isset( $_SERVER['HTTP_CLIENT_IP'] ) ? $_SERVER['HTTP_CLIENT_IP'] : '' ), '' );
} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
	$addf_gr_curr_ip = sanitize_meta( '', wp_unslash( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : '' ), '' );
} else {
	$addf_gr_curr_ip = sanitize_meta( '', wp_unslash( isset( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR'] : '' ), '' );
}
if ( ( 'yes' == $addf_gr_allow_guests ) && ( ! is_user_logged_in() ) ) {
	if ( ( 'yes' === $addf_gr_rest_sh_addr_check ) && ( ( '' === $shipping_address_1 ) || ( '' === $shipping_city ) || ( '' === $shipping_postcode ) || ( '' === $shipping_country ) || ( '' === $shipping_state ) ) ) {
		?>
		<p class="woocommerce-info"><?php echo esc_html__( $addf_gr_shpin_addr_msg, 'addf_giftr' ); ?></p>
		<?php
	} else {
		?>
		<div class="gift_registry_empty">
			<button class="a_for_create_registry"> <?php echo esc_html__( 'Create new Gift Registry', 'addf_giftr' ); ?></button>
		</div>
		<?php
	}
} elseif ( ( 'yes' != $addf_gr_allow_guests ) && ( ! is_user_logged_in() ) ) {
	$addf_gr_guest_msg = get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_guest_res_msg' );
	?>
	<p class="woocommerce-info"><?php echo esc_html__( $addf_gr_guest_msg, 'addf_giftr' ); ?></p>
	<?php
}
if ( 'sent' == WC()->session->get( 'addf_prc_notify_user_share_mail_sent' ) ) {
	?>
	<p class="woocommerce-info"><?php echo esc_html__( 'Mail sent successfully', 'addf_giftr' ); ?> </p>
	<?php
} elseif ( 'not_sent' == WC()->session->get( 'addf_prc_notify_user_share_mail_sent' ) ) {
	?>
	<p class="woocommerce-info"><?php echo esc_html__( 'Mail not sent please try again later', 'addf_giftr' ); ?></p>
	<?php
}
WC()->session->set( 'addf_prc_notify_user_share_mail_sent', '' );
if ( is_user_logged_in() ) {
	$addf_gr_allowed_users = get_option( 'wc_settings_tab_gift_registry_addf_gr_allow_users_create_registry' );
	$user                  = wp_get_current_user(); // getting & setting the current user
	$roles                 = $user->roles;
	if ( in_array( $roles[0], (array) $addf_gr_allowed_users ) || empty( $addf_gr_allowed_users ) ) {
		if ( ( 'yes' === $addf_gr_rest_sh_addr_check ) && ( ( '' === $shipping_address_1 ) || ( '' === $shipping_city ) || ( '' === $shipping_postcode ) || ( '' === $shipping_country ) || ( '' === $shipping_state ) ) ) {
			?>
			<p class="woocommerce-info"><?php echo esc_html__( $addf_gr_shpin_addr_msg, 'addf_giftr' ); ?></p>
			<?php
		} else {
			?>
			<div class="gift_registry_empty">
				<button class="a_for_create_registry"> <?php echo esc_html__( 'Create new Gift Registry', 'addf_giftr' ); ?></button>
			</div>

			<!-- Gift registry search functionality[new added feature] -->

			<?php if ('yes' == $addf_gr_enable_gift_registry_search && ( 'registry_page' == $addf_gr_gift_registry_search_page || 'both' == $addf_gr_gift_registry_search_page ) && is_user_logged_in()) { ?>

			<div id="addf-gr-registry-search-container" >
				<select id="addf-gr-registry-search-select" name="addf_gr_registry_search_select" > 
				</select>			
			</div>
				<?php
			}
		}
	} else {
		$addf_gr_user_res_msg = get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_rest_user_res_msg' );
		?>
		<p class="woocommerce-info"><?php echo esc_html__( $addf_gr_user_res_msg, 'addf_giftr' ); ?></p>
		<?php
	}
}
?>

<div class="register-new-gift-registry">
	<div class="register-new-gift-registry-title">
		<h3 class="addf_gr_create_new_reg"> <?php echo esc_html__( 'Creating New Gift Registry', 'addf_giftr' ); ?></h3>
	</div>
	<div class="register-new-gift-registry-body">
		<form action="" method="post">
			<?php wp_nonce_field( 'addify_gift_registry_nonce', 'addify_gift_registry_nonce' ); ?>
			<table>
				<tr>
					<td class="text-table-side">
						<span class="title-gift-registry"><strong> <?php echo esc_html__( 'Title', 'addf_giftr' ); ?><span class="red">&nbsp;*</strong></span></span>
					</td>
					<td>
						<input class="title-gift-registry-input" type="text" placeholder="Enter Title of registry" name="gift-registry-title"  required><br>
					</td>
				</tr>
				<tr>
					<td class="text-table-side">
						<span class="title-gift-registry"><strong> <?php echo esc_html__( 'Visibility', 'addf_giftr' ); ?><span class="red">&nbsp;*</strong></span></span>
					</td>
					<td>
						<input class="addf-gift-registry-visibility-pub"  type="radio" value="1"  name="addf-gift-registry-visibility" checked >
						<label for="addf-gift-registry-visibility-pub"><?php echo esc_html__( 'Public', 'addf_giftr' ); ?></label>&nbsp;&nbsp;
						<input class="addf-gift-registry-visibility-pri" type="radio" value="2"  name="addf-gift-registry-visibility"  >
						<label for="addf-gift-registry-visibility-pri"><?php echo esc_html__( 'Private', 'addf_giftr' ); ?></label>
					</td>
				</tr>
				<tr class="addf-gift-registry-visibility-private-tr">
					<td>
						<span class="title-gift-registry"><strong> <?php echo esc_html__( 'Password', 'addf_giftr' ); ?><span class="red">&nbsp;*</strong></span>
					</td>
					<td>
						<input type="password" class="title-gift-registry-input addf-gift-registry-visibility-private-pass" name="addf-gift-registry-visibility-private-pass" placeholder="<?php echo esc_html__( 'Enter Password', 'addf_giftr' ); ?>">
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<h3  class="addf_gr_create_new_reg"> <?php echo esc_html__( 'Registrant Information', 'addf_giftr' ); ?></h3>
					</td>
				</tr>

				<?php
				if ( is_user_logged_in() ) {
					global $current_user;
					?>
					<tr>
						<td>
							<span class="title-gift-registry"><strong> <?php echo esc_html__( 'User Name', 'addf_giftr' ); ?><span class="red">&nbsp;*</strong></span></span>
						</td>
						<td>
							<input class="title-gift-registry-input" type="text" placeholder="Enter last Name of Registrant" name="gift-registry-registrant-first-name"  readonly value="<?php echo esc_html__( $current_user->display_name ); ?>"><br>
						</td>
					</tr>
					<tr>
						<td>
							<span class="title-gift-registry"><strong> <?php echo esc_html__( 'Email', 'addf_giftr' ); ?><span class="red">&nbsp;*</strong></span></span>
						</td>
						<td>
							<input class="title-gift-registry-input" type="email" placeholder="Enter Email of Registrant" name="gift-registry-registrant-email" readonly value="<?php echo esc_html__( $current_user->user_email ); ?>"><br>
						</td>
					</tr>
					<?php
				} else {
					?>
					<tr>
						<td>
							<span class="title-gift-registry"><strong> <?php echo esc_html__( 'First Name', 'addf_giftr' ); ?><span class="red">&nbsp;*</strong></span></span>
						</td>
						<td>
							<input class="title-gift-registry-input" type="text" placeholder="Enter First Name of Registrant" name="gift-registry-registrant-first-name" 
							<?php
							if ( ! is_user_logged_in() ) {
								echo 'required';}
							?>
								><br>
							</td>
						</tr>
						<tr>
							<td>
								<span class="title-gift-registry"><strong> <?php echo esc_html__( 'Last Name', 'addf_giftr' ); ?><span class="red">&nbsp;*</strong></span></span>
							</td>
							<td>
								<input class="title-gift-registry-input" type="text" required placeholder="Enter last Name of Registrant" name="gift-registry-registrant-last-name"  
								<?php
								if ( ! is_user_logged_in() ) {
									echo 'required';}
								?>
									><br>
								</td>
							</tr>
							<tr>
								<td>
									<span class="title-gift-registry"><strong> <?php echo esc_html__( 'Email', 'addf_giftr' ); ?><span class="red">&nbsp;*</strong></span></span>
								</td>
								<td>
									<input class="title-gift-registry-input" required type="email" placeholder="Enter Email of Registrant" name="gift-registry-registrant-email"  
									<?php
									if ( ! is_user_logged_in() ) {
										echo 'required';}
									?>
										><br>
									</td>
								</tr>
							<?php } ?>
							
							<tr>
								<td colspan="2">
									<h3 class="addf_gr_create_new_reg"> <?php echo esc_html__( 'Co-Registrant Information', 'addf_giftr' ); ?></h3>
								</td>
							</tr>
							<tr>
								<td>
									<span class="title-gift-registry"><strong> <?php echo esc_html__( 'First Name', 'addf_giftr' ); ?></strong></span>
								</td>
								<td>
									<input class="title-gift-registry-input" type="text" placeholder="Enter First Name of Co-Registrant" name="gift-registry-co-registrant-first"  ><br>
								</td>
							</tr>
							<tr>
								<td>
									<span class="title-gift-registry"><strong> <?php echo esc_html__( 'Last Name', 'addf_giftr' ); ?></strong></span>
								</td>
								<td>
									<input class="title-gift-registry-input" type="text" placeholder="Enter last Name of Co-Registrant" name="gift-registry-co-registrant-last-name"  ><br>
								</td>
							</tr>
							<tr>
								<td>
									<span class="title-gift-registry"><strong> <?php echo esc_html__( 'Email', 'addf_giftr' ); ?></strong></span>
								</td>
								<td>
									<input class="title-gift-registry-input" type="email" placeholder="Enter Email of Co-Registrant" name="gift-registry-co-registrant-email"  ><br>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<h3 class="addf_gr_create_new_reg"> <?php echo esc_html__( 'Event Information', 'addf_giftr' ); ?></h3>
								</td>
							</tr>
							<tr>
								<td>
									<span class="title-gift-registry"><strong> <?php echo esc_html__( 'Event Date', 'addf_giftr' ); ?><span class="red">&nbsp;*</strong></span></span>
								</td>
								<td>
									<input class="addf-gift-registry-event-date title-gift-registry-input" type="date" name="gift-registry-event-info-date"  required><br>
								</td>
							</tr>
							<tr>
								<td>
									<span class="title-gift-registry"><strong> <?php echo esc_html__( 'Event Location', 'addf_giftr' ); ?></strong></span>
								</td>
								<td>
									<input class="title-gift-registry-input" type="text" placeholder="Event Location" name="gift-registry-event-info-location" ><br>
								</td>
							</tr>
							<tr>
								<td>
									<span class="title-gift-registry"><strong> <?php echo esc_html__( 'Event Message', 'addf_giftr' ); ?></strong></span>
								</td>
								<td>
									<input class="title-gift-registry-input" type="text" placeholder="Event Message" name="gift-registry-event-info-message" ><br>
								</td>
							</tr>
							<?php
								$shipping_first_name = '';
								$shipping_last_name  = '';
								$shipping_company    = '';
								$shipping_address_1  = '';
								$shipping_address_2  = '';
								$shipping_city       = '';
								$shipping_country    = '';
								$shipping_postcode   = '';
								$shipping_state      = '';


							if (is_user_logged_in()) {
								global $current_user;

								$shipping_first_name = get_user_meta( $current_user->ID, 'shipping_first_name', true );
								$shipping_last_name  = get_user_meta( $current_user->ID, 'shipping_last_name', true );
								$shipping_company    = get_user_meta( $current_user->ID, 'shipping_company', true );
								$shipping_address_1  = get_user_meta( $current_user->ID, 'shipping_address_1', true );
								$shipping_address_2  = get_user_meta( $current_user->ID, 'shipping_address_2', true );
								
								$shipping_city     = get_user_meta( $current_user->ID, 'shipping_city', true );
								$shipping_postcode = get_user_meta( $current_user->ID, 'shipping_postcode', true );
								$shipping_country  = get_user_meta( $current_user->ID, 'shipping_country', true );
								$shipping_state    = get_user_meta( $current_user->ID, 'shipping_state', true );
								
							}
							// shipping address for guest
							// if ( ! is_user_logged_in() ) {
							?>
								<input type="hidden" name="addf_gr_guest_ip_address_reg_created" value="<?php echo esc_attr( $addf_gr_curr_ip ); ?>">
								<tr>
									<td colspan="2">
										<h3 class="addf_gr_create_new_reg"> <?php echo esc_html__( 'Shipping Address', 'addf_giftr' ); ?></h3>
									</td>
								</tr>
								<tr>
									<td>
										<span class="title-gift-registry"><strong> <?php echo esc_html__( 'First Name', 'addf_giftr' ); ?><span class="red">&nbsp;*</strong></strong></span>
									</td>
									<td>
										<input class="title-gift-registry-input" required type="text" placeholder="First Name" name="gift_registry_guest_ship_f_name" value=<?php echo esc_attr($shipping_first_name); ?>><br>
									</td>
								</tr>
								<tr>
									<td>
										<span class="title-gift-registry"><strong> <?php echo esc_html__( 'Last Name', 'addf_giftr' ); ?><span class="red">&nbsp;*</strong></strong></span>
									</td>
									<td>
										<input class="title-gift-registry-input" required type="text" placeholder="Last Name" name="gift_registry_guest_ship_l_name" value=<?php echo esc_attr($shipping_last_name); ?>><br>
									</td>
								</tr>
								<tr>
									<td>
										<span class="title-gift-registry"><strong> <?php echo esc_html__( 'Company Name', 'addf_giftr' ); ?></strong></span>
									</td>
									<td>
										<input class="title-gift-registry-input"  type="text" placeholder="Company Name" name="gift_registry_guest_ship_comp_name" value=<?php echo esc_attr($shipping_company); ?>><br>
									</td>
								</tr>
								<tr>
									<td>
										<span class="title-gift-registry"><strong> <?php echo esc_html__( 'Address', 'addf_giftr' ); ?><span class="red">&nbsp;*</strong></strong></span>
									</td>
									<td>
										<input class="title-gift-registry-input" required type="text" placeholder="Address" name="gift_registry_guest_shipping_address" value=<?php echo esc_attr($shipping_address_1) . ' ' . esc_attr($shipping_address_2); ?>><br>
									</td>
								</tr>
								<tr>
									<td>
										<span class="title-gift-registry"><strong> <?php echo esc_html__( 'City', 'addf_giftr' ); ?><span class="red">&nbsp;*</strong></strong></span>
									</td>
									<td>
										<input class="title-gift-registry-input" required type="text" placeholder="City" name="gift_registry_guest_shipping_city" value=<?php echo esc_attr($shipping_city); ?>><br>
									</td>
								</tr>
								<tr>
									<td>
										<span class="title-gift-registry"><strong> <?php echo esc_html__( 'Postcode', 'addf_giftr' ); ?><span class="red">&nbsp;*</strong></strong></span>
									</td>
									<td>
										<input class="title-gift-registry-input" required type="text" placeholder="Postcode" name="gift_registry_guest_shipping_post_code" value=<?php echo esc_attr($shipping_postcode); ?>><br>
									</td>
								</tr>
								<tr>
									<td>
										<span class="title-gift-registry"><strong> <?php echo esc_html__( 'Country', 'addf_giftr' ); ?><span class="red">&nbsp;*</strong></strong></span>
									</td>
									<td>
									<select name="gift_registry_guest_shipping_country" required class="addf_gr_countries_field_height addf_gr_countries_billing_front title-gift-registry-input">
										<option value="" <?php echo ( '' == $shipping_country ) ? 'selected' : ''; ?> hidden ><?php echo esc_html__( 'Select your country', 'addf_giftr' ); ?></option>
										<?php
										$addf_gr_country_obj = new WC_Countries();
										$addf_gr_countries   = $addf_gr_country_obj->__get( 'countries' );
										foreach ( $addf_gr_countries as $key => $country_name ) {
											?>
											<option value="<?php echo esc_html( $key ); ?>" <?php echo ( $shipping_country == $key ) ? 'selected' : ''; ?>>
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
										<span class="title-gift-registry"><strong> <?php echo esc_html__( 'State', 'addf_giftr' ); ?><span class="red">&nbsp;*</strong></strong></span>
									</td>
									<td>
										<div class="addf_gr_guest_state">
											<select name="gift_registry_guest_shipping_state" required class="addf_gr_countries_field_height title-gift-registry-input gift_registry_guest_shipping_state">
												<option value="" selected> <?php echo esc_html__( 'Select your country first', 'addf_giftr' ); ?></option>
											</select>
										</div>
									</td>
								</tr>
								<?php
							// }
							$addf_gr_enable_pp_cb   = get_option( 'addf_gr_enable_pp_cb' );
							$addf_gr_enable_pp_text = get_option( 'addf_gr_enable_pp_text' );
								if ( 'yes' == $addf_gr_enable_pp_cb ) {
									?>
								<tr>
									<td></td>
									<td >
										<input type="checkbox" id="addf_gr_agree_pp_cb">
										<span> <?php echo wp_kses_post( $addf_gr_enable_pp_text ); ?></span>
									</td>
								</tr>
								<?php
								}
								?>
							<input type="hidden" class="addf_gr_addf_gr_enable_pp_cb" value="<?php echo esc_attr( $addf_gr_enable_pp_cb ); ?>" >
						</table>
						<br><button type="submit" name="gift-registry-submit" id="gift-registry-submit"> <?php echo esc_html__( 'Create Gift Registry', 'addf_giftr' ); ?></button>
						<input type="button" class="button a_for_create_registry_calcel" value="<?php echo esc_html__( 'Cancel', 'addf_giftr' ); ?>">
					</form>
				</div>
			</div>
			<br>
			<?php
			if ( WC()->session->get( 'addf_prc_notify_user' ) ) {
				?>
				<p class="woocommerce-info"> <?php echo esc_html__( ' New Gift Registry Created', 'addf_giftr' ); ?></p>
				<?php
			}
			WC()->session->set( 'addf_prc_notify_user', false );
			$addf_gr_args                = array(
				'post_type'      => 'addf_gift_registry',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'orderby'        => 'id',
				'order'          => 'desc',
				'meta_query'     => array(
					array(
						'key'   => 'gift-registry-registrant-user_is_wp',
						'value' => get_current_user_id(),
					),
				),
			);
			$addf_gr_loop                = new WP_Query( $addf_gr_args );
			$addf_gr_get_current_user_id = get_current_user_id();
			while ( $addf_gr_loop->have_posts() ) :
				$addf_gr_loop->the_post();
				$addf_gr_post              = $addf_gr_loop->get_post();
				$addf_gr_active_visibility = get_post_meta( get_the_ID(), 'gift-registry-event-info-expire-date', true );
				$addf_gr_get_reg_user_id   = get_post_meta( get_the_ID(), 'gift-registry-registrant-user_is_wp', true );
				if ( is_user_logged_in() ) {
					if ( $addf_gr_get_reg_user_id != $addf_gr_get_current_user_id ) {
						continue;
					}
				} elseif ( '0' == $addf_gr_get_reg_user_id ) {
					$addf_gr_guest_ip_address_reg_created = get_post_meta( get_the_ID(), 'addf_gr_guest_ip_address_reg_created', true );
					if ( $addf_gr_curr_ip != $addf_gr_guest_ip_address_reg_created ) {
						continue;
					}
				}
				?>
				
				<div class=" existing-gift-registry-data existing-gift-registry-data<?php echo esc_attr( get_the_ID() ); ?>" data-addf_gr_post_id="<?php echo esc_attr( get_the_ID() ); ?>">
					<table  class="existing-gift-registry-data-table addf_gr_edit_registry addf_gr_max_width">
						<tr>
							<td class=" addf-align-center" colspan="3">
								<h2><a class="addf_gift_registry_redirect" href="<?php echo esc_url( the_permalink() ); ?>"><?php echo esc_html__( get_post( get_the_ID() )->post_title, 'addf_giftr' ); ?></a></h2>
							</td>
						</tr>
						<?php
						if ( 'expired' == $addf_gr_active_visibility ) {
							?>
							<tr>
								<td class=" addf-align-center" colspan="3">
									<h3><?php echo esc_html__( 'Registry Expired', 'addf_giftr' ); ?></a></h3>
								</td>
							</tr>
						<?php } ?>
						<tr>
							<td>
								<h3><?php echo esc_html__( ' Visibility', 'addf_giftr' ); ?></h3>
							</td>
							<td colspan="2">
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
							<td colspan="2">
								<?php echo esc_html__( get_post_meta( get_the_ID(), 'gift-registry-registrant-first-name', true ), 'addf_giftr' ) . '&nbsp;' . esc_html__( get_post_meta( get_the_ID(), 'gift-registry-registrant-last-name', true ), 'addf_giftr' ); ?>
								<br>
							</td>
						</tr>
						<tr>
							<td>
								<h3><?php echo esc_html__( 'Registrant Email', 'addf_giftr' ); ?></h3>
							</td>
							<td colspan="2">
								<?php echo esc_html__( get_post_meta( get_the_ID(), 'gift-registry-registrant-email', true ), 'addf_giftr' ); ?>
							</td>
						</tr>
						<tr style="display:none;" class="addf_gr_post_see_less addf_gr_post_see_less<?php echo esc_attr(get_the_ID()); ?>">
							<td>
								<h3><?php echo esc_html__( 'Co Registrant Name', 'addf_giftr' ); ?></h3>
							</td>
							<td colspan="2">
								<?php echo esc_html__( get_post_meta( get_the_ID(), 'gift-registry-co-registrant-first', true ), 'addf_giftr' ) . '&nbsp;' . esc_html__( get_post_meta( get_the_ID(), 'gift-registry-co-registrant-last-name', true ), 'addf_giftr' ); ?>
							</td>
						</tr>
						<tr style="display:none;" class="addf_gr_post_see_less addf_gr_post_see_less<?php echo esc_attr(get_the_ID()); ?>">
							<td>
								<h3><?php echo esc_html__( 'Co Registrant Email', 'addf_giftr' ); ?></h3>
							</td>
							<td colspan="2">
								<?php echo esc_html__( get_post_meta( get_the_ID(), 'gift-registry-co-registrant-email', true ), 'addf_giftr' ); ?>
							</td>
						</tr>
						<tr>
							<td>
								<h3><?php echo esc_html__( 'Event Date', 'addf_giftr' ); ?></h3>
							</td>
							<td colspan="2">
								<?php echo esc_html__( get_post_meta( get_the_ID(), 'gift-registry-event-info-date', true ), 'addf_giftr' ); ?>
							</td>
						</tr>
						<tr>
							<td>
								<h3><?php echo esc_html__( 'Event Location', 'addf_giftr' ); ?></h3>
							</td>
							<td colspan="2">
								<?php echo esc_html__( get_post_meta( get_the_ID(), 'gift-registry-event-info-location', true ), 'addf_giftr' ); ?>
							</td>
						</tr>
						<tr style="display:none;" class="addf_gr_post_see_less addf_gr_post_see_less<?php echo esc_attr(get_the_ID()); ?>">
							<td>
								<h3><?php echo esc_html__( 'Event Detail', 'addf_giftr' ); ?></h3>
							</td>
							<td colspan="2">
								<?php echo esc_html__( get_post_meta( get_the_ID(), 'gift-registry-event-info-message', true ), 'addf_giftr' ); ?>
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
							<tr style="display:none;" class="addf_gr_post_see_less addf_gr_post_see_less<?php echo esc_attr(get_the_ID()); ?>">
								<td colspan="3" class="addf-align-center">
									<h3><?php echo esc_html__( 'Shipping Address', 'addf_giftr' ); ?></h3>
								</td>
							</tr>
							<tr style="display:none;" class="addf_gr_post_see_less addf_gr_post_see_less<?php echo esc_attr(get_the_ID()); ?>">
								<td>
									<h3><?php echo esc_html__( 'First Name', 'addf_giftr' ); ?></h3>
								</td>
								<td colspan="2">
									<?php echo esc_html__( $shipping_first_name, 'addf_giftr' ); ?>
								</td>
							</tr>
							<tr style="display:none;" class="addf_gr_post_see_less addf_gr_post_see_less<?php echo esc_attr(get_the_ID()); ?>">
								<td>
									<h3><?php echo esc_html__( 'Last Name', 'addf_giftr' ); ?></h3>
								</td>
								<td colspan="2">
									<?php echo esc_html__( $shipping_last_name, 'addf_giftr' ); ?>
								</td>
							</tr>
							<tr style="display:none;" class="addf_gr_post_see_less addf_gr_post_see_less<?php echo esc_attr(get_the_ID()); ?>">
								<td>
									<h3><?php echo esc_html__( 'Company', 'addf_giftr' ); ?></h3>
								</td>
								<td colspan="2">
									<?php echo esc_html__( $shipping_company, 'addf_giftr' ); ?>
								</td>
							</tr>
							<tr style="display:none;" class="addf_gr_post_see_less addf_gr_post_see_less<?php echo esc_attr(get_the_ID()); ?>">
								<td>
									<h3><?php echo esc_html__( 'Address', 'addf_giftr' ); ?></h3>
								</td>
								<td colspan="2">
									<?php echo esc_html__( $shipping_address_1, 'addf_giftr' ); ?>
								</td>
							</tr>
							<tr style="display:none;" class="addf_gr_post_see_less addf_gr_post_see_less<?php echo esc_attr(get_the_ID()); ?>">
								<td>
									<h3><?php echo esc_html__( 'City', 'addf_giftr' ); ?></h3>
								</td>
								<td colspan="2">
									<?php echo esc_html__( $shipping_city, 'addf_giftr' ); ?>
								</td>
							</tr>
							<tr style="display:none;" class="addf_gr_post_see_less addf_gr_post_see_less<?php echo esc_attr(get_the_ID()); ?>">
								<td>
									<h3><?php echo esc_html__( 'Postcode', 'addf_giftr' ); ?></h3>
								</td>
								<td colspan="2">
									<?php echo esc_html__( $shipping_postcode, 'addf_giftr' ); ?>
								</td>
							</tr>
							<tr style="display:none;" class="addf_gr_post_see_less addf_gr_post_see_less<?php echo esc_attr(get_the_ID()); ?>">
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
							<tr style="display:none;" class="addf_gr_post_see_less addf_gr_post_see_less<?php echo esc_attr(get_the_ID()); ?>">
								<td>
									<h3><?php echo esc_html__( 'State', 'addf_giftr' ); ?></h3>
								</td>
								<td colspan="2">
									<?php echo esc_html__( $shipping_state, 'addf_giftr' ); ?>
								</td>
							</tr>
							<?php
						// }
							if ( 'expired' != $addf_gr_active_visibility ) {
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
												&nbsp;&nbsp;&nbsp;
												<a href="https://twitter.com/share?url=<?php echo esc_url( the_permalink() ); ?>" 
													class="twitter-share-button" data-show-count="false"><span class="fa"> <i class="fab fa-twitter addf_gift_registry_social_font_style"></i></span></a>
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
							?>
							<tr>
								<td style="display:none;" class="addf-deetion-ajax-error-tr" colspan="2">
									<span class="addf-deetion-ajax-error"></span>
								</td>
							</tr>
						</table>
						<form action="" method="post"  style="display:none;" class="addf_gr_post_see_less addf_gr_post_see_less<?php echo esc_attr(get_the_ID()); ?>">
							<?php wp_nonce_field( 'addify_gift_registry_nonce', 'addify_gift_registry_nonce' ); ?>
							<div class="existing-gift-registry-data-table">
								<div class="addf_gr_product_scrollable_div">
									<table  class="<?php echo esc_attr('existing-gift-registry-data-table_replace_data' . get_the_ID()); ?>">
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
											if ( 'expired' != $addf_gr_active_visibility ) {
												?>
												<th></th>
												<?php
											}
											?>
										</tr>
										<?php
										$addf_gift_registry_product = get_post_meta( get_the_ID(), 'addf_gift_registry_product', true );
										if ( is_array( $addf_gift_registry_product ) ) {
											$count_addf_gift_registry_product = count( $addf_gift_registry_product );
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
														if ( ! $addf_single_product_image ) {
															$product = wc_get_product( $addf_g_r_single_product );
															if ( $product->is_type( 'variation' ) ) {
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

														<input type="number" name="addf_gr_post_id" value="<?php echo esc_attr(get_the_ID()); ?>"  style="display:none;">
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
														if ( array_key_exists( $key, (array) $var_for_singlr_product ) ) {
															$var_for_singlr_product_single = $var_for_singlr_product[ $key ];
														} else {
															$var_for_singlr_product_single = 1;
														}
														if ( array_key_exists( $key, (array) $var_for_singlr_product_rec ) ) {
															if ( 1 < $var_for_singlr_product_rec[ $key ] ) {
																$addf_gr_min_desired_qty = $var_for_singlr_product_rec[ $key ];
															} else {
																$addf_gr_min_desired_qty = 1;
															}
														} else {
															$addf_gr_min_desired_qty = 0;
														}
														?>

														<input class="addf_gr_add_cart_input_field addf-gr-desire-product" min="<?php echo esc_attr( $addf_gr_min_desired_qty ); ?>" max="" value="<?php echo esc_attr( $var_for_singlr_product_single ); ?>" type="number" name="addf_gr_product_quantity[<?php echo esc_attr( $key ); ?>]" 
														<?php
														if ( 'expired' == $addf_gr_active_visibility ) {
															echo 'readonly';}
														?>
															>
														</td>
														<td >
															<!-- Recieved Products -->
															<?php
															$var_for_singlr_product_rec = get_post_meta( get_the_ID(), 'addf_gr_product_quantity_recieved', true );
															if ( array_key_exists( $key, (array) $var_for_singlr_product_rec ) ) {
																$var_for_singlr_product_single = $var_for_singlr_product_rec[ $key ];
															} else {
																$var_for_singlr_product_single = 0;
															}
															echo esc_attr( $var_for_singlr_product_single );
															?>
														</td>
														<?php
														if ( 'expired' != $addf_gr_active_visibility ) {
															?>
															<td class="del_addf_single_product">
																<input type="hidden" readonly name="" class="addf-delete-product-from-registry_post_id_val" value="<?php echo esc_attr(get_the_ID()); ?>" >
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
									if ( 'expired' != $addf_gr_active_visibility ) {
										?>
										<div class="addf-gr-submit-btn">
											<input type="button" name="" class="addf-add-product-from-registry" value="Add a Product" data-id="<?php echo esc_attr(get_the_ID()); ?>">
											<button type="submit" name="addf-gift-registry-update-btn"><?php echo esc_html__( 'Save changes', 'addf_giftr' ); ?></button>
										</div>
										<?php
									}
									?>
								</div>
							</form>
							<div class="addf_gr_cursor_div addf_gr_cursor addf-align-center addf_gr_cursor_see_all"  data-addf_gr_post_id="<?php echo esc_attr(get_the_ID()); ?>">
								<p class="addf_gr_cursor_see_all<?php echo esc_attr(get_the_ID()); ?>"><?php echo esc_html__( 'View More', 'addf_giftr' ); ?></p>
								<p style="display:none;" class="addf_gr_cursor_see_less<?php echo esc_attr(get_the_ID()); ?>"><?php echo esc_html__( 'View Less', 'addf_giftr' ); ?></p>
							</div>
						</div>
						<?php
					endwhile;
			?>
					<?php



