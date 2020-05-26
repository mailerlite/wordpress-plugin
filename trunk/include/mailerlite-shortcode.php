<?php

/**
 * Class MailerLite_Shortcode
 */
class MailerLite_Shortcode {

	/**
	 * WordPress' init() hook
	 */
	public static function init() {
		add_shortcode(
			'mailerlite_form', [
				'MailerLite_Shortcode',
				'mailerlite_generate_shortcode',
			]
		);

		add_action(
			'wp_ajax_mailerlite_tinymce_window',
			[ 'MailerLite_Shortcode', 'mailerlite_tinymce_window' ]
		);

		add_action(
			'wp_ajax_mailerlite_redirect_to_form_edit',
			[ 'MailerLite_Shortcode', 'redirect_to_form_edit' ]
		);

		if ( get_user_option( 'rich_editing' ) ) {
			add_filter(
				'mce_buttons', [
					'MailerLite_Shortcode',
					'mailerlite_register_button',
				]
			);
			add_filter(
				'mce_external_plugins', [
					'MailerLite_Shortcode',
					'mailerlite_add_tinymce_plugin',
				]
			);
		}

	}

	/**
	 * Add tinymce button to toolbar
	 *
	 * @param $buttons
	 *
	 * @return mixed
	 */
	public static function mailerlite_register_button( $buttons ) {
		array_push( $buttons, "mailerlite_shortcode" );

		return $buttons;
	}

	/**
	 * Register tinymce plugin
	 *
	 * @param $plugin_array
	 *
	 * @return mixed
	 */
	public static function mailerlite_add_tinymce_plugin( $plugin_array ) {
		$plugin_array['mailerlite_shortcode'] = MAILERLITE_PLUGIN_URL . '/assets/js/mailerlite_shortcode.js';

		return $plugin_array;
	}

	/**
	 * Returns selection of forms
	 */
	public static function mailerlite_tinymce_window() {
		global $wpdb, $forms;

		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}

		$query = "
			SELECT *
			FROM {$wpdb->base_prefix}mailerlite_forms
		";
		$forms = $wpdb->get_results($query);

		include( MAILERLITE_PLUGIN_DIR . 'include/templates/common/tiny_mce.php' );

		exit;
	}

	/**
	 *
	 * Converts shortcode into html
	 *
	 * @param $attributes
	 *
	 * @return string
	 */
	public static function mailerlite_generate_shortcode( $attributes ) {
		$form_attributes = shortcode_atts( [
			'form_id' => '1',
		], $attributes );

		ob_start();
		load_mailerlite_form( $form_attributes['form_id'] );

		return ob_get_clean();
	}

	public function redirect_to_form_edit() {
		global $wpdb;

        check_admin_referer( 'mailerlite_redirect', 'ml_nonce' );

        $query = $wpdb->prepare(
			"SELECT * FROM
			{$wpdb->base_prefix}mailerlite_forms
			WHERE id = %d
			ORDER BY time DESC",
			$_GET['form_id']
		);
		$form = $wpdb->get_row($query);

		if ( $form != null ) {
			if ( $form->type == MailerLite_Form::TYPE_CUSTOM ) {
				wp_redirect( admin_url( 'admin.php?page=mailerlite_main&view=edit&id=' . $form->id ) );
				exit;
			} elseif ( $form->type == MailerLite_Form::TYPE_EMBEDDED ) {
				$form_data = unserialize( $form->data );
				wp_redirect( 'https://app.mailerlite.com/webforms/new/content/' . ( $form_data['id'] ) );
				exit;
			}
		} else {
			echo 'Form not found.';
			exit;
		}
	}
}
