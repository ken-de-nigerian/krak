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
            <div class="bg-white pxy-62 shadow" id="ticketCreate">
                <p class="mb-0 f-26 gilroy-Semibold text-uppercase text-center text-dark">REQUEST FOR LOAN</p>
                <form id="loan-form">
                    <div class="row">
                        <div class="col-12">
                            <div class="label-top new-ticket">
                                <label class="gilroy-medium text-gray-100 mb-2 f-15 mt-4 r-mt-amount">Loan Amount</label>
                                <input type="text" class="form-control input-form-control apply-bg" name="amount" id="amount" placeholder="Enter Amount" autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="label-top new-ticket">
                                <label class="gilroy-medium text-gray-100 mb-2 f-15 mt-4 r-mt-amount">Reason For Loan</label>
                                <textarea type="text" rows="3" class="form-control input-form-control apply-bg" name="loan_remarks" id="loan_remarks" placeholder="Enter Reason For Loan" autocomplete="off"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="mt-20 param-ref">
                                <label class="gilroy-medium text-gray-100 mb-2 f-15" for="loan_term">Loan Term</label>
                                <div class="avoid-blink">
                                    <select class="select2" name="loan_term" id="loan_term" autocomplete="off">
                                        <option value="Short">Short-Term Loans</option>
                                        <option value="Medium">Medium-Term Loans</option>
                                        <option value="Long">Long-Term Loans</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="mt-20 param-ref">
                                <label class="gilroy-medium text-gray-100 mb-2 f-15" for="repayment_plan">Repayment Plan</label>
                                <div class="avoid-blink">
                                    <select class="select2" name="repayment_plan" id="repayment_plan" autocomplete="off">
                                        <option value="Monthly Installment">Monthly Installment</option>
                                        <option value="Frequency">Frequency</option>
                                        <option value="Fixed">Fixed</option>
                                        <option value="Amortization Schedule">Amortization Schedule</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="label-top new-ticket">
                                <label class="gilroy-medium text-gray-100 mb-2 f-15 mt-4 r-mt-amount">Collateral Information</label>
                                <textarea rows="4" class="form-control input-form-control apply-bg" name="collateral" id="collateral" placeholder="Enter Collateral Information" autocomplete="off"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid">
                        <?=$this->token()?>
                        <button type="submit" class="btn btn-lg btn-primary mt-4" id="loanBtn">
                            <span>Send Request</span>
                        </button>
                    </div>

                    <div class="d-flex justify-content-center align-items-center mt-4 back-direction">
                        <a href="<?=$this->siteUrl()?>/user/dashboard" class="text-gray gilroy-medium d-inline-flex align-items-center position-relative back-btn">
                            <svg class="position-relative nscaleX-1" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M8.47075 10.4709C8.7311 10.2105 8.7311 9.78842 8.47075 9.52807L4.94216 5.99947L8.47075 2.47087C8.7311 2.21053 8.7311 1.78842 8.47075 1.52807C8.2104 1.26772 7.78829 1.26772 7.52794 1.52807L3.52795 5.52807C3.2676 5.78842 3.2676 6.21053 3.52795 6.47088L7.52794 10.4709C7.78829 10.7312 8.2104 10.7312 8.47075 10.4709Z" fill="currentColor"/>
                            </svg>
                            <span class="ms-1 back-btn">Back</span>
                        </a>
                    </div>
                </form>

                <ol style="margin-top: 20px;">
                    <li>
                        <span>
                            By submitting your loan application, you acknowledge and agree to the following terms and conditions:
                        </span>
                    </li>

                    <li>
                        <span>
                            Loan Approval: Loan approval is subject to credit assessment and verification of the information provided in your application. We reserve the right to approve or decline your loan application at our discretion.
                        </span>
                    </li>

                    <li>
                        <span>
                            Repayment Obligation: Upon approval, you agree to repay the loan amount, along with accrued interest and any applicable fees, according to the agreed-upon repayment plan.
                        </span>
                    </li>

                    <li>
                        <span>
                            Interest Rates and Fees: The loan terms include interest rates and fees applicable to your loan. Please review these carefully before proceeding with your application.
                        </span>
                    </li>

                    <li>
                        <span>
                            Default and Consequences: Failure to repay the loan according to the agreed-upon terms may result in default. In the event of default, we may take legal action, report non-payment to credit bureaus, and impose additional charges.
                        </span>
                    </li>

                    <li>
                        <span>
                            Loan Security: Depending on the loan type and amount, we may require collateral as security for the loan. Failure to provide adequate security may impact the approval decision.
                        </span>
                    </li>

                    <li>
                        <span>
                            Change in Circumstances: You agree to inform us promptly of any changes in your financial circumstances that may affect your ability to repay the loan.
                        </span>
                    </li>

                    <li>
                        <span>
                            Privacy and Data Protection: We are committed to protecting your privacy and personal information. By applying for a loan, you consent to the collection, use, and disclosure of your information for the purpose of processing your application and managing your loan.
                        </span>
                    </li>

                    <li>
                        <span>
                            Amendments: We reserve the right to amend these terms and conditions at any time. Any changes will be communicated to you in writing or through our website.
                        </span>
                    </li>
                </ol>
            </div>
            <!-- main-containt -->
        </div>
    </div>
</div>