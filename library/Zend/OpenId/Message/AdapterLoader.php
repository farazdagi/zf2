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
namespace Zend\OpenId\Message;

use Zend\Loader\PluginClassLoader;

/**
 * Class loader for Message\Adapter instances
 *
 * @category   Zend
 * @package    Zend_OpenId
 * @subpackage Zend_OpenId_Message
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class AdapterLoader extends PluginClassLoader
{
    /**
     * @var array Pre-defined aliases
     */
    protected $plugins = array(
        Adapter::TYPE_KEYVALUE  => 'Zend\OpenId\Message\Adapter\KeyValue',
        Adapter::TYPE_HTTP      => 'Zend\OpenId\Message\Adapter\Http',
    );
}
