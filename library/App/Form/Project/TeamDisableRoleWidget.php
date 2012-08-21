<?php
namespace App\Form\Project;
use App\Entity\UserRole;

class TeamDisableRoleWidget extends \Twitter_Bootstrap_Form_Horizontal
{
	
	
	protected $project; // Array of categories from DB

	
	public function __construct($project)
	{
		$this->project = $project;
		parent::__construct();
	}
	
	
	public function init()
	{		
			$this->_addClassNames('fl-form fl-teamdesablewidget-form'); 
$warning_message =  <<<EOT
	<div class="alert alert-info">
		<span class="label label-info">Info</span>
		Description what is role widget for. Description what is role widget for. Description what is role widget for. 
		Description what is role widget for. Description what is role widget for. Description what is role widget for. 
	</div>
EOT;
	
		$this->addElement('hidden', 'warning', array(
				'description' => $warning_message,
				'ignore' => true,
				'decorators' => array(
						array('Description', array('escape'=>false, 'tag'=>'')),
				),
		));

		// Widget Setting
		$this->addElement('checkbox','role_widget_disable',
				array(  'value' => $this->project->disableRoleWidget,
						'label' => "Disable Role Widget",
						//'description' => 'Please read what will happen if you will disable role widget.',
						'decorators' => array(
							array('FieldSize'),
							array('ViewHelper'),
							array('Addon'),
							array('ElementErrors'),
							array('Description', array('tag' => 'p', 'class' => 'help-block')),
							array('Label', array('class' => 'fl-control-label-checkbox','escape' => false)),
							array('HtmlTag', array('tag' => 'div', 'class' => 'fl-controls-checkbox')),
							array('Wrapper')
						),	
				)
		);

		$this->addDisplayGroup(
				array('warning','role_widget_disable'),
				'role_widget',
				array('legend' => 'Role Widget Disable')
		);

	
		// submit button
		$this->addElement('submit','submit',array(
				'buttonType' => \Twitter_Bootstrap_Form_Element_Submit::BUTTON_PRIMARY,
				'label' => "Save",
				'escape' => false,
				'class' => 'btn btn-info'
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



