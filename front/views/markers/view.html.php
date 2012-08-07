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

ini_set("display_errors", 0);
 
jimport( 'joomla.application.component.view');
 
class WebmapPlusViewMarkers extends JView
{
    function display($tpl = null)
    {        
        $model =& $this->getModel();
		$data = $model->getData();
        $this->assignRef( 'data', $data);
		
        parent::display($tpl);
    }
}
