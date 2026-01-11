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


<script>
    "use strict"
    function createCountDown(elementId, sec) {
        // Check if the elementId exists and is not empty
        if (elementId && elementId.length > 0) {
            let tms = sec;
            const x = setInterval(function () {
                const distance = tms * 1000;
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                document.getElementById(elementId).innerHTML = days + "d: " + hours + "h " + minutes + "m " + seconds + "s ";
                if (distance < 0) {
                    clearInterval(x);
                    document.getElementById(elementId).innerHTML = '<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> resolving</span>';
                }
                tms--;
            }, 1000);
        }
    }
</script>

<div class="position-relative">
    <div class="containt-parent">
        <div class="main-containt">
            <!-- main-containt -->
            <div class="text-center">
                <p class="mb-0 gilroy-Semibold f-26 text-dark theme-tran r-f-20 text-uppercase">Investment Details</p>
                <p class="mb-0 gilroy-medium text-gray-100 f-16 r-f-12 mt-2 tran-title p-inline-block">Everything you need to know about your investment</p>
            </div>

            <div class="d-flex align-items-center back-direction mt-24">
                <a href="<?=$this->siteUrl()?>/user/investments" class="text-gray-100 f-16 leading-20 gilroy-medium d-inline-flex align-items-center position-relative back-btn">
                    <svg class="position-relative nscaleX-1" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M8.47075 10.4709C8.7311 10.2105 8.7311 9.78842 8.47075 9.52807L4.94216 5.99947L8.47075 2.47087C8.7311 2.21053 8.7311 1.78842 8.47075 1.52807C8.2104 1.26772 7.78829 1.26772 7.52794 1.52807L3.52795 5.52807C3.2676 5.78842 3.2676 6.21053 3.52795 6.47088L7.52794 10.4709C7.78829 10.7312 8.2104 10.7312 8.47075 10.4709Z" fill="currentColor"/>
                    </svg>
                    <span class="ms-1 back-btn">Back to list</span>
                </a>
            </div>

            <div class="invested-Profit-plan bg-white mt-12">
                <div class="plan_profit">
                    <div class="row col-gap-20">
                        <div class="col-xl-4">
                            <div class="inv-plan">
                                <p class="mb-0 f-16 leading-20 text-gray gilroy-medium">Invest Plan</p>
                                <div class="mb-0 d-flex gilroy-Semibold mt-2 gap-12">
                                    <span class="f-26 leading-32 text-dark platinum">
                                        <?php foreach($data['plans'] as $plan){
                                                if($plan['planId'] === $data['investment-details']['planId']){ ?>
                                            <?=e($plan["name"])?>
                                        <?php
                                            break;
                                            }
                                        } ?> 
                                    </span>

                                    <?php if ($data['investment-details']['status'] == 1): ?>
                                        <span class="inv-status-badge f-11 leading-14 bg-success text-white d-flex justify-content-center align-items-center align-self-center">Completed</span>
                                    <?php elseif ($data['investment-details']['status'] == 2): ?>
                                        <span class="inv-status-badge f-11 leading-14 bg-warning text-white d-flex justify-content-center align-items-center align-self-center">Running</span>
                                    <?php elseif ($data['investment-details']['status'] == 3): ?>
                                        <span class="inv-status-badge f-11 leading-14 bg-primary text-white d-flex justify-content-center align-items-center align-self-center">Initiated</span>
                                    <?php elseif ($data['investment-details']['status'] == 4): ?>
                                        <span class="inv-status-badge f-11 leading-14 bg-danger text-white d-flex justify-content-center align-items-center align-self-center">Rejected</span>
                                    <?php endif ?>
                                </div>
                                <p class="mb-0 f-16 leading-20 text-gray-100 gilroy-medium mt-2">
                                    Return 

                                    <?php foreach($data['plans'] as $plan){
                                            if($plan['planId'] === $data['investment-details']['planId']){ ?>
                                        <?=e($plan["interest"])?>%
                                    <?php
                                        break;
                                        }
                                    } ?> 

                                    after 

                                    <?php foreach($data['times'] as $time){
                                            if($time['time'] === $data['investment-details']['hours']){ ?>
                                        <?=e($time["name"])?>
                                    <?php
                                        break;
                                        }
                                    } ?>
                                </p>
                            </div>
                        </div>
                        <div class="col-xl-4">
                            <div class="invest_profit bg-white-50">
                                <p class="mb-0 f-14 leading-17 text-gray-100 gilroy-medium">Invested &amp; Profit</p>
                                <p class="mb-0 f-22 leading-24 text-primary gilroy-Semibold mt-2"><?=e($data['investment-details']['amount'])?> <?= formatCurrency($data['user']['currency']) ?></p>
                                <p class="mb-0 f-16 leading-20 text-dark l-sp mt-5p gilroy-medium">+<?=e($data['investment-details']['interest'])?> <?= formatCurrency($data['user']['currency']) ?></p>
                            </div>
                        </div>

                        <div class="col-xl-4">
                            <div class="invest_capital bg-white h-100 d-flex flex-column">
                                <p class="mb-0 f-14 leading-17 text-gray-100 gilroy-medium text-start">Receivable amount (with capital)</p>
                                <p class="mb-0 f-22 leading-24 text-dark gilroy-Semibold mt-2 text-start"><?=e($data['receivable -amount'])?> <?= formatCurrency($data['user']['currency']) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-24 inv-row-gaps">
                <div class="col-xl-6">
                    <div class="inv-terms bg-white">
                        <div class="inv-terms-header border-b-EF">
                            <p class="mb-0 f-18 leading-24 gilroy-Semibold text-dark">Investment Terms</p>
                        </div>

                        <div class="d-flex justify-content-between mt-20">
                            <p class="mb-0 f-14 leading-17 gilroy-medium text-gray-100">Term duration</p>
                            <p class="mb-0 f-14 leading-17 gilroy-medium text-dark">
                                <?php foreach($data['times'] as $time){
                                        if($time['time'] === $data['investment-details']['hours']){ ?>
                                    <?=e($time["name"])?>
                                <?php
                                    break;
                                    }
                                } ?>
                            </p>
                        </div>

                        <div class="d-flex justify-content-between mt-20">
                            <p class="mb-0 f-14 leading-17 gilroy-medium text-gray-100">Profit</p>
                            <p class="mb-0 f-14 leading-17 gilroy-medium text-dark">
                                <?php foreach($data['plans'] as $plan){
                                        if($plan['planId'] === $data['investment-details']['planId']){ ?>
                                    <?=e($plan["interest"])?>%
                                <?php
                                    break;
                                    }
                                } ?> 
                            </p>
                        </div>

                        <div class="d-flex justify-content-between mt-20">
                            <p class="mb-0 f-14 leading-17 gilroy-medium text-gray-100">Minimum amount</p>
                            <p class="mb-0 f-14 leading-17 gilroy-medium text-dark">
                                <?php foreach($data['plans'] as $plan){
                                        if($plan['planId'] === $data['investment-details']['planId']){ ?>
                                    <?=e($plan["minimum"])?> <?= formatCurrency($data['user']['currency']) ?>
                                <?php
                                    break;
                                    }
                                } ?>
                            </p>
                        </div>

                        <div class="d-flex justify-content-between mt-20">
                            <p class="mb-0 f-14 leading-17 gilroy-medium text-gray-100">Maximum amount</p>
                            <p class="mb-0 f-14 leading-17 gilroy-medium text-dark">
                                <?php foreach($data['plans'] as $plan){
                                        if($plan['planId'] === $data['investment-details']['planId']){ ?>
                                    <?=e($plan["maximum"])?> <?= formatCurrency($data['user']['currency']) ?>
                                <?php
                                    break;
                                    }
                                } ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="inv-terms bg-white">
                        <div class="inv-terms-header border-b-EF">
                            <p class="mb-0 f-18 leading-24 gilroy-Semibold text-dark">Investment Details</p>
                        </div>

                        <div class="d-flex justify-content-between mt-24">
                            <p class="mb-0 f-14 leading-17 gilroy-medium text-gray-100">Invested Amount</p>
                            <p class="mb-0 f-14 leading-17 gilroy-medium text-dark">
                                <?=e($data['investment-details']['amount'])?> <?= formatCurrency($data['user']['currency']) ?>
                            </p>
                        </div>

                        <div class="d-flex justify-content-between mt-20">
                            <p class="mb-0 f-14 leading-17 gilroy-medium text-gray-100">Net profit</p>
                            <p class="mb-0 f-14 leading-17 gilroy-medium text-dark">
                                <?=e($data['investment-details']['interest'])?> <?= formatCurrency($data['user']['currency']) ?>
                            </p>
                        </div>

                        <div class="d-flex justify-content-between mt-20">
                            <p class="mb-0 f-14 leading-17 gilroy-medium text-gray-100">Investment starts at</p>
                            <p class="mb-0 f-14 leading-17 gilroy-medium text-dark">
                                <?= date('d-m-Y h:i A', strtotime($data['investment-details']['created_at'])) ?>
                            </p>
                        </div>

                        <div class="d-flex justify-content-between mt-20">
                            <p class="mb-0 f-14 leading-17 gilroy-medium text-gray-100">Investment ends at</p>
                            <p class="mb-0 f-14 leading-17 gilroy-medium text-dark">
                                <?= date('d-m-Y h:i A', strtotime($data['investment-details']['next_time'])) ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12">
                    <div class="settings-wrapper inv-details wrapper">
                        <div class="sliding-content-parent bg-white mt-3">
                            <div class="content-1">
                                <table class="table table-p table-bordered">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="details">
                                                    <p class="mb-0 f-13 leading-16 text-gray-100 gilroy-medium">Details</p>
                                                    <p class="mb-0 f-15 leading-18 text-primary gilroy-medium mt-2">
                                                        <?php foreach($data['plans'] as $plan){
                                                                if($plan['planId'] === $data['investment-details']['planId']){ ?>
                                                            <?=e($plan["name"])?>
                                                        <?php
                                                            break;
                                                            }
                                                        } ?> 
                                                    </p>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="details pl-8rem">
                                                    <p class="mb-0 f-13 leading-16 text-gray-100 gilroy-medium">Date &amp; Time</p>
                                                    <?php if ($data['investment-details']['status'] == 1): ?>
                                                        <p class="mb-0 f-15 leading-18 text-success gilroy-medium mt-2">
                                                            COMPLETED
                                                        </p>
                                                    <?php elseif ($data['investment-details']['status'] == 2): ?>
                                                        <p class="mb-0 f-15 leading-18 text-warning gilroy-medium mt-2" id="timestamp<?=e($data['investment-details']['id'])?>">
                                                        </p>
                                                    <?php elseif ($data['investment-details']['status'] == 3): ?>
                                                        <p class="mb-0 f-15 leading-18 text-dark gilroy-medium mt-2">
                                                            INITIATED
                                                        </p>
                                                    <?php elseif ($data['investment-details']['status'] == 4): ?>
                                                        <p class="mb-0 f-15 leading-18 text-danger gilroy-medium mt-2">
                                                            REJECTED
                                                        </p>
                                                    <?php endif ?>
                                                </div>

                                                <script>
                                                    createCountDown('timestamp<?php echo $data['investment-details']['id']; ?>', <?php 
                                                        $nextTime = strtotime($data['investment-details']['next_time']);
                                                        $now = time();
                                                        $diffInSeconds = $nextTime - $now;
                                                        echo $diffInSeconds; 
                                                    ?>);
                                                </script>
                                            </td>
                                            <td>
                                                <div class="details">
                                                    <p class="mb-0 f-13 leading-16 text-gray-100 gilroy-medium text-end">Amount</p>
                                                    <p class="mb-0 f-15 leading-18 text-primary gilroy-medium mt-2 text-end l-sp64">
                                                        + <?=e($data['receivable -amount'])?>
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>

                                    <?php
                                        if (!function_exists('diffDatePercent')) {
                                            function diffDatePercent($start , $end): string
                                            {
                                                $start = strtotime($start);
                                                $end = strtotime($end);

                                                $diff = $end - $start;

                                                $current = time();
                                                $cdiff = $current - $start;

                                                if ($cdiff > $diff) {
                                                    $percentage = 1.0;
                                                }elseif ($current < $start) {
                                                    $percentage = 0.0;
                                                }else {
                                                    $percentage = $cdiff / $diff;
                                                }

                                                return sprintf('%.2f%%', $percentage * 100);
                                            }
                                        }
                                    ?>
                                    
                                    <?php if ($data['investment-details']['status'] == 2): ?>
                                        <?php
                                            if (!empty($data['investment-details']['last_time'])) {
                                                $start = $data['investment-details']['last_time'];
                                            } else {
                                                $start = $data['investment-details']['created_at'];
                                            }
                                        ?>
                                        <!-- Progressbar -->
                                        <div class="custom-progress">
                                            <div class="progress mx-auto w-100">
                                                <div class="progress-bar bg-warning" role="progressbar" style="width: <?php echo diffDatePercent($start, $data['investment-details']['next_time']); ?>" id="progressBar" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    <?php endif ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- main-containt -->
        </div>
    </div>
</div>

