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

/*Upgrades to database from version 1.2.0 to version 1.2.1*/

/*BEGIN Changes to #__webmapplus_locations table*/
ALTER TABLE  `#__webmapplus_locations` CHANGE  `zip`  `zip` CHAR( 10 ) NOT NULL COMMENT  'Zip/Postal Code of Location';
/*BEGIN Changes to #__webmapplus_locations table*/