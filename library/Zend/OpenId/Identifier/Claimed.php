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
namespace Zend\OpenId\Identifier;
use Zend\OpenId;

/**
 * Once user supplied Identifier is normalized it becomes ClaimedId
 * Implemented as decorator adding extra processing (normalization) to Identifier
 *
 * @category   Zend
 * @package    Zend_OpenId
 * @subpackage Zend_OpenId_Identifier
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Claimed
    extends OpenId\Identifier\AbstractIdentifier
    implements OpenId\Identifier,
               OpenId\Identifier\Decorated
{
    /**
     * Non-Decorated value
     * @var string
     */
    protected $rawValue = null;

    /**
     * Whether URL fragment of Identifier should be preserved or not
     * @var boolean
     */
    protected $preserveFragment = false;

    /**
     * Allow to pass decorated Identifier directly into constructor
     *
     * @param string|\Zend\OpenId\Identifier $id Decorated Identifier
     *
     * @return void
     */
    public function __construct($id = null)
    {
        if (!($id instanceof \Zend\OpenId\Identifier)) {
            $id = new UserSupplied($id);
        }
        $this->set($id->get());
    }

    /**
     * Returns raw (non-decorated) value
     *
     * @return string
     */
    public function getRaw()
    {
        return $this->rawValue;
    }

    /**
     * Set the internal value. Use normalized value
     *
     * @param string $id New Identifier value
     * @return \Zend\OpenId\Identifier
     */
    public function set($id)
    {
        $this->rawValue = $id;
        return parent::set($this->normalize($id));
    }

    /**
     * Normalize incomming Identifier according to procedure described in 
     * section "7.2 Normalization" of  
     * {@link http://openid.net/specs/openid-authentication-2_0.html OpenID 2.0 Specification}    
     *
     * Normalization is perfomed according to following rules:
     * 1. If the user's input starts with the "xri://" prefix, it MUST be 
     *    stripped off, so that XRIs are used in the canonical form.
     * 2. If the first character of the resulting string is an 
     *    XRI Global Context Symbol ("=", "@", "+", "$", "!") or "(", as defined in 
     *    Section 2.2.1 of [XRI_Syntax_2.0] then the input SHOULD be treated as an XRI.
     * 3. Otherwise, the input SHOULD be treated as an http URL; if it does not 
     *    include a "http" or "https" scheme, the Identifier MUST be prefixed 
     *    with the string "http://". If the URL contains a fragment part, 
     *    it MUST be stripped off together with the fragment delimiter 
     *    character "#".  
     * 4. URL Identifiers MUST then be further normalized by both following 
     *    redirects when retrieving their content and finally applying the rules 
     *    in Section 6 of [RFC3986] to the final destination URL. 
     *    This final URL MUST be noted by the Relying Party as the 
     *    Claimed Identifier and be used when requesting authentication.
     *
     * @param string $id Identifier to normalize
     * @return string
     */
    protected function normalize($id)
    {
        $id = trim($id);
        if (strlen($id) === 0) {
            return $id;
        }

        // 7.2.1 (XRI)
        if (strpos($id, 'xri://') === 0) {
            $id = substr($id, strlen('xri://'));
        }

        // 7.2.2 (XRI)
        if ($id[0] == '=' ||
            $id[0] == '@' ||
            $id[0] == '+' ||
            $id[0] == '$' ||
            $id[0] == '!') {
            return $id;
        }

        // 7.2.3 (URL)
        if (strpos($id, "://") === false) {
            $id = 'http://' . $id;
        }

        // 7.2.4
        return $this->normalizeUrl($id);
    }

    /**
     * Normalizes URL according to RFC 3986.
     *
     * @param string $id URL to be normalized
     *
     * @return string|null Normalized URL on success, null otherwise
     */
    protected function normalizeUrl($id)
    {
        // @todo Use filter_var once RFC 3986 is supported
        // RFC 2396 is absoleted by more modern RFC 3986, but 
        // FILTER_VALIDATE_URL (http://www.php.net/manual/en/filter.filters.validate.php)
        // as of now supports only the former.
        
       
        // RFC 3986, 6.2.2.  Syntax-Based Normalization

        // RFC 3986, 6.2.2.2 Percent-Encoding Normalization
        $i = 0;
        $n = strlen($id);
        $res = '';
        while ($i < $n) {
            if ($id[$i] == '%') {
                if ($i + 2 >= $n) {
                    return null;
                }
                ++$i;
                if ($id[$i] >= '0' && $id[$i] <= '9') {
                    $c = ord($id[$i]) - ord('0');
                } else if ($id[$i] >= 'A' && $id[$i] <= 'F') {
                    $c = ord($id[$i]) - ord('A') + 10;
                } else if ($id[$i] >= 'a' && $id[$i] <= 'f') {
                    $c = ord($id[$i]) - ord('a') + 10;
                } else {
                    return null;
                }
                ++$i;
                if ($id[$i] >= '0' && $id[$i] <= '9') {
                    $c = ($c << 4) | (ord($id[$i]) - ord('0'));
                } else if ($id[$i] >= 'A' && $id[$i] <= 'F') {
                    $c = ($c << 4) | (ord($id[$i]) - ord('A') + 10);
                } else if ($id[$i] >= 'a' && $id[$i] <= 'f') {
                    $c = ($c << 4) | (ord($id[$i]) - ord('a') + 10);
                } else {
                    return null;
                }
                ++$i;
                $ch = chr($c);
                if (($ch >= 'A' && $ch <= 'Z') ||
                    ($ch >= 'a' && $ch <= 'z') ||
                    $ch == '-' ||
                    $ch == '.' ||
                    $ch == '_' ||
                    $ch == '~') {
                    $res .= $ch;
                } else {
                    $res .= '%';
                    if (($c >> 4) < 10) {
                        $res .= chr(($c >> 4) + ord('0'));
                    } else {
                        $res .= chr(($c >> 4) - 10 + ord('A'));
                    }
                    $c = $c & 0xf;
                    if ($c < 10) {
                        $res .= chr($c + ord('0'));
                    } else {
                        $res .= chr($c - 10 + ord('A'));
                    }
                }
            } else {
                $res .= $id[$i++];
            }
        }

        if (!preg_match('|^([^:]+)://([^:@]*(?:[:][^@]*)?@)?([^/:@?#]*)(?:[:]([^/?#]*))?(/[^?#]*)?((?:[?](?:[^#]*))?)((?:#.*)?)$|', $res, $reg)) {
            return null;
        }
        $scheme = $reg[1];
        $auth = $reg[2];
        $host = $reg[3];
        $port = $reg[4];
        $path = $reg[5];
        $query = $reg[6];
        $fragment = $reg[7]; 

        if (empty($scheme) || empty($host)) {
            return null;
        }

        // RFC 3986, 6.2.2.1.  Case Normalization
        $scheme = strtolower($scheme);
        $host = strtolower($host);

        // RFC 3986, 6.2.2.3.  Path Segment Normalization
        if (!empty($path)) {
            $i = 0;
            $n = strlen($path);
            $res = "";
            while ($i < $n) {
                if ($path[$i] == '/') {
                    ++$i;
                    while ($i < $n && $path[$i] == '/') {
                        ++$i;
                    }
                    if ($i < $n && $path[$i] == '.') {
                        ++$i;
                        if ($i < $n && $path[$i] == '.') {
                            ++$i;
                            if ($i == $n || $path[$i] == '/') {
                                if (($pos = strrpos($res, '/')) !== false) {
                                    $res = substr($res, 0, $pos);
                                }
                            } else {
                                    $res .= '/..';
                            }
                        } else if ($i != $n && $path[$i] != '/') {
                            $res .= '/.';
                        }
                    } else {
                        $res .= '/';
                    }
                } else {
                    $res .= $path[$i++];
                }
            }
            $path = $res;
        }

        // RFC 3986,6.2.3.  Scheme-Based Normalization
        if ($scheme == 'http') {
            if ($port == 80) {
                $port = '';
            }
        } else if ($scheme == 'https') {
            if ($port == 443) {
                $port = '';
            }
        }
        if (empty($path)) {
            $path = '/';
        }

        $id = $scheme
            . '://'
            . $auth
            . $host
            . (empty($port) ? '' : (':' . $port))
            . $path
            . $query
            . ($this->preserveFragment ? $fragment : '');
        return $id;
    }
}
