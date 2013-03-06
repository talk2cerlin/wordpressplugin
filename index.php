<?php 
/*
Plugin Name: Edit page
Plugin URI: http://www.linkstreet.in	
Description: Allowing edit permission for particular page for particular user.
Author: Cerlin	
Author URI: http://www.linkstreet.in
Version: 1.0
*/

function pageid()
{
	cssimport();
	echo '<div id="infohomepageedit" >Here we can echo the details of the plugin..</div>';
}

function menu_re_pageedit()
{
	add_menu_page('Test', 'Page Edit', 'install_themes', 'page-edit', 'pageid');
	add_submenu_page('page-edit', 'Plugin info', 'Plugin info', 'install_themes', 'page-edit');
	add_submenu_page('page-edit', 'Assign Page', 'Assign Page', 'install_themes', 'page-edit/assign-page.php');
	add_menu_page('Editing pages', 'Edit pages', 'read', 'page-edit/edit-page.php');
	
	if(current_user_can('administrator'))
		remove_menu_page('page-edit/edit-page.php');
	else
	{
		global $wpdb;
		$current_user_id = get_current_user_id();
		$data = $wpdb->get_row("SELECT * FROM page_permissions WHERE user_id='".$current_user_id."' AND page_id!='0'",ARRAY_A);
		$datacount = count($data);
		if($datacount==0)
			remove_menu_page('page-edit/edit-page.php');
	}
}

function activate()
{
	global $wpdb;
	$tablerows = $wpdb->get_results("SHOW TABLES LIKE 'page_permissions'");
	$countval = count($tablerows);
	if($countval==0)
	{
		$wpdb->query("create table page_permissions (id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,user_id VARCHAR(50),page_id VARCHAR(50))");
	}
}

function cssimport()
{
	$cssurl = plugins_url('page-edit/css/pagestyle.css');
	echo  "<link href='".$cssurl."' type='text/css' rel='stylesheet' />";
}

add_filter( 'wp_default_editor', create_function(null,'return "html";') );
add_action('admin_menu','menu_re_pageedit');
register_activation_hook( __FILE__, 'activate' );
?>
