<?php

require_once 'PHPUnit/Framework/TestSuite.php';

require_once 'test/Zend/Service/Amazon/Ec2/AvailabilityzonesTest.php';

require_once 'test/Zend/Service/Amazon/Ec2/EbsTest.php';

require_once 'test/Zend/Service/Amazon/Ec2/ElasticipTest.php';

require_once 'test/Zend/Service/Amazon/Ec2/ImageTest.php';

require_once 'test/Zend/Service/Amazon/Ec2/InstanceTest.php';

require_once 'test/Zend/Service/Amazon/Ec2/KeypairTest.php';

require_once 'test/Zend/Service/Amazon/Ec2/RegionTest.php';

require_once 'test/Zend/Service/Amazon/Ec2/SecuritygroupsTest.php';

/**
 * Static test suite.
 */
class AllTests extends PHPUnit_Framework_TestSuite
{

    /**
     * Constructs the test suite handler.
     */
    public function __construct()
    {
        $this->setName( 'AllTests' );
        $this->addTestSuite( 'AvailabilityzonesTest' );
        $this->addTestSuite( 'EbsTest' );
        $this->addTestSuite( 'ElasticipTest' );
        $this->addTestSuite( 'ImageTest' );
        $this->addTestSuite( 'InstanceTest' );
        $this->addTestSuite( 'KeypairTest' );
        $this->addTestSuite( 'RegionTest' );
        $this->addTestSuite( 'SecuritygroupsTest' );
    }

    /**
     * Creates the suite.
     */
    public static function suite()
    {
        return new self( );
    }
}

