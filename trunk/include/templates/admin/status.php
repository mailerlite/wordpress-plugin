<?php defined( 'ABSPATH' ) or die( "No direct access allowed!" ); ?>
<?php include_once( 'header.php' ); ?>

<div class="wrap columns-2 dd-wrap">
    <h1><?php echo __( 'Status', 'mailerlite' ); ?></h1>

    <div class="metabox-holder has-right-sidebar">
		<?php include( "sidebar.php" ); ?>
        <div id="post-body">
            <div id="post-body-content" class="mailerlite-activate">
                <table class="form-table">
					<?php foreach ( $information as $group => $fields ) { ?>
                        <tr>
                            <th valign="top">
                                <label for="mailerlite-api-key">
									<?php echo $group; ?>
                                </label>
                            </th>
                            <td>
                                <ul>
									<?php foreach ( $fields as $name => $value ) {
										echo sprintf( '<li></li><strong>%s:</strong> %s</li>', $name, $value );
									} ?>
                                </ul>
                            </td>
                        </tr>
					<?php } ?>
                    <tr>
                        <th><?php _e( 'Copy this for support', 'mailerlite' ); ?></th>
                        <td>

                        <textarea onclick="this.focus();this.select()" readonly="readonly" style="width: 100%"
                                  rows="10"><?php
	                        foreach ( $information as $group => $fields ) {

		                        echo sprintf( "# %s\n\n", $group );

		                        foreach ( $fields as $name => $value ) {
			                        echo sprintf( "%s: %s\n", $name, $value );
		                        }

		                        echo "\n";
	                        }
	                        ?></textarea>
                        </td>
                    </tr>
                </table>

            </div>
        </div>
    </div>
</div>