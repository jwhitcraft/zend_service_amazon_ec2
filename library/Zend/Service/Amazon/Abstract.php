<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Service
 * @subpackage Amazon
 * @copyright  Copyright (c) 2005-2009 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

require_once 'Zend/Service/Abstract.php';

/**
 * Abstract Amazon class that handles the credentials for any of the Web Services that
 * Amazon offers
 *
 * @category   Zend
 * @package    Zend_Service
 * @subpackage Amazon
 * @copyright  Copyright (c) 22005-2009 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
abstract class Zend_Service_Amazon_Abstract extends Zend_Service_Abstract
{
    /**
     * @var string Amazon Access Key
     */
    protected static $defaultAccessKey = null;

    /**
     * @var string Amazon Secret Key
     */
    protected static $defaultSecretKey = null;

    /**
     * @var string Amazon Secret Key
     */
    protected $secretKey;

    /**
     * @var string Amazon Access Key
     */
    protected $accessKey;

    /**
     * Set the keys to use when accessing SQS.
     *
     * @param  string $access_key       Set the default Access Key
     * @param  string $secret_key       Set the default Secret Key
     * @return void
     */
    public static function setKeys($accessKey, $secretKey)
    {
        self::$defaultAccessKey = $accessKey;
        self::$defaultSecretKey = $secretKey;
    }

    /**
     * Create Amazon Sqs client.
     *
     * @param  string $access_key       Override the default Access Key
     * @param  string $secret_key       Override the default Secret Key
     * @return void
     */
    public function __construct($accessKey=null, $secretKey=null)
    {
        if(!$accessKey) {
            $accessKey = self::$defaultAccessKey;
        }
        if(!$secretKey) {
            $secretKey = self::$defaultSecretKey;
        }
        if(!$accessKey || !$secretKey) {
            require_once 'Zend/Service/Amazon/Exception.php';
            throw new Zend_Service_Amazon_Exception("AWS keys were not supplied");
        }
        $this->accessKey = $accessKey;
        $this->secretKey = $secretKey;
    }

    /**
     * Method to fetch the Access Key
     *
     * @return string
     */
    protected function getAccessKey()
    {
        return $this->accessKey;
    }

    /**
     * Method to fetch the Secret AWS Key
     *
     * @return string
     */
    protected function getSecretKey()
    {
        return $this->secretKey;
    }
}