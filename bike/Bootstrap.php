<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

	protected function _initAutoload()
	{
		$moduleLoader = new Zend_Application_Module_Autoloader(array(
	               'namespace' => '', 
	               'basePath' => APPLICATION_PATH));
		
		return $moduleLoader;
	}
	
	
	/**
	 * 
	 * Inicializamos el traductor para usarlo en las vistas
	 */
	protected function _initTraductor(){
		// Recuperamos una instancia de la vista para poder usar el traductor en las vistas.
		$view = Zend_Layout::startMvc()->getView();
		
		// Creamos una instancia del traductor, pero sin asignarle un locale
		$translate = new Zend_Translate('array',
																		APPLICATION_PATH . DIRECTORY_SEPARATOR . 'langs',
																		null,
																		array('scan' => Zend_Translate::LOCALE_DIRECTORY));
		
		// Instanciamos una clase Zend_Locale para establecer el idioma segun la informacion del navegador si lo tenemos, o uno por defecto. 
		$locale = new Zend_Locale();
		$locale->setLocale(Zend_Locale::BROWSER);
		$idiomaNavegador = key($locale->getBrowser());
		
		// Si el idioma del navegador esta en los idiomas de la aplicacion lo asignamos, sino, asignamos uno por defecto
		$idioma = (in_array($idiomaNavegador, $translate->getList())) ? $idiomaNavegador : 'es'; 
		
		// Asignamos el traducto a la vista con el idioma seleccionado.
		$translate->setLocale($idioma);
		$view->translate = $translate;
		
		Zend_Registry::set('Zend_Translate', $translate);
		Zend_Registry::set('Zend_Locale', $locale);
	}
	
	
	
	/**
	 * 
	 * Cargamos los ficheros javascripts y los estilos
	 */
	protected function _initLoadScriptsAndStyles() {
		// Recuperamos la vista
		$view = Zend_Layout::startMvc()->getView();
		
		/**
		 * Añadimos los ficheros javascripts
		 */
		
		// jquery
		$view->headScript()->appendFile('https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js');
		
		// jqueryui
		$view->headScript()->appendFile('https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js');
		
		// Mi fichero de carga
		$view->headScript()->appendFile('/resources/jscripts/bikes.js');
		
		/**
		 * Añadimos los estilos css
		 */
		
		// Mis estilos
		$view->headLink()->appendStylesheet('/resources/styles/reset.css');
		$view->headLink()->appendStylesheet('/resources/styles/bikes.css');
		$view->headLink()->appendStylesheet('/resources/styles/forms.css');
		
		// jqueryiu
		$view->headLink()->appendStylesheet('/resources/styles/jquery-ui-1.8.18.custom.css');
	}
	
	
	
	
	/**
	 * 
	 * Carga de plugins
	 * 
	 * Para que los cargue automáticamente, simplemente quitar la etiquieta QUITAR y añadir los que se quieran cargar
	 * 
	 */
	protected function QUITAR_initPluginload(){
		$loader = new Zend_Loader_PluginLoader();
		$loader->addPrefixPath('', APPLICATION_PATH . '/plugins');
		$loader->load('NOMBRE_FICHERO_SIN_EXTENSION');
		$front = Zend_Controller_Front::getInstance();
		$front->registerPlugin(new NOMBRE_CLASE());
	}
	
	
}

