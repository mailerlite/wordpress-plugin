<?php defined( 'ABSPATH' ) or die( "No direct access allowed!" );

/**
 * Class MailerLite_Admin_Status
 */
class MailerLite_Admin_Status {
	public static function init() {
		self::init_hooks();
	}

	private static function init_hooks() {
		add_action(
			'admin_menu', [
				'MailerLite_Admin_Status',
				'mailerlite_admin_generate_menu_link',
			]
		);
	}

	/**
	 * Generates admin menu links
	 */
	public static function mailerlite_admin_generate_menu_link() {
		global $menu, $submenu;


		if ( in_array( 'mailerlite_main', wp_list_pluck( $menu, 2 ) ) ) {
			return;
		}

		add_menu_page(
			'MailerLite',
			'MailerLite',
			'manage_options',
			'mailerlite_status',
			null, MAILERLITE_PLUGIN_URL . '/assets/image/icon.png'
		);

		add_submenu_page(
			'mailerlite_main',
			__( 'Status', 'mailerlite' ),
			__( 'Status', 'mailerlite' ),
			'manage_options',
			'mailerlite_status',
			[ 'MailerLite_Admin_Status', 'mailerlite_status' ]
		);
	}

	/**
	 * status page method
	 */
	public static function mailerlite_status() {
		global $mailerlite_error;

		$information = mailerlite_status_information();
		include( MAILERLITE_PLUGIN_DIR . 'include/templates/admin/status.php' );
	}

}
