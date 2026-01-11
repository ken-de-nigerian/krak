<?php
defined('FIR') OR exit();
/**
 * The template for displaying Example Create page
 */
$InitiatedInvestmentsPerPage = 5;?>
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

<?php if (empty($data['get-investments'])): ?>
    <div class="position-relative">
        <div class="containt-parent">
            <div class="main-containt">
                <!-- main-containt -->
                <div class="text-center" id="invest_list">
                    <p class="mb-0 gilroy-Semibold f-26 text-dark theme-tran r-f-20 text-uppercase">Investment list</p>
                    <p class="mb-0 gilroy-medium text-gray-100 f-16 r-f-12 mt-2 tran-title p-inline-block">Below is the comprehensive list of all initiated investments currently on record.</p>
                </div>

                <div class="d-flex justify-content-between mt-24 mb-3 r-mt-22 align-items-center">
                    <div class="me-2 me-3">
                        <div class="param-ref param-ref-withdraw filter-ref r-filter-ref w-135">
                            <label for="status"></label>
                            <select name="status" class="select2 f-13" id="status" data-minimum-results-for-search="Infinity">
                                <option value="all">All</option>
                                <option value="initiated" selected>Initiated</option>
                                <option value="pending">Pending</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div>

                    <a href="<?=$this->siteUrl()?>/user/plans" class="btn bg-primary text-light Add-new-btn w-176 addnew">
                        <span class="f-14 gilroy-medium"> + New Investment</span>
                    </a>
                </div>
                
                <div class="notfound mt-16 bg-white p-4">
                    <div class="d-flex flex-wrap justify-content-center align-items-center gap-26">
                        <div class="image-notfound">
                            <img src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/dist/images/not-found.png" class="img-fluid"  alt=""/>
                        </div>
                        <div class="text-notfound">
                            <p class="mb-0 f-20 leading-25 gilroy-medium text-dark">Sorry! No data found.</p>
                            <p class="mb-0 f-16 leading-24 gilroy-regular text-gray-100 mt-12">As of now, there are no investment records available.</p>                        
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
                <div class="text-center" id="invest_list">
                    <p class="mb-0 gilroy-Semibold f-26 text-dark theme-tran r-f-20 text-uppercase">Investment list</p>
                    <p class="mb-0 gilroy-medium text-gray-100 f-16 r-f-12 mt-2 tran-title p-inline-block">The List of all the investments you had or currently have ongoing</p>
                </div>

                <div class="d-flex justify-content-between mt-24 mb-3 r-mt-22 align-items-center">
                    <div class="me-2 me-3">
                        <div class="param-ref param-ref-withdraw filter-ref r-filter-ref w-135">
                            <label for="status"></label>
                            <select name="status" class="select2 f-13" id="status" data-minimum-results-for-search="Infinity">
                                <option value="all">All</option>
                                <option value="initiated" selected>Initiated</option>
                                <option value="pending">Pending</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div>

                    <a href="<?=$this->siteUrl()?>/user/plans" class="btn bg-primary text-light Add-new-btn w-176 addnew">
                        <span class="f-14 gilroy-medium"> + New Investment</span>
                    </a>
                </div>

                <div id="loadMoreInitiatedInvestmentsContainer">
                    <div class="list-group">
                        <?php foreach ($data['get-investments'] as $investment): ?>
                            <div class="transac-parent cursor-pointer">
                                <div class="d-flex justify-content-between transac-child">
                                    <div class="d-flex w-50">
                                        <div class="deposit-circle d-flex justify-content-center align-items-center">
                                            <img src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/frontend/templates/images/investment.png" alt="Transaction" />
                                        </div>

                                        <div class="ml-20 r-ml-8">
                                            <p class="mb-0 text-dark f-16 gilroy-medium theme-tran">
                                                <?php foreach($data['plans'] as $plan){
                                                        if($plan['planId'] === $investment['planId']){ ?>
                                                    <?=e($plan["name"])?>
                                                <?php
                                                    break;
                                                    }
                                                } ?> 
                                            </p>

                                            <div class="d-flex flex-wrap">
                                                <p class="mb-0 text-gray-100 f-13 leading-17 gilroy-regular tran-title mt-2">Date</p>
                                                <p class="mb-0 text-gray-100 f-13 leading-17 gilroy-regular tran-title mt-2 d-flex justify-content-center align-items-center">
                                                    <svg class="mx-2 text-muted-100" width="4" height="4" viewBox="0 0 4 4" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <circle cx="2" cy="2" r="2" fill="currentColor" />
                                                    </svg>
                                                    <?= date('d-m-Y h:i A', strtotime($investment['initiated_at'])) ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-center align-items-center">
                                        <div>
                                            <p class="mb-0 gilroy-medium text-gray-100 r-f-12 f-16 ph-20">
                                                <?= formatCurrency($data['user']['currency'], $investment['amount']) ?>
                                            </p>

                                            <?php if ($investment['status'] == 1): ?>
                                                <p class="text-success f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">
                                                    Completed
                                                </p>
                                            <?php elseif ($investment['status'] == 2): ?>
                                                <p class="text-warning f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">
                                                    Running
                                                </p>
                                            <?php elseif ($investment['status'] == 3): ?>
                                                <p class="text-dark f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">
                                                    Initiated
                                                </p>
                                            <?php elseif ($investment['status'] == 4): ?>
                                                <p class="text-danger f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">
                                                    Rejected
                                                </p>
                                            <?php endif ?>
                                        </div>

                                        <div class="cursor-pointer transaction-arrow ml-28 r-ml-12">
                                            <a href="<?php echo ($investment['status'] == 1 || $investment['status'] == 2 || $investment['status'] == 4) ? $this->siteUrl() . '/user/investments/investment-details/' . e($investment['investId']) : 'javascript:void(0)'; ?>" class="arrow-hovers">
                                                <svg class="nscaleX-1" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M3.5312 1.52861C3.27085 1.78896 3.27085 2.21107 3.5312 2.47141L7.0598 6.00001L3.5312 9.52861C3.27085 9.78895 3.27085 10.2111 3.5312 10.4714C3.79155 10.7318 4.21366 10.7318 4.47401 10.4714L8.47401 6.47141C8.73436 6.21106 8.73436 5.78895 8.47401 5.52861L4.47401 1.52861C4.21366 1.26826 3.79155 1.26826 3.5312 1.52861Z" fill="currentColor"/>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>

                <?php if (count($data['get-investments']) >= $InitiatedInvestmentsPerPage): ?>
                <div class="mt-4">
                    <div class="text-center">
                        <button class="btn btn-sm btn-primary text-light loadMoreInitiatedInvestments" data-page="2">
                            <span>Load More</span>
                        </button>
                    </div>
                </div>
                <?php endif; ?>

                <div class="row d-none" id="InitiatedInvestmentsLastpage">
                    <div class="offset-lg-3 col-lg-6 col-md-12 col-12 text-center mt-3 text-dark"><p>You’ve reached the end of the list</p></div>
                </div>
                <!-- main-containt -->
            </div>
        </div>
    </div>
<?php endif; ?>