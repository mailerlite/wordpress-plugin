<?php defined( 'ABSPATH' ) or die( "No direct access allowed!" );

require_once MAILERLITE_PLUGIN_DIR . "libs/mailerlite_rest/MailerLite_Forms_Groups.php";
require_once MAILERLITE_PLUGIN_DIR . "libs/mailerlite_rest/MailerLite_Forms_Fields.php";
require_once MAILERLITE_PLUGIN_DIR . "libs/mailerlite_rest/MailerLite_Forms_Webforms.php";
require_once MAILERLITE_PLUGIN_DIR . "libs/mailerlite_rest/MailerLite_Forms_Settings_Double_OptIn.php";

/**
 * Class MailerLite_Admin
 */
class MailerLite_Admin {

	const FIRST_GROUP_LOAD = 100;

	private static $initiated = false;
	private static $api_key = false;

	/**
	 * Initialization method
	 */
	public static function init() {
		$mailerlite_error = false;

		self::$api_key = get_option( 'mailerlite_api_key' );

		$account_id        = get_option( 'account_id' );
		$account_subdomain = get_option( 'account_subdomain' );

		if ( self::$api_key && ( ! $account_id || ! $account_subdomain ) ) {
			self::update_account_info();
		}

		if ( ! self::$initiated ) {
			self::init_hooks();
		}

		if ( isset( $_POST['action'] )
			 && $_POST['action'] == 'enter-mailerlite-key'
			 &&  (ctype_alnum($_POST['mailerlite_key']) || empty($_POST['mailerlite_key']))
		) {
			self::set_api_key();
		}

		if ( isset( $_POST['action'] )
		     && $_POST['action'] == 'enter-popup-forms'
		) {
			self::set_popups();
		}

		if ( isset( $_POST['action'] )
		     && $_POST['action'] == 'toggle-double-opt-in'
		) {
			self::toggle_double_opt_in();
		}

		add_action( 'wp_ajax_mailerlite_get_more_groups', 'MailerLite_Admin::ajax_get_more_groups' );
	}

	function ajax_get_more_groups() {
		global $wpdb;

        check_admin_referer( 'mailerlite_load_more_groups', 'ml_nonce' );

		$query = $wpdb->prepare(
			"SELECT *
			FROM {$wpdb->base_prefix}mailerlite_forms
			WHERE id=%d",
			$_POST['form_id']
		);
		$form = $wpdb->get_row($query);

		$form->data = unserialize( $form->data );

		$ML_Groups = new MailerLite_Forms_Groups( self::$api_key );

		$lists = $form->data['lists'];
		$groups_from_ml_extended = $ML_Groups->getAllJson( [
			'limit'  => 1000,
			'offset' => self::FIRST_GROUP_LOAD,
		] );
		$groups = array_filter($groups_from_ml_extended, function($group) use ($lists) {
			return ! in_array($group->id, $lists);
		});

		include( MAILERLITE_PLUGIN_DIR . 'include/templates/admin/ajax_groups.php' );

		exit;
	}

	/**
	 * Adds admin stuff
	 */
	private static function init_hooks() {
		self::$initiated = true;

		add_action(
			'admin_init',
			[ 'MailerLite_Admin', 'mailerlite_admin_init_setting' ]
		);
		add_action(
			'admin_menu', [
				'MailerLite_Admin',
				'mailerlite_admin_generate_menu_link',
			]
		);

		add_action( 'admin_enqueue_scripts', [ 'MailerLite_Admin', 'load_mailerlite_admin_css' ] );
	}

	public static function load_mailerlite_admin_css( $hook ) {
		$allowed_hooks = [
			'toplevel_page_mailerlite_main',
			'mailerlite_page_mailerlite_settings',
			'mailerlite_page_mailerlite_status',
		];

		if ( ! in_array( $hook, $allowed_hooks ) ) {
			return;
		}

		wp_register_style(
			'mailerlite.css',
			MAILERLITE_PLUGIN_URL . '/assets/css/mailerlite.css', [],
			MAILERLITE_VERSION
		);
		wp_enqueue_style( 'mailerlite.css' );
	}

	/**
	 * Generates admin menu links
	 */
	public static function mailerlite_admin_generate_menu_link() {
		add_menu_page(
			'MailerLite', 'MailerLite', 'manage_options', 'mailerlite_main',
			null, MAILERLITE_PLUGIN_URL . '/assets/image/icon.png'
		);

		add_submenu_page(
			'mailerlite_main',
			__( 'Forms', 'mailerlite' ),
			__( 'Signup forms', 'mailerlite' ),
			'manage_options',
			'mailerlite_main',
			[ 'MailerLite_Admin', 'mailerlite_main' ]
		);
		add_submenu_page(
			'mailerlite_main',
			__( 'Settings', 'mailerlite' ),
			__( 'Settings', 'mailerlite' ),
			'manage_options',
			'mailerlite_settings',
			[ 'MailerLite_Admin', 'mailerlite_settings' ]
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

	public static function mailerlite_admin_init_setting() {
	}

	/**
	 * Checks if there is API key set
	 */
	private static function mailerlite_api_key_require() {
		global $mailerlite_error;

		if ( self::$api_key == false ) {
			include( MAILERLITE_PLUGIN_DIR . 'include/templates/admin/api_key.php' );
			exit;
		}
	}

	/**
	 * Create, edit, list pages method
	 */
	public static function mailerlite_main() {
		global $wpdb, $mailerlite_error;

		// Check for api key
		self::mailerlite_api_key_require();

		$api_key = self::$api_key;
		$result  = '';

		// Create new signup form view
		if ( isset( $_GET['view'] ) && $_GET['view'] == 'create' ) {
			if ( isset( $_POST['create_signup_form'] ) ) {
				self::create_new_form( $_POST );
				wp_redirect(
					'admin.php?page=mailerlite_main&view=edit&id='
					. $wpdb->insert_id
				);
			} else {
				if ( isset( $_GET['noheader'] ) ) {
					require_once( ABSPATH . 'wp-admin/admin-header.php' );
				}
			}

			$ML_Webforms = new MailerLite_Forms_Webforms( $api_key );
			$webforms    = $ML_Webforms->getAllJson();

			if ( ! empty( $webforms->error ) && ! empty( $webforms->error->message ) ) {
				$mailerlite_error = '<u>' . __( 'Error happened', 'mailerlite' ) . '</u>: ' . $webforms->error->message;
			}

			if ( $ML_Webforms->hasCurlError() ) {
				$mailerlite_error = '<u>' . __( 'Send this error to info@mailerlite.com or our chat',
						'mailerlite' ) . '</u>: ' . $ML_Webforms->getResponseBody();
			}

			include( MAILERLITE_PLUGIN_DIR . 'include/templates/admin/create.php' );
		} // Edit signup form view
		elseif ( isset( $_GET['view'] ) && isset( $_GET['id'] )
		         && $_GET['view'] == 'edit'
		         && absint( $_GET['id'] )
		) {
			$_POST = array_map( 'stripslashes_deep', $_POST );

			$form_id = absint( $_GET['id'] );

			$query = $wpdb->prepare(
				"SELECT *
				FROM {$wpdb->base_prefix}mailerlite_forms
				WHERE id=%d",
				$form_id
			);
			$form = $wpdb->get_row($query);

			if ( isset( $form->data ) ) {
				$form->data = unserialize( $form->data );

				if ( $form->type == MailerLite_Form::TYPE_CUSTOM ) {
					add_filter(
						'wp_default_editor',
						function() {
							return 'tinymce';
						}
					);

					$ML_Groups = new MailerLite_Forms_Groups( $api_key );

					$groups_from_ml = $ML_Groups->getAllJson( [
						'limit'  => self::FIRST_GROUP_LOAD,
						'offset' => 0,
					] );

					$lists = $form->data['lists'];
					if (! isset($form->data['selected_groups'])) {
						$groups_selected = array_filter($groups_from_ml, function($group) use ($lists) {
							return in_array($group->id, $lists);
						});

						if (count($groups_selected) != count($lists)) {
							$groups_from_ml_extended = $ML_Groups->getAllJson([
								'limit'  => 1100,
								'offset' => 0,
							]);

							$groups_selected = array_filter($groups_from_ml_extended, function($group) use ($lists) {
								return in_array($group->id, $lists);
							});
						}
					} else {
						$groups_selected = $form->data['selected_groups'];
					}

					$groups_not_selected = array_filter($groups_from_ml, function($group) use ($lists) {
						return ! in_array($group->id, $lists);
					});
					$groups = array_merge($groups_selected, $groups_not_selected);

					$can_load_more_groups = self::checkIfMoreGroups($ML_Groups);

					if ( $ML_Groups->hasCurlError() ) {
						$mailerlite_error = '<u>' . __( 'Send this error to info@mailerlite.com or our chat',
								'mailerlite' ) . '</u>: ' . $ML_Groups->getResponseBody();

					}

					$ML_Fields = new MailerLite_Forms_Fields( $api_key );
					$fields    = $ML_Fields->getAllJson();

					if ( isset( $_POST['save_custom_signup_form'] ) ) {
						$form_name        = self::issetWithDefault( 'form_name',
							__( 'Subscribe for newsletter!', 'mailerlite' ) );
						$form_title       = self::issetWithDefault( 'form_title',
							__( 'Newsletter signup', 'mailerlite' ) );
						$form_description = self::issetWithDefault( 'form_description',
							__( 'Just simple MailerLite form!', 'mailerlite' ), false );
						$success_message  = self::issetWithDefault( 'success_message',
							'<span style="color: rgb(51, 153, 102);">' . __( 'Thank you for sign up!',
								'mailerlite' ) . '</span>', false );
						$button_name      = self::issetWithDefault( 'button_name', __( 'Subscribe', 'mailerlite' ) );
						$please_wait      = self::issetWithDefault( 'please_wait' );
						$language         = self::issetWithDefault( 'language' );

						$selected_fields = isset( $_POST['form_selected_field'] )
						                   && is_array(
							                   $_POST['form_selected_field']
						                   ) ? $_POST['form_selected_field'] : [];
						$field_titles    = isset( $_POST['form_field'] )
						                   && is_array(
							                   $_POST['form_field']
						                   ) ? $_POST['form_field'] : [];

						if ( ! isset( $field_titles['email'] ) || $field_titles['email'] == '' ) {
							$field_titles['email'] = __( 'Email', 'mailerlite' );
						}

						$form_lists = isset( $_POST['form_lists'] ) && is_array( $_POST['form_lists'] ) ? $_POST['form_lists'] : [];

						$form_selected_groups =[];
						$selected_groups = explode(';*',$_POST['selected_groups']);

						foreach ($selected_groups as $group) {
							$group = explode('::', $group);
							$group_data = [];
							$group_data['id'] = $group[0];
							$group_data['name'] = $group[1];
							$form_selected_groups[] = (object)$group_data;
						}

						$prepared_fields = [];

						// Force to use email
						$prepared_fields['email'] = $field_titles['email'];

						foreach ( $selected_fields as $field ) {
							if ( isset( $field_titles[ $field ] ) ) {
								$prepared_fields[ $field ] = $field_titles[ $field ];
							}
						}

						$form_data = [
							'title'           => $form_title,
							'description'     => wpautop( $form_description, true ),
							'success_message' => wpautop( $success_message, true ),
							'button'          => $button_name,
							'please_wait'     => $please_wait,
							'language'        => $language,
							'lists'           => $form_lists,
							'fields'          => $prepared_fields,
							'selected_groups' => $form_selected_groups
						];

						$wpdb->update(
							$wpdb->base_prefix . 'mailerlite_forms',
							[
								'name' => $form_name,
								'data' => serialize( $form_data ),
							],
							[ 'id' => $form_id ],
							[],
							[ '%d' ]
						);

						$form->data = $form_data;
						$form->name = $form_name;

						$result = 'success';
					}

					include( MAILERLITE_PLUGIN_DIR . 'include/settings/languages.php' );

					include( MAILERLITE_PLUGIN_DIR . 'include/templates/admin/edit_custom.php' );
				} elseif ( $form->type == MailerLite_Form::TYPE_EMBEDDED ) {
					$ML_Webforms = new MailerLite_Forms_Webforms( $api_key );
					$webforms    = $ML_Webforms->getAllJson();

					if ( ! empty( $webforms->error ) && ! empty( $webforms->error->message ) ) {
						$mailerlite_error = '<u>' . __( 'Error happened',
								'mailerlite' ) . '</u>: ' . $webforms->error->message;
					}

					if ( $ML_Webforms->hasCurlError() ) {
						$mailerlite_error = '<u>' . __( 'Send this error to info@mailerlite.com or our chat',
								'mailerlite' ) . '</u>: ' . $ML_Webforms->getResponseBody();
					}

					$parsed_webforms = [];

					foreach ( $webforms as $webform ) {
						$parsed_webforms[ $webform->id ] = $webform->code;
					}

					if ( isset( $_POST['save_embedded_signup_form'] ) ) {
						$form_name = self::issetWithDefault( 'form_name', __( 'Embedded webform', 'mailerlite' ) );

						$form_webform_id = isset( $_POST['form_webform_id'] )
						                   && isset( $parsed_webforms[ $_POST['form_webform_id'] ] )
							? $_POST['form_webform_id'] : 0;

						$form_data = [
							'id'   => $form_webform_id,
							'code' => $parsed_webforms[ $form_webform_id ],
						];

						$wpdb->update(
							$wpdb->base_prefix . 'mailerlite_forms',
							[
								'name' => $form_name,
								'data' => serialize( $form_data ),
							],
							[ 'id' => $form_id ],
							[],
							[ '%d' ]
						);

						$form->data = $form_data;
						$form->name = $form_name;

						$result = 'success';
					}

					include( MAILERLITE_PLUGIN_DIR . 'include/templates/admin/edit_embedded.php' );
				}
			} else {
				$query = "
					SELECT * FROM
					{$wpdb->base_prefix}mailerlite_forms
					ORDER BY time DESC
				";
				$forms_data = $wpdb->get_results($query);

				include( MAILERLITE_PLUGIN_DIR . 'include/templates/admin/main.php' );
			}
		} // Delete signup form view
		elseif ( isset( $_GET['view'] ) && isset( $_GET['id'] )
		         && $_GET['view'] == 'delete'
		         && absint( $_GET['id'] ) ) {
			$wpdb->delete(
				$wpdb->base_prefix . 'mailerlite_forms', [ 'id' => $_GET['id'] ]
			);
			wp_redirect( 'admin.php?page=mailerlite_main' );
		} // Signup forms list
		else {
			$query = "
				SELECT * FROM
				{$wpdb->base_prefix}mailerlite_forms
				ORDER BY time DESC
			";
			$forms_data = $wpdb->get_results($query);

			include( MAILERLITE_PLUGIN_DIR . 'include/templates/admin/main.php' );
		}
	}

	/**
	 * Settings page method
	 */
	public static function mailerlite_settings() {
		global $mailerlite_error;
		self::mailerlite_api_key_require();

		$api_key = "....".substr(self::$api_key, -4);

		$ML_Settings_Double_OptIn   = new MailerLite_Forms_Settings_Double_OptIn( self::$api_key );
		$double_optin_enabled       = $ML_Settings_Double_OptIn->status();
		$double_optin_enabled_local = ! get_option( 'mailerlite_double_optin_disabled' );

		// Make sure they option is up-to-date
		if ( $double_optin_enabled != $double_optin_enabled_local ) {
			update_option( 'mailerlite_double_optin_disabled', ! get_option( 'mailerlite_double_optin_disabled' ) );
		}

		include( MAILERLITE_PLUGIN_DIR . 'include/templates/admin/settings.php' );
	}

	/**
	 * Checks and sets API key
	 */
	private static function set_api_key() {
		global $mailerlite_error;

		if ( function_exists( 'current_user_can' ) && ! current_user_can( 'manage_options' ) ) {
			die( __( 'You not allowed to do that', 'mailerlite' ) );
		}

		$key = preg_replace( '/[^a-z0-9]/i', '', $_POST['mailerlite_key'] );

		if ( $key == '' ) {
			// Allow to the remove the key
			update_option( 'mailerlite_api_key', $key );
			update_option( 'mailerlite_enabled', false );
			update_option( 'account_id', '' );
			update_option( 'account_subdomain', '' );
			update_option( 'mailerlite_popups_disabled', false );
			self::$api_key = $key;
		} else {
			$ML_Lists = new MailerLite_Forms_Groups( $key );
			$ML_Lists->getAll();
			$response = $ML_Lists->getResponseInfo();

			if ( $response['http_code'] == 401 ) {
				$mailerlite_error = __( 'Wrong MailerLite API key', 'mailerlite' );
			} elseif ( $ML_Lists->hasCurlError() ) {
				$mailerlite_error = '<u>' . __( 'Send this error to info@mailerlite.com or our chat',
						'mailerlite' ) . '</u>: ' . $ML_Lists->getResponseBody();
			} else {
				update_option( 'mailerlite_api_key', $key );
				update_option( 'mailerlite_enabled', true );
				self::$api_key = $key;

				self::update_account_info();
			}
		}
	}

	/**
	 * Checks and sets popup tracker setting
	 */
	private static function set_popups() {
		if ( function_exists( 'current_user_can' ) && ! current_user_can( 'manage_options' ) ) {
			die( __( 'You not allowed to do that', 'mailerlite' ) );
		}

		update_option( 'mailerlite_popups_disabled', ! get_option( 'mailerlite_popups_disabled' ) );
	}

	/**
	 * Checks and sets the double opt-in
	 */
	private static function toggle_double_opt_in() {
		if ( function_exists( 'current_user_can' ) && ! current_user_can( 'manage_options' ) ) {
			die( __( 'You not allowed to do that', 'mailerlite' ) );
		}

		self::mailerlite_api_key_require();

		$api_key = self::$api_key;

		$ML_Settings_Double_OptIn = new MailerLite_Forms_Settings_Double_OptIn( $api_key );

		if ( get_option( 'mailerlite_double_optin_disabled' ) ) {
			$ML_Settings_Double_OptIn->enable();
		} else {
			$ML_Settings_Double_OptIn->disable();
		}

		update_option( 'mailerlite_double_optin_disabled', ! get_option( 'mailerlite_double_optin_disabled' ) );
	}

	public static function update_account_info() {
		// request to mailerlite api
		$ch = curl_init();

		curl_setopt_array( $ch, [
			CURLOPT_URL            => 'https://api.mailerlite.com/api/v2',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_TIMEOUT        => 30,
			CURLOPT_HTTPHEADER     => [
				'X-MailerLite-ApiKey: ' . self::$api_key,
			],
		] );

		$output = curl_exec( $ch );
		curl_close( $ch );

		$response = json_decode( $output );

		if ( ! empty( $response->account ) ) {
			update_option( 'account_id', $response->account->id );
			update_option( 'account_subdomain', $response->account->subdomain );
			update_option( 'mailerlite_popups_disabled', false );
		}
	}

	/**
	 * Create new signup form
	 *
	 * @param $data
	 */
	private static function create_new_form( $data ) {
		global $wpdb, $mailerlite_error;

		$form_type = in_array( $data['form_type'], [ MailerLite_Form::TYPE_CUSTOM, MailerLite_Form::TYPE_EMBEDDED ] )
			? $data['form_type'] : MailerLite_Form::TYPE_CUSTOM;

		if ( $form_type == MailerLite_Form::TYPE_CUSTOM ) {
			$form_name = __( 'New custom signup form', 'mailerlite' );
			$form_data = [
				'title'           => __( 'Newsletter signup', 'mailerlite' ),
				'description'     => __(
					'Just simple MailerLite form!', 'mailerlite'
				),
				'success_message' => '<span style="color: rgb(51, 153, 102);">' . __(
						'Thank you for sign up!', 'mailerlite'
					) . '</span>',
				'button'          => __( 'Subscribe', 'mailerlite' ),
				'lists'           => [],
				'fields'          => [ 'email' => __( 'Email', 'mailerlite' ) ],
			];

			if ( array_key_exists( 'create_signup_form_now', $_POST ) ) {
				$form_name          = $_POST['form_name'];
				$form_data['lists'] = $_POST['form_lists'];
				$selected_groups = explode(';*',$_POST['selected_groups']);

				foreach ($selected_groups as $group) {
					$group = explode('::', $group);
					$group_data = [];
					$group_data['id'] = $group[0];
					$group_data['name'] = $group[1];
					$form_data['selected_groups'][] = (object)$group_data;
				}
			} else {
				$ML_Groups = new MailerLite_Forms_Groups( self::$api_key );
				$groups    = $ML_Groups->getAllJson([
					'limit'  => self::FIRST_GROUP_LOAD,
					'offset' => 0
				]);
				$can_load_more_groups = self::checkIfMoreGroups($ML_Groups);

				require_once( ABSPATH . 'wp-admin/admin-header.php' );
				include( MAILERLITE_PLUGIN_DIR . 'include/templates/admin/create_custom.php' );
				exit;
			}
		} else {
			$form_name = __( 'New embedded signup form', 'mailerlite' );
			$form_data = [
				'id'   => 0,
				'code' => 0,
			];
		}

		$wpdb->insert( $wpdb->base_prefix . 'mailerlite_forms', [
			'name' => $form_name,
			'time' => date( 'Y-m-d h:i:s' ),
			'type' => $form_type,
			'data' => serialize( $form_data ),
		] );
	}

	/**
	 * Helper to reuse input field with default data
	 *
	 * @param string $post_key
	 * @param string $default
	 * @param bool   $sanitize
	 *
	 * @return string
	 */
	private static function issetWithDefault( $post_key, $default = '', $sanitize = true ) {
		if ( isset( $_POST[ $post_key ] ) ) {
			if ( $sanitize ) {
				return sanitize_text_field( $_POST[ $post_key ] );
			}

			return $_POST[ $post_key ];
		}

		return $default;
	}

	private static function checkIfMoreGroups($ML_Groups)
	{
		$can_load_more_groups_check = $ML_Groups->getAllJson( [
			'limit'  => 1,
			'offset' => self::FIRST_GROUP_LOAD,
		] );

		return count( $can_load_more_groups_check ) > 0;
	}
}
