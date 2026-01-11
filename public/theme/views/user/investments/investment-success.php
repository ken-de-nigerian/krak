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
                return '€';
            case '£':
                return '£';
            case '$':
                return '$';
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
                <p class="mb-0 f-26 gilroy-Semibold text-uppercase text-center">NEW INVESTMENT</p>
                <p class="mb-0 text-center f-13 gilroy-medium text-gray mt-4 dark-A0">Step: 3 of 3</p>
                <p class="mb-0 text-center f-18 gilroy-medium text-dark dark-5B mt-2">Investment Completed</p>
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
                    The investment has been completed successfully. You can review the transaction details for more information.
                </p>

                <!-- Total -->
                <div class="exchange-total-parent border-light-mode d-flex justify-content-center align-items-center mt-24">
                    <div class="exchange-total">
                        <p class="text-gray-100 gilroy-medium f-16 text-center mb-0">Amount</p>
                        <p class="f-26 mb-0 mt-8">
                            <span class="text-dark gilroy-medium"><span class="text-primary gilroy-Semibold"><?= formatCurrency($data['user']['currency']) ?></span> <?=e($data['invested-amount'])?></span>
                        </p>
                    </div>
                </div>

                <!-- Icon -->
                <div class="d-flex justify-content-center">
                    <div class="border-hr d-flex justify-content-center"></div>
                </div>

                <!-- Getting Amount -->
                <div class="getting-amount border-light-mode">
                    <p class="mb-0 text-center gilroy-medium text-dark dark-5B f-16">Getting Amount</p>
                    <p class="f-26 mb-0 mt-8 text-center">
                        <span class="text-dark gilroy-medium"><span class="text-primary gilroy-Semibold"><?= formatCurrency($data['user']['currency']) ?></span> <?=e($data['interest'])?></span>
                    </p>
                </div>

                <p class="mb-0 inv-complete f-14 leading-17 gilroy-medium text-gray-100 text-center mt-24"><em>* Invested on <?=e($data['plan-details']['name'])?> via Wallet Balance</em></p>

                <!-- Button -->
                <div class="d-flex justify-content-center mt-28 r-mt-20">
                    <div class="d-flex justify-content-center mt-32 r-mt-20">
                        <a href="<?=$this->siteUrl()?>/user/investments/investment-details/<?=e($data['investment-details']['investId'])?>" class="print-btn d-flex justify-content-center align-items-center inv-complete gap-10">
                            <span class="ml-10">Investment Details</span>                        
                        </a>
                        <a href="<?=$this->siteUrl()?>/user/plans" class="repeat-btn d-flex justify-content-center align-items-center ml-20">
                            <span class="gilroy-medium">Invest Again</span>                        
                        </a>
                    </div>
                </div>
            </div>
            <!-- main-containt -->
        </div>
    </div>
</div>