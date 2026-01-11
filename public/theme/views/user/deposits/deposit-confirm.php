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


<script type="text/javascript">
    'use strict';
    const creationTime = new Date("<?=$data['deposit-details']['created_at']?>").getTime();
    const expireSec = 86400; // 24 hours in seconds
    const deadline = creationTime + (expireSec * 1000);
    const expireText = "Expired";
</script>

<div class="position-relative">
    <div class="containt-parent">
        <div class="main-containt">
            <!-- main-containt -->
            <div class="bg-white pxy-62 pt-62 custom-input-height shadow convert">
                <p class="mb-0 f-26 gilroy-Semibold text-uppercase text-center">Deposit Money</p>
                <p class="mb-0 text-center f-13 gilroy-medium text-gray mt-4 dark-A0">Step: 2 of 3</p>
                <p class="mb-0 text-center f-18 gilroy-medium text-dark dark-5B mt-2">Confirm Your Deposit</p>

                <div class="text-center">
                    <svg class="mt-18 nscaleX-1" width="314" height="6" viewBox="0 0 314 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="100" height="6" rx="3" fill="#635BFE" />
                        <rect x="107" width="100" height="6" rx="3" fill="#635BFE" />
                        <rect class="rect-B87" x="214" width="100" height="6" rx="3" fill="#DDD3FD" />
                    </svg>
                </div>

                <p class="mb-0 text-center f-14 gilroy-medium text-gray dark-p mt-20">Check your deposit information before confirmation.</p>

                <form id="confirm-form">
                    <input type="hidden" name="balance" id="balance">
                    <input type="hidden" class="abbreviation" name="abbreviation" value="<?=e($data['payment-method']['abbreviation'])?>">
                    <input type="hidden" class="amount" value="<?=e($data['payment-amount'])?>">

                    <!-- Timer -->
                    <div class="d-flex justify-content-between mt-32">
                        <span class="f-16 gilroy-medium text-gray-100">Time Remaining : </span>
                        <span class="f-16 gilroy-medium text-danger" id="timer"></span>
                    </div>

                    <!-- Progressbar -->
                    <div class="custom-progress mt-15" id="demo1">
                        <div class="progress mx-auto w-100">
                            <div class="progress-bar bg-warning" role="progressbar" id="progressBar"></div>
                        </div>
                    </div>

                    <!-- Crypto Exchange Details -->
                    <div class="mt-32">
                        <div class="exchange-send-get d-flex justify-content-between">
                            <!-- Crypto Send -->
                            <div class="send-left-box w-50">
                                <p class="mb-0 f-14 leading-17 gilroy-medium text-gray-100">You'll send</p>

                                <p class="mb-0 f-24 l gilroy-Semibold mt-1">
                                    <span class="text-dark converted" id="converted">0.00</span>
                                    <span class="text-primary"> <?=e($data['payment-method']['abbreviation'])?></span>
                                </p>
                            </div>

                            <!-- Crypto Get -->
                            <div class="get-right-box w-50">
                                <p class="mb-0 f-14 leading-17 gilroy-medium text-light">equivalently</p>
                                <p class="mb-0 f-24 l gilroy-Semibold mt-1">
                                    <span class="text-white"><?= e(number_format($data['payment-amount'], 2)) ?></span>
                                    <span class="text-warning"> <?= formatCurrency($data['user']['currency']) ?></span>
                                </p>

                                <div class="right-box-icon">
                                    <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="12.5" cy="12.5" r="12.5" fill="white" />
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M10.5312 17.4705C10.2709 17.2102 10.2709 16.788 10.5312 16.5277L14.0598 12.9991L10.5312 9.47051C10.2708 9.21016 10.2708 8.78805 10.5312 8.5277C10.7915 8.26735 11.2137 8.26735 11.474 8.5277L15.474 12.5277C15.7344 12.788 15.7344 13.2102 15.474 13.4705L11.474 17.4705C11.2137 17.7309 10.7915 17.7309 10.5312 17.4705Z" fill="#6A6B87"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="payment_details" class="mt-20">
                        <!-- Crypto Pay Amount -->
                        <div class="d-flex flex-wrap justify-content-between">
                            <div class="left-merchant pt-20p">
                                <p class="mb-0 f-16 gilroy-medium text-gray-100 mt-38">Please make payment of</p>
                                <p class="mb-0 f-32 l gilroy-Semibold mt-2">
                                    <span class="text-dark converted" id="converted">0.00</span>
                                    <span class="text-primary"> <?=e($data['payment-method']['abbreviation'])?></span>
                                </p>
                                <p class="f-16 leading-25 text-gray-100 gilroy-medium mt-6">to our company address below</p>
                            </div>

                            <!-- QR Code -->
                            <div class="right-qr-code mt-24 user-profile-qr-code">
                                <img class="img-fluid" src="https://api.qrserver.com/v1/create-qr-code/?data=<?=e($data['payment-method']['gateway_parameter'])?>" alt="image" width="170" height="170" />
                                <p class="mb-0 f-12 gilroy-medium text-gray-100 mt-8">Scan QR code on your mobile</p>
                            </div>
                        </div>

                        <!-- Wallet Address -->
                        <div class="d-flex justify-content-between m-address">
                            <p class="mb-0 gilroy-medium text-gray-100 mb-2 mt-12">Wallet Address</p>
                            <p class="mb-0 gilroy-medium text-gray-100 mb-2 mt-12 copy-parent-div top-0" id="copy-parent-div">Copied</p>
                        </div>

                        <div class="d-flex position-relative copy-div">
                            <label for="walletAddress"></label>
                            <input class="form-control input-form-control apply-bg" type="text" id="walletAddress" value="<?=e($data['payment-method']['gateway_parameter'])?>" readonly />

                            <span onclick="copyToClipboard(document.getElementById('walletAddress'))" class="flex-shrink-1 b-none copy-btn" style="cursor: pointer;">
                                <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect class="rect-F30" width="36" height="36" rx="4" fill="#F5F6FA" />
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M22.2855 11.3759C21.7715 11.3339 21.1112 11.3333 20.1641 11.3333H14.2474C13.7872 11.3333 13.4141 10.9602 13.4141 10.5C13.4141 10.0397 13.7872 9.66663 14.2474 9.66663L20.1997 9.66663C21.1029 9.66662 21.8314 9.66661 22.4213 9.71481C23.0286 9.76443 23.5621 9.86928 24.0557 10.1208C24.8397 10.5202 25.4771 11.1577 25.8766 11.9417C26.1281 12.4352 26.2329 12.9687 26.2825 13.5761C26.3307 14.166 26.3307 14.8945 26.3307 15.7976V21.75C26.3307 22.2102 25.9576 22.5833 25.4974 22.5833C25.0372 22.5833 24.6641 22.2102 24.6641 21.75V15.8333C24.6641 14.8861 24.6634 14.2259 24.6214 13.7118C24.5802 13.2075 24.5034 12.9178 24.3916 12.6983C24.1519 12.2279 23.7694 11.8455 23.299 11.6058C23.0796 11.494 22.7898 11.4171 22.2855 11.3759ZM13.1319 12.5833H19.9462C20.3855 12.5833 20.7644 12.5833 21.0766 12.6088C21.406 12.6357 21.7337 12.6951 22.049 12.8558C22.5194 13.0955 22.9019 13.4779 23.1416 13.9483C23.3022 14.2636 23.3617 14.5913 23.3886 14.9208C23.4141 15.2329 23.4141 15.6119 23.4141 16.0512V22.8654C23.4141 23.3047 23.4141 23.6837 23.3886 23.9958C23.3617 24.3253 23.3022 24.653 23.1416 24.9683C22.9019 25.4387 22.5194 25.8211 22.049 26.0608C21.7337 26.2215 21.406 26.2809 21.0766 26.3078C20.7644 26.3333 20.3855 26.3333 19.9462 26.3333H13.1319C12.6926 26.3333 12.3137 26.3333 12.0015 26.3078C11.6721 26.2809 11.3444 26.2215 11.0291 26.0608C10.5587 25.8211 10.1762 25.4387 9.93655 24.9683C9.77589 24.653 9.71646 24.3253 9.68954 23.9958C9.66404 23.6837 9.66405 23.3047 9.66406 22.8654V16.0512C9.66405 15.6119 9.66404 15.2329 9.68954 14.9208C9.71646 14.5913 9.77589 14.2636 9.93655 13.9483C10.1762 13.4779 10.5587 13.0955 11.0291 12.8558C11.3444 12.6951 11.6721 12.6357 12.0015 12.6088C12.3137 12.5833 12.6927 12.5833 13.1319 12.5833ZM12.1373 14.2699C11.9109 14.2884 11.8269 14.3198 11.7857 14.3408C11.6289 14.4207 11.5015 14.5482 11.4216 14.705C11.4006 14.7462 11.3692 14.8301 11.3507 15.0565C11.3314 15.2926 11.3307 15.6028 11.3307 16.0833V22.8333C11.3307 23.3138 11.3314 23.624 11.3507 23.8601C11.3692 24.0865 11.4006 24.1704 11.4216 24.2116C11.5015 24.3684 11.6289 24.4959 11.7857 24.5758C11.8269 24.5968 11.9109 24.6282 12.1373 24.6467C12.3734 24.666 12.6836 24.6666 13.1641 24.6666H19.9141C20.3945 24.6666 20.7048 24.666 20.9409 24.6467C21.1673 24.6282 21.2512 24.5968 21.2924 24.5758C21.4492 24.4959 21.5767 24.3684 21.6566 24.2116C21.6776 24.1704 21.709 24.0865 21.7275 23.8601C21.7467 23.624 21.7474 23.3138 21.7474 22.8333V16.0833C21.7474 15.6028 21.7467 15.2926 21.7275 15.0565C21.709 14.8301 21.6776 14.7462 21.6566 14.705C21.5767 14.5482 21.4492 14.4207 21.2924 14.3408C21.2512 14.3198 21.1673 14.2884 20.9409 14.2699C20.7048 14.2506 20.3945 14.25 19.9141 14.25H13.1641C12.6836 14.25 12.3734 14.2506 12.1373 14.2699Z" fill="currentColor"/>
                                </svg>
                            </span>
                        </div>
                    </div>

                    <?php if ($data['payment-method']['need_proof'] == 1): ?>
                        <?php if ($data['payment-method']['proof_type'] == "image"): ?>
                        <!-- Payment Proof -->
                        <div class="attach-file attach-print label-top mt-20">
                            <label for="hidden-input" class="form-label text-gray-100 gilroy-medium">Payment Proof</label>
                            <input class="form-control upload-filed payment_details" type="file" name="photoimg" id="hidden-input">
                            <span id="file-error" class="error"></span>
                        </div>
                        <?php elseif ($data['payment-method']['proof_type'] == "text"): ?>
                            <!-- Payment Proof -->
                            <div class="attach-file attach-print label-top mt-20">
                                <label for="hidden-input" class="form-label text-gray-100 gilroy-medium">Transaction ID</label>
                                <input class="form-control input-form-control apply-bg" type="text" name="hashID" id="hashID">
                                <span id="file-error" class="error"></span>
                            </div>
                        <?php endif ?>
                    <?php endif ?>

                    <!-- button -->
                    <div class="d-grid">
                        <?=$this->token()?>
                        <button type="submit" class="btn btn-lg btn-primary mt-4 submit-button" id="confirmBtn" data-deposit="<?=e($data['deposit-details']['depositId'])?>" data-method="<?=e($data['payment-method']['method_code'])?>">

                            <span>Confirm &amp; Deposit</span>

                            <span id="rightAngleSvgIcon">
                                <svg class="position-relative ms-1 rtl-wrap-one nscaleX-1" width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M4.11648 12.216C3.81274 11.9123 3.81274 11.4198 4.11648 11.1161L8.23317 6.99937L4.11648 2.88268C3.81274 2.57894 3.81274 2.08647 4.11648 1.78273C4.42022 1.47899 4.91268 1.47899 5.21642 1.78273L9.88309 6.4494C10.1868 6.75314 10.1868 7.2456 9.88309 7.54934L5.21642 12.216C4.91268 12.5198 4.42022 12.5198 4.11648 12.216Z" fill="currentColor"/>
                                </svg>
                            </span>
                        </button>
                    </div>

                    <!-- Back Button -->
                    <div class="d-flex justify-content-center align-items-center mt-4 back-direction">
                        <a href="<?=$this->siteUrl()?>/user/deposit" class="text-gray gilroy-medium d-inline-flex align-items-center position-relative back-btn">
                            <svg class="position-relative nscaleX-1" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M8.47075 10.4709C8.7311 10.2105 8.7311 9.78842 8.47075 9.52807L4.94216 5.99947L8.47075 2.47087C8.7311 2.21053 8.7311 1.78842 8.47075 1.52807C8.2104 1.26772 7.78829 1.26772 7.52794 1.52807L3.52795 5.52807C3.2676 5.78842 3.2676 6.21053 3.52795 6.47088L7.52794 10.4709C7.78829 10.7312 8.2104 10.7312 8.47075 10.4709Z" fill="currentColor"/>
                            </svg>
                            <span class="ms-1 back-btn ns cryptoConfirmBackBtnText">Back</span>
                        </a>
                    </div>
                </form>
            </div>
            <!-- main-containt -->
        </div>
    </div>
</div>