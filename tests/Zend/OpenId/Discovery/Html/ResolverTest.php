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
namespace ZendTest\OpenId\Discovery;

use Zend\OpenId\OpenId,
    Zend\OpenId\Discovery\Service\Html\Resolver,
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
    /**
     * Test discovery component directly (w/o wrapping inside customer)
     */
    public function testDiscoveryComponent()
    {
        // setup HTTP client
        $http = new Http\Client(null,
            array(
                'maxredirects' => 4,
                'timeout'      => 15,
                'useragent'    => 'Zend_OpenId'
            )
        );
        $httpAdapter = new Http\Client\Adapter\Test();
        $http->setAdapter($httpAdapter);

        // setup cache storage
        $storage = new Storage\File(__DIR__."/_files/consumer");

        // setup discovery service
        $service = new Resolver();
        $service->setHttpClient($http)
                ->setStorage($storage);

        $params = array(
            'ClaimedId' => "http://id.myopenid.com/",
            'OPLocalId' => "http://torio.myopenid.com",
            'EndPoint' => "http://www.myopenid.com/"
        );
        $id = 'http://id.myopenid.com/';

        // Bad response
        $discoveryInfo = $service->discover(new Identifier\Claimed($id));
        var_dump($discoveryInfo);

        exit;
        $this->assertFalse( $service->discover(new Identifier\Claimed($params['ClaimedId'])) );
        return;

        // Test HTML based discovery (OpenID 1.1)
        $storage->delDiscoveryInfo(self::ID);
        $test->setResponse("HTTP/1.1 200 OK\r\n\r\n" .
                           "<html><head>\n" .
                           "<link rel=\"openid.server\" href=\"" . self::SERVER . "\">\n" .
                           "<link rel=\"openid.delegate\" href=\"" . self::REAL_ID . "\">\n" .
                           "</head><body</body></html>\n");
        $id = self::ID;
        $this->assertTrue( $consumer->discovery($id, $server, $version) );
        $this->assertSame( self::REAL_ID, $id );
        $this->assertSame( self::SERVER, $server );
        $this->assertSame( 1.1, $version );
    }

    /**
     * Ported from previous implementation
     */
    public function testDiscovery()
    {
        $this->markTestIncomplete();
        return;
        $storage = new Storage\File(__DIR__."/_files/consumer");
        $consumer = new ConsumerHelper($storage);
        $http = new Http\Client(null,
            array(
                'maxredirects' => 4,
                'timeout'      => 15,
                'useragent'    => 'Zend_OpenId'
            ));
        $test = new Http\Client\Adapter\Test();
        $http->setAdapter($test);
        $consumer->SetHttpClient($http);

        // Bad response
        $storage->delDiscoveryInfo(self::ID);
        $id = self::ID;
        $this->assertFalse( $consumer->discovery($id, $server, $version) );

        // Test HTML based discovery (OpenID 1.1)
        $storage->delDiscoveryInfo(self::ID);
        $test->setResponse("HTTP/1.1 200 OK\r\n\r\n" .
                           "<html><head>\n" .
                           "<link rel=\"openid.server\" href=\"" . self::SERVER . "\">\n" .
                           "<link rel=\"openid.delegate\" href=\"" . self::REAL_ID . "\">\n" .
                           "</head><body</body></html>\n");
        $id = self::ID;
        $this->assertTrue( $consumer->discovery($id, $server, $version) );
        $this->assertSame( self::REAL_ID, $id );
        $this->assertSame( self::SERVER, $server );
        $this->assertSame( 1.1, $version );

        // Test HTML based discovery (OpenID 1.1)
        $storage->delDiscoveryInfo(self::ID);
        $test->setResponse("HTTP/1.1 200 OK\r\n\r\n" .
                           "<html><head>\n" .
                           "<link href=\"" . self::SERVER . "\" rel=\"openid.server\">\n" .
                           "<link href=\"" . self::REAL_ID . "\" rel=\"openid.delegate\">\n" .
                           "</head><body</body></html>\n");
        $id = self::ID;
        $this->assertTrue( $consumer->discovery($id, $server, $version) );
        $this->assertSame( self::REAL_ID, $id );
        $this->assertSame( self::SERVER, $server );
        $this->assertSame( 1.1, $version );

        // Test HTML based discovery (OpenID 2.0)
        $storage->delDiscoveryInfo(self::ID);
        $test->setResponse("HTTP/1.1 200 OK\r\n\r\n" .
                           "<html><head>\n" .
                           "<link rel=\"openid2.provider\" href=\"" . self::SERVER . "\">\n" .
                           "<link rel=\"openid2.local_id\" href=\"" . self::REAL_ID . "\">\n" .
                           "</head><body</body></html>\n");
        $id = self::ID;
        $this->assertTrue( $consumer->discovery($id, $server, $version) );
        $this->assertSame( self::REAL_ID, $id );
        $this->assertSame( self::SERVER, $server );
        $this->assertSame( 2.0, $version );

        // Test HTML based discovery (OpenID 2.0)
        $storage->delDiscoveryInfo(self::ID);
        $test->setResponse("HTTP/1.1 200 OK\r\n\r\n" .
                           "<html><head>\n" .
                           "<link href=\"" . self::SERVER . "\" rel=\"openid2.provider\">\n" .
                           "<link href=\"" . self::REAL_ID . "\" rel=\"openid2.local_id\">\n" .
                           "</head><body</body></html>\n");
        $id = self::ID;
        $this->assertTrue( $consumer->discovery($id, $server, $version) );
        $this->assertSame( self::REAL_ID, $id );
        $this->assertSame( self::SERVER, $server );
        $this->assertSame( 2.0, $version );

        // Test HTML based discovery (OpenID 1.1 and 2.0)
        $storage->delDiscoveryInfo(self::ID);
        $test->setResponse("HTTP/1.1 200 OK\r\n\r\n" .
                           "<html><head>\n" .
                           "<link rel=\"openid2.provider\" href=\"" . self::SERVER . "\">\n" .
                           "<link rel=\"openid2.local_id\" href=\"" . self::REAL_ID . "\">\n" .
                           "<link rel=\"openid.server\" href=\"" . self::SERVER . "\">\n" .
                           "<link rel=\"openid.delegate\" href=\"" . self::REAL_ID . "\">\n" .
                           "</head><body</body></html>\n");
        $id = self::ID;
        $this->assertTrue( $consumer->discovery($id, $server, $version) );
        $this->assertSame( self::REAL_ID, $id );
        $this->assertSame( self::SERVER, $server );
        $this->assertSame( 2.0, $version );

        // Test HTML based discovery (OpenID 1.1) (single quotes)
        $storage->delDiscoveryInfo(self::ID);
        $test->setResponse("HTTP/1.1 200 OK\r\n\r\n" .
                           "<html><head>\n" .
                           "<link rel='openid.server' href='" . self::SERVER . "'>\n" .
                           "<link rel='openid.delegate' href='" . self::REAL_ID . "'>\n" .
                           "</head><body</body></html>\n");
        $id = self::ID;
        $this->assertTrue( $consumer->discovery($id, $server, $version) );
        $this->assertSame( self::REAL_ID, $id );
        $this->assertSame( self::SERVER, $server );
        $this->assertSame( 1.1, $version );

        // Test HTML based discovery (OpenID 1.1) (single quotes)
        $storage->delDiscoveryInfo(self::ID);
        $test->setResponse("HTTP/1.1 200 OK\r\n\r\n" .
                           "<html><head>\n" .
                           "<link href='" . self::SERVER . "' rel='openid.server'>\n" .
                           "<link href='" . self::REAL_ID . "' rel='openid.delegate'>\n" .
                           "</head><body</body></html>\n");
        $id = self::ID;
        $this->assertTrue( $consumer->discovery($id, $server, $version) );
        $this->assertSame( self::REAL_ID, $id );
        $this->assertSame( self::SERVER, $server );
        $this->assertSame( 1.1, $version );

        // Test HTML based discovery (OpenID 2.0) (single quotes)
        $storage->delDiscoveryInfo(self::ID);
        $test->setResponse("HTTP/1.1 200 OK\r\n\r\n" .
                           "<html><head>\n" .
                           "<link rel='openid2.provider' href='" . self::SERVER . "'>\n" .
                           "<link rel='openid2.local_id' href='" . self::REAL_ID . "'>\n" .
                           "</head><body</body></html>\n");
        $id = self::ID;
        $this->assertTrue( $consumer->discovery($id, $server, $version) );
        $this->assertSame( self::REAL_ID, $id );
        $this->assertSame( self::SERVER, $server );
        $this->assertSame( 2.0, $version );

        // Test HTML based discovery (OpenID 2.0) (single quotes)
        $storage->delDiscoveryInfo(self::ID);
        $test->setResponse("HTTP/1.1 200 OK\r\n\r\n" .
                           "<html><head>\n" .
                           "<link href='" . self::SERVER . "' rel='openid2.provider'>\n" .
                           "<link href='" . self::REAL_ID . "' rel='openid2.local_id'>\n" .
                           "</head><body</body></html>\n");
        $id = self::ID;
        $this->assertTrue( $consumer->discovery($id, $server, $version) );
        $this->assertSame( self::REAL_ID, $id );
        $this->assertSame( self::SERVER, $server );
        $this->assertSame( 2.0, $version );

        // Test HTML based discovery (OpenID 1.1 and 2.0) (single quotes)
        $storage->delDiscoveryInfo(self::ID);
        $test->setResponse("HTTP/1.1 200 OK\r\n\r\n" .
                           "<html><head>\n" .
                           "<link rel='openid2.provider' href='" . self::SERVER . "'>\n" .
                           "<link rel='openid2.local_id' href='" . self::REAL_ID . "'>\n" .
                           "<link rel='openid.server' href='" . self::SERVER . "'>\n" .
                           "<link rel='openid.delegate' href='" . self::REAL_ID . "'>\n" .
                           "</head><body</body></html>\n");
        $id = self::ID;
        $this->assertTrue( $consumer->discovery($id, $server, $version) );
        $this->assertSame( self::REAL_ID, $id );
        $this->assertSame( self::SERVER, $server );
        $this->assertSame( 2.0, $version );

        // Wrong HTML
        $storage->delDiscoveryInfo(self::ID);
        $test->setResponse("HTTP/1.1 200 OK\r\n\r\n" .
                           "<html><head>\n" .
                           "</head><body</body></html>\n");
        $id = self::ID;
        $this->assertFalse( $consumer->discovery($id, $server, $version) );

        // Test HTML based discovery with multivalue rel (OpenID 1.1)
        $storage->delDiscoveryInfo(self::ID);
        $test->setResponse("HTTP/1.1 200 OK\r\n\r\n" .
                           "<html><head>\n" .
                           "<link rel=\" aaa openid.server bbb \" href=\"" . self::SERVER . "\">\n" .
                           "<link rel=\"aaa openid.delegate\" href=\"" . self::REAL_ID . "\">\n" .
                           "</head><body</body></html>\n");
        $id = self::ID;
        $this->assertTrue( $consumer->discovery($id, $server, $version) );
        $this->assertSame( self::REAL_ID, $id );
        $this->assertSame( self::SERVER, $server );
        $this->assertSame( 1.1, $version );
    }
}

