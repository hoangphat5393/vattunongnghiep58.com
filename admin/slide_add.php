<?php require_once('slide_c/slide_c.php');?>
<?php 
	$atz = new slide_controller();

	$rs = $atz->add_slide();

	$post = $atz->post;

	$errors = array();

	if(isset($rs['errors'])){
		$errors = $rs['errors'];	
	}
?>

<!DOCTYPE html>
<html lang="en-us">
	<head>
		<title> Thêm slide</title>
		
		<?php include('module/head.php')?>

	</head>
	
	<!-- #BODY -->
	<body class="">

		<!-- #HEADER -->
		<header id="header">
			<?php include('module/header.php')?>
		</header>
		<!-- END HEADER -->

		<!-- #NAVIGATION -->
		<!-- Left panel : Navigation area -->
		<!-- Note: This width of the aside area can be adjusted through LESS variables -->
		<?php include('module/nav.php')?>
		<!-- END NAVIGATION -->

		<!-- MAIN PANEL -->
		<div id="main" role="main">

			<!-- RIBBON -->
			<div id="ribbon">

				<span class="ribbon-button-alignment"> 
					<span id="refresh" class="btn btn-ribbon" data-action="resetWidgets" data-title="refresh"  rel="tooltip" data-placement="bottom" data-original-title="<i class='text-warning fa fa-warning'></i> Warning! This will reset all your widget settings." data-html="true">
						<i class="fa fa-refresh"></i>
					</span> 
				</span>

				<!-- breadcrumb -->
				<ol class="breadcrumb">
					<li><a href="index.php">Admin</a></li>
					<li>Chuyên mục</li>
					<li>Thêm slide</li>
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
								Thêm slide
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
								<div class="jarviswidget" id="wid-id-5" data-widget-colorbutton="false"	data-widget-editbutton="false" data-widget-deletebutton="false" data-widget-sortable="false">
									
									<header>
										<h2>Thêm slide</h2>
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

											<form id="catForm" action="" method="post" enctype="multipart/form-data">

												<legend>Mời điền đầy đủ thông tin</legend>

												<fieldset>
													<div class="form-group <?=isset($errors['Slide_Title_vi'])?'has-error':''?>">
														<label for="Slide_Title_vi">Tiêu đề bài viết</label>
														<small class="help-block" data-bv-validator="notEmpty" data-bv-for="title" data-bv-result="INVALID" style=""><?=isset($errors['Slide_Title_vi'])?$errors['Slide_Title_vi']:''?></small>
														<input type="text" class="form-control" id="Slide_Title_vi" name="Slide_Title_vi" value="<?=$post['Slide_Title_vi']?>"/>
													</div>

													<div class="form-group <?=isset($errors['Slide_Img'])?'has-error':''?>">
														<label for="Slide_Img">Ảnh đại diện</label>
														<small class="help-block" data-bv-validator="notEmpty" data-bv-for="title" data-bv-result="INVALID" style=""><?=isset($errors['Slide_Img'])?$errors['Slide_Img']:''?></small>
														<?php if ($post['Slide_Img']): ?>
															<img src="<?=$post['Slide_Img']?>" alt="" width="200">
														<?php endif ?>
														<input type="file" class="form-control" id="Slide_Img" name="Slide_Img"/>
														<input type="hidden" class="form-control" name="Slide_Img" value="<?=$post['Slide_Img']?>" />
													</div>
													
													<!-- <div class="form-group <?=isset($errors['Slide_Description_vi'])?'has-error':''?>">
														<label for="Slide_Description_vi">Mô tả</label>
														<textarea rows="5" name="Slide_Description_vi" class="form-control"><?=$post['Slide_Description_vi']?>	</textarea> 
													</div> -->

													<div class="form-group <?=isset($errors['Slide_Show'])?'has-error':''?>">
														<label>Hiện</label>
														<small class="help-block" data-bv-validator="notEmpty" data-bv-for="title" data-bv-result="INVALID" style=""><?=isset($errors['Slide_Show'])?$errors['Slide_Show']:''?></small>
														<select name="Slide_Show" id="Slide_Show" class="form-control">
															<option value="0">Ẩn</option>
															<option value="1" <?=($post['Slide_Show']==1)?'selected':''?>>Hiện</option>
														</select>
														<i></i>
													</div>

													<div class="form-group">
														<label>Thứ tự</label>
														<input type="text" class="form-control" name="Slide_Order" value="<?=$post['Slide_Order']?>" />
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
		<?php include('module/footer.php')?>
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
		<?php include('module/js.php')?>

		<!-- PAGE RELATED PLUGIN(S)-->

		<script src="js/plugin/bootstrapvalidator/bootstrapValidator.min.js"></script>
		<script src="js/plugin/summernote/summernote.min.js"></script>

		<script type="text/javascript">

			$(document).ready(function() {
				
				pageSetUp();
				
				// input form

				$('.summernote').summernote({
					height: 500,
					toolbar: [
					    ['style', ['style']],
					    ['font', ['bold', 'italic', 'underline', 'clear']],
					    ['fontname', ['fontname']],
					    ['color', ['color']],
					    ['para', ['ul', 'ol', 'paragraph']],
					    ['height', ['height']],
					    ['table', ['table']],
					    ['insert', ['link', 'picture', 'hr']],
					    ['view', ['fullscreen', 'codeview', 'help']]
					],
					image: [
						['custom', ['imageAttributes']],
						['imagesize', ['resizeFull', 'resizeHalf', 'resizeQuarter', 'resizeNone']],
						['float', ['floatLeft', 'floatRight', 'floatNone']],
						['remove', ['removeMedia']]
					],
					callbacks: {
			            onPaste: function (e) {
			                var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
				            e.preventDefault();
				            document.execCommand('insertText', false, bufferText);
			            },
					    onImageUpload: function(files, editor, welEditable) {
						    var my_editor = $(this);
						    $.each(files, function(k, v){
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
				        url: "<?=$atz->site_url['full']?>",
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

	</body>

</html>