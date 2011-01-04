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
namespace Zend\OpenId\Identifier\Claimed;
use Zend\OpenId;

/**
 * Claimed Identifier to be used in Positive Assertion response
 *
 * @category   Zend
 * @package    Zend_OpenId
 * @subpackage Zend_OpenId_Identifier
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class ForPositiveAssertion
    extends OpenId\Identifier\Claimed
    implements OpenId\Identifier,
               OpenId\Identifier\Decorated
{
    /**
     * Normalizes URL according to RFC 3986. Preserve URL fragment part.
     *
     * @param string $id URL to be normalized
     *
     * @return string|null Normalized URL on success, null otherwise
     */
    protected function normalizeUrl($id)
    {
        $this->preserveFragment = true;
        return parent::normalizeUrl($id);
    }
}
