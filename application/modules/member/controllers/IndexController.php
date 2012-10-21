<?php

class Member_IndexController extends Boilerplate_Controller_Action_Abstract {

	public function init(){	
		$this->_helper->_layout->setLayout('member-public');
		parent::init();
		
	}
	
	public function indexAction() {
		$this->_redirect('/member/dashboard');
	}
	
	/**
	 * Lost password action
	 */
	public function lostPasswordAction() {	
		$this->view->pageTitle = 'Lost password';

		$form = new \App\Form\Site\LostPasswordForm();
		$this->view->form = $form;
		
		if ($this->_request->isPost ()) {			
			if ($form->isValid ( $this->_request->getPost () )) {
				try {		
						$facadeUser = new \App\Facade\UserFacade($this->_em);
						$facadeUser->lostPassword($form->getValue ( 'email' ));
						$this->_helper->FlashMessenger ( array ('success' => "Password has been sent to your email. Please change the password after login for your safety." ) );
						$this->_helper->redirector('login', 'index','member');
				} catch ( Exception $e ) {
						$this->_helper->FlashMessenger ( array ('error' => $e->getMessage () ) );
					}
				
			
			} else {
				// $form->buildBootstrapErrorDecorators();
				$this->_helper->FlashMessenger ( array ('error' => 'Oops... something is wrong with your account email or password. Please try it again.' ) );
			}	
				
		}
	}
	
	/*
	 * Logout from Account
	 */
	public function logoutAction() {
		Zend_Auth::getInstance()->clearIdentity();
		$this->_helper->FlashMessenger ( array ('success' => "You have been logout." ) );
		$this->_redirect('/');			
		
	}
	
	/**
	 * Login action
	 */
	public function loginAction() {
		$this->view->pageTitle = 'Login';
		$form = new \App\Form\Site\LoginForm();
		$this->view->form = $form;
		
		if ($this->_request->isPost ()) {
			
			if ($form->isValid ( $this->_request->getPost () )) {
				// fetch values
				$values = $form->getValues ();
				
				$authAdapter = new \Boilerplate_Auth_Adapter_Doctrine2($form->getValue("email"), $form->getValue("password"));
				$result = Zend_Auth::getInstance()->authenticate($authAdapter);
				if ($result->isValid()) {
					//user is valid so store and redirect to admin home
					$this->_helper->FlashMessenger ( array ('success' => 'Login successful' ) );
					$this->_helper->redirector('index', 'dashboard','member');
				} else {					
					// $form->buildBootstrapErrorDecorators();
					$this->_helper->FlashMessenger ( array ('error' => 'Oops... something is wrong with your account email or password. Please try it again.' ) );	
				}		
			} 			
			// print error
			else {
				// $form->buildBootstrapErrorDecorators();
				$this->_helper->FlashMessenger ( array ('error' => 'Oops... something is wrong with your account email or password. Please try it again.' ) );
			}
		}
	
	}
	
	/*
	 * Sign up process, validation of form
	 */
	public function signUpAction() {
		$this->view->pageTitle = 'Sign up for FLO~';
		
		$form = new \App\Form\Site\SignupForm();
		$this->view->form = $form;
		if ($this->_request->isPost ()) {
			if ($form->isValid ( $this->_request->getPost () )) {
				// finding user
				$user = $this->_em->getRepository ('\App\Entity\User')->findOneByEmail ( $form->getValue ( 'email' ) );
				// user doesn't exist, we can create new one
				if (! $user) {
					try {	
						// storing the values
						$facadeUser = new \App\Facade\UserFacade($this->_em);
						$facadeUser->createAccount($form->getValues());
											
						// SUCCESS
						$this->_helper->FlashMessenger ( array ('success' => "Congratulations, account created!. An email with more information will be landing in your inbox shortly." ) );
						$this->_redirect('/member/index/login');
						
						// something bad happen with Doctrine
					} catch ( Exception $e ) {
						$this->_helper->FlashMessenger ( array ('error' => $e->getMessage () ) );
					}
				
				} 				// user already exists
				else {
					$this->_helper->FlashMessenger ( array ('error' => "The provided e-mail address is already associated with a registered user." ) );
				}
			} // print error
			else {
				$this->_helper->FlashMessenger ( array ('error' => "Please take a look at the form again." ) );
			}
		}
	
	}
	



}





