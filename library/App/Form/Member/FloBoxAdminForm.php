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

    $this->_addClassNames('fl-form fl-form-flobox');
    	
    $this->addElement('text', 'title', array(
                'label' => 'Title:',
                'required' => true,
                'class' => 'fl-width99',
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
            'rows' => 3,
            'class' => 'fl-width99',
    		'filters'    => array('StringTrim'),
    		'validators' => array(array('StringLength', false, array(1,1000)) )
    ));
     
    
    $this->addElement('textarea', 'message', array(
    			'label' => 'Text:',
    			'required' => true,
    			'filters' => array('StringTrim'),
    			'rows' => 7,
                'class' => 'fl-width99',
    			//'placeholder' => "Describe your idea...",
    			'validators' => array( array('StringLength', false, array(0,2000) ))
    	));

/*
         $this->addElement('hidden', 'divider', array(
                'description' => '<div class="fl_thin_divider"></div>',
                'ignore' => true,
                'decorators' => array(
                        array('Description', array('escape'=>false, 'tag'=>'')),
                ),
         ));
*/       


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
         			'label' => "Create New FLO~ Box message",
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

