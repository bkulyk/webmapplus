<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php JHTML::_('behavior.tooltip'); ?>
<form action="index.php" method="post" enctype="multipart/form-data" name="adminForm" id="adminForm">
	<p>This wizard will automatically create a shadow and other images necessary for it to be display on Google Maps</p>
	<p>The marker must be in PNG format with a transparent background, and no larger than 200 pixels wide by 200 pixels high.</p>
	<div class="col80">
		<fieldset>
			<table class="admintable">
				<tr>
				<td class="key">
					<label for="title" width="100">
						<?php echo JText::_( 'Name' ); ?>:
					</label>
				</td>
				<td colspan="3">
					<input type="text" name="name" id="name" title="<?php echo JText::_( 'Marker_Name_DESC' ); ?>" />
				</td>
			</tr>
			<tr>
				<td class="key">
					<label for="title" width="100">
						<?php echo JText::_( 'Image' ); ?>:
					</label>
				</td>
				<td colspan="3">
					<input type="file" name="marker" id="marker" title="<?php echo JText::_( 'Image_DESC' ); ?>" />
				</td>
			</tr>
			<tr>
				<td class="key">
					<label for="title" width="100">
						<?php echo JText::_( 'Select Anchor Point' )?>:
						<br /><small>(This is where Google Maps should anchor the marker to the map)</small>
					</label>
				</td>
				<td style="text-align: center">
					<div><img src="<?php echo COM_WEBMAPPLUS_ASSETS_URL;?>/images/anchorLeft.gif" alt="Left" /></div>
					<input type="radio" name="anchorPoint" value="left" />
				</td>
				<td style="text-align: center">
					<div><img src="<?php echo COM_WEBMAPPLUS_ASSETS_URL;?>/images/anchorMiddle.gif" alt="Center" /></div>
					<input type="radio" name="anchorPoint" value="middle" />
				</td>
				<td style="text-align: center">
					<div><img src="<?php echo COM_WEBMAPPLUS_ASSETS_URL;?>/images/anchorRight.gif" alt="Right" /></div>
					<input type="radio" name="anchorPoint" value="right" />
				</td>
			</tr>
			</table>
		</fieldset>
		<div class="clr"></div>
	</div>
	<input type="hidden" name="option" value="com_webmapplus" />
	<input type="hidden" name="cid[]" value="<?php echo $this->marker->id; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="view" value="marker" />
	<input type="hidden" name="layout" value="wizard2" />
	<input type="hidden" name="controller" value="marker" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>