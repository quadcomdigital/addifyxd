<?php
/**
 * Constructor of membership activated.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
class ADDF_GIFT_REGISTRY_SHARE_WP_MAIL extends WC_Email {
	public function __construct() {
		$this->id             = 'addf_gr_share_registry_wp_email';
		$this->title          = __( 'Gift Registry', 'addf_giftr' );
		$this->customer_email = true;
		$this->description    = __( 'The mail will be send to the mail when registrant share registry via mail.', 'addf_giftr' );
		$this->template_base  = ADDF_GR_DIR;
		$this->template_html  = 'emails/share-registry/email-gr-html-template.php';
		$this->template_plain = 'emails/share-registry/email-gr-plain-template.php';
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
		return __( '[{site_title}]: ' . get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_site_title' ), 'addf_giftr' );
	}
	public function get_default_heading() {
		return __( 'Gift Registry : ' . get_option( 'wc_settings_tab_gift_registry_notify_gift_registry_site_title_heading' ), 'woocommerce' );
	}
	public function trigger( $addf_send_mail_To, $addf_gr_post_id, $addf_gr_user_id, $addf_g_r_mail_subject, $addf_g_r_user_message ) {
		$this->setup_locale();
		$gift_object = get_post( $addf_gr_post_id );
		if ( is_a( $gift_object, 'WP_POST' ) ) {
			$user_name                               = get_post_meta( $addf_gr_post_id, 'gift-registry-registrant-first-name', true ) . ' ' . get_post_meta( $addf_gr_post_id, 'gift-registry-registrant-last-name', true );
			$this->object                            = $gift_object;
			$this->reg_subject                       = $addf_g_r_mail_subject;
			$this->placeholders['{registrant_id}']   = $addf_gr_user_id;
			$this->placeholders['{registrant_name}'] = $user_name;
			$this->placeholders['{registry_title}']  = '<a href="' . get_permalink( $addf_gr_post_id ) . '">' . get_the_title( $addf_gr_post_id ) . '</a>';
			$this->placeholders['{registry_url}']    = get_permalink( $addf_gr_post_id );
			$this->placeholders['{registrant_msg}']  = $addf_g_r_user_message;
			$this->recipient                         = $addf_send_mail_To;
		}
		if ( $this->is_enabled() && $this->get_recipient() ) {
			if ( $this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() ) ) {
				WC()->session->set( 'addf_prc_notify_user_share_mail_sent', 'sent' );
			} else {
				WC()->session->set( 'addf_prc_notify_user_share_mail_sent', 'not_sent' );
			}
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
