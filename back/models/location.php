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

/**
 * JoomLocation Component Location Model
 *
 * @package		Joomla
 * @subpackage	Multimap
 * @since 1.5
 */
class WebmapPlusModelLocation extends JModel
{
	/**
	 * Location id
	 *
	 * @var int
	 */
	var $_id = null;

	/**
	 * Location data
	 *
	 * @var array
	 */
	var $_data = null;

	/**
	 * Constructor
	 *
	 * @since 1.5
	 */
	function __construct()
	{
		parent::__construct();

		$array = JRequest::getVar('cid', array(0), '', 'array');
		$edit	= JRequest::getVar('edit',true);
		if($edit)
			$this->setId((int)$array[0]);
	}

	/**
	 * Method to set the location identifier
	 *
	 * @access	public
	 * @param	int Weblink identifier
	 */
	function setId($id)
	{
		// Set weblink id and wipe data
		$this->_id		= $id;
		$this->_data	= null;
	}

	function getData()
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
	
	/*
	 * function &getAttributes()
	 * 
	 */
	function getAttributes()
	{
		$query = 'SELECT * FROM #__webmapplus_attributes'
				. ' ORDER BY ordering';
				
		return $this->_getList($query);
	}
	
	/*
	 * 
	 * @access public
	 */
	function getCountryCodes()
	{
		$query = 'SELECT * FROM #__webmapplus_contry'
				. ' ORDER BY iso';
				
		return $this->_getList($query);
	}


	function store($data)
	{
		$row =& $this->getTable();
		
		// Bind the form fields to the web link table
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		// if new item, order last in appropriate group
		if (!$row->id) {
			$row->ordering = $row->getNextOrder();
		}
		
		/*
		if ($row->address1 == '') {
			$this->setError( JText::_('Address 1 is a required field') );
            return false;
		}
		
		if ($row->city == '') {
			$this->setError( JText::_('City/Town/Suburb is a required field') );
            return false;
		}*/

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
			$query = 'DELETE FROM #__webmapplus_locations'
				. ' WHERE id IN ( '.$cids.' )';
			$this->_db->setQuery( $query );
			if(!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
	
			$query = 'DELETE FROM #__webmapplus_location_attributes'
				. ' WHERE loc_id IN ( '.$cids.' )';
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
		$user 	=& JFactory::getUser();

		if (count( $cid ))
		{
			JArrayHelper::toInteger($cid);
			$cids = implode( ',', $cid );

			$query = 'UPDATE #__webmapplus_locations'
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
		if (!$row->move( $direction, ' published >= 0 ' )) {
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

	function _loadData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$query = 'SELECT l.*'.
					' FROM #__webmapplus_locations AS l' .
					' WHERE l.id = '.(int) $this->_id;
			$this->_db->setQuery($query);
			$data = $this->_db->loadObject();
			
			$query = 'SELECT la.*'.
					' FROM #__webmapplus_location_attributes AS la' .
					' WHERE la.loc_id = '.(int) $this->_id;
			
			$attr = $this->_getList($query);
			echo $this->_db->getErrorMsg($query);
			foreach($attr as $la)
			{
			   $aid = $la->attr_id;
			   $data->attributes->$aid = $la;
      		}
      
			$this->_data = $data;
			return (boolean) $this->_data;
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
			$location = & $this->getTable();
			if(! $location->checkin($this->_id)) {
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
			$location = & $this->getTable();
			if(!$location->checkout($uid, $this->_id)) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			 $location->checkout($uid, $this->_id);
			return true;
		}
		return false;
	}
	
	function updateAttribute($id, $value){
	   $sql = "UPDATE #__webmapplus_location_attributes SET value = '".nl2br($value)."' WHERE id = $id";
	   $this->_db->setQuery( $sql );
     return $this->_db->query();
  }
  
	function addAttribute($a_id, $value){
   if ($this->_id)
	 {
	   $sql = "INSERT INTO #__webmapplus_location_attributes(loc_id, attr_id, value) VALUES ".
                   "($this->_id, $a_id, '".nl2br($value)."')";
     $this->_db->setQuery( $sql );
     return $this->_db->query();
   }
   return false;
  }

	function _initData()
	{
        // Lets load the content if it doesn't already exist
        if ( empty($this->_data))
        {
            $location = new stdClass ();
        
            $location->id = null;
            $location->name = null;
            $location->address1 = null;
            $location->address2 = null;
            $location->city = null;
            $location->state = null;
            $location->zip = null;
			$location->country_code = null;
            $location->phone = null;
            $location->fax = null;
            $location->email = null;
            $location->hours = null;
            $location->lat = null;
            $location->long = null;
            $location->pano = null;
			$location->country_code = null;
            $location->photo_path = null;
			$location->author = null;
			$location->keywords = null;
			$location->robots = null;
			$location->description = null;
			$location->category = 0;
            $location->checked_out = 0;
            $location->checked_out_time = "0000-00-00 00:00:00";
            $location->ordering = 0;
            $location->published = 0;
			$location->markerType = 0;
            $location->created = "0000-00-00 00:00:00";
            $location->modified = "0000-00-00 00:00:00";
        
            $this->_data = $location;
            return (boolean)$this->_data;
        }
        return true;
	}
}