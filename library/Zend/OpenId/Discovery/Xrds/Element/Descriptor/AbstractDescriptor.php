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
 * @package    Zend_OpenId
 * @subpackage Zend_OpenId_Discovery
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * @namespace
 */
namespace Zend\OpenId\Discovery\Xrds\Element\Descriptor;
use Zend\OpenId;

/**
 * Abstract implementation of common methods of container to encapsulate data 
 * from XRD Sequence, which abtracts single XRD element 
 *
 * @category   Zend
 * @package    Zend_OpenId
 * @subpackage Zend_OpenId_Discovery
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class AbstractDescriptor
    implements OpenId\Discovery\Xrds\Element\Descriptor
{
    /**
     * Registrered service endpoints
     *
     * @var of \Zend\OpenId\Discovery\Xrds\Element\ServiceEndpoint elements
     */
    private $services = array();

    /**
     * Append discovered service endpoint
     *
     * @param \Zend\OpenId\Discovery\Xrds\Element\ServiceEndpoint $service Service to append
     *
     * @return \Zend\OpenId\Discovery\Xrds\Element\Descriptor
     */
    public function addService(\Zend\OpenId\Discovery\Xrds\Element\ServiceEndpoint $service)
    {
        $this->services[$service->getHash()] = $service;
        return $this;
    }

    /**
     * Get services registered with descriptor
     *
     * @param mixed $type Service type or types. 
     *                    If null all services are returned. 
     *                    If string then single service is checked. 
     *                    If array then multiple services are checked.
     *
     * @return array Array of \Zend\OpenId\Discovery\Xrds\Element\ServiceEndpoint elements
     */
    public function getServices($type = null)
    {
        if (is_array($type)) {
            $services = array();
            $types = $type;
            foreach ($types as $type) {
                if (is_string($type)) { // to avoid endless recursion
                    foreach ($this->getServices($type) as $service) {
                        // add, but filter out duplicates
                        $services[$service->getHash()] = $service;
                    }
                }
            }
            return array_values($services); // to get indexed array
        } else if (is_string($type)) {
            return $this->getServicesByType($type);
        }

        return array_values($this->services);
    }

    /**
     * Get services by URI
     *
     * @param string $needle URI of service endpoint
     *
     * @return array
     */
    private function getServicesByType($needle)
    {
        $services = array();

        foreach ($this->services as $service) {
            if ($service->hasType($needle)) {
                $services[] = $service;
            }
        }

        return $services;
    }
}
