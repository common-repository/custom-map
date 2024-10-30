<?php
/*
Plugin Name: Custom Maps
Plugin URI: https://wordpress.org/plugins/custom-map/
Description: Add map with multiple locations and with custom thumbnail
Author: mndpsingh287
Version: 1.1
Author URI: https://profiles.wordpress.org/mndpsingh287/
License: GPLv2
Text Domain: custom maps 
*/
if(!defined("custom_maps"))
{ 
   define("custom_maps", "custom_maps");
}
include('lib/class_custom_map.php');
register_activation_hook(__FILE__, 'custom_map_activation_process');
function custom_map_activation_process()
{
  custom_map::custom_map_activation_process();	
}
/* Custom Map admin class */
if(is_admin()):
custom_map::render('custom_map_admin');
endif;
/* Custom Map front class */
custom_map::render('custom_map_front');
add_filter('upload_mimes','add_custom_mime_types');
    function add_custom_mime_types($mimes){
        return array_merge($mimes,array (
            'gpx' => 'application/gpx',
            'kml' => 'application/vnd.google-earth.kml+xml',
            'kmz' => 'application/vnd.google-earth.kmz+xml'
            ));
}