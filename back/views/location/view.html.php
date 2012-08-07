<?php
/**
 * @package		WebmapPlus
 * @subpackage	Backend Views
 * @copyright	Copyright (C) 2009 Accade LLC.
 * @license		GNU/GPL, see LICENSE.txt
 * This component is classified as derivative of Joomla! and as such this
 * version may have been modified pursuant to the GNU General Public License,
 * and as distributed it includes or is derivative of works licensed under the
 * GNU General Public License or other free or open source software licenses.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.view');


class WebmapPlusViewLocation extends JView {
    
    function display($tpl = null) {
        JToolBarHelper::cancel();
        JToolBarHelper::save();
        JToolBarHelper::apply();
        
        // Get data from the model
        $item = &$this->get('Data');
        
        $isNew = ($item->id < 1);
        
        // Set toolbar items for the page
        $text = $isNew ? JText::_('New') : JText::_('Edit');
        JToolBarHelper::title(JText::_('Location').': <small><small>[ '.$text.' ]</small></small>');
        
        $attributes = &$this->get('Attributes');
        $db = &JFactory::getDBO();
        echo $db->getErrorMsg();
        $query = 'SELECT ordering AS value, name AS text'.' FROM #__webmapplus_locations ORDER BY ordering';
        
        $lists['ordering'] = JHTML::_('list.specificordering', $item, $item->id, $query);
        
        $query = 'SELECT id AS value, name AS text'.' FROM #__webmapplus_markerTypes';
        $db->setQuery($query);
        $markers = $db->loadObjectList();
        //Handle a Default Marker Type
        $blank_array = array();
        $blank_array['value'] = '0';
        $blank_array['text'] = 'System Default';
        array_unshift($markers, $blank_array);
        $lists['markerIcons'] = JHTML::_('select.genericlist', $markers, "markerType", 'class="inputbox"', 'value', 'text', $item->markerType);
        
        $query = 'SELECT id AS value, name AS text'.' FROM #__webmapplus_categories';
        $db->setQuery($query);
        $categories = $db->loadObjectList();
        //Handle a No Category Location
        $blank_array = array();
        $blank_array['value'] = '0';
        $blank_array['text'] = 'No Category';
        array_unshift($categories, $blank_array);
        $lists['category'] = JHTML::_('select.genericlist', $categories, "category", 'class="inputbox"', 'value', 'text', $item->category);
        
        $query = 'SELECT iso AS value, printable_name AS text '.' FROM #__webmapplus_country';
        $db->setQuery($query);
        $country = $db->loadObjectList();
        //If a country_code is not set for the item, use the default country_code from the paramters
        if (!isset($item->country_code)) {
            $params = &JComponentHelper::getParams('com_webmapplus');
            $item->country_code = $params->get('default_country', 'US');
        }
        $lists['country'] = JHTML::_('select.genericlist', $country, "country_code", 'class="inputbox"', 'value', 'text', $item->country_code);
        
        $lists['published'] = JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $item->published);
        
        $this->assignRef('session', JFactory::getSession());
        $this->assignRef('location', $item);
        $this->assignRef('attributes', $attributes);
        $this->assignRef('lists', $lists);
        
        parent::display($tpl);
    }
    
    function generateAttrField($name, $type, $value = "", $values = array()) {
        switch ($type) {
            
            case 'bool':
                return JHTML::_('select.booleanlist', JText::_($name), 'class="inputbox"', $value);
                break;
            
            case 'telephone':
                return "<input maxlength='20' class='text_area' type='text' name='".$name."' value=\"$value\"  width='55' />";
                break;
            
            case 'text':
                return "<textarea maxlength='255' class='text_area' name='".$name."' rows='1' cols='20'>".$value."</textarea>";
                break;
            
            case 'link':
                return "<input maxlength='255' class='text_area' type='text' name='".$name."' value=\"$value\"  width='55' />";
                break;
            
            case 'textarea':
                return "<textarea class='text_area' name='".$name."' rows='2' cols='20'>".$value."</textarea>";
                break;
            
            case 'enum':
                foreach ($values as & $v) {
                    $v = array("value"=>$v);
                }
                
                return JHTML::_('select.genericlist', $values, JText::_($name), 'class="inputbox"', 'value', 'value', $value);
                break;

            default:
                return "Unkown Field Type";
                break;
        }
    }
}
