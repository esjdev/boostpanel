<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<!-- banner part start-->
<section class="banner_part">
    <ul class="circles">
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
    </ul>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 col-sm-12">
                <div class="banner_text">
                    <div class="banner_text_iner">
                        <h5 data-aos="fade-up" data-aos-duration="200"><?= lang("header_title_small"); ?></h5>
                        <h1 data-aos="fade-up" data-aos-duration="500"><?= lang("header_title"); ?></h1>
                        <p data-aos="fade-up" data-aos-duration="1000"><?= lang("header_subtitle"); ?></p>
                        <div class="d-block d-lg-none">
                            <?php if (!logged()) : ?>
                                <a href="<?= base_url('login'); ?>" class="btn_2"><?= lang("button_login"); ?></a>
                                <a href="<?= base_url('register'); ?>" class="btn_2"><?= lang("button_register"); ?></a>
                            <?php else : ?>
                                <a href="<?= base_url('your/dashboard'); ?>" class="btn_2"><?= lang("menu_access_panel"); ?></a>
                                <a href="<?= base_url('logoff'); ?>" class="btn_2"><?= lang("menu_logoff"); ?></a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- banner part start-->

<!-- member_counter counter start -->
<section class="member_counter peach-gradient-rgba">
    <div class="container">
        <div class="row" data-aos="zoom-out">
            <div class="col-lg-4 col-sm-6">
                <div class="single_member_counter">
                    <span class="counter"><?= $total_users; ?></span>
                    <h4><?= lang("total_users"); ?></h4>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6">
                <div class="single_member_counter">
                    <span class="counter"><?= $total_users_satisfied; ?></span>
                    <h4><?= lang("satisfied_clients"); ?></h4>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6">
                <div class="single_member_counter">
                    <span class="counter"><?= $total_orders; ?></span>
                    <h4><?= lang("total_orders"); ?></h4>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- member_counter counter end -->

<!-- learning part start-->
<section class="learning_part">
    <div class="container">
        <div class="row align-items-sm-center align-items-lg-stretch" data-aos="zoom-out">
            <div class="col-md-7 col-lg-7">
                <div class="learning_img">
                    <img src="<?= set_image('about_img.svg'); ?>" alt="about-boostpanel" class="floating-left-right ie">
                </div>
            </div>
            <div class="col-md-5 col-lg-5" data-aos="fade-left" data-aos-duration="500">
                <div class="learning_member_text">
                    <h5><?= lang("about_us"); ?></h5>
                    <h2><?= lang("best_social_media_marketing"); ?></h2>
                    <p><?= lang("about_boostpanel"); ?></p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- learning part end-->

<!-- feature_part start-->
<section class="feature_part">
    <div class="container">
        <div class="row" data-aos="zoom-out">
            <div class="col-sm-12">
                <div class="section_tittle text-center">
                    <p><?= lang("features"); ?></p>
                    <h2><?= lang("what_we_offer"); ?></h2>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="single_feature" data-aos="fade-right" data-aos-duration="200">
                    <div class="single_feature_part">
                        <span class="single_feature_icon"><i class="fa fa-cubes"></i></span>
                        <h4><?= lang("api_support"); ?></h4>
                        <p><?= lang("about_api_support"); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="single_feature">
                    <div class="single_feature_part" data-aos="fade-right" data-aos-duration="600">
                        <span class="single_feature_icon"><i class="fa fa-dollar"></i></span>
                        <h4><?= lang("secure_payments"); ?></h4>
                        <p><?= lang("about_secure_payments"); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="single_feature">
                    <div class="single_feature_part" data-aos="fade-right" data-aos-duration="1000">
                        <span class="single_feature_icon"><i class="fa fa-shopping-bag"></i></span>
                        <h4><?= lang("resellers"); ?></h4>
                        <p><?= lang("about_resellers"); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="single_feature" data-aos="fade-right" data-aos-duration="1400">
                    <div class="single_feature_part single_feature_part_2">
                        <span class="single_service_icon style_icon"><i class="fa fa-plus"></i></span>
                        <h4><?= lang("others"); ?></h4>
                        <p><?= lang("about_others"); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- upcoming_event part start-->
