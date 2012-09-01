<?php
class MemberProfile_IndexController extends  Boilerplate_Controller_Action_Abstract
{	
	private $facadeFlobox;
	private $facadeUser;
	private $user;
	private $user_id;
	
	public function init(){
		parent::init();
		// check project existance for user and project
		$this->facadeFlobox = new \App\Facade\Member\FloBoxFacade($this->_em);
		$this->facadeUser = new \App\Facade\UserFacade($this->_em);
		$this->checkUser(); // check the id
		$this->view->module = "member"; // enable to add id into breadcrumbs
	}

	/*
	 * Check all neccessary things
	*/
	public function checkUser(){		
	
		
		try{
			$id = $this->_request->getParam("id");
			// check id param for project
			if(!is_numeric($id)){
				throw new \Exception("Member not found.");
			}	
			
			// init basic things
			$this->user = $this->facadeUser->findOneUser($id);
			$this->user_id = $id;
			$this->view->user = $this->user;
			
			
		} catch (\Exception $e){			
			$this->_redirect('/error/member-not-found');
		}
	}

	/**
	 * Display members projects
	 */
	public function widgetMyProjectAction(){
		$facadeProject = new \App\Facade\ProjectFacade($this->_em);
		$paginator = $facadeProject->findAllProjectsForUserPaginator($this->user_id);
		$paginator->setItemCountPerPage(5);
		$page = $this->_request->getParam('page', 1);
		$paginator->setCurrentPageNumber($page);
		$this->view->paginator = $paginator;
		
	}
	
	/**
	 * Display favourite projects
	 */
	public function widgetMyFavouriteProjectAction(){
		
		// get categories for form
		$facadeProject = new \App\Facade\ProjectFacade($this->_em);
		$paginator = $facadeProject->findAllFavouriteProjectsForUserPaginator($this->user_id);
		$paginator->setItemCountPerPage(10); // items per page
		$paginator->setCurrentPageNumber($this->_request->getParam('page', 1));
		$this->view->paginator = $paginator;
	
	}
	
	/**
	 * Display favourite members
	 */
	public function widgetMyFavouriteMemberAction(){
	// get categories for form
   	$facadeUser = new \App\Facade\UserFacade($this->_em);
    	$paginator = $facadeUser->findAllFavouriteUsersForUserPaginator($this->user_id);
    	$paginator->setItemCountPerPage(10); // items per page
    	$paginator->setCurrentPageNumber($this->_request->getParam('page', 1));
    	$this->view->paginator = $paginator;
	}
	
	/**
	 * Display members projects
	 */
	public function widgetMyRoleAction(){
	}
	
	/**
	 * Display members projects
	 */
	public function widgetMyCollaborationAction(){
	
		
		$facadeCollaboration = new \App\Facade\Project\CollaborationFacade($this->_em);
		$paginator = $facadeCollaboration->findApplicationsPaginator($this->user_id, array('state'=>\App\Entity\ProjectApplication::APPLICATION_ACCEPTED ));
		
		$paginator->setItemCountPerPage(5);
		$page = $this->_request->getParam('page', 1);
		$paginator->setCurrentPageNumber($page);
		$this->view->paginator = $paginator;
	
	}
	
	/**
	 * Displazy created projects for this user
	 */
	public function myProjectAction()
	{
		$this->view->pageTitle = "Published Projects" ;
		// get categories for form
		$facadeProject = new \App\Facade\ProjectFacade($this->_em);
		$projects = $facadeProject->findAllProjectsForUser($this->user_id);
		$this->view->projects = $projects;
	
	}
	
	
	/**
	 * Display Collaborations for this user
	 */
	public function myCollaborationAction()
	{
		$this->view->pageTitle = "My Collaborations" ;
		// get categories for form
		$facadeCollaboration = new \App\Facade\Project\CollaborationFacade($this->_em);
		$applications = $facadeCollaboration->findApplications($this->user_id, array('state'=>\App\Entity\ProjectApplication::APPLICATION_ACCEPTED ));
		$this->view->applications = $applications;
	
	}
	
	
    /**
     * Public Profile for Everybody
     */
    public function indexAction()
    {	 	
    	
		$this->view->pageTitle = $this->user->getName() ." 's Profile " ;
    }
    
    /**
     * Display Favourite projects
     */
    public function favouriteProjectAction()
    {
    	$this->view->pageTitle = "My Favourite Projects" ;
    
    	// get categories for form
    	$facadeProject = new \App\Facade\ProjectFacade($this->_em);
    	$paginator = $facadeProject->findAllFavouriteProjectsForUserPaginator($this->user_id);
    	$paginator->setItemCountPerPage(10); // items per page
    	$paginator->setCurrentPageNumber($this->_request->getParam('page', 1));
    	$this->view->paginator = $paginator;
    }
    
    
    /**
     * Display Favourite users
     */
    public function favouriteMemberAction()
    {
    	$this->view->pageTitle = "My Favourite Members" ;
    	// get categories for form
    	$facadeUser = new \App\Facade\UserFacade($this->_em);
    	$paginator = $facadeUser->findAllFavouriteUsersForUserPaginator($this->user_id);
    	$paginator->setItemCountPerPage(10); // items per page
    	$paginator->setCurrentPageNumber($this->_request->getParam('page', 1));
    	$this->view->paginator = $paginator;
    }
    
    /**
     * Public Profile for Everybody
     */
    public function floboxAction()
    {  	
    	$this->view->pageTitle = 'FloBox' ;
    
    	$zend_paginator = $this->facadeFlobox->findFloMessages($this->user_id);
    	$zend_paginator->setItemCountPerPage(10); // items per page
    	$page = $this->_request->getParam('page', 1);
    	$zend_paginator->setCurrentPageNumber($page);
    	$this->view->paginator = $zend_paginator;
    	
     }
    
     /**
      * Check if the message is really for this owner
      */
     public function checkFloboxMessage(){ 
     	try{
     		// checking message
     		$message_id = $this->_request->getParam("message_id");
     		// check id param for project
     		if(!is_numeric($message_id)){
     			throw new \Exception('This FloBox message is not found, are you trying to hack us? :D ');
     		}
     			
     		return $this->facadeFlobox->findOneMessage($this->_member_id,$message_id);
     	
     	}catch(\Exception $e){
     		$this->_helper->FlashMessenger(array('error' => $e->getMessage()));
     		$this->_redirect('/error/');
     	}
     }
     
     
    /**
     * Detail of floBox message
     */
    public function floboxDetailAction()
    {	
    		$message = $this->checkFloboxMessage();
			$form = new \App\Form\Member\FloBoxCommentForm($this->_member, $message->id);
    		
			// validation
			if ($this->_request->isPost()) {
				if ($form->isValid($this->_request->getPost())) {
					try{
						$values = $form->getValues();
						$this->facadeFlobox->createComment($this->_member_id, $values['flobox_id'],$values);
					
						$this->_helper->FlashMessenger( array('success' =>  "Comment has been added."));
					} catch (\Exception $e){
						$this->_helper->FlashMessenger( array('error' =>  $e->getMessage()));
					}
				}
				// not validated properly
				else {
					$this->_helper->FlashMessenger( array('error' => "Please check your input."));
				}
			}
    		$this->view->form = $form;
    		$this->view->message = $message;
    		$this->view->pageTitle = 'FloBox Detail' ; 	 
    }
    
    
}





