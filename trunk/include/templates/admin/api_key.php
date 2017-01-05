<?php defined('ABSPATH') or die("No direct access allowed!"); ?>
<?php include("header.php"); ?>
<div class="wrap columns-2 dd-wrap">
    <div id="poststuff" class="metabox-holder has-right-sidebar">
        <?php include("sidebar.php"); ?>
        <div id="post-body">
            <div id="post-body-content">
                <p><?php echo __('Hi there! You will be able to create awesome signup forms, but first we need your MailerLite API key!', 'mailerlite'); ?></p>

                <div class="mailerlite-activate">
                    <div class="description-block">
                        <p class="title"><?php echo __('Enter an API key', 'mailerlite'); ?></p>

                        <p><?php echo __("Don't know where to find it?", 'mailerlite'); ?> <a
                                href="https://kb.mailerlite.com/does-mailerlite-offer-an-api/"
                                target="_blank"><?php echo __('Check it here!', 'mailerlite'); ?></a></p>
                    </div>
                    <div class="input-block">
                        <form action="" method="post" id="enter-mailerlite-key">
                            <input type="text" name="mailerlite_key" class="regular-text" placeholder="API-key"/>
                            <input type="submit" name="submit" id="submit" class="button button-primary"
                                   value="<?php echo __('Save this key', 'mailerlite'); ?>">
                            <input type="hidden" name="action" value="enter-mailerlite-key">
                        </form>
                    </div>
                    <div class="clear"></div>
                </div>
                <p><strong><?php echo __("Don't have an account?", 'mailerlite'); ?></strong></p>
                <a href="https://www.mailerlite.com/signup" target="_blank"
                   class="button button-secondary"><?php echo __('Register!', 'mailerlite'); ?></a>
            </div>
        </div>
    </div>
</div>