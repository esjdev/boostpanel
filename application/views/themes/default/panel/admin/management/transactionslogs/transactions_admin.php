<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="w-75 container-fluid padding_top">
    <div class="row justify-content-center">
        <div class="col-sm-12 mt-3">
            <div class="section_tittle text-center" data-aos="fade-up">
                <p><?= lang("transaction_list"); ?></p>
                <h2><?= lang("menu_transaction_logs"); ?></h2>
            </div>

            <?php if (flashdata('error')) : ?>
                <div class="alert alert-danger alert-dismissible rounded error mt-2" role="alert">
                    <i class="fa fa-exclamation-triangle"></i> <span class="error-message"><?= flashdata('error'); ?></span>
                    <a class="close cursor-pointer" aria-label="close">&times;</a>
                </div> <!-- Alert error -->
            <?php endif; ?>

            <div class="input-group bg-dark rounded mb-3">
                <?php
                echo form_input([
                    'name' => 'searchTransaction',
                    'class' => 'form-control searchTransactionAjax bg-dark text-white border-0',
                    'type' => 'text',
                    'data-url' => 'transaction/search',
                    'placeholder' => lang("placeholder_search_transaction"),
                ]);
                ?>
                <div class="input-group-prepend">
                    <i class="fa fa-search rounded-right text-white bg-default m-t-12 mr-3"></i>
                </div>
            </div>

            <?php if (!empty($list_transaction)) : ?>
                <div class="table-responsive-lg table-transactions">
                    <table class="table border">
                        <thead>
                            <tr>
                                <th><?= lang('user_role'); ?></th>
                                <th><?= lang('input_transaction_id'); ?></th>
                                <th><?= lang('payment_method'); ?></th>
                                <th><?= lang('amount'); ?></th>
                                <th><?= lang('created'); ?></th>
                                <th><?= lang('status'); ?></th>
                                <th class="text-center"><?= lang('action'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($list_transaction as $value) : $textSexyAlert = str_replace("'", "\'", lang('confirm_close_message')); ?>
                                <tr>
                                    <td class="border"><?= dataUserId($value->user_id, 'email'); ?></td>
                                    <td class="border"><?= $value->transaction_id; ?></td>
                                    <td class="border"><?= payment_type($value->payment_method); ?></td>
                                    <td class="border"><?= configs('currency_symbol', 'value') . currency_format($value->amount); ?></td>
                                    <td class="border"><?= $value->created_at; ?></td>
                                    <td class="border"><?= payment_status($value->status); ?></td>
                                    <td class="border text-center">
                                        <?php if (userLevel(logged(), 'admin')) : ?>
                                            <a href="javascript:void(0);" onclick="alert_confirm('<?= base_url('admin/transaction/delete/' . $value->id); ?>', '<?= lang('alert_close'); ?>', '<?= $textSexyAlert; ?>', '<?= lang('close'); ?>', '<?= lang('success_deleted_success'); ?>')"><i class="fa fa-trash fa-2x text-danger"></i>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <?= $pagination_links; ?>
            <?php else : ?>
                <div class='bg-danger text-white p-2 rounded'><?= lang('error_nothing_found'); ?></div>
            <?php endif; ?>
        </div>
    </div>
</div>