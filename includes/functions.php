<?php
	
// Redirects to the $new_location argument
	function redirect_to($new_location) {
		header("Location: " . $new_location);
		exit;
	}
	
// Preps strings taken from users (GET, POST, COOKIES) to prevent possible SQL Injection
	function escape_string($string) {
		global $db;
		
		$escaped_string = mysqli_real_escape_string($db, $string);
		return $escaped_string;
	}	

// Tests if query was successful (pass through the results/set of the mysqli_query)
	function confirm_query($result_set) {
		if(!$result_set) {
			die("Database query failed.");
		}
	}
		
//This is a function that constructs a div that contains potential errors
	function form_errors($errors_array=[]) {
		$output = "";
		if(!empty($errors_array)) {
			$output .= "<div class=\"errors\">";
			$output .= "Please fix the following errors:";
			$output .= "<ul>";
			foreach ($errors_array as $error_key => $error_message) {
				$output .= "<li>";
				$output .= /*htmlentities(*/$error_message/*)*/;
				$output .="</li>";
			}
			$output .= "</ul>";
			$output .= "</div>";
		}
		return $output;
	}

// Finds/returns all 'visible' data if argument is TRUE from the subjects table and stores it in the $subject_set array
// If FALSE, will return both visible and invisible subjects
	function find_all_subjects($public = true) {
		global $db;

		$query = "SELECT * ";
		$query .= "FROM subjects ";
		if($public) {
		$query .= "WHERE visible = 1 ";
		}
		$query .= "ORDER BY position ASC";

		$subject_set = mysqli_query($db, $query);
		confirm_query($subject_set);

		return $subject_set;
	}

// Finds/returns all 'visible' data if argument is TRUE from the pages table for the given subject id and stores it in the $page_set array;  If FALSE, will return all pages for the subject, whether visible or not
// Pass through the subject id whose pages your are trying to find
	function find_pages_for_subject($subject_id, $public = true) {
		global $db;
		
		$safe_subject_id = mysqli_real_escape_string($db, $subject_id);

		$query = "SELECT * ";
		$query .= "FROM pages ";
	//	$query .= "WHERE visible = 1 ";
	//	$query .= "AND subject_id = {$safe_subject_id} ";
		$query .= "WHERE subject_id = {$safe_subject_id} ";
		if($public) {
			$query .= "AND visible = 1 ";
		}
		$query .= "ORDER BY position ASC";

		$page_set = mysqli_query($db, $query);
		confirm_query($page_set);

		return $page_set;
	}
	
// Finds/returns the subject of a specific id (which is passed through the argument via the query string)	
	function find_subject_by_id($selected_subject_id, $public = true) {
		global $db;
		// b/c we are receiving the subject id from the query string, we need to make sure it is safe to use.
		$safe_subject_id = mysqli_real_escape_string($db, $selected_subject_id);
		
		$query = "SELECT * ";
		$query .= "FROM subjects ";
	//	$query .= "WHERE visible = 1 ";
		$query .= "WHERE id = {$safe_subject_id} ";
		if($public) {
			$query .= "AND visible = 1 ";
		}
		$query .= "LIMIT 1";
		
		$subject_set = mysqli_query($db, $query);
		confirm_query($subject_set);
		
		// Since we are returning only one specific row, we can perform the mysqli_fetch on the function side
		// and just return the associative array itself within $subject
		if($subject = mysqli_fetch_assoc($subject_set)) {
			return $subject;
		} else {
			return null;
		}
	}
	
// Finds/returns the page of a specefic id 
	function find_page_by_id($selected_page_id, $public = true) {
		global $db;
		// b/c we are receiving the subject id from the query string, we need to make sure it is safe to use.
		$safe_page_id = mysqli_real_escape_string($db, $selected_page_id);
		
		$query = "SELECT * ";
		$query .= "FROM pages ";
	//	$query .= "WHERE visible = 1 ";
		$query .= "WHERE id = {$safe_page_id} ";
		if($public) {
			$query .= "AND visible = 1 ";
		}
		$query .= "LIMIT 1";
		
		$page_set = mysqli_query($db, $query);
		confirm_query($page_set);
		
		// Since we are returning only one specific row, we can perform the mysqli_fetch on the function side
		// and just return the associative array itself within $page
		if($page = mysqli_fetch_assoc($page_set)) {
			return $page;
		} else {
			return null;
		}
	}
	
// Returns the first page of a given subject id
	function find_default_page_for_subject($subject_id) {
		$page_set = find_pages_for_subject($subject_id, true);
		if($first_page = mysqli_fetch_assoc($page_set)) {
			return $first_page;
		} else {
			return null;
		}
	}
	
// $_GETs the subject/page id within the query string and uses that to set the current subject/page variables (made global)
	function find_selected_page($public = false) {
		global $current_subject;
		global $current_page;
		
		if(isset($_GET["subject"])) {
			$current_subject = find_subject_by_id($_GET["subject"], $public);
			if($current_subject && $public) {
				$current_page = find_default_page_for_subject($current_subject["id"]);
			} else {
				$current_page = null;
			}
		} elseif (isset($_GET["page"])) {
			$current_page = find_page_by_id($_GET["page"], $public);
			$current_subject = null;
		} else {
			$current_subject = null;
			$current_page = null;
		}
	}	
	
//	Returns query results for all admins
	function find_all_admins() {
		global $db;
		
		$query = "SELECT * ";
		$query .= "FROM admins ";
		$query .= "ORDER BY username ASC";
		$admins_set = mysqli_query($db, $query);
		confirm_query($db);
		return $admins_set;
	}
	
//	Queries db for admin given admin id (probably from $_GET) and returns an assoc array with admin data for that id
	function find_admin_by_id($admin_id) {
		global $db;
		
		$safe_admin_id = mysqli_real_escape_string($db, $admin_id);
		$query = "SELECT * FROM admins ";
		$query .= "WHERE id = {$safe_admin_id} ";
		$query .= "LIMIT 1";
		
		$id_set = mysqli_query($db, $query);
		confirm_query($id_set);
		
		if($id = mysqli_fetch_assoc($id_set)) {
			return $id;
		} else {
			return null;
		}
	}
	
//	Queries db for admin given admin username (probably from $_GET) and returns an assoc array with admin data for that username
	function find_admin_by_username($admin_username) {
		global $db;
		
		$safe_admin_username = mysqli_real_escape_string($db, $admin_username);
		$query = "SELECT * FROM admins ";
		$query .= "WHERE username = '{$safe_admin_username}' ";
		$query .= "LIMIT 1";
		
		$admin_set = mysqli_query($db, $query);
		confirm_query($admin_set);
		
		if($admin = mysqli_fetch_assoc($admin_set)) {
			return $admin;
		} else {
			return null;
		}
	}	

// Navigation takes 2 arguments:
	// - the current subject array or null
	// - the current page array or null
	function navigation($current_subject, $current_page) {
		$output = "<ul class=\"subjects\">";
		$subject_set = find_all_subjects(false);
			// Using returned data (from db query) to create a list of menu_names from the subjects table
			// (setting it within the new $subject array)

		while($subject = mysqli_fetch_assoc($subject_set)) {
			$output .= "<li";
			if ($current_subject && $subject["id"] == $current_subject["id"]) {
				$output .= " class=\"selected\"";
			}
			$output .= ">";
			$output .= "<a href=\"manage_content.php?subject=";
			$output .= urlencode($subject["id"]); 
			$output .= "\">";
			$output .= htmlentities($subject["menu_name"]);
			$output .= "</a>";
			
			$page_set = find_pages_for_subject($subject["id"], false);
			$output .= "<ul class=\"pages\">";
			while($page = mysqli_fetch_assoc($page_set)) {
				$output .= "<li";
				if ($current_page && $page["id"] == $current_page["id"]) {
					$output .= " class=\"selected\"";
				}
				$output .= ">";
				$output .= "<a href=\"manage_content.php?page=";
				$output .= urlencode($page["id"]);
				$output .= "\">";
				$output .= htmlentities($page["menu_name"]);
				$output .= "</a>";
				$output .= "</li>"; 
			}
			mysqli_free_result($page_set);
			$output .= "</ul>";
			$output .= "</li>";
		}
		mysqli_free_result($subject_set);
		$output .= "</ul>";
		return $output;
	}
	
// Public Navigation takes 2 arguments:
// - the current subject array or null
// - the current page array or null
	function public_navigation($current_subject, $current_page) {
		$output = "<ul class=\"subjects\">";
		$subject_set = find_all_subjects(true);
			// Using returned data (from db query) to create a list of menu_names from the subjects table
			// (setting it within the new $subject array)

		while($subject = mysqli_fetch_assoc($subject_set)) {
			$output .= "<li";
			if ($current_subject && $subject["id"] == $current_subject["id"]) {
				$output .= " class=\"selected\"";
			}
			$output .= ">";
			$output .= "<a href=\"index.php?subject=";
			$output .= urlencode($subject["id"]); 
			$output .= "\">";
			$output .= htmlentities($subject["menu_name"]);
			$output .= "</a>";
			
			if($current_subject["id"] == $subject["id"] || $current_page["subject_id"] == $subject["id"]) {
				$page_set = find_pages_for_subject($subject["id"]);
				$output .= "<ul class=\"pages\">";
				while($page = mysqli_fetch_assoc($page_set)) {
					$output .= "<li";
					if ($current_page && $page["id"] == $current_page["id"]) {
						$output .= " class=\"selected\"";
					}
					$output .= ">";
					$output .= "<a href=\"index.php?page=";
					$output .= urlencode($page["id"]);
					$output .= "\">";
					$output .= htmlentities($page["menu_name"]);
					$output .= "</a>";
					$output .= "</li>"; 
				}
				$output .= "</ul>";
				mysqli_free_result($page_set);
			}
			$output .= "</li>";
		}
		mysqli_free_result($subject_set);
		$output .= "</ul>";
		return $output;
	}
	
// Creates an unordered list with all pages for a given subject id
	function list_pages($subject_id) {
		$page_set = find_pages_for_subject($subject_id);
			$output = "<ul>";
			while($page = mysqli_fetch_assoc($page_set)) {
				$output .= "<li>";
				$output .= "<a href=\"manage_content.php?page=";
				$output .= urlencode($page["id"]);
				$output .= "\">";
				$output .= htmlentities($page["menu_name"]);
				$output .= "</a>";
				$output .= "</li>";
			}
			$output .= "</ul>"; 
			return $output;
			
			mysqli_free_result($page_set);
	}
	
// Generates a near random and nearly unique encrypted salt string to use in password encryption
	function generate_salt($length) {
		// Not 100% unique, not 100% random, but good enough for a salt
		// MD5 returns 32 characters (more than the 22 we will need for our Blowfish hashing in our password encryption
		$unique_random_string = md5(uniqid(mt_rand(), true));
		
		// Valid characters for a salt are [a-zA-Z0-9./]
		// Base64 will transform our string into these characters, but also '+'s
		$base64_string = base64_encode($unique_random_string);
		
		// But not '+' which is valid in base 64 encoding, so to change those:
		$modified_base64_string = str_replace('+', '.', $base64_string);
		
		// Truncate string to the correct length
		$salt = substr($modified_base64_string, 0, $length);
		
		return $salt;
	}	
	
// Encrypts passwords	
	function password_encrypt($password) {
		$hash_format = "$2y$10$";	// Tells PHP to use Blowfish with a "cost" of 10
		$salt_length = 22;			// Blowfish salts should be 22 characters or more
		$salt = generate_salt($salt_length);
		$format_and_salt = $hash_format . $salt;
		$hash = crypt($password, $format_and_salt);
		return $hash;
	}
	
// Takes the submitted password from the login form and the existing password hash and checks to see if they match; returns T/F
	function password_check($submitted_password, $existing_hash) {
		$hash = crypt($submitted_password, $existing_hash);
		if ($hash === $existing_hash) {
			return true;
		} else {
			return false;
		}
	}	
	
// Encrypts password and compares the encrypted password to the existing hash within the database for the given username
	// Step 1) Checks that the username exists within the database
		// Login fails if no match is found
	// Step 2) Checks that the existing password matches the submitted password (using the password_check() function)
		// Login fails if no  match is found
	function attempt_login($username, $password) {
		$admin = find_admin_by_username($username);
		if ($admin) {
		// Found admin, now check password:	
			if (password_check($password, $admin["hashed_password"])) {
			// Password Matched, returns the $admin array to use after this function is called
				return $admin;
			} else {
			// Password was not matched:	
				return false;
			}
		} else {
		// Admin not found:	
			return false;
		}
	}	
	
// Returns T/F if a user is logged in by checking for the admin_id in which is set in the SESSION if the user succesfully logged in
// This function is useful for writing conditionals based on whether or not the user is logged in
	function logged_in() {
		return isset($_SESSION["admin_id"]);
	}

// Confirms that an admin is logged in and redirects to login page if not
// Used on pages that are admin-only
	function confirm_logged_in() {
		if (!logged_in()) {
			redirect_to("login.php");
		}
	}
	
?>