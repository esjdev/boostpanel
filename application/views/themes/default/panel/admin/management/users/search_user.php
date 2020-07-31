<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php if (!empty($search_user)) : ?>
	<div class="table-users">
		<div class="table-responsive-lg mb-5">
			<table class="table border">
				<thead>
					<tr>
						<th class="text-center">*</th>
						<th><?= lang("input_name"); ?></th>
						<th><?= lang("input_username"); ?></th>
						<th><?= lang("input_email"); ?></th>
						<th><?= lang("total_spent"); ?></th>
						<th><?= lang("details"); ?></th>
						<th class="text-center"><?= lang("action"); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php $pos = 0;
					foreach ($search_user as $value) : $pos++;
						$textSexyAlert = str_replace("'", "\'", lang('confirm_close_message'));
						$sum_spendings = $this->model->sum_results(TABLE_ORDERS, ['user_id' => $value->id, 'status' => 'completed'], 'charge');

						if ($value->role == 'ADMIN') $role = '<div class="badge badge-danger text-white fs-12">' . lang("admin_role") . '</div>';
						if ($value->role == 'SUPPORT') $role = '<div class="badge badge-warning text-white fs-12">' . lang("support_role") . '</div>';
						if ($value->role == 'BANNED') $role = '<div class="badge badge-primary text-white fs-12">' . lang("banned_role") . '</div>';
						if ($value->role == 'USER') $role = '';
					?>
						<tr class="<?= (($value->role == 'BANNED') ? 'bg-light text-dark' : 'text-dark'); ?> <?= (($value->role == 'ADMIN' || $value->role == 'SUPPORT') ? 'text-dark font-weight-bold' : 'text-dark'); ?>">
							<td class="border text-center"><?= $pos; ?></td>
							<td class="border"><?= $value->name; ?></td>
							<td class="border"><?= $value->username . " " . $role; ?>
							</td>
							<td class="border"><?= $value->email; ?></td>
							<td class="border"><?= (($value->role == 'ADMIN' || $value->role == 'SUPPORT') ? '<span class="font-weight-bold">' . lang("undefined") . '</span>' : '' . configs('currency_symbol', 'value') . "" . currency_format($sum_spendings) . ''); ?>
							</td>
							<td class="border"><a href="<?= base_url("admin/users/show/" . $value->uuid); ?>"><i class="fa fa-eye fa-2x cursor-pointer"></i></a></td>
							<td class="border text-center">
								<?php if ($value->role != 'ADMIN') : ?>
									<a href="javascript:void(0);" onclick="alert_confirm('<?= base_url('admin/users/destroy/' . $value->uuid); ?>', '<?= lang('alert_close'); ?>', '<?= $textSexyAlert; ?>', '<?= lang('close'); ?>', '<?= lang('success_deleted_success'); ?>')"><i class="fa fa-trash fa-2x text-danger"></i>
									<?php else : ?>
										<i class="fa fa-minus cursor-pointer"></i>
									<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
<?php else : ?>
	<div class='bg-danger text-white p-2 rounded mb-4'><?= lang('error_nothing_found'); ?></div>
<?php endif; ?>