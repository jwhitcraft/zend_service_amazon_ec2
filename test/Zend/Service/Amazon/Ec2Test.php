<?php

set_include_path(
        dirname(__FILE__) . '/../../../../library'
        . PATH_SEPARATOR . get_include_path()
    );


require_once 'Zend/Service/Amazon/Ec2.php';

require_once 'PHPUnit/Framework/TestCase.php';

/**
 * Zend_Service_Amazon_Ec2 test case.
 */
class Ec2Test extends PHPUnit_Framework_TestCase
{

    /**
     * @var Zend_Service_Amazon_Ec2
     */
    private $Zend_Service_Amazon_Ec2;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        parent::tearDown();
    }

    public function testFactoryReturnsKeyPairObject()
    {
        $object = Zend_Service_Amazon_Ec2::factory('keypair', 'access_key', 'secret_access_key');
        $this->assertType('Zend_Service_Amazon_Ec2_Keypair', $object);
    }

    public function testFactoryReturnsElasticIpObject()
    {
        $object = Zend_Service_Amazon_Ec2::factory('elasticip', 'access_key', 'secret_access_key');
        $this->assertType('Zend_Service_Amazon_Ec2_Elasticip', $object);
    }


    public function testFactoryReturnsEbsObject()
    {
        $object = Zend_Service_Amazon_Ec2::factory('ebs', 'access_key', 'secret_access_key');
        $this->assertType('Zend_Service_Amazon_Ec2_Ebs', $object);
    }

    public function testFactoryReturnImageObject()
    {
        $object = Zend_Service_Amazon_Ec2::factory('image', 'access_key', 'secret_access_key');
        $this->assertType('Zend_Service_Amazon_Ec2_Image', $object);
    }

    public function testFactoryReturnsInstanceObject()
    {
        $object = Zend_Service_Amazon_Ec2::factory('instance', 'access_key', 'secret_access_key');
        $this->assertType('Zend_Service_Amazon_Ec2_Instance', $object);
    }

    public function testFactoryReturnsSecurityGroupsObject()
    {
        $object = Zend_Service_Amazon_Ec2::factory('security', 'access_key', 'secret_access_key');
        $this->assertType('Zend_Service_Amazon_Ec2_Securitygroups', $object);
    }

}

