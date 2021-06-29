<?php if (get_option('account_id') && get_option('account_subdomain')) { ?>

        <div class="ml-form-embed"
             data-account="<?php echo get_option('account_id') . ':' . get_option('account_subdomain'); ?>"
             data-form="<?php echo $form_data['id'] . ':' . $form_data['code']; ?>">
        </div>

<?php } else { ?>

    <script type="text/javascript" src="https://static.mailerlite.com/data/webforms/<?php echo $form_data['id']; ?>/<?php echo $form_data['code']; ?>.js?v=<?php echo time(); ?>"></script>
<?php } ?>