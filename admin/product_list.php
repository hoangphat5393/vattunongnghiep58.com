<?php require_once('product_c/product_c.php'); ?>

<?php
$atz = new product_controller();
$product_list = $atz->get_product();
$cats = $atz->get_cat();

$cat_list = array();
if (!empty($cats)) {
	foreach ($cats as $kk => $v) {
		$cat_list[$v['Cat_ID']] = $v;
	}
}
$del_product = $atz->remove_product();
?>


<!DOCTYPE html>
<html lang="en-us">

<head>
	<title> Danh sách sản phẩm </title>

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
				<li>Admin</li>
				<li>Chuyên mục</li>
				<li>Danh sách sản phẩm</li>
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
							Danh sách sản phẩm
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
								<h2>Danh sách modules</h2>
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

									<a href="product_add.php" class="btn btn-labeled btn-primary">
										<span class="btn-label"><i class="fa fa-plus-circle"></i></span>Thêm
									</a>
									<div class="table-responsive">

										<table class="table table-bordered table-striped table-hover vcenter">
											<thead>
												<tr>
													<th class="text-center">ID</th>
													<th>Tên sản phẩm</th>
													<th class="text-center">Giá</th>
													<th class="text-center">Chuyên mục</th>
													<th class="text-center">Hiện</th>
													<th class="text-center">Ưu tiên</th>
													<th class="text-center">Ngày đăng</th>
													<th class="text-center">Thao tác</th>
												</tr>
											</thead>
											<tbody>
												<?php if (!empty($product_list)) : ?>
													<?php foreach ($product_list as $v) : ?>
														<tr class="data-row">
															<td class="text-center"><?= $v['Product_ID'] ?></td>
															<td>
																<?= $v['Product_Name_vi'] ?><br>
																<img src="<?= $v['Product_Thumbnail'] ?>" width="150">
															</td>
															<td class="fw-bold data-col">
																<?php if ($v['Product_SalePrice']) : ?>
																	Giá: <?= number_format($v['Product_Price'], 0, ',', '.') ?> VNĐ<br>
																	Giá giảm: <?= $v['Product_Discount'] ?> <?= $v['Product_DiscountUnit'] ?><br>
																	Giá bán: <span class="text-red"><?= number_format($v['Product_SalePrice'], 0, ',', '.') ?> VNĐ<?= !empty($v['Product_PriceType']) ? '/<sub>' . $v['Product_PriceType'] . '</sub>' : '' ?></span>
																<?php else : ?>
																	<span class="text-red">Liên hệ</span>
																<?php endif ?>
															</td>
															<td class="text-center fw-bold">
																<a href="<?= $atz->site_url['main'] . 'danh-sach-san-pham/' . $atz->slug($cat_list[$v['Product_Cat']]['Cat_Name_vi']) . '-' . $cat_list[$v['Product_Cat']]['Cat_ID'] . '.html' ?>" title="<?= $cat_list[$v['Product_Cat']]['Cat_Name_vi'] ?>" target="_blank"><?= $cat_list[$v['Product_Cat']]['Cat_Name_vi'] ?></a>
															</td>
															<td class="text-center">
																<?php if ($v['Product_Show']) : ?>
																	Hiện
																<?php else : ?>
																	Ẩn
																<?php endif ?>
															</td>
															<td class="text-center"><?= $v['Product_Priority'] ?></td>
															<td class="text-center"><?= date('d-m-Y', $v['Product_Created']) ?></td>

															<td class="text-center">
																<a class="btn btn-sm btn-primary" href="<?= $atz->site_url['main'] . 'san-pham/' . $atz->slug($v['Product_Name_vi']) . '-' . $v['Product_ID'] . '.html' ?>" title="Link" target="_blank"><i class="fa fa-link"></i></a>
																<a class="btn btn-sm btn-primary" href="product_add.php?edit=<?= $v['Product_ID'] ?>" title="Edit"><i class="fa fa-pencil-square-o"></i></a>
																<a class="btn btn-sm btn-danger" href="product_list.php?delete=<?= $v['Product_ID'] ?>" title="Delete"><i class="fa fa-times"></i></a>
															</td>
														</tr>
													<?php endforeach ?>
												<?php else : ?>
													<tr>
														<td colspan="8" class="text-center">Chưa có dữ liệu</td>
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

	<!-- PAGE RELATED PLUGIN(S) -->


	<script type="text/javascript">
		// DO NOT REMOVE : GLOBAL FUNCTIONS!
		$(document).ready(function() {
			pageSetUp();
		})
	</script>

	<!-- Your GOOGLE ANALYTICS CODE Below -->
	<?php //include('module/google_analytics.php')
	?>

</body>

</html>