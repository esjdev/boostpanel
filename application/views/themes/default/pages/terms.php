<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="<?= (logged() ? 'w-75 container-fluid' : 'container'); ?> padding_top">
	<div class="row justify-content-center" data-aos="fade-up">
		<div class="col-sm-12 mt-3">
			<div class="section_tittle text-center">
				<p><?= lang("terms_of_service"); ?></p>
				<h2><?= lang("menu_terms_policy"); ?></h2>
			</div>
		</div>

		<div class="col-sm-12">
			<h4 class="card-title font-weight-bold"><?= lang("terms"); ?></h4>
			<?php
				foreach ($show_terms as $terms):
					echo strip_word_textarea($terms->value);
				endforeach;
			?>
		</div>

		<div class="col-sm-12 mt-5 mb-5">
			<h4 class="card-title font-weight-bold"><?= lang("privacy_policy"); ?></h4>
			<?php
				foreach ($show_policy as $policy):
					echo strip_word_textarea($policy->value);
				endforeach;
			?>
		</div>
	</div>
</div>
