<?php
if(!class_exists('custom_map_front'))
{
	class custom_map_front
	{
		public function __construct()
		{
			$opt = get_option('custom_map_settings');
			add_shortcode($opt['shortcode_name'], array($this, 'custom_map_shortcode_callback'));
			add_action('wp_head', array(&$this,'custom_map_js_callback'));
		}
		public function custom_map_shortcode_callback($atts)
		{
			$GLOBALS['atts'] = $atts;
			custom_map::view('front','shortcode');
		}
		public function custom_map_js_callback()
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
		}
	}
}