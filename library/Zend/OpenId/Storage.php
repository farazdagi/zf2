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
     * @param int $timestamp
     * @return \Zend\OpenId\Storage
     */
    public function cleanupAssociations($timestamp);

    /**
     * Remove all associations
     *
     * @return \Zend\OpenId\Storage
     */
    public function resetAssociations();

    /**
     * Store information discovered for Identifier
     *
     * @param \Zend\OpenId\Indentifier Identifier for which discovery is performed
     * @param \Zend\OpenId\Discovery\Information $disoveryInfo Container with discovered info
     * @param \Zend\OpenId\Storage\Expiration $expirationInfo When to invalidate data
     *
     * @return \Zend\OpenId\Storage
     */
    public function addDiscoveryInformation(
        \Zend\OpenId\Identifier $id,
        \Zend\OpenId\Discovery\Information $discoveryInfo,
        \Zend\OpenId\Storage\Expiration $expirationInfo = null);

    /**
     * Get information discovered for Identifier
     *
     * @param \Zend\OpenId\Identifier $id Normalized Identifier used in discovery
     *
     * @return \Zend\OpenId\Discovery\Information
     */
    public function getDiscoveryInformation(\Zend\OpenId\Identifier $id);

    /**
     * Remove cached information discovered for Identifier
     *
     * @param \Zend\OpenId\Identifier $id Normalized Identifier used in discovery
     *
     * @return \Zend\OpenId\Storage
     */
    public function removeDiscoveryInformation(\Zend\OpenId\Identifier $id);

    /**
     * Remove expired discovery data from the cache
     *
     * @param int $timestamp
     * @return \Zend\OpenId\Storage
     */
    public function cleanupDiscoveryInformation($timestamp);

    /**
     * Remove all discovery data from the cache
     *
     * @return \Zend\OpenId\Storage
     */
    public function resetDiscoveryInformation();

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
     * Cleanup all expired nonce data
     *
     * @param int $timestamp
     * @return \Zend\OpenId\Storage
     */
    public function cleanupNonces($timestamp);

    /**
     * Remove all nonce data
     *
     * @return \Zend\OpenId\Storage
     */
    public function resetNonces();

    /**
     * Cleanup all expired data
     *
     * Internally cleanupAssociations(), cleanupNonces(), and 
     * cleanupDiscoveryInfo() are called
     *
     * @return \Zend\OpenId\Storage
     */
    public function cleanup();

    /**
     * Reset the storage to its initial state
     *
     * Internally resetAssociations(), resetNonces(), and 
     * resetDiscoveryInforomation() called
     *
     * @return \Zend\OpenId\Storage
     */
    public function reset();
}
