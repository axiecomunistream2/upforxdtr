<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: ../index.php');
}

require_once(__DIR__ . '/../include/Core.php'); 
require_once(__DIR__ . '/../include/Config.php');
require_once(__DIR__ . '/../include/PDOQuery.php');
if (!empty($_GET) && !empty($_GET['id'])) {
    $q = query('SELECT * FROM products WHERE id = :id LIMIT 1', array(':id'=>$_GET['id']));
    if ($q->rowCount() > 0) {
        $result = $q->fetchAll();
        foreach($result as $row){
            $product_id = $row['id'];
            $product_name = $row['name'];
            $product_desc = $row['description'];
            $product_img = $row['image'];
            $product_price = $row['price'];
            $product_help = $row['help'];
            $product_patt = $row['pattern'];
            $q = Query('SELECT count(*) FROM stock WHERE type = :id AND owner = ""', array(':id'=>$product_id));
            $result = $q->fetchColumn();
            $stock = $result;
        }
    }else{
        $product_id = 0;
        $product_name = 'Product not found';
        $product_desc = 'This product was not found in the system.';
        $stock = 0;
    }
}else{
    header('Location: ../index.php');
}
?>
<div id="modalPurchase" class="modal fade animated slideIn faster" id="additem" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><?php echo $product_name; ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php echo nl2br($product_desc); ?>
      </div>
      <div class="modal-footer">
        <span class="mr-auto text-muted"><?php echo $stock; ?> in Stock</span>
        <?php if ($stock > 0) { echo '<button type="button" class="btn btn-primary" onclick="ProcessPurchase('.$product_id.')">Purchase for IDR '.$product_price.'</button>';
        } else { echo '<button type="button" class="btn btn-primary disabled" disabled>Out of stock</button>'; } ?>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>