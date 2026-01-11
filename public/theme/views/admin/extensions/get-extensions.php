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
                                    <h4 class="display-6 coming-soon-text">Extensions</h4>
                                    <p class="text-success fs-15 mt-3">You have full control to manage and edit your extensions here.</p>
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
                <?php if (empty($data['extensions'])): ?>
                    <div class="row">
                        <div class="card">
                            <div class="card-body">
                                <div class="text-center">
                                    <div class="tab-pane fade show active py-2 ps-2" id="all-noti-tab" role="tabpanel">
                                        <div class="empty-notification-elem">
                                            <div class="w-25 w-sm-50 pt-3 mx-auto">
                                                <img src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/admin/images/svg/bell.svg" class="img-fluid" alt="user-pic" />
                                            </div>
                                            
                                            <div class="text-center pb-5 mt-2">
                                                <h6 class="fs-18 fw-semibold lh-base">No extensions has been added.</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="row">
                        <div class="col-12">
                            <div class="row row-cols-xxl-5 row-cols-lg-3 row-cols-1">
                                <?php foreach ($data['extensions'] as $script): ?>
                                <div class="col">
                                    <div class="card card-body">
                                        <div class="d-flex mb-4 align-items-center">
                                            <div class="flex-shrink-0">
                                                <img src="<?=$this->siteUrl().'/'.PUBLIC_PATH.'/'.UPLOADS_PATH?>/extensions/<?=e($script["imagelocation"])?>" alt="" class="avatar-sm rounded-circle" />
                                            </div>
                                            <div class="flex-grow-1 ms-2">
                                                <h5 class="card-title mb-1"><?=e($script["name"])?></h5>

                                                <p class="text-muted mb-0">
                                                    <?php if($script["status"] == 1):?>
                                                        <span class="badge bg-success-subtle text-approved">Active</span>
                                                    <?php elseif($script["status"] == 2):?>
                                                        <span class="badge bg-danger-subtle text-danger">Disabled</span>
                                                    <?php endif; ?>
                                                </p>
                                            </div>
                                        </div>
                                        <h6 class="mb-1">Added</h6>

                                        <p class="card-text text-muted"><?= e(date('M d, Y h:iA', strtotime($script["created_at"]))) ?></p>

                                        <a href="<?=$this->siteUrl()?>/admin/extensions/edit-extension/<?= e($script['id']) ?>" class="btn btn-primary waves-effect waves-light">Edit Extension</a>
                                    </div>
                                </div><!-- end col -->
                                <?php endforeach;?>
                            </div><!-- end row -->
                        </div><!-- end col -->
                    </div>
                <?php endif; ?>
            </div>
            <!--end col-->
        </div>
        <!--end row-->
    </div>
    <!-- container-fluid -->
</div>