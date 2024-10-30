<?php global $wpdb;
          $custom_map_settings =  array(
                                        "map_center_latitude" => '',
										"map_center_longitude"=> '',
										"google_api_key"=> '',
										"map_id"=> 'custom_map',
										"map_conflicts" => 'false',
										"map_animation" => 'none',
										"map_custom_thumbnail" => '',														
										"map_panControl" => 'true',
										"map_zoomControl" => 'true',
										"map_mapTypeControl" => 'true',
										"map_scaleControl" => 'true',
										"map_streetViewControl" => 'true',
										"map_overviewMapControl" => 'true',
										"map_rotateControl" => 'true',
										"map_scrollwheel" => 'true',
										"shortcode_name" => 'custom_map'
										);
$opt = get_option('custom_map_settings');
require_once(ABSPATH.'wp-admin/includes/upgrade.php');
            $sql = "CREATE TABLE IF NOT EXISTS ".self::dbTable()." (
				`mid` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`mapName` varchar(255) NOT NULL,
				`mapHeight` varchar(255) NOT NULL,
				`mapWidth` varchar(255) NOT NULL,
				`latC` varchar(255) NOT NULL,
				`longC` varchar(255) NOT NULL,
				`map_div` varchar(255) NOT NULL,
				`zoomLevel` varchar(255) NOT NULL,
				`marker` varchar(255) NOT NULL,
				`map_view` varchar(255) NOT NULL,
				`map_animation` varchar(255) NOT NULL,
				`map_theme` varchar(255) NOT NULL,
				`map_locations` varchar(5000) NOT NULL,
				`map_layers` varchar(5000) NOT NULL,
				`map_trashed` int(10) NOT NULL,
				 PRIMARY KEY (`mid`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";
			  dbDelta($sql);
			  
			   $sql1 = "CREATE TABLE IF NOT EXISTS ".self::polyline_dbTable()." (
				`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`mid` int(10) NOT NULL,
				`polylines_settings` varchar(255) NOT NULL,
				`polylines_data` longtext NOT NULL,
				 PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";
			  dbDelta($sql1);
			  
			  $sql2 = "CREATE TABLE IF NOT EXISTS ".self::polygon_dbTable()." (
				`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`mid` int(10) NOT NULL,
				`polygon_settings` varchar(255) NOT NULL,
				`polygon_data` longtext NOT NULL,
				 PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";
			  dbDelta($sql2);
			  
			  flush_rewrite_rules();
			 if(!$opt['map_id'] && empty($opt['map_id'])) {
				update_option('custom_map_settings', $custom_map_settings);
			 }
?>