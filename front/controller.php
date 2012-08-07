<?php
/**
 * @package		WebmapPlus
 * @subpackage	Frontend Controller
 * @copyright	Copyright (C) 2009 Accade LLC.
 * @license		GNU/GPL, see LICENSE.txt
 * This component is classified as derivative of Joomla! and as such this 
 * version may have been modified pursuant to the GNU General Public License, 
 * and as distributed it includes or is derivative of works licensed under the 
 * GNU General Public License or other free or open source software licenses. 
 */
 
// No direct access
 
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport('joomla.application.component.controller');
 
class WebmapPlusController extends JController
{
    /**
     * Method to display the view
     *
     * @access    public
     */
    function display()
    {
        parent::display();
    }
	
	function sendEmail()
	{
		// Check for request forgeries
        JRequest::checkToken() or jexit('Invalid Token');

        $post = JRequest::get('post');
		
		$this->addModelPath (JPATH_COMPONENT_ADMINISTRATOR . DS . 'models');
		$model = $this->getModel('location');
		$location = $model->getData();

		$contact_name = $post['contact_name'];
		$contact_email = $post['contact_email'];
		$contact_message = $post['contact_message'];
		if($contact_name == null || $contact_message == null){
			echo JText::_('Please enter a name and message to send.');
			return false;
		}
		else{
			if(false){ return false; }//Captacha check goes here
			else
			{
				JUtility::sendMail($contact_email, $contact_name, $location->email, 'Contact Message for: '.$location->name, $contact_message, 0, null, null, null, $contact_email, $contact_name);
				echo JText::_('Message Sent');
				return true;
			}
		}
		return false;
	}
}