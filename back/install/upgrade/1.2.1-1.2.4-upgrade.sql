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

/*Upgrades to database from version 1.2.1 to version 1.2.4*/

/*BEGIN Changes to #__webmapplus_locations table*/
UPDATE `#__webmapplus_country` SET `iso` = 'RS', `name` = 'SERBIA', `printable_name` = 'Serbia', `iso3` = 'SRB', `numcode` = '688' WHERE `iso` = 'CS';
/*BEGIN Changes to #__webmapplus_locations table*/
