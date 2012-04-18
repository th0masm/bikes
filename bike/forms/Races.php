<?php


class Form_Races extends Zend_Form{

	public function __construct($options = null){
		
		parent::__construct($options);
		
		
		/**
		 * Campos del formulario
		 * idRace
		 * strRaceName (varchar-100)
		 * intRaceCategory (int)
		 * dtRaceDate (int)
		 * intRiderCountry (int)
		 * 
		 */
		
		
		$decorator = array('ViewHelper', 'Errors');
		
		$this->setName('races');
		$this->setAttrib('accept-charset', 'utf-8');
		$this->setAttrib('enctype', Zend_Form::ENCTYPE_MULTIPART);
		
		// id
		$idRace = new Zend_Form_Element_Hidden('idRace');
		$idRace->setDecorators($decorator);
		
		
		// nombre de la carrera
		$raceName = new Zend_Form_Element_Text('strRaceName');
		$raceName->setRequired(true)
							->addFilters(array('StripTags', 'StringTrim'))
							->addValidator('NotEmpty')
							->setDecorators($decorator)
							->setLabel('RACES_FORM_NAME')
							->setAttrib('maxlength', 100);
		
		// categoria de la carrera
		$categories = new Application_Model_DbTable_Categories();
		$raceCategory = new Zend_Form_Element_Select('intRaceCategory');
		$raceCategory->addMultiOptions($categories->getAllCategories())
									->addValidator('NotEmpty')
									->setLabel('RACES_FORM_CATEGORY');
		
		// fecha de la carrera
		$raceDate = new Zend_Form_Element_Text('dtRaceDate');
		$raceDate->setRequired(true)
								->addFilters(array('StripTags', 'StringTrim'))
								->addValidator('Date')
								->setDecorators($decorator)
								->setLabel('RACES_FORM_DATE')
								->setAttrib('maxlength', 10);
		
		// pais de la carrera
		$countries = new Application_Model_DbTable_Countries();
		$raceCountry = new Zend_Form_Element_Select('intRaceCountry');
		$raceCountry->addMultiOptions($countries->getAllCountriesForForm())
									->addValidator('NotEmpty')
									->setLabel('RACES_FORM_COUNTRY');
		
		// boton de envio del formulario
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setAttrib('id', 'submitbutton')->setDecorators($decorator);
		
		$this->addElements(array($idRace, $raceName, $raceCategory, $raceDate, $raceCountry, $submit));
	}
	
}

