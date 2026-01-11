<?php
defined('FIR') OR exit();
/**
 * The template for displaying Home page content
 */
?>
<?php 
    function getRelativeTime($date) {
        $now = new DateTime();
        $Date = new DateTime($date);
        $interval = $Date->diff($now);

        if ($interval->y >= 1) {
            $years = $interval->y;
            $suffix = ($years === 1) ? 'year' : 'years';
            return $years . ' ' . $suffix . ' ago';
        } elseif ($interval->m >= 1) {
            $months = $interval->m;
            $suffix = ($months === 1) ? 'month' : 'months';
            return $months . ' ' . $suffix . ' ago';
        } elseif ($interval->d >= 1) {
            $days = $interval->d;
            $suffix = ($days === 1) ? 'day' : 'days';
            return $days . ' ' . $suffix . ' ago';
        } elseif ($interval->h >= 1) {
            $hours = $interval->h;
            $suffix = ($hours === 1) ? 'hour' : 'hours';
            return $hours . ' ' . $suffix . ' ago';
        } elseif ($interval->i >= 1) {
            $minutes = $interval->i;
            $suffix = ($minutes === 1) ? 'minute' : 'minutes';
            return $minutes . ' ' . $suffix . ' ago';
        } else {
            $seconds = $interval->s;
            $suffix = ($seconds === 1) ? 'second' : 'seconds';
            return $seconds . ' ' . $suffix . ' ago';
        }
    }
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
                                    <h4 class="display-6 coming-soon-text">Cron Job</h4>
                                    <p class="text-success fs-15 mt-3">Cronjob Automation: Efficiently Manage Scheduled Tasks.</p>
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

                <div class="row">
                    <div class="col-xxl-3 col-sm-6 project-card">
                        <div class="card">
                            <div class="card-body">
                                <?php
                                    // Get the current timestamp
                                    $Invest_current_time = time();

                                    // Get the timestamp of the last cron job
                                    $Invest_last_cron_time = strtotime($data['settings']['last_cron']);

                                    // Calculate the difference in seconds
                                    $Invest_time_difference = $Invest_current_time - $Invest_last_cron_time;

                                    // Define the threshold (15 minutes = 900 seconds)
                                    $Invest_threshold = 900;

                                    // Determine the background color class based on the time difference
                                    $Invest_background_class = ($Invest_time_difference > $Invest_threshold) ? "bg-danger-subtle" : "bg-success-subtle";
                                ?>
                                <div class="p-3 mt-n3 mx-n3 <?php echo $Invest_background_class; ?> rounded-top">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <h5 class="mb-0 fs-14">
                                                <a href="#" class="text-body">Investment Cron</a>
                                            </h5>
                                        </div>
                                    </div>
                                </div>

                                <div class="py-3">
                                    <div class="row gy-3">
                                        <div class="col-6">
                                            <div>
                                                <p class="text-muted mb-1">Status</p>
                                                <?php
                                                    // Get the current timestamp
                                                    $Invest_current_time = time();

                                                    // Get the timestamp of the last cron job
                                                    $Invest_last_cron_time = strtotime($data['settings']['last_cron']);

                                                    // Calculate the difference in seconds
                                                    $Invest_time_difference = $Invest_current_time - $Invest_last_cron_time;

                                                    // Define the threshold (15 minutes = 900 seconds)
                                                    $Invest_threshold = 900;

                                                    // Calculate the progress percentage rounded up
                                                    $Invest_progress_percentage = ceil(min(100, ($Invest_time_difference / $Invest_threshold) * 100));

                                                    // Determine the status badge and text
                                                    if ($Invest_time_difference > $Invest_threshold) {
                                                        $Invest_status_badge = "<div class=\"badge bg-danger-subtle text-danger fs-12\">Not Running</div>";
                                                    } else {
                                                        $Invest_status_badge = "<div class=\"badge bg-success-subtle text-approved fs-12\">Running</div>";
                                                    }
                                                ?>
                                                <?php echo $Invest_status_badge; ?>
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <div>
                                                <p class="text-muted mb-1">Last Run</p>
                                                <h5 class="fs-14"><?=e(getRelativeTime($data['settings']['last_cron']))?></h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <div class="d-flex mb-2">
                                        <div class="flex-grow-1">
                                            <div>Progress</div>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div><?php echo $Invest_progress_percentage; ?>%</div>
                                        </div>
                                    </div>
                                    <div class="progress progress-sm animated-progress">
                                        <div class="progress-bar <?php echo $Invest_background_class; ?>" role="progressbar" style="width: <?php echo $Invest_progress_percentage; ?>%;" aria-valuenow="<?php echo $Invest_progress_percentage; ?>" aria-valuemin="0" aria-valuemax="100">
                                        </div><!-- /.progress-bar -->
                                    </div><!-- /.progress -->
                                </div>

                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->
                    </div>
                    <!-- end col -->

                    <div class="col-xxl-3 col-sm-6 project-card">
                        <div class="card">
                            <div class="card-body">
                                <?php
                                    // Get the current timestamp
                                    $Deposit_current_time = time();

                                    // Get the timestamp of the last cron job
                                    $Deposit_last_cron_time = strtotime($data['settings']['last_deposit_cron']);

                                    // Calculate the difference in seconds
                                    $Deposit_time_difference = $Deposit_current_time - $Deposit_last_cron_time;

                                    // Define the threshold (15 minutes = 900 seconds)
                                    $Deposit_threshold = 900;

                                    // Determine the background color class based on the time difference
                                    $Deposit_background_class = ($Deposit_time_difference > $Deposit_threshold) ? "bg-danger-subtle" : "bg-success-subtle";
                                ?>
                                <div class="p-3 mt-n3 mx-n3 <?php echo $Deposit_background_class; ?> rounded-top">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <h5 class="mb-0 fs-14">
                                                <a href="#" class="text-body">Initiated Deposit Cron</a>
                                            </h5>
                                        </div>
                                    </div>
                                </div>

                                <div class="py-3">
                                    <div class="row gy-3">
                                        <div class="col-6">
                                            <div>
                                                <p class="text-muted mb-1">Status</p>
                                                <?php
                                                    // Get the current timestamp
                                                    $Deposit_current_time = time();

                                                    // Get the timestamp of the last cron job
                                                    $Deposit_last_cron_time = strtotime($data['settings']['last_deposit_cron']);

                                                    // Calculate the difference in seconds
                                                    $Deposit_time_difference = $Deposit_current_time - $Deposit_last_cron_time;

                                                    // Define the threshold (15 minutes = 900 seconds)
                                                    $Deposit_threshold = 900;

                                                    // Calculate the progress percentage
                                                    $Deposit_progress_percentage = ceil(min(100, ($Deposit_time_difference / $Deposit_threshold) * 100));

                                                    // Determine the status badge and text
                                                    if ($Deposit_time_difference > $Deposit_threshold) {
                                                        $Deposit_status_badge = "<div class=\"badge bg-danger-subtle text-danger fs-12\">Not Running</div>";
                                                    } else {
                                                        $Deposit_status_badge = "<div class=\"badge bg-success-subtle text-approved fs-12\">Running</div>";
                                                    }
                                                ?>
                                                <?php echo $Deposit_status_badge; ?>
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <div>
                                                <p class="text-muted mb-1">Last Run</p>
                                                <h5 class="fs-14"><?=e(getRelativeTime($data['settings']['last_deposit_cron']))?></h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div>
                                    <div class="d-flex mb-2">
                                        <div class="flex-grow-1">
                                            <div>Progress</div>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div><?php echo $Deposit_progress_percentage; ?>%</div>
                                        </div>
                                    </div>
                                    <div class="progress progress-sm animated-progress">
                                        <div class="progress-bar <?php echo $Deposit_background_class; ?>" role="progressbar" style="width: <?php echo $Deposit_progress_percentage; ?>%;" aria-valuenow="<?php echo $Deposit_progress_percentage; ?>" aria-valuemin="0" aria-valuemax="100">
                                        </div><!-- /.progress-bar -->
                                    </div><!-- /.progress -->
                                </div>

                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!--end col-->
        </div>
        <!--end row-->
    </div>
    <!-- container-fluid -->
</div>
