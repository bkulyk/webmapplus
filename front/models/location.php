<?php
/**
 * @package		WebmapPlus
 * @subpackage	Frontend Model
 * @copyright	Copyright (C) 2009 Accade LLC.
 * @license		GNU/GPL, see LICENSE.txt
 * This component is classified as derivative of Joomla! and as such this
 * version may have been modified pursuant to the GNU General Public License,
 * and as distributed it includes or is derivative of works licensed under the
 * GNU General Public License or other free or open source software licenses.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class WebmapPlusModelLocation extends JModel {
    var $_id = null;
    
    var $_category = null;
    
    var $_data = null;
    
    var $_total = null;
    
    var $_pagination = null;
    
    function __construct() {
        parent::__construct();
        
        list( $mainframe, $option ) = array( JFactory::getApplication(), JRequest::getCmd('option') );
        
        // Get the pagination request variables
        $limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
        $limitstart = $mainframe->getUserStateFromRequest($option.'.limitstart', 'limitstart', 0, 'int');
        
        // In case limit has been changed, adjust limitstart accordingly
        $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
        
        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
    }
    
    function getData() {
        // Lets load the content if it doesn't already exist
        if ( empty($this->_data)) {
            $this->_id = JRequest::getInt('id', 0);
            
            $data = $this->getLocByID($this->_id);
            $item = $data[0];
            
            $query = $this->_buildAttributeQuery($this->_id);
            $attrs = array();
            $attrs = $this->_getList($query);
            
            foreach ($attrs as & $attr) {
                if ($attr->type == 'telephone') {
                    $attr->type = "text";
                }
            }
            
            //If Item has a category
            if ($item->category > 0) {
                $category_marker = $this->categoryMarkerType($item->category);
                //If item-marker is set to the system default and category marker is set
                if ($item->markerType == 0 && $category_marker != 0) {
                    $item->markerType = $category_marker;
                }
            } else {
                //If item-marker is set to the system default
                if ($item->markerType == 0) {
                    $item->markerType = 1;
                }
            }
            
            $marker = $this->getMarker($item->markerType);
            
            $item->markerUrl = WEBMAPPLUS_ASSETS_URL."images/markers/".$marker->file_name.".png";
            
            $item->photo_path = empty($item->photo_path) ? WEBMAPPLUS_ASSETS_URL."images/na.jpg" : WEBMAPPLUS_MEDIA_URL.$item->photo_path;
            
            $item->attributes = $attrs;
            $this->_data = $item;
        }
        return $this->_data;
    }

    
    function _buildAttributeQuery($lid) {
        $query = ' SELECT at.id, at.name, at.type, lat.value, at.ordering, lat.loc_id'.' FROM #__webmapplus_attributes AS at, #__webmapplus_location_attributes '.'AS lat WHERE lat.attr_id = at.id  AND lat.loc_id = '.mysql_real_escape_string($lid).' AND at.published = 1 AND at.displayPage = 1 ORDER BY at.ordering';
        
        return $query;
    }
    
    function getLocByID($id) {
        $query = 'SELECT a.*, c.printable_name'.' FROM #__webmapplus_locations AS a '.' LEFT JOIN #__webmapplus_country AS c ON a.country_code = c.iso'.' LEFT JOIN #__webmapplus_categories as cat ON cat.id = a.category OR cat.parent_id =  a.category'.' WHERE a.id = '.mysql_real_escape_string($id);
        return $this->_getList($query);
    }
    
    function getMarker($id) {
        $query = 'SELECT m.*'.' FROM #__webmapplus_markerTypes AS m '.' WHERE m.id = '.mysql_real_escape_string($id).' LIMIT 1';
        $markers = $this->_getList($query);
        return $markers[0];
    }
    
    function categoryMarkerType($category) {
        $query = 'SELECT cat.markerType FROM #__webmapplus_categories as cat WHERE cat.id ='.(int) $category.' LIMIT 1';
        $categories = $this->_getList($query);
        foreach ($categories as $cat) {
            $data = $cat->markerType;
        }
        return $data;
    }

}
