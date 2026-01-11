<?php
defined('FIR') OR exit();
/**
 * The template for displaying Example Create page
 */
$TransactionsPerPage = 5;?>
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
<?php if (empty($data['get-transactions'])): ?>
    <div class="position-relative">
        <div class="containt-parent">
            <div class="main-containt">
                <!-- main-containt -->
                <div class="text-center mb-3" id="invest_list">
                    <p class="mb-0 gilroy-Semibold f-26 text-dark theme-tran r-f-20 text-uppercase">Transactions list</p>
                    <p class="mb-0 gilroy-medium text-gray-100 f-16 r-f-12 mt-2 tran-title p-inline-block">The list of all the transactions you have.</p>
                </div>

                <div class="notfound mt-24 mb-3 r-mt-22 bg-white p-4">
                    <div class="d-flex flex-wrap justify-content-center align-items-center gap-26">
                        <div class="image-notfound">
                            <img src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/dist/images/not-found.png" class="img-fluid"  alt=""/>
                        </div>
                        <div class="text-notfound">
                            <p class="mb-0 f-20 leading-25 gilroy-medium text-dark">Sorry! No data found.</p>
                            <p class="mb-0 f-16 leading-24 gilroy-regular text-gray-100 mt-12">As of now, there are no transactions records available.</p>                        
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
                <div class="text-center mb-3" id="invest_list">
                    <p class="mb-0 gilroy-Semibold f-26 text-dark theme-tran r-f-20 text-uppercase">Transactions list</p>
                    <p class="mb-0 gilroy-medium text-gray-100 f-16 r-f-12 mt-2 tran-title p-inline-block">The list of all the transactions you have.</p>
                </div>

                <div id="loadMoreTransactionsContainer" class="mt-24 mb-3 r-mt-22">
                    <div class="list-group">
                        <?php foreach ($data['get-transactions'] as $transaction): ?>
                            <div class="transac-parent">
                                <div class="d-flex justify-content-between transac-child">
                                    <div class="d-flex w-50">
                                        <div class="deposit-circle d-flex justify-content-center align-items-center">
                                            <?php if ($transaction['trx_type'] == "+"): ?>
                                                <img src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/frontend/templates/images/cashin.png" alt="Transaction" />
                                            <?php elseif ($transaction['trx_type'] == "-"): ?>
                                                <img src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/frontend/templates/images/cashout.png" alt="Transaction" />
                                            <?php endif ?>
                                        </div>

                                        <div class="ml-20 r-ml-8">
                                            <p class="mb-0 text-dark f-16 gilroy-medium theme-tran">
                                                <?=e($transaction['details'])?>
                                            </p>

                                            <div class="d-flex flex-wrap">
                                                <p class="mb-0 text-gray-100 f-13 leading-17 gilroy-regular tran-title mt-2">Date</p>
                                                <p class="mb-0 text-gray-100 f-13 leading-17 gilroy-regular tran-title mt-2 d-flex justify-content-center align-items-center">
                                                    <svg class="mx-2 text-muted-100" width="4" height="4" viewBox="0 0 4 4" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <circle cx="2" cy="2" r="2" fill="currentColor" />
                                                    </svg>
                                                    <?= date('d-m-Y h:i A', strtotime($transaction['created_at'])) ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-center align-items-center">
                                        <div>
                                            <p class="mb-0 gilroy-medium text-gray-100 r-f-12 f-16 ph-20">
                                                <?= formatCurrency($data['user']['currency'], $transaction['amount']) ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>

                <?php if (count($data['get-transactions']) >= $TransactionsPerPage): ?>
                <div class="mt-4">
                    <div class="text-center">
                        <button class="btn btn-sm btn-primary text-light loadMoreTransactions" data-page="2">
                            <span>Load More</span>
                        </button>
                    </div>
                </div>
                <?php endif; ?>

                <div class="row d-none" id="TransactionsLastpage">
                    <div class="offset-lg-3 col-lg-6 col-md-12 col-12 text-center mt-3 text-dark"><p>You’ve reached the end of the list</p></div>
                </div>
                <!-- main-containt -->
            </div>
        </div>
    </div>
<?php endif; ?>