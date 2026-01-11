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
                                    <h4 class="display-6 coming-soon-text">Edit Profile</h4>
                                    <p class="text-success fs-15 mt-3">You have full control to manage and edit your account settings here.</p>
                                    <div class="hstack flex-wrap gap-2">
                                        <a href="#passwordModal" data-bs-toggle="modal" class="btn btn-primary btn-label rounded-pill">
                                            <i class="ri-lock-password-fill label-icon align-middle rounded-pill fs-16 me-2"></i>Reset Password
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end card body -->
                </div>
                <!-- end card -->

                <form id="profile-form">
                    <div class="row justify-content-evenly mb-2">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Edit Profile</h4>
                            </div>
                            
                            <!-- end card header -->
                            <div class="card-body">
                                <div class="live-preview">
                                    <div class="row gy-4">
                                        <div class="col-xxl-4 col-md-6">
                                            <div class="form-floating">
                                                <input type="file" name="photoimg" id="hidden-input" class="form-control">
                                                <label for="hidden-input">Profile Picture</label>
                                            </div>

                                            <img id="preview-image" class="img-thumbnail mt-4 mb-4" alt="Profile Picture" width="150" style="display: none;" src="">
                                        </div>
                                        <!--end col-->

                                        <div class="col-xxl-4 col-md-6">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="fullname" name="fullname" value="<?=e($data['admin']['name'])?>" autocomplete="off">
                                                <label for="fullname">Fullname</label>
                                            </div>
                                        </div>
                                        <!--end col-->

                                        <div class="col-xxl-4 col-md-6">
                                            <div class="form-floating">
                                                <input type="email" class="form-control" id="email" name="email" value="<?=e($data['admin']['email'])?>" autocomplete="off">
                                                <label for="email">Email Address</label>
                                            </div>
                                        </div>
                                        <!--end col-->
                                    </div>
                                    <!--end row-->
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-evenly mb-4">
                        <div class="card">
                            <div class="card-body">
                                <?=$this->token()?>
                                <button id="profileBtn" class="btn btn-primary waves-effect waves-light w-100">Update Profile</button>
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

<div id="passwordModal" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"  role="dialog" aria-labelledby="passwordBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="NotificationModalbtn-close"></button>
            </div>
            <div class="modal-body">
                <form id="password-form" class="forms-sample" role="form">
                    <h4>Reset Password</h4>
                    <p class="tx-color-03">Reset your password to a stronger one.</p>

                    <div class="form-floating mb-4">
                        <input type="password" class="form-control" name="oldPassword" id="oldPassword" placeholder="Enter old password" autocomplete="off">
                        <label for="oldPassword">Old Password</label>
                    </div>

                    <div class="form-floating mb-4">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter new password" autocomplete="off">
                        <label for="password">New Password</label>
                    </div>

                    <div class="form-floating mb-4">
                        <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Enter password confirm" autocomplete="off">
                        <label for="confirmPassword">Confirm Password</label>
                    </div>

                    <div class="hstack gap-2 justify-content-end">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <?=$this->token()?>
                        <button id="editPassword" class="btn btn-primary waves-effect waves-light w-100">Save Password</button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>