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
 * @subpackage Zend_OpenId_Storage
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

namespace Zend\OpenId;

/**
 * Interface for state persistence layer during OpenID requests
 *
 * @category   Zend
 * @package    Zend_OpenId
 * @subpackage Zend_OpenId_Storage
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
interface Storage
{
    /**
     * Store information about association
     *
     * @param \Zend\OpenId\Association $associtation Association instance
     *
     * @return \Zend\OpenId\Storage
     */
    public function addAssociation(\Zend\OpenId\Association $associtation);

    /**
     * Get information about association identified by $url
     *
     * @param string $url OP Endpoint URL
     * @param string $handle Association handle (if any)
     *
     * @return \Zend\OpenId\Association
     */
    public function getAssociation($url, $handle = null);

    /**
     * Remove association identified by $url
     *
     * @param string $url OP Endpoint URL
     *
     * @return \Zend\OpenId\Storage
     */
    public function removeAssociation($url);

    /**
     * Remove all expired associations
     *
     * @return \Zend\OpenId\Storage
     */
    public function cleanupAssociations();

    /**
     * Store information discovered for $identifier
     *
     * @param \Zend\OpenId\DiscoveryInfo DiscoveryInfo instance
     *
     * @return \Zend\OpenId\Storage
     */
    public function addDiscoveryInfo($discoveryInfo);

    /**
     * Get information discovered for $identifier
     *
     * @param string $identifier Normalized Identifier used in discovery
     *
     * @return \Zend\OpenId\DiscoveryInfo
     */
    public function getDiscoveryInfo($identifier);

    /**
     * Remove cached information discovered for $identifier
     *
     * @param string $identifier Normalized Identifier used in discovery
     *
     * @return \Zend\OpenId\Storage
     */
    public function removeDiscoveryInfo($identifier);

    /**
     * Remove expired discovery data from the cache
     *
     * @return \Zend\OpenId\Storage
     */
    public function cleanupDiscoveryInfo();

    /**
     * Associate OP Endpoint URL with nonce value to prevent replay attacks
     *
     * @param string $url OP Endpoint URL
     * @param \Zend\OpenId\Nonce $nonce Response nonce returned by OP
     *
     * @return \Zend\OpenId\Storage
     */
    public function addNonce($nonce);

    /**
     * Remove associated nonce
     *
     * @param string $url OP Endpoint URL
     *
     * @return \Zend\OpenId\Storage
     */
    public function removeNonce($url);

    /**
     * Cleanup all exprired nonce data
     *
     * @param int $timestamp
     * @return \Zend\OpenId\Storage
     */
    public function cleanupNonces();

    /**
     * Reset the storage to its initial state
     *
     * Internally cleanupAssociations(), cleanupNonces(), and 
     * cleanupDiscoveryInfo() are called
     *
     * @return \Zend\OpenId\Storage
     */
    public function cleanup();
}
