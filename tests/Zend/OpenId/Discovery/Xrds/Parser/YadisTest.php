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
namespace ZendTest\OpenId\Discovery\Xrds\Parser;

use Zend\OpenId\OpenId,
    Zend\OpenId\Discovery\Information as DiscoveryInfo,
    Zend\OpenId\Discovery\Xrds\Element,
    Zend\OpenId\Discovery\Xrds\Parser\Yadis as Parser,
    Zend\OpenId\Discovery,
    Zend\OpenId\Identifier;

/**
 * @category   Zend
 * @package    Zend_OpenId
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_OpenId
 */
class YadisTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {}

    public function testParserSimpleYadis()
    {
        $types = array(
            "http://lid.netmesh.org/sso/2.0",
            "http://lid.netmesh.org/sso/1.0",
            "http://lid.netmesh.org/custom/2.0",
        );
        $uris = array(
            "http://www.test.ws/?id=1",
            "http://www.test.ws/?id=2",
        );

        $parser = new Parser();
        $xrds =  file_get_contents(dirname(__FILE__) . '/xrds_files/yadis.simple.xml');
        $descriptor = $parser->parse($xrds);

        $this->assertInstanceOf('\Zend\OpenId\Discovery\Xrds\Element\Descriptor', $descriptor);
        
        // get all services
        $services = $descriptor->getServices();
        $this->assertTrue(is_array($services));
        $this->assertEquals(3, count($services));

        $this->assertTrue($services[0]->hasType($types[0]));
        $this->assertFalse($services[0]->hasType($types[1]));
        $this->assertTrue($services[0]->hasType($types[2]));
        $this->assertSame($uris[0], $services[0]->getUri());
        $this->assertSame($uris[0], $services[0]->getUri(0));
        $this->assertSame($uris[1], $services[0]->getUri(1));
        $this->assertNull($services[0]->getPriority());

        $this->assertFalse($services[1]->hasType($types[0]));
        $this->assertTrue($services[1]->hasType($types[1]));
        $this->assertFalse($services[1]->hasType($types[2]));
        $this->assertSame($uris[0], $services[1]->getUri());
        $this->assertSame($uris[0], $services[1]->getUri(0));
        $this->assertNull($services[1]->getUri(1));
        $this->assertSame(10, $services[1]->getPriority());

        $this->assertFalse($services[2]->hasType($types[0]));
        $this->assertTrue($services[2]->hasType($types[1]));
        $this->assertFalse($services[2]->hasType($types[2]));
        $this->assertSame(array(), $services[2]->getUris());
        $this->assertNull($services[2]->getUri());
        $this->assertSame(20, $services[2]->getPriority());

        // get all by passing type array
        $services = $descriptor->getServices($types);
        $this->assertTrue(is_array($services));
        $this->assertEquals(3, count($services));

        $this->assertTrue($services[0]->hasType($types[0]));
        $this->assertFalse($services[0]->hasType($types[1]));
        $this->assertTrue($services[0]->hasType($types[2]));
        $this->assertSame($uris[0], $services[0]->getUri());
        $this->assertSame($uris[0], $services[0]->getUri(0));
        $this->assertSame($uris[1], $services[0]->getUri(1));
        $this->assertNull($services[0]->getPriority());

        $this->assertFalse($services[1]->hasType($types[0]));
        $this->assertTrue($services[1]->hasType($types[1]));
        $this->assertFalse($services[1]->hasType($types[2]));
        $this->assertSame($uris[0], $services[1]->getUri());
        $this->assertSame($uris[0], $services[1]->getUri(0));
        $this->assertNull($services[1]->getUri(1));
        $this->assertSame(10, $services[1]->getPriority());

        $this->assertFalse($services[2]->hasType($types[0]));
        $this->assertTrue($services[2]->hasType($types[1]));
        $this->assertFalse($services[2]->hasType($types[2]));
        $this->assertSame(array(), $services[2]->getUris());
        $this->assertNull($services[2]->getUri());
        $this->assertSame(20, $services[2]->getPriority());
    }

    public function testOpenIdService()
    {
        $uris = array(
            "http://www.myopenid.com/server",
            "http://www.livejournal.com/openid/server.bml",
        );

        $parser = new Parser();
        $xrds =  file_get_contents(
            dirname(__FILE__) . '/xrds_files/yadis.openid.xml');
        $descriptor = $parser->parse($xrds);

        $this->assertInstanceOf('\Zend\OpenId\Discovery\Xrds\Element\Descriptor', $descriptor);
        
        // get all services
        $services = $descriptor->getServices();
        $this->assertTrue(is_array($services));
        $this->assertEquals(4, count($services));

        $this->assertTrue($services[0]->hasType(DiscoveryInfo::OPENID_10));
        $this->assertSame($uris[0], $services[0]->getUri());

        $this->assertTrue($services[1]->hasType(DiscoveryInfo::OPENID_20));
        $this->assertSame($uris[1], $services[1]->getUri());
    }

    public function testParseFailedException()
    {
        $this->setExpectedException(
            '\Zend\OpenId\Discovery\Xrds\Exception\ParseFailedException', 
            "Couldn't find end of Start Tag XRDS");

        $parser = new Parser();
        $xrds =  file_get_contents(dirname(__FILE__) . '/xrds_files/yadis.broken.xml');
        $descriptor = $parser->parse($xrds);
    }
}

