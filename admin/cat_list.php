<?php require_once('cat_c/cat_c.php');?>

<?php 
	$atz = new cat_controller();
	$list_cats = $atz->get_cats();

	$del_cats = $atz->remove_cat();

	// Các chuyên mục ko được xóa
	$not_remove = $atz->cat_not_delete;
?>


<!DOCTYPE html>
<html lang="en-us">
	<head>
		<title> Danh sách chuyên mục </title>		
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
					<li>Admin</li>
					<li>Chuyên mục</li>
					<li>Danh sách chuyên mục</li>
				</ol>
				<!-- end breadcrumb -->

			</div>
			<!-- END RIBBON -->

			<!-- MAIN CONTENT -->
			<div id="content">
				<div class="row">
					<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
						<h1 class="page-title txt-color-blueDark">
							<i class="fa fa-table fa-fw "></i> 
								Admin 
							<span>> 
								Danh sách chuyên mục
							</span>
						</h1>
					</div>
				</div>

				<!-- widget grid -->
				<section id="widget-grid" class="">

					<!-- row -->
					<div class="row">

						<!-- NEW WIDGET START -->
						<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

							<!-- Widget ID (each widget will need unique ID)-->
							<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-0" data-widget-editbutton="false">
								
								<header>
									<span class="widget-icon"> <i class="fa fa-table"></i> </span>
									<h2>Danh sách chuyên mục</h2>
								</header>

								<!-- widget div-->
								<div>

									<!-- widget edit box -->
									<div class="jarviswidget-editbox">
										<!-- This area used as dropdown edit box -->

									</div>
									<!-- end widget edit box -->

									<!-- widget content -->
									<div class="widget-body">
										
										<a href="cat_add.php" class="btn btn-labeled btn-primary"> 
											<span class="btn-label"><i class="fa fa-plus-circle"></i></span>Thêm
										</a>
										<div class="table-responsive">
										
											<table class="table table-bordered table-striped table-hover">
												<thead>
													<tr>
														<th class="text-center">ID</th>
														<th>Tên</th>
														<th>Loại</th>
														<th>Thứ tự</th>
														<th class="text-center">Trạng thái</th>
														<th class="text-center">Thao tác</th>
													</tr>
												</thead>
												<tbody>
													<?php if (!empty($list_cats)): ?>
														<?php foreach ($list_cats as $k => $v):?>
															
															<tr>
																<td class="text-center"><?=$v['Cat_ID']?></td><?php  ?>
																<td><?=$v['Cat_Name_vi']?></td>
																<td>
																	<?php 
																		switch ($v['Cat_Type']) {
																			case 'product':
																				echo 'Sản phẩm';
																				break;
																			case 'post':
																				echo 'Bài Viết';
																				break;
																			case 'photo':
																				echo 'Hình ảnh';
																				break;
																			case 'search':
																				echo 'Tìm kiếm';
																				break;
																		} 
																	?>
																</td>
																<td><?=$v['Cat_Order']?></td>
																<td class="text-center">
																	<?php if ($v['Cat_Status']): ?>
																		Hoạt động
																	<?php else: ?>
																		Không hoạt động
																	<?php endif ?>
																</td>
																<td class="text-center">
																	<a class="btn btn-sm btn-primary" href="cat_add.php?edit=<?=$v['Cat_ID']?>"><i class="fa fa-pencil-square-o"></i></a>
																	<?php if (!in_array($v['Cat_ID'], $not_remove)): ?>
																		<a class="btn btn-sm btn-danger" href="cat_list.php?delete=<?=$v['Cat_ID']?>"><i class="fa fa-times"></i></a>
																	<?php endif ?>
																</td>
															</tr>

															<?php if (!empty($v['Cat_Child'])): ?>
																<?php foreach ($v['Cat_Child'] as $v1): ?>
																	<tr>
																		<td class="text-center"><?=$v1['Cat_ID']?></td><?php  ?>
																		<td>----- <?=$v1['Cat_Name_vi']?></td>
																		<td>
																			<?php 
																				switch ($v1['Cat_Type']) {
																					case 'product':
																						echo 'Sản phẩm';
																						break;
																					case 'post':
																						echo 'Bài Viết';
																						break;
																					case 'photo':
																						echo 'Hình ảnh';
																						break;
																					case 'search':
																						echo 'Tìm kiếm';
																						break;
																				} 
																			?>
																		</td>
																		<td><?=$v1['Cat_Order']?></td>
																		<td class="text-center">
																			<?php if ($v1['Cat_Status']): ?>
																				Hoạt động
																			<?php else: ?>
																				Không hoạt động
																			<?php endif ?>
																		</td>
																		<td class="text-center">
																			<a class="btn btn-sm btn-primary" href="cat_add.php?edit=<?=$v1['Cat_ID']?>"><i class="fa fa-pencil-square-o"></i></a>
																			<?php if (!in_array($v1['Cat_ID'], $not_remove)): ?>
																				<a class="btn btn-sm btn-danger" href="cat_list.php?delete=<?=$v1['Cat_ID']?>"><i class="fa fa-times"></i></a>
																			<?php endif ?>
																		</td>
																	</tr>

																	<?php if (!empty($v1['Cat_Child'])): ?>
																		<?php foreach ($v1['Cat_Child'] as $v2): ?>
																			<tr>
																				<td class="text-center"><?=$v2['Cat_ID']?></td><?php  ?>
																				<td>----- ----- <?=$v2['Cat_Name_vi']?></td>
																				<td>
																					<?php 
																						switch ($v2['Cat_Type']) {
																							case 'product':
																								echo 'Sản phẩm';
																								break;
																							case 'post':
																								echo 'Bài Viết';
																								break;
																							case 'photo':
																								echo 'Hình ảnh';
																								break;
																							case 'search':
																								echo 'Tìm kiếm';
																								break;
																						} 
																					?>
																				</td>
																				<td><?=$v2['Cat_Order']?></td>
																				<td class="text-center">
																					<?php if ($v['Cat_Status']): ?>
																						Hoạt động
																					<?php else: ?>
																						Không hoạt động
																					<?php endif ?>
																				</td>
																				<td class="text-center">
																					<a class="btn btn-sm btn-primary" href="cat_add.php?edit=<?=$v2['Cat_ID']?>"><i class="fa fa-pencil-square-o"></i></a>
																					<?php if (!in_array($v2['Cat_ID'], $not_remove)): ?>
																						<a class="btn btn-sm btn-danger" href="cat_list.php?delete=<?=$v2['Cat_ID']?>"><i class="fa fa-times"></i></a>
																					<?php endif ?>
																				</td>
																			</tr>			
																		<?php endforeach ?>		
																	<?php endif ?>				
																<?php endforeach ?>		
															<?php endif ?>	
														<?php endforeach ?>
													<?php else:?>
														<tr>
															<td colspan="5" class="text-center">Chưa có dữ liệu</td>
														</tr>	
													<?php endif ?>
													
												</tbody>
											</table>
											
										</div>
									</div>
									<!-- end widget content -->

								</div>
								<!-- end widget div -->

							</div>
							<!-- end widget -->

						</article>
						<!-- WIDGET END -->

					</div>

					<!-- end row -->

					<!-- row -->

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

		<!-- PAGE RELATED PLUGIN(S) -->


		<script type="text/javascript">
		// DO NOT REMOVE : GLOBAL FUNCTIONS!
		$(document).ready(function() {
			pageSetUp();
		})

		</script>

		<!-- Your GOOGLE ANALYTICS CODE Below -->
		<?php //include('module/google_analytics.php')?>

	</body>

</html>