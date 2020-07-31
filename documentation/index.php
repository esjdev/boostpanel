<?php
session_start();

if (isset($_COOKIE['lang'])) {
    if (file_exists('lang/lang_' . $_COOKIE['lang'] . '.php')) {
        require_once 'lang/lang_' . $_COOKIE['lang'] . '.php';
    } else {
        require_once 'lang/lang_en.php';
    }
} else {
    require_once 'lang/lang_en.php';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>BoostPanel - <?= $lang['lang_title']; ?></title>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= $lang['lang_description']; ?>">
    <link rel="shortcut icon" href="favicon.ico">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
    <!-- FontAwesome JS -->
    <script defer src="https://use.fontawesome.com/releases/v5.8.2/js/all.js" integrity="sha384-DJ25uNYET2XCl5ZF++U8eNxPWqcKohUUBUpKGlNLMchM7q4Wjg2CUpjHLaL8yYPH" crossorigin="anonymous"></script>
    <!-- Global CSS -->
    <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">
    <!-- Plugins CSS -->
    <link rel="stylesheet" href="assets/plugins/prism/prism.css">
    <link rel="stylesheet" href="assets/plugins/elegant_font/css/style.css">
    <!-- Theme CSS -->
    <link id="theme-style" rel="stylesheet" href="assets/css/styles.css">
</head>

<body class="body-green">
    <div class="page-wrapper">
        <!-- ******Header****** -->
        <header id="header" class="header">
            <div class="container">
                <div class="branding">
                    <h1 class="logo d-flex justify-content-center">
                        <a href="index.php">
                            <img src="assets/images/logo_white.png" alt="image">
                        </a>
                        <ul class="list-inline d-flex justify-content-center ml-3">
                            <li class="list-inline-item"><a href="english"><img src="assets/images/us.png" alt="image" width="25"></a>
                            </li>
                            <li class="list-inline-item"><a href="brazilian"><img src="assets/images/br.png" alt="image" width="25"></a>
                            </li>
                        </ul>
                    </h1>
                </div>
                <!--//branding-->
            </div>
            <!--//container-->
        </header>
        <!--//header-->
        <div class="doc-wrapper">
            <div class="container">
                <div id="doc-header" class="doc-header text-center">
                    <h1 class="doc-title"><i class="icon fa fa-paper-plane"></i> <?= $lang['lang_title']; ?></h1>
                </div>
                <!--//doc-header-->
                <div class="doc-body row">
                    <div class="doc-content col-md-9 col-12 order-1">
                        <div class="content-inner">

                            <section id="introduction" class="doc-section">
                                <h2 class="section-title"><?= $lang['lang_introduction']; ?></h2>
                                <div class="section-block">
                                    <p><?= $lang['lang_subintroduction']; ?></p>

                                    <div class="alert alert-danger mt-3">
                                        <i class="fas fa-exclamation-circle"></i> <?= $lang['lang_recommended_https']; ?>
                                    </div>
                                </div>
                            </section>
                            <!--//doc-section-->

                            <section id="requirements" class="doc-section">
                                <h2 class="section-title"><?= $lang['lang_requirements']; ?></h2>
                                <p class="mt-3">
                                    <i class="fas fa-circle-notch text-success"></i> PHP >= 5.6<br>
                                    <i class="fas fa-circle-notch text-success"></i> PHP CURL<br>
                                    <i class="fas fa-circle-notch text-success"></i> PHP OpenSSL<br>
                                    <i class="fas fa-circle-notch text-success"></i> PHP PDO<br>
                                    <i class="fas fa-circle-notch text-success"></i> Mod Rewrite<br>
                                    <i class="fas fa-circle-notch text-success"></i> Mbstring PHP Extension<br>
                                    <i class="fas fa-circle-notch text-success"></i> Allow url fopen On<br>
                                    <i class="fas fa-circle-notch text-success"></i> Zip Extension<br>
                                    <i class="fas fa-circle-notch text-success"></i> Configured Cronjob<br>
                                </p>
                            </section>

                            <section id="installation" class="doc-section">
                                <h2 class="section-title"><?= $lang['lang_installation']; ?></h2>
                                <div id="start_installation" class="section-block">
                                    <?= $lang['lang_step_installation']; ?>
                                </div>
                                <!--//section-block-->
                            </section>
                            <!--//doc-section-->

                            <section id="configure_cronjob" class="doc-section">
                                <h2 class="section-title"><?= $lang['lang_configure_cronjob']; ?></h2>
                                <div class="section-block">
                                    <p>
                                        <?= $lang['lang_subcronjob']; ?><br><br>

                                        <ol>
                                            <li><?= $lang['lang_cronjob_how_access_token']; ?></li>
                                        </ol>
                                    </p>

                                    <a href="assets/images/cronjob_token.png" target="_blank">
                                        <img src="assets/images/cronjob_token.png" class="img-fluid rounded" alt="image">
                                    </a>

                                    <div class="alert alert-info mt-3">
                                        <div class="text-dark">
                                            <strong><?= $lang['lang_note']; ?></strong><br>
                                            <?= sprintf($lang['lang_note_cronjob'], '<img src="assets/images/icon_update_token_cron.png" class="img-fluid" alt="image">'); ?></div>
                                    </div>

                                    <div class="callout-block callout-success mt-3">
                                        <div class="icon-holder">
                                            <i class="fas fa-thumbs-up"></i>
                                        </div>
                                        <!--//icon-holder-->
                                        <div class="content">
                                            <h4 class="callout-title"><?= $lang['lang_links_cronjob']; ?></h4><br>
                                            <p>
                                                <code>https://yourwebsite.com/cron/orders?security=<span class="text-danger"><?= $lang['lang_your_token_cronjob']; ?></span></code><br><br>
                                                <code>https://yourwebsite.com/cron/subscriptions?security=<span class="text-danger"><?= $lang['lang_your_token_cronjob']; ?></span></code><br><br>
                                                <code>https://yourwebsite.com/cron/status_orders?security=<span class="text-danger"><?= $lang['lang_your_token_cronjob']; ?></span></code><br><br>
                                                <code>https://yourwebsite.com/cron/status_subscriptions?security=<span class="text-danger"><?= $lang['lang_your_token_cronjob']; ?></span></code><br><br>
                                                <code>https://yourwebsite.com/cron/payments_status?security=<span class="text-danger"><?= $lang['lang_your_token_cronjob']; ?></span></code>
                                            </p>
                                        </div>
                                        <!--//content-->
                                    </div>

                                    <h4><?= $lang['lang_example_links_cronjob']; ?></h4>
                                    <br>

                                    <a href="assets/images/cronjob_config.png" target="_blank">
                                        <img src="assets/images/cronjob_config.png" class="img-fluid rounded" alt="image">
                                    </a>
                                </div>
                            </section>

                            <section id="api_providers" class="doc-section">
                                <h2 class="section-title"><?= $lang['lang_api_providers']; ?></h2>
                                <div class="section-block">
                                    <p>
                                        <?= $lang['lang_how_using_api_third']; ?><br><br>

                                        <ol>
                                            <li><?= $lang['lang_go_menu_admin_api_providers']; ?></li>
                                        </ol>

                                        <a href="assets/images/api_providers/api_providers.png" target="_blank">
                                            <img src="assets/images/api_providers/api_providers.png" class="img-fluid rounded" alt="image">
                                        </a><br><br>

                                        <h5><?= $lang['lang_add_api_provider']; ?></h5><br>

                                        <a href="assets/images/api_providers/add_api_provider.png" target="_blank">
                                            <img src="assets/images/api_providers/add_api_provider.png" class="img-fluid rounded" alt="image">
                                        </a><br><br>
                                    </p>

                                    <div class="alert alert-info">
                                        <div class="text-dark">
                                            <strong><?= $lang['lang_note']; ?></strong><br>
                                            <?= $lang['lang_note_api_providers']; ?>
                                        </div>
                                    </div>

                                    <div class="callout-block callout-success mt-3">
                                        <div class="icon-holder">
                                            <i class="fas fa-thumbs-up"></i>
                                        </div>
                                        <!--//icon-holder-->
                                        <div class="content">
                                            <h4 class="callout-title"><?= $lang['lang_congratulations_text']; ?></h4><br>
                                            <p>
                                                <b>Name</b> - API Name<br>
                                                <b>API Url</b> - Example <em>https://serviceapi.com/api/v2</em><br>
                                                <b>Parameter Type</b> - Select the API parameter correctly <em>(key or
                                                    api_token)</em><br>
                                                <b>API Key</b> - Get the secret key of the API<br>
                                                <b>Status</b> - Enables and Disables the System API<br><br>

                                                <span class="text-danger"><strong><?= $lang['lang_note']; ?>:</strong> <?= $lang['lang_note_success_api_provider']; ?></span>
                                            </p>
                                        </div>
                                        <!--//content-->
                                    </div>
                                </div>
                            </section>

                            <section id="google_recaptcha" class="doc-section">
                                <h2 class="section-title">Google reCAPTCHA V2</h2>
                                <div class="section-block">
                                    <p>
                                        <?= $lang['lang_subgooglerecaptcha']; ?><br><br>

                                        <ol>
                                            <strong>1.</strong> <?= sprintf($lang['lang_access_google_recaptcha'], 'https://www.google.com/recaptcha/intro/v3.html'); ?>
                                        </ol>

                                        <a href="assets/images/google_recaptcha/google_recaptcha.png" target="_blank">
                                            <img src="assets/images/google_recaptcha/google_recaptcha.png" class="img-fluid rounded" alt="image">
                                        </a><br>

                                        <div class="alert alert-info mt-3">
                                            <div class="text-dark">
                                                <strong><?= $lang['lang_note']; ?></strong><br>
                                                <?= $lang['lang_note_google_recaptcha']; ?>
                                            </div>
                                        </div>
                                    </p>

                                    <h5><?= $lang['lang_see_screen']; ?></h5><br>

                                    <a href="assets/images/google_recaptcha/register_new_google_recaptcha.png" target="_blank">
                                        <img src="assets/images/google_recaptcha/register_new_google_recaptcha.png" class="img-fluid rounded" alt="image">
                                    </a><br><br>

                                    <h5><?= $lang['lang_fill_out_form_img_google_recaptcha']; ?></h5><br>

                                    <a href="assets/images/google_recaptcha/fill_out_form_google_recaptcha.png" target="_blank">
                                        <img src="assets/images/google_recaptcha/fill_out_form_google_recaptcha.png" class="img-fluid rounded" alt="image">
                                    </a><br><br>

                                    <h5><?= $lang['lang_after_click_submit']; ?></h5><br>

                                    <a href="assets/images/google_recaptcha/submit_google_recaptcha.png" target="_blank">
                                        <img src="assets/images/google_recaptcha/submit_google_recaptcha.png" class="img-fluid rounded" alt="image">
                                    </a><br><br>

                                    <p>
                                        <ol>
                                            <strong>2.</strong> <?= $lang['lang_access_menu_settings_recaptcha']; ?>
                                        </ol>
                                    </p>

                                    <a href="assets/images/google_recaptcha/config_recaptcha.png" target="_blank">
                                        <img src="assets/images/google_recaptcha/config_recaptcha.png" class="img-fluid rounded" alt="image">
                                    </a><br>

                                    <div class="callout-block callout-success mt-3">
                                        <div class="icon-holder">
                                            <i class="fas fa-thumbs-up"></i>
                                        </div>
                                        <!--//icon-holder-->
                                        <div class="content">
                                            <h4 class="callout-title"><?= $lang['lang_congratulations_text']; ?></h4><br>
                                            <p>
                                                <?= $lang['lang_finally_config_recaptcha']; ?>
                                            </p>
                                        </div>
                                        <!--//content-->
                                    </div>
                                </div>
                            </section>

                            <section id="filesBoostpanel" class="doc-section">
                                <h2 class="section-title"><?= $lang['lang_files_boostpanel']; ?></h2>
                                <div class="section-block">
                                    <p>
                                        <?= $lang['lang_files']; ?>
                                    </p>
                                </div>
                            </section>
                        </div>
                        <!--//content-inner-->
                    </div>
                    <!--//doc-content-->
                    <div class="doc-sidebar col-md-3 col-12 order-0 d-none d-md-flex">
                        <div id="doc-nav" class="doc-nav">

                            <nav id="doc-menu" class="nav doc-menu flex-column sticky">
                                <a class="nav-link scrollto" href="#introduction"><?= $lang['lang_introduction']; ?></a>
                                <a class="nav-link scrollto" href="#requirements"><?= $lang['lang_requirements']; ?></a>
                                <a class="nav-link scrollto" href="#installation"><?= $lang['lang_installation']; ?></a>
                                <nav class="doc-sub-menu nav flex-column">
                                    <a class="nav-link scrollto" href="#start_installation"><?= $lang['lang_start_installation']; ?></a>
                                    <a class="nav-link scrollto" href="#configure_cronjob"><?= $lang['lang_configure_cronjob']; ?></a>
                                    <a class="nav-link scrollto" href="#api_providers"><?= $lang['lang_api_providers']; ?></a>
                                    <a class="nav-link scrollto" href="#google_recaptcha">Google reCAPTCHA V2</a>
                                </nav>
                                <!--//nav-->
                                <a class="nav-link scrollto" href="#filesBoostpanel"><?= $lang['lang_files_boostpanel']; ?></a>
                                <!--//nav-->
                            </nav>
                            <!--//doc-menu-->
                        </div>
                    </div>
                    <!--//doc-sidebar-->
                </div>
                <!--//doc-body-->
            </div>
            <!--//container-->
        </div>
        <!--//doc-wrapper-->
    </div>
    <!--//page-wrapper-->

    <footer id="footer" class="footer text-center">
        <div class="container">
            <!--/* This template is released under the Creative Commons Attribution 3.0 License. Please keep the attribution link below when using for your own project. Thank you for your support. :) If you'd like to use the template without the attribution, you can buy the commercial license via our website: themes.3rdwavemedia.com */-->
            <small class="copyright">Template <i class="fas fa-heart"></i> by <a href="https://themes.3rdwavemedia.com/" target="_blank">Xiaoying Riley</a></small>
        </div>
        <!--//container-->
    </footer>
    <!--//footer-->


    <!-- Main Javascript -->
    <script src="assets/plugins/jquery-3.3.1.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/plugins/prism/prism.js"></script>
    <script src="assets/plugins/jquery-scrollTo/jquery.scrollTo.min.js"></script>
    <script src="assets/plugins/stickyfill/dist/stickyfill.min.js"></script>
    <script src="assets/js/main.js"></script>

</body>

</html>