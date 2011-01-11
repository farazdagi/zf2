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
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * @namespace
 */
namespace Zend\OpenId;
use Zend\OpenId\Message;

/**
 * Message container object.
 */
interface Message
{
    /**
     * Make sure that class is ready for Dependency Injection
     *
     * @param \Zend\Message\Encoding Encoding used to encode/decode message items
     *
     * @return void
     */
    public function __construct($encoding = null);

    /**
     * Set single field in message
     *
     * @param string $name Field name
     * @param string $value Field value
     *
     * @return \Zend\OpenId\Message
     */
    public function setItem($name, $value);

    /**
     * Get single message field
     *
     * @param string $name Field name
     *
     * @return string
     */
    public function getItem($name);

    /**
     * Clear the field from the messge
     *
     * @param string $name Field name
     *
     * @return \Zend\OpenId\Message
     */
    public function removeItem($name);

    /**
     * Reset message and populate it with provided data.
     *
     * @param array|string $data Data to be parsed into message
     * @param mixed $encoding Data format used by $data. Value range:
     *                        \Zend\OpenId\Message\Encoding object
     *                        \Zend\OpenId\Message\Encoding::TYPE_ARRAY, 
     *                        \Zend\OpenId\Message\Encoding::TYPE_KEYVALUE, 
     *                        \Zend\OpenId\Message\Encoding::TYPE_HTTP
     *
     * @return \Zend\OpenId\Message
     */
    public function set($data, $encoding = null);

    /**
     * Alias for self::set()
     */
    public function setMessage($data, $encoding = null);

    /**
     * Generate and return message using specified encoding.
     *
     * @param mixed $encoding Encoding type. Value range:
     *                        \Zend\OpenId\Message\Encoding object
     *                        \Zend\OpenId\Message\Encoding::TYPE_ARRAY, 
     *                        \Zend\OpenId\Message\Encoding::TYPE_KEYVALUE, 
     *                        \Zend\OpenId\Message\Encoding::TYPE_HTTP
     *
     * @return string 
     */
    public function get($encoding = Message\Encoding::TYPE_ARRAY);

    /**
     * Alias for self::get()
     */
    public function getMessage($encoding = Message\Encoding::TYPE_ARRAY);

    /**
     * Set current encoding format.
     *
     * @param mixed $encoding Encoding type. Value range:
     *                        \Zend\OpenId\Message\Encoding object
     *                        \Zend\OpenId\Message\Encoding::TYPE_ARRAY, 
     *                        \Zend\OpenId\Message\Encoding::TYPE_KEYVALUE, 
     *                        \Zend\OpenId\Message\Encoding::TYPE_HTTP
     *
     * @return \Zend\OpenId\Message
     */
    public function setEncoding($encoding = Message\Encoding::TYPE_ARRAY);

    /**
     * Get current encoding format
     *
     * @return \Zend\OpenId\Message\Encoding
     */
    public function getEncoding();

}