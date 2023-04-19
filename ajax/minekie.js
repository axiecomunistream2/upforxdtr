
function maxLengthCheck(object) {
    if (object.value.length > object.max.length)
    object.value = object.value.slice(0, object.max.length)
}
    
function isNumeric (evt) {
    var theEvent = evt || window.event;
    var key = theEvent.keyCode || theEvent.which;
    key = String.fromCharCode (key);
    var regex = /[0-9]|\./;
    if ( !regex.test(key) ) {
        theEvent.returnValue = false;
        if(theEvent.preventDefault) theEvent.preventDefault();
    }
}

function PaymentMethodChange(obj) {
    if (obj.selectedData.value == '') {
        $('#TopupManual').html('');
    }else{
        $.get({url:"ajax/manual-"+obj.selectedData.value+".php",cache:false}).done(function(obj){ $('#TopupManual').html("<label><b>วิธีการชำระเงิน</b></label><pre>" + obj+"</pre>"); });
        if (obj.selectedData.value == 'tm') {
            $('#PaymentInputType').html('รหัสบัตรเงินสด');
        }else if (obj.selectedData.value == 'tw') {
            $('#PaymentInputType').html('หมายเลขอ้างอิง');
        }
        $('input[name=x-number]').val('');
    }
}

function PurchaseModal(id) {
    $.ajax({
        method: "GET",
        url: "ajax/modal_purchase.php?id="+id,
        cache: false,
    }).done(function(obj){
        $('#modalContainer').html(obj);
        $('#modalPurchase').modal('show');
    }).fail(function(obj){
        console.log(obj);
    });
}

function PurchaseInfo(id) {
    $.ajax({
        method: "GET",
        url: "ajax/modal_info.php?id="+id,
        cache: false,
    }).done(function(obj){
        $('#modalContainer').html(obj);
        $('#modalInfo').modal({backdrop: 'static', keyboard: false})  
        $('#modalInfo').modal('show');
    }).fail(function(obj){
        console.log(obj);
    });
}

function ProcessPurchase(id) {
    swalx({
      title: 'Are you sure?',
      text: "If you buy the wrong product, we will not be responsible for anything.",
      type: 'question',
      showCancelButton: true,
      confirmButtonText: 'Continue',
      cancelButtonText: 'Cancel',
      reverseButtons: false,
      allowEscapeKey: false,
      allowEnterKey: false,
      allowOutsideClick: false
    }).then((result) => {
      if (result.value) {
        swal({
            title: 'Processing', 
            text: 'Preparing you order, please wait...',
            type: 'info',
            showCancelButton: false,
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            onOpen: () => {
                $.ajax({
                    method: "GET",
                    url: "ajax/submitPurchase.php?id="+id,
                    cache: false,
                }).done(function(obj){
                    console.log(obj);
                    if (obj.status === 'error') {
                        swalx('Error!', obj.info, 'error');
                    }else if (obj.status === 'success') {
                        swalx({
                            title: 'Success!', 
                            text: obj.info, 
                            type: 'success',
                            timer: 1500,
                            showCancelButton: false,
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        }).then(
                            function() { window.location.href = 'history.php'; }
                        );
                    }
                }).fail(function(obj){
                    console.log(obj);
                });
            }
          });
      } else if (result.dismiss === swal.DismissReason.cancel) {
        swal.close();
      }
    })
}

$(document).ready(function(){
    
    $('form[name=backendAddStock]').submit(function(e) {
        e.preventDefault();
        swal({
            title: 'Processing', 
            text: 'Preparing your order, please wait...',
            type: 'info',
            showCancelButton: false,
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            onOpen: () => {
                swal.showLoading();
                $.ajax({
                    method: "POST",
                    url: "ajax/b_add_stock.php",
                    cache: false,
                    data: $(this).serialize()
                }).done(function(obj){
                    console.log(obj);
                    if (obj.status === 'error') {
                        swalx('Error!', obj.info, 'error');
                    }else if (obj.status === 'success') {
                        swalx({
                            title: 'Success!', 
                            text: obj.info, 
                            type: 'success',
                            timer: 1500,
                            showCancelButton: false,
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        }).then(
                            function() { window.location.href = 'backend.php'; }
                        );
                    }
                }).fail(function(obj){
                    console.log(obj);
                });
            }
        });
    });
    
    $('form[name=backendEditStock]').submit(function(e) {
        e.preventDefault();
        swal({
            title: 'Processing', 
            text: 'Preparing your order, please wait...',
            type: 'info',
            showCancelButton: false,
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            onOpen: () => {
                swal.showLoading();
                $.ajax({
                    method: "POST",
                    url: "ajax/b_edit_stock.php",
                    cache: false,
                    data: $(this).serialize()
                }).done(function(obj){
                    console.log(obj);
                    if (obj.status === 'error') {
                        swalx('Error!', obj.info, 'error');
                    }else if (obj.status === 'success') {
                        swalx({
                            title: 'Success!', 
                            text: obj.info, 
                            type: 'success',
                            timer: 1500,
                            showCancelButton: false,
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        }).then(
                            function() { window.location.href = 'backend.php'; }
                        );
                    }
                }).fail(function(obj){
                    console.log(obj);
                });
            }
        });
    });
    
    $('form[name=backendAddItem]').submit(function(e) {
        e.preventDefault();
        swal({
            title: 'Processing', 
            text: 'Preparing your order, please wait...',
            type: 'info',
            showCancelButton: false,
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            onOpen: () => {
                swal.showLoading();
                $.ajax({
                    method: "POST",
                    url: "ajax/b_add_item.php",
                    cache: false,
                    data: $(this).serialize()
                }).done(function(obj){
                    console.log(obj);
                    if (obj.status === 'error') {
                        swalx('Error!', obj.info, 'error');
                    }else if (obj.status === 'success') {
                        swalx({
                            title: 'Success!', 
                            text: obj.info, 
                            type: 'success',
                            timer: 1500,
                            showCancelButton: false,
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        }).then(
                            function() { window.location.href = 'backend.php'; }
                        );
                    }
                }).fail(function(obj){
                    console.log(obj);
                });
            }
        });
    });
    
    $('form[name=backendEditItem]').submit(function(e) {
        e.preventDefault();
        swal({
            title: 'Processing', 
            text: 'Preparing your order, please wait...',
            type: 'info',
            showCancelButton: false,
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            onOpen: () => {
                swal.showLoading();
                $.ajax({
                    method: "POST",
                    url: "ajax/b_edit_item.php",
                    cache: false,
                    data: $(this).serialize()
                }).done(function(obj){
                    console.log(obj);
                    if (obj.status === 'error') {
                        swalx('Error!', obj.info, 'error');
                    }else if (obj.status === 'success') {
                        swalx({
                            title: 'Success!', 
                            text: obj.info, 
                            type: 'success',
                            timer: 1500,
                            showCancelButton: false,
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        }).then(
                            function() { window.location.href = 'backend.php'; }
                        );
                    }
                }).fail(function(obj){
                    console.log(obj);
                });
            }
        });
    });
    
    $('form[name=payment]').submit(function(e) {
        e.preventDefault();
        swal({
            title: 'Processing', 
            text: 'Preparing your order, please wait...',
            type: 'info',
            showCancelButton: false,
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            onOpen: () => {
                swal.showLoading();
                $.ajax({
                    method: "POST",
                    url: "ajax/submitPayment.php",
                    cache: false,
                    data: $(this).serialize()
                }).done(function(obj){
                    console.log(obj);
                    if (obj.status === 'error') {
                        swalx('Error!', obj.info, 'error');
                    }else if (obj.status === 'success') {
                        swalx({
                            title: 'Success!', 
                            text: obj.info, 
                            type: 'success',
                            timer: 1500,
                            showCancelButton: false,
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        }).then(
                            function() { window.location.href = 'topup.php'; }
                        );
                    }
                    $('input[name=x-number]').val('');
                }).fail(function(obj){
                    console.log(obj);
                });
            }
        });
    });
    
    $('form[name=login]').submit(function(e) {
        e.preventDefault();
        $.ajax({
            method: "POST",
            url: "ajax/login.php",
            cache: false,
            data: $(this).serialize()
        }).done(function(obj){
            console.log(obj);
            if (obj.status === 'error') {
                swalx('Error!', obj.info, 'error');
            }else if (obj.status === 'success') {
                swalx({
                    title: 'Success!', 
                    text: obj.info, 
                    type: 'success',
                    timer: 1500,
                    showCancelButton: false,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false
                }).then(
                    function() { window.location.href = 'index.php'; }
                );
            }
        }).fail(function(obj){
            alert(obj);
        });
    });
    
    $('form[name=register]').submit(function(e) {
        e.preventDefault();
        $.ajax({
            method: "POST",
            url: "ajax/register.php",
            cache: false,
            data: $(this).serialize()
        }).done(function(obj){
            console.log(obj);
            if (obj.status === 'error') {
                swalx('Error!', obj.info, 'error');
            }else if (obj.status === 'success') {
                swalx({
                    title: 'Success!', 
                    text: obj.info, 
                    type: 'success',
                    timer: 1500,
                    showCancelButton: false,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false
                }).then(
                    function() { window.location.href = 'index.php'; }
                );
            }
        }).fail(function(obj){
            alert(obj);
        });
    });
});