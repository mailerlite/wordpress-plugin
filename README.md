# Official MailerLite WordPress plugin

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


# Installation

## Method 1

1. Login to your WordPress admin panel.
2. Open Plugins in the left sidebar, click Add New, and search for MailerLite plugin.
3. Install the plugin and activate it.

## Method 2

1. Download the MailerLite plugin.
2. Unzip the downloaded file and upload to your /wp-content/plugins/ folder.
3. Activate the plugin in Wordpress admin panel.

# Setup

1. After successful installation you will see MailerLite icon on the left sidebar. Click it.
2. Enter your MailerLite account API key. You can find it in your MailerLite account by clicking "Developer API" link in the bottom of the page.
3. Click "Add New Signup Form" .
4. Choose "Webforms created using MailerLite" if you wan't to use a sign up form that you already created in your MailerLite account or "Custom sign up form" if you want to create it now.
5. If you want to include sign up form in the sidebar of your blog or website, go to Appearance > Widgets and drag "MailerLite sign up form" to the sidebar. Choose which signup form to display.
6. If you want to include sign up form inside your post or page, use shortcodes. You will see MailerLite icon in your content editor, click it and choose which form to display. It will put a shortcode (for example [mailerlite_form form_id=1]) and your visitors will see signup form in that place.


# Frequently Asked Questions

### Requirements

* Requires PHP `>=5.3`.

### What is the plugin license?

* This plugin is released under a GPL license.

### What is MailerLite?
MailerLite is easy to use web-based email marketing software. It can help you create and send email newsletters, manage subscribers, track and analyze results.

### Do I need a MailerLite account to use this plugin?
Yes, you can easily register at https://www.mailerlite.com

### How to display a form in posts or pages?
Use shortcode with form id which you created [mailerlite_form form_id=1].

### How to display a form in widget areas like a sidebar?
Just add "Mailerlite sign up form widget" and select form you have created

### How to display a form in my template files?

Use the `load_mailerlite_form($id)` function.

```php
<?php
if( function_exists( 'load_mailerlite_form' ) ) {
    load_mailerlite_form(0);
}
```

### How can I style the sign-up form?

You can use CSS rules to style the sign-up form, use the following CSS selectors to target the various form elements.

Every form can be different, because element ID of form is:

`#mailerlite-form_(your_form_id)`

Elements of form can be styled.

```css
.mailerlite-form .mailerlite-form-title {} /* the form title */
.mailerlite-form .mailerlite-form-description {} /* the form description */
.mailerlite-form .mailerlite-form-field label {} /* the form input label */
.mailerlite-form .mailerlite-form-field input {} /* the form inputs */
.mailerlite-form .mailerlite-form-loader {} /* the form loading text */
.mailerlite-form .mailerlite-subscribe-button-container {} /* the form button container */
.mailerlite-form .mailerlite-subscribe-button-container .mailerlite-subscribe-submit {} /* the form submit button */
.mailerlite-form .mailerlite-form-response {} /* the form response message */
```

Add your custom CSS rules to the end of your theme stylesheet, /wp-content/themes/your-theme-name/style.css. Do not add them to the plugin stylesheet as they will be automatically overwritten on the next plugin update.

### Where can I find my MailerLite API key?

[Check it here!](https://kb.mailerlite.com/does-mailerlite-offer-an-api "Check it here!")