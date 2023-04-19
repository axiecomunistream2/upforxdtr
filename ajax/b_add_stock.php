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
if (!empty(rtrim($_POST['inputItemType'])) && !empty(rtrim($_POST['inputItemData']))) {
        if (is_numeric($_POST['inputItemType'])) {
            $req_type = $_POST['inputItemType'];
            $req_data = $_POST['inputItemData'];
            
            $allData = preg_split('/\r\n|\r|\n/', $req_data);
            if (array_values($allData)[0] == '<batch>') {
                foreach ($allData as $myData) {
                    if ($myData != '<batch>') {
                        $q1 = query('INSERT INTO stock (type, contents) VALUES (:type, :data)', array(':data'=>$myData,':type'=>$req_type));
                    }
                }
                if (!$q1) {
                    $obj->status = 'error';
                    $obj->info = 'Unable to connect to the database!';
                    CreateJsonResponse();
                }else{
                    $obj->status = 'success';
                    $obj->info = 'Stock added!';
                    CreateJsonResponse();
                }
            }else{
                $q1 = query('INSERT INTO stock (type, contents) VALUES (:type, :data)', array(':data'=>$req_data,':type'=>$req_type));
                if (!$q1) {
                    $obj->status = 'error';
                    $obj->info = 'Unable to connect to the database!';
                    CreateJsonResponse();
                }else{
                    $obj->status = 'success';
                    $obj->info = 'Stock added!';
                    CreateJsonResponse();
                }
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