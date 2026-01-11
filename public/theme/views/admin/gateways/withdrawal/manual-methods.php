<?php
defined('FIR') OR exit();
/**
 * The template for displaying Home page content
 */
$WithdrawalMethodsPerPage = 8;?>

<div class="page-content">
    <div class="container-fluid">
        <div class="col-lg-12">
            <div class="card rounded-0 bg-success-subtle mx-n4 mt-n4 border-top">
                <div class="px-4">
                    <div class="row">
                        <div class="col-xxl-5 align-self-center">
                            <div class="py-4">
                                <h4 class="display-6 coming-soon-text">Withdrawal Methods</h4>
                                <p class="text-success fs-15 mt-3">From adding new methods to updating existing ones, streamline your withdrawal process with ease.</p>
                                <div class="hstack flex-wrap gap-2">
                                    <a href="#addWithdrawalMethodModal" data-bs-toggle="modal" class="btn btn-primary btn-label rounded-pill"><i class="ri-exchange-dollar-line label-icon align-middle rounded-pill fs-16 me-2"></i> Add Withdrawal Method</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end card body -->
            </div>
            <!-- end card -->

            <?php if (empty($data['gateways'])): ?>
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
                                            <h6 class="fs-18 fw-semibold lh-base">No Withdrawals gateways.</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div id="loadMoreWithdrawalMethodsContainer">
                    <div class="row gateways mb-4">
                        <?php foreach ($data['gateways'] as $gateway): ?>
                            <div class="col-xxl-3">
                                <div class="team-list row list-view-filter">
                                    <div class="col">
                                        <div class="card team-box">
                                            <div class="card-body">
                                                <div class="row align-items-center team-row">
                                                    <div class="col team-settings">
                                                        <div class="row">
                                                            <div class="col"></div>

                                                            <div class="col text-end dropdown">
                                                                <a href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false" class=""> 
                                                                    <i class="ri-more-fill fs-17"></i> 
                                                                </a>

                                                                <ul class="dropdown-menu dropdown-menu-end" style="">
                                                                    <li>
                                                                        <a class="dropdown-item edit-list" href="<?=$this->siteUrl()?>/admin/withdrawal_gateway/edit-method/<?=e($gateway['withdraw_code'])?>"><i class="ri-pencil-line me-2 align-bottom text-muted"></i>Edit</a>
                                                                    </li>

                                                                    <?php if($gateway["status"] == 1):?>
                                                                        <li>
                                                                            <button class="dropdown-item remove-list" data-bs-toggle="modal" data-bs-target="#deactivateMethodModal" data-id="<?= e($gateway['withdraw_code']) ?>">
                                                                                <i class="ri-close-circle-line me-2 align-bottom text-muted"></i>Deactivate
                                                                            </button>
                                                                        </li>
                                                                    <?php elseif($gateway["status"] == 2):?>
                                                                        <li>
                                                                            <button class="dropdown-item remove-list" data-bs-toggle="modal" data-bs-target="#activateMethodModal" data-id="<?= e($gateway['withdraw_code']) ?>">
                                                                                <i class="ri-checkbox-circle-line me-2 align-bottom text-muted"></i>Activate
                                                                            </button>
                                                                        </li>
                                                                    <?php endif; ?>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-4 col">
                                                        <div class="team-profile-img">
                                                            <div class="avatar-lg img-thumbnail rounded-circle flex-shrink-0">
                                                                <img src="<?=$this->siteUrl().'/'.PUBLIC_PATH.'/'.UPLOADS_PATH?>/withdrawal/<?=e($gateway['image'])?>" alt="" class="member-img img-fluid d-block rounded-circle" />
                                                            </div>

                                                            <div class="team-content">
                                                                <a class="member-name" data-bs-toggle="offcanvas" aria-controls="member-overview"> 
                                                                    <h5 class="fs-16 mb-1"><?=e($gateway['name'])?></h5> 
                                                                </a>

                                                                <p class="text-muted member-designation mb-0">
                                                                    <?php if($gateway["status"] == 1):?>
                                                                        <span class="badge bg-success-subtle text-approved">Active</span>
                                                                    <?php elseif($gateway["status"] == 2):?>
                                                                        <span class="badge bg-danger-subtle text-danger">Disabled</span>
                                                                    <?php endif; ?>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach;?>
                    </div>
                </div>
                <!--end row-->

                <?php if (count($data['gateways']) >= $WithdrawalMethodsPerPage): ?>
                <div class="text-center mb-3 mt-3">
                    <button class="btn btn-primary waves-effect waves-light loadMoreWithdrawalMethods" data-page="2">Load More </button>
                </div>
                <?php endif; ?>

                <div class="row d-none" id="WithdrawalMethodsLastpage">
                    <div class="offset-lg-3 col-lg-6 col-md-12 col-12 text-center mt-3"><p>You’ve reached the end of the list</p></div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <!-- container-fluid -->
</div>

<!-- addWithdrawalMethodModal -->
<div id="addWithdrawalMethodModal" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"  role="dialog" aria-labelledby="addWithdrawalMethodBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0">
            <form id="add-withdrawal-method-form">
                <div class="modal-body">
                    <div class="g-3">
                        <div class="col-lg-12">
                            <div class="px-1 pt-1">
                                <div class="modal-team-cover position-relative mb-0 mt-n4 mx-n4 rounded-top overflow-hidden">
                                    <img src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/admin/images/small/img-9.jpg" alt="" id="modal-cover-img" class="img-fluid">

                                    <div class="d-flex position-absolute start-0 end-0 top-0 p-3">
                                        <div class="flex-grow-1">
                                            <h5 class="modal-title text-white" id="exampleModalLabel">Add Withdrawal Method</h5>
                                        </div>

                                        <div class="flex-shrink-0">
                                            <div class="d-flex gap-3 align-items-center">
                                                <button type="button" class="btn-close btn-close-white"  id="close-jobListModal" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center mb-4 mt-n5 pt-2">
                                <div class="position-relative d-inline-block">
                                    <div class="position-absolute bottom-0 end-0">
                                        <label for="hidden-input" class="mb-0" data-bs-toggle="tooltip" data-bs-placement="right" title="Select Image">
                                            <div class="avatar-xs cursor-pointer">
                                                <div class="avatar-title bg-light border rounded-circle text-muted">
                                                    <i class="ri-image-fill"></i>
                                                </div>
                                            </div>
                                        </label>
                                        <input class="form-control d-none" type="file" name="photoimg" id="hidden-input">
                                    </div>

                                    <div class="avatar-lg p-1">
                                        <div class="avatar-title bg-light rounded-circle">
                                            <img src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/admin/images/users/multi-user.jpg" id="preview-image" class="avatar-md rounded-circle object-fit-cover" />
                                        </div>
                                    </div>
                                </div>
                                <h5 class="fs-13 mt-3">Withdrawal Method Image</h5>
                            </div>
                        </div>

                        <div class="row gy-4">
                            <div class="col-xxl-6 col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Method Name" autocomplete="off">
                                    <label for="name">Name</label>
                                </div>
                            </div>
                            <!--end col-->

                            <div class="col-xxl-6 col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="abbreviation" id="abbreviation" placeholder="Abbreviation" autocomplete="off">
                                    <label for="abbreviation">Abbreviation</label>
                                </div>
                            </div>
                            <!--end col-->

                            <div class="col-xxl-6 col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="min_amount" id="min_amount" placeholder="Minimum Amount" autocomplete="off">
                                    <label for="min_amount">Min Amount</label>
                                </div>
                            </div>
                            <!--end col-->

                            <div class="col-xxl-6 col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="max_amount" id="max_amount" placeholder="Maximum Amount" autocomplete="off">
                                    <label for="max_amount">Max Amount</label>
                                </div>
                            </div>
                            <!--end col-->

                            <div class="col-xxl-12 col-md-12">
                                <div class="form-floating">
                                    <select id="method_status" name="status" class="form-control" autocomplete="off">
                                        <option value="1">Active</option>
                                        <option value="2">Inactive</option>
                                    </select>
                                    <label for="method_status">Status</label>
                                </div>
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="hstack gap-2 justify-content-end">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <?=$this->token()?>
                        <button id="addWithdrawalMethodBtn" class="btn btn-primary waves-effect waves-light w-100">Submit Form</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end add modal-->

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
                        <p class="text-muted mx-4 mb-0">Are you sure you want to activate this Withdrawal Method ?</p>
                    </div>
                </div>

                <form id="activate-withdrawal-method-form">
                    <input type="hidden" id="activate-id" name="withdraw_code">
                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <?=$this->token()?>
                        <button id="activateWithdrawalMethodBtn" class="btn w-sm btn-danger">Yes, Activate</button>
                    </div>
                </form>
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
                        <p class="text-muted mx-4 mb-0">Are you sure you want to deactivate this Withdrawal Method ?</p>
                    </div>
                </div>

                <form id="deactivate-withdrawal-method-form">
                    <input type="hidden" id="deactivate-id" name="withdraw_code">
                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <?=$this->token()?>
                        <button id="deactivateWithdrawalMethodBtn" class="btn w-sm btn-danger">Yes, Deactivate</button>
                    </div>
                </form>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
