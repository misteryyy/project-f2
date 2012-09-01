<?php

class Admin_MemberController extends Boilerplate_Controller_Action_Abstract {

	
	/*
	 * Sign up process, validation of form
	 */
	public function indexAction() {
		
		$this->view->pageTitle = "Member's Administration";
		$facadeUser = new \App\Facade\UserFacade($this->_em);
		$this->view->users = $facadeUser->findAllUsers(); // default 3 resolution	

	}
	
	/**
	 * Ban Member
	 */
	public function ajaxBanUserAction(){
		$this->ajaxify();
		$id = $this->_getParam("id");
		if(is_numeric($id)){
			$user = $this->_em->getRepository ('\App\Entity\User')->findOneById ( $id );
			if($user){
	
				$user->ban = ! $user->ban;
				$this->_em->flush();
				$this->_response->setHttpResponseCode(200);
				$this->_response->setBody(json_encode(array("ban" => $user->ban)));
			} else{
				$this->_response->setHttpResponseCode(503);
			}
		}else {
			$this->_response->setHttpResponseCode(503);
			 
		}
	}
	
	

	
	
	public function logAction(){
		
		$this->view->pageTitle = "Detail" ;
		 
		$id = $this->_request->getParam("id");
		if(is_numeric($id)){
			// get categories for form
			try{
				$facadeUser = new \App\Facade\UserFacade($this->_em);
				$this->view->logs = $facadeUser->findLogForUser($id);
				$this->view->user = $facadeUser->findOneUser($id);
			}catch(\Exception $e){
				$this->_helper->FlashMessenger( array('error' =>  $e->getMessage()));	
			}
			
		} else {
			$this->_helper->FlashMessenger( array('error' =>  "This user is not found, are you trying to hack us? :D "));
			
		}
		
		
	}
	



}





