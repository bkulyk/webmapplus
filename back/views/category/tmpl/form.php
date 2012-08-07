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

defined('_JEXEC') or die('Restricted access');
?>
<?php JHTML::_('behavior.tooltip'); ?>
<?php JHTML::_('behavior.mootools'); ?>
<form action="index.php" method="post" name="adminForm">
    <div class="col80">
        <fieldset class="adminform">
            <legend>
                <?php echo JText::_('Details'); ?>
            </legend>
            <table class="admintable">
                <tr>
                    <td class="key">
                        <label for="title" width="100">
                            <?php echo JText::_('Name'); ?>
                            :
                        </label>
                    </td>
                    <td colspan="2">
                        <input class="text_area" type="text" name="name" id="name" value="<?php echo $this->category->name; ?>" size="50" maxlength="50" title="<?php echo JText::_( 'NAME_DESC' ); ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="key">
                        <label for="alias" width="100">
                            <?php echo JText::_('Alias'); ?>
                            :
                        </label>
                    </td>
                    <td colspan="2">
                        <input class="text_area" type="text" name="alias" id="alias" value="<?php echo $this->category->alias; ?>" size="50" maxlength="50" title="<?php echo JText::_( 'Alias DESC' ); ?>" />
                    </td>
                </tr>
                <tr>
                    <td width="150" align="right" class="key">
                        <label for="parent">
                            <?php
                            echo JText::_('Parent Category');
                            ?>
                            :
                        </label>
                    </td>
                    <td>
                        <?php
                        echo $this->lists['parent'];
                        ?>
                    </td>
                </tr>
                <link type="text/css" href="<?php echo COM_WEBMAPPLUS_MOORAINBOW_URL; ?>mooRainbow.css" rel="stylesheet"/>
                <script type="text/javascript" src="<?php echo COM_WEBMAPPLUS_MOORAINBOW_URL; ?>mooRainbow.js">
                </script>
                <script type="text/javascript">
                    window.addEvent('domready', function(){
                        new MooRainbow('myRainbow', {
                            id: 'colorPicker',
                            wheel: true,
                            imgPath: "<?php echo COM_WEBMAPPLUS_MOORAINBOW_URL; ?>images/",
                            'onChange': function(color){
                                $('highlightColor').value = color.hex;
                            }
                        });
                    });
                </script>
                <tr>
                    <td width="150" align="right" class="key">
                        <label for="highlight_color">
                            <?php
                            echo JText::_('Highlight Color');
                            ?>
                            :
                        </label>
                    </td>
                    <td>
                        <img width="16" height="16" id="myRainbow" alt="[r]" src="<?php echo COM_WEBMAPPLUS_MOORAINBOW_URL; ?>images/rainbow.png"/><input type="text" size="13" name="highlight_color" id="highlightColor" value="<?php echo $this->category->highlight_color; ?>">
                    </td>
                </tr>
				<tr>
                    <td width="150" align="right" class="key">
                        <label for="marker">
                            <?php
                            echo JText::_('Default Marker');
                            ?>
                            :
                        </label>
                    </td>
                    <td>
                        <?php
                        echo $this->lists['markerIcons'];
                        ?>
                    </td>
                </tr>
            </table>
        </fieldset>
        <fieldset class="adminform">
            <legend>
                <?php
                echo JText::_('Settings');
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
    </div>
    <div class="clr">
    </div>
    <input type="hidden" name="option" value="com_webmapplus" /><input type="hidden" name="cid[]" value="<?php echo $this->category->id; ?>" /><input type="hidden" name="task" value="" /><input type="hidden" name="controller" value="category" />
    <?php echo JHTML::_('form.token'); ?>
</form>