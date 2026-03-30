<?php
/**
 * Constructor of membership activated.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
class ADDF_GIFT_REGISTRY_EXPIRE_WP_MAIL extends WC_Email {
	public function __construct() {
		$this->id             = 'addf_gr_expire_registry_wp_email';
		$this->title          = __( 'Gift Registry', 'addf_giftr' );
		$this->customer_email = true;
		$this->description    = __( 'The mail will be send to registrant whenever a registry is expired.', 'addf_giftr' );
		$this->template_base  = ADDF_GR_DIR;
		$this->template_html  = 'emails/registry-expire/email-gr-html-template.php';
		$this->template_plain = 'emails/registry-expire/email-gr-plain-template.php';
		$this->placeholders   = array(
			'{registrant_id}'   => '',
			'{registrant_name}' => '',
			'{registry_title}'  => '',
			'{registry_url}'    => '',
			'{registrant_msg}'  => '',
		);
		parent::__construct();
		add_action( 'adds_gift_registry_create_new_registry_mail', array( $this, 'trigger' ), 10, 1 );
	}
	public function get_default_subject() {
		return __( '[{site_title}]: ' . get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_exp_site_title' ), 'addf_giftr' );
	}
	public function get_default_heading() {
		return __( 'Gift Registry : ' . get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_exp_site_title_heading' ), 'woocommerce' );
	}
	public function trigger( $addf_gr_post_id ) {
		$this->setup_locale();
		$gift_object = get_post( $addf_gr_post_id );
		if ( is_a( $gift_object, 'WP_POST' ) ) {
			$addf_gr_user_id                         = get_post_meta( $addf_gr_post_id, 'gift-registry-registrant-user_is_wp', true );
			$addf_send_mail_To                       = get_post_meta( $addf_gr_post_id, 'gift-registry-registrant-email', true );
			$user_name                               = get_post_meta( $addf_gr_post_id, 'gift-registry-registrant-first-name', true ) . ' ' . get_post_meta( $addf_gr_post_id, 'gift-registry-registrant-last-name', true );
			$this->object                            = $gift_object;
			$this->placeholders['{registrant_id}']   = $addf_gr_user_id;
			$this->placeholders['{registrant_name}'] = $user_name;
			$this->placeholders['{current_time}']    = gmdate( 'Y/m/d H:i:s' );
			$this->placeholders['{registry_title}']  = '<a href="' . get_permalink( $addf_gr_post_id ) . '">' . get_the_title( $addf_gr_post_id ) . '</a>';
			$this->recipient                         = $addf_send_mail_To;
		}
		if ( $this->is_enabled() && $this->get_recipient() ) {
			$this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
		}
		$this->restore_locale();
	}
	public function get_content_html() {
		return wc_get_template_html(
			$this->template_html,
			array(
				'registry'           => $this->object,
				'email_heading'      => $this->get_heading(),
				'additional_content' => $this->get_additional_content(),
				'sent_to_admin'      => false,
				'plain_text'         => false,
				'email'              => $this,
			),
			$this->template_base,
			$this->template_base
		);
	}
	public function get_content_plain() {
		return wc_get_template_html(
			$this->template_plain,
			array(
				'registry'           => $this->object,
				'email_heading'      => $this->get_heading(),
				'additional_content' => $this->get_additional_content(),
				'sent_to_admin'      => false,
				'plain_text'         => false,
				'email'              => $this,
			),
			$this->template_base,
			$this->template_base
		);
	}
}
