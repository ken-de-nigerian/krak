<?php
defined('FIR') OR exit();
/**
 * The template for displaying the footer section
 */
?>
<!-- **************** MAIN CONTENT START **************** -->
<main>
    <!-- =======================
    Main Banner START -->
    <section class="pt-xl-8 pb-0 position-relative bg-dark" data-bs-theme="dark">
        <div class="container py-5 position-relative">
            <!-- Title and contents -->
            <div class="inner-container mx-auto text-center">
                <!-- Title -->
                <h1 class="mb-4">Our
                    <!-- Decoration -->
                    <span>
                        <svg width="76" height="51" viewBox="0 0 76 51" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <mask id="path-1-inside-1_3060_294" fill="white">
                            <path d="M25.5 51C18.737 51 12.251 48.3134 7.46878 43.5312C2.6866 38.749 -1.88135e-07 32.263 0 25.5C1.88135e-07 18.737 2.6866 12.251 7.46878 7.46878C12.251 2.6866 18.737 -8.06482e-08 25.5 0L25.5 25.5L25.5 51Z"/>
                            </mask>
                            <path d="M25.5 51C18.737 51 12.251 48.3134 7.46878 43.5312C2.6866 38.749 -1.88135e-07 32.263 0 25.5C1.88135e-07 18.737 2.6866 12.251 7.46878 7.46878C12.251 2.6866 18.737 -8.06482e-08 25.5 0L25.5 25.5L25.5 51Z" fill="white" stroke="#202124" stroke-width="4" mask="url(#path-1-inside-1_3060_294)"/>
                            <path d="M50.5 51C43.737 51 37.251 48.3134 32.4688 43.5312C27.6866 38.749 25 32.263 25 25.5C25 18.737 27.6866 12.251 32.4688 7.46878C37.251 2.6866 43.737 -8.06482e-08 50.5 0L50.5 25.5L50.5 51Z" fill="#202124"/>
                            <path d="M75.5 51C68.737 51 62.251 48.3134 57.4688 43.5312C52.6866 38.749 50 32.263 50 25.5C50 18.737 52.6866 12.251 57.4688 7.46878C62.251 2.6866 68.737 -8.06482e-08 75.5 0V25.5L75.5 51Z" fill="#09B850"/>
                        </svg>
                    </span>
                    Services
                </h1>
                <p class="mb-5">Partner with our expert team to unlock new opportunities and navigate your investment journey with confidence.</p>
            </div>
        </div>
    </section>
    <!-- =======================
    Main Banner END -->

    <!-- =======================
    Services START -->
    <section class="bg-light overflow-hidden">
        <div class="container">
            <!-- Title and content -->
            <div class="row mb-4 mb-md-6">
                <div class="col-md-6 col-lg-5">
                    <h2 class="mb-0">Explore Our Services</h2>
                </div>

                <div class="col-md-6 col-lg-4 ms-auto">
                    <p>Explore our diverse range of investment opportunities, showcasing our expertise, innovation, and commitment to delivering exceptional results.</p>

                    <!-- Slider arrow -->
                    <div class="d-flex gap-3 position-relative mt-3">
                        <a href="#" class="btn btn-white border btn-icon rounded-circle mb-0 swiper-button-prev-team"><i class="bi bi-arrow-left"></i></a>
                        <a href="#" class="btn btn-white border btn-icon rounded-circle mb-0 swiper-button-next-team"><i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>

            <!-- Service start -->
            <div class="swiper swiper-outside-end-n20" data-swiper-options='{
                "spaceBetween": 50,
                "loop": false,
                "navigation":{
                    "nextEl":".swiper-button-next-team",
                    "prevEl":".swiper-button-prev-team"
                },
                "breakpoints": { 
                    "576": {"slidesPerView": 1},
                    "768": {"slidesPerView": 3},
                    "992": {"slidesPerView": 3},
                    "1200": {"slidesPerView": 4}
                }}'>
                
                <div class="swiper-wrapper">
                    <?php foreach ($data['services'] as $service): ?>
                    <!-- Service item -->
                    <div class="swiper-slide">
                        <div class="card card-img-scale bg-body overflow-hidden">
                            <!-- Image -->
                            <div class="card-img-scale-wrapper">
                                <img src="<?=$this->siteUrl().'/'.PUBLIC_PATH.'/'.UPLOADS_PATH?>/services/<?=e($service['image'])?>" class="card-img-top img-scale" alt="service image">
                            </div>
                            <!-- Card body -->
                            <div class="card-body p-4">
                                <h6><a href="<?=$this->siteUrl()?>/services?details=<?=e($service['serviceId'])?>"><?=e($service['title'])?></a></h6>
                                <p class="mb-0"><?=e($service['short'])?></p>
                            </div>
                            <!-- Card footer -->
                            <div class="card-footer border-top bg-body p-4">
                                <a class="icon-link icon-link-hover stretched-link p-0 m-0" href="<?=$this->siteUrl()?>/services?details=<?=e($service['serviceId'])?>">Explore this service<i class="bi bi-arrow-right"></i> </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach ?>
                </div>
            </div>
            <!-- Service END -->

            <!-- CTA -->
            <div class="d-flex align-items-center gap-2 mt-6">
                <ul class="avatar-group mb-0">
                    <li class="avatar avatar-sm">
                        <img class="avatar-img rounded-circle" src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/home/images/avatar/06.jpg" alt="avatar">
                    </li>
                    <li class="avatar avatar-sm">
                        <img class="avatar-img rounded-circle" src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/home/images/avatar/05.jpg" alt="avatar">
                    </li>
                    <li class="avatar avatar-sm">
                        <div class="avatar-img rounded-circle text-bg-dark">
                            <i class="bi bi-telephone text-white position-absolute top-50 start-50 translate-middle"></i>
                        </div>
                    </li>
                </ul>
                <p class="fw-normal mb-0">We are here to assist you with any issues or questions you might have. <a href="mailto:<?=e($this->siteSettings('email_address'))?>" class="text-decoration-underline text-primary-hover fw-semibold">Contact Us</a></p>
            </div>
        </div>
    </section>
    <!-- =======================
    Services END -->

    <!-- =======================
    Core feature START -->
    <section class="pt-3">
        <div class="container">
            <!-- Title -->
            <div class="inner-container-small text-center mb-4 mb-sm-6">
                <h2>Core Investment Features</h2>
                <p class="mb-0">Explore the key features designed to enhance your investment experience and drive your financial success.</p>
            </div>

            <!-- Feature START -->
            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-5 g-lg-6">
                <!-- item -->
                <div class="col">
                    <div class="card card-body bg-transparent text-center p-0">
                        <span class="h4 text-primary"><i class="bi bi-bar-chart"></i></span>
                        <h6 class="mb-2">Comprehensive Market Analysis</h6>
                        <p class="mb-0">Access detailed market insights and analytics to make informed investment decisions.</p>
                    </div>
                </div>
                
                <!-- item -->
                <div class="col">
                    <div class="card card-body bg-transparent text-center p-0">
                        <span class="h4 text-primary"><i class="bi bi-graph-up"></i></span>
                        <h6 class="mb-2">User-Friendly Dashboard</h6>
                        <p class="mb-0">Navigate a sleek and intuitive dashboard designed for optimal investment management.</p>
                    </div>
                </div>

                <!-- item -->
                <div class="col">
                    <div class="card card-body bg-transparent text-center p-0">
                        <span class="h4 text-primary"><i class="bi bi-headset"></i></span>
                        <h6 class="mb-2">24/7 Customer Support</h6>
                        <p class="mb-0">Get round-the-clock assistance for all your investment needs and queries.</p>
                    </div>
                </div>

                <!-- item -->
                <div class="col">
                    <div class="card card-body bg-transparent text-center p-0">
                        <span class="h4 text-primary"><i class="bi bi-gear"></i></span>
                        <h6 class="mb-2">Advanced Investment Tools</h6>
                        <p class="mb-0">Utilize sophisticated tools and features designed to maximize your investment potential.</p>
                    </div>
                </div>

                <!-- item -->
                <div class="col">
                    <div class="card card-body bg-transparent text-center p-0">
                        <span class="h4 text-primary"><i class="bi bi-shield"></i></span>
                        <h6 class="mb-2">Secure Transactions</h6>
                        <p class="mb-0">Benefit from state-of-the-art security measures to safeguard your investments.</p>
                    </div>
                </div>

                <!-- item -->
                <div class="col">
                    <div class="card card-body bg-transparent text-center p-0">
                        <span class="h4 text-primary"><i class="bi bi-speedometer"></i></span>
                        <h6 class="mb-2">High-Speed Execution</h6>
                        <p class="mb-0">Experience fast and efficient trade execution to stay ahead in the market.</p>
                    </div>
                </div>
                
            </div>
            <!-- Feature END -->
        </div>
    </section>
    <!-- =======================
    Core feature END -->
</main>
<!-- **************** MAIN CONTENT END **************** -->