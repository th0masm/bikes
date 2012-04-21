<?php

class ResultsController extends Zend_Controller_Action
{

	/**
	 *
	 * Instancia del modelo para los resultados
	 * @var Db_Table
	 */
	private $_table;
	
	
	/**
	 *
	 * Guarda el titulo del controlador
	 * @var String
	 */
	private $_titulo;
	
	
	
	/**
	 *
	 * Guarda el nombre del controlador en mayúsculas para el translate
	 * @var String
	 * @example RESULTS_
	 */
	private $_cname;
	
	
	
	/**
	 *
	 * Guarda el nombre de la accion seleccionada, para usarlo en el translate
	 * @var String
	 */
	private $_action;
	
	
	
	/**
	 *
	 * Inicializamos algunas variables
	 */
	public function init()
	{
		$this->_cname = strtoupper($this->getRequest()->getControllerName()) . '_';
		$this->_action = strtoupper($this->getRequest()->getActionName());
		$this->_titulo = ' - ' . $this->view->translate->_($this->_cname . 'TITLE') . ' - ';
		$this->_table = new Application_Model_DbTable_Results;
	}
	
	
	
	/**
	 *
	 * Mostraremos un select para poder seleccionar la carrera que queremos listar.
	 */
  public function indexAction()
  {
	 	// Debemos recuperar las carreras dependiendo de las categorias. getAllRacesForResults
	 	
  	$races = new Application_Model_DbTable_Races();
  	$this->view->races = $races->getAllRacesForResults();
  	
	}


	
	
	/**
	 *
	 * Añadimos los resultados, dependiendo de la categoría
	 */
	public function addAction()
	{
		$this->view->titulo = $this->_titulo . $this->view->translate->_($this->_cname . $this->_action);

		
		// Si queremos añadir unos resultados nuevos, comprobamos que el id de la carrera este seteado y sea mayor de 0
		$race = $this->getRequest()->getParam('race');
		if (isset ($race) && ($race > 0)) {
			$form = new Form_Results(array('idRace' => $race));
		}
		exit;
		
		
		
		$form = new Form_Results();
		$form->submit->setLabel($this->view->translate->_($this->_cname . $this->_action));
		
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			if ($form->isValid($formData)) {
				// Formulario enviado, debemos hacer la gestion de los resultados.

				
/*				
				// Enviamso todos los datos del formulario para manejarlos en el modelo.
				$res = $this->_table->addRider($form->getValues());
	
				if($res['errnum'] == 1){
					$this->_callRender($res['errmsg']);
					return;
				}
	
				$msg[] = $this->view->translate->_($this->_cname . $this->_action . '_OK');
	
				// Comrpobamos que el fichero se suba correctamente
				if ($this->uploadImage($form->strRiderName->getValue())) {
					// Si el fichero se sube correctamente, debemos colocar el nombre del fichero en la base de datos.
					$this->_table->setRiderImage($res['lastInsertId'], $this->_imgName);
					$msg[] = $this->view->translate->_('RIDERS_IMAGE_UPLOAD_OK');
				}else{
					$msg[] = $this->view->translate->_('RIDERS_IMAGE_UPLOAD_FAIL');
				}
	
				$this->_callRender($msg);
*/	
			} else {
				$form->populate($formData);
			}
		}
		$this->view->form = $form;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}









