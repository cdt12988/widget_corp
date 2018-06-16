<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php");?>
<?php require_once("../includes/functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php find_selected_page(); ?>

<?php $layout_context = "admin"; ?>
<?php include("../includes/layouts/header.php"); ?>
<div id="main">
	<div id="navigation">
		<br>
		<a href="admin.php">&laquo; Main menu</a><br>
		<?php echo navigation($current_subject, $current_page); ?>
	</div>
	<div id="page">
		<?php echo display_message(); ?>
		<?php
			$errors = display_errors();
			echo form_errors($errors);
		?>
		<h2>Create Subject</h2>
		
		<form action="create_subject.php" method="post">
			<p>
				<label for="menu_name">Menu name: </label>
				<input id="menu_name" type="text" name="menu_name" value="">
			</p>
			<p>
				<label for="position">Position: </label>
				<select id="position" name="position">
				<?php
					$subject_set = find_all_subjects(false);
					$subject_count = mysqli_num_rows($subject_set);
					for($i=1; $i <= ($subject_count +1); $i++) {
						echo "<option value=\"{$i}\">{$i}</option>";
					}
				?>
				</select>
			</p>
			<p>
				<label for="visible">Visible: </label>
				<input id="visible" type="radio" name="visible" value="0"> No
				&nbsp;
				<input id="visible" type="radio" name="visible" value="1"> Yes
			</p>
			<input type="submit" name="submit" value="Create Subject">
		</form>
		<br>
		<a href="manage_content.php">Cancel</a>
	</div>
</div>

<?php include("../includes/layouts/footer.php"); ?>