<?php

require "/classes/Database.php";
header("Content-type: text/json");

$dbinfo = array(
    "host" => "127.0.0.1",
    "user" => "root",
    "pass" => "",
    "name" => "hiskor"
);

// Creates the PDO Object for queries
$db = new Database ( $dbinfo );
$db->jsonError = true;

// Checks the type of request and redirects
$_POST['type'] = isset($_POST['type']) ? $_POST['type'] : null ;
switch ($_POST['type'])
{
    case 'login':
        login();
    break;

    case 'register':
        register();
    break;
}


function login()
{

    global $db;

    if (isset($_POST['username']) && isset($_POST['passwordMD5']))
    {
        //Put parameters into local variables
        $username = $_POST['username'];
        $passwordMD5 = $_POST['passwordMD5'];

        // Gets the hashed password for the user in the database
        $db->query("SELECT `password` FROM `users` WHERE `username`=?")->bind(1, $username)->execute();
        if ($db->getTotalRows()) {
            $result = $db->fetch();
            $resultpassword = $result['password'];
        }

        // Checks if the hash sent from the client matches hash in database
        if ($passwordMD5 == $resultpassword) {
            $token = generateToken();
            $db->query("UPDATE `users` SET `token` = ? WHERE `username`=?")->bind(1, $token)->bind(2, $username)->execute();
            header('Content-type: application/json');
            echo json_encode(array(
                'username' => $username,
                'token'    => $token,
                'message'  => 'Success'
            ));
            return true;
        } else {
            header('Content-type: application/json');
            echo json_encode(array(
                'message' => 'Failed'
            ));
            return false;
        }
    }
    return false;
}

function register()
{
    global $db;

    if (isset($_POST['username']) && isset($_POST['passwordMD5']) && isset($_POST['email']))
    {
        $username = $_POST['username'];
        $passwordMD5 = $_POST['passwordMD5'];
        $email = $_POST['email'];

        $db->query("SELECT `username`, `email` FROM `users` WHERE `username=?` OR `email=?`")->bind(1, $username)->bind(2, $email)->execute();
        if ($db->getTotalRows()) {
            $result = $db->fetch();
        }

        if (!isset($result)) {
            $db->query("INSERT INTO `users` (`username`, `password`, `email`) VALUES (`?`, `?`, `?`)")->bind(1, $username)->bind(2, $passwordMD5)->bind(3, $email)->execute();
            header('Content-type: application/json');
            echo json_encode(array(
                'username'  => $username,
                'email'     => $email,
                'status'    => 'Registration Passed'
            ));
            return true;
        } else {
            header('Content-type: application/json');
            echo json_encode(array(
                'username'  => $username,
                'email'     => $email,
                'status'    => 'Registration Failed'
            ));
            return false;
        }
    }
    return false;
}


// Generates random token for authentication sessions
function generateToken()
{
    $token_length = 25;
    $token = '';
 
    for ($length = 0; $length < $token_length; $length++) {

        $num = mt_rand(48, 122);

        if ($num == '92') {
            $num = '93';
        }

        $token .= chr($num);
    }
    return $token;
}