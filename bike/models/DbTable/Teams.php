<?php

class Application_Model_DbTable_Teams extends Zend_Db_Table_Abstract
{

	/**
	*
	* Nombre de la tabla para los equipos
	* @var string
	*/
    protected $_name = 'tblTeams';
	
	/**
	 *
	 * Nombre de la clave primaria en la tabla tblTeams
	 * @var String
	 */
	protected $_primary = array('idTeam');
	
	
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
		// Filtro para los String, elimina todo caracter que no sea Alfanumerico y el espacio en blanco
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
	 * Devuelve todos los registros de los equipos haciendo un join de las categorias.
	 * @return Objeto fetchAll
	 */
	public function getAllTeams(){
		return $this->select()
												->from($this->_name)
												->setIntegrityCheck(false)
												->join('tblCategories', $this->_name.'.intTeamCategory = tblCategories.idCategory')
												->order(array('tblCategories.strCategoryName', 'strTeamName'))
												->query()
												->fetchAll();
	}
	
	
	
	/**
	*
	* Devuelve todos los equipos para mostrarlos en los select de los formularios.
	* @return array
	*/
	public function getAllTeamsForForm(){
		$rows = $this->select()
								->from($this->_name)
								->order(array('strTeamName'))
								->query()
								->fetchAll();
		
		foreach($rows AS $row)
			$res[$row['idTeam']] = $row['strTeamName'];
		return $res;
	}
	
	
	
	/**
	 *
	 * Recogemos un equipo para listarlo
	 * @param Int $id
	 * @throws Exception
	 * @return Array
	 */
	public function getTeam($id){
		$id = $this->_filtro['int']->filter($id);
	
		// Buscamos un equipo
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
	 * Insertamos un nuevo equipo
	 * @param Array
	 */
	public function addTeam($team, $categoria){
		$teamName = $this->_filtro['string']->filter($team);
		$teamCategory = $this->_filtro['int']->filter($categoria);
	
		// Si ya existe el equipo, no continuamos
		if($this->existsTeam($teamName, $teamCategory))
			return array('errnum' => 1, 'errmsg' => sprintf($this->_tr->translate($this->_cname . 'EXISTS'), $teamName));
			
		$this->insert(array(
												'strTeamName' => $teamName, 
												'intTeamCategory' => $teamCategory));
	}
	
	/**
	 *
	 * Actualiza un equipo
	 * @param Int $id
	 * @param String $teamName
	 * @param String $teamCategory
	 */
	public function updateTeam($id, $teamName, $teamCategory){
		$id = $this->_filtro['int']->filter($id);
		$teamName = $this->_filtro['string']->filter($teamName);
		$teamCategory = $this->_filtro['string']->filter($teamCategory);
			
		// Si ya existe el equipo, no continuamos
		if($this->existsTeam($teamName, $teamCategory))
		return array('errnum' => 1, 'errmsg' => sprintf($this->_tr->translate($this->_cname . 'EXISTS'), $teamName));
			
		$this->update(array(
												'strTeamName' => $teamName, 
												'intTeamCategory' => $teamCategory), 
									$this->_primary[1] . ' = ' . $id);
	}
	
	
	/**
	 *
	 * Nos dice si un equipo ya existe en la base de datos.
	 * @param String $team
	 */
	private function existsTeam($team, $categoria){
		$row = $this->fetchRow($this->select()->where('strTeamName = ?', $team));
		return (($row->strTeamName == $team) && ($row->intTeamCategory == $categoria)) ? true : false;
	}
	
	
	
	/**
	 *
	 * Eliminamos un equipo
	 * @param Int $id
	 */
	public function deleteTeam($id){
		$id = $this->_filtro['int']->filter($id);
		$row = $this->find($id);
		if($row->count() == 1){
			// Probamos a eliminar el equipo...
			try{
				$this->delete($this->getAdapter()->quoteInto($this->_primary[1] . ' = ?', $id));
			}catch (Exception $e){
				// Si salta una excepcion, puede ser por las foreign keys (getCode = 1451)
				if ($e->getCode() == 1451){
					return 'TEAMS_DELETE_ERROR_FOREIGN';
				}
			}
			return 'TEAMS_DELETE_OK';
		}else{
			return 'TEAMS_DELETE_NOT_FOUND';
		}
		return 'TEAMS_DELETE_ERROR';
	}
	
	

}

