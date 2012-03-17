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
class AdapterTest extends TestCase
{
    protected $adapters;
    protected $encoders;

    protected $items;
    protected $encodedKeyValue;
    protected $encodedHttp;

    public function setUp()
    {
        $this->adapters = MessageService::getAdapterBroker();
        $this->encoders = MessageService::getEncoderBroker();
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
    }

    public function testKeyValueMessage()
    {
        $message = $this->adapters->load('keyvalue');
        foreach($this->items as $k=>$v) {
            $message[$k] = $v;
        }
        $this->assertSame($this->encodedKeyValue, $message->encode());
    }

    public function testKeyValueMessageWithDifferentEncoder()
    {
        $message = $this->adapters->load('keyvalue');
        foreach($this->items as $k=>$v) {
            $message[$k] = $v;
        }
        $this->assertSame($this->encodedKeyValue, $message->encode());

        $message->setEncoder($this->encoders->load('http'));
        $this->assertSame($this->encodedHttp, $message->encode());
        $this->assertSame($this->encodedKeyValue, $message->encode($this->encoders->load('keyvalue')));
    }

    public function testHttpMessage()
    {
        $message = $this->adapters->load('http');
        foreach($this->items as $k=>$v) {
            $message[$k] = $v;
        }
        $this->assertSame($this->encodedHttp, $message->encode());
    }

    public function testHttpMessageWithDifferentEncoder()
    {
        $message = $this->adapters->load('http');
        foreach($this->items as $k=>$v) {
            $message[$k] = $v;
        }
        $this->assertSame($this->encodedHttp, $message->encode());

        $message->setEncoder($this->encoders->load('keyvalue'));
        $this->assertSame($this->encodedKeyValue, $message->encode());
        $this->assertSame($this->encodedHttp, $message->encode($this->encoders->load('http')));
    }

    public function testKeyValueEncoderNotSet()
    {
        $this->setExpectedException(
            'Zend\OpenId\Exception\RuntimeException',
            'Message encoder not set'
        );
        $message = $this->adapters->load('keyvalue');
        $message->setEncoder(null);
        $message->encode();
    }
}

