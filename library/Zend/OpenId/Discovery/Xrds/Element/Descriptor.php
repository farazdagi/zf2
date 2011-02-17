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
 * Container to encapsulate data of single Resourse Descriptor, abtracts single XRD element 
 *
 * From Yadis 1.0 Specs:
 * <xs:element name="XRD">
 *  <xs:complexType>
 *      <xs:sequence>
 *          <xs:element ref="xrd:Query" minOccurs="0"/>
 *          <xs:element ref="xrd:Status" minOccurs="0"/>
 *          <xs:element ref="xrd:Expires" minOccurs="0"/>
 *          <xs:element ref="xrd:ProviderID" minOccurs="0"/>
 *          <xs:element ref="xrd:LocalID" minOccurs="0" maxOccurs="unbounded"/>
 *          <xs:element ref="xrd:CanonicalID" minOccurs="0" maxOccurs="unbounded"/>
 *          <xs:element ref="xrd:Ref" minOccurs="0" maxOccurs="unbounded"/>
 *          <xs:element ref="xrd:Service" minOccurs="0" maxOccurs="unbounded"/>
 *          <xs:group ref="xrd:otherelement" minOccurs="0" maxOccurs="unbounded"/>
 *      </xs:sequence>
 *      <xs:attribute name="id" type="xs:ID"/>
 *      <xs:attribute name="idref" type="xs:IDREF" use="optional"/>
 *      <xs:attribute name="version" type="xs:string" use="optional" fixed="2.0"/>
 *      <xs:attributeGroup ref="xrd:otherattribute"/>
 *  </xs:complexType>
 * </xs:element>
 *
 * From XRI 2.0 Resolution Specs (Community Specs 01):
 * <xs:element name="XRD">
 *     <xs:complexType>
 *         <xs:sequence>
 *             <xs:element ref="xrd:Query" minOccurs="0"/>
 *             <xs:element ref="xrd:Status" minOccurs="0"/>
 *             <xs:element ref="xrd:ServerStatus" minOccurs="0"/>
 *
 *             <xs:element ref="xrd:Expires" minOccurs="0"/>
 *             <xs:element ref="xrd:ProviderID" minOccurs="0"/>
 *             <xs:choice>
 *                 <xs:element ref="xrd:Redirect" minOccurs="0" maxOccurs="unbounded"/>
 *                 <xs:element ref="xrd:Ref" minOccurs="0" maxOccurs="unbounded"/>
 *             </xs:choice>
 *             <xs:element ref="xrd:LocalID" minOccurs="0" maxOccurs="unbounded"/>
 *             <xs:element ref="xrd:EquivID" minOccurs="0" maxOccurs="unbounded"/>
 *             <xs:element ref="xrd:CanonicalID" minOccurs="0" maxOccurs="unbounded"/>
 *
 *             <xs:element ref="xrd:CanonicalEquivID" minOccurs="0" maxOccurs="unbounded"/>
 *             <xs:element ref="xrd:Service" minOccurs="0" maxOccurs="unbounded"/>
 *             <xs:group ref="xrd:otherelement" minOccurs="0" maxOccurs="unbounded"/>
 *         </xs:sequence>
 *         <xs:attribute name="idref" type="xs:IDREF" use="optional"/>
 *         <xs:attribute name="version" type="xs:string" use="optional" fixed="2.0"/>
 *         <xs:attributeGroup ref="xrd:otherattribute"/>
 *     </xs:complexType>
 * </xs:element>
 *
 * @category   Zend
 * @package    Zend_OpenId
 * @subpackage Zend_OpenId_Discovery
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
interface Descriptor
{
    /**
     * Append discovered service endpoint
     *
     * @param \Zend\OpenId\Discovery\Xrds\Element\ServiceEndpoint $service Service to append
     *
     * @return \Zend\OpenId\Discovery\Xrds\Element\Descriptor
     */
    public function addService(\Zend\OpenId\Discovery\Xrds\Element\ServiceEndpoint $service);

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
    public function getServices($type = null);

    /**
     * Client-side status of a resolution query.
     * For more info check Section 15 of XRI Resolution 2.0
     *
     * @param int $status Status to set
     *
     * @return \Zend\OpenId\Discovery\Xrds\Element\Descriptor
     */
    public function setStatus($status);

    /**
     * Get client-side resolution status
     *
     * @return int
     */
    public function getStatus();
}
