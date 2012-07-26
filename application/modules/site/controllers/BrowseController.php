<?php

class Site_BrowseController extends Boilerplate_Controller_Action_Abstract
{
	
 	/**
 	 * Browse Members
 	 */
    public function memberAction()
    {    
    	
    	// create search engine
    	//debug($this->_request->getParams());
    	
    	// find users
    	$facadeUser = new \App\Facade\UserFacade($this->_em);
    	$paginator = $facadeUser->findAllUsersPaginator($this->_member_id,$this->_request->getParams());
    	
    	$config = Zend_Registry::get('config');
    	$paginator->setItemCountPerPage($config['app']['project']['count_per_page']); // items per page
    	$page = $this->_request->getParam('page', 1);
    	$paginator->setCurrentPageNumber($page);
    	$this->view->paginator = null; // $paginator;
		$form = new \App\Form\Site\BrowseMemberForm();
    	$this->view->form = $form;
    	
    	// set default in form
    	if ($this->_request->isGet()) {
    				$form->setDefaults($_GET);
			    	// specific roles
			    	if(isset($_GET['specific_role'])){
			    		sort($_GET['specific_role']);
			    		$this->view->debug .= "VE SPECIFIC HLEDAME <strong>'".  implode(' ',$_GET['specific_role'])."'</strong>";
			    	}
			    	
			    	// project roles
			    	if(isset($_GET['project_role'])){
			    		sort($_GET['project_role']);
			    		$this->view->debug .= "VE SPECIFIC HLEDAME <strong>'".  implode(' ',$_GET['project_role'] )."'</strong>";
			    	}
			 
			    	// add keyword query
			    	// project roles
			    	if(isset($_GET['q'])){
			    		$this->view->debug .= "Keyword".$_GET['q'];
			    		$keyword_query = Zend_Search_Lucene_Search_QueryParser::parse($_GET['q']);	    	
			    	}
    	}
    	
    	
    	 
     	Zend_Search_Lucene_Analysis_Analyzer::setDefault(
     			new Zend_Search_Lucene_Analysis_Analyzer_Common_Utf8_CaseInsensitive ());
     	Zend_Search_Lucene_Search_QueryParser::setDefaultEncoding('utf-8');
     	
    	
     	$index = Zend_Search_Lucene::open(APPLICATION_PATH . '/indexes/members');
     	$hits = $index->find($keyword_query);
    	
    
     	$paginator = Zend_Paginator::factory($hits);
     	$paginator->setCurrentPageNumber(1);
     	$paginator->setItemCountPerPage(10);
     	$this->view->hits = $paginator;
    	
     	// display hits
    	foreach ($this->view->hits as $h){
    		echo $h->user_id;
    		echo $h->user_name;
    		echo $h->specific_roles;
    		
    	
    	}
     	
     	
     	
    	
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
	 * Create indexes for members
	 */
	public function buildMemberIndexAction(){
			
		$this->ajaxify();
		
		$facadeUser = new \App\Facade\UserFacade($this->_em);
		$paginator = $facadeUser->findAllUsersPaginator($this->_member_id,$this->_request->getParams());
		 
		\Zend_Search_Lucene_Analysis_Analyzer::setDefault(new Zend_Search_Lucene_Analysis_Analyzer_Common_Utf8Num_CaseInsensitive ());
		$index = \Zend_Search_Lucene::create(APPLICATION_PATH . '/indexes/members');
		
			
		if ( count($paginator) == 0) {echo "No data for index";} 
		
		foreach ($paginator as $user){ 
	
	 $specificRoles = "";
	 // sort array alfabetically
	 if( count($user->getSpecificRolesArray()) > 0 ){
	 	$specificRoles = $user->getSpecificRolesArray();
	 	sort($specificRoles);
	 	$specificRoles = implode(' ',$specificRoles);
	 };
		echo $specificRoles ." for ".$user->name ." <br>" ;
		
		$doc = new Zend_Search_Lucene_Document();
		$doc->addField(Zend_Search_Lucene_Field::unIndexed('user_id', $user->id));
		$doc->addField(Zend_Search_Lucene_Field::text('user_name', $user->name,'utf-8'));
		$doc->addField(Zend_Search_Lucene_Field::text('specific_roles', $specificRoles,'utf-8'));
		
		$index->addDocument($doc);
			
		}
		
		// optimize the index
		$index->optimize();
		//pass the view data for reporting
		echo $index->numDocs();
		
		
		
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