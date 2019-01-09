<?php defined( 'ABSPATH' ) or die( "No direct access allowed!" ); ?>

<?php if ( $mailerlite_error ): ?>
    <div class="error">
        <p><?php echo $mailerlite_error; ?></p>
    </div>

<?php endif; ?>