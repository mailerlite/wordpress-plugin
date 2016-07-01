<?php

require_once dirname(__FILE__) . '/base/ML_Rest.php';

class ML_Webforms extends ML_Rest
{
    function __construct($api_key)
    {
        $this->name = 'webforms';

        parent::__construct($api_key);
    }
}