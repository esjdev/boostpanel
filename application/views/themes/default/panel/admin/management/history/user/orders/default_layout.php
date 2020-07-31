<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="row justify-content-center">
	<div class="col-sm-12">
		<div class="input-group bg-dark rounded">
			<?php
			echo form_input([
				'name' => 'searchOrders',
				'class' => 'form-control searchOrdersAjax bg-dark text-white border-0',
				'type' => 'text',
				'data-url' => 'orders/search',
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
					<a class="nav-link <?= (active('orders') ? active('orders') : active('orders/' . uri(2))); ?>" href="<?= base_url("orders"); ?>">
						<i class="fa fa-list-ul"></i> <?= lang("text_status_all"); ?>
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link <?= (active('orders/pending') ? active('orders/pending') : active('orders/pending/' . uri(3))); ?>" href="<?= base_url("orders/pending"); ?>">
						<i class="fa fa-clock-o"></i> <?= lang("status_pending"); ?>
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link <?= (active('orders/processing') ? active('orders/processing') : active('orders/processing/' . uri(3))); ?>" href="<?= base_url("orders/processing"); ?>">
						<i class="fa fa-bar-chart"></i> <?= lang("status_processing"); ?>
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link <?= (active('orders/inprogress') ? active('orders/inprogress') : active('orders/inprogress/' . uri(3))); ?>" href="<?= base_url("orders/inprogress"); ?>">
						<i class="fa fa-spinner"></i> <?= lang("status_inprocess"); ?>
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link <?= (active('orders/completed') ? active('orders/completed') : active('orders/completed/' . uri(3))); ?>" href="<?= base_url("orders/completed"); ?>">
						<i class="fa fa-check"></i> <?= lang("status_completed"); ?>
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link <?= (active('orders/partial') ? active('orders/partial') : active('orders/partial/' . uri(3))); ?>" href="<?= base_url("orders/partial"); ?>">
						<i class="fa fa-hourglass-half"></i> <?= lang("status_partial"); ?>
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link <?= (active('orders/canceled') ? active('orders/canceled') : active('orders/canceled/' . uri(3))); ?>" href="<?= base_url("orders/canceled"); ?>">
						<i class="fa fa-times-circle"></i> <?= lang("status_canceled"); ?>
					</a>
				</li>
			</ul>
		</div>
	</div>
</div>