<?php
defined('FIR') OR exit();
/**
 * The template for displaying the footer section
 */
?>
<style>
    /* Stacked layout for mobile */
    @media (max-width: 767.98px) {
        .table thead {
            display: none; /* Hide the table header on mobile */
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
            content: attr(data-label); /* Use the data-label attribute as the label */
            float: left;
            font-weight: bold;
            text-transform: uppercase;
        }

        .table td .d-flex {
            justify-content: flex-end; /* Align buttons to the right */
        }

        .badge {
            font-size: 0.8rem;
            padding: 0.5em 0.75em;
            border-radius: 0.25rem;
        }

        .btn {
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem;
        }
    }
</style>
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card rounded-0 bg-success-subtle mx-n4 mt-n4 border-top">
                    <div class="px-4">
                        <div class="row">
                            <div class="col-xxl-5 align-self-center">
                                <div class="py-4">
                                    <h4 class="display-6 coming-soon-text">Loan</h4>
                                    <p class="text-success fs-15 mt-3">You have full control to monitor all loan requests on your site.</p>
                                    <div class="hstack flex-wrap gap-2">
                                        <a href="<?=$this->siteUrl()?>/admin/dashboard" class="btn btn-primary btn-label rounded-pill">
                                            <i class="ri-arrow-go-back-fill label-icon align-middle rounded-pill fs-16 me-2"></i>Go Back
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end card body -->
                </div>
                <!-- end card -->

                <?php if (empty($data['get-loans'])): ?>
                    <div class="row">
                        <div class="card">
                            <div class="card-body">
                                <div class="text-center">
                                    <div class="tab-pane fade show active py-2 ps-2" id="all-noti-tab" role="tabpanel">
                                        <div class="empty-notification-elem">
                                            <div class="w-25 w-sm-50 pt-3 mx-auto">
                                                <img src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/admin/images/svg/bell.svg" class="img-fluid" alt="user-pic" />
                                            </div>

                                            <div class="text-center pb-5 mt-2">
                                                <h6 class="fs-18 fw-semibold lh-base">No loans found.</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="row mb-3">
                        <div class="col-xl-12">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Amount</th>
                                            <th>Loan Remarks</th>
                                            <th>Loan Term</th>
                                            <th>Repayment Plan</th>
                                            <th>Collateral</th>
                                            <th>Loan Status</th>
                                            <th>Actions</th> <!-- New column for buttons -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($data['get-loans'] as $loan): ?>
                                            <tr>
                                                <td data-label="Amount"><?php echo htmlspecialchars($loan['amount']); ?></td>
                                                <td data-label="Loan Remarks"><?php echo htmlspecialchars($loan['loan_remarks']); ?></td>
                                                <td data-label="Loan Term"><?php echo htmlspecialchars($loan['loan_term']); ?></td>
                                                <td data-label="Repayment Plan"><?php echo htmlspecialchars($loan['repayment_plan']); ?></td>
                                                <td data-label="Collateral"><?php echo htmlspecialchars($loan['collateral']); ?></td>
                                                <td data-label="Loan Status">
                                                    <span class="badge bg-<?php echo $loan['loan_status'] === 1 ? 'success' : ($loan['loan_status'] === 2 ? 'warning' : 'danger'); ?>">
                                                        <?php echo $loan['loan_status'] === 1 ? 'Approved' : ($loan['loan_status'] === 2 ? 'Pending' : 'Rejected'); ?>
                                                    </span>
                                                </td>
                                                <td data-label="Actions">
                                                    <?php if ($loan['loan_status'] == 2): ?>
                                                        <div class="d-flex gap-2">
                                                            <button data-bs-toggle="modal" data-bs-target="#approveModal" data-loan-id="<?= e($loan['loan_reference_id']) ?>" class="btn btn-success btn-sm">Approve</button>
                                                            <button data-bs-toggle="modal" data-bs-target="#rejectModal" data-loan-id="<?= e($loan['loan_reference_id']) ?>" class="btn btn-danger btn-sm">Reject</button>
                                                        </div>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- End of table-responsive wrapper -->
                        </div>
                        <!-- end col -->
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <!--end row-->
    </div>
    <!-- container-fluid -->
</div>
<!-- End Page-content -->

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approveModalLabel">Approve Loan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to approve this loan?
            </div>
            <div class="modal-footer">
                <form id="approve-loan-form">
                    <input type="hidden" id="loanId" name="loanId">
                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <?= $this->token() ?>
                        <button id="approveLoanBtn" class="btn w-sm btn-success">Yes, Approve</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel">Reject Loan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to reject this loan?
            </div>
            <div class="modal-footer">
                <form id="reject-loan-form">
                    <input type="hidden" id="rejectLoanId" name="loanId">
                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <?= $this->token() ?>
                        <button id="rejectLoanBtn" class="btn w-sm btn-danger">Yes, Reject</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
