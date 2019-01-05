<?php

require_once dirname(__FILE__) . '/base/ML_Rest.php';

/**
 * Class ML_Fields
 */
class ML_Fields extends ML_Rest
{
    function __construct($api_key)
    {
        $this->endpoint = 'fields';

        parent::__construct($api_key);
    }
}