<?php
class Form_Teams extends Zend_Form
{
	
	public function __construct($options = null)
	{
		parent::__construct($options);
		$decorator = array('ViewHelper', 'Errors');

		$this->setName('teams');
		$this->setAttrib('accept-charset', 'utf-8');

		$id = new Zend_Form_Element_Hidden('idTeam');
		$id->setDecorators($decorator);
		
		$team = new Zend_Form_Element_Text('strTeamName');
		$team->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setDecorators($decorator)
		->setLabel('TEAMS_FORM_NAME')
		->setAttrib('maxlength', 100);

		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setAttrib('id', 'submitbutton')->setDecorators($decorator);
	
		$categories = new Application_Model_DbTable_Categories();
		
		$select = new Zend_Form_Element_Select('intTeamCategory');
		$select->addMultiOptions($categories->getAllCategories())
						->addValidator('NotEmpty')
						->setLabel('CATEGORIES_FORM_NAME');
		
		$this->addElements(array($id, $team, $select, $submit));
	}
}

