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
class EncoderBrokerTest extends TestCase
{
    protected $broker;

    public function setUp()
    {
        $this->broker = MessageService::getEncoderBroker();
    }

    public function testBrokerGetting()
    {
        $broker = MessageService::getEncoderBroker();
        $this->assertInstanceOf('Zend\OpenId\Message\EncoderBroker', $broker);
    }

    public function testBrokerSettingOk()
    {
        MessageService::setEncoderBroker(new CustomEncoderBroker);
        $broker = MessageService::getEncoderBroker();
        $this->assertInstanceOf('ZendTest\OpenId\Message\CustomEncoderBroker', $broker);
    }

    public function testBrokerSettingInvalidArgumentException()
    {
        $this->setExpectedException(
            'Zend\OpenId\Exception\InvalidArgumentException',
            'Encoder broker must extend EncoderBroker; received "stdClass"');
        MessageService::setEncoderBroker(new \StdClass);
    }

    public function testLoadArrayEncoder()
    {
        $this->assertInstanceOf('Zend\OpenId\Message\Encoder\AsArray', $this->broker->load('array'));
    }

    public function testLoadHttpEncoder()
    {
        $this->assertInstanceOf('Zend\OpenId\Message\Encoder\Http', $this->broker->load('http'));
    }

    public function testLoadKeyValueEncoder()
    {
        $this->assertInstanceOf('Zend\OpenId\Message\Encoder\KeyValue', $this->broker->load('keyvalue'));
    }

    public function testPluginClassLoaderValidationOk()
    {
        $this->broker->register('something', new Message\Encoder\AsArray);
        $this->assertInstanceOf('Zend\OpenId\Message\Encoder\AsArray', $this->broker->load('something'));
    }

    public function testPluginClassLoaderValidationFailed()
    {
        $this->setExpectedException(
            'Zend\OpenId\Exception\InvalidArgumentException',
            'Message encoders must implement Zend\OpenId\Message\Encoder');
        $this->broker->register('something', 'StdClass');
    }
}

class CustomEncoderBroker extends Message\EncoderBroker
{}


