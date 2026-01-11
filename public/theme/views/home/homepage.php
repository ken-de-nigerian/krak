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
    <section class="overflow-hidden pt-0 pb-0">
        <!-- Title and content -->
        <div class="bg-dark position-relative pt-7 pb-6 px-4 px-md-0">
            <!-- SVG decoration -->
            <figure class="position-absolute top-0 start-0">
                <svg class="fill-white" style="opacity:0.02" width="662" height="614" viewBox="0 0 662 614" xmlns="http://www.w3.org/2000/svg">
                    <path d="M-78 0V603.815C-61.4821 612.795 -44.1025 615.867 -28.4464 611.85C9.04192 602.16 38.9177 554.186 58.4519 503.612C77.8424 453.511 90.1949 397.029 105.995 343.383C121.794 289.973 142.477 237.745 173.215 206.549C224.779 154.321 291.425 172.991 349.166 202.768C406.907 232.545 466.227 272.248 525.979 256.414C570.505 244.598 611.441 200.878 636.002 138.724C652.233 97.6029 661.138 48.9196 662 0L-78 0Z"/>
                </svg>
            </figure>

            <!-- SVG decoration -->
            <figure class="position-absolute top-0 end-0">
                <svg class="fill-white" style="opacity:0.02" width="347" height="878" viewBox="0 0 347 878" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M425.992 -12.5L0.604347 -20.2914C0.604347 -20.2914 313.492 124 345.492 877.5L388.105 436L425.992 -12.5Z"/>
                    </svg>
            </figure>

            <!-- Title & Content -->
            <div class="inner-container text-center position-relative mt-8">
                <!-- Title -->
                <h1 class="mt-4 text-white">Investment platform</h1>
                <h6 class="h1 cd-headline clip big-clip is-full-width text-primary mb-5">
                    <span class="text-white pt-0">focused on </span>
                    <span class="typed" data-type-text="Crypto Trading&&Crypto Mining&&Forex Market&&Real Estate&&Agriculture&&Gold&&Crude Oil&&Insurance Investments&&Electricity Investments"></span>
                </h6>

                <p class="text-white opacity-7 mb-5">Partnering with 500+ Fortune companies and mid-sized firms to provide uniquely customized and scalable investment solutions tailored to your financial goals.</p>
                
                <!-- Button -->
                <div class="d-flex gap-1 gap-sm-3 flex-wrap justify-content-center mb-6">
                    <?php if ($data['user_isloggedin']): ?>
                        <a href="<?=$this->siteUrl()?>/user/logout" class="btn btn-sm btn-light mb-0">Logout</a>
                        <a href="<?=$this->siteUrl()?>/user/dashboard" class="btn btn-sm btn-primary mb-0"><i class="bi bi-person-circle me-1"></i>Dashboard</a>
                    <?php else: ?>
                        <a href="<?=$this->siteUrl()?>/login" class="btn btn-sm btn-light mb-0">Login</a>
                        <a href="<?=$this->siteUrl()?>/register" class="btn btn-sm btn-primary mb-0"><i class="bi bi-person-circle me-1"></i>Register Account</a>
                    <?php endif; ?>
                </div>

                <!-- List -->
                <ul class="list-inline d-flex flex-wrap justify-content-center gap-2 gap-sm-5 mb-0">
                    <li class="list-inline-item text-white"> <i class="bi bi-patch-check-fill text-primary me-2"></i>24/7 Support</li>
                    <li class="list-inline-item text-white"> <i class="bi bi-patch-check-fill text-primary me-2"></i>Award Winning Agency</li>
                    <li class="list-inline-item text-white"> <i class="bi bi-patch-check-fill text-primary me-2"></i>Tailored Solutions</li>
                </ul>
            </div>
        </div>  
    </section>
    <!-- =======================
    Main Banner END -->

    <div class="tradingview-widget-container">
        <div class="tradingview-widget-container__widget"></div>
        <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-ticker-tape.js" async>
            {
                "symbols": [{
                "proName": "OANDA:SPX500USD",
                "title": "S&P 500"
            },
                {
                    "proName": "OANDA:NAS100USD",
                    "title": "Nasdaq 100"
                },
                {
                    "proName": "FX_IDC:EURUSD",
                    "title": "EUR/USD"
                },
                {
                    "proName": "BITSTAMP:BTCUSD",
                    "title": "BTC/USD"
                },
                {
                    "proName": "BITSTAMP:ETHUSD",
                    "title": "ETH/USD"
                }
            ],
                "colorTheme": "light",
                "isTransparent": false,
                "displayMode": "regular",
                "locale": "en"
            }
        </script>
    </div>

    <section class="bg-light position-relative overflow-hidden">
        <div class="container position-relative">
            <div class="row align-items-start">
                <!-- About Us image -->
                <div class="col-lg-6 col-xl-5 position-relative text-center order-1 mt-6 mt-lg-0">
                    <!-- SVG decoration -->
                    <figure class="position-absolute top-0 start-0 mt-n6 ms-n5 z-index-1">
                        <svg width="117" height="98" viewBox="0 0 117 98" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path class="fill-primary" d="M53.9804 0.725277C52.9701 1.69222 52.9701 5.07654 53.9804 11.1683C54.9906 17.1634 55.7123 18.5654 57.5885 18.5654C58.3582 18.5654 59.128 18.3237 59.3685 18.0336C59.561 17.6952 59.2723 13.9241 58.7912 9.52449C58.1177 3.52942 57.6847 1.45049 57.0112 0.773624C55.9047 -0.241669 54.9425 -0.290017 53.9804 0.725277Z"></path>
                            <path class="fill-primary" d="M94.055 9.62103C87.2236 16.3413 84.8182 19.3872 85.0106 21.2244C85.1068 22.3847 85.3955 22.7715 86.3095 22.9165C87.2236 23.0616 88.3782 22.2397 91.6015 19.0004C96.9415 13.7305 101.512 8.46069 101.512 7.54209C101.512 6.62349 100.165 5.31812 99.2026 5.31812C98.7696 5.36646 96.4604 7.25201 94.055 9.62103Z"></path>
                            <path class="fill-primary" d="M23.2866 15.2294C22.2763 15.8096 22.1801 17.5984 23.046 18.7104C24.1044 20.0641 34.0148 25.9625 35.2656 25.9625C35.891 25.9625 36.7088 25.6241 37.0937 25.2373C38.537 23.4001 36.4202 21.1278 29.1077 16.5348C25.9806 14.6492 24.7779 14.3108 23.2866 15.2294Z"></path>
                            <path class="fill-primary" d="M103.433 40.37L97.8047 41.4337V42.8357C97.8047 43.851 98.1414 44.4312 99.0555 45.0114C100.21 45.6399 101.124 45.6399 107.09 44.8663C116.038 43.7543 116.038 43.7543 116.663 42.3523C117.096 41.337 117.048 40.9985 116.326 40.1283C115.22 38.8713 110.986 38.9196 103.433 40.37Z"></path>
                            <path class="fill-primary" d="M3.94616 46.1232C0.193694 46.8485 -1.20145 49.2175 1.20397 50.7646C1.97371 51.2481 2.83967 51.4414 3.80184 51.1997C4.57157 51.0547 8.42026 50.7162 12.4133 50.5228C17.8495 50.2328 19.7739 49.9427 20.3512 49.4109C21.5058 48.2505 20.3993 46.8001 17.8495 46.1232C15.3479 45.4947 7.12133 45.4947 3.94616 46.1232Z"></path>
                            <path class="fill-primary" d="M89.1454 69.9584C88.9529 70.1034 88.8086 70.7803 88.8086 71.4088C88.8086 72.7142 90.2518 73.8262 97.6125 77.9357C103.915 81.465 104.877 81.7551 106.416 80.4981C108.244 79.0477 107.234 77.5006 102.664 74.8898C95.3032 70.6352 90.3 68.7981 89.1454 69.9584Z"></path>
                            <path class="fill-primary" d="M30.8398 72.8111C28.3382 74.9384 21.2181 86.9769 20.0635 91.038C19.0532 94.519 21.9397 95.9211 24.0565 93.0203C24.6819 92.1984 26.3657 89.2975 27.7609 86.6868C29.1079 84.0277 31.369 80.1115 32.6198 77.8876C34.929 74.0681 35.2177 72.8111 33.9187 71.9408C33.0047 71.3123 32.3312 71.5541 30.8398 72.8111Z"></path>
                            <path class="fill-primary" d="M60.9544 80.7401C60.1365 81.707 60.0884 93.6488 60.9063 95.7277C61.243 96.4529 62.0128 97.3232 62.7825 97.6616C63.889 98.1935 64.1776 98.1451 65.0436 97.0815C65.5247 96.4529 65.8133 95.631 65.669 95.1959C65.5247 94.8091 65.2841 91.6666 65.0917 88.2823C64.9474 84.8979 64.6106 81.6103 64.4182 80.9818C63.889 79.6764 61.9165 79.5314 60.9544 80.7401Z"></path>
                        </svg>
                    </figure>

                    <!-- SVG decoration -->
                    <figure class="position-absolute bottom-0 end-0 mb-n6 me-n6 d-none d-xl-block">
                        <svg class="fill-white" width="170" height="133" viewBox="0 0 170 133" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M45.1119 117.165C45.245 116.536 45.2987 115.9 45.3557 115.224C45.6466 112.726 45.2954 110.253 44.9409 107.821C44.1631 102.349 42.9352 97 41.7469 91.654C40.8746 87.7763 39.9229 83.8918 39.5802 79.8986C39.5248 79.1331 39.4264 78.404 39.4505 77.6452C39.5193 75.4086 40.0293 75.0512 41.9043 76.0504C43.4353 76.8603 44.92 77.7465 46.3152 78.7452C48.6924 80.4274 50.9834 82.1825 53.3141 83.9409C60.1212 89.0403 66.925 94.1794 73.7751 99.2424C76.4464 101.23 79.2502 103.068 82.1933 104.678C83.7244 105.488 85.2555 106.298 86.989 106.604C90.1815 107.154 92.1918 105.602 92.5464 102.348C92.7438 100.483 92.3753 98.69 92.0068 96.897C91.125 92.658 89.8725 88.548 88.5802 84.4346C85.8178 75.472 82.8503 66.5721 80.4156 57.5171C79.1407 52.7244 78.1935 47.8392 77.7294 42.9148C77.5823 41.3407 77.5823 41.3407 78.1734 39.5486C79.2219 39.9174 79.9987 40.6637 80.7357 41.4066C82.4345 43.0716 84.1367 44.6969 85.7064 46.4711C89.4306 50.6697 93.1118 54.9046 96.7499 59.176C106.432 70.4845 116.157 81.7566 126.266 92.7407C128.024 94.651 129.872 96.4487 131.759 98.2498C132.536 98.9961 133.398 99.6696 134.357 100.151C136.467 101.21 138.381 99.8499 138.1 97.5036C137.926 96.2475 137.636 94.9417 137.178 93.7417C135.96 90.6357 134.703 87.5264 133.32 84.4865C129.327 75.9005 125.214 67.3045 121.137 58.7515C117.031 50.0759 112.882 41.4368 109.319 32.4867C106.98 26.5631 104.723 20.6064 103.083 14.4614C102.958 14.0504 102.482 13.5297 103.366 13.0037C104.357 14.0484 105.468 15.1032 106.41 16.2638C109.783 20.3527 113.117 24.4382 116.405 28.5999C123.776 37.9114 131.064 47.256 138.475 56.5709C141.912 60.8652 145.431 65.1265 149.297 69.0568C150.659 70.4531 152.067 71.7732 153.565 72.9808C154.95 74.0988 156.597 74.9586 158.318 73.9827C160.04 73.0068 160.231 71.2209 159.982 69.4379C159.824 68.4636 159.746 67.496 159.505 66.5547C159.087 64.8776 158.629 63.1972 158.045 61.5862C156.848 58.2416 155.611 54.8937 154.248 51.6153C151.127 44.0639 147.847 36.4992 144.725 28.9479C143.498 25.9611 142.357 22.9016 141.252 19.8852C141.153 19.6366 141.186 19.239 141.325 19.0105C141.518 18.6263 141.938 18.8619 142.166 19.0013C143.267 19.6949 144.371 20.3487 145.426 21.1185C148.55 23.4244 151.631 25.7667 154.756 28.0726C156.825 29.6087 158.981 31.072 161.097 32.532C162.611 33.5408 164.265 34.321 166.058 34.8728C166.603 35.0389 167.113 35.162 167.517 34.6355C167.835 34.1819 167.429 33.7872 167.214 33.4888C166.378 32.4972 165.542 31.5056 164.577 30.6233C158.908 25.2996 152.882 20.4263 146.322 16.1887C145.148 15.4089 143.878 14.8211 142.655 14.1573C141.775 13.6826 140.832 13.4829 139.876 13.4422C137.765 13.3442 136.467 14.516 136.206 16.6563C136.046 18.0843 136.491 19.4433 136.899 20.7592C138.231 24.876 139.937 28.8241 141.64 32.812C145.082 40.8308 148.563 48.853 151.962 56.9083C153.16 59.7724 154.146 62.7787 155.138 65.7056C155.528 66.7796 156.032 67.9033 155.713 69.318C154.185 68.4682 153.171 67.2214 152.11 66.0507C148.475 62.22 145.062 58.1278 141.771 54.0059C133.636 43.7891 125.545 33.5358 117.41 23.3189C114.915 20.1852 112.374 17.1277 109.751 14.1032C108.403 12.5478 106.922 11.1415 105.441 9.73512C104.664 8.98882 103.755 8.39152 102.746 8.02607C101.04 7.40151 99.4403 8.3477 99.0907 10.1202C98.881 11.1836 98.9095 12.2672 99.1796 13.3311C99.5056 14.6801 99.8283 16.0688 100.237 17.3847C102.801 25.85 106.021 34.1305 109.705 42.1297C115.484 54.6705 121.511 67.1121 127.373 79.6199C129.472 84.1217 131.449 88.6533 133.466 93.1882C133.667 93.6457 133.984 94.1529 133.364 94.8615C130.978 92.3376 128.585 89.8932 126.282 87.3362C117.178 77.2378 108.249 66.9539 99.4033 56.6369C95.9833 52.6242 92.4408 48.6413 88.9346 44.7014C86.9982 42.5358 84.9756 40.443 82.7376 38.5323C81.6534 37.6399 80.5691 36.7475 79.2991 36.1598C76.875 35.0342 75.0308 36.0398 74.6438 38.7301C74.4469 40.115 74.4883 41.52 74.609 42.9317C75.0927 48.0981 75.9834 53.1787 77.2382 58.2099C79.4252 66.8837 82.1847 75.4056 84.739 83.9902C86.1364 88.7532 87.6166 93.4831 88.5638 98.3683C88.7841 99.5481 88.9647 100.725 88.8641 101.917C88.7936 102.752 88.4094 103.04 87.6187 102.934C86.0043 102.637 84.5923 101.837 83.1803 101.037C79.8967 99.1985 76.7591 97.0516 73.7042 94.8717C66.6918 89.8351 59.7656 84.7256 52.7533 79.689C50.3761 78.0067 47.9559 76.3609 45.486 74.831C44.3089 74.091 42.9992 73.4999 41.6728 73.1077C39.3309 72.4295 37.599 73.5247 37.1958 75.9333C36.9861 76.9968 37.0543 78.0837 37.0828 79.1673C37.3489 83.5945 38.3431 87.923 39.2579 92.2448C40.4786 98.1541 41.739 104.067 42.3506 110.085C42.5995 112.348 42.567 114.628 42.0184 116.864C41.6493 118.395 40.7121 119.076 39.1737 118.827C38.029 118.65 36.8581 118.311 35.7732 117.899C33.1836 116.839 30.6437 115.664 28.0574 114.565C21.4001 111.48 14.862 108.406 8.01848 106.106C5.87843 105.405 3.74844 104.584 1.18058 104.688C1.77835 105.66 2.56571 105.806 3.22053 106.102C8.22424 108.406 13.1883 110.707 18.1489 113.048C22.9541 115.335 27.6799 117.616 32.4487 119.861C34.218 120.691 36.0338 121.445 37.9227 121.804C41.694 122.603 44.277 120.899 45.1119 117.165Z"></path>
                        </svg>
                    </figure>

                    <!-- Image -->
                    <img src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/home/images/about/02.jpg" class="rounded position-relative" alt="about-img">
                    <div class="card-img-overlay d-flex p-3">
                        <!-- Video button and link -->
                        <div class="m-auto">
                            <a href="https://www.youtube.com/embed/tXHviS-4ygo" class="btn btn-lg btn-white btn-round stretched-link mb-0" data-glightbox="" data-gallery="course-video">
                                <i class="fas fa-play"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- About Us content -->
                <div class="col-lg-6 ms-auto position-relative order-lg-2">
                    <h2 class="mb-4 mb-md-5">Who We Are</h2>
                    <p class="mb-4 mb-md-5">We are a team of dedicated investment professionals committed to delivering innovative financial solutions and exceptional service to our clients. Our mission is to help individuals and businesses achieve their financial goals through strategic investment planning, cutting-edge tools, and personalized advice.</p>
                    <!-- Accordion START -->
                    <div class="accordion accordion-icon accordion-bg-light" id="accordionFaq">
                        <!-- Item -->
                        <div class="accordion-item mb-3">
                            <div class="accordion-header font-base" id="heading-1">
                                <button class="accordion-button fw-semibold rounded collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-1" aria-expanded="true" aria-controls="collapse-1">
                                    Our Mission
                                </button>
                            </div>
                            <!-- Body -->
                            <div id="collapse-1" class="accordion-collapse collapse show" aria-labelledby="heading-1" data-bs-parent="#accordionFaq">
                                <div class="accordion-body mt-3 pb-0">
                                    Our mission is to empower our clients to achieve financial success by providing tailored investment strategies, transparent advice, and innovative tools that maximize returns and minimize risks.
                                </div>
                            </div>
                        </div>

                        <!-- Item -->
                        <div class="accordion-item mb-3">
                            <div class="accordion-header font-base" id="heading-2">
                                <button class="accordion-button fw-semibold rounded collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-2" aria-expanded="false" aria-controls="collapse-2">
                                    Our Vision
                                </button>
                            </div>
                            <!-- Body -->
                            <div id="collapse-2" class="accordion-collapse collapse" aria-labelledby="heading-2" data-bs-parent="#accordionFaq">
                                <div class="accordion-body mt-3 pb-0">
                                    We envision a future where every individual and business has access to the tools, knowledge, and expertise needed to make informed investment decisions and achieve long-term financial growth.
                                </div>
                            </div>
                        </div>

                        <!-- Item -->
                        <div class="accordion-item mb-3">
                            <div class="accordion-header font-base" id="heading-3">
                                <button class="accordion-button fw-semibold collapsed rounded" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-3" aria-expanded="false" aria-controls="collapse-3">
                                    Our Values
                                </button>
                            </div>
                            <!-- Body -->
                            <div id="collapse-3" class="accordion-collapse collapse" aria-labelledby="heading-3" data-bs-parent="#accordionFaq">
                                <div class="accordion-body mt-3 pb-0">
                                    We are guided by our core values of integrity, transparency, innovation, and client-centricity. These principles ensure that we deliver trustworthy, effective, and personalized investment solutions to our clients.
                                </div>
                            </div>
                        </div>

                        <!-- Item -->
                        <div class="accordion-item mb-3">
                            <div class="accordion-header font-base" id="heading-4">
                                <button class="accordion-button fw-semibold collapsed rounded" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-4" aria-expanded="false" aria-controls="collapse-4">
                                    Our Approach
                                </button>
                            </div>
                            <!-- Body -->
                            <div id="collapse-4" class="accordion-collapse collapse" aria-labelledby="heading-4" data-bs-parent="#accordionFaq">
                                <div class="accordion-body mt-3 pb-0">
                                    We take a personalized approach to investment management, working closely with each client to understand their financial goals, risk tolerance, and unique circumstances. Our tailored strategies are designed to deliver sustainable growth and long-term success.
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Accordion END -->
                </div>
            </div> 
            <!-- Row END -->
        </div>
    </section>

    <!-- =======================
    About START -->
    <section class="pt-0">
        <div class="container">
            <div class="row">
                <div class="col-lg-11 mx-auto">
                    <div class="row g-5 g-lg-7 align-items-center">
                        <!-- Years content -->
                        <div class="col-md-6 col-lg-4 text-center">
                            <div class="icon-xxl bg-primary rounded-circle mx-auto mb-n7" style="width: 170px; height: 170px;"></div>
                            <span class="heading-color fw-bold" style="font-size: 7rem; line-height: 1.2;">7</span>
                            <h6 class="w-75 mx-auto">Years of Wealth Creation Excellence</h6>
                            <p class="text-muted mt-3 w-75 mx-auto">Since our founding in 2018, <?=e($this->siteSettings('sitename'))?> has been a trusted partner, guiding clients through complex markets with strategic insight, disciplined execution, and a steadfast commitment to their financial aspirations.</p>
                        </div>
                        
                        <!-- Title content -->
                        <div class="col-md-6 col-lg-4">
                            <h6 class="text-body fw-normal">Established in 2018</h6>
                            <h3>Premier Investment & Wealth Management</h3>
                            <p class="text-muted mt-3">Based in the heart of the UK’s financial landscape, <?=e($this->siteSettings('sitename'))?> stands out for its innovative, data-driven approach. Our team combines global market expertise with personalized solutions, delivering portfolios that balance growth, stability, and long-term value for our clients.</p>
                        </div>
                        
                        <!-- Info content -->
                        <div class="col-lg-4">
                            <p class="mb-4">At <?=e($this->siteSettings('sitename'))?>, we empower our clients to achieve financial freedom through tailored wealth management. Our seasoned advisors leverage advanced analytics, cutting-edge technology, and deep market knowledge to craft diversified portfolios that withstand volatility and capitalize on opportunities. We prioritize transparency, rigorous risk management, and a client-first philosophy, ensuring your investments align with your unique goals—whether building generational wealth, securing retirement, or funding future aspirations. With a proven track record and 24/7 support, we’re your partner for sustainable financial success.</p>
                            <a href="<?=$this->siteUrl()?>/about" class="btn btn-dark mb-0">Explore our investment philosophy</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- =======================
    About END -->

    <!-- =======================
    Services START -->
    <section class="bg-light overflow-hidden pt-0">
        <div class="container">
            <!-- Title and content -->
            <div class="row mb-4 mb-md-6">
                <div class="col-md-6 col-lg-5">
                    <h2 class="mb-0">Empowering your wealth creation</h2>
                </div>

                <div class="col-md-6 col-lg-4 ms-auto">
                    <p>Our expert advisors craft personalized investment solutions to accelerate your financial goals in today’s dynamic markets.</p>

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

                    <!-- Service item -->
                    <div class="swiper-slide">
                        <div class="card card-img-scale bg-body overflow-hidden">
                            <!-- Image -->
                            <div class="card-img-scale-wrapper">
                                <img src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/home/images/services/4by3/01.png" class="card-img-top img-scale" alt="service image">
                            </div>
                            <!-- Card body -->
                            <div class="card-body p-4">
                                <h6><a href="<?=$this->siteUrl()?>/services">Portfolio Management</a></h6>
                                <p class="mb-0">Tailored strategies to optimize returns while managing risk effectively.</p>
                            </div>
                            <!-- Card footer -->
                            <div class="card-footer border-top bg-body p-4">
                                <a class="icon-link icon-link-hover stretched-link p-0 m-0" href="<?=$this->siteUrl()?>/services">Explore this service<i class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>

                    <!-- Service item -->
                    <div class="swiper-slide">
                        <div class="card card-img-scale bg-body overflow-hidden">
                            <!-- Image -->
                            <div class="card-img-scale-wrapper">
                                <img src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/home/images/services/4by3/02.png" class="card-img-top img-scale" alt="service image">
                            </div>
                            <!-- Card body -->
                            <div class="card-body p-4">
                                <h6><a href="<?=$this->siteUrl()?>/services">Wealth Planning</a></h6>
                                <p class="mb-0">Comprehensive plans to secure your financial future and legacy.</p>
                            </div>
                            <!-- Card footer -->
                            <div class="card-footer border-top bg-body p-4">
                                <a class="icon-link icon-link-hover stretched-link p-0 m-0" href="<?=$this->siteUrl()?>/services">Explore this service<i class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>

                    <!-- Service item -->
                    <div class="swiper-slide">
                        <div class="card card-img-scale bg-body overflow-hidden">
                            <!-- Image -->
                            <div class="card-img-scale-wrapper">
                                <img src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/home/images/services/4by3/03.png" class="card-img-top img-scale" alt="service image">
                            </div>
                            <!-- Card body -->
                            <div class="card-body p-4">
                                <h6><a href="<?=$this->siteUrl()?>/services">Alternative Investments</a></h6>
                                <p class="mb-0">Access exclusive opportunities in private equity and real assets.</p>
                            </div>
                            <!-- Card footer -->
                            <div class="card-footer border-top bg-body p-4">
                                <a class="icon-link icon-link-hover stretched-link p-0 m-0" href="<?=$this->siteUrl()?>/services">Explore this service<i class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>

                    <!-- Service item -->
                    <div class="swiper-slide">
                        <div class="card card-img-scale bg-body overflow-hidden">
                            <!-- Image -->
                            <div class="card-img-scale-wrapper">
                                <img src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/home/images/services/4by3/04.png" class="card-img-top img-scale" alt="service image">
                            </div>
                            <!-- Card body -->
                            <div class="card-body p-4">
                                <h6><a href="<?=$this->siteUrl()?>/services">Risk Management</a></h6>
                                <p class="mb-0">Protect your wealth with advanced risk mitigation strategies.</p>
                            </div>
                            <!-- Card footer -->
                            <div class="card-footer border-top bg-body p-4">
                                <a class="icon-link icon-link-hover stretched-link p-0 m-0" href="<?=$this->siteUrl()?>/services">Explore this service<i class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>

                    <!-- Service item -->
                    <div class="swiper-slide">
                        <div class="card card-img-scale bg-body overflow-hidden">
                            <!-- Image -->
                            <div class="card-img-scale-wrapper">
                                <img src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/home/images/services/4by3/05.png" class="card-img-top img-scale" alt="service image">
                            </div>
                            <!-- Card body -->
                            <div class="card-body p-4">
                                <h6><a href="<?=$this->siteUrl()?>/services">Retirement Planning</a></h6>
                                <p class="mb-0">Build a secure future with customized retirement strategies.</p>
                            </div>
                            <!-- Card footer -->
                            <div class="card-footer border-top bg-body p-4">
                                <a class="icon-link icon-link-hover stretched-link p-0 m-0" href="<?=$this->siteUrl()?>/services">Explore this service<i class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>

                    <!-- Service item -->
                    <div class="swiper-slide">
                        <div class="card card-img-scale bg-body overflow-hidden">
                            <!-- Image -->
                            <div class="card-img-scale-wrapper">
                                <img src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/home/images/services/4by3/06.png" class="card-img-top img-scale" alt="service image">
                            </div>
                            <!-- Card body -->
                            <div class="card-body p-4">
                                <h6><a href="<?=$this->siteUrl()?>/services">Tax-Efficient Investing</a></h6>
                                <p class="mb-0">Maximize returns with strategies to minimize tax liabilities.</p>
                            </div>
                            <!-- Card footer -->
                            <div class="card-footer border-top bg-body p-4">
                                <a class="icon-link icon-link-hover stretched-link p-0 m-0" href="<?=$this->siteUrl()?>/services">Explore this service<i class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
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
                <p class="fw-normal mb-0">Grow Your Wealth with Confidence <a href="<?=$this->siteUrl()?>/login" class="text-decoration-underline text-primary-hover fw-semibold">Ready to invest?</a></p>
            </div>
        </div>
    </section>
    <!-- =======================
    Services END -->

    <!-- =======================
    Why us START -->
    <section class="overflow-hidden pb-6 pt-0">
        <div class="container">
            <div class="row align-items-center">
                <!-- Image -->
                <div class="col-lg-6 text-center position-relative mb-5 mb-lg-0">
                    <!-- Image -->
                    <div class="pe-lg-6 pe-xl-8"><img src="https://cfcdn.olymptrade.com/s5/static/94af569d1d46c3346dedfe81daa82d9f/e6609/hand.webp" class="rounded" alt=""></div>
                </div>

                <!-- Content -->
                <div class="col-lg-6 ms-auto">
                    <!-- Title and content -->
                    <h2 class="mb-lg-4">Invest with Confidence</h2>
                    <p class="mb-lg-4">Our dedication to maximizing returns, leveraging market expertise, and prioritizing investor trust.</p>
                    <a href="<?=$this->siteUrl()?>/register" class="btn btn-dark mb-0">Explore our opportunities</a>
                    <hr class="my-4 my-lg-5"> <!-- Divider -->

                    <!-- List -->
                    <div class="row">
                        <div class="col-sm-6">
                            <ul class="list-group list-group-borderless">
                                <li class="list-group-item heading-color fw-normal d-flex mb-0"><i class="bi bi-patch-check text-primary me-2"></i>Customized Strategies</li>
                                <li class="list-group-item heading-color fw-normal d-flex mb-0"><i class="bi bi-patch-check text-primary me-2"></i>Investor-Focused Approach</li>
                                <li class="list-group-item heading-color fw-normal d-flex mb-0"><i class="bi bi-patch-check text-primary me-2"></i>Proven Performance</li>
                                <li class="list-group-item heading-color fw-normal d-flex mb-0"><i class="bi bi-patch-check text-primary me-2"></i>Diversified Portfolios</li>
                            </ul>
                        </div>

                        <div class="col-sm-6">
                            <ul class="list-group list-group-borderless">
                                <li class="list-group-item heading-color fw-normal d-flex mb-0"><i class="bi bi-patch-check text-primary me-2"></i>Risk Management</li>
                                <li class="list-group-item heading-color fw-normal d-flex mb-0"><i class="bi bi-patch-check text-primary me-2"></i>Cost Efficiency</li>
                                <li class="list-group-item heading-color fw-normal d-flex mb-0"><i class="bi bi-patch-check text-primary me-2"></i>Long-Term Growth</li>
                                <li class="list-group-item heading-color fw-normal d-flex mb-0"><i class="bi bi-patch-check text-primary me-2"></i>24/7 Support & Insights</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- =======================
    Why us END -->

    <!-- =======================
    Feature group START -->
    <section class="pt-6 pb-6">
        <div class="container">
            <!-- Title -->
            <div class="inner-container-small text-center mb-4 mb-sm-5">
                <h2 class="mb-4">Empowering Your Investments with Advanced Tools</h2>
                <p class="mb-0">There is nothing that can stop you from achieving your financial goals, except yourself. With the right tools and strategies, you can unlock your investment potential.</p>
            </div>

            <div class="row g-4 g-lg-6">
                <!-- Feature item -->
                <div class="col-xl-4">
                    <div class="card card-body d-flex p-4 border h-100 flex-md-row flex-xl-column">
                        <!-- Content -->
                        <div class="me-md-5 me-xl-0 mb-4 mb-md-0">
                            <!-- Icon -->
                            <figure class="text-primary mb-3">
                                <svg width="50" height="50" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.74136 18.8326C9.60765 18.6411 9.54079 18.5453 9.48641 18.4446C9.36533 18.2205 9.2882 17.9753 9.25914 17.7222C9.24609 17.6085 9.24609 17.4917 9.24609 17.2582V14.1914H14.7461V17.2582C14.7461 19.1702 14.7461 20.1262 14.5249 20.5566C14.0158 21.5471 12.7956 21.931 11.8111 21.4103C11.3833 21.1841 10.836 20.4002 9.74136 18.8326Z" fill="currentColor"/>
                                    <path d="M17.5114 9.84101C19.375 6.88684 20.3067 5.40976 19.9484 4.21191C19.8359 3.83574 19.6441 3.48802 19.386 3.19214C18.5641 2.25 16.8176 2.25 13.3248 2.25H10.6405C7.12482 2.25 5.36697 2.25 4.54466 3.1974C4.28652 3.49482 4.09526 3.84421 3.98384 4.22194C3.6289 5.42518 4.57619 6.90596 6.47077 9.86751L9.23438 14.1875H14.7695L17.5114 9.84101Z" fill="currentColor" fill-opacity="0.25"/>
                                </svg>
                            </figure>   
                            <h5 class="card-title mb-3">Expertise in Investment Strategies</h5>
                            <p class="mb-3">Our platform offers a comprehensive suite of tools designed to empower your investment decisions.</p>
                            <a href="<?=$this->siteUrl()?>/plans" class="btn btn-dark mb-0">View Investment Options</a>
                        </div>
                        <!-- Image -->
                        <div class="mt-auto p-0">
                            <img src="https://cfcdn.olymptrade.com/s5/static/a085556f397c18431ffea7487cf03ce0/9320a/phone_demo_account_new.webp" alt="feature-img">
                        </div>
                    </div>
                </div>

                <!-- Feature group items -->
                <div class="col-xl-8">
                    <div class="row g-4 g-lg-6">
                        <!-- Feature item -->
                        <div class="col-12">
                            <div class="card bg-primary bg-opacity-10 overflow-hidden p-5 pb-0">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="card-body p-0">
                                            <!-- Icon -->
                                            <figure class="text-primary mb-3">
                                                <svg width="50" height="50" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M10 2.25C8.20507 2.25 6.75 3.70507 6.75 5.5V6.25H13.25V5.5C13.25 3.70507 11.7949 2.25 10 2.25Z" fill="currentColor"/>
                                                    <path d="M17.75 17.25H18.5C20.2949 17.25 21.75 15.7949 21.75 14C21.75 12.2051 20.2949 10.75 18.5 10.75H17.75V17.25Z" fill="currentColor"/>
                                                    <path d="M4.5 12.25C5.4665 12.25 6.25 13.0335 6.25 14C6.25 14.9665 5.4665 15.75 4.5 15.75H3.53571C3.03452 15.75 2.78393 15.75 2.60098 15.865C2.50557 15.9249 2.4249 16.0056 2.36496 16.101C2.25 16.2839 2.25 16.5345 2.25 17.0357C2.25 18.8734 2.25 19.7923 2.67151 20.4631C2.89131 20.8129 3.1871 21.1087 3.53691 21.3285C4.20774 21.75 5.12659 21.75 6.96429 21.75C7.46548 21.75 7.71607 21.75 7.89903 21.635C7.99443 21.5751 8.0751 21.4944 8.13505 21.399C8.25 21.2161 8.25 20.9655 8.25 20.4643L8.25 19.5C8.25 18.5335 9.0335 17.75 10 17.75C10.9665 17.75 11.75 18.5335 11.75 19.5V20.4643C11.75 20.9655 11.75 21.2161 11.865 21.399C11.9249 21.4944 12.0056 21.5751 12.101 21.635C12.2839 21.75 12.5345 21.75 13.0357 21.75H13.25C14.8846 21.75 15.7019 21.75 16.3179 21.4136C16.7806 21.161 17.161 20.7806 17.4136 20.3179C17.75 19.7019 17.75 18.8846 17.75 17.25V10.75C17.75 9.1154 17.75 8.2981 17.4136 7.68207C17.161 7.21936 16.7806 6.83904 16.3179 6.58638C15.7019 6.25 14.8846 6.25 13.25 6.25H6.75C5.1154 6.25 4.2981 6.25 3.68206 6.58638C3.21936 6.83904 2.83904 7.21936 2.58638 7.68206C2.25 8.2981 2.25 9.1154 2.25 10.75V10.9643C2.25 11.4655 2.25 11.7161 2.36496 11.899C2.4249 11.9944 2.50557 12.0751 2.60098 12.135C2.78393 12.25 3.03452 12.25 3.53572 12.25H4.5Z" fill="currentColor" fill-opacity="0.25"/>
                                                </svg>
                                            </figure>
                                            <!-- Title and content -->
                                            <h5 class="card-title mb-3">Collaborating on Investments Shouldn't Be This Hard</h5>
                                            <p class="mb-3">We provide investment solutions, enabling you to make informed decisions and achieve your financial goals.</p>
                                            <a href="<?=$this->siteUrl()?>/plans" class="btn btn-outline-primary mb-5">Explore Investment Plans</a>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <!-- Image -->
                                        <div class="me-n9 mb-n6">
                                            <img src="https://cfcdn.olymptrade.com/s5/static/f62662ea45ced95e39eec13b05fa4584/b38f1/section-stocks-bg_new.webp" class="card-img" alt="feature-img">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Feature item -->
                        <div class="col-lg-6">
                            <div class="card bg-dark rounded h-100 overflow-hidden p-5">
                                <!-- SVG decoration -->
                                <figure class="position-absolute bottom-0 end-0 mb-n4 me-n5">
                                    <svg class="fill-white opacity-1" width="220px" height="209px" viewBox="0 0 220 209" style="enable-background:new 0 0 220 209;" xml:space="preserve">
                                        <path d="M84.3,120.6c-1.1-0.3-1.9-0.8-2.4-1.6c-1-1.4-0.7-3.3,0.8-5.2l15.9-21.1c2.2-2.9,6.5-5.3,10.6-5.8 c2.3-0.3,4.2,0,5.4,1l14.9,11.6c1,0.8,1.4,1.9,1.2,3.2c-0.6,3.1-4.6,6.5-9.3,8l-30.9,9.5C88.2,121,85.9,121.1,84.3,120.6z  M112.7,87.7c-1-0.3-2.1-0.3-3.4-0.1c-3.9,0.5-8.1,2.8-10.1,5.6l-15.9,21.1c-1.3,1.7-1.6,3.3-0.8,4.5c1.2,1.7,4.3,2,8,0.9 l30.8-9.5c5.2-1.6,8.4-5,8.9-7.5c0.2-1.1-0.1-2-1-2.6l-14.9-11.6C113.8,88.1,113.3,87.9,112.7,87.7z"></path>
                                        <path d="M82.9,123.4c-1-0.3-1.8-0.7-2.4-1.4c-1.1-1.4-1-3.3,0.4-5.4l15.2-23.5c3.5-5.4,12.9-8.9,17-6.3l17.8,11.1 c1.2,0.8,1.8,1.9,1.6,3.3c-0.3,3.3-4.4,7.2-9.4,9l-32.9,12.4C87.5,123.6,84.9,123.9,82.9,123.4z M111.6,86.7 c-4.3-1.1-11.9,2.1-14.9,6.7l-15.2,23.5c-1.2,1.9-1.4,3.5-0.5,4.7c1.4,1.8,5.1,1.9,9,0.4l32.9-12.4c5.5-2.1,8.7-5.9,9-8.5 c0.1-1.2-0.3-2.1-1.4-2.7l-17.8-11.1C112.5,87,112.1,86.9,111.6,86.7z"></path>
                                        <path d="M81.7,126.3c-0.9-0.3-1.7-0.7-2.3-1.3c-1.3-1.3-1.3-3.3,0-5.6l14.1-25.9c3.3-6,13.2-10.4,18-8l20.8,10.3 c1.4,0.7,2.2,1.9,2.2,3.5c-0.1,3.5-4,7.8-9.3,10.2l-34.9,15.6C87,126.5,83.9,126.9,81.7,126.3z M110.3,85.8 c-4.8-1.3-13.4,2.9-16.2,8.1l-14.1,25.9c-1.1,2-1.1,3.7-0.1,4.9c1.4,1.5,5.1,2.1,10.1-0.1l34.9-15.6c5.7-2.6,8.9-6.8,8.9-9.6 c0-1.3-0.6-2.3-1.8-2.9l-20.8-10.3C111,86,110.6,85.9,110.3,85.8z"></path>
                                        <path d="M80.7,129.6c-0.9-0.2-1.6-0.6-2.2-1.2c-1.4-1.3-1.6-3.4-0.5-5.8l12.7-28.4c2.9-6.6,13.4-11.9,18.9-9.8 l24.1,9.3c1.7,0.7,2.7,1.9,2.8,3.6c0.2,3.7-3.6,8.4-9.1,11.3l-36.8,19.1C86.9,129.6,83.2,130.2,80.7,129.6z M108.8,84.8 c-5.3-1.4-14.9,3.7-17.5,9.7l-12.7,28.4c-1,2.1-0.8,4,0.4,5.1c1.7,1.6,5.9,2,11.3-0.8L127,108c5.9-3.1,9-7.8,8.8-10.7 c-0.1-1.4-0.9-2.5-2.4-3L109.3,85C109.2,84.9,109,84.9,108.8,84.8z"></path>
                                        <path d="M79.9,133c-0.8-0.2-1.5-0.5-2.1-1c-1.6-1.3-2-3.4-1.1-5.9l10.8-30.9c2.5-7.1,13.4-13.6,19.7-11.8l27.5,7.9 c2,0.6,3.2,1.9,3.4,3.7c0.5,3.8-3.2,9.1-8.8,12.4l-38.3,23C87,132.9,82.8,133.8,79.9,133z M107,84c-6-1.6-16.5,4.7-18.8,11.4 l-10.8,30.9c-0.8,2.3-0.5,4.1,0.9,5.3c2.4,2,7.6,1.3,12.5-1.7l38.3-23c6.1-3.7,8.9-8.7,8.5-11.8c-0.2-1.6-1.2-2.7-3-3.2L107.2,84 C107.1,84,107.1,84,107,84z"></path>
                                        <path d="M79.5,136.8c-0.7-0.2-1.3-0.5-1.9-0.9c-1.8-1.3-2.4-3.4-1.7-6.1l8.5-33.4c2-7.7,13.2-15.4,20.4-14l31.2,6.2 c2.3,0.5,3.8,1.8,4.2,3.8c0.8,4.1-2.6,9.6-8.4,13.6L92,133.3C87.5,136.4,82.8,137.7,79.5,136.8z M136.2,89.4 c-0.1,0-0.3-0.1-0.5-0.1l-31.2-6.2c-6.8-1.3-17.8,6.2-19.6,13.5L76.4,130c-0.6,2.4-0.1,4.3,1.5,5.4c2.9,2,8.6,0.9,13.8-2.6 l39.7-27.2c6.2-4.3,8.8-9.7,8.1-12.9C139.1,91,138,89.9,136.2,89.4z"></path>
                                        <path d="M79.3,140.7c-0.6-0.2-1.1-0.4-1.6-0.7c-2-1.2-2.9-3.4-2.4-6.2L80.9,98c1.3-8.2,12.8-17.2,20.9-16.3l35.1,4.1 c2.7,0.3,4.5,1.7,5.1,3.9c1.2,4.2-2,10.3-7.8,14.7l-40.7,31.8C88.5,140,83,141.7,79.3,140.7z M136.7,86.2l0,0.3l-35.1-4.1 c-7.6-0.9-18.9,7.9-20.2,15.7l-5.6,35.8c-0.4,2.6,0.3,4.5,2.1,5.6c3.4,2,9.6,0.5,15-3.8l40.7-31.7c5.6-4.3,8.7-10.1,7.6-14.1 c-0.5-2-2.1-3.2-4.6-3.4L136.7,86.2z"></path>
                                        <path d="M79.5,144.9c-0.4-0.1-0.9-0.3-1.3-0.5c-2.3-1.1-3.4-3.3-3.3-6.3l2.2-38.2c0.5-8.8,12.2-19.1,21.2-18.8 l39.1,1.6c3.1,0.1,5.3,1.5,6.1,3.9c1.5,4.3-1.4,10.8-7,15.9l-41.3,36.6C90,143.8,83.8,146,79.5,144.9z M137.4,83.1l0,0.3 l-39.1-1.6c-8.5-0.3-20,9.8-20.5,18.2l-2.2,38.2c-0.2,2.7,0.8,4.7,2.9,5.7c4,1.9,10.7-0.2,16.3-5.1l41.3-36.6 c5.5-4.9,8.3-11.1,6.8-15.2c-0.8-2.2-2.7-3.4-5.5-3.5L137.4,83.1z"></path>
                                        <path d="M80.2,149.2c-0.3-0.1-0.6-0.2-0.8-0.3c-2.6-1-4.1-3.2-4.2-6.3l-1.8-40.4c-0.2-4.1,2-9,6.1-13.3 c4.5-4.8,10.3-7.9,15.1-8.1l43.2-1.3c3.5-0.1,6.1,1.3,7.3,3.9c2,4.5-0.5,11.3-6.1,17l-41.4,41.8C92,147.8,85.1,150.5,80.2,149.2z  M140.6,80.4c-0.8-0.2-1.7-0.3-2.7-0.3l-43.2,1.3c-4.7,0.1-10.3,3.1-14.7,7.9c-3.9,4.2-6.1,9-5.9,12.9l1.8,40.4 c0.1,2.8,1.4,4.8,3.8,5.7c4.6,1.8,11.8-0.9,17.5-6.6l41.4-41.8c5.3-5.4,7.8-12.1,6-16.3C143.8,82.1,142.4,80.9,140.6,80.4z"></path>
                                        <path d="M81.4,153.8c-0.1,0-0.1,0-0.2,0c-2.9-0.8-4.8-3.1-5.3-6.3L69.5,105c-0.6-4.2,1.1-9.3,4.8-14 c4.4-5.7,10.6-9.6,16.1-10.1l47.5-4.8c4-0.4,7,1,8.5,3.9c2.4,4.6,0.4,11.9-5,18.1l-41,47.3C94.7,151.8,86.9,155.3,81.4,153.8z  M137.9,76.4l0,0.3l-47.5,4.8c-5.4,0.5-11.4,4.3-15.7,9.9c-3.6,4.6-5.3,9.5-4.7,13.6l6.5,42.5c0.5,3,2.1,5,4.8,5.8 c5.3,1.5,12.9-1.9,18.5-8.3l41-47.2c5.2-6,7.2-13,4.9-17.3c-1.4-2.6-4.2-3.9-7.9-3.5L137.9,76.4z"></path>
                                        <path d="M83.2,158.5c-3-0.8-5-2.9-5.9-6.1l-11.8-44.3c-1.1-4.2,0.1-9.6,3.5-14.7c4.2-6.6,10.7-11.3,16.9-12.4 l51.8-8.7c4.5-0.8,8.1,0.6,10,3.7c2.9,4.7,1.4,12.3-3.7,19.1L104,148.1c-5.6,7.4-13.9,11.7-20.1,10.5 C83.6,158.6,83.4,158.5,83.2,158.5z M142.6,73.1c-1.4-0.4-3.1-0.4-4.9-0.1l-51.8,8.7c-6,1-12.4,5.6-16.5,12.1 c-3.2,5-4.5,10.2-3.4,14.2l11.8,44.3c0.9,3.2,2.9,5.2,6.1,5.8c6,1.2,14-3,19.5-10.2l39.9-52.9c4.9-6.5,6.4-13.9,3.7-18.4 C146.1,74.7,144.5,73.6,142.6,73.1z"></path>
                                        <path d="M85.6,163.2c-2.9-0.8-5.1-2.8-6.3-5.7l-18-45.7c-1.7-4.2-0.9-9.8,2-15.4c3.9-7.3,10.7-13.1,17.5-14.7 l56.1-13.2c5-1.2,9.3,0.1,11.7,3.5c3.4,4.8,2.5,12.7-2.2,20L108.2,151c-5.3,8.1-13.8,13.3-20.8,12.6 C86.7,163.5,86.2,163.4,85.6,163.2z M137,68.8l0.1,0.3L80.9,82.3c-6.6,1.6-13.3,7.2-17,14.4c-2.8,5.4-3.5,10.8-1.9,14.9l18,45.7 c1.3,3.3,3.9,5.3,7.5,5.6c6.8,0.7,15.1-4.4,20.2-12.3l38.1-58.9c4.6-7.1,5.5-14.7,2.3-19.3c-2.3-3.2-6.2-4.4-11-3.2L137,68.8z"></path>
                                        <path d="M88.7,168.1c-2.9-0.8-5.1-2.6-6.6-5.3L57.3,116c-2.3-4.3-2.1-10.2,0.4-16.2C61,91.7,68,85,75.3,82.7 l60.3-18.2c5.6-1.7,10.5-0.5,13.5,3.1c4,4.8,3.7,13.1-0.5,20.9l-35.4,65c-4.8,8.8-13.5,14.9-21.2,14.9 C90.8,168.5,89.7,168.3,88.7,168.1z M135.8,65.1L75.5,83.3c-7.2,2.2-14,8.7-17.3,16.7c-2.4,5.8-2.6,11.5-0.4,15.6l24.9,46.8 c1.8,3.5,5.1,5.4,9.2,5.4c7.5,0,16-6,20.7-14.6l35.4-65c4.2-7.6,4.4-15.6,0.6-20.2C145.8,64.6,141.1,63.5,135.8,65.1z"></path>
                                        <path d="M92.6,172.9c-2.8-0.7-5.1-2.4-6.8-4.8l-32.6-47.4c-3-4.3-3.5-10.5-1.4-17c2.8-8.9,9.7-16.5,17.6-19.4 l64.4-23.9c6.2-2.3,11.8-1.3,15.5,2.6c4.6,4.9,5.1,13.2,1.4,21.7L118.9,156c-4.3,9.6-12.8,16.5-21.3,17.3 C95.8,173.4,94.1,173.3,92.6,172.9z M143.5,60.2c-2.8-0.8-6.1-0.5-9.5,0.7L69.6,84.9c-7.7,2.9-14.5,10.3-17.2,19 c-2,6.3-1.5,12.3,1.3,16.4l32.6,47.4c2.5,3.6,6.5,5.4,11.2,5c8.2-0.7,16.6-7.6,20.7-16.9l31.8-71.3c3.7-8.2,3.2-16.3-1.2-21 C147.4,61.9,145.6,60.8,143.5,60.2z"></path>
                                        <path d="M97.3,177.7c-2.6-0.7-5-2.1-6.8-4.3l-41.2-47.4c-3.8-4.3-5-10.8-3.4-17.8C48,98.6,54.8,90,63.1,86.4 l68.3-30.1c6.7-3,13.2-2.3,17.7,1.9c5.3,4.9,6.6,13.5,3.5,22.4l-27.1,77.6c-3.5,10.1-11.9,18.1-20.9,19.8 C102,178.3,99.5,178.3,97.3,177.7z M131.5,56.5l0.1,0.3L63.3,86.9c-8.1,3.6-14.7,12-16.8,21.3c-1.5,6.8-0.3,13.1,3.3,17.3 L90.9,173c3.3,3.8,8.1,5.3,13.5,4.3c8.8-1.7,17-9.5,20.4-19.4L152,80.3c3-8.7,1.8-17.1-3.4-21.8c-4.3-4-10.5-4.6-17-1.7 L131.5,56.5z"></path>
                                        <path d="M102.9,182.3c-2.5-0.7-4.7-1.9-6.7-3.7l-50.6-46.8c-4.7-4.3-6.7-11.2-5.7-18.8c1.4-10.1,7.8-19.5,16.5-24 L128.2,52c7.3-3.7,14.6-3.4,20,0.9c6.1,4.8,8.3,13.7,6,23.1L133,159.7c-2.7,10.7-10.6,19.4-20.1,22.2 C109.3,183,105.9,183.1,102.9,182.3z M128.4,52.2l0.1,0.2L56.6,89.6c-8.5,4.4-14.8,13.6-16.2,23.5c-1,7.4,1,14.1,5.5,18.2 l50.6,46.8c4.2,3.9,10,5.1,16.1,3.3c9.3-2.7,17-11.3,19.6-21.8l21.3-83.8c2.3-9.2,0.1-17.7-5.7-22.4c-5.2-4.2-12.3-4.5-19.3-0.8 L128.4,52.2z"></path>
                                        <path d="M109.3,186.7c-2.2-0.6-4.3-1.6-6.2-3l-61-45.4c-5.7-4.3-8.8-11.5-8.3-19.8c0.6-10.7,6.5-20.7,15.5-26.1 l75.1-44.6c7.7-4.6,16.1-4.7,22.5-0.3c7,4.7,10.3,13.5,8.7,23.6l-14.1,90c-1.7,11-9.1,20.7-18.7,24.6 C118.1,187.4,113.4,187.8,109.3,186.7z M124.4,48l0.2,0.3L49.5,92.9c-8.8,5.2-14.6,15-15.2,25.6c-0.4,8.1,2.5,15.1,8.1,19.3 l61,45.4c5.3,3.9,12.2,4.6,19,1.9c9.4-3.9,16.7-13.3,18.4-24.2l14.1-90c1.5-9.8-1.6-18.3-8.4-22.9c-6.2-4.2-14.4-4.1-21.9,0.4 L124.4,48z"></path>
                                        <path d="M116.6,190.8c-1.9-0.5-3.7-1.3-5.5-2.3l-72.2-43.1c-7-4.2-11.1-11.8-11.4-20.9c-0.3-11.1,5.1-21.8,14.2-28 l77.8-52.8c8.1-5.5,17.5-6.2,25.1-1.9c8,4.5,12.4,13.4,11.8,23.9l-5.5,95.9c-0.7,11.4-7.1,21.7-16.9,26.9 C128.2,191.5,122.2,192.2,116.6,190.8z M119.6,43.9l0.2,0.3L42,97c-8.9,6-14.2,16.6-13.9,27.5c0.3,8.9,4.3,16.3,11.1,20.4 l72.2,43.1c6.6,3.9,14.7,4,22.2,0c9.6-5.1,15.9-15.2,16.6-26.4l5.5-95.9c0.6-10.2-3.7-18.9-11.5-23.3c-7.4-4.2-16.6-3.5-24.5,1.9 L119.6,43.9z"></path>
                                        <path d="M124.9,194.4c-1.5-0.4-2.9-0.9-4.3-1.6L36.2,153c-8.4-4-13.9-12-15-22c-1.3-11.4,3.6-22.8,12.6-29.8 l79.8-61.7c8.4-6.5,18.8-7.9,27.7-3.8c9.1,4.2,14.8,13.2,15.3,24.1l4.6,101.5c0.5,11.6-5.1,22.7-14.6,29 C139.9,195,132.1,196.4,124.9,194.4z M137.2,35c-7.7-2.1-16.1-0.2-23.1,5.2l-79.8,61.7c-8.8,6.8-13.6,18-12.4,29.2 c1.1,9.8,6.4,17.6,14.6,21.5l84.4,39.8c8.2,3.8,17.4,3,25.5-2.4c9.3-6.2,14.8-17.1,14.3-28.5L156.1,60 c-0.5-10.6-6.1-19.5-14.9-23.6C139.9,35.8,138.5,35.3,137.2,35z"></path>
                                        <path d="M134,197.6c-0.8-0.2-1.6-0.5-2.5-0.8l-97.4-35.4c-9.9-3.6-17.1-12.2-19.1-23c-2.2-11.7,1.8-23.4,10.8-31.3 l81.1-71.2c8.6-7.6,19.9-9.8,30.3-6.1c10.4,3.8,17.6,12.8,19.3,24.1l16.3,106.6c1.8,11.8-2.6,23.4-11.9,30.9 C153.2,197.8,143.2,200,134,197.6z M134.5,29.6c-9.5-2.5-19.5-0.1-27.2,6.7l-81.1,71.2c-8.8,7.7-12.8,19.2-10.6,30.7 c2,10.6,9,19,18.7,22.5l97.4,35.4c9.7,3.5,20.5,1.6,28.8-5.2c9.1-7.4,13.4-18.7,11.6-30.3L155.9,54c-1.7-11.1-8.8-19.9-18.9-23.6 C136.2,30.1,135.3,29.8,134.5,29.6z"></path>
                                        <path d="M144,200.1L32.8,170.3C21,167.1,12,158.2,8.9,146.4c-3.2-11.8,0.1-24,8.7-32.6L99,32.4 c8.7-8.6,20.9-11.9,32.7-8.7c11.8,3.2,20.8,12.1,23.9,23.9l29.8,111.1c3.2,11.8-0.1,24-8.7,32.6C168,200,155.8,203.3,144,200.1z  M99.2,32.6l0.2,0.2L18,114.2c-8.5,8.5-11.7,20.5-8.6,32c3.1,11.6,11.9,20.3,23.5,23.5l111.2,29.8c11.6,3.1,23.6-0.1,32.1-8.6 c8.5-8.5,11.7-20.5,8.6-32L155,47.7c-3.1-11.6-11.9-20.4-23.5-23.5c-11.6-3.1-23.6,0.1-32.1,8.6L99.2,32.6z"></path>
                                    </svg>
                                </figure>
            
                                <!-- Card body -->
                                <div class="card-body bg-transparent p-0">
                                    <h5 class="text-white mb-0">Want to see how to apply this in investments?</h5>
                                </div>
            
                                <!-- Card footer -->
                                <div class="card-footer bg-transparent p-0 mt-6">
                                    <a href="<?=$this->siteUrl()?>/plans" class="btn btn-white mb-0">See our plans</a>
                                </div>
                            </div>
                        </div>

                        <!-- Feature item -->
                        <div class="col-lg-6">
                            <div class="card bg-light rounded h-100 overflow-hidden p-4">
                                <!-- Card body -->
                                <div class="card-body bg-transparent p-0">
                                    <!-- Icon SVG -->
                                    <figure class="text-primary">
                                        <svg width="50" height="50" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M4.95 9.83377C3.51099 9.1789 2.79149 8.85147 2.55187 8.40828C2.3112 7.96313 2.3112 7.42663 2.55187 6.98148C2.79149 6.53829 3.51099 6.21086 4.95 5.55599L10.8816 2.8566C11.37 2.63435 11.6142 2.52322 11.872 2.50115C11.9572 2.49386 12.0428 2.49386 12.128 2.50115C12.3858 2.52322 12.63 2.63435 13.1184 2.8566L19.05 5.55599C20.489 6.21086 21.2085 6.53829 21.4481 6.98148C21.6888 7.42663 21.6888 7.96313 21.4481 8.40828C21.2085 8.85147 20.489 9.1789 19.05 9.83377Z" fill="currentColor" fill-opacity="0.25"/>
                                            <path d="M4.95 14.1115C3.51099 14.7664 2.79149 15.0938 2.55187 15.537C2.3112 15.9822 2.3112 16.5187 2.55187 16.9638C2.79149 17.407 3.51099 17.7345 4.95 18.3893L10.8816 21.0887C11.37 21.311 11.6142 21.4221 11.872 21.4442C11.9572 21.4515 12.0428 21.4515 12.128 21.4442C12.3858 21.4221 12.63 21.311 13.1184 21.0887L19.05 18.3893C20.489 17.7345 21.2085 17.407 21.4481 16.9638C21.6888 16.5187 21.6888 15.9822 21.4481 15.537C21.2085 15.0938 20.489 14.7664 19.05 14.1115Z" fill="currentColor" fill-opacity="0.25"/>
                                            <path d="M4.95 18.3893C3.51099 19.0442 2.79149 19.3716 2.55187 19.8148C2.3112 20.26 2.3112 20.7965 2.55187 21.2416C2.79149 21.6848 3.51099 22.0122 4.95 22.6671L10.8816 25.3665C11.37 25.5887 11.6142 25.6999 11.872 25.7219C11.9572 25.7292 12.0428 25.7292 12.128 25.7219C12.3858 25.6999 12.63 25.5887 13.1184 25.3665L19.05 22.6671C20.489 22.0122 21.2085 21.6848 21.4481 21.2416C21.6888 20.7965 21.6888 20.26 21.4481 19.8148C21.2085 19.3716 20.489 19.0442 19.05 18.3893Z" fill="currentColor" fill-opacity="0.25"/>
                                            <path d="M6 10.2734L10.8827 12.4929C11.3707 12.7146 11.6146 12.8255 11.8722 12.8476C11.9572 12.8548 12.0428 12.8548 12.1278 12.8476C12.3854 12.8255 12.6293 12.7146 13.1173 12.4929L18 10.2734M6 14.2734L10.8827 16.4929C11.3707 16.7146 11.6146 16.8255 11.8722 16.8476C11.9572 16.8548 12.0428 16.8548 12.1278 16.8476C12.3854 16.8255 12.6293 16.7146 13.1173 16.4929L18 14.2734" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>  
                                    </figure>
                                    <!-- Title -->
                                    <h5 class="mb-3">Investment Security</h5>
                                    <p class="mb-0">Your investments are secure with us. We ensure the highest level of protection for your financial assets.</p>
                                </div>
            
                                <!-- Card footer -->
                                <div class="card-footer bg-transparent p-0 mt-6">
                                    <a href="<?=$this->siteUrl()?>/about" class="btn btn-primary">Learn more</a>
                                </div>
                            </div>
                        </div>
                    </div> <!-- Row END -->
                </div>
            </div> 
            <!-- Row END -->
        </div>  
    </section>
    <!-- =======================
    Feature group END -->

    <!-- ======================= 
    Why Choose Us START -->
    <section class="bg-dark position-relative overflow-hidden" data-bs-theme="dark">
        <!-- SVG decoration -->
        <figure class="position-absolute top-0 end-0 me-n9 mt-n7 d-none d-md-block">
            <svg class="fill-white" width="768.8px" height="1386px" viewBox="0 0 768.8 1386" style="enable-background: new 0 0 768.8 1386; opacity: 0.07;" xml:space="preserve">
                <path
                    d="M647.6,748.4c1.9,6,3.3,12.2,3.8,18.4c2.2,18.9-0.7,38.9-9.1,61.5c-15.6,41.9-47.8,85.3-81.6,131.5 c-46.1,63.1-94.5,128.4-108.1,199.1c-15.7,80.6,17.2,154.5,101.1,226.1l-0.4,0.4c-188.1-160.7-84.4-301.8,7.3-426.2 c33.9-46,65.8-89.6,81.4-131.2c17.5-46.8,11.8-84.9-18-119.6c-39.6-46.6-86.5-86.9-135.7-129.3C339.1,450.3,184.9,317.3,240.6,4.6 l0.6,0.1C185.7,317,339.7,450.1,488.7,578.7c49.3,42.7,95.8,82.8,135.8,129.6C635.5,721,643.1,734.2,647.6,748.4z"
                />
                <path
                    d="M636.2,722.3c23.4,73.7-25.2,149.2-76.5,228.7c-40.8,63.5-83,129.1-91.9,198.3c-9.9,78.2,25.9,150,109.6,219 l-0.4,0.7c-45-37.1-75.4-74-93.5-112.7c-15.9-34.5-21.2-69.5-16.5-106.8c8.9-69.5,51-135.1,91.9-198.6 C616.4,861.3,671,776.5,622,694.1c-39.6-66.6-102.4-118.8-169-174.2c-68.7-57.3-140-116.4-188.1-195.3 c-25.9-42.6-42.9-86.9-51.5-135.2c-9.8-54.6-9.2-112.9,2-177.9l0.6,0.1c-46.5,271.4,97.9,391.7,237.5,507.9 c66.7,55.4,129.4,107.7,169.1,174.5C628.8,702.7,633.1,712.6,636.2,722.3z"
                />
                <path
                    d="M624.6,687.5c11.7,37,10.5,76.9-3.8,121.9c-13.7,43.3-38.1,87.4-61.8,130.2c-35.7,64.2-72.5,130.4-76.3,198.6 c-4.4,76.5,34.5,146.1,118.7,213.3l-0.6,0.8c-188.5-150.2-114.3-284-42.6-413.2c49.3-88.7,95.7-172.7,62.4-260.6 c-31.5-83-108.5-141.7-189.7-204C363,422.5,293.2,369,244.9,298c-26.1-38.5-43.3-78.4-53-121.8C181.2,127,180.6,75,190.2,16.8l0.9,0 C151,259.3,293.5,368.3,431.2,473.7c81.5,62.2,158.3,121.3,190.1,204.2C622.6,681.2,623.6,684.4,624.6,687.5z"
                />
                <path
                    d="M614.6,642.5c2.2,6.8,4.1,13.7,5.4,20.8c18.1,91.8-22.3,177.4-61.1,260.2c-32.2,68.8-62.8,133.6-62.1,201.3 c0.8,76.3,41.6,143,128.7,209.2l-0.6,0.8c-46.1-35.1-78.8-70-100.3-106.8c-19.1-32.9-28.6-66.6-28.9-103.1 c-0.6-67.6,29.8-132.7,62.3-201.6c38.8-82.7,79-168.2,60.9-259.8c-19.1-96.7-109.2-158.6-204.5-224.2 c-68.3-47.1-139.2-95.6-188.9-160.4c-56.1-73.2-75.8-157-60.3-255.8l0.9,0c-34.9,220.7,109.1,319.7,248.6,415.2 C503.4,499.3,587.6,557.2,614.6,642.5z"
                />
                <path
                    d="M610.8,603.9c4.4,13.9,7.1,28.7,7.8,44.6c3.6,91.5-28.4,172.7-59.3,251.2c-28.5,72.3-55.1,140.5-50.2,208.8 c5.2,76.6,49.9,143,140.1,208.8l-0.4,0.7c-90.5-66-135.2-132.4-140.8-209.2c-5-68.6,21.9-136.9,50.3-209.4 c30.9-78.5,62.6-159.6,59.1-250.9c-4.2-109-107.3-171.8-216.8-238.5c-70.1-42.7-142.8-87-194.2-146.1 c-58.1-66.8-79.9-143.6-66-234.6l1,0.3c-30.7,202.9,117.1,292.9,259.8,379.6C494.9,466.2,584.1,520.8,610.8,603.9z"
                />
                <path
                    d="M610.7,570.7c6.1,19.2,8.4,40.1,6,62.8c-8.3,84.1-33.8,160.3-55.9,227.3c-27.6,83.2-51.4,154.9-42.7,226 c9.5,78.4,58.7,146,155,213l-0.6,0.8c-96.5-66.7-145.8-134.6-155.4-213.2c-8.8-71.4,15.2-143.4,42.8-226.7 c22.3-67.1,47.6-143.3,55.9-227.3c12-120.7-104.2-182.9-227.6-248.9c-72.3-38.6-147.1-78.7-200.6-132.7 c-60.6-61-84.2-131.8-72-216.2l1.2,0.2C89.9,223.5,242,305,389.1,383.7C488.9,437.2,584.6,488.6,610.7,570.7z"
                />
                <path
                    d="M613,540.8c7.3,23.1,8.5,48.7,1.9,77.9c-14,63.3-31.8,122.1-47.4,173.8c-30,99.3-56,185.2-45,263.4 c11.7,84.3,67.2,156.6,174.2,227.2l-0.5,1c-56.6-37.2-98.1-74.3-126.8-113.1c-26.6-35.7-42.3-73.2-47.9-114.7 c-11.1-78.8,14.9-164.4,45.1-264c15.7-51.7,33.4-110.5,47.5-173.6C643.5,486.7,513.4,426,375.8,361.9 c-74.9-34.9-152.2-71-207.6-120.1c-63-55.7-88.3-121-78-199.6l1.1-0.1c-10.5,78.1,14.8,143.1,77.7,198.6 c55.4,49.1,132.7,85.3,207.5,119.9C484,410.7,587.1,458.9,613,540.8z"
                />
                <path
                    d="M617.2,512.9c8.3,26.2,8,56.1-3.5,90.9c-5.6,17.1-11.2,33.9-16.5,50c-47.8,143.4-85.7,256.4-76.5,351.9 c9.7,99.7,71.5,180,200.4,260.4l-0.5,1c-67.3-41.9-115.6-83.2-148.1-126.6c-30.9-41-48.1-85.2-52.8-134.8 c-9.3-95.8,28.8-208.9,76.6-352.3c5.6-16.2,10.9-32.9,16.5-50c47.5-143.5-96.3-201.6-248.6-263.1c-77.7-31.1-157.6-63.5-215.4-108.2 C83.3,181.7,56.1,121.4,65.2,48l1.2,0.2c-9,72.7,18.1,132.8,83.1,183c57.8,44.6,137.6,76.8,214.8,108.1 C480.2,386,591.2,431,617.2,512.9z"
                />
                <path
                    d="M622.4,486.7c9.6,30.2,6.1,63.9-10.3,102.3C465,933,463,1084.4,745.1,1249l-0.8,1.1 c-143.5-83.6-211.5-162.5-227.9-263.5c-17.4-106.8,25.3-236.4,94.5-398.1c18.6-43.4,20.4-80.4,6-113.1 C583.7,401.1,471.1,362,351.5,320.8C189.7,265,22.7,207.3,40,54.3l1.2,0.2C23.8,206.4,190.5,263.8,351.7,319.6 C471.4,361,584.6,400,617.6,475C619.7,478.9,621.2,482.8,622.4,486.7z"
                />
                <path
                    d="M628.6,461.4c10.3,32.5,4.4,69.6-18,112.8c-93.9,182.2-122.9,308.3-94.5,408.8c26.1,92.5,101.7,167,252.8,249 l-0.8,1.1c-151.5-82.5-227.2-157.2-253.6-249.9c-28.5-100.7,0.5-227.1,94.5-409.9c24.3-47.3,29-87.1,14.3-121.5 c-32.7-76-155.1-112-284.6-149.7C172.6,253.7,0.1,203.5,15.1,60.4l1.2,0.2c-14.6,141.8,157,191.9,323.2,240.6 c129.9,37.9,252.4,73.9,285.3,150.4C626.2,454.9,627.5,458,628.6,461.4z"
                />
            </svg>
        </figure>

        <div class="container position-relative">
            <!-- Title -->
            <div class="inner-container-small text-center">
                <span class="bg-light heading-color small rounded-3 px-3 py-2">Innovative solutions, Measurable results</span>
                <h2 class="mb-0 mt-4">Why Choose Us for Your Investments</h2>
            </div>

            <!-- Reasons list START -->
            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-4 gy-5 gy-md-7 mt-3">
                <!-- Reason item -->
                <div class="col">
                    <div class="card bg-light h-100">
                        <div class="card-body pb-0">
                            <!-- Icon -->
                            <div class="icon-lg bg-white text-primary rounded-circle mb-4 mt-n5">
                                <i class="bi bi-clock-history fa-fw fs-5"></i>
                            </div>
                            <!-- Content -->
                            <h5 class="mb-3">24/7 Customer Support</h5>
                            <p>Our dedicated support team is available around the clock to assist you with any queries or issues, ensuring you have a seamless investment experience.</p>
                        </div>
                    </div>
                </div>

                <!-- Reason item -->
                <div class="col">
                    <div class="card bg-light h-100">
                        <div class="card-body pb-0">
                            <!-- Icon -->
                            <div class="icon-lg bg-white text-primary rounded-circle mb-4 mt-n5">
                                <i class="bi bi-arrow-up-right-square fa-fw fs-5"></i>
                            </div>
                            <!-- Content -->
                            <h5 class="mb-3">Fast Withdrawals</h5>
                            <p>Experience quick and hassle-free withdrawals with our streamlined process, ensuring your funds are readily accessible when you need them.</p>
                        </div>
                    </div>
                </div>

                <!-- Reason item -->
                <div class="col">
                    <div class="card bg-light h-100">
                        <div class="card-body pb-0">
                            <!-- Icon -->
                            <div class="icon-lg bg-white text-primary rounded-circle mb-4 mt-n5">
                                <i class="bi bi-shield-lock fa-fw fs-5"></i>
                            </div>
                            <!-- Content -->
                            <h5 class="mb-3">Top-Notch Security</h5>
                            <p>We prioritize your safety with state-of-the-art security measures, ensuring your investments and personal information are always protected.</p>
                        </div>
                    </div>
                </div>

                <!-- Reason item -->
                <div class="col">
                    <div class="card bg-light h-100">
                        <div class="card-body pb-0">
                            <!-- Icon -->
                            <div class="icon-lg bg-white text-primary rounded-circle mb-4 mt-n5">
                                <i class="bi bi-speedometer2 fa-fw fs-5"></i>
                            </div>
                            <!-- Content -->
                            <h5 class="mb-3">Competitive Returns</h5>
                            <p>Benefit from our expertly crafted investment strategies designed to deliver competitive returns, helping you grow your wealth effectively.</p>
                        </div>
                    </div>
                </div>

                <!-- Reason item -->
                <div class="col">
                    <div class="card bg-light h-100">
                        <div class="card-body pb-0">
                            <!-- Icon -->
                            <div class="icon-lg bg-white text-primary rounded-circle mb-4 mt-n5">
                                <i class="bi bi-graph-up-arrow fa-fw fs-5"></i>
                            </div>
                            <!-- Content -->
                            <h5 class="mb-3">Expert Insights</h5>
                            <p>Gain access to expert market insights and analysis, empowering you to make informed decisions and maximize your investment potential.</p>
                        </div>
                    </div>
                </div>

                <!-- Reason item -->
                <div class="col">
                    <div class="card bg-light h-100">
                        <div class="card-body pb-0">
                            <!-- Icon -->
                            <div class="icon-lg bg-white text-primary rounded-circle mb-4 mt-n5">
                                <i class="bi bi-person-check fa-fw fs-5"></i>
                            </div>
                            <!-- Content -->
                            <h5 class="mb-3">Personalized Support</h5>
                            <p>Enjoy personalized support from our experienced advisors who are committed to understanding your unique investment goals and needs.</p>
                        </div>
                    </div>
                </div>

                <!-- Reason item -->
                <div class="col">
                    <div class="card bg-light h-100">
                        <div class="card-body pb-0">
                            <!-- Icon -->
                            <div class="icon-lg bg-white text-primary rounded-circle mb-4 mt-n5">
                                <i class="bi bi-bar-chart-line fa-fw fs-5"></i>
                            </div>
                            <!-- Content -->
                            <h5 class="mb-3">Advanced Analytics</h5>
                            <p>Leverage our advanced analytics tools to track performance, identify trends, and optimize your investment strategy for maximum growth.</p>
                        </div>
                    </div>
                </div>

                <!-- Reason item -->
                <div class="col">
                    <div class="card bg-light h-100">
                        <div class="card-body pb-0">
                            <!-- Icon -->
                            <div class="icon-lg bg-white text-primary rounded-circle mb-4 mt-n5">
                                <i class="bi bi-currency-exchange fa-fw fs-5"></i>
                            </div>
                            <!-- Content -->
                            <h5 class="mb-3">Diverse Investment Opportunities</h5>
                            <p>Explore a wide range of investment opportunities across various sectors, allowing you to diversify your portfolio and mitigate risks effectively.</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Reasons list END -->
        </div>
    </section>
    <!-- ======================= 
    Why Choose Us END -->

    <!-- =======================
    Services list and sidebar START -->
    <section class="pt-6">
        <div class="container">
            <!-- Title and content -->
            <div class="row mb-4 mb-md-6">
                <div class="col-md-6 col-lg-5">
                    <h2 class="mb-0">Explore Our Services</h2>
                </div>

                <div class="col-md-6 col-lg-4 ms-auto">
                    <p>Explore our diverse range of investment opportunities, showcasing our expertise, innovation, and commitment to delivering exceptional results.</p>
                </div>
            </div>

            <!-- Services grid -->
            <div class="row g-4 g-sm-5 g-xl-7 mt-0">
                <?php 
                $counter = 0; // Initialize a counter
                $bgDarkAdded = false; // Flag to track if bg-dark div has been added
                $bgPrimaryAdded = false; // Flag to track if bg-primary div has been added
                foreach ($data['services'] as $service): 
                    $counter++; // Increment the counter
                    if (!$bgDarkAdded && $counter % 3 == 0): // Add bg-dark div only once
                        $bgDarkAdded = true; // Set flag to true
                ?>
                        <!-- Item with bg-dark -->
                        <div class="col-md-6 col-lg-4">
                            <article class="card bg-dark p-4 overflow-hidden h-100" data-bs-theme="dark">
                                <!-- SVG decoration -->
                                <figure class="position-absolute end-0 bottom-0 mb-n5 me-n7">
                                    <svg width="277.7px" height="258.7px" viewBox="0 0 277.7 258.7" style="enable-background:new 0 0 277.7 258.7;" xml:space="preserve">
                                        <path class="fill-light" d="M86.9,168.9l-60,60l2.3,2.3l60-60C88.4,170.4,87.6,169.6,86.9,168.9z"/>
                                        <path class="fill-light" d="M83.6,165.1l-56.7,47.6l2.1,2.5l56.7-47.6C84.9,166.7,84.2,165.9,83.6,165.1z"/>
                                        <path class="fill-light" d="M98.6,178.4l-27.8,48.1l2.8,1.6l27.8-48.1C100.4,179.5,99.5,179,98.6,178.4z"/>
                                        <path class="fill-light" d="M117.3,185.8l-7.3,41.2l3.2,0.6l7.3-41.2C119.4,186.2,118.3,186,117.3,185.8z"/>
                                        <path class="fill-light" d="M112.4,184.6l-10.7,39.9l3.1,0.8l10.7-39.9C114.5,185.2,113.4,184.9,112.4,184.6z"/>
                                        <path class="fill-light" d="M103,180.9l-20,42.9l2.9,1.4l20-42.8C104.9,181.8,104,181.3,103,180.9z"/>
                                        <path class="fill-light" d="M107.6,182.9l-14.7,40.4l3,1.1l14.7-40.4C109.6,183.7,108.6,183.3,107.6,182.9z"/>
                                        <path class="fill-light" d="M94.4,175.6l-41.7,59.5l2.6,1.8L97,177.4C96.2,176.8,95.3,176.2,94.4,175.6z"/>
                                        <path class="fill-light" d="M90.5,172.4l-57.9,69.1l2.5,2.1l58-69.1C92.1,173.8,91.3,173.1,90.5,172.4z"/>
                                        <path class="fill-light" d="M80.6,161L30,196.4l1.8,2.6l50.6-35.4C81.8,162.7,81.2,161.9,80.6,161z"/>
                                        <path class="fill-light" d="M71.4,122.4l-35.3-3.1l-0.3,3.2l35.3,3.1C71.2,124.6,71.3,123.5,71.4,122.4z"/>
                                        <path class="fill-light" d="M71.1,132.5l-29.5,2.6l0.3,3.2l29.5-2.6C71.3,134.7,71.2,133.6,71.1,132.5z"/>
                                        <path class="fill-light" d="M71,127.5H40v3.2H71c0-0.5,0-1.1,0-1.6C71,128.5,71,128,71,127.5z"/>
                                        <path class="fill-light" d="M71.6,137.5L42,142.8l0.6,3.2l29.7-5.2C72,139.7,71.8,138.6,71.6,137.5z"/>
                                        <path class="fill-light" d="M78,156.6l-44.1,25.4l1.6,2.8l44.1-25.4C79,158.5,78.5,157.6,78,156.6z"/>
                                        <path class="fill-light" d="M75.8,152.1L37.3,170l1.4,2.9l38.5-18C76.7,154,76.2,153.1,75.8,152.1z"/>
                                        <path class="fill-light" d="M72.6,142.5l-31.3,8.4l0.8,3.1l31.3-8.4C73.1,144.6,72.8,143.6,72.6,142.5z"/>
                                        <path class="fill-light" d="M74,147.4l-34.2,12.5l1.1,3l34.2-12.5C74.7,149.4,74.3,148.4,74,147.4z"/>
                                        <path class="fill-light" d="M175.4,163.6l21.4,15l1.8-2.6l-21.4-15C176.7,161.9,176.1,162.7,175.4,163.6z"/>
                                        <path class="fill-light" d="M180.7,155l29.9,13.9l1.4-2.9l-29.9-13.9C181.7,153.1,181.2,154,180.7,155z"/>
                                        <path class="fill-light" d="M178.3,159.4l23.6,13.6l1.6-2.8l-23.5-13.6C179.4,157.6,178.8,158.5,178.3,159.4z"/>
                                        <path class="fill-light" d="M172.3,167.5l23.7,19.9l2.1-2.5l-23.7-19.9C173.7,165.9,173,166.7,172.3,167.5z"/>
                                        <path class="fill-light" d="M72.2,117.4l-45.2-8l-0.6,3.2l45.2,8C71.8,119.5,72,118.5,72.2,117.4z"/>
                                        <path class="fill-light" d="M122.3,186.6l-3.9,44.9l3.2,0.3l3.9-44.9C124.4,186.8,123.3,186.7,122.3,186.6z"/>
                                        <path class="fill-light" d="M182.8,150.4l40.1,14.6l1.1-3l-40.1-14.6C183.6,148.4,183.2,149.4,182.8,150.4z"/>
                                        <path class="fill-light" d="M184.5,145.6l54.5,14.6l0.8-3.1l-54.5-14.6C185,143.6,184.8,144.6,184.5,145.6z"/>
                                        <path class="fill-light" d="M186.5,135.7l80.5,7l0.3-3.2l-80.5-7C186.7,133.6,186.6,134.7,186.5,135.7z"/>
                                        <path class="fill-light" d="M185.7,140.7l69.1,12.2l0.6-3.2l-69.1-12.2C186.1,138.6,185.9,139.7,185.7,140.7z"/>
                                        <path class="fill-light" d="M168.7,171.1l30.2,30.2l2.3-2.3L171,168.9C170.3,169.6,169.5,170.4,168.7,171.1z"/>
                                        <path class="fill-light" d="M127.3,187v52.4h3.2V187c-0.5,0-1.1,0-1.6,0C128.4,187,127.9,187,127.3,187z"/>
                                        <path class="fill-light" d="M132.4,186.9l5.7,65.3l3.2-0.3l-5.7-65.3C134.5,186.7,133.5,186.8,132.4,186.9z"/>
                                        <path class="fill-light" d="M142.4,185.4l18.1,67.4l3.1-0.8l-18.1-67.4C144.5,184.9,143.4,185.2,142.4,185.4z"/>
                                        <path class="fill-light" d="M137.4,186.4l12.7,72.3l3.2-0.6l-12.7-72.3C139.5,186,138.5,186.2,137.4,186.4z"/>
                                        <path class="fill-light" d="M164.9,174.5l30.5,36.3l2.5-2.1l-30.5-36.3C166.6,173.1,165.8,173.8,164.9,174.5z"/>
                                        <path class="fill-light" d="M160.8,177.4l24.7,35.3l2.6-1.8l-24.7-35.3C162.6,176.2,161.7,176.8,160.8,177.4z"/>
                                        <path class="fill-light" d="M156.5,180l21.9,37.9l2.8-1.6l-21.9-37.9C158.4,179,157.4,179.5,156.5,180z"/>
                                        <path class="fill-light" d="M147.2,184l21.3,58.5l3-1.1l-21.3-58.5C149.3,183.3,148.3,183.7,147.2,184z"/>
                                        <path class="fill-light" d="M152,182.2l22,47.2l2.9-1.4l-22-47.2C153.9,181.3,152.9,181.8,152,182.2z"/>
                                        <path class="fill-light" d="M171,89.3l36.1-36.1l-2.3-2.3L168.7,87C169.5,87.7,170.3,88.5,171,89.3z"/>
                                        <path class="fill-light" d="M174.3,93.1L211,62.3l-2.1-2.5l-36.7,30.8C173,91.4,173.7,92.2,174.3,93.1z"/>
                                        <path class="fill-light" d="M177.3,97.2l36.3-25.4l-1.8-2.6l-36.3,25.4C176.1,95.4,176.7,96.3,177.3,97.2z"/>
                                        <path class="fill-light" d="M179.9,101.5l37-21.4l-1.6-2.8l-37,21.4C178.8,99.6,179.4,100.6,179.9,101.5z"/>
                                        <path class="fill-light" d="M182.1,106.1l41.1-19.2l-1.4-2.9l-41.1,19.2C181.2,104.1,181.7,105.1,182.1,106.1z"/>
                                        <path class="fill-light" d="M183.9,110.8l52.5-19.1l-1.1-3l-52.5,19.1C183.2,108.7,183.6,109.8,183.9,110.8z"/>
                                        <path class="fill-light" d="M163.5,82.6l28.3-40.4l-2.6-1.8l-28.3,40.4C161.7,81.3,162.6,81.9,163.5,82.6z"/>
                                        <path class="fill-light" d="M167.4,85.8L200.8,46l-2.5-2.1l-33.4,39.8C165.8,84.4,166.6,85,167.4,85.8z"/>
                                        <path class="fill-light" d="M159.3,79.7l19.6-33.9l-2.8-1.6l-19.6,33.9C157.4,78.6,158.4,79.2,159.3,79.7z"/>
                                        <path class="fill-light" d="M186.9,129.1c0,0.5,0,1.1,0,1.6H275v-3.2h-88.1C186.8,128,186.9,128.5,186.9,129.1z"/>
                                        <path class="fill-light" d="M186.8,125.6l90.9-8l-0.3-3.2l-90.9,8C186.6,123.5,186.7,124.6,186.8,125.6z"/>
                                        <path class="fill-light" d="M185.3,115.6l77.6-20.8l-0.8-3.1l-77.6,20.8C184.8,113.6,185,114.6,185.3,115.6z"/>
                                        <path class="fill-light" d="M186.2,120.6l89.9-15.9l-0.6-3.2l-89.9,15.9C185.9,118.5,186.1,119.5,186.2,120.6z"/>
                                        <polygon class="fill-light" points="39.1,24.5 90.5,85.8 90.5,85.7   "/>
                                        <path class="fill-light" d="M93,83.7L41.5,22.4l-2.5,2.1l51.4,61.3C91.3,85,92.1,84.3,93,83.7z"/>
                                        <path class="fill-light" d="M89.1,87l-53-53l-2.3,2.3l53,53C87.6,88.5,88.4,87.7,89.1,87z"/>
                                        <path class="fill-light" d="M97,80.7L47.4,9.8l-2.6,1.8l49.7,70.9C95.3,81.9,96.1,81.3,97,80.7z"/>
                                        <path class="fill-light" d="M101.4,78.1L57,1.2l-2.8,1.6l44.4,76.9C99.5,79.2,100.4,78.6,101.4,78.1z"/>
                                        <path class="fill-light" d="M85.6,90.6L32.8,46.3l-2.1,2.5l52.9,44.4C84.2,92.2,84.9,91.4,85.6,90.6z"/>
                                        <path class="fill-light" d="M77.1,103.1L4.1,69.1L2.7,72l73.1,34.1C76.2,105.1,76.7,104.1,77.1,103.1z"/>
                                        <polygon class="fill-light" points="82.4,94.5 27.2,55.9 82.4,94.5   "/>
                                        <path class="fill-light" d="M82.4,94.5L27.2,55.9l-1.8,2.6l55.2,38.7C81.2,96.3,81.8,95.4,82.4,94.5z"/>
                                        <path class="fill-light" d="M79.6,98.7L14.1,60.9l-1.6,2.8L78,101.5C78.5,100.6,79,99.6,79.6,98.7z"/>
                                        <path class="fill-light" d="M73.4,112.5L8.2,95.1l-0.8,3.1l65.2,17.5C72.8,114.6,73.1,113.6,73.4,112.5z"/>
                                        <path class="fill-light" d="M75.1,107.8l-74-26.9l-1.1,3l74,26.9C74.3,109.8,74.7,108.7,75.1,107.8z"/>
                                        <path class="fill-light" d="M140.6,72.3l3.2-18l-3.2-0.6l-3.2,18C138.5,71.9,139.5,72.1,140.6,72.3z"/>
                                        <path class="fill-light" d="M135.6,71.5l1.7-19.7l-3.2-0.3l-1.7,19.7C133.5,71.3,134.5,71.4,135.6,71.5z"/>
                                        <path class="fill-light" d="M154.9,77.3l11.8-25.2l-2.9-1.4L152,75.9C152.9,76.3,153.9,76.8,154.9,77.3z"/>
                                        <path class="fill-light" d="M145.5,73.5l4.9-18.3l-3.1-0.8l-4.9,18.4C143.4,73,144.5,73.2,145.5,73.5z"/>
                                        <path class="fill-light" d="M150.3,75.2l7.5-20.7l-3-1.1l-7.5,20.7C148.3,74.4,149.3,74.8,150.3,75.2z"/>
                                        <path class="fill-light" d="M130.6,71.2l0-24h-3.2v24c0.5,0,1.1,0,1.6,0C129.5,71.1,130,71.2,130.6,71.2z"/>
                                        <path class="fill-light" d="M115.5,72.7l-14.2-53l-3.1,0.8l14.2,53C113.4,73.2,114.5,73,115.5,72.7z"/>
                                        <path class="fill-light" d="M105.9,75.9L70.5,0l-2.9,1.4L103,77.3C104,76.8,104.9,76.3,105.9,75.9z"/>
                                        <path class="fill-light" d="M110.6,74.1l-24.7-68l-3,1.1l24.7,68C108.6,74.8,109.6,74.4,110.6,74.1z"/>
                                        <path class="fill-light" d="M125.5,71.2l-2.7-30.9l-3.2,0.3l2.7,30.9C123.4,71.4,124.4,71.3,125.5,71.2z"/>
                                        <path class="fill-light" d="M120.5,71.8l-7-39.6l-3.2,0.6l7,39.6C118.3,72.1,119.4,71.9,120.5,71.8z"/>
                                        <path class="fill-light" d="M182.2,151.8l35.3,14.6l0.5-1.1l-35.3-14.6C182.5,151,182.3,151.4,182.2,151.8z"/>
                                        <path class="fill-light" d="M186.2,137.2l18.3,2.4l0.2-1.2l-18.3-2.4C186.3,136.4,186.3,136.8,186.2,137.2z"/>
                                        <path class="fill-light" d="M185.6,117.1l9.1-2l-0.3-1.2l-9,2C185.4,116.3,185.5,116.7,185.6,117.1z"/>
                                        <path class="fill-light" d="M180,156.4l27.4,14.3l0.5-1.1l-27.4-14.3C180.3,155.6,180.2,156,180,156.4z"/>
                                        <path class="fill-light" d="M186.4,122.1l8.8-1.1l-0.2-1.2l-8.8,1.2C186.3,121.3,186.3,121.7,186.4,122.1z"/>
                                        <path class="fill-light" d="M186.7,132.2l12.9,0.6l0-1.2l-12.9-0.6C186.8,131.4,186.8,131.8,186.7,132.2z"/>
                                        <path class="fill-light" d="M186.8,127.1l10-0.4l-0.1-1.2l-10,0.4C186.8,126.3,186.8,126.8,186.8,127.1z"/>
                                        <path class="fill-light" d="M185.3,142.2l29.3,6.5l0.2-1.2l-29.3-6.5C185.5,141.4,185.4,141.8,185.3,142.2z"/>
                                        <path class="fill-light" d="M183.9,147.1l39.9,12.6l0.4-1.1l-39.8-12.6C184.2,146.3,184.1,146.7,183.9,147.1z"/>
                                        <path class="fill-light" d="M177.4,160.7l19.1,12.2l0.6-1L178,159.7C177.8,160,177.6,160.4,177.4,160.7z"/>
                                        <path class="fill-light" d="M145.7,184.5l1.5,4.7l1.2-0.4l-1.5-4.7C146.5,184.2,146.1,184.4,145.7,184.5z"/>
                                        <path class="fill-light" d="M155.1,180.7l0.5,0.9l1.1-0.6l-0.5-0.9C155.8,180.3,155.4,180.5,155.1,180.7z"/>
                                        <path class="fill-light" d="M150.5,182.8l0.8,1.9l1.1-0.5l-0.8-1.9C151.2,182.5,150.8,182.7,150.5,182.8z"/>
                                        <path class="fill-light" d="M159.5,178.2l0.7,1.1l1-0.7l-0.7-1.1C160.2,177.8,159.8,178,159.5,178.2z"/>
                                        <path class="fill-light" d="M174.5,164.8l12.2,9.3l0.7-1l-12.2-9.3C175,164.2,174.7,164.5,174.5,164.8z"/>
                                        <path class="fill-light" d="M171.2,168.6l7.1,6.5l0.8-0.9l-7.1-6.5C171.7,168.1,171.4,168.4,171.2,168.6z"/>
                                        <path class="fill-light" d="M163.7,175.4l1.7,2.3l1-0.7l-1.7-2.3C164.3,174.9,164,175.2,163.7,175.4z"/>
                                        <path class="fill-light" d="M167.6,172.2l3.8,4.1l0.9-0.8l-3.8-4.1C168.2,171.7,167.9,171.9,167.6,172.2z"/>
                                        <path class="fill-light" d="M132,71.2l0.1-3.2l-1.2-0.1l-0.1,3.3C131.2,71.2,131.6,71.2,132,71.2z"/>
                                        <path class="fill-light" d="M146.9,74l0.6-1.8l-1.1-0.4l-0.6,1.8C146.1,73.8,146.5,73.9,146.9,74z"/>
                                        <path class="fill-light" d="M140.8,185.7l2.6,11.7l1.2-0.3l-2.6-11.7C141.6,185.6,141.2,185.7,140.8,185.7z"/>
                                        <path class="fill-light" d="M184.3,112.2l10.9-3.4l-0.4-1.2l-10.9,3.4C184.1,111.5,184.2,111.8,184.3,112.2z"/>
                                        <path class="fill-light" d="M127,71.2l-0.4-9.8l-1.2,0l0.4,9.8C126.2,71.2,126.6,71.2,127,71.2z"/>
                                        <path class="fill-light" d="M121.9,71.6l-2.5-18.9l-1.2,0.2l2.5,18.9C121.1,71.7,121.5,71.6,121.9,71.6z"/>
                                        <path class="fill-light" d="M112,73.6l-10.7-33.9l-1.1,0.4L110.9,74C111.3,73.9,111.7,73.8,112,73.6z"/>
                                        <path class="fill-light" d="M116.9,72.4l-6.1-27.7l-1.2,0.3l6.1,27.7C116.1,72.6,116.5,72.5,116.9,72.4z"/>
                                        <path class="fill-light" d="M151.6,75.8l3.8-9.1l-1.1-0.5l-3.8,9.1C150.9,75.5,151.2,75.6,151.6,75.8z"/>
                                        <path class="fill-light" d="M182.6,107.5l15.1-6.3l-0.5-1.1l-15.1,6.3C182.3,106.7,182.5,107.1,182.6,107.5z"/>
                                        <path class="fill-light" d="M180.5,102.9l22.4-11.6l-0.6-1.1L180,101.8C180.2,102.1,180.3,102.5,180.5,102.9z"/>
                                        <path class="fill-light" d="M175.2,94.3l22.3-17.1l-0.8-1l-22.3,17.1C174.7,93.6,175,94,175.2,94.3z"/>
                                        <path class="fill-light" d="M178.1,98.5l25.7-16.4l-0.6-1l-25.7,16.4C177.6,97.8,177.8,98.1,178.1,98.5z"/>
                                        <path class="fill-light" d="M156.2,78l6-11.5l-1.1-0.6l-6,11.6C155.4,77.6,155.8,77.8,156.2,78z"/>
                                        <path class="fill-light" d="M160.5,80.5l5.4-8.5l-1-0.6l-5.4,8.5C159.8,80.1,160.2,80.3,160.5,80.5z"/>
                                        <path class="fill-light" d="M164.6,83.5l6.4-8.4l-1-0.7l-6.4,8.4C164,83,164.3,83.2,164.6,83.5z"/>
                                        <path class="fill-light" d="M172,90.4l17-15.5l-0.8-0.9l-17,15.5C171.5,89.8,171.7,90.1,172,90.4z"/>
                                        <path class="fill-light" d="M168.5,86.8l10.9-11.9l-0.9-0.8l-10.9,11.9C167.9,86.2,168.2,86.5,168.5,86.8z"/>
                                        <path class="fill-light" d="M61.3,100.5l-0.5,1.1l14.3,5.9c0.1-0.4,0.3-0.7,0.5-1.1L61.3,100.5z"/>
                                        <path class="fill-light" d="M77.8,101.8L66,95.6l-0.6,1.1l11.8,6.2C77.4,102.5,77.6,102.1,77.8,101.8z"/>
                                        <path class="fill-light" d="M80.3,97.4l-9.2-5.8l-0.6,1l9.2,5.9C79.9,98.1,80.1,97.8,80.3,97.4z"/>
                                        <path class="fill-light" d="M83.3,93.3l-7.8-6l-0.7,1l7.8,6C82.8,94,83.1,93.6,83.3,93.3z"/>
                                        <path class="fill-light" d="M86.6,89.5L78,81.7l-0.8,0.9l8.5,7.8C86,90.1,86.3,89.8,86.6,89.5z"/>
                                        <path class="fill-light" d="M90.2,86L77.6,72.2L76.7,73l12.6,13.8C89.6,86.5,89.9,86.2,90.2,86z"/>
                                        <path class="fill-light" d="M72.5,116l-13.9-3.1l-0.3,1.2l13.9,3.1C72.3,116.7,72.4,116.3,72.5,116z"/>
                                        <path class="fill-light" d="M73.8,111.1l-15.3-4.8l-0.4,1.2l15.3,4.8C73.6,111.8,73.7,111.5,73.8,111.1z"/>
                                        <path class="fill-light" d="M71.5,120.9l-7.4-1l-0.2,1.2l7.4,1C71.4,121.7,71.5,121.3,71.5,120.9z"/>
                                        <path class="fill-light" d="M107.3,75.3L91.8,38l-1.1,0.4l15.5,37.3C106.5,75.6,106.9,75.5,107.3,75.3z"/>
                                        <path class="fill-light" d="M102.7,77.4L83,39.7l-1.1,0.6L101.6,78C102,77.8,102.3,77.6,102.7,77.4z"/>
                                        <path class="fill-light" d="M94.1,82.7L72.7,54.9l-1,0.7l21.4,27.9C93.5,83.2,93.8,83,94.1,82.7z"/>
                                        <path class="fill-light" d="M98.3,79.9L75.4,44l-1,0.7l22.9,35.9C97.6,80.3,97.9,80.1,98.3,79.9z"/>
                                        <path class="fill-light" d="M101.6,180.2l-16.1,31l1.1,0.6l16.1-31C102.3,180.5,102,180.3,101.6,180.2z"/>
                                        <path class="fill-light" d="M106.2,182.3l-12.1,29.1l1.1,0.5l12.1-29.1C106.9,182.6,106.5,182.5,106.2,182.3z"/>
                                        <path class="fill-light" d="M97.2,177.6l-21.2,33.2l1,0.6l21.2-33.2C97.9,178,97.6,177.8,97.2,177.6z"/>
                                        <path class="fill-light" d="M93.1,174.7l-24.9,32.4l1,0.7l24.9-32.5C93.8,175.1,93.5,174.9,93.1,174.7z"/>
                                        <path class="fill-light" d="M110.9,184.1l-8.3,26.3l1.2,0.4l8.3-26.3C111.7,184.4,111.3,184.2,110.9,184.1z"/>
                                        <path class="fill-light" d="M125.7,186.9l-1.5,35.3l1.2,0.1L127,187C126.6,186.9,126.1,186.9,125.7,186.9z"/>
                                        <path class="fill-light" d="M115.8,185.5l-5.7,25.9l1.2,0.3l5.7-25.8C116.5,185.7,116.1,185.6,115.8,185.5z"/>
                                        <path class="fill-light" d="M120.7,186.4l-4.2,31.7l1.2,0.2l4.2-31.8C121.5,186.5,121.1,186.5,120.7,186.4z"/>
                                        <path class="fill-light" d="M135.8,186.6l3.5,26.3l1.2-0.2l-3.5-26.3C136.6,186.5,136.2,186.5,135.8,186.6z"/>
                                        <path class="fill-light" d="M130.8,187l1.5,34.1l1.2-0.1l-1.5-34C131.6,186.9,131.2,187,130.8,187z"/>
                                        <path class="fill-light" d="M82.6,163.9l-14.3,11l0.7,1l14.3-11C83,164.5,82.8,164.2,82.6,163.9z"/>
                                        <path class="fill-light" d="M89.3,171.4L64,199l0.9,0.8l25.3-27.6C89.9,171.9,89.6,171.6,89.3,171.4z"/>
                                        <path class="fill-light" d="M85.8,167.8l-22,20.1l0.8,0.9l22-20.1C86.3,168.4,86,168,85.8,167.8z"/>
                                        <path class="fill-light" d="M77.2,155.3l-1.7,0.9l0.6,1.1l1.7-0.9C77.6,156,77.4,155.6,77.2,155.3z"/>
                                        <path class="fill-light" d="M79.7,159.7l-6.9,4.4l0.6,1l6.9-4.4C80.1,160.4,79.9,160,79.7,159.7z"/>
                                    </svg>
                                </figure>

                                <div class="card-body p-0">
                                    <!-- Badge -->
                                    <div class="d-flex justify-content-between mb-5">
                                        <h6 class="mb-0 fw-light">INV-05</h6>
                                        <div class="badge bg-primary text-white small">Investment</div>
                                    </div>
                                    <!-- Title -->
                                    <h5 class="card-title"><a href="<?=$this->siteUrl()?>/user/plans">Strategies to Maximize Returns in a Volatile Market</a></h5>
                                </div>

                                <!-- Author detail -->
                                <div class="card-footer bg-transparent p-0 d-flex justify-content-between mt-8">
                                    <div>
                                        <small>Investment Advisor</small>
                                        <h6>John Carter</h6>
                                    </div>
                                    <div class="avatar flex-shrink-0">
                                        <img class="avatar-img rounded-circle" src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/home/images/avatar/04.jpg" alt="avatar">
                                    </div>
                                </div>
                            </article>
                        </div>
                    <?php elseif (!$bgPrimaryAdded && $counter % 4 == 0): // Add bg-primary div only once
                        $bgPrimaryAdded = true; // Set flag to true
                    ?>
                        <!-- Item with bg-primary -->
                        <div class="col-md-6 col-lg-4">
                            <article class="card bg-primary p-4 overflow-hidden h-100">
                                <!-- pattern decoration -->
                                <div class="position-absolute end-0 bottom-0 mb-n5 me-n7">
                                    <img src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/home/images/elements/pattern.svg" class="opacity-4" alt="">
                                </div>

                                <!-- Card body -->
                                <div class="card-body z-index-1 p-0">
                                    <div class="d-flex justify-content-between mb-5">
                                        <div class="badge bg-dark text-white small">Investment Insights</div>
                                        <a href="<?=$this->siteUrl()?>/services" class="mb-0 text-white small" tabindex="0" role="button" data-bs-container="body" data-bs-toggle="popover" data-bs-trigger="focus" data-bs-placement="top" data-bs-content="You're seeing this ad because your activity meets the intended audience of our site.">
                                            <i class="bi bi-info-circle ps-1"></i> Sponsored
                                        </a>
                                    </div>
                                    <!-- Title -->
                                    <h5 class="card-title">Looking to grow your wealth? Discover proven investment strategies to secure your financial future.</h5>
                                </div>

                                <!-- Card footer -->
                                <div class="card-footer bg-transparent d-flex justify-content-between align-items-center p-0 mt-8 z-index-1">
                                    <!-- Avatar info -->
                                    <div class="d-flex align-items-center">
                                        <div class="avatar flex-shrink-0 me-2">
                                            <div class="avatar-img rounded-circle bg-dark">
                                                <span class="text-white position-absolute top-50 start-50 translate-middle fw-bold small">INV</span>
                                            </div>
                                        </div>
                                        <span class="heading-color fw-semibold">Investment Advisor</span>
                                    </div>
                                    <!-- Button -->
                                    <a href="<?=$this->siteUrl()?>/services" class="btn btn-sm btn-white mb-0">Learn More</a>
                                </div>
                            </article>
                        </div>
                    <?php else: // Regular 1-2 columns ?>
                        <!-- Regular Item -->
                        <div class="col-md-6 col-lg-4">
                            <article class="card bg-transparent h-100 p-0">
                                <!-- Badge -->
                                <div class="badge text-bg-dark position-absolute top-0 start-0 m-3"><?=e($service['tags'])?></div>

                                <!-- Card image -->
                                <img src="<?=$this->siteUrl().'/'.PUBLIC_PATH.'/'.UPLOADS_PATH?>/services/<?=e($service['image'])?>" class="card-img" alt="Blog-img">

                                <!-- Card Body -->
                                <div class="card-body px-2 pb-4">
                                    <!-- Title -->
                                    <h6 class="card-title mb-2"><a href="<?=$this->siteUrl()?>/services?details=<?=e($service['serviceId'])?>"><?=e($service['title'])?></a></h6>
                                    <p class="small mb-0"><?=e($service['short'])?></p>
                                </div>
                                
                                <!-- Card footer -->
                                <div class="card-footer bg-transparent d-flex justify-content-between px-2 py-0">
                                    <span class="heading-color fw-semibold mb-0"><?=e($service['tags'])?></span>
                                    <a class="icon-link icon-link-hover stretched-link" href="<?=$this->siteUrl()?>/services?details=<?=e($service['serviceId'])?>">Read more<i class="bi bi-arrow-right"></i> </a>
                                </div>
                            </article>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <!-- =======================
    Services list and sidebar END -->

    <section class="bg-dark text-white pt-6 pb-6">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-12 mx-auto">
                    <p>
                        At <?=e($this->siteSettings('sitename'))?>, we provide a wide range of professionally managed investment opportunities across the world’s most profitable markets—including crypto, forex, stocks, and commodities. Each plan is carefully structured to deliver attractive daily returns over a short 5-day investment cycle, with zero stress or effort required from you.
                    </p>
                    <p>
                        Unlike traditional trading, where investors often face the pressure of watching charts, managing risks, and potentially losing capital due to market volatility, <?=e($this->siteSettings('sitename'))?> offers a smarter, safer, and more consistent alternative. Our expert traders and investment managers handle every aspect of the market activity for you—so you can sit back and watch your money grow.
                    </p>
                    <p class="fw-bold">
                        Simple. Secure. Profitable.
                    </p>
                    <p>
                        Think of it like owning shares: you invest in a project, and we do the work. Each day, your profit is calculated and added to your balance. Once your 5-day investment cycle ends, you can easily withdraw both your capital and the total profits directly to your wallet.
                    </p>
                    <p>
                        We’ve also opened up access to the commodities market—including sectors like agriculture, crude oil, electricity, and real estate—without the usual high entry costs. Now, with an affordable amount, you can be part of these high-value markets and earn daily profit returns, guided by our expert financial teams.
                    </p>
                    <p>
                        The funds you invest with us are strategically deployed into real, profit-generating projects. These ventures create sustainable income for both you as the investor and <?=e($this->siteSettings('sitename'))?> as a company.
                    </p>
                    <hr class="my-4">
                    <h2 class="mb-3 text-white">Why Choose <?=e($this->siteSettings('sitename'))?>?</h2>
                    <ul class="list-unstyled">
                        <li class="mb-2">✔️ Guaranteed Daily Returns</li>
                        <li class="mb-2">✔️ Zero Trading Experience Needed</li>
                        <li class="mb-2">✔️ Fully Managed by Professionals</li>
                        <li class="mb-2">✔️ Hassle-Free Withdrawals</li>
                        <li class="mb-2">✔️ Diversified Investment Options</li>
                        <li class="mb-2">✔️ Access to Global Markets with Low Capital</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- =======================
    Pricing START -->
    <section class="pt-6">
        <div class="container">
            <!-- Title -->
            <div class="inner-container-small text-center mb-4 mb-sm-5">
                <h2 class="mb-4">The Best Investment Packages For You</h2>
                <p class="mb-0">Your success deserves a pricing strategy that aligns with your goals. We offer a range of pricing plans crafted to meet your unique investment needs.</p>
            </div>

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


            <!-- TradingView Widget BEGIN -->
            <div class="tradingview-widget-container p-0 mt-45">
                <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-mini-symbol-overview.js" async>
                    {
                        "symbol": "COINBASE:BTCUSD",
                        "width": "100%",
                        "height": "350",
                        "locale": "en",
                        "dateRange": "12M",
                        "colorTheme": "light",
                        "trendLineColor": "rgba(9, 184, 80, 1)",
                        "underLineColor": "rgba(9, 184, 80, 0.3)",
                        "underLineBottomColor": "rgba(9, 184, 80, 0)",
                        "isTransparent": true,
                        "autosize": true,
                        "largeChartUrl": ""
                    }
                </script>
            </div>
            <!-- TradingView Widget END -->
        </div>
    </section>
    <!-- =======================
    Pricing END -->

    <!-- =======================
    Features content START -->
    <section class="pt-0">
        <div class="container">
            <div class="bg-dark rounded-3 position-relative overflow-hidden p-5 p-md-7" data-bs-theme="dark">
                <!-- SVG decoration -->
                <figure class="position-absolute bottom-0 start-0 mb-n5 ms-n7">
                    <svg class="fill-white" width="177" height="187" viewBox="0 0 177 187" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M145.997 54.7907C146.303 55.9483 147.111 56.7781 148.142 57.4275C148.71 57.7851 148.555 58.3776 148.482 58.842C148.419 59.2452 148.287 59.5844 148.46 60.0065C148.532 60.1801 148.397 60.5706 148.231 60.6875C147.703 61.0627 147.243 61.5096 146.628 61.801C144.465 62.8271 142.147 63.2656 139.827 63.6105C137.458 63.9623 135.087 64.3768 132.677 64.3899C131.927 64.3937 131.928 64.407 131.735 63.5032C133.049 63.4002 134.363 63.3426 135.664 63.1811C136.697 63.0532 137.712 62.7983 138.828 62.5815C138.54 62.2938 138.316 62.2013 137.914 62.2829C135.722 62.7261 133.518 63.0936 131.162 63.1237C131.131 62.9437 131.093 62.7258 131.055 62.5095C131.106 62.4847 131.219 62.4301 131.412 62.3365L130.707 62.0338C130.804 61.5801 130.891 61.1692 131.005 60.6343C131.35 60.3272 132.108 60.4369 132.465 59.8406C133.144 60.1569 133.745 59.7031 134.378 59.6166C135.162 59.5101 135.923 59.235 136.538 59.0732C136.659 58.644 136.732 58.316 136.843 57.9997C137.23 56.8911 137.253 56.9328 138.132 56.5166C139.109 56.0533 140.247 55.939 141.077 55.158C141.13 55.1076 141.254 55.0794 141.321 55.1043C142.025 55.371 142.442 55.0921 142.889 54.5154C143.264 54.0318 143.905 54.1232 144.48 54.34C144.921 54.5069 145.385 54.6119 145.997 54.7907Z"></path>
                        <path d="M24.428 116.334C25.1448 115.73 25.1448 115.73 24.5158 114.968C24.5615 114.364 25.1989 113.883 24.9199 113.199C25.284 112.72 25.6931 112.269 26.002 111.757C26.5002 110.931 27.3748 110.769 28.1599 110.519C28.6932 110.349 29.2345 110.164 29.7889 110.038C30.4375 109.89 31.0639 109.649 31.7116 109.499C32.2986 109.364 32.901 109.291 33.4982 109.203C33.6642 109.179 33.8684 109.145 34.0013 109.216C34.3549 109.405 34.657 109.384 35.0322 109.236C35.7537 108.952 36.4793 109.31 37.1858 109.407C37.844 109.499 38.4753 109.88 39.0822 110.203C39.3141 110.325 39.8134 110.578 39.2077 110.944C39.1647 110.97 39.2822 111.239 39.3021 111.399C39.3227 111.566 39.3736 111.86 39.3118 111.89C38.5819 112.237 38.8737 112.524 39.2484 112.987C39.8001 113.669 39.649 113.875 39.0359 114.806C38.6624 115.372 38.3638 116.014 37.677 116.145C35.8203 116.497 33.9632 116.877 32.0655 116.221C31.3051 115.958 30.4472 115.833 29.6556 116.213C28.9735 116.541 28.1709 116.608 27.6086 117.214C27.4389 117.397 27.114 117.444 26.8523 117.53C26.2949 117.713 25.7631 117.711 25.1905 117.476C24.5955 117.232 24.6639 116.708 24.428 116.334Z"></path>
                        <path d="M107.925 54.6811C107.026 55.0396 106.183 55.3027 105.408 55.6985C103.185 56.8338 100.753 56.7813 98.3713 57.0078C97.3016 57.1097 96.3873 56.5562 95.4595 56.133C95.2986 56.0594 95.2371 55.767 95.123 55.5624C95.5551 55.5356 95.9599 55.5105 96.5508 55.4736L94.8167 54.7282C94.7925 54.2801 94.8495 53.8726 94.7301 53.5265C94.5819 53.097 94.7873 52.772 94.9803 52.5195C95.2049 52.2253 95.6012 52.0623 95.9543 51.8212C96.7809 52.4066 97.6969 52.376 98.6818 52.2735C98.7451 51.83 99.2289 51.4823 98.9919 50.909C98.9415 50.7875 99.0535 50.5078 99.1765 50.4194C100.182 49.6971 101.007 48.6351 102.422 48.6597C102.677 48.6645 103 48.6868 103.173 48.5503C103.434 48.3445 103.784 48.7803 103.999 48.3272C104.177 47.9543 104.459 48.3627 104.699 48.3852C104.996 48.413 105.301 48.4077 105.588 48.477C105.851 48.5403 106.095 48.6843 106.349 48.7937C106.766 50.1287 107.562 51.2137 108.543 52.2046C108.334 53.0393 108.123 53.8866 107.925 54.6811Z"></path>
                        <path d="M3.22725 135.018C3.01142 134.651 2.82621 134.358 2.6643 134.052C2.60584 133.94 2.59095 133.8 2.57731 133.67C2.53843 133.299 3.1029 133.289 3.04058 132.867C2.99194 132.536 2.97883 132.167 3.07829 131.856C3.16105 131.598 3.61758 131.359 3.57889 131.185C3.49264 130.796 3.50464 130.591 3.90481 130.457C3.97853 130.432 4.01395 130.239 4.03446 130.118C4.18727 129.225 4.18475 129.224 5.15765 129.129C5.0126 128.997 4.88744 128.882 4.65873 128.674C5.06032 128.695 5.36079 128.675 5.64444 128.734C5.99106 128.805 6.32811 128.932 6.66056 129.057C6.99508 129.182 7.31918 129.336 7.73596 129.297L6.56754 128.649C6.58429 128.605 6.60126 128.561 6.61867 128.516C6.845 128.585 7.07915 128.636 7.29634 128.728C8.39764 129.193 9.47652 129.718 10.5979 130.126C11.5873 130.486 12.3905 131.027 12.9544 131.916C12.6709 132.036 12.2909 132.102 12.664 132.539C13.0715 133.016 12.857 133.407 12.3933 133.832C12.9178 134.161 13.5636 134.486 12.9514 135.326L11.5434 134.721C11.5047 134.777 11.4652 134.834 11.426 134.891C11.8428 135.245 12.2597 135.599 12.6887 135.963C12.2313 136.259 11.7885 136.473 11.2316 136.153C11.0752 136.064 10.8129 136.179 10.6028 136.162C9.68931 136.086 9.68926 136.081 9.38524 136.736C9.32706 136.861 9.27587 136.989 9.22141 137.116L9.07184 137.464C8.61231 137.647 8.17897 137.804 7.7589 137.99C6.86549 138.388 5.968 138.251 5.08368 137.997C4.64531 137.87 4.24938 137.593 3.80988 137.474C3.32539 137.343 3.08927 136.963 2.85718 136.617C2.39472 135.928 2.4732 135.694 3.22725 135.018Z"></path>
                        <path d="M45.1232 65.2617C45.2584 65.9616 45.403 66.6418 45.5014 67.3281C45.5109 67.3946 45.2383 67.5014 45.0079 67.6442C45.1784 67.8653 45.3473 68.0383 45.4581 68.2427C45.5893 68.4833 45.6663 68.7532 45.7726 69.0081C45.9873 69.5241 46.2494 70.0452 45.4624 70.3332C45.4026 70.3556 45.3917 70.4965 45.3469 70.5761C45.0273 71.1423 44.9322 71.7719 44.1975 72.164C43.6059 72.4798 43.0766 72.978 42.4099 73.2273C41.7843 73.4605 41.1793 73.7203 40.4896 73.7742C39.4305 73.8564 38.3836 73.9374 37.4391 73.3399C37.2022 73.19 37.0439 72.915 36.7713 72.6108C37.1705 72.4037 37.4404 72.2637 37.7561 72.1004C37.159 71.5986 36.3433 71.2982 36.012 70.4027C36.3054 70.2335 36.5888 70.07 36.9424 69.8661L36.0159 69.4679C36.2322 68.5719 36.2712 67.6903 36.6811 66.8754C36.9162 66.4081 37.2463 66.0587 37.6679 65.8802C38.2092 65.6505 38.682 65.2875 39.2978 65.1747C39.8547 65.073 40.3863 64.8352 40.9624 64.7248C41.9093 64.5441 42.8389 64.7449 43.7755 64.7177C44.2221 64.7044 44.6802 65.0713 45.1232 65.2617Z"></path>
                        <path d="M46.2804 129.498L49.0061 130.67C48.426 130.121 47.7113 129.866 47.0284 129.547C46.7534 129.419 46.3502 129.401 46.3291 128.992C46.3067 128.569 46.2946 128.127 46.4009 127.727C46.4376 127.59 46.8499 127.475 47.0823 127.488C47.332 127.501 47.5726 127.677 47.8446 127.794C47.8471 127.546 47.799 127.376 47.8598 127.269C47.9856 127.049 48.3374 126.822 48.3017 126.666C48.0996 125.798 48.7296 125.211 48.9848 124.513C49.1002 124.196 49.5073 123.818 49.8217 123.777C50.595 123.68 51.0987 123.027 51.9901 122.955C52.3057 123.076 52.7066 123.2 53.2579 123.055C53.9802 122.865 54.8069 122.793 55.5419 123.264C55.8837 123.483 56.2915 123.599 56.74 123.792C56.8815 124.879 56.9937 125.994 57.1795 127.097C57.3743 128.251 56.6292 128.905 55.8957 129.501C55.36 129.936 54.8984 130.629 54.033 130.482C52.9291 131.494 51.5109 131.149 50.2239 131.389C49.3586 131.551 48.583 131.201 47.8174 131.023C47.1848 130.876 46.6825 130.169 46.1228 129.71C46.1753 129.639 46.2282 129.568 46.2804 129.498Z"></path>
                        <path d="M70.5926 110.828C69.8281 111.133 69.0176 111.456 68.1052 111.82C67.6827 111.638 67.1604 111.466 66.7002 111.189C66.5294 111.086 66.4586 110.745 66.4359 110.504C66.4267 110.412 66.6809 110.296 66.8165 110.19C66.5305 109.848 66.0829 109.528 66.471 108.956C66.5438 108.85 66.6214 108.688 66.5917 108.582C66.4737 108.163 66.3624 107.714 66.6436 107.365C67.1481 106.738 67.6053 105.994 68.3353 105.65C69.7792 104.97 71.284 104.381 72.9227 104.438C73.8997 104.472 74.8905 104.55 75.8376 104.771C76.2517 104.868 76.7353 105.198 76.8534 105.769C77.0486 106.707 77.2959 107.636 77.4597 108.58C77.5152 108.902 77.4763 109.304 77.3247 109.586C76.3715 111.363 74.9375 112.305 72.6182 111.955C72.2802 111.904 71.8821 111.958 71.6155 111.795C71.2299 111.559 70.9384 111.168 70.5926 110.828Z"></path>
                        <path d="M117.847 127.388C117.571 127.329 117.295 127.27 117.018 127.211C118.028 127.648 117.947 128.878 118.706 129.48C118.978 129.695 118.92 130.208 118.668 130.323C118.258 130.511 118.371 130.807 118.409 131.029C118.511 131.618 118.314 131.997 117.837 132.349C116.462 133.364 115.132 134.45 113.335 134.625C112.4 135.364 111.22 135.191 110.158 135.443C109.642 135.566 109.063 135.427 108.45 135.404C108.087 134.829 108.444 134.373 109.022 133.885C108.703 133.819 108.44 133.687 108.221 133.738C107.919 133.81 107.907 133.589 107.908 133.476C107.913 132.959 107.974 132.449 107.783 131.94C107.736 131.815 107.877 131.58 107.99 131.446C108.193 131.206 108.471 131.045 108.353 130.631C108.32 130.513 108.583 130.291 108.737 130.146C110.066 128.906 111.503 127.884 113.383 127.619C113.637 127.584 113.968 127.401 114.248 127.277C115.048 126.923 115.893 127.156 116.679 126.953C117.202 126.82 117.519 127.034 117.847 127.388Z"></path>
                        <path d="M91.7672 24.8247C91.547 24.6914 91.3267 24.5567 91.0847 24.4087C91.1914 24.3453 91.2532 24.2747 91.3141 24.2755C92.2508 24.2918 93.1886 24.214 94.1227 24.389C94.7102 24.499 95.2887 24.5664 95.4832 25.2642C95.6155 25.7395 96.5858 25.7315 96.1984 26.507C96.6387 27.1399 96.321 27.7134 96.0335 28.2693C96.3799 28.5727 96.7377 28.8211 97.0145 29.1413C97.3269 29.5007 97.1767 30.157 96.7095 30.7302C96.6033 30.8604 96.3966 30.9421 96.3498 31.0845C95.9876 32.1812 95.9964 32.1824 94.9713 32.2693C94.4601 32.312 93.9473 32.41 93.4379 32.3949C92.8165 32.3769 92.3452 32.5594 91.8854 32.9972C91.2027 33.647 90.29 33.8676 89.4099 33.6518C88.6128 33.4571 88.0808 32.8153 87.8304 31.9216C88.401 31.7779 88.9702 31.6335 89.7678 31.4315C89.1539 31.1676 88.7378 30.9967 88.3291 30.8104C87.922 30.6261 88.0252 30.6171 88.0117 29.8426C88.0021 29.2882 88.5441 28.7258 88.0154 28.1815C88.1945 27.7162 88.3745 27.2513 88.554 26.7849C88.8844 25.9237 89.299 25.2133 90.4096 25.223C90.8495 25.2269 91.2921 24.9722 91.7672 24.8247Z"></path>
                        <path d="M44.107 152.712C43.459 153.621 42.5969 153.667 41.933 153.49C41.084 153.264 40.1846 153.198 39.3681 152.77C38.3108 152.216 37.1992 151.745 36.0708 151.357C35.5233 151.169 35.277 150.66 34.7775 150.454C34.6477 150.401 34.4752 150.114 34.5167 150.032C34.8351 149.394 34.2607 148.779 34.5511 148.091C34.7441 147.636 34.5125 147.001 34.4652 146.377C35.1831 146.091 35.0212 144.756 36.3034 144.825C36.9667 145.426 37.9354 145.056 38.829 145.173C38.9537 145.19 39.1033 145.116 39.2089 145.16C40.4687 145.689 41.7218 146.232 43.0851 146.818C42.8176 146.944 42.7019 146.999 42.4138 147.134C42.7381 147.192 42.9584 147.302 43.078 147.234C43.206 147.162 43.2123 146.897 43.3284 146.767C43.3808 146.708 43.5787 146.75 43.6947 146.791C43.7479 146.809 43.8138 146.971 43.7879 147.008C43.5956 147.289 43.3815 147.557 43.1557 147.853C43.5492 148.291 43.9691 148.758 44.2831 149.108C44.1087 149.443 44.004 149.643 43.8999 149.842C44.4206 149.637 44.3732 150.11 44.5331 150.357C44.6949 150.608 44.9019 150.83 45.0765 151.049C44.6463 151.811 44.0031 151.888 43.2288 151.808C43.5289 152.116 43.7977 152.394 44.107 152.712Z"></path>
                        <path d="M142.716 84.3152C142.415 84.9644 141.971 85.5099 142.563 86.1407C141.625 86.3733 141.096 86.221 140.769 85.4333C140.544 84.8894 140.167 84.4326 140.038 83.808C139.926 83.2613 139.343 82.8277 139.086 82.2877C138.888 81.8727 138.872 81.3703 138.776 80.9068C138.933 80.8421 139.103 80.7729 139.37 80.6632C139.426 80.9259 139.482 81.1839 139.537 81.442C139.598 81.442 139.658 81.4414 139.718 81.4414C139.797 81.132 139.876 80.8232 139.964 80.4756C140.132 80.4449 140.508 80.4876 140.713 80.3201C141.944 79.3147 143.426 78.9719 144.92 78.6814C145.41 78.5859 145.939 78.7078 146.448 78.6805C147.705 78.6135 147.954 78.5635 148.829 79.1248C149.462 79.5304 150.135 79.9671 150.461 80.741C150.589 81.0465 150.808 81.4079 151.083 81.5396C151.779 81.8718 151.726 82.3095 151.443 82.8658C151.364 83.0212 151.312 83.1991 151.287 83.3727C151.127 84.4857 151.054 84.5767 150.076 84.7453C148.627 84.9954 147.181 84.791 145.738 84.6785C144.719 84.599 143.705 84.4371 142.716 84.3152Z"></path>
                        <path d="M121.835 94.4787C121.131 95.0122 120.474 95.576 119.647 95.9019C118.103 96.5084 116.595 97.223 115.016 97.716C113.357 98.2345 111.689 98.8214 109.998 98.8377C109.465 98.5303 110.375 98.0553 109.719 97.7567C109.532 97.6711 109.548 97.1292 109.479 96.7951C109.308 95.9655 109.387 95.5771 110.082 95.3249C110.767 95.0762 111.171 94.6209 111.556 94.0752C112.151 93.2323 113.138 92.8985 113.918 92.2932C114.751 91.6455 115.766 91.3648 116.718 90.9566C117.21 90.7452 117.662 90.4132 118.169 90.2677C118.679 90.1213 119.256 90.0328 119.767 90.1229C120.078 90.1783 120.414 90.5741 120.573 90.8986C120.769 91.2989 121.163 91.618 121.113 92.1498C121.083 92.4573 121.401 92.5488 121.627 92.7083C121.887 92.8917 122.2 93.1206 121.628 93.4621C121.229 93.7011 121.459 94.1453 121.835 94.4787Z"></path>
                        <path d="M136.472 40.1805C135.254 41.3715 133.878 41.518 132.535 41.6733C131.354 41.8095 130.155 41.8815 128.967 41.8493C127.781 41.8178 126.52 42.1082 125.38 41.2466C126.412 40.6056 127.599 41.2053 128.793 40.6764C127.433 40.2827 126.149 40.5244 124.646 40.2742C125.098 39.9209 125.262 39.7928 125.462 39.6353C125.258 39.5008 125.044 39.3595 124.83 39.2197C124.923 38.805 125.013 38.3963 125.12 37.9146C125.36 37.8766 125.706 37.8427 126.045 37.7685C127.361 37.483 127.358 37.4765 128.384 36.3706C128.499 36.2468 128.642 36.1471 128.783 36.0505C129.857 35.3143 130.973 34.7028 132.323 34.6237C132.822 34.5945 133.697 34.6377 134.13 34.8381C134.231 34.8855 134.388 34.8517 134.506 34.8136C134.808 34.7182 134.959 34.8855 134.986 35.129C135.063 35.8387 135.59 36.3584 135.751 37.0273C135.82 37.3139 136.018 37.4446 136.268 37.5987C136.608 37.8088 136.773 38.7242 136.554 39.022C136.418 39.2073 135.984 39.1778 136.142 39.5774C136.225 39.7875 136.359 39.977 136.472 40.1805Z"></path>
                        <path d="M89.1868 68.5912C89.1283 68.8288 89.0665 69.0775 88.9818 69.4213C89.2737 69.3968 89.4812 69.3789 89.6988 69.3603C90.0427 70.2228 90.4007 71.1207 90.7703 72.0465C90.7254 72.1509 90.6515 72.2704 90.6217 72.3998C90.2729 73.9381 89.0565 74.5392 87.7415 74.9115C86.7142 75.2023 85.6469 75.3732 84.5538 75.3933C83.8714 75.4058 83.2459 75.41 82.5487 74.8918C83.6848 74.868 84.6962 74.8464 85.7081 74.825C85.7152 74.7469 85.7228 74.669 85.73 74.5905C85.5555 74.5525 85.3806 74.4828 85.2057 74.4815C84.4829 74.4761 83.7589 74.5119 83.0375 74.4893C82.7178 74.4794 82.2782 74.5636 82.4035 73.9216C82.4423 73.7236 82.0972 73.4494 81.9251 73.2115C81.7741 73.0031 81.6191 72.7975 81.4964 72.631C81.6972 72.0138 82.2189 72.2314 82.5924 71.9533C82.2284 71.8989 81.8643 71.8444 81.3851 71.7731C81.3163 71.5184 81.2243 71.178 81.1317 70.8375C81.3599 70.7457 81.705 70.6424 81.3216 70.2442C80.9076 69.813 80.8464 69.4125 81.3985 69.0434C81.4488 69.0099 81.5136 68.9234 81.5016 68.8819C81.2679 68.0789 81.9048 67.9389 82.3962 67.825C83.5707 67.5539 84.7755 67.7065 85.9641 67.7529C86.2432 67.7636 86.8916 67.8044 86.6283 68.4913C86.6133 68.5302 86.8108 68.6498 86.9092 68.7313C87.0037 68.6053 87.2057 68.436 87.1746 68.3592C86.9577 67.8173 87.5411 67.8264 87.6121 67.9011C88.0494 68.3589 88.7933 68.0534 89.1868 68.5912Z"></path>
                        <path d="M60.1016 61.539C60.3147 61.4539 60.5285 61.3688 60.8524 61.2392C60.5112 61.0434 60.2297 60.8815 59.9227 60.7049C60.0379 60.1352 60.1316 59.537 60.2859 58.9549C60.3792 58.6032 60.5307 58.2545 60.7233 57.9465C60.8705 57.7119 61.2541 58.1107 61.4967 57.6593C61.6536 57.3676 62.2689 57.356 62.7202 57.2946C63.0866 57.2445 63.6041 57.7777 63.8523 57.0964C64.2742 57.2508 64.6967 57.4053 65.1192 57.5587C65.16 57.5741 65.2036 57.5818 65.2463 57.5929L67.9449 56.5169C68.0318 56.3148 68.0978 56.0893 68.2179 55.8986C68.4042 55.6051 68.8627 55.4 68.987 55.6448C69.2632 56.1896 69.838 55.6729 70.0797 56.0798C69.9519 57.1887 69.8514 57.5124 69.368 58.2516C69.8868 58.2374 70.339 58.2252 70.9073 58.2103C70.9749 58.3905 71.2012 58.687 71.129 58.8524C70.6711 59.8956 70.4631 61.1112 69.3192 61.7249C69.0088 61.8911 68.7195 62.1288 68.3887 62.2194C67.6341 62.4266 66.8621 62.5703 66.0936 62.7251C65.7148 62.8013 65.3278 62.839 64.9464 62.9046C64.3558 63.0062 63.7289 62.6314 63.1623 63.0394C62.6383 62.7154 62.0791 62.8492 61.5094 62.8433C60.7353 62.8355 60.1804 62.3956 60.1016 61.539Z"></path>
                        <path d="M24.4201 92.6484C24.6329 92.1533 24.778 91.8144 24.9238 91.4764C25.1196 91.0237 25.2545 90.5005 25.954 90.6782C26.0315 90.6982 26.2157 90.5766 26.2389 90.4905C26.3899 89.9196 26.957 89.9985 27.3212 89.766C28.3098 89.1352 29.3635 89.4325 30.3814 89.6063C30.9619 89.7052 31.2661 90.0374 31.367 90.7899C31.4803 91.6344 31.3685 92.5824 31.9007 93.3574C31.7336 93.768 32.2272 94.3747 31.5132 94.6312C31.41 94.6685 31.3196 94.8107 31.2728 94.9246C30.6145 96.5411 29.2628 97.1487 27.6753 97.459C26.8702 97.6168 26.0852 97.9101 25.2473 97.7475C24.9035 97.6809 24.5639 97.5824 24.2334 97.4668C24.1247 97.4295 23.9688 97.2891 23.9739 97.204C24.0206 96.4599 23.6721 95.7544 23.717 95.0325C23.7728 94.1427 24.0445 93.2821 24.4201 92.6484Z"></path>
                        <path d="M85.1622 121.171C85.5341 121.11 85.8079 121.066 86.3399 120.98C85.923 120.73 85.7815 120.643 85.6369 120.56C85.1575 120.288 85.158 120.289 85.3668 119.625C85.3927 119.542 85.456 119.424 85.423 119.376C85.0904 118.889 85.1571 118.381 85.4916 117.983C85.8704 117.531 86.3053 117.059 86.8188 116.802C88.8218 115.798 90.9977 115.636 93.1932 115.728C93.5675 115.744 93.9306 116.025 94.2939 116.181C94.1918 116.36 94.1118 116.501 94.0312 116.643C94.5005 116.807 94.794 117.055 94.6726 117.634C94.6365 117.807 94.781 118.128 94.9349 118.213C95.4606 118.503 94.9509 118.951 95.152 119.271C94.5173 119.37 94.89 120.17 94.381 120.37C93.8938 120.561 93.4312 120.911 92.9335 120.962C91.8375 121.074 90.7144 121.149 89.6272 121.024C89.0712 120.959 88.6498 121.037 88.2631 121.32C87.6363 121.781 86.9717 122.063 86.1902 122.083C85.5378 122.1 85.5384 122.113 85.1622 121.171Z"></path>
                        <path d="M80.624 91.0137C80.4978 90.9595 80.3092 90.9142 80.1693 90.8056C80.0759 90.7328 80.0816 90.5217 79.9874 90.4624C79.8844 90.3978 79.702 90.4728 79.5812 90.4225C79.4742 90.3776 79.4078 90.1714 79.3165 90.1668C79.1486 90.1582 78.9628 90.2206 78.8091 90.3009C78.7068 90.354 78.6473 90.4902 78.5292 90.6391C78.8385 90.6783 79.0875 90.7098 79.3374 90.741C78.8711 91.072 78.4389 91.4767 77.8741 90.974C78.033 90.8226 78.19 90.6744 78.384 90.4901C77.622 90.0765 77.7186 90.9893 77.2214 91.1111C77.4485 90.7176 77.2817 90.3187 77.5163 89.9257C77.625 89.7434 77.3224 89.3152 77.1692 88.8985C77.6818 88.6315 78.256 88.332 78.928 87.9818C78.6288 87.7908 78.4158 87.6546 78.1564 87.489C78.3006 86.8287 78.4488 86.1517 78.5983 85.4672C79.1525 85.3208 79.6398 85.2295 80.0983 85.0602C80.495 84.9128 80.8412 84.6246 81.2407 84.49C82.3684 84.1105 83.5386 84.2218 84.6843 84.3589C85.0944 84.4076 85.7209 84.5368 85.5193 85.274C86.1887 85.5579 85.9856 86.305 86.2724 86.8047C86.6314 87.4307 86.4199 88.1743 85.7862 88.8025C84.8371 89.7432 83.6269 90.1093 82.4489 90.4644C81.859 90.6417 81.2743 90.8744 80.624 91.0137Z"></path>
                        <path d="M106.096 77.1603C106.254 76.9853 106.406 76.8162 106.581 76.6227C105.532 76.1173 104.758 77.0928 103.708 76.8237C103.903 76.6426 104.023 76.4785 104.184 76.3917C104.444 76.2527 104.73 76.1649 105.107 76.0146C104.533 75.8743 104.384 75.881 103.886 76.029C103.366 75.0139 103.366 75.0139 103.702 74.2241C104.377 74.0006 105.18 73.9069 105.758 73.497C106.396 73.0447 107.114 73.1391 107.767 72.884C108.392 72.6407 109.114 72.6108 109.798 72.5656C110.173 72.5403 110.623 72.5754 110.924 72.7656C111.305 73.0064 111.83 73.4387 111.822 73.7773C111.809 74.3347 112.191 74.3728 112.415 74.6465C112.722 75.021 112.985 75.4664 112.849 76.0171C112.769 76.3387 112.844 76.7949 112.651 76.9726C112.178 77.4048 111.632 77.8263 111.04 78.0411C109.921 78.4463 108.756 78.7382 107.592 78.9962C107.301 79.0612 106.941 78.8189 106.48 78.6772C106.745 78.538 106.849 78.4825 107.071 78.3649C106.383 78.1795 106.405 77.5177 106.096 77.1603Z"></path>
                        <path d="M149.811 103.865C149.471 104.129 149.308 104.257 149.148 104.381C150.058 105.11 150.08 105.204 149.346 105.888C149.96 105.888 150.451 105.887 150.985 105.887C151.019 106.162 151.052 106.444 151.087 106.729C150.499 106.964 149.902 107.202 149.249 107.462C149.749 108.003 150.36 107.369 150.926 107.701C150.729 108.42 150.098 108.615 149.506 108.824C149.181 108.937 148.808 108.913 148.491 109.037C147.074 109.59 145.621 110.008 144.14 110.344C144.008 110.374 143.883 110.441 143.756 110.495C143.071 110.787 142.393 111.109 141.608 110.815C141.518 110.192 142.096 110.13 142.529 109.918C142.111 109.333 141.696 110.091 141.294 109.876C140.805 109.63 141.856 108.95 140.931 108.84L141.523 107.465C141.969 107.19 142.415 106.909 142.868 106.64C143.322 106.37 143.844 106.287 144.283 105.906C144.71 105.537 145.377 105.457 145.915 105.202C146.266 105.036 146.664 105.005 146.954 104.622C147.163 104.346 147.704 104.382 147.846 103.886C147.885 103.748 148.224 103.613 148.42 103.619C148.796 103.63 149.167 103.743 149.811 103.865Z"></path>
                        <path d="M61.1012 76.0729C61.3179 75.6484 61.2217 75.2495 61.6818 74.9738C61.6643 75.2069 61.6519 75.3759 61.6382 75.5603C62.2118 75.6293 62.7893 75.6799 63.358 75.7793C63.5038 75.8049 63.7352 75.9866 63.7344 76.095C63.7306 76.6971 64.2125 77.087 64.3423 77.6129C64.4001 77.8457 64.3303 78.1275 64.2577 78.3683C64.2208 78.4888 63.9888 78.5497 63.9515 78.67C63.9061 78.8166 63.9734 78.9978 63.9937 79.1815C63.1296 79.0682 63.1032 79.0437 62.4417 79.7764C62.0457 80.2148 61.4949 80.1045 61.0207 80.3322C61.1594 80.4884 61.2933 80.6392 61.4437 80.808C61.2544 80.8785 61.0397 81.0417 60.9188 80.9892C59.4082 80.3321 59.3736 80.319 58.1699 80.6199C58.6867 81.3034 59.4034 80.7273 60.0901 80.9746C59.9818 81.147 59.8845 81.4325 59.792 81.4307C58.8634 81.4162 57.9055 81.5906 57.177 80.6765C56.9508 80.3928 56.8089 80.2981 56.9548 79.9419C57.0452 79.7226 57.0179 79.4332 56.9783 79.1861C56.8766 78.5459 56.7386 77.9121 56.6032 77.2165C56.8396 77.1724 57.0585 77.1319 57.0052 77.1415C57.4833 76.8443 56.877 76.0595 57.738 76.3456C57.9825 76.4265 57.6467 75.658 58.2639 75.9232C58.5024 76.0258 58.9297 75.6902 59.2448 75.5645C59.4149 75.7309 59.5776 75.8892 59.74 76.0479C59.8102 76.0041 59.8803 75.9603 59.9507 75.9161C59.788 75.7153 59.6254 75.5146 59.4212 75.2619C59.9044 75.2009 60.1842 75.165 60.5514 75.1181C60.7213 75.4134 60.9114 75.743 61.1012 76.0729Z"></path>
                        <path d="M80.6889 135.08C80.1943 135.214 79.8028 135.32 79.3672 135.438C79.5787 135.684 79.7129 135.84 79.888 136.044C79.4488 136.434 78.9292 136.6 78.4143 136.711C77.276 136.954 76.1081 137.019 74.9648 137.27C74.262 137.425 73.5773 137.158 72.9441 136.705C73.0755 136.076 73.81 136.243 74.1893 135.834L72.9526 135.303C73.2161 134.335 73.47 133.403 73.7031 132.549C74.8258 132.03 75.8853 131.826 77.0023 131.838C77.6389 131.845 78.2438 131.555 78.9221 131.679C79.1903 131.728 79.3841 132.182 79.7313 131.875C79.7369 131.87 79.982 132.119 79.9789 132.122C79.4819 132.653 80.3029 133.257 79.8967 133.799C79.8701 133.834 80.0578 134.041 80.1601 134.157C80.2721 134.284 80.4204 134.383 80.5146 134.52C80.5855 134.623 80.5956 134.766 80.6889 135.08Z"></path>
                        <path d="M108.148 113.549C108.646 113.548 109.009 113.547 109.526 113.546C109.26 113.289 109.138 113.171 109.009 113.046C109.068 113.004 109.132 112.921 109.183 112.929C109.668 113.001 110.129 112.954 110.595 112.782C110.873 112.679 111.187 112.653 111.487 112.63C112.019 112.591 112.453 112.398 112.769 111.919C112.639 111.766 112.504 111.609 112.442 111.536C112.725 111.373 113.034 111.196 113.263 111.064C113.421 111.132 113.497 111.144 113.501 111.169C113.552 111.463 113.592 111.759 113.631 112.03C114.004 112.117 114.343 112.196 114.85 112.314C114.502 112.616 114.31 112.78 114.035 113.017C114.298 113.327 114.563 113.639 114.847 113.973C114.793 114.152 114.73 114.362 114.653 114.618L115.217 114.861C115.421 115.704 115.132 116.199 114.39 116.535C113.581 116.902 112.825 117.382 111.906 117.715C111.846 117.383 111.789 117.063 111.726 116.711C111.153 116.631 110.588 116.685 109.985 116.855C109.382 117.024 108.716 116.973 108.1 117.017C107.802 116.448 108.267 116.3 108.558 116.02C108.436 115.951 108.325 115.833 108.215 115.834C107.533 115.842 107.297 115.61 107.504 115.059C107.698 114.542 107.936 114.042 108.148 113.549Z"></path>
                        <path d="M97.6308 151.861C98.0372 151.784 98.3694 151.72 98.7836 151.641C98.6323 151.51 98.5523 151.384 98.4501 151.361C97.9542 151.25 97.4522 151.167 96.8842 151.062L97.2016 150.324C97.3618 150.317 97.558 150.309 97.9464 150.293L96.9086 149.846C96.9199 149.68 96.9573 149.551 96.9306 149.437C96.8685 149.177 96.6696 148.722 96.7095 148.701C97.1138 148.492 97.1249 148.025 97.3797 147.763C97.7592 147.373 98.2618 147.064 98.8544 147.326C99.0316 147.404 99.1283 147.725 99.293 147.754C99.4269 147.777 99.5942 147.463 99.7735 147.425C99.9199 147.393 100.224 147.516 100.254 147.626C100.375 148.069 100.746 148.142 101.075 148.257C101.485 148.399 101.651 148.677 101.6 149.074C101.545 149.497 101.749 149.689 102.076 149.923C102.537 150.255 102.726 150.75 102.447 151.331C102.35 151.532 102.264 151.746 102.222 151.963C102.115 152.515 101.721 153.099 101.283 153.098C100.214 153.093 99.1212 153.283 98.1023 152.722C97.7051 152.503 97.6946 152.203 97.6308 151.861Z"></path>
                        <path d="M92.2876 101.301C92.5161 101.026 92.7542 100.737 93.0472 100.383C92.7116 100.035 92.3404 99.8155 91.8189 100.021C91.6057 99.4014 92.3431 99.3675 92.4646 98.9026C92.2617 98.7673 92.0484 98.6243 91.839 98.4847C91.62 97.9855 92.4279 97.9674 92.2869 97.4173C92.1424 96.8547 92.8921 97.0506 93.127 96.7261C93.2588 96.5439 93.55 96.4455 93.7889 96.3776C94.5789 96.1522 95.4387 96.4334 96.2258 96.0107C96.4139 95.9095 96.7983 96.1718 97.1862 96.2978C96.5302 96.8596 97.6415 97.3886 97.0789 97.9596C97.3296 98.3174 98.0343 98.3841 97.7766 99.1877C97.6028 99.2096 97.3304 99.2437 96.9421 99.2919C97.1983 99.5854 97.3227 99.7276 97.4507 99.8738C97.3674 100.35 96.9097 100.388 96.6116 100.428C95.6193 100.56 94.7525 100.912 93.9404 101.499C93.4407 101.861 92.8523 101.924 92.2876 101.301Z"></path>
                        <path d="M55.8827 92.9763C56.0945 93.7776 56.251 94.3684 56.4534 95.1314L55.9946 96.1986C55.2153 96.3557 54.3668 96.151 53.6074 96.5503C52.7432 96.255 51.9761 96.6106 51.177 96.8626C50.9224 96.9425 50.5841 96.7563 50.2094 96.6756C50.237 96.4701 50.3187 96.2901 50.2706 96.1568C49.7781 94.7901 50.6854 94.0919 51.6411 93.5822C52.4568 93.147 53.3994 92.8889 54.3159 92.7261C54.8352 92.6335 55.4167 92.8919 55.8827 92.9763Z"></path>
                    </svg>
                </figure>

                <div class="row position-relative">
                    <div class="col-lg-5 position-relative order-1">
                        <!-- Counter item -->
                        <div class="border border-white border-opacity-50 rounded-circle d-flex align-items-center justify-content-center flex-column h-250px w-250px">
                            <div class="d-flex">
                                <h4 class="purecounter text-white mb-0" data-purecounter-start="0" data-purecounter-end="600"   data-purecounter-delay="300">0</h4>
                                <span class="h4 text-primary mb-0">k+</span>
                            </div>
                            <p class="text-center text-white mb-0">Happy Customers</p>
                        </div>

                        <!-- Counter item -->
                        <div class="d-flex justify-content-end mt-n6 mt-lg-0 mt-xl-n7 me-n3">
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center flex-column h-200px w-200px">
                                <div class="d-flex justify-content-center">
                                    <h4 class="purecounter mb-0" data-purecounter-start="0" data-purecounter-end="25"   data-purecounter-delay="300">0</h4>
                                    <span class="h4 text-primary mb-0">m+</span>
                                </div>
                                <p class="text-center heading-color mb-0">Completed Investments</p>
                            </div>
                        </div>

                        <!-- Counter item -->
                        <div class="d-flex justify-content-center mt-n6 mt-lg-0">
                            <div class="bg-primary bg-opacity0 rounded-circle d-flex align-items-center justify-content-center flex-column h-200px w-200px">
                                <div class="d-flex justify-content-center">
                                    <h4 class="purecounter mb-0" data-purecounter-start="0" data-purecounter-end="200"  data-purecounter-delay="300">0</h4>
                                    <span class="h4 text-primary mb-0">k+</span>
                                </div>
                                <p class="text-center heading-color mb-0">Acquired Users</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 ms-auto mb-6 mb-lg-0 order-lg-2">
                        <h3 class="mb-4">Cultivating Investment Success</h3>
                        <p class="mb-5">At <?=e($this->siteSettings('sitename'))?>, our vision is to lead a new era of investment strategies. We are dedicated to empowering investors, enabling them to shape their financial futures, and fostering a world where investment opportunities are limitless.</p>

                        <!-- Features item -->
                        <div class="d-sm-flex mb-4">
                            <!-- Icon -->
                            <figure class="text-primary mb-2">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.24609 17.2586V14.6904H14.7461V17.2586C14.7461 19.1706 14.7461 20.1266 14.5249 20.557C14.0158 21.5475 12.7956 21.9314 11.8111 21.4107C11.3833 21.1845 10.836 20.4007 9.74136 18.833L9.74136 18.833C9.60764 18.6415 9.54079 18.5457 9.48641 18.4451C9.36533 18.2209 9.2882 17.9757 9.25914 17.7226C9.24609 17.609 9.24609 17.4922 9.24609 17.2586Z" fill="currentColor"/>
                                    <path d="M17.5114 9.84101C19.375 6.88684 20.3067 5.40976 19.9484 4.21191C19.8359 3.83574 19.6441 3.48802 19.386 3.19214C18.5641 2.25 16.8176 2.25 13.3248 2.25H10.6405C7.12482 2.25 5.36697 2.25 4.54466 3.1974C4.28652 3.49482 4.09526 3.84421 3.98384 4.22194C3.6289 5.42518 4.57619 6.90596 6.47077 9.86751L9.23438 14.1875H14.7695L17.5114 9.84101Z" fill="currentColor" fill-opacity="0.25"/>
                                </svg>
                            </figure>
                            <!-- Content -->
                            <div class="ms-sm-4">
                                <h6>Strategic Investments</h6>
                                <p class="pb-0">We specialize in formulating strategic investment solutions that address market challenges and drive financial growth.</p>
                            </div>
                        </div>

                        <!-- Features item -->
                        <div class="d-sm-flex mb-4">
                            <!-- Icon -->
                            <figure class="text-primary mb-2">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 2.5C9.79086 2.5 8 4.41878 8 6.78571C8 6.97115 8.01099 7.15383 8.03232 7.33306C8.28317 9.44152 11 10 12 10C13 10 15.7168 9.44152 15.9677 7.33306C15.989 7.15383 16 6.97115 16 6.78571C16 4.41878 14.2091 2.5 12 2.5Z" fill="currentColor"/>
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M7.24989 7.1053C7.24989 7.05966 7.2505 7.01416 7.25173 6.96883C4.2677 7.95454 2.25 9.97184 2.25 12.1034C2.25 14.0748 3.97605 15.8116 6.59627 16.8293L4.46793 18.9716C4.176 19.2655 4.17756 19.7403 4.47141 20.0323C4.76526 20.3242 5.24013 20.3226 5.53207 20.0288L8.20833 17.3349C9.15517 17.5678 10.1784 17.7162 11.25 17.7636V21C11.25 21.4142 11.5858 21.75 12 21.75C12.4142 21.75 12.75 21.4142 12.75 21V17.7636C13.8164 17.7164 14.8348 17.5693 15.7777 17.3384L18.4697 20.0303C18.7626 20.3232 19.2374 20.3232 19.5303 20.0303C19.8232 19.7374 19.8232 19.2626 19.5303 18.9697L17.3938 16.8331C20.0196 15.816 21.75 14.0773 21.75 12.1034C21.75 9.97178 19.7322 7.95444 16.748 6.96875C16.7493 7.01411 16.7499 7.05963 16.7499 7.1053C16.7499 7.31546 16.7368 7.5225 16.7115 7.72562C16.4136 10.1152 13.1874 10.7482 11.9999 10.7482C10.8124 10.7482 7.58615 10.1152 7.28826 7.72562C7.26294 7.5225 7.24989 7.31546 7.24989 7.1053Z" fill="currentColor" fill-opacity="0.25"/>
                                </svg>
                            </figure>
                            <!-- Content -->
                            <div class="ms-sm-4">
                                <h6>Investor-Centric Approach</h6>
                                <p class="pb-0">Our investor-centric approach ensures we understand your unique investment goals and provide tailored strategies that drive growth.</p>
                            </div>
                        </div>

                        <!-- Features item -->
                        <div class="d-sm-flex">
                            <!-- Icon -->
                            <figure class="text-primary mb-2">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3.40004 19.9484C3.41524 20.1895 3.42283 20.3101 3.51172 20.4292C3.53619 20.462 3.58073 20.5066 3.61353 20.5311C3.73269 20.6199 3.85325 20.6275 4.09437 20.6427C5.14741 20.7091 7.28849 20.6474 8.66268 19.2732C9.39454 18.5414 9.77757 17.5685 9.97243 16.6532C10.0108 16.4728 10.03 16.3826 10.0112 16.2835C10.0053 16.2528 9.99478 16.2173 9.98295 16.1883C9.94486 16.0949 9.87386 16.0239 9.73186 15.8819L8.16087 14.3109C8.01887 14.1689 7.94787 14.0979 7.85449 14.0598C7.82551 14.048 7.78998 14.0375 7.75923 14.0316C7.66016 14.0127 7.56997 14.0319 7.38958 14.0703C6.47423 14.2652 5.50143 14.6484 4.76906 15.3808C3.39483 16.822 3.32539 18.9603 3.40004 19.9484Z" fill="currentColor"/>
                                    <path d="M14.8008 4.83653C14.8008 4.6936 14.8008 4.55865 14.8008 4.42543C14.8008 3.64719 14.0495 3 13.2152 3C12.0998 3 11.5037 4.06903 11.5037 5.02736C11.5037 5.98624 11.8098 7.05809 12.8975 7.58215C12.8778 7.38709 12.858 7.2118 12.8395 7.05114C12.8888 6.91176 12.939 6.77156 12.9971 6.64199C13.3275 5.6328 14.2933 5.01088 15.3666 5.03469C16.4423 5.05965 16.7986 5.86106 16.7986 6.74058C16.7986 7.65993 16.2653 8.60557 15.6779 8.8774C15.5415 9.05362 15.4072 9.13919 15.3502 9.16941C15.5054 9.64031 15.8774 9.97817 16.3765 10.1853C16.5455 10.2862 16.7823 10.3728 16.9888 10.4413C17.1952 10.5098 17.3708 10.559 17.4408 10.6373C17.5139 10.7112 17.5596 10.8458 17.5025 10.9413C17.4453 11.0376 17.2229 11.1681 16.9949 11.25C16.0203 11.5705 14.7406 12.3684 14.7242 12.3978C14.4569 12.6702 14.1935 12.7521 13.8617 12.9293C13.2048 13.1526 12.5617 13.5735 12.5617 14.1563C12.5617 15.1224 14.0169 15.6728 15.7874 15.4014C16.0217 15.3004 16.2565 15.0792 16.5285 14.563C16.8132 14.0295 16.6358 13.2173 15.7946 12.9708C15.4405 12.8762 15.0959 12.8011 14.8008 12.8008C14.5198 12.8008 14.2637 12.6623 14.0637 12.5578C13.5832 12.2876 13.1299 12.2741 12.6455 12.3866C11.6331 12.5282 11.1012 12.8337 10.8799 12.9628C10.8682 12.9718 10.8574 12.9793 10.8465 12.9883C10.6476 13.1252 10.5285 13.3652 10.7284 13.6065C10.9174 13.8375 11.0714 13.9039 11.2082 13.8818C12.4232 13.6939 14.6033 12.4064 15.1237 11.5135C15.1849 11.3825 15.1481 11.2124 14.9102 11.0513C14.6583 10.8726 14.2919 10.9158 14.1096 11.0598C14.1111 10.8645 14.1102 10.6586 14.1102 10.4468C14.1102 9.99441 14.1962 9.48926 14.3683 9.08073C14.5776 8.50993 15.3805 7.50089 15.3805 6.90965C15.3805 6.32494 15.4424 5.69297 15.6783 5.00563C15.962 4.09829 16.1182 3.77557 16.3624 3.76727C16.7396 3.74855 17.1105 3.75936 17.4901 3.70395C18.0005 3.62889 18.2522 3.49181 18.6887 3.25479C19.4995 2.79907 19.2329 1.51168 18.4924 1.06963C17.9761 0.773627 17.6127 0.920195 17.4901 1.14325C17.2686 1.5867 17.0331 2.03462 16.9562 2.35617C16.7948 3.12619 17.7715 3.54958 18.0685 4.06661C18.6478 4.8179 17.9815 5.19311 17.4901 5.10329C16.2329 5.00361 14.8008 4.8002 14.8008 4.83653Z" fill="currentColor"/>
                                </svg>
                            </figure>
                            <!-- Content -->
                            <div class="ms-sm-4">
                                <h6>Market Analysis</h6>
                                <p class="pb-0">Our team provides thorough market analysis to identify opportunities and ensure informed investment decisions.</p>
                            </div>
                        </div>
                    </div>
                </div> 
                <!-- Row END -->
            </div>
        </div>
    </section>
    <!-- =======================
    Features content END -->

    <!-- =======================
    Steps START -->
    <section class="pt-0 pb-0">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <!-- Title -->
                    <h2 class="mb-3 mb-lg-5">How To Get Started</h2>
                    <p class="mb-3 mb-lg-5">Follow these 4 simple steps to begin your investment journey with us:</p>

                    <!-- Steps -->
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="icon-md bg-primary bg-opacity-10 text-primary rounded-circle fw-bold">01</div>
                        <h6 class="fw-normal mb-0">Create An Account</h6>
                    </div>
                    <p class="mb-4" style="margin-left: 57px;">Registering an account is quick and easy. Provide your details and verify your email to get started.</p>

                    <!-- Steps -->
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="icon-md bg-primary bg-opacity-10 text-primary rounded-circle fw-bold">02</div>
                        <h6 class="fw-normal mb-0">Choose Your Plan</h6>
                    </div>
                    <p class="mb-4" style="margin-left: 57px;">Select an investment plan that aligns with your financial goals. We offer a variety of options to suit your needs.</p>

                    <!-- Steps -->
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="icon-md bg-primary bg-opacity-10 text-primary rounded-circle fw-bold">03</div>
                        <h6 class="fw-normal mb-0">Fund Your Account</h6>
                    </div>
                    <p class="mb-4" style="margin-left: 57px;">Deposit funds into your account using our secure payment methods. Your investments are just a few clicks away.</p>

                    <!-- Steps -->
                    <div class="d-flex align-items-center gap-3">
                        <div class="icon-md bg-primary bg-opacity-10 text-primary rounded-circle fw-bold">04</div>
                        <h6 class="fw-normal mb-0">Withdraw Profits</h6>
                    </div>
                    <p class="mb-4" style="margin-left: 57px;">Once you start earning, easily withdraw your profits through our simple and secure process.</p>
                </div>

                <div class="col-lg-5 position-relative ms-auto">
                    <!-- Year -->
                    <div class="position-absolute top-50 start-0 translate-middle ms-n2">
                        <h2 class="heading-color opacity-1 display-4 text-uppercase rotate-270">Since 2018</h2>
                    </div>
                    <!-- Image -->
                    <img src="https://cfcdn.olymptrade.com/s5/static/4feea6e4bfc13ecfaf9f18a2156f4c83/7b177/instruments_demo_bg.webp" class="rounded-3 position-relative" alt="Happy Investors">
                </div>
            </div> <!-- Row END -->
        </div>
    </section>
    <!-- =======================
    Steps END -->

    <!-- =======================
    Testimonials START -->
    <section>
        <div class="container pt-0">
            <div class="row">
                <!-- Testimonials content -->
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <span class="heading-color bg-light small rounded-3 px-3 py-2">📈 Trusted by Wealth Creators</span>
                    <!-- Title -->
                    <h2 class="my-4">Discover how we've empowered our investors</h2>

                    <h6 class="mb-0">Over 2000+ investors growing with <?=e($this->siteSettings('sitename'))?></h6>

                    <!-- Slider START -->
                    <div class="swiper mt-2 mt-md-4" data-swiper-options='{
                            "spaceBetween": 30,
                            "pagination":{
                                "el":".swiper-pagination",
                                "clickable":"true"
                            },
                            "breakpoints": { 
                                "576": {"slidesPerView": 2}
                            }}'>
                            
                        <div class="swiper-wrapper pb-5">
                            <!-- Review item -->
                            <div class="swiper-slide">
                                <div class="card bg-transparent h-100">
                                    <div class="card-body p-0">
                                        <!-- Rating star -->
                                        <ul class="list-inline mb-2">
                                            <li class="list-inline-item me-0"><i class="fas fa-star text-warning"></i></li>
                                            <li class="list-inline-item me-0"><i class="fas fa-star text-warning"></i></li>
                                            <li class="list-inline-item me-0"><i class="fas fa-star text-warning"></i></li>
                                            <li class="list-inline-item me-0"><i class="fas fa-star text-warning"></i></li>
                                            <li class="list-inline-item me-0"><i class="fas fa-star-half-alt text-warning"></i></li>
                                        </ul>
                                        <!-- Content -->
                                        <p class="heading-color fw-normal"><?=e($this->siteSettings('sitename'))?>’s investment platform has transformed my portfolio with its intuitive tools and strategic insights.</p>
                                    </div>

                                    <div class="card-footer bg-transparent p-0">
                                        <!-- Avatar -->
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm flex-shrink-0 me-2">
                                                <img class="avatar-img rounded" src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/home/images/avatar/03.jpg" alt="avatar">
                                            </div>
                                            <p class="mb-0">By Alex Thornton</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Review item -->
                            <div class="swiper-slide">
                                <div class="card bg-transparent h-100">
                                    <div class="card-body p-0">
                                        <!-- Rating star -->
                                        <ul class="list-inline mb-2">
                                            <li class="list-inline-item me-0"><i class="fas fa-star text-warning"></i></li>
                                            <li class="list-inline-item me-0"><i class="fas fa-star text-warning"></i></li>
                                            <li class="list-inline-item me-0"><i class="fas fa-star text-warning"></i></li>
                                            <li class="list-inline-item me-0"><i class="fas fa-star text-warning"></i></li>
                                            <li class="list-inline-item me-0"><i class="fas fa-star-half-alt text-warning"></i></li>
                                        </ul>
                                        <!-- Content -->
                                        <p class="heading-color fw-normal">The best investment platform I’ve used—customizable, reliable, and packed with powerful features.</p>
                                    </div>

                                    <div class="card-footer bg-transparent p-0">
                                        <!-- Avatar -->
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm flex-shrink-0 me-2">
                                                <img class="avatar-img rounded" src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/home/images/avatar/01.jpg" alt="avatar">
                                            </div>
                                            <p class="mb-0">By Sarah Bennett</p>
                                        </div>
                                    </div>
                                </div>
                            </div>  

                            <!-- Review item -->
                            <div class="swiper-slide">
                                <div class="card bg-transparent h-100">
                                    <div class="card-body p-0">
                                        <!-- Rating star -->
                                        <ul class="list-inline mb-2">
                                            <li class="list-inline-item me-0"><i class="fas fa-star text-warning"></i></li>
                                            <li class="list-inline-item me-0"><i class="fas fa-star text-warning"></i></li>
                                            <li class="list-inline-item me-0"><i class="fas fa-star text-warning"></i></li>
                                            <li class="list-inline-item me-0"><i class="fas fa-star text-warning"></i></li>
                                            <li class="list-inline-item me-0"><i class="fas fa-star-half-alt text-warning"></i></li>
                                        </ul>
                                        <!-- Content -->
                                        <p class="heading-color fw-normal"><?=e($this->siteSettings('sitename'))?> helped me diversify my investments and achieve consistent returns with confidence.</p>
                                    </div>

                                    <div class="card-footer bg-transparent p-0">
                                        <!-- Avatar -->
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm flex-shrink-0 me-2">
                                                <img class="avatar-img rounded" src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/home/images/avatar/02.jpg" alt="avatar">
                                            </div>
                                            <p class="mb-0">By Michael Chen</p>
                                        </div>
                                    </div>
                                </div>
                            </div>  
                            
                            <!-- Review item -->
                            <div class="swiper-slide">
                                <div class="card bg-transparent h-100">
                                    <div class="card-body p-0">
                                        <!-- Rating star -->
                                        <ul class="list-inline mb-2">
                                            <li class="list-inline-item me-0"><i class="fas fa-star text-warning"></i></li>
                                            <li class="list-inline-item me-0"><i class="fas fa-star text-warning"></i></li>
                                            <li class="list-inline-item me-0"><i class="fas fa-star text-warning"></i></li>
                                            <li class="list-inline-item me-0"><i class="fas fa-star text-warning"></i></li>
                                            <li class="list-inline-item me-0"><i class="fas fa-star-half-alt text-warning"></i></li>
                                        </ul>
                                        <!-- Content -->
                                        <p class="heading-color fw-normal">I highly recommend <?=e($this->siteSettings('sitename'))?> for anyone seeking a robust and user-friendly investment solution.</p>
                                    </div>

                                    <div class="card-footer bg-transparent p-0">
                                        <!-- Avatar -->
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm flex-shrink-0 me-2">
                                                <img class="avatar-img rounded" src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/home/images/avatar/05.jpg" alt="avatar">
                                            </div>
                                            <p class="mb-0">By Emily Watson</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pagination -->
                        <div class="swiper-pagination swiper-pagination-primary position-relative text-start"></div>
                    </div>
                    <!-- Slider END -->
                </div>

                <!-- Testimonials image -->
                <div class="col-sm-10 col-lg-6 col-xl-5 position-relative ms-xl-auto">
                    <!-- Image -->
                    <img src="https://cfcdn.olymptrade.com/s5/static/376ff666bd9e575ec29e586ed02586b6/6e077/happyguys.webp" class="rounded" alt="investment-img">
                </div>
            </div>
        </div>
    </section>
    <!-- =======================
    Testimonials END -->

    <!-- =======================
    CTA START -->
    <section class="position-relative z-index-2 py-0 mb-n7">
        <div class="container position-relative">
            <div class="bg-primary rounded position-relative overflow-hidden p-4 p-sm-5">

                <!-- SVG decoration -->
                <figure class="position-absolute bottom-0 end-0 mb-n3">
                    <svg class="fill-dark opacity-5" width="400" height="145" viewBox="0 0 400 145" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0.437348 59.1519C0.291566 59.1519 0.121486 59.0549 0.0485943 58.9093C-0.0485943 58.7153 0 58.4728 0.194377 58.3515L60.0625 18.9131L70.899 31.5499C71.0205 31.6954 71.0691 31.8652 71.0205 32.035C70.9719 32.2047 70.8504 32.3503 70.6803 32.423L0.583131 59.1034C0.534537 59.1276 0.485943 59.1519 0.437348 59.1519ZM59.9167 19.8833L2.86706 57.4541L70.1701 31.8409L59.9167 19.8833Z" fill="#202124"/>
                        <path d="M0.851004 59.2979C0.63233 59.2979 0.413656 59.1281 0.389359 58.9098C0.365061 58.6673 0.510844 58.449 0.729518 58.4005L76.9253 38.7783L90.7504 55.7082L0.851004 59.2979ZM76.658 39.603L3.30501 58.4733L89.2683 55.0291L76.658 39.603Z" fill="#202124"/>
                        <path d="M70.8969 32.0817L70.168 32.0859L70.2189 40.7934L70.9478 40.7892L70.8969 32.0817Z" fill="#202124"/>
                        <path d="M72.6739 60.5583L0.851562 59.297V58.5694L71.9207 59.8064L71.7749 56.1439L72.5038 56.1196L72.6739 60.5583Z" fill="#202124"/>
                        <path d="M16.2865 52.6087L12.1719 55.7021L12.318 55.8959L16.4327 52.8025L16.2865 52.6087Z" fill="#202124"/>
                        <path d="M21.6213 50.601L17.4219 54.1704L17.5794 54.3551L21.7789 50.7856L21.6213 50.601Z" fill="#202124"/>
                        <path d="M27.4394 48.3802L23.1289 52.8901L23.3047 53.0576L27.6152 48.5476L27.4394 48.3802Z" fill="#202124"/>
                        <path d="M33.4909 46.1008L28.8633 51.4375L29.047 51.5962L33.6746 46.2596L33.4909 46.1008Z" fill="#202124"/>
                        <path d="M39.2488 43.9004L34.6523 49.9487L34.8459 50.0953L39.4424 44.047L39.2488 43.9004Z" fill="#202124"/>
                        <path d="M45.6875 41.4253L40.9922 48.2988L41.1929 48.4355L45.8883 41.562L45.6875 41.4253Z" fill="#202124"/>
                        <path d="M52.8514 38.7208L47.2773 46.6982L47.4766 46.837L53.0507 38.8596L52.8514 38.7208Z" fill="#202124"/>
                        <path d="M59.6794 36.1172L53.5391 45.0894L53.7397 45.2262L59.88 36.254L59.6794 36.1172Z" fill="#202124"/>
                        <path d="M66.3562 33.5847L59.6758 43.5107L59.8775 43.646L66.5579 33.72L66.3562 33.5847Z" fill="#202124"/>
                        <path d="M70.4459 34.1845L65.8281 41.9414L66.037 42.0653L70.6548 34.3084L70.4459 34.1845Z" fill="#202124"/>
                        <path d="M393.661 145L392.273 144.88L393.152 140.401L394.54 140.521L393.661 145ZM189.936 138.628C187.484 138.568 185.124 138.297 182.949 137.846L183.366 136.975C185.448 137.395 187.669 137.636 190.028 137.696L189.936 138.628ZM196.969 138.357L196.737 137.455C198.866 137.245 201.133 136.884 203.4 136.433L203.817 137.305C201.503 137.786 199.19 138.147 196.969 138.357ZM395.419 136.073L394.031 135.953C394.309 134.45 394.586 132.977 394.864 131.474L396.252 131.564C395.974 133.067 395.697 134.57 395.419 136.073ZM176.518 135.862C174.621 135.021 172.816 133.969 171.243 132.766L172.307 132.195C173.834 133.337 175.500 134.329 177.304 135.141L176.518 135.862ZM210.387 135.712L209.832 134.9C211.868 134.329 213.950 133.668 215.986 132.917L216.633 133.698C214.598 134.450 212.469 135.111 210.387 135.712ZM222.695 131.354L221.954 130.602C223.851 129.791 225.748 128.919 227.599 128.017L228.432 128.739C226.535 129.670 224.592 130.542 222.695 131.354ZM167.310 128.949C166.246 127.687 165.320 126.274 164.488 124.771L165.783 124.470C166.570 125.943 167.495 127.296 168.513 128.528L167.310 128.949ZM396.992 127.085L395.604 126.995C395.882 125.462 396.113 123.959 396.298 122.517L397.686 122.607C397.501 124.050 397.270 125.552 396.992 127.085ZM233.799 125.883L232.874 125.192C234.586 124.230 236.252 123.208 237.825 122.216L237.917 122.156L238.889 122.787L238.797 122.847C237.270 123.869 235.558 124.891 233.799 125.883ZM162.590 120.382C162.128 118.940 161.804 117.437 161.619 115.874L163.007 115.814C163.192 117.347 163.516 118.819 163.932 120.232L162.590 120.382ZM243.794 119.541L242.776 118.910C244.303 117.858 245.876 116.745 247.449 115.603L248.467 116.204C246.894 117.377 245.321 118.489 243.794 119.541ZM398.334 118.098L396.946 118.038C397.131 116.505 397.316 115.002 397.455 113.559L398.843 113.619C398.704 115.062 398.519 116.565 398.334 118.098ZM253.094 112.838L252.030 112.237C253.464 111.155 254.945 110.042 256.518 108.810L257.582 109.381C256.009 110.614 254.528 111.756 253.094 112.838ZM162.822 111.335L161.434 111.305C161.480 109.862 161.619 108.329 161.896 106.766L163.285 106.856C163.007 108.419 162.868 109.922 162.822 111.335ZM399.213 109.081L397.825 109.051C397.918 107.548 398.010 106.015 398.057 104.542L399.445 104.572C399.398 106.045 399.306 107.578 399.213 109.081ZM262.024 105.894L260.960 105.323C262.440 104.151 263.875 103.009 265.355 101.837L266.419 102.408C264.939 103.580 263.504 104.752 262.024 105.894ZM164.210 102.408L162.822 102.257C163.007 101.476 163.238 100.664 163.470 99.8528C163.655 99.1615 163.886 98.5002 164.071 97.8089L165.413 97.9892C165.182 98.6806 164.996 99.3418 164.811 100.033C164.580 100.845 164.395 101.656 164.210 102.408ZM398.149 100.063C398.149 98.5603 398.103 97.0274 398.010 95.5545L399.398 95.5245C399.491 96.9973 399.491 98.5303 399.537 100.033L398.149 100.063ZM270.815 98.9210L269.751 98.3499C271.232 97.1777 272.666 96.0054 274.146 94.8632L275.211 95.4343C273.730 96.5765 272.249 97.7488 270.815 98.9210ZM166.940 93.6308L165.598 93.4204C166.107 92.0377 166.662 90.5949 167.264 89.0319L168.606 89.2724C168.004 90.8054 167.449 92.2181 166.940 93.6308ZM279.606 91.9476L278.542 91.3765C280.115 90.1441 281.596 89.0019 283.030 87.9198L284.094 88.5209C282.660 89.5730 281.179 90.7152 279.606 91.9476ZM397.686 91.0759C397.548 89.5730 397.362 88.0701 397.131 86.5972L398.519 86.5070C398.751 87.9799 398.936 89.5129 399.074 91.0158L397.686 91.0759ZM288.629 85.1244L287.611 84.5232C289.184 83.3810 290.757 82.2388 292.284 81.1868L293.302 81.8180C291.775 82.8700 290.202 83.9822 288.629 85.1244ZM170.410 84.9140L169.068 84.6735C169.670 83.2307 170.317 81.7879 170.919 80.3151L172.261 80.5556C171.659 82.0284 171.012 83.4712 170.410 84.9140ZM396.298 82.1486C396.252 81.9082 396.206 81.6677 396.159 81.4272C395.882 80.1648 395.512 78.9324 395.095 77.7602L396.437 77.5498C396.854 78.7521 397.224 80.0145 397.501 81.3070C397.548 81.5475 397.594 81.7879 397.640 82.0284L396.298 82.1486ZM298.114 78.5717L297.143 77.9405C298.808 76.8584 300.520 75.7763 302.140 74.7844L303.111 75.4457C301.446 76.4376 299.780 77.4896 298.114 78.5717ZM174.158 76.2272L172.816 75.9867C173.464 74.5440 174.065 73.1012 174.713 71.6584L176.055 71.8988C175.453 73.3416 174.806 74.7844 174.158 76.2272ZM393.198 73.4619C392.412 72.0491 391.440 70.6665 390.422 69.4040L391.625 68.9832C392.689 70.2757 393.661 71.6884 394.494 73.1613L393.198 73.4619ZM308.201 72.4399L307.276 71.7486C309.080 70.7566 310.885 69.7647 312.643 68.8630L313.522 69.5844C311.764 70.4861 309.959 71.4480 308.201 72.4399ZM177.859 67.5404L176.518 67.3000C177.165 65.7369 177.721 64.3242 178.230 62.9415L179.571 63.1520C179.062 64.5346 178.507 65.9774 177.859 67.5404ZM319.074 66.9092L318.288 66.1578C320.185 65.2861 322.174 64.4444 324.118 63.6329L324.858 64.3843C322.868 65.1959 320.971 66.0375 319.074 66.9092ZM386.674 65.7069C385.240 64.5346 383.667 63.4525 382.001 62.4907L382.926 61.7993C384.685 62.7913 386.304 63.9034 387.785 65.1358L386.674 65.7069ZM330.781 62.1600L330.133 61.3785C330.364 61.2884 330.595 61.1982 330.873 61.1381C332.724 60.5068 334.575 59.9057 336.425 59.3947L336.981 60.2363C335.176 60.7473 333.325 61.3184 331.521 61.9496C331.243 62.0097 331.012 62.0699 330.781 62.1600ZM376.449 59.9658C374.505 59.2444 372.423 58.6432 370.249 58.1924L370.665 57.3207C372.932 57.8016 375.107 58.4028 377.143 59.1542L376.449 59.9658ZM181.191 58.7334L179.849 58.5531C180.358 57.0502 180.821 55.5473 181.237 54.1345L182.625 54.2848C182.163 55.7276 181.700 57.2305 181.191 58.7334ZM343.366 58.6432L342.949 57.7716C345.217 57.3207 347.530 56.9299 349.751 56.6594L349.982 57.5612C347.808 57.8317 345.587 58.1924 343.366 58.6432ZM363.632 57.2606C361.457 57.0802 359.144 57.0201 356.830 57.0802L356.784 56.1785C359.190 56.1184 361.550 56.1785 363.817 56.3588L363.632 57.2606ZM183.689 49.8362L182.301 49.7160C182.625 48.2131 182.903 46.7102 183.134 45.2674L184.522 45.3576C184.291 46.8004 184.013 48.3033 183.689 49.8362ZM184.985 40.8188L183.597 40.7888C183.643 39.8570 183.689 38.9252 183.689 38.0535C183.689 37.4824 183.689 36.9113 183.643 36.3101L185.031 36.2801C185.031 36.8512 185.077 37.4523 185.077 38.0234C185.077 38.9552 185.031 39.8870 184.985 40.8188ZM75.8812 36.6708L74.8633 36.0396C76.2976 35.0777 77.7783 33.9956 79.4902 32.7032L80.5544 33.3043C78.8425 34.5968 77.3156 35.7090 75.8812 36.6708ZM183.227 31.8315C182.995 30.3286 182.671 28.8557 182.301 27.4129L183.689 27.2626C184.106 28.7355 184.383 30.2384 184.615 31.7714L183.227 31.8315ZM85.0889 29.8777L84.0247 29.3066C85.1814 28.4049 86.3844 27.4731 87.5874 26.5112L88.4665 25.8199L89.5307 26.3910L88.6516 27.0823C87.4486 28.0141 86.2456 28.9459 85.0889 29.8777ZM180.821 23.0545C180.451 22.1528 180.034 21.2510 179.618 20.3493C179.340 19.8383 179.062 19.2973 178.785 18.7863L180.080 18.4556C180.404 18.9967 180.682 19.5077 180.960 20.0487C181.422 20.9505 181.839 21.8823 182.209 22.7840L180.821 23.0545ZM93.9264 22.9343L92.8622 22.3632C94.5279 21.1007 96.0085 19.9585 97.3966 18.9366L98.4608 19.5377C97.0727 20.5296 95.5921 21.6418 93.9264 22.9343ZM103.088 16.1712L102.070 15.5701C103.736 14.3978 105.355 13.3157 106.928 12.3238L107.900 12.9550C106.373 13.9169 104.753 14.9990 103.088 16.1712ZM175.916 14.7585C174.806 13.4359 173.603 12.1735 172.261 11.0012L173.371 10.4602C174.759 11.6625 176.009 12.9851 177.165 14.3076L175.916 14.7585ZM112.989 9.91915L112.064 9.22782C113.915 8.20585 115.766 7.24399 117.524 6.40236L118.357 7.12376C116.598 7.96538 114.794 8.89718 112.989 9.91915ZM167.819 7.63474C166.153 6.58271 164.441 5.62085 162.590 4.77923L163.377 4.02778C165.274 4.89946 167.079 5.89138 168.791 6.97347L167.819 7.63474ZM124.094 4.68906L123.400 3.90755C125.482 3.12604 127.611 2.43470 129.693 1.89366L130.202 2.73528C128.212 3.24627 126.176 3.90755 124.094 4.68906ZM156.668 2.58499C154.586 1.98383 152.411 1.53296 150.190 1.26244L150.468 0.390755C152.735 0.691335 155.002 1.14221 157.223 1.77342L156.668 2.58499ZM136.726 1.41273L136.448 0.541045C138.762 0.210406 141.121 0.030058 143.435 0L143.481 0.901741C141.260 0.931799 138.993 1.11215 136.726 1.41273Z" fill="#202124"/>
                    </svg>
                </figure>

                <div class="row g-4 position-relative">
                    <!-- Title and inputs -->
                    <div class="col-lg-6">
                        <!-- Title -->
                        <h3 class="text-white">Let’s discuss your financial growth strategy</h3>

                        <!-- Search -->
                        <div class="col-md-10 bg-body rounded-2 p-2 mt-4">
                            <div class="input-group">
                                <input class="form-control focus-shadow-none border-0 me-1" type="email" placeholder="Your email address">
                                <button type="button" class="btn btn-dark rounded-2">Get Started</button>
                            </div>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="col-lg-5 col-xl-4 ms-auto text-lg-end">
                        <ul class="list-group list-group-borderless mb-0">
                            <li class="list-group-item mb-0">
                                <a href="<?=$this->siteUrl()?>" class="text-white fw-normal">
                                    <i class="bi bi-link me-1"></i> Link: <?=e($this->siteSettings('sitename'))?>
                                </a>
                            </li>
                            <li class="list-group-item text-white fw-normal mb-0">
                                <i class="bi bi-clock me-1"></i> Hours: 8am to 6pm (Mon-Fri)
                            </li>
                            <li class="list-group-item mb-0">
                                <a href="mailto:<?=e($this->siteSettings('email_address'))?>" class="text-white fw-normal">
                                    <i class="bi bi-envelope me-1"></i> Email: <?=e($this->siteSettings('email_address'))?>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div> <!-- Row END -->
            </div>
        </div>
    </section>
    <!-- =======================
    CTA END -->
</main>
<!-- **************** MAIN CONTENT END **************** -->
    