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
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * @namespace
 */
namespace Zend\OpenId\Message\Encoding;
use Zend\OpenId\Message;

/**
 * Simple Factory class, exposing creational method to produce concrete encodings
 *
 * @category   Zend
 * @package    Zend_OpenId
 * @subpackage Zend_OpenId_Message
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Factory
{
    /**
     * List of available encoding classes
     */
    private static $availableEncodings = array(
        Message\Encoding::TYPE_KEYVALUE => '\Zend\OpenId\Message\Encoding\KeyValue',
        Message\Encoding::TYPE_HTTP     => '\Zend\OpenId\Message\Encoding\Http',
        Message\Encoding::TYPE_ARRAY    => '\Zend\OpenId\Message\Encoding\AsArray',
    );

    /**
     * Setup and return concrete instance of \Zend\OpenId\Message\Encoding
     *
     * @param int $encoding Encoding type. Value range:
     *                        \Zend\OpenId\Message\Encoding::TYPE_ARRAY, 
     *                        \Zend\OpenId\Message\Encoding::TYPE_KEYVALUE, 
     *                        \Zend\OpenId\Message\Encoding::TYPE_HTTP
     *
     * @return \Zend\OpenId\Message\Encoding
     * @see \Zend\OpenId\Message\Encoding
     * @todo DI Container usage would suit well w/i this method
     */
    public static function create($encoding)
    {
        foreach (self::$availableEncodings as $type => $class) {
            if ($type & $encoding) {
                return new $class();
            }
        }

        throw new Exception\WrongEncodingType($encoding);
    }
}
