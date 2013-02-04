<?php

require "/classes/Database.php";

$dbinfo = array(
    "host" => "127.0.0.1",
    "user" => "root",
    "pass" => "",
    "name" => "hiskor"
);

// Creates the PDO Object for queries
$db = new Database ( $dbinfo );
$db->jsonError = true;

$username = $_POST['username'];
$password = $_POST['password'];
$salt = "FSF^D&*FH#RJNF@!$JH#@$";

$db->query("SELECT `password` FROM `users` WHERE `username`=?")->bind(1, $username)->execute();
if ($db->getTotalRows()) {
    $result = $db->fetch();
    $resultpassword = $result['password'];
}

if (md5($password.$salt) = $resultpassword) {
    $token = generateToken();
    $db->query("UPDATE `users` SET `token` = ? WHERE `username`=?")->bind(1, $token)->bind(2, $username)->execute();
    //header('Content-type: application/json');
    echo "Success";
    /*echo json_encode(array(
        'username' => $username,
        'token'    => $token,
        'message'  => 'Success'
    ));*/
    return true;
} else {
    //header('Content-type: application/json');
    echo "Failed";
    /*echo json_encode(array(
        'message' => 'Failed'
    ));*/
    return false;
}