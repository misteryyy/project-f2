<?php
class Member_MyFavouriteController extends  Boilerplate_Controller_Action_Abstract
{
	
	private $project_id; // int id
	private $project;  // project object
	private $facadeComment;
	private $facadeProject;
	private $facadeProjectUpdate;

	public function init(){	
		parent::init();
		// check project existance for user and project
		$this->facadeProject = new \App\Facade\ProjectFacade($this->_em);	
		$this->facadeComment = new \App\Facade\Project\CommentFacade($this->_em);
		$this->facadeProjectUpdate = new \App\Facade\Project\UpdateFacade($this->_em);
	}


    /**
     * Display Favourite projects
     */
    public function projectAction()
    {
    	$this->view->pageTitle = "My Favourite Projects" ;
    		
    	if($this->_request->isPost() || $this->_request->isGet()){
    		switch ($this->_request->getParam("_method")){
    	
    			//  create new question
    			case 'like-project':
    				try{
    					$facadeProject = new \App\Facade\ProjectFacade($this->_em);
    					// return count of current followers
    					$data = $facadeProject->likeProject($this->_member_id, $this->_request->getParam('project_id'));
    	
    					$this->_helper->FlashMessenger( array('success' =>  "Project was unliked."));
    					$params = array('id' => $this->project_id);
    					$this->_helper->redirector('project', $this->getRequest()->getControllerName(), $this->getRequest()->getModuleName(), $params);
    					
    					break;
    				}catch(Exception $e){
    					$this->_helper->FlashMessenger( array('error' =>  $e->getMessage()));	 
    				}
    				break;
    		}
    	}
    	
    	// get categories for form
    	$facadeProject = new \App\Facade\ProjectFacade($this->_em);
    	$paginator = $facadeProject->findAllFavouriteProjectsForUserPaginator($this->_member_id);
    	$paginator->setItemCountPerPage(10); // items per page
    	$paginator->setCurrentPageNumber($this->_request->getParam('page', 1));
    	$this->view->paginator = $paginator;
    }
    
    
    
    /**
     * Display Favourite users
     */
    public function memberAction()
    {
    	$this->view->pageTitle = "My Favourite Members" ;
    
    	if($this->_request->isPost() || $this->_request->isGet()){
    		switch ($this->_request->getParam("_method")){
    			 
    			//  create new question
    			case 'like-user':
    				try{
    					$facadeUser = new \App\Facade\UserFacade($this->_em);
    					// return count of current members
    					$data = $facadeUser->likeMember($this->_member_id, $this->_request->getParam('friend_id'));
    					
    					$this->_helper->FlashMessenger( array('success' =>  "Member was unliked."));
    					$params = array('id' => $this->project_id);
    					$this->_helper->redirector('member', $this->getRequest()->getControllerName(), $this->getRequest()->getModuleName(), $params);
    						
    					break;
    				}catch(Exception $e){
    					$this->_helper->FlashMessenger( array('error' =>  $e->getMessage()));
    				}
    				break;
    		}
    	}
    	 
    	// get categories for form
    	$facadeUser = new \App\Facade\UserFacade($this->_em);
    	$paginator = $facadeUser->findAllFavouriteUsersForUserPaginator($this->_member_id);
    	$paginator->setItemCountPerPage(10); // items per page
    	$paginator->setCurrentPageNumber($this->_request->getParam('page', 1));
    	$this->view->paginator = $paginator;
    
    }
    
    
    
    
}





