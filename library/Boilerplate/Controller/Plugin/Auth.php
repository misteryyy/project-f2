<?php
class Boilerplate_Controller_Plugin_Auth extends Zend_Controller_Plugin_Abstract {

    /**
     * @var Zend_Auth
     */
    protected $_auth;
    
    public function __construct(Zend_Auth $auth) {
        $this->_auth = $auth;
    }
    
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request) {       
        ////Check if the user is not logged in
        if (!$this->_auth->hasIdentity()){
        	
        	
        	
        	//access to member module only in index controller
        	if( 'member' == $request->getModuleName() && 'index' != $request->getControllerName()  ) { //everething in index controller is public
				return $this->_redirect($request, 'index', 'login', 'member');
        	}
        	
        	//access to member module only in index controller
        	if( 'admin' == $request->getModuleName() ) { //everething in index controller is public
        		return $this->_redirect($request, 'index', 'login', 'member');
        	}

        	// browse members just for logged users
        	//access to member module only in index controller
        	if( 'site' == $request->getModuleName() && 'browse' == $request->getControllerName() && 'member' == $request->getActionName() ) { //everething in index controller is public
        
			return $this->_redirect($request, 'index', 'login', 'member');
        	}
        	
        	if( 'member-profile' == $request->getModuleName()) { //everething in index controller is public	
        		return $this->_redirect($request, 'index', 'login', 'member');
        	}
        	
        	// browse members just for logged users
        	//access to member module only in index controller
        	if( 'project' == $request->getModuleName() 
        	  && 'index' == $request->getControllerName() 
        	 && ('comments' == $request->getActionName() || 
        	 		'project-board' == $request->getActionName() ) ) { //everething in index controller is public
        	
        		return $this->_redirect($request, 'index', 'login', 'member');
        	}
        	 	
        	
        	
        }
    }
    
    protected function _redirect($request, $controller, $action, $module,$message ="") {

    	if ($request->getControllerName() == $controller &&
                $request->getActionName() == $action &&
                $request->getModuleName() == $module) {
            return true;
        }

        $url = Zend_Controller_Front::getInstance()->getBaseUrl();
        $url .= '/' . $module . '/' . $controller . '/' . $action;
  
        if (DEBUG) {
        //        debug_redirect($url);
         }

        
        $flashMessenger = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger'); 
        //$flashMessenger->( array ('error' => $e->getMessage () ) );
        $flashMessenger->addMessage(array ('error' => "You have to be logged in to access this page :).") );
        $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
        $redirector->gotoUrl('/login')
        ->redirectAndExit();
      //  debug($request);
    }

}

?>
