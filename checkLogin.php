<?php

require "/classes/Database.php";
session_start();

$dbinfo = array(
    "host" => "127.0.0.1",
    "user" => "root",
    "pass" => "",
    "name" => "hiskor"
);

// Creates the PDO Object for queries
$db = new Database ( $dbinfo );
$db->jsonError = true;

if ($_SERVER['REQUEST_METHOD'] == "POST")
{
    $username = $_POST['username'];
    $password = $_POST['password'];
    $salt = 'FSF^D&*FH#RJNF@!$JH#@$';

    $db->query("SELECT * FROM `users` WHERE `username`=?")->bind(1, $username)->execute();

    if ($db->getTotalRows()) {
        $result = $db->fetch();
        $resultpassword = $result['password'];
    }

    if (md5($password . $salt) == $resultpassword) {
        $recentLogin = time();

        $_SESSION['username'] = $username;
        $_SESSION['loggedIn'] = 1;
        $_SESSION['recentLogin'] = $recentLogin;

        // Sets the login time
        $db->query("UPDATE `users` SET `recentLogin` = ? WHERE `username`=?")->bind(1, $recentLogin)->bind(2, $username)->execute();

        header("Location: dashboard.php");
        exit;
    } else {
        print 'Error logging in';
    }
}