<?php if ( ! defined( 'ABSPATH' ) ) exit;
$opt = get_option('custom_map_settings');
$custom_map_settings = array();
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';
custom_map::admin_assets(); ?>
<div class="wrap settings_custom_map">
<h2><?php _e('Settings', 'custom_maps');?></h2>
<?php if(  wp_verify_nonce($_POST['_wpnonce']) && isset($_POST['save_custom_maps']) ):
 _e('<p><strong>Saving Please Wait...</strong></p>', 'custom_maps');
 unset($_POST['_wpnonce'],$_POST['_wp_http_referer'],$_POST['save_custom_maps']);
	foreach( $_POST as $key=>$value ){
		$custom_map_settings[$key] = $value;
	 }
  $updated = update_option('custom_map_settings', $custom_map_settings );
  if($updated)
  {
	custom_map::redirect('admin.php?page=custom_maps_settings&msg=1');
  }
  else
  {
	custom_map::redirect('admin.php?page=custom_maps_settings&msg=2');
  }
 endif;
if($msg == 1):
 custom_map::success('Settings saved successfully');
elseif($msg == 2):
 custom_map::error('Settings not saved.');
endif;
?>
<div class="container">
<form id="savecustommapform" method="post" name="savecustommapform" action="">
<div style="float:right"><input type="submit" value="Save Data" class="button button-primary button-large" name="save_custom_maps"></div>
	<ul class="tabs">
		<li class="tab-link current" data-tab="tab-1"><?php _e('General', 'custom_maps');?></li>
		<li class="tab-link" data-tab="tab-2"><?php _e('Map Controls', 'custom_maps');?></li>
		<li class="tab-link" data-tab="tab-3"><?php _e('Rename Shortcode', 'custom_maps');?> <?php echo custom_map::prolink();?></li>
        <li class="tab-link" data-tab="tab-4"><?php _e('Troubleshoot Problem', 'custom_maps');?></li>
	</ul>
	<div id="tab-1" class="tab-content current">
      <?php wp_nonce_field(); ?> 
     <table class="form-table">
       <tbody>
          <tr>
            <th scope="row"><label for="google_api_key"><?php _e('Google API Key', 'custom_maps');?></label></th>
            <td><input type="text" class="regular-text" value="<?php echo !empty($opt['google_api_key']) ? $opt['google_api_key'] : ''; ?>" id="google_api_key" name="google_api_key"><p id="tagline-description" class="description"><?php _e('Please Insert You Google API. <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">Learn How To Get Google API key.</a>', 'custom_maps');?></p></td>
            </tr>
             <tr>
            <th scope="row"><label for="map_id"><?php _e('Custom Map ID(#)', 'custom_maps');?></label></th>
            <td><input type="text" class="regular-text" value="<?php echo !empty($opt['map_id']) ? $opt['map_id'] : ''; ?>" id="map_id" name="map_id"><p id="tagline-description" class="description"><?php _e('Please set your custom Map ID. Default: <strong>custom_thumbnail_map</strong>, Don\'t use #.', 'custom_maps');?></p></td>
            </tr>             
 <tr>
            <th scope="row"><label for="map_animation"><?php _e('Map Animation', 'custom_maps');?></label></th>
            <td>
            <input type="radio" name="map_animation" value="none" <?php echo ($opt['map_animation'] == 'none') ? 'checked="checked"' : ''; ?>/><?php _e('No Animation', 'custom_maps');?>  <input type="radio" name="map_animation" value="BOUNCE" <?php echo ($opt['map_animation'] == 'BOUNCE') ? 'checked="checked"' : ''; ?>/><?php _e('Bounce', 'custom_maps');?>  <input type="radio" name="map_animation" value="DROP" <?php echo ($opt['map_animation'] == 'DROP') ? 'checked="checked"' : ''; ?>/> <?php _e('Drop', 'custom_maps');?>
            </td>
            </tr>  
 <tr>
<th scope="row"><?php _e('Upload marker logo for the map', 'custom_maps');?></th>
<td>  
<span class='upload'>
        <input type='text' id='map_custom_thumbnail' class='regular-text text-upload' name='map_custom_thumbnail' value='<?php echo esc_url( $opt['map_custom_thumbnail'] ); ?>'/>
        <input type='button' class='button button-upload' value='Upload an image'/></br>
        <?php if(!empty($opt['map_custom_thumbnail'])){?>
        <img style='max-width: 300px; display: block;' src='<?php echo esc_url( $opt['map_custom_thumbnail'] ); ?>' class='preview-upload' />
        <?php } else {?>
     <img style='max-width: 300px; display: none;' src='<?php echo esc_url( $opt['map_custom_thumbnail'] ); ?>' class='preview-upload showimg' />    
        <?php } ?>
    </span>
        <span class="description"><?php _e('Upload marker logo for the map.', 'custom_maps' ); ?></span> 
 </td>
</tr>                
        </tbody>    
       </table>
	</div>
    
	<div id="tab-2" class="tab-content">
     <h2><?php _e('Map Controls', 'custom_maps');?></h2>
       <table class="form-table">
       <tbody>
             <tr>
<th scope="row"><?php _e('Map Pan Control', 'custom_maps');?></th>
<td> <fieldset><label for="map_panControl">
<input type="checkbox" id="map_panControl" name="map_panControl" value="true" <?php echo ($opt['map_panControl'] == 'true') ? 'checked="checked"' : '';?>><?php _e('Check for Map Pan Control', 'custom_maps');?></label>
</fieldset></td>
</tr> 
 <tr>
<th scope="row"><?php _e('Map Zoom Control', 'custom_maps');?></th>
<td> <fieldset><label for="map_zoomControl">
<input type="checkbox" value="true" id="map_zoomControl" name="map_zoomControl" <?php echo ($opt['map_zoomControl'] == 'true') ? 'checked="checked"' : '';?>><?php _e('Check for Map Zoom Control', 'custom_maps');?></label>
</fieldset></td>
</tr> 

<tr>
<th scope="row"><?php _e('Map Type Control', 'custom_maps');?></th>
<td> <fieldset><label for="map_mapTypeControl">
<input type="checkbox" value="true" id="map_mapTypeControl" name="map_mapTypeControl" <?php echo ($opt['map_mapTypeControl'] == 'true') ? 'checked="checked"' : '';?>><?php _e('Check for Map Type Control', 'custom_maps');?></label>
</fieldset></td>
</tr> 

<tr>
<th scope="row"><?php _e('Map Scale Control', 'custom_maps');?></th>
<td> <fieldset><label for="map_scaleControl">
<input type="checkbox" value="true" id="map_scaleControl" name="map_scaleControl" <?php echo ($opt['map_scaleControl'] == 'true') ? 'checked="checked"' : '';?>><?php _e('Check for Map Scale Control', 'custom_maps');?></label>
</fieldset></td>
</tr> 

<tr>
<th scope="row"><?php _e('Map Street View Control', 'custom_maps');?></th>
<td> <fieldset><label for="map_streetViewControl">
<input type="checkbox" value="true" id="map_streetViewControl" name="map_streetViewControl" <?php echo ($opt['map_streetViewControl'] == 'true') ? 'checked="checked"' : '';?>><?php _e('Check for Map Street View Control', 'custom_maps');?></label>
</fieldset></td>
</tr> 

<tr>
<th scope="row"><?php _e('Map Over View Control', 'custom_maps');?></th>
<td> <fieldset><label for="map_overviewMapControl">
<input type="checkbox" value="true" id="map_overviewMapControl" name="map_overviewMapControl" <?php echo ($opt['map_streetViewControl'] == 'true') ? 'checked="checked"' : '';?>><?php _e('Check for Map Over View Control', 'custom_maps');?></label>
</fieldset></td>
</tr> 

<tr>
<th scope="row"><?php _e('Map Rotate Control', 'custom_maps');?></th>
<td> <fieldset><label for="map_rotateControl">
<input type="checkbox" value="true" id="map_rotateControl" name="map_rotateControl" <?php echo ($opt['map_rotateControl'] == 'true') ? 'checked="checked"' : '';?>><?php _e('Check for Map Rotate Control', 'custom_maps');?></label>
</fieldset></td>
</tr> 

<tr>
<th scope="row"><?php _e('Map Scroll Wheel', 'custom_maps');?></th>
<td> <fieldset><label for="map_rotateControl">
<input type="checkbox" value="true" id="map_scrollwheel" name="map_scrollwheel" <?php echo ($opt['map_scrollwheel'] == 'true') ? 'checked="checked"' : '';?>><?php _e('Check for Map Scroll Wheel', 'custom_maps');?></label>
</fieldset></td>
</tr> 
       </tbody>
       </table>
    
	</div>
    
	<div id="tab-3" class="tab-content">
    <span class="red_msg"><?php _e('This Feature is Available in PRO version only. ', 'custom_maps');?><?php echo custom_map::prolink();?></span>
       <img src="<?php echo plugins_url( 'images/shortcode.jpg', __FILE__ );?>" /> 
       <input type="hidden" class="regular-text" value="<?php echo !empty($opt['shortcode_name']) ? $opt['shortcode_name'] : ''; ?>" id="shortcode_name" name="shortcode_name">
	</div>
    <div id="tab-4" class="tab-content">
	   <h2><?php _e('Troubleshoot Problem', 'custom_maps');?></h2>
       <table class="form-table">
       <tbody>
          <tr>
<th scope="row"><?php _e('Remove Map Js if already in use', 'custom_maps');?></th>
<td> <fieldset><label for="map_conflicts">
<input type="checkbox" value="true" id="map_conflicts" name="map_conflicts" <?php echo ($opt['map_conflicts'] == 'true') ? 'checked="checked"' : '';?>><?php _e('Check to remove map.js,  if already in use.', 'custom_maps');?></label>
</fieldset></td>
</tr> 
</tbody>
</table>
	</div>
    <div style="float:right; margin-top:10px;"><input type="submit" value="Save Data" class="button button-primary button-large" name="save_custom_maps"></div>
        </form>
</div><!-- container -->
</div>