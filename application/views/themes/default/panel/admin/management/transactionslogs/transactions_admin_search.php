<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php if (!empty($search_transaction)) : ?>
    <div class="tab-content table-responsive-lg table-transactions">
        <table class="table table-hover">
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
                <?php foreach ($search_transaction as $value) : $textSexyAlert = str_replace("'", "\'", lang('confirm_close_message')); ?>
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
<?php else : ?>
    <div class='bg-danger text-white p-2 rounded'><?= lang('error_nothing_found'); ?></div>
<?php endif; ?>