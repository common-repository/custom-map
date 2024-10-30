<?php if ( ! defined( 'ABSPATH' ) ) exit;
global $wpdb; 
wp_enqueue_style( 'custom_maps_admin', plugins_url('/css/custom_maps_admin.css' , __FILE__ ));
$opt = get_option('custom_map_settings');
$allshortcodes = custom_map::allShortcodes($limit = 3);
$allTrashedshortcodes = custom_map::allShortcodes($limit = 3,'','',1);
$view = isset($_GET['view']) ? $_GET['view'] : '';
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';
?>
<div class="wrap custom_map_dash">
<h1><?php _e('Custom Map Shortcodes', 'custom_maps');?><?php if(count($allshortcodes) < 3) { ?><a class="page-title-action" href="admin.php?page=custom_maps_add_new"><?php _e('Add New', 'custom_maps');?></a> <?php } else { ?><a class="page-title-action" href="javascript:void(0)" onclick="return confirm('Please Buy PRO Version to add more Map shortcodes.')"><?php _e('Add New', 'custom_maps');?></a> <?php echo custom_map::prolink();?><?php } ?></h1>
<?php if($msg == 10):
custom_map::error('You can not use empty ID.');
elseif($msg == 1):
custom_map::success('Item(s) Trashed Successfully.');	
elseif($msg == 2):
custom_map::error('Item(s) Not Trashed.');	
elseif($msg == 3):
custom_map::success('Item(s) Deleted Successfully.');	
elseif($msg == 4):
custom_map::success('Item(s) Not Deleted.');	
elseif($msg == 5):
custom_map::success('Item(s) Restored Successfully.');	
elseif($msg == 6):
custom_map::success('Item(s) Not Restored.');
elseif($msg == 7):
custom_map::success('Item(s) Not Cloned.');		
endif;
/* Bulk Actions */
if(isset($_POST['bulkaction']))
{
	echo 'Performing action please wait....';
	$action_name = $_POST['action_name'];
	$mapshortcode = $_POST['mapshortcode']; //multi
	if(!empty($action_name))
	{
	 custom_map::bulkaction($action_name, $mapshortcode);
	}
}
else if(isset($_GET['action']))
{
	echo 'Performing action please wait....';
	$action_name = $_GET['action'];
	$id = $_GET['id'];
	custom_map::bulkaction($action_name, $id);
}
?>
<form action="" method="post" name="bulkform">
<ul class="subsubsub">
	<li class="all"><a class="current" href="admin.php?page=custom_maps_dashboard"><?php _e('All', 'custom_maps');?><span class="count">(<?php echo count($allshortcodes);?>)</span></a></li>
    <?php if(count($allTrashedshortcodes) > 0) { ?>
    |
    <li class="trashItems"><a class="current" href="admin.php?page=custom_maps_dashboard&view=trash"><?php _e('Trash', 'custom_maps');?> <span class="count">(<?php echo count($allTrashedshortcodes);?>)</span></a></li>
    <?php } ?>
</ul>
<div class="tablenav top">
<div class="alignleft actions bulkactions">
<label class="screen-reader-text" for="bulk-action-selector-top"><?php _e('Select bulk action', 'custom_maps');?></label>
<select id="bulk-action-selector-top" name="action_name">
<option value="-1"><?php _e('Bulk Actions', 'custom_maps');?></option>
<?php if($view == 'trash'){ ?>
<option value="restore"><?php _e('Restore', 'custom_maps');?></option>
<?php } else { ?>
<option value="trash"><?php _e('Move to Trash', 'custom_maps');?></option>
<?php } ?>
<option value="delete"><?php _e('Delete Permanently', 'custom_maps');?></option>
</select>
<input type="submit" value="Apply" class="button action" id="doaction" name="bulkaction">
</div>
</div>


<table class="wp-list-table widefat fixed striped posts">
<thead>
<tr>
<td class="manage-column column-cb check-column" id="cb"><label for="cb-select-all-1" class="screen-reader-text"><?php _e('Select All', 'custom_maps');?></label><input type="checkbox" id="cb-select-all-1"></td>
<th class="manage-column" id="title" scope="col"><?php _e('Map Name', 'custom_maps');?></th>
<th id="author" class="manage-column" scope="col"><?php _e('Map Height', 'custom_maps');?></th>
<th id="author" class="manage-column" scope="col"><?php _e('Map Shortcode', 'custom_maps');?></th>
<th id="author" class="manage-column" scope="col"><?php _e('Map Marker', 'custom_maps');?></th>
<th id="author" class="manage-column" scope="col"></th>
</tr>
</thead>
<tbody id="the-list">
<?php if($view == 'trash') { ?>
<?php if(!empty($allTrashedshortcodes) && is_array($allTrashedshortcodes) && sizeof($allTrashedshortcodes) != 0){
foreach($allTrashedshortcodes as $allshortcode):	
?>
<tr>
<th class="check-column" scope="row"><label for="cb-select-1" class="screen-reader-text"><?php echo $allshortcode->mapName; ?></label>
			<input type="checkbox" value="<?php echo $allshortcode->mid; ?>" name="mapshortcode[]" id="cb-select-1">
</th>

<td class="author column-author" data-colname="Title">
<strong><?php echo $allshortcode->mapName; ?></strong>
<div class="row-actions"><span class="edit"><a href="admin.php?page=custom_maps_dashboard&id=<?php echo $allshortcode->mid; ?>&action=restore"><?php _e('Restore', 'custom_maps');?></a> | </span><span class="trash"><a class="submitdelete" href="admin.php?page=custom_maps_dashboard&id=<?php echo $allshortcode->mid; ?>&action=delete" onclick="return confirm('Are You sure want to delete ?')"><?php _e('Delete Permanently', 'custom_maps');?></a> </span></div>
</td>
<td class="author column-author" data-colname="Title">
<?php echo $allshortcode->mapHeight; ?>
</td>
<td class="author column-author" data-colname="Title">
<code>[<?php echo $opt['shortcode_name']?> id=<?php echo $allshortcode->mid; ?>]</code>
</td>
<td class="author column-author" data-colname="Title">
<?php if(!empty($allshortcode->marker)) 
	{
		$imageUrl = $allshortcode->marker;
	}
	else
	{
		$imageUrl = $opt['map_custom_thumbnail'];
	}
if(!empty($imageUrl)) { ?>
<img style="max-width: 300px; display: block;" src="<?php echo $imageUrl; ?>">
<?php } ?>
</td>
<td class="author column-author" data-colname="Title">
<div class="map-row-action">
<span class="edit">
<a href="admin.php?page=custom_maps_dashboard&id=<?php echo $allshortcode->mid; ?>&action=restore"><?php _e('Restore', 'custom_maps');?></a> | </span> <span class="trash"><a href="admin.php?page=custom_maps_dashboard&id=<?php echo $allshortcode->mid; ?>&action=delete" onclick="return confirm('Are You sure want to delete ?')" class="submitdelete"><?php _e('Delete Permanently', 'custom_maps');?></a></span>
</div>
</td>
</tr>
<?php endforeach; } 
 }  else { 
 if(!empty($allshortcodes) && is_array($allshortcodes) && sizeof($allshortcodes) != 0){
foreach($allshortcodes as $allshortcode):	
?>
<tr>
<th class="check-column" scope="row"><label for="cb-select-1" class="screen-reader-text"><?php echo $allshortcode->mapName; ?></label>
			<input type="checkbox" value="<?php echo $allshortcode->mid; ?>" name="mapshortcode[]" id="cb-select-1">
</th>

<td class="author column-author" data-colname="Title">
<strong><?php echo $allshortcode->mapName; ?></strong>
<div class="row-actions"><span class="edit"><a href="admin.php?page=custom_maps_edit&id=<?php echo $allshortcode->mid; ?>"><?php _e('Edit', 'custom_maps');?></a> | </span><span class="trash"><a class="submitdelete" href="admin.php?page=custom_maps_dashboard&id=<?php echo $allshortcode->mid; ?>&action=trash"><?php _e('Trash', 'custom_maps');?></a> | </span><a class="submitdelete" href="javascript:void(0)" onclick="return confirm('This Feature is available in PRO Version.')"><?php _e('Clone', 'custom_maps');?></a> | </span><span class="edit"><a href="javascript:void(0)" onclick="return confirm('This Feature is available in PRO Version.')"><?php _e('Export', 'custom_maps');?></a> </span></div>
</td>
<td class="author column-author" data-colname="Title">
<?php echo $allshortcode->mapHeight; ?>
</td>
<td class="author column-author" data-colname="Title">
<code>[<?php echo $opt['shortcode_name']?> id=<?php echo $allshortcode->mid; ?>]</code>
</td>
<td class="author column-author" data-colname="Title">
<?php if(!empty($allshortcode->marker)) 
	{
		$imageUrl = $allshortcode->marker;
	}
	else
	{
		$imageUrl = $opt['map_custom_thumbnail'];
	}
if(!empty($imageUrl)) { ?>
<img style="max-width: 300px; display: block;" src="<?php echo $imageUrl; ?>">
<?php } ?>
</td>
<td class="author column-author" data-colname="Title">
<div class="map-row-action">
<span class="edit">
<a href="admin.php?page=custom_maps_edit&id=<?php echo $allshortcode->mid; ?>"><?php _e('Edit', 'custom_maps');?></a> | </span>
<span class="trash">
<a href="admin.php?page=custom_maps_dashboard&id=<?php echo $allshortcode->mid; ?>&action=trash" class="submitdelete"><?php _e('Trash', 'custom_maps');?></a>
| </span>
<span class="edit">
<a href="javascript:void(0)" onclick="return confirm('This Feature is available in PRO Version.')"><?php _e('Clone', 'custom_maps');?></a>
| </span>
<span class="edit">
<a href="javascript:void(0)" onclick="return confirm('This Feature is available in PRO Version.')"><?php _e('Export', 'custom_maps');?></a> </span>
</div>
</td>
</tr>
<?php endforeach; } }?>
</tbody>
<tfoot>
<tr>
<td class="manage-column column-cb check-column" id="cb"><label for="cb-select-all-1" class="screen-reader-text"><?php _e('Select All', 'custom_maps');?></label><input type="checkbox" id="cb-select-all-1"></td>
<th class="manage-column" id="title" scope="col"><?php _e('Map Name', 'custom_maps');?></th>
<th id="author" class="manage-column" scope="col"><?php _e('Map Height', 'custom_maps');?></th>
<th id="author" class="manage-column" scope="col"><?php _e('Map Shortcode', 'custom_maps');?></th>
<th id="author" class="manage-column" scope="col"><?php _e('Map Marker', 'custom_maps');?></th>
<th id="author" class="manage-column" scope="col"></th>
</tr>
</tfoot>
</table>
</form>
</div>