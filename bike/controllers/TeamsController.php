<?php

class TeamsController extends Zend_Controller_Action
{
	
	/**
	 *
	 * Instancia del modelo para los equipos
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
	 * @example TEAMS_
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
		$this->_table = new Application_Model_DbTable_Teams;
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
	 * Listamos los equipos
	 */
	public function listAction()
	{
		$this->view->titulo = $this->_titulo . $this->view->translate->_($this->_cname . $this->_action);
		$this->view->teams = $this->_table->getAllTeams();
	}


	/**
	 *
	 * Editamos un equipo
	 */
	public function editAction()
	{
		$this->view->titulo = $this->_titulo . $this->view->translate->_($this->_cname . $this->_action);

		$form = new Form_Teams();
		$form->submit->setLabel($this->view->translate->_($this->_cname . $this->_action . '_SAVE'));

		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			if ($form->isValid($formData)) {
				
				$id = (int)$form->getValue('idTeam');
				$team = $form->getValue('strTeamName');
				$categoria = $form->getValue('intTeamCategory');

				$res = $this->_table->updateTeam($id, $team, $categoria);

				if($res['errnum'] == 1){
					$this->_callRender($res['errmsg']);
					return;
				}

				$this->_callRender($this->view->translate->_($this->_cname . $this->_action . '_OK'));
			} else {
				$form->populate($formData);
			}
		} else{
			$arr = $this->_table->getTeam($this->_getParam('id', 0));

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
	 * Añadimos un equipo
	 */
	public function addAction()
	{
		$this->view->titulo = $this->_titulo . $this->view->translate->_($this->_cname . $this->_action);

		$form = new Form_Teams();
		$form->submit->setLabel($this->view->translate->_($this->_cname . $this->_action));
		 
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			if ($form->isValid($formData)) {
				
				$categoria = $form->getValue('intTeamCategory');
				$team = $form->getValue('strTeamName');
				$res = $this->_table->addTeam($team, $categoria);
				
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
	 * Borramos un equipo
	 */
	public function deleteAction(){
		$this->view->titulo = $this->_titulo . $this->view->translate->_($this->_cname . $this->_action);

		$id = $this->_getParam('id', 0);

		if ($this->_getParam('b', 0) == 1) {
			$this->_callRender($this->view->translate->_($this->_table->deleteTeam($id)));
		}
			
		$this->view->team = $this->_table->getTeam($id);
		$this->view->delete = 'teams/delete/id/' . $id . '/b/1';
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


