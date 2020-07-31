<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<script src="https://js.stripe.com/v3/"></script>

<div class="w-75 container-fluid padding_top">
	<div class="row justify-content-center">
		<div class="col-sm-6 mt-3">
			<div class="section_tittle text-center" data-aos="fade-up">
				<p>Stripe</p>
				<h2><?= sprintf(lang("starting_payment"), 'Stripe'); ?></h2>
			</div>

			<?= form_open('stripe/success', ['id' => 'payment-form']); ?>
			<div class="card shadow-sm">
				<div class="card-body">
					<div class="tab-content">
						<div class="payment-errors alert alert-icon alert-danger alert-dismissible d-none">
							<i class="fe fe-alert-triangle mr-2" aria-hidden="true"></i>
							<span class="payment-errors-message"></span>
						</div>

						<div class="form-group">
							<label class="form-label text-dark font-weight-bold"><?= lang('user_information'); ?></label>
							<input type="text" class="form-control" name="name" id="name" placeholder="<?= dataUser(logged(), 'name'); ?>" required autofocus readonly>
						</div>
						<div class="form-group">
							<input type="text" class="form-control" name="email" id="email" placeholder="<?= logged(); ?>" required autofocus readonly>
						</div>
						<div class="form-group">
							<label for="card-element" class="text-dark font-weight-bold">
								<?= lang('credit_or_debit_card'); ?>
							</label>
							<div id="card-element">
								<!-- A Stripe Element will be inserted here. -->
							</div>

							<!-- Used to display form errors. -->
							<div id="card-errors" class="text-danger font-weight-bold" role="alert"></div>
						</div>

						<div class="form-group">
							<button class="genric-btn info-green e-large btn-block radius fs-16" id="btnSubmit"><?= lang('pay'); ?>
								<?= configs('currency_symbol', 'value'); ?><?= currency_format(session('amount')); ?>
							</button>
						</div>

						<div class="form-group text-center mt-5">
							<img src="<?= set_image('visa.jpg'); ?>" id="visa">
							<img src="<?= set_image('mastercard.jpg'); ?>" id="mastercard">
							<img src="<?= set_image('amex.jpg'); ?>" id="amex">
						</div>
					</div>
				</div>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>

<script type="text/javascript">
	// Create a Stripe client.
	var stripe = Stripe('<?= config_payment('stripe_publishable_key', 'value'); ?>');

	// Create an instance of Elements.
	var elements = stripe.elements();

	// Custom styling can be passed to options when creating an Element.
	// (Note that this demo uses a wider set of styles than the guide below.)
	var style = {
		base: {
			color: '#32325d',
			fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
			fontSmoothing: 'antialiased',
			fontSize: '16px',
			'::placeholder': {
				color: '#aab7c4'
			}
		},
		invalid: {
			color: '#fa755a',
			iconColor: '#fa755a'
		}
	};

	// Create an instance of the card Element.
	var card = elements.create('card', {
		hidePostalCode: true,
		style: style
	});

	// Add an instance of the card Element into the `card-element` <div>.
	card.mount('#card-element');

	// Handle real-time validation errors from the card Element.
	card.addEventListener('change', function(event) {
		var displayError = document.getElementById('card-errors');
		if (event.error) {
			displayError.textContent = event.error.message;
		} else {
			displayError.textContent = '';
		}
	});

	// Handle form submission.
	var form = document.getElementById('payment-form');
	form.addEventListener('submit', function(event) {
		event.preventDefault();

		stripe.createToken(card).then(function(result) {
			if (result.error) {
				// Inform the user if there was an error.
				var errorElement = document.getElementById('card-errors');
				errorElement.textContent = result.error.message;
			} else {
				// Send the token to your server.
				stripeTokenHandler(result.token);
			}
		});
	});

	// Submit the form with the token ID.
	function stripeTokenHandler(token) {
		$('#btnSubmit').html('<?= lang('loading'); ?> ...').attr("disabled", "disabled");
		// Insert the token ID into the form so it gets submitted to the server
		var form = document.getElementById('payment-form');
		var hiddenInput = document.createElement('input');
		hiddenInput.setAttribute('type', 'hidden');
		hiddenInput.setAttribute('name', 'stripeToken');
		hiddenInput.setAttribute('value', token.id);
		form.appendChild(hiddenInput);

		// Submit the form
		form.submit();
	}
</script>