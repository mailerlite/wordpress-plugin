<?php

require_once MAILERLITE_PLUGIN_DIR . "libs/mailerlite_rest/ML_Subscribers.php";

class MailerLite_Form
{
    public $form_id;
    public $form_type;
    public $form_name;
    public $form_data;

    /**
     *
     * Sets form data for class
     */
    public static function init()
    {
        add_action(
            'wp_enqueue_scripts',
            array('MailerLite_Form', 'add_jquery_validation_libraries')
        );
    }

    /**
     * Generates form by type
     *
     * @param $form_id
     * @param $form_type
     * @param $form_name
     * @param $form_data
     */
    public function generate_form($form_id, $form_type, $form_name, $form_data)
    {
        global $mailerlite_error;

        $this->form_id = $form_id;
        $this->form_type = $form_type;
        $this->form_name = $form_name;
        $this->form_data = $form_data;

        if ($this->form_type == 1) {
            $this->generate_custom_form();
        } else {
            $this->generate_embedded_form();
        }
    }

    /**
     * Saves form data
     */
    public static function save_form_data()
    {
        global $wpdb, $mailerlite_error;

        $form_id = isset($_POST['form_id']) ? absint($_POST['form_id']) : 0;
        $form_fields = isset($_POST['form_fields']) ? $_POST['form_fields']
            : array();

        $api_key = get_option('mailerlite_api_key');

        if ($form_id > 0 && isset($form_fields['email'])) {
            $form = $wpdb->get_row(
                "SELECT * FROM " . $wpdb->base_prefix
                . "mailerlite_forms WHERE id = " . $form_id
            );

            if (isset($form->data)) {

                $form->data = unserialize($form->data);

                $ML_Subscribers = new ML_Subscribers($api_key);

                $form_email = $form_fields['email'];
                unset($form_fields['email']);

                $fields = array();

                foreach ($form_fields as $field => $value) {
                    $fields[] = array('name' => $field, 'value' => $value);
                }

                $subscriber = array(
                    'email' => $form_email,
                    'fields' => $fields
                );

                foreach ($form->data['lists'] as $list) {
                    $ML_Subscribers->setId($list)->add($subscriber, 1);
                }

                echo json_encode(
                    array('status' => 'success', 'message' => __(
                        'Subscriber successfully saved', 'mailerlite'
                    ))
                );
            } else {
                echo json_encode(
                    array('status' => 'error',
                          'message' => __('Form not found', 'mailerlite'))
                );
            }
        } else {
            echo json_encode(
                array('status' => 'error',
                      'message' => __('Wrong data provided', 'mailerlite'))
            );
        }

        exit;
    }

    /**
     * Method to generate custom form
     */
    private function generate_custom_form()
    {
        global $form_id, $form_name, $form_data;

        $form_id = $this->form_id;
        $form_name = $this->form_name;
        $form_data = $this->form_data;

        include(MAILERLITE_PLUGIN_DIR
            . 'include/templates/forms/custom_form.php');

    }

    /**
     * Method to generate embedded form
     */
    private function generate_embedded_form()
    {
        global $form_data;

        $form_data = $this->form_data;

        include(MAILERLITE_PLUGIN_DIR
            . 'include/templates/forms/embedded_form.php');
    }

    /**
     * Register jQuery validation library
     */
    public static function add_jquery_validation_libraries()
    {

        if (!wp_script_is('jquery') && !wp_script_is('google-hosted-jquery')) {
            wp_register_script(
                'google-hosted-jquery',
                '//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js',
                false
            );
            wp_enqueue_script('google-hosted-jquery');
        }

        if (!wp_script_is('jquery-validation-plugin')
            && wp_script_is(
                'jquery'
            )
        ) {
            wp_register_script(
                'jquery-validation-plugin',
                'https://static.mailerlite.com/js/jquery.validate.min.js',
                array('jquery')
            );
        } else if (!wp_script_is('jquery-validation-plugin')
            && wp_script_is(
                'google-hosted-jquery'
            )
        ) {
            wp_register_script(
                'jquery-validation-plugin',
                'https://static.mailerlite.com/js/jquery.validate.min.js',
                array('google-hosted-jquery')
            );
        }

        wp_enqueue_script('jquery-validation-plugin');

    }
}

add_action(
    'wp_ajax_nopriv_mailerlite_subscribe_form',
    array('MailerLite_Form', 'save_form_data')
);
add_action(
    'wp_ajax_mailerlite_subscribe_form',
    array('MailerLite_Form', 'save_form_data')
);


/**
 * Loads MailerLite form
 *
 * @param $form_id
 */
function load_mailerlite_form($form_id)
{
    global $wpdb;

    $form = $wpdb->get_row(
        "SELECT * FROM " . $wpdb->base_prefix . "mailerlite_forms WHERE id = "
        . $form_id
    );

    if (isset($form->data)) {
        $form_data = unserialize($form->data);

        $MailerLite_form = new Mailerlite_Form();
        $MailerLite_form->generate_form(
            $form_id, $form->type, $form->name, $form_data
        );
    }
}

function mailerlite_universal() { ?>
    <!-- MailerLite Universal -->
    <script>
        (function(m,a,i,l,e,r){m['MailerLiteObject']=e;function f(){
        var c={a:arguments,q:[]};var r=this.push(c);return "number"!=typeof r?r:f.bind(c.q);}
        f.q=f.q||[];m[e]=m[e]||f.bind(f.q);m[e].q=m[e].q||f.q;r=a.createElement(i);
        var _=a.getElementsByTagName(i)[0];r.async=1;r.src=l+'?'+(~~(new Date().getTime()/10000000));
        _.parentNode.insertBefore(r,_);})(window, document, 'script', 'https://static.mailerlite.com/js/universal.js', 'ml');

        var ml_account = ml('accounts', '<?php echo get_option('account_id'); ?>', '<?php echo get_option('account_subdomain'); ?>', 'load');
    </script>
    <!-- End MailerLite Universal -->
<?php }

$mailerlite_api_key = get_option('mailerlite_api_key');
$account_id = get_option('account_id');
$account_subdomain = get_option('account_subdomain');
$mailerlite_popups_disabled = get_option('mailerlite_popups_disabled');

if (!$mailerlite_popups_disabled && $mailerlite_api_key && $account_id && $account_subdomain) {
    add_action('wp_head', 'mailerlite_universal');
}
