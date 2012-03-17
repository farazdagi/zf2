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
 * HTTP message encoding strategy as outlined in section 4.1.2 of
 * {@link http://openid.net/specs/openid-authentication-2_0.html OpenID 2.0 Specification}.
 *
 * @category   Zend
 * @package    Zend_OpenId
 * @subpackage Zend_OpenId_Message
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Http implements Message\Encoder
{
    /**
     * Encode array of items into string acc. to concrete encoding algorithm
     *
     * x-www-urlencoded, as in a HTTP POST body or in a URL's query string ([RFC3986] section 3)
     *
     * @return string
     */
    public function encode($items)
    {
        $pairs = array();
        foreach ($items as $key => $value) {
            $pairs[] = sprintf("openid.%s=%s", rawurlencode($key), rawurlencode($value));
        }

        return implode('&', $pairs);
    }

    /**
     * Parse incoming data into array
     *
     * @return array
     */
    public function decode($data)
    {
        $items = array();
        $pairs = explode('&', $data);
        foreach ($pairs as $pair) {
            if (trim($pair) !== '' && strpos($pair, '=') !== false) {
                list($key, $value) = explode('=', $pair, 2);
                $items[str_replace('openid.', '', rawurldecode($key))] = rawurldecode($value);
            }
        }
        return $items;
    }
}
