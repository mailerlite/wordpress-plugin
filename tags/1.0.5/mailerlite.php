<?php

/**
 * Plugin Name: Official MailerLite Sign Up Forms
 * Description: Official MailerLite Sign Up Forms plugin for WordPress. Ability to embed MailerLite webforms and create custom ones just with few clicks.
 * Version: 1.0.5
 * Author: MailerGroup
 * Author URI: https://www.mailerlite.com
 * License: GPLv2 or later

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

define( 'MAILERLITE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'MAILERLITE_PLUGIN_URL', plugins_url( '', __FILE__ ) );

define( 'MAILERLITE_VERSION', '1.0.4');

function mailerlite_load_plugin_textdomain() {

    $domain = 'mailerlite';
    load_plugin_textdomain( $domain, FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );

}
add_action( 'init', 'mailerlite_load_plugin_textdomain' );

function mailerlite_install() {
    global $wpdb;

    $table_name = $wpdb->prefix . "mailerlite_forms";

    $sql = "CREATE TABLE ".$table_name." (
              id mediumint(9) NOT NULL AUTO_INCREMENT,
              time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
              name tinytext NOT NULL,
              type tinyint(1) default '1' NOT NULL,
              data text NOT NULL,
              PRIMARY KEY (id)
           );";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}

register_activation_hook( __FILE__, 'mailerlite_install' );

function register_mailerlite_styles() {
    wp_register_style( 'mailerlite_forms.css', MAILERLITE_PLUGIN_URL . '/assets/css/mailerlite_forms.css', array(), MAILERLITE_VERSION );
    wp_enqueue_style( 'mailerlite_forms.css');
}

add_action( 'wp_enqueue_scripts', 'register_mailerlite_styles' );

if ( is_admin() )
{
    require_once( MAILERLITE_PLUGIN_DIR . 'include/mailerlite-admin.php' );
    add_action( 'init', array( 'MailerLite_Admin', 'init' ) );
}


require_once( MAILERLITE_PLUGIN_DIR . 'include/mailerlite-widget.php' );
require_once( MAILERLITE_PLUGIN_DIR . 'include/mailerlite-shortcode.php' );

add_action( 'init', array( 'MailerLite_Shortcode', 'init' ) );
add_action( 'init', array( 'MailerLite_Form', 'init' ) );