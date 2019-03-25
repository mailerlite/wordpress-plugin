<?php

require_once dirname( __FILE__ ) . '/base/MailerLite_Forms_Rest.php';

/**
 * Class MailerLite_Forms_Groups
 */
class MailerLite_Forms_Groups extends MailerLite_Forms_Rest {
	/**
	 * MailerLite_Forms_Groups constructor.
	 *
	 * @param $api_key
	 */
	function __construct( $api_key ) {
		$this->endpoint = 'groups';

		parent::__construct( $api_key );
	}

	/**
	 * @param array $data
	 *
	 * @return MailerLite_Forms_Group_Entity[]
	 * @throws Exception
	 */
	public function getAllJson( $data = [] ) {
		return parent::getAllJson( $data );
	}

	function getActive() {
		$this->path .= 'active/';

		return $this->execute( 'GET' );
	}

	function getUnsubscribed() {
		$this->path .= 'unsubscribed/';

		return $this->execute( 'GET' );
	}

	function getBounced() {
		$this->path .= 'bounced/';

		return $this->execute( 'GET' );
	}
}