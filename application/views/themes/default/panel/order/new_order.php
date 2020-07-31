<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="w-75 container-fluid padding_top">
	<div class="row justify-content-center">
		<div class="col-sm-7 mt-3">
			<div class="section_tittle text-center" data-aos="fade-up">
				<p><?= lang("place_new_order"); ?></p>
				<h2><?= lang("menu_new_order"); ?></h2>
			</div>

			<div class="alert alert-danger alert-dismissible rounded error" style="display:none;" role="alert">
				<i class="fa fa-exclamation-triangle"></i> <span class="error-message"></span>
				<a class="close cursor-pointer" aria-label="close">&times;</a>
			</div> <!-- Alert error -->

			<div class="alert alert-success alert-dismissible rounded success" style="display:none;" role="alert">
				<i class="fa fa-thumbs-up"></i> <span class="success-message"></span>
				<a class="close cursor-pointer" aria-label="close">&times;</a>
			</div> <!-- Alert success -->

			<ul class="nav nav-tabs mb-4 fs-14">
				<li class="nav-item">
					<a class="nav-link border shadow-sm font-weight-bold active" data-toggle="tab" href="#order">
						<i class="fa fa-clone"></i> <?= lang("menu_new_order"); ?>
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link ml-1 border shadow-sm font-weight-bold" data-toggle="tab" href="#massOrder">
						<i class="fa fa-sitemap"></i> <?= lang("mass_order"); ?>
					</a>
				</li>
			</ul>

			<div class="tab-content">
				<div id="order" class="tab-pane active">
					<?= form_open('order/store', ['id' => 'add-new-order']); ?>

					<?php if (!in_array($user_custom_rate, [0, '', null])) : ?>
						<div class="bg-info p-2 text-white rounded mb-3 text-center">
							<?= sprintf(lang("discount_total_amount"), dataUser(logged(), 'custom_rate') . '%'); ?>
						</div>
					<?php endif; ?>

					<div class="form-group">
						<?php
						echo form_label(lang("menu_category"), 'category', [
							'class' => 'form-text font-weight-bold'
						]);

						list_category('category');

						echo '<div id="no_service_category" class="bg-danger text-white p-2 rounded d-none mt-2">' . lang("error_service_no_exists_category") . '</div>';
						?>
					</div>
					<div class="form-group services-input d-none">
						<?php
						echo form_label(lang("menu_services"), 'services', [
							'class' => 'form-text font-weight-bold'
						]);

						echo "<div id='select_services'></div>";
						?>
					</div>
					<div class="form-group description-service d-none">
						<?php
						echo form_label(lang("description"), '', [
							'class' => 'form-text font-weight-bold'
						]);

						echo form_textarea([
							'name' => 'description_service',
							'class' => 'form-control',
							'id' => 'description_service',
							'value' => '',
							'rows' => '5',
							'placeholder' => lang("description"),
							'disabled' => 'disabled',
						]);
						?>
					</div>
					<div class="form-group link-no d-none">
						<?php
						echo form_label(lang("link"), 'link', [
							'class' => 'form-text font-weight-bold'
						]);

						echo form_input([
							'name' => 'link',
							'class' => 'form-control',
							'type' => 'text',
							'value' => set_value("link")
						]);
						?>
					</div>
					<div class="form-group quantity-no d-none">
						<?php
						echo form_label(lang("quantity"), 'quantity', [
							'class' => 'form-text font-weight-bold'
						]);

						echo form_input([
							'name' => 'quantity',
							'class' => 'form-control',
							'id' => 'quantityService',
							'type' => 'number',
							'value' => set_value("quantity")
						]);
						?>
					</div>
					<div class="form-group answer_number_poll d-none">
						<?php
						echo form_label(lang("answer_number"), 'poll_answer_number', [
							'class' => 'form-text font-weight-bold'
						]);

						echo form_input([
							'name' => 'poll_answer_number',
							'class' => 'form-control',
							'type' => 'text',
							'value' => '',
						]);
						?>
					</div>
					<div class="form-group seo_keywords d-none">
						<?php
						echo form_label(lang("keywords_seo"), 'seo_keywords', [
							'class' => 'form-text font-weight-bold'
						]);

						echo form_input([
							'name' => 'seo_keywords',
							'class' => 'form-control',
							'type' => 'text',
							'value' => '',
							'id' => 'tagsinput_keywords',
						]);
						?>
					</div>
					<div class="form-group comments-custom d-none">
						<?php
						echo form_label(lang("comments"), 'comments_custom_package', [
							'class' => 'form-text font-weight-bold'
						]);

						echo form_input([
							'name' => 'comments_custom_package',
							'class' => 'form-control',
							'type' => 'text',
							'value' => '',
							'id' => 'tagsinput_comments',
						]);
						?>
					</div>
					<div class="form-group usernames_hashtags d-none">
						<?php
						echo form_label(lang("usernames"), 'usernames_hashtags', [
							'class' => 'form-text font-weight-bold'
						]);

						echo form_input([
							'name' => 'usernames_hashtags',
							'class' => 'form-control',
							'type' => 'text',
							'value' => '',
							'id' => 'tagsinput_usernames_hashtags',
						]);
						?>
					</div>
					<div class="form-group usernames d-none">
						<?php
						echo form_label(lang("usernames"), 'usernames', [
							'class' => 'form-text font-weight-bold'
						]);

						echo form_input([
							'name' => 'usernames',
							'class' => 'form-control',
							'type' => 'text',
							'value' => '',
							'id' => 'tagsinput_usernames',
						]);
						?>
					</div>
					<div class="form-group mentions_with_hashtags d-none">
						<?php
						echo form_label(lang("hashtags_new_order"), 'mentions_with_hashtags', [
							'class' => 'form-text font-weight-bold'
						]);

						echo form_input([
							'name' => 'mentions_with_hashtags',
							'class' => 'form-control',
							'type' => 'text',
							'value' => '',
							'id' => 'tagsinput_hashtags',
						]);
						?>
					</div>
					<div class="form-group mentions_with_hashtag d-none">
						<?php
						echo form_label(lang("hashtag_new_order"), 'mentions_with_hashtag', [
							'class' => 'form-text font-weight-bold'
						]);

						echo form_input([
							'name' => 'mentions_with_hashtag',
							'class' => 'form-control',
							'type' => 'text',
							'value' => '',
							'placeholder' => '#hashtag',
						]);
						?>
					</div>
					<div class="form-group username-follower d-none">
						<?php
						echo form_label(lang("input_username"), 'username_follower', [
							'class' => 'form-text font-weight-bold'
						]);

						echo form_input([
							'name' => 'username_follower',
							'class' => 'form-control',
							'type' => 'text',
							'value' => set_value('username_follower'),
						]);
						?>
					</div>
					<div class="form-group media_url d-none">
						<?php
						echo form_label(lang("media_url_new_order"), 'media_url', [
							'class' => 'form-text font-weight-bold'
						]);

						echo form_input([
							'name' => 'media_url',
							'class' => 'form-control',
							'type' => 'text',
							'value' => set_value('media_url'),
						]);
						?>
					</div>
					<div class="form-group dripfeed d-none">
						<div class="bg-info p-2 p-l-50 rounded">
							<div class="custom-control custom-checkbox">
								<input type="checkbox" class="custom-control-input" id="dripfeedCheck" name="dripfeed">
								<label class="custom-control-label font-weight-bold text-white pt-1" for="dripfeedCheck"><?= lang("receive_dripfeed_new_order"); ?></label>
							</div>
						</div>

						<div class="form-group forms-dripfeed d-none">
							<div class="row">
								<div class="col-6">
									<?php
									echo form_label(lang("runs_new_order"), 'runs', [
										'class' => 'form-text font-weight-bold'
									]);

									echo form_input([
										'name' => 'runs',
										'class' => 'form-control',
										'id' => 'quantityRuns',
										'type' => 'number',
										'value' => set_value('runs'),
									]);
									?>
								</div>
								<div class="col-6">
									<?php
									echo form_label(lang("interval_new_order"), 'interval', [
										'class' => 'form-text font-weight-bold'
									]);

									echo form_dropdown('interval', [
										'10' => '10',
										'20' => '20',
										'30' => '30',
										'40' => '40',
										'50' => '50',
										'60' => '60',
									], '10', 'class="form-control"');
									?>
								</div>
								<div class="col-12">
									<?php
									echo form_label(lang("total_quantity_new_order"), 'total_quantity_dripfeed', [
										'class' => 'form-text font-weight-bold'
									]);

									echo form_input([
										'name' => 'total_quantity_dripfeed',
										'class' => 'form-control',
										'type' => 'text',
										'value' => '0',
										'readonly' => 'readonly',
									]);
									?>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group subscriptions d-none">
						<div class="row">
							<div class="col-6">
								<?php
								echo form_label(lang("input_username"), 'username_subscriptions', [
									'class' => 'form-text font-weight-bold'
								]);

								echo form_input([
									'name' => 'username_subscriptions',
									'class' => 'form-control',
									'type' => 'text',
									'value' => set_value('username_subscriptions'),
								]);
								?>
							</div>
							<div class="col-6">
								<?php
								echo form_label(lang("new_posts_order"), 'new_posts_subs', [
									'class' => 'form-text font-weight-bold'
								]);

								echo form_input([
									'name' => 'new_posts_subs',
									'class' => 'form-control',
									'type' => 'text',
									'value' => set_value('new_posts_subs'),
									'placeholder' => 'Minimum 1 post',
								]);
								?>
							</div>
							<div class="col-6">
								<?php
								echo form_label(lang("quantity"), 'quantity_subscription_min', [
									'class' => 'form-text font-weight-bold'
								]);

								echo form_input([
									'name' => 'quantity_subscription_min',
									'class' => 'form-control',
									'type' => 'number',
									'placeholder' => 'min',
								]);
								?>
							</div>
							<div class="col-6">
								<?php
								echo form_label(lang("quantity_max"), 'quantity_subscription_max', [
									'class' => 'form-text font-weight-bold'
								]);

								echo form_input([
									'name' => 'quantity_subscription_max',
									'class' => 'form-control',
									'type' => 'number',
									'placeholder' => 'max',
								]);
								?>
							</div>
							<div class="col-6">
								<?php
								echo form_label(lang("delay_new_order"), 'delay_subscription', [
									'class' => 'form-text font-weight-bold'
								]);

								echo form_dropdown('delay_subscription', [
									'0' => lang("no_delay"),
									'5' => '5',
									'10' => '10',
									'15' => '15',
									'30' => '30',
									'60' => '60',
									'90' => '90',
								], '0', 'class="form-control"');
								?>
							</div>
							<div class="col-6">
								<?php
								echo form_label(lang("expire"), 'expiry_subscription', [
									'class' => 'form-text font-weight-bold'
								]);

								echo '<div class="input-group">';
								echo form_input([
									'name' => 'expiry_subscription',
									'class' => 'form-control datepicker pl-3',
									'id' => 'expiry',
									'type' => 'text',
									'onkeydown' => 'return false',
									'placeholder' => 'Date',
								]);

								echo '<span class="input-group-append">
											<button class="btn btn-secondary" type="button" onclick="document.getElementById(\'expiry\').value=\'\'"><i class="fa fa-trash fa-1x"></i></button>
									</span>';
								echo '</div>';
								?>
							</div>
						</div>
					</div>
					<div class="form-group text-min-max d-none">
						<div class="row">
							<div class="col-6">
								<span class="badge badge-danger text-white font-weight-bold fs-15"><?= lang("min"); ?>: <span id="min">0</span></span>
							</div>
							<div class="col-6">
								<span class="badge badge-danger text-white font-weight-bold fs-15"><?= lang("max"); ?>: <span id="max">0</span></span>
							</div>
						</div>
					</div>
					<div class="form-group total-price-new-order d-none">
						<div class="row">
							<div class="col-6">
								<span class="badge badge-primary fs-17"><?= lang("total_price"); ?>: <?= configs('currency_symbol', 'value') . "<span id='priceTotal' class='font-weight-normal'>0.00</span>"; ?></span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<?php
						echo form_submit([
							'class' => 'genric-btn info-green e-large btn-block radius fs-16',
							'type' => 'submit',
							'id' => 'submit-order',
							'value' => lang("button_submit")
						]);
						?>
					</div>
					<?= form_close(); ?>
				</div>

				<div id="massOrder" class="tab-pane fade">
					<?= form_open('order/mass', ['id' => 'mass-order']); ?>

					<?php if (!in_array($user_custom_rate, [0, '', null])) : ?>
						<div class="bg-info p-2 text-white rounded mb-3 text-center">
							<?= sprintf(lang("discount_total_amount"), dataUser(logged(), 'custom_rate') . '%'); ?>
						</div>
					<?php endif; ?>


					<div class="form-group">
						<?php
						echo form_label(lang("order_per_line"), 'mass_order', [
							'class' => 'form-text font-weight-bold'
						]);

						echo form_textarea([
							'name' => 'mass_order',
							'class' => 'form-control',
							'value' => set_value("mass_order"),
							'placeholder' => "service_id | quantity | link",
							'rows' => '15'
						]);
						?>
					</div>
					<div class="form-group">
						<?php
						echo form_submit([
							'class' => 'genric-btn info-green e-large btn-block radius fs-16',
							'type' => 'submit',
							'value' => lang("button_submit")
						]);
						?>
					</div>
					<?= form_close(); ?>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="<?= set_js('new_order.min.js'); ?>"></script>