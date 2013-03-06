<?php
jsimport();
cssimport();
if(isset($_POST['userid']) && $_POST['userid'] != "" && $_POST['userid'] != null)
{
	global $wpdb;
	$tablerows = $wpdb->get_results("SHOW TABLES LIKE 'page_permissions'");
	$countval = count($tablerows);
	if($countval==0)
	{
		echo "DATABASE ERROR. Please Re-activate the plugin.";
	}
	else
	{
		$args = array('post_type' => 'page','post_status' => 'publish'); 
		$pagelist = get_pages($args);
		$current_user_id = get_current_user_id();
		$data = $wpdb->get_row("SELECT * FROM page_permissions WHERE user_id='".$_POST['userid']."'",ARRAY_A);
		$datacount = count($data);
		if($datacount!=0)
		{
			$dataarray = explode(",",$data['page_id']);
		}
		?>
		<div id="info"><p>Select the PAGE which the selected user can edit.</p></div><br />
		<table class="tablecss">
		<form action="" method="POST" name="important">
		<tr><td><input type="hidden" name="useridval" value="<?php echo $_POST['userid']; ?>" /></td></tr>
		<?php foreach($pagelist as $value): ?>
			<tr><td><input type="checkbox" name="pagelist[]" value="<?php echo $value->ID; ?>" <?php if(isset($dataarray)) { if(in_array($value->ID,$dataarray)) echo "checked"; } ?> /><?php if($value->post_title == null) echo "  [NO NAME]"; else echo "  ".$value->post_title; ?></td></tr>
		<?php endforeach ?>
		<tr><td><input type="submit" name="Add" value="Add" class="button" /></td></tr>
		</form></table>
<?php }
}
elseif(isset($_POST['useridval']) && $_POST['useridval'] != "" && $_POST['useridval'] != null)
{
	global $wpdb;
	$usercheck = $wpdb->get_row("SELECT * from page_permissions where user_id='".$_POST['useridval']."'");
	$countval = count($usercheck);
	if(isset($_POST['pagelist']) || $_POST['pagelist'] != null && $_POST['pagelist'] != "")
		$pagelist = implode(",",$_POST['pagelist']);
	else 
		$pagelist = 0;
	$user_id = $_POST['useridval'];
	if($countval==0)
	{
		if($wpdb->query("INSERT into page_permissions(`user_id`,`page_id`) values ('".$user_id."','".$pagelist."')"))
		{
			echo "Added. Redirecting please wait...";
			echo '<script>setTimeout(function(){location.reload(true)},2000);</script>';
		}
	}
	else
	{
		if($wpdb->query("UPDATE page_permissions SET page_id='".$pagelist."' WHERE user_id='".$user_id."'"))
		{
			echo "Updated. Redirecting please wait...";
			echo '<script>setTimeout(function(){location.reload(true)},2000);</script>';
		}
		else
		{
			echo '<div id="information">No changes to update. Please make some changes and update.</div>';
		}
	}
}
else
{
	$users = get_users();?>
	<div id="info"><p>Select the user whom you want to give the privilages to edit.</p></div><br />
	<table class="tablecss">
<?php 
	$incre = 1;
	foreach($users as $val) : 
		if($val->roles[0] != administrator) :?>
			<tr><td><?php echo $incre.". "; $incre++ ?></td><td>
			<form action="" method="POST" name="important">
			<input type="hidden" name="userid" value="<?php echo $val->data->ID; ?>" />
			<?php echo $val->data->display_name; ?></td><td>
			<input type="submit" name="select" value="Select" class="button" />
			</form>
			</td></tr>
<?php 	
		endif; 
	endforeach; ?>
</table>

<?php } ?>



<?php
/* Php functions */

function jsimport()
{
	$jsurl = plugins_url('page-edit/js/script.js');
	echo "<script src=".$jsurl."></script>";
}
if (!function_exists('cssimport'))
{
	function cssimport()
	{
		$cssurl = plugins_url('page-edit/css/pagestyle.css');
		echo  "<link href='".$cssurl."' type='text/css' rel='stylesheet' />";
	}
}
?>