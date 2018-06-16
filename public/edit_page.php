<?php require_once("../includes/session.php") ?>
<?php require_once("../includes/db_connection.php");?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation_functions.php");?>
<?php confirm_logged_in(); ?>
<?php find_selected_page(); ?>

<?php
	if(!$current_page) {
	// redirects if no page id was passed through the query string or the subject couldn't be found in database	
		redirect_to("manage_content.php");
	}
?>

<?php
	if(isset($_POST['submit'])) {
		
		// Validations
		$required_fields = ["menu_name", "position", "visible", "content"];
		validate_presences($required_fields);
		$fields_with_max_lengths = ["menu_name" => 30];
		validate_max_lengths($fields_with_max_lengths);
		
		if(empty($errors)) {
						
			$id = $current_page["id"];
			$menu_name = escape_string($_POST["menu_name"]);
			$position = (int) $_POST["position"];
			$visible = isset($_POST["visible"]) ? (int) $_POST["visible"] : "";
			$content = escape_string($_POST["content"]);
		//	$visible = (int) $_POST["visible"];
		
			$query = "UPDATE pages SET ";
			$query .= "menu_name = '{$menu_name}', ";
			$query .= "position = '{$position}', ";
			$query .= "visible = '{$visible}', ";
			$query .= "content = '{$content}' ";
			$query .= "WHERE id = '{$id}' ";
			$query .= "LIMIT 1";
			$result = mysqli_query($db, $query);
			
			if($result && mysqli_affected_rows($db) >= 0){
				$_SESSION["message"] = "Page successfully updated.";
				redirect_to("manage_content.php?page={$id}");
			} else {
				$message = "Page failed to update.  Database query failed.";
			}
		}	
	}
?>

<?php $layout_context = "admin"; ?>
<?php include("../includes/layouts/header.php"); ?>

<div id="main">
	<div id="navigation">
		<br>
		<a href="admin.php">&laquo; Main menu</a><br>
		<?php echo navigation($current_subject, $current_page); ?>
	</div>
	<div id="page">
		<?php
		if(!empty($message)) {
			echo "<div class = \"message\">";
			echo htmlentities($message);
			echo "</div>";
		}	
		?>
		<?php
			echo form_errors($errors);
		?>
		<h2>Edit Page: <?php echo htmlentities($current_page["menu_name"]); ?></h2>
		
		<form action="edit_page.php?page=<?php echo urlencode($current_page["id"]); ?>" method="post">
			<p>
				<label for="menu_name">Menu name: </label>
				<input id="menu_name" type="text" name="menu_name" value="<?php echo htmlentities($current_page["menu_name"]); ?>">
			</p>
			<p>
				<label for="position">Position: </label>
				<select id="position" name="position">
				<?php
					$subject_id = $current_page["subject_id"];
					$page_set = find_pages_for_subject($subject_id, false);
					$page_count = mysqli_num_rows($page_set);
					for($i=1; $i <= $page_count; $i++) {
						echo "<option value=\"{$i}\"";
						if($current_page["position"] == $i) {
							echo " selected";
						}
						echo ">{$i}</option>";
					}
				?>
				</select>
			</p>
			<p>
				<label for="visible">Visible: </label>
				<input id="visible" type="radio" name="visible" value="0" <?php if($current_page["visible"] == 0) {echo "checked";} ?>> No
				&nbsp;
				<input id="visible" type="radio" name="visible" value="1"<?php if($current_page["visible"] == 1) {echo "checked";} ?>> Yes
			</p>
			<p>
				<label for="content">Content: </label><br>
				<textarea id="content" name="content" value=""><?php echo htmlentities($current_page["content"]); ?></textarea>
			</p>
			<input type="submit" name="submit" value="Edit Page">
		</form>
		<br>
		<a href="manage_content.php?page=<?php echo urlencode($current_page["id"]); ?>">Cancel</a>
		&nbsp;
		&nbsp;
		<a href="delete_page.php?page=<?php echo urlencode($current_page["id"]);?>" onclick="return confirm('Are you sure you want to delete this page?');">Delete Page</a>

	</div>
</div>

<?php include("../includes/layouts/footer.php"); ?>