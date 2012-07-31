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
     * Delete update for Project
     */
    public function updateDeleteAction()
    {
    	$this->checkProjectAndUser();
    	$update = $this->checkUpdate(); // returns update
    
    	try{ // update project data
    		$this->facadeProjectUpdate->deleteUpdate($this->_member_id, $this->project_id, $update->id);
    		$this->_helper->FlashMessenger( array('success' =>  "Update has been deleted"));
    		$params = array('id' => $this->project_id);
    		$this->_helper->redirector('update', $this->getRequest()->getControllerName(), $this->getRequest()->getModuleName(), $params); 
    		
    	} catch (\Exception $e){
    		$this->_helper->FlashMessenger( array('error' =>  $e->getMessage()));
    	}
    	
    }
    


    /**
     * Display Favourite projects
     */
    public function projectAction()
    {
    	$this->view->pageTitle = "My Favourite Projects" ;
    	// get categories for form
    	$facadeProject = new \App\Facade\ProjectFacade($this->_em);
    	$paginator = $facadeProject->findAllFavouriteProjectsForUserPaginator($this->_member_id);
    	$paginator->setItemCountPerPage(1); // items per page
    	$paginator->setCurrentPageNumber($this->_request->getParam('page', 1));
    	$this->view->paginator = $paginator;

    }
    
    
}





