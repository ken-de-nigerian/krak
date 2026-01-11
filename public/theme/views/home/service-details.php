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
                <h1 class="mb-4">
                	<?=e($data['service-details']['title'])?>
                </h1>
                <p class="mb-5"><?=e($data['service-details']['short'])?></p>
            </div>
        </div>
    </section>
    <!-- =======================
    Main Banner END -->

	<!-- =======================
	Case studies detail START -->
	<section class="pt-2">
		<div class="container">
			<div class="row">
				<!-- Image -->
				<div class="col-12 mt-6">
					<div class="card h-300px h-md-400px h-xl-600px overflow-hidden" style="background:url(<?=$this->siteUrl().'/'.PUBLIC_PATH.'/'.UPLOADS_PATH?>/services/<?=e($data['service-details']['image'])?>) no-repeat; background-size:cover; background-position:center;"></div>
				</div>	

				<!-- Info -->
				<div class="col-12 mt-6">
					<div class="row">
						<!-- Project detail -->
						<div class="col-lg-12 ms-auto ps-5">
							<p class="lead"><?= htmlspecialchars_decode(e($data['service-details']['description'])) ?></p>
						</div>
					</div>
				</div>
			</div> <!-- Row END -->
		</div>
	</section>
	<!-- =======================
	Case studies detail END -->
</main>
<!-- **************** MAIN CONTENT END **************** -->