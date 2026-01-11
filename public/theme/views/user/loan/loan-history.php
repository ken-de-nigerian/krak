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

<style>
    @media (max-width: 767.98px) {
        .table thead {
            display: none;
        }

        .table tr {
            display: block;
            margin-bottom: 1rem;
            border-bottom: 2px solid #dee2e6;
        }

        .table td {
            display: block;
            text-align: right;
            font-size: 0.9rem;
            border-bottom: 1px solid #dee2e6;
        }

        .table td::before {
            content: attr(data-label);
            float: left;
            font-weight: bold;
            text-transform: uppercase;
        }
    }
</style>

<style>
    /* Ensure the table fits within the screen on mobile */
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch; /* Smooth scrolling on iOS */
    }

    /* Add padding and smaller font size for mobile */
    @media (max-width: 767.98px) {
        .table th,
        .table td {
            padding: 0.5rem;
            font-size: 0.9rem;
        }

        .table thead th {
            font-size: 0.9rem;
        }

        .badge {
            font-size: 0.8rem;
        }
    }

    /* Ensure the table doesn't overflow on small screens */
    .table {
        width: 100%;
        max-width: 100%;
        margin-bottom: 1rem;
        background-color: transparent;
        padding: 10px;
    }

    /* Add a border to the table for better readability */
    .table-bordered {
        border: 1px solid #dee2e6;
    }

    .table-bordered th,
    .table-bordered td {
        border: 1px solid #dee2e6;
    }

    /* Style the badges */
    .badge {
        padding: 0.5em 0.75em;
        border-radius: 0.25rem;
    }

    .bg-success {
        background-color: #28a745 !important;
        color: #000000 !important;
    }

    .bg-warning {
        background-color: #ffc107 !important;
        color: #000000 !important;
    }

    .bg-danger {
        background-color: #dc3545 !important;
        color: #000000 !important;
    }
</style>

<?php if (empty($data['get-loans'])): ?>
    <div class="position-relative">
        <div class="containt-parent">
            <div class="main-containt">
                <!-- main-containt -->
                <div class="text-center" id="invest_list">
                    <p class="mb-0 gilroy-Semibold f-26 text-dark theme-tran r-f-20 text-uppercase">Loans list</p>
                    <p class="mb-0 gilroy-medium text-gray-100 f-16 r-f-12 mt-2 tran-title p-inline-block">History of loans in your account.</p>
                </div>

                <div class="d-flex justify-content-between mt-24 mb-3 r-mt-22 align-items-center">
                    <a href="<?=$this->siteUrl()?>/user/loan" class="btn bg-primary text-light Add-new-btn w-176 addnew">
                        <span class="f-14 gilroy-medium"> + Request Loan</span>
                    </a>
                </div>
                
                <div class="notfound mt-16 bg-white p-4">
                    <div class="d-flex flex-wrap justify-content-center align-items-center gap-26">
                        <div class="image-notfound">
                            <img src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/dist/images/not-found.png" class="img-fluid"  alt=""/>
                        </div>
                        <div class="text-notfound">
                            <p class="mb-0 f-20 leading-25 gilroy-medium text-dark">Sorry! No data found.</p>
                            <p class="mb-0 f-16 leading-24 gilroy-regular text-gray-100 mt-12">As of now, there are no loan records available.</p>                        
                        </div>
                    </div>
                </div>
                <!-- main-containt -->
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="position-relative">
        <div class="containt-parent">
            <div class="main-containt">
                <!-- main-containt -->
                <div class="text-center" id="invest_list">
                    <p class="mb-0 gilroy-Semibold f-26 text-dark theme-tran r-f-20 text-uppercase">Loans list</p>
                    <p class="mb-0 gilroy-medium text-gray-100 f-16 r-f-12 mt-2 tran-title p-inline-block">History of deposits in your account.</p>
                </div>

                <div class="d-flex justify-content-between mt-24 mb-3 r-mt-22 align-items-center">
                    <a href="<?=$this->siteUrl()?>/user/loan" class="btn bg-primary text-light Add-new-btn w-176 addnew">
                        <span class="f-14 gilroy-medium"> + Request Loan</span>
                    </a>
                </div>

                <div class="transac-parent cursor-pointer">
                    <div class="table-responsive">
                        <table class="table table-stripped">
                            <thead>
                                <tr>
                                    <th>Amount</th>
                                    <th>Remarks</th>
                                    <th>Term</th>
                                    <th>Repayment</th>
                                    <th>Collateral</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['get-loans'] as $loan): ?>
                                    <tr>
                                        <td data-label="Amount"><?= formatCurrency($data['user']['currency'], $loan['amount']) ?></td>
                                        <td data-label="Remarks"><?= e($loan['loan_remarks']) ?></td>
                                        <td data-label="Term"><?= e($loan['loan_term']) ?></td>
                                        <td data-label="Repayment"><?= e($loan['repayment_plan']) ?></td>
                                        <td data-label="Collateral"><?= e($loan['collateral']) ?></td>
                                        <td data-label="Status">
                                            <?php if ($loan['loan_status'] == 1): ?>
                                                <span class="badge bg-success">Approved</span>
                                            <?php elseif ($loan['loan_status'] == 2): ?>
                                                <span class="badge bg-warning">Pending</span>
                                            <?php elseif ($loan['loan_status'] == 3): ?>
                                                <span class="badge bg-danger">Rejected</span>
                                            <?php endif ?>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>