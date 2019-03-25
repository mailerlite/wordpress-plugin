<?php defined( 'ABSPATH' ) or die( "No direct access allowed!" ); ?>
<?php include_once( 'header.php' ); ?>

<div class="wrap columns-2 dd-wrap">

	<?php if ( ! get_option( 'mailerlite_popups_disabled' ) ) : else: ?>
        <div class="notice notice-error">
            <p>
                <strong><?php echo __( 'MailerLite popup script is',
						'mailerlite' ) ?> <?php if ( ! get_option( 'mailerlite_popups_disabled',
						'mailerlite' ) ) : ?><?php echo __( 'enabled',
						'mailerlite' ) ?><?php else: ?><?php echo __( 'disabled', 'mailerlite' ) ?><?php endif; ?>
                    .</strong>
				<?php echo __( 'Go to settings if you want to change it', 'mailerlite' ); ?>.
                <br/>
				<?php if ( ! get_option( 'mailerlite_popups_disabled' ) ): ?>
                    <strong><?php echo _e( 'Your popup forms will be displayed automatically while the popup script is enabled',
							'mailerlite' ); ?></strong>
				<?php else: ?>
                    <strong><?php echo __( 'Your popup forms wont be displayed while the popup script is disabled',
							'mailerlite' ); ?></strong>
				<?php endif; ?>
            </p>
        </div>
	<?php endif; ?>

    <h1><?php echo __( 'Signup forms', 'mailerlite' ); ?> <a
                href="<?php echo admin_url( 'admin.php?page=mailerlite_main&view=create' ); ?>"
                class="add-new-h2"><?php _e( 'Add New', 'mailerlite' ); ?></a></h1>

    <div class="metabox-holder has-right-sidebar">
		<?php include( "sidebar.php" ); ?>
        <div id="post-body">
            <div id="post-body-content">
				<?php if ( ! empty( $forms_data ) ): ?>
                    <table class="wp-list-table widefat fixed forms">
                        <thead>
                        <tr>
                            <th class="column-posts num"><?php _e( 'ID', 'mailerlite' ); ?></th>
                            <th><?php _e( 'Name', 'mailerlite' ); ?></th>
                            <th><?php _e( 'Type', 'mailerlite' ); ?></th>
                            <th><?php _e( 'Date', 'mailerlite' ); ?></th>
                        </tr>
                        </thead>
                        <tbody id="the-list">
						<?php $i = 1; ?>
						<?php foreach ( $forms_data as $form ): ?>
							<?php $i ++; ?>
                            <tr<?php echo $i % 2 == 0 ? ' class="alternate"' : ''; ?>>
                                <td class="column-posts num"><?php echo $form->id; ?></td>
                                <td>
                                    <strong><a class="row-title"
                                               href="<?php echo admin_url( 'admin.php?page=mailerlite_main&view=edit&id=' . $form->id ); ?>"><?php echo $form->name; ?></a></strong>

                                    <div class="row-actions">
                                        <span class="edit"><a
                                                    href="<?php echo admin_url( 'admin.php?page=mailerlite_main&view=edit&id=' . $form->id ); ?>"><?php _e( 'Edit',
			                                        'mailerlite' ); ?></a> | </span>
                                        <span class="trash"><a
                                                    onclick="return confirm('<?php _e( "Are you sure you want to delete this form?",
														'mailerlite' ); ?>')"
                                                    href="<?php echo admin_url( 'admin.php?page=mailerlite_main&view=delete&noheader=true&id=' . $form->id ); ?>"><?php _e( 'Delete',
													'mailerlite' ); ?></a></span>
                                    </div>
                                </td>
                                <td><?php echo $form->type == MailerLite_Form::TYPE_CUSTOM ? __( 'Custom form',
										'mailerlite' ) : __( 'Embedded form', 'mailerlite' ); ?></td>
                                <td><?php echo $form->time; ?></td>
                            </tr>
						<?php endforeach; ?>
                        </tbody>
                    </table>
				<?php else: ?>
                    <div class="postbox mailerlite-empty-list">
                        <div class="mailerlite-row">
                            <div class="mailerlite-col">
                                <p><img class="mailerlite-icon"
                                        src="<?php echo MAILERLITE_PLUGIN_URL ?>/assets/image/custom_form.png"
                                        style="max-width: 240px;"></p>
                            </div>
                            <div class="mailerlite-col">

                                <h3><?php _e( 'Create your first signup form', 'mailerlite' ); ?></h3>

                                <p><?php _e( 'Create a custom signup form or add a form created using MailerLite.',
										'mailerlite' ); ?></p>

                                <p><a href="<?php echo admin_url( 'admin.php?page=mailerlite_main&view=create' ); ?>"
                                      class="button button-hero button-primary"><?php _e( 'Add signup form',
											'mailerlite' ); ?></a></p>
                            </div>
                            <div class="clear"></div>

                        </div>
                    </div>
				<?php endif; ?>
            </div>
        </div>
    </div>
</div>