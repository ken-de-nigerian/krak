<?php
defined('FIR') OR exit();
/**
 * The template for displaying Home page content
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
                                    <h4 class="display-6 coming-soon-text">Identity Proof</h4>
                                    <p class="text-success fs-15 mt-3">Effortlessly manage users kyc status, stay in control of your platform's user base.</p>
                                    <div class="hstack flex-wrap gap-2">
                                        <a href="<?=$this->siteUrl()?>/admin/users/view-profile/<?=e($data['user']["userid"])?>" class="btn btn-primary btn-label rounded-pill">
                                            <i class="ri-arrow-go-back-fill label-icon align-middle rounded-pill fs-16 me-2"></i>Go Back
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end card body -->
                </div>

                <div class="row">
                    <div class="col-xl-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex align-items-center">
                                    <h5 class="card-title flex-grow-1 mb-0">KYC Data</h5>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="text-muted">
                                    <div class="">
                                        <div class="row gy-3">
                                            <div class="col-lg-3 col-sm-6">
                                                <div>
                                                    <p class="mb-2 text-uppercase fw-medium">Added Date :</p>
                                                    <h5 class="fs-15 mb-0"><?= e(date('d M, Y', strtotime($data['get-identity-proof']["date_added"]))) ?></h5>
                                                </div>
                                            </div>

                                            <div class="col-lg-3 col-sm-6">
                                                <div>
                                                    <p class="mb-2 text-uppercase fw-medium">Identity Type :</p>
                                                    <h5 class="fs-15 mb-0">
                                                        <?php if($data['get-identity-proof']['identity_type'] == 1):?>
                                                            National ID
                                                        <?php elseif($data['get-identity-proof']['identity_type'] == 2):?>
                                                            Driving License
                                                        <?php elseif($data['get-identity-proof']['identity_type'] == 3):?>
                                                            Passport
                                                        <?php endif; ?>
                                                    </h5>
                                                </div>
                                            </div>

                                            <div class="col-lg-3 col-sm-6">
                                                <div>
                                                    <p class="mb-2 text-uppercase fw-medium">Document Number :</p>
                                                    <h5 class="fs-15 mb-0">
                                                        <?=e($data['get-identity-proof']["identity_number"])?>
                                                    </h5>
                                                </div>
                                            </div>

                                            <div class="col-lg-3 col-sm-6">
                                                <div>
                                                    <p class="mb-2 text-uppercase fw-medium">File Type :</p>
                                                    <div class="badge bg-light fs-12"><?=e($data['get-identity-proof']["type"])?></div>
                                                </div>
                                            </div>

                                            <div class="col-lg-3 col-sm-6">
                                                <div>
                                                    <p class="mb-2 text-uppercase fw-medium">Status :</p>
                                                    <?php if($data['get-identity-proof']['status'] == 1):?>
                                                        <div class="badge bg-success fs-12">Approved</div>
                                                    <?php elseif($data['get-identity-proof']['status'] == 2):?>
                                                        <div class="badge bg-warning fs-12">Pending</div>
                                                    <?php elseif($data['get-identity-proof']['status'] == 3):?>
                                                        <div class="badge bg-danger fs-12">Rejected</div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="pt-3 border-top border-top-dashed mt-4">
                                        <h6 class="mb-3 fw-semibold text-uppercase">Resources</h6>
                                        <div class="row g-3">
                                            <div class="col-xxl-3 col-lg-3">
                                                <div class="border rounded border-dashed p-2">
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0 me-3">
                                                            <div class="avatar-sm">
                                                                <div class="avatar-title bg-light text-secondary rounded fs-24">
                                                                    <i class="ri-folder-zip-line"></i>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <h5 class="fs-13 mb-1">
                                                                <a href="<?=$this->siteUrl().'/'.PUBLIC_PATH.'/'.UPLOADS_PATH?>/identity-proof/<?=e($data['get-identity-proof']["fileupload"])?>" class="text-body text-truncate d-block" target="_blank"><?=e($data['get-identity-proof']["fileupload"])?></a>
                                                            </h5>
                                                            <div><?=e($data['get-identity-proof']["size"])?>KB</div>
                                                        </div>

                                                        <div class="flex-shrink-0 ms-2">
                                                            <div class="d-flex gap-1">
                                                                <a href="<?=$this->siteUrl().'/'.PUBLIC_PATH.'/'.UPLOADS_PATH?>/identity-proof/<?=e($data['get-identity-proof']["fileupload"])?>" download class="btn btn-icon text-muted btn-sm fs-18"><i class="ri-download-2-line"></i></a>

                                                                <?php if($data['get-identity-proof']['status'] == 2 || $data['get-identity-proof']['status'] == 3):?>
                                                                    <div class="dropdown">
                                                                        <button class="btn btn-icon text-muted btn-sm fs-18 dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                            <i class="ri-more-fill"></i>
                                                                        </button>

                                                                        <ul class="dropdown-menu" style="">
                                                                            <li>
                                                                                <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#activateMethodModal"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Accept</button>
                                                                            </li>

                                                                            <li>
                                                                                <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#deactivateMethodModal"><i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Reject</button>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end row -->
                                    </div>
                                </div>
                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->
                    </div>
                </div>
                <!--end col-->
            </div>
        </div>
        <!--end row-->
    </div>
    <!-- container-fluid -->
</div>
<!-- End Page-content -->

<!-- activateMethodModal -->
<div id="activateMethodModal" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"  role="dialog" aria-labelledby="activateBackdropLabel" aria-hidden="true">
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
                        <p class="text-muted mx-4 mb-0">Are you sure you want to approve this Submission ?</p>
                    </div>
                </div>

                <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button id="activateIdentityBtn" class="btn w-sm btn-danger" data-uploadid="<?= e($data['get-identity-proof']['uploadid']) ?>" data-userid="<?= e($data['user']['userid']) ?>">Yes, Activate</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- deactivateMethodModal -->
<div id="deactivateMethodModal" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"  role="dialog" aria-labelledby="deactivateBackdropLabel" aria-hidden="true">
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
                        <p class="text-muted mx-4 mb-0">Are you sure you want to reject this Submission ?</p>
                    </div>
                </div>

                <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button id="rejectIdentityBtn" class="btn w-sm btn-danger" data-uploadid="<?= e($data['get-identity-proof']['uploadid']) ?>" data-userid="<?= e($data['user']['userid']) ?>">Yes, Reject</button>
                </div>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->