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
namespace ZendTest\OpenId\Discovery\Service\Yadis;

use Zend\OpenId\OpenId,
    Zend\OpenId\Discovery\Service\Yadis\Resolver,
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
    const ID_CLAIMED = "http://www.phpmag.ru/openid/discovery/yadis/";
    const ID_OPLOCAL = "http://torio.myopenid.com";
    const OP_ENDPOINT = "http://www.myopenid.com/";

    /**
     * @var \Zend\OpenId\Discovery\Service
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
        $this->http = new Http\Client\Adapter\Curl();
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

    /**
     * @group cur
     */
    public function testXrdsLocationDiscovery()
    {
        $id = new Identifier\UserSupplied(self::ID_CLAIMED);

        $this->service->getStorage()->removeDiscoveryInformation($id);
        $this->assertNull($this->service->discover($id));

        $info = $this->service->discover($id);

        // HTML Document with <meta http-equiv="X-XRDS-Location">
        // HTTP Response Header with X-XRDS-Location Header + Body
        // HTTP Response Only
        // A document with MIME media type application/xrds+xml
    }

    public function testRecursiveDiscovery()
    {}

    public function testHttpClientMissingException()
    {
        $this->setExpectedException(
            '\Zend\OpenId\Discovery\Service\Exception\DependencyMissingException', 
            'HTTP client must be injected');
        $this->service->setHttpClient(null); // reset client

        $discoveryInfo = $this->service->discover(new Identifier\UserSupplied(self::ID));
    }

    //public function testHttpRequestFailedException()
    //{
        //$this->setExpectedException(
            //'\Zend\OpenId\Discovery\Service\Exception\HttpRequestFailedException', 
            //'HTTP Request failed');
        //$id = 'htttp://eror.in.address/some';
        //$this->service->getStorage()->removeDiscoveryInformation(new Identifier\UserSupplied($id));
        //$discoveryInfo = $this->service->discover(new Identifier\UserSupplied($id));
    //}

    //public function testDiscoveryFailedException()
    //{
        //$this->setExpectedException(
            //'\Zend\OpenId\Discovery\Service\Exception\DiscoveryFailedException', 
            //'Destination page not found or is empty');

        //$this->http->setResponse("HTTP/1.1 501 Not Implemented\r\n\r\n" .  "");
        //$this->service->getStorage()->removeDiscoveryInformation(new Identifier\UserSupplied(self::ID));
        //$discoveryInfo = $this->service->discover(new Identifier\UserSupplied(self::ID));
    //}

    //public function testStoredInfo()
    //{
        //$this->service->getStorage()->removeDiscoveryInformation(new Identifier\UserSupplied(self::ID));
        //$this->http->setResponse("HTTP/1.1 200 OK\r\n\r\n" .
                           //"<html><head>\n" .
                           //"<link rel=\"openid2.provider\" href=\"" . self::OP_ENDPOINT . "\">\n" .
                           //"<link rel=\"openid2.local_id\" href=\"" . self::ID_OPLOCAL . "\">\n" .
                           //"</head><body</body></html>\n");

        //$info = $this->service->discover(new Identifier\UserSupplied(self::ID));

        //$this->assertInstanceOf('\Zend\OpenId\Discovery\Information', $info);
        //$this->assertSame(self::OP_ENDPOINT, $info->getEndpointUrl() );
        //$this->assertSame(self::ID_OPLOCAL, $info->getLocalIdentifier()->get());
        //$this->assertSame(Discovery\Information::OPENID_20, $info->getProtocolVersion());

        //$storedInfo = $this->service->discover(new Identifier\UserSupplied(self::ID));
        //$this->assertEquals($info->getProtocolVersion(), $storedInfo->getProtocolVersion());
        //$this->assertEquals($info->getEndpointUrl(), $storedInfo->getEndpointUrl());
        //$this->assertEquals($info->getLocalIdentifier()->get(), $storedInfo->getLocalIdentifier()->get());
    //}

}

