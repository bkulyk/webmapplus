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

class MarkerHelper
{
	static function createShadow($src, $skew_val)
	{
	  $imgsrc = $src;
	  $w = imagesx($imgsrc);
	  $h = imagesy($imgsrc);
	  $canvas = imagecreatetruecolor($w+($w*$skew_val),  $h);
	  $trans = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
	  imagefill($canvas, 0,0, $trans); 
	  $temp=0;
	  for($y=$h-1; $y>=0; $y--)
	   {
	    for($x=0 ; $x<$w ; $x++) 
	    {		
			$rgb = imagecolorat($imgsrc,$x,$y);
	  		$a = ($rgb >> 24)/127+.675;
			$a = $a > 1 ? 127 : $a*127;
			
			$rgb = imagecolorallocatealpha($canvas, 0, 0, 0, $a);
	        //imagecopy($canvas, $imgsrc, $x+$temp, $y, $x, $y, 1, 1);
			
			imagesetpixel($canvas,$x+$temp, $y, $rgb);
	      	imagecolortransparent($canvas,$trans);
	    }
	    $temp+=$skew_val;
	   }
	  
	  $final = imagecreatetruecolor($w+($w*$skew_val), $h);
	  $ftrans = imagecolorallocatealpha($final, 0, 0, 0, 127);
	  imagefill($final, 0,0, $ftrans); 
	  
	  imagecopyresampled($final, $canvas, 0, $h*.5, 0, 0, $w+($w*$skew_val), $h*.5, $w+($w*$skew_val), $h);
		
	  imagesavealpha($final, true);
	  	  
	  return $final;
	}
	
	static function createTransparent($src){
	  $w = imagesx($src);
	  $h = imagesy($src);
	  $canvas = imagecreatetruecolor($w,  $h);
	  $trans = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
	  imagefill($canvas, 0,0, $trans);
	   for($y=0; $y<$h; $y++)
	   {
	    for($x=0 ; $x<$w ; $x++) 
	    {		
			$rgb = imagecolorat($src,$x,$y);
	  		$a = ($rgb >> 24)/127;
			
			$a = ($a == 1) ? 127 : 126;
			$rgb = imagecolorallocatealpha($canvas, 0, 0, 0, $a);
	        //imagecopy($canvas, $imgsrc, $x+$temp, $y, $x, $y, 1, 1);
			
			imagesetpixel($canvas,$x, $y, $rgb);
	      	imagecolortransparent($canvas,$trans);
	    }
	   }
	   imagesavealpha($canvas, true);
	   return $canvas;
	}
	
	static function createPrint($src, $checker = false, $grey = false){
	  $w = imagesx($src);
	  $h = imagesy($src);
	  $canvas = imagecreatetruecolor($w,  $h);
	  $trans = imagecolorallocatealpha($canvas, 0, 255, 0, 127);
	  imagecolortransparent($canvas, $trans);
	  imagefill($canvas, 0,0, $trans);
	  imagecopy($canvas, $src, 0, 0, 0, 0, $w, $h);
	  
	  if($checker){
	  	$new = imagecreatetruecolor($w,  $h);
	    $trans = imagecolorallocatealpha($new, 0, 255, 0, 127);
	    imagecolortransparent($new, $trans);
	    imagefill($new, 0,0, $trans);
		
	  	for($x=0; $x < $w; $x++){
	  		for($y=0; $y < $h; $y++){
	  			if($y%2 == $x%2)
	  				imagesetpixel($new, $x, $y, $trans);
				else
					imagesetpixel($new, $x, $y, imagecolorat($canvas, $x, $y));
			}
	  	}
		$canvas = $new;
	  }
	  
	  if($grey){
		$new = imagecreatetruecolor($w,  $h);
		$grey = imagecolorallocatealpha($new, 228, 228, 228, 0);
		imagefill($new, 0,0, $grey);
		imagecopy($new, $canvas, 0, 0, 0, 0, $w, $h);
		$canvas = $new;
	  }
	  
	  
	  return $canvas;	
	}

	static function findBoundry($src){
		$w = imagesx($src);
		$h = imagesy($src);
		$maxs = array();
		$mins = array();
		for($y=0; $y < $h; $y++){
			$maxs[$y] = -1;
			$mins[$y] = -1; 
			for($x=0; $x < $w; $x++){
	  			$rgb = imagecolorat($src,$x,$y);
	  			if(($rgb >> 24) != 127){
	  				$maxs[$y] = $x;
					if($mins[$y] == -1)
						$mins[$y] = $x;
	  			}
			}
		}
		
		$coordinates = array();
		
		foreach($maxs as $y => $x){
			if($x != -1){
				$coordinates[] = $x;
				$coordinates[] = $y;
			}
		}
		
		$mins = array_reverse($mins, true);
		
		foreach($mins as $y => $x){
			if($x != -1){
				$coordinates[] = $x;
				$coordinates[] = $y;
			}
		}
		return $coordinates;
		
	}
	
	static function overlayImageMap($src, $map){
	  $w = imagesx($src);
	  $h = imagesy($src);
	  $canvas = imagecreatetruecolor($w,  $h);
	  $trans = imagecolorallocatealpha($canvas, 0, 255, 0, 127);
	  imagecolortransparent($canvas, $trans);
	  imagefill($canvas, 0,0, $trans);
	  imagecopy($canvas, $src, 0, 0, 0, 0, $w, $h);
	  imagefilledpolygon($canvas, $map, count($map)/2, imagecolorallocatealpha($canvas, 0, 255, 0, 127*.5));
	  return $canvas;
	}
	
}



?>