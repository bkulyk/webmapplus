/**
 * @package		WebmapPlus
 * @subpackage	Install - Upgrade SQL
 * @copyright	Copyright (C) 2009 Accade LLC.
 * @license		GNU/GPL, see LICENSE.txt
 * This component is classified as derivative of Joomla! and as such this
 * version may have been modified pursuant to the GNU General Public License,
 * and as distributed it includes or is derivative of works licensed under the
 * GNU General Public License or other free or open source software licenses.
 */

/*Upgrades to database from version 1.1 to version 1.2.0*/

/*BEGIN Create #__webmapplus_categories*/
CREATE TABLE IF NOT EXISTS `#__webmapplus_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Parent of the category',
  `title` varchar(255) NOT NULL DEFAULT '',
  `highlight_color` varchar(7) NOT NULL DEFAULT '' COMMENT 'CSS Highlight Color for the Listings Box',
  `markerType` INT( 11 ) NOT NULL DEFAULT 0 COMMENT 'Default Marker Type',
  `name` varchar(255) NOT NULL DEFAULT '',
  `alias` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `checked_out` int(11) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
/*END Create #__webmapplus_categories*/

/*BEGIN Changes to #__webmapplus_location_attributes table*/
ALTER TABLE  `#__webmapplus_location_attributes` CHANGE  `value` `value` text NOT NULL;
/*END Changes to #__webmapplus_location_attributes table*/

/*Begin Changes to #__webmapplus_locations table*/
ALTER TABLE #__webmapplus_locations ADD `category` int(11) NOT NULL AFTER author;
ALTER TABLE #__webmapplus_locations CHANGE `state` `state` varchar(128) NOT NULL;
/*END Changes to #__webmapplus_locations table*/
