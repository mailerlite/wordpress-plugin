<?php

require_once dirname( __FILE__ ) . '/base/ML_Rest.php';

/**
 * Class ML_Fields
 */
class ML_Fields extends ML_Rest {
	/**
	 * ML_Fields constructor.
	 *
	 * @param $api_key
	 */
	function __construct( $api_key ) {
		$this->endpoint = 'fields';

		parent::__construct( $api_key );
	}
}