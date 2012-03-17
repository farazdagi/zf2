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
class AdapterBrokerTest extends TestCase
{
    protected $broker;

    public function setUp()
    {
        $this->broker = MessageService::getAdapterBroker();
    }

    public function testBrokerGetting()
    {
        $broker = MessageService::getAdapterBroker();
        $this->assertInstanceOf('Zend\OpenId\Message\AdapterBroker', $broker);
    }

    public function testBrokerSettingOk()
    {
        MessageService::setAdapterBroker(new CustomAdapterBroker);
        $broker = MessageService::getAdapterBroker();
        $this->assertInstanceOf('ZendTest\OpenId\Message\CustomAdapterBroker', $broker);
    }

    public function testBrokerSettingInvalidArgumentException()
    {
        $this->setExpectedException(
            'Zend\OpenId\Exception\InvalidArgumentException',
            'Adapter broker must extend AdapterBroker; received "stdClass"');
        MessageService::setAdapterBroker(new \StdClass);
    }

    public function testLoadHttpAdapter()
    {
        $this->assertInstanceOf('Zend\OpenId\Message\Adapter\Http', $this->broker->load('http'));
    }

    public function testLoadKeyValueAdapter()
    {
        $this->assertInstanceOf('Zend\OpenId\Message\Adapter\KeyValue', $this->broker->load('keyvalue'));
    }

    public function testPluginClassLoaderValidationOk()
    {
        $this->broker->register('something', new Message\Adapter\KeyValue);
        $this->assertInstanceOf('Zend\OpenId\Message\Adapter\KeyValue', $this->broker->load('something'));
    }

    public function testPluginClassLoaderValidationFailed()
    {
        $this->setExpectedException(
            'Zend\OpenId\Exception\InvalidArgumentException',
            'Message adapters must implement Zend\OpenId\Message\Adapter');
        $this->broker->register('something', 'StdClass');
    }
}

class CustomAdapterBroker extends Message\AdapterBroker
{}


