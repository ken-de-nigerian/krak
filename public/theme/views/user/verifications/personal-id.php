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
            <div class="bg-white pxy-62 exchange pt-62 shadow" id="identitiyVerify">
                <p class="mb-0 f-26 gilroy-Semibold text-uppercase text-center">Verifications</p>
                <div class="row">
                    <div class="col-12">
                        <nav>
                            <div class="nav-tab-parent d-flex justify-content-center mt-4">
                                <div class="d-flex p-2 border-1p rounded-pill gap-1 bg-white nav-tab-child">
                                    <a href="<?=$this->siteUrl()?>/user/verifications/personal-id" class="tablink-edit text-gray-100 tabactive">Identity Verification</a>
                                    <a href="<?=$this->siteUrl()?>/user/verifications/personal-address" class="tablink-edit text-gray-100">Address Verfication</a>
                                    
                                    <?php if ($data['settings']['twofa_status'] == 1): ?>
                                    <a href="<?=$this->siteUrl()?>/user/verifications/two-factor" class="tablink-edit text-gray-100">2fa Authentication</a>
                                    <?php endif ?>
                                </div>
                            </div>
                        </nav>

                        <div class="mt-28 label-top">
                            <form id="identitiy-form">
                                <div class="mt-28 param-ref">
                                    <label class="gilroy-medium text-gray-100 mb-2 f-15" for="identity_type">Identity Type</label>

                                    <?php if(isset($data['identity-proof'])): ?>
                                        <?php if($data['identity-proof']['status'] == 1):?>
                                            <span class="gilroy-medium text-success f-15"> (approved)</span>
                                        <?php elseif($data['identity-proof']['status'] == 2):?>
                                            <span class="gilroy-medium text-warning f-15"> (pending)</span>
                                        <?php elseif($data['identity-proof']['status'] == 3):?>
                                            <span class="gilroy-medium text-danger f-15"> (rejected)</span>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <div class="avoid-blink">
                                        <select class="select2" data-minimum-results-for-search="Infinity" name="identity_type" id="identity_type">
                                            <option value="1" <?= (isset($data['identity-proof']['identity_type']) && e($data['identity-proof']['identity_type']) == '1' ? ' selected' : '') ?>>National ID</option>
                                            <option value="2" <?= (isset($data['identity-proof']['identity_type']) && e($data['identity-proof']['identity_type']) == '2' ? ' selected' : '') ?>>Driving License</option>
                                            <option value="3" <?= (isset($data['identity-proof']['identity_type']) && e($data['identity-proof']['identity_type']) == '3' ? ' selected' : '') ?>>Passport</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="label-top mt-20">
                                    <label class="gilroy-medium text-gray-100 f-15 mb-2" for="identity_number">Identity Number</label>
                                    <input type="text" class="form-control input-form-control apply-bg l-s2" name="identity_number" id="identity_number" value="<?= isset($data['identity-proof']["identity_number"]) ? e($data['identity-proof']["identity_number"]) : '' ?>" autocomplete="off">
                                </div>

                                <div class="attach-file attach-print amount-label">
                                    <label class="gilroy-medium text-B87 f-15 mb-2 mt-24 r-mt-amount r-mt-6" for="hidden-input">Attach Identity Proof</label>
                                    <input type="file" class="form-control upload-filed" name="photoimg" id="hidden-input">
                                    <label class="d-none" id="identity-error"></label>
                                </div>

                                <p class="mb-0 f-11 gilroy-regular text-B87 mt-10">Upload your documents (Max: 2 mb)</p>

                                <?php if(isset($data['identity-proof'])): ?>
                                    <div class="proof-btn-div d-flex justify-content-start mt-3">
                                        <a href="<?=$this->siteUrl()?>/user/identity_proof/<?=e($data['identity-proof']["id"])?>" class="btn f-10 leading-12 proof-btn p-0 border-DF bg-FFF text-dark">

                                            <span><?=e($data['identity-proof']["fileupload"])?></span>

                                            <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect width="26" height="26" rx="4" fill="" />
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M13 8C13.2761 8 13.5 8.22386 13.5 8.5V14.2929L15.6464 12.1464C15.8417 11.9512 16.1583 11.9512 16.3536 12.1464C16.5488 12.3417 16.5488 12.6583 16.3536 12.8536L13.3536 15.8536C13.1583 16.0488 12.8417 16.0488 12.6464 15.8536L9.64645 12.8536C9.45118 12.6583 9.45118 12.3417 9.64645 12.1464C9.84171 11.9512 10.1583 11.9512 10.3536 12.1464L12.5 14.2929V8.5C12.5 8.22386 12.7239 8 13 8ZM8 17.5C8 17.2239 8.22386 17 8.5 17H17.5C17.7761 17 18 17.2239 18 17.5C18 17.7761 17.7761 18 17.5 18H8.5C8.22386 18 8 17.7761 8 17.5Z" fill="currentColor"/>
                                            </svg>
                                        </a>
                                    </div>
                                <?php endif; ?>

                                <?php if(isset($data['identity-address'])): ?>
                                    <?php if($data['identity-address']['status'] == 1):?>
                                        <div class="d-grid">
                                            <?=$this->token()?>
                                            <button type="submit" class="btn btn-primary px-4 py-2 mt-3" disabled id="identityBtn">
                                                <span>Verify Identity</span>
                                            </button>
                                        </div>
                                    <?php else: ?>
                                        <div class="d-grid">
                                            <?=$this->token()?>
                                            <button type="submit" class="btn btn-primary px-4 py-2 mt-3" id="identityBtn">
                                                <span>Verify Identity</span>
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <div class="d-grid">
                                        <?=$this->token()?>
                                        <button type="submit" class="btn btn-primary px-4 py-2 mt-3" id="identityBtn">
                                            <span>Verify Identity</span>
                                        </button>
                                    </div>
                                <?php endif; ?>
                            </form>
                            <!-- 2nd step end-->
                        </div>
                    </div>
                </div>
            </div>
            <!-- main-containt -->
        </div>
    </div>
</div>
