<?php
defined('FIR') OR exit();
/**
 * The template for displaying Example Create page
 */
?>
<?php 
    function formatCurrency($currency, $amount): string
    {
        switch ($currency) {
            case '€':
                return '€ ' . number_format($amount, 2);
            case '£':
                return '£ ' . number_format($amount, 2);
            case '$':
                return '$ ' . number_format($amount, 2);
            default:
                return $currency . ' ' . number_format($amount, 2);
        }
    }
?>
<div class="position-relative">
    <div class="containt-parent">
        <div class="main-containt">
            <!-- main-containt -->
            <div class="pb-34">
                <div class="px-61 pb-20 helper-size">
                    <p class="mb-0 f-26 gilroy-Semibold text-uppercase text-center text-dark">Wallet List</p>
                    <p class="mb-0 text-center f-16 leading-26 gilroy-medium text-gray-100 dark-c dark-p mt-8">
                        Overview of your crypto wallets with real-time balance conversions, giving you insights into your balance's worth across different cryptocurrencies.
                    </p>
                </div>

                <div class="px-28 helper-div">
                    <div class="row r-mt-n">
                        <div class="col-12 col-xl-6 mt-19">
                            <div class="balance-box">
                                <div class="d-flex justify-content-between">
                                    <?php if ($data['user']['currency'] == "€"): ?>
                                        <div class="wallet-left-box d-flex gap-18">
                                            <div class="curency-box d-flex align-items-center justify-content-center">
                                                <img src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/uploads/currency_logos/icons8-euro-64.png" alt="Currency" />
                                            </div>

                                            <div class="mt-n3p span-currency">
                                                <span class="f-15 gilroy-medium text-gray">Fiat</span>
                                                <p class="mb-0 mt-6"><span class="f-28 gilroy-Semibold text-dark">EUR</span><span class="ml-2p f-15 text-primary gilroy-medium">(default)</span></p>
                                            </div>
                                        </div>
                                    <?php elseif ($data['user']['currency'] == "£"): ?>
                                        <div class="wallet-left-box d-flex gap-18">
                                            <div class="curency-box d-flex align-items-center justify-content-center">
                                                <img src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/uploads/currency_logos/icons8-british-pound-64.png" alt="Currency" />
                                            </div>

                                            <div class="mt-n3p span-currency">
                                                <span class="f-15 gilroy-medium text-gray">Fiat</span>
                                                <p class="mb-0 mt-6"><span class="f-28 gilroy-Semibold text-dark">GBP</span><span class="ml-2p f-15 text-primary gilroy-medium">(default)</span></p>
                                            </div>
                                        </div>
                                    <?php elseif ($data['user']['currency'] == "$"): ?>
                                        <div class="wallet-left-box d-flex gap-18">
                                            <div class="curency-box d-flex align-items-center justify-content-center">
                                                <img src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/uploads/currency_logos/icons8-us-dollar-64.png" alt="Currency" />
                                            </div>

                                            <div class="mt-n3p span-currency">
                                                <span class="f-15 gilroy-medium text-gray">Fiat</span>
                                                <p class="mb-0 mt-6"><span class="f-28 gilroy-Semibold text-dark">USD</span><span class="ml-2p f-15 text-primary gilroy-medium">(default)</span></p>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <div class="wallet-right-box mt-n3p span-currency text-end">
                                        <span class="f-15 gilroy-medium text-gray">Balance</span>
                                        <p class="mb-0 mt-6 f-28 gilroy-Semibold text-dark l-s2"><?= e(number_format($data['user']['interest_wallet'], 2)) ?></p>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between span-currency">
                                    <div class="currency-mt-32">
                                        <p class="text-gray mb-0 f-12 leading-16 gilroy-medium">
                                            Last Action:
                                            <span class="text-dark"><?= e($data['transaction']['details'] ?? 'No transaction available') ?></span>
                                        </p>
                                    </div>

                                    <div class="right-icon-div d-flex">
                                        <div class="btn-block d-flex mt-20">
                                            <div class="d-flex flex-wrap pt-5p wallet-svg show-tooltip" data-bs-toggle="tooltip" data-color="primary-bottom" data-bs-placement="bottom" title="deposit">
                                                <a href="<?=$this->siteUrl()?>/user/deposit" title="deposit">
                                                    <svg class="cursor-pointer deposit" width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M10.2534 6.9231H25.7478C26.345 6.92308 26.8601 6.92306 27.2845 6.95837C27.7324 6.99564 28.1778 7.07793 28.6065 7.30039C29.246 7.63225 29.7659 8.1618 30.0917 8.81313C30.3101 9.24972 30.3909 9.70344 30.4275 10.1596C30.4622 10.5918 30.4622 11.1165 30.4621 11.7248V16.1539C30.4621 16.7911 29.9549 17.3077 29.3293 17.3077C28.7036 17.3077 28.1964 16.7911 28.1964 16.1539V15H7.8048V20.5385C7.8048 21.2037 7.80568 21.6333 7.8319 21.9602C7.85705 22.2737 7.89973 22.3899 7.92827 22.4469C8.03689 22.664 8.21019 22.8406 8.42335 22.9512C8.47937 22.9802 8.59347 23.0237 8.90123 23.0493C9.22221 23.076 9.64395 23.0769 10.2971 23.0769H18.0006C18.6263 23.0769 19.1335 23.5935 19.1335 24.2308C19.1335 24.868 18.6263 25.3846 18.0006 25.3846H10.2535C9.65626 25.3847 9.14107 25.3847 8.71672 25.3494C8.26885 25.3121 7.82339 25.2298 7.39473 25.0073C6.75525 24.6755 6.23533 24.1459 5.90949 23.4946C5.69108 23.058 5.61029 22.6043 5.5737 22.1481C5.53903 21.7159 5.53904 21.1912 5.53906 20.583V11.7248C5.53904 11.1165 5.53903 10.5918 5.5737 10.1596C5.61029 9.70344 5.69108 9.24972 5.90949 8.81313C6.23533 8.1618 6.75525 7.63225 7.39473 7.30038C7.82339 7.07793 8.26885 6.99564 8.71673 6.95837C9.14107 6.92306 9.65625 6.92308 10.2534 6.9231ZM7.8048 12.6923H28.1964V11.7693C28.1964 11.104 28.1955 10.6745 28.1693 10.3475C28.1442 10.0341 28.1015 9.91785 28.0729 9.8608C27.9643 9.6437 27.791 9.46718 27.5778 9.35655C27.5218 9.32748 27.4077 9.28401 27.1 9.2584C26.779 9.23169 26.3573 9.23079 25.7041 9.23079H10.2971C9.64395 9.23079 9.22221 9.23169 8.90123 9.2584C8.59347 9.28401 8.47937 9.32748 8.42336 9.35655C8.21019 9.46717 8.03689 9.64369 7.92827 9.8608C7.89973 9.91785 7.85705 10.0341 7.8319 10.3475C7.80568 10.6745 7.8048 11.104 7.8048 11.7693V12.6923ZM25.9307 18.4616C26.5563 18.4616 27.0635 18.9782 27.0635 19.6154V23.7528L28.5282 22.2611C28.9706 21.8104 29.6879 21.8104 30.1303 22.2611C30.5727 22.7117 30.5727 23.4422 30.1303 23.8928L26.7317 27.3544C26.2893 27.805 25.572 27.805 25.1296 27.3544L21.731 23.8928C21.2886 23.4422 21.2886 22.7117 21.731 22.2611C22.1734 21.8104 22.8907 21.8104 23.3331 22.2611L24.7978 23.7529V19.6154C24.7978 18.9782 25.305 18.4616 25.9307 18.4616Z" fill="currentColor"
                                                        />
                                                    </svg>
                                                </a>
                                            </div>

                                            <div class="hr-40"></div>

                                            <div class="d-flex flex-wrap pt-5p wallet-svg show-tooltip" data-bs-toggle="tooltip" data-color="primary-bottom" data-bs-placement="bottom" title="withdraw">
                                                <a href="<?=$this->siteUrl()?>/user/payout" class="mt-1p">
                                                    <svg class="cursor-pointer withdraw" width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M10.2534 8.30774H25.7478C26.345 8.30772 26.8601 8.3077 27.2845 8.34301C27.7324 8.38028 28.1778 8.46257 28.6065 8.68503C29.246 9.0169 29.7659 9.54645 30.0917 10.1978C30.3101 10.6344 30.3909 11.0881 30.4275 11.5442C30.4622 11.9764 30.4622 12.5012 30.4621 13.1094V17.5385C30.4621 18.1758 29.9549 18.6924 29.3293 18.6924C28.7036 18.6924 28.1964 18.1758 28.1964 17.5385V16.3847H7.8048V21.9231C7.8048 22.5884 7.80568 23.0179 7.8319 23.3449C7.85705 23.6583 7.89973 23.7745 7.92827 23.8316C8.03689 24.0487 8.21019 24.2252 8.42335 24.3358C8.47937 24.3649 8.59347 24.4084 8.90123 24.434C9.22221 24.4607 9.64395 24.4616 10.2971 24.4616H18.0006C18.6263 24.4616 19.1335 24.9782 19.1335 25.6154C19.1335 26.2527 18.6263 26.7693 18.0006 26.7693H10.2535C9.65627 26.7693 9.14107 26.7693 8.71672 26.734C8.26885 26.6967 7.82339 26.6144 7.39473 26.392C6.75525 26.0601 6.23533 25.5306 5.90949 24.8792C5.69108 24.4427 5.61029 23.9889 5.5737 23.5328C5.53903 23.1006 5.53904 22.5758 5.53906 21.9676V13.1094C5.53904 12.5012 5.53903 11.9764 5.5737 11.5442C5.61029 11.0881 5.69108 10.6344 5.90949 10.1978C6.23533 9.54644 6.75525 9.0169 7.39473 8.68503C7.82339 8.46257 8.26885 8.38028 8.71673 8.34301C9.14107 8.3077 9.65625 8.30772 10.2534 8.30774ZM7.8048 14.077H28.1964V13.1539C28.1964 12.4886 28.1955 12.0591 28.1693 11.7322C28.1442 11.4187 28.1015 11.3025 28.0729 11.2454C27.9643 11.0283 27.791 10.8518 27.5778 10.7412C27.5218 10.7121 27.4077 10.6687 27.1 10.643C26.779 10.6163 26.3573 10.6154 25.7041 10.6154H10.2971C9.64395 10.6154 9.22221 10.6163 8.90123 10.643C8.59347 10.6687 8.47937 10.7121 8.42336 10.7412C8.21019 10.8518 8.03689 11.0283 7.92827 11.2454C7.89973 11.3025 7.85705 11.4187 7.8319 11.7322C7.80568 12.0591 7.8048 12.4886 7.8048 13.1539V14.077ZM25.1296 20.1842C25.572 19.7336 26.2893 19.7336 26.7317 20.1842L30.1303 23.6457C30.5727 24.0963 30.5727 24.8269 30.1303 25.2775C29.6879 25.7281 28.9706 25.7281 28.5282 25.2775L27.0635 23.7857V27.9231C27.0635 28.5604 26.5563 29.077 25.9307 29.077C25.305 29.077 24.7978 28.5604 24.7978 27.9231V23.7857L23.3331 25.2775C22.8907 25.7281 22.1734 25.7281 21.731 25.2775C21.2886 24.8269 21.2886 24.0963 21.731 23.6457L25.1296 20.1842Z" fill="currentColor"/>
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php foreach ($data['get-gateway'] as $index => $gateway): ?>
                            <div class="col-12 col-xl-6 mt-19 convert" id="gateway_<?=$index?>">
                                <div class="balance-box">
                                    <div class="d-flex justify-content-between">
                                        <div class="wallet-left-box d-flex gap-18">
                                            <div class="deposit-circle curency-box d-flex align-items-center justify-content-center">
                                                <img src="<?=$this->siteUrl().'/'.PUBLIC_PATH.'/'.UPLOADS_PATH?>/deposit/<?=e($gateway['image'])?>" alt="Currency">
                                            </div>

                                            <div class="mt-n3p span-currency">
                                                <span class="f-15 gilroy-medium text-gray">Crypto</span>
                                                <p class="mb-0 mt-6"><span class="f-28 gilroy-Semibold text-dark" style="text-transform: uppercase;"><?=e($gateway['name'])?></span><span class="ml-2p f-15 text-primary gilroy-medium"></span></p>
                                            </div>
                                        </div>

                                        <div class="wallet-right-box mt-n3p span-currency text-end">
                                            <span class="f-15 gilroy-medium text-gray">Balance</span>
                                            <input type="hidden" class="abbreviation" name="abbreviation" value="<?=e($gateway['abbreviation'])?>">
                                            <input type="hidden" class="amount" name="amount" value="<?=e($data['user']['interest_wallet'])?>">
                                            <p class="mb-0 mt-6 f-28 gilroy-Semibold text-dark l-s2 converted">0.00</p>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between span-currency">
                                        <div class="currency-mt-32">
                                            <p class="text-gray mb-0 f-12 leading-16 gilroy-medium">
                                                Last Action:
                                                <span class="text-dark">No transaction available.</span>
                                            </p>
                                        </div>

                                        <div class="right-icon-div d-flex">
                                            <div class="btn-block d-flex mt-20">
                                                <div class="d-flex flex-wrap pt-5p wallet-svg show-tooltip" data-bs-toggle="tooltip" data-color="primary-bottom" data-bs-placement="bottom" title="deposit">
                                                    <a href="<?=$this->siteUrl()?>/user/deposit" title="deposit">
                                                        <svg class="cursor-pointer deposit" width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M10.2534 6.9231H25.7478C26.345 6.92308 26.8601 6.92306 27.2845 6.95837C27.7324 6.99564 28.1778 7.07793 28.6065 7.30039C29.246 7.63225 29.7659 8.1618 30.0917 8.81313C30.3101 9.24972 30.3909 9.70344 30.4275 10.1596C30.4622 10.5918 30.4622 11.1165 30.4621 11.7248V16.1539C30.4621 16.7911 29.9549 17.3077 29.3293 17.3077C28.7036 17.3077 28.1964 16.7911 28.1964 16.1539V15H7.8048V20.5385C7.8048 21.2037 7.80568 21.6333 7.8319 21.9602C7.85705 22.2737 7.89973 22.3899 7.92827 22.4469C8.03689 22.664 8.21019 22.8406 8.42335 22.9512C8.47937 22.9802 8.59347 23.0237 8.90123 23.0493C9.22221 23.076 9.64395 23.0769 10.2971 23.0769H18.0006C18.6263 23.0769 19.1335 23.5935 19.1335 24.2308C19.1335 24.868 18.6263 25.3846 18.0006 25.3846H10.2535C9.65626 25.3847 9.14107 25.3847 8.71672 25.3494C8.26885 25.3121 7.82339 25.2298 7.39473 25.0073C6.75525 24.6755 6.23533 24.1459 5.90949 23.4946C5.69108 23.058 5.61029 22.6043 5.5737 22.1481C5.53903 21.7159 5.53904 21.1912 5.53906 20.583V11.7248C5.53904 11.1165 5.53903 10.5918 5.5737 10.1596C5.61029 9.70344 5.69108 9.24972 5.90949 8.81313C6.23533 8.1618 6.75525 7.63225 7.39473 7.30038C7.82339 7.07793 8.26885 6.99564 8.71673 6.95837C9.14107 6.92306 9.65625 6.92308 10.2534 6.9231ZM7.8048 12.6923H28.1964V11.7693C28.1964 11.104 28.1955 10.6745 28.1693 10.3475C28.1442 10.0341 28.1015 9.91785 28.0729 9.8608C27.9643 9.6437 27.791 9.46718 27.5778 9.35655C27.5218 9.32748 27.4077 9.28401 27.1 9.2584C26.779 9.23169 26.3573 9.23079 25.7041 9.23079H10.2971C9.64395 9.23079 9.22221 9.23169 8.90123 9.2584C8.59347 9.28401 8.47937 9.32748 8.42336 9.35655C8.21019 9.46717 8.03689 9.64369 7.92827 9.8608C7.89973 9.91785 7.85705 10.0341 7.8319 10.3475C7.80568 10.6745 7.8048 11.104 7.8048 11.7693V12.6923ZM25.9307 18.4616C26.5563 18.4616 27.0635 18.9782 27.0635 19.6154V23.7528L28.5282 22.2611C28.9706 21.8104 29.6879 21.8104 30.1303 22.2611C30.5727 22.7117 30.5727 23.4422 30.1303 23.8928L26.7317 27.3544C26.2893 27.805 25.572 27.805 25.1296 27.3544L21.731 23.8928C21.2886 23.4422 21.2886 22.7117 21.731 22.2611C22.1734 21.8104 22.8907 21.8104 23.3331 22.2611L24.7978 23.7529V19.6154C24.7978 18.9782 25.305 18.4616 25.9307 18.4616Z" fill="currentColor"/>
                                                        </svg>
                                                    </a>
                                                </div>

                                                <div class="hr-40"></div>

                                                <div class="d-flex flex-wrap pt-5p wallet-svg show-tooltip" data-bs-toggle="tooltip" data-color="primary-bottom" data-bs-placement="bottom" title="withdraw">
                                                    <a href="<?=$this->siteUrl()?>/user/payout" class="mt-1p">
                                                        <svg class="cursor-pointer withdraw" width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M10.2534 8.30774H25.7478C26.345 8.30772 26.8601 8.3077 27.2845 8.34301C27.7324 8.38028 28.1778 8.46257 28.6065 8.68503C29.246 9.0169 29.7659 9.54645 30.0917 10.1978C30.3101 10.6344 30.3909 11.0881 30.4275 11.5442C30.4622 11.9764 30.4622 12.5012 30.4621 13.1094V17.5385C30.4621 18.1758 29.9549 18.6924 29.3293 18.6924C28.7036 18.6924 28.1964 18.1758 28.1964 17.5385V16.3847H7.8048V21.9231C7.8048 22.5884 7.80568 23.0179 7.8319 23.3449C7.85705 23.6583 7.89973 23.7745 7.92827 23.8316C8.03689 24.0487 8.21019 24.2252 8.42335 24.3358C8.47937 24.3649 8.59347 24.4084 8.90123 24.434C9.22221 24.4607 9.64395 24.4616 10.2971 24.4616H18.0006C18.6263 24.4616 19.1335 24.9782 19.1335 25.6154C19.1335 26.2527 18.6263 26.7693 18.0006 26.7693H10.2535C9.65627 26.7693 9.14107 26.7693 8.71672 26.734C8.26885 26.6967 7.82339 26.6144 7.39473 26.392C6.75525 26.0601 6.23533 25.5306 5.90949 24.8792C5.69108 24.4427 5.61029 23.9889 5.5737 23.5328C5.53903 23.1006 5.53904 22.5758 5.53906 21.9676V13.1094C5.53904 12.5012 5.53903 11.9764 5.5737 11.5442C5.61029 11.0881 5.69108 10.6344 5.90949 10.1978C6.23533 9.54644 6.75525 9.0169 7.39473 8.68503C7.82339 8.46257 8.26885 8.38028 8.71673 8.34301C9.14107 8.3077 9.65625 8.30772 10.2534 8.30774ZM7.8048 14.077H28.1964V13.1539C28.1964 12.4886 28.1955 12.0591 28.1693 11.7322C28.1442 11.4187 28.1015 11.3025 28.0729 11.2454C27.9643 11.0283 27.791 10.8518 27.5778 10.7412C27.5218 10.7121 27.4077 10.6687 27.1 10.643C26.779 10.6163 26.3573 10.6154 25.7041 10.6154H10.2971C9.64395 10.6154 9.22221 10.6163 8.90123 10.643C8.59347 10.6687 8.47937 10.7121 8.42336 10.7412C8.21019 10.8518 8.03689 11.0283 7.92827 11.2454C7.89973 11.3025 7.85705 11.4187 7.8319 11.7322C7.80568 12.0591 7.8048 12.4886 7.8048 13.1539V14.077ZM25.1296 20.1842C25.572 19.7336 26.2893 19.7336 26.7317 20.1842L30.1303 23.6457C30.5727 24.0963 30.5727 24.8269 30.1303 25.2775C29.6879 25.7281 28.9706 25.7281 28.5282 25.2775L27.0635 23.7857V27.9231C27.0635 28.5604 26.5563 29.077 25.9307 29.077C25.305 29.077 24.7978 28.5604 24.7978 27.9231V23.7857L23.3331 25.2775C22.8907 25.7281 22.1734 25.7281 21.731 25.2775C21.2886 24.8269 21.2886 24.0963 21.731 23.6457L25.1296 20.1842Z" fill="currentColor"/>
                                                        </svg>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>
            <!-- main-containt -->
        </div>
    </div>
</div>