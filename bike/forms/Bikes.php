<?php
class Form_Bikes extends Zend_Form
{
	
	public function __construct($options = null)
	{
		parent::__construct($options);
		$decorator = array('ViewHelper', 'Errors');

		$this->setName('bikes');
		$this->setAttrib('accept-charset', 'utf-8');

		$id = new Zend_Form_Element_Hidden('idBike');
		$id->setDecorators($decorator);
		
		$bike = new Zend_Form_Element_Text('strBikeName');
		$bike->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setDecorators($decorator)
		->setLabel('BIKES_FORM_NAME')
		->setAttrib('maxlength', 45);

		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setAttrib('id', 'submitbutton')->setDecorators($decorator);
	
		$categories = new Application_Model_DbTable_Categories();
		
		$select = new Zend_Form_Element_Select('intBikeCategory');
		$select->addMultiOptions($categories->getAllCategories())
						->addValidator('NotEmpty')
						->setLabel('CATEGORIES_FORM_NAME');
		
		$this->addElements(array($id, $bike, $select, $submit));
	}
}

