<?php

set_include_path(
        dirname(__FILE__) . '/../../../../../library'
        . PATH_SEPARATOR . get_include_path()
    );

require_once 'library/Zend/Service/Amazon/Ec2/Ebs.php';

require_once 'PHPUnit/Framework/TestCase.php';

/** Zend_Http_Client */
require_once 'Zend/Http/Client.php';

/** Zend_Http_Client_Adapter_Test */
require_once 'Zend/Http/Client/Adapter/Test.php';

/**
 * Zend_Service_Amazon_Ec2_Ebs test case.
 */
class EbsTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Zend_Service_Amazon_Ec2_Ebs
     */
    private $Zend_Service_Amazon_Ec2_Ebs;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->Zend_Service_Amazon_Ec2_Ebs = new Zend_Service_Amazon_Ec2_Ebs('access_key', 'secret_access_key');

        $adapter = new Zend_Http_Client_Adapter_Test();
        $client = new Zend_Http_Client(null, array(
            'adapter' => $adapter
        ));
        $this->adapter = $adapter;
        Zend_Service_Amazon_Ec2_Ebs::setHttpClient($client);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        unset($this->adapter);
        $this->Zend_Service_Amazon_Ec2_Ebs = null;

        parent::tearDown();
    }

    /**
     * Tests Zend_Service_Amazon_Ec2_Ebs->attachVolume()
     */
    public function testAttachVolume()
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
                    . "<AttachVolumeResponse  xmlns=\"http://ec2.amazonaws.com/doc/2008-12-01/\">\r\n"
                    . "  <volumeId>vol-4d826724</volumeId>\r\n"
                    . "  <instanceId>i-6058a509</instanceId>\r\n"
                    . "  <device>/dev/sdh</device>\r\n"
                    . "  <status>attaching</status>\r\n"
                    . "  <attachTime>2008-05-07T11:51:50.000Z</attachTime>\r\n"
                    . "</AttachVolumeResponse >";
        $this->adapter->setResponse($rawHttpResponse);

        $return = $this->Zend_Service_Amazon_Ec2_Ebs->attachVolume('vol-4d826724', 'i-6058a509', '/dev/sdh');

        $this->assertEquals('vol-4d826724', $return['volumeId']);
        $this->assertEquals('i-6058a509', $return['instanceId']);
        $this->assertEquals('/dev/sdh', $return['device']);
    }

    /**
     * Tests Zend_Service_Amazon_Ec2_Ebs->createSnapshot()
     */
    public function testCreateSnapshot()
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
                    . "<CreateSnapshotResponse xmlns=\"http://ec2.amazonaws.com/doc/2008-12-01/\">\r\n"
                    . "  <snapshotId>snap-78a54011</snapshotId>\r\n"
                    . "  <volumeId>vol-4d826724</volumeId>\r\n"
                    . "  <status>pending</status>\r\n"
                    . "  <startTime>2008-05-07T11:51:50.000Z</startTime>\r\n"
                    . "  <progress></progress>\r\n"
                    . "</CreateSnapshotResponse>";
        $this->adapter->setResponse($rawHttpResponse);

        $return = $this->Zend_Service_Amazon_Ec2_Ebs->createSnapshot('vol-4d826724');

        $this->assertEquals('vol-4d826724', $return['volumeId']);
        $this->assertEquals('snap-78a54011', $return['snapshotId']);

    }

    /**
     * Tests Zend_Service_Amazon_Ec2_Ebs->createVolume()
     */
    public function testCreateVolume()
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
                    . "<CreateVolumeResponse xmlns=\"http://ec2.amazonaws.com/doc/2008-12-01/\">\r\n"
                    . "  <volumeId>vol-4d826724</volumeId>\r\n"
                    . "  <size>400</size>\r\n"
                    . "  <status>creating</status>\r\n"
                    . "  <createTime>2008-05-07T11:51:50.000Z</createTime>\r\n"
                    . "  <availabilityZone>us-east-1a</availabilityZone>\r\n"
                    . "  <snapshotId></snapshotId>\r\n"
                    . "</CreateVolumeResponse>";
        $this->adapter->setResponse($rawHttpResponse);

        $return = $this->Zend_Service_Amazon_Ec2_Ebs->createVolume(400, 'us-east-1a');

        $this->assertEquals('400', $return['size']);
        $this->assertEquals('vol-4d826724', $return['volumeId']);
        $this->assertEquals('us-east-1a', $return['availabilityZone']);

    }

    public function testCreateVolumeFromSnapshot()
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
                    . "<CreateVolumeResponse xmlns=\"http://ec2.amazonaws.com/doc/2008-12-01/\">\r\n"
                    . "  <volumeId>vol-4d826724</volumeId>\r\n"
                    . "  <size>400</size>\r\n"
                    . "  <status>creating</status>\r\n"
                    . "  <createTime>2008-05-07T11:51:50.000Z</createTime>\r\n"
                    . "  <availabilityZone>us-east-1a</availabilityZone>\r\n"
                    . "  <snapshotId>snap-78a54011</snapshotId>\r\n"
                    . "</CreateVolumeResponse>";
        $this->adapter->setResponse($rawHttpResponse);

        $return = $this->Zend_Service_Amazon_Ec2_Ebs->createVolume(400, 'us-east-1a', 'snap-78a54011');

        $this->assertEquals('400', $return['size']);
        $this->assertEquals('vol-4d826724', $return['volumeId']);
        $this->assertEquals('us-east-1a', $return['availabilityZone']);
        $this->assertEquals('snap-78a54011', $return['snapshotId']);

    }

    /**
     * Tests Zend_Service_Amazon_Ec2_Ebs->deleteSnapshot()
     */
    public function testDeleteSnapshot()
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
                    . "<DeleteSnapshotResponse xmlns=\"http://ec2.amazonaws.com/doc/2008-12-01/\">\r\n"
                    . "  <return>true</return>\r\n"
                    . "</DeleteSnapshotResponse>";
        $this->adapter->setResponse($rawHttpResponse);

        $return = $this->Zend_Service_Amazon_Ec2_Ebs->deleteSnapshot('snap-78a54011');

        $this->assertType('boolean', $return);
        $this->assertTrue($return);

    }

    public function testDeleteVolume()
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
                    . "<DeleteVolumeResponse xmlns=\"http://ec2.amazonaws.com/doc/2008-12-01/\">\r\n"
                    . "  <return>true</return>\r\n"
                    . "</DeleteVolumeResponse>";
        $this->adapter->setResponse($rawHttpResponse);

        $return = $this->Zend_Service_Amazon_Ec2_Ebs->deleteVolume('vol-4d826724');

        $this->assertType('boolean', $return);
        $this->assertTrue($return);
    }

    /**
     * Tests Zend_Service_Amazon_Ec2_Ebs->describeSnapshot()
     */
    public function testDescribeSingleSnapshot()
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
                    . "<DescribeSnapshotsResponse xmlns=\"http://ec2.amazonaws.com/doc/2008-12-01/\">\r\n"
                    . "  <snapshotSet>\r\n"
                    . "    <item>\r\n"
                    . "      <snapshotId>snap-78a54011</snapshotId>\r\n"
                    . "      <volumeId>vol-4d826724</volumeId>\r\n"
                    . "      <status>pending</status>\r\n"
                    . "      <startTime>2008-05-07T12:51:50.000Z</startTime>\r\n"
                    . "      <progress>80%</progress>\r\n"
                    . "    </item>\r\n"
                    . "  </snapshotSet>\r\n"
                    . "</DescribeSnapshotsResponse>";
        $this->adapter->setResponse($rawHttpResponse);

        $return = $this->Zend_Service_Amazon_Ec2_Ebs->describeSnapshot('snap-78a54011');

        $this->assertEquals('snap-78a54011', $return[0]['snapshotId']);
        $this->assertEquals('vol-4d826724', $return[0]['volumeId']);
    }

    public function testDescribeMultipleSnapshots()
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
                    . "<DescribeSnapshotsResponse xmlns=\"http://ec2.amazonaws.com/doc/2008-12-01/\">\r\n"
                    . "  <snapshotSet>\r\n"
                    . "    <item>\r\n"
                    . "      <snapshotId>snap-78a54011</snapshotId>\r\n"
                    . "      <volumeId>vol-4d826724</volumeId>\r\n"
                    . "      <status>pending</status>\r\n"
                    . "      <startTime>2008-05-07T12:51:50.000Z</startTime>\r\n"
                    . "      <progress>80%</progress>\r\n"
                    . "    </item>\r\n"
                    . "    <item>\r\n"
                    . "      <snapshotId>snap-78a54012</snapshotId>\r\n"
                    . "      <volumeId>vol-4d826725</volumeId>\r\n"
                    . "      <status>pending</status>\r\n"
                    . "      <startTime>2008-08-07T12:51:50.000Z</startTime>\r\n"
                    . "      <progress>65%</progress>\r\n"
                    . "    </item>\r\n"
                    . "  </snapshotSet>\r\n"
                    . "</DescribeSnapshotsResponse>";
        $this->adapter->setResponse($rawHttpResponse);

        $return = $this->Zend_Service_Amazon_Ec2_Ebs->describeSnapshot(array('snap-78a54011', 'snap-78a54012'));

        $arrSnapshots = array(
            array(
                'snapshotId'    => 'snap-78a54011',
                'volumeId'      => 'vol-4d826724',
                'status'        => 'pending',
                'startTime'     => '2008-05-07T12:51:50.000Z',
                'progress'      => '80%',
            ),
            array(
                'snapshotId'    => 'snap-78a54012',
                'volumeId'      => 'vol-4d826725',
                'status'        => 'pending',
                'startTime'     => '2008-08-07T12:51:50.000Z',
                'progress'      => '65%',
            )
        );

        foreach($return as $k => $r) {
            $this->assertSame($arrSnapshots[$k], $r);
        }

    }

    /**
     * Tests Zend_Service_Amazon_Ec2_Ebs->describeVolume()
     */
    public function testDescribeSingleVolume()
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
                    . "<DescribeVolumesResponse xmlns=\"http://ec2.amazonaws.com/doc/2008-12-01/\">\r\n"
                    . "<volumeSet>\r\n"
                    . "  <item>\r\n"
                    . "    <volumeId>vol-4282672b</volumeId>\r\n"
                    . "    <size>800</size>\r\n"
                    . "    <status>in-use</status>\r\n"
                    . "    <createTime>2008-05-07T11:51:50.000Z</createTime>\r\n"
                    . "    <attachmentSet>\r\n"
                    . "      <item>\r\n"
                    . "        <volumeId>vol-4282672b</volumeId>\r\n"
                    . "        <instanceId>i-6058a509</instanceId>\r\n"
                    . "        <size>800</size>\r\n"
                    . "        <snapshotId>snap-12345678</snapshotId>\r\n"
                    . "        <availabilityZone>us-east-1a</availabilityZone>\r\n"
                    . "        <status>attached</status>\r\n"
                    . "        <attachTime>2008-05-07T12:51:50.000Z</attachTime>\r\n"
                    . "      </item>\r\n"
                    . "    </attachmentSet>\r\n"
                    . "  </item>\r\n"
                    . "</volumeSet>\r\n"
                    . "</DescribeVolumesResponse>";
        $this->adapter->setResponse($rawHttpResponse);

        $return = $this->Zend_Service_Amazon_Ec2_Ebs->describeVolume('vol-4282672b');

        $this->assertEquals('vol-4282672b', $return[0]['volumeId']);
        $this->assertEquals('in-use', $return[0]['status']);
        $this->assertType('array', $return[0]['attachmentSet']);

    }

    public function testDescribeMultipleVolume()
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
                    . "<DescribeVolumesResponse xmlns=\"http://ec2.amazonaws.com/doc/2008-12-01/\">\r\n"
                    . "<volumeSet>\r\n"
                    . "  <item>\r\n"
                    . "    <volumeId>vol-4282672b</volumeId>\r\n"
                    . "    <size>800</size>\r\n"
                    . "    <status>in-use</status>\r\n"
                    . "    <createTime>2008-05-07T11:51:50.000Z</createTime>\r\n"
                    . "    <attachmentSet>\r\n"
                    . "      <item>\r\n"
                    . "        <volumeId>vol-4282672b</volumeId>\r\n"
                    . "        <instanceId>i-6058a509</instanceId>\r\n"
                    . "        <device>/dev/sdh</device>\r\n"
                    . "        <snapshotId>snap-12345678</snapshotId>\r\n"
                    . "        <availabilityZone>us-east-1a</availabilityZone>\r\n"
                    . "        <status>attached</status>\r\n"
                    . "        <attachTime>2008-05-07T12:51:50.000Z</attachTime>\r\n"
                    . "      </item>\r\n"
                    . "    </attachmentSet>\r\n"
                    . "  </item>\r\n"
                    . "  <item>\r\n"
                    . "    <volumeId>vol-42826775</volumeId>\r\n"
                    . "    <size>40</size>\r\n"
                    . "    <status>available</status>\r\n"
                    . "    <createTime>2008-08-07T11:51:50.000Z</createTime>\r\n"
                    . "  </item>\r\n"
                    . "</volumeSet>\r\n"
                    . "</DescribeVolumesResponse>";
        $this->adapter->setResponse($rawHttpResponse);

        $return = $this->Zend_Service_Amazon_Ec2_Ebs->describeVolume(array('vol-4282672b', 'vol-42826775'));

        $arrVolumes = array(
            array(
                'volumeId'          => 'vol-4282672b',
                'size'              => '800',
                'status'            => 'in-use',
                'createTime'        => '2008-05-07T11:51:50.000Z',
                'attachmentSet'     => array(
                    'volumeId'              => 'vol-4282672b',
                    'instanceId'            => 'i-6058a509',
                    'device'                => '/dev/sdh',
                    'status'                => 'attached',
                    'attachTime'            => '2008-05-07T12:51:50.000Z',
                )
            ),
            array(
                'volumeId'          => 'vol-42826775',
                'size'              => '40',
                'status'            => 'available',
                'createTime'        => '2008-08-07T11:51:50.000Z',
                'attachmentSet'     => null
            )
        );

        foreach($return as $k => $r) {
            $this->assertSame($arrVolumes[$k], $r);
        }
    }

    /**
     * Tests Zend_Service_Amazon_Ec2_Ebs->detachVolume()
     */
    public function testDetachVolume()
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
                    . "<DetachVolumeResponse xmlns=\"http://ec2.amazonaws.com/doc/2008-12-01/\">\r\n"
                    . "  <volumeId>vol-4d826724</volumeId>\r\n"
                    . "  <instanceId>i-6058a509</instanceId>\r\n"
                    . "  <device>/dev/sdh</device>\r\n"
                    . "  <status>detaching</status>\r\n"
                    . "  <attachTime>2008-05-08T11:51:50.000Z</attachTime>\r\n"
                    . "</DetachVolumeResponse>";
        $this->adapter->setResponse($rawHttpResponse);

        $return = $this->Zend_Service_Amazon_Ec2_Ebs->detachVolume('vol-4d826724');

        $this->assertEquals('vol-4d826724', $return['volumeId']);
        $this->assertEquals('detaching', $return['status']);

    }

}

