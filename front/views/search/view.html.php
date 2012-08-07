<?php
/**
 * @package		WebmapPlus
 * @subpackage	Frontend Helpers
 * @copyright	Copyright (C) 2009 Accade LLC.
 * @license		GNU/GPL, see LICENSE.txt
 * This component is classified as derivative of Joomla! and as such this 
 * version may have been modified pursuant to the GNU General Public License, 
 * and as distributed it includes or is derivative of works licensed under the 
 * GNU General Public License or other free or open source software licenses. 
 */
 
// no direct access
 
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.view');
 
class WebmapPlusViewSearch extends JView
{
    function display($tpl = null)
    {
      // load Joomla's library files
      jimport('joomla.plugin.plugin');
      jimport('joomla.version');
      jimport('joomla.html.parameter');
      
  	  $mainframe = JFactory::getApplication();
  	  $db =& JFactory::getDBO();
	    $params = &JComponentHelper::getParams( 'com_webmapplus' );        
      $model =& $this->getModel();
  		$document	=& JFactory::getDocument();
      $units = $params->get( 'units' ) == 0 ? "Mi" : "Km";
       
      $query = 'SELECT id AS value, name AS text'
			     . ' FROM #__webmapplus_categories';
  		$db->setQuery($query);
  		$categories = (array)$db->loadObjectList();
  		//Handle a No Category Location
  		$blank_array = array();
  		$blank_array['value'] = '0';
  		$blank_array['text'] = 'All Items';
  		array_unshift($categories,  $blank_array);
  		$category	= JHTML::_('select.genericlist',  $categories, "category", 'class="inputbox"', 'value', 'text');
      $this->assignRef( 'category', $category );
      
      $this->assignRef( 'units', $units );
  		$this->assignRef( 'params', $params);
  		
  		$menus	= &JSite::getMenu();
  		$menu	= $menus->getActive();

  		// because the application sets a default page title, we need to get it
  		// right from the menu item itself
  		if (is_object( $menu )) {
  			$menu_params = new JParameter( $menu->params );
  			if (!$menu_params->get( 'page_title')) {
  				$params->set('page_title',	 htmlspecialchars_decode($mainframe->getCfg('sitename' )));
  			}
  		} else {
  			$params->set('page_title',	 htmlspecialchars_decode(JText::_("Location Search")));
  		}
  		$document->setTitle( $params->get( 'page_title' ) );
      parent::display($tpl);
    }
}