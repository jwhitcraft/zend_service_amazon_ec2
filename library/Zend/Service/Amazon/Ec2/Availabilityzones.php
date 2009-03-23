<?php

require_once 'Zend/Service/Amazon/Ec2/Abstract.php';

class Zend_Service_Amazon_Ec2_Availabilityzones extends Zend_Service_Amazon_Ec2_Abstract
{
    /**
     * Describes availability zones that are currently available to the account
     * and their states.
     *
     * @param string|array $zoneName            Name of an availability zone.
     * @return array                            An array that contains all the return items.  Keys: zoneName and zoneState.
     */
    public function describe($zoneName = null)
    {
        $params = array();
        $params['Action'] = 'DescribeAvailabilityZones';

        if(is_array($zoneName) && !empty($zoneName)) {
            foreach($zoneName as $k=>$name) {
                $params['ZoneName.' . ($k+1)] = $name;
            }
        } elseif($zoneName) {
            $params['ZoneName.1'] = $zoneName;
        }

        $response = $this->sendRequest($params);

        $xpath  = $response->getXPath();
        $nodes  = $xpath->query('//ec2:item');

        $return = array();
        foreach ($nodes as $k => $node) {
            $item = array();
            $item['zoneName']   = $xpath->evaluate('string(ec2:zoneName/text())', $node);
            $item['zoneState']  = $xpath->evaluate('string(ec2:zoneState/text())', $node);

            $return[] = $item;
            unset($item);
        }

        return $return;
    }
}
