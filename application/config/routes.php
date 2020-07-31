<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|    example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|    https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|    $route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|    $route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|    $route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples: my-controller/index    -> my_controller/index
|           my-controller/my-method    -> my_controller/my_method
*/
$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = false;

// Cron
$route['cron/(:any)'] = 'cron/index/$1';

// Install System
$route['install'] = 'install/install/index';

// Register
$route['register'] = 'register/index';
$route['activation/account/(:any)'] = 'register/activation/$1';

// Login
$route['login'] = 'login/index';
$route['logoff'] = 'login/destroy';

// Pages
$route['faq'] = 'faq/index';
$route['terms'] = 'terms/index';

// Recover Password
$route['recover'] = 'recover/index';
$route['recover/token/(:any)'] = 'recover/show/$1';

// Profile
$route['profile'] = 'panel/profile/profile/index';
$route['profile/change/timezone'] = 'panel/profile/profile/change_timezone';
$route['profile/generate/token'] = 'panel/profile/profile/generate_new_token_api';
$route['profile/update'] = 'panel/profile/profile/update';

// History Orders
$route['orders'] = 'panel/history/user/orders/all';
$route['orders/(:num)'] = 'panel/history/user/orders/all/$1';
$route['orders/search'] = 'panel/history/user/orders/search';
$route['orders/(:any)'] = 'panel/history/user/orders/orders_for_status/$1';
$route['orders/(:any)/(:num)'] = 'panel/history/user/orders/orders_for_status/$1/$2';
# Subscriptions Orders
$route['subscriptions'] = 'panel/history/user/subscriptions/all';
$route['subscriptions/(:num)'] = 'panel/history/user/subscriptions/all/$1';
$route['subscriptions/search'] = 'panel/history/user/subscriptions/search';
$route['subscriptions/(:any)/(:num)'] = 'panel/history/user/subscriptions/status_subscription/$1/$2';
$route['subscriptions/type/(:any)'] = 'panel/history/user/subscriptions/subscription_for_status/$1';
$route['subscriptions/type/(:any)/(:num)'] = 'panel/history/user/subscriptions/subscription_for_status/$1/$2';

// Tickets
$route['tickets'] = 'panel/ticket/ticket/index';
$route['tickets/(:num)'] = 'panel/ticket/ticket/index/$1';
$route['admin/tickets'] = 'panel/ticket/ticket/index_admin';
$route['admin/tickets/(:num)'] = 'panel/ticket/ticket/index_admin/$1';
$route['ticket/show/(:any)'] = 'panel/ticket/ticket/show/$1';
$route['ticket/close/(:any)'] = 'panel/ticket/ticket/close_ticket/$1';
$route['ticket/destroy/(:any)'] = 'panel/ticket/ticket/destroy_ticket/$1';
$route['ticket/search'] = 'panel/ticket/ticket/search';

// Dashboard
$route['your/dashboard'] = 'panel/dashboard/dashboard/index';
$route['your/dashboard/(:num)'] = 'panel/dashboard/dashboard/index/$1';

// Settings Manager //
$route['admin/settings'] = 'panel/admin/management/settings/index';
$route['admin/settings/get'] = 'panel/admin/management/settings/get';
$route['admin/settings/generate/token'] = 'panel/admin/management/settings/generate_new_token';
$route['admin/settings/website-settings'] = 'panel/admin/management/settings/website_settings';
$route['admin/settings/notifications-settings'] = 'panel/admin/management/settings/notifications_settings';
$route['admin/settings/theme-setting'] = 'panel/admin/management/settings/change_theme';
$route['admin/settings/timezone-setting'] = 'panel/admin/management/settings/timezone_setting';
$route['admin/settings/currency-settings'] = 'panel/admin/management/settings/currency_settings';
$route['admin/settings/recaptcha-settings'] = 'panel/admin/management/settings/google_recaptcha';
$route['admin/settings/terms-policy-settings'] = 'panel/admin/management/settings/terms_policy_settings';
$route['admin/settings/email-settings'] = 'panel/admin/management/settings/email_settings';
$route['admin/settings/email-templates/(:any)'] = 'panel/admin/management/settings/email_templates/$1';
$route['admin/settings/others-settings'] = 'panel/admin/management/settings/others_settings';
$route['admin/settings/embed-settings'] = 'panel/admin/management/settings/embed_code_settings';
$route['admin/settings/update-register-page'] = 'panel/admin/management/settings/updateregisterPage';

// FAQ Manager //
$route['admin/faq'] = 'panel/admin/management/faq/index';
$route['admin/faq/edit/(:num)'] = 'panel/admin/management/faq/edit/$1';
$route['admin/faq/destroy/(:num)'] = 'panel/admin/management/faq/destroy/$1';

// Users Manager //
$route['admin/users'] = 'panel/admin/management/users/index';
$route['admin/users/(:num)'] = 'panel/admin/management/users/index/$1';
$route['admin/users/create'] = 'panel/admin/management/users/create';
$route['admin/users/show/(:any)'] = 'panel/admin/management/users/show/$1';
$route['admin/users/profile/update/(:any)'] = 'panel/admin/management/users/update_profile/$1';
$route['admin/users/role/update/(:any)'] = 'panel/admin/management/users/change_role/$1';
$route['admin/users/custom/rate/update/(:any)'] = 'panel/admin/management/users/change_custom_rate/$1';
$route['admin/users/change/password/(:any)'] = 'panel/admin/management/users/change_password/$1';
$route['admin/users/balance/(:any)'] = 'panel/admin/management/users/action_balance/$1';
$route['admin/users/banned/(:any)'] = 'panel/admin/management/users/action_ban/$1';
$route['admin/users/search'] = 'panel/admin/management/users/search';
$route['admin/users/destroy/(:any)'] = 'panel/admin/management/users/destroy/$1';

// Category //
$route['admin/category'] = 'panel/admin/management/category/index';
$route['admin/category/(:num)'] = 'panel/admin/management/category/index/$1';
$route['admin/category/create'] = 'panel/admin/management/category/create';
$route['admin/category/edit/(:num)'] = 'panel/admin/management/category/edit/$1';
$route['admin/category/destroy/(:num)'] = 'panel/admin/management/category/destroy/$1';

// API Providers //
$route['admin/api/providers'] = 'panel/admin/management/api_providers/index';
$route['admin/api/providers/services/(:any)'] = 'panel/admin/management/api_providers/show/$1';
$route['admin/api/providers/create'] = 'panel/admin/management/api_providers/store';
$route['admin/api/providers/edit/(:num)'] = 'panel/admin/management/api_providers/edit/$1';
$route['admin/api/providers/destroy/(:num)'] = 'panel/admin/management/api_providers/destroy/$1';
$route['admin/api/update/balance/(:num)'] = 'panel/admin/management/api_providers/update_balance/$1';
$route['admin/api/service/create'] = 'panel/admin/management/api_providers/add_service_via_api';
$route['admin/api/sync/services/(:num)'] = 'panel/admin/management/api_providers/sync_services_api/$1';

// Admin History Orders
$route['admin/orders'] = 'panel/history/admin/orders/all';
$route['admin/orders/(:num)'] = 'panel/history/admin/orders/all/$1';
$route['admin/orders/search'] = 'panel/history/admin/orders/search';
$route['admin/orders/(:any)'] = 'panel/history/admin/orders/orders_for_status/$1';
$route['admin/orders/(:any)/(:num)'] = 'panel/history/admin/orders/orders_for_status/$1/$2';
$route['admin/orders/actions/(:any)/(:num)'] = 'panel/history/admin/orders/actions/$1/$2';
# Admin History Subscriptions
$route['admin/subscriptions'] = 'panel/history/admin/subscriptions/all';
$route['admin/subscriptions/(:num)'] = 'panel/history/admin/subscriptions/all/$1';
$route['admin/subscriptions/search'] = 'panel/history/admin/subscriptions/search';
$route['admin/subscriptions/(:any)/(:num)'] = 'panel/history/admin/subscriptions/status_subscription/$1/$2';
$route['admin/subscriptions/type/(:any)'] = 'panel/history/admin/subscriptions/subscription_for_status/$1';
$route['admin/subscriptions/type/(:any)/(:num)'] = 'panel/history/admin/subscriptions/subscription_for_status/$1/$2';

// Services //
$route['services'] = 'panel/admin/management/services/index_user';
$route['admin/services'] = 'panel/admin/management/services/index_admin';
$route['admin/services/search'] = 'panel/admin/management/services/search';
$route['admin/services/create'] = 'panel/admin/management/services/store';
$route['admin/services/edit/(:num)'] = 'panel/admin/management/services/edit/$1';
$route['admin/services/destroy/(:num)'] = 'panel/admin/management/services/destroy/$1';

// News //
$route['admin/news'] = 'panel/admin/management/news/index';
$route['admin/news/(:num)'] = 'panel/admin/management/news/index/$1';
$route['admin/news/store'] = 'panel/admin/management/news/store';
$route['admin/news/edit/(:num)'] = 'panel/admin/management/news/edit/$1';
$route['admin/news/destroy/(:num)'] = 'panel/admin/management/news/destroy/$1';

// Order //
$route['new/order'] = 'panel/order/order/index';
$route['service_list_order'] = 'panel/order/order/get_category_ajax';
$route['order/store'] = 'panel/order/order/store';
$route['order/mass'] = 'panel/order/order/mass_order';

// Logs //
$route['admin/logs'] = 'panel/admin/management/logs/index';
$route['admin/logs/(:num)'] = 'panel/admin/management/logs/index/$1';
$route['admin/logs/destroy/(:num)'] = 'panel/admin/management/logs/destroy/$1';

// Payments Integrations //
$route['admin/payments'] = 'panel/admin/management/payments/payments/index';
$route['admin/payments/update-payment-status/(:any)'] = 'panel/admin/management/payments/payments/updateStatusPayment/$1';
$route['admin/payment/edit/(:any)'] = 'panel/admin/management/payments/payments/edit_payment/$1';
# Payments
$route['paypal/create_payment'] = 'panel/admin/management/payments/paypal_pay/index';
$route['paypal/processing'] = 'panel/admin/management/payments/paypal_pay/completed';
$route['paypal/success'] = 'panel/admin/management/payments/paypal_pay/success';
$route['paypal/cancel'] = 'panel/admin/management/payments/paypal_pay/cancel';
$route['pagseguro/create_payment'] = 'panel/admin/management/payments/pagseguro_pay/index';
$route['mercadopago/create_payment'] = 'panel/admin/management/payments/mercadopago_pay/index';
$route['stripe/proccess'] = 'panel/admin/management/payments/stripe_pay/index';
$route['stripe/create_payment'] = 'panel/admin/management/payments/stripe_pay/create_payment';
$route['stripe/success'] = 'panel/admin/management/payments/stripe_pay/success';
$route['stripe/completed'] = 'panel/admin/management/payments/stripe_pay/completed';
$route['stripe/cancel'] = 'panel/admin/management/payments/stripe_pay/cancel';
$route['twocheckout/proccess'] = 'panel/admin/management/payments/twocheckout_pay/index';
$route['twocheckout/create_payment'] = 'panel/admin/management/payments/twocheckout_pay/create_payment';
$route['twocheckout/success'] = 'panel/admin/management/payments/twocheckout_pay/success';
$route['twocheckout/completed'] = 'panel/admin/management/payments/twocheckout_pay/completed';
$route['twocheckout/cancel'] = 'panel/admin/management/payments/twocheckout_pay/cancel';
$route['coinpayments/proccess'] = 'panel/admin/management/payments/coinpayments_pay/index';
$route['coinpayments/create_payment'] = 'panel/admin/management/payments/coinpayments_pay/create_payment';
$route['coinpayments/success'] = 'panel/admin/management/payments/coinpayments_pay/success';
$route['coinpayments/completed'] = 'panel/admin/management/payments/coinpayments_pay/completed';
$route['skrill/create_payment'] = 'panel/admin/management/payments/skrill_pay/index';
$route['skrill/success'] = 'panel/admin/management/payments/skrill_pay/success';
$route['skrill/cancel'] = 'panel/admin/management/payments/skrill_pay/cancel';
$route['skrill/status'] = 'panel/admin/management/payments/skrill_pay/status';
$route['payumoney/step_one'] = 'panel/admin/management/payments/payumoney_pay/index';
$route['payumoney/create_payment'] = 'panel/admin/management/payments/payumoney_pay/create_payment';
$route['payumoney/step_two'] = 'panel/admin/management/payments/payumoney_pay/proccess';
$route['payumoney/confirm'] = 'panel/admin/management/payments/payumoney_pay/confirm_pay';
$route['payumoney/status'] = 'panel/admin/management/payments/payumoney_pay/status';
$route['paytm/step_one'] = 'panel/admin/management/payments/paytm_pay/index';
$route['paytm/create_payment'] = 'panel/admin/management/payments/paytm_pay/create_payment';
$route['paytm/redirect'] = 'panel/admin/management/payments/paytm_pay/success';
$route['paytm/response'] = 'panel/admin/management/payments/paytm_pay/response';
$route['instamojo/create_payment'] = 'panel/admin/management/payments/instamojo_pay/index';
$route['instamojo/response'] = 'panel/admin/management/payments/instamojo_pay/status';
$route['mollie/create_payment'] = 'panel/admin/management/payments/mollie_pay/index';
$route['mollie/success'] = 'panel/admin/management/payments/mollie_pay/success';
$route['razorpay/create_payment'] = 'panel/admin/management/payments/razorpay_pay/index';
$route['razorpay/redirect'] = 'panel/admin/management/payments/razorpay_pay/redirect';

// Add Balance //
$route['add/balance'] = 'panel/addbalance/addbalance/index';

// Transaction Logs //
$route['transactions/user'] = 'panel/admin/management/transaction_logs/index_user';
$route['transactions/user/(:num)'] = 'panel/admin/management/transaction_logs/index_user/$1';
$route['admin/transactions'] = 'panel/admin/management/transaction_logs/index_admin';
$route['admin/transactions/(:num)'] = 'panel/admin/management/transaction_logs/index_admin/$1';
$route['admin/transaction/search'] = 'panel/admin/management/transaction_logs/search';
$route['admin/transaction/delete/(:num)'] = 'panel/admin/management/transaction_logs/destroy/$1';

// Language
$route['admin/language'] = 'panel/admin/management/language/index';
$route['admin/language/action/add'] = 'panel/admin/management/language/create';
$route['admin/language/edit/(:any)'] = 'panel/admin/management/language/edit/$1';
$route['admin/language/update/(:any)'] = 'panel/admin/management/language/update/$1';
$route['admin/language/destroy/(:any)'] = 'panel/admin/management/language/destroy/$1';
$route['language/(:any)'] = 'setlanguage/set_language/$1';
$route['language/no-select/idiom'] = 'setlanguage/no_select_language';

// API
$route['api'] = 'panel/admin/management/api/index';
$route['api/v2'] = 'panel/admin/management/api/v2';
