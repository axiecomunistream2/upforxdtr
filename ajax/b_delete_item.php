<?php
error_reporting(0);
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: ../index.php');
}

require_once(__DIR__ . '/../include/Config.php');
require_once(__DIR__ . '/../include/PDOQuery.php');

$q = Query('SELECT type FROM clients where username = :user', array(':user'=>$_SESSION['username']));
$status = $q->fetchColumn();
if ($status !== 'manager') {
    die();
}

if (!empty($_GET) && !empty($_GET['id'])) {
    $q1 = query('DELETE FROM products WHERE id= :id', array(':id'=>$_GET['id']));
    if ($q1) {
        echo 'OK';
    }
}
?>