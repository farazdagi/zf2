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
use Zend\OpenId,
    Zend\Http;

/**
 * If discovery resolver relies on HTTP client, it must implement this interface
 *
 * @category   Zend
 * @package    Zend_OpenId
 * @subpackage Zend_OpenId_Discovery
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
interface Transport
{
    /**
     * Inject HTTP client used as transport in discovery process
     *
     * @param \Zend\Http\Client $client HTTP Client
     *
     * @return \Zend\OpenId\Discovery\Service Allow method chaining
     */
    public function setHttpClient(\Zend\Http\Client $client = null);

    /**
     * Obtain contained HTTP transport
     *
     * @return \Zend\Http\Client
     */
    public function getHttpClient();
}
