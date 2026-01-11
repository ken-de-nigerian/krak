<?php
defined('FIR') OR exit();
/**
 * The template for displaying Home page content
 */
?>
<div class="page-content">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                    <h4 class="mb-sm-0">Home</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a>Dashboard</a></li>
                            <li class="breadcrumb-item active">Home</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-xl-12">
                <div class="card crm-widget">
                    <div class="card-body p-0">
                        <div class="row row-cols-xxl-5 row-cols-md-3 row-cols-1 g-0">
                            <div class="col">
                                <div class="py-4 px-3">
                                    <h5 class="text-muted text-uppercase fs-13">Total Users <i class="ri-arrow-up-circle-line text-success fs-18 float-end align-middle"></i></h5>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <i class="ri-group-line display-6 text-muted cfs-22"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h2 class="mb-0 cfs-22"><span class="counter-value" data-target="<?=e($data["all-users-count"])?>"><?=e($data["all-users-count"])?></span></h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end col -->

                            <div class="col">
                                <div class="mt-3 mt-md-0 py-4 px-3">
                                    <h5 class="text-muted text-uppercase fs-13">Active Users <i class="ri-arrow-up-circle-line text-success fs-18 float-end align-middle"></i></h5>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <i class="ri-user-follow-line display-6 text-muted cfs-22"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h2 class="mb-0 cfs-22"><span class="counter-value" data-target="<?=e($data["active-users-count"])?>"><?=e($data["active-users-count"])?></span></h2>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- end col -->
                            <div class="col">
                                <div class="mt-3 mt-md-0 py-4 px-3">
                                    <h5 class="text-muted text-uppercase fs-13">Banned Users <i class="ri-arrow-down-circle-line text-danger fs-18 float-end align-middle"></i></h5>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <i class="ri-user-unfollow-line display-6 text-muted cfs-22"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h2 class="mb-0 cfs-22"><span class="counter-value" data-target="<?=e($data["banned-users-count"])?>"><?=e($data["active-users-count"])?></span></h2>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- end col -->
                            <div class="col">
                                <div class="mt-3 mt-lg-0 py-4 px-3">
                                    <h5 class="text-muted text-uppercase fs-13">KYC Unverified Users <i class="ri-arrow-up-circle-line text-success fs-18 float-end align-middle"></i></h5>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <i class="ri-open-arm-line display-6 text-muted cfs-22"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h2 class="mb-0 cfs-22"><span class="counter-value" data-target="<?=e($data["kyc-unverified-count"])?>"><?=e($data["kyc-unverified-count"])?></span></h2>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- end col -->
                            <div class="col">
                                <div class="mt-3 mt-lg-0 py-4 px-3">
                                    <h5 class="text-muted text-uppercase fs-13">KYC Pending Users <i class="ri-arrow-down-circle-line text-danger fs-18 float-end align-middle"></i></h5>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <i class="ri-parent-line display-6 text-muted cfs-22"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h2 class="mb-0 cfs-22"><span class="counter-value" data-target="<?=e($data["kyc-pending-count"])?>"><?=e($data["kyc-pending-count"])?></span></h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end col -->
                        </div>
                        <!-- end row -->
                    </div>
                    <!-- end card body -->
                </div>
                <!-- end card -->
            </div>
            <!-- end col -->
        </div>
        <!-- end row -->

        <div class="row">
            <div class="col-lg-12">
                <div class="swiper cryptoSlider">
                    <div class="swiper-wrapper">
                        <?php foreach ($data['get-gateway'] as $index => $gateway): ?>
                            <div class="swiper-slide convert" id="gateway_<?=$index?>">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <img src="<?=$this->siteUrl().'/'.PUBLIC_PATH.'/'.UPLOADS_PATH?>/deposit/<?=e($gateway['image'])?>" class="bg-light rounded-circle p-1 avatar-xs img-fluid material-shadow" alt="" />
                                            <h6 class="ms-2 mb-0 fs-14"><?=e($gateway['name'])?></h6>
                                        </div>

                                        <div class="row align-items-end g-0">
                                            <div class="col-6">
                                                <input type="hidden" class="abbreviation" name="abbreviation" value="<?=e($gateway['abbreviation'])?>">
                                                <h5 class="mb-1 mt-4 converted">0.00</h5>
                                            </div>
                                        </div>
                                        <!-- end row -->
                                    </div>
                                    <!-- end card body -->
                                </div>
                                <!-- end card -->
                            </div>
                        <?php endforeach ?>
                    </div>
                    <!-- end swiper wrapper -->
                </div>
                <!-- end swiper -->
            </div>
            <!-- end col -->
        </div>
        <!-- end row -->

        <div class="row">
            <div class="col-xxl-6 col-lg-6">
                <?php if (empty($data['newly-registered'])): ?>
                    <div class="card">
                        <div class="card-body">
                            <div class="text-center">
                                <div class="tab-pane fade show active py-2 ps-2" id="all-noti-tab" role="tabpanel">
                                    <div class="empty-notification-elem">
                                        <div class="w-25 w-sm-50 pt-3 mx-auto">
                                            <img src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/admin/images/svg/bell.svg" class="img-fluid" alt="user-pic" />
                                        </div>

                                        <div class="text-center pb-5 mt-2">
                                            <h6 class="fs-18 fw-semibold lh-base">No accounts found.</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="card card-height-100">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Newly Registered</h4>
                        </div>

                        <!-- end card-header -->
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush border-dashed mb-0">
                                <?php foreach ($data['newly-registered'] as $user): ?>
                                    <li class="list-group-item d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <img src="<?=$this->siteUrl().'/'.PUBLIC_PATH.'/'.UPLOADS_PATH?>/users/<?=e($user["imagelocation"])?>" class="avatar-xs rounded-circle" alt="">
                                        </div>

                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="fs-14 mb-1"><?=e($user["firstname"])?> <?=e($user["lastname"])?></h6>
                                            <p class="text-muted mb-0">
                                                <?php
                                                $referred_by = "None";
                                                foreach($data['users'] as $refer) {
                                                    if($refer['userid'] === $user['ref_by']) {
                                                        $referred_by = e($refer["firstname"]) . " " . e($refer["lastname"]);
                                                        break;
                                                    }
                                                }
                                                ?>
                                                referred by - <?= $referred_by ?>
                                            </p>
                                        </div>

                                        <div class="flex-shrink-0 text-end">
                                            <h6 class="fs-14 mb-1">
                                                <?= date('d M Y h:i A', strtotime($user['registration_date'])) ?>
                                            </h6>

                                            <?php if ($user["status"] == 1): ?>
                                                <p class="text-success fs-12 mb-0">Active</p>
                                            <?php elseif ($user["status"] == 2): ?>
                                                <p class="text-danger fs-12 mb-0">Banned</p>
                                            <?php endif ?>
                                        </div>
                                    </li>
                                <?php endforeach ?>
                            </ul>
                            <!-- end ul -->
                            <div class="mt-4 mb-4 text-center">
                                <a href="<?=$this->siteUrl()?>/admin/users" class="text-muted text-decoration-underline">Show All</a>
                            </div>
                        </div>
                        <!-- end card body -->
                    </div>
                <?php endif; ?>
            </div>
            <!-- end col -->

            <div class="col-xxl-6 col-lg-6">
                <?php if (empty($data['recent-transactions'])): ?>
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
                <?php else: ?>
                    <div class="card card-height-100">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Recent Transactions</h4>
                        </div>

                        <!-- end card-header -->
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush border-dashed mb-0">
                                <?php foreach ($data['recent-transactions'] as $transact): ?>
                                    <?php foreach($data['users'] as $user){
                                        if($user['userid'] === $transact['userid']){ ?>
                                        <li class="list-group-item d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <img src="<?=$this->siteUrl().'/'.PUBLIC_PATH.'/'.UPLOADS_PATH?>/users/<?=e($user["imagelocation"])?>" class="avatar-xs rounded-circle" alt="">
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
                            <div class="mt-4 mb-4 text-center">
                                <a href="<?=$this->siteUrl()?>/admin/transactions" class="text-muted text-decoration-underline">Show All</a>
                            </div>
                        </div>
                        <!-- end card body -->
                    </div>
                    <!-- end card -->
                <?php endif; ?>
            </div>
            <!-- end col -->
        </div>
        <!-- end row -->
    </div>
    <!-- container-fluid -->
</div>

