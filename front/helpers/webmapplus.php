<?php
/**
 * @package		WebmapPlus
 * @subpackage	Frontend Helpers
 * @copyright	Copyright (C) 2009 Accade LLC.
 * @license		GNU/GPL, see LICENSE.txt
 * This component is classified as derivative of Joomla! and as such this
 * version may have been modified pursuant to the GNU General Public License,
 * and as distributed it includes or is derivative of works licensed under the
 * GNU General Public License or other free or open source software licenses.
 */

if (!function_exists('json_decode')) {
    function json_decode($content, $assoc = false) {
        if (!class_exists("Services_JSON")) {
            require_once ("json.php");
        }
        
        if ($assoc) {
            $json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
        } else {
            $json = new Services_JSON;
        }
        return $json->decode($content);
    }
}

if (!function_exists('json_encode')) {
    function json_encode($content) {
        
        if (!class_exists("Services_JSON")) {
            require_once ("json.php");
        }
        
        $json = new Services_JSON;
        
        return $json->encode($content);
    }
}

class WebmapPlusHelper {
    static function getPointDistance($lat1, $lon1, $lat2, $lon2) {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        return $dist * 60 * 1.1515;
    }
    
    static function GeoCode($address, $returnBox = false) {
        $params = &JComponentHelper::getParams('com_webmapplus');
        $key = $params->get('gmaps_api_key', '');
        
        $request = "http://maps.google.com/maps/geo?q=".urlencode($address)."&key=$key";
        
        if(function_exists("curl_version")){
          $ch = curl_init();
          curl_setopt ($ch, CURLOPT_URL, $request);
          curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 0);
          $page = curl_exec($ch);
          curl_close($ch);
        }
        elseif(ini_get('allow_url_fopen') == 1){
          $page = file_get_contents($request);
        }
        else{
          echo "cURL is not installed and allow_url_fopen is false. Can not continue."; die();
          return false;        
        } 

        //Silly Google doesn't use UTF-8 Encoding
        $page = mb_convert_encoding($page, 'UTF-8', mb_detect_encoding($page, 'UTF-8, ISO-8859-1', true));
        $data = json_decode($page);
        
        if ($data->Status->code == "200") {
            if (!$returnBox)
                return $data->Placemark[0]->Point->coordinates;
            else
                return $data->Placemark[0]->ExtendedData->LatLonBox;
        } else
            return $data->Status->code;
    }
}
?>
