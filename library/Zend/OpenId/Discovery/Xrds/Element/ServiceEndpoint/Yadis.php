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
 * Container to encapsulate XRDS Service data for Yadis schema
 *
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
 * @category   Zend
 * @package    Zend_OpenId
 * @subpackage Zend_OpenId_Discovery
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Yadis
    extends    OpenId\Discovery\Xrds\Element\ServiceEndpoint\AbstractServiceEndpoint
    implements OpenId\Discovery\Xrds\Element\ServiceEndpoint
{
    /**
     * Acc. to Yadis XRDS Schema priority attribute must be non-negative integer
     *
     * @var int
     */
    private $priority;

    /**
     * Set service priority
     *
     * @param int $priority Priority value
     *
     * @return \Zend\OpenId\Discovery\Xrds\Element\ServiceEndpoint\Yadis
     */
    public function setPriority($priority)
    {
        $this->priority = (int)$priority;
        return $this;
    }

    /**
     * Get service priority
     *
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * String uniquely identifying the service object
     * For Yadis services there's and extra priority parameter
     *
     * @param string $salt Extra string to be used in hashing
     *
     * @return string
     */
    public function getHash($salt = '')
    {
        return parent::getHash($this->getPriority());
    }
}
