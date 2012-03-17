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
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * @namespace
 */
namespace ZendTest\OpenId\Message;

use PHPUnit_Framework_TestCase as TestCase,
    Zend\OpenId\OpenId,
    Zend\OpenId\Message\Message as MessageService,
    Zend\OpenId\Message as Message;

/**
 * @category   Zend
 * @package    Zend_OpenId
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_OpenId
 */
class EncoderTest extends TestCase
{
    protected $broker;

    protected $items;
    protected $encodedKeyValue;
    protected $encodedHttp;
    protected $encodedArray;

    public function setUp()
    {
        $this->broker = MessageService::getEncoderBroker();
        $this->items = array(
            'foo' => 'bar',
            'baz' => 42,
            'message' => 'Some error message'
        );
        $this->encodedKeyValue =
            'foo:bar' . "\n" .
            'baz:42' . "\n".
            'message:Some error message' . "\n"
        ;
        $this->encodedHttp = 'openid.foo=bar&openid.baz=42&openid.message=Some%20error%20message';
        $this->encodedArray = array(
            'foo' => 'bar',
            'baz' => 42,
            'message' => 'Some error message'
        );
    }

    public function testKeyValueEncoderEncode()
    {
        $encoder = $this->broker->load('keyvalue');
        $this->assertInstanceOf('Zend\OpenId\Message\Encoder\KeyValue', $encoder);

        $this->assertSame($this->encodedKeyValue, $encoder->encode($this->items));
    }

    public function testKeyValueEncoderDecode()
    {
        $encoder = $this->broker->load('keyvalue');
        $this->assertInstanceOf('Zend\OpenId\Message\Encoder\KeyValue', $encoder);

        $decoded = $encoder->decode($this->encodedKeyValue);
        $this->assertEquals($this->items, $decoded);
        $this->assertArrayHasKey('foo', $decoded);
        $this->assertArrayHasKey('baz', $decoded);
    }

    public function testHttpEncoderEncode()
    {
        $encoder = $this->broker->load('http');
        $this->assertInstanceOf('Zend\OpenId\Message\Encoder\Http', $encoder);

        $this->assertSame($this->encodedHttp, $encoder->encode($this->items));
    }

    public function testHttpEncoderDecode()
    {
        $encoder = $this->broker->load('http');
        $this->assertInstanceOf('Zend\OpenId\Message\Encoder\Http', $encoder);

        $decoded = $encoder->decode($this->encodedHttp);
        $this->assertEquals($this->items, $decoded);
        $this->assertArrayHasKey('foo', $decoded);
        $this->assertArrayHasKey('baz', $decoded);
    }

    public function testArrayEncoderEncode()
    {
        $encoder = $this->broker->load('array');
        $this->assertInstanceOf('Zend\OpenId\Message\Encoder\AsArray', $encoder);

        $this->assertSame($this->encodedArray, $encoder->encode($this->items));
    }

    public function testArrayEncoderDecode()
    {
        $encoder = $this->broker->load('array');
        $this->assertInstanceOf('Zend\OpenId\Message\Encoder\AsArray', $encoder);

        $decoded = $encoder->decode($this->encodedArray);
        $this->assertEquals($this->items, $decoded);
        $this->assertArrayHasKey('foo', $decoded);
        $this->assertArrayHasKey('baz', $decoded);
    }
}

