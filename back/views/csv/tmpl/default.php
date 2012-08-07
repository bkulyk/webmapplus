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


defined('_JEXEC') or die('Restricted access'); ?>
<form action="index.php" enctype="multipart/form-data" method="post" name="adminForm">
    <fieldset class="adminform">
        <legend>
            <?php
            echo JText::_('Upload CSV File');
            ?>
        </legend>
        <table class="admintable">
            <tr>
                <td width="100" align="right" class="key">
                    <label for="csv_file">
                        <?php
                        echo JText::_('CSV File');
                        ?>
                        :
                    </label>
                </td>
                <td>
                    <input class="file" type="file" name="csv_file" id="csv_file" size="55" value="" />
                </td>
            </tr>
        </table>
    </fieldset>
	<input type="hidden" name="option" value="com_webmapplus" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="controller" value="csv" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
<fieldset class="adminform">
<legend><?php echo JText::_( 'What should my CSV File look like?' ); ?></legend>
<?php echo JText::_( 'Name,Address1,Address2,City,State,Zip_Postal_Code,Email,ISO_Country_Code,Author,Keywords,Robots,Description,Category,Published');
foreach($this->allAttribs as $attrib): echo ",".$attrib->name; endforeach; ?><br/>
<?php
$count = 0;
foreach($this->data as $row):
?>
	<?php echo trim($row->name); ?>,
	<?php echo trim($row->address1); ?>,
	<?php echo trim($row->address2); ?>,
	<?php echo trim($row->city); ?>,
	<?php echo trim($row->state); ?>,
	<?php echo trim($row->zip); ?>,
	<?php echo trim($row->email); ?>,
	<?php echo trim($row->country_code); ?>,
	<?php echo trim($row->author); ?>,
	<?php echo trim($row->keywords); ?>,
	<?php echo trim($row->robots); ?>,
	<?php echo trim($row->description); ?>,
	<?php echo trim($row->category); ?>,
	<?php echo trim($row->published); ?>
	<?php foreach($row->attributes as $attrib): echo ",".trim(strip_tags($attrib->value)); endforeach; ?>
	<br/>
	<?php $count++; if($count >= 2){break;} ?>
<?php endforeach; ?>
<?php
$sample = COM_WEBMAPPLUS_CSV_PATH."sampleCSVForWebmapPlus.csv";
$fh = fopen($sample, 'w');

if ($fh == false)
{
    echo JText::_("<p>Unable to create Sample CSV File for download, please reset file permissions for this directory:\"".COM_WEBMAPPLUS_CSV_PATH."\".</p>");
}
else
{
    $stringData = JText::_("Name,Address1,Address2,City,State,Zip_Postal_Code,Email,ISO_Country_Code,Author,Keywords,Robots,Description,Category,Published");
    foreach ($this->allAttribs as $attrib):
        $stringData = $stringData.",".$attrib->name;
    endforeach;
    $stringData = $stringData."\n";
    $count = 0;
    foreach ($this->data as $row):
        $stringData = $stringData.trim($row->name).",";
        $stringData = $stringData.trim($row->address1).",";
        $stringData = $stringData.trim($row->address2).",";
        $stringData = $stringData.trim($row->city).",";
        $stringData = $stringData.trim($row->state).",";
        $stringData = $stringData.trim($row->zip).",";
        $stringData = $stringData.trim($row->email).",";
        $stringData = $stringData.trim($row->country_code).",";
		$stringData = $stringData.trim($row->author).",";
		$stringData = $stringData.trim($row->keywords).",";
		$stringData = $stringData.trim($row->robots).",";
		$stringData = $stringData.trim($row->description).",";
		$stringData = $stringData.trim($row->category).",";
        $stringData = $stringData.trim($row->published);
        foreach ($row->attributes as $attrib):
            $stringData = $stringData.",".trim(strip_tags($attrib->value));
        endforeach;
        $stringData = $stringData."\n";
    endforeach;
    fwrite($fh, $stringData);
    fclose($fh);
	echo "<a href='".COM_WEBMAPPLUS_CSV_URL."sampleCSVForWebmapPlus.csv'>Download This Sample</a>";
}
?>
</fieldset>
