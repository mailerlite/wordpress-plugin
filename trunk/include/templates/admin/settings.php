<?php defined('ABSPATH') or die("No direct access allowed!"); ?>
<?php include_once('header.php'); ?>

<div class="wrap columns-2 dd-wrap">
    <h2><?php echo __('Plugin settings', 'mailerlite'); ?></h2>

    <div id="poststuff" class="metabox-holder has-right-sidebar">
        <?php include("sidebar.php"); ?>
        <div id="post-body">
            <div id="post-body-content">

                <div class="mailerlite-activate">
                    <div class="description-block">
                        <p class="title"><?php echo __('Enter an API key', 'mailerlite'); ?></p>

                        <p><?php echo __("Don't know where to find it?", 'mailerlite'); ?> <a
                                href="https://kb.mailerlite.com/does-mailerlite-offer-an-api/"
                                target="_blank"><?php echo __("Check it here!", 'mailerlite'); ?></a></p>
                    </div>
                    <div class="input-block">
                        <form action="" method="post" id="enter-mailerlite-key">
                            <input type="text" name="mailerlite_key" class="regular-text" placeholder="API-key"
                                   value="<?php echo $api_key; ?>"/>
                            <input type="submit" name="submit" id="submit" class="button button-primary"
                                   value="<?php echo __('Save this key', 'mailerlite'); ?>">
                            <input type="hidden" name="action" value="enter-mailerlite-key">
                        </form>
                    </div>
                    <div class="clear"></div>
                </div>

                <div class="mailerlite-activate">
                    <div class="description-block">
                        <p class="title"><?php echo __('Popup forms script', 'mailerlite'); ?></p>
                    </div>

                    <div class="input-block">
                        <form action="" method="post" id="mailerlite-popups">

                            <p class="<?php if (get_option('mailerlite_popups_disabled')) : ?>info<?php else: ?>success<?php endif; ?> popups">
                                <?php if (!get_option('mailerlite_popups_disabled')) : ?> <?php echo __('enabled', 'mailerlite'); ?><?php else: ?><?php echo __('disabled', 'mailerlite');?><?php endif; ?>
                            </p>

                            <input type="submit" name="submit" id="submit" class="button button-primary"
                                   value="<?php if (!get_option('mailerlite_popups_disabled')) : ?><?php echo __('Disable', 'mailerlite'); ?><?php else: ?><?php echo __('Enable', 'mailerlite');?><?php endif; ?>">
                            <input type="hidden" name="action" value="enter-popup-forms">
                        </form>
                    </div>
                    <div class="clear"></div>

                    <div class="input-block">
                        <?php if (!get_option('mailerlite_popups_disabled')): ?>
                            <?php echo __('Your popup forms will be displayed automatically while the popup script is enabled', 'mailerlite');?>
                        <?php else: ?>
                            <?php echo __('Your popup forms wont be displayed while the popup script is disabled', 'mailerlite');?>
                        <?php endif; ?>
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