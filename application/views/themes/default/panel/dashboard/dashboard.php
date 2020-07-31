<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="w-75 container-fluid padding_top">
	<div class="row justify-content-center">
		<div class="col-sm-12 mt-3">
			<div class="section_tittle text-center">
				<p><?= lang("welcome_to_panel"); ?></p>
				<h2><?= lang("menu_dashboard"); ?></h2>
			</div>

			<div class="row">
				<div class="col-sm-6 mb-3">
					<?php if ($user_role == 'USER') : ?>
						<div class="card shadow">
							<div class="card-body young-passion-gradient text-white text-center border rounded">
								<i class="fa fa-money fa-4x float-left mt-2"></i>
								<div class="fs-30 font-weight-bold">
									<?= configs('currency_symbol', 'value') . "<span class='counter'>" . userBalance(logged()) . "</span>"; ?>
								</div>
								<div class="h6"><?= strtoupper(lang("current_balance")); ?></div>
							</div>
						</div>
					<?php endif; ?>

					<?php if (in_array($user_role, ['ADMIN', 'SUPPORT'])) : ?>
						<div class="card shadow">
							<div class="card-body young-passion-gradient text-white text-center border rounded">
								<i class="fa fa-users fa-4x float-left mt-2"></i>
								<div class="fs-30 font-weight-bold counter"><?= $total_users; ?></div>
								<div class="h6"><?= strtoupper(lang("dashboard_total_users")); ?></div>
							</div>
						</div>
					<?php endif; ?>

					<div class="card shadow">
						<div class="card-body peach-gradient-rgba text-white text-center border rounded">
							<?php $spendings = (in_array($user_role, ['ADMIN', 'SUPPORT']) ? $admin_sum_spendings : $user_sum_spendings); ?>
							<i class="fa fa-usd fa-4x float-left mt-2 ml-2"></i>
							<div class="fs-30 font-weight-bold">
								<?= configs('currency_symbol', 'value') . "<span class='counter'>" . currency_format($spendings) . "</span>"; ?>
							</div>
							<div class="h6"><?= strtoupper(lang("dashboard_total_spendings")); ?></div>
						</div>
					</div>

					<div class="card shadow">
						<div class="card-body blue-gradient text-white text-center border rounded">
							<i class="fa fa-shopping-cart fa-4x float-left mt-2"></i>
							<div class="fs-30 font-weight-bold counter"><?= ($user_role == 'USER' ? $count_orders_user : $count_orders); ?></div>
							<div class="h6"><?= strtoupper(lang("dashboard_total_order")); ?></div>
						</div>
					</div>

					<div class="card shadow">
						<div class="card-body aqua-gradient text-white text-center border rounded">
							<i class="fa fa-life-ring fa-4x float-left mt-2"></i>
							<div class="fs-30 font-weight-bold counter"><?= ($user_role == 'USER' ? $total_tickets_user : $total_tickets); ?></div>
							<div class="h6"><?= strtoupper(lang("dashboard_total_ticket")); ?></div>
						</div>
					</div>
				</div>

				<div class="col-sm-6 bg-linear-blue text-white rounded p-3 mb-3" style="height:460px;">
					<h4 class="mb-3 text-white"><i class="fa fa-shopping-cart"></i> <?= lang('recent_orders'); ?></h4>
					<div id="chart_orders" style="height:400px;"></div>
				</div>

				<div class="col-sm-6">
					<div class="h5 bg-linear-blue shadow pt-3 pb-3 pl-3 text-white rounded"><i class="fa fa-cubes"></i> <?= lang('top_best_sellers'); ?></div>

					<?php if (!empty($list_top_bestseller)) : ?>
						<div class="row">
							<?php foreach ($list_top_bestseller as $value) :
								$api = $this->model->get('*', TABLE_API_PROVIDERS, ['id' => $value->api_provider_id], '', '', true);
								$category = $this->model->get('*', TABLE_CATEGORIES, ['id' => $value->category_id], '', '', true);

								$name_service = limit_str($value->name_service, 80, false);
								$category_name = limit_str($category['name'], 55, true);
							?>
								<div class="col-sm-12 mb-2">
									<div class="p-2 bg-blue-other-light rounded border shadow">
										<div class="row">
											<div class="col-sm-12">
												<div class="h6 font-weight-bold text-blue <?= (userLevel(logged(), 'user') ? 'm-t-10' : ''); ?>"><?= $name_service; ?></div>

												<?php if (userLevel(logged(), 'user')) : ?>
													<div class="bg-blue-light rounded p-2 mt-1 text-white fs-11">
														<strong><?= lang('service_id'); ?>:</strong> <?= $value->id_service; ?> -
														<strong><?= lang("menu_category"); ?>:</strong> <?= $category_name; ?>
														<div class="badge badge-danger fs-12 float-right">
															<?= configs('currency_symbol', 'value') . currency_format($value->price); ?>
														</div>
													</div>
												<?php endif; ?>

												<?php if (userLevel(logged(), 'admin') || userLevel(logged(), 'support')) : ?>
													<small class="fs-12 text-white">
														<div class="bg-blue-light rounded p-2 mt-1 text-blue-light fs-11">
															<strong><?= lang("total"); ?>:</strong> <?= $value->total; ?> -
															<strong><?= lang('type'); ?>:</strong> <?= ($value->add_type == 'api') ? $api['name'] : 'Manual'; ?> -
															<strong><?= lang('api_service_id'); ?>:</strong> <?= ($value->add_type == 'api') ? $value->api_service_id : '--'; ?>
															<div class="badge badge-danger fs-12 float-right">
																<?= configs('currency_symbol', 'value') . currency_format($value->price); ?>
															</div>
														</div>
													</small>
												<?php endif; ?>
											</div>
										</div>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					<?php else : ?>
						<div class='text-danger mt-3 mb-3'><?= lang("error_nothing_found"); ?></div>
					<?php endif; ?>
				</div>

				<div class="col-sm-6 shadow rounded p-2 border">
					<div class="bg-white">
						<div class="card-title h5 p-2 text-blue-light">
							<i class="fa fa-bars"></i> <?= lang('updated_and_disabled_services'); ?>
						</div>

						<?= (empty($list_news_updated_disable) ? "<div class='text-danger pl-2'>" . lang("error_nothing_found") . "</div>" : ''); ?>

						<?php if (!empty($list_news_updated_disable)) : ?>

							<div class="card-body last_informations">
								<div class="news-list-panel">
									<?php
									foreach ($list_news_updated_disable as $value) :
										$id_service_disables = explode(',', $value->desc_disables);
										$id_service_updates = explode(',', $value->desc_updates);
									?>
										<div class="news-list-item">
											<span class="young-passion-gradient text-white p-1 fs-13 rounded date">
												<?= date("F j, Y, g:i a", strtotime($value->created_at)); ?>
											</span>

											<div class="news-type-list">
												<div class="news-type-list-item">
													<div class="title text-green mt-3"><?= lang("updated"); ?></div>
													<?php
													if ($value->desc_updates != '' || !empty($value->desc_updates)) :
														foreach ($id_service_updates as $updated) :
															if ($updated != '') :
																$name_service_update = $this->model->get('*', TABLE_SERVICES, ['api_service_id' => $updated], '', '', true);
													?>
																<ul class="list">
																	<li class="list-item fs-13"><strong><?= $updated; ?></strong> - <?= $name_service_update['name']; ?></li>
																</ul>
													<?php endif;
														endforeach;
													else : echo "<div class='ml-4 mb-3 fs-11'>" . lang("error_no_services_updated") . "</div>";
													endif; ?>
												</div>
												<div class="news-type-list-item">
													<div class="title text-danger"><?= lang("disabled"); ?></div>
													<?php
													if ($value->desc_disables != '' || !empty($value->desc_disables)) :
														foreach ($id_service_disables as $disable) :
															if ($disable != '') :
																$name_service_disabled = $this->model->get('*', TABLE_SERVICES, ['api_service_id' => $disable], '', '', true);
													?>
																<ul class="list">
																	<li class="list-item fs-13"><strong><?= $disable; ?></strong> - <?= $name_service_disabled['name']; ?></li>
																</ul>
													<?php endif;
														endforeach;
													else : echo "<div class='ml-4 mb-3 fs-11'>" . lang("error_no_services_disabled") . "</div>";
													endif; ?>
												</div>
											</div>
										</div>
									<?php endforeach; ?>
								</div>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>

			<div class="col-sm-12">
				<div class="section_tittle text-center m-t-50">
					<p><?= lang("latest_news"); ?></p>
					<h2><?= lang("general_news"); ?></h2>
				</div>

				<?php if (!empty($list_news)) : ?>
					<div class="row">
						<?php
						foreach ($list_news as $value) :
							$description = limit_str($value->description, 200, true);
						?>
							<div class="col-sm-4 mb-3">
								<div class="shadow p-2 cursor-pointer border rounded">
									<div class="text-dark font-weight-bold fs-16"><?= $value->title; ?></div>
									<div class="text-dark mt-2">
										<?= strip_tags($description); ?>
									</div>

									<div class="mt-3 pull-left fs-9"><i class="fa fa-clock-o"></i> <?= convert_time($value->created_at, dataUser(logged(), 'timezone')); ?></div>
									<div class="btn_4 mt-3 pull-right" data-toggle="modal" data-target="#news<?= $value->id; ?>"><?= lang("read_more"); ?> <i class="fa fa-plus"></i></div>
								</div>
							</div>

							<div class="modal fade" id="news<?= $value->id; ?>" tabindex="-1">
								<div class="modal-dialog">
									<div class="modal-content bg-white">
										<div class="modal-header border-0">
											<p class="modal-title font-weight-bold text-dark"><?= $value->title; ?>
												<br>
												<small class="fs-10"><?= convert_time($value->created_at, dataUser(logged(), 'timezone')); ?></small>
											</p>
											<button type="button" class="close" data-dismiss="modal">
												<span>&times;</span>
											</button>
										</div>
										<div class="modal-body overflow-auto" style="height:600px;">
											<?= $value->description; ?>
										</div>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				<?php else : ?>
					<div class='bg-danger text-white p-2 rounded'><?= lang("error_nothing_found"); ?></div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>

<!-- c3chart js -->
<script src="<?= set_js('plugins/c3_chart/d3-5.8.2.min.js'); ?>"></script>
<script src="<?= set_js('plugins/c3_chart/c3.min.js'); ?>"></script>
<script src="<?= set_js('plugins/c3_chart/Chart.js'); ?>"></script>
<script>
	$(document).ready(function() {
		chart_spline('#chart_orders', <?= $list_chartjs_order; ?>);
	});
</script>
