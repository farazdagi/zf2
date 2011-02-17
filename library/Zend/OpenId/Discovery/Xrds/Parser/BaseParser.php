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
    Zend\OpenId\Discovery\Xrds,
    Zend\OpenId\Discovery\Xrds\Element,
    Zend\OpenId\Discovery\Xrds\Parser\Exception;

/**
 * Default XRDS Parser Implementation
 *
 * @category   Zend
 * @package    Zend_OpenId
 * @subpackage Zend_OpenId_Discovery
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
abstract class BaseParser
    implements Xrds\Parser
{
    /**
     * Parse input XRDS string into Descriptor object
     *
     * @param stirng $input Input XML 
     *
     * @return \Zend\OpenId\Discovery\Xrds\Element\Descriptor
     */
    public function parse($input)
    {
        $tree = $this->loadString($input);
        $descriptor = $this->initDescriptorInstance(
            $this->getDescriptorInstance(), $this->extractDescriptor($tree)
        );

        foreach($this->extractServices($tree) as $serviceElement) {
            $service = $this->getServiceInstance();
            $descriptor->addService(
                $this->initServiceInstance($service, $serviceElement)
            );
        }

        return $descriptor;
    }
    /**
     * Update service instance with values from XRD Service
     *
     * @param \Zend\OpenId\Discovery\Xrds\Element\Descriptor $descriptor Descriptor instance to configure
     * @param \SimpleXMLElement $el XML Element
     *
     * @return \Zend\OpenId\Discovery\Xrds\Element\Descriptor
     */
    private function initDescriptorInstance(
        \Zend\OpenId\Discovery\Xrds\Element\Descriptor $descriptor, 
        \SimpleXMLElement $el)
    {
        $attr = $el->attributes();

        if ($el->Status->count()) {
            $statusCode = (int)$el->Status->attributes()->code;
            if (0 !== $statusCode) {
                $descriptor->setStatus($statusCode);
            }
        }

        return $descriptor;
    }

    /**
     * Update service instance with values from XRD Service
     *
     * @param \Zend\OpenId\Discovery\Xrds\Element\ServiceEndpoint $service Service instance to configure
     * @param \SimpleXMLElement $el XML Element
     *
     * @return \Zend\OpenId\Discovery\Xrds\Element\Service
     */
    private function initServiceInstance(
        \Zend\OpenId\Discovery\Xrds\Element\ServiceEndpoint $service, 
        \SimpleXMLElement $el)
    {
        $attr = $el->attributes();

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
        } else {
            $service->setPriority(0);
        }

        // OP Local Id
        if ((string)$el->LocalID) {
            $service->setLocalIdentifier((string)$el->LocalID);
        }

        return $service;
    }

    /**
     * Load XRDS string into tree
     *
     * @param stirng $input Input XML 
     *
     * @return \SimpleXMLElement
     */
    private function loadString($input)
    {
        libxml_clear_errors();
        libxml_use_internal_errors(true);
        $tree = simplexml_load_string($input);
        if (!$tree) {
            $msg = "Failed loading XML\n";
            foreach(libxml_get_errors() as $error) {
                $msg .= "\t" . $error->message;
            }
            throw new Exception\ParseFailedException($msg);
        }
        return $tree;
    }

    /**
     * Extract XRD Elements from SimpleXMLElement
     *
     * return \SimpleXMLElement
     */
    private function extractDescriptor(\SimpleXMLElement $el)
    {
        if ($el->XRD->count()) {
            return $el->XRD;
        } else if ($el->XRDS->count() && $el->XRDS->XRD->count()) {
            return $el->XRDS->XRD;
        } else if ($el->Service) { // XRD as root element
            return $el;
        } else {
            throw new Exception\ElementNotFoundException("XRD element cannot be located in input XRDS");
        }
    }

    /**
     * Extract services from SimpleXMLElement
     *
     * return \SimpleXMLElement
     */
    private function extractServices(\SimpleXMLElement $el)
    {
        if ($el->Service->count()) {
            return $el->Service;
        } else if ($el->XRD->count() && $el->XRD->Service->count()) {
            return $el->XRD->Service;
        //} else if ($el->XRDS->count() && $el->XRDS->XRD->count() && $el->XRDS->XRD->Service->count()) {
            //return $el->XRDS->XRD->Service;
        } else {
            return array();
        }
    }
}
