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
                <p class="mb-0 f-26 gilroy-Semibold text-uppercase text-center">Withdraw Money</p>
                <p class="mb-0 text-center f-13 gilroy-medium text-gray mt-4 dark-A0">Step: 3 of 3</p>
                <p class="mb-0 text-center f-18 gilroy-medium text-dark dark-5B mt-2">Withdrawal Completed</p>
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

                <p class="mb-0 text-center f-14 gilroy-medium text-gray-100 dark-CDO mt-6 r-mt-8 leading-25">
                    Your withdrawal request has been submitted and is awaiting approval.
                </p>

                <div class="amount-withdraw border-light-mode r-mt-24">
                    <p class="mb-0 text-center gilroy-medium text-primary dark-5B f-16">Amount Withdrawn</p>
                    <p class="mb-0 text-center gilroy-Semibold text-dark dark-A0 f-32 mt-8"><?= formatCurrency($data['user']['currency'], $data['withdraw-amount']) ?></p>
                </div>

                <!-- Icon -->
                <div class="d-flex justify-content-center">
                    <div class="border-hr d-flex justify-content-center"></div>
                </div>

                <!-- Getting Amount -->
                <div class="getting-amount border-light-mode">
                    <input type="hidden" class="abbreviation" name="abbreviation" value="<?=e($data['withdraw-method']['abbreviation'])?>">
                    <input type="hidden" class="amount" value="<?=e($data['withdraw-amount'])?>">
                    <p class="mb-0 text-center gilroy-medium text-dark dark-5B f-16">You will get</p>
                    <p class="f-26 mb-0 mt-8 text-center">
                        <span class="text-dark gilroy-medium"><span class="text-primary gilroy-Semibold converted" id="converted">0.00</span> <?=e($data['withdraw-method']['abbreviation'])?></span>
                    </p>
                </div>

                <div class="d-flex justify-content-center mt-28 r-mt-20">
                    <a href="<?=$this->siteUrl()?>/user/payouts" class="print-btn d-flex justify-content-center align-items-center gap-10">
                        <span class="ml-10">Withdrawals</span>
                    </a>

                    <a href="<?=$this->siteUrl()?>/user/payout" class="repeat-btn d-flex justify-content-center align-items-center ml-20">
                        <span class="gilroy-medium">Withdraw Again</span>
                    </a>
                </div>
            </div>
            <!-- main-containt -->
        </div>
    </div>
</div>