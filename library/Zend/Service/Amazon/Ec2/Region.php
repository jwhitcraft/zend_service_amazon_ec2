<?php

require_once 'Zend/Service/Amazon/Ec2/Abstract.php';

class Zend_Service_Amazon_Ec2_Region extends Zend_Service_Amazon_Ec2_Abstract
{

    /**
     * Describes availability zones that are currently available to the account
     * and their states.
     *
     * @param string|array $region              Name of an region.
     * @return array                            An array that contains all the return items.  Keys: regionName and regionUrl.
     */
    public function describe($region = null)
    {
        $params = array();
        $params['Action'] = 'DescribeRegions';

        if(is_array($region) && !empty($region)) {
            foreach($region as $k=>$name) {
                $params['Region.' . ($k+1)] = $name;
            }
        } elseif($region) {
            $params['Region.1'] = $region;
        }

        $response = $this->sendRequest($params);

        $xpath  = $response->getXPath();
        $nodes  = $xpath->query('//ec2:item');

        $return = array();
        foreach ($nodes as $k => $node) {
            $item = array();
            $item['regionName']   = $xpath->evaluate('string(ec2:regionName/text())', $node);
            $item['regionUrl']  = $xpath->evaluate('string(ec2:regionUrl/text())', $node);

            $return[] = $item;
            unset($item);
        }

        return $return;
    }
}
