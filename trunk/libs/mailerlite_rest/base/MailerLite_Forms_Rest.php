<?php

require_once dirname( __FILE__ ) . '/MailerLite_Forms_Rest_Base.php';

/**
 * Class MailerLite_Forms_Rest
 */
class MailerLite_Forms_Rest extends MailerLite_Forms_Rest_Base {
	/** @var string */
	var $endpoint = '';

	/**
	 * MailerLite_Forms_Rest constructor.
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

	/**
	 * @param array $data
	 *
	 * @return array|mixed|object|null
	 * @throws Exception
	 */
	function getAllJson( $data = [] ) {
		return json_decode( $this->execute( 'GET', $data ) );
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