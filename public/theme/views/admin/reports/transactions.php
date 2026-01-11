<?php
defined('FIR') OR exit();
/**
 * The template for displaying the footer section
 */
$AllTransactionsPerPage = 10;?>
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card rounded-0 bg-success-subtle mx-n4 mt-n4 border-top">
                    <div class="px-4">
                        <div class="row">
                            <div class="col-xxl-5 align-self-center">
                                <div class="py-4">
                                    <h4 class="display-6 coming-soon-text">Transactions</h4>
                                    <p class="text-success fs-15 mt-3">You have full control to monitor all transactions on your site.</p>
                                    <div class="hstack flex-wrap gap-2">
                                        <a href="<?=$this->siteUrl()?>/admin/dashboard" class="btn btn-primary btn-label rounded-pill">
                                            <i class="ri-arrow-go-back-fill label-icon align-middle rounded-pill fs-16 me-2"></i>Go Back
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end card body -->
                </div>
                <!-- end card -->

                <?php if (empty($data['transactions'])): ?>
                    <div class="row">
                        <div class="card">
                            <div class="card-body">
                                <div class="text-center">
                                    <div class="tab-pane fade show active py-2 ps-2" id="all-noti-tab" role="tabpanel">
                                        <div class="empty-notification-elem">
                                            <div class="w-25 w-sm-50 pt-3 mx-auto">
                                                <img src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/admin/images/svg/bell.svg" class="img-fluid" alt="user-pic" />
                                            </div>

                                            <div class="text-center pb-5 mt-2">
                                                <h6 class="fs-18 fw-semibold lh-base">No transactions found.</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="col-xxl-12 col-lg-12">
                        <div class="card card-height-100">
                            <div class="card-body p-0" id="loadMoreAllTransactionsContainer">
                                <ul class="list-group list-group-flush border-dashed mb-0 transactions">
                                    <?php foreach ($data['transactions'] as $transact): ?>
                                        <?php foreach($data['users'] as $user){
                                            if($user['userid'] === $transact['userid']){ ?>
                                            <li class="list-group-item d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="<?=$this->siteUrl().'/'.PUBLIC_PATH.'/'.UPLOADS_PATH?>/users/<?=e($user["imagelocation"])?>" class="avatar-xs rounded-circle" alt="" />
                                                </div>

                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="fs-14 mb-1"><?=e($user["firstname"])?> <?=e($user["lastname"])?></h6>
                                                    <p class="text-muted mb-0"><?=e($transact["details"])?></p>
                                                </div>

                                                <div class="flex-shrink-0 text-end">
                                                    <h6 class="fs-14 mb-1"><?= e(date('d M, Y', strtotime($transact["created_at"]))) ?></h6>
                                                    <?php if ($transact["trx_type"] == "+"): ?>
                                                        <p class="text-success fs-12 mb-0">+ $<?=e($transact["amount"])?></p>
                                                    <?php else: ?>
                                                        <p class="text-danger fs-12 mb-0">- $<?=e($transact["amount"])?></p>
                                                    <?php endif ?>
                                                </div>
                                            </li>
                                        <?php
                                            break;
                                            }
                                        } ?>
                                    <?php endforeach;?>
                                </ul>
                                <!-- end ul -->
                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->
                    </div>

                    <!-- end row -->
                    <?php if (count($data['transactions']) >= $AllTransactionsPerPage): ?>
                    <div class="text-center mb-3 mt-3">
                        <button class="btn btn-primary waves-effect waves-light loadMoreAllTransactions" data-page="2">Load More </button>
                    </div>
                    <?php endif; ?>

                    <div class="row d-none" id="AllTransactionsLastpage">
                        <div class="offset-lg-3 col-lg-6 col-md-12 col-12 text-center mt-3"><p>You’ve reached the end of the list</p></div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <!--end row-->
    </div>
    <!-- container-fluid -->
</div>
<!-- End Page-content -->