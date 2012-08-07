<?php
/**
 * @package		WebmapPlus
 * @subpackage	Backend Component
 * @copyright	Copyright (C) 2009 Accade LLC.
 * @license		GNU/GPL, see LICENSE.txt
 * This component is classified as derivative of Joomla! and as such this
 * version may have been modified pursuant to the GNU General Public License,
 * and as distributed it includes or is derivative of works licensed under the
 * GNU General Public License or other free or open source software licenses.
 */


// no direct access
defined('_JEXEC') or die('Restricted access');

JSubMenuHelper::addEntry(JText::_('Locations'), 'index.php?option=com_webmapplus');
JSubMenuHelper::addEntry(JText::_('Attributes'), 'index.php?option=com_webmapplus&view=attributes');
JSubMenuHelper::addEntry(JText::_('Markers'), 'index.php?option=com_webmapplus&view=markers');
JSubMenuHelper::addEntry(JText::_('Categories'), 'index.php?option=com_webmapplus&view=categories');
JSubMenuHelper::addEntry(JText::_('Edit CSS'), 'index.php?option=com_webmapplus&view=css');
JSubMenuHelper::addEntry(JText::_('Upload/Download CSV'), 'index.php?option=com_webmapplus&view=csv');

$params =& JComponentHelper::getParams('com_webmapplus');
$view = JRequest::getCmd('view',null);
$db	=& JFactory::getDBO();
define('COM_WEBMAPPLUS_MEDIA_BASE',    	JPATH_ROOT.DS.$params->get($path, 'media'.DS.'webmapplus'));
define('COM_WEBMAPPLUS_MEDIA_BASEURL', 	JURI::root().$params->get($path, 'media/webmapplus'));
define('COM_WEBMAPPLUS_ASSETS_URL', 	JURI::root().'components/com_webmapplus/assets');
define('COM_WEBMAPPLUS_ASSETS_PATH', 	JPATH_ROOT.DS.'components'.DS.'com_webmapplus'.DS.'assets');
define('COM_WEBMAPPLUS_CSS_PATH', 		COM_WEBMAPPLUS_ASSETS_PATH.DS.'css');
define('COM_WEBMAPPLUS_CSV_PATH', JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_webmapplus'.DS.'elements'.DS);
define('COM_WEBMAPPLUS_CSV_URL', JURI::root().'administrator/components/com_webmapplus/elements/');
define('COM_WEBMAPPLUS_MOORAINBOW_URL', JURI::root().'administrator/components/com_webmapplus/assets/rainbowColorPicker/');

// Require the base controller
require_once (JPATH_COMPONENT.DS.'controller.php');
require_once( JPATH_COMPONENT_SITE.DS.'helpers'.DS.'webmapplus.php' );


$task = JRequest::getVar('task');

if($controller = JRequest::getVar('controller')) {
	
	// Define the controller name and path
	$controllerName	= strtolower($controller);
	$controllerPath	= JPATH_COMPONENT.DS.'controllers'.DS.$controllerName.'.php';

	// If the controller file path exists, include it ... else lets die with a 500 error
	if (file_exists($controllerPath)) {
		require_once($controllerPath);
	} else {
		JError::raiseError(500, 'Invalid Controller');
	}
}
else
{
	// Base controller, just set the task :)
	$controllerName = null;
}

// Set the name for the controller and instantiate it
$controllerClass = 'WebmapPlusController'.ucfirst($controllerName);
if (class_exists($controllerClass)) {
	$controller = new $controllerClass();
} else {
	JError::raiseError(500, 'Invalid Controller Class');
}

// Perform the Request task
$controller->execute($task);

// Redirect if set by the controller
$controller->redirect();
?>
