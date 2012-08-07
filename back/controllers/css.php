<?php
/**
 * @package		WebmapPlus
 * @subpackage	Backend Controllers
 * @copyright	Copyright (C) 2009 Accade LLC.
 * @license		GNU/GPL, see LICENSE.txt
 * This component is classified as derivative of Joomla! and as such this
 * version may have been modified pursuant to the GNU General Public License,
 * and as distributed it includes or is derivative of works licensed under the
 * GNU General Public License or other free or open source software licenses.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die ('Restricted access');

jimport('joomla.application.component.controller');

class WebmapPlusControllerCSS extends WebmapPlusController
{
    function __construct($config = array ())
    {
        parent::__construct($config);
    }

    function save()
    {
        // Check for request forgeries
        JRequest::checkToken() or jexit('Invalid Token');

        $model = $this->getModel('css');

        if ($model->store())
        {
            $msg = JText::_('CSS Saved');
        } else
        {
            $msg = JText::_('Error Saving CSS');
        }
		
        $link = 'index.php?option=com_webmapplus';
        $this->setRedirect($link, $msg);
    }
	
	function apply()
    {
        // Check for request forgeries
        JRequest::checkToken() or jexit('Invalid Token');

        $model = $this->getModel('css');

        if ($model->store())
        {
            $msg = JText::_('CSS Saved');
        } else
        {
            $msg = JText::_('Error Saving CSS');
        }
		
        $link = 'index.php?option=com_webmapplus&view=css';
        $this->setRedirect($link, $msg);
    }

    function cancel()
    {
        // Check for request forgeries
        JRequest::checkToken() or jexit('Invalid Token');
		$model = $this->getModel('css');
        $this->setRedirect('index.php?option=com_webmapplus');
    }

}
?>
