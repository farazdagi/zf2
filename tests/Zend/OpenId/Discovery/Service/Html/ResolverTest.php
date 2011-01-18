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
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * @namespace
 */
namespace ZendTest\OpenId\Discovery\Service\Html;

use Zend\OpenId\OpenId,
    Zend\OpenId\Discovery\Service\Html\Resolver,
    Zend\OpenId\Discovery,
    Zend\OpenId\Identifier,
    Zend\OpenId\Storage,
    Zend\OpenId\Consumer\GenericConsumer as Consumer,
    Zend\Http;

/**
 * @category   Zend
 * @package    Zend_OpenId
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_OpenId
 */
class DiscoveryTest extends \PHPUnit_Framework_TestCase
{
    const ID = "http://id.myopenid.com/";
    const ID_CLAIMED = "http://claimed.myopenid.com/";
    const ID_OPLOCAL = "http://torio.myopenid.com";
    const OP_ENDPOINT = "http://www.myopenid.com/";

    /**
     * @var \Zend\OpenId\Discovery\Information
     */
    private $service;

    /**
     * @var \Zend\Http\Client\Adapter\Test
     */
    private $http;

    public function setUp()
    {
        // setup HTTP client
        $client = new Http\Client(null,
            array(
                'maxredirects' => 4,
                'timeout'      => 15,
                'useragent'    => 'Zend_OpenId'
            )
        );
        $this->http = new Http\Client\Adapter\Test();
        $client->setAdapter($this->http);

        // setup cache storage
        $storage = new Storage\File(__DIR__."/_files/consumer");
        $storage->resetDiscoveryInformation();

        // setup discovery service
        $this->service = new Resolver();
        $this->service->setHttpClient($client)
                      ->setStorage($storage);

    }

    public function tearDown()
    {
        $this->service->getStorage()->reset();
    }

    public function testHttpClientMissingException()
    {
        $this->setExpectedException(
            '\Zend\OpenId\Discovery\Service\Exception\DependencyMissingException', 
            'HTTP client must be injected');
        $this->service->setHttpClient(null); // reset client

        $discoveryInfo = $this->service->discover(new Identifier\UserSupplied(self::ID));
    }

    public function testHttpRequestFailedException()
    {
        $this->setExpectedException(
            '\Zend\OpenId\Discovery\Service\Exception\HttpRequestFailedException', 
            'HTTP Request failed');
        $id = 'htttp://eror.in.address/some';
        $this->service->getStorage()->removeDiscoveryInformation(new Identifier\UserSupplied($id));
        $discoveryInfo = $this->service->discover(new Identifier\UserSupplied($id));
    }

    public function testDiscoveryFailedException()
    {
        $this->setExpectedException(
            '\Zend\OpenId\Discovery\Service\Exception\DiscoveryFailedException', 
            'Destination page not found or is empty');

        $this->http->setResponse("HTTP/1.1 501 Not Implemented\r\n\r\n" .  "");
        $this->service->getStorage()->removeDiscoveryInformation(new Identifier\UserSupplied(self::ID));
        $discoveryInfo = $this->service->discover(new Identifier\UserSupplied(self::ID));
    }

    /**
     * Ported from previous implementation
     */
    public function testDiscovery()
    {
        $this->http->setResponse("HTTP/1.1 200 Ok\r\n\r\n" .  "");
        $this->service->getStorage()->removeDiscoveryInformation(new Identifier\UserSupplied(self::ID));
        $this->assertNull($this->service->discover(new Identifier\UserSupplied(self::ID)) );

        // OpenID 1.1
        $this->service->getStorage()->removeDiscoveryInformation(new Identifier\UserSupplied(self::ID));
        $this->http->setResponse("HTTP/1.1 200 OK\r\n\r\n" .
                           "<html><head>\n" .
                           "<link rel=\"openid.server\" href=\"" . self::OP_ENDPOINT . "\">\n" .
                           "<link rel=\"openid.delegate\" href=\"" . self::ID_OPLOCAL . "\">\n" .
                           "</head><body</body></html>\n");

        $info = $this->service->discover(new Identifier\UserSupplied(self::ID));

        $this->assertInstanceOf('\Zend\OpenId\Discovery\Information', $info);
        $this->assertSame(self::OP_ENDPOINT, $info->getEndpointUrl() );
        $this->assertSame(self::ID_OPLOCAL, $info->getLocalIdentifier()->get());
        $this->assertSame(Discovery\Information::OPENID_11, $info->getProtocolVersion());

        // OpenID 1.1 (swapped href/rel atts placement)
        $this->service->getStorage()->removeDiscoveryInformation(new Identifier\UserSupplied(self::ID));
        $this->http->setResponse("HTTP/1.1 200 OK\r\n\r\n" .
                           "<html><head>\n" .
                           "<link href=\"" . self::OP_ENDPOINT . "\" rel=\"openid.server\">\n" .
                           "<link href=\"" . self::ID_OPLOCAL . "\" rel=\"openid.delegate\">\n" .
                           "</head><body</body></html>\n");

        $info = $this->service->discover(new Identifier\UserSupplied(self::ID));

        $this->assertInstanceOf('\Zend\OpenId\Discovery\Information', $info);
        $this->assertSame(self::OP_ENDPOINT, $info->getEndpointUrl() );
        $this->assertSame(self::ID_OPLOCAL, $info->getLocalIdentifier()->get());
        $this->assertSame(Discovery\Information::OPENID_11, $info->getProtocolVersion());

        // OpenID 2.0
        $this->service->getStorage()->removeDiscoveryInformation(new Identifier\UserSupplied(self::ID));
        $this->http->setResponse("HTTP/1.1 200 OK\r\n\r\n" .
                           "<html><head>\n" .
                           "<link rel=\"openid2.provider\" href=\"" . self::OP_ENDPOINT . "\">\n" .
                           "<link rel=\"openid2.local_id\" href=\"" . self::ID_OPLOCAL . "\">\n" .
                           "</head><body</body></html>\n");

        $info = $this->service->discover(new Identifier\UserSupplied(self::ID));

        $this->assertInstanceOf('\Zend\OpenId\Discovery\Information', $info);
        $this->assertSame(self::OP_ENDPOINT, $info->getEndpointUrl() );
        $this->assertSame(self::ID_OPLOCAL, $info->getLocalIdentifier()->get());
        $this->assertSame(Discovery\Information::OPENID_20, $info->getProtocolVersion());

        // OpenID 2.0 (swap href/rel atts placement)
        $this->service->getStorage()->removeDiscoveryInformation(new Identifier\UserSupplied(self::ID));
        $this->http->setResponse("HTTP/1.1 200 OK\r\n\r\n" .
                           "<html><head>\n" .
                           "<link href=\"" . self::OP_ENDPOINT . "\" rel=\"openid2.provider\">\n" .
                           "<link href=\"" . self::ID_OPLOCAL . "\" rel=\"openid2.local_id\">\n" .
                           "</head><body</body></html>\n");

        $info = $this->service->discover(new Identifier\UserSupplied(self::ID));

        $this->assertInstanceOf('\Zend\OpenId\Discovery\Information', $info);
        $this->assertSame(self::OP_ENDPOINT, $info->getEndpointUrl() );
        $this->assertSame(self::ID_OPLOCAL, $info->getLocalIdentifier()->get());
        $this->assertSame(Discovery\Information::OPENID_20, $info->getProtocolVersion());

        // OpenID 1.1 + 2.0
        $this->service->getStorage()->removeDiscoveryInformation(new Identifier\UserSupplied(self::ID));
        $this->http->setResponse("HTTP/1.1 200 OK\r\n\r\n" .
                           "<html><head>\n" .
                           "<link rel=\"openid2.provider\" href=\"" . self::OP_ENDPOINT . "\">\n" .
                           "<link rel=\"openid2.local_id\" href=\"" . self::ID_OPLOCAL . "\">\n" .
                           "<link rel=\"openid.server\" href=\"" . self::OP_ENDPOINT . "\">\n" .
                           "<link rel=\"openid.delegate\" href=\"" . self::ID_OPLOCAL . "\">\n" .
                           "</head><body</body></html>\n");

        $info = $this->service->discover(new Identifier\UserSupplied(self::ID));

        $this->assertInstanceOf('\Zend\OpenId\Discovery\Information', $info);
        $this->assertSame(self::OP_ENDPOINT, $info->getEndpointUrl() );
        $this->assertSame(self::ID_OPLOCAL, $info->getLocalIdentifier()->get());
        $this->assertSame(Discovery\Information::OPENID_20, $info->getProtocolVersion());

        // OpenID 1.1 (single quotes)
        $this->service->getStorage()->removeDiscoveryInformation(new Identifier\UserSupplied(self::ID));
        $this->http->setResponse("HTTP/1.1 200 OK\r\n\r\n" .
                           "<html><head>\n" .
                           "<link rel='openid.server' href='" . self::OP_ENDPOINT . "'>\n" .
                           "<link rel='openid.delegate' href='" . self::ID_OPLOCAL . "'>\n" .
                           "</head><body</body></html>\n");

        $info = $this->service->discover(new Identifier\UserSupplied(self::ID));

        $this->assertInstanceOf('\Zend\OpenId\Discovery\Information', $info);
        $this->assertSame(self::OP_ENDPOINT, $info->getEndpointUrl() );
        $this->assertSame(self::ID_OPLOCAL, $info->getLocalIdentifier()->get());
        $this->assertSame(Discovery\Information::OPENID_11, $info->getProtocolVersion());

        // OpenID 1.1 (single quotes, swap href/rel atts placement)
        $this->service->getStorage()->removeDiscoveryInformation(new Identifier\UserSupplied(self::ID));
        $this->http->setResponse("HTTP/1.1 200 OK\r\n\r\n" .
                           "<html><head>\n" .
                           "<link href='" . self::OP_ENDPOINT . "' rel='openid.server'>\n" .
                           "<link href='" . self::ID_OPLOCAL . "' rel='openid.delegate'>\n" .
                           "</head><body</body></html>\n");

        $info = $this->service->discover(new Identifier\UserSupplied(self::ID));

        $this->assertInstanceOf('\Zend\OpenId\Discovery\Information', $info);
        $this->assertSame(self::OP_ENDPOINT, $info->getEndpointUrl() );
        $this->assertSame(self::ID_OPLOCAL, $info->getLocalIdentifier()->get());
        $this->assertSame(Discovery\Information::OPENID_11, $info->getProtocolVersion());

        // OpenID 2.0 (single quotes)
        $this->service->getStorage()->removeDiscoveryInformation(new Identifier\UserSupplied(self::ID));
        $this->http->setResponse("HTTP/1.1 200 OK\r\n\r\n" .
                           "<html><head>\n" .
                           "<link rel='openid2.provider' href='" . self::OP_ENDPOINT . "'>\n" .
                           "<link rel='openid2.local_id' href='" . self::ID_OPLOCAL . "'>\n" .
                           "</head><body</body></html>\n");

        $info = $this->service->discover(new Identifier\UserSupplied(self::ID));

        $this->assertInstanceOf('\Zend\OpenId\Discovery\Information', $info);
        $this->assertSame(self::OP_ENDPOINT, $info->getEndpointUrl() );
        $this->assertSame(self::ID_OPLOCAL, $info->getLocalIdentifier()->get());
        $this->assertSame(Discovery\Information::OPENID_20, $info->getProtocolVersion());

        // OpenID 2.0 (single quotes, swap href/rel atts placement)
        $this->service->getStorage()->removeDiscoveryInformation(new Identifier\UserSupplied(self::ID));
        $this->http->setResponse("HTTP/1.1 200 OK\r\n\r\n" .
                           "<html><head>\n" .
                           "<link href='" . self::OP_ENDPOINT . "' rel='openid2.provider'>\n" .
                           "<link href='" . self::ID_OPLOCAL . "' rel='openid2.local_id'>\n" .
                           "</head><body</body></html>\n");

        $info = $this->service->discover(new Identifier\UserSupplied(self::ID));

        $this->assertInstanceOf('\Zend\OpenId\Discovery\Information', $info);
        $this->assertSame(self::OP_ENDPOINT, $info->getEndpointUrl() );
        $this->assertSame(self::ID_OPLOCAL, $info->getLocalIdentifier()->get());
        $this->assertSame(Discovery\Information::OPENID_20, $info->getProtocolVersion());

        // OpenID 1.1 + 2.0 (single quotes)
        $this->service->getStorage()->removeDiscoveryInformation(new Identifier\UserSupplied(self::ID));
        $this->http->setResponse("HTTP/1.1 200 OK\r\n\r\n" .
                           "<html><head>\n" .
                           "<link rel='openid2.provider' href='" . self::OP_ENDPOINT . "'>\n" .
                           "<link rel='openid2.local_id' href='" . self::ID_OPLOCAL . "'>\n" .
                           "<link rel='openid.server' href='" . self::OP_ENDPOINT . "'>\n" .
                           "<link rel='openid.delegate' href='" . self::ID_OPLOCAL . "'>\n" .
                           "</head><body</body></html>\n");

        $info = $this->service->discover(new Identifier\UserSupplied(self::ID));

        $this->assertInstanceOf('\Zend\OpenId\Discovery\Information', $info);
        $this->assertSame(self::OP_ENDPOINT, $info->getEndpointUrl() );
        $this->assertSame(self::ID_OPLOCAL, $info->getLocalIdentifier()->get());
        $this->assertSame(Discovery\Information::OPENID_20, $info->getProtocolVersion());

        // OpenID 1.1 + 2.0 (single quotes, swap href/rel atts placement)
        $this->service->getStorage()->removeDiscoveryInformation(new Identifier\UserSupplied(self::ID));
        $this->http->setResponse("HTTP/1.1 200 OK\r\n\r\n" .
                           "<html><head>\n" .
                           "<link href='" . self::OP_ENDPOINT . "' rel='openid2.provider'>\n" .
                           "<link href='" . self::ID_OPLOCAL . "' rel='openid2.local_id'>\n" .
                           "<link href='" . self::OP_ENDPOINT . "' rel='openid.server'>\n" .
                           "<link href='" . self::ID_OPLOCAL . "' rel='openid.delegate'>\n" .
                           "</head><body</body></html>\n");

        $info = $this->service->discover(new Identifier\UserSupplied(self::ID));

        $this->assertInstanceOf('\Zend\OpenId\Discovery\Information', $info);
        $this->assertSame(self::OP_ENDPOINT, $info->getEndpointUrl() );
        $this->assertSame(self::ID_OPLOCAL, $info->getLocalIdentifier()->get());
        $this->assertSame(Discovery\Information::OPENID_20, $info->getProtocolVersion());

        // Wrong HTML
        $this->service->getStorage()->removeDiscoveryInformation(new Identifier\UserSupplied(self::ID));
        $this->http->setResponse("HTTP/1.1 200 OK\r\n\r\n" .
                           "<html><head>\n" .
                           "</head><body</body></html>\n");
        $this->assertNull($this->service->discover(new Identifier\UserSupplied(self::ID)));

        // OpenID 1.1 discovery with multivalue rel
        $this->service->getStorage()->removeDiscoveryInformation(new Identifier\UserSupplied(self::ID));
        $this->http->setResponse("HTTP/1.1 200 OK\r\n\r\n" .
                           "<html><head>\n" .
                           "<link rel=\" aaa openid.server bbb \" href=\"" . self::OP_ENDPOINT . "\">\n" .
                           "<link rel=\"aaa openid.delegate\" href=\"" . self::ID_OPLOCAL . "\">\n" .
                           "</head><body</body></html>\n");

        $info = $this->service->discover(new Identifier\UserSupplied(self::ID));

        $this->assertInstanceOf('\Zend\OpenId\Discovery\Information', $info);
        $this->assertSame(self::OP_ENDPOINT, $info->getEndpointUrl() );
        $this->assertSame(self::ID_OPLOCAL, $info->getLocalIdentifier()->get());
        $this->assertSame(Discovery\Information::OPENID_11, $info->getProtocolVersion());
    }

    public function testStoredInfo()
    {
        $this->service->getStorage()->removeDiscoveryInformation(new Identifier\UserSupplied(self::ID));
        $this->http->setResponse("HTTP/1.1 200 OK\r\n\r\n" .
                           "<html><head>\n" .
                           "<link rel=\"openid2.provider\" href=\"" . self::OP_ENDPOINT . "\">\n" .
                           "<link rel=\"openid2.local_id\" href=\"" . self::ID_OPLOCAL . "\">\n" .
                           "</head><body</body></html>\n");

        $info = $this->service->discover(new Identifier\UserSupplied(self::ID));

        $this->assertInstanceOf('\Zend\OpenId\Discovery\Information', $info);
        $this->assertSame(self::OP_ENDPOINT, $info->getEndpointUrl() );
        $this->assertSame(self::ID_OPLOCAL, $info->getLocalIdentifier()->get());
        $this->assertSame(Discovery\Information::OPENID_20, $info->getProtocolVersion());

        $storedInfo = $this->service->discover(new Identifier\UserSupplied(self::ID));
        $this->assertEquals($info->getProtocolVersion(), $storedInfo->getProtocolVersion());
        $this->assertEquals($info->getEndpointUrl(), $storedInfo->getEndpointUrl());
        $this->assertEquals($info->getLocalIdentifier()->get(), $storedInfo->getLocalIdentifier()->get());
    }

}

