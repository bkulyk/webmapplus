<?php
/**
 * @package		JoomLocations
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

class TableAttribute extends JTable
{

    var $id = null;
    var $name = null;
    var $type = null;
	var $displayListing = 0;
	var $displayBHover = 0;
	var $displayBClick = 0;
	var $displayPage = 0;
    var $checked_out = 0;
    var $checked_out_time = "0000-00-00 00:00:00";
    var $ordering = 0;
    var $published = 0;
	var $values = 0;

    function __construct( & $db)
    {
        parent::__construct('#__webmapplus_attributes', 'id', $db);
    }
}
?>
