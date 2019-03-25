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