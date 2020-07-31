<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Api_providers extends MY_Controller
{
    protected $table;
    protected $table_categories;
    protected $table_services;
    protected $table_api_providers;
    protected $table_news;

    public function __construct()
    {
        parent::__construct();
        if (!userLevel(logged(), 'admin')) return redirect(base_url());

        $this->table = TABLE_API_PROVIDERS;
        $this->table_categories = TABLE_CATEGORIES;
        $this->table_services = TABLE_SERVICES;
        $this->table_news = TABLE_NEWS;

        $this->load->model("panel/api_providers/apiproviders_model");
    }

    public function index()
    {
        $data = [
            'title' => lang("title_api_providers"),
            'list_api_providers' => $this->model->fetch('*', $this->table, '', 'created_at', 'desc'),
        ];

        view('layouts/auth_header', $data);
        view('panel/admin/management/api_providers/list_api_providers');
        view('layouts/auth_footer');
    }

    public function store()
    {
        if (isset($_POST) && !empty($_POST)) {
            $name_api = $this->input->post("name_api", true);
            $url_api = $this->input->post("url_api", true);
            $key_api = $this->input->post("key_api", true);
            $type_parameter = $this->input->post("type_parameter", true);
            $api_status = $this->input->post("api_status", true);

            $this->apiproviders_model->validate_form();

            $type_services = ($type_parameter == 'api_token' ? 'packages' : 'services');
            $connect = api_connect($url_api, [$type_parameter => $key_api, 'action' => $type_services], true);

            if ($this->form_validation->run() == false) {
                foreach ($this->form_validation->error_array() as $key => $value) {
                    json([
                        'csrf' => $this->security->get_csrf_hash(),
                        'type' => 'error',
                        'message' => form_error($key, false, false),
                    ]);
                }
            }

            if (!in_array($type_parameter, ['key', 'api_token'])) {
                json([
                    'csrf' => $this->security->get_csrf_hash(),
                    'type' => 'error',
                    'message' => lang("error_choose_parameter_api"),
                ]);
            }

            if ($url_api == base_url('api/v2')) {
                json([
                    'csrf' => $this->security->get_csrf_hash(),
                    'type' => 'error',
                    'message' => lang('error_cannot_add_your_own_api'),
                ]);
            }

            if (isset($connect['data'])) {
                json([
                    'csrf' => $this->security->get_csrf_hash(),
                    'type' => 'error',
                    'message' => lang("error_api_provider_does_not_exists_services"),
                ]);
            }

            if (isset($connect['error']) || isset($connect['errors']) || ($type_parameter == 'key' && !is_numeric($connect[0]['service'])) || ($type_parameter == 'api_token' && !is_numeric($connect[0]['id'])) || !isset($connect[0]['min']) && !isset($connect[0]['max'])) {
                json([
                    'csrf' => $this->security->get_csrf_hash(),
                    'type' => 'error',
                    'message' => lang("error_api_provider_does_not_exists_or_not_compatible"),
                ]);
            }

            $balance = api_connect($url_api, [$type_parameter => $key_api, 'action' => 'balance'], true);

            $status = ($api_status == 1 ? '1' : '0');
            $data = [
                'uuid' => uuid(),
                'name' => $name_api,
                'key' => $key_api,
                'url' => $url_api,
                'balance' => $balance['balance'],
                'currency' => (isset($balance['currency']) ? $balance['currency'] : ''),
                'type_parameter' => $type_parameter,
                'status' => $status,
                'created_at' => NOW,
            ];

            $this->model->insert($this->table, $data);
            json([
                'csrf' => $this->security->get_csrf_hash(),
                'type' => 'success',
                'message' => lang("success_api_providers"),
            ]);
        }
    }

    public function edit($id)
    {
        if (isset($_POST) && !empty($_POST)) {
            if (DEMO_VERSION != true) {
                $edit_name_api = $this->input->post("edit_name_api", true);
                $edit_url_api = $this->input->post("edit_url_api", true);
                $edit_key_api = $this->input->post("edit_key_api", true);
                $edit_type_parameter = $this->input->post("edit_type_parameter", true);
                $edit_api_status = $this->input->post("edit_api_status", true);

                $this->apiproviders_model->validate_form(true);

                $api = $this->model->get('*', $this->table, ['id' => $id], '', '', true);

                $type_services = ($edit_type_parameter == 'api_token' ? 'packages' : 'services');
                $connect = api_connect($edit_url_api, [$edit_type_parameter => $edit_key_api, 'action' => $type_services], true);

                if ($this->form_validation->run() == false && $edit_key_api != $api['key']) {
                    foreach ($this->form_validation->error_array() as $key => $value) {
                        json([
                            'csrf' => $this->security->get_csrf_hash(),
                            'type' => 'error',
                            'message' => form_error($key, false, false),
                        ]);
                    }
                }

                if (!in_array($edit_type_parameter, ['key', 'api_token'])) {
                    json([
                        'csrf' => $this->security->get_csrf_hash(),
                        'type' => 'error',
                        'message' => lang("error_choose_parameter_api"),
                    ]);
                }

                if (empty($connect) || isset($connect['data'])) {
                    json([
                        'csrf' => $this->security->get_csrf_hash(),
                        'type' => 'error',
                        'message' => lang("error_api_provider_does_not_exists_services"),
                    ]);
                }

                if (isset($connect['error']) || isset($connect['errors']) || !isset($connect)) {
                    json([
                        'csrf' => $this->security->get_csrf_hash(),
                        'type' => 'error',
                        'message' => lang("error_api_provider_does_not_exists_or_not_compatible"),
                    ]);
                }

                $status = ($edit_api_status == 1 ? '1' : '0');
                $data = [
                    'uuid' => uuid(),
                    'name' => $edit_name_api,
                    'key' => $edit_key_api,
                    'url' => $edit_url_api,
                    'type_parameter' => $edit_type_parameter,
                    'status' => $status,
                    'created_at' => NOW,
                ];

                $this->model->update($this->table, ['id' => $id], $data);

                json([
                    'csrf' => $this->security->get_csrf_hash(),
                    'type' => 'success',
                    'message' => lang("success_edit_api_providers"),
                ]);
            } else {
                json([
                    'csrf' => $this->security->get_csrf_hash(),
                    'type' => 'error',
                    'message' => lang("demo"),
                ]);
            }
        }
    }

    public function show($uuid)
    {
        $api = $this->model->get('*', $this->table, ['uuid' => $uuid], '', '', true);

        $data = [
            'title' => lang("title_show_api_providers") . " - " . $api['name'],
            'api_providers_services' => $this->model->fetch('*', $this->table, ['id' => $api['id']]),
            'get_status_api_providers' => $api,
            'count_category' => $this->model->counts($this->table_categories),
        ];

        view('layouts/auth_header', $data);
        view('panel/admin/management/api_providers/api_providers_services');
        view('layouts/auth_footer');
    }

    public function destroy($id)
    {
        if (DEMO_VERSION != true) {
            $delete_api_provider = $this->model->counts($this->table, ['id' => $id]);

            if ($delete_api_provider > 0) {
                $this->model->delete($this->table, ['id' => $id]);
                $this->model->delete($this->table_services, ['api_provider_id' => $id]);
            }
        } else {
            set_flashdata('error', lang("demo"));
        }
        redirect(base_url('admin/api/providers'));
    }

    public function update_balance($id)
    {
        $api = $this->model->get('*', $this->table, ['id' => $id], '', '', true);
        $api_connect = api_connect($api['url'], [$api['type_parameter'] => $api['key'], 'action' => 'balance'], true);
        $data = [
            'balance' => $api_connect['balance'],
            'currency' => (isset($api_connect['currency']) ? $api_connect['currency'] : ''),
        ];

        $this->model->update($this->table, ['id' => $id], $data);
        redirect(base_url('admin/api/providers'));
    }

    public function add_service_via_api()
    {
        if (isset($_POST) && !empty($_POST)) {
            $this->load->model("panel/services/services_model");

            $name_service = $this->input->post("name_service", true);
            $category_service = $this->input->post("category_service", true);
            $min_amount_service = $this->input->post("min_amount_service", true);
            $max_amount_service = $this->input->post("max_amount_service", true);
            $price_service = $this->input->post("price_service", true);
            $currency_price = $this->input->post("currency_price", true);
            $auto_convert_currency_service = $this->input->post("auto_convert_currency_service", true);
            $price_percentage_increase = $this->input->post("price_percentage_increase", true);
            $description = $this->input->post("description_service", true);
            $dripfeed = $this->input->post("dripfeed", true);
            $api_service_id = $this->input->post("api_service_id", true);
            $api_provider_id = $this->input->post("api_provider_id", true);
            $type = $this->input->post("type", true);

            $this->services_model->validation_form_service_via_api();

            $api = $this->model->get('*', $this->table, ['id' => $api_provider_id], '', '', true);

            if ($this->form_validation->run() == false) {
                foreach ($this->form_validation->error_array() as $key => $value) {
                    json([
                        'csrf' => $this->security->get_csrf_hash(),
                        'type' => 'error',
                        'message' => form_error($key, false, false),
                    ]);
                }
            }

            $service_type = ($api['type_parameter'] == 'api_token' ? ($type == 'custom_data' ? 'custom_data' : 'default') : strtolower(str_replace(" ", "_", $type)));
            $rate = $price_service + (($price_service * $price_percentage_increase) / 100);

            $balance = api_connect($api['url'], [$api['type_parameter'] => $api['key'], 'action' => 'balance'], true);

            if (isset($auto_convert_currency_service) && !isset($balance['currency'])) {
                json([
                    'csrf' => $this->security->get_csrf_hash(),
                    'type' => 'error',
                    'message' => lang("error_not_support_plugin_auto_convert"),
                ]);
            }

            if (isset($auto_convert_currency_service) && configs('auto_currency_converter', 'value') == 'on') {
                $rate = convert_exchange($currency_price, configs("currency_code", "value"), $rate);
            }

            $data = [
                'category_id' => $category_service,
                'name' => $name_service,
                'description' => $description,
                'price' => $rate,
                'min' => $min_amount_service,
                'max' => $max_amount_service,
                'add_type' => 'api',
                'type' => $service_type,
                'api_service_id' => $api_service_id,
                'api_provider_id' => $api_provider_id,
                'dripfeed' => $dripfeed,
                'status' => '1',
                'created_at' => NOW,
            ];

            $this->model->insert($this->table_services, $data);

            json([
                'csrf' => $this->security->get_csrf_hash(),
                'type' => 'success',
                'title' => lang("success_add_service_via_api_add"),
                'message' => lang("success_service_via_api_add"),
            ]);
        }
    }

    public function sync_services_api($id)
    {
        ini_set('max_execution_time', 500000);

        if (isset($_POST) && !empty($_POST)) {
            $synchronous_request = $this->input->post("synchronous_request", true);
            $price_percentage_increase = (int) $this->input->post("price_percentage_increase", true);
            $auto_convert_currency_service = $this->input->post("auto_convert_currency_service", true);

            if ($synchronous_request == 'noselect' || !in_array($synchronous_request, ['current', 'all'])) {
                json([
                    'csrf' => $this->security->get_csrf_hash(),
                    'type' => 'error',
                    'message' => lang("type_synchronous"),
                ]);
            }

            $api = $this->model->get('*', $this->table, ['id' => $id, 'status' => '1'], '', '', true);

            $type_services = ($api['type_parameter'] == 'api_token' ? 'packages' : 'services');
            $services_api = api_connect($api['url'], [$api['type_parameter'] => $api['key'], 'action' => $type_services], true);
            $balance = api_connect($api['url'], [$api['type_parameter'] => $api['key'], 'action' => 'balance'], true);

            $services_list = $this->model->fetch('`id` ' . ($api['type_parameter'] == 'api_token' ? 'as IDService' : '') . ', `category_id`, `name`, `description`, `price`, `min`, `max`, `add_type`, `type`, `api_service_id` ' . ($api['type_parameter'] == 'api_token' ? 'as id' : 'as service') . ', `api_provider_id`, `dripfeed`, `status`, `created_at`', $this->table_services, ['api_provider_id' => $api['id']], '', '', '', '', true);

            if (!empty($api)) {
                if (isset($services_api['error']) || isset($services_api['errors'])) {
                    json([
                        'csrf' => $this->security->get_csrf_hash(),
                        'type' => 'error',
                        'message' => lang('error_api_connecting'),
                    ]);
                }

                if (empty($services_api) || isset($services_api['data'])) {
                    json([
                        'csrf' => $this->security->get_csrf_hash(),
                        'type' => 'error',
                        'message' => lang("error_api_provider_does_not_exists_services"),
                    ]);
                }

                switch ($synchronous_request) {
                    case 'current':
                        if (DEMO_VERSION != true) {
                            $id_services_disables = '';
                            $id_services_update = '';

                            if (empty($services_list)) {
                                json([
                                    'csrf' => $this->security->get_csrf_hash(),
                                    'type' => 'error',
                                    'message' => sprintf(lang("error_service_lists_sync_services"), $api['name']),
                                ]);
                            }

                            if ($api['type_parameter'] == 'key') {
                                // Services Disables
                                $disables = compare_result($services_list, $services_api, 'service', 'service');

                                if (!empty($disables)) {
                                    foreach ($disables as $key => $disable) {
                                        $this->model->update($this->table_services, [
                                            'api_service_id' => $disable['service'],
                                            'api_provider_id' => $api['id'],
                                        ], ['status' => '0', 'created_at' => NOW]);

                                        $id_services_disables .= $disable['service'] . ",";
                                    }
                                }

                                // New Services
                                $news_services = compare_result($services_api, $services_list, 'service', 'service');

                                // Services Exists
                                $exists_services = compare_result($services_api, $news_services, 'service', 'service');

                                if (!empty($exists_services)) {
                                    foreach ($exists_services as $key => $exists_service) {
                                        $rate = $exists_service['rate'] + (($exists_service['rate'] * $price_percentage_increase) / 100);

                                        if (isset($auto_convert_currency_service) && !isset($balance['currency'])) {
                                            json([
                                                'csrf' => $this->security->get_csrf_hash(),
                                                'type' => 'error',
                                                'message' => lang("error_not_support_plugin_auto_convert"),
                                            ]);
                                        }

                                        if (isset($auto_convert_currency_service) && configs('auto_currency_converter', 'value') == 'on') {
                                            $rate = convert_exchange($balance['currency'], configs("currency_code", "value"), $rate);
                                        }

                                        $service_type = strtolower(str_replace(" ", "_", (isset($exists_service['type']) ? $exists_service['type'] : 'default')));

                                        $data_array = [
                                            'name' => $exists_service['name'],
                                            'description' => (isset($exists_service['description']) ? $exists_service['description'] : ''),
                                            'price' => $rate,
                                            'min' => $exists_service['min'],
                                            'max' => $exists_service['max'],
                                            'type' => $service_type,
                                            'dripfeed' => (isset($exists_service['dripfeed']) ? $exists_service['dripfeed'] : 0),
                                            'status' => '1',
                                            'created_at' => NOW,
                                        ];

                                        $this->model->update($this->table_services, [
                                            'api_service_id' => $exists_service['service'],
                                            'api_provider_id' => $api['id'],
                                        ], $data_array);

                                        $id_services_update .= $exists_service['service'] . ",";
                                    }
                                }

                                $news = $this->model->get('*', $this->table_news, ['type' => 'result_services', 'desc_disables' => $id_services_disables, 'desc_updates' => $id_services_update], '', '', true);

                                $data_db = substr($news['created_at'], 0, 10);
                                $data_now = substr(NOW, 0, 10);

                                if ($data_db == $data_now) {
                                    $this->model->update($this->table_news, ['desc_disables' => $id_services_disables, 'desc_updates' => $id_services_update], [
                                        'type' => 'result_services',
                                        'desc_disables' => $id_services_disables,
                                        'desc_updates' => $id_services_update,
                                    ]);
                                } else {
                                    $this->model->insert($this->table_news, [
                                        'type' => 'result_services',
                                        'desc_disables' => $id_services_disables,
                                        'desc_updates' => $id_services_update,
                                        'created_at' => NOW,
                                    ]);
                                }
                            } else {
                                // Services Disables
                                $disables = compare_result($services_list, $services_api, 'id', 'id');

                                if (!empty($disables)) {
                                    foreach ($disables as $key => $disable) {
                                        $this->model->update($this->table_services, [
                                            'api_service_id' => $disable['id'],
                                            'api_provider_id' => $api['id'],
                                        ], ['status' => '0', 'created_at' => NOW]);

                                        $id_services_disables .= $disable['id'] . ",";
                                    }
                                }

                                // New Services
                                $news_services = compare_result($services_api, $services_list, 'id', 'id');

                                // Services Exists
                                $exists_services = compare_result($services_api, $news_services, 'id', 'id');

                                if (!empty($exists_services)) {
                                    foreach ($exists_services as $key => $exists_service) {
                                        $rate = $exists_service['rate'] + (($exists_service['rate'] * $price_percentage_increase) / 100);

                                        if (isset($auto_convert_currency_service) && !isset($balance['currency'])) {
                                            json([
                                                'csrf' => $this->security->get_csrf_hash(),
                                                'type' => 'error',
                                                'message' => lang("error_not_support_plugin_auto_convert"),
                                            ]);
                                        }

                                        if (isset($auto_convert_currency_service) && configs('auto_currency_converter', 'value') == 'on') {
                                            $rate = convert_exchange($balance['currency'], configs("currency_code", "value"), $rate);
                                        }

                                        $service_type = strtolower(str_replace(" ", "_", (isset($exists_service['type']) ? $exists_service['type'] : 'default')));

                                        $data_array = [
                                            'name' => $exists_service['name'],
                                            'description' => (isset($exists_service['desc']) ? $exists_service['desc'] : ''),
                                            'price' => $rate,
                                            'min' => $exists_service['min'],
                                            'max' => $exists_service['max'],
                                            'type' => $service_type,
                                            'dripfeed' => (isset($exists_service['dripfeed']) ? $exists_service['dripfeed'] : 0),
                                            'status' => '1',
                                            'created_at' => NOW,
                                        ];

                                        $this->model->update($this->table_services, [
                                            'api_service_id' => $exists_service['id'],
                                            'api_provider_id' => $api['id'],
                                        ], $data_array);

                                        $id_services_update .= $exists_service['id'] . ",";
                                    }
                                }

                                $news = $this->model->get('*', $this->table_news, ['type' => 'result_services', 'desc_disables' => $id_services_disables, 'desc_updates' => $id_services_update], '', '', true);

                                $data_db = substr($news['created_at'], 0, 10);
                                $data_now = substr(NOW, 0, 10);

                                if ($data_db == $data_now) {
                                    $this->model->update($this->table_news, ['desc_disables' => $id_services_disables, 'desc_updates' => $id_services_update], [
                                        'type' => 'result_services',
                                        'desc_disables' => $id_services_disables,
                                        'desc_updates' => $id_services_update,
                                    ]);
                                } else {
                                    $this->model->insert($this->table_news, [
                                        'type' => 'result_services',
                                        'desc_disables' => $id_services_disables,
                                        'desc_updates' => $id_services_update,
                                        'created_at' => NOW,
                                    ]);
                                }
                            }

                            json([
                                'csrf' => $this->security->get_csrf_hash(),
                                'type' => 'success',
                                'title' => lang("success_sync_api_services"),
                                'message' => "",
                            ]);
                        }

                        json([
                            'csrf' => $this->security->get_csrf_hash(),
                            'type' => 'error',
                            'message' => lang("demo"),
                        ]);
                        break;

                    case 'all':
                        if ($api['type_parameter'] == 'key') {
                            // New Services
                            $news_services = compare_result($services_api, $services_list, 'service', 'service');

                            if (!empty($news_services)) {
                                $i = 1;
                                foreach ($news_services as $key => $news_service) {
                                    $category_name = $news_service['category'];
                                    $service_type = strtolower(str_replace(" ", "_", (isset($news_service['type']) ? $news_service['type'] : 'default')));

                                    $category_check = $this->model->get('*', $this->table_categories, ['name' => $category_name]);

                                    $rate = $news_service['rate'] + (($news_service['rate'] * $price_percentage_increase) / 100);

                                    if (DEMO_VERSION == true && count($news_services) > 300) {
                                        json([
                                            'csrf' => $this->security->get_csrf_hash(),
                                            'type' => 'error',
                                            'message' => sprintf(lang("demo_hidden_limited"), count($news_services)),
                                        ]);
                                    }

                                    if (isset($auto_convert_currency_service) && !isset($balance['currency'])) {
                                        json([
                                            'csrf' => $this->security->get_csrf_hash(),
                                            'type' => 'error',
                                            'message' => lang("error_not_support_plugin_auto_convert"),
                                        ]);
                                    }

                                    if (isset($auto_convert_currency_service) && configs('auto_currency_converter', 'value') == 'on') {
                                        $rate = convert_exchange($balance['currency'], configs("currency_code", "value"), $rate);
                                    }

                                    $data_service = [
                                        'name' => $news_service['name'],
                                        'description' => (isset($news_service['description']) ? $news_service['description'] : ''),
                                        'price' => $rate,
                                        'min' => $news_service['min'],
                                        'max' => $news_service['max'],
                                        'add_type' => 'api',
                                        'type' => $service_type,
                                        'api_service_id' => $news_service['service'],
                                        'api_provider_id' => $api['id'],
                                        'dripfeed' => (isset($news_service['dripfeed']) ? $news_service['dripfeed'] : 0),
                                        'status' => '1',
                                        'created_at' => NOW,
                                    ];

                                    if (!empty($category_check)) {
                                        $category_id = $category_check->id;
                                        $data_service["category_id"] = $category_id;
                                    } else {
                                        $data_category = [
                                            'name' => $category_name,
                                            'status' => '1',
                                            'created_at' => NOW,
                                        ];

                                        $this->model->insert($this->table_categories, $data_category);

                                        $category_id = $this->db->insert_id();
                                        $data_service["category_id"] = $category_id;
                                    }

                                    $data_batch_services[] = $data_service;
                                    ++$i;
                                }

                                if (!empty($data_batch_services)) {
                                    $this->model->insert_in_batch($this->table_services, $data_batch_services);
                                }
                            }
                        } else {
                            // New Services
                            $news_services = compare_result($services_api, $services_list, 'id', 'id');

                            if (!empty($news_services)) {
                                $i = 1;
                                foreach ($news_services as $key => $news_service) {
                                    $category_name = trim($news_service['service']);
                                    $service_type = strtolower(str_replace(" ", "_", (isset($news_service['type']) ? $news_service['type'] : 'default')));

                                    $category_check = $this->model->get('*', $this->table_categories, ['name' => $category_name]);

                                    $rate = $news_service['rate'] + (($news_service['rate'] * $price_percentage_increase) / 100);

                                    if (isset($auto_convert_currency_service) && !isset($balance['currency'])) {
                                        json([
                                            'csrf' => $this->security->get_csrf_hash(),
                                            'type' => 'error',
                                            'message' => lang("error_not_support_plugin_auto_convert"),
                                        ]);
                                    }

                                    if (isset($auto_convert_currency_service) && configs('auto_currency_converter', 'value') == 'on') {
                                        $rate = convert_exchange($balance['currency'], configs("currency_code", "value"), $rate);
                                    }

                                    $data_service = [
                                        'name' => $news_service['name'],
                                        'description' => (isset($news_service['desc']) ? $news_service['desc'] : ''),
                                        'price' => $rate,
                                        'min' => $news_service['min'],
                                        'max' => $news_service['max'],
                                        'add_type' => 'api',
                                        'type' => $service_type,
                                        'api_service_id' => $news_service['id'],
                                        'api_provider_id' => $api['id'],
                                        'dripfeed' => (isset($news_service['dripfeed']) ? $news_service['dripfeed'] : 0),
                                        'status' => '1',
                                        'created_at' => NOW,
                                    ];

                                    if (!empty($category_check)) {
                                        $category_id = $category_check->id;
                                        $data_service["category_id"] = $category_id;
                                    } else {
                                        $data_category = [
                                            'name' => $category_name,
                                            'status' => '1',
                                            'created_at' => NOW,
                                        ];

                                        $this->model->insert($this->table_categories, $data_category);

                                        $category_id = $this->db->insert_id();
                                        $data_service["category_id"] = $category_id;
                                    }

                                    $data_batch_services[] = $data_service;
                                    ++$i;
                                }

                                if (!empty($data_batch_services)) {
                                    $this->model->insert_in_batch($this->table_services, $data_batch_services);
                                }
                            }
                        }

                        json([
                            'csrf' => $this->security->get_csrf_hash(),
                            'type' => 'success',
                            'title' => lang("success_sync_api_news_services"),
                            'message' => "<span class='badge badge-info text-white'>" . lang("success_sync_api_services_news") . ": " . count($news_services) . "</span>",
                        ]);
                        break;
                }
            } else {
                json([
                    'csrf' => $this->security->get_csrf_hash(),
                    'type' => 'error',
                    'message' => lang("error_api_disabled"),
                ]);
            }
        }
    }

    /**
     * Callbacks errors form validation
     */
    public function select_validation($var, $message)
    {
        if ($var == 'noselect') {
            $this->form_validation->set_message('select_validation', $message);
            return false;
        } else {
            return true;
        }
    }
}
