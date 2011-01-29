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
use Zend\OpenId,
    Zend\OpenId\Discovery\Xrds\Exception;

/**
 * Container to encapsulate XRDS Service data for XRI 2.0 schema
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
class Xri
    extends    OpenId\Discovery\Xrds\Element\ServiceEndpoint\AbstractServiceEndpoint
    implements OpenId\Discovery\Xrds\Element\ServiceEndpoint
{
    /**
     * Add service type
     * Acc. to schema XRI defines only 0 or 1 type element inside XRID's service
     * element. Therefore method is overridden to preserve singularity of type.
     *
     * @param string $type Type of service being described
     *
     * @return \Zend\OpenId\Discovery\Xrds\Element\ServiceEndpoint
     */
    public function addType($type)
    {
        $this->reset(self::RESET_TYPES);
        return parent::addType($type);
    }

    /**
     * Fetch (by key) service type
     * Acc. to schema XRI defines only 0 or 1 type element inside XRID's service
     * element. Therefore method is overridden to preserve singularity of type.
     *
     * @param int $key Zero based index of element
     *
     * @return string
     */
    public function getType($key = 0)
    {
        if ($key !== 0) {
            throw new Exception\ElementFailsValidationException(
                'XRI 2.0 Resolution specified 0 or 1 modifier for ' .
                'xrid:XRIDescriptor/xrid:Service/xrid:Type');
        }
        return parent::getType($key);
    }

    /**
     * Set several types at once
     * Acc. to schema XRI defines only 0 or 1 type element inside XRID's service
     * element. Therefore method is overridden to preserve singularity of type.
     *
     * @param array $list Array of strings
     *
     * @return \Zend\OpenId\Discovery\Xrds\Element\ServiceEndpoint
     */
    public function setTypes($list)
    {
        // only last element is passed to parent
        return parent::setTypes(array_slice($list, -1));
    }
}
