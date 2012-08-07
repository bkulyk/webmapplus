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

class WebmapPlusViewCategory extends JView
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
		$category	=& $this->get('Data');
		$lists 	= array();		
		$isNew	= ($category->id < 1);

		// fail if checked out not by 'me'
		if ($model->isCheckedOut( $user->get('id') )) {
			
			$msg = JText::sprintf( 'DESCBEINGEDITTED', JText::_( 'Category' ), $category->title );
			$mainframe->redirect( 'index.php?option='. $option, $msg );
		}

		// Set toolbar items for the page
		$text = $isNew ? JText::_( 'New' ) : JText::_( 'Edit' );
		JToolBarHelper::title(   JText::_( 'Category' ).': <small><small>[ ' . $text.' ]</small></small>' );
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
			$category->published 		= 1;
			$category->order 			= 0;
		}

		$categories	= &$this->get('AllCategories');
		$blank_array = array();
		$blank_array['value'] = '0';
		$blank_array['text'] = 'No Parent';
		array_unshift($categories,  $blank_array);
		$lists['parent'] = JHTML::_('select.genericlist',  $categories, 'parent_id', 'class="inputbox" size="1"', 'value', 'text', $category->parent_id );
		
		// build the html select list for ordering
		$query = 'SELECT ordering AS value, name AS text'
			. ' FROM #__webmapplus_categories'
			. ' ORDER BY ordering';
		$lists['ordering'] = JHTML::_('list.specificordering',  $category, $category->id, $query, false );
		// build the html select list for published
		$lists['published'] = JHTML::_('select.booleanlist',  'published', 'class="inputbox"', $category->published );
		//Marker Types
		$query = 'SELECT id AS value, name AS text'
			     . ' FROM #__webmapplus_markerTypes';
		$db->setQuery($query);
		$markers = $db->loadObjectList();
		$blank_array = array();
		$blank_array['value'] = '0';
		$blank_array['text'] = 'System Default';
		array_unshift($markers,  $blank_array);
		$lists['markerIcons'] 	= JHTML::_('select.genericlist',  $markers, "markerType", 'class="inputbox"', 'value', 'text',  $category->markerType);
	
		$this->assignRef('lists', $lists);
		$this->assignRef('category', $category);
	
		$this->assignRef('request_url',	$uri->toString());

		parent::display($tpl);
	}
}
?>
