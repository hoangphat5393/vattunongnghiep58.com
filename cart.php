<?php require_once('cart_c/cart_c.php');?>

<?php 
    
    $atz = new cart_controller();

    $cart = $atz->update_cart();
    
    $rs = $atz->checkout_cart();

    $post = $atz->post;
    
    $errors = array();
    if(isset($rs['errors'])){
        $errors = $rs['errors'];    
    }
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        

        <!-- HEAD -->
        <?php include('modules/head.php') ?>
        <!-- END HEAD -->

        <title>Giỏ hàng - <?=SETTING['Setting_Title']?></title>
    </head>

    <body>

        <!-- HEADER -->
        <header>
            <?php include('modules/header.php');?>
        </header>
        <!-- END HEADER -->
        
        <!-- MENU -->
        <?php include('modules/menu.php')?>
        <!-- MENU HEADER -->

        <div class="container mt-4 cart">

            <form action="" method="post" id="contact-form" class="contact-one__form">
                
                <div class="row mt-3">

                    <div class="col-lg-3 order-2 order-lg-1 main-cat">
                        <div class="row mb-3">
                            <?php include('modules/left_sidebar.php')?>
                        </div>
                    </div>

                    <div class="col-lg-9 order-1 order-lg-2 mb-5 main-product contact-form">
                        <h3>Giỏ hàng của bạn</h3>
                        <div class="row">
                            <div class="col-md-8">
                            
                                
                                <div class="cart-info">

                                    <div class="table-responsive mt-4">
                                        <table class="table vcenter">
                                            <thead>
                                                <tr>
                                                    <th>Sản phẩm</th>
                                                    <th>Sô lượng</th>
                                                    <th>Thành tiền</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (isset($_SESSION['cart'])&&!empty($_SESSION['cart'])): ?>

                                                    <?php foreach ($_SESSION['cart'] as $k => $v): ?>

                                                        <tr class="product_item_<?=$v['Product_ID']?>">
                                                            <td>
                                                                <a href="product.php?id=<?=$v['Product_ID']?>" title="<?=$v['Product_Name_vi']?>">
                                                                    <img class="img-fluid" width="100" src="<?=str_replace('../', '', $v['Product_Thumbnail'])?>" alt="<?=$v['Product_Name_vi']?>">
                                                                </a>
                                                            </td>
                                                            <td>
                                                                <?=$v['Product_Name_vi']?> 
                                                                <div class="row">
                                                                <div class="input-group mt-3 col-12 col-md-8 col-lg-8 col-xl-6">
                                                                    <div class="input-group-prepend" id="button-addon3">
                                                                        <button class="btn btn-sm btn-outline-secondary" type="button" onclick="minus_number(<?=$v['Product_ID']?>)"><i class="fas fa-minus"></i></button>
                                                                    </div>
                                                                    
                                                                    <input type="hidden" name="product_id[<?=$v['Product_ID']?>]" class="form-control" value="<?=$v['Product_ID']?>">
                                                                    <input type="hidden" name="price[<?=$v['Product_ID']?>]" class="form-control price" value="<?=$v['Product_Price']?>">
                                                                    
                                                                    <input type="number" name="quanlity[<?=$v['Product_ID']?>]" class="form-control form-control-sm quanlity" data-price="<?=$v['Product_Price']?>" quanlity-id="<?=$v['Product_ID']?>" value="1">
                                                                    
                                                                    <input type="hidden" class="current_price" value="<?=$v['Product_Price']*1?>">

                                                                    <div class="input-group-append" id="button-addon3">
                                                                        <button class="btn btn-sm btn-outline-secondary" type="button" onclick="plus_number(<?=$v['Product_ID']?>)"><i class="fas fa-plus"></i></button>
                                                                    </div>
                                                                </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="d-inline-block product_price_<?=$v['Product_ID']?>">
                                                                    <?=number_format($v['Product_Price'],0,',','.')?>đ    
                                                                </div>
                                                                <button class="btn btn-sm btn-danger cart_delete" title="Xóa" onclick="delete_item(<?=$v['Product_ID']?>)"><i class="fa fa-times"></i></button>
                                                            </td>
                                                            <input type="hidden" name="product_id[<?=$v['Product_ID']?>]" class="item_id" value="<?=$v['Product_ID']?>">
                                                            <input type="hidden" name="price[<?=$v['Product_ID']?>]" class="item_current_price" item-price-id="<?=$v['Product_ID']?>" value="<?=$v['Product_Price']?>">
                                                        </tr>    
                                                    <?php endforeach ?>
                                                        
                                                <?php endif ?>
                                                <tr>
                                                    <td class="text-left">
                                                        <a href="<?=$atz->site_url['main']?>" class="btn btn-primary"><< Mua thêm</button>
                                                    </td>
                                                    <td class="text-right" colspan="2">Tổng thàng tiền: <div class="d-inline total_price text-red"></div></td>
                                                    <input type="hidden" class="Invoice_Price" name="Invoice_Price">
                                                </tr>
                                            </tbody>
                                        </table>    
                                    </div>

                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="cart-contact p-2">

                                <p>NHẬP THÔNG TIN NHẬN HÀNG</p>
                                
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <?php if ($errors): ?>
                                            <label id="Invoice_Name-error" class="error" for="Invoice_Name"><?=isset($errors['Invoice_Name'])?$errors['Invoice_Name']:''?></label>
                                        <?php endif ?>
                                        <input type="text" class="form-control" name="Invoice_Name" placeholder="Họ tên *" value="<?=isset($post['Invoice_Name'])?$post['Invoice_Name']:''?>">
                                    </div>
                                    
                                    <div class="col-md-12 mb-3">
                                        <?php if ($errors): ?>
                                            <label id="Invoice_Email-error" class="error" for="Invoice_Email"><?=isset($errors['Invoice_Email'])?$errors['Invoice_Email']:''?></label>
                                        <?php endif ?>
                                        <input type="text" class="form-control" name="Invoice_Email" placeholder="Email" value="<?=isset($post['Invoice_Email'])?$post['Invoice_Email']:''?>">
                                    </div>
                                    
                                    <div class="col-md-12 mb-3">
                                        <?php if ($errors): ?>
                                            <label id="Invoice_Mobile-error" class="error" for="Invoice_Mobile"><?=isset($errors['Invoice_Mobile'])?$errors['Invoice_Mobile']:''?></label>
                                        <?php endif ?>
                                        <input type="text" class="form-control" name="Invoice_Mobile" placeholder="Điện thoại *" value="<?=isset($post['Invoice_Mobile'])?$post['Invoice_Mobile']:''?>">
                                    </div>
                                    
                                    <div class="col-md-12 mb-3">
                                        <?php if ($errors): ?>
                                            <label id="Invoice_Address-error" class="error" for="Invoice_Address"><?=isset($errors['Invoice_Address'])?$errors['Invoice_Address']:''?></label>
                                        <?php endif ?>
                                        <input type="text" class="form-control" name="Invoice_Address" placeholder="Địa Chỉ *" value="<?=isset($post['Invoice_Address'])?$post['Invoice_Address']:''?>">
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <?php if ($errors): ?>
                                            <label id="Invoice_Note-error" class="error" for="Invoice_Note"><?=isset($errors['Invoice_Note'])?$errors['Invoice_Note']:''?></label>
                                        <?php endif ?>
                                        <textarea class="form-control" name="Invoice_Note" placeholder="Ghi chú"><?=isset($post['Invoice_Note'])?$post['Invoice_Note']:''?></textarea><!-- /# -->
                                    </div>                                    
                                    <div class="col-md-12 text-left">
                                        <!-- <input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response"> -->
                                        <button type="submit" name="submit" class="btn btn-danger">Đặt hàng</button>
                                        <button type="submit" name="reset" class="btn btn-primary">Reset</button>
                                    </div>
                                </div>
                            </div>

                            </div>
                        </div>
                    </div>

                </div>

            </form>


        </div>

        <?php include('modules/footer.php') ?>

        <!-- MAIN JS -->
        <?php include('modules/js.php') ?>
        <!-- END MAIN JS -->

        <script>
            
            <?php if(isset($rs['success'])):?>
                alert('<?=$rs['success']['main']?>');
            <?php endif ?>
            
            total_price();

            function formatNumber(num){
                return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.")
            }

            function minus_number(id){

                var quanlity = parseInt($("input[quanlity-id="+id+"]").val());
                var price = parseInt($("input[quanlity-id="+id+"]").attr('data-price'));

                if(quanlity>0){
                    quanlity = quanlity-1;
                    $("input[quanlity-id="+id+"]").val(quanlity);    
                }
                
                // update item price
                $("input[item-price-id="+id+"]").val(quanlity*price);
                $('.product_price_'+id).html(formatNumber(quanlity*price)+'đ');

                total_price();
            }

            function plus_number(id){

                var quanlity = parseInt($("input[quanlity-id="+id+"]").val());
                var price = parseInt($("input[quanlity-id="+id+"]").attr('data-price'));

                quanlity = quanlity+1;
                $("input[quanlity-id="+id+"]").val(quanlity);

                // update item price
                $("input[item-price-id="+id+"]").val(quanlity*price);
                $('.product_price_'+id).html(formatNumber(quanlity*price)+'đ');

                total_price();            
            }

            function delete_item(id){
                $.ajax({
                    type: "POST",
                    url: 'cart.php',
                    data: {delete:id},
                    dataType: 'json',
                    success: function(data){
                        if(data==1){
                            $('.product_item_'+id).remove();
                            total_price();    
                        }
                    }
                });    
            }

            function total_price(){
                var total_price = 0;
                
                $('.item_current_price').each(function( i, obj ) {
                    total_price += parseInt(obj.value);
                });

                console.log(total_price);
                $('.Invoice_Price').val(total_price);
                $('.total_price').html(formatNumber(total_price)+'đ');
            }
            

            $(document).ready(function() {
                $('#12contact-form').validate({
                    errorPlacement: function(error, element) {
                        var place = element.closest('.input-group');
                        if (!place.get(0)) {
                            place = element;
                        }
                        if (place.get(0).type === 'checkbox') {
                            place = element.parent();
                        }
                        if (error.text() !== '') {
                            place.before(error);
                        }
                    },
                    rules: {
                        Invoice_Name: {
                            required: true
                        },
                        Invoice_Email: {
                            required: true,
                            email: true
                        },
                        Invoice_Mobile: {
                            // required: true,
                            number: true,
                            digits: true,
                            minlength: 10
                        },
                        Invoice_Note: {
                            required: true
                        },
                    },
                    messages: {
                        Invoice_Name: {
                            required: "Chưa nhập tên!"
                        },
                        Invoice_Email: {
                            required: "Chưa nhập Email!",
                            email: "Email không hợp lệ"
                        },
                        Invoice_Mobile: {
                            required: "Chưa nhập điện thoại!",
                            number: "Điện thoại phải là số!",
                            digits: "Điện thoại không được nhập số âm !",
                            minlength: "Điện thoại phải ít nhất có 10 số!"
                        },
                        Invoice_Note: {
                            required: "Chưa nhập lời nhắn!"
                        }
                    },
                    
                    submitHandler: function(form) {
                        
                        $.ajax({
                            url: "<?=$atz->site_url['full']?>", 
                            type: "POST",             
                            data: $(form).serialize(),
                            cache: false,             
                            processData: false,      
                            success: function(data) {
                                alert(data);
                            }
                        });
                        
                        return false; // required to block normal submit since you used ajax
                    }
                });
            });
        </script>
    </body>

</html>