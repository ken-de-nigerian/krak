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
            <div class="bg-white pxy-62 shadow" id="withdrawalCreate">
                <p class="mb-0 f-26 gilroy-Semibold text-uppercase text-center">Withdraw Money</p>
                <p class="mb-0 text-center f-13 gilroy-medium text-gray mt-4 dark-A0">Step: 1 of 3</p>
                <p class="mb-0 text-center f-18 gilroy-medium text-dark dark-5B mt-2">Create Withdrawal</p>
                <div class="text-center">
                    <svg class="mt-18 nscaleX-1" width="314" height="6" viewBox="0 0 314 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="100" height="6" rx="3" fill="#635BFE" />
                        <rect class="rect-B87" x="107" width="100" height="6" rx="3" fill="#DDD3FD" />
                        <rect class="rect-B87" x="214" width="100" height="6" rx="3" fill="#DDD3FD" />
                    </svg>
                </div>
                <p class="mb-0 text-center f-14 gilroy-medium text-gray dark-p mt-20">
                    You can withdraw your funds using our popular payment methods. Fill the details correctly &amp; the amount you want to withdraw.
                </p>
                <form id="withdraw-form">

                    <!-- Currency -->
                    <div class="mt-28 param-ref">
                        <label class="gilroy-medium text-gray-100 mb-2 f-15" for="interest_wallet">Balance</label>
                        <div class="avoid-blink">
                            <input class="form-control input-form-control apply-bg l-s2" id="interest_wallet" value="<?= e(number_format($data['user']['interest_wallet'], 2)) ?>" readonly>
                        </div>
                    </div>

                    <!-- Amount -->
                    <div class="mt-20 label-top">
                        <label class="gilroy-medium text-gray-100 mb-2 f-15" for="withdraw-amount">Amount</label>
                        <input type="text" class="form-control input-form-control apply-bg l-s2" name="amount" id="withdraw-amount" placeholder="0.00" autocomplete="off">
                        <span id="withdraw-error"></span>
                    </div>

                    <?php if (empty($data['get-withdraw'])): ?>
                        <!-- Withdrawal Methods Empty -->
                        <div class="row">
                            <div class="col-12">
                                <div class="mt-20 param-ref text-center">
                                    <label class="gilroy-medium text-warning mb-2 f-15">Withdrawal Methods are currently inactive.</label>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Withdrawal Methods -->
                        <div class="row">
                            <div class="col-12">
                                <div class="mt-20 param-ref">
                                    <label class="gilroy-medium text-gray-100 mb-2 f-15" for="withdraw_code">Withdrawal Methods</label>
                                    <div class="avoid-blink">
                                        <select class="select2" name="withdraw_code" id="withdraw_code" autocomplete="off">
                                            <?php foreach ($data['get-withdraw'] as $withdraw): ?>
                                                <option data-type="<?= e($withdraw['withdraw_code']) ?>" value="<?= e($withdraw['withdraw_code']) ?>"><?= e($withdraw['name']) ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="d-grid">
                        <?=$this->token()?>
                        <button type="submit" class="btn btn-lg btn-primary mt-4" id="withdrawalBtn">

                            <span class="px-1">Proceed</span>

                            <span id="withdrawalSvgIcon">
                                <svg class="position-relative ms-1 rtl-wrap-one nscaleX-1" width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M4.11648 12.216C3.81274 11.9123 3.81274 11.4198 4.11648 11.1161L8.23317 6.99937L4.11648 2.88268C3.81274 2.57894 3.81274 2.08647 4.11648 1.78273C4.42022 1.47899 4.91268 1.47899 5.21642 1.78273L9.88309 6.4494C10.1868 6.75314 10.1868 7.2456 9.88309 7.54934L5.21642 12.216C4.91268 12.5198 4.42022 12.5198 4.11648 12.216Z" fill="currentColor"/>
                                </svg>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
            <!-- main-containt -->
        </div>
    </div>
</div>