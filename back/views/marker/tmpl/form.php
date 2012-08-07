<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php JHTML::_('behavior.tooltip'); ?>
		<form action="index.php" method="post" name="adminForm">

		<div class="col80">
			<fieldset class="adminform">
				<legend><?php echo JText::_( 'Details' ); ?></legend>

					<table class="admintable">
					<tr><td colspan="3">Markers should come in 27 variations, one blank and one for each letter of the alphabet.<br />Files should be named with the marker name followed by a letter (e.g. blue_markerA.png for the letter A, and blue_marker.png for blank) and be uploaded to the /components/com_webmapplus/assets/images/markers/ directory.</td></tr>
					<tr>
						<td class="key">
							<label for="title" width="100">
								<?php echo JText::_( 'Name' ); ?>:
							</label>
						</td>
						<td colspan="2">
							<input class="text_area" type="text" name="name" id="name" value="<?php echo $this->marker->name; ?>" size="50" maxlength="50" title="<?php echo JText::_( 'Name_DESC' ); ?>" />
						</td>
					</tr>
					
					<tr>
						<td class="key">
							<label for="title" width="150">
								<?php echo JText::_( 'Anchor Point' ); ?>:
							</label>
						</td>
						<td colspan="2">
							<?php
                                echo $this->lists['anchorPoint'];
                             ?>
						</td>
					</tr>
					

				</table>
			</fieldset>
		</div>
		<div class="clr"></div>

		<input type="hidden" name="option" value="com_webmapplus" />
		<input type="hidden" name="file_name" value="<?php echo $this->marker->file_name; ?>" />
		<input type="hidden" name="cid[]" value="<?php echo $this->marker->id; ?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="controller" value="marker" />
		<?php echo JHTML::_( 'form.token' ); ?>
		</form>