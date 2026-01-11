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
<?php if (empty($data['plans'])): ?>
    <div class="position-relative">
        <div class="containt-parent">
            <div class="main-containt">
                <!-- main-containt -->
                <div class="notfound mt-16 bg-white p-4">
                    <div class="d-flex flex-wrap justify-content-center align-items-center gap-26">
                        <div class="image-notfound">
                            <img src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/dist/images/not-found.png" class="img-fluid" />
                        </div>
                        <div class="text-notfound">
                            <p class="mb-0 f-20 leading-25 gilroy-medium text-dark">Sorry! No investments found.</p>
                            <p class="mb-0 f-16 leading-24 gilroy-regular text-gray-100 mt-12">As of now, there are no investment plans added.</p>
                        </div>
                    </div>
                </div>
                <!-- main-containt -->
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="position-relative">
        <div class="containt-parent">
            <div class="main-containt">
                <!-- main-containt -->
                <div class="text-center inv-title px-326">
                    <p class="mb-0 gilroy-Semibold f-26 text-dark theme-tran r-f-20 text-uppercase">Investment plans</p>
                    <p class="mb-0 gilroy-medium text-gray-100 f-16 leading-26 r-f-12 mt-2 inv-title tran-title">
                        Here are our several investment plans. You can invest daily, weekly or monthly and get higher returns in your investment.
                    </p>
                </div>

                <div class="row">
                    <?php foreach ($data['plans'] as $plan): ?>
                    <div class="col-xl-4 col-top">
                        <form id="invest-form-<?= e($plan['planId']) ?>">
                            <label for="plan-iv-<?= e($plan['planId']) ?>"></label>
                            <input id="plan-iv-<?= e($plan['planId']) ?>" name="planId" value="<?= e($plan['planId']) ?>" hidden>
                            <div class="invest-plan plan-diamond bg-white bdr-8">
                                <p class="text-dark d-title f-20 leading-24 gilroy-Semibold text-uppercase text-center"><?=e($plan['name'])?></p>

                                <div class="mt-14 profit-duration d-flex justify-content-between bg-white-100">
                                    <div class="daily-profit">
                                        <p class="mb-0 f-13 leading-16 gilroy-medium text-dark">Daily Profit</p>
                                        <p class="mb-0 f-20 leading-24 text-primary gilroy-Semibold mt-2"><?=e($plan['interest'])?>%</p>
                                    </div>

                                    <div class="duration text-end">
                                        <p class="mb-0 f-13 leading-16 gilroy-medium text-dark">Duration</p>
                                        <p class="mb-0 f-20 leading-24 text-primary gilroy-Semibold mt-2">
                                            <?php foreach($data['times'] as $time){
                                                if($time['time'] === $plan['times']){ ?>
                                                <?=e($time["name"])?>
                                            <?php
                                                break;
                                                }
                                            } ?>
                                        </p>
                                    </div>
                                </div>

                                <div class="min-max-amount border-b-EF d-flex justify-content-between">
                                    <?php if ($plan['fixed_amount'] == 0): ?>
                                        <div class="min-max-left">
                                            <p class="mb-0 f-13 leading-16 gilroy-medium text-gray-100">Min Amount</p>
                                            <p class="mb-0 f-16 leading-20 gilroy-Semibold text-dark mt-2"><?=e($plan['minimum'])?> <?= formatCurrency($data['user']['currency']) ?></p>
                                        </div>

                                        <div class="min-max-right">
                                            <p class="mb-0 f-13 leading-16 gilroy-medium text-gray-100">Max Amount</p>
                                            <p class="mb-0 f-16 leading-20 gilroy-Semibold text-dark mt-2"><?= ($plan['maximum'] === "Unlimited") ? $plan['maximum'] : e($plan['maximum'] . ' ' . formatCurrency($data['user']['currency'])) ?></p>
                                        </div>
                                    <?php else: ?>
                                        <div class="min-max-right">
                                            <p class="mb-0 f-13 leading-16 gilroy-medium text-gray-100">Investment Amount</p>
                                            <p class="mb-0 f-16 leading-20 gilroy-Semibold text-dark mt-2"><?=e($plan['fixed_amount'])?> <?= formatCurrency($data['user']['currency']) ?></p>
                                        </div>
                                    <?php endif ?>
                                </div>

                                <div class="terms mt-20">
                                    <div class="d-flex justify-content-between">
                                        <p class="mb-0 f-14 leading-17 gilroy-medium text-gray-100">ROI</p>
                                        <p class="mb-0 f-14 leading-17 gilroy-medium text-dark">
                                            <?php 
                                                $total = $plan['interest'] * $plan['repeat_time'];
                                                echo $total . '%';
                                            ?>
                                        </p>
                                    </div>

                                    <div class="d-flex justify-content-between mt-16">
                                        <p class="mb-0 f-14 leading-17 gilroy-medium text-gray-100">Referral</p>
                                        <p class="mb-0 f-14 leading-17 gilroy-medium text-dark">
                                            <?=e($data['referral-settings']['percent'])?>%
                                        </p>
                                    </div>

                                    <div class="d-flex justify-content-between mt-16">
                                        <p class="mb-0 f-14 leading-17 gilroy-medium text-gray-100">Capital Return</p>
                                        <p class="mb-0 f-14 leading-17 gilroy-medium text-dark">
                                            <?php if ($plan['capital_back_status'] == 1): ?>
                                                Yes
                                            <?php else: ?>
                                                No
                                            <?php endif ?>
                                        </p>
                                    </div>
                                </div>

                                <div class="d-grid">
                                    <?=$this->token()?>
                                    <button type="submit" id="planBtn" class="investment-btn green-btn cursor-pointer bg-primary d-flex justify-content-center mt-24 b-none">
                                        <span class="f-14 leading-20 gilroy-regular inv-btn text-white">Invest Now</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <?php endforeach ?>
                </div>
                <!-- main-containt -->
            </div>
        </div>
    </div>
<?php endif; ?>