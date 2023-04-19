<?php
error_reporting(0);
session_start();
header('Content-type: application/json; charset=utf-8');

require_once(__DIR__ . '/../include/Config.php');
require_once(__DIR__ . '/../include/PDOQuery.php');

$obj = (object)[];
function CreateJsonResponse() {
    global $obj;
    $obj->timestamp = time();
    die(json_encode($obj, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty(rtrim($_POST['username'])) && !empty(rtrim($_POST['password']))) {
        $user = $_POST['username'];
        $pass = $_POST['password'];
        
        if (!empty($_SESSION['username'])) {
            $obj->status = 'error';
            $obj->info = 'You already logged in.';
            CreateJsonResponse();
        }
        
        if(!preg_match('/^[a-zA-Z0-9_-]+$/', $user)) {
            $obj->status = 'error';
            $obj->info = 'Invalid username format!';
            CreateJsonResponse();
        }else{
            $q = Query('SELECT password FROM clients where username = :user', array(':user'=>$user));
            if ($q->rowCount() == 1) {
                $hash = $q->fetch()[0];
                if(password_verify($pass, $hash)) {
                    $_SESSION['username'] = $user;
                    $obj->status = 'success';
                    $obj->info = 'You have successfully logged in.';
                    CreateJsonResponse();
                }else{
                    $obj->status = 'error';
                    $obj->info = 'The password you entered is wrong!';
                    CreateJsonResponse();
                }
            }else{
                $obj->status = 'error';
                $obj->info = 'Username not found!';
                CreateJsonResponse();
            }
        }
    }else{
        $obj->status = 'error';
        $obj->info = 'Please fill in the information correctly and completely!';
        CreateJsonResponse();
    }
}
?>