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
                                    <h4 class="display-6 coming-soon-text">Edit Template - <?=e($data['get-email-details']['name'])?></h4>
                                    <p class="text-success fs-15 mt-3">Edit your email template and set status here.</p>
                                    <div class="hstack flex-wrap gap-2">
                                        <a href="<?=$this->siteUrl()?>/admin/templates" class="btn btn-primary btn-label rounded-pill">
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

                <form id="edit-email-form">
                    <input type="hidden" id="id" name="id" value="<?=e($data['get-email-details']['id'])?>">
                    <div class="row justify-content-evenly mb-2">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Edit Template</h4>
                            </div>
                            
                            <!-- end card header -->
                            <div class="card-body">
                                <div class="live-preview">
                                    <div class="row gy-4">
                                        <div class="col-xxl-4 col-md-6">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="name" name="name" value="<?=e($data['get-email-details']['name'])?>" autocomplete="off">
                                                <label for="name">Name</label>
                                            </div>
                                        </div>
                                        <!--end col-->

                                        <div class="col-xxl-4 col-md-6">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="subject" name="subject" value="<?=e($data['get-email-details']['subject'])?>" autocomplete="off">
                                                <label for="subject">Subject</label>
                                            </div>
                                        </div>
                                        <!--end col-->

                                        <div class="col-xxl-4 col-md-6 mb-2">
                                            <div class="form-floating">
                                                <select id="email_status" name="email_status" class="form-control" autocomplete="off">
                                                    <option value="1" <?=(e($data['get-email-details']['email_status']) == '1' ? ' selected' : '')?>>Active</option>
                                                    <option value="2" <?=(e($data['get-email-details']['email_status']) == '2' ? ' selected' : '')?>>Inactive</option>
                                                </select>
                                                <label for="email_status">Email Status</label>
                                            </div>
                                        </div>
                                        <!--end col-->

                                        <div class="col-xxl-12 col-md-12">
                                            <?=$this->token()?>
                                            <button id="editEmailBtn" class="btn btn-primary waves-effect waves-light w-100" data-email="<?=e($data['get-email-details']['id'])?>">Submit Form</button>
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
                            <!-- end card header -->
                            <div class="card-body">
                                <div class="live-preview">
                                    <div class="row gy-4">
                                        <div class="col-xxl-12 col-md-6">
                                            <?= html_entity_decode(e($data['get-email-details']['email_body'])) ?>
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