<?php if ( ! defined( 'ABSPATH' ) ) exit;
global $wpdb;
custom_map::admin_assets();

$allshortcodes = custom_map::allShortcodes($limit = 200,'ASC'); 
$opt = get_option('custom_map_settings');
if(isset($_REQUEST['map_id']))
{
echo $map_id=$_REQUEST['map_id'];
$shortcodeData = custom_map::singleShortcode($map_id);
//print_r($shortcodeData);

if($mapoptions['map_div'] == '')
{
	$mapoptions['map_div'] = $shortcodeData->map_div;
}
}
?>
<script>
jQuery(document).ready(function(){
  initMap();
});
</script>
<style>
       #mapid{
        width: 100%;
        height: 400px;
      }
</style>
<div class="wrap settings_custom_map">
	<h2><?php _e('Advanced Settings', 'custom_maps');?></h2>
	<div class="container">
		<ul class="tabs">
			<li class="tab-link current" data-tab="tab-1"><?php _e('Polylines', 'custom_maps');?></li>
			<li class="tab-link" data-tab="tab-2"><?php _e('Polygon', 'custom_maps');?></li>
			<li class="tab-link" data-tab="tab-3"><?php _e('Heap Maps', 'custom_maps');?></li>
		</ul>
		<div id="tab-1" class="tab-content current">
              <form name="form_advanced_settings">
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
			<td><input type="text" class="regular-text" name="ux_txt_polyline_color" id="ux_txt_polyline_color"></td>
			</tr>
			
			<tr>
			<th scope="row"><label for="mapName"><?php _e('Polyline Opacity*', 'custom_maps');?></label></th>
			<td><input type="text" class="regular-text" name="ux_txt_polyline_opacity" id="ux_txt_polyline_opacity"></td>
			</tr>
			
			<tr>
			<th scope="row"><label for="mapName"><?php _e('Polyline Thickeness*', 'custom_maps');?></label></th>
			<td><input type="text" class="regular-text" name="ux_txt_polyline_color" id="ux_txt_polyline_color"></td>
			</tr>
			
			<tr>
			<th scope="row"><label for="mapName"><?php _e('Polyline Type*', 'custom_maps');?></label></th>
			<td>
				<select id="ux_polyline_type" name="ux_polyline_type">
					<option value='Solid' selected="selected">Solid</option>
					<option value='Dashed'>Dashed</option>
					<option value='Dotted'>Dotted</option>
				</select>
			</td>
                        
			</tr>
<tr>
			<th scope="row"><label for="mapName"><?php _e('Polyline Data*', 'custom_maps');?></label></th>
			<td>
				<textarea rows="4" cols="50" id="ux_polyline_data" name="ux_polyline_data" readonly>
At w3schools.com you will learn how to make a website. We offer free tutorials in all web development technologies. 
</textarea>
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
		<div id="tab-2" class="tab-content">
		  tab2
		</div>  
		<div id="tab-3" class="tab-content">
			tab3
		</div> 		  
	</div><!-- container -->
</div>
<script>
jQuery('#custom_map_shortcode').on('change', function() {
  var map_id=this.value;
  window.location.href = "admin.php?page=custom_maps_advanced_settings&map_id="+map_id;
initMap();
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
<div id="mapid"></div>
    <script>
 function initMap() {
       var map;
        map = new google.maps.Map(document.getElementById('mapid'), {
          center: {lat: -34.397, lng: 150.644},
          zoom: 8
        });

// Define a symbol using SVG path notation, with an opacity of 1.
  var lineSymbol = {
    path: 'M 0,-1 0,1 0,-1',
    strokeOpacity: 1,
    scale: 4
  };

 poly = new google.maps.Polyline({
	  strokeColor: '#000000',
 icons: [{
      icon: lineSymbol,
      offset: '0',
      repeat: '20px'
    }],
          strokeOpacity: 1.0,
          strokeWeight: 3
        });
        poly.setMap(map);

       
        map.addListener('click', addLatLng);
      }

      
      function addLatLng(event) {

        var path = poly.getPath();
        
       jQuery("#ux_polyline_data").val(event.latLng);

        path.push(event.latLng);

        // Add a new marker at the new plotted point on the polyline.
        /*var marker = new google.maps.Marker({
          position: event.latLng,
          title: '#' + path.getLength(),
          map: map
        });*/
      }
google.maps.event.addDomListener(window, 'load', initMap);
    </script>

<?php }?>
