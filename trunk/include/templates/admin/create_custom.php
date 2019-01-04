<?php defined('ABSPATH') or die("No direct access allowed!"); ?>
<?php include_once('header.php'); ?>

<div class="wrap columns-2 dd-wrap">
    <h1><?php echo __('Create new signup form', 'mailerlite'); ?></h1>
    <div class="metabox-holder has-right-sidebar">
        <?php include("sidebar.php"); ?>
        <div id="post-body">
            <div id="post-body-content">

                <form action="<?php echo admin_url('admin.php?page=mailerlite_main&view=create&noheader=true'); ?>"
                      method="post" id="create_custom">
                    <input type="hidden" name="create_signup_form_now" value="1">
                    <div class="inside">

                        <input type="text" name="form_name" class="form-large" size="30" maxlength="255" value="<?php echo $form_name ?>" id="form_name" placeholder="<?php _e('Form name', 'mailerlite'); ?>">

                        <h2><?php _e('Lists', 'mailerlite'); ?></h2>
                        <p class="description"><?php _e('Select the list(s) to which people who submit this form should be subscribed.', 'mailerlite'); ?></p>
                        <table id="list-table" class="form-table">
                            <tbody>
		                    <?php foreach ($lists->Results as $list): ?>
                                <tr>
                                    <th style="width:1%;"><input id="list_<?php echo $list->id; ?>"
                                                                 type="checkbox"
                                                                 class="input_control"
                                                                 name="form_lists[]"
                                                                 value="<?php echo $list->id; ?>">
                                    </th>
                                    <td><label
                                                for="list_<?php echo $list->id; ?>"><?php echo $list->name; ?></label>
                                    </td>
                                </tr>
		                    <?php endforeach; ?>

		                    <?php if ( count( $lists->Results ) === 0 ) {
			                   ?>
                                <p style="color: red;">
                                    <?php _e( 'Please create a group first', 'mailerlite' ); ?>
                                </p>
                            <?php
		                    } ?>
                            </tbody>
                        </table>

                        <div class="submit">
                            <input class="button-primary"
                                   value="<?php echo __('Create form', 'mailerlite'); ?>" name="create_signup_form"
                                   type="submit">
                            <a class="button-secondary"
                               href="<?php echo admin_url('admin.php?page=mailerlite_main'); ?>"><?php echo __('Back', 'mailerlite'); ?></a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    (function () {
        var jQuery = window.jQueryWP || window.jQuery;

        jQuery(document).ready(function ($) {
            $('#create_custom').on('submit', function (e) {
                var checkedLists = $("[name='form_lists[]']:checked").length;

                if (checkedLists === 0) {
                    $("#list-table").css('color', 'red');
                    e.preventDefault();
                    return false;
                }
            });
        });
    })();
</script>

