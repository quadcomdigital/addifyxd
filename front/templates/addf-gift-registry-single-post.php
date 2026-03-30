<?php
	// updating a registered gift registry
if ( isset( $_POST['addf-gift-registry-update-btn'] ) ) {
	if ( isset( $_POST['addf_gr_post_id'] ) ) {
		$addf_gr_post_id = sanitize_meta( '', wp_unslash( $_POST['addf_gr_post_id'] ), '' );
	}
	if ( isset( $_POST['addf_gr_product_quantity'] ) ) {
		$addf_insert_product_quantity = sanitize_meta( '', wp_unslash( $_POST['addf_gr_product_quantity'] ), '' );
	}
	update_post_meta( $addf_gr_post_id, 'addf_gr_product_quantity', $addf_insert_product_quantity );
}
$addf_gr_active_visibility       = get_post_meta( get_the_ID(), 'gift-registry-event-info-expire-date', true );
$addf_gift_registry_meta_user    = get_post_meta( get_the_ID(), 'gift-registry-registrant-user_is_wp', true );
$addf_gift_registry_current_user = get_current_user_id();
if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
	$addf_gr_curr_ip = sanitize_meta( '', wp_unslash( isset( $_SERVER['HTTP_CLIENT_IP'] ) ? $_SERVER['HTTP_CLIENT_IP'] : '' ), '' );
} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
	$addf_gr_curr_ip = sanitize_meta( '', wp_unslash( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : '' ), '' );
} else {
	$addf_gr_curr_ip = sanitize_meta( '', wp_unslash( isset( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR'] : '' ), '' );
}
$addf_gr_check_logged_in_user = false;
if ( ( is_user_logged_in() ) ) {
	if ( $addf_gift_registry_meta_user == $addf_gift_registry_current_user ) {
		$addf_gr_check_logged_in_user = true;
	}
} elseif ( '0' == $addf_gift_registry_meta_user ) {
		$addf_gr_guest_ip_address_reg_created = get_post_meta( get_the_ID(), 'addf_gr_guest_ip_address_reg_created', true );
	if ( $addf_gr_curr_ip == $addf_gr_guest_ip_address_reg_created ) {
		$addf_gr_check_logged_in_user = true;
	}
}

if ( $addf_gr_check_logged_in_user ) {
	include ADDF_GR_DIR . 'front/templates/addf-gift-registry-creater.php';
} elseif ( 'expired' != $addf_gr_active_visibility ) {
		$addf_gr_visibility_pri      = get_post_meta( get_the_ID(), 'addf-gift-registry-visibility', true );
		$addf_gr_visibility_pri_pass = get_post_meta( get_the_ID(), 'addf-gift-registry-visibility-private-pass', true );
	if ( '2' === $addf_gr_visibility_pri ) {
		if ( ! isset( $_POST['title-gift-registry-btn-pass'] ) ) {
			?>
				<div class="addf-gr-pass-verify">
					<form action="" method="post" class="addf-gr-pass-verify-form">
					<?php wp_nonce_field( 'addify_gift_registry_nonce', 'addify_gift_registry_nonce' ); ?>
						<h3><?php echo esc_html__( ' Please verify password first', 'addf_giftr' ); ?></h3><br>
						<input type="password" name="addf-gr-pass-verification" class="title-gift-registry-input-pass">
						<br>
						<button class="title-gift-registry-btn-pass" name="title-gift-registry-btn-pass" type="submit"><?php echo esc_html__( ' Submit', 'addf_giftr' ); ?></button>
					</form>
				</div>
				<?php
		}

		$nonce = isset( $_POST['addify_gift_registry_nonce'] ) ? sanitize_text_field( $_POST['addify_gift_registry_nonce'] ) : '';

		if ( isset( $_POST['title-gift-registry-btn-pass'] ) && ! wp_verify_nonce( $nonce, 'addify_gift_registry_nonce' ) ) {

			wp_die( esc_html__( 'Security Violated!', 'addf_giftr' ) );

		}
		if ( isset( $_POST['title-gift-registry-btn-pass'] ) ) {

			$addf_gr_password = sanitize_meta( '', wp_unslash( isset( $_POST['addf-gr-pass-verification'] ) ? $_POST['addf-gr-pass-verification'] : '' ), 'post' );
			if ( $addf_gr_password == $addf_gr_visibility_pri_pass ) {
				include ADDF_GR_DIR . 'front/templates/addf-gift-registry-public-int.php';
			} else {
				?>
					<div class="addf-gr-pass-verify">
						<span class="align-center red"><?php echo esc_html__( 'Wrong password', 'addf_giftr' ); ?></span>
						<form action="" method="post" class="addf-gr-pass-verify-form">
						<?php wp_nonce_field( 'addify_gift_registry_nonce', 'addify_gift_registry_nonce' ); ?>
							<h3><?php echo esc_html__( ' Please verify password first', 'addf_giftr' ); ?></h3><br>
							<input type="password" name="addf-gr-pass-verification" class="title-gift-registry-input-pass">
							<br>
							<button class="title-gift-registry-btn-pass" name="title-gift-registry-btn-pass" type="submit"><?php echo esc_html__( ' Submit', 'addf_giftr' ); ?></button>
						</form>
					</div>
					<?php
			}
		}
	} else {
		include ADDF_GR_DIR . 'front/templates/addf-gift-registry-public-int.php';
	}
} else {
	$addf_gr_expired_msg = get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_exp_reg_msg' );
	?>
		<p class="woocommerce-info"><?php echo esc_html__( $addf_gr_expired_msg, 'addf_giftr' ); ?></p>
		<?php

}

