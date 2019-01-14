<?php

require_once dirname( __FILE__ ) . '/base/ML_Rest.php';

/**
 * Class ML_Webforms
 */
class ML_Webforms extends ML_Rest {
	/**
	 * ML_Webforms constructor.
	 *
	 * @param $api_key
	 */
	function __construct( $api_key ) {
		$this->endpoint = 'webforms';

		parent::__construct( $api_key );
	}

	/**
	 * @return ML_Webform_Entity[]
	 */
	public function getAllJson() {
		return parent::getAllJson();
	}
}