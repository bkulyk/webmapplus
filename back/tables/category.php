<?php
/**
 * @package		WebmapPlus
 * @subpackage	Backend Tables
 * @copyright	Copyright (C) 2009 Accade LLC.
 * @license		GNU/GPL, see LICENSE.txt
 * This component is classified as derivative of Joomla! and as such this
 * version may have been modified pursuant to the GNU General Public License,
 * and as distributed it includes or is derivative of works licensed under the
 * GNU General Public License or other free or open source software licenses.
 */

// no direct access
defined('_JEXEC') or die ('Restricted access');

// Include library dependencies
jimport('joomla.filter.input');

class TableCategory extends JTable
{

    var $id = null;
    var $parent_id = 0;
    var $title = null;
    var $name = null;
    var $alias = null;
    var $description = null;
	var $highlight_color = null;
	var $markerType = 0;
    var $checked_out = 0;
    var $checked_out_time = "0000-00-00 00:00:00";
    var $ordering = 0;
    var $published = 0;

    function __construct( & $db)
    {
        parent::__construct('#__webmapplus_categories', 'id', $db);
    }
}
?>
