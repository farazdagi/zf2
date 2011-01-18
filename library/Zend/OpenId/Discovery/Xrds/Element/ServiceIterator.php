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
 * @subpackage Zend_OpenId_Discovery
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * @namespace
 */
namespace Zend\OpenId\Discovery\Xrds\Element;
use Zend\OpenId;

/**
 * Iterator for service traversal.
 *
 * @category   Zend
 * @package    Zend_OpenId
 * @subpackage Zend_OpenId_Discovery
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class ServiceIterator
    implements \Iterator
{
    /**
     * List of contained items
     * @var array
     */
    private $items = array();

    /**
     * Current iterator position
     * @var int
     */
    private $pos = 0;

    /**
     * Get item at current position
     *
     * @return \Zend\OpenId\Discovery\Service\Endpoint
     */
    public function current()
    {
        return $this->items[$this->pos];
    }

    /**
     * Get current position
     *
     * @return int
     */
    public function key()
    {
        return $this->pos;
    }

    /**
     * Move internal pointer forward
     *
     * @return void
     */
    public function next()
    {
        $this->pos++;
    }

    /**
     * Move internal pointer to beginning
     *
     * @return void
     */
    public function rewind()
    {
        $this->pos = 0;
    }

    /**
     * Check if element on current position is valid
     *
     * @return boolean
     */
    public function valid()
    {
        return isset($this->items[$this->pos]) 
            && $this->items[$this->pos] instanceof \Zend\OpenId\Discovery\Service\Endpoint;
    }

}
