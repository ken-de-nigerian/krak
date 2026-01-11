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
                                    <h4 class="display-6 coming-soon-text">Settings</h4>
                                    <p class="text-success fs-15 mt-3">You have full control to manage and edit your site settings here.</p>
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

                <form id="setup-form">
                    <div class="row justify-content-evenly mb-2">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">General Information</h4>
                            </div>
                            
                            <!-- end card header -->
                            <div class="card-body">
                                <div class="live-preview">
                                    <div class="row gy-4">
                                        <div class="col-xxl-3 col-md-6">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="sitename" name="sitename" value="<?=e($data['settings']['sitename'])?>" autocomplete="off">
                                                <label for="sitename">Sitename</label>
                                            </div>
                                        </div>
                                        <!--end col-->

                                        <div class="col-xxl-3 col-md-6">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" value="USD" id="currency" autocomplete="off">
                                                <label for="currency">Currency</label>
                                            </div>
                                        </div>
                                        <!--end col-->

                                        <div class="col-xxl-3 col-md-6">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" value="$" id="currency-symbol" autocomplete="off">
                                                <label for="currency-symbol">Currency Symbol</label>
                                            </div>
                                        </div>
                                        <!--end col-->

                                        <div class="col-xxl-3 col-md-6">
                                            <div class="form-floating">
                                                <select id="timezone" name="timezone" class="form-control" autocomplete="off">
                                                    <?php foreach(timezone_identifiers_list() as $value): ?>
                                                        <option value="<?=e($value)?>"<?=(e($data['settings']['timezone']) == $value ? ' selected' : '')?>><?=e($value)?></option>
                                                    <?php endforeach ?>
                                                </select>
                                                <label for="timezone">Timezone</label>
                                            </div>
                                        </div>
                                        <!--end col-->
                                    </div>
                                    <!--end row-->
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-evenly mb-2">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Settings</h4>
                            </div>
                            
                            <!-- end card header -->
                            <div class="card-body">
                                <div class="live-preview">
                                    <div class="row gy-4">
                                        <div class="col-xxl-4 col-md-6">
                                            <div class="form-floating">
                                                <select id="invest_commission" name="invest_commission" class="form-control" autocomplete="off">
                                                    <option value="1" <?=(e($data['settings']['invest_commission']) == '1' ? ' selected' : '')?>>Enabled</option>
                                                    <option value="2" <?=(e($data['settings']['invest_commission']) == '2' ? ' selected' : '')?>>Disabled</option>
                                                </select>
                                                <label for="invest_commission">Referral Commissions</label>
                                            </div>
                                        </div>
                                        <!--end col-->

                                        <div class="col-xxl-4 col-md-6">
                                            <div class="form-floating">
                                                <select id="signup_bonus_control" name="signup_bonus_control" class="form-control" autocomplete="off">
                                                    <option value="1" <?=(e($data['settings']['signup_bonus_control']) == '1' ? ' selected' : '')?>>Enabled</option>
                                                    <option value="2" <?=(e($data['settings']['signup_bonus_control']) == '2' ? ' selected' : '')?>>Disabled</option>
                                                </select>
                                                <label for="signup_bonus_control">Signup Bonus</label>
                                            </div>
                                        </div>
                                        <!--end col-->

                                        <div class="col-xxl-4 col-md-6">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="signup_bonus_amount" name="signup_bonus_amount" value="<?=e($data['settings']['signup_bonus_amount'])?>" autocomplete="off">
                                                <label for="signup_bonus_amount">Signup Bonus Amount</label>
                                            </div>
                                        </div>
                                        <!--end col-->

                                        <div class="col-xxl-4 col-md-6">
                                            <div class="form-floating">
                                                <select id="b_transfer" name="b_transfer" class="form-control" autocomplete="off">
                                                    <option value="1" <?=(e($data['settings']['b_transfer']) == '1' ? ' selected' : '')?>>Enabled</option>
                                                    <option value="2" <?=(e($data['settings']['b_transfer']) == '2' ? ' selected' : '')?>>Disabled</option>
                                                </select>
                                                <label for="b_transfer">Transfer Funds</label>
                                            </div>
                                        </div>

                                        <div class="col-xxl-4 col-md-6">
                                            <div class="form-floating">
                                                <select id="b_request" name="b_request" class="form-control" autocomplete="off">
                                                    <option value="1" <?=(e($data['settings']['b_request']) == '1' ? ' selected' : '')?>>Enabled</option>
                                                    <option value="2" <?=(e($data['settings']['b_request']) == '2' ? ' selected' : '')?>>Disabled</option>
                                                </select>
                                                <label for="b_request">Request Funds</label>
                                            </div>
                                        </div>
                                        <!--end col-->

                                        <div class="col-xxl-4 col-md-6">
                                            <div class="form-floating">
                                                <select id="user_ranking" name="user_ranking" class="form-control" autocomplete="off">
                                                    <option value="1" <?=(e($data['settings']['user_ranking']) == '1' ? ' selected' : '')?>>Enabled</option>
                                                    <option value="2" <?=(e($data['settings']['user_ranking']) == '2' ? ' selected' : '')?>>Disabled</option>
                                                </select>
                                                <label for="user_ranking">User Ranking</label>
                                            </div>
                                        </div>
                                        <!--end col-->
                                    </div>
                                    <!--end row-->
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-evenly mb-2">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Security</h4>
                            </div>
                            
                            <!-- end card header -->
                            <div class="card-body">
                                <div class="live-preview">
                                    <div class="row gy-4">
                                        <div class="col-xxl-4 col-md-6">
                                            <div class="form-floating">
                                                <select id="twofa_status" name="twofa_status" class="form-control" autocomplete="off">
                                                    <option value="1" <?=(e($data['settings']['twofa_status']) == '1' ? ' selected' : '')?>>Enabled</option>
                                                    <option value="2" <?=(e($data['settings']['twofa_status']) == '2' ? ' selected' : '')?>>Disabled</option>
                                                </select>
                                                <label for="twofa_status">2FA Authentication</label>
                                            </div>
                                        </div>
                                        <!--end col-->

                                        <div class="col-xxl-4 col-md-6">
                                            <div class="form-floating">
                                                <select id="register_status" name="register_status" class="form-control" autocomplete="off">
                                                    <option value="1" <?=(e($data['settings']['register_status']) == '1' ? ' selected' : '')?>>Enabled</option>
                                                    <option value="2" <?=(e($data['settings']['register_status']) == '2' ? ' selected' : '')?>>Disabled</option>
                                                </select>
                                                <label for="register_status">User Registration</label>
                                            </div>
                                        </div>
                                        <!--end col-->

                                        <div class="col-xxl-4 col-md-6 mb-2">
                                            <div class="form-floating">
                                                <select id="kyc_status" name="kyc_status" class="form-control" autocomplete="off">
                                                    <option value="1" <?=(e($data['settings']['kyc_status']) == '1' ? ' selected' : '')?>>Enabled</option>
                                                    <option value="2" <?=(e($data['settings']['kyc_status']) == '2' ? ' selected' : '')?>>Disabled</option>
                                                </select>
                                                <label for="kyc_status">KYC Status</label>
                                            </div>
                                        </div>

                                        <div class="col-xxl-12 col-md-12">
                                            <?=$this->token()?>
                                            <button id="setupBtn" type="submit" class="btn btn-primary waves-effect waves-light w-100">Submit Form</button>
                                        </div>
                                        <!--end col-->
                                    </div>
                                    <!--end row-->
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!--end col-->
        </div>
        <!--end row-->
    </div>
    <!-- container-fluid -->
</div>
<!-- End Page-content -->


