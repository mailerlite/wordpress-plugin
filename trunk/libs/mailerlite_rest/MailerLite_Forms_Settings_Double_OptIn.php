<?php

require_once dirname( __FILE__ ) . '/base/MailerLite_Forms_Rest.php';

/**
 * Class MailerLite_Forms_Settings_Double_OptIn
 */
class MailerLite_Forms_Settings_Double_OptIn extends MailerLite_Forms_Rest {
	/**
	 * MailerLite_Forms_Settings_Double_OptIn constructor.
	 *
	 * @param $api_key
	 */
	function __construct( $api_key ) {
		$this->endpoint = 'settings/double_optin';

		parent::__construct( $api_key );
		$this->path = $this->url . $this->endpoint;

	}

	/**
	 * @return |null
	 * @throws Exception
	 */
	public function enable() {
		return $this->setStatus( true );
	}

	/**
	 * @return |null
	 * @throws Exception
	 */
	public function disable() {
		return $this->setStatus( false );
	}

	/**
	 * @return bool
	 * @throws Exception
	 */
	public function status() {
		$response = $this->execute( 'GET' );
		$array    = json_decode( $response, true );

		if ( isset( $array['enabled'] ) && $array['enabled'] ) {
			return true;
		}

		return false;
	}

	/**
	 * @param boolean $status
	 *
	 * @return |null
	 * @throws Exception
	 */
	public function setStatus( $status ) {
		return $this->execute( 'POST', [ 'enable' => $status ] );
	}
}