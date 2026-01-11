<?php
defined('FIR') OR exit();
/**
 * The template for displaying the footer section
 */
?>
<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card rounded-0 bg-success-subtle mx-n4 mt-n4 border-top">
                    <div class="px-4">
                        <div class="row">
                            <div class="col-xxl-5 align-self-center">
                                <div class="py-4">
                                    <h4 class="display-6 coming-soon-text">Logo & Favicon</h4>
                                    <p class="text-success fs-15 mt-3">If the logo and favicon are not changed after you update, please clear the cache from your browser.</p>
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

                <div class="row gx-5">
                    <div class="col-lg-6">
                        <form id="logo-form">
                            <div class="row justify-content-evenly mb-2">
                                <div class="card">
                                    <div class="card-header align-items-center d-flex">
                                        <h4 class="card-title mb-0 flex-grow-1">Logo</h4>
                                    </div>
                                    
                                    <!-- end card header -->
                                    <div class="card-body">
                                        <div class="live-preview">
                                            <div class="row gy-4">
                                                <div class="col-xxl-12 col-md-12">
                                                    <div class="form-floating">
                                                        <input type="file" name="photoimg" id="hidden-input" class="form-control">
                                                        <label for="hidden-input">Logo</label>
                                                    </div>
                                                </div>

                                                <div class="col-xxl-12 col-md-12">
                                                    <?=$this->token()?>
                                                    <button id="logoBtn" class="btn btn-primary waves-effect waves-light w-100">Upload Logo</button>
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

                    <div class="col-lg-6">
                        <form id="favicon-form">
                            <div class="row justify-content-evenly mb-2">
                                <div class="card">
                                    <div class="card-header align-items-center d-flex">
                                        <h4 class="card-title mb-0 flex-grow-1">Favicon</h4>
                                    </div>
                                    
                                    <!-- end card header -->
                                    <div class="card-body">
                                        <div class="live-preview">
                                            <div class="row gy-4">
                                                <div class="col-xxl-12 col-md-12">
                                                    <div class="form-floating">
                                                        <input type="file" name="photoimg" id="hidden-input" class="form-control">
                                                        <label for="hidden-input">Favicon</label>
                                                    </div>
                                                </div>

                                                <div class="col-xxl-12 col-md-12">
                                                    <?=$this->token()?>
                                                    <button id="faviconBtn" class="btn btn-primary waves-effect waves-light w-100">Upload Favicon</button>
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
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
    <!-- container-fluid -->
</div>
<!-- End Page-content -->