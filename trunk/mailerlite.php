<?php

/**
 * Plugin Name: Official MailerLite Sign Up Forms
 * Description: Official MailerLite Sign Up Forms plugin for WordPress. Ability to embed MailerLite webforms and create custom ones just with few clicks.
 * Version: 1.1.22
 * Author: MailerGroup
 * Author URI: https://www.mailerlite.com
 * License: GPLv2 or later
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

define('MAILERLITE_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('MAILERLITE_PLUGIN_URL', plugins_url('', __FILE__));

define('MAILERLITE_VERSION', '1.1.22');

define('MAILERLITE_PHP_VERSION', '5.0.1');
define('MAILERLITE_WP_VERSION', '3.0.1');

function mailerlite_load_plugin_textdomain()
{

    $domain = 'mailerlite';
    load_plugin_textdomain(
        $domain, false, basename(dirname(__FILE__)) . '/languages/'
    );

}

add_action('init', 'mailerlite_load_plugin_textdomain');

function mailerlite_install()
{
    global $wp_version;
    global $wpdb;

    $message = '';

    if ( version_compare( PHP_VERSION, MAILERLITE_PHP_VERSION, '<' ) )
    {
        $message = '<p> The <strong>MailerLite</strong> plugin requires PHP version '.MAILERLITE_PHP_VERSION.' or greater.</p>';
    }

    if (version_compare( $wp_version, MAILERLITE_WP_VERSION, '<' ))
    {
        $message = '<p> The <strong>MailerLite</strong> plugin requires WordPress version '.MAILERLITE_WP_VERSION.' or greater.</p>';
    }

    if (!function_exists('curl_version'))
    {
        $message = '<p> The <strong>MailerLite</strong> plugin requires <strong>php-curl</strong> library. Please visit <a target="_blank" href="http://php.net/curl">php.net/curl</a></p>';
    }

    if ($message)
    {
        deactivate_plugins( basename( __FILE__ ) );
        wp_die($message, 'Plugin Activation Error', array('response' => 200, 'back_link' => TRUE));
    }

    $table_name = $wpdb->base_prefix . "mailerlite_forms";

    //$charset_collate = $wpdb->get_charset_collate();

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    $charset_collate = ' CHARACTER SET utf8 COLLATE utf8_bin';

    $sql = "CREATE TABLE IF NOT EXISTS " . $table_name . " (
              id mediumint(9) NOT NULL AUTO_INCREMENT,
              time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
              name tinytext NOT NULL,
              type tinyint(1) default '1' NOT NULL,
              data text NOT NULL,
              PRIMARY KEY (id)
           ) DEFAULT ".$charset_collate. ";";
    dbDelta($sql);

    $sql = "ALTER TABLE  " . $table_name . " " . $charset_collate . ";";
    $wpdb->query($sql);

    $sql = "ALTER TABLE  " . $table_name . " CHANGE  `name`  `name` TINYTEXT " . $charset_collate . ";";
    $wpdb->query($sql);

    $sql = "ALTER TABLE  " . $table_name . " CHANGE  `data`  `data` TEXT " . $charset_collate . ";";
    $wpdb->query($sql);
}

register_activation_hook(__FILE__, 'mailerlite_install');

function register_mailerlite_styles()
{
    wp_register_style(
        'mailerlite_forms.css', MAILERLITE_PLUGIN_URL
        . '/assets/css/mailerlite_forms.css', array(),
        MAILERLITE_VERSION
    );
    wp_enqueue_style('mailerlite_forms.css');
}

add_action('wp_enqueue_scripts', 'register_mailerlite_styles');

if (is_admin()) {
    require_once(MAILERLITE_PLUGIN_DIR . 'include/mailerlite-admin.php');
    add_action('init', array('MailerLite_Admin', 'init'));
}


require_once(MAILERLITE_PLUGIN_DIR . 'include/mailerlite-widget.php');
require_once(MAILERLITE_PLUGIN_DIR . 'include/mailerlite-shortcode.php');

add_action('init', array('MailerLite_Shortcode', 'init'));
add_action('init', array('MailerLite_Form', 'init'));
