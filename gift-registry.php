<?php
	/**
	* Plugin Name:  Gift Registry for WooCommerce
	* Plugin URI: https://addify.store/product/woocommerce-gift-registry/
	* Description: Allow your customers to create multiple gift registries and share with their friends and family so they buy gifts for the upcoming events.
	* Author: Addify
	* Author URI: https://addify.store/
	* Version: 1.2.0
	* Domain Path:       /languages
	* Text Domain:       addf_giftr
	* License:           GNU General Public License v3.0
	* License URI:       http://www.gnu.org/licenses/gpl-3.0.html
	* WC requires at least: 3.0.9
	* WC tested up to: 8.*.*
	*/

	
if ( ! class_exists('AF_Gift_Registry_Main') ) {

	class AF_Gift_Registry_Main {

		public function __construct() {
			$this->addfl_global_constents_vars();

			add_action( 'plugins_loaded', array( $this, 'af_gf_init' ) );
			add_action('init', array( $this, 'af_gf_admin_init' ));

			// HOPS compatibility.
			add_action('before_woocommerce_init', array( $this, 'af_gf_HOPS_Compatibility' ));

			add_action('woocommerce_init', array( $this, 'addf_gift_registry_add_customer_session' ));
		}


		public function af_gf_HOPS_Compatibility() {

			if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
			}
		}

		public function af_gf_init() {

			// Check the installation of WooCommerce module if it is not a multi site.
			if ( ! is_multisite() && ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {

				add_action( 'admin_notices', array( $this, 'af_ev_check_wocommerce' ));
			}
		}
		public function af_ev_check_wocommerce() {
			// Deactivate the plugin.
			deactivate_plugins( __FILE__ );
			?>
				<div id="message" class="error">
					<p>
						<strong>
						<?php esc_html_e( 'Gift Registry for WooCommerce plugin is inactive. WooCommerce plugin must be active in order to activate it.', 'addf_giftr' ); ?>
						</strong>
					</p>
				</div>
				<?php
		}
		public function af_gf_admin_init() {

			if ( defined('WC_PLUGIN_FILE') ) {

				if ( is_admin() ) {
					include_once ADDF_GR_DIR . '/admin/class-gift-registry-admin.php';
				} else {
					include_once ADDF_GR_DIR . '/front/class-gift-registry-front.php';
				}


				add_action( 'wp_loaded', array( $this, 'addf_gift_registry_load_text_domain' ) );

				//add greeting message to cart[new feature]
				add_filter( 'woocommerce_add_cart_item_data', array( $this, 'addf_gift_registry_add_greeting_message_to_cart_item' ), 1, 4 );

				// insert new
				add_action( 'wp_ajax_addf_gift_registry_add_a_new_product_from_registry', array( $this, 'addf_gift_registry_add_a_new_product_from_registry_cb' ) );
				add_action( 'wp_ajax_nopriv_addf_gift_registry_add_a_new_product_from_registry', array( $this, 'addf_gift_registry_add_a_new_product_from_registry_cb' ) );

				//insert greeting message[new feature]
				add_action( 'wp_ajax_addf_gift_registry_add_a_greeting_message_from_registry', array( $this, 'addf_gift_registry_add_a_greeting_message_from_registry_cb' ) );
				add_action( 'wp_ajax_nopriv_addf_gift_registry_add_a_greeting_message_from_registry', array( $this, 'addf_gift_registry_add_a_greeting_message_from_registry_cb' ) );

				//delete greeting message[new feature]
				add_action( 'wp_ajax_addf_gift_registry_delete_a_greeting_message_from_registry', array( $this, 'addf_gift_registry_delete_a_greeting_message_from_registry_cb' ) );
				add_action( 'wp_ajax_nopriv_addf_gift_registry_delete_a_greeting_message_from_registry', array( $this, 'addf_gift_registry_delete_a_greeting_message_from_registry_cb' ) );

				//delete registry from front[new feature]
				add_action( 'wp_ajax_addf_gift_registry_delete_registry', array( $this, 'addf_gift_registry_delete_registry_cb' ) );
				add_action( 'wp_ajax_nopriv_addf_gift_registry_delete_registry', array( $this, 'addf_gift_registry_delete_registry_cb' ) );

				//search registry at front[new feature]
				add_action( 'wp_ajax_addf_gift_registry_search', array( $this, 'addf_gift_registry_search_cb' ) );
				add_action( 'wp_ajax_nopriv_addf_gift_registry_search', array( $this, 'addf_gift_registry_search_cb' ) );

				// add attribute
				add_action( 'wp_ajax_addf_gift_registry_add_a_product_attr_selection', array( $this, 'addf_gift_registry_add_a_product_attr_selection_cb' ) );
				add_action( 'wp_ajax_nopriv_addf_gift_registry_add_a_product_attr_selection', array( $this, 'addf_gift_registry_add_a_product_attr_selection_cb' ) );

				// insert new product from btn
				add_action( 'wp_ajax_addf_gift_registry_add_a_product_through_button', array( $this, 'addf_gift_registry_add_a_product_through_button_cb' ) );
				add_action( 'wp_ajax_nopriv_addf_gift_registry_add_a_product_through_button', array( $this, 'addf_gift_registry_add_a_product_through_button_cb' ) );

				// del a product from regustry
				add_action( 'wp_ajax_addf_gift_registry_delete_a_product_from_registry', array( $this, 'addf_gift_registry_delete_a_product_from_registry_cb' ) );
				add_action( 'wp_ajax_nopriv_addf_gift_registry_delete_a_product_from_registry', array( $this, 'addf_gift_registry_delete_a_product_from_registry_cb' ) );

				// for check single product to cart
				add_action( 'wp_ajax_addf_gift_registry_check_qty_single', array( $this, 'addf_gift_registry_check_qty_single_cb' ) );
				add_action( 'wp_ajax_nopriv_addf_gift_registry_check_qty_single', array( $this, 'addf_gift_registry_check_qty_single_cb' ) );
					
				// for adding single product to cart
				add_action( 'wp_ajax_addf_gift_registry_add_to_cat_single', array( $this, 'addf_gift_registry_add_to_cat_single_cb' ) );
				add_action( 'wp_ajax_nopriv_addf_gift_registry_add_to_cat_single', array( $this, 'addf_gift_registry_add_to_cat_single_cb' ) );
					
				// add to cart bulk
				add_action( 'wp_ajax_addf_gift_registry_add_to_cat_bulk_action', array( $this, 'addf_gift_registry_add_to_cat_bulk_action_cb' ) );
				add_action( 'wp_ajax_nopriv_addf_gift_registry_add_to_cat_bulk_action', array( $this, 'addf_gift_registry_add_to_cat_bulk_action_cb' ) );
				
				// for  is in stock or not variable product
				add_action( 'wp_ajax_addf_gift_registry_check_is_instock', array( $this, 'addf_gift_registry_check_is_instock_cb' ) );
				add_action( 'wp_ajax_nopriv_addf_gift_registry_check_is_instock', array( $this, 'addf_gift_registry_check_is_instock_cb' ) );

				// for  state of guest
				add_action( 'wp_ajax_addf_gr_search_country_state_ajax', array( $this, 'addf_gr_search_country_state_ajax_cb' ) );
				add_action( 'wp_ajax_nopriv_addf_gr_search_country_state_ajax', array( $this, 'addf_gr_search_country_state_ajax_cb' ) );

				add_filter( 'woocommerce_email_classes', array( $this, 'addf_gift_registry_send_mail' ), 90, 1 );

				// expire gift registry
				add_action( 'wp_loaded', array( $this, 'addf_gift_registry_gr_is_expired_cb' ) );

				// register post type.

				$labels = array(
					'name'               => esc_html__( 'Gift Registry', 'addf_giftr' ),
					'singular_name'      => esc_html__( 'Gift Registry', 'addf_giftr' ),
					'menu_name'          => esc_html__( 'Gift Registry', 'addf_giftr' ),
					'parent_item_colon'  => esc_html__( 'Parent Item:', 'addf_giftr' ),
					'all_items'          => esc_html__( 'Gift Registry', 'addf_giftr' ),
					'view_item'          => esc_html__( 'View Messages', 'addf_giftr' ),
					'add_new_item'       => esc_html__( 'Add New Registry', 'addf_giftr' ),
					'add_new'            => esc_html__( 'Add New Registry', 'addf_giftr' ),
					'edit_item'          => esc_html__( 'Edit Registry', 'addf_giftr' ),
					'update_item'        => esc_html__( 'Update Registry', 'addf_giftr' ),
					'search_items'       => esc_html__( 'Search Registry', 'addf_giftr' ),
					'not_found'          => esc_html__( 'Not found', 'addf_giftr' ),
					'not_found_in_trash' => esc_html__( 'Not found in Trash', 'addf_giftr' ),
				);
				$args   = array(
					'label'               => esc_html__( 'addf_gift_registry', 'addf_giftr' ),
					'description'         => esc_html__( ' Gift Registry', 'addf_giftr' ),
					'labels'              => $labels,
					'supports'            => array( 'title' ),
					'hierarchical'        => false,
					'public'              => true,
					'show_ui'             => true,
					'show_in_menu'        => 'woocommerce',
					'show_in_nav_menus'   => false,
					'show_in_admin_bar'   => false,
					'can_export'          => true,
					'has_archive'         => true,
					'exclude_from_search' => false,
					'publicly_queryable'  => true,
					'capability_type'     => 'post',
				);
				register_post_type( 'addf_gift_registry', $args );

			}
		}


		public function addf_gift_registry_search_cb() {

			$nonce = isset( $_POST['addify_gift_registry_nonce'] ) ? sanitize_text_field( $_POST['addify_gift_registry_nonce'] ) : '';

			if ( ! wp_verify_nonce( $nonce, 'addify_gift_registry_nonce' ) ) {

				wp_die( esc_html__( 'Security Violated!', 'addf_giftr' ) );

			}

			if ( isset( $_POST['q'] ) && '' !== $_POST['q'] ) {
				$query = sanitize_text_field( wp_unslash( $_POST['q'] ) );
			} else {
				$query = '';
			}

			$addf_gr_args = array(
				'post_type'      => 'addf_gift_registry',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'orderby'        => 'id',
				'order'          => 'desc',
				's'              => $query,
				'meta_query'     => array(
					array(
						'key'   => 'gift-registry-registrant-user_is_wp',
						'value' => get_current_user_id(),
					),
				),
			);

			$addf_gr_data_array = array();

			$addf_gr_loop = new WP_Query( $addf_gr_args );
			
			if ($addf_gr_loop->have_posts()) {
				while ($addf_gr_loop->have_posts()) {
					$addf_gr_loop->the_post();
					$post_id        = get_the_ID();
					$post_title     = get_the_title($post_id);
					$post_permalink = get_permalink($post_id);

					$addf_gr_data_array[] = array( $post_title, $post_permalink );
			
				}
				wp_reset_postdata();
			}
			echo wp_json_encode( $addf_gr_data_array );
			die();
		}


		public function addf_gift_registry_add_greeting_message_to_cart_item( $cart_item_data, $product_id, $variation_id, $quantity ) {

			$addf_registry_post_id = WC()->session->get('addf_gift_registry_seesion_add_To_cart_gr_id');


			$addf_gr_current_user = wp_get_current_user();
			$addf_gr_product_id   = $product_id;

			if ( '0' != $variation_id) {
				$addf_gr_product_id = $variation_id;
			}

			$addf_gr_session_id = 'addf_gr_greeting_message_' . $addf_gr_product_id . '_' . $addf_gr_current_user->ID;

			if (WC()->session->get($addf_gr_session_id)) {
				$cart_item_data['addf_greeting_message'] = WC()->session->get($addf_gr_session_id);
			}

			return $cart_item_data;
		}

		public function addf_gift_registry_load_text_domain() {
			if ( function_exists( 'load_plugin_textdomain' ) ) {
				load_plugin_textdomain( 'addf_giftr', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
			}

			$posts = current(
				get_posts(
					array(
						'post_type'   => 'page',
						'title'       => 'Gift Registry',
						'post_status' => 'all',
						'numberposts' => -1,
						'fields'      => 'ids',
					)
				)
			);
			if ( empty( $posts ) ) {
				$my_post = array(
					'post_title'   => 'Gift Registry',
					'post_content' => '[addf_gift_registry_short_code]',
					'post_status'  => 'publish',
					'post_type'    => 'page',

				);
					// Insert the post into the database.
				wp_insert_post( $my_post );
			}
		}
		public function addfl_global_constents_vars() {
			if ( ! defined( 'ADDF_GR_URL' ) ) {
				define( 'ADDF_GR_URL', plugin_dir_url( __FILE__ ) );
			}

			if ( ! defined( 'ADDF_GR_BASENAME' ) ) {
				define( 'ADDF_GR_BASENAME', plugin_basename( __FILE__ ) );
			}
			if ( ! defined( 'ADDF_GR_DIR' ) ) {
				define( 'ADDF_GR_DIR', plugin_dir_path( __FILE__ ) );
			}
		}

		public function addf_gift_registry_send_mail( $emails ) {
			// include your class file.

			include_once ADDF_GR_DIR . 'emails/create-new-registry/registrant/create-new-mail.php';
			include_once ADDF_GR_DIR . 'emails/create-new-registry/co_registrant/create-new-mail.php';
			include_once ADDF_GR_DIR . 'emails/create-new-registry/admin/create-new-mail-admin.php';
			include_once ADDF_GR_DIR . 'emails/share-registry/create-new-mail.php';
			include_once ADDF_GR_DIR . 'emails/registry-expire/create-new-mail.php';
			include_once ADDF_GR_DIR . 'emails/product-purchase/registrant/create-new-mail.php';
			include_once ADDF_GR_DIR . 'emails/product-purchase/co_registrant/create-new-mail.php';
			include_once ADDF_GR_DIR . 'emails/product-purchase/admin/create-new-mail-admin.php';
			// create an instance of file and set in a unique index of emails array.
			$emails['addf_gr_create_new_wp_email_admin']               = new ADDF_GIFT_REGISTRY_CREATE_NEW_REG_WP_MAIL_ADMIN();
			$emails['addf_gr_create_new_wp_email_registrant']          = new ADDF_GIFT_REGISTRY_CREATE_NEW_REG_WP_MAIL();
			$emails['addf_gr_create_new_wp_email_co_registrant']       = new ADDF_GIFT_REGISTRY_CREATE_NEW_REG_WP_MAIL_CO_REG();
			$emails['addf_gr_share_registry_wp_email']                 = new ADDF_GIFT_REGISTRY_SHARE_WP_MAIL();
			$emails['addf_gr_expire_registry_wp_email']                = new ADDF_GIFT_REGISTRY_EXPIRE_WP_MAIL();
			$emails['addf_gr_product_purchase_wp_email_admin']         = new ADDF_GIFT_REGISTRY_PRODUCT_PURCHASE_WP_MAIL_ADMIN();
			$emails['addf_gr_product_purchase_wp_email_registrant']    = new ADDF_GIFT_REGISTRY_PRODUCT_PURCHASE_WP_MAIL_REG();
			$emails['addf_gr_product_purchase_wp_email_co_registrant'] = new ADDF_GIFT_REGISTRY_PRODUCT_PURCHASE_MAIL_CO_REG();
			// return emails array.
			return $emails;
		}


		public function addf_gift_registry_add_a_product_through_button_cb() {

			$nonce = isset( $_POST['addify_gift_registry_nonce'] ) ? sanitize_text_field( $_POST['addify_gift_registry_nonce'] ) : '';

			if ( ! wp_verify_nonce( $nonce, 'addify_gift_registry_nonce' ) ) {

				wp_die( esc_html__( 'Security Violated!', 'addf_giftr' ) );

			}
			$gift_registry_selected             = sanitize_text_field( isset( $_POST['gift_registry_selected'] ) ? $_POST['gift_registry_selected'] : '' );
			$gift_registry_product_selected     = sanitize_text_field( isset( $_POST['gift_registry_product_selected'] ) ? $_POST['gift_registry_product_selected'] : '' );
			$gift_registry_product_qty_selected = sanitize_text_field( isset( $_POST['gift_registry_product_qty_selected'] ) ? $_POST['gift_registry_product_qty_selected'] : '' );
			$addf_gr_attr_for_variation         = sanitize_text_field( isset( $_POST['addf_gr_attr_for_variation'] ) ? $_POST['addf_gr_attr_for_variation'] : '' );
			$addf_gr_to_be_updated_list         = (array) get_post_meta( $gift_registry_selected, 'addf_gift_registry_product', true );
			$addf_gr_product_quantity           = (array) get_post_meta( $gift_registry_selected, 'addf_gr_product_quantity', true );
			// if already exists
			$_product = wc_get_product( $gift_registry_product_selected );
			if ( $_product->is_type( 'variation' ) ) {
				$variation_id_for_key                   = $gift_registry_product_selected;
				$gift_registry_product_selected_for_key = $_product->get_parent_id();
			} else {
				$gift_registry_product_selected_for_key = $gift_registry_product_selected;
				$variation_id_for_key                   = 0;
			}
			$addf_gr_var_attr    = array();
			$addf_gr_attr_string = explode( '&', $addf_gr_attr_for_variation );
			if ( is_array( $addf_gr_attr_string ) ) {
				foreach ( $addf_gr_attr_string as $value ) {
					$addf_gr_attr_string_eq = explode( '=', $value );
					if ( '' != $addf_gr_attr_string_eq[0] ) {
						$addf_gr_attr_string_key                      = str_replace( 'attribute_', '', $addf_gr_attr_string_eq[0] );
						$addf_gr_var_attr[ $addf_gr_attr_string_key ] = $addf_gr_attr_string_eq[1];
					}
				}
			}
			$addf_gr_key_index = $this->generate_addf_gr_product_id( $gift_registry_product_selected_for_key, $variation_id_for_key, $addf_gr_var_attr, $gift_registry_selected );
			if ( array_key_exists( $addf_gr_key_index, $addf_gr_to_be_updated_list ) ) {
				$addf_g_r_already_qty                           = $addf_gr_product_quantity[ $addf_gr_key_index ];
				$addf_gr_product_quantity[ $addf_gr_key_index ] = $addf_g_r_already_qty + $gift_registry_product_qty_selected;
				$addf_gr_product_quantity_if_successfull        = update_post_meta( $gift_registry_selected, 'addf_gr_product_quantity', $addf_gr_product_quantity );
				$message                                        = sprintf( '“%s” has been added to your gift registry.', get_the_title( $gift_registry_product_selected ) );
				wc_add_notice( $message, 'success' );
				if ( $addf_gr_product_quantity_if_successfull ) {
					wp_send_json(
						array(
							'success' => 'yes',
							'message' => wc_print_notices( true ),
						)
					);
					die();
				}
			} else {
				$addf_gr_to_be_updated_list[ $addf_gr_key_index ] = $gift_registry_product_selected;
				$addf_gr_to_be_updated_list_if_successfull        = update_post_meta( $gift_registry_selected, 'addf_gift_registry_product', (array) $addf_gr_to_be_updated_list );
				$addf_gr_product_quantity[ $addf_gr_key_index ]   = $gift_registry_product_qty_selected;
				$addf_gr_var_selection_val[ $addf_gr_key_index ]  = (array) $addf_gr_var_attr;
				$addf_gr_variation_selection_verify_vales         = update_post_meta( $gift_registry_selected, 'addf_gr_variation_selection_verify_vales', $addf_gr_var_selection_val );
				$addf_gr_product_quantity_if_successfull          = update_post_meta( $gift_registry_selected, 'addf_gr_product_quantity', $addf_gr_product_quantity );
				$addf_gr_recieved_products[ $addf_gr_key_index ]  = 0;
				$addf_gr_to_be_updated_list_rec_if_successfull    = update_post_meta( $gift_registry_selected, 'addf_gr_product_quantity_recieved', $addf_gr_recieved_products );
				$message = sprintf( '“%s” has been added to your gift registry.', get_the_title( $gift_registry_product_selected ) );
				wc_add_notice( $message, 'success' );
				if ( $addf_gr_to_be_updated_list_if_successfull ) {
					wp_send_json(
						array(
							'success' => 'yes',
							'message' => wc_print_notices( true ),
							'key'     => $addf_gr_key_index,
						)
					);
					die();
				} else {
					wp_send_json(
						array(
							'success' => 'no',
							'message' => wc_print_notices( false ),
						)
					);
					die();
				}
			}
		}

		public function addf_gift_registry_add_a_product_attr_selection_cb() {

			$nonce = isset( $_POST['addify_gift_registry_nonce'] ) ? sanitize_text_field( $_POST['addify_gift_registry_nonce'] ) : '';

			if ( ! wp_verify_nonce( $nonce, 'addify_gift_registry_nonce' ) ) {

				wp_die( esc_html__( 'Security Violated!', 'addf_giftr' ) );

			}
			$product_id            = sanitize_text_field( isset( $_POST['product_id'] ) ? $_POST['product_id'] : '' );
			$_product_id           = $product_id;
			$product               = wc_get_product( $_product_id );
			$addf_gr_all_selection = '';
			if ( $product->is_type( 'variation' ) ) {
				$_product_id    = $product->get_parent_id();
				$parent_product = wc_get_product( $_product_id );
				$attributes     = $parent_product->get_variation_attributes();
				ob_start();
				foreach ( $product->get_attributes() as $taxonomy => $terms_slug ) {
					if ( ( '' == $terms_slug ) && ( '' != $taxonomy ) ) {
						$addf_gr_all_selection .= $taxonomy . '&';
						?>
							<div class="addf_gr_half_width">
								<label for="<?php echo esc_attr( $taxonomy ); ?>"><?php echo esc_html__( str_replace( 'pa_', '', $taxonomy ), 'addf_giftr' ); ?></label>
								<select name="<?php echo esc_attr( $taxonomy ); ?>" id="<?php echo esc_attr( $taxonomy ); ?>" class="addf_gr_select_attr">
								<?php
								foreach ( $attributes as $attribute_name => $options ) {
									if ( $attribute_name == $taxonomy ) {
										?>
											<option value="" hidden><?php echo esc_html__( 'Select ' . str_replace( 'pa_', '', $taxonomy ), 'addf_giftr' ); ?></option>
											<?php
											foreach ( $options as $key => $value ) {
												?>
												<option value="<?php echo esc_attr( $value ); ?>"> <?php echo esc_html__( $value, 'addf_giftr' ); ?></option>
												<?php
											}
									}
								}
								?>
								</select>
							</div>
							<?php
					}
				}
			}
			?>
				<input type="hidden" class="addf_gr_all_selection_verify" value="<?php echo esc_attr( $addf_gr_all_selection ); ?>">
				<input type="hidden" class="addf_gr_all_selection_verify_vales" value="">
				<?php
				$addf_gift_registry_add_attr = ob_get_clean();
				wp_send_json(
				array(
					'success'                     => 'yes',
					'addf_gift_registry_add_attr' => $addf_gift_registry_add_attr,
				)
				);
				die();
		}


		public function addf_gift_registry_add_customer_session() {
			if (is_user_logged_in() || is_admin()) {
				return;
			}
			if (isset(WC()->session)) {
				if (!WC()->session->has_session()) {
					WC()->session->set_customer_session_cookie(true);
				}
			}
		}

		public function addf_gift_registry_add_a_new_product_from_registry_cb() {

			$nonce = isset( $_POST['addify_gift_registry_nonce'] ) ? sanitize_text_field( $_POST['addify_gift_registry_nonce'] ) : '';

			if ( ! wp_verify_nonce( $nonce, 'addify_gift_registry_nonce' ) ) {

				wp_die( esc_html__( 'Security Violated!', 'addf_giftr' ) );

			}
			$addf_gr_post_id_tobe_updated           = sanitize_text_field( isset( $_POST['addf_gr_post_id_tobe_updated'] ) ? $_POST['addf_gr_post_id_tobe_updated'] : '' );
			$addf_gr_product_id_tobe_updated        = sanitize_text_field( isset( $_POST['addf_gr_product_id_tobe_updated'] ) ? $_POST['addf_gr_product_id_tobe_updated'] : '' );
			$addf_gr_product_quantity_to_be_updated = sanitize_text_field( isset( $_POST['addf_gr_product_quantity_to_be_updated'] ) ? $_POST['addf_gr_product_quantity_to_be_updated'] : '' );
			// adding variation
			$addf_gr_all_selection_verify_vales = sanitize_text_field( isset( $_POST['addf_gr_all_selection_verify_vales'] ) ? $_POST['addf_gr_all_selection_verify_vales'] : '' );
			if ( '' == $addf_gr_all_selection_verify_vales ) {
				$addf_gr_all_selection_verify_vales = 0;
			}

			$addf_gr_var_attr    = array();
			$addf_gr_attr_string = explode( '&', $addf_gr_all_selection_verify_vales );
			if ( is_array( $addf_gr_attr_string ) ) {
				foreach ( $addf_gr_attr_string as $value ) {
					$addf_gr_attr_string_eq = explode( '=', $value );
					if ( is_array( $addf_gr_attr_string_eq ) ) {
						if ( ( '' != $addf_gr_attr_string_eq[0] ) && ( '0' != $addf_gr_attr_string_eq[0] ) ) {
							$addf_gr_var_attr[ $addf_gr_attr_string_eq[0] ] = $addf_gr_attr_string_eq[1];
						}
					}
				}
			}
			$_product = wc_get_product( $addf_gr_product_id_tobe_updated );
			if ( $_product->is_type( 'variation' ) ) {
				$variation_id_for_key                    = $addf_gr_product_id_tobe_updated;
				$addf_gr_product_id_tobe_updated_for_key = $_product->get_parent_id();
			} else {
				$variation_id_for_key                    = 0;
				$addf_gr_product_id_tobe_updated_for_key = $addf_gr_product_id_tobe_updated;
			}
			$addf_gr_var_selection_val         = (array) get_post_meta( $addf_gr_post_id_tobe_updated, 'addf_gr_variation_selection_verify_vales', true );
			$addf_gr_product_quantity          = (array) get_post_meta( $addf_gr_post_id_tobe_updated, 'addf_gr_product_quantity', true );
			$addf_gr_post_id_tobe_updated_list = (array) get_post_meta( $addf_gr_post_id_tobe_updated, 'addf_gift_registry_product', true );
			$addf_gr_recieved_products         = (array) get_post_meta( $addf_gr_post_id_tobe_updated, 'addf_gr_product_quantity_recieved', true );
			$addf_gr_key_index                 = $this->generate_addf_gr_product_id( $addf_gr_product_id_tobe_updated_for_key, $variation_id_for_key, $addf_gr_var_attr, $addf_gr_post_id_tobe_updated );
			if ( array_key_exists( $addf_gr_key_index, $addf_gr_post_id_tobe_updated_list ) ) {
				$addf_already_quantity = $addf_gr_product_quantity[ $addf_gr_key_index ];
				if ( ! $addf_already_quantity || ( '' == $addf_already_quantity ) ) {
					$addf_already_quantity = 1;
				}
				$addf_gr_product_quantity[ $addf_gr_key_index ] = $addf_already_quantity + $addf_gr_product_quantity_to_be_updated;
				$addf_gr_check                                  = update_post_meta( $addf_gr_post_id_tobe_updated, 'addf_gr_product_quantity', $addf_gr_product_quantity );
				if ( $addf_gr_check ) {
					ob_start();
					include ADDF_GR_DIR . 'front/templates/refresh-ajax-product-list.php';
					$addf_gift_registry_ajax_refresh_table = ob_get_clean();
					wp_send_json(
						array(
							'success' => 'yes',
							'addf_gift_registry_ajax_refresh_table' => $addf_gift_registry_ajax_refresh_table,
						)
					);

					die();
				}
			} else {
				$addf_gr_product_quantity[ $addf_gr_key_index ]          = $addf_gr_product_quantity_to_be_updated;
				$addf_gr_var_selection_val[ $addf_gr_key_index ]         = $addf_gr_var_attr;
				$addf_gr_post_id_tobe_updated_list[ $addf_gr_key_index ] = $addf_gr_product_id_tobe_updated;
				$addf_gr_check_if_successfull_quantity                   = update_post_meta( $addf_gr_post_id_tobe_updated, 'addf_gr_product_quantity', $addf_gr_product_quantity );

				$addf_gr_variation_selection_verify_vales = update_post_meta( $addf_gr_post_id_tobe_updated, 'addf_gr_variation_selection_verify_vales', $addf_gr_var_selection_val );

				$addf_gr_check_if_successfull                    = update_post_meta( $addf_gr_post_id_tobe_updated, 'addf_gift_registry_product', $addf_gr_post_id_tobe_updated_list );
				$addf_gr_recieved_products[ $addf_gr_key_index ] = 0;
				update_post_meta( $addf_gr_post_id_tobe_updated, 'addf_gr_product_quantity_recieved', $addf_gr_recieved_products );

				ob_start();
				include ADDF_GR_DIR . 'front/templates/refresh-ajax-product-list.php';
				$addf_gift_registry_ajax_refresh_table = ob_get_clean();
				$after_update__quantity                = get_post_meta( $addf_gr_post_id_tobe_updated, 'addf_gr_product_quantity', true );
				if ( $addf_gr_check_if_successfull && $addf_gr_variation_selection_verify_vales ) {
					wp_send_json(
						array(
							'success' => 'yes',
							'addf_gift_registry_ajax_refresh_table' => $addf_gift_registry_ajax_refresh_table,
							'key'     => $addf_gr_key_index,
							'addf_gr_product_quantity_to_be_updated' => $addf_gr_product_quantity,
							'addf_gr_product_quantity_to_be_updated_after_update' => $after_update__quantity,
						)
					);
					die();
				} else {
					wp_send_json(
						array(
							'success' => 'no',
						)
					);
				}
			}
			die();
		}



		public function addf_gift_registry_add_a_greeting_message_from_registry_cb() {

			$nonce = isset( $_POST['addify_gift_registry_nonce'] ) ? sanitize_text_field( $_POST['addify_gift_registry_nonce'] ) : '';

			if ( ! wp_verify_nonce( $nonce, 'addify_gift_registry_nonce' ) ) {

				wp_die( esc_html__( 'Security Violated!', 'addf_giftr' ) );

			}
			$addf_gr_post_id_tobe_updated           = sanitize_text_field( isset( $_POST['addf_gr_post_id_tobe_updated'] ) ? $_POST['addf_gr_post_id_tobe_updated'] : '' );
			$addf_gr_product_id_tobe_updated        = sanitize_text_field( isset( $_POST['addf_gr_product_id_tobe_updated'] ) ? $_POST['addf_gr_product_id_tobe_updated'] : '' );
			$addf_gr_greeting_message_to_be_updated = sanitize_text_field( isset( $_POST['addf_gr_greeting_message'] ) ? $_POST['addf_gr_greeting_message'] : '' );

			$addf_gr_current_user = wp_get_current_user();

			$addf_gr_session_name_id = 'addf_gr_greeting_message_' . $addf_gr_product_id_tobe_updated . '_' . $addf_gr_current_user->ID;
			
			WC()->session->set( $addf_gr_session_name_id, $addf_gr_greeting_message_to_be_updated  );

			ob_start();
			include ADDF_GR_DIR . 'front/templates/refresh-ajax-product-registry-at-buyer-side.php';
			$addf_gift_registry_ajax_refresh_product_registry_at_buyer_side = ob_get_clean();
			wp_send_json(
				array(
					'success' => 'yes',
					'addf_gift_registry_ajax_refresh_product_registry_at_buyer_side' => $addf_gift_registry_ajax_refresh_product_registry_at_buyer_side,
				)
			);

			die();
		}

		public function addf_gift_registry_delete_a_greeting_message_from_registry_cb() {

			$nonce = isset( $_POST['addify_gift_registry_nonce'] ) ? sanitize_text_field( $_POST['addify_gift_registry_nonce'] ) : '';

			if ( ! wp_verify_nonce( $nonce, 'addify_gift_registry_nonce' ) ) {

				wp_die( esc_html__( 'Security Violated!', 'addf_giftr' ) );

			}
			$addf_gr_post_id_tobe_updated    = sanitize_text_field( isset( $_POST['addf_gr_post_id_tobe_updated'] ) ? $_POST['addf_gr_post_id_tobe_updated'] : '' );
			$addf_gr_product_id_tobe_updated = sanitize_text_field( isset( $_POST['addf_gr_product_id_tobe_updated'] ) ? $_POST['addf_gr_product_id_tobe_updated'] : '' );
			
			$addf_gr_current_user = wp_get_current_user();

			
			$addf_gr_session_name_id = 'addf_gr_greeting_message_' . $addf_gr_product_id_tobe_updated . '_' . $addf_gr_current_user->ID;

			WC()->session->set( $addf_gr_session_name_id, ''  );

			ob_start();
			include ADDF_GR_DIR . 'front/templates/refresh-ajax-product-registry-at-buyer-side.php';
			$addf_gift_registry_ajax_refresh_product_registry_at_buyer_side = ob_get_clean();
			wp_send_json(
				array(
					'success' => 'yes',
					'addf_gift_registry_ajax_refresh_product_registry_at_buyer_side' => $addf_gift_registry_ajax_refresh_product_registry_at_buyer_side,
				)
			);
			die();
		}

		public function addf_gift_registry_delete_registry_cb() {

			$nonce = isset( $_POST['addify_gift_registry_nonce'] ) ? sanitize_text_field( $_POST['addify_gift_registry_nonce'] ) : '';

			if ( ! wp_verify_nonce( $nonce, 'addify_gift_registry_nonce' ) ) {

				wp_die( esc_html__( 'Security Violated!', 'addf_giftr' ) );

			}
			$addf_gr_post_id_tobe_deleted = sanitize_text_field( isset( $_POST['addf_gr_post_id_tobe_deleted'] ) ? $_POST['addf_gr_post_id_tobe_deleted'] : '' );
			$addf_gr_post_deleted         = false;

			if ($addf_gr_post_id_tobe_deleted) {
				$addf_gr_post_deleted = wp_delete_post($addf_gr_post_id_tobe_deleted);              
			}

			if ($addf_gr_post_deleted) {
				$message = sprintf( 'Registry has been deleted Successfully.' );
				wc_add_notice( $message, 'success' );
				wp_send_json(
					array(
						'success'  => 'yes',
						'message'  => wc_print_notices( true ),
						'base_url' =>home_url(),
					)
				);
				die();
			}

			$message = sprintf( 'Error in deleting Registry.' );
			wc_add_notice( $message, 'error' );

			wp_send_json(
				array(
					'success' => 'no',
					'message' => wc_print_notices( true ),
				)
			);
			die();
		}



		public function addf_gift_registry_check_qty_single_cb() {
			$nonce = isset( $_POST['addify_gift_registry_nonce'] ) ? sanitize_text_field( $_POST['addify_gift_registry_nonce'] ) : '';

			if ( ! wp_verify_nonce( $nonce, 'addify_gift_registry_nonce' ) ) {

				wp_die( esc_html__( 'Security Violated!', 'addf_giftr' ) );

			}
			$qty     = sanitize_text_field( isset( $_POST['qty'] ) ? $_POST['qty'] : '' );
			$req_qty = sanitize_text_field( isset( $_POST['req_qty'] ) ? $_POST['req_qty'] : '' );
			$msg     = sanitize_text_field( isset( $_POST['msg'] ) ? $_POST['msg'] : '' );
			$message = sprintf( 'Selected quantity for product is greater than required quantity' );
			wc_add_notice( $message, 'error' );
			wp_send_json(
				array(
					'success' => true,
					'message' => wc_print_notices( true ),
				)
			);
		}
		public function addf_gift_registry_add_to_cat_single_cb() {
			
			
			$nonce = isset( $_POST['addify_gift_registry_nonce'] ) ? sanitize_text_field( $_POST['addify_gift_registry_nonce'] ) : '';

			if ( ! wp_verify_nonce( $nonce, 'addify_gift_registry_nonce' ) ) {

				wp_die( esc_html__( 'Security Violated!', 'addf_giftr' ) );

			}
			$key        = sanitize_text_field( isset( $_POST['product_id'] ) ? $_POST['product_id'] : '' );
			$gr_post_id = sanitize_text_field( isset( $_POST['gr_post_id'] ) ? $_POST['gr_post_id'] : '' );
			WC()->session->set( 'addf_gift_registry_seesion_add_To_cart_gr_id', $gr_post_id );
			$addf_g_r_quantity_for_product = sanitize_text_field( isset( $_POST['quantity_for_product'] ) ? $_POST['quantity_for_product'] : '' );
			global $addf_gift_registry_count_add_t0_cart;
			$addf_gift_registry_product               = (array) get_post_meta( $gr_post_id, 'addf_gift_registry_product', true );
			$addf_g_r_product_id                      = $addf_gift_registry_product[ $key ];
			$addf_gr_variation_selection_verify_vales = get_post_meta( $gr_post_id, 'addf_gr_variation_selection_verify_vales', true );
			$variation_attr                           = array();
			if ( array_key_exists( $key, (array) $addf_gr_variation_selection_verify_vales ) ) {
				$variation_attr_main = $addf_gr_variation_selection_verify_vales[ $key ];
				foreach ( $variation_attr_main as $key => $value ) {
					$variation_attr[ 'attribute_' . $key ] = $value;
				}
			}
			$product = wc_get_product( $addf_g_r_product_id );
			if ( $product->is_type( 'variation' ) ) {
				$variation_id        = $addf_g_r_product_id;
				$addf_g_r_product_id = $product->get_parent_id();
			} else {
				$variation_id = 0;
			}

			$addf_gr_rest_user_op = get_option( 'wc_settings_tab_gift_registry_addf_gr_restrict_add_to_cart' );
			if ( ! WC()->session->get( 'addf_gift_registry_seesion_add_To_cart' ) ) {
				if ( 'allow_user' != $addf_gr_rest_user_op ) {
					if ( ! WC()->cart->is_empty() ) {
						$message = sprintf( "Can't add gift registry products to cart because cart already have products" );
						wc_add_notice( $message, 'error' );
						wp_send_json(
							array(
								'success' => false,
								'message' => wc_print_notices( true ),
							)
						);
					}
				}
			}

			//start of new added logic for checking received amount should not be greater then desired amount
			$var_for_singlr_product_rec = (array) get_post_meta( $gr_post_id, 'addf_gr_product_quantity_recieved', true );
			$var_for_singlr_product     = (array) get_post_meta( $gr_post_id, 'addf_gr_product_quantity', true );

			$item_found = false;
			$quantity   = 0;
			
			foreach (WC()->cart->get_cart() as $new_key=>$item) {
				foreach ($item as $inner_key=>$inner_item) {
					if (( 'variation_id' == $inner_key && $inner_item == $variation_id && '0' != $variation_id ) || ( ( 'product_id' == $inner_key && $inner_item == $addf_g_r_product_id ) ) ) {
						$item_found = true;
					}

					if ($item_found && 'quantity' == $inner_key) {
						$quantity  += $inner_item;
						$item_found = false;
					}
				}
			} 
			

			if ($var_for_singlr_product[ $key ] < ( $var_for_singlr_product_rec[ $key ]+ $quantity + $addf_g_r_quantity_for_product )) {

				$message = sprintf( 'Selected quantity for product is greater than required quantity' );
				wc_add_notice( $message, 'error' );
				wp_send_json(
					array(
						'success' => false,
						'message' => wc_print_notices( true ),
					)
				);
			}
			//end of new added logic for checking received amount should not be greater then desired amount


			if ( array_key_exists( $key, (array) $addf_gift_registry_product ) ) {
				if ( WC()->cart->add_to_cart( $addf_g_r_product_id, $addf_g_r_quantity_for_product, $variation_id, $variation_attr) ) {
					WC()->session->set( 'addf_gift_registry_seesion_add_To_cart', true );
					++$addf_gift_registry_count_add_t0_cart;
					$message = sprintf( '“%s” has been added to your cart.', get_the_title( $addf_g_r_product_id ) );
					wc_add_notice( $message, 'success' );

					$cart_contents                     = WC()->cart->get_cart_contents();
					$addf_gift_registry_cart_item_data = array();

					foreach ($cart_contents as $cart_item_key => $cart_item) {
						if ($cart_item['product_id'] == $addf_g_r_product_id) {
							$addf_gift_registry_cart_item_data = $cart_item;
							break; 
						}
					}
					wp_send_json(
						array(
							'success' => true,
							'message' => wc_print_notices( true ),
						)
					);
				}
			}if ( array_key_exists( $key, (array) $addf_gift_registry_product ) ) {
				if ( WC()->cart->add_to_cart( $addf_g_r_product_id, $addf_g_r_quantity_for_product, $variation_id, $variation_attr ) ) {
					WC()->session->set( 'addf_gift_registry_seesion_add_To_cart', true );
					++$addf_gift_registry_count_add_t0_cart;
					$message = sprintf( '“%s” has been added to your cart.', get_the_title( $addf_g_r_product_id ) );
					wc_add_notice( $message, 'success' );

					$cart_contents                     = WC()->cart->get_cart_contents();
					$addf_gift_registry_cart_item_data = array();

					foreach ($cart_contents as $cart_item_key => $cart_item) {
						if ($cart_item['product_id'] == $addf_g_r_product_id) {
							$addf_gift_registry_cart_item_data = $cart_item;
							break; 
						}
					}
					wp_send_json(
						array(
							'success' => true,
							'message' => wc_print_notices( true ),
						)
					);
				}
			}
			if ( WC()->cart->add_to_cart( $addf_g_r_product_id, $addf_g_r_quantity_for_product, $variation_id, $variation_attr  ) ) {
				WC()->session->set( 'addf_gift_registry_seesion_add_To_cart', true );
				++$addf_gift_registry_count_add_t0_cart;
				$message = sprintf( '“%s” has been added to your cart.', get_the_title( $addf_g_r_product_id ) );
				wc_add_notice( $message, 'success' );
				wp_send_json(
					array(
						'success' => true,
						'message' => wc_print_notices( true ),
					)
				);
			} else {
				wp_send_json(
					array(
						'success' => false,
						'msg'     => 'contrl reached',
						'message' => wc_print_notices( true ),
					)
				);
			}
		}
		public function addf_gift_registry_add_to_cat_bulk_action_cb() {
			$nonce = isset( $_POST['addify_gift_registry_nonce'] ) ? sanitize_text_field( $_POST['addify_gift_registry_nonce'] ) : '';

			if ( ! wp_verify_nonce( $nonce, 'addify_gift_registry_nonce' ) ) {

				wp_die( esc_html__( 'Security Violated!', 'addf_giftr' ) );

			}
			$key_ids    = sanitize_text_field( isset( $_POST['product_ids'] ) ? $_POST['product_ids'] : '' );
			$gr_post_id = sanitize_text_field( isset( $_POST['gr_post_id'] ) ? $_POST['gr_post_id'] : '' );
			WC()->session->set( 'addf_gift_registry_seesion_add_To_cart_gr_id', $gr_post_id );
			$addf_g_r_addf_quantities_for_products = sanitize_text_field( isset( $_POST['addf_quantities_for_products'] ) ? $_POST['addf_quantities_for_products'] : '' );
			global $addf_gift_registry_count_add_t0_cart;
			$addf_gr_rest_user_op = get_option( 'wc_settings_tab_gift_registry_addf_gr_restrict_add_to_cart' );
			if ( ! WC()->session->get( 'addf_gift_registry_seesion_add_To_cart' ) ) {
				if ( 'allow_user' != $addf_gr_rest_user_op ) {
					if ( ! WC()->cart->is_empty() ) {
						$message = sprintf( "Can't add gift registry products to cart because cart already have products" );
						wc_add_notice( $message, 'error' );
						wp_send_json(
							array(
								'success' => false,
								'reason'  => true,
								'message' => wc_print_notices( true ),
							)
						);
					}
				}
			}
			$addf_gift_registry_product                  = get_post_meta( $gr_post_id, 'addf_gift_registry_product', true );
			$addf_gr_variation_selection_verify_vales    = get_post_meta( $gr_post_id, 'addf_gr_variation_selection_verify_vales', true );
			$key_ids_array                               = (array) explode( ',', $key_ids );
			$addf_g_r_addf_quantities_for_products_array = explode( ',', $addf_g_r_addf_quantities_for_products );
			$addf_g_r_product_ids_array_size             = count( $key_ids_array );
			$addf_gr_variation_selection_verify_vales    = get_post_meta( $gr_post_id, 'addf_gr_variation_selection_verify_vales', true );
			$addf_gift_registry_product                  = get_post_meta( $gr_post_id, 'addf_gift_registry_product', true );
			for ( $i = 1; $i < $addf_g_r_product_ids_array_size; $i++ ) {
				$variation_attr = array();
				if ( array_key_exists( $key_ids_array[ $i ], (array) $addf_gr_variation_selection_verify_vales ) ) {
					$variation_attr_main = $addf_gr_variation_selection_verify_vales[ $key_ids_array[ $i ] ];
					foreach ( $variation_attr_main as $key => $value ) {
						$variation_attr[ 'attribute_' . $key ] = $value;
					}
				}
				$addf_g_r_product_id = $addf_gift_registry_product[ $key_ids_array[ $i ] ];
				$product             = wc_get_product( $addf_g_r_product_id );
				if ( $product->is_type( 'variation' ) ) {
					$variation_id        = $addf_g_r_product_id;
					$addf_g_r_product_id = $product->get_parent_id();
				} else {
					$variation_id = 0;
				}

				//start of new added logic for checking received amount should not be greater then desired amount
				$var_for_singlr_product_rec = (array) get_post_meta( $gr_post_id, 'addf_gr_product_quantity_recieved', true );
				$var_for_singlr_product     = (array) get_post_meta( $gr_post_id, 'addf_gr_product_quantity', true );

				$item_found = false;
				$quantity   =0;

				foreach (WC()->cart->get_cart() as $new_key=>$item) {
					foreach ($item as $inner_key=>$inner_item) {
						if (( 'variation_id' == $inner_key && $inner_item == $variation_id && '0' != $variation_id ) || ( ( 'product_id' == $inner_key && $inner_item == $addf_g_r_product_id ) ) ) {
							$item_found = true;
						}
	
						if ($item_found && 'quantity' == $inner_key) {
							$quantity  += $inner_item;
							$item_found = false;
						}
					}
				}   
		
				if ($var_for_singlr_product[ $key_ids_array[ $i ] ] < ( $var_for_singlr_product_rec[ $key_ids_array[ $i ] ]+$quantity + $addf_g_r_addf_quantities_for_products_array[ $i ] )) {

					$message = sprintf( 'Selected quantity for product is greater than required quantity.' );
					wc_add_notice( $message, 'error' );
					continue;
				}
				//end of new added logic for checking received amount should not be greater then desired amount


				if ( WC()->cart->add_to_cart( $addf_g_r_product_id, $addf_g_r_addf_quantities_for_products_array[ $i ], $variation_id, $variation_attr  ) ) {
					WC()->session->set( 'addf_gift_registry_seesion_add_To_cart', true );
					$message = sprintf( '“%s” has been added to your cart.', get_the_title( $addf_g_r_product_id ) );
					wc_add_notice( $message, 'success' );
				}
			}

			wp_send_json(
				array(
					'success' => true,
					'ids'     => $key_ids_array,
					'qty'     => $addf_g_r_addf_quantities_for_products_array,
					'message' => wc_print_notices( true ),
				)
			);
		}
		public function addf_gift_registry_check_is_instock_cb() {
			$nonce = isset( $_POST['addify_gift_registry_nonce'] ) ? sanitize_text_field( $_POST['addify_gift_registry_nonce'] ) : '';

			if ( ! wp_verify_nonce( $nonce, 'addify_gift_registry_nonce' ) ) {

				wp_die( esc_html__( 'Security Violated!', 'addf_giftr' ) );

			}
			$variation_id    = sanitize_meta( '', wp_unslash( isset( $_POST['variation_id'] ) ? $_POST['variation_id'] : '' ), '' );
			$data_attributes = sanitize_meta( '', wp_unslash( isset( $_POST['data_attributes'] ) ? $_POST['data_attributes'] : '' ), '' );
			$product_array   = wc_get_product( $variation_id );
			if ( ( '' != $product_array->is_on_backorder() ) || ( '' != $product_array->is_in_stock() ) ) {
				$addf_gr_key_send = '';
				foreach ( $data_attributes as $key => $value ) {
					if ( '' == $value ) {
						$addf_gr_key_send .= '&' . $key;
					}
				}
				if ( ! $addf_gr_key_send ) {
					$addf_gr_key_send = '';
				}
				wp_send_json(
					array(
						'success'          => true,
						'addf_gr_key_send' => $addf_gr_key_send,
					)
				);
			} else {
				$addf_gr_key_send = '';
				wp_send_json(
					array(
						'success'          => false,
						'addf_gr_key_send' => $addf_gr_key_send,
					)
				);
			}
		}
		public function addf_gr_search_country_state_ajax_cb() {
			$nonce = isset( $_POST['addify_gift_registry_nonce'] ) ? sanitize_text_field( $_POST['addify_gift_registry_nonce'] ) : '';

			if ( ! wp_verify_nonce( $nonce, 'addify_gift_registry_nonce' ) ) {

				wp_die( esc_html__( 'Security Violated!', 'addf_giftr' ) );

			}
			
			if ( isset( $_POST['addf_country'] ) ) {
				$country       = isset( $_POST['addf_country'] ) ? sanitize_text_field( $_POST['addf_country'] ) : '';
				$state         = isset($_POST['addf_state'])?sanitize_text_field($_POST['addf_state']): '';
				$country_obj   = new WC_Countries();
				$addf_gr_state = $country_obj->get_states( $country );
				if ( ! empty( $addf_gr_state ) ) {
					?>
						<select name="gift_registry_guest_shipping_state" class="addf_gr_countries_field_height title-gift-registry-input gift_registry_guest_shipping_state">
						<?php
						foreach ($addf_gr_state as $key => $value) {
							?>
							<option <?php echo ( $key == $state ) || ( $value == $state )  ? 'selected' : ''; ?>>
								<?php echo esc_html__($value, 'addf_giftr'); ?>
							</option>
							<?php
						}
						
						?>
						</select>
						<?php
				} else {
					?>
						<input type="Text" name="gift_registry_guest_shipping_state" required class="addf_gr_countries_field_height title-gift-registry-input gift_registry_guest_shipping_state" placeholder="<?php echo esc_html__( 'Enter State', 'addf_giftr' ); ?>">
						<?php
				}
				die();
			}
		}

		public function addf_gift_registry_delete_a_product_from_registry_cb() {
			$nonce = isset( $_POST['addify_gift_registry_nonce'] ) ? sanitize_text_field( $_POST['addify_gift_registry_nonce'] ) : '';

			if ( ! wp_verify_nonce( $nonce, 'addify_gift_registry_nonce' ) ) {

				wp_die( esc_html__( 'Security Violated!', 'addf_giftr' ) );

			}
			$postid                   = sanitize_meta( '', wp_unslash( isset( $_POST['post_id'] ) ? $_POST['post_id'] : '' ), '' );
			$product_id               = sanitize_meta( '', wp_unslash( isset( $_POST['product_id'] ) ? $_POST['product_id'] : '' ), '' );
			$new_addf_gr_product_list = get_post_meta( $postid, 'addf_gift_registry_product', true );
			$addf_gr_product_quantity = get_post_meta( $postid, 'addf_gr_product_quantity', true );
			if ( ! array_key_exists( $product_id, (array) $new_addf_gr_product_list ) ) {
				wp_send_json(
					array(
						'success' => false,
					)
				);
			}
			unset( $new_addf_gr_product_list[ $product_id ] );
			unset( $addf_gr_product_quantity[ $product_id ] );
			$addf_gr_check_if_del_successfull = update_post_meta( $postid, 'addf_gift_registry_product', $new_addf_gr_product_list );
			update_post_meta( $postid, 'addf_gr_product_quantity', $addf_gr_product_quantity );
			ob_start();
			?>
				<div id="addf_gift_registry_product_tr">
					<select name="addf_gift_registry_product[]" id="addf_gift_registry_product" data-placeholder="<?php echo esc_html__( 'Choose Products...', 'addf_giftr' ); ?>" class=" addf_js_multiproduct_select" multiple="multiple" tabindex="-1" style="width:60%;">
					<?php
					$addf_gift_registry_specific_product = (array) get_post_meta( $postid, 'addf_gift_registry_product', true );
					if ( ! empty( $addf_gift_registry_specific_product ) ) {
						foreach ( $addf_gift_registry_specific_product as $addf_gr_pro ) {
							$addf_gr_prod_post = get_post( $addf_gr_pro );
							if ( ! empty( $addf_gr_prod_post->post_title ) ) {
								?>
									<option id="addf_gift_registry_product_id_<?php echo intval( $addf_gr_pro ); ?>" value="<?php echo intval( $addf_gr_pro ); ?>" selected="selected"><?php echo esc_html__( $addf_gr_prod_post->post_title, 'addf_giftr' ); ?></option>
									<?php
							}
						}
					}
					?>
					</select>
				</div>
				<?php
				$addf_gift_registry_ajax_replace_select = ob_get_clean();
				if ( $addf_gr_check_if_del_successfull ) {
					wp_send_json(
						array(
							'success' => true,
							'addf_gift_registry_ajax_replace_select' => $addf_gift_registry_ajax_replace_select, // $addf_gift_registry_ajax_refresh_table,
						)
					);
				} else {
					wp_send_json(
						array(
							'success' => false,
						)
					);
				}
		}
		public function addf_gift_registry_gr_is_expired_cb() {
			// save default settings
			//
			if ( ! get_option( 'wc_settings_tab_gift_registry_gift_registry_btn_option' ) || ( '' == get_option( 'wc_settings_tab_gift_registry_gift_registry_btn_option' ) ) ) {
				update_option( 'wc_settings_tab_gift_registry_gift_registry_btn_option', 'btn' );
			}
			if ( ! get_option( 'wc_settings_tab_gift_registry_gift_registry_menu_for_op' ) || ( '' == get_option( 'wc_settings_tab_gift_registry_gift_registry_menu_for_op' ) ) ) {
				update_option( 'wc_settings_tab_gift_registry_gift_registry_menu_for_op', 'all' );
			}
			if ( ! get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_guest_res_msg' ) || ( '' == get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_guest_res_msg' ) ) ) {
				update_option( 'wc_settings_tab_gift_registry_notify_gift_registry_guest_res_msg', 'You are not allowed to create gift registry. Login to create a Gift Registry' );
			}
			if ( ! get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_shiping_addr' ) || ( '' == get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_shiping_addr' ) ) ) {
				update_option( 'wc_settings_tab_gift_registry_notify_gift_registry_shiping_addr', 'You are not allowed to create gift registry. Fulfill your shipping address first' );
			}
			if ( ! get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_rest_user_res_msg' ) || ( '' == get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_rest_user_res_msg' ) ) ) {
				update_option( 'wc_settings_tab_gift_registry_notify_gift_registry_rest_user_res_msg', 'You are not allowed to create gift registry' );
			}
			if ( ! get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_exp_reg_msg' ) || ( '' == get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_exp_reg_msg' ) ) ) {
				update_option( 'wc_settings_tab_gift_registry_notify_gift_registry_exp_reg_msg', 'Gift Registry you are trying to access is expired' );
			}
			if ( ! get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_new_reg_site_title' ) || ( '' == get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_new_reg_site_title' ) ) ) {
				update_option( 'wc_settings_tab_gift_registry_notify_gift_registry_new_reg_site_title', 'New gift registry is created' );
			}
			if ( ! get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_new_reg_site_title_heading' ) || ( '' == get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_new_reg_site_title_heading' ) ) ) {
				update_option( 'wc_settings_tab_gift_registry_notify_gift_registry_new_reg_site_title_heading', 'New gift registry is created' );
			}
			if ( ! get_option( 'addf_gr_email_subject_for_admin' ) || ( '' == get_option( 'addf_gr_email_subject_for_admin' ) ) ) {
				update_option( 'addf_gr_email_subject_for_admin', 'New gift Registry is created' );
			}
			if ( ! get_option( 'addf_gr_email_subject_for_admin_heading' ) || ( '' == get_option( 'addf_gr_email_subject_for_admin_heading' ) ) ) {
				update_option( 'addf_gr_email_subject_for_admin_heading', 'New gift Registry is created' );
			}
			if ( ! get_option( 'addf_gr_email_subject_for_co_registrant' ) || ( '' == get_option( 'addf_gr_email_subject_for_co_registrant' ) ) ) {
				update_option( 'addf_gr_email_subject_for_co_registrant', 'New gift registry is created' );
			}
			if ( ! get_option( 'addf_gr_email_subject_for_co_registrant_heading' ) || ( '' == get_option( 'addf_gr_email_subject_for_co_registrant_heading' ) ) ) {
				update_option( 'addf_gr_email_subject_for_co_registrant_heading', 'New gift registry is created' );
			}
			if ( ! get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_site_title' ) || ( '' == get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_site_title' ) ) ) {
				update_option( 'wc_settings_tab_gift_registry_notify_gift_registry_site_title', 'Share gift registry' );
			}
			if ( ! get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_site_title_heading' ) || ( '' == get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_site_title_heading' ) ) ) {
				update_option( 'wc_settings_tab_gift_registry_notify_gift_registry_site_title_heading', 'Share gift registry' );
			}
			if ( ! get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_p_buy_site_title' ) || ( '' == get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_p_buy_site_title' ) ) ) {
				update_option( 'wc_settings_tab_gift_registry_notify_gift_registry_p_buy_site_title', 'Gift Registry' );
			}
			if ( ! get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_exp_site_title' ) || ( '' == get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_exp_site_title' ) ) ) {
				update_option( 'wc_settings_tab_gift_registry_notify_gift_registry_exp_site_title', 'Registry is expired' );
			}
			if ( ! get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_exp_site_title_heading' ) || ( '' == get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_exp_site_title_heading' ) ) ) {
				update_option( 'wc_settings_tab_gift_registry_notify_gift_registry_exp_site_title_heading', 'Registry is expired' );
			}
			// new registry created
			// for admin
			if ( ( ! get_option( 'addf_gf_email_content_for_admin' ) ) || ( '' == get_option( 'addf_gf_email_content_for_admin' ) ) ) {
				update_option( 'addf_gf_email_content_for_admin', '<p>' . esc_html__( 'Dear Admin a user {registrant_name}( {registrant_id} ) just have created  new Gift Registry named as {registry_title} as on {current_time}', 'addf_giftr' ) . '</p>' );
			}
			// for registrant
			if ( ( ! get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_admin__new_reg_email_sytax' ) ) || ( '' == get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_admin__new_reg_email_sytax' ) ) ) {
				update_option( 'wc_settings_tab_gift_registry_notify_gift_registry_admin__new_reg_email_sytax', '<p>' . esc_html__( 'Dear user {registrant_name}( {registrant_id} )', 'addf_giftr' ) . ' <br>  ' . esc_html__( 'you have created  new Gift Registry named as {registry_title} as on {current_time}', 'addf_giftr' ) . '</p>' );
			}
			// for co registrant
			if ( ( ! get_option( 'addf_gr_email_content_for_co_registrant' ) ) || ( '' == get_option( 'addf_gr_email_content_for_co_registrant' ) ) ) {
				update_option( 'addf_gr_email_content_for_co_registrant', '<p>' . esc_html__( 'Dear user your friend {registrant_name} just have created  new Gift Registry named as {registry_title} at {current_time} and choose you as a co registrant ', 'addf_giftr' ) . '</p>' );
			}

			// for share
			if ( ( ! get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_admin_email_sytax' ) ) || ( '' == get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_admin_email_sytax' ) ) ) {
				update_option( 'wc_settings_tab_gift_registry_notify_gift_registry_admin_email_sytax', '<p>' . esc_html__( ' Dear user your friend {registrant_name} shared his gift registry named as {registry_title} with you ', 'addf_giftr' ) . '</p>' );
			}
			// for purchase subject
			if ( ( ! get_option( 'addf_gr_product_purchase_mail_subject_admin' ) ) || ( '' == get_option( 'addf_gr_product_purchase_mail_subject_admin' ) ) ) {
				update_option( 'addf_gr_product_purchase_mail_subject_admin', esc_html__( 'Product is purchased', 'addf_giftr' ) );
			}
			if ( ( ! get_option( 'addf_gr_product_purchase_mail_subject_admin_heading' ) ) || ( '' == get_option( 'addf_gr_product_purchase_mail_subject_admin_heading' ) ) ) {
				update_option( 'addf_gr_product_purchase_mail_subject_admin_heading', esc_html__( 'Product is purchased', 'addf_giftr' ) );
			}
			if ( ( ! get_option( 'addf_gr_product_purchase_mail_subject_registrant' ) ) || ( '' == get_option( 'addf_gr_product_purchase_mail_subject_registrant' ) ) ) {
				update_option( 'addf_gr_product_purchase_mail_subject_registrant', esc_html__( 'Product is purchased', 'addf_giftr' ) );
			}
			if ( ( ! get_option( 'addf_gr_product_purchase_mail_subject_registrant_heading' ) ) || ( '' == get_option( 'addf_gr_product_purchase_mail_subject_registrant_heading' ) ) ) {
				update_option( 'addf_gr_product_purchase_mail_subject_registrant_heading', esc_html__( 'Product is purchased', 'addf_giftr' ) );
			}
			if ( ( ! get_option( 'addf_gr_product_purchase_mail_subject_co_registrant' ) ) || ( '' == get_option( 'addf_gr_product_purchase_mail_subject_co_registrant' ) ) ) {
				update_option( 'addf_gr_product_purchase_mail_subject_co_registrant', esc_html__( 'Product is purchased', 'addf_giftr' ) );
			}
			if ( ( ! get_option( 'addf_gr_product_purchase_mail_subject_co_registrant_heading' ) ) || ( '' == get_option( 'addf_gr_product_purchase_mail_subject_co_registrant_heading' ) ) ) {
				update_option( 'addf_gr_product_purchase_mail_subject_co_registrant_heading', esc_html__( 'Product is purchased', 'addf_giftr' ) );
			}
			// content purchase for admin
			if ( ( ! get_option( 'email_addf_gift_registry_email_syntax_admin' ) ) || ( '' == get_option( 'email_addf_gift_registry_email_syntax_admin' ) ) ) {
				update_option( 'email_addf_gift_registry_email_syntax_admin', '<p> ' . esc_html__( 'Congratulations ,Dear admin a user purchased {product_name} for {registrant_name} ( {registrant_id} ) from {registry_title} as on {time_of_purchase}', 'addf_giftr' ) . '</p>' );
			}
			if ( ( ! get_option( 'email_addf_gift_registry_email_syntax_registrant' ) ) || ( '' == get_option( 'email_addf_gift_registry_email_syntax_registrant' ) ) ) {
				update_option( 'email_addf_gift_registry_email_syntax_registrant', '<p> ' . esc_html__( 'Congratulations ,Dear user {registrant_name}( {registrant_id} ) your friend have  purchased {product_name} from {registry_title} for you as on {time_of_purchase}', 'addf_giftr' ) . '</p>' );
			}
			if ( ( ! get_option( 'email_addf_gift_registry_email_syntax_co_registrant' ) ) || ( '' == get_option( 'email_addf_gift_registry_email_syntax_co_registrant' ) ) ) {
				update_option( 'email_addf_gift_registry_email_syntax_co_registrant', '<p> ' . esc_html__( 'Congratulations ,Dear user. A user purchased {product_name} for {registrant_name}  from {registry_title} where you are co registrant as on {time_of_purchase}', 'addf_giftr' ) . '</p>' );
			}
			// for expire
			if ( ( ! get_option( 'email_addf_gift_registry_email_syntax_reg_expired' ) ) || ( '' == get_option( 'email_addf_gift_registry_email_syntax_reg_expired' ) ) ) {
				update_option( 'email_addf_gift_registry_email_syntax_reg_expired', '<p>' . esc_html__( 'Dear user {registrant_name}( {registrant_id} )', 'addf_giftr' ) . ' <br>  ' . esc_html__( 'your Gift Registry named as {registry_title} has been expired as on {current_time}', 'addf_giftr' ) . '</p>' );
			}
			// for privacy policy
			if ( ( ! get_option( 'addf_gr_enable_pp_text' ) ) || ( '' == get_option( 'addf_gr_enable_pp_text' ) ) ) {
				update_option( 'addf_gr_enable_pp_text', wp_kses_post( 'I agree to the term and conditions' ) );
			}
			// for no registry created yet
			if ( ( ! get_option( 'addf_gr_empty_gr_text' ) ) || ( '' == get_option( 'addf_gr_empty_gr_text' ) ) ) {
				update_option( 'addf_gr_empty_gr_text', wp_kses_post( 'No gift registry found <a href="{gift_registry_page}" > click here </a> to create new registry' ) );
			}
			// for btn text
			if ( ( ! get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_btn_text' ) ) || ( '' == get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_btn_text' ) ) ) {
				update_option( 'wc_settings_tab_gift_registry_notify_gift_registry_btn_text', esc_html__( 'Add to Gift Registry', 'addf_giftr' ) );
			}
			// end of save default settings
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

		public function generate_addf_gr_product_id( $product_id, $variation_id = 0, $variation = array(), $addf_post_id = array() ) {
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
			if ( is_array( $addf_post_id ) && ! empty( $addf_post_id ) ) {
				$addf_post_id_key = '';
				foreach ( $addf_post_id as $key => $value ) {
					if ( is_array( $value ) || is_object( $value ) ) {
						$value = http_build_query( $value );
					}
					$addf_post_id_key .= trim( $key ) . trim( $value );
				}
				$id_parts[] = $addf_post_id_key;
			}
			return apply_filters( 'addf_gr_prime_product_id', md5( implode( '_', $id_parts ) ), $product_id, $variation_id, $variation, $addf_post_id );
		}
	}
	new AF_Gift_Registry_Main();
}
