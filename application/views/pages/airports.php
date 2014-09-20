<section class="page-top" style="margin-bottom:0px;">
					<div class="container">
						<div class="row">
							<div class="col-md-12">
								<ul class="breadcrumb">
									<li><a href="/zz_staging_ci/">Home</a></li>
								</ul>
							</div>
						</div>
						<div class="row">
							<div class="col-md-8">
								<h2>Destinations Map</h2>
							</div>
							<div class="col-md-4">
								<div class="alert alert-danger" align="center" style="position:absolute">
								
								    <label style="margin-bottom:0px;">
								    <input type="checkbox" id="01"  onclick="toggleGroup('01')"CHECKED/>
								    </label>
								    Airports &nbsp;&nbsp;
								     
								    <label style="margin-bottom:0px;">

								    <input type="checkbox" id="02"  onclick="toggleGroup('02')" CHECKED/>
								    </label>
								    Seaports &nbsp;&nbsp;
								    
								    <label style="margin-bottom:0px;">

								    <input type="checkbox" id="03"  onclick="toggleGroup('03')" CHECKED/>
								    </label>
								    Helipads								
								</div>
							</div>

						</div>
					</div>
</section>

<div id="map" style="width:100%;height:600px;"></div>


<script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
<script type="text/javascript" src="/zz_staging_ci/assets/js/downloadxml.js" ></script>
<script type="text/javascript">
//<![CDATA[
var map;
var customIcons = {
  "01": {
    icon: 'http://labs.google.com/ridefinder/images/mm_20_red.png',
    shadow: 'http://labs.google.com/ridefinder/images/mm_20_shadow.png'
  },
  "02": {
    icon: 'http://labs.google.com/ridefinder/images/mm_20_yellow.png',
    shadow: 'http://labs.google.com/ridefinder/images/mm_20_shadow.png'
  },
  "03": {
    icon: 'http://labs.google.com/ridefinder/images/mm_20_blue.png',
    shadow: 'http://labs.google.com/ridefinder/images/mm_20_shadow.png'
  },
};


  map = new google.maps.Map(document.getElementById("map"), {
    center: new google.maps.LatLng(33.745946,1.8935937),
    zoom: 2,
    minZoom: 2,
    mapTypeId: 'hybrid',
    streetViewControl: 'FALSE'
  });
  var infoWindow = new google.maps.InfoWindow;
  
  downloadUrl("/zz_staging_ci/assets/data/markers.xml", function(data) {
    var xml = xmlParse(data);
    var markers = xml.documentElement.getElementsByTagName("marker");
    var bounds = new google.maps.LatLngBounds();
    for (var i = 0; i < markers.length; i++) {
      var fs = markers[i].getAttribute("fs");
      var name = markers[i].getAttribute("name");
      var city = markers[i].getAttribute("city");
      var state = markers[i].getAttribute("state");
      var country = markers[i].getAttribute("country");
      var category = markers[i].getAttribute("type");
      
      var id_category;
      
      switch (category) {
         case "land":
            id_category="01";
            break;
         case "sea":
            id_category="02";
            break;
         case "heli":
            id_category="03";
            break;
         default:
      } 
      
      var point = new google.maps.LatLng(
          parseFloat(markers[i].getAttribute("lat")),
          parseFloat(markers[i].getAttribute("lng")));
      bounds.extend(point);
      
      var zoom = 14;
      
      if(state == ""){
      var html = "<b>" + fs + "</b><br />" + name + "<br />" + city + ", " + country;
      }
      else{
      var html = "<b>" + fs + "</b><br />" + name + "<br />" + city + ", " + state + "<br />" + country;
      }
      
      html += '<br><a  href="javascript:map.setCenter(new google.maps.LatLng('+point.toUrlValue(6)+')); map.setZoom('+zoom+');">[ Zoom To ]</a>';
      html += '&nbsp;&nbsp;&nbsp;&nbsp;<a  href="javascript:map.setCenter(new google.maps.LatLng('+point.toUrlValue(6)+')); map.setZoom(parseInt(map.getZoom())+1);">[ + ]</a>';
      html += '&nbsp;&nbsp;&nbsp;&nbsp;<a  href="javascript:map.setCenter(new google.maps.LatLng('+point.toUrlValue(6)+')); map.setZoom(parseInt(map.getZoom())-1);">[ - ]</a>';

      var icon = customIcons[id_category] || {};
      var marker = new google.maps.Marker({
        map: map,
        position: point,
        icon: icon.icon,
        shadow: icon.shadow
      });
      markerGroups[id_category].push(marker);
      bindInfoWindow(marker, map, infoWindow, html);
    }
    //map.fitBounds(bounds);
  });


function bindInfoWindow(marker, map, infoWindow, html) {
  google.maps.event.addListener(marker, 'click', function() {
    infoWindow.setContent(html);
    infoWindow.open(map, marker);
  });
}

 var markerGroups = { "01": [], "02": [] , "03": [] };
function toggleGroup(id_category) {
 for (var i = 0; i < markerGroups[id_category].length; i++) {
var marker = markerGroups[id_category][i];
if (marker.getMap()) {
  marker.setMap(null);
} else {
  marker.setMap(map);
}
 } 
  }

//]]>
</script>