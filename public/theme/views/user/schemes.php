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
            <div class="bg-white pxy-62" id="invest_add">
                <p class="mb-0 f-26 gilroy-Semibold text-uppercase text-center">New Investment</p>
                <p class="mb-0 text-center f-13 gilroy-medium text-gray mt-4 dark-A0">Step: 2 of 3</p>
                <p class="mb-0 text-center f-18 gilroy-medium text-dark dark-5B mt-2">Fill Information</p>

                <div class="text-center">
                    <svg class="mt-18 nscaleX-1" width="314" height="6" viewBox="0 0 314 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="100" height="6" rx="3" fill="#635BFE" />
                        <rect x="107" width="100" height="6" rx="3" fill="#635BFE" />
                        <rect class="rect-B87" x="214" width="100" height="6" rx="3" fill="#DDD3FD" />
                    </svg>
                </div>

                <p class="mb-0 text-center f-14 gilroy-medium text-gray dark-p mt-20">You can invest in any plan using our popular payment methods or wallet.</p>

                <form id="schemes-form">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="label-top mt-20">
                                <label class="gilroy-medium text-gray-100 mb-2 f-15" for="plan">Investment Plan</label>
                                <input type="text" class="form-control input-form-control apply-bg l-s2" id="plan" value="<?=e($data['plan-details']['name'])?>" readonly/>
                            </div>
                        </div>

                        <p class="mb-0 text-gray-100 dark-B87 gilroy-regular r-f-12 f-12 mt-2">
                            <em>
                                * Invest for 
                                    <?php foreach($data['times'] as $time){
                                            if($time['time'] === $data['plan-details']['times']){ ?>
                                        <?=e($time["name"])?>
                                    <?php
                                        break;
                                        }
                                    } ?> 
                                and get profit <?=e($data['plan-details']['interest'])?>%
                            </em>
                        </p>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="label-top mt-20">
                                <label class="gilroy-medium text-gray-100 mb-2 f-15" for="custom-amount">Amount <span class="currency">(<?= formatCurrency($data['user']['currency']) ?>)</span></label>
                                <?php if ($data['plan-details']['fixed_amount'] == 0): ?>
                                    <input type="text" class="form-control input-form-control apply-bg l-s2 amount" name="custom-amount" id="custom-amount" placeholder="Give an amount" autocomplete="off">
                                <?php else: ?>
                                    <input type="text" class="form-control input-form-control apply-bg l-s2 amount" name="custom-amount" id="custom-amount" value="<?=e($data['plan-details']['fixed_amount'])?>" readonly>
                                <?php endif ?>
                            </div>
                            <div class="error" id="custom-error"></div>
                        </div>
                        <?php if ($data['plan-details']['fixed_amount'] == 0): ?>
                            <p class="mb-0 text-gray-100 dark-B87 gilroy-regular r-f-12 f-12 mt-2"><em>* Note: Minimum invest <?= formatCurrency($data['plan-details']['minimum']) ?> <?= formatCurrency($data['user']['currency']) ?> and up to <?= formatCurrency($data['plan-details']['maximum']) ?> <?= formatCurrency($data['user']['currency']) ?></em></p>
                        <?php else: ?>
                            <p class="mb-0 text-gray-100 dark-B87 gilroy-regular r-f-12 f-12 mt-2"><em>* Note: Fixed investment amount <?= formatCurrency($data['plan-details']['fixed_amount']) ?> <?= formatCurrency($data['user']['currency']) ?></em></p>
                        <?php endif ?>
                    </div>

                    <div class="row" id="payment-method-div">
                        <div class="col-12">
                            <div class="mt-20 param-ref">
                                <label class="gilroy-medium text-gray-100 mb-2 f-15" for="method">Payment Method</label>
                                <div class="avoid-blink">
                                    <select class="select2 sl_common_bx" data-minimum-results-for-search="Infinity" name="method" id="method" autocomplete="off">
                                        <option value="deposit">Deposit & Invest</option>
                                        <option value="interest_wallet">Invest from balance - <?= e(number_format($data['user']['interest_wallet'], 2)) ?> <?= formatCurrency($data['user']['currency']) ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid">
                        <?=$this->token()?>
                        <button type="submit" class="btn btn-lg btn-primary mt-4" id="schemesBtn" data-plan="<?=e($data['plan-details']['planId'])?>">

                            <span>Next</span>

                            <svg class="position-relative ms-1 rtl-wrap-one nscaleX-1" width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M4.11648 12.216C3.81274 11.9123 3.81274 11.4198 4.11648 11.1161L8.23317 6.99937L4.11648 2.88268C3.81274 2.57894 3.81274 2.08647 4.11648 1.78273C4.42022 1.47899 4.91268 1.47899 5.21642 1.78273L9.88309 6.4494C10.1868 6.75314 10.1868 7.2456 9.88309 7.54934L5.21642 12.216C4.91268 12.5198 4.42022 12.5198 4.11648 12.216Z" fill="currentColor"/>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
            <!-- main-containt -->
        </div>
    </div>
</div>
