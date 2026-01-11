<?php
defined('FIR') OR exit();
/**
 * The template for displaying the footer section
 */
?>

<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">
            <div id="two-column-menu"></div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu">Dashboard</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link <?php echo $data['url'] === "dashboard" ? 'active' : ''; ?>" href="<?=$this->siteUrl()?>/admin/dashboard">
                        <i class="ri-apps-2-fill"></i> <span data-key="t-dashboards">Dashboard</span>
                    </a>
                </li>

                <li class="menu-title"><span data-key="t-menu">Manage</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarPlans" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarPlans">
                        <i class="ri-timer-flash-fill"></i> <span data-key="t-apps">Plan & Time</span>
                    </a>

                    <div class="collapse menu-dropdown <?php echo $data['url'] === "plans" || $data['url'] === "time" ? 'show ' : ''; ?>" id="sidebarPlans">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="<?=$this->siteUrl()?>/admin/plans" class="nav-link <?php echo $data['url'] === "plans" ? 'active' : ''; ?>" data-key="t-chat"> Plan Manage </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?=$this->siteUrl()?>/admin/time" class="nav-link <?php echo $data['url'] === "time" ? 'active' : ''; ?>" data-key="t-api-key">Time Manage</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link <?php echo $data['url'] === "referrals" ? 'active' : ''; ?>" href="<?=$this->siteUrl()?>/admin/referrals">
                        <i class="ri-swap-fill"></i> <span data-key="t-layouts">Referral Settings</span>
                    </a>
                </li>

                <li class="menu-title"><span data-key="t-menu">Finances</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarDeposits" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarDeposits">
                        <i class="ri-money-dollar-circle-fill"></i> <span data-key="t-layouts">Deposits</span>

                        <?php if (!empty($data["pending-deposits-count"]) || !empty($data["initiated-deposits-count"])) : ?>
                            <span class="badge badge-pill bg-danger" data-key="t-hot">!</span>
                        <?php endif; ?>
                    </a>
                    <div class="collapse menu-dropdown <?php echo $data['url'] === "deposits_pending" || $data['url'] === "deposits_completed" || $data['url'] === "deposits_rejected" || $data['url'] === "deposits_initiated" || $data['url'] === "deposits" ? 'show ' : ''; ?>" id="sidebarDeposits">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="<?=$this->siteUrl()?>/admin/deposits_pending" class="nav-link <?php echo $data['url'] === "deposits_pending" ? 'active' : ''; ?>" data-key="t-horizontal">Pending 
                                    
                                    <?php if (!empty($data["pending-deposits-count"])) : ?>
                                        <span class="badge badge-pill bg-danger" data-key="t-hot"><?=e($data["pending-deposits-count"])?></span>
                                    <?php endif; ?>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?=$this->siteUrl()?>/admin/deposits_completed" class="nav-link <?php echo $data['url'] === "deposits_completed" ? 'active' : ''; ?>" data-key="t-detached">Completed</a>
                            </li>

                            <li class="nav-item">
                                <a href="<?=$this->siteUrl()?>/admin/deposits_rejected" class="nav-link <?php echo $data['url'] === "deposits_rejected" ? 'active' : ''; ?>" data-key="t-two-column">Rejected</a>
                            </li>

                            <li class="nav-item">
                                <a href="<?=$this->siteUrl()?>/admin/deposits_initiated" class="nav-link <?php echo $data['url'] === "deposits_initiated" ? 'active' : ''; ?>" data-key="t-hovered">Initiated 

                                    <?php if (!empty($data["initiated-deposits-count"])) : ?>
                                        <span class="badge badge-pill bg-danger" data-key="t-hot"><?=e($data["initiated-deposits-count"])?></span>
                                    <?php endif; ?>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?=$this->siteUrl()?>/admin/deposits" class="nav-link <?php echo $data['url'] === "deposits" ? 'active' : ''; ?>" data-key="t-hovered">All Deposits</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarWithdrawals" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarWithdrawals">
                        <i class="ri-funds-fill"></i> <span data-key="t-layouts">Withdrawals</span>

                        <?php if (!empty($data["pending-withdrawals-count"]) || !empty($data["initiated-withdrawals-count"])) : ?>
                            <span class="badge badge-pill bg-danger" data-key="t-hot">!</span>
                        <?php endif; ?>
                    </a>

                    <div class="collapse menu-dropdown <?php echo $data['url'] === "withdrawals_pending" || $data['url'] === "withdrawals_completed" || $data['url'] === "withdrawals_rejected" || $data['url'] === "withdrawals_initiated" || $data['url'] === "withdrawals" ? 'show ' : ''; ?>" id="sidebarWithdrawals">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="<?=$this->siteUrl()?>/admin/withdrawals_pending" class="nav-link <?php echo $data['url'] === "withdrawals_pending" ? 'active' : ''; ?>" data-key="t-horizontal">Pending 
                                    
                                    <?php if (!empty($data["pending-withdrawals-count"])) : ?>
                                        <span class="badge badge-pill bg-danger" data-key="t-hot"><?=e($data["pending-withdrawals-count"])?></span>
                                    <?php endif; ?>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?=$this->siteUrl()?>/admin/withdrawals_completed" class="nav-link <?php echo $data['url'] === "withdrawals_completed" ? 'active' : ''; ?>" data-key="t-detached">Approved</a>
                            </li>

                            <li class="nav-item">
                                <a href="<?=$this->siteUrl()?>/admin/withdrawals_rejected" class="nav-link <?php echo $data['url'] === "withdrawals_rejected" ? 'active' : ''; ?>" data-key="t-two-column">Rejected</a>
                            </li>

                            <li class="nav-item">
                                <a href="<?=$this->siteUrl()?>/admin/withdrawals_initiated" class="nav-link <?php echo $data['url'] === "withdrawals_initiated" ? 'active' : ''; ?>" data-key="t-hovered">Initiated 
                                    
                                    <?php if (!empty($data["initiated-withdrawals-count"])) : ?>
                                        <span class="badge badge-pill bg-danger" data-key="t-hot"><?=e($data["initiated-withdrawals-count"])?></span>
                                    <?php endif; ?>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?=$this->siteUrl()?>/admin/withdrawals" class="nav-link <?php echo $data['url'] === "withdrawals" ? 'active' : ''; ?>" data-key="t-hovered">All Withdrawals</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarInvestments" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarInvestments">
                        <i class="ri-coins-fill"></i> <span data-key="t-layouts">Investments</span>

                        <?php if (!empty($data["running-investments-count"])) : ?>
                            <span class="badge badge-pill bg-danger" data-key="t-hot">!</span>
                        <?php endif; ?>
                    </a>

                    <div class="collapse menu-dropdown <?php echo $data['url'] === "investments_running" || $data['url'] === "investments_completed" || $data['url'] === "investments_cancelled" || $data['url'] === "investments_initiated" || $data['url'] === "investments" ? 'show ' : ''; ?>" id="sidebarInvestments">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="<?=$this->siteUrl()?>/admin/investments_running" class="nav-link <?php echo $data['url'] === "investments_running" ? 'active' : ''; ?>" data-key="t-horizontal">Running 
                                    
                                    <?php if (!empty($data["running-investments-count"])) : ?>
                                        <span class="badge badge-pill bg-danger" data-key="t-hot"><?=e($data["running-investments-count"])?></span>
                                    <?php endif; ?>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?=$this->siteUrl()?>/admin/investments_completed" class="nav-link <?php echo $data['url'] === "investments_completed" ? 'active' : ''; ?>" data-key="t-detached">Completed</a>
                            </li>

                            <li class="nav-item">
                                <a href="<?=$this->siteUrl()?>/admin/investments_cancelled" class="nav-link <?php echo $data['url'] === "investments_cancelled" ? 'active' : ''; ?>" data-key="t-two-column">Cancelled</a>
                            </li>

                            <li class="nav-item">
                                <a href="<?=$this->siteUrl()?>/admin/investments_initiated" class="nav-link <?php echo $data['url'] === "investments_initiated" ? 'active' : ''; ?>" data-key="t-hovered">Initiated</a>
                            </li>

                            <li class="nav-item">
                                <a href="<?=$this->siteUrl()?>/admin/investments" class="nav-link <?php echo $data['url'] === "investments" ? 'active' : ''; ?>" data-key="t-hovered">All Investments</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarReports" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarReports">
                        <i class="ri-stock-fill"></i> <span data-key="t-layouts">Reports</span>
                    </a>
                    <div class="collapse menu-dropdown <?php echo $data['url'] === "transactions" || $data['url'] === "commissions" ? 'show ' : ''; ?>" id="sidebarReports">
                        <ul class="nav nav-sm flex-column">

                            <li class="nav-item">
                                <a href="<?=$this->siteUrl()?>/admin/transactions" class="nav-link <?php echo $data['url'] === "transactions" ? 'active' : ''; ?>" data-key="t-detached">Transactions History</a>
                            </li>

                            <li class="nav-item">
                                <a href="<?=$this->siteUrl()?>/admin/commissions" class="nav-link <?php echo $data['url'] === "commissions" ? 'active' : ''; ?>" data-key="t-two-column">Referral Commissions</a>
                            </li>
                            
                            <li class="nav-item">
                                <a href="<?=$this->siteUrl()?>/admin/loans" class="nav-link <?php echo $data['url'] === "loans" ? 'active' : ''; ?>" data-key="t-two-column">Loans History</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="menu-title"><span data-key="t-menu">Gateways</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarGateways" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarGateways">
                        <i class="ri-currency-fill"></i> <span data-key="t-apps">Payment Methods</span>
                    </a>

                    <div class="collapse menu-dropdown <?php echo $data['url'] === "deposit_gateway" || $data['url'] === "withdrawal_gateway" ? 'show ' : ''; ?>" id="sidebarGateways">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="<?=$this->siteUrl()?>/admin/deposit_gateway" class="nav-link <?php echo $data['url'] === "deposit_gateway" ? 'active' : ''; ?>" data-key="t-chat"> Deposit Gateways </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?=$this->siteUrl()?>/admin/withdrawal_gateway" class="nav-link <?php echo $data['url'] === "withdrawal_gateway" ? 'active' : ''; ?>" data-key="t-api-key">Withdrawal Gateways</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-pages">Users</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link collapsed" href="#sidebarUsers" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarUsers">
                        <i class="ri-account-pin-circle-fill"></i> 
                        <span data-key="t-layouts">Manage Users</span> 

                        <?php if (!empty($data["banned-users-count"]) || !empty($data["kyc-unverified-count"]) || !empty($data["kyc-pending-count"])) : ?>
                            <span class="badge badge-pill bg-danger" data-key="t-hot">!</span>
                        <?php endif; ?>
                    </a>

                    <div class="menu-dropdown collapse <?php echo $data['url'] === "active" || $data['url'] === "banned" || $data['url'] === "kyc_unverified" || $data['url'] === "kyc_pending" || $data['url'] === "users" || $data['url'] === "notifications" || $data['url'] === "notify" ? 'show ' : ''; ?>" id="sidebarUsers">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="<?=$this->siteUrl()?>/admin/users" class="nav-link <?php echo $data['url'] === "users" ? 'active' : ''; ?>" data-key="t-two-column">All Users</a>
                            </li>
                            
                            <li class="nav-item">
                                <a href="<?=$this->siteUrl()?>/admin/active" class="nav-link <?php echo $data['url'] === "active" ? 'active' : ''; ?>" data-key="t-horizontal">Active Users</a>
                            </li>

                            <li class="nav-item">
                                <a href="<?=$this->siteUrl()?>/admin/banned" class="nav-link <?php echo $data['url'] === "banned" ? 'active' : ''; ?>" data-key="t-detached">Banned Users 

                                    <?php if (!empty($data["banned-users-count"])) : ?>
                                        <span class="badge badge-pill bg-danger" data-key="t-hot"><?=e($data["banned-users-count"])?></span>
                                    <?php endif; ?>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?=$this->siteUrl()?>/admin/kyc_unverified" class="nav-link <?php echo $data['url'] === "kyc_unverified" ? 'active' : ''; ?>" data-key="t-horizontal">Kyc Unverified 

                                    <?php if (!empty($data["kyc-unverified-count"])) : ?>
                                        <span class="badge badge-pill bg-danger" data-key="t-hot"><?=e($data["kyc-unverified-count"])?></span>
                                    <?php endif; ?>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?=$this->siteUrl()?>/admin/kyc_pending" class="nav-link <?php echo $data['url'] === "kyc_pending" ? 'active' : ''; ?>" data-key="t-detached">Kyc Pending 

                                    <?php if (!empty($data["kyc-pending-count"])) : ?>
                                        <span class="badge badge-pill bg-danger" data-key="t-hot"><?=e($data["kyc-pending-count"])?></span>
                                    <?php endif; ?>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?=$this->siteUrl()?>/admin/notifications" class="nav-link <?php echo $data['url'] === "notifications" || $data['url'] === "notify" ? 'active' : ''; ?>" data-key="t-two-column">Email Notifications</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link <?php echo $data['url'] === "ranking" ? 'active' : ''; ?>" href="<?=$this->siteUrl()?>/admin/ranking">
                        <i class="ri-medal-fill"></i> <span data-key="t-pages">User Ranking</span>
                    </a>
                </li>

                <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-components">Settings</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link <?php echo $data['url'] === "cron" ? 'active' : ''; ?>" href="<?=$this->siteUrl()?>/admin/cron"> <i class=" ri-refresh-fill"></i> <span data-key="t-widgets">CronJob</span> </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link <?php echo $data['url'] === "extensions" ? 'active' : ''; ?>" href="<?=$this->siteUrl()?>/admin/extensions"> <i class="ri-paint-brush-fill"></i> <span data-key="t-widgets">Extensions</span> </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link <?php echo $data['url'] === "settings" ? 'active' : ''; ?>" href="<?=$this->siteUrl()?>/admin/settings"> <i class=" ri-settings-3-fill"></i> <span data-key="t-widgets">General Settings</span> </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link <?php echo $data['url'] === "logo" ? 'active' : ''; ?>" href="<?=$this->siteUrl()?>/admin/logo"> <i class="ri-camera-3-fill"></i> <span data-key="t-widgets">Logo & Favicon</span> </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarEmail" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarEmail">
                        <i class="ri-mail-settings-fill"></i> <span data-key="t-forms">Email Settings</span>
                    </a>
                    <div class="collapse menu-dropdown <?php echo $data['url'] === "templates" || $data['url'] === "email" ? 'show ' : ''; ?>" id="sidebarEmail">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="<?=$this->siteUrl()?>/admin/templates" class="nav-link <?php echo $data['url'] === "templates" ? 'active' : ''; ?>" data-key="t-basic-elements">Templates</a>
                            </li>
                            <li class="nav-item">
                                <a href="<?=$this->siteUrl()?>/admin/email" class="nav-link <?php echo $data['url'] === "email" ? 'active' : ''; ?>" data-key="t-form-select"> Email Configuration </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-components">Extra</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link <?php echo $data['url'] === "seo" ? 'active' : ''; ?>" href="<?=$this->siteUrl()?>/admin/seo">
                        <i class="ri-leaf-fill"></i> <span data-key="t-pages">Seo</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link <?php echo $data['url'] === "maintenance" ? 'active' : ''; ?>" href="<?=$this->siteUrl()?>/admin/maintenance">
                        <i class="ri-alarm-warning-fill"></i> <span data-key="t-pages">Maintenace Mode</span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->

<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>
