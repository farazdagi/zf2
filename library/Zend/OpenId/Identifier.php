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
 * @subpackage Zend_OpenId_Identifier
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * @namespace
 */
namespace Zend\OpenId;

/**
 * Wrapper interface describing basic contract of any type of OpenID Identifier
 *
 * @category   Zend
 * @package    Zend_OpenId
 * @subpackage Zend_OpenId_Identifier
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
interface Identifier
{
    /**
     * Get Identifier value
     *
     * @return string
     */
    public function get();

    /**
     * Set Identifier value
     * @param string $id New identifier value
     *
     * @return \Zend\OpenId\Identifier
     */
    public function set($id);

    /**
     * Check if passed Identifier is equal to contained
     *
     * @param \Zend\OpenId\Identifier $id Identifier to compare to
     *
     * @return boolean
     */
    public function equals(\Zend\OpenId\Identifier $id);
}
