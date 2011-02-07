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
namespace Zend\OpenId\Discovery\Xrds\Element\ServiceEndpoint;
use Zend\OpenId;

/**
 * Default implimentation of common XRD Service functionality
 *
 *
 * @category   Zend
 * @package    Zend_OpenId
 * @subpackage Zend_OpenId_Discovery
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
abstract class AbstractServiceEndpoint
    implements OpenId\Discovery\Xrds\Element\ServiceEndpoint
{ 
    /**
     * Service type
     */
    private $types = array();

    /**
     * Service URIs
     */
    private $uris = array();

    /**
     * Add service type
     *
     * @param string $type Type of service being described
     *
     * @return \Zend\OpenId\Discovery\Xrds\Element\ServiceEndpoint
     */
    public function addType($type)
    {
        $this->types[] = $type;
        return $this;
    }

    /**
     * Remove specified service type
     *
     * @param string $type Type of service being described
     *
     * @return \Zend\OpenId\Discovery\Xrds\Element\ServiceEndpoint
     */
    public function removeType($type)
    {
        $key = array_search($type, $this->types);
        if (false !== $key) {
            unset($this->types[$key]);
        }
        return $this;
    }

    /**
     * Check if specified type is found in current service stack
     *
     * @param stirng $type Service type to ckeck
     *
     * @return boolean
     */
    public function hasType($type)
    {
        $key = array_search($type, $this->types);
        return (boolean)(false !== $key);
    }

    /**
     * Fetch (by key) service type
     *
     * @param int $key Zero based index of element
     *
     * @return string
     */
    public function getType($key = 0)
    {
        if (isset($this->types[$key])) {
            return $this->types[$key];
        }
        return null;
    }

    /**
     * Get all added service types
     *
     * @return array
     */
    public function getTypes()
    {
        return $this->types;
    }

    /**
     * Set several types at once
     *
     * @param array $list Array of strings
     *
     * @return \Zend\OpenId\Discovery\Xrds\Element\ServiceEndpoint
     */
    public function setTypes($list)
    {
        $this->types = $list;
        return $this;
    }

    /**
     * Add transport-level URI where the service described may be accessed.
     *
     * @param string $uri Location where service may be accessed
     *
     * @return \Zend\OpenId\Discovery\Xrds\Element\ServiceEndpoint
     */
    public function addUri($uri)
    {
        $this->uris[] = $uri;
        return $this;
    }

    /**
     * Remove specified URI
     *
     * @param string $uri Location where service may be accessed
     *
     * @return \Zend\OpenId\Discovery\Xrds\Element\ServiceEndpoint
     */
    public function removeUri($uri)
    {
        $key = array_search($uri, $this->uris);
        if (false !== $key) {
            unset($this->uris[$key]);
        }
        return $this;
    }

    /**
     * Fetch (by key) URI at which service may be accessed 
     *
     * @param int $key Zero based index of element
     *
     * @return string
     */
    public function getUri($key = 0)
    {
        if (isset($this->uris[$key])) {
            return $this->uris[$key];
        }
        return null;
    }

    /**
     * Get all URIs registered as service's locations
     *
     * @return array
     */
    public function getUris()
    {
        return $this->uris;
    }

    /**
     * Set several URIs at once
     *
     * @param array $list Array of service URIs
     *
     * @return \Zend\OpenId\Discovery\Xrds\Element\ServiceEndpoint
     */
    public function setUris($list)
    {
        $this->uris = $list;
        return $this;
    }

    /**
     * Reset object inernal state
     *
     * @return \Zend\OpenId\Discovery\Xrds\Element\ServiceEndpoint
     */
    public function reset($type = self::RESET_ALL)
    {
        if ($type & self::RESET_ALL) {
            $this
                ->setTypes(array())
                ->setUris(array());
        } else {
            if ($type & self::RESET_TYPES) {
                $this->setTypes(array());
            }
            if ($type & self::RESET_URIS) {
                $this->setUris(array());
            }
        }
        return $this;
    }

    /**
     * String uniquely identifying the service object
     *
     * @param string $salt Extra string to be used in hashing
     *
     * @return string
     */
    public function getHash($salt = '')
    {
        $hash = $salt;
        foreach ($this->getUris() as $uri) {
            $hash .= $uri;
        }
        foreach ($this->getTypes() as $type) {
            $hash .= $type;
        }

        return md5($hash);
    }

}
