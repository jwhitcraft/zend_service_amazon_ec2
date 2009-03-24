<?php

set_include_path(
        dirname(__FILE__) . '/../../../../../library'
        . PATH_SEPARATOR . get_include_path()
    );

require_once 'Zend/Service/Amazon/Ec2/Image.php';

require_once 'PHPUnit/Framework/TestCase.php';

/** Zend_Http_Client */
require_once 'Zend/Http/Client.php';

/** Zend_Http_Client_Adapter_Test */
require_once 'Zend/Http/Client/Adapter/Test.php';


/**
 * Zend_Service_Amazon_Ec2_Image test case.
 */
class ImageTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Zend_Service_Amazon_Ec2_Image
     */
    private $Zend_Service_Amazon_Ec2_Image;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->Zend_Service_Amazon_Ec2_Image = new Zend_Service_Amazon_Ec2_Image('access_key', 'secret_access_key');

        $adapter = new Zend_Http_Client_Adapter_Test();
        $client = new Zend_Http_Client(null, array(
            'adapter' => $adapter
        ));
        $this->adapter = $adapter;
        Zend_Service_Amazon_Ec2_Image::setHttpClient($client);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        $this->Zend_Service_Amazon_Ec2_Image = null;

        parent::tearDown();
    }

    /**
     * Tests Zend_Service_Amazon_Ec2_Image->deregister()
     */
    public function testDeregister()
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
                    . "<DeregisterImageResponse xmlns=\"http://ec2.amazonaws.com/doc/2008-12-01/\">\r\n"
                    . "  <return>true</return>\r\n"
                    . "</DeregisterImageResponse>\r\n";
        $this->adapter->setResponse($rawHttpResponse);

        $return = $this->Zend_Service_Amazon_Ec2_Image->deregister('ami-61a54008');

        $this->assertTrue($return);

    }

    /**
     * Tests Zend_Service_Amazon_Ec2_Image->describe()
     */
    public function testDescribe()
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
                    . "<DescribeImagesResponse xmlns=\"http://ec2.amazonaws.com/doc/2008-12-01/\">\r\n"
                    . "  <imagesSet>\r\n"
                    . "    <item>\r\n"
                    . "      <imageId>ami-be3adfd7</imageId>\r\n"
                    . "      <imageLocation>ec2-public-images/fedora-8-i386-base-v1.04.manifest.xml</imageLocation>\r\n"
                    . "      <imageState>available</imageState>\r\n"
                    . "      <imageOwnerId>206029621532</imageOwnerId>\r\n"
                    . "      <isPublic>false</isPublic>\r\n"
                    . "      <architecture>i386</architecture>\r\n"
                    . "      <imageType>machine</imageType>\r\n"
                    . "      <kernelId>aki-4438dd2d</kernelId>\r\n"
                    . "      <ramdiskId>ari-4538dd2c</ramdiskId>\r\n"
                    . "    </item>\r\n"
                    . "  </imagesSet>\r\n"
                    . "</DescribeImagesResponse>";
        $this->adapter->setResponse($rawHttpResponse);

        $return = $this->Zend_Service_Amazon_Ec2_Image->describe('ami-be3adfd7');

        $this->assertEquals('ami-be3adfd7', $return[0]['imageId']);
        $this->assertEquals('ec2-public-images/fedora-8-i386-base-v1.04.manifest.xml', $return[0]['imageLocation']);

    }

    /**
     * Tests Zend_Service_Amazon_Ec2_Image->describeAttribute()
     */
    public function testDescribeAttributeLaunchPermission()
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
                    . "<DescribeImageAttributeResponse xmlns=\"http://ec2.amazonaws.com/doc/2008-12-01/\">\r\n"
                    . "  <imageId>ami-61a54008</imageId>\r\n"
                    . "  <launchPermission>\r\n"
                    . "    <item>\r\n"
                    . "      <userId>495219933132</userId>\r\n"
                    . "    </item>\r\n"
                    . "  </launchPermission>\r\n"
                    . "</DescribeImageAttributeResponse>\r\n";
        $this->adapter->setResponse($rawHttpResponse);

        $return = $this->Zend_Service_Amazon_Ec2_Image->describeAttribute('ami-61a54008', 'launchPermission');

        $this->assertEquals('ami-61a54008', $return['imageId']);
        $this->assertEquals('495219933132', $return['launchPermission'][0]);
    }

    public function testDescribeAttributeProductCodes()
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
                    . "<DescribeImageAttributeResponse xmlns=\"http://ec2.amazonaws.com/doc/2008-12-01/\">\r\n"
                    . "  <imageId>ami-61a54008</imageId>\r\n"
                    . "  <productCodes>\r\n"
                    . "    <item>\r\n"
                    . "      <productCode>774F4FF8</productCode>\r\n"
                    . "    </item>\r\n"
                    . "  </productCodes>\r\n"
                    . "</DescribeImageAttributeResponse>\r\n";
        $this->adapter->setResponse($rawHttpResponse);

        $return = $this->Zend_Service_Amazon_Ec2_Image->describeAttribute('ami-61a54008', 'productCodes');

        $this->assertEquals('ami-61a54008', $return['imageId']);
        $this->assertEquals('774F4FF8', $return['productCodes'][0]);
    }

    /**
     * Tests Zend_Service_Amazon_Ec2_Image->modifyAttribute()
     */
    public function testModifyAttributeSingleLaunchPermission()
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
                    . "<ModifyImageAttributeResponse xmlns=\"http://ec2.amazonaws.com/doc/2008-12-01/\">\r\n"
                    . "  <return>true</return>\r\n"
                    . "</ModifyImageAttributeResponse>\r\n";
        $this->adapter->setResponse($rawHttpResponse);

        $return = $this->Zend_Service_Amazon_Ec2_Image->modifyAttribute('ami-61a54008', 'launchPermission', 'add', '495219933132', 'all');
        $this->assertTrue($return);
    }

    public function testModifyAttributeMultipleLaunchPermission()
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
                    . "<ModifyImageAttributeResponse xmlns=\"http://ec2.amazonaws.com/doc/2008-12-01/\">\r\n"
                    . "  <return>true</return>\r\n"
                    . "</ModifyImageAttributeResponse>\r\n";
        $this->adapter->setResponse($rawHttpResponse);

        $return = $this->Zend_Service_Amazon_Ec2_Image->modifyAttribute('ami-61a54008', 'launchPermission', 'add', array('495219933132', '495219933133'), array('all', 'all'));
        $this->assertTrue($return);
    }

    public function testModifyAttributeThrowsExceptionOnInvalidAttribute()
    {
        try {
            $return = $this->Zend_Service_Amazon_Ec2_Image->modifyAttribute('ami-61a54008', 'invalidPermission', 'add', '495219933132', 'all');
            $this->fail('An exception should be throw if you are modifying an invalid attirubte');
        } catch (Zend_Service_Amazon_Ec2_Exception $zsaee) {}
    }

    public function testModifyAttributeProuctCodes()
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
                    . "<ModifyImageAttributeResponse xmlns=\"http://ec2.amazonaws.com/doc/2008-12-01/\">\r\n"
                    . "  <return>true</return>\r\n"
                    . "</ModifyImageAttributeResponse>\r\n";
        $this->adapter->setResponse($rawHttpResponse);

        $return = $this->Zend_Service_Amazon_Ec2_Image->modifyAttribute('ami-61a54008', 'productCodes', null, null, null, '774F4FF8');

        $this->assertTrue($return);

    }

    /**
     * Tests Zend_Service_Amazon_Ec2_Image->register()
     */
    public function testRegister()
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
                    . "<RegisterImageResponse xmlns=\"http://ec2.amazonaws.com/doc/2008-12-01/\">\r\n"
                    . "  <imageId>ami-61a54008</imageId>\r\n"
                    . "</RegisterImageResponse>\r\n";
        $this->adapter->setResponse($rawHttpResponse);

        $return = $this->Zend_Service_Amazon_Ec2_Image->register('mybucket-myimage.manifest.xml');

        $this->assertEquals('ami-61a54008', $return);

    }

    /**
     * Tests Zend_Service_Amazon_Ec2_Image->resetAttribute()
     */
    public function testResetAttribute()
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
                    . "<ResetImageAttributeResponse xmlns=\"http://ec2.amazonaws.com/doc/2008-12-01/\">\r\n"
                    . "  <return>true</return>\r\n"
                    . "</ResetImageAttributeResponse>\r\n";
        $this->adapter->setResponse($rawHttpResponse);

        $return = $this->Zend_Service_Amazon_Ec2_Image->resetAttribute('ami-61a54008', 'launchPermission');

        $this->assertTrue($return);

    }

}

