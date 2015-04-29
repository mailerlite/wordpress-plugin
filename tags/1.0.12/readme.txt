=== Plugin Name ===
Contributors: mailerlite
Donate link: https://www.mailerlite.com/
Tags: mailerlite, newsletter, subscribe, form, webform
Requires at least: 3.0.1
Tested up to: 4.1
Stable tag: 1.0.12
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add newsletter sign up forms to your WordPress site. Subscribers will be saved directly to your MailerLite account. Super easy to set up!

== Description ==

= Official MailerLite WordPress plugin =

The Official MailerLite Sign Up Form plugin makes it easy to grow your newsletter subscriber list. Use the plugin to add newsletter sign up form to your Wordpress blog or website and automatically integrate it with your MailerLite account.

If you don't have MailerLite account yet, [you can signup for a FREE trial here](https://www.mailerlite.com/).

Once you activate the plugin, you'll be able to select and add any of webforms you have in your MailerLite account or create a new webform. Place the webform in the sidebar using widget or put it enywhere in your post with a shortcode.

Setup is fast and easy! You just need to enter your MailerLite account API code and you're all set.

Plugin features:

* Add webforms from your MailerLite account to your Wordpress blog or website
* Create new webforms
* Save subscribers automatically to your MailerLite account
* Place webforms using widget or shortcode
* Double opt-in signup
* Setup welcome emails in your MailerLite account

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

* Requires PHP5.

= What is the plugin license? =

* This plugin is released under a GPL license.

= What is MailerLite? =
MailerLite is easy to use web-based email marketing software. It can help you create and send email newsletters, manage subscribers, track and analyze results.

= Do I need a MailerLite account to use this plugin? =
Yes, you can easily register at www.mailerlite.com

= How to display a form in posts or pages? =
Use shortcode with form id which you created [mailerlite form_id=0].

= How to display a form in widget areas like a sidebar? =
Just add "Mailerlite sign up form widget" and select form you have created

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

[Check it here!](http://mailerlite.helpscoutdocs.com/article/12-does-mailerlite-offer-an-api "Check it here!")

== Screenshots ==

1. screenshot-1.jpg
2. screenshot-2.jpg
3. screenshot-3.jpg
4. screenshot-4.jpg
5. screenshot-5.jpg
6. screenshot-6.jpg


== Changelog ==

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
