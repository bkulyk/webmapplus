<?php
/**
 * @package		WebmapPlus
 * @subpackage	Frontend Models
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

class WebmapPlusModelAttributes extends JModel
{
	/**
	 * Attributes data array
	 *
	 * @var array
	 */
	var $_data = null;

	/**
	 * Attribute total
	 *
	 * @var integer
	 */
	var $_total = null;

	/**
	 * Constructor
	 *
	 * @since 1.5
	 */
	function __construct()
	{
		parent::__construct();

		list( $mainframe, $option ) = array( JFactory::getApplication(), JRequest::getCmd('option') );
	}

	/**
	 * Method to get attributes item data
	 *
	 * @access public
	 * @return array
	 */
	function getData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$query = $this->_buildQuery();
			$data = $this->_getList($query);
		}
	    
    	$this->_data = $data;
		return $this->_data;
	}

	/**
	 * Method to get the total number of attributes
	 *
	 * @access public
	 * @return integer
	 */
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
		$query = ' SELECT * FROM #__webmapplus_attributes AS a ORDER BY a.ordering';

		return $query;
	}
  
}
