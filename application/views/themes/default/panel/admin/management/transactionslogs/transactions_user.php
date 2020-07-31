<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="w-75 container-fluid padding_top">
    <div class="row justify-content-center">
        <div class="col-sm-12 mt-3">
            <div class="section_tittle text-center" data-aos="fade-up">
                <p><?= lang("view_your_transactions"); ?></p>
                <h2><?= lang("menu_transaction_logs"); ?></h2>
            </div>

            <?php if (!empty($list_transaction)) : ?>
                <div class="table-responsive-lg">
                    <table class="table border">
                        <thead>
                            <tr>
                                <th><?= lang('input_transaction_id'); ?></th>
                                <th><?= lang('payment_method'); ?></th>
                                <th><?= lang('amount'); ?></th>
                                <th><?= lang('created'); ?></th>
                                <th><?= lang('status'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($list_transaction as $value) : ?>
                                <tr>
                                    <td class="border"><?= $value->transaction_id; ?></td>
                                    <td class="border"><?= payment_type($value->payment_method); ?></td>
                                    <td class="border"><?= configs('currency_symbol', 'value') . currency_format($value->amount); ?></td>
                                    <td class="border"><?= $value->created_at; ?></td>
                                    <td class="border"><?= payment_status($value->status); ?></td>
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