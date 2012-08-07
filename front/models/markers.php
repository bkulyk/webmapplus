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
defined('_JEXEC') or die('Restricted access');
ini_set('display_errors', 0);
jimport('joomla.application.component.model');


class WebmapPlusModelMarkers extends JModel {
    
    var $_data = null;
    
    var $_total = null;
    
    var $_pagination = null;
    
    function __construct() {
        parent::__construct();
        
        list( $mainframe, $option ) = array( JFactory::getApplication(), JRequest::getCmd('option') );
    }
    
    function getData() {
        // Lets load the content if it doesn't already exist
        if ( empty($this->_data)) {
            $address = JRequest::getVar('address', 0);
            $range = JRequest::getInt('range', 20);
            
            if (! empty($address)) {
                $latlng = WebmapPlusHelper::GeoCode($address, true);
                $data = $this->getLocByLatLngBox($latlng, $range);
            } else
                $data = $this->getLocations();
            
            if (!isset($data)) {
                $data = array();
            }
            
            $locIds = array();
            
            foreach ($data as $d) {
                $locIds[] = $d->id;
            }
            $query = $this->_buildAttributeQuery($locIds);
            $attrs = $this->_getList($query);
            $notFoundPhoto = new stdClass ();
            $notFoundPhoto->path = "na.jpg";
            $notFoundPhoto->name = "No Picture";
            
            $useLetters = (count($data) > 1 && count($data) <= 26);
            $index = 0;
            $markerIds = array();
            
            foreach ($data as $item) {
                $item->attributes = array();
                
                if (! empty($attrs)) {
                    foreach ($attrs as $attr) {
                        if ($attr->loc_id == $item->id) {
                            $item->attributes[] = $attr;
                        }
                    }
                }
                
                if (! empty($item->photo_path))
                    $item->photo_path = WEBMAPPLUS_MEDIA_URL.$item->photo_path;
                else
                    $item->photo_path = WEBMAPPLUS_ASSETS_URL."images/na.jpg";
                
                $item->link = JRoute::_('index.php?option=com_webmapplus&view=location&id='.$item->id);
                //If Item has a category
				$category_marker = 0;
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
				if ($item->markerType == 0) {
                        $item->markerType = 1;
                }
                //categoryMarkerType($item->category);
                $markerIds[$item->markerType] = 1;
                $index++;
            }
            $markerTypes = $this->_getMarkerTypes(array_keys($markerIds));
        }
        
        $this->_data = array("markers"=>$data, "markerTypes"=>$markerTypes, "useLetters"=>$useLetters);
        return $this->_data;
    }
    
    function getTotal() {
        // Lets load the content if it doesn't already exist
        if ( empty($this->_total)) {
            $query = $this->_buildQuery();
            
            $this->_total = $this->_getListCount($query);
        }
        
        return $this->_total;
    }
    
    function _buildQuery() {
        $category = JRequest::getInt('category', 0);

        
        if ($category == 0) {
            $query = 'SELECT a.id, a.name, a.alias, a.address1, a.address2, a.city, a.state, a.zip,'.' a.country_code AS country, a.photo_path, a.lat, a.long, a.pano, a.category, a.markerType, cat.highlight_color'.' FROM #__webmapplus_locations AS a'.' LEFT JOIN #__webmapplus_categories as cat ON cat.id = a.category'.' WHERE a.published = 1 ORDER BY a.ordering';
        } else {
            $data = array();
            $data = $this->categories($category);
            $query = 'SELECT a.id, a.name, a.alias, a.address1, a.address2, a.city, a.state, a.zip,'.' a.country_code AS country, a.photo_path, a.lat, a.long, a.pano, a.category, a.markerType, cat.highlight_color'.' FROM #__webmapplus_locations AS a'.' LEFT JOIN #__webmapplus_categories as cat ON cat.id = a.category'.' WHERE a.published = 1 AND a.category IN ('.$category.''.implode(',', $data).') ORDER BY a.ordering';
        }
        
        return $query;
    }
    
    function categoryMarkerType($category) {
        $query = 'SELECT cat.markerType FROM #__webmapplus_categories as cat WHERE cat.id ='.(int) $category.' LIMIT 1';
        $categories = $this->_getList($query);
        foreach ($categories as $cat) {
            $data = $cat->markerType;
        }
        return $data;
    }
    
    function categories($category) {
        $data = array();
        $query = 'SELECT cat.id FROM #__webmapplus_categories as cat WHERE cat.parent_id ='.$category;
        $categories = $this->_getList($query);
        foreach ($categories as $cat) {
            $data[] = $cat->id;
            return array_merge($data, $this->categories($cat->id));
        }
        return $data;
    }
    
    function _buildAttributeQuery($locs) {
        $query = 'SELECT at.id, at.name, at.type, lat.value, at.displayListing, at.displayBHover, at.displayBClick, at.ordering, lat.loc_id'.' FROM #__webmapplus_location_attributes AS lat'.' LEFT JOIN #__webmapplus_attributes AS at ON lat.attr_id = at.id'.' WHERE lat.attr_id = at.id ORDER BY at.ordering AND at.published = 1';
        if (count($locs) > 0)
            $query .= ' AND lat.loc_id IN ('.implode(',', $locs).')';
        
        return $query;
    }
    
    function _getMarkerTypes($ids = array()) {
        
        $query = "SELECT m.id, m.file_name, m.imageMap, m.iconSize, m.shadowSize, m.iconAnchor, "."m.infoWindowAnchor, m.lettering FROM #__webmapplus_markerTypes AS m ";
        
        if (! empty($ids))
            $query .= "WHERE m.id IN (".implode(',', $ids).")";
        
        $markers = $this->_getList($query);
        $return = array();
        
        foreach ($markers as $marker)
            $return[$marker->id] = $marker;
        
        return $return;
    
    }
    
    function getLocByLatLngBox($box, $range = 20) {
        $db = &JFactory::getDBO();
        $params = &JComponentHelper::getParams('com_webmapplus');
        
        if ($params->get('units') == 1)
            $rangeFactor = 0.009001; //Kilometers
        else
            $rangeFactor = 0.014457; //Miles
        
        $lowerLat = $box->south - ($range * $rangeFactor);
        $upperLat = $box->north + ($range * $rangeFactor);
        $lowerLng = $box->west - ($range * $rangeFactor);
        $upperLng = $box->east + ($range * $rangeFactor);
        
        $midLat = ($box->north+$box->south)/2;
        $midLng = ($box->east+$box->west)/2;
        
        $query = 'SELECT a.id, a.name, a.alias, a.address1, a.address2, a.city, a.state, a.zip,'.' a.country_code AS country, a.photo_path, a.lat, a.long, a.pano, a.category, a.markerType, cat.highlight_color'.' FROM #__webmapplus_locations AS a'.' LEFT JOIN #__webmapplus_categories as cat ON cat.id = a.category'." WHERE a.lat  BETWEEN  ".$lowerLat."  AND  ".$upperLat." AND a.long  BETWEEN  ".$lowerLng."  AND  ".$upperLng." AND a.published = 1";
        
        $locs = $this->_getList($query);
        
        foreach($locs as $key => $l){
          $l->distance = $this->distance($l->lat, $l->long, $midLat, $midLng, $params->get('units') == 0);
          if($l->distance > $range){
            unset($locs[$key]);
          }
        }        
        usort($locs, array($this, locDistComp));
        
        return $locs;
    }
    
    function getLocations() {
        return $this->_getList($this->_buildQuery());
    }
    
    function distance($lat1, $lng1, $lat2, $lng2, $miles = true)
    {
    	$pi80 = M_PI / 180;
    	$lat1 *= $pi80;
    	$lng1 *= $pi80;
    	$lat2 *= $pi80;
    	$lng2 *= $pi80;
    
    	$r = 6372.797; // mean radius of Earth in km
    	$dlat = $lat2 - $lat1;
    	$dlng = $lng2 - $lng1;
    	$a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlng / 2) * sin($dlng / 2);
    	$c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    	$km = $r * $c;
    
    	return ($miles ? ($km * 0.621371192) : $km);
    }
    
    function locDistComp($a, $b)
    {
      if ($a->distance == $b->distance) {
          return 0;
      }
      return ($a->distance < $b->distance) ? -1 : 1;
    }

}
