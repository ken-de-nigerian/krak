<?php
defined('FIR') OR exit();
/**
 * The template for displaying Example Create page
 */
$TemplatesPerPage = 8;?>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card rounded-0 bg-success-subtle mx-n4 mt-n4 border-top">
                    <div class="px-4">
                        <div class="row">
                            <div class="col-xxl-5 align-self-center">
                                <div class="py-4">
                                    <h4 class="display-6 coming-soon-text">Email Templates</h4>
                                    <p class="text-success fs-15 mt-3">You have full control to manage and edit your email templates here.</p>
                                    <div class="hstack flex-wrap gap-2">
                                        <a href="<?=$this->siteUrl()?>/admin/dashboard" class="btn btn-primary btn-label rounded-pill">
                                            <i class="ri-arrow-go-back-fill label-icon align-middle rounded-pill fs-16 me-2"></i>Go Back
                                        </a>
                                        <a href="#emailModal" data-bs-toggle="modal" class="btn btn-info btn-label rounded-pill"><i class="ri-mail-line label-icon align-middle rounded-pill fs-16 me-2"></i> Send Test Email</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end card body -->
                </div>
                <!-- end card -->
                <?php if (empty($data['templates'])): ?>
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
                                                <h6 class="fs-18 fw-semibold lh-base">You have added no email templates.</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div id="loadMoreTemplatesContainer">
                        <div class="template row">
                            <?php foreach ($data['templates'] as $email): ?>
                                <div class="col-xl-3">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="card-title mb-0"><i class="ri-mail-line align-middle me-1 lh-1"></i><?=e($email["name"])?></h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-muted">
                                                Subject - <?=e($email["subject"])?>
                                            </p>
                                        </div>

                                        <div class="card-footer">
                                            <div class="hstack gap-2 justify-content-end">
                                                <a href="<?=$this->siteUrl()?>/admin/templates/edit-template/<?= e($email['id']) ?>" class="btn btn-link btn-sm link-success"><i class="ri-pencil-fill align-middle lh-1"></i> Edit</a>

                                                <?php if($email["email_status"] == 1):?>
                                                    <span class="badge bg-success-subtle text-approved">Active</span>
                                                <?php elseif($email["email_status"] == 2):?>
                                                    <span class="badge bg-danger-subtle text-danger">Disabled</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach;?>
                        </div>
                    </div>
                    
                    <?php if (count($data['templates']) >= $TemplatesPerPage): ?>
                    <div class="text-center mb-3 mt-3">
                        <button class="btn btn-primary waves-effect waves-light loadMoreTemplates" data-page="2">Load More </button>
                    </div>
                    <?php endif; ?>

                    <div class="row d-none" id="TemplatesLastpage">
                        <div class="offset-lg-3 col-lg-6 col-md-12 col-12 text-center mt-3"><p>You’ve reached the end of the list</p></div>
                    </div>
                <?php endif; ?>
            </div>
            <!--end col-->
        </div>
        <!--end row-->
    </div>
    <!-- container-fluid -->
</div>

<div id="emailModal" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"  role="dialog" aria-labelledby="emailBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="NotificationModalbtn-close"></button>
            </div>
            <div class="modal-body">
                <form id="email-form">
                    <h4>Send Test Email</h4>
                    <p class="tx-color-03">Test your email configurations here.</p>
                    <div class="form-floating mb-4">
                        <input type="email" class="form-control" name="email" id="email" placeholder="Enter email address" autocomplete="off">
                        <label for="email">Email Address</label>
                    </div>

                    <div class="hstack gap-2 justify-content-end">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <?=$this->token()?>
                        <button id="sendEmail" class="btn btn-primary waves-effect waves-light w-100">Send Email</button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>