<?php defined('ABSPATH') or die("No direct access allowed!"); ?>
<?php include_once('header.php'); ?>

<div class="wrap columns-2 dd-wrap">
    <h2><?php echo __('Edit webform', 'mailerlite'); ?></h2>
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
                        <h3><span><?php echo __('Webform details', 'mailerlite'); ?></span></h3>

                        <div class="inside">
                            <table class="form-table">
                                <tbody>
                                <tr>
                                    <th><label for="form_name"><?php echo __('Form title', 'mailerlite'); ?></label>
                                    </th>
                                    <td><input type="text" name="form_name" size="30" maxlength="255"
                                               value="<?php echo $form->name; ?>" id="form_name"> <span
                                            class="description"><strong><?php echo __('Tip:', 'mailerlite'); ?></strong> <?php echo __("This title won't be displayed in public!", 'mailerlite'); ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label for="form_webform_id"><?php _e('Webform', 'mailerlite'); ?></label></th>
                                    <td>
                                        <select id="form_webform_id" name="form_webform_id">
                                            <?php foreach ($webforms->Results as $webform): ?>
                                                <?php if (!in_array($webform->type, array('embed', 'button'))) { continue; } ?>
                                                <option data-code="<?php echo $webform->code; ?>"
                                                        value="<?php echo $webform->id; ?>"<?php echo $webform->id == $form->data['id'] ? ' selected="selected"' : ''; ?>><?php echo $webform->name; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div id="webform_example">​</div>
                                        ​​​​​​​
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <div class="submit">
                                <input class="button-primary mailerlite-pull-right"
                                       value="<?php _e('Save form', 'mailerlite'); ?>" name="save_embedded_signup_form"
                                       type="submit">
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
    jQuery(window).load(function () {
        var select = jQuery("#form_webform_id");
        loadIframe(select.children("option:selected").attr('data-code'));
        select.change(function () {
            loadIframe(jQuery("option:selected", this).attr('data-code'));
        });
    });

    function loadIframe(code) {
        if (!code) {
            return;
        }

        jQuery('#webform_example').html(jQuery('<iframe></iframe>', {
            id: 'webform_example_iframe',
            src: "https://app.mailerlite.com/webforms/submit/" + code + "/",
            style: 'width:100%;height:350px;'
        }));
    }
</script>