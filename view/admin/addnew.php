<?php if ( ! defined( 'ABSPATH' ) ) exit;
global $wpdb;
$opt = get_option('custom_map_settings');
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';
if(!empty($opt['google_api_key']))
			  {
$gAPI = $opt['google_api_key'];				  
wp_enqueue_script( 'custom_maps_gm', 'http://maps.google.com/maps/api/js?key='.$gAPI.'&amp;libraries=places'); 				  
			  } 
 else
			  {	
wp_enqueue_script( 'custom_maps_gm', 'http://maps.google.com/maps/api/js?sensor=false&amp;libraries=places'); 	
			  }
custom_map::admin_assets();
wp_enqueue_script( 'custom_maps_autolocate', plugins_url('/js/jquery.geocomplete.js' , __FILE__ )); 
$mapthemes = custom_map::getMapThemes();
?>
<script>
jQuery(document).ready(function(e) {
 jQuery("#geocomplete").geocomplete({
           map: ".map_canvas",
           details: "form",
		   location: "",
           types: ["geocode", "establishment"],
        }); 
  
});
</script>
<div class="wrap settings_custom_map">
<h2><?php _e('Add New Map', 'custom_maps');?></h2>
<?php
if(  wp_verify_nonce($_POST['_wpnonce']) && isset($_POST['add_custom_maps']) ):
unset($_POST['map_location_name'][0], $_POST['map_location_lat'][0], $_POST['map_location_lng'][0],$_POST['map_location_pc'][0], $_POST['map_location_add'][0]);
       $locations = array(        
                           'map_location_name' => $_POST['map_location_name'],
                           'map_location_lat' => $_POST['map_location_lat'],
						   'map_location_lng' => $_POST['map_location_lng'],	
						   'map_location_pc' => $_POST['map_location_pc'],
						   'map_location_add' => $_POST['map_location_add']
						);
$location = serialize($locations);

$layers = array(
	'map_kml_layer'=>$_POST['ux_check_kml'],
	'map_layer_kml_link'=>$_POST['ux_txt_kml_link'],
	'map_traffic_layer'=>$_POST['ux_txt_traffic_layer'],
	'map_transit_layer'=>$_POST['ux_txt_transit_layer'],
	'map_bicyle_layer'=>$_POST['ux_txt_bicycling_layer'],
	'ux_txt_fusion_layer'=>$_POST['ux_txt_fusion_layer'],
	'map_fusion_source'=>$_POST['ux_txt_fusion_source'],
	'map_fusion_destination'=>$_POST['ux_txt_fusion_destination'],
	'map_imagery'=>$_POST['ux_txt_imagery_layer']
);
$layer = serialize($layers);
//print_r($layer);
//die("hello");
_e("Saving Please wait...", 'custom_maps');
$fieldsData = array(
		sanitize_text_field($_POST['mapName']), 
		sanitize_text_field($_POST['mapHeight']), 
	    sanitize_text_field($_POST['mapWidth']), 
		sanitize_text_field($_POST['lat']),
		sanitize_text_field($_POST['lng']), 
		sanitize_text_field($_POST['map_div']), 
		sanitize_text_field($_POST['zoomLevel']),
		sanitize_text_field($_POST['marker']), 
		sanitize_text_field($_POST['map_view']), 
		sanitize_text_field($_POST['map_animation']),
		sanitize_text_field($_POST['map_theme']),
		$location,
		$layer,
		'0',
	); 
custom_map::generateShortcode($fieldsData);	
exit;	
endif;
if($msg == 2):
custom_map::error('Error to generate shortcode.');
endif;
?>
<div class="container">
<form id="addcustommapform" method="post" name="addcustommapform" action="">
<div style="float:right"><input type="submit" value="Add Shortcode" class="button button-primary button-large" name="add_custom_maps"></div>
	<ul class="tabs">
		<li class="tab-link current" data-tab="tab-1"><?php _e('Map Shortcode General', 'custom_maps');?></li>
        <li class="tab-link" data-tab="tab-2"><?php _e('Add Map Location Markers', 'custom_maps');?></li>
		<li class="tab-link" data-tab="tab-3"><?php _e('Layer Settings', 'custom_maps');?></li>
	</ul>
	<div id="tab-1" class="tab-content current">
      <?php wp_nonce_field(); ?> 
     <table class="form-table">
       <tbody>
           <tr>
            <th scope="row"><label for="mapName"><?php _e('Map Address *', 'custom_maps');?></label></th>
            <td><input type="text" class="regular-text" value="" name="mapName" required="required" id="geocomplete"><p id="tagline-description" class="description"><?php _e('Please Add a map name or title.', 'custom_maps');?></p></td>
            </tr>
            
            <tr>
            <th scope="row"><label for="lat"><?php _e('Latitude - Center', 'custom_maps');?></label></th>
            <td><input type="text" class="regular-text" value="<?php echo !empty($opt['map_center_latitude']) ? $opt['map_center_latitude'] : ''; ?>" id="lat" name="lat"><p id="tagline-description" class="description"><?php _e('Please set your location latitude to set center of Map. - Picking Default from settings', 'custom_maps');?></p></td>
            </tr>
           <tr>
            <th scope="row"><label for="lng"><?php _e('Longitude - Center', 'custom_maps');?></label></th>
            <td><input type="text" class="regular-text" value="<?php echo !empty($opt['map_center_longitude']) ? $opt['map_center_longitude'] : ''; ?>" id="lng" name="lng"><p id="tagline-description" class="description"><?php _e('Please set your location longitude to set center of Map. - Picking Default from settings', 'custom_maps');?></p></td>
            </tr>
            
             <tr>
            <th scope="row"><label for="mapHeight"><?php _e('Map Height *', 'custom_maps');?></label></th>
            <td><input type="text" class="regular-text" value="" id="mapHeight" name="mapHeight" required="required"><p id="tagline-description" class="description"><?php _e('Please set map height, e.g 500px', 'custom_maps');?></p></td>
            </tr>
            <tr>
            <th scope="row"><label for="mapWidth"><?php _e('Map Width ', 'custom_maps');?></label></th>
            <td><input type="text" class="regular-text" value="" id="mapWidth" name="mapWidth"><p id="tagline-description" class="description"><?php _e('Please set map Width, e.g 500px', 'custom_maps');?></p></td>
            </tr>
            <th scope="row"><label for="map_div"><?php _e('Custom Map ID (#)', 'custom_maps');?></label></th>
            <td><input type="text" class="regular-text" value="<?php echo !empty($opt['map_id']) ? $opt['map_id'] : ''; ?>" id="map_div" name="map_div"><p id="tagline-description" class="description"><?php _e('Please set your custom Map div ID. Default: Picking from settings, Don\'t use #.', 'custom_maps');?></p></td>
            </tr> 
            
            <tr>
            <th scope="row"><label for="zoomLevel"><?php _e('Map Zoom Level', 'custom_maps');?></label></th>
            <td><input type="number" class="regular-text" value="9" id="zoomLevel" name="zoomLevel"><p id="tagline-description" class="description"><?php _e('Please set map zoom level.', 'custom_maps');?></p></td>
            </tr>  
            
             <tr>
<th scope="row"><?php _e('Upload marker logo for the map', 'custom_maps');?></th>
<td>  
<span class='upload'>
        <input type='text' id='map_custom_thumbnail' class='regular-text text-upload' name='marker' value='<?php echo esc_url( $opt['map_custom_thumbnail'] ); ?>'/>
        <input type='button' class='button button-upload' value='Upload an image'/></br>
        <?php if(!empty($opt['map_custom_thumbnail'])){
			?>
        <img style='max-width: 300px; display: block;' src='<?php echo esc_url( $opt['map_custom_thumbnail'] ); ?>' class='preview-upload' />
        <?php } else {?>
     <img style='max-width: 300px; display: none;' src='<?php echo esc_url( $opt['map_custom_thumbnail'] ); ?>' class='preview-upload showimg' />    
        <?php } ?>
    </span>
        <span class="description"><?php _e('Upload marker logo for the map.', 'custom_maps' ); ?></span> 
 </td>
</tr>             
          <tr>
            <th scope="row"><label for="map_view"><?php _e('Map View ID', 'custom_maps');?></label></th>
            <td>
            <input type="radio" name="map_view" value="ROADMAP" checked="checked"/> <?php _e('ROADMAP (normal, default 2D map) ', 'custom_maps');?> <input type="radio" name="map_view" value="SATELLITE"/> <?php _e('SATELLITE (photographic map) ', 'custom_maps');?>
            <input type="radio" name="map_view" value="HYBRID"/> <?php _e('HYBRID (photographic map + roads and city names) ', 'custom_maps');?>
            <input type="radio" name="map_view" value="TERRAIN"/> <?php _e('TERRAIN (map with mountains, rivers, etc. )', 'custom_maps');?>
            </td>
            </tr>       
   <tr>
            <th scope="row"><label for="map_animation"><?php _e('Map Animation', 'custom_maps');?></label></th>
            <td>
            <input type="radio" name="map_animation" value="none" <?php echo ($opt['map_animation'] == 'none') ? 'checked="checked"' : ''; ?>/><?php _e('No Animation', 'custom_maps');?>  <input type="radio" name="map_animation" value="BOUNCE" <?php echo ($opt['map_animation'] == 'BOUNCE') ? 'checked="checked"' : ''; ?>/><?php _e('Bounce', 'custom_maps');?>  <input type="radio" name="map_animation" value="DROP" <?php echo ($opt['map_animation'] == 'DROP') ? 'checked="checked"' : ''; ?>/> <?php _e('Drop', 'custom_maps');?>    
            </td>
            </tr> 
             <tr>
            <th scope="row"><label for="map_animation"><?php _e('Map Style Theme', 'custom_maps');?></label></th>
            <td>
            <select id="map_theme" name="map_theme">
             <option value=""><?php _e('Default', 'custom_maps');?></option>
             <?php if(!empty($mapthemes) && is_array($mapthemes)):
			 foreach($mapthemes as $maptheme):?>
             <option value="<?php echo $maptheme; ?>"><?php echo ucwords(str_replace('_',' ',$maptheme)); ?></option>
             <?php endforeach; endif;?>
            </select>
           <p id="tagline-description" class="description"><?php _e('Map Theme', 'custom_maps');?></p>    
            </td>
            </tr>         
             <tr style="display:none">
             <th scope="row"><label for="map_animation"><?php _e('Map Preview', 'custom_maps');?></label></th>
             <td><div class="map_canvas" style="height:300px"></div></td>
          </tr>       
        </tbody>    
       </table>
	</div>
  <div id="tab-2" class="tab-content">
	   <h2><?php _e('Add Location Markers', 'custom_maps');?></h2>
       <table class="form-table">
       <tbody>
         <tr>
         <td> <button type="button" class="button button-primary button-large add_social" ><?php _e('Add Location Markers','map_with_custom_thumbnail');?></button> <span id="morethan10"></span></td>
         </tr>
          <tr>
           <td>	
                   <div class="item_cliche hideIt">
                   <div class="mydata">
						  <label><?php _e('Location Name','custom_maps');?></label>
						  <input type="text" class="input-xlarge trace img_input" name="map_location_name[]" >	<br/>
                          <label><?php _e('Location Latitude','custom_maps');?></label>
						  <input type="text" class="input-xlarge trace img_input" name="map_location_lat[]" ><br/>
                          <label><?php _e('Location Longitude','custom_maps');?></label>
						  <input type="text" class="input-xlarge trace img_input" name="map_location_lng[]" > <br/>                       	
                          <label><?php _e('Location Postal Code','custom_maps');?></label>
						   <input type="text" class="input-xlarge trace img_input" name="map_location_pc[]" ><br/>
                           <label><?php _e('Location Address','custom_maps');?></label>
						  <textarea name="map_location_add[]"></textarea><br/>					
					      <input type="button" class="btn btn-danger delete_item" value="Delete" />
					 <hr />
				   </div>
                  </div>
                  <div class="big_social_cont"></div> 
			</td>
          </tr>          
         </tbody>
         </table>	  
	</div>
	<div id="tab-3" class="tab-content">
	   <h2><?php _e('Layer Settings', 'custom_maps');?></h2>
	   <table class="form-table">
	   <tbody>
		 <tbody>
		<tr>
		<th scope="row"><label for="mapName"><?php _e('KML Layer *', 'custom_maps');?></label></th>
		<td><input type="checkbox" name="ux_check_kml" id="ux_check_kml" onclick="layer_enable()" value="0"><p id="tagline-description" class="description" style="display: inline;"><?php _e('(Tick to enable the KML Layers to display the Geographic Information given by the KML Link.)', 'custom_maps');?></p></td>
		</tr>
		
		<tr id="tr_kml_link" >
		<th scope="row"><label for="mapName"><?php _e('KML Link*', 'custom_maps');?></label></th>
		<td><input type="text" class="regular-text" name="ux_txt_kml_link" id="ux_txt_kml_link"><p id="tagline-description" class="description"><?php _e('KML Layers allows you to display the Geographic Information given by the KML Link.', 'custom_maps');?></p></td>
		</tr>

		<tr>
		<th scope="row"><label for="mapName"><?php _e('Traffic Link*', 'custom_maps');?></label></th>
		<td><input type="checkbox" name="ux_txt_traffic_layer" id="ux_txt_traffic_layer" onclick="layer_enable()" value="0"><p id="tagline-description" class="description" style="display: inline;"><?php _e('(Tick to display Real Time Traffic Conditions of supported Locations).', 'custom_maps');?></p></td>
		</tr>
		<tr>
		<th scope="row"><label for="mapName"><?php _e('Transit Link*', 'custom_maps');?></label></th>
		<td><input type="checkbox" name="ux_txt_transit_layer" id="ux_txt_transit_layer" onclick="layer_enable()" value="0"><p id="tagline-description" class="description"style="display: inline;"><?php _e('(Tick to show Public Transit Network of Locations supported by Transit Information.)', 'custom_maps');?></p></td>
		</tr>
		<tr>
		<th scope="row"><label for="mapName"><?php _e('Bicycling layer*', 'custom_maps');?></label></th>
		<td><input type="checkbox" name="ux_txt_bicycling_layer" id="ux_txt_bicycling_layer" onclick="layer_enable()" value="0"><p id="tagline-description" class="description"style="display: inline;"><?php _e('(Tick to find any Bicycle, Bike Paths or other Bicycling specific Overlays on the Map.)', 'custom_maps');?></p></td>
		</tr>
		<tr>
		<th scope="row"><label for="mapName"><?php _e('Fusion layer*', 'custom_maps');?></label></th>
		<td><input type="checkbox" name="ux_txt_fusion_layer" id="ux_txt_fusion_layer" onclick="layer_enable()" value="0"><p id="tagline-description" class="description"style="display: inline;"><?php _e('(Tick to display your Map with its corresponding Locations and their detailed Information.)', 'custom_maps');?></p></td>
		</tr>
<tr>
		<th scope="row"><label for="mapName"><?php _e('Source & Destination*', 'custom_maps');?></label></th>
		<td><input style="width:30%" type="text" name="ux_txt_fusion_source" id="ux_txt_fusion_source" placeholder="Enter Source for fusion layer"><input type="text" name="ux_txt_fusion_destination" id="ux_txt_fusion_destination" placeholder="Enter destination for fusion layer" style="width:30%"><p id="tagline-description" class="description"  style="display: inline;"></p></td>
		</tr>
		<tr>
		<tr>
		<th scope="row"><label for="mapName"><?php _e('45° Imagery*', 'custom_maps');?></label></th>
		<td><input type="checkbox" name="ux_txt_imagery_layer" id="ux_txt_imagery_layer" onclick="layer_enable()" value="0"><p id="tagline-description" class="description"style="display: inline;"><?php _e('(Apply 45° Imagery ? (only available for map type SATELLITE and HYBRID).)', 'custom_maps');?></p></td>
		</tr>
		</tbody>
		 </table>	  
	</div> 			
	<div style="float:right; margin-top:10px;"><input type="submit" value="Add Shortcode" class="button button-primary button-large" name="add_custom_maps"></div>
        </form>
</div><!-- container -->
</div>
<script>
function layer_enable()
{
	var kml_layer = jQuery("#ux_check_kml").prop("checked");
	if(kml_layer == true)
	{
		//jQuery("#tr_kml_link").css("display","inline-block");
		jQuery("#ux_check_kml").val(1);

	}
	else
	{
		//("#tr_kml_link").css("display","none");
		jQuery("#ux_check_kml").val(0);

		
	}
	var traffic_layer = jQuery("#ux_txt_traffic_layer").prop("checked");
	if(traffic_layer == true)
	{
		jQuery("#ux_txt_traffic_layer").val(1);
	}
	else
	{
		jQuery("#ux_txt_traffic_layer").val(0);
		
	}
	
		
	var transit_layer = jQuery("#ux_txt_transit_layer").prop("checked");
	if(transit_layer == true)
	{
		jQuery("#ux_txt_transit_layer").val(1);
	}
	else
	{
		jQuery("#ux_txt_transit_layer").val(0);
		
	}
	
	var bicycling_layer = jQuery("#ux_txt_bicycling_layer").prop("checked");
	if(bicycling_layer == true)
	{
		jQuery("#ux_txt_bicycling_layer").val(1);
	}
	else
	{
		jQuery("#ux_txt_bicycling_layer").val(0);
		
	}
	
	var fusion_layer = jQuery("#ux_txt_fusion_layer").prop("checked");
	if(fusion_layer == true)
	{
		jQuery("#ux_txt_fusion_layer").val(1);
	}
	else
	{
		jQuery(ux_txt_fusion_layer).val(0);
		
	}
	
	
	var imagery_layer = jQuery("#ux_txt_imagery_layer").prop("checked");
	if(imagery_layer == true)
	{
		jQuery("#ux_txt_imagery_layer").val(1);
	}
	else
	{
		jQuery(ux_txt_imagery_layer).val(0);
		
	}
}
</script>