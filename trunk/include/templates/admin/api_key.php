<?php defined( 'ABSPATH' ) or die( "No direct access allowed!" ); ?>
<?php include( "header.php" ); ?>

<div class="wrap columns-2 dd-wrap">
    <h1><?php echo __( 'Plugin settings', 'mailerlite' ); ?></h1>

    <div class="metabox-holder has-right-sidebar">
		<?php include( "sidebar.php" ); ?>
        <div id="post-body">
            <div id="post-body-content">

                <p><?php echo __( 'Hi there! You will be able to create awesome signup forms, but first we need your MailerLite API key!',
						'mailerlite' ); ?></p>

                <table class="form-table">
                    <tbody>
                    <tr>
                        <th><label for="form_api-key"><?php echo __( 'Enter an API key', 'mailerlite' ); ?></label></th>
                        <td>
                            <form action="" method="post" id="enter-mailerlite-key">
                                <input type="text" name="mailerlite_key" id="form_api-key" class="regular-text"
                                       placeholder="API-key"/>
                                <input type="submit" name="submit" id="submit" class="button button-primary"
                                       value="<?php echo __( 'Save this key', 'mailerlite' ); ?>">
                                <input type="hidden" name="action" value="enter-mailerlite-key">
                            </form>
                            <p class="description">
								<?php echo __( "Don't know where to find it?", 'mailerlite' ); ?>
                                <a href="https://kb.mailerlite.com/does-mailerlite-offer-an-api/" target="_blank">
									<?php echo __( 'Check it here!', 'mailerlite' ); ?>
                                </a>
                            </p>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <br>
                <hr>

                <h2 class="title"><?php echo __( "Don't have an account?", 'mailerlite' ); ?></h2>

                <a href="https://www.mailerlite.com/signup" target="_blank"
                   class="button button-secondary"><?php echo __( 'Register!', 'mailerlite' ); ?></a>

            </div>
        </div>
    </div>
</div>