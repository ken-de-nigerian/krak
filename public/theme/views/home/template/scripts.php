<?php
defined('FIR') OR exit();
/**
 * The template for displaying the footer section
 */
?>

<!-- Resend-Otp-Code -->
<script>
    $(document).ready(function() {
        // Handler for the resend link click event
        $("#resendOtp").click(function(event) {
            event.preventDefault();
            
            // Add the spinner to the link
            $(this).html('<img src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/frontend/templates/images/2fa/refresh.svg" alt="refresh" class="img-fluid ref-light"><img src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/frontend/templates/images/2fa/refresh-dark.svg" alt="refresh" class="img-fluid ref-dark d-none"><span class="px-1">Resending...</span>');
            
            // Send an Ajax request to the resendcode action
            $.ajax({
                url: '<?=$this->siteUrl()?>/twofa/resendcode', // Update with the correct URL for the "Send Code" functionality
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    // Check the response status
                    if (response.status === 'success') {
                        // Show a success message
                        iziToast.success({
                            message: response.message,
                            position: 'topRight'
                        });
                    } else if (response.status === 'error') {
                        // Show an error message
                        iziToast.error({
                            message: response.message,
                            position: 'topRight'
                        });
                    } else if (response.status === "warning") {
                        // Show an iziToast warning notification
                        iziToast.warning({
                            message: response.message,
                            position: "topRight",
                        });
                    }
                },
                error: function (xhr, status, error) {
                    // Handle errors gracefully
                    console.log(xhr.responseText);
                    iziToast.error({
                        message: "An error occurred. Please try again.",
                        position: "topRight",
                    });
                },
                complete: function() {
                    // Remove the spinner from the link
                    $("#resendOtp").html('<img src="<?= $this->siteUrl() ?>/<?= $this->themePath() ?>/assets/frontend/templates/images/2fa/refresh.svg" alt="refresh" class="img-fluid ref-light"><img src="<?= $this->siteUrl() ?>/<?= $this->themePath() ?>/assets/frontend/templates/images/2fa/refresh-dark.svg" alt="refresh" class="img-fluid ref-dark d-none"><span class="px-1">Resend code</span>');
                }
            });
        });
    });
</script>

<!-- Confirm otpCode -->
<script>
    $(document).ready(function() {
        // Handler for the forgot button click event
        $("#2faVerifyCode").click(function(event) {
            // Prevent form submission if the code field is empty
            var codeValue = $("#code").val().trim();
            if (codeValue === '') {
                event.preventDefault();
                // Add the 'is-invalid' class to the code input field
                $("#code").addClass("is-invalid");
                return;
            }

            // Add the spinner to the button
            $("#2faVerifyCode").html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Processing...</span>');
            // Disable the button
            $("#2faVerifyCode").prop("disabled", true);

            // Send an Ajax request to the forgot password controller
            $.ajax({
                url: '<?=$this->siteUrl()?>/twofa/verify', // Update with the correct URL for the "Reset Password" functionality
                type: 'POST',
                data: $('#2fa-verification-form').serialize(),
                dataType: 'json',
                success: function(response) {
                    // Check the response status
                    if (response.status === 'success') {
                        // Redirect
                        window.location.href = '<?=$this->siteUrl()?>/' + response.redirect;
                    } else if (response.status === 'error') {
                        // The forgot password request failed, show an iziToast error notification
                        iziToast.error({
                            message: response.message,
                            position: 'topRight'
                        });
                    }
                },
                error: function (xhr, status, error) {
                    // Handle errors gracefully
                    console.log(xhr.responseText);
                    iziToast.error({
                        message: "An error occurred. Please try again.",
                        position: "topRight",
                    });
                },
                complete: function() {
                    // Remove the spinner from the button and enable the button
                    $("#2faVerifyCode").html("Submit");
                    $("#2faVerifyCode").prop("disabled", false);
                }
            });
        });

        // Add event listener for input fields to remove 'is-invalid' class on input
        $("#code").on("input", function() {
            $(this).removeClass("is-invalid");
        });
    });
</script>

<!-- Phone Number Validates -->
<script>
    $(document).ready(function() {
        // Check if the phone input field exists
        const phoneInput = $("#phone");
        if (phoneInput.length > 0) {
            // Initialize the intl-tel-input plugin on the phone input field
            const input = document.querySelector("#phone");
            const iti = window.intlTelInput(input, {
                utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.12/js/utils.js",
            });

            // Update country name on input changes
            input.addEventListener("input", function() {
                updateCountryName();
                validatePhoneNumber();
            });

            // Listen for changes in the selected country
            input.addEventListener("countrychange", function() {
                updateHiddenInputs();
                validatePhoneNumber();
            });

            function updateHiddenInputs() {
                const countryName = iti.getSelectedCountryData().name;
                // Set the value of the hidden input field to the selected country's name
                $("#country").val(countryName);
            }

            function updateCountryName() {
                // Get the country based on the entered phone number
                const country = iti.getSelectedCountryData().name;
                // Set the value of the hidden input field to the selected country's name
                $("#country").val(country);
            }

            function validatePhoneNumber() {
                // Get the formatted international version of the phone number
                const formattedPhoneNumber = iti.getNumber(intlTelInputUtils.numberFormat.E164);
                // Set the value of the hidden input field to the formatted phone number
                $("#formattedPhone").val(formattedPhoneNumber);

                const isValid = iti.isValidNumber();
                if (!isValid) {
                    // Display an error message or provide feedback to the user
                    $("#phone-error").text("Please enter a valid international phone number.").addClass("error");
                } else {
                    // Clear the error message if the phone number is valid
                    $("#phone-error").text("");
                }
                // Enable or disable the submitted button based on the validity of the phone number
                $("#registerBtn").prop("disabled", !isValid);
            }
        }
    });
</script>

<!-- Forgot-Password Ajax -->
<script>
    $(document).ready(function() {
        // Handler for the forgot button click event
        $("#forgotBtn").click(function(event) {
            // Prevent form submission if the email field is empty
            const emailValue = $("#email").val().trim();
            if (emailValue === '') {
                event.preventDefault();
                // Add the 'is-invalid' class to the email input field
                $("#email").addClass("is-invalid");
                return;
            }

            // Add the spinner to the button
            $("#forgotBtn").html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Processing...</span>');

            // Disable the button
            $("#forgotBtn").prop("disabled", true);

            // Send an Ajax request to the forgot password controller
            $.ajax({
                url: '<?= $this->siteUrl() ?>/forgot', // Update with the correct URL for the "Forgot Password" functionality
                type: 'POST',
                data: $('#forgot-form').serialize(),
                dataType: 'json',
                success: function(response) {
                    // Check the response status
                    if (response.status === 'success') {
                        /// Show a success message
                        iziToast.success({
                            message: response.message,
                            position: 'topRight'
                        });

                        // Redirect after a delay (optional)
                        setTimeout(function () {
                            window.location.href = '<?=$this->siteUrl()?>/' + response.redirect;
                        }, 3000); // Redirect after 3 seconds
                    } else if (response.status === 'error') {
                        // The forgot password request failed, show an iziToast error notification
                        iziToast.error({
                            message: response.message,
                            position: 'topRight'
                        });
                    }
                },
                error: function (xhr) {
                    // Handle errors gracefully
                    console.log(xhr.responseText);
                    iziToast.error({
                        message: "An error occurred. Please try again.",
                        position: "topRight",
                    });
                },
                complete: function() {
                    // Remove the spinner from the button and enable the button
                    $("#forgotBtn").html("Send");
                    $("#forgotBtn").prop("disabled", false);
                }
            });
        });

        // Add event listener for input fields to remove 'is-invalid' class on input
        $("#email").on("input", function() {
            $(this).removeClass("is-invalid");
        });
    });
</script>

<!-- Reset Password Ajax -->
<script>
    $(document).ready(function() {
	    // Handler for the reset password button click event
	    $("#resetPasswordBtn").click(function(event) {
	        event.preventDefault();

            // Retrieve the id and token from the data attribute
            const idValue = $(this).data("id");

            const tokenValue = $(this).data("token");

            // Validate password and confirm password
            const passwordValue = $("#password").val().trim();
            const confirmPasswordValue = $("#confirmPassword").val().trim();

            // Add the 'is-invalid' class to the empty fields
	        if (passwordValue === '') {
	            $("#password").addClass("is-invalid");
	        }
	        if (confirmPasswordValue === '') {
	            $("#confirmPassword").addClass("is-invalid");
	        }

	       	// Check if any field is empty
	        if (passwordValue === '' || confirmPasswordValue === '') {
	            return;
	        }

	        // Add the spinner to the button
	        $("#resetPasswordBtn").html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Processing...</span>');

	        // Disable the button
	        $("#resetPasswordBtn").prop("disabled", true);

	        // Send an Ajax request to the reset password controller
            $.ajax({
                url: '<?=$this->siteUrl()?>/reset?id=' + idValue + '&reset=' + tokenValue,
                type: 'POST',
                data: $('#resetPasswordForm').serialize(),
                dataType: 'json',
                success: function (response) {
                    // Check the response status
                    if (response.status === 'success') {
                        // The setup was successful, redirect to the appropriate dashboard URL
                        window.location.href = '<?=$this->siteUrl()?>/' + response.redirect;
                    } else if (response.status === 'error') {
                        /// Show an error message
                        iziToast.error({
                            message: response.message,
                            position: 'topRight'
                        });
                    } else if (response.status === 'warning') {
                        // Show an iziToast warning notification
                        iziToast.warning({
                            message: response.message,
                            position: 'topRight'
                        });

                        // Redirect after a delay (optional)
                        setTimeout(function () {
                            window.location.href = '<?=$this->siteUrl()?>/' + response.redirect;
                        }, 3000); // Redirect after 3 seconds
                    }
                },
                error: function (xhr) {
                    // Handle errors gracefully
                    console.log(xhr.responseText);
                    iziToast.error({
                        message: "An error occurred. Please try again.",
                        position: "topRight",
                    });
                },
                complete: function () {
                    // Remove the spinner from the button and enable the button
                    $("#resetPasswordBtn").html("Reset Password");
                    $("#resetPasswordBtn").prop("disabled", false);
                }
            });
	    });

	    // Add event listeners to remove 'is-invalid' class on input
	    $("#password").on("input", function() {
	        $(this).removeClass("is-invalid");
	    });

	    $("#confirmPassword").on("input", function() {
	        $(this).removeClass("is-invalid");
	    });
	});
</script>

<!-- Login Ajax -->
<script>
    $(document).ready(function() {
	    // Handler for the login button click event
	    $("#loginBtn").click(function(event) {
	        // Check if the email or password fields are empty
            const emailValue = $("#email").val().trim();
            const passwordValue = $("#password").val().trim();
            let isValid = true;

            if (emailValue === '') {
	            // Add the 'is-invalid' class to the email input field
	            $("#email").addClass("is-invalid");
	            isValid = false;
	        } else {
	            // Remove the 'is-invalid' class from the email input field
	            $("#email").removeClass("is-invalid");
	        }

	        if (passwordValue === '') {
	            // Add the 'is-invalid' class to the password input field
	            $("#password").addClass("is-invalid");
	            isValid = false;
	        } else {
	            // Remove the 'is-invalid' class from the password input field
	            $("#password").removeClass("is-invalid");
	        }

	        if (!isValid) {
	            event.preventDefault(); // Prevent form submission
	            return;
	        }

            // Function to clear error messages
            function clearError(elementId) {
                $(elementId).text('').removeClass('error');
            }

            // Add event listeners to input fields to clear error messages on input change
            $('#email').on('input', function() {
                clearError('#email-error');
            });

	        // Add the spinner to the button
	        $("#loginBtn").html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Processing...</span>');

	        // Disable the button
	        $("#loginBtn").prop("disabled", true);

	        // Send an Ajax request to the login controller
	        $.ajax({
	            url: '<?= $this->siteUrl() ?>/login', // Modify the URL according to your setup
	            type: 'POST',
	            data: $('#login').serialize(),
	            dataType: 'json',
	            success: function(response) {
	                // Check the response status
	                if (response.status === 'success') {
                        // The setup was successful, redirect to the appropriate dashboard URL
                        window.location.href = '<?=$this->siteUrl()?>/' + response.redirect;
                    } else if (response.status === 'error') {
                        // Check the error message to determine the type of error
                        if (response.message.includes('email')) {
                            $("#email-error").text(response.message).addClass("error");
                        } else {
                            // Show an iziToast error notification
                            iziToast.error({
                                message: response.message,
                                position: 'topRight'
                            });
                        }
                    }
	            },
	            error: function (xhr) {
	                // Handle errors gracefully
	                console.log(xhr.responseText);
                    iziToast.error({
                        message: "An error occurred. Please try again.",
                        position: "topRight",
                    });
	            },
	            complete: function() {
	                // Remove the spinner from the button and enable the button
	                $("#loginBtn").html("Sign in");
	                $("#loginBtn").prop("disabled", false);
	            }
	        });
	    });

	    // Add event listener for input fields to remove 'is-invalid' class on input
	    $("#email, #password").on("input", function() {
	        $(this).removeClass("is-invalid");
	    });
	});
</script>

<!-- Register Ajax -->
<script>
    $(document).ready(function() {
        // Handler for the register button click event
        $("#registerBtn").click(function(event) {
            event.preventDefault(); // Prevent form submission

            // Validate email, password
            const firstValue = $("#firstname").val().trim();
            const lastnameValue = $("#lastname").val().trim();
            const emailValue = $("#email").val().trim();
            const phoneValue = $("#phone").val().trim();
            const passwordValue = $("#password").val().trim();
            const confirmPasswordValue = $("#confirmPassword").val().trim();

            if (firstValue === '') {
                // Add the 'is-invalid' class to the firstname input field
                $("#firstname").addClass("is-invalid");
            } else {
                // Remove the 'is-invalid' class from the firstname input field
                $("#firstname").removeClass("is-invalid");
            }

            if (lastnameValue === '') {
                // Add the 'is-invalid' class to the lastname input field
                $("#lastname").addClass("is-invalid");
            } else {
                // Remove the 'is-invalid' class from the lastname input field
                $("#lastname").removeClass("is-invalid");
            }

            if (emailValue === '') {
                // Add the 'is-invalid' class to the email input field
                $("#email").addClass("is-invalid");
            } else {
                // Remove the 'is-invalid' class from the email input field
                $("#email").removeClass("is-invalid");
            }

            if (phoneValue === '') {
                // Add the 'is-invalid' class to the phone input field
                $("#phone").addClass("is-invalid");
            } else {
                // Remove the 'is-invalid' class from the phone input field
                $("#phone").removeClass("is-invalid");
            }

            if (passwordValue === '') {
                // Add the 'is-invalid' class to the password input field
                $("#password").addClass("is-invalid");
            } else {
                // Remove the 'is-invalid' class from the password input field
                $("#password").removeClass("is-invalid");
            }

            if (confirmPasswordValue === '') {
                // Add the 'is-invalid' class to the confirmPassword input field
                $("#confirmPassword").addClass("is-invalid");
            } else {
                // Remove the 'is-invalid' class from the confirmPassword input field
                $("#confirmPassword").removeClass("is-invalid");
            }

            // Check if any field is empty
            if (firstValue === '' || lastnameValue === '' || emailValue === '' || passwordValue === '' || confirmPasswordValue === '') {
                return;
            }

            // Function to clear error messages
            function clearError(elementId) {
                $(elementId).text('').removeClass('error');
            }

            $('#password').on('input', function() {
                clearError('#password-error');
            });

            $('#email').on('input', function() {
                clearError('#email-error');
            });

            // Add the spinner to the button
            $("#registerBtn").html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Processing...</span>');

            // Disable the button
            $("#registerBtn").prop("disabled", true);

            // Send an Ajax request to the register controller
            $.ajax({
                url: '<?= $this->siteUrl() ?>/register', // Modify the URL according to your setup
                type: 'POST',
                data: $('#register-form').serialize(),
                dataType: 'json',
                success: function(response) {
                    // Check the response status
                    if (response.status === 'success') {
                        // The registration was successful, redirect to the redirect URL specified in the JSON object
                        window.location.href = '<?=$this->siteUrl()?>/' + response.redirect;
                    } else if (response.status === 'error') {
                        // Check the error message to determine the type of error
                        if (response.message.includes('phone')) {
                            $("#phone-error").text(response.message).addClass("error");
                        } else if (response.message.includes('password')) {
                            $("#password-error").text(response.message).addClass("error");
                        } else if (response.message.includes('email')) {
                            $("#email-error").text(response.message).addClass("error");
                        } else {
                            // Show an iziToast error notification
                            iziToast.error({
                                message: response.message,
                                position: 'topRight'
                            });
                        }
                    }
                },
                error: function(xhr) {
                    // Handle errors gracefully
                    console.log(xhr.responseText);
                    iziToast.error({
                        message: "An error occurred. Please try again.",
                        position: "topRight",
                    });
                },
                complete: function() {
                    // Remove the spinner from the button and enable the button
                    $("#registerBtn").html("Continue");
                    $("#registerBtn").prop("disabled", false);
                }
            });
        });

        // Add event listener for input fields to remove 'is-invalid' class on input
        $("#firstname, #lastname, #email, #password, #confirmPassword").on("input", function() {
            $(this).removeClass("is-invalid");
        });
    });
</script>

<!-- Complete-setup Ajax -->
<script>
    $(document).ready(function() {
        // Handler for the setup button click event
        $("#setupBtn").click(function(event) {
            event.preventDefault(); // Prevent form submission

            // Add the spinner to the button
            $("#setupBtn").html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Processing...</span>');
            $("#setupBtn").prop("disabled", true); // Disable the button

            // Send an Ajax request to the setup controller
            $.ajax({
                url: '<?= $this->siteUrl() ?>/setup', // Modify the URL according to your setup
                type: 'POST',
                data: $('#setup-form').serialize(),
                dataType: 'json',
                success: function(response) {
                    // Check the response status
                    if (response.status === 'success') {
                        // The setup was successful, redirect to the appropriate dashboard URL
                        window.location.href = '<?=$this->siteUrl()?>/' + response.redirect;
                    } else if (response.status === 'error') {
                        /// Show an error message
                        iziToast.error({
                            message: response.message,
                            position: 'topRight'
                        });
                    } else if (response.status === 'warning') {
                        // Show an iziToast warning notification
                        iziToast.warning({
                            message: response.message,
                            position: 'topRight'
                        });

                        // Redirect after a delay (optional)
                        setTimeout(function () {
                            window.location.href = '<?=$this->siteUrl()?>/' + response.redirect;
                        }, 3000); // Redirect after 3 seconds
                    }
                },
                error: function(xhr) {
                    // Handle errors gracefully
                    console.log(xhr.responseText);
                    iziToast.error({
                        message: "An error occurred. Please try again.",
                        position: "topRight",
                    });
                },
                complete: function() {
                    // Remove the spinner from the button and enable the button
                    $("#setupBtn").html("Submit For Review");
                    $("#setupBtn").prop("disabled", false);
                }
            });
        });
    });
</script>

<!--Buy House Template Ajax -->
<script>
    $(document).ready(function () {
        // Handler for the edit button click event
        $("#buyHouseBtn").click(function (event) {
            event.preventDefault(); // Prevent form submission

            // Retrieve the project ID from the data attribute
            const houseValue = $(this).data("house");

            if (!houseValue) {
                console.error("House is not set in the data attribute.");
                return;
            }

            // Validate inputs
            let valid = true;

            // amount validation
            const amountValue = $("#amount").val().trim();
            if (amountValue === "") {
                $("#amount").addClass("is-invalid");
                valid = false;
            } else {
                $("#amount").removeClass("is-invalid");
            }

            const emailValue = $("#email").val().trim();
            if (emailValue === "") {
                $("#email").addClass("is-invalid");
                valid = false;
            } else {
                $("#email").removeClass("is-invalid");
            }

            const phoneValue = $("#phone").val().trim();
            if (phoneValue === "") {
                $("#phone").addClass("is-invalid");
                valid = false;
            } else {
                $("#phone").removeClass("is-invalid");
            }

            const messageValue = $("#message").val().trim();
            if (messageValue === "") {
                $("#message").addClass("is-invalid");
                valid = false;
            } else {
                $("#message").removeClass("is-invalid");
            }

            if (!valid) {
                return;
            }

            // Add the spinner to the button
            $("#buyHouseBtn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
            $("#buyHouseBtn").prop("disabled", true); // Disable the button

            // Send an Ajax request to the edit controller
            $.ajax({
                url: "<?=$this->siteUrl()?>/listings/details/" + houseValue, // Modify the URL according to your edit
                type: 'POST',
                data: $('#buy-house-form').serialize(),
                dataType: 'json',
                success: function (response) {
                    // Check the response status
                    if (response.status === "success") {
                        // Show a success message
                        iziToast.success({
                            message: response.message,
                            position: 'topRight'
                        });

                        // Redirect after a delay (optional)
                        setTimeout(function () {
                            window.location.href = '<?=$this->siteUrl()?>/' + response.redirect;
                        }, 3000); // Redirect after 3 seconds
                    } else if (response.status === "error") {
                        // The edit failed, show an iziToast error notification
                        iziToast.error({
                            message: response.message,
                            position: "topRight",
                        });
                    } else if (response.status === "warning") {
                        // Show an iziToast warning notification
                        iziToast.warning({
                            message: response.message,
                            position: "topRight",
                        });
                    }
                },
                error: function (xhr) {
                    // Handle errors gracefully
                    console.log(xhr.responseText);
                    iziToast.error({
                        message: "An error occurred. Please try again.",
                        position: "topRight",
                    });
                },
                complete: function () {
                    // Remove the spinner from the button and enable the button
                    $("#buyHouseBtn").html("Send a message");
                    $("#buyHouseBtn").prop("disabled", false);
                },
            });
        });

        // Add event listener for input fields to remove 'is-invalid' class on input
        $("#amount, #email, #phone, #message").on("input", function () {
            $(this).removeClass("is-invalid");
        });
    });
</script>
