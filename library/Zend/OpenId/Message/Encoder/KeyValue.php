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
namespace Zend\OpenId\Message\Encoder;

use Zend\OpenId\Message;

/**
 * KeyValue message encoding strategy as outlined in section 4.1.1 of
 * {@link http://openid.net/specs/openid-authentication-2_0.html
 * OpenID 2.0 Specification}.
 *
 * @category   Zend
 * @package    Zend_OpenId
 * @subpackage Zend_OpenId_Message
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class KeyValue implements Message\Encoder
{
    /**
     * Encode array of items into string acc. to concrete encoding algorithm
     *
     * @return string
     */
    public function encode($items)
    {
        $message = '';
        foreach ($items as $key => $value) {
            $message .= sprintf("%s:%s\n", $key, $value);
        }

        return $message;
    }

    /**
     * Parse incoming data into array
     *
     * @return array
     */
    public function decode($data)
    {
        $items = array();
        $lines = explode("\n", $data);
        foreach ($lines as $line) {
            if (trim($line) !== '' && strpos($line, ':') !== false) {
                list($key, $value) = explode(':', $line, 2);
                $items[$key] = $value;
            }
        }
        return $items;
    }
}
