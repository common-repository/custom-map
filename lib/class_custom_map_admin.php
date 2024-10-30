<?php
if(!class_exists('custom_map_admin'))
{
	class custom_map_admin
	{
		/*
		* Hooks
		*/
		public function __construct()
		{
			add_action('admin_menu', array(&$this,'custom_map_settings_menu'));
			add_action( 'wp_ajax_save_custom_map_settings', array(&$this,'save_custom_map_settings_callback'));
            add_action( 'wp_ajax_save_custom_map_settings', array(&$this,'save_custom_map_settings_callback'));
		}		
		/* 
		* Menus 
		*/
		public function custom_map_settings_menu()
		{
			add_menu_page( 
			__( 'Custom Maps', 'custom_maps' ),
			'Custom Maps',
			'manage_options',
			'custom_maps_dashboard',
			array(&$this,'custom_maps_dashboard_callback'),
			plugins_url( 'view/admin/images/icon.png', dirname( __FILE__ ) ),
			'40'
             );	
			 /* Add new menu */		 
			  add_submenu_page(
			'custom_maps_dashboard',
			 __( 'Add New', 'custom_maps' ),
			'Add New',
			'manage_options',
			'custom_maps_add_new',
			array(&$this,'custom_maps_add_new_callback'));		
			/* Edit Page */
			  add_submenu_page(
			'',
			 __( '', 'custom_maps' ),
			'Edit Schortcode',
			'manage_options',
			'custom_maps_edit',
			array(&$this,'custom_maps_edit_callback'));
			
			/* Settings */
			 add_submenu_page(
			'custom_maps_dashboard',
			 __( 'Settings', 'custom_maps' ),
			'Settings',
			'manage_options',
			'custom_maps_settings',
			array(&$this,'custom_maps_settings_callback'));
			
			/* Advanced Settings*/		 
			 add_submenu_page(
			'custom_maps_dashboard',
			 __( 'Advanced Settings', 'custom_maps' ),
			'Advanced Settings',
			'manage_options',
			'custom_maps_advanced_settings',
			array(&$this,'custom_maps_advanced_settings'));



			/* import */
			 add_submenu_page(
			'custom_maps_dashboard',
			 __( 'Import', 'custom_maps' ),
			'Import',
			'manage_options',
			'custom_maps_import',
			array(&$this,'custom_maps_import_callback'));

			/* Export */
			 add_submenu_page(
			'custom_maps_dashboard',
			 __( 'Export', 'custom_maps' ),
			'Export',
			'manage_options',
			'custom_maps_export',
			array(&$this,'custom_maps_export_callback'));
			
			/* Pro */
			 add_submenu_page(
			'custom_maps_dashboard',
			 __( 'Buy PRO', 'custom_maps' ),
			'Buy PRO',
			'manage_options',
			'custom_maps_buy_pro',
			array(&$this,'custom_maps_buy_pro_callback'));

/* Advanced Settings for Polygons*/		 
			 add_submenu_page(
			'',
			 '',
			'',
			'manage_options',
			'custom_maps_polygons',
			array(&$this,'custom_maps_polygons'));
		}
		/*
		Dashboard
		*/
	    public function custom_maps_dashboard_callback()
	    {
		    custom_map::view('admin','dashboard');  
		}
		/*
		Addnew
		*/
		public function custom_maps_add_new_callback()
		{
			custom_map::view('admin','addnew'); 
		}
		/* 
		edit 
		*/
		public function custom_maps_edit_callback()
		{
			custom_map::view('admin','edit'); 
		}
		
		/*
		Settings
		*/
		public function custom_maps_settings_callback()
		{
			 custom_map::view('admin','settings'); 
		}
		/*Advanced Settings*/
		public function custom_maps_advanced_settings()
		{
			custom_map::view('admin','advancedsettings'); 
			
		}
public function custom_maps_polygons(){
custom_map::view('admin','polygon'); 
}
		/*
		Import
		*/
		public function custom_maps_import_callback()
		{
			custom_map::view('admin','import'); 
		}
		/*
		Export
		*/
		public function custom_maps_export_callback()
		{
			custom_map::view('admin','export'); 
		}
		/*
		Buy Pro
		*/
		public function custom_maps_buy_pro_callback()
		{
			custom_map::view('admin','buypro');
		}
		/*
		Ajax Save
		*/
		public function save_custom_map_settings_callback()
		{
			custom_map::view('admin/ajax','ajax');
		}
	}
}