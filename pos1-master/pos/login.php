<?php
//Start session
session_start();

//Array to store validation errors
$errmsg_arr = array();

//Validation error flag
$errflag = false;

//Connect to mysql server
$conn = mysqli_connect('localhost', 'root', "", 'sales');
if (!$conn) {
	die('Failed to connect to server: ' . mysqli_error());
}


//Function to sanitize values received from the form. Prevents SQL injection
function clean($str)
{
	$str = @trim($str);
	if (get_magic_quotes_gpc()) {
		$str = stripslashes($str);
	}
	return mysqli_real_escape_string($str);
}

//Sanitize the POST values
$login = ($_POST['username']);
$password = ($_POST['password']);

//Input Validations
if ($login == '') {
	$errmsg_arr[] = 'Username missing';
	$errflag = true;
}
if ($password == '') {
	$errmsg_arr[] = 'Password missing';
	$errflag = true;
}

//If there are input validations, redirect back to the login form
if ($errflag) {
	$_SESSION['ERRMSG_ARR'] = $errmsg_arr;
	session_write_close();
	header("location: index.php");
	exit();
}

//Create query
$qry = "SELECT * FROM user WHERE username='$login' AND password='$password'";
$result = mysqli_query($conn, $qry);

//Check whether the query was successful or not
if ($result) {
	if (mysqli_num_rows($result) > 0) {
		//Login Successful
		session_regenerate_id();
		$member = mysqli_fetch_assoc($result);
		$_SESSION['SESS_MEMBER_ID'] = $member['id'];
		$_SESSION['SESS_FIRST_NAME'] = $member['name'];
		$_SESSION['SESS_LAST_NAME'] = $member['position'];
		$_SESSION['SESS_USER_ROLE'] = $member['position'];
		//$_SESSION['SESS_PRO_PIC'] = $member['profImage'];
		session_write_close();
		// Redirect based on user role
		if ($member['position'] === 'cashier') {
			// Generate random invoice code
			$invoice = rand(100000, 999999);
			header("location: main/sales.php?id=cash&invoice=$invoice");
		} else {
			// Redirect suppliers to supplier portal
			if ($member['position'] === 'supplier') {
				header("location: main/supplier_portal.php");
			} else {
				header("location: main/index.php");
			}
		}
		exit();
	} else {
		//Login failed
		header("location: index.php");
		exit();
	}
} else {
	die("Query failed");
}
