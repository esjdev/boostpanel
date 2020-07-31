<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php if (!empty($search_subscriptions)) : ?>
	<div class="table-subscriptions-search">
		<div class="tab-content table-responsive-lg">
			<table class="table table-hover text-center">
				<thead>
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
				foreach ($search_subscriptions as $subscription) :
					$services = $this->model->get("id, name", TABLE_SERVICES, ["id" => $subscription->service_id], "", "", true);
					$name_service = limit_str($services['name'], 30, false);

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
							<td><?= $subscription->id; ?></td>
							<td><?= "<strong>" . dataUserId($subscription->user_id, "username") . "</strong>" . " <small>(" . $subscription->username . ")</small>"; ?></td>
							<td class="font-weight-bold"><?= "<span class='badge badge-primary'>{$subscription->service_id}</span> " . $name_service; ?></td>
							<td><?= ($subscription->min_sub == $subscription->max_sub ? $subscription->max_sub : $subscription->min_sub . " - " . $subscription->max_sub); ?></td>
							<td><?= $subscription->order_response_posts_sub . " / " . ($subscription->posts_sub == -1 ? "&infin;" : "<b>" . $subscription->posts_sub . "</b>"); ?></td>
							<td><?= ($subscription->delay_sub == '0' || $subscription->delay_sub == '' ? lang("no_delay") : $subscription->delay_sub . " " . lang("minutes")); ?></td>
							<td><?= $subscription->created_at; ?></td>
							<td><?= $subscription->updated_at; ?></td>
							<td><?= ($subscription->expiry_sub == "" || $subscription->expiry_sub == null || $subscription->expiry_sub == 0 ? '' : $subscription->expiry_sub); ?></td>
							<td>
								<?php if (userLevel(logged(), 'admin')) : ?>
									<?php if ($subscription->status_sub == 'Active') : ?>
										<div class="btn-group dropleft">
											<div class="badge bg-light cursor-pointer dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-boundary="viewport"><?= $status; ?></div>
											<div class="dropdown-menu">
												<a class="dropdown-item" href="<?= base_url("admin/subscriptions/pause/" . $subscription->id); ?>"><?= lang("button_pause"); ?></a>
												<a class="dropdown-item" href="javascript:void(0);" data-toggle="modal" data-target="#statusCompleted" data-backdrop="static" onclick="statusCompletedSubs(<?= $subscription->id; ?>);"><?= lang("status_completed"); ?></a>
												<a class="dropdown-item" href="<?= base_url("admin/subscriptions/expired/" . $subscription->id); ?>"><?= lang("status_subs_expired"); ?></a>
											</div>
										</div>
									<?php elseif ($subscription->status_sub == 'Paused') : ?>
										<div class="btn-group dropleft">
											<div class="badge badge-secondary cursor-pointer dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-boundary="viewport"><?= $status; ?></div>
											<div class="dropdown-menu">
												<a class="dropdown-item" href="<?= base_url("admin/subscriptions/resume/" . $subscription->id); ?>"><?= lang("status_active"); ?></a>
												<a class="dropdown-item" href="javascript:void(0);" data-toggle="modal" data-target="#statusCompleted" data-backdrop="static" onclick="statusCompletedSubs(<?= $subscription->id; ?>);"><?= lang("status_completed"); ?></a>
												<a class="dropdown-item" href="<?= base_url("admin/subscriptions/expired/" . $subscription->id); ?>"><?= lang("status_subs_expired"); ?></a>
												<a class="dropdown-item" href="<?= base_url("admin/subscriptions/stop/" . $subscription->id); ?>"><?= lang("status_canceled"); ?></a>
											</div>
										</div>
									<?php else : ?>
										<?= $status; ?>
									<?php endif; ?>
								<?php endif; ?>
							</td>
						</tr>
					</tbody>
				<?php endforeach; ?>
			</table>
		</div>
	</div>
<?php else : ?>
	<div class='bg-danger text-white p-2 rounded mt-3'><?= lang('error_nothing_found'); ?></div>
<?php endif; ?>