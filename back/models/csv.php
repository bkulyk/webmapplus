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


class WebmapPlusModelCSV  extends JModel
{

	var $_data = null;

	var $_total = null;

	var $_pagination = null;

	function __construct()
	{
		parent::__construct();
	}

	function getData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$data = $this->getLocations();
			
			$locIds = array();
			foreach($data as $d)
			{
				$locIds[] = $d->id;
			} 		  
			$query = $this->_buildAttributeQuery($locIds);
			$attrs = $this->_getList($query);
  			$index = 0;
			foreach($data as $item)
		      {
		        $attrArray = array();
                                         
		        if(!empty($attrs)){
					foreach($attrs as $attr)
			        {
			          if($attr->loc_id == $item->id){
			          	if($attr->type == 'telephone')
						{
							$attr->type = "text";
						}
						$attrArray[] = $attr;
					  }
			        }
					$item->attributes = $attrArray;  
				}
				
		        $index++;
		      }
		}
		
    	$this->_data = $data;
		return $this->_data;
	}

	function getTotal()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_total))
		{
			$query = $this->_buildQuery();
			
			$this->_total = $this->_getListCount($query);
		}

		return $this->_total;
	}

	function _buildQuery()
	{

		$query = 'SELECT a.* '
			. ' FROM #__webmapplus_locations AS a';

		return $query;
	}

	function _buildAttributeQuery($locs)
	{
		$query = 'SELECT at.id, at.name, at.type, lat.value, lat.loc_id '
			   . ' FROM #__webmapplus_location_attributes AS lat '
			   . ' LEFT JOIN #__webmapplus_attributes AS at ON lat.attr_id = at.id '
          	   . ' WHERE lat.attr_id = at.id ORDER BY at.id';
			
		return $query;
	}
  
  function getAllAttributes(){
  	$query = ' SELECT a.* FROM #__webmapplus_attributes AS a ORDER BY a.id ';
	return $this->_getList($query);
  }
  
  function getLocations(){
	$query = $this->_buildQuery();
	return $this->_getList($query);
  }
  
  function store($data)
  {
      $row = & $this->getTable();
	  
      // Bind the form fields to the web link table
      if (!$row->bind($data))
      {
          $this->setError($this->_db->getErrorMsg());
          return false;
      }
  
      // if new item, order last in appropriate group
      if (!$row->id)
      {
          $row->ordering = $row->getNextOrder();
      }
  
      // Make sure the web link table is valid
      if (!$row->check())
      {
          $this->setError($this->_db->getErrorMsg());
          return false;
      }
  
      // Store the web link table to the database
      if (!$row->store())
      {
          $this->setError($this->_db->getErrorMsg());
          return false;
      }
      if (!$this->_id)
          $this->_id = $this->_db->insertid();
  
      return true;
  }
}
