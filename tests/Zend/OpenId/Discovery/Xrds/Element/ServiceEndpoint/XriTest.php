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
namespace ZendTest\OpenId\Discovery\Xrds\Element\ServiceEndpoint;

use Zend\OpenId\OpenId,
    Zend\OpenId\Discovery\Xrds\Element,
    Zend\OpenId\Discovery\Xrds\Element\ServiceEndpoint\Xri as ServiceEndpoint,
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
        $elService = new ServiceEndpoint();
        $types = array(
            "http://lid.netmesh.org/sso/2.0",
            "http://lid.netmesh.org/sso/1.0"
        );
        $uris = array(
            "http://www.livejournal.com/openid/server.bml"
        );

        $elService
            ->addType($types[0])
            ->addType($types[1]) // overrides previously set value
            ->addUri($uris[0]);

        $this->assertFalse($elService->hasType($types[0]));
        $this->assertSame($types[1], $elService->getType());
        $this->assertSame($uris[0], $elService->getUri());
        
        // removal
        $this->assertTrue($elService->hasType($types[1]));
        $this->assertSame($uris[0], $elService->getUri());
        $elService
            ->removeType($types[1])
            ->removeUri($uris[0]);
        $this->assertFalse($elService->hasType($types[1]));
        $this->assertNull($elService->getType());
        $this->assertNull($elService->getUri());

        // reset and mass add
        $elService
            ->addType($types[0]);
        $this->assertTrue($elService->hasType($types[0]));
        $elService->reset();
        $this->assertFalse($elService->hasType($types[0]));
        $this->assertNull($elService->getType());
        $this->assertNull($elService->getUri());

        $elService
            ->setTypes($types)
            ->setUris($uris);
        $this->assertFalse($elService->hasType($types[0]));
        $this->assertSame($types[1], $elService->getType());
        $this->assertSame(array_slice($types, -1), $elService->getTypes());
        $this->assertSame($uris, $elService->getUris());
    }

    public function testReset()
    {
        $elService = new ServiceEndpoint();
        $types = array(
            "http://lid.netmesh.org/sso/2.0",
            "http://lid.netmesh.org/sso/1.0"
        );
        $uris = array(
            "http://www.livejournal.com/openid/server.bml"
        );

        $elService
            ->addType($types[0])
            ->addUri($uris[0]);

        // reset types
        $elServiceCur = clone $elService;
        $this->assertSame($types[0], $elServiceCur->getType());
        $this->assertSame($uris[0], $elServiceCur->getUri());
        $this->assertSame(array_slice($types, 0, 1), $elServiceCur->getTypes());
        $this->assertSame($uris, $elServiceCur->getUris());
        $elServiceCur
            ->reset(ServiceEndpoint::RESET_TYPES);
        $this->assertNull($elServiceCur->getType());
        $this->assertSame($uris[0], $elServiceCur->getUri());
        $this->assertSame(array(), $elServiceCur->getTypes());
        $this->assertSame($uris, $elServiceCur->getUris());

        // reset uris
        $elServiceCur = clone $elService;
        $this->assertSame($types[0], $elServiceCur->getType());
        $this->assertSame($uris[0], $elServiceCur->getUri());
        $this->assertSame(array_slice($types, 0, 1), $elServiceCur->getTypes());
        $this->assertSame($uris, $elServiceCur->getUris());
        $elServiceCur
            ->reset(ServiceEndpoint::RESET_URIS);
        $this->assertSame($types[0], $elServiceCur->getType());
        $this->assertNull($elServiceCur->getUri());
        $this->assertSame(array_slice($types, 0, 1), $elServiceCur->getTypes());
        $this->assertSame(array(), $elServiceCur->getUris());

        // reset all
        $elServiceCur = clone $elService;
        $this->assertSame($types[0], $elServiceCur->getType());
        $this->assertSame($uris[0], $elServiceCur->getUri());
        $this->assertSame(array_slice($types, 0, 1), $elServiceCur->getTypes());
        $this->assertSame($uris, $elServiceCur->getUris());
        $elServiceCur
            ->reset(ServiceEndpoint::RESET_ALL);
        $this->assertNull($elServiceCur->getType());
        $this->assertNull($elServiceCur->getUri());
        $this->assertSame(array(), $elServiceCur->getTypes());
        $this->assertSame(array(), $elServiceCur->getUris());

        // reset types and uris
        $elServiceCur = clone $elService;
        $this->assertSame($types[0], $elServiceCur->getType());
        $this->assertSame($uris[0], $elServiceCur->getUri());
        $this->assertSame(array_slice($types, 0, 1), $elServiceCur->getTypes());
        $this->assertSame($uris, $elServiceCur->getUris());
        $elServiceCur
            ->reset(ServiceEndpoint::RESET_TYPES | ServiceEndpoint::RESET_URIS);
        $this->assertNull($elServiceCur->getType());
        $this->assertNull($elServiceCur->getUri());
        $this->assertSame(array(), $elServiceCur->getTypes());
        $this->assertSame(array(), $elServiceCur->getUris());
    }

    public function testTypeSingularity()
    {
        $elService = new ServiceEndpoint();
        $types = array(
            "http://lid.netmesh.org/sso/2.0",
            "http://lid.netmesh.org/sso/1.0"
        );
        $uris = array(
            "http://www.livejournal.com/openid/server.bml"
        );

        $elService
            ->addType($types[0])
            ->addType($types[1]) // overrides previously set value
            ->addUri($uris[0]);
        $this->assertFalse($elService->hasType($types[0]));
        $this->assertSame($types[1], $elService->getType());
        $this->assertSame($uris[0], $elService->getUri());
        $this->setExpectedException(
            '\Zend\OpenId\Discovery\Xrds\Exception\ElementFailsValidationException', 
            'XRI 2.0 Resolution specified 0 or 1 modifier for xrid:XRIDescriptor/xrid:Service/xrid:Type');
        $elService->getType("non-existent-key");
    }

    public function testHash()
    {
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
            ->addType($types[0])
            ->addType($types[1])
            ->addUri($uris[0]);
        $elService2
            ->addType($types[0])
            ->addUri($uris[0]);
        $elService3
            ->addType($types[0])
            ->addType($types[1])
            ->addUri($uris[0]);

        // services having same types and uris must have identical hash
        $this->assertSame($elService1->getHash(), $elService3->getHash());
        $this->assertTrue($elService1->getHash() != $elService2->getHash());
    }
}

