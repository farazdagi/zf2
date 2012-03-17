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
 * @subpackage Zend_OpenId_Message
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * @namespace
 */
namespace Zend\OpenId\Message;

use Zend\OpenId\Exception;

/**
 * Facade to OpenId\Message package.
 *
 * @category   Zend
 * @package    Zend_OpenId
 * @subpackage Zend_OpenId_Message
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Message
{
    /**
     * @var AdapterBroker
     */
    protected static $adapterBroker;

    /**
     * @var EncoderBroker
     */
    protected static $encoderBroker;

    /**
     * Set adapter broker
     *
     * @param  AdapterBroker $broker
     * @return void
     */
    public static function setAdapterBroker($broker)
    {
        if (!$broker instanceof AdapterBroker) {
            throw new Exception\InvalidArgumentException(sprintf(
                'Adapter broker must extend AdapterBroker; received "%s"',
                (is_object($broker) ? get_class($broker) : gettype($broker))
            ));
        }
        self::$adapterBroker = $broker;
    }

    /**
     * Get adapter broker. Create if doesn't exist.
     *
     * @return AdapterBroker
     */
    public static function getAdapterBroker()
    {
        if (null === self::$adapterBroker) {
            self::setAdapterBroker(new AdapterBroker());
        }

        return self::$adapterBroker;
    }

    /**
     * Set encoder broker
     *
     * @param  EncoderBroker $broker
     * @return void
     */
    public static function setEncoderBroker($broker)
    {
        if (!$broker instanceof EncoderBroker) {
            throw new Exception\InvalidArgumentException(sprintf(
                'Encoder broker must extend EncoderBroker; received "%s"',
                (is_object($broker) ? get_class($broker) : gettype($broker))
            ));
        }
        self::$encoderBroker = $broker;
    }

    /**
     * Get encoder broker. Create if doesn't exist.
     *
     * @return EncoderBroker
     */
    public static function getEncoderBroker()
    {
        if (null === self::$encoderBroker) {
            self::setEncoderBroker(new EncoderBroker());
        }

        return self::$encoderBroker;
    }
}
