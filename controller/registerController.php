<?php


use \model\DataBase\UserDao;
use \model\users\User;

function __autoload($class_name)
{
    $class_name = '..\\' . $class_name;
    $class_name = str_replace("\\", "/", $class_name);
    require_once $class_name . '.php';
}

session_start();
$error = false;

if (isset($_POST['register'])) {
    $name = trim(htmlentities($_POST['name']));
    $familyName = trim(htmlentities($_POST['familyName']));
    $email = trim(htmlentities($_POST['email']));
    $atSignPos = strpos($email, '@');
    $password = trim(htmlentities($_POST['password']));
    $confirmPassword = trim(htmlentities($_POST['confirmPassword']));
    $gender = trim(htmlentities($_POST['gender']));

    if (isset($_POST['day']) && isset($_POST['month']) && isset($_POST['year'])) {
        $day = trim(htmlentities($_POST['day']));
        $month = trim(htmlentities($_POST['month']));
        $year = trim(htmlentities($_POST['year']));
        $birthday = $year . '-' . $month . '-' . $day;
    } else {
        $error = true;
    }

    if (isset($_POST['notification'])) {
        $notification = 1;
    } else {
        $notification = null;
    }

    if (!isset($_POST['terms'])) {
        $error = true;
    }


    if (strlen($name) == 0 || strlen($name) > 45 || is_numeric($name)) {
        $error = true;
    }
    if (strlen($familyName) == 0 || strlen($familyName) > 45 || is_numeric($familyName)) {
        $error = true;
    }
    if (strlen($email) == 0 || strlen($email) > 45 || $atSignPos < 1) {
        $error = true;
    }
    if (strlen($password) == 0 || strlen($password) > 45 || $password !== $confirmPassword) {
        $error = true;
    } else {
        $password = sha1($password);
    }
    if (strlen($gender) == 0 || strlen($gender) > 10) {
        $error = true;
    }

    if (!$error) {
        $user = new User($name, $familyName, $email, $password, $gender, $birthday, $notification);
        try {
            if (!UserDao::getInstance()->existsUser($email)) {
                UserDao::getInstance()->insertUser($user);
                $_SESSION['isLogged'] = true;
                $_SESSION['user_id'] = $user->getUserId();
                $_SESSION['user'] = $user;
                header('Location:../');
            } else {
                header('Location:../?page=error401');
            }
        } catch (\PDOException $e) {
            header('Location:../?page=error500');
        }

    } else {
        header('Location:../?page=error401');
    }

}

if (isset($_GET['existsUser'])) {
    try {
        echo is_array(UserDao::getInstance()->existsUser($_GET['existsUser']));
    } catch (\PDOException $e) {
        http_response_code(500);
    }
}






