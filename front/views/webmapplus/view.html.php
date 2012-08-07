<?php
/**
 * @package		WebmapPlus
 * @subpackage	Frontend Views
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
 
/**
 * HTML View class for the WebmapPlus Component
 *
 * @package    WebmapPlus
 */
 
class WebmapPlusViewWebmapPlus extends JView
{
    function display($tpl = null)
    {
    	JHTML::_('behavior.mootools');
		$params = &JComponentHelper::getParams( 'com_webmapplus' );
        $model =& $this->getModel();
		
        $key = $params->get( 'gmaps_api_key' );
		$units = $params->get( 'units' ) == 0 ? "Mi" : "Km";
		
        $this->assignRef( 'key', $key );
		$this->assignRef( 'params', $params );
        $this->assignRef( 'units', $units );
		
		$db =& JFactory::getDBO();
		
		$query = 'SELECT id AS value, name AS text'
			     . ' FROM #__webmapplus_categories';
		$db->setQuery($query);
		$categories = (array)$db->loadObjectList();
		//Handle a No Category Location
		$blank_array = array();
		$blank_array['value'] = '0';
		$blank_array['text'] = 'All Items';
		array_unshift($categories,  $blank_array);
		$category	= JHTML::_('select.genericlist',  $categories, "category", 'class="inputbox"', 'value', 'text', JRequest::getInt('category', 0));
        $this->assignRef( 'category', $category );
        parent::display($tpl);
    }
    function createDescription(&$location){
      $return = "<b>$location->name</b><br />";
      $return .= '<div style="float: left; width: 200px;">';
      $return .= "$location->address1<br />";
      $return .= !empty($location->address2) ? "$location->address2 <br />":'';
      $return .= "T: $location->phone<br />";
      $return .= !empty($location->f) ? "F: $location->fax<br />" : '';
      $return .= "$location->city, $location->state $location->zip<br />";
      $return .= "<div>";
      $return .= "<ul>";
      foreach($location->attributes as $attribute)
      {
        $return .= "<li>".$attribute->name."</li>";
      }
      $return .= "</ul>";
      $return .= "</div>";
      $return .= "</div>";
      $return .= '<div style="float: left; width: 200px;">';
      $return .= "</div>";
      $return .= '<div style="clear:both"></div>';
      return $return;
    }
}

