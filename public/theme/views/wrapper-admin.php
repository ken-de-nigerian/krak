<?php
defined('FIR') OR exit();
/**
 * The main template file
 * This file puts together the three main section of the software, header, content and footer
 */
?>
<!DOCTYPE html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default" data-bs-theme="dark">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="description" content="<?=e($this->siteSettings('description'))?>">
        <meta name="keywords" content="<?=e($this->siteSettings('keywords'))?>">
        <meta property="og:title" content="<?=e($this->siteSettings('sitename'))?> : <?=e($this->siteSettings('description'))?>"/>
        <meta property="og:type" content="website"/>
        <meta property="og:url" content="<?=$this->siteUrl()?>"/>
        <meta property="og:image" content="<?=$this->siteUrl().'/'.PUBLIC_PATH.'/'.UPLOADS_PATH?>/seo/<?=e($this->siteSettings('seo_image'))?>"/>
        <meta property="og:site_name" content="<?=e($this->siteSettings('sitename'))?>"/>
        <meta property="og:description" content="<?=e($this->siteSettings('description'))?>"/>

        <link rel="shortcut icon" href="<?=$this->siteUrl().'/'.PUBLIC_PATH.'/'.UPLOADS_PATH?>/logo/<?=e($this->siteSettings('favicon'))?>" />

        <title><?=e($this->siteSettings('sitename'))?> - <?=e($this->siteSettings('title'))?></title>

        <!-- Css -->
        <link href="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/admin/libs/swiper/swiper-bundle.min.css" rel="stylesheet" />
        <link rel="stylesheet" type="text/css" href="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/dist/plugins/intl-tel-input-17.0.19/css/intlTelInput.min.css">

        <!-- Layout config Js -->
        <script src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/admin/js/layout.js"></script>
        <!-- Bootstrap Css -->
        <link href="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/admin/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/admin/css/icons.min.css" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/admin/css/app.min.css" rel="stylesheet" type="text/css" />
        <!-- Custom Css-->
        <link href="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/admin/css/custom.min.css" rel="stylesheet" type="text/css" />
        <!-- Select2 Css -->
        <link rel="stylesheet" href="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/css/select2.min.css">
        <!-- Quill Css -->
        <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        
        <!-- session messages -->
        <?=$this->message()?>
    </head>

    <body>

        <?php if ($data['url'] !== 'login'): ?>
            <?=$data['sidenav_view']?>
        <?php endif; ?>

        <?php if ($data['url'] == 'login'): ?>
            <?=$data['content_view']?>
        <?php else: ?>
            <!-- Begin page -->
            <div id="layout-wrapper">
                <?=$data['navigation_view']?>

                <div class="main-content">
                    <?=$data['content_view']?>
                    <?=$data['footer_view']?>
                </div>
            </div>
            <!-- END layout-wrapper -->
        <?php endif; ?>

        <?php if ($data['url'] == 'login'): ?>

            <!-- particles js -->
            <script src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/admin/libs/particles.js/particles.js"></script>
            <!-- particles app js -->
            <script src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/admin/js/pages/particles.app.js"></script>
            <!-- password-addon init -->
            <script src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/admin/js/pages/password-addon.init.js"></script>
        <?php else: ?>

            <!-- JAVASCRIPT -->
            <script src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/admin/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
            <script src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/admin/libs/simplebar/simplebar.min.js"></script>
            <script src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/admin/libs/node-waves/waves.min.js"></script>
            <script src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/admin/libs/feather-icons/feather.min.js"></script>
            <script src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/admin/js/pages/plugins/lord-icon-2.1.0.js"></script>
            <script src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/admin/js/pages/card.init.js"></script>

            <!-- apexcharts -->
            <script src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/admin/libs/apexcharts/apexcharts.min.js"></script>
            <!-- Swiper Js -->
            <script src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/admin/libs/swiper/swiper-bundle.min.js"></script>
            <!-- CRM js -->
            <script src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/admin/js/pages/dashboard-crypto.init.js"></script>

            <!-- App js -->
            <script src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/admin/js/app.js"></script>

            <!-- intlTelInput init -->
            <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.12/js/intlTelInput.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.12/js/utils.js"></script>
            
            <!-- quill js -->
            <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

            <!-- select2 init -->
            <script src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/js/select2.min.js"></script>
        <?php endif; ?>

        <!-- iziToast js -->
        <link rel="stylesheet" href="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/css/iziToast.min.css">
        <script src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/js/iziToast.min.js"></script>

        <!-- custom scripts -->
        <?=$data['scripts_view']?>
    </body>
</html>
