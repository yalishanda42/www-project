<?php

require_once "classes/util/View.class.php";
require_once "classes/util/Database.class.php";
require_once "classes/models/User.class.php";

session_start();

// Establish database connection

$database = new Database();
$db = $database->getNewConnection();

if (!$db) {
    $view = new View("database_error");
    $view->send();
}

$msg = '';

if (isset($_POST["submitbutton"]))
{
	$name=$_POST["uname"];	
	$password=$_POST["psw"];
	$user = new User($db);
	$user->username = $name;
	$is_logged_in = $user->usernameExists() && password_verify($password, $user->password);
	$msg = "";
	if($is_logged_in) {
		$_SESSION["user_id"] = $user->id;
		header("Location: index.php");
	}
	else {
		$params["msg"] = "Incorrect Username or Password";
		$view = new View("login");
		$view->send($params);       	
	}
}
else {
	if (isset($_POST["signup"]))
		{
			header("Location: signup.php");
		}
}

$view = new View("login");
$view->send();
