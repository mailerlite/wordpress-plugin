<?php

require_once dirname( __FILE__ ) . '/base/MailerLite_Forms_Rest.php';

/**
 * Class MailerLite_Forms_Webforms
 */
class MailerLite_Forms_Webforms extends MailerLite_Forms_Rest {
	/**
	 * MailerLite_Forms_Webforms constructor.
	 *
	 * @param $api_key
	 */
	function __construct( $api_key ) {
		$this->endpoint = 'webforms';

		parent::__construct( $api_key );
	}

	/**
	 * @param array $data
	 *
	 * @return MailerLite_Forms_Webform_Entity[]
	 * @throws Exception
	 */
	public function getAllJson( $data = [] ) {
		return parent::getAllJson( $data );
	}
}