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
namespace Zend\OpenId\Discovery\Service\Yadis;
use Zend\OpenId,
    Zend\OpenId\Storage,
    Zend\OpenId\Discovery,
    Zend\OpenId\Identifier,
    Zend\OpenId\Discovery\Xrds\Parser\Yadis as Parser,
    Zend\OpenId\Discovery\Service\Yadis\Result as DiscoveryInformation,
    Zend\OpenId\Discovery\Service\Exception;

/**
 * Yadis protocol implementation:
 * - Given a YadisID, which is an URL or is resolvable to URL, OP produces Yadis Document
 * - Yadis document is an XRDS containing Yadis Resource Descriptor (XRD in XRI terms)
 * - Once Yadis document is obtained, it may be parsed for services, as any other
 * XRDS document.
 * - Yadis Resource Descriptor is parsed into Discovery\Information object.
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
     * Location response header used for referencing Yadis Documents
     * @var string
     */
    const XRDS_LOCATION = 'X-XRDS-Location';

    /**
     * MIME Type for Yadis Document
     * @var string
     */
    const YADIS_MIME_TYPE = 'application/xrds+xml';

    /**
     * Yadis provider response is one of following:
     * - HTML Document having <meta> with http-equiv set to X-XRDS-Location
     * - X-XRDS-Location HTTP Response Header + content
     * - Response Headers ONLY. Either X-XRDS-Location, Content-Type or both
     * - Document with MIME type "application/xrds+xml"
     *
     * So, we must accept HTML, XHTML, and XRDS documents
     *
     * @var string
     */
    const REQUEST_HEADER_ACCEPT = 'text/html; q=0.2, application/xhtml+xml; q=0.3, application/xrds+xml';

    /**
     * When resolving Provider may return either Yadis document or URL that 
     * locates Yadis document. Max. redirects defines maximum number of 
     * redirects resolver will take before retiring.
     *
     * @var int
     */
    private $maxRedirects = 10;

    /**
     * Transport
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

        $service = $this->getServiceEndpoint($id->get());
        $expiration = new Storage\Expiration(time() + 60 * 60);

        $info = new DiscoveryInformation();
        $info->setSuppliedIdentifier($id)
             ->setProtocolVersion($service->getProtocolVersion())
             ->setLocalIdentifier(new Identifier\OpLocal($service->getLocalIdentifier()))
             ->setEndpointUrl($service->getUri());
        
        // persist/cache info
        $this->getStorage()
             ->addDiscoveryInformation($id, $info, $expiration);

        return $info;
    }

    /**
     * Obtain Yadis document by performing Yadis discovery on a given URL.
     *
     * 1. Determine Resource Descriptor URL.
     *  Send HEAD request, in order to obtain XRDS-Location Headers.
     *
     * 2. Try to obtain XRDS Document (which is Yadis Resource Descriptor)
     *  Send GET request on XRDS-Location or on original Yadis URL, to obtain 
     *  either new XRDS-Location (for a third request) or XRDS Document itself.
     *
     *  If original URL was Yadis Resource Descriptor URL (meaning direct URL not
     *  requiring any redirection and returning content of type application/xrds+xml)
     *  Then on second request (GET, which follows HEAD), we are able to have 
     *  Yadis Document.
     *
     * 3. Try to continue resolving if no Yadis Document obtained yet
     *  Provided we haven't obtained Yadis Document yet, and have XRDS-Location,
     *  either from response headers or as a meta-attribute, we can initiate
     *  recursive discovery on that XRDS-Location.
     *
     * @param string $url Yadis Resourse Descriptor URL or Yadis ID (resolvable to URL)
     * @param int $count Request count, no further requests are allowed once we hit $maxRedirests limit
     *
     * @return \Zend\OpenId\Discovery\Xrds\Element\ServiceEndpoint
     */
    private function getServiceEndpoint($url, $count = 0)
    {
        if ($count > $this->maxRedirects) {
            throw new Exception\RedirectsLimitExcdeption(sprintf(
                'Maximum redirects limit %d reached..', $this->maxRedirects
            ));
        }

        // setup request
        $client = $this->getHttpClient();
        if ($client === null) {
            throw new Exception\DependencyMissingException('HTTP client must be injected');
        }

        // HEAD request
        try {
            $client->resetParameters()
                   ->setUri($url)
                   ->setMethod(\Zend\Http\Client::HEAD)
                   ->setHeaders(array(
                       'Accept: ' . self::REQUEST_HEADER_ACCEPT,
                   ));
            $response = $client->request();
        } catch (\Exception $e) {
            throw new Exception\HttpRequestFailedException('HTTP Request failed', 0, $e);
        }

        // check request status
        if ($response->getStatus() != 200) {
            throw new Exception\YadisDocumentNotFound();
        }

        // decide what XRDS Location to use
        if ($loc = $response->getHeader('X-XRDS-Location')) { // Resource Descriptor URL
            $xrdsUrl = $loc;
        } else { // Yadis URL
            $xrdsUrl = $url;
        }

        // GET request: try obtaining XRDS Document from XRDS Descriptor URL
        try {
            $client->resetParameters()
                   ->setUri($xrdsUrl)
                   ->setMethod(\Zend\Http\Client::GET)
                   ->setHeaders(array(
                       'Accept: ' . self::REQUEST_HEADER_ACCEPT,
                   ));
            $response = $client->request();
        } catch (\Exception $e) {
            throw new Exception\HttpRequestFailedException('HTTP Request failed', 0, $e);
        }

        // check request status
        if ($response->getStatus() != 200) {
            throw new Exception\YadisDocumentNotFound();
        }

        $xrdsUrl = null;

        // check if XRDS-Location is set in response headers
        if ($loc = $response->getHeader('X-XRDS-Location')) {
            $xrdsUrl = $loc;
        }

        // check if XRDS-Location is set in meta
        if (null === $xrdsUrl && preg_match_all('/<[\s]*meta[\s]*name="?' . '([^>"]*)"?[\s]*' . 'content="?([^>"]*)"?[\s]*[\/]?[\s]*>/si', $response->getBody(), $match)) {
            foreach ($match[1] as $key => $name) {
                if ($name == self::XRDS_LOCATION) {
                    $xrdsUrl = $match[2][$key];
                    break;
                }
            }
        }
        

        // make sure redirect is not requested
        if (null !== $xrdsUrl) {
            return $this->getServiceEndpoint($xrdsUrl, ++$count);
        }

        // check if xrds+xml content type is claimed
        if ('application/xrds+xml' === $response->getHeader('Content-type')) {
            return $this->parseYadisDocument($response->getBody());
        }

        return null;
    }

    /**
     * Parse Yadis Descriptor Document into ServiceEndpoint with the most priority
     *
     * @param string $xrdsDocument Yadis Descriptor Document in XRDS format
     *
     * @return \Zend\OpenId\Discovery\Xrds\Element\ServiceEndpoint
     */
    private function parseYadisDocument($xrdsDocument)
    {
        $parser = new Parser();
        $descriptor = $parser->parse($xrdsDocument);

        $priority = 0;
        $types = array(
            Discovery\Information::OPENID_20,
            Discovery\Information::OPENID_11,
            Discovery\Information::OPENID_10,
        );

        $services = array();
        
        // get all OpenID related services
        foreach ($types as $type) {
            foreach ($descriptor->getServices() as $service) {
                if ($service->hasType($type)) {
                    // within API versions sort by priority
                    $priority = $service->getPriority();
                    $services[$type][$priority] = $service;
                    continue;
                }
            }
            if (isset($services[$type])) {
                krsort($services[$type]);
            }
        }

        // single a service
        $service = null;

        // obtain API having most priority (controlled by order of OpenID versions in $types)
        if (is_array($services) && count($services)) {
            $services = array_shift($services);
            // get service for a given API having highest priority
            if (is_array($services) && count($services)) {
                $service = array_shift($services);
            }
        }

        if (null === $service) {
            throw Exception\UnableToLocateServiceException();
        }

        return $service;
    }

    /**
     * When resolving Provider may return either Yadis document or URL that 
     * locates Yadis document. Max. redirects defines maximum number of 
     * redirects resolver will take before retiring.
     *
     * @param int $value Maximum redirects
     *
     * @return \Zend\OpenId\Discovery\Service\Yadis\Resolver
     */
    public function setMaxRedirects($value)
    {
        $this->maxRedirects = $value;
        return $this;
    }

    /**
     * Get number of allowable redirects during process of Yadis Discovery
     *
     * @return int
     */
    public function getMaxRedirects()
    {
        return $this->maxRedirects;
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
