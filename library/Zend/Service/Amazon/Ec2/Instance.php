<?php

require_once 'Zend/Service/Amazon/Ec2/Abstract.php';

class Zend_Service_Amazon_Ec2_Instance extends Zend_Service_Amazon_Ec2_Abstract
{

    /**
     * Launches a specified number of Instances.
     *
     * If Amazon EC2 cannot launch the minimum number AMIs you request, no
     * instances launch. If there is insufficient capacity to launch the
     * maximum number of AMIs you request, Amazon EC2 launches as many
     * as possible to satisfy the requested maximum values.
     *
     * Every instance is launched in a security group. If you do not specify
     * a security group at launch, the instances start in your default security group.
     * For more information on creating security groups, see CreateSecurityGroup.
     *
     * An optional instance type can be specified. For information
     * about instance types, see Instance Types.
     *
     * You can provide an optional key pair ID for each image in the launch request
     * (for more information, see CreateKeyPair). All instances that are created
     * from images that use this key pair will have access to the associated public
     * key at boot. You can use this key to provide secure access to an instance of an
     * image on a per-instance basis. Amazon EC2 public images use this feature to
     * provide secure access without passwords.
     *
     * Launching public images without a key pair ID will leave them inaccessible.
     *
     * @param string $imageId                       ID of the AMI with which to launch instances.
     * @param integer $minCount                     Minimum number of instances to launch.
     * @param integer $maxCount                     Maximum number of instances to launch.
     * @param string $keyName                       Name of the key pair with which to launch instances.
     * @param string|array $securityGroup           Names of the security groups with which to associate the instances.
     * @param string $userData                      The user data available to the launched instances. This should not be
     *                                              Base64 encoded.
     * @param string $instanceType                  Specifies the instance type.
     *                                              Options include m1.small, m1.large, m1.xlarge, c1.medium, and c1.xlarge.
     *                                              The default value is m1.small.
     * @param string $placement                     Specifies the availability zone in which to launch the instance(s).
     *                                              By default, Amazon EC2 selects an availability zone for you.
     * @param string $kernelId                      The ID of the kernel with which to launch the instance.
     * @param string $ramdiskId                     The ID of the RAM disk with which to launch the instance.
     * @param string $blockDeviceVirtualName        Specifies the virtual name to map to the corresponding device name. For example: instancestore0
     * @param string $blockDeviceName               Specifies the device to which you are mapping a virtual name. For example: sdb
     */
    public function run($imageId, $minCount = 1, $maxCount = 1, $keyName = null, $securityGroup = null, $userData = null, $instanceType = 'm1.small', $placement = null, $kernelId = null, $ramdiskId = null, $blockDeviceVirtualName = null, $blockDeviceName = null)
    {
        $params = array();
        $params['Action'] = 'RunInstances';
        $params['ImageId'] = $imageId;
        $params['MinCount'] = $minCount;
        $params['MaxCount'] = $maxCount;

        if($keyName) {
            $params['KeyName'] = $keyName;
        }

        if(is_array($securityGroup) && !empty($securityGroup)) {
            foreach($securityGroup as $k=>$name) {
                $params['SecurityGroup.' . ($k+1)] = $name;
            }
        } elseif($securityGroup) {
            $params['SecurityGroup.1'] = $securityGroup;
        }

        if($userData) {
            $params['UserData'] = base64_encode($userData);
        }

        if($instanceType) {
            $params['InstanceType'] = $instanceType;
        }

        if($placement) {
            $params['Placement.AvailabilityZone'] = $placement;
        }

        if($kernelId) {
            $params['KernelId'] = $kernelId;
        }

        if($ramdiskId) {
            $params['RamdiskId'] = $ramdiskId;
        }

        if($blockDeviceVirtualName && $blockDeviceName) {
            $params['BlockDeviceMapping.n.VirtualName'] = $blockDeviceVirtualName;
            $params['BlockDeviceMapping.n.DeviceName'] = $blockDeviceName;
        }

        $response = $this->sendRequest($params);
        $xpath = $response->getXPath();

        $return = array();

        $return['reservationId'] = $xpath->evaluate('string(//ec2:reservationId/text())');
        $return['ownerId'] = $xpath->evaluate('string(//ec2:ownerId/text())');

        $gs = $xpath->query('//ec2:groupSet/ec2:item');
        foreach($gs as $gs_node) {
            $return['groupSet'][] = $xpath->evaluate('string(ec2:groupId/text())', $gs_node);
            unset($gs_node);
        }
        unset($gs);

        $is = $xpath->query('//ec2:instancesSet/ec2:item');
        foreach($is as $is_node) {
            $item = array();

            $item['instanceId'] = $xpath->evaluate('string(ec2:instanceId/text())', $is_node);
            $item['imageId'] = $xpath->evaluate('string(ec2:imageId/text())', $is_node);
            $item['instanceState']['code'] = $xpath->evaluate('string(ec2:instanceState/ec2:code/text())', $is_node);
            $item['instanceState']['name'] = $xpath->evaluate('string(ec2:instanceState/ec2:name/text())', $is_node);
            $item['privateDnsName'] = $xpath->evaluate('string(ec2:privateDnsName/text())', $is_node);
            $item['dnsName'] = $xpath->evaluate('string(ec2:dnsName/text())', $is_node);
            $item['keyName'] = $xpath->evaluate('string(ec2:keyName/text())', $is_node);
            $item['instanceType'] = $xpath->evaluate('string(ec2:instanceType/text())', $is_node);
            $item['amiLaunchIndex'] = $xpath->evaluate('string(ec2:amiLaunchIndex/text())', $is_node);
            $item['launchTime'] = $xpath->evaluate('string(ec2:launchTime/text())', $is_node);
            $item['availabilityZone'] = $xpath->evaluate('string(ec2:placement/ec2:availabilityZone/text())', $is_node);

            $return['instances'][] = $item;
            unset($item);
            unset($is_node);
        }
        unset($is);

        return $return;

    }

    /**
     * Returns information about instances that you own.
     *
     * If you specify one or more instance IDs, Amazon EC2 returns information
     * for those instances. If you do not specify instance IDs, Amazon EC2
     * returns information for all relevant instances. If you specify an invalid
     * instance ID, a fault is returned. If you specify an instance that you do
     * not own, it will not be included in the returned results.
     *
     * Recently terminated instances might appear in the returned results.
     * This interval is usually less than one hour.
     *
     * @param string|array $instaceId       Set of instances IDs of which to get the status.
     */
    public function describe($instanceId)
    {
        $params = array();
        $params['Action'] = 'DescribeInstances';

        if(is_array($instanceId) && !empty($instanceId)) {
            foreach($instanceId as $k=>$name) {
                $params['InstanceId.' . ($k+1)] = $name;
            }
        } elseif($instanceId) {
            $params['InstanceId.1'] = $instanceId;
        }

        $response = $this->sendRequest($params);
        $xpath = $response->getXPath();

        $nodes = $xpath->query('//ec2:reservationSet/ec2:item');

        $return = array();

        foreach($nodes as $node) {
            $return['reservationId'] = $xpath->evaluate('string(ec2:reservationId/text())', $node);
            $return['ownerId'] = $xpath->evaluate('string(ec2:ownerId/text())', $node);

            $gs = $xpath->query('ec2:groupSet/ec2:item', $node);
            foreach($gs as $gs_node) {
                $return['groupSet'][] = $xpath->evaluate('string(ec2:groupId/text())', $gs_node);
                unset($gs_node);
            }
            unset($gs);

            $is = $xpath->query('ec2:instancesSet/ec2:item', $node);
            foreach($is as $is_node) {
                $item = array();

                $item['instanceId'] = $xpath->evaluate('string(ec2:instanceId/text())', $is_node);
                $item['imageId'] = $xpath->evaluate('string(ec2:imageId/text())', $is_node);
                $item['instanceState']['code'] = $xpath->evaluate('string(ec2:instanceState/ec2:code/text())', $is_node);
                $item['instanceState']['name'] = $xpath->evaluate('string(ec2:instanceState/ec2:name/text())', $is_node);
                $item['privateDnsName'] = $xpath->evaluate('string(ec2:privateDnsName/text())', $is_node);
                $item['dnsName'] = $xpath->evaluate('string(ec2:dnsName/text())', $is_node);
                $item['keyName'] = $xpath->evaluate('string(ec2:keyName/text())', $is_node);
                $item['productCode'] = $xpath->evaluate('string(ec2:productCodesSet/ec2:item/ec2:productCode/text())', $is_node);
                $item['instanceType'] = $xpath->evaluate('string(ec2:instanceType/text())', $is_node);
                $item['launchTime'] = $xpath->evaluate('string(ec2:launchTime/text())', $is_node);
                $item['availabilityZone'] = $xpath->evaluate('string(ec2:placement/ec2:availabilityZone/text())', $is_node);
                $item['kernelId'] = $xpath->evaluate('string(ec2:kernelId/text())', $is_node);
                $item['ramediskId'] = $xpath->evaluate('string(ec2:ramediskId/text())', $is_node);

                $return['instances'][] = $item;
                unset($item);
                unset($is_node);
            }
            unset($is);
        }

        return $return;
    }

    /**
     * Shuts down one or more instances. This operation is idempotent; if you terminate
     * an instance more than once, each call will succeed.
     *
     * Terminated instances will remain visible after termination (approximately one hour).
     *
     * @param string|array $instanceId      One or more instance IDs returned.
     * @return array
     */
    public function terminate($instanceId)
    {
        $params = array();
        $params['Action'] = 'TerminateInstances';

        if(is_array($instanceId) && !empty($instanceId)) {
            foreach($instanceId as $k=>$name) {
                $params['InstanceId.' . ($k+1)] = $name;
            }
        } elseif($instanceId) {
            $params['InstanceId.1'] = $instanceId;
        }

        $response = $this->sendRequest($params);
        $xpath = $response->getXPath();

        $nodes = $xpath->query('//ec2:instancesSet/ec2:item');

        $return = array();
        foreach($nodes as $node) {
            $item = array();

            $item['instanceId'] = $xpath->evaluate('string(ec2:instanceId/text())', $node);
            $item['shutdownState']['code'] = $xpath->evaluate('string(ec2:shutdownState/ec2:code/text())', $node);
            $item['shutdownState']['name'] = $xpath->evaluate('string(ec2:shutdownState/ec2:name/text())', $node);
            $item['previousState']['code'] = $xpath->evaluate('string(ec2:previousState/ec2:code/text())', $node);
            $item['previousState']['name'] = $xpath->evaluate('string(ec2:previousState/ec2:name/text())', $node);

            $return[] = $item;
            unset($item);
        }

        return $return;
    }

    /**
     * Returns true if the specified product code is attached to the specified instance.
     * The operation returns false if the product code is not attached to the instance.
     *
     * The ConfirmProductInstance operation can only be executed by the owner of the AMI.
     * This feature is useful when an AMI owner is providing support and wants to
     * verify whether a user's instance is eligible.
     *
     * @param string $productCode           The product code to confirm.
     * @param string $instanceId            The instance for which to confirm the product code.
     * @return array|boolean                An array if the product code is attached to the instance, false if it is not.
     */
    public function confirmProduct($productCode, $instanceId)
    {
        $params = array();
        $params['Action'] = 'ConfirmProductInstance';
        $params['ProductCode'] = $productCode;
        $params['InstanceId'] = $instanceId;

        $response = $this->sendRequest($params);
        $xpath = $response->getXPath();

        $result = $xpath->evaluate('string(//ec2:result/text())');

        if($result === "true") {
            $return['result'] = true;
            $return['ownerId'] = $xpath->evaluate('string(//ec2:ownerId/text())');

            return $return;
        }

        return false;
    }

}
