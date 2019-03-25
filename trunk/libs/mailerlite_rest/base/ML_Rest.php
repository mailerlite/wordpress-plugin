<?php

require_once dirname( __FILE__ ) . '/ML_Rest_Base.php';

/**
 * Class ML_Rest
 */
class ML_Rest extends ML_Rest_Base {
	/** @var string */
	var $endpoint = '';

	/**
	 * ML_Rest constructor.
	 *
	 * @param $api_key
	 */
	function __construct( $api_key ) {
		parent::__construct();

		$this->apiKey = $api_key;

		$this->path = $this->url . $this->endpoint . '/';
	}

	function getAll() {
		return $this->execute( 'GET' );
	}

	function getAllJson() {
		return json_decode( $this->execute( 'GET' ) );
	}

	function get( $data = null ) {
		if ( ! $this->id ) {
			throw new InvalidArgumentException( 'ID is not set.' );
		}

		return $this->execute( 'GET' );
	}

	function add( $data = null ) {
		return $this->execute( 'POST', $data );
	}

	function put( $data = null ) {
		return $this->execute( 'PUT', $data );
	}

	function remove( $data = null ) {
		return $this->execute( 'DELETE' );
	}
}