<?php if ( ! defined( 'ABSPATH' ) ) exit;
wp_enqueue_style( 'custom_maps_admin', plugins_url('/css/custom_maps_admin.css' , __FILE__ ));
wp_enqueue_script( 'custom_maps_admin', plugins_url('/js/custom_maps_admin.js' , __FILE__ )); 
?>
<div class="wrap settings_custom_map">
<h2><?php _e('Import Custom Maps', 'custom_maps');?></h2>
<ul class="tabs">
		<li class="tab-link current" data-tab="tab-1"><?php _e('Import Custom Maps', 'custom_maps');?> <?php echo custom_map::prolink();?></li>
</ul>
<div id="tab-1" class="tab-content current">
  <table class="form-table">
       <tbody>
            <tr>
            <th scope="row"><label for="import_settings"><?php _e('Import Map Setting', 'custom_maps');?></label></th>
            <td><textarea rows="10" class="large-text code" id="settingData" name="settingData" disabled=""><?php _e('Feature Available in PRO version.', 'custom_maps');?></textarea><p id="tagline-description" class="description"><?php _e('Paste Setting data to import.', 'custom_maps');?><?php echo custom_map::prolink();?></p>
            </td>
            <td>
            <button id="import_settings" class="button button-primary button-large" onclick="importSettings()"><?php _e('Import Settings', 'custom_maps');?></button>           <p id="st_status"></p>
            </td>
            <hr />
            </tr>
             <tr>
            <th scope="row"><label for="import_shortcodes"><?php _e('Import Map Shortcodes', 'custom_maps');?></label></th>
            <td><textarea rows="10" class="large-text code" id="shortcodeData" name="shortcodeData" disabled="disabled"><?php _e('Feature Available in PRO version.', 'custom_maps');?></textarea><p id="tagline-description" class="description"><?php _e('Paste Map shortcode data to import.', 'custom_maps');?><?php echo custom_map::prolink();?></p></td>
            <td>
         <button id="import_shortcodes" class="button button-primary button-large" onclick="importShortcodes()"><?php _e('Import Shortcodes', 'custom_maps');?></button>     <p id="sh_status"></p>        
            </td>
            </tr>
         </tbody>
    </table>   
</div>
</div>