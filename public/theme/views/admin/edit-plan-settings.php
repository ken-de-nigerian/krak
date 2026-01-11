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
                                    <h4 class="display-6 coming-soon-text">Edit Plan - <?=e($data['plan-details']["name"])?></h4>
                                    <p class="text-success fs-15 mt-3">You have complete authority to oversee and modify your plan details here.</p>
                                    <div class="hstack flex-wrap gap-2">
                                        <a href="<?=$this->siteUrl()?>/admin/plans" class="btn btn-primary btn-label rounded-pill">
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

                <form id="edit-plan-form" class="row gx-5">
                    <div class="col-lg-12">
                        <div class="row justify-content-evenly mb-2">
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Plan Data</h4>
                                </div>
                                
                                <!-- end card header -->
                                <div class="card-body">
                                    <div class="live-preview">
                                        <div class="row gy-4">
                                            <div class="col-xxl-4 col-md-4">
                                                <div class="form-floating mb-4">
                                                    <input type="text" class="form-control" id="name" name="name" value="<?=e($data['plan-details']["name"])?>" autocomplete="off">
                                                    <label for="name">Name</label>
                                                </div>
                                            </div>

                                            <div class="col-xxl-4 col-md-4">
                                                <div class="form-floating mb-4">
                                                    <select class="form-control" id="invest_type" name="invest_type">
                                                        <?php if ($data['plan-details']['fixed_amount'] == 0): ?>
                                                            <option value="1" selected>Range</option>
                                                            <option value="2">Fixed</option>
                                                        <?php else: ?>
                                                            <option value="1">Range</option>
                                                            <option value="2" selected>Fixed</option>
                                                        <?php endif ?>
                                                    </select>
                                                    <label for="invest_type">Amount type</label>
                                                </div>
                                            </div>

                                            <div class="col-xxl-4 col-md-4 range">
                                                <div class="form-floating mb-4">
                                                    <input type="text" class="form-control" id="minimum" name="minimum" value="<?=e($data['plan-details']["minimum"])?>" autocomplete="off">
                                                    <label for="minimum">Minimum Amount</label>
                                                </div>
                                            </div>

                                            <div class="col-xxl-4 col-md-4 range2">
                                                <div class="form-floating mb-4">
                                                    <input type="text" class="form-control" id="maximum" name="maximum" value="<?=e($data['plan-details']["maximum"])?>" autocomplete="off">
                                                    <label for="maximum">Maximum Amount</label>
                                                </div>
                                            </div>

                                            <div class="col-xxl-4 col-md-4 fixed">
                                                <div class="form-floating mb-4">
                                                    <input type="text" class="form-control" id="fixed_amount" name="fixed_amount" value="<?=e($data['plan-details']["fixed_amount"])?>" autocomplete="off">
                                                    <label for="fixed_amount">Fixed Amount</label>
                                                </div>
                                            </div>

                                            <div class="col-xxl-4 col-md-4">
                                                <div class="form-floating mb-4">
                                                    <select class="form-control" id="interest_status" name="interest_status">
                                                        <option value="1" <?=(e($data['plan-details']['interest_status']) == '1' ? ' selected' : '')?>>Percent</option>
                                                        <option value="2" <?=(e($data['plan-details']['interest_status']) == '2' ? ' selected' : '')?>>Fixed</option>
                                                    </select>
                                                    <label for="interest_status">Interest Type</label>
                                                </div>
                                            </div>

                                            <div class="col-xxl-4 col-md-4">
                                                <div class="form-floating mb-4">
                                                    <input type="text" class="form-control" id="interest" name="interest" value="<?=e($data['plan-details']["interest"])?>" autocomplete="off">
                                                    <label for="interest">Interest</label>
                                                </div>
                                            </div>

                                            <div class="col-xxl-4 col-md-4">
                                                <div class="form-floating mb-4">
                                                    <select name="time" class="form-control" id="time">
                                                        <?php foreach ($data['times'] as $time): ?>
                                                            <option value="<?=e($time["time"])?>" <?=(e($data['plan-details']['times']) == $time['time'] ? ' selected' : '')?>><?=e($time["name"])?></option>
                                                        <?php endforeach ?>
                                                    </select>
                                                    <label for="time">Time</label>
                                                </div>
                                            </div>

                                            <div class="col-xxl-4 col-md-4">
                                                <div class="form-floating mb-4">
                                                    <select name="return_type" class="form-control" id="return_type">
                                                        <option value="1" <?=(e($data['plan-details']['lifetime_status']) == '0' ? ' selected' : '')?>>Repeat</option>
                                                        <option value="0" <?=(e($data['plan-details']['lifetime_status']) == '1' ? ' selected' : '')?>>Lifetime</option>
                                                    </select>
                                                    <label for="return_type">Return Type</label>
                                                </div>
                                            </div>

                                            <div class="col-xxl-4 col-md-4 repeat">
                                                <div class="form-floating mb-4">
                                                    <input type="text" class="form-control" id="repeat_time" name="repeat_time" value="<?=e($data['plan-details']["repeat_time"])?>" autocomplete="off">
                                                    <label for="repeat_time">Repeat Times</label>
                                                </div>
                                            </div>

                                            <div class="col-xxl-4 col-md-4 capital">
                                                <div class="form-floating mb-4">
                                                    <select name="capital_back" id="capital_back" class="form-control">
                                                        <option value="1" <?=(e($data['plan-details']['capital_back_status']) == '1' ? ' selected' : '')?>>Yes</option>
                                                        <option value="2" <?=(e($data['plan-details']['capital_back_status']) == '2' ? ' selected' : '')?>>No</option>
                                                    </select>
                                                    <label for="capital_back">Capital Back</label>
                                                </div>
                                            </div>

                                            <div class="col-xxl-4 col-md-4">
                                                <div class="form-floating mb-4">
                                                    <select name="featured" id="featured" class="form-control">
                                                        <option value="1" <?=(e($data['plan-details']['featured']) == '1' ? ' selected' : '')?>>Yes</option>
                                                        <option value="2" <?=(e($data['plan-details']['featured']) == '2' ? ' selected' : '')?>>No</option>
                                                    </select>
                                                    <label for="featured">Featured</label>
                                                </div>
                                            </div>

                                            <div class="col-xxl-4 col-md-4">
                                                <div class="form-floating mb-4">
                                                    <select name="status" id="plan_status" class="form-control">
                                                        <option value="1" <?=(e($data['plan-details']['status']) == '1' ? ' selected' : '')?>>Active</option>
                                                        <option value="2" <?=(e($data['plan-details']['status']) == '2' ? ' selected' : '')?>>Disabled</option>
                                                    </select>
                                                    <label for="plan_status">Status</label>
                                                </div>
                                            </div>

                                            <div class="col-xxl-12 col-md-12">
                                                <?=$this->token()?>
                                                <button id="editPlanBtn" class="btn btn-primary waves-effect waves-light w-100" data-id="<?=e($data['plan-details']['planId'])?>">Submit Form</button>
                                            </div>
                                            <!--end col-->
                                        </div>
                                        <!--end row-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
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