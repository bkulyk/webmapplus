<?php
/**
 * @package		WebmapPlus
 * @subpackage	Backend Models
 * @copyright	Copyright (C) 2009 Accade LLC.
 * @license		GNU/GPL, see LICENSE.txt
 * This component is classified as derivative of Joomla! and as such this
 * version may have been modified pursuant to the GNU General Public License,
 * and as distributed it includes or is derivative of works licensed under the
 * GNU General Public License or other free or open source software licenses.
 */


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.model');

class WebmapPlusModelCSS  extends JModel
{

	var $_data = null;

	var $_total = null;

	var $_pagination = null;

	function __construct()
	{
		parent::__construct();
	}

	function store()
	{	

		$template_path = COM_WEBMAPPLUS_CSS_PATH.DS.'style.css';
		$csscontent	 	= JRequest::getVar('csscontent', '', 'post', 'string', JREQUEST_ALLOWRAW);

		if( $fp = @fopen( $template_path, 'w' )) {
			fputs( $fp, stripslashes( $csscontent ) );
			fclose( $fp );
			return true;
		}else{
			return false;
		}

	}
}
