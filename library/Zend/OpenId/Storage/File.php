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
use Zend\OpenId,
    Zend\OpenId\Storage,
    Zend\OpenId\Storage\Exception;

/**
 * Storage implemmentation using serialized files
 *
 * @uses       Zend\OpenId\Storage
 * @uses       Zend\OpenId\Exception
 * @uses       Zend\OpenId\Storage\Exception
 * @category   Zend
 * @package    Zend_OpenId
 * @subpackage Zend_OpenId_Storage
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class File 
    extends AbstractStorage
    implements Storage
{

    /**
     * Directory name to store data files in
     *
     * @var string $_dir
     */
    private $_dir;

    /**
     * Constructs storage object and creates storage directory
     *
     * @param string $dir directory name to store data files in
     * @throws Zend\OpenId\Exception
     */
    public function __construct($dir = null)
    {
        if ($dir === null) {
            $tmp = getenv('TMP');
            if (empty($tmp)) {
                $tmp = getenv('TEMP');
                if (empty($tmp)) {
                    $tmp = "/tmp";
                }
            }
            $user = get_current_user();
            if (is_string($user) && !empty($user)) {
                $tmp .= '/' . $user;
            }
            $dir = $tmp . '/openid/consumer';
        }
        $this->_dir = $dir;
        if (!is_dir($this->_dir)) {
            if (!@mkdir($this->_dir, 0700, 1)) {
                throw new Exception\InitializationException(
                    'Cannot access storage directory ' . $dir);
            }
        }

        if (($f = @fopen($this->_dir.'/assoc.lock', 'w+')) === false) {
            throw new Exception\LockFailedException(
                'Cannot create a lock file in the directory ' . $dir);
        }
        fclose($f);
        if (($f = @fopen($this->_dir.'/discovery.lock', 'w+')) === false) {
            throw new Exception\LockFailedException(
                'Cannot create a lock file in the directory ' . $dir);
        }
        fclose($f);
        if (($f = @fopen($this->_dir.'/nonce.lock', 'w+')) === false) {
            throw new Exception\LockFailedException(
                'Cannot create a lock file in the directory ' . $dir);
        }
        fclose($f);
    }

    /**
     * Expose directory name used as destination
     *
     * @return string
     */
    public function getSavePath()
    {
        return $this->_dir;
    }

    /**
     * Store information about association
     *
     * @param \Zend\OpenId\Association $associtation Association instance
     *
     * @return \Zend\OpenId\Storage
     */
    public function addAssociation(\Zend\OpenId\Association $associtation)
    {
        $name1 = $this->_dir . '/assoc_url_' . md5($url);
        $name2 = $this->_dir . '/assoc_handle_' . md5($handle);
        $lock = @fopen($this->_dir . '/assoc.lock', 'w+');
        if ($lock === false) {
            return false;
        }
        if (!flock($lock, LOCK_EX)) {
            fclose($lock);
            return false;
        }
        try {
            $f = @fopen($name1, 'w+');
            if ($f === false) {
                fclose($lock);
                return false;
            }
            $data = serialize(array($url, $handle, $macFunc, $secret, $expires));
            fwrite($f, $data);
            if (function_exists('symlink')) {
                @unlink($name2);
                if (symlink($name1, $name2)) {
                    fclose($f);
                    fclose($lock);
                    return true;
                }
            }
            $f2 = @fopen($name2, 'w+');
            if ($f2) {
                fwrite($f2, $data);
                fclose($f2);
                @unlink($name1);
                $ret = true;
            } else {
                $ret = false;
            }
            fclose($f);
            fclose($lock);
            return $ret;
        } catch (\Exception $e) {
            fclose($lock);
            throw $e;
        }
    }

    /**
     * Get information about association identified by $url
     *
     * @param string $url OP Endpoint URL
     * @param string $handle Association handle (if any)
     *
     * @return \Zend\OpenId\Association
     */
    public function getAssociation($url, $handle = null)
    {
        $name1 = $this->_dir . '/assoc_url_' . md5($url);
        $lock = @fopen($this->_dir . '/assoc.lock', 'w+');
        if ($lock === false) {
            return false;
        }
        if (!flock($lock, LOCK_EX)) {
            fclose($lock);
            return false;
        }
        try {
            $f = @fopen($name1, 'r');
            if ($f === false) {
                fclose($lock);
                return false;
            }
            $ret = false;
            $data = stream_get_contents($f);
            if (!empty($data)) {
                list($storedUrl, $handle, $macFunc, $secret, $expires) = unserialize($data);
                if ($url === $storedUrl && $expires > time()) {
                    $ret = true;
                } else {
                    $name2 = $this->_dir . '/assoc_handle_' . md5($handle);
                    fclose($f);
                    @unlink($name2);
                    @unlink($name1);
                    fclose($lock);
                    return false;
                }
            }
            fclose($f);
            fclose($lock);
            return $ret;
        } catch (\Exception $e) {
            fclose($lock);
            throw $e;
        }
    }

    /**
     * Remove association identified by $url
     *
     * @param string $url OP Endpoint URL
     *
     * @return \Zend\OpenId\Storage
     */
    public function removeAssociation($url)
    {
        $name1 = $this->_dir . '/assoc_url_' . md5($url);
        $lock = @fopen($this->_dir . '/assoc.lock', 'w+');
        if ($lock === false) {
            return false;
        }
        if (!flock($lock, LOCK_EX)) {
            fclose($lock);
            return false;
        }
        try {
            $f = @fopen($name1, 'r');
            if ($f === false) {
                fclose($lock);
                return false;
            }
            $data = stream_get_contents($f);
            if (!empty($data)) {
                list($storedUrl, $handle, $macFunc, $secret, $expires) = unserialize($data);
                if ($url === $storedUrl) {
                    $name2 = $this->_dir . '/assoc_handle_' . md5($handle);
                    fclose($f);
                    @unlink($name2);
                    @unlink($name1);
                    fclose($lock);
                    return true;
                }
            }
            fclose($f);
            fclose($lock);
            return true;
        } catch (\Exception $e) {
            fclose($lock);
            throw $e;
        }
    }

    /**
     * Remove all expired associations
     *
     * @param int $timestamp
     * @return \Zend\OpenId\Storage
     */
    public function cleanupAssociations($timestamp)
    {
        return $this->cleanupByType($timestamp, 'assoc.lock', array('assoc_url_*', 'assoc_handle_*'));
    }

    /**
     * Remove all associations
     *
     * @return \Zend\OpenId\Storage
     */
    public function resetAssociations()
    {
        return $this->cleanupAssociations(0);
    }

    /**
     * Store information discovered for Identifier
     *
     * @param \Zend\OpenId\Indentifier Identifier for which discovery is performed
     * @param \Zend\OpenId\Discovery\Information $disoveryInfo Container with discovered info
     * @param \Zend\OpenId\Storage\Expiration $expirationInfo When to invalidate data
     *
     * @throws \Zend\OpenId\Storage\Exception
     * @return \Zend\OpenId\Storage
     */
    public function addDiscoveryInformation(
        \Zend\OpenId\Identifier $id,
        \Zend\OpenId\Discovery\Information $discoveryInfo,
        \Zend\OpenId\Storage\Expiration $expirationInfo = null)
    {
        // if expiry time not set, expire never
        if (null === $expirationInfo) {
            $expirationInfo = new Storage\Expiration(); 
        }

        $name = $this->_dir . '/discovery_' . md5($id->get());
        $lock = @fopen($this->_dir . '/discovery.lock', 'w+');
        if ($lock === false) {
            throw new Exception\LockFailedException('Cannot create a lock file');
        }
        if (!flock($lock, LOCK_EX)) {
            fclose($lock);
            throw new Exception\LockFailedException('Cannot obtain a lock on file');
        }
        try {
            $f = @fopen($name, 'w+');
            if ($f === false) {
                fclose($lock);
                throw new Exception\InitializationException('Storage file cannot be created');
            }
            $data = serialize(array($discoveryInfo, $expirationInfo));
            fwrite($f, $data);
            fclose($f);
            fclose($lock);
        } catch (\Exception $e) {
            fclose($lock);
            throw $e;
        }
    }

    /**
     * Get information discovered for $identifier
     *
     * @param \Zend\OpenId\Identifier $id Normalized Identifier used in discovery
     *
     * @return \Zend\OpenId\Discovery\Information
     */
    public function getDiscoveryInformation(\Zend\OpenId\Identifier $id)
    {
        $name = $this->_dir . '/discovery_' . md5($id->get());
        $lock = @fopen($this->_dir . '/discovery.lock', 'w+');
        if ($lock === false) {
            return null;
        }
        if (!flock($lock, LOCK_EX)) {
            fclose($lock);
            return null;
        }
        try {
            $f = @fopen($name, 'r');
            if ($f === false) {
                fclose($lock);
                return null;
            }
            $ret = null;
            $data = stream_get_contents($f);
            if (!empty($data)) {
                list($discoveryInfo, $expirationInfo) = unserialize($data);
                if ($discoveryInfo->getSuppliedIdentifier()->equals($id)  
                    && $expirationInfo->isNotExpired()
                ) {
                    $ret = $discoveryInfo;
                } else {
                    fclose($f);
                    @unlink($name);
                    fclose($lock);
                    return null;
                }
            }
            fclose($f);
            fclose($lock);
            return $ret;
        } catch (\Exception $e) {
            fclose($lock);
            throw $e;
        }
    }

    /**
     * Remove cached information discovered for Identifier
     *
     * @param \Zend\OpenId\Identifier $id Identifier used in discovery
     *
     * @return boolean True on success, false otherwise
     */
    public function removeDiscoveryInformation(\Zend\OpenId\Identifier $id)
    {
        $name = $this->_dir . '/discovery_' . md5($id->get());
        $lock = @fopen($this->_dir . '/discovery.lock', 'w+');
        if ($lock === false) {
            return false;
        }
        if (!flock($lock, LOCK_EX)) {
            fclose($lock);
            return false;
        }
        try {
            @unlink($name);
            fclose($lock);
            return true;
        } catch (\Exception $e) {
            fclose($lock);
            throw $e;
        }
    }

    /**
     * Remove expired discovery data from the cache
     *
     * @param int $timestamp
     * @return \Zend\OpenId\Storage
     */
    public function cleanupDiscoveryInformation($timestamp)
    {
        return $this->cleanupByType($timestamp, 'discovery.lock', array('discovery_*'));
    }

    /**
     * Remove all discovery data from the cache
     *
     * @return \Zend\OpenId\Storage
     */
    public function resetDiscoveryInformation()
    {
        return $this->cleanupDiscoveryInformation(0);
    }

    /**
     * Associate OP Endpoint URL with nonce value to prevent replay attacks
     *
     * @param string $url OP Endpoint URL
     * @param \Zend\OpenId\Nonce $nonce Response nonce returned by OP
     *
     * @return \Zend\OpenId\Storage
     */
    public function addNonce($nonce)
    {
        $name = $this->_dir . '/nonce_' . md5($provider.';'.$nonce);
        $lock = @fopen($this->_dir . '/nonce.lock', 'w+');
        if ($lock === false) {
            return false;
        }
        if (!flock($lock, LOCK_EX)) {
            fclose($lock);
            return false;
        }
        try {
            $f = @fopen($name, 'x');
            if ($f === false) {
                fclose($lock);
                return false;
            }
            fwrite($f, $provider.';'.$nonce);
            fclose($f);
            fclose($lock);
            return true;
        } catch (\Exception $e) {
            fclose($lock);
            throw $e;
        }
    }

    /**
     * Remove associated nonce
     *
     * @param string $url OP Endpoint URL
     *
     * @return \Zend\OpenId\Storage
     */
    public function removeNonce($url)
    {
        throw new \Zend\OpenId\Exception\NotImplementedException();
    }

    /**
     * Cleanup all exprired nonce data
     *
     * @param int $timestamp
     * @return \Zend\OpenId\Storage
     */
    public function cleanupNonces($timestamp)
    {
        return $this->cleanupByType($timestamp, 'nonce.lock', array('nonce_*'));
    }

    /**
     * Remove all nonce data
     *
     * @return \Zend\OpenId\Storage
     */
    public function resetNonces()
    {
        return $this->cleanupNonces(0);
    }

    private function cleanupByType($timestamp, $lockfile, $filetypes)
    {
        $lock = @fopen($this->_dir . '/' . $lockfile, 'w+');
        if ($lock !== false) {
            flock($lock, LOCK_EX);
        }
        if ($lock === false) {
            throw new Exception\LockFailedException(
                'Cannot lock ' . $lockfile);
        }
        if (!flock($lock, LOCK_EX)) {
            fclose($lock);
            throw new Exception\LockFailedException(
                'Cannot obtain exclusive lock on ' . $lockfile);
        }
        try {
            foreach($filetypes as $type) {
                foreach (glob($this->_dir . '/' . $type) as $name) {
                    if (0 === $timestamp) { // purge w/o looking at mtime
                        @unlink($name);
                    } elseif (filemtime($name) < $time) {
                        @unlink($name);
                    }
                }
            }
            fclose($lock);
        } catch (\Exception $e) {
            fclose($lock);
            throw $e;
        }

        return $this;
    }
}
