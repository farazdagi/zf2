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
    Zend\OpenId\Discovery\Xrds\Exception\ParseFailedException as ParseFailed;

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
        $descriptor = $this->createDescriptor();

        foreach($this->extractServices($tree) as $service) {
            $descriptor->addService(
                $this->createService($service)
            );
        }

        return $descriptor;
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
        libxml_use_internal_errors(true);
        $tree = simplexml_load_string($input);
        if (!$tree) {
            $msg = "Failed loading XML\n";
            foreach(libxml_get_errors() as $error) {
                $msg .= "\t" . $error->message;
            }
            throw new ParseFailed($msg);
        }
        return $tree;
    }

}
