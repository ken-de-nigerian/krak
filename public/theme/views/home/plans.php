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
                    Plans
                </h1>
                <p class="mb-5">Achieving your investment goals requires a pricing strategy that fits your financial objectives. We offer a range of investment plans, each designed to align with your unique needs and maximize your returns.</p>
            </div>
        </div>
    </section>
    <!-- =======================
    Main Banner END -->

    <!-- =======================
    Pricing START -->
    <section class="pt-8">
        <div class="container">
            <!-- Slider START -->
            <div class="swiper" data-swiper-options='{
                "loop": false,
                "spaceBetween": 40,
                "pagination":{
                    "el":".swiper-pagination"
                },
                "breakpoints": {
                    "576": {"slidesPerView": 1}, 
                    "768": {"slidesPerView": 2}, 
                    "992": {"slidesPerView": 3}
                }}'>

                <!-- Slider items -->
                <div class="swiper-wrapper">
                    <?php foreach ($data['plans'] as $plan): ?>
                    <!-- Slider item -->
                    <div class="swiper-slide">
                        <!-- Pricing item -->
                        <div class="card card-body bg-dark border rounded p-md-4" data-bs-theme="dark">
                            <h6 class="mb-2 text-primary"><?=e($plan['name'])?></h6>
                            <!-- Price -->
                            <div class="d-flex align-items-center">
                                <span class="h1 mb-0">
                                    <?php if ($plan['fixed_amount'] == 0): ?>
                                        $<?=e($plan['minimum'])?> - <?= ($plan['maximum'] === "Unlimited") ? $plan['maximum'] : '$' . $plan['maximum'] ?>
                                    <?php else: ?>
                                        $<?=e($plan['fixed_amount'])?>
                                    <?php endif ?>
                                </span>
                            </div>

                            <!-- Buttons -->
                            <a href="<?=$this->siteUrl()?>/user/plans" class="btn btn-primary mt-4">Get started</a>

                            <hr class="my-4"> <!-- Divider -->

                            <h6 class="mb-0">Features</h6>
                            <span><?=e($plan['description'])?></span>

                            <!-- List -->
                            <ul class="list-group list-group-borderless border-0 mb-0 mt-2">
                                <li class="list-group-item d-flex mb-0"><i class="bi bi-check-circle-fill text-primary me-2"></i>
                                    ROI - <?=e($plan['interest'])?>%
                                </li>

                                <li class="list-group-item d-flex mb-0"><i class="bi bi-check-circle-fill text-primary me-2"></i>
                                    Duration - 
                                    <?php foreach($data['times'] as $time){
                                        if($time['time'] === $plan['times']){ ?>
                                        After <?=e($time["name"])?>
                                    <?php
                                        break;
                                        }
                                    } ?>
                                </li>

                                <li class="list-group-item d-flex mb-0"><i class="bi bi-check-circle-fill text-primary me-2"></i>
                                    Referrals - <?=e($data['referral-settings']['percent'])?>%
                                </li>

                                <li class="list-group-item d-flex mb-0"><i class="bi bi-check-circle-fill text-primary me-2"></i>
                                    Trade Management - <?=e($plan['trade_management'])?>
                                </li>

                                <li class="list-group-item d-flex mb-0"><i class="bi bi-check-circle-fill text-primary me-2"></i>
                                    <?php if ($plan['capital_back_status'] == 1): ?>
                                        Capital Returned - Yes
                                    <?php else: ?>
                                        Capital Returned - No
                                    <?php endif ?>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <?php endforeach ?>
                </div>

                <!-- Slider Pagination -->
                <div class="swiper-pagination swiper-pagination-primary position-relative mt-4"></div>
            </div>  
        </div>
    </section>
    <!-- =======================
    Pricing END -->

    <!-- =======================
    Feature START -->
    <section class="pt-0">
        <div class="container">
            <div class="inner-container-small text-center mb-4 mb-sm-6">
                <h2 class="mb-4">Features Included in Every Investment Plan</h2>
                <p class="mb-0">Our commitment to providing exceptional investment solutions sets us apart. We strive to deliver an unparalleled experience to help you achieve your financial goals.</p>
            </div>

            <!-- Feature item START -->
            <div class="row g-4 g-lg-5">
                <!-- Item -->
                <div class="col-md-4 text-center">
                    <div class="card card-body border bg-light">
                        <div class="icon-lg text-primary mx-auto mb-3"><i class="bi bi-clock fa-xl"></i></div>
                        <h6>Start Investing in Minutes</h6>
                        <p>Get up and running quickly with a seamless onboarding process, so you can focus on growing your portfolio.</p>
                    </div>
                </div>

                <!-- Item -->
                <div class="col-md-4 text-center">
                    <div class="card card-body border bg-light">
                        <div class="icon-lg text-primary mx-auto mb-3"><i class="bi bi-graph-up-arrow fa-xl"></i></div>
                        <h6>Maximize Your Returns</h6>
                        <p>Leverage our advanced tools and insights to optimize your investment strategies and achieve higher returns.</p>
                    </div>
                </div>

                <!-- Item -->
                <div class="col-md-4 text-center">
                    <div class="card card-body border bg-light">
                        <div class="icon-lg text-primary mx-auto mb-3"><i class="bi bi-headset fa-xl"></i></div>
                        <h6>24/7 Expert Support</h6>
                        <p>Our dedicated support team is available around the clock to assist with any investment-related queries or issues.</p>
                    </div>
                </div>
            </div>
            <!-- Feature item END -->
        </div>
    </section>
    <!-- =======================
    Feature END -->

    <!-- =======================
    Faqs START -->
    <section class="pt-0">
        <div class="container">
            <div class="row">
                <div class="col-md-7 mx-auto">
                    <!-- Title -->
                    <h2 class="mb-5 text-center">Investment Questions Answered</h2>

                    <!-- Accordion START -->
                    <div class="accordion accordion-icon accordion-bg-light" id="accordionFaq">
                        <!-- Item -->
                        <div class="accordion-item mb-3">
                            <div class="accordion-header font-base" id="heading-1">
                                <button class="accordion-button fw-semibold rounded collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-1" aria-expanded="true" aria-controls="collapse-1">
                                    How do I start investing with your platform?
                                </button>
                            </div>
                            <!-- Body -->
                            <div id="collapse-1" class="accordion-collapse collapse show" aria-labelledby="heading-1" data-bs-parent="#accordionFaq">
                                <div class="accordion-body mt-3 pb-0">
                                    Getting started is simple. Sign up on our platform, complete the verification process, and deposit funds into your account. Our user-friendly interface will guide you through the initial steps to begin investing.
                                </div>
                            </div>
                        </div>

                        <!-- Item -->
                        <div class="accordion-item mb-3">
                            <div class="accordion-header font-base" id="heading-2">
                                <button class="accordion-button fw-semibold rounded collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-2" aria-expanded="false" aria-controls="collapse-2">
                                    What types of investments can I make?
                                </button>
                            </div>
                            <!-- Body -->
                            <div id="collapse-2" class="accordion-collapse collapse" aria-labelledby="heading-2" data-bs-parent="#accordionFaq">
                                <div class="accordion-body mt-3 pb-0">
                                    Our platform offers a variety of investment options including stocks, bonds, mutual funds, ETFs, and more. You can diversify your portfolio according to your risk tolerance and investment goals.
                                </div>
                            </div>
                        </div>

                        <!-- Item -->
                        <div class="accordion-item mb-3">
                            <div class="accordion-header font-base" id="heading-3">
                                <button class="accordion-button fw-semibold collapsed rounded" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-3" aria-expanded="false" aria-controls="collapse-3">
                                    How can I track my investment performance?
                                </button>
                            </div>
                            <!-- Body -->
                            <div id="collapse-3" class="accordion-collapse collapse" aria-labelledby="heading-3" data-bs-parent="#accordionFaq">
                                <div class="accordion-body mt-3 pb-0">
                                    Our platform provides comprehensive tools for tracking your investments. You can view real-time performance metrics, historical data, and detailed reports to monitor and analyze your portfolio's performance.
                                </div>
                            </div>
                        </div>

                        <!-- Item -->
                        <div class="accordion-item mb-3">
                            <div class="accordion-header font-base" id="heading-4">
                                <button class="accordion-button fw-semibold collapsed rounded" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-4" aria-expanded="false" aria-controls="collapse-4">
                                    What are the fees associated with investing?
                                </button>
                            </div>
                            <!-- Body -->
                            <div id="collapse-4" class="accordion-collapse collapse" aria-labelledby="heading-4" data-bs-parent="#accordionFaq">
                                <div class="accordion-body mt-3 pb-0">
                                    We offer competitive fee structures. Our fees include transaction costs, management fees, and any applicable service charges. For detailed information, please refer to our fee schedule or contact our support team.
                                </div>
                            </div>
                        </div>

                        <!-- Item -->
                        <div class="accordion-item mb-3">
                            <div class="accordion-header font-base" id="heading-5">
                                <button class="accordion-button fw-semibold collapsed rounded" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-5" aria-expanded="false" aria-controls="collapse-5">
                                    Is there a minimum investment amount?
                                </button>
                            </div>
                            <!-- Body -->
                            <div id="collapse-5" class="accordion-collapse collapse" aria-labelledby="heading-5" data-bs-parent="#accordionFaq">
                                <div class="accordion-body mt-3 pb-0">
                                    Yes, there is a minimum investment amount required to start. This amount varies depending on the type of investment and account level. Check our investment options for specific minimums.
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Accordion END -->
                </div>
            </div>
        </div>
    </section>
    <!-- =======================
    Faqs END -->
</main>
<!-- **************** MAIN CONTENT END **************** -->