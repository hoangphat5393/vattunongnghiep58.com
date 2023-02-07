<?php require_once('user_c/user_c.php');require_once('country/CountryCodes.php');?>
<?php 
	$atz = new user_controller();

	$user_gender = $atz->user_gender;

	$user_permission = $atz->user_permission;

	$rs = $atz->add_user();

	// Hàm upload ảnh đại diện (thumbnail)
	$atz->ajax_upload_thumbnail();

	$post = $atz->post;

	$errors = array();
	if(isset($rs['errors'])){
		$errors = $rs['errors'];	
	}
	$country_list = countries_list();

	// echo '<pre>';
	// print_r($post);
	// exit;
?>

<!DOCTYPE html>
<html lang="en-us">
	<head>
		<title> Thêm dự án </title>
		
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
					<li>Thêm dự án</li>
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
								Admin <span>> Thêm dự án</span>
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
										<h2>Thêm dự án</h2>
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

													<div class="form-group <?=isset($errors['User_Avatar'])?'has-error':''?>">
														<label for="User_Avatar">Ảnh đại diện</label>
														<small class="help-block" data-bv-validator="notEmpty" data-bv-for="title" data-bv-result="INVALID" style=""><?=isset($errors['User_Avatar'])?$errors['User_Avatar']:''?></small>
														<?php if ($post['User_Avatar']): ?>
															<img src="<?=$post['User_Avatar']?>" alt="" width="200">
														<?php endif ?>
														<input type="file" class="form-control" id="User_Avatar" name="User_Avatar"/>
														<input type="hidden" class="form-control" name="User_Avatar" value="<?=$post['User_Avatar']?>" />
													</div>

													<div class="form-group <?=isset($errors['User_NickName'])?'has-error':''?>">
														<label for="User_NickName">NickName</label>
														<small class="help-block" data-bv-validator="notEmpty" data-bv-for="title" data-bv-result="INVALID" style=""><?=isset($errors['User_NickName'])?$errors['User_NickName']:''?></small>
														<input type="text" class="form-control" id="User_NickName" name="User_NickName" value="<?=$post['User_NickName']?>"/>
													</div>

													<div class="form-group <?=isset($errors['User_LastName'])?'has-error':''?>">
														<label for="User_LastName">Họ</label>
														<small class="help-block" data-bv-validator="notEmpty" data-bv-for="title" data-bv-result="INVALID" style=""><?=isset($errors['User_LastName'])?$errors['User_LastName']:''?></small>
														<input type="text" class="form-control" id="User_LastName" name="User_LastName" value="<?=$post['User_LastName']?>"/>
													</div>

													<div class="form-group <?=isset($errors['User_FirstName'])?'has-error':''?>">
														<label for="User_FirstName">Tên</label>
														<small class="help-block" data-bv-validator="notEmpty" data-bv-for="title" data-bv-result="INVALID" style=""><?=isset($errors['User_FirstName'])?$errors['User_FirstName']:''?></small>
														<input type="text" class="form-control" id="User_FirstName" name="User_FirstName" value="<?=$post['User_FirstName']?>"/>
													</div>

													<div class="form-group <?=isset($errors['User_Gender'])?'has-error':''?>">
														<label for="User_Gender">Giới tính</label>
														<small class="help-block" data-bv-validator="notEmpty" data-bv-for="title" data-bv-result="INVALID" style=""><?=isset($errors['User_Type'])?$errors['User_Type']:''?></small>
														<select name="User_Gender" id="User_Gender" class="form-control">
															<?php foreach ($user_gender as $k=>$v): ?>
																<option value="<?=$k?>" <?=($post['User_Gender']==$k)?'selected':''?>><?=$v?></option>
															<?php endforeach ?>
														</select>
													</div>
													
													<div class="form-group <?=isset($errors['User_Birthday'])?'has-error':''?>">
														<label for="User_Birthday">Sinh nhật</label>
														<small class="help-block" data-bv-validator="notEmpty" data-bv-for="title" data-bv-result="INVALID" style=""><?=isset($errors['User_Birthday'])?$errors['User_Birthday']:''?></small>
														<div class="input-group">
															<input type="text" name="User_Birthday" id="User_Birthday" class="form-control datepicker" data-dateformat="dd-mm-yy" value="<?=$post['User_Birthday']?>">
															<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
														</div>
													</div>

													<div class="form-group <?=isset($errors['User_Mobile'])?'has-error':''?>">
														<label for="User_Mobile">Số điện thoại</label>
														<small class="help-block" data-bv-validator="notEmpty" data-bv-for="title" data-bv-result="INVALID" style=""><?=isset($errors['User_Mobile'])?$errors['User_Mobile']:''?></small>
														<input type="text" class="form-control" id="User_Mobile" name="User_Mobile" value="<?=$post['User_Mobile']?>"/>
													</div>

													<div class="form-group <?=isset($errors['User_Email'])?'has-error':''?>">
														<label for="User_Email">Email</label>
														<small class="help-block" data-bv-validator="notEmpty" data-bv-for="title" data-bv-result="INVALID" style=""><?=isset($errors['User_Email'])?$errors['User_Email']:''?></small>
														<input type="text" class="form-control" id="User_Email" name="User_Email" value="<?=$post['User_Email']?>"/>
													</div>

													<div class="form-group <?=isset($errors['User_Zipcode'])?'has-error':''?>">
														<label for="User_Zipcode">Mã bưu chính | CCCD</label>
														<small class="help-block" data-bv-validator="notEmpty" data-bv-for="title" data-bv-result="INVALID" style=""><?=isset($errors['User_Zipcode'])?$errors['User_Zipcode']:''?></small>
														<input type="text" class="form-control" id="User_Zipcode" name="User_Zipcode" value="<?=$post['User_Zipcode']?>"/>
													</div>

													<div class="form-group <?=isset($errors['User_Address'])?'has-error':''?>">
														<label for="User_Address">Địa chỉ</label>
														<small class="help-block" data-bv-validator="notEmpty" data-bv-for="title" data-bv-result="INVALID" style=""><?=isset($errors['User_Address'])?$errors['User_Address']:''?></small>
														<input type="text" class="form-control" id="User_Address" name="User_Address" value="<?=$post['User_Address']?>"/>
													</div>

													<div class="form-group <?=isset($errors['User_Country'])?'has-error':''?>">
														<label for="User_Country">Quốc tịch</label>
														<small class="help-block" data-bv-validator="notEmpty" data-bv-for="title" data-bv-result="INVALID" style=""><?=isset($errors['User_Country'])?$errors['User_Country']:''?></small>
														<select name="User_Country" id="User_Show" class="form-control">
															<option value="0">Chọn quốc tịch </option>
															<?php foreach($country_list as $k=>$v):?>
																<option value="<?=$k?>" <?=($post['User_Country']==$k)?'selected':''?>><?=$v['name']?></option>
															<?php endforeach?>
														</select>
													</div>

													<div class="form-group <?=isset($errors['User_Registered'])?'has-error':''?>">
														<label for="User_Registered">Ngày đăng ký</label>
														<small class="help-block" data-bv-validator="notEmpty" data-bv-for="title" data-bv-result="INVALID" style=""><?=isset($errors['User_Registered'])?$errors['User_Registered']:''?></small>
														<div class="input-group">
															<input type="text" name="User_Registered" id="User_Registered" class="form-control datepicker" data-dateformat="dd-mm-yy" value="<?=date('d-m-Y',strtotime($post['User_Registered']))?>">
															<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
														</div>
													</div>
													<?php if($_SESSION['user']['User_RootAdmin']<3):?>
														<div class="form-group <?=isset($errors['User_RootAdmin'])?'has-error':''?>">
															<label>Phân quyền</label>
															<small class="help-block" data-bv-validator="notEmpty" data-bv-for="title" data-bv-result="INVALID" style=""><?=isset($errors['User_RootAdmin'])?$errors['User_RootAdmin']:''?></small>
															<select name="User_RootAdmin" id="User_RootAdmin" class="form-control">
																<?php foreach ($user_permission as $k=>$v): ?>
																	<?php if($k >= $_SESSION['user']['User_RootAdmin']):?>
																		<option value="<?=$k?>" <?=($post['User_RootAdmin']==$k)?'selected':''?>><?=$v?></option>
																	<?php endif ?>
																<?php endforeach ?>
															</select>
														</div>
													<?php endif?>

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
	</body>

</html>