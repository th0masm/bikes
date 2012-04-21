<?php

class Application_Model_DbTable_Results extends Zend_Db_Table_Abstract
{


  /**
  *
  * Nombre de la tabla para los resultados
  * @var string
  */
  protected $_name = 'tblResults';
  
  /**
   *
   * Nombre de la clave primaria en la tabla tblResults
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
  
  
  
  
  



}

