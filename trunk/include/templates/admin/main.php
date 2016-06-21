<?php defined('ABSPATH') or die("No direct access allowed!"); ?>
<?php include_once('header.php'); ?>

<div class="wrap columns-2 dd-wrap">
    <h2>Popup forms</h2>
    <div class="popup-descriptipn"
         style="background: #ffffff; margin: 10px 0px 10px 0px; padding: 15px; border: 1px solid #e1e1e1;">
        <?php if (!get_option('mailerlite_popups_disabled')) { ?>
            <p style="margin: 0px 0px 10px 0px;">
                <strong>MailerLite tracking script is enabled.</strong> Want to <a
                    style="color: #666666" href="<?php echo admin_url('admin.php?page=mailerlite_main&mailerlite_popups_disabled=1'); ?>">turn it off?</a>
            </p>
            <p></p>
            <p style="margin: 0px 0px 0px 0px;">
                Create popup forms in your MailerLite account and it will be displayed
                in your blog.
            </p>
        <?php } else { ?>
            <p style="margin: 0px 0px 10px 0px;">
                <strong>MailerLite tracking script is disabled.</strong> Want to <a href="<?php echo admin_url('admin.php?page=mailerlite_main&mailerlite_popups_disabled=0'); ?>" style="color: #666666">turn it on?</a>
            </p>
            <p></p>
            <p style="margin: 0px 0px 0px 0px;">Your popup forms won't be displayed while the tracking script is
                disabled.
            </p>
        <?php } ?>
    </div>
</div>

<div class="wrap columns-2 dd-wrap">
    <h2><?php echo __('Signup forms', 'mailerlite'); ?> <a
            href="<?php echo admin_url('admin.php?page=mailerlite_main&view=create'); ?>"
            class="add-new-h2"><?php _e('Add New', 'mailerlite'); ?></a></h2>

    <div id="poststuff" class="metabox-holder has-right-sidebar">
        <?php include("sidebar.php"); ?>
        <div id="post-body">
            <div id="post-body-content">
                <?php if (!empty($forms_data)): ?>
                    <table class="wp-list-table widefat fixed forms">
                        <thead>
                        <tr>
                            <th class="column-posts num"><?php _e('ID', 'mailerlite'); ?></th>
                            <th><?php _e('Name', 'mailerlite'); ?></th>
                            <th class="column-author"><?php _e('Type', 'mailerlite'); ?></th>
                            <th class="column-date"><?php _e('Date', 'mailerlite'); ?></th>
                        </tr>
                        </thead>
                        <tbody id="the-list">
                        <?php $i = 1; ?>
                        <?php foreach ($forms_data as $form): ?>
                            <?php $i++; ?>
                            <tr<?php echo $i % 2 == 0 ? ' class="alternate"' : ''; ?>>
                                <td class="column-posts num"><?php echo $form->id; ?></td>
                                <td>
                                    <strong><a class="row-title"
                                               href="<?php echo admin_url('admin.php?page=mailerlite_main&view=edit&id=' . $form->id); ?>"><?php echo $form->name; ?></a></strong>

                                    <div class="row-actions">
                                        <span class="edit"><a
                                                href="<?php echo admin_url('admin.php?page=mailerlite_main&view=edit&id=' . $form->id); ?>"><?php _e('Edit', 'mailerlite'); ?></a> | </span>
                                        <span class="trash"><a
                                                onclick="return confirm('<?php _e("Are you sure you want to delete this form?", 'mailerlite'); ?>')"
                                                href="<?php echo admin_url('admin.php?page=mailerlite_main&view=delete&noheader=true&id=' . $form->id); ?>"><?php _e('Delete', 'mailerlite'); ?></a></span>
                                    </div>
                                </td>
                                <td><?php echo $form->type == 1 ? __('Custom form', 'mailerlite') : __('Embedded form', 'mailerlite'); ?></td>
                                <td><?php echo $form->time; ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="postbox mailerlite-empty-list">
                        <div class="inside mailerlite-text-center">
                            <a href="<?php echo admin_url('admin.php?page=mailerlite_main&view=create'); ?>"
                               class="button-large button-primary"><?php _e('Add signup form', 'mailerlite'); ?></a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>