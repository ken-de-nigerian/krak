<?php
defined('FIR') OR exit();
/**
 * The template for displaying Example Create page
 */
?>
<?php 
    function formatCurrency($currency): string
    {
        switch ($currency) {
            case '€':
                return 'EUR ';
            case '£':
                return 'GBP ';
            case '$':
                return 'USD ';
            default:
                return $currency . ' ';
        }
    }
?>
<div class="position-relative">
    <div class="containt-parent">
        <div class="main-containt">
            <!-- main-containt -->
            <div class="bg-white pxy-62 shadow">
                <p class="mb-0 f-26 gilroy-Semibold text-uppercase text-center">Review Request</p>

                <p class="mb-0 text-center f-14 gilroy-medium text-gray dark-p mt-20">Please review the details carefully and proceed with the payment at your earliest convenience.</p>

                <div class="print-mail mt-4 mb-4">
                    <div class="d-flex gap-18 justify-content-center">
                        <div class="d-flex align-items-center justify-content-center user-mail mt-20">
                            <img src="<?=$this->siteUrl().'/'.PUBLIC_PATH.'/'.UPLOADS_PATH?>/users/<?=e($data['receiver']['imagelocation'])?>" class="img-fluid" />
                        </div>
                        <div class="d-flex">
                            <div class="mt-26">
                                <p class="mb-0 text-dark gilroy-medium f-16 theme-font"><?=e($data['receiver']['email'])?></p>
                                <p class="mb-0 text-gray-100 dark-B87 gilroy-regular f-12 mt-2 leading-20 theme-amount">Requested Amount</p>
                                <p class="mb-0 text-primary dark-B87 gilroy-medium mt-2p f-16 theme-usd"><?= formatCurrency($data['user']['currency']) ?> <?=$data['request-details']['amount']?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Icon -->
                <div class="d-flex justify-content-center">
                    <div class="border-hr d-flex justify-content-center"></div>
                </div>

                <div class="print-mail mt-4">
                    <div class="d-flex gap-18 justify-content-center">
                        <div class="d-flex align-items-center justify-content-center user-mail mt-20">
                            <img src="<?=$this->siteUrl().'/'.PUBLIC_PATH.'/'.UPLOADS_PATH?>/users/<?=e($data['user']['imagelocation'])?>" class="img-fluid" />
                        </div>
                        <div class="d-flex">
                            <div class="mt-26">
                                <p class="mb-0 text-dark gilroy-medium f-16 theme-font">You</p>
                                <p class="mb-0 text-gray-100 dark-B87 gilroy-regular f-12 mt-2 leading-20 theme-amount">Wallet Balance</p>
                                <p class="mb-0 text-primary dark-B87 gilroy-medium mt-2p f-16 theme-usd"><?= formatCurrency($data['user']['currency']) ?> <?=$data['user']['interest_wallet']?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-center mt-32 r-mt-20">
                    <a href="" data-bs-toggle="modal" data-bs-target="#approveModal" class="print-btn d-flex justify-content-center align-items-center gap-10 approve-request-btn">
                        <span>Approve</span>
                    </a>

                    <a href="" data-bs-toggle="modal" data-bs-target="#rejectModal" class="bg-white repeat-btn d-flex justify-content-center align-items-center ml-20 reject-request-btn">
                        <span class="gilroy-medium">Reject</span>
                    </a>
                </div>

                <div class="modal fade modal-overly" id="approveModal" aria-labelledby="edit-modal-header" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"  role="dialog">
                    <div class="delete-custom-modal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="edit-modal-header">
                                    <p class="modal-title f-20 gilroy-Semibold text-dark mb-0">Approve Request</p>
                                    <button type="button" class="b-unset" data-bs-dismiss="modal" aria-label="Close">
                                        <span class="close-div position-absolute modal-close-btn btn-close rtl-wrap-four text-gray-100 d-flex align-items-center justify-content-center">
                                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M5.24408 5.24408C5.56951 4.91864 6.09715 4.91864 6.42259 5.24408L10 8.82149L13.5774 5.24408C13.9028 4.91864 14.4305 4.91864 14.7559 5.24408C15.0814 5.56951 15.0814 6.09715 14.7559 6.42259L11.1785 10L14.7559 13.5774C15.0814 13.9028 15.0814 14.4305 14.7559 14.7559C14.4305 15.0814 13.9028 15.0814 13.5774 14.7559L10 11.1785L6.42259 14.7559C6.09715 15.0814 5.56951 15.0814 5.24408 14.7559C4.91864 14.4305 4.91864 13.9028 5.24408 13.5774L8.82149 10L5.24408 6.42259C4.91864 6.09715 4.91864 5.56951 5.24408 5.24408Z" fill="currentColor"/>
                                            </svg>
                                        </span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <p class="mb-0 text-gray-100 f-16 leading-26 gilroy-medium">Are you sure you want to approve this request?</p>
                                </div>

                                <div class="modals-bottom d-flex justify-content-end delete-gap">
                                    <button class="btn btn-secondary-cancel f-14 leading-17 text-gray-100 gilroy-medium" data-bs-dismiss="modal">Cancel</button>

                                    <form id="approve-form">
                                        <?=$this->token()?>
                                        <button class="ml-delete btn btn-secondary-delete f-14 leading-17 text-dark gilroy-medium" id="approve-modal-yes" data-approve="<?=e($data['request-details']["requestId"])?>">Yes, Approve</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade modal-overly" id="rejectModal" aria-labelledby="edit-modal-header" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"  role="dialog">
                    <div class="delete-custom-modal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="edit-modal-header">
                                    <p class="modal-title f-20 gilroy-Semibold text-dark mb-0">Reject Request</p>
                                    <button type="button" class="b-unset" data-bs-dismiss="modal" aria-label="Close">
                                        <span class="close-div position-absolute modal-close-btn btn-close rtl-wrap-four text-gray-100 d-flex align-items-center justify-content-center">
                                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M5.24408 5.24408C5.56951 4.91864 6.09715 4.91864 6.42259 5.24408L10 8.82149L13.5774 5.24408C13.9028 4.91864 14.4305 4.91864 14.7559 5.24408C15.0814 5.56951 15.0814 6.09715 14.7559 6.42259L11.1785 10L14.7559 13.5774C15.0814 13.9028 15.0814 14.4305 14.7559 14.7559C14.4305 15.0814 13.9028 15.0814 13.5774 14.7559L10 11.1785L6.42259 14.7559C6.09715 15.0814 5.56951 15.0814 5.24408 14.7559C4.91864 14.4305 4.91864 13.9028 5.24408 13.5774L8.82149 10L5.24408 6.42259C4.91864 6.09715 4.91864 5.56951 5.24408 5.24408Z" fill="currentColor"/>
                                            </svg>
                                        </span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <p class="mb-0 text-gray-100 f-16 leading-26 gilroy-medium">Are you sure you want to reject this request?</p>
                                </div>

                                <div class="modals-bottom d-flex justify-content-end delete-gap">
                                    <button class="btn btn-secondary-cancel f-14 leading-17 text-gray-100 gilroy-medium" data-bs-dismiss="modal">Cancel</button>

                                    <form id="reject-form">
                                        <?=$this->token()?>
                                        <button class="ml-delete btn btn-secondary-delete f-14 leading-17 text-dark gilroy-medium" id="reject-modal-yes" data-reject="<?=e($data['request-details']["requestId"])?>">Yes, Reject</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- main-containt -->
        </div>
    </div>
</div>