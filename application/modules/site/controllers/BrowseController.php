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
  	
    	// only category is set, display just category
    		 
//     	if($this->facadeAcl->isAdmin($this->_member_id)) echo "JSI ADMIN";
    	 
    	 
//     	// build search engine, if category choosed build index just on the particular category
//     	$facadeSearchEngine = new \App\Facade\SearchEngineFacade($this->_em);
//     	$facadeSearchEngine->buildProjectIndexes(array('category'=>$this->_request->getParam('category')));
    	 
    	 
//     	$query = "";
    	
    	
    
//     		// just category is set, donˇt use
//     		if ( !empty($_GET['category']) && empty($_GET['q']) && empty($_GET['priority']) &&  empty($_GET['level']) &&  empty($_GET['project_role'])) {
//     			debug("Vsechno je prazdne jenom kategorie je nastavena. Nepouzivej vyhledavani.");
//     		}
    		 	 
//     		// set default in form
//     		if (isset($_GET['q'])) {
//     			$form->setDefaults($_GET);
    			 
//     			// add keyword query
//     			// project roles
//     			if(trim( $_GET['q'] ) !== "" ){
//     				$query = "".$_GET['q']."";
    				 
//     			}	 
//     		}
    	

    
    	
//     	$this->view->query = $query;

//     	$paginator = $facadeSearchEngine->findProjectPaginator($query);
     	
    	// just select data from DB
    	// searching in categories
//      	if(isset($_GET["category"]) AND trim( $_GET['q'] ) == ""){
//      		// Feeding projects
//      		try{
//      		$paginator = $facadeProject->findAllProjectsByCategory($params['category']);
    		
//      		//additional params 
//      		} catch (\Exception $e){
//      			$this->_helper->FlashMessenger(array('error' => 'This categoory is not found. Are you trying to hack us?'));
//      			$this->_redirect('/member/error/');
//      		}
//      	}
    			
//     	} else {
//     		// get all projects
//     		$paginator = $facadeProject->findAllProjectsPaginator();
//     	}
    	
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