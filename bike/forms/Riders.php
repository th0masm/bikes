<?php


class Form_Riders extends Zend_Form{

	public function __construct($options = null){
		
		parent::__construct($options);
		
		
		/**
		 * Campos del formulario
		 * idRider
		 * strRiderName (varchar-100)
		 * intRiderCategory (int)
		 * intRiderTeam (int)
		 * intRiderBike (int)
		 * intRiderNumber (int)
		 * dtRiderBirth (int)
		 * strRiderCity (varchar-100)
		 * intRiderCountry (int)
		 * intRiderWeight (int)
		 * intRiderHeight (int)
		 * 
		 * strRiderImage (varchar-45)
		 * 
		 */
		
		
		//$decorator = array('ViewHelper', 'Errors');
		
		$decorator = array('ViewHelper', 'Errors');
		
		$this->setName('riders');
		$this->setAttrib('accept-charset', 'utf-8');
		$this->setAttrib('enctype', Zend_Form::ENCTYPE_MULTIPART);
		
		// id
		$idRider = new Zend_Form_Element_Hidden('idRider');
		$idRider->setDecorators($decorator);
		
		
		// nombre del piloto
		$riderName = new Zend_Form_Element_Text('strRiderName');
		$riderName->setRequired(true)
							->addFilters(array('StripTags', 'StringTrim'))
							->addValidator('NotEmpty')
							->setDecorators($decorator)
							->setLabel('RIDERS_FORM_NAME')
							->setAttrib('maxlength', 100);
		
		// equipo del piloto
		$teams = new Application_Model_DbTable_Teams();
		$riderTeam = new Zend_Form_Element_Select('intRiderTeam');		
		$riderTeam->addMultiOptions($teams->getAllTeamsForForm())
							->addValidator('NotEmpty')
							->setLabel('RIDERS_FORM_TEAM');
		
		// categoria del piloto
		$categories = new Application_Model_DbTable_Categories();
		$riderCategory = new Zend_Form_Element_Select('intRiderCategory');
		$riderCategory->addMultiOptions($categories->getAllCategories())
									->addValidator('NotEmpty')
									->setLabel('RIDERS_FORM_CATEGORY');
		
		// dorsal del piloto
		$riderNumber = new Zend_Form_Element_Text('intRiderNumber');
		$riderNumber->setRequired(true)
								->addFilter('Int')
								->setDecorators($decorator)
								->setLabel('RIDERS_FORM_NUMBER')
								->setAttrib('maxlength', 3)
								->setAttrib('size', 4);
		
		// fecha de nacimiento del piloto (lo guardamos como int 'timestamp')
		$riderBirth = new Zend_Form_Element_Text('dtRiderBirth');
		$riderBirth->setRequired(true)
								->addFilters(array('StripTags', 'StringTrim'))
								->addValidator('Date')
								->setDecorators($decorator)
								->setLabel('RIDERS_FORM_BIRTH')
								->setAttrib('maxlength', 10);
		
		// moto del piloto
		$bikes = new Application_Model_DbTable_Bikes();
		$riderBike = new Zend_Form_Element_Select('intRiderBike');		
		$riderBike->addMultiOptions($bikes->getAllBikesForForm($options['intRiderCategory']))
							->addValidator('NotEmpty')
							->setLabel('RIDERS_FORM_BIKE');
				
		// ciudad del piloto
		$riderCity = new Zend_Form_Element_Text('strRiderCity');
		$riderCity->setRequired(true)
							->addFilters(array('StripTags', 'StringTrim'))
							->setDecorators($decorator)
							->setLabel('RIDERS_FORM_CITY')
							->setAttrib('maxlength', 100);
		
		// peso del piloto
		$riderWeight = new Zend_Form_Element_Text('intRiderWeight');
		$riderWeight->setRequired(true)
								->addFilter('Int')
								->setDecorators($decorator)
								->setLabel('RIDERS_FORM_WEIGHT')
								->setAttrib('maxlength', 3)
								->setAttrib('size', 4);
		
		// altura del piloto
		$riderHeight = new Zend_Form_Element_Text('intRiderHeight');
		$riderHeight->setRequired(true)
								->addFilter('Int')
								->setDecorators($decorator)
								->setLabel('RIDERS_FORM_HEIGHT')
								->setAttrib('maxlength', 3)
								->setAttrib('size', 4);
		
		// pais del piloto
		$countries = new Application_Model_DbTable_Countries();
		$riderCountry = new Zend_Form_Element_Select('intRiderCountry');
		$riderCountry->addMultiOptions($countries->getAllCountriesForForm())
									->addValidator('NotEmpty')
									->setLabel('RIDERS_FORM_COUNTRY');
		
		// File para subir una nueva imagen del piloto
		$riderImageFile = new Zend_Form_Element_File('strRiderImageFile');
		$riderImageFile->setLabel('RIDERS_FORM_IMAGE')
							->addValidator('FilesSize', false, 1024*1024) // Tamaño del fichero en bytes (1Mb)
							->addValidator('Extension', false, 'jpg,jpeg,gif,png')
							->addValidator('ImageSize', false, array('maxwidth' => 200, 'maxheight' => 200)) // Tamaño maximo de la imagen
							->setValueDisabled(true)
							->addValidator('MimeType', false, 'image'); // Solo permitimos imagenes, controlando el mimetype

		// Campo oculto para la actual imagen, por si no la queremos modificar.
		$riderImage = new Zend_Form_Element_Hidden('strRiderImage');
		$riderImage->setDecorators($decorator);
		
		// boton de envio del formulario
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setAttrib('id', 'submitbutton')->setDecorators($decorator);
		
		$this->addElements(array($idRider, $riderImage, $riderName, $riderCategory, $riderTeam, $riderBike, $riderNumber, $riderBirth, $riderCity, $riderCountry, $riderWeight, $riderHeight, $riderImageFile, $submit));
	}
	
}

