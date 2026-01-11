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
                                    <h4 class="display-6 coming-soon-text">Time Configurations</h4>
                                    <p class="text-success fs-15 mt-3">You have full control to monitor all investment times on your site.</p>
                                    <div class="hstack flex-wrap gap-2">
                                        <a href="<?=$this->siteUrl()?>/admin/dashboard" class="btn btn-primary btn-label rounded-pill">
                                            <i class="ri-arrow-go-back-fill label-icon align-middle rounded-pill fs-16 me-2"></i>Go Back
                                        </a>
                                        <div class="hstack flex-wrap gap-2">
                                            <a href="#addTimeModal" data-bs-toggle="modal" class="btn btn-info btn-label rounded-pill"><i class="ri-exchange-dollar-line label-icon align-middle rounded-pill fs-16 me-2"></i> Add New Time</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end card body -->
                </div>
                <!-- end card -->

                <?php if (empty($data['times'])): ?>
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
                                                <h6 class="fs-18 fw-semibold lh-base">No times configurations found.</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="row row-cols-xxl-5 row-cols-lg-3 row-cols-1">
                        <?php foreach ($data['times'] as $time): ?>
                            <div class="col">
                                <div class="card card-body">
                                    <h6 class="mb-1"><?=e($time['name'])?></h6>
                                    <p class="card-text text-muted"><?= date('d-m-Y h:i A', strtotime($time['created_at'])) ?></p>

                                    <button data-bs-toggle="modal" data-bs-target="#editTimeModal" data-id="<?= e($time['id']) ?>" data-name="<?= e($time['name']) ?>" data-time="<?= e($time['time']) ?>" class="btn btn-primary btn-sm"> <i class="ri-edit-fill align-bottom me-2"></i> Edit </button>
                                </div>
                            </div>
                            <!-- end col -->
                        <?php endforeach ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <!--end row-->
    </div>
    <!-- container-fluid -->
</div>
<!-- End Page-content -->

<!-- addTimeModal -->
<div id="addTimeModal" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"  role="dialog" aria-labelledby="addTimeBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="NotificationModalbtn-close"></button>
            </div>
            <div class="modal-body">
                <h4>Add New Time</h4>
                <p class="tx-color-03">You have full control to add and edit time configurations.</p>
                <!-- Tab panes -->
                <form id="add-time-form">
                    <div class="form-floating mb-4">
                        <input type="text" class="form-control" id="add-name" name="name" placeholder="Enter time name" autocomplete="off">
                        <label for="add-name">Time</label>
                    </div>

                    <div class="form-floating mb-4">
                        <input type="text" class="form-control" id="add-hours" name="hours" placeholder="Enter time in hours" autocomplete="off">
                        <label for="add-hours">Time In Hours</label>
                    </div>

                    <div class="hstack gap-2 justify-content-end">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <?=$this->token()?>
                        <button id="addTimeBtn" class="btn btn-primary waves-effect waves-light w-100">Submit Form</button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<!-- editTimeModal -->
<div id="editTimeModal" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"  role="dialog" aria-labelledby="editTimeBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="NotificationModalbtn-close"></button>
            </div>
            <div class="modal-body">
                <h4>Edit Time</h4>
                <p class="tx-color-03">You have full control to add and edit time configurations.</p>
                <!-- Tab panes -->
                <form id="edit-time-form">
                    <input type="hidden" class="form-control" id="timeId" name="timeId">
                    <div class="form-floating mb-4">
                        <input type="text" class="form-control" id="edit-name" name="name" autocomplete="off">
                        <label for="edit-name">Time</label>
                    </div>

                    <div class="form-floating mb-4">
                        <input type="text" class="form-control" id="edit-hours" name="hours" autocomplete="off">
                        <label for="edit-hours">Time In Hours</label>
                    </div>

                    <div class="hstack gap-2 justify-content-end">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <?=$this->token()?>
                        <button id="editTimeBtn" class="btn btn-primary waves-effect waves-light w-100">Submit Form</button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>