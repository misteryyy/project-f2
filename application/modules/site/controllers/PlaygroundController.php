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
    	$this->ajaxify();
    	echo "lalala";
    	$mandrill = new \Mandrill_Mandrill('6268c2e0-f2ac-4bdc-976b-6ca3bb68afb0');	    	
    	
    	$respond = $mandrill->call("templates/list", null);
    
    	echo '/'.$respond[0]['name'].'/'; 
    	
    }
    
    public function sendTemplateAction(){
    	$this->ajaxify();
    	$template = 'welcome_email';
    	
    	$sender = 'info@floplatform.com';
    	$subject = "Welcome to FLO~ Platform";
    	$recipients = array( array('name' => "name of rec",'email' => 'j.kortan@gmail.com') );
    	$global_merge_vars = array( array('name' => "SUBJECT",'content' => "VALUE FOR TITLE") );
    	 
    	$template_content = array( array("name"=> "SUBJECT", 'content' => "content of name"));
    	//$template_content = array();
    	// data for API
    	$struct = array('template_name' => $template,
    					'template_content' => $template_content, 
    					"message" => array(
    						'subject' => $subject,
    						'from_email' => $sender,
    						'from_name' => "Name sender",
    						'to' => $recipients,
    						'global_merge_vars' => $global_merge_vars,
    					)
    			);
    	 
    	$mandrill = new \Mandrill_Mandrill('6268c2e0-f2ac-4bdc-976b-6ca3bb68afb0');
    
    	$respond = $mandrill->call("messages/send-template", $struct);
    	print_r($respond);
    	 
    }
    
    
    public function sendEmailAction(){
    	$this->ajaxify();
    	$sender = 'info@floplatform.com';
    	$subject = "welcome to FLO~ Platform";
    	$recipients = array( array('name' => "name of rec",'email' => 'j.kortan@gmail.com') );
    	$global_merge_vars = array( array('name' => "TITLE",'content' => "VALUE FOR TITLE") );
    	
    	// data for API
    	$struct = array( "message" => array( 
    								'html' => "This is html field",
    								'subject' => $subject,
    								'from_email' => $sender,
    								'from_name' => "Name sender",
    								'to' => $recipients,
    								'global_merge_vars' => $global_merge_vars,    			
    			));
    	
    	$mandrill = new \Mandrill_Mandrill('6268c2e0-f2ac-4bdc-976b-6ca3bb68afb0');
    	 
    	$respond = $mandrill->call("messages/send", $struct);
    	print_r($respond);
    	
    }
    

}