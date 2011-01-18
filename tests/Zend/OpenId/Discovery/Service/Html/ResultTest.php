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
    Zend\OpenId\Identifier,
    Zend\OpenId\Discovery\Service\Html as HtmlDiscovery,
    Zend\OpenId\Discovery;

/**
 * @see \Zend\OpenId\Discovery\Information
 * @see \Zend\OpenId\Discovery\Service\Result
 * @see \Zend\OpenId\Discovery\Service\Html\Result
 */

/**
 * @category   Zend
 * @package    Zend_OpenId
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_OpenId
 */
class ResultTest extends \PHPUnit_Framework_TestCase
{
    const ID_USER_SUPPLIED = 'http://zftest.myopenid.com';

    public function testConstruction()
    {
        // w/o params
        $info = new HtmlDiscovery\Result();
        $this->assertNull($info->getEndpointUrl());
        $this->assertEquals(Discovery\Information::OPENID_20, $info->getProtocolVersion());
        $this->assertNull($info->getClaimedIdentifier());
        $this->assertNull($info->getLocalIdentifier());
        $this->assertNull($info->getSuppliedIdentifier());


        // w/ params
        $id = new Identifier\UserSupplied(self::ID_USER_SUPPLIED);

        $info = new HtmlDiscovery\Result($id);
        $this->assertInstanceOf('Zend\OpenId\Identifier', $info->getSuppliedIdentifier());
        $this->assertEquals($id->get(), $info->getSuppliedIdentifier()->get());

    }

    public function testInterface()
    {
        $id = new Identifier\UserSupplied(self::ID_USER_SUPPLIED);
        $idClaimed = new Identifier\UserSupplied('op_local_id');
        $idLocal = new Identifier\UserSupplied('claimed_id');
        $idSupplied = new Identifier\UserSupplied('supplied_id');

        $info = new HtmlDiscovery\Result($id);

        // test method chaining
        $this->assertInstanceOf('Zend\OpenId\Discovery\Information', $info->setEndpointUrl('http://myopenid.com'));
        $this->assertInstanceOf('Zend\OpenId\Discovery\Information', $info->setProtocolVersion('http://myopenid.com'));
        $this->assertInstanceOf('Zend\OpenId\Discovery\Information', $info->setClaimedIdentifier($id));
        $this->assertInstanceOf('Zend\OpenId\Discovery\Information', $info->setLocalIdentifier($id));
        $this->assertInstanceOf('Zend\OpenId\Discovery\Information', $info->setSuppliedIdentifier($id));

        $info->setEndpointUrl('http://myopenid.com')
            ->setProtocolVersion(Discovery\Information::OPENID_11)
            ->setClaimedIdentifier($idClaimed)
            ->setLocalIdentifier($idLocal)
            ->setSuppliedIdentifier($idSupplied);

        $this->assertEquals('http://myopenid.com', $info->getEndpointUrl());
        $this->assertEquals(Discovery\Information::OPENID_11, $info->getProtocolVersion());

        $this->assertInstanceOf('Zend\OpenId\Identifier', $info->getClaimedIdentifier());
        $this->assertEquals($idClaimed->get(), $info->getClaimedIdentifier()->get());

        $this->assertInstanceOf('Zend\OpenId\Identifier', $info->getLocalIdentifier());
        $this->assertEquals($idLocal->get(), $info->getLocalIdentifier()->get());
        $this->assertInstanceOf('Zend\OpenId\Identifier', $info->getSuppliedIdentifier());
        $this->assertEquals($idSupplied->get(), $info->getSuppliedIdentifier()->get());
    }

    public function testSerialization()
    {
        $id = new Identifier\UserSupplied(self::ID_USER_SUPPLIED);
        $idClaimed = new Identifier\UserSupplied('op_local_id');
        $idLocal = new Identifier\UserSupplied('claimed_id');
        $idSupplied = new Identifier\UserSupplied('supplied_id');


        $info = new HtmlDiscovery\Result($id);

        $info->setEndpointUrl('http://myopenid.com')
            ->setProtocolVersion(Discovery\Information::OPENID_11)
            ->setClaimedIdentifier($idClaimed)
            ->setLocalIdentifier($idLocal)
            ->setSuppliedIdentifier($idSupplied);

        $data = serialize($info);
        $newInfo = unserialize($data);

        $this->assertInstanceOf('Zend\OpenId\Discovery\Information', $newInfo);
        $this->assertEquals($info->getEndpointUrl(), $newInfo->getEndpointUrl());
        $this->assertEquals($info->getProtocolVersion(), $newInfo->getProtocolVersion());
        $this->assertEquals($info->getClaimedIdentifier(), $newInfo->getClaimedIdentifier());
        $this->assertEquals($info->getLocalIdentifier(), $newInfo->getLocalIdentifier());
        $this->assertEquals($info->getSuppliedIdentifier(), $newInfo->getSuppliedIdentifier());
    }
}
