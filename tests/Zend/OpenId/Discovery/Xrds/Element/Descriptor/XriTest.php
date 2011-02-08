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
namespace ZendTest\OpenId\Discovery\Xrds\Element\Descriptor;

use Zend\OpenId\OpenId,
    Zend\OpenId\Discovery\Xrds\Element,
    Zend\OpenId\Discovery\Xrds\Element\ServiceEndpoint\Xri as ServiceEndpoint,
    Zend\OpenId\Discovery\Xrds\Element\Descriptor\Xri as Descriptor,
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
    public function testInitialization()
    {
        $elDescriptor = new Descriptor();
        $elService1 = new ServiceEndpoint();
        $elService2 = new ServiceEndpoint();
        $elService3 = new ServiceEndpoint();

        $types = array(
            "http://lid.netmesh.org/sso/2.0",
            "http://lid.netmesh.org/sso/1.0"
        );
        $uris = array(
            "http://www.livejournal.com/openid/server.bml",
        );

        $elService1
            ->setPriority(10)
            ->addType($types[0])
            ->addType($types[1])
            ->addUri($uris[0]);
        $elService2
            ->setPriority(20)
            ->addType($types[0])
            ->addUri($uris[0]);
        $elService3
            ->setPriority(30)
            ->addType($types[1])
            ->addUri($uris[0]);

        $elDescriptor
            ->addService($elService1)
            ->addService($elService2)
            ->addService($elService3);

        // get all services
        $services = $elDescriptor->getServices();
        $this->assertTrue(is_array($services));
        $this->assertEquals(3, count($services));

        $this->assertTrue($services[0]->hasType($types[0]));
        $this->assertTrue($services[0]->hasType($types[1]));
        $this->assertSame($uris[0], $services[0]->getUri());
        $this->assertSame(10, $services[0]->getPriority());

        $this->assertTrue($services[1]->hasType($types[0]));
        $this->assertFalse($services[1]->hasType($types[1]));
        $this->assertSame($uris[0], $services[1]->getUri());
        $this->assertSame(20, $services[1]->getPriority());

        $this->assertFalse($services[2]->hasType($types[0]));
        $this->assertTrue($services[2]->hasType($types[1]));
        $this->assertSame($uris[0], $services[2]->getUri());
        $this->assertSame(30, $services[2]->getPriority());

        // get all by passing type array
        $services = $elDescriptor->getServices($types);
        $this->assertTrue(is_array($services));
        $this->assertEquals(3, count($services));

        $this->assertTrue($services[0]->hasType($types[0]));
        $this->assertTrue($services[0]->hasType($types[1]));
        $this->assertSame($uris[0], $services[0]->getUri());
        $this->assertSame(10, $services[0]->getPriority());

        $this->assertTrue($services[1]->hasType($types[0]));
        $this->assertFalse($services[1]->hasType($types[1]));
        $this->assertSame($uris[0], $services[1]->getUri());
        $this->assertSame(20, $services[1]->getPriority());

        $this->assertFalse($services[2]->hasType($types[0]));
        $this->assertTrue($services[2]->hasType($types[1]));
        $this->assertSame($uris[0], $services[2]->getUri());
        $this->assertSame(30, $services[2]->getPriority());

        // get single type service - type[0]
        $services = $elDescriptor->getServices($types[0]);
        $this->assertTrue(is_array($services));
        $this->assertEquals(2, count($services));

        $this->assertTrue($services[0]->hasType($types[0]));
        $this->assertTrue($services[0]->hasType($types[1]));
        $this->assertSame($uris[0], $services[0]->getUri());
        $this->assertSame(10, $services[0]->getPriority());

        $this->assertTrue($services[1]->hasType($types[0]));
        $this->assertFalse($services[1]->hasType($types[1]));
        $this->assertSame($uris[0], $services[1]->getUri());
        $this->assertSame(20, $services[1]->getPriority());

        // get single type service - type[1]
        $services = $elDescriptor->getServices($types[1]);
        $this->assertTrue(is_array($services));
        $this->assertEquals(2, count($services));

        $this->assertTrue($services[0]->hasType($types[0]));
        $this->assertTrue($services[0]->hasType($types[1]));
        $this->assertSame($uris[0], $services[0]->getUri());
        $this->assertSame(10, $services[0]->getPriority());

        $this->assertFalse($services[1]->hasType($types[0]));
        $this->assertTrue($services[1]->hasType($types[1]));
        $this->assertSame($uris[0], $services[1]->getUri());
        $this->assertSame(30, $services[1]->getPriority());

    }
}

