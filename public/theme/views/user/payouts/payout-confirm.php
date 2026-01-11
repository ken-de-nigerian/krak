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
            <div class="bg-white pxy-62 pt-62 custom-input-height shadow convert">
                <p class="mb-0 f-26 gilroy-Semibold text-uppercase text-center">Withdraw Money</p>
                <p class="mb-0 text-center f-13 gilroy-medium text-gray mt-4 dark-A0">Step: 2 of 3</p>
                <p class="mb-0 text-center f-18 gilroy-medium text-dark dark-5B mt-2">Confirm Your Withdrawal</p>

                <div class="text-center">
                    <svg class="mt-18 nscaleX-1" width="314" height="6" viewBox="0 0 314 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="100" height="6" rx="3" fill="#635BFE" />
                        <rect x="107" width="100" height="6" rx="3" fill="#635BFE" />
                        <rect class="rect-B87" x="214" width="100" height="6" rx="3" fill="#DDD3FD" />
                    </svg>
                </div>

                <p class="mb-0 text-center f-14 gilroy-medium text-gray dark-p mt-20">Check your withdrawal information before confirmation.</p>

                <form id="payout-form">
                    <input type="hidden" name="balance" id="balance">
                    <input type="hidden" class="abbreviation" name="abbreviation" value="<?=e($data['withdraw-method']['abbreviation'])?>">
                    <input type="hidden" class="amount" value="<?=e($data['withdraw-amount'])?>">

                    <!-- Crypto Exchange Details -->
                    <div class="mt-32">
                        <div class="exchange-send-get d-flex justify-content-between">
                            <!-- Crypto Send -->
                            <div class="send-left-box w-50">
                                <p class="mb-0 f-14 leading-17 gilroy-medium text-gray-100">You withdrew</p>

                                <p class="mb-0 f-24 l gilroy-Semibold mt-1">
                                    <span class="text-dark"><?= e(number_format($data['withdraw-amount'], 2)) ?></span>
                                    <span class="text-primary"> <?= formatCurrency($data['user']['currency']) ?></span>
                                </p>
                            </div>

                            <!-- Crypto Get -->
                            <div class="get-right-box w-50">
                                <p class="mb-0 f-14 leading-17 gilroy-medium text-light">equivalently</p>
                                <p class="mb-0 f-24 l gilroy-Semibold mt-1">
                                    <span class="text-white converted" id="converted">0.00</span>
                                    <span class="text-warning"> <?=e($data['withdraw-method']['abbreviation'])?></span>
                                </p>

                                <div class="right-box-icon">
                                    <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="12.5" cy="12.5" r="12.5" fill="white" />
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M10.5312 17.4705C10.2709 17.2102 10.2709 16.788 10.5312 16.5277L14.0598 12.9991L10.5312 9.47051C10.2708 9.21016 10.2708 8.78805 10.5312 8.5277C10.7915 8.26735 11.2137 8.26735 11.474 8.5277L15.474 12.5277C15.7344 12.788 15.7344 13.2102 15.474 13.4705L11.474 17.4705C11.2137 17.7309 10.7915 17.7309 10.5312 17.4705Z" fill="#6A6B87"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="label-top mt-20">
                            <label class="gilroy-medium text-gray-100 mb-2 f-15" for="crypto_address">Receiving Address</label>
                            <input type="text" value="" class="form-control input-form-control apply-bg crypto_address focus-bgcolor" name="wallet" id="crypto_address" placeholder="Please provide your <?=e($data['withdraw-method']['abbreviation'])?> address">
                            <span class="error"></span>
                        </div>
                        <p class="mb-0 text-gray-100 dark-B87 gilroy-regular r-f-12 f-12 mt-2"><em>* Providing wrong address may result in permanent loss of your coin</em></p>
                    </div>

                    <!-- button -->
                    <div class="d-grid">
                        <?=$this->token()?>
                        <button type="submit" class="btn btn-lg btn-primary mt-4 submit-button" id="payoutBtn" data-withdraw="<?=e($data['withdrawal-details']['withdrawId'])?>" data-method="<?=e($data['withdraw-method']['withdraw_code'])?>">

                            <span>Confirm &amp; Withdraw</span>

                            <span id="rightAngleSvgIcon">
                                <svg class="position-relative ms-1 rtl-wrap-one nscaleX-1" width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M4.11648 12.216C3.81274 11.9123 3.81274 11.4198 4.11648 11.1161L8.23317 6.99937L4.11648 2.88268C3.81274 2.57894 3.81274 2.08647 4.11648 1.78273C4.42022 1.47899 4.91268 1.47899 5.21642 1.78273L9.88309 6.4494C10.1868 6.75314 10.1868 7.2456 9.88309 7.54934L5.21642 12.216C4.91268 12.5198 4.42022 12.5198 4.11648 12.216Z" fill="currentColor"/>
                                </svg>
                            </span>
                        </button>
                    </div>

                    <!-- Back Button -->
                    <div class="d-flex justify-content-center align-items-center mt-4 back-direction">
                        <a href="<?=$this->siteUrl()?>/user/payout" class="text-gray gilroy-medium d-inline-flex align-items-center position-relative back-btn">
                            <svg class="position-relative nscaleX-1" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M8.47075 10.4709C8.7311 10.2105 8.7311 9.78842 8.47075 9.52807L4.94216 5.99947L8.47075 2.47087C8.7311 2.21053 8.7311 1.78842 8.47075 1.52807C8.2104 1.26772 7.78829 1.26772 7.52794 1.52807L3.52795 5.52807C3.2676 5.78842 3.2676 6.21053 3.52795 6.47088L7.52794 10.4709C7.78829 10.7312 8.2104 10.7312 8.47075 10.4709Z" fill="currentColor"/>
                            </svg>
                            <span class="ms-1 back-btn ns cryptoConfirmBackBtnText">Back</span>
                        </a>
                    </div>
                </form>
            </div>
            <!-- main-containt -->
        </div>
    </div>
</div>