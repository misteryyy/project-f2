<?php

namespace App\Form\Site;

class LostPasswordForm extends \Twitter_Bootstrap_Form_Horizontal
{
   public function init()
    {
  

        $this->_addClassNames('fl-form fl-form-lost');

    	//Member email
    	$this->addElement('text', 'email', array(
    			'label' => 'Email:',
    			'required' => true,
    			'class'         => 'fl-width97',
    			'description' => "Entre email where the recovery password will be send.",
    			'validators' => array("EmailAddress")
    	));
    	
        	/**
        	 * ORDERING IN FIELDSET
        	 */
         	$this->addDisplayGroup(
        			array("email"), 
         			'lost',
         			array('legend' => 'Lost password')
        	);
       
         	// submit button
         	$this->addElement('submit','submit',array(
         			'buttonType' => \Twitter_Bootstrap_Form_Element_Submit::BUTTON_PRIMARY,
         			'label' => "Send me new password",
                    'class' => 'btn btn-inverse',
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

