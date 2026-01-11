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
                                    <h4 class="display-6 coming-soon-text">SEO Configuration</h4>
                                    <p class="text-success fs-15 mt-3">Boost Your Website's Visibility: Expert SEO Tips & Strategies.</p>
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

                <form id="seo-form" class="row gx-5">
                    <div class="col-lg-6">
                        <div class="row justify-content-evenly mb-2">
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">SEO Image</h4>
                                </div>
                                
                                <!-- end card header -->
                                <div class="card-body">
                                    <div class="live-preview">
                                        <div class="row gy-4">
                                            <div class="col-xxl-12 col-md-12">
                                                <div class="form-floating">
                                                    <input type="file" name="photoimg" id="hidden-input" class="form-control">
                                                    <label for="hidden-input">SEO Image</label>
                                                </div>
                                            </div>
                                            <!--end col-->
                                        </div>
                                        <!--end row-->
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <img id="preview-image" alt="SEO Image" src="<?=$this->siteUrl().'/'.PUBLIC_PATH.'/'.UPLOADS_PATH?>/seo/<?=e($this->siteSettings('seo_image'))?>" style="width: 100%;height: 400px;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="row justify-content-evenly mb-2">
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">SEO Data</h4>
                                </div>
                                
                                <!-- end card header -->
                                <div class="card-body">
                                    <div class="live-preview">
                                        <div class="row gy-4">
                                            <div class="col-xxl-12 col-md-12">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" id="keywords" name="keywords" value="<?=e($data['settings']['keywords'])?>" autocomplete="off">
                                                    <label for="keywords">Meta Keywords</label>
                                                </div>
                                            </div>
                                            <!--end col-->

                                            <div class="col-xxl-12 col-md-12">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" name="title" id="title" value="<?=e($data['settings']['title'])?>" autocomplete="off">
                                                    <label for="title">Meta Title</label>
                                                </div>
                                            </div>
                                            <!--end col-->

                                            <div class="col-xxl-12 col-md-12">
                                                <div class="form-floating">
                                                    <textarea type="text" class="form-control" id="description" name="description" rows="3" style="height: 150px;" autocomplete="off"><?=e($data['settings']['description'])?></textarea>
                                                    <label for="description">Meta Description</label>
                                                </div>
                                            </div>
                                            <!--end col-->

                                            <div class="col-xxl-12 col-md-12">
                                                <?=$this->token()?>
                                                <button id="seoBtn" class="btn btn-primary waves-effect waves-light w-100">Submit Form</button>
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