<?php

namespace App\Form\Site;

class LoginForm extends \Twitter_Bootstrap_Form_Horizontal
{
   public function init()
    {
    	//Member email
    	$this->addElement('text', 'email', array(
    			'label' => 'Email',
    			'required' => true,
    			'prepend'       => '@',
    			'class'         => 'focused',
    			'validators' => array("EmailAddress")
    	));
    	
    	//Member email
    	$this->addElement('password', 'password', array(
    			'label' => 'Password',
    			'required' => true,
    			'class'         => 'focused',
    	));
    	
        	/**
        	 * ORDERING IN FIELDSET
        	 */
         	$this->addDisplayGroup(
        			array("email","password"), 
         			'login',
         			array('legend' => 'Login')
        	);
       
         	// submit button
         	$this->addElement('submit','submit',array(
         			'buttonType' => \Twitter_Bootstrap_Form_Element_Submit::BUTTON_PRIMARY,
         			'label' => "Login",
         			'escape'        => false,
         	));
         	 
      
         	
         	// Action Section
        	$this->addDisplayGroup(
        			array('submit'),
        			'actions',
        			array(
        					'disableLoadDefaultDecorators' => true,
        					'decorators' => array('Actions')
        			)
        	);
        	     	   
    }
}

