<?php

namespace App\Form\Member;

class EditNofificationAndNewsletterForm extends \Twitter_Bootstrap_Form_Horizontal
{
	
	protected $user; // user entity
	public function __construct($user)
	{
		$this->user = $user;	
		parent::__construct();
	}
	
   public function init()
    {
    	
        $this->_addClassNames('fl-form');
    // Description for Newsletters
    $this->addElement('hidden', 'newsletter_info', array(
    			'description' => '<div class="alert alert-info">As one of our first group of users on FLO~, you may have previously signed up for our newsletter. If you’re not signed up for our newsletter, and would like to, select Yes. If you’re already signed up, and would like to be removed, select No. Otherwise, you will stay at your current status.</div>',
    			'ignore' => true,
    			'decorators' => array(
    					array('Description', array('escape'=>false, 'tag'=>'')),
    			),
    	));
    	
    if($this->user->emailNewsletter){
    		$optionsN = array('yes','no');
    }else {
    		$optionsN = array('no','yes');
    }
    
      // Country Select Box
    $this->addElement('select','newsletter', array(
    		'label' => 'Would you like to receive newsletters?', 
    		'multiOptions' => $optionsN
    	
    		));
  
    // Description for Newsletters
    $this->addElement('hidden', 'notification_info', array(
    		'description' => '<div class="alert alert-info">There are a number of different actions within FLO~ that result in e-mail notifications to the e-mail account you’ve registered with. If you’d like to turn this off, you can do so here. Our default setting is for you to receive these notifications.</div>',
    		'ignore' => true,
    		'decorators' => array(
    				array('Description', array('escape'=>false, 'tag'=>'')),
    		),
    ));
    
    if($this->user->emailNotification){
    	$options = array('yes','no');
    }else {
    	$options = array('no','yes');
    }
    
    // Country Select Box
    $this->addElement('select','notification', array(
    		'label' => 'Would you like to receive system notifications?',
    		'multiOptions' => $options
    		 
    ));
    
     	
		 	
 			/**
        	 * ORDERING IN FIELDSET
        	 */
         	$this->addDisplayGroup(
        			array('newsletter_info','newsletter',
        				  'notification_info','notification',
        				
        					), 
         			'Member Newsletter & Notification Settings',	array('legend' => 'Newsletter & Notification Settings')
        	);
       
         	// submit button
         	$this->addElement('submit','submit',array(
         			'buttonType' => \Twitter_Bootstrap_Form_Element_Submit::BUTTON_PRIMARY,
         			'label' => "Save",
         			'escape' => false,
                    'class' => 'btn btn-info'
         	));
         	 
         	$this->addElement('button', 'reset', array(
         			'buttonType' => \Twitter_Bootstrap_Form_Element_Submit::BUTTON_PRIMARY,
         			'label' => 'Reset',
         			'type' => 'reset',
                    'class' => 'btn'
         	));
         		
         	// Action Section
         	$this->addDisplayGroup(
         			array('reset', 'submit'),
         			'actions',
         			array(
         					'disableLoadDefaultDecorators' => true,
         					'decorators' => array('Actions')
         			)
         	);
    
        	     	   
    }
}

