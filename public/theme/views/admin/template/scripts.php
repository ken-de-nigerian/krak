<?php
defined('FIR') OR exit();
/**
 * The template for displaying the footer section
 */
?>

<!-- Fetch Converted Amounts -->
<script>
    $(document).ready(function() {
        // Iterate over each item with class "convert"
        $('.convert').each(function() {
            const $box = $(this);
            const abbreviation = $box.find('.abbreviation').val();

            // Reference the converted % marketcap element using the unique ID
            const convertedElement = $box.find('.converted');

            convertedElement.html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span></span>');

            // AJAX request
            $.ajax({
                url: '<?=$this->siteUrl()?>/admin/fetch',
                type: 'GET',
                data: {
                    abbreviation: abbreviation
                },
                success: function (response) {
                    // Clear the "Loading..." message
                    convertedElement.text('');

                    if (response.status === 'error') {
                        // If an error occurred, display the error message
                        convertedElement.text('Not Available');
                    } else {
                        // Display the formatted amount
                        convertedElement.text('$' + response.converted);
                    }
                },
                error: function(xhr) {
                    // Handle errors gracefully
                    console.log(xhr.responseText);
                    iziToast.error({
                        message: "An error occurred. Please try again.",
                        position: "topRight",
                    });
                }
            });
        });
    });
</script>

<!-- editTimeModal-->
<script>
    // Function to set value for modal input on activation
    (function ($) {
        "use strict";
        $(document).on('click', '[data-bs-target="#editTimeModal"]', function () {
            const name = $(this).data('name');
            const time = $(this).data('time');
            const id = $(this).data('id');
            $('#edit-name').val(name);
            $('#edit-hours').val(time);
            $('#timeId').val(id);
        });
    })(jQuery);
</script>

<!-- Cancel Running Investment -->
<script>
    // Event listener for cancelling a running investment
    $('#cancelInvestBtn').on('click', function () {
        
        // Retrieving IDs from data attributes
        const IdValue = $(this).data("id");

        // Checking if IDs are set
        if (!IdValue) {
            console.error("ID is not set in the data attribute.");
            return;
        }

        // Adding spinner and disabling button
        $("#cancelInvestBtn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
        $("#cancelInvestBtn").prop("disabled", true);

        // Making AJAX request to approve address proof
        $.ajax({
            url: '<?=$this->siteUrl()?>/admin/investments/view-investment/' + IdValue,
            type: 'POST',
            data: $('#cancel-investment-form').serialize(),
            dataType: 'json',
            success: function(response) {
                // Handling response based on status
                if (response.status === "success") {
                    // Displaying a success message
                    iziToast.success({
                        message: response.message,
                        position: 'topRight'
                    });

                    // Reloading page after a delay
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else if (response.status === "error") {
                    // Displaying error message
                    iziToast.error({
                        message: response.message,
                        position: 'topRight'
                    });
                } else if (response.status === "warning") {
                    // Displaying a warning message
                    iziToast.error({
                        message: response.message,
                        position: 'topRight'
                    });

                    // Reloading page after a delay
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
            },
            error: function(xhr) {
                // Handling errors
                console.log(xhr.responseText);
                iziToast.error({
                    message: "An error occurred. Please try again.",
                    position: "topRight",
                });
            },
            complete: function () {
                // Removing spinner and enabling button
                $("#cancelInvestBtn").html("Submit Form");
                $("#cancelInvestBtn").prop("disabled", false);
            },
        });
    });
</script>

<!-- Approve Address proof -->
<script>
    // Event listener for approving address proof
    $('#activateAddressBtn').on('click', function () {
        
        // Retrieving IDs from data attributes
        const uploadidValue = $(this).data("uploadid");
        const useridValue = $(this).data("userid");

        // Checking if IDs are set
        if (!uploadidValue || !useridValue) {
            console.error("ID is not set in the data attribute.");
            return;
        }

        // Adding spinner and disabling button
        $("#activateAddressBtn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
        $("#activateAddressBtn").prop("disabled", true);

        // Making AJAX request to approve address proof
        $.ajax({
            type: 'GET',
            url: '<?=$this->siteUrl()?>/admin/users/address-proof/' + useridValue,
            data: { 
                approve: uploadidValue 
            },
            success: function(response) {
                // Handling response based on status
                if (response.status === "success") {
                    // Displaying a success message
                    iziToast.success({
                        message: response.message,
                        position: 'topRight'
                    });

                    // Reloading page after a delay
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else if (response.status === "error") {
                    // Displaying error message
                    iziToast.error({
                        message: response.message,
                        position: 'topRight'
                    });
                } else if (response.status === "warning") {
                    // Displaying a warning message
                    iziToast.error({
                        message: response.message,
                        position: 'topRight'
                    });

                    // Reloading page after a delay
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
            },
            error: function(xhr) {
                // Handling errors
                console.log(xhr.responseText);
                iziToast.error({
                    message: "An error occurred. Please try again.",
                    position: "topRight",
                });
            },
            complete: function () {
                // Removing spinner and enabling button
                $("#activateAddressBtn").html("Yes, Activate");
                $("#activateAddressBtn").prop("disabled", false);
            },
        });
    });
</script>

<!-- Reject Address proof -->
<script>
    // Event listener for rejecting address proof
    $('#rejectAddressBtn').on('click', function () {
        
        // Retrieving IDs from data attributes
        const uploadidValue = $(this).data("uploadid");
        const useridValue = $(this).data("userid");

        // Checking if IDs are set
        if (!uploadidValue || !useridValue) {
            console.error("ID is not set in the data attribute.");
            return;
        }

        // Adding spinner and disabling button
        $("#rejectAddressBtn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
        $("#rejectAddressBtn").prop("disabled", true);

        // Making AJAX request to reject address proof
        $.ajax({
            type: 'GET',
            url: '<?=$this->siteUrl()?>/admin/users/address-proof/' + useridValue,
            data: { 
                reject: uploadidValue 
            },
            success: function(response) {
                // Handling response based on status
                if (response.status === "success") {
                    // Displaying a success message
                    iziToast.success({
                        message: response.message,
                        position: 'topRight'
                    });

                    // Reloading page after a delay
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else if (response.status === "error") {
                    // Displaying error message
                    iziToast.error({
                        message: response.message,
                        position: 'topRight'
                    });
                } else if (response.status === "warning") {
                    // Displaying a warning message
                    iziToast.error({
                        message: response.message,
                        position: 'topRight'
                    });

                    // Reloading page after a delay
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
            },
            error: function(xhr) {
                // Handling errors
                console.log(xhr.responseText);
                iziToast.error({
                    message: "An error occurred. Please try again.",
                    position: "topRight",
                });
            },
            complete: function () {
                // Removing spinner and enabling button
                $("#rejectAddressBtn").html("Yes, Activate");
                $("#rejectAddressBtn").prop("disabled", false);
            },
        });
    });
</script>

<!-- Approve Identity proof -->
<script>
    // Event listener for approving identity proof
    $('#activateIdentityBtn').on('click', function () {
        
        // Retrieving IDs from data attributes
        const uploadidValue = $(this).data("uploadid");
        const useridValue = $(this).data("userid");

        // Checking if IDs are set
        if (!uploadidValue || !useridValue) {
            console.error("ID is not set in the data attribute.");
            return;
        }

        // Adding spinner and disabling button
        $("#activateIdentityBtn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
        $("#activateIdentityBtn").prop("disabled", true);

        // Making AJAX request to approve identity proof
        $.ajax({
            type: 'GET',
            url: '<?=$this->siteUrl()?>/admin/users/identity-proof/' + useridValue,
            data: { 
                approve: uploadidValue 
            },
            success: function(response) {
                // Handling response based on status
                if (response.status === "success") {
                    // Displaying a success message
                    iziToast.success({
                        message: response.message,
                        position: 'topRight'
                    });

                    // Reloading page after a delay
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else if (response.status === "error") {
                    // Displaying error message
                    iziToast.error({
                        message: response.message,
                        position: 'topRight'
                    });
                } else if (response.status === "warning") {
                    // Displaying a warning message
                    iziToast.error({
                        message: response.message,
                        position: 'topRight'
                    });

                    // Reloading page after a delay
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
            },
            error: function(xhr) {
                // Handling errors
                console.log(xhr.responseText);
                iziToast.error({
                    message: "An error occurred. Please try again.",
                    position: "topRight",
                });
            },
            complete: function () {
                // Removing spinner and enabling button
                $("#activateIdentityBtn").html("Yes, Activate");
                $("#activateIdentityBtn").prop("disabled", false);
            },
        });
    });
</script>

<!-- Reject Identity proof -->
<script>
    // Event listener for rejecting identity proof
    $('#rejectIdentityBtn').on('click', function () {
        
        // Retrieving IDs from data attributes
        const uploadidValue = $(this).data("uploadid");
        const useridValue = $(this).data("userid");

        // Checking if IDs are set
        if (!uploadidValue || !useridValue) {
            console.error("ID is not set in the data attribute.");
            return;
        }

        // Adding spinner and disabling button
        $("#rejectIdentityBtn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
        $("#rejectIdentityBtn").prop("disabled", true);

        // Making AJAX request to reject identity proof
        $.ajax({
            type: 'GET',
            url: '<?=$this->siteUrl()?>/admin/users/identity-proof/' + useridValue,
            data: { 
                reject: uploadidValue 
            },
            success: function(response) {
                // Handling response based on status
                if (response.status === "success") {
                    // Displaying a success message
                    iziToast.success({
                        message: response.message,
                        position: 'topRight'
                    });

                    // Reloading page after a delay
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else if (response.status === "error") {
                    // Displaying error message
                    iziToast.error({
                        message: response.message,
                        position: 'topRight'
                    });
                } else if (response.status === "warning") {
                    // Displaying a warning message
                    iziToast.error({
                        message: response.message,
                        position: 'topRight'
                    });

                    // Reloading page after a delay
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
            },
            error: function(xhr) {
                // Handling errors
                console.log(xhr.responseText);
                iziToast.error({
                    message: "An error occurred. Please try again.",
                    position: "topRight",
                });
            },
            complete: function () {
                // Removing spinner and enabling button
                $("#rejectIdentityBtn").html("Yes, Activate");
                $("#rejectIdentityBtn").prop("disabled", false);
            },
        });
    });
</script>

<!-- Select Users-->
<script>
    $(document).ready(function() {
        const user = $("#users");
        if (user.length > 0) {

            // Initialize Select2
            $('.js-example-basic-single').select2();

            // Hide the label initially if any option is selected
            if ($('#users').val() !== null && $('#users').val().length > 0) {
                $('#users-label').hide();
            }

            // Show/hide label based on selection
            $('#users').on('change', function() {
                // Check if any option is selected
                const selectedUsers = $(this).val();
                if (selectedUsers && selectedUsers.length > 0) {
                    $('#users-label').hide();
                } else {
                    $('#users-label').show();
                }
            });
        }
    });
</script>

<!-- Upload Profile Picture-->
<script>
    // Function to trigger file input click
    const $upload = $("#upload");

    function changeProfile() {
        $upload.trigger("click");
    }

    // Function to handle file selection
    $upload.change(function() {
        const file = this.files[0];
        const fileName = file.name;
        $("#file_name").val(fileName); // Set the value of hidden input to the file name
        const userid = $("#userid").val(); // Get the userid
        const token = $("input[name='token_id']").val(); // Get the token by input name
        uploadFile(file, userid, token); // Call the function to upload the file via Ajax
    });

    // Function to upload file via Ajax
    function uploadFile(file, userid, token) {
        const formData = new FormData();
        formData.append("photoimg", file);
        formData.append("userid", userid); // Append userid to the FormData
        formData.append("token_id", token); // Append token to the FormData

        $.ajax({
            url: "<?=$this->siteUrl()?>/admin/upload",
            type: "POST",
            data: formData,
            dataType: "json",
            contentType: false,
            processData: false,
            success: function(response) {
                // Check the response status
                if (response.status === "success") {
                    // Reload the page after a short delay
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else if (response.status === "error") {
                    // Display an error message or provide feedback to the user
                    iziToast.error({
                        message: response.message,
                        position: 'topRight'
                    });
                }
            },
            error: function(xhr) {
                // Handle errors gracefully
                console.log(xhr.responseText);
                iziToast.error({
                    message: "An error occurred. Please try again.",
                    position: "topRight",
                });
            }
        });
    }
</script>

<!-- activateMethodModal-->
<script>
    // Function to set value for modal input on activation
    (function ($) {
        "use strict";
        $(document).on('click', '[data-bs-target="#activateMethodModal"]', function () {
            const id = $(this).data('id');
            $('#activate-id').val(id);
        });
    })(jQuery);
</script>

<!-- deactivateMethodModal-->
<script>
    // Function to set value for modal input on deactivation
    (function ($) {
        "use strict";
        $(document).on('click', '[data-bs-target="#deactivateMethodModal"]', function () {
            const id = $(this).data('id');
            $('#deactivate-id').val(id);
        });
    })(jQuery);
</script>

<!-- deleteDepositModal-->
<script>
    // Function to set value for modal input on deposit deletion
    (function ($) {
        "use strict";
        $(document).on('click', '[data-bs-target="#deleteDepositModal"]', function () {
            const depositId = $(this).data('deposit');
            $('#delete-depositId').val(depositId);
        });
    })(jQuery);
</script>

<!-- deleteWithdrawalModal -->
<script>
    // Function to set value for modal input on withdrawal deletion
    (function ($) {
        "use strict";
        $(document).on('click', '[data-bs-target="#deleteWithdrawalModal"]', function () {
            const withdrawId = $(this).data('withdrawal');
            $('#delete-withdrawId').val(withdrawId);
        });
    })(jQuery);
</script>

<!-- deleteInvestmentModal -->
<script>
    // Function to set value for modal input on investment deletion
    (function ($) {
        "use strict";
        $(document).on('click', '[data-bs-target="#deleteInvestmentModal"]', function () {
            const investId = $(this).data('investment');
            $('#delete-investId').val(investId);
        });
    })(jQuery);
</script>

<!-- Copy New Password-->
<script>
    // Function to copy text to clipboard
    function copyToClipboard(inputField) {
        // Select the text inside the input field
        inputField.select();
        inputField.setSelectionRange(0, 99999); /* For mobile devices */

        // Use Clipboard API to copy text
        navigator.clipboard.writeText(inputField.value)
            .then(() => {
                // If copying was successful
                iziToast.success({ message: "Copied: " + inputField.value, position: "topRight" });
            })
            .catch(() => {
                // If copying failed
                iziToast.error({ message: "Failed to copy. Please try again.", position: "topRight" });
            });
    }
</script>

<!-- Quill Editor -->
<script>
    // Check if the necessary elements exist before executing the script
    const editorElement = document.getElementById('editor');
    const detailsElement = document.getElementById('details');

    if (editorElement && detailsElement) {
        const quill = new Quill(editorElement, {
            theme: 'snow'
        });

        // Clear the content of the editor
        quill.root.innerHTML = '';

        const details = detailsElement;

        // Set the initial value for the hidden input field
        details.value = '';

        // Capture the HTML content from the Quill editor and assign it to the hidden input field
        quill.on('text-change', function () {
            details.value = quill.root.innerHTML;
        });
    }
</script>

<!-- Image Selector -->
<script>
    $(document).ready(function () {
        // Add an event listener to the file input field
        $("#hidden-input").change(function () {
            // Get the selected file
            const fileInput = this;

            if (fileInput.files && fileInput.files[0]) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    // Update the image source with the selected file's data URL
                    $("#preview-image").attr("src", e.target.result);
                    // Show the image by changing its display property to "block"
                    $("#preview-image").css("display", "block");
                };

                // Read the selected file as a data URL
                reader.readAsDataURL(fileInput.files[0]);
            }
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

            // Add the spinner to the button
            $("#loginBtn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');

            // Disable the button
            $("#loginBtn").prop("disabled", true);

            // Send an Ajax request to the login controller
            $.ajax({
                url: '<?=$this->siteUrl()?>/admin/login', // Modify the URL according to your setup
                type: 'POST',
                data: $('#login-form').serialize(),
                dataType: 'json',
                success: function(response) {
                    // Check the response status
                    if (response.status === 'success') {
                        // The login was successful, redirect to the redirect URL specified in the JSON object
                        window.location.href = '<?=$this->siteUrl()?>/' + response.redirect;
                    } else if (response.status === 'error') {
                        // The login failed, show an iziToast error notification
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

<!-- Edit-Profile Ajax -->
<script>
    $(document).ready(function () {
        // Handler for the edit button click event
        $("#profileBtn").click(function (event) {
            event.preventDefault(); // Prevent form submission

            // Validate inputs
            let valid = true;

            // First name validation
            const fullnameValue = $("#fullname").val().trim();
            if (fullnameValue === "") {
                $("#fullname").addClass("is-invalid");
                valid = false;
            } else {
                $("#fullname").removeClass("is-invalid");
            }

            // Last name validation
            const emailValue = $("#email").val().trim();
            if (emailValue === "") {
                $("#email").addClass("is-invalid");
                valid = false;
            } else {
                $("#email").removeClass("is-invalid");
            }

            if (!valid) {
                return;
            }

            // Add the spinner to the button
            $("#profileBtn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
            $("#profileBtn").prop("disabled", true); // Disable the button

            // Create a FormData object
            const formData = new FormData($('#profile-form')[0]);

            // Add the selected file to the FormData object
            const fileInput = $("#hidden-input")[0];
            if (fileInput.files.length > 0) {
                formData.append('photoimg', fileInput.files[0]);
            }

            // Send an Ajax request to the edit controller
            $.ajax({
                url: "<?=$this->siteUrl()?>/admin/profile", // Modify the URL according to your edit
                type: "POST",
                data: formData,
                dataType: "json",
                contentType: false,
                processData: false,
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
                    $("#profileBtn").html("Update Profile");
                    $("#profileBtn").prop("disabled", false);
                },
            });
        });

        // Add event listener for input fields to remove 'is-invalid' class on input
        $("#fullname, #email").on("input", function () {
            $(this).removeClass("is-invalid");
        });
    });
</script>

<!-- Update Password Ajax -->
<script>
    $(document).ready(function () {
        // Handler for the editPassword button click event
        $("#editPassword").click(function (event) {
            event.preventDefault(); // Prevent form submission

            // Validate inputs
            let valid = true;

            // Old password validation
            const oldPasswordValue = $("#oldPassword").val().trim();
            if (oldPasswordValue === "") {
                $("#oldPassword").addClass("is-invalid");
                valid = false;
            } else {
                $("#oldPassword").removeClass("is-invalid");
            }

            // New password validation
            const newPasswordValue = $("#password").val().trim();
            if (newPasswordValue === "") {
                $("#password").addClass("is-invalid");
                valid = false;
            } else {
                $("#password").removeClass("is-invalid");
            }

            // Confirm password validation
            const confirmPasswordValue = $("#confirmPassword").val().trim();
            if (confirmPasswordValue === "") {
                $("#confirmPassword").addClass("is-invalid");
                valid = false;
            } else {
                $("#confirmPassword").removeClass("is-invalid");
            }

            if (!valid) {
                return;
            }

            // Add the spinner to the button
            $("#editPassword").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
            $("#editPassword").prop("disabled", true); // Disable the button

            // Create a FormData object
            const formData = new FormData($('#password-form')[0]);

            // Send an Ajax request to the password controller
            $.ajax({
                url: "<?=$this->siteUrl()?>/admin/password", // Modify the URL according to your setup
                type: "POST",
                data: formData,
                dataType: "json",
                contentType: false,
                processData: false,
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
                        // The password change failed, show an iziToast error notification
                        iziToast.error({
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
                    $("#editPassword").html("Save Password");
                    $("#editPassword").prop("disabled", false);
                },
            });
        });

        // Add event listener for input fields to remove 'is-invalid' class on input
        $("#oldPassword, #password, #confirmPassword").on("input", function () {
            $(this).removeClass("is-invalid");
        });
    });
</script>

<!-- Settings -->
<script>
    $(document).ready(function() {
        // Handler for the setup button click event
        $("#setupBtn").click(function(event) {
            event.preventDefault(); // Prevent form submission

            // Validate inputs
            let valid = true;

            // sitename validation
            const sitenameValue = $("#sitename").val().trim();
            if (sitenameValue === '') {
                $("#sitename").addClass("is-invalid");
                valid = false;
            } else {
                $("#sitename").removeClass("is-invalid");
            }

            // signup_bonus_amount validation
            const signup_bonus_amountValue = $("#signup_bonus_amount").val();
            if (signup_bonus_amountValue === '') {
                $("#signup_bonus_amount").addClass("is-invalid");
                valid = false;
            } else {
                $("#signup_bonus_amount").removeClass("is-invalid");
            }

            if (!valid) {
                return;
            }

            // Add the spinner to the button
            $("#setupBtn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
            $("#setupBtn").prop("disabled", true); // Disable the button

            // Send an Ajax request to the setup controller
            $.ajax({
                url: '<?=$this->siteUrl()?>/admin/settings', // Modify the URL according to your setup
                type: 'POST',
                data: $('#setup-form').serialize(),
                dataType: 'json',
                success: function(response) {
                    // Check the response status
                    if (response.status === 'success') {
                        // Show a success message
                        iziToast.success({
                            message: response.message,
                            position: 'topRight'
                        });

                        // Redirect after a delay (optional)
                        setTimeout(function () {
                            window.location.href = '<?=$this->siteUrl()?>/' + response.redirect;
                        }, 3000); // Redirect after 3 seconds
                    } else if (response.status === 'error') {
                        // The login failed, show an iziToast error notification
                        iziToast.error({
                            message: response.message,
                            position: 'topRight'
                        });
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
                    $("#setupBtn").html("Submit Form");
                    $("#setupBtn").prop("disabled", false);
                }
            });
        });

        // Add event listener for input fields to remove 'is-invalid' class on input
        $("#title, #sitename, #description, #signup_bonus_amount").on("input", function() {
            $(this).removeClass("is-invalid");
        });
    });
</script>

<!-- Smtp Settings -->
<script>
    $(document).ready(function() {
        // Handler for the setup button click event
        $("#smtpBtn").click(function(event) {
            event.preventDefault(); // Prevent form submission

            // Validate inputs
            let valid = true;

            // smtp_host validation
            const smtp_hostValue = $("#smtp_host").val().trim();
            if (smtp_hostValue === '') {
                $("#smtp_host").addClass("is-invalid");
                valid = false;
            } else {
                $("#smtp_host").removeClass("is-invalid");
            }

            // smtp_username validation
            const smtp_usernameValue = $("#smtp_username").val().trim();
            if (smtp_usernameValue === '') {
                $("#smtp_username").addClass("is-invalid");
                valid = false;
            } else {
                $("#smtp_username").removeClass("is-invalid");
            }

            // smtp_password validation
            const smtp_passwordValue = $("#smtp_password").val().trim();
            if (smtp_passwordValue === '') {
                $("#smtp_password").addClass("is-invalid");
                valid = false;
            } else {
                $("#smtp_password").removeClass("is-invalid");
            }

            // smtp_encryption validation
            const smtp_encryptionValue = $("#smtp_encryption").val();
            if (smtp_encryptionValue === '') {
                $("#smtp_encryption").addClass("is-invalid");
                valid = false;
            } else {
                $("#smtp_encryption").removeClass("is-invalid");
            }

            // smtp_port validation
            const smtp_portValue = $("#smtp_port").val().trim();
            if (smtp_portValue === '') {
                $("#smtp_port").addClass("is-invalid");
                valid = false;
            } else {
                $("#smtp_port").removeClass("is-invalid");
            }

            if (!valid) {
                return;
            }

            // Add the spinner to the button
            $("#smtpBtn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
            $("#smtpBtn").prop("disabled", true); // Disable the button

            // Send an Ajax request to the smtp controller
            $.ajax({
                url: '<?=$this->siteUrl()?>/admin/email', // Modify the URL according to your smtp
                type: 'POST',
                data: $('#smtp-form').serialize(),
                dataType: 'json',
                success: function(response) {
                    // Check the response status
                    if (response.status === 'success') {
                        // Show a success message
                        iziToast.success({
                            message: response.message,
                            position: 'topRight'
                        });

                        // Redirect after a delay (optional)
                        setTimeout(function () {
                            window.location.href = '<?=$this->siteUrl()?>/' + response.redirect;
                        }, 3000); // Redirect after 3 seconds
                    } else if (response.status === 'error') {
                        // The login failed, show an iziToast error notification
                        iziToast.error({
                            message: response.message,
                            position: 'topRight'
                        });
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
                    $("#smtpBtn").html("Submit Form");
                    $("#smtpBtn").prop("disabled", false);
                }
            });
        });

        // Add event listener for input fields to remove 'is-invalid' class on input
        $("#smtp_host, #smtp_username, #smtp_password, #smtp_encryption, #smtp_port").on("input", function() {
            $(this).removeClass("is-invalid");
        });
    });
</script>

<!-- Mailjet Settings -->
<script>
    $(document).ready(function() {
        // Handler for the mailjet button click event
        $("#mailjetBtn").click(function(event) {
            event.preventDefault(); // Prevent form submission

            // Validate inputs
            let valid = true;

            // mailjet_api_key validation
            const mailjet_api_keyValue = $("#mailjet_api_key").val().trim();
            if (mailjet_api_keyValue === '') {
                $("#mailjet_api_key").addClass("is-invalid");
                valid = false;
            } else {
                $("#mailjet_api_key").removeClass("is-invalid");
            }

            // mailjet_api_secret validation
            const mailjet_api_secretValue = $("#mailjet_api_secret").val().trim();
            if (mailjet_api_secretValue === '') {
                $("#mailjet_api_secret").addClass("is-invalid");
                valid = false;
            } else {
                $("#mailjet_api_secret").removeClass("is-invalid");
            }

            if (!valid) {
                return;
            }

            // Add the spinner to the button
            $("#mailjetBtn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
            $("#mailjetBtn").prop("disabled", true); // Disable the button

            // Send an Ajax request to the mailjet controller
            $.ajax({
                url: '<?=$this->siteUrl()?>/admin/email/mailjet', // Modify the URL according to your mailjet
                type: 'POST',
                data: $('#mailjet-form').serialize(),
                dataType: 'json',
                success: function(response) {
                    // Check the response status
                    if (response.status === 'success') {
                        // Show a success message
                        iziToast.success({
                            message: response.message,
                            position: 'topRight'
                        });

                        // Redirect after a delay (optional)
                        setTimeout(function () {
                            window.location.href = '<?=$this->siteUrl()?>/' + response.redirect;
                        }, 3000); // Redirect after 3 seconds
                    } else if (response.status === 'error') {
                        // The login failed, show an iziToast error notification
                        iziToast.error({
                            message: response.message,
                            position: 'topRight'
                        });
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
                    $("#mailjetBtn").html("Submit Form");
                    $("#mailjetBtn").prop("disabled", false);
                }
            });
        });

        // Add event listener for input fields to remove 'is-invalid' class on input
        $("#mailjet_api_key, #mailjet_api_secret").on("input", function() {
            $(this).removeClass("is-invalid");
        });
    });
</script>

<!-- Send Test Email -->
<script>
    $(document).ready(function() {
        // Handler for the setup button click event
        $("#sendEmail").click(function(event) {
            event.preventDefault(); // Prevent form submission

            // Validate inputs
            let valid = true;

            // smtp_host validation
            const emailValue = $("#email").val().trim();
            if (emailValue === '') {
                $("#email").addClass("is-invalid");
                valid = false;
            } else {
                $("#email").removeClass("is-invalid");
            }

            if (!valid) {
                return;
            }

            // Add the spinner to the button
            $("#sendEmail").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
            $("#sendEmail").prop("disabled", true); // Disable the button

            // Send an Ajax request to the email controller
            $.ajax({
                url: '<?=$this->siteUrl()?>/admin/email/test-email', // Modify the URL according to your email
                type: 'POST',
                data: $('#email-form').serialize(),
                dataType: 'json',
                success: function(response) {
                    // Check the response status
                    if (response.status === 'success') {
                        // Show a success message
                        iziToast.success({
                            message: response.message,
                            position: 'topRight'
                        });

                        // Redirect after a delay (optional)
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
                    } else if (response.status === 'error') {
                        // The login failed, show an iziToast error notification
                        iziToast.error({
                            message: response.message,
                            position: 'topRight'
                        });
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
                    $("#sendEmail").html("Submit Form");
                    $("#sendEmail").prop("disabled", false);
                }
            });
        });

        // Add event listener for input fields to remove 'is-invalid' class on input
        $("#email").on("input", function() {
            $(this).removeClass("is-invalid");
        });
    });
</script>

<!--Update Email Template Ajax -->
<script>
    $(document).ready(function () {
        // Handler for the edit button click event
        $("#editEmailBtn").click(function (event) {
            event.preventDefault(); // Prevent form submission

            // Retrieve the project ID from the data attribute
            const emailValue = $(this).data("email");

            if (!emailValue) {
                console.error("Email is not set in the data attribute.");
                return;
            }

            // Validate inputs
            let valid = true;

            // name validation
            const nameValue = $("#name").val().trim();
            if (nameValue === "") {
                $("#name").addClass("is-invalid");
                valid = false;
            } else {
                $("#name").removeClass("is-invalid");
            }

            const subjectValue = $("#subject").val().trim();
            if (subjectValue === "") {
                $("#subject").addClass("is-invalid");
                valid = false;
            } else {
                $("#subject").removeClass("is-invalid");
            }

            const email_statusValue = $("#email_status").val().trim();
            if (email_statusValue === "") {
                $("#email_status").addClass("is-invalid");
                valid = false;
            } else {
                $("#email_status").removeClass("is-invalid");
            }

            if (!valid) {
                return;
            }

            // Add the spinner to the button
            $("#editEmailBtn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
            $("#editEmailBtn").prop("disabled", true); // Disable the button

            // Send an Ajax request to the edit controller
            $.ajax({
                url: "<?=$this->siteUrl()?>/admin/templates/edit-template/" + emailValue, // Modify the URL according to your edit
                type: 'POST',
                data: $('#edit-email-form').serialize(),
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
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
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
                    $("#editEmailBtn").html("Submit Form");
                    $("#editEmailBtn").prop("disabled", false);
                },
            });
        });

        // Add event listener for input fields to remove 'is-invalid' class on input
        $("#name, #subject, #email_status").on("input", function () {
            $(this).removeClass("is-invalid");
        });
    });
</script>

<!-- Logo Ajax -->
<script>
    $(document).ready(function () {
        // Handler for the edit button click event
        $("#logoBtn").click(function (event) {
            event.preventDefault(); // Prevent form submission

            // Add the spinner to the button
            $("#logoBtn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
            $("#logoBtn").prop("disabled", true); // Disable the button

            // Create a FormData object
            const formData = new FormData($('#logo-form')[0]);

            // Add the selected file to the FormData object
            const fileInput = $("#hidden-input")[0];
            if (fileInput.files.length > 0) {
                formData.append('photoimg', fileInput.files[0]);
            }

            // Send an Ajax request to the edit controller
            $.ajax({
                url: "<?=$this->siteUrl()?>/admin/logo", // Modify the URL according to your edit
                type: "POST",
                data: formData,
                dataType: "json",
                contentType: false,
                processData: false,
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
                    $("#logoBtn").html("Upload Logo");
                    $("#logoBtn").prop("disabled", false);
                },
            });
        });
    });
</script>

<!-- Favicon Ajax -->
<script>
    $(document).ready(function () {
        // Handler for the edit button click event
        $("#faviconBtn").click(function (event) {
            event.preventDefault(); // Prevent form submission

            // Add the spinner to the button
            $("#faviconBtn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
            $("#faviconBtn").prop("disabled", true); // Disable the button

            // Create a FormData object
            const formData = new FormData($('#favicon-form')[0]);

            // Add the selected file to the FormData object
            const fileInput = $("#hidden-input")[0];
            if (fileInput.files.length > 0) {
                formData.append('photoimg', fileInput.files[0]);
            }

            // Send an Ajax request to the edit controller
            $.ajax({
                url: "<?=$this->siteUrl()?>/admin/favicon", // Modify the URL according to your edit
                type: "POST",
                data: formData,
                dataType: "json",
                contentType: false,
                processData: false,
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
                    $("#faviconBtn").html("Upload Favicon");
                    $("#faviconBtn").prop("disabled", false);
                },
            });
        });
    });
</script>

<!--Update Extension Ajax -->
<script>
    $(document).ready(function () {
        // Handler for the edit button click event
        $("#extensionBtn").click(function (event) {
            event.preventDefault(); // Prevent form submission

            // Retrieve the project ID from the data attribute
            const extensionValue = $(this).data("extension");

            if (!extensionValue) {
                console.error("Extension ID is not set in the data attribute.");
                return;
            }

            // Validate inputs
            let valid = true;

            // name validation
            const nameValue = $("#name").val().trim();
            if (nameValue === "") {
                $("#name").addClass("is-invalid");
                valid = false;
            } else {
                $("#name").removeClass("is-invalid");
            }

            const scriptValue = $("#script").val().trim();
            if (scriptValue === "") {
                $("#script").addClass("is-invalid");
                valid = false;
            } else {
                $("#script").removeClass("is-invalid");
            }

            const extension_statusValue = $("#extension_status").val().trim();
            if (extension_statusValue === "") {
                $("#extension_status").addClass("is-invalid");
                valid = false;
            } else {
                $("#extension_status").removeClass("is-invalid");
            }

            if (!valid) {
                return;
            }

            // Add the spinner to the button
            $("#extensionBtn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
            $("#extensionBtn").prop("disabled", true); // Disable the button

            // Send an Ajax request to the edit controller
            $.ajax({
                url: "<?=$this->siteUrl()?>/admin/extensions/edit-extension/" + extensionValue, // Modify the URL according to your edit
                type: 'POST',
                data: $('#extension-form').serialize(),
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
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
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
                    $("#extensionBtn").html("Submit Form");
                    $("#extensionBtn").prop("disabled", false);
                },
            });
        });

        // Add event listener for input fields to remove 'is-invalid' class on input
        $("#name, #script, #extension_status").on("input", function () {
            $(this).removeClass("is-invalid");
        });
    });
</script>

<!-- Set Maintenance Ajax -->
<script>
    $(document).ready(function () {
        // Handler for the edit button click event
        $("#maintenanceBtn").click(function (event) {
            event.preventDefault(); // Prevent form submission

            // Validate inputs
            let valid = true;

            // maintenance_mode validation
            const maintenance_modeValue = $("#maintenance_mode").val().trim();
            if (maintenance_modeValue === "") {
                $("#maintenance_mode").addClass("is-invalid");
                valid = false;
            } else {
                $("#maintenance_mode").removeClass("is-invalid");
            }

            const detailsValue = $("#details").val().trim();
            if (detailsValue === "") {
                $("#details").addClass("is-invalid");
                valid = false;
            } else {
                $("#details").removeClass("is-invalid");
            }

            if (!valid) {
                return;
            }

            // Add the spinner to the button
            $("#maintenanceBtn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
            $("#maintenanceBtn").prop("disabled", true); // Disable the button

            // Send an Ajax request to the edit controller
            $.ajax({
                url: "<?=$this->siteUrl()?>/admin/maintenance", // Modify the URL according to your edit
                type: 'POST',
                data: $('#maintenance-form').serialize(),
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
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
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
                    $("#maintenanceBtn").html("Submit Form");
                    $("#maintenanceBtn").prop("disabled", false);
                },
            });
        });

        // Add event listener for input fields to remove 'is-invalid' class on input
        $("#maintenance_mode, #details").on("input", function () {
            $(this).removeClass("is-invalid");
            $("#details-error").removeClass("error");
            $("#details-error").text("");
        });
    });
</script>

<!-- SEO Ajax -->
<script>
    $(document).ready(function () {
        // Handler for the seo button click event
        $("#seoBtn").click(function (event) {
            event.preventDefault(); // Prevent form submission

            // Validate inputs
            let valid = true;

            // keywords validation
            const keywordsValue = $("#keywords").val().trim();
            if (keywordsValue === "") {
                $("#keywords").addClass("is-invalid");
                valid = false;
            } else {
                $("#keywords").removeClass("is-invalid");
            }

            // title validation
            const titleValue = $("#title").val().trim();
            if (titleValue === "") {
                $("#title").addClass("is-invalid");
                valid = false;
            } else {
                $("#title").removeClass("is-invalid");
            }

            // description validation
            const descriptionValue = $("#description").val().trim();
            if (descriptionValue === "") {
                $("#description").addClass("is-invalid");
                valid = false;
            } else {
                $("#description").removeClass("is-invalid");
            }

            if (!valid) {
                return;
            }

            // Add the spinner to the button
            $("#seoBtn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
            $("#seoBtn").prop("disabled", true); // Disable the button

            // Create a FormData object
            const formData = new FormData($('#seo-form')[0]);

            // Add the selected file to the FormData object
            const fileInput = $("#hidden-input")[0];
            if (fileInput.files.length > 0) {
                formData.append('photoimg', fileInput.files[0]);
            }

            // Send an Ajax request to the edit controller
            $.ajax({
                url: "<?=$this->siteUrl()?>/admin/seo", // Modify the URL according to your edit
                type: "POST",
                data: formData,
                dataType: "json",
                contentType: false,
                processData: false,
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
                    $("#seoBtn").html("Submit Form");
                    $("#seoBtn").prop("disabled", false);
                },
            });
        });

        // Add event listener for input fields to remove 'is-invalid' class on input
        $("#keywords, #title, #description").on("input", function () {
            $(this).removeClass("is-invalid");
        });
    });
</script>

<!-- Ranks Ajax -->
<script>
    $(document).ready(function () {
        // Handler for the rank button click event
        $("#rankBtn").click(function (event) {
            event.preventDefault(); // Prevent form submission

            // Retrieve the rank ID from the data attribute
            const rankValue = $(this).data("rank");

            if (!rankValue) {
                console.error("Rank ID is not set in the data attribute.");
                return;
            }

            // Validate inputs
            let valid = true;

            // name validation
            const nameValue = $("#name").val().trim();
            if (nameValue === "") {
                $("#name").addClass("is-invalid");
                valid = false;
            } else {
                $("#name").removeClass("is-invalid");
            }

            // min_invest validation
            const min_investValue = $("#min_invest").val().trim();
            if (min_investValue === "") {
                $("#min_invest").addClass("is-invalid");
                valid = false;
            } else {
                $("#min_invest").removeClass("is-invalid");
            }

            // min_referral validation
            const min_referralValue = $("#min_referral").val().trim();
            if (min_referralValue === "") {
                $("#min_referral").addClass("is-invalid");
                valid = false;
            } else {
                $("#min_referral").removeClass("is-invalid");
            }

            // bonus validation
            const bonusValue = $("#bonus").val().trim();
            if (bonusValue === "") {
                $("#bonus").addClass("is-invalid");
                valid = false;
            } else {
                $("#bonus").removeClass("is-invalid");
            }

            if (!valid) {
                return;
            }

            // Add the spinner to the button
            $("#rankBtn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
            $("#rankBtn").prop("disabled", true); // Disable the button

            // Create a FormData object
            const formData = new FormData($('#rank-form')[0]);

            // Add the selected file to the FormData object
            const fileInput = $("#hidden-input")[0];
            if (fileInput.files.length > 0) {
                formData.append('photoimg', fileInput.files[0]);
            }

            // Send an Ajax request to the edit controller
            $.ajax({
                url: "<?=$this->siteUrl()?>/admin/ranking/edit-ranking/" + rankValue, // Modify the URL according to your edit
                type: "POST",
                data: formData,
                dataType: "json",
                contentType: false,
                processData: false,
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
                    $("#rankBtn").html("Submit Form");
                    $("#rankBtn").prop("disabled", false);
                },
            });
        });

        // Add event listener for input fields to remove 'is-invalid' class on input
        $("#name, #min_invest, #min_referral, #bonus").on("input", function () {
            $(this).removeClass("is-invalid");
        });
    });
</script>

<!-- Add Ranks Ajax -->
<script>
    $(document).ready(function () {
        // Handler for the rank button click event
        $("#addRankBtn").click(function (event) {
            event.preventDefault(); // Prevent form submission

            // Validate inputs
            let valid = true;

            // name validation
            const nameValue = $("#name").val().trim();
            if (nameValue === "") {
                $("#name").addClass("is-invalid");
                valid = false;
            } else {
                $("#name").removeClass("is-invalid");
            }

            // min_invest validation
            const min_investValue = $("#min_invest").val().trim();
            if (min_investValue === "") {
                $("#min_invest").addClass("is-invalid");
                valid = false;
            } else {
                $("#min_invest").removeClass("is-invalid");
            }

            // min_referral validation
            const min_referralValue = $("#min_referral").val().trim();
            if (min_referralValue === "") {
                $("#min_referral").addClass("is-invalid");
                valid = false;
            } else {
                $("#min_referral").removeClass("is-invalid");
            }

            // bonus validation
            const bonusValue = $("#bonus").val().trim();
            if (bonusValue === "") {
                $("#bonus").addClass("is-invalid");
                valid = false;
            } else {
                $("#bonus").removeClass("is-invalid");
            }

            if (!valid) {
                return;
            }

            // Add the spinner to the button
            $("#addRankBtn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
            $("#addRankBtn").prop("disabled", true); // Disable the button

            // Create a FormData object
            const formData = new FormData($('#add-rank-form')[0]);

            // Add the selected file to the FormData object
            const fileInput = $("#hidden-input")[0];
            if (fileInput.files.length > 0) {
                formData.append('photoimg', fileInput.files[0]);
            }

            // Send an Ajax request to the edit controller
            $.ajax({
                url: "<?=$this->siteUrl()?>/admin/ranking/add-ranking", // Modify the URL according to your edit
                type: "POST",
                data: formData,
                dataType: "json",
                contentType: false,
                processData: false,
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
                    $("#addRankBtn").html("Submit Form");
                    $("#addRankBtn").prop("disabled", false);
                },
            });
        });

        // Add event listener for input fields to remove 'is-invalid' class on input
        $("#name, #min_invest, #min_referral, #bonus").on("input", function () {
            $(this).removeClass("is-invalid");
        });
    });
</script>

<!-- Load More Templates -->
<script>
    $(document).ready(function() {
        let page = 2; // Initial page number
        const container = $("#loadMoreTemplatesContainer");
        const loadMoreTemplates = $(".loadMoreTemplates");
        const lastPageDiv = $("#TemplatesLastpage"); // Get the reference to the last page div

        loadMoreTemplates.click(function(e) {
            e.preventDefault();
            const currentPage = $(this).data("page");

            if (!currentPage) {
                console.error("Page is not set in the data attribute.");
                return;
            }

            loadMoreTemplates.html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Loading...</span>');

            // Send an AJAX request to fetch more content
            $.ajax({
                url: "<?=$this->siteUrl()?>/admin/templates", // Update with the correct URL
                type: "GET",
                data: { page: currentPage },
                success: function(response) {
                    const templates = response.templates;

                    // Construct the HTML markup for each template item
                    let html = '';
                    for (let i = 0; i < templates.length; i++) {
                        const template = templates[i];

                        html += '<div class="col-xl-3">';
                        html += '<div class="card">';
                        html += '<div class="card-header"><h6 class="card-title mb-0"><i class="ri-mail-line align-middle me-1 lh-1"></i>' + template.name + '</h6></div>';
                        html += '<div class="card-body"><p class="text-muted">Subject - ' + template.subject + '</p></div>';

                        html += '<div class="card-footer">';
                        html += '<div class="hstack gap-2 justify-content-end">';
                        html += '<a href="<?=$this->siteUrl()?>/admin/templates/edit-template/' + template.id + '" class="btn btn-link btn-sm link-success"><i class="ri-pencil-fill align-middle lh-1"></i> Edit</a>';

                        if (template.email_status == 1) {
                            html += '<span class="badge bg-success-subtle text-approved">Active</span>';
                        } else if (template.email_status == 2) {
                            html += '<span class="badge bg-danger-subtle text-danger">Disabled</span>';
                        }

                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                    }

                    // Append the new content to the container
                    container.find(".template").append(html);

                    // Update the page number
                    page++;

                    // If there is no more content to load, hide the load more button
                    if (templates.length === 0) {
                        loadMoreTemplates.hide();
                        lastPageDiv.removeClass("d-none");
                    } else {
                        loadMoreTemplates.text("Load More"); // Restore the button text
                        loadMoreTemplates.data("page", page); // Update the data-page attribute
                    }
                },
                error: function() {
                    // Handle the error case
                    loadMoreTemplates.text("Load More"); // Restore the button text
                },
            });
        });
    });
</script>

<!-- Load More Ranking -->
<script>
    $(document).ready(function() {
        let page = 2; // Initial page number
        const container = $("#loadMoreRankingContainer");
        const loadMoreRanking = $(".loadMoreRanking");
        const lastPageDiv = $("#RankingLastpage"); // Get the reference to the last page div

        loadMoreRanking.click(function(e) {
            e.preventDefault();
            const currentPage = $(this).data("page");

            if (!currentPage) {
                console.error("Page is not set in the data attribute.");
                return;
            }

            loadMoreRanking.html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Loading...</span>');

            // Send an AJAX request to fetch more content
            $.ajax({
                url: "<?=$this->siteUrl()?>/admin/ranking", // Update with the correct URL
                type: "GET",
                data: { page: currentPage },
                success: function(response) {
                    const ranks = response.ranks;

                    // Construct the HTML markup for each template item
                    let html = '';
                    for (let i = 0; i < ranks.length; i++) {
                        const rank = ranks[i];

                        html += '<div class="col">';
                        html += '<div class="card card-body">';
                        html += '<div class="d-flex mb-4 align-items-center">';

                        html += '<div class="flex-shrink-0"><img src="<?=$this->siteUrl()?>/' + '<?=PUBLIC_PATH?>/' + '<?=UPLOADS_PATH?>/ranks/' + rank.icon + '" alt="" class="avatar-sm rounded-circle" /></div>';

                        html += '<div class="flex-grow-1 ms-2">';

                        html += '<h5 class="card-title mb-1">' + rank.name + '</h5>';
                        html += '<p class="text-muted mb-0">';

                        if (rank.status == 1) {
                            html += '<span class="badge bg-success-subtle text-approved">Active</span>';
                        } else if (rank.status == 2) {
                            html += '<span class="badge bg-danger-subtle text-danger">Disabled</span>';
                        }

                        html += '</p>';

                        html += '</div>';
                         html += '</div>';

                        html += '<p class="card-text text-muted">Bonus - $' + rank.bonus + '</p>';
                        html += '<h6 class="mb-1">Min Referral - ' + rank.min_referral + '</h6>';
                        html += '<h6 class="mb-1">Min Invest - $' + rank.min_invest + '</h6>';

                        html += '<a href="<?=$this->siteUrl()?>/admin/ranking/edit-ranking/' + rank.rankingId + '" class="btn btn-primary waves-effect waves-light mt-3">Edit Rank</a>';

                        html += '</div>';
                        html += '</div>';
                    }

                    // Append the new content to the container
                    container.find(".rank").append(html);

                    // Update the page number
                    page++;

                    // If there is no more content to load, hide the load more button
                    if (ranks.length === 0) {
                        loadMoreRanking.hide();
                        lastPageDiv.removeClass("d-none");
                    } else {
                        loadMoreRanking.text("Load More"); // Restore the button text
                        loadMoreRanking.data("page", page); // Update the data-page attribute
                    }
                },
                error: function() {
                    // Handle the error case
                    loadMoreRanking.text("Load More"); // Restore the button text
                },
            });
        });
    });
</script>

<!-- Load More Users -->
<script>
    $(document).ready(function() {
        let page = 2; // Initial page number
        const container = $("#loadMoreUsersContainer");
        const loadMoreUsers = $(".loadMoreUsers");
        const lastPageDiv = $("#UsersLastpage"); // Get the reference to the last page div

        loadMoreUsers.click(function(e) {
            e.preventDefault();
            const currentPage = $(this).data("page");

            if (!currentPage) {
                console.error("Page is not set in the data attribute.");
                return;
            }

            loadMoreUsers.html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Loading...</span>');

            // Send an AJAX request to fetch more content
            $.ajax({
                url: "<?=$this->siteUrl()?>/admin/users", // Update with the correct URL
                type: "GET",
                data: { page: currentPage },
                success: function(response) {
                    const users = response.users;

                    // Construct the HTML markup for each template item
                    let html = '';
                    for (let i = 0; i < users.length; i++) {
                        const user = users[i];

                        html += '<div class="col-xxl-3 col-md-6">';
                        html += '<div class="card">';
                        html += '<div class="card-body">';
                        html += '<div class="d-flex align-items-center">';

                        html += '<div class="flex-shrink-0">';
                        html += '<div class="avatar-lg rounded">';

                        let firstLetterFirstName = user.firstname.charAt(0);
                        let firstLetterLastName = user.lastname.charAt(0);

                        if (user.imagelocation == "default.png") {
                            html += '<div class="avatar-title border bg-light text-primary rounded text-uppercase fs-24">'+ firstLetterFirstName +''+ firstLetterLastName +'</div>';
                        }else{
                            html += '<img src="<?=$this->siteUrl()?>/<?=PUBLIC_PATH?>/<?=UPLOADS_PATH?>/users/'+ user.imagelocation +'" alt="" class="member-img img-fluid d-block rounded" />';
                        }

                        html += '</div></div>';

                        html += '<div class="flex-grow-1 ms-3">';

                        html += '<a href="<?=$this->siteUrl()?>/admin/users/view-profile/'+ user.userid +'"><h5 class="fs-16 mb-1">'+ user.firstname +' '+ user.lastname +'</h5></a>';

                        html += '<p class="text-muted mb-2">'+ user.email +'</p>';

                        html += '<div class="d-flex flex-wrap gap-2 align-items-center">';
                        if (user.status == 1) {
                            html += '<span class="badge bg-success-subtle text-approved">Active</span>';
                        } else if (user.status == 2) {
                            html += '<span class="badge bg-danger-subtle text-danger">Blocked</span>';
                        }
                        html += '</div>';

                        html += '<div class="d-flex gap-4 mt-2 text-muted">';
                        html += '<div><i class="ri-map-pin-2-line text-primary me-1 align-bottom"></i> '+ user.country +'</div>';
                        html += '<div><i class="ri-time-line text-primary me-1 align-bottom"></i>'+ getRelativeTime(user.registration_date) +'</div>';

                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                    }

                    // Append the new content to the container
                    container.find(".job-list-row").append(html);

                    // Update the page number
                    page++;

                    // If there is no more content to load, hide the load more button
                    if (users.length === 0) {
                        loadMoreUsers.hide();
                        lastPageDiv.removeClass("d-none");
                    } else {
                        loadMoreUsers.text("Load More"); // Restore the button text
                        loadMoreUsers.data("page", page); // Update the data-page attribute
                    }
                },
                error: function() {
                    // Handle the error case
                    loadMoreUsers.text("Load More"); // Restore the button text
                },
            });
        });

        function getRelativeTime(dateString) {
            const now = new Date();
            const dateAdded = new Date(dateString);
            const elapsed = now - dateAdded;

            // Convert elapsed time to appropriate units
            const seconds = Math.floor(elapsed / 1000);
            const minutes = Math.floor(seconds / 60);
            const hours = Math.floor(minutes / 60);
            const days = Math.floor(hours / 24);
            const months = Math.floor(days / 30); // Approximate, not exact
            const years = Math.floor(days / 365);

            if (years > 0) {
                return years + (years === 1 ? " year ago" : " years ago");
            } else if (months > 0) {
                return months + (months === 1 ? " month ago" : " months ago");
            } else if (days > 0) {
                return days + (days === 1 ? " day ago" : " days ago");
            } else if (hours > 0) {
                return hours + (hours === 1 ? " hour ago" : " hours ago");
            } else if (minutes > 0) {
                return minutes + (minutes === 1 ? " minute ago" : " minutes ago");
            } else {
                return seconds + (seconds === 1 ? " second ago" : " seconds ago");
            }
        }
    });
</script>

<!-- Load More Active Users -->
<script>
    $(document).ready(function() {
        let page = 2; // Initial page number
        const container = $("#loadMoreActiveUsersContainer");
        const loadMoreActiveUsers = $(".loadMoreActiveUsers");
        const lastPageDiv = $("#ActiveUsersLastpage"); // Get the reference to the last page div

        loadMoreActiveUsers.click(function(e) {
            e.preventDefault();
            const currentPage = $(this).data("page");

            if (!currentPage) {
                console.error("Page is not set in the data attribute.");
                return;
            }

            loadMoreActiveUsers.html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Loading...</span>');

            // Send an AJAX request to fetch more content
            $.ajax({
                url: "<?=$this->siteUrl()?>/admin/active", // Update with the correct URL
                type: "GET",
                data: { page: currentPage },
                success: function(response) {
                    const users = response.users;

                    // Construct the HTML markup for each template item
                    let html = '';
                    for (let i = 0; i < users.length; i++) {
                        const user = users[i];

                        html += '<div class="col-xxl-3 col-md-6">';
                        html += '<div class="card">';
                        html += '<div class="card-body">';
                        html += '<div class="d-flex align-items-center">';

                        html += '<div class="flex-shrink-0">';
                        html += '<div class="avatar-lg rounded">';

                        let firstLetterFirstName = user.firstname.charAt(0);
                        let firstLetterLastName = user.lastname.charAt(0);

                        if (user.imagelocation == "default.png") {
                            html += '<div class="avatar-title border bg-light text-primary rounded text-uppercase fs-24">'+ firstLetterFirstName +''+ firstLetterLastName +'</div>';
                        }else{
                            html += '<img src="<?=$this->siteUrl()?>/<?=PUBLIC_PATH?>/<?=UPLOADS_PATH?>/users/'+ user.imagelocation +'" alt="" class="member-img img-fluid d-block rounded" />';
                        }

                        html += '</div></div>';

                        html += '<div class="flex-grow-1 ms-3">';

                        html += '<a href="<?=$this->siteUrl()?>/admin/users/view-profile/'+ user.userid +'"><h5 class="fs-16 mb-1">'+ user.firstname +' '+ user.lastname +'</h5></a>';

                        html += '<p class="text-muted mb-2">'+ user.email +'</p>';

                        html += '<div class="d-flex flex-wrap gap-2 align-items-center">';
                        if (user.status == 1) {
                            html += '<span class="badge bg-success-subtle text-approved">Active</span>';
                        } else if (user.status == 2) {
                            html += '<span class="badge bg-danger-subtle text-danger">Blocked</span>';
                        }
                        html += '</div>';

                        html += '<div class="d-flex gap-4 mt-2 text-muted">';
                        html += '<div><i class="ri-map-pin-2-line text-primary me-1 align-bottom"></i> '+ user.country +'</div>';
                        html += '<div><i class="ri-time-line text-primary me-1 align-bottom"></i>'+ getRelativeTime(user.registration_date) +'</div>';

                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                    }

                    // Append the new content to the container
                    container.find(".job-list-row").append(html);

                    // Update the page number
                    page++;

                    // If there is no more content to load, hide the load more button
                    if (users.length === 0) {
                        loadMoreActiveUsers.hide();
                        lastPageDiv.removeClass("d-none");
                    } else {
                        loadMoreActiveUsers.text("Load More"); // Restore the button text
                        loadMoreActiveUsers.data("page", page); // Update the data-page attribute
                    }
                },
                error: function() {
                    // Handle the error case
                    loadMoreActiveUsers.text("Load More"); // Restore the button text
                },
            });
        });

        function getRelativeTime(dateString) {
            const now = new Date();
            const dateAdded = new Date(dateString);
            const elapsed = now - dateAdded;

            // Convert elapsed time to appropriate units
            const seconds = Math.floor(elapsed / 1000);
            const minutes = Math.floor(seconds / 60);
            const hours = Math.floor(minutes / 60);
            const days = Math.floor(hours / 24);
            const months = Math.floor(days / 30); // Approximate, not exact
            const years = Math.floor(days / 365);

            if (years > 0) {
                return years + (years === 1 ? " year ago" : " years ago");
            } else if (months > 0) {
                return months + (months === 1 ? " month ago" : " months ago");
            } else if (days > 0) {
                return days + (days === 1 ? " day ago" : " days ago");
            } else if (hours > 0) {
                return hours + (hours === 1 ? " hour ago" : " hours ago");
            } else if (minutes > 0) {
                return minutes + (minutes === 1 ? " minute ago" : " minutes ago");
            } else {
                return seconds + (seconds === 1 ? " second ago" : " seconds ago");
            }
        }
    });
</script>

<!-- Load More Banned Users -->
<script>
    $(document).ready(function() {
        let page = 2; // Initial page number
        const container = $("#loadMoreBannedUsersContainer");
        const loadMoreBannedUsers = $(".loadMoreBannedUsers");
        const lastPageDiv = $("#BannedUsersLastpage"); // Get the reference to the last page div

        loadMoreBannedUsers.click(function(e) {
            e.preventDefault();
            const currentPage = $(this).data("page");

            if (!currentPage) {
                console.error("Page is not set in the data attribute.");
                return;
            }

            loadMoreBannedUsers.html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Loading...</span>');

            // Send an AJAX request to fetch more content
            $.ajax({
                url: "<?=$this->siteUrl()?>/admin/banned", // Update with the correct URL
                type: "GET",
                data: { page: currentPage },
                success: function(response) {
                    const users = response.users;

                    // Construct the HTML markup for each template item
                    let html = '';
                    for (let i = 0; i < users.length; i++) {
                        const user = users[i];

                        html += '<div class="col-xxl-3 col-md-6">';
                        html += '<div class="card">';
                        html += '<div class="card-body">';
                        html += '<div class="d-flex align-items-center">';

                        html += '<div class="flex-shrink-0">';
                        html += '<div class="avatar-lg rounded">';

                        let firstLetterFirstName = user.firstname.charAt(0);
                        let firstLetterLastName = user.lastname.charAt(0);

                        if (user.imagelocation == "default.png") {
                            html += '<div class="avatar-title border bg-light text-primary rounded text-uppercase fs-24">'+ firstLetterFirstName +''+ firstLetterLastName +'</div>';
                        }else{
                            html += '<img src="<?=$this->siteUrl()?>/<?=PUBLIC_PATH?>/<?=UPLOADS_PATH?>/users/'+ user.imagelocation +'" alt="" class="member-img img-fluid d-block rounded" />';
                        }

                        html += '</div></div>';

                        html += '<div class="flex-grow-1 ms-3">';

                        html += '<a href="<?=$this->siteUrl()?>/admin/users/view-profile/'+ user.userid +'"><h5 class="fs-16 mb-1">'+ user.firstname +' '+ user.lastname +'</h5></a>';

                        html += '<p class="text-muted mb-2">'+ user.email +'</p>';

                        html += '<div class="d-flex flex-wrap gap-2 align-items-center">';
                        if (user.status == 1) {
                            html += '<span class="badge bg-success-subtle text-approved">Active</span>';
                        } else if (user.status == 2) {
                            html += '<span class="badge bg-danger-subtle text-danger">Blocked</span>';
                        }
                        html += '</div>';

                        html += '<div class="d-flex gap-4 mt-2 text-muted">';
                        html += '<div><i class="ri-map-pin-2-line text-primary me-1 align-bottom"></i> '+ user.country +'</div>';
                        html += '<div><i class="ri-time-line text-primary me-1 align-bottom"></i>'+ getRelativeTime(user.registration_date) +'</div>';

                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                    }

                    // Append the new content to the container
                    container.find(".job-list-row").append(html);

                    // Update the page number
                    page++;

                    // If there is no more content to load, hide the load more button
                    if (users.length === 0) {
                        loadMoreBannedUsers.hide();
                        lastPageDiv.removeClass("d-none");
                    } else {
                        loadMoreBannedUsers.text("Load More"); // Restore the button text
                        loadMoreBannedUsers.data("page", page); // Update the data-page attribute
                    }
                },
                error: function() {
                    // Handle the error case
                    loadMoreBannedUsers.text("Load More"); // Restore the button text
                },
            });
        });

        function getRelativeTime(dateString) {
            const now = new Date();
            const dateAdded = new Date(dateString);
            const elapsed = now - dateAdded;

            // Convert elapsed time to appropriate units
            const seconds = Math.floor(elapsed / 1000);
            const minutes = Math.floor(seconds / 60);
            const hours = Math.floor(minutes / 60);
            const days = Math.floor(hours / 24);
            const months = Math.floor(days / 30); // Approximate, not exact
            const years = Math.floor(days / 365);

            if (years > 0) {
                return years + (years === 1 ? " year ago" : " years ago");
            } else if (months > 0) {
                return months + (months === 1 ? " month ago" : " months ago");
            } else if (days > 0) {
                return days + (days === 1 ? " day ago" : " days ago");
            } else if (hours > 0) {
                return hours + (hours === 1 ? " hour ago" : " hours ago");
            } else if (minutes > 0) {
                return minutes + (minutes === 1 ? " minute ago" : " minutes ago");
            } else {
                return seconds + (seconds === 1 ? " second ago" : " seconds ago");
            }
        }
    });
</script>

<!-- Load More KYC Unverifed Users -->
<script>
    $(document).ready(function() {
        let page = 2; // Initial page number
        const container = $("#loadMoreKYCUnverifiedUsersContainer");
        const loadMoreKYCUnverifiedUsers = $(".loadMoreKYCUnverifiedUsers");
        const lastPageDiv = $("#KYCUnverifiedUsersLastpage"); // Get the reference to the last page div

        loadMoreKYCUnverifiedUsers.click(function(e) {
            e.preventDefault();
            const currentPage = $(this).data("page");

            if (!currentPage) {
                console.error("Page is not set in the data attribute.");
                return;
            }

            loadMoreKYCUnverifiedUsers.html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Loading...</span>');

            // Send an AJAX request to fetch more content
            $.ajax({
                url: "<?=$this->siteUrl()?>/admin/kyc_unverified", // Update with the correct URL
                type: "GET",
                data: { page: currentPage },
                success: function(response) {
                    const users = response.users;

                    // Construct the HTML markup for each template item
                    let html = '';
                    for (let i = 0; i < users.length; i++) {
                        const user = users[i];

                        html += '<div class="col-xxl-3 col-md-6">';
                        html += '<div class="card">';
                        html += '<div class="card-body">';
                        html += '<div class="d-flex align-items-center">';

                        html += '<div class="flex-shrink-0">';
                        html += '<div class="avatar-lg rounded">';

                        let firstLetterFirstName = user.firstname.charAt(0);
                        let firstLetterLastName = user.lastname.charAt(0);

                        if (user.imagelocation == "default.png") {
                            html += '<div class="avatar-title border bg-light text-primary rounded text-uppercase fs-24">'+ firstLetterFirstName +''+ firstLetterLastName +'</div>';
                        }else{
                            html += '<img src="<?=$this->siteUrl()?>/<?=PUBLIC_PATH?>/<?=UPLOADS_PATH?>/users/'+ user.imagelocation +'" alt="" class="member-img img-fluid d-block rounded" />';
                        }

                        html += '</div></div>';

                        html += '<div class="flex-grow-1 ms-3">';

                        html += '<a href="<?=$this->siteUrl()?>/admin/users/view-profile/'+ user.userid +'"><h5 class="fs-16 mb-1">'+ user.firstname +' '+ user.lastname +'</h5></a>';

                        html += '<p class="text-muted mb-2">'+ user.email +'</p>';

                        html += '<div class="d-flex flex-wrap gap-2 align-items-center">';
                        if (user.status == 1) {
                            html += '<span class="badge bg-success-subtle text-approved">Active</span>';
                        } else if (user.status == 2) {
                            html += '<span class="badge bg-danger-subtle text-danger">Blocked</span>';
                        }
                        html += '</div>';

                        html += '<div class="d-flex gap-4 mt-2 text-muted">';
                        html += '<div><i class="ri-map-pin-2-line text-primary me-1 align-bottom"></i> '+ user.country +'</div>';
                        html += '<div><i class="ri-time-line text-primary me-1 align-bottom"></i>'+ getRelativeTime(user.registration_date) +'</div>';

                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                    }

                    // Append the new content to the container
                    container.find(".job-list-row").append(html);

                    // Update the page number
                    page++;

                    // If there is no more content to load, hide the load more button
                    if (users.length === 0) {
                        loadMoreKYCUnverifiedUsers.hide();
                        lastPageDiv.removeClass("d-none");
                    } else {
                        loadMoreKYCUnverifiedUsers.text("Load More"); // Restore the button text
                        loadMoreKYCUnverifiedUsers.data("page", page); // Update the data-page attribute
                    }
                },
                error: function() {
                    // Handle the error case
                    loadMoreKYCUnverifiedUsers.text("Load More"); // Restore the button text
                },
            });
        });

        function getRelativeTime(dateString) {
            const now = new Date();
            const dateAdded = new Date(dateString);
            const elapsed = now - dateAdded;

            // Convert elapsed time to appropriate units
            const seconds = Math.floor(elapsed / 1000);
            const minutes = Math.floor(seconds / 60);
            const hours = Math.floor(minutes / 60);
            const days = Math.floor(hours / 24);
            const months = Math.floor(days / 30); // Approximate, not exact
            const years = Math.floor(days / 365);

            if (years > 0) {
                return years + (years === 1 ? " year ago" : " years ago");
            } else if (months > 0) {
                return months + (months === 1 ? " month ago" : " months ago");
            } else if (days > 0) {
                return days + (days === 1 ? " day ago" : " days ago");
            } else if (hours > 0) {
                return hours + (hours === 1 ? " hour ago" : " hours ago");
            } else if (minutes > 0) {
                return minutes + (minutes === 1 ? " minute ago" : " minutes ago");
            } else {
                return seconds + (seconds === 1 ? " second ago" : " seconds ago");
            }
        }
    });
</script>

<!-- Load More KYC Pending Users -->
<script>
    $(document).ready(function() {
        let page = 2; // Initial page number
        const container = $("#loadMoreKYCPendingUsersContainer");
        const loadMoreKYCPendingUsers = $(".loadMoreKYCPendingUsers");
        const lastPageDiv = $("#KYCPendingUsersLastpage"); // Get the reference to the last page div

        loadMoreKYCPendingUsers.click(function(e) {
            e.preventDefault();
            const currentPage = $(this).data("page");

            if (!currentPage) {
                console.error("Page is not set in the data attribute.");
                return;
            }

            loadMoreKYCPendingUsers.html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Loading...</span>');

            // Send an AJAX request to fetch more content
            $.ajax({
                url: "<?=$this->siteUrl()?>/admin/kyc_pending", // Update with the correct URL
                type: "GET",
                data: { page: currentPage },
                success: function(response) {
                    const users = response.users;

                    // Construct the HTML markup for each template item
                    let html = '';
                    for (let i = 0; i < users.length; i++) {
                        const user = users[i];

                        html += '<div class="col-xxl-3 col-md-6">';
                        html += '<div class="card">';
                        html += '<div class="card-body">';
                        html += '<div class="d-flex align-items-center">';

                        html += '<div class="flex-shrink-0">';
                        html += '<div class="avatar-lg rounded">';

                        let firstLetterFirstName = user.firstname.charAt(0);
                        let firstLetterLastName = user.lastname.charAt(0);

                        if (user.imagelocation == "default.png") {
                            html += '<div class="avatar-title border bg-light text-primary rounded text-uppercase fs-24">'+ firstLetterFirstName +''+ firstLetterLastName +'</div>';
                        }else{
                            html += '<img src="<?=$this->siteUrl()?>/<?=PUBLIC_PATH?>/<?=UPLOADS_PATH?>/users/'+ user.imagelocation +'" alt="" class="member-img img-fluid d-block rounded" />';
                        }

                        html += '</div></div>';

                        html += '<div class="flex-grow-1 ms-3">';

                        html += '<a href="<?=$this->siteUrl()?>/admin/users/view-profile/'+ user.userid +'"><h5 class="fs-16 mb-1">'+ user.firstname +' '+ user.lastname +'</h5></a>';

                        html += '<p class="text-muted mb-2">'+ user.email +'</p>';

                        html += '<div class="d-flex flex-wrap gap-2 align-items-center">';
                        if (user.status == 1) {
                            html += '<span class="badge bg-success-subtle text-approved">Active</span>';
                        } else if (user.status == 2) {
                            html += '<span class="badge bg-danger-subtle text-danger">Blocked</span>';
                        }
                        html += '</div>';

                        html += '<div class="d-flex gap-4 mt-2 text-muted">';
                        html += '<div><i class="ri-map-pin-2-line text-primary me-1 align-bottom"></i> '+ user.country +'</div>';
                        html += '<div><i class="ri-time-line text-primary me-1 align-bottom"></i>'+ getRelativeTime(user.registration_date) +'</div>';

                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                    }

                    // Append the new content to the container
                    container.find(".job-list-row").append(html);

                    // Update the page number
                    page++;

                    // If there is no more content to load, hide the load more button
                    if (users.length === 0) {
                        loadMoreKYCPendingUsers.hide();
                        lastPageDiv.removeClass("d-none");
                    } else {
                        loadMoreKYCPendingUsers.text("Load More"); // Restore the button text
                        loadMoreKYCPendingUsers.data("page", page); // Update the data-page attribute
                    }
                },
                error: function() {
                    // Handle the error case
                    loadMoreKYCPendingUsers.text("Load More"); // Restore the button text
                },
            });
        });

        function getRelativeTime(dateString) {
            const now = new Date();
            const dateAdded = new Date(dateString);
            const elapsed = now - dateAdded;

            // Convert elapsed time to appropriate units
            const seconds = Math.floor(elapsed / 1000);
            const minutes = Math.floor(seconds / 60);
            const hours = Math.floor(minutes / 60);
            const days = Math.floor(hours / 24);
            const months = Math.floor(days / 30); // Approximate, not exact
            const years = Math.floor(days / 365);

            if (years > 0) {
                return years + (years === 1 ? " year ago" : " years ago");
            } else if (months > 0) {
                return months + (months === 1 ? " month ago" : " months ago");
            } else if (days > 0) {
                return days + (days === 1 ? " day ago" : " days ago");
            } else if (hours > 0) {
                return hours + (hours === 1 ? " hour ago" : " hours ago");
            } else if (minutes > 0) {
                return minutes + (minutes === 1 ? " minute ago" : " minutes ago");
            } else {
                return seconds + (seconds === 1 ? " second ago" : " seconds ago");
            }
        }
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
                    $("#phone").addClass("is-invalid");
                } else {
                    // Clear the error message if the phone number is valid
                    $("#phone").removeClass("is-invalid");
                }
                // Enable or disable the submitted button based on the validity of the phone number
                $("#addUserBtn").prop("disabled", !isValid);

                const user = $("#editUserBtn");
                if (user.length > 0) {
                    $("#editUserBtn").prop("disabled", !isValid);
                }
            }
        }
    });
</script>

<!-- Register User Ajax -->
<script>
    $(document).ready(function () {
        // Handler for the edit button click event
        $("#addUserBtn").click(function (event) {
            event.preventDefault(); // Prevent form submission

            // Validate inputs
            let valid = true;

            // firstname validation
            const firstnameValue = $("#firstname").val().trim();
            if (firstnameValue === "") {
                $("#firstname").addClass("is-invalid");
                valid = false;
            } else {
                $("#firstname").removeClass("is-invalid");
            }

            // lastname validation
            const lastnameValue = $("#lastname").val().trim();
            if (lastnameValue === "") {
                $("#lastname").addClass("is-invalid");
                valid = false;
            } else {
                $("#lastname").removeClass("is-invalid");
            }

            // email validation
            const emailValue = $("#email").val().trim();
            if (emailValue === "") {
                $("#email").addClass("is-invalid");
                valid = false;
            } else {
                $("#email").removeClass("is-invalid");
            }

            // password validation
            const passwordValue = $("#password").val().trim();
            if (passwordValue === "") {
                $("#password").addClass("is-invalid");
                valid = false;
            } else {
                $("#password").removeClass("is-invalid");
            }

            // confirmPassword validation
            const confirmPasswordValue = $("#confirmPassword").val().trim();
            if (confirmPasswordValue === "") {
                $("#confirmPassword").addClass("is-invalid");
                valid = false;
            } else {
                $("#confirmPassword").removeClass("is-invalid");
            }

            if (!valid) {
                return;
            }

            // Add the spinner to the button
            $("#addUserBtn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
            $("#addUserBtn").prop("disabled", true); // Disable the button

            // Send an Ajax request to the edit controller
            $.ajax({
                url: "<?=$this->siteUrl()?>/admin/users/add-user", // Modify the URL according to your edit
                type: 'POST',
                data: $('#add-user-form').serialize(),
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

                        // Redirect after a delay (optional)
                        setTimeout(function () {
                            window.location.href = '<?=$this->siteUrl()?>/' + response.redirect;
                        }, 3000); // Redirect after 3 seconds
                    } else if (response.status === "warning") {
                        // Show an iziToast warning notification
                        iziToast.error({
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
                    $("#addUserBtn").html("Submit Form");
                    $("#addUserBtn").prop("disabled", false);
                },
            });
        });

        // Add event listener for input fields to remove 'is-invalid' class on input
        $("#firstname, #lastname, #email, #password, #confirmPassword").on("input", function () {
            $(this).removeClass("is-invalid");
        });
    });
</script>

<!-- Edit User Ajax -->
<script>
    $(document).ready(function () {
        // Handler for the edit button click event
        $("#editUserBtn").click(function (event) {
            event.preventDefault(); // Prevent form submission

            // Retrieve the project ID from the data attribute
            const idValue = $(this).data("id");

            if (!idValue) {
                console.error("ID is not set in the data attribute.");
                return;
            }

            // Validate inputs
            let valid = true;

            // firstname validation
            const firstnameValue = $("#firstname").val().trim();
            if (firstnameValue === "") {
                $("#firstname").addClass("is-invalid");
                valid = false;
            } else {
                $("#firstname").removeClass("is-invalid");
            }

            // lastname validation
            const lastnameValue = $("#lastname").val().trim();
            if (lastnameValue === "") {
                $("#lastname").addClass("is-invalid");
                valid = false;
            } else {
                $("#lastname").removeClass("is-invalid");
            }

            // email validation
            const emailValue = $("#email").val().trim();
            if (emailValue === "") {
                $("#email").addClass("is-invalid");
                valid = false;
            } else {
                $("#email").removeClass("is-invalid");
            }

            // country validation
            const countryValue = $("#country").val().trim();
            if (countryValue === "") {
                $("#country").addClass("is-invalid");
                valid = false;
            } else {
                $("#country").removeClass("is-invalid");
            }

            // city validation
            const cityPasswordValue = $("#city").val().trim();
            if (cityPasswordValue === "") {
                $("#city").addClass("is-invalid");
                valid = false;
            } else {
                $("#city").removeClass("is-invalid");
            }

            // state validation
            const statePasswordValue = $("#state").val().trim();
            if (statePasswordValue === "") {
                $("#state").addClass("is-invalid");
                valid = false;
            } else {
                $("#state").removeClass("is-invalid");
            }

            // address_1 validation
            const addressPasswordValue = $("#address_1").val().trim();
            if (addressPasswordValue === "") {
                $("#address_1").addClass("is-invalid");
                valid = false;
            } else {
                $("#address_1").removeClass("is-invalid");
            }

            if (!valid) {
                return;
            }

            // Add the spinner to the button
            $("#editUserBtn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
            $("#editUserBtn").prop("disabled", true); // Disable the button

            // Send an Ajax request to the edit controller
            $.ajax({
                url: "<?=$this->siteUrl()?>/admin/users/edit-profile/" + idValue, // Modify the URL according to your edit
                type: 'POST',
                data: $('#edit-user-form').serialize(),
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
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
                    } else if (response.status === "error") {
                        // The edit failed, show an iziToast error notification
                        iziToast.error({
                            message: response.message,
                            position: "topRight",
                        });
                    } else if (response.status === "warning") {
                        // Show an iziToast warning notification
                        iziToast.error({
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
                    $("#editUserBtn").html("Submit Form");
                    $("#editUserBtn").prop("disabled", false);
                },
            });
        });

        // Add event listener for input fields to remove 'is-invalid' class on input
        $("#firstname, #lastname, #email, #country, #city, #state, #address_1").on("input", function () {
            $(this).removeClass("is-invalid");
        });
    });
</script>

<!-- User Login Ajax -->
<script>
    $(document).ready(function() {
        // Handler for the login button click event
        $("#UserLoginBtn").click(function(event) {
            // Check if the email or password fields are empty
            const userEmailValue = $("#userEmail").val().trim();
            let isValid = true;

            if (userEmailValue === '') {
                // Add the 'is-invalid' class to the email input field
                $("#userEmail").addClass("is-invalid");
                isValid = false;
            } else {
                // Remove the 'is-invalid' class from the email input field
                $("#userEmail").removeClass("is-invalid");
            }

            if (!isValid) {
                event.preventDefault(); // Prevent form submission
                return;
            }

            // Add the spinner to the button
            $("#UserLoginBtn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');

            // Disable the button
            $("#UserLoginBtn").prop("disabled", true);

            // Send an Ajax request to the login controller
            $.ajax({
                url: '<?= $this->siteUrl() ?>/admin/users/login-user', // Modify the URL according to your setup
                type: 'POST',
                data: $('#user-login-form').serialize(),
                dataType: 'json',
                success: function(response) {
                    // Check the response status
                    if (response.status === 'success') {
                        // The login was successful, open the redirect URL in a new tab
                        window.open('<?=$this->siteUrl()?>/' + response.redirect, '_blank');
                    }else if (response.status === 'error') {
                        // The login failed, show an iziToast error notification
                        iziToast.error({
                            message: response.message,
                            position: 'topRight'
                        });
                    } else if (response.status === 'warning') {
                        // The login failed, show an iziToast warning notification
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
                complete: function() {
                    // Remove the spinner from the button and enable the button
                    $("#UserLoginBtn").html('<i class=" ri-account-pin-box-fill align-bottom me-1"></i> Login As User');
                    $("#UserLoginBtn").prop("disabled", false);
                }
            });
        });

        // Add event listener for input fields to remove 'is-invalid' class on input
        $("#userEmail").on("input", function() {
            $(this).removeClass("is-invalid");
        });
    });
</script>

<!-- Add Funds Ajax -->
<script>
    $(document).ready(function () {
        // Handler for the edit button click event
        $("#addMoneyBtn").click(function (event) {
            event.preventDefault(); // Prevent form submission

            // Validate inputs
            let valid = true;

            // add-amount validation
            const amountValue = $("#add-amount").val().trim();
            if (amountValue === "") {
                $("#add-amount").addClass("is-invalid");
                valid = false;
            } else {
                $("#add-amount").removeClass("is-invalid");
            }

            if (!valid) {
                return;
            }

            // Add the spinner to the button
            $("#addMoneyBtn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
            $("#addMoneyBtn").prop("disabled", true); // Disable the button

            // Send an Ajax request to the edit controller
            $.ajax({
                url: "<?=$this->siteUrl()?>/admin/users/add-funds", // Modify the URL according to your edit
                type: 'POST',
                data: $('#add-money-form').serialize(),
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
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
                    } else if (response.status === "error") {
                        // The edit failed, show an iziToast error notification
                        iziToast.error({
                            message: response.message,
                            position: "topRight",
                        });
                    } else if (response.status === "warning") {
                        // Show an iziToast warning notification
                        iziToast.error({
                            message: response.message,
                            position: "topRight",
                        });

                        // Redirect after a delay (optional)
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
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
                    $("#addMoneyBtn").html("Submit Form");
                    $("#addMoneyBtn").prop("disabled", false);
                },
            });
        });

        // Add event listener for input fields to remove 'is-invalid' class on input
        $("#amount").on("input", function () {
            $(this).removeClass("is-invalid");
        });
    });
</script>

<!-- Remove Funds Ajax -->
<script>
    $(document).ready(function () {
        // Handler for the edit button click event
        $("#removeMoneyBtn").click(function (event) {
            event.preventDefault(); // Prevent form submission

            // Validate inputs
            let valid = true;

            // remove-amount validation
            const amountValue = $("#remove-amount").val().trim();
            if (amountValue === "") {
                $("#remove-amount").addClass("is-invalid");
                valid = false;
            } else {
                $("#remove-amount").removeClass("is-invalid");
            }

            if (!valid) {
                return;
            }

            // Add the spinner to the button
            $("#removeMoneyBtn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
            $("#removeMoneyBtn").prop("disabled", true); // Disable the button

            // Send an Ajax request to the edit controller
            $.ajax({
                url: "<?=$this->siteUrl()?>/admin/users/remove-funds", // Modify the URL according to your edit
                type: 'POST',
                data: $('#remove-money-form').serialize(),
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
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
                    } else if (response.status === "error") {
                        // The edit failed, show an iziToast error notification
                        iziToast.error({
                            message: response.message,
                            position: "topRight",
                        });
                    } else if (response.status === "warning") {
                        // Show an iziToast warning notification
                        iziToast.error({
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
                    $("#removeMoneyBtn").html("Submit Form");
                    $("#removeMoneyBtn").prop("disabled", false);
                },
            });
        });

        // Add event listener for input fields to remove 'is-invalid' class on input
        $("#amount").on("input", function () {
            $(this).removeClass("is-invalid");
        });
    });
</script>

<!-- Send Email To One User -->
<script>
    $(document).ready(function() {
        // Handler for the setup button click event
        $("#sendEmailUser").click(function(event) {
            event.preventDefault(); // Prevent form submission

            // Validate inputs
            let valid = true;

            // Class validation
            const subjectValue = $("#subject").val().trim();
            if (subjectValue === '') {
                $("#subject").addClass("is-invalid");
                valid = false;
            } else {
                $("#subject").removeClass("is-invalid");
            }

            const detailsValue = $("#details").val().trim();
            if (detailsValue === "") {
                // Display an error message or provide feedback to the user
                $("#details-error").text('This field is required').addClass("text-danger");
                valid = false;
            }

            if (!valid) {
                return;
            }

            // Add the spinner to the button
            $("#sendEmailUser").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
            $("#sendEmailUser").prop("disabled", true); // Disable the button

            // Send an Ajax request to the setup controller
            $.ajax({
                url: '<?=$this->siteUrl()?>/admin/users/send-email', // Modify the URL according to your setup
                type: 'POST',
                data: $('#send-email').serialize(),
                dataType: 'json',
                success: function(response) {
                    // Check the response status
                    if (response.status === 'success') {
                        // Show a success message
                        iziToast.success({
                            message: response.message,
                            position: 'topRight'
                        });

                        // Redirect after a delay (optional)
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
                    } else if (response.status === 'error') {
                        // The login failed, show an iziToast error notification
                        iziToast.error({
                            message: response.message,
                            position: 'topRight'
                        });
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
                    $("#sendEmailUser").html("Send Email");
                    $("#sendEmailUser").prop("disabled", false);
                }
            });
        });

        // Add event listener for input fields to remove 'is-invalid' class on input
        $("#details, #subject").on("input", function() {
            $(this).removeClass("is-invalid");
            $("#details-error").removeClass("error");
            $("#details-error").text("");
        });
    });
</script>

<!-- Send Email To All Users -->
<script>
    $(document).ready(function() {
        // Handler for the setup button click event
        $("#notifyBtn").click(function(event) {
            event.preventDefault(); // Prevent form submission

            // Validate inputs
            let valid = true;

            // Class validation
            const subjectValue = $("#subject").val().trim();
            if (subjectValue === '') {
                $("#subject").addClass("is-invalid");
                valid = false;
            } else {
                $("#subject").removeClass("is-invalid");
            }

            const detailsValue = $("#details").val().trim();
            if (detailsValue === "") {
                // Display an error message or provide feedback to the user
                $("#details-error").text('This field is required').addClass("text-danger");
                valid = false;
            }

            if (!valid) {
                return;
            }

            // Add the spinner to the button
            $("#notifyBtn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
            $("#notifyBtn").prop("disabled", true); // Disable the button

            // Send an Ajax request to the setup controller
            $.ajax({
                url: '<?=$this->siteUrl()?>/admin/notifications', // Modify the URL according to your setup
                type: 'POST',
                data: $('#notifications-form').serialize(),
                dataType: 'json',
                success: function(response) {
                    // Check the response status
                    if (response.status === 'success') {
                        // Show a success message
                        iziToast.success({
                            message: response.message,
                            position: 'topRight'
                        });

                        // Redirect after a delay (optional)
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
                    } else if (response.status === 'error') {
                        // The login failed, show an iziToast error notification
                        iziToast.error({
                            message: response.message,
                            position: 'topRight'
                        });
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
                    $("#notifyBtn").html("Send Email");
                    $("#notifyBtn").prop("disabled", false);
                }
            });
        });

        // Add event listener for input fields to remove 'is-invalid' class on input
        $("#details, #subject").on("input", function() {
            $(this).removeClass("is-invalid");
            $("#details-error").removeClass("error");
            $("#details-error").text("");
        });
    });
</script>

<!-- Send Email To Selected Users -->
<script>
    $(document).ready(function() {
        // Handler for the setup button click event
        $("#notifySelectedBtn").click(function(event) {
            event.preventDefault(); // Prevent form submission

            // Validate inputs
            let valid = true;

            // Class validation
            const subjectValue = $("#subject").val().trim();
            if (subjectValue === '') {
                $("#subject").addClass("is-invalid");
                valid = false;
            } else {
                $("#subject").removeClass("is-invalid");
            }

            const detailsValue = $("#details").val().trim();
            if (detailsValue === "") {
                // Display an error message or provide feedback to the user
                $("#details-error").text('This field is required').addClass("text-danger");
                valid = false;
            }

            if (!valid) {
                return;
            }

            // Add the spinner to the button
            $("#notifySelectedBtn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
            $("#notifySelectedBtn").prop("disabled", true); // Disable the button

            // Send an Ajax request to the setup controller
            $.ajax({
                url: '<?=$this->siteUrl()?>/admin/notify/selected-users', // Modify the URL according to your setup
                type: 'POST',
                data: $('#notify-selected-form').serialize(),
                dataType: 'json',
                success: function(response) {
                    // Check the response status
                    if (response.status === 'success') {
                        // Show a success message
                        iziToast.success({
                            message: response.message,
                            position: 'topRight'
                        });

                        // Redirect after a delay (optional)
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
                    } else if (response.status === 'error') {
                        // The login failed, show an iziToast error notification
                        iziToast.error({
                            message: response.message,
                            position: 'topRight'
                        });
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
                    $("#notifySelectedBtn").html("Send Email");
                    $("#notifySelectedBtn").prop("disabled", false);
                }
            });
        });

        // Add event listener for input fields to remove 'is-invalid' class on input
        $("#details, #subject").on("input", function() {
            $(this).removeClass("is-invalid");
            $("#details-error").removeClass("error");
            $("#details-error").text("");
        });
    });
</script>

<!-- Send Email To KYC Unverified Users -->
<script>
    $(document).ready(function() {
        // Handler for the setup button click event
        $("#notifyKycUnverifiedUsersBtn").click(function(event) {
            event.preventDefault(); // Prevent form submission

            // Validate inputs
            let valid = true;

            // Class validation
            const subjectValue = $("#subject").val().trim();
            if (subjectValue === '') {
                $("#subject").addClass("is-invalid");
                valid = false;
            } else {
                $("#subject").removeClass("is-invalid");
            }

            const detailsValue = $("#details").val().trim();
            if (detailsValue === "") {
                // Display an error message or provide feedback to the user
                $("#details-error").text('This field is required').addClass("text-danger");
                valid = false;
            }

            if (!valid) {
                return;
            }

            // Add the spinner to the button
            $("#notifyKycUnverifiedUsersBtn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
            $("#notifyKycUnverifiedUsersBtn").prop("disabled", true); // Disable the button

            // Send an Ajax request to the setup controller
            $.ajax({
                url: '<?=$this->siteUrl()?>/admin/notify/kyc-unverified-users', // Modify the URL according to your setup
                type: 'POST',
                data: $('#notify-kyc-unverified-users-form').serialize(),
                dataType: 'json',
                success: function(response) {
                    // Check the response status
                    if (response.status === 'success') {
                        // Show a success message
                        iziToast.success({
                            message: response.message,
                            position: 'topRight'
                        });

                        // Redirect after a delay (optional)
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
                    } else if (response.status === 'error') {
                        // The login failed, show an iziToast error notification
                        iziToast.error({
                            message: response.message,
                            position: 'topRight'
                        });
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
                    $("#notifyKycUnverifiedUsersBtn").html("Send Email");
                    $("#notifyKycUnverifiedUsersBtn").prop("disabled", false);
                }
            });
        });

        // Add event listener for input fields to remove 'is-invalid' class on input
        $("#details, #subject").on("input", function() {
            $(this).removeClass("is-invalid");
            $("#details-error").removeClass("error");
            $("#details-error").text("");
        });
    });
</script>

<!-- Send Email To KYC Unverified Users -->
<script>
    $(document).ready(function() {
        // Handler for the setup button click event
        $("#notifyKycPendingUsersBtn").click(function(event) {
            event.preventDefault(); // Prevent form submission

            // Validate inputs
            let valid = true;

            // Class validation
            const subjectValue = $("#subject").val().trim();
            if (subjectValue === '') {
                $("#subject").addClass("is-invalid");
                valid = false;
            } else {
                $("#subject").removeClass("is-invalid");
            }

            const detailsValue = $("#details").val().trim();
            if (detailsValue === "") {
                // Display an error message or provide feedback to the user
                $("#details-error").text('This field is required').addClass("text-danger");
                valid = false;
            }

            if (!valid) {
                return;
            }

            // Add the spinner to the button
            $("#notifyKycPendingUsersBtn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
            $("#notifyKycPendingUsersBtn").prop("disabled", true); // Disable the button

            // Send an Ajax request to the setup controller
            $.ajax({
                url: '<?=$this->siteUrl()?>/admin/notify/kyc-pending-users', // Modify the URL according to your setup
                type: 'POST',
                data: $('#notify-kyc-pending-users-form').serialize(),
                dataType: 'json',
                success: function(response) {
                    // Check the response status
                    if (response.status === 'success') {
                        // Show a success message
                        iziToast.success({
                            message: response.message,
                            position: 'topRight'
                        });

                        // Redirect after a delay (optional)
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
                    } else if (response.status === 'error') {
                        // The login failed, show an iziToast error notification
                        iziToast.error({
                            message: response.message,
                            position: 'topRight'
                        });
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
                    $("#notifyKycPendingUsersBtn").html("Send Email");
                    $("#notifyKycPendingUsersBtn").prop("disabled", false);
                }
            });
        });

        // Add event listener for input fields to remove 'is-invalid' class on input
        $("#details, #subject").on("input", function() {
            $(this).removeClass("is-invalid");
            $("#details-error").removeClass("error");
            $("#details-error").text("");
        });
    });
</script>

<!-- Send Email To Users With Empty Balance -->
<script>
    $(document).ready(function() {
        // Handler for the setup button click event
        $("#notifyUsersWithEmptyBalanceBtn").click(function(event) {
            event.preventDefault(); // Prevent form submission

            // Validate inputs
            let valid = true;

            // Class validation
            const subjectValue = $("#subject").val().trim();
            if (subjectValue === '') {
                $("#subject").addClass("is-invalid");
                valid = false;
            } else {
                $("#subject").removeClass("is-invalid");
            }

            const detailsValue = $("#details").val().trim();
            if (detailsValue === "") {
                // Display an error message or provide feedback to the user
                $("#details-error").text('This field is required').addClass("text-danger");
                valid = false;
            }

            if (!valid) {
                return;
            }

            // Add the spinner to the button
            $("#notifyUsersWithEmptyBalanceBtn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
            $("#notifyUsersWithEmptyBalanceBtn").prop("disabled", true); // Disable the button

            // Send an Ajax request to the setup controller
            $.ajax({
                url: '<?=$this->siteUrl()?>/admin/notify/users-with-empty-balance', // Modify the URL according to your setup
                type: 'POST',
                data: $('#users-with-empty-balance-form').serialize(),
                dataType: 'json',
                success: function(response) {
                    // Check the response status
                    if (response.status === 'success') {
                        // Show a success message
                        iziToast.success({
                            message: response.message,
                            position: 'topRight'
                        });

                        // Redirect after a delay (optional)
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
                    } else if (response.status === 'error') {
                        // The login failed, show an iziToast error notification
                        iziToast.error({
                            message: response.message,
                            position: 'topRight'
                        });
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
                    $("#notifyUsersWithEmptyBalanceBtn").html("Send Email");
                    $("#notifyUsersWithEmptyBalanceBtn").prop("disabled", false);
                }
            });
        });

        // Add event listener for input fields to remove 'is-invalid' class on input
        $("#details, #subject").on("input", function() {
            $(this).removeClass("is-invalid");
            $("#details-error").removeClass("error");
            $("#details-error").text("");
        });
    });
</script>

<!-- Send Email To Users With Initiated Deposits -->
<script>
    $(document).ready(function() {
        // Handler for the setup button click event
        $("#notifyUsersWithInitiatedDepositsBtn").click(function(event) {
            event.preventDefault(); // Prevent form submission

            // Validate inputs
            let valid = true;

            // Class validation
            const subjectValue = $("#subject").val().trim();
            if (subjectValue === '') {
                $("#subject").addClass("is-invalid");
                valid = false;
            } else {
                $("#subject").removeClass("is-invalid");
            }

            const detailsValue = $("#details").val().trim();
            if (detailsValue === "") {
                // Display an error message or provide feedback to the user
                $("#details-error").text('This field is required').addClass("text-danger");
                valid = false;
            }

            if (!valid) {
                return;
            }

            // Add the spinner to the button
            $("#notifyUsersWithInitiatedDepositsBtn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
            $("#notifyUsersWithInitiatedDepositsBtn").prop("disabled", true); // Disable the button

            // Send an Ajax request to the setup controller
            $.ajax({
                url: '<?=$this->siteUrl()?>/admin/notify/users-with-initiated-deposits', // Modify the URL according to your setup
                type: 'POST',
                data: $('#users-with-initiated-deposits-form').serialize(),
                dataType: 'json',
                success: function(response) {
                    // Check the response status
                    if (response.status === 'success') {
                        // Show a success message
                        iziToast.success({
                            message: response.message,
                            position: 'topRight'
                        });

                        // Redirect after a delay (optional)
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
                    } else if (response.status === 'error') {
                        // The login failed, show an iziToast error notification
                        iziToast.error({
                            message: response.message,
                            position: 'topRight'
                        });
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
                    $("#notifyUsersWithInitiatedDepositsBtn").html("Send Email");
                    $("#notifyUsersWithInitiatedDepositsBtn").prop("disabled", false);
                }
            });
        });

        // Add event listener for input fields to remove 'is-invalid' class on input
        $("#details, #subject").on("input", function() {
            $(this).removeClass("is-invalid");
            $("#details-error").removeClass("error");
            $("#details-error").text("");
        });
    });
</script>

<!-- Send Email To Users With Pending Deposits -->
<script>
    $(document).ready(function() {
        // Handler for the setup button click event
        $("#notifyUsersWithPendingDepositsBtn").click(function(event) {
            event.preventDefault(); // Prevent form submission

            // Validate inputs
            let valid = true;

            // Class validation
            const subjectValue = $("#subject").val().trim();
            if (subjectValue === '') {
                $("#subject").addClass("is-invalid");
                valid = false;
            } else {
                $("#subject").removeClass("is-invalid");
            }

            const detailsValue = $("#details").val().trim();
            if (detailsValue === "") {
                // Display an error message or provide feedback to the user
                $("#details-error").text('This field is required').addClass("text-danger");
                valid = false;
            }

            if (!valid) {
                return;
            }

            // Add the spinner to the button
            $("#notifyUsersWithPendingDepositsBtn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
            $("#notifyUsersWithPendingDepositsBtn").prop("disabled", true); // Disable the button

            // Send an Ajax request to the setup controller
            $.ajax({
                url: '<?=$this->siteUrl()?>/admin/notify/users-with-pending-deposits', // Modify the URL according to your setup
                type: 'POST',
                data: $('#users-with-pending-deposits-form').serialize(),
                dataType: 'json',
                success: function(response) {
                    // Check the response status
                    if (response.status === 'success') {
                        // Show a success message
                        iziToast.success({
                            message: response.message,
                            position: 'topRight'
                        });

                        // Redirect after a delay (optional)
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
                    } else if (response.status === 'error') {
                        // The login failed, show an iziToast error notification
                        iziToast.error({
                            message: response.message,
                            position: 'topRight'
                        });
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
                    $("#notifyUsersWithPendingDepositsBtn").html("Send Email");
                    $("#notifyUsersWithPendingDepositsBtn").prop("disabled", false);
                }
            });
        });

        // Add event listener for input fields to remove 'is-invalid' class on input
        $("#details, #subject").on("input", function() {
            $(this).removeClass("is-invalid");
            $("#details-error").removeClass("error");
            $("#details-error").text("");
        });
    });
</script>

<!-- Reset User's Password Ajax -->
<script>
    $(document).ready(function () {
        // Handler for the edit button click event
        $("#resetUsersBtn").click(function (event) {
            event.preventDefault(); // Prevent form submission

            // Retrieve the project ID from the data attribute
            const idValue = $(this).data("id");

            if (!idValue) {
                console.error("ID is not set in the data attribute.");
                return;
            }

            // Validate inputs
            let valid = true;

            // password validation
            const passwordValue = $("#password").val().trim();
            if (passwordValue === "") {
                $("#password").addClass("is-invalid");
                valid = false;
            } else {
                $("#password").removeClass("is-invalid");
            }

            if (!valid) {
                return;
            }

            // Add the spinner to the button
            $("#resetUsersBtn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
            $("#resetUsersBtn").prop("disabled", true); // Disable the button

            // Send an Ajax request to the edit controller
            $.ajax({
                url: "<?=$this->siteUrl()?>/admin/users/reset-password/" + idValue, // Modify the URL according to your edit
                type: 'POST',
                data: $('#reset-form').serialize(),
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
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
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
                    $("#resetUsersBtn").html("Yes, Reset It");
                    $("#resetUsersBtn").prop("disabled", false);
                },
            });
        });

        // Add event listener for input fields to remove 'is-invalid' class on input
        $("#password").on("input", function () {
            $(this).removeClass("is-invalid");
        });
    });
</script>

<!-- Block User's Account Ajax -->
<script>
    $(document).ready(function () {
        // Handler for the edit button click event
        $("#blockUsersBtn").click(function (event) {
            event.preventDefault(); // Prevent form submission

            // Retrieve the project ID from the data attribute
            const idValue = $(this).data("id");

            if (!idValue) {
                console.error("ID is not set in the data attribute.");
                return;
            }

            // Validate inputs
            let valid = true;

            // userid validation
            const useridValue = $("#userid").val().trim();
            if (useridValue === "") {
                $("#userid").addClass("is-invalid");
                valid = false;
            } else {
                $("#userid").removeClass("is-invalid");
            }

            if (!valid) {
                return;
            }

            // Add the spinner to the button
            $("#blockUsersBtn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
            $("#blockUsersBtn").prop("disabled", true); // Disable the button

            // Send an Ajax request to the edit controller
            $.ajax({
                url: "<?=$this->siteUrl()?>/admin/users/block-user/" + idValue, // Modify the URL according to your edit
                type: 'POST',
                data: $('#block-form').serialize(),
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
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
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
                    $("#blockUsersBtn").html("Yes, Block");
                    $("#blockUsersBtn").prop("disabled", false);
                },
            });
        });

        // Add event listener for input fields to remove 'is-invalid' class on input
        $("#userid").on("input", function () {
            $(this).removeClass("is-invalid");
        });
    });
</script>

<!-- Activate User's Account Ajax -->
<script>
    $(document).ready(function () {
        // Handler for the edit button click event
        $("#activateUsersBtn").click(function (event) {
            event.preventDefault(); // Prevent form submission

            // Retrieve the project ID from the data attribute
            const idValue = $(this).data("id");

            if (!idValue) {
                console.error("ID is not set in the data attribute.");
                return;
            }

            // Validate inputs
            let valid = true;

            // userid validation
            const useridValue = $("#userid").val().trim();
            if (useridValue === "") {
                $("#userid").addClass("is-invalid");
                valid = false;
            } else {
                $("#userid").removeClass("is-invalid");
            }

            if (!valid) {
                return;
            }

            // Add the spinner to the button
            $("#activateUsersBtn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
            $("#activateUsersBtn").prop("disabled", true); // Disable the button

            // Send an Ajax request to the edit controller
            $.ajax({
                url: "<?=$this->siteUrl()?>/admin/users/activate-user/" + idValue, // Modify the URL according to your edit
                type: 'POST',
                data: $('#activate-form').serialize(),
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
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
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
                    $("#activateUsersBtn").html("Yes, Activate");
                    $("#activateUsersBtn").prop("disabled", false);
                },
            });
        });

        // Add event listener for input fields to remove 'is-invalid' class on input
        $("#userid").on("input", function () {
            $(this).removeClass("is-invalid");
        });
    });
</script>

<!-- Delete User Account -->
<script>
    $(document).ready(function () {
        // Handler for the delete-modal-yes button click event
        $("#deleteUsersBtn").click(function (event) {
            event.preventDefault(); // Prevent form submission

            // Retrieve the user ID from the data attribute
            const userId = $(this).data("user");

            if (!userId) {
                console.error("User ID is not set in the data attribute.");
                return;
            }

            // Add the spinner to the button
            $("#deleteUsersBtn").html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Processing...</span>');
            $("#deleteUsersBtn").prop("disabled", true); // Disable the button

            // Send an Ajax request to the confirmation controller
            $.ajax({
                url: '<?=$this->siteUrl()?>/requests/delete_from_admin',
                type: 'GET',
                data: 'id=' + userId,
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
                        // Show an error message
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
                complete: function () {
                    // Remove the spinner from the button and enable the button
                    $("#deleteUsersBtn").html("Yes, Delete");
                    $("#deleteUsersBtn").prop("disabled", false);
                },
            });
        });
    });
</script>

<!-- Load User Deposits -->
<script>
    $(document).ready(function() {
        let page = 2; // Initial page number
        const container = $("#loadMoreDepositsContainer");
        const loadMoreDeposits = $(".loadMoreDeposits");
        const lastPageDiv = $("#DepositsLastpage"); // Get the reference to the last page div

        loadMoreDeposits.click(function(e) {
            e.preventDefault();

            const userId = $(this).data("id");
            const currentPage = $(this).data("page");

            loadMoreDeposits.html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Loading...</span>');

            // Send an AJAX request to fetch more content
            $.ajax({
                url: "<?=$this->siteUrl()?>/admin/load_user_deposits", // Update with the correct URL
                type: "GET",
                data: { 
                    userid: userId,
                    page: currentPage 
                },
                success: function(response) {
                    const deposits = response.deposits;

                    // Construct the HTML markup for each deposit item
                    let html = '';
                    for (let i = 0; i < deposits.length; i++) {
                        const deposit = deposits[i];

                        // Retrieve the associated plan information
                        const gateway = getGatewayById(deposit.method_code);

                        html += '<tr>';
                        html += '<td>';
                        html += '<div class="d-flex align-items-center">';

                        if (gateway) {
                            html += '<img src="<?= $this->siteUrl() ?>/<?= PUBLIC_PATH ?>/<?= UPLOADS_PATH ?>/deposit/' + gateway.image + '" alt="" class="avatar-xs rounded-circle" />';
                        }

                        html += '<div class="ms-2">';

                        if (gateway) {
                            html += '<a href="<?=$this->siteUrl()?>/admin/deposits/view-deposit/'+ deposit.depositId +'"><h6 class="fs-15 mb-1">' + gateway.name + '</h6></a>';
                        }

                        html += '<p class="mb-0 text-muted">' + formatDate(deposit.created_at) + '</p>';

                        if (deposit.status == "0") {
                            html += '<p class="mb-0 text-muted">Initiated</p>';
                        } else if (deposit.status == "1") {
                            html += '<p class="mb-0 text-success">Completed</p>';
                        } else if (deposit.status == "2") {
                            html += '<p class="mb-0 text-warning">Pending</p>';
                        } else if (deposit.status == "3") {
                            html += '<p class="mb-0 text-danger">Rejected</p>';
                        }

                        const currency = '$';
                        html += '</div></div></td>';
                        html += '<td>' + formatCurrency(currency, deposit.amount) + '</td>';

                        html += '<td>';
                        html += '<div class="dropdown d-inline-block">';
                        html += '<button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="ri-more-fill align-middle"></i></button>';

                        html += '<ul class="dropdown-menu dropdown-menu-end">';

                        html += '<li><a href="<?=$this->siteUrl()?>/admin/deposits/view-deposit/'+ deposit.depositId +'" class="dropdown-item"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View</a></li>';

                        html += '<button data-bs-toggle="modal" data-bs-target="#deleteDepositModal" data-deposit="'+ deposit.depositId +'" class="dropdown-item"> <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete </button>';

                        html += '</ul></div></td></tr>';
                    }

                    // Append the new content to the container
                    container.find(".deposits").append(html);

                    // Update the page number
                    page++;

                    // If there is no more content to load, hide the load more button
                    if (deposits.length === 0) {
                        loadMoreDeposits.hide();
                        lastPageDiv.removeClass("d-none");
                    } else {
                        loadMoreDeposits.text("Load More"); // Restore the button text
                        loadMoreDeposits.data("page", page); // Update the data-page attribute
                    }
                },
                error: function() {
                    // Handle the error case
                    loadMoreDeposits.text("Load More"); // Restore the button text
                },
            });
        });

        // Custom function to retrieve gateways by ID
        function getGatewayById(method_code) {
            const gatewaysMap = {};
            const gateways = <?= json_encode($data['gateways']) ?>; // Retrieve the gateway array from PHP

            // Build a map of gateways keyed by their method_code
            gateways.forEach(gateway => {
                gatewaysMap[gateway.method_code] = gateway;
            });

            // Return the gateway if found, otherwise return null
            return gatewaysMap[method_code] || null;
        }

        function formatCurrency(currency, amount) {
            // Parse the amount as float to ensure it's treated as a number
            amount = parseFloat(amount);

            switch(currency) {
                case '€':
                    return '€' + amount.toFixed(2);
                case '£':
                    return '£' + amount.toFixed(2);
                case '$':
                    return '$' + amount.toFixed(2);
                default:
                    return currency + ' ' + amount.toFixed(2);
            }
        }

        function formatDate(createdAt){

            // Convert the createdAt string to a Date object
            const createdAtDate = new Date(createdAt);

            // Format the date components
            const day = createdAtDate.getDate().toString().padStart(2, '0');
            const month = (createdAtDate.getMonth() + 1).toString().padStart(2, '0'); // Months are zero-indexed, so add 1
            const year = createdAtDate.getFullYear();
            let hours = createdAtDate.getHours().toString().padStart(2, '0');
            const minutes = createdAtDate.getMinutes().toString().padStart(2, '0');
            const meridiem = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12; // Handle midnight (0 hours)

            // Construct the formatted date string
            return day + '-' + month + '-' + year + ' ' + hours + ':' + minutes + ' ' + meridiem;
        }
    });
</script>

<!-- Load User Withdrawals -->
<script>
    $(document).ready(function() {
        let page = 2; // Initial page number
        const container = $("#loadMorePayoutsContainer");
        const loadMorePayouts = $(".loadMorePayouts");
        const lastPageDiv = $("#PayoutsLastpage"); // Get the reference to the last page div

        loadMorePayouts.click(function(e) {
            e.preventDefault();

            const userId = $(this).data("id");
            const currentPage = $(this).data("page");

            loadMorePayouts.html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Loading...</span>');

            // Send an AJAX request to fetch more content
            $.ajax({
                url: "<?=$this->siteUrl()?>/admin/load_user_payouts", // Update with the correct URL
                type: "GET",
                data: { 
                    userid: userId,
                    page: currentPage 
                },
                success: function(response) {
                    const payouts = response.payouts;

                    // Construct the HTML markup for each payout item
                    let html = '';
                    for (let i = 0; i < payouts.length; i++) {
                        const payout = payouts[i];

                        // Retrieve the associated plan information
                        const gateway = getGatewayById(payout.method_code);

                        html += '<tr>';
                        html += '<td>';
                        html += '<div class="d-flex align-items-center">';

                        if (gateway) {
                            html += '<img src="<?= $this->siteUrl() ?>/<?= PUBLIC_PATH ?>/<?= UPLOADS_PATH ?>/withdrawal/' + gateway.image + '" alt="" class="avatar-xs rounded-circle" />';
                        }

                        html += '<div class="ms-2">';

                        if (gateway) {
                            html += '<a href="<?=$this->siteUrl()?>/admin/withdrawals/view-withdrawal/'+ payout.withdrawId +'"><h6 class="fs-15 mb-1">'+ gateway.name +'</h6></a>';
                        }

                        html += '<p class="mb-0 text-muted">'  + formatDate(payout.created_at) + '</p>';

                        if (payout.status == "0") {
                            html += '<p class="mb-0 text-muted">Initiated</p>';
                        } else if (payout.status == "1") {
                            html += '<p class="mb-0 text-success">Completed</p>';
                        } else if (payout.status == "2") {
                            html += '<p class="mb-0 text-warning">Pending</p>';
                        } else if (payout.status == "3") {
                            html += '<p class="mb-0 text-danger">Rejected</p>';
                        }

                        const currency = '$';
                        html += '</div></div></td>';
                        html += '<td>'+formatCurrency(currency, payout.amount)+'</td>';

                        html += '<td>';
                        html += '<div class="dropdown d-inline-block">';
                        html += '<button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="ri-more-fill align-middle"></i></button>';

                        html += '<ul class="dropdown-menu dropdown-menu-end">';

                        html += '<li><a href="<?=$this->siteUrl()?>/admin/withdrawals/view-withdrawal/'+ payout.withdrawId +'" class="dropdown-item"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View</a></li>';

                        html += '<button data-bs-toggle="modal" data-bs-target="#deleteWithdrawalModal" data-withdrawal="'+ payout.withdrawId +'" class="dropdown-item"> <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete </button>';

                        html += '</ul></div></td></tr>';
                    }

                    // Append the new content to the container
                    container.find(".payouts").append(html);

                    // Update the page number
                    page++;

                    // If there is no more content to load, hide the load more button
                    if (payouts.length === 0) {
                        loadMorePayouts.hide();
                        lastPageDiv.removeClass("d-none");
                    } else {
                        loadMorePayouts.text("Load More"); // Restore the button text
                        loadMorePayouts.data("page", page); // Update the data-page attribute
                    }
                },
                error: function() {
                    // Handle the error case
                    loadMorePayouts.text("Load More"); // Restore the button text
                },
            });
        });

        // Custom function to retrieve gateways by ID
        function getGatewayById(method_code) {
            const gatewaysMap = {};
            const gateways = <?= json_encode($data['withdrawal-gateways']) ?>; // Retrieve the gateway array from PHP

            // Build a map of gateways keyed by their method_code
            gateways.forEach(gateway => {
                gatewaysMap[gateway.method_code] = gateway;
            });

            // Return the gateway if found, otherwise return null
            return gatewaysMap[method_code] || null;
        }

        function formatCurrency(currency, amount) {
            // Parse the amount as float to ensure it's treated as a number
            amount = parseFloat(amount);

            switch(currency) {
                case '€':
                    return '€' + amount.toFixed(2);
                case '£':
                    return '£' + amount.toFixed(2);
                case '$':
                    return '$' + amount.toFixed(2);
                default:
                    return currency + ' ' + amount.toFixed(2);
            }
        }

        function formatDate(createdAt){

            // Convert the createdAt string to a Date object
            const createdAtDate = new Date(createdAt);

            // Format the date components
            const day = createdAtDate.getDate().toString().padStart(2, '0');
            const month = (createdAtDate.getMonth() + 1).toString().padStart(2, '0'); // Months are zero-indexed, so add 1
            const year = createdAtDate.getFullYear();
            let hours = createdAtDate.getHours().toString().padStart(2, '0');
            const minutes = createdAtDate.getMinutes().toString().padStart(2, '0');
            const meridiem = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12; // Handle midnight (0 hours)

            // Construct the formatted date string
            return day + '-' + month + '-' + year + ' ' + hours + ':' + minutes + ' ' + meridiem;
        }
    });
</script>

<!-- Load User Investments -->
<script>
    $(document).ready(function() {
        let page = 2; // Initial page number
        const container = $("#loadMoreInvestsContainer");
        const loadMoreInvests = $(".loadMoreInvests");
        const lastPageDiv = $("#InvestsLastpage"); // Get the reference to the last page div

        loadMoreInvests.click(function(e) {
            e.preventDefault();

            const userId = $(this).data("id");
            const currentPage = $(this).data("page");

            loadMoreInvests.html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Loading...</span>');

            // Send an AJAX request to fetch more content
            $.ajax({
                url: "<?=$this->siteUrl()?>/admin/load_user_invests", // Update with the correct URL
                type: "GET",
                data: { 
                    userid: userId,
                    page: currentPage 
                },
                success: function(response) {
                    const invests = response.invests;

                    // Construct the HTML markup for each invested item
                    let html = '';
                    for (let i = 0; i < invests.length; i++) {
                        const invest = invests[i];

                        // Retrieve the associated plan information
                        const plan = getPlanById(invest.planId);

                        html += '<tr>';
                        html += '<td>';
                        html += '<div class="d-flex align-items-center">';

                        html += '<img src="<?= $this->siteUrl() ?>/<?= $this->themePath() ?>/assets/frontend/templates/images/investment.png" alt="" class="avatar-xs rounded-circle" />';

                        html += '<div class="ms-2">';

                        if (plan) {
                            html += '<a href="<?=$this->siteUrl()?>/admin/investments/view-investment/'+ invest.investId +'"><h6 class="fs-15 mb-1">'+ plan.name +'</h6></a>';
                        }

                        html += '<p class="mb-0 text-muted">'  + formatDate(invest.initiated_at) + '</p>';

                        if (invest.status == "1") {
                            html += '<p class="mb-0 text-success">Completed</p>';
                        } else if (invest.status == "2") {
                            html += '<p class="mb-0 text-warning">Pending</p>';
                        } else if (invest.status == "3") {
                            html += '<p class="mb-0 text-muted">Initiated</p>';
                        } else if (invest.status == "4") {
                            html += '<p class="mb-0 text-danger">Cancelled</p>';
                        }

                        const currency = '$';
                        html += '</div></div></td>';
                        html += '<td>'+formatCurrency(currency, invest.amount)+'</td>';

                        html += '<td>';
                        html += '<div class="dropdown d-inline-block">';
                        html += '<button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="ri-more-fill align-middle"></i></button>';

                        html += '<ul class="dropdown-menu dropdown-menu-end">';

                        html += '<li><a href="<?=$this->siteUrl()?>/admin/investments/view-investment/'+ invest.investId +'" class="dropdown-item"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View</a></li>';

                        html += '<button data-bs-toggle="modal" data-bs-target="#deleteInvestmentModal" data-investment="'+ invest.investId +'" class="dropdown-item"> <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete </button>';

                        html += '</ul></div></td></tr>';
                    }
                    
                    // Append the new content to the container
                    container.find(".invests").append(html);

                    // Update the page number
                    page++;

                    // If there is no more content to load, hide the load more button
                    if (invests.length === 0) {
                        loadMoreInvests.hide();
                        lastPageDiv.removeClass("d-none");
                    } else {
                        loadMoreInvests.text("Load More"); // Restore the button text
                        loadMoreInvests.data("page", page); // Update the data-page attribute
                    }
                },
                error: function() {
                    // Handle the error case
                    loadMoreInvests.text("Load More"); // Restore the button text
                },
            });
        });

        // Custom function to retrieve plans by ID
        function getPlanById(planId) {
            const plansMap = {};
            const plans = <?= json_encode($data['plans']) ?>; // Retrieve the plan array from PHP

            // Build a map of plans keyed by their planId
            plans.forEach(plan => {
                plansMap[plan.planId] = plan;
            });

            // Return the plan if found, otherwise return null
            return plansMap[planId] || null;
        }

        function formatCurrency(currency, amount) {
            // Parse the amount as float to ensure it's treated as a number
            amount = parseFloat(amount);

            switch(currency) {
                case '€':
                    return '€' + amount.toFixed(2);
                case '£':
                    return '£' + amount.toFixed(2);
                case '$':
                    return '$' + amount.toFixed(2);
                default:
                    return currency + ' ' + amount.toFixed(2);
            }
        }

        function formatDate(createdAt){

            // Convert the createdAt string to a Date object
            const createdAtDate = new Date(createdAt);

            // Format the date components
            const day = createdAtDate.getDate().toString().padStart(2, '0');
            const month = (createdAtDate.getMonth() + 1).toString().padStart(2, '0'); // Months are zero-indexed, so add 1
            const year = createdAtDate.getFullYear();
            let hours = createdAtDate.getHours().toString().padStart(2, '0');
            const minutes = createdAtDate.getMinutes().toString().padStart(2, '0');
            const meridiem = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12; // Handle midnight (0 hours)

            // Construct the formatted date string
            return day + '-' + month + '-' + year + ' ' + hours + ':' + minutes + ' ' + meridiem;
        }
    });
</script>

<!-- Load User Referrals -->
<script>
    $(document).ready(function() {
        let page = 2; // Initial page number
        const container = $("#loadMoreReferralsContainer");
        const loadMoreReferrals = $(".loadMoreReferrals");
        const lastPageDiv = $("#ReferralsLastpage"); // Get the reference to the last page div

        loadMoreReferrals.click(function(e) {
            e.preventDefault();

            const userId = $(this).data("id");
            const currentPage = $(this).data("page");

            loadMoreReferrals.html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Loading...</span>');

            // Send an AJAX request to fetch more content
            $.ajax({
                url: "<?=$this->siteUrl()?>/admin/load_user_referrals", // Update with the correct URL
                type: "GET",
                data: { 
                    userid: userId,
                    page: currentPage 
                },
                success: function(response) {
                    const referrals = response.referrals;

                    // Construct the HTML markup for each referral item
                    let html = '';
                    for (let i = 0; i < referrals.length; i++) {
                        const referral = referrals[i];

                        html += '<tr>';

                        html += '<td>';
                        html += '<div class="d-flex align-items-center">';
                        html += '<img src="<?=$this->siteUrl()?>/<?=PUBLIC_PATH?>/<?=UPLOADS_PATH?>/users/'+ referral.imagelocation +'" alt="" class="avatar-xs rounded-circle" />';
                        html += '<div class="ms-2">';
                        html += '<a href="<?=$this->siteUrl()?>/admin/users/view-profile/'+ referral.userid +'"><h6 class="fs-15 mb-1">'+ referral.firstname +' '+ referral.lastname +'</h6></a>';
                        html += '<p class="mb-0 text-muted">Joined - ' + formatDate(referral.registration_date) + '</p>';
                        html += '</div>';
                        html += '</div>';
                        html += '</td>';

                        html += '<td>';
                        if (referral.status == 1) {
                            html += '<p class="mb-0 text-success">Active</p>';
                        } else if (referral.status == 2) {
                            html += '<p class="mb-0 text-danger">Blocked</p>';
                        }
                        html += '</td>';

                        html += '<td>';
                        html += '<div class="dropdown d-inline-block">';
                        html += '<a href="<?=$this->siteUrl()?>/admin/users/view-profile/'+ referral.userid +'" class="btn btn-sm btn-outline-success material-shadow-none"><i class="ri-user-add-line align-middle"></i></a>';
                        html += '</div>';
                        html += '</td>';

                        html += '</tr>';
                    }
                    
                    // Append the new content to the container
                    container.find(".referrals").append(html);

                    // Update the page number
                    page++;

                    // If there is no more content to load, hide the load more button
                    if (referrals.length === 0) {
                        loadMoreReferrals.hide();
                        lastPageDiv.removeClass("d-none");
                    } else {
                        loadMoreReferrals.text("Load More"); // Restore the button text
                        loadMoreReferrals.data("page", page); // Update the data-page attribute
                    }
                },
                error: function() {
                    // Handle the error case
                    loadMoreReferrals.text("Load More"); // Restore the button text
                },
            });
        });

        function formatDate(createdAt){

            // Convert the createdAt string to a Date object
            const createdAtDate = new Date(createdAt);

            // Format the date components
            const day = createdAtDate.getDate().toString().padStart(2, '0');
            const month = (createdAtDate.getMonth() + 1).toString().padStart(2, '0'); // Months are zero-indexed, so add 1
            const year = createdAtDate.getFullYear();
            let hours = createdAtDate.getHours().toString().padStart(2, '0');
            const minutes = createdAtDate.getMinutes().toString().padStart(2, '0');
            const meridiem = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12; // Handle midnight (0 hours)

            // Construct the formatted date string
            return day + '-' + month + '-' + year + ' ' + hours + ':' + minutes + ' ' + meridiem;
        }
    });
</script>

<!-- Load User Commissions -->
<script>
    $(document).ready(function() {
        let page = 2; // Initial page number
        const container = $("#loadMoreCommissionsContainer");
        const loadMoreCommissions = $(".loadMoreCommissions");
        const lastPageDiv = $("#CommissionsLastpage"); // Get the reference to the last page div

        loadMoreCommissions.click(function(e) {
            e.preventDefault();

            const userId = $(this).data("id");
            const currentPage = $(this).data("page");

            loadMoreCommissions.html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Loading...</span>');

            // Send an AJAX request to fetch more content
            $.ajax({
                url: "<?=$this->siteUrl()?>/admin/load_user_commissions", // Update with the correct URL
                type: "GET",
                data: { 
                    userid: userId,
                    page: currentPage 
                },
                success: function(response) {
                    const commissions = response.commissions;

                    // Construct the HTML markup for each deposit item
                    let html = '';
                    for (let i = 0; i < commissions.length; i++) {
                        const commission = commissions[i];
                        const user = getUserById(commission.from_id);

                        html += '<tr><td><div class="d-flex align-items-center">';
                        
                        if (user) {
                            html += `<img src="<?= $this->siteUrl() ?>/<?= PUBLIC_PATH ?>/<?= UPLOADS_PATH ?>/users/${user.imagelocation}" alt="" class="avatar-xs rounded-circle" />`;
                        }
                        
                        html += '<div class="ms-2">';
                        
                        if (user) {
                            html += `<a><h6 class="fs-15 mb-1">$${commission.commission_amount} ${commission.title}</h6></a>`;
                        }
                        
                        html += `<p class="mb-0 text-muted">${formatDate(commission.created_at)}</p>`;
                        
                        html += '</div></div></td></tr>';
                    }

                    // Append the new content to the container
                    container.find(".commissions").append(html);

                    // Update the page number
                    page++;

                    // If there is no more content to load, hide the load more button
                    if (commissions.length === 0) {
                        loadMoreCommissions.hide();
                        lastPageDiv.removeClass("d-none");
                    } else {
                        loadMoreCommissions.text("Load More"); // Restore the button text
                        loadMoreCommissions.data("page", page); // Update the data-page attribute
                    }
                },
                error: function() {
                    // Handle the error case
                    loadMoreCommissions.text("Load More"); // Restore the button text
                },
            });
        });

        // Custom function to retrieve user details by ID
        function getUserById(from_id) {
            const usersMap = {};
            const users = <?= json_encode($data['users']) ?>; // Retrieve the user array from PHP

            // Build a map of users keyed by their IDs
            users.forEach(user => {
                usersMap[user.userid] = user;
            });

            // Return the user if found, otherwise return null
            return usersMap[from_id] || null;
        }

        function formatDate(createdAt){

            // Convert the createdAt string to a Date object
            const createdAtDate = new Date(createdAt);

            // Format the date components
            const day = createdAtDate.getDate().toString().padStart(2, '0');
            const month = (createdAtDate.getMonth() + 1).toString().padStart(2, '0'); // Months are zero-indexed, so add 1
            const year = createdAtDate.getFullYear();
            let hours = createdAtDate.getHours().toString().padStart(2, '0');
            const minutes = createdAtDate.getMinutes().toString().padStart(2, '0');
            const meridiem = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12; // Handle midnight (0 hours)

            // Construct the formatted date string
            return day + '-' + month + '-' + year + ' ' + hours + ':' + minutes + ' ' + meridiem;
        }
    });
</script>

<!-- Load User Transactions -->
<script>
    $(document).ready(function() {
        let page = 2; // Initial page number
        const container = $("#loadMoreTransactionsContainer");
        const loadMoreTransactions = $(".loadMoreTransactions");
        const lastPageDiv = $("#TransactionsLastpage"); // Get the reference to the last page div

        loadMoreTransactions.click(function(e) {
            e.preventDefault();

            const userId = $(this).data("id");
            const currentPage = $(this).data("page");

            loadMoreTransactions.html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Loading...</span>');

            // Send an AJAX request to fetch more content
            $.ajax({
                url: "<?=$this->siteUrl()?>/admin/load_user_transactions", // Update with the correct URL
                type: "GET",
                data: { 
                    userid: userId,
                    page: currentPage 
                },
                success: function(response) {
                    const transactions = response.transactions;

                    // Construct the HTML markup for each invested item
                    let html = '';
                    for (let i = 0; i < transactions.length; i++) {
                        const transaction = transactions[i];

                        html += '<tr>';
                        html += '<td>';
                        html += '<div class="d-flex align-items-center">';

                        if (transaction.trx_type == "+") {
                            html += '<img src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/frontend/templates/images/cashin.png" alt="Transaction" class="avatar-xs rounded-circle">';
                        }else if (transaction.trx_type == "-") {
                            html += '<img src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/frontend/templates/images/cashout.png" alt="Transaction" class="avatar-xs rounded-circle">';
                        }

                        html += '<div class="ms-2">';

                        html += '<a><h6 class="fs-15 mb-1">'+ transaction.details +'</h6></a>';

                        html += '<p class="mb-0 text-muted">'  + formatDate(transaction.created_at) + '</p>';

                        const currency = '$';
                        html += '</div></div></td>';
                        html += '<td>'+formatCurrency(currency, transaction.amount)+'</td>';

                        html += '<td>';
                        if (transaction.trx_type == "+") {
                            html += '<span class="badge badge-label bg-success"><i class="mdi mdi-circle-medium"></i> Credit</span>';
                        }else if (transaction.trx_type == "-") {
                            html += '<span class="badge badge-label bg-danger"><i class="mdi mdi-circle-medium"></i> Debit</span>';
                        }
                        html += '</td>';

                        html += '</tr>';
                    }
                    
                    // Append the new content to the container
                    container.find(".transactions").append(html);

                    // Update the page number
                    page++;

                    // If there is no more content to load, hide the load more button
                    if (transactions.length === 0) {
                        loadMoreTransactions.hide();
                        lastPageDiv.removeClass("d-none");
                    } else {
                        loadMoreTransactions.text("Load More"); // Restore the button text
                        loadMoreTransactions.data("page", page); // Update the data-page attribute
                    }
                },
                error: function() {
                    // Handle the error case
                    loadMoreTransactions.text("Load More"); // Restore the button text
                },
            });
        });

        function formatCurrency(currency, amount) {
            // Parse the amount as float to ensure it's treated as a number
            amount = parseFloat(amount);

            switch(currency) {
                case '€':
                    return '€' + amount.toFixed(2);
                case '£':
                    return '£' + amount.toFixed(2);
                case '$':
                    return '$' + amount.toFixed(2);
                default:
                    return currency + ' ' + amount.toFixed(2);
            }
        }

        function formatDate(createdAt){

            // Convert the createdAt string to a Date object
            const createdAtDate = new Date(createdAt);

            // Format the date components
            const day = createdAtDate.getDate().toString().padStart(2, '0');
            const month = (createdAtDate.getMonth() + 1).toString().padStart(2, '0'); // Months are zero-indexed, so add 1
            const year = createdAtDate.getFullYear();
            let hours = createdAtDate.getHours().toString().padStart(2, '0');
            const minutes = createdAtDate.getMinutes().toString().padStart(2, '0');
            const meridiem = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12; // Handle midnight (0 hours)

            // Construct the formatted date string
            return day + '-' + month + '-' + year + ' ' + hours + ':' + minutes + ' ' + meridiem;
        }
    });
</script>

<!-- Add Deposit Record Ajax -->
<script>
    $(document).ready(function () {
        // Handler for the edit button click event
        $("#addDepositBtn").click(function (event) {
            event.preventDefault(); // Prevent form submission

            // Validate inputs
            let valid = true;

            // deposit-amount validation
            const amountValue = $("#deposit-amount").val().trim();
            if (amountValue === "") {
                $("#deposit-amount").addClass("is-invalid");
                valid = false;
            } else {
                $("#deposit-amount").removeClass("is-invalid");
            }

            if (!valid) {
                return;
            }

            // Add the spinner to the button
            $("#addDepositBtn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
            $("#addDepositBtn").prop("disabled", true); // Disable the button

            // Send an Ajax request to the edit controller
            $.ajax({
                url: "<?=$this->siteUrl()?>/admin/users/add-deposit-record", // Modify the URL according to your edit
                type: 'POST',
                data: $('#add-deposit-form').serialize(),
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
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
                    } else if (response.status === "error") {
                        // The edit failed, show an iziToast error notification
                        iziToast.error({
                            message: response.message,
                            position: "topRight",
                        });
                    } else if (response.status === "warning") {
                        // Show an iziToast warning notification
                        iziToast.error({
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
                    $("#addDepositBtn").html("Submit Form");
                    $("#addDepositBtn").prop("disabled", false);
                },
            });
        });

        // Add event listener for input fields to remove 'is-invalid' class on input
        $("#deposit-amount").on("input", function () {
            $(this).removeClass("is-invalid");
        });
    });
</script>

<!-- Delete Deposit Record Ajax -->
<script>
    $(document).ready(function () {
        // Handler for the edit button click event
        $("#deleteDepositBtn").click(function (event) {
            event.preventDefault(); // Prevent form submission

            // Validate inputs
            let valid = true;

            // delete-depositId validation
            const idValue = $("#delete-depositId").val().trim();
            if (idValue === "") {
                $("#delete-depositId").addClass("is-invalid");
                valid = false;
            } else {
                $("#delete-depositId").removeClass("is-invalid");
            }

            if (!valid) {
                return;
            }

            // Add the spinner to the button
            $("#deleteDepositBtn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
            $("#deleteDepositBtn").prop("disabled", true); // Disable the button

            // Send an Ajax request to the edit controller
            $.ajax({
                url: "<?=$this->siteUrl()?>/requests/delete_deposit_record", // Modify the URL according to your edit
                type: 'POST',
                data: $('#delete-deposit-form').serialize(),
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
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
                    } else if (response.status === "error") {
                        // The edit failed, show an iziToast error notification
                        iziToast.error({
                            message: response.message,
                            position: "topRight",
                        });
                    } else if (response.status === "warning") {
                        // Show an iziToast warning notification
                        iziToast.error({
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
                    $("#deleteDepositBtn").html("Yes, Delete");
                    $("#deleteDepositBtn").prop("disabled", false);
                },
            });
        });

        // Add event listener for input fields to remove 'is-invalid' class on input
        $("#delete-depositId").on("input", function () {
            $(this).removeClass("is-invalid");
        });
    });
</script>

<!-- Add Withdrawal Record Ajax -->
<script>
    $(document).ready(function () {
        // Handler for the edit button click event
        $("#addWithdrawalBtn").click(function (event) {
            event.preventDefault(); // Prevent form submission

            // Validate inputs
            let valid = true;

            // withdrawal-amount validation
            const amountValue = $("#withdrawal-amount").val().trim();
            if (amountValue === "") {
                $("#withdrawal-amount").addClass("is-invalid");
                valid = false;
            } else {
                $("#withdrawal-amount").removeClass("is-invalid");
            }

            // withdrawal-wallet validation
            const walletValue = $("#withdrawal-wallet").val().trim();
            if (walletValue === "") {
                $("#withdrawal-wallet").addClass("is-invalid");
                valid = false;
            } else {
                $("#withdrawal-wallet").removeClass("is-invalid");
            }

            if (!valid) {
                return;
            }

            // Add the spinner to the button
            $("#addWithdrawalBtn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
            $("#addWithdrawalBtn").prop("disabled", true); // Disable the button

            // Send an Ajax request to the edit controller
            $.ajax({
                url: "<?=$this->siteUrl()?>/admin/users/add-withdrawal-record", // Modify the URL according to your edit
                type: 'POST',
                data: $('#add-withdrawal-form').serialize(),
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
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
                    } else if (response.status === "error") {
                        // The edit failed, show an iziToast error notification
                        iziToast.error({
                            message: response.message,
                            position: "topRight",
                        });
                    } else if (response.status === "warning") {
                        // Show an iziToast warning notification
                        iziToast.error({
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
                    $("#addWithdrawalBtn").html("Submit Form");
                    $("#addWithdrawalBtn").prop("disabled", false);
                },
            });
        });

        // Add event listener for input fields to remove 'is-invalid' class on input
        $("#withdrawal-amount, #withdrawal-wallet").on("input", function () {
            $(this).removeClass("is-invalid");
        });
    });
</script>

<!-- Delete Withdrawal Record Ajax -->
<script>
    $(document).ready(function () {
        // Handler for the edit button click event
        $("#deleteWithdrawBtn").click(function (event) {
            event.preventDefault(); // Prevent form submission

            // Validate inputs
            let valid = true;

            // delete-withdrawId validation
            const idValue = $("#delete-withdrawId").val().trim();
            if (idValue === "") {
                $("#delete-withdrawId").addClass("is-invalid");
                valid = false;
            } else {
                $("#delete-withdrawId").removeClass("is-invalid");
            }

            if (!valid) {
                return;
            }

            // Add the spinner to the button
            $("#deleteWithdrawBtn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
            $("#deleteWithdrawBtn").prop("disabled", true); // Disable the button

            // Send an Ajax request to the edit controller
            $.ajax({
                url: "<?=$this->siteUrl()?>/requests/delete_withdrawal_record", // Modify the URL according to your edit
                type: 'POST',
                data: $('#delete-withdrawal-form').serialize(),
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
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
                    } else if (response.status === "error") {
                        // The edit failed, show an iziToast error notification
                        iziToast.error({
                            message: response.message,
                            position: "topRight",
                        });
                    } else if (response.status === "warning") {
                        // Show an iziToast warning notification
                        iziToast.error({
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
                    $("#deleteWithdrawBtn").html("Yes, Delete");
                    $("#deleteWithdrawBtn").prop("disabled", false);
                },
            });
        });

        // Add event listener for input fields to remove 'is-invalid' class on input
        $("#delete-withdrawId").on("input", function () {
            $(this).removeClass("is-invalid");
        });
    });
</script>

<!-- Add Investment Record Ajax -->
<script>
    $(document).ready(function () {
        // Handler for the edit button click event
        $("#addInvestmentBtn").click(function (event) {
            event.preventDefault(); // Prevent form submission

            // Validate inputs
            let valid = true;

            // investment-amount validation
            const amountValue = $("#investment-amount").val().trim();
            if (amountValue === "") {
                $("#investment-amount").addClass("is-invalid");
                valid = false;
            } else {
                $("#investment-amount").removeClass("is-invalid");
            }

            if (!valid) {
                return;
            }

            // Add the spinner to the button
            $("#addInvestmentBtn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
            $("#addInvestmentBtn").prop("disabled", true); // Disable the button

            // Send an Ajax request to the edit controller
            $.ajax({
                url: "<?=$this->siteUrl()?>/admin/users/add-investment-record", // Modify the URL according to your edit
                type: 'POST',
                data: $('#add-investment-form').serialize(),
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
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
                    } else if (response.status === "error") {
                        // The edit failed, show an iziToast error notification
                        iziToast.error({
                            message: response.message,
                            position: "topRight",
                        });
                    } else if (response.status === "warning") {
                        // Show an iziToast warning notification
                        iziToast.error({
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
                    $("#addInvestmentBtn").html("Submit Form");
                    $("#addInvestmentBtn").prop("disabled", false);
                },
            });
        });

        // Add event listener for input fields to remove 'is-invalid' class on input
        $("#investment-amount").on("input", function () {
            $(this).removeClass("is-invalid");
        });
    });
</script>

<!-- Delete Withdrawal Record Ajax -->
<script>
    $(document).ready(function () {
        // Handler for the edit button click event
        $("#deleteInvestmentBtn").click(function (event) {
            event.preventDefault(); // Prevent form submission

            // Validate inputs
            let valid = true;

            // delete-investId validation
            const idValue = $("#delete-investId").val().trim();
            if (idValue === "") {
                $("#delete-investId").addClass("is-invalid");
                valid = false;
            } else {
                $("#delete-investId").removeClass("is-invalid");
            }

            if (!valid) {
                return;
            }

            // Add the spinner to the button
            $("#deleteInvestmentBtn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
            $("#deleteInvestmentBtn").prop("disabled", true); // Disable the button

            // Send an Ajax request to the edit controller
            $.ajax({
                url: "<?=$this->siteUrl()?>/requests/delete_investment_record", // Modify the URL according to your edit
                type: 'POST',
                data: $('#delete-investment-form').serialize(),
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
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
                    } else if (response.status === "error") {
                        // The edit failed, show an iziToast error notification
                        iziToast.error({
                            message: response.message,
                            position: "topRight",
                        });
                    } else if (response.status === "warning") {
                        // Show an iziToast warning notification
                        iziToast.error({
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
                    $("#deleteInvestmentBtn").html("Yes, Delete");
                    $("#deleteInvestmentBtn").prop("disabled", false);
                },
            });
        });

        // Add event listener for input fields to remove 'is-invalid' class on input
        $("#delete-investId").on("input", function () {
            $(this).removeClass("is-invalid");
        });
    });
</script>

<!-- Load Deposit Methods -->
<script>
    $(document).ready(function() {
        let page = 2; // Initial page number
        const container = $("#loadMoreMethodsContainer");
        const loadMoreMethods = $(".loadMoreMethods");
        const lastPageDiv = $("#MethodsLastpage"); // Get the reference to the last page div

        loadMoreMethods.click(function(e) {
            e.preventDefault();

            const userId = $(this).data("id");
            const currentPage = $(this).data("page");

            loadMoreMethods.html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Loading...</span>');

            // Send an AJAX request to fetch more content
            $.ajax({
                url: "<?=$this->siteUrl()?>/admin/deposit_gateway", // Update with the correct URL
                type: "GET",
                data: { 
                    userid: userId,
                    page: currentPage 
                },
                success: function(response) {
                    const gateways = response.gateways;

                    // Construct the HTML markup for each invested item
                    let html = '';
                    for (let i = 0; i < gateways.length; i++) {
                        const gateway = gateways[i];

                        html += '<div class="col-xxl-3">';
                        html += '<div class="team-list row list-view-filter">';
                        html += '<div class="col">';
                        html += '<div class="card team-box">';
                        html += '<div class="card-body">';
                        html += '<div class="row align-items-center team-row">';
                        html += '<div class="col team-settings">';
                        html += '<div class="row">';
                        html += '<div class="col"></div>';
                        html += '<div class="col text-end dropdown">';
                        html += '<a href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false" class=""><i class="ri-more-fill fs-17"></i></a>';

                        html += '<ul class="dropdown-menu dropdown-menu-end">';

                        html += '<li><a class="dropdown-item edit-list" href="<?=$this->siteUrl()?>/admin/deposit_gateway/edit-method/'+ gateway.method_code +'"><i class="ri-pencil-line me-2 align-bottom text-muted"></i>Edit</a></li>';

                        if (gateway.status == "1") {
                            html += '<li><button class="dropdown-item remove-list" data-bs-toggle="modal" data-bs-target="#deactivateMethodModal" data-id="'+ gateway.method_code +'"><i class="ri-close-circle-line me-2 align-bottom text-muted"></i>Deactivate</button></li>';
                        } else if (gateway.status == "2") {
                            html += '<li><button class="dropdown-item remove-list" data-bs-toggle="modal" data-bs-target="#activateMethodModal" data-id="'+ gateway.method_code +'"><i class="ri-checkbox-circle-line me-2 align-bottom text-muted"></i>Activate</button></li>';
                        }

                        html += '</ul>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '<div class="col-lg-4 col">';
                        html += '<div class="team-profile-img">';

                        html += '<div class="avatar-lg img-thumbnail rounded-circle flex-shrink-0"><img src="<?= $this->siteUrl() ?>/<?= PUBLIC_PATH ?>/<?= UPLOADS_PATH ?>/deposit/' + gateway.image + '" alt="" class="member-img img-fluid d-block rounded-circle" /></div>';

                        html += '<div class="team-content">';
                        html += '<a class="member-name" data-bs-toggle="offcanvas" aria-controls="member-overview"><h5 class="fs-16 mb-1">'+ gateway.name +'</h5></a>';

                        html += '<p class="text-muted member-designation mb-0">';
                            if (gateway.status == "1") {
                                html += '<span class="badge bg-success-subtle text-approved">Active</span>';
                            } else if (gateway.status == "2") {
                                html += '<span class="badge bg-danger-subtle text-danger">Disabled</span>';
                            }
                        html += '</p>';

                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                    }
                    
                    // Append the new content to the container
                    container.find(".gateways").append(html);

                    // Update the page number
                    page++;

                    // If there is no more content to load, hide the load more button
                    if (gateways.length === 0) {
                        loadMoreMethods.hide();
                        lastPageDiv.removeClass("d-none");
                    } else {
                        loadMoreMethods.text("Load More"); // Restore the button text
                        loadMoreMethods.data("page", page); // Update the data-page attribute
                    }
                },
                error: function() {
                    // Handle the error case
                    loadMoreMethods.text("Load More"); // Restore the button text
                },
            });
        });
    });
</script>

<!-- Add Deposit Method Ajax -->
<script>
    $(document).ready(function () {
        // Handler for the rank button click event
        $("#addDepositMethodBtn").click(function (event) {
            event.preventDefault(); // Prevent form submission

            // Validate inputs
            let valid = true;

            // name validation
            const nameValue = $("#name").val().trim();
            if (nameValue === "") {
                $("#name").addClass("is-invalid");
                valid = false;
            } else {
                $("#name").removeClass("is-invalid");
            }

            // abbreviation validation
            const abbreviationValue = $("#abbreviation").val().trim();
            if (abbreviationValue === "") {
                $("#abbreviation").addClass("is-invalid");
                valid = false;
            } else {
                $("#abbreviation").removeClass("is-invalid");
            }

            // min_amount validation
            const min_amountValue = $("#min_amount").val().trim();
            if (min_amountValue === "") {
                $("#min_amount").addClass("is-invalid");
                valid = false;
            } else {
                $("#min_amount").removeClass("is-invalid");
            }

            // max_amount validation
            const max_amountValue = $("#max_amount").val().trim();
            if (max_amountValue === "") {
                $("#max_amount").addClass("is-invalid");
                valid = false;
            } else {
                $("#max_amount").removeClass("is-invalid");
            }

            // gateway_parameter validation
            const gateway_parameterValue = $("#gateway_parameter").val().trim();
            if (gateway_parameterValue === "") {
                $("#gateway_parameter").addClass("is-invalid");
                valid = false;
            } else {
                $("#gateway_parameter").removeClass("is-invalid");
            }

            if (!valid) {
                return;
            }

            // Add the spinner to the button
            $("#addDepositMethodBtn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
            $("#addDepositMethodBtn").prop("disabled", true); // Disable the button

            // Create a FormData object
            const formData = new FormData($('#add-deposit-method-form')[0]);

            // Add the selected file to the FormData object
            const fileInput = $("#hidden-input")[0];
            if (fileInput.files.length > 0) {
                formData.append('photoimg', fileInput.files[0]);
            }

            // Send an Ajax request to the edit controller
            $.ajax({
                url: "<?=$this->siteUrl()?>/admin/deposit_gateway/add-method", // Modify the URL according to your edit
                type: "POST",
                data: formData,
                dataType: "json",
                contentType: false,
                processData: false,
                success: function (response) {
                    // Check the response status
                    if (response.status === "success") {
                        // Show a success message
                        iziToast.success({
                            message: response.message,
                            position: 'topRight'
                        });

                        // Redirect after a delay (optional)
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
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
                    $("#addDepositMethodBtn").html("Submit Form");
                    $("#addDepositMethodBtn").prop("disabled", false);
                },
            });
        });

        // Add event listener for input fields to remove 'is-invalid' class on input
        $("#name, #abbreviation, #min_amount, #max_amount, #gateway_parameter").on("input", function () {
            $(this).removeClass("is-invalid");
        });
    });
</script>

<!-- Edit Deposit Method Ajax -->
<script>
    $(document).ready(function () {
        // Handler for the edit button click event
        $("#editDepositMethodBtn").click(function (event) {
            event.preventDefault(); // Prevent form submission

            // Retrieve the ID from the data attribute
            const idValue = $(this).data("id");

            if (!idValue) {
                console.error("ID is not set in the data attribute.");
                return;
            }

            // Validate inputs
            let valid = true;

            // name validation
            const nameValue = $("#name").val().trim();
            if (nameValue === "") {
                $("#name").addClass("is-invalid");
                valid = false;
            } else {
                $("#name").removeClass("is-invalid");
            }

            // abbreviation validation
            const abbreviationValue = $("#abbreviation").val().trim();
            if (abbreviationValue === "") {
                $("#abbreviation").addClass("is-invalid");
                valid = false;
            } else {
                $("#abbreviation").removeClass("is-invalid");
            }

            // min_amount validation
            const min_amountValue = $("#min_amount").val().trim();
            if (min_amountValue === "") {
                $("#min_amount").addClass("is-invalid");
                valid = false;
            } else {
                $("#min_amount").removeClass("is-invalid");
            }

            // max_amount validation
            const max_amountValue = $("#max_amount").val().trim();
            if (max_amountValue === "") {
                $("#max_amount").addClass("is-invalid");
                valid = false;
            } else {
                $("#max_amount").removeClass("is-invalid");
            }

            // gateway_parameter validation
            const gateway_parameterValue = $("#gateway_parameter").val().trim();
            if (gateway_parameterValue === "") {
                $("#gateway_parameter").addClass("is-invalid");
                valid = false;
            } else {
                $("#gateway_parameter").removeClass("is-invalid");
            }

            if (!valid) {
                return;
            }

            // Add the spinner to the button
            $("#editDepositMethodBtn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
            $("#editDepositMethodBtn").prop("disabled", true); // Disable the button

            // Create a FormData object
            const formData = new FormData($('#edit-deposit-method-form')[0]);

            // Add the selected file to the FormData object
            const fileInput = $("#hidden-input")[0];
            if (fileInput.files.length > 0) {
                formData.append('photoimg', fileInput.files[0]);
            }

            // Send an Ajax request to the edit controller
            $.ajax({
                url: "<?=$this->siteUrl()?>/admin/deposit_gateway/edit-method/" + idValue, // Modify the URL according to your edit
                type: "POST",
                data: formData,
                dataType: "json",
                contentType: false,
                processData: false,
                success: function (response) {
                    // Check the response status
                    if (response.status === "success") {
                        // Show a success message
                        iziToast.success({
                            message: response.message,
                            position: 'topRight'
                        });

                        // Redirect after a delay (optional)
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
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
                    $("#editDepositMethodBtn").html("Submit Form");
                    $("#editDepositMethodBtn").prop("disabled", false);
                },
            });
        });

        // Add event listener for input fields to remove 'is-invalid' class on input
        $("#name, #abbreviation, #min_amount, #max_amount, #gateway_parameter").on("input", function () {
            $(this).removeClass("is-invalid");
        });
    });
</script>

<!-- Activate Deposit Method Ajax -->
<script>
    $(document).ready(function () {
        // Handler for the edit button click event
        $("#activateDepositMethodBtn").click(function (event) {
            event.preventDefault(); // Prevent form submission

            // Add the spinner to the button
            $("#activateDepositMethodBtn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
            $("#activateDepositMethodBtn").prop("disabled", true); // Disable the button

            // Send an Ajax request to the edit controller
            $.ajax({
                url: "<?=$this->siteUrl()?>/admin/deposit_gateway/activate-method", // Modify the URL according to your edit
                type: 'POST',
                data: $('#activate-deposit-method-form').serialize(),
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
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
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
                    $("#activateDepositMethodBtn").html("Yes, Activate");
                    $("#activateDepositMethodBtn").prop("disabled", false);
                },
            });
        });
    });
</script>

<!-- Dectivate Deposit Method Ajax -->
<script>
    $(document).ready(function () {
        // Handler for the edit button click event
        $("#deactivateDepositMethodBtn").click(function (event) {
            event.preventDefault(); // Prevent form submission

            // Add the spinner to the button
            $("#deactivateDepositMethodBtn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
            $("#deactivateDepositMethodBtn").prop("disabled", true); // Disable the button

            // Send an Ajax request to the edit controller
            $.ajax({
                url: "<?=$this->siteUrl()?>/admin/deposit_gateway/deactivate-method", // Modify the URL according to your edit
                type: 'POST',
                data: $('#deactivate-deposit-method-form').serialize(),
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
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
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
                    $("#deactivateDepositMethodBtn").html("Yes, Deactivate");
                    $("#deactivateDepositMethodBtn").prop("disabled", false);
                },
            });
        });
    });
</script>

<!-- Load Withdrawal Methods -->
<script>
    $(document).ready(function() {
        let page = 2; // Initial page number
        const container = $("#loadMoreWithdrawalMethodsContainer");
        const loadMoreWithdrawalMethods = $(".loadMoreWithdrawalMethods");
        const lastPageDiv = $("#WithdrawalMethodsLastpage"); // Get the reference to the last page div

        loadMoreWithdrawalMethods.click(function(e) {
            e.preventDefault();

            const userId = $(this).data("id");
            const currentPage = $(this).data("page");

            loadMoreWithdrawalMethods.html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Loading...</span>');

            // Send an AJAX request to fetch more content
            $.ajax({
                url: "<?=$this->siteUrl()?>/admin/withdrawal_gateway", // Update with the correct URL
                type: "GET",
                data: { 
                    userid: userId,
                    page: currentPage 
                },
                success: function(response) {
                    const gateways = response.gateways;

                    // Construct the HTML markup for each invested item
                    let html = '';
                    for (let i = 0; i < gateways.length; i++) {
                        const gateway = gateways[i];

                        html += '<div class="col-xxl-3">';
                        html += '<div class="team-list row list-view-filter">';
                        html += '<div class="col">';
                        html += '<div class="card team-box">';
                        html += '<div class="card-body">';
                        html += '<div class="row align-items-center team-row">';
                        html += '<div class="col team-settings">';
                        html += '<div class="row">';
                        html += '<div class="col"></div>';
                        html += '<div class="col text-end dropdown">';
                        html += '<a href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false" class=""><i class="ri-more-fill fs-17"></i></a>';

                        html += '<ul class="dropdown-menu dropdown-menu-end">';

                        html += '<li><a class="dropdown-item edit-list" href="<?=$this->siteUrl()?>/admin/withdrawal_gateway/edit-method/'+ gateway.withdraw_code +'"><i class="ri-pencil-line me-2 align-bottom text-muted"></i>Edit</a></li>';

                        if (gateway.status == "1") {
                            html += '<li><button class="dropdown-item remove-list" data-bs-toggle="modal" data-bs-target="#deactivateMethodModal" data-id="'+ gateway.withdraw_code +'"><i class="ri-close-circle-line me-2 align-bottom text-muted"></i>Deactivate</button></li>';
                        } else if (gateway.status == "2") {
                            html += '<li><button class="dropdown-item remove-list" data-bs-toggle="modal" data-bs-target="#activateMethodModal" data-id="'+ gateway.withdraw_code +'"><i class="ri-checkbox-circle-line me-2 align-bottom text-muted"></i>Activate</button></li>';
                        }

                        html += '</ul>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '<div class="col-lg-4 col">';
                        html += '<div class="team-profile-img">';

                        html += '<div class="avatar-lg img-thumbnail rounded-circle flex-shrink-0"><img src="<?= $this->siteUrl() ?>/<?= PUBLIC_PATH ?>/<?= UPLOADS_PATH ?>/withdrawal/' + gateway.image + '" alt="" class="member-img img-fluid d-block rounded-circle" /></div>';

                        html += '<div class="team-content">';
                        html += '<a class="member-name" data-bs-toggle="offcanvas" aria-controls="member-overview"><h5 class="fs-16 mb-1">'+ gateway.name +'</h5></a>';

                        html += '<p class="text-muted member-designation mb-0">';
                            if (gateway.status == "1") {
                                html += '<span class="badge bg-success-subtle text-approved">Active</span>';
                            } else if (gateway.status == "2") {
                                html += '<span class="badge bg-danger-subtle text-danger">Disabled</span>';
                            }
                        html += '</p>';

                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                    }
                    
                    // Append the new content to the container
                    container.find(".gateways").append(html);

                    // Update the page number
                    page++;

                    // If there is no more content to load, hide the load more button
                    if (gateways.length === 0) {
                        loadMoreWithdrawalMethods.hide();
                        lastPageDiv.removeClass("d-none");
                    } else {
                        loadMoreWithdrawalMethods.text("Load More"); // Restore the button text
                        loadMoreWithdrawalMethods.data("page", page); // Update the data-page attribute
                    }
                },
                error: function() {
                    // Handle the error case
                    loadMoreWithdrawalMethods.text("Load More"); // Restore the button text
                },
            });
        });
    });
</script>

<!-- Add Withdrawal Method Ajax -->
<script>
    $(document).ready(function () {
        // Handler for the rank button click event
        $("#addWithdrawalMethodBtn").click(function (event) {
            event.preventDefault(); // Prevent form submission

            // Validate inputs
            let valid = true;

            // name validation
            const nameValue = $("#name").val().trim();
            if (nameValue === "") {
                $("#name").addClass("is-invalid");
                valid = false;
            } else {
                $("#name").removeClass("is-invalid");
            }

            // abbreviation validation
            const abbreviationValue = $("#abbreviation").val().trim();
            if (abbreviationValue === "") {
                $("#abbreviation").addClass("is-invalid");
                valid = false;
            } else {
                $("#abbreviation").removeClass("is-invalid");
            }

            // min_amount validation
            const min_amountValue = $("#min_amount").val().trim();
            if (min_amountValue === "") {
                $("#min_amount").addClass("is-invalid");
                valid = false;
            } else {
                $("#min_amount").removeClass("is-invalid");
            }

            // max_amount validation
            const max_amountValue = $("#max_amount").val().trim();
            if (max_amountValue === "") {
                $("#max_amount").addClass("is-invalid");
                valid = false;
            } else {
                $("#max_amount").removeClass("is-invalid");
            }

            if (!valid) {
                return;
            }

            // Add the spinner to the button
            $("#addWithdrawalMethodBtn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
            $("#addWithdrawalMethodBtn").prop("disabled", true); // Disable the button

            // Create a FormData object
            const formData = new FormData($('#add-withdrawal-method-form')[0]);

            // Add the selected file to the FormData object
            const fileInput = $("#hidden-input")[0];
            if (fileInput.files.length > 0) {
                formData.append('photoimg', fileInput.files[0]);
            }

            // Send an Ajax request to the edit controller
            $.ajax({
                url: "<?=$this->siteUrl()?>/admin/withdrawal_gateway/add-method", // Modify the URL according to your edit
                type: "POST",
                data: formData,
                dataType: "json",
                contentType: false,
                processData: false,
                success: function (response) {
                    // Check the response status
                    if (response.status === "success") {
                        // Show a success message
                        iziToast.success({
                            message: response.message,
                            position: 'topRight'
                        });

                        // Redirect after a delay (optional)
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
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
                    $("#addWithdrawalMethodBtn").html("Submit Form");
                    $("#addWithdrawalMethodBtn").prop("disabled", false);
                },
            });
        });

        // Add event listener for input fields to remove 'is-invalid' class on input
        $("#name, #abbreviation, #min_amount, #max_amount").on("input", function () {
            $(this).removeClass("is-invalid");
        });
    });
</script>

<!-- Edit Withdrawal Method Ajax -->
<script>
    $(document).ready(function () {
        // Handler for the edit button click event
        $("#editWithdrawalMethodBtn").click(function (event) {
            event.preventDefault(); // Prevent form submission

            // Retrieve the ID from the data attribute
            const idValue = $(this).data("id");

            if (!idValue) {
                console.error("ID is not set in the data attribute.");
                return;
            }

            // Validate inputs
            let valid = true;

            // name validation
            const nameValue = $("#name").val().trim();
            if (nameValue === "") {
                $("#name").addClass("is-invalid");
                valid = false;
            } else {
                $("#name").removeClass("is-invalid");
            }

            // abbreviation validation
            const abbreviationValue = $("#abbreviation").val().trim();
            if (abbreviationValue === "") {
                $("#abbreviation").addClass("is-invalid");
                valid = false;
            } else {
                $("#abbreviation").removeClass("is-invalid");
            }

            // min_amount validation
            const min_amountValue = $("#min_amount").val().trim();
            if (min_amountValue === "") {
                $("#min_amount").addClass("is-invalid");
                valid = false;
            } else {
                $("#min_amount").removeClass("is-invalid");
            }

            // max_amount validation
            const max_amountValue = $("#max_amount").val().trim();
            if (max_amountValue === "") {
                $("#max_amount").addClass("is-invalid");
                valid = false;
            } else {
                $("#max_amount").removeClass("is-invalid");
            }

            if (!valid) {
                return;
            }

            // Add the spinner to the button
            $("#editWithdrawalMethodBtn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
            $("#editWithdrawalMethodBtn").prop("disabled", true); // Disable the button

            // Create a FormData object
            const formData = new FormData($('#edit-withdrawal-method-form')[0]);

            // Add the selected file to the FormData object
            const fileInput = $("#hidden-input")[0];
            if (fileInput.files.length > 0) {
                formData.append('photoimg', fileInput.files[0]);
            }

            // Send an Ajax request to the edit controller
            $.ajax({
                url: "<?=$this->siteUrl()?>/admin/withdrawal_gateway/edit-method/" + idValue, // Modify the URL according to your edit
                type: "POST",
                data: formData,
                dataType: "json",
                contentType: false,
                processData: false,
                success: function (response) {
                    // Check the response status
                    if (response.status === "success") {
                        // Show a success message
                        iziToast.success({
                            message: response.message,
                            position: 'topRight'
                        });

                        // Redirect after a delay (optional)
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
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
                    $("#editWithdrawalMethodBtn").html("Submit Form");
                    $("#editWithdrawalMethodBtn").prop("disabled", false);
                },
            });
        });

        // Add event listener for input fields to remove 'is-invalid' class on input
        $("#name, #abbreviation, #min_amount, #max_amount").on("input", function () {
            $(this).removeClass("is-invalid");
        });
    });
</script>

<!-- Activate Withdrawal Method Ajax -->
<script>
    $(document).ready(function () {
        // Handler for the edit button click event
        $("#activateWithdrawalMethodBtn").click(function (event) {
            event.preventDefault(); // Prevent form submission

            // Add the spinner to the button
            $("#activateWithdrawalMethodBtn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
            $("#activateWithdrawalMethodBtn").prop("disabled", true); // Disable the button

            // Send an Ajax request to the edit controller
            $.ajax({
                url: "<?=$this->siteUrl()?>/admin/withdrawal_gateway/activate-method", // Modify the URL according to your edit
                type: 'POST',
                data: $('#activate-withdrawal-method-form').serialize(),
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
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
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
                    $("#activateWithdrawalMethodBtn").html("Yes, Activate");
                    $("#activateWithdrawalMethodBtn").prop("disabled", false);
                },
            });
        });
    });
</script>

<!-- Dectivate Withdrawal Method Ajax -->
<script>
    $(document).ready(function () {
        // Handler for the edit button click event
        $("#deactivateWithdrawalMethodBtn").click(function (event) {
            event.preventDefault(); // Prevent form submission

            // Add the spinner to the button
            $("#deactivateWithdrawalMethodBtn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
            $("#deactivateWithdrawalMethodBtn").prop("disabled", true); // Disable the button

            // Send an Ajax request to the edit controller
            $.ajax({
                url: "<?=$this->siteUrl()?>/admin/withdrawal_gateway/deactivate-method", // Modify the URL according to your edit
                type: 'POST',
                data: $('#deactivate-withdrawal-method-form').serialize(),
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
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
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
                    $("#deactivateWithdrawalMethodBtn").html("Yes, Deactivate");
                    $("#deactivateWithdrawalMethodBtn").prop("disabled", false);
                },
            });
        });
    });
</script>

<!-- Load All Commissions -->
<script>
    $(document).ready(function() {
        let page = 2; // Initial page number
        const container = $("#loadMoreAllCommissionsContainer");
        const loadMoreAllCommissions = $(".loadMoreAllCommissions");
        const lastPageDiv = $("#AllCommissionsLastpage"); // Get the reference to the last page div

        loadMoreAllCommissions.click(function(e) {
            e.preventDefault();
            const currentPage = $(this).data("page");

            loadMoreAllCommissions.html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Loading...</span>');

            // Send an AJAX request to fetch more content
            $.ajax({
                url: "<?=$this->siteUrl()?>/admin/commissions", // Update with the correct URL
                type: "GET",
                data: { 
                    page: currentPage 
                },
                success: function(response) {
                    const commissions = response.commissions;

                    // Start building the HTML for each commission entry
                    let html = '';
                    for (let i = 0; i < commissions.length; i++) {
                        const commission = commissions[i];
                        const user = getUserById(commission.to_id);

                        html += '<div class="card product">';
                        html += '<div class="card-body">';
                        html += '<div class="row gy-3">';
                        html += '<div class="col-sm">';
                        html += '<h5 class="fs-14 text-truncate">';
                        html += '<a class="text-body">';
                        
                        // Add user's name if available
                        if (user) {
                            html += `${user.firstname} ${user.lastname}`;
                        }
                        
                        html += '</a>';
                        html += '</h5>';

                        // Display commission details
                        html += '<ul class="list-inline text-muted">';
                        html += `<li class="list-inline-item">Earned $${commission.commission_amount} ${commission.title}`;
                        html += '</ul>';
                        html += '</div>';

                        html += '<div class="col-sm-auto">';
                        html += '<div class="text-lg-end">';
                        html += '<p class="text-muted mb-1">Invested Amount:</p>';
                        html += `<h5 class="fs-14">$<span id="ticket_price" class="product-price">${commission.main_amount}</span></h5>`;
                        html += '</div>';
                        html += '</div>';

                        html += '<div class="col-sm-auto">';
                        html += '<div class="text-lg-end">';
                        html += '<p class="text-muted mb-1">Referral Percent:</p>';
                        html += `<h5 class="fs-14">${commission.percent}%</span></h5>`;
                        html += '</div>';
                        html += '</div>';

                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                    }

                    // Append the new content to the container
                    container.find(".commissions").append(html);

                    // Update the page number
                    page++;

                    // If there is no more content to load, hide the load more button
                    if (commissions.length === 0) {
                        loadMoreAllCommissions.hide();
                        lastPageDiv.removeClass("d-none");
                    } else {
                        loadMoreAllCommissions.text("Load More"); // Restore the button text
                        loadMoreAllCommissions.data("page", page); // Update the data-page attribute
                    }
                },
                error: function() {
                    // Handle the error case
                    loadMoreAllCommissions.text("Load More"); // Restore the button text
                },
            });
        });

        // Custom function to retrieve user details by ID
        function getUserById(to_id) {
            const usersMap = {};
            const users = <?= json_encode($data['users']) ?>; // Retrieve the user array from PHP

            // Build a map of users keyed by their IDs
            users.forEach(user => {
                usersMap[user.userid] = user;
            });

            // Return the user if found, otherwise return null
            return usersMap[to_id] || null;
        }
    });
</script>

<!-- Load All Transactions -->
<script>
    $(document).ready(function() {
        let page = 2; // Initial page number
        const container = $("#loadMoreAllTransactionsContainer");
        const loadMoreAllTransactions = $(".loadMoreAllTransactions");
        const lastPageDiv = $("#AllTransactionsLastpage"); // Get the reference to the last page div

        loadMoreAllTransactions.click(function(e) {
            e.preventDefault();
            const currentPage = $(this).data("page");

            loadMoreAllTransactions.html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Loading...</span>');

            // Send an AJAX request to fetch more content
            $.ajax({
                url: "<?=$this->siteUrl()?>/admin/transactions", // Update with the correct URL
                type: "GET",
                data: { 
                    page: currentPage 
                },
                success: function(response) {
                    const transactions = response.transactions;

                    // Start building the HTML for each transaction entry
                    let html = '';
                    for (let i = 0; i < transactions.length; i++) {
                        const transaction = transactions[i];
                        const user = getUserById(transaction.userid);

                        const formatDate = (dateString) => {
                            const date = new Date(dateString);
                            const options = { day: 'numeric', month: 'short', year: 'numeric' };
                            return date.toLocaleDateString('en-US', options);
                        };

                        // Inside the loop
                        const formattedDate = formatDate(transaction.created_at);

                        if (user) {
                            html += `
                                <li class="list-group-item d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <img src="<?=$this->siteUrl().'/'.PUBLIC_PATH.'/'.UPLOADS_PATH?>/users/${user.imagelocation}" class="avatar-xs rounded-circle" alt="" />
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="fs-14 mb-1">${user.firstname} ${user.lastname}</h6>
                                        <p class="text-muted mb-0">${transaction.details}</p>
                                    </div>
                                    <div class="flex-shrink-0 text-end">
                                        <h6 class="fs-14 mb-1">${formattedDate}</h6>
                                        <p class="text-${transaction.trx_type === '+' ? 'success' : 'danger'} fs-12 mb-0">${transaction.trx_type === '+' ? '+' : '-'} $${transaction.amount}</p>
                                    </div>
                                </li>
                            `;
                        }
                    }

                    // Append the new content to the container
                    container.find(".transactions").append(html);

                    // Update the page number
                    page++;

                    // If there is no more content to load, hide the load more button
                    if (transactions.length === 0) {
                        loadMoreAllTransactions.hide();
                        lastPageDiv.removeClass("d-none");
                    } else {
                        loadMoreAllTransactions.text("Load More"); // Restore the button text
                        loadMoreAllTransactions.data("page", page); // Update the data-page attribute
                    }
                },
                error: function() {
                    // Handle the error case
                    loadMoreAllTransactions.text("Load More"); // Restore the button text
                },
            });
        });

        // Custom function to retrieve user details by ID
        function getUserById(userid) {
            const usersMap = {};
            const users = <?= json_encode($data['users']) ?>; // Retrieve the user array from PHP

            // Build a map of users keyed by their IDs
            users.forEach(user => {
                usersMap[user.userid] = user;
            });

            // Return the user if found, otherwise return null
            return usersMap[userid] || null;
        }
    });
</script>

<!-- Load All Investments -->
<script>
    $(document).ready(function() {
        let page = 2; // Initial page number
        const container = $("#loadMoreInvestmentsContainer");
        const loadMoreInvestments = $(".loadMoreInvestments");
        const lastPageDiv = $("#InvestmentsLastpage"); // Get the reference to the last page div

        loadMoreInvestments.click(function(e) {
            e.preventDefault();
            const currentPage = $(this).data("page");

            loadMoreInvestments.html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Loading...</span>');

            // Send an AJAX request to fetch more content
            $.ajax({
                url: "<?=$this->siteUrl()?>/admin/investments", // Update with the correct URL
                type: "GET",
                data: { 
                    page: currentPage 
                },
                success: function(response) {
                    const investments = response.investments;

                    // Start building the HTML for each investment entry
                    let html = '';
                    for (let i = 0; i < investments.length; i++) {
                        const investment = investments[i];
                        const user = getUserById(investment.userid);
                        const plan = getPlanById(investment.planId);

                        html += '<li class="list-group-item d-flex align-items-center">';
                        html += '<div class="flex-shrink-0">';
                        html += '<img src="<?=$this->siteUrl()?>/<?=PUBLIC_PATH?>/<?=UPLOADS_PATH?>/users/'+ user.imagelocation +'" class="avatar-xs rounded-circle" alt="" />';
                        html += '</div>';

                        html += '<div class="flex-grow-1 ms-3">';
                        html += '<h6 class="fs-14 mb-1"><a href="<?=$this->siteUrl()?>/admin/investments/view-investment/'+ investment.investId +'">'+ user.firstname +' '+ user.lastname +'</a></h6>';

                        html += '<p class="text-muted mb-0">$'+ investment.amount +' On '+ plan.name +'</p>';

                        html += '</div>';

                        html += '<div class="flex-shrink-0 text-end me-2">';
                        html += '<h6 class="fs-14 mb-1">'+ formatDate(investment.initiated_at) +'</h6>';

                        if (investment.status == 1) {
                            html += '<p class="text-success fs-12 mb-0">Completed</p>';
                        } else if (investment.status == 2) {
                            html += '<p class="text-warning fs-12 mb-0">Running</p>';

                            // Calculate percentage completion
                            const diffPercent = diffDatePercent(investment.created_at, investment.next_time);
                            html += '<div class="progress animated-progress custom-progress progress-label">';
                            html += '<div class="progress-bar bg-danger" role="progressbar" style="width: '+ diffPercent +'" aria-valuenow="'+ diffPercent +'" aria-valuemin="0" aria-valuemax="100"></div>';
                            html += '</div>';

                            html += '<p class="text-muted mb-0" id="timestamp'+ investment.id +'"></p>';
                            createCountDown('timestamp'+ investment.id, investment.next_time);
                        } else if (investment.status == 3) {
                            html += '<p class="fs-12 mb-0">Initiated</p>';
                        } else if (investment.status == 4) {
                            html += '<p class="text-danger fs-12 mb-0">Cancelled</p>';
                        }

                        html += '</div>';
                        
                        html += '<div class="flex-shrink-0 text-end ms-2">';
                        html += '<div class="dropdown d-inline-block">';
                        html += '<a href="<?=$this->siteUrl()?>/admin/investments/view-investment/'+ investment.investId +'" class="btn btn-soft-secondary btn-sm dropdown">Details</a>';
                        html += '</div>';
                        html += '</div>';
                        html += '</li>';
                    }

                    // Append the new content to the container
                    container.find(".investments").append(html);

                    // Update the page number
                    page++;

                    // If there is no more content to load, hide the load more button
                    if (investments.length === 0) {
                        loadMoreInvestments.hide();
                        lastPageDiv.removeClass("d-none");
                    } else {
                        loadMoreInvestments.text("Load More"); // Restore the button text
                        loadMoreInvestments.data("page", page); // Update the data-page attribute
                    }
                },
                error: function() {
                    // Handle the error case
                    loadMoreInvestments.text("Load More"); // Restore the button text
                },
            });
        });

        function formatDate(dateString) {
            const date = new Date(dateString);
            const options = { day: 'numeric', month: 'short', year: 'numeric' };
            return date.toLocaleDateString('en-US', options);
        }

        // Custom function to calculate percentage difference between two dates
        function diffDatePercent(start, end) {
            const diff = new Date(end) - new Date(start);
            const current = new Date() - new Date(start);
            let percentage = current > diff ? 1.0 : current < 0 ? 0.0 : current / diff;
            return (percentage * 100).toFixed(2) + "%";
        }

        // Custom function to create countdown timer
        function createCountDown(elementId, endTime) {
            const endTimeMillis = new Date(endTime).getTime();
            const interval = setInterval(function() {
                const now = new Date().getTime();
                const distance = endTimeMillis - now;
                if (distance <= 0) {
                    clearInterval(interval);
                    document.getElementById(elementId).innerHTML = "Timer Expired";
                } else {
                    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                    document.getElementById(elementId).innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";
                }
            }, 1000);
        }

        // Custom function to retrieve user details by ID
        function getUserById(userid) {
            const users = <?= json_encode($data['users']) ?>; // Retrieve the user array from PHP
            return users.find(user => user.userid === userid) || null;
        }

        // Custom function to retrieve plans by ID
        function getPlanById(planId) {
            const plans = <?= json_encode($data['plans']) ?>; // Retrieve the plan array from PHP
            return plans.find(plan => plan.planId === planId) || null;
        }
    });
</script>

<!-- Load Running Investments -->
<script>
    $(document).ready(function() {
        let page = 2; // Initial page number
        const container = $("#loadMoreRunningInvestmentsContainer");
        const loadMoreRunningInvestments = $(".loadMoreRunningInvestments");
        const lastPageDiv = $("#RunningInvestmentsLastpage"); // Get the reference to the last page div

        loadMoreRunningInvestments.click(function(e) {
            e.preventDefault();
            const currentPage = $(this).data("page");

            loadMoreRunningInvestments.html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Loading...</span>');

            // Send an AJAX request to fetch more content
            $.ajax({
                url: "<?=$this->siteUrl()?>/admin/investments_running", // Update with the correct URL
                type: "GET",
                data: { 
                    page: currentPage 
                },
                success: function(response) {
                    const investments = response.investments;

                    // Start building the HTML for each investment entry
                    let html = '';
                    for (let i = 0; i < investments.length; i++) {
                        const investment = investments[i];
                        const user = getUserById(investment.userid);
                        const plan = getPlanById(investment.planId);

                        html += '<li class="list-group-item d-flex align-items-center">';
                        html += '<div class="flex-shrink-0">';
                        html += '<img src="<?=$this->siteUrl()?>/<?=PUBLIC_PATH?>/<?=UPLOADS_PATH?>/users/'+ user.imagelocation +'" class="avatar-xs rounded-circle" alt="" />';
                        html += '</div>';

                        html += '<div class="flex-grow-1 ms-3">';
                        html += '<h6 class="fs-14 mb-1"><a href="#">'+ user.firstname +' '+ user.lastname +'</a></h6>';

                        if (plan) {
                            html += '<p class="text-muted mb-0">$'+ investment.amount +' On '+ plan.name +'</p>';
                        }

                        html += '</div>';

                        html += '<div class="flex-shrink-0 text-end me-2">';
                        html += '<h6 class="fs-14 mb-1">'+ formatDate(investment.initiated_at) +'</h6>';

                        if (investment.status == 1) {
                            html += '<p class="text-success fs-12 mb-0">Completed</p>';
                        } else if (investment.status == 2) {
                            html += '<p class="text-warning fs-12 mb-0">Running</p>';

                            // Calculate percentage completion
                            const diffPercent = diffDatePercent(investment.created_at, investment.next_time);
                            html += '<div class="progress animated-progress custom-progress progress-label">';
                            html += '<div class="progress-bar bg-danger" role="progressbar" style="width: '+ diffPercent +'" aria-valuenow="'+ diffPercent +'" aria-valuemin="0" aria-valuemax="100"></div>';
                            html += '</div>';

                            html += '<p class="text-muted mb-0" id="timestamp'+ investment.id +'"></p>';
                            createCountDown('timestamp'+ investment.id, investment.next_time);
                        } else if (investment.status == 3) {
                            html += '<p class="fs-12 mb-0">Initiated</p>';
                        } else if (investment.status == 4) {
                            html += '<p class="text-danger fs-12 mb-0">Cancelled</p>';
                        }
                        
                        html += '</div>';
                        
                        html += '<div class="flex-shrink-0 text-end ms-2">';
                        html += '<div class="dropdown d-inline-block">';
                        html += '<a href="<?=$this->siteUrl()?>/admin/investments/view-investment/'+ investment.investId +'" class="btn btn-soft-secondary btn-sm dropdown">Details</a>';
                        html += '</div>';
                        html += '</div>';
                        html += '</li>';
                    }

                    // Append the new content to the container
                    container.find(".investments").append(html);

                    // Update the page number
                    page++;

                    // If there is no more content to load, hide the load more button
                    if (investments.length === 0) {
                        loadMoreRunningInvestments.hide();
                        lastPageDiv.removeClass("d-none");
                    } else {
                        loadMoreRunningInvestments.text("Load More"); // Restore the button text
                        loadMoreRunningInvestments.data("page", page); // Update the data-page attribute
                    }
                },
                error: function() {
                    // Handle the error case
                    loadMoreRunningInvestments.text("Load More"); // Restore the button text
                },
            });
        });

        function formatDate(dateString) {
            const date = new Date(dateString);
            const options = { day: 'numeric', month: 'short', year: 'numeric' };
            return date.toLocaleDateString('en-US', options);
        }

        // Custom function to calculate percentage difference between two dates
        function diffDatePercent(start, end) {
            const diff = new Date(end) - new Date(start);
            const current = new Date() - new Date(start);
            let percentage = current > diff ? 1.0 : current < 0 ? 0.0 : current / diff;
            return (percentage * 100).toFixed(2) + "%";
        }

        // Custom function to create countdown timer
        function createCountDown(elementId, endTime) {
            const endTimeMillis = new Date(endTime).getTime();
            const interval = setInterval(function() {
                const now = new Date().getTime();
                const distance = endTimeMillis - now;
                if (distance <= 0) {
                    clearInterval(interval);
                    document.getElementById(elementId).innerHTML = "Timer Expired";
                } else {
                    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                    document.getElementById(elementId).innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";
                }
            }, 1000);
        }

        // Custom function to retrieve user details by ID
        function getUserById(userid) {
            const users = <?= json_encode($data['users']) ?>; // Retrieve the user array from PHP
            return users.find(user => user.userid === userid) || null;
        }

        // Custom function to retrieve plans by ID
        function getPlanById(planId) {
            const plans = <?= json_encode($data['plans']) ?>; // Retrieve the plan array from PHP
            return plans.find(plan => plan.planId === planId) || null;
        }
    });
</script>

<!-- Load Completed Investments -->
<script>
    $(document).ready(function() {
        let page = 2; // Initial page number
        const container = $("#loadMoreCompletedInvestmentsContainer");
        const loadMoreCompletedInvestments = $(".loadMoreCompletedInvestments");
        const lastPageDiv = $("#CompletedInvestmentsLastpage"); // Get the reference to the last page div

        loadMoreCompletedInvestments.click(function(e) {
            e.preventDefault();
            const currentPage = $(this).data("page");

            loadMoreCompletedInvestments.html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Loading...</span>');

            // Send an AJAX request to fetch more content
            $.ajax({
                url: "<?=$this->siteUrl()?>/admin/investments_completed", // Update with the correct URL
                type: "GET",
                data: { 
                    page: currentPage 
                },
                success: function(response) {
                    const investments = response.investments;

                    // Start building the HTML for each investment entry
                    let html = '';
                    for (let i = 0; i < investments.length; i++) {
                        const investment = investments[i];
                        const user = getUserById(investment.userid);
                        const plan = getPlanById(investment.planId);

                        html += '<li class="list-group-item d-flex align-items-center">';
                        html += '<div class="flex-shrink-0">';
                        html += '<img src="<?=$this->siteUrl()?>/<?=PUBLIC_PATH?>/<?=UPLOADS_PATH?>/users/'+ user.imagelocation +'" class="avatar-xs rounded-circle" alt="" />';
                        html += '</div>';

                        html += '<div class="flex-grow-1 ms-3">';
                        html += '<h6 class="fs-14 mb-1"><a href="#">'+ user.firstname +' '+ user.lastname +'</a></h6>';

                        if (plan) {
                            html += '<p class="text-muted mb-0">$'+ investment.amount +' On '+ plan.name +'</p>';
                        }

                        html += '</div>';

                        html += '<div class="flex-shrink-0 text-end me-2">';
                        html += '<h6 class="fs-14 mb-1">'+ formatDate(investment.initiated_at) +'</h6>';

                        html += '<p class="text-success fs-12 mb-0">Completed</p>';
                        
                        html += '</div>';
                        
                        html += '<div class="flex-shrink-0 text-end ms-2">';
                        html += '<div class="dropdown d-inline-block">';
                        html += '<a href="<?=$this->siteUrl()?>/admin/investments/view-investment/'+ investment.investId +'" class="btn btn-soft-secondary btn-sm dropdown">Details</a>';
                        html += '</div>';
                        html += '</div>';
                        html += '</li>';
                    }

                    // Append the new content to the container
                    container.find(".investments").append(html);

                    // Update the page number
                    page++;

                    // If there is no more content to load, hide the load more button
                    if (investments.length === 0) {
                        loadMoreCompletedInvestments.hide();
                        lastPageDiv.removeClass("d-none");
                    } else {
                        loadMoreCompletedInvestments.text("Load More"); // Restore the button text
                        loadMoreCompletedInvestments.data("page", page); // Update the data-page attribute
                    }
                },
                error: function() {
                    // Handle the error case
                    loadMoreCompletedInvestments.text("Load More"); // Restore the button text
                },
            });
        });

        function formatDate(dateString) {
            const date = new Date(dateString);
            const options = { day: 'numeric', month: 'short', year: 'numeric' };
            return date.toLocaleDateString('en-US', options);
        }

        // Custom function to retrieve user details by ID
        function getUserById(userid) {
            const users = <?= json_encode($data['users']) ?>; // Retrieve the user array from PHP
            return users.find(user => user.userid === userid) || null;
        }

        // Custom function to retrieve plans by ID
        function getPlanById(planId) {
            const plans = <?= json_encode($data['plans']) ?>; // Retrieve the plan array from PHP
            return plans.find(plan => plan.planId === planId) || null;
        }
    });
</script>

<!-- Load Canceled Investments -->
<script>
    $(document).ready(function() {
        let page = 2; // Initial page number
        const container = $("#loadMoreCancelledInvestmentsContainer");
        const loadMoreCancelledInvestments = $(".loadMoreCancelledInvestments");
        const lastPageDiv = $("#CancelledInvestmentsLastpage"); // Get the reference to the last page div

        loadMoreCancelledInvestments.click(function(e) {
            e.preventDefault();
            const currentPage = $(this).data("page");

            loadMoreCancelledInvestments.html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Loading...</span>');

            // Send an AJAX request to fetch more content
            $.ajax({
                url: "<?=$this->siteUrl()?>/admin/investments_cancelled", // Update with the correct URL
                type: "GET",
                data: { 
                    page: currentPage 
                },
                success: function(response) {
                    const investments = response.investments;

                    // Start building the HTML for each investment entry
                    let html = '';
                    for (let i = 0; i < investments.length; i++) {
                        const investment = investments[i];
                        const user = getUserById(investment.userid);
                        const plan = getPlanById(investment.planId);

                        html += '<li class="list-group-item d-flex align-items-center">';
                        html += '<div class="flex-shrink-0">';
                        html += '<img src="<?=$this->siteUrl()?>/<?=PUBLIC_PATH?>/<?=UPLOADS_PATH?>/users/'+ user.imagelocation +'" class="avatar-xs rounded-circle" alt="" />';
                        html += '</div>';

                        html += '<div class="flex-grow-1 ms-3">';
                        html += '<h6 class="fs-14 mb-1"><a href="#">'+ user.firstname +' '+ user.lastname +'</a></h6>';

                        if (plan) {
                            html += '<p class="text-muted mb-0">$'+ investment.amount +' On '+ plan.name +'</p>';
                        }

                        html += '</div>';

                        html += '<div class="flex-shrink-0 text-end me-2">';
                        html += '<h6 class="fs-14 mb-1">'+ formatDate(investment.initiated_at) +'</h6>';

                        html += '<p class="text-danger fs-12 mb-0">Cancelled</p>';
                        
                        html += '</div>';
                        
                        html += '<div class="flex-shrink-0 text-end ms-2">';
                        html += '<div class="dropdown d-inline-block">';
                        html += '<a href="<?=$this->siteUrl()?>/admin/investments/view-investment/'+ investment.investId +'" class="btn btn-soft-secondary btn-sm dropdown">Details</a>';
                        html += '</div>';
                        html += '</div>';
                        html += '</li>';
                    }

                    // Append the new content to the container
                    container.find(".investments").append(html);

                    // Update the page number
                    page++;

                    // If there is no more content to load, hide the load more button
                    if (investments.length === 0) {
                        loadMoreCancelledInvestments.hide();
                        lastPageDiv.removeClass("d-none");
                    } else {
                        loadMoreCancelledInvestments.text("Load More"); // Restore the button text
                        loadMoreCancelledInvestments.data("page", page); // Update the data-page attribute
                    }
                },
                error: function() {
                    // Handle the error case
                    loadMoreCancelledInvestments.text("Load More"); // Restore the button text
                },
            });
        });

        function formatDate(dateString) {
            const date = new Date(dateString);
            const options = { day: 'numeric', month: 'short', year: 'numeric' };
            return date.toLocaleDateString('en-US', options);
        }

        // Custom function to retrieve user details by ID
        function getUserById(userid) {
            const users = <?= json_encode($data['users']) ?>; // Retrieve the user array from PHP
            return users.find(user => user.userid === userid) || null;
        }

        // Custom function to retrieve plans by ID
        function getPlanById(planId) {
            const plans = <?= json_encode($data['plans']) ?>; // Retrieve the plan array from PHP
            return plans.find(plan => plan.planId === planId) || null;
        }
    });
</script>

<!-- Load Initiated Investments -->
<script>
    $(document).ready(function() {
        let page = 2; // Initial page number
        const container = $("#loadMoreInitiatedInvestmentsContainer");
        const loadMoreInitiatedInvestments = $(".loadMoreInitiatedInvestments");
        const lastPageDiv = $("#InitiatedInvestmentsLastpage"); // Get the reference to the last page div

        loadMoreInitiatedInvestments.click(function(e) {
            e.preventDefault();
            const currentPage = $(this).data("page");

            loadMoreInitiatedInvestments.html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Loading...</span>');

            // Send an AJAX request to fetch more content
            $.ajax({
                url: "<?=$this->siteUrl()?>/admin/investments_initiated", // Update with the correct URL
                type: "GET",
                data: { 
                    page: currentPage 
                },
                success: function(response) {
                    const investments = response.investments;

                    // Start building the HTML for each investment entry
                    let html = '';
                    for (let i = 0; i < investments.length; i++) {
                        const investment = investments[i];
                        const user = getUserById(investment.userid);
                        const plan = getPlanById(investment.planId);

                        html += '<li class="list-group-item d-flex align-items-center">';
                        html += '<div class="flex-shrink-0">';
                        html += '<img src="<?=$this->siteUrl()?>/<?=PUBLIC_PATH?>/<?=UPLOADS_PATH?>/users/'+ user.imagelocation +'" class="avatar-xs rounded-circle" alt="" />';
                        html += '</div>';

                        html += '<div class="flex-grow-1 ms-3">';
                        html += '<h6 class="fs-14 mb-1"><a href="#">'+ user.firstname +' '+ user.lastname +'</a></h6>';

                        if (plan) {
                            html += '<p class="text-muted mb-0">$'+ investment.amount +' On '+ plan.name +'</p>';
                        }

                        html += '</div>';

                        html += '<div class="flex-shrink-0 text-end me-2">';
                        html += '<h6 class="fs-14 mb-1">'+ formatDate(investment.initiated_at) +'</h6>';

                        html += '<p class="fs-12 mb-0">Initiated</p>';
                        
                        html += '</div>';
                        
                        html += '<div class="flex-shrink-0 text-end ms-2">';
                        html += '<div class="dropdown d-inline-block">';
                        html += '<a href="<?=$this->siteUrl()?>/admin/investments/view-investment/'+ investment.investId +'" class="btn btn-soft-secondary btn-sm dropdown">Details</a>';
                        html += '</div>';
                        html += '</div>';
                        html += '</li>';
                    }

                    // Append the new content to the container
                    container.find(".investments").append(html);

                    // Update the page number
                    page++;

                    // If there is no more content to load, hide the load more button
                    if (investments.length === 0) {
                        loadMoreInitiatedInvestments.hide();
                        lastPageDiv.removeClass("d-none");
                    } else {
                        loadMoreInitiatedInvestments.text("Load More"); // Restore the button text
                        loadMoreInitiatedInvestments.data("page", page); // Update the data-page attribute
                    }
                },
                error: function() {
                    // Handle the error case
                    loadMoreInitiatedInvestments.text("Load More"); // Restore the button text
                },
            });
        });

        function formatDate(dateString) {
            const date = new Date(dateString);
            const options = { day: 'numeric', month: 'short', year: 'numeric' };
            return date.toLocaleDateString('en-US', options);
        }

        // Custom function to retrieve user details by ID
        function getUserById(userid) {
            const users = <?= json_encode($data['users']) ?>; // Retrieve the user array from PHP
            return users.find(user => user.userid === userid) || null;
        }

        // Custom function to retrieve plans by ID
        function getPlanById(planId) {
            const plans = <?= json_encode($data['plans']) ?>; // Retrieve the plan array from PHP
            return plans.find(plan => plan.planId === planId) || null;
        }
    });
</script>

<!-- Load More Withdrawals -->
<script>
    $(document).ready(function() {
        let page = 2; // Initial page number
        const container = $("#loadMoreWithdrawalsContainer");
        const loadMoreWithdrawals = $(".loadMoreWithdrawals");
        const lastPageDiv = $("#WithdrawalsLastpage"); // Get the reference to the last page div

        loadMoreWithdrawals.click(function(e) {
            e.preventDefault();

            const userId = $(this).data("id");
            const currentPage = $(this).data("page");

            loadMoreWithdrawals.html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Loading...</span>');

            // Send an AJAX request to fetch more content
            $.ajax({
                url: "<?=$this->siteUrl()?>/admin/withdrawals", // Update with the correct URL
                type: "GET",
                data: { 
                    userid: userId,
                    page: currentPage 
                },
                success: function(response) {
                    const withdrawals = response.withdrawals;

                    // Construct the HTML markup for each withdrawal item
                    let html = '';
                    for (let i = 0; i < withdrawals.length; i++) {
                        const withdrawal = withdrawals[i];

                        // Retrieve the associated plan information
                        const gateway = getGatewayById(withdrawal.method_code);

                        html += '<tr>';
                        html += '<td>';
                        html += '<div class="d-flex align-items-center">';

                        if (gateway) {
                            html += '<img src="<?= $this->siteUrl() ?>/<?= PUBLIC_PATH ?>/<?= UPLOADS_PATH ?>/withdrawal/' + gateway.image + '" alt="" class="avatar-xs rounded-circle" />';
                        }

                        html += '<div class="ms-2">';

                        if (gateway) {
                            html += '<a href="<?=$this->siteUrl()?>/admin/withdrawals/view-withdrawal/'+ withdrawal.withdrawId +'"><h6 class="fs-15 mb-1">'+ gateway.name +'</h6></a>';
                        }

                        html += '<small class="mb-0 text-muted">'  + formatDate(withdrawal.created_at) + '</small>';

                        const currency = '$';
                        html += '</div></div></td>';

                        html += '<td>'+formatCurrency(currency, withdrawal.amount)+'</td>';

                        if (withdrawal.status == "0") {
                            html += '<td>Initiated</td>';
                        } else if (withdrawal.status == "1") {
                            html += '<td class="text-success">Completed</td>';
                        } else if (withdrawal.status == "2") {
                            html += '<td class="text-warning">Pending</td>';
                        } else if (withdrawal.status == "3") {
                            html += '<td class="text-danger">Rejected</td>';
                        }

                        html += '<td>';
                        html += '<div class="dropdown d-inline-block">';
                        html += '<a href="<?=$this->siteUrl()?>/admin/withdrawals/view-withdrawal/'+ withdrawal.withdrawId +'" class="btn btn-soft-secondary btn-sm dropdown">Details</a>';
                        html += '</div>';
                        html += '</td>';
                        html += '</tr>';
                    }

                    // Append the new content to the container
                    container.find(".withdrawals").append(html);

                    // Update the page number
                    page++;

                    // If there is no more content to load, hide the load more button
                    if (withdrawals.length === 0) {
                        loadMoreWithdrawals.hide();
                        lastPageDiv.removeClass("d-none");
                    } else {
                        loadMoreWithdrawals.text("Load More"); // Restore the button text
                        loadMoreWithdrawals.data("page", page); // Update the data-page attribute
                    }
                },
                error: function() {
                    // Handle the error case
                    loadMoreWithdrawals.text("Load More"); // Restore the button text
                },
            });
        });

        // Custom function to retrieve gateways by ID
        function getGatewayById(method_code) {
            const gatewaysMap = {};
            const gateways = <?= json_encode($data['withdrawal-gateways']) ?>; // Retrieve the gateway array from PHP

            // Build a map of gateways keyed by their method_code
            gateways.forEach(gateway => {
                gatewaysMap[gateway.method_code] = gateway;
            });

            // Return the gateway if found, otherwise return null
            return gatewaysMap[method_code] || null;
        }

        function formatCurrency(currency, amount) {
            // Parse the amount as float to ensure it's treated as a number
            amount = parseFloat(amount);

            switch(currency) {
                case '€':
                    return '€' + amount.toFixed(2);
                case '£':
                    return '£' + amount.toFixed(2);
                case '$':
                    return '$' + amount.toFixed(2);
                default:
                    return currency + ' ' + amount.toFixed(2);
            }
        }

        function formatDate(createdAt){

            // Convert the createdAt string to a Date object
            const createdAtDate = new Date(createdAt);

            // Format the date components
            const day = createdAtDate.getDate().toString().padStart(2, '0');
            const month = (createdAtDate.getMonth() + 1).toString().padStart(2, '0'); // Months are zero-indexed, so add 1
            const year = createdAtDate.getFullYear();
            let hours = createdAtDate.getHours().toString().padStart(2, '0');
            const minutes = createdAtDate.getMinutes().toString().padStart(2, '0');
            const meridiem = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12; // Handle midnight (0 hours)

            // Construct the formatted date string
            return day + '-' + month + '-' + year + ' ' + hours + ':' + minutes + ' ' + meridiem;
        }
    });
</script>

<!-- Load More Pending Withdrawals -->
<script>
    $(document).ready(function() {
        let page = 2; // Initial page number
        const container = $("#loadMorePendingWithdrawalsContainer");
        const loadMorePendingWithdrawals = $(".loadMorePendingWithdrawals");
        const lastPageDiv = $("#PendingWithdrawalsLastpage"); // Get the reference to the last page div

        loadMorePendingWithdrawals.click(function(e) {
            e.preventDefault();

            const userId = $(this).data("id");
            const currentPage = $(this).data("page");

            loadMorePendingWithdrawals.html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Loading...</span>');

            // Send an AJAX request to fetch more content
            $.ajax({
                url: "<?=$this->siteUrl()?>/admin/withdrawals_pending", // Update with the correct URL
                type: "GET",
                data: { 
                    userid: userId,
                    page: currentPage 
                },
                success: function(response) {
                    const withdrawals = response.withdrawals;

                    // Construct the HTML markup for each withdrawal item
                    let html = '';
                    for (let i = 0; i < withdrawals.length; i++) {
                        const withdrawal = withdrawals[i];

                        // Retrieve the associated plan information
                        const gateway = getGatewayById(withdrawal.method_code);

                        html += '<tr>';
                        html += '<td>';
                        html += '<div class="d-flex align-items-center">';

                        if (gateway) {
                            html += '<img src="<?= $this->siteUrl() ?>/<?= PUBLIC_PATH ?>/<?= UPLOADS_PATH ?>/withdrawal/' + gateway.image + '" alt="" class="avatar-xs rounded-circle" />';
                        }

                        html += '<div class="ms-2">';

                        if (gateway) {
                            html += '<a href="<?=$this->siteUrl()?>/admin/withdrawals/view-withdrawal/'+ withdrawal.withdrawId +'"><h6 class="fs-15 mb-1">'+ gateway.name +'</h6></a>';
                        }

                        html += '<small class="mb-0 text-muted">'  + formatDate(withdrawal.created_at) + '</small>';

                        const currency = '$';
                        html += '</div></div></td>';

                        html += '<td>'+formatCurrency(currency, withdrawal.amount)+'</td>';

                        if (withdrawal.status == "0") {
                            html += '<td>Initiated</td>';
                        } else if (withdrawal.status == "1") {
                            html += '<td class="text-success">Completed</td>';
                        } else if (withdrawal.status == "2") {
                            html += '<td class="text-warning">Pending</td>';
                        } else if (withdrawal.status == "3") {
                            html += '<td class="text-danger">Rejected</td>';
                        }

                        html += '<td>';
                        html += '<div class="dropdown d-inline-block">';
                        html += '<a href="<?=$this->siteUrl()?>/admin/withdrawals/view-withdrawal/'+ withdrawal.withdrawId +'" class="btn btn-soft-secondary btn-sm dropdown">Details</a>';
                        html += '</div>';
                        html += '</td>';
                        html += '</tr>';
                    }

                    // Append the new content to the container
                    container.find(".withdrawals").append(html);

                    // Update the page number
                    page++;

                    // If there is no more content to load, hide the load more button
                    if (withdrawals.length === 0) {
                        loadMorePendingWithdrawals.hide();
                        lastPageDiv.removeClass("d-none");
                    } else {
                        loadMorePendingWithdrawals.text("Load More"); // Restore the button text
                        loadMorePendingWithdrawals.data("page", page); // Update the data-page attribute
                    }
                },
                error: function() {
                    // Handle the error case
                    loadMorePendingWithdrawals.text("Load More"); // Restore the button text
                },
            });
        });

        // Custom function to retrieve gateways by ID
        function getGatewayById(method_code) {
            const gatewaysMap = {};
            const gateways = <?= json_encode($data['withdrawal-gateways']) ?>; // Retrieve the gateway array from PHP

            // Build a map of gateways keyed by their method_code
            gateways.forEach(gateway => {
                gatewaysMap[gateway.method_code] = gateway;
            });

            // Return the gateway if found, otherwise return null
            return gatewaysMap[method_code] || null;
        }

        function formatCurrency(currency, amount) {
            // Parse the amount as float to ensure it's treated as a number
            amount = parseFloat(amount);

            switch(currency) {
                case '€':
                    return '€' + amount.toFixed(2);
                case '£':
                    return '£' + amount.toFixed(2);
                case '$':
                    return '$' + amount.toFixed(2);
                default:
                    return currency + ' ' + amount.toFixed(2);
            }
        }

        function formatDate(createdAt){

            // Convert the createdAt string to a Date object
            const createdAtDate = new Date(createdAt);

            // Format the date components
            const day = createdAtDate.getDate().toString().padStart(2, '0');
            const month = (createdAtDate.getMonth() + 1).toString().padStart(2, '0'); // Months are zero-indexed, so add 1
            const year = createdAtDate.getFullYear();
            let hours = createdAtDate.getHours().toString().padStart(2, '0');
            const minutes = createdAtDate.getMinutes().toString().padStart(2, '0');
            const meridiem = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12; // Handle midnight (0 hours)

            // Construct the formatted date string
            return day + '-' + month + '-' + year + ' ' + hours + ':' + minutes + ' ' + meridiem;
        }
    });
</script>

<!-- Load More Completed Withdrawals -->
<script>
    $(document).ready(function() {
        let page = 2; // Initial page number
        const container = $("#loadMoreCompletedWithdrawalsContainer");
        const loadMoreCompletedWithdrawals = $(".loadMoreCompletedWithdrawals");
        const lastPageDiv = $("#CompletedWithdrawalsLastpage"); // Get the reference to the last page div

        loadMoreCompletedWithdrawals.click(function(e) {
            e.preventDefault();

            const userId = $(this).data("id");
            const currentPage = $(this).data("page");

            loadMoreCompletedWithdrawals.html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Loading...</span>');

            // Send an AJAX request to fetch more content
            $.ajax({
                url: "<?=$this->siteUrl()?>/admin/withdrawals_completed", // Update with the correct URL
                type: "GET",
                data: { 
                    userid: userId,
                    page: currentPage 
                },
                success: function(response) {
                    const withdrawals = response.withdrawals;

                    // Construct the HTML markup for each withdrawal item
                    let html = '';
                    for (let i = 0; i < withdrawals.length; i++) {
                        const withdrawal = withdrawals[i];

                        // Retrieve the associated plan information
                        const gateway = getGatewayById(withdrawal.method_code);

                        html += '<tr>';
                        html += '<td>';
                        html += '<div class="d-flex align-items-center">';

                        if (gateway) {
                            html += '<img src="<?= $this->siteUrl() ?>/<?= PUBLIC_PATH ?>/<?= UPLOADS_PATH ?>/withdrawal/' + gateway.image + '" alt="" class="avatar-xs rounded-circle" />';
                        }

                        html += '<div class="ms-2">';

                        if (gateway) {
                            html += '<a href="<?=$this->siteUrl()?>/admin/withdrawals/view-withdrawal/'+ withdrawal.withdrawId +'"><h6 class="fs-15 mb-1">'+ gateway.name +'</h6></a>';
                        }

                        html += '<small class="mb-0 text-muted">'  + formatDate(withdrawal.created_at) + '</small>';

                        const currency = '$';
                        html += '</div></div></td>';

                        html += '<td>'+formatCurrency(currency, withdrawal.amount)+'</td>';

                        if (withdrawal.status == "0") {
                            html += '<td>Initiated</td>';
                        } else if (withdrawal.status == "1") {
                            html += '<td class="text-success">Completed</td>';
                        } else if (withdrawal.status == "2") {
                            html += '<td class="text-warning">Pending</td>';
                        } else if (withdrawal.status == "3") {
                            html += '<td class="text-danger">Rejected</td>';
                        }

                        html += '<td>';
                        html += '<div class="dropdown d-inline-block">';
                        html += '<a href="<?=$this->siteUrl()?>/admin/withdrawals/view-withdrawal/'+ withdrawal.withdrawId +'" class="btn btn-soft-secondary btn-sm dropdown">Details</a>';
                        html += '</div>';
                        html += '</td>';
                        html += '</tr>';
                    }

                    // Append the new content to the container
                    container.find(".withdrawals").append(html);

                    // Update the page number
                    page++;

                    // If there is no more content to load, hide the load more button
                    if (withdrawals.length === 0) {
                        loadMoreCompletedWithdrawals.hide();
                        lastPageDiv.removeClass("d-none");
                    } else {
                        loadMoreCompletedWithdrawals.text("Load More"); // Restore the button text
                        loadMoreCompletedWithdrawals.data("page", page); // Update the data-page attribute
                    }
                },
                error: function() {
                    // Handle the error case
                    loadMoreCompletedWithdrawals.text("Load More"); // Restore the button text
                },
            });
        });

        // Custom function to retrieve gateways by ID
        function getGatewayById(method_code) {
            const gatewaysMap = {};
            const gateways = <?= json_encode($data['withdrawal-gateways']) ?>; // Retrieve the gateway array from PHP

            // Build a map of gateways keyed by their method_code
            gateways.forEach(gateway => {
                gatewaysMap[gateway.method_code] = gateway;
            });

            // Return the gateway if found, otherwise return null
            return gatewaysMap[method_code] || null;
        }

        function formatCurrency(currency, amount) {
            // Parse the amount as float to ensure it's treated as a number
            amount = parseFloat(amount);

            switch(currency) {
                case '€':
                    return '€' + amount.toFixed(2);
                case '£':
                    return '£' + amount.toFixed(2);
                case '$':
                    return '$' + amount.toFixed(2);
                default:
                    return currency + ' ' + amount.toFixed(2);
            }
        }

        function formatDate(createdAt){

            // Convert the createdAt string to a Date object
            const createdAtDate = new Date(createdAt);

            // Format the date components
            const day = createdAtDate.getDate().toString().padStart(2, '0');
            const month = (createdAtDate.getMonth() + 1).toString().padStart(2, '0'); // Months are zero-indexed, so add 1
            const year = createdAtDate.getFullYear();
            let hours = createdAtDate.getHours().toString().padStart(2, '0');
            const minutes = createdAtDate.getMinutes().toString().padStart(2, '0');
            const meridiem = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12; // Handle midnight (0 hours)

            // Construct the formatted date string
            return day + '-' + month + '-' + year + ' ' + hours + ':' + minutes + ' ' + meridiem;
        }
    });
</script>

<!-- Load More Initiated Withdrawals -->
<script>
    $(document).ready(function() {
        let page = 2; // Initial page number
        const container = $("#loadMoreInitiatedWithdrawalsContainer");
        const loadMoreInitiatedWithdrawals = $(".loadMoreInitiatedWithdrawals");
        const lastPageDiv = $("#InitiatedWithdrawalsLastpage"); // Get the reference to the last page div

        loadMoreInitiatedWithdrawals.click(function(e) {
            e.preventDefault();

            const userId = $(this).data("id");
            const currentPage = $(this).data("page");

            loadMoreInitiatedWithdrawals.html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Loading...</span>');

            // Send an AJAX request to fetch more content
            $.ajax({
                url: "<?=$this->siteUrl()?>/admin/withdrawals_initiated", // Update with the correct URL
                type: "GET",
                data: { 
                    userid: userId,
                    page: currentPage 
                },
                success: function(response) {
                    const withdrawals = response.withdrawals;

                    // Construct the HTML markup for each withdrawal item
                    let html = '';
                    for (let i = 0; i < withdrawals.length; i++) {
                        const withdrawal = withdrawals[i];

                        // Retrieve the associated plan information
                        const gateway = getGatewayById(withdrawal.method_code);

                        html += '<tr>';
                        html += '<td>';
                        html += '<div class="d-flex align-items-center">';

                        if (gateway) {
                            html += '<img src="<?= $this->siteUrl() ?>/<?= PUBLIC_PATH ?>/<?= UPLOADS_PATH ?>/withdrawal/' + gateway.image + '" alt="" class="avatar-xs rounded-circle" />';
                        }

                        html += '<div class="ms-2">';

                        if (gateway) {
                            html += '<a href="<?=$this->siteUrl()?>/admin/withdrawals/view-withdrawal/'+ withdrawal.withdrawId +'"><h6 class="fs-15 mb-1">'+ gateway.name +'</h6></a>';
                        }

                        html += '<small class="mb-0 text-muted">'  + formatDate(withdrawal.created_at) + '</small>';

                        const currency = '$';
                        html += '</div></div></td>';

                        html += '<td>'+formatCurrency(currency, withdrawal.amount)+'</td>';

                        if (withdrawal.status == "0") {
                            html += '<td>Initiated</td>';
                        } else if (withdrawal.status == "1") {
                            html += '<td class="text-success">Completed</td>';
                        } else if (withdrawal.status == "2") {
                            html += '<td class="text-warning">Pending</td>';
                        } else if (withdrawal.status == "3") {
                            html += '<td class="text-danger">Rejected</td>';
                        }

                        html += '<td>';
                        html += '<div class="dropdown d-inline-block">';
                        html += '<a href="<?=$this->siteUrl()?>/admin/withdrawals/view-withdrawal/'+ withdrawal.withdrawId +'" class="btn btn-soft-secondary btn-sm dropdown">Details</a>';
                        html += '</div>';
                        html += '</td>';
                        html += '</tr>';
                    }

                    // Append the new content to the container
                    container.find(".withdrawals").append(html);

                    // Update the page number
                    page++;

                    // If there is no more content to load, hide the load more button
                    if (withdrawals.length === 0) {
                        loadMoreInitiatedWithdrawals.hide();
                        lastPageDiv.removeClass("d-none");
                    } else {
                        loadMoreInitiatedWithdrawals.text("Load More"); // Restore the button text
                        loadMoreInitiatedWithdrawals.data("page", page); // Update the data-page attribute
                    }
                },
                error: function() {
                    // Handle the error case
                    loadMoreInitiatedWithdrawals.text("Load More"); // Restore the button text
                },
            });
        });

        // Custom function to retrieve gateways by ID
        function getGatewayById(method_code) {
            const gatewaysMap = {};
            const gateways = <?= json_encode($data['withdrawal-gateways']) ?>; // Retrieve the gateway array from PHP

            // Build a map of gateways keyed by their method_code
            gateways.forEach(gateway => {
                gatewaysMap[gateway.method_code] = gateway;
            });

            // Return the gateway if found, otherwise return null
            return gatewaysMap[method_code] || null;
        }

        function formatCurrency(currency, amount) {
            // Parse the amount as float to ensure it's treated as a number
            amount = parseFloat(amount);

            switch(currency) {
                case '€':
                    return '€' + amount.toFixed(2);
                case '£':
                    return '£' + amount.toFixed(2);
                case '$':
                    return '$' + amount.toFixed(2);
                default:
                    return currency + ' ' + amount.toFixed(2);
            }
        }

        function formatDate(createdAt){

            // Convert the createdAt string to a Date object
            const createdAtDate = new Date(createdAt);

            // Format the date components
            const day = createdAtDate.getDate().toString().padStart(2, '0');
            const month = (createdAtDate.getMonth() + 1).toString().padStart(2, '0'); // Months are zero-indexed, so add 1
            const year = createdAtDate.getFullYear();
            let hours = createdAtDate.getHours().toString().padStart(2, '0');
            const minutes = createdAtDate.getMinutes().toString().padStart(2, '0');
            const meridiem = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12; // Handle midnight (0 hours)

            // Construct the formatted date string
            return day + '-' + month + '-' + year + ' ' + hours + ':' + minutes + ' ' + meridiem;
        }
    });
</script>

<!-- Load More Rejected Withdrawals -->
<script>
    $(document).ready(function() {
        let page = 2; // Initial page number
        const container = $("#loadMoreRejectedWithdrawalsContainer");
        const loadMoreRejectedWithdrawals = $(".loadMoreRejectedWithdrawals");
        const lastPageDiv = $("#RejectedWithdrawalsLastpage"); // Get the reference to the last page div

        loadMoreRejectedWithdrawals.click(function(e) {
            e.preventDefault();

            const userId = $(this).data("id");
            const currentPage = $(this).data("page");

            loadMoreRejectedWithdrawals.html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Loading...</span>');

            // Send an AJAX request to fetch more content
            $.ajax({
                url: "<?=$this->siteUrl()?>/admin/withdrawals_rejected", // Update with the correct URL
                type: "GET",
                data: { 
                    userid: userId,
                    page: currentPage 
                },
                success: function(response) {
                    const withdrawals = response.withdrawals;

                    // Construct the HTML markup for each withdrawal item
                    let html = '';
                    for (let i = 0; i < withdrawals.length; i++) {
                        const withdrawal = withdrawals[i];

                        // Retrieve the associated plan information
                        const gateway = getGatewayById(withdrawal.method_code);

                        html += '<tr>';
                        html += '<td>';
                        html += '<div class="d-flex align-items-center">';

                        if (gateway) {
                            html += '<img src="<?= $this->siteUrl() ?>/<?= PUBLIC_PATH ?>/<?= UPLOADS_PATH ?>/withdrawal/' + gateway.image + '" alt="" class="avatar-xs rounded-circle" />';
                        }

                        html += '<div class="ms-2">';

                        if (gateway) {
                            html += '<a href="<?=$this->siteUrl()?>/admin/withdrawals/view-withdrawal/'+ withdrawal.withdrawId +'"><h6 class="fs-15 mb-1">'+ gateway.name +'</h6></a>';
                        }

                        html += '<small class="mb-0 text-muted">'  + formatDate(withdrawal.created_at) + '</small>';

                        const currency = '$';
                        html += '</div></div></td>';

                        html += '<td>'+formatCurrency(currency, withdrawal.amount)+'</td>';

                        if (withdrawal.status == "0") {
                            html += '<td>Initiated</td>';
                        } else if (withdrawal.status == "1") {
                            html += '<td class="text-success">Completed</td>';
                        } else if (withdrawal.status == "2") {
                            html += '<td class="text-warning">Pending</td>';
                        } else if (withdrawal.status == "3") {
                            html += '<td class="text-danger">Rejected</td>';
                        }

                        html += '<td>';
                        html += '<div class="dropdown d-inline-block">';
                        html += '<a href="<?=$this->siteUrl()?>/admin/withdrawals/view-withdrawal/'+ withdrawal.withdrawId +'" class="btn btn-soft-secondary btn-sm dropdown">Details</a>';
                        html += '</div>';
                        html += '</td>';
                        html += '</tr>';
                    }

                    // Append the new content to the container
                    container.find(".withdrawals").append(html);

                    // Update the page number
                    page++;

                    // If there is no more content to load, hide the load more button
                    if (withdrawals.length === 0) {
                        loadMoreRejectedWithdrawals.hide();
                        lastPageDiv.removeClass("d-none");
                    } else {
                        loadMoreRejectedWithdrawals.text("Load More"); // Restore the button text
                        loadMoreRejectedWithdrawals.data("page", page); // Update the data-page attribute
                    }
                },
                error: function() {
                    // Handle the error case
                    loadMoreRejectedWithdrawals.text("Load More"); // Restore the button text
                },
            });
        });

        // Custom function to retrieve gateways by ID
        function getGatewayById(method_code) {
            const gatewaysMap = {};
            const gateways = <?= json_encode($data['withdrawal-gateways']) ?>; // Retrieve the gateway array from PHP

            // Build a map of gateways keyed by their method_code
            gateways.forEach(gateway => {
                gatewaysMap[gateway.method_code] = gateway;
            });

            // Return the gateway if found, otherwise return null
            return gatewaysMap[method_code] || null;
        }

        function formatCurrency(currency, amount) {
            // Parse the amount as float to ensure it's treated as a number
            amount = parseFloat(amount);

            switch(currency) {
                case '€':
                    return '€' + amount.toFixed(2);
                case '£':
                    return '£' + amount.toFixed(2);
                case '$':
                    return '$' + amount.toFixed(2);
                default:
                    return currency + ' ' + amount.toFixed(2);
            }
        }

        function formatDate(createdAt){

            // Convert the createdAt string to a Date object
            const createdAtDate = new Date(createdAt);

            // Format the date components
            const day = createdAtDate.getDate().toString().padStart(2, '0');
            const month = (createdAtDate.getMonth() + 1).toString().padStart(2, '0'); // Months are zero-indexed, so add 1
            const year = createdAtDate.getFullYear();
            let hours = createdAtDate.getHours().toString().padStart(2, '0');
            const minutes = createdAtDate.getMinutes().toString().padStart(2, '0');
            const meridiem = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12; // Handle midnight (0 hours)

            // Construct the formatted date string
            return day + '-' + month + '-' + year + ' ' + hours + ':' + minutes + ' ' + meridiem;
        }
    });
</script>

<!-- Approve Withdrawals -->
<script>
    // Event listener for approving address proof
    $('#approveWithdrawalBtn').on('click', function () {
        
        // Retrieving IDs from data attributes
        const withdrawIdValue = $(this).data("approve");

        // Checking if IDs are set
        if (!withdrawIdValue) {
            console.error("ID is not set in the data attribute.");
            return;
        }

        // Adding spinner and disabling button
        $("#approveWithdrawalBtn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
        $("#approveWithdrawalBtn").prop("disabled", true);

        // Making AJAX request to approve withdrawals
        $.ajax({
            type: 'GET',
            url: '<?=$this->siteUrl()?>/admin/withdrawals/view-withdrawal/' + withdrawIdValue,
            data: { 
                approve: withdrawIdValue 
            },
            success: function(response) {
                // Handling response based on status
                if (response.status === "success") {
                    // Displaying a success message
                    iziToast.success({
                        message: response.message,
                        position: 'topRight'
                    });

                    // Reloading page after a delay
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else if (response.status === "error") {
                    // Displaying error message
                    iziToast.error({
                        message: response.message,
                        position: 'topRight'
                    });
                } else if (response.status === "warning") {
                    // Displaying a warning message
                    iziToast.error({
                        message: response.message,
                        position: 'topRight'
                    });

                    // Reloading page after a delay
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
            },
            error: function(xhr) {
                // Handling errors
                console.log(xhr.responseText);
                iziToast.error({
                    message: "An error occurred. Please try again.",
                    position: "topRight",
                });
            },
            complete: function () {
                // Removing spinner and enabling button
                $("#approveWithdrawalBtn").html("Yes, Approve");
                $("#approveWithdrawalBtn").prop("disabled", false);
            },
        });
    });
</script>

<!-- Reject Withdrawals -->
<script>
    // Event listener for approving address proof
    $('#rejectWithdrawalBtn').on('click', function () {
        
        // Retrieving IDs from data attributes
        const withdrawIdValue = $(this).data("reject");

        // Checking if IDs are set
        if (!withdrawIdValue) {
            console.error("ID is not set in the data attribute.");
            return;
        }

        // Adding spinner and disabling button
        $("#rejectWithdrawalBtn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
        $("#rejectWithdrawalBtn").prop("disabled", true);

        // Making AJAX request to reject withdrawals
        $.ajax({
            type: 'GET',
            url: '<?=$this->siteUrl()?>/admin/withdrawals/view-withdrawal/' + withdrawIdValue,
            data: { 
                reject: withdrawIdValue 
            },
            success: function(response) {
                // Handling response based on status
                if (response.status === "success") {
                    // Displaying a success message
                    iziToast.success({
                        message: response.message,
                        position: 'topRight'
                    });

                    // Reloading page after a delay
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else if (response.status === "error") {
                    // Displaying error message
                    iziToast.error({
                        message: response.message,
                        position: 'topRight'
                    });
                } else if (response.status === "warning") {
                    // Displaying a warning message
                    iziToast.error({
                        message: response.message,
                        position: 'topRight'
                    });

                    // Reloading page after a delay
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
            },
            error: function(xhr) {
                // Handling errors
                console.log(xhr.responseText);
                iziToast.error({
                    message: "An error occurred. Please try again.",
                    position: "topRight",
                });
            },
            complete: function () {
                // Removing spinner and enabling button
                $("#rejectWithdrawalBtn").html("Yes, Reject");
                $("#rejectWithdrawalBtn").prop("disabled", false);
            },
        });
    });
</script>

<!-- Load More Deposit -->
<script>
    $(document).ready(function() {
        let page = 2; // Initial page number
        const container = $("#loadMoreAllDepositsContainer");
        const loadMoreAllDeposits = $(".loadMoreAllDeposits");
        const lastPageDiv = $("#AllDepositsLastpage"); // Get the reference to the last page div

        loadMoreAllDeposits.click(function(e) {
            e.preventDefault();

            const currentPage = $(this).data("page");

            loadMoreAllDeposits.html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Loading...</span>');

            // Send an AJAX request to fetch more content
            $.ajax({
                url: "<?= $this->siteUrl() ?>/admin/deposits", // Update with the correct URL
                type: "GET",
                data: {
                    page: currentPage 
                },
                success: function(response) {
                    const deposits = response.deposits;

                    // Construct the HTML markup for each deposit item
                    let html = '';
                    for (let i = 0; i < deposits.length; i++) {
                        const deposit = deposits[i];

                        // Retrieve the associated plan information
                        const gateway = getGatewayById(deposit.method_code);

                        html += '<tr>';
                        html += '<td>';
                        html += '<div class="d-flex align-items-center">';

                        if (gateway) {
                            html += '<img src="<?= $this->siteUrl() ?>/<?= PUBLIC_PATH ?>/<?= UPLOADS_PATH ?>/deposit/' + gateway.image + '" alt="" class="avatar-xs rounded-circle" />';
                        }

                        html += '<div class="ms-2">';

                        if (gateway) {
                            html += '<a href="<?= $this->siteUrl() ?>/admin/deposits/view-deposit/'+ deposit.depositId +'"><h6 class="fs-15 mb-1">'+ gateway.name +'</h6></a>';
                        }

                        html += '<small class="mb-0 text-muted">'  + formatDate(deposit.created_at) + '</small>';

                        const currency = '$';
                        html += '</div></div></td>';

                        html += '<td>'+formatCurrency(currency, deposit.amount)+'</td>';

                        if (deposit.status == "0") {
                            html += '<td>Initiated</td>';
                        } else if (deposit.status == "1") {
                            html += '<td class="text-success">Completed</td>';
                        } else if (deposit.status == "2") {
                            html += '<td class="text-warning">Pending</td>';
                        } else if (deposit.status == "3") {
                            html += '<td class="text-danger">Rejected</td>';
                        }

                        html += '<td>';
                        html += '<div class="dropdown d-inline-block">';
                        html += '<a href="<?= $this->siteUrl() ?>/admin/deposits/view-deposit/'+ deposit.depositId +'" class="btn btn-soft-secondary btn-sm dropdown">Details</a>';
                        html += '</div>';
                        html += '</td>';
                        html += '</tr>';
                    }

                    // Append the new content to the container
                    container.find(".deposits").append(html);

                    // Update the page number
                    page++;

                    // If there is no more content to load, hide the load more button
                    if (deposits.length === 0) {
                        loadMoreAllDeposits.hide();
                        lastPageDiv.removeClass("d-none");
                    } else {
                        loadMoreAllDeposits.text("Load More"); // Restore the button text
                        loadMoreAllDeposits.data("page", page); // Update the data-page attribute
                    }
                },
                error: function() {
                    // Handle the error case
                    loadMoreAllDeposits.text("Load More"); // Restore the button text
                },
            });
        });

        // Custom function to retrieve gateways by ID
        function getGatewayById(method_code) {
            const gatewaysMap = {};
            const gateways = <?= json_encode($data['gateways']) ?>; // Retrieve the gateway array from PHP

            // Build a map of gateways keyed by their method_code
            gateways.forEach(gateway => {
                gatewaysMap[gateway.method_code] = gateway;
            });

            // Return the gateway if found, otherwise return null
            return gatewaysMap[method_code] || null;
        }

        function formatCurrency(currency, amount) {
            // Parse the amount as float to ensure it's treated as a number
            amount = parseFloat(amount);

            switch(currency) {
                case '€':
                    return '€' + amount.toFixed(2);
                case '£':
                    return '£' + amount.toFixed(2);
                case '$':
                    return '$' + amount.toFixed(2);
                default:
                    return currency + ' ' + amount.toFixed(2);
            }
        }

        function formatDate(createdAt){

            // Convert the createdAt string to a Date object
            const createdAtDate = new Date(createdAt);

            // Format the date components
            const day = createdAtDate.getDate().toString().padStart(2, '0');
            const month = (createdAtDate.getMonth() + 1).toString().padStart(2, '0'); // Months are zero-indexed, so add 1
            const year = createdAtDate.getFullYear();
            let hours = createdAtDate.getHours().toString().padStart(2, '0');
            const minutes = createdAtDate.getMinutes().toString().padStart(2, '0');
            const meridiem = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12; // Handle midnight (0 hours)

            // Construct the formatted date string
            return day + '-' + month + '-' + year + ' ' + hours + ':' + minutes + ' ' + meridiem;
        }
    });
</script>

<!-- Load More Pending Deposit -->
<script>
    $(document).ready(function() {
        let page = 2; // Initial page number
        const container = $("#loadMorePendingDepositsContainer");
        const loadMorePendingDeposits = $(".loadMorePendingDeposits");
        const lastPageDiv = $("#PendingDepositsLastpage"); // Get the reference to the last page div

        loadMorePendingDeposits.click(function(e) {
            e.preventDefault();

            const currentPage = $(this).data("page");

            loadMorePendingDeposits.html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Loading...</span>');

            // Send an AJAX request to fetch more content
            $.ajax({
                url: "<?= $this->siteUrl() ?>/admin/deposits_pending", // Update with the correct URL
                type: "GET",
                data: {
                    page: currentPage 
                },
                success: function(response) {
                    const deposits = response.deposits;

                    // Construct the HTML markup for each deposit item
                    let html = '';
                    for (let i = 0; i < deposits.length; i++) {
                        const deposit = deposits[i];

                        // Retrieve the associated plan information
                        const gateway = getGatewayById(deposit.method_code);

                        html += '<tr>';
                        html += '<td>';
                        html += '<div class="d-flex align-items-center">';

                        if (gateway) {
                            html += '<img src="<?= $this->siteUrl() ?>/<?= PUBLIC_PATH ?>/<?= UPLOADS_PATH ?>/deposit/' + gateway.image + '" alt="" class="avatar-xs rounded-circle" />';
                        }

                        html += '<div class="ms-2">';

                        if (gateway) {
                            html += '<a href="<?= $this->siteUrl() ?>/admin/deposits/view-deposit/'+ deposit.depositId +'"><h6 class="fs-15 mb-1">'+ gateway.name +'</h6></a>';
                        }

                        html += '<small class="mb-0 text-muted">'  + formatDate(deposit.created_at) + '</small>';

                        const currency = '$';
                        html += '</div></div></td>';

                        html += '<td>'+formatCurrency(currency, deposit.amount)+'</td>';

                        if (deposit.status == "0") {
                            html += '<td>Initiated</td>';
                        } else if (deposit.status == "1") {
                            html += '<td class="text-success">Completed</td>';
                        } else if (deposit.status == "2") {
                            html += '<td class="text-warning">Pending</td>';
                        } else if (deposit.status == "3") {
                            html += '<td class="text-danger">Rejected</td>';
                        }

                        html += '<td>';
                        html += '<div class="dropdown d-inline-block">';
                        html += '<a href="<?= $this->siteUrl() ?>/admin/deposits/view-deposit/'+ deposit.depositId +'" class="btn btn-soft-secondary btn-sm dropdown">Details</a>';
                        html += '</div>';
                        html += '</td>';
                        html += '</tr>';
                    }

                    // Append the new content to the container
                    container.find(".deposits").append(html);

                    // Update the page number
                    page++;

                    // If there is no more content to load, hide the load more button
                    if (deposits.length === 0) {
                        loadMorePendingDeposits.hide();
                        lastPageDiv.removeClass("d-none");
                    } else {
                        loadMorePendingDeposits.text("Load More"); // Restore the button text
                        loadMorePendingDeposits.data("page", page); // Update the data-page attribute
                    }
                },
                error: function() {
                    // Handle the error case
                    loadMorePendingDeposits.text("Load More"); // Restore the button text
                },
            });
        });

        // Custom function to retrieve gateways by ID
        function getGatewayById(method_code) {
            const gatewaysMap = {};
            const gateways = <?= json_encode($data['gateways']) ?>; // Retrieve the gateway array from PHP

            // Build a map of gateways keyed by their method_code
            gateways.forEach(gateway => {
                gatewaysMap[gateway.method_code] = gateway;
            });

            // Return the gateway if found, otherwise return null
            return gatewaysMap[method_code] || null;
        }

        function formatCurrency(currency, amount) {
            // Parse the amount as float to ensure it's treated as a number
            amount = parseFloat(amount);

            switch(currency) {
                case '€':
                    return '€' + amount.toFixed(2);
                case '£':
                    return '£' + amount.toFixed(2);
                case '$':
                    return '$' + amount.toFixed(2);
                default:
                    return currency + ' ' + amount.toFixed(2);
            }
        }

        function formatDate(createdAt){

            // Convert the createdAt string to a Date object
            const createdAtDate = new Date(createdAt);

            // Format the date components
            const day = createdAtDate.getDate().toString().padStart(2, '0');
            const month = (createdAtDate.getMonth() + 1).toString().padStart(2, '0'); // Months are zero-indexed, so add 1
            const year = createdAtDate.getFullYear();
            let hours = createdAtDate.getHours().toString().padStart(2, '0');
            const minutes = createdAtDate.getMinutes().toString().padStart(2, '0');
            const meridiem = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12; // Handle midnight (0 hours)

            // Construct the formatted date string
            return day + '-' + month + '-' + year + ' ' + hours + ':' + minutes + ' ' + meridiem;
        }
    });
</script>

<!-- Load More Completed Deposit -->
<script>
    $(document).ready(function() {
        let page = 2; // Initial page number
        const container = $("#loadMoreCompletedDepositsContainer");
        const loadMoreCompletedDeposits = $(".loadMoreCompletedDeposits");
        const lastPageDiv = $("#CompletedDepositsLastpage"); // Get the reference to the last page div

        loadMoreCompletedDeposits.click(function(e) {
            e.preventDefault();

            const currentPage = $(this).data("page");

            loadMoreCompletedDeposits.html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Loading...</span>');

            // Send an AJAX request to fetch more content
            $.ajax({
                url: "<?= $this->siteUrl() ?>/admin/deposits_completed", // Update with the correct URL
                type: "GET",
                data: {
                    page: currentPage 
                },
                success: function(response) {
                    const deposits = response.deposits;

                    // Construct the HTML markup for each deposit item
                    let html = '';
                    for (let i = 0; i < deposits.length; i++) {
                        const deposit = deposits[i];

                        // Retrieve the associated plan information
                        const gateway = getGatewayById(deposit.method_code);

                        html += '<tr>';
                        html += '<td>';
                        html += '<div class="d-flex align-items-center">';

                        if (gateway) {
                            html += '<img src="<?= $this->siteUrl() ?>/<?= PUBLIC_PATH ?>/<?= UPLOADS_PATH ?>/deposit/' + gateway.image + '" alt="" class="avatar-xs rounded-circle" />';
                        }

                        html += '<div class="ms-2">';

                        if (gateway) {
                            html += '<a href="<?= $this->siteUrl() ?>/admin/deposits/view-deposit/'+ deposit.depositId +'"><h6 class="fs-15 mb-1">'+ gateway.name +'</h6></a>';
                        }

                        html += '<small class="mb-0 text-muted">'  + formatDate(deposit.created_at) + '</small>';

                        const currency = '$';
                        html += '</div></div></td>';

                        html += '<td>'+formatCurrency(currency, deposit.amount)+'</td>';

                        if (deposit.status == "0") {
                            html += '<td>Initiated</td>';
                        } else if (deposit.status == "1") {
                            html += '<td class="text-success">Completed</td>';
                        } else if (deposit.status == "2") {
                            html += '<td class="text-warning">Pending</td>';
                        } else if (deposit.status == "3") {
                            html += '<td class="text-danger">Rejected</td>';
                        }

                        html += '<td>';
                        html += '<div class="dropdown d-inline-block">';
                        html += '<a href="<?= $this->siteUrl() ?>/admin/deposits/view-deposit/'+ deposit.depositId +'" class="btn btn-soft-secondary btn-sm dropdown">Details</a>';
                        html += '</div>';
                        html += '</td>';
                        html += '</tr>';
                    }

                    // Append the new content to the container
                    container.find(".deposits").append(html);

                    // Update the page number
                    page++;

                    // If there is no more content to load, hide the load more button
                    if (deposits.length === 0) {
                        loadMoreCompletedDeposits.hide();
                        lastPageDiv.removeClass("d-none");
                    } else {
                        loadMoreCompletedDeposits.text("Load More"); // Restore the button text
                        loadMoreCompletedDeposits.data("page", page); // Update the data-page attribute
                    }
                },
                error: function() {
                    // Handle the error case
                    loadMoreCompletedDeposits.text("Load More"); // Restore the button text
                },
            });
        });

        // Custom function to retrieve gateways by ID
        function getGatewayById(method_code) {
            const gatewaysMap = {};
            const gateways = <?= json_encode($data['gateways']) ?>; // Retrieve the gateway array from PHP

            // Build a map of gateways keyed by their method_code
            gateways.forEach(gateway => {
                gatewaysMap[gateway.method_code] = gateway;
            });

            // Return the gateway if found, otherwise return null
            return gatewaysMap[method_code] || null;
        }

        function formatCurrency(currency, amount) {
            // Parse the amount as float to ensure it's treated as a number
            amount = parseFloat(amount);

            switch(currency) {
                case '€':
                    return '€' + amount.toFixed(2);
                case '£':
                    return '£' + amount.toFixed(2);
                case '$':
                    return '$' + amount.toFixed(2);
                default:
                    return currency + ' ' + amount.toFixed(2);
            }
        }

        function formatDate(createdAt){

            // Convert the createdAt string to a Date object
            const createdAtDate = new Date(createdAt);

            // Format the date components
            const day = createdAtDate.getDate().toString().padStart(2, '0');
            const month = (createdAtDate.getMonth() + 1).toString().padStart(2, '0'); // Months are zero-indexed, so add 1
            const year = createdAtDate.getFullYear();
            let hours = createdAtDate.getHours().toString().padStart(2, '0');
            const minutes = createdAtDate.getMinutes().toString().padStart(2, '0');
            const meridiem = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12; // Handle midnight (0 hours)

            // Construct the formatted date string
            return day + '-' + month + '-' + year + ' ' + hours + ':' + minutes + ' ' + meridiem;
        }
    });
</script>

<!-- Load More Initiated Deposit -->
<script>
    $(document).ready(function() {
        let page = 2; // Initial page number
        const container = $("#loadMoreInitiatedDepositsContainer");
        const loadMoreInitiatedDeposits = $(".loadMoreInitiatedDeposits");
        const lastPageDiv = $("#InitiatedDepositsLastpage"); // Get the reference to the last page div

        loadMoreInitiatedDeposits.click(function(e) {
            e.preventDefault();

            const currentPage = $(this).data("page");

            loadMoreInitiatedDeposits.html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Loading...</span>');

            // Send an AJAX request to fetch more content
            $.ajax({
                url: "<?= $this->siteUrl() ?>/admin/deposits_initiated", // Update with the correct URL
                type: "GET",
                data: {
                    page: currentPage 
                },
                success: function(response) {
                    const deposits = response.deposits;

                    // Construct the HTML markup for each deposit item
                    let html = '';
                    for (let i = 0; i < deposits.length; i++) {
                        const deposit = deposits[i];

                        // Retrieve the associated plan information
                        const gateway = getGatewayById(deposit.method_code);

                        html += '<tr>';
                        html += '<td>';
                        html += '<div class="d-flex align-items-center">';

                        if (gateway) {
                            html += '<img src="<?= $this->siteUrl() ?>/<?= PUBLIC_PATH ?>/<?= UPLOADS_PATH ?>/deposit/' + gateway.image + '" alt="" class="avatar-xs rounded-circle" />';
                        }

                        html += '<div class="ms-2">';

                        if (gateway) {
                            html += '<a href="<?= $this->siteUrl() ?>/admin/deposits/view-deposit/'+ deposit.depositId +'"><h6 class="fs-15 mb-1">'+ gateway.name +'</h6></a>';
                        }

                        html += '<small class="mb-0 text-muted">'  + formatDate(deposit.created_at) + '</small>';

                        const currency = '$';
                        html += '</div></div></td>';

                        html += '<td>'+formatCurrency(currency, deposit.amount)+'</td>';

                        if (deposit.status == "0") {
                            html += '<td>Initiated</td>';
                        } else if (deposit.status == "1") {
                            html += '<td class="text-success">Completed</td>';
                        } else if (deposit.status == "2") {
                            html += '<td class="text-warning">Pending</td>';
                        } else if (deposit.status == "3") {
                            html += '<td class="text-danger">Rejected</td>';
                        }

                        html += '<td>';
                        html += '<div class="dropdown d-inline-block">';
                        html += '<a href="<?= $this->siteUrl() ?>/admin/deposits/view-deposit/'+ deposit.depositId +'" class="btn btn-soft-secondary btn-sm dropdown">Details</a>';
                        html += '</div>';
                        html += '</td>';
                        html += '</tr>';
                    }

                    // Append the new content to the container
                    container.find(".deposits").append(html);

                    // Update the page number
                    page++;

                    // If there is no more content to load, hide the load more button
                    if (deposits.length === 0) {
                        loadMoreInitiatedDeposits.hide();
                        lastPageDiv.removeClass("d-none");
                    } else {
                        loadMoreInitiatedDeposits.text("Load More"); // Restore the button text
                        loadMoreInitiatedDeposits.data("page", page); // Update the data-page attribute
                    }
                },
                error: function() {
                    // Handle the error case
                    loadMoreInitiatedDeposits.text("Load More"); // Restore the button text
                },
            });
        });

        // Custom function to retrieve gateways by ID
        function getGatewayById(method_code) {
            const gatewaysMap = {};
            const gateways = <?= json_encode($data['gateways']) ?>; // Retrieve the gateway array from PHP

            // Build a map of gateways keyed by their method_code
            gateways.forEach(gateway => {
                gatewaysMap[gateway.method_code] = gateway;
            });

            // Return the gateway if found, otherwise return null
            return gatewaysMap[method_code] || null;
        }

        function formatCurrency(currency, amount) {
            // Parse the amount as float to ensure it's treated as a number
            amount = parseFloat(amount);

            switch(currency) {
                case '€':
                    return '€' + amount.toFixed(2);
                case '£':
                    return '£' + amount.toFixed(2);
                case '$':
                    return '$' + amount.toFixed(2);
                default:
                    return currency + ' ' + amount.toFixed(2);
            }
        }

        function formatDate(createdAt){

            // Convert the createdAt string to a Date object
            const createdAtDate = new Date(createdAt);

            // Format the date components
            const day = createdAtDate.getDate().toString().padStart(2, '0');
            const month = (createdAtDate.getMonth() + 1).toString().padStart(2, '0'); // Months are zero-indexed, so add 1
            const year = createdAtDate.getFullYear();
            let hours = createdAtDate.getHours().toString().padStart(2, '0');
            const minutes = createdAtDate.getMinutes().toString().padStart(2, '0');
            const meridiem = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12; // Handle midnight (0 hours)

            // Construct the formatted date string
            return day + '-' + month + '-' + year + ' ' + hours + ':' + minutes + ' ' + meridiem;
        }
    });
</script>

<!-- Load More Rejected Deposit -->
<script>
    $(document).ready(function() {
        let page = 2; // Initial page number
        const container = $("#loadMoreRejectedDepositsContainer");
        const loadMoreRejectedDeposits = $(".loadMoreRejectedDeposits");
        const lastPageDiv = $("#RejectedDepositsLastpage"); // Get the reference to the last page div

        loadMoreRejectedDeposits.click(function(e) {
            e.preventDefault();

            const currentPage = $(this).data("page");

            loadMoreRejectedDeposits.html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Loading...</span>');

            // Send an AJAX request to fetch more content
            $.ajax({
                url: "<?= $this->siteUrl() ?>/admin/deposits_rejected", // Update with the correct URL
                type: "GET",
                data: {
                    page: currentPage 
                },
                success: function(response) {
                    const deposits = response.deposits;

                    // Construct the HTML markup for each deposit item
                    let html = '';
                    for (let i = 0; i < deposits.length; i++) {
                        const deposit = deposits[i];

                        // Retrieve the associated plan information
                        const gateway = getGatewayById(deposit.method_code);

                        html += '<tr>';
                        html += '<td>';
                        html += '<div class="d-flex align-items-center">';

                        if (gateway) {
                            html += '<img src="<?= $this->siteUrl() ?>/<?= PUBLIC_PATH ?>/<?= UPLOADS_PATH ?>/deposit/' + gateway.image + '" alt="" class="avatar-xs rounded-circle" />';
                        }

                        html += '<div class="ms-2">';

                        if (gateway) {
                            html += '<a href="<?= $this->siteUrl() ?>/admin/deposits/view-deposit/'+ deposit.depositId +'"><h6 class="fs-15 mb-1">'+ gateway.name +'</h6></a>';
                        }

                        html += '<small class="mb-0 text-muted">'  + formatDate(deposit.created_at) + '</small>';

                        const currency = '$';
                        html += '</div></div></td>';

                        html += '<td>'+formatCurrency(currency, deposit.amount)+'</td>';

                        if (deposit.status == "0") {
                            html += '<td>Initiated</td>';
                        } else if (deposit.status == "1") {
                            html += '<td class="text-success">Completed</td>';
                        } else if (deposit.status == "2") {
                            html += '<td class="text-warning">Pending</td>';
                        } else if (deposit.status == "3") {
                            html += '<td class="text-danger">Rejected</td>';
                        }

                        html += '<td>';
                        html += '<div class="dropdown d-inline-block">';
                        html += '<a href="<?= $this->siteUrl() ?>/admin/deposits/view-deposit/'+ deposit.depositId +'" class="btn btn-soft-secondary btn-sm dropdown">Details</a>';
                        html += '</div>';
                        html += '</td>';
                        html += '</tr>';
                    }

                    // Append the new content to the container
                    container.find(".deposits").append(html);

                    // Update the page number
                    page++;

                    // If there is no more content to load, hide the load more button
                    if (deposits.length === 0) {
                        loadMoreRejectedDeposits.hide();
                        lastPageDiv.removeClass("d-none");
                    } else {
                        loadMoreRejectedDeposits.text("Load More"); // Restore the button text
                        loadMoreRejectedDeposits.data("page", page); // Update the data-page attribute
                    }
                },
                error: function() {
                    // Handle the error case
                    loadMoreRejectedDeposits.text("Load More"); // Restore the button text
                },
            });
        });

        // Custom function to retrieve gateways by ID
        function getGatewayById(method_code) {
            const gatewaysMap = {};
            const gateways = <?= json_encode($data['gateways']) ?>; // Retrieve the gateway array from PHP

            // Build a map of gateways keyed by their method_code
            gateways.forEach(gateway => {
                gatewaysMap[gateway.method_code] = gateway;
            });

            // Return the gateway if found, otherwise return null
            return gatewaysMap[method_code] || null;
        }

        function formatCurrency(currency, amount) {
            // Parse the amount as float to ensure it's treated as a number
            amount = parseFloat(amount);

            switch(currency) {
                case '€':
                    return '€' + amount.toFixed(2);
                case '£':
                    return '£' + amount.toFixed(2);
                case '$':
                    return '$' + amount.toFixed(2);
                default:
                    return currency + ' ' + amount.toFixed(2);
            }
        }

        function formatDate(createdAt){

            // Convert the createdAt string to a Date object
            const createdAtDate = new Date(createdAt);

            // Format the date components
            const day = createdAtDate.getDate().toString().padStart(2, '0');
            const month = (createdAtDate.getMonth() + 1).toString().padStart(2, '0'); // Months are zero-indexed, so add 1
            const year = createdAtDate.getFullYear();
            let hours = createdAtDate.getHours().toString().padStart(2, '0');
            const minutes = createdAtDate.getMinutes().toString().padStart(2, '0');
            const meridiem = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12; // Handle midnight (0 hours)

            // Construct the formatted date string
            return day + '-' + month + '-' + year + ' ' + hours + ':' + minutes + ' ' + meridiem;
        }
    });
</script>

<!-- Approve Deposits -->
<script>
    // Event listener for approving address proof
    $('#approveDepositBtn').on('click', function () {
        
        // Retrieving IDs from data attributes
        const depositIdValue = $(this).data("approve");

        // Checking if IDs are set
        if (!depositIdValue) {
            console.error("ID is not set in the data attribute.");
            return;
        }

        // Adding spinner and disabling button
        $("#approveDepositBtn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
        $("#approveDepositBtn").prop("disabled", true);

        // Making AJAX request to approve deposit
        $.ajax({
            type: 'GET',
            url: '<?=$this->siteUrl()?>/admin/deposits/view-deposit/' + depositIdValue,
            data: { 
                approve: depositIdValue 
            },
            success: function(response) {
                // Handling response based on status
                if (response.status === "success") {
                    // Displaying a success message
                    iziToast.success({
                        message: response.message,
                        position: 'topRight'
                    });

                    // Reloading page after a delay
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else if (response.status === "error") {
                    // Displaying error message
                    iziToast.error({
                        message: response.message,
                        position: 'topRight'
                    });
                } else if (response.status === "warning") {
                    // Displaying a warning message
                    iziToast.error({
                        message: response.message,
                        position: 'topRight'
                    });

                    // Reloading page after a delay
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
            },
            error: function(xhr) {
                // Handling errors
                console.log(xhr.responseText);
                iziToast.error({
                    message: "An error occurred. Please try again.",
                    position: "topRight",
                });
            },
            complete: function () {
                // Removing spinner and enabling button
                $("#approveDepositBtn").html("Yes, Approve");
                $("#approveDepositBtn").prop("disabled", false);
            },
        });
    });
</script>

<!-- Reject Deposits -->
<script>
    // Event listener for approving address proof
    $('#rejectDepositBtn').on('click', function () {
        
        // Retrieving IDs from data attributes
        const depositIdValue = $(this).data("reject");

        // Checking if IDs are set
        if (!depositIdValue) {
            console.error("ID is not set in the data attribute.");
            return;
        }

        // Adding spinner and disabling button
        $("#rejectDepositBtn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
        $("#rejectDepositBtn").prop("disabled", true);

        // Making AJAX request to reject deposits
        $.ajax({
            type: 'GET',
            url: '<?=$this->siteUrl()?>/admin/deposits/view-deposit/' + depositIdValue,
            data: { 
                reject: depositIdValue 
            },
            success: function(response) {
                // Handling response based on status
                if (response.status === "success") {
                    // Displaying a success message
                    iziToast.success({
                        message: response.message,
                        position: 'topRight'
                    });

                    // Reloading page after a delay
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else if (response.status === "error") {
                    // Displaying error message
                    iziToast.error({
                        message: response.message,
                        position: 'topRight'
                    });
                } else if (response.status === "warning") {
                    // Displaying a warning message
                    iziToast.error({
                        message: response.message,
                        position: 'topRight'
                    });

                    // Reloading page after a delay
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
            },
            error: function(xhr) {
                // Handling errors
                console.log(xhr.responseText);
                iziToast.error({
                    message: "An error occurred. Please try again.",
                    position: "topRight",
                });
            },
            complete: function () {
                // Removing spinner and enabling button
                $("#rejectDepositBtn").html("Yes, Reject");
                $("#rejectDepositBtn").prop("disabled", false);
            },
        });
    });
</script>

<!-- Update Referral Settings Ajax -->
<script>
    $(document).ready(function () {
        // Handler for the referralBtn button click event
        $("#referralBtn").click(function (event) {
            event.preventDefault(); // Prevent form submission

            // Validate inputs
            let valid = true;

            // percent validation
            const percentValue = $("#percent").val().trim();
            if (percentValue === "") {
                $("#percent").addClass("is-invalid");
                valid = false;
            } else {
                $("#percent").removeClass("is-invalid");
            }

            if (!valid) {
                return;
            }

            // Add the spinner to the button
            $("#referralBtn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
            $("#referralBtn").prop("disabled", true); // Disable the button

            // Send an Ajax request to the password controller
            $.ajax({
                url: "<?=$this->siteUrl()?>/admin/referrals", // Modify the URL according to your setup
                type: 'POST',
                data: $('#referral-form').serialize(),
                dataType: 'json',
                success: function (response) {
                    // Check the response status
                    if (response.status === "success") {
                        // Show a success message
                        iziToast.success({
                            message: response.message,
                            position: 'topRight'
                        });

                        // Reloading page after a delay
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    } else if (response.status === "error") {
                        // The password change failed, show an iziToast error notification
                        iziToast.error({
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
                    $("#referralBtn").html("Submit Form");
                    $("#referralBtn").prop("disabled", false);
                },
            });
        });

        // Add event listener for input fields to remove 'is-invalid' class on input
        $("#percent").on("input", function () {
            $(this).removeClass("is-invalid");
        });
    });
</script>

<!-- Add Time Settings Ajax -->
<script>
    $(document).ready(function () {
        // Handler for the addTimeBtn button click event
        $("#addTimeBtn").click(function (event) {
            event.preventDefault(); // Prevent form submission

            // Validate inputs
            let valid = true;

            // add-name validation
            const addnameValue = $("#add-name").val().trim();
            if (addnameValue === "") {
                $("#add-name").addClass("is-invalid");
                valid = false;
            } else {
                $("#add-name").removeClass("is-invalid");
            }

            // add-hours validation
            const addhoursValue = $("#add-hours").val().trim();
            if (addhoursValue === "") {
                $("#add-hours").addClass("is-invalid");
                valid = false;
            } else {
                $("#add-hours").removeClass("is-invalid");
            }

            if (!valid) {
                return;
            }

            // Add the spinner to the button
            $("#addTimeBtn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
            $("#addTimeBtn").prop("disabled", true); // Disable the button

            // Send an Ajax request to the password controller
            $.ajax({
                url: "<?=$this->siteUrl()?>/admin/time", // Modify the URL according to your setup
                type: 'POST',
                data: $('#add-time-form').serialize(),
                dataType: 'json',
                success: function (response) {
                    // Check the response status
                    if (response.status === "success") {
                        // Show a success message
                        iziToast.success({
                            message: response.message,
                            position: 'topRight'
                        });

                        // Reloading page after a delay
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    } else if (response.status === "error") {
                        // The password change failed, show an iziToast error notification
                        iziToast.error({
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
                    $("#addTimeBtn").html("Submit Form");
                    $("#addTimeBtn").prop("disabled", false);
                },
            });
        });

        // Add event listener for input fields to remove 'is-invalid' class on input
        $("#add-name, #add-hours").on("input", function () {
            $(this).removeClass("is-invalid");
        });
    });
</script>

<!-- Edit Time Settings Ajax -->
<script>
    $(document).ready(function () {
        // Handler for the editTimeBtn button click event
        $("#editTimeBtn").click(function (event) {
            event.preventDefault(); // Prevent form submission

            // Validate inputs
            let valid = true;

            // edit-name validation
            const editnameValue = $("#edit-name").val().trim();
            if (editnameValue === "") {
                $("#edit-name").addClass("is-invalid");
                valid = false;
            } else {
                $("#edit-name").removeClass("is-invalid");
            }

            // edit-hours validation
            const edithoursValue = $("#edit-hours").val().trim();
            if (edithoursValue === "") {
                $("#edit-hours").addClass("is-invalid");
                valid = false;
            } else {
                $("#edit-hours").removeClass("is-invalid");
            }

            if (!valid) {
                return;
            }

            // Add the spinner to the button
            $("#editTimeBtn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
            $("#editTimeBtn").prop("disabled", true); // Disable the button

            // Send an Ajax request to the password controller
            $.ajax({
                url: "<?=$this->siteUrl()?>/admin/time/edit-time", // Modify the URL according to your setup
                type: 'POST',
                data: $('#edit-time-form').serialize(),
                dataType: 'json',
                success: function (response) {
                    // Check the response status
                    if (response.status === "success") {
                        // Show a success message
                        iziToast.success({
                            message: response.message,
                            position: 'topRight'
                        });

                        // Reloading page after a delay
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    } else if (response.status === "error") {
                        // The password change failed, show an iziToast error notification
                        iziToast.error({
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
                    $("#editTimeBtn").html("Submit Form");
                    $("#editTimeBtn").prop("disabled", false);
                },
            });
        });

        // Add event listener for input fields to remove 'is-invalid' class on input
        $("#edit-name, #edit-hours").on("input", function () {
            $(this).removeClass("is-invalid");
        });
    });
</script>

<!-- Add Plan Ajax -->
<script>
    $(document).ready(function () {
        // Handler for the addPlanBtn button click event
        $("#addPlanBtn").click(function (event) {
            event.preventDefault(); // Prevent form submission

            const investTypeSelect = $("#invest_type");
            const returnTypeSelect = $("#return_type");

            // Validate inputs
            let valid = true;

            // name validation
            const nameValue = $("#name").val().trim();
            if (nameValue === "") {
                $("#name").addClass("is-invalid");
                valid = false;
            } else {
                $("#name").removeClass("is-invalid");
            }

            // Check an invested type
            if (investTypeSelect.val() === "1") {
                // minimum validation
                const minimumValue = $("#minimum").val().trim();
                if (minimumValue === "") {
                    $("#minimum").addClass("is-invalid");
                    valid = false;
                } else {
                    $("#minimum").removeClass("is-invalid");
                }

                // maximum validation
                const maximumValue = $("#maximum").val().trim();
                if (maximumValue === "") {
                    $("#maximum").addClass("is-invalid");
                    valid = false;
                } else {
                    $("#maximum").removeClass("is-invalid");
                }
            } else if (investTypeSelect.val() === "2") {
                // fixed_amount validation
                const fixedAmountValue = $("#fixed_amount").val().trim();
                if (fixedAmountValue === "") {
                    $("#fixed_amount").addClass("is-invalid");
                    valid = false;
                } else {
                    $("#fixed_amount").removeClass("is-invalid");
                }
            }

            // interest validation
            const interestValue = $("#interest").val().trim();
            if (interestValue === "") {
                $("#interest").addClass("is-invalid");
                valid = false;
            } else {
                $("#interest").removeClass("is-invalid");
            }

            // Check a return type
            if (returnTypeSelect.val() === "1") {
                // repeat_time validation
                const repeatTimeValue = $("#repeat_time").val().trim();
                if (repeatTimeValue === "") {
                    $("#repeat_time").addClass("is-invalid");
                    valid = false;
                } else {
                    $("#repeat_time").removeClass("is-invalid");
                }
            }

            if (!valid) {
                return;
            }

            // Add the spinner to the button
            $("#addPlanBtn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
            $("#addPlanBtn").prop("disabled", true); // Disable the button

            // Send an Ajax request to the password controller
            $.ajax({
                url: "<?=$this->siteUrl()?>/admin/plans", // Modify the URL according to your setup
                type: 'POST',
                data: $('#add-plan-form').serialize(),
                dataType: 'json',
                success: function (response) {
                    // Check the response status
                    if (response.status === "success") {
                        // Show a success message
                        iziToast.success({
                            message: response.message,
                            position: 'topRight'
                        });

                        // Reloading page after a delay
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    } else if (response.status === "error") {
                        // The password change failed, show an iziToast error notification
                        iziToast.error({
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
                    $("#addPlanBtn").html("Submit Form");
                    $("#addPlanBtn").prop("disabled", false);
                },
            });
        });

        // Add event listener for input fields to remove 'is-invalid' class on input
        $("#name, #minimum, #maximum, #fixed_amount, #interest, #repeat_time").on("input", function () {
            $(this).removeClass("is-invalid");
        });
    });
</script>

<!-- Edit Plan Ajax -->
<script>
    $(document).ready(function () {
        // Handler for the editPlanBtn button click event
        $("#editPlanBtn").click(function (event) {
            event.preventDefault(); // Prevent form submission

            const planIdValue = $(this).data("id");

            // Checking if IDs are set
            if (!planIdValue) {
                console.error("ID is not set in the data attribute.");
                return;
            }

            const investTypeSelect = $("#invest_type");
            const returnTypeSelect = $("#return_type");

            // Validate inputs
            let valid = true;

            // name validation
            const nameValue = $("#name").val().trim();
            if (nameValue === "") {
                $("#name").addClass("is-invalid");
                valid = false;
            } else {
                $("#name").removeClass("is-invalid");
            }

            // Check an invested type
            if (investTypeSelect.val() === "1") {
                // minimum validation
                const minimumValue = $("#minimum").val().trim();
                if (minimumValue === "") {
                    $("#minimum").addClass("is-invalid");
                    valid = false;
                } else {
                    $("#minimum").removeClass("is-invalid");
                }

                // maximum validation
                const maximumValue = $("#maximum").val().trim();
                if (maximumValue === "") {
                    $("#maximum").addClass("is-invalid");
                    valid = false;
                } else {
                    $("#maximum").removeClass("is-invalid");
                }
            } else if (investTypeSelect.val() === "2") {
                // fixed_amount validation
                const fixedAmountValue = $("#fixed_amount").val().trim();
                if (fixedAmountValue === "") {
                    $("#fixed_amount").addClass("is-invalid");
                    valid = false;
                } else {
                    $("#fixed_amount").removeClass("is-invalid");
                }
            }

            // interest validation
            const interestValue = $("#interest").val().trim();
            if (interestValue === "") {
                $("#interest").addClass("is-invalid");
                valid = false;
            } else {
                $("#interest").removeClass("is-invalid");
            }

            // Check a return type
            if (returnTypeSelect.val() === "1") {
                // repeat_time validation
                const repeatTimeValue = $("#repeat_time").val().trim();
                if (repeatTimeValue === "") {
                    $("#repeat_time").addClass("is-invalid");
                    valid = false;
                } else {
                    $("#repeat_time").removeClass("is-invalid");
                }
            }

            if (!valid) {
                return;
            }

            // Add the spinner to the button
            $("#editPlanBtn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
            $("#editPlanBtn").prop("disabled", true); // Disable the button

            // Send an Ajax request to the password controller
            $.ajax({
                url: "<?=$this->siteUrl()?>/admin/plans/edit-plan/" + planIdValue, // Modify the URL according to your setup
                type: 'POST',
                data: $('#edit-plan-form').serialize(),
                dataType: 'json',
                success: function (response) {
                    // Check the response status
                    if (response.status === "success") {
                        // Show a success message
                        iziToast.success({
                            message: response.message,
                            position: 'topRight'
                        });

                        // Reloading page after a delay
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    } else if (response.status === "error") {
                        // The password change failed, show an iziToast error notification
                        iziToast.error({
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
                    $("#editPlanBtn").html("Submit Form");
                    $("#editPlanBtn").prop("disabled", false);
                },
            });
        });

        // Add event listener for input fields to remove 'is-invalid' class on input
        $("#name, #minimum, #maximum, #fixed_amount, #interest, #repeat_time").on("input", function () {
            $(this).removeClass("is-invalid");
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Handle Approve Modal
        document.addEventListener('click', function (event) {
            if (event.target.matches('[data-bs-target="#approveModal"]')) {
                const loanId = event.target.dataset.loanId; // Use dataset to access data-loan-id
                const loanIdInput = document.getElementById('loanId');

                if (loanIdInput) {
                    loanIdInput.value = loanId; // Set the value of the hidden input
                }
            }

            // Handle Reject Modal
            if (event.target.matches('[data-bs-target="#rejectModal"]')) {
                const loanId = event.target.dataset.loanId; // Use dataset to access data-loan-id
                const rejectLoanIdInput = document.getElementById('rejectLoanId');

                if (rejectLoanIdInput) {
                    rejectLoanIdInput.value = loanId; // Set the value of the hidden input
                }
            }
        });
    });
</script>

<!-- Approve Deposits -->
<script>
    // Event listener for approving address proof
    $('#approveLoanBtn').on('click', function () {
        
        // Adding spinner and disabling button
        $("#approveLoanBtn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
        $("#approveLoanBtn").prop("disabled", true);

        // Making AJAX request to approve deposit
        $.ajax({
            url: '<?=$this->siteUrl()?>/admin/loans/approve-loans',
            type: 'POST',
            data: $('#approve-loan-form').serialize(),
            dataType: 'json',
            success: function(response) {
                // Handling response based on status
                if (response.status === "success") {
                    // Displaying a success message
                    iziToast.success({
                        message: response.message,
                        position: 'topRight'
                    });

                    // Reloading page after a delay
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else if (response.status === "error") {
                    // Displaying error message
                    iziToast.error({
                        message: response.message,
                        position: 'topRight'
                    });
                } else if (response.status === "warning") {
                    // Displaying a warning message
                    iziToast.error({
                        message: response.message,
                        position: 'topRight'
                    });

                    // Reloading page after a delay
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
            },
            error: function(xhr) {
                // Handling errors
                console.log(xhr.responseText);
                iziToast.error({
                    message: "An error occurred. Please try again.",
                    position: "topRight",
                });
            },
            complete: function () {
                // Removing spinner and enabling button
                $("#approveLoanBtn").html("Yes, Approve");
                $("#approveLoanBtn").prop("disabled", false);
            },
        });
    });
</script>

<!-- Reject Deposits -->
<script>
    // Event listener for approving address proof
    $('#rejectLoanBtn').on('click', function () {
        
        // Adding spinner and disabling button
        $("#rejectLoanBtn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
        $("#rejectLoanBtn").prop("disabled", true);

        // Making AJAX request to reject deposit
        $.ajax({
            url: '<?=$this->siteUrl()?>/admin/loans/reject-loans',
            type: 'POST',
            data: $('#reject-loan-form').serialize(),
            dataType: 'json',
            success: function(response) {
                // Handling response based on status
                if (response.status === "success") {
                    // Displaying a success message
                    iziToast.success({
                        message: response.message,
                        position: 'topRight'
                    });

                    // Reloading page after a delay
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else if (response.status === "error") {
                    // Displaying error message
                    iziToast.error({
                        message: response.message,
                        position: 'topRight'
                    });
                } else if (response.status === "warning") {
                    // Displaying a warning message
                    iziToast.error({
                        message: response.message,
                        position: 'topRight'
                    });

                    // Reloading page after a delay
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
            },
            error: function(xhr) {
                // Handling errors
                console.log(xhr.responseText);
                iziToast.error({
                    message: "An error occurred. Please try again.",
                    position: "topRight",
                });
            },
            complete: function () {
                // Removing spinner and enabling button
                $("#rejectLoanBtn").html("Yes, Reject");
                $("#rejectLoanBtn").prop("disabled", false);
            },
        });
    });
</script>