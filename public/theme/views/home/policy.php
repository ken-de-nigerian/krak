<?php
defined('FIR') OR exit();
/**
 * The template for displaying the footer section
 */
?>
<!--==============================
Breadcumb
============================== -->
<div class="breadcumb-wrapper space" style="padding-bottom: 0px;">
    <!-- bg animated image/ -->   
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="breadcumb-content">
                    <h1 class="breadcumb-title">Privacy & Policy</h1>
                    <ul class="breadcumb-menu">
                        <li><a href="<?=$this->siteUrl()?>">Home</a></li>
                        <li class="active">Privacy & Policy</li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-6 d-lg-block d-none">
                <div class="breadcumb-thumb">
                    <img src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/home/img/normal/breadcrumb-thumb.png" alt="img">
                </div>            
            </div>
        </div>
    </div>
</div>

<!-- ================================= About Section Start =============================== -->
<section class="about-one space">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-12">
                <div class="terms__conditions-content">
                    <div class="privacy__item">
                        <h2 class="section__title mb-30">Privacy Policy Agreement</h2>
                        <p>
                            Companies or websites that handle customer information are required by law and third parties to publish their Privacy Policies on their business websites. If you own a website, web app, mobile app or
                            <a class="link" href="#!">desktop app</a> that collects or processes user data, you most certainly will have to post a Privacy Policy on your website (or give in-app access to the full Privacy Policy
                            agreement).
                        </p>
                        <p>
                            Privacy is not a new concept. Humans have always desired privacy in their social as well as private lives. But the idea of privacy as a human right is a relatively modern phenomenon.
                        </p>
                    </div>

                    <div class="privacy__item">
                        <h3 class="info__title">Here are some of the main reasons:</h3>
                        <ul class="icon__list">
                            <li>
                                <span class="list_item_icon">
                                    <i class="fas fa-circle"></i>
                                </span>
                                <span class="list__item-text">Required by the law</span>
                            </li>
                            <li>
                                <span class="list_item_icon">
                                    <i class="fas fa-circle"></i>
                                </span>
                                <span class="list__item-text">Required by third party services</span>
                            </li>
                            <li>
                                <span class="list_item_icon">
                                    <i class="fas fa-circle"></i>
                                </span>
                                <span class="list__item-text">Increases Transparency</span>
                            </li>
                            <li>
                                <span class="list_item_icon">
                                    <i class="fas fa-circle"></i>
                                </span>
                                <span class="list__item-text">Let's take a look at each of these reasons in more depth.</span>
                            </li>
                        </ul>
                    </div>

                    <div class="privacy__item">
                        <h3 class="info__title">What we collect</h3>
                        <p>
                            Apart from governing laws, some websites like Apple, Amazon, and <a class="link" href="#!">Google require website</a> and app owners to post a Privacy Policy agreement if they use any of their
                            services. Many websites and apps use in-page/in-app advertising by third parties to generate revenue. As these ads also collect user data, third parties require the websites or apps to ask their
                            users' permission for sharing their personal data.
                        </p>
                        <p>
                            Some of the most popular third party services require website and app owners to post Privacy Policy agreements on their websites. Some of these services include:
                        </p>
                    </div>

                    <div class="privacy__item">
                        <h3 class="info__title">Best terms and conditions page possible :</h3>
                        <ul class="icon__list">
                            <li>
                                <span class="list_item_icon">
                                    <i class="fas fa-circle"></i>
                                </span>
                                <span class="list__item-text">
                                    <strong>Abusive users :</strong> Terms and Conditions agreements allow you to establish what constitutes appropriate activity on your site or app, empowering you to remove abusive users and
                                    content
                                </span>
                            </li>
                            <li>
                                <span class="list_item_icon">
                                    <i class="fas fa-circle"></i>
                                </span>
                                <span class="list__item-text">
                                    <strong>Intellectual property theft : </strong> Asserting your claim to the creative assets of your site in your terms and conditions will prevent ownership disputes and copyright
                                    infringement.
                                </span>
                            </li>
                            <li>
                                <span class="list_item_icon">
                                    <i class="fas fa-circle"></i>
                                </span>
                                <span class="list__item-text">
                                    <strong>Potential litigation :</strong> If a user lodges a legal complaint against your business, showing that they were presented with clear terms and conditions before they used .
                                </span>
                            </li>
                        </ul>
                    </div>

                    <div class="privacy__item">
                        <h3 class="info__title">To Set Liabilities Limits</h3>
                        <p>
                            Almost every terms and conditions agreement has a warranty or limitations of liability disclaimer. We’ll cover it in more detail in our section about what
                            <a class="link" href="#!">clauses to include in your terms and conditions</a>, but this clause essentially limits what customers can hold you liable for.
                        </p>
                    </div>

                    <div class="privacy__item">
                        <h3 class="info__title">Most companies restrict liability for:</h3>
                        <ul class="icon__list">
                            <li>
                                <span class="list__item-text">1. Inaccuracies and errors</span>
                            </li>
                            <li>
                                <span class="list__item-text">2. Lack of enjoyment</span>
                            </li>
                            <li>
                                <span class="list__item-text">3. Product or website downtime</span>
                            </li>
                            <li>
                                <span class="list__item-text">4. Viruses, spyware, and product damage</span>
                            </li>
                        </ul>
                    </div>

                    <div class="privacy__item">
                        <h3 class="info__title">Questions, comments, or report of incidents</h3>
                        <p class="mb-1">
                            You may direct questions, comments or reports to:
                        </p>
                        <p>
                            <?=e($this->siteSettings('email_address'))?>
                        </p>
                    </div>

                    <div class="privacy__item">
                        <h3 class="info__title">Reheaders to this privacy policy without notice</h3>
                        <p class="mb-0">
                            This Privacy Policy is dynamic. It will continually change. You may not assume that it remains the same and you agree to check the policy each time you visit the site for changes. Unless, in the sole
                            opinion of the website, this policy changes so drastically as to suggest a posted notification on the site or via email, you will receive no notification of changes to this Privacy Policy nor, under
                            any circumstances, does this site promise notification. Your continued use of this site always evidences your acceptance of the terms this Privacy Policy or any modifications.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ================================= About Section End =============================== -->