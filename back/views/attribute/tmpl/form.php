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
<?php JHTML::_('behavior.tooltip'); ?>
		<form action="index.php" method="post" name="adminForm">

		<div class="col80">
			<fieldset class="adminform">
				<legend><?php echo JText::_( 'Details' ); ?></legend>

					<table class="admintable">
						
					<tr>
						<td class="key">
							<label for="title" width="100">
								<?php echo JText::_( 'Name' ); ?>:
							</label>
						</td>
						<td colspan="2">
							<input class="text_area" type="text" name="name" id="name" value="<?php echo $this->attribute->name; ?>" size="50" maxlength="50" title="<?php echo JText::_( 'NAME_DESC' ); ?>" />
						</td>
					</tr>
					
					<tr>
						<td class="key">
							<label for="title" width="150">
								<?php echo JText::_( 'Type' ); ?>:
							</label>
						</td>
						<td colspan="2">
							<?php echo $this->lists['types']; ?>
						</td>
					</tr>
					
					<tr id="values-row" style="<?php echo (empty($this->attribute->type) || $this->attribute->type != "enum") ? "display: none" : "" ?>">
						<td class="key">
							<label for="title" width="150">
								<?php echo JText::_( 'Values' ); ?>:
							</label>
						</td>
						<td colspan="2">
							<input type="text" name="values" value="<?php echo $this->attribute->values; ?>" size="50" maxlength="255" title ="<?php echo JTEXT::_('VALUES_DESC'); ?>" />
						</td>
					</tr>
					

				</table>
			</fieldset>
			<fieldset class="adminform">
			<legend>
                <?php
                echo JText::_('Visibility Settings');
                ?>
            </legend>
            <table class="admintable">
                <tr>
                    <td width="150" align="right" class="key">
                        <label for="published">
                            <?php
                            echo JText::_('Published');
                            ?>
                            :
                        </label>
                    </td>
                    <td>
                        <?php
                        echo $this->lists['published'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <td width="150" align="right" class="key">
                        <label for="ordering">
                            <?php
                            echo JText::_('Order ');
                            ?>
                            :
                        </label>
                    </td>
                    <td>
                        <?php
                        echo $this->lists['ordering'];
                        ?>
                    </td>
                </tr>
            </table>
        </fieldset>
		<fieldset class="adminform">
			<legend>
                <?php
                echo JText::_('Display Settings');
                ?>
            </legend>
            <table class="admintable">
				<tr>
                    <td width="150" align="right" class="key">
                        <label for="published">
                            <?php
                            echo JText::_('Location Listing');
                            ?>
                            :
                        </label>
                    </td>
                    <td>
                        <?php
                        echo $this->lists['displayListing'];
                        ?>
                    </td>
                </tr>
				<tr>
                    <td width="150" align="right" class="key">
                        <label for="published">
                            <?php
                            echo JText::_('Balloon on Hover');
                            ?>
                            :
                        </label>
                    </td>
                    <td>
                        <?php
                        echo $this->lists['displayBHover'];
                        ?>
                    </td>
                </tr>
				<tr>
                    <td width="150" align="right" class="key">
                        <label for="published">
                            <?php
                            echo JText::_('Balloon on Click');
                            ?>
                            :
                        </label>
                    </td>
                    <td>
                        <?php
                        echo $this->lists['displayBClick'];
                        ?>
                    </td>
                </tr>
				<tr>
                    <td width="150" align="right" class="key">
                        <label for="published">
                            <?php
                            echo JText::_('Location Page');
                            ?>
                            :
                        </label>
                    </td>
                    <td>
                        <?php
                        echo $this->lists['displayPage'];
                        ?>
                    </td>
                </tr>
            </table>
        </fieldset>
		</div>
		<div class="clr"></div>

		<input type="hidden" name="option" value="com_webmapplus" />
		<input type="hidden" name="cid[]" value="<?php echo $this->attribute->id; ?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="controller" value="attribute" />
		<?php echo JHTML::_( 'form.token' ); ?>
		</form>