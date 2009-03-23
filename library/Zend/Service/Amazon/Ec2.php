<?php

require_once 'Zend/Loader.php';

class Zend_Service_Amazon_Ec2
{
    /**
     * Factory method to fetch what you want to work with.
     *
     * @param string $section           Create the method that you want to work with
     * @param string $key               Override the default aws key
     * @param string $secret_key        Override the default aws secretkey
     * @throws Zend_Service_Amazon_Ec2_Exception
     * @return object
     */
    public static function factory($section, $key = null, $secret_key = null)
    {
        switch(strtolower($section)) {
            case 'keypair':
                $class = 'Zend_Service_Amazon_Ec2_Keypair';
                break;
            case 'eip':
                // break left out
            case 'elasticip':
                $class = 'Zend_Service_Amazon_Ec2_Elasticip';
                break;
            case 'ebs':
                $class = 'Zend_Service_Amazon_Ec2_Ebs';
                break;
            case 'availabilityzones':
                // break left out
            case 'zones':
                $class = 'Zend_Service_Amazon_Ec2_Availabilityzones';
                break;
            case 'ami':
                // break left out
            case 'image':
                $class = 'Zend_Service_Amazon_Ec2_Image';
                break;
            case 'instance':
                $class = 'Zend_Service_Amazon_Ec2_Instance';
                break;
            case 'security':
                // break left out
            case 'securitygroups':
                $class = 'Zend_Service_Amazon_Ec2_Securitygroups';
                break;
            default:
                throw new Zend_Service_Amazon_Ec2_Exception('Invalid Section: ' . $section);
                break;
        }

        Zend_Loader::loadClass($class);
        return new $class($key, $secret_key);
    }
}

