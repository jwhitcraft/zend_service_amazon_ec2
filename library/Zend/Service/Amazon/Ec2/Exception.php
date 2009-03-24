<?php

require_once 'Zend/Service/Amazon/Exception.php';

class Zend_Service_Amazon_Ec2_Exception extends Zend_Service_Amazon_Exception
{
    private $awsErrorCode = '';

    public function __construct($message, $code = 0, $awsErrorCode = '')
    {
        parent::__construct($message, $code);
        $this->awsErrorCode = $awsErrorCode;
    }

    public function getErrorCode()
    {
        return $this->awsErrorCode;
    }
}
