<?php

namespace App\Form\Member;

class FloBoxAdminForm extends \Twitter_Bootstrap_Form_Horizontal
{
	
	public function __construct()
	{
		parent::__construct();
	}
	
	
   public function init()
    {

    $this->_addClassNames('fl-form');
    	
    $this->addElement('text', 'title', array(
                'label' => 'Title:',
                'required' => true,
                'class' => 'span8',
                'filters'    => array('StringTrim'),
                //'description' => "Title. Max 50 letters.",
                'validators' => array( array('StringLength', false, array(0,100) ))));
  
    // type of FloMessage
    $options = array( 
    		\App\Entity\UserFloBox::MESSAGE_TYPE_CHOICE => \App\Entity\UserFloBox::MESSAGE_TYPE_CHOICE,
    		\App\Entity\UserFloBox::MESSAGE_TYPE_PROBLEM => \App\Entity\UserFloBox::MESSAGE_TYPE_PROBLEM,
    		\App\Entity\UserFloBox::MESSAGE_TYPE_INTEREST => \App\Entity\UserFloBox::MESSAGE_TYPE_INTEREST,
    		);

    // Country Select Box
    $this->addElement('select','type', array(
    		'label' => 'Type:', 
    		'multiOptions' => $options
    	
    		));
    
    $this->addElement('textarea', 'typeDetail', array(
    		'label' => 'Specicify your idea:',
    		'required' => true,
            'class' => 'span8',
            'rows' => 3,
    		'filters'    => array('StringTrim'),
    		'validators' => array(array('StringLength', false, array(1,100)) )
    ));
     
    
    $this->addElement('textarea', 'message', array(
    			'label' => 'Text:',
    			'required' => true,
    			'filters' => array('StringTrim'),
    			'rows' => 5,
                'class' => 'span8',
    			'description' => "Describe your idea in max 1000 letters.",
    			'validators' => array( array('StringLength', false, array(0,1000) ))
    	));


 			/**
        	 * ORDERING IN FIELDSET
        	 */
         	$this->addDisplayGroup(
        			array('title','type','typeDetail','message'), 
         			'FLO~Box Idea',	
         			array('legend' => 'FLO~Box Admin')
        	);
       
         	// submit button
         	$this->addElement('submit','submit',array(
         			'label' => "Submit",
         			'escape' => false,
         			'class' => 'btn btn-info'
         	));
         		
         	
         		
         	// Action Section
         	$this->addDisplayGroup(
         			array( 'submit'),
         			'actions',
         			array(
         					'disableLoadDefaultDecorators' => true,
         					'decorators' => array('Actions')
         			)
         	);
    
        	     	   
    }
}

