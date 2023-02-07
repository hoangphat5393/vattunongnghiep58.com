<!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
<script src="<?=$atz->site_url['main']?>assets/jquery-3.6.3/jquery-3.6.3.min.js"></script>
<script src="<?=$atz->site_url['main']?>assets/bootstrap-4.6.0/js/bootstrap.bundle.min.js"></script>

<!-- Extra -->
<script src="<?=$atz->site_url['main']?>assets/owl.carousel-2.3.4/js/owl.carousel.min.js"></script>

<script src="<?=$atz->site_url['main']?>assets/jquery-simplyscroll-2.1.1/jquery.simplyscroll.min.js"></script>

<!-- Jquery Validate -->
<script src="<?=$atz->site_url['main']?>assets/jquery-validation-1.19.5/jquery.validate.min.js"></script>


<script>
	$('.dropdown-menu a.dropdown-toggle').on('click', function(e) {
	  if (!$(this).next().hasClass('show')) {
	    $(this).parents('.dropdown-menu').first().find('.show').removeClass('show');
	  }
	  var $subMenu = $(this).next('.dropdown-menu');
	  $subMenu.toggleClass('show');


	  $(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function(e) {
	    $('.dropdown-submenu .show').removeClass('show');
	  });


	  return false;
	});
</script>


<!--Start of Tawk.to Script-->
<script type="text/javascript">
	var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
	(function(){
		var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
		s1.async=true;
		s1.src='https://embed.tawk.to/608d419262662a09efc3ea48/1f4jsg970';
		s1.charset='UTF-8';
		s1.setAttribute('crossorigin','*');
		s0.parentNode.insertBefore(s1,s0);
	})();
</script>
<!--End of Tawk.to Script-->

<!-- <div class="zalo-chat-widget" data-oaid="579745863508352884" data-welcome-message="Rất vui khi được hỗ trợ bạn!" data-autopopup="0" data-width="350" data-height="420"></div>

<script src="https://sp.zalo.me/plugins/sdk.js"></script> -->