<?php defined('ABSPATH') or die("No direct access allowed!");

require_once MAILERLITE_PLUGIN_DIR . "libs/mailerlite_rest/ML_Lists.php";
require_once MAILERLITE_PLUGIN_DIR . "libs/mailerlite_rest/ML_Webforms.php";

class MailerLite_Admin
{

    private static $initiated = false;
    private static $api_key = false;

    /**
     * Initialization method
     */
    public static function init()
    {

        global $mailerlite_error;

        $mailerlite_error = false;

        self::$api_key = get_option('mailerlite_api_key');

        if (!self::$initiated) {
            self::init_hooks();
        }

        if (isset($_POST['action'])
            && $_POST['action'] == 'enter-mailerlite-key'
        ) {
            self::set_api_key();
        }
    }

    /**
     * Adds admin stuff
     */
    private static function init_hooks()
    {

        self::$initiated = true;

        add_action(
            'admin_init',
            array('MailerLite_Admin', 'mailerlite_admin_init_setting')
        );
        add_action(
            'admin_menu', array(
                'MailerLite_Admin',
                'mailerlite_admin_generate_menu_link'
            )
        );

        wp_register_style(
            'mailerlite.css',
            MAILERLITE_PLUGIN_URL . '/assets/css/mailerlite.css', array(),
            MAILERLITE_VERSION
        );
        wp_enqueue_style('mailerlite.css');
    }

    /**
     * Generates admin menu links
     */
    public static function mailerlite_admin_generate_menu_link()
    {

        add_menu_page(
            'MailerLite', 'MailerLite', 'manage_options', 'mailerlite_main',
            null, MAILERLITE_PLUGIN_URL . '/assets/image/icon.png'
        );

        add_submenu_page(
            'mailerlite_main', __('Forms', 'mailerlite'),
            __('Signup forms', 'mailerlite'), 'manage_options',
            'mailerlite_main', array('MailerLite_Admin', 'mailerlite_main')
        );
        add_submenu_page(
            'mailerlite_main', __('Settings', 'mailerlite'),
            __('Settings', 'mailerlite'), 'manage_options',
            'mailerlite_settings',
            array('MailerLite_Admin', 'mailerlite_settings')
        );
    }

    public static function mailerlite_admin_init_setting()
    {

    }

    /**
     * Checks if there is API key set
     */
    private static function mailerlite_api_key_require()
    {
        global $mailerlite_error;

        if (self::$api_key == false) {
            include(MAILERLITE_PLUGIN_DIR
                . 'include/templates/admin/api_key.php');
            exit;
        }

    }

    /**
     * Create, edit, list pages method
     */
    public static function mailerlite_main()
    {
        global $fields, $lists, $form, $forms_data, $webforms, $mailerlite_error, $result, $wpdb;

        //Check for api key
        self::mailerlite_api_key_require();

        $api_key = self::$api_key;
        $result = '';

        //Create new signup form view
        if (isset($_GET['view']) && $_GET['view'] == 'create') {
            if (isset($_POST['create_signup_form'])) {
                self::create_new_form($_POST);
                wp_redirect(
                    'admin.php?page=mailerlite_main&view=edit&id='
                    . $wpdb->insert_id
                );
            } else {
                if (isset($_GET['noheader'])) {
                    require_once(ABSPATH . 'wp-admin/admin-header.php');
                }
            }

            $ML_Webforms = new ML_Webforms($api_key);
            $webforms = $ML_Webforms->getAll();
            $webforms = json_decode($webforms);

            include(MAILERLITE_PLUGIN_DIR
                . 'include/templates/admin/create.php');


        } //Edit signup form view
        else if (isset($_GET['view']) && isset($_GET['id'])
            && $_GET['view'] == 'edit'
            && absint($_GET['id'])
        ) {
            $form_id = absint($_GET['id']);

            $form = $wpdb->get_row(
                "SELECT * FROM " . $wpdb->prefix
                . "mailerlite_forms WHERE id = " . $form_id
            );

            if (isset($form->data)) {
                $form->data = unserialize($form->data);

                if ($form->type == 1) {
                    add_filter(
                        'wp_default_editor',
                        create_function('', 'return "tinymce";')
                    );

                    $ML_Lists = new ML_Lists($api_key);
                    $lists = $ML_Lists->getAll();
                    $lists = json_decode($lists);

                    $fields = $ML_Lists->setId($lists->Results[0]->id)
                        ->getFields();
                    $fields = json_decode($fields);

                    if (isset($_POST['save_custom_signup_form'])) {
                        $form_name = isset($_POST['form_name'])
                        && $_POST['form_name'] != ''
                            ? sanitize_text_field($_POST['form_name'])
                            : __(
                                'Subscribe for newsletter!', 'mailerlite'
                            );
                        $form_title = isset($_POST['form_title'])
                        && $_POST['form_title'] != ''
                            ? sanitize_text_field($_POST['form_title'])
                            : __(
                                'Newsletter signup', 'mailerlite'
                            );
                        $form_description = isset($_POST['form_description'])
                        && $_POST['form_description'] != ''
                            ? $_POST['form_description']
                            : __(
                                'Just simple MailerLite form!', 'mailerlite'
                            );
                        $button_name = isset($_POST['button_name'])
                        && $_POST['button_name'] != ''
                            ? sanitize_text_field($_POST['button_name'])
                            : __(
                                'Subscribe', 'mailerlite'
                            );

                        $selected_fields = isset($_POST['form_selected_field'])
                        && is_array(
                            $_POST['form_selected_field']
                        ) ? $_POST['form_selected_field'] : array();
                        $field_titles = isset($_POST['form_field'])
                        && is_array(
                            $_POST['form_field']
                        ) ? $_POST['form_field'] : array();

                        if (!isset($field_titles['email'])
                            || $field_titles['email'] == ''
                        ) {
                            $field_titles['email'] = __('Email', 'mailerlite');
                        }

                        $form_lists = isset($_POST['form_lists'])
                        && is_array(
                            $_POST['form_lists']
                        ) ? $_POST['form_lists'] : array();

                        $prepared_fields = array();

                        //Force to use email
                        $prepared_fields['email'] = $field_titles['email'];

                        foreach ($selected_fields as $field) {
                            if (isset($field_titles[$field])) {
                                $prepared_fields[$field]
                                    = $field_titles[$field];
                            }
                        }

                        $form_data = array(
                            'title' => $form_title,
                            'description' => wpautop($form_description, true),
                            'button' => $button_name,
                            'lists' => $form_lists,
                            'fields' => $prepared_fields
                        );

                        $wpdb->update(
                            $wpdb->prefix . 'mailerlite_forms',
                            array(
                                'name' => $form_name,
                                'data' => serialize($form_data)
                            ),
                            array('id' => $form_id),
                            array(),
                            array('%d')
                        );

                        $form->data = $form_data;

                        $result = 'success';
                    }

                    include(MAILERLITE_PLUGIN_DIR
                        . 'include/templates/admin/edit_custom.php');
                } else if ($form->type == 2) {
                    $ML_Webforms = new ML_Webforms($api_key);
                    $webforms = $ML_Webforms->getAll();
                    $webforms = json_decode($webforms);

                    $parsed_webforms = array();

                    foreach ($webforms->Results as $webform) {
                        $parsed_webforms[$webform->id] = $webform->code;
                    }

                    if (isset($_POST['save_embedded_signup_form'])) {
                        $form_name = isset($_POST['form_name'])
                        && $_POST['form_name'] != ''
                            ? sanitize_text_field($_POST['form_name'])
                            : __(
                                'Embedded webform', 'mailerlite'
                            );
                        $form_webform_id = isset($_POST['form_webform_id'])
                        && isset($parsed_webforms[$_POST['form_webform_id']])
                            ? $_POST['form_webform_id'] : 0;

                        $form_data = array(
                            'id' => $form_webform_id,
                            'code' => $parsed_webforms[$form_webform_id]
                        );

                        $wpdb->update(
                            $wpdb->prefix . 'mailerlite_forms',
                            array(
                                'name' => $form_name,
                                'data' => serialize($form_data)
                            ),
                            array('id' => $form_id),
                            array(),
                            array('%d')
                        );

                        $form->data = $form_data;

                        $result = 'success';
                    }

                    include(MAILERLITE_PLUGIN_DIR
                        . 'include/templates/admin/edit_embedded.php');
                }
            } else {
                $forms_data = $wpdb->get_results(
                    "SELECT * FROM " . $wpdb->prefix
                    . "mailerlite_forms ORDER BY time DESC"
                );

                include(MAILERLITE_PLUGIN_DIR
                    . 'include/templates/admin/main.php');
            }
        } //Delete signup form view
        else if (isset($_GET['view']) && isset($_GET['id'])
            && $_GET['view'] == 'delete'
            && absint($_GET['id'])
        ) {
            $wpdb->delete(
                $wpdb->prefix . 'mailerlite_forms', array('id' => $_GET['id'])
            );
            wp_redirect('admin.php?page=mailerlite_main');
        } //Signup forms list
        else {
            $forms_data = $wpdb->get_results(
                "SELECT * FROM " . $wpdb->prefix
                . "mailerlite_forms ORDER BY time DESC"
            );

            include(MAILERLITE_PLUGIN_DIR . 'include/templates/admin/main.php');
        }
    }

    /**
     * Settings page method
     */
    public static function mailerlite_settings()
    {
        global $api_key, $mailerlite_error;

        self::mailerlite_api_key_require();

        $api_key = self::$api_key;

        include(MAILERLITE_PLUGIN_DIR . 'include/templates/admin/settings.php');
    }

    /**
     * Checks and sets API key
     */
    private static function set_api_key()
    {
        global $mailerlite_error;

        if (function_exists('current_user_can')
            && !current_user_can(
                'manage_options'
            )
        ) {
            die(__('You not allowed to do that', 'mailerlite'));
        }

        $key = preg_replace('/[^a-z0-9]/i', '', $_POST['mailerlite_key']);

        $ML_Lists = new ML_Lists($key);
        $ML_Lists->getAll();
        $response = $ML_Lists->getResponseInfo();

        if ($response['http_code'] == 401) {
            $mailerlite_error = __('Wrong MailerLite API key', 'mailerlite');
        } else {
            update_option('mailerlite_api_key', $key);
            update_option('mailerlite_enabled', true);
            self::$api_key = $key;
        }
    }

    /**
     * Create new signup form
     *
     * @param $data
     */
    private static function create_new_form($data)
    {
        global $wpdb;

        $form_type = in_array($data['form_type'], array(1, 2))
            ? $data['form_type'] : 1;

        if ($form_type == 1) {
            $form_name = __('New custom signup form', 'mailerlite');
            $form_data = array(
                'title' => __('Newsletter signup', 'mailerlite'),
                'description' => __(
                    'Just simple MailerLite form!', 'mailerlite'
                ),
                'button' => __('Subscribe', 'mailerlite'),
                'lists' => array(),
                'fields' => array('email' => __('Email', 'mailerlite'))
            );
        } else {
            $form_name = __('New embedded signup form', 'mailerlite');
            $form_data = array(
                'id' => 0,
                'code' => 0
            );
        }

        $wpdb->insert(
            $wpdb->prefix . 'mailerlite_forms',
            array(
                'name' => $form_name,
                'time' => date('Y-m-d h:i:s'),
                'type' => $form_type,
                'data' => serialize($form_data)
            )
        );
    }
}
