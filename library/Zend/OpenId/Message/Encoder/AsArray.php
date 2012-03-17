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
 * Array message encoding strategy. This is an auxilary format to ease development.
 *
 * @category   Zend
 * @package    Zend_OpenId
 * @subpackage Zend_OpenId_Message
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class AsArray implements Message\Encoder
{
    /**
     * Encode array of items into string acc. to concrete encoding algorithm
     *
     * @return array
     */
    public function encode($items)
    {
        return $items;
    }

    /**
     * Parse incoming data into array
     *
     * @return array
     */
    public function decode($data)
    {
        return $data; // since nothing has been done during encoding stage
                      // nothing is done on decoding
    }
}
