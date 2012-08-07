<?php
defined('_JEXEC') or die();

class JElementWebmapPlusHead extends JElement
{
	var	$_name = 'WebmapPlusHead';

	function fetchTooltip($label, $description, &$node, $control_name, $name) {
		return '&nbsp;';
	}

	function fetchElement($name, $value, &$node, $control_name)
	{
		if ($value) {
			return '<p style="background: #CCE6FF;color: #0069CC;padding:5px"><strong>' . JText::_($value) . '</strong></p>';
		} else {
			return '<hr />';
		}
	}
}
?>