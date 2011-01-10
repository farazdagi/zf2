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
namespace Zend\OpenId\Discovery\Service;
use Zend\OpenId;

/**
 * Default implementation of container holding discovered info. 
 * Refer to Section 7.3.1 of OpenID 2.0 specs for description of items returned
 * upon successfull discovery: 
 * - OP Endpoint URL
 * - Protocol Version
 *
 * If the end user did not enter an OP Identifier, the following information 
 * will also be present: 
 * - Claimed Identifier
 * - OP-Local Identifier
 *
 * @category   Zend
 * @package    Zend_OpenId
 * @subpackage Zend_OpenId_Discovery
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Result 
    implements OpenId\Discovery\Information
{
    /**
     * OP Endpoint URL
     * @var string
     */
    private $endpointUrl;

    /**
     * Protocol version
     * @var string
     */
    private $protocolVersion;

    /**
     * Claimed Identifier
     * @var \Zend\OpenId\Identifier
     */
    private $claimedIdentifier;

    /**
     * OP Local Identifier
     * @var \Zend\OpenId\Identifier
     */
    private $localIdentifier;

    /**
     * Original Identifier used in discovery
     * @var \Zend\OpenId\Identifier
     */
    private $suppliedIdentifier;

    /**
     * Create container
     * @param \Zend\OpenId\Identifier $id User-supplied identifier used in dicovery
     */
    public function __construct($id = null)
    {
        $this->setSuppliedIdentifier($id);
    }
    
    /**
     * Set OP Endpoint URL
     *
     * @param string $url OP Endpoint URL
     * @return \Zend\OpenId\Discovery\Information
     */
    public function setEndpointUrl($url = null)
    {
        $this->endpointUrl = $url;
        return $this;
    }

    /**
     * Get OP Endpoint URL
     *
     * @return string
     */
    public function getEndpointUrl()
    {
        return $this->endpointUrl;
    }

    /**
     * OpenID protocol version discoverd at given Identifier
     *
     * @param string $version Protocol version
     * @return \Zend\OpenId\Discovery\Information
     */
    public function setProtocolVersion($version = null)
    {
        $this->protocolVersion = $version;
        return $this;
    }

    /**
     * Get protocol version
     *
     * @return string
     */
    public function getProtocolVersion()
    {
        return $this->protocolVersion;
    }

    /**
     * Claimed Identifier.
     *
     * If what user provided is not OP Identifier, then Claimed Identifier is also 
     * returned as a part of discovered info.
     *
     * @param \Zend\OpenId\Identifier $id Claimed Identifier
     * @return \Zend\OpenId\Discovery\Information
     */
    public function setClaimedIdentifier(\Zend\OpenId\Identifier $id = null)
    {
        $this->claimedIdentifier = $id;
        return $this;
    }

    /**
     * Get Claimed Identifier (if applicable)
     *
     * @return \Zend\OpenId\Identifier
     */
    public function getClaimedIdentifier()
    {
        return $this->claimedIdentifier;
    }

    /**
     * OP-Local Identifier or Delegate. 
     *
     * If what user provided is not OP Identifier, then OP-Local Identifier is also 
     * returned as a part of discovered info.
     *
     * @param \Zend\OpenId\Identifier $id OP-Local Identifier
     * @return \Zend\OpenId\Discovery\Information
     */
    public function setLocalIdentifier(\Zend\OpenId\Identifier $id = null)
    {
        $this->localIdentifier = $id;
        return $this;
    }

    /**
     * Get OP-Local Identifier (if applicable)
     *
     * @return \Zend\OpenId\Identifier
     */
    public function getLocalIdentifier()
    {
        return $this->localIdentifier;
    }

    /**
     * For reference: Identifier on which discovery was performed is preserved
     *
     * @param \Zend\OpenId\Identifier $id Original Identifier
     * @return \Zend\OpenId\Discovery\Information
     */
    public function setSuppliedIdentifier(\Zend\OpenId\Identifier $id = null)
    {
        $this->userSuppliedIdentifier = $id;
        return $this;
    }

    /**
     * Get original identifier used for discovery
     *
     * @return \Zend\OpenId\Identifier
     */
    public function getSuppliedIdentifier()
    {
        return $this->userSuppliedIdentifier;
    }

    /**
     * Serialize all relevant data
     *
     * @return string
     */
    public function serialize() {
        return serialize(array(
            'endpointUrl'           => $this->getEndpointUrl(),
            'protocolVersion'       => $this->getProtocolVersion(),
            'claimedIdentifier'     => $this->getClaimedIdentifier(),
            'localIdentifier'       => $this->getLocalIdentifier(),
            'suppliedIdentifier'    => $this->getSuppliedIdentifier()
        ));
    }

    /**
     * Unserialize previously serialized object's data
     *
     * @return void
     */
    public function unserialize($data) {
        $data = unserialize($data);
        $this->setEndpointUrl($data['endpointUrl'])
             ->setProtocolVersion($data['protocolVersion'])
             ->setClaimedIdentifier($data['claimedIdentifier'])
             ->setLocalIdentifier($data['localIdentifier'])
             ->setSuppliedIdentifier($data['suppliedIdentifier']);
    }


}
