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

jimport('joomla.application.component.controller');

class WebmapPlusController extends JController
{
	/**
	 * Method to display the view
	 *
	 * @access	public
	 */
	function display()
	{
		parent::display();
		$params =& JComponentHelper::getParams('com_webmapplus');
		
		$key = $params->get('gmaps_api_key');
		if(empty($key))
			return JError::raiseNotice(500, "Google Maps API Key not set. Click parameters to enter a key.");
	}

}
?>
