<?php
if ( ! defined( 'ABSPATH' ) ) exit;
global $wpdb, $atts;
$opt = get_option('custom_map_settings');
$mapoptions = shortcode_atts( array('id' => 0, 'map_div' => '', 'map_height' => '', 'map_width' => ''), $atts);
$shortcodeData = custom_map::singleShortcode($mapoptions['id']);

$layers = unserialize($shortcodeData->map_layers);

if($mapoptions['map_div'] == '')
{
	$mapoptions['map_div'] = $shortcodeData->map_div;
}
if($mapoptions['map_height'] == '')
{
	$mapoptions['map_height'] = $shortcodeData->mapHeight;
}
if($mapoptions['map_width'] == '')
{
	$mapoptions['map_width'] = $shortcodeData->mapWidth;
}
if(count($shortcodeData) == 0)
{
	echo '<p style="color:red">Map with ID: '.$mapoptions['id'].' is not exists.</p>';
}
else
{
	$mapConrol = array(
                     'panControl' => $opt['map_panControl'],
					 'zoomControl' => $opt['map_zoomControl'],
					 'mapTypeControl' => $opt['map_mapTypeControl'],
					 'scaleControl' => $opt['map_scaleControl'],
					 'streetViewControl' => $opt['map_streetViewControl'],
					 'overviewMapControl' => $opt['map_overviewMapControl'],
					 'rotateControl' => $opt['map_rotateControl'],
					 'scrollwheel' => $opt['map_scrollwheel'],
                  );		  				  
} ?>
<style>
#<?php echo $mapoptions['map_div'];?> {
	height: <?php echo $mapoptions['map_height']?>;
	<?php if(!empty($mapoptions['map_width'])): ?>
	width: <?php echo $mapoptions['map_width']?>;
	<?php endif;?>
} 
#iw_container .iw_title {
	font-size: 16px;
	font-weight: bold;
}
.iw_content {
	padding: 15px 15px 15px 0;
} 
</style>  
    <script>
function custom_map_<?php echo $mapoptions['id']?>()
{	
// necessary variables
var map;
var infoWindow;
// markersData variable stores the information necessary to each marker
var markersData = [
<?php 
$seprator = '';
/* Displaying All Locations */
$locations = unserialize($shortcodeData->map_locations);	
for($i = 1; $i<= count($locations['map_location_name']); $i++)
{
if(!empty($locations['map_location_name'][$i]) || !empty($locations['map_location_lat'][$i]) || !empty($locations['map_location_lng'][$i]) || !empty($locations['map_location_pc'][$i]) ){
if($i < count($locations['map_location_name'])) {$seprator = ',';}
?>
   {
      lat: <?php echo $locations['map_location_lat'][$i];?>,
      lng: <?php echo $locations['map_location_lng'][$i];?>,
      name: "<?php echo $locations['map_location_name'][$i];?>",
      address1:"<?php echo $locations['map_location_add'][$i];?>",
      postalCode: "<?php echo $locations['map_location_pc'][$i];?>" // don't insert comma in the last item of each marker
   }<?php echo $seprator;
    }
	}  ?>
];

function initialize<?php echo $mapoptions['id']?>() {
	<?php 
$map_theme = $shortcodeData->map_theme; 
custom_map::view('admin/themes', $map_theme);?>
   var mapOptions = {
      center: new google.maps.LatLng(<?php echo $shortcodeData->latC;?>, <?php echo $shortcodeData->longC;?>),
      zoom: <?php echo !empty($shortcodeData->zoomLevel) ? $shortcodeData->zoomLevel : 9; ?>,
	  panControl: <?php echo $mapConrol['panControl']; ?>,
      zoomControl: <?php echo $mapConrol['zoomControl']; ?>,
      mapTypeControl: <?php echo $mapConrol['mapTypeControl']; ?>,
      scaleControl: <?php echo $mapConrol['scaleControl']; ?>,
      streetViewControl: <?php echo $mapConrol['streetViewControl']; ?>,
      overviewMapControl:<?php echo $mapConrol['overviewMapControl']; ?>,
      rotateControl: <?php echo $mapConrol['rotateControl']; ?>,
	  <?php if(!empty($map_theme)):?> 
	  styles : <?php echo $map_theme; ?>, 
	  <?php endif;?>   
	  mapTypeId: google.maps.MapTypeId.<?php echo $shortcodeData->map_view;?>,
	  scrollwheel: <?php echo $mapConrol['scrollwheel']; ?>
   };

   map = new google.maps.Map(document.getElementById('<?php echo $mapoptions['map_div']?>'), mapOptions);

// code of kml layer 
<?php if($layers['map_kml_layer']==1):?>
    var ctaLayer = new google.maps.KmlLayer({
          url: '<?php echo $layers['map_layer_kml_link'];?>',
          map: map
        });
  <?php endif;?>

//code of traffic layer
<?php if($layers['map_traffic_layer']==1):?>
    var trafficLayer = new google.maps.TrafficLayer();
        trafficLayer.setMap(map);
  <?php endif;?>

//code of transit layer
<?php if($layers['map_transit_layer']==1):?>
    var transitLayer = new google.maps.TransitLayer();
        transitLayer.setMap(map);
<?php endif;?>

   // a new Info Window is created
   infoWindow = new google.maps.InfoWindow();

   // Event that closes the Info Window with a click on the map
   google.maps.event.addListener(map, 'click', function() {
      infoWindow.close();
   });

   // Finally displayMarkers() function is called to begin the markers creation
   displayMarkers<?php echo $mapoptions['id']?>();
}
google.maps.event.addDomListener(window, 'load', initialize<?php echo $mapoptions['id']?>);


// This function will iterate over markersData array
// creating markers with createMarker function
function displayMarkers<?php echo $mapoptions['id']?>(){

   // this variable sets the map bounds according to markers position
   var bounds = new google.maps.LatLngBounds();
   
   // for loop traverses markersData array calling createMarker function for each marker 
   for (var i = 0; i < markersData.length; i++){

      var latlng = new google.maps.LatLng(markersData[i].lat, markersData[i].lng);
      var name = markersData[i].name;
      var address1 = markersData[i].address1;
	  var postalCode = markersData[i].postalCode;
      createMarker<?php echo $mapoptions['id']?>(latlng, name, address1, postalCode);

      // marker position is added to bounds variable
      bounds.extend(latlng);  
   }

   // Finally the bounds variable is used to set the map bounds
   // with fitBounds() function
   map.fitBounds(bounds);
}

// This function creates each marker and it sets their Info Window content
function createMarker<?php echo $mapoptions['id']?>(latlng, name, address1, postalCode){
   var marker = new google.maps.Marker({
      map: map,
      position: latlng,
	  <?php if(!empty($shortcodeData->marker)){?>
	  icon: '<?php echo $shortcodeData->marker;?>',
	 <?php } 
	 if($shortcodeData->map_animation != 'none')
	 {?>
	  animation:google.maps.Animation.<?php echo $shortcodeData->map_animation; ?>, //BOUNCE
	 <?php } ?>  
      title: "<?php echo $shortcodeData->mapName; ?>"
   });

   // This event expects a click on a marker
   // When this event is fired the Info Window content is created
   // and the Info Window is opened.
   google.maps.event.addListener(marker, 'click', function() {      
      // Creating the content to be inserted in the infowindow
      var iwContent = '<div id="iw_container">' +
            '<div class="iw_title">' + name + '</div>' +
         '<div class="iw_content">' + address1 + '<br />' +
         postalCode + '</div></div>';      
      // including content to the Info Window.
      infoWindow.setContent(iwContent);
      // opening the Info Window in the current map and at the current marker location.
      infoWindow.open(map, marker);
   });
}
}
custom_map_<?php echo $mapoptions['id']?>();
</script>
<div id="<?php echo $mapoptions['map_div'];?>"></div>