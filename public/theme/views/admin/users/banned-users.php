<?php
defined('FIR') OR exit();
/**
 * The template for displaying Home page content
 */
$BannedUsersPerPage = 8;?>
<?php 
    function getRelativeTime($date) {
        $now = new DateTime();
        $Date = new DateTime($date);
        $interval = $Date->diff($now);

        if ($interval->y >= 1) {
            $years = $interval->y;
            $suffix = ($years === 1) ? 'year' : 'years';
            return $years . ' ' . $suffix . ' ago';
        } elseif ($interval->m >= 1) {
            $months = $interval->m;
            $suffix = ($months === 1) ? 'month' : 'months';
            return $months . ' ' . $suffix . ' ago';
        } elseif ($interval->d >= 1) {
            $days = $interval->d;
            $suffix = ($days === 1) ? 'day' : 'days';
            return $days . ' ' . $suffix . ' ago';
        } elseif ($interval->h >= 1) {
            $hours = $interval->h;
            $suffix = ($hours === 1) ? 'hour' : 'hours';
            return $hours . ' ' . $suffix . ' ago';
        } elseif ($interval->i >= 1) {
            $minutes = $interval->i;
            $suffix = ($minutes === 1) ? 'minute' : 'minutes';
            return $minutes . ' ' . $suffix . ' ago';
        } else {
            $seconds = $interval->s;
            $suffix = ($seconds === 1) ? 'second' : 'seconds';
            return $seconds . ' ' . $suffix . ' ago';
        }
    }
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
                                    <h4 class="display-6 coming-soon-text">Banned Users</h4>
                                    <p class="text-success fs-15 mt-3">Effortlessly manage users with powerful administrative tools, stay in control of your platform's user base.</p>
                                    <div class="hstack flex-wrap gap-2">
                                        <a href="<?=$this->siteUrl()?>/admin/dashboard" class="btn btn-primary btn-label rounded-pill">
                                            <i class="ri-arrow-go-back-fill label-icon align-middle rounded-pill fs-16 me-2"></i>Go Back
                                        </a>
                                        <a href="<?=$this->siteUrl()?>/admin/users/add-user" class="btn btn-info btn-label rounded-pill"><i class="ri-account-pin-circle-fill label-icon align-middle rounded-pill fs-16 me-2"></i> Register User</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end card body -->
                </div>
                <!-- end card -->

                <?php if (empty($data['users'])): ?>
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
                                                <h6 class="fs-18 fw-semibold lh-base">No account found.</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="card">
                        <div class="card-body">
                            <form class="row g-2" action="<?=$this->siteUrl()?>/admin/banned" method="GET">
                                <div class="col-sm-4">
                                    <div class="search-box">
                                        <input class="form-control" type="search" name="search" placeholder="Search with only firstname, lastname or email..." autocomplete="off">
                                        <i class="ri-search-line search-icon"></i>
                                    </div>
                                </div>

                                <!--end col-->
                                <div class="col-sm-auto ms-auto">
                                    <div class="list-grid-nav hstack gap-1">
                                        <button type="button" id="dropdownMenuLink1" data-bs-toggle="dropdown" aria-expanded="false" class="btn btn-soft-info btn-icon material-shadow-none fs-14"><i class="ri-more-2-fill"></i></button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink1">
                                            <li><a class="dropdown-item" href="<?=$this->siteUrl()?>/admin/users">All</a></li>
                                            <li><a class="dropdown-item" href="<?=$this->siteUrl()?>/admin/active">Active Users</a></li>
                                            <li><a class="dropdown-item active" href="<?=$this->siteUrl()?>/admin/banned">Banned Users</a></li>
                                            <li><a class="dropdown-item" href="<?=$this->siteUrl()?>/admin/kyc_unverified">Kyc Unverified</a></li>
                                            <li><a class="dropdown-item" href="<?=$this->siteUrl()?>/admin/kyc_pending">Kyc Pending</a></li>
                                        </ul>
                                        <button type="submit" class="btn btn-primary w-100"><i class="ri-search-2-line me-1 align-bottom"></i> Search</button>
                                    </div>
                                </div>
                                <!--end col-->
                            </form>
                            <!--end row-->
                        </div>
                    </div>

                    <div id="loadMoreBannedUsersContainer">
                        <div class="row job-list-row">
                            <?php foreach ($data['users'] as $user): ?>
                            <div class="col-xxl-3 col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <div class="avatar-lg rounded">
                                                    <?php if($user['imagelocation'] == "default.png"):?>
                                                        <div class="avatar-title border bg-light text-primary rounded text-uppercase fs-24"><?= e(substr($user["firstname"], 0, 1)) ?><?= e(substr($user["lastname"], 0, 1)) ?></div>
                                                    <?php else: ?>
                                                        <img src="<?=$this->siteUrl().'/'.PUBLIC_PATH.'/'.UPLOADS_PATH?>/users/<?=e($user["imagelocation"])?>" alt="" class="member-img img-fluid d-block rounded" />
                                                    <?php endif; ?>
                                                </div>
                                            </div>

                                            <div class="flex-grow-1 ms-3">
                                                <a href="<?=$this->siteUrl()?>/admin/users/view-profile/<?=e($user["userid"])?>"> 
                                                    <h5 class="fs-16 mb-1"><?=e($user["firstname"])?> <?=e($user["lastname"])?></h5> 
                                                </a>

                                                <p class="text-muted mb-2"><?=e($user["email"])?></p>

                                                <div class="d-flex flex-wrap gap-2 align-items-center">
                                                    <?php if($user["status"] == 1):?>
                                                        <span class="badge bg-success-subtle text-approved">Active</span>
                                                    <?php elseif($user["status"] == 2):?>
                                                        <span class="badge bg-danger-subtle text-danger">Blocked</span>
                                                    <?php endif; ?>
                                                </div>

                                                <div class="d-flex gap-4 mt-2 text-muted">
                                                    <div>
                                                        <i class="ri-map-pin-2-line text-primary me-1 align-bottom"></i> <?=e($user["country"])?>
                                                    </div>

                                                    <div>
                                                        <i class="ri-time-line text-primary me-1 align-bottom"></i><?=e(getRelativeTime($user['registration_date']))?>
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

                    <!-- end col -->
                    <?php if (count($data['users']) >= $BannedUsersPerPage): ?>
                    <div class="text-center mb-3 mt-3">
                        <button class="btn btn-primary waves-effect waves-light loadMoreBannedUsers" data-page="2">Load More </button>
                    </div>
                    <?php endif; ?>

                    <div class="row d-none" id="BannedUsersLastpage">
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