<?php

class BikesController extends Zend_Controller_Action
{
	
	/**
	 *
	 * Instancia del modelo para las motos
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
	 * @example BIKES_
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
		$this->_table = new Application_Model_DbTable_Bikes;
	}


	/**
	 *
	 * En el index no hacemos nada mas que una redireccion a la accion list
	 */
	public function indexAction()
	{
		$this->_forward('list');
	}


	/**
	 *
	 * Listamos las motos
	 */
	public function listAction()
	{
		$this->view->titulo = $this->_titulo . $this->view->translate->_($this->_cname . $this->_action);
		$this->view->bikes = $this->_table->getAllBikes();
	}


	/**
	 *
	 * Editamos una moto
	 */
	public function editAction()
	{
		$this->view->titulo = $this->_titulo . $this->view->translate->_($this->_cname . $this->_action);

		$form = new Form_Bikes();
		$form->submit->setLabel($this->view->translate->_($this->_cname . $this->_action . '_SAVE'));

		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			if ($form->isValid($formData)) {
				$id = (int)$form->getValue('idBike');
				$bike = $form->getValue('strBikeName');
				$categoria = $form->getValue('intBikeCategory');

				$res = $this->_table->updateBike($id, $bike, $categoria);

				if($res['errnum'] == 1){
					$this->_callRender($res['errmsg']);
					return;
				}

				$this->_callRender($this->view->translate->_($this->_cname . $this->_action . '_OK'));
			} else {
				$form->populate($formData);
			}
		} else{
			$arr = $this->_table->getBike($this->_getParam('id', 0));

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
	 * Añadimos una nueva moto
	 */
	public function addAction()
	{
		$this->view->titulo = $this->_titulo . $this->view->translate->_($this->_cname . $this->_action);

		$form = new Form_Bikes();
		$form->submit->setLabel($this->view->translate->_($this->_cname . $this->_action));
		 
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			if ($form->isValid($formData)) {
				
				$categoria = $form->getValue('intBikeCategory');
				$moto = $form->getValue('strBikeName');
				$res = $this->_table->addBike($moto, $categoria);
				
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
	 * Borramos una moto
	 */
	public function deleteAction(){
		$this->view->titulo = $this->_titulo . $this->view->translate->_($this->_cname . $this->_action);

		$id = $this->_getParam('id', 0);

		if ($this->_getParam('b', 0) == 1) {
			$this->_callRender($this->view->translate->_($this->_table->deleteBike($id)));
		}
			
		$this->view->bike = $this->_table->getBike($id);
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


