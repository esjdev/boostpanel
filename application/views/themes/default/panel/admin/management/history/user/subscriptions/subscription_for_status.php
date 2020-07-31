<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="w-75 container-fluid padding_top">
	<div class="row justify-content-center">
		<div class="col-sm-12 mt-3">
			<div class="section_tittle text-center" data-aos="fade-up">
				<p><?= lang("subscriptions_logs"); ?></p>
				<h2><?= lang("subscriptions"); ?></h2>
			</div>

			<?php view("panel/admin/management/history/user/subscriptions/default_layout"); ?>

			<?php if (!empty($subscriptions_for_status)) : ?>
				<div class="table-subscriptions-search">
					<table class="table tab-content table-responsive-lg border">
						<thead class="text-center">
							<tr>
								<th><?= lang("id"); ?></th>
								<th><?= lang("service"); ?></th>
								<th><?= lang("input_username"); ?></th>
								<th><?= lang("quantity"); ?></th>
								<th><?= lang("posts"); ?></th>
								<th><?= lang("delay"); ?></th>
								<th><?= lang("created"); ?></th>
								<th><?= lang("updated"); ?></th>
								<th><?= lang("expire"); ?></th>
								<th><?= lang("status"); ?></th>
							</tr>
						</thead>
						<?php
						foreach ($subscriptions_for_status as $subscription) :
							$services = $this->model->get("id, name", TABLE_SERVICES, ["id" => $subscription->service_id], "", "", true);
							$name_service = limit_str($services['name'], 50, false);

							if ($subscription->status_sub == 'Active') {
								$status = '<div class="badge bg-primary cursor-pointer text-white fs-12">' . lang("status_active") . '</div>';
							} elseif ($subscription->status_sub == 'Paused') {
								$status = '<div class="badge badge-secondary text-white cursor-pointer fs-12">' . lang("status_subs_paused") . '</div>';
							} elseif ($subscription->status_sub == 'Completed') {
								$status = '<div class="badge badge-green text-white cursor-pointer fs-12">' . lang("status_completed") . '</div>';
							} elseif ($subscription->status_sub == 'Expired') {
								$status = '<div class="badge badge-danger cursor-pointer text-white fs-12">' . lang("status_subs_expired") . '</div>';
							} elseif ($subscription->status_sub == 'Canceled') {
								$status = '<div class="badge badge-danger cursor-pointer text-white fs-12">' . lang("status_canceled") . '</div>';
							}
						?>
							<tbody>
								<tr class="text-center">
									<td class="border"><?= $subscription->id; ?></td>
									<td class="border font-weight-bold"><?= $name_service; ?></td>
									<td class="border"><?= $subscription->username; ?></td>
									<td class="border"><?= ($subscription->min_sub == $subscription->max_sub ? $subscription->max_sub : $subscription->min_sub . " - " . $subscription->max_sub); ?></td>
									<td class="border"><?= $subscription->order_response_posts_sub . " / " . ($subscription->posts_sub == -1 ? "&infin;" : "<b>" . $subscription->posts_sub . "</b>"); ?></td>
									<td class="border"><?= ($subscription->delay_sub == '0' || $subscription->delay_sub == '' ? lang("no_delay") : $subscription->delay_sub . " " . lang("minutes")); ?></td>
									<td class="border"><?= $subscription->created_at; ?></td>
									<td class="border"><?= $subscription->updated_at; ?></td>
									<td class="border"><?= ($subscription->expiry_sub == "" || $subscription->expiry_sub == null || $subscription->expiry_sub == 0 ? '' : $subscription->expiry_sub); ?></td>
									<td class="border">
										<?php if ($subscription->status_sub == 'Active') : ?>
											<div class="btn-group dropleft">
												<div class="badge bg-light cursor-pointer dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-boundary="viewport"><?= $status; ?></div>
												<div class="dropdown-menu mb-3">
													<a class="dropdown-item" href="<?= base_url("subscriptions/pause/" . $subscription->id); ?>"><?= lang("button_pause"); ?></a>
												</div>
											</div>
										<?php elseif ($subscription->status_sub == 'Paused') : ?>
											<div class="btn-group dropleft">
												<div class="badge bg-light cursor-pointer dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-boundary="viewport"><?= $status; ?></div>
												<div class="dropdown-menu mb-3">
													<a class="dropdown-item" href="<?= base_url("subscriptions/resume/" . $subscription->id); ?>"><?= lang("status_active"); ?></a>
													<a class="dropdown-item" href="<?= base_url("subscriptions/stop/" . $subscription->id); ?>"><?= lang("status_canceled"); ?></a>
												</div>
											</div>
										<?php else : ?>
											<?= $status; ?>
										<?php endif; ?>
									</td>
								</tr>
							</tbody>
						<?php endforeach; ?>
					</table>

					<?= $pagination_links; ?>
				</div>
			<?php else : ?>
				<div class='bg-danger text-white p-2 rounded mt-3'><?= lang('error_nothing_found'); ?></div>
			<?php endif; ?>
		</div>
	</div>
</div>