<?php

if (!$_cfg) { die(); }

$pdo = new PDO("mysql:host={$_cfg['host']};dbname={$_cfg['dbnm']};charset=utf8;", $_cfg['user'], $_cfg['pass']);

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
$pdo->exec("SET NAMES 'UTF8';");
$pdo->exec("SET CHARACTER SET utf8");
$pdo->exec("SET character_set_results=utf8");
$pdo->exec("SET character_set_client=utf8");
$pdo->exec("SET character_set_connection=utf8");

function Query($sql, $array = []) {
    global $pdo;
    $q = $pdo->prepare($sql);
    $q->execute($array);
    return $q;
}

?>