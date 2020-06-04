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
				],
				MAILERLITE_VERSION
			);

            add_action('enqueue_block_editor_assets', ['MailerLite_Gutenberg', 'enqueue_gutenberg_scripts'], 100, 0);

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

    public static function enqueue_gutenberg_scripts()
    {
        wp_localize_script('mailerlite-form-block', 'mailerlite_vars', [
            'ml_nonce' => wp_create_nonce('mailerlite_gutenberg'),
        ]);
	}

	/**
	 * Return all forms for the block editor
	 */
	public static function ajax_forms() {
		global $wpdb;

        check_admin_referer( 'mailerlite_gutenberg', 'ml_nonce' );

        $query = "
			SELECT * FROM
			{$wpdb->base_prefix}mailerlite_forms
			ORDER BY time DESC
		";
		$forms_data = $wpdb->get_results($query);

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
        check_admin_referer( 'mailerlite_preview', 'ml_nonce' );

        include( MAILERLITE_PLUGIN_DIR . 'include/templates/forms/preview.php' );
		exit;
	}

	/**
	 * The selected block preview iframe - used to display the form without interruptions
	 */
	public function form_preview_iframe() {
		global $wpdb;

        check_admin_referer( 'mailerlite_gutenberg', 'ml_nonce' );

        $query = $wpdb->prepare(
			"SELECT * FROM
			{$wpdb->base_prefix}mailerlite_forms
			WHERE id = %d
			ORDER BY time DESC",
			$_POST['form_id']
		);
		$form = $wpdb->get_results($query);

		if ( count( $form ) === 0 ) {
			echo wp_send_json_success( [ 'html' => false, 'edit_link' => false ] );
		}

		$nonce = wp_create_nonce('mailerlite_preview');

        $url = admin_url('admin-ajax.php').'?action=mailerlite_gutenberg_form_preview2&ml_nonce='.$nonce.'&form_id='.$_POST['form_id'];

		ob_start();
		include( MAILERLITE_PLUGIN_DIR . 'include/templates/forms/iframe.php' );
		$html = ob_get_clean();

        $nonce = wp_create_nonce('mailerlite_redirect');

        echo wp_send_json_success( [
			'html'      => $html,
			'edit_link' => admin_url( 'admin-ajax.php' ) . '?action=mailerlite_redirect_to_form_edit&ml_nonce='.$nonce.'&form_id=' . $_POST['form_id'],
		] );
	}
}
