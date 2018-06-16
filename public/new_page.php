<?php require_once("../includes/session.php") ?>
<?php require_once("../includes/db_connection.php");?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation_functions.php");?>
<?php confirm_logged_in(); ?>
<?php find_selected_page(); ?>

<?php
	if(!$current_subject) {
	// redirects if no subject id was passed through the query string or the subject couldn't be found in database	
		redirect_to("manage_content.php");
	}
?>

<?php
	if(isset($_POST['submit'])) {
		
		$id = $current_subject["id"];
		$menu_name = escape_string($_POST["menu_name"]);
		$position = (int) $_POST["position"];
		$visible = isset($_POST["visible"]) ? (int) $_POST["visible"] : "";
		$subject_id = $current_subject["id"];
		$content = escape_string($_POST["content"]);
	//	$visible = (int) $_POST["visible"];
		
		// Validations
		$required_fields = ["menu_name", "position", "visible", "content"];
		validate_presences($required_fields);
		$fields_with_max_lengths = ["menu_name" => 30];
		validate_max_lengths($fields_with_max_lengths);
		
		if(empty($errors)) { 
								
			$query = "INSERT INTO pages (";
			$query .= "menu_name, position, visible, content, subject_id";
			$query .= ") VALUES (";
			$query .= " '{$menu_name}', {$position}, {$visible}, '{$content}', {$subject_id}";
			$query .= ")";
			$result = mysqli_query($db, $query);
			
			if($result) {
				$_SESSION["message"] = "Page successfully created.";
				redirect_to("manage_content.php");
			} else {
				$message = "Page could not be created.  Database query failed.";
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
		<?php echo form_errors($errors); ?>
		<h2>Create Page for: <?php echo $current_subject["menu_name"]; ?></h2>
		
		<form action="new_page.php?subject=<?php echo urlencode($current_subject["id"]); ?>" method="post">
			<p>
				<label for="menu_name">Menu name: </label>
				<input id="menu_name" type="text" name="menu_name" value="">
			</p>
			<p>
				<label for="position">Position: </label>
				<select id="position" name="position">
				<?php
					$subject_id = $current_subject["id"];
					$page_set = find_pages_for_subject($subject_id);
					$page_count = mysqli_num_rows($page_set);
					for($i=1; $i <= ($page_count +1); $i++) {
						echo "<option value=\"{$i}\">{$i}</option>";
					}
				?>
				</select>
			</p>
			<p>
				<label for="visible">Visible: </label>
				<input id="visible" type="radio" name="visible" value="0" <?php // if(isset($visible)) { if($current_subject["visible"] == 0) {echo "checked";}} ?>> No
				&nbsp;
				<input id="visible" type="radio" name="visible" value="1" <?php // if(isset($visible)) { if($current_subject["visible"] == 1) {echo "checked";}} ?>> Yes
			</p>
			<p>
				<label for="content">Content: </label><br>
				<textarea id="content" name="content" value=""></textarea>
			</p>
			<input type="submit" name="submit" value="Create Page">
		</form>
		<br>
		<a href="manage_content.php?subject=<?php echo urlencode($current_subject["id"]); ?>">Cancel</a>
	</div>
</div>

<?php include("../includes/layouts/footer.php"); ?>
