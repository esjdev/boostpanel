<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="row justify-content-center">
	<div class="col-sm-12">
		<div class="input-group bg-dark rounded">
			<?php
			echo form_input([
				'name' => 'searchAdminOrders',
				'class' => 'form-control searchAdminOrdersAjax bg-dark text-white border-0',
				'type' => 'text',
				'data-url' => '/admin/orders/search',
				'placeholder' => lang("placeholder_search_order")
			]);
			?>

			<div class="input-group-prepend">
				<i class="fa fa-search rounded-right text-white bg-default m-t-12 mr-3"></i>
			</div>
		</div>

		<div class="tabs-wrapper mt-3">
			<ul class="nav nav-justified nav-tabs font-weight-bold fs-16">
				<li class="nav-item">
					<a class="nav-link <?= (active('admin/orders') ? active('admin/orders') : active('admin/orders/' . uri(3))); ?>" href="<?= base_url("admin/orders"); ?>">
						<i class="fa fa-list-ul"></i> <?= lang("text_status_all"); ?>
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link <?= (active('admin/orders/pending') ? active('admin/orders/pending') : active('admin/orders/pending/' . uri(4))); ?>" href="<?= base_url("admin/orders/pending"); ?>">
						<i class="fa fa-clock-o"></i> <?= lang("status_pending"); ?>
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link <?= (active('admin/orders/processing') ? active('admin/orders/processing') : active('admin/orders/processing/' . uri(4))); ?>" href="<?= base_url("admin/orders/processing"); ?>">
						<i class="fa fa-bar-chart"></i> <?= lang("status_processing"); ?>
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link <?= (active('admin/orders/inprogress') ? active('admin/orders/inprogress') : active('admin/orders/inprogress/' . uri(4))); ?>" href="<?= base_url("admin/orders/inprogress"); ?>">
						<i class="fa fa-spinner"></i> <?= lang("status_inprocess"); ?>
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link <?= (active('admin/orders/completed') ? active('admin/orders/completed') : active('admin/orders/completed/' . uri(4))); ?>" href="<?= base_url("admin/orders/completed"); ?>">
						<i class="fa fa-check"></i> <?= lang("status_completed"); ?>
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link <?= (active('admin/orders/partial') ? active('admin/orders/partial') : active('admin/orders/partial/' . uri(4))); ?>" href="<?= base_url("admin/orders/partial"); ?>">
						<i class="fa fa-hourglass-half"></i> <?= lang("status_partial"); ?>
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link <?= (active('admin/orders/canceled') ? active('admin/orders/canceled') : active('admin/orders/canceled/' . uri(4))); ?>" href="<?= base_url("admin/orders/canceled"); ?>">
						<i class="fa fa-times-circle"></i> <?= lang("status_canceled"); ?>
					</a>
				</li>
			</ul>
		</div>
	</div>
</div>
