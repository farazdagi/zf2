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
namespace Zend\OpenId\Discovery\Service;
use Zend\OpenId;

/**
 * Default implementation of Discovery service
 *
 * @category   Zend
 * @package    Zend_OpenId
 * @subpackage Zend_OpenId_Discovery
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
abstract class Resolver
    implements OpenId\Discovery\Service
{
    /**
     * Storage used for caching discovery info
     * @var \Zend\OpenId\Storage
     */
    private $storage;

    /**
     * Discovered info is cached using various types of storages. Concrete
     * storage type can be injected into any Discovery\Service
     *
     * @param \Zend\OpenId\Storage $storage Storage adapter to use
     * @return \Zend\OpenId\Discovery\Service
     */
    public function setStorage(\Zend\OpenId\Storage $storage)
    {
        $this->storage = $storage;
    }

    /**
     * Get storage
     *
     * @return \Zend\OpenId\Storage
     */
    public function getStorage()
    {
        if (null === $this->storage) {
            throw new \Zend\OpenId\Discovery\Exception\StorageNotSet();
        }
        return $this->storage;
    }
}
