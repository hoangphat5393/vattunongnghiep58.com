<?php require_once('cat_c/cat_c.php');?>

<?php 
	$atz = new cat_controller();

	$rs = $atz->add_cat();

	$post = $atz->post;

	$cat_type = $atz->cat_type;
	
	$list_parent_cats = $atz->get_parent_cats();

	$errors = array();
	if(isset($rs['errors'])){
		$errors = $rs['errors'];	
	}
	
	$cat_parent = '';
	$check_child = '';

	if(isset($_GET['edit'])){
		$cat_parent = $atz->get_parent($post['Cat_Parent']);
		$check_child = $atz->get_child($_GET['edit']);
	}

	// echo '<pre>';
	// print_r($cat_parent);
	// print_r($check_child);
	// die;
		
?>

<!DOCTYPE html>
<html lang="en-us">
	<head>
		<title> Thêm chuyên mục </title>
		
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
					<li>Thêm huyên mục</li>
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
								Thêm chuyên mục
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
							<div class="col-md-offset-3 col-sm-6">
						
								<!-- Widget ID (each widget will need unique ID)-->
								<div class="jarviswidget" id="wid-id-5" data-widget-colorbutton="false"	data-widget-editbutton="false" data-widget-deletebutton="false" data-widget-sortable="false">
									
									<header>
										<h2>Thêm chuyên mục </h2>
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

											<form id="catForm" action="" method="post">

												<fieldset>
													<legend>
														Mời điền đầy đủ thông tin
													</legend>
													<div class="form-group <?=isset($errors['Cat_Name_vi'])?'has-error':''?>">
														<label>Tên chuyên mục</label>
														<small class="help-block" data-bv-validator="notEmpty" data-bv-for="title" data-bv-result="INVALID" style=""><?=isset($errors['Cat_Name_vi'])?$errors['Cat_Name_vi']:''?></small>
														<input type="text" class="form-control" name="Cat_Name_vi" value="<?=$post['Cat_Name_vi']?>"/>
													</div>
												</fieldset>
												<fieldset>
													<div class="form-group <?=isset($errors['Cat_Type'])?'has-error':''?>">
														<label>Loại dữ liệu</label>
														<small class="help-block" data-bv-validator="notEmpty" data-bv-for="title" data-bv-result="INVALID" style=""><?=isset($errors['Cat_Type'])?$errors['Cat_Type']:''?></small>
														<select name="Cat_Type" id="Cat_Type" class="form-control">
															<?php foreach ($cat_type as $k => $v): ?>
																<option value="<?=$k?>" <?php echo ($post['Cat_Type']==$k)?'selected':'' ?>><?=$v?></option>	
															<?php endforeach ?>
														</select>
													</div>
												</fieldset>

												<fieldset>
													<div class="form-group <?=isset($errors['Cat_Parent'])?'has-error':''?>">
														<label>Chuyên mục cha</label>
														<small class="help-block" data-bv-validator="notEmpty" data-bv-for="title" data-bv-result="INVALID" style=""><?=isset($errors['Cat_Parent'])?$errors['Cat_Parent']:''?></small>
														
														<!-- Thêm mới -->
														<?php if (!isset($_GET['edit'])): ?>
															
															<select name="Cat_Parent" id="Cat_Parent" class="form-control">
																<option value="0">--- Chọn chuyên mục phụ thuộc ---</option>
																<!-- LV 0 -->
																<?php if (!empty($list_parent_cats)): ?>
																	<?php foreach ($list_parent_cats as $k => $v): ?>
																		<option value="<?=$v['Cat_ID']?>" <?=($post['Cat_Parent']==$v['Cat_ID'])?'selected':'' ?>>--- <?=$v['Cat_Name_vi']?></option>
																		
																		<!-- LV 1 -->
																		<?php if (!empty($v['Cat_Child'])): ?>
																			<?php foreach ($v['Cat_Child'] as $v1): ?>
																				<option value="<?=$v1['Cat_ID']?>" <?=($post['Cat_Parent']==$v1['Cat_ID'])?'selected':'' ?>>------ <?=$v1['Cat_Name_vi']?></option>
																			<?php endforeach ?>
																		<?php endif ?>
																		<!-- END LV 1 -->
																	<?php endforeach ?>	
																<?php endif ?>
																<!-- END LV 0 -->
															</select>

														<!-- Sửa -->
														<?php else: ?>

															<select name="Cat_Parent" id="Cat_Parent" class="form-control">
																<option value="0">--- Chọn chuyên mục phụ thuộc ---</option>
																<!-- LV 0 -->
																<?php if (!empty($list_parent_cats)): ?>
																	<?php foreach ($list_parent_cats as $k => $v): ?>
																	
																		<?php if ($post['Cat_Parent']==0): ?>
																			<?php if (empty($check_child)): ?>
																				<option value="<?=$v['Cat_ID']?>" <?=($post['Cat_Parent']==$v['Cat_ID'])?'selected':'' ?>>--- <?=$v['Cat_Name_vi']?></option>
																				<!-- LV 1 -->
																				<?php if (!empty($v['Cat_Child'])): ?>
																					<?php foreach ($v['Cat_Child'] as $v1): ?>
																						<option value="<?=$v1['Cat_ID']?>" <?=($post['Cat_Parent']==$v1['Cat_ID'])?'selected':'' ?>>------ <?=$v1['Cat_Name_vi']?></option>
																					<?php endforeach ?>
																				<?php endif ?>
																				<!-- END LV 1 -->
																			<?php endif ?>

																		<?php else: ?>

																			<?php if (!empty($cat_parent)): ?>
																				<option value="<?=$v['Cat_ID']?>" <?=($post['Cat_Parent']==$v['Cat_ID'])?'selected':'' ?>>--- <?=$v['Cat_Name_vi']?></option>

																				<?php if (empty($check_child)): ?>
																					<!-- LV 1 -->
																					<?php if (!empty($v['Cat_Child'])): ?>
																						<?php foreach ($v['Cat_Child'] as $v1): ?>
																							<option value="<?=$v1['Cat_ID']?>" <?=($post['Cat_Parent']==$v1['Cat_ID'])?'selected':'' ?>>------ <?=$v1['Cat_Name_vi']?></option>
																						<?php endforeach ?>
																					<?php endif ?>
																					<!-- END LV 1 -->
																				<?php endif ?>

																			<?php endif ?>

																		<?php endif ?>

																	<?php endforeach ?>	
																<?php endif ?>
																<!-- END LV 0 -->
															</select>
														<?php endif ?>
														
													</div>
												</fieldset>

												<fieldset>
													<div class="form-group <?=isset($errors['Cat_Description_vi'])?'has-error':''?>">
														<label for="Cat_Description_vi">Mô tả</label>
														<textarea rows="5" name="Cat_Description_vi" class="form-control"><?=$post['Cat_Description_vi']?>	</textarea> 
													</div>
												</fieldset>

												<fieldset>
													<div class="form-group <?=isset($errors['Cat_Keywords_vi'])?'has-error':''?>">
														<label for="Cat_Keywords_vi">Từ khóa (Cách nhau bằng dấu ",")</label>
														<input type="text" class="form-control" name="Cat_Keywords_vi" placeholder="Hạt giống, Nông nghiệp" value="<?=$post['Cat_Keywords_vi']?>"/>
													</div>
												</fieldset>

												<fieldset>
													<div class="form-group <?=isset($errors['Cat_Order'])?'has-error':''?>">
														<label for="Cat_Order">Thứ tự</label>
														<small class="help-block" data-bv-validator="notEmpty" data-bv-for="title" data-bv-result="INVALID" style=""><?=isset($errors['Cat_Order'])?$errors['Cat_Order']:''?></small>
														<input type="text" class="form-control" name="Cat_Order" value="<?=$post['Cat_Order']?>" />
													</div>
												</fieldset>

												<fieldset>
													<div class="form-group <?=isset($errors['Cat_Show'])?'has-error':''?>">
														<label for="Cat_Show">Vị trí</label>
														<small class="help-block" data-bv-validator="notEmpty" data-bv-for="title" data-bv-result="INVALID" style=""><?=isset($errors['Cat_Show'])?$errors['Cat_Show']:''?></small>


														<?php 
															if (is_array($post['Cat_Show'])) {
																$post['Cat_Show'] = implode(',', $post['Cat_Show']);
															}	
														?>
														<select multiple name="Cat_Show[]" id="Cat_Show" class="form-control select2">
															<optgroup label="Chọn vị trí chuyên mục xuất hiện">
																<option value="main_menu" <?=strpos($post['Cat_Show'], 'main_menu')!==false?'selected':''?>>Menu chính</option>
																<option value="left_menu" <?=strpos($post['Cat_Show'], 'left_menu')!==false?'selected':''?>>Menu trái</option>
															</optgroup>
														</select>
													</div>
												</fieldset>

												<fieldset>
													<div class="form-group">
														<label>Trạng thái</label>
														<select name="Cat_Status" id="Cat_Status" class="form-control">
															<option value="0">Không hoạt động</option>
															<option value="1" <?=($post['Cat_Status']==1)?'selected':''?>>Hoạt động</option>
														</select>
													</div>
												</fieldset>

												<fieldset>
													<div class="form-group">
														<label>Hiển thị sản phẩm của mục này trên trang chủ</label>
														<select name="Cat_Hot" id="Cat_Hot" class="form-control">
															<option value="0">Ẩn</option>
															<option value="1" <?=($post['Cat_Hot']==1)?'selected':''?>>Hiện</option>
														</select>
													</div>
												</fieldset>

												<div class="form-actions">
													<div class="row">
														<div class="col-md-12">
															<button class="btn btn-default" type="submit" name="submit">
																<i class="fa fa-save"></i> Lưu
															</button>
														</div>
													</div>
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

		<!-- JQUERY SELECT2 INPUT -->
		<script src="js/plugin/select2/select2.min.js"></script>

		<script type="text/javascript">

			$(document).ready(function() {
				
				pageSetUp();
				
				// cat form

				// $('#catForm').bootstrapValidator({
				// 	feedbackIcons : {
				// 		valid : 'glyphicon glyphicon-ok',
				// 		invalid : 'glyphicon glyphicon-remove',
				// 		validating : 'glyphicon glyphicon-refresh'
				// 	},
				// 	fields : {
				// 		Cat_Name : {
				// 			validators : {
				// 				notEmpty : {
				// 					message : 'Trường này không được để trống'
				// 				}
				// 			}
				// 		},
				// 		Cat_Order : {
				// 			validators : {
				// 				digits : {
				// 					message : 'Chỉ được nhập số'
				// 				}
				// 			}
				// 		}
				// 	},	
				// });
			})
		
		</script>

		<!-- Your GOOGLE ANALYTICS CODE Below -->
		<?php //include('module/google_analytics.php')?>

	</body>

</html>