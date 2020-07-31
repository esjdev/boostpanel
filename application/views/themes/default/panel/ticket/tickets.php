<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="w-75 container-fluid padding_top">
	<div class="row justify-content-between">
		<div class="col-sm-12 mt-3">
			<div class="section_tittle text-center" data-aos="fade-up">
				<p><?= lang("have_any_questions"); ?></p>
				<h2><?= lang("menu_support"); ?></h2>

				<a href="javascript:void(0);" class="btn btn-green btn-lg border-0 text-white mt-5" data-toggle="modal" data-target="#supportTicket" data-backdrop="static" onclick="add_new_service();">
					<i class="fa fa-plus-circle text-white"></i> <?= lang("add_new"); ?>
				</a>
			</div>

			<div class="input-group bg-dark rounded mb-3">
				<?php
				if (!empty($get_ticket_user)) :
					echo form_input([
						'name' => 'searchTickets',
						'class' => 'form-control searchTicketsAjax bg-dark text-white border-0',
						'type' => 'text',
						'data-url' => '/ticket/search',
						'placeholder' => lang("placeholder_search_ticket"),
					]);
				else :
					echo form_input([
						'name' => 'searchTickets',
						'class' => 'form-control searchTicketsAjax bg-dark text-white border-0',
						'type' => 'text',
						'data-url' => 'javascript:void(0);',
						'placeholder' => lang("placeholder_search_ticket"),
						'disabled' => 'disabled'
					]);
				endif;
				?>

				<div class="input-group-prepend">
					<i class="fa fa-search rounded-right text-white bg-default mr-3 m-t-12"></i>
				</div>
			</div>

			<?php if (!empty($get_ticket_user)) : ?>
				<div class="table-search-tickets">
					<div class="table-responsive-lg">
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
								<?php foreach ($get_ticket_user as $value) : ?>
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
									?>
									<tr class="cursor-pointer">
										<td class="border text-center font-weight-bold">#<?= $value->id; ?></td>
										<td class="border">
											<a href="<?= base_url('ticket/show/' . $value->uuid); ?>" class="text-secondary text-decoration-none"><?= $subject; ?> <?= ($value->status == 'answered' ? '<div class="badge badge-green p-1"><i class="fa fa-bell"></i></div>' : ''); ?>
											</a>
										</td>
										<td class="border"><?= $status; ?></td>
										<td class="border"><?= convert_time($value->updated_at, dataUser(logged(), 'timezone')); ?></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>

				<?= $pagination_links; ?>
			<?php else : ?>
				<div class='bg-danger text-white p-2 rounded'><?= lang('error_nothing_found'); ?></div>
			<?php endif; ?>

			<!--- Modal Add new Ticket -->
			<div class="modal fade" id="supportTicket" tabindex="-1">
				<div class="modal-dialog">
					<div class="modal-content bg-white">
						<div class="modal-header">
							<h4 class="modal-title font-weight-bold text-dark">
								<?= lang("add_new"); ?></h4>
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

							<?= form_open('tickets', ['id' => 'ticket-support']); ?>
							<div class="form-group">
								<?php
								echo form_label(lang("input_subject"), '', [
									'class' => 'form-text font-weight-bold'
								]);

								echo form_dropdown('subject_ticket', [
									'not' => lang("select_option_default"),
									'order' => lang("select_option_order"),
									'payment' => lang("select_option_payment"),
									'service' => lang("select_option_service"),
									'other' => lang("select_option_other"),
								], null, [
									'id' => 'subject_ticket',
									'class' => 'form-control',
								]);
								?>
							</div>
							<div class="only-order" style="display:none;">
								<div class="form-group">
									<?php
									echo form_label(lang("input_order_id"), 'order_id', [
										'class' => 'form-text font-weight-bold'
									]);

									echo form_input([
										'name' => 'order_id',
										'class' => 'form-control',
										'type' => 'text'
									]);
									?>
								</div>
							</div>
							<div class="only-payment" style="display:none;">
								<div class="form-group">
									<?php
									echo form_label(lang("input_transaction_id"), 'trans_id_payment', [
										'class' => 'form-text font-weight-bold'
									]);

									echo form_input([
										'name' => 'trans_id_payment',
										'class' => 'form-control',
										'type' => 'text'
									]);
									?>
								</div>
							</div>
							<div class="form-group">
								<?php
								echo form_label(lang("input_message"), 'message', [
									'class' => 'form-text font-weight-bold'
								]);

								echo form_textarea([
									'name' => 'message',
									'class' => 'form-control',
									'value' => set_value("message"),
									'placeholder' => lang("input_message"),
									'rows' => '5'
								]);
								?>
							</div>
							<div class="form-group">
								<?php
								echo form_submit([
									'class' => 'btn btn-lg btn-green mb-3 w-100 border-0',
									'type' => 'submit',
									'value' => lang("button_submit")
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
		</div>
	</div>
</div>

<script>
	$('#subject_ticket').on('change', function() {
		var subject_ticket = document.getElementById('subject_ticket');
		subject = subject_ticket.options[subject_ticket.selectedIndex].value;

		if (subject == 'order') {
			$(".only-order").attr('style', 'display:block;');
		} else {
			$(".only-order").attr('style', 'display:none;');
			$("input[name=order_id]").val('');
		}

		if (subject == 'payment') {
			$(".only-payment").attr('style', 'display:block;');
		} else {
			$(".only-payment").attr('style', 'display:none;');
			$("input[name=trans_id_payment]").val('');
		}
	});
</script>