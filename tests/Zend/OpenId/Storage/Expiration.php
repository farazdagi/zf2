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
namespace ZendTest\OpenId\Storage;

use Zend\OpenId\OpenId,
    Zend\OpenId\Storage,
    Zend\OpenId\Storage\Exception;

/**
 * @see OpenId\Storage\Expiration
 */

/**
 * @category   Zend
 * @package    Zend_OpenId
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_OpenId
 */
class FileTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructedObject()
    {
        // w/o params
        $expiration = new Storage\Expiration();
        $this->assertNull($expiration->get());
        $this->assertFalse($expiration->isExpired());

        // w/ params
        $expireTill = time() + 60;
        $expiration = new Storage\Expiration($expireTill);
        $this->assertEquals($expireTill, $expiration->get());


        // make sure that chaining methods are actually working
        $this->assertInstanceOf('Zend\OpenId\Storage\Expiration', $expiration->set(42));

    }

    public function testGetSetExpiring()
    {
        $expireTillA = time() + 60;  
        $expireTillB = time() + 1; // in one second 

        $expiration = new Storage\Expiration($expireTillA);
        $this->assertEquals($expireTillA, $expiration->get());

        $expiration->set($expireTillB);
        $this->assertEquals($expireTillB, $expiration->get());

        // test expiry
        $expiration->set(time() + 1);
        $this->assertFalse($expiration->isExpired());
        $this->assertTrue($expiration->isNotExpired());
        sleep(2);
        $this->assertTrue($expiration->isExpired());
        $this->assertFalse($expiration->isNotExpired());
    }

    public function testSerialization()
    {
        $expireTill = time() + 1;  

        $expiration = new Storage\Expiration($expireTill);
        $this->assertEquals($expireTill, $expiration->get());
        sleep(2);
        $this->assertTrue($expiration->isExpired());
        $this->assertFalse($expiration->isNotExpired());

        $serializedExpiration = unserialize(serialize($expiration));
        $this->assertEquals($expireTill, $expiration->get());
        $this->assertTrue($expiration->isExpired());
        $this->assertFalse($expiration->isNotExpired());
    }

}
