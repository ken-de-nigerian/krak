<?php
defined('FIR') OR exit();
/**
 * The template for displaying Home page content
 */

$DepositsPerPage = 9;
$PayoutsPerPage = 9;
$InvestsPerPage = 9;
$ReferralsPerPage = 9;
$TransactionsPerPage = 9;
$CommissionsPerPage = 9;

?>
<div class="page-content">
    <div class="container-fluid">
        <div class="profile-foreground position-relative mx-n4 mt-n4">
            <div class="profile-wid-bg"></div>
        </div>

        <div class="pt-4 mb-4 mb-lg-3 pb-lg-4 profile-wrapper">
            <div class="row g-4">
                <div class="col-auto">
                    <div class="avatar-lg">
                        <img src="<?=$this->siteUrl().'/'.PUBLIC_PATH.'/'.UPLOADS_PATH?>/users/<?=e($data['user']["imagelocation"])?>" alt="user-img" class="img-thumbnail rounded-circle" />
                    </div>
                </div>

                <!--end col-->
                <div class="col">
                    <div class="p-2">
                        <h3 class="text-white mb-1"><?=e($data['user']["firstname"])?> <?=e($data['user']["lastname"])?></h3>

                        <p class="text-white text-opacity-75"><?=e($data['user']["email"])?></p>

                        <div class="hstack text-white-50 gap-1">
                            <div class="me-2">
                                <i class="ri-map-pin-user-line me-1 text-white text-opacity-75 fs-16 align-middle"></i>
                                <?=e($data['user']["country"])?>
                            </div>

                            <span class="badge badge-label <?= (e($data['user']['status']) === "1") ? 'bg-success' : 'bg-danger' ?>">
                                <i class="mdi mdi-circle-medium"></i> <?= (e($data['user']['status']) === "1") ? 'Active' : 'Blocked' ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <!--end row-->
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div>
                    <div class="d-flex profile-wrapper mb-4 mt-4">
                        <!-- Nav tabs -->
                        <ul class="flex-grow-1"></ul>

                        <div class="flex-shrink-0">
                            <a href="<?=$this->siteUrl()?>/admin/users" class="btn btn-soft-dark waves-effect waves-light material-shadow-none"><i class="ri-arrow-go-back-fill align-bottom"></i> Go Back</a>

                            <a href="<?=$this->siteUrl()?>/admin/users/edit-profile/<?=e($data['user']["userid"])?>" class="btn btn-primary"><i class="ri-edit-box-line align-bottom"></i> Edit Profile</a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xxl-3">
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex align-items-center">
                                        <h5 class="card-title flex-grow-1 mb-0">Profile Progress</h5>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <?php
                                        // Assuming $data['user'] contains user data
                                        $user = $data['user'];

                                        // Calculate completion percentage
                                        $totalFields = 4; // Assuming there are 4 fields to be filled
                                        $completedFields = 0;

                                        // Check each field and count completed fields
                                        if (!empty($user["address_1"])) $completedFields++;
                                        if (!empty($user["country"])) $completedFields++;
                                        if (!empty($user["city"])) $completedFields++;
                                        if (!empty($user["state"])) $completedFields++;

                                        // Calculate completion percentage
                                        $completionPercentage = ceil(min(100, ($completedFields / $totalFields) * 100));
                                    ?>

                                    <div class="progress animated-progress custom-progress progress-label">
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: <?= $completionPercentage ?>%" aria-valuenow="<?= $completionPercentage ?>" aria-valuemin="0" aria-valuemax="100">
                                            <div class="label"><?= $completionPercentage ?>%</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <input id="referralURL" type="text" value="<?=$this->siteUrl()?>/register/?ref=<?=e($data['user']['userid'])?>" style="display: none;">
                                    <a onclick="copyToClipboard(document.getElementById('referralURL'))" class="btn w-sm btn-soft-success w-100"><i class="ri-links-line align-bottom"></i> Copy Referral Link</a>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex align-items-center">
                                        <h5 class="card-title flex-grow-1 mb-0">Account Information</h5>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-borderless mb-0">
                                            <tbody>
                                                <tr>
                                                    <th class="ps-0" scope="row">Full Name :</th>
                                                    <td class="text-muted"><?=e($data['user']["firstname"])?> <?=e($data['user']["lastname"])?></td>
                                                </tr>

                                                <tr>
                                                    <th class="ps-0" scope="row">Mobile :</th>
                                                    <td class="text-muted"><?=e($data['user']["phone"])?></td>
                                                </tr>

                                                <tr>
                                                    <th class="ps-0" scope="row">E-mail :</th>
                                                    <td class="text-muted"><?=e($data['user']["email"])?></td>
                                                </tr>

                                                <tr>
                                                    <th class="ps-0" scope="row">Country :</th>
                                                    <td class="text-muted"><?=e($data['user']["country"])?>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th class="ps-0" scope="row">Referred By</th>
                                                    <td class="text-muted">
                                                        <span class="badge badge-label bg-primary">
                                                            <i class="mdi mdi-circle-medium"></i> 
                                                            <?php if (!empty($data['referrer']["userid"])): ?>
                                                                <a href="<?= $this->siteUrl() ?>/admin/users/view-profile/<?= e($data['referrer']["userid"]) ?>"> 
                                                                    <?= !empty($data["referrer"]["firstname"]) || !empty($data["referrer"]["lastname"]) ? e($data["referrer"]["firstname"]) . ' ' . e($data["referrer"]["lastname"]) : "None" ?>
                                                                </a>
                                                            <?php else: ?>
                                                                None
                                                            <?php endif; ?>
                                                        </span>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th class="ps-0" scope="row">Registration Date</th>
                                                    <td class="text-muted"><?= date('d M Y h:i A', strtotime($data['user']['registration_date'])) ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div><!-- end card body -->
                            </div><!-- end card -->

                            <?php if (!empty($data["address-proof-count"]) || !empty($data["identity-proof-count"])) : ?>
                                <div class="card">
                                    <div class="card-header">
                                        <div class="d-flex align-items-center">
                                            <h5 class="card-title flex-grow-1 mb-0">KYC Data</h5>
                                        </div>
                                    </div>

                                    <div class="card-body">
                                        <div class="d-flex flex-wrap gap-2">
                                            <div>
                                                <a href="<?=$this->siteUrl()?>/admin/users/address-proof/<?=e($data['user']["userid"])?>" class="btn btn-primary waves-effect waves-light w-sm">
                                                    <i class="ri-ancient-pavilion-fill align-bottom me-1"></i> Address Proof
                                                </a>
                                            </div>

                                            <div>
                                                <a href="<?=$this->siteUrl()?>/admin/users/identity-proof/<?=e($data['user']["userid"])?>" class="btn btn-primary waves-effect waves-light w-sm">
                                                    <i class="ri-user-location-fill align-bottom me-1"></i> Identity Proof
                                                </a>
                                            </div>
                                        </div>
                                    </div><!-- end card body -->
                                </div><!-- end card -->
                            <?php endif; ?>

                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex align-items-center">
                                        <h5 class="card-title flex-grow-1 mb-0">Action</h5>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div class="d-flex flex-wrap gap-2">
                                        <div>
                                            <button data-bs-toggle="modal" data-bs-target="#fundsModal" class="btn btn-primary waves-effect waves-light w-sm">
                                                <i class="ri-refund-2-fill align-bottom me-1"></i> Manage Funds
                                            </button>
                                        </div>

                                        <div>
                                            <button data-bs-toggle="modal" data-bs-target="#emailModal" class="btn btn-primary waves-effect waves-light w-sm">
                                                <i class="ri-question-answer-fill align-bottom me-1"></i> Send Email
                                            </button>
                                        </div>

                                        <div>
                                            <button data-bs-toggle="modal" data-bs-target="#passwordModal" class="btn btn-primary waves-effect waves-light w-sm">
                                                <i class="ri-lock-unlock-fill align-bottom me-1"></i> Reset User Password
                                            </button>
                                        </div>

                                        <div>
                                            <form id="user-login-form">
                                                <input type="hidden" id="userEmail" name="email" value="<?=e($data['user']['email'])?>">
                                                <?=$this->token()?>
                                                <button id="UserLoginBtn" class="btn btn-primary waves-effect waves-light w-sm">
                                                    <i class="ri-account-pin-box-fill align-bottom me-1"></i> Login As User
                                                </button>
                                            </form>
                                        </div>

                                        <div>
                                            <?php if(e($data['user']['status']) === "1"): ?>
                                                <button data-bs-toggle="modal" data-bs-target="#blockModal" class="btn btn-primary waves-effect waves-light w-sm">
                                                    <i class="ri-close-circle-fill align-bottom me-1"></i> Block Account
                                                </button>
                                            <?php elseif(e($data['user']['status']) === "2"): ?> 
                                                <button data-bs-toggle="modal" data-bs-target="#activateModal" class="btn btn-primary waves-effect waves-light w-sm">
                                                    <i class="ri-checkbox-circle-fill align-bottom me-1"></i> Activate Account
                                                </button>
                                            <?php endif; ?>
                                        </div>

                                        <div>
                                            <button data-bs-toggle="modal" data-bs-target="#deleteModal" class="btn btn-primary waves-effect waves-light w-sm">
                                                <i class="ri-delete-bin-2-fill align-bottom me-1"></i> Delete Account
                                            </button>
                                        </div>
                                    </div>
                                </div><!-- end card body -->
                            </div><!-- end card -->

                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex align-items-center">
                                        <h5 class="card-title flex-grow-1 mb-0">Recently Referred</h5>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <?php if (empty($data['referrals'])): ?>
                                        <div class="text-center">
                                            <div class="empty-notification-elem">
                                                <div class="w-25 w-sm-50 pt-3 mx-auto">
                                                    <img src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/admin/images/svg/bell.svg" class="img-fluid" alt="user-pic" />
                                                </div>

                                                <div class="text-center pb-5 mt-2">
                                                    <h6 class="fs-18 fw-semibold lh-base">This user has no referrals.</h6>
                                                </div>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div>
                                            <?php foreach ($data['referrals'] as $referral): ?>
                                                <div class="d-flex align-items-center py-3">
                                                    <div class="avatar-xs flex-shrink-0 me-3">
                                                        <img src="<?=$this->siteUrl().'/'.PUBLIC_PATH.'/'.UPLOADS_PATH?>/users/<?=e($referral["imagelocation"])?>" alt="" class="img-fluid rounded-circle material-shadow" />
                                                    </div>

                                                    <div class="flex-grow-1">
                                                        <div>
                                                            <h5 class="fs-14 mb-1"><?=e($referral["firstname"])?> <?=e($referral["lastname"])?></h5>
                                                            <p class="fs-13 text-muted mb-0">Joined - <?= date('d M Y h:i A', strtotime($referral['registration_date'])) ?></p>
                                                        </div>
                                                    </div>

                                                    <div class="flex-shrink-0 ms-2">
                                                        <a href="<?=$this->siteUrl()?>/admin/users/view-profile/<?=e($referral["userid"])?>" class="btn btn-sm btn-outline-success material-shadow-none"><i class="ri-user-add-line align-middle"></i></a>
                                                    </div>
                                                </div>
                                            <?php endforeach;?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <!-- end card body -->
                            </div>
                            <!--end card-->
                        </div>
                        <!--end col-->

                        <div class="col-xxl-9">
                            <div class="card crm-widget">
                                <div class="card-body p-0">
                                    <div class="row row-cols-md-3 row-cols-1">
                                        <div class="col col-lg">
                                            <div class="mt-3 mt-md-0 py-4 px-3">
                                                <h5 class="text-muted text-uppercase fs-13">
                                                    Balance 
                                                    <i class="ri-arrow-up-circle-line text-success fs-18 float-end align-middle"></i>
                                                </h5>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <i class="ri-exchange-dollar-line display-6 text-muted"></i>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h2 class="mb-0">$<?= number_format((float) e($data['user']["interest_wallet"]), 2) ?></h2>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- end col -->

                                        <div class="col col-lg">
                                            <div class="mt-3 mt-md-0 py-4 px-3">
                                                <h5 class="text-muted text-uppercase fs-13">
                                                    Total Deposit 
                                                    <i class="ri-arrow-down-circle-line text-danger fs-18 float-end align-middle"></i>
                                                </h5>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <i class="ri-wallet-line display-6 text-muted"></i>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h2 class="mb-0">$<?= number_format((float) e($data["deposits"]), 2) ?></h2>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- end col -->

                                        <div class="col col-lg">
                                            <div class="mt-3 mt-lg-0 py-4 px-3">
                                                <h5 class="text-muted text-uppercase fs-13">
                                                    Total Withdrawal 
                                                    <i class="ri-arrow-up-circle-line text-success fs-18 float-end align-middle"></i>
                                                </h5>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <i class="ri-exchange-line display-6 text-muted"></i>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h2 class="mb-0">$<?= number_format((float) e($data["payouts"]), 2) ?></h2>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- end col -->

                                        <div class="col col-lg">
                                            <div class="mt-3 mt-lg-0 py-4 px-3">
                                                <h5 class="text-muted text-uppercase fs-13">
                                                    Total Investment 
                                                    <i class="ri-arrow-down-circle-line text-danger fs-18 float-end align-middle"></i>
                                                </h5>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <i class="ri-stock-line display-6 text-muted"></i>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h2 class="mb-0">$<?= number_format((float) e($data["investments"]), 2) ?></h2>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- end col -->
                                    </div><!-- end row -->
                                </div><!-- end card body -->
                            </div><!-- end card -->

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="flex-shrink-0">
                                                <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                                                    <li class="nav-item">
                                                        <a class="nav-link active" data-bs-toggle="tab" href="#deposits" role="tab">
                                                            Deposits
                                                        </a>
                                                    </li>

                                                    <li class="nav-item">
                                                        <a class="nav-link" data-bs-toggle="tab" href="#withdrawals" role="tab">
                                                            Withdrawals
                                                        </a>
                                                    </li>

                                                    <li class="nav-item">
                                                        <a class="nav-link" data-bs-toggle="tab" href="#investments" role="tab">
                                                            Investments
                                                        </a>
                                                    </li>

                                                    <li class="nav-item">
                                                        <a class="nav-link" data-bs-toggle="tab" href="#referrals" role="tab">
                                                            Referrals
                                                        </a>
                                                    </li>

                                                    <li class="nav-item">
                                                        <a class="nav-link" data-bs-toggle="tab" href="#commissions" role="tab">
                                                            Commissions
                                                        </a>
                                                    </li>

                                                    <li class="nav-item">
                                                        <a class="nav-link" data-bs-toggle="tab" href="#transactions" role="tab">
                                                            Transactions
                                                        </a>
                                                    </li>

                                                    <li class="nav-item">
                                                        <a class="nav-link" data-bs-toggle="tab" href="#loans" role="tab">
                                                            Loans
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-content text-muted">
                                        <div class="tab-pane active" id="deposits" role="tabpanel">
                                            <div class="card crm-widget">
                                                <div class="card-body p-0">
                                                    <div class="row row-cols-md-3 row-cols-1">
                                                        <div class="col col-lg">
                                                            <div class="mt-3 mt-md-0 py-4 px-3">
                                                                <h5 class="text-muted text-uppercase fs-13">
                                                                    Pending Deposits
                                                                    <i class="ri-arrow-up-circle-line text-success fs-18 float-end align-middle"></i>
                                                                </h5>
                                                                <div class="d-flex align-items-center">
                                                                    <div class="flex-shrink-0">
                                                                        <i class="ri-wallet-line display-6 text-muted"></i>
                                                                    </div>
                                                                    <div class="flex-grow-1 ms-3">
                                                                        <h2 class="mb-0">$<?= number_format((float) e($data["deposits-pending"]), 2) ?></h2>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div><!-- end col -->

                                                        <div class="col col-lg">
                                                            <div class="mt-3 mt-md-0 py-4 px-3">
                                                                <h5 class="text-muted text-uppercase fs-13">
                                                                    Completed Deposits
                                                                    <i class="ri-arrow-up-circle-line text-success fs-18 float-end align-middle"></i>
                                                                </h5>
                                                                <div class="d-flex align-items-center">
                                                                    <div class="flex-shrink-0">
                                                                        <i class="ri-wallet-line display-6 text-muted"></i>
                                                                    </div>
                                                                    <div class="flex-grow-1 ms-3">
                                                                        <h2 class="mb-0">$<?= number_format((float) e($data["deposits-completed"]), 2) ?></h2>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div><!-- end col -->

                                                        <div class="col col-lg">
                                                            <div class="mt-3 mt-md-0 py-4 px-3">
                                                                <h5 class="text-muted text-uppercase fs-13">
                                                                    Rejected Deposits
                                                                    <i class="ri-arrow-up-circle-line text-success fs-18 float-end align-middle"></i>
                                                                </h5>
                                                                <div class="d-flex align-items-center">
                                                                    <div class="flex-shrink-0">
                                                                        <i class="ri-wallet-line display-6 text-muted"></i>
                                                                    </div>
                                                                    <div class="flex-grow-1 ms-3">
                                                                        <h2 class="mb-0">$<?= number_format((float) e($data["deposits-rejected"]), 2) ?></h2>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div><!-- end col -->

                                                        <div class="col col-lg">
                                                            <div class="mt-3 mt-md-0 py-4 px-3">
                                                                <h5 class="text-muted text-uppercase fs-13">
                                                                    Initiated Deposits
                                                                    <i class="ri-arrow-up-circle-line text-success fs-18 float-end align-middle"></i>
                                                                </h5>
                                                                <div class="d-flex align-items-center">
                                                                    <div class="flex-shrink-0">
                                                                        <i class="ri-wallet-line display-6 text-muted"></i>
                                                                    </div>
                                                                    <div class="flex-grow-1 ms-3">
                                                                        <h2 class="mb-0">$<?= number_format((float) e($data["deposits-initiated"]), 2) ?></h2>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div><!-- end col -->
                                                    </div><!-- end row -->
                                                </div><!-- end card body -->
                                            </div><!-- end card -->

                                            <?php if (empty($data['get-user-deposits'])): ?>
                                                <div class="card">
                                                    <div class="card-header">
                                                        <div class="d-flex align-items-center">
                                                            <h5 class="card-title flex-grow-1 mb-0">Deposit History</h5>
                                                            <div class="flex-shrink-0">
                                                                <button data-bs-toggle="modal" data-bs-target="#addDepositModal" class="btn btn-primary btn-sm"><i class="ri-add-fill align-middle me-1"></i> Add Deposit</button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="card-body">
                                                        <div class="text-center">
                                                            <div class="empty-notification-elem">
                                                                <div class="w-25 w-sm-50 pt-3 mx-auto">
                                                                    <img src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/admin/images/svg/bell.svg" class="img-fluid" alt="user-pic" />
                                                                </div>

                                                                <div class="text-center pb-5 mt-2">
                                                                    <h6 class="fs-18 fw-semibold lh-base">This user has no deposits.</h6>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <div class="card">
                                                    <div class="card-header">
                                                        <div class="d-flex align-items-center">
                                                            <h5 class="card-title flex-grow-1 mb-0">Deposit History</h5>
                                                            <div class="flex-shrink-0">
                                                                <button data-bs-toggle="modal" data-bs-target="#addDepositModal" class="btn btn-primary btn-sm"><i class="ri-add-fill align-middle me-1"></i> Add Deposit</button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="card-body">
                                                        <div class="table-card">
                                                            <table class="table table-borderless align-middle" id="loadMoreDepositsContainer">
                                                                <tbody class="deposits">
                                                                    <?php foreach ($data['get-user-deposits'] as $deposit): ?>
                                                                    <?php foreach($data['gateways'] as $gateway){
                                                                        if($gateway['method_code'] === $deposit['method_code']){ ?>
                                                                        <tr>
                                                                            <td>
                                                                                <div class="d-flex align-items-center">
                                                                                    <img src="<?=$this->siteUrl().'/'.PUBLIC_PATH.'/'.UPLOADS_PATH?>/deposit/<?=e($gateway['image'])?>" alt="" class="avatar-xs rounded-circle" />
                                                                                    <div class="ms-2">

                                                                                        <a href="<?=$this->siteUrl()?>/admin/deposits/view-deposit/<?=e($deposit['depositId'])?>">
                                                                                            <h6 class="fs-15 mb-1"><?=e($gateway['name'])?></h6>
                                                                                        </a>

                                                                                        <p class="mb-0 text-muted"><?= date('d-m-Y h:i A', strtotime($deposit['created_at'])) ?></p>

                                                                                        <?php if ($deposit['status'] == 0): ?>
                                                                                            <p class="mb-0 text-muted">
                                                                                                Initiated
                                                                                            </p>
                                                                                        <?php elseif ($deposit['status'] == 1): ?>
                                                                                            <p class="mb-0 text-success">
                                                                                                Completed
                                                                                            </p>
                                                                                        <?php elseif ($deposit['status'] == 2): ?>
                                                                                            <p class="mb-0 text-warning">
                                                                                                Pending
                                                                                            </p>
                                                                                        <?php elseif ($deposit['status'] == 3): ?>
                                                                                            <p class="mb-0 text-danger">
                                                                                                Rejected
                                                                                            </p>
                                                                                        <?php endif ?>
                                                                                    </div>
                                                                                </div>
                                                                            </td>

                                                                            <td>$<?= number_format((float) e($deposit['amount']), 2) ?></td>

                                                                            <td>
                                                                                <div class="dropdown d-inline-block">
                                                                                    <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                                        <i class="ri-more-fill align-middle"></i>
                                                                                    </button>
                                                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                                                        <li>
                                                                                            <a href="<?=$this->siteUrl()?>/admin/deposits/view-deposit/<?=e($deposit['depositId'])?>" class="dropdown-item"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View</a>
                                                                                        </li>

                                                                                        <li>
                                                                                            <button data-bs-toggle="modal" data-bs-target="#deleteDepositModal" data-deposit="<?= e($deposit['depositId']) ?>" class="dropdown-item"> <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete </button>
                                                                                        </li>
                                                                                    </ul>
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
                                                </div>

                                                <!-- end col -->
                                                <?php if (count($data['get-user-deposits']) >= $DepositsPerPage): ?>
                                                <div class="text-center mb-3 mt-3">
                                                    <button class="btn btn-primary waves-effect waves-light loadMoreDeposits" data-id="<?=e($data['user']['userid'])?>" data-page="2">Load More </button>
                                                </div>
                                                <?php endif; ?>

                                                <div class="row d-none" id="DepositsLastpage">
                                                    <div class="offset-lg-3 col-lg-6 col-md-12 col-12 text-center mt-3"><p>You’ve reached the end of the list</p></div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="tab-content text-muted">
                                        <div class="tab-pane" id="withdrawals" role="tabpanel">
                                            <div class="card crm-widget">
                                                <div class="card-body p-0">
                                                    <div class="row row-cols-md-3 row-cols-1">
                                                        <div class="col col-lg">
                                                            <div class="mt-3 mt-md-0 py-4 px-3">
                                                                <h5 class="text-muted text-uppercase fs-13">
                                                                    Completed Withdrawals
                                                                    <i class="ri-arrow-up-circle-line text-success fs-18 float-end align-middle"></i>
                                                                </h5>
                                                                <div class="d-flex align-items-center">
                                                                    <div class="flex-shrink-0">
                                                                        <i class="ri-exchange-line display-6 text-muted"></i>
                                                                    </div>
                                                                    <div class="flex-grow-1 ms-3">
                                                                        <h2 class="mb-0">$<?= number_format((float) e($data["payouts-completed"]), 2) ?></h2>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div><!-- end col -->
                                                    </div><!-- end row -->
                                                </div><!-- end card body -->
                                            </div><!-- end card -->

                                            <?php if (empty($data['get-user-withdrawals'])): ?>
                                                <div class="card">
                                                    <div class="card-header">
                                                        <div class="d-flex align-items-center">
                                                            <h5 class="card-title flex-grow-1 mb-0">Withdrawal History</h5>
                                                            <div class="flex-shrink-0">
                                                                <button data-bs-toggle="modal" data-bs-target="#addWithdrawalModal" class="btn btn-primary btn-sm"><i class="ri-add-fill align-middle me-1"></i> Add Withdrawal</button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="card-body">
                                                        <div class="text-center">
                                                            <div class="empty-notification-elem">
                                                                <div class="w-25 w-sm-50 pt-3 mx-auto">
                                                                    <img src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/admin/images/svg/bell.svg" class="img-fluid" alt="user-pic" />
                                                                </div>

                                                                <div class="text-center pb-5 mt-2">
                                                                    <h6 class="fs-18 fw-semibold lh-base">This user has no withdrawals.</h6>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <div class="card">
                                                    <div class="card-header">
                                                        <div class="d-flex align-items-center">
                                                            <h5 class="card-title flex-grow-1 mb-0">Withdrawal History</h5>
                                                            <div class="flex-shrink-0">
                                                                <button data-bs-toggle="modal" data-bs-target="#addWithdrawalModal" class="btn btn-primary btn-sm"><i class="ri-add-fill align-middle me-1"></i> Add Withdrawal</button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="card-body">
                                                        <div class="table-card">
                                                            <table class="table table-borderless align-middle" id="loadMorePayoutsContainer">
                                                                <tbody class="payouts">
                                                                    <?php foreach ($data['get-user-withdrawals'] as $withdrawal): ?>
                                                                    <?php foreach($data['withdrawal-gateways'] as $gateway){
                                                                        if($gateway['withdraw_code'] === $withdrawal['withdraw_code']){ ?>
                                                                        <tr>
                                                                            <td>
                                                                                <div class="d-flex align-items-center">
                                                                                    <img src="<?=$this->siteUrl().'/'.PUBLIC_PATH.'/'.UPLOADS_PATH?>/withdrawal/<?=e($gateway['image'])?>" alt="" class="avatar-xs rounded-circle" />

                                                                                    <div class="ms-2">
                                                                                        <a href="<?=$this->siteUrl()?>/admin/withdrawals/view-withdrawal/<?=e($withdrawal['withdrawId'])?>"><h6 class="fs-15 mb-1"><?=e($gateway['name'])?></h6></a>
                                                                                        <p class="mb-0 text-muted"><?= date('d-m-Y h:i A', strtotime($withdrawal['created_at'])) ?></p>

                                                                                        <?php if ($withdrawal['status'] == 0): ?>
                                                                                            <p class="mb-0 text-muted">
                                                                                                Initiated
                                                                                            </p>
                                                                                        <?php elseif ($withdrawal['status'] == 1): ?>
                                                                                            <p class="mb-0 text-success">
                                                                                                Completed
                                                                                            </p>
                                                                                        <?php elseif ($withdrawal['status'] == 2): ?>
                                                                                            <p class="mb-0 text-warning">
                                                                                                Pending
                                                                                            </p>
                                                                                        <?php elseif ($withdrawal['status'] == 3): ?>
                                                                                            <p class="mb-0 text-danger">
                                                                                                Rejected
                                                                                            </p>
                                                                                        <?php endif ?>
                                                                                    </div>
                                                                                </div>
                                                                            </td>

                                                                            <td>$<?= number_format((float) e($withdrawal['amount']), 2) ?></td>

                                                                            <td>
                                                                                <div class="dropdown d-inline-block">
                                                                                    <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                                        <i class="ri-more-fill align-middle"></i>
                                                                                    </button>
                                                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                                                        <li>
                                                                                            <a href="<?=$this->siteUrl()?>/admin/withdrawals/view-withdrawal/<?=e($withdrawal['withdrawId'])?>" class="dropdown-item"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View</a>
                                                                                        </li>

                                                                                        <li>
                                                                                            <button data-bs-toggle="modal" data-bs-target="#deleteWithdrawalModal" data-withdrawal="<?= e($withdrawal['withdrawId']) ?>" class="dropdown-item"> <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete </button>
                                                                                        </li>
                                                                                    </ul>
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
                                                </div>

                                                <!-- end col -->
                                                <?php if (count($data['get-user-withdrawals']) >= $PayoutsPerPage): ?>
                                                <div class="text-center mb-3 mt-3">
                                                    <button class="btn btn-primary waves-effect waves-light loadMorePayouts" data-id="<?=e($data['user']['userid'])?>" data-page="2">Load More </button>
                                                </div>
                                                <?php endif; ?>

                                                <div class="row d-none" id="PayoutsLastpage">
                                                    <div class="offset-lg-3 col-lg-6 col-md-12 col-12 text-center mt-3"><p>You’ve reached the end of the list</p></div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="tab-content text-muted">
                                        <div class="tab-pane" id="investments" role="tabpanel">
                                            <div class="card crm-widget">
                                                <div class="card-body p-0">
                                                    <div class="row row-cols-md-3 row-cols-1">
                                                        <div class="col col-lg">
                                                            <div class="mt-3 mt-md-0 py-4 px-3">
                                                                <h5 class="text-muted text-uppercase fs-13">
                                                                    Pending Investments
                                                                    <i class="ri-arrow-up-circle-line text-success fs-18 float-end align-middle"></i>
                                                                </h5>
                                                                <div class="d-flex align-items-center">
                                                                    <div class="flex-shrink-0">
                                                                        <i class="ri-stock-line display-6 text-muted"></i>
                                                                    </div>
                                                                    <div class="flex-grow-1 ms-3">
                                                                        <h2 class="mb-0">$<?= number_format((float) e($data["investments-pending"]), 2) ?></h2>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div><!-- end col -->

                                                        <div class="col col-lg">
                                                            <div class="mt-3 mt-md-0 py-4 px-3">
                                                                <h5 class="text-muted text-uppercase fs-13">
                                                                    Completed Investments
                                                                    <i class="ri-arrow-up-circle-line text-success fs-18 float-end align-middle"></i>
                                                                </h5>
                                                                <div class="d-flex align-items-center">
                                                                    <div class="flex-shrink-0">
                                                                        <i class="ri-stock-line display-6 text-muted"></i>
                                                                    </div>
                                                                    <div class="flex-grow-1 ms-3">
                                                                        <h2 class="mb-0">$<?= number_format((float) e($data["investments-completed"]), 2) ?></h2>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div><!-- end col -->

                                                        <div class="col col-lg">
                                                            <div class="mt-3 mt-md-0 py-4 px-3">
                                                                <h5 class="text-muted text-uppercase fs-13">
                                                                    Cancelled Investments
                                                                    <i class="ri-arrow-up-circle-line text-success fs-18 float-end align-middle"></i>
                                                                </h5>
                                                                <div class="d-flex align-items-center">
                                                                    <div class="flex-shrink-0">
                                                                        <i class="ri-stock-line display-6 text-muted"></i>
                                                                    </div>
                                                                    <div class="flex-grow-1 ms-3">
                                                                        <h2 class="mb-0">$<?= number_format((float) e($data["investments-cancelled"]), 2) ?></h2>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div><!-- end col -->

                                                        <div class="col col-lg">
                                                            <div class="mt-3 mt-md-0 py-4 px-3">
                                                                <h5 class="text-muted text-uppercase fs-13">
                                                                    Initiated Investments
                                                                    <i class="ri-arrow-up-circle-line text-success fs-18 float-end align-middle"></i>
                                                                </h5>
                                                                <div class="d-flex align-items-center">
                                                                    <div class="flex-shrink-0">
                                                                        <i class="ri-stock-line display-6 text-muted"></i>
                                                                    </div>
                                                                    <div class="flex-grow-1 ms-3">
                                                                        <h2 class="mb-0">$<?= number_format((float) e($data["investments-initiated"]), 2) ?></h2>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div><!-- end col -->
                                                    </div><!-- end row -->
                                                </div><!-- end card body -->
                                            </div><!-- end card -->

                                            <?php if (empty($data['get-user-investments'])): ?>
                                                <div class="card">
                                                    <div class="card-header">
                                                        <div class="d-flex align-items-center">
                                                            <h5 class="card-title flex-grow-1 mb-0">Investment History</h5>
                                                            <div class="flex-shrink-0">
                                                                <button data-bs-toggle="modal" data-bs-target="#addInvestmentModal" class="btn btn-primary btn-sm"><i class="ri-add-fill align-middle me-1"></i> Add Investment</button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="card-body">
                                                        <div class="text-center">
                                                            <div class="empty-notification-elem">
                                                                <div class="w-25 w-sm-50 pt-3 mx-auto">
                                                                    <img src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/admin/images/svg/bell.svg" class="img-fluid" alt="user-pic" />
                                                                </div>

                                                                <div class="text-center pb-5 mt-2">
                                                                    <h6 class="fs-18 fw-semibold lh-base">This user has no investments.</h6>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <div class="card">
                                                    <div class="card-header">
                                                        <div class="d-flex align-items-center">
                                                            <h5 class="card-title flex-grow-1 mb-0">Investment History</h5>
                                                            <div class="flex-shrink-0">
                                                                <button data-bs-toggle="modal" data-bs-target="#addInvestmentModal" class="btn btn-primary btn-sm"><i class="ri-add-fill align-middle me-1"></i> Add Investment</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="table-card">
                                                            <table class="table table-borderless align-middle" id="loadMoreInvestsContainer">
                                                                <tbody class="invests">
                                                                    <?php foreach ($data['get-user-investments'] as $investment): ?>
                                                                        <tr>
                                                                            <td>
                                                                                <div class="d-flex align-items-center">
                                                                                    <img src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/frontend/templates/images/investment.png" alt="" class="avatar-xs rounded-circle" />
                                                                                    <div class="ms-2">
                                                                                        <a href="<?=$this->siteUrl()?>/admin/investments/view-investment/<?=e($investment['investId'])?>">
                                                                                            <h6 class="fs-15 mb-1">
                                                                                                <?php foreach($data['plans'] as $plan){
                                                                                                        if($plan['planId'] === $investment['planId']){ ?>
                                                                                                    <?=e($plan["name"])?>
                                                                                                <?php
                                                                                                    break;
                                                                                                    }
                                                                                                } ?>
                                                                                            </h6>
                                                                                        </a>
                                                                                        <p class="mb-0 text-muted"><?= date('d-m-Y h:i A', strtotime($investment['initiated_at'])) ?></p>

                                                                                        <?php if ($investment['status'] == 1): ?>
                                                                                            <p class="mb-0 text-success">
                                                                                                Completed
                                                                                            </p>
                                                                                        <?php elseif ($investment['status'] == 2): ?>
                                                                                            <p class="mb-0 text-warning">
                                                                                                Running
                                                                                            </p>
                                                                                        <?php elseif ($investment['status'] == 3): ?>
                                                                                            <p class="mb-0 text-muted">
                                                                                                Initiated
                                                                                            </p>
                                                                                        <?php elseif ($investment['status'] == 4): ?>
                                                                                            <p class="mb-0 text-danger">
                                                                                                Cancelled
                                                                                            </p>
                                                                                        <?php endif ?>
                                                                                    </div>
                                                                                </div>
                                                                            </td>

                                                                            <td>$<?= number_format((float) e($investment['amount']), 2) ?></td>

                                                                            <td>
                                                                                <div class="dropdown d-inline-block">
                                                                                    <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                                        <i class="ri-more-fill align-middle"></i>
                                                                                    </button>

                                                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                                                        <li>
                                                                                            <a href="<?=$this->siteUrl()?>/admin/investments/view-investment/<?=e($investment['investId'])?>" class="dropdown-item"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View</a>
                                                                                        </li>

                                                                                        <li>
                                                                                            <button data-bs-toggle="modal" data-bs-target="#deleteInvestmentModal" data-investment="<?= e($investment['investId']) ?>" class="dropdown-item"> <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete </button>
                                                                                        </li>
                                                                                    </ul>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    <?php endforeach ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- end col -->
                                                <?php if (count($data['get-user-investments']) >= $InvestsPerPage): ?>
                                                <div class="text-center mb-3 mt-3">
                                                    <button class="btn btn-primary waves-effect waves-light loadMoreInvests" data-id="<?=e($data['user']['userid'])?>" data-page="2">Load More </button>
                                                </div>
                                                <?php endif; ?>

                                                <div class="row d-none" id="InvestsLastpage">
                                                    <div class="offset-lg-3 col-lg-6 col-md-12 col-12 text-center mt-3"><p>You’ve reached the end of the list</p></div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="tab-content text-muted">
                                        <div class="tab-pane" id="referrals" role="tabpanel">
                                            <?php if (empty($data['referred'])): ?>
                                                <div class="card">
                                                    <div class="card-header">
                                                        <div class="d-flex align-items-center">
                                                            <h5 class="card-title flex-grow-1 mb-0">Referred Users</h5>
                                                        </div>
                                                    </div>

                                                    <div class="card-body">
                                                        <div class="text-center">
                                                            <div class="empty-notification-elem">
                                                                <div class="w-25 w-sm-50 pt-3 mx-auto">
                                                                    <img src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/admin/images/svg/bell.svg" class="img-fluid" alt="user-pic" />
                                                                </div>

                                                                <div class="text-center pb-5 mt-2">
                                                                    <h6 class="fs-18 fw-semibold lh-base">This user has no referrals.</h6>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <div class="card">
                                                    <div class="card-header">
                                                        <div class="d-flex align-items-center">
                                                            <h5 class="card-title flex-grow-1 mb-0">Referred Users</h5>
                                                        </div>
                                                    </div>

                                                    <div class="card-body">
                                                        <div class="table-card">
                                                            <table class="table table-borderless align-middle" id="loadMoreReferralsContainer">
                                                                <tbody class="referrals">
                                                                    <?php foreach ($data['referred'] as $user): ?>
                                                                        <tr>
                                                                            <td>
                                                                                <div class="d-flex align-items-center">
                                                                                    <img src="<?=$this->siteUrl().'/'.PUBLIC_PATH.'/'.UPLOADS_PATH?>/users/<?=e($user["imagelocation"])?>" alt="" class="avatar-xs rounded-circle" />
                                                                                    <div class="ms-2">
                                                                                        <a href="<?=$this->siteUrl()?>/admin/users/view-profile/<?=e($user["userid"])?>"><h6 class="fs-15 mb-1"><?=e($user["firstname"])?> <?=e($user["lastname"])?></h6></a>
                                                                                        <p class="mb-0 text-muted">Joined - <?= date('d M Y h:i A', strtotime($user['registration_date'])) ?></p>
                                                                                    </div>
                                                                                </div>
                                                                            </td>

                                                                            <td>
                                                                                <?php if ($user['status'] == 1): ?>
                                                                                    <p class="mb-0 text-success">Active</p>
                                                                                <?php elseif ($user['status'] == 2): ?>
                                                                                    <p class="mb-0 text-danger">Blocked</p>
                                                                                <?php endif ?>
                                                                            </td>

                                                                            <td>
                                                                                <div class="dropdown d-inline-block">
                                                                                    <a href="<?=$this->siteUrl()?>/admin/users/view-profile/<?=e($user["userid"])?>" class="btn btn-sm btn-outline-success material-shadow-none"><i class="ri-user-add-line align-middle"></i></a>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    <?php endforeach;?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- end col -->
                                                <?php if (count($data['referred']) >= $ReferralsPerPage): ?>
                                                <div class="text-center mb-3 mt-3">
                                                    <button class="btn btn-primary waves-effect waves-light loadMoreReferrals" data-id="<?=e($data['user']['userid'])?>" data-page="2">Load More </button>
                                                </div>
                                                <?php endif; ?>

                                                <div class="row d-none" id="ReferralsLastpage">
                                                    <div class="offset-lg-3 col-lg-6 col-md-12 col-12 text-center mt-3"><p>You’ve reached the end of the list</p></div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="tab-content text-muted">
                                        <div class="tab-pane" id="commissions" role="tabpanel">
                                            <div class="card crm-widget">
                                                <div class="card-body p-0">
                                                    <div class="row row-cols-md-3 row-cols-1">
                                                        <div class="col col-lg">
                                                            <div class="mt-3 mt-md-0 py-4 px-3">
                                                                <h5 class="text-muted text-uppercase fs-13">
                                                                    Commissions Earned 
                                                                    <i class="ri-arrow-up-circle-line text-success fs-18 float-end align-middle"></i>
                                                                </h5>
                                                                <div class="d-flex align-items-center">
                                                                    <div class="flex-shrink-0">
                                                                        <i class="ri-exchange-dollar-line display-6 text-muted"></i>
                                                                    </div>
                                                                    <div class="flex-grow-1 ms-3">
                                                                        <h2 class="mb-0">$<?= number_format((float) e($data["commissions"]), 2) ?></h2>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div><!-- end col -->

                                                        <div class="col col-lg">
                                                            <div class="mt-3 mt-md-0 py-4 px-3">
                                                                <h5 class="text-muted text-uppercase fs-13">
                                                                    Referrals
                                                                    <i class="ri-arrow-up-circle-line text-success fs-18 float-end align-middle"></i>
                                                                </h5>
                                                                <div class="d-flex align-items-center">
                                                                    <div class="flex-shrink-0">
                                                                        <i class="ri-parent-line display-6 text-muted"></i>
                                                                    </div>
                                                                    <div class="flex-grow-1 ms-3">
                                                                        <h2 class="mb-0"><?=e($data['count-referrals'])?></h2>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div><!-- end col -->

                                                        <div class="col col-lg">
                                                            <div class="mt-3 mt-md-0 py-4 px-3">
                                                                <h5 class="text-muted text-uppercase fs-13">
                                                                    Current Rank
                                                                    <i class="ri-arrow-up-circle-line text-success fs-18 float-end align-middle"></i>
                                                                </h5>
                                                                <div class="d-flex align-items-center">
                                                                    <div class="flex-shrink-0">
                                                                        <i class="ri-star-smile-line display-6 text-muted"></i>
                                                                    </div>
                                                                    <div class="flex-grow-1 ms-3">
                                                                        <h2 class="mb-0"><?= e(!empty($data['current-ranking']['name']) ? $data['current-ranking']['name'] : 'Newbie') ?></h2>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div><!-- end col -->
                                                    </div><!-- end row -->
                                                </div><!-- end card body -->
                                            </div><!-- end card -->

                                            <?php if (empty($data['get-commissions'])): ?>
                                                <div class="card">
                                                    <div class="card-header">
                                                        <div class="d-flex align-items-center">
                                                            <h5 class="card-title flex-grow-1 mb-0">Commissions</h5>
                                                        </div>
                                                    </div>

                                                    <div class="card-body">
                                                        <div class="text-center">
                                                            <div class="empty-notification-elem">
                                                                <div class="w-25 w-sm-50 pt-3 mx-auto">
                                                                    <img src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/admin/images/svg/bell.svg" class="img-fluid" alt="user-pic" />
                                                                </div>

                                                                <div class="text-center pb-5 mt-2">
                                                                    <h6 class="fs-18 fw-semibold lh-base">This user has earned no commissions.</h6>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <div class="card">
                                                    <div class="card-header">
                                                        <div class="d-flex align-items-center">
                                                            <h5 class="card-title flex-grow-1 mb-0">Commissions</h5>
                                                        </div>
                                                    </div>

                                                    <div class="card-body">
                                                        <div class="table-card">
                                                            <table class="table table-borderless align-middle" id="loadMoreCommissionsContainer">
                                                                <tbody class="commissions">
                                                                    <?php foreach ($data['get-commissions'] as $referral): ?>
                                                                    <?php foreach($data['users'] as $user){
                                                                        if($user['userid'] === $referral['from_id']){ ?>
                                                                        <tr>
                                                                            <td>
                                                                                <div class="d-flex align-items-center">
                                                                                    <img src="<?=$this->siteUrl().'/'.PUBLIC_PATH.'/'.UPLOADS_PATH?>/users/<?=e($user["imagelocation"])?>" alt="" class="avatar-xs rounded-circle" />
                                                                                    <div class="ms-2">
                                                                                        <a><h6 class="fs-15 mb-1">$<?=e($referral["commission_amount"])?> <?=e($referral["title"])?></h6></a>
                                                                                        <p class="mb-0 text-muted"><?= date('d M Y h:i A', strtotime($referral['created_at'])) ?></p>
                                                                                    </div>
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
                                                </div>

                                                <!-- end col -->
                                                <?php if (count($data['get-commissions']) >= $CommissionsPerPage): ?>
                                                <div class="text-center mb-3 mt-3">
                                                    <button class="btn btn-primary waves-effect waves-light loadMoreCommissions" data-id="<?=e($data['user']['userid'])?>" data-page="2">Load More </button>
                                                </div>
                                                <?php endif; ?>

                                                <div class="row d-none" id="CommissionsLastpage">
                                                    <div class="offset-lg-3 col-lg-6 col-md-12 col-12 text-center mt-3"><p>You’ve reached the end of the list</p></div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="tab-content text-muted">
                                        <div class="tab-pane" id="transactions" role="tabpanel">
                                            <?php if (empty($data['get-transactions'])): ?>
                                                <div class="card">
                                                    <div class="card-header">
                                                        <div class="d-flex align-items-center">
                                                            <h5 class="card-title flex-grow-1 mb-0">All Transactions</h5>
                                                        </div>
                                                    </div>

                                                    <div class="card-body">
                                                        <div class="text-center">
                                                            <div class="empty-notification-elem">
                                                                <div class="w-25 w-sm-50 pt-3 mx-auto">
                                                                    <img src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/admin/images/svg/bell.svg" class="img-fluid" alt="user-pic" />
                                                                </div>

                                                                <div class="text-center pb-5 mt-2">
                                                                    <h6 class="fs-18 fw-semibold lh-base">This user has no transactions.</h6>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <div class="card">
                                                    <div class="card-header">
                                                        <div class="d-flex align-items-center">
                                                            <h5 class="card-title flex-grow-1 mb-0">All Transactions</h5>
                                                        </div>
                                                    </div>

                                                    <div class="card-body">
                                                        <div class="table-card">
                                                            <table class="table table-borderless align-middle" id="loadMoreTransactionsContainer">
                                                                <tbody class="transactions">
                                                                    <?php foreach ($data['get-transactions'] as $transaction): ?>
                                                                        <tr>
                                                                            <td>
                                                                                <div class="d-flex align-items-center">

                                                                                    <?php if ($transaction['trx_type'] == "+"): ?>
                                                                                        <img src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/frontend/templates/images/cashout.png" alt="Transaction" class="avatar-xs rounded-circle">
                                                                                    <?php elseif ($transaction['trx_type'] == "-"): ?>
                                                                                        <img src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/frontend/templates/images/cashin.png" alt="Transaction" class="avatar-xs rounded-circle">
                                                                                    <?php endif ?>

                                                                                    <div class="ms-2">
                                                                                        <a>
                                                                                            <h6 class="fs-15 mb-1"><?=e($transaction['details'])?></h6>
                                                                                        </a>

                                                                                        <p class="mb-0 text-muted"><?= date('d-m-Y h:i A', strtotime($transaction['created_at'])) ?></p>
                                                                                    </div>
                                                                                </div>
                                                                            </td>

                                                                            <td>$<?= number_format((float) e($transaction['amount']), 2) ?></td>

                                                                            <td>
                                                                                <?php if ($transaction['trx_type'] == "+"): ?>
                                                                                    <span class="badge badge-label bg-success"><i class="mdi mdi-circle-medium"></i> Credit</span>
                                                                                <?php elseif ($transaction['trx_type'] == "-"): ?>
                                                                                    <span class="badge badge-label bg-danger"><i class="mdi mdi-circle-medium"></i> Debit</span>
                                                                                <?php endif ?>
                                                                            </td>
                                                                        </tr>
                                                                    <?php endforeach ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- end col -->
                                                <?php if (count($data['get-transactions']) >= $TransactionsPerPage): ?>
                                                <div class="text-center mb-3 mt-3">
                                                    <button class="btn btn-primary waves-effect waves-light loadMoreTransactions" data-id="<?=e($data['user']['userid'])?>" data-page="2">Load More </button>
                                                </div>
                                                <?php endif; ?>

                                                <div class="row d-none" id="TransactionsLastpage">
                                                    <div class="offset-lg-3 col-lg-6 col-md-12 col-12 text-center mt-3"><p>You’ve reached the end of the list</p></div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="tab-content text-muted">
                                        <div class="tab-pane" id="loans" role="tabpanel">
                                            <?php if (empty($data['get-loans'])): ?>
                                                <div class="card">
                                                    <div class="card-header">
                                                        <div class="d-flex align-items-center">
                                                            <h5 class="card-title flex-grow-1 mb-0">All Loans</h5>
                                                        </div>
                                                    </div>

                                                    <div class="card-body">
                                                        <div class="text-center">
                                                            <div class="empty-notification-elem">
                                                                <div class="w-25 w-sm-50 pt-3 mx-auto">
                                                                    <img src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/admin/images/svg/bell.svg" class="img-fluid" alt="user-pic" />
                                                                </div>

                                                                <div class="text-center pb-5 mt-2">
                                                                    <h6 class="fs-18 fw-semibold lh-base">This user has no loans.</h6>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <div class="card">
                                                    <div class="card-header">
                                                        <div class="d-flex align-items-center">
                                                            <h5 class="card-title flex-grow-1 mb-0">All Loans</h5>
                                                        </div>
                                                    </div>

                                                    <div class="card-body">
                                                        <div class="table-card">
                                                            <table class="table table-bordered align-middle">
                                                                <tbody>
                                                                    <?php foreach ($data['get-loans'] as $loan): ?>
                                                                        <tr>
                                                                            <td colspan="3">
                                                                                <p><b>Loan Amount:</b> $<?= number_format((float) e($loan['amount']), 2) ?></p>
                                                                                <p><b>Loan Remarks:</b> <?=e($loan['loan_remarks'])?></p>
                                                                                <p><b>Created At:</b> <?= date('d-m-Y h:i A', strtotime($loan['created_at'])) ?></p>
                                                                                <p><b>Loan Term:</b> <?=e($loan['loan_term'])?></p>
                                                                                <p><b>Repayment Plan:</b> <?=e($loan['repayment_plan'])?></p>
                                                                                <p><b>Collateral:</b> <?=e($loan['collateral'])?></p>

                                                                                <?php if ($loan['loan_status'] == 1): ?>
                                                                                    <span class="badge badge-label bg-success"><i class="mdi mdi-circle-medium"></i> Approved</span>
                                                                                <?php else: ?>
                                                                                    <span class="badge badge-label bg-danger"><i class="mdi mdi-circle-medium"></i> Rejected</span>
                                                                                <?php endif ?>
                                                                            </td>
                                                                        </tr>
                                                                    <?php endforeach ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div><!-- end col -->
                            </div><!-- end row -->
                        </div>
                        <!--end col-->
                    </div>
                </div>
            </div>
            <!--end col-->
        </div>
        <!--end row-->
    </div><!-- container-fluid -->
</div>
<!-- End Page-content -->

<!-- fundsModal -->
<div id="fundsModal" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"  role="dialog" aria-labelledby="fundsBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="NotificationModalbtn-close"></button>
            </div>
            <div class="modal-body">
                <h4>Add | Remove Funds</h4>
                <p class="tx-color-03">You have full control to manage and edit this users balance, add | remove funds.</p>

                <ul class="nav nav-pills nav-justified mb-3" role="tablist">
                    <li class="nav-item waves-effect waves-light w-100" role="presentation">
                        <a class="nav-link active" data-bs-toggle="tab" href="#add-money" role="tab" aria-selected="true">
                            Add Money
                        </a>
                    </li>

                    <li class="nav-item waves-effect waves-light w-100" role="presentation">
                        <a class="nav-link" data-bs-toggle="tab" href="#remove-money" role="tab" aria-selected="false" tabindex="-1">
                            Remove Money
                        </a>
                    </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content text-muted">
                    <div class="tab-pane active show" id="add-money" role="tabpanel">
                        <form id="add-money-form">
                            <input type="hidden" id="userid" name="userid" value="<?=e($data['user']["userid"])?>">
                            <div class="form-floating mb-4">
                                <input type="text" class="form-control" id="balance" value="$<?=e($data['user']["interest_wallet"])?>" readonly>
                                <label for="balance">Current balance</label>
                            </div>

                            <div class="form-floating mb-4">
                                <input type="text" class="form-control" id="add-amount" name="amount" placeholder="Enter amount" autocomplete="off">
                                <label for="add-amount">Amount</label>
                            </div>

                            <div class="hstack gap-2 justify-content-end">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                <?=$this->token()?>
                                <button id="addMoneyBtn" class="btn btn-primary waves-effect waves-light w-100">Submit Form</button>
                            </div>
                        </form>
                    </div>

                    <div class="tab-pane" id="remove-money" role="tabpanel">
                        <form id="remove-money-form">
                            <input type="hidden" id="userid" name="userid" value="<?=e($data['user']["userid"])?>">
                            <div class="form-floating mb-4">
                                <input type="text" class="form-control" id="balance" value="$<?=e($data['user']["interest_wallet"])?>" readonly>
                                <label for="balance">Current balance</label>
                            </div>

                            <div class="form-floating mb-4">
                                <input type="text" class="form-control" id="remove-amount" name="amount" placeholder="Enter amount" autocomplete="off">
                                <label for="remove-amount">Amount</label>
                            </div>

                            <div class="hstack gap-2 justify-content-end">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                <?=$this->token()?>
                                <button id="removeMoneyBtn" class="btn btn-primary waves-effect waves-light w-100">Submit Form</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<!-- emailModal -->
<div id="emailModal" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"  role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="NotificationModalbtn-close"></button>
            </div>
            <div class="modal-body">
                <h4>Send Email -  <?=e($data['user']['firstname'])?> <?=e($data['user']['lastname'])?></h4>
                <p class="tx-color-03">Effortlessly send email notifications to this user</p>

                <form id="send-email">
                    <input type="hidden" id="email" name="email" value="<?=e($data['user']['email'])?>">
                    <div class="form-group mb-3">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="subject" name="subject" placeholder="Enter subject" autocomplete="off">
                            <label for="subject">Subject</label>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <style>
                            #editor {
                                height: 200px;
                                margin-bottom: 10px;
                            }
                            .ql-container {
                                box-sizing: border-box;
                                font-family: Helvetica, Arial, sans-serif;
                                font-size: 13px;
                                margin: 0px;
                                position: relative;
                                height: auto;
                            }
                        </style>

                        <div id="editor"></div>
                        <input type="hidden" name="description" id="details" autocomplete="off">
                        <span id="details-error"></span>
                    </div>

                    <div class="hstack gap-2 justify-content-end">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <?=$this->token()?>
                        <button id="sendEmailUser" class="btn btn-primary waves-effect waves-light w-100">Send Email</button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<!-- passwordModal -->
<div id="passwordModal" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"  role="dialog" aria-labelledby="passwordBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="NotificationModalbtn-close"></button>
            </div>
            <div class="modal-body">
                <div class="mt-2 text-center">
                    <lord-icon src="https://cdn.lordicon.com/khheayfj.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>
                    <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                        <h4>Are you sure ?</h4>
                        <p class="text-muted mx-4 mb-0">Are you sure you want to reset user's password ?</p>
                    </div>
                </div>

                <form id="reset-form">
                    <input type="hidden" id="userid" name="userid" value="<?=e($data['user']['userid'])?>">
                    <input id="password" name="password" type="text" value="<?php $password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 12); echo $password; ?>" style="display: none;">

                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                        <a onclick="copyToClipboard(document.getElementById('password'))" class="btn w-sm btn-soft-success"><i class="ri-links-line align-bottom"></i> Copy new password</a>
                        <?=$this->token()?>
                        <button id="resetUsersBtn" class="btn w-sm btn-danger" data-id="<?=e($data['user']['userid'])?>">Yes, Reset It</button>
                    </div>
                </form>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php if(e($data['user']['status']) === "1"): ?>                                           
    <!-- blockModal -->
    <div id="blockModal" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"  role="dialog" aria-labelledby="blockBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cancel" id="NotificationModalbtn-close"></button>
                </div>
                <div class="modal-body">
                    <div class="mt-2 text-center">
                        <lord-icon src="https://cdn.lordicon.com/bwakhnow.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>
                        <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                            <h4>Are you sure ?</h4>
                            <p class="text-muted mx-4 mb-0">Are you sure you want to block this Account ?</p>
                        </div>
                    </div>

                    <form id="block-form">
                        <input type="hidden" id="userid" name="userid" value="<?=e($data['user']['userid'])?>">
                        <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <?=$this->token()?>
                            <button id="blockUsersBtn" class="btn w-sm btn-danger" data-id="<?=e($data['user']['userid'])?>">Yes, Block</button>
                        </div>
                    </form>
                </div>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
<?php elseif(e($data['user']['status']) === "2"): ?>
    <!-- activateModal -->
    <div id="activateModal" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"  role="dialog" aria-labelledby="activateBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cancel" id="NotificationModalbtn-close"></button>
                </div>
                <div class="modal-body">
                    <div class="mt-2 text-center">
                        <lord-icon src="https://cdn.lordicon.com/bwakhnow.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>
                        <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                            <h4>Are you sure ?</h4>
                            <p class="text-muted mx-4 mb-0">Are you sure you want to activate this Account ?</p>
                        </div>
                    </div>

                    <form id="activate-form">
                        <input type="hidden" id="userid" name="userid" value="<?=e($data['user']['userid'])?>">
                        <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <?=$this->token()?>
                            <button id="activateUsersBtn" class="btn w-sm btn-danger" data-id="<?=e($data['user']['userid'])?>">Yes, Activate</button>
                        </div>
                    </form>
                </div>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
<?php endif; ?>

<!-- deleteModal -->
<div id="deleteModal" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"  role="dialog" aria-labelledby="deleteBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cancel" id="NotificationModalbtn-close"></button>
            </div>
            <div class="modal-body">
                <div class="mt-2 text-center">
                    <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>
                    <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                        <h4>Are you sure ?</h4>
                        <p class="text-muted mx-4 mb-0">Are you sure you want to delete this Account ?</p>
                    </div>
                </div>
                <form id="delete-form">
                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <?=$this->token()?>
                        <button id="deleteUsersBtn" class="btn w-sm btn-danger" data-user="<?= e($data['user']['userid']) ?>">Yes, Delete</button>
                    </div>
                </form>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<!-- addDepositModal -->
<div id="addDepositModal" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"  role="dialog" aria-labelledby="addDepositBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="NotificationModalbtn-close"></button>
            </div>
            <div class="modal-body">
                <h4>Add Deposit Record</h4>
                <p class="tx-color-03">You have full control to manage this users deposit record.</p>

                <form id="add-deposit-form">
                    <input type="hidden" id="userid" name="userid" value="<?=e($data['user']["userid"])?>">
                    <div class="form-floating mb-4">
                        <select id="method_code" name="method_code" class="form-control">
                            <?php foreach ($data['gateways'] as $gateway): ?>
                                <option value="<?=e($gateway["method_code"])?>"><?=e($gateway["name"])?></option>
                            <?php endforeach ?>
                        </select>
                        <label for="method_code">Payment Gateway</label>
                    </div>

                    <div class="form-floating mb-4">
                        <input type="text" class="form-control" id="deposit-amount" name="amount" placeholder="Enter amount" autocomplete="off">
                        <label for="deposit-amount">Amount</label>
                    </div>

                    <div class="hstack gap-2 justify-content-end">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <?=$this->token()?>
                        <button id="addDepositBtn" class="btn btn-primary waves-effect waves-light w-100">Submit Form</button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<!-- addWithdrawalModal -->
<div id="addWithdrawalModal" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"  role="dialog" aria-labelledby="addWithdrawalBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="NotificationModalbtn-close"></button>
            </div>
            <div class="modal-body">
                <h4>Add Withdrawal Record</h4>
                <p class="tx-color-03">You have full control to manage this users withdrawal record.</p>

                <form id="add-withdrawal-form">
                    <input type="hidden" id="userid" name="userid" value="<?=e($data['user']["userid"])?>">
                    <div class="form-floating mb-4">
                        <select id="withdraw_code" name="withdraw_code" class="form-control">
                            <?php foreach ($data['withdrawal-gateways'] as $gateway): ?>
                                <option value="<?= e($gateway['withdraw_code']) ?>" selected>
                                        <?= e($gateway['name']) ?>
                                    </option>
                            <?php endforeach; ?>
                        </select>
                        <label for="withdraw_code">Withdrawal Gateway</label>
                    </div>

                    <div class="form-floating mb-4">
                        <input type="text" class="form-control" id="withdrawal-wallet" name="wallet" autocomplete="off" readonly>
                        <label for="withdrawal-wallet">Wallet Address</label>
                    </div>

                    <div class="form-floating mb-4">
                        <input type="text" class="form-control" id="withdrawal-amount" name="amount" placeholder="Enter amount" autocomplete="off">
                        <label for="withdrawal-amount">Amount</label>
                    </div>

                    <div class="hstack gap-2 justify-content-end">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <?=$this->token()?>
                        <button id="addWithdrawalBtn" class="btn btn-primary waves-effect waves-light w-100">Submit Form</button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<!-- addDepositModal -->
<div id="addInvestmentModal" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"  role="dialog" aria-labelledby="addDepositBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="NotificationModalbtn-close"></button>
            </div>
            <div class="modal-body">
                <h4>Add Investment Record</h4>
                <p class="tx-color-03">You have full control to manage this users investment record.</p>

                <form id="add-investment-form">
                    <input type="hidden" id="userid" name="userid" value="<?=e($data['user']["userid"])?>">
                    <div class="form-floating mb-4">
                        <select id="planId" name="planId" class="form-control">
                            <?php foreach ($data['plans'] as $plan): ?>
                                <option value="<?=e($plan["planId"])?>"><?=e($plan["name"])?></option>
                            <?php endforeach ?>
                        </select>
                        <label for="planId">Investment Plan</label>
                    </div>

                    <div class="form-floating mb-4">
                        <input type="text" class="form-control" id="investment-amount" name="amount" placeholder="Enter amount" autocomplete="off">
                        <label for="investment-amount">Amount</label>
                    </div>

                    <div class="hstack gap-2 justify-content-end">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <?=$this->token()?>
                        <button id="addInvestmentBtn" class="btn btn-primary waves-effect waves-light w-100">Submit Form</button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<!-- deleteDepositModal -->
<div id="deleteDepositModal" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"  role="dialog" aria-labelledby="deleteDepositBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cancel" id="NotificationModalbtn-close"></button>
            </div>

            <div class="modal-body">
                <div class="mt-2 text-center">
                    <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>
                    <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                        <h4>Are you sure ?</h4>
                        <p class="text-muted mx-4 mb-0">Are you sure you want to delete this Deposit ?</p>
                    </div>
                </div>

                <form id="delete-deposit-form">
                    <input type="hidden" id="delete-depositId" name="depositId">
                    <input type="hidden" id="userid" name="userid" value="<?=e($data['user']["userid"])?>">
                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <?=$this->token()?>
                        <button id="deleteDepositBtn" class="btn w-sm btn-danger">Yes, Delete</button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<!-- deleteWithdrawalModal -->
<div id="deleteWithdrawalModal" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"  role="dialog" aria-labelledby="deleteWithdrawalBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cancel" id="NotificationModalbtn-close"></button>
            </div>

            <div class="modal-body">
                <div class="mt-2 text-center">
                    <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>
                    <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                        <h4>Are you sure ?</h4>
                        <p class="text-muted mx-4 mb-0">Are you sure you want to delete this Withdrawal ?</p>
                    </div>
                </div>

                <form id="delete-withdrawal-form">
                    <input type="hidden" id="delete-withdrawId" name="withdrawId">
                    <input type="hidden" id="userid" name="userid" value="<?=e($data['user']["userid"])?>">
                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <?=$this->token()?>
                        <button id="deleteWithdrawBtn" class="btn w-sm btn-danger">Yes, Delete</button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<!-- deleteInvestmentModal -->
<div id="deleteInvestmentModal" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"  role="dialog" aria-labelledby="deleteInvestmentBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cancel" id="NotificationModalbtn-close"></button>
            </div>

            <div class="modal-body">
                <div class="mt-2 text-center">
                    <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>
                    <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                        <h4>Are you sure ?</h4>
                        <p class="text-muted mx-4 mb-0">Are you sure you want to delete this Investment ?</p>
                    </div>
                </div>

                <form id="delete-investment-form">
                    <input type="hidden" id="delete-investId" name="investId">
                    <input type="hidden" id="userid" name="userid" value="<?=e($data['user']["userid"])?>">
                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <?=$this->token()?>
                        <button id="deleteInvestmentBtn" class="btn w-sm btn-danger">Yes, Delete</button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>