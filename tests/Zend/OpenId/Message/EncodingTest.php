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
namespace ZendTest\OpenId\Message;

use Zend\OpenId\OpenId,
    Zend\OpenId\Message as Message;

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

    public function testKeyValueEncoding()
    {
        $data = 'foo:bar' . "\n" . 'foo1:42' . "\n";
        $partialData = 'foo:bar' . "\n";
        $items = array(
            'foo' => 'bar',
            'foo1' => 42
        );
        // pass encoding as object
        $this->runCommonTests(Message\Encoding::TYPE_KEYVALUE, $data, $partialData, $items);
        // pass encoding as constant
        $this->runCommonTests(new Message\Encoding\KeyValue(), $data, $partialData, $items);
    }

    public function testHttpEncoding()
    {
        $data = 'foo=bar&foo1=42';
        $partialData = 'foo=bar';
        $items = array(
            'foo' => 'bar',
            'foo1' => 42
        );
        // pass encoding as object
        $this->runCommonTests(Message\Encoding::TYPE_HTTP, $data, $partialData, $items);
        // pass encoding as constant
        $this->runCommonTests(new Message\Encoding\Http(), $data, $partialData, $items);
    }
    
    public function testAsArrayEncoding()
    {
        $items = array(
            'foo' => 'bar',
            'foo1' => 42
        );
        $data = $items;
        $partialData = array_slice($data, 0, 1);
        // pass encoding as object
        $this->runCommonTests(Message\Encoding::TYPE_ARRAY, $data, $partialData, $items);
        // pass encoding as constant
        $this->runCommonTests(new Message\Encoding\AsArray(), $data, $partialData, $items);
    }


    public function testDefaultEncoding()
    {
        $message = new Message\Container\Standard();
        $this->assertTrue($message->getEncoding() instanceof \Zend\OpenId\Message\Encoding\AsArray);
    }

    /**
     * Simple method to ensure that where $this is guaranteed it is actually
     * gets returned (for chained methods)
     */
    public function testReturnValues()
    {
        $this->markTestIncomplete('Not impmlemented yet');
    }

    /**
     * Since encoding algorithms are essentially implementation of strategy
     * patter, once concrete algorithm is selected all the tests can be shared
     */
    private function runCommonTests($encoding, $data, $partialData, $items)
    {
        $message = new Message\Container\Standard($encoding);

        // check that encoding set correctly
        $this->assertTrue($message->getEncoding() instanceof \Zend\OpenId\Message\Encoding);

        // see whether encoding set through mutator works correctly
        $message->setEncoding($encoding);
        $this->assertTrue($message->getEncoding() instanceof \Zend\OpenId\Message\Encoding);

        // test item setters
        foreach($items as $k=>$v) {
            $message->setItem($k, $v);
        }

        $this->reviewContainer($message, $encoding, $data, $partialData, $items);

        // test the decoding
        $message = new Message\Container\Standard($encoding);
        $message->set($data);
        $this->reviewContainer($message, $encoding, $data, $partialData, $items);

        // test aliases
        $message = new Message\Container\Standard($encoding);
        $message->setMessage($data);
        $this->reviewContainer($message, $encoding, $data, $partialData, $items);

        // explicitly pass the encoding to setMessage()
        $message = new Message\Container\Standard($encoding);
        $message->setMessage($data, $encoding);
        $this->reviewContainer($message, $encoding, $data, $partialData, $items);

    }

    // set of tests to ensure the integrity of the container
    private function reviewContainer($message, $encoding, $data, $partialData,  $items)
    {
        // test compiled message
        $this->assertEquals($message->get(), $data);
        $this->assertEquals($message->getMessage(), $data);
        $this->assertEquals($message->getMessage($encoding), $data);

        // test contained items
        $this->assertEquals($message->getItem('foo'), $items['foo']);
        $this->assertEquals($message->getItem('foo1'), $items['foo1']);
        $this->assertEquals($message->getItem('undefined'), null);

        // test item unsetting
        $message->removeItem('foo1');
        $this->assertEquals($message->get(), $partialData);
        $this->assertEquals($message->getItem('foo1'), null);

        // test array access
        //$this->assertEquals($message->getItem('foo'), $message['foo']);
        //$this->assertEquals($message->getItem('foo1'), $message['foo1']);
        
    }
}

