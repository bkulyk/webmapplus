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

defined('_JEXEC') or die ('Restricted access');

jimport('joomla.application.component.controller');
?>
<form action="index.php" enctype="multipart/form-data" method="post" name="adminForm" id="adminForm">
    <div>
            <table cellspacing="0" cellpadding="0" border="0" width="100%">
                <tr>
                    <td valign="top">
                    	<fieldset class="adminform">
                    <legend>
                        <?php
                        echo JText::_('Location Details');
                        ?>
                    </legend>
                    <legend>
                        <?php
                        echo JText::_('Basic Information');
                        ?>
                    </legend>
                    <table class="admintable">
                        <tr>
                            <td width="100" align="right" class="key">
                                <label for="greeting">
                                    <?php
                                    echo JText::_('Name');
                                    ?>
                                    :
                                </label>
                            </td>
                            <td>
                                <input class="text_area" type="text" name="name" id="name" size="55" maxlength="255" title="<?php echo JText::_( 'NAME_DESC' ); ?>" value="<?php echo $this->location->name;?>" />
                            </td>
                        </tr>
                        <tr>
                            <td width="100" align="right" class="key">
                                <label for="greeting">
                                    <?php
                                    echo JText::_('Address 1');
                                    ?>
                                    :
                                </label>
                            </td>
                            <td>
                                <input class="text_area" type="text" name="address1" id="address1" size="55" maxlength="128" value="<?php echo $this->location->address1;?>" />
                            </td>
                        </tr>
                        <tr>
                            <td width="100" align="right" class="key">
                                <label for="greeting">
                                    <?php
                                    echo JText::_('Address 2');
                                    ?>
                                    :
                                </label>
                            </td>
                            <td>
                                <input class="text_area" type="text" name="address2" id="address2" size="55" maxlength="128" value="<?php echo $this->location->address2;?>" />
                            </td>
                        </tr>
                        <tr>
                            <td width="100" align="right" class="key">
                                <label for="greeting">
                                    <?php
                                    echo JText::_('City/Town/Suburb');
                                    ?>
                                    :
                                </label>
                            </td>
                            <td>
                                <input class="text_area" type="text" name="city" id="city" size="55" maxlength="128" value="<?php echo $this->location->city;?>" />
                            </td>
                        </tr>
                        <tr>
                            <td width="100" align="right" class="key">
                                <label for="greeting">
                                    <?php
                                    echo JText::_('State/Providence');
                                    ?>
                                    :
                                </label>
                            </td>
                            <td>
                                <input class="text_area" type="text" name="state" id="state" size="55" maxlength="128" value="<?php echo $this->location->state;?>" />
                            </td>
                        </tr>
                        <tr>
                            <td width="100" align="right" class="key">
                                <label for="greeting">
                                    <?php
                                    echo JText::_('Postal Code');
                                    ?>
                                    :
                                </label>
                            </td>
                            <td>
                                <input class="text_area" type="text" name="zip" id="zip" size="9" maxlength="9" value="<?php echo $this->location->zip;?>" />
                            </td>
                        </tr>
                        <tr>
                            <td width="100" align="right" class="key">
                                <label for="greeting">
                                    <?php
                                    echo JText::_('Country');
                                    ?>
                                    :
                                </label>
                            </td>
                            <td>
                                <?php
                                echo $this->lists['country'];
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="100" align="right" class="key">
                                <label for="greeting">
                                    <?php
                                    echo JText::_('Email');
                                    ?>
                                    :
                                </label>
                            </td>
                            <td>
                                <input class="text_area" type="text" name="email" id="email" size="25" maxlength="255" value="<?php echo $this->location->email;?>" />
                            </td>
                        </tr>
						<tr>
                            <td width="100" align="right" class="key">
                                <label for="category">
                                    <?php
                                    echo JText::_('Category');
                                    ?>
                                    :
                                </label>
                            </td>
                            <td>
                                <?php
                                echo $this->lists['category'];
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="100" align="right" class="key">
                                <label for="photo">
                                    <?php
                                    echo JText::_('Photo');
                                    ?>
                                    :
                                </label>
                            </td>
                            <td>
                                <?php
                                if ( isset ($this->location->photo_path))
                                {
                                ?>
                                <img alt="<?php echo $this->location->name;?>" title="<?php echo $this->location->name;?>" src="<?php echo COM_WEBMAPPLUS_MEDIA_BASEURL; ?>/<?php echo $this->location->photo_path;?>" width="80px" height="60px">
                                </img>
                                <?php
                                }
                                ?>
                                <input class="text_area" type="file" name="photo_path" id="photo_path" size="55" value="<?php echo $this->location->photo_path;?>" />
                            </td>
                        </tr>
                    </table>
                    <legend>
                        <?php
                        echo JText::_('Attributes');
                        ?>
                    </legend>
                    <table class="admintable">
                        <?php
                        if (count($this->attributes) == 0):
                        ?>
                        <tr style="min-height: 25px; height: 25px;">
                            <td>
                            </td>
                        </tr>
                        <?php
                        endif;
                        ?>
                        <?php
                        foreach ($this->attributes as $attribute)
                        {
                            $aid = $attribute->id;
                        ?>
                        <tr>
                            <td width="100" align="right" class="key">
                                <label for="<?php echo 'attr_'.$aid; ?>">
                                    <?php
                                    echo $attribute->name;
                                    ?>
									:
                                </label>
                            </td>
                            <td>
                                <?php
                                $value = isset ($this->location->attributes->$aid)?$this->location->attributes->$aid->value:'';
                                $nameTrailer = isset ($this->location->attributes->$aid)?'_'.$this->location->attributes->$aid->id:'';
                                echo $this->generateAttrField('attr_'.$aid.$nameTrailer, $attribute->type, $value, explode('|', $attribute->values));
                                ?>
                            </td>
                        </tr>
                        <?php
                        }
                        ?>
                    </table>
                    <legend>
                        <?php
                        echo JText::_('Advanced Settings');
                        ?>
                    </legend>
                    <table class="admintable">
                        <tr>
                            <td width="100" align="right" class="key">
                                <label for="markerIcons">
                                    <?php
                                    echo JText::_('Marker Icon');
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
                        <tr>
                            <td width="100" align="right" class="key">
                                <label for="Latitude">
                                    <?php
                                    echo JText::_('Latitude - Auto Generated');
                                    ?>
                                    :
                                </label>
                            </td>
                            <td>
                                <input class="text_area" type="text" name="lat" id="lat" size="12" maxlength="11" value="<?php echo $this->location->lat;?>" />
                            </td>
                        </tr>
                        <tr>
                            <td width="100" align="right" class="key">
                                <label for="Longitude">
                                    <?php
                                    echo JText::_('Longitude - Auto Generated');
                                    ?>
                                    :
                                </label>
                            </td>
                            <td>
                                <input class="text_area" type="text" name="long" id="long" size="12" maxlength="11" value="<?php echo $this->location->long;?>" />
                            </td>
                        </tr>
						<tr>
                            <td width="100" align="right" class="key">
                                <label for="pano">
                                    <?php
                                    echo JText::_('Custom Panorama (Replaces Street View)');
                                    ?>
                                    :
                                </label>
                            </td>
                            <td>
                                <input class="text_area" type="text" name="pano" id="pano" size="25" maxlength="255" value="<?php echo $this->location->pano;?>" />
                            </td>
                        </tr>
                    </table>
                    <legend>
                        <?php
                        echo JText::_('Visibility Settings');
                        ?>
                    </legend>
                    <table class="admintable">
                        <tr>
                            <td width="100" align="right" class="key">
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
                            <td width="100" align="right" class="key">
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
                </td>
                <td valign="top" width="350" style="padding: 7px 0 0 5px">
                    <?php
                    jimport('joomla.html.pane');
                    $pane = & JPane::getInstance('sliders', array ('allowAllClose'=>true));
                    echo $pane->startPane("content-pane");
                    $title = JText::_('Metadata Information');
                    echo $pane->startPanel($title, "metadata-page");
                    ?>
                    <table class="admintable">
                        <tr>
                            <td width="100" align="right" class="key">
                                <label for="description">
                                    <?php
                                    echo JText::_('Description');
                                    ?>
                                    :
                                </label>
                            </td>
                            <td>
                            	<textarea class="text_area" name="description" id="description" rows="2" cols="30"><?php echo $this->location->description;?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td width="100" align="right" class="key">
                                <label for="keywords">
                                    <?php
                                    echo JText::_('Keywords');
                                    ?>
                                    :
                                </label>
                            </td>
                            <td>
                                <textarea class="text_area" name="keywords" id="keywords" rows="2" cols="30"><?php echo $this->location->keywords;?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td width="100" align="right" class="key">
                                <label for="robots">
                                    <?php
                                    echo JText::_('Robots');
                                    ?>
                                    :
                                </label>
                            </td>
                            <td>
                                <input class="text_area" type="text" name="robots" id="robots" size="55" maxlength="255" value="<?php echo $this->location->robots;?>" />
                            </td>
                        </tr>
                        <tr>
                        <td width="100" align="right" class="key">
                            <label for="author">
                                <?php
                                echo JText::_('Author');
                                ?>
                                :
                            </label>
                        </td>
                        <td>
                            <input class="text_area" type="text" name="author" id="author" size="55" maxlength="255" value="<?php echo $this->location->author;?>" />
                        </td>
                        </td>
                    </tr>
                    </table>
                    <?php
                    echo $pane->endPanel();
                    echo $pane->endPane();
                    ?>
                </td>
				</fieldset>
                </tr>
            </table>
    </div>
    <div class="clr">
    </div>
    <input type="hidden" name="option" value="com_webmapplus" /><input type="hidden" name="cid[]" value="<?php echo $this->location->id; ?>" /><input type="hidden" name="task" value="" /><input type="hidden" name="controller" value="location" />
    <?php
    echo JHTML::_('form.token');
    ?>
</form>