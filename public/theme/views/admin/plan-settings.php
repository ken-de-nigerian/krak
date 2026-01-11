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
                                    <h4 class="display-6 coming-soon-text">Plans & Pricing</h4>
                                    <p class="text-success fs-15 mt-3">You have full control to edit and manage all plans on your site.</p>
                                    <div class="hstack flex-wrap gap-2">
                                        <a href="<?=$this->siteUrl()?>/admin/dashboard" class="btn btn-primary btn-label rounded-pill">
                                            <i class="ri-arrow-go-back-fill label-icon align-middle rounded-pill fs-16 me-2"></i>Go Back
                                        </a>
                                        <div class="hstack flex-wrap gap-2">
                                            <a href="#addPlanModal" data-bs-toggle="modal" class="btn btn-info btn-label rounded-pill"><i class="ri-exchange-dollar-line label-icon align-middle rounded-pill fs-16 me-2"></i> Add New Plan</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end card body -->
                </div>
                <!-- end card -->

                <?php if (empty($data['plans'])): ?>
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
                                                <h6 class="fs-18 fw-semibold lh-base">No plans configurations found.</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="row">
                        <?php foreach ($data['plans'] as $plan): ?>
                            <div class="col-xxl-3 col-lg-6">
                                <div class="card pricing-box">
                                    <div class="card-body bg-light m-2 p-4">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="flex-grow-1">
                                                <h5 class="mb-0 fw-semibold"><?=e($plan['name'])?></h5>
                                            </div>

                                            <div class="ms-auto">
                                                <h2 class="month mb-0">
                                                    <?=e($plan['interest'])?>%
                                                </h2>
                                            </div>
                                        </div>

                                        <ul class="list-unstyled vstack gap-3">
                                            <li>
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0 text-success me-1">
                                                        <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <?php if ($plan['fixed_amount'] == 0): ?>
                                                            $<?=e($plan['minimum'])?> - <?= ($plan['maximum'] === "Unlimited") ? $plan['maximum'] : '$' . $plan['maximum'] ?>
                                                        <?php else: ?>
                                                            $<?=e($plan['fixed_amount'])?>
                                                        <?php endif ?>
                                                    </div>
                                                </div>
                                            </li>

                                            <li>
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0 text-success me-1">
                                                        <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <?php foreach($data['times'] as $time){
                                                            if($time['time'] === $plan['times']){ ?>
                                                            <?=e($time["name"])?>
                                                        <?php
                                                            break;
                                                            }
                                                        } ?>
                                                    </div>
                                                </div>
                                            </li>

                                            <li>
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0 text-success me-1">
                                                        <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <?php if ($plan['capital_back_status'] == 1): ?>
                                                            Capital Returned - Yes
                                                        <?php else: ?>
                                                            Capital Returned - No
                                                        <?php endif ?>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                        <div class="mt-3 pt-2">
                                            <a href="<?=$this->siteUrl()?>/admin/plans/edit-plan/<?=e($plan["planId"])?>" class="btn btn-danger w-100">Edit Plan</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <!--end row-->
    </div>
    <!-- container-fluid -->
</div>
<!-- End Page-content -->

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Get references to the select element and the divs with class "range" and "fixed"
        const investTypeSelect = document.getElementById("invest_type");
        const rangeDiv = document.querySelector(".range");
        const range2Div = document.querySelector(".range2");
        const fixedDiv = document.querySelector(".fixed");

        // Add event listener to the select element for changes
        investTypeSelect.addEventListener("change", function() {
            // Check the selected value
            if (investTypeSelect.value === "1") {
                // If "Range" is selected, show the range div and hide the fixed div
                rangeDiv.style.display = "block";
                range2Div.style.display = "block";
                fixedDiv.style.display = "none";
            } else if (investTypeSelect.value === "2") {
                // If "Fixed" is selected, show the fixed div and hide the range div
                rangeDiv.style.display = "none";
                range2Div.style.display = "none";
                fixedDiv.style.display = "block";
            }
        });

        // Initialize display based on default value of select element
        if (investTypeSelect.value === "1") {
            rangeDiv.style.display = "block";
            range2Div.style.display = "block";
            fixedDiv.style.display = "none";
        } else if (investTypeSelect.value === "2") {
            rangeDiv.style.display = "none";
            range2Div.style.display = "none";
            fixedDiv.style.display = "block";
        }
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Get references to the select element and the divs with class "range" and "fixed"
        const returnTypeSelect = document.getElementById("return_type");
        const repeatDiv = document.querySelector(".repeat");
        const capitalDiv = document.querySelector(".capital");

        // Add event listener to the select element for changes
        returnTypeSelect.addEventListener("change", function() {
            // Check the selected value
            if (returnTypeSelect.value === "1") {
                // If "Range" is selected, show the range div and hide the fixed div
                repeatDiv.style.display = "block";
                capitalDiv.style.display = "block";
            } else if (returnTypeSelect.value === "0") {
                // If "Fixed" is selected, show the fixed div and hide the range div
                repeatDiv.style.display = "none";
                capitalDiv.style.display = "none";
            }
        });

        // Initialize display based on default value of select element
        if (returnTypeSelect.value === "1") {
            repeatDiv.style.display = "block";
            capitalDiv.style.display = "block";
        } else if (returnTypeSelect.value === "0") {
            repeatDiv.style.display = "none";
            capitalDiv.style.display = "none";
        }
    });
</script>

<!-- addPlanModal -->
<div id="addPlanModal" class="modal fade bs-example-modal-lg" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"  role="dialog" aria-labelledby="addTimeBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="NotificationModalbtn-close"></button>
            </div>
            <div class="modal-body">
                <h4>Add New Time</h4>
                <p class="tx-color-03">You have full control to add and edit time configurations.</p>
                <!-- Tab panes -->
                <form id="add-plan-form">
                    <div class="row gy-4">
                        <div class="col-xxl-6 col-md-6">
                            <div class="form-floating mb-4">
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter plan name" autocomplete="off">
                                <label for="name">Name</label>
                            </div>
                        </div>

                        <div class="col-xxl-6 col-md-6">
                            <div class="form-floating mb-4">
                                <select class="form-control" id="invest_type" name="invest_type">
                                    <option value="1">Range</option>
                                    <option value="2">Fixed</option>
                                </select>
                                <label for="invest_type">Amount type</label>
                            </div>
                        </div>

                        <div class="col-xxl-6 col-md-6 range">
                            <div class="form-floating mb-4">
                                <input type="text" class="form-control" id="minimum" name="minimum" placeholder="Enter minimum amount" autocomplete="off">
                                <label for="minimum">Minimum Amount</label>
                            </div>
                        </div>

                        <div class="col-xxl-6 col-md-6 range2">
                            <div class="form-floating mb-4">
                                <input type="text" class="form-control" id="maximum" name="maximum" placeholder="Enter maximum amount" autocomplete="off">
                                <label for="maximum">Maximum Amount</label>
                            </div>
                        </div>

                        <div class="col-xxl-6 col-md-6 fixed">
                            <div class="form-floating mb-4">
                                <input type="text" class="form-control" id="fixed_amount" name="fixed_amount" placeholder="Enter fixed amount" autocomplete="off">
                                <label for="fixed_amount">Fixed Amount</label>
                            </div>
                        </div>

                        <div class="col-xxl-6 col-md-6">
                            <div class="form-floating mb-4">
                                <select class="form-control" id="interest_status" name="interest_status">
                                    <option value="1">Percent</option>
                                    <option value="2">Fixed</option>
                                </select>
                                <label for="interest_status">Interest Type</label>
                            </div>
                        </div>

                        <div class="col-xxl-6 col-md-6">
                            <div class="form-floating mb-4">
                                <input type="text" class="form-control" id="interest" name="interest" placeholder="Enter plan interest" autocomplete="off">
                                <label for="interest">Interest</label>
                            </div>
                        </div>

                        <div class="col-xxl-6 col-md-6">
                            <div class="form-floating mb-4">
                                <select name="time" class="form-control" id="time">
                                    <?php foreach ($data['times'] as $time): ?>
                                        <option value="<?=e($time["time"])?>"><?=e($time["name"])?></option>
                                    <?php endforeach ?>
                                </select>
                                <label for="time">Time</label>
                            </div>
                        </div>

                        <div class="col-xxl-6 col-md-6">
                            <div class="form-floating mb-4">
                                <select name="return_type" class="form-control" id="return_type">
                                    <option value="1">Repeat</option>
                                    <option value="0">Lifetime</option>
                                </select>
                                <label for="return_type">Return Type</label>
                            </div>
                        </div>

                        <div class="col-xxl-6 col-md-6 repeat">
                            <div class="form-floating mb-4">
                                <input type="text" class="form-control" id="repeat_time" name="repeat_time" placeholder="Enter repeat time" autocomplete="off">
                                <label for="repeat_time">Repeat Times</label>
                            </div>
                        </div>

                        <div class="col-xxl-6 col-md-6 capital">
                            <div class="form-floating mb-4">
                                <select name="capital_back" id="capital_back" class="form-control">
                                    <option value="1">Yes</option>
                                    <option value="2">No</option>
                                </select>
                                <label for="capital_back">Capital Back</label>
                            </div>
                        </div>

                        <div class="col-xxl-6 col-md-6">
                            <div class="form-floating mb-4">
                                <select name="featured" id="featured" class="form-control">
                                    <option value="1">Yes</option>
                                    <option value="2">No</option>
                                </select>
                                <label for="featured">Featured</label>
                            </div>
                        </div>

                        <div class="col-xxl-6 col-md-6">
                            <div class="form-floating mb-4">
                                <select name="status" id="plan_status" class="form-control">
                                    <option value="1">Active</option>
                                    <option value="2">Disabled</option>
                                </select>
                                <label for="plan_status">Status</label>
                            </div>
                        </div>
                    </div>

                    <div class="hstack gap-2 justify-content-end">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <?=$this->token()?>
                        <button id="addPlanBtn" class="btn btn-primary waves-effect waves-light w-100">Submit Form</button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>