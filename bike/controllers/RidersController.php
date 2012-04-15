<?php

class RidersController extends Zend_Controller_Action
{

	/**
	 *
	 * Instancia del modelo para los corredores
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
	 * @example RIDERS_
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
	 * Guarda el nombre de la imagen del piloto
	 * @var string
	 */
	private $_imgName;
	

	
	/**
	 * 
	 * Guarda el directorio donde se almacenan las imagenes de los pilotos
	 * @var string
	 */
	private $_imageDir;

	
	
	/**
	 * 
	 * Guarda la configuracion para poder recuperar algunos valores
	 * @var array
	 */
	private $_config;
	

	/**
	 *
	 * Inicializamos algunas variables
	 */
	public function init()
	{
		$this->_cname = strtoupper($this->getRequest()->getControllerName()) . '_';
		$this->_action = strtoupper($this->getRequest()->getActionName());
		$this->_titulo = ' - ' . $this->view->translate->_($this->_cname . 'TITLE') . ' - ';
		$this->_table = new Application_Model_DbTable_Riders;
		$this->_config = $this->getInvokeArg('bootstrap')->getOptions();
		$this->_imageDir = $this->_config['motogp']['dirRiderImages'];
	}


	/**
	 *
	 * Mostraremos un select para poderl seleccionar la categoría de los pilotos que queremos ver.
	 */
	public function indexAction()
	{
		// Debemos recuperar las categorias para mostrarlas en el fomrulario como un selectbox
		$categories = new Application_Model_DbTable_Categories();
		$this->view->categories = $categories->getAllCategories();
	}


	/**
	 *
	 * Listamos los corredores
	 */
	public function listAction()
	{
		$this->view->titulo = $this->_titulo . $this->view->translate->_($this->_cname . $this->_action);
		if ($this->getRequest()->isPost()) {
			$form = $this->getRequest()->getPost();
			if ($form['intRiderCategory'] > 0) {
				$this->view->urlRiderImages = $this->_config['motogp']['urlRiderImages'];
				$this->view->riders = $this->_table->getAllRiders($form['intRiderCategory']);
			}else{
				$this->view->msg = $this->view->translate->_('RIDERS_CATEGORY_NOT_FOUND'); 
			}
		}
	}

	/**
	 *
	 * Editamos un corredor
	 */
	public function editAction()
	{
		$this->view->titulo = $this->_titulo . $this->view->translate->_($this->_cname . $this->_action);

		$form = new Form_Riders();
		$form->submit->setLabel($this->view->translate->_($this->_cname . $this->_action . '_SAVE'));

		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			if ($form->isValid($formData)) {
				
				$res = $this->_table->updateRider($form->getValues());

				if($res['errnum'] == 1){
					$this->_callRender($res['errmsg']);
					return;
				}

				$msg[] = $this->view->translate->_($this->_cname . $this->_action . '_OK');
				
				// Comrpobamos que el fichero se suba correctamente
				if ($this->uploadImage($form->strRiderName->getValue())) {
					// Si el fichero se sube correctamente, debemos colocar el nombre del fichero en la base de datos.
					if($this->_imgName != NULL)
						$this->_table->setRiderImage($form->idRider->getValue(), $this->_imgName);
					$msg[] = $this->view->translate->_('RIDERS_IMAGE_UPLOAD_OK');
				}else{
					$msg[] = $this->view->translate->_('RIDERS_IMAGE_UPLOAD_FAIL');
				}
				
				$this->_callRender($msg);
			} else {
				$form->populate($formData);
			}
		} else{
			$arr = $this->_table->getRider($this->_getParam('id', 0));

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
	 * Añadimos un corredor
	 */
	public function addAction()
	{
		$this->view->titulo = $this->_titulo . $this->view->translate->_($this->_cname . $this->_action);

		$form = new Form_Riders();
		$form->submit->setLabel($this->view->translate->_($this->_cname . $this->_action));
		 
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			if ($form->isValid($formData)) {
				
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
				
			} else {
				$form->populate($formData);
			}
		}
		$this->view->form = $form;
	}


	/**
	 *
	 * Borramos un corredor
	 */
	public function deleteAction(){
		$this->view->titulo = $this->_titulo . $this->view->translate->_($this->_cname . $this->_action);

		$id = $this->_getParam('id', 0);

		// Si estamos en la confirmacion para eliminar, pues eliminamos.
		if ($this->_getParam('b', 0) == 1) {
			// eliminamos el registro de la base de datos junto con la imagen del piloto.
			$this->_callRender($this->view->translate->_($this->_table->deleteRider($id, $this->_imageDir)));
		}
			
		$this->view->rider = $this->_table->getRider($id);
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
	
	
	public function uploadImage($riderName){
		$adapter = new Zend_File_Transfer();
		$adapter->setDestination($this->_imageDir);

		if($adapter->getFileName('strRiderImageFile', false) != NULL){
			$img = pathinfo($adapter->getFileName('strRiderImageFile', false));
			$this->_imgName = strtolower(str_replace(' ', '_', $riderName)) . '.' . $img['extension'];
			$adapter->addFilter('Rename', array('target' => $this->_imageDir . $this->_imgName, 'overwrite' => true));
			return ($adapter->receive()) ? true : false;
		}
		return false;
		
	}
	
	
}

