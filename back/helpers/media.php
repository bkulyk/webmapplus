<?php
/**
 * @package		WebmapPlus
 * @subpackage	Backend Helpers
 * @copyright	Copyright (C) 2009 Accade LLC.
 * @license		GNU/GPL, see LICENSE.txt
 * This component is classified as derivative of Joomla! and as such this
 * version may have been modified pursuant to the GNU General Public License,
 * and as distributed it includes or is derivative of works licensed under the
 * GNU General Public License or other free or open source software licenses.
 */

class MediaHelper
{
    /**
     * Checks if the file is an image
     * @param string The filename
     * @return boolean
     */
    function isImage($fileName)
    {
        static $imageTypes = 'xcf|odg|gif|jpg|png|bmp';
        return preg_match("/$imageTypes/i", $fileName);
    }

    /**
     * Checks if the file is an image
     * @param string The filename
     * @return boolean
     */
    function getTypeIcon($fileName)
    {
        // Get file extension
        return strtolower(substr($fileName, strrpos($fileName, '.')+1));
    }

    /**
     * Checks if the file can be uploaded
     *
     * @param array File information
     * @param string An error message to be returned
     * @return boolean
     */
    function canUpload($file, & $err)
    {

        if ( empty($file['name']))
        {
            return false;
        }

        jimport('joomla.filesystem.file');
        if ($file['name'] !== JFile::makesafe($file['name']))
        {
            return false;
        }

        $format = strtolower(JFile::getExt($file['name']));
        $allowable = array ('jpg', 'png', 'jpeg');
        $ignore = array ();
        if (!in_array($format, $allowable) && !in_array($format, $ignored))
        {
            $err = JText::_('Warning: File is a wrong type, please upload a jpg, jpeg, or png');
            return false;
        }

        $maxSize = 2097152;
        if ($maxSize > 0 && (int)$file['size'] > $maxSize)
        {
            $err = JText::_('Warning: File is too large - Max upload size is 2MB');
            return false;
        }

        $user = JFactory::getUser();
        $imginfo = null;
        if (false && $params->get('restrict_uploads', 1))
        {
            $images = explode(',', $params->get('image_extensions'));
            if (in_array($format, $images))
            { // if its an image run it through getimagesize
                if (($imginfo = getimagesize($file['tmp_name'])) === FALSE)
                {
                    return false;
                }
            } else if (!in_array($format, $ignored))
            {
                // if its not an image...and we're not ignoring it
                $allowed_mime = explode(',', $params->get('upload_mime'));
                $illegal_mime = explode(',', $params->get('upload_mime_illegal'));
                if (function_exists('finfo_open') && false && $params->get('check_mime', 1))
                {
                    // We have fileinfo
                    $finfo = finfo_open(FILEINFO_MIME);
                    $type = finfo_file($finfo, $file['tmp_name']);
                    if (strlen($type) && !in_array($type, $allowed_mime) && in_array($type, $illegal_mime))
                    {
                        return false;
                    }
                    finfo_close($finfo);
                } else if (function_exists('mime_content_type') && false && $params->get('check_mime', 1))
                {
                    // we have mime magic
                    $type = mime_content_type($file['tmp_name']);
                    if (strlen($type) && !in_array($type, $allowed_mime) && in_array($type, $illegal_mime))
                    {
                        return false;
                    }
                } else if (!$user->authorize('login', 'administrator'))
                {
                    return false;
                }
            }
        }

        $xss_check = JFile::read($file['tmp_name'], false, 256);
        $html_tags = array ('abbr', 'acronym', 'address', 'applet', 'area', 'audioscope', 'base', 'basefont', 'bdo', 'bgsound', 'big', 'blackface', 'blink', 'blockquote', 'body', 'bq', 'br', 'button', 'caption', 'center', 'cite', 'code', 'col', 'colgroup', 'comment', 'custom', 'dd', 'del', 'dfn', 'dir', 'div', 'dl', 'dt', 'em', 'embed', 'fieldset', 'fn', 'font', 'form', 'frame', 'frameset', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'head', 'hr', 'html', 'iframe', 'ilayer', 'img', 'input', 'ins', 'isindex', 'keygen', 'kbd', 'label', 'layer', 'legend', 'li', 'limittext', 'link', 'listing', 'map', 'marquee', 'menu', 'meta', 'multicol', 'nobr', 'noembed', 'noframes', 'noscript', 'nosmartquotes', 'object', 'ol', 'optgroup', 'option', 'param', 'plaintext', 'pre', 'rt', 'ruby', 's', 'samp', 'script', 'select', 'server', 'shadow', 'sidebar', 'small', 'spacer', 'span', 'strike', 'strong', 'style', 'sub', 'sup', 'table', 'tbody', 'td', 'textarea', 'tfoot', 'th', 'thead', 'title', 'tr', 'tt', 'ul', 'var', 'wbr', 'xml', 'xmp', '!DOCTYPE', '!--');
        foreach ($html_tags as $tag)
        {
            // A tag is '<tagname ', so we need to add < and a space or '<tagname>'
            if (stristr($xss_check, '<'.$tag.' ') || stristr($xss_check, '<'.$tag.'>'))
            {
                return false;
            }
        }
        return true;
    }

    function parseSize($size)
    {
        if ($size < 1024)
        {
            return $size.' bytes';
        }
        else
        {
            if ($size >= 1024 && $size < 1024*1024)
            {
                return sprintf('%01.2f', $size/1024.0).' Kb';
            } else
            {
                return sprintf('%01.2f', $size/(1024.0*1024)).' Mb';
            }
        }
    }

    function countFiles($dir)
    {
        $total_file = 0;
        $total_dir = 0;

        if (is_dir($dir))
        {
            $d = dir($dir);

            while (false !== ($entry = $d->read()))
            {
                if (substr($entry, 0, 1) != '.' && is_file($dir.DIRECTORY_SEPARATOR.$entry) && strpos($entry, '.html') === false && strpos($entry, '.php') === false)
                {
                    $total_file++;
                }
                if (substr($entry, 0, 1) != '.' && is_dir($dir.DIRECTORY_SEPARATOR.$entry))
                {
                    $total_dir++;
                }
            }

            $d->close();
        }

        return array ($total_file, $total_dir);
    }

    function createthumb($name, $filename, $new_w, $new_h)
    {
        $system = explode(".", $name);
        $count = count($system);

        if (preg_match("/jpg|jpeg/", $system[$count-1]))
        {
            $src_img = imagecreatefromjpeg($name);
        }
        if (preg_match("/png/", $system[$count-1]))
        {
            $src_img = imagecreatefrompng($name);
        }
        $old_x = imageSX($src_img);
        $old_y = imageSY($src_img);


        if ($width == 0)
        {
            $factor = $new_h/$old_y;
        }
        elseif ($height == 0)
        {
            $factor = $new_w/$old_x;
        }
        else
        {
            $factor = min($new_w/$old_x, $new_h/$old_y);
        }

        $thumb_w = round($old_x*$factor);
        $thumb_h = round($old_y*$factor);

        $dst_img = ImageCreateTrueColor($thumb_w, $thumb_h);
        imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $thumb_w, $thumb_h, $old_x, $old_y);
        if (preg_match("/png/", $system[1]))
        {
            imagepng($dst_img, $filename);
        } else
        {
            imagejpeg($dst_img, $filename);
        }
        imagedestroy($dst_img);
        imagedestroy($src_img);
    }



}
