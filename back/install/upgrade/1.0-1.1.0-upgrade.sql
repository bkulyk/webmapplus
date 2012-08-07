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

/*Upgrades to database from version 1.0 to version 1.1*/

/*BEGIN Add columns to #__webmapplus_locations table*/
ALTER TABLE #__webmapplus_locations ADD description varchar(255) NOT NULL COMMENT 'Description Metatag Field for Location' AFTER email;
ALTER TABLE #__webmapplus_locations ADD keywords varchar(255) NOT NULL COMMENT 'Keywords Metatag Field for Location' AFTER description;
ALTER TABLE #__webmapplus_locations ADD robots varchar(255) NOT NULL COMMENT 'Robots Metatag Field for Location' AFTER keywords;
ALTER TABLE #__webmapplus_locations ADD author varchar(255) NOT NULL COMMENT 'Author Metatag Field for Location' AFTER robots;
/*END Add columns to #__webmapplus_locations table*/

/*BEGIN Add columns to #__webmapplus_markerTypes table*/
ALTER TABLE #__webmapplus_markerTypes ADD imageMap text NOT NULL AFTER file_name;			
ALTER TABLE #__webmapplus_markerTypes ADD iconSize varchar(10) NOT NULL AFTER imageMap;		
ALTER TABLE #__webmapplus_markerTypes ADD shadowSize varchar(10) NOT NULL AFTER iconSize;			
ALTER TABLE #__webmapplus_markerTypes ADD iconAnchor varchar(10) NOT NULL AFTER shadowSize;			
ALTER TABLE #__webmapplus_markerTypes ADD anchorPoint tinyint(1) NOT NULL COMMENT 'Magic Number representing the anchor point of the marker (1=left, 2=center, 3=right)' AFTER iconAnchor; 			
ALTER TABLE #__webmapplus_markerTypes ADD infoWindowAnchor varchar(10) NOT NULL AFTER anchorPoint;			
ALTER TABLE #__webmapplus_markerTypes ADD lettering tinyint(1) NOT NULL DEFAULT '0' AFTER infoWindowAnchor; 
ALTER TABLE #__webmapplus_markerTypes ADD system tinyint(1) NOT NULL DEFAULT '0' AFTER lettering;		
/*END Add columns to #__webmapplus_markerTypes table*/

/*BEGIN Changes to data in #__webmapplus_markerTypes*/
UPDATE #__webmapplus_markerTypes SET system = 1, lettering = 1 WHERE id < 12;
/*End Changes to data in #__webmapplus_markerTypes*/
