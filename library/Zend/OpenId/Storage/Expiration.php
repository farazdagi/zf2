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
 * @subpackage Zend_OpenId_Storage
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * @namespace
 */
namespace Zend\OpenId\Storage;
use Zend\OpenId;

/**
 * Helper object to manipulate expiry times of vairous artifacts stored in storages.
 *
 * @category   Zend
 * @package    Zend_OpenId
 * @subpackage Zend_OpenId_Storage
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Expiration
    implements \Serializable
{
    /**
     * Unix timestamp 
     * @var int
     */
    private $timestamp = null;

    public function __construct($timestamp = null)
    {
        $this->timestamp = $timestamp;
    }

    /**
     * Set timestamp 
     *
     * @param int $timestamp UNIX Timestamp
     * @return \Zend\OpenId\Storage\Expiration
     */
    public function set($timestamp) 
    {
        $this->timestamp = $timestamp;
        return $this;
    }

    /**
     * Get expriation timestamp
     *
     * @return int
     */
    public function get()
    {
        return $this->timestamp;
    }

    /**
     * Whether expriation time already hit the ground
     *
     * @return boolean
     */
    public function expired()
    {
        if (null === $this->timestamp) {
            return false;
        }
        return $this->timestamp < time();
    } 

    public function serialize() {
        return serialize($this->timestamp);
    }

    public function unserialize($data) {
        $this->timestamp = unserialize($data);
    }
}
