<style type="text/css">
	.markertable{
		border-top: 1px solid #CCC;
		border-left: 1px solid #CCC;
	}
	.markertable td, .markertable th{
		border-bottom: 1px solid #CCC;
		border-right: 1px solid #CCC;
	}
</style>
<table style="width: 50%; float: left; margin-left: 15px;"  cellspacing="0" cellpadding="0" class="admintable markertable">
	<tr>
		<th>Image</th>
		<th>Name</th>
		<th>Description</th>
	</tr>
	<tr>
		<td><img src="<?php echo $this->baseFile;?>.png" alt="Main"/></td>
		<td>Main</td>
		<td>The main foreground image. 24-bit PNG image with alpha transparency.</td>
	</tr>
	<tr>
		<td><img src="<?php echo $this->baseFile;?>s.png" alt="Main"/></td>
		<td>Shadow</td>
		<td>The shadow image. 24-bit PNG image with alpha transparency.</td>
	</tr>
	<tr>
		<td><img src="<?php echo $this->baseFile;?>p.gif" alt="Main"/></td>
		<td>Print</td>
		<td>An alternate foreground icon image used for printing on browsers incapable of handling the main foreground image. Transparent GIF.</td>
	</tr>
	<tr>
		<td><img src="<?php echo $this->baseFile;?>mp.gif" alt="Main"/></td>
		<td>Alternate Print</td>
		<td>An alternate non-transparent icon image used for printing on browsers incapable of handling either transparent PNGs or transparent GIFs.</td>
	</tr>
	<tr>
		<td><img src="<?php echo $this->baseFile;?>ps.gif" alt="Main"/></td>
		<td>Print Shadow</td>
		<td>A shadow image used for printed maps. This is a GIF image since most browsers cannot print PNG images.</td>
	</tr>
	<tr>
		<td><img src="<?php echo $this->baseFile;?>t.png" alt="Main"/></td>
		<td>Transparent</td>
		<td>A virtually transparent version of the foreground icon image used to capture click events in Internet Explorer. (The image will look like it is blank, but it is not.)</td>
	</tr>
	<tr>
		<td><img src="<?php echo $this->baseFile;?>o.png" alt="Main"/></td>
		<td>Image Map</td>
		<td>The green area represents the clickable area of the marker. (For visual purposes only, this image will not be displayed on the map.)</td>
	</tr>
</table>
<div id="gmap" style="width: 45%; height: 375px; float: right; border: 1px solid #CCC;">
</div>
<script src="http://www.google.com/jsapi?key=<?php echo $this->key; ?>" type="text/javascript"></script>
<script type="text/javascript">
  google.load("maps", "2");
   
  // Call this function when the page has been loaded
  function initialize() {
    var map = new google.maps.Map2(document.getElementById("gmap"));
	map.setUIToDefault();
	var center = new google.maps.LatLng(37.4419, -122.1419);
    map.setCenter(center, 13);
	
	var markertext = "<p style='width: 250px;'>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras euismod consectetur erat quis congue. Nulla facilisi.</p>";
	
	var icon = new google.maps.Icon();
	icon.image = '<?php echo $this->baseFile;?>.png';
	icon.shadow = '<?php echo $this->baseFile;?>s.png';
	icon.iconSize = new google.maps.Size(<?php echo implode(',', $this->iconSize);?>);
	icon.shadowSize = new google.maps.Size(<?php echo implode(',', $this->shadowSize);?>);
	icon.iconAnchor = new google.maps.Point(<?php echo implode(',', $this->iconAnchor);?>);
	icon.infoWindowAnchor = new google.maps.Point(<?php echo implode(',', $this->infoWindowAnchor);?>);
	icon.printImage = '<?php echo $this->baseFile;?>p.gif';
	icon.mozPrintImage = '<?php echo $this->baseFile;?>mp.gif';
	icon.printShadow = '<?php echo $this->baseFile;?>ps.gif';
	icon.transparent = '<?php echo $this->baseFile;?>t.png';
	icon.imageMap = [<?php echo implode(',', $this->imageMap);?>];

	var markerOptions = {icon:icon, draggable: true};
	var marker = new google.maps.Marker(center,markerOptions );
	
	google.maps.Event.addListener(marker, "click", function () {
		marker.openInfoWindowHtml(markertext);
	});
		
	google.maps.Event.addListener(marker, "dragstart", function() {
		map.closeInfoWindow();
	});

	map.addOverlay(marker);
	marker.openInfoWindowHtml(markertext);
  }
  google.setOnLoadCallback(initialize);
</script>
<br class="clr" />
<form action="index.php" method="post" name="adminForm" id="adminForm">
	<input type="hidden" name="name" value="<?php echo $this->name; ?>" />
	<input type="hidden" name="file_name" value="<?php echo $this->filename; ?>" />
	<input type="hidden" name="imageMap" value="<?php echo implode(',', $this->imageMap); ?>" />
	<input type="hidden" name="iconSize" value="<?php echo implode(',', $this->iconSize); ?>" />
	<input type="hidden" name="shadowSize" value="<?php echo implode(',', $this->shadowSize); ?>" />
	<input type="hidden" name="iconAnchor" value="<?php echo implode(',', $this->iconAnchor); ?>" />
	<input type="hidden" name="infoWindowAnchor" value="<?php echo implode(',', $this->infoWindowAnchor); ?>" />
	<input type="hidden" name="option" value="com_webmapplus" />
	<input type="hidden" name="cid[]" value="" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="controller" value="marker" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
