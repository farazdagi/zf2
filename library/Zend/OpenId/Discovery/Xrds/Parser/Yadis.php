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
namespace Zend\OpenId\Discovery\Xrds\Parser;
use Zend\OpenId,
    Zend\OpenId\Discovery\Xrds\Element\Descriptor\Yadis as Descriptor,
    Zend\OpenId\Discovery\Xrds\Element\ServiceEndpoint\Yadis as ServiceEndpoint;

/**
 * Yadis parser for XRDS documents
 *
 * @category   Zend
 * @package    Zend_OpenId
 * @subpackage Zend_OpenId_Discovery
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Yadis
    extends OpenId\Discovery\Xrds\Parser\AbstractParser
    implements OpenId\Discovery\Xrds\Parser
{
    /**
     * Create XRD element
     *
     * @return \Zend\OpenId\Discovery\Xrds\Element\Descriptor
     */
    public function createDescriptor()
    {
        return new Descriptor();
    }

    /**
     * Create XRD Service
     *
     * @return \Zend\OpenId\Discovery\Xrds\Element\Service
     */
    public function createService(\SimpleXMLElement $el)
    {
        $attr = $el->attributes();

        $service = new ServiceEndpoint();

        // type
        if ($el->Type->count()) {
            foreach ($el->Type as $type) {
                $service->addType(trim((string)$type));
            }
        }

        // service location
        if ($el->URI->count()) {
            foreach ($el->URI as $uri) {
                $service->addUri(trim((string)$uri));
            }
        }

        // priority
        if ((string)$attr->priority) {
            $service->setPriority((string)$attr->priority);
        }

        return $service;
    }

    /**
     * Extract services from SimpleXMLElement
     *
     * return \SimpleXMLElement
     */
    public function extractServices(\SimpleXMLElement $el)
    {
        return $el->XRD->Service;
    }
}
