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

class WebmapPlusControllerCSV extends WebmapPlusController
{
    function __construct($config = array ())
    {
        parent::__construct($config);
    }

    function save()
    {
    	$mainframe = JFactory::getApplication();
		
        // Check for request forgeries
        JRequest::checkToken() or jexit('Invalid Token');
		$post = JRequest::get('post');
		
        $file = JRequest::getVar('csv_file', '', 'files', 'array');
        $folder = JRequest::getVar('folder', '', '', 'path');
        $format = JRequest::getVar('format', 'html', '', 'cmd');
        $return = JRequest::getVar('return-url', null, 'post', 'base64');
        $err = null;

        // Set FTP credentials, if given
        jimport('joomla.client.helper');
        JClientHelper::setCredentialsFromRequest('ftp');

        // Make the filename safe
        jimport('joomla.filesystem.file');
        $file['name'] = JFile::makeSafe($file['name']);
		
		//Open the File in Read mode
		$handle = fopen($file['tmp_name'], "r");
		
		//Grab the first row and flip the array returned so that the value become the key and key becomes value
		//We now have an array of the file headers looking like Array ( [Name] => 0 [Address1] => 1 ...etc
		$file_headers = array_flip(fgetcsv($handle));
		
		//While we are able to return data from the file parse the file and store the data
        while (($data = fgetcsv($handle)) !== FALSE)
        {
			$model_data = array();
			//Create an array to store data for the location
			$model_data['name'] = $data[$file_headers['Name']];
			$model_data['address1'] = $data[$file_headers['Address1']];
			$model_data['address2'] = $data[$file_headers['Address2']];
			$model_data['city'] = $data[$file_headers['City']];
			$model_data['state'] = $data[$file_headers['State']];
			$model_data['zip'] = $data[$file_headers['Zip_Postal_Code']];
			$model_data['email'] = $data[$file_headers['Email']];
			$model_data['country_code'] = $data[$file_headers['ISO_Country_Code']];
			$model_data['published'] = $data[$file_headers['Published']];
			$point = WebmapPlusHelper::GeoCode("".$model_data['address1']." ".$model_data['address2'].", ".$model_data['city'].", ".$model_data['state']." ".$model_data['zip'].", ".$model_data['country_code']);
            $model_data['lat'] = $point[1];
            $model_data['long'] = $point[0];
            $location = $this->getModel('location');
            if ($location->store($model_data))
            {
                $msg = JText::_('Location Saved');
            } else
            {
                $msg = JText::_('Error Saving Location: '.$model_data['name'].' Error: '.$location->getError()); break;
            }
			//Reset array and store attributes
			$model_data = array();
			
			$csv = $this->getModel('csv');
			$attributes = $csv->getAllAttributes();
			foreach($attributes as $attrib)
            {
                $model_data['value'] = $data[$file_headers[$attrib->name]];
                $model_data['id'] = $attrib->id;
                if (!$location->addAttribute($model_data['id'], $model_data['value']))
                {
                    $msg = JText::_('Error Saving Location: '.$model_data['name']); break;
                }
			}
        }
		fclose($handle);
		
        $link = 'index.php?option=com_webmapplus';
        $this->setRedirect($link, $msg);
    }
	
	function generateSampleCSV()
	{
		$model = $this->getModel('csv');
        if ($model->isCheckedOut())
        {
            $this->setRedirect('index.php?option=com_webmapplus');
            return JError::raiseWarning(500, JText::sprintf('DESCBEINGEDITTED', JText::_('The module'), $row->title));
        }
        JRequest::setVar('hidemainmenu', 1);
        JRequest::setVar('layout', 'sample');
        JRequest::setVar('view', 'csv');

        parent::display();

        $model->checkout();
	}
	
	    function cancel()
    {
        // Check for request forgeries
        JRequest::checkToken() or jexit('Invalid Token');
		$model = $this->getModel('csv');
        $this->setRedirect('index.php?option=com_webmapplus');
    }

}
?>
