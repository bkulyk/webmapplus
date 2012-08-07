<?php
/**
 * @package   WebmapPlus
 * @subpackage  Frontend Views - Location
 * @copyright Copyright (C) 2009 Accade LLC.
 * @license   GNU/GPL, see LICENSE.txt
 * This component is classified as derivative of Joomla! and as such this 
 * version may have been modified pursuant to the GNU General Public License, 
 * and as distributed it includes or is derivative of works licensed under the 
 * GNU General Public License or other free or open source software licenses. 
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
?>
<link type="text/css" rel="stylesheet" href="<?php echo WEBMAPPLUS_ASSETS_URL;?>css/style.css"/>
<?php if ($this->params->get( 'show_page_title', 1)) : ?>
<div class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
  <?php echo $this->location->name; ?>
</div>
<?php endif; ?>
<div class="contentpane<?php echo $this->escape($this->params->get('pageclass_sfx')); ?> clearfix">
<div class="location-page clearfix">
  
  <?php
  if( basename( $this->location->photo_path ) != 'na.jpg' || !intval( $this->params->get( 'wmp_hide_no_pic', '0') ) ) { ?>
  <div class="location-photo">
    <img src="<?php echo $this->location->photo_path;?>"
         alt="<?php echo $this->location->name;?>" />
  </div>
  <?php } ?>
  
  <div class="location-details">
    <div class="location-address1"><?php echo $this->location->address1;?></div>
    
    <?php if(!empty($this->location->address2)):?>
      <div class="location-address2"><?php echo $this->location->address2;?></div>
    <?php endif; ?>
    
    <div class="location-cityZip">
      <?php echo $this->location->city.", ".$this->location->state." ".$this->location->zip."<br/>".$this->location->printable_name;?>
    </div>
  <div class="location-attributes"><ul><?php
    foreach($this->location->attributes as $attribute){
      $att = $this->generateAttribute($attribute);
      if(!empty($att) && $attribute->type != 'textarea')
      {
        echo "<li>".$att."</li>";
      }
    }
    ?></ul>
    <?php
    foreach($this->location->attributes as $attribute){
      $att = $this->generateAttribute($attribute);
      if(!empty($att) && $attribute->type == 'textarea')
      {
        echo $att;
      }
    }
    ?>
    </div>
 <div id="location-links">
  <a href="#" onclick="l.toggleDirections();return false;" onfocus="this.blur();" id="directions-link">Get Directions</a>
  <?php if(!empty($this->location->email)) { ?>- <a href="<?php echo JRoute::_('index.php?option=com_webmapplus&view=location&id='.$this->location->id.'&layout=contact_form&tmpl=component');?>" class="modal" rel="{size: {x: 400, y: 300}}">Send Email</a> <?php } ?>
  <?php if($this->params->get( 'location_page_street_view', 1 ) == 1) { ?> - <a href="#" onclick="toggleStreetView(); return false;" id="sv-toggle" onfocus="this.blur();">Street View</a>
 <?php } else {} ?>
  <?php if(($this->params->get( 'staticDir', 0) == 1) && ($this->params->get( 'staticDirAddr', "") != "")) { ?> - <a href="#" onclick="staticDirections(); return false;" id="staticDir-toggle" onfocus="this.blur();">Directions to <?php echo $this->params->get( 'staticDirLabel', ""); ?></a>
 <?php }?>
 </div>
 
 <div id="location-directions">
  <div id="directions-mode-toggle">
    <span id="dir-mode-inactive">To Here</span> - 
    <span id="dir-mode-active"><a href="#" onclick="l.toggleDirectionsMode(); return false;" id="dir-mode-link">From Here</a></span>
  </div>
  <div class="directions-label" id="directions-label-start">Start Address</div>
  <div class="directions-label" id="directions-label-end">End Address</div>
  <div class="directions-fields">
    <form action="#" method="GET" onSubmit="setDirections(this); return false;" id="dir-form">
      <input type="text" id="dir-text" name="from" value="" autocomplete="off"/><input type="submit" value="Go"/>
      <input type="hidden" id="dir-hidden" name="to" value="
      <?php   echo $this->location->address1.' ';
          echo !empty($this->location->address2) ? $this->location->address2.' ' : ' ';
          echo ' '.$this->location->city.", ".$this->location->state.", ".$this->location->zip.", ".$this->location->printable_name;
      ?>" />
    </form>
  </div>
  <div class="directions-example">(e.g. 1 Infinite Loop, Cupertino CA 95014)</div>
 </div>
 
  
</div>
    </div>
    
  <?php if( intval( $this->params->get( 'location_page_street_view', 0 ) ) ) { ?>
    <div id="gmap-sv" style="width: 100%; height: 450px; background-color:#E5E3DF">
      <div id="gmap" style="width: 100%; height: 100%; border: 1px solid #CCC;">
      </div>
      <div id="sv" style="width: 100%; height: 100%; display: none">
      </div>
      <a href="#" onClick="toggleStreetView(); return false;" onfocus="this.blur();" id="sv-close">Close</a>
    </div>
  <?php } ?>
  
  <div id="gmap-directions">
  </div>
  
</div>
<script src="http://maps.google.com/maps?file=api&v=2s&key=<?php echo $this->key; ?>" type="text/javascript"></script>
<script src="<?php echo WEBMAPPLUS_ASSETS_URL;?>js/location.js" type="text/javascript"></script>
<script type="text/javascript">
var l;
Window.onDomReady(function(){
  l = new LocationMap({
  lat: <?php echo $this->location->lat;?>,
  lng: <?php echo $this->location->long;?>,
  pano: "<?php echo $this->location->pano;?>",
  markerPath: <?php echo '"'.$this->location->markerUrl.'"';?>, 
  uiOpts : {
      basicMap: "<?php echo $this->params->get( 'map_basic_map', 1 ); ?>",
      satelliteMap: "<?php echo $this->params->get( 'map_satellite_map', 1 ); ?>",
      hybridMap: "<?php echo $this->params->get( 'map_hybrid_map', 1 ); ?>",
      physicalMap: "<?php echo $this->params->get( 'map_physical_map', 0 ); ?>",
      scrollWheel: "<?php echo $this->params->get( 'map_scroll_wheel', 1 ); ?>",
      mapControls: "<?php echo $this->params->get( 'map_controls', 'auto' ); ?>",
      scalecontrol: "<?php echo $this->params->get( 'map_scalecontrol', 1 ); ?>",
      panorma: "<?php echo $this->params->get( 'panoramio_layer', 1 ); ?>",
      wikipedia: "<?php echo $this->params->get( 'wikipedia_layer', 1 ); ?>",
      wikipediaZoomLevel: "<?php echo $this->params->get( 'wikipedia_zoom_layer', 15 ); ?>"
    }});});

function setDirections(form){l.setDirections(form.from.value, form.to.value);}
function toggleStreetView(){l.toggleStreetView();}
function staticDirections(){l.setDirections('<?php  echo $this->location->address1.' ';
          echo !empty($this->location->address2) ? $this->location->address2.' ' : ' ';
          echo ' '.$this->location->city.", ".$this->location->state.", ".$this->location->zip.", ".$this->location->printable_name;
      ?>','<?php echo $this->params->get( 'staticDirAddr', ''); ?>');}
</script>
<script type="text/javascript">
function sendForm(form){
    var resp = $('contact_res').empty().addClass('ajax-loading').setStyle('display', 'block');
  try {
    form.send({
      method: 'post',
      noCache: 'true',
      update: resp,
      onComplete: function(){
        resp.removeClass('ajax-loading');
        $('sbox-window').setStyle('display', 'none');
        $('sbox-overlay').setStyle('display', 'none');
      }
    });
  }
  catch(err){
    resp.innerHTML = 'Error when trying to submit the form: ' + err;
    $('sbox-window').setStyle('display', 'none');
    $('sbox-overlay').setStyle('display', 'none');
  }
}
</script>