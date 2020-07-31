<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="w-75 container-fluid padding_top">
	<div class="row justify-content-between">
		<div class="col-sm-12 mt-3">
			<div class="section_tittle text-center" data-aos="fade-up">
				<p><?= lang("have_any_questions"); ?></p>
				<h2><?= lang("menu_support"); ?></h2>

				<a href="<?= base_url((userLevel(logged(), 'user') ? 'tickets' : 'admin/tickets')); ?>" class="btn btn-green border-0 text-white mt-5">
					<?= lang("tickets"); ?>
				</a>
			</div>

			<div class="alert alert-danger alert-dismissible rounded error" style="display:none;" role="alert">
				<i class="fa fa-exclamation-triangle"></i> <span class="error-message"></span>
				<a class="close cursor-pointer" aria-label="close">&times;</a>
			</div> <!-- Alert error -->

			<div class="alert alert-success alert-dismissible rounded success" style="display:none;" role="alert">
				<i class="fa fa-thumbs-up"></i> <span class="success-message"></span>
				<a class="close cursor-pointer" aria-label="close">&times;</a>
			</div> <!-- Alert success -->

			<div class="card shadow-lg border-0">
				<?php foreach ($get_ticket as $ticket) : ?>
					<div class="card-header text-white font-weight-bold bg-other-blue">
						<?php
						if ($ticket->subject == 'order') :
							$subject = lang("select_option_order") . " #" . $ticket->id;
						elseif ($ticket->subject == 'payment') :
							$subject = lang("select_option_payment") . " #" . $ticket->id;
						elseif ($ticket->subject == 'service') :
							$subject = lang("select_option_service") . " #" . $ticket->id;
						elseif ($ticket->subject == 'other') :
							$subject = lang("select_option_other") . " #" . $ticket->id;
						endif;

						if (isset($ticket_status)) {
							echo $subject . (($ticket_status == 1) ? ' <span class="badge badge-danger ml-1">' . lang("status_closed") . '</span>' : '');
						}
						?>
					</div>

					<?php if ($ticket_status == 0) : ?>
						<div class="col-md-12">
							<?= form_open('ticket/show/' . $ticket->uuid, ['id' => 'ticket-messages']); ?>
							<div class="form-group">
								<?php
								echo form_label(lang("input_message"), 'message', [
									'class' => 'form-text text-dark mt-4'
								]);

								echo form_textarea([
									'name' => 'message',
									'class' => 'form-control',
									'type' => 'text',
									'value' => set_value("message"),
									'rows' => '5'
								]);
								?>
							</div>
							<div class="form-group">
								<?php
								echo form_submit([
									'class' => 'btn btn-primary border-0',
									'type' => 'submit',
									'value' => lang("button_submit")
								]);

								$textSexyAlert = str_replace("'", "\'", lang('confirm_close_message'));
								?>
								<?= form_close(); ?>
								<?php if (userLevel(logged(), 'admin') || userLevel(logged(), 'support')) : ?>
									<a href="javascript:void(0);" onclick="alert_swal('<?= base_url('ticket/close/' . $ticket->uuid); ?>', '<?= lang('alert_close'); ?>', '<?= $textSexyAlert; ?>', '<?= lang('close'); ?>')" class="btn btn-danger border-0"><i class="fa fa-times"></i> <?= lang("button_close_ticket"); ?></a>
								<?php endif; ?>
							</div>
						</div>
					<?php endif; ?>

					<?php if ($ticket->pay_or_order_id != 0) : ?>
						<span class="w-50 bg-secondary text-white p-3 mx-auto text-center rounded fs-15 <?= ($ticket->status == 'closed' ? 'mt-3' : ''); ?>">
							<strong><?= lang("transaction_or_order_id"); ?>:</strong><br>
							<?= $ticket->pay_or_order_id; ?>
						</span>
					<?php endif; ?>

					<div class="card-body">
						<div class="row">
							<div class="col-sm-12 aqua-gradient shadow p-3 text-grey rounded font-weight-normal">
								<div class="rounded text-white fs-16 font-weight-bold">
									<i class="fa fa-user"></i>
									<strong><?= userTicketMessage($ticket->user_id, 'username'); ?></strong>
									<small><i class="fa fa-clock-o"></i> <?= convert_time($ticket->created_at, dataUser(logged(), 'timezone')); ?></small>
								</div>
								<div class="ticket-message">
									<div class="text-dark"><?= nl2br(strip_tags($ticket->description)); ?></div>
								</div>
							</div>
						</div>
					</div>
				<?php endforeach; ?>

				<?php if (!empty($get_message_ticket)) : ?>
					<div class="container w-50">
						<h2 class="font-weight-bold text-center my-4"><?= lang("answers"); ?></h2>
					</div>
				<?php endif; ?>

				<?php foreach ($get_message_ticket as $messages) : ?>
					<div class="card-body">
						<div class="row">
							<div class="col-sm-12 <?= (userTicketMessage($messages->user_id, 'role') == 'ADMIN' || userTicketMessage($messages->user_id, 'role') == 'SUPPORT') ? 'p-2 text-white rounded font-weight-normal' : 'p-2 text-grey rounded font-weight-normal'; ?>">
								<div class="rounded p-2 young-passion-gradient text-white">
									<strong>
										<i class="fa fa-user"></i> <?= (userTicketMessage($messages->user_id, 'role') == 'ADMIN' || userTicketMessage($messages->user_id, 'role') == 'SUPPORT') ? lang("menu_support") : userTicketMessage($messages->user_id, 'username'); ?>
									</strong>
									<small class="ml-1"><i class="fa fa-clock-o"></i> <?= convert_time($messages->created_at, dataUser(logged(), 'timezone')); ?>
									</small>
								</div>
								<div class="ticket-message mt-2 ml-2 text-dark fs-14">
									<?= nl2br(strip_tags($messages->content)); ?>

									<?php if (userTicketMessage($messages->user_id, 'role') == 'ADMIN' || userTicketMessage($messages->user_id, 'role') == 'SUPPORT') : ?>
										<div class="mt-3 fs-10">~~ <?= userTicketMessage($messages->user_id, 'name'); ?></div>
									<?php endif; ?>
								</div>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>
