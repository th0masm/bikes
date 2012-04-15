<?php
/*
 * Estas lineas se usan para los 'temas'
 * 
 * $this->_helper->layout()->setLayoutPath( APPLICATION_PATH . DIRECTORY_SEPARATOR . 'layouts/scripts/TEMA/'); // TEMA directorio del tema.
 * $this->_helper->layout()->setLayout('LAYOUT'); // layout.phtml dentro de el path de arriba
 * 
 * 
 * */

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/bike'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../libs'),
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/bikes.ini'
);

$application->bootstrap()
            ->run();

