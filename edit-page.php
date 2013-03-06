<?php 
global $wpdb;
jsimport();
cssimport();
if(isset($_POST['pageid']) && $_POST['pageid']!=null)
{
	$page_id = $_POST['pageid'];
	$current_user_id = get_current_user_id();
	$data = $wpdb->get_row("SELECT * FROM page_permissions WHERE user_id='".$current_user_id."'",ARRAY_A);
	$datavalue = explode(",",$data['page_id']);
	if(in_array($page_id,$datavalue))
	{
		$page_contents = $wpdb->get_row("SELECT * from $wpdb->posts WHERE ID=".$page_id,ARRAY_A);
		the_editor($page_contents["post_content"]);
		?>
		<form name="editcontentform" method="POST" action="" id="editcontentform">
		<input type="hidden" name="pagecntnt" id="pagecntnt" />
		<input type="hidden" name="pageidval" id="pageidval" value="<?php echo $page_id ?>" /><br />
		<input type="submit" name="submit" value="Update" onclick="return editformsubmit()" class="button" />
		</form>
		<?php
	}
	else 
	{
		wp_die("The permission has been changed by admin.");
	}
}
elseif(isset($_POST['pagecntnt']) && $_POST['pagecntnt'] != null && $_POST['pagecntnt'] != "")
{
	if($_POST['pageidval'] != "" && $_POST['pageidval'] != null)
	{
		if($wpdb->query("UPDATE $wpdb->posts SET post_content='".$_POST['pagecntnt']."' WHERE ID=".$_POST['pageidval']))
		{
			$page_contents = $wpdb->get_row("SELECT * from $wpdb->posts WHERE ID=".$_POST['pageidval'],ARRAY_A);
			echo '<div id="success">Updated &nbsp;<a href="'.$page_contents["guid"].'" class="button" target="_blank">View</a></div>';
			the_editor($page_contents["post_content"]);
			?>
			<form name="editcontentform" method="POST" action="" id="editcontentform">
			<input type="hidden" name="pagecntnt" id="pagecntnt" />
			<input type="hidden" name="pageidval" id="pageidval" value="<?php echo $_POST['pageidval'] ?>" /><br />
			<input type="submit" name="submit" value="Update" onclick="return editformsubmit()" class="button" />
			</form>
			<?php
		}
		else
		{
			echo '<div id="information">No changes to update. Please make some changes and update.</div>';
			$page_contents = $wpdb->get_row("SELECT * from $wpdb->posts WHERE ID=".$_POST['pageidval'],ARRAY_A);
			the_editor($page_contents["post_content"]);
			?>
			<form name="editcontentform" method="POST" action="" id="editcontentform">
			<input type="hidden" name="pagecntnt" id="pagecntnt" />
			<input type="hidden" name="pageidval" id="pageidval" value="<?php echo $_POST['pageidval'] ?>" />
			<input type="submit" name="submit" value="Update" onclick="return editformsubmit()" class="button" />
			</form>
			<?php
		}
	}
}
else
{
if(current_user_can("administrator"))
	echo "Welcome Admin, Please go to Pages->All Pages"; 
else
{
	$current_user_id = get_current_user_id();
	$data = $wpdb->get_row("SELECT * FROM page_permissions WHERE user_id='".$current_user_id."'",ARRAY_A);
	$datacount = count($data);
	if($datacount != 0)
	{
		if($data['page_id'] != 0)
		{
			$userdata = explode(",",$data['page_id']);
			echo '<div id="infoedit">You are allowed to edit the listed pages. </div><br />';
			echo "<table>";
			$pages = get_pages();
			$incrementval = 1;
			foreach($pages as $value) : 
				if(in_array($value->ID,$userdata)): ?>
				<tr><td><?php echo $incrementval."."; $incrementval++; ?></td><td>
				<form name="imp" action="" method="POST">
				<input type="hidden" name="pageid" value="<?php echo $value->ID; ?>" /><?php echo $value->post_title;?></td><td>
				<input type="submit" name="Select" value="Select" class="button" />&nbsp;<a href="<?php echo $value->guid; ?>" class="button" target="_blank">View</a>
				</form>
				</td></tr>
			<?php endif; endforeach;
		}
		else
			echo "You have no pages to edit";
	}
	else
		echo "You have no pages to edit";
}
}
?>

<?php
/* Php functions to import js and css */

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