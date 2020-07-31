<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="w-75 container-fluid padding_top">
	<div class="row justify-content-center">
		<div class="col-sm-12 mt-3">
			<div class="section_tittle text-center" data-aos="fade-up">
				<p><?= lang("service_list"); ?></p>
				<h2><?= lang("menu_services"); ?></h2>
			</div>

			<?php if (!empty($rows_services)) : ?>
				<table class="table-responsive-lg table">
					<thead>
						<tr class="fs-14">
							<th class="text-center"><?= lang("id"); ?></th>
							<th><?= lang("input_name"); ?></th>
							<th><?= lang("rate"); ?> (<?= configs('currency_symbol', 'value'); ?>)</th>
							<th><?= lang("min"); ?> / <?= lang("max"); ?></th>
							<th class="text-center"><?= lang("description"); ?></th>
						</tr>
					</thead>
					<?php
					foreach ($categories as $value) :
						$services = $this->model->fetch('*', TABLE_SERVICES, ['category_id' => $value->id, 'status' => '1'], 'created_at', 'desc', '', '');

						if (!empty($services)) :
					?>
							<tbody>
								<tr class="bg-linear text-white">
									<td colspan="5"><?= $value->name; ?></td>
								</tr>
								<?php
								foreach ($services as $data) :
									$name_service = limit_str($data->name, 80, false);
									$value_rate = ($data->price <= 0.009 ? currency_format($data->price, 4) : currency_format($data->price));
								?>
									<tr>
										<td class="border text-center"><?= $data->id; ?></td>
										<td class="border"><?= $name_service; ?></td>
										<td class="border"><?= configs('currency_symbol', 'value') . $value_rate; ?></td>
										<td class="border"><?= $data->min; ?>/<?= $data->max; ?></td>
										<td class="border text-center"><i class="fa fa-eye fa-2x cursor-pointer" data-toggle="modal" data-target="#buttonDescription<?= $data->id; ?>" data-backdrop="static"></i></td>
									</tr>
							</tbody>

							<div class="modal fade padding_top" id="buttonDescription<?= $data->id; ?>" tabindex="-1">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header border-0">
											<p class="modal-title font-weight-bold text-dark"><?= $data->name; ?></p>
											<button type="button" class="close" data-dismiss="modal">
												<span>&times;</span>
											</button>
										</div>
										<div class="modal-body">
											<?php
											if ($data->description) {
												echo nl2br(strip_tags($data->description));
											} else {
												echo "<div class='bg-danger text-white p-2 rounded'><i class=\"fa fa-exclamation-triangle\"></i> " . lang('error_does_not_have_description') . "</div>";
											}
											?>
											<div class="modal-footer border-0">
												<button type="button" class="btn btn-danger" data-dismiss="modal"><?= lang("close"); ?></button>
											</div>
										</div>
									</div>
								</div>
							</div>
				<?php endforeach; endif; endforeach; ?>
				</table>
			<?php else : ?>
				<div class='bg-danger text-white p-2 rounded'><?= lang('error_nothing_found'); ?></div>
			<?php endif; ?>
		</div>
	</div>
</div>