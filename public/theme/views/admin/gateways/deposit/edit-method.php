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
                                    <h4 class="display-6 coming-soon-text">Edit Method - <?=e($data['payment-method']["name"])?></h4>
                                    <p class="text-success fs-15 mt-3">You have complete authority to oversee and modify your deposit methods here.</p>
                                    <div class="hstack flex-wrap gap-2">
                                        <a href="<?=$this->siteUrl()?>/admin/deposit_gateway" class="btn btn-primary btn-label rounded-pill">
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

                <form id="edit-deposit-method-form" class="row gx-5">
                    <div class="col-lg-6">
                        <div class="row justify-content-evenly mb-2">
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Deposit Method Image</h4>
                                </div>
                                
                                <!-- end card header -->
                                <div class="card-body">
                                    <div class="live-preview">
                                        <div class="row gy-4">
                                            <div class="col-xxl-12 col-md-12">
                                                <div class="form-floating">
                                                    <input type="file" name="photoimg" id="hidden-input" class="form-control">
                                                    <label for="hidden-input">Deposit Method Image</label>
                                                </div>

                                                <img id="preview-image" class="img-thumbnail mt-4 mb-4" alt="Profile Picture" src="<?=$this->siteUrl().'/'.PUBLIC_PATH.'/'.UPLOADS_PATH?>/deposit/<?=e($data['payment-method']["image"])?>" style="width: 45%;">
                                            </div>
                                            <!--end col-->
                                        </div>
                                        <!--end row-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="row justify-content-evenly mb-2">
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Deposit Method Data</h4>
                                </div>
                                
                                <!-- end card header -->
                                <div class="card-body">
                                    <div class="live-preview">
                                        <div class="row gy-4">
                                            <div class="col-xxl-6 col-md-6">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" id="name" name="name" value="<?=e($data['payment-method']["name"])?>" autocomplete="off">
                                                    <label for="name">Name</label>
                                                </div>
                                            </div>
                                            <!--end col-->

                                            <div class="col-xxl-6 col-md-6">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" name="abbreviation" id="abbreviation" value="<?=e($data['payment-method']["abbreviation"])?>">
                                                    <label for="abbreviation">Abbreviation</label>
                                                </div>
                                            </div>
                                            <!--end col-->

                                            <div class="col-xxl-6 col-md-6">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" name="min_amount" id="min_amount" value="<?=e($data['payment-method']["min_amount"])?>" autocomplete="off">
                                                    <label for="min_amount">Minimum Amount</label>
                                                </div>
                                            </div>
                                            <!--end col-->

                                            <div class="col-xxl-6 col-md-6">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" name="max_amount" id="max_amount" value="<?=e($data['payment-method']["max_amount"])?>" autocomplete="off">
                                                    <label for="max_amount">Maximum Amount</label>
                                                </div>
                                            </div>
                                            <!--end col-->

                                            <div class="col-xxl-12 col-md-12">
                                                <div class="form-floating">
                                                    <textarea type="text" class="form-control" id="gateway_parameter" name="gateway_parameter" placeholder="Enter Wallet" rows="3" style="height: 70px;" autocomplete="off"><?=e($data['payment-method']["gateway_parameter"])?></textarea>
                                                    <label for="gateway_parameter">Wallet Address</label>
                                                </div>
                                            </div>
                                            <!--end col-->

                                            <div class="col-xxl-4 col-md-4">
                                                <div class="form-floating">
                                                    <select id="method_status" name="status" class="form-control" autocomplete="off">
                                                        <option value="1" <?=(e($data['payment-method']['status']) == '1' ? ' selected' : '')?>>Active</option>
                                                        <option value="2" <?=(e($data['payment-method']['status']) == '2' ? ' selected' : '')?>>Inactive</option>
                                                    </select>
                                                    <label for="method_status">Status</label>
                                                </div>
                                            </div>
                                            <!--end col-->

                                            <div class="col-xxl-4 col-md-4">
                                                <div class="form-floating">
                                                    <select id="need_proof" name="need_proof" class="form-control" autocomplete="off">
                                                        <option value="1" <?=(e($data['payment-method']['need_proof']) == '1' ? ' selected' : '')?>>Yes</option>
                                                        <option value="2" <?=(e($data['payment-method']['need_proof']) == '2' ? ' selected' : '')?>>No</option>
                                                    </select>
                                                    <label for="need_proof">Deposit Proof</label>
                                                </div>
                                            </div>
                                            <!--end col-->

                                            <div class="col-xxl-4 col-md-4">
                                                <div class="form-floating">
                                                    <select id="proof_type" name="proof_type" class="form-control" autocomplete="off">
                                                        <option value="image" <?=(e($data['payment-method']['status']) == 'image' ? ' selected' : '')?>>Image Proof</option>
                                                        <option value="text" <?=(e($data['payment-method']['status']) == 'text' ? ' selected' : '')?>>Transaction ID</option>
                                                    </select>
                                                    <label for="proof_type">Proof Type</label>
                                                </div>
                                            </div>
                                            <!--end col-->

                                            <div class="col-xxl-12 col-md-12">
                                                <?=$this->token()?>
                                                <button id="editDepositMethodBtn" class="btn btn-primary waves-effect waves-light w-100" data-id="<?=e($data['payment-method']['method_code'])?>">Submit Form</button>
                                            </div>
                                            <!--end col-->
                                        </div>
                                        <!--end row-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!--end row-->
    </div>
    <!-- container-fluid -->
</div>
<!-- End Page-content -->