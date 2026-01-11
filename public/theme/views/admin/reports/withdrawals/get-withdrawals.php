<?php
defined('FIR') OR exit();
/**
 * The template for displaying the footer section
 */
$WithdrawalsPerPage = 5;?>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card rounded-0 bg-success-subtle mx-n4 mt-n4 border-top">
                    <div class="px-4">
                        <div class="row">
                            <div class="col-xxl-5 align-self-center">
                                <div class="py-4">
                                    <h4 class="display-6 coming-soon-text">Withdrawals</h4>
                                    <p class="text-success fs-15 mt-3">You have full control to monitor all withdrawals on your site.</p>
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

                <?php if (empty($data['withdrawals'])): ?>
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
                                                <h6 class="fs-18 fw-semibold lh-base">No withdrawals found.</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="col-xxl-12 col-lg-12">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Withdrawals History</h4>
                                <div class="col-sm-auto ms-auto">
                                    <div class="list-grid-nav hstack gap-1">
                                        <button type="button" id="dropdownMenuLink1" data-bs-toggle="dropdown" aria-expanded="false" class="btn btn-soft-info btn-icon material-shadow-none fs-14"><i class="ri-more-2-fill"></i></button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink1">
                                            <li><a class="dropdown-item active" href="<?=$this->siteUrl()?>/admin/withdrawals">All</a></li>
                                            <li><a class="dropdown-item" href="<?=$this->siteUrl()?>/admin/withdrawals_completed">Completed</a></li>
                                            <li><a class="dropdown-item" href="<?=$this->siteUrl()?>/admin/withdrawals_initiated">Initiated</a></li>
                                            <li><a class="dropdown-item" href="<?=$this->siteUrl()?>/admin/withdrawals_pending">Pending</a></li>
                                            <li><a class="dropdown-item" href="<?=$this->siteUrl()?>/admin/withdrawals_rejected">Rejected</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="table-card">
                                    <table class="table table-borderless align-middle" id="loadMoreWithdrawalsContainer">
                                        <tbody class="withdrawals">
                                            <?php foreach ($data['withdrawals'] as $withdrawal): ?>
                                            <?php foreach($data['withdrawal-gateways'] as $gateway){
                                                if($gateway['withdraw_code'] === $withdrawal['withdraw_code']){ ?>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="<?=$this->siteUrl().'/'.PUBLIC_PATH.'/'.UPLOADS_PATH?>/withdrawal/<?=e($gateway['image'])?>" alt="" class="avatar-xs rounded-circle" />

                                                            <div class="ms-2">
                                                                <a href="<?=$this->siteUrl()?>/admin/withdrawals/view-withdrawal/<?=e($withdrawal['withdrawId'])?>"><h6 class="fs-15 mb-1"><?=e($gateway['name'])?></h6></a>
                                                                <small class="mb-0 text-muted"><?= date('d-m-Y h:i A', strtotime($withdrawal['created_at'])) ?></small>
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <td>$<?= number_format((float) e($withdrawal['amount']), 2) ?></td>

                                                    <?php if ($withdrawal['status'] == 0): ?>
                                                        <td>Initiated</td>
                                                    <?php elseif ($withdrawal['status'] == 1): ?>
                                                        <td class="text-success">Completed</td>
                                                    <?php elseif ($withdrawal['status'] == 2): ?>
                                                        <td class="text-warning">Pending</td>
                                                    <?php elseif ($withdrawal['status'] == 3): ?>
                                                        <td class="text-danger">Rejected</td>
                                                    <?php endif ?>

                                                    <td>
                                                        <div class="dropdown d-inline-block">
                                                            <a href="<?=$this->siteUrl()?>/admin/withdrawals/view-withdrawal/<?=e($withdrawal['withdrawId'])?>" class="btn btn-soft-secondary btn-sm dropdown">
                                                                Details
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php
                                                break;
                                                }
                                            } ?> 
                                            <?php endforeach ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->
                    </div>

                    <!-- end row -->
                    <?php if (count($data['withdrawals']) >= $WithdrawalsPerPage): ?>
                    <div class="text-center mb-3 mt-3">
                        <button class="btn btn-primary waves-effect waves-light loadMoreWithdrawals" data-page="2">Load More </button>
                    </div>
                    <?php endif; ?>

                    <div class="row d-none" id="WithdrawalsLastpage">
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