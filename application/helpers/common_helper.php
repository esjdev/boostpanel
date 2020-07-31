<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('themes')) {
    /**
     * @return void
     */
    function themes()
    {
        $ci = &get_instance();
        $ci->load->helper('directory');
        $map = directory_map('./public/themes/', true, true);

        echo '<select class="form-control" name="theme_website">';
        foreach ($map as $value) {
            if ($value != 'index.html') {
                $name_theme = str_replace(DIRECTORY_SEPARATOR, "", $value);
                if (file_exists(VIEWPATH . '/themes/' . $name_theme)) {
                    echo '<option value="' . $name_theme . '" ' . ($name_theme == config('theme') ? 'selected' : '') . '>' . ucfirst($name_theme) . '</option>';
                }
            }
        }
        echo '</select>';
    }
}

if (!function_exists('logged')) {
    /**
     * Verify session email exists
     * @return void
     */
    function logged()
    {
        return session('email');
    }
}

if (!function_exists('set_flashdata')) {
    /**
     * Set Flash Session
     * @return void
     */
    function set_flashdata($name, $value)
    {
        $ci = &get_instance();
        return $ci->session->set_flashdata($name, $value);
    }
}

if (!function_exists('flashdata')) {
    /**
     * Get Flash Session
     * @return void
     */
    function flashdata($name)
    {
        $ci = &get_instance();
        return $ci->session->flashdata($name);
    }
}

if (!function_exists('set_session')) {
    /**
     * Set Session
     * @return void
     */
    function set_session($name, $value)
    {
        $ci = &get_instance();
        return $ci->session->set_userdata($name, $value);
    }
}

if (!function_exists('session')) {
    /**
     * Get Session
     * @return void
     */
    function session($name)
    {
        $ci = &get_instance();
        return $ci->session->userdata($name);
    }
}

if (!function_exists('unset_session')) {
    /**
     * Delete Session
     * @return void
     */
    function unset_session($name)
    {
        $ci = &get_instance();
        return $ci->session->unset_userdata($name);
    }
}

if (!function_exists('strip_word_html')) {
    /**
     *
     * @param [type] $data
     * @return void
     */
    function strip_word_textarea($data)
    {
        return strip_tags($data, '<html><b><em><hr><i><li><ol><p><s><span><table><thead><tbody><tr><td><u><ul><div><strong><sup><br><a><img><blockquote><h1><h2><h3><h4><h5><h6><small><big><samp><var><cite><ins><del><q><tt><code><kbd><pre><address>');
    }
}

if (!function_exists('view')) {
    /**
     * Load view
     * @param [type] $name
     * @param [type] $data[]
     * @return void
     */
    function view($name, $data = [])
    {
        $ci = &get_instance();
        return $ci->load->view('themes/' . config('theme') . "/" . $name, $data);
    }
}

if (!function_exists('set_image')) {
    /**
     * Load Image
     * @param [type] $name
     * @return void
     */
    function set_image($name)
    {
        return base_url('public/themes/' . config('theme') . '/images/' . $name);
    }
}

if (!function_exists('set_css')) {
    /**
     * Load CSS
     * @param [type] $name
     * @return void
     */
    function set_css($name)
    {
        return link_tag('public/themes/' . config('theme') . '/css/' . $name);
    }
}

if (!function_exists('set_js')) {
    /**
     * Load JS
     * @param [type] $name
     * @return void
     */
    function set_js($name)
    {
        return base_url("public/themes/" . config('theme') . "/js/" . $name);
    }
}

if (!function_exists('json_api')) {
    /**
     *
     * @return void
     */
    function json_api($json)
    {
        echo json_encode($json, JSON_PRETTY_PRINT);
        exit;
    }
}

if (!function_exists('json')) {
    /**
     * @param $array
     */
    function json($array)
    {
        echo json_encode($array, JSON_PRETTY_PRINT);
        exit;
    }
}

if (!function_exists('valid_url')) {
    /**
     * @param $str
     * @return bool
     */
    function valid_url($str)
    {
        return (!filter_var($str, FILTER_VALIDATE_URL) === false ? true : false);
    }
}

if (!function_exists('uuid')) {
    /**
     *
     * @return void
     */
    function uuid()
    {
        $uid = uniqid('', true);
        $data = rand(11111, 99999) . logged() . time() . $_SERVER['REMOTE_ADDR'] . $_SERVER['REMOTE_PORT'];
        $hash = hash('ripemd128', $uid . password_hash($data, PASSWORD_DEFAULT) . mt_rand(0, 0xffff) . mt_rand(0, 0x3fff));
        $result = substr($hash,  0,  8) . '-' .
            substr($hash,  8,  4) . '-' .
            substr($hash, 0,  4) . '-' .
            substr($hash, 16,  4) . '-' .
            substr($hash, 20, 12);

        return $result;
    }
}

if (!function_exists('token_rand')) {
    /**
     *
     * @return void
     */
    function token_rand()
    {
        return md5(rand(0, 99999) . time() . password_hash(logged(), PASSWORD_DEFAULT));
    }
}

if (!function_exists("create_random_api_key")) {
    /**
     * @param string $length
     * @return string
     */
    function create_random_api_key($length = "")
    {
        $length = ($length == "" ? 32 : $length);
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ' . md5(password_hash(time(), PASSWORD_DEFAULT));
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}

if (!function_exists('config')) {
    /**
     * @param $item
     * @return mixed
     */
    function config($item)
    {
        $ci = &get_instance();

        $ci->load->config('config');
        $item = $ci->config->item($item);

        return $item;
    }
}

if (!function_exists('add_log')) {
    /**
     * @param $action
     * @param $id_error
     * @param $value
     * @return mixed
     */
    function add_log($action, $id_error, $value)
    {
        $ci = &get_instance();

        $logs = $ci->model->get('*', TABLE_LOGS, ['id_error' => $id_error], '', '', true);
        $data_db = substr($logs['updated_at'], 0, 10);
        $data_now = substr(NOW, 0, 10);

        if ($data_db == $data_now) {
            $sql = $ci->model->update(TABLE_LOGS, ['id_error' => $id_error], [
                'value' => $value,
                'id_error' => $id_error,
                'updated_at' => NOW,
            ]);
        } else {
            $sql = $ci->model->insert(TABLE_LOGS, [
                'action' => $action,
                'value' => $value,
                'id_error' => $id_error,
                'created_at' => NOW,
                'updated_at' => NOW,
            ]);
        }

        return ($sql ? true : false);
    }
}

if (!function_exists('configs')) {
    /**
     * Get data configs in Database
     * @param [type] $name
     * @param [type] $object
     * @return void
     */
    function configs($name, $object)
    {
        $ci = &get_instance();
        $configs = $ci->model->get('*', TABLE_CONFIG, ['name' => $name], '', '', true);

        if (isset($configs[$object])) return $configs[$object];
    }
}

if (!function_exists('config_payment')) {
    /**
     * Get data configs in Database
     * @param [type] $name
     * @param [type] $object
     * @return void
     */
    function config_payment($name, $object)
    {
        $ci = &get_instance();
        $configs = $ci->model->get('*', TABLE_PAYMENTS_CONFIG, ['name' => $name], '', '', true);

        if (isset($configs[$object])) return $configs[$object];
    }
}

if (!function_exists('payments')) {
    /**
     * List Payments Supported
     * @return void
     */
    function payments()
    {
        $badge = [
            'BRL' => '<span class="badge badge-green fs-12" data-toggle="tooltip" title="' . sprintf(lang("convertion_from"), "BRL") . '">BRL</span>',
            'INR' => '<span class="badge badge-green fs-12" data-toggle="tooltip" title="' . sprintf(lang("convertion_from"), "INR") . '">INR</span>'
        ];

        $payments_methods = [
            'paypal' => [
                'METHOD' => 'PayPal',
                'NAME' => config_payment('paypal_name', 'value'),
                'MIN' => number_format(config_payment('paypal_min_payment', 'value'), 2, '.', ''),
                'MAX' => (config_payment('paypal_max_payment', 'value') == 0 ? '∞' : number_format(config_payment('paypal_max_payment', 'value'), 2, '.', '')),
                'STATUS' => (config_payment('paypal_status', 'value') == 'on' ? 'checked' : ''),

                'CLASS_TABLE' => 'text-muted',

                'PERMISSION' => config_payment('paypal_status', 'value') == 'on' && config_payment('paypal_client_id', 'value') != ''
                    && config_payment('paypal_client_secret', 'value') != '',
            ],

            'pagseguro' => [
                'METHOD' => 'PagSeguro ' . $badge['BRL'],
                'NAME' => config_payment('pagseguro_name', 'value'),
                'MIN' => number_format(config_payment('pagseguro_min_payment', 'value'), 2, '.', ''),
                'MAX' => (config_payment('pagseguro_max_payment', 'value') == 0 ? '∞' : number_format(config_payment('pagseguro_max_payment', 'value'), 2, '.', '')),
                'STATUS' => (config_payment('pagseguro_status', 'value') == 'on' ? 'checked' : ''),

                'CLASS_TABLE' => 'text-muted',

                'PERMISSION' => config_payment('pagseguro_status', 'value') == 'on' && config_payment('pagseguro_token', 'value') != '' && config_payment('pagseguro_email', 'value') != '',
            ],

            'mercadopago' => [
                'METHOD' => 'MercadoPago ' . $badge['BRL'],
                'NAME' => config_payment('mercadopago_name', 'value'),
                'MIN' => number_format(config_payment('mercadopago_min_payment', 'value'), 2, '.', ''),
                'MAX' => (config_payment('mercadopago_max_payment', 'value') == 0 ? '∞' : number_format(config_payment('mercadopago_max_payment', 'value'), 2, '.', '')),
                'STATUS' => (config_payment('mercadopago_status', 'value') == 'on' ? 'checked' : ''),

                'CLASS_TABLE' => 'text-muted',

                'PERMISSION' => config_payment('mercadopago_status', 'value') == 'on' && config_payment('mercadopago_access_token', 'value') != '',
            ],

            'stripe' => [
                'METHOD' => 'Stripe',
                'NAME' => config_payment('stripe_name', 'value'),
                'MIN' => number_format(config_payment('stripe_min_payment', 'value'), 2, '.', ''),
                'MAX' => (config_payment('stripe_max_payment', 'value') == 0 ? '∞' : number_format(config_payment('stripe_max_payment', 'value'), 2, '.', '')),
                'STATUS' => (config_payment('stripe_status', 'value') == 'on' ? 'checked' : ''),

                'CLASS_TABLE' => 'text-muted',

                'PERMISSION' => config_payment('stripe_status', 'value') == 'on' && config_payment('stripe_secret_key', 'value') != ''
                    && config_payment('stripe_publishable_key', 'value') != '',
            ],

            'twocheckout' => [
                'METHOD' => '2Checkout',
                'NAME' => config_payment('2checkout_name', 'value'),
                'MIN' => number_format(config_payment('2checkout_min_payment', 'value'), 2, '.', ''),
                'MAX' => (config_payment('2checkout_max_payment', 'value') == 0 ? '∞' : number_format(config_payment('2checkout_max_payment', 'value'), 2, '.', '')),
                'STATUS' => (config_payment('2checkout_status', 'value') == 'on' ? 'checked' : ''),

                'CLASS_TABLE' => 'text-muted',

                'PERMISSION' => config_payment('2checkout_status', 'value') == 'on' && config_payment('2checkout_publishable_key', 'value') != '' && config_payment('2checkout_private_key', 'value') != '' && config_payment('2checkout_seller_id', 'value') != '',
            ],

            'coinpayments' => [
                'METHOD' => 'CoinPayments',
                'NAME' => config_payment('coinpayments_name', 'value'),
                'MIN' => number_format(config_payment('coinpayments_min_payment', 'value'), 2, '.', ''),
                'MAX' => (config_payment('coinpayments_max_payment', 'value') == 0 ? '∞' : number_format(config_payment('coinpayments_max_payment', 'value'), 2, '.', '')),
                'STATUS' => (config_payment('coinpayments_status', 'value') == 'on' ? 'checked' : ''),

                'CLASS_TABLE' => 'text-muted',

                'PERMISSION' => config_payment('coinpayments_status', 'value') == 'on' && config_payment('coinpayments_public_key', 'value') != '' && config_payment('coinpayments_private_key', 'value') != '',
            ],

            'skrill' => [
                'METHOD' => 'Skrill Business',
                'NAME' => config_payment('skrill_name', 'value'),
                'MIN' => number_format(config_payment('skrill_min_payment', 'value'), 2, '.', ''),
                'MAX' => (config_payment('skrill_max_payment', 'value') == 0 ? '∞' : number_format(config_payment('skrill_max_payment', 'value'), 2, '.', '')),
                'STATUS' => (config_payment('skrill_status', 'value') == 'on' ? 'checked' : ''),

                'CLASS_TABLE' => 'text-muted',

                'PERMISSION' => config_payment('skrill_status', 'value') == 'on' && config_payment('skrill_email', 'value') != '',
            ],

            'payumoney' => [
                'METHOD' => 'PayUmoney ' . $badge['INR'],
                'NAME' => config_payment('payumoney_name', 'value'),
                'MIN' => number_format(config_payment('payumoney_min_payment', 'value'), 2, '.', ''),
                'MAX' => (config_payment('payumoney_max_payment', 'value') == 0 ? '∞' : number_format(config_payment('payumoney_max_payment', 'value'), 2, '.', '')),
                'STATUS' => (config_payment('payumoney_status', 'value') == 'on' ? 'checked' : ''),

                'CLASS_TABLE' => 'text-muted',

                'PERMISSION' => config_payment('payumoney_status', 'value') == 'on' && config_payment('payumoney_merchant_key', 'value') != ''
                    && config_payment('payumoney_merchant_salt', 'value') != '',
            ],

            'paytm' => [
                'METHOD' => 'PayTM ' . $badge['INR'],
                'NAME' => config_payment('paytm_name', 'value'),
                'MIN' => number_format(config_payment('paytm_min_payment', 'value'), 2, '.', ''),
                'MAX' => (config_payment('paytm_max_payment', 'value') == 0 ? '∞' : number_format(config_payment('paytm_max_payment', 'value'), 2, '.', '')),
                'STATUS' => (config_payment('paytm_status', 'value') == 'on' ? 'checked' : ''),

                'CLASS_TABLE' => 'text-muted',

                'PERMISSION' => config_payment('paytm_status', 'value') == 'on' && config_payment('paytm_merchant_key', 'value') != ''
                    && config_payment('paytm_merchant_mid', 'value') != '' && config_payment('paytm_merchant_website', 'value') != '',
            ],

            'instamojo' => [
                'METHOD' => 'Instamojo ' . $badge['INR'],
                'NAME' => config_payment('instamojo_name', 'value'),
                'MIN' => number_format(config_payment('instamojo_min_payment', 'value'), 2, '.', ''),
                'MAX' => (config_payment('instamojo_max_payment', 'value') == 0 ? '∞' : number_format(config_payment('instamojo_max_payment', 'value'), 2, '.', '')),
                'STATUS' => (config_payment('instamojo_status', 'value') == 'on' ? 'checked' : ''),

                'CLASS_TABLE' => 'text-muted',

                'PERMISSION' => config_payment('instamojo_status', 'value') == 'on' && config_payment('instamojo_api_key', 'value') != ''
                    && config_payment('instamojo_auth_token', 'value') != '',
            ],

            'mollie' => [
                'METHOD' => 'Mollie',
                'NAME' => config_payment('mollie_name', 'value'),
                'MIN' => number_format(config_payment('mollie_min_payment', 'value'), 2, '.', ''),
                'MAX' => (config_payment('mollie_max_payment', 'value') == 0 ? '∞' : number_format(config_payment('mollie_max_payment', 'value'), 2, '.', '')),
                'STATUS' => (config_payment('mollie_status', 'value') == 'on' ? 'checked' : ''),

                'CLASS_TABLE' => 'text-muted',

                'PERMISSION' => config_payment('mollie_status', 'value') == 'on' && config_payment('mollie_api_key', 'value') != '',
            ],

            'razorpay' => [
                'METHOD' => 'RazorPay ' . $badge['INR'],
                'NAME' => config_payment('razorpay_name', 'value'),
                'MIN' => number_format(config_payment('razorpay_min_payment', 'value'), 2, '.', ''),
                'MAX' => (config_payment('razorpay_max_payment', 'value') == 0 ? '∞' : number_format(config_payment('razorpay_max_payment', 'value'), 2, '.', '')),
                'STATUS' => (config_payment('razorpay_status', 'value') == 'on' ? 'checked' : ''),

                'CLASS_TABLE' => 'text-muted',

                'PERMISSION' => config_payment('razorpay_status', 'value') == 'on' && config_payment('razorpay_key_id', 'value') != '' && config_payment('razorpay_key_secret', 'value') != '',
            ],


            'manual' => [
                'METHOD' => 'Manual',
                'NAME' => 'Manual',
                'MIN' => '∞',
                'MAX' => '∞',
                'STATUS' => (config_payment('manual_status', 'value') == 'on' ? 'checked' : ''),

                'CLASS_TABLE' => 'text-muted',

                'PERMISSION' => config_payment('manual_status', 'value') == 'on',
            ],
        ];

        return $payments_methods;
    }
}

if (!function_exists('verifyPayments')) {
    /**
     * Verify Payment
     * @return void
     */
    function verifyPayments()
    {
        if (config_payment('paypal_status', 'value') == 'off' && config_payment('pagseguro_status', 'value') == 'off' && config_payment('mercadopago_status', 'value') == 'off' && config_payment('stripe_status', 'value') == 'off' && config_payment('2checkout_status', 'value') == 'off' && config_payment('coinpayments_status', 'value') == 'off' && config_payment('skrill_status', 'value') == 'off' && config_payment('payumoney_status', 'value') == 'off' && config_payment('paytm_status', 'value') == 'off' && config_payment('instamojo_status', 'value') == 'off' && config_payment('mollie_status', 'value') == 'off' && config_payment('razorpay_status', 'value') == 'off' && config_payment('manual_status', 'value') == 'off') return true;
    }
}

if (!function_exists('paymentsForm')) {
    /**
     * List Payments Form
     * @return void
     */
    function paymentsForm()
    {
        $forms_payments = [
            'paypal' => [
                'STATUS' => config_payment('paypal_status', 'value') == 'on',
                'NAME' => 'PayPal',
                'URL' => 'paypal/create_payment',
            ],

            'pagseguro' => [
                'STATUS' => config_payment('pagseguro_status', 'value') == 'on',
                'NAME' => 'PagSeguro',
                'URL' => 'pagseguro/create_payment',
                'CURRENCY' => 'BRL'
            ],

            'mercadopago' => [
                'STATUS' => config_payment('mercadopago_status', 'value') == 'on',
                'NAME' => 'MercadoPago',
                'URL' => 'mercadopago/create_payment',
                'CURRENCY' => 'BRL'
            ],

            'stripe' => [
                'STATUS' => config_payment('stripe_status', 'value') == 'on',
                'NAME' => 'Stripe',
                'URL' => 'stripe/proccess',
            ],

            'twocheckout' => [
                'STATUS' => config_payment('2checkout_status', 'value') == 'on',
                'NAME' => '2Checkout',
                'URL' => 'twocheckout/proccess',
            ],

            'coinpayments' => [
                'STATUS' => config_payment('coinpayments_status', 'value') == 'on',
                'NAME' => 'CoinPayments',
                'URL' => 'coinpayments/proccess',
            ],

            'skrill' => [
                'STATUS' => config_payment('skrill_status', 'value') == 'on',
                'NAME' => 'Skrill',
                'URL' => 'skrill/create_payment',
            ],

            'payumoney' => [
                'STATUS' => config_payment('payumoney_status', 'value') == 'on',
                'NAME' => 'PayUmoney',
                'URL' => 'payumoney/step_one',
            ],

            'paytm' => [
                'STATUS' => config_payment('paytm_status', 'value') == 'on',
                'NAME' => 'PayTM',
                'URL' => 'paytm/step_one',
            ],

            'instamojo' => [
                'STATUS' => config_payment('instamojo_status', 'value') == 'on',
                'NAME' => 'Instamojo',
                'URL' => 'instamojo/create_payment',
            ],

            'mollie' => [
                'STATUS' => config_payment('mollie_status', 'value') == 'on',
                'NAME' => 'Mollie',
                'URL' => 'mollie/create_payment',
            ],

            'razorpay' => [
                'STATUS' => config_payment('razorpay_status', 'value') == 'on',
                'NAME' => 'RazorPay',
                'URL' => 'razorpay/create_payment',
            ],
        ];

        return $forms_payments;
    }
}

if (!function_exists('listCoin')) {
    /**
     * List Coin
     * @return void
     */
    function listCoin()
    {
        if (config_payment('coinpayments_environment', 'value') == 'Live') {
            $coins = [
                'Bitcoin' => 'BTC',
                'Bitcoin (Lightning Network)' => 'BTC.LN',
                'Litecoin' => 'LTC',
                'Velas' => 'VLX',
                'AGX' => 'AGX',
                'Apollo' => 'APL',
                'The Advertising Currency - Articles' => 'artTAC',
                'Aryacoin' => 'AYA',
                'Badcoin' => 'BAD',
                'The Advertising Currency - Banners' => 'banTAC',
                'Bitcoin Diamond' => 'BCD',
                'Bitcoin Cash' => 'BCH',
                'Bytecoin' => 'BCN',
                'Beam' => 'BEAM',
                'BF Token' => 'BFT',
                'Bean Cash' => 'BITB',
                'BlackCoin' => 'BLK',
                'BNB Coin (ERC-20)' => 'BNB',
                'Bitcoin SV' => 'BSV',
                'Bitcoin Adult' => 'BTAD',
                'Bitcoin Gold' => 'BTG',
                'BitTorrent' => 'BTT',
                'CloakCoin' => 'CLOAK',
                'CommonsOS (Mainnet)' => 'COM',
                'Crown' => 'CRW',
                'CrypticCoin' => 'CRYP',
                'CureCoin' => 'CURE',
                'Dai' => 'DAI',
                'Dash' => 'DASH',
                'Decred' => 'DCR',
                'DeviantCoin' => 'DEV',
                'DigiByte' => 'DGB',
                'Divi' => 'DIVI',
                'Dogecoin' => 'DOGE',
                'eBoost' => 'EBST',
                'BTG' => 'ERK',
                'BTG' => 'ETC',
                'Ether' => 'ETH',
                'Electroneum' => 'ETN',
                'EUNO' => 'EUNO',
                'STASIS EURS' => 'EURS',
                'EventChain' => 'EVC',
                'Expanse' => 'EXP',
                'FLASH' => 'FLASH',
                'Fuel Token' => 'FUEL',
                'GameCredits' => 'GAME',
                'Goldcoin' => 'GLC',
                'Groestlcoin' => 'GRS',
                'Gemini dollar' => 'GUSD',
                'KuCoin Shares' => 'KCS',
                'Kinguin Krowns' => 'KRS',
                'Loki' => 'LOKI',
                'Lisk' => 'LSK',
                'The Advertising Currency - Magazines' => 'magTAC',
                'MonetaryUnit' => 'MUE',
                'Namecoin' => 'NMC',
                'NXT' => 'NXT',
                'OKB' => 'OKB',
                'The Advertising Currency - Outdoor' => 'outTAC',
                'PIVX' => 'PIVX',
                'POA20 (ERC20 Token)' => 'POA20',
                'PotCoin' => 'POT',
                'Peercoin' => 'PPC',
                'ProCurrency' => 'PROC',
                'QASH' => 'QASH',
                'Qtum' => 'QTUM',
                'The Advertising Currency - Radio' => 'radTAC',
                'Rasputin Online Coin' => 'ROC',
                'Ravencoin' => 'RVN',
                'Sai' => 'SAI',
                'Steem Dollars' => 'SBD',
                'Siambitcoin' => 'sBTC',
                'SkinCoin' => 'SKIN',
                'SmartCash' => 'SMART',
                'Snowball' => 'SNBL',
                'The Advertising Currency - Social' => 'socTAC',
                'Sirin' => 'SRN',
                'STEEM' => 'STEEM',
                'StorjToken' => 'STORJ',
                'Stratis' => 'STRAT',
                'Syscoin' => 'SYS',
                'The Advertising Currency - Traffic Exchange' => 'teTAC',
                'TokenPay' => 'TPAY',
                'Triggers' => 'TRIGGERS',
                'Tronipay' => 'TRP',
                'TRON' => 'TRX',
                'TrueUSD' => 'TUSD',
                'The Advertising Currency - Television' => 'tvTAC',
                'Ubiq' => 'UBQ',
                'Ucacoin' => 'UCA',
                'UnikoinGold' => 'UKG',
                'UniversalCurrency' => 'UNIT',
                'USD//C' => 'USDC',
                'Tether USD (ERC20)' => 'USDT.ERC20',
                'The Advertising Currency - Video' => 'vidTAC',
                'The Advertising Currency - Viral Mailers' => 'vmTAC',
                'Vertcoin' => 'VTC',
                'Waves' => 'WAVES',
                'Counterparty' => 'XCP',
                'NEM' => 'XEM',
                'Monero' => 'XMR',
                'Stakenet' => 'XSN',
                'SucreCoin' => 'XSR',
                'VERGE' => 'XVG',
                'ZCoin' => 'XZC',
                'ZCash' => 'ZEC',
                'Horizen' => 'ZEN',
            ];
        } else {
            $coins = [
                'Litecoin Testnet (Sandbox)' => 'LTCT'
            ];
        }

        return $coins;
    }
}

if (!function_exists('payment_status')) {
    /**
     * Payment Status
     * @return void
     */
    function payment_status($status)
    {
        switch ($status) {
            case 'pending':
                $status = "<span class='badge badge-secondary cursor-pointer fs-12'>" . lang('status_pending') . "</span>";
                break;

            case 'processing':
                $status = "<span class='badge badge-warning text-white cursor-pointer fs-12'>" . lang('status_processing') . "</span>";
                break;

            case 'paid':
                $status = "<span class='badge badge-green cursor-pointer fs-12'>" . lang('status_paid') . "</span>";
                break;

            case 'in_dispute':
                $status = "<span class='badge badge-danger cursor-pointer fs-12'>" . lang('status_in_dispute') . "</span>";
                break;

            case 'refunded':
                $status = "<span class='badge badge-danger cursor-pointer fs-12'>" . lang('status_refunded') . "</span>";
                break;

            case 'canceled':
                $status = "<span class='badge badge-danger cursor-pointer fs-12'>" . lang('status_canceled') . "</span>";
                break;
        }

        return $status;
    }
}

if (!function_exists('payment_type')) {
    /**
     * Type Payments
     * @return void
     */
    function payment_type($type_payment)
    {
        switch ($type_payment) {
            case 'paypal':
                $method = 'PayPal';
                break;

            case 'pagseguro':
                $method = 'PagSeguro';
                break;

            case 'mercadopago':
                $method = 'MercadoPago';
                break;

            case 'stripe':
                $method = 'Stripe';
                break;

            case 'twocheckout':
                $method = '2Checkout';
                break;

            case 'coinpayments':
                $method = 'CoinPayments';
                break;

            case 'skrill':
                $method = 'Skrill';
                break;

            case 'payumoney':
                $method = 'PayUmoney';
                break;

            case 'paytm':
                $method = 'PayTM';
                break;

            case 'instamojo':
                $method = 'Instamojo';
                break;

            case 'mollie':
                $method = 'Mollie';
                break;

            case 'razorpay':
                $method = 'RazorPay';
                break;

            case 'manual':
                $method = 'Manual';
                break;
        }

        return $method;
    }
}

if (!function_exists('dataUser')) {
    /**
     * Get specific data in the table users
     * @param [type] $email
     * @param [type] $object
     * @return void
     */
    function dataUser($email, $object)
    {
        $ci = &get_instance();
        $user = $ci->model->get('*', TABLE_USERS, ['email' => $email], '', '', true);

        if (isset($user[$object])) return $user[$object];
    }
}

if (!function_exists('dataUserId')) {
    /**
     * Get specific data in the table users via ID
     *
     * @param $id
     * @param $object
     * @return mixed
     */
    function dataUserId($id, $object)
    {
        $ci = &get_instance();
        $user = $ci->model->get('*', TABLE_USERS, ['id' => $id], '', '', true);

        if (isset($user[$object])) return $user[$object];
    }
}

if (!function_exists('userLevel')) {
    /**
     * Verify if user is normal, admin, support or banned
     * @param [type] $email
     * @param [type] $level
     * @return bool
     */
    function userLevel($email, $level)
    {
        if ($level == 'user') {
            $level = 'USER';
        }

        if ($level == 'admin') {
            $level = 'ADMIN';
        }

        if ($level == 'support') {
            $level = 'SUPPORT';
        }

        if ($level == 'banned') {
            $level = 'BANNED';
        }

        if (logged() and dataUser($email, 'role') == $level) return true;
    }
}

if (!function_exists('userBalance')) {
    /**
     * Get balance user
     * @param [type] $email
     * @param string $currency
     * @return string
     */
    function userBalance($email, $currency = "")
    {
        return ($currency ? dataUser($email, 'balance') : currency_format(dataUser($email, 'balance')));
    }
}

if (!function_exists('countUsers')) {
    /**
     * Count total Users
     * @return void
     */
    function countUsers()
    {
        $ci = &get_instance();
        $count = $ci->model->counts(TABLE_USERS);
        return $count;
    }
}

if (!function_exists('countTicket')) {
    /**
     * @return mixed
     */
    function countTicket()
    {
        $ci = &get_instance();

        if (userLevel(logged(), 'user')) {
            $count = $ci->model->counts(TABLE_TICKETS, ['user_id' => dataUser(logged(), 'id'), 'status' => 'answered']);
        } else {
            $count = $ci->model->counts(TABLE_TICKETS, ['status' => 'pending']);
        }

        return $count;
    }
}

if (!function_exists('countLogs')) {
    /**
     * @param $where
     * @return void
     */
    function countLogs($where = false)
    {
        $ci = &get_instance();
        if ($where) {
            $count = $ci->model->counts(TABLE_LOGS, 'Date(created_at) = CURDATE()');
        } else {
            $count = $ci->model->counts(TABLE_LOGS);
        }
        return $count;
    }
}

if (!function_exists('userTicketMessage')) {
    /**
     * @param $id
     * @param $return
     * @return mixed
     */
    function userTicketMessage($id, $return)
    {
        $ci = &get_instance();
        $user = $ci->model->get('*', TABLE_USERS, ['id' => $id], '', '', true);

        return ($return == 'username' ? $user['username'] : $user['role']);
    }
}

if (!function_exists('list_category')) {
    /**
     * List category
     * @param string $name
     * @return void
     */
    function list_category($name = 'category_service')
    {
        $ci = &get_instance();
        $categories = (userLevel(logged(), 'user') ? $ci->model->fetch('*', TABLE_CATEGORIES, ['status' => '1'], 'id', 'asc', '', '') : $ci->model->fetch('*', TABLE_CATEGORIES, "", 'id', 'asc', '', ''));

        echo '<select class="form-control" name="' . $name . '">';

        echo '<option value="noselect" class="font-weight-bold">' . lang("choose_category") . '</option>';
        foreach ($categories as $key => $row) {
            echo '<option value="' . $row->id . '">' . $row->name . '</option>';
        }

        echo '</select>';
    }
}

if (!function_exists('compare_result')) {
    /**
     * @param $array1
     * @param $array2
     * @param $compare1
     * @param $compare2
     * @return array
     */
    function compare_result($array1, $array2, $compare1, $compare2)
    {
        $results = array_udiff(
            $array1,
            $array2,
            function ($a, $b) use ($compare1, $compare2) {
                return $a[$compare1] - $b[$compare2];
            }
        );

        return $results;
    }
}

if (!function_exists('active_menu')) {
    /**
     * @param $page
     * @return string
     */
    function active_menu($page)
    {
        $ci = &get_instance();
        return ($ci->uri->uri_string() == $page ? 'menu-active' : '');
    }
}

if (!function_exists('active')) {
    /**
     * @param $page
     * @return string
     */
    function active($page)
    {
        $ci = &get_instance();
        return ($ci->uri->uri_string() == $page ? 'active' : '');
    }
}

if (!function_exists('uri')) {
    /**
     * @param [type] $uri
     * @return void
     */
    function uri($uri)
    {
        $ci = &get_instance();
        return $ci->uri->segment($uri);
    }
}

if (!function_exists('api_connect')) {
    /**
     * @param $url
     * @param array $array
     * @param null $data
     * @return bool|mixed|string|null
     */
    function api_connect($url, $array = [], $data = null, $option = false)
    {
        $_array = [];
        if (is_array($array)) {
            foreach ($array as $name => $value) {
                $_array[] = $name . '=' . urlencode($value);
            }
        }

        if ($option) {
            $domain = base64_encode(base_url());
            $ch = curl_init($url . "&domain=" . $domain);
        } else {
            $ch = curl_init($url);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 120);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        if (is_array($array)) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, join('&', $_array));
        }
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        $result = curl_exec($ch);
        if (curl_errno($ch) != 0 && empty($result)) {
            $result = false;
        }
        $data = ($data == true ? json_decode($result, true) : $result);
        curl_close($ch);
        return $data;
    }
}

if (!function_exists('curl_download')) {
    /**
     * @param [type] $url
     * @param string $pathZip
     * @return void
     */
    function curl_download($url, $pathZip = "")
    {
        $resourceZip = fopen($pathZip, "w");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_FILE, $resourceZip);
        $page = curl_exec($ch);
        if (!$page) {
            json([
                'type' => 'error',
                'message' => 'Error: ' . curl_error($ch),
            ]);
        }
        curl_close($ch);
    }
}

if (!function_exists("currency_codes")) {
    function currency_codes()
    {
        $data = [
            "AUD" => "Australian dollar",
            "BRL" => "Brazilian dollar",
            "CAD" => "Canadian dollar",
            "CZK" => "Czech koruna",
            "DKK" => "Danish krone",
            "EUR" => "Euro",
            "HKD" => "Hong Kong dollar",
            "HUF" => "Hungarian forint",
            "INR" => "Indian rupee",
            "ILS" => "Israeli",
            "JPY" => "Japanese yen",
            "CNY" => "Chinese Yuan",
            'RON' => "Romania",
            'TRY' => 'Turkey',
            'ZAR' => 'South Africa',
            "MYR" => "Malaysian ringgit",
            "MXN" => "Mexican peso",
            "NZD" => "New Zealand dollar",
            "NOK" => "Norwegian krone",
            "PHP" => "Philippine peso",
            "PLN" => "Polish złoty",
            "GBP" => "Pound sterling",
            "RUB" => "Russian ruble",
            "SGD" => "Singapore dollar",
            "SEK" => "Swedish krona",
            "CHF" => "Swiss franc",
            "THB" => "Thai baht",
            "USD" => "United States dollar",
        ];

        return $data;
    }
}

if (!function_exists('convert_exchange')) {
    /**
     * @param string $from
     * @param string $to
     * @param $amount
     * @return float
     */
    function convert_exchange($from = "", $to = "", $amount)
    {
        $req_url = 'https://api.exchangeratesapi.io/latest?base=' . ($from ? $from : configs('currency_code', 'value'));
        $api = api_connect($req_url, "", true);

        if ($api !== null) {
            $base_price = $amount;
            $price = round(($base_price * $api['rates'][($to ? $to : configs('currency_code', 'value'))]), 2);
            return $price;
        } else {
            return $amount;
        }
    }
}

if (!function_exists('change_config')) {
    /**
     * Function config file config.php
     *
     * @param $driver
     * @param $host
     * @param $db_user
     * @param $db_password
     * @param $db_name
     * @param $timezone
     * @return void
     */
    function change_config($driver, $host, $db_user, $db_password, $db_name, $timezone)
    {
        $hash = create_random_api_key(16);

        change_file(APPPATH . "config/config.php", [
            // CONFIG MYSQL DATABASE
            '__HOSTNAME__' => $host,
            '__USERNAME__' => $db_user,
            '__PASSWORD__' => $db_password,
            '__DATABASE__' => $db_name,
            '__DBDRIVER__' => $driver,
            '__TIMEZONE__' => $timezone,
            '__SECURITY_CRONJOB_' => $hash,
        ]);
    }
}

if (!function_exists('encrypt_encrypt')) {
    /**
     * @param [type] $token
     * @param [type] $domain
     * @param [type] $string
     * @return void
     */
    function encrypt_encrypt($token, $domain, $string)
    {
        $output = false;
        $encrypt_method = "AES-256-CBC";

        $key = $token;
        $iv = substr($domain, 0, 16);

        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);

        return $output;
    }
}

if (!function_exists('encrypt_decrypt')) {
    /**
     *
     * @param [type] $action
     * @param [type] $string
     * @return void
     */
    function encrypt_decrypt($token, $domain, $string)
    {
        $output = false;
        $encrypt_method = "AES-256-CBC";

        $key = $token;
        $iv = substr($domain, 0, 16);

        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);

        return $output;
    }
}


if (!function_exists('timezone_list')) {
    /**
     * @return array
     */
    function timezone_list()
    {
        $zones = [];
        $dataZones = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
        foreach ($dataZones as $key => $zone) {
            date_default_timezone_set($zone);
            $zones[$key]['zone'] = $zone;
            $zones[$key]['time'] = "(UTC " . date('P', time()) . ") " . $zone;
        }

        return $zones;
    }
}

if (!function_exists('set_timezone')) {
    /**
     * Set Timezone default
     */
    function set_timezone()
    {
        $ci = &get_instance();
        $ci->load->helper('cookie');
        $timezone = config('timezone');

        $protocolo = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' || $_SERVER['SERVER_PORT'] == 443) ? true : false;
        if (!get_cookie('timezone')) {
            set_cookie([
                'name' => 'timezone',
                'value' => $timezone,
                'expire' => '2147483647',
                'secure' => $protocolo,
                'httponly' => true,
            ]);
        }

        if ($timezone != get_cookie('timezone')) {
            delete_cookie('timezone');
            set_cookie([
                'name' => 'timezone',
                'value' => $timezone,
                'expire' => '2147483647',
                'secure' => $protocolo,
                'httponly' => true,
            ]);
        }
    }
}

if (!function_exists('convert_time')) {
    /**
     * @param $datetime
     * @param $time_correct
     * @return string
     * @throws Exception
     */
    function convert_time($datetime, $time_correct)
    {
        $ci = &get_instance();
        $ci->load->helper('cookie');

        $timezone = get_cookie('timezone');
        $date = new DateTime($datetime, new DateTimeZone($timezone));
        $date->setTimezone(new DateTimeZone($time_correct));
        return $date->format('Y-m-d H:i:s');
    }
}

if (!function_exists("currency_format")) {
    /**
     * @param $number
     * @param string $number_decimal
     * @param string $decimalpoint
     * @param string $separator
     * @return string
     */
    function currency_format($number, $number_decimal = "", $decimalpoint = "", $separator = "")
    {
        $decimal = ($number_decimal == "" ? configs('currency_decimal', 'value') : $number_decimal);
        $decimalpoint = ($decimalpoint == "" ? configs('currency_decimal_separator', 'value') : $decimalpoint);
        $separator = ($separator == "" ? configs('currency_thousand_separator', 'value') : $separator);
        $number = number_format($number, $decimal, $decimalpoint, $separator);
        return $number;
    }
}

if (!function_exists('limit_str')) {
    /**
     * @param $var
     * @param $limit
     * @param null $question
     * @return string
     */
    function limit_str($var, $limit, $question = null)
    {
        $return = (strlen($var) > $limit) ? substr($var, 0, $limit) . '...' : $var;
        $question = ($question == null ? '<i class="fa fa-question-circle cursor-pointer font-weigh-bold text-danger" data-toggle="tooltip" data-placement="bottom" title="' . $var . '"></i>' : '');
        return (strlen($var) > $limit ? '' . $return . ' ' . $question . '' : $return);
    }
}

if (!function_exists('change_file')) {
    /**
     * Change value of variable
     *
     * @param $path
     * @param $search_opts
     */
    function change_file($path, $search_opts)
    {
        $FileContent = file_get_contents($path);

        $search = $search_opts;

        $result = strtr($FileContent, $search);
        file_put_contents($path, $result);
    }
}

if (!function_exists('file_read')) {
    /**
     *
     * @param [type] $path
     * @return void
     */
    function file_read($path)
    {
        $content = file_get_contents($path);
        return $content;
    }
}
