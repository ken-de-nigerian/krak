<?php
defined('FIR') OR exit();
/**
 * The template for displaying the footer section
 */
?>
<!-- **************** MAIN CONTENT START **************** -->
<main>
    <!-- =======================
    Main hero START -->
    <section class="pt-lg-8">
        <div class="container pt-4 pt-lg-0">
            <!-- Breadcrumb & title -->
            <div class="row align-items-center mb-7">
                <div class="col-xl-6">
                    <!-- Breadcrumb -->
                    <div class="d-flex position-relative z-index-9">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb breadcrumb-dots mb-1">
                                <li class="breadcrumb-item"><a href="<?=$this->siteUrl()?>">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Property Listing</li>
                            </ol>
                        </nav>
                    </div>
                    <!-- Title -->
                    <h1 class="display-5">Property Listing</h1>
                </div>
                <!-- Content -->
                <div class="col-xl-5 ms-auto">
                    <p class="mb-4">With over 6 years of experience leading major real estate companies worldwide, we utilize our expertise and network to find properties with the greatest investment potential for you. we offer you the ability to fund, rent, buy or invest in any property of your choice.</p>
                    <div class="text-center d-inline-block bg-light border rounded px-5 py-3">
                        <span class="heading-color">Need Help?</span> <a class="ms-2" href="mailto:<?=e($this->siteSettings('email_address'))?>">Contact us now<span class="bi-chevron-right small ms-1"></span></a>
                    </div>
                </div>
            </div>

            <?php foreach ($data['listings'] as $house): ?>
                <!-- Portfolio item START -->
                <div class="card card-img-scale bg-transparent overflow-hidden mb-6 mb-xl-8">
                    <div class="row g-xl-6 align-items-center">
                        <div class="col-lg-6">
                            <!-- Card Image -->              
                            <div class="card-img-scale-wrapper rounded-2 h-100">
                                <img src="<?=$this->siteUrl().'/'.PUBLIC_PATH.'/'.UPLOADS_PATH?>/listings/<?=e($house['image'])?>" class="img-scale" alt="portfolio-img">
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="col-lg-6">
                            <!-- Card body -->
                            <div class="card-body h-100 px-0 p-xl-5">
                                <!-- Title -->
                                <h4 class="card-title"><?=e($house['title'])?></h4>
                                <p class="card-text mb-3 mb-lg-4">
                                    <?=e($house['description'])?>
                                </p>

                                <!-- List -->
                                <div class="d-flex gap-2 gap-sm-3 gap-lg-1 flex-lg-column flex-wrap mb-3 mb-lg-4">
                                    <div>
                                        Amount: $<?=e($house['amount'])?>
                                    </div>

                                    <span>
                                        Location: <?=e($house['location'])?>
                                    </span>

                                    <span>
                                        Added Date: <?= date('M j, Y', strtotime($house['date_added'])) ?>
                                    </span>
                                </div>

                                <!-- Button -->
                                <a href="<?=$this->siteUrl()?>/listings/details/<?=e($house['propertyId'])?>" class="text-primary-hover stretched-link heading-color mb-0">Request Info<i class="fa-solid fa-arrow-right-long fa-fw ms-2"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Portfolio item END -->
            <?php endforeach ?>

            <!-- Pagination START -->
            <div class="row mt-7">
                <div class="col-12">
                    <ul class="pagination pagination-primary-soft list-unstyled d-flex justify-content-center flex-wrap mb-0">
                        <li class="page-item">
                            <a class="page-link" href="#"><i class="fas fa-long-arrow-alt-left me-2 rtl-flip"></i>Prev</a>
                        </li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item active"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">..</a></li>
                        <li class="page-item"><a class="page-link" href="#">22</a></li>
                        <li class="page-item"><a class="page-link" href="#">23</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next<i class="fas fa-long-arrow-alt-right ms-2 rtl-flip"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- Pagination END -->
        </div>  
    </section>
    <!-- =======================
    Main hero END -->

    <!-- =======================
    CTA START -->
    <section class="position-relative z-index-2 py-0 mb-n7">
        <div class="container position-relative">
            <div class="bg-primary rounded position-relative overflow-hidden p-4 p-sm-5">

                <!-- SVG decoration -->
                <figure class="position-absolute top-0 start-0 ms-n8">
                    <svg width="371" height="354" viewBox="0 0 371 354" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <ellipse cx="172.5" cy="176.5" rx="131.5" ry="125.5" fill="url(#paint0_linear_195_2)"></ellipse>
                        <ellipse cx="185.5" cy="177" rx="185.5" ry="177" fill="url(#paint1_linear_195_2)"></ellipse>
                        <defs>
                        <linearGradient id="paint0_linear_195_2" x1="172.5" y1="51" x2="172.5" y2="302" gradientUnits="userSpaceOnUse">
                        <stop offset="0.0569271" stop-color="#D9D9D9" stop-opacity="0.5"></stop>
                        <stop offset="0.998202" stop-color="#D9D9D9" stop-opacity="0"></stop>
                        </linearGradient>
                        <linearGradient id="paint1_linear_195_2" x1="185.5" y1="0" x2="185.5" y2="354" gradientUnits="userSpaceOnUse">
                        <stop offset="0.0569271" stop-color="#D9D9D9" stop-opacity="0.2"></stop>
                        <stop offset="0.998202" stop-color="#D9D9D9" stop-opacity="0"></stop>
                        </linearGradient>
                        </defs>
                    </svg>
                </figure>

                <!-- Image decoration -->
                <div class="position-absolute end-0 bottom-0 me-sm-5">
                    <img src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/home/images/elements/cta-vector.svg" class="h-200px" alt="vector-img">
                </div>

                <div class="row g-4 position-relative">
                    <!-- Title and inputs -->
                    <div class="col-lg-7 mx-auto text-center">
                        <!-- Title -->
                        <h2 class="text-white mb-4">Let's Work Together</h2>
                        <p class="text-white mb-4">I'll take the time to understand your vision and goals, and work with you to develop a customized plan to help you succeed. </p>
                        <!-- Button -->
                        <div class="d-sm-flex justify-content-center align-items-center gap-2">
                            <a href="mailto:<?=e($this->siteSettings('email_address'))?>" class="btn btn-dark"><i class="bi bi-envelope me-2"></i>Email us</a>
                            <a href="mailto:<?=e($this->siteSettings('email_address'))?>" class="btn btn-dark"><i class="bi bi-telephone me-2"></i>Contact us</a>
                        </div>
                    </div>

                </div> <!-- Row END -->
            </div>
        </div>
    </section>
    <!-- =======================
    CTA END -->
</main>
<!-- **************** MAIN CONTENT END **************** -->