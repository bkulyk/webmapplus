<?php
/**
 * @package		WebmapPlus
 * @subpackage	Frontend
 * @copyright	Copyright (C) 2009 Accade LLC.
 * @license		GNU/GPL, see LICENSE.txt
 * This component is classified as derivative of Joomla! and as such this 
 * version may have been modified pursuant to the GNU General Public License, 
 * and as distributed it includes or is derivative of works licensed under the 
 * GNU General Public License or other free or open source software licenses. 
 */
 
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// Require the base controller

 define('WEBMAPPLUS_ASSETS_URL', JURI::base().'components/com_webmapplus/assets/');
 define('WEBMAPPLUS_MEDIA_URL', JURI::base().'media/webmapplus/');
 
require_once( JPATH_COMPONENT.DS.'controller.php' );
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'webmapplus.php' );
 
// Require specific controller if requested
if($controller = JRequest::getWord('controller')) {
    $path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
    if (file_exists($path)) {
        require_once $path;
    } else {
        $controller = '';
    }
}
 
// Create the controller
$classname    = 'WebmapPlusController'.$controller;
$controller   = new $classname( );
 
// Perform the Request task
$controller->execute( JRequest::getVar( 'task' ) );
 
// Redirect if set by the controller
$controller->redirect();