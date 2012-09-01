<?php

class Site_IndexController extends Boilerplate_Controller_Action_Abstract
{
	
	private $facadeProject;
	
	public function init(){
		parent::init();
		$this->facadeProject = new \App\Facade\Site\ProjectFacade($this->_em);
		//$this->checkProject();
	}
	
    public function indexAction()
    {    
    	$this->view->pageTitle = 'FLO~ Grow.Lead...';
    		// Feeding projects
    		$facadeProject = new \App\Facade\Site\ProjectFacade($this->_em);
    		$paginator = $facadeProject->findAllProjectsPaginator();
    		$paginator->setItemCountPerPage(9); // items per page
    		$page = $this->_request->getParam('page', 1);
    		$paginator->setCurrentPageNumber($page);
    		$this->view->paginator = $paginator;
    }
      
    
    public function aboutAction()
    {
    	$this->view->pageTitle = 'FLO~ Grow.Lead... - About us';
    }
    
    public function helpAction()
    {
    	$this->view->pageTitle = 'FLO~ Grow.Lead... - Help';
    }
    
    /**
     * Request for project
     */
    public function ajaxIndexAction()
    {
    	$this->ajaxify();
    
    
    	if($this->_request->isPost() || $this->_request->isGet()){
    		switch ($this->_request->getParam("_method")){
    
    			//  create new question
    			case 'like-member':
    				try{
    					$facadeUser = new \App\Facade\UserFacade($this->_em);
    					// return count of current members
    					$data = $facadeUser->likeMember($this->_member_id, $this->_request->getParam('friend_id'));
    					
    					$respond = array("respond" => "success",
    							"message" => "Action successfull.",
    							"data" => $data);
    					
    					$this->_response->setBody(json_encode($respond));
    					break;
    				}catch(Exception $e){
    					$respond = array("respond" => "error","message" => $e->getMessage());
    					$this->_response->setBody(json_encode($respond));
    				}
    				break;	

    			//  create new question
    			case 'like-project':
    					try{
    						$facadeProject = new \App\Facade\ProjectFacade($this->_em);
    						// return count of current followers
    						$data = $facadeProject->likeProject($this->_member_id, $this->_request->getParam('project_id'));
    							
    						$respond = array("respond" => "success",
    								"message" => "Action successfull.",
    								"data" => $data);
    							
    						$this->_response->setBody(json_encode($respond));
    						break;
    					}catch(Exception $e){
    						$respond = array("respond" => "error","message" => $e->getMessage());
    						$this->_response->setBody(json_encode($respond));
    					}
    					break;
    		}
    	} else {
    		$this->_response->setHttpResponseCode(503); // echo error
    
    	}
    		
    }
    

    /**
     * RSS feed
     */
    public function rssAction(){
    		$this->ajaxify();
    		
    		// read params from url
    		$params = $this->_request->getParams();
    		$facadeProject = new \App\Facade\Site\ProjectFacade($this->_em);
    		// searching in categories
    		if(isset($params["category"])){
    			// Feeding projects
    			try{
    				$paginator = $facadeProject->findAllProjectsByCategory($params['category']);
					$category = $facadeProject->findCategoryById($params['category']);
    				//additional params
    			} catch (\Exception $e){
    				$this->_helper->FlashMessenger(array('error' => 'This categoory is not found. Are you trying to hack us?'));
    				$this->_redirect('/site/error/');
    			}
    			 
    		} else {
    			$this->_helper->FlashMessenger(array('error' => 'RSS for this categoory is not found. Are you trying to hack us?'));
    			$this->_redirect('/site/error/');
    		}
    		
 			// generate rss   		
     		$feed = new Zend_Feed_Writer_Feed;
     		$feed->setTitle('FLO~ Projects / '.$category->name);
     		$feed->setLink('http://'.$_SERVER['HTTP_HOST'].'/rss/?category='.$category->id);
     		$feed->setDateModified(time());
     		$feed->setDescription('FLO~ Projects');
 
     		foreach ($paginator as $p) {	
     			$entry = $feed->createEntry();
     			$entry->setTitle($p->title);
     		
     			$entry->setId(''.$p->id);
     			$entry->setLink('http://'.$_SERVER['HTTP_HOST'].'/project/index/index/id/' . $p->id);
     			$info = str_split($p->content,50);
     			
    			$entry->addAuthor($p->user->name);
     			$entry->setDescription($info[0].'...');
     			$feed->addEntry($entry);
     		}
    	
     		$feed->setFeedLink('http://'.$_SERVER['HTTP_HOST'].'/rss?category', 'rss');
     		echo $feed->export('rss');
    }
    
    public function phpInfoAction(){
    			
    }
     
     
    /**
     * Action controller for loading libs to the layout
     * @return empty
     */
    public function libAction()
    {    
    }


    public function headerAction()
    {    
    }
    
    public function leftMenuAction()
    {
    }
    
    public function breadcrumbsAction()
    {
    }
    
    /**
     * Learn More Page
     */
    public function learnMoreAction(){
    	
    	
    }
    

    
    public function sitemapAction()
    {
    	$this->view->layout()->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(true);
    	echo $this->view->navigation()->sitemap()->setFormatOutput(true);
    
    }
    
    public function footerAction()
    { 
        $cache = Zend_Registry::get('cache');

        if ($cache->contains('timestamp')) {
            $timestamp = $cache->fetch('timestamp');
            $this->view->cachedTimestamp = true;
        } else {
            $timestamp = time();
            $cache->save('timestamp', $timestamp);
        }

        // categories for generating rss
        $this->view->categories = $this->facadeProject->findAllProjectCategories();
        $this->view->timestamp = $timestamp;
    }

    public function footer2Action()
    { 
        
    }
}