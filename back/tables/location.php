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

class TableLocation extends JTable
{

    var $id = null;
    var $name = null;
    var $address1 = null;
    var $address2 = null;
    var $city = null;
    var $state = null;
    var $zip = null;
    var $phone = null;
    var $fax = null;
    var $email = null;
    var $hours = null;
    var $lat = null;
    var $long = null;
    var $pano = null;
	var $country_code = null;
    var $photo_path = null;
	var $author = null;
	var $keywords = null;
	var $robots = null;
	var $description = null;
	var $category = 0;
    var $checked_out = 0;
    var $checked_out_time = "0000-00-00 00:00:00";
    var $ordering = 0;
    var $published = 0;
    var $created = "0000-00-00 00:00:00";
    var $modified = "0000-00-00 00:00:00";
    var $markerType = 0;

    function __construct( & $db)
    {
        parent::__construct('#__webmapplus_locations', 'id', $db);
    }
}
?>
