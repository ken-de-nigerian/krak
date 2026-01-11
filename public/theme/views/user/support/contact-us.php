<?php
defined('FIR') OR exit();
/**
 * The template for displaying Example Create page
 */
?>
<div class="position-relative">
    <div class="containt-parent">
        <div class="main-containt">
            <!-- main-containt -->
            <div class="bg-white pxy-62 shadow" id="ticketCreate">
                <p class="mb-0 f-26 gilroy-Semibold text-uppercase text-center text-dark">NEW MESSAGE</p>
                <p class="mb-0 text-center f-14 gilroy-medium text-gray-100 dark-p mt-20">Write to us with the problem you are facing. Our team will get back to you soon.</p>

                <form id="ticket-create-form">
                    <div class="row">
                        <div class="col-12">
                            <div class="label-top new-ticket">
                                <label class="gilroy-medium text-gray-100 mb-2 f-15 mt-4 r-mt-amount">Subject</label>
                                <input type="text" class="form-control input-form-control apply-bg" name="subject" id="subject" placeholder="Enter Subject" autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <div class="label-top mt-20">
                        <style>
                            #editor {
                                height: 200px;
                            }
                            .ql-container {
                                box-sizing: border-box;
                                font-family: Helvetica, Arial, sans-serif;
                                font-size: 13px;
                                margin: 0px;
                                position: relative;
                                height: auto;
                            }
                        </style>

                        <div id="editor"></div>
                        <input type="hidden" name="description" id="description" autocomplete="off">
                        <span id="description-error" class="error"></span>
                    </div>

                    <div class="d-grid">
                        <?=$this->token()?>
                        <button type="submit" class="btn btn-lg btn-primary mt-4" id="contactBtn">
                            <span>Send Email</span>
                        </button>
                    </div>

                    <div class="d-flex justify-content-center align-items-center mt-4 back-direction">
                        <a href="<?=$this->siteUrl()?>/user/dashboard" class="text-gray gilroy-medium d-inline-flex align-items-center position-relative back-btn">
                            <svg class="position-relative nscaleX-1" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M8.47075 10.4709C8.7311 10.2105 8.7311 9.78842 8.47075 9.52807L4.94216 5.99947L8.47075 2.47087C8.7311 2.21053 8.7311 1.78842 8.47075 1.52807C8.2104 1.26772 7.78829 1.26772 7.52794 1.52807L3.52795 5.52807C3.2676 5.78842 3.2676 6.21053 3.52795 6.47088L7.52794 10.4709C7.78829 10.7312 8.2104 10.7312 8.47075 10.4709Z" fill="currentColor"/>
                            </svg>
                            <span class="ms-1 back-btn">Back</span>
                        </a>
                    </div>
                </form>
            </div>
            <!-- main-containt -->
        </div>
    </div>
</div>