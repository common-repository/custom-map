<?php if ( ! defined( 'ABSPATH' ) ) exit;
global $wpdb;
custom_map::admin_assets();
$allshortcodes = custom_map::allShortcodes($limit = 200,'ASC'); 
$opt = get_option('custom_map_settings');
if(isset($_REQUEST['map_id']))
{
$map_id = intval($_REQUEST['map_id']);

$shortcodeData = custom_map::singleShortcode($map_id);
//print_r($shortcodeData);
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
				  
}
?>
<?php if((isset($_REQUEST['map_id']))&&(isset($_REQUEST['pol_id'])))
{
//echo $_REQUEST['pol_id'];
_e("Deletting Please wait...", 'custom_maps');
$m_id = intval($_REQUEST['map_id']);
$where = array();
$where['id'] = intval($_REQUEST['pol_id']);
custom_map::delete_polylines($m_id,$where);
}?>

<?php 
$msg = isset($_REQUEST['msg']) ? $_REQUEST['msg'] : '';
if($msg == 1):
  custom_map::success('Polylines added successfully.');
elseif($msg==2):
custom_map::error('Map shortcode not updated');
elseif($msg==3):
custom_map::success('Polylines has been deleted successfully');
elseif($msg==5):
custom_map::success('All Polylines has been deleted successfully');
endif;

/* Bulk Actions */
if(isset($_POST['bulkaction'])):

	echo 'Performing action please wait....';
       $tbl_name=custom_map::polyline_dbTable();
	$action_name = $_POST['ux_ddl_action'];
	$ux_chk_bulk= $_POST['ux_chk_bulk']; //multi
	$mid = intval($_REQUEST['map_id']);
if(!empty($action_name)):

$page_name = $_REQUEST['page'];

 custom_map::bulkaction_Polygon_polylines($page_name,$ux_chk_bulk,$tbl_name,$mid);
 
endif;

endif;?>

<?php if(isset($_REQUEST['map_id'])):?>
<script>
jQuery(document).ready(function(){
  //initMap();
    jQuery("#custom_map_shortcode").val(<?php echo intval($_REQUEST['map_id']);?>);
});
</script>
<?php endif;?>

<style>
       #mapid{
        width: 100%;
        height: 400px;
      }
</style>
<?php 
if(isset($_POST['btn_polyline']))
{

$mid = intval($_REQUEST['map_id']);

global $wpdb;
$tbl_name=custom_map::polyline_dbTable();
$count=custom_map::count_check_polyline_polygon($mid,$tbl_name);

if($count<3):
$polyline_setting = array();
$polyline_setting['polyline_opacity'] = $_POST['ux_txt_polyline_opacity'];
$polyline_setting['polyline_color'] = $_POST['ux_txt_polyline_color'];
$polyline_setting['polyline_opacity'] = $_POST['ux_txt_polyline_opacity'];
$polyline_setting['polyline_thickness'] = $_POST['ux_txt_polyline_thickness']; 
$polyline_setting['polyline_type'] = $_POST['ux_txt_polyline_type'];
//print_r($polyline_setting);
$polyline_setting_data = serialize($polyline_setting);

$fieldsData = array();
$fieldsData['mid'] = $mid;
$fieldsData['polylines_settings']=$polyline_setting_data;
$fieldsData['polylines_data'] = $_POST['ux_polyline_data'];
	
if(!empty($_POST['ux_polyline_data'])):	
custom_map::generateShortcode_polylines($fieldsData,$mid);
else:
custom_map::error('All fields are required.');
endif;
else:
    custom_map::error('You can create maximum three polylines for particluar map.');
endif;//endif 	

}
?>
<div class="wrap settings_custom_map">
	<h2><?php _e('Advanced Settings', 'custom_maps');?></h2>
	<div class="container">
<div class="tab-links" style="display: inline-flex;">
		<ul class="tabs">
			<li class="tab-link current" data-tab="tab-1"><?php _e('Polylines', 'custom_maps');?></li>
			
		</ul>
<?php if(isset($_REQUEST['map_id'])):?>
<a style="margin-top: 10px;color: #222;text-decoration: none;" href ="admin.php?page=custom_maps_polygons&map_id=<?php echo intval($_REQUEST['map_id']);?>"><?php _e('Polygons', 'custom_maps');?></a>
<?php else:?>
<a style="margin-top: 10px;color: #222;text-decoration: none;" href ="admin.php?page=custom_maps_polygons"><?php _e('Polygons', 'custom_maps');?></a>
<?php endif;?>
</div>
		<div id="tab-1" class="tab-content current">
              <form name="form_advanced_settings" method="post" action="admin.php?page=custom_maps_advanced_settings&map_id=<?php echo $map_id; ?>">
		 <table class="form-table">
		 	<tbody>
			
                         <tr>
			<th scope="row"><label for="mapName"><?php _e('Choose Map Shortcode*', 'custom_maps');?></label></th>
			<td><select name="custom_map_shortcode" id="custom_map_shortcode">
					<option value=""><?php _e('--- Please select shortcode ---', 'custom_maps');?></option>
	            	<?php foreach($allshortcodes as $allshortcode){?>
			       <option value="<?php echo $allshortcode->mid;?>">
				<?php echo $allshortcode->mapName; ?> : [<?php echo $opt['shortcode_name']; ?> id=<?php echo $allshortcode->mid; ?>]
				   </option>
					<?php }	?>
					</select>	
                          </td>
			</tr>

			<tr>
			<th scope="row"><label for="mapName"><?php _e('Polyline Color*', 'custom_maps');?></label></th>
			<td><input type="text" class="regular-text color" name="ux_txt_polyline_color" id="ux_txt_polyline_color" onblur="polyline_data(<?php echo $allshortcode->mid; ?>)"  required value="rgb(255, 0, 0)"></td>
			</tr>
			
			<tr>
			<th scope="row"><label for="mapName"><?php _e('Polyline Opacity*', 'custom_maps');?></label></th>
			<td><input type="text" class="regular-text" name="ux_txt_polyline_opacity" id="ux_txt_polyline_opacity" onblur="polyline_data(<?php echo $allshortcode->mid; ?>)" value= "1.0" required></td>
			</tr>
			
			<tr>
			<th scope="row"><label for="mapName"><?php _e('Polyline Thickeness*', 'custom_maps');?></label></th>
			<td><input type="text" class="regular-text" name="ux_txt_polyline_thickness" id="ux_txt_polyline_thickness" onblur="polyline_data(<?php echo $allshortcode->mid; ?>)" value="2" required></td>
			</tr>
			
			<tr>
			<th scope="row"><label for="mapName"><?php _e('Polyline Type*', 'custom_maps');?></label></th>
			<td>
				<select id="ux_txt_polyline_type" name="ux_txt_polyline_type">
					<option value='Solid' selected="selected">Solid</option>
					<option value='Dashed'>Dashed</option>
					<option value='Dotted'>Dotted</option>
				</select>
			</td>
            </tr>
			
			<tr>
			<th scope="row"><label for="mapName"><?php _e('Polyline Data*', 'custom_maps');?></label></th>
			<td>
				<textarea readonly rows="4" cols="50" id="ux_polyline_data" name="ux_polyline_data" required></textarea>
			</td>
			</tr>

			<tr>
			<th scope="row"><label for="mapName"></label></th>
			<td>
			<input type="submit" name="btn_polyline" id="btn_polyline" value="save Data" class="button button-primary button-large" style="float:right">
			</td>
			</tr>
			</tbody>
		 </table>

</form>
		</div>
		
	</div><!-- container -->
</div>
<script>
jQuery('#custom_map_shortcode').on('change', function() {
  var map_id=this.value;
if(map_id !='')
{
  window.location.href = "admin.php?page=custom_maps_advanced_settings&map_id="+map_id;
}
})
</script>
<?php if(isset($_REQUEST['map_id']))
{
	$opt = get_option('custom_map_settings');
	if($opt['map_conflicts'] != 'true')
	{
	  if(!empty($opt['google_api_key']))
	  {
	 echo "<script src='https://maps.googleapis.com/maps/api/js?key=".$opt['google_api_key']."'></script>";
	  } 
	  else
	  {	
	echo "<script  src='https://maps.googleapis.com/maps/api/js'></script>";		
	  }
	}
?>
<style>
#<?php echo $mapoptions['map_div'];?> {
    height: 450px;
    width: 97%;
    margin-top: 20px;
    border: 1px solid #000;
	
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
var polyline_data_array = [];
function custom_map_<?php echo $map_id?>()
{	

var map;
var infoWindow;
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

function initialize<?php echo $map_id;?>() {

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


 google.maps.event.addListenerOnce(map, 'bounds_changed', function(event) {
      //alert(this.getZoom());
  if (this.getZoom() > 15) {
    this.setZoom(<?php echo !empty($shortcodeData->zoomLevel) ? $shortcodeData->zoomLevel : 15; ?>);
  }
});

 
// code of 45 imagery

<?php if($layers['map_imagery']==1):?>
     map.setTilt(45);
  <?php endif;?>

/*adding new polylines*/
      var polylines_color =jQuery("#ux_txt_polyline_color").val();
       //var Polyline_Opacity=jQuery("#ux_txt_polyline_thickness").val();

       poly = new google.maps.Polyline({
          strokeColor: polylines_color,
          strokeOpacity: 1.0,
          strokeWeight: 3
        });
        poly.setMap(map);
  map.addListener('click', addLatLng);
/*end*/
       
<?php
	/* polylines */


	$total_polyline_array = custom_map_polyline_id_array(sanitize_text_field($map_id));
	if ($total_polyline_array > 0) 
	{

		foreach ($total_polyline_array as $poly_id) 
		{
			$polyoptions = custom_map_polyline_options($poly_id);
			$polygon_setting=unserialize($polyoptions->polylines_settings);
			$polyline_opacity=$polygon_setting['polyline_opacity'];
			$polyline_color =$polygon_setting['polyline_color'];
			$polyline_thickness =$polygon_setting['polyline_thickness'];
			$polyline_type =$polygon_setting['polyline_type'];
			if($polyline_type=="Dashed")
			{
			$path="M 0,-1 0,1";
			}
if($polyline_type=="Dotted")
			{
			$path="M 0,-0.1 0,0.1";
			}?>

			<?php
            $poly_array = custom_map_polyline_array($poly_id);			
			if (sizeof($poly_array) > 1) 
			{
				$poly_array = custom_map_polyline_array($poly_id);
				?>
				var custom_PathLineData_<?php echo $poly_id; ?> = [
                        <?php
                        $poly_array = custom_map_polyline_array($poly_id);

                        foreach ($poly_array as $single_poly) {
                            $poly_data_raw = str_replace(" ","",$single_poly);
                            $poly_data_raw = explode(",",$poly_data_raw);
                           $lat = $poly_data_raw[0];
                           $lng = $poly_data_raw[1];
                            ?>
                            new google.maps.LatLng(<?php echo $lat; ?>, <?php echo $lng; ?>),            
                            <?php
                        }
                        ?>
                    ];


// Define a symbol using SVG path notation, with an opacity of 1.
<?php if($polyline_type!="Solid"):
//echo $polyline_type;?>
        var lineSymbol1 = {
          path: '<?php echo $path;?>',
          strokeOpacity: 1,
strokeColor:"<?php echo $polyline_color; ?>",
strokeWeight: "<?php echo $polyline_thickness ?>",
          scale: 4
        };

         var line = new google.maps.Polyline({
          path: custom_PathLineData_<?php echo $poly_id; ?>,
          strokeOpacity: 0,
          icons: [{
            icon: lineSymbol1,
            offset: '0',
            repeat: '20px'
          }],
          map: map
        });

	<?php elseif($polyline_type=="Solid"):?>
var custom_PathLine_<?php echo $poly_id; ?> = new google.maps.Polyline({
                      path: custom_PathLineData_<?php echo $poly_id; ?>,
                      strokeOpacity: "<?php echo $polyline_opacity; ?>",
		strokeColor: "<?php echo $polyline_color; ?>",
		strokeWeight: "<?php echo $polyline_thickness ?>"
				});
//alert(custom_PathLine_<?php echo $poly_id; ?>);
                   custom_PathLine_<?php echo $poly_id; ?>.setMap(map);


<?php endif;?>	

				
			<?php
			}
		}
	}
			?>


/* Drawing polygons and fetch data from database*/
<?php
	/* polygons */
	$total_polygon_array = custom_map_polygon_id_array(sanitize_text_field($map_id));
//print_r($total_polygon_array);
	if ($total_polygon_array > 0) 
	{

		foreach ($total_polygon_array as $poly_id) 
		{
			
			$polyoptions = custom_map_polygon_options($poly_id);
			$polygon_setting=unserialize($polyoptions->polygon_settings);
			$polygon_line_opacity=$polygon_setting['polygon_line_opacity'];
			$polygon_line_color =$polygon_setting['polygon_line_color'];
			$polygon_color =$polygon_setting['polygon_color'];
			$polygon_opacity =$polygon_setting['polygon_opacity'];
			?>

			<?php
            $poly_array = custom_map_polygon_array($poly_id);
//print_r($poly_array);		
			if (sizeof($poly_array) > 1) 
			{
				$poly_array = custom_map_polygon_array($poly_id);
//print_r($poly_array);
				?>
				var custom_PathLineData_<?php echo $poly_id; ?> = [
                        <?php
                        $poly_array = custom_map_polygon_array($poly_id);

                        foreach ($poly_array as $single_poly) {
                            $poly_data_raw = str_replace(" ","",$single_poly);
                            $poly_data_raw = explode(",",$poly_data_raw);
                           $lat = $poly_data_raw[0];
                           $lng = $poly_data_raw[1];
                            ?>
                            new google.maps.LatLng(<?php echo $lat; ?>, <?php echo $lng; ?>),            
                            <?php
                        }
                        ?>
                    ];
			var drawpolygon_<?php echo $poly_id; ?> = new google.maps.Polygon({
			  path: custom_PathLineData_<?php echo $poly_id; ?>,
			  strokeColor: '<?php echo $polygon_line_color;?>',
			  strokeOpacity: <?php echo $polygon_line_opacity;?>,
			  strokeWeight: 2,
			  fillColor: '<?php echo $polygon_color;?>',
			  fillOpacity: <?php echo $polygon_opacity;?>
			});
		
			drawpolygon_<?php echo $poly_id; ?>.setMap(map);
			<?php
			}
		}
	}
			?>

/* End code of polygon*/




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

//code of Bicyle layer code 1 means enabled 0 means disabled
<?php if($layers['map_bicyle_layer']==1):?>
     var bikeLayer = new google.maps.BicyclingLayer();
  bikeLayer.setMap(map);
<?php endif;?>

//code of Fusion layer code 
<?php if($layers['ux_txt_fusion_layer']==1):?>
     var layer = new google.maps.FusionTablesLayer({
    query: {
      select: '<?php echo $layers['map_fusion_source'];?>',
      from: '<?php echo $layers['map_fusion_destination'];?>'
    }
  });
  layer.setMap(map);
<?php endif;?>




   // a new Info Window is created
   infoWindow = new google.maps.InfoWindow();

   // Event that closes the Info Window with a click on the map
   google.maps.event.addListener(map, 'click', function() {
      infoWindow.close();
   });

   // Finally displayMarkers() function is called to begin the markers creation
   displayMarkers<?php echo $map_id?>();
}
google.maps.event.addDomListener(window, 'load', initialize<?php echo $map_id?>);


// This function will iterate over markersData array
// creating markers with createMarker function
function displayMarkers<?php echo $map_id?>(){

   // this variable sets the map bounds according to markers position
   var bounds = new google.maps.LatLngBounds();
   
   // for loop traverses markersData array calling createMarker function for each marker 
   for (var i = 0; i < markersData.length; i++){

      var latlng = new google.maps.LatLng(markersData[i].lat, markersData[i].lng);
      var name = markersData[i].name;
      var address1 = markersData[i].address1;
	  var postalCode = markersData[i].postalCode;
      createMarker<?php echo $map_id?>(latlng, name, address1, postalCode);

      // marker position is added to bounds variable
      bounds.extend(latlng);  
   }

   // Finally the bounds variable is used to set the map bounds
   // with fitBounds() function
   map.fitBounds(bounds);
}

// This function creates each marker and it sets their Info Window content
function createMarker<?php echo $map_id?>(latlng, name, address1, postalCode){
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
	  function addLatLng(event) 
{
	var path = poly.getPath();
	polyline_data_array.push(event.latLng);
	jQuery("#ux_polyline_data").val(polyline_data_array);
	path.push(event.latLng);
}
}
custom_map_<?php echo $map_id?>();
</script>
<div id="<?php echo $mapoptions['map_div'];?>"></div>
<?php  $polylines_record = $wpdb->get_results("SELECT * FROM wp_custom_map_polyline WHERE mid = '".intval($map_id)."'");
//print_r($polylines_record);
?>
<div class="alignleft actions bulkactions" style="margin-top:10px;">
<form action="admin.php?page=custom_maps_advanced_settings&map_id=<?php echo $map_id; ?>" method="post" name="bulkform">
<label class="screen-reader-text" for="bulk-action-selector-top">Select bulk action</label>
<select name="ux_ddl_action" id="ux_ddl_action">
<option value="">Choose Action</option>
  <option value="delete">Delete</option>
</select>
<input type="submit" value="Apply" class="button action" id="doaction" name="bulkaction">
</div>
<table id="tbl_polyline" style="width:97% !important;margin-top: 20px;">
<tbody>
<tr>
<th><input type="checkbox" name="ux_chk_bulk" id="ux_chk_bulk" value="0"> </th>
<th>Polylines ID</th>
<th>Polylines Color</th>
<th>Polylines Opacity</th>
<th>Polylines Thickness</th>
<th>Polylines Type</th>
<th>Action</th>
</tr>
<?php foreach($polylines_record as $polylines_value ){
$polylines_setting = unserialize($polylines_value->polylines_settings);
?>

<tr>
<td><input type="checkbox" class="cmap_check" name="ux_chk_bulk[]" id="ux_chk_bulk_<?php echo $polylines_value->id;?>" value="<?php echo $polylines_value->id;?>"></td>
<td><?php echo $polylines_value->id;?></td>
<td><?php echo $polylines_setting['polyline_color'];?></td>
<td><?php echo $polylines_setting['polyline_opacity'];?></td>
<td><?php echo $polylines_setting['polyline_thickness'];?></td>
<td><?php echo $polylines_setting['polyline_type'];?></td>
<td><a href="javascript:void(0)" onclick="polyline_del(<?php echo $polylines_value->mid;?>,<?php echo $polylines_value->id;?>)">Delete</a></td>
</tr>

<?php }?>
</tbody>
</table>
</form>
<?php }?>

<script type='text/javascript' src="<?php echo plugins_url( '/js/jqColorPicker.min.js', __FILE__ )?>"></script>
 <script>
  jQuery(".regular-text.color").colorPicker(); 
function polyline_del(map_id,pl_id)
{
    var Messgae = confirm("Are you sure to delete this Polylines?");
    if (Messgae == true) {
       //alert(Messgae);
          window.location.href = "admin.php?page=custom_maps_advanced_settings&map_id="+map_id+"&pol_id="+pl_id;
    } 
}

jQuery("#ux_chk_bulk").click(function(){

       jQuery(".cmap_check").each(function(index, value){
     var all_check= jQuery("#ux_chk_bulk").prop("checked");
if(all_check == true)
{
  jQuery('.cmap_check').prop('checked', true);
}
else
{
 jQuery('.cmap_check').prop('checked', false);
}


    });
});
 </script>


<?php
    function custom_map_polyline_options($poly_id) {
    global $wpdb;
    $results = $wpdb->get_results("SELECT * FROM wp_custom_map_polyline WHERE id = '".intval($poly_id)."' Limit 1");

 //echo $results->polylines_settings;
    foreach ( $results as $result ) {
       return $result;
    }
}

function custom_map_polyline_array($poly_id) {
    global $wpdb;
    $results = $wpdb->get_results("SELECT * FROM wp_custom_map_polyline WHERE id = '".intval($poly_id)."' Limit 1");

    foreach ( $results as $result ) {

        $current_polydata = $result->polylines_data;

        $new_polydata = str_replace("),(","|",$current_polydata);
        $new_polydata = str_replace("(","",$new_polydata);
        $new_polydata = str_replace("),","",$new_polydata);
	    $new_polydata = str_replace(")","",$new_polydata);
        $new_polydata = explode("|",$new_polydata);

        foreach ($new_polydata as $poly) {
            
            $ret[] = $poly;
        }

        return $ret;
    }
}

function custom_map_polyline_id_array($map_id) {
    global $wpdb;
    $ret = array();
    $results = $wpdb->get_results("SELECT * FROM wp_custom_map_polyline WHERE mid = '".intval($map_id)."'");
    foreach ( $results as $result ) {
        $current_id = $result->id;
        $ret[] = $current_id;
   }
    return $ret;
}
//print_r($poly_array);

/*Three function of filter polygon data*/

 function custom_map_polygon_options($poly_id) {
    global $wpdb;
    $results = $wpdb->get_results("SELECT * FROM wp_custom_map_polygon WHERE id = '".intval($poly_id)."' Limit 1");

     foreach ( $results as $result ) {
       return $result;
    }
}

function custom_map_polygon_array($poly_id) {
    global $wpdb;
    $results = $wpdb->get_results("SELECT * FROM wp_custom_map_polygon WHERE id = '".intval($poly_id)."' Limit 1");
    foreach ( $results as $result ) {

        $current_polydata = $result->polygon_data;

        $new_polydata = str_replace("),(","|",$current_polydata);
        $new_polydata = str_replace("(","",$new_polydata);
        $new_polydata = str_replace("),","",$new_polydata);
	$new_polydata = str_replace(")","",$new_polydata);
        $new_polydata = explode("|",$new_polydata);

        foreach ($new_polydata as $poly) {
            
            $ret[] = $poly;
        }
		return $ret;
    }
}

function custom_map_polygon_id_array($map_id) {
    global $wpdb;
    $ret = array();
    $results = $wpdb->get_results("SELECT * FROM wp_custom_map_polygon WHERE mid = '".intval($map_id)."'");
    foreach ( $results as $result ) {
        $current_id = $result->id;
        $ret[] = $current_id;
   }
    return $ret;
}
?>
<style>
table#tbl_polyline tr:first-child {

    font-weight: bold;
}

table#tbl_polyline {
    border-collapse: collapse;
 }

table#tbl_polyline th, table#tbl_polyline td {
    text-align: center;
    padding: 8px;
}

table#tbl_polyline tr:nth-child(even){background-color: #f2f2f2}

table#tbl_polyline th {
    background-color: #4CAF50;
    color: white;
}
</style>
