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

namespace Zend\OpenId\Storage;
use Zend\OpenId\Storage;

/**
 * Base storage implementation
 *
 * @category   Zend
 * @package    Zend_OpenId
 * @subpackage Zend_OpenId_Storage
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
abstract class AbstractStorage
    implements Storage
{
    /**
     * Cleanup all expired data
     *
     * Internally cleanupAssociations(), cleanupNonces(), and 
     * cleanupDiscoveryInfo() are called
     *
     * @return \Zend\OpenId\Storage
     */
    public function cleanup()
    {
        $this->cleanupAssociations()
             ->cleanupDiscoveryInfo()
             ->cleanupNonces();
        return $this;
    }

    /**
     * Reset the storage to its initial state
     *
     * Internally resetAssociations(), resetNonces(), and 
     * resetDiscoveryInforomation() called
     *
     * @return \Zend\OpenId\Storage
     */
    public function reset()
    {
        $this->resetAssociations()
             ->resetDiscoveryInfo()
             ->resetNonces();
        return $this;
    }
}
