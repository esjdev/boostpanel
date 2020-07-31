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
    <!-- style css required (do not remove) -->
    <style>.loading:after {content: '<?= lang("loading") . "... " . lang("loading_patient_wait") . ""; ?>';}</style>
    <script src="<?= set_js('jquery-3.4.1.min.js'); ?>"></script>
    <script>var base = '<?= base_url(); ?>';</script>
    <?= configs('javascript_embed_header', 'value'); ?>
</head>

<body>
    <?php if (in_array(uri(1), ['', 'home'])) : ?>
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
    <header class="main_menu home_menu">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-sm-12">
                    <nav class="navbar navbar-expand-lg navbar-light">
                        <a class="navbar-brand" href="<?= base_url(); ?>"><img src="<?= set_image(configs('website_logo', 'value')); ?>" class="logo" alt="logo-boostpanel"></a>

                        <button class="un-auth-navbar-toggler navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                        <div class="collapse navbar-collapse main-menu-item" id="navbarSupportedContent">
                            <ul class="navbar-nav align-items-center font-weight-normal ml-auto">
                                <li class="nav-item">
                                    <a class="nav-link <?= (in_array(uri(1), ['', 'home']) ? 'active' : ''); ?>" href="<?= base_url(); ?>"><i class="fa fa-home"></i> <?= lang("menu_home"); ?></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?= active_menu('services'); ?>" href="<?= base_url("services"); ?>"><i class="fa fa-list"></i> <?= lang("menu_services"); ?></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?= active_menu('faq'); ?>" href="<?= base_url('faq'); ?>"><i class="fa fa-question-circle"></i> <?= lang("menu_faq"); ?></a>
                                </li>

                                <?php if (!logged()) : ?>
                                    <li class="d-none d-lg-block">
                                        <a class="btn_4 bg-info mr-3 ml-3 font-weight-normal rounded" href="<?= base_url('login'); ?>"><?= lang("button_login"); ?></a>
                                    </li>

                                    <?php if (configs('registration_page', 'value') == 'on') : ?>
                                        <li class="d-none d-lg-block">
                                            <a class="btn_4 font-weight-normal rounded" href="<?= base_url('register'); ?>"><?= lang("button_register"); ?></a>
                                        </li>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <li class="d-none d-lg-block">
                                        <a class="btn_4 bg-info font-weight-normal fs-14 rounded ml-2" href="<?= base_url('your/dashboard'); ?>"><?= lang("menu_access_panel"); ?></a>
                                        <a class="btn_4 bg-danger font-weight-normal fs-14 rounded ml-2" href="<?= base_url('logoff'); ?>"><?= lang("menu_logoff"); ?></a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </nav>

                    <?php if (flashdata('user_banned')): ?>
                        <div class="bg-danger text-white p-2 mt-3 rounded" role="alert">
                            <i class="fa fa-exclamation-triangle"></i> <span class="error-message"><?= flashdata('user_banned'); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>
    <!-- Header part end-->
