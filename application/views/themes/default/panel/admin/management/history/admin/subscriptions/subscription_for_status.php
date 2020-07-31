<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="w-75 container-fluid padding_top">
	<div class="row justify-content-center">
		<div class="col-sm-12 mt-3">
			<div class="section_tittle text-center" data-aos="fade-up">
				<p><?= lang("subscriptions_logs"); ?></p>
				<h2><?= lang("subscriptions"); ?></h2>
			</div>

			<?php view("panel/admin/management/history/admin/subscriptions/default_layout"); ?>

			<?php if (!empty($subscriptions_for_status)) : ?>
				<div class="table-subscriptions-search">
					<table class="table tab-content table-responsive-lg border">
						<thead class="text-center">
							<tr>
								<th><?= lang("id"); ?></th>
								<th><?= lang("input_username"); ?></th>
								<th><?= lang("service"); ?></th>
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
									<td class="border"><?= $subscription->id; ?></td>
									<td class="border"><?= "<strong>" . dataUserId($subscription->user_id, "username") . "</strong>" . " <small>(" . $subscription->username . ")</small>"; ?></td>
									<td class="border font-weight-bold"><?= "<span class='badge badge-primary'>{$subscription->service_id}</span> " . $name_service; ?></td>
									<td class="border"><?= ($subscription->min_sub == $subscription->max_sub ? $subscription->max_sub : $subscription->min_sub . " - " . $subscription->max_sub); ?></td>
									<td class="border"><?= $subscription->order_response_posts_sub . " / " . ($subscription->posts_sub == -1 ? "&infin;" : "<b>" . $subscription->posts_sub . "</b>"); ?></td>
									<td class="border"><?= ($subscription->delay_sub == '0' || $subscription->delay_sub == '' ? lang("no_delay") : $subscription->delay_sub . " " . lang("minutes")); ?></td>
									<td class="border"><?= $subscription->created_at; ?></td>
									<td class="border"><?= $subscription->updated_at; ?></td>
									<td class="border"><?= ($subscription->expiry_sub == "" || $subscription->expiry_sub == null || $subscription->expiry_sub == 0 ? '' : $subscription->expiry_sub); ?></td>
									<td class="border">
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

					<?= $pagination_links; ?>
				</div>
			<?php else : ?>
				<div class='bg-danger text-white p-2 rounded mt-3'><?= lang('error_nothing_found'); ?></div>
			<?php endif; ?>
		</div>
	</div>
</div>


<!-- Modal Status Completed Subscription -->
<div class="modal fade" id="statusCompleted" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content bg-white">
			<div class="modal-header">
				<h4 class="modal-title font-weight-bold text-dark"><?= lang("status"); ?></h4>
				<button type="button" class="close" data-dismiss="modal">
					<span>&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="alert alert-danger alert-dismissible rounded error" style="display:none;" role="alert">
					<i class="fa fa-exclamation-triangle"></i> <span class="error-message"></span>
					<a class="close cursor-pointer" aria-label="close">&times;</a>
				</div> <!-- Alert error -->

				<div class="alert alert-success alert-dismissible rounded success" style="display:none;" role="alert">
					<i class="fa fa-thumbs-up"></i> <span class="success-message"></span>
					<a class="close cursor-pointer" aria-label="close">&times;</a>
				</div> <!-- Alert success -->

				<?= form_open('', ['id' => 'update-status-completed-subs']); ?>
				<div class="form-group">
					<?php
					echo form_label(lang("posts"), 'posts_sub', [
						'class' => 'form-text font-weight-bold'
					]);

					echo form_input([
						'name' => 'posts_sub',
						'class' => 'form-control',
						'type' => 'number',
						'value' => set_value('posts_sub')
					]);
					?>
				</div>
				<div class="form-group">
					<?php
					echo form_label(lang("remains"), 'remains_sub', [
						'class' => 'form-text font-weight-bold'
					]);

					echo form_input([
						'name' => 'remains_sub',
						'class' => 'form-control',
						'type' => 'number',
						'value' => set_value('remains_sub')
					]);
					?>
				</div>
				<div class="form-group">
					<?php
					echo form_label(lang("start_count"), 'start_count_sub', [
						'class' => 'form-text font-weight-bold'
					]);

					echo form_input([
						'name' => 'start_count_sub',
						'class' => 'form-control',
						'type' => 'number',
						'value' => set_value('start_count')
					]);
					?>
				</div>
				<div class="form-group">
					<?php
					echo form_submit([
						'class' => 'genric-btn info-green e-large btn-block radius fs-16',
						'type' => 'submit',
						'value' => lang("save")
					]);
					?>
				</div>
				<?= form_close(); ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal"><?= lang("close"); ?></button>
			</div>
		</div>
	</div>
</div>