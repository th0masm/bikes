<?php

class Application_Model_DbTable_Riders extends Zend_Db_Table_Abstract
{


	/**
	 *
	 * Nombre de la tabla para los corredores
	 * @var string
	 */
	protected $_name = 'tblRiders';

	/**
	 *
	 * Nombre de la clave primaria en la tabla tblRiders
	 * @var String
	 */
	protected $_primary = array('idRider');


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
	 * Devuelve todos los registros de los corredores haciendo un join de las categorias, equipos y motos.
	 * @return Objeto fetchAll
	 */
	public function getAllRiders($category = NULL){
		
		return $this->select()
								->from($this->_name)
								->where('intRiderCategory = ' . $category)
								->setIntegrityCheck(false)
								->join('tblCategories', $this->_name . '.intRiderCategory = tblCategories.idCategory')
								->join('tblTeams', $this->_name . '.intRiderTeam = tblTeams.idTeam')
								->join('tblBikes', $this->_name . '.intRiderBike = tblBikes.idBike')
								->order(array('intRiderNumber'))
								->query()
								->fetchAll();
	}



	/**
	 *
	 * Devuelve todos los corredores para mostrarlos en los select de los formularios.
	 * @return array
	 */
	public function getAllRidersForForm(){
		$rows = $this->fetchAll()->toArray();
		foreach($rows AS $row)
			$res[$row['idRider']] = $row['strRiderName'];
		return $res;
	}



	/**
	 *
	 * Recogemos un corredor para listarlo
	 * @param Int $id
	 * @throws Exception
	 * @return Array
	 */
	public function getRider($id){
		$id = $this->_filtro['int']->filter($id);
			
		// Buscamos un corredor
		$row = $this->find($id);

		// Si no hay registros...
		if($row->count() == 0){
			return array('errmsg' => $this->_tr->translate($this->_cname . 'NOT_FOUND'), 'errnum' => 1);
		}else{
			$a = $row->toArray();
			$f = new Zend_Date($a[0]['dtRiderBirth']);
			$a[0]['dtRiderBirth'] = $f->toString("dd/MM/YYYY");;
			return $a[0];
		}
	}


	/**
	 *
	 * Insertamos un nuevo corredor
	 * @param Array
	 */
	public function addRider($rider){
		$rider = $this->filterRider($rider);

		unset($rider['strRiderImageFile']);
		
		// Si ya existe el corredor, no continuamos OJO, en el existsRider debo comprobar campo por campo!!!!!!
		if($this->existsRider($rider, true))
			return array('errnum' => 1, 'errmsg' => sprintf($this->_tr->translate($this->_cname . 'EXISTS'), $rider['strRiderName']));

		$this->insert($rider);
		
		// Debemos devolver el ultimo id
		return array('lastInsertId' => $this->getAdapter()->lastInsertId());
	}

	/**
	 *
	 * Actualiza un corredor
	 * @param Array $rider
	 */
	public function updateRider($rider){
		$rider = $this->filterRider($rider);

		// Si ya existe el equipo, no continuamos
		if($this->existsRider($rider))
			return array('errnum' => 1, 'errmsg' => sprintf($this->_tr->translate($this->_cname . 'EXISTS'), $rider['strRiderName']));

		// Asignamos el nombre de la imagen que hemos subido.
		if ($rider['strRiderImageFile'] != NULL) $rider['strRiderImage'] = $rider['strRiderImageFile'];
		// Quitamos del array el campo file del formulario
		unset($rider['strRiderImageFile']);
		$this->update($rider, $this->_primary[1] . ' = ' . $rider['idRider']);
	}


	/**
	 *
	 * Nos dice si un corredor ya existe en la base de datos.
	 * @param String $team
	 */
	private function existsRider($rider, $add = null){
		$row = $this->fetchRow($this->select()->where('strRiderName = ?', $rider['strRiderName']));
		
		// Si estamos añadiendo solo comprobamos el nombre si es igual, no comprobamos el id
		if ($add){
			return ($row->strRiderName == $rider['strRiderName']) ? true : false;
		} else{
			return (($row->strRiderName == $rider['strRiderName']) && ($row->idRider != $rider['idRider'])) ? true : false;
		}
	}

	
	/**
	 * 
	 * Guarda la imagen del piloto
	 * @param int $idRider
	 * @param string $imageRider
	 */
	public function setRiderImage($idRider, $imageRider){
		$this->update(array('strRiderImage' => $imageRider), $this->_primary[1] . ' = ' . $idRider);
	}

	
	/**
	 *
	 * Eliminamos un corredor
	 * @param Int $id
	 */
	public function deleteRider($id, $dirImage = NULL){
		$id = $this->_filtro['int']->filter($id);
		$row = $this->find($id);
		if($row->count() == 1){
			// Recuperamos el corredor para poder eliminar el fichero de la imagen una vez hayamos eliminado el registro.
			$rider = $row->getRow(0);
			// Probamos a eliminar el corredor...
			try{
				$this->delete($this->getAdapter()->quoteInto($this->_primary[1] . ' = ?', $id));
			}catch (Exception $e){
				// Si salta una excepcion, puede ser por las foreign keys (getCode = 1451)
				if ($e->getCode() == 1451){
					return 'RIDERS_DELETE_ERROR_FOREIGN';
				}
			}
			
			// Si hemos eliminado correctamente el corredor, eliminamos tambien la imagen del piloto. Debemos comrpobar que el fichero existe en el directorio, sino, no lo eliminamos, claro.
 			if ((file_exists($dirImage . $rider->strRiderImage)) && ($rider->strRiderImage != NULL)) unlink($dirImage . $rider->strRiderImage);
 			
			return 'RIDERS_DELETE_OK';
		}else{
			return 'RIDERS_DELETE_NOT_FOUND';
		}
		return 'RIDERS_DELETE_ERROR';
	}


	/**
	 * 
	 * Filtra los datos que vienen del formulario
	 * @param array $rider
	 */
	private function filterRider($rider){
		$rider['idRider'] = $this->_filtro['int']->filter($rider['idRider']);
		$rider['strRiderName'] = $this->_filtro['string']->filter($rider['strRiderName']);
		$rider['intRiderCategory'] = $this->_filtro['int']->filter($rider['intRiderCategory']);
		$rider['intRiderTeam'] = $this->_filtro['int']->filter($rider['intRiderTeam']);
		$rider['intRiderBike'] = $this->_filtro['int']->filter($rider['intRiderBike']);
		$rider['intRiderNumber'] = $this->_filtro['int']->filter($rider['intRiderNumber']);
		// Creamos un objeto Zend_Date y como parámetro la fecha que recibimos
		$fecha = new Zend_Date($rider['dtRiderBirth']);
		$rider['dtRiderBirth'] = $fecha->toString("YYYY-MM-dd");
		$rider['strRiderCity'] = $this->_filtro['string']->filter($rider['strRiderCity']);
		$rider['intRiderCountry'] = $this->_filtro['int']->filter($rider['intRiderCountry']);
		$rider['intRiderWeight'] = $this->_filtro['int']->filter($rider['intRiderWeight']);
		$rider['intRiderHeight'] = $this->_filtro['int']->filter($rider['intRiderHeight']);
		return $rider;
	}


}

