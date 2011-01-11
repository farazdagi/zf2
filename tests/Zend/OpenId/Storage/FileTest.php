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
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */


/**
 * @namespace
 */
namespace ZendTest\OpenId\Storage;

use Zend\OpenId\OpenId,
    Zend\OpenId\Identifier,
    Zend\OpenId\Discovery,
    Zend\OpenId\Storage,
    Zend\OpenId\Storage\Exception;

/**
 * @see Storage\File
 */

/**
 * @category   Zend
 * @package    Zend_OpenId
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_OpenId
 */
class FileTest extends \PHPUnit_Framework_TestCase
{
    const URL      = "http://www.myopenid.com/";
    const HANDLE   = "d41d8cd98f00b204e9800998ecf8427e";
    const MAC_FUNC = "sha256";
    const SECRET   = "4fa03202081808bd19f92b667a291873";

    const ID       = "http://id.myopenid.com/";
    const REAL_ID  = "http://real_id.myopenid.com/";
    const SERVER   = "http://www.myopenid.com/";
    const SERVER2  = "http://www.myopenid2.com/";
    const VERSION  = 1.0;

    protected $_tmpDir;

    /**
     * Remove directory recursively
     *
     * @param string $dir
     */
    private static function _rmDir($dirName)
    {
        if (!file_exists($dirName)) {
            return;
        }

        // remove files from temporary direcytory
        $dir = opendir($dirName);
        while (($file = readdir($dir)) !== false) {
            if (is_dir($dirName . '/' . $file)) {
                if ($file == '.'  ||  $file == '..') {
                    continue;
                }

                self::_rmDir($dirName . '/' . $file);
            } else {
                unlink($dirName . '/' . $file);
            }
        }
        closedir($dir);

        @rmdir($dirName);
    }

    public function setUp()
    {
        $this->_tmpDir = __DIR__ . "/_files";

        // Clear directory
        self::_rmDir($this->_tmpDir);
        mkdir($this->_tmpDir);
    }

    public function tearDown()
    {
        self::_rmDir($this->_tmpDir);
    }

    /**
     * testing __construct
     * @group current
     */
    public function testConstruct()
    {
        $tmp = $this->_tmpDir;
        $dir = $tmp . '/openid_consumer';

        $storage = new Storage\File($dir);
        $this->assertTrue( is_dir($dir) );

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $this->markTestSkipped();
            return;
        }

        chmod($dir, 0400);
        $dir2 = $dir . '/test';
        try {
            $storage = new Storage\File($dir2);
            $ex = null;
        } catch (Exception\InitializationException $e) {
            $ex = $e;
        }
        // check marker exception
        $this->assertInstanceOf('\Zend\OpenId\Exception', $ex);
        $this->assertInstanceOf('\Zend\OpenId\Storage\Exception', $ex);
        $this->assertContains( 'Cannot access storage directory', $ex->getMessage() );
        chmod($dir, 0777);
        $this->assertFalse( is_dir($dir2) );
        self::_rmDir($dir);
    }

    /**
     * @group current
     */
    public function testConstructWithNoDirSpecified()
    {
        $storage = new Storage\File();

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $this->markTestSkipped();
            return;
        }

        $dir = $storage->getSavePath();

        chmod($dir, 0400);
        $dir2 = $dir . '/test';
        try {
            $storage = new Storage\File($dir2);
            $ex = null;
        } catch (Exception\InitializationException $e) {
            $ex = $e;
        }
        // check marker exception
        $this->assertInstanceOf('\Zend\OpenId\Exception', $ex);
        $this->assertInstanceOf('\Zend\OpenId\Storage\Exception', $ex);
        $this->assertContains( 'Cannot access storage directory', $ex->getMessage() );
        chmod($dir, 0777);
        $this->assertFalse( is_dir($dir2) );
        self::_rmDir($dir);
    }

    /**
     * @group current
     */
    public function testConstructAssocLockException()
    {
        $tmp = $this->_tmpDir;
        $dir = $tmp . '/openid_consumer';
        $storage = new Storage\File($dir);

        $this->assertTrue( is_dir($dir) );

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $this->markTestSkipped();
            return;
        }

        chmod($dir . '/assoc.lock', 000);
        try {
            $storage = new Storage\File($dir);
            $ex = null;
        } catch (Exception $e) {
            $ex = $e;
        }
        $this->assertInstanceOf('\Zend\OpenId\Exception', $ex);
        $this->assertInstanceOf('\Zend\OpenId\Storage\Exception', $ex);
        $this->assertInstanceOf('\Zend\OpenId\Storage\Exception\LockFailedException', $ex);
        chmod($dir . '/assoc.lock', 777);
    }

    /**
     * @group current
     */
    public function testConstructDiscoveryLockException()
    {
        $tmp = $this->_tmpDir;
        $dir = $tmp . '/openid_consumer';
        $storage = new Storage\File($dir);

        $this->assertTrue( is_dir($dir) );

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $this->markTestSkipped();
            return;
        }

        chmod($dir . '/discovery.lock', 000);
        try {
            $storage = new Storage\File($dir);
            $ex = null;
        } catch (Exception $e) {
            $ex = $e;
        }
        $this->assertInstanceOf('\Zend\OpenId\Exception', $ex);
        $this->assertInstanceOf('\Zend\OpenId\Storage\Exception', $ex);
        $this->assertInstanceOf('\Zend\OpenId\Storage\Exception\LockFailedException', $ex);
        chmod($dir . '/discovery.lock', 777);
    }

    /**
     * @group current
     */
    public function testConstructNonceLockException()
    {
        $tmp = $this->_tmpDir;
        $dir = $tmp . '/openid_consumer';
        $storage = new Storage\File($dir);

        $this->assertTrue( is_dir($dir) );

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $this->markTestSkipped();
            return;
        }

        chmod($dir . '/nonce.lock', 000);
        try {
            $storage = new Storage\File($dir);
            $ex = null;
        } catch (Exception $e) {
            $ex = $e;
        }
        $this->assertInstanceOf('\Zend\OpenId\Exception', $ex);
        $this->assertInstanceOf('\Zend\OpenId\Storage\Exception', $ex);
        $this->assertInstanceOf('\Zend\OpenId\Storage\Exception\LockFailedException', $ex);
        chmod($dir . '/nonce.lock', 777);
    }

    public function testSetAssociation()
    {
        $this->markTestIncomplete();
    }

    /**
     * testing getAssociation
     *
     */
    public function testGetAssociation()
    {
        $tmp = $this->_tmpDir;
        $dir = $tmp . '/openid_consumer';

        $expiresIn = time() + 600;
        $storage = new Storage\File($tmp);
        $storage->removeAssociation(self::URL);
        $this->assertTrue( $storage->addAssociation(self::URL, self::HANDLE, self::MAC_FUNC, self::SECRET, $expiresIn) );
        $this->assertTrue( $storage->getAssociation(self::URL, $handle, $macFunc, $secret, $expires) );
        $this->assertSame( self::HANDLE, $handle );
        $this->assertSame( self::MAC_FUNC, $macFunc );
        $this->assertSame( self::SECRET, $secret );
        $this->assertSame( $expiresIn, $expires );
        $this->assertTrue( $storage->removeAssociation(self::URL) );
        $this->assertFalse( $storage->getAssociation(self::URL, $handle, $macFunc, $secret, $expires) );

        $storage = new Storage\File($dir);
        $this->assertTrue( is_dir($dir) );

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            return;
        }

        chmod($dir, 0);
        $this->assertFalse( $storage->addAssociation(self::URL, self::HANDLE, self::MAC_FUNC, self::SECRET, $expiresIn) );
        chmod($dir, 0777);
    }

    /**
     * testing getAssociationByHandle
     *
     */
    public function testGetAssociationByHandle()
    {
        $tmp = $this->_tmpDir;
        $dir = $tmp . '/openid_consumer';

        $expiresIn = time() + 600;
        $storage = new Storage\File($tmp);
        $storage->removeAssociation(self::URL);
        $this->assertTrue( $storage->addAssociation(self::URL, self::HANDLE, self::MAC_FUNC, self::SECRET, $expiresIn) );
        $this->assertTrue( $storage->getAssociationByHandle(self::HANDLE, $url, $macFunc, $secret, $expires) );
        $this->assertSame( self::URL, $url );
        $this->assertSame( self::MAC_FUNC, $macFunc );
        $this->assertSame( self::SECRET, $secret );
        $this->assertSame( $expiresIn, $expires );
        $this->assertTrue( $storage->removeAssociation(self::URL) );
        $this->assertFalse( $storage->getAssociationByHandle(self::HANDLE, $url, $macFunc, $secret, $expires) );
    }

    /**
     * testing getAssociation
     *
     */
    public function testGetAssociationExpiratin()
    {
        $tmp = $this->_tmpDir;
        $dir = $tmp . '/openid_consumer';

        $expiresIn = time() + 1;
        $storage = new Storage\File($tmp);
        $storage->removeAssociation(self::URL);
        $this->assertTrue( $storage->addAssociation(self::URL, self::HANDLE, self::MAC_FUNC, self::SECRET, $expiresIn) );
        sleep(2);
        $this->assertFalse( $storage->getAssociation(self::URL, $handle, $macFunc, $secret, $expires) );
    }

    /**
     * testing getAssociationByHandle
     *
     */
    public function testGetAssociationByHandleExpiration()
    {
        $tmp = $this->_tmpDir;
        $dir = $tmp . '/openid_consumer';

        $expiresIn = time() + 1;
        $storage = new Storage\File($tmp);
        $storage->removeAssociation(self::URL);
        $this->assertTrue( $storage->addAssociation(self::URL, self::HANDLE, self::MAC_FUNC, self::SECRET, $expiresIn) );
        sleep(2);
        $this->assertFalse( $storage->getAssociationByHandle(self::HANDLE, $url, $macFunc, $secret, $expires) );
    }

    /**
     * testing getDiscoveryInfo
     * @group discoveryInfo
     */
    public function testGetDiscoveryInfo()
    {
        $tmp = $this->_tmpDir;
        $dir = $tmp . '/openid_consumer';

        $expiresIn = new Storage\Expiration(time() + 600);
        $storage = new Storage\File($tmp);

        // setup Identifier
        $id = new Identifier\UserSupplied(self::ID);
        $idClaimed = new Identifier\UserSupplied('http://claimed.myopenid.com');
        $idLocal = new Identifier\UserSupplied('htttp://local.myopenid.com');

        // setup Discovery\Information
        $info = new Discovery\Service\Html\Result($id);
        $info
            ->setEndpointUrl('http://myopenid.com')
            ->setProtocolVersion(Discovery\Information::OPENID_20_OP)
            ->setClaimedIdentifier($idClaimed)
            ->setLocalIdentifier($idLocal);
        
        // purge previous data
        $storage->removeDiscoveryInformation($id);

        // test addition
        $storage->addDiscoveryInformation($id, $info, $expiresIn);
        $this->assertInstanceOf('Zend\OpenId\Discovery\Information', $storage->getDiscoveryInformation($id));
        $infoFromStorage = $storage->getDiscoveryInformation($id);
        $this->assertSame($id->get(), $infoFromStorage->getSuppliedIdentifier()->get());
        $this->assertSame(Discovery\Information::OPENID_20_OP, $infoFromStorage->getProtocolVersion());
        $this->assertSame($idClaimed->get(), $infoFromStorage->getClaimedIdentifier()->get());
        $this->assertSame($idLocal->get(), $infoFromStorage->getLocalIdentifier()->get());
        $this->assertTrue($storage->removeDiscoveryInformation($id));

        // test non-existent id
        $this->assertNull($storage->getDiscoveryInformation($id));

        self::_rmDir($dir);
        $storage = new Storage\File($dir);
        $this->assertTrue( is_dir($dir) );

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            return;
        }

        chmod($dir, 0);

        try {
            $storage->addDiscoveryInformation($id, $info, $expiresIn);
            $ex = null;
        } catch (Exception $e) {
            $ex = $e;
        }
        $this->assertInstanceOf('\Zend\OpenId\Storage\Exception\LockFailedException', $ex);
        $this->assertContains('Cannot create a lock file', $ex->getMessage());

        chmod($dir, 0777);
        @rmdir($dir);
    }

    /**
     * testing getDiscoveryInfo
     * @group discoveryInfo
     */
    public function testGetDiscoveryInfoExpiration()
    {
        $tmp = $this->_tmpDir;
        $dir = $tmp . '/openid_consumer';

        $expiresIn = new Storage\Expiration(time() + 1);
        $storage = new Storage\File($tmp);
        $id = new Identifier\UserSupplied(self::ID);

        $info = new Discovery\Service\Html\Result($id);
        $info
            ->setEndpointUrl('http://myopenid.com')
            ->setProtocolVersion(Discovery\Information::OPENID_20_OP);

        $storage->removeDiscoveryInformation($id);
        $storage->addDiscoveryInformation($id, $info, $expiresIn);

        $this->assertInstanceOf('Zend\OpenId\Discovery\Information', $storage->getDiscoveryInformation($id));
        $infoFromStorage = $storage->getDiscoveryInformation($id);
        $this->assertSame($id->get(), $infoFromStorage->getSuppliedIdentifier()->get());
        $this->assertSame('http://myopenid.com', $infoFromStorage->getEndpointUrl());
        $this->assertSame(Discovery\Information::OPENID_20_OP, $infoFromStorage->getProtocolVersion());
        $this->assertTrue($storage->removeDiscoveryInformation($id));

        sleep(2);
        $this->assertNull($storage->getDiscoveryInformation($id));
    }

    /**
     * testing isUniqueNonce
     *
     */
    public function testIsUniqueNonce()
    {
        $tmp = $this->_tmpDir;
        $dir = $tmp . '/openid_consumer';

        $storage = new Storage\File($tmp);
        $storage->purgeNonces();
        $this->assertTrue( $storage->isUniqueNonce(self::SERVER, '1') );
        $this->assertTrue( $storage->isUniqueNonce(self::SERVER, '2') );
        $this->assertFalse( $storage->isUniqueNonce(self::SERVER, '1') );
        $this->assertFalse( $storage->isUniqueNonce(self::SERVER, '2') );
        $storage->purgeNonces();
        $this->assertTrue( $storage->isUniqueNonce(self::SERVER, '1') );
        sleep(2);
        $date = @date("r", time());
        sleep(2);
        $this->assertTrue( $storage->isUniqueNonce(self::SERVER, '2') );
        $storage->purgeNonces($date);
        $this->assertTrue( $storage->isUniqueNonce(self::SERVER, '1') );
        $this->assertFalse( $storage->isUniqueNonce(self::SERVER, '2') );
        $storage->purgeNonces();
        $this->assertTrue( $storage->isUniqueNonce(self::SERVER, '1') );
        sleep(2);
        $date = time();
        sleep(2);
        $this->assertTrue( $storage->isUniqueNonce(self::SERVER, '2') );
        $storage->purgeNonces($date);
        $this->assertTrue( $storage->isUniqueNonce(self::SERVER, '1') );
        $this->assertFalse( $storage->isUniqueNonce(self::SERVER, '2') );
        $storage->purgeNonces();
        $this->assertTrue( $storage->isUniqueNonce(self::SERVER, '1') );
        $this->assertTrue( $storage->isUniqueNonce(self::SERVER2, '1') );
        $storage->purgeNonces();
    }
}
