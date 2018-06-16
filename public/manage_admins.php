<?php require_once("../includes/session.php") ?>
<?php require_once("../includes/db_connection.php");?>
<?php require_once("../includes/functions.php"); ?>
<?php confirm_logged_in(); ?>

<?php $layout_context = "admin"; ?>
<?php include("../includes/layouts/header.php"); ?>
<div id="main">
	<div id="navigation">
		<br>
		<a href="admin.php">&laquo; Main menu</a><br>
	</div>
		<div id="page">
			<?php echo display_message(); ?>
			<h2>Manage Admins</h2>
			<table>
				<tr>
					<th style="text-align: left; width: 200px;">Username</th>
					<th colspan="2" style="text-align: left;">Actions</th>
				</tr>
			<?php
				$admin_set = find_all_admins();
				while($admin = mysqli_fetch_assoc($admin_set)) {
			?>
				<tr>
					<td><?php echo htmlentities($admin["username"]); ?></td>
					<td><a href="edit_admin.php?id=<?php echo urlencode($admin["id"]); ?>">Edit</a></td>
					<td><a href="delete_admin.php?id=<?php echo urlencode($admin["id"]); ?>" onclick="return confirm('Are you sure you want to delete this admin?');">Delete</a></td>
				</tr>
			<?php } ?>
			</table>
			
		<!--	<div class="float_left">
				<b>Username</b><br>
				<?php
					$admin_set = find_all_admins();
					while($admin = mysqli_fetch_assoc($admin_set)) {
						echo htmlentities($admin["username"]);
						echo "<br>";
					}
				?>
			</div>
			<div>
				<b>Actions</b><br>
				<?php
					$admin_set = find_all_admins();
					while($id = mysqli_fetch_assoc($admin_set)) {
						echo "<a href=\"edit_admin.php?id=";
						echo urlencode($id["id"]);
						echo "\">Edit</a> ";
						echo "<a href=\"delete_admin.php?id=";
						echo urlencode($id["id"]);
						echo "\" onclick=\"return confirm('Are you sure you want to delete this admin?');\">Delete</a><br>";
					}
				?>
			</div> -->
			<br>
			<a href="new_admin.php<?php // echo urlencode($current_user["id"]); ?>">+ Add new admin</a>		
	</div>
</div>
<?php include("../includes/layouts/footer.php"); ?>