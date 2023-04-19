<?php 
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
}
require_once(__DIR__ . '/include/Core.php'); 
require_once(__DIR__ . '/include/Config.php');
require_once(__DIR__ . '/include/PDOQuery.php');
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
                    <h2 data-bs-hover-animate="pulse">Order History</h2>
                </div>
            </div>
        </section>
    </main>
<?php echo MinifyTemplate(__DIR__ . '/template/footer.php'); ?>
<div id="modalHistory" class="modal fade animated slideIn faster" id="additem" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Order History</h5>
        <a class="close" href="index.php">
          <span aria-hidden="true">&times;</span>
        </a>
      </div>
      <div class="modal-body">
        <table id="history" class="display">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
					<th scope="col">Product name</th>
					<th scope="col">Date</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody><?php
                          $q = query('SELECT * FROM stock WHERE owner = :user', array(':user'=>strtolower($_SESSION['username'])));
                          if ($q->rowCount() == 0) {
                          }else{
                              $result = $q->fetchAll();
                              foreach($result as $row) {
                                $qProductMeta = query('SELECT * FROM products WHERE id= :id', array(':id'=>$row['type']));
                                $resultProductMeta = $qProductMeta->fetchAll();
                                foreach($resultProductMeta as $data)
                                {
                                    $product_name = $data['name'];
                                }
                                echo '<tr><th scope="row">'.$row['id'].'</th><td>'.$product_name.'</td><td>'.date("d/m/Y", strtotime($row['date'])).'</td><td><button type="button" class="btn btn-primary" onclick="PurchaseInfo('.$row['id'].')">View</button></td></tr>';
                              }
                          }
            ?></tbody>
        </table>
      </div>
      <div class="modal-footer">
        <a class="btn btn-secondary" href="index.php">Close</a>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
$(document).ready( function () {
    $('#modalHistory').modal({backdrop: 'static', keyboard: false})  
    $('#modalHistory').modal('show');
    $('#history').DataTable();
} );
</script>
<div id="modalContainer"></div>
</body>
</html>