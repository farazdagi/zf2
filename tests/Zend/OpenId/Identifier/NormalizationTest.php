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
namespace ZendTest\OpenId\Identifier;

use Zend\OpenId\OpenId,
    Zend\OpenId\Identifier;

/**
 * @category   Zend
 * @package    Zend_OpenId
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_OpenId
 */
class EncodingTest extends \PHPUnit_Framework_TestCase
{
    public function testUserSuppliedId()
    {
        $id = new Identifier\UserSupplied();
        $this->assertNull($id->get());
        $this->assertEquals($id->set('a')->get(), 'a');

    }

    public function testClaimedId()
    {
        $this->markTestIncomplete();
    }

    public function testClaimedForAuthorizationRequestId()
    {
        $this->markTestIncomplete();
    }

    public function testClaimedForPositiveAssertionId()
    {
        $this->markTestIncomplete();
    }

    /**
     * testing testNormalizeUrl
     *
     */
    public function testNormalizeUrl()
    {
        $id = new Identifier\Claimed();
        $url = 'example://a/b/c/%7Bfoo%7D';
        $this->assertSame($id->set($url)->get(), $url);

        $url = 'eXAMPLE://A/./b/../b/%63/%7bfoo%7d';
        $this->assertSame($id->set($url)->get(), 'example://a/b/c/%7Bfoo%7D');

        $url = 'eXAMPLE://A/./b/../b/%63/%bbfoo%Bd';
        $this->assertSame($id->set($url)->get(), 'example://a/b/c/%BBfoo%BD');

        $url = 'example://a/b/c/%1';
        $this->assertNull($id->set($url)->get());

        $url = 'example://a/b/c/%x1';
        $this->assertNull($id->set($url)->get());

        $url = 'example://a/b/c/%1x';
        $this->assertNull($id->set($url)->get());

        $url = 'eXAMPLE://A/b/c/x%20y';
        $this->assertSame($id->set($url)->get(), 'example://a/b/c/x%20y');

        $url = 'example://host/.a/b/c';
        $this->assertSame($id->set($url)->get(), 'example://host/.a/b/c');

        $url = 'a/b/c';
        $this->assertSame($id->set($url)->get(), 'http://a/b/c');

        $url = 'example://:80/a/b/c';
        $this->assertNull($id->set($url)->get());

        $url = 'example://host/a/.b/c';
        $this->assertSame($id->set($url)->get(), 'example://host/a/.b/c');

        $url = 'example://host/a/b/.c';
        $this->assertSame($id->set($url)->get(), 'example://host/a/b/.c');

        $url = 'example://host/..a/b/c';
        $this->assertSame($id->set($url)->get(), 'example://host/..a/b/c');

        $url = 'example://host/a/..b/c';
        $this->assertSame($id->set($url)->get(), 'example://host/a/..b/c');

        $url = 'example://host/a/b/..c';
        $this->assertSame($id->set($url)->get(), 'example://host/a/b/..c');

        $url = 'example://host/./b/c';
        $this->assertSame($id->set($url)->get(), 'example://host/b/c');

        $url = 'example://host/a/./c';
        $this->assertSame($id->set($url)->get(), 'example://host/a/c');

        $url = 'example://host/a/b/.';
        $this->assertSame($id->set($url)->get(), 'example://host/a/b');

        $url = 'example://host/a/b/./';
        $this->assertSame($id->set($url)->get(), 'example://host/a/b/');

        $url = 'example://host/../b/c';
        $this->assertSame($id->set($url)->get(), 'example://host/b/c');

        $url = 'example://host/a/../c';
        $this->assertSame($id->set($url)->get(), 'example://host/c');

        $url = 'example://host/a/b/..';
        $this->assertSame($id->set($url)->get(), 'example://host/a');

        $url = 'example://host/a/b/../';
        $this->assertSame($id->set($url)->get(), 'example://host/a/');

        $url = 'example://host/a/b/c/..';
        $this->assertSame($id->set($url)->get(), 'example://host/a/b');

        $url = 'example://host/a/b/c/../..';
        $this->assertSame($id->set($url)->get(), 'example://host/a');

        $url = 'example://host/a/b/c/../../..';
        $this->assertSame($id->set($url)->get(), 'example://host/');

        $url = 'example://host///a///b///c///..///../d';
        $this->assertSame($id->set($url)->get(), 'example://host/a/d');

        $url = 'example://host///a///b///c///.///./d';
        $this->assertSame($id->set($url)->get(), 'example://host/a/b/c/d');

        $url = 'example://host///a///b///c///..///./d';
        $this->assertSame($id->set($url)->get(), 'example://host/a/b/d');

        $url = 'example://host///a///b///c///.///../d';
        $this->assertSame($id->set($url)->get(), 'example://host/a/b/d');

        $url = 'http://example.com';
        $this->assertSame($id->set($url)->get(), 'http://example.com/');

        $url = 'http://example.com/';
        $this->assertSame($id->set($url)->get(), 'http://example.com/');

        $url = 'http://example.com:';
        $this->assertSame($id->set($url)->get(), 'http://example.com/');

        $url = 'http://example.com:80/';
        $this->assertSame($id->set($url)->get(), 'http://example.com/');

        $url = 'https://example.com:443/';
        $this->assertSame($id->set($url)->get(), 'https://example.com/');

        $url = 'http://example.com?';
        $this->assertSame($id->set($url)->get(), 'http://example.com/?');

        $url = 'http://example.com/?';
        $this->assertSame($id->set($url)->get(), 'http://example.com/?');

        $url = 'http://example.com/test.php?Foo=Bar#Baz';
        $this->assertSame($id->set($url)->get(), 'http://example.com/test.php?Foo=Bar');
    }

    /**
     * testing testNormalize
     *
     */
    public function testNormalize()
    {
        $url = '';
        $this->assertTrue( OpenId::normalize($url) );
        $this->assertSame( '', $url );

        $url = ' localhost ';
        $this->assertTrue( OpenId::normalize($url) );
        $this->assertSame( 'http://localhost/', $url );

        $url = 'xri://$ip*127.0.0.1';
        $this->assertTrue( OpenId::normalize($url) );
        $this->assertSame( 'http://127.0.0.1/', $url );

        $url = 'xri://$dns*localhost';
        $this->assertTrue( OpenId::normalize($url) );
        $this->assertSame( 'http://localhost/', $url );

        $url = 'xri://localhost';
        $this->assertTrue( OpenId::normalize($url) );
        $this->assertSame( 'http://localhost/', $url );

        $url = '=name';
        $this->assertTrue( OpenId::normalize($url) );
        $this->assertSame( '=name', $url );

        $url = '@name';
        $this->assertTrue( OpenId::normalize($url) );
        $this->assertSame( '@name', $url );

        $url = '+name';
        $this->assertTrue( OpenId::normalize($url) );
        $this->assertSame( '+name', $url );

        $url = '$name';
        $this->assertTrue( OpenId::normalize($url) );
        $this->assertSame( '$name', $url );

        $url = '!name';
        $this->assertTrue( OpenId::normalize($url) );
        $this->assertSame( '!name', $url );

        $url = 'localhost';
        $this->assertTrue( OpenId::normalize($url) );
        $this->assertSame( 'http://localhost/', $url );

        $url = 'http://localhost';
        $this->assertTrue( OpenId::normalize($url) );
        $this->assertSame( 'http://localhost/', $url );

        $url = 'https://localhost';
        $this->assertTrue( OpenId::normalize($url) );
        $this->assertSame( 'https://localhost/', $url );
    }
}

