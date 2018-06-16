		<div id="footer">Copyright <?php echo date("Y"); ?>, Widget Corp</div>
	</div>
	</body>
</html>
<?php
// Close database connection if one is set

	if(isset($db)) {
		mysqli_close($db);
	}
?>