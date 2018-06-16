<?php require_once("../includes/session.php") ?>
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
		<br>
		<a href="new_subject.php">+ Add a subject</a>
	</div>
	<div id="page">
		<?php echo display_message(); ?>
		<?php if($current_subject) { ?>
			<h2>Manage Subject</h2>
			Menu name: <?php echo htmlentities($current_subject["menu_name"]); ?><br>
			Position: <?php echo $current_subject["position"]; ?><br>
			Visible: <?php echo $current_subject["visible"] == 1 ? 'yes' : 'no'; ?><br>
			<br>
			<a href="edit_subject.php?subject=<?php echo urlencode($current_subject["id"]); ?>">Edit Subject</a>
			<div class="topborder" >
				<h3>Pages in this subject: </h3>
				<?php
					$list_pages = list_pages($current_subject["id"]);
					echo $list_pages;
					if($list_pages == "<ul></ul>") {
						echo "<i>-- No pages exist for this subject --</i><br><br>";
					}
				?>
				<a href="new_page.php?subject=<?php echo urlencode($current_subject["id"]); ?>">+ Add a new page to this subject</a>
			</div>
		<?php } elseif($current_page) { ?>
			<h2>Manage Page</h2>
			Menu name: <?php echo htmlentities($current_page["menu_name"]); ?><br>
			Position: <?php echo $current_page["position"]; ?><br>
			Visible: <?php echo $current_page["visible"] == 1 ? 'yes' : 'no'; ?><br>
			Subject ID: <?php echo $current_page["subject_id"]; ?><br>
			Content: <br>
			<div class="view_content">
			<?php echo htmlentities($current_page["content"]); ?>
			</div>
			<br>
			<a href="edit_page.php?page=<?php echo urlencode($current_page["id"]); ?>">Edit Page</a>	
			
		<?php } else { ?>
			<h2>Manage Content</h2>
			Please select a subject or page to view/edit.
		<?php } ?>
	</div>
</div>

<?php include("../includes/layouts/footer.php"); ?>