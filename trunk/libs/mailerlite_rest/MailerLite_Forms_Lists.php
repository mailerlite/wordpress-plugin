<?php

require_once dirname( __FILE__ ) . '/base/MailerLite_Forms_Rest.php';

/**
 * Class MailerLite_Forms_Lists
 */
class MailerLite_Forms_Lists extends MailerLite_Forms_Rest {
	/**
	 * MailerLite_Forms_Lists constructor.
	 *
	 * @param $api_key
	 */
	function __construct( $api_key ) {
		$this->name = 'lists';

		parent::__construct( $api_key );
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

	function getFields() {
		$this->path .= 'fields/';

		return $this->execute( 'GET' );
	}
}