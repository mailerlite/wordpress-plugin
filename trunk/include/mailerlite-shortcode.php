<?php

class MailerLite_Shortcode
{

    /**
     * WordPress' init() hook
     */
    public static function init()
    {

        add_shortcode(
            'mailerlite_form', array('MailerLite_Shortcode',
                'mailerlite_generate_shortcode')
        );

        add_action(
            'wp_ajax_nopriv_mailerlite_tinymce_window',
            array('MailerLite_Shortcode', 'mailerlite_tinymce_window')
        );
        add_action(
            'wp_ajax_mailerlite_tinymce_window',
            array('MailerLite_Shortcode', 'mailerlite_tinymce_window')
        );

        if (get_user_option('rich_editing')) {
            add_filter(
                'mce_buttons', array('MailerLite_Shortcode',
                    'mailerlite_register_button')
            );
            add_filter(
                'mce_external_plugins', array('MailerLite_Shortcode',
                    'mailerlite_add_tinymce_plugin')
            );
        }

    }

    /**
     * Add tinymce button to toolbar
     *
     * @param $buttons
     * @return mixed
     */
    public static function mailerlite_register_button($buttons)
    {

        array_push($buttons, "mailerlite_shortcode");

        return $buttons;

    }

    /**
     * Register tinymce plugin
     *
     * @param $plugin_array
     * @return mixed
     */
    public static function mailerlite_add_tinymce_plugin($plugin_array)
    {
        $plugin_array['mailerlite_shortcode']
            = MAILERLITE_PLUGIN_URL . '/assets/js/mailerlite_shortcode.js';

        return $plugin_array;
    }

    /**
     * Returns selection of forms
     */
    public static function mailerlite_tinymce_window()
    {
        global $wpdb, $forms;

        if (!current_user_can('edit_posts')) {
            return;
        }

        $forms = $wpdb->get_results(
            "SELECT * FROM " . $wpdb->base_prefix . "mailerlite_forms"
        );

        include(MAILERLITE_PLUGIN_DIR
            . 'include/templates/common/tiny_mce.php');

        exit;
    }

    /**
     *
     * Converts shortcode into html
     *
     * @param $attributes
     *
     * @return string
     */
    public static function mailerlite_generate_shortcode($attributes)
    {
        $form_attributes = shortcode_atts(
            array(
                'form_id' => '1'
            ), $attributes
        );

        ob_start();
        load_mailerlite_form($form_attributes['form_id']);

        return ob_get_clean();
    }
}