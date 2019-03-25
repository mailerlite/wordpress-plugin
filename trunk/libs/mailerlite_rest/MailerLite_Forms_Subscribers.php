<?php

require_once dirname( __FILE__ ) . '/base/MailerLite_Forms_Rest.php';

/**
 * Class MailerLite_Forms_Subscribers
 */
class MailerLite_Forms_Subscribers extends MailerLite_Forms_Rest {
	/** @var int|null */
	var $groupId = null;

	/**
	 * MailerLite_Forms_Subscribers constructor.
	 *
	 * @param $api_key
	 */
	public function __construct( $api_key ) {
		$this->endpoint = 'subscribers';

		parent::__construct( $api_key );
	}

	function add( $subscriber = null, $resubscribe = 0 ) {
		$subscriber['resubscribe'] = $resubscribe;

		return $this->execute( 'POST', $subscriber );
	}

	function addAll( $subscribers, $resubscribe = 0 ) {
		$data['resubscribe'] = $resubscribe;

		$data['subscribers'] = $subscribers;

		$this->path .= 'import/';

		return $this->execute( 'POST', $data );
	}

	function get( $email = null, $history = 0 ) {
		$this->setGroupId( null );

		$this->path .= '?email=' . urlencode( $email );

		if ( $history ) {
			$this->path .= '&history=1';
		}

		return $this->execute( 'GET' );
	}

	function remove( $email = null ) {
		$this->path .= '?email=' . urlencode( $email );

		return $this->execute( 'DELETE' );
	}

	function unsubscribe( $email ) {
		$this->path .= 'unsubscribe/?email=' . urlencode( $email );

		return $this->execute( 'POST' );
	}

	function setGroupId( $groupId ) {
		$this->groupId = $groupId;

		if ( $this->groupId ) {
			$this->path = $this->url . 'groups' . '/' . $groupId . '/subscribers';
		} else {
			$this->path = $this->url . $this->endpoint . '/';
		}

		return $this;
	}
}