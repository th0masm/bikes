<?php

class Application_Model_DbTable_Categories extends Zend_Db_Table_Abstract
{
		/**
		 * 
		 * Nombre de la tabla para las categorias
		 * @var string
		 */
    protected $_name = 'tblCategories';
    
    /**
     * 
     * Nombre de la clave primaria en la tabla tblCategories
     * @var String
     */
    protected $_primary = array('idCategory');

    
    /**
     * 
     * Guarda una instancia para filtrar los datos.
     * @var Zend_Filter
     */
    protected $_filtro = array();
    
    
    /**
     * 
     * Instancia para el traductor
     * @var Zend_Translate
     * $this->_tr->translate('CADENA_A_TRADUCIR');
     */    
    protected $_tr;
    
    
    /**
     * 
     * Guarda el nombre del controlador para el translate
     * @var String
     */
    protected $_cname;
    
    
    
    /**
     * 
     * Guarda el nombre de la accion para el translate
     * @var String
     */
    protected $_action;
    

    /**
     * Inicializa los filtros para los datos.
     * @see Zend_Db_Table_Abstract::init()
     */
    public function init(){
    	// Filtro para los String, elimina todo caracter que no sea Alphanumerico y el espacio en blanco
    	$this->_filtro['string'] = new Zend_Filter_Alnum(array('allowwhitespace' => true));
    	
    	// Filtra cualquier dato que no sea un int
    	$this->_filtro['int'] = new Zend_Filter_Int();

    	// Instanciamos el traductor que tenemos configurado en el bootstrap
    	$translator = Zend_Registry::get('Zend_Translate');
    	$this->_tr = $translator->getAdapter();
    	
    	// Recuperamos el nombre del controlador y de la accion llamados y los pasamos a mayusculas
    	$this->_cname = strtoupper(Zend_Controller_Front::getInstance()->getRequest()->getControllerName()) . '_';
    	$this->_action = strtoupper(Zend_Controller_Front::getInstance()->getRequest()->getActionName());
    }
    
    
    
    /**
     * 
     * Recogemos una categoria para listarla
     * @param Int $id
     * @throws Exception
     * @return Array
     */
		public function getCategory($id){
			$id = $this->_filtro['int']->filter($id);

			// Buscamos una categoria
			$row = $this->find($id);
			
			// Si no hay registros...
			if($row->count() == 0){
				return array('errmsg' => $this->_tr->translate($this->_cname . 'NOT_FOUND'), 'errnum' => 1);
			}else{
				$a = $row->toArray();
				return $a[0];
			}
		}
		
		/**
		 * 
		 * Insertamos una nueva categoria
		 * @param String $category
		 */
		public function addCategory($category){
			$category = $this->_filtro['string']->filter($category);
				
			// Si ya existe la categoria , no continuamos
			if($this->existsCategory($category))
				return array('errnum' => 1, 'errmsg' => sprintf($this->_tr->translate($this->_cname . 'EXISTS'), $category));
			
			$cat = array('strCategoryName' => $category);
			$this->insert($cat);
		}
		
		/**
		 * 
		 * Actualiza una categoria
		 * @param Int $id
		 * @param String $category
		 */
		public function updateCategory($id, $category){
			$id = $this->_filtro['int']->filter($id);
			$category = $this->_filtro['string']->filter($category);
			
			// Si ya existe la categoria , no continuamos
			if($this->existsCategory($category))
				return array('errnum' => 1, 'errmsg' => sprintf($this->_tr->translate($this->_cname . 'EXISTS'), $category));
			
			$this->update(array('strCategoryName' => $category), $this->_primary[1] . ' = ' . $id);
		}

		
		/**
		 * 
		 * Nos dice si una categoria ya existe en la base de datos.
		 * @param String $category
		 */
		private function existsCategory($category){
			$row = $this->fetchRow($this->select()->where('strCategoryName = ?', $category));
			return ($row->strCategoryName == $category) ? true : false;
		}
		
		
		
		/**
		 * 
		 * Eliminamos una categoria
		 * @param Int $id
		 * @return String 'Codigo de error'
		 */
		public function deleteCategory($id){
			$id = $this->_filtro['int']->filter($id);
			$row = $this->find($id);
			if($row->count() == 1){
				// Probamos a eliminar la categoria...
				try{
					$this->delete($this->getAdapter()->quoteInto($this->_primary[1] . ' = ?', $id));
				}catch (Exception $e){
					// Si salta una excepcion, puede ser por las foreign keys (getCode = 1451)
					if ($e->getCode() == 1451){
						return 'CATEGORIES_DELETE_ERROR_FOREIGN';
					}
				}
				return 'CATEGORIES_DELETE_OK';
			}else{
				return 'CATEGORIES_DELETE_NOT_FOUND';
			}
			return 'CATEGORIES_DELETE_ERROR';
		}
		
		
		/**
		 * 
		 * Devuelve todas las categorias en formato array para los select de los formularios
		 * @return Array
		 */
		public function getAllCategories(){
			
			$cats = $this->fetchAll();
			// Si no hay categorias devolvemos false.
			if (count($cats) < 0) return false;
			
			foreach ($cats AS $category)
				$arr[$category->idCategory] = $category->strCategoryName;

			return $arr;
			
		}
		
}

