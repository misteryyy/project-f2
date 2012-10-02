<?php

class Site_BrowseController extends Boilerplate_Controller_Action_Abstract
{
	
 	/**
 	 * Browse Members
 	 */
    public function memberAction()
    {   
    		// find users
    	$facadeUser = new \App\Facade\UserFacade($this->_em);	
		$form = new \App\Form\Site\BrowseMemberForm();
    	$this->view->form = $form;
    	
    	$query = "";
    	// set default in form
    	if (isset($_GET['q'])) {
    		// build search engine
    		$facadeSearchEngine = new \App\Facade\SearchEngineFacade($this->_em);
    		$facadeSearchEngine->buildMemberIndexes();
    		
    		
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
    		
    			$options = $this->_request->getParams();
    				$options['unbanned'] = true; // just user who don't have ban

    			$paginator = $facadeUser->findAllUsersPaginator($this->_member_id,$options); 
	    		$config = Zend_Registry::get('config');
	    		$paginator->setItemCountPerPage($config['app']['project']['count_per_page']); // items per page
	    		$page = $this->_request->getParam('page', 1);
	    		$paginator->setCurrentPageNumber($page);
	    		$this->view->paginator = $paginator; // $paginator;
    	}

    	
     	
    	
	}
      

	public function allIsEmpty(){
		
		if( empty($_GET['type']) && empty($_GET['category']) && empty($_GET['q']) && empty($_GET['priority']) &&  empty($_GET['level']) &&  empty($_GET['project_role']) ){
			return true;
		}else return false;
	}
	
	public function allIsEmptyExceptType(){
	
		if( !empty($_GET['type']) && empty($_GET['category']) && empty($_GET['q']) && empty($_GET['priority']) &&  empty($_GET['level']) &&  empty($_GET['project_role']) ){
			return true;
		}else return false;
	}
	
	
	public function allIsEmptyExceptCategory(){
	
		if( empty($_GET['type']) && !empty($_GET['category']) && empty($_GET['q']) && empty($_GET['priority']) &&  empty($_GET['level']) &&  empty($_GET['project_role']) ){
			return true;
		}else return false;
	}
	
	public function useSearchEngineWithCategory(){
		if( empty($_GET['type']) && 
		    !empty($_GET['category']) && 
			( !empty($_GET['q']) || !empty($_GET['priority']) ||  !empty($_GET['level'])  || !empty($_GET['project_role']) ) ) {
			return true;
		}else return false;
		
	}
	
	public function useSearchEngineWithoutCategory(){
		if( empty($_GET['type']) &&
				empty($_GET['category']) &&
				( !empty($_GET['q']) || !empty($_GET['priority']) ||  !empty($_GET['level'])  || !empty($_GET['project_role']) ) ) {
			return true;
		}else return false;
	
	}
	
	
	/**
	 * Creates query for searching in projects
	 */
	public function createMemberSearchQuery(){
		
		$query = "";
		
		if (isset($_GET['q'])) { // fultext queery is set

			// add keyword query
			// project roles
			if(trim( $_GET['q'] ) !== "" ){
				$query = "'".$_GET['q']."'";
		
			}
		
			// project roles
			if(isset($_GET['project_role'])){
				sort($_GET['project_role']);
				 
				if(!empty($query)){
					$query .=  " AND ";
				}
				$query .=  "project_roles:".implode(' AND project_roles:',$_GET['project_role']);
			}
			
			if(!empty($_GET['level'])){
				if(!empty($query)){
					$query .=  " AND ";
				}
				$query .= "level:".$_GET['level']."";
			}
			
			if(!empty($_GET['priority'])){
				if(!empty($query)){
					$query .=  " AND ";
				}
				$query .= "priority:".$_GET['priority']."";
			}
			
			
		}
		return $query;
	}
	
    /**
     * Browse Projects
     */
    public function projectAction(){
    	// read params from url
    	$params = $this->_request->getParams();
    	$facadeProject = new \App\Facade\Site\ProjectFacade($this->_em);
    	$categories = $facadeProject->findAllProjectCategoriesArray();
    	$form = new \App\Form\Site\BrowseProjectForm($categories);
    	$this->view->form = $form;
		$this->view->displayForm = true;	
    	// nothing set -> display all
    	if ( $this->allIsEmpty() ) {
    		$query = "Display all";
    		$paginator = $facadeProject->findAllProjectsPaginator();
    	} 
    	
    	// just category is set, no fulltext search
    	if( $this->allIsEmptyExceptCategory()){
    		try{
    			$paginator = $facadeProject->findAllProjectsByCategory($params['category']);
    			$category = $facadeProject->findCategoryById($params['category']);
    			$query = "Display all in " . $category->name;	
    			
    		} catch (\Exception $e){
    			$this->_helper->FlashMessenger(array('error' => 'This categoory is not found. Are you trying to hack us?'));
    			$this->_redirect('/member/error/');
    		}
    	}
    	
    	// nothing set -> display all
    	if ( $this->allIsEmptyExceptType() ) {
    		$this->view->displayForm = false; // hide form 
    		$query = "Display ".$params['type'];
    		$paginator = $facadeProject->findAllProjectsPaginator(array('type'=> $params['type']));
    	}
  	
    	// SEARCH ENGINE USAGE
    	if($this->useSearchEngineWithCategory()){
    		$facadeSearchEngine = new \App\Facade\SearchEngineFacade($this->_em);
    		$facadeSearchEngine->buildProjectIndexes(array('category'=>$this->_request->getParam('category')));
    		$engine_query = $this->createMemberSearchQuery();
    		$query = "Display results from engine with categories : ". $engine_query;
    		
    		$paginator = $facadeSearchEngine->findProjectPaginator($engine_query);
	
    	}
    	
    	// SEARCH ENGINE 
    	if($this->useSearchEngineWithoutCategory()){
    		$facadeSearchEngine = new \App\Facade\SearchEngineFacade($this->_em);
    		$facadeSearchEngine->buildProjectIndexes(array('category'=>$this->_request->getParam('category')));
    		
    		$engine_query = $this->createMemberSearchQuery();
    		$query = "Display results from all and engine ". $engine_query;
    		$paginator = $facadeSearchEngine->findProjectPaginator($engine_query);
    	}
 	
    	// if  nothing is  set show all projects
    	$config = Zend_Registry::get('config');
    	$paginator->setItemCountPerPage($config['app']['project']['count_per_page']); // items per page
    	$page = $this->_request->getParam('page', 1);
    	$paginator->setCurrentPageNumber($page);
    	$this->view->paginator = $paginator;
    	$this->view->query = $query;
 		// set values to form   	
    	$form->setDefaults($_GET);
	
    }
     
     
   
}