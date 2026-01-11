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
                                    <h4 class="display-6 coming-soon-text">Email SMTP Settings</h4>
                                    <p class="text-success fs-15 mt-3">Edit your email settings and test your email notifications here.</p>
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

                <?php if ($data['settings']['email_provider'] == "phpmailer"): ?>
                    <form id="smtp-form">
                        <div class="row justify-content-evenly mb-2">
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Phpmailer Smtp Settings</h4>
                                </div>
                                
                                <!-- end card header -->
                                <div class="card-body">
                                    <div class="live-preview">
                                        <div class="row gy-4">
                                            <div class="col-xxl-6 col-md-6">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" id="smtp_host" name="smtp_host" value="<?=e($data['settings']['smtp_host'])?>" autocomplete="off">
                                                    <label for="smtp_host">SMTP HOST</label>
                                                </div>
                                            </div>
                                            <!--end col-->

                                            <div class="col-xxl-6 col-md-6">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" id="smtp_username" name="smtp_username" value="<?=e($data['settings']['smtp_username'])?>" autocomplete="off">
                                                    <label for="smtp_username">SMTP USERNAME</label>
                                                </div>
                                            </div>
                                            <!--end col-->

                                            <div class="col-xxl-6 col-md-6">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" id="smtp_password" name="smtp_password" value="<?=e($data['settings']['smtp_password'])?>" autocomplete="off">
                                                    <label for="smtp_password">SMTP PASSWORD</label>
                                                </div>
                                            </div>
                                            <!--end col-->

                                            <div class="col-xxl-6 col-md-6">
                                                <div class="form-floating">
                                                    <select id="smtp_encryption" name="smtp_encryption" class="form-control" data-width="100%" autocomplete="off">
                                                        <option value="ssl" <?=(e($data['settings']['smtp_encryption']) == 'ssl' ? ' selected' : '')?>>SSL</option>
                                                        <option value="tls" <?=(e($data['settings']['smtp_encryption']) == 'tls' ? ' selected' : '')?>>TLS</option>
                                                    </select>
                                                    <label for="smtp_encryption">SMTP ENCRYPTION</label>
                                                </div>
                                            </div>
                                            <!--end col-->

                                            <div class="col-xxl-6 col-md-6">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" id="smtp_port" name="smtp_port" value="<?=e($data['settings']['smtp_port'])?>">
                                                    <label for="smtp_port">SMTP PORT</label>
                                                </div>
                                            </div>
                                            <!--end col-->
                                        </div>
                                        <!--end row-->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-evenly mb-2">
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Email Settings</h4>
                                </div>
                                
                                <!-- end card header -->
                                <div class="card-body">
                                    <div class="live-preview">
                                        <div class="row gy-4">
                                            <div class="col-xxl-6 col-md-6">
                                                <div class="form-floating">
                                                    <select id="email_notification" name="email_notification" class="form-control" autocomplete="off">
                                                        <option value="1" <?=(e($data['settings']['email_notification']) == '1' ? ' selected' : '')?>>Enabled</option>
                                                        <option value="2" <?=(e($data['settings']['email_notification']) == '2' ? ' selected' : '')?>>Disabled</option>
                                                    </select>
                                                    <label for="email_notification">Email Notification</label>
                                                </div>
                                            </div>
                                            <!--end col-->

                                            <div class="col-xxl-6 col-md-6 mb-4">
                                                <div class="form-floating">
                                                    <select id="email_provider" name="email_provider" class="form-control" autocomplete="off">
                                                        <option value="phpmailer" <?=(e($data['settings']['email_provider']) == 'phpmailer' ? ' selected' : '')?>>Phpmailer</option>
                                                        <option value="mailjet" <?=(e($data['settings']['email_provider']) == 'mailjet' ? ' selected' : '')?>>Mailjet</option>
                                                        <option value="symfony" <?=(e($data['settings']['email_provider']) == 'symfony' ? ' selected' : '')?>>Symfony Mailer</option>
                                                    </select>
                                                    <label for="email_provider">Email Provider</label>
                                                </div>
                                            </div>
                                            <!--end col-->

                                            <div class="col-xxl-12 col-md-12">
                                                <?=$this->token()?>
                                                <button id="smtpBtn" type="submit" class="btn btn-primary waves-effect waves-light w-100">Submit Form</button>
                                            </div>
                                            <!--end col-->
                                        </div>
                                        <!--end row-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                <?php elseif ($data['settings']['email_provider'] == "symfony"): ?>
                    <form id="smtp-form">
                        <div class="row justify-content-evenly mb-2">
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Symfony Smtp Settings</h4>
                                </div>
                                
                                <!-- end card header -->
                                <div class="card-body">
                                    <div class="live-preview">
                                        <div class="row gy-4">
                                            <div class="col-xxl-6 col-md-6">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" id="smtp_host" name="smtp_host" value="<?=e($data['settings']['smtp_host'])?>" autocomplete="off">
                                                    <label for="smtp_host">SMTP HOST</label>
                                                </div>
                                            </div>
                                            <!--end col-->

                                            <div class="col-xxl-6 col-md-6">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" id="smtp_username" name="smtp_username" value="<?=e($data['settings']['smtp_username'])?>" autocomplete="off">
                                                    <label for="smtp_username">SMTP USERNAME</label>
                                                </div>
                                            </div>
                                            <!--end col-->

                                            <div class="col-xxl-6 col-md-6">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" id="smtp_password" name="smtp_password" value="<?=e($data['settings']['smtp_password'])?>" autocomplete="off">
                                                    <label for="smtp_password">SMTP PASSWORD</label>
                                                </div>
                                            </div>
                                            <!--end col-->

                                            <div class="col-xxl-6 col-md-6">
                                                <div class="form-floating">
                                                    <select id="smtp_encryption" name="smtp_encryption" class="form-control" data-width="100%" autocomplete="off">
                                                        <option value="ssl" <?=(e($data['settings']['smtp_encryption']) == 'ssl' ? ' selected' : '')?>>SSL</option>
                                                        <option value="tls" <?=(e($data['settings']['smtp_encryption']) == 'tls' ? ' selected' : '')?>>TLS</option>
                                                    </select>
                                                    <label for="smtp_encryption">SMTP ENCRYPTION</label>
                                                </div>
                                            </div>
                                            <!--end col-->

                                            <div class="col-xxl-6 col-md-6">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" id="smtp_port" name="smtp_port" value="<?=e($data['settings']['smtp_port'])?>">
                                                    <label for="smtp_port">SMTP PORT</label>
                                                </div>
                                            </div>
                                            <!--end col-->
                                        </div>
                                        <!--end row-->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-evenly mb-2">
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Email Settings</h4>
                                </div>
                                
                                <!-- end card header -->
                                <div class="card-body">
                                    <div class="live-preview">
                                        <div class="row gy-4">
                                            <div class="col-xxl-6 col-md-6">
                                                <div class="form-floating">
                                                    <select id="email_notification" name="email_notification" class="form-control" autocomplete="off">
                                                        <option value="1" <?=(e($data['settings']['email_notification']) == '1' ? ' selected' : '')?>>Enabled</option>
                                                        <option value="2" <?=(e($data['settings']['email_notification']) == '2' ? ' selected' : '')?>>Disabled</option>
                                                    </select>
                                                    <label for="email_notification">Email Notification</label>
                                                </div>
                                            </div>
                                            <!--end col-->

                                            <div class="col-xxl-6 col-md-6 mb-4">
                                                <div class="form-floating">
                                                    <select id="email_provider" name="email_provider" class="form-control" autocomplete="off">
                                                        <option value="phpmailer" <?=(e($data['settings']['email_provider']) == 'phpmailer' ? ' selected' : '')?>>Phpmailer</option>
                                                        <option value="mailjet" <?=(e($data['settings']['email_provider']) == 'mailjet' ? ' selected' : '')?>>Mailjet</option>
                                                        <option value="symfony" <?=(e($data['settings']['email_provider']) == 'symfony' ? ' selected' : '')?>>Symfony Mailer</option>
                                                    </select>
                                                    <label for="email_provider">Email Provider</label>
                                                </div>
                                            </div>
                                            <!--end col-->

                                            <div class="col-xxl-12 col-md-12">
                                                <?=$this->token()?>
                                                <button id="smtpBtn" type="submit" class="btn btn-primary waves-effect waves-light w-100">Submit Form</button>
                                            </div>
                                            <!--end col-->
                                        </div>
                                        <!--end row-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                <?php elseif ($data['settings']['email_provider'] == "mailjet"): ?>
                    <form id="mailjet-form">
                        <div class="row justify-content-evenly mb-2">
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Mailjet Settings</h4>
                                </div>
                                
                                <!-- end card header -->
                                <div class="card-body">
                                    <div class="live-preview">
                                        <div class="row gy-4">
                                            <div class="col-xxl-6 col-md-6">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" id="mailjet_api_key" name="mailjet_api_key" value="<?=e($data['settings']['mailjet_api_key'])?>" autocomplete="off">
                                                    <label for="mailjet_api_key">MAILJET API KEY</label>
                                                </div>
                                            </div>
                                            <!--end col-->

                                            <div class="col-xxl-6 col-md-6">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" id="mailjet_api_secret" name="mailjet_api_secret" value="<?=e($data['settings']['mailjet_api_secret'])?>" autocomplete="off">
                                                    <label for="mailjet_api_secret">MAILJET API SECRET</label>
                                                </div>
                                            </div>
                                            <!--end col-->
                                        </div>
                                        <!--end row-->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-evenly mb-2">
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Email Settings</h4>
                                </div>
                                
                                <!-- end card header -->
                                <div class="card-body">
                                    <div class="live-preview">
                                        <div class="row gy-4">
                                            <div class="col-xxl-6 col-md-6">
                                                <div class="form-floating">
                                                    <select id="email_notification" name="email_notification" class="form-control" autocomplete="off">
                                                        <option value="1" <?=(e($data['settings']['email_notification']) == '1' ? ' selected' : '')?>>Enabled</option>
                                                        <option value="2" <?=(e($data['settings']['email_notification']) == '2' ? ' selected' : '')?>>Disabled</option>
                                                    </select>
                                                    <label for="email_notification">Email Notification</label>
                                                </div>
                                            </div>
                                            <!--end col-->

                                            <div class="col-xxl-6 col-md-6 mb-4">
                                                <div class="form-floating">
                                                    <select id="email_provider" name="email_provider" class="form-control" autocomplete="off">
                                                        <option value="phpmailer" <?=(e($data['settings']['email_provider']) == 'phpmailer' ? ' selected' : '')?>>Phpmailer</option>
                                                        <option value="mailjet" <?=(e($data['settings']['email_provider']) == 'mailjet' ? ' selected' : '')?>>Mailjet</option>
                                                        <option value="symfony" <?=(e($data['settings']['email_provider']) == 'symfony' ? ' selected' : '')?>>Symfony Mailer</option>
                                                    </select>
                                                    <label for="email_provider">Email Provider</label>
                                                </div>
                                            </div>
                                            <!--end col-->

                                            <div class="col-xxl-12 col-md-12">
                                                <?=$this->token()?>
                                                <button id="mailjetBtn" type="submit" class="btn btn-primary waves-effect waves-light w-100">Submit Form</button>
                                            </div>
                                            <!--end col-->
                                        </div>
                                        <!--end row-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                <?php endif ?>
            </div>
            <!--end col-->
        </div>
        <!--end row-->
    </div>
    <!-- container-fluid -->
</div>
<!-- End Page-content -->

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