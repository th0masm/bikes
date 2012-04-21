<?php

class Application_Model_DbTable_Bikes extends Zend_Db_Table_Abstract
{
	/**
	*
	* Nombre de la tabla para las motos
	* @var string
	*/
	protected $_name = 'tblBikes';
	
	/**
	 *
	 * Nombre de la clave primaria en la tabla tblBikes
	 * @var String
	 */
	protected $_primary = array('idBike');
	
	
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
	 * Inicializa los filtros para los datos y otras cosas :)
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
	 * Devuelve todos los registros de las motos haciendo un join de las categorias.
	 * @return Objeto fetchAll
	 */
	public function getAllBikes($category = NULL){
		if (!is_null($category)) {
			return $this->select()
									->from($this->_name)
									->where('intBikeCategory = ?', $category)
									->setIntegrityCheck(false)
									->join('tblCategories', $this->_name.'.intBikeCategory = tblCategories.idCategory')
									->order($this->_name . '.strBikeName ASC')
									->query()
									->fetchAll();
		}
		return $this->select()
								->from($this->_name)
								->setIntegrityCheck(false)
								->join('tblCategories', $this->_name.'.intBikeCategory = tblCategories.idCategory')
								->order('tblCategories.strCategoryName ASC', $this->_name . '.strBikeName ASC')
								->query()
								->fetchAll();
	}
	
	
	/**
	 * 
	 * Devuelve todas las motos para mostrarlas en los select de los formularios.
	 * @return array
	 */
	public function getAllBikesForForm($category = 1){
		$rows = $this->select()
								->from($this->_name)
								->where('intBikeCategory = ?', $category)
								->order($this->_name . '.strBikeName ASC')
								->query()
								->fetchAll();

		foreach($rows AS $row)
			$res[$row['idBike']] = $row['strBikeName'];
		return $res;
	}
	
	
	
	/**
	 *
	 * Recogemos una moto para listarla
	 * @param Int $id
	 * @throws Exception
	 * @return Array
	 */
	public function getBike($id){
		$id = $this->_filtro['int']->filter($id);
	
		// Buscamos una moto
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
	 * Insertamos una nueva moto
	 * @param Array
	 */
	public function addBike($bike, $categoria){
		$bikeName = $this->_filtro['string']->filter($bike);
		$bikeCategory = $this->_filtro['int']->filter($categoria);
	
		// Si ya existe la moto, no continuamos
		if($this->existsBike($bikeName, $bikeCategory))
			return array('errnum' => 1, 'errmsg' => sprintf($this->_tr->translate($this->_cname . 'EXISTS'), $bikeName));
			
		$this->insert(array(
												'strBikeName' => $bikeName, 
												'intBikeCategory' => $bikeCategory));
	}
	
	/**
	 *
	 * Actualiza una moto
	 * @param Int $id
	 * @param String $bikeName
	 * @param String $bikeCategory
	 */
	public function updateBike($id, $bikeName, $bikeCategory){
		$id = $this->_filtro['int']->filter($id);
		$bikeName = $this->_filtro['string']->filter($bikeName);
		$bikeCategory = $this->_filtro['string']->filter($bikeCategory);
			
		// Si ya existe la moto, no continuamos
		if($this->existsBike($bikeName, $bikeCategory))
		return array('errnum' => 1, 'errmsg' => sprintf($this->_tr->translate($this->_cname . 'EXISTS'), $bikeName));
			
		$this->update(array(
												'strBikeName' => $bikeName, 
												'intBikeCategory' => $bikeCategory), 
									$this->_primary[1] . ' = ' . $id);
	}
	
	
	/**
	 *
	 * Nos dice si una moto ya existe en la base de datos.
	 * @param String $bike
	 * @param String $category
	 */
	private function existsBike($bike, $category){
		$row = $this->fetchRow($this->select()->where('strBikeName = ?', $bike));
		return (($row->strBikeName == $bike) && ($row->intBikeCategory == $category)) ? true : false;
	}
	
	
	
	/**
	 *
	 * Eliminamos una moto
	 * @param Int $id
	 */
	public function deleteBike($id){
		$id = $this->_filtro['int']->filter($id);
		$row = $this->find($id);
		if($row->count() == 1){
			// Probamos a eliminar la moto...
			try{
				$this->delete($this->getAdapter()->quoteInto($this->_primary[1] . ' = ?', $id));
			}catch (Exception $e){
				// Si salta una excepcion, puede ser por las foreign keys (getCode = 1451)
				if ($e->getCode() == 1451){
					return 'BIKES_DELETE_ERROR_FOREIGN';
				}
			}
			return 'BIKES_DELETE_OK';
		}else{
			return 'BIKES_DELETE_NOT_FOUND';
		}
		return 'BIKES_DELETE_ERROR';
	}
	
	

}

