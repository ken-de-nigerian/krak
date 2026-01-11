<?php
defined('FIR') OR exit();
/**
 * The template for displaying Example Create page
 */
?>
<div class="container-fluid container-layout px-0">
    <div class="section-payment webview-fail">
        <div class="payment-main-module">
            <div class="d-flex justify-content-center align-items-center">
                <div class="status-logo">
                    <img src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/dist/images/failed.svg" />
                </div>
            </div>

            <h3>Sorry..!</h3>
            
            <h3 style="font-size: 14px;">Your Payment Was Unsuccessful</h3>

            <div class="btn-tryagin d-flex justify-content-center align-items-center">
                <a href="<?=$this->siteUrl()?>/user/dashboard" class="btn btn-lg btn-light">Home</a>
            </div>
        </div>
    </div>
</div>