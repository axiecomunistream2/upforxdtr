<?php 
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: ../index.php');
}
require_once(__DIR__ . '/include/Core.php'); 
require_once(__DIR__ . '/include/Config.php');
require_once(__DIR__ . '/include/PDOQuery.php');
$q = Query('SELECT type FROM clients where username = :user', array(':user'=>$_SESSION['username']));
$status = $q->fetchColumn();
if ($status !== 'manager') {
    header('Location: index.php');
}
?>
<!DOCTYPE html>
<html><head>
<?php echo MinifyTemplate(__DIR__ . '/template/header.php'); ?>
</head><body>
<?php echo MinifyTemplate(__DIR__ . '/template/navbar.php'); ?>
    <main class="page contact-page">
        <section data-aos="fade-up" class="portfolio-block contact">
            <div class="container">
                <div class="heading">
                    <h2 data-bs-hover-animate="pulse">Backend System</h2>
                </div>
                <form data-aos="flip-left" data-aos-duration="800">
                    <a class="btn btn-primary btn-block btn-lg" data-toggle="modal" data-target="#additem" data-backdrop="static" data-keyboard="false" href="#">Add Item</a>
                    <a class="btn btn-primary btn-block btn-lg" data-toggle="modal" data-target="#edititem" data-backdrop="static" data-keyboard="false" href="#">Manage Item</a>
                    <a class="btn btn-primary btn-block btn-lg" data-toggle="modal" data-target="#addstock" data-backdrop="static" data-keyboard="false" href="#">Add Stock</a>
                    <a class="btn btn-primary btn-block btn-lg" data-toggle="modal" data-target="#editstock" data-backdrop="static" data-keyboard="false" href="#">Manage Stock</a>
                </form>
            </div>
        </section>
    </main>
<?php echo MinifyTemplate(__DIR__ . '/template/footer.php'); ?>

<!-- ADD ITEM -->
<form name="backendAddItem" method="post" action="" class="modal fade" id="additem" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Add Product</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
            <label>Name</label>
            <input type="text" name="inputProductName" class="form-control" placeholder="Race Sky V4" required autofocus>
        </div>
        <div class="form-group">
            <label>Price</label>
            <input type="number" min="1" name="inputProductPrice" class="form-control" placeholder="1200.00" required>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea type="text" name="inputProductDesc" class="form-control" placeholder="This product is . . ." required></textarea>
        </div>
        <div class="form-group">
            <label>Instruction</label>
            <textarea type="text" name="inputProductHelp" class="form-control" placeholder="To use this product do . . ." required></textarea>
        </div>
        <div class="form-group">
            <label>Image URL</label>
            <input type="text" name="inputProductImg" class="form-control" placeholder="img/mc-account.png" required>
        </div>
        <div class="form-group">
            <label>Shipping method</label>
            <select class="form-control" name="inputProductPattern">
                <option value="none" selected disabled>Please select shipping method.</option>
                <option value="normaltext">&middot; Plain text &middot; (Suitable for sending URLs or message)</option>
                <option value="code">&middot; Gift Code / Redeem Code &middot; (Suitable for game key)</option>
                <option value="eml:psw">&middot; Email:Password &middot; (Suitable for most web account)</option>
                <option value="usr:psw">&middot; Username:Password &middot; (Suitable for most game account)</option>
                <option value="usr:eml:psw">&middot; Username:Email:Password &middot; (Suitable for full access account)</option>
            </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Save changes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</form>
<!-- EDIT ITEM -->
<div class="modal fade" id="edititem" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Manage product</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table id="products" class="display">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Product name</th>
                    <th scope="col">Price</th>
                    <th scope="col">Manage</th>
                </tr>
            </thead>
                <tbody><?php
                    $q = query('SELECT * FROM products');
                    $result = $q->fetchAll();
                    foreach($result as $row){echo '<tr><th scope="row">'.$row['id'].'</th><td>'.$row['name'].'</td><td>'.$row['price'].'</td><td><a class="btn btn-sm btn-block btn-danger" href="backend_a.php?id='.$row['id'].'">Edit/Delete</a></td></tr>';}
                ?></tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- ADD STOCK -->
<form name="backendAddStock" method="post" action="" class="modal fade" id="addstock" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Add Stock</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
            <label>Product type</label>
            <select class="form-control" id="inputItemType" name="inputItemType" required>
                <option value="" selected disabled>Please select the product you wish to add.</option><?php
                    $q = query('SELECT * FROM products');
                    $result = $q->fetchAll();
                    foreach($result as $row) { echo '<option value="'.$row['id'].'">'.$row['name'].' - Price: IDR '.$row['price'].' -  Method: '.$row['pattern'].'</option>'; } ?></select>
        </div>
        <div class="form-group">
            <label>Information of this product</label>
            <textarea type="text" id="inputItemData" name="inputItemData" class="form-control" placeholder="Write information sent to customer here" rows="5" required></textarea>
            <label class="mt-2 text-muted">Do you know? You can add multiple stocks by typing &lt;batch&gt; in the first line. <a href="#" onclick="$('#inputItemData').val('<batch>\n' + $('#inputItemData').val()); $('#inputItemData').focus();">Click to add</a></label>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Save changes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</form>
<!-- EDIT STOCK -->
<div class="modal fade" id="editstock" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Manage Product Stock</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table id="stock" class="display">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
					<th scope="col">Product name</th>
					<th scope="col">Buyer's email</th>
					<th scope="col">Date</th>
                    <th scope="col">Manage</th>
                </tr>
            </thead>
            <tbody><?php
                          $q = query('SELECT * FROM stock');
                          $result = $q->fetchAll();
                          foreach($result as $row)
                          {
                              
                            $qProductMeta = query('SELECT * FROM products WHERE id= :id', array(':id'=>$row['type']));
                            $resultProductMeta = $qProductMeta->fetchAll();
                            foreach($resultProductMeta as $data)
                            {
                                $product_name = $data['name'];
                            }
                            
                            if (empty($row['owner'])) {
                                $row['owner'] = 'Not purchased yet';
                            }
							
							if (empty($row['date'])) {
                                $row['date'] = 'Not purchased yet';
                            }
                            
                            echo '<tr><th scope="row">'.$row['id'].'</th><td>'.$product_name.'</td><td>'.$row['owner'].'</td><td>'.$row['date'].'</td><td><a class="btn btn-sm btn-block btn-danger" href="backend_b.php?id='.$row['id'].'">Edit/Delete</a></td></tr>';
                          }
            ?></tbody>
        </table>
      </div>
      <div class="modal-footer">
        <a class="btn btn-danger" href="ajax/b_clean_stock.php">Remove Duplicate</a>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<script>
$(document).ready( function () {
    $('#products').DataTable();
    $('#stock').DataTable();
} );
</script>
</body>
</html>