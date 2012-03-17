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

use IteratorAggregate,
    ArrayAccess,
    Serializable,
    Countable;

/**
 * Defines protocol message described in Section 4 of the
 * {@link http://openid.net/specs/openid-authentication-2_0.html OpenID 2.0 Specification}.
 *
 * Interfaces being extended are actually summarized by ArrayObject
 *
 * @category   Zend
 * @package    Zend_OpenId
 * @subpackage Zend_OpenId_Message
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
interface Adapter extends IteratorAggregate, ArrayAccess , Serializable , Countable
{
    /**
     * Available adapters
     */
    const TYPE_KEYVALUE = 'keyvalue';
    const TYPE_HTTP     = 'http';

    /**
     * Generate and return message using specified encoder.
     *
     * @param \Zend\OpenId\Message\Encoder $encoder Encoder type.
     *
     * @return string
     */
    public function encode(Encoder $encoder = null);

    /**
     * Set current encoder
     *
     * @param Zend\OpenId\Message\Encoder $encoder Encoder type.
     *
     * @return \Zend\OpenId\Message
     */
    public function setEncoder(Encoder $encoder = null);

    /**
     * Get current encoder
     *
     * @return \Zend\OpenId\Message\Encoder
     */
    public function getEncoder();

}
