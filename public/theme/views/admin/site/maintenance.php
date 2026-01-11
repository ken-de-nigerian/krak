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
                                    <h4 class="display-6 coming-soon-text">Maintenance Mode</h4>
                                    <p class="text-success fs-15 mt-3">Set your site maintenance mode status here.</p>
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

                <form id="maintenance-form">
                    <input type="hidden" id="id" name="id" value="<?=e($data['maintenance']['id'])?>">
                    <div class="row justify-content-evenly mb-2">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Maintenance Mode</h4>
                            </div>
                            
                            <!-- end card header -->
                            <div class="card-body">
                                <div class="live-preview">
                                    <div class="row gy-4">
                                        <div class="col-xxl-12 col-md-6">
                                            <div class="form-floating">
                                                <select id="maintenance_mode" name="maintenance_mode" class="form-control" autocomplete="off">
                                                    <option value="1" <?=(e($data['maintenance']['maintenance_mode']) == '1' ? ' selected' : '')?>>Active</option>
                                                    <option value="2" <?=(e($data['maintenance']['maintenance_mode']) == '2' ? ' selected' : '')?>>Inactive</option>
                                                </select>
                                                <label for="maintenance_mode">Maintenance Mode</label>
                                            </div>
                                        </div>
                                        <!--end col-->

                                        <div class="col-xxl-12 col-md-12">
                                            <div class="form-floating">
                                                <textarea type="text" class="form-control" id="details" name="details" rows="3" style="height: 200px;" autocomplete="off"><?=e($data['maintenance']['details'])?></textarea>
                                                <label for="details">Maintenance Message</label>
                                            </div>
                                        </div>
                                        <!--end col-->
                                        
                                        <div class="col-xxl-12 col-md-6">
                                            <?=$this->token()?>
                                            <button id="maintenanceBtn" class="btn btn-primary waves-effect waves-light w-100">Submit Form</button>
                                        </div>
                                        <!--end col-->
                                    </div>
                                    <!--end row-->
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!--end col-->
        </div>
        <!--end row-->
    </div>
    <!-- container-fluid -->
</div>