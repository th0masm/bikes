<?php

class Application_Model_DbTable_Races extends Zend_Db_Table_Abstract
{

	/**
	*
	* Nombre de la tabla para las carreras
	* @var string
	*/
	protected $_name = 'tblRaces';
	
	/**
	 *
	 * Nombre de la clave primaria en la tabla tblRaces
	 * @var String
	 */
	protected $_primary = array('idRace');
	
	
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
	 * Devuelve todos los registros de las carreras haciendo un join de las categorias.
	 * @param String $category
	 * @return Objeto fetchAll
	 */
	public function getAllRaces($category){
		/*
		return $this->select()
								->from($this->_name)
								->setIntegrityCheck(false)
								->join('tblCategories', $this->_name.'.intRaceCategory = tblCategories.idCategory')
								->order($this->_name . '.strRaceName ASC')
								->query()
								->fetchAll();
		*/
		return $this->select()
								->from($this->_name)
								->where('intRaceCategory = ' . $category)
								->setIntegrityCheck(false)
								->join('tblCategories', $this->_name . '.intRaceCategory = tblCategories.idCategory')
								->order(array('tblCategories.strCategoryName', 'strRaceName'))
								->query()
								->fetchAll();
		
		
		
	}
	
	
	/**
	 *
	 * Devuelve todas las carreras para mostrarlas en los select de los formularios.
	 * @return array
	 */
	public function getAllRacesForForm(){
		$rows = $this->fetchAll()->toArray();
		foreach($rows AS $row)
			$res[$row['idRace']] = $row['strRaceName'];
		return $res;
	}
	
	
	
	/**
	 *
	 * Recogemos una categoria para listarla
	 * @param Int $id
	 * @throws Exception
	 * @return Array
	 */
	public function getRace($id){
		$id = $this->_filtro['int']->filter($id);
	
		// Buscamos una carrera
		$row = $this->find($id);
			
		// Si no hay registros...
		if($row->count() == 0){
			return array('errmsg' => $this->_tr->translate($this->_cname . 'NOT_FOUND'), 'errnum' => 1);
		}else{
			$a = $row->toArray();
			$f = new Zend_Date($a[0]['dtRaceDate']);
			$a[0]['dtRaceDate'] = $f->toString("dd/MM/YYYY");;
			return $a[0];
		}
	}
	
	
	/**
	 *
	 * Insertamos una nueva carrera
	 * @param Array $race
	 */
	public function addRace($race){
		// Filtramos los datos.
		$race = $this->setFilters($race);
		unset($race['idRace']);

		// Si ya existe la carrera, no continuamos
		if($this->existsRace($race, true))
			return array('errnum' => 1, 'errmsg' => sprintf($this->_tr->translate($this->_cname . 'EXISTS'), $race['strRaceName']));
			
		$this->insert($race);
		
	}
	
	/**
	 *
	 * Actualiza una carrera
	 * @param Array $race
	 */
	public function updateRace($race){
		// Filtramos los datos.
		$race = $this->setFilters($race);

		// Si ya existe la carrera, no continuamos
		if($this->existsRace($race))
			return array('errnum' => 1, 'errmsg' => sprintf($this->_tr->translate($this->_cname . 'EXISTS'), $race['strRaceName']));
			
		$this->update($race, $this->_primary[1] . ' = ' . $race['idRace']);
	}
	
	
	/**
	 *
	 * Nos dice si una carrera ya existe en la base de datos.
	 * @param Array $race
	 * @param Bool $add
	 */
	private function existsRace($race, $add = false){
		$row = $this->fetchRow($this->select()->where('strRaceName = ?', $race['strRaceName']));

		// Si estamos añadiendo, comprobamos solo el nombre
		if ($add) {
			return (is_null($row)) ? false : true;
		} else {
			return (($row->strRaceName == $race['strRaceName']) && $row->idRace != $race['idRace']) ?  true : false;
		}
	}
	
	
	
	/**
	 *
	 * Eliminamos una carrera
	 * @param Int $id
	 */
	public function deleteRace($id){
		$id = $this->_filtro['int']->filter($id);
		$row = $this->find($id);
		if($row->count() == 1){
			// Probamos a eliminar la carrera...
			try{
				$this->delete($this->getAdapter()->quoteInto($this->_primary[1] . ' = ?', $id));
			}catch (Exception $e){
				// Si salta una excepcion, puede ser por las foreign keys (getCode = 1451)
				if ($e->getCode() == 1451){
					return 'RACES_DELETE_ERROR_FOREIGN';
				}
			}
			return 'RACES_DELETE_OK';
		}else{
			return 'RACES_DELETE_NOT_FOUND';
		}
		return 'RACES_DELETE_ERROR';
	}
	
	
	
	
	
	private function setFilters($race){
		$race['idRace'] = $this->_filtro['int']->filter($race['idRace']);
		$race['strRaceName'] = $this->_filtro['string']->filter($race['strRaceName']);
		$race['intRaceCategory'] = $this->_filtro['int']->filter($race['intRaceCategory']);
		// Creamos un objeto Zend_Date y como parámetro la fecha que recibimos
		$fecha = new Zend_Date($race['dtRaceDate']);
		$race['dtRaceDate'] = $fecha->toString("YYYY-MM-dd");
		$race['intRaceCountry'] = $this->_filtro['int']->filter($race['intRaceCountry']);
		
		return $race;
	}
	


}

