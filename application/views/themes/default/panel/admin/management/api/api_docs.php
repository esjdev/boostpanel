<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="<?= (logged() ? 'w-75 container-fluid' : 'container'); ?> padding_top api-documentation">
    <div class="row justify-content-center" data-aos="fade-up">
        <div class="col-sm-12 mt-3">
            <div class="section_tittle text-center">
                <p><?= lang("menu_api_documentation"); ?></p>
                <h2><?= lang("menu_api"); ?></h2>
            </div>

            <div class="table-responsive">
                <table class="table border mt-4">
                    <tbody>
                        <tr>
                            <td class="font-weight-bold text-dark cursor-pointer border"><?= lang("http_method"); ?></td>
                            <td>POST</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold text-dark cursor-pointer border"><?= lang("api_url"); ?></td>
                            <td><?= base_url("api/v2"); ?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold text-dark cursor-pointer border"><?= lang("response_format"); ?></td>
                            <td>JSON</td>
                        </tr>
                        <?php if (logged()) : ?>
                            <tr>
                                <td class="font-weight-bold text-dark cursor-pointer border"><?= lang("api_key"); ?></td>
                                <td><?= (userLevel(logged(), 'user') ? dataUser(logged(), 'api_key') : lang('hidden')); ?></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <div class="card-footer border">
                    <div class="row">
                        <div class="col-sm-6 font-weight-bold mt-2"><?= lang("download_examples"); ?></div>
                        <div class="col-sm-6 text-sm-right">
                            <a href="<?= base_url('example.txt'); ?>" class="btn btn-green" target="_blank">
                                <i class="fa fa-cloud-download"></i> <?= lang("php_code"); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <h3 class="text-dark mt-4 cursor-pointer font-weight-bold"><?= lang("service_list"); ?></h3>
            <div class="table-responsive">
                <table class="table border-bottom">
                    <thead>
                        <tr>
                            <th class="font-weight-bold text-white bg-linear cursor-pointer"><?= lang("parameters"); ?></th>
                            <th class="font-weight-bold text-white bg-linear border-left cursor-pointer"><?= lang("description"); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="font-weight-bold text-dark">key</td>
                            <td class="border-left"><?= lang("api_key"); ?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold text-dark">action</td>
                            <td class="border-left">services</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <p><strong><?= lang("example_response"); ?></strong></p>
            <pre>[
    {
        "service": "1",
        "name": "Followers",
        "type": "Default",
        "rate": "5.12",
        "min": "25",
        "max": "100000",
        "dripfeed": true,
        "category": "First Category"
    },
    {
        "service": "2",
        "name": "Comments",
        "type": "Custom Comments",
        "rate": "12.42",
        "min": "100",
        "max": "1000",
        "dripfeed": false,
        "category": "Second Category"
    }
]
</pre>

            <h3 class="text-dark mt-4 cursor-pointer font-weight-bold"><?= lang("add_order"); ?></h3>
            <form class="form-inline">
                <div class="form-group">
                    <select class="form-control mb-2" id="service_type_api_docs">
                        <option value="0">Default</option>
                        <option value="1">Custom Data</option>
                        <option value="2">Subscriptions</option>
                        <option value="3">Custom Comments</option>
                        <option value="4">Custom Comments Package</option>
                        <option value="5">Mentions With Hashtags</option>
                        <option value="6">Mentions Custom List</option>
                        <option value="7">Mentions Hashtag</option>
                        <option value="8">Mentions User Followers</option>
                        <option value="9">Mentions Media Likers</option>
                        <option value="10">Package</option>
                        <option value="11">Comment Likes</option>
                        <option value="12">Poll</option>
                        <option value="13">SEO</option>
                    </select>
                </div>
            </form>

            <div class="service_type_0">
                <div class="table-responsive">
                    <table class="table border-bottom">
                        <thead>
                            <tr>
                                <th class="font-weight-bold text-white bg-linear cursor-pointer"><?= lang("parameters"); ?></th>
                                <th class="font-weight-bold text-white bg-linear border-left cursor-pointer"><?= lang("description"); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="font-weight-bold text-dark">key</td>
                                <td class="border-left"><?= lang("api_key"); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">action</td>
                                <td class="border-left">add</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">service</td>
                                <td class="border-left"><?= lang("service_id"); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">link</td>
                                <td class="border-left"><?= lang("link_to_page"); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">quantity</td>
                                <td class="border-left"><?= lang("needed_quantity"); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">runs <b class='text-danger'>(<?= lang("optional"); ?>)</b></td>
                                <td class="border-left"><?= lang("runs_to_deliver"); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">interval <b class='text-danger'>(<?= lang("optional"); ?>)</b></td>
                                <td class="border-left"><?= lang("interval_in_minutes"); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="service_type_1" style="display:none;">
                <div class="table-responsive">
                    <table class="table border-bottom">
                        <thead>
                            <tr>
                                <th class="font-weight-bold text-white bg-linear cursor-pointer"><?= lang("parameters"); ?></th>
                                <th class="font-weight-bold text-white bg-linear border-left cursor-pointer"><?= lang("description"); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="font-weight-bold text-dark">key</td>
                                <td class="border-left"><?= lang("api_key"); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">action</td>
                                <td class="border-left">add</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">service</td>
                                <td class="border-left"><?= lang("service_id"); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">link</td>
                                <td class="border-left"><?= lang("link_to_page"); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">quantity</td>
                                <td class="border-left"><?= lang("needed_quantity"); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">custom_data</td>
                                <td class="border-left"><?= lang("list_separated_by"); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="service_type_2" style="display:none;">
                <div class="table-responsive">
                    <table class="table border-bottom">
                        <thead>
                            <tr>
                                <th class="font-weight-bold text-white bg-linear cursor-pointer"><?= lang("parameters"); ?></th>
                                <th class="font-weight-bold text-white bg-linear border-left cursor-pointer"><?= lang("description"); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="font-weight-bold text-dark">key</td>
                                <td class="border-left"><?= lang("api_key"); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">action</td>
                                <td class="border-left">add</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">service</td>
                                <td class="border-left"><?= lang("service_id"); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">username</td>
                                <td class="border-left"><?= lang("username"); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">min</td>
                                <td class="border-left"><?= lang("quantity_min"); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">max</td>
                                <td class="border-left"><?= lang("quantity_max"); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">posts</td>
                                <td class="border-left"><?= lang("new_posts_count"); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">delay</td>
                                <td class="border-left"><?= lang("delay_in_minutes"); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark"><?= lang("expire"); ?> <b class='text-danger'>(<?= lang("optional"); ?>)</b></td>
                                <td class="border-left"><?= lang("expiry_date"); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="service_type_3" style="display:none;">
                <div class="table-responsive">
                    <table class="table border-bottom">
                        <thead>
                            <tr>
                                <th class="font-weight-bold text-white bg-linear cursor-pointer"><?= lang("parameters"); ?></th>
                                <th class="font-weight-bold text-white bg-linear border-left cursor-pointer"><?= lang("description"); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="font-weight-bold text-dark">key</td>
                                <td class="border-left"><?= lang("api_key"); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">action</td>
                                <td class="border-left">add</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">service</td>
                                <td class="border-left"><?= lang("service_id"); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">link</td>
                                <td class="border-left"><?= lang("link_to_page"); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">comments</td>
                                <td class="border-left"><?= lang("list_separated_by"); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="service_type_4" style="display:none;">
                <div class="table-responsive">
                    <table class="table border-bottom">
                        <thead>
                            <tr>
                                <th class="font-weight-bold text-white bg-linear cursor-pointer"><?= lang("parameters"); ?></th>
                                <th class="font-weight-bold text-white bg-linear border-left cursor-pointer"><?= lang("description"); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="font-weight-bold text-dark">key</td>
                                <td class="border-left"><?= lang("api_key"); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">action</td>
                                <td class="border-left">add</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">service</td>
                                <td class="border-left"><?= lang("service_id"); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">link</td>
                                <td class="border-left"><?= lang("link_to_page"); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">comments</td>
                                <td class="border-left"><?= lang("list_separated_by"); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="service_type_5" style="display:none;">
                <div class="table-responsive">
                    <table class="table border-bottom">
                        <thead>
                            <tr>
                                <th class="font-weight-bold text-white bg-linear cursor-pointer"><?= lang("parameters"); ?></th>
                                <th class="font-weight-bold text-white bg-linear border-left cursor-pointer"><?= lang("description"); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="font-weight-bold text-dark">key</td>
                                <td class="border-left"><?= lang("api_key"); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">action</td>
                                <td class="border-left">add</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">service</td>
                                <td class="border-left"><?= lang("service_id"); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">link</td>
                                <td class="border-left"><?= lang("link_to_page"); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">quantity</td>
                                <td class="border-left"><?= lang("needed_quantity"); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">usernames</td>
                                <td class="border-left"><?= lang("list_separated_by"); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">hashtags</td>
                                <td class="border-left"><?= lang("list_separated_by"); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="service_type_6" style="display:none;">
                <div class="table-responsive">
                    <table class="table border-bottom">
                        <thead>
                            <tr>
                                <th class="font-weight-bold text-white bg-linear cursor-pointer"><?= lang("parameters"); ?></th>
                                <th class="font-weight-bold text-white bg-linear border-left cursor-pointer"><?= lang("description"); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="font-weight-bold text-dark">key</td>
                                <td class="border-left"><?= lang("api_key"); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">action</td>
                                <td class="border-left">add</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">service</td>
                                <td class="border-left"><?= lang("service_id"); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">link</td>
                                <td class="border-left"><?= lang("link_to_page"); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">usernames</td>
                                <td class="border-left"><?= lang("list_separated_by"); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="service_type_7" style="display:none;">
                <div class="table-responsive">
                    <table class="table border-bottom">
                        <thead>
                            <tr>
                                <th class="font-weight-bold text-white bg-linear cursor-pointer"><?= lang("parameters"); ?></th>
                                <th class="font-weight-bold text-white bg-linear border-left cursor-pointer"><?= lang("description"); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="font-weight-bold text-dark">key</td>
                                <td class="border-left"><?= lang("api_key"); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">action</td>
                                <td class="border-left">add</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">service</td>
                                <td class="border-left"><?= lang("service_id"); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">link</td>
                                <td class="border-left"><?= lang("link_to_page"); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">quantity</td>
                                <td class="border-left"><?= lang("needed_quantity"); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">hashtag</td>
                                <td class="border-left"><?= lang("hashtag_scrape_usernames"); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="service_type_8" style="display:none;">
                <div class="table-responsive">
                    <table class="table border-bottom">
                        <thead>
                            <tr>
                                <th class="font-weight-bold text-white bg-linear cursor-pointer"><?= lang("parameters"); ?></th>
                                <th class="font-weight-bold text-white bg-linear border-left cursor-pointer"><?= lang("description"); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="font-weight-bold text-dark">key</td>
                                <td class="border-left"><?= lang("api_key"); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">action</td>
                                <td class="border-left">add</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">service</td>
                                <td class="border-left"><?= lang("service_id"); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">link</td>
                                <td class="border-left"><?= lang("link_to_page"); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">quantity</td>
                                <td class="border-left"><?= lang("needed_quantity"); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">username</td>
                                <td class="border-left"><?= lang("url_scrape_followers"); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="service_type_9" style="display:none;">
                <div class="table-responsive">
                    <table class="table border-bottom">
                        <thead>
                            <tr>
                                <th class="font-weight-bold text-white bg-linear cursor-pointer"><?= lang("parameters"); ?></th>
                                <th class="font-weight-bold text-white bg-linear border-left cursor-pointer"><?= lang("description"); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="font-weight-bold text-dark">key</td>
                                <td class="border-left"><?= lang("api_key"); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">action</td>
                                <td class="border-left">add</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">service</td>
                                <td class="border-left"><?= lang("service_id"); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">link</td>
                                <td class="border-left"><?= lang("link_to_page"); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">quantity</td>
                                <td class="border-left"><?= lang("needed_quantity"); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">media</td>
                                <td class="border-left"><?= lang("media_url_scrape_likers"); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="service_type_10" style="display:none;">
                <div class="table-responsive">
                    <table class="table border-bottom">
                        <thead>
                            <tr>
                                <th class="font-weight-bold text-white bg-linear cursor-pointer"><?= lang("parameters"); ?></th>
                                <th class="font-weight-bold text-white bg-linear border-left cursor-pointer"><?= lang("description"); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="font-weight-bold text-dark">key</td>
                                <td class="border-left"><?= lang("api_key"); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">action</td>
                                <td class="border-left">add</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">service</td>
                                <td class="border-left"><?= lang("service_id"); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">link</td>
                                <td class="border-left"><?= lang("link_to_page"); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="service_type_11" style="display:none;">
                <div class="table-responsive">
                    <table class="table border-bottom">
                        <thead>
                            <tr>
                                <th class="font-weight-bold text-white bg-linear cursor-pointer"><?= lang("parameters"); ?></th>
                                <th class="font-weight-bold text-white bg-linear border-left cursor-pointer"><?= lang("description"); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="font-weight-bold text-dark">key</td>
                                <td class="border-left"><?= lang("api_key"); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">action</td>
                                <td class="border-left">add</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">service</td>
                                <td class="border-left"><?= lang("service_id"); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">link</td>
                                <td class="border-left"><?= lang("link_to_page"); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">quantity</td>
                                <td class="border-left"><?= lang("needed_quantity"); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">username</td>
                                <td class="border-left"><?= lang("username_comment_owner"); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="service_type_12" style="display:none;">
                <div class="table-responsive">
                    <table class="table border-bottom">
                        <thead>
                            <tr>
                                <th class="font-weight-bold text-white bg-linear cursor-pointer"><?= lang("parameters"); ?></th>
                                <th class="font-weight-bold text-white bg-linear border-left cursor-pointer"><?= lang("description"); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="font-weight-bold text-dark">key</td>
                                <td class="border-left"><?= lang("api_key"); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">action</td>
                                <td class="border-left">add</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">service</td>
                                <td class="border-left"><?= lang("service_id"); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">link</td>
                                <td class="border-left"><?= lang("link_to_page"); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">quantity</td>
                                <td class="border-left"><?= lang("needed_quantity"); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">answer_number</td>
                                <td class="border-left"><?= lang("answer_number_poll"); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="service_type_13" style="display:none;">
                <div class="table-responsive">
                    <table class="table border-bottom">
                        <thead>
                            <tr>
                                <th class="font-weight-bold text-white bg-linear cursor-pointer"><?= lang("parameters"); ?></th>
                                <th class="font-weight-bold text-white bg-linear border-left cursor-pointer"><?= lang("description"); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="font-weight-bold text-dark">key</td>
                                <td class="border-left"><?= lang("api_key"); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">action</td>
                                <td class="border-left">add</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">service</td>
                                <td class="border-left"><?= lang("service_id"); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">link</td>
                                <td class="border-left"><?= lang("link_to_page"); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">quantity</td>
                                <td class="border-left"><?= lang("needed_quantity"); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-dark">keywords</td>
                                <td class="border-left"><?= lang("list_separated_by"); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <p><strong><?= lang("example_response"); ?></strong></p>
            <pre>{
    "order": 26
}
</pre>

            <h3 class="text-dark mt-4 cursor-pointer font-weight-bold"><?= lang("order_status"); ?></h3>
            <div class="table-responsive">
                <table class="table border-bottom">
                    <thead>
                        <tr>
                            <th class="font-weight-bold text-white bg-linear cursor-pointer"><?= lang("parameters"); ?></th>
                            <th class="font-weight-bold text-white bg-linear border-left cursor-pointer"><?= lang("description"); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="font-weight-bold text-dark">key</td>
                            <td class="border-left"><?= lang("api_key"); ?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold text-dark">action</td>
                            <td class="border-left">status</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold text-dark">order</td>
                            <td class="border-left"><?= lang("input_order_id"); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <p><strong><?= lang("example_response_order"); ?></strong></p>
                    <pre>{
    "charge": "5.0000",
    "start_count": "5763",
    "status": "Processing",
    "remains": "172",
    "currency": "USD"
}
</pre>
                </div>

                <div class="col-sm-6">
                    <p><strong><?= lang("example_response_subscription"); ?></strong></p>
                    <pre>{
    "status": "Active",
    "expiry": null,
    "posts": "1",
    "orders": [
        "22"
    ]
}
</pre>
                </div>
            </div>

            <h3 class="text-dark mt-4 cursor-pointer font-weight-bold"><?= lang("multiple_orders_status"); ?></h3>
            <div class="table-responsive">
                <table class="table border-bottom">
                    <thead>
                        <tr>
                            <th class="font-weight-bold text-white bg-linear cursor-pointer"><?= lang("parameters"); ?></th>
                            <th class="font-weight-bold text-white bg-linear border-left cursor-pointer"><?= lang("description"); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="font-weight-bold text-dark">key</td>
                            <td class="border-left"><?= lang("api_key"); ?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold text-dark">action</td>
                            <td class="border-left">status</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold text-dark">orders</td>
                            <td class="border-left"><?= lang("order_ids_separated_by_comma"); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <p><strong><?= lang("example_response"); ?></strong></p>
            <pre>{
    "1": {
        "charge": "0.0231",
        "start_count": "0",
        "status": "Pending",
        "remains": "0",
        "currency": "USD"
    },
    "3": {
        "error": "Incorrect Order ID"
    },
    "10": {
        "charge": "10.0000",
        "start_count": "52",
        "status": "Completed",
        "remains": "128",
        "currency": "USD"
    }
}
</pre>

            <h3 class="text-dark mt-4 cursor-pointer font-weight-bold"><?= lang("user_balance"); ?></h3>
            <div class="table-responsive">
                <table class="table border-bottom">
                    <thead>
                        <tr>
                            <th class="font-weight-bold text-white bg-linear cursor-pointer"><?= lang("parameters"); ?></th>
                            <th class="font-weight-bold text-white bg-linear border-left cursor-pointer"><?= lang("description"); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="font-weight-bold text-dark">key</td>
                            <td class="border-left"><?= lang("api_key"); ?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold text-dark">action</td>
                            <td class="border-left">balance</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <p><strong><?= lang("example_response"); ?></strong></p>
            <pre>{
    "balance": "56.61292",
    "currency": "USD"
}
</pre>
        </div>
    </div>
</div>

<script src="<?= set_js('api_docs.min.js'); ?>"></script>
