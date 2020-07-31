<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="container-fluid mb-3 mt-5">
	<div class="d-none d-lg-block">
		<?php /** Check for registered language */ if (langs_row()) : ?>
		<div class="row">
			<div class="col-sm-2">
				<div class="icon-language">
					<i class="fa fa-globe fa-3x text-blue-light change-language-button"></i>

					<div class="change-language-show" style="display:none;">
						<?php langs_footer(); ?>
					</div>
				</div>
			</div>
		</div>
		<?php endif; ?>
	</div>
</div>

<!-- popper js -->
<script src="<?= set_js('popper.min.js'); ?>"></script>
<!-- bootstrap js -->
<script src="<?= set_js('bootstrap.bundle.min.js'); ?>"></script>
<script src="<?= set_js('bootstrap-datepicker.min.js'); ?>"></script>
<script src="<?= set_js('bootstrap-toggle.min.js'); ?>"></script>
<!-- sweetalert2 js -->
<script src="<?= set_js('sweetalert2.all.min.js'); ?>"></script>
<!-- counterup js -->
<script src="<?= set_js('waypoints.min.js'); ?>"></script>
<script src="<?= set_js('jquery.counterup.min.js'); ?>"></script>
<!-- aos js -->
<script src="<?= set_js('aos.min.js'); ?>"></script>
<!-- custom js -->
<script src="<?= set_js('core.min.js'); ?>"></script>
<script src="<?= set_js('scripts.min.js'); ?>"></script>
<!-- tagsinput js -->
<script src="<?= set_js('jquery.tagsinput-revisited.min.js'); ?>"></script>
<!-- execute aos js -->
<script>
	AOS.init();
</script>

<?= configs('javascript_embed_footer', 'value'); ?>
</body>

</html>