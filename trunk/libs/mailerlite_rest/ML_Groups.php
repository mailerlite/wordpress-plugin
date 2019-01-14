<?php

require_once dirname( __FILE__ ) . '/base/ML_Rest.php';

/**
 * Class ML_Groups
 */
class ML_Groups extends ML_Rest {
	/**
	 * ML_Groups constructor.
	 *
	 * @param $api_key
	 */
	function __construct( $api_key ) {
		$this->endpoint = 'groups';

		parent::__construct( $api_key );
	}

	/**
	 * @return ML_Group_Entity[]
	 */
	public function getAllJson() {
		return parent::getAllJson();
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