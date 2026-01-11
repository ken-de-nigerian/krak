<?php
defined('FIR') OR exit();
/**
 * The template for displaying Example Create page
 */
?>
<div class="position-relative">
    <div class="containt-parent">
        <div class="main-containt">
            <!-- main-containt -->
            <div class="bg-white pxy-62 exchange pt-62 shadow" id="twofaVerification">
                <p class="mb-0 f-26 gilroy-Semibold text-uppercase text-center">2FA Settings</p>
                <div class="row">
                    <div class="col-12">
                        <nav>
                            <div class="nav-tab-parent d-flex justify-content-center mt-4">
                                <div class="d-flex p-2 border-1p rounded-pill gap-1 bg-white nav-tab-child">
                                    <a href="<?=$this->siteUrl()?>/user/verifications/personal-id" class="tablink-edit text-gray-100">Identity Verification</a>
                                    <a href="<?=$this->siteUrl()?>/user/verifications/personal-address" class="tablink-edit text-gray-100">Address Verfication</a>
                                    
                                    <?php if ($data['settings']['twofa_status'] == 1): ?>
                                    <a href="<?=$this->siteUrl()?>/user/verifications/two-factor" class="tablink-edit text-gray-100 tabactive">2fa Authentication</a>
                                    <?php endif ?>
                                </div>
                            </div>
                        </nav>

                        <div class="mt-32 responsive-size" id="section_2fa_form">
                            <form method="post" class="form-horizontal mt-2" id="2fa-form">
                                <div class="mt-28 param-ref">
                                    <label class="gilroy-medium text-gray-100 mb-2 f-15">2-factor Authentication Status</label>
                                    <div class="avoid-blink">
                                        <select class="select2" data-minimum-results-for-search="Infinity" name="twofactor_status" id="twofactor_status">
                                            <option value='2' <?=(e($data['user']['twofactor_status']) == '2' ? ' selected' : '')?>>Disabled</option>
                                            <option value='1' <?=(e($data['user']['twofactor_status']) == '1' ? ' selected' : '')?>>Enabled</option>
                                        </select>
                                        <span class="error" id="2fa-error"></span>
                                    </div>
                                </div>

                                <div class="d-grid mt-3">
                                    <?=$this->token()?>
                                    <button type="submit" class="btn btn-primary px-4 py-2 mt-3" id="2faBtn">
                                        <span>Submit</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- main-containt -->
        </div>
    </div>
</div>