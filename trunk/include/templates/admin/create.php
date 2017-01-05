<?php defined('ABSPATH') or die("No direct access allowed!"); ?>
<?php include_once('header.php'); ?>

<div class="wrap columns-2 dd-wrap">
    <h2><?php echo __('Create new signup form', 'mailerlite'); ?></h2>

    <div id="poststuff" class="metabox-holder has-right-sidebar">
        <?php include("sidebar.php"); ?>
        <div id="post-body">
            <div id="post-body-content">
                <div class="stuffbox">
                    <h3><span><?php echo __('Form type', 'mailerlite'); ?></span></h3>

                    <form action="<?php echo admin_url('admin.php?page=mailerlite_main&view=create&noheader=true'); ?>"
                          method="post">
                        <div class="inside">
                            <p>
                                <label for="form_type_custom" class="selectit">
                                    <input id="form_type_custom" type="radio" name="form_type" value="1"
                                           checked="checked">
                                    <?php echo __('Custom signup form', 'mailerlite'); ?>
                                </label>
                            </p>

                            <p>
                                <?php
                                    $embed_button_webforms = array();

                                    foreach ($webforms->Results as $webform) {
                                        if (!in_array($webform->type, array('embed', 'button'))) { continue; }

                                        $embed_button_webforms[] = $webform;
                                    }
                                ?>

                                <label for="form_type_webform" class="selectit">
                                    <input id="form_type_webform" type="radio" name="form_type"
                                           value="2"<?php echo count($embed_button_webforms) == 0 ? ' disabled="disabled"' : ''; ?>>
                                    <?php echo __('Webforms created using MailerLite', 'mailerlite'); ?>
                                </label>
                            </p>

                            <div class="submit">
                                <input class="button-primary mailerlite-pull-right"
                                       value="<?php echo __('Create form', 'mailerlite'); ?>" name="create_signup_form"
                                       type="submit">
                                <a class="button-secondary"
                                   href="<?php echo admin_url('admin.php?page=mailerlite_main'); ?>"><?php echo __('Back', 'mailerlite'); ?></a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>