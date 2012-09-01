<?php

class Site_ErrorController extends  Boilerplate_Controller_Action_Abstract
{

	
	/**
	 * Set new error layout for all errors
	 * @see Boilerplate_Controller_Action_Abstract::init()
	 */
	public function init(){
		parent::init();
		// use just one template
		Zend_Layout::getMvcInstance()->setLayoutPath(
				APPLICATION_PATH . "/modules/site/layouts/scripts"
		);
		$this->_helper->_layout->setLayout('error');
	}
	
	/**
	 * Project not found page
	 */
	public function projectNotFoundAction(){}
	
	
	/**
	 * Member not found page
	 */
	public function memberNotFoundAction(){}
	
	
    public function errorAction()
    {
  	 
    	
    	$errors = $this->_getParam('error_handler');
         echo "<pre>";
             var_dump($errors->exception->getMessage());
             var_dump($errors->exception->getTraceAsString());
         echo "</pre>";

        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $this->view->message = 'Page not found';
                break;
            default:
                // application error
                $this->getResponse()->setHttpResponseCode(500);
                $this->view->message = 'Application error';
                break;
        }
        
         // Log exception, if logger available
         if ($log = $this->getLog()) {
             $log->crit($this->view->message, $errors->exception);
         }
        
        // conditionally display exceptions
        if ($this->getInvokeArg('displayExceptions') == true) {
            $this->view->exception = $errors->exception;
        }
     
        $this->view->request   = $errors->request;
   		
    }

    public function getLog()
    {
        $bootstrap = $this->getInvokeArg('bootstrap');
        if (!$bootstrap->hasPluginResource('Log')) {
            return false;
        }
        $log = $bootstrap->getResource('Log');
        return $log;
    }


}

