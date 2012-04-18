<?php

class RacesController extends Zend_Controller_Action
{
	/**
	*
	* Instancia del modelo para las carreras
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
	 * @example RACES_
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
		$this->_table = new Application_Model_DbTable_Races;
	}
	
	
	/**
	 *
	 * Mostramos un select con las categorias.
	 */
	public function indexAction()
	{
		// Debemos recuperar las categorias para mostrarlas en el fomrulario como un selectbox
		$categories = new Application_Model_DbTable_Categories();
		$this->view->categories = $categories->getAllCategories();
	}
	
	
	/**
	 *
	 * Listamos las carreras
	 */
	public function listAction()
	{
		/*
		$this->view->titulo = $this->_titulo . $this->view->translate->_($this->_cname . $this->_action);
		$this->view->races = $this->_table->getAllRaces();
		*/
		
		
		$this->view->titulo = $this->_titulo . $this->view->translate->_($this->_cname . $this->_action);
		if ($this->getRequest()->isPost()) {
			$form = $this->getRequest()->getPost();
			if ($form['intRaceCategory'] > 0) {
				$this->view->races = $this->_table->getAllRaces($form['intRaceCategory']);
			}else{
				$this->view->msg = $this->view->translate->_('RACES_CATEGORY_NOT_FOUND');
			}
		}
		
		
		
		
		
		
	}
	
	
	/**
	 *
	 * Editamos una carrera
	 */
	public function editAction()
	{
		$this->view->titulo = $this->_titulo . $this->view->translate->_($this->_cname . $this->_action);
	
		$form = new Form_Races();
		$form->submit->setLabel($this->view->translate->_($this->_cname . $this->_action . '_SAVE'));
	
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			if ($form->isValid($formData)) {
				/*
				$id = (int)$form->getValue('idRace');
				$race = $form->getValue('strRaceName');
				$categoria = (int)$form->getValue('intRaceCategory');
				$fecha = $form->getValue('dtRaceDate');
				$pais = (int)$form->getValue('intRaceCountry');
				*/
	
				$res = $this->_table->updateRace($form->getValues());
	
				if($res['errnum'] == 1){
					$this->_callRender($res['errmsg']);
					return;
				}
	
				$this->_callRender($this->view->translate->_($this->_cname . $this->_action . '_OK'));
			} else {
				$form->populate($formData);
			}
		} else{
			$arr = $this->_table->getRace($this->_getParam('id', 0));
	
			if($arr['errnum'] == 1) {
				$this->_callRender($arr['errmsg']);
				return;
			}
	
			$form->populate($arr);
	
		}
		$this->view->form = $form;
	}
	
	
	
	
	/**
	 *
	 * Añadimos una nueva carrera
	 */
	public function addAction()
	{
		$this->view->titulo = $this->_titulo . $this->view->translate->_($this->_cname . $this->_action);
	
		$form = new Form_Races();
		$form->submit->setLabel($this->view->translate->_($this->_cname . $this->_action));
			
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			if ($form->isValid($formData)) {
	
				$res = $this->_table->addRace($form->getValues());
	
				if($res['errnum'] == 1){
					$this->_callRender($res['errmsg']);
					return;
				}
	
				$this->_callRender($this->view->translate->_($this->_cname . $this->_action . '_OK'));
			} else {
				$form->populate($formData);
			}
		}
		$this->view->form = $form;
	}
	
	
	/**
	 *
	 * Borramos una carrera
	 */
	public function deleteAction(){
		$this->view->titulo = $this->_titulo . $this->view->translate->_($this->_cname . $this->_action);
	
		$id = $this->_getParam('id', 0);
	
		if ($this->_getParam('b', 0) == 1) {
			$this->_callRender($this->view->translate->_($this->_table->deleteRace($id)));
		}
			
		$this->view->race = $this->_table->getRace($id);
	}
	
	
	/**
	 *
	 * Funcion para mostrar el mensaje de resultado
	 * @param String $msg
	 */
	public function _callRender($msg = ''){
		$this->view->msg = $msg;
		$this->render('result');
	}
	

}









