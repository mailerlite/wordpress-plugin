<?php

/**
 * Class MailerLite_Gutenberg
 */
class MailerLite_Gutenberg {

	/**
	 * WordPress' init() hook
	 */
	public static function init() {

		/**
		 * Only run block editor is supported
		 */
		if ( function_exists( 'register_block_type' ) ) {
			wp_register_script(
				'mailerlite-form-block',
				MAILERLITE_PLUGIN_URL . '/assets/js/block.build.js',
				[
					'wp-blocks',
					'wp-components',
					'wp-element',
					'wp-editor',
					'wp-i18n',
				]
			);

			register_block_type( 'mailerlite/form-block', [
				'editor_script' => 'mailerlite-form-block',
			] );

			add_action(
				'wp_ajax_mailerlite_gutenberg_forms',
				[ 'MailerLite_Gutenberg', 'ajax_forms' ]
			);

			add_action(
				'wp_ajax_mailerlite_gutenberg_form_preview',
				[ 'MailerLite_Gutenberg', 'form_preview_iframe' ]
			);

			add_action(
				'wp_ajax_mailerlite_gutenberg_form_preview2',
				[ 'MailerLite_Gutenberg', 'form_preview_html' ]
			);

		}
	}

	/**
	 * Return all forms for the block editor
	 */
	public static function ajax_forms() {
		global $wpdb;
		$forms_data = $wpdb->get_results(
			"SELECT * FROM " . $wpdb->base_prefix . "mailerlite_forms ORDER BY time DESC"
		);

		$forms_data = array_map( function ( $form ) {
			return [
				'label' => $form->name,
				'value' => $form->id,
			];
		}, $forms_data );

		echo wp_send_json_success( [
			'forms'      => $forms_data,
			'count'      => count( $forms_data ),
			'forms_link' => admin_url( 'admin.php?page=mailerlite_main' ),
		] );
	}

	/**
	 * The selected block preview HTML
	 */
	public function form_preview_html() {
		include( MAILERLITE_PLUGIN_DIR . 'include/templates/forms/preview.php' );
		exit;
	}

	/**
	 * The selected block preview iframe - used to display the form without interruptions
	 */
	public function form_preview_iframe() {
		global $wpdb;

		$form = $wpdb->get_results(
			"SELECT * FROM `" . $wpdb->base_prefix . "mailerlite_forms` WHERE `id` = " . $_POST['form_id'] . " ORDER BY time DESC"
		);

		if ( count( $form ) === 0 ) {
			echo wp_send_json_success( [ 'html' => false, 'edit_link' => false ] );
		}

		$url = admin_url( 'admin-ajax.php' ) . '?action=mailerlite_gutenberg_form_preview2&form_id=' . $_POST['form_id'];

		ob_start();
		include( MAILERLITE_PLUGIN_DIR . 'include/templates/forms/iframe.php' );
		$html = ob_get_clean();

		echo wp_send_json_success( [
			'html'      => $html,
			'edit_link' => admin_url( 'admin-ajax.php' ) . '?action=mailerlite_redirect_to_form_edit&form_id=' . $_POST['form_id'],
		] );
	}
}