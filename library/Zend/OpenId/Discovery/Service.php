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
namespace Zend\OpenId\Discovery;
use Zend\OpenId;

/**
 * Interface defining contract for discovery services. Discovery is the process
 * by which supplied identifier is "resolved" into information array necessary
 * to initiate further authentication requests. That's RP uses Identifier to 
 * look up ("discover") that information.
 *
 * @category   Zend
 * @package    Zend_OpenId
 * @subpackage Zend_OpenId_Discovery
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
interface Service
{
    /**
     * Resolve the identifier by performing discovery on it
     *
     * @param \Zend\OpenId\Identifier Identifier to perform discovery on
     *
     * @return \Zend\OpenId\Discovery\Information
     */
    public function discover(\Zend\OpenId\Identifier $id);

    /**
     * Discovered info is cached using various types of storages. Concrete
     * storage type can be injected into any Discovery\Service
     *
     * @param \Zend\OpenId\Storage $storage Storage adapter to use
     * @return \Zend\OpenId\Discovery\Service
     */
    public function setStorage(\Zend\OpenId\Storage $storage);

    /**
     * Get storage
     *
     * @return \Zend\OpenId\Storage
     */
    public function getStorage();
}
