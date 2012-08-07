<?php
/**
 * @version		$Id: controller.php 10094 2008-03-02 04:35:10Z instance $
 * @package		Joomla
 * @subpackage	Content
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class WebmapPlusControllerLocation extends WebmapPlusController {
    function __construct($config = array()) {
        parent::__construct($config);
        $db = &JFactory::getDBO();
    }
    
    function add() {
        JRequest::setVar('hidemainmenu', 1);
        JRequest::setVar('layout', 'form');
        JRequest::setVar('view', 'location');
        JRequest::setVar('edit', false);
        
        parent::display();
        
        $model = $this->getModel('location');
        $model->checkout();
    
    }
    
    function edit() {
        $model = $this->getModel('location');
        if ($model->isCheckedOut()) {
            $this->setRedirect('index.php?option=com_webmapplus');
            return JError::raiseWarning(500, JText::sprintf('DESCBEINGEDITTED', JText::_('The module'), $row->title));
        }
        JRequest::setVar('hidemainmenu', 1);
        JRequest::setVar('layout', 'form');
        JRequest::setVar('view', 'location');
        JRequest::setVar('edit', true);
        
        parent::display();
        
        $model->checkout();
    
    }
    
    function apply() {
        // Check for request forgeries
        JRequest::checkToken() or jexit('Invalid Token');
        
        $post = JRequest::get('post', JREQUEST_ALLOWHTML);
        $cid = JRequest::getVar('cid', array(0), 'post', 'array');
        $lat = JRequest::getVar('lat', 0, 'post', 'float');
        $long = JRequest::getVar('long', 0, 'post', 'float');
        $address1 = JRequest::getVar('address1', '', 'post', 'string');
        $address2 = JRequest::getVar('address2', '', 'post', 'string');
        $city = JRequest::getVar('city', '', 'post', 'string');
        $state = JRequest::getVar('state', '', 'post', 'string');
        $zip = JRequest::getVar('zip', '', 'post', 'string');
        $country = JRequest::getVar('country_code', '', 'post', 'string');
        
        $post['id'] = (int) $cid[0];
        
        $model = $this->getModel('location');
        $model->setId($post['id']);
        $location = $model->getData();
        
        if ( empty($lat) || empty($long) || $location->address1 != $address1 || $location->address2 != $address2 || $location->city != $city || $location->state != $state || $location->zip != $zip || $location->country != $country) {
            
            $point = WebmapPlusHelper::GeoCode("$address1 $address2, $city, $state $zip, $country");
            if ($point == "400") {
                $this->setError(JText::_('Geocoding Failed: Bad Request'));
            } else if ($point == "500") {
                $this->setError(JText::_('Geocoding Failed: Server Error - Try again'));
            } else if ($point == "601") {
                $this->setError(JText::_('Geocoding Failed: Empty Address Request - Remeber to include an address for the location'));
            } else if ($point == "602") {
                $this->setError(JText::_('Geocoding Failed: Location Not Found'));
            } else if ($point == "603") {
                $this->setError(JText::_('Geocoding Failed: Google is unable to geocode this address due to legal concerns'));
            } else if ($point == "604") {
                $this->setError(JText::_('Geocoding Failed: Unkown Error'));
            } else if ($point == "610") {
                $this->setError(JText::_('Geocoding Failed: Google Maps Key is either not set or incorrect'));
            } else if ($point == "620") {
                $this->setError(JText::_('Geocoding Failed: Too many requests'));
            } else if ($point == "") {
                $this->setError(JText::_('Geocoding Failed: Empty Address Request - Remeber to include an address for the location'));
            } else {
                if ( empty($lat))
                    $post['lat'] = $point[1];
                
                if ( empty($long))
                    $post['long'] = $point[0];
            }
        }
        
        $file = JRequest::getVar('photo_path', '', 'files', 'array');
        jimport('joomla.filesystem.file');
        $file['name'] = JFile::makeSafe($file['name']);
        
        if (isset($file['name']) && ! empty($file['name']) && isset($location->photo_path) && ! empty($location->photo_path) && is_file(COM_WEBMAPPLUS_MEDIA_BASE.DS.$location->photo_path) && (strtolower($file['name']) == $location->photo_path)) {
            unlink(COM_WEBMAPPLUS_MEDIA_BASE.DS.$location->photo_path);
        }
        
        $post['photo_path'] = $this->uploadImage();
        
        if ($model->store($post)) {
            $msg = JText::_('Location Saved');
        } else {
            $msg = $model->getError().JText::_(' - Error Saving Location');
        }
        
        foreach ($post as $key=>$value) {
            if (substr($key, 0, 5) == 'attr_') {
                $var = explode('_', $key);
                if (isset($var[2]) && ! empty($var[2])) {
                    if (!$model->updateAttribute($var[2], $value)) {
                        $msg = JText::_('Error Saving Location');
                        break;
                    }
                } else {
                    if (!$model->addAttribute($var[1], $value)) {
                        $msg = JText::_('Error Saving Location');
                        break;
                    }
                }
            }
        }
        
        $model->checkin();
        $link = 'index.php?option=com_webmapplus&controller=location&task=edit&cid[]='.$post['id'];
        $this->setRedirect($link, $this->getError().$msg);
    }
    
    function save() {
        // Check for request forgeries
        JRequest::checkToken() or jexit('Invalid Token');
        
        $post = JRequest::get('post', JREQUEST_ALLOWHTML);
        $cid = JRequest::getVar('cid', array(0), 'post', 'array');
        $lat = JRequest::getVar('lat', 0, 'post', 'float');
        $long = JRequest::getVar('long', 0, 'post', 'float');
        $address1 = JRequest::getVar('address1', '', 'post', 'string');
        $address2 = JRequest::getVar('address2', '', 'post', 'string');
        $city = JRequest::getVar('city', '', 'post', 'string');
        $state = JRequest::getVar('state', '', 'post', 'string');
        $zip = JRequest::getVar('zip', '', 'post', 'string');
        $country = JRequest::getVar('country_code', '', 'post', 'string');
        
        $post['id'] = (int) $cid[0];
        
        $model = $this->getModel('location');
        $model->setId($post['id']);
        $location = $model->getData();
        
        if ( empty($lat) || empty($long) || $location->address1 != $address1 || $location->address2 != $address2 || $location->city != $city || $location->state != $state || $location->zip != $zip || $location->country != $country) {
            
            $point = WebmapPlusHelper::GeoCode("$address1 $address2, $city, $state $zip, $country");
            if ($point == "400") {
                $this->setError(JText::_('Geocoding Failed: Bad Request'));
                $model->checkin();
                $link = 'index.php?option=com_webmapplus&controller=location&task=edit&cid[]='.$post['id'];
                $this->setRedirect($link, $this->getError().$msg);
                $this->redirect();
            } else if ($point == "500") {
                $this->setError(JText::_('Geocoding Failed: Server Error - Try again'));
                $model->checkin();
                $link = 'index.php?option=com_webmapplus&controller=location&task=edit&cid[]='.$post['id'];
                $this->setRedirect($link, $this->getError().$msg);
                $this->redirect();
            } else if ($point == "601") {
                $this->setError(JText::_('Geocoding Failed: Empty Address Request - Remeber to include an address for the location'));
                $model->checkin();
                $link = 'index.php?option=com_webmapplus&controller=location&task=edit&cid[]='.$post['id'];
                $this->setRedirect($link, $this->getError().$msg);
                $this->redirect();
            } else if ($point == "602") {
                $this->setError(JText::_('Geocoding Failed: Location Not Found'));
                $model->checkin();
                $link = 'index.php?option=com_webmapplus&controller=location&task=edit&cid[]='.$post['id'];
                $this->setRedirect($link, $this->getError().$msg);
                $this->redirect();
            } else if ($point == "603") {
                $this->setError(JText::_('Geocoding Failed: Google is unable to geocode this address due to legal concerns'));
                $model->checkin();
                $link = 'index.php?option=com_webmapplus&controller=location&task=edit&cid[]='.$post['id'];
                $this->setRedirect($link, $this->getError().$msg);
                $this->redirect();
            } else if ($point == "604") {
                $this->setError(JText::_('Geocoding Failed: Unkown Error'));
                $model->checkin();
                $link = 'index.php?option=com_webmapplus&controller=location&task=edit&cid[]='.$post['id'];
                $this->setRedirect($link, $this->getError().$msg);
                $this->redirect();
            } else if ($point == "610") {
                $this->setError(JText::_('Geocoding Failed: Google Maps Key is either not set or incorrect'));
                $model->checkin();
                $link = 'index.php?option=com_webmapplus&controller=location&task=edit&cid[]='.$post['id'];
                $this->setRedirect($link, $this->getError().$msg);
                $this->redirect();
            } else if ($point == "620") {
                $this->setError(JText::_('Geocoding Failed: Too many requests'));
            } else if ($point == "") {
                $this->setError(JText::_('Geocoding Failed: Empty Address Request - Remeber to include an address for the location'));
                $model->checkin();
                $link = 'index.php?option=com_webmapplus&controller=location&task=edit&cid[]='.$post['id'];
                $this->setRedirect($link, $this->getError().$msg);
                $this->redirect();
            } else {
                if ( empty($lat))
                    $post['lat'] = $point[1];
                
                if ( empty($long))
                    $post['long'] = $point[0];
            }
        }
        
        $file = JRequest::getVar('photo_path', '', 'files', 'array');
        jimport('joomla.filesystem.file');
        $file['name'] = JFile::makeSafe($file['name']);
        
        if (isset($file['name']) && ! empty($file['name']) && isset($location->photo_path) && ! empty($location->photo_path) && is_file(COM_WEBMAPPLUS_MEDIA_BASE.DS.$location->photo_path) && (strtolower($file['name']) == $location->photo_path)) {
            unlink(COM_WEBMAPPLUS_MEDIA_BASE.DS.$location->photo_path);
        }
        
        $post['photo_path'] = $this->uploadImage();
        
        if ($model->store($post)) {
            $msg = JText::_('Location Saved');
        } else {
            $msg = $model->getError().JText::_(' - Error Saving Location');
            $model->checkin();
            $link = 'index.php?option=com_webmapplus&controller=location&task=edit&cid[]='.$post['id'];
            $this->setRedirect($link, $this->getError().$msg);
            $this->redirect();
        }
        
        foreach ($post as $key=>$value) {
            if (substr($key, 0, 5) == 'attr_') {
                $var = explode('_', $key);
                if (isset($var[2]) && ! empty($var[2])) {
                    if (!$model->updateAttribute($var[2], $value)) {
                        $msg = JText::_('Error Saving Location');
                        break;
                    }
                } else {
                    if (!$model->addAttribute($var[1], $value)) {
                        $msg = JText::_('Error Saving Location');
                        $model->checkin();
                        $link = 'index.php?option=com_webmapplus&controller=location&task=edit&cid[]='.$post['id'];
                        $this->setRedirect($link, $this->getError().$msg);
                        $this->redirect();
                        break;
                    }
                }
            }
        }
        
        $model->checkin();
        $link = 'index.php?option=com_webmapplus&view=webmapplus';
        $this->setRedirect($link, $this->getError().$msg);
    }
    
    function remove() {
        // Check for request forgeries
        JRequest::checkToken() or jexit('Invalid Token');
        
        $cid = JRequest::getVar('cid', array(), 'post', 'array');
        JArrayHelper::toInteger($cid);
        
        if (count($cid) < 1) {
            JError::raiseError(500, JText::_('Select an item to delete'));
        }
        
        $model = $this->getModel('location');
        if (!$model->delete($cid)) {
            echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
        }
        
        $this->setRedirect('index.php?option=com_webmapplus&view=webmapplus');
    }

    
    function publish() {
        // Check for request forgeries
        JRequest::checkToken() or jexit('Invalid Token');
        
        $cid = JRequest::getVar('cid', array(), 'post', 'array');
        JArrayHelper::toInteger($cid);
        
        if (count($cid) < 1) {
            JError::raiseError(500, JText::_('Select an item to publish'));
        }
        
        $model = $this->getModel('location');
        if (!$model->publish($cid, 1)) {
            echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
        }
        
        $this->setRedirect('index.php?option=com_webmapplus&view=webmapplus');
    }

    
    function unpublish() {
        // Check for request forgeries
        JRequest::checkToken() or jexit('Invalid Token');
        
        $cid = JRequest::getVar('cid', array(), 'post', 'array');
        JArrayHelper::toInteger($cid);
        if (count($cid) < 1) {
            JError::raiseError(500, JText::_('Select a location to unpublish'));
        }
        
        $model = $this->getModel('location');
        if (!$model->publish($cid, 0)) {
            echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
        }
        
        $this->setRedirect('index.php?option=com_webmapplus&view=webmapplus');
    }
    
    function cancel() {
        // Check for request forgeries
        JRequest::checkToken() or jexit('Invalid Token');
        $model = $this->getModel('location');
        $model->checkin();
        $this->setRedirect('index.php?option=com_webmapplus&view=webmapplus');
    }

    
    function orderup() {
        // Check for request forgeries
        JRequest::checkToken() or jexit('Invalid Token');
        
        $model = $this->getModel('location');
        $model->move(-1);
        
        $this->setRedirect('index.php?option=com_webmapplus&view=webmapplus');
    }
    
    function orderdown() {
        // Check for request forgeries
        JRequest::checkToken() or jexit('Invalid Token');
        
        $model = $this->getModel('location');
        $model->move(1);
        
        $this->setRedirect('index.php?option=com_webmapplus&view=webmapplus');
    }
    
    function saveorder() {
        // Check for request forgeries
        JRequest::checkToken() or jexit('Invalid Token');
        
        $cid = JRequest::getVar('cid', array(), 'post', 'array');
        $order = JRequest::getVar('order', array(), 'post', 'array');
        JArrayHelper::toInteger($cid);
        JArrayHelper::toInteger($order);
        
        $model = $this->getModel('location');
        $model->saveorder($cid, $order);
        
        $msg = 'New ordering saved';
        $this->setRedirect('index.php?option=com_webmapplus&view=webmapplus', $msg);
    }
    
    function uploadImage() {
        $mainframe = JFactory::getApplication();
        
        // Check for request forgeries
        JRequest::checkToken('request') or jexit('Invalid Token');
        
        $file = JRequest::getVar('photo_path', '', 'files', 'array');
        $folder = JRequest::getVar('folder', '', '', 'path');
        $format = JRequest::getVar('format', 'html', '', 'cmd');
        $return = JRequest::getVar('return-url', null, 'post', 'base64');
        $err = null;
        
        // Set FTP credentials, if given
        jimport('joomla.client.helper');
        JClientHelper::setCredentialsFromRequest('ftp');
        
        require_once (JPATH_COMPONENT.DS.'helpers'.DS.'media.php');
        
        // Make the filename safe
        jimport('joomla.filesystem.file');
        $file['name'] = JFile::makeSafe($file['name']);
        
        if (isset($file['name'])) {
            $filepath = JPath::clean(COM_WEBMAPPLUS_MEDIA_BASE.DS.$folder.DS.strtolower($file['name']));
            if (!MediaHelper::canUpload($file, $err)) {
                if ($format == 'json') {
                    jimport('joomla.error.log');
                    $log = &JLog::getInstance('upload.error.php');
                    $log->addEntry(array('comment'=>'Invalid: '.$filepath.': '.$err));
                    header('HTTP/1.0 415 Unsupported Media Type');
                    jexit('Error. Unsupported Media Type!');
                } else {
                    JError::raiseNotice(100, JText::_($err));
                    // REDIRECT
                    if ($return) {
                        $mainframe->redirect(base64_decode($return).'&folder='.$folder);
                    }
                    return;
                }
            }
            
            if (JFile::exists($filepath)) {
                if ($format == 'json') {
                    jimport('joomla.error.log');
                    $log = &JLog::getInstance('upload.error.php');
                    $log->addEntry(array('comment'=>'File already exists: '.$filepath));
                    header('HTTP/1.0 409 Conflict');
                    jexit('Error. File already exists');
                } else {
                    JError::raiseNotice(100, JText::_('Error. File already exists'));
                    // REDIRECT
                    if ($return) {
                        $mainframe->redirect(base64_decode($return).'&folder='.$folder);
                    }
                    return;
                }
            }
            
            if (!JFile::upload($file['tmp_name'], $filepath)) {
                if ($format == 'json') {
                    jimport('joomla.error.log');
                    $log = &JLog::getInstance('upload.error.php');
                    $log->addEntry(array('comment'=>'Cannot upload: '.$filepath));
                    header('HTTP/1.0 400 Bad Request');
                    jexit('Error. Unable to upload file');
                } else {
                    JError::raiseWarning(100, JText::_('Error. Unable to upload file'));
                    // REDIRECT
                    if ($return) {
                        $mainframe->redirect(base64_decode($return).'&folder='.$folder);
                    }
                    return;
                }
            } else {
                if ($format == 'json') {
                    jimport('joomla.error.log');
                    $log = &JLog::getInstance();
                    $log->addEntry(array('comment'=>$folder));
                    jexit('Upload complete');
                } else {
                    $mainframe->enqueueMessage(JText::_('Upload complete'));
                    // REDIRECT
                    if ($return) {
                        $mainframe->redirect(base64_decode($return).'&folder='.$folder);
                    }
                    $params = &JComponentHelper::getParams('com_webmapplus');
                    $height = $params->get('picture_height');
                    $width = $params->get('picture_width');
                    MediaHelper::createthumb($filepath, $filepath, $width, $height);
                    $filepath = str_replace(JPATH_ROOT, "", $filepath);
                    $file_information = pathinfo($filepath);
                    
                    return $file_information['basename'];
                }
            }
        } else {
            $mainframe->redirect('index.php', 'Invalid Request', 'error');
        }
    }
    
    function sendEmail() {
        $website = trim($_POST['website']);
        $contact_name = trim($_POST['contact_name']);
        $contact_location_email = trim($_POST['contact_location_email']);
        $contact_email = trim($_POST['contact_email']);
        $contact_message = trim($_POST['contact_message']);
        $contact_location = trim($_POST['contact_location']);
        
        if (! empty($website)) {
            return "<p>Message Sent</p>";
            exit;
        }
        echo "test";
        JUtility::sendMail($contact_email, $contact_name, $contact_location_email, 'Contact Message for:'.$contact_location, $contact_message, 0, null, null, null, $contact_email, $contact_name);
        echo "<p>Message Sent</p>";
        exit;
    }

}
