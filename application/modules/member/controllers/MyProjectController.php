<?php
class Member_MyProjectController extends  Boilerplate_Controller_Action_Abstract
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
		$this->view->module = "member";
	}
	
	
	/**
	 * Modul for Levels
	 */
	public function levelAction()
	{
		$this->checkProjectAndUser();
		$this->view->pageTitle = "Levels and Tasks" ;
		$this->view->project = $this->project;
	
		// Form for changing levels
		$form = new \App\Form\Project\EditProjectLevelForm($this->project);
		$facadeTask = new \App\Facade\Project\TaskFacade($this->_em);
			
		// validation
		if ($this->_request->isPost()) {
			if ($form->isValid($this->_request->getPost())) {
				try{
					$facadeTask->setProjectLevel($this->_member_id, $this->project_id,$form->getValues());
					$this->_helper->FlashMessenger( array('success' =>  "Project has been successfully moved to level ". $values['level']));
					$params = array('id' => $this->project_id);
					$this->_helper->redirector('level', $this->getRequest()->getControllerName(), $this->getRequest()->getModuleName(), $params);
						
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
	}

	
	/**
	 * Modul for Levels
	 */
	public function taskAction()
	{
		$this->checkProjectAndUser();	
		$this->view->pageTitle = "Tasks";
		// Form for changing levels
		$form = new \App\Form\Project\EditProjectLevelForm($this->project);
		$facadeTask = new \App\Facade\Project\TaskFacade($this->_em);		
		// validation
		if ($this->_request->isPost()) {
			if ($form->isValid($this->_request->getPost())) {
				try{
					$facadeTask->setProjectLevel($this->_member_id, $this->project_id,$form->getValues());
					$this->_helper->FlashMessenger( array('success' =>  "Project has been successfully moved to level ". $form->getAttrib("level")));
					$params = array('id' => $this->project_id);
					$this->_helper->redirector('task', $this->getRequest()->getControllerName(), $this->getRequest()->getModuleName(), $params);
		
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
		$this->view->project = $this->project;
	}
	
	/**
	 * Ajax Handling for Question
	 */
	public function ajaxTaskAction(){
		$this->ajaxify();
		$this->checkProjectAndUser();

		$facadeTeam = new \App\Facade\Project\TeamFacade($this->_em);
		$facadeTask = new \App\Facade\Project\TaskFacade($this->_em);
	
		if($this->_request->isPost() || $this->_request->isGet()){
			switch ($this->_request->getParam("_method")){
				case 'findAllForCurrentLevel' :
					$tasks = $facadeTask->findTasksForProject($this->project_id, $this->project->level);
					$data = array(); // data for sending to the script
					foreach($tasks as $t){
						$data[] = $t->toArray();
					}
					$respond = array("respond" => "success",
							"message" => "Data loaded successfully.",
							"data" => $data);
					$this->_response->setBody(json_encode($respond));
					break;
					//  create new question
				case 'create' :
					try{
						$facadeTask->createProjectTask($this->_member_id,$this->project_id, $this->_request->getParams());
						$respond = array("respond" => "success",'message' => "New task was added.");
						$this->_response->setBody(json_encode($respond));
					}catch(Exception $e){
						$respond = array("respond" => "error","message" => $e->getMessage());
						$this->_response->setBody(json_encode($respond));
					}
	
					break;
				case 'update' :
					try{
						$facadeTask->updateProjectTask($this->_member_id, $this->project_id, $this->_request->getParam('task_id'),$this->_request->getParams());
						$respond = array("respond" => "success",'message' => "Task was updated.");
						$this->_response->setBody(json_encode($respond));

					}catch(Exception $e){
						$respond = array("respond" => "error","message" => $e->getMessage());
						$this->_response->setBody(json_encode($respond));
					}
	
					break;
				case 'delete' :
					try{
						$facadeTask->deleteProjectTask($this->_member_id, $this->project_id, $this->_request->getParam('task_id'));
						$respond = array("respond" => "success",'message' => "Task was deleleted.");
						$this->_response->setBody(json_encode($respond));
					}catch(Exception $e){
						$respond = array("respond" => "error","message" => $e->getMessage());
						$this->_response->setBody(json_encode($respond));
					}
					break;
					
				case 'finish' :
						try{
							$data = $this->_request->getParams();
							$facadeTask->finishProjectTask($this->_member_id, $this->project_id, $this->_request->getParam('task_id'),$data);
							if(isset($data['finished']))
							$respond = array("respond" => "success","message" => "Task has been finished.");
							else
							$respond = array("respond" => "success","message" => "Task has been set to unfinished state.");
	
							$this->_response->setBody(json_encode($respond));
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
	 * Display Creators project for sign user
	 */
    public function surveyAction()
    {
    	$this->checkProjectAndUser();
    	$this->view->pageTitle = "Published Projects Survey Admin" ;
    	// get categories for form
    	$facadeProjectSurvey = new \App\Facade\Project\SurveyFacade($this->_em);
    	$answers = $facadeProjectSurvey->findProjectSurveyAnswers($this->_member_id, $this->project_id);
    	
    	$questions = $facadeProjectSurvey->findAllProjectSurveyQuestionsArray();
    		 
    	$form = new \App\Form\Member\ProjectSurveyAdminForm($questions, $answers);

    	// update project survey
    	if ($this->_request->isPost()) { 		
    		if ($form->isValid($this->_request->getPost())) { 			
    			// update survey
    			$facadeProjectSurvey->updateProjectSurvey($this->_member_id, $this->project_id,$form->getValues());
    			$this->_helper->FlashMessenger( array('success' => "Your answers has been updated"));	 
    		}
    		// not validated properly
    		else {
    			$this->_helper->FlashMessenger( array('error' => "Please check your input."));
    		}
    	}
    	
    	// set empty answers
    	$this->view->emptyAnswers = $facadeProjectSurvey->findEmptyAnswers($this->_member_id, $this->project_id); 	 
    	//display form
    	$this->view->form = $form;
    	$this->view->project = $this->project;
    }
    
    /**
     * Update Administration / Displaying of updates
     */
    public function updateAction()
    {
    	$this->checkProjectAndUser();
    	$this->view->pageTitle = "Published Projects - Updates" ; 
    	
    	// receiving paginator
    	$facadeProjectUpdate = new \App\Facade\Project\UpdateFacade($this->_em);
	    	$paginator = $facadeProjectUpdate->findProjectUpdates($this->_member_id, $this->project_id);
	    	$paginator->setItemCountPerPage(3);
	    	$page = $this->_request->getParam('page', 1);
	    	$paginator->setCurrentPageNumber($page);
	    	$this->view->paginator = $paginator;
	    	$this->view->project = $this->project;
    }
     
    
    /**
     * Polls for Project 
     */
    public function pollAction()
    {
    	$this->checkProjectAndUser();
    	$this->view->pageTitle = "My Project Polls" ;
    
    	$facadeProjectPoll = new \App\Facade\Project\PollFacade($this->_em);
    	// get paginator
    	$paginator = $facadeProjectPoll->findAllPollsForProjectPaginator($this->project_id);
    	$paginator->setItemCountPerPage(25);
    	$page = $this->_request->getParam('page', 1);
    	$paginator->setCurrentPageNumber($page);
    	$this->view->paginator = $paginator;
    	$this->view->project = $this->project;
    }

    /**
     * Display details about voting
     */
    public function pollDetailAction(){
    	$this->checkProjectAndUser();

    	$this->view->pageTitle = "My Project Polls" ;
    	$facadeProjectPoll = new \App\Facade\Project\PollFacade($this->_em);
    	
    	$this->view->poll = $facadeProjectPoll->findOnePollForProject($this->_request->getParam('poll_id'));
    	
    	// get paginator
    	$paginator = $facadeProjectPoll->findAllUsersWithAnswersForPollPaginator($this->project_id,$this->_request->getParam('poll_id'));
   
    	$paginator->setItemCountPerPage(25);
    	$page = $this->_request->getParam('page', 1);
    	$paginator->setCurrentPageNumber($page);
    	$this->view->paginator = $paginator;
    	$this->view->project = $this->project;
    }
       
    /**
     *
     */
    public function pollCreateAction()
    {
    	$this->checkProjectAndUser();
    	$this->view->pageTitle = "Create New Poll" ; 
    	$form = new \App\Form\Project\AddPollForm();
    	
    	
    	if ($this->_request->isPost()) {
    		if ($form->isValid($this->_request->getPost())) {
    			
    			try{
    				$facadeProjectPoll = new \App\Facade\Project\PollFacade($this->_em);
    				$facadeProjectPoll->createPoll($this->_member_id, $this->project_id, $form->getValues());
    				
    				$this->_helper->FlashMessenger( array('success' => "New Poll was created."));
    				$params = array('id' => $this->project_id);
    				$this->_helper->redirector('poll', $this->getRequest()->getControllerName(), $this->getRequest()->getModuleName(), $params);
    				   
    				
				} catch (\Exception $e){
					$this->_helper->FlashMessenger( array('error' => $e->getMessage()));
					
				}
    				 
    		}
    		// not validated properly
    		else {
    			$this->_helper->FlashMessenger( array('error' => "Please check your input."));
    		}
    	}
    	
    	$this->view->form = $form;
    	$this->view->project = $this->project;
    
    }
    
    /**
     * 
     */
    public function updateCreateAction()
    {
    	$this->checkProjectAndUser();
    	$this->view->pageTitle = "Create Update" ;
    		 
    	$form = new \App\Form\Project\AddUpdateForm();
    	
    	// update project survey
    	if ($this->_request->isPost()) {
    		if ($form->isValid($this->_request->getPost())) {
    			// update survey
    			$facadeProjectUpdate = new \App\Facade\Project\UpdateFacade($this->_em);
    			$facadeProjectUpdate->createProjectUpdate($this->_member_id, $this->project_id,$form->getValues());
    			$this->_helper->FlashMessenger( array('success' => "New Update has been created."));
    			$params = array('id' => $this->project_id);
    			$this->_helper->redirector('update', $this->getRequest()->getControllerName(), $this->getRequest()->getModuleName(), $params);
    			
    		}
    		// not validated properly
    		else {
    			$this->_helper->FlashMessenger( array('error' => "Please check your input."));		 
    		}
    	} 
    	//display form
    	$this->paginator = null;
    	$this->view->form = $form;
    	$this->view->project = $this->project;
    
    }
    
    
    public function checkUpdate(){
    	// checking update
    	$update_id = $this->_request->getParam("update_id");
    	// check id param for project
    	if(!is_numeric($update_id)){
    		$this->_helper->FlashMessenger(array('error' => 'This project is not found, are you trying to hack us? :D '));
    		$this->_redirect('/member/error/');
    	}
    	
    	try{
    		return $this->facadeProjectUpdate->findOneUpdate($this->_member_id, $this->project_id,$update_id);	 
    	}catch(\Exception $e){
    		$this->_helper->FlashMessenger(array('error' => $e->getMessage()));
    		$this->_redirect('/member/error/');
    	}
 
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
     * Edit update for Project
     */
    public function updateEditAction()
    {
    	$this->checkProjectAndUser();
    	$update = $this->checkUpdate(); // returns update
    	
    	$this->view->pageTitle = "Edit Update" ;	
    	$form = new \App\Form\Project\EditUpdateForm();
    	$error = false;
    	// update project survey
    	if ($this->_request->isPost()) {
    		if ($form->isValid($this->_request->getPost())) {
    			// update survey
    			$this->facadeProjectUpdate->updateProjectUpdate($this->_member_id, $this->project_id,$update->id,$form->getValues());
    			$this->_helper->FlashMessenger( array('success' => "Update has been updated."));
    			$params = array('id' => $this->project_id);
    			$this->_helper->redirector('update', $this->getRequest()->getControllerName(), $this->getRequest()->getModuleName(), $params);
    		
    		}
    		// not validated properly
    		else {
    			$this->_helper->FlashMessenger( array('error' => "Please check your input."));
     			$error = true;
    		}
    	}
    	
    	// leave the old values, if user already sended form
    	if(!$error){
    		$data = array(
    				'title' => $update->title,
    				'content' => $update->content,
    		);
    		$form->setDefaults($data);
    	}
    	//display form
    	$this->view->form = $form;
    	$this->view->project = $this->project;
    
    }

    /**
     * Edit Creator's Project
     */
    public function editProjectAction()
    {
    	$this->checkProjectAndUser();	
    	$this->view->pageTitle = "Edit project ".$this->project->getTitle();			
    	
    	$categories = $this->facadeProject->findAllProjectCategoriesArray();
    	$form = new \App\Form\Project\EditProjectForm($categories,$this->project);

    	if ($this->_request->isPost()) {
    			if ($form->isValid($this->_request->getPost())) {
    				
    			try{ // update project data
    				
    				$this->facadeProject->updateProject($this->_member_id,$this->project_id,$form->getValues());		
    				$this->_helper->FlashMessenger( array('success' =>  "Project Updated"));	
    				
    				$params = array('id' => $this->project_id);
    				$this->_helper->redirector('edit-project', $this->getRequest()->getControllerName(), $this->getRequest()->getModuleName(), $params);
    				
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
    		$this->view->project = $this->project;
    }
    
    /*
     * Check all neccessary things
     */
    public function checkProjectAndUser(){
    	$id = $this->_request->getParam("id");
    	// check id param for project
    	if(!is_numeric($id)){
   			$this->_helper->FlashMessenger(array('error' => 'This project is not found, are you trying to hack us? :D '));
    		$this->_redirect('/error');
    	}
    	try{
    		// init basic things
    		$this->project = $this->facadeProject->findProjectForUser($this->_member_id, $id);
    		$this->project_id = $id;
    		
    	} catch (\Exception $e){
    		$this->_helper->FlashMessenger(array('error' => 'This project is not found, are you trying to hack us? :D '));
    		$this->_helper->FlashMessenger(array('error' => $e->getMessage()));
    		$this->_redirect('/error/');	
    	}	
    }
    

    /**
     * Comments for creator Section
     * Module for answering comments with higher priority
     */
    public function commentCreatorAction(){
    
    	$this->checkProjectAndUser();
    	 				
    	$this->view->pageTitle = $this->project->getTitle() . " ~ Comments for Creator" ;
    	$this->view->project = $this->project;
    	$form = new \App\Form\Project\AddCommentFromCreatorForm($this->_member,$this->project_id);	
    	$this->view->form = $form;
    	 
    	// validation and form handler
    		if ($this->_request->isPost()) {
    			if ($form->isValid($this->_request->getPost())) {
    				try{
    					$this->facadeComment->answerComment($this->_member_id,$this->project_id,$form->getValues());
    					$this->_helper->FlashMessenger( array('success' =>  "Comment has been added."));
    					$form->reset();
    				} catch (\Exception $e){
    					$this->_helper->FlashMessenger( array('error' =>  $e->getMessage()));
    				}
    			}
    			// not validated properly
    			else {
    				$this->_helper->FlashMessenger( array('error' => "Please check your input."));
    			}
    		}
    		
		// displaing comments
    	try{
    			// receiving comments for this project
    			$zendPaginator = $this->facadeComment->findUnasweredCommentsForProject($this->project_id);
    			$zendPaginator->setItemCountPerPage(1);
    			$zendPaginator->setCurrentPageNumber($this->_request->getParam('page', 1));
    			$this->view->paginator = $zendPaginator;
    				
    	}catch(\Exception $e){
    			$this->_helper->FlashMessenger( array('error' => $e->getMessage()));
    	}	
    }
    /**
     * Edit Creator's Project Picture
     */
    public function editProjectPictureAction()
    {
    	$this->checkProjectAndUser();	
    	$this->view->project = $this->project;
    }
    
    
    public function ajaxEditProjectPictureAction(){
    	$this->ajaxify();
    	$this->checkProjectAndUser();
    	 
    	
    	if($this->_request->isPost() || $this->_request->isGet()){
    		switch ($this->_request->getParam("_method")){
    			 
    			//  create new question
    			case 'update':
    				try{
    					$fileManager = new Boilerplate_Util_FileManagerS3($this->project);
    					// absolutePath, webUrl, fileName
    					$file = $fileManager->updateThumbnail($this->_member_id);	
    					
    					// Processing new image and delete old images
    					$this->facadeProject->updateProjectPicture($this->_member_id, $this->project_id,$file['file']);
	
    					$resp = array("absolutPath" => $file['path'],
    							"webUrl" => $file['web_url'],
    							"fileName" => $file['file']);
    				    					
    					// response
    					$response = array(
    							"respond" => "success",
    							'message' => "Picture uploaded successfully.",
    							"path" => $file['path'],
    							'web_url' => $file['web_url'],
    							"fileName" => $file['file']);
    					$this->_response->setBody(json_encode($response));
    	
    	
    				}catch (\Exception $e){
    					$respond = array("respond" => "error",'message' => $e->getMessage());
    					$this->_response->setBody(json_encode($respond));
    				}
    					
    				break;				
    		}
    	} else {
    		$this->_response->setHttpResponseCode(503); // echo error
    	   
    	}
    	
    	
    }
    
    
    
    
    
    
    /**
     * Display Creators project for sign user
     */
    public function detailAction()
    {
    	$this->view->pageTitle = "Published Projects - Detail" ;
    	// get categories for form
    	$facadeProject = new \App\Facade\ProjectFacade($this->_em);
    	$projects = $facadeProject->findAllProjectsForUser($this->_member_id);
    	$this->view->projects = $projects;
    }
    
    
    

    /**
     * Display Creators project for sign user
     */
    public function indexAction()
    {
    	$this->view->pageTitle = "Published Projects" ;
    	// get categories for form
    	$facadeProject = new \App\Facade\ProjectFacade($this->_em);
    	$projects = $facadeProject->findAllProjectsForUser($this->_member_id);
    	$this->view->projects = $projects;

    }
    
    
}





