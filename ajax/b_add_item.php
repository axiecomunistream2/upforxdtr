<?php
error_reporting(0);
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: ../index.php');
}
header('Content-type: application/json; charset=utf-8');

require_once(__DIR__ . '/../include/Config.php');
require_once(__DIR__ . '/../include/PDOQuery.php');

$q = Query('SELECT type FROM clients where username = :user', array(':user'=>$_SESSION['username']));
$status = $q->fetchColumn();
if ($status !== 'manager') {
    die();
}

$obj = (object)[];
function CreateJsonResponse() {
    global $obj;
    $obj->timestamp = time();
    die(json_encode($obj, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty(rtrim($_POST['inputProductName'])) && !empty(rtrim($_POST['inputProductPrice'])) && !empty(rtrim($_POST['inputProductDesc'])) && !empty(rtrim($_POST['inputProductHelp'])) && !empty(rtrim($_POST['inputProductImg'])) && !empty(rtrim($_POST['inputProductPattern']))) {
        $allowed_pattern = array("normaltext","code","eml:psw","usr:psw","usr:eml:psw");
        if (is_numeric($_POST['inputProductPrice']) && in_array($_POST['inputProductPattern'], $allowed_pattern)) {
            $req_name = $_POST['inputProductName'];
            $req_price = floor($_POST['inputProductPrice']);
            $req_desc = $_POST['inputProductDesc'];
            $req_help = $_POST['inputProductHelp'];
            $req_img = $_POST['inputProductImg'];
            $req_patt = $_POST['inputProductPattern'];
            
            $q1 = query('INSERT INTO products (name, description, price, pattern, help, image) VALUES (:name, :desc, :price, :patt, :inst, :img)', array(':name'=>$req_name,':desc'=>$req_desc,':price'=>$req_price,':patt'=>$req_patt,':inst'=>$req_help,':img'=>$req_img));
            if (!$q1) {
                $obj->status = 'error';
                $obj->info = 'Unable to connect to the database!';
                CreateJsonResponse();
            }else{
                $obj->status = 'success';
                $obj->info = 'Product added!';
                CreateJsonResponse();
            }
        }else{
            $obj->status = 'error';
            $obj->info = 'Please fill in the information correctly and completely!';
            CreateJsonResponse();
        }
    }else{
        $obj->status = 'error';
        $obj->info = 'Please fill in the information correctly and completely!';
        CreateJsonResponse();
    }
}
?>