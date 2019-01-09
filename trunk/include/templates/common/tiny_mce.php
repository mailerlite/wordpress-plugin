<html>
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script type="text/javascript"
            src="<?php echo site_url() . '/wp-includes/js/tinymce/tiny_mce_popup.js'; ?>"></script>
</head>

<body>

<h2>Add form</h2>

<form id="mailerlite_tinymce_form" action="" method="post">

    <p><?php _e( "Select form from list below, and hit \"Add Shortcode\" to add the shortcode to your post!",
			'mailerlite' ); ?></p>

    <p>
        <label for="mailerlite_form_id">Form</label><br/>
        <select class="widefat" id="mailerlite_form_id" name="mailerlite_form_id">
			<?php foreach ( $forms as $form ): ?>
                <option value="<?php echo $form->id; ?>"><?php echo $form->name; ?></option>
			<?php endforeach; ?>
        </select>
    </p>

    <input type="button" name="<?php _e( 'Add Shortcode', 'mailerlite' ); ?>" value="Add Shortcode">

</form>
<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery('form#mailerlite_tinymce_form input:button').click(function () {
            var form_id = jQuery('form#mailerlite_tinymce_form #mailerlite_form_id').val();
            var shortcode = '[mailerlite_form form_id=' + form_id + ']';
            tinyMCEPopup.execCommand("mceInsertContent", false, shortcode);
            tinyMCEPopup.close();
            return false;
        });
    });
</script>
</body>
</html>
