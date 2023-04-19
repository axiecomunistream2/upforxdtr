<?php
error_reporting(0);
header('Content-type: application/json; charset=utf-8');
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: ../index.php');
}

require_once(__DIR__ . '/../include/Config.php');
require_once(__DIR__ . '/../include/PDOQuery.php');

$obj = (object)[];
function CreateJsonResponse() {
    global $obj;
    $obj->timestamp = time();
    die(json_encode($obj, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));
}

if (!empty($_GET['id'])) {
    $product_id = $_GET['id'];
    $q = Query('SELECT count(*) FROM stock WHERE type = :id AND owner = ""', array(':id'=>$product_id));
    $result = $q->fetchColumn();
    if ($result > 0) {
        $q = query('SELECT * FROM stock WHERE type= :type AND owner="" ORDER BY RAND() LIMIT 1', array(':type'=>$product_id));
        $result = $q->fetchAll();
        foreach($result as $row) {
            $item_id = $row['id'];
            $item_type = $row['type'];
            $item_contents = $row['contents'];
            $item_date = $row['date'];
        }
        $q = query('SELECT * FROM products WHERE id = :id', array(':id'=>$item_type));
        $result = $q->fetchAll();
        foreach($result as $row) {
            $product_id = $row['id'];
            $product_name = $row['name'];
            $product_price = $row['price'];
            $product_help = $row['help'];
            $product_patt = $row['pattern'];
        }
        
        $q = Query('SELECT coins FROM clients where username = :user', array(':user'=>$_SESSION['username']));
        $coins = $q->fetchColumn();
        if ($coins >= $product_price) {
            Query('UPDATE stock SET owner= :owner, date= :date WHERE id= :id', array(':owner'=>rtrim($_SESSION['username']),':id'=>$item_id,':date'=>date("Y-m-d H:i:s")));
            Query('UPDATE clients SET coins = coins - :amount WHERE username = :user', array(':user'=>$_SESSION['username'],':amount'=>$product_price));
            $obj->status = 'success';
            $obj->info = 'Successful purchase!';
            CreateJsonResponse();
        }else{
            $obj->status = 'error';
            $obj->info = 'Your balance is insufficient to purchase this product.';
            CreateJsonResponse();
        }
    }else{
        $obj->status = 'error';
        $obj->info = 'Out of stock, sorry :(';
        CreateJsonResponse();
    }
}
?>