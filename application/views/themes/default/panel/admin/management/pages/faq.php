<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="w-75 container-fluid padding_top">
	<div class="row justify-content-center">
		<div class="col-sm-12 mt-3">
			<div class="section_tittle text-center" data-aos="fade-up">
				<p><?= lang("faq_management"); ?></p>
				<h2><?= lang("menu_faq"); ?></h2>

				<a href="javascript:void(0);" class="btn btn-green btn-lg border-0 text-white mt-5" data-toggle="modal" data-target="#addFaq" data-backdrop="static" onclick="add_new_service();">
					<i class="fa fa-plus-circle text-white"></i> <?= lang("add_new"); ?>
				</a>
			</div>

			<?php if (flashdata('error')) : ?>
				<div class="alert alert-danger alert-dismissible rounded error" role="alert">
					<i class="fa fa-exclamation-triangle"></i> <span class="error-message"><?= flashdata('error'); ?></span>
					<a class="close cursor-pointer" aria-label="close">&times;</a>
				</div> <!-- Alert Flash Error -->
			<?php endif; ?>

			<?= (empty($list_faq) ? '<div class="bg-danger text-white p-2 rounded">' . lang("error_nothing_found") . '</div>' : ''); ?>

			<div class="accordion" id="accordionContent">
				<?php foreach ($list_faq as $faq) : $textSexyAlert = str_replace("'", "\'", lang('confirm_close_message')); ?>
					<div class="card rounded mb-3 border-0 shadow-sm bg-other-blue">
						<div class="card-header" id="headingOne">
							<button class="btn btn-link text-uppercase border-0 text-decoration-none" type="button" data-toggle="collapse" data-target="#collapse<?= $faq->id; ?>" aria-expanded="true" aria-controls="collapseOne">
								<i class="fa fa-plus text-grey-other"></i> <span class="ml-2 text-light" id="title_faq<?= $faq->id; ?>"><?= strip_tags($faq->title); ?></span>
							</button>

							<div class="row float-right">
								<div class="col col-12">
									<a href="javascript:void(0);" onclick="editFAQ('<?= $faq->id; ?>')" class="btn btn-info" data-toggle="modal" data-target="#editFaq" data-backdrop="static"><i class="fa fa-edit"></i></a>
									<a href="javascript:void(0);" class="btn btn-danger" onclick="alert_confirm('<?= base_url('admin/faq/destroy/' . $faq->id); ?>', '<?= lang('alert_close'); ?>', '<?= $textSexyAlert; ?>', '<?= lang('close'); ?>', '<?= lang('success_deleted_success'); ?>')"><i class="fa fa-trash"></i></a>
								</div>
							</div>
						</div>

						<div id="collapse<?= $faq->id; ?>" class="collapse bg-blue-light" aria-labelledby="headingOne" data-parent="#accordionContent">
							<div class="card-body text-blue-light" id="content_faq<?= $faq->id; ?>">
								<?= nl2br(strip_tags($faq->content)); ?>
							</div>
							<div id="strip_tags<?= $faq->id; ?>" class="d-none">
								<?= strip_tags($faq->content); ?>
							</div>
						</div>
					</div>
				<?php
				endforeach;
				?>
			</div>
		</div>
	</div>
</div>

<!-- Modal Add new FAQ -->
<div class="modal fade" id="addFaq" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content bg-white">
			<div class="modal-header">
				<h4 class="modal-title font-weight-bold text-dark"><?= lang("add_new"); ?></h4>
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

				<?= form_open('admin/faq', ['id' => 'add-faq']); ?>
				<div class="form-group">
					<?php
					echo form_label(lang("title"), 'title', [
						'class' => 'form-text font-weight-bold'
					]);

					echo form_input([
						'name' => 'title',
						'class' => 'form-control',
						'type' => 'text',
						'value' => set_value('title'),
						'placeholder' => lang("title")
					]);
					?>
				</div>
				<div class="form-group">
					<?php
					echo form_label(lang("description"), 'description', [
						'class' => 'form-text font-weight-bold'
					]);

					echo form_textarea([
						'name' => 'description',
						'class' => 'form-control',
						'value' => set_value('description'),
						'placeholder' => lang("description"),
						'rows' => '5'
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

<!-- Modal Edit FAQ -->
<div class="modal fade" id="editFaq" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content bg-white">
			<div class="modal-header">
				<h4 class="modal-title font-weight-bold text-dark"><?= lang("edit_faq"); ?></h4>
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

				<?= form_open('', ['id' => 'edit-faq']); ?>
				<div class="form-group">
					<?php
					echo form_label(lang("title"), 'title_edit', [
						'class' => 'form-text font-weight-bold'
					]);

					echo form_input([
						'name' => 'title_edit',
						'class' => 'form-control',
						'value' => set_value('title_edit'),
						'type' => 'text',
					]);
					?>
				</div>
				<div class="form-group">
					<?php
					echo form_label(lang("description"), 'description_edit', [
						'class' => 'form-text font-weight-bold'
					]);

					echo form_textarea([
						'name' => 'description_edit',
						'class' => 'form-control',
						'value' => set_value('description_edit'),
						'rows' => '5'
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