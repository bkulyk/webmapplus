<?php
/**
 * @package		WebmapPlus
 * @subpackage	Backend Views
 * @copyright	Copyright (C) 2009 Accade LLC.
 * @license		GNU/GPL, see LICENSE.txt
 * This component is classified as derivative of Joomla! and as such this
 * version may have been modified pursuant to the GNU General Public License,
 * and as distributed it includes or is derivative of works licensed under the
 * GNU General Public License or other free or open source software licenses.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

class WebmapPlusViewCSV extends JView
{
    function display($tpl = null)
    {
        list( $mainframe, $option ) = array( JFactory::getApplication(), JRequest::getCmd('option') );

        $db = & JFactory::getDBO();
        $uri = & JFactory::getURI();

        JToolBarHelper::title(JText::_('CSV'), 'generic.png');
        JToolBarHelper::save();
		    JToolBarHelper::cancel('cancel', 'Close');
        JToolBarHelper::preferences('com_webmapplus', 450);

        $model = & $this->getModel();
        $data = $model->getData();
        $this->assignRef('data', $data);
        $allAttribs = $model->getAllAttributes();
        $this->assignRef('allAttribs', $allAttribs);
        parent::display($tpl);
    }
	
	function _displaySampleCSV($tpl = null)
    {
        list( $mainframe, $option ) = array( JFactory::getApplication(), JRequest::getCmd('option') );

        $db = & JFactory::getDBO();
        $uri = & JFactory::getURI();

        JToolBarHelper::title(JText::_('CSV'), 'generic.png');
        JToolBarHelper::preferences('com_webmapplus', 450);

        $model = & $this->getModel();
        $data = $model->getData();
        $this->assignRef('data', $data);
        $allAttribs = $model->getAllAttributes();
        $this->assignRef('allAttribs', $allAttribs);
        parent::display($tpl);
    }
}
