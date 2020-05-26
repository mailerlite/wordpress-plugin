<?php defined( 'ABSPATH' ) or die( "No direct access allowed!" ); ?>
<?php include_once( 'header.php' ); ?>

    <div class="wrap columns-2 dd-wrap">
        <h1><?php _e( 'Edit custom signup form', 'mailerlite' ); ?></h1>
		<?php if ( isset( $result ) && $result == 'success' ): ?>
            <div id="message" class="updated below-h2"><p><?php _e( 'Form saved.', 'mailerlite' ); ?> <a
                            href="<?php echo admin_url( 'admin.php?page=mailerlite_main' ); ?>"><?php _e( 'Back to forms list',
							'mailerlite' ); ?></a>
                </p></div>
		<?php endif; ?>
        <div class="metabox-holder has-right-sidebar">
			<?php include( "sidebar.php" ); ?>
            <div id="post-body">
                <div id="post-body-content">
                    <form id="edit_custom"
                          action="<?php echo admin_url( 'admin.php?page=mailerlite_main&view=edit&id=' . ( isset( $_GET['id'] ) ? $_GET['id'] : 0 ) ); ?>"
                          method="post">

                        <input type="text" name="form_name" class="form-large" size="30" maxlength="255"
                               value="<?php echo $form->name; ?>" id="form_name"
                               placeholder="<?php _e( 'Form name', 'mailerlite' ); ?>">
                        <div>
							<?php echo __( 'Use the shortcode', 'mailerlite' ); ?>
                            <input type="text" onfocus="this.select();" readonly="readonly"
                                   value="[mailerlite_form form_id=<?php echo( isset( $_GET['id'] ) ? $_GET['id'] : 0 ); ?>]"
                                   size="26">
							<?php echo __( 'to display this form inside a post, page or text widget.',
								'mailerlite' ); ?>
                        </div>

                        <br>

                        <div class="option-page-wrap">

                            <h2 class="nav-tab-wrapper" id="wpt-tabs">
                                <a class="nav-tab nav-tab-active" id="ml-details-tab"
                                   href="#top#ml-details"><?php _e( 'Form details', 'mailerlite' ); ?></a>
                                <a class="nav-tab" id="ml-fields-tab"
                                   href="#top#ml-fields"><?php _e( 'Form fields and lists', 'mailerlite' ); ?></a>
                            </h2>

                            <div class="tab-content-wrapper">
                                <section id="ml-details" class="tab-content active">

                                    <table class="form-table">
                                        <tbody>
                                        <tr>
                                            <th>
                                                <label for="form_title"><?php _e( 'Form title',
														'mailerlite' ); ?></label>
                                            </th>
                                            <td>
                                                <input type="text" class="regular-text" name="form_title" size="30"
                                                       maxlength="255" value="<?php echo $form->data['title']; ?>"
                                                       id="form_title">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><label for="form_description"><?php _e( 'Form description',
														'mailerlite' ); ?></label></th>
                                            <td>
												<?php
												$settings = [
													'media_buttons' => false,
													'textarea_rows' => 4,
													'tinymce'       => [
														'toolbar1' =>
															'bold,italic,underline,bullist,numlist,link,unlink,forecolor,alignleft,aligncenter,alignright,undo,redo',
														'toolbar2' => '',
													],
												];

												wp_editor( stripslashes( $form->data['description'] ),
													'form_description', $settings );
												?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><label for="success_message"><?php _e( 'Success message',
														'mailerlite' ); ?></label></th>
                                            <td>
												<?php
												$settings = [
													'media_buttons' => false,
													'textarea_rows' => 4,
													'tinymce'       => [
														'toolbar1' =>
															'bold,italic,underline,bullist,numlist,link,unlink,forecolor,alignleft,aligncenter,alignright,undo,redo',
														'toolbar2' => '',
													],
												];

												wp_editor( stripslashes( $form->data['success_message'] ),
													'success_message', $settings );
												?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><label for="button_name"><?php _e( 'Button title',
														'mailerlite' ); ?></label>
                                            </th>
                                            <td><input type="text" class="regular-text" name="button_name" size="30"
                                                       maxlength="255" value="<?php echo $form->data['button']; ?>"
                                                       id="button_name">
                                            </td>
                                        </tr>

                                        <tr>
                                            <th><label for="button_name"><?php _e( 'Please wait message',
														'mailerlite' ); ?></label>
                                            </th>
                                            <td><input type="text" class="regular-text" name="please_wait" size="30"
                                                       maxlength="255"
                                                       value="<?php if ( isset( $form->data['please_wait'] ) ) {
												           echo $form->data['please_wait'];
											           } ?>" id="please_wait_name">
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>
                                                <label for="language"><?php _e( 'Validation messages',
														'mailerlite' ); ?></label>
                                            </th>
                                            <td>
                                                <select id="language" name="language">
													<?php foreach ( $languages as $langKey => $langName ): ?>
                                                        <option data-code="<?php echo $langKey; ?>"
                                                                value="<?php echo $langKey; ?>"
															<?php echo $langKey == ( isset( $form->data['language'] ) ? $form->data['language'] : '' ) ?
																' selected="selected"' : ''; ?>><?php echo $langName; ?></option>
													<?php endforeach; ?>
                                                </select>

                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>

                                </section>

                                <section id="ml-fields" class="tab-content">

                                    <table class="form-table">
                                        <tr>
                                            <td style="vertical-align: top;width: 350px;">
                                                <h2><?php _e( 'Fields', 'mailerlite' ); ?></h2>
                                                <p class="description"><?php _e( 'Select fields which will be displayed in the form.',
														'mailerlite' ); ?></p>
                                                <table class="form-table">
                                                    <tbody>

													<?php
													/** @var MailerLite_Forms_Field_Entity $field */
													foreach ( $fields as $field ): ?>
                                                        <tr>
                                                            <th style="width:1%;"><input type="checkbox"
                                                                                         class="input_control"
                                                                                         name="form_selected_field[]"
                                                                                         value="<?php echo $field->key; ?>"<?php echo $field->
																key == 'email' || array_key_exists( $field->key,
																	$form->data['fields'] ) ?
																	' checked="checked"' : '';
																echo $field->key == 'email' ? ' disabled="disabled"' : ''; ?>>
                                                            </th>
                                                            <td><input type="text" id="field_<?php echo $field->key; ?>"
                                                                       name="form_field[<?php echo $field->key; ?>]"
                                                                       size="30" maxlength="255"
                                                                       value="<?php echo array_key_exists( $field->key,
																           $form->data['fields'] ) ? $form->data['fields'][ $field->key ] : $field->title; ?>"<?php echo $field->key == 'email' || array_key_exists( $field->key,
																	$form->data['fields'] ) ?
																	'' : ' disabled="disabled"'; ?>>
                                                            </td>
                                                        </tr>
													<?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td style="vertical-align: top;">
                                                <h2><?php _e( 'Lists', 'mailerlite' ); ?></h2>
                                                <p class="description"><?php _e( 'Select the list(s) to which people who submit this form should be subscribed.',
														'mailerlite' ); ?></p>
                                                <table class="form-table">
                                                    <tbody>
													<?php
													/** @var MailerLite_Forms_Group_Entity $group */
													foreach ( $groups as $group ) { ?>
                                                        <tr>
                                                            <th style="width:1%;"><input
                                                                        id="list_<?php echo $group->id; ?>"
                                                                        type="checkbox"
                                                                        class="input_control"
                                                                        name="form_lists[]"
                                                                        value="<?php echo $group->id; ?>"<?php echo in_array( $group->
																id,
																	$form->data['lists'] ) ? ' checked="checked"' : ''; ?>>
                                                            </th>
                                                            <td>
                                                                <label for="list_<?php echo $group->id; ?>"><?php echo $group->name; ?></label>
                                                            </td>
                                                        </tr>
													<?php } ?>
                                                    </tbody>
                                                </table>
                                                <table id="more-groups" class="form-table" style="display: none;">

                                                </table>
												<?php if ( $can_load_more_groups ) {
													?>
                                                    <button id="load-more-groups" class="button-primary" type="button">
                                                        Load more
                                                    </button>
													<?php
												}
												?>
                                            </td>
                                        </tr>
                                    </table>

                                </section>
                            </div>

                        </div>

                        <div id="no-selections-error" class="notice-error" style="display: none;">
                            <p>
								<?php _e( 'Please select at least one Interest Group (tag) from the list.',
									'mailerlite' ); ?>
                            </p>
                        </div>
                        <input type="hidden" id="selected_groups" name="selected_groups" /> 
                        <div class="submit">
                            <input class="button-primary"
                                   value="<?php _e( 'Save form', 'mailerlite' ); ?>"
                                   name="save_custom_signup_form" type="submit">
                            <a class="button-secondary"
                               href="<?php echo admin_url( 'admin.php?page=mailerlite_main' ); ?>"><?php echo __( 'Back',
									'mailerlite' ); ?></a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $(".wp-editor-tabs").remove();

            var checkbox_class = $('.input_control');

            checkbox_class.click(function () {
                var input = $('input#field_' + $(this).attr('value'));

                if ($(this).prop('checked') == false) {
                    input.attr('disabled', true);
                } else {
                    input.attr('disabled', false);
                }
            });


            var $tabs = $('h2#wpt-tabs'),
                $sections = $('section.tab-content');

            $tabs.find('a.nav-tab').on('click.wptab', (function (e) {
                e.stopPropagation();
                // Hide all tabs
                $tabs.find('a.nav-tab').removeClass('nav-tab-active');
                $sections.removeClass('active');
                // Activate only clicked tab
                var sectionId = $(this).attr('id').replace('-tab', '');
                $('#' + sectionId).addClass('active');
                $(this).addClass('nav-tab-active');
            }));

            $(document).ready(function ($) {
                $('#edit_custom').on('submit', function (e) {
                    var checkedLists = $("[name='form_lists[]']:checked");
                
                    var selected_groups = '';
                    checkedLists.each(function() {
                        group = this.value +'::'+$("label[for='list_"+ this.value + "']").text();
                        if (selected_groups != '') {
                            selected_groups = selected_groups+';*'+group;
                        } else {
                            selected_groups = group;
                        }
                        
                    });
                    
                    $("[name=selected_groups]").val(selected_groups);

                    if (checkedLists.length === 0) {
                        $("#no-selections-error").show().addClass('notice');
                        e.preventDefault();
                        return false;
                    }
                });
            });

            $('#load-more-groups').on('click', function () {
                $('#load-more-groups').prop('disabled', true);

                jQuery.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: {
                        action: 'mailerlite_get_more_groups', form_id: <?php echo $form->id;?>,
                        ml_nonce: '<?php echo wp_create_nonce( 'mailerlite_load_more_groups' );?>'
                    }
                }).done(function (html) {
                    $('#more-groups').show().html(html);
                    $('#load-more-groups').hide();
                });
            });
        });
    </script>


<?php /**/
