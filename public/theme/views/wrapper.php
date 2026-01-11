<?php
defined('FIR') OR exit();
/**
 * The main template file
 * This file puts together the three main sections of the software, header, content and footer
 */
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
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

        <!-- Dark mode -->
        <script>
            const storedTheme = localStorage.getItem("theme");

            const getPreferredTheme = () => {
                if (storedTheme) {
                    return storedTheme;
                }
                return window.matchMedia("(prefers-color-scheme: light)").matches ? "light" : "light";
            };

            const setTheme = function (theme) {
                if (theme === "auto" && window.matchMedia("(prefers-color-scheme: dark)").matches) {
                    document.documentElement.setAttribute("data-bs-theme", "dark");
                } else {
                    document.documentElement.setAttribute("data-bs-theme", theme);
                }
            };

            setTheme(getPreferredTheme());

            window.addEventListener("DOMContentLoaded", () => {
                var el = document.querySelector(".theme-icon-active");
                if (el != "undefined" && el != null) {
                    const showActiveTheme = (theme) => {
                        const activeThemeIcon = document.querySelector(".theme-icon-active use");
                        const btnToActive = document.querySelector(`[data-bs-theme-value="${theme}"]`);
                        const svgOfActiveBtn = btnToActive.querySelector(".mode-switch use").getAttribute("href");

                        document.querySelectorAll("[data-bs-theme-value]").forEach((element) => {
                            element.classList.remove("active");
                        });

                        btnToActive.classList.add("active");
                        activeThemeIcon.setAttribute("href", svgOfActiveBtn);
                    };

                    window.matchMedia("(prefers-color-scheme: dark)").addEventListener("change", () => {
                        if (storedTheme !== "light" || storedTheme !== "dark") {
                            setTheme(getPreferredTheme());
                        }
                    });

                    showActiveTheme(getPreferredTheme());

                    document.querySelectorAll("[data-bs-theme-value]").forEach((toggle) => {
                        toggle.addEventListener("click", () => {
                            const theme = toggle.getAttribute("data-bs-theme-value");
                            localStorage.setItem("theme", theme);
                            setTheme(theme);
                            showActiveTheme(theme);
                        });
                    });
                }
            });
        </script>

        <!-- Google Font -->
        <link rel="preconnect" href="https://fonts.googleapis.com/" />
        <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&amp;family=Inter:wght@400;500;600&amp;display=swap" rel="stylesheet" />

        <!-- Plugins CSS -->
        <link rel="stylesheet" type="text/css" href="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/home/vendor/font-awesome/css/all.min.css" />
        <link rel="stylesheet" type="text/css" href="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/home/vendor/bootstrap-icons/bootstrap-icons.css" />
        <link rel="stylesheet" type="text/css" href="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/home/vendor/swiper/swiper-bundle.min.css" />
        <link rel="stylesheet" type="text/css" href="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/home/vendor/glightbox/css/glightbox.css">

        <!-- Theme CSS -->
        <link rel="stylesheet" type="text/css" href="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/home/css/style.css" />

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
                border-radius: 4px !important;
                font-size: 12px !important;
                line-height: 2rem !important;
                display: inline-block;
                cursor: pointer;
                zoom: 1;
                margin-bottom: 4px;
                color: #ffffff;
            }

            .goog-te-gadget .goog-te-combo {
                width: 145px;
                margin-bottom: 20px !important;
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

    <body>
        <div id="preloader" class="preloader position-fixed top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center">
            <div class="preloader-content text-center">
                <!-- Central Image -->
                <img class="light-mode-item navbar-brand-item preloader-image" src="<?=$this->siteUrl().'/'.PUBLIC_PATH.'/'.UPLOADS_PATH?>/logo/fav-dark.png" alt="logo-light">
                <img class="dark-mode-item navbar-brand-item preloader-image" src="<?=$this->siteUrl().'/'.PUBLIC_PATH.'/'.UPLOADS_PATH?>/logo/<?=e($this->siteSettings('favicon'))?>" alt="logo-dark">
                <!-- Rotating Circle -->
                <div class="circle"></div>
            </div>
        </div>

        <?=$data['header_view']?>

        <?=$data['content_view']?>

        <?=$data['footer_view']?>

        <div id="google_translate_element"></div>
        <script type="text/javascript">
            function googleTranslateElementInit() {
                new google.translate.TranslateElement({
                    pageLanguage: 'en'
                }, 'google_translate_element');
            }
        </script>
        <script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

        <!-- Bootstrap JS -->
        <script src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/home/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

        <!--Vendors-->
        <script src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/home/vendor/ityped/index.js"></script>
        <script src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/home/vendor/swiper/swiper-bundle.min.js"></script>

        <script src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/home/vendor/jarallax/jarallax.min.js"></script>
        <script src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/home/vendor/jarallax/jarallax-video.min.js"></script>
        <script src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/home/vendor/glightbox/js/glightbox.js"></script>
        <script src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/home/vendor/isotope/isotope.pkgd.min.js"></script>
        <script src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/home/vendor/purecounterjs/dist/purecounter_vanilla.js"></script>
        <script src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/home/vendor/sticky-js/sticky.min.js"></script>

        <!-- Theme Functions -->
        <script src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/home/js/functions.js"></script>
        <script src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/home/js/preloader.js"></script>

        <script src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/dist/libraries/jquery-3.6.1/jquery-3.6.1.min.js"></script>
        <link rel="stylesheet" href="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/css/iziToast.min.css">
        <script src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/js/iziToast.min.js"></script>

        <?=$data['scripts_view']?>
        
        <?=$this->liveChat('script')?>
        <?=$this->whatsApp('script')?>
    </body>
</html>

