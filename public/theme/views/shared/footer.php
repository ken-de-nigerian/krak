<?php
defined('FIR') OR exit();
/**
 * The template for displaying the footer section
 */
?>
<!-- =======================
Footer START -->
<footer class="bg-dark pt-6 position-relative" data-bs-theme="dark">
    <div class="container position-relative mt-5">

        <!-- CTA -->
        <div class="bg-light border rounded p-4 p-sm-5 mb-7">
            <div class="d-md-flex align-items-center">
                <!-- Icon -->
                <div class="icon-lg bg-dark text-white rounded flex-shrink-0"><i class="bi bi-cursor fa-xl"></i></div>
                <!-- Content -->
                <div class="ms-md-4 my-4 my-md-0">
                    <h5>Start Your Investment Journey!</h5>
                    <p class="mb-0">Join Stonegate Holdings to access profitable markets, enjoy daily returns, and grow your wealth effortlessly.</p>
                </div>
                <!-- Button -->
                <a href="<?=$this->siteUrl()?>/register" class="btn btn-white mb-0 ms-auto flex-shrink-0">Invest Now</a>
            </div>
        </div>

        <!-- Widgets -->
        <div class="row g-4 justify-content-between">
            <!-- Widget 1 START -->
            <div class="col-lg-5">
                <!-- logo -->
                <a href="<?=$this->siteUrl()?>">
                    <img class="light-mode-item h-40px" src="<?=$this->siteUrl().'/'.PUBLIC_PATH.'/'.UPLOADS_PATH?>/logo/logo-dark.png" alt="logo" />
                    <img class="dark-mode-item h-40px" src="<?=$this->siteUrl().'/'.PUBLIC_PATH.'/'.UPLOADS_PATH?>/logo/<?=e($this->siteSettings('logo'))?>" alt="logo" />
                </a>

                <p class="mt-4 mb-2">
                    <?=e($this->siteSettings('sitename'))?> simplifies the process of investing digital assets from anywhere in the world.
                </p>
                
                <p class="mt-4 mb-2">
                    Trump Royale, 18201 Collins Ave, Sunny Isles Beach, FL 33160, United States.
                </p>
            </div>
            <!-- Widget 1 END -->

            <!-- Widget 2 START -->
            <div class="col-lg-6 col-xxl-4">
                <div class="row g-4">
                    <!-- Link block -->
                    <div class="col-6">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link pt-0" href="<?=$this->siteUrl()?>">Home</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="<?=$this->siteUrl()?>/about">About us</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="<?=$this->siteUrl()?>/services">Our Services</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="<?=$this->siteUrl()?>/plans">Plans</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="<?=$this->siteUrl()?>/coins">Buy BTC / ETH / LTC</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="<?=$this->siteUrl()?>/faqs">FAQs</a>
                            </li>
                        </ul>
                    </div>

                    <!-- Link block -->
                    <div class="col-6">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link pt-0" href="<?=$this->siteUrl()?>/services">Agricultural Investments</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="<?=$this->siteUrl()?>/services">Crude Oil Trading</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="<?=$this->siteUrl()?>/services">Forex Market</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="<?=$this->siteUrl()?>/services">Real Estate Investment</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="<?=$this->siteUrl()?>/services">Gold Mining Investments</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="<?=$this->siteUrl()?>/services">See More <i class="bi bi-box-arrow-up-right small ms-1"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- Widget 2 END -->
        </div>

        <!-- Divider -->
        <hr class="mt-4 mb-0">

        <!-- Bottom footer -->
        <div class="d-md-flex justify-content-between align-items-center text-center text-lg-start py-4">
            <!-- copyright text -->
            <div class="text-body">Copyrights ©<?php echo date("Y"); ?> <?=e($this->siteSettings('sitename'))?>. All Rights Reserved.</div>
            <!-- copyright links-->
            <div class="nav mt-2 mt-lg-0">
                <ul class="list-inline mx-auto mb-0">
                    <li class="list-inline-item me-0"><a class="nav-link py-0" href="<?=$this->siteUrl()?>/policy">Privacy policy</a></li>
                    <li class="list-inline-item me-0"><a class="nav-link py-0 pe-0" href="<?=$this->siteUrl()?>/policy">Terms &amp; conditions</a></li>
                </ul>
            </div>
        </div>
        
    </div>
</footer>
<!-- =======================
Footer END -->