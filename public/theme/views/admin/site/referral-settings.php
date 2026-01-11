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
                                    <h4 class="display-6 coming-soon-text">Referral Settings</h4>
                                    <p class="text-success fs-15 mt-3">Set your site referral percentage status here.</p>
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

                <form id="referral-form">
                    <div class="row justify-content-evenly mb-2">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Referral Settings</h4>
                            </div>
                            
                            <!-- end card header -->
                            <div class="card-body">
                                <div class="live-preview">
                                    <div class="row gy-4">
                                        <div class="col-xxl-6 col-md-6">
                                            <div class="form-floating">
                                                <select id="referral_status" name="status" class="form-control" autocomplete="off">
                                                    <option value="1" <?=(e($data['referral-settings']['status']) == '1' ? ' selected' : '')?>>Enabled</option>
                                                    <option value="2" <?=(e($data['referral-settings']['status']) == '2' ? ' selected' : '')?>>Disabled</option>
                                                </select>
                                                <label for="referral_status">Referral Status</label>
                                            </div>
                                        </div>
                                        <!--end col-->

                                        <div class="col-xxl-6 col-md-6">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" name="percent" id="percent" value="<?=e($data['referral-settings']['percent'])?>" autocomplete="off">
                                                <label for="percent">Percentage</label>
                                            </div>
                                        </div>
                                        <!--end col-->
                                        
                                        <div class="col-xxl-12 col-md-6">
                                            <?=$this->token()?>
                                            <button id="referralBtn" class="btn btn-primary waves-effect waves-light w-100">Submit Form</button>
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