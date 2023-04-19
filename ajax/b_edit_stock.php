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
if (!empty(rtrim($_POST['inputItemType'])) && !empty(rtrim($_POST['inputItemData'])) && !empty(rtrim($_POST['inputItemId']))) {
        if (is_numeric($_POST['inputItemType']) && is_numeric($_POST['inputItemId'])) {
            $item_id = $_POST['inputItemId'];
            $req_type = $_POST['inputItemType'];
            $req_data = $_POST['inputItemData'];
            
            $q1 = query('UPDATE stock SET type= :type, contents= :data WHERE id= :id', array(':data'=>$req_data,':type'=>$req_type,':id'=>$item_id));
            if (!$q1) {
                $obj->status = 'error';
                $obj->info = 'Unable to connect to the database!';
                CreateJsonResponse();
            }else{
                $obj->status = 'success';
                $obj->info = 'Successfully executed!';
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