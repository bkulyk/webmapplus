<?php
/**
 * @package		WebmapPlus
 * @subpackage	Frontend Views - Multimap
 * @copyright	Copyright (C) 2009 Accade LLC.
 * @license		GNU/GPL, see LICENSE.txt
 * This component is classified as derivative of Joomla! and as such this 
 * version may have been modified pursuant to the GNU General Public License, 
 * and as distributed it includes or is derivative of works licensed under the 
 * GNU General Public License or other free or open source software licenses. 
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
?>
<?php if ($this->params->get( 'show_page_title', 1)) : ?>
<div class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
	<?php echo $this->escape($this->params->get('page_title')); ?>
</div>
<?php endif; ?>
<div class="contentpane<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
<link type="text/css" rel="stylesheet" href="<?php echo WEBMAPPLUS_ASSETS_URL;?>css/style.css"/>
<div style="margin: 10px 0; ">
<?php if($this->params->get( 'map_search',  1) == 1): ?>
<div id="gmaps_search_form">
<h2>Find the location closest to you: </h2>
  <form action="#" onsubmit="mm.searchSubmit(this.searchAddress.value, this.range.value); return false;">
Address: <input type="text" size="25" class="searchAddress" name="searchAddress" value=""/>
<?php if($this->params->get( 'map_search_range',  1) == 1): ?>
Range: <select name="range">
        <option value="5">5 <?php echo $this->units;?></option>
        <option value="10">10 <?php echo $this->units;?></option>
        <option value="20" selected>20 <?php echo $this->units;?></option>
        <option value="50">50 <?php echo $this->units;?></option>
        <option value="100">100 <?php echo $this->units;?></option>
        <option value="200">200 <?php echo $this->units;?></option>
        <option value="400">400 <?php echo $this->units;?></option>
        <option value="800">800 <?php echo $this->units;?></option>
     </select>
	 <?php endif; ?>
<?php if($this->params->get( 'map_search_range',  1) == 0): ?>
<input type="hidden" name="range" value="0"/>
<?php endif; ?>	 
     <input name="submit" type="submit" value="Search" />    
  </form>
</div>
<?php endif; ?>
<?php if($this->params->get( 'map_category_filter',  0) == 1): ?>
<div id="gmaps_category_form">
<h2>Category Filter: </h2>
  <form action="#" onsubmit="mm.filterCategories(this.category.value); return false;">
Category: <?php echo $this->category; ?>
     <input name="submit" type="submit" value="Search" />    
  </form>
</div>
<?php endif; ?>
<div style="clear: both"></div>
<h5 class="clearfix wmp_having_trouble">Having trouble viewing a location? Double click the area around it to zoom in.</h5>
<img src="<?php echo WEBMAPPLUS_ASSETS_URL;?>images/ajax-loader.gif" id="loading-indicator" alt="Loading..."/>
<div style="clear: both"></div>
</div>

<div class="clearfix webmapplus-map">
  <div id="gmap_results"></div>
  <div id="gmap">
  </div>
</div>


</div>
<script src="http://www.google.com/jsapi?key=<?php echo $this->key; ?>" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo WEBMAPPLUS_ASSETS_URL;?>js/webmapplus.js"></script>
<script type="text/javascript">
	google.load("maps", "2");
	var mm = null;
	window.addEvent('domready', function(){mm = new WebmapPlus({loadingIndicatorId: "loading-indicator", 	
		resultPanelId : "gmap_results",
		gMapId : "gmap",
		markersAjaxURL : "<?php echo JURI::base().'index.php?option=com_webmapplus&view=markers';?>",
		assetsURL : "<?php echo WEBMAPPLUS_ASSETS_URL;?>",
		searchFormId : "gmaps_search_form",
		category : "<?php echo JRequest::getInt( 'category', 0 ); ?>" ,
		staticDir: "<?php echo $this->params->get( 'staticDir', 0 ); ?>" ,
		staticDirLabel: "<?php echo $this->params->get( 'staticDirLabel', '' ); ?>" ,
		uiOpts : {
			basicMap: "<?php echo $this->params->get( 'map_basic_map',  1); ?>",
			satelliteMap: "<?php echo $this->params->get( 'map_satellite_map', 1); ?>",
			hybridMap: "<?php echo $this->params->get( 'map_hybrid_map', 1 ); ?>",
			physicalMap: "<?php echo $this->params->get( 'map_physical_map', 0 ); ?>",
			scrollWheel: "<?php echo $this->params->get( 'map_scroll_wheel', 1 ); ?>",
			mapControls: "<?php echo $this->params->get( 'map_controls', 1 ); ?>",
			scalecontrol: "<?php echo $this->params->get( 'map_scalecontrol', 1 ); ?>",
			panorma: "<?php echo $this->params->get( 'panoramio_layer',  1); ?>",
			wikipedia: "<?php echo $this->params->get( 'wikipedia_layer', 1 ); ?>",
			wikipediaZoomLevel: "<?php echo $this->params->get( 'wikipedia_zoom_layer', 15 ); ?>",
			maxLocations	: <?php echo $this->params->get( 'max_display_locations', 25 ); ?>,
			searchFormEnabled : <?php echo $this->params->get( 'map_search',  1); ?>,
			categoryFilterEnabled : <?php echo $this->params->get( 'map_category_filter',  0); ?>,
			searchFormAutoLocation : <?php echo $this->params->get( 'map_search_auto_locate',  1); ?>
		},
		bClickOpts: {
			name	: <?php echo $this->params->get( 'location_balloon_click_name', 1 ); ?>,
			address1: <?php echo $this->params->get( 'location_balloon_click_address1', 1 ); ?>,
			address2: <?php echo $this->params->get( 'location_balloon_click_address2', 1 ); ?>,
			city	: <?php echo $this->params->get( 'location_balloon_click_city', 1 ); ?>,
			state	: <?php echo $this->params->get( 'location_balloon_click_state', 1 ); ?>,
			zip		: <?php echo $this->params->get( 'location_balloon_click_zip', 1 ); ?>,
			country	: <?php echo $this->params->get( 'location_balloon_click_country', 1 ); ?>,
			photo	: <?php echo $this->params->get( 'location_balloon_click_photo', 1 ); ?>,
			more	: <?php echo $this->params->get( 'location_balloon_click_more_link', 1 ); ?>,
			streetView : <?php echo $this->params->get( 'location_balloon_click_street_view', 1 ); ?>
		},
		bHoverOpts: {
			name	: <?php echo $this->params->get( 'location_balloon_hover_name', 1 ); ?>,
			address1: <?php echo $this->params->get( 'location_balloon_hover_address1', 1 ); ?>,
			address2: <?php echo $this->params->get( 'location_balloon_hover_address2', 1 ); ?>,
			city	: <?php echo $this->params->get( 'location_balloon_hover_city', 1 ); ?>,
			state	: <?php echo $this->params->get( 'location_balloon_hover_state', 1 ); ?>,
			zip		: <?php echo $this->params->get( 'location_balloon_hover_zip', 1 ); ?>,
			country	: <?php echo $this->params->get( 'location_balloon_hover_country', 1 ); ?>,
			photo	: <?php echo $this->params->get( 'location_balloon_hover_photo', 1 ); ?>,
			enabled : <?php echo $this->params->get( 'location_balloon_hover', 1 ); ?>
		},
		listingOpts: {
			name	: <?php echo $this->params->get( 'location_listing_name', 1 ); ?>,
			address1: <?php echo $this->params->get( 'location_listing_address1', 1 ); ?>,
			address2: <?php echo $this->params->get( 'location_listing_address2', 1 ); ?>,
			city	: <?php echo $this->params->get( 'location_listing_city', 1 ); ?>,
			state	: <?php echo $this->params->get( 'location_listing_state', 1 ); ?>,
			zip		: <?php echo $this->params->get( 'location_listing_zip', 1 ); ?>,
			country	: <?php echo $this->params->get( 'location_listing_country', 1 ); ?>,
			photo	: <?php echo $this->params->get( 'location_listing_photo', 1 ); ?>,
			more	: <?php echo $this->params->get( 'location_listing_more_link', 1 ); ?>
		}	
		});
		mm.update("<?php echo JRequest::getVar('searchAddress', '');?>", 
               <?php echo JRequest::getInt('range', 20);?>, true, 
               <?php echo JRequest::getInt('category', 0);?>);
		});
	function openLocationBalloon(index){mm.openLocationBalloon(index);}
	function loadStreetView(lat, lng, cPano){mm.loadStreetView(lat, lng, cPano);}
	function hideStreetView(){mm.hideStreetView();}
	function toggleDirections(){mm.toggleDirections();mm.map.updateInfoWindow();}
	function staticDirections(to){mm.setDirections(to, "<?php echo $this->params->get( 'staticDirAddr', '' ); ?>");mm.map.updateInfoWindow();}
	function setDirections(form){mm.setDirections(form.from.value, form.to.value);}
	function centerMap(lat, lng){mm.centerMap(lat, lng); mm.map.updateInfoWindow();}
	function moreResults(limit){mm.updateMap(limit, <?php echo $this->params->get( 'max_display_locations', 25 ); ?> + limit);}
</script>
