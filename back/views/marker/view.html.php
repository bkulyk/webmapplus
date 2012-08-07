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

class WebmapPlusViewMarker extends JView
{

	function display($tpl = null)
	{
		$mainframe = JFactory::getApplication();
		
		if($this->getLayout() == 'form') {
			$this->_displayForm($tpl);
			return;
		}elseif($this->getLayout() == 'wizard'){
			$marker	=& $this->get('Data');
			$this->assignRef('marker', $marker);
			JToolBarHelper::title(   JText::_( 'Marker Wizard' ).': <small><small>['.JText::_( 'Upload' ).']</small></small>' );
			JToolBarHelper::save("wizardStep1", "Upload");
			JToolBarHelper::cancel();	
		}elseif($this->getLayout() == 'wizard2'){
			$this->_displayWizard2($tpl);
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
		$marker	=& $this->get('Data');

		$lists 	= array();		
		$isNew	= ($marker->id < 1);

		// fail if checked out not by 'me'
		if ($model->isCheckedOut( $user->get('id') )) {
			
			$msg = JText::sprintf( 'DESCBEINGEDITTED', JText::_( 'Marker' ), $attribute->title );
			$mainframe->redirect( 'index.php?option='. $option, $msg );
		}

		// Set toolbar items for the page
		$text = $isNew ? JText::_( 'New' ) : JText::_( 'Edit' );
		JToolBarHelper::title(   JText::_( 'Marker' ).': <small><small>[ ' . $text.' ]</small></small>' );
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
		
	
		$this->assignRef('marker', $marker);
		$this->assignRef('lists', $lists);
		
		$anchorPoint = array(array('value' =>'1', 'text' => 'Left'), array('value' => '2', 'text' => 'Center'), array('value' => '3', 'text' => 'Right'));
		$lists['anchorPoint'] = JHTML::_('select.genericlist',  $anchorPoint, "anchorPoint", 'class="inputbox"', 'value', 'text',  $this->marker->anchorPoint);
	
		$this->assignRef('request_url',	$uri->toString());

		parent::display($tpl);
	}
	
	function _displayWizard2($tpl){
		JToolBarHelper::title(   JText::_( 'Marker Wizard' ).': <small><small>['.JText::_( 'Preview' ).']</small></small>' );
		JToolBarHelper::save("wizardSave");
		JToolBarHelper::cancel();
		list( $mainframe, $option ) = array( JFactory::getApplication(), JRequest::getCmd('option') );
		$session = &JFactory::getSession();
		$tmpMarker = $session->get("tmpMarker");
		$basefile = JURI::root().'tmp/'.$tmpMarker['filename'];
		$params = &JComponentHelper::getParams( 'com_webmapplus' );
		
        $key = $params->get( 'gmaps_api_key' );
        $this->assignRef( 'key', $key );
		$this->assignRef( 'params', $params );
		$this->assignRef('baseFile', $basefile);
		$this->assignRef('name', $tmpMarker['name']);
		$this->assignRef('filename', $tmpMarker['filename']);
		$this->assignRef('imageMap', $tmpMarker['imageMap']);
		$this->assignRef('iconSize', $tmpMarker['iconSize']);
		$this->assignRef('shadowSize', $tmpMarker['shadowSize']);
		$this->assignRef('iconAnchor', $tmpMarker['iconAnchor']);
		$this->assignRef('infoWindowAnchor', $tmpMarker['infoWindowAnchor']);
		
		parent::display($tpl);	
	}
}
?>
