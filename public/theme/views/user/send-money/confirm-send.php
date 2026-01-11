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
            <div class="bg-white pxy-62 shadow" id="sendMoneyConfirm">
                <p class="mb-0 f-26 gilroy-Semibold text-uppercase text-center">Send Money</p>
                <p class="mb-0 text-center f-13 gilroy-medium text-gray mt-4 dark-A0">Step: 2 of 3</p>
                <p class="mb-0 text-center f-18 gilroy-medium text-dark dark-5B mt-2">Confirm Your Transfer</p>
                <div class="text-center">
                    <svg class="mt-18 nscaleX-1" width="314" height="6" viewBox="0 0 314 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="100" height="6" rx="3" fill="#635BFE" />
                        <rect x="107" width="100" height="6" rx="3" fill="#635BFE" />
                        <rect class="rect-B87" x="214" width="100" height="6" rx="3" fill="#DDD3FD" />
                    </svg>
                </div>

                <p class="mb-0 text-center f-14 gilroy-medium text-gray dark-p mt-20">Please confirm your intention to proceed with this transfer. Once initiated, your account will be debited, and this action cannot be reversed.</p>
                
                <div class="mt-32 param-ref text-center">
                    <p class="mb-0 gilroy-medium text-primary dark-A0 f-16 sm-font-14 recipent-top-margin">Recipient</p>
                    <p class="mb-0 text-center f-20 gilroy-medium text-dark dark-5B mt-10"><?=$data['transfer-details']['receiver_email']?></p>
                    <div class="mt-40 transaction-box">
                        <div class="d-flex justify-content-between border-b-EF pb-13">
                            <p class="mb-0 gilroy-regular text-gray-100">Transfer Amount</p>
                            <p class="mb-0 gilroy-regular text-gray-100"><?= formatCurrency($data['user']['currency']) ?> <?=$data['transfer-details']['amount']?></p>
                        </div>
                    </div>
                </div>

                <div class="d-grid">
                    <a href="" data-bs-toggle="modal" data-bs-target="#approveModal" class="btn btn-lg btn-primary mt-4">
                        <span>Confirm &amp; Send</span>
                    </a>
                </div>

                <div class="d-flex justify-content-center align-items-center mt-4 back-direction">
                    <a href="<?=$this->siteUrl()?>/user/send" class="text-gray gilroy-medium d-inline-flex align-items-center position-relative back-btn">
                        <svg class="position-relative nscaleX-1" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M8.47075 10.4709C8.7311 10.2105 8.7311 9.78842 8.47075 9.52807L4.94216 5.99947L8.47075 2.47087C8.7311 2.21053 8.7311 1.78842 8.47075 1.52807C8.2104 1.26772 7.78829 1.26772 7.52794 1.52807L3.52795 5.52807C3.2676 5.78842 3.2676 6.21053 3.52795 6.47088L7.52794 10.4709C7.78829 10.7312 8.2104 10.7312 8.47075 10.4709Z" fill="currentColor"/>
                        </svg>
                        <span class="ms-1 back-btn">Back</span>
                    </a>
                </div>

                <div class="modal fade modal-overly" id="approveModal" aria-labelledby="edit-modal-header" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"  role="dialog">
                    <div class="delete-custom-modal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="edit-modal-header">
                                    <p class="modal-title f-20 gilroy-Semibold text-dark mb-0">Confirm Your Transfer</p>
                                    <button type="button" class="b-unset" data-bs-dismiss="modal" aria-label="Close">
                                        <span class="close-div position-absolute modal-close-btn btn-close rtl-wrap-four text-gray-100 d-flex align-items-center justify-content-center">
                                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M5.24408 5.24408C5.56951 4.91864 6.09715 4.91864 6.42259 5.24408L10 8.82149L13.5774 5.24408C13.9028 4.91864 14.4305 4.91864 14.7559 5.24408C15.0814 5.56951 15.0814 6.09715 14.7559 6.42259L11.1785 10L14.7559 13.5774C15.0814 13.9028 15.0814 14.4305 14.7559 14.7559C14.4305 15.0814 13.9028 15.0814 13.5774 14.7559L10 11.1785L6.42259 14.7559C6.09715 15.0814 5.56951 15.0814 5.24408 14.7559C4.91864 14.4305 4.91864 13.9028 5.24408 13.5774L8.82149 10L5.24408 6.42259C4.91864 6.09715 4.91864 5.56951 5.24408 5.24408Z" fill="currentColor"/>
                                            </svg>
                                        </span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <p class="mb-0 text-gray-100 f-16 leading-26 gilroy-medium">Are you sure you want to make this transfer?</p>
                                </div>

                                <div class="modals-bottom d-flex justify-content-end delete-gap">
                                    <button class="btn btn-secondary-cancel f-14 leading-17 text-gray-100 gilroy-medium" data-bs-dismiss="modal">Cancel</button>

                                    <form id="send-money-confirm-form">
                                        <?=$this->token()?>
                                        <button class="ml-delete btn btn-secondary-delete f-14 leading-17 text-dark gilroy-medium" id="sendMoneyConfirmBtn" data-send="<?=e($data['transfer-details']['transferId'])?>">Yes, Approve</button>
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