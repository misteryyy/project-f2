<?php

class Member_ProjectController extends  Boilerplate_Controller_Action_Abstract
{
	
	private $_message_time_out = 'Before you can move to the next step, you need to fill in some basic information.';
	
	public function init(){	
		parent::init();
		//debug($_SESSION);
	}
	
    public function indexAction()
    {
    	//$member = Zend_Auth::getInstance()->getIdentity();
    	//$this->view->pageTitle = $member->name . '\'s Dashboard \  ' ;
    }
    
    
    /**
     * Project Create / Final Publishing
     */
    public function createProjectStepFiveAction()
    {
    	// check if session from one
    	if(!Zend_Session::namespaceIsset('projectStep4')){
    		$this->_helper->FlashMessenger( array('success' => $this->_message_time_out));
    		$this->_redirect("member/project/create-project-step-four");
    	}
 
    	$this->view->pageTitle = "Publish Project" ;
    	$this->view->step = 5; // for generation menu
    	 
    	// setting data from sessions
    	if(Zend_Session::namespaceIsset('projectStep1')){
    		$session1 = Zend_Session::namespaceGet('projectStep1');
    		$this->view->firstStep = $session1['firstFormData'];
    	}
    	if(Zend_Session::namespaceIsset('projectStep2')){
    		$session2 = Zend_Session::namespaceGet('projectStep2');
    		$this->view->secondStep = $session2['secondFormData'];
    	}
    	if(Zend_Session::namespaceIsset('projectStep3')){
    		$session3 = Zend_Session::namespaceGet('projectStep3');
    		$this->view->thirdStep = $session3['thirdFormData'];
    	}
    	if(Zend_Session::namespaceIsset('projectStep4')){
    		$session4 = Zend_Session::namespaceGet('projectStep4');
    		$this->view->fourthStep = $session4['fourthFormData'];
    	}

    	//display form
    	$form = new \App\Form\MemberCreateProjectStep5();
    	// PROJECT APPROVED
    	if ($this->_request->isPost()) {
    		if ($form->isValid($this->_request->getPost())) {
    			 
    			try{// Create Project Basic
    			$facadeProject = new \App\Facade\ProjectFacade($this->_em);
    			$facadeProject->createProject($this->_member_id,
    					$this->view->firstStep,
    					$this->view->secondStep,
    					$this->view->thirdStep,
    					$this->view->fourthStep);
    			$this->_helper->FlashMessenger( array('success' => "<strong>Congratulations!</strong> Your project has been published. Take a minute to familiarize yourself with this project-display layout, as there are some important functions and features that haven’t been covered in the project-setup process. You can now manage your project on the dashboard, where you can see a detailed explanation of these new available functions."));
    			// unset sessions
     			Zend_Session::namespaceUnset('projectStep1');
     			Zend_Session::namespaceUnset('projectStep2');
     			Zend_Session::namespaceUnset('projectStep3');
     			Zend_Session::namespaceUnset('projectStep4');
     			Zend_Session::namespaceUnset('projectStep5');   
				
				// add notification
    			$this->_redirect("member/my-project");
    			
    			}catch(\Exception $e ){	
    				$this->_helper->FlashMessenger( array('error' => $e->getMessage()));
    			}
    		
    		}
    		// not validated properly
    		else {
    			$this->_helper->FlashMessenger( array('error' => "You have to accept project publishing."));
    		}
    	}
    
    	$this->view->form = $form;
    }
    
    
    
    
    /**
     * 
     */
    public function createProjectStepOneAction()
    {
    	$this->view->pageTitle = "Create Project " ;
    	$this->view->step = 1; // for generation menu
    	
    	// get categories for form
    	$facadeProject = new \App\Facade\ProjectFacade($this->_em);
    	$categories = $facadeProject->findAllProjectCategoriesArray();
    	
    	$form = new \App\Form\MemberCreateProjectStep1($categories);
  
    	if ($this->_request->isPost()) {
  
    		$values = $form->getValues();
    	
    		
    		if ($form->isValid($this->_request->getPost())) {
    			// store data to session, user can cancel the project in the end
    			$session_step1 = new Zend_Session_Namespace('projectStep1');
    			$session_step1->firstFormData = $form->getValues();	
    			// fetch values
    			$this->_redirect('/member/project/create-project-step-two');	
    		}
    		// not validated properly
    		else {
    			$this->_helper->FlashMessenger( array('error' => "Please check your input."));
    		}
    	}

    	if(Zend_Session::namespaceIsset('projectStep1')){	     	
    		$session = Zend_Session::namespaceGet('projectStep1');
    		if(isset($session['firstFormData'])){
    			$form->setDefaults($session['firstFormData']);
    		}
    	};	
    	//display form
    	$this->view->form = $form;
    }
    
    
    /**
     * Create Project Picture
     */
    public function createProjectStepTwoAction()
    {
    	// check if session from one
    	if(!Zend_Session::namespaceIsset('projectStep1')){
    		$this->_helper->FlashMessenger( array('success' => $this->_message_time_out));
    		
    		$this->_redirect("member/project/create-project-step-one");
    	}
    	
    	$this->view->pageTitle = "Choose Picture representing project " ;
    	$this->view->step = 2; // for generation menu
    }
 
    /**
     * Use for creating the thumbnail for project
     */
    public function ajaxCreateProjectStepTwoAction()
    {
    	$this->ajaxify();
    	
    	if($this->_request->isPost() || $this->_request->isGet()){
    		switch ($this->_request->getParam("_method")){
    	
    			//  create new question
    			case 'create':

    				//delete previous file
    				if(Zend_Session::namespaceIsset('projectStep2')){
    					$session = Zend_Session::namespaceGet('projectStep2');
    					if(is_file($session['secondFormData']['absolutPath'])){
    						unlink($session['secondFormData']['absolutPath']);
    					}
    					Zend_Session::namespaceUnset('projectStep2');
    					// restart the namespace
    				}
	
    				try{
    					$fileManager = new Boilerplate_Util_FileManagerS3();
    					// absolutePath, webUrl, fileName
    					$file = $fileManager->createTemporaryThumbnailFromPost($this->_member_id);		
    					$resp = array("absolutPath" => $file['path'],
    									"webUrl" => $file['web_url'],
    									"fileName" => $file['file']);
    							
    					if(Zend_Session::namespaceIsset('projectStep2')){
    						$session = Zend_Session::namespaceGet('projectStep2');
    						$session['secondFormData'] = $resp;	
    					}else {// create session with link to picture
    						$session_step2 = new Zend_Session_Namespace('projectStep2');
    						$session_step2->secondFormData = $resp;	
    					};
    					
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
    				//  create new role
    			case 'update' :
    				try{

    					$respond = array("respond" => "success",'message' => "Role was updated.");
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
     * Roles Setting
     */
    public function createProjectStepThreeAction()
    {
    	//check if session from two
    	//if(!Zend_Session::namespaceIsset('projectStep2')){
    	//	$this->_helper->FlashMessenger( array('success' => "I can't wait so long. You have to start again. Sorry :D"));
    	//	$this->_redirect("member/project/create-project-step-two");
    	//} 
    	
    	// check if session from one
    	if(!Zend_Session::namespaceIsset('projectStep1')){
    		$this->_helper->FlashMessenger( array('success' => $this->_message_time_out));
    	
    		$this->_redirect("member/project/create-project-step-one");
    	}	  

    	$this->view->pageTitle = "Roles Setting" ;
    	$this->view->step = 3; // for generation menu
    	 
    	// VALIDATION IS DONE BY JQUERY ONLY
    	$form = new \App\Form\MemberCreateProjectStep3();
    	//then process your file, it's path is found by calling $upload->getFilename()
    	$this->view->form = $form;
    	
    	
    	if ($this->_request->isPost()) {
    	
    		$values = $form->getValues();
    		
    	
    		if ($form->isValid($this->_request->getPost())) {
    			// store data to session, user can cancel the project in the end
    			$session_step3 = new Zend_Session_Namespace('projectStep3');
    			$session_step3->thirdFormData = $form->getValues();
    			// fetch values
    			debug('Current Session Data');
    			debug($session_step1->thirdFormData);
    			//$this->_helper->FlashMessenger( array('success' => "Lets to the survey"));
    			$this->_redirect('/member/project/create-project-step-four');
	 
    		}
    		// not validated properly
    		else {
    			$this->_helper->FlashMessenger( array('info' => var_export($values)));
    			$this->_helper->FlashMessenger( array('error' => "Please check your input."));
    	
    		}
    	}
    	if(Zend_Session::namespaceIsset('projectStep3')){
    		$session = Zend_Session::namespaceGet('projectStep3');
    		if(isset($session['thirdFormData'])){
    			$form->setDefaults($session['thirdFormData']);
    		}
    	};
    	 
    	//display form
    	$this->view->form = $form;
  
    }

    /**
     * Project Survey
     */
    public function createProjectStepFourAction()
    {
    	// check if session from one
    	if(!Zend_Session::namespaceIsset('projectStep3')){
    		$this->_helper->FlashMessenger( array('success' => $this->_message_time_out));
    		$this->_redirect("member/project/create-project-step-three");
    	}
    	
    	$this->view->pageTitle = "Survey" ;
    	$this->view->step = 4; // for generation menu
    	
    	$facadeProjectSurvey = new \App\Facade\Project\SurveyFacade($this->_em);
    	// filing form with questions
    	$questions = $facadeProjectSurvey->findAllProjectSurveyQuestionsArray();
    	$form = new \App\Form\MemberCreateProjectStep4($questions);
    		
    	if ($this->_request->isPost()) {
    		 
    		$values = $form->getValues();
    	
    		 
    		if ($form->isValid($this->_request->getPost())) {
    			// store data to session, user can cancel the project in the end
    			$session_step4 = new Zend_Session_Namespace('projectStep4');
    			$session_step4->fourthFormData = $form->getValues();
    			// fetch values
    			$this->_redirect('/member/project/create-project-step-five');
    		}
    		// not validated properly
    		else {
    			$this->_helper->FlashMessenger( array('info' => var_export($values)));
    			$this->_helper->FlashMessenger( array('error' => "Please check your input."));
    			 
    		}
    	}
    	
    	if(Zend_Session::namespaceIsset('projectStep4')){
    		$session = Zend_Session::namespaceGet('projectStep4');
    		if(isset($session['fourthFormData'])){
    			$form->setDefaults($session['fourthFormData']);
    		}
    	};
    	
    	//display form
    	$this->view->form = $form;
 	
    }
    
   
    
    
    
    
}





