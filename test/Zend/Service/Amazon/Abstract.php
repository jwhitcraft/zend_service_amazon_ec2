<?php

require_once 'Zend/Service/Amazon/Abstract.php';

require_once 'PHPUnit/Framework/TestCase.php';

/**
 * Zend_Service_Amazon_Sqs_Queue test case.
 */
class AmamzonAbstract extends PHPUnit_Framework_TestCase
{
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

    public function testNoKeysThrowException()
    {
        try {
            $class = new TestAmamzonAbstract();
            $this->fail('Exception should be thrown when no keys are passed in.');
        } catch(Zend_Service_Amazon_Exception $zsae) {}
    }

    public function testConstructorWithKeysDoesNotThrowException()
    {
        try {
            $class = new TestAmamzonAbstract('TestAccessKey', 'TestSecretKey');
        } catch(Zend_Service_Amazon_Exception $zsae) {
            $this->fail('Exception should be thrown when no keys are passed in.');
        }
    }

    public function testSetStaticKeys()
    {
        TestAmamzonAbstract::setKeys('TestAccessKey', 'TestSecretKey');
        $class = new TestAmamzonAbstract();

        $this->assertEquals('TestAccessKey', $class->returnAccessKey());
        $this->assertEquals('TestSecretKey', $class->returnSecretKey());
    }

    public function testPassKeysIntoConstructor()
    {
        $class = new TestAmamzonAbstract('TestAccessKey', 'TestSecretKey');

        $this->assertEquals('TestAccessKey', $class->returnAccessKey());
        $this->assertEquals('TestSecretKey', $class->returnSecretKey());
    }

    public function testPassedInKeysOverrideStaticSetKeys()
    {
        TestAmamzonAbstract::setKeys('TestStaticAccessKey', 'TestStaticSecretKey');
        $class = new TestAmamzonAbstract('TestAccessKey', 'TestSecretKey');

        $this->assertEquals('TestAccessKey', $class->returnAccessKey());
        $this->assertEquals('TestSecretKey', $class->returnSecretKey());
    }
}

class TestAmamzonAbstract extends Zend_Service_Amazon_Abstract
{
    public function returnAccessKey()
    {
        return $this->accessKey;
    }

    public function returnSecretKey()
    {
        return $this->secretKey;
    }
}

