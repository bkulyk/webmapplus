var WebmapPlus = new Class({
	Implements: [Options, Events],
	options: ({
		loadingIndicatorId: "", 	
		resultPanelId : "",
		gMapId : "",
		markersAjaxURL : "",

		assetsURL : "",
		searchFormId : "",
		
		category: "",

	    staticDir: "",
	    staticDirLabel: "",
		
		uiOpts : {
			basicMap	: 1,
			satelliteMap: 1,
			hybridMap	: 1,
			physicalMap	: 0,
			scrollWheel	: 1,
			mapControls	: "auto",
			scalecontrol: 1,
			panorma: 0,
			wikipedia: "none",
			wikipediaZoomLevel: 15,
			maxLocations: 25,
			searchFormEnabled: 1,
			categoryFilterEnabled: 0,
			searchFormAutoLocation: 1
		},
		bClickOpts: {
			name	: 1,
			address1: 1,
			address2: 1,
			city	: 1,
			state	: 1,
			zip		: 1,
			country	: 0,
			photo	: 1,
			more	: 1,
			streetView : 1
		},
		bHoverOpts: {
			name	: 1,
			address1: 1,
			address2: 1,
			city	: 1,
			state	: 1,
			zip		: 1,
			country	: 0,
			photo	: 0,
			enabled : 1
		},
		listingOpts: {
			name	: 1,
			address1: 0,
			address2: 0,
			city	: 0,
			state	: 0,
			zip		: 0,
			country	: 0,
			photo	: 1,
			more	: 0
		}
	}),
	
	initialize: function(options) {
	  this.setOptions(options);
	  this.isIE6 = /MSIE (5\.5|6)/.test(navigator.userAgent);
	  this.map = new google.maps.Map2($(this.options.gMapId));

	  this.map.setUI(this.createUI()); 
	  
	  this.searchForm = $(this.options.searchFormId);
	  
	  this.loadingIndicator = $(this.options.loadingIndicatorId);
	  if(this.options.uiOpts.searchFormEnabled == "1" && this.options.uiOpts.searchFormAutoLocation == "1"){this.prepopulateSearchBox();}
	  this.resultPanel = $(this.options.resultPanelId);
	  this.directions = new google.maps.Directions(this.map, this.resultPanel);
	  google.maps.Event.addListener(this.directions, 'error', function(){
	  	this.directionsError();
	  }.bind(this));
	  google.maps.Event.addListener(this.directions, 'load', function(){
	  	this.directionsLoad();
	  }.bind(this));
	  GEvent.addListener(this.map, 'zoomend', function(oldLevel, newLevel){
		if (this.options.uiOpts.wikipediaZoomLevel >= newLevel) {
			this.removeLayers();
		}
	  }.bind(this));
	  //this.update();
	},
	
	isIE6: false,
	map: null,
	resultPanel: null,
	directions: null,
	loadingIndicator: null,
	markers: [],
	markerTypes: [],
	useLetters: false,
	locations: [],
	displayOptions: [],
	svPano: null,
	layers: [],
	alphabet: ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 
			   'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'],
    lowerLimit: 0,
	upperLimit: 0,
	
	
	update: function(address, range, displayListings, category){
		this.loadingIndicator.setStyle('display', 'inline');
  		uri = this.options.markersAjaxURL;
		
		if(address != null)
		{
			uri = uri+"&address="+encodeURIComponent(address);
			if(range != null)
			{
				uri = uri+"&range="+range;
			}
		}
		if (category != null) {
			uri = uri+"&category="+category;
		}
		else if(this.options.category != "") {
			uri = uri+"&category="+this.options.category;
		}
		
		google.maps.DownloadUrl(uri, function(data) {
	      var data = eval('(' + data + ')');
		  this.locations = data['markers']; 
		  this.markerTypes = data['markerTypes'];
		  this.useLetters = data['useLetters'];
		  this.removeOverlays();
		  this.updateMap();
	    }.bind(this));
	},
	
	updateMap : function(lowerLimit, upperLimit){
		if(lowerLimit == null)
			lowerLimit = 0;
		if(upperLimit == null)
			upperLimit = this.options.uiOpts.maxLocations;
		if(this.options.uiOpts.maxLocations == 0){
			upperLimit = this.locations.length;
		}
			
		  this.resultPanel.innerHTML = "<h3>"+this.locations.length+'  Result(s) Found.</h3>'+
		  							   '<div class="location-listings">';
	      if(this.locations.length > 0)
	      {
              var maxLat = this.locations[0].lat;
              var maxLng = this.locations[0].long;
              var minLat = this.locations[0].lat;
              var minLng = this.locations[0].long;
			  
			  var i;
			  for (i = lowerLimit; i < upperLimit && i < this.locations.length; i++) {
			  		this.resultPanel.innerHTML += this.getListingBox(this.locations[i], i, this.alphabet[i - lowerLimit]);
			  }
			  
			  var createMarkers = this.markers.length == 0;
			  
              for (var j = 0; j < this.locations.length; j++) {
                  maxLat = Math.max(this.locations[j].lat, maxLat);
                  maxLng = Math.max(this.locations[j].long, maxLng);
                  minLat = Math.min(this.locations[j].lat, minLat);
                  minLng = Math.min(this.locations[j].long, minLng);
                  
				  var letter = "";
				  
				  if (j < upperLimit && j >= lowerLimit) {
				  		letter = this.alphabet[j - lowerLimit];
				  	}
				  
				  if(!createMarkers && ((j < this.upperLimit && j >= this.lowerLimit)
				  					|| (j < upperLimit && j >= lowerLimit))){
				  	this.map.removeOverlay(this.markers[j]);
					this.markers[j] = this.createMarker(this.locations[j], letter);
					this.map.addOverlay(this.markers[j]);
				  }
				  
				  if (createMarkers) {
				  	var marker = this.createMarker(this.locations[j], letter);
				  	this.map.addOverlay(marker);
				  	this.markers.push(marker);
				  }
                  
              }
			  
			  if (lowerLimit != 0) {
                  var subtractor = upperLimit - (this.options.uiOpts.maxLocations * 2);
                  if (subtractor < 0) {
                      subtractor = 0;
                  }
                  this.resultPanel.innerHTML += "<a href='#' onClick='moreResults(" + subtractor + "); return false; '>Previous Results</a>"
                  this.resultPanel.innerHTML += "</div>&nbsp;";
              }
			  
              if (i != this.locations.length) {
                  this.resultPanel.innerHTML += "<a href='#' onClick='moreResults(" + upperLimit + "); return false; '>More Results</a>"
                  this.resultPanel.innerHTML += "</div>";
              }
              if (this.locations.length == 1) 
                  marker.openInfoWindowHtml(this.generateBalloonHTML(this.locations[0], "Click"));
              
              var centerLat = minLat + (maxLat - minLat) / 2;
              var centerLng = minLng + (maxLng - minLng) / 2;
              var zoom = this.getZoom(minLat, maxLat, minLng, maxLng);
              this.centerMap(centerLat, centerLng, zoom);
              
			  if (this.options.uiOpts.wikipediaZoomLevel < zoom) {
				this.generateLayers();
			  }
			  
			  this.lowerLimit = lowerLimit;
			  this.upperLimit = upperLimit;
			  
              this.loadingIndicator.setStyle('display', 'none');
              return true;
	      }
	      this.resultPanel.innerHTML = "<h3>No Results Found.</h3>";
		 
	      this.loadingIndicator.setStyle('display', 'none');
	      alert('No Results Found.');
	      return false;
	},
	
	searchSubmit : function(address, range){
	  this.update(address, range, true, 0);
	},
	
	filterCategories : function(category){
	  this.update(null, null, true, category);
	},
	
	removeOverlays : function(){		
		while(this.markers.length > 0){
			this.map.removeOverlay(this.markers.pop());
		}
		this.removeLayers();
	},
	
	removeLayers : function(){
		while(this.layers.length > 0){
			this.map.removeOverlay(this.layers.pop());
		}
	},
	
	prepopulateSearchBox : function(){
		var location = "";
		if (google.loader.ClientLocation != undefined && google.loader.ClientLocation.address != undefined) {
			if (google.loader.ClientLocation.address.country_code == "US" &&
			google.loader.ClientLocation.address.region) {
				location = google.loader.ClientLocation.address.city + ", " +
				google.loader.ClientLocation.address.region.toUpperCase();
			}
			else {
				location = google.loader.ClientLocation.address.city + ", " +
				google.loader.ClientLocation.address.country_code;
			}
		}
		
		$$("input.searchAddress", this.searchForm)[0].value = location; 
		
	},
	
	toggleDirections: function(){
		if ($("location-directions").getStyle("height").toInt() > 0) {
			$("directions-link").setStyle("font-weight", "normal");
			$("location-directions").setStyle("height", 0)
			this.map.updateInfoWindow();	
		}
		else {
			$("directions-link").setStyle("font-weight", "bold");
			$("location-directions").setStyle("height", 60)
			this.map.updateInfoWindow();
		}
	},
	
	setDirections : function(fromAddress, toAddress) {
	  this.directions.load("from: " + fromAddress + " to: "+toAddress);
	},
	
	directionsLoad : function(){
		this.resultPanel.innerHTML = "";
		this.removeOverlays(); 
		this.map.closeInfoWindow();
	},
	returnMap : function(){
		return this.map;
	},
	
	directionsError : function(){
		var message = "";
		switch(this.directions.getStatus().code){
			case G_GEO_MISSING_QUERY 		:
			case G_GEO_MISSING_ADDRESS 		:
			case G_GEO_UNKNOWN_ADDRESS 		: 	message = "Address not found. Please try again.";
												break;
			case G_GEO_UNKNOWN_DIRECTIONS	:	
			case G_GEO_UNAVAILABLE_ADDRESS	:	message = "Unable to get directions for this address.";
												break;
			case G_GEO_BAD_KEY				:	
			case G_GEO_TOO_MANY_QUERIES		:	message = "An internal server error has occured please contact the website administrator. ("+this.directions.getStatus().code+")";
												break;
			case G_GEO_BAD_REQUEST			:
			case G_GEO_SERVER_ERROR			:
			default							:	message = "An error has occured. Please try again.";
		}
		alert(message);
	},
	
	centerMap : function(lat, lng, zoom){
	  if(zoom == null)
	    zoom = 15;
	  this.map.setCenter(new google.maps.LatLng(lat, lng), zoom);
	},
	
	loadStreetView : function(lat, lng, pano){
	  $('location-balloon').setStyle("display", "none");
	  $('location-sv').setStyle("display", "block");
	  if(pano != undefined && pano != ""){
	   this.customStreetView(pano);
    }
    else{
	   var pano = new google.maps.StreetviewPanorama($('location-sv-pano'), {latlng: new google.maps.LatLng(lat, lng)});
	   GEvent.addListener(pano, "error", this.streetViewUnavailable);
	  }
	  this.map.updateInfoWindow();
	},
	
	customStreetView : function(pano){
	
	  var coords = $('location-sv-pano').getCoordinates();
	  var w = coords.width;
	  var h = coords.height;
		$('location-sv-pano').innerHTML = '<iframe src="'+pano+'" width="100%" height="100%" frameborder="0" scrolling="no"></iframe>'; 
  },
	
	streetViewUnavailable : function(errorCode){
		$('location-sv-pano').innerHTML = "<h3 class=\"sv-unavailable\">Sorry, Street View is not available for this location.</h3>";
	},
	
	hideStreetView : function(){
	  $('location-balloon').setStyle("display", "block");
	  $('location-sv').setStyle("display", "none");
	  this.map.updateInfoWindow();
	},
	
	createMarker : function(mLocation, letter){		   
		var markerInfo = this.markerTypes[mLocation.markerType];
		var markerIcon = new google.maps.Icon(G_DEFAULT_ICON);
		
		var baseImage = this.options.assetsURL+"images/markers/"+markerInfo.file_name;
		
		var isDot = (letter == false || letter == "" || letter == null) && this.useLetters;
		
		markerIcon.image = baseImage;
		
		if (!isDot){
			if(markerInfo.lettering == true && this.useLetters)
				markerIcon.image += "-" + letter;
				
			if (markerInfo.iconSize != "") {
				markerIcon.iconSize = new google.maps.Size(parseInt(markerInfo.iconSize.split(',')[0]),
														   parseInt(markerInfo.iconSize.split(',')[1]));
			}
			
			if (markerInfo.shadowSize != "") {
				markerIcon.shadowSize = new google.maps.Size(parseInt(markerInfo.shadowSize.split(',')[0]),
															 parseInt(markerInfo.shadowSize.split(',')[1]));
			}
			
			if (markerInfo.iconAnchor != "") {
				markerIcon.iconAnchor = new google.maps.Point(parseInt(markerInfo.iconAnchor.split(',')[0]),
															  parseInt(markerInfo.iconAnchor.split(',')[1]));
			}
			
			
			if (markerInfo.infoWindowAnchor != "") {
				markerIcon.infoWindowAnchor = new google.maps.Point(parseInt(markerInfo.infoWindowAnchor.split(',')[0]),
																	parseInt(markerInfo.infoWindowAnchor.split(',')[1]));
			}
			
			if (markerInfo.imageMap != "") {
				markerIcon.imageMap = markerInfo.imageMap.split(',');
			}
			
			if (markerInfo.system == 0) {
  			markerIcon.shadow = baseImage + "s.png";
  			markerIcon.printImage = baseImage + "p.gif";
  			markerIcon.mozPrintImage = baseImage + "mp.gif";
  			markerIcon.printShadow = baseImage + "ps.gif";
  			markerIcon.transparent = baseImage + "t.png";
		  }
		}
		else {
			markerIcon.image += "-dot";
			markerIcon.iconSize = new google.maps.Size(7,7);
			markerIcon.shadowSize = new google.maps.Size(0,0);
			markerIcon.iconAnchor = new google.maps.Point(4,7);
			markerIcon.infoWindowAnchor = new google.maps.Point(4,0);
			markerIcon.imageMap = [5,0,6,1,6,2,6,3,6,4,6,5,5,6,1,6,0,5,0,4,0,3,0,2,0,1,1,0];
			baseImage += "-dot";
      		markerIcon.shadow = baseImage + "t.png";
			markerIcon.printImage = baseImage + "p.gif";
			markerIcon.mozPrintImage = baseImage + "mp.gif";
			markerIcon.printShadow = baseImage + "ps.gif";
			markerIcon.transparent = baseImage + "t.png"; 
		}	
			
		markerIcon.image += ".png";

		var mOptions = {icon : markerIcon};
	   
	 	var mPoint = new google.maps.LatLng(parseFloat(mLocation.lat), parseFloat(mLocation.long));
		var marker = new google.maps.Marker(mPoint, mOptions); 
		
		if (this.options.bHoverOpts.enabled == 1) {
			google.maps.Event.addListener(marker, "mouseover", function(){
				marker.openInfoWindowHtml(this.generateBalloonHTML(mLocation, "Hover"));
			}.bind(this));
		}
		google.maps.Event.addListener(marker,"mouseout", function() {
			marker.closeInfoWindow(); 
		});
		
		google.maps.Event.addListener(marker, 'click',
		  function() 
		  {
		     google.maps.Event.clearListeners(marker, "mouseout") ;
		     marker.closeInfoWindow(); 
		     marker.openInfoWindowHtml(this.generateBalloonHTML(mLocation, "Click"), {onCloseFn : 
		        function(){google.maps.Event.addListener(marker,"mouseout", 
		            function() {marker.closeInfoWindow();});}} );
		  }.bind(this)
		); 
		return marker;
	},
	
	getZoom : function(minLat, maxLat, minLng, maxLng){
	miles = (3958.75 * Math.acos(Math.sin(minLat / 57.2958) * Math.sin(maxLat / 57.2958) + Math.cos(minLat / 57.2958) * Math.cos(maxLat / 57.2958) * Math.cos(maxLng / 57.2958 - minLng / 57.2958)));

            if(miles < 0.5)
                zoom = 16;
            else if(miles < 1)
                zoom = 15;
            else if(miles < 2)
                zoom = 14;
            else if(miles < 3)
                zoom = 13;
            else if(miles < 7)
                zoom = 12;
            else if(miles < 15)
                zoom = 11;
            else if(miles < 30)
                zoom = 10;
            else if(miles < 60)
                zoom = 9;
            else if(miles < 120)
                zoom = 8;
            else if(miles < 240)
                zoom = 7;
            else if(miles < 480)
                zoom = 6;
            else if(miles < 960)
                zoom = 5;
            else if(miles < 1920)
                zoom = 4;
            else if(miles < 3840)
                zoom = 3;
            else
                zoom = 2;
		return zoom;
	},
	
	createUI : function(){
	  var uiOptions = this.map.getDefaultUI();
	  
	  uiOptions.maptypes.normal = this.options.uiOpts.basicMap==1;
	  uiOptions.maptypes.satellite = this.options.uiOpts.satelliteMap==1;
	  uiOptions.maptypes.hybrid = this.options.uiOpts.hybridMap==1;
	  uiOptions.maptypes.physical = this.options.uiOpts.physicalMap==1;
	  uiOptions.zoom.scrollwheel = this.options.uiOpts.scrollWheel==1;
	  uiOptions.controls.scalecontrol = this.options.uiOpts.scalecontrol==1;
	  
	  if(this.options.uiOpts.mapControls != "auto"){
	  	if(this.options.uiOpts.mapControls == "none"){
			uiOptions.controls.largemapcontrol3d = false;
			uiOptions.controls.smallzoomcontrol3d = false;
		}
		else if(this.options.uiOpts.mapControls == "small"){
			uiOptions.controls.largemapcontrol3d = false;
			uiOptions.controls.smallzoomcontrol3d = true;
		}
		else if(this.options.uiOpts.mapControls == "large"){
			uiOptions.controls.largemapcontrol3d = true;
			uiOptions.controls.smallzoomcontrol3d = false;
		}
	  }
	  
	  return uiOptions;
	},
	
	generateBalloonHTML : function(location, type){
		var opts = eval("this.options.b"+type+"Opts");		
		html = '<div id="location-balloon">';
		html += '<div id="location-details">';
		if (opts.name == 1) 
			html += 	'<div id="location-name">'+location.name+'</div>';
		if (opts.address1 == 1)
			html += 	'<div id="location-address1">'+location.address1+'</div>';
		
		if(opts.address2 == 1&& !(location.address2 === ""))
			html += 	'<div id="location-address2">'+location.address2+'</div>';
		
		if (opts.city == 1 || opts.state == 1 || 
			opts.zip == 1  || opts.country == 1) {
			html += '<div class="location-cty-st-zip">';
			if(opts.city == 1)
				html += location.city;
			if(opts.state == 1 || this.options.listingOpts.zip == 1)
				html += ', '
			if(opts.state == 1)
				html +=location.state + ' ';
			if(opts.zip == 1)
				html +=location.zip + ' ';
			if(opts.country == 1)
				html +=location.country + ' ';
			html += '</div>';
		}	
		html += 	'<div id="location-attributes">'+this.getAttributeList(location.attributes, "B"+type)+'</div>';
		
		if (type != "Hover") {
			html += '<div id="location-links">';
			
			if(opts.more == 1)
				html += '<a class="details-link" href="' + location.link + '">Details</a> - ';
			

			if (this.options.bClickOpts.streetView == 1) {
				html += '<a class="street-link" href="#" onClick="loadStreetView(' + location.lat + ', ' + location.long + ', \''+location.pano+'\'); return false;" onFocus="this.blur()">Street View</a> <br /> ';
			}
			html += '<a href="#" id="zoom-link" onClick="centerMap(' + location.lat + ', ' + location.long + '); return false;" onFocus="this.blur()">Zoom here</a> - ';
			html += '<a href="#" id="directions-link" onClick="toggleDirections(); return false;" onFocus="this.blur()">Get Directions</a>';
			if (this.options.staticDir == 1) {
			 html += ' - <a href="#" id="static-directions-link" onClick="staticDirections(\''+location.address1+' '+
                location.address2+' '+location.city+', ' +location.state+' '+location.zip +
               '\'); return false;" onFocus="this.blur()">Directions to '+this.options.staticDirLabel+'</a>';
			}
			html += '</div>';
			html += '<div id="location-directions">';
			html += '<div class="directions-label">From Address:</div>';
			html += '<div class="directions-fields"><form action="#" method="GET" onSubmit="setDirections(this); return false;">';
			html += '<input type="text" name="from" value="" autocomplete="off"/><input type="submit" value="Go"/>';
			html += '<input type="hidden" name="to" value="' + location.address1 + ' ' + location.address2 + ' ' + location.city + ', ' +
			location.state +
			' ' +
			location.zip +
			'"/>';
			html += '</form></div>';
			html += '<div class="directions-example">(e.g. 1 Infinite Loop, Cupertino CA 95014)</div>';
			html += '</div>';
		}
		else{
			html += '<div id="location-hover-instructions">';
			html += 'Click the marker for more information.';
			html += '</div>';
		}
		
		html += '</div>';
		if (opts.photo == 1) {
			html += '<div id="location-photo">';
			html += '<img src="' + location.photo_path + '" alt="' + location.name + '" />';
			html += '</div>';
		}
		html += '</div>';
		html += '<div id="location-sv"><div id="location-sv-pano"></div><div id="location-sv-links">';
		
		html += '<a class="back-link" href="#" onclick="hideStreetView(); return false;" onFocus="this.blur()"><< Back</a>';
		
		if(opts.more == 1)
				html += ' - <a class="details-link" href="' + location.link + '">Details</a>';
		
		html += '</div></div>';
		return html;
	},

	getListingBox : function(location, index, letter){
		var markerInfo = this.markerTypes[location.markerType];
		var markerImage = this.options.assetsURL+"images/markers/"+markerInfo.file_name;
				
		if(letter != "" && letter != false && markerInfo.lettering == true && this.useLetters)
			markerImage += "-"+letter;
		
		markerImage += ".png";
		html = '<div class="location-listing clearfix" style="background-color: ' + location.highlight_color + '">';
		
		if (this.options.listingOpts.photo == 1) {
			html += '<div class="location-photo">';
			html += '<img src="' + location.photo_path + '" alt="' + location.name + '" />';
			html += '</div>';
		}
		html += 	'<div class="location-marker">';
		html += 		'<img src="'+markerImage+'" alt="" />';
		html += 	'</div>';
		html += '<div class="location-details">';
		if (this.options.listingOpts.name == 1) {
			html += '<div class="location-name">';
			html += '<a href="#' + location.alias + '" onclick="openLocationBalloon(' + index + '); return false;" class="location-map-link">';
			html += location.name + '</a></div>';
		}
		if (this.options.listingOpts.address1 == 1) {
			html += '<div class="location-address1">' + location.address1 + '</div>';
		}
		
		if (!(this.options.listingOpts.address2 == 1 && location.address2 === "")) {
			html += '<div class="location-address2">' + location.address2 + '</div>';
		}
		
		if (this.options.listingOpts.city == 1 || this.options.listingOpts.state == 1 || 
			this.options.listingOpts.zip == 1  || this.options.listingOpts.country == 1) {
			html += '<div class="location-cty-st-zip">';
			if(this.options.listingOpts.city == 1)
				html += location.city;
			if(this.options.listingOpts.state == 1 || this.options.listingOpts.zip == 1)
				html += ', '
			if(this.options.listingOpts.state == 1)
				html +=location.state + ' ';
			if(this.options.listingOpts.zip == 1)
				html +=location.zip + ' ';
			if(this.options.listingOpts.country == 1)
				html +=location.country + ' ';
			html += '</div>';
		}
							
		html += 	'<div class="location-attributes">'+this.getAttributeList(location.attributes, "Listing")+'</div>';
		if (this.options.listingOpts.more == 1) {
			html += '<div class="location-link"><a href="' + location.link + '">More</a>';
		}
		html += 	'</div>';	
		html += 	'</div>';
		html += '</div>';
		
		return html;
	},
	
	getAttributeList : function(attributes, view){
		if(attributes == undefined)
			return "";
		
		var html = "<ul>";
			
		for(var i=0; i < attributes.length; i++){
			if(attributes[i].value != undefined && attributes[i].value != 0 && attributes[i].value != "" 
			&& eval("attributes[i].display"+view) != 0){
				if (attributes[i].type == 'bool') 
					html += "<li>" + attributes[i].name + "</li>";
				else if (attributes[i].type == 'link') 
					html += "<li><a href='"+attributes[i].value+"'>"+ attributes[i].name + "</a></li>";
				else if (attributes[i].type != 'textarea') 
					html += "<li>" + attributes[i].name + ": " + attributes[i].value + "</li>";
			}
		}
		html += "</ul>";
		for(var i=0; i < attributes.length; i++){
			if(attributes[i].value != undefined && attributes[i].value != 0 && attributes[i].value != "" 
			&& eval("attributes[i].display"+view) != 0){
				if (attributes[i].type == 'textarea') 
					html += "<p>" + attributes[i].name + ": " + attributes[i].value + "</p>";
			}
		}
		return html;
	},
	
	openLocationBalloon : function(index){
		this.centerMap(this.locations[index].lat, this.locations[index].long);
		this.markers[index].openInfoWindowHtml(this.generateBalloonHTML(this.locations[index], "Click"));	
	},
	
	generateLayers : function(){
		myLayer = null;
		if (this.options.uiOpts.panorma == 1) {
			myLayer = new GLayer("com.panoramio.all");
			this.map.addOverlay(myLayer);
			this.layers.push(myLayer);
		}
		this.addWikipediaLayers();
	},
	
	addWikipediaLayers : function(){
		myLayer = null;
		switch(this.options.uiOpts.wikipedia)
		{
			case "en":
				myLayer = new GLayer("org.wikipedia.en");
				this.map.addOverlay(myLayer);
				this.layers.push(myLayer);
				break;
			case "fr":
				myLayer = new GLayer("org.wikipedia.fr");
				this.map.addOverlay(myLayer);
				break;
			case "es":
				myLayer = new GLayer("org.wikipedia.es");
				this.map.addOverlay(myLayer);
				this.layers.push(myLayer);
				break;
			default:
				break;
		}
	}
});

WebmapPlus.implement(new Options);
