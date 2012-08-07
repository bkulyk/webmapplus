var LocationMap = new Class({
    options: ({
        lat: 0,
        lng: 0,
        resultPanelId: "gmap-directions",
        gMapId: "gmap",
	    svId : "sv",
	    pano : "",
        markerPath: "/components/com_webmapplus/assets/images/markers/red_marker.png",
        uiOpts: {
	        basicMap: 1,
	        satelliteMap: 1,
	        hybridMap: 1,
	        physicalMap: 0,
	        scrollWheel: 1,
	        mapControls: "auto",
	        scalecontrol: 1,
	        panorma: 0,
	        wikipedia: "none",
	        wikipediaZoomLevel: 15
    	}
    }),
    
    
    map: null,
    directions: null,
    resultPanel: null,
    layers: [],
    markers: [],
	svToggled: false,
    
    initialize: function(options){
        this.setOptions(options);
        this.map = new google.maps.Map2($(this.options.gMapId));
        this.map.setUI(this.createUI());
        this.map.setCenter(new google.maps.LatLng(this.options.lat, this.options.lng), 15);
        
        var markerIcon = new google.maps.Icon(G_DEFAULT_ICON);
        
        markerIcon.iconSize = new google.maps.Size(20, 34);
        markerIcon.shadowSize = new google.maps.Size(38, 34);
        markerIcon.iconAnchor = new google.maps.Point(10, 34);
        markerIcon.infoWindowAnchor = new google.maps.Point(10, 0);
        markerIcon.image = this.options.markerPath;
        markerIcon.shadow = "/components/com_webmapplus/assets/images/markers/shadow_Marker.png";
        
        var mOptions = {
            icon: markerIcon
        };
        
        var marker = new GMarker(new google.maps.LatLng(this.options.lat, this.options.lng), mOptions);
        this.map.addOverlay(marker);
        this.markers.push(marker);
        
        this.generateLayers();
        
        this.resultPanel = $(this.options.resultPanelId);
        this.directions = new google.maps.Directions(this.map, this.resultPanel);
        google.maps.Event.addListener(this.directions, 'error', function(){
            this.directionsError();
        }
.bind(this));
        google.maps.Event.addListener(this.directions, 'load', function(){
            this.directionsLoad();
        }
.bind(this));
        google.maps.Event.addListener(this.directions, 'addoverlay', function(){
            this.directionsAfterLoad();
        }
.bind(this));
        GEvent.addListener(this.map, 'zoomend', function(oldLevel, newLevel){
            if (this.options.uiOpts.wikipediaZoomLevel >= newLevel) {
                this.removeLayers();
            }
        }
.bind(this));
    },
    
    createUI: function(){
        var uiOptions = this.map.getDefaultUI();
        
        uiOptions.maptypes.normal = this.options.uiOpts.basicMap == 1;
        uiOptions.maptypes.satellite = this.options.uiOpts.satelliteMap == 1;
        uiOptions.maptypes.hybrid = this.options.uiOpts.hybridMap == 1;
        uiOptions.maptypes.physical = this.options.uiOpts.physicalMap == 1;
        uiOptions.zoom.scrollwheel = this.options.uiOpts.scrollWheel == 1;
        uiOptions.controls.scalecontrol = this.options.uiOpts.scalecontrol == 1;
        
        if (this.options.uiOpts.mapControls != "auto") {
            if (this.options.uiOpts.mapControls == "none") {
                uiOptions.controls.largemapcontrol3d = false;
                uiOptions.controls.smallzoomcontrol3d = false;
            }
            else 
                if (this.options.uiOpts, mapControls == "small") {
                    uiOptions.controls.largemapcontrol3d = false;
                    uiOptions.controls.smallzoomcontrol3d = true;
                }
                else 
                    if (this.options.uiOpts.mapControls == "large") {
                        uiOptions.controls.largemapcontrol3d = true;
                        uiOptions.controls.smallzoomcontrol3d = false;
                    }
        }
        
        return uiOptions;
    },
    
    toggleDirections: function(){
        if ($("location-directions").getStyle("height").toInt() > 0) {
            $("directions-link").setStyle("font-weight", "normal");
            $("location-directions").setStyle("height", 0)
            this.map.updateInfoWindow();
        }
        else {
            $("directions-link").setStyle("font-weight", "bold");
            $("location-directions").setStyle("height", 75)
            this.map.updateInfoWindow();
        }
    },
    
    toggleDirectionsMode: function(){
        if ($("dir-text").getProperty('name') == "to") {
            $("dir-text").setProperty('name', 'from');
            $("dir-hidden").setProperty('name', 'to');
            $('directions-label-end').setStyle('display', 'none');
            $('directions-label-start').setStyle('display', 'block');
        }
        else {
            $("dir-text").setProperty('name', 'to');
            $("dir-hidden").setProperty('name', 'from');
            $('directions-label-end').setStyle('display', 'block');
            $('directions-label-start').setStyle('display', 'none');
        }
        
        var link = $('dir-mode-link');
        var linkText = $('dir-mode-inactive').getText();
        $('dir-mode-active').setText(link.getText());
        link.setText($('dir-mode-inactive').getText());
        
        $('dir-mode-inactive').setText("");
        $('dir-mode-inactive').adopt(link);
        
        var active = $('dir-mode-inactive');
        $('dir-mode-active').setProperty('id', 'dir-mode-inactive');
        active.setProperty('id', 'dir-mode-active');
        
    },
    
    
    setDirections: function(fromAddress, toAddress){
        this.directions.load("from: " + fromAddress + " to: " + toAddress);
    },
    
    directionsLoad: function(){
        this.resultPanel.innerHTML = "";
        this.map.closeInfoWindow();
        this.removeOverlays();
    },
    
    returnMap: function(){
        return this.map;
    },
    
    toggleStreetView: function(){
    	if (this.svToggled) {
			$('sv-toggle').setStyle("font-weight", "normal");
			$('sv-close').setStyle("display", "none");
			this.hideStreetView();
		}
		else{
			$('sv-toggle').setStyle("font-weight", "bold");
			$('sv-close').setStyle("display", "block");
			this.loadStreetView();
		}
		this.svToggled = !this.svToggled; 
    },
    
    loadStreetView: function(){
        $(this.options.gMapId).setStyle("display", "none");
        $(this.options.svId).setStyle("display", "block");
        if(this.options.pano != undefined && this.options.pano != ""){
    	   this.customStreetView(this.options.pano);
        }
        else{
    	   var pano = new google.maps.StreetviewPanorama($(this.options.svId), {latlng: new google.maps.LatLng(this.options.lat, this.options.lng)});
    	   GEvent.addListener(pano, "error", this.streetViewUnavailable);
    	  }
	},
	
	customStreetView : function(pano){
	
	  var coords = $(this.options.svId).getCoordinates();
	  var w = coords.width;
	  var h = coords.height;
		$(this.options.svId).innerHTML = '<iframe src="'+pano+'" width="100%" height="100%" frameborder="0" scrolling="no"></iframe>'; 
  },
    
	streetViewUnavailable : function(errorCode){
		$('sv').innerHTML = "<h3 class=\"sv-unavailable\">Sorry, Street View is not available for this location.</h3>";
	},
	
    hideStreetView: function(){
        $(this.options.svId).setStyle("display", "none");
        $(this.options.gMapId).setStyle("display", "block");
		this.map.checkResize();
    },
    
    directionsAfterLoad: function(){
		if(this.svToggled)
			this.toggleStreetView();
			
        var scroll = new Fx.Scroll(window).toElement(this.resultPanel);
    },
    
    removeOverlays: function(){
        while (this.markers.length > 0) {
            this.map.removeOverlay(this.markers.pop());
        }
        this.removeLayers();
    },
    
    removeLayers: function(){
        while (this.layers.length > 0) {
            this.map.removeOverlay(this.layers.pop());
        }
    },
    
    directionsError: function(){
        var message = "";
        switch (this.directions.getStatus().code) {
            case G_GEO_MISSING_QUERY:
            case G_GEO_MISSING_ADDRESS:
            case G_GEO_UNKNOWN_ADDRESS:
                message = "Address not found. Please try again.";
                break;
            case G_GEO_UNKNOWN_DIRECTIONS:
            case G_GEO_UNAVAILABLE_ADDRESS:
                message = "Unable to get directions for this address.";
                break;
            case G_GEO_BAD_KEY:
            case G_GEO_TOO_MANY_QUERIES:
                message = "An internal server error has occured please contact the website administrator. (" + this.directions.getStatus().code + ")";
                break;
            case G_GEO_BAD_REQUEST:
            case G_GEO_SERVER_ERROR:
            default:
                message = "An error has occured. Please try again.(" + this.directions.getStatus().code + ")";
        }
        alert(message);
    },
    
    generateLayers: function(){
        myLayer = null;
        if (this.options.uiOpts.panorma == 1) {
            myLayer = new GLayer("com.panoramio.all");
            this.layers.push(myLayer);
            this.map.addOverlay(myLayer);
        }
        this.addWikipediaLayers();
    },
    
    addWikipediaLayers: function(){
        myLayer = null;
        switch (this.options.uiOpts.wikipedia) {
            case "en":
                myLayer = new GLayer("org.wikipedia.en");
                this.map.addOverlay(myLayer);
                this.layers.push(myLayer);
                break;
            case "fr":
                myLayer = new GLayer("org.wikipedia.fr");
                this.map.addOverlay(myLayer);
                this.layers.push(myLayer);
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
LocationMap.implement(new Options);
