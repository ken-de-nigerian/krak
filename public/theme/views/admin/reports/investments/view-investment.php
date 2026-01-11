<?php
defined('FIR') OR exit();
/**
 * The template for displaying the footer section
 */
?>

<script>
    "use strict"
    function createCountDown(elementId, sec) {
        // Check if the elementId exists and is not empty
        if (elementId && elementId.length > 0) {
            let tms = sec;
            const x = setInterval(function () {
                const distance = tms * 1000;
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                document.getElementById(elementId).innerHTML = days + "d: " + hours + "h " + minutes + "m " + seconds + "s ";
                if (distance < 0) {
                    clearInterval(x);
                    document.getElementById(elementId).innerHTML = 'Timer Expired';
                }
                tms--;
            }, 1000);
        }
    }
</script>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card rounded-0 bg-success-subtle mx-n4 mt-n4 border-top">
                    <div class="px-4">
                        <div class="row">
                            <div class="col-xxl-5 align-self-center">
                                <div class="py-4">
                                    <?php foreach ($data['plans'] as $plan): ?>
                                        <?php if ($plan['planId'] === $data['investment-details']['planId']): ?>
                                            <?php foreach ($data['users'] as $user): ?>
                                                <?php if ($user['userid'] === $data['investment-details']['userid']): ?>
                                                    <h4 class="display-6 coming-soon-text">
                                                        <?=e($plan["name"])?>           
                                                    </h4>

                                                    <p class="text-success fs-15 mt-3">Invested By <?=e($user["firstname"])?> <?=e($user["lastname"])?> - $<?=e($data['investment-details']["amount"])?></p>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>

                                    <div class="hstack flex-wrap gap-2">
                                        <a href="<?=$this->siteUrl()?>/admin/investments" class="btn btn-primary btn-label rounded-pill">
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
                    <div class="col-xxl-4">
                        <?php if ($data['investment-details']['status'] == 2): ?>
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex align-items-center">
                                        <h5 class="card-title flex-grow-1 mb-0" id="timestamp<?=e($data['investment-details']['id'])?>"></h5>
                                        <script>
                                            createCountDown('timestamp<?php echo $data['investment-details']['id']; ?>', <?php 
                                                $nextTime = strtotime($data['investment-details']['next_time']);
                                                $now = time();
                                                $diffInSeconds = $nextTime - $now;
                                                echo $diffInSeconds; 
                                            ?>);
                                        </script>
                                    </div>
                                </div>

                                <?php
                                    if (!function_exists('diffDatePercent')) {
                                        function diffDatePercent($start , $end): string
                                        {
                                            $start = strtotime($start);
                                            $end = strtotime($end);

                                            $diff = $end - $start;

                                            $current = time();
                                            $cdiff = $current - $start;

                                            if ($cdiff > $diff) {
                                                $percentage = 1.0;
                                            } elseif ($current < $start) {
                                                $percentage = 0.0;
                                            } else {
                                                $percentage = $cdiff / $diff;
                                            }

                                            return sprintf('%.2f%%', $percentage * 100);
                                        }
                                    }
                                ?>

                                <?php if ($data['investment-details']['status'] == 2): ?>
                                    <?php
                                    if (!empty($data['investment-details']['last_time'])) {
                                        $start = $data['investment-details']['last_time'];
                                    } else {
                                        $start = $data['investment-details']['created_at'];
                                    }
                                    ?>

                                    <div class="card-body">
                                        <div class="progress animated-progress custom-progress progress-label">
                                            <div class="progress-bar bg-danger" role="progressbar" style="width: <?php echo diffDatePercent($start, $data['investment-details']['next_time']); ?>" aria-valuenow="<?php echo diffDatePercent($start, $data['investment-details']['next_time']); ?>" aria-valuemin="0" aria-valuemax="100">
                                                <div class="label"><?php echo diffDatePercent($start, $data['investment-details']['next_time']); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif ?>
                            </div>
                        <?php endif ?>

                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex align-items-center">
                                    <h5 class="card-title flex-grow-1 mb-0">Investment Information</h5>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-borderless mb-0">
                                        <tbody>
                                            <tr>
                                                <th class="ps-0" scope="row">Plan :</th>
                                                <td class="text-muted">
                                                    <?php foreach ($data['plans'] as $plan): ?>
                                                        <?php if ($plan['planId'] === $data['investment-details']['planId']): ?>
                                                            <?=e($plan["name"])?>  
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th class="ps-0" scope="row">Invested By</th>
                                                <td class="text-muted">
                                                    <?php foreach ($data['users'] as $user): ?>
                                                        <?php if ($user['userid'] === $data['investment-details']['userid']): ?>
                                                            <span class="badge badge-label bg-primary">
                                                                <i class="mdi mdi-circle-medium"></i>
                                                                <?=e($user["firstname"])?> <?=e($user["lastname"])?>
                                                            </span>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th class="ps-0" scope="row">Amount :</th>
                                                <td class="text-muted">$<?=e($data['investment-details']["amount"])?></td>
                                            </tr>

                                            <tr>
                                                <th class="ps-0" scope="row">Interest :</th>
                                                <td class="text-muted">$<?=e($data['investment-details']["interest"])?>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th class="ps-0" scope="row">Duration :</th>
                                                <td class="text-muted">
                                                    <?php foreach ($data['times'] as $time): ?>
                                                        <?php if ($time['time'] === $data['investment-details']['hours']): ?>
                                                            <?=e($time["name"])?>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th class="ps-0" scope="row">Invested</th>
                                                <td class="text-muted"><?= date('d M Y h:i A', strtotime($data['investment-details']['initiated_at'])) ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div>
                    <!--end col-->

                    <div class="col-xxl-8">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex align-items-center">
                                    <h5 class="card-title flex-grow-1 mb-0">Investment History</h5>

                                    <?php if ($data['investment-details']['status'] == 2): ?>
                                        <div class="flex-shrink-0">
                                            <div>
                                                <button data-bs-toggle="modal" data-bs-target="#cancelModal" class="btn btn-light"><i class="ri-delete-bin-7-line align-bottom"></i> Cancel</button>
                                            </div>
                                        </div>
                                    <?php endif ?>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="table-card">
                                    <table class="table table-borderless align-middle">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/frontend/templates/images/investment.png" alt="" class="avatar-xs rounded-circle" />
                                                        <div class="ms-2">
                                                            <a>
                                                                <h6 class="fs-15 mb-1">
                                                                    <?php foreach($data['plans'] as $plan){
                                                                            if($plan['planId'] === $data['investment-details']['planId']){ ?>
                                                                        <?=e($plan["name"])?>
                                                                    <?php
                                                                        break;
                                                                        }
                                                                    } ?>
                                                                </h6>
                                                            </a>

                                                            <p class="mb-0 text-muted"><?= date('d-m-Y h:i A', strtotime($data['investment-details']['initiated_at'])) ?></p>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td>
                                                    $<?= number_format((float) e($data['investment-details']['amount']), 2) ?>
                                                </td>

                                                <?php if ($data['investment-details']['status'] == 1): ?>
                                                    <td class="text-success">Completed</td>
                                                <?php elseif ($data['investment-details']['status'] == 2): ?>
                                                    <td class="text-warning">Pending</td>
                                                <?php elseif ($data['investment-details']['status'] == 3): ?>
                                                    <td>Initiated</td>
                                                <?php elseif ($data['investment-details']['status'] == 4): ?>
                                                    <td class="text-danger">Cancelled</td>
                                                <?php endif; ?>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end col-->
                </div>
                <!--end row-->
            </div>
        </div>
        <!--end row-->
    </div>
    <!-- container-fluid -->
</div>
<!-- End Page-content -->

<!-- cancelModal -->
<div id="cancelModal" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"  role="dialog" aria-labelledby="cancelBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="NotificationModalbtn-close"></button>
            </div>

            <div class="modal-body">
                <h4>Cancel Investment</h4>
                <p class="tx-color-03">choose an option manage this investment status.</p>

                <form id="cancel-investment-form">
                    <div class="form-floating mb-4">
                        <select name="action" class="form-control" id="action">
                            <option value="1">Return Capital & Interest to the user balance</option>
                            <option value="2">Return Capital With No interest</option>
                            <option value="3">Return Interest With No Capital</option>
                            <option value="4">No Capital & Interest is returned</option>
                        </select>
                        <label for="action">Action</label>
                    </div>

                    <div class="hstack gap-2 justify-content-end">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <?=$this->token()?>
                        <button id="cancelInvestBtn" class="btn btn-primary waves-effect waves-light w-100" data-id="<?= e($data['investment-details']['investId']) ?>">Submit Form</button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>