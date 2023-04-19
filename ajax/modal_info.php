<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: ../index.php');
}

require_once(__DIR__ . '/../include/Core.php'); 
require_once(__DIR__ . '/../include/Config.php');
require_once(__DIR__ . '/../include/PDOQuery.php');
if (!empty($_GET) && !empty($_GET['id'])) {
    $q = query('SELECT * FROM stock WHERE owner = :user AND id = :id', array(':user'=>strtolower($_SESSION['username']), ':id'=>$_GET['id']));
    $result = $q->fetchAll();
    foreach($result as $row) {
        $item_id = $row['id'];
        $item_type = $row['type'];
        $item_contents = $row['contents'];
        $item_date = $row['date'];
        $item_owner = $row['owner'];
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
    
    if ($product_patt == 'usr:eml:psw') {
        $order_preset = $item_contents;
        $array =  explode(':', $order_preset);
        $order['user'] = array_values($array)[0];
        $order['email'] = array_values($array)[1];
        $order['pass'] = array_values($array)[2];
        $order_contents = '<b>Email</b>: '.$order['email'].'<br>';
        $order_contents .= '<b>Username</b>: '.$order['user'].'<br>';
        $order_contents .= '<b>Password</b>: '.$order['pass'];
    }elseif ($product_patt == 'usr:psw') {
        $order_preset = $item_contents;
        $array =  explode(':', $order_preset);
        $order['user'] = array_values($array)[0];
        $order['pass'] = array_values($array)[1];
        $order_contents = '<b>Username</b>: '.$order['user'].'<br>';
        $order_contents .= '<b>Password</b>: '.$order['pass'];
    }elseif ($product_patt == 'eml:psw') {
        $order_preset = $item_contents;
        $array =  explode(':', $order_preset);
        $order['email'] = array_values($array)[0];
        $order['pass'] = array_values($array)[1];
        $order_contents = '<b>Email</b>: '.$order['email'].'<br>';
        $order_contents .= '<b>Password</b>: '.$order['pass'];
    }elseif ($product_patt == 'code') {
        $order_contents = '<b>Code</b>: ' . $item_contents;
    }elseif ($product_patt == 'normaltext') {
        $order_contents = $item_contents;
    }
}else{
    header('Location: ../index.php');
}
?>
<div id="modalInfo" class="modal fade animated slideIn faster" id="additem" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><?php echo $product_name; ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <b>Account Data</b><br>
        <?php echo $order_contents ?>
        <br><br><b>Instruction</b><br>
        <?php echo nl2br($product_help) ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>