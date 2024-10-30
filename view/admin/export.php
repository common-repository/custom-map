<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
wp_enqueue_style( 'custom_maps_admin', plugins_url('/css/custom_maps_admin.css' , __FILE__ ));
wp_enqueue_script( 'custom_maps_admin', plugins_url('/js/custom_maps_admin.js' , __FILE__ )); 
?>
<div class="wrap settings_custom_map">
<h2><?php _e('Export Custom Maps', 'custom_maps');?></h2>
<ul class="tabs">
		<li class="tab-link current" data-tab="tab-1"><?php _e('Export Custom Maps', 'custom_maps');?> <?php echo custom_map::prolink();?></li>
</ul>
<img src="<?php echo plugins_url( 'images/export.jpg', __FILE__ );?>" /> 
</div>