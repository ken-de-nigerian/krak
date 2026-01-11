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
                                    <h4 class="display-6 coming-soon-text">Edit Rank - <?=e($data["get-rank"]["name"])?></h4>
                                    <p class="text-success fs-15 mt-3">Stay in control and optimize the ranking system to foster engagement and reward user achievement.</p>
                                    <div class="hstack flex-wrap gap-2">
                                        <a href="<?=$this->siteUrl()?>/admin/ranking" class="btn btn-primary btn-label rounded-pill">
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

                <form id="rank-form" class="row gx-5">
                    <div class="col-lg-6">
                        <div class="row justify-content-evenly mb-2">
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Rank Image</h4>
                                </div>
                                
                                <!-- end card header -->
                                <div class="card-body">
                                    <div class="live-preview">
                                        <div class="row gy-4">
                                            <div class="col-xxl-12 col-md-12">
                                                <div class="form-floating">
                                                    <input type="file" name="photoimg" id="hidden-input" class="form-control">
                                                    <label for="hidden-input">Rank Image</label>
                                                </div>

                                                <img id="preview-image" class="img-thumbnail mt-4 mb-4" alt="Profile Picture" src="<?=$this->siteUrl().'/'.PUBLIC_PATH.'/'.UPLOADS_PATH?>/ranks/<?=e($data["get-rank"]["icon"])?>" style="width: 45%;">
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
                                    <h4 class="card-title mb-0 flex-grow-1">Rank Data</h4>
                                </div>
                                
                                <!-- end card header -->
                                <div class="card-body">
                                    <div class="live-preview">
                                        <div class="row gy-4">
                                            <div class="col-xxl-6 col-md-6">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" id="name" name="name" value="<?=e($data["get-rank"]["name"])?>" autocomplete="off">
                                                    <label for="name">Name</label>
                                                </div>
                                            </div>
                                            <!--end col-->

                                            <div class="col-xxl-6 col-md-6">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" name="min_invest" id="min_invest" value="<?=e($data["get-rank"]["min_invest"])?>" autocomplete="off">
                                                    <label for="min_invest">Min Invest</label>
                                                </div>
                                            </div>
                                            <!--end col-->

                                            <div class="col-xxl-6 col-md-6">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" name="min_referral" id="min_referral" value="<?=e($data["get-rank"]["min_referral"])?>" autocomplete="off">
                                                    <label for="min_referral">Min Referral</label>
                                                </div>
                                            </div>
                                            <!--end col-->

                                            <div class="col-xxl-6 col-md-6">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" name="bonus" id="bonus" value="<?=e($data["get-rank"]["bonus"])?>" autocomplete="off">
                                                    <label for="bonus">Bonus</label>
                                                </div>
                                            </div>
                                            <!--end col-->

                                            <div class="col-xxl-12 col-md-12">
                                                <div class="form-floating">
                                                    <select id="rank_status" name="status" class="form-control" autocomplete="off">
                                                        <option value="1" <?=(e($data['get-rank']['status']) == '1' ? ' selected' : '')?>>Active</option>
                                                        <option value="2" <?=(e($data['get-rank']['status']) == '2' ? ' selected' : '')?>>Inactive</option>
                                                    </select>
                                                    <label for="rank_status">Rank Status</label>
                                                </div>
                                            </div>
                                            <!--end col-->

                                            <div class="col-xxl-12 col-md-12">
                                                <?=$this->token()?>
                                                <button id="rankBtn" class="btn btn-primary waves-effect waves-light w-100" data-rank="<?=e($data['get-rank']['rankingId'])?>">Submit Form</button>
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