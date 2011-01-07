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
namespace Zend\OpenId\Discovery\Service\Html;
use Zend\OpenId;

/**
 * Simple HTML discovery on URL Identifier
 *
 * HTML-Based discovery MUST be supported by Relying Parties. 
 * HTML-Based discovery is only usable for discovery of Claimed Identifiers.
 * OP Identifiers must be XRIs or URLs that support XRDS discovery.  
 *
 * To use HTML-Based discovery, an HTML document MUST be available at the URL 
 * of the Claimed Identifier. Within the HEAD element of the document: 
 *      - A LINK element MUST be included with attributes "rel" set 
 *        to "openid2.provider" and "href" set to an OP Endpoint URL 
 *      - A LINK element MAY be included with attributes "rel" set 
 *        to "openid2.local_id" and "href" set to the end user's OP-Local Identifier 
 *
 * The protocol version when HTML discovery is performed is "http://specs.openid.net/auth/2.0/signon". 
 *
 * @category   Zend
 * @package    Zend_OpenId
 * @subpackage Zend_OpenId_Discovery
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Resolver
    extends OpenId\Discovery\Service\Resolver
    implements OpenId\Discovery\Service,
               OpenId\Discovery\Transport
{
    /**
     * @var \Zend\Http\Client
     */
    private $httpClient;

    /**
     * Resolve the identifier by performing discovery on it
     *
     * @param \Zend\OpenId\Identifier Identifier to perform discovery on
     *
     * @return \Zend\OpenId\Discovery\Result
     */
    public function discover(\Zend\OpenId\Identifier $id)
    {
    }

    /**
     * Inject HTTP client used as transport in discovery process
     *
     * @param \Zend\Http\Client $client HTTP Client
     *
     * @return \Zend\OpenId\Discovery\Service Allow method chaining
     */
    public function setHttpClient(\Zend\Http\Client $client)
    {
        $this->httpClient = $client;
        return $this;
    }

    /**
     * Obtain contained HTTP transport
     *
     * @return \Zend\Http\Client
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }
}
