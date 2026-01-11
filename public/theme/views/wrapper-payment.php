<?php
defined('FIR') OR exit();
/**
 * The main template file
 * This file puts together the three main sections of the software, header, content and footer
 */
?>
<!DOCTYPE html>
<html lang="en" class="scrol-pt">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="<?=e($this->siteSettings('description'))?>">
        <meta name="keywords" content="<?=e($this->siteSettings('keywords'))?>">
        <meta property="og:title" content="<?=e($this->siteSettings('sitename'))?> : <?=e($this->siteSettings('description'))?>"/>
        <meta property="og:type" content="website"/>
        <meta property="og:url" content="<?=$this->siteUrl()?>"/>
        <meta property="og:image" content="<?=$this->siteUrl().'/'.PUBLIC_PATH.'/'.UPLOADS_PATH?>/seo/<?=e($this->siteSettings('seo_image'))?>"/>
        <meta property="og:site_name" content="<?=e($this->siteSettings('sitename'))?>"/>
        <meta property="og:description" content="<?=e($this->siteSettings('description'))?>"/>

        <title><?=e($this->siteSettings('sitename'))?> - <?=e($this->siteSettings('title'))?></title>

        <!-- favicon -->
        <link rel="shortcut icon" href="<?=$this->siteUrl().'/'.PUBLIC_PATH.'/'.UPLOADS_PATH?>/logo/<?=e($this->siteSettings('favicon'))?>">

        <script src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/frontend/templates/js/flashesh-dark.min.js"></script>
        <link rel="stylesheet" href="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/dist/libraries/bootstrap-5.0.2/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/frontend/templates/css/style.min.css">

        <!-- google translate -->
        <style>
            * {
                box-sizing: border-box;
            }

            #google_translate_element {
                z-index: 9999999;
                position: fixed;
                bottom: -20px;
                left: 15px;
            }

            .goog-te-gadget {
                font-family: Roboto, "Open Sans", sans-serif !important;
                text-transform: uppercase;
            }
            
            .goog-logo-link {
                display: none !important;
            }

            .goog-te-gadget {
                color: transparent !important;
            }

            .goog-te-gadget .goog-te-combo {
                color: #b5b5b5 !important;
            }

            .goog-te-banner-frame.skiptranslate {
                display: none !important;
            }

            .goog-te-combo, .VIpgJd-ZVi9od-ORHb *, .VIpgJd-ZVi9od-SmfZ *, .VIpgJd-ZVi9od-xl07Ob *, .VIpgJd-ZVi9od-vH1Gmf *, .VIpgJd-ZVi9od-l9xktf * {
                background-color: #000000 !important;
                border: 1px solid rgba(255, 255, 255, 0.5) !important;
                padding: 13px !important;
                border-radius: 7px !important;
                font-size: 12px !important;
                line-height: 2rem !important;
                display: inline-block;
                cursor: pointer;
                zoom: 1;
                margin-bottom: 4px;
                color: #ffffff;
            }

            .goog-te-gadget .goog-te-combo {
                margin: 4px -4px;
                width: 145px;
            }
            
            .VIpgJd-ZVi9od-l4eHX-hSRGPd, .VIpgJd-ZVi9od-l4eHX-hSRGPd:link, .VIpgJd-ZVi9od-l4eHX-hSRGPd:visited, .VIpgJd-ZVi9od-l4eHX-hSRGPd:hover, .VIpgJd-ZVi9od-l4eHX-hSRGPd:active {
                font-size: 12px;
                font-weight: bold;
                color: #444;
                text-decoration: none;
                display:none;
            }

            .goog-te-menu2 {
                max-width: 100%;
            }

            .goog-te-menu-value {
                color: #fff !important;
            }
            .goog-te-menu-value:before {
                font-family: "Material Icons";
                content: "\E927";
                margin-right: 16px;
                font-size: 2rem;
                vertical-align: -10px;
            }

            .goog-te-menu-value span:nth-child(5) {
                display: none;
            }

            .goog-te-menu-value span:nth-child(3) {
                border: none !important;
                font-family: "Material Icons";
            }
            .goog-te-menu-value span:nth-child(3):after {
                font-family: "Material Icons";
                content: "\E5C5";
                font-size: 1.5rem;
                vertical-align: -6px;
            }

            .goog-te-gadget-icon {
                background-position: 0px 0px;
                height: 32px !important;
                width: 32px !important;
                margin-right: 8px !important;
                display: none;
            }

            .goog-te-banner-frame.skiptranslate {
                display: none !important;
            }

            body {
                top: 0px !important;
            }

            @media (max-width: 667px) {
                #google_translate_element {
                }
                #google_translate_element goog-te-gadget {
                }
                #google_translate_element .skiptranslate {
                }
                #google_translate_element .goog-te-gadget-simple {
                    text-align: center;
                }
            }

            .goog-te-gadget-simple .VIpgJd-ZVi9od-xl07Ob-lTBxed {
                color: #fff !important;
            }
            
            .goog-te-gadget img{
                display:none !important;
            }
            body > .skiptranslate {
                display: none;
            }
            body {
                top: 0px !important;
            }
        </style>

        <?=$this->message()?>
    </head>
    <body class="bg-body-muted">

        <?=$data['content_view']?>

        <script src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/dist/libraries/jquery-3.6.1/jquery-3.6.1.min.js"></script>
        <script src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/dist/libraries/bootstrap-5.0.2/js/bootstrap.min.js"></script>
        <script src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/frontend/templates/js/main.min.js"></script>

        <link rel="stylesheet" href="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/css/iziToast.min.css">
        <script src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/js/iziToast.min.js"></script>

        <script type="text/javascript">
            function googleTranslateElementInit() {
                new google.translate.TranslateElement({
                    pageLanguage: 'en'
                }, 'google_translate_element');
            }
        </script>
        <script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

        <?=$data['scripts_view']?>

        <?=$this->liveChat('script')?>
        <?=$this->whatsApp('script')?>
    </body>
</html>
