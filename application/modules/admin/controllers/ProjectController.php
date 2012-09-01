<?php

class Admin_ProjectController extends Boilerplate_Controller_Action_Abstract {

	/*
	 * Sign up process, validation of form
	 */
	public function indexAction() {
		
		$this->view->pageTitle = 'Project Administration';
		$facadeProject = new \App\Facade\ProjectFacade($this->_em);
		$this->view->projects = $facadeProject->findAllProjects(); // default 3 resolution

	
	}
	
	/**
	 * Ban Project
	 */
	public function ajaxBanProjectAction(){
		$this->ajaxify();
		$id = $this->_getParam("id");
		if(is_numeric($id)){
			$project = $this->_em->getRepository ('\App\Entity\Project')->findOneById ( $id );
			if($project){
	
				$project->ban = ! $project->ban;
				$this->_em->flush();
				$this->_response->setHttpResponseCode(200);
				$this->_response->setBody(json_encode(array("ban" => $project->ban)));
			} else{
				$this->_response->setHttpResponseCode(503);
			}
		}else {
			$this->_response->setHttpResponseCode(503);
	
		}
	}
	
	
	public function logAction(){
	
		$this->view->pageTitle = "Published Project Logs" ;
			
		$id = $this->_request->getParam("id");
		if(is_numeric($id)){
			// get categories for form
			try{
				$facadeProject = new \App\Facade\ProjectFacade($this->_em);
				$this->view->logs = $facadeProject->findLogForProject($id);
				$this->view->project = $facadeProject->findOneProject($id);
			}catch(\Exception $e){
				$this->_helper->FlashMessenger( array('error' =>  $e->getMessage()));
			}
				
		} else {
			$this->_helper->FlashMessenger( array('error' =>  "This project is not found, are you trying to hack us? :D "));
				
		}
	
	
	}



}





