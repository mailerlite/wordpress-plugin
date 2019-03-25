<?php

require_once dirname( __FILE__ ) . '/base/MailerLite_Forms_Rest.php';

/**
 * Class MailerLite_Forms_Fields
 */
class MailerLite_Forms_Fields extends MailerLite_Forms_Rest {
	/**
	 * MailerLite_Forms_Fields constructor.
	 *
	 * @param $api_key
	 */
	function __construct( $api_key ) {
		$this->endpoint = 'fields';

		parent::__construct( $api_key );
	}
}