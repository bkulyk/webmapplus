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

class WebmapPlusViewAttribute extends JView
{

	function display($tpl = null)
	{
		$mainframe = JFactory::getApplication();
		
		if($this->getLayout() == 'form') {
			$this->_displayForm($tpl);
			return;
		}
		
		parent::display($tpl);
	}

	function _displayForm($tpl)
	{
		list( $mainframe, $option ) = array( JFactory::getApplication(), JRequest::getCmd('option') );

		$db		=& JFactory::getDBO();
		$uri 	=& JFactory::getURI();
		$user 	=& JFactory::getUser();
		$model	=& $this->getModel();
		$editor =& JFactory::getEditor();	
		//Data from model
		$attribute	=& $this->get('Data');

		$lists 	= array();		
		$isNew	= ($attribute->id < 1);

		// fail if checked out not by 'me'
		if ($model->isCheckedOut( $user->get('id') )) {
			
			$msg = JText::sprintf( 'DESCBEINGEDITTED', JText::_( 'Attribute' ), $attribute->title );
			$mainframe->redirect( 'index.php?option='. $option, $msg );
		}

		// Set toolbar items for the page
		$text = $isNew ? JText::_( 'New' ) : JText::_( 'Edit' );
		JToolBarHelper::title(   JText::_( 'Attribute' ).': <small><small>[ ' . $text.' ]</small></small>' );
		JToolBarHelper::save();
		JToolBarHelper::apply();
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}
		JToolBarHelper::help( 'screen.multimaps', true );

		// Edit or Create?
		if (!$isNew)
		{
			$model->checkout( $user->get('id') );
		}
		else
		{
			// initialise new record
			$attribute->published 		= 1;
			$attribute->order 			= 0;
		}

		
		
		// build the html select list for ordering
		$query = 'SELECT ordering AS value, name AS text'
			. ' FROM #__webmapplus_attributes'
			. ' ORDER BY ordering';
		$lists['ordering'] = JHTML::_('list.specificordering',  $attribute, $attribute->id, $query, false );
		// build the html select list
		$lists['published'] = JHTML::_('select.booleanlist',  'published', 'class="inputbox"', $attribute->published );
		$lists['displayListing'] = JHTML::_('select.booleanlist',  'displayListing', 'class="inputbox"', $attribute->displayListing );
		$lists['displayBHover'] = JHTML::_('select.booleanlist',  'displayBHover', 'class="inputbox"', $attribute->displayBHover );
		$lists['displayBClick'] = JHTML::_('select.booleanlist',  'displayBClick', 'class="inputbox"', $attribute->displayBClick );
		$lists['displayPage'] = JHTML::_('select.booleanlist',  'displayPage', 'class="inputbox"', $attribute->displayPage );
		
		$types = array(
			array("text" => JText::_("Yes / No"), "value" => "bool"),
			array("text" => JText::_("Text"), "value" => "text"),
			array("text" => JText::_("Dropdown"), "value" => "enum"),
			array("text" => JText::_("Telephone Number"), "value" => "telephone"),
			array("text" => JText::_("Text Area"), "value" => "textarea"),
			array("text" => JText::_("HTML Link"), "value" => "link"),
			);
		$lists['types'] = JHTML::_('Select.genericlist', $types, "type", 'onChange="if(this.value != \'enum\'){$(\'values-row\').setStyle(\'display\', \'none\')}else{$(\'values-row\').setStyle(\'display\', \'\')}"', "value", "text", $attribute->type);
	
		$this->assignRef('lists', $lists);
		$this->assignRef('attribute', $attribute);
	
		$this->assignRef('request_url',	$uri->toString());

		parent::display($tpl);
	}
}
?>
