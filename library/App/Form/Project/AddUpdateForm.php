<?php

namespace App\Form\Project;

class AddUpdateForm extends \Twitter_Bootstrap_Form_Horizontal
{
   public function init()
    {

        $this->_addClassNames('fl-form fl-form-update');
  
    $this->addElement('text', 'title', array(
    			'label' => 'Title:',
    			'required' => true,
    			'filters'    => array('StringTrim'),
                'class' => 'fl-width99',
    			//'description' => "Title. Max 50 letters.",
    			'validators' => array( array('StringLength', false, array(0,100) ))));

 	$this->addElement('textarea', 'content', array(
    			'label' => 'Text:',
    			'required' => true,
    			'filters'    => array('StringTrim'),
    			'rows' => 5,
                'class' => 'fl-width99',
                'placeholder' => 'Text of your update...',
    			//'description' => "Describe your update in max 2000 letters.",
    			'validators' => array( array('StringLength', false, array(0,2000) ))
    	));

 	
 			/**
        	 * ORDERING IN FIELDSET
        	 */
         	$this->addDisplayGroup(
        			array('title',
        					'content',
        					), 
         			'update-create',	array('legend' => 'Create Update')
        	);
         	
         	
         	// submit button
         	$this->addElement('submit','submit',array(
         			'buttonType' => \Twitter_Bootstrap_Form_Element_Submit::BUTTON_PRIMARY,
         			'label' => "Create",
                    'class' => 'btn btn-info',
         			'escape' => false,
         	));
         	 
         	$this->addElement('button', 'reset', array(
         			'buttonType' => \Twitter_Bootstrap_Form_Element_Submit::BUTTON_PRIMARY,
         			'label' => 'Reset',
                    'class' => 'btn',
         			'type' => 'reset'
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

