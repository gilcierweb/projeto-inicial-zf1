<?php

/*
 * INSERT INTO  `projeto_inicial`.`acl_to_roles` (
  `id` ,
  `acl_id` ,
  `role_id`
  )
  VALUES (
  NULL ,  '1',  '1'
  ), (
  NULL ,  '2',  '1'
  );
 * 
 */

class Mylib_Controller_Plugin_Acl extends Zend_Controller_Plugin_Abstract
{

    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {

        $auth = Zend_Auth::getInstance();
        Zend_Debug::dump($auth->getIdentity());
        $authModel = new Application_Model_Auth();

        if (!$auth->hasIdentity()) {
            //If user doesn't exist it will get the Guest account from "users" table Id=1
            $authModel->authenticate(array('login' => 'Guest', 'password' => 'shocks'));
        }

        $request = $this->getRequest();
        $aclResource = new Application_Model_AclResource();

        //Check if the request is valid and controller an action exists. If not redirects to an error page.
//          Zend_Debug::dump($request);
//        Zend_Debug::dump($aclResource->resourceValid($request));
        if ($aclResource->resourceValid($request) === false) {
            $request->setModuleName('adm');
            $request->setControllerName('error');
            $request->setActionName('error');
//            $request->setActionName('accessdenied');
            return;
        }
        $module = $request->getModuleName();
        $controller = $request->getControllerName();
        $action = $request->getActionName();
        //Check if the requested resource exists in database. If not it will add it
        if (!$aclResource->resourceExists($module, $controller, $action)) {
            $aclResource->createResource($module, $controller, $action);
        }
        //Get role_id
        $role_id = $auth->getIdentity()->role_id;
        $role = Application_Model_Role::getById($role_id);

        $role = $role[0]->role;
        // setup acl
        $acl = new Zend_Acl();
        // add the role
        $acl->addRole(new Zend_Acl_Role($role));
        if ($role_id == 3) {//If role_id=3 "Admin" don't need to create the resources           
            $acl->allow($role);
        } else {

            //Create all the existing resources
            $resources = $aclResource->getAllResources();
//            Zend_Debug::dump($resources);
            // Add the existing resources to ACL
            foreach ($resources as $resource) {

                $resource->getModule();
                $resource->getController();
                $resource->getAction();
                $acl->add(new Zend_Acl_Resource($resource->getController()));
            }
            //Create user AllowedResources
            $userAllowedResources = $aclResource->getCurrentRoleAllowedResources($role_id);
//            Zend_Debug::dump($userAllowedResources);
            // Add the user permissions to ACL
            foreach ($userAllowedResources as $controllerName => $allowedActions) {
                $arrayAllowedActions = array();
                foreach ($allowedActions as $allowedAction) {
                    $arrayAllowedActions[] = $allowedAction;
                }
                $acl->allow($role, $controllerName, $arrayAllowedActions);
            }
        }

        //Check if user is allowed to acces the url and redirect if needed
//        if (!$acl->isAllowed($role, $controller . '/' . $action)) {die('aqui');
//            $request->setModuleName('adm');
//            $request->setControllerName('error');
//            $request->setActionName('accessdenied');
//
////            $request->setModuleName('auth');
////            $request->setControllerName('auth');
////            $request->setActionName('login'); 
//
//            return;
//        }
        //Check if user is allowed to acces the url and redirect if needed
        if (!$acl->isAllowed($role, $controller, $action)) {
            $request->setModuleName('adm');
            $request->setControllerName('error');
            $request->setActionName('accessdenied');
            return;
        }
//        echo '<pre>';print_r($arrayAllowedActions);die('gil');
    }

}

//class Mylib2_Controller_Plugin_ACL extends Zend_Controller_Plugin_Abstract
//{
//
//    public function preDispatch(Zend_Controller_Request_Abstract $request)
//    {
//        $objAuth = Zend_Auth::getInstance();
//        $clearACL = false;
//
//        // initially treat the user as a guest so we can determine if the current
//        // resource is accessible by guest users
//        $role = 'guest';
//
//        // if its not accessible then we need to check for a user login
//        // if the user is logged in then we check if the role of the logged
//        // in user has the credentials to access the current resource
//
//        try {
//            if ($objAuth->hasIdentity()) {
//                $arrUser = $objAuth->getIdentity();
//
//                $sess = new Zend_Session_Namespace('Mylib_ACL');
//                if ($sess->clearACL) {
//                    $clearACL = true;
//                    unset($sess->clearACL);
//                }
//
//                $objAcl = DJC_ACL_Factory::get($objAuth, $clearACL);
//
//                if (!$objAcl->isAllowed($arrUser['role'], $request->getModuleName() . '::' . $request->getControllerName() . '::' . $request->getActionName())) {
//                    $request->setModuleName('default');
//                    $request->setControllerName('error');
//                    $request->setActionName('noauth');
//                }
//            } else {
//                $objAcl = DJC_ACL_Factory::get($objAuth, $clearACL);
//                if (!$objAcl->isAllowed($role, $request->getModuleName() . '::' . $request->getControllerName() . '::' . $request->getActionName())) {
//                    return Zend_Controller_Action_HelperBroker::getStaticHelper('redirector')->setGotoRoute(array(), "login");
//                }
//            }
//        } catch (Zend_Exception $e) {
//            $request->setModuleName('default');
//            $request->setControllerName('error');
//            $request->setActionName('noresource');
//        }
//    }

//    protected function _initPlugins()
//    {
//        $front = Zend_Controller_Front::getInstance();
//        $front->registerPlugin(new DJC_Controller_Plugin_ACL(), 1);
//    }

//}
