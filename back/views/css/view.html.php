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

// No direct access
defined('_JEXEC') or die ('Restricted access');

jimport('joomla.application.component.view');

class WebmapPlusViewCSS extends JView
{
    /**
     * Display the CSS file
     * @return void
     **/
    function display($tpl = null)
    {

        JToolBarHelper::title(JText::_('CSS').': <small><small>[ Edit ]</small></small>');
        JToolBarHelper::save();
        JToolBarHelper::apply();
        if ( isset ($isNew) && $isNew)
        {
            JToolBarHelper::cancel();
        } else
        {
            // for existing items the button is renamed `close`
            JToolBarHelper::cancel('cancel', 'Close');
        }
		JToolBarHelper::preferences('com_webmapplus', 450);

        parent::display($tpl);
    }
}


?>
