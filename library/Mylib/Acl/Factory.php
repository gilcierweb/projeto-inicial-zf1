<?php

class Mylib_Acl_Factory
{

    private static $_sessionNameSpace = 'Mylib_Acl_Namespace';
    private static $_objAuth;
    private static $_objAclSession;
    private static $_objAcl;

    public static function get(Zend_Auth $objAuth, $clearACL = false)
    {

        self::$_objAuth = $objAuth;
        self::$_objAclSession = new Zend_Session_Namespace(self::$_sessionNameSpace);

        if ($clearACL) {
            self::_clear();
        }

        if (isset(self::$_objAclSession->acl)) {
            return self::$_objAclSession->acl;
        } else {
            return self::_loadAclFromDB();
        }
    }

    private static function _clear()
    {
        unset(self::$_objAclSession->acl);
    }

    private static function _saveAclToSession()
    {
        self::$_objAclSession->acl = self::$_objAcl;
    }

    private static function _loadAclFromDB()
    {
        $arrRoles = Entities_RoleTable::getAll();
        $arrResources = Entities_ResourceTable::getAll();
        $arrRoleResources = Entities_RoleResourceTable::getAll();

        self::$_objAcl = new Zend_Acl();

        self::$_objAcl->addRole(new Zend_Acl_Role(Entities_RoleTable::getGuestRoleName()));
        foreach ($arrRoles as $role) {
            self::$_objAcl->addRole(new Zend_Acl_Role($role['role']));
        }

        // add all resources to the acl
        foreach ($arrResources as $resource) {
            self::$_objAcl->add(new Zend_Acl_Resource($resource['module'] . '::' . $resource['controller'] . '::' . $resource['action']));
        }

        // allow roles to resources
        foreach ($arrRoleResources as $roleResource) {
            self::$_objAcl->allow($roleResource['role']['roleName'], $roleResource['resource']['module'] . '::' . $roleResource['resource']['controller'] . '::' . $roleResource['resource']['action']);
        }

        self::_saveAclToSession();
        return self::$_objAcl;
    }

}
