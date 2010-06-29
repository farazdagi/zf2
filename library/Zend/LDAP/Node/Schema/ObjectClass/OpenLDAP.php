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
 * @package    Zend_LDAP
 * @subpackage Schema
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

/**
 * @namespace
 */
namespace Zend\LDAP\Node\Schema\ObjectClass;
use Zend\LDAP\Node\Schema;

/**
 * Zend_LDAP_Node_Schema_ObjectClass_OpenLDAP provides access to the objectClass
 * schema information on an OpenLDAP server.
 *
 * @uses       \Zend\LDAP\Node\Schema\Schema
 * @uses       \Zend\LDAP\Node\Schema\Item
 * @uses       \Zend\LDAP\Node\Schema\ObjectClass\ObjectClassInterface
 * @category   Zend
 * @package    Zend_LDAP
 * @subpackage Schema
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class OpenLDAP 
    extends Schema\Item
    implements ObjectClassInterface
{
    /**
     * All inherited "MUST" attributes
     *
     * @var array
     */
    protected $_inheritedMust = null;
    /**
     * All inherited "MAY" attributes
     *
     * @var array
     */
    protected $_inheritedMay = null;


    /**
     * Gets the objectClass name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Gets the objectClass OID
     *
     * @return string
     */
    public function getOid()
    {
        return $this->oid;
    }

    /**
     * Gets the attributes that this objectClass must contain
     *
     * @return array
     */
    public function getMustContain()
    {
        if ($this->_inheritedMust === null) {
            $this->_resolveInheritance();
        }
        return $this->_inheritedMust;
    }

    /**
     * Gets the attributes that this objectClass may contain
     *
     * @return array
     */
    public function getMayContain()
    {
        if ($this->_inheritedMay === null) {
            $this->_resolveInheritance();
        }
        return $this->_inheritedMay;
    }

    /**
     * Resolves the inheritance tree
     *
     * @return void
     */
    protected function _resolveInheritance()
    {
        $must = $this->must;
        $may = $this->may;
        foreach ($this->getParents() as $p) {
            $must = array_merge($must, $p->getMustContain());
            $may = array_merge($may, $p->getMayContain());
        }
        $must = array_unique($must);
        $may = array_unique($may);
        $may = array_diff($may, $must);
        sort($must, SORT_STRING);
        sort($may, SORT_STRING);
        $this->_inheritedMust = $must;
        $this->_inheritedMay = $may;
    }

    /**
     * Gets the objectClass description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->desc;
    }

    /**
     * Gets the objectClass type
     *
     * @return integer
     */
    public function getType()
    {
        if ($this->structural) {
            return Schema\Schema::OBJECTCLASS_TYPE_STRUCTURAL;
        } else if ($this->abstract) {
            return Schema\Schema::OBJECTCLASS_TYPE_ABSTRACT;
        } else if ($this->auxiliary) {
            return Schema\Schema::OBJECTCLASS_TYPE_AUXILIARY;
        } else {
            return Schema\Schema::OBJECTCLASS_TYPE_UNKNOWN;
        }
    }

    /**
     * Returns the parent objectClasses of this class.
     * This includes structural, abstract and auxiliary objectClasses
     *
     * @return array
     */
    public function getParentClasses()
    {
        return $this->sup;
    }

    /**
     * Returns the parent object classes in the inhertitance tree if one exists
     *
     * @return array of \Zend\LDAP\Node\Schema\ObjectClass\OpenLDAP
     */
    public function getParents()
    {
        return $this->_parents;
    }
}