<?php
if ( ! class_exists( 'AF_Gift_Registry_Front ' ) ) {
	class AF_Gift_Registry_Front {
		public function __construct() {
			// adding css and javascript files
			add_action( 'wp_enqueue_scripts', array( $this, 'addf_gift_registry_files_add_script' ) );

			// add single product popup
			add_action( 'wp_footer', array( $this, 'addf_gift_registry_add_single_product' ) );
			add_action('wp_footer', array( $this, 'addf_gift_registry_add_greeting_message' ));
			add_action( 'wp_loaded', array( $this, 'addf_gift_registry_wp_loaded_send_mail' ), 100 );

			// Add endpoint of quote and process its content.
			add_action( 'init', array( $this, 'addify_gift_registry_add_endpoints' ) );

			add_filter( 'woocommerce_account_menu_items', array( $this, 'addify_gift_registry_new_menu_items' ) );

			add_action( 'woocommerce_account_gift_registry_endpoint', array( $this, 'addify_gift_registry_endpoint_content' ) );
			add_shortcode( 'addf_gift_registry_short_code', array( $this, 'addify_gift_registry_endpoint_content' ) );

			add_filter( 'query_vars', array( $this, 'addify_gift_registry_add_query_vars' ) );

			add_filter( 'the_title', array( $this, 'addify_gift_registry_endpoint_title' ) );

			// add  with cart button
			add_action( 'woocommerce_after_shop_loop_item', array( $this, 'addf_gift_registry_add_to_cart_button_shop' ) );

			// add with cart btn single product page
			add_action( 'woocommerce_single_product_summary', array( $this, 'addf_gift_registry_add_to_cart_single_product_page' ) );

			// for single listing page
			add_filter( 'template_include', array( __CLASS__, 'addf_gr_portfolio_page_template' ) );

			// cart info div about shipping
			add_filter( 'woocommerce_before_cart_contents', array( $this, 'addf_gift_registry_info_shipping_to_others__cart_cb' ) );
			add_filter( 'woocommerce_after_checkout_billing_form', array( $this, 'addf_gift_registry_info_shipping_to_others_cb' ) );
			add_filter( 'woocommerce_shipping_fields', array( $this, 'addf_gift_registry_remove_shipping_cb' ) );

			// order is done
			// woocommerce_thankyou
			// woocommerce_checkout_order_created
			add_action( 'woocommerce_checkout_order_created', array( $this, 'addf_gift_registry_after_placing_order' ), 10, 1 );

			// update cart woocommerce_cart_item_removed
			add_action( 'woocommerce_cart_updated', array( $this, 'addf_gift_registry_cart_is_updated_ajax' ) );

			// restrict add to cart
			add_action( 'woocommerce_before_shop_loop', array( $this, 'addf_gr_restrict_add_to_cart_info' ) );
			add_action( 'woocommerce_before_single_product_summary', array( $this, 'addf_gr_restrict_add_to_cart_info' ) );

			// remove from menu
			add_filter( 'wp_nav_menu_items', array( $this, 'addf_gift_registry_add_to_menu' ), 10, 2 );

			add_action( 'wp_loaded', array( $this, 'addf_gr_add_mail_files' ) );

				// HOPS compatibility
			add_action( 'before_woocommerce_init', array( $this, 'af_gr__HOPS_Compatibility' ) );


			//update code[Greeting message feature]
			add_filter( 'woocommerce_get_item_data', array( $this, 'addf_gift_registry_display_greeting_message_in_cart' ), 10, 2 );

			add_action( 'woocommerce_add_order_item_meta', array( $this, 'addf_gift_registry_add_greeting_message_to_order_for_individual_product' ), 10, 3 );

			add_action( 'woocommerce_order_item_meta_end', array( $this, 'addf_gift_registry_show_greeting_message_to_order_for_individual_product' ), 10, 3 );

			add_filter( 'woocommerce_add_to_cart_validation', array( $this, 'addf_gift_registry_add_to_cart_validation' ) , 10, 5 );

			add_filter( 'woocommerce_update_cart_validation', array( $this, 'addf_gift_registry_on_action_cart_updated' ) , 10, 4 );
		}

		public function addf_gr_add_mail_files() {
			if ( ! class_exists( 'WC_Email', false ) ) {
				include_once dirname( WC_PLUGIN_FILE ) . '/includes/emails/class-wc-email.php';
			}
			include_once ADDF_GR_DIR . 'emails/create-new-registry/registrant/create-new-mail.php';
			include_once ADDF_GR_DIR . 'emails/create-new-registry/co_registrant/create-new-mail.php';
			include_once ADDF_GR_DIR . 'emails/create-new-registry/admin/create-new-mail-admin.php';
			include_once ADDF_GR_DIR . 'emails/registry-expire/create-new-mail.php';
		}

		public function addf_gr_get_the_current_user_ip() {
			if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
				$addf_gr_curr_ip = sanitize_meta( '', wp_unslash( isset( $_SERVER['HTTP_CLIENT_IP'] ) ? $_SERVER['HTTP_CLIENT_IP'] : '' ), '' );
			} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
				$addf_gr_curr_ip = sanitize_meta( '', wp_unslash( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : '' ), '' );
			} else {
				$addf_gr_curr_ip = sanitize_meta( '', wp_unslash( isset( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR'] : '' ), '' );
			}
			return apply_filters( 'wpb_get_ip', $addf_gr_curr_ip );
		}

		public function addf_gift_registry_files_add_script() {
		
				wp_enqueue_style( 'gift-registry-front-css', plugins_url( '../includes/css/addf-gift-registry-style.css', __FILE__ ), false, '1.0.0' );
				wp_enqueue_script( 'jquery' );
				wp_enqueue_script( 'gift-registry-front-script', plugins_url( '../includes/js/addf-g-r-frontend.js', __FILE__ ), false, '1.0.0', $in_footer = false );
				wp_enqueue_style( 'Font-Awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css', false, '1.0.0' );
				// Enqueue Select2 JS CSS.
				wp_enqueue_style( 'select2', plugins_url( '../includes/css/select2.css', __FILE__ ), true, '1.0.0' );
				wp_enqueue_script( 'select2', plugins_url( '../includes/js/select2.js', __FILE__ ), true, '1.0.1', array( 'jquery' ) );

				//update code[new feature: automatic redirect after add to cart]
				$addf_gr_redirect_after_add_to_cart = get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_enable_redirect_after_add_to_cart' );
				$addf_gr_redirect_page_url          = '';

			if ('yes' == $addf_gr_redirect_after_add_to_cart ) {
				$addf_gr_redirect_after_add_to_cart_option_page = get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_redirect_after_add_to_cart_option' ); 

				if ('checkout_page' == $addf_gr_redirect_after_add_to_cart_option_page) {
					$addf_gr_redirect_page_url = wc_get_checkout_url();
				} elseif ('cart_page' == $addf_gr_redirect_after_add_to_cart_option_page) {
					$addf_gr_redirect_page_url = wc_get_cart_url();
				}

			}
				wp_localize_script(
					'gift-registry-front-script',
					'my_ajax_object',
					array(
						'ajax_url'                  => admin_url( 'admin-ajax.php' ),
						'nonce'                     => wp_create_nonce( 'addify_gift_registry_nonce' ),
						'addf_gr_redirect_page_url' => $addf_gr_redirect_page_url,
					)
				);
		}
		// wp_loaded
		public function addf_gift_registry_wp_loaded_send_mail() {
			// updating expiry registry starts
			$addf_gr_args = array(
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
			$addf_gr_loop = new WP_Query( $addf_gr_args );
			while ( $addf_gr_loop->have_posts() ) :
				$addf_gr_loop->the_post();
				$addf_gr_post              = $addf_gr_loop->get_post();
				$addf_gr_active_visibility = get_post_meta( get_the_ID(), 'gift-registry-event-info-expire-date', true );

				$addf_gr_reg_event_date = get_post_meta( get_the_ID(), 'gift-registry-event-info-date', true );
				$addf_gr_reg_get_date   = get_post_meta( get_the_ID(), 'gift-registry-event-info-expire-date', true );
				$addf_gr_db_date        = strtotime( $addf_gr_reg_event_date );
				$today                  = gmdate( 'Y-m-d' );
				$now_date               = strtotime( $today );
				$datediff               = $addf_gr_db_date - $now_date;
				$time_calculated        = round( $datediff / ( 60 * 60 * 24 ) );
				if ( 1 > $time_calculated ) {
					if ( 'expired' != $addf_gr_reg_get_date ) {
						update_post_meta( get_the_ID(), 'gift-registry-event-info-expire-date', 'expired' );
						WC()->mailer()->emails['addf_gr_expire_registry_wp_email']->trigger( get_the_ID() );
					}
				} else {
					update_post_meta( get_the_ID(), 'gift-registry-event-info-expire-date', 'active' );
				}
			endwhile;
			// updating expiry registry end.
			// for email share.

			$nonce = isset( $_POST['addify_gift_registry_nonce'] ) ? sanitize_text_field( $_POST['addify_gift_registry_nonce'] ) : '';

			if ( ( isset( $_POST['gift-registry-submit'] ) || isset( $_POST['addf_gift_registry_send_email'] ) ) && ! wp_verify_nonce( $nonce, 'addify_gift_registry_nonce' ) ) {

				wp_die( esc_html__( 'Security Violated!', 'addf_giftr' ) );

			}
			if ( isset( $_POST['addf_gift_registry_send_email'] ) ) {

				$addf_send_mail_To          = sanitize_meta( '', wp_unslash( isset( $_POST['addf_gift_registry_share_via_mail_to'] ) ? $_POST['addf_gift_registry_share_via_mail_to'] : '' ), '' );
				$addf_g_r_mail_subject      = sanitize_meta( '', wp_unslash( isset( $_POST['addf_gift_registry_share_via_mail_subject'] ) ? $_POST['addf_gift_registry_share_via_mail_subject'] : '' ), '' );
				$addf_g_r_user_message      = sanitize_meta( '', wp_unslash( isset( $_POST['addf_gift_registry_share_via_mail_message'] ) ? $_POST['addf_gift_registry_share_via_mail_message'] : '' ), '' );
				$addf_gr_share_mail_content = get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_admin_email_sytax' );
				$addf_gr_post_id            = sanitize_meta( '', wp_unslash( isset( $_POST['addf_share_mail_gift_registry_btn_submit_post_value'] ) ? $_POST['addf_share_mail_gift_registry_btn_submit_post_value'] : '' ), '' );
				$addf_gr_user_id            = sanitize_meta( '', wp_unslash( isset( $_POST['addf_share_mail_gift_registry_btn_submit_post_userid'] ) ? $_POST['addf_share_mail_gift_registry_btn_submit_post_userid'] : '' ), '' );
				$addf_gr_user_name          = get_the_author_meta( 'display_name', $addf_gr_user_id );
				$addf_post_permalink        = get_permalink( sanitize_meta( '', wp_unslash( isset( $_POST['addf_share_mail_gift_registry_btn_submit_post_value'] ) ? $_POST['addf_share_mail_gift_registry_btn_submit_post_value'] : '' ), '' ), '' );

				if ( '' === $addf_gr_share_mail_content ) {
					$addf_gr_share_mail_content = '<p>' . esc_html__( ' Dear your friend {registrant_name} shared his gift registry named as {registry_title} with you ', 'addf_giftr' ) . '</p>';
				}
				$addf_gift_registry_mail_process_1   = str_replace( '{registrant_id}', $addf_gr_user_id, $addf_gr_share_mail_content );
				$addf_gift_registry_mail_process_msg = str_replace( '{registrant_msg}', $addf_g_r_user_message, $addf_gift_registry_mail_process_1 );
				$addf_gift_registry_mail_process_2   = str_replace( '{registrant_name}', $addf_gr_user_name, $addf_gift_registry_mail_process_msg );
				$addf_gift_registry_mail_process_2   = str_replace( '{registry_title}', '<a href="' . $addf_post_permalink . '" >' . get_the_title( $addf_gr_post_id ) . '</a>', $addf_gift_registry_mail_process_2 );
				$addf_gift_registry_mail_process     = str_replace( '{registry_url}', $addf_post_permalink, $addf_gift_registry_mail_process_2 );
				$addf_gr_title                       = get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_site_title' );
				$addf_gr_filtered_subject            = str_replace( '{registrant_subject}', $addf_g_r_mail_subject, $addf_gr_title );
				$addf_gr_admin_mail                  = get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_admin_email' );

				WC()->mailer()->emails['addf_gr_share_registry_wp_email']->trigger( $addf_send_mail_To, $addf_gr_post_id, $addf_gr_user_id, $addf_g_r_mail_subject, $addf_g_r_user_message );
			}
				// creating new registry
			if ( isset( $_POST['gift-registry-submit'] ) ) {

				// send email
				$addf_gift_registry_mail_title  = get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_new_reg_site_title' );
				$addf_inform_registrant_op      = get_option( 'wc_settings_tab_gift_registry_notify_registrant_new_reg_send_mail' );
				$addf_inform_admin              = get_option( 'wc_settings_tab_gift_registry_notify_registrant_new_admin_send_mail' );
				$addf_inform_co_registrant_op   = get_option( 'wc_settings_tab_gift_registry_notify_co_registrant_new_reg_send_mail' );
				$addf_gr_content_mail_r_created = get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_admin__new_reg_email_sytax' );
				$addf_gr_admin_mail             = get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_admin_email' );
				$addf_gr_mail_from              = get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_mail_from' );

				if ( '' === $addf_gr_content_mail_r_created ) {
					$addf_gr_content_mail_r_created = '<p>' . esc_html__( 'Dear user {registrant_name}( {registrant_id} )', 'addf_giftr' ) . ' <br>  ' . esc_html__( 'you have created  new Gift Registry named as {registry_title} as on {current_time}', 'addf_giftr' ) . '</p>';
				}
				$addf_gr_new_reg_mail_ch_id    = str_replace( '{registrant_id}', get_current_user_id(), $addf_gr_content_mail_r_created );
				$addf_gr_new_reg_mail_ch_name  = str_replace( '{registrant_name}', sanitize_meta( '', wp_unslash( isset( $_POST['gift-registry-registrant-first-name'] ) ? $_POST['gift-registry-registrant-first-name'] : '' ), '' ) . ' ' . sanitize_meta( '', wp_unslash( isset( $_POST['gift-registry-registrant-last-name'] ) ? $_POST['gift-registry-registrant-last-name'] : '' ), '' ), $addf_gr_new_reg_mail_ch_id );
				$addf_gr_new_reg_mail_ch_title = str_replace( '{registry_title}', sanitize_meta( '', wp_unslash( isset( $_POST['gift-registry-title'] ) ? $_POST['gift-registry-title'] : '' ), '' ), $addf_gr_new_reg_mail_ch_name );
				$addf_gr_new_reg_mail_ch_time  = str_replace( '{current_time}', gmdate( 'Y/m/d H:i:s' ), $addf_gr_new_reg_mail_ch_title );
				$addf_gr_new_reg_mail_ch_msg   = str_replace( '{registrant_msg}', sanitize_meta( '', wp_unslash( isset( $_POST['gift-registry-event-info-message'] ) ? $_POST['gift-registry-event-info-message'] : '' ), '' ), $addf_gr_new_reg_mail_ch_time );
				// More headers
				$gift_registry_registrant_email    = sanitize_meta( '', wp_unslash( isset( $_POST['gift-registry-registrant-email'] ) ? $_POST['gift-registry-registrant-email'] : '' ), '' );
				$gift_registry_co_registrant_email = sanitize_meta( '', wp_unslash( isset( $_POST['gift-registry-co-registrant-email'] ) ? $_POST['gift-registry-co-registrant-email'] : '' ), '' );

				$addf_gift_registry_mail_body = '<p>' . wp_kses_post( $addf_gr_new_reg_mail_ch_msg ) . '</p>';

				$addf_gr_my_post = array(
					'post_title'  => sanitize_text_field( wp_unslash( $_POST['gift-registry-title'] ) ),
					'post_type'   => 'addf_gift_registry',
					'post_status' => 'publish',
				);
				// Insert the post into the database
				$addf_gift_registry_result = wp_insert_post( $addf_gr_my_post );
				if ( $addf_gift_registry_result && ! is_wp_error( $addf_gift_registry_result ) ) {
					WC()->session->set( 'addf_prc_notify_user', true );
					// saving current user
					$addf_insert_current_user = get_current_user_id();
					
					update_post_meta( $addf_gift_registry_result, 'gift-registry-registrant-user_is_wp', $addf_insert_current_user );
					
					// Insert  registrant information
					$addf_insert_first_name = sanitize_meta( '', wp_unslash( $_POST['gift-registry-registrant-first-name'] ), 'post' );
					update_post_meta( $addf_gift_registry_result, 'gift-registry-registrant-first-name', $addf_insert_first_name );
					if ( ! is_user_logged_in() ) {
						$addf_insert_last_name = sanitize_meta( '', wp_unslash( $_POST['gift-registry-registrant-last-name'] ), 'post' );
						update_post_meta( $addf_gift_registry_result, 'gift-registry-registrant-last-name', $addf_insert_last_name );
					}
					if ( isset( $_POST['addf-gift-registry-visibility'] ) ) {
						$addf_gift_registry_visibility_pri = sanitize_meta( '', wp_unslash( $_POST['addf-gift-registry-visibility'] ), 'post' );
						update_post_meta( $addf_gift_registry_result, 'addf-gift-registry-visibility', $addf_gift_registry_visibility_pri );
					}
					if ( isset( $_POST['addf-gift-registry-visibility-private-pass'] ) ) {
						$addf_gift_registry_visibility_pri_pass = sanitize_meta( '', wp_unslash( $_POST['addf-gift-registry-visibility-private-pass'] ), 'post' );
						update_post_meta( $addf_gift_registry_result, 'addf-gift-registry-visibility-private-pass', $addf_gift_registry_visibility_pri_pass );
					}
					$addf_insert_email = sanitize_meta( '', wp_unslash( $_POST['gift-registry-registrant-email'] ), 'post' );
					update_post_meta( $addf_gift_registry_result, 'gift-registry-registrant-email', $addf_insert_email );
					// Insert co registrant information
					if ( isset( $_POST['gift-registry-co-registrant-first'] ) ) {
						$addf_insert_first_name_co = sanitize_meta( '', wp_unslash( $_POST['gift-registry-co-registrant-first'] ), 'post' );
						update_post_meta( $addf_gift_registry_result, 'gift-registry-co-registrant-first', $addf_insert_first_name_co );
					}
					if ( isset( $_POST['gift-registry-co-registrant-last-name'] ) ) {
						$addf_insert_last_name_co = sanitize_meta( '', wp_unslash( $_POST['gift-registry-co-registrant-last-name'] ), 'post' );
						update_post_meta( $addf_gift_registry_result, 'gift-registry-co-registrant-last-name', $addf_insert_last_name_co );
					}
					$addf_insert_email_co = sanitize_meta( '', wp_unslash( $_POST['gift-registry-co-registrant-email'] ), 'post' );
					update_post_meta( $addf_gift_registry_result, 'gift-registry-co-registrant-email', $addf_insert_email_co );
					// Insert evant information
					if ( isset( $_POST['gift-registry-event-info-date'] ) ) {
						$gift_registry_info_date = sanitize_meta( '', wp_unslash( $_POST['gift-registry-event-info-date'] ), 'post' );
						update_post_meta( $addf_gift_registry_result, 'gift-registry-event-info-date', $gift_registry_info_date );
					}
					if ( isset( $_POST['gift-registry-event-info-location'] ) ) {
						$gift_registry_event_location = sanitize_meta( '', wp_unslash( $_POST['gift-registry-event-info-location'] ), 'post' );
						update_post_meta( $addf_gift_registry_result, 'gift-registry-event-info-location', $gift_registry_event_location );
					}
					// guest shipping address
					if ( isset( $_POST['addf_gr_guest_ip_address_reg_created'] ) ) {
						$addf_gr_guest_ip_address_reg_created = sanitize_meta( '', wp_unslash( $_POST['addf_gr_guest_ip_address_reg_created'] ), 'post' );
						update_post_meta( $addf_gift_registry_result, 'addf_gr_guest_ip_address_reg_created', $addf_gr_guest_ip_address_reg_created );
					}
					if ( isset( $_POST['gift_registry_guest_ship_f_name'] ) ) {
						$gift_registry_guest_ship_f_name = sanitize_meta( '', wp_unslash( $_POST['gift_registry_guest_ship_f_name'] ), 'post' );
						update_post_meta( $addf_gift_registry_result, 'gift_registry_guest_ship_f_name', $gift_registry_guest_ship_f_name );
					}
					if ( isset( $_POST['gift_registry_guest_ship_l_name'] ) ) {
						$gift_registry_guest_ship_l_name = sanitize_meta( '', wp_unslash( $_POST['gift_registry_guest_ship_l_name'] ), 'post' );
						update_post_meta( $addf_gift_registry_result, 'gift_registry_guest_ship_l_name', $gift_registry_guest_ship_l_name );
					}
					if ( isset( $_POST['gift_registry_guest_ship_comp_name'] ) ) {
						$gift_registry_guest_ship_comp_name = sanitize_meta( '', wp_unslash( $_POST['gift_registry_guest_ship_comp_name'] ), 'post' );
						update_post_meta( $addf_gift_registry_result, 'gift_registry_guest_ship_comp_name', $gift_registry_guest_ship_comp_name );
					}
					if ( isset( $_POST['gift_registry_guest_shipping_address'] ) ) {
						$gift_registry_guest_shipping_address = sanitize_meta( '', wp_unslash( $_POST['gift_registry_guest_shipping_address'] ), 'post' );
						update_post_meta( $addf_gift_registry_result, 'gift_registry_guest_shipping_address', $gift_registry_guest_shipping_address );
					}
					if ( isset( $_POST['gift_registry_guest_shipping_city'] ) ) {
						$gift_registry_guest_shipping_city = sanitize_meta( '', wp_unslash( $_POST['gift_registry_guest_shipping_city'] ), 'post' );
						update_post_meta( $addf_gift_registry_result, 'gift_registry_guest_shipping_city', $gift_registry_guest_shipping_city );
					}
					if ( isset( $_POST['gift_registry_guest_shipping_post_code'] ) ) {
						$gift_registry_guest_shipping_post_code = sanitize_meta( '', wp_unslash( $_POST['gift_registry_guest_shipping_post_code'] ), 'post' );
						update_post_meta( $addf_gift_registry_result, 'gift_registry_guest_shipping_post_code', $gift_registry_guest_shipping_post_code );
					}
					if ( isset( $_POST['gift_registry_guest_shipping_country'] ) ) {
						$gift_registry_guest_shipping_country = sanitize_meta( '', wp_unslash( $_POST['gift_registry_guest_shipping_country'] ), 'post' );
						update_post_meta( $addf_gift_registry_result, 'gift_registry_guest_shipping_country', $gift_registry_guest_shipping_country );
					}
					if ( isset( $_POST['gift_registry_guest_shipping_state'] ) ) {
						$gift_registry_guest_shipping_state = sanitize_meta( '', wp_unslash( $_POST['gift_registry_guest_shipping_state'] ), 'post' );
						update_post_meta( $addf_gift_registry_result, 'gift_registry_guest_shipping_state', $gift_registry_guest_shipping_state );
					}

					$gift_registry_event_message = sanitize_meta( '', wp_unslash( $_POST['gift-registry-event-info-message'] ), 'post' );
					update_post_meta( $addf_gift_registry_result, 'gift-registry-event-info-message', $gift_registry_event_message );

					if ( 'yes' == $addf_inform_admin ) {
						WC()->mailer()->emails['addf_gr_create_new_wp_email_admin']->trigger( $addf_gift_registry_result );
					}
					// inform registrant
					if ( 'yes' == $addf_inform_registrant_op ) {
						WC()->mailer()->emails['addf_gr_create_new_wp_email_registrant']->trigger( $addf_gift_registry_result );
					}
					// inform co registrant
					if ( 'yes' == $addf_inform_co_registrant_op ) {
						WC()->mailer()->emails['addf_gr_create_new_wp_email_co_registrant']->trigger( $addf_gift_registry_result );
					}
				}
			}
			// quantity update
			if ( isset( $_POST['addf-gift-registry-update-btn'] ) ) {
				$addf_gr_post_id              = sanitize_meta( '', wp_unslash( isset( $_POST['addf_gr_post_id'] ) ? $_POST['addf_gr_post_id'] : '' ), '' );
				$addf_insert_product_quantity = sanitize_meta( '', wp_unslash( isset( $_POST['addf_gr_product_quantity'] ) ? $_POST['addf_gr_product_quantity'] : '' ), '' );
				update_post_meta( $addf_gr_post_id, 'addf_gr_product_quantity', $addf_insert_product_quantity );
			}
		}

					// popups
		public function addf_gift_registry_add_single_product() {
			global $addf_gr_check_current_user_ip;
			$addf_gr_check_current_user_ip = $this->addf_gr_get_the_current_user_ip();
			?>
			<!--  for single product add -->

			<div class="addf-g-r-bg-cover" style="display:none">
				<table  class="addf_gr_popup_styles">
					<tr>
						<td colspan="6" class="align-center">
							<h2 class="addf_gr_popup_styles_heading"><?php echo esc_html__( 'Add a product', 'addf_giftr' ); ?></h2>
						</td>
					</tr>
					<tr>
						<th colspan="5" ><?php echo esc_html__( 'Select a product', 'addf_giftr' ); ?></th>
						<th  class="addf_gr_popup_text_h"><?php echo esc_html__( 'Quantity', 'addf_giftr' ); ?></th>
					</tr>
					<tr>
						<td colspan="5"  class="addf_gr_popup_text_h">
							<select name="addf-gift-registry-add-single-product"  class=" addf-gift-registry-add-single-product-class" >
								<option hidden selected><?php echo esc_html__( 'Select a product', 'addf_giftr' ); ?></option>
							</select>
						</td>
						<td  class="addf_gr_popup_text_h">
							<input type="number" name="" min="1" value="1" class="addf_gr_input_field_class addf-gr-desire-product addf-gift-registry-add-single-product-quantity">
						</td>
						<input type="hidden" class="addf_gr_post_id_to_be_edited" name="" >
					</tr>
					<tr>
						<td colspan="6">
							<div class="addf_gr_select_attr_popup"></div>
						</td>
					</tr>
					<tr>
						<td colspan="6" class="addf-add-new-product-div-article-table">
							<button class="addf-add-product-from-registry-single-product">
								<?php echo esc_html__( 'Add product', 'addf_giftr' ); ?>
							</button>
							<button class="addf-g-r-bg-cover-hide-btn"><?php echo esc_html__( 'Cancel', 'addf_giftr' ); ?></button>
							<br><span class="addf-gr-result-configuration"></span>
						</td>
					</tr>
				</table>
			</div>
			
			<!-- share email cover -->
			<div class="addf_gift_registry_share_email_cover" style="display:none;">
				<div class="addf_gift_registry_share_email_div addf_gr_popup_styles">
					<form action="" method="post" >
						<?php wp_nonce_field( 'addify_gift_registry_nonce', 'addify_gift_registry_nonce' ); ?>
						<div class="align-center">
							<h2 class="addf_gr_popup_styles_heading"><?php echo esc_html__( 'Share via Email', 'addf_giftr' ); ?></h2>
						</div>
						<label  class="addf_gr_popup_text_h" for="addf_gift_registry_share_via_mail_to"><?php echo esc_html__( 'To', 'addf_giftr' ); ?></label>
						<input type="text" name="addf_gift_registry_share_via_mail_to" class="addf_gr_input_field_class " required>
						<label  class="addf_gr_popup_text_h" for="addf_gift_registry_share_via_mail_message"><?php echo esc_html__( 'Message', 'addf_giftr' ); ?></label>
						<textarea name="addf_gift_registry_share_via_mail_message" class="addf_gift_registry_share_via_mail_message" cols="30" rows="10"></textarea>
						<input type="hidden" class="addf_share_mail_gift_registry_btn_submit_post_value" name="addf_share_mail_gift_registry_btn_submit_post_value">
						<input type="hidden" class="addf_share_mail_gift_registry_btn_submit_post_userid" name="addf_share_mail_gift_registry_btn_submit_post_userid">
						<div class="addf_share_mail_gift_registry_btn">
							<button type="submit" class="addf_share_mail_gift_registry_btn_submit" name="addf_gift_registry_send_email"><?php echo esc_html__( 'Send mail', 'addf_giftr' ); ?></button>
							<input type="button" class="addf_share_mail_gift_registry_btn_cancel" value="<?php echo esc_html__( 'Cancel', 'addf_giftr' ); ?>">
						</div>
					</form>
				</div>
			</div>

			<!--  add to registry -->
			<div class="addf_gift_registry_add_to_cart_from_product_div_cover" style="display:none;">
				<div class="addf_gr_popup_styles_add_reg addf_gr_popup_styles">
					<div class="align-center">
						<h2 class="addf_gr_popup_styles_heading"><?php echo esc_html__( 'Add to Gift Registry', 'addf_giftr' ); ?></h2>
					</div>
					<br><span class="addf-gr-result-configuration"></span>
					<table class="">
						<tr>
							<td  class="align-center">
								<label for="addf_gift_registry_add_to_cart_from_product_registry_selected" ><?php echo esc_html__( 'Choose a Gift Registry', 'addf_giftr' ); ?></label>
								<select name="" class="addf_gr_popup_option addf_gr_select_input addf_gift_registry_add_to_cart_from_product_registry_selected" style="width:100%;">
									<option value="" selected hidden> <?php echo esc_html__( 'Select a Registry', 'addf_giftr' ); ?> </option>
									<?php
									$args             = array(
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
									$loop             = new WP_Query( $args );
									$addf_gr_op_count = true;
									while ( $loop->have_posts() ) :
										$loop->the_post();
										$post            = $loop->get_post();
										$addf_gr_user_id = get_post_meta( get_the_ID(), 'gift-registry-registrant-user_is_wp', true );
										if ( '0' == $addf_gr_user_id ) {
											$addf_gr_guest_ip_address_reg_created = get_post_meta( get_the_ID(), 'addf_gr_guest_ip_address_reg_created', true );
											if ( $this->addf_gr_get_the_current_user_ip() != $addf_gr_guest_ip_address_reg_created ) {
												continue;
											}
										}
										if ( 'expired' != get_post_meta( get_the_ID(), 'gift-registry-event-info-expire-date', true ) ) {
											$addf_gr_op_count = false;
											?>
											<option class="addf_gr_popup_option" value="<?php echo esc_attr( get_the_ID() ); ?>">
												<?php echo esc_html__( the_title(), 'addf_giftr' ); ?>
											</option>
											<?php
										}
									endwhile;
									if ( $addf_gr_op_count ) {
										?>
										<option value="" disabled><?php echo esc_html__( 'No gift registry found', 'addf_giftr' ); ?></option>
										<?php
									}
									?>
								</select>
							</td>
						</tr>
						<tr>
							<td colspan="6" class="align-center">
								<button class="addf_gift_registry_add_to_cart_from_product_save_to_directory">
									<?php echo esc_html__( 'Confirm', 'addf_giftr' ); ?>
								</button>
								<button class="addf_gift_registry_add_to_cart_from_product_div_cover_close_btn"><?php echo esc_html__( 'Cancel', 'addf_giftr' ); ?></button>
							</td>
						</tr>
					</table>
				</div>
			</div>
			<?php
		}


		public function addf_gift_registry_add_greeting_message() {
			?>
			<div class="addf-greeting-message-popup" style="display:none">
				<table  class="addf_gr_popup_styles">
					<tr>
						<td colspan="6" class="align-center">
							<h2 class="addf_gr_popup_styles_heading"><?php echo esc_html__( 'Add a message', 'addf_giftr' ); ?></h2>
						</td>
					</tr>
					<tr>
						<td  colspan="1" class="addf_gr_popup_text_h"><?php echo esc_html__( 'Message', 'addf_giftr' ); ?></th>
						<td colspan="5" class="addf_gr_popup_text_h">
							<textarea  name=""  class="addf_gr_input_field_class  addf-gift-registry-add-greeting-message-text-field"></textarea>
						</td>
						<input type="hidden" class="addf_gr_post_id_to_be_edited" name="" >
						<input type="hidden" class="addf_gr_product_id">
					</tr>
					
					<tr>
						<td colspan="6" class="addf-add-new-message-div-article-table">
							<button class="addf-add-greeting-message">
								<?php echo esc_html__( 'Add message', 'addf_giftr' ); ?>
							</button>
							<button class="addf-gr-hide-greeting-message-popup-btn"><?php echo esc_html__( 'Cancel', 'addf_giftr' ); ?></button>
							<br><span class="addf-gr-add-message-result-configuration"></span>
						</td>
					</tr>
				</table>
			</div>

			<?php
		}

		public function addify_gift_registry_add_endpoints() {
			add_rewrite_endpoint( 'gift_registry', EP_ROOT | EP_PAGES );
			flush_rewrite_rules();
		}

		public function addify_gift_registry_new_menu_items( $items ) {

			
			// Remove the logout menu item.
			$logout = $items['customer-logout'];
			unset( $items['customer-logout'] );
			// Insert your custom endpoint.
			$addf_gr_add_menu = get_option( 'wc_settings_tab_gift_registry_gift_registry_menu_for_op' );
			if ( ! is_user_logged_in() ) {
				$addf_allow_guest_crt_reg = get_option( 'wc_settings_tab_gift_registry_notify_registrant_allow_gest' );
				if ( ( 'yes' != $addf_allow_guest_crt_reg ) && ( 'selected' === $addf_gr_add_menu ) ) {
					//changed below commented line by the code on next line
					//unset( $items['gift_registry'] );
					unset( $items['gift-registry'] );
				} else {
					//changed below commented line by the code on next line
					// $items['gift_registry'] = esc_html__( 'Gift Registry', 'addf_giftr' );
					$items['gift-registry'] = esc_html__( 'Gift Registry', 'addf_giftr' );
				}
			} else {
				$addf_gr_add_menu = get_option( 'wc_settings_tab_gift_registry_gift_registry_menu_for_op' );
				if ( 'selected' === $addf_gr_add_menu ) {
					$user                    = wp_get_current_user();
					$addf_gr_user_curr_array = $user->roles;
					$addf_gr_user_curr_user  = $addf_gr_user_curr_array[0];
					$addf_gr_allowed_ts_menu = get_option( 'wc_settings_tab_gift_registry_addf_gr_allow_users_create_registry' );
					if ( empty( $addf_gr_allowed_ts_menu ) ) {
						//changed below commented line by the code on next line
						// $items['gift_registry'] = esc_html__( 'Gift Registry', 'addf_giftr' );
						$items['gift-registry'] = esc_html__( 'Gift Registry', 'addf_giftr' );
					} elseif ( in_array( $addf_gr_user_curr_user, (array) $addf_gr_allowed_ts_menu ) ) {
							//changed below commented line by the code on next line
							// $items['gift_registry'] = esc_html__( 'Gift Registry', 'addf_giftr' );
							$items['gift-registry'] = esc_html__( 'Gift Registry', 'addf_giftr' );
					} else {
						//changed below commented line by the code on next line
						// unset( $items['gift_registry'] );
						unset( $items['gift-registry'] );
					}
				} else {
					//changed below commented line by the code on next line
					// $items['gift_registry'] = esc_html__( 'Gift Registry', 'addf_giftr' );
					$items['gift-registry'] = esc_html__( 'Gift Registry', 'addf_giftr' );
				}
			}
			// Insert back the logout item.
			$items['customer-logout'] = $logout;
			return $items;
		}

		public function addify_gift_registry_endpoint_content() {
			include_once ADDF_GR_DIR . 'front/templates/gift-registry-table.php';
		}

		public function addify_gift_registry_add_query_vars( $vars ) {
			$vars[] = 'gift_registry';
			return $vars;
		}

		public function addify_gift_registry_endpoint_title( $title ) {
			global $wp_query;
			$is_endpoint = isset( $wp_query->query_vars['gift_registry'] );
			if ( $is_endpoint && ! is_admin() && is_main_query() && in_the_loop() && is_account_page() ) {
				// New page title.
				$title = esc_html__( 'Gift Registry', 'addf_giftr' );
				remove_filter( 'the_title', array( $this, 'endpoint_title' ) );
			}
			return $title;
		}
			// add new btn with cart at all places
		public function addf_gift_registry_add_to_cart_button_shop() {
			$addf_gr_rest_user_op = get_option( 'wc_settings_tab_gift_registry_addf_gr_restrict_add_to_cart' );
			?>
			<input type="hidden" value="<?php echo esc_attr( $addf_gr_rest_user_op ); ?>" class="addf_gr_rest_user_op">
			<input type="hidden" class="addf_gift_registry_seesion_add_To_cart" value="<?php echo esc_attr( WC()->session->get( 'addf_gift_registry_seesion_add_To_cart' ) ); ?>">
			<?php
			global $product;
			if ( ( '' == $product->is_on_backorder() ) && ( '' == $product->is_in_stock() ) || ( '' == $product->get_price() ) ) {
				return;
			}
			$addf_gr_add_menu         = get_option( 'wc_settings_tab_gift_registry_gift_registry_menu_for_op' );
			$addf_allow_guest_crt_reg = get_option( 'wc_settings_tab_gift_registry_notify_registrant_allow_gest' );
			if ( ! is_user_logged_in() ) {
				if ( ( 'yes' != $addf_allow_guest_crt_reg ) ) {
					return;
				}
			} elseif ( is_user_logged_in() ) {
				$user                    = wp_get_current_user();
				$addf_gr_user_curr_array = $user->roles;
				$addf_gr_user_curr_user  = current( $addf_gr_user_curr_array );
				$addf_gr_allowed_ts_menu = get_option( 'wc_settings_tab_gift_registry_addf_gr_allow_users_create_registry' );
				if ( is_array( $addf_gr_allowed_ts_menu ) ) {
					if ( count( $addf_gr_allowed_ts_menu ) > 0 ) {
						if ( ! in_array( $addf_gr_user_curr_user, (array) $addf_gr_allowed_ts_menu ) ) {
							return;
						}
					}
				}
			}
			$addf_gr_rest_user_op = get_option( 'wc_settings_tab_gift_registry_addf_gr_restrict_add_to_cart' );
			?>
			<input type="hidden" value="<?php echo esc_attr( $addf_gr_rest_user_op ); ?>" class="addf_gr_rest_user_op">
			<input type="hidden" class="addf_gift_registry_seesion_add_To_cart" value="<?php echo esc_attr( WC()->session->get( 'addf_gift_registry_seesion_add_To_cart' ) ); ?>">
			<?php
			if ( 'variable' != $product->get_type() ) {
				$addf_gr_add_menu = get_option( 'wc_settings_tab_gift_registry_gift_registry_menu_for_op' );
				$addf_gr_btn_txt  = get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_btn_text' );
				if ( '' === $addf_gr_btn_txt ) {
					$addf_gr_btn_txt = 'Add to Gift Registry';
				}
				$addf_gr_btn_op = get_option( 'wc_settings_tab_gift_registry_gift_registry_btn_option' );
				?>
				<a    class="
				btn_cur_point 
				<?php
				if ( 'btn' == $addf_gr_btn_op ) {
					echo 'button'; }
				if ( 0 == $this->addf_gr_all_gr_current_user() ) {
					echo ' addf_gr_empty_reg_btn ';
				} else {
					echo ' addf_gift_registry_add_to_cart_from_product ';
				}
				?>
					" data-id="<?php echo esc_attr(get_the_ID()); ?>">
					<?php echo esc_html__( $addf_gr_btn_txt, 'addf_giftr' ); ?>
				</a>
				<?php
			}
		}

		public function addf_gr_all_gr_current_user() {
			$addf_gr_args          = array(
				'post_type'   => 'addf_gift_registry',
				'post_status' => 'publish',
				'fields'      => 'ids',
			);
			$addf_gr_loop          = (array) ( get_posts( $addf_gr_args ) );
			$addf_gr_ttl_reg_count = 0;
			foreach ( $addf_gr_loop as $value ) {
				$addf_gr_get_reg_user_id = get_post_meta( $value, 'gift-registry-registrant-user_is_wp', true );
				if ( 0 == $addf_gr_get_reg_user_id ) {
					if ( get_current_user_id() != $addf_gr_get_reg_user_id ) {
						continue;
					}
					$addf_gr_guest_ip_address_reg_created = get_post_meta( $value, 'addf_gr_guest_ip_address_reg_created', true );
					if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
						$addf_gr_curr_ip = sanitize_meta( '', wp_unslash( isset( $_SERVER['HTTP_CLIENT_IP'] ) ? $_SERVER['HTTP_CLIENT_IP'] : '' ), '' );
					} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
						$addf_gr_curr_ip = sanitize_meta( '', wp_unslash( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : '' ), '' );
					} else {
						$addf_gr_curr_ip = sanitize_meta( '', wp_unslash( isset( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR'] : '' ), '' );
					}
					if ( $addf_gr_curr_ip != $addf_gr_guest_ip_address_reg_created ) {
						continue;
					}
				} elseif ( get_current_user_id() != $addf_gr_get_reg_user_id ) {
						continue;
				}
				++$addf_gr_ttl_reg_count;
			}
			return $addf_gr_ttl_reg_count;
		}
			// add new btn with cart at single product page
		public function addf_gift_registry_add_to_cart_single_product_page() {
			$addf_gr_rest_user_op = get_option( 'wc_settings_tab_gift_registry_addf_gr_restrict_add_to_cart' );
			?>
			<input type="hidden" value="<?php echo esc_attr( $addf_gr_rest_user_op ); ?>" class="addf_gr_rest_user_op">
			<input type="hidden" class="addf_gift_registry_seesion_add_To_cart" value="<?php echo esc_attr( WC()->session->get( 'addf_gift_registry_seesion_add_To_cart' ) ); ?>">
			<?php
			$addf_gr_add_menu         = get_option( 'wc_settings_tab_gift_registry_gift_registry_menu_for_op' );
			$addf_allow_guest_crt_reg = get_option( 'wc_settings_tab_gift_registry_notify_registrant_allow_gest' );
			if ( ! is_user_logged_in() ) {
				if ( ( 'yes' != $addf_allow_guest_crt_reg ) ) {
					return;
				}
			} elseif ( is_user_logged_in() ) {
				$user                    = wp_get_current_user();
				$addf_gr_user_curr_array = $user->roles;
				$addf_gr_user_curr_user  = current( $addf_gr_user_curr_array );
				$addf_gr_allowed_ts_menu = get_option( 'wc_settings_tab_gift_registry_addf_gr_allow_users_create_registry' );
				if ( is_array( $addf_gr_allowed_ts_menu ) ) {
					if ( count( $addf_gr_allowed_ts_menu ) > 0 ) {
						if ( ! in_array( $addf_gr_user_curr_user, (array) $addf_gr_allowed_ts_menu ) ) {
							return;
						}
					}
				}
			}
			global $product;
			?>
			<input type="hidden" class="addf_gr_attr_for_variation">
			<?php
			if ( 'variable' === $product->get_type() ) {
				add_action( 'woocommerce_single_variation', array( $this, 'addf_gift_regsitry_add_to_regsitry_from_product_for_var' ), 20 );
			} else {
				add_action( 'woocommerce_simple_add_to_cart', array( $this, 'addf_gift_regsitry_add_to_regsitry_from_product' ), 30 );
			}
		}

		public function addf_gift_regsitry_add_to_regsitry_from_product_for_var() {
			$addf_gr_btn_txt = get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_btn_text' );
			if ( '' == $addf_gr_btn_txt ) {
				$addf_gr_btn_txt = 'Add to Gift Registry';
			}
			$addf_gr_btn_op = get_option( 'wc_settings_tab_gift_registry_gift_registry_btn_option' );
			?>
			<br>
			<a class="button addf_gift_registry_cart_frm_btn_hide"><?php echo esc_html__( 'Select Variation for Gift Registry', 'addf_giftr' ); ?></a>
			<a  style="display:none;"  class="
			<?php
			if ( 'btn' == $addf_gr_btn_op ) {
				echo ' button ';
			}
			if ( 0 == $this->addf_gr_all_gr_current_user() ) {
				echo ' addf_gr_empty_reg_btn ';
			} else {
				echo ' addf_gift_registry_add_to_cart_from_product  ';
			}
			?>
			btn_cur_point addf_gift_registry_cart_frm_btn_show " data-id="" data-attr="">
			<?php echo esc_html__( $addf_gr_btn_txt, 'addf_giftr' ); ?>
		</a>
			<?php
		}

		public function addf_gift_regsitry_add_to_regsitry_from_product() {
			global $product;
			if ( ( '' == $product->is_on_backorder() ) && ( '' == $product->is_in_stock() ) || ( '' == $product->get_price() ) ) {
				return;
			}
			$addf_gr_btn_txt = get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_btn_text' );
			$addf_gr_btn_op  = get_option( 'wc_settings_tab_gift_registry_gift_registry_btn_option' );
			if ( '' == $addf_gr_btn_txt ) {
				$addf_gr_btn_txt = 'Add to Gift Registry';
			}
			?>
		<a   class="
			<?php
			if ( 'btn' === $addf_gr_btn_op ) {
				echo 'button';
			}
			if ( 0 == $this->addf_gr_all_gr_current_user() ) {
				echo ' addf_gr_empty_reg_btn ';
			} else {
				echo ' addf_gift_registry_add_to_cart_from_product ';
			}
			?>
		btn_cur_point  " data-id="<?php echo esc_attr(get_the_ID()); ?>">
			<?php echo esc_html__( $addf_gr_btn_txt, 'addf_giftr' ); ?>
	</a>
			<?php
		}

		public static function addf_gr_portfolio_page_template( $template ) {
			$addf_gr_enable_gift_registry_search = get_option('wc_settings_tab_gift_registry_notify_gift_registry_enable_gift_registry_search');
			$addf_gr_gift_registry_search_page   = get_option('wc_settings_tab_gift_registry_search_visibility_page_option');
			if ( is_embed() ) {
				return $template;
			}
			global $post;
			if ( is_singular( 'addf_gift_registry' ) ) {
				get_header();
				?>
		<div class="clear"></div>
	</header> 
	<div id="content" class="site-content">
				<?php
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
				?>
		
		<div class="container">
			<div class="content-left-wrap col-md-9">
			<!-- gift registry search functionality[new added feature] -->
			<?php if ('yes' == $addf_gr_enable_gift_registry_search && ( 'single_registry_page' == $addf_gr_gift_registry_search_page || 'both' == $addf_gr_gift_registry_search_page ) && is_user_logged_in()) { ?>
			<div id="addf-gr-registry-search-container" >
					<select id="addf-gr-registry-search-select" name="addf_gr_registry_search_select" > 
					</select>			
				</div>
				<?php
				}
				?>
				<div id="primary" class="content-area">	
					<main id="main" class="site-main" role="main">
						<?php require_once ADDF_GR_DIR . 'front/templates/addf-gift-registry-single-post.php'; ?>
					</main>
				</div>
			</div>
			<div class="sidebar-wrap col-md-3 content-left-wrap">
				<?php get_sidebar(); ?>
			</div>
		</div>
				<?php
				get_footer();
				exit;
			} elseif ( is_post_type_archive( 'addf_gift_registry' ) ) {
				get_header();
				?>
		<div class="clear"></div>
	</header> 
	<div class="storefront-breadcrumb">
		<nav class="woocommerce-breadcrumb">
			<a href="<?php echo esc_url( get_home_url() ); ?>">
				<?php echo esc_html__( 'Home', 'addf_giftr' ); ?>
			</a>
			<span class="breadcrumb-separator"> / </span>
				<?php echo esc_html__( 'Gift Registry', 'addf_giftr' ); ?>
		</nav>
	</div>
	<div id="content" class="site-content">
		<div class="container">
			<div class="content-left-wrap col-md-9">
				<div id="primary" class="content-area">
					<main id="main" class="site-main" role="main">
						<?php require_once ADDF_GR_DIR . 'front/templates/gift-registry-table.php'; ?>
					</main>
				</div>
			</div>
			<div class="sidebar-wrap col-md-3 content-left-wrap">
				<?php get_sidebar(); ?>
			</div>
		</div>
	</div>
				<?php
				get_footer();
				exit;
			}
			return $template;
		}

		public function addf_gift_registry_remove_shipping_cb( $fields ) {
			$addf_gr_post_id              = WC()->session->get( 'addf_gift_registry_seesion_add_To_cart_gr_id' );
			$addf_gift_registry_meta_user = get_post_meta( $addf_gr_post_id, 'gift-registry-registrant-user_is_wp', true );
			$addf_gr_session              = WC()->session->get( 'addf_gift_registry_seesion_add_To_cart' );
			// shipping address
			// if ( '0' == $addf_gift_registry_meta_user ) {
				$shipping_first_name = get_post_meta( $addf_gr_post_id, 'gift_registry_guest_ship_f_name', true );
				$shipping_last_name  = get_post_meta( $addf_gr_post_id, 'gift_registry_guest_ship_l_name', true );
				$shipping_company    = get_post_meta( $addf_gr_post_id, 'gift_registry_guest_ship_comp_name', true );
				$shipping_address_1  = get_post_meta( $addf_gr_post_id, 'gift_registry_guest_shipping_address', true );
				$shipping_address_2  = ' ';
				$shipping_city       = get_post_meta( $addf_gr_post_id, 'gift_registry_guest_shipping_city', true );
				$shipping_postcode   = get_post_meta( $addf_gr_post_id, 'gift_registry_guest_shipping_post_code', true );
				$shipping_country    = get_post_meta( $addf_gr_post_id, 'gift_registry_guest_shipping_country', true );
				$shipping_state      = get_post_meta( $addf_gr_post_id, 'gift_registry_guest_shipping_state', true );

			if ($shipping_country) {
				$country_obj   = new WC_Countries();
				$addf_gr_state = $country_obj->get_states( $shipping_country );

				foreach ($addf_gr_state as $key=>$value) {
					if ($shipping_state == $value ) {
						$shipping_state = $key;
						break;
					}
				}

			}
			if ( WC()->session->get( 'addf_gift_registry_seesion_add_To_cart' ) ) {

				add_filter( 'gettext', array( $this, 'addf_gr_change_text_ship_to_another_addr' ), 20, 3 );
				// first name
				$_POST['shipping_first_name'] = $shipping_first_name;
				// last name
				$_POST['shipping_last_name'] = $shipping_last_name;
				// company name
				$_POST['shipping_company'] = $shipping_company;
				// shipping_address_1
				$_POST['shipping_address_1'] = $shipping_address_1;
				// shipping_address_2
				$_POST['shipping_address_2'] = $shipping_address_2;
				// shipping_city
				$_POST['shipping_city'] = $shipping_city;
				// shipping_postcode
				$_POST['shipping_postcode'] = $shipping_postcode;
				// shipping_country
				$_POST['shipping_country'] = $shipping_country;
				// shipping_state
				$_POST['shipping_state'] = $shipping_state;

			}
			return $fields;
		}

		public function addf_gr_change_text_ship_to_another_addr( $translated_text, $text, $domain ) {
			switch ( $translated_text ) {
				case 'Ship to a different address?':
					$translated_text = esc_html__( 'Ship to Gift Registry owner', 'addf_giftr' );
					break;
			}
			return $translated_text;
		}

		public function addf_gift_registry_info_shipping_to_others_cb() {
			if ( WC()->session->get( 'addf_gift_registry_seesion_add_To_cart' ) == true ) {
				$addf_gr_post_id              = WC()->session->get( 'addf_gift_registry_seesion_add_To_cart_gr_id' );
				$addf_gift_registry_meta_user = get_post_meta( $addf_gr_post_id, 'gift-registry-registrant-user_is_wp', true );
				?>
		<p class="woocommerce-info"><?php echo esc_html__( 'Shipping to Gift Registry "' . get_the_title( $addf_gr_post_id ) . '" owner shipping address', 'addf_giftr' ); ?></p>
				<?php
			}
		}

		public function addf_gift_registry_info_shipping_to_others__cart_cb() {
			if ( WC()->session->get( 'addf_gift_registry_seesion_add_To_cart' ) == true ) {
				$addf_gr_post_id              = WC()->session->get( 'addf_gift_registry_seesion_add_To_cart_gr_id' );
				$addf_gift_registry_meta_user = get_post_meta( $addf_gr_post_id, 'gift-registry-registrant-user_is_wp', true );
				?>
		<p class="woocommerce-info"><?php echo esc_html__( 'Products from "' . get_the_title( $addf_gr_post_id ) . '" Registry ', 'addf_giftr' ); ?></p>
				<?php
			}
		}

		public function addf_gift_registry_after_placing_order( $order ) {
			$order_id = $order->get_id();
			if ( ! $order_id ) {
				return;
			}
			$addf_gr_post_id              = WC()->session->get( 'addf_gift_registry_seesion_add_To_cart_gr_id' );
			$addf_gift_registry_meta_user = get_post_meta( $addf_gr_post_id, 'gift-registry-registrant-user_is_wp', true );
			$addf_gr_session              = WC()->session->get( 'addf_gift_registry_seesion_add_To_cart' );
			// shipping address
			// if ( '0' == $addf_gift_registry_meta_user ) {
			$shipping_first_name = get_post_meta( $addf_gr_post_id, 'gift_registry_guest_ship_f_name', true );
			$shipping_last_name  = get_post_meta( $addf_gr_post_id, 'gift_registry_guest_ship_l_name', true );
			$shipping_company    = get_post_meta( $addf_gr_post_id, 'gift_registry_guest_ship_comp_name', true );
			$shipping_address_1  = get_post_meta( $addf_gr_post_id, 'gift_registry_guest_shipping_address', true );
			$shipping_address_2  = ' ';
			$shipping_city       = get_post_meta( $addf_gr_post_id, 'gift_registry_guest_shipping_city', true );
			$shipping_postcode   = get_post_meta( $addf_gr_post_id, 'gift_registry_guest_shipping_post_code', true );
			$shipping_country    = get_post_meta( $addf_gr_post_id, 'gift_registry_guest_shipping_country', true );
			$shipping_state      = get_post_meta( $addf_gr_post_id, 'gift_registry_guest_shipping_state', true );
			// } else {
			//  $shipping_first_name = get_user_meta( $addf_gift_registry_meta_user, 'shipping_first_name', true );
			//  $shipping_last_name  = get_user_meta( $addf_gift_registry_meta_user, 'shipping_last_name', true );
			//  $shipping_company    = get_user_meta( $addf_gift_registry_meta_user, 'shipping_company', true );
			//  $shipping_address_1  = get_user_meta( $addf_gift_registry_meta_user, 'shipping_address_1', true );
			//  $shipping_address_2  = get_user_meta( $addf_gift_registry_meta_user, 'shipping_address_2', true );
			//  if ( '' == $shipping_address_2 ) {
			//      $shipping_address_2 = ' ';
			//  }
			//  $shipping_city     = get_user_meta( $addf_gift_registry_meta_user, 'shipping_city', true );
			//  $shipping_postcode = get_user_meta( $addf_gift_registry_meta_user, 'shipping_postcode', true );
			//  $shipping_country  = get_user_meta( $addf_gift_registry_meta_user, 'shipping_country', true );
			//  $shipping_state    = get_user_meta( $addf_gift_registry_meta_user, 'shipping_state', true );
			// }

			if ( WC()->session->get( 'addf_gift_registry_seesion_add_To_cart' ) ) {
				update_post_meta( $order_id, '_shipping_first_name', $shipping_first_name );
				update_post_meta( $order_id, '_shipping_last_name', $shipping_last_name );
				update_post_meta( $order_id, '_shipping_company', $shipping_company );
				update_post_meta( $order_id, '_shipping_address_1', $shipping_address_1 );
				update_post_meta( $order_id, '_shipping_address_2', $shipping_address_2 );
				update_post_meta( $order_id, '_shipping_city', $shipping_city );
				update_post_meta( $order_id, '_shipping_postcode', $shipping_postcode );
				update_post_meta( $order_id, '_shipping_country', $shipping_country );
				update_post_meta( $order_id, '_shipping_state', $shipping_state );

				$addf_gr_infrm_admin         = get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_admin' );
				$addf_gr_infrm_registrant    = get_option( 'wc_settings_tab_gift_registry_notify_r_owner' );
				$addf_gr_infrm_co_registrant = get_option( 'wc_settings_tab_gift_registry_notify_registrant_friend' );

				$addf_gr_admin_mail = get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_admin_email' );
				$addf_gr_mail_from  = get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_mail_from' );
				$addf_gr_mail_title = get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_p_buy_site_title' );
				// filtering purchase email content
				$addf_gr_mail_content = get_option( 'email_addf_gift_registry_email_syntax' );
				if ( '' == $addf_gr_mail_content ) {
					$addf_gr_mail_content = '<p> <strong>' . esc_html__( 'Congratulations ,Dear user {registrant_name}( {registrant_id} )', 'addf_giftr' ) . '</strong> <br>  ' . esc_html__( 'your friend have  purchased {product_name}  for you as on {time_of_purchase}', 'addf_giftr' ) . '</p>';
				}
				$addf_gr_replace_id   = str_replace( '{registrant_id}', $addf_gift_registry_meta_user, $addf_gr_mail_content );
				$addf_gr_user_name    = get_post_meta( $addf_gr_post_id, 'gift-registry-registrant-first-name', true ) . '&nbsp;' . get_post_meta( $addf_gr_post_id, 'gift-registry-registrant-last-name', true );
				$addf_gr_replace_name = str_replace( '{registrant_name}', $addf_gr_user_name, $addf_gr_replace_id );

				$var_for_singlr_product_rec = (array) get_post_meta( $addf_gr_post_id, 'addf_gr_product_quantity_recieved', true );
				$addf_gift_registry_product = (array) get_post_meta( $addf_gr_post_id, 'addf_gift_registry_product', true );
				$order                      = new WC_Order( $order_id );
				$items                      = $order->get_items();
				$product_name               = ' ';
				foreach ( $items as $item ) {
					$product_name .= '(' . $item['qty'] . ')' . $item['name'];
					$product_id    = $item['product_id'];
					$variation_id  = $item['variation_id'];
					$product       = wc_get_product( $product_id );
					if ( $product->is_type( 'variable' ) ) {
						$variation_id = $item['variation_id'];
						$var_product  = wc_get_product( $variation_id );
					} else {
						$variation_id = 0;
						$var_product  = array();
					}
					$addf_gr_order_prod = array();
					foreach ($item->get_meta_data() as $metaData) {
						$attribute  = $metaData->get_data();
						$main_value = $attribute['value'];
						$key        = $attribute['key'];

						if (is_object($var_product) && $var_product instanceof WC_Product) {
							foreach ($var_product->get_attributes() as $key_1 => $value) {
								if ('' == $value) {
									if ($key_1 == $key) {
										$addf_gr_order_prod[ $key_1 ] = $main_value;
									}
								}
							}
						}
					}


					$addf_gr_unique_key         = $this->generate_addf_gr_product_id( $product_id, $variation_id, $addf_gr_order_prod, $addf_gr_post_id );
					$var_for_singlr_product_rec = (array) get_post_meta( $addf_gr_post_id, 'addf_gr_product_quantity_recieved', true );
					if ( ! array_key_exists( $addf_gr_unique_key, $var_for_singlr_product_rec ) ) {
						$var_for_singlr_product_rec[ $addf_gr_unique_key ] = 0;
					}
					$var_for_singlr_product_rec[ $addf_gr_unique_key ] = $var_for_singlr_product_rec[ $addf_gr_unique_key ] + $item['qty'];
					update_post_meta( $addf_gr_post_id, 'addf_gr_product_quantity_recieved', $var_for_singlr_product_rec );
				}
				$addf_gr_replace_p_name = str_replace( '{product_name}', $product_name, $addf_gr_replace_name );
				$addf_gr_replace_time   = str_replace( '{time_of_purchase}', gmdate( 'Y/m/d H:i:s' ), $addf_gr_replace_p_name );
				// More headers

				$addf_gr_mail_content_filtered = '<p>' . wp_kses_post( $addf_gr_replace_time ) . '</p>';
				if ( 'yes' == $addf_gr_infrm_admin ) {
					WC()->mailer()->emails['addf_gr_product_purchase_wp_email_admin']->trigger( $addf_gr_post_id, $product_name );
				}
				if ( 'yes' == $addf_gr_infrm_registrant ) {
					WC()->mailer()->emails['addf_gr_product_purchase_wp_email_registrant']->trigger( $addf_gr_post_id, $product_name );
				}
				if ( 'yes' == $addf_gr_infrm_co_registrant ) {
					WC()->mailer()->emails['addf_gr_product_purchase_wp_email_co_registrant']->trigger( $addf_gr_post_id, $product_name );
				}
			} else {
				?>
		<input type="hidden" id="addf_gift_registry_pb_side_for_public_users" value="0">
				<?php
			}
			remove_filter( 'woocommerce_after_checkout_billing_form', array( $this, 'addf_gift_registry_info_shipping_to_others_cb' ) );
			remove_filter( 'woocommerce_shipping_fields', array( $this, 'addf_gift_registry_remove_shipping_cb' ) );
			WC()->session->set( 'addf_gift_registry_seesion_add_To_cart', false );
			WC()->session->set( 'addf_gift_registry_seesion_add_To_cart_gr_id', '' );
		}

		public function addf_gift_registry_cart_is_updated_ajax() {
			if ( WC()->session->get( 'addf_gift_registry_seesion_add_To_cart' ) ) {
				if ( WC()->cart->is_empty() ) {
					if ( is_cart() || is_shop() || is_singular( 'product' ) || is_product() || is_product_category() || ( is_single() ) || is_product_tag() ) {
						WC()->session->set( 'addf_gift_registry_seesion_add_To_cart', false );
						remove_filter( 'woocommerce_shipping_fields', array( $this, 'addf_gift_registry_remove_shipping_cb' ) );
						remove_filter( 'woocommerce_after_checkout_billing_form', array( $this, 'addf_gift_registry_info_shipping_to_others_cb' ) );
					}
				}
			}
		}

			// restrict add to cart
		public function addf_gr_restrict_add_to_cart_info() {

				$page = current(
					get_posts(
						array(
							'post_type'   => 'page',
							'title'       => 'Gift Registry',
							'post_status' => 'all',
							'numberposts' => -1,

						)
					)
				);
			if ( $page ) {
				$addf_gr_empty_gr_text      = get_option( 'addf_gr_empty_gr_text' );
				$addf_gr_empty_gr_text_fltr = str_replace( '{gift_registry_page}', get_permalink( $page->ID ), $addf_gr_empty_gr_text );
				?>
		<p class="woocommerce-info addf_gr_empty_reg_msg" style="display:none;"><?php echo wp_kses_post( $addf_gr_empty_gr_text_fltr ); ?></p>
				<?php
			}
			?>
	<p class="woocommerce-info addf_gr_restrict_add_to_cart_info" style="display:none;"><?php echo esc_html__( 'Cart contain Gift Registry Items cannot add products', 'addf_giftr' ); ?></p>
	<div class="addf_gr_product_success_added_to_cart"></div>
			<?php
		}

		// remove page from menu
		public function addf_gift_registry_add_to_menu( $items, $args ) {
			$addf_gr_add_menu = get_option( 'wc_settings_tab_gift_registry_gift_registry_menu_for_op' );
			if ( 'primary' === $args->theme_location ) {
				if ( is_user_logged_in() ) {
					if ( 'selected' === $addf_gr_add_menu ) {
						$user                    = wp_get_current_user();
						$addf_gr_user_curr_array = $user->roles;
						$addf_gr_user_curr_user  = $addf_gr_user_curr_array[0];
						$addf_gr_allowed_ts_menu = get_option( 'wc_settings_tab_gift_registry_addf_gr_allow_users_create_registry' );
						if ( ! empty( $addf_gr_allowed_ts_menu ) ) {
							if ( ! in_array( $addf_gr_user_curr_user, (array) $addf_gr_allowed_ts_menu ) ) {
								$items = str_replace( '<a href="' . get_site_url() . '/gift-registry/" aria-current="page">Gift Registry</a>', '', $items );
								$items = str_replace( '<a href="' . get_site_url() . '/gift-registry/">Gift Registry</a>', '', $items );
							}
						}
					}
				} else {
					$addf_allow_guest_crt_reg = get_option( 'wc_settings_tab_gift_registry_notify_registrant_allow_gest' );
					if ( ( 'yes' != $addf_allow_guest_crt_reg ) && ( 'selected' === $addf_gr_add_menu ) ) {
						$items = str_replace( '<a href="' . get_site_url() . '/gift-registry/" aria-current="page">Gift Registry</a>', '', $items );
						$items = str_replace( '<a href="' . get_site_url() . '/gift-registry/">Gift Registry</a>', '', $items );
					}
				}
			}

			return $items;
		}

		public function generate_addf_gr_product_id( $product_id, $variation_id = 0, $variation = array(), $quote_item_data = array() ) {
			$id_parts = array( $product_id );
			if ( $variation_id && 0 !== $variation_id ) {
				$id_parts[] = $variation_id;
			}
			if ( is_array( $variation ) && ! empty( $variation ) ) {
				$variation_key = '';
				foreach ( $variation as $key => $value ) {
					$variation_key .= trim( $key ) . trim( $value );
				}
				$id_parts[] = $variation_key;
			}
			if ( is_array( $quote_item_data ) && ! empty( $quote_item_data ) ) {
				$quote_item_data_key = '';
				foreach ( $quote_item_data as $key => $value ) {
					if ( is_array( $value ) || is_object( $value ) ) {
						$value = http_build_query( $value );
					}
					$quote_item_data_key .= trim( $key ) . trim( $value );
				}
				$id_parts[] = $quote_item_data_key;
			}
			return apply_filters( 'addf_gr_prime_product_id', md5( implode( '_', $id_parts ) ), $product_id, $variation_id, $variation, $quote_item_data );
		}
		public function af_gr__HOPS_Compatibility() {

			if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
			}
		}

		public function addf_gift_registry_display_greeting_message_in_cart( $item_data, $cart_item ) {

			foreach ($cart_item as $key=>$item) {
				if ('addf_greeting_message' == $key) {
					if ('' != $item) {
						echo '<br>Greeting Message : ' . esc_attr($item);
						break;
					}
				}
			}
			
			return $item_data;
		}


		public function addf_gift_registry_add_greeting_message_to_order_for_individual_product( $item_id, $values, $order_id ) {

			if (isset($values['addf_greeting_message'])) {
				$order_data = array( 'Greeting Message' => $values['addf_greeting_message'] );
	
				if ($item_id) {
					wc_add_order_item_meta($item_id, 'addf_greeting_message' , $order_data);
				}
			}
		}

		public function addf_gift_registry_show_greeting_message_to_order_for_individual_product( $item_id, $values, $order ) {

			$addf_gr_greeting_message = (array) wc_get_order_item_meta( $item_id, 'addf_greeting_message', true );

			foreach ($addf_gr_greeting_message as $key=>$value) {
				if ('' == $value) {
					continue;
				}
				if ('Greeting Message' == $key ) {
					if ('' != $value) {
						echo '<br><b>' . esc_attr($key) . '</b> : ' . esc_attr($value);
					}
				}
				
			}
		}

		public function addf_gift_registry_add_to_cart_validation( $passed_validation, $product_id, $quantity, $variation_id = '', $variation = '' ) {

			$addf_gr_rest_user_op      = get_option( 'wc_settings_tab_gift_registry_addf_gr_restrict_add_to_cart' );
			$add_to_cart_with_registry = WC()->session->get( 'addf_gift_registry_seesion_add_To_cart' );


			if ( ( 'rest_user' == $addf_gr_rest_user_op ) && ( '1' == $add_to_cart_with_registry ) ) {
				wc_add_notice('Cart contain Gift Registry Items cannot add product.', 'error');
				return false;
			}

			$product = wc_get_product($product_id);
			$post_id = WC()->session->get( 'addf_gift_registry_seesion_add_To_cart_gr_id');

			$key = '';

			if ( $post_id ) {
				$addf_gift_registry_product = (array) get_post_meta( $post_id, 'addf_gift_registry_product', true );
				$var_for_singlr_product     = (array) get_post_meta( $post_id, 'addf_gr_product_quantity', true );
				$var_for_singlr_product_rec = (array) get_post_meta( $post_id, 'addf_gr_product_quantity_recieved', true );
				

				foreach ($addf_gift_registry_product as $product_key => $value) {
					if ( '' != $value && ( ( (int) $value === (int) $product_id ) || ( 0 !== (int) $variation_id && ( (int) $value === (int) $variation_id ) ) ) ) {
						$key = $product_key;
					}
				}

				$item_found        = false;
				$addf_pro_quantity = 0;

				foreach (WC()->cart->get_cart() as $new_key=>$item) {
					foreach ($item as $inner_key=>$inner_item) {
						if (( 'variation_id' == $inner_key && $inner_item == $variation_id && 0 !== (int) $variation_id ) || ( ( 'product_id' == $inner_key && $inner_item == $product_id ) ) ) {
							$item_found = true;
						}

						if ($item_found && 'quantity' == $inner_key) {
							$addf_pro_quantity += $inner_item;
							$item_found         = false;
						}
					}
				}  
				
				if ('' == $key) {
					return $passed_validation;
				}

				if ($var_for_singlr_product[ $key ] >= ( (int) $var_for_singlr_product_rec[ $key ]+ (int) $addf_pro_quantity ) + (int) $quantity ) {
					return $passed_validation;
				} else {
					wc_add_notice('Selected quantity for product is greater than required quantity', 'error');
					return false;
				}   
			}
			return $passed_validation;
		}

		public function addf_gift_registry_on_action_cart_updated( $cart_updated, $cart_item_key, $cart_item, $quantity ) {

			$post_id                                       = WC()->session->get( 'addf_gift_registry_seesion_add_To_cart_gr_id');
			$product_id_of_product_in_cart_that_is_updated ='';
			$variation_id_of_product_in_cart_that_is_updated ='';
			
			$current_quantity                         = 0;
			$product_with_greeting_message_is_updated = false;
			foreach ($cart_item as $item_key=>$item_value) {
				if ('product_id' == $item_key) {
					$product_id_of_product_in_cart_that_is_updated = $item_value;
				}
				if ('variation_id' == $item_key) {
					$variation_id_of_product_in_cart_that_is_updated = $item_value;
				}
				if ('quantity' == $item_key) {
					$current_quantity = $item_value;
				}
				if ('addf_greeting_message' == $item_key) {
					$product_with_greeting_message_is_updated =true;
				}
			}
			if ($current_quantity == $quantity) {
				return $cart_updated;
			}


			if ( $post_id ) {
				$addf_gift_registry_product = (array) get_post_meta( $post_id, 'addf_gift_registry_product', true );
				$var_for_singlr_product     = (array) get_post_meta( $post_id, 'addf_gr_product_quantity', true );
				$var_for_singlr_product_rec = (array) get_post_meta( $post_id, 'addf_gr_product_quantity_recieved', true );

				$key = '';

				$key =  array_search($cart_item['product_id'], $addf_gift_registry_product);
				if ('' ==$key) {
					$key =  array_search($cart_item['variation_id'], $addf_gift_registry_product);
				}

				$item_found        = false;
				$addf_pro_quantity = 0;

				foreach (WC()->cart->get_cart() as $new_key=>$item) {

					if (!$product_with_greeting_message_is_updated && !in_array('addf_greeting_message', array_keys($item))) {
						continue;
					}

					foreach ($item as $inner_key=>$inner_item) {
						if ($product_with_greeting_message_is_updated && 'addf_greeting_message' == $inner_key) {
							break;
						}
						if (( 'variation_id' == $inner_key && $inner_item == $variation_id_of_product_in_cart_that_is_updated ) || ( ( 'product_id' == $inner_key && $inner_item == $product_id_of_product_in_cart_that_is_updated ) ) ) {
							$item_found = true;
						}

						if ($item_found && 'quantity' == $inner_key) {
							$addf_pro_quantity += $inner_item;
							$item_found         = false;
						}
					}
				}   
				
				if ('' == $key) {
					return $cart_updated;
				}
			
				if ($var_for_singlr_product[ $key ] < ( $var_for_singlr_product_rec[ $key ] + $quantity + $addf_pro_quantity )) {
					wc_add_notice( 'Selected quantity for product is greater than required quantity', 'error' );
					return false;
				}

			}
			return $cart_updated;
		}
	}
	new AF_Gift_Registry_Front();
}


