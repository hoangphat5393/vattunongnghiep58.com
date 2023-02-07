<?php require_once('product_c/product_c.php'); ?>
<?php

$atz = new product_controller();

$rs = $atz->add_product();


// Hàm upload ảnh đại diện (thumbnail)
$atz->ajax_upload_thumbnail();

// Hàm upload ảnh tạm trong danh sách ảnh
$atz->ajax_upload_image();

// Hàm upload ảnh tạm trong summernote
$atz->ajax_upload_image_editor();


$post = $atz->post;

$cats = $atz->get_cat();

$errors = array();

if (isset($rs['errors'])) {
	$errors = $rs['errors'];
}
?>

<!DOCTYPE html>
<html lang="en-us">

<head>
	<title> Thêm sản phẩm </title>

	<?php include('module/head.php') ?>

</head>

<!-- #BODY -->

<body class="">

	<!-- #HEADER -->
	<header id="header">
		<?php include('module/header.php') ?>
	</header>
	<!-- END HEADER -->

	<!-- #NAVIGATION -->
	<!-- Left panel : Navigation area -->
	<!-- Note: This width of the aside area can be adjusted through LESS variables -->
	<?php include('module/nav.php') ?>
	<!-- END NAVIGATION -->

	<!-- MAIN PANEL -->
	<div id="main" role="main">

		<!-- RIBBON -->
		<div id="ribbon">

			<span class="ribbon-button-alignment">
				<span id="refresh" class="btn btn-ribbon" data-action="resetWidgets" data-title="refresh" rel="tooltip" data-placement="bottom" data-original-title="<i class='text-warning fa fa-warning'></i> Warning! This will reset all your widget settings." data-html="true">
					<i class="fa fa-refresh"></i>
				</span>
			</span>

			<!-- breadcrumb -->
			<ol class="breadcrumb">
				<li><a href="index.php">Admin</a></li>
				<li>Chuyên mục</li>
				<li>Thêm sản phẩm</li>
			</ol>
			<!-- end breadcrumb -->

		</div>
		<!-- END RIBBON -->


		<!-- MAIN CONTENT -->
		<div id="content">

			<!-- row -->
			<div class="row">

				<!-- col -->
				<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
					<h1 class="page-title txt-color-blueDark">

						<!-- PAGE HEADER -->
						<i class="fa-fw fa fa-home"></i>
						Admin
						<span>>
							Thêm sản phẩm
						</span>
					</h1>
				</div>
				<!-- end col -->

			</div>
			<!-- end row -->

			<!--
					The ID "widget-grid" will start to initialize all widgets below 
					You do not need to use widgets if you dont want to. Simply remove 
					the <section></section> and you can use wells or panels instead 
					-->

			<!-- widget grid -->
			<section id="widget-grid" class="">

				<!-- row -->
				<div class="row">

					<!-- NEW WIDGET ROW START -->
					<div class="col-md-offset-2 col-sm-8">

						<!-- Widget ID (each widget will need unique ID)-->
						<div class="jarviswidget" id="wid-id-5" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-deletebutton="false" data-widget-sortable="false">

							<header>
								<h2>Thêm sản phẩm</h2>
							</header>

							<!-- widget div-->

							<div>
								<!-- widget edit box -->
								<div class="jarviswidget-editbox">
									<!-- This area used as dropdown edit box -->
									<input class="form-control" type="text">
								</div>
								<!-- end widget edit box -->

								<!-- widget content -->
								<div class="widget-body">

									<form id="main_form" action="" method="post" enctype="multipart/form-data">

										<legend>Mời điền đầy đủ thông tin</legend>

										<fieldset>
											<div class="form-group <?= isset($errors['Product_Name_vi']) ? 'has-error' : '' ?>">
												<label for="Product_Name_vi">Tên sản phẩm</label>
												<small class="help-block" data-bv-validator="notEmpty" data-bv-for="title" data-bv-result="INVALID" style=""><?= isset($errors['Product_Name_vi']) ? $errors['Product_Name_vi'] : '' ?></small>
												<input type="text" class="form-control" id="Product_Name_vi" name="Product_Name_vi" value="<?= $post['Product_Name_vi'] ?>" />
											</div>

											<div class="form-group <?= isset($errors['Product_Thumbnail']) ? 'has-error' : '' ?>">
												<label for="Product_Thumbnail">Ảnh đại diện</label>
												<small class="help-block" data-bv-validator="notEmpty" data-bv-for="title" data-bv-result="INVALID" style=""><?= isset($errors['Product_Thumbnail']) ? $errors['Product_Thumbnail'] : '' ?></small>
												<img id="thumbnail" src="<?= $post['Product_Thumbnail'] ?>" alt="" width="200">
												<!-- <input type="file" class="form-control" id="Product_Thumbnail" name="Product_Thumbnail"/> -->
												<input type="hidden" class="form-control" id="Product_Thumbnail" name="Product_Thumbnail" value="<?= $post['Product_Thumbnail'] ?>" />
											</div>

											<div class="form-group <?= isset($errors['Product_Imgs']) ? 'has-error' : '' ?>">
												<label for="Product_Imgs">Danh sách ảnh</label>
												<small class="help-block" data-bv-validator="notEmpty" data-bv-for="title" data-bv-result="INVALID" style=""><?= isset($errors['Product_Imgs']) ? $errors['Product_Imgs'] : '' ?></small>
												<div id="sortable_image" class="row block-content p-0 bg-body-dark">
													<?php if (!empty($post['Product_Imgs'])) : ?>
														<?php foreach ($post['Product_Imgs'] as $v) : ?>
															<?php if (filter_var($v, FILTER_VALIDATE_URL)) : ?>
																<div class="col-6 col-lg-4 mb-4">
																	<div class="options-container ui-sortable-handle p-0" data-category="image">
																		<img class="img-responsive options-item" src="<?= $v ?>">
																		<div class="options-overlay bg-black-75">
																			<div class="options-overlay-content">
																				<!-- <span data-toggle="tooltip">
			                                                                            	<a href="<?= $v ?>" class="badge badge-info img-lightbox">
			                                                                            		<i class="fa fa-eye"></i>
				                                                                            </a>
			                                                                            </span> -->
																				<span data-toggle="tooltip">
																					<a href="#" class="badge badge-success set_thumbnail" data-name="<?= $v ?>" data-src="<?= $v ?>">
																						<i class="fa fa-thumb-tack"></i>
																					</a>
																				</span>
																				<a href="<?= $v ?>" class="badge badge-danger delete_image"><i class="fa fa-times"></i></a>
																			</div>
																		</div>
																	</div>
																	<input type="hidden" class="Product_Imgs" name="Product_Imgs[]" value="<?= $v ?>">
																</div>
															<?php else : ?>
																<?php $img = $atz->site_url['upload'] . 'product/' . $v; ?>
																<div class="col-6 col-lg-4 mb-4">
																	<div class="options-container ui-sortable-handle p-0" data-category="image">
																		<img class="img-responsive options-item" src="<?= $atz->site_url['upload'] . 'product/' . $v ?>">
																		<div class="options-overlay bg-black-75">
																			<div class="options-overlay-content">
																				<span data-toggle="tooltip">
																					<a href="#" class="badge badge-success set_thumbnail" data-name="<?= $atz->site_url['upload'] . 'product/' . $v ?>" data-src="<?= $atz->site_url['upload'] . 'product/' . $v ?>">
																						<i class="fa fa-thumb-tack"></i>
																					</a>
																				</span>
																				<a href="<?= $atz->site_url['upload'] . 'product/' . $v ?>" class="badge badge-danger delete_image"><i class="fa fa-times"></i></a>
																			</div>
																		</div>
																	</div>
																	<input type="hidden" class="Product_Imgs" name="Product_Imgs[]" value="<?= $v ?>">
																</div>
															<?php endif ?>
														<?php endforeach ?>
													<?php endif ?>
												</div>
												<input type="file" class="form-control" id="img_files" name="img_files[]" multiple />
											</div>

											<div class="form-group <?= isset($errors['Product_Description_vi']) ? 'has-error' : '' ?>">
												<label for="Product_Description_vi">Mô tả</label>
												<small class="help-block" data-bv-validator="notEmpty" data-bv-for="title" data-bv-result="INVALID" style=""><?= isset($errors['Product_Description_vi']) ? $errors['Product_Description_vi'] : '' ?></small>
												<textarea rows="5" name="Product_Description_vi" class="form-control"><?= $post['Product_Description_vi'] ?>	</textarea>
											</div>

											<div class="form-group <?= isset($errors['Product_Content_vi']) ? 'has-error' : '' ?>">
												<label for="Product_Content_vi">Nội dung</label>
												<small class="help-block" data-bv-validator="notEmpty" data-bv-for="title" data-bv-result="INVALID"><?= isset($errors['Product_Content_vi']) ? $errors['Product_Content_vi'] : '' ?></small>
												<textarea name="Product_Content_vi" class="summernote"><?= $post['Product_Content_vi'] ?></textarea>
											</div>

											<div class="form-group <?= isset($errors['Product_PriceType']) ? 'has-error' : '' ?>">
												<label for="Product_SalePrice">Đơn vị tính</label>
												<small class="help-block" data-bv-validator="notEmpty" data-bv-for="title" data-bv-result="INVALID" style=""><?= isset($errors['Product_PriceType']) ? $errors['Product_PriceType'] : '' ?></small>
												<input type="text" id="Product_PriceType" class="form-control" name="Product_PriceType" value="<?= $post['Product_PriceType'] ?>" />
											</div>

											<div class="form-group <?= isset($errors['Product_Price']) ? 'has-error' : '' ?>">
												<label for="Product_Price">Giá</label>
												<small class="help-block" data-bv-validator="notEmpty" data-bv-for="title" data-bv-result="INVALID" style=""><?= isset($errors['Product_Price']) ? $errors['Product_Price'] : '' ?></small>
												<input type="text" id="Product_Price" class="form-control update-price numberonly" name="Product_Price" value="<?= $post['Product_Price'] ?>" />
											</div>

											<div class="form-group <?= isset($errors['Product_Discount']) ? 'has-error' : '' ?>">
												<label for="Product_Discount">Giá giảm</label>
												<div class="input-group select-group">
													<input type="text" id="Product_Discount" class="form-control update-price numberonly auto_numeric" name="Product_Discount" value="<?= $post['Product_Discount'] ?>" placeholder="0" />
													<div class="input-group-btn">
														<select id="Product_DiscountUnit" class="form-control update-price numberonly" name="Product_DiscountUnit" title="Đơn vị giảm giá">
															<option value="VNĐ" <?= $post['Product_DiscountUnit'] == 'VNĐ' ? 'selected' : '' ?>>VNĐ</option>
															<option value="%" <?= $post['Product_DiscountUnit'] == '%' ? 'selected' : '' ?>>%</option>
														</select>
													</div>
												</div>

												<!-- <div class="input-group">
														    <input type="text" id="Product_Discount" class="form-control update-price numberonly name="Product_Discount" value="<?= $post['Product_Discount'] ?>" placeholder="0"/>
														    <span class="input-group-addon">VNĐ</span>
														    <input type="hidden" id="Product_DiscountUnit" class="form-control" name="Product_DiscountUnit" value="<?= $post['Product_DiscountUnit'] ?>"/>
	  													</div> -->
											</div>

											<div class="form-group <?= isset($errors['Product_SalePrice']) ? 'has-error' : '' ?>">
												<label for="Product_SalePrice">Giá bán</label>
												<small class="help-block" data-bv-validator="notEmpty" data-bv-for="title" data-bv-result="INVALID" style=""><?= isset($errors['Product_SalePrice']) ? $errors['Product_SalePrice'] : '' ?></small>
												<input type="text" id="Product_SalePrice" class="form-control numberonly" name="Product_SalePrice" value="<?= $post['Product_SalePrice'] ?>" readonly />
											</div>


											<div class="form-group <?= isset($errors['Product_Keywords_vi']) ? 'has-error' : '' ?>">
												<label for="Product_Keywords_vi">Từ khóa (Cách nhau bằng dấu ",")</label>
												<small class="help-block" data-bv-validator="notEmpty" data-bv-for="title" data-bv-result="INVALID" style=""><?= isset($errors['Product_Keywords_vi']) ? $errors['Product_Keywords_vi'] : '' ?></small>
												<input type="text" id="Product_Keywords_vi" class="form-control" name="Product_Keywords_vi" value="<?= $post['Product_Keywords_vi'] ?>" placeholder="hạt giống trang nông, ngò gai" />
											</div>

											<div class="form-group">
												<label>Chuyên mục</label>
												<select name="Product_Cat" id="Product_Cat" class="form-control">
													<option value="">--- Chọn chuyên mục ---</option>
													<?php if (!empty($cats)) : ?>
														<?php foreach ($cats as $v) : ?>
															<option value="<?= $v['Cat_ID'] ?>" <?php if ($post['Product_Cat'] == $v['Cat_ID']) {
																									echo 'selected';
																								} ?>><?= $v['Cat_Name_vi'] ?></option>
														<?php endforeach ?>
													<?php endif ?>
												</select>
											</div>

											<div class="form-group <?= isset($errors['Product_Hot']) ? 'has-error' : '' ?>">
												<label>Hot</label>
												<small class="help-block" data-bv-validator="notEmpty" data-bv-for="title" data-bv-result="INVALID" style=""><?= isset($errors['Product_Hot']) ? $errors['Product_Hot'] : '' ?></small>
												<select name="Product_Hot" id="Product_Hot" class="form-control">
													<option value="0">---</option>
													<option value="1" <?= ($post['Product_Hot'] == 1) ? 'selected' : '' ?>>Hot</option>
												</select>
												<i></i>
											</div>

											<div class="form-group <?= isset($errors['Product_Show']) ? 'has-error' : '' ?>">
												<label>Hiện</label>
												<small class="help-block" data-bv-validator="notEmpty" data-bv-for="title" data-bv-result="INVALID" style=""><?= isset($errors['Product_Show']) ? $errors['Product_Show'] : '' ?></small>
												<select name="Product_Show" id="Product_Show" class="form-control">
													<option value="0">Ẩn</option>
													<option value="1" <?= ($post['Product_Show'] == 1) ? 'selected' : '' ?>>Hiện</option>
												</select>
												<i></i>
											</div>

											<div class="form-group">
												<label>Thứ tự</label>
												<input type="text" class="form-control" name="Product_Priority" value="<?= $post['Product_Priority'] ?>" />
											</div>
										</fieldset>

										<div class="form-actions">
											<button class="btn btn-default btn-lg" type="reset">
												<i class="fa fa-refresh"></i> Reset
											</button>
											<button class="btn btn-primary btn-lg" type="submit" name="submit">
												<i class="fa fa-save"></i> Lưu
											</button>
										</div>

									</form>

								</div>
								<!-- end widget content -->

							</div>
							<!-- end widget div -->

						</div>
						<!-- end widget -->


					</div>
					<!-- WIDGET ROW END -->

				</div>

				<!-- end row -->

			</section>
			<!-- end widget grid -->

		</div>
		<!-- END MAIN CONTENT -->

	</div>
	<!-- END MAIN PANEL -->

	<!-- PAGE FOOTER -->
	<?php include('module/footer.php') ?>
	<!-- END PAGE FOOTER -->

	<!-- SHORTCUT AREA : With large tiles (activated via clicking user name tag)
		Note: These tiles are completely responsive,
		you can add as many as you like
		-->
	<div id="shortcut">
		<ul>
			<li>
				<a href="inbox.html" class="jarvismetro-tile big-cubes bg-color-blue"> <span class="iconbox"> <i class="fa fa-envelope fa-4x"></i> <span>Mail <span class="label pull-right bg-color-darken">14</span></span> </span> </a>
			</li>
			<li>
				<a href="calendar.html" class="jarvismetro-tile big-cubes bg-color-orangeDark"> <span class="iconbox"> <i class="fa fa-calendar fa-4x"></i> <span>Calendar</span> </span> </a>
			</li>
			<li>
				<a href="gmap-xml.html" class="jarvismetro-tile big-cubes bg-color-purple"> <span class="iconbox"> <i class="fa fa-map-marker fa-4x"></i> <span>Maps</span> </span> </a>
			</li>
			<li>
				<a href="invoice.html" class="jarvismetro-tile big-cubes bg-color-blueDark"> <span class="iconbox"> <i class="fa fa-book fa-4x"></i> <span>Invoice <span class="label pull-right bg-color-darken">99</span></span> </span> </a>
			</li>
			<li>
				<a href="gallery.html" class="jarvismetro-tile big-cubes bg-color-greenLight"> <span class="iconbox"> <i class="fa fa-picture-o fa-4x"></i> <span>Gallery </span> </span> </a>
			</li>
			<li>
				<a href="profile.html" class="jarvismetro-tile big-cubes selected bg-color-pinkDark"> <span class="iconbox"> <i class="fa fa-user fa-4x"></i> <span>My Profile </span> </span> </a>
			</li>
		</ul>
	</div>
	<!-- END SHORTCUT AREA -->

	<!--================================================== -->

	<!-- MAIN JS -->
	<?php include('module/js.php') ?>

	<!-- PAGE RELATED PLUGIN(S)-->

	<script src="js/plugin/bootstrapvalidator/bootstrapValidator.min.js"></script>
	<script src="js/plugin/summernote/summernote.min.js"></script>

	<script type="text/javascript">
		$(document).ready(function() {

			pageSetUp();

			// input form

			$('.summernote').summernote({
				height: 500,
				lineHeights: ['0.2', '0.3', '0.4', '0.5', '0.6', '0.8', '1.0', '1.2', '1.4', '1.5', '1.6', '1.8', '2.0', '3.0'],
				fontSizes: ['8', '9', '10', '11', '12', '14', '16', '18', '24', '36'],
				toolbar: [
					['style', ['style']],
					['font', ['bold', 'italic', 'underline', 'clear']],
					['fontname', ['fontname']],
					['fontsize', ['fontsize']],
					['color', ['color']],
					['para', ['ul', 'ol', 'paragraph']],
					['height', ['height']],
					['table', ['table']],
					['insert', ['link', 'picture', 'video', 'hr']],
					['view', ['fullscreen', 'codeview', 'help']]
				],
				image: [
					['custom', ['imageAttributes']],
					['imagesize', ['resizeFull', 'resizeHalf', 'resizeQuarter', 'resizeNone']],
					['float', ['floatLeft', 'floatRight', 'floatNone']],
					['remove', ['removeMedia']]
				],
				callbacks: {
					onPaste: function(e) {
						var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
						e.preventDefault();
						document.execCommand('insertText', false, bufferText);
					},
					onImageUpload: function(files, editor, welEditable) {
						var my_editor = $(this);
						$.each(files, function(k, v) {
							sendFile(v, my_editor, welEditable);
						});
					}
				}
			});

			// Hàm upload summernote
			function sendFile(file, editor, welEditable) {
				data = new FormData();
				data.append("summernote", file);

				$.ajax({
					data: data,
					type: 'POST',
					xhr: function() {
						var myXhr = $.ajaxSettings.xhr();
						if (myXhr.upload) myXhr.upload.addEventListener('progress', progressHandlingFunction, false);
						return myXhr;
					},
					url: "<?= $atz->site_url['full'] ?>",
					cache: false,
					contentType: false,
					processData: false,
					success: function(url) {
						// var url = $.trim(url);
						editor.summernote('editor.insertImage', url);

						// if (isValidUrl(url)) {
						//     editor.summernote('editor.insertImage', url);
						// } else {
						//     alert(url);
						// }
					}
				});
			}


			// Tiến trình upload
			function progressHandlingFunction(e) {
				if (e.lengthComputable) {
					$('progress').attr({
						value: e.loaded,
						max: e.total
					});
					// reset progress on complete
					if (e.loaded == e.total) {
						$('progress').attr('value', '0.0');
					}
				}
			}

			// Kiểm tra url hợp lệ
			function isValidUrl(url) {
				var myVariable = url;
				if (/^(http|https|ftp):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/i.test(myVariable)) {
					return 1;
				} else {
					return 0;
				}
			}
		})
	</script>


	<!-- !Ajax upload nhiều ảnh -->
	<script>
		// Click set_thumbnail, tiến hành cài đặt dữ liệu cho cropper và show modal box lên
		$('#sortable_image').on('click', '.set_thumbnail', function() {
			// var src = $(this).parents('.ui-sortable-handle').find('img').attr('src');
			var src = $(this).attr('data-name');

			console.log(src);
			$('#image').attr('src', src);
			// $('#modal_cropper').modal('show');

			$.post("<?= $atz->site_url['full'] ?>", {
				'image_src': src
			}, function(data) {
				if (!data) { // Có lỗi
					alert('Lỗi xảy ra, xin tải lại trang và upload lại');
				} else { // Thành công
					$('#thumbnail').attr('src', data); // Đổi ảnh thumbnail hiển thị

					$('#Product_Thumbnail').val(data); // Cài đặt giá trị cho input ẩn
				}
				// $('#page-loader').removeClass('show'); // Bỏ trạng thái loading
			});
		});

		// Click delete_image, xóa element
		$('#sortable_image').on('click', '.delete_image', function() {
			$(this).parents('.col-6.col-lg-4.mb-4').remove();
			$('.tooltip').remove();
			return false;
		});

		$('#img_files').on('change', function() { // Chọn ảnh
			// $('#page-loader').addClass('show'); // Hiện preload

			// Tạo form
			var form_data = new FormData();
			$.each($(this)[0].files, function(k, v) {
				form_data.append('ajax_images_upload[]', v);
			});

			// Xóa input chọn files
			$('#img_files').val('');

			// Ajax
			$.ajax({
				url: "<?= $atz->site_url['full'] ?>",
				type: 'post',
				data: form_data,
				dataType: 'json',
				contentType: false,
				processData: false,
				success: function(response) {
					if (response.status == 0) { // Có lỗi
						alert(response.message); // In lỗi ra
					} else if (!response.file_name.length) { // Thành công
						alert('Không thể tải ảnh lên, xin hãy thử lại.');
					} else {
						$.each(response.file_name, function(k, v) {

							var img_src = '<?= $atz->site_url['upload'] . 'tmp/' ?>' + v;

							$('#sortable_image').append('\
		                        						<div class="col-6 col-lg-4 mb-4">\
		                        							<div class="options-container ui-sortable-handle p-0" data-category="image">\
		                        								<img class="img-responsive options-item"src' + '="' + img_src + '">\
		                        								<div class="options-overlay bg-black-75">\
                                                                    <div class="options-overlay-content">\
                                                                        <span data-toggle="tooltip">\
                                                                        	<a href="#" class="badge badge-success set_thumbnail" data-name="' + img_src + '" data-src="' + img_src + '">\
                                                                        		<i class="fa fa-thumb-tack"></i>\
                                                                        	</a>\
                                                                    	</span>\
                                                                        <a href="' + img_src + '" class="badge badge-danger delete_image"><i class="fa fa-times"></i></a>\
                                                                    </div>\
                                                                </div>	\
		                        							</div>\
		                        							<input type="hidden" class="Product_Imgs" name="Product_Imgs[]" value="' + img_src + '">\
		                        						</div>');
						});
					}
				}
			});
		});
	</script>

	<!-- INPUT NUMBER ONLY -->
	<script>
		$(document).ready(function() {
			$('.numberonly').keypress(function(e) {
				var charCode = (e.which) ? e.which : event.keyCode
				if (String.fromCharCode(charCode).match(/[^0-9]/g))
					return false;
			});
		});
	</script>

	<!-- !Tính giảm giá -->
	<script>
		$(function() {
			price_discount();
			// currency();
		});

		$('.update-price').on('input', function() {
			price_discount();
		});

		function price_discount() {

			// Input giảm giá
			// if ($(".input-discount-1").hasClass("d-none")) {
			// 	var product_discount = $(".input-discount-2").val();
			// }else if ($(".input-discount-2").hasClass("d-none")) {
			// 	var product_discount = $(".input-discount-1").val();
			// }

			// Input giá
			var product_price = $('#Product_Price').val();

			// Input giá giảm
			var product_discount = $('#Product_Discount').val();

			// Đơn vị giảm giá
			var product_discount_unit = $('#Product_DiscountUnit').val();

			// Tổng
			var total = 0;

			if (product_price <= 0) {
				$('#Product_Price').val(0);
			}

			// console.log(product_price);
			// console.log(product_discount);
			// console.log(product_discount_unit);

			if (product_discount_unit == '%') { // Nếu là %

				total = product_price * ((100 - product_discount) / 100);

				console.log(product_discount_unit);

			} else if (product_discount_unit == 'VNĐ') { // Nếu là Đơn vị

				// Đổi lại input
				// $('.input-discount-2').addClass('d-none');
				// $('.input-discount-2').removeClass('d-block');
				// $('.input-discount-2').prop('disabled', true);

				// $('.input-discount-1').removeClass('d-none');
				// $('.input-discount-1').addClass('d-block');
				// $('.input-discount-1').prop('disabled', false);

				// if(parseInt(product_price)<parseInt(product_discount)){

				// 	if ($(".input-discount-1").hasClass("d-none")) {
				// 		var product_discount = $(".input-discount-2").val(product_price);
				// 	}else if ($(".input-discount-2").hasClass("d-none")) {
				// 		var product_discount = $(".input-discount-1").val(product_price);
				// 	}
				// 	product_discount = product_price;
				// }

				// $('#Product_DiscountUnit').val('VNĐ');
				total = product_price - product_discount;

				console.log(total);
			}
			$('#Product_SalePrice').val(total);
			// currency();
		}
	</script>

	<!-- !kí hiệu tiền tệ -->
	<script>
		// function currency(){
		// 	var unit = $('#Product_DiscountUnit').val();
		// 	var currency = $('#Product_DiscountUnit').find('option[value="'+unit+'"]');
		// 	$('.currency').text(currency.attr("data-currency"));
		// }
	</script>


</body>

</html>