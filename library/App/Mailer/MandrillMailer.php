<?php
/**
 * Class Mandrill mailer sends email through Mandrill API
 * version 0.1
 * User: misteryyy
 */
namespace App\Mailer;

class MandrillMailer 
{
	/*
	 * Basic settings
	 */
	static $fromEmail = "FLO~ Platform";
	static $fromName = "info@floplatform.com";
	private $mandrill;
	
	/**
	 * Init for mandrilll settings
	 */
	public function __construct(){
		//init mandrill API
		$this->mandrill = new \Mandrill_Mandrill('6268c2e0-f2ac-4bdc-976b-6ca3bb68afb0');
	}
	
	
	/**
	 * General structor for sending templates from Mandrill
	 * @param string $template
	 * @param string $subject
	 * @param array $recipient
	 * @param array $global_merge_vars
	 */
	public function sendTemplateAction($template,$subject,$recipients = array(),$global_merge_vars = array()){
		$recipients = array( array('name' => "name of rec",'email' => 'j.kortan@gmail.com') );
		//$global_merge_vars = array( array('name' => "SUBJECT",'content' => "VALUE FOR TITLE") );
		//$template_content = array( array("name"=> "SUBJECT", 'content' => "content of name"));
		//$template_content = array();
		// data for API
		$struct = array('template_name' => $template,
			//	'template_content' => $template_content,
				"message" => array(
						'subject' => $subject,
						'from_email' => self::$fromEmail,
						'from_name' => self::$fromName,
						'to' => $recipients,
						'global_merge_vars' => $global_merge_vars,
				)
		);
		return $this->mandrill->call("messages/send-template", $struct); 
	}

	
	/**
	 * sends welcome email to user
	 */
	public function sendWelcomeEmail($recipient){
		$global_merge_vars = array(); // no vars in template		
		$this->sendTemplateAction('welcome_email', "Welcome to FLO~ Platform",$recipients,$global_merge_vars);
		
	}

}