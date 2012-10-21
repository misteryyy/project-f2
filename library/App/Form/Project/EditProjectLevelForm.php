<?php
namespace App\Form\Project;
use Doctrine\DBAL\Schema\Visitor\RemoveNamespacedAssets;

use App\Entity\UserRole;
/**
 * Form for changing levels for project
 * @author misteryyy
 *
 */
class EditProjectLevelForm extends \Twitter_Bootstrap_Form_Horizontal
{
		
	protected $project; // Array of categories from DB
	protected $levelComment; // content for current answer
	
	public function __construct($project,$comment)
	{
		$this->project = $project;
		$this->levelComment = $comment;
		parent::__construct();
	
	}
	
	
	public function init()
	{		 
		// dont move with the EOT, it has to be on the first position
		$this->_addClassNames('fl-form');
	    $levelArr = array(1 => "LEVEL1",2 => 'LEVEL2', 3 => 'LEVEL3');
	    unset($levelArr[$this->project->level]);
	    
		$this->addElement('select','level', array(
				'label' => 'Choose level',
				'description' => "",
				'multiOptions' => $levelArr
		));

		
		// choose what to display
		if(isset($this->levelComment)){
			$content = $this->levelComment->content;
		}else {
			$content = "";
		}
		$this->addElement('textarea', "content", array(
				'class' => 'span7',
				'rows' => '6',
				'required' => false,
				'value' => $content,
				'filters' => array('StringTrim'),
				//'description' => ,
		));
		
		$this->addDisplayGroup(
				array('level'),
				'role_widget',
				array('legend' => 'Move on in your project')
		);

	
		// submit button
		$this->addElement('submit','submit',array(
				'buttonType' => \Twitter_Bootstrap_Form_Element_Submit::BUTTON_PRIMARY,
				'label' => "Confirm level",
				'escape' => false,
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



