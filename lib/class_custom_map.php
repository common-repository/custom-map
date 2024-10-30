<?php
if(!class_exists('custom_map'))
{
	class custom_map
	{	
	    /*
		* Activation Process
		*/
		static function custom_map_activation_process()
		{
		   self::view('admin/install','install');
	    }
	  /*
	  * render
	  */
	   static function render($classname, $instance = true)
	   {
		   if(!empty($classname))
		   {
			   include('class_'.$classname.'.php');
			   if(class_exists($classname) && $instance == true)
			   {
				   self::instance($classname);
			   }
		   }
	   }
	  /*
	  * Instance of class
	  */
	  static function instance($classname)
	  {
		  if(!empty($classname))
		  {
			  new $classname;
		  }
	  }
	  /*
	  * render view
	  */
		static function view($folder, $filename)
	  {
		   if(!empty($filename) && !empty($folder))
		   {
			   include(dirname(__DIR__).'/view/'.$folder.'/'.$filename.'.php');
		   }
	  }
	  /*
	  * db Table
	  */
	   static function dbTable()
	   {
		  global $wpdb;
		  $tbl =  $wpdb->prefix.'custom_map';
		  return $tbl;
	   }
 /*
	  * db Polyline Table
	  */
	   static function polyline_dbTable()
	   {
		  global $wpdb;
		  $tbl =  $wpdb->prefix.'custom_map_polyline';
		  return $tbl;
	   }
 /*
	  * db Polygon Table
	  */
	   static function polygon_dbTable()
	   {
		  global $wpdb;
		  $tbl =  $wpdb->prefix.'custom_map_polygon';
		  return $tbl;
	   }
	   /*
	   * All Shortcodes
	   */
	   static function allShortcodes($limit = null, $order = null, $orderBy = null, $isTrashed = null)
	   {
		   global $wpdb;
			   if(empty($limit))
			   {
				   $limit = 10;
			   }
			   if(empty($order))
			   {
				   $order = 'DESC';
			   }
			   if(empty($orderBy))
			   {
				   $orderBy = 'mid';
			   }
			 if($isTrashed == 1)
			 {
				 $allshortcodes = $wpdb->get_results('select * from '.self::dbTable().' where map_trashed = 1 ORDER BY '.$orderBy.' '.$order.'');
			 }
			 else
			 {
				 $allshortcodes = $wpdb->get_results('select * from '.self::dbTable().' where map_trashed = 0 ORDER BY '.$orderBy.' '.$order.' LIMIT '.$limit.''); 
			 }
		 return $allshortcodes;
	   }
	   /*
	   * single shortcode data
	   */
	   static function singleShortcode($id)
	   {
		    global $wpdb;
		    $singleShortcodeData = $wpdb->get_row('select * from '.self::dbTable().' where mid = "'.$id.'"'); 
			return $singleShortcodeData;
	   }
	   /*
	   * Export Settings
	   */
	   static function exportSettings()
	   {
		  /* Avaiable in Pro Version */	
	   }
	    /*
	   * Export Shortcodes
	   */
	   static function exportShortcodes()
	   {
		   /* Avaiable in Pro Version */	
	   }
	 /*
	   * Gererate Shortcode
	   */
	   static function generateShortcode($fieldsdata)
	   {
		    global $wpdb;
			$saveData = $wpdb->query( $wpdb->prepare( 
			"
				INSERT INTO ".self::dbTable()."
				( mapName, mapHeight, mapWidth, latC, longC, map_div, zoomLevel, marker, map_view, map_animation, map_theme, map_locations,map_layers, map_trashed )
				VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
			", 
				$fieldsdata
		) );		
		 	if($saveData)
				{
					$lastid = $wpdb->insert_id;
					self::redirect('admin.php?page=custom_maps_edit&id='.$lastid.'&msg=1');
				}
				else
				{
					self::redirect('admin.php?page=custom_maps_add_new&msg=2');
				}	
	   }

         /*
	  * inserting data for polylines for particluar map 
	  */ 
	  static function generateShortcode_polylines($fieldsdata,$mid)
	   {
		    global $wpdb;
		    $savadata=$wpdb->insert(self::polyline_dbTable(),$fieldsdata);
if($savadata)
			{
				self::redirect('admin.php?page=custom_maps_advanced_settings&map_id='.$mid.'&msg=1');
			}
			else
			{
				self::redirect('admin.php?page=custom_maps_advanced_settings&map_id='.$mid.'&msg=2');
			}	
	  }

/*
Three Filter functions of Polyline data  
-->custom_map_polyline_options()
-->custom_map_polyline_array()
-->custom_map_polyline_id_array()
*/
static function custom_map_polyline_options($poly_id) {
    global $wpdb;
    $results = $wpdb->get_results("SELECT * FROM wp_custom_map_polyline WHERE id = '".intval($poly_id)."' Limit 1");
   //echo $results->polylines_settings;
    foreach ( $results as $result ) {
       return $result;
    }
}

static function custom_map_polyline_array($poly_id) {
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

static function custom_map_polyline_id_array($map_id) {
    global $wpdb;
    $ret = array();
    $results = $wpdb->get_results("SELECT * FROM wp_custom_map_polyline WHERE mid = '".intval($map_id)."'");
    foreach ( $results as $result ) {
        $current_id = $result->id;
        $ret[] = $current_id;
   }
    return $ret;
}
/*
End of Filter of Polylines
*
/

/*
Three Filter functions of Polyline data  
-->custom_map_polygon_options()
-->custom_map_polygon_array()
-->custom_map_polygon_id_array()
*/

static function custom_map_polygon_options($poly_id) {
    global $wpdb;
    $results = $wpdb->get_results("SELECT * FROM wp_custom_map_polygon WHERE id = '".intval($poly_id)."' Limit 1");

     foreach ( $results as $result ) {
       return $result;
    }
}

static function custom_map_polygon_array($poly_id) {
    global $wpdb;
    $results = $wpdb->get_results("SELECT * FROM wp_custom_map_polygon WHERE id = '".intval($poly_id)."' Limit 1");
//print_r( $results);
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

static function custom_map_polygon_id_array($map_id) {
    global $wpdb;
    $ret = array();
    $results = $wpdb->get_results("SELECT * FROM wp_custom_map_polygon WHERE mid = '".intval($map_id)."'");
    foreach ( $results as $result ) {
        $current_id = $result->id;
        $ret[] = $current_id;
   }
    return $ret;
}

/*
End of Filter of Polygons
*
/




      /*
	  * deleting data for polylines for particluar map 
	  */ 
	 static function delete_polylines($m_id,$where)
	 {
		    
		global $wpdb;
		$deldata=$wpdb->delete(self::polyline_dbTable(), $where);
		if($deldata)
		{
			self::redirect('admin.php?page=custom_maps_advanced_settings&map_id='.$m_id.'&msg=3');
		}
		else
		{
			self::redirect('admin.php?page=custom_maps_advanced_settings&map_id='.$m_id.'&msg=4');
		}
	 }

	  /*
	  * inserting data for polygons for particluar map 
	  */ 
	  static function generateShortcode_polygons($fieldsData,$mid)
	  {
			global $wpdb;
			$savadata=$wpdb->insert(self::polygon_dbTable(),$fieldsData);
			
			if($savadata)
			{
				self::redirect('admin.php?page=custom_maps_polygons&map_id='.$mid.'&msg=1');
			}
			else
			{
				self::redirect('admin.php?page=custom_maps_polygons&map_id='.$mid.'&msg=2');
			}				
	  }
	  
	 /*
	   * deleting polygons for particluar map 
	  */ 
	 static function delete_polygon($m_id,$where)
	 {
		    
		global $wpdb;
		$deldata=$wpdb->delete(self::polygon_dbTable(), $where);
		if($deldata)
		{
			self::redirect('admin.php?page=custom_maps_polygons&map_id='.$m_id.'&msg=3');
		}
		else
		{
			self::redirect('admin.php?page=custom_maps_polygons&map_id='.$m_id.'&msg=4');
		}
	 }
/*
	* This function check how many polygons/polylines created
	*/ 
	static function count_check_polyline_polygon($mid,$tbl_name)
	{
		  global $wpdb;
		  $count_polyline_polygon = $wpdb->get_var('select count(mid) from '.$tbl_name.' where mid = "'.$mid.'"'); 
		  return $count_polyline_polygon;
	}
/*Bulk action for polygon and polylines*/
static function bulkaction_Polygon_polylines($page_name,$ids,$tbl_name,$mid)
{         
   if(is_array($ids) && !empty($ids))
	{ 
        global $wpdb;
         foreach($ids as $id):
           $where=array();
           $where['id']=$id;
		   $deldata=$wpdb->delete($tbl_name, $where);		
		   $is_trash = true;
		endforeach;
if($is_trash == true):
self::redirect('admin.php?page='.$page_name.'&map_id='.$mid.'&msg=5');
else:
self::redirect('admin.php?page='.$page_name.'&map_id='.$mid.'&msg=6');
endif;
    }
}
	 /*
	  * Update Shortcode
	  */ 
	  static function updateShortcode($fieldsdata, $id)
	   {
		    global $wpdb;
			$updateData = $wpdb->update( 
				self::dbTable(), $fieldsdata, array( 'mid' => $id ), 
				array( 
					 '%s', '%s', '%s', '%s', '%s','%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s' ,'%s'
				), 
				array( '%d' ) 
			);
			if($updateData)
			{
				self::redirect('admin.php?page=custom_maps_edit&id='.$id.'&msg=2');
			}
			else
			{
				self::redirect('admin.php?page=custom_maps_edit&id='.$id.'&msg=3');
			}
	   }
	 /*
	 * Bulk Actions
	 */ 
	 static function bulkaction($action = 'trash', $ids)
	 {
		global $wpdb; 
		/*if multiple */
		if(is_array($ids) && !empty($ids))
		{ 
		     /* Multi Trash */
			 if($action == 'trash')
			 {
				$is_trash = false;
				foreach($ids as $mid):
				$updateData = $wpdb->update( 
				self::dbTable(), array('map_trashed' => 1), array( 'mid' => $mid ), 
				array( 
					 '%s' 
				), 
				array( '%d' ) 
			   );
			   $is_trash = true;
			   endforeach;
			   if($is_trash)
			   {
				 self::redirect('admin.php?page=custom_maps_dashboard&msg=1');  
			   }
			   else
			   {
				 self::redirect('admin.php?page=custom_maps_dashboard&msg=2');     
			   }			   
			 }
			 /* Multi delete */
			 elseif( $action == 'delete')
			 {
				$is_delete = false;
				foreach($ids as $mid):
				$delete_data = $wpdb->delete( 
				  self::dbTable(), array( 'mid' => $mid )		
			     );
			   $is_delete = true;
			   endforeach;
			   if($is_delete)
			   {
				 self::redirect('admin.php?page=custom_maps_dashboard&msg=3');  
			   }
			   else
			   {
				 self::redirect('admin.php?page=custom_maps_dashboard&msg=4');     
			   }
			 }
			 /* Multi restore */
			 elseif($action == 'restore')
			 {
			    $is_restore = false;
				foreach($ids as $mid):
				$updateData = $wpdb->update( 
				self::dbTable(), array('map_trashed' => 0), array( 'mid' => $mid ), 
				array( 
					 '%s' 
				), 
				array( '%d' ) 
			   );
			   $is_restore = true;
			   endforeach;
			   if($is_restore)
			   {
				 self::redirect('admin.php?page=custom_maps_dashboard&msg=5');  
			   }
			   else
			   {
				 self::redirect('admin.php?page=custom_maps_dashboard&msg=6');     
			   }			 
			 }
		}
		else
		{
			/* Trash */
		    if($action == 'trash')
			 {
				$is_trash = $wpdb->update( 
				self::dbTable(), array('map_trashed' => 1), array( 'mid' => $ids ), 
				array( 
					 '%s' 
				), 
				array( '%d' ) 
			   );
			    if($is_trash)
			   {
				 self::redirect('admin.php?page=custom_maps_dashboard&msg=1');  
			   }
			   else
			   {
				 self::redirect('admin.php?page=custom_maps_dashboard&msg=2');     
			   }	
			 }
			 /* Delete */
			 elseif( $action == 'delete')
			 {
			   $is_delete = $wpdb->delete( 
				  self::dbTable(), array( 'mid' => $ids )		
			     ); 
			   if($is_delete)
			   {
				 self::redirect('admin.php?page=custom_maps_dashboard&msg=3');  
			   }
			   else
			   {
				 self::redirect('admin.php?page=custom_maps_dashboard&msg=4');     
			   }
			 }
			 /* restore */
			 elseif($action == 'restore')
			 {
				$is_restore = $wpdb->update( 
				self::dbTable(), array('map_trashed' => 0), array( 'mid' => $ids ), 
				array( 
					 '%s' 
				), 
				array( '%d' ) 
			   );
			    if($is_restore)
			   {
				 self::redirect('admin.php?page=custom_maps_dashboard&msg=5');  
			   }
			   else
			   {
				 self::redirect('admin.php?page=custom_maps_dashboard&msg=6');     
			   }	
			 }
			 /* restore */
			 elseif($action == 'clone')
			 {
			  /* Avaiable in Pro Version */	 
			 }
		}
	 }
	  /*
	 * Get all Map Themes
	 */  
	 static function getMapThemes()
	 {
        //$dir = str_replace('lib\\','',dirname( __FILE__ ).'\view\admin\themes/');
	    $dir = str_replace('lib/','',dirname( __FILE__ ).'/view/admin/themes/');
		$theme_files = glob($dir."/*.php");
		$mapthemes = array();
		foreach($theme_files as $theme_file){
			$mapthemes[basename($theme_file,".php")]=basename($theme_file,".php");
		}
		return $mapthemes;
	 }
	  /*
	   * Redirect
	   */
	   static function redirect($url)
	   {
		   echo '<script>window.location.href="'.$url.'";</script>';
	   }
	   /*
	   * Success
	   */
	   static function success($msg)
	   {
		   echo '<div id="message" class="updated" ><p>'.$msg.'</p></div>';
	   }
	   /*
	   * Success
	   */
	   static function error($msg)
	   {
		   echo '<div id="message" class="updated error" ><p>'.$msg.'</p></div>';
	   }
	   /*
	   * Some Important js and css
	   */
	   static function admin_assets()
	   {
		    wp_enqueue_style( 'custom_maps_admin', plugins_url('/view/admin/css/custom_maps_admin.css', dirname( __FILE__ ) ));
			wp_enqueue_style( 'thickbox' );
			wp_enqueue_script( 'thickbox' );
			wp_enqueue_script( 'media-upload' );
			wp_enqueue_script( 'custom_maps_admin', plugins_url('/view/admin/js/custom_maps_admin.js', dirname( __FILE__ ))); 
	   }	
	   /*
	   * Pro Link
	   */ 
	   static function prolink()
	   {
		   $link = "<a href='http://www.webdesi9.com/product/custom-map/' title='Buy Pro Version' target='_blank' class='buy_pro page-title-action'>Buy PRO</a>";
		   return $link;
	   }
 } //class end
} // class exists end