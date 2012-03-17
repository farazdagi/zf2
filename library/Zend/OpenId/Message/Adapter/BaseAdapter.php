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
namespace Zend\OpenId\Message\Adapter;

use ArrayObject,
    Zend\OpenId\Message,
    Zend\OpenId\Exception;

/**
 * KeyValue Message
 *
 * @category   Zend
 * @package    Zend_OpenId
 * @subpackage Zend_OpenId_Message
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
abstract class BaseAdapter extends ArrayObject implements Message\Adapter
{
    /**
     * @var \Zend\OpenId\Message\Encoder
     */
    protected $encoder;

    /**
     * Generate and return message using specified encoder.
     *
     * @param \Zend\OpenId\Message\Encoder $encoder Encoder type.
     *
     * @return string
     */
    public function encode(Message\Encoder $encoder = null)
    {
        if(null !== $encoder) {
            $this->setEncoder($encoder);
        }

        return $this->getEncoder()
                    ->encode((array)$this);
    }

    /**
     * Set current encoder
     *
     * @param Zend\OpenId\Message\Encoder $encoder Encoder type.
     *
     * @return \Zend\OpenId\Message
     */
    public function setEncoder(Message\Encoder $encoder = null)
    {
        $this->encoder = $encoder;
        return $this;
    }

    /**
     * Get current encoder
     *
     * @return \Zend\OpenId\Message\Encoder
     */
    public function getEncoder()
    {
        if (null !== $this->encoder) {
            return $this->encoder;
        }
        throw new Exception\RuntimeException('Message encoder not set');
    }
}
