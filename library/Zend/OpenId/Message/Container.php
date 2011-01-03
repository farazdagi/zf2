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
namespace Zend\OpenId\Message;
use Zend\OpenId;

/**
 * Message container - implements message encoding/format defined in Section 4 
 * of the {@link http://openid.net/specs/openid-authentication-2_0.html 
 * OpenID 2.0 Specification}. In addition to formats provided by specs, 
 * additional format (TYPE_ARRAY) is added to facilitate development.
 *
 * @category   Zend
 * @package    Zend_OpenId
 * @subpackage Zend_OpenId_Message
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
abstract class Container
    implements OpenId\Message
{
    /**
     * List of items wrapped inside message container
     * @var array
     */
    private $items = array();

    /**
     * Encoding format
     * @var \Zend\OpenId\Message\Encoding
     */
    private $encoding;

    /**
     * Make sure that class is ready for Dependency Injection
     *
     * @param \Zend\Message\Encoding Encoding used to encode/decode message items
     *
     * @return void
     */
    public function __construct($encoding = null)
    {
        if (null !== $encoding) {
            $this->setEncoding($encoding);
        }
    }

    /**
     * Set single field in message
     *
     * @param string $name Field name
     * @param string $value Field value
     *
     * @return \Zend\OpenId\Message
     */
    public function setItem($name, $value)
    {
        $this->items[$name] = $value;
        return $this;
    }

    /**
     * Get single message field
     *
     * @param string $name Field name
     *
     * @return null|string
     */
    public function getItem($name)
    {
        if (isset($this->items[$name])) {
            return $this->items[$name];
        }

        return null;
    }

    /**
     * Clear the field from the messge
     *
     * @param string $name Field name
     *
     * @return \Zend\OpenId\Message
     */
    public function removeItem($name)
    {
        unset($this->items[$name]);
        return $this;
    }

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
    public function set($data, $encoding = null)
    {
        if (null !== $encoding) {
            $this->setEncoding($encoding);
        }

        $this->items = $this->getEncoding()
                            ->decode($data);

        return $this;
    }

    /**
     * Alias for self::set()
     */
    public function setMessage($data, $encoding = null)
    {
        return $this->set($data, $encoding);
    }

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
    public function get($encoding = null)
    {
        if (null !== $encoding) {
            $this->setEncoding($encoding);
        }

        return $this->getEncoding()
                    ->encode($this->items);
    }

    /**
     * Alias for self::get()
     */
    public function getMessage($encoding = null)
    {
        return $this->get($encoding);
    }

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
    public function setEncoding($encoding = Message\Encoding::TYPE_ARRAY)
    {
        if (!($encoding instanceof \Zend\OpenId\Message\Encoding)) {
            $encoding = Encoding\Factory::create($encoding);
        }
        $this->encoding = $encoding;

        return $this;
    }

    /**
     * Get current encoding format
     *
     * @return \Zend\OpenId\Message\Encoding
     */
    public function getEncoding()
    {
        if (null === $this->encoding) {
            $this->encoding = new Encoding\AsArray();
        }

        return $this->encoding;
    }


}
