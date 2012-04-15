<?php

class CategoriesController extends Zend_Controller_Action
{
		
		/**
		 * 
		 * Instancia del modelo para las categorias 
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
		 * @example CATEGORIES_
		 */
		private $_cname;
		
		
		
		/**
		 * 
		 * Guarda el nombre de la accion seleccionada, para usarlo en el translate
		 * @var unknown_type
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
    	$this->_table = new Application_Model_DbTable_Categories;      
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
     * Listamos las categorias
     */
    public function listAction()
    {
			$this->view->titulo = $this->_titulo . $this->view->translate->_($this->_cname . $this->_action);
			$this->view->categorias = $this->_table->fetchAll();
    }

    
    /**
     * 
     * Editamos una categoria
     */
    public function editAction()
    {
    	$this->view->titulo = $this->_titulo . $this->view->translate->_($this->_cname . $this->_action);
    	
    	$form = new Form_Categories();
    	$form->submit->setLabel($this->view->translate->_($this->_cname . $this->_action . '_SAVE'));
    	
    	if ($this->getRequest()->isPost()) {
    		$formData = $this->getRequest()->getPost();
    		if ($form->isValid($formData)) {
    			$id = (int)$form->getValue('idCategory');
    			$categoria = $form->getValue('strCategoryName');
    			
    			$res = $this->_table->updateCategory($id, $categoria);
    			
    			if($res['errnum'] == 1){
    				$this->_callRender($res['errmsg']);
    				return;
    			}
    			
    			$this->_callRender($this->view->translate->_($this->_cname . $this->_action . '_OK'));
    		} else {
    			$form->populate($formData);
    		}
    	} else{
    		$arr = $this->_table->getCategory($this->_getParam('id', 0));
    		
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
     * Añadimos una nueva categoria
     */
    public function addAction()
    {
    	$this->view->titulo = $this->_titulo . $this->view->translate->_($this->_cname . $this->_action);
    	 
    	$form = new Form_Categories();
    	$form->submit->setLabel($this->view->translate->_($this->_cname . $this->_action));
    	 
    	if ($this->getRequest()->isPost()) {
    		$formData = $this->getRequest()->getPost();
    		if ($form->isValid($formData)) {
    			$categoria = $form->getValue('strCategoryName');
    			$res = $this->_table->addCategory($categoria);

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
     * Borramos una categoria
     */
    public function deleteAction()
    {
			$this->view->titulo = $this->_titulo . $this->view->translate->_($this->_cname . $this->_action);
			
			$id = $this->_getParam('id', 0);
			
			if ($this->_getParam('b', 0) == 1) {
				// Nos devuelve un codigo de error dependiendo de lo ejecutado
				$this->_callRender($this->view->translate->_($this->_table->deleteCategory($id)));
			}
			
			$this->view->categoria = $this->_table->getCategory($id);
			$this->view->delete = 'categories/delete/id/' . $id . '/b/1';
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


