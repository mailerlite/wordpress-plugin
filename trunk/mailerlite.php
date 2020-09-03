<?php

/**
 * Plugin Name: Official MailerLite Sign Up Forms
 * Description: Official MailerLite Sign Up Forms plugin for WordPress. Ability to embed MailerLite webforms and create custom ones just with few clicks.
 * Version: 1.4.6
 * Author: MailerGroup
 * Author URI: https://www.mailerlite.com
 * License: GPLv2 or later
 * Text Domain: mailerlite
 * Domain Path: /languages/
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301,
 * USA.
 */

define( 'MAILERLITE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'MAILERLITE_PLUGIN_URL', plugins_url( '', __FILE__ ) );

define( 'MAILERLITE_VERSION', '1.4.6' );

define( 'MAILERLITE_PHP_VERSION', '5.6.0' );
define( 'MAILERLITE_WP_VERSION', '3.0.1' );

function mailerlite_load_plugin_textdomain() {
	$domain = 'mailerlite';
	load_plugin_textdomain(
		$domain, false, basename( dirname( __FILE__ ) ) . '/languages/'
	);
}

add_action( 'init', 'mailerlite_load_plugin_textdomain' );

function mailerlite_install() {
	global $wp_version, $wpdb;

	$message = '';

	if ( version_compare( PHP_VERSION, MAILERLITE_PHP_VERSION, '<' ) ) {
		$message = '<p> The <strong>MailerLite</strong> plugin requires PHP version ' . MAILERLITE_PHP_VERSION . ' or greater. You are currently using PHP version ' . PHP_VERSION . '</p>';
	}

	if ( version_compare( $wp_version, MAILERLITE_WP_VERSION, '<' ) ) {
		$message = '<p> The <strong>MailerLite</strong> plugin requires WordPress version ' . MAILERLITE_WP_VERSION . ' or greater.</p>';
	}

	if ( ! function_exists( 'curl_version' ) ) {
		$message = '<p> The <strong>MailerLite</strong> plugin requires <strong>php-curl</strong> library. Please visit <a target="_blank" href="http://php.net/curl">php.net/curl</a></p>';
	}

	if ( $message ) {
		deactivate_plugins( basename( __FILE__ ) );
		wp_die( $message, 'Plugin Activation Error', [ 'response' => 200, 'back_link' => true ] );
	}

	$table_name = $wpdb->base_prefix . "mailerlite_forms";

	//$charset_collate = $wpdb->get_charset_collate();

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	$charset_collate = ' CHARACTER SET utf8 COLLATE utf8_bin';

	$sql = "CREATE TABLE IF NOT EXISTS " . $table_name . " (
              id mediumint(9) NOT NULL AUTO_INCREMENT,
              time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
              name tinytext NOT NULL,
              type tinyint(1) default '1' NOT NULL,
              data text NOT NULL,
              PRIMARY KEY (id)
           ) DEFAULT " . $charset_collate . ";";
	dbDelta( $sql );

	$sql = $wpdb->prepare(
		"ALTER TABLE %s %s;",
		$table_name,
		$charset_collate
	);
	$wpdb->query( $sql );

	$sql = $wpdb->prepare(
		"ALTER TABLE  %s CHANGE  `name` `name` TINYTEXT %s;",
		$table_name,
		$charset_collate
	);
	$wpdb->query( $sql );

	$sql = $wpdb->prepare(
		"ALTER TABLE %s CHANGE  `data` `data` TEXT %s;",
		$table_name,
		$charset_collate
	);
	$wpdb->query( $sql );
}

register_activation_hook( __FILE__, 'mailerlite_install' );

function register_mailerlite_styles() {
	wp_register_style(
		'mailerlite_forms.css',
		MAILERLITE_PLUGIN_URL . '/assets/css/mailerlite_forms.css', [],
		MAILERLITE_VERSION
	);
	wp_enqueue_style( 'mailerlite_forms.css' );
}

add_action( 'wp_enqueue_scripts', 'register_mailerlite_styles' );

function mailerlite_status_information_for_mailto_link() {
	$data = mailerlite_status_information();

	$body = "\n\n\n";

	$body .= "Official MailerLite Sign Up Forms information: \n\n";

	foreach ( $data as $group => $fields ) {
		$body .= sprintf( "# %s \n\n", $group );

		foreach ( $fields as $name => $value ) {
			$body .= sprintf( "%s: %s\n", $name, $value );
		}

		$body .= "\n";
	}

	$body = str_replace( "\n", '%0A', $body );

	return $body;
}

mailerlite_status_information_for_mailto_link();

function mailerlite_status_information() {
	global $wpdb;

	$theme        = wp_get_theme();
	$curl_version = '';
	if ( function_exists( 'curl_version' ) ) {
		$curl_info    = curl_version();
		$curl_version = $curl_info['version'] . ', ' . $curl_info['ssl_version'];
	}

	// Only if loading the plugin succeeded
	if ( class_exists( 'MailerLite_Form' ) ) {
		$query = "
			SELECT *
			FROM {$wpdb->base_prefix}mailerlite_forms
		";
		$forms = $wpdb->get_results($query);
		$number_of_custom_forms   = 0;
		$number_of_embedded_forms = 0;

		foreach ( $forms as $form ) {
			if ( $form->type == MailerLite_Form::TYPE_CUSTOM ) {
				$number_of_custom_forms ++;
			} elseif ( $form->type == MailerLite_Form::TYPE_EMBEDDED ) {
				$number_of_embedded_forms ++;
			}
		}
	}

	$environment_group = __( 'Environment', 'mailerlite' );
	$plugin_group      = __( 'Plugin', 'mailerlite' );

	$fields                                               = [];
	$fields['WordPress']['Version']                       = get_bloginfo( 'version' );
	$fields['WordPress']['Home URL']                      = get_option( 'home' );
	$fields['WordPress']['Site URL']                      = get_option( 'home' );
	$fields['WordPress']['Multisite']                     = is_multisite() ? 'Yes' : 'No';
	$fields['WordPress']['Debug mode']                    = ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? 'Yes' : 'No';
	$fields['WordPress']['Theme name']                    = $theme->get( 'Name' );
	$fields['WordPress']['Theme URI']                     = $theme->get( 'ThemeURI' );
	$fields['WordPress']['Active plugins']                = implode( ', ', get_option( 'active_plugins' ) );
	$fields[ $environment_group ]['Required PHP version'] = MAILERLITE_PHP_VERSION;
	$fields[ $environment_group ]['PHP version']          = phpversion();
	$fields[ $environment_group ]['Server information']   = isset( $_SERVER['SERVER_SOFTWARE'] ) ? wp_unslash( $_SERVER['SERVER_SOFTWARE'] ) : '';
	$fields[ $environment_group ]['cURL version']         = $curl_version;
	$fields[ $plugin_group ]['Version']                   = MAILERLITE_VERSION;
	$fields[ $plugin_group ]['API key provided']          = (bool) get_option( 'mailerlite_api_key' ) ? 'Yes' : 'No';
	$fields[ $plugin_group ]['Popups enabled']            = ! get_option( 'mailerlite_popups_disabled' ) ? 'Yes' : 'No';
	$fields[ $plugin_group ]['Double opt-in enabled']     = ! get_option( 'mailerlite_double_optin_disabled' ) ? 'Yes' : 'No';

	if ( class_exists( 'MailerLite_Form' ) ) {
		$fields[ $plugin_group ]['Custom forms']   = $number_of_custom_forms;
		$fields[ $plugin_group ]['Embedded forms'] = $number_of_embedded_forms;
	}

	return $fields;
}

mailerlite_status_information();

if ( in_array( 'official-mailerlite-sign-up-forms/mailerlite.php', get_option( 'active_plugins' ) ) ) {

	// Double check
	if ( ! version_compare( PHP_VERSION, MAILERLITE_PHP_VERSION, '<' ) ) {

		if ( is_admin() ) {
			require_once( MAILERLITE_PLUGIN_DIR . 'include/mailerlite-admin.php' );
			require_once( MAILERLITE_PLUGIN_DIR . 'include/mailerlite-admin-status.php' );

			add_action( 'init', [ 'MailerLite_Admin', 'init' ] );
			add_action( 'init', [ 'MailerLite_Admin_Status', 'init' ] );
		}

		require_once( MAILERLITE_PLUGIN_DIR . 'include/mailerlite-widget.php' );
		require_once( MAILERLITE_PLUGIN_DIR . 'include/mailerlite-shortcode.php' );
		require_once( MAILERLITE_PLUGIN_DIR . 'include/mailerlite-gutenberg.php' );

		add_action( 'init', [ 'MailerLite_Shortcode', 'init' ] );
		add_action( 'init', [ 'MailerLite_Form', 'init' ] );
		add_action( 'init', [ 'MailerLite_Gutenberg', 'init' ] );
	} else {
		function mailerlite_old_php_notice() {
			$class   = 'notice notice-error';
			$message = '<p> The <strong>MailerLite</strong> plugin requires PHP version ' . MAILERLITE_PHP_VERSION . ' or greater. You are currently using PHP version <strong>' . PHP_VERSION . '</strong></p>';

			printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), $message );
		}

		add_action( 'admin_notices', 'mailerlite_old_php_notice' );

		require_once( MAILERLITE_PLUGIN_DIR . 'include/mailerlite-admin-status.php' );
		add_action( 'init', [ 'MailerLite_Admin_Status', 'init' ] );
	}
}
