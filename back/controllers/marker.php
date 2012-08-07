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

class WebmapPlusControllerMarker extends WebmapPlusController
{
    function __construct($config = array ())
    {
        parent::__construct($config);
    }

    function add()
    {
        JRequest::setVar('hidemainmenu', 1);
        JRequest::setVar('layout', 'wizard');
        JRequest::setVar('view', 'marker');
        JRequest::setVar('edit', false);

        parent::display();

        $model = $this->getModel('marker');

    }

    function edit()
    {
        $model = $this->getModel('marker');

        JRequest::setVar('hidemainmenu', 1);
        JRequest::setVar('layout', 'form');
        JRequest::setVar('view', 'marker');
        JRequest::setVar('edit', true);

        parent::display();
		
		$model->checkout();
    }

    function save()
    {
        // Check for request forgeries
        JRequest::checkToken() or jexit('Invalid Token');

        $post = JRequest::get('post');
        $cid = JRequest::getVar('cid', array (0), 'post', 'array');
        $post['id'] = (int)$cid[0];
		require_once( JPATH_COMPONENT.DS.'helpers'.DS.'marker.php' );
		$marker = imagecreatefrompng(COM_WEBMAPPLUS_ASSETS_PATH.DS.'images'.DS.'markers'.DS.$post['file_name'].'.png');
		switch($post['anchorPoint']){
			case 1		: $anchorX = 0;
					 		  break;
			case 3 	: $anchorX = imagesx($marker);
					  		  break;
			default 		: $anchorX = imagesx($marker)/2;
		}
		
		$anchory = imagesy($marker);
		$post['iconAnchor'] = $anchorX.','.$anchory;

        $model = $this->getModel('marker');

        if ($model->store($post))
        {
            $msg = JText::_('Marker Saved');
        } else
        {
            $msg = JText::_('Error Saving Marker');
        }

        $model->checkin();
        $link = 'index.php?option=com_webmapplus&view=markers';
        $this->setRedirect($link, $msg);
    }
	
	function apply()
    {
        // Check for request forgeries
        JRequest::checkToken() or jexit('Invalid Token');

        $post = JRequest::get('post');
        $cid = JRequest::getVar('cid', array (0), 'post', 'array');
        $post['id'] = (int)$cid[0];
		require_once( JPATH_COMPONENT.DS.'helpers'.DS.'marker.php' );
		$marker = imagecreatefrompng(COM_WEBMAPPLUS_ASSETS_PATH.DS.'images'.DS.'markers'.DS.$post['file_name'].'.png');
		switch($post['anchorPoint']){
			case 1		: $anchorX = 0;
					 		  break;
			case 3 	: $anchorX = imagesx($marker);
					  		  break;
			default 		: $anchorX = imagesx($marker)/2;
		}
		
		$anchory = imagesy($marker);
		$post['iconAnchor'] = $anchorX.','.$anchory;

        $model = $this->getModel('marker');

        if ($model->store($post))
        {
            $msg = JText::_('Marker Saved');
        } else
        {
            $msg = JText::_('Error Saving Marker');
        }

        $model->checkin();
        $link = 'index.php?option=com_webmapplus&controller=marker&task=edit&cid[]='.$post['id'];;
        $this->setRedirect($link, $msg);
    }

    function remove()
    {
        // Check for request forgeries
        JRequest::checkToken() or jexit('Invalid Token');

        $cid = JRequest::getVar('cid', array (), 'post', 'array');
        JArrayHelper::toInteger($cid);

        if (count($cid) < 1)
        {
            JError::raiseError(500, JText::_('Select a marker to delete'));
        }

        $model = $this->getModel('marker');
        if (!$model->delete($cid))
        {
            echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
        }

        $this->setRedirect('index.php?option=com_webmapplus&view=markers');
    }

    function cancel()
    {
        // Check for request forgeries
        JRequest::checkToken() or jexit('Invalid Token');
		$model = $this->getModel('marker');
		$model->checkin();
        $this->setRedirect('index.php?option=com_webmapplus&view=markers');
    }

    function publish()
    {
        // Check for request forgeries
        JRequest::checkToken() or jexit('Invalid Token');

        $cid = JRequest::getVar('cid', array (), 'post', 'array');
        JArrayHelper::toInteger($cid);
        if (count($cid) < 1)
        {
            JError::raiseError(500, JText::_('Select a marker to publish'));
        }

        $model = $this->getModel('attribute');
        if (!$model->publish($cid, 1))
        {
            echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
        }

        $this->setRedirect('index.php?option=com_webmapplus&view=markers');
    }


    function unpublish()
    {
        // Check for request forgeries
        JRequest::checkToken() or jexit('Invalid Token');

        $cid = JRequest::getVar('cid', array (), 'post', 'array');
        JArrayHelper::toInteger($cid);
        if (count($cid) < 1)
        {
            JError::raiseError(500, JText::_('Select an item to unpublish'));
        }

        $model = $this->getModel('attribute');
        if (!$model->publish($cid, 0))
        {
            echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
        }

        $this->setRedirect('index.php?option=com_webmapplus&view=markers');
    }

    function orderup()
    {
        // Check for request forgeries
        JRequest::checkToken() or jexit('Invalid Token');

        $model = $this->getModel('attribute');
        $model->move(-1);

        $this->setRedirect('index.php?option=com_webmapplus&view=markers');
    }

    function orderdown()
    {
        // Check for request forgeries
        JRequest::checkToken() or jexit('Invalid Token');

        $model = $this->getModel('attribute');
        $model->move(1);

        $this->setRedirect('index.php?option=com_webmapplus&view=markers');
    }

    function saveorder()
    {
        // Check for request forgeries
        JRequest::checkToken() or jexit('Invalid Token');

        $cid = JRequest::getVar('cid', array (), 'post', 'array');
        $order = JRequest::getVar('order', array (), 'post', 'array');
        JArrayHelper::toInteger($cid);
        JArrayHelper::toInteger($order);

        $model = $this->getModel('attribute');
        $model->saveorder($cid, $order);

        $msg = JText::_('New ordering saved');
        $this->setRedirect('index.php?option=com_webmapplus&view=markers', $msg);
    }
	
	function wizardStep1(){
		JRequest::checkToken('request') or jexit('Invalid Token');
		$markerFile = $this->_uploadFile("marker", true);
		if($markerFile === false){
			$this->setRedirect('index.php?option=com_webmapplus&view=marker&layout=wizard');
			return;
		}
		$name = JRequest::getVar("name", '', '', 'string');
		$anchor = JRequest::getVar("anchorPoint", '', '', 'string');
		
		require_once( JPATH_COMPONENT.DS.'helpers'.DS.'marker.php' );
		$marker = imagecreatefrompng(JPATH_ROOT.$markerFile['dirname'].DS.$markerFile['basename']);
		$shadow = MarkerHelper::createShadow($marker, .525);
		$trans = MarkerHelper::createTransparent($marker);
		$imageMap = MarkerHelper::findBoundry($marker);
		
		$filePath = JPATH_ROOT.$markerFile['dirname'].DS.$markerFile['filename'];
		
		imagepng( $shadow ,$filePath."s.png");
		imagegif( MarkerHelper::createPrint($marker) ,$filePath."p.gif");
		imagegif( MarkerHelper::createPrint($marker, false, true),$filePath."mp.gif");
		imagegif( MarkerHelper::createPrint($shadow, true),$filePath."ps.gif");
		imagepng( $trans,$filePath."t.png");
		imagepng( MarkerHelper::overlayImageMap($marker, $imageMap),$filePath."o.png");

		$session = &JFactory::getSession();
		
		switch($anchor){
			case "left"		: $anchorX = 0;
					 		  break;
			case "right" 	: $anchorX = imagesx($marker)-1;
					  		  break;
			default 		: $anchorX = imagesx($marker)/2;
		} 
		
		$tmpMarker  = array("name" => $name,
							"filename" => $markerFile['filename'], 
							"imageMap" => $imageMap,
							'iconSize' => array(imagesx($marker), imagesy($marker)),
							'shadowSize' => array(imagesx($shadow), imagesy($shadow)),
							'iconAnchor' => array($anchorX, imagesy($marker)-1),
							'infoWindowAnchor' => array(imagesx($marker)/2, 0),
							'anchor' => $anchor);
		$session->set("tmpMarker", $tmpMarker);
		//die();
		$this->setRedirect('index.php?option=com_webmapplus&view=marker&layout=wizard2');
	}
	
	function wizardSave(){
		// Check for request forgeries
        JRequest::checkToken() or jexit('Invalid Token');

        $post = JRequest::get('post');
		$cid = JRequest::getVar('cid', array (0), 'post', 'array');
        $post['id'] = (int)$cid[0];
		$filename = $post['file_name'];
		
		$oldBase = JPATH_ROOT.DS."tmp".DS.$filename;
		$newBase= COM_WEBMAPPLUS_ASSETS_PATH.DS.'images'.DS.'markers'.DS.$filename;
		
		$model = $this->getModel('marker');

        if ($model->store($post))
        {
            $msg = JText::_('Marker Saved');
			rename($oldBase.".png", $newBase.".png");
			rename($oldBase."s.png",  $newBase."s.png");
			rename($oldBase."p.gif",  $newBase."p.gif");
			rename($oldBase."mp.gif", $newBase."mp.gif");
			rename($oldBase."ps.gif", $newBase."ps.gif");
			rename($oldBase."t.png",  $newBase."t.png");
			unlink($oldBase."o.png");
        } else
        {
            $msg = JText::_('Error Saving Marker: '.$model->getError());
        }

        $model->checkin();
        $link = 'index.php?option=com_webmapplus&view=markers';
        $this->setRedirect($link, $msg);		
		
	}
	
	function _uploadFile($varName, $overwrite = false){
		$mainframe = JFactory::getApplication();
		$file = JRequest::getVar($varName, '', 'files', 'array');
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

        if ( isset ($file['name']))
        {
            $filepath = JPath::clean(JPATH_SITE.DS.'tmp'.DS.strtolower($file['name']));
			
			
			$format = strtolower(JFile::getExt($file['name']));
	        $allowable = array ('png');
	        $ignore = array ();
	        if (!in_array($format, $allowable) && !in_array($format, $ignore))
	        {
				JError::raiseNotice(100, JText::_('Error: File is a wrong type, please upload a png'));
	            return false;
	        }
			
            if (!MediaHelper::canUpload($file, $err))
            {
                JError::raiseNotice(100, JText::_($err));
                // REDIRECT
                if ($return)
                {
                    $mainframe->redirect(base64_decode($return));
                }
                return;
            }

            if (JFile::exists($filepath) && !$overwrite)
            {
	            JError::raiseNotice(100, JText::_('Error. File already exists'));
	            // REDIRECT
	            if ($return)
	            {
	                $mainframe->redirect(base64_decode($return));
	            }
	            return;
            }

            if (!JFile::upload($file['tmp_name'], $filepath))
            {
	            JError::raiseWarning(100, JText::_('Error. Unable to upload file'));
	            // REDIRECT
	            if ($return)
	            {
	                $mainframe->redirect(base64_decode($return));
	            }
	            return;
            } else
            {
            	$mainframe->enqueueMessage(JText::_('Upload complete'));
                // REDIRECT
                if ($return)
                {
                    $mainframe->redirect(base64_decode($return));
                }
                $params = & JComponentHelper::getParams('com_webmapplus');
                $filepath = str_replace(JPATH_ROOT, "", $filepath);
                $file_information = pathinfo($filepath);
                return $file_information;
            }
        } else
        {
            $mainframe->redirect('index.php', 'Invalid Request', 'error');
        }
	}
}
?>
