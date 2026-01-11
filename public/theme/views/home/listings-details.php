<?php
defined('FIR') OR exit();
/**
 * The template for displaying the footer section
 */
?>
<!-- **************** MAIN CONTENT START **************** -->
<main>
	<!-- =======================
	Case studies detail START -->
	<section class="pt-xl-9">
		<div class="container pt-4 pt-xl-0">
			<div class="row">

				<!-- Title -->
				<div class="col-md-9 mx-auto text-center">
					<!-- Bread crumb -->
					<div class="d-flex justify-content-center position-relative mb-4">
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb breadcrumb-dots pb-0 mb-0">
								<li class="breadcrumb-item"><a href="#">Home</a></li>
								<li class="breadcrumb-item"><a href="<?=$this->siteUrl()?>/listings">Listings</a></li>
								<li class="breadcrumb-item active" aria-current="page"><?=e($data['property-details']['title'])?></li>
							</ol>
						</nav>
					</div>
					<!-- Title -->
					<h1 class="mb-4"><?=e($data['property-details']['title'])?></h1>
					<p>
						With over 6 years of experience leading major real estate companies worldwide, we utilize our expertise and network to find properties with the greatest investment potential for you. we offer you the ability to fund, rent, buy or invest in any property of your choice.
					</p>
				</div>

				<!-- Image -->
				<div class="col-12 mt-6">
					<div class="card h-300px h-md-400px h-xl-600px overflow-hidden" style="background:url(<?=$this->siteUrl().'/'.PUBLIC_PATH.'/'.UPLOADS_PATH?>/listings/<?=e($data['property-details']['image'])?>) no-repeat; background-size:cover; background-position:center;"></div>
				</div>	

				<!-- Info -->
				<div class="col-12 mt-6">
					<div class="row">
						<!-- Project info -->
						<div class="col-11 col-lg-5 col-xl-4 mx-auto mt-n8 mt-md-n9">
							<div class="card card-body bg-dark p-sm-5" data-bs-theme="dark">
								<!-- List -->
								<ul class="list-group list-group-borderless">
									<li class="list-group-item mb-3">
										<small>Amount</small>
										<p class="heading-color lead mt-1 mb-0">
											$<?=e($data['property-details']['amount'])?>
										</p>
									</li>

									<li class="list-group-item mb-3">
										<small>Location</small>
										<p class="heading-color lead mt-1 mb-0">
											<?=e($data['property-details']['location'])?>
										</p>
									</li>

									<li class="list-group-item mb-3">
										<small>Added Date</small>
										<p class="heading-color lead mt-1 mb-0">
											<?= date('M j, Y', strtotime($data['property-details']['date_added'])) ?>
										</p>
									</li>
								</ul>
							</div>
						</div>

						<!-- Project detail -->
						<div class="col-lg-7 ms-auto ps-5 mt-6 mt-lg-0">
							<h4 class="mb-3">Overview</h4>
							<p class="lead"><?=e($data['property-details']['description'])?></p>
						</div>
					</div>
				</div>
			</div> <!-- Row END -->
		</div>
	</section>
	<!-- =======================
	Case studies detail END -->

	<!-- =======================
	Contact form START -->
	<section class="pt-0">
		<div class="container">

			<!-- Title -->
			<div class="inner-container-small mx-auto">
				<div class="text-center mb-6">
					<h2 class="mb-">Make Request</h2>
					<p class="mb-0">Fill in your details below to start request process</p>
				</div>
			</div>	

			<!-- Form and pricing -->
			<div class="row">
				<div class="col-lg-8 mx-auto">
					<!-- Contact form START -->
					<form class="row g-4" id="buy-house-form">
						<div class="col-md-6">
							<label class="form-label heading-color">Amount *</label>
							<input type="text" id="amount" name="amount" class="form-control form-control-lg" placeholder="Your amount">
						</div>
			
						<div class="col-md-6">
							<label class="form-label heading-color">Email address *</label>
							<input type="email" id="email" name="email" class="form-control form-control-lg" placeholder="name@example.com">
						</div>
			
						<div class="col-md-6">
							<label class="form-label heading-color">Phone number *</label>
							<input type="text" id="phone" name="phone" class="form-control form-control-lg" placeholder="(xxx) xx xxxx">
						</div>
			
						<div class="col-md-6">
							<label class="form-label heading-color">Country *</label>
							<select name="location" id="location" class="form-control form-control-lg">
							    <option value="Afghanistan">Afghanistan</option>
							    <option value="Åland Islands">Åland Islands</option>
							    <option value="Albania">Albania</option>
							    <option value="Algeria">Algeria</option>
							    <option value="American Samoa">American Samoa</option>
							    <option value="Andorra">Andorra</option>
							    <option value="Angola">Angola</option>
							    <option value="Anguilla">Anguilla</option>
							    <option value="Antarctica">Antarctica</option>
							    <option value="Antigua and Barbuda">Antigua and Barbuda</option>
							    <option value="Argentina">Argentina</option>
							    <option value="Armenia">Armenia</option>
							    <option value="Aruba">Aruba</option>
							    <option value="Australia">Australia</option>
							    <option value="Austria">Austria</option>
							    <option value="Azerbaijan">Azerbaijan</option>
							    <option value="Bahamas">Bahamas</option>
							    <option value="Bahrain">Bahrain</option>
							    <option value="Bangladesh">Bangladesh</option>
							    <option value="Barbados">Barbados</option>
							    <option value="Belarus">Belarus</option>
							    <option value="Belgium">Belgium</option>
							    <option value="Belize">Belize</option>
							    <option value="Benin">Benin</option>
							    <option value="Bermuda">Bermuda</option>
							    <option value="Bhutan">Bhutan</option>
							    <option value="Bolivia">Bolivia</option>
							    <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
							    <option value="Botswana">Botswana</option>
							    <option value="Bouvet Island">Bouvet Island</option>
							    <option value="Brazil">Brazil</option>
							    <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
							    <option value="Brunei Darussalam">Brunei Darussalam</option>
							    <option value="Bulgaria">Bulgaria</option>
							    <option value="Burkina Faso">Burkina Faso</option>
							    <option value="Burundi">Burundi</option>
							    <option value="Cambodia">Cambodia</option>
							    <option value="Cameroon">Cameroon</option>
							    <option value="Canada">Canada</option>
							    <option value="Cape Verde">Cape Verde</option>
							    <option value="Cayman Islands">Cayman Islands</option>
							    <option value="Central African Republic">Central African Republic</option>
							    <option value="Chad">Chad</option>
							    <option value="Chile">Chile</option>
							    <option value="China">China</option>
							    <option value="Christmas Island">Christmas Island</option>
							    <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
							    <option value="Colombia">Colombia</option>
							    <option value="Comoros">Comoros</option>
							    <option value="Congo">Congo</option>
							    <option value="Congo, The Democratic Republic of The">Congo, The Democratic Republic of The</option>
							    <option value="Cook Islands">Cook Islands</option>
							    <option value="Costa Rica">Costa Rica</option>
							    <option value="Cote D'ivoire">Cote D'ivoire</option>
							    <option value="Croatia">Croatia</option>
							    <option value="Cuba">Cuba</option>
							    <option value="Cyprus">Cyprus</option>
							    <option value="Czech Republic">Czech Republic</option>
							    <option value="Denmark">Denmark</option>
							    <option value="Djibouti">Djibouti</option>
							    <option value="Dominica">Dominica</option>
							    <option value="Dominican Republic">Dominican Republic</option>
							    <option value="Ecuador">Ecuador</option>
							    <option value="Egypt">Egypt</option>
							    <option value="El Salvador">El Salvador</option>
							    <option value="Equatorial Guinea">Equatorial Guinea</option>
							    <option value="Eritrea">Eritrea</option>
							    <option value="Estonia">Estonia</option>
							    <option value="Ethiopia">Ethiopia</option>
							    <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
							    <option value="Faroe Islands">Faroe Islands</option>
							    <option value="Fiji">Fiji</option>
							    <option value="Finland">Finland</option>
							    <option value="France">France</option>
							    <option value="French Guiana">French Guiana</option>
							    <option value="French Polynesia">French Polynesia</option>
							    <option value="French Southern Territories">French Southern Territories</option>
							    <option value="Gabon">Gabon</option>
							    <option value="Gambia">Gambia</option>
							    <option value="Georgia">Georgia</option>
							    <option value="Germany">Germany</option>
							    <option value="Ghana">Ghana</option>
							    <option value="Gibraltar">Gibraltar</option>
							    <option value="Greece">Greece</option>
							    <option value="Greenland">Greenland</option>
							    <option value="Grenada">Grenada</option>
							    <option value="Guadeloupe">Guadeloupe</option>
							    <option value="Guam">Guam</option>
							    <option value="Guatemala">Guatemala</option>
							    <option value="Guernsey">Guernsey</option>
							    <option value="Guinea">Guinea</option>
							    <option value="Guinea-bissau">Guinea-bissau</option>
							    <option value="Guyana">Guyana</option>
							    <option value="Haiti">Haiti</option>
							    <option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands</option>
							    <option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
							    <option value="Honduras">Honduras</option>
							    <option value="Hong Kong">Hong Kong</option>
							    <option value="Hungary">Hungary</option>
							    <option value="Iceland">Iceland</option>
							    <option value="India">India</option>
							    <option value="Indonesia">Indonesia</option>
							    <option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option>
							    <option value="Iraq">Iraq</option>
							    <option value="Ireland">Ireland</option>
							    <option value="Isle of Man">Isle of Man</option>
							    <option value="Israel">Israel</option>
							    <option value="Italy">Italy</option>
							    <option value="Jamaica">Jamaica</option>
							    <option value="Japan">Japan</option>
							    <option value="Jersey">Jersey</option>
							    <option value="Jordan">Jordan</option>
							    <option value="Kazakhstan">Kazakhstan</option>
							    <option value="Kenya">Kenya</option>
							    <option value="Kiribati">Kiribati</option>
							    <option value="Korea, Democratic People's Republic of">Korea, Democratic People's Republic of</option>
							    <option value="Korea, Republic of">Korea, Republic of</option>
							    <option value="Kuwait">Kuwait</option>
							    <option value="Kyrgyzstan">Kyrgyzstan</option>
							    <option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option>
							    <option value="Latvia">Latvia</option>
							    <option value="Lebanon">Lebanon</option>
							    <option value="Lesotho">Lesotho</option>
							    <option value="Liberia">Liberia</option>
							    <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
							    <option value="Liechtenstein">Liechtenstein</option>
							    <option value="Lithuania">Lithuania</option>
							    <option value="Luxembourg">Luxembourg</option>
							    <option value="Macao">Macao</option>
							    <option value="Macedonia, The Former Yugoslav Republic of">Macedonia, The Former Yugoslav Republic of</option>
							    <option value="Madagascar">Madagascar</option>
							    <option value="Malawi">Malawi</option>
							    <option value="Malaysia">Malaysia</option>
							    <option value="Maldives">Maldives</option>
							    <option value="Mali">Mali</option>
							    <option value="Malta">Malta</option>
							    <option value="Marshall Islands">Marshall Islands</option>
							    <option value="Martinique">Martinique</option>
							    <option value="Mauritania">Mauritania</option>
							    <option value="Mauritius">Mauritius</option>
							    <option value="Mayotte">Mayotte</option>
							    <option value="Mexico">Mexico</option>
							    <option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
							    <option value="Moldova, Republic of">Moldova, Republic of</option>
							    <option value="Monaco">Monaco</option>
							    <option value="Mongolia">Mongolia</option>
							    <option value="Montenegro">Montenegro</option>
							    <option value="Montserrat">Montserrat</option>
							    <option value="Morocco">Morocco</option>
							    <option value="Mozambique">Mozambique</option>
							    <option value="Myanmar">Myanmar</option>
							    <option value="Namibia">Namibia</option>
							    <option value="Nauru">Nauru</option>
							    <option value="Nepal">Nepal</option>
							    <option value="Netherlands">Netherlands</option>
							    <option value="Netherlands Antilles">Netherlands Antilles</option>
							    <option value="New Caledonia">New Caledonia</option>
							    <option value="New Zealand">New Zealand</option>
							    <option value="Nicaragua">Nicaragua</option>
							    <option value="Niger">Niger</option>
							    <option value="Nigeria">Nigeria</option>
							    <option value="Niue">Niue</option>
							    <option value="Norfolk Island">Norfolk Island</option>
							    <option value="Northern Mariana Islands">Northern Mariana Islands</option>
							    <option value="Norway">Norway</option>
							    <option value="Oman">Oman</option>
							    <option value="Pakistan">Pakistan</option>
							    <option value="Palau">Palau</option>
							    <option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option>
							    <option value="Panama">Panama</option>
							    <option value="Papua New Guinea">Papua New Guinea</option>
							    <option value="Paraguay">Paraguay</option>
							    <option value="Peru">Peru</option>
							    <option value="Philippines">Philippines</option>
							    <option value="Pitcairn">Pitcairn</option>
							    <option value="Poland">Poland</option>
							    <option value="Portugal">Portugal</option>
							    <option value="Puerto Rico">Puerto Rico</option>
							    <option value="Qatar">Qatar</option>
							    <option value="Reunion">Reunion</option>
							    <option value="Romania">Romania</option>
							    <option value="Russian Federation">Russian Federation</option>
							    <option value="Rwanda">Rwanda</option>
							    <option value="Saint Helena">Saint Helena</option>
							    <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
							    <option value="Saint Lucia">Saint Lucia</option>
							    <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
							    <option value="Saint Vincent and The Grenadines">Saint Vincent and The Grenadines</option>
							    <option value="Samoa">Samoa</option>
							    <option value="San Marino">San Marino</option>
							    <option value="Sao Tome and Principe">Sao Tome and Principe</option>
							    <option value="Saudi Arabia">Saudi Arabia</option>
							    <option value="Senegal">Senegal</option>
							    <option value="Serbia">Serbia</option>
							    <option value="Seychelles">Seychelles</option>
							    <option value="Sierra Leone">Sierra Leone</option>
							    <option value="Singapore">Singapore</option>
							    <option value="Slovakia">Slovakia</option>
							    <option value="Slovenia">Slovenia</option>
							    <option value="Solomon Islands">Solomon Islands</option>
							    <option value="Somalia">Somalia</option>
							    <option value="South Africa">South Africa</option>
							    <option value="South Georgia and The South Sandwich Islands">South Georgia and The South Sandwich Islands</option>
							    <option value="Spain">Spain</option>
							    <option value="Sri Lanka">Sri Lanka</option>
							    <option value="Sudan">Sudan</option>
							    <option value="Suriname">Suriname</option>
							    <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
							    <option value="Swaziland">Swaziland</option>
							    <option value="Sweden">Sweden</option>
							    <option value="Switzerland">Switzerland</option>
							    <option value="Syrian Arab Republic">Syrian Arab Republic</option>
							    <option value="Taiwan">Taiwan</option>
							    <option value="Tajikistan">Tajikistan</option>
							    <option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
							    <option value="Thailand">Thailand</option>
							    <option value="Timor-leste">Timor-leste</option>
							    <option value="Togo">Togo</option>
							    <option value="Tokelau">Tokelau</option>
							    <option value="Tonga">Tonga</option>
							    <option value="Trinidad and Tobago">Trinidad and Tobago</option>
							    <option value="Tunisia">Tunisia</option>
							    <option value="Turkey">Turkey</option>
							    <option value="Turkmenistan">Turkmenistan</option>
							    <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
							    <option value="Tuvalu">Tuvalu</option>
							    <option value="Uganda">Uganda</option>
							    <option value="Ukraine">Ukraine</option>
							    <option value="United Arab Emirates">United Arab Emirates</option>
							    <option value="United Kingdom">United Kingdom</option>
							    <option value="United States">United States</option>
							    <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
							    <option value="Uruguay">Uruguay</option>
							    <option value="Uzbekistan">Uzbekistan</option>
							    <option value="Vanuatu">Vanuatu</option>
							    <option value="Venezuela">Venezuela</option>
							    <option value="Viet Nam">Viet Nam</option>
							    <option value="Virgin Islands, British">Virgin Islands, British</option>
							    <option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option>
							    <option value="Wallis and Futuna">Wallis and Futuna</option>
							    <option value="Western Sahara">Western Sahara</option>
							    <option value="Yemen">Yemen</option>
							    <option value="Zambia">Zambia</option>
							    <option value="Zimbabwe">Zimbabwe</option>
							</select>
						</div>

						<div class="col-12">
							<label class="form-label heading-color">Message *</label>
							<textarea class="form-control" name="message" id="message" placeholder="Write your message here...." style="height: 150px"></textarea>
						</div>

						<!-- Button -->
						<div class="col-12 d-grid">
							 <?=$this->token()?>
							<button class="btn btn-lg btn-primary mb-0" id="buyHouseBtn" data-house="<?=e($data['property-details']['propertyId'])?>">Send a message</button>
						</div>
					</form>
					<!-- Contact form END -->
				</div>
			</div>
		</div>
	</section>
	<!-- =======================
	Contact form START -->
</main>
<!-- **************** MAIN CONTENT END **************** -->