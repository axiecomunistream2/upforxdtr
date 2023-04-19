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

if (!empty($_GET) && !empty($_GET['id'])) {
    $q = query('SELECT * FROM stock WHERE id = :id', array(':id'=>$_GET['id']));
    $result = $q->fetchAll();
    foreach($result as $row) {
        $item_id = $row['id'];
        $item_type = $row['type'];
        $item_contents = $row['contents'];
        $item_owner = $row['owner'];
		$item_date = $row['date'];
    }
}else{
    header('Location: backend.php');
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
                </form>
            </div>
        </section>
    </main>
<?php echo MinifyTemplate(__DIR__ . '/template/footer.php'); ?>

<!-- ADD ITEM -->
<form name="backendEditStock" method="post" action="" class="modal fade" id="editorform" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Product</h5>
        <a role="button" class="close" href="backend.php">
          <span aria-hidden="true">&times;</span>
        </a>
      </div>
      <div class="modal-body">
        <div class="form-group">
            <label for="inputItemType">Product type</label>
            <select class="form-control" id="inputItemType" name="inputItemType" required>
             <option value="none">Please select the product you wish to add.</option><?php
                $q = query('SELECT * FROM products');
                $result = $q->fetchAll();
                foreach($result as $row) {
                    if ($item_type == $row['id']) { $is_sel = 'selected'; } else { $is_sel = ''; }
                    echo '<option value="'.$row['id'].'"'.$is_sel.'>'.$row['name'].' - Price: '.$row['price'].' IDR'.' - Type'.$row['pattern'].'</option>';
                }
            ?></select>
        </div>
        <div class="form-group">
            <label for="inputItemData">Information of this product</label>
            <textarea type="text" id="inputItemData" name="inputItemData" class="form-control" placeholder="Write information sent to customer here" rows="5" required><?php echo $item_contents; ?></textarea>
        </div><?php
		if (!empty($item_order)) {
			echo '<div class="form-group"><label for="">Buyer</label><input type="text" id="" name="" class="form-control" placeholder="" value="'.$item_owner.'" disabled></input></div>';
			echo '<div class="form-group"><label for="">Date</label><input type="text" id="" name="" class="form-control" placeholder="" value="'.$item_date.'" disabled></input></div>';
		}
      ?></div>
      <div class="modal-footer">
        <input type="hidden" name="inputItemId" value="<?php echo $item_id; ?>"></input>
        <button type="button" onclick="deleteStock(<?php echo $item_id; ?>);" class="btn btn-danger">Delete</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
        <a class="btn btn-secondary" href="backend.php">Close</a>
      </div>
    </div>
  </div>
</form>
<script>
$(window).on('load',function(){
    $('#editorform').modal({backdrop: 'static', keyboard: false})  
    $('#editorform').modal('show');
});
</script>
<script>
function deleteStock(id) {
    swalx({
      title: 'Are you sure want to delete this product?',
      text: "Once deleted, you can't restore it!",
      type: 'question',
      showCancelButton: true,
      confirmButtonText: 'Continue',
      cancelButtonText: 'Cancel',
      reverseButtons: true,
      allowEscapeKey: false,
      allowEnterKey: false,
      allowOutsideClick: false
    }).then((result) => {
      if (result.value) {
        $.get("ajax/b_delete_stock.php?id="+id, function(data, status){
            if (data == "OK") {
                swalx({
                    title: 'Success!', 
                    text: 'Product deleted!', 
                    type: 'success',
                    timer: 1500,
                    showCancelButton: false,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false
                }).then(
                    function() { window.location.href = 'backend.php'; }
                );
            }else{
                ezSwal("Error!","Cannot be deleted","error");
            }
        });
      } else if (
        result.dismiss === swal.DismissReason.cancel
      ) {
        swal.close();
      }
    })
}
</script>
</body>
</html>