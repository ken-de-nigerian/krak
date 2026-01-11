<?php
defined('FIR') OR exit();
/**
 * The template for displaying the footer section
 */
?>
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card rounded-0 bg-success-subtle mx-n4 mt-n4 border-top">
                    <div class="px-4">
                        <div class="row">
                            <div class="col-xxl-5 align-self-center">
                                <div class="py-4">
                                    <?php foreach ($data['withdrawal-gateways'] as $gateway): ?>
                                        <?php if ($gateway['withdraw_code'] === $data['withdrawal-details']['withdraw_code']): ?>
                                            <?php foreach ($data['users'] as $user): ?>
                                                <?php if ($user['userid'] === $data['withdrawal-details']['userid']): ?>
                                                    <h4 class="display-6 coming-soon-text">
                                                        <?=e($gateway["name"])?>           
                                                    </h4>

                                                    <p class="text-success fs-15 mt-3">Withdrawn By <?=e($user["firstname"])?> <?=e($user["lastname"])?> - $<?=e($data['withdrawal-details']["amount"])?></p>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>

                                    <div class="hstack flex-wrap gap-2">
                                        <a href="<?=$this->siteUrl()?>/admin/withdrawals" class="btn btn-primary btn-label rounded-pill">
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

                <div class="row">
                    <div class="col-xxl-4">
                        <?php if ($data['withdrawal-details']['status'] == 2): ?>
                            <div class="card">
                                <div class="card-body">
                                    <input id="address" type="text" value="<?=e($data['withdrawal-details']["wallet_address"])?>" autocomplete="off" style="display: none;">
                                    <a onclick="copyToClipboard(document.getElementById('address'))" class="btn w-sm btn-soft-success w-100"><i class="ri-links-line align-bottom"></i> Copy User's Wallet</a>
                                </div>
                            </div>
                        <?php endif ?>

                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex align-items-center">
                                    <h5 class="card-title flex-grow-1 mb-0">Withdrawal Information</h5>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-borderless mb-0">
                                        <tbody>
                                            <tr>
                                                <th class="ps-0" scope="row">Wallet :</th>
                                                <td class="text-muted">
                                                    <?php foreach ($data['withdrawal-gateways'] as $gateway): ?>
                                                        <?php if ($gateway['withdraw_code'] === $data['withdrawal-details']['withdraw_code']): ?>
                                                            <?=e($gateway["name"])?>  
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th class="ps-0" scope="row">Withdrawn By</th>
                                                <td class="text-muted">
                                                    <?php foreach ($data['users'] as $user): ?>
                                                        <?php if ($user['userid'] === $data['withdrawal-details']['userid']): ?>
                                                            <span class="badge badge-label bg-primary">
                                                                <i class="mdi mdi-circle-medium"></i>
                                                                <?=e($user["firstname"])?> <?=e($user["lastname"])?>
                                                            </span>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th class="ps-0" scope="row">Amount :</th>
                                                <td class="text-muted">$<?=e($data['withdrawal-details']["amount"])?></td>
                                            </tr>

                                            <tr>
                                                <th class="ps-0" scope="row">Crypto Equivalent :</th>
                                                <td class="text-muted"><?= isset($data['withdrawal-details']["crypto_amount"]) && !empty($data['withdrawal-details']["crypto_amount"]) ? e($data['withdrawal-details']["crypto_amount"]) : '0.00' ?>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th class="ps-0" scope="row">Date</th>
                                                <td class="text-muted"><?= date('d M Y h:i A', strtotime($data['withdrawal-details']['created_at'])) ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div>
                    <!--end col-->

                    <div class="col-xxl-8">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex align-items-center">
                                    <h5 class="card-title flex-grow-1 mb-0">Withdrawal History</h5>

                                    <?php if ($data['withdrawal-details']['status'] == 2): ?>
                                        <div class="flex-shrink-0">
                                            <button data-bs-toggle="modal" data-bs-target="#approveWithdrawalModal" class="btn btn-primary btn-sm me-3"><i class=" ri-checkbox-circle-line align-middle me-1"></i> Approve</button>

                                            <button data-bs-toggle="modal" data-bs-target="#rejectWithdrawalModal" class="btn btn-primary btn-sm"><i class=" ri-close-circle-line align-middle me-1"></i> Reject</button>
                                        </div>
                                    <?php endif ?>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="table-card">
                                    <table class="table table-borderless align-middle">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">

                                                        <?php foreach ($data['withdrawal-gateways'] as $gateway): ?>
                                                            <?php if ($gateway['withdraw_code'] === $data['withdrawal-details']['withdraw_code']): ?>
                                                                <img src="<?=$this->siteUrl().'/'.PUBLIC_PATH.'/'.UPLOADS_PATH?>/withdrawal/<?=e($gateway["image"])?>" class="avatar-xs rounded-circle" alt="user-profile-image">
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>

                                                        <div class="ms-2">
                                                            <a>
                                                                <h6 class="fs-15 mb-1">
                                                                    <?php foreach ($data['withdrawal-gateways'] as $gateway): ?>
                                                                        <?php if ($gateway['withdraw_code'] === $data['withdrawal-details']['withdraw_code']): ?>
                                                                            <?=e($gateway["name"])?>  
                                                                        <?php endif; ?>
                                                                    <?php endforeach; ?>
                                                                </h6>
                                                            </a>

                                                            <p class="mb-0 text-muted"><?= date('d-m-Y h:i A', strtotime($data['withdrawal-details']['created_at'])) ?></p>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td>
                                                    $<?= number_format((float) e($data['withdrawal-details']['amount']), 2) ?>
                                                </td>

                                                <?php if ($data['withdrawal-details']['status'] == 1): ?>
                                                    <td class="text-success">Completed</td>
                                                <?php elseif ($data['withdrawal-details']['status'] == 2): ?>
                                                    <td class="text-warning">Pending</td>
                                                <?php elseif ($data['withdrawal-details']['status'] == 0): ?>
                                                    <td>Initiated</td>
                                                <?php elseif ($data['withdrawal-details']['status'] == 3): ?>
                                                    <td class="text-danger">Rejected</td>
                                                <?php endif; ?>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end col-->
                </div>
                <!--end row-->
            </div>
        </div>
        <!--end row-->
    </div>
    <!-- container-fluid -->
</div>
<!-- End Page-content -->

<!-- approveWithdrawalModal -->
<div id="approveWithdrawalModal" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"  role="dialog" aria-labelledby="approveBackdropLabel" aria-hidden="true">
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
                        <p class="text-muted mx-4 mb-0">Are you sure you want to approve this Withdrawal ?</p>
                    </div>
                </div>

                <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <?=$this->token()?>
                    <button id="approveWithdrawalBtn" class="btn w-sm btn-danger" data-approve="<?=e($data['withdrawal-details']['withdrawId'])?>">Yes, Approve</button>
                </div>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- rejectWithdrawalModal -->
<div id="rejectWithdrawalModal" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"  role="dialog" aria-labelledby="rejectBackdropLabel" aria-hidden="true">
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
                        <p class="text-muted mx-4 mb-0">Are you sure you want to reject this Withdrawal ?</p>
                    </div>
                </div>

                <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <?=$this->token()?>
                    <button id="rejectWithdrawalBtn" class="btn w-sm btn-danger" data-reject="<?=e($data['withdrawal-details']['withdrawId'])?>">Yes, Reject</button>
                </div>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->