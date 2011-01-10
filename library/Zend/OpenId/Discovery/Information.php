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
namespace Zend\OpenId\Discovery;
use Zend\OpenId;

/**
 * Container holding discovered info. 
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
interface Information
    extends \Serializable
{
    /**
     * Discoverable service types
     */
    const OPENID_10 = "http://openid.net/signon/1.0";
    const OPENID_11 = "http://openid.net/signon/1.1";
    const OPENID_20 = "http://specs.openid.net/auth/2.0/signon";
    const OPENID_20_OP = "http://specs.openid.net/auth/2.0/server";
    const OPENID_20_RP = "http://specs.openid.net/auth/2.0/return_to";

    /**
     * Set OP Endpoint URL
     *
     * @param string $url OP Endpoint URL
     * @return \Zend\OpenId\Discovery\Information
     */
    public function setEndpointUrl($url = null);

    /**
     * Get OP Endpoint URL
     *
     * @return string
     */
    public function getEndpointUrl();

    /**
     * OpenID protocol version discoverd at given Identifier
     *
     * @param string $version Protocol version
     * @return \Zend\OpenId\Discovery\Information
     */
    public function setProtocolVersion($version = null);

    /**
     * Get protocol version
     *
     * @return string
     */
    public function getProtocolVersion();

    /**
     * Claimed Identifier.
     *
     * If what user provided is not OP Identifier, then Claimed Identifier is also 
     * returned as a part of discovered info.
     *
     * @param \Zend\OpenId\Identifier $id Claimed Identifier
     * @return \Zend\OpenId\Discovery\Information
     */
    public function setClaimedIdentifier(\Zend\OpenId\Identifier $id = null);

    /**
     * Get Claimed Identifier (if applicable)
     *
     * @return \Zend\OpenId\Identifier
     */
    public function getClaimedIdentifier();

    /**
     * OP-Local Identifier or Delegate. 
     *
     * If what user provided is not OP Identifier, then OP-Local Identifier is also 
     * returned as a part of discovered info.
     *
     * @param \Zend\OpenId\Identifier $id OP-Local Identifier
     * @return \Zend\OpenId\Discovery\Information
     */
    public function setLocalIdentifier(\Zend\OpenId\Identifier $id = null);

    /**
     * Get OP-Local Identifier (if applicable)
     *
     * @return \Zend\OpenId\Identifier
     */
    public function getLocalIdentifier();

    /**
     * For reference: Identifier on which discovery was performed is preserved
     *
     * @param \Zend\OpenId\Identifier $id Original Identifier
     * @return \Zend\OpenId\Discovery\Information
     */
    public function setSuppliedIdentifier(\Zend\OpenId\Identifier $id = null);

    /**
     * Get original identifier used for discovery
     *
     * @return \Zend\OpenId\Identifier
     */
    public function getSuppliedIdentifier();
}
