<?php

require_once MAILERLITE_PLUGIN_DIR . "include/mailerlite-form.php";

/**
 * Class MailerLite_Widget
 */
class MailerLite_Widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		parent::__construct(
			'mailerlite_widget',
			__( 'MailerLite sign up form', 'mailerlite' ),
			[
				'description' => __( 'MailerLite sign up form Widget', 'mailerlite' ),
			]
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		global $wpdb;

		$form_id = isset( $instance['mailerlite_form_id'] )
		           && intval(
			           $instance['mailerlite_form_id']
		           ) ? $instance['mailerlite_form_id'] : 0;
		$query = $wpdb->prepare(
			"SELECT * FROM
			{$wpdb->base_prefix}mailerlite_forms
			WHERE id = %d",
			$form_id
		);
		$form = $wpdb->get_row($query);

		if ( isset( $form->data ) ) {
			$form_data = unserialize( $form->data );

			echo $args['before_widget'];

			$MailerLite_form = new Mailerlite_Form();
			$MailerLite_form->generate_form(
				$form_id, $form->type, $form->name, $form_data
			);

			echo $args['after_widget'];
		}
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 *
	 * @return void
	 */
	public function form( $instance ) {
		global $wpdb;

		$query = "
			SELECT * FROM
			{$wpdb->base_prefix}mailerlite_forms
			ORDER BY time DESC
		";
		$forms_data = $wpdb->get_results($query);

		if ( isset( $instance['mailerlite_form_id'] ) ) {
			$id = $instance['mailerlite_form_id'];
		} else {
			$id = 0;
		}
		?>
        <p>
            <label for="<?php echo $this->get_field_id(
				'mailerlite_form_id'
			); ?>"><?php echo __( 'Select form:', 'mailerlite' ); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id(
				'mailerlite_form_id'
			); ?>" name="<?php echo $this->get_field_name(
				'mailerlite_form_id'
			); ?>">
				<?php foreach ( $forms_data as $form ): ?>
                    <option value="<?php echo $form->id; ?>"<?php echo
					$form->id == $id ? ' selected="selected"'
						: ''; ?>><?php echo $form->name; ?></option>
				<?php endforeach; ?>
            </select>
        </p>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = [];
		$instance['mailerlite_form_id']
		          = ( ! empty( $new_instance['mailerlite_form_id'] ) )
			? strip_tags( $new_instance['mailerlite_form_id'] ) : '';

		return $instance;
	}
}

function register_mailerlite_widget() {
	register_widget( 'Mailerlite_Widget' );
}

add_action( 'widgets_init', 'register_mailerlite_widget' );