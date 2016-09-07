<?php defined('ABSPATH') or die("No direct access allowed!"); ?>
<?php include_once('header.php'); ?>

    <div class="wrap columns-2 dd-wrap">
        <h2><?php _e('Edit custom signup form', 'mailerlite'); ?></h2>
        <?php if (isset($result) && $result == 'success'): ?>
            <div id="message" class="updated below-h2"><p><?php _e('Form saved.', 'mailerlite'); ?> <a
                        href="<?php echo admin_url('admin.php?page=mailerlite_main'); ?>"><?php _e('Back to forms list', 'mailerlite'); ?></a>
                </p></div>
        <?php endif; ?>
        <div id="poststuff" class="metabox-holder has-right-sidebar">
            <?php include("sidebar.php"); ?>
            <div id="post-body">
                <div id="post-body-content">
                    <form
                        action="<?php echo admin_url('admin.php?page=mailerlite_main&view=edit&id=' . (isset($_GET['id']) ? $_GET['id'] : 0)); ?>"
                        method="post">
                        <div class="postbox">
                            <h3><span><?php _e('Main information', 'mailerlite'); ?></span></h3>

                            <div class="inside">
                                <table class="form-table">
                                    <tbody>
                                    <tr>
                                        <th><label for="form_name"><?php _e('Form name', 'mailerlite'); ?></label></th>
                                        <td><input type="text" name="form_name" size="30" maxlength="255"
                                                   value="<?php echo $form->name; ?>" id="form_name"> <span
                                                class="description"><strong><?php echo __('Tip:', 'mailerlite'); ?></strong> <?php echo __("This title won't be displayed in public!", 'mailerlite'); ?></span>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="postbox">
                            <h3><span><?php _e('Form details', 'mailerlite'); ?></span></h3>

                            <div class="inside">
                                <table class="form-table">
                                    <tbody>
                                    <tr>
                                        <th><label for="form_title"><?php _e('Form title', 'mailerlite'); ?></label>
                                        </th>
                                        <td><input type="text" name="form_title" size="30" maxlength="255"
                                                   value="<?php echo $form->data['title']; ?>" id="form_title"> <span
                                                class="description"><strong><?php echo __('Example:', 'mailerlite'); ?></strong> <?php echo __("Newsletter signup!", 'mailerlite'); ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th><label
                                                for="form_description"><?php _e('Form description', 'mailerlite'); ?></label>
                                        </th>
                                        <td>
                                            <?php
                                            $settings = array(
                                                'media_buttons' => false,
                                                'textarea_rows' => 4,
                                                'tinymce' => array(
                                                    'toolbar1' => 'bold,italic,underline,bullist,numlist,link,unlink,forecolor,alignleft,aligncenter,alignright,undo,redo',
                                                    'toolbar2' => ''
                                                )
                                            );

                                            wp_editor(stripslashes($form->data['description']), 'form_description', $settings);
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th><label
                                                for="success_message"><?php _e('Success message', 'mailerlite'); ?></label>
                                        </th>
                                        <td>
                                            <?php
                                            $settings = array(
                                                'media_buttons' => false,
                                                'textarea_rows' => 4,
                                                'tinymce' => array(
                                                    'toolbar1' => 'bold,italic,underline,bullist,numlist,link,unlink,forecolor,alignleft,aligncenter,alignright,undo,redo',
                                                    'toolbar2' => ''
                                                )
                                            );

                                            wp_editor(stripslashes($form->data['success_message']), 'success_message', $settings);
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th><label for="button_name"><?php _e('Button title', 'mailerlite'); ?></label>
                                        </th>
                                        <td><input type="text" name="button_name" size="30" maxlength="255"
                                                   value="<?php echo $form->data['button']; ?>" id="button_name"> <span
                                                class="description"><strong><?php _e('Example:'); ?></strong> <?php _e('Subscribe'); ?></span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th><label for="language"><?php _e('Validation messages', 'mailerlite'); ?></label>
                                        </th>
                                        <td>
                                            <select id="language" name="language">
                                                <?php foreach ($languages as $langKey => $langName): ?>
                                                    <option data-code="<?php echo $langKey; ?>"
                                                            value="<?php echo $langKey; ?>"<?php echo $langKey == (isset($form->data['language'])?$form->data['language']:'') ? ' selected="selected"' : ''; ?>><?php echo $langName; ?></option>
                                                <?php endforeach; ?>
                                            </select>

                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="postbox">
                            <h3><span><?php _e('Form fields and lists', 'mailerlite'); ?></span></h3>

                            <div class="inside">
                                <table class="form-table">
                                    <tr>
                                        <td style="vertical-align: top;">
                                            <h2><?php _e('Fields', 'mailerlite'); ?></h2>
                                            <table class="form-table">
                                                <tbody>
                                                <?php foreach ($fields->Fields as $field): ?>
                                                    <tr>
                                                        <th style="width:1%;"><input type="checkbox"
                                                                                     class="input_control"
                                                                                     name="form_selected_field[]"
                                                                                     value="<?php echo $field->field; ?>"<?php echo $field->field == 'email' || array_key_exists($field->field, $form->data['fields']) ? ' checked="checked"' : '';
                                                            echo $field->field == 'email' ? ' disabled="disabled"' : ''; ?>>
                                                        </th>
                                                        <td><input type="text" id="field_<?php echo $field->field; ?>"
                                                                   name="form_field[<?php echo $field->field; ?>]"
                                                                   size="30" maxlength="255"
                                                                   value="<?php echo array_key_exists($field->field, $form->data['fields']) ? $form->data['fields'][$field->field] : $field->title; ?>"<?php echo $field->field == 'email' || array_key_exists($field->field, $form->data['fields']) ? '' : ' disabled="disabled"'; ?>>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </td>
                                        <td style="vertical-align: top;">
                                            <h2><?php _e('Lists', 'mailerlite'); ?></h2>
                                            <table class="form-table">
                                                <tbody>
                                                <?php foreach ($lists->Results as $list): ?>
                                                    <tr>
                                                        <th style="width:1%;"><input id="list_<?php echo $list->id; ?>"
                                                                                     type="checkbox"
                                                                                     class="input_control"
                                                                                     name="form_lists[]"
                                                                                     value="<?php echo $list->id; ?>"<?php echo in_array($list->id, $form->data['lists']) ? ' checked="checked"' : ''; ?>>
                                                        </th>
                                                        <td><label
                                                                for="list_<?php echo $list->id; ?>"><?php echo $list->name; ?></label>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                                <div class="submit">
                                    <input class="button-primary mailerlite-pull-right"
                                           value="<?php _e('Save form', 'mailerlite'); ?>"
                                           name="save_custom_signup_form" type="submit">
                                    <a class="button-secondary"
                                       href="<?php echo admin_url('admin.php?page=mailerlite_main'); ?>"><?php echo __('Back', 'mailerlite'); ?></a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $(".wp-editor-tabs").remove();

            var checkbox_class = $('.input_control');

            checkbox_class.click(function () {
                var input = $('input#field_' + $(this).attr('value'));

                if ($(this).prop('checked') == false) {
                    input.attr('disabled', true);
                }
                else {
                    input.attr('disabled', false);
                }
            });
        });
    </script>

<?php /**/