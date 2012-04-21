<?php
class Form_Results extends Zend_Form
{
	
	public function __construct($options = null)
	{
		parent::__construct($options);
		$decorator = array('ViewHelper', 'Errors');

		$this->setName('results');
		$this->setAttrib('accept-charset', 'utf-8');

		$id = new Zend_Form_Element_Hidden('idResult');
		$id->setDecorators($decorator);
		
// Recojo la carrera segun el id que le enviamos al "formulario"
		$idRace = $options['idRace'];
		$races = new Application_Model_DbTable_Races();
		$race = $races->getRace($idRace);

// Recojos los corredores dependiendo de la categoria
		$idCategory = $race['intRaceCategory'];
		$riders = new Application_Model_DbTable_Riders();
		$rider = $riders->getAllRiders($idCategory);
		
		
		
		
		
		echo var_dump($rider);
		exit;
		
		
		
		
		
		
	
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setAttrib('id', 'submitbutton')->setDecorators($decorator);
		
		$this->addElements(array($id, $submit));
	}
}

