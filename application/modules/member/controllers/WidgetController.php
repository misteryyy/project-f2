<?php
/**
 * Widget for public project page
 * @author misteryyy
 *
 */
class Member_WidgetController extends  Boilerplate_Controller_Action_Abstract
{

	private $facadeProject;
	
	public function init(){
		parent::init();

	}

	
    /**
	 * Menu with basic structure
	 */ 
    public function memberRightMenuAction(){
    }
    
    
    
    /**
     * Notification widget
     */
    public function notificationAction(){
    	
    	try{
    		$facadeUser = new \App\Facade\NotificationFacade($this->_em);
    		$paginator = $facadeUser->findPublicLogForUserPaginator($this->_member_id);
    		$paginator->setItemCountPerPage(10);
    		$page = $this->_request->getParam('page', 1);
    		$paginator->setCurrentPageNumber($page);
    		$this->view->paginator = $paginator;
    		$this->view->paginator = $paginator;
    	
    	}catch(\Exception $e){
    		$this->_helper->FlashMessenger( array('error' =>  $e->getMessage()));
    	}
    		
    	
    }
    
    
    
    /**
     * Survey widget
     */
    public function surveyAction(){
    	$facadeProjectSurvey = new \App\Facade\Project\SurveyFacade($this->_em);
    	// set empty answers
    	$this->view->unfinishedSurveys = $facadeProjectSurvey->findProjectsWithEmptySurveysForUser($this->_member_id);	
    }
    
    
    /**
     * Recent projects widget
     */
    public function recentProjectAction(){
    	
    }
    
    
    /**
     * Feature projects widget
     */
    public function featureProjectAction(){
    	 
    }
    
    
}

