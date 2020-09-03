=== Plugin Name ===
Contributors: mailerlite
Donate link: https://www.mailerlite.com/
Tags: mailerlite, newsletter, subscribe, form, webform
Requires at least: 3.0.1
Tested up to: 5.4.1
Requires PHP: 5.6.0
Stable tag: 1.4.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add newsletter sign up forms to your WordPress site. Subscribers will be saved directly to your MailerLite account. Super easy to set up!

== Description ==

= Official MailerLite WordPress plugin =

The Official MailerLite Sign Up Form plugin makes it easy to grow your newsletter subscriber list from your WordPress blog or website. The plugin automatically integrates your Wordpress form with your MailerLite email marketing account.

If you don't have MailerLite account yet, [you can signup for a FREE trial here](https://www.mailerlite.com/).

Once you activate the plugin, you’ll be able to select and add any of the pre-built webforms from your MailerLite account or create a new form from scratch. You can place the form in the sidebar using a widget or use a shortcode to put it wherever you want.

Setup is fast and easy! You just need to enter your MailerLite account API code and you’re all set.

Plugin features include:

* Easily-to-add webforms from MailerLite to your WordPress blog or website
* Option to create new webforms
* Wordpress 5 new editor support
* Save subscribers automatically to your MailerLite account
* Place webforms using widget or shortcode
* Double opt-in signup
* Updated plugin layout
* Automate welcome emails from your MailerLite account

== Installation ==

= Method 1 =

1. Login to your WordPress admin panel.
2. Open Plugins in the left sidebar, click Add New, and search for MailerLite plugin.
3. Install the plugin and activate it.

= Method 2 =

1. Download the MailerLite plugin.
2. Unzip the downloaded file and upload to your /wp-content/plugins/ folder.
3. Activate the plugin in Wordpress admin panel.

= Setup =

1. After successful installation you will see MailerLite icon on the left sidebar. Click it.
2. Enter your MailerLite account API key. You can find it in your MailerLite account by clicking "Developer API" link in the bottom of the page.
3. Click "Add New Signup Form" .
4. Choose "Webforms created using MailerLite" if you wan't to use a sign up form that you already created in your MailerLite account or "Custom sign up form" if you want to create it now.
5. If you want to include sign up form in the sidebar of your blog or website, go to Appearance > Widgets and drag "MailerLite sign up form" to the sidebar. Choose which signup form to display.
6. If you want to include sign up form inside your post or page, use shortcodes. You will see MailerLite icon in your content editor, click it and choose which form to display. It will put a shortcode (for example [mailerlite_form form_id=1]) and your visitors will see signup form in that place.


== Frequently Asked Questions ==

= Requirements =

* Requires PHP5 and CURL.

= What is the plugin license? =

* This plugin is released under a GPL license.

= What is MailerLite? =
MailerLite is easy to use web-based email marketing software. It can help you create and send email newsletters, manage subscribers, track and analyze results.

= Do I need a MailerLite account to use this plugin? =
Yes, you can easily register at www.mailerlite.com

= How to display a form in posts or pages? =
Use shortcode with form id which you created [mailerlite_form form_id=1].

= How to display a form in widget areas like a sidebar? =
Just add "MailerLite sign up form widget" and select form you have created

= How to display a form in my template files? =

Use the load_mailerlite_form($id) function.

`
<?php
if( function_exists( 'load_mailerlite_form' ) ) {
    load_mailerlite_form(0);
}
`

= How can I style the sign-up form? =

You can use CSS rules to style the sign-up form, use the following CSS selectors to target the various form elements.

Every form can be different, because element ID of form is:

`#mailerlite-form_(your_form_id)`

Elements of form can be styled.

`
.mailerlite-form .mailerlite-form-title {} /* the form title */
.mailerlite-form .mailerlite-form-description {} /* the form description */
.mailerlite-form .mailerlite-form-field label {} /* the form input label */
.mailerlite-form .mailerlite-form-field input {} /* the form inputs */
.mailerlite-form .mailerlite-form-loader {} /* the form loading text */
.mailerlite-form .mailerlite-subscribe-button-container {} /* the form button container */
.mailerlite-form .mailerlite-subscribe-button-container .mailerlite-subscribe-submit {} /* the form submit button */
.mailerlite-form .mailerlite-form-response {} /* the form response message */
`

Add your custom CSS rules to the end of your theme stylesheet, /wp-content/themes/your-theme-name/style.css. Do not add them to the plugin stylesheet as they will be automatically overwritten on the next plugin update.

= Where can I find my MailerLite API key? =

[Check it here!](https://kb.mailerlite.com/does-mailerlite-offer-an-api/ "Check it here!")

== Screenshots ==

1. screenshot-1.jpg
2. screenshot-2.jpg
3. screenshot-3.jpg
4. screenshot-4.jpg
5. screenshot-5.jpg
6. screenshot-6.jpg


== Changelog ==

= 1.4.6 =
* Fix - Gutenberg script enqueue called action method was non-static

= 1.4.5 =
* Fix - Additional security for AJAX calls

= 1.4.4 =
* Fix - Additional security for database queries

= 1.4.3 =
* Fix - removed use of deprecated php method

= 1.4.2 =
* Fix - Styles on form description bug
* Update - Added a Load more button for groups on the creation stage phase.
* Update - Hidden api key

= 1.4 =
* Tweak - Show more than 100 groups when creating a custom form
* Tweak - Renamed some classes to avoid fatal errors from other plugin having the same class names
* Tweak - Reformatted code to WordPress code style
* Tweak - Loading custom form javascript after window load for better optimisations
* Tweak - Switched loading jQuery validate plugin from MailerLite CDN to your local WordPress site

= 1.3.6 =
* Feature - Status page to provide information about your environment
* Tweak - Even better support for older PHP versions in main plugin file
* Fix - Plugin's stylesheet is only included in pages where it is required

= 1.3.5 =
* Tweak - Better support for older PHP versions in main plugin file

= 1.3.4 =
* Fix - Subscriber adding bug

= 1.3.3 =
* Fix - Form edit redirect not working

= 1.3.2 =
* Fix - Form block js file version bump

= 1.3.1 =
* Fix - "Media views" block bug

= 1.3 =
* Feature - WordPress 5 block
* Feature - Double opt-in feature
* Tweak - New admin design
* Update - The plugin uses MailerLite API V2 from now on

= 1.2.8 =
Check WP 5.0 support
= 1.2.7 =
API url fix
= 1.2.6 =
Test new Wordpress
= 1.2.5 =
Updated forms (GDPR compatible)
= 1.2.4 =
release fix
= 1.2.3 =
fix form protocol
= 1.2.2 =
mistype fix in version no
= 1.2.1 =
adding en_US locale language translation files
= 1.2 =
some small php notice errors fixed. More clear tooltips about embed forms. Popup script settings moved to settings page. Added en_US locale translations (the same as en_EN)
= 1.1.25 =
short php open tag fix
= 1.1.24 =
bigger curl timeout for API call, temporary
= 1.1.23 =
fixed translations, added english .po/.mo files, added "please wait" message translate option to custom forms
= 1.1.22 =
updated jquery validation script URL to use static.mailerlite.com
= 1.1.21 =
small bugfixes
= 1.1.20 =
curl error showing, empty embed form bugfix, other bugfixes
= 1.1.19 =
translation fixes
= 1.1.18 =
mistype fix for old versions
= 1.1.17 =
translation errors for LT language, allowing only embed and button forms
= 1.1.16 =
* providing support for older PHP versions
= 1.1.15 =
* file_get_contents changed to cURL
= 1.1.14 =
* custom thank you message added
= 1.1.13 =
* tested with 4.6 version
= 1.1.12 =
* multisite support
= 1.1.11 =
* post escaped with stripslashes_deep
= 1.1.10 =
* mistype - signing up
= 1.1.9 =
* fixed app static script url
= 1.1.8 =
* fixed old syntax constructors in API classes
= 1.1.7 =
* option to activate popup form
= 1.1.6 =
* popup webforms added
= 1.1.5 =
* php notice fix
= 1.1.4 =
* fixed jquery bug
= 1.1.3 =
* some problem with version number
= 1.1.2 =
* wordpress issue bug fix
= 1.1.1 =
* updated readme and version constants
= 1.1 =
* tested with up to 4.5.1 wordpress. version. Added list of languages for validation messages. Fixed mistype "sign up"
= 1.0.18 =
* added php,wordpress and curl version checks before activation
= 1.0.17 =
* fix db queries for update
= 1.0.16 =
* links to https, db update charset
= 1.0.15 =
* Updated links to knowledge base about api key, changed db charset for table - utf8_bin
= 1.0.14 =
* Removed new lines for some cases
= 1.0.13 =
* Empty form description allowed
= 1.0.12 =
* Fix mistype in curl method
= 1.0.11 =
* Version fix
= 1.0.10 =
* Some code refactor, array fixes for PHP older than 5.4
= 1.0.9 =
* Curl safe mode fix
= 1.0.8 =
* Fix shortcode popup
= 1.0.7 =
* Fix shortcode
= 1.0.6 =
* Fix embedded form cache
= 1.0.5 =
* Fix for WP 4.0
= 1.0.4 =
* Subscribe button update
= 1.0.3 =
* jQuery load update
= 1.0.2 =
* Small changes
= 1.0.1 =
* Added translations
= 1.0 =
* First release

== Upgrade Notice ==

= 1.2.8 =
Check WP 5.0 support
= 1.2.7 =
API url fix
= 1.2.6 =
Test new Wordpress
= 1.2.5 =
Updated forms (GDPR compatible)
= 1.2.4 =
release fix
= 1.2.3 =
fix form protocol
= 1.2.2 =
mistype fix in version no
= 1.2.1 =
adding en_US locale language translation files
= 1.2 =
some small php notice errors fixed. More clear tooltips about embed forms. Popup script settings moved to settings page. Added en_US locale translations (the same as en_EN)
= 1.1.25 =
short php open tag fix
= 1.1.24 =
bigger curl timeout for API call, temporary
= 1.1.23 =
fixed translations, added english .po/.mo files, added "please wait" message translate option to custom forms
= 1.1.22 =
updated jquery validation script URL to use static.mailerlite.com
= 1.1.21 =
small bugfixes
= 1.1.20 =
curl error showing, empty embed form bugfix, other bugfixes
= 1.1.19 =
translation fixes
= 1.1.18 =
mistype fix for old versions
= 1.1.17 =
translation errors for LT language, allowing only embed and button forms
= 1.1.16 =
* providing support for older PHP versions
= 1.1.15 =
* file_get_contents changed to cURL
= 1.1.14 =
* custom thank you message added
= 1.1.13 =
* tested with 4.6 version
= 1.1.12 =
* multisite support
= 1.1.11 =
* post escaped with stripslashes_deep
= 1.1.10 =
* mistype - signing up
= 1.1.9 =
* fixed app static script url
= 1.1.8 =
* fixed old syntax constructors in API classes
= 1.1.7 =
* option to activate popup form
= 1.1.6 =
* popup webforms added
= 1.1.5 =
* php notice fix
= 1.1.4 =
* fixed jquery bug
= 1.1.3 =
* problem with number
= 1.1.2 =
* wordpress issue bug fix
= 1.1.1 =
* updated readme and version constants
= 1.1 =
* tested with up to 4.5.1 wordpress. version. Added list of languages for validation messages. Fixed mistype "sign up"
= 1.0.18 =
* added php,wordpress and curl version checks before activation
= 1.0.17 =
* fix db queries for update
= 1.0.16 =
* links to https, db update charset
= 1.0.15 =
* Updated links to knowledge base about api key, changed db charset for table - utf8_bin
= 1.0.14 =
* Removed new lines for some cases
= 1.0.13 =
* Empty form description allowed
= 1.0.12 =
* Fix mistype in curl method
= 1.0.11 =
* Version fix
= 1.0.10 =
* Some code refactor, array fixes for PHP older than 5.4
= 1.0.9 =
* Curl safe mode fix
= 1.0.8 =
* Fix shortcode popup
= 1.0.7 =
* Fix shortcode
= 1.0.6 =
* Fix embedded form cache
= 1.0.5 =
* Fix for WP 4.0
= 1.0.4 =
* Subscribe button update
= 1.0.3 =
* jQuery load update
= 1.0.2 =
* Small changes
= 1.0.1 =
* Added translations
= 1.0 =
* First release

== Arbitrary section ==
