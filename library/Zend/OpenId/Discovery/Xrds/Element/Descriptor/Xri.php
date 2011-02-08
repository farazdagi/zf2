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
use Zend\OpenId,
    Zend\OpenId\Discovery\Xrds\Element;

/**
 * Container to encapsulate data from XRD Sequence, abtracts single XRD element 
 *
 * From XRI 2.0 Resolution Specs (Community Specs 01):
 *
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
 * @see http://docs.oasis-open.org/xri/2.0/specs/cd02/xrd.xsd
 * @category   Zend
 * @package    Zend_OpenId
 * @subpackage Zend_OpenId_Discovery
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Xri
    extends BaseDescriptor
    implements Element\Descriptor
{}
