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
 
class WebmapPlusViewLocation extends JView
{
    function display($tpl = null)
    {
      // load Joomla's library files
      jimport('joomla.plugin.plugin');
      jimport('joomla.version');
      jimport('joomla.html.parameter');
      
    	$mainframe = JFactory::getApplication();
    	JHTML::_('behavior.mootools');
		JHTML::_('behavior.modal');
		$params = &JComponentHelper::getParams( 'com_webmapplus' );        
        $model =& $this->getModel();
		$document	=& JFactory::getDocument();
        
        $data = $model->getData();
        $key = $params->get( 'gmaps_api_key' );
        $this->assignRef( 'key', $key );
        $this->assignRef( 'location', $data);
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
			$params->set('page_title',	 htmlspecialchars_decode($data->name));
		}
		$document->setTitle( $params->get( 'page_title' ) );
		$document->setDescription($this->location->description);
		$document->setMetaData("keywords", $this->location->keywords);
		$document->setMetaData("robots", $this->location->robots);
		$document->setMetaData("author", $this->location->author);
        parent::display($tpl);
    }
    
    function createDescription(&$location){
      $return = "";
      return $return;
    }
    
    function generateAttribute(&$attribute){
      $return = "";
      if(!empty($attribute->value))
      {
        if($attribute->type == 'bool')
          $return = $attribute->name;
        elseif($attribute->type == 'link')
          $return = '<a href="'.$attribute->value.'">'.$attribute->name.'</a>';
        else
          $return = $attribute->name.": ".$attribute->value;
      }
      return $return;
    }
}