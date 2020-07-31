<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="<?= html_escape(configs('meta_description', 'value')); ?>">
    <meta name="keywords" content="<?= html_escape(configs('meta_keywords', 'value')); ?>">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="HandheldFriendly" content="True">
    <meta name="MobileOptimized" content="320">
    <title><?= html_escape($title); ?></title>
    <?= link_tag('public/themes/' . config('theme') . '/images/' . configs('website_favicon', 'value'), 'shortcut icon', 'image/ico'); ?>
    <!-- Google fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
    <!-- Bootstrap CSS -->
    <?= set_css('bootstrap.min.css'); ?>
    <?= set_css('bootstrap-toggle.min.css'); ?>
    <?= set_css('bootstrap-datepicker.min.css'); ?>
    <!-- animate CSS -->
    <?= set_css('animate.min.css'); ?>
    <!-- fontawesome CSS -->
    <?= set_css('font-awesome.min.css'); ?>
    <!-- aos CSS -->
    <?= set_css('aos.min.css'); ?>
    <!-- style CSS -->
    <?= set_css('style.min.css'); ?>
    <!-- fonts size CSS -->
    <?= set_css('fontsize.min.css'); ?>
    <!-- sweetalert2 CSS -->
    <?= set_css('sweetalert2.min.css'); ?>
    <!-- c3chart CSS -->
    <link href="<?= set_js('plugins/c3_chart/c3.min.css'); ?>" rel="stylesheet" type="text/css" />
    <!-- style css required (do not remove) -->
    <style>.loading:after {content: '<?= lang("loading") . "... " . lang("loading_patient_wait") . ""; ?>';}</style>
    <script src="<?= set_js('jquery-3.4.1.min.js'); ?>"></script>
    <script>var base = '<?= base_url(); ?>';</script>
	<?= configs('javascript_embed_header', 'value'); ?>
</head>

<body>
    <?php if (uri(1) == 'your' && uri(2) == 'dashboard') : ?>
        <div class="preloader">
            <div class="loader">
                <div class="ytp-spinner">
                    <div class="ytp-spinner-container">
                        <div class="ytp-spinner-rotator">
                            <div class="ytp-spinner-left">
                                <div class="ytp-spinner-circle"></div>
                            </div>
                            <div class="ytp-spinner-right">
                                <div class="ytp-spinner-circle"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div id="page-overlay" class="visible incoming">
        <div class="loader-wrapper-outer">
            <div class="loader-wrapper-inner">
                <div class="loader">
                    <div class="balls mx-auto">
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                    <br>
                    <div class="loading"></div>
                </div>
            </div>
        </div>
    </div>

    <!--::header part start::-->
    <header class="main_menu home_menu border-shadow">
        <div class="container-fluid bg-other-blue">
            <div class="row align-items-center">
                <div class="col-sm-12">
                    <nav class="navbar navbar-expand-lg navbar-light">
                        <?php /** icon logs visible only admin */ if (userLevel(logged(), 'admin')) : ?>
                            <div class="d-none d-lg-block">
                                <div class="row">
                                    <a href="<?= base_url("admin/logs"); ?>"><span class="badge-notification text-white" data-badge="<?= countLogs(true); ?>"><i class="fa fa-bell fa-2x text-grey-other"></i></span></a>
                                </div>
                            </div>
                        <?php endif; ?>

                        <a class="navbar-brand mx-auto" href="<?= base_url('your/dashboard'); ?>"><img src="<?= set_image('logo_white.png'); ?>" class="logo" alt="logo"></a>

                        <?php /** icon Profile to user logged */ if (logged()) : ?>
                            <div class="d-none d-lg-block">
                                <ul class="nav navbar-menu align-items-center">
                                    <li class="dropdown">
                                        <a href="javascript:void(0);" data-toggle="dropdown" class="nav-link dropdown d-flex align-items-center py-0 px-lg-0 px-2 text-color ml-2">
                                            <span class="ml-2 d-none d-lg-block leading-none">
                                                <span class="text-white text-right d-block mt-1">
                                                    <strong><?= lang("balance"); ?>:</strong><br>
                                                    <?= (userLevel(logged(), 'admin') ? lang("undefined") : configs('currency_symbol', 'value') . "" . userBalance(logged())); ?>
                                                </span>
                                            </span>
                                            <span class="avatar"></span>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            <div class="dropdown-item text-center d-block d-lg-none">
                                                <strong><?= lang("balance"); ?>:</strong><br>
                                                <?= (userLevel(logged(), 'admin') ? lang("undefined") : configs('currency_symbol', 'value') . "" . userBalance(logged())); ?>
                                            </div>
                                            <a class="dropdown-item" href="<?= base_url("profile"); ?>"><i class="fa fa-user dropdown-icon"></i> <?= lang("menu_account_panel"); ?></a>
                                            <a class="dropdown-item" href="<?= base_url("logoff"); ?>"><i class="fa fa-cog dropdown-icon"></i> <?= lang("menu_logoff"); ?></a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        <?php endif;
                        /** end Profile to user logged */ ?>
                    </nav>
                </div>
            </div>
        </div>

        <nav class="navbar navbar-expand-lg navbar-light bg-white border-shadow">
            <button class="auth-navbar-toggler navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <?php /** Select Language Mobile */ if (langs_row()) : ?>
                <div class="d-block d-lg-none change-language-button-mobile cursor-pointer m-l-15">
                    <i class="fa fa-globe fa-2x text-blue-light"></i>
                </div>

                <div class="d-block d-lg-none">
                    <div class="change-language-show-mobile dropdown-menu p-2 m-t-50" style="display:none;">
                        <?php langs_footer(); ?>
                    </div>
                </div>
            <?php endif;
            /** End Select Language Mobile */ ?>

            <?php /** icon logs visible only admin in mobile */ if (userLevel(logged(), 'admin')) : ?>
                <div class="d-block d-lg-none ml-auto">
                    <a href="<?= base_url("admin/logs"); ?>"><span class="badge-notification" data-badge="<?= countLogs(true); ?>"><i class="fa fa-bell fa-2x"></i></span></a>
                </div>
            <?php endif; ?>

            <?php /** icon Profile to user logged only in mobile */ if (logged()) : ?>
                <div class="d-block d-lg-none">
                    <ul class="nav navbar-menu">
                        <li class="dropdown">
                            <a href="javascript:void(0);" data-toggle="dropdown" class="nav-link d-flex align-items-center py-0 px-lg-0 px-2 text-color ml-2">
                                <span class="ml-2 d-none d-lg-block leading-none">
                                    <span class="text-muted text-right d-block mt-1">
                                        <strong><?= lang("balance"); ?>:</strong><br>
                                        <?= (userLevel(logged(), 'admin') ? lang("undefined") : configs('currency_symbol', 'value') . "" . userBalance(logged())); ?>
                                    </span>
                                </span>
                                <span class="avatar"></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                <div class="dropdown-item text-center d-block d-lg-none">
                                    <strong><?= lang("balance"); ?>:</strong><br>
                                    <?= (userLevel(logged(), 'admin') ? lang("undefined") : configs('currency_symbol', 'value') . "" . userBalance(logged())); ?>
                                </div>
                                <a class="dropdown-item" href="<?= base_url("profile"); ?>"><i class="fa fa-user dropdown-icon"></i> <?= lang("menu_account_panel"); ?></a>
                                <a class="dropdown-item" href="<?= base_url("logoff"); ?>"><i class="fa fa-cog dropdown-icon"></i> <?= lang("menu_logoff"); ?></a>
                            </div>
                        </li>
                    </ul>
                </div>
            <?php endif;
            /** end Profile to user logged */ ?>

            <div class="collapse navbar-collapse main-menu-item" id="navbarSupportedContent">
                <ul class="navbar-nav align-items-center font-weight-normal mx-auto">
                    <li class="nav-item">
                        <a class="nav-link <?= active_menu('your/dashboard'); ?>" href="<?= base_url('your/dashboard'); ?>"><i class="fa fa-dashboard"></i> <?= lang("menu_dashboard"); ?></a>
                    </li>

                    <?php /** Type of account: USER */ if (userLevel(logged(), 'user')) : ?>
                        <li class="nav-item">
                            <a class="nav-link <?= active_menu('new/order'); ?>" href="<?= base_url("new/order"); ?>"><i class="fa fa-shopping-cart"></i> <?= lang("menu_new_order"); ?></a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="javascript:void(0);" id="dropdownMenuOrderUser" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-history"></i> <?= lang("menu_order_history"); ?>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuOrderUser">
                                <a class="dropdown-item <?= active_menu('transactions/user'); ?>" href="<?= base_url("transactions/user"); ?>"><?= lang("menu_transaction_logs"); ?></a>
                                <a class="dropdown-item <?= active_menu('orders'); ?>" href="<?= base_url("orders"); ?>"><?= lang("menu_order"); ?></a>
                                <a class="dropdown-item <?= active_menu('subscriptions'); ?>" href="<?= base_url('subscriptions'); ?>"><?= lang("menu_subscription"); ?></a>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= active_menu('add/balance'); ?>" href="<?= base_url('add/balance'); ?>"><i class="fa fa-money"></i> <?= lang("menu_add_balance"); ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= active_menu('services'); ?>" href="<?= base_url("services"); ?>"><i class="fa fa-list"></i> <?= lang("menu_services"); ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= active_menu('tickets'); ?>" href="<?= base_url("tickets"); ?>"><i class="fa fa-life-ring"></i> <?= lang("menu_support"); ?> <?= (countTicket() > 0) ? '<span class="badge badge-green">' . countTicket() . '</span>' : ''; ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= active_menu('api'); ?>" href="<?= base_url('api'); ?>"><i class="fa fa-cubes"></i> <?= lang("menu_api"); ?></a>
                        </li>
                    <?php endif;
                    /** End menu to user logged */ ?>

                    <?php /** Type of account: SUPPORT */ if (userLevel(logged(), 'support')) : ?>
                        <li class="nav-item <?= active_menu('admin/transactions'); ?>">
                            <a class="nav-link <?= active_menu('admin/transactions'); ?>" href="<?= base_url("admin/transactions"); ?>"><i class="fa fa-history"></i> <?= lang("menu_transaction_logs"); ?></a>
                        </li>
                        <li class="nav-item <?= active_menu('admin/orders'); ?>">
                            <a class="nav-link <?= active_menu('admin/orders'); ?>" href="<?= base_url("admin/orders"); ?>"><i class="fa fa-history"></i> <?= lang("menu_orders"); ?></a>
                        </li>
                        <li class="nav-item <?= active_menu('admin/subscriptions'); ?>">
                            <a class="nav-link <?= active_menu('admin/subscriptions'); ?>" href="<?= base_url("admin/subscriptions"); ?>"><i class="fa fa-history"></i> <?= lang("menu_subscription"); ?></a>
                        </li>
                        <li class="nav-item <?= active_menu('admin/tickets'); ?>">
                            <a class="nav-link" href="<?= base_url("admin/tickets"); ?>"><i class="fa fa-life-ring"></i> <?= lang("menu_support"); ?>
                                <?= (countTicket() > 0) ? '<span class="badge badge-green">' . countTicket() . '</span>' : ''; ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= active_menu('api'); ?>" href="<?= base_url('api'); ?>"><i class="fa fa-cubes"></i> <?= lang("menu_api"); ?></a>
                        </li>
                    <?php endif;
                    /** End menu to support logged */ ?>

                    <?php /** Type of account: ADMIN */ if (userLevel(logged(), 'admin')) : ?>
                        <li class="nav-item">
                            <a class="nav-link <?= active_menu('admin/category'); ?>" href="<?= base_url("admin/category"); ?>"><i class="fa fa-table"></i> <?= lang("menu_category"); ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= active_menu('admin/services'); ?>" href="<?= base_url("admin/services"); ?>"><i class="fa fa-list"></i> <?= lang("menu_services"); ?></a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="javascript:void(0);" id="dropdownMenuOrderAdmin" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-history"></i> <?= lang("menu_order_history"); ?>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuOrderAdmin">
                                <a class="dropdown-item <?= active_menu('admin/transactions'); ?>" href="<?= base_url("admin/transactions"); ?>"><?= lang("menu_transaction_logs"); ?></a>
                                <a class="dropdown-item <?= active_menu('admin/orders'); ?>" href="<?= base_url("admin/orders"); ?>"><?= lang("menu_order"); ?></a>
                                <a class="dropdown-item <?= active_menu('admin/subscriptions'); ?>" href="<?= base_url('admin/subscriptions'); ?>"><?= lang("menu_subscription"); ?></a>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= active_menu('admin/tickets'); ?>" href="<?= base_url("admin/tickets"); ?>"><i class="fa fa-life-ring"></i> <?= lang("menu_support"); ?>
                                <?= (countTicket() > 0) ? '<span class="badge badge-green">' . countTicket() . '</span>' : ''; ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= active_menu('api'); ?>" href="<?= base_url('api'); ?>"><i class="fa fa-cubes"></i> <?= lang("menu_api"); ?></a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="javascript:void(0);" id="dropdownMenuAdmin" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-cog"></i> <?= lang("menu_panel_admin"); ?>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuAdmin">
                                <h5 class="dropdown-header fs-15 bg-secondary text-white"><?= lang("menu_settings"); ?></h5>
                                <a class="dropdown-item <?= active_menu('admin/settings'); ?>" href="<?= base_url('admin/settings'); ?>"><?= lang("menu_settings"); ?></a>
                                <a class="dropdown-item <?= active_menu('admin/payments'); ?>" href="<?= base_url('admin/payments'); ?>"><?= lang("menu_payment_integrations"); ?></a>
                                <a class="dropdown-item <?= active_menu('admin/api/providers'); ?>" href="<?= base_url("admin/api/providers"); ?>"><?= lang("menu_api_providers"); ?></a>
                                <a class="dropdown-item <?= active_menu('admin/users'); ?>" href="<?= base_url("admin/users"); ?>"><?= lang("menu_users"); ?></a>
                                <a class="dropdown-item <?= active_menu('admin/news'); ?>" href="<?= base_url('admin/news'); ?>"><?= lang("menu_news_management"); ?></a>

                                <h5 class="dropdown-header fs-15 bg-secondary text-white"><?= lang("menu_others_settings"); ?></h5>

                                <a class="dropdown-item <?= active_menu('admin/faq'); ?>" href="<?= base_url('admin/faq'); ?>"><?= lang("menu_faq_settings"); ?></a>
                                <a class="dropdown-item <?= active_menu('admin/language'); ?>" href="<?= base_url('admin/language'); ?>"><?= lang('menu_language_settings'); ?></a>
                            </div>
                        </li>
                    <?php endif;
                    /** End menu to admin logged */ ?>
                </ul>
            </div>
        </nav>
    </header>
    <!-- Header part end-->