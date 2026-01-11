<?php
defined('FIR') OR exit();
/**
 * The template for displaying the footer section
 */
?>
<div class="container-fluid container-layout px-0 main-containt">
    <div class="main-auth-div" id="2faVerification">
        <div class="row">
            <div class="col-md-5 col-xl-5 hide-small-device">
                <div class="bg-pattern">
                    <div class="bg-content">
                        <div class="d-flex justify-content-start">
                            <div class="logo-div">
                                <a href="<?=$this->siteUrl()?>">
                                    <img src="<?=$this->siteUrl().'/'.PUBLIC_PATH.'/'.UPLOADS_PATH?>/logo/<?=e($this->siteSettings('logo'))?>" alt="Brand Logo" style="width: 200px;">
                                </a>
                            </div>
                        </div>

                        <div class="transaction-block">
                            <div class="transaction-text">
                                <h3 class="mb-6p">Hassle free money</h3>
                                <h1 class="mb-2p">Transactions</h1>
                                <h2>Right at you fingertips</h2>
                            </div>
                        </div>

                        <div class="transaction-image">
                            <div class="static-image">
                                <img class="img img-fluid" src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/frontend/templates/images/2fa/2fa-img.svg">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-7 col-12 col-xl-7">
                <div class="auth-section d-flex align-items-center">
                    <div class="auth-module">
                        <div class="auth-module-header">
                            <div class="d-flex align-items-center back-direction otp-top">
                                <a href="<?=$this->siteUrl()?>/login" class="d-inline-flex align-items-center back-btn">
                                    <svg class="position-relative" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M8.47075 10.4709C8.7311 10.2105 8.7311 9.78842 8.47075 9.52807L4.94216 5.99947L8.47075 2.47087C8.7311 2.21053 8.7311 1.78842 8.47075 1.52807C8.2104 1.26772 7.78829 1.26772 7.52794 1.52807L3.52795 5.52807C3.2676 5.78842 3.2676 6.21053 3.52795 6.47088L7.52794 10.4709C7.78829 10.7312 8.2104 10.7312 8.47075 10.4709Z" fill="currentColor"></path>
                                    </svg>
                                    <span class="ms-1">Back</span>
                                </a>
                            </div>

                            <p class="mb-0 text-start auth-title mt-20">
                                Two Factor Authentication (2FA) via: 
                                <?php if ($data['user']['twofactor_status'] == "1"): ?>
                                    Email
                                <?php endif ?>
                            </p>
                            <p class="mb-0 auth-text text-start mt-12 leading-24 pe-3">
                                A text message with a 6-digit verification code was just sent to
                                <span class="text-primary">
                                    <?php if ($data['user']['twofactor_status'] == "1"): ?>
                                        <?=e($data['user']['email'])?>
                                    <?php endif ?>
                                </span>
                            </p>
                        </div>

                        <form id="2fa-verification-form" class="mt-4">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group mb-3">
                                        <label class="form-label verification"><span>Verification code</span>
                                            <a id="resendOtp" style="cursor: pointer;" class="btn resend-code">
                                                <img src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/frontend/templates/images/2fa/refresh.svg" alt="refresh" class="img-fluid ref-light">

                                                <img src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/frontend/templates/images/2fa/refresh-dark.svg" alt="refresh" class="img-fluid ref-dark d-none">
                                                <span class="px-1">Resend code</span>
                                            </a>
                                        </label>

                                        <input type="text" class="form-control input-form-control" maxlength="6" id="code" placeholder="Enter the 6-digit code" name="code" autocomplete="off">
                                        <div id="2fa-error" class="error"></div>
                                    </div>

                                    <div class="d-grid">
                                        <?=$this->token()?>
                                        <button type="submit" class="btn btn-lg btn-primary mt-2 2faVerifyCode" id="2faVerifyCode">
                                            <span>Verify</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
