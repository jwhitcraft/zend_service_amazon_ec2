<?php

require_once 'Zend/Service/Amazon/Abstract.php';

require_once 'Zend/Service/Amazon/Ec2/Response.php';

abstract class Zend_Service_Amazon_Ec2_Abstract extends Zend_Service_Amazon_Abstract
{
    /**
     * The HTTP query server
     */
    const EC2_ENDPOINT = 'ec2.amazonaws.com';

    /**
     * The API version to use
     */
    const EC2_API_VERSION = '2008-12-01';

    /**
     * Legacy parameter required by Ec2
     */
    const EC2_SIGNATURE_VERSION = '1';

    /**
     * Period after which HTTP request will timeout in seconds
     */
    const HTTP_TIMEOUT = 10;

    /**
     * Sends a HTTP request to the queue service using Zend_Http_Client
     *
     * @param array $params         List of parameters to send with the request
     * @return Zend_Service_Amazon_Ec2_Response
     * @throws Zend_Service_Amazon_Ec2_Exception
     */
    protected function sendRequest(array $params = array())
    {
        $url = 'http://' . self::EC2_ENDPOINT . '/';

        $params = $this->addRequiredParameters($params);

        try {
            /* @var $request Zend_Http_Client */
            $request = self::getHttpClient();

            $request->setConfig(array(
                'timeout' => self::HTTP_TIMEOUT
            ));

            $request->setUri($url);
            $request->setMethod(Zend_Http_Client::POST);
            $request->setParameterPost($params);

            $httpResponse = $request->request();


        } catch (Zend_Http_Client_Exception $zhce) {
            $message = 'Error in request to AWS service: ' . $zhce->getMessage();
            throw new Zend_Service_Amazon_Ec2_Exception($message, $zhce->getCode());
        }

        $response = new Zend_Service_Amazon_Ec2_Response($httpResponse);
        $this->checkForErrors($response);

        return $response;
    }

    /**
     * Adds required authentication and version parameters to an array of
     * parameters
     *
     * The required parameters are:
     * - AWSAccessKey
     * - SignatureVersion
     * - Timestamp
     * - Version and
     * - Signature
     *
     * If a required parameter is already set in the <tt>$parameters</tt> array,
     * it is overwritten.
     *
     * @param array $parameters the array to which to add the required
     *                          parameters.
     *
     * @return array
     */
    protected function addRequiredParameters(array $parameters)
    {
        $parameters['AWSAccessKeyId']   = $this->getAccessKey();
        $parameters['SignatureVersion'] = self::EC2_SIGNATURE_VERSION;
        $parameters['Timestamp']        = gmdate('c');
        $parameters['Version']          = self::EC2_API_VERSION;
        $parameters['Signature']        = $this->signParameters($parameters);

        return $parameters;
    }

    /**
     * Computes the RFC 2104-compliant HMAC signature for request parameters
     *
     * This implements the Amazon Web Services signature, as per the following
     * specification:
     *
     * 1. Sort all request parameters (including <tt>SignatureVersion</tt> and
     *    excluding <tt>Signature</tt>, the value of which is being created),
     *    ignoring case.
     *
     * 2. Iterate over the sorted list and append the parameter name (in its
     *    original case) and then its value. Do not URL-encode the parameter
     *    values before constructing this string. Do not use any separator
     *    characters when appending strings.
     *
     * @param array  $parameters the parameters for which to get the signature.
     * @param string $secretKey  the secret key to use to sign the parameters.
     *
     * @return string the signed data.
     */
    protected function signParameters(array $paramaters)
    {
        $data = '';

        uksort($paramaters, 'strcasecmp');
        unset($paramaters['Signature']);

        foreach($paramaters as $key => $value) {
            $data .= $key . $value;
        }

        require_once 'Zend/Crypt/Hmac.php';
        $hmac = Zend_Crypt_Hmac::compute($this->getSecretKey(), 'SHA1', $data, Zend_Crypt_Hmac::BINARY);

        return base64_encode($hmac);
    }

    /**
     * Checks for errors responses from Amazon
     *
     * @param Zend_Service_Amazon_Ec2_Response $response the response object to
     *                                                   check.
     *
     * @return void
     *
     * @throws Zend_Service_Amazon_Ec2_Exception if one or more errors are
     *         returned from Amazon.
     */
    private function checkForErrors(Zend_Service_Amazon_Ec2_Response $response)
    {
        $xpath = new DOMXPath($response->getDocument());
        $list  = $xpath->query('//Error');
        if ($list->length > 0) {
            $node    = $list->item(0);
            $code    = $xpath->evaluate('string(Code/text())', $node);
            $message = $xpath->evaluate('string(Message/text())', $node);
            require_once 'Zend/Service/Amazon/Ec2/Exception.php';
            throw new Zend_Service_Amazon_Ec2_Exception($message, 0, $code);
        }

    }
}