<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php if (!empty($search_tickets_user)) : ?>
<div class="table-search-tickets">
	<div class="tab-content table-responsive-lg">
		<table class="table border">
			<thead>
			<tr>
				<th class="text-center"><?= lang("ticket_id"); ?></th>
				<th><?= lang("input_subject"); ?></th>
				<th><?= lang("status"); ?></th>
				<th><?= lang("last_update"); ?></th>
			</tr>
			</thead>
			<tbody>
			<?php foreach ($search_tickets_user as $value) : ?>
				<?php
				if ($value->status == 'pending') :
					$status = '<span class="text-warning font-weight-bold">' . lang("status_pending") . '</span>';
				elseif ($value->status == 'answered') :
					$status = '<span class="text-success font-weight-bold">' . lang("status_answered") . '</span>';
				elseif ($value->status == 'closed') :
					$status = '<span class="text-danger font-weight-bold">' . lang("status_closed") . '</span>';
				endif;

				if ($value->subject == 'order') :
					$subject = lang("select_option_order");
				elseif ($value->subject == 'payment') :
					$subject = lang("select_option_payment");
				elseif ($value->subject == 'service') :
					$subject = lang("select_option_service");
				elseif ($value->subject == 'other') :
					$subject = lang("select_option_other");
				endif;

				$textSexyAlert = str_replace("'", "\'", lang('confirm_close_message'));
				?>
				<tr class="cursor-pointer bg-light">
					<td onclick="location.href='<?= base_url('ticket/show/' . $value->uuid); ?>';" class="border font-weight-bold text-center">#<?= $value->id; ?></td>
					<td onclick="location.href='<?= base_url('ticket/show/' . $value->uuid); ?>';" class="border"><?= $subject; ?> <?= ($value->status == 'answered' ? '<div class="badge badge-green p-1"><i class="fa fa-bell"></i></div>' : ''); ?>
					</td>
					<td onclick="location.href='<?= base_url('ticket/show/' . $value->uuid); ?>';" class="border"><?= $status; ?></td>
					<td onclick="location.href='<?= base_url('ticket/show/' . $value->uuid); ?>';" class="border"><?= convert_time($value->updated_at, dataUser(logged(), 'timezone')); ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
<?php else: ?>
	<div class='bg-danger text-white p-2 rounded mb-3'><?= lang('error_nothing_found'); ?></div>
<?php endif; ?>
