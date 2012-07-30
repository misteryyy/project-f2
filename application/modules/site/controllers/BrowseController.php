<?php

class Site_BrowseController extends Boilerplate_Controller_Action_Abstract
{
	
 	/**
 	 * Browse Members
 	 */
    public function memberAction()
    {    
    	// build search engine
    	$facadeSearchEngine = new \App\Facade\SearchEngineFacade($this->_em);
    	$facadeSearchEngine->buildMemberIndexes();
    	
    	// find users
    	$facadeUser = new \App\Facade\UserFacade($this->_em);	
		$form = new \App\Form\Site\BrowseMemberForm();
    	$this->view->form = $form;

    	$query = "";
    	// set default in form
    	if (isset($_GET['q'])) {
    		$form->setDefaults($_GET);
    				
    		// add keyword query
    		// project roles
    		if(trim( $_GET['q'] ) !== "" ){
    			$query = "name:'".$_GET['q']."'";
    		
    		}
    		
    		// specific roles
			if(isset($_GET['specific_role'])){
			    sort($_GET['specific_role']);
			    if(trim( $_GET['q'] ) !== ""){$query .=  " AND "; }
			    $query .=  "specific_roles:".implode(' AND specific_roles:',$_GET['specific_role']);
			}
			    	
			// project roles
			if(isset($_GET['project_role'])){
			    sort($_GET['project_role']);
			    		    
			    if(trim( $_GET['q'] ) !== "" OR isset( $_GET['specific_role'] )){
			    	$query .=  " AND ";
			    }
			    $query .=  "project_roles:".implode(' AND project_roles:',$_GET['project_role']);
			}
			   
		     	$paginator = $facadeSearchEngine->findUsersPaginator($query);
		     	$paginator->setCurrentPageNumber(1);
		     	$paginator->setItemCountPerPage(10);
		     	$this->view->query = $query;
		     	$this->view->paginator = $paginator;
    	
    	} else {
    		
	    		$paginator = $facadeUser->findAllUsersPaginator($this->_member_id,$this->_request->getParams()); 
	    		$config = Zend_Registry::get('config');
	    		$paginator->setItemCountPerPage($config['app']['project']['count_per_page']); // items per page
	    		$page = $this->_request->getParam('page', 1);
	    		$paginator->setCurrentPageNumber($page);
	    		$this->view->paginator = $paginator; // $paginator;
    	}

    	echo "Delete index one.";
    	$facadeSearchEngine->deleteUserIndex(1);
     	echo "Current Number of indexes.";
     	
    	
	}
      
	

	public function resultAction(){
		$this->ajaxify();
		
 	
 			
// 			if(count($hits)>0){
// 				$video_repository = Frontend_Model_Repositories_VideoFactory::factory();
// 				$videos_array = array();
// 				foreach ($hits as $hit) {
// 					$videos_array[] =  $video_repository->findById($hit->video_id);
// 				}
				 
		
// 				$paginator = Zend_Paginator::factory($videos_array);
// 				debug($paginator);
// 				//nastaveni poctu stranek list
// 				$paginator->setItemCountPerPage(12);
		
// 				$page = $this->_request->getParam('page', 1);
// 				$paginator->setCurrentPageNumber($page);
// 				// pass the paginator to the view to render
// 				$this->view->paginator = $paginator;
		
// 			}
// 			$this->view->results = $hits;
		
		
// 		} else {
		
// 			$this->view->results = null;
// 		}
		 
// 		$this->view->keywords = $keywords;
		
	}
	
	

	
	
    /**
     * Browse Projects
     */
    public function projectAction(){
    	// read params from url
    	$params = $this->_request->getParams();
    	$facadeProject = new \App\Facade\Site\ProjectFacade($this->_em);
    	// searching in categories
    	if(isset($params["category"])){
    		// Feeding projects
    		try{
    		$paginator = $facadeProject->findAllProjectsByCategory($params['category']);
    		
    		//additional params 
    		} catch (\Exception $e){
    			$this->_helper->FlashMessenger(array('error' => 'This categoory is not found. Are you trying to hack us?'));
    			$this->_redirect('/member/error/');
    		}
    			
    	} else {
    		// get all projects
    		$paginator = $facadeProject->findAllProjectsPaginator();
    	}
    	
    	// if  nothing is  set show all projects
    	$config = Zend_Registry::get('config');
    	$paginator->setItemCountPerPage($config['app']['project']['count_per_page']); // items per page
    	$page = $this->_request->getParam('page', 1);
    	$paginator->setCurrentPageNumber($page);
    	$this->view->paginator = $paginator;
    	

    

    		
    }
     
     
   
}