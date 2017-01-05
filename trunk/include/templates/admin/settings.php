<?php defined('ABSPATH') or die("No direct access allowed!"); ?>
<?php include_once('header.php'); ?>

<div class="wrap columns-2 dd-wrap">
    <h2><?php _e('Plugin settings', 'mailerlite'); ?></h2>

    <div id="poststuff" class="metabox-holder has-right-sidebar">
        <?php include("sidebar.php"); ?>
        <div id="post-body">
            <div id="post-body-content">
                <p><?php _e('Here you able to change your API key!', 'mailerlite'); ?></p>

                <div class="mailerlite-activate">
                    <div class="description-block">
                        <p class="title"><?php _e('Enter an API key', 'mailerlite'); ?></p>

                        <p><?php _e("Don't know where to find it?", 'mailerlite'); ?> <a
                                href="https://kb.mailerlite.com/does-mailerlite-offer-an-api/"
                                target="_blank"><?php _e("Check it here!", 'mailerlite'); ?></a></p>
                    </div>
                    <div class="input-block">
                        <form action="" method="post" id="enter-mailerlite-key">
                            <input type="text" name="mailerlite_key" class="regular-text" placeholder="API-key"
                                   value="<?php echo $api_key; ?>"/>
                            <input type="submit" name="submit" id="submit" class="button button-primary"
                                   value="<?php _e('Save this key', 'mailerlite'); ?>">
                            <input type="hidden" name="action" value="enter-mailerlite-key">
                        </form>
                    </div>
                    <div class="clear"></div>
                </div>
                <p><strong><?php _e("Don't have an account?", 'mailerlite'); ?></strong></p>
                <a href="https://www.mailerlite.com/signup" target="_blank"
                   class="button button-secondary"><?php _e('Register!', 'mailerlite'); ?></a>
            </div>
        </div>
    </div>
</div>