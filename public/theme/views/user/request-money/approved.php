<?php
defined('FIR') OR exit();
/**
 * The template for displaying Example Create page
 */
?>

<?php 
    function formatCurrency($currency, $amount): string
    {
        switch ($currency) {
            case '€':
                return '€ ' . number_format($amount, 2);
            case '£':
                return '£ ' . number_format($amount, 2);
            case '$':
                return '$ ' . number_format($amount, 2);
            default:
                return $currency . ' ' . number_format($amount, 2);
        }
    }
?>
<div class="position-relative">
    <div class="containt-parent">
        <div class="main-containt">
            <!-- main-containt -->
            <div class="bg-white pxy-62 shadow convert">
                <p class="mb-0 f-26 gilroy-Semibold text-uppercase text-center">Approved</p>

                <div class="mt-36 d-flex justify-content-center position-relative h-44">
                    <lottie-player class="position-absolute success-anim" src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/user/templates/animation/confirm.json" background="transparent" speed="1" autoplay></lottie-player>
                </div>

                <p class="mb-0 text-center f-14 gilroy-medium text-gray dark-p mt-40 r-mt-8">
                    Your decision to approve the payment request has been recorded, and the transaction will proceed accordingly.
                </p>

                <div class="print-mail mt-4">
                    <div class="d-flex gap-18 justify-content-center">
                        <div class="d-flex align-items-center justify-content-center user-mail mt-20">
                            <img src="<?=$this->siteUrl().'/'.PUBLIC_PATH.'/'.UPLOADS_PATH?>/users/<?=e($data['receiver-details']['imagelocation'])?>" class="img-fluid" />
                        </div>

                        <div class="d-flex">
                            <div class="mt-26">
                                <p class="mb-0 text-dark gilroy-medium f-16 theme-font"><?=$data['receiver-details']['email']?></p>
                                <p class="mb-0 text-gray-100 dark-B87 gilroy-regular f-12 mt-2 leading-20 theme-amount">Requested Amount</p>
                                <p class="mb-0 text-primary dark-B87 gilroy-medium mt-2p f-16 theme-usd"><?= formatCurrency($data['receiver-details']['currency'], $data['request-details']['amount']) ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-center mt-28 r-mt-20">
                    <a href="<?=$this->siteUrl()?>/user/request" class="repeat-btn d-flex justify-content-center align-items-center ml-20">
                        <span class="gilroy-medium">Request Money</span>
                    </a>
                </div>
            </div>
            <!-- main-containt -->
        </div>
    </div>
</div>