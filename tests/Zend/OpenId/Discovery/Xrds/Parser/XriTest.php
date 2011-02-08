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
namespace ZendTest\OpenId\Discovery\Xrds\Parser;

use Zend\OpenId\OpenId,
    Zend\OpenId\Discovery\Xrds\Element,
    Zend\OpenId\Discovery\Xrds\Parser\Xri as Parser,
    Zend\OpenId\Discovery,
    Zend\OpenId\Identifier;

/**
 * @category   Zend
 * @package    Zend_OpenId
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_OpenId
 */
class XriTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {}

    public function testParser()
    {
        $types = array(
            "http://lid.netmesh.org/sso/2.0",
            "http://lid.netmesh.org/sso/1.0",
            "http://lid.netmesh.org/custom/2.0",
        );
        $uris = array(
            "http://www.test.ws/?id=1",
            "http://www.test.ws/?id=2",
        );

        $parser = new Parser();
        $xrds =  file_get_contents(dirname(__FILE__) . '/xrds_files/yadis.simple.xml');
        $descriptor = $parser->parse($xrds);

        $this->assertInstanceOf('\Zend\OpenId\Discovery\Xrds\Element\Descriptor', $descriptor);
        
        // get all services
        $services = $descriptor->getServices();

    }
    public function testParseFailedException()
    {
        $this->setExpectedException(
            '\Zend\OpenId\Discovery\Xrds\Exception\ParseFailedException', 
            "Couldn't find end of Start Tag XRDS");

        $parser = new Parser();
        $xrds =  file_get_contents(dirname(__FILE__) . '/xrds_files/xri.broken.xml');
        $descriptor = $parser->parse($xrds);
    }
}

