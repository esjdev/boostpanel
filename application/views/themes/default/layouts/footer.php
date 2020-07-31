<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<!-- footer part start-->
<footer class="footer-area">
	<div class="container">
		<div class="row justify-content-between">
			<div class="col-sm-6 col-md-4 col-xl-3">
				<div class="single-footer-widget footer_1">
					<a href="<?= base_url(); ?>"><img src="<?= set_image(configs('website_logo', 'value')); ?>" alt="logo-boostpanel-footer"></a>
					<div class="footer-link">
						<div class="footer-title">
							<h4 class="title"><?= lang("quick_link"); ?></h4>
						</div>

						<ul class="link">
							<li><a href="<?= base_url(); ?>"><?= lang("menu_home"); ?></a></li>
							<?php if (!logged()) : ?>
								<li><a href="<?= base_url('login'); ?>"><?= lang("button_login"); ?></a></li>
								<li><a href="<?= base_url('register'); ?>"><?= lang("button_register"); ?></a></li>
							<?php endif; ?>
							<li><a href="<?= base_url("terms"); ?>"><?= lang("menu_terms_policy"); ?></a></li>
							<li><a href="<?= base_url('api'); ?>"><?= lang("menu_api_documentation"); ?></a></li>
							<li><a href="<?= base_url('faq'); ?>"><?= lang("menu_faq"); ?></a></li>
						</ul>
					</div>
				</div>
			</div>
			<div class="d-none d-lg-block select-language">
				<?php langs_footer(); ?>
			</div>
			<div class="col-xl-3 col-sm-6 col-md-4">
				<div class="single-footer-widget">
					<h4><?= lang("contact_us"); ?></h4>

					<?php if (configs('email', 'value') != "") : ?>
						<div class="contact_info">
							<p><i class="fa fa-comments"></i> <?= configs('email', 'value'); ?></p>
						</div>
					<?php endif; ?>

					<ul class="list-inline">
						<li class="list-inline-item"><a href="<?= configs('facebook_link', 'value'); ?>" target="_blank" class="btn-facebook text-white"><i class="fa fa-facebook"></i></a></li>
						<li class="list-inline-item"><a href="<?= configs('twitter_link', 'value'); ?>" target="_blank" class="btn-twitter text-white"><i class="fa fa-twitter"></i></a></li>
						<li class="list-inline-item"><a href="<?= configs('instagram_link', 'value'); ?>" target="_blank" class="btn-instagram text-white"><i class="fa fa-instagram"></i></a></li>
						<li class="list-inline-item"><a href="<?= configs('youtube_link', 'value'); ?>" target="_blank" class="btn-youtube text-white"><i class="fa fa-youtube-play"></i></a></li>
					</ul>

					<div class="d-block d-lg-none">
						<?php langs_footer(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12">
				<div class="copyright_part_text text-center">
					<div class="row">
						<div class="col-lg-12">
							<p class="footer-text m-0">
								<?php
								$dateNow = substr(NOW, 0, 4);
								$date = ($dateNow == date('Y') ? date('Y') : '2020 ~ ' . date('Y'));
								?>
								<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
								Copyright &copy; <?= $date; ?> <?= configs('app_title', 'value'); ?><br>
								<small class="fs-10">All rights reserved | This template is made with <i class="fa fa-heart-o" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a></small>
								<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</footer>
<!-- footer part end-->

<!-- popper js -->
<script src="<?= set_js('popper.min.js'); ?>"></script>
<!-- bootstrap js -->
<script src="<?= set_js('bootstrap.bundle.min.js'); ?>"></script>
<script src="<?= set_js('bootstrap-datepicker.min.js'); ?>"></script>
<!-- counterup js -->
<script src="<?= set_js('waypoints.min.js'); ?>"></script>
<script src="<?= set_js('jquery.counterup.min.js'); ?>"></script>
<!-- aos js -->
<script src="<?= set_js('aos.min.js'); ?>"></script>
<!-- custom js -->
<script src="<?= set_js('core.min.js'); ?>"></script>
<!-- tagsinput js -->
<script src="<?= set_js('jquery.tagsinput-revisited.min.js'); ?>"></script>
<!-- execute aos js -->
<script>
	AOS.init();
</script>

<?= configs('javascript_embed_footer', 'value'); ?>
</body>

</html>