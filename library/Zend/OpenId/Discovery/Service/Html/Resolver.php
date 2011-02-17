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
use Zend\OpenId,
    Zend\OpenId\Storage,
    Zend\OpenId\Discovery,
    Zend\OpenId\Identifier,
    Zend\OpenId\Discovery\Service\Html\Result as DiscoveryInformation,
    Zend\OpenId\Discovery\Service\Exception;

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
 * The protocol version when HTML discovery is performed are:
 * - "http://specs.openid.net/auth/2.0/signon" 
 * - "http://openid.net/signon/1.0"
 * - "http://openid.net/signon/1.1"
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
     * @return \Zend\OpenId\Discovery\Information
     */
    public function discover(\Zend\OpenId\Identifier $id)
    {
        if ($info = $this->getStorage()->getDiscoveryInformation($id)) {
            return $info;
        }

        // setup request
        $client = $this->getHttpClient();
        if ($client === null) {
            throw new Exception\DependencyMissingException('HTTP client must be injected');
        }

        try {
            $client->resetParameters()
                   ->setUri($id->get())
                   ->setMethod(\Zend\Http\Client::GET)
                   ->setParameterGet(array());
            $response = $client->request();
        } catch (\Exception $e) {
            throw new Exception\HttpRequestFailedException('HTTP Request failed', 0, $e);
        }

        if (false === $response->isSuccessful()) {
            throw new Exception\DiscoveryFailedException('Destination page not found or is empty');
        }

        $status = $response->getStatus();
        $body = $response->getBody();

        // parse the output
        if ($status != 200 || !strlen(trim($body))) {
            return null;
        }

        $version = Discovery\Information::OPENID_20;
        $claimedId = $opLocalId = $endpointUrl = null;

        if (preg_match(
                '/<link[^>]*rel=(["\'])[ \t]*(?:[^ \t"\']+[ \t]+)*?openid2.provider[ \t]*[^"\']*\\1[^>]*href=(["\'])([^"\']+)\\2[^>]*\/?>/i',
                $body,
                $r)) {
            $version = Discovery\Information::OPENID_20;
            $endpointUrl = $r[3];
        } else if (preg_match(
                '/<link[^>]*href=(["\'])([^"\']+)\\1[^>]*rel=(["\'])[ \t]*(?:[^ \t"\']+[ \t]+)*?openid2.provider[ \t]*[^"\']*\\3[^>]*\/?>/i',
                $body,
                $r)) {
            $version = Discovery\Information::OPENID_20;
            $endpointUrl = $r[2];
        } else if (preg_match(
                '/<link[^>]*rel=(["\'])[ \t]*(?:[^ \t"\']+[ \t]+)*?openid.server[ \t]*[^"\']*\\1[^>]*href=(["\'])([^"\']+)\\2[^>]*\/?>/i',
                $body,
                $r)) {
            $version = Discovery\Information::OPENID_11;
            $endpointUrl = $r[3];
        } else if (preg_match(
                '/<link[^>]*href=(["\'])([^"\']+)\\1[^>]*rel=(["\'])[ \t]*(?:[^ \t"\']+[ \t]+)*?openid.server[ \t]*[^"\']*\\3[^>]*\/?>/i',
                $body,
                $r)) {
            $version = Discovery\Information::OPENID_11;
            $endpointUrl = $r[2];
        } else {
            return null;
        }
        if ($version == Discovery\Information::OPENID_20) {
            if (preg_match(
                    '/<link[^>]*rel=(["\'])[ \t]*(?:[^ \t"\']+[ \t]+)*?openid2.local_id[ \t]*[^"\']*\\1[^>]*href=(["\'])([^"\']+)\\2[^>]*\/?>/i',
                    $body,
                    $r)) {
                $opLocalId = $r[3];
            } else if (preg_match(
                    '/<link[^>]*href=(["\'])([^"\']+)\\1[^>]*rel=(["\'])[ \t]*(?:[^ \t"\']+[ \t]+)*?openid2.local_id[ \t]*[^"\']*\\3[^>]*\/?>/i',
                    $body,
                    $r)) {
                $opLocalId = $r[2];
            }
        } else {
            if (preg_match(
                    '/<link[^>]*rel=(["\'])[ \t]*(?:[^ \t"\']+[ \t]+)*?openid.delegate[ \t]*[^"\']*\\1[^>]*href=(["\'])([^"\']+)\\2[^>]*\/?>/i',
                    $body,
                    $r)) {
                $opLocalId = $r[3];
            } else if (preg_match(
                    '/<link[^>]*href=(["\'])([^"\']+)\\1[^>]*rel=(["\'])[ \t]*(?:[^ \t"\']+[ \t]+)*?openid.delegate[ \t]*[^"\']*\\3[^>]*\/?>/i',
                    $body,
                    $r)) {
                $opLocalId = $r[2];
            }
        }

        $expiration = new Storage\Expiration(time() + 60 * 60);
        $info = new DiscoveryInformation();
        $info->setSuppliedIdentifier($id)
             ->setProtocolVersion($version)
             ->setLocalIdentifier(new Identifier\OpLocal($opLocalId))
             ->setEndpointUrl($endpointUrl);

        // persist/cache info
        $this->getStorage()
             ->addDiscoveryInformation($id, $info, $expiration);

        return $info;
   }

    /**
     * Inject HTTP client used as transport in discovery process
     *
     * @param \Zend\Http\Client $client HTTP Client
     *
     * @return \Zend\OpenId\Discovery\Service Allow method chaining
     */
    public function setHttpClient(\Zend\Http\Client $client = null)
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
