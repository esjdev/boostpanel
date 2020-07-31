<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="w-75 container-fluid padding_top">
    <div class="row justify-content-center">
        <div class="col-sm-6 mt-3">
            <div class="section_tittle text-center" data-aos="fade-up">
                <p>PayUmoney</p>
                <h2><?= sprintf(lang("starting_payment"), 'PayUmoney'); ?></h2>
            </div>

            <?= form_open('payumoney/step_two', ['id' => 'payment-payumoney']); ?>
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="tab-content">
                        <div class="alert alert-danger alert-dismissible rounded error" style="display:none;" role="alert">
                            <i class="fa fa-exclamation-triangle"></i> <span class="error-message"></span>
                            <a class="close cursor-pointer" aria-label="close">&times;</a>
                        </div> <!-- Alert error -->

                        <input type="hidden" name="product_info" id="product_info" class="form-control" value="<?= lang('adding_account_balance'); ?>">

                        <div class="form-group">
                            <label class="form-label text-dark font-weight-bold"><?= lang('user_information'); ?></label>
                            <input type="text" class="form-control" name="name" value="<?= dataUser(logged(), 'name'); ?>" readonly>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="email" value="<?= logged(); ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label class="form-label text-dark font-weight-bold"><?= lang('phone'); ?></label>
                            <input type="number" name="mobile_number" id="mobile_number" class="form-control" placeholder="<?= lang('phone'); ?>">
                        </div>

                        <div class="form-group">
                            <?php
                            echo form_submit([
                                'class' => 'genric-btn info-green e-large btn-block radius fs-16',
                                'id' => 'btnSubmit',
                                'type' => 'submit',
                                'value' => lang('next_step')
                            ]);
                            ?>
                        </div>
                        <?= form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('#payment-payumoney').on('submit', function () {
        event.preventDefault();
        $('#btnSubmit').val('<?= lang('loading'); ?> ...').attr("disabled", "disabled");

        var data = $(this).serialize();
        $.post(this.action, data, function (response) {
            if (response.type == 'error') {
                $('#btnSubmit').val('<?= lang('next_step'); ?>').removeAttr('disabled');
                $("input[name=csrf_boostpanel]").val(response.csrf);
                $('.error').attr('style', 'display:block;');
                $('.error-message').html(response.message);
            } else if (response.type == 'success') {
                $("input[name=csrf_boostpanel]").val(response.csrf);
                $('.error').attr('style', 'display:none;');
                window.location.href = response.base_url;
            }
        }, 'json');
    });
</script>