<?php
defined('FIR') OR exit();
/**
 * The template for displaying the footer section
 */
$AllCommissionsPerPage = 5;?>
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card rounded-0 bg-success-subtle mx-n4 mt-n4 border-top">
                    <div class="px-4">
                        <div class="row">
                            <div class="col-xxl-5 align-self-center">
                                <div class="py-4">
                                    <h4 class="display-6 coming-soon-text">Commissions</h4>
                                    <p class="text-success fs-15 mt-3">You have full control to monitor all referral comissions earned on your site.</p>
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

                <?php if (empty($data['commissions'])): ?>
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
                                                <h6 class="fs-18 fw-semibold lh-base">No commissions found.</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="row mb-3" id="loadMoreAllCommissionsContainer">
                        <div class="col-xl-12 commissions">
                            <?php foreach ($data['commissions'] as $refer): ?>
                                <div class="card product">
                                    <div class="card-body">
                                        <div class="row gy-3">
                                            <div class="col-sm">
                                                <h5 class="fs-14 text-truncate">
                                                    <a class="text-body">
                                                        <?php foreach($data['users'] as $user){
                                                            if($user['userid'] === $refer['to_id']){ ?>
                                                            <?=e($user["firstname"])?> <?=e($user["lastname"])?>
                                                        <?php
                                                            break;
                                                            }
                                                        } ?>
                                                    </a>
                                                </h5>

                                                <ul class="list-inline text-muted">
                                                    <li class="list-inline-item">Earned $<?=e($refer["commission_amount"])?> <?=e($refer["title"])?></li>
                                                </ul>
                                            </div>

                                            <div class="col-sm-auto">
                                                <div class="text-lg-end">
                                                    <p class="text-muted mb-1">Invested Amount:</p>
                                                    <h5 class="fs-14">$<span id="ticket_price" class="product-price"><?=e($refer["main_amount"])?></span></h5>
                                                </div>
                                            </div>

                                            <div class="col-sm-auto">
                                                <div class="text-lg-end">
                                                    <p class="text-muted mb-1">Referral Percent:</p>
                                                    <h5 class="fs-14"><?=e($refer["percent"])?>%</span></h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end card -->
                            <?php endforeach;?>
                        </div>
                        <!-- end col -->
                    </div>

                    <!-- end row -->
                    <?php if (count($data['commissions']) >= $AllCommissionsPerPage): ?>
                    <div class="text-center mb-3 mt-3">
                        <button class="btn btn-primary waves-effect waves-light loadMoreAllCommissions" data-page="2">Load More </button>
                    </div>
                    <?php endif; ?>

                    <div class="row d-none" id="AllCommissionsLastpage">
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