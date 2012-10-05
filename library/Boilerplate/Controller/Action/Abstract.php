<?php

abstract class Boilerplate_Controller_Action_Abstract extends Zend_Controller_Action {

   protected $_member = array(); 
   protected $loggedMember;
   protected $_member_id = 0;
   protected $facadeAcl;
   protected $facadeNotification;
   
   /**
    * @var Doctrine\ORM\EntityManager
    */
   protected $_em = null;
   
   /**
    * @var \sfServiceContainer
    */
   protected $_sc = null;
   
   /**
    * @var \App\Service\RandomQuote
    * @InjectService RandomQuote
    */
   protected $_randomQuote = null;
      
 /**
  * Disable layout, prepare for Ajax
  */ 
 public function ajaxify(){
 	if (Zend_Controller_Action_HelperBroker::hasHelper('layout')) {
 		$this->_helper->layout->disableLayout();
 	}
 	$this->_helper->viewRenderer->setNoRender(true);
 }  
   
 
 
 /**
  * Doctrine Debug
  * @param unknown_type $var
  */
 public function dPr($var){
 	\Doctrine\Common\Util\Debug::dump($var);
 }
 
 /**
  * Init all roles and permissions 
  * @see Zend_Controller_Action::init()
  */
 public function init(){
	// Setting up the instance for user who is logged or not
 	$this->_em = Zend_Registry::get('em');
	$this->view->em = $this->_em;
	
	$this->isLogged = false;
	$this->isAdmin = false;
	$this->view->isLogged = false;
	$this->view->isAdmin = false;
	
  	if(Zend_Auth::getInstance()->hasIdentity()){
    		$this->_member = Zend_Auth::getInstance()->getIdentity();
    		$this->_member_id = $this->_member['id'];
    		
    		// save user object, used for checking if this object is in projects or in users
    		$facadeUser = new \App\Facade\UserFacade($this->_em);
    		$this->view->loggedMember = $facadeUser->findOneUser($this->_member_id);
    		$this->loggedMember = $this->view->loggedMember;
    		// for permission checking
    		$this->facadeAcl = new \App\Facade\ACLFacade($this->_em);
    		
    		// ACL permission settings
    		$this->view->isLogged = true;
    		$this->isLogged = true;
    		$this->view->isAdmin = $this->facadeAcl->isAdmin($this->loggedMember->id);
  			$this->isAdmin = $this->view->isAdmin;
  			
  	}else {
  			$notRegisteredUser = new \App\Entity\User();
  				$role_visitor = new \App\Entity\UserRole();
  				$role_visitor->setName(\App\Entity\UserRole::SYSTEM_ROLE_VISITOR);
  				$role_visitor->setType(\App\Entity\UserRole::TYPE_SYSTEM);
  				$notRegisteredUser->setEmail("unregistered@floplatform.com");
  				$notRegisteredUser->setName("Unregistered");
  				// setting roles
  				$notRegisteredUser->addRole($role_visitor);
    		$this->loggedMember = $notRegisteredUser;	
    		$this->_member;
   		
  	}

  // Navigation settings
  $uri = $this->_request->getPathInfo();           
  $activeNav = $this->view->navigation()->findByUri($uri);
  if(isset($activeNav)){
  	$activeNav->active = true;
  }
  debug($activeNav);
 
 } 


}