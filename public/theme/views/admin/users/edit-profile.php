<?php
defined('FIR') OR exit();
/**
 * The template for displaying Home page content
 */
?>
<style>
    .iti__country-list {
        background-color: #262a2f !important;
    }
</style>
<div class="page-content">
    <div class="container-fluid">
        <div class="position-relative mx-n4 mt-n4">
            <div class="profile-wid-bg profile-setting-img"></div>
        </div>

        <div class="row">
            <div class="col-xxl-3">
                <div class="card mt-n5">
                    <div class="card-body p-4">
                        <div class="text-center">
                            <div class="profile-user position-relative d-inline-block mx-auto mb-4">
                                <img src="<?=$this->siteUrl().'/'.PUBLIC_PATH.'/'.UPLOADS_PATH?>/users/<?=e($data['user']["imagelocation"])?>" class="rounded-circle avatar-xl img-thumbnail user-profile-image material-shadow" alt="user-profile-image" id="preview-image">

                                <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                    <input type="hidden" id="file_name">
                                    <input type="hidden" id="userid" name="userid" value="<?=e($data['user']["userid"])?>">
                                    <input type="file" name="photoimg" id="upload" class="profile-img-file-input" />
                                    <?=$this->token()?>
                                    <label for="upload" class="profile-photo-edit avatar-xs">
                                        <span class="avatar-title rounded-circle bg-light text-body material-shadow">
                                            <i class="ri-camera-fill"></i>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <h5 class="fs-16 mb-1"><?=e($data['user']["firstname"])?> <?=e($data['user']["lastname"])?></h5>
                            <p class="text-muted mb-0"><?=e($data['user']["email"])?></p>
                        </div>
                    </div>
                </div>

                <!--end card-->
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h5 class="card-title flex-grow-1 mb-0">Profile Progress</h5>
                        </div>
                    </div>

                    <div class="card-body">
                        <?php
                            // Assuming $data['user'] contains user data
                            $user = $data['user'];

                            // Calculate completion percentage
                            $totalFields = 4; // Assuming there are 4 fields to be filled
                            $completedFields = 0;

                            // Check each field and count completed fields
                            if (!empty($user["address_1"])) $completedFields++;
                            if (!empty($user["country"])) $completedFields++;
                            if (!empty($user["city"])) $completedFields++;
                            if (!empty($user["state"])) $completedFields++;

                            // Calculate completion percentage
                            $completionPercentage = ceil(min(100, ($completedFields / $totalFields) * 100));
                        ?>

                        <div class="progress animated-progress custom-progress progress-label">
                            <div class="progress-bar bg-danger" role="progressbar" style="width: <?= $completionPercentage ?>%" aria-valuenow="<?= $completionPercentage ?>" aria-valuemin="0" aria-valuemax="100">
                                <div class="label"><?= $completionPercentage ?>%</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <input id="referralURL" type="text" value="<?=$this->siteUrl()?>/register/?ref=<?=e($data['user']['userid'])?>" style="display: none;">
                        <a onclick="copyToClipboard(document.getElementById('referralURL'))" class="btn w-sm btn-soft-success w-100"><i class="ri-links-line align-bottom"></i> Copy Referral Link</a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h5 class="card-title flex-grow-1 mb-0">Account Information</h5>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <th class="ps-0" scope="row">Full Name :</th>
                                        <td class="text-muted"><?=e($data['user']["firstname"])?> <?=e($data['user']["lastname"])?></td>
                                    </tr>

                                    <tr>
                                        <th class="ps-0" scope="row">Mobile :</th>
                                        <td class="text-muted"><?=e($data['user']["phone"])?></td>
                                    </tr>

                                    <tr>
                                        <th class="ps-0" scope="row">E-mail :</th>
                                        <td class="text-muted"><?=e($data['user']["email"])?></td>
                                    </tr>

                                    <tr>
                                        <th class="ps-0" scope="row">Country :</th>
                                        <td class="text-muted"><?=e($data['user']["country"])?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th class="ps-0" scope="row">Referred By</th>
                                        <td class="text-muted">
                                            <span class="badge badge-label bg-primary">
                                                <i class="mdi mdi-circle-medium"></i>
                                                <?php if (!empty($data['referrer']["userid"])): ?>
                                                    <a href="<?= $this->siteUrl() ?>/admin/users/view-profile/<?= e($data['referrer']["userid"]) ?>"> 
                                                        <?= !empty($data["referrer"]["firstname"]) || !empty($data["referrer"]["lastname"]) ? e($data["referrer"]["firstname"]) . ' ' . e($data["referrer"]["lastname"]) : "None" ?>
                                                    </a>
                                                <?php else: ?>
                                                    None
                                                <?php endif; ?>
                                            </span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th class="ps-0" scope="row">Registration Date</th>
                                        <td class="text-muted"><?= date('d M Y h:i A', strtotime($data['user']['registration_date'])) ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div><!-- end card body -->
                </div><!-- end card -->
            </div>
            <!--end col-->

            <div class="col-xxl-9">
                <form id="edit-user-form">
                    <input type="hidden" id="formattedPhone" name="formattedPhone" value="<?=e($data['user']['phone'])?>">
                    <div class="card mt-xxl-n5 mb-4">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <h5 class="card-title flex-grow-1 mb-0">General</h5>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Enter firstname" value="<?=e($data["user"]["firstname"])?>" autocomplete="off">
                                            <label for="firstname">Firstname</label>
                                        </div>
                                    </div>
                                </div>

                                <!--end col-->
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Enter lastname" value="<?=e($data["user"]["lastname"])?>" autocomplete="off">
                                            <label for="lastname">Lastname</label>
                                        </div>
                                    </div>
                                </div>

                                <!--end col-->
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <div class="form-floating">
                                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email address" value="<?=e($data["user"]["email"])?>" autocomplete="off" readonly>
                                            <label for="email">Email</label>
                                        </div>
                                    </div>
                                </div>

                                <!--end col-->
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <div class="form-floating">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="phone" value="<?=e($data["user"]["phone"])?>" autocomplete="off" style="padding: 1.2rem 3.5rem;" readonly>
                                                <label for="phone"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end row-->
                        </div>
                    </div>

                    <div class="card mt-xxl-n5">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <h5 class="card-title flex-grow-1 mb-0">Contact</h5>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <div class="row">
                                <!--end col-->
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="country" name="country" placeholder="Enter country" value="<?=e($data["user"]["country"])?>" autocomplete="off" readonly>
                                            <label for="country">Country</label>
                                        </div>
                                    </div>
                                </div>

                                <!--end col-->
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="city" name="city" placeholder="Enter city" value="<?=e($data["user"]["city"])?>" autocomplete="off">
                                            <label for="city">City</label>
                                        </div>
                                    </div>
                                </div>

                                <!--end col-->
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="state" name="state" placeholder="Enter state" value="<?=e($data["user"]["state"])?>" autocomplete="off">
                                            <label for="state">State</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <div class="form-floating">
                                            <textarea class="form-control" id="address_1" name="address_1" autocomplete="off" placeholder="Enter address" rows="3" style="height: 100px;"><?=e($data["user"]["address_1"])?></textarea>
                                            <label for="address_1">Address 1</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <div class="form-floating">
                                            <textarea class="form-control" id="address_2" name="address_2" autocomplete="off" placeholder="Enter address" rows="3" style="height: 100px;"><?=e($data["user"]["address_2"])?></textarea>
                                            <label for="address_2">Address 2</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-xxl-n5">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <h5 class="card-title flex-grow-1 mb-0">Currency</h5>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <div class="row">
                                <!--end col-->
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <div class="form-floating">
                                            <select id="currency" name="currency" class="form-control" autocomplete="off">
                                                <option value="&#x24;" <?=(e($data['user']['currency']) == '$' ? ' selected' : '')?>>USD</option>
                                                <option value="&#x20AC;" <?=(e($data['user']['currency']) == '€' ? ' selected' : '')?>>EUR</option>
                                                <option value="&#xA3;" <?=(e($data['user']['currency']) == '£' ? ' selected' : '')?>>GBP</option>
                                            </select>
                                            <label for="currency">Preferred Currency</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-xxl-n5">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <h5 class="card-title flex-grow-1 mb-0">Settings</h5>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <div class="row">
                                <!--end col-->
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <div class="form-floating">
                                            <select id="account_verify" name="account_verify" class="form-control" autocomplete="off">
                                                <option value="1" <?=(e($data['user']['account_verify']) == '1' ? ' selected' : '')?>>Verified</option>
                                                <option value="2" <?=(e($data['user']['account_verify']) == '2' ? ' selected' : '')?>>Pending</option>
                                                <option value="3" <?=(e($data['user']['account_verify']) == '3' ? ' selected' : '')?>>Unverified</option>
                                            </select>
                                            <label for="account_verify">KYC STATUS</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <div class="form-floating">
                                            <select id="twofactor_status" name="twofactor_status" class="form-control" autocomplete="off">
                                                <option value="1" <?=(e($data['user']['twofactor_status']) == '1' ? ' selected' : '')?>>Active</option>
                                                <option value="2" <?=(e($data['user']['twofactor_status']) == '2' ? ' selected' : '')?>>Disabled</option>
                                            </select>
                                            <label for="twofactor_status">2FA STATUS</label>
                                        </div>
                                    </div>
                                </div>

                                <!--end col-->
                                <div class="col-lg-12">
                                    <div class="hstack gap-2 justify-content-end">
                                        <?=$this->token()?>
                                        <button id="editUserBtn" class="btn btn-primary" data-id="<?= e($data['user']['userid']) ?>">Update Profile</button>
                                        <a href="<?=$this->siteUrl()?>/admin/users/view-profile/<?=e($data['user']["userid"])?>" class="btn btn-soft-success">Cancel</a>
                                    </div>
                                </div>
                                <!--end col-->
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
<!-- End Page-content -->