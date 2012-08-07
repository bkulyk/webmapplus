<div id="location-contact">
	<div class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
		Send Email - <?php echo $this->location->name; ?>
	</div>
	<div class="contentpane<?php echo $this->escape($this->params->get('pageclass_sfx')); ?> clearfix">
		<div id="contact_res"></div>
		<form id="location-contact-form" name="contact_webmapplus" method="get" onsubmit="sendForm(this); return false;" action="index2.php">
			<div class="form-row clearfix"><label>Name:</label><input name="contact_name" type="text" value="<?php echo isset($_POST['name']); ?>"></input></div>
			<div class="form-row clearfix"><label>Email:</label><input name="contact_email" type="text" value="<?php echo isset($_POST['email']); ?>"></input></div>
			<div class="form-row clearfix"><label>Message:</label><textarea name="contact_message" rows="5" cols="15" id="webmapplus_message"><?php echo isset($_POST['webmapplus_message']); ?></textarea></div>
			<div class="form-row clearfix"><input id="submit_contact" value="Send" type="submit" class="submit"/></div>
			<input type="hidden" name="cid"	value="<?php echo $this->location->id; ?>" />
			<input type="hidden" name="tmpl" value="component" />
			<input type="hidden" name="option" value="com_webmapplus" />
			<input type="hidden" name="task" value="sendEmail" />
			<input type="hidden" name="controller" value="webmapplus" />
			<?php echo JHTML::_( 'form.token' ); ?>
		</form>
	</div>
</div>