<?php
if ( ! class_exists( 'AF_Gift_Registry_Admin' ) ) {
	class AF_Gift_Registry_Admin {
		public function __construct() {
			// adding css
			add_action( 'admin_enqueue_scripts', array( $this, 'addf_gift_registry_Admin_enqueue_scripts' ) );

			// adding setting tab
			add_filter( 'woocommerce_settings_tabs_array', __CLASS__ . '::add_settings_tab', 50 );
			add_action( 'woocommerce_settings_tabs_settings_tab_gift_registry', __CLASS__ . '::settings_tab' );
			add_action( 'woocommerce_update_options_settings_tab_gift_registry', __CLASS__ . '::update_settings' );

			// add meta boxes
			add_action( 'add_meta_boxes', array( $this, 'addf_add_new_gift_registry' ) );
			// save meta data
			add_action( 'save_post_addf_gift_registry', array( $this, 'addf_save_new_gift_registry_save_metaData' ), 10, 2 );

			// select 2 select product
			add_action( 'wp_ajax_addf_gift_registry_getproductsearch', array( $this, 'addf_gift_registry_getproductsearch_cb' ) );
			add_action( 'wp_ajax_nopriv_addf_gift_registry_getproductsearch', array( $this, 'addf_gift_registry_getproductsearch_cb' ) );

			add_action( 'wp_ajax_addf_gr_single_Product_and_variation', array( $this, 'addf_gr_single_Product_and_variation' ) );
			add_action( 'wp_ajax_nopriv_addf_gr_single_Product_and_variation', array( $this, 'addf_gr_single_Product_and_variation' ) );

			// adding extra column
			add_filter( 'manage_addf_gift_registry_posts_columns', array( $this, 'set_custom_edit_addf_gift_registry_columns' ) );
			add_action( 'manage_addf_gift_registry_posts_custom_column', array( $this, 'custom_addf_gift_registry_column' ), 10, 2 );

			// text editor for email on product purchase
			// admin
			add_action( 'woocommerce_admin_field_addf_gift_registry_email_syntax_admin', array( $this, 'addf_gift_registry_email_syntax_admin' ) );
			add_action( 'woocommerce_admin_settings_sanitize_option_email_addf_gift_registry_email_syntax', array( $this, 'addf_gift_registry_email_syntax_admin_val' ), 10, 3 );
			// registrant
			add_action( 'woocommerce_admin_field_addf_gift_registry_syntax_mail_editor_registrant', array( $this, 'addf_gift_registry_email_syntax_registrant' ) );
			add_action( 'woocommerce_admin_settings_sanitize_option_email_addf_gift_registry_email_syntax_registrant', array( $this, 'addf_gift_registry_email_syntax_registrant_val' ), 10, 3 );
			// co registrant
			add_action( 'woocommerce_admin_field_addf_gift_registry_syntax_mail_editor_co_registrant', array( $this, 'addf_gift_registry_email_syntax_co_registrant' ) );
			add_action( 'woocommerce_admin_settings_sanitize_option_email_addf_gift_registry_email_syntax_co_registrant', array( $this, 'addf_gift_registry_email_syntax_co_registrant_val' ), 10, 3 );

			// text editor for share email
			add_action( 'woocommerce_admin_field_addf_gift_registry_syntax_share_mail_editor', array( $this, 'addf_gift_registry_share_email_syntax' ) );
			add_action( 'woocommerce_admin_settings_sanitize_option_wc_settings_tab_gift_registry_notify_gift_registry_admin_email_sytax', array( $this, 'addf_gift_registry_share_email_syntax_val' ), 10, 3 );

			// text editor for new registry email for admin
			add_action( 'woocommerce_admin_field_addf_gift_registry_syntax_new_reg_mail_editor_admin', array( $this, 'addf_gift_registry_syntax_new_reg_mail_editor_admin_cb' ) );
			add_action( 'woocommerce_admin_settings_sanitize_option_addf_gf_email_content_for_admin', array( $this, 'addf_gift_registry_syntax_new_reg_mail_editor_admin_callback' ), 10, 3 );
			// text editor for new registry email for registrant
			add_action( 'woocommerce_admin_field_addf_gift_registry_syntax_new_reg_mail_editor', array( $this, 'addf_gift_registry_new_reg_email_syntax' ) );
			add_action( 'woocommerce_admin_settings_sanitize_option_wc_settings_tab_gift_registry_notify_gift_registry_admin__new_reg_email_sytax', array( $this, 'addf_gift_registry_new_reg_email_syntax_val' ), 10, 3 );
			// text editor for new registry email for co registrant
			add_action( 'woocommerce_admin_field_addf_gift_registry_syntax_new_reg_mail_editor_co_registrant', array( $this, 'addf_gift_registry_co_reg_email_syntax' ) );
			add_action( 'woocommerce_admin_settings_sanitize_option_addf_gr_email_content_for_co_registrant', array( $this, 'addf_gift_registry_co_reg_email_syntax_val' ), 10, 3 );

			// text editor for expire registry email
			add_action( 'woocommerce_admin_field_addf_gift_registry_syntax_mail_editor_admin', array( $this, 'addf_gift_registry_expire_reg_email_syntax_admin' ) );
			add_action( 'woocommerce_admin_settings_sanitize_option_addf_gift_expire_registry_syntax_mail_editor', array( $this, 'addf_gift_registry_expire_reg_email_syntax_admin_val' ), 10, 3 );
			add_action( 'woocommerce_admin_field_addf_gift_expire_registry_syntax_mail_editor', array( $this, 'addf_gift_expire_registry_syntax_mail_editor' ) );
			add_action( 'woocommerce_admin_settings_sanitize_option_email_addf_gift_registry_email_syntax_reg_expired', array( $this, 'addf_gift_expire_registry_syntax_mail_editor_val' ), 10, 3 );

			// cron
			add_filter( 'cron_schedules', array( $this, 'addf_gift_registry_add_cron_interval' ) );
			add_action( 'addf_gr_crone_time', array( $this, 'addf_gift_registry_gr_is_expired' ) );
			add_action( 'init', array( $this, 'addf_gift_registry_schedule_call_back' ) );
			register_deactivation_hook( __FILE__, array( $this, 'crone_deactivation' ) );

			//adding greeting message to order meta[code added after new feature of greeting message]
			add_action('woocommerce_before_order_itemmeta', array( $this, 'addf_gift_registry_display_greeting_message_in_order' ), 10, 3);
		}

		// adding css
		public function addf_gift_registry_Admin_enqueue_scripts() {


			//screen check
			$addf_gr_current_screen = get_current_screen();

			if ( $addf_gr_current_screen && ( in_array($addf_gr_current_screen->id, $this-> get_screen_tab_id() ) )) {

				// addf-gift-registry-admin-style.css
					wp_enqueue_style( 'gift-registry-admin-css', plugins_url( '../includes/css/addf-gift-registry-admin-style.css', __FILE__ ), false, '1.0.0' );
					wp_enqueue_script( 'jquery' );
					wp_enqueue_script( 'gift-registry-admin-script', plugins_url( '../includes/js/addf-g-r-admin.js', __FILE__ ), false, '1.0.0', $in_footer = false );

					wp_enqueue_style( 'select2', plugins_url( 'assets/css/select2.css', WC_PLUGIN_FILE ), array(), '5.7.2' );
					wp_enqueue_script( 'select2', plugins_url( 'assets/js/select2/select2.min.js', WC_PLUGIN_FILE ), array( 'jquery' ), '4.0.3', true );

					wp_enqueue_style( 'Font-Awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css', false, '1.0.0' );

					$aurgs = array(
						'ajaxurl' => admin_url( 'admin-ajax.php' ),
						'nonce'   => wp_create_nonce( 'addify_gift_registry_nonce' ),
					);
					wp_localize_script( 'gift-registry-admin-script', 'php_var', $aurgs );

			}
		}

		public function get_screen_tab_id() {
			$tabs =array( 'woocommerce_page_wc-settings', 'edit-addf_gift_registry', 'addf_gift_registry' );
			return $tabs;
		}
		// Add the custom columns to the addf_gift_registry post type:
		public function set_custom_edit_addf_gift_registry_columns( $columns ) {
			$columns['addf_gift_registry_visibility'] = esc_html__( 'Registry Visibility', 'addf_giftr' );
			$columns['gift_registry_user_id']         = esc_html__( 'Registrant User Id', 'addf_giftr' );
			$columns['gift_registry_user']            = esc_html__( 'Registrant User Name', 'addf_giftr' );
			$columns['gift_registry_user_email']      = esc_html__( 'Registrant Email', 'addf_giftr' );
			$columns['gift_registry_status']          = esc_html__( 'Status', 'addf_giftr' );

			return $columns;
		}
		// Add the data to the custom columns for the addf_gift_registry post type:
		public function custom_addf_gift_registry_column( $column, $post_id ) {
			switch ( $column ) {
				case 'gift_registry_user_id':
					$gift_regsitry_user_id = get_post_meta( $post_id, 'gift-registry-registrant-user_is_wp', true );
					if ( ! empty( $gift_regsitry_user_id ) ) {
						echo esc_html__( $gift_regsitry_user_id, 'addf_giftr' );
					} else {
						echo esc_html__( 'Guest', 'addf_giftr' );
					}
					break;
				case 'gift_registry_user':
					$terms_first_name = get_post_meta( $post_id, 'gift-registry-registrant-first-name', true );
					$terms_last_name  = get_post_meta( $post_id, 'gift-registry-registrant-last-name', true );
					if ( is_string( $terms_first_name ) ) {
						echo esc_html__( $terms_first_name, 'addf_giftr' ) . '&nbsp;' . esc_html__( $terms_last_name, 'addf_giftr' );
					} else {
						echo esc_html__( "Unable to get user's name", 'addf_giftr' );
					}
					break;

				case 'gift_registry_user_email':
					$gift_regsitry_user_email = get_post_meta( $post_id, 'gift-registry-registrant-email', true );
					if ( ! empty( $gift_regsitry_user_email ) ) {
						echo esc_html__( $gift_regsitry_user_email, 'addf_giftr' );
					} else {
						echo esc_html__( "Unable to get user's email", 'addf_giftr' );
					}
					break;
				case 'addf_gift_registry_visibility':
					$addf_gift_registry_visibility = get_post_meta( $post_id, 'addf-gift-registry-visibility', true );
					if ( ! empty( $addf_gift_registry_visibility ) ) {
						if ( '2' === $addf_gift_registry_visibility ) {
							echo esc_html__( 'Password protected', 'addf_giftr' );
						} else {
							echo esc_html__( 'Public', 'addf_giftr' );
						}
					} else {
						echo esc_html__( 'Not Defined', 'addf_giftr' );
					}
					break;
				case 'gift_registry_status':
					$addf_gift_registry_validity = get_post_meta( $post_id, 'gift-registry-event-info-expire-date', true );
					if ( 'expired' != $addf_gift_registry_validity ) {
						?>
					<span class="addf_gr_active">
						<?php echo esc_html__( 'Active', 'addf_giftr' ); ?>
					</span>
						<?php
					} else {
						?>
					<span class="addf_gr_expire">
						<?php echo esc_html__( 'Expired', 'addf_giftr' ); ?>
					</span>
						<?php
					}
					break;
			}
		}
		public static function add_settings_tab( $settings_tabs ) {
			$settings_tabs['settings_tab_gift_registry'] = esc_html__( 'Gift Registry Settings', 'addf_giftr' );
			return $settings_tabs;
		}
		public static function settings_tab() {
			woocommerce_admin_fields( self::get_settings() );
		}
		public static function update_settings() {
			woocommerce_update_options( self::get_settings() );
		}
		public static function get_settings() {
			global $wp_roles;
			$roles    = $wp_roles->get_names();
			$settings = array(
				'section_title'                            => array(
					'name' => esc_html__( 'Gift Registry Settings', 'addf_giftr' ),
					'type' => 'title',
					'desc' => '<br><a class="button addf_gr_all_btns addf_gf_gen_tab" >' .
					esc_html__( 'General Settings', 'addf_giftr' ) . '</a>&nbsp;&nbsp;<a class="button addf_gr_all_btns addf_gf_social_tab" >' .
					esc_html__( 'Social Settings', 'addf_giftr' ) . '</a>&nbsp;&nbsp;<a class="button addf_gr_all_btns addf_gf_email_tab" >' .
					esc_html__( 'Email Settings', 'addf_giftr' ) . '</a>&nbsp;&nbsp;<a class="button addf_gr_all_btns addf_gf_res_msg" >' .
					esc_html__( 'Restriction Messages Customization', 'addf_giftr' ) . '</a>&nbsp;&nbsp;<a class="button addf_gr_all_btns addf_gf_btn_tab" >' .
					esc_html__( 'Button Customization', 'addf_giftr' ) . '</a>&nbsp;&nbsp;<a class="button" href="' .
					esc_url( 'edit.php?post_type=addf_gift_registry' ) . '">' . esc_html__( 'View all Gift Registries', 'addf_giftr' ) .
					'</a>',
					'id'   => 'wc_settings_tab_gift_registry_section_title',
				),
				'notify_gift_registry_admin'               => array(
					'name'  => esc_html__( ' Notify Admin when someone buy gift  ', 'addf_giftr' ),
					'type'  => 'checkbox',
					'class' => 'addf_gf_gen_tab_ops',
					'desc'  => '<br>' . esc_html__( 'Check if you want to notify the admin if someone buy a gift ', 'addf_giftr' ),
					'id'    => 'wc_settings_tab_gift_registry_notify_gift_registry_admin',
				),
				'notify_r_owner'                           => array(
					'name'  => esc_html__( 'Notify Registrant when someone buy gift', 'addf_giftr' ),
					'type'  => 'checkbox',
					'class' => 'addf_gf_gen_tab_ops',
					'desc'  => '<br>' . esc_html__( 'Check if you want to notify the registrant if someone buy a gift ', 'addf_giftr' ),
					'id'    => 'wc_settings_tab_gift_registry_notify_r_owner',
				),
				'notify_registrant_friend'                 => array(
					'name'  => esc_html__( ' Notify co registrant when someone buy gift ', 'addf_giftr' ),
					'type'  => 'checkbox',
					'class' => 'addf_gf_gen_tab_ops',
					'desc'  => '<br>' . esc_html__( 'Check if you want to notify the co registrant of registry if someone buy a gift ', 'addf_giftr' ),
					'id'    => 'wc_settings_tab_gift_registry_notify_registrant_friend',
				),
				'notify_registrant_new_admin_send_mail'    => array(
					'name'  => esc_html__( ' Notify admin when Registry is created ', 'addf_giftr' ),
					'type'  => 'checkbox',
					'class' => 'addf_gf_gen_tab_ops',
					'desc'  => '<br>' . esc_html__( 'Check if you want to notify the admin after creating registry ', 'addf_giftr' ),
					'id'    => 'wc_settings_tab_gift_registry_notify_registrant_new_admin_send_mail',
				),
				'notify_registrant_new_reg_send_mail'      => array(
					'name'  => esc_html__( ' Notify Registrant when Registry is created ', 'addf_giftr' ),
					'type'  => 'checkbox',
					'class' => 'addf_gf_gen_tab_ops',
					'desc'  => '<br>' . esc_html__( 'Check if you want to notify the registrant of registry  after creating registry ', 'addf_giftr' ),
					'id'    => 'wc_settings_tab_gift_registry_notify_registrant_new_reg_send_mail',
				),
				'notify_co_registrant_new_reg_send_mail'   => array(
					'name'  => esc_html__( ' Notify Co Registrant when Registry is created ', 'addf_giftr' ),
					'type'  => 'checkbox',
					'class' => 'addf_gf_gen_tab_ops',
					'desc'  => '<br>' . esc_html__( 'Check if you want to notify the co registrant  after creating registry ', 'addf_giftr' ),
					'id'    => 'wc_settings_tab_gift_registry_notify_co_registrant_new_reg_send_mail',
				),
				'notify_gift_registry_restriction'         => array(
					'name'  => esc_html__( '	Restrict to create registry if shipping is not full filled  ', 'addf_giftr' ),
					'type'  => 'checkbox',
					'class' => 'addf_gf_gen_tab_ops',
					'desc'  => '<br>' . esc_html__( 'Check if you want to restrict the user to add to registry if shipping form is not fulfilled ', 'addf_giftr' ),
					'id'    => 'wc_settings_tab_gift_registry_notify_gift_registry_restriction',
				),
				'notify_gift_registry_enable_redirect_after_add_to_cart' => array(
					'name'  => esc_html__( '	Enable Automatic Redirect after add to cart  ', 'addf_giftr' ),
					'type'  => 'checkbox',
					'class' => 'addf_gf_gen_tab_ops',
					'desc'  => '<br>' . esc_html__( 'Check if you want to enable automatic redirect to cart/checkout page after add to cart ', 'addf_giftr' ),
					'id'    => 'wc_settings_tab_gift_registry_notify_gift_registry_enable_redirect_after_add_to_cart',
				),
				'gift_registry_redirect_after_add_to_cart_option' => array(
					'name'    => esc_html__( ' Redirect to Page  ', 'addf_giftr' ),
					'type'    => 'select',
					'class'   => array( 'addf_gf_gen_tab_ops' ),
					'options' => array(
						'cart_page'     => __( 'Cart Page', 'addf_giftr' ),
						'checkout_page' => __( 'Checkout Page', 'addf_giftr' ),
					),
					'desc'    => '<br>' . esc_html__( 'Select the page on which automatic redirect will take place after add to cart ', 'addf_giftr' ),
					'id'      => 'wc_settings_tab_gift_registry_notify_gift_registry_redirect_after_add_to_cart_option',
				),
				'gift_registry_enable_edit_delete_for_expired_registry' => array(
					'name'  => esc_html__( '	Enable edit and delete option for expired registry', 'addf_giftr' ),
					'type'  => 'checkbox',
					'class' => 'addf_gf_gen_tab_ops',
					'desc'  => '<br>' . esc_html__( 'Check if you want to enable edit and delete option for expired registry at front. ', 'addf_giftr' ),
					'id'    => 'wc_settings_tab_gift_registry_notify_gift_registry_enable_edit_delete_for_expired_registry',
				),
				'gift_registry_enable_gift_registry_search' => array(
					'name'  => esc_html__( '	Enable search option for gift registry', 'addf_giftr' ),
					'type'  => 'checkbox',
					'class' => 'addf_gf_gen_tab_ops',
					'desc'  => '<br>' . esc_html__( 'Check if you want to enable search option for gift registry. ', 'addf_giftr' ),
					'id'    => 'wc_settings_tab_gift_registry_notify_gift_registry_enable_gift_registry_search',
				),
				'gift_registry_search_visibility_page_option' => array(
					'name'    => esc_html__( ' Gift Registry search visibility  ', 'addf_giftr' ),
					'type'    => 'select',
					'class'   => array( 'addf_gf_gen_tab_ops' ),
					'options' => array(
						'single_registry_page' => __( ' Single Registry Page', 'addf_giftr' ),
						'registry_page'        => __( 'Gift Registry Page', 'addf_giftr' ),
						'both'                 => __( 'Both', 'addf_giftr' ),
					),
					'desc'    => '<br>' . esc_html__( 'Select the page on which gift registry search will displayed ', 'addf_giftr' ),
					'id'      => 'wc_settings_tab_gift_registry_search_visibility_page_option',
				),
				'notify_gift_registry_restriction_pp_cb'   => array(
					'name'  => esc_html__( '	Enable privacy policy ', 'addf_giftr' ),
					'type'  => 'checkbox',
					'class' => 'addf_gf_gen_tab_ops',
					'desc'  => '<br>' . esc_html__( 'Check if you want to enable privacy policy checkbox', 'addf_giftr' ),
					'id'    => 'addf_gr_enable_pp_cb',
				),
				'notify_gift_registry_restriction_pp_text' => array(
					'name'  => esc_html__( 'Privacy policy', 'addf_giftr' ),
					'type'  => 'textarea',
					'class' => 'addf_gf_gen_tab_ops addf_gr_pp_textarea',
					'desc'  => '<br>' . esc_html__( 'Enter privacy policy policy , you can use <a href="https://www.google.com"> Text </a> for anchor tag. ', 'addf_giftr' ),
					'id'    => 'addf_gr_enable_pp_text',
				),
				'addf_gr_restrict_add_to_cart'             => array(
					'name'    => esc_html__( ' Select restriction type  ', 'addf_giftr' ),
					'type'    => 'select',
					'class'   => array( 'wc-enhanced-select', 'addf_gf_gen_tab_ops' ),
					'options' => array(
						'rest_user'  => __( 'Restrict user', 'addf_giftr' ),
						'allow_user' => __( 'Force to deliver with gift registry items', 'addf_giftr' ),
					),
					'desc'    => '<br>' . esc_html__( 'Select a method to restrict/allow user if cart have items', 'addf_giftr' ),
					'id'      => 'wc_settings_tab_gift_registry_addf_gr_restrict_add_to_cart',
				),
				'addf_gr_enable_free_shipping_for_registry_orders' => array(
					'name'    => esc_html__( ' Enable free shipping for Gift Registry orders', 'addf_giftr' ),
					'type'    => 'checkbox',
					'class'   => 'addf_gf_gen_tab_ops',
					'desc'    => '<br>' . esc_html__( 'Apply free shipping when cart contains Gift Registry items only.', 'addf_giftr' ),
					'id'      => 'wc_settings_tab_gift_registry_enable_free_shipping_for_registry_orders',
					'default' => 'yes',
				),
				'gift_registry_menu_for_op'                => array(
					'name'    => esc_html__( ' Select add to menu type  ', 'addf_giftr' ),
					'type'    => 'radio',
					'class'   => 'addf_gf_gen_tab_ops',
					'options' => array(
						'all'      => esc_html__( 'All Users', 'addf_giftr' ),
						'selected' => esc_html__( 'Only for users allowed to create registry', 'addf_giftr' ),
					),
					'desc'    => esc_html__( 'Choose whether Gift Registry page for all users or only for allowed users ', 'addf_giftr' ),
					'id'      => 'wc_settings_tab_gift_registry_gift_registry_menu_for_op',
				),
				'notify_registrant_allow_gest'             => array(
					'name'  => esc_html__( ' Allow Guest users to create registry', 'addf_giftr' ),
					'type'  => 'checkbox',
					'class' => 'addf_gf_gen_tab_ops',
					'desc'  => '<br>' . esc_html__( 'Check if you want to allow guests users to create registry ', 'addf_giftr' ),
					'id'    => 'wc_settings_tab_gift_registry_notify_registrant_allow_gest',
				),
				'addf_gr_allow_users_create_registry'      => array(
					'name'    => esc_html__( ' Select users  ', 'addf_giftr' ),
					'type'    => 'multiselect',
					'class'   => 'wc-enhanced-select select_product addf_gf_gen_tab_ops',
					'options' => $roles,
					'desc'    => '<br>' . esc_html__( 'Select roles you want to allow them to create registry ', 'addf_giftr' ),
					'id'      => 'wc_settings_tab_gift_registry_addf_gr_allow_users_create_registry',
				),
				'notify_gift_registry_fb_share'            => array(
					'name'  => esc_html__( ' Share on facebook', 'addf_giftr' ),
					'type'  => 'checkbox',
					'class' => 'addf_gf_social_tab_ops',
					'desc'  => '<br>' . esc_html__( 'Check if you want to allow facebook share ', 'addf_giftr' ),
					'id'    => 'wc_settings_tab_gift_registry_notify_gift_registry_fb_share',
				),
				'notify_gift_registry_twitter_share'       => array(
					'name'  => esc_html__( ' Share on Twitter', 'addf_giftr' ),
					'type'  => 'checkbox',
					'class' => 'addf_gf_social_tab_ops',
					'desc'  => '<br>' . esc_html__( 'Check if you want to allow Twitter share ', 'addf_giftr' ),
					'id'    => 'wc_settings_tab_gift_registry_notify_gift_registry_twitter_share',
				),
				'notify_gift_registry_email_share'         => array(
					'name'  => esc_html__( ' Share on email', 'addf_giftr' ),
					'type'  => 'checkbox',
					'class' => 'addf_gf_social_tab_ops',
					'desc'  => '<br>' . esc_html__( 'Check if you want to allow email share ', 'addf_giftr' ),
					'id'    => 'wc_settings_tab_gift_registry_notify_gift_registry_email_share',
				),
				'notify_gift_registry_email_share_link_pr' => array(
					'name'  => esc_html__( 'Allow to copy link ', 'addf_giftr' ),
					'type'  => 'checkbox',
					'class' => 'addf_gf_social_tab_ops',
					'desc'  => '<br>' . esc_html__( 'Check if you want to allow user to copy link of registry ', 'addf_giftr' ),
					'id'    => 'wc_settings_tab_gift_registry_notify_gift_registry_email_share_link_private',
				),
				'notify_gift_registry_admin_email'         => array(
					'name'  => esc_html__( ' Email Address  ', 'addf_giftr' ),
					'type'  => 'text',
					'class' => 'addf_gf_email_tab_ops',
					'desc'  => esc_html__( 'Enter your (Admin) email address for notification ', 'addf_giftr' ),
					'id'    => 'wc_settings_tab_gift_registry_notify_gift_registry_admin_email',
				),
				'notify_gift_registry_new_reg_site_title'  => array(
					'name'  => esc_html__( ' Subject of email for admin', 'addf_giftr' ),
					'type'  => 'text',
					'class' => 'addf_gf_email_tab_ops',
					'desc'  => esc_html__( 'Subject of email send to admin to be displayed whenever new registry is created.', 'addf_giftr' ),
					'id'    => 'addf_gr_email_subject_for_admin',
				),
				'notify_gift_registry_new_reg_site_title_heading' => array(
					'name'  => esc_html__( ' Heading of email for admin', 'addf_giftr' ),
					'type'  => 'text',
					'class' => 'addf_gf_email_tab_ops',
					'desc'  => esc_html__( 'Heading in email send to admin to be displayed whenever new registry is created.', 'addf_giftr' ),
					'id'    => 'addf_gr_email_subject_for_admin_heading',
				),
				'notify_gift_registry_admin__new_reg_email_admin' => array(
					'name'  => esc_html__( ' Email Content for new registry created  ', 'addf_giftr' ),
					'type'  => 'addf_gift_registry_syntax_new_reg_mail_editor_admin',
					'class' => 'addf_gf_email_tab_ops',
					'desc'  => esc_html__( 'Type content of email which will send to admin whenever a new registry created . Use {registrant_id} for user id , {registrant_name} for name of user , {registry_title} for registry title , {current_time} for time  and {registrant_msg} for registrant message ', 'addf_giftr' ),
					'id'    => 'addf_gf_email_content_for_admin',
				),
				'notify_gift_registry_new_reg_site_title_admin' => array(
					'name'  => esc_html__( 'Subject of email for registrant  ', 'addf_giftr' ),
					'type'  => 'text',
					'class' => 'addf_gf_email_tab_ops',
					'desc'  => esc_html__( 'Subject of email send to registrant to be displayed whenever new registry is created.', 'addf_giftr' ),
					'id'    => 'wc_settings_tab_gift_registry_notify_gift_registry_new_reg_site_title',
				),
				'notify_gift_registry_new_reg_site_title_admin_heading' => array(
					'name'  => esc_html__( 'Heading of email for registrant  ', 'addf_giftr' ),
					'type'  => 'text',
					'class' => 'addf_gf_email_tab_ops',
					'desc'  => esc_html__( 'Heading in email send to registrant to be displayed whenever new registry is created.', 'addf_giftr' ),
					'id'    => 'wc_settings_tab_gift_registry_notify_gift_registry_new_reg_site_title_heading',
				),
				'notify_gift_registry_admin__new_reg_email_sytax' => array(
					'name'  => esc_html__( ' Email Content for new registry created  ', 'addf_giftr' ),
					'type'  => 'addf_gift_registry_syntax_new_reg_mail_editor',
					'class' => 'addf_gf_email_tab_ops',
					'desc'  => esc_html__( 'Type content of email which will send to registrant whenever a new registry created . Use {registrant_id} for user id , {registrant_name} for name of user , {registry_title} for registry title , {current_time} for time  and {registrant_msg} for registrant message ', 'addf_giftr' ),
					'id'    => 'wc_settings_tab_gift_registry_notify_gift_registry_admin__new_reg_email_sytax',
				),
				'notify_gift_registry_new_reg_site_title_co_reg' => array(
					'name'  => esc_html__( ' Subject of email for co registrant  ', 'addf_giftr' ),
					'type'  => 'text',
					'class' => 'addf_gf_email_tab_ops',
					'desc'  => esc_html__( 'Subject of email send to co registrant to be displayed whenever new registry is created.', 'addf_giftr' ),
					'id'    => 'addf_gr_email_subject_for_co_registrant',
				),
				'notify_gift_registry_new_reg_site_title_co_reg_heading' => array(
					'name'  => esc_html__( ' Heading of email for co registrant  ', 'addf_giftr' ),
					'type'  => 'text',
					'class' => 'addf_gf_email_tab_ops',
					'desc'  => esc_html__( 'Heading in email send to co registrant to be displayed whenever new registry is created.', 'addf_giftr' ),
					'id'    => 'addf_gr_email_subject_for_co_registrant_heading',
				),
				'notify_gift_registry_admin__new_reg_email_sytax_co_reg' => array(
					'name'  => esc_html__( ' Email Content for new registry created  ', 'addf_giftr' ),
					'type'  => 'addf_gift_registry_syntax_new_reg_mail_editor_co_registrant',
					'class' => 'addf_gf_email_tab_ops',
					'desc'  => esc_html__( 'Type content of email which will send to co registrant whenever a new registry created . Use {registrant_id} for user id , {registrant_name} for name of user , {co_registrant_name} for co registrant name , {registry_title} for registry title , {current_time} for time  and {registrant_msg} for registrant message ', 'addf_giftr' ),
					'id'    => 'addf_gr_email_content_for_co_registrant',
				),

				'notify_gift_registry_site_title'          => array(
					'name'  => esc_html__( ' Subject of email for share  ', 'addf_giftr' ),
					'type'  => 'text',
					'class' => 'addf_gf_email_tab_ops',
					'desc'  => esc_html__( 'Subject of email to be displayed if share', 'addf_giftr' ),
					'id'    => 'wc_settings_tab_gift_registry_notify_gift_registry_site_title',
				),
				'notify_gift_registry_site_title_heading'  => array(
					'name'  => esc_html__( ' Heading of email for share  ', 'addf_giftr' ),
					'type'  => 'text',
					'class' => 'addf_gf_email_tab_ops',
					'desc'  => esc_html__( 'Heading in email to be displayed if share', 'addf_giftr' ),
					'id'    => 'wc_settings_tab_gift_registry_notify_gift_registry_site_title_heading',
				),
				'notify_gift_registry_admin_email_sytax'   => array(
					'name'  => esc_html__( ' Email Content for share  ', 'addf_giftr' ),
					'type'  => 'addf_gift_registry_syntax_share_mail_editor',
					'class' => 'addf_gf_email_tab_ops',
					'desc'  => esc_html__( 'Type content of email which will be send whenever user share registry through email. Use {registrant_id} for user id , {registrant_name} for name of user , {registrant_msg} for registrant message , {registry_title} for registry title and {registry_url} for url of registry', 'addf_giftr' ),
					'id'    => 'wc_settings_tab_gift_registry_notify_gift_registry_admin_email_sytax',
				),
				'notify_gift_registry_p_buy_site_title_admin' => array(
					'name'  => esc_html__( ' Subject of email for admin   ', 'addf_giftr' ),
					'type'  => 'text',
					'class' => 'addf_gf_email_tab_ops',
					'desc'  => esc_html__( 'Subject of email send to admin to be displayed whenever someone buys product from registry.', 'addf_giftr' ),
					'id'    => 'addf_gr_product_purchase_mail_subject_admin',
				),
				'notify_gift_registry_p_buy_site_title_admin_heading' => array(
					'name'  => esc_html__( ' Heading of email for admin   ', 'addf_giftr' ),
					'type'  => 'text',
					'class' => 'addf_gf_email_tab_ops',
					'desc'  => esc_html__( 'Heading in email send to admin to be displayed whenever someone buys product from registry.', 'addf_giftr' ),
					'id'    => 'addf_gr_product_purchase_mail_subject_admin_heading',
				),
				'notify_gift_registry_site_email_template_admin' => array(
					'name'  => esc_html__( 'Admin email template for purchased products ', 'addf_giftr' ),
					'type'  => 'addf_gift_registry_syntax_mail_editor_admin',
					'class' => 'addf_gf_email_tab_ops',
					'desc'  => esc_html__(
						'Type a content which will send whenever someone purchases a product . Use {registrant_id} for user id , {registrant_name} for name of user {registry_title} for registry title ,  
						{product_name} for product name purchased {time_of_purchase} for time of product purchased',
						'addf_giftr'
					),
					'id'    => 'email_addf_gift_registry_email_syntax_admin',
				),
				'notify_gift_registry_p_buy_site_title_registrant' => array(
					'name'  => esc_html__( ' Subject of email for registrant   ', 'addf_giftr' ),
					'type'  => 'text',
					'class' => 'addf_gf_email_tab_ops',
					'desc'  => esc_html__( 'Subject of email send to registrant to be displayed whenever someone buys product from registry.', 'addf_giftr' ),
					'id'    => 'addf_gr_product_purchase_mail_subject_registrant',
				),
				'notify_gift_registry_p_buy_site_title_registrant_heading' => array(
					'name'  => esc_html__( ' Heading of email for registrant   ', 'addf_giftr' ),
					'type'  => 'text',
					'class' => 'addf_gf_email_tab_ops',
					'desc'  => esc_html__( 'Heading in email send to registrant to be displayed whenever someone buys product from registry.', 'addf_giftr' ),
					'id'    => 'addf_gr_product_purchase_mail_subject_registrant_heading',
				),
				'notify_gift_registry_site_email_template_registrant' => array(
					'name'  => esc_html__( 'Registrant email template for purchased products ', 'addf_giftr' ),
					'type'  => 'addf_gift_registry_syntax_mail_editor_registrant',
					'class' => 'addf_gf_email_tab_ops',
					'desc'  => esc_html__(
						'Type a content which will send whenever someone purchases a product . Use {registrant_id} for user id , {registrant_name} for name of user {registry_title} for registry title ,  
						{product_name} for product name purchased {time_of_purchase} for time of product purchased',
						'addf_giftr'
					),
					'id'    => 'email_addf_gift_registry_email_syntax_registrant',
				),
				'notify_gift_registry_p_buy_site_title_co_registrant' => array(
					'name'  => esc_html__( ' Subject of email for co registrant   ', 'addf_giftr' ),
					'type'  => 'text',
					'class' => 'addf_gf_email_tab_ops',
					'desc'  => esc_html__( 'Subject of email send to co registrant to be displayed whenever someone buys product from registry.', 'addf_giftr' ),
					'id'    => 'addf_gr_product_purchase_mail_subject_co_registrant',
				),
				'notify_gift_registry_p_buy_site_title_co_registrant_heading' => array(
					'name'  => esc_html__( ' Heading of email for co registrant   ', 'addf_giftr' ),
					'type'  => 'text',
					'class' => 'addf_gf_email_tab_ops',
					'desc'  => esc_html__( 'Heading in email send to co registrant to be displayed whenever someone buys product from registry.', 'addf_giftr' ),
					'id'    => 'addf_gr_product_purchase_mail_subject_co_registrant_heading',
				),
				'notify_gift_registry_site_email_template_co_registrant' => array(
					'name'  => esc_html__( 'Co registrant email template for purchased products ', 'addf_giftr' ),
					'type'  => 'addf_gift_registry_syntax_mail_editor_co_registrant',
					'class' => 'addf_gf_email_tab_ops',
					'desc'  => esc_html__(
						'Type a content which will send whenever someone purchases a product . Use {registrant_id} for user id , {registrant_name} for name of user , {co_registrant_name} for co registrant name , {registry_title} for registry title ,  
						{product_name} for product name purchased and {time_of_purchase} for time of product purchased',
						'addf_giftr'
					),
					'id'    => 'email_addf_gift_registry_email_syntax_co_registrant',
				),
				'notify_gift_registry_exp_site_title'      => array(
					'name'  => esc_html__( ' Subject of email   ', 'addf_giftr' ),
					'type'  => 'text',
					'class' => 'addf_gf_email_tab_ops',
					'desc'  => esc_html__( 'Subject of email to be displayed whenever a registry is expired.', 'addf_giftr' ),
					'id'    => 'wc_settings_tab_gift_registry_notify_gift_registry_exp_site_title',
				),
				'notify_gift_registry_exp_site_title_heading' => array(
					'name'  => esc_html__( ' Heading of email   ', 'addf_giftr' ),
					'type'  => 'text',
					'class' => 'addf_gf_email_tab_ops',
					'desc'  => esc_html__( 'Heading in email to be displayed whenever a registry is expired.', 'addf_giftr' ),
					'id'    => 'wc_settings_tab_gift_registry_notify_gift_registry_exp_site_title_heading',
				),
				'notify_gift_registry_site_email_template_reg_expired_registrant' => array(
					'name'  => esc_html__( 'Email template for expired registry ', 'addf_giftr' ),
					'type'  => 'addf_gift_expire_registry_syntax_mail_editor',
					'class' => 'addf_gf_email_tab_ops',
					'desc'  => esc_html__(
						'Type a content which will send whenever a registry is expired . Use {registrant_id} for user id , {registrant_name} for name of user ,  
						{registry_title} for registry title and {current_time} for time ',
						'addf_giftr'
					),
					'id'    => 'email_addf_gift_registry_email_syntax_reg_expired',
				),
				'notify_gift_registry_guest_res_msg'       => array(
					'name'  => esc_html__( ' Enter Restriction Message for Guest user  ', 'addf_giftr' ),
					'type'  => 'textarea',
					'class' => 'addf_gf_res_msg_ops',
					'desc'  => esc_html__( 'Enter restriction message for guest user if user is not allowed to create gift registry ', 'addf_giftr' ),
					'id'    => 'wc_settings_tab_gift_registry_notify_gift_registry_guest_res_msg',
				),
				'notify_gift_registry_empty_gr_reg'        => array(
					'name'  => esc_html__( ' Enter Restriction Message for Guest user  ', 'addf_giftr' ),
					'type'  => 'textarea',
					'class' => 'addf_gf_res_msg_ops',
					'desc'  => esc_html__( "Enter restriction message for user which will show to user on add to cart if there's no gift registry created . ", 'addf_giftr' )
					. esc_html__( 'Use <a href="{gift_registry_page}" > click here </a> to go to gift registry page', 'addf_giftr' ),
					'id'    => 'addf_gr_empty_gr_text',
				),
				'notify_gift_registry_rest_user_res_msg'   => array(
					'name'  => esc_html__( ' Enter Restriction Message for restricted users  ', 'addf_giftr' ),
					'type'  => 'textarea',
					'class' => 'addf_gf_res_msg_ops',
					'desc'  => esc_html__( 'Enter restriction message for users who are not allowed to create gift registry ', 'addf_giftr' ),
					'id'    => 'wc_settings_tab_gift_registry_notify_gift_registry_rest_user_res_msg',
				),
				'notify_gift_registry_exp_reg_msg'         => array(
					'name'  => esc_html__( ' Enter message for expired registry if accessed directly  ', 'addf_giftr' ),
					'type'  => 'textarea',
					'class' => 'addf_gf_res_msg_ops',
					'desc'  => esc_html__( 'Enter message for expired gift registry to inform user if accessed directly', 'addf_giftr' ),
					'id'    => 'wc_settings_tab_gift_registry_notify_gift_registry_exp_reg_msg',
				),
				'notify_gift_registry_shiping_addr'        => array(
					'name'  => esc_html__( ' Enter message for shipping address if not fulfilled  ', 'addf_giftr' ),
					'type'  => 'textarea',
					'class' => 'addf_gf_res_msg_ops',
					'desc'  => esc_html__( 'Message will shown if shipping address is not fulfilled', 'addf_giftr' ),
					'id'    => 'wc_settings_tab_gift_registry_notify_gift_registry_shiping_addr',
				),
				'gift_registry_btn_option'                 => array(
					'name'    => esc_html__( ' Select type  ', 'addf_giftr' ),
					'type'    => 'radio',
					'class'   => 'addf_gf_btn_tab_ops',
					'options' => array(
						'btn'  => esc_html__( 'Show Button', 'addf_giftr' ),
						'link' => esc_html__( 'Show link', 'addf_giftr' ),
					),
					'desc'    => esc_html__( 'choose a type for add to cart button or text ', 'addf_giftr' ),
					'id'      => 'wc_settings_tab_gift_registry_gift_registry_btn_option',
				),
				'notify_gift_registry_btn_text'            => array(
					'name'  => esc_html__( ' Add to Registry button text  ', 'addf_giftr' ),
					'type'  => 'text',
					'class' => 'addf_gf_btn_tab_ops',
					'desc'  => esc_html__( 'Enter text for add to registry button ', 'addf_giftr' ),
					'id'    => 'wc_settings_tab_gift_registry_notify_gift_registry_btn_text',
				),
				'section_end'                              => array(
					'type' => 'sectionend',
					'id'   => 'wc_settings_tab_gift_registry_section_end',
				),
			);
			return apply_filters( 'wc_settings_tab_gift_registry_settings', $settings );
		}
		// for text editor on purchase
		// admin
		public function a( $value, $option, $raw_value ) {
			update_option( $option['id'], $raw_value );
		}
		public function addf_gift_registry_email_syntax_admin( $value ) {
			$option_value = WC_Admin_Settings::get_option( $value['id'], $value['default'] );
			?>
	<tr valign="top">
		<th scope="row" class="titledesc">
			<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?></label>
		</th>
		<td class="addf_gr addf_gr_editors addf_gr-<?php echo esc_html( $value['type'] ); ?>">
			<?php echo esc_html( $value['desc'] ); ?>
			<?php wp_editor( $option_value, esc_attr( $value['id'] ) ); ?>
		</td>
	</tr>
			<?php
		}
		// registrant
		public function addf_gift_registry_email_syntax_registrant_val( $value, $option, $raw_value ) {
			update_option( $option['id'], $raw_value );
		}
		public function addf_gift_registry_email_syntax_registrant( $value ) {
			$option_value = WC_Admin_Settings::get_option( $value['id'], $value['default'] );
			?>
	<tr valign="top">
		<th scope="row" class="titledesc">
			<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?></label>
		</th>
		<td class="addf_gr addf_gr_editors addf_gr-<?php echo esc_html( $value['type'] ); ?>">
			<?php echo esc_html( $value['desc'] ); ?>
			<?php wp_editor( $option_value, esc_attr( $value['id'] ) ); ?>
		</td>
	</tr>
			<?php
		}
		// co registrant
		public function addf_gift_registry_email_syntax_co_registrant_val( $value, $option, $raw_value ) {
			update_option( $option['id'], $raw_value );
		}
		public function addf_gift_registry_email_syntax_co_registrant( $value ) {
			$option_value = WC_Admin_Settings::get_option( $value['id'], $value['default'] );
			?>
	<tr valign="top">
		<th scope="row" class="titledesc">
			<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?></label>
		</th>
		<td class="addf_gr addf_gr_editors addf_gr-<?php echo esc_html( $value['type'] ); ?>">
			<?php echo esc_html( $value['desc'] ); ?>
			<?php wp_editor( $option_value, esc_attr( $value['id'] ) ); ?>
		</td>
	</tr>
			<?php
		}
		// for text editor on share
		public function addf_gift_registry_share_email_syntax_val( $value, $option, $raw_value ) {
			update_option( $option['id'], $raw_value );
		}
		public function addf_gift_registry_share_email_syntax( $value ) {
			$option_value = WC_Admin_Settings::get_option( $value['id'], $value['default'] );
			?>
	<tr valign="top">
		<th scope="row" class="titledesc">
			<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?></label>
		</th>
		<td class="addf_gr addf_gr-<?php echo esc_html( $value['type'] ); ?> addf_gf_email_tab_ops">
			<?php echo esc_html( $value['desc'] ); ?>
			<?php wp_editor( $option_value, esc_attr( $value['id'] ) ); ?>
		</td>
	</tr>
			<?php
		}
		// text editor for new registry created  for admin
		public function addf_gift_registry_syntax_new_reg_mail_editor_admin_callback( $value, $option, $raw_value ) {
			update_option( $option['id'], $raw_value );
		}
		public function addf_gift_registry_syntax_new_reg_mail_editor_admin_cb( $value ) {
			$option_value = WC_Admin_Settings::get_option( $value['id'], $value['default'] );
			?>
	<tr valign="top">
		<th scope="row" class="titledesc">
			<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?></label>
		</th>
		<td class="addf_gr addf_gr-<?php echo esc_html( $value['type'] ); ?> addf_gf_email_tab_ops">
			<?php echo esc_html( $value['desc'] ); ?>
			<?php wp_editor( $option_value, esc_attr( $value['id'] ) ); ?>
		</td>
	</tr>
			<?php
		}
		// text editor for new registry created for registrant
		public function addf_gift_registry_new_reg_email_syntax_val( $value, $option, $raw_value ) {
			update_option( $option['id'], $raw_value );
		}
		public function addf_gift_registry_new_reg_email_syntax( $value ) {
			$option_value = WC_Admin_Settings::get_option( $value['id'], $value['default'] );
			?>
	<tr valign="top">
		<th scope="row" class="titledesc">
			<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?></label>
		</th>
		<td class="addf_gr addf_gr-<?php echo esc_html( $value['type'] ); ?> addf_gf_email_tab_ops">
			<?php echo esc_html( $value['desc'] ); ?>
			<?php wp_editor( $option_value, esc_attr( $value['id'] ) ); ?>
		</td>
	</tr>
			<?php
		}
		// text editor for new registry created for co registrant
		public function addf_gift_registry_co_reg_email_syntax_val( $value, $option, $raw_value ) {
			update_option( $option['id'], $raw_value );
		}
		public function addf_gift_registry_co_reg_email_syntax( $value ) {
			$option_value = WC_Admin_Settings::get_option( $value['id'], $value['default'] );
			?>
	<tr valign="top">
		<th scope="row" class="titledesc">
			<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?></label>
		</th>
		<td class="addf_gr addf_gr-<?php echo esc_html( $value['type'] ); ?> addf_gf_email_tab_ops">
			<?php echo esc_html( $value['desc'] ); ?>
			<?php wp_editor( $option_value, esc_attr( $value['id'] ) ); ?>
		</td>
	</tr>
			<?php
		}
		public function addf_gift_registry_expire_reg_email_syntax_admin_val( $value, $option, $raw_value ) {
			update_option( $option['id'], $raw_value );
		}
		public function addf_gift_registry_expire_reg_email_syntax_admin( $value ) {
			$option_value = WC_Admin_Settings::get_option( $value['id'], $value['default'] );
			?>
	<tr valign="top">
		<th scope="row" class="titledesc">
			<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?></label>
		</th>
		<td class="addf_gr addf_gr-<?php echo esc_html( $value['type'] ); ?> addf_gf_email_tab_ops">
			<?php echo esc_html( $value['desc'] ); ?>
			<?php wp_editor( $option_value, esc_attr( $value['id'] ) ); ?>
		</td>
	</tr>
			<?php
		}
		// registry expire for registrant
		public function addf_gift_expire_registry_syntax_mail_editor_val( $value, $option, $raw_value ) {
			update_option( $option['id'], $raw_value );
		}
		public function addf_gift_expire_registry_syntax_mail_editor( $value ) {
			$option_value = WC_Admin_Settings::get_option( $value['id'], $value['default'] );
			?>
	<tr valign="top">
		<th scope="row" class="titledesc">
			<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?></label>
		</th>
		<td class="addf_gr addf_gr-<?php echo esc_html( $value['type'] ); ?> addf_gf_email_tab_ops">
			<?php echo esc_html( $value['desc'] ); ?>
			<?php wp_editor( $option_value, esc_attr( $value['id'] ) ); ?>
		</td>
	</tr>
			<?php
		}
		public function addf_add_new_gift_registry() {
			add_meta_box(
				'addf_gift_registry_mb',
				esc_html__( 'Personal information', 'addf_giftr' ),
				array( $this, 'addf_add_new_gift_registry_cb' ),
				'addf_gift_registry'
			);
			add_meta_box(
				'addf_gift_registry_address',
				esc_html__( 'Address information', 'addf_giftr' ),
				array( $this, 'addf_add_new_gift_registry_address_cb' ),
				'addf_gift_registry'
			);
			add_meta_box(
				'addf_gift_registry_products',
				esc_html__( 'Products', 'addf_giftr' ),
				array( $this, 'addf_add_new_gift_registry_add_products_cb' ),
				'addf_gift_registry'
			);
		}
		// information metabox
		public function addf_add_new_gift_registry_cb() {
			wp_nonce_field( 'addify_gift_registry_nonce', 'addify_gift_registry_nonce' );
			?>
	<table class="addf_gr_bk_ship_addr_tb">
		<tr>
			<th colspan="2" class="addf-gr-bk-seperation-heading align-center">
				<h3>
					<?php echo esc_html__( 'Registrant Information', 'addf_giftr' ); ?>
				</h3>
			</th>
		</tr>
		<tr>
			<th class="width-text-th">
				<h4>
					<?php echo esc_html__( 'Visibility', 'addf_giftr' ); ?>
				</h4>
			</th>
			<td>
				<?php
				$addf_gr_visibility = get_post_meta( get_the_ID(), 'addf-gift-registry-visibility', true );
				if ( '' == $addf_gr_visibility ) {
					$addf_gr_visibility = '1';
				}
				?>
				<input type="radio" class="addf-gift-registry-visibility-pub addf-gift-registry-visibility_pub" name="addf-gift-registry-visibility" value="1" <?php checked( $addf_gr_visibility, '1' ); ?> >
				<label for="addf-gift-registry-visibility_pub"> <?php echo esc_html__( 'Public', 'addf_giftr' ); ?></label>
				&nbsp;&nbsp;&nbsp;
				<input type="radio" class="addf-gift-registry-visibility-pri addf-gift-registry-visibility_pri" name="addf-gift-registry-visibility" value="2" <?php checked( $addf_gr_visibility, '2' ); ?> >
				<label for="addf-gift-registry-visibility_pri"> <?php echo esc_html__( 'Password protected', 'addf_giftr' ); ?></label>
			</td>
		</tr>
		<tr class="addf-gift-registry-visibility-private-tr">
			<th class="width-text-th">
				<h4>
					<?php echo esc_html__( 'Password', 'addf_giftr' ); ?>
				</h4>
			</th>
			<td>
				<input type="password" class="addf-gift-registry-visibility-private-pass addf-gift-regsitry-input-fields addf-gr-input-bk-field" name="addf-gift-registry-visibility-private-pass" value="<?php echo esc_html__( get_post_meta( get_the_ID(), 'addf-gift-registry-visibility-private-pass', true ), 'addf_giftr' ); ?>">
			</td>
		</tr>
		<tr>
			<th class="width-text-th">
				<h4>
					<?php echo esc_html__( 'First Name', 'addf_giftr' ); ?><span class="red">&nbsp;*</span>
				</h4>
			</th>
			<td>
				<input type="text" required class="addf-gift-regsitry-input-fields addf-gr-input-bk-field" name="gift-registry-registrant-first-name" value="<?php echo esc_html__( get_post_meta( get_the_ID(), 'gift-registry-registrant-first-name', true ), 'addf_giftr' ); ?>">
			</td>
		</tr>
		<tr>
			<th class="width-text-th">
				<h4>
					<?php echo esc_html__( 'Last Name', 'addf_giftr' ); ?>
				</h4>
			</th>
			<td>
				<input type="text"  class="addf-gift-regsitry-input-fields addf-gr-input-bk-field" name="gift-registry-registrant-last-name" value="<?php echo esc_html__( get_post_meta( get_the_ID(), 'gift-registry-registrant-last-name', true ), 'addf_giftr' ); ?>">
			</td>
		</tr>
		<tr>
			<th class="width-text-th">
				<h4>
					<?php echo esc_html__( 'Email', 'addf_giftr' ); ?><span class="red">&nbsp;*</span>
				</h4>
			</th>
			<td>
				<input type="email" required class="addf-gift-regsitry-input-fields addf-gr-input-bk-field" name="gift-registry-registrant-email" value="<?php echo esc_html__( get_post_meta( get_the_ID(), 'gift-registry-registrant-email', true ), 'addf_giftr' ); ?>">
			</td>
		</tr>
		<tr>
			<th colspan="2" class="addf-gr-bk-seperation-heading">
				<h3>
					<?php echo esc_html__( 'Co-Registrant Information', 'addf_giftr' ); ?>
				</h3>
			</th>
		</tr>
		<tr>
			<th class="width-text-th">
				<h4>
					<?php echo esc_html__( 'First Name', 'addf_giftr' ); ?>
				</h4>
			</th>
			<td>
				<input type="text" class="addf-gift-regsitry-input-fields addf-gr-input-bk-field" name="gift-registry-co-registrant-first" value="<?php echo esc_html__( get_post_meta( get_the_ID(), 'gift-registry-co-registrant-first', true ), 'addf_giftr' ); ?>">
			</td>
		</tr>
		<tr>
			<th class="width-text-th">
				<h4>
					<?php echo esc_html__( 'Last Name', 'addf_giftr' ); ?>
				</h4>
			</th>
			<td>
				<input type="text" class="addf-gift-regsitry-input-fields addf-gr-input-bk-field" name="gift-registry-co-registrant-last-name" value="<?php echo esc_html__( get_post_meta( get_the_ID(), 'gift-registry-co-registrant-last-name', true ), 'addf_giftr' ); ?>">
			</td>
		</tr>
		<tr>
			<th class="width-text-th">
				<h4>
					<?php echo esc_html__( 'Email', 'addf_giftr' ); ?>
				</h4>
			</th>
			<td>
				<input type="email" class="addf-gift-regsitry-input-fields addf-gr-input-bk-field" name="gift-registry-co-registrant-email" value="<?php echo esc_html__( get_post_meta( get_the_ID(), 'gift-registry-co-registrant-email', true ), 'addf_giftr' ); ?>">
			</td>
		</tr>
	</table>
			<?php
		}
		// address metabox
		public function addf_add_new_gift_registry_address_cb() {
			?>
	<table class=" addf_gr_bk_ship_addr_tb ">
		<tr>
			<th colspan="6" class="addf-gr-bk-seperation-heading">
				<h3><?php echo esc_html__( 'Event Information', 'addf_giftr' ); ?></h3>
			</th>
		</tr>
		<tr>
			<th>
				<h4>
					<?php echo esc_html__( 'Event Date', 'addf_giftr' ); ?><span class="red">&nbsp;*</span>
				</h4>
			</th>
			<td colspan="2">
				<input type="date" required class="addf-gift-registry-event-date  addf-gr-input-bk-field addf-gift-regsitry-input-fields" name="gift-registry-event-info-date" value="<?php echo esc_html__( get_post_meta( get_the_ID(), 'gift-registry-event-info-date', true ), 'addf_giftr' ); ?>">
			</td>
			<th>
				<h4>
					<?php echo esc_html__( 'Event Location', 'addf_giftr' ); ?>
				</h4>
			</th>
			<td colspan="2">
				<input type="text" class="addf-gr-input-bk-field addf-gift-regsitry-input-fields" name="gift-registry-event-info-location" value="<?php echo esc_html__( get_post_meta( get_the_ID(), 'gift-registry-event-info-location', true ), 'addf_giftr' ); ?>">
			</td>
		</tr>
		<tr>
			<th >
				<h4>
					<?php echo esc_html__( 'Event Message', 'addf_giftr' ); ?>
				</h4>
			</th>
			<td colspan="2">
				<input type="text" class="addf-gr-input-bk-field addf-gift-regsitry-input-fields" name="gift-registry-event-info-message" value="<?php echo esc_html__( get_post_meta( get_the_ID(), 'gift-registry-event-info-message', true ), 'addf_giftr' ); ?>">
			</td>
		</tr>
			<?php
				$addf_gr_user_id = get_post_meta( get_the_ID(), 'gift-registry-registrant-user_is_wp', true );
			
			// if ( '0' == $addf_gr_user_id ) {
				$shipping_first_name = get_post_meta( get_the_ID(), 'gift_registry_guest_ship_f_name', true );
				$shipping_last_name  = get_post_meta( get_the_ID(), 'gift_registry_guest_ship_l_name', true );
				$shipping_company    = get_post_meta( get_the_ID(), 'gift_registry_guest_ship_comp_name', true );
				$shipping_address_1  = get_post_meta( get_the_ID(), 'gift_registry_guest_shipping_address', true );
				$shipping_city       = get_post_meta( get_the_ID(), 'gift_registry_guest_shipping_city', true );
				$shipping_postcode   = get_post_meta( get_the_ID(), 'gift_registry_guest_shipping_post_code', true );
				$shipping_country    = get_post_meta( get_the_ID(), 'gift_registry_guest_shipping_country', true );
				$shipping_state      = get_post_meta( get_the_ID(), 'gift_registry_guest_shipping_state', true );

				//new registry creation
			if ('' == $shipping_first_name && '' == $addf_gr_user_id) {
				$shipping_first_name = get_user_meta( get_current_user_id(), 'shipping_first_name', true );
				$shipping_last_name  = get_user_meta( get_current_user_id(), 'shipping_last_name', true );
				$shipping_company    = get_user_meta( get_current_user_id(), 'shipping_company', true );
				$shipping_address_1  = get_user_meta( get_current_user_id(), 'shipping_address_1', true );
				$shipping_address_2  = get_user_meta( get_current_user_id(), 'shipping_address_2', true );
					
				$shipping_city     = get_user_meta( get_current_user_id(), 'shipping_city', true );
				$shipping_postcode = get_user_meta( get_current_user_id(), 'shipping_postcode', true );
				$shipping_country  = get_user_meta( get_current_user_id(), 'shipping_country', true );
				$shipping_state    = get_user_meta( get_current_user_id(), 'shipping_state', true );
			}


			?>
			<tr >
				<th colspan="6" class="addf-gr-bk-seperation-heading">
					<h3><?php echo esc_html__( 'Shipping Address', 'addf_giftr' ); ?></h3>
				</th>
			</tr>
			<tr >
				<th>
					<h4><?php echo esc_html__( 'First Name', 'addf_giftr' ); ?><span class="red">&nbsp;*</span></h4>
				</th>
				<td colspan="2">
					<input class="addf-gr-input-bk-field" required type="text" name="gift_registry_guest_ship_f_name" value="<?php echo esc_html__( $shipping_first_name, 'addf_giftr' ); ?>">
				</td>
				<th>
					<h4><?php echo esc_html__( 'Last Name', 'addf_giftr' ); ?><span class="red">&nbsp;*</span></h4>
				</th>
				<td colspan="2">
					<input class="addf-gr-input-bk-field" required type="text" name="gift_registry_guest_ship_l_name" value="<?php echo esc_html__( $shipping_last_name, 'addf_giftr' ); ?>">
				</td>
			</tr>
			<tr >
				<th>
					<h4><?php echo esc_html__( 'Company', 'addf_giftr' ); ?></h4>
				</th>
				<td colspan="2">
					<input class="addf-gr-input-bk-field" type="text" name="gift_registry_guest_ship_comp_name" value="<?php echo esc_html__( $shipping_company, 'addf_giftr' ); ?>">
				</td>
				<th>
					<h4><?php echo esc_html__( 'Address', 'addf_giftr' ); ?><span class="red">&nbsp;*</span></h4>
				</th>
				<td colspan="2">
					<input class="addf-gr-input-bk-field" required type="text" name="gift_registry_guest_shipping_address" value="<?php echo esc_html__( $shipping_address_1, 'addf_giftr' ); ?>">
				</td>
			</tr>
			<tr >
				<th>
					<h4><?php echo esc_html__( 'City', 'addf_giftr' ); ?><span class="red">&nbsp;*</span></h4>
				</th>
				<td colspan="2">
					<input class="addf-gr-input-bk-field" required type="text" name="gift_registry_guest_shipping_city" value="<?php echo esc_html__( $shipping_city, 'addf_giftr' ); ?>">
				</td>
				<th>
					<h4><?php echo esc_html__( 'Postcode', 'addf_giftr' ); ?><span class="red">&nbsp;*</span></h4>
				</th>
				<td colspan="2">
					<input class="addf-gr-input-bk-field" required type="text" name="gift_registry_guest_shipping_post_code" value="<?php echo esc_html__( $shipping_postcode, 'addf_giftr' ); ?>">
				</td>
			</tr>
			<tr >
				<th>
					<h4><?php echo esc_html__( 'Country', 'addf_giftr' ); ?><span class="red">&nbsp;*</span></h4>
				</th>
				<td colspan="2">
					<select name="gift_registry_guest_shipping_country" required class="addf_gr_countries_field_height addf_gr_countries_billing_front title-gift-registry-input" >
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
				</td>
				<th>
					<h4><?php echo esc_html__( 'State', 'addf_giftr' ); ?><span class="red">&nbsp;*</span></h4>
				</th>
				<td colspan="2">
					<div class="addf_gr_guest_state">
					<?php
					if ( '' == $shipping_country ) {
						?>
							<select name="gift_registry_guest_shipping_state" required class="addf_gr_countries_field_height title-gift-registry-input gift_registry_guest_shipping_state">
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
								<select name="gift_registry_guest_shipping_state" class="addf_gr_countries_field_height title-gift-registry-input gift_registry_guest_shipping_state">
								<?php
								foreach ( $addf_gr_state as $key => $value ) {
									?>
										<option <?php selected( $shipping_state, $key, true )  || selected( $shipping_state, $value, true )  ; ?>><?php echo esc_html__( $value ); ?> </option>
										<?php
								}
								?>
								</select>
								<?php
						} else {
							?>
								<input type="text" name="gift_registry_guest_shipping_state" required class="addf_gr_countries_field_height title-gift-registry-input gift_registry_guest_shipping_state " value="<?php echo esc_html__( $shipping_state, 'addf_giftr' ); ?>" placeholder="<?php echo esc_html__( 'Enter State', 'addf_giftr' ); ?>">
								<?php
						}
					}
					?>
					</div>
				</td>
			</tr>
				<?php
			// }
				?>
	</table>
			<?php
		}
		// add product metabox
		public function addf_add_new_gift_registry_add_products_cb() {
			?>
	<table  class="wp-list-table widefat fixed striped table-view-list addf_gr_be_table <?php echo esc_attr( 'existing-gift-registry-data-table_replace_data' . get_the_ID() ); ?>">
		<thead>
			
			<tr>
				<th class="addf_gr_bk_img_td">
					<?php echo esc_html__( 'Image', 'addf_giftr' ); ?>
				</th>
				<th class="addf_gr_bk_name">
					<?php echo esc_html__( ' Name', 'addf_giftr' ); ?>
				</th>
				<th  class="addf_gr_bk_prc">
					<?php echo esc_html__( ' Price', 'addf_giftr' ); ?>
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
			$addf_gift_registry_product = get_post_meta( get_the_ID(), 'addf_gift_registry_product', true );
			if ( is_array( $addf_gift_registry_product ) ) {
				if ( 1 < count( $addf_gift_registry_product ) ) {
					foreach ( $addf_gift_registry_product as $key => $addf_g_r_single_product ) {
						if ( ' ' == $addf_g_r_single_product ) {
							continue;
						}
						if ( ! wc_get_product( $addf_g_r_single_product ) ) {
							continue;
						}
						?>
						<tr>
							<td class="addf_gr_bk_img_td">
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
							<td  class="addf_gr_bk_name">
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
											<span><?php echo esc_html__( $value_of_main_attr, 'addf_giftr' ); ?></span>
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
										<span><?php echo esc_html__( $value, 'addf_giftr' ); ?></span>
										<?php
									}
								}
								?>
								<input type="hidden" name="addf_gr_post_id" value="<?php echo esc_attr( get_the_ID() ); ?>"  >
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
								$var_for_singlr_product     = get_post_meta( get_the_ID(), 'addf_gr_product_quantity', true );
								$var_for_singlr_product_rec = get_post_meta( get_the_ID(), 'addf_gr_product_quantity_recieved', true );
								if ( array_key_exists( $key, (array) $var_for_singlr_product_rec ) ) {
									$addf_gr_min_desired_qty = $var_for_singlr_product_rec[ $key ];
								} else {
									$addf_gr_min_desired_qty = 1;
								}
								if ( array_key_exists( $key, (array) $var_for_singlr_product ) ) {
									$var_for_singlr_product_qty = $var_for_singlr_product[ $key ];
								} else {
									$var_for_singlr_product_qty = 1;
								}
								?>

								<input class="addf-gr-desire-product" min="<?php echo esc_attr( $addf_gr_min_desired_qty ); ?>" max=""  value="<?php echo esc_attr( $var_for_singlr_product_qty ); ?>" type="number" name="addf_gr_product_quantity[<?php echo esc_attr( $key ); ?>]" >
							</td>
							<td  class="addf_gr_bk_rd_qty">
								<!-- Recieved Products -->
								<?php
								$var_for_singlr_product_rec = get_post_meta( get_the_ID(), 'addf_gr_product_quantity_recieved', true );
								if ( array_key_exists( $key, (array) $var_for_singlr_product_rec ) ) {
									$addf_gr_min_received_qty = $var_for_singlr_product_rec[ $key ];
								} else {
									$addf_gr_min_received_qty = 0;
								}
								echo esc_attr( $addf_gr_min_received_qty );
								?>
							</td>
							<td class=" addf_gr_bk_rd_qty del_addf_single_product">
								<input type="hidden" readonly class="addf-delete-product-from-registry_post_id_val" value="<?php echo esc_attr( get_the_ID() ); ?>" >                                            
								<span class="fa addf-delete-product-from-registry"  data-id="<?php echo esc_attr( $key ) . ':' . esc_attr( get_the_ID() ); ?>"> <span class="fa fa-trash " style="font-size:25px;"></span> </span>
							</td>
						</tr>
						<?php
					}
				} else {
					?>
					<tr>
						<td colspan="6" class="align-center">
							<h3><?php echo esc_html__( 'No product found', 'addf_giftr' ); ?></h3>
						</td>
					</tr>
					<?php
				}
			} else {
				?>
				<tr>
					<td colspan="6" class="align-center">
						<h3><?php echo esc_html__( 'No product found', 'addf_giftr' ); ?></h3>
					</td>
				</tr>
				<?php
			}
			?>
		</tbody>
	</table>

	<table class="addf_gr_be_table">
		<tr>
			<td colspan="6" class="align-right addf-add-product-from-registry-td" >
				<input type="button" class="addf-add-product-from-registry button button-primary button-large" value="Add a Product" data-id="<?php echo esc_attr(get_the_ID()); ?>">
			</td>
		</tr>
	</table>
	<!--  add single product popup -->
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
				<br><span class="addf-gr-result-configuration"></span>
			</tr>
			<tr>
				<td colspan="6">
					<div class="addf_gr_select_attr_popup"></div>
				</td>
			</tr>
			<tr>
				<td colspan="6" class="addf-add-new-product-div-article-table">
					<input type="button" class="addf-add-product-from-registry-single-product button button-primary button-large" value="<?php echo esc_html__( 'Add product', 'addf_giftr' ); ?>">
					<input type="button" class="addf-g-r-bg-cover-hide-btn button button-primary button-large" value="<?php echo esc_html__( 'Cancel', 'addf_giftr' ); ?>">
				</td>
			</tr>
		</table>
	</div>
			<?php
		}
		public function addf_save_new_gift_registry_save_metaData( $post_id, $post ) {
			//For custom post type:
			$exclude_statuses = array(
				'auto-draft',
				'trash',
			);

			$action = isset( $_GET['action'] ) ? sanitize_text_field( wp_unslash( $_GET['action'] ) ) : '';
			
			if ( in_array( get_post_status($post_id), $exclude_statuses ) ) {
				return;

			}
			if (  'untrash' == $action ) {
				return;

			}
			if (  is_ajax()  ) {
				return;

			}

				$nonce = isset( $_POST['addify_gift_registry_nonce'] ) ? sanitize_text_field( $_POST['addify_gift_registry_nonce'] ) : '';

			if ( ! wp_verify_nonce( $nonce, 'addify_gift_registry_nonce' ) ) {

				wp_die( esc_html__( 'Security Violated!', 'addf_giftr' ) );

			}

			if ( '' == get_post_meta($post_id, 'gift-registry-registrant-user_is_wp', true)) {
				update_post_meta($post_id, 'gift-registry-registrant-user_is_wp', get_current_user_id());
			}
			
			if ( isset( $_POST['addf-gift-registry-visibility'] ) ) {
				update_post_meta( $post_id, 'addf-gift-registry-visibility', sanitize_meta( '', wp_unslash( $_POST['addf-gift-registry-visibility'] ), 'post' ) );
			}
			if ( isset( $_POST['addf-gift-registry-visibility-private-pass'] ) ) {
				update_post_meta( $post_id, 'addf-gift-registry-visibility-private-pass', sanitize_meta( '', wp_unslash( $_POST['addf-gift-registry-visibility-private-pass'] ), 'post' ) );
			}
			if ( isset( $_POST['gift-registry-registrant-first-name'] ) ) {
				update_post_meta( $post_id, 'gift-registry-registrant-first-name', sanitize_meta( '', wp_unslash( $_POST['gift-registry-registrant-first-name'] ), 'post' ) );
			}
			if ( isset( $_POST['gift-registry-registrant-last-name'] ) ) {
				update_post_meta( $post_id, 'gift-registry-registrant-last-name', sanitize_meta( '', wp_unslash( $_POST['gift-registry-registrant-last-name'] ), 'post' ) );
			}
			if ( isset( $_POST['gift-registry-registrant-email'] ) ) {
				update_post_meta( $post_id, 'gift-registry-registrant-email', sanitize_meta( '', wp_unslash( $_POST['gift-registry-registrant-email'] ), 'post' ) );
			}
			if ( isset( $_POST['gift-registry-co-registrant-first'] ) ) {
				update_post_meta( $post_id, 'gift-registry-co-registrant-first', sanitize_meta( '', wp_unslash( $_POST['gift-registry-co-registrant-first'] ), 'post' ) );
			}
			if ( isset( $_POST['gift-registry-co-registrant-last-name'] ) ) {
				update_post_meta( $post_id, 'gift-registry-co-registrant-last-name', sanitize_meta( '', wp_unslash( $_POST['gift-registry-co-registrant-last-name'] ), 'post' ) );
			}
			if ( isset( $_POST['gift-registry-co-registrant-email'] ) ) {
				update_post_meta( $post_id, 'gift-registry-co-registrant-email', sanitize_meta( '', wp_unslash( $_POST['gift-registry-co-registrant-email'] ), 'post' ) );
			}
			if ( isset( $_POST['gift-registry-event-info-date'] ) ) {
				update_post_meta( $post_id, 'gift-registry-event-info-date', sanitize_meta( '', wp_unslash( $_POST['gift-registry-event-info-date'] ), 'post' ) );
			}
			if ( isset( $_POST['gift-registry-event-info-location'] ) ) {
				update_post_meta( $post_id, 'gift-registry-event-info-location', sanitize_meta( '', wp_unslash( $_POST['gift-registry-event-info-location'] ), 'post' ) );
			}
			if ( isset( $_POST['gift-registry-event-info-message'] ) ) {
				update_post_meta( $post_id, 'gift-registry-event-info-message', sanitize_meta( '', wp_unslash( $_POST['gift-registry-event-info-message'] ), 'post' ) );
			}
			if ( isset( $_POST['addf_gr_product_quantity'] ) ) {
				update_post_meta( $post_id, 'addf_gr_product_quantity', sanitize_meta( '', wp_unslash( $_POST['addf_gr_product_quantity'] ), 'post' ) );
			}
					// shipping address
			if ( isset( $_POST['gift_registry_guest_ship_f_name'] ) ) {
				update_post_meta( $post_id, 'gift_registry_guest_ship_f_name', sanitize_meta( '', wp_unslash( $_POST['gift_registry_guest_ship_f_name'] ), 'post' ) );
			}
			if ( isset( $_POST['gift_registry_guest_ship_l_name'] ) ) {
				update_post_meta( $post_id, 'gift_registry_guest_ship_l_name', sanitize_meta( '', wp_unslash( $_POST['gift_registry_guest_ship_l_name'] ), 'post' ) );
			}
			if ( isset( $_POST['gift_registry_guest_ship_comp_name'] ) ) {
				update_post_meta( $post_id, 'gift_registry_guest_ship_comp_name', sanitize_meta( '', wp_unslash( $_POST['gift_registry_guest_ship_comp_name'] ), 'post' ) );
			}
			if ( isset( $_POST['gift_registry_guest_shipping_address'] ) ) {
				update_post_meta( $post_id, 'gift_registry_guest_shipping_address', sanitize_meta( '', wp_unslash( $_POST['gift_registry_guest_shipping_address'] ), 'post' ) );
			}
			if ( isset( $_POST['gift_registry_guest_shipping_city'] ) ) {
				update_post_meta( $post_id, 'gift_registry_guest_shipping_city', sanitize_meta( '', wp_unslash( $_POST['gift_registry_guest_shipping_city'] ), 'post' ) );
			}
			if ( isset( $_POST['gift_registry_guest_shipping_post_code'] ) ) {
				update_post_meta( $post_id, 'gift_registry_guest_shipping_post_code', sanitize_meta( '', wp_unslash( $_POST['gift_registry_guest_shipping_post_code'] ), 'post' ) );
			}
			if ( isset( $_POST['gift_registry_guest_shipping_country'] ) ) {
				update_post_meta( $post_id, 'gift_registry_guest_shipping_country', sanitize_meta( '', wp_unslash( $_POST['gift_registry_guest_shipping_country'] ), 'post' ) );
			}
			if ( isset( $_POST['gift_registry_guest_shipping_state'] ) ) {
				update_post_meta( $post_id, 'gift_registry_guest_shipping_state', sanitize_meta( '', wp_unslash( $_POST['gift_registry_guest_shipping_state'] ), 'post' ) );
			}
		}
		public function addf_gift_registry_getproductsearch_cb() {
			$return = array();
			if ( isset( $_GET['q'] ) ) {
				$search = sanitize_text_field( wp_unslash( $_GET['q'] ) );
			}
			$nonce = isset( $_GET['addify_gift_registry_nonce'] ) ? sanitize_text_field( $_GET['addify_gift_registry_nonce'] ) : '';

			if ( ! wp_verify_nonce( $nonce, 'addify_gift_registry_nonce' ) ) {

				wp_die( esc_html__( 'Security Violated!', 'addf_giftr' ) );

			}

			$search_results = new WP_Query(
				array(
					's'              => $search,
					'post_type'      => array( 'product', 'product_variation' ),
					'post_status'    => 'publish',
					'posts_per_page' => -1,
				)
			);
			if ( $search_results->have_posts() ) :
				while ( $search_results->have_posts() ) :
					$search_results->the_post();
					global $product;
					if ( ( '' != $product->is_on_backorder() ) || ( ( '' != $product->is_in_stock() ) && ( '' != $product->get_price() ) ) ) {
						if ( $product->is_type( 'simple' ) || ( $product->is_type( 'variation' ) && 'publish' == get_post_status( $product->get_parent_id() ) ) ) {
							$title    = ( mb_strlen( $search_results->post->post_title ) > 50 ) ? mb_substr( $search_results->post->post_title, 0, 49 ) . '...' : $search_results->post->post_title;
							$return[] = array( $search_results->post->ID, $title );
						}
					}
				endwhile;
			endif;
			wp_send_json( $return );
		}
		public function addf_gr_single_Product_and_variation() {
			$return = array();
			if ( isset( $_GET['q'] ) ) {
				$search = sanitize_text_field( wp_unslash( $_GET['q'] ) );
			}

			$nonce = isset( $_GET['addify_gift_registry_nonce'] ) ? sanitize_text_field( $_GET['addify_gift_registry_nonce'] ) : '';

			if ( ! wp_verify_nonce( $nonce, 'addify_gift_registry_nonce' ) ) {

				wp_die( esc_html__( 'Security Violated!', 'addf_giftr' ) );

			}

			$search_results = new WP_Query(
				array(
					's'              => $search,
					'post_type'      => array( 'product', 'product_variation' ),
					'post_status'    => 'publish',
					'posts_per_page' => -1,
				)
			);
			if ( $search_results->have_posts() ) :
				while ( $search_results->have_posts() ) :
					$search_results->the_post();
					global $product;
					if ( ( '' != $product->is_on_backorder() ) || ( ( '' != $product->is_in_stock() ) && ( '' != $product->get_price() ) ) ) {
						if ( $product->is_type( 'simple' ) || ( $product->is_type( 'variation' ) && 'publish' == get_post_status( $product->get_parent_id() ) ) ) {
							$title    = ( mb_strlen( $search_results->post->post_title ) > 50 ) ? mb_substr( $search_results->post->post_title, 0, 49 ) . '...' : $search_results->post->post_title;
							$return[] = array( $search_results->post->ID, $title );
						}
					}
				endwhile;
			endif;
			wp_send_json( $return );
		}
		public function addf_gift_registry_add_cron_interval( $schedules ) {
			$schedules['addf_gift_registry_cron_time'] = array(
				'interval' => 1000,
				'display'  => '17minutes',
			);
			return $schedules;
		}
		public function addf_gift_registry_schedule_call_back() {
			if ( ! wp_next_scheduled( 'addf_gr_crone_time' ) ) {
				wp_schedule_event( time(), 'addf_gift_registry_cron_time', 'addf_gr_crone_time' );
			}
		}
		public function addf_gift_registry_gr_is_expired() {

			$addf_gr_args = array(
				'post_type'      => 'addf_gift_registry',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'orderby'        => 'id',
				'order'          => 'desc',
			);
			$addf_gr_loop = new WP_Query( $addf_gr_args );
			while ( $addf_gr_loop->have_posts() ) :
				$addf_gr_loop->the_post();
				$post                   = $addf_gr_loop->get_post();
				$addf_gr_reg_event_date = get_post_meta( get_the_ID(), 'gift-registry-event-info-date', true );
				$addf_gr_reg_get_date   = get_post_meta( get_the_ID(), 'gift-registry-event-info-expire-date', true );

				$addf_gr_db_date = strtotime( $addf_gr_reg_event_date );
				$today           = gmdate( 'Y-m-d' );
				$now_date        = strtotime( $today );
				$datediff        = $addf_gr_db_date - $now_date;
				$time_calculated = round( $datediff / ( 60 * 60 * 24 ) );
				update_option( 'test_crone_gift_registry', 'time calculated is ' . $time_calculated );

				if ( 1 > $time_calculated ) {
					if ( 'expired' != $addf_gr_reg_get_date ) {
						update_post_meta( get_the_ID(), 'gift-registry-event-info-expire-date', 'expired' );
						WC()->mailer()->emails['addf_gr_expire_registry_wp_email']->trigger( get_the_ID() );
					}
				} else {
					update_post_meta( get_the_ID(), 'gift-registry-event-info-expire-date', 'active' );
				}
			endwhile;
		}
		public function crone_deactivation() {
			wp_clear_scheduled_hook( 'addf_gr_crone_time' );
			die();
		}

		public function addf_gift_registry_display_greeting_message_in_order( $item_id, $item, $product ) {
			$addf_gr_greeting_message = (array) wc_get_order_item_meta( $item_id, 'addf_greeting_message', true );

			foreach ($addf_gr_greeting_message as $key=>$value) {
				if ('' == $value) {
					continue;
				}
				if ('Greeting Message' == $key ) {
					echo '<br><b>' . esc_attr($key) . '</b> : ' . esc_attr($value);
				}
				
			}
		}
	}

	new AF_Gift_Registry_Admin();
}
