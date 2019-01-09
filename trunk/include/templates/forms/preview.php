<html lang="en">
<head>
    <title></title>
	<?php wp_head(); ?>
</head>
<body>
<div style='width: 400px;margin: auto;'>
	<?php load_mailerlite_form( $_GET['form_id'] ); ?>
</div>
<style>
    .ml_message_wrapper > * {
        margin: 0 !important;
        padding: 0 !important;
    }
</style>
</body>
</html>