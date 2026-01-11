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
            <div class="text-center">
                <p class="mb-0 gilroy-Semibold f-26 text-dark theme-tran r-f-20 text-uppercase">Your Profile</p>
                <p class="mb-0 gilroy-medium text-gray-100 f-16 r-f-12 profile-header mt-2 tran-title">You have full control to manage your own account setting</p>
            </div>

            <div class="row" id="profileUpdate">
                <div class="col-xl-12 col-xxl-6">
                    <!-- Profile Image Div -->
                    <div class="avatar-left-div bg-white mt-32">
                        <div class="d-flex justify-content-between">
                            <div class="left-avatar-desc">
                                <p class="mb-0 f-20 leading-25 gilroy-Semibold text-dark"><?=e($data['user']['firstname'])?> <?=e($data['user']['lastname'])?></p>
                                <p class="mb-0 f-14 leading-22 gilroy-medium text-gray-100 mt-8">Please set your profile image.</p>
                                <p class="mb-0 f-12 leading-18 gilroy-medium fst-italic text-gray mt-3p">Supported format: jpeg, png, bmp, gif, webp, or svg</p>
                                <div class="d-flex mt-26 align-items-center justify-content-between">
                                    <div class="camera">

                                        <input id="upload" type="file" />
										<input type="hidden" id="file_name" />

										<a class="bg-primary green-btn" href="javascript:void(0)" onclick="changeProfile()">
											<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M7.1683 0.750003C7.1741 0.750004 7.17995 0.750006 7.18586 0.750006L10.8317 0.750003C10.9144 0.749983 10.9863 0.749965 11.0549 0.754121C11.9225 0.806667 12.6823 1.35427 13.0065 2.16076C13.0321 2.22455 13.0549 2.29277 13.081 2.3712L13.0865 2.38783C13.1213 2.49221 13.1289 2.5138 13.1353 2.52975C13.2433 2.79858 13.4966 2.98112 13.7858 2.99863C13.8028 2.99966 13.8282 3.00001 13.9458 3.00001C13.96 3.00001 13.974 3 13.9877 3C14.2239 2.99995 14.3974 2.99992 14.5458 3.01462C15.9687 3.15561 17.0944 4.28127 17.2354 5.70422C17.2501 5.85261 17.2501 6.01915 17.25 6.24402C17.25 6.25679 17.25 6.26976 17.25 6.28292V12.181C17.25 12.7847 17.25 13.283 17.2169 13.6889C17.1824 14.1104 17.1085 14.498 16.923 14.862C16.6354 15.4265 16.1765 15.8854 15.612 16.173C15.248 16.3585 14.8605 16.4324 14.4389 16.4669C14.033 16.5 13.5347 16.5 12.931 16.5H5.06902C4.4653 16.5 3.96703 16.5 3.56114 16.4669C3.13957 16.4324 2.75204 16.3585 2.38804 16.173C1.82355 15.8854 1.36461 15.4265 1.07699 14.862C0.891523 14.498 0.817599 14.1104 0.783156 13.6889C0.749993 13.283 0.75 12.7847 0.75001 12.181L0.75001 6.28292C0.75001 6.26976 0.750008 6.25679 0.750005 6.24402C0.749959 6.01915 0.749925 5.85261 0.764628 5.70422C0.905612 4.28127 2.03128 3.15561 3.45422 3.01462C3.60266 2.99992 3.77616 2.99995 4.01231 3C4.02606 3 4.04002 3.00001 4.0542 3.00001C4.1718 3.00001 4.19725 2.99966 4.21421 2.99863C4.50342 2.98112 4.75667 2.79858 4.86475 2.52975C4.87116 2.5138 4.8787 2.49222 4.9135 2.38783C4.91537 2.38222 4.91722 2.37666 4.91906 2.37115C4.94517 2.29275 4.96789 2.22453 4.99353 2.16076C5.31775 1.35427 6.0775 0.806667 6.94513 0.754121C7.01375 0.749965 7.08565 0.749983 7.1683 0.750003ZM7.18586 2.25001C7.07584 2.25001 7.05297 2.25034 7.03581 2.25138C6.7466 2.26889 6.49335 2.45143 6.38528 2.72026C6.37886 2.73621 6.37132 2.75779 6.33652 2.86218C6.33465 2.86779 6.3328 2.87335 6.33097 2.87886C6.30485 2.95726 6.28213 3.02548 6.25649 3.08925C5.93227 3.89574 5.17252 4.44334 4.30489 4.49589C4.23623 4.50005 4.16095 4.50003 4.07344 4.50001C4.06709 4.50001 4.06068 4.50001 4.0542 4.50001C3.75811 4.50001 3.66633 4.50095 3.60212 4.50731C2.89064 4.57781 2.32781 5.14064 2.25732 5.85211C2.25093 5.91658 2.25001 6.00223 2.25001 6.28292V12.15C2.25001 12.7924 2.25059 13.2292 2.27817 13.5667C2.30504 13.8955 2.35373 14.0637 2.4135 14.181C2.55731 14.4632 2.78678 14.6927 3.06902 14.8365C3.18632 14.8963 3.35448 14.945 3.68329 14.9718C4.02086 14.9994 4.45758 15 5.10001 15H12.9C13.5424 15 13.9792 14.9994 14.3167 14.9718C14.6455 14.945 14.8137 14.8963 14.931 14.8365C15.2132 14.6927 15.4427 14.4632 15.5865 14.181C15.6463 14.0637 15.695 13.8955 15.7218 13.5667C15.7494 13.2292 15.75 12.7924 15.75 12.15V6.28292C15.75 6.00223 15.7491 5.91658 15.7427 5.85211C15.6722 5.14064 15.1094 4.57781 14.3979 4.50731C14.3337 4.50095 14.2419 4.50001 13.9458 4.50001L13.9266 4.50001C13.8391 4.50003 13.7638 4.50005 13.6951 4.49589C12.8275 4.44334 12.0677 3.89574 11.7435 3.08925C11.7179 3.02547 11.6952 2.95724 11.669 2.87881L11.6635 2.86218C11.6287 2.7578 11.6212 2.73621 11.6147 2.72026C11.5067 2.45143 11.2534 2.26889 10.9642 2.25138C10.947 2.25034 10.9242 2.25001 10.8142 2.25001H7.18586ZM9.00001 7.12501C7.75737 7.12501 6.75001 8.13236 6.75001 9.37501C6.75001 10.6176 7.75737 11.625 9.00001 11.625C10.2427 11.625 11.25 10.6176 11.25 9.37501C11.25 8.13236 10.2427 7.12501 9.00001 7.12501ZM5.25001 9.37501C5.25001 7.30394 6.92894 5.62501 9.00001 5.62501C11.0711 5.62501 12.75 7.30394 12.75 9.37501C12.75 11.4461 11.0711 13.125 9.00001 13.125C6.92894 13.125 5.25001 11.4461 5.25001 9.37501Z" fill="currentColor"/>
                                            </svg>
										    <span class="f-14 leading-20 text-white mx-2 gilroy-medium">Change Photo</span>
										</a>
                                        <span id="file-error"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="right-avatar-img">
                                <img src="<?=$this->siteUrl().'/'.PUBLIC_PATH.'/'.UPLOADS_PATH?>/users/<?=e($data['user']['imagelocation'])?>" alt="Profile" id="profileImage" />
                            </div>
                        </div>
                    </div>

                    <!-- Default Currency Div -->
                    <div class="default-wallet-div d-flex justify-content-between bg-white mt-24">
                        <div class="wallet-text d-flex">
                            <p class="wallet-text-hover mb-0 text-dark f-20 leading-25 gilroy-Semibold">Default Currency</p>

                            <div class="cursor-pointer wallet-svg d-flex align-items-center">
                                <a href="" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                    <svg class="ml-12" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M11.8448 2.09484C12.759 1.18063 14.2412 1.18063 15.1554 2.09484C16.0696 3.00905 16.0696 4.49129 15.1554 5.4055L5.73337 14.8276C5.71852 14.8424 5.70381 14.8571 5.68921 14.8718C5.47363 15.0878 5.28355 15.2782 5.0544 15.4186C4.85309 15.542 4.63361 15.6329 4.40403 15.688C4.1427 15.7507 3.87364 15.7505 3.56847 15.7502C3.54781 15.7502 3.52698 15.7502 3.50598 15.7502H2.25008C1.83586 15.7502 1.50008 15.4144 1.50008 15.0002V13.7443C1.50008 13.7233 1.50006 13.7025 1.50004 13.6818C1.49975 13.3766 1.4995 13.1076 1.56224 12.8462C1.61736 12.6167 1.70827 12.3972 1.83164 12.1959C1.97206 11.9667 2.16249 11.7766 2.37848 11.5611C2.3931 11.5465 2.40784 11.5317 2.42269 11.5169L11.8448 2.09484ZM14.0948 3.1555C13.7663 2.82707 13.2339 2.82707 12.9054 3.1555L3.48335 12.5776C3.19868 12.8622 3.14619 12.9215 3.1106 12.9796C3.06948 13.0467 3.03917 13.1199 3.0208 13.1964C3.0049 13.2626 3.00008 13.3417 3.00008 13.7443V14.2502H3.50598C3.90857 14.2502 3.98762 14.2453 4.05386 14.2294C4.13039 14.2111 4.20354 14.1808 4.27065 14.1396C4.32873 14.1041 4.38804 14.0516 4.67271 13.7669L14.0948 4.34484C14.4232 4.01641 14.4232 3.48393 14.0948 3.1555ZM8.25006 15.0002C8.25006 14.586 8.58584 14.2502 9.00006 14.2502H15.7501C16.1643 14.2502 16.5001 14.586 16.5001 15.0002C16.5001 15.4144 16.1643 15.7502 15.7501 15.7502H9.00006C8.58584 15.7502 8.25006 15.4144 8.25006 15.0002Z" fill="currentColor"/>
                                    </svg>
                                </a>
                            </div>

                            <div class="modal fade modal-overly" id="exampleModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"  role="dialog">
                                <div class="modal-dialog modal-dialog-centered modal-lg res-dialog">
                                    <div class="modal-content">
                                        <div class="modal-content">
                                            <div class="modal-header w-modal-header">
                                                <p class="modal-title gilroy-Semibold text-dark">Set Default Currency</p>
                                                <button type="button" class="cursor-pointer close-btn" data-bs-dismiss="modal" aria-label="Close">
                                                    <span class="close-div position-absolute modal-close-btn rtl-wrap-four text-gray-100 d-flex align-items-center justify-content-center">
                                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M5.24408 5.24408C5.56951 4.91864 6.09715 4.91864 6.42259 5.24408L10 8.82149L13.5774 5.24408C13.9028 4.91864 14.4305 4.91864 14.7559 5.24408C15.0814 5.56951 15.0814 6.09715 14.7559 6.42259L11.1785 10L14.7559 13.5774C15.0814 13.9028 15.0814 14.4305 14.7559 14.7559C14.4305 15.0814 13.9028 15.0814 13.5774 14.7559L10 11.1785L6.42259 14.7559C6.09715 15.0814 5.56951 15.0814 5.24408 14.7559C4.91864 14.4305 4.91864 13.9028 5.24408 13.5774L8.82149 10L5.24408 6.42259C4.91864 6.09715 4.91864 5.56951 5.24408 5.24408Z" fill="currentColor"/>
                                                        </svg>
                                                    </span>
                                                </button>
                                            </div>

                                            <div class="modal-body modal-body-pxy">
                                                <form id="currency-form">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="col-md-12">
                                                                <div class="param-ref param-ref-withdraw money-ref r-mt-11">
                                                                    <label class="gilroy-medium text-gray-100 mb-7 f-14 leading-17 r-mt-0" for="currency">Select Currency </label>
                                                                    <select class="select2 withdraw-type" data-minimum-results-for-search="Infinity" name="currency" id="currency" autocomplete="off">
                                                                        <option value="&#x24;" <?=(e($data['user']['currency']) == '$' ? ' selected' : '')?>>USD</option>
                                                                        <option value="&#x20AC;" <?=(e($data['user']['currency']) == '€' ? ' selected' : '')?>>EUR</option>
                                                                        <option value="&#xA3;" <?=(e($data['user']['currency']) == '£' ? ' selected' : '')?>>GBP</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row mt-20">
                                                        <div class="col-md-12 pd-bottom pb-2">
                                                        	<?=$this->token()?>
                                                            <button type="submit" class="btn bg-primary add-option-btn w-100 setting-btn f-16 leading-20 gilroy-medium" id="currencyBtn">
                                                                <span>Save Changes</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="mb-0 f-20 leading-25 gilroy-Semibold text-uppercase text-primary">
                        	<?= formatCurrency($data['user']['currency']) ?>
                        </p>
                    </div>
                </div>

                <div class="col-xl-12 col-xxl-6">
                    <div class="bg-white profile-qr-code mt-32">
                        <!-- Qr Code Div -->
                        <div class="d-flex flex-wrap justify-content-between gap-26">
                            <div class="left-qr-desc">
                                <div class="peofile-qr-text d-flex">
                                    <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M7.11084 1.83301L7.33203 1.83301C7.83829 1.83301 8.2487 2.24341 8.2487 2.74968C8.2487 3.25594 7.83829 3.66634 7.33203 3.66634H7.1487C6.3635 3.66634 5.82973 3.66706 5.41715 3.70076C5.01527 3.7336 4.80975 3.79311 4.66638 3.86616C4.32142 4.04193 4.04095 4.3224 3.86519 4.66736C3.79214 4.81073 3.73262 5.01625 3.69979 5.41813C3.66608 5.83071 3.66537 6.36448 3.66537 7.14968V7.33301C3.66537 7.83927 3.25496 8.24968 2.7487 8.24968C2.24244 8.24968 1.83203 7.83927 1.83203 7.33301L1.83203 7.11182C1.83202 6.37393 1.83201 5.76493 1.87254 5.26884C1.91464 4.75358 2.00499 4.27993 2.23167 3.83504C2.58321 3.14512 3.14414 2.58419 3.83407 2.23265C4.27896 2.00597 4.7526 1.91562 5.26786 1.87352C5.76395 1.83299 6.37295 1.833 7.11084 1.83301ZM16.5802 3.70076C16.1677 3.66706 15.6339 3.66634 14.8487 3.66634H14.6654C14.1591 3.66634 13.7487 3.25594 13.7487 2.74968C13.7487 2.24341 14.1591 1.83301 14.6654 1.83301L14.8866 1.83301C15.6244 1.833 16.2334 1.83299 16.7295 1.87352C17.2448 1.91562 17.7184 2.00597 18.1633 2.23265C18.8533 2.58419 19.4142 3.14512 19.7657 3.83504C19.9924 4.27993 20.0828 4.75358 20.1249 5.26884C20.1654 5.76493 20.1654 6.37392 20.1654 7.1118V7.33301C20.1654 7.83927 19.755 8.24968 19.2487 8.24968C18.7424 8.24968 18.332 7.83927 18.332 7.33301V7.14968C18.332 6.36448 18.3313 5.83071 18.2976 5.41813C18.2648 5.01625 18.2053 4.81073 18.1322 4.66736C17.9564 4.3224 17.676 4.04193 17.331 3.86616C17.1876 3.79311 16.9821 3.7336 16.5802 3.70076ZM1.83203 10.9997C1.83203 10.4934 2.24244 10.083 2.7487 10.083H2.75787C3.26413 10.083 3.67453 10.4934 3.67453 10.9997C3.67453 11.5059 3.26413 11.9163 2.75787 11.9163H2.7487C2.24244 11.9163 1.83203 11.5059 1.83203 10.9997ZM5.95703 10.9997C5.95703 10.4934 6.36744 10.083 6.8737 10.083H6.88287C7.38913 10.083 7.79953 10.4934 7.79953 10.9997C7.79953 11.5059 7.38913 11.9163 6.88287 11.9163H6.8737C6.36744 11.9163 5.95703 11.5059 5.95703 10.9997ZM10.082 10.9997C10.082 10.4934 10.4924 10.083 10.9987 10.083H11.0079C11.5141 10.083 11.9245 10.4934 11.9245 10.9997C11.9245 11.5059 11.5141 11.9163 11.0079 11.9163H10.9987C10.4924 11.9163 10.082 11.5059 10.082 10.9997ZM14.207 10.9997C14.207 10.4934 14.6174 10.083 15.1237 10.083H15.1329C15.6391 10.083 16.0495 10.4934 16.0495 10.9997C16.0495 11.5059 15.6391 11.9163 15.1329 11.9163H15.1237C14.6174 11.9163 14.207 11.5059 14.207 10.9997ZM18.332 10.9997C18.332 10.4934 18.7424 10.083 19.2487 10.083H19.2579C19.7641 10.083 20.1745 10.4934 20.1745 10.9997C20.1745 11.5059 19.7641 11.9163 19.2579 11.9163H19.2487C18.7424 11.9163 18.332 11.5059 18.332 10.9997ZM2.7487 13.7497C3.25496 13.7497 3.66537 14.1601 3.66537 14.6663V14.8497C3.66537 15.6349 3.66608 16.1686 3.69979 16.5812C3.73262 16.9831 3.79214 17.1886 3.86519 17.332C4.04096 17.677 4.32142 17.9574 4.66638 18.1332C4.80975 18.2062 5.01527 18.2658 5.41715 18.2986C5.82973 18.3323 6.3635 18.333 7.1487 18.333H7.33203C7.83829 18.333 8.2487 18.7434 8.2487 19.2497C8.2487 19.7559 7.83829 20.1663 7.33203 20.1663H7.11082C6.37294 20.1664 5.76395 20.1664 5.26786 20.1258C4.7526 20.0837 4.27896 19.9934 3.83407 19.7667C3.14414 19.4152 2.58321 18.8542 2.23167 18.1643C2.00499 17.7194 1.91464 17.2458 1.87254 16.7305C1.83201 16.2344 1.83202 15.6254 1.83203 14.8875L1.83203 14.6663C1.83203 14.1601 2.24244 13.7497 2.7487 13.7497ZM19.2487 13.7497C19.755 13.7497 20.1654 14.1601 20.1654 14.6663V14.8876C20.1654 15.6254 20.1654 16.2344 20.1249 16.7305C20.0828 17.2458 19.9924 17.7194 19.7657 18.1643C19.4142 18.8542 18.8533 19.4152 18.1633 19.7667C17.7184 19.9934 17.2448 20.0837 16.7295 20.1258C16.2334 20.1664 15.6245 20.1664 14.8866 20.1663H14.6654C14.1591 20.1663 13.7487 19.7559 13.7487 19.2497C13.7487 18.7434 14.1591 18.333 14.6654 18.333H14.8487C15.6339 18.333 16.1677 18.3323 16.5802 18.2986C16.9821 18.2658 17.1876 18.2062 17.331 18.1332C17.676 17.9574 17.9564 17.677 18.1322 17.332C18.2053 17.1886 18.2648 16.9831 18.2976 16.5812C18.3313 16.1686 18.332 15.6349 18.332 14.8497V14.6663C18.332 14.1601 18.7424 13.7497 19.2487 13.7497Z" fill="currentColor"/>
                                    </svg>

                                    <div class="peofile-qr-body-text ml-12">
                                        <p class="mb-0 f-16 leading-20 gilroy-medium text-dark mt-3p">Referral QR Code</p>
                                        <p class="mt-8 mb-0 f-13 leading-20 gilroy-medium text-gray-100 w-258">Use the QR code to easily invite your friends.</p>
                                    </div>
                                </div>

                                <label for="referralURL"></label>
                                <input id="referralURL" type="text" value="<?=$this->siteUrl()?>/register/?ref=<?=e($data['user']['userid'])?>" style="display: none;">

                                <div class="d-flex print-update-code mt-20">
                                    <a onclick="copyToClipboard(document.getElementById('referralURL'))" class="print-code bg-primary text-white green-btn d-flex gap-2 align-items-center" style="cursor: pointer;">
                                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M3.23077 12.8333H10.7692V10.5H3.23077V12.8333ZM3.23077 7H10.7692V3.5H9.42308C9.19872 3.5 9.00801 3.41493 8.85096 3.24479C8.69391 3.07465 8.61538 2.86806 8.61538 2.625V1.16667H3.23077V7ZM12.9231 7.58333C12.9231 7.42535 12.8698 7.28863 12.7632 7.17318C12.6567 7.05773 12.5304 7 12.3846 7C12.2388 7 12.1126 7.05773 12.006 7.17318C11.8994 7.28863 11.8462 7.42535 11.8462 7.58333C11.8462 7.74132 11.8994 7.87804 12.006 7.99349C12.1126 8.10894 12.2388 8.16667 12.3846 8.16667C12.5304 8.16667 12.6567 8.10894 12.7632 7.99349C12.8698 7.87804 12.9231 7.74132 12.9231 7.58333ZM14 7.58333V11.375C14 11.454 13.9734 11.5224 13.9201 11.5801C13.8668 11.6378 13.8037 11.6667 13.7308 11.6667H11.8462V13.125C11.8462 13.3681 11.7676 13.5747 11.6106 13.7448C11.4535 13.9149 11.2628 14 11.0385 14H2.96154C2.73718 14 2.54647 13.9149 2.38942 13.7448C2.23237 13.5747 2.15385 13.3681 2.15385 13.125V11.6667H0.269231C0.196314 11.6667 0.133213 11.6378 0.0799279 11.5801C0.0266426 11.5224 0 11.454 0 11.375V7.58333C0 7.1033 0.158453 6.69162 0.475361 6.34831C0.792268 6.00499 1.17228 5.83333 1.61538 5.83333H2.15385V0.875C2.15385 0.631944 2.23237 0.425347 2.38942 0.255208C2.54647 0.0850694 2.73718 0 2.96154 0H8.61538C8.83974 0 9.08654 0.0607639 9.35577 0.182292C9.625 0.303819 9.83814 0.449653 9.99519 0.619792L11.274 2.00521C11.4311 2.17535 11.5657 2.40625 11.6779 2.69792C11.7901 2.98958 11.8462 3.25694 11.8462 3.5V5.83333H12.3846C12.8277 5.83333 13.2077 6.00499 13.5246 6.34831C13.8415 6.69162 14 7.1033 14 7.58333Z" fill="currentColor"/>
                                        </svg>
                                        <span class="print-code-text f-13 leading-20">Copy Link</span>
                                    </a>

                                    <a href="<?=$this->siteUrl().'/'.PUBLIC_PATH.'/'.UPLOADS_PATH?>/qr_image/<?=e($data['user']['qr_image'])?>" download class="ml-12 update-code text-gray-100 d-flex justify-content-center align-items-center">
                                        <span class="print-code-text f-13 leading-20">Dowload QrCode</span>
                                    </a>
                                </div>
                            </div>

                            <div class="right-qr-code">
                                <div class="profile-qr-img" id="userProfileQrCode">
                                    <img class="qrCodeImage" src="<?=$this->siteUrl().'/'.PUBLIC_PATH.'/'.UPLOADS_PATH?>/qr_image/<?=e($data['user']['qr_image'])?>" alt="QrCode" />
                                </div>
                            </div>
                        </div>

                        <!-- Password Div -->
                        <div class="profile-qr-bootom d-flex justify-content-between align-items-center mt-26">
                            <div class="d-flex align-items-center">
                                <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M5.5 7.33301C5.5 4.29544 7.96244 1.83301 11 1.83301C13.2568 1.83301 15.1941 3.19209 16.042 5.13267C16.2446 5.59658 16.0329 6.13697 15.569 6.33967C15.1051 6.54236 14.5647 6.3306 14.362 5.86668C13.7953 4.56977 12.5021 3.66634 11 3.66634C8.97496 3.66634 7.33333 5.30796 7.33333 7.33301V8.25117C7.55123 8.24967 7.78286 8.24967 8.02881 8.24968H13.9712C14.7091 8.24966 15.3181 8.24965 15.8142 8.29019C16.3294 8.33228 16.8031 8.42264 17.248 8.64932C17.9379 9.00085 18.4988 9.56178 18.8504 10.2517C19.077 10.6966 19.1674 11.1702 19.2095 11.6855C19.25 12.1816 19.25 12.7906 19.25 13.5285V14.8876C19.25 15.6254 19.25 16.2344 19.2095 16.7305C19.1674 17.2458 19.077 17.7194 18.8504 18.1643C18.4988 18.8542 17.9379 19.4152 17.248 19.7667C16.8031 19.9934 16.3294 20.0837 15.8142 20.1258C15.3181 20.1664 14.7091 20.1664 13.9712 20.1663H8.02879C7.29091 20.1664 6.68192 20.1664 6.18583 20.1258C5.67057 20.0837 5.19693 19.9934 4.75204 19.7667C4.06211 19.4152 3.50118 18.8542 3.14964 18.1643C2.92296 17.7194 2.83261 17.2458 2.79051 16.7305C2.74998 16.2344 2.74999 15.6254 2.75 14.8875V13.5285C2.74999 12.7906 2.74998 12.1816 2.79051 11.6855C2.83261 11.1702 2.92296 10.6966 3.14964 10.2517C3.50118 9.56178 4.06211 9.00085 4.75204 8.64932C4.9923 8.5269 5.24094 8.44424 5.5 8.38747V7.33301ZM6.33512 10.1174C5.93324 10.1503 5.72772 10.2098 5.58435 10.2828C5.23939 10.4586 4.95892 10.7391 4.78316 11.084C4.71011 11.2274 4.65059 11.4329 4.61776 11.8348C4.58405 12.2474 4.58333 12.7811 4.58333 13.5663V14.8497C4.58333 15.6349 4.58405 16.1686 4.61776 16.5812C4.65059 16.9831 4.71011 17.1886 4.78316 17.332C4.95892 17.677 5.23939 17.9574 5.58435 18.1332C5.72772 18.2062 5.93324 18.2658 6.33512 18.2986C6.7477 18.3323 7.28147 18.333 8.06667 18.333H13.9333C14.7185 18.333 15.2523 18.3323 15.6649 18.2986C16.0668 18.2658 16.2723 18.2062 16.4157 18.1332C16.7606 17.9574 17.0411 17.677 17.2168 17.332C17.2899 17.1886 17.3494 16.9831 17.3822 16.5812C17.416 16.1686 17.4167 15.6349 17.4167 14.8497V13.5663C17.4167 12.7811 17.416 12.2474 17.3822 11.8348C17.3494 11.4329 17.2899 11.2274 17.2168 11.084C17.0411 10.7391 16.7606 10.4586 16.4157 10.2828C16.2723 10.2098 16.0668 10.1503 15.6649 10.1174C15.2523 10.0837 14.7185 10.083 13.9333 10.083H8.06667C7.28147 10.083 6.7477 10.0837 6.33512 10.1174ZM11 12.3747C11.5063 12.3747 11.9167 12.7851 11.9167 13.2913V15.1247C11.9167 15.6309 11.5063 16.0413 11 16.0413C10.4937 16.0413 10.0833 15.6309 10.0833 15.1247V13.2913C10.0833 12.7851 10.4937 12.3747 11 12.3747Z" fill="currentColor"/>
                                </svg>
                                <p class="ml-12 mb-0 f-16 leading-20 gilroy-medium text-dark">Change Password</p>
                            </div>

                            <div class="d-flex align-items-center">
                                <div class="mb-0 f-16 leading-20 gilroy-medium d-flex align-items-center text-gray-100 password-text pass-height">*************</div>
                                <div class="cursor-pointer" data-bs-toggle="modal" data-bs-target="#exampleModal-2">
                                    <svg class="ml-14" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect width="24" height="24" rx="4" fill="#2e2f44" />
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M14.2139 6.62899C14.925 5.91794 16.0778 5.91794 16.7889 6.62899C17.4999 7.34005 17.4999 8.4929 16.7889 9.20395L9.46059 16.5322C9.44904 16.5438 9.4376 16.5552 9.42624 16.5666C9.25857 16.7346 9.11073 16.8827 8.9325 16.9919C8.77592 17.0879 8.60522 17.1586 8.42666 17.2015C8.2234 17.2503 8.01413 17.2501 7.77678 17.2498C7.76071 17.2498 7.74451 17.2498 7.72818 17.2498H6.75136C6.4292 17.2498 6.16803 16.9886 6.16803 16.6665V15.6897C6.16803 15.6733 6.16801 15.6571 6.168 15.6411C6.16778 15.4037 6.16758 15.1945 6.21638 14.9912C6.25925 14.8126 6.32996 14.6419 6.42591 14.4853C6.53513 14.3071 6.68324 14.1593 6.85123 13.9916C6.8626 13.9803 6.87407 13.9688 6.88562 13.9573L14.2139 6.62899ZM15.9639 7.45395C15.7085 7.19851 15.2943 7.19851 15.0389 7.45395L7.71058 14.7822C7.48916 15.0036 7.44834 15.0498 7.42065 15.0949C7.38867 15.1471 7.3651 15.204 7.35081 15.2635C7.33844 15.3151 7.33469 15.3765 7.33469 15.6897V16.0831H7.72818C8.0413 16.0831 8.10279 16.0794 8.1543 16.067C8.21382 16.0527 8.27073 16.0292 8.32292 15.9972C8.36809 15.9695 8.41422 15.9287 8.63563 15.7073L15.9639 8.37899C16.2193 8.12355 16.2193 7.7094 15.9639 7.45395ZM11.418 16.6665C11.418 16.3443 11.6792 16.0831 12.0013 16.0831H17.2513C17.5735 16.0831 17.8347 16.3443 17.8347 16.6665C17.8347 16.9886 17.5735 17.2498 17.2513 17.2498H12.0013C11.6792 17.2498 11.418 16.9886 11.418 16.6665Z" fill="white"/>
                                    </svg>
                                </div>

                                <div class="modal fade modal-overly" id="exampleModal-2" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"  role="dialog">
                                    <div class="modal-dialog modal-dialog-centered modal-lg res-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header w-modal-header">
                                                <p class="modal-title gilroy-Semibold text-dark">Change Password</p>
                                                <button type="button" class="cursor-pointer close-btn" data-bs-dismiss="modal" aria-label="Close">
                                                    <span class="close-div position-absolute modal-close-btn rtl-wrap-four text-gray-100 d-flex align-items-center justify-content-center">
                                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M5.24408 5.24408C5.56951 4.91864 6.09715 4.91864 6.42259 5.24408L10 8.82149L13.5774 5.24408C13.9028 4.91864 14.4305 4.91864 14.7559 5.24408C15.0814 5.56951 15.0814 6.09715 14.7559 6.42259L11.1785 10L14.7559 13.5774C15.0814 13.9028 15.0814 14.4305 14.7559 14.7559C14.4305 15.0814 13.9028 15.0814 13.5774 14.7559L10 11.1785L6.42259 14.7559C6.09715 15.0814 5.56951 15.0814 5.24408 14.7559C4.91864 14.4305 4.91864 13.9028 5.24408 13.5774L8.82149 10L5.24408 6.42259C4.91864 6.09715 4.91864 5.56951 5.24408 5.24408Z" fill="currentColor"/>
                                                        </svg>
                                                    </span>
                                                </button>
                                            </div>

                                            <div class="modal-body modal-body-pxy">
                                                <form id="password-form">
                                                    <div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="label-top mt-withdraw">
                                                                    <label class="gilroy-medium text-gray-100 mb-2 f-14 leading-17 r-mt-amount r-mt-6" for="oldPassword">Old Password</label>
                                                                    <input type="password" class="form-control input-form-control input-form-control-withdraw apply-bg" name="oldPassword" id="oldPassword" placeholder="Old Password" autocomplete="off">
                                                                    <span id="password-error"></span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="label-top mt-withdraw position-r">
                                                                    <label class="gilroy-medium text-gray-100 mb-2 f-14 leading-17 mt-20 r-mt-amount r-mt-6" for="password">New Password</label>
                                                                    <input type="password" class="form-control input-form-control input-form-control-withdraw apply-bg" name="password" id="password" placeholder="New Password" autocomplete="off">

                                                                    <p class="mb-0 text-gray-100 dark-B87 gilroy-regular f-12 mt-2"><em>*Password should contain minimum 6 characters</em></p>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="label-top mt-withdraw">
                                                                    <label class="gilroy-medium text-gray-100 mb-2 f-14 leading-17 mt-20 r-mt-amount r-mt-6" for="confirmPassword"> Confirm Password</label>
                                                                    <input type="password" class="form-control input-form-control input-form-control-withdraw apply-bg" name="confirmPassword" id="confirmPassword" placeholder="Confirm Password" autocomplete="off">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row mt-20">
                                                        <div class="col-md-12 pd-bottom pb-2">
                                                        	<?=$this->token()?>
                                                            <button type="submit" class="btn bg-primary add-option-btn w-100 setting-btn f-16 leading-20 gilroy-medium" id="editPassword">
                                                                <span>Save Changes</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Phone Div -->
                        <div class="profile-qr-bootom d-flex justify-content-between align-items-center mt-26">
                            <div class="d-flex align-items-center">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="m14,1V0c5.514,0,10,4.486,10,10h-1c0-4.962-4.038-9-9-9Zm5,9h1c0-3.309-2.691-6-6-6v1c2.757,0,5,2.243,5,5Zm-1.473,3.837l5.679,5.679-2.879,2.879c-1.033,1.035-2.432,1.605-3.941,1.605C9.189,24,0,14.812,0,7.613,0,6.104.57,4.705,1.605,3.672L4.484.793l5.679,5.679-3.373,3.373c1.506,3.559,3.919,5.974,7.36,7.369l3.377-3.377Zm4.265,5.679l-4.265-4.265-3.13,3.131-.303-.116c-3.92-1.496-6.732-4.306-8.357-8.352l-.123-.307,3.135-3.135L4.484,2.207l-2.172,2.172c-.846.844-1.312,1.993-1.312,3.234,0,6.615,8.772,15.387,15.387,15.387,1.241,0,2.389-.466,3.233-1.312l2.172-2.172Z" fill="currentColor" />
                                </svg>
                                <p class="ml-12 mb-0 f-16 leading-20 gilroy-medium text-dark">Phone</p>
                            </div>

                            <div class="d-flex align-items-center">
                                <div class="mb-0 f-16 leading-20 gilroy-medium d-flex align-items-center text-gray-100 phone-text pass-height"><?= !empty($data['user']['phone']) ? e($data['user']['phone']) : 'N/A' ?></div>
                                <div class="cursor-pointer" data-bs-toggle="modal" data-bs-target="#exampleModal-4">
                                    <svg class="ml-14" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect width="24" height="24" rx="4" fill="#2e2f44" />
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M14.2139 6.62899C14.925 5.91794 16.0778 5.91794 16.7889 6.62899C17.4999 7.34005 17.4999 8.4929 16.7889 9.20395L9.46059 16.5322C9.44904 16.5438 9.4376 16.5552 9.42624 16.5666C9.25857 16.7346 9.11073 16.8827 8.9325 16.9919C8.77592 17.0879 8.60522 17.1586 8.42666 17.2015C8.2234 17.2503 8.01413 17.2501 7.77678 17.2498C7.76071 17.2498 7.74451 17.2498 7.72818 17.2498H6.75136C6.4292 17.2498 6.16803 16.9886 6.16803 16.6665V15.6897C6.16803 15.6733 6.16801 15.6571 6.168 15.6411C6.16778 15.4037 6.16758 15.1945 6.21638 14.9912C6.25925 14.8126 6.32996 14.6419 6.42591 14.4853C6.53513 14.3071 6.68324 14.1593 6.85123 13.9916C6.8626 13.9803 6.87407 13.9688 6.88562 13.9573L14.2139 6.62899ZM15.9639 7.45395C15.7085 7.19851 15.2943 7.19851 15.0389 7.45395L7.71058 14.7822C7.48916 15.0036 7.44834 15.0498 7.42065 15.0949C7.38867 15.1471 7.3651 15.204 7.35081 15.2635C7.33844 15.3151 7.33469 15.3765 7.33469 15.6897V16.0831H7.72818C8.0413 16.0831 8.10279 16.0794 8.1543 16.067C8.21382 16.0527 8.27073 16.0292 8.32292 15.9972C8.36809 15.9695 8.41422 15.9287 8.63563 15.7073L15.9639 8.37899C16.2193 8.12355 16.2193 7.7094 15.9639 7.45395ZM11.418 16.6665C11.418 16.3443 11.6792 16.0831 12.0013 16.0831H17.2513C17.5735 16.0831 17.8347 16.3443 17.8347 16.6665C17.8347 16.9886 17.5735 17.2498 17.2513 17.2498H12.0013C11.6792 17.2498 11.418 16.9886 11.418 16.6665Z" fill="white"/>
                                    </svg>
                                </div>

                                <div class="modal fade modal-overly" id="exampleModal-4" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"  role="dialog">
                                    <div class="modal-dialog modal-dialog-centered modal-lg res-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header w-modal-header">
                                                <p class="modal-title gilroy-Semibold text-dark">Add Phone</p>
                                                <button type="button" class="cursor-pointer close-btn" data-bs-dismiss="modal" aria-label="Close">
                                                    <span class="close-div position-absolute modal-close-btn rtl-wrap-four text-gray-100 d-flex align-items-center justify-content-center">
                                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M5.24408 5.24408C5.56951 4.91864 6.09715 4.91864 6.42259 5.24408L10 8.82149L13.5774 5.24408C13.9028 4.91864 14.4305 4.91864 14.7559 5.24408C15.0814 5.56951 15.0814 6.09715 14.7559 6.42259L11.1785 10L14.7559 13.5774C15.0814 13.9028 15.0814 14.4305 14.7559 14.7559C14.4305 15.0814 13.9028 15.0814 13.5774 14.7559L10 11.1785L6.42259 14.7559C6.09715 15.0814 5.56951 15.0814 5.24408 14.7559C4.91864 14.4305 4.91864 13.9028 5.24408 13.5774L8.82149 10L5.24408 6.42259C4.91864 6.09715 4.91864 5.56951 5.24408 5.24408Z" fill="currentColor"/>
                                                        </svg>
                                                    </span>
                                                </button>
                                            </div>

                                            <div class="modal-body modal-body-pxy">
                                                <form id="phone-form">
						                            <input type="hidden" id="formattedPhone" name="formattedPhone" value="<?=e($data['user']['phone'])?>">
						                            <input type="hidden" id="country" name="country" value="<?=e($data['user']['country'])?>">
                                                    <div>
                                                        <!-- Phone -->
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="label-top mt-withdraw">
                                                                    <label id="subheader_text" class="gilroy-medium text-gray-100 mb-2 f-14 leading-17 r-mt-amount r-mt-6" for="phone">Phone</label>

                                                                    <div class="phone_group">
                                                                        <input type="tel" class="form-control apply-bg" id="phone" value="<?=e($data['user']['phone'])?>" autocomplete="off">
                                                                        <span id="phone-error"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row mt-20">
                                                        <div class="col-md-12 pd-bottom pb-2">
                                                        	<?=$this->token()?>
                                                            <button type="button" class="btn bg-primary add-option-btn w-100 setting-btn f-16 leading-20 gilroy-medium common_button next" id="phoneBtn">
                                                                <span>Add phone</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Personal Information Div -->
            <div class="profile-personal-information bg-white mt-18">
                <div class="d-flex align-items-center">
                    <p class="mb-0 f-24 leading-30 gilroy-Semibold text-dark">Personal Information</p>
                    <div class="hover-qr-code cursor-pointer wallet-svg position-r">
                        <a href="" data-bs-toggle="modal" data-bs-target="#exampleModal-3">
                            <svg class="ml-12" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M11.8448 2.09484C12.759 1.18063 14.2412 1.18063 15.1554 2.09484C16.0696 3.00905 16.0696 4.49129 15.1554 5.4055L5.73337 14.8276C5.71852 14.8424 5.70381 14.8571 5.68921 14.8718C5.47363 15.0878 5.28355 15.2782 5.0544 15.4186C4.85309 15.542 4.63361 15.6329 4.40403 15.688C4.1427 15.7507 3.87364 15.7505 3.56847 15.7502C3.54781 15.7502 3.52698 15.7502 3.50598 15.7502H2.25008C1.83586 15.7502 1.50008 15.4144 1.50008 15.0002V13.7443C1.50008 13.7233 1.50006 13.7025 1.50004 13.6818C1.49975 13.3766 1.4995 13.1076 1.56224 12.8462C1.61736 12.6167 1.70827 12.3972 1.83164 12.1959C1.97206 11.9667 2.16249 11.7766 2.37848 11.5611C2.3931 11.5465 2.40784 11.5317 2.42269 11.5169L11.8448 2.09484ZM14.0948 3.1555C13.7663 2.82707 13.2339 2.82707 12.9054 3.1555L3.48335 12.5776C3.19868 12.8622 3.14619 12.9215 3.1106 12.9796C3.06948 13.0467 3.03917 13.1199 3.0208 13.1964C3.0049 13.2626 3.00008 13.3417 3.00008 13.7443V14.2502H3.50598C3.90857 14.2502 3.98762 14.2453 4.05386 14.2294C4.13039 14.2111 4.20354 14.1808 4.27065 14.1396C4.32873 14.1041 4.38804 14.0516 4.67271 13.7669L14.0948 4.34484C14.4232 4.01641 14.4232 3.48393 14.0948 3.1555ZM8.25006 15.0002C8.25006 14.586 8.58584 14.2502 9.00006 14.2502H15.7501C16.1643 14.2502 16.5001 14.586 16.5001 15.0002C16.5001 15.4144 16.1643 15.7502 15.7501 15.7502H9.00006C8.58584 15.7502 8.25006 15.4144 8.25006 15.0002Z" fill="currentColor"/>
                            </svg>
                        </a>
                    </div>

                    <div class="modal fade modal-overly" id="exampleModal-3" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"  role="dialog">
                        <div class="modal-dialog modal-dialog-centered modal-lg res-dialog">
                            <div class="modal-content">
                                <div class="modal-header w-modal-header">
                                    <p class="modal-title gilroy-Semibold text-dark">Profile Information</p>
                                    <button type="button" class="cursor-pointer close-btn" data-bs-dismiss="modal" aria-label="Close">
                                        <span class="close-div position-absolute modal-close-btn rtl-wrap-four text-gray-100 d-flex align-items-center justify-content-center">
                                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M5.24408 5.24408C5.56951 4.91864 6.09715 4.91864 6.42259 5.24408L10 8.82149L13.5774 5.24408C13.9028 4.91864 14.4305 4.91864 14.7559 5.24408C15.0814 5.56951 15.0814 6.09715 14.7559 6.42259L11.1785 10L14.7559 13.5774C15.0814 13.9028 15.0814 14.4305 14.7559 14.7559C14.4305 15.0814 13.9028 15.0814 13.5774 14.7559L10 11.1785L6.42259 14.7559C6.09715 15.0814 5.56951 15.0814 5.24408 14.7559C4.91864 14.4305 4.91864 13.9028 5.24408 13.5774L8.82149 10L5.24408 6.42259C4.91864 6.09715 4.91864 5.56951 5.24408 5.24408Z" fill="currentColor"/>
                                            </svg>
                                        </span>
                                    </button>
                                </div>

                                <div class="modal-body modal-body-pxy">
                                    <form id="profile-form">
                                        <div class="row">
                                            <!-- First Name -->
                                            <div class="col-6 column-pr-unset2">
                                                <div class="label-top mt-withdraw">
                                                    <label class="gilroy-medium text-gray-100 mb-2 f-14 leading-17 r-mt-amount r-mt-6" for="firstname">First Name <span class="f-16 text-F30">*</span></label>
                                                    <input type="text" class="form-control input-form-control input-form-control-withdraw apply-bg" name="firstname" id="firstname" value="<?=e($data['user']['firstname'])?>" autocomplete="off">
                                                </div>
                                            </div>

                                            <!-- Last Name -->
                                            <div class="col-6 column-pl-unset2">
                                                <div class="label-top mt-withdraw position-r">
                                                    <label class="gilroy-medium text-gray-100 mb-2 f-14 leading-17 r-mt-amount r-mt-6" for="lastname">Last Name <span class="f-16 text-F30">*</span></label>
                                                    <input type="text" class="form-control input-form-control input-form-control-withdraw apply-bg" name="lastname" id="lastname" value="<?=e($data['user']['lastname'])?>" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <!-- Adress 1 -->
                                            <div class="col-6 column-pl-unset2">
                                                <div class="label-top mt-withdraw">
                                                    <label class="gilroy-medium text-gray-100 mb-2 f-14 leading-17 mt-20 r-mt-amount r-mt-6" for="address_1">Adress 1</label>
                                                    <textarea class="form-control input-form-control input-form-control-withdraw apply-bg" name="address_1" id="address_1" rows="2"><?=e($data['user']['address_1'])?></textarea>
                                                </div>
                                            </div>

                                            <!-- Adress 2 -->
                                            <div class="col-6 column-pl-unset2">
                                                <div class="label-top mt-withdraw">
                                                    <label class="gilroy-medium text-gray-100 mb-2 f-14 leading-17 mt-20 r-mt-amount r-mt-6" for="address_2">Adress 2</label>
                                                    <textarea class="form-control input-form-control input-form-control-withdraw apply-bg" name="address_2" id="address_2" rows="2"><?=e($data['user']['address_2'])?></textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <!-- City -->
                                            <div class="col-6 column-pr-unset2">
                                                <div class="label-top mt-withdraw">
                                                    <label class="gilroy-medium text-gray-100 mb-2 f-14 leading-17 mt-20 r-mt-amount r-mt-6" for="city">City</label>
                                                    <input type="text" class="form-control input-form-control input-form-control-withdraw apply-bg" name="city" id="city" value="<?=e($data['user']['city'])?>" autocomplete="off">
                                                </div>
                                            </div>

                                            <!-- State -->
                                            <div class="col-6 column-pl-unset2">
                                                <div class="label-top mt-withdraw position-r">
                                                    <label class="gilroy-medium text-gray-100 mb-2 f-14 leading-17 mt-20 r-mt-amount r-mt-6" for="state">State</label>
                                                    <input type="text" class="form-control input-form-control input-form-control-withdraw apply-bg" name="state" id="state" value="<?=e($data['user']['state'])?>" autocomplete="off" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <!-- Country -->
                                            <div class="col-6 column-pr-unset2">
                                                <div class="param-ref param-ref-withdraw param-ref-withdraw-modal money-ref-2">
                                                    <label class="gilroy-medium text-gray-100 mb-2 f-14 leading-17 mt-20 r-mt-0" for="country-list">Country</label>
                                                    <input type="text" class="form-control input-form-control input-form-control-withdraw apply-bg" name="country" id="country-list" value="<?=e($data['user']['country'])?>" autocomplete="off" readonly>
                                                </div>
                                            </div>

                                            <!-- Timezone -->
                                            <div class="col-6 column-pl-unset2">
                                                <div class="param-ref param-ref-withdraw param-ref-withdraw-modal money-ref-2">
                                                    <label class="gilroy-medium text-gray-100 mb-2 f-14 leading-17 mt-20 r-mt-0" for="timezone">Time Zone</label>
                                                    <select class="select2" name="timezone" id="timezone" autocomplete="off">
                                                        <?php foreach(timezone_identifiers_list() as $value): ?>
											            	<option value="<?=e($value)?>"<?=(e($data['user']['timezone']) == $value ? ' selected' : '')?>><?=e($value)?></option>
											           	<?php endforeach ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-20">
                                            <div class="col-md-12 pd-bottom pb-2">
                                            	<?=$this->token()?>
                                                <button type="submit" class="btn bg-primary add-option-btn w-100 setting-btn f-16 leading-20 gilroy-medium" id="profileBtn">
                                                    <span>Save Changes</span>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Personal Information View Div  -->
                <div class="profile-info-body d-flex profile-wraps justify-content-between mt-36">
                    <div class="left-profile-info w-50">
                        <div class="d-flex gap-3 justify-content-between profile-borders-bottom">
                            <p class="mb-0 f-15 leading-18 text-dark gilroy-medium text-align-initial">Name</p>
                            <p class="mb-0 f-15 leading-18 text-gray-100 gilroy-medium text-align-end"><?=e($data['user']['firstname'])?> <?=e($data['user']['lastname'])?></p>
                        </div>

                        <div class="d-flex gap-3 justify-content-between profile-borders-bottom">
                            <p class="mb-0 f-15 leading-18 text-dark gilroy-medium text-align-initial">Email</p>
                            <p class="mb-0 f-15 leading-18 text-gray gilroy-medium text-align-end" style="text-transform: lowercase;"><?=e($data['user']['email'])?></p>
                        </div>

                        <div class="d-flex gap-3 justify-content-between profile-borders-bottom">
                            <p class="mb-0 f-15 leading-18 text-dark gilroy-medium text-align-initial">Address 1</p>
                            <p class="mb-0 f-15 leading-18 text-gray-100 gilroy-medium text-align-end"><?= !empty($data['user']['address_1']) ? e($data['user']['address_1']) : 'N/A' ?></p>
                        </div>

                        <div class="d-flex gap-3 justify-content-between profile-bottom b-unset">
                            <p class="mb-0 f-15 leading-18 text-dark gilroy-medium text-align-initial">Address 2</p>
                            <p class="mb-0 f-15 leading-18 text-gray-100 gilroy-medium text-align-end"><?= !empty($data['user']['address_2']) ? e($data['user']['address_2']) : 'N/A' ?></p>
                        </div>
                    </div>

                    <div class="ml-76 left-profile-info w-50">
                        <div class="d-flex gap-3 justify-content-between profile-borders-bottom responsive-mtop">
                            <p class="mb-0 f-15 leading-18 text-dark gilroy-medium text-align-initial">City</p>
                            <p class="mb-0 f-15 leading-18 text-gray-100 gilroy-medium text-align-end"><?= !empty($data['user']['city']) ? e($data['user']['city']) : 'N/A' ?></p>
                        </div>

                        <div class="d-flex gap-3 justify-content-between profile-borders-bottom">
                            <p class="mb-0 f-15 leading-18 text-dark gilroy-medium text-align-initial">State</p>
                            <p class="mb-0 f-15 leading-18 text-gray-100 gilroy-medium text-align-end"><?= !empty($data['user']['state']) ? e($data['user']['state']) : 'N/A' ?></p>
                        </div>

                        <div class="d-flex gap-3 justify-content-between profile-borders-bottom">
                            <p class="mb-0 f-15 leading-18 text-dark gilroy-medium text-align-initial">Country</p>
                            <p class="mb-0 f-15 leading-18 text-gray-100 gilroy-medium text-align-end"><?= !empty($data['user']['country']) ? e($data['user']['country']) : 'N/A' ?></p>
                        </div>

                        <div class="d-flex gap-3 justify-content-between profile-bottom b-unset">
                            <p class="mb-0 f-15 leading-18 text-dark gilroy-medium text-align-initial">Time Zone</p>
                            <p class="mb-0 f-15 leading-18 text-gray-100 gilroy-medium text-align-end"><?= !empty($data['user']['timezone']) ? e($data['user']['timezone']) : 'N/A' ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- main-containt -->

            <div class="row">
                <div class="col-xl-12 col-xxl-12">
                    <!-- Delete Account Div -->
                    <div class="default-wallet-div d-flex justify-content-between bg-white mt-24">
                        <div class="wallet-text d-flex">
                            <p class="wallet-text-hover mb-0 text-dark f-20 leading-25 gilroy-Semibold">Delete Account</p>
                        </div>
                        <a href="" data-bs-toggle="modal" data-bs-target="#deleteModal" class="mb-0 f-20 leading-25 gilroy-Semibold text-uppercase text-primary">
                            <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M5.66406 1.49996C5.66406 1.03972 6.03716 0.666626 6.4974 0.666626H11.4974C11.9576 0.666626 12.3307 1.03972 12.3307 1.49996C12.3307 1.9602 11.9576 2.33329 11.4974 2.33329H6.4974C6.03716 2.33329 5.66406 1.9602 5.66406 1.49996ZM3.1576 3.16663H1.4974C1.03716 3.16663 0.664062 3.53972 0.664062 3.99996C0.664062 4.4602 1.03716 4.83329 1.4974 4.83329H2.38443L2.91918 12.8545C2.96114 13.484 2.99586 14.005 3.05812 14.429C3.12294 14.8705 3.22577 15.274 3.43997 15.65C3.77342 16.2353 4.27639 16.7058 4.88259 16.9996C5.272 17.1883 5.68139 17.2641 6.12622 17.2994C6.55347 17.3333 7.07559 17.3333 7.70651 17.3333H10.2883C10.9192 17.3333 11.4413 17.3333 11.8686 17.2994C12.3134 17.2641 12.7228 17.1883 13.1122 16.9996C13.7184 16.7058 14.2214 16.2353 14.5548 15.65C14.769 15.274 14.8718 14.8705 14.9367 14.429C14.9989 14.005 15.0337 13.484 15.0756 12.8544L15.6104 4.83329H16.4974C16.9576 4.83329 17.3307 4.4602 17.3307 3.99996C17.3307 3.53972 16.9576 3.16663 16.4974 3.16663H14.8372C14.8323 3.16658 14.8275 3.16658 14.8226 3.16663H3.17218C3.16733 3.16658 3.16247 3.16658 3.1576 3.16663ZM13.94 4.83329H4.0548L4.57995 12.7106C4.62468 13.3814 4.6556 13.8361 4.70711 14.1869C4.75714 14.5277 4.81827 14.7023 4.88812 14.825C5.05485 15.1176 5.30633 15.3529 5.60943 15.4998C5.73643 15.5613 5.91478 15.6107 6.25811 15.638C6.61158 15.666 7.06727 15.6666 7.73961 15.6666H10.2552C10.9275 15.6666 11.3832 15.666 11.7367 15.638C12.08 15.6107 12.2584 15.5613 12.3854 15.4998C12.6885 15.3529 12.9399 15.1176 13.1067 14.825C13.1765 14.7023 13.2376 14.5277 13.2877 14.1869C13.3392 13.8361 13.3701 13.3814 13.4148 12.7106L13.94 4.83329ZM7.33073 6.91663C7.79097 6.91663 8.16406 7.28972 8.16406 7.74996V11.9166C8.16406 12.3769 7.79097 12.75 7.33073 12.75C6.87049 12.75 6.4974 12.3769 6.4974 11.9166V7.74996C6.4974 7.28972 6.87049 6.91663 7.33073 6.91663ZM10.6641 6.91663C11.1243 6.91663 11.4974 7.28972 11.4974 7.74996V11.9166C11.4974 12.3769 11.1243 12.75 10.6641 12.75C10.2038 12.75 9.83073 12.3769 9.83073 11.9166V7.74996C9.83073 7.28972 10.2038 6.91663 10.6641 6.91663Z" fill="currentColor" />
                            </svg>
                        </a>

                        <div class="modal fade modal-overly" id="deleteModal" aria-labelledby="edit-modal-header" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"  role="dialog">
                            <div class="delete-custom-modal">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="edit-modal-header">
                                            <p class="modal-title f-20 gilroy-Semibold text-dark mb-0">Delete Account</p>
                                            <button type="button" class="b-unset" data-bs-dismiss="modal" aria-label="Close">
                                                <span class="close-div position-absolute modal-close-btn btn-close rtl-wrap-four text-gray-100 d-flex align-items-center justify-content-center">
                                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M5.24408 5.24408C5.56951 4.91864 6.09715 4.91864 6.42259 5.24408L10 8.82149L13.5774 5.24408C13.9028 4.91864 14.4305 4.91864 14.7559 5.24408C15.0814 5.56951 15.0814 6.09715 14.7559 6.42259L11.1785 10L14.7559 13.5774C15.0814 13.9028 15.0814 14.4305 14.7559 14.7559C14.4305 15.0814 13.9028 15.0814 13.5774 14.7559L10 11.1785L6.42259 14.7559C6.09715 15.0814 5.56951 15.0814 5.24408 14.7559C4.91864 14.4305 4.91864 13.9028 5.24408 13.5774L8.82149 10L5.24408 6.42259C4.91864 6.09715 4.91864 5.56951 5.24408 5.24408Z" fill="currentColor"/>
                                                    </svg>
                                                </span>
                                            </button>
                                        </div>

                                        <div class="modal-body">
                                            <p class="mb-0 text-gray-100 f-16 leading-26 gilroy-medium">Are you sure you want to delete your account?</p>
                                        </div>

                                        <div class="modals-bottom d-flex justify-content-end delete-gap">
                                            <button class="btn btn-secondary-cancel f-14 leading-17 text-gray-100 gilroy-medium" data-bs-dismiss="modal">Cancel</button>

                                            <form id="delete-form">
                                                <?=$this->token()?>
                                                <button class="ml-delete btn btn-secondary-delete f-14 leading-17 text-dark gilroy-medium" id="delete-modal-yes" data-user="<?= e($data['user']['userid']) ?>">Yes, Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
