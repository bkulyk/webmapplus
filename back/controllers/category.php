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

class WebmapPlusControllerCategory extends WebmapPlusController
{
    function __construct($config = array ())
    {
        parent::__construct($config);
    }

    function add()
    {
        JRequest::setVar('hidemainmenu', 1);
        JRequest::setVar('layout', 'form');
        JRequest::setVar('view', 'category');
        JRequest::setVar('edit', false);

        parent::display();

        $model = $this->getModel('category');

    }

    function edit()
    {
        $model = $this->getModel('category');

        JRequest::setVar('hidemainmenu', 1);
        JRequest::setVar('layout', 'form');
        JRequest::setVar('view', 'category');
        JRequest::setVar('edit', true);

        parent::display();
		
		$model->checkout();
    }

    function apply()
    {
        // Check for request forgeries
        JRequest::checkToken() or jexit('Invalid Token');

        $post = JRequest::get('post');
        $cid = JRequest::getVar('cid', array (0), 'post', 'array');
        $post['id'] = (int)$cid[0];

        $model = $this->getModel('category');

        if ($model->store($post))
        {
            $msg = JText::_('Category Saved');
        } else
        {
            $msg = JText::_('Error Saving Category');
        }

        $model->checkin();
        $link = 'index.php?option=com_webmapplus&controller=category&task=edit&cid[]='.$post['id'];
        $this->setRedirect($link, $msg);
    }

    function save()
    {
        // Check for request forgeries
        JRequest::checkToken() or jexit('Invalid Token');

        $post = JRequest::get('post');
        $cid = JRequest::getVar('cid', array (0), 'post', 'array');
        $post['id'] = (int)$cid[0];

        $model = $this->getModel('category');

        if ($model->store($post))
        {
            $msg = JText::_('Category Saved');
        } else
        {
            $msg = JText::_('Error Saving Category');
        }

        $model->checkin();
        $link = 'index.php?option=com_webmapplus&view=categories';
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
            JError::raiseError(500, JText::_('Select an item to delete.'));
        }

        $model = $this->getModel('category');
        if (!$model->delete($cid))
        {
        	$msg = JText::_('Can Not Delete Categories that are parent\'s to other categories');
            echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
        }

        $this->setRedirect('index.php?option=com_webmapplus&view=categories', $msg);
    }

    function cancel()
    {
        // Check for request forgeries
        JRequest::checkToken() or jexit('Invalid Token');
		$model = $this->getModel('category');
		$model->checkin();
        $this->setRedirect('index.php?option=com_webmapplus&view=categories');
    }

    function publish()
    {
        // Check for request forgeries
        JRequest::checkToken() or jexit('Invalid Token');

        $cid = JRequest::getVar('cid', array (), 'post', 'array');
        JArrayHelper::toInteger($cid);
        if (count($cid) < 1)
        {
            JError::raiseError(500, JText::_('Select an item to publish'));
        }

        $model = $this->getModel('category');
        if (!$model->publish($cid, 1))
        {
            echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
        }

        $this->setRedirect('index.php?option=com_webmapplus&view=categories');
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

        $model = $this->getModel('category');
        if (!$model->publish($cid, 0))
        {
            echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
        }

        $this->setRedirect('index.php?option=com_webmapplus&view=categories');
    }

    function orderup()
    {
        // Check for request forgeries
        JRequest::checkToken() or jexit('Invalid Token');

        $model = $this->getModel('category');
        $model->move(-1);

        $this->setRedirect('index.php?option=com_webmapplus&view=categories');
    }

    function orderdown()
    {
        // Check for request forgeries
        JRequest::checkToken() or jexit('Invalid Token');

        $model = $this->getModel('category');
        $model->move(1);

        $this->setRedirect('index.php?option=com_webmapplus&view=categories');
    }

    function saveorder()
    {
        // Check for request forgeries
        JRequest::checkToken() or jexit('Invalid Token');

        $cid = JRequest::getVar('cid', array (), 'post', 'array');
        $order = JRequest::getVar('order', array (), 'post', 'array');
        JArrayHelper::toInteger($cid);
        JArrayHelper::toInteger($order);

        $model = $this->getModel('category');
        $model->saveorder($cid, $order);

        $msg = 'New ordering saved';
        $this->setRedirect('index.php?option=com_webmapplus&view=categories', $msg);
    }
}
?>
