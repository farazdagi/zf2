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
 * Container to encapsulate data from XRD Sequence, abtracts single XRD element 
 *
 * From XRI 2.0 Resolution Specs:
 *  <xs:element name="XRIDescriptor">
 *      <xs:complexType>
 *          <xs:sequence>
 *              <xs:element ref="xrid:Resolved" />
 *              <xs:element ref="xrid:AuthorityID" />
 *              <xs:element ref="xrid:Expires" minOccurs="0"/>
 *              <xs:element ref="xrid:Authority" minOccurs="0"
 *              <xs:element ref="xrid:Service" minOccurs="0"
 *              <xs:element ref="xrid:Synonyms" minOccurs="0"/>
 *              <xs:element ref="xrid:TrustMechanism" minOccurs="0"/>
 *              <xs:group ref="xrid:otherelement" minOccurs="0" maxOccurs="unbounded"/> 
 *          </xs:sequence>
 *          <xs:attribute ref="xrid:id"/> 
 *          <xs:attributeGroup ref="xrid:otherattribute"/> 
 *          <xs:attribute ref="xrid:version"/>
 *      </xs:complexType> 
 *  </xs:element>
 *
 * @category   Zend
 * @package    Zend_OpenId
 * @subpackage Zend_OpenId_Discovery
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Xri
    extends OpenId\Discovery\Xrds\Element\Descriptor\AbstractDescriptor
    implements OpenId\Discovery\Xrds\Element\Descriptor
{}
