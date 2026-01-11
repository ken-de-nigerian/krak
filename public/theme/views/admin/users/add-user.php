<?php
defined('FIR') OR exit();
/**
 * The template for displaying Home page content
 */
?>
<style>
	.iti__country-list {
	    background-color: #262a2f !important;
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
		                            <h4 class="display-6 coming-soon-text">Register User</h4>
		                            <p class="text-success fs-15 mt-3">Effortlessly manage users with powerful administrative tools, stay in control of your platform's user base.</p>
		                            <div class="hstack flex-wrap gap-2">
                                        <a href="<?=$this->siteUrl()?>/admin/users" class="btn btn-primary btn-label rounded-pill">
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

		        <form id="add-user-form">
                    <input type="hidden" id="formattedPhone" name="formattedPhone">
                    <div class="row justify-content-evenly mb-2">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">General Information</h4>
                            </div>
                            
                            <!-- end card header -->
                            <div class="card-body">
                                <div class="live-preview">
                                    <div class="row gy-4">
                                        <div class="col-xxl-3 col-md-6">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Enter firstname" autocomplete="off">
                                                <label for="firstname">Firstname</label>
                                            </div>
                                        </div>
                                        <!--end col-->

                                        <div class="col-xxl-3 col-md-6">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Enter lastname" autocomplete="off">
                                                <label for="lastname">Lastname</label>
                                            </div>
                                        </div>
                                        <!--end col-->

                                        <div class="col-xxl-3 col-md-6">
                                            <div class="form-floating">
                                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" autocomplete="off">
                                                <label for="email">Email</label>
                                            </div>
                                        </div>
                                        <!--end col-->

                                        <div class="col-xxl-3 col-md-6">
                                            <div class="form-floating">
                                                <div class="form-floating">
	                                                <input type="text" class="form-control" id="phone" autocomplete="off" style="padding: 1.2rem 3.5rem;">
	                                                <label for="phone"></label>
	                                            </div>
                                            </div>
                                        </div>
                                        <!--end col-->

                                        <div class="col-xxl-3 col-md-6">
                                            <div class="form-floating">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" id="country" name="country" autocomplete="off" placeholder="Enter country" readonly>
                                                    <label for="country">Country</label>
                                                </div>
                                            </div>
                                        </div>
                                        <!--end col-->
                                    </div>
                                    <!--end row-->
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row justify-content-evenly mb-2">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Security</h4>
                            </div>
                            
                            <!-- end card header -->
                            <div class="card-body">
                                <div class="live-preview">
                                    <div class="row gy-4">
                                        <div class="col-xxl-6 col-md-6">
                                            <div class="form-floating">
                                                <div class="form-floating">
	                                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" autocomplete="off">
	                                                <label for="password">Password</label>
	                                            </div>
                                            </div>
                                        </div>

                                        <div class="col-xxl-6 col-md-6 mb-2">
                                            <div class="form-floating">
                                                <div class="form-floating">
	                                                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Enter password confirm" autocomplete="off">
	                                                <label for="confirmPassword">Confirm Password</label>
	                                            </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <?=$this->token()?>
                                            <button id="addUserBtn" class="btn btn-primary waves-effect waves-light w-100">Submit Form</button>
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
		</div>
    </div>
    <!-- container-fluid -->
</div>
<!-- End Page-content -->