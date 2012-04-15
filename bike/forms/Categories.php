<?php
class Form_Categories extends Zend_Form
{
	
	public function __construct($options = null)
	{
		parent::__construct($options);
		$decorator = array('ViewHelper', 'Errors');

		$this->setName('categories');
		$this->setAttrib('accept-charset', 'utf-8');

		$id = new Zend_Form_Element_Hidden('idCategory');
		$id->setDecorators($decorator);
		
		$category = new Zend_Form_Element_Text('strCategoryName');
		$category->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setDecorators($decorator)
		->setLabel('CATEGORIES_FORM_NAME')
		->setAttrib('maxlength', 20);

		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setAttrib('id', 'submitbutton')->setDecorators($decorator);
	
		$this->addElements(array($id, $category, $submit));
	}
}

