<?php

set_include_path(
        dirname(__FILE__) . '/../../../../../library'
        . PATH_SEPARATOR . get_include_path()
    );

require_once 'Zend/Service/Amazon/Ec2/Instance.php';

require_once 'PHPUnit/Framework/TestCase.php';

/** Zend_Http_Client */
require_once 'Zend/Http/Client.php';

/** Zend_Http_Client_Adapter_Test */
require_once 'Zend/Http/Client/Adapter/Test.php';

/**
 * Zend_Service_Amazon_Ec2_Instance test case.
 */
class InstanceTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Zend_Service_Amazon_Ec2_Instance
     */
    private $Zend_Service_Amazon_Ec2_Instance;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->Zend_Service_Amazon_Ec2_Instance = new Zend_Service_Amazon_Ec2_Instance('access_key', 'secret_access_key');

        $adapter = new Zend_Http_Client_Adapter_Test();
        $client = new Zend_Http_Client(null, array(
            'adapter' => $adapter
        ));
        $this->adapter = $adapter;
        Zend_Service_Amazon_Ec2_Instance::setHttpClient($client);

    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        unset($this->adapter);

        $this->Zend_Service_Amazon_Ec2_Instance = null;

        parent::tearDown();
    }

    /**
     * Tests Zend_Service_Amazon_Ec2_Instance->confirmProduct()
     */
    public function testConfirmProductReturnsOwnerId()
    {
        $rawHttpResponse = "HTTP/1.1 200 OK\r\n"
                    . "Date: Fri, 24 Oct 2008 17:24:52 GMT\r\n"
                    . "Server: hi\r\n"
                    . "Last-modified: Fri, 24 Oct 2008 17:24:52 GMT\r\n"
                    . "Status: 200 OK\r\n"
                    . "Content-type: application/xml; charset=utf-8\r\n"
                    . "Expires: Tue, 31 Mar 1981 05:00:00 GMT\r\n"
                    . "Connection: close\r\n"
                    . "\r\n"
                    . "<ConfirmProductInstanceResponse xmlns=\"http://ec2.amazonaws.com/doc/2008-12-01/\">\r\n"
                    . "  <result>true</result>\r\n"
                    . "  <ownerId>254933287430</ownerId>\r\n"
                    . "</ConfirmProductInstanceResponse>\r\n";
        $this->adapter->setResponse($rawHttpResponse);

        $return = $this->Zend_Service_Amazon_Ec2_Instance->confirmProduct('254933287430', 'i-1bda7172');

        $this->assertEquals('254933287430', $return['ownerId']);
    }

    public function testConfirmProductReturnsFalse()
    {
        $rawHttpResponse = "HTTP/1.1 200 OK\r\n"
                    . "Date: Fri, 24 Oct 2008 17:24:52 GMT\r\n"
                    . "Server: hi\r\n"
                    . "Last-modified: Fri, 24 Oct 2008 17:24:52 GMT\r\n"
                    . "Status: 200 OK\r\n"
                    . "Content-type: application/xml; charset=utf-8\r\n"
                    . "Expires: Tue, 31 Mar 1981 05:00:00 GMT\r\n"
                    . "Connection: close\r\n"
                    . "\r\n"
                    . "<ConfirmProductInstanceResponse xmlns=\"http://ec2.amazonaws.com/doc/2008-12-01/\">\r\n"
                    . "  <result>false</result>\r\n"
                    . "</ConfirmProductInstanceResponse>\r\n";
        $this->adapter->setResponse($rawHttpResponse);

        $return = $this->Zend_Service_Amazon_Ec2_Instance->confirmProduct('254933287430', 'i-1bda7172');

        $this->assertFalse($return);
    }

    /**
     * Tests Zend_Service_Amazon_Ec2_Instance->describe()
     */
    public function testDescribeSingleInstance()
    {
        $rawHttpResponse = "HTTP/1.1 200 OK\r\n"
                    . "Date: Fri, 24 Oct 2008 17:24:52 GMT\r\n"
                    . "Server: hi\r\n"
                    . "Last-modified: Fri, 24 Oct 2008 17:24:52 GMT\r\n"
                    . "Status: 200 OK\r\n"
                    . "Content-type: application/xml; charset=utf-8\r\n"
                    . "Expires: Tue, 31 Mar 1981 05:00:00 GMT\r\n"
                    . "Connection: close\r\n"
                    . "\r\n"
                    . "<DescribeInstancesResponse xmlns=\"http://ec2.amazonaws.com/doc/2008-12-01/\">\r\n"
                    . "  <reservationSet>\r\n"
                    . "    <item>\r\n"
                    . "      <reservationId>r-44a5402d</reservationId>\r\n"
                    . "      <ownerId>UYY3TLBUXIEON5NQVUUX6OMPWBZIQNFM</ownerId>\r\n"
                    . "      <groupSet>\r\n"
                    . "        <item>\r\n"
                    . "          <groupId>default</groupId>\r\n"
                    . "        </item>\r\n"
                    . "      </groupSet>\r\n"
                    . "      <instancesSet>\r\n"
                    . "        <item>\r\n"
                    . "          <instanceId>i-28a64341</instanceId>\r\n"
                    . "          <imageId>ami-6ea54007</imageId>\r\n"
                    . "          <instanceState>\r\n"
                    . "            <code>0</code>\r\n"
                    . "            <name>running</name>\r\n"
                    . "          </instanceState>\r\n"
                    . "          <privateDnsName>10-251-50-75.ec2.internal</privateDnsName>\r\n"
                    . "          <dnsName>ec2-72-44-33-4.compute-1.amazonaws.com</dnsName>\r\n"
                    . "          <keyName>example-key-name</keyName>\r\n"
                    . "          <productCodesSet>\r\n"
                    . "            <item><productCode>774F4FF8</productCode></item>\r\n"
                    . "          </productCodesSet>\r\n"
                    . "          <instanceType>m1.small</instanceType>\r\n"
                    . "          <launchTime>2007-08-07T11:54:42.000Z</launchTime>\r\n"
                    . "          <placement>\r\n"
                    . "           <availabilityZone>us-east-1b</availabilityZone>\r\n"
                    . "          </placement>\r\n"
                    . "          <kernelId>aki-ba3adfd3</kernelId>\r\n"
                    . "          <ramdiskId>ari-badbad00</ramdiskId>\r\n"
                    . "        </item>\r\n"
                    . "      </instancesSet>\r\n"
                    . "    </item>\r\n"
                    . "  </reservationSet>\r\n"
                    . "</DescribeInstancesResponse>\r\n";
        $this->adapter->setResponse($rawHttpResponse);

        $return = $this->Zend_Service_Amazon_Ec2_Instance->describe('i-28a64341');

        $this->assertEquals('r-44a5402d', $return['reservationId']);
        $this->assertEquals('default', $return['groupSet'][0]);
        $this->assertEquals('i-28a64341', $return['instances'][0]['instanceId']);
        $this->assertEquals('ami-6ea54007', $return['instances'][0]['imageId']);
        $this->assertEquals('m1.small', $return['instances'][0]['instanceType']);
        $this->assertEquals('us-east-1b', $return['instances'][0]['availabilityZone']);
    }

    /**
     * Tests Zend_Service_Amazon_Ec2_Instance->run()
     */
    public function testRunOneSecurityGroup()
    {
        $rawHttpResponse = "HTTP/1.1 200 OK\r\n"
                    . "Date: Fri, 24 Oct 2008 17:24:52 GMT\r\n"
                    . "Server: hi\r\n"
                    . "Last-modified: Fri, 24 Oct 2008 17:24:52 GMT\r\n"
                    . "Status: 200 OK\r\n"
                    . "Content-type: application/xml; charset=utf-8\r\n"
                    . "Expires: Tue, 31 Mar 1981 05:00:00 GMT\r\n"
                    . "Connection: close\r\n"
                    . "\r\n"
                    . "<RunInstancesResponse xmlns=\"http://ec2.amazonaws.com/doc/2008-12-01/\">\r\n"
                    . "  <reservationId>r-47a5402e</reservationId>\r\n"
                    . "  <ownerId>495219933132</ownerId>\r\n"
                    . "  <groupSet>\r\n"
                    . "    <item>\r\n"
                    . "      <groupId>default</groupId>\r\n"
                    . "    </item>\r\n"
                    . "  </groupSet>\r\n"
                    . "  <instancesSet>\r\n"
                    . "    <item>\r\n"
                    . "      <instanceId>i-2ba64342</instanceId>\r\n"
                    . "      <imageId>ami-60a54009</imageId>\r\n"
                    . "      <instanceState>\r\n"
                    . "        <code>0</code>\r\n"
                    . "        <name>pending</name>\r\n"
                    . "      </instanceState>\r\n"
                    . "      <privateDnsName></privateDnsName>\r\n"
                    . "      <dnsName></dnsName>\r\n"
                    . "      <keyName>example-key-name</keyName>\r\n"
                    . "       <amiLaunchIndex>0</amiLaunchIndex>\r\n"
                    . "      <InstanceType>m1.small</InstanceType>\r\n"
                    . "      <launchTime>2007-08-07T11:51:50.000Z</launchTime>\r\n"
                    . "      <placement>\r\n"
                    . "        <availabilityZone>us-east-1b</availabilityZone>\r\n"
                    . "      </placement>\r\n"
                    . "    </item>\r\n"
                    . "    <item>\r\n"
                    . "      <instanceId>i-2bc64242</instanceId>\r\n"
                    . "      <imageId>ami-60a54009</imageId>\r\n"
                    . "      <instanceState>\r\n"
                    . "        <code>0</code>\r\n"
                    . "        <name>pending</name>\r\n"
                    . "      </instanceState>\r\n"
                    . "      <privateDnsName></privateDnsName>\r\n"
                    . "      <dnsName></dnsName>\r\n"
                    . "      <keyName>example-key-name</keyName>\r\n"
                    . "      <amiLaunchIndex>1</amiLaunchIndex>\r\n"
                    . "      <InstanceType>m1.small</InstanceType>\r\n"
                    . "      <launchTime>2007-08-07T11:51:50.000Z</launchTime>\r\n"
                    . "      <placement>\r\n"
                    . "        <availabilityZone>us-east-1b</availabilityZone>\r\n"
                    . "      </placement>\r\n"
                    . "    </item>\r\n"
                    . "    <item>\r\n"
                    . "      <instanceId>i-2be64332</instanceId>\r\n"
                    . "      <imageId>ami-60a54009</imageId>\r\n"
                    . "      <instanceState>\r\n"
                    . "        <code>0</code>\r\n"
                    . "        <name>pending</name>\r\n"
                    . "      </instanceState>\r\n"
                    . "      <privateDnsName></privateDnsName>\r\n"
                    . "      <dnsName></dnsName>\r\n"
                    . "      <keyName>example-key-name</keyName>\r\n"
                    . "      <amiLaunchIndex>2</amiLaunchIndex>\r\n"
                    . "      <InstanceType>m1.small</InstanceType>\r\n"
                    . "      <launchTime>2007-08-07T11:51:50.000Z</launchTime>\r\n"
                    . "      <placement>\r\n"
                    . "        <availabilityZone>us-east-1b</availabilityZone>\r\n"
                    . "      </placement>\r\n"
                    . "    </item>\r\n"
                    . "  </instancesSet>\r\n"
                    . "</RunInstancesResponse>\r\n";
        $this->adapter->setResponse($rawHttpResponse);

        $return = $this->Zend_Service_Amazon_Ec2_Instance->run('ami-60a54009', 1, 3, "example-key-name", "default", "instance_id=www3", 'm1.small', 'us-east-1b', 'aki-4438dd2d', 'ari-4538dd2c', 'vertdevice', '/dev/sdv');

        $this->assertEquals(3, count($return['instances']));
        $this->assertEquals('495219933132', $return['ownerId']);

        $arrInstanceIds = array('i-2ba64342', 'i-2bc64242', 'i-2be64332');

        foreach($return['instances'] as $k => $r) {
            $this->assertEquals($arrInstanceIds[$k], $r['instanceId']);
            $this->assertEquals($k, $r['amiLaunchIndex']);
        }

    }

/**
     * Tests Zend_Service_Amazon_Ec2_Instance->run()
     */
    public function testRunMultipleSecurityGroups()
    {
        $rawHttpResponse = "HTTP/1.1 200 OK\r\n"
                    . "Date: Fri, 24 Oct 2008 17:24:52 GMT\r\n"
                    . "Server: hi\r\n"
                    . "Last-modified: Fri, 24 Oct 2008 17:24:52 GMT\r\n"
                    . "Status: 200 OK\r\n"
                    . "Content-type: application/xml; charset=utf-8\r\n"
                    . "Expires: Tue, 31 Mar 1981 05:00:00 GMT\r\n"
                    . "Connection: close\r\n"
                    . "\r\n"
                    . "<RunInstancesResponse xmlns=\"http://ec2.amazonaws.com/doc/2008-12-01/\">\r\n"
                    . "  <reservationId>r-47a5402e</reservationId>\r\n"
                    . "  <ownerId>495219933132</ownerId>\r\n"
                    . "  <groupSet>\r\n"
                    . "    <item>\r\n"
                    . "      <groupId>default</groupId>\r\n"
                    . "    </item>\r\n"
                    . "    <item>\r\n"
                    . "      <groupId>web</groupId>\r\n"
                    . "    </item>\r\n"
                    . "  </groupSet>\r\n"
                    . "  <instancesSet>\r\n"
                    . "    <item>\r\n"
                    . "      <instanceId>i-2ba64342</instanceId>\r\n"
                    . "      <imageId>ami-60a54009</imageId>\r\n"
                    . "      <instanceState>\r\n"
                    . "        <code>0</code>\r\n"
                    . "        <name>pending</name>\r\n"
                    . "      </instanceState>\r\n"
                    . "      <privateDnsName></privateDnsName>\r\n"
                    . "      <dnsName></dnsName>\r\n"
                    . "      <keyName>example-key-name</keyName>\r\n"
                    . "       <amiLaunchIndex>0</amiLaunchIndex>\r\n"
                    . "      <InstanceType>m1.small</InstanceType>\r\n"
                    . "      <launchTime>2007-08-07T11:51:50.000Z</launchTime>\r\n"
                    . "      <placement>\r\n"
                    . "        <availabilityZone>us-east-1b</availabilityZone>\r\n"
                    . "      </placement>\r\n"
                    . "    </item>\r\n"
                    . "  </instancesSet>\r\n"
                    . "</RunInstancesResponse>\r\n";
        $this->adapter->setResponse($rawHttpResponse);

        $return = $this->Zend_Service_Amazon_Ec2_Instance->run('ami-60a54009', 1, 3, 'example-key-name', array('default','web'), 'instance_id=www3', 'm1.small', 'us-east-1b', 'aki-4438dd2d', 'ari-4538dd2c', 'vertdevice', '/dev/sdv');

        $arrGroups = array('default', 'web');

        $this->assertSame($arrGroups, $return['groupSet']);
    }

    public function testTerminateSingleInstances()
    {
        $rawHttpResponse = "HTTP/1.1 200 OK\r\n"
                    . "Date: Fri, 24 Oct 2008 17:24:52 GMT\r\n"
                    . "Server: hi\r\n"
                    . "Last-modified: Fri, 24 Oct 2008 17:24:52 GMT\r\n"
                    . "Status: 200 OK\r\n"
                    . "Content-type: application/xml; charset=utf-8\r\n"
                    . "Expires: Tue, 31 Mar 1981 05:00:00 GMT\r\n"
                    . "Connection: close\r\n"
                    . "\r\n"
                    . "<TerminateInstancesResponse xmlns=\"http://ec2.amazonaws.com/doc/2008-12-01/\">\r\n"
                    . "  <instancesSet>\r\n"
                    . "    <item>\r\n"
                    . "      <instanceId>i-28a64341</instanceId>\r\n"
                    . "      <shutdownState>\r\n"
                    . "        <code>32</code>\r\n"
                    . "        <name>shutting-down</name>\r\n"
                    . "      </shutdownState>\r\n"
                    . "      <previousState>\r\n"
                    . "        <code>16</code>\r\n"
                    . "        <name>running</name>\r\n"
                    . "      </previousState>\r\n"
                    . "    </item>\r\n"
                    . "  </instancesSet>\r\n"
                    . "</TerminateInstancesResponse>\r\n";
        $this->adapter->setResponse($rawHttpResponse);

        $return = $this->Zend_Service_Amazon_Ec2_Instance->terminate('i-28a64341');

        $this->assertEquals(1, count($return));

        foreach($return as $r) {
            $this->assertEquals('i-28a64341', $r['instanceId']);
        }
    }

    public function testTerminateMultipleInstances()
    {
        $rawHttpResponse = "HTTP/1.1 200 OK\r\n"
                    . "Date: Fri, 24 Oct 2008 17:24:52 GMT\r\n"
                    . "Server: hi\r\n"
                    . "Last-modified: Fri, 24 Oct 2008 17:24:52 GMT\r\n"
                    . "Status: 200 OK\r\n"
                    . "Content-type: application/xml; charset=utf-8\r\n"
                    . "Expires: Tue, 31 Mar 1981 05:00:00 GMT\r\n"
                    . "Connection: close\r\n"
                    . "\r\n"
                    . "<TerminateInstancesResponse xmlns=\"http://ec2.amazonaws.com/doc/2008-12-01/\">\r\n"
                    . "  <instancesSet>\r\n"
                    . "    <item>\r\n"
                    . "      <instanceId>i-28a64341</instanceId>\r\n"
                    . "      <shutdownState>\r\n"
                    . "        <code>32</code>\r\n"
                    . "        <name>shutting-down</name>\r\n"
                    . "      </shutdownState>\r\n"
                    . "      <previousState>\r\n"
                    . "        <code>16</code>\r\n"
                    . "        <name>running</name>\r\n"
                    . "      </previousState>\r\n"
                    . "    </item>\r\n"
                    . "    <item>\r\n"
                    . "      <instanceId>i-21a64348</instanceId>\r\n"
                    . "      <shutdownState>\r\n"
                    . "        <code>32</code>\r\n"
                    . "        <name>shutting-down</name>\r\n"
                    . "      </shutdownState>\r\n"
                    . "      <previousState>\r\n"
                    . "        <code>16</code>\r\n"
                    . "        <name>running</name>\r\n"
                    . "      </previousState>\r\n"
                    . "    </item>\r\n"
                    . "  </instancesSet>\r\n"
                    . "</TerminateInstancesResponse>\r\n";
        $this->adapter->setResponse($rawHttpResponse);

        $arrInstanceIds = array('i-28a64341', 'i-21a64348');

        $return = $this->Zend_Service_Amazon_Ec2_Instance->terminate($arrInstanceIds);

        $this->assertEquals(2, count($return));

        foreach($return as $k=>$r) {
            $this->assertEquals($arrInstanceIds[$k], $r['instanceId']);
        }
    }

}

