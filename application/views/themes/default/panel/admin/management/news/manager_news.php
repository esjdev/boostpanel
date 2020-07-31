<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="w-75 container-fluid padding_top">
	<div class="row justify-content-center">
		<div class="col-sm-12 mt-3">
			<div class="section_tittle text-center" data-aos="fade-up">
				<p><?= lang("menu_news_management"); ?></p>
				<h2><?= lang("news"); ?></h2>

				<a href="javascript:void(0);" class="btn btn-green btn-lg border-0 text-white mt-5" data-toggle="modal" data-target="#addNews" data-backdrop="static">
					<i class="fa fa-plus-circle text-white"></i> <?= lang("add_new"); ?>
				</a>
			</div>

			<?php if (flashdata('error')) : ?>
				<div class="alert alert-danger alert-dismissible rounded error" role="alert">
					<i class="fa fa-exclamation-triangle"></i> <span class="error-message"><?= flashdata('error'); ?></span>
					<a class="close cursor-pointer" aria-label="close">&times;</a>
				</div> <!-- Alert error -->
			<?php endif; ?>

			<?php if (!empty($list_news)) : ?>
				<div class="table-responsive-lg mt-2">
					<table class="table border">
						<thead>
							<tr>
								<th class="text-center">*</th>
								<th><?= lang("title"); ?></th>
								<th><?= lang("created"); ?></th>
								<th class="text-right"><?= lang("action"); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php $pos = $count;
							foreach ($list_news as $value) :
								$textSexyAlert = str_replace("'", "\'", lang('confirm_close_message'));

								$title = limit_str($value->title, 55, true);
								echo '<div class="content_news' . $value->id . ' d-none">' . strip_word_textarea($value->description) . '</div>';
							?>
								<tr>
									<td class="border text-center"><?= $pos++; ?></td>
									<td class="border" id="title_news<?= $value->id; ?>" data-title="<?= $value->title; ?>"><?= $title; ?></td>
									<td class="border"><?= convert_time($value->created_at, dataUser(logged(), 'timezone')); ?></td>
									<td class="border text-right">
										<div class="btn-group-sm">
											<a href="javascript:void(0);" class="btn btn-icon bg-primary border-primary text-white" data-toggle="tooltip" data-placement="bottom" title="<?= lang("edit"); ?>"><i class="fa fa-edit" data-toggle="modal" data-target="#editNews" data-backdrop="static" onclick="edit_news(<?= $value->id; ?>)"></i></a>
											<a href="javascript:void(0);" class="btn btn-icon bg-danger border-danger text-white" data-toggle="tooltip" data-placement="bottom" title="<?= lang("delete"); ?>" onclick="alert_confirm('<?= base_url('admin/news/destroy/' . $value->id); ?>', '<?= lang('alert_close'); ?>', '<?= $textSexyAlert; ?>', '<?= lang('close'); ?>', '<?= lang('success_deleted_success'); ?>')"><i class="fa fa-trash"></i></a>
										</div>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>

					<?= $pagination_links; ?>
				</div>
			<?php else : ?>
				<div class='bg-danger text-white rounded p-2'><?= lang('error_empty_news'); ?></div>
			<?php endif; ?>
		</div>
	</div>
</div>

<!-- Modal Add new News -->
<div class="modal fade" id="addNews" tabindex="-1">
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

				<?= form_open('admin/news/store', ['id' => 'add-news']); ?>
				<div class="form-group">
					<?php
					echo form_label(lang("title"), 'title_news', [
						'class' => 'form-text font-weight-bold'
					]);

					echo form_input([
						'name' => 'title_news',
						'class' => 'form-control',
						'type' => 'text',
						'value' => set_value('title_news'),
						'placeholder' => lang("title")
					]);
					?>
				</div>
				<div class="form-group">
					<?php
					echo form_label(lang("description"), '', [
						'class' => 'form-text font-weight-bold'
					]);

					echo form_textarea([
						'name' => 'news_description',
						'class' => 'form-control',
						'id' => 'newsContent',
						'rows' => '3',
					]);

					echo form_textarea([
						'id' => 'text-area-input-news-description',
						'name' => 'text-area-input-news-description',
						'class' => 'd-none',
						'value' => '',
						'rows' => '3',
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

<!-- Modal Edit News -->
<div class="modal fade" id="editNews" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content bg-white">
			<div class="modal-header">
				<h4 class="modal-title font-weight-bold text-dark"><?= lang("edit_news"); ?></h4>
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

				<?= form_open('', ['id' => 'edit-news']); ?>
				<div class="form-group">
					<?php
					echo form_label(lang("title"), 'edit_title_news', [
						'class' => 'form-text font-weight-bold'
					]);

					echo form_input([
						'name' => 'edit_title_news',
						'class' => 'form-control',
						'type' => 'text',
						'value' => set_value('edit_title_news'),
						'placeholder' => lang("title")
					]);
					?>
				</div>
				<div class="form-group">
					<?php
					echo form_label(lang("description"), '', [
						'class' => 'form-text font-weight-bold'
					]);

					echo form_textarea([
						'name' => 'edit_news_description',
						'class' => 'form-control',
						'id' => 'edit_news_description',
						'rows' => '3',
					]);

					echo form_textarea([
						'id' => 'edit-text-area-input-news-description',
						'name' => 'edit-text-area-input-news-description',
						'class' => 'd-none',
						'value' => '',
						'rows' => '3',
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

<script src="<?= set_js('plugins/ckeditor/ckeditor.js'); ?>"></script>
<script>
	var editorNewsContent = CKEDITOR.replace('newsContent', {
		height: 350
	});

	var editNewsContent = CKEDITOR.replace('edit_news_description', {
		height: 350
	});

	timer = setInterval(updateDiv, 100);

	function updateDiv() {
		$('#text-area-input-news-description').html(editorNewsContent.getData());
		$('#edit-text-area-input-news-description').html(editNewsContent.getData());
	}

	function edit_news(id) {
		let title_news = $('#title_news' + id).data('title');
		let type_news = $('#type_news' + id).data("type");
		let description_news = $('.content_news' + id).html();
		$("#edit-news").attr("action", 'news/edit/' + id);
		$('input[name=edit_title_news]').val(title_news);
		$('select[name=edit_type_news]').val(type_news);
		$('#edit-text-area-input-news-description').html(editNewsContent.setData(description_news));
	} // Edit News
</script>