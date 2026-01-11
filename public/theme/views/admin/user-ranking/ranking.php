<?php
defined('FIR') OR exit();
/**
 * The template for displaying Home page content
 */
$RankingPerPage = 10;?>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card rounded-0 bg-success-subtle mx-n4 mt-n4 border-top">
                    <div class="px-4">
                        <div class="row">
                            <div class="col-xxl-5 align-self-center">
                                <div class="py-4">
                                    <h4 class="display-6 coming-soon-text">User Ranking</h4>
                                    <p class="text-success fs-15 mt-3">Stay in control and optimize the ranking system to foster engagement and reward user achievement.</p>
                                    <div class="hstack flex-wrap gap-2">
                                        <a href="<?=$this->siteUrl()?>/admin/dashboard" class="btn btn-primary btn-label rounded-pill">
                                            <i class="ri-arrow-go-back-fill label-icon align-middle rounded-pill fs-16 me-2"></i>Go Back
                                        </a>
                                        <a href="#rankModal" data-bs-toggle="modal" class="btn btn-info btn-label rounded-pill"><i class="ri-medal-fill label-icon align-middle rounded-pill fs-16 me-2"></i> Add Rank</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end card body -->
                </div>
                <!-- end card -->

                <?php if (empty($data['ranks'])): ?>
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
                                                <h6 class="fs-18 fw-semibold lh-base">You have added no ranks.</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="col-12" id="loadMoreRankingContainer">
                        <div class="row row-cols-xxl-5 row-cols-lg-3 row-cols-1 rank">
                            <?php foreach ($data['ranks'] as $rank): ?>
                                <div class="col">
                                    <div class="card card-body">
                                        <div class="d-flex mb-4 align-items-center">
                                            <div class="flex-shrink-0">
                                                <img src="<?=$this->siteUrl().'/'.PUBLIC_PATH.'/'.UPLOADS_PATH?>/ranks/<?=e($rank["icon"])?>" alt="" class="avatar-sm rounded-circle" />
                                            </div>

                                            <div class="flex-grow-1 ms-2">
                                                <h5 class="card-title mb-1"><?=e($rank["name"])?></h5>

                                                <p class="text-muted mb-0">
                                                    <?php if($rank["status"] == 1):?>
                                                        <span class="badge bg-success-subtle text-approved">Active</span>
                                                    <?php elseif($rank["status"] == 2):?>
                                                        <span class="badge bg-danger-subtle text-danger">Disabled</span>
                                                    <?php endif; ?>
                                                </p>
                                            </div>
                                        </div>

                                        <p class="card-text text-muted">Bonus - $<?=e($rank["bonus"])?></p>
                                        <h6 class="mb-1">Min Referral - <?=e($rank["min_referral"])?></h6>
                                        <h6 class="mb-1">Min Invest - $<?=e($rank["min_invest"])?></h6>
                                        
                                        <a href="<?=$this->siteUrl()?>/admin/ranking/edit-ranking/<?= e($rank['rankingId']) ?>" class="btn btn-primary waves-effect waves-light mt-3">Edit Rank</a>
                                    </div>
                                </div><!-- end col -->
                            <?php endforeach;?>
                        </div><!-- end row -->
                    </div>

                    <!-- end col -->
                    <?php if (count($data['ranks']) >= $RankingPerPage): ?>
                    <div class="text-center mb-3 mt-3">
                        <button class="btn btn-primary waves-effect waves-light loadMoreRanking" data-page="2">Load More </button>
                    </div>
                    <?php endif; ?>

                    <div class="row d-none" id="RankingLastpage">
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

<div id="rankModal" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"  role="dialog" aria-labelledby="rankBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0">
            <form id="add-rank-form">
                <div class="modal-body">
                    <div class="g-3">
                        <div class="col-lg-12">
                            <div class="px-1 pt-1">
                                <div class="modal-team-cover position-relative mb-0 mt-n4 mx-n4 rounded-top overflow-hidden">
                                    <img src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/admin/images/small/img-9.jpg" alt="" id="modal-cover-img" class="img-fluid">

                                    <div class="d-flex position-absolute start-0 end-0 top-0 p-3">
                                        <div class="flex-grow-1">
                                            <h5 class="modal-title text-white" id="exampleModalLabel">Add New Ranking</h5>
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
                                <h5 class="fs-13 mt-3">Rank Image</h5>
                            </div>
                        </div>

                        <div class="row gy-4">
                            <div class="col-xxl-6 col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Rank Name" autocomplete="off">
                                    <label for="name">Name</label>
                                </div>
                            </div>
                            <!--end col-->

                            <div class="col-xxl-6 col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="min_invest" id="min_invest" placeholder="Minimum Invest" autocomplete="off">
                                    <label for="min_invest">Min Invest</label>
                                </div>
                            </div>
                            <!--end col-->

                            <div class="col-xxl-6 col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="min_referral" id="min_referral" placeholder="Minimum Referral" autocomplete="off">
                                    <label for="min_referral">Min Referral</label>
                                </div>
                            </div>
                            <!--end col-->

                            <div class="col-xxl-6 col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="bonus" id="bonus" placeholder="Bonus" autocomplete="off">
                                    <label for="bonus">Bonus</label>
                                </div>
                            </div>
                            <!--end col-->

                            <div class="col-xxl-12 col-md-112">
                                <div class="form-floating">
                                    <select id="rank_status" name="status" class="form-control" autocomplete="off">
                                        <option value="1">Active</option>
                                        <option value="2">Inactive</option>
                                    </select>
                                    <label for="rank_status">Rank Status</label>
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
                        <button id="addRankBtn" class="btn btn-primary waves-effect waves-light w-100">Submit Form</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end add modal-->