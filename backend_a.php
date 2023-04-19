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
    $q = query('SELECT * FROM products WHERE id = :id', array(':id'=>$_GET['id']));
    $result = $q->fetchAll();
    foreach($result as $row) {
        $product_id = $row['id'];
        $product_name = $row['name'];
        $product_desc = $row['description'];
        $product_img = $row['image'];
        $product_price = $row['price'];
        $product_help = $row['help'];
        $product_patt = $row['pattern'];
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
<form name="backendEditItem" method="post" action="" class="modal fade" id="editorform" tabindex="-1" role="dialog">
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
            <label>Name</label>
            <input type="text" name="inputProductName" class="form-control" placeholder="Race Sky V4" value="<?php echo $product_name; ?>" required autofocus>
        </div>
        <div class="form-group">
            <label>Price</label>
            <input type="number" min="1" name="inputProductPrice" class="form-control" placeholder="1200.00" value="<?php echo $product_price; ?>" required>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea type="text" name="inputProductDesc" class="form-control" placeholder="This product is . . ." required><?php echo $product_desc; ?></textarea>
        </div>
        <div class="form-group">
            <label>Instruction</label>
            <textarea type="text" name="inputProductHelp" class="form-control" placeholder="To use this product do . . ." required><?php echo $product_help; ?></textarea>
        </div>
        <div class="form-group">
            <label>Image URL</label>
            <input type="text" name="inputProductImg" class="form-control" placeholder="img/mc-account.png" value="<?php echo $product_img; ?>" required>
        </div>
        <div class="form-group">
            <label>Shipping method</label>
            <select class="form-control" name="inputProductPattern">
                <option value="none" selected disabled>Please select shipping method.</option>
                <option value="normaltext"<?php if($product_patt == 'normaltext') { echo 'selected';} ?>>&middot; Plain text &middot; (Suitable for sending URLs or message)</option>
                <option value="code"<?php if($product_patt == 'code') { echo 'selected';} ?>>&middot; Gift Code / Redeem Code &middot; (Suitable for game key)</option>
                <option value="eml:psw"<?php if($product_patt == 'eml:psw') { echo 'selected';} ?>>&middot; Email:Password &middot; (Suitable for most web account)</option>
                <option value="usr:psw"<?php if($product_patt == 'usr:psw') { echo 'selected';} ?>>&middot; Username:Password &middot; (Suitable for most game account)</option>
                <option value="usr:eml:psw"<?php if($product_patt == 'usr:eml:psw') { echo 'selected';} ?>>&middot; Username:Email:Password &middot; (Suitable for full access account)</option>
            </select>
        </div>
      </div>
      <div class="modal-footer">
        <input type="hidden" name="inputProductId" value="<?php echo $product_id; ?>"></input>
        <button type="button" onclick="deleteProduct(<?php echo $product_id; ?>);" class="btn btn-danger">Delete</button>
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
function deleteProduct(id) {
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
        $.get("ajax/b_delete_item.php?id="+id, function(data, status){
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
                ezSwal("Error!","Cannot be deleted.","error");
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