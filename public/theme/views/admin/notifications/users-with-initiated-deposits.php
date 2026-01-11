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
                                    <h4 class="display-6 coming-soon-text">Send Email - Users With Initiated Deposits</h4>
                                    <p class="text-success fs-15 mt-3">Stay connected and keep your users informed with personalized emails.</p>
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

                <form id="users-with-initiated-deposits-form">
                    <div class="row justify-content-evenly mb-2">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Notification To Users With Initiated Deposits</h4>
                                <div class="col-sm-auto ms-auto">
                                    <div class="list-grid-nav hstack gap-1">
                                        <button type="button" id="dropdownMenuLink1" data-bs-toggle="dropdown" aria-expanded="false" class="btn btn-soft-info btn-icon material-shadow-none fs-14"><i class="ri-more-2-fill"></i></button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink1">
                                            <li><a class="dropdown-item" href="<?=$this->siteUrl()?>/admin/notifications">All</a></li>
                                            <li><a class="dropdown-item" href="<?=$this->siteUrl()?>/admin/notify/selected-users">Selected Users</a></li>
                                            <li><a class="dropdown-item" href="<?=$this->siteUrl()?>/admin/notify/kyc-unverified-users">Kyc Unverified Users</a></li>
                                            <li><a class="dropdown-item" href="<?=$this->siteUrl()?>/admin/notify/kyc-pending-users">Kyc Pending Users</a></li>
                                            <li><a class="dropdown-item" href="<?=$this->siteUrl()?>/admin/notify/users-with-empty-balance">Users With Empty Balance</a></li>
                                            <li><a class="dropdown-item active" href="<?=$this->siteUrl()?>/admin/notify/users-with-initiated-deposits">Users With Initiated Deposits</a></li>
                                            <li><a class="dropdown-item" href="<?=$this->siteUrl()?>/admin/notify/users-with-pending-deposits">Users With Pending Deposits</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- end card header -->
                            <div class="card-body">
                                <div class="live-preview">
                                    <div class="row gy-4">
                                        <div class="col-xxl-12 col-md-12">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="subject" name="subject" placeholder="Enter subject" autocomplete="off">
                                                <label for="subject">Subject</label>
                                            </div>
                                        </div>
                                        <!--end col-->

                                        <div class="col-xxl-12 col-md-12">
                                            <div class="form-floating">
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
                                                <input type="hidden" name="details" id="details" autocomplete="off">
                                                <span id="details-error"></span>
                                            </div>
                                        </div>
                                        <!--end col-->

                                        <div class="col-xxl-12 col-md-12">
                                            <?=$this->token()?>
                                            <button id="notifyUsersWithInitiatedDepositsBtn" class="btn btn-primary waves-effect waves-light w-100">Send Email</button>
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
        <!--end row-->
    </div>
    <!-- container-fluid -->
</div>
<!-- End Page-content -->