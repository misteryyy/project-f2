<?php

class Site_PlaygroundController extends  Boilerplate_Controller_Action_Abstract
{


        
    public function init()
    {
    	parent::init();
        $this->_em = Zend_Registry::get('em');
    }

   
    /**
     * S3 server
     */
	public function s3Action(){
		$this->ajaxify();
		echo "Welcome to S3 playground.";
		
		$s3 = new Zend_Service_Amazon_S3('AKIAIIT4QVXXNFSXG3BA','aPe8De9tNYpl5kz8aCYUBWVg2aEcFsV/rMyMx9fT');
		$s3->getBuckets();
		
		//Connecting to bucket
		$my_buckets = $s3->getBuckets();
		echo "<br/>";
		if($s3->isBucketAvailable($my_buckets[0])) {echo "Bucket ".$my_buckets[0]." is available.";}
		
		$list = $s3->getObjectsByBucket($my_buckets[0]);
		print_r($list);
		
		
// 		$s3->createBucket("my-own-bucket");
// 		$s3->putObject("my-own-bucket/myobject", "somedata");
// 		echo $s3->getObject("my-own-bucket/myobject");
		
	}
    
    
    
    public function indexAction()  {
    	//TODO tomas rada breadcrumb
//     	$this->view->breadcrumb = array(array('label' => $label, 'link' => $this->getRequest()->getBaseUrl() . '/kontakty/'));
//     	a v layout.php to pak zobrazuju
    	
//     	<?php
//     	$separator = '>';
//     	//$baseUrl = 'http://' . $_SERVER ["SERVER_NAME"] . $this->baseUrl();
//     	echo '<div id="breadcrumb"><a href="' . $baseUrl . '">Úvodní stránka</a>';
//     	if ($this->breadcrumb) {
//     		foreach ($this->breadcrumb as $link) {
//     			echo ' ' . $separator . ' <a href="' . $link['link'] . '">' . $link['label'] . '</a>';
//     		}
//     	}
//     	echo "</div>"
//     	
    	
    	//$this->_helper->layout()->disableLayout();
    	
//     	try{
//   		throw new Exception("Password already exists");
//     	} catch (Exception $e){
//     		/*
//     		 * Exception message
//     		*/
//     		$this->_helper->FlashMessenger( array('error' => 	$e->getMessage())
//     		);		
//     	}
//     	/*
//     	 * Error message
//     	*/
//     	$this->_helper->FlashMessenger(
//     			array('error' => 'There was a problem with your form submission.')
//     	);
    	 
//     	/*
//     	 * Success message
//     	*/
//     	$this->_helper->FlashMessenger(
//     			array('success' => 'This is just suxxess, that everything is all right.')
//     	);
    	 
//     	/*
//     	 * Info message
//     	*/
//     	$this->_helper->FlashMessenger(
//     			array('info' => 'This is just notification, that everything is all right.')
//     	);
    	
    	if (Zend_Controller_Action_HelperBroker::hasHelper('layout')) {
    	//	$this->_helper->layout->disableLayout();
    	}
    	//$this->_helper->viewRenderer->setNoRender(true);
    	

    	$exampleF = new \App\Form\ExampleForm();
    	$this->view->form = $exampleF;
    	
    	if ($this->_request->isPost()) {
    		if ($exampleF->isValid($this->_request->getPost())) {
    	
    			// fetch values
    			$values = $exampleF->getValues();
    			pr($values);
    	
    			// ... do some stuff
    		}
    	
    		// print error
    		else {

    			
    		//	$this->view->messages = array('error', 'Please control your input!'); // extra message on top
    		}
    	}
    	
    	
    }

}