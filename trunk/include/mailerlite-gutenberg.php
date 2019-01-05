<?php

/**
 * Class MailerLite_Gutenberg
 */
class MailerLite_Gutenberg {

	/**
	 * WordPress' init() hook
	 */
	public static function init() {
		if ( function_exists( 'register_block_type' ) ) {
			wp_register_script(
				'mailerlite-form-block',
				MAILERLITE_PLUGIN_URL . '/assets/js/block.build.js',
				[
					'wp-api-fetch',
					'wp-blocks',
					'wp-components',
					'wp-compose',
					'wp-data',
					'wp-element',
					'wp-editor',
					'wp-i18n',
					'wp-url',
					'lodash',
				]
			);

			register_block_type( 'mailerlite/form-block', [
				'editor_script' => 'mailerlite-form-block',
			] );
		}
	}
}