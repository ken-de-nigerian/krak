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
            <div class="bg-white pxy-62 shadow" id="requestMoneyConfirm">
                <p class="mb-0 f-26 gilroy-Semibold text-uppercase text-center">Request Money</p>
                <p class="mb-0 text-center f-13 gilroy-medium text-gray mt-4 dark-A0">Step: 2 of 3</p>
                <p class="mb-0 text-center f-18 gilroy-medium text-dark dark-5B mt-2">Confirm Your Request</p>

                <div class="text-center">
                    <svg class="mt-18 nscaleX-1" width="314" height="6" viewBox="0 0 314 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="100" height="6" rx="3" fill="#635BFE" />
                        <rect x="107" width="100" height="6" rx="3" fill="#635BFE" />
                        <rect class="rect-B87" x="214" width="100" height="6" rx="3" fill="#DDD3FD" />
                    </svg>
                </div>

                <p class="mb-0 text-center f-14 gilroy-medium text-gray dark-p mt-20">Please ensure that the recipient is a registered user with us. You can't request money from someone who isn't registered.</p>

                <div class="mt-32 param-ref text-center">
                    <p class="mb-0 gilroy-medium text-primary dark-A0 f-16 sm-font-14 recipent-top-margin">You are requesting money from</p>
                    <p class="mb-0 text-center f-20 gilroy-medium text-dark dark-5B mt-10"><?=$data['request-details']['sender_email']?></p>
                    <div class="mt-40 transaction-box">
                        <p class="mb-0 gilroy-medium leading-20 text-center text-primary dark-A0 mt-32">Amount</p>
                        <p class="mb-0 f-20 gilroy-medium text-center text-dark dark-5B mt-10"><?= formatCurrency($data['user']['currency']) ?> <?=$data['request-details']['amount']?></p>
                    </div>
                </div>

                <form id="request-money-confirm-form">
                    <div class="d-grid">
                        <?=$this->token()?>
                        <button type="submit" class="btn btn-lg btn-primary mt-4" id="requestMoneyConfirmBtn" data-request="<?=e($data['request-details']['requestId'])?>">
                            <span>Confirm &amp; Send</span>
                        </button>
                    </div>

                    <div class="d-flex justify-content-center align-items-center mt-4 back-direction">
                        <a href="<?=$this->siteUrl()?>/user/request" class="text-gray gilroy-medium d-inline-flex align-items-center position-relative back-btn">
                            <svg class="position-relative nscaleX-1" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M8.47075 10.4709C8.7311 10.2105 8.7311 9.78842 8.47075 9.52807L4.94216 5.99947L8.47075 2.47087C8.7311 2.21053 8.7311 1.78842 8.47075 1.52807C8.2104 1.26772 7.78829 1.26772 7.52794 1.52807L3.52795 5.52807C3.2676 5.78842 3.2676 6.21053 3.52795 6.47088L7.52794 10.4709C7.78829 10.7312 8.2104 10.7312 8.47075 10.4709Z" fill="currentColor"/>
                            </svg>
                            <span class="ms-1 back-btn">Back</span>
                        </a>
                    </div>
                </form>
            </div>
            <!-- main-containt -->
        </div>
    </div>
</div>