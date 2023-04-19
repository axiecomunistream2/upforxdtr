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

query('CREATE TABLE stock_copy LIKE stock;');
query('INSERT INTO stock_copy (id, type, contents, owner, date) SELECT min(id), type, contents, owner, date FROM stock GROUP BY type, type, contents, owner, date;');
query('RENAME TABLE stock TO stock_old, stock_copy TO stock;');
query('DROP TABLE stock_old;');
header('Location: ../backend.php');
die();
?>