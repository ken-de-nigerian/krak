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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- App favicon -->
    <link rel="shortcut icon" href="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/admin/images/favicon.ico">
    <title>404 - Page Not Found</title>

    <!-- Layout config Js -->
    <script src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/admin/js/layout.js"></script>
    <!-- Bootstrap Css -->
    <link href="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/admin/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/admin/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/admin/css/app.min.css" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/admin/css/custom.min.css" rel="stylesheet" type="text/css" />

</head>

<body>
    <!-- auth-page wrapper -->
    <div class="auth-page-wrapper py-5 d-flex justify-content-center align-items-center min-vh-100">
        <!-- auth-page content -->
        <div class="auth-page-content overflow-hidden p-0">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-7 col-lg-8">
                        <div class="text-center">
                            <img src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/admin/images/error400-cover.png" alt="error img" class="img-fluid">
                            <div class="mt-3">
                                <h3 class="text-uppercase">Sorry, Page not Found 😭</h3>
                                <p class="text-muted mb-4">The page you are looking for is not available!</p>
                                <a href="<?=$this->siteUrl()?>" class="btn btn-success"><i class="mdi mdi-home me-1"></i>Back to home</a>
                            </div>
                        </div>
                    </div><!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end auth-page content -->
    </div>
    <!-- end auth-page-wrapper -->
</body>
</html>