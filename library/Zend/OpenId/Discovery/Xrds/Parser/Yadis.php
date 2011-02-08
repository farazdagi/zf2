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
    extends    Xrds\Parser\BaseParser
    implements Xrds\Parser
{
    /**
     * Create XRD element
     *
     * @return \Zend\OpenId\Discovery\Xrds\Element\Descriptor
     */
    public function getDescriptorInstance()
    {
        return new Descriptor();
    }

    /**
     * Create XRD's Service element
     *
     * @return \Zend\OpenId\Discovery\Xrds\Element\ServiceEndpoint
     */
    public function getServiceInstance()
    {
        return new ServiceEndpoint();
    }
}
