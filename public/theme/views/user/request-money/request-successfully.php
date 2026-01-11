<?php
defined('FIR') OR exit();
/**
 * The template for displaying Example Create page
 */
?>
<?php 
    function formatCurrency($currency): string
    {
        switch ($currency) {
            case '€':
                return 'EUR ';
            case '£':
                return 'GBP ';
            case '$':
                return 'USD ';
            default:
                return $currency . ' ';
        }
    }
?>
<div class="position-relative">
    <div class="containt-parent">
        <div class="main-containt">
            <!-- main-containt -->
            <div class="bg-white pxy-62 shadow">
                <p class="mb-0 f-26 gilroy-Semibold text-uppercase text-center">Request Money</p>
                <p class="mb-0 text-center f-13 gilroy-medium text-gray mt-4 dark-A0">Step: 3 of 3</p>
                <p class="mb-0 text-center f-18 gilroy-medium text-dark dark-5B mt-2">Request Sent</p>

                <div class="text-center">
                    <svg class="mt-18 nscaleX-1" width="314" height="6" viewBox="0 0 314 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="100" height="6" rx="3" fill="#635BFE" />
                        <rect x="107" width="100" height="6" rx="3" fill="#635BFE" />
                        <rect x="214" width="100" height="6" rx="3" fill="#635BFE" />
                    </svg>
                </div>

                <div class="mt-36 d-flex justify-content-center position-relative h-44">
                    <lottie-player class="position-absolute success-anim" src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/user/templates/animation/confirm.json" background="transparent" speed="1" autoplay></lottie-player>
                </div>

                <p class="mb-0 gilroy-medium f-20 success-text text-dark mt-20 text-center dark-5B r-mt-16">Success!</p>
                <p class="mb-0 text-center f-14 gilroy-medium text-gray dark-CDO mt-6 r-mt-8 leading-25">Money request sent successfully.</p>

                <div class="print-mail mt-4">
                    <div class="d-flex gap-18 justify-content-center">
                        <div class="d-flex align-items-center justify-content-center user-mail mt-20">
                            <img src="<?=$this->siteUrl().'/'.PUBLIC_PATH.'/'.UPLOADS_PATH?>/users/<?=e($data['sender']['imagelocation'])?>" class="img-fluid" />
                        </div>
                        <div class="d-flex">
                            <div class="mt-26">
                                <p class="mb-0 text-dark gilroy-medium f-16 theme-font"><?=$data['request-details']['sender_email']?></p>
                                <p class="mb-0 text-gray-100 dark-B87 gilroy-regular f-12 mt-2 leading-20 theme-amount">Requested Amount</p>
                                <p class="mb-0 text-primary dark-B87 gilroy-medium mt-2p f-16 theme-usd"><?= formatCurrency($data['user']['currency']) ?> <?=$data['request-details']['amount']?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-center mt-32 r-mt-20">
                    <a href="<?=$this->siteUrl()?>/user/request" class="bg-white repeat-btn d-flex justify-content-center align-items-center ml-20">
                        <span class="gilroy-medium">Request Again</span>
                    </a>
                </div>
            </div>
            <!-- main-containt -->
        </div>
    </div>
</div>