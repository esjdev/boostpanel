<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="<?= (logged() ? 'w-75 container-fluid' : 'container'); ?> padding_top">
	<div class="row justify-content-center" data-aos="fade-up">
		<div class="col-sm-12 mt-3">
			<div class="section_tittle text-center">
				<p><?= lang("common_questions"); ?></p>
				<h2><?= lang("menu_faq"); ?></h2>
			</div>

			<div class="accordion" id="accordionContent">
				<?php
					echo ($faq_counts == 0 ? '<div class="alert alert-danger p-2 rounded">' . lang("error_nothing_found") . '</div>' : '');
					foreach ($list_faq as $faq):
				?>
				<div class="card rounded mb-3 border-0 shadow-sm bg-other-blue">
					<div class="card-header" id="headingOne">
						<h2 class="mb-0">
							<button class="btn btn-link text-uppercase border-0 text-decoration-none" type="button" data-toggle="collapse" data-target="#collapse<?= $faq->id; ?>" aria-expanded="true" aria-controls="collapseOne">
								<i class="fa fa-plus text-grey-other"></i> <span class="ml-2 text-light"><?= strip_tags($faq->title); ?></span>
							</button>
						</h2>
					</div>

					<div id="collapse<?= $faq->id; ?>" class="collapse bg-blue-light" aria-labelledby="headingOne"
						data-parent="#accordionContent">
						<div class="card-body text-blue-light">
							<?= nl2br(strip_tags($faq->content)); ?>
						</div>
					</div>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>
