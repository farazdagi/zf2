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
    Zend\OpenId\Discovery\Information as DiscoveryInformation,
    Zend\OpenId\Discovery\Xrds\Element,
    Zend\OpenId\Discovery\Xrds\Parser\Xri as Parser,
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
class XriTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {}

    public function testServicesXml()
    {
        $parser = new Parser();
        $xrds =  file_get_contents(dirname(__FILE__) . '/xrds_files/xri.services.xml');
        $descriptor = $parser->parse($xrds);

        $this->assertInstanceOf('\Zend\OpenId\Discovery\Xrds\Element\Descriptor', $descriptor);
        
        // get all services
        $services = $descriptor->getServices();

        $this->assertTrue(is_array($services));
        $this->assertSame(3, count($services));

        list($s1, $s2, $s3) = $services;

        $this->assertSame(DiscoveryInformation::OPENID_10, $s1->getType());
        $this->assertSame('http://www.myopenid.com/server', $s1->getUri());

        $this->assertSame('http://my.blog/url', $s2->getUri());

        $this->assertSame('skype:call?myskypeusername', $s3->getUri());

    }

    public function testXrdXml()
    {
        $parser = new Parser();
        $xrds =  file_get_contents(dirname(__FILE__) . '/xrds_files/xri.xrd.xml');
        $descriptor = $parser->parse($xrds);

        $this->assertInstanceOf('\Zend\OpenId\Discovery\Xrds\Element\Descriptor', $descriptor);
        
        // get all services
        $services = $descriptor->getServices();

        $this->assertTrue(is_array($services));
        $this->assertSame(2, count($services));

        list($s1, $s2) = $services;

        $this->assertSame('xri://+i-service*(+forwarding)*($v*1.0)', $s1->getType());
        $this->assertSame('http://1id.com/', $s1->getUri());
        $this->assertSame(10, $s1->getPriority());

        $this->assertSame('xri://+i-service*(+contact)*($v*1.0)', $s2->getType());
        $this->assertSame('http://1id.com/contact/', $s2->getUri());
        $this->assertSame(20, $s2->getPriority());

    }

    public function testXrdsXml()
    {
        $parser = new Parser();
        $xrds =  file_get_contents(dirname(__FILE__) . '/xrds_files/xri.xrds.xml');
        $descriptor = $parser->parse($xrds);

        $this->assertInstanceOf('\Zend\OpenId\Discovery\Xrds\Element\Descriptor', $descriptor);
        
        // get all services
        $services = $descriptor->getServices();

        $this->assertTrue(is_array($services));
        $this->assertSame(5, count($services));

        list($s1, $s2) = $services;

        $this->assertSame('xri://+i-service*(+contact)*($v*1.0)', $s1->getType());
        $this->assertSame('http://1id.com/contact/', $s1->getUri());
        $this->assertSame(10, $s1->getPriority());

        $this->assertSame('http://openid.net/signon/1.0', $s2->getType());
        $this->assertSame('https://1id.com/sso/', $s2->getUri());
        $this->assertSame(20, $s2->getPriority());
    }

    public function testNoServicesXml()
    {
        $parser = new Parser();
        $xrds =  file_get_contents(dirname(__FILE__) . '/xrds_files/xri.noservices.xml');
        $descriptor = $parser->parse($xrds);

        $this->assertInstanceOf('\Zend\OpenId\Discovery\Xrds\Element\Descriptor', $descriptor);

        // get all services
        $services = $descriptor->getServices();

        $this->assertTrue(is_array($services));
        $this->assertSame(0, count($services));
    }

    public function testDescriptorStatus()
    {
        $parser = new Parser();
        $xrds =  file_get_contents(dirname(__FILE__) . '/xrds_files/xri.notfound.xml');
        $descriptor = $parser->parse($xrds);

        $this->assertInstanceOf('\Zend\OpenId\Discovery\Xrds\Element\Descriptor', $descriptor);

        $this->assertSame(222, $descriptor->getStatus());
    }

    public function testDescriptorElementNotFoundException()
    {
        $this->setExpectedException(
            'Zend\OpenId\Discovery\Xrds\Parser\Exception\ElementNotFoundException', 
            "XRD element cannot be located in input XRDS");

        $parser = new Parser();
        $xrds =  file_get_contents(dirname(__FILE__) . '/xrds_files/xri.xrd.notfound.xml');
        $descriptor = $parser->parse($xrds);
    }

    public function testParseFailedException()
    {
        $this->setExpectedException(
            'Zend\OpenId\Discovery\Xrds\Parser\Exception\ParseFailedException', 
            "Couldn't find end of Start Tag XRDS");

        $parser = new Parser();
        $xrds =  file_get_contents(dirname(__FILE__) . '/xrds_files/xri.broken.xml');
        $descriptor = $parser->parse($xrds);
    }
}

