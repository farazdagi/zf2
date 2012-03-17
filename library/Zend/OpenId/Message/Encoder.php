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

use Zend\OpenId;

/**
 * Defines encoding/format of a protocol message described in Section 4 of the
 * {@link http://openid.net/specs/openid-authentication-2_0.html
 * OpenID 2.0 Specification}. In addition to formats provided by specs,
 * additional format (TYPE_ARRAY) is added to facilitate testing.
 *
 * @category   Zend
 * @package    Zend_OpenId
 * @subpackage Zend_OpenId_Message
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
interface Encoder
{
    /**
     * Available encodings
     */
    const TYPE_KEYVALUE = 'keyvalue';
    const TYPE_HTTP     = 'http';
    const TYPE_ARRAY    = 'array';

    /**
     * Encode array of items into string acc. to concrete encoding algorithm
     *
     * @return string|array String for actual algorithms (KeyValue and Http) and array for debugging algorithm (AsArray)
     */
    public function encode($items);

    /**
     * Parse incoming data into array
     *
     * @param string $data Message to decode
     *
     * @return array
     */
    public function decode($data);
}
