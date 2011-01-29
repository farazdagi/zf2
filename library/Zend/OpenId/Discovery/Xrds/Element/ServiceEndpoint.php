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
namespace Zend\OpenId\Discovery\Xrds\Element;
use Zend\OpenId;

/**
 * Container to encapsulate resource descriptor's (XRD) service endpoint
 *
 * From Yadis 1.0 Specs:
 * <xs:element name="Service">
 *  <xs:complexType>
 *      <xs:sequence>
 *          <xs:element ref="xrd:ProviderID" minOccurs="0"/>
 *          <xs:element ref="xrd:Type" minOccurs="0" maxOccurs="unbounded"/>
 *          <xs:element ref="xrd:Path" minOccurs="0" maxOccurs="unbounded"/>
 *          <xs:element ref="xrd:MediaType" minOccurs="0" maxOccurs="unbounded"/>
 *          <xs:element ref="xrd:URI" minOccurs="0" maxOccurs="unbounded"/>
 *          <xs:group ref="xrd:otherelement" minOccurs="0" maxOccurs="unbounded"/>
 *      </xs:sequence>
 *      <xs:attributeGroup ref="xrd:priorityAttrGrp"/>
 *      <xs:attributeGroup ref="xrd:otherattribute"/>
 *  </xs:complexType>
 * <xs:element>
 *
 * From XRI 2.0 Resolution Specs:
 * <xs:element name="Service"> 
 *  <xs:complexType>
 *      <xs:sequence>
 *          <xs:element ref="xrid:Type" minOccurs="0"/>
 *          <xs:group ref="xrid:URI" maxOccurs="unbounded"/>
 *          <xs:element ref="xrid:MediaType" minOccurs="0" maxOccurs="unbounded"/>
 *          <xs:group ref="xrid:otherelement" minOccurs="0" maxOccurs="unbounded"/>
 *      </xs:sequence>
 *      <xs:attributeGroup ref="xrid:otherattribute"/>
 *  </xs:complexType>
 * </xs:element>
 *
 * @category   Zend
 * @package    Zend_OpenId
 * @subpackage Zend_OpenId_Discovery
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
interface ServiceEndpoint
{
    /**
     * Add service type
     *
     * @param string $type Type of service being described
     *
     * @return \Zend\OpenId\Discovery\Xrds\Element\ServiceEndpoint
     */
    public function addType($type);

    /**
     * Remove specified service type
     *
     * @param string $type Type of service being described
     *
     * @return \Zend\OpenId\Discovery\Xrds\Element\ServiceEndpoint
     */
    public function removeType($type);

    /**
     * Check if specified type is found in current service stack
     *
     * @param stirng $type Service type to ckeck
     *
     * @return boolean
     */
    public function hasType($type);

    /**
     * Get all added service types
     *
     * @return array
     */
    public function getTypes();

    /**
     * Set several types at once
     *
     * @param array $list Array of strings
     *
     * @return \Zend\OpenId\Discovery\Xrds\Element\ServiceEndpoint
     */
    public function setTypes($list);

    /**
     * Add transport-level URI where the service described may be accessed.
     *
     * @param string $uri Location where service may be accessed
     *
     * @return \Zend\OpenId\Discovery\Xrds\Element\ServiceEndpoint
     */
    public function addUri($uri);

    /**
     * Remove specified URI
     *
     * @param string $uri Location where service may be accessed
     *
     * @return \Zend\OpenId\Discovery\Xrds\Element\ServiceEndpoint
     */
    public function removeUri($uri);

    /**
     * Fetch (by key) URI at which service may be accessed 
     *
     * @param int $key Zero based index of element
     *
     * @return string
     */
    public function getUri($key = 0);

    /**
     * Get all URIs registered as service's locations
     *
     * @return array
     */
    public function getUris();

    /**
     * Set several URIs at once
     *
     * @param array $list Array of service URIs
     *
     * @return \Zend\OpenId\Discovery\Xrds\Element\ServiceEndpoint
     */
    public function setUris($list);

    /**
     * Reset object inernal state
     *
     * @return \Zend\OpenId\Discovery\Xrds\Element\ServiceEndpoint
     */
    public function reset();
}
