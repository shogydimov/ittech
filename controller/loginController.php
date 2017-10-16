<?php

// TODO Georgi test this code

session_start();

use \model\DataBase\UserDao;
use \model\users\User;

function __autoload($class_name)
{
	$class_name = '..\\' . $class_name;
	$class_name = str_replace("\\", "/", $class_name);
	require_once $class_name . '.php';
}

if (isset($_POST['login'])) {
	$email = trim(htmlentities($_POST['email']));
	$password = sha1(trim(htmlentities($_POST['password'])));
	
	if (UserDao ::getInstance() -> loginValidate($email, $password)) {
		$user = UserDao ::getInstance() -> loginValidate($email, $password);
		$_SESSION['isLogged'] = true;
		$_SESSION['user_id'] = $user['user_id'];
		$_SESSION['name'] = $user['name'];
		$_SESSION['familyName'] = $user['family_name'];
		$_SESSION['email'] = $user['email'];
		$_SESSION['password'] = $user['password'];
		$_SESSION['gender'] = $user['gender'];
		$_SESSION['birthday'] = $user['birthday'];
		$_SESSION['notifications'] = $user['notifications'];
		$_SESSION['is_admin'] = $user['is_admin'];
		header('Location:../');
	}
	else{
		$_SESSION["invalidUser"] = true;
		header("Location:../");
	}
}

if (isset($_GET['loginValidation'])) {
	if(isset($_SESSION["invalidUser"]) && $_SESSION["invalidUser"]){
		echo 'dont';
		//header("Location:../");
		session_destroy();
	}
	else{
		echo "ebago!";
	}
}





