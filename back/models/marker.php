<?php
/**
 * @package		WebmapPlus
 * @subpackage	Backend Models
 * @copyright	Copyright (C) 2009 Accade LLC.
 * @license		GNU/GPL, see LICENSE.txt
 * This component is classified as derivative of Joomla! and as such this
 * version may have been modified pursuant to the GNU General Public License,
 * and as distributed it includes or is derivative of works licensed under the
 * GNU General Public License or other free or open source software licenses.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.model');

class WebmapPlusModelMarker extends JModel
{

	var $_id = null;

	var $_data = null;

	function __construct()
	{
		parent::__construct();

		$array = JRequest::getVar('cid', array(0), '', 'array');
		$edit	= JRequest::getVar('edit',true);
		if($edit)
			$this->setId((int)$array[0]);
	}

	function setId($id)
	{
		// Set weblink id and wipe data
		$this->_id		= $id;
		$this->_data	= null;
	}

	function &getData()
	{
		// Load the weblink data
		if ($this->_loadData())
		{
			// Initialize some variables
			$user = &JFactory::getUser();

		}
		else  $this->_initData();

		return $this->_data;
	}

	function store($data)
	{
		$row =& $this->getTable();
		// Bind the form fields to the web link table
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
	
		// Make sure the web link table is valid
		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Store the web link table to the database
		if (!$row->store()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		if(!$this->_id)
		  $this->_id = $this->_db->insertid();
		  
		return true;
	}

	function delete($cid = array())
	{
		$result = false;

		if (count( $cid ))
		{
			JArrayHelper::toInteger($cid);
			$cids = implode( ',', $cid );
			$query = 'DELETE FROM #__webmapplus_markerTypes'
				. ' WHERE id IN ( '.$cids.' )';
			$this->_db->setQuery( $query );
			if(!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}

		return true;
	}

	function publish($cid = array(), $publish = 1)
	{
		if (count( $cid ))
		{
			JArrayHelper::toInteger($cid);
			$cids = implode( ',', $cid );

			$query = 'UPDATE #__webmapplus_markerTypes'
				. ' SET published = '.(int) $publish
				. ' WHERE id IN ( '.$cids.' )';			
			$this->_db->setQuery( $query );
			if (!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}

		return true;
	}

	function move($direction)
	{
		$row =& $this->getTable();
		
		if (!$row->load($this->_id)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		return true;
	}

	function saveorder($cid = array(), $order)
	{
		$row =& $this->getTable();
		
		// update ordering values
		for( $i=0; $i < count($cid); $i++ )
		{
			$row->load( (int) $cid[$i] );
	    
			if ($row->order != $order[$i])
			{
				$row->ordering = $order[$i];
				if (!$row->store()) {
					$this->setError($this->_db->getErrorMsg());
					echo $this->_db->getErrorMsg();
					return false;
				}
			}
		}
		return true;
	}

function isCheckedOut($uid = null)
	{
    if ($this->_id)
		{
			// Make sure we have a user id to checkout the article with
			if (is_null($uid)) {
				$user	=& JFactory::getUser();
				$uid	= $user->get('id');
			}
			$row = & $this->getTable();
			$row->load($this->_id);
			return $row->isCheckedOut( $uid );
		}
		return false;
	}

	function checkin()
	{
		if ($this->_id)
		{
			$attribute = & $this->getTable();
			if(! $attribute->checkin($this->_id)) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}
		return false;
	}

	function checkout($uid = null)
	{
		if ($this->_id)
		{
			// Make sure we have a user id to checkout the article with
			if (is_null($uid)) {
				$user	=& JFactory::getUser();
				$uid	= $user->get('id');
			}
			// Lets get to it and checkout the thing...
			$attribute = & $this->getTable();
			if(!$attribute->checkout($uid, $this->_id)) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			 $attribute->checkout($uid, $this->_id);
			return true;
		}
		return false;
	}
	
	function _loadData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$query = 'SELECT l.*'.
					' FROM #__webmapplus_markerTypes AS l' .
					' WHERE l.id = '.(int) $this->_id;
			$this->_db->setQuery($query);
			$data = $this->_db->loadObject();
			
			echo $this->_db->getErrorMsg($query);
      
			$this->_data = $data;
			return (boolean) $this->_data;
		}
		return true;
	}

	function _initData()
	{
        // Lets load the content if it doesn't already exist
        if ( empty($this->_data))
        {
            $marker = new stdClass ();
        
            $marker->id = null;
            $marker->name = null;
            $marker->file_name = null;
			$marker->imageMap = null;
			$marker->iconSize = null;
			$marker->shadowSize = null;
			$marker->iconAnchor = null;
			$marker->anchorPoint = 2;
			$marker->infoWindowAnchor = null;
			$marker->lettering = null;
			$marker->system = 0;
		    $marker->checked_out = 0;
		    $marker->checked_out_time = "0000-00-00 00:00:00";
		    $marker->editor = 0;
        
            $this->_data = $marker;
            return (boolean)$this->_data;
        }
        return true;
	}
}