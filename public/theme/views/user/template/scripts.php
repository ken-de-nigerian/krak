<?php
defined('FIR') OR exit();
/**
 * The template for displaying the footer section
 */
?>

<!-- Quill Editor -->
<script>
    // Check if the necessary elements exist before executing the script
    const editorElement = document.getElementById('editor');
    const descriptionElement = document.getElementById('description');

    if (editorElement && descriptionElement) {
        const quill = new Quill(editorElement, {
            theme: 'snow'
        });

        // Clear the content of the editor
        quill.root.innerHTML = '';

        const description = descriptionElement;

        // Set the initial value for the hidden input field
        description.value = '';

        // Capture the HTML content from the Quill editor and assign it to the hidden input field
        quill.on('text-change', function () {
            description.value = quill.root.innerHTML;
        });
    }
</script>

<!-- Load Deposits -->
<script>
    $(document).ready(function() {
        let page = 2; // Initial page number
        const container = $("#loadMoreDepositsContainer");
        const loadMoreDeposits = $(".loadMoreDeposits");
        const lastPageDiv = $("#DepositsLastpage"); // Get the reference to the last page div

        loadMoreDeposits.click(function(e) {
            e.preventDefault();
            const currentPage = $(this).data("page");
            loadMoreDeposits.html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Loading...</span>');

            // Send an AJAX request to fetch more content
            $.ajax({
                url: "<?=$this->siteUrl()?>/user/deposits", // Update with the correct URL
                type: "GET",
                data: { page: currentPage },
                success: function(response) {
                    const deposits = response.deposits;

                    // Construct the HTML markup for each deposit item
                    let html = '';
                    for (let i = 0; i < deposits.length; i++) {
                        const deposit = deposits[i];

                        // Retrieve the associated plan information
                        const gateway = getGatewayById(deposit.method_code);

                        html += '<div class="transac-parent cursor-pointer">';
                        html += '<div class="d-flex justify-content-between transac-child">';
                        html += '<div class="d-flex w-50">';
                        html += '<div class="deposit-circle d-flex justify-content-center align-items-center">';

                        if (gateway) {
                            html += '<img src="<?= $this->siteUrl() ?>/<?= PUBLIC_PATH ?>/<?= UPLOADS_PATH ?>/deposit/' + gateway.image + '" alt=""/>';
                        }

                        html += '</div>';
                        html += '<div class="ml-20 r-ml-8">';

                        if (gateway) {
                            html += '<p class="mb-0 text-dark f-16 gilroy-medium theme-tran">' + gateway.name + "</p>";
                        }

                        html += '<div class="d-flex flex-wrap">';
                        html += '<p class="mb-0 text-gray-100 f-13 leading-17 gilroy-regular tran-title mt-2">Date</p>';
                        html += '<p class="mb-0 text-gray-100 f-13 leading-17 gilroy-regular tran-title mt-2 d-flex justify-content-center align-items-center">';
                        html += '<svg class="mx-2 text-muted-100" width="4" height="4" viewBox="0 0 4 4" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="2" cy="2" r="2" fill="currentColor" /></svg>'  + formatDate(deposit.created_at);
                        html += '</p>';

                        html += '</div>';
                        html += '</div>';
                        html += '</div>';

                        html += '<div class="d-flex justify-content-center align-items-center">';
                        html += '<div>';
                        const currency = '<?= e($data['user']['currency']) ?>';
                        html += '<p class="mb-0 gilroy-medium text-gray-100 r-f-12 f-16 ph-20">' + formatCurrency(currency, deposit.amount);
                        html += '</p>';

                        if (deposit.status == "0") {
                            html += '<p class="text-dark f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">Initiated</p>';
                        }else if (deposit.status == "1") {
                            html += '<p class="text-success f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">Completed</p>';
                        }else if (deposit.status == "2") {
                            html += '<p class="text-warning f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">Pending</p>';
                        }else if (deposit.status == "3") {
                            html += '<p class="text-danger f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">Rejected</p>';
                        }

                        html += '</div>';
                        html += '<div class="cursor-pointer transaction-arrow ml-28 r-ml-12">';
                        html += '<a href="' + (deposit.status == 0 ? '<?=$this->siteUrl()?>/user/confirm/deposit/' + deposit.depositId + '/' + deposit.method_code : 'javascript:void(0)') + '" class="arrow-hovers">';
                        html += '<svg class="nscaleX-1" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M3.5312 1.52861C3.27085 1.78896 3.27085 2.21107 3.5312 2.47141L7.0598 6.00001L3.5312 9.52861C3.27085 9.78895 3.27085 10.2111 3.5312 10.4714C3.79155 10.7318 4.21366 10.7318 4.47401 10.4714L8.47401 6.47141C8.73436 6.21106 8.73436 5.78895 8.47401 5.52861L4.47401 1.52861C4.21366 1.26826 3.79155 1.26826 3.5312 1.52861Z" fill="currentColor"/></svg>';

                        html += '</a>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                    }

                    // Append the new content to the container
                    container.find(".list-group").append(html);

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
                    return '€ ' + amount.toFixed(2);
                case '£':
                    return '£ ' + amount.toFixed(2);
                case '$':
                    return '$ ' + amount.toFixed(2);
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

<!-- Load InitiatedDeposits -->
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
                url: "<?=$this->siteUrl()?>/payment/initiated", // Update with the correct URL
                type: "GET",
                data: { page: currentPage },
                success: function(response) {
                    const deposits = response.deposits;

                    // Construct the HTML markup for each deposit item
                    let html = '';
                    for (let i = 0; i < deposits.length; i++) {
                        const deposit = deposits[i];

                        // Retrieve the associated plan information
                        const gateway = getGatewayById(deposit.method_code);

                        html += '<div class="transac-parent cursor-pointer">';
                        html += '<div class="d-flex justify-content-between transac-child">';
                        html += '<div class="d-flex w-50">';
                        html += '<div class="deposit-circle d-flex justify-content-center align-items-center">';

                        if (gateway) {
                            html += '<img src="<?= $this->siteUrl() ?>/<?= PUBLIC_PATH ?>/<?= UPLOADS_PATH ?>/deposit/' + gateway.image + '" alt=""/>';
                        }

                        html += '</div>';
                        html += '<div class="ml-20 r-ml-8">';

                        if (gateway) {
                            html += '<p class="mb-0 text-dark f-16 gilroy-medium theme-tran">' + gateway.name + "</p>";
                        }

                        html += '<div class="d-flex flex-wrap">';
                        html += '<p class="mb-0 text-gray-100 f-13 leading-17 gilroy-regular tran-title mt-2">Date</p>';
                        html += '<p class="mb-0 text-gray-100 f-13 leading-17 gilroy-regular tran-title mt-2 d-flex justify-content-center align-items-center">';
                        html += '<svg class="mx-2 text-muted-100" width="4" height="4" viewBox="0 0 4 4" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="2" cy="2" r="2" fill="currentColor" /></svg>'  + formatDate(deposit.created_at);
                        html += '</p>';

                        html += '</div>';
                        html += '</div>';
                        html += '</div>';

                        html += '<div class="d-flex justify-content-center align-items-center">';
                        html += '<div>';
                        const currency = '<?= e($data['user']['currency']) ?>';
                        html += '<p class="mb-0 gilroy-medium text-gray-100 r-f-12 f-16 ph-20">' + formatCurrency(currency, deposit.amount);
                        html += '</p>';

                        if (deposit.status == "0") {
                            html += '<p class="text-dark f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">Initiated</p>';
                        }else if (deposit.status == "1") {
                            html += '<p class="text-success f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">Completed</p>';
                        }else if (deposit.status == "2") {
                            html += '<p class="text-warning f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">Pending</p>';
                        }else if (deposit.status == "3") {
                            html += '<p class="text-danger f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">Rejected</p>';
                        }

                        html += '</div>';
                        html += '<div class="cursor-pointer transaction-arrow ml-28 r-ml-12">';
                        html += '<a href="' + (deposit.status == 0 ? '<?=$this->siteUrl()?>/user/confirm/deposit/' + deposit.depositId + '/' + deposit.method_code : 'javascript:void(0)') + '" class="arrow-hovers">';
                        html += '<svg class="nscaleX-1" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M3.5312 1.52861C3.27085 1.78896 3.27085 2.21107 3.5312 2.47141L7.0598 6.00001L3.5312 9.52861C3.27085 9.78895 3.27085 10.2111 3.5312 10.4714C3.79155 10.7318 4.21366 10.7318 4.47401 10.4714L8.47401 6.47141C8.73436 6.21106 8.73436 5.78895 8.47401 5.52861L4.47401 1.52861C4.21366 1.26826 3.79155 1.26826 3.5312 1.52861Z" fill="currentColor"/></svg>';

                        html += '</a>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                    }

                    // Append the new content to the container
                    container.find(".list-group").append(html);

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
                    return '€ ' + amount.toFixed(2);
                case '£':
                    return '£ ' + amount.toFixed(2);
                case '$':
                    return '$ ' + amount.toFixed(2);
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

<!-- Load PendingDeposits -->
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
                url: "<?=$this->siteUrl()?>/payment/pending", // Update with the correct URL
                type: "GET",
                data: { page: currentPage },
                success: function(response) {
                    const deposits = response.deposits;

                    // Construct the HTML markup for each deposit item
                    let html = '';
                    for (let i = 0; i < deposits.length; i++) {
                        const deposit = deposits[i];

                        // Retrieve the associated plan information
                        const gateway = getGatewayById(deposit.method_code);

                        html += '<div class="transac-parent cursor-pointer">';
                        html += '<div class="d-flex justify-content-between transac-child">';
                        html += '<div class="d-flex w-50">';
                        html += '<div class="deposit-circle d-flex justify-content-center align-items-center">';

                        if (gateway) {
                            html += '<img src="<?= $this->siteUrl() ?>/<?= PUBLIC_PATH ?>/<?= UPLOADS_PATH ?>/deposit/' + gateway.image + '" alt=""/>';
                        }

                        html += '</div>';
                        html += '<div class="ml-20 r-ml-8">';

                        if (gateway) {
                            html += '<p class="mb-0 text-dark f-16 gilroy-medium theme-tran">' + gateway.name + "</p>";
                        }

                        html += '<div class="d-flex flex-wrap">';
                        html += '<p class="mb-0 text-gray-100 f-13 leading-17 gilroy-regular tran-title mt-2">Date</p>';
                        html += '<p class="mb-0 text-gray-100 f-13 leading-17 gilroy-regular tran-title mt-2 d-flex justify-content-center align-items-center">';
                        html += '<svg class="mx-2 text-muted-100" width="4" height="4" viewBox="0 0 4 4" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="2" cy="2" r="2" fill="currentColor" /></svg>'  + formatDate(deposit.created_at);
                        html += '</p>';

                        html += '</div>';
                        html += '</div>';
                        html += '</div>';

                        html += '<div class="d-flex justify-content-center align-items-center">';
                        html += '<div>';
                        const currency = '<?= e($data['user']['currency']) ?>';
                        html += '<p class="mb-0 gilroy-medium text-gray-100 r-f-12 f-16 ph-20">' + formatCurrency(currency, deposit.amount);
                        html += '</p>';

                        if (deposit.status == "0") {
                            html += '<p class="text-dark f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">Initiated</p>';
                        }else if (deposit.status == "1") {
                            html += '<p class="text-success f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">Completed</p>';
                        }else if (deposit.status == "2") {
                            html += '<p class="text-warning f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">Pending</p>';
                        }else if (deposit.status == "3") {
                            html += '<p class="text-danger f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">Rejected</p>';
                        }

                        html += '</div>';
                        html += '<div class="cursor-pointer transaction-arrow ml-28 r-ml-12">';
                        html += '<a href="' + (deposit.status == 0 ? '<?=$this->siteUrl()?>/user/confirm/deposit/' + deposit.depositId + '/' + deposit.method_code : 'javascript:void(0)') + '" class="arrow-hovers">';
                        html += '<svg class="nscaleX-1" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M3.5312 1.52861C3.27085 1.78896 3.27085 2.21107 3.5312 2.47141L7.0598 6.00001L3.5312 9.52861C3.27085 9.78895 3.27085 10.2111 3.5312 10.4714C3.79155 10.7318 4.21366 10.7318 4.47401 10.4714L8.47401 6.47141C8.73436 6.21106 8.73436 5.78895 8.47401 5.52861L4.47401 1.52861C4.21366 1.26826 3.79155 1.26826 3.5312 1.52861Z" fill="currentColor"/></svg>';

                        html += '</a>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                    }

                    // Append the new content to the container
                    container.find(".list-group").append(html);

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
                    return '€ ' + amount.toFixed(2);
                case '£':
                    return '£ ' + amount.toFixed(2);
                case '$':
                    return '$ ' + amount.toFixed(2);
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

<!-- Load CompletedDeposits -->
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
                url: "<?=$this->siteUrl()?>/payment/completed", // Update with the correct URL
                type: "GET",
                data: { page: currentPage },
                success: function(response) {
                    const deposits = response.deposits;

                    // Construct the HTML markup for each deposit item
                    let html = '';
                    for (let i = 0; i < deposits.length; i++) {
                        const deposit = deposits[i];

                        // Retrieve the associated plan information
                        const gateway = getGatewayById(deposit.method_code);

                        html += '<div class="transac-parent cursor-pointer">';
                        html += '<div class="d-flex justify-content-between transac-child">';
                        html += '<div class="d-flex w-50">';
                        html += '<div class="deposit-circle d-flex justify-content-center align-items-center">';

                        if (gateway) {
                            html += '<img src="<?= $this->siteUrl() ?>/<?= PUBLIC_PATH ?>/<?= UPLOADS_PATH ?>/deposit/' + gateway.image + '" alt=""/>';
                        }

                        html += '</div>';
                        html += '<div class="ml-20 r-ml-8">';

                        if (gateway) {
                            html += '<p class="mb-0 text-dark f-16 gilroy-medium theme-tran">' + gateway.name + "</p>";
                        }

                        html += '<div class="d-flex flex-wrap">';
                        html += '<p class="mb-0 text-gray-100 f-13 leading-17 gilroy-regular tran-title mt-2">Date</p>';
                        html += '<p class="mb-0 text-gray-100 f-13 leading-17 gilroy-regular tran-title mt-2 d-flex justify-content-center align-items-center">';
                        html += '<svg class="mx-2 text-muted-100" width="4" height="4" viewBox="0 0 4 4" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="2" cy="2" r="2" fill="currentColor" /></svg>'  + formatDate(deposit.created_at);
                        html += '</p>';

                        html += '</div>';
                        html += '</div>';
                        html += '</div>';

                        html += '<div class="d-flex justify-content-center align-items-center">';
                        html += '<div>';
                        const currency = '<?= e($data['user']['currency']) ?>';
                        html += '<p class="mb-0 gilroy-medium text-gray-100 r-f-12 f-16 ph-20">' + formatCurrency(currency, deposit.amount);
                        html += '</p>';

                        if (deposit.status == "0") {
                            html += '<p class="text-dark f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">Initiated</p>';
                        }else if (deposit.status == "1") {
                            html += '<p class="text-success f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">Completed</p>';
                        }else if (deposit.status == "2") {
                            html += '<p class="text-warning f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">Pending</p>';
                        }else if (deposit.status == "3") {
                            html += '<p class="text-danger f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">Rejected</p>';
                        }

                        html += '</div>';
                        html += '<div class="cursor-pointer transaction-arrow ml-28 r-ml-12">';
                        html += '<a href="' + (deposit.status == 0 ? '<?=$this->siteUrl()?>/user/confirm/deposit/' + deposit.depositId + '/' + deposit.method_code : 'javascript:void(0)') + '" class="arrow-hovers">';
                        html += '<svg class="nscaleX-1" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M3.5312 1.52861C3.27085 1.78896 3.27085 2.21107 3.5312 2.47141L7.0598 6.00001L3.5312 9.52861C3.27085 9.78895 3.27085 10.2111 3.5312 10.4714C3.79155 10.7318 4.21366 10.7318 4.47401 10.4714L8.47401 6.47141C8.73436 6.21106 8.73436 5.78895 8.47401 5.52861L4.47401 1.52861C4.21366 1.26826 3.79155 1.26826 3.5312 1.52861Z" fill="currentColor"/></svg>';

                        html += '</a>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                    }

                    // Append the new content to the container
                    container.find(".list-group").append(html);

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
                    return '€ ' + amount.toFixed(2);
                case '£':
                    return '£ ' + amount.toFixed(2);
                case '$':
                    return '$ ' + amount.toFixed(2);
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

<!-- Load CancelledDeposits -->
<script>
    $(document).ready(function() {
        let page = 2; // Initial page number
        const container = $("#loadMoreCancelledDepositsContainer");
        const loadMoreCancelledDeposits = $(".loadMoreCancelledDeposits");
        const lastPageDiv = $("#CancelledDepositsLastpage"); // Get the reference to the last page div

        loadMoreCancelledDeposits.click(function(e) {
            e.preventDefault();
            const currentPage = $(this).data("page");
            loadMoreCancelledDeposits.html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Loading...</span>');

            // Send an AJAX request to fetch more content
            $.ajax({
                url: "<?=$this->siteUrl()?>/payment/cancelled", // Update with the correct URL
                type: "GET",
                data: { page: currentPage },
                success: function(response) {
                    const deposits = response.deposits;

                    // Construct the HTML markup for each deposit item
                    let html = '';
                    for (let i = 0; i < deposits.length; i++) {
                        const deposit = deposits[i];

                        // Retrieve the associated plan information
                        const gateway = getGatewayById(deposit.method_code);

                        html += '<div class="transac-parent cursor-pointer">';
                        html += '<div class="d-flex justify-content-between transac-child">';
                        html += '<div class="d-flex w-50">';
                        html += '<div class="deposit-circle d-flex justify-content-center align-items-center">';

                        if (gateway) {
                            html += '<img src="<?= $this->siteUrl() ?>/<?= PUBLIC_PATH ?>/<?= UPLOADS_PATH ?>/deposit/' + gateway.image + '" alt=""/>';
                        }

                        html += '</div>';
                        html += '<div class="ml-20 r-ml-8">';

                        if (gateway) {
                            html += '<p class="mb-0 text-dark f-16 gilroy-medium theme-tran">' + gateway.name + "</p>";
                        }

                        html += '<div class="d-flex flex-wrap">';
                        html += '<p class="mb-0 text-gray-100 f-13 leading-17 gilroy-regular tran-title mt-2">Date</p>';
                        html += '<p class="mb-0 text-gray-100 f-13 leading-17 gilroy-regular tran-title mt-2 d-flex justify-content-center align-items-center">';
                        html += '<svg class="mx-2 text-muted-100" width="4" height="4" viewBox="0 0 4 4" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="2" cy="2" r="2" fill="currentColor" /></svg>'  + formatDate(deposit.created_at);
                        html += '</p>';

                        html += '</div>';
                        html += '</div>';
                        html += '</div>';

                        html += '<div class="d-flex justify-content-center align-items-center">';
                        html += '<div>';
                        const currency = '<?= e($data['user']['currency']) ?>';
                        html += '<p class="mb-0 gilroy-medium text-gray-100 r-f-12 f-16 ph-20">' + formatCurrency(currency, deposit.amount);
                        html += '</p>';

                        if (deposit.status == "0") {
                            html += '<p class="text-dark f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">Initiated</p>';
                        }else if (deposit.status == "1") {
                            html += '<p class="text-success f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">Completed</p>';
                        }else if (deposit.status == "2") {
                            html += '<p class="text-warning f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">Pending</p>';
                        }else if (deposit.status == "3") {
                            html += '<p class="text-danger f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">Rejected</p>';
                        }

                        html += '</div>';
                        html += '<div class="cursor-pointer transaction-arrow ml-28 r-ml-12">';
                        html += '<a href="' + (deposit.status == 0 ? '<?=$this->siteUrl()?>/user/confirm/deposit/' + deposit.depositId + '/' + deposit.method_code : 'javascript:void(0)') + '" class="arrow-hovers">';
                        html += '<svg class="nscaleX-1" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M3.5312 1.52861C3.27085 1.78896 3.27085 2.21107 3.5312 2.47141L7.0598 6.00001L3.5312 9.52861C3.27085 9.78895 3.27085 10.2111 3.5312 10.4714C3.79155 10.7318 4.21366 10.7318 4.47401 10.4714L8.47401 6.47141C8.73436 6.21106 8.73436 5.78895 8.47401 5.52861L4.47401 1.52861C4.21366 1.26826 3.79155 1.26826 3.5312 1.52861Z" fill="currentColor"/></svg>';

                        html += '</a>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                    }

                    // Append the new content to the container
                    container.find(".list-group").append(html);

                    // Update the page number
                    page++;

                    // If there is no more content to load, hide the load more button
                    if (deposits.length === 0) {
                        loadMoreCancelledDeposits.hide();
                        lastPageDiv.removeClass("d-none");
                    } else {
                        loadMoreCancelledDeposits.text("Load More"); // Restore the button text
                        loadMoreCancelledDeposits.data("page", page); // Update the data-page attribute
                    }
                },
                error: function() {
                    // Handle the error case
                    loadMoreCancelledDeposits.text("Load More"); // Restore the button text
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
                    return '€ ' + amount.toFixed(2);
                case '£':
                    return '£ ' + amount.toFixed(2);
                case '$':
                    return '$ ' + amount.toFixed(2);
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

<!-- Load Investments -->
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
                url: "<?=$this->siteUrl()?>/user/investments", // Update with the correct URL
                type: "GET",
                data: { page: currentPage },
                success: function(response) {
                    const investments = response.investments;

                    // Construct the HTML markup for each investment item
                    let html = '';
                    for (let i = 0; i < investments.length; i++) {
                        const investment = investments[i];

                        // Retrieve the associated plan information
                        const plan = getPlanById(investment.planId);

                        html += '<div class="transac-parent cursor-pointer">';
                        html += '<div class="d-flex justify-content-between transac-child">';
                        html += '<div class="d-flex w-50">';
                        html += '<div class="deposit-circle d-flex justify-content-center align-items-center">';
                        html += '<img src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/frontend/templates/images/investment.png" alt="Transaction" />';
                        html += '</div>';
                        html += '<div class="ml-20 r-ml-8">';

                        if (plan) {
                            html += '<p class="mb-0 text-dark f-16 gilroy-medium theme-tran">' + plan.name + "</p>";
                        }

                        html += '<div class="d-flex flex-wrap">';
                        html += '<p class="mb-0 text-gray-100 f-13 leading-17 gilroy-regular tran-title mt-2">Date</p>';
                        html += '<p class="mb-0 text-gray-100 f-13 leading-17 gilroy-regular tran-title mt-2 d-flex justify-content-center align-items-center">';
                        html += '<svg class="mx-2 text-muted-100" width="4" height="4" viewBox="0 0 4 4" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="2" cy="2" r="2" fill="currentColor" /></svg>'  + formatDate(investment.initiated_at);
                        html += '</p>';

                        html += '</div>';
                        html += '</div>';
                        html += '</div>';

                        html += '<div class="d-flex justify-content-center align-items-center">';
                        html += '<div>';
                        const currency = '<?= e($data['user']['currency']) ?>';
                        html += '<p class="mb-0 gilroy-medium text-gray-100 r-f-12 f-16 ph-20">' + formatCurrency(currency, investment.amount);
                        html += '</p>';

                        if (investment.status == "1") {
                            html += '<p class="text-success f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">Completed</p>';
                        }else if (investment.status == "2") {
                            html += '<p class="text-warning f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">Running</p>';
                        }else if (investment.status == "3") {
                            html += '<p class="text-dark f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">Initiated</p>';
                        }else if (investment.status == "4") {
                            html += '<p class="text-danger f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">Rejected</p>';
                        }

                        html += '</div>';
                        html += '<div class="cursor-pointer transaction-arrow ml-28 r-ml-12">';
                        html += '<a href="' + (investment.status == 1 || investment.status == 2 || investment.status == 4 ? '<?=$this->siteUrl()?>/user/investments/investment-details/' + investment.investId : 'javascript:void(0)') + '" class="arrow-hovers">';
                        html += '<svg class="nscaleX-1" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M3.5312 1.52861C3.27085 1.78896 3.27085 2.21107 3.5312 2.47141L7.0598 6.00001L3.5312 9.52861C3.27085 9.78895 3.27085 10.2111 3.5312 10.4714C3.79155 10.7318 4.21366 10.7318 4.47401 10.4714L8.47401 6.47141C8.73436 6.21106 8.73436 5.78895 8.47401 5.52861L4.47401 1.52861C4.21366 1.26826 3.79155 1.26826 3.5312 1.52861Z" fill="currentColor"/></svg>';

                        html += '</a>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                    }

                    // Append the new content to the container
                    container.find(".list-group").append(html);

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
                    return '€ ' + amount.toFixed(2);
                case '£':
                    return '£ ' + amount.toFixed(2);
                case '$':
                    return '$ ' + amount.toFixed(2);
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

<!-- Load PendingInvestments -->
<script>
    $(document).ready(function() {
        let page = 2; // Initial page number
        const container = $("#loadMorePendingInvestmentsContainer");
        const loadMorePendingInvestments = $(".loadMorePendingInvestments");
        const lastPageDiv = $("#PendingInvestmentsLastpage"); // Get the reference to the last page div

        loadMorePendingInvestments.click(function(e) {
            e.preventDefault();
            const currentPage = $(this).data("page");
            loadMorePendingInvestments.html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Loading...</span>');

            // Send an AJAX request to fetch more content
            $.ajax({
                url: "<?=$this->siteUrl()?>/investment/pending", // Update with the correct URL
                type: "GET",
                data: { page: currentPage },
                success: function(response) {
                    const investments = response.investments;

                    // Construct the HTML markup for each investment item
                    let html = '';
                    for (let i = 0; i < investments.length; i++) {
                        const investment = investments[i];

                        // Retrieve the associated plan information
                        const plan = getPlanById(investment.planId);

                        html += '<div class="transac-parent cursor-pointer">';
                        html += '<div class="d-flex justify-content-between transac-child">';
                        html += '<div class="d-flex w-50">';
                        html += '<div class="deposit-circle d-flex justify-content-center align-items-center">';
                        html += '<img src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/frontend/templates/images/investment.png" alt="Transaction" />';
                        html += '</div>';
                        html += '<div class="ml-20 r-ml-8">';

                        if (plan) {
                            html += '<p class="mb-0 text-dark f-16 gilroy-medium theme-tran">' + plan.name + "</p>";
                        }

                        html += '<div class="d-flex flex-wrap">';
                        html += '<p class="mb-0 text-gray-100 f-13 leading-17 gilroy-regular tran-title mt-2">Date</p>';
                        html += '<p class="mb-0 text-gray-100 f-13 leading-17 gilroy-regular tran-title mt-2 d-flex justify-content-center align-items-center">';
                        html += '<svg class="mx-2 text-muted-100" width="4" height="4" viewBox="0 0 4 4" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="2" cy="2" r="2" fill="currentColor" /></svg>'  + formatDate(investment.initiated_at);
                        html += '</p>';

                        html += '</div>';
                        html += '</div>';
                        html += '</div>';

                        html += '<div class="d-flex justify-content-center align-items-center">';
                        html += '<div>';
                        const currency = '<?= e($data['user']['currency']) ?>';
                        html += '<p class="mb-0 gilroy-medium text-gray-100 r-f-12 f-16 ph-20">' + formatCurrency(currency, investment.amount);
                        html += '</p>';

                        if (investment.status == "1") {
                            html += '<p class="text-success f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">Completed</p>';
                        }else if (investment.status == "2") {
                            html += '<p class="text-warning f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">Running</p>';
                        }else if (investment.status == "3") {
                            html += '<p class="text-dark f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">Initiated</p>';
                        }else if (investment.status == "4") {
                            html += '<p class="text-danger f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">Rejected</p>';
                        }

                        html += '</div>';
                        html += '<div class="cursor-pointer transaction-arrow ml-28 r-ml-12">';
                        html += '<a href="' + (investment.status == 1 || investment.status == 2 || investment.status == 4 ? '<?=$this->siteUrl()?>/user/investments/investment-details/' + investment.investId : 'javascript:void(0)') + '" class="arrow-hovers">';
                        html += '<svg class="nscaleX-1" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M3.5312 1.52861C3.27085 1.78896 3.27085 2.21107 3.5312 2.47141L7.0598 6.00001L3.5312 9.52861C3.27085 9.78895 3.27085 10.2111 3.5312 10.4714C3.79155 10.7318 4.21366 10.7318 4.47401 10.4714L8.47401 6.47141C8.73436 6.21106 8.73436 5.78895 8.47401 5.52861L4.47401 1.52861C4.21366 1.26826 3.79155 1.26826 3.5312 1.52861Z" fill="currentColor"/></svg>';

                        html += '</a>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                    }

                    // Append the new content to the container
                    container.find(".list-group").append(html);

                    // Update the page number
                    page++;

                    // If there is no more content to load, hide the load more button
                    if (investments.length === 0) {
                        loadMorePendingInvestments.hide();
                        lastPageDiv.removeClass("d-none");
                    } else {
                        loadMorePendingInvestments.text("Load More"); // Restore the button text
                        loadMorePendingInvestments.data("page", page); // Update the data-page attribute
                    }
                },
                error: function() {
                    // Handle the error case
                    loadMorePendingInvestments.text("Load More"); // Restore the button text
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
                    return '€ ' + amount.toFixed(2);
                case '£':
                    return '£ ' + amount.toFixed(2);
                case '$':
                    return '$ ' + amount.toFixed(2);
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

<!-- Load CompletedInvestments -->
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
                url: "<?=$this->siteUrl()?>/investment/completed", // Update with the correct URL
                type: "GET",
                data: { page: currentPage },
                success: function(response) {
                    const investments = response.investments;

                    // Construct the HTML markup for each investment item
                    let html = '';
                    for (let i = 0; i < investments.length; i++) {
                        const investment = investments[i];

                        // Retrieve the associated plan information
                        const plan = getPlanById(investment.planId);

                        html += '<div class="transac-parent cursor-pointer">';
                        html += '<div class="d-flex justify-content-between transac-child">';
                        html += '<div class="d-flex w-50">';
                        html += '<div class="deposit-circle d-flex justify-content-center align-items-center">';
                        html += '<img src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/frontend/templates/images/investment.png" alt="Transaction" />';
                        html += '</div>';
                        html += '<div class="ml-20 r-ml-8">';

                        if (plan) {
                            html += '<p class="mb-0 text-dark f-16 gilroy-medium theme-tran">' + plan.name + "</p>";
                        }

                        html += '<div class="d-flex flex-wrap">';
                        html += '<p class="mb-0 text-gray-100 f-13 leading-17 gilroy-regular tran-title mt-2">Date</p>';
                        html += '<p class="mb-0 text-gray-100 f-13 leading-17 gilroy-regular tran-title mt-2 d-flex justify-content-center align-items-center">';
                        html += '<svg class="mx-2 text-muted-100" width="4" height="4" viewBox="0 0 4 4" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="2" cy="2" r="2" fill="currentColor" /></svg>'  + formatDate(investment.initiated_at);
                        html += '</p>';

                        html += '</div>';
                        html += '</div>';
                        html += '</div>';

                        html += '<div class="d-flex justify-content-center align-items-center">';
                        html += '<div>';
                        const currency = '<?= e($data['user']['currency']) ?>';
                        html += '<p class="mb-0 gilroy-medium text-gray-100 r-f-12 f-16 ph-20">' + formatCurrency(currency, investment.amount);
                        html += '</p>';

                        if (investment.status == "1") {
                            html += '<p class="text-success f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">Completed</p>';
                        }else if (investment.status == "2") {
                            html += '<p class="text-warning f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">Running</p>';
                        }else if (investment.status == "3") {
                            html += '<p class="text-dark f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">Initiated</p>';
                        }else if (investment.status == "4") {
                            html += '<p class="text-danger f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">Rejected</p>';
                        }

                        html += '</div>';
                        html += '<div class="cursor-pointer transaction-arrow ml-28 r-ml-12">';
                        html += '<a href="' + (investment.status == 1 || investment.status == 2 || investment.status == 4 ? '<?=$this->siteUrl()?>/user/investments/investment-details/' + investment.investId : 'javascript:void(0)') + '" class="arrow-hovers">';
                        html += '<svg class="nscaleX-1" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M3.5312 1.52861C3.27085 1.78896 3.27085 2.21107 3.5312 2.47141L7.0598 6.00001L3.5312 9.52861C3.27085 9.78895 3.27085 10.2111 3.5312 10.4714C3.79155 10.7318 4.21366 10.7318 4.47401 10.4714L8.47401 6.47141C8.73436 6.21106 8.73436 5.78895 8.47401 5.52861L4.47401 1.52861C4.21366 1.26826 3.79155 1.26826 3.5312 1.52861Z" fill="currentColor"/></svg>';

                        html += '</a>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                    }

                    // Append the new content to the container
                    container.find(".list-group").append(html);

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
                    return '€ ' + amount.toFixed(2);
                case '£':
                    return '£ ' + amount.toFixed(2);
                case '$':
                    return '$ ' + amount.toFixed(2);
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

<!-- Load InitiatedInvestments -->
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
                url: "<?=$this->siteUrl()?>/investment/initiated", // Update with the correct URL
                type: "GET",
                data: { page: currentPage },
                success: function(response) {
                    const investments = response.investments;

                    // Construct the HTML markup for each investment item
                    let html = '';
                    for (let i = 0; i < investments.length; i++) {
                        const investment = investments[i];

                        // Retrieve the associated plan information
                        const plan = getPlanById(investment.planId);

                        html += '<div class="transac-parent cursor-pointer">';
                        html += '<div class="d-flex justify-content-between transac-child">';
                        html += '<div class="d-flex w-50">';
                        html += '<div class="deposit-circle d-flex justify-content-center align-items-center">';
                        html += '<img src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/frontend/templates/images/investment.png" alt="Transaction" />';
                        html += '</div>';
                        html += '<div class="ml-20 r-ml-8">';

                        if (plan) {
                            html += '<p class="mb-0 text-dark f-16 gilroy-medium theme-tran">' + plan.name + "</p>";
                        }

                        html += '<div class="d-flex flex-wrap">';
                        html += '<p class="mb-0 text-gray-100 f-13 leading-17 gilroy-regular tran-title mt-2">Date</p>';
                        html += '<p class="mb-0 text-gray-100 f-13 leading-17 gilroy-regular tran-title mt-2 d-flex justify-content-center align-items-center">';
                        html += '<svg class="mx-2 text-muted-100" width="4" height="4" viewBox="0 0 4 4" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="2" cy="2" r="2" fill="currentColor" /></svg>'  + formatDate(investment.initiated_at);
                        html += '</p>';

                        html += '</div>';
                        html += '</div>';
                        html += '</div>';

                        html += '<div class="d-flex justify-content-center align-items-center">';
                        html += '<div>';
                        const currency = '<?= e($data['user']['currency']) ?>';
                        html += '<p class="mb-0 gilroy-medium text-gray-100 r-f-12 f-16 ph-20">' + formatCurrency(currency, investment.amount);
                        html += '</p>';

                        if (investment.status == "1") {
                            html += '<p class="text-success f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">Completed</p>';
                        }else if (investment.status == "2") {
                            html += '<p class="text-warning f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">Running</p>';
                        }else if (investment.status == "3") {
                            html += '<p class="text-dark f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">Initiated</p>';
                        }else if (investment.status == "4") {
                            html += '<p class="text-danger f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">Rejected</p>';
                        }

                        html += '</div>';
                        html += '<div class="cursor-pointer transaction-arrow ml-28 r-ml-12">';
                        html += '<a href="' + (investment.status == 1 || investment.status == 2 || investment.status == 4 ? '<?=$this->siteUrl()?>/user/investments/investment-details/' + investment.investId : 'javascript:void(0)') + '" class="arrow-hovers">';
                        html += '<svg class="nscaleX-1" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M3.5312 1.52861C3.27085 1.78896 3.27085 2.21107 3.5312 2.47141L7.0598 6.00001L3.5312 9.52861C3.27085 9.78895 3.27085 10.2111 3.5312 10.4714C3.79155 10.7318 4.21366 10.7318 4.47401 10.4714L8.47401 6.47141C8.73436 6.21106 8.73436 5.78895 8.47401 5.52861L4.47401 1.52861C4.21366 1.26826 3.79155 1.26826 3.5312 1.52861Z" fill="currentColor"/></svg>';

                        html += '</a>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                    }

                    // Append the new content to the container
                    container.find(".list-group").append(html);

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
                    return '€ ' + amount.toFixed(2);
                case '£':
                    return '£ ' + amount.toFixed(2);
                case '$':
                    return '$ ' + amount.toFixed(2);
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

<!-- Load CancelledInvestments -->
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
                url: "<?=$this->siteUrl()?>/investment/cancelled", // Update with the correct URL
                type: "GET",
                data: { page: currentPage },
                success: function(response) {
                    const investments = response.investments;

                    // Construct the HTML markup for each investment item
                    let html = '';
                    for (let i = 0; i < investments.length; i++) {
                        const investment = investments[i];

                        // Retrieve the associated plan information
                        const plan = getPlanById(investment.planId);

                        html += '<div class="transac-parent cursor-pointer">';
                        html += '<div class="d-flex justify-content-between transac-child">';
                        html += '<div class="d-flex w-50">';
                        html += '<div class="deposit-circle d-flex justify-content-center align-items-center">';
                        html += '<img src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/frontend/templates/images/investment.png" alt="Transaction" />';
                        html += '</div>';
                        html += '<div class="ml-20 r-ml-8">';

                        if (plan) {
                            html += '<p class="mb-0 text-dark f-16 gilroy-medium theme-tran">' + plan.name + "</p>";
                        }

                        html += '<div class="d-flex flex-wrap">';
                        html += '<p class="mb-0 text-gray-100 f-13 leading-17 gilroy-regular tran-title mt-2">Date</p>';
                        html += '<p class="mb-0 text-gray-100 f-13 leading-17 gilroy-regular tran-title mt-2 d-flex justify-content-center align-items-center">';
                        html += '<svg class="mx-2 text-muted-100" width="4" height="4" viewBox="0 0 4 4" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="2" cy="2" r="2" fill="currentColor" /></svg>'  + formatDate(investment.initiated_at);
                        html += '</p>';

                        html += '</div>';
                        html += '</div>';
                        html += '</div>';

                        html += '<div class="d-flex justify-content-center align-items-center">';
                        html += '<div>';
                        const currency = '<?= e($data['user']['currency']) ?>';
                        html += '<p class="mb-0 gilroy-medium text-gray-100 r-f-12 f-16 ph-20">' + formatCurrency(currency, investment.amount);
                        html += '</p>';

                        if (investment.status == "1") {
                            html += '<p class="text-success f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">Completed</p>';
                        }else if (investment.status == "2") {
                            html += '<p class="text-warning f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">Running</p>';
                        }else if (investment.status == "3") {
                            html += '<p class="text-dark f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">Initiated</p>';
                        }else if (investment.status == "4") {
                            html += '<p class="text-danger f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">Rejected</p>';
                        }

                        html += '</div>';
                        html += '<div class="cursor-pointer transaction-arrow ml-28 r-ml-12">';
                        html += '<a href="' + (investment.status == 1 || investment.status == 2 || investment.status == 4 ? '<?=$this->siteUrl()?>/user/investments/investment-details/' + investment.investId : 'javascript:void(0)') + '" class="arrow-hovers">';
                        html += '<svg class="nscaleX-1" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M3.5312 1.52861C3.27085 1.78896 3.27085 2.21107 3.5312 2.47141L7.0598 6.00001L3.5312 9.52861C3.27085 9.78895 3.27085 10.2111 3.5312 10.4714C3.79155 10.7318 4.21366 10.7318 4.47401 10.4714L8.47401 6.47141C8.73436 6.21106 8.73436 5.78895 8.47401 5.52861L4.47401 1.52861C4.21366 1.26826 3.79155 1.26826 3.5312 1.52861Z" fill="currentColor"/></svg>';

                        html += '</a>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                    }

                    // Append the new content to the container
                    container.find(".list-group").append(html);

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
                    return '€ ' + amount.toFixed(2);
                case '£':
                    return '£ ' + amount.toFixed(2);
                case '$':
                    return '$ ' + amount.toFixed(2);
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

<!-- Fetch Investments -->
<script>
    // Add an event listener to the select id
    $('#status').on('change', function () {
        // Get the selected status
        const selectedStatus = $(this).val();

        // Make an AJAX request to fetch routes for the selected investment status
        $.ajax({
            type: 'GET',
            url: '<?=$this->siteUrl()?>/fetch/routes',
            data: { status: selectedStatus },
            success: function (response) {
                window.location.href = '<?=$this->siteUrl()?>/' + response.redirect;
            },
            error: function () {
                // Handle errors gracefully
                iziToast.error({
                    message: "An error occurred. Please try again.",
                    position: "topRight",
                });
            }
        });
    });
</script>

<!-- Fetch Deposits -->
<script>
    // Add an event listener to the select id
    $('#deposit').on('change', function () {
        // Get the selected deposit
        const selectedStatus = $(this).val();

        // Make an AJAX request to fetch routes for the selected deposit status
        $.ajax({
            type: 'GET',
            url: '<?=$this->siteUrl()?>/fetch/deposit_routes',
            data: { deposit: selectedStatus },
            success: function (response) {
                window.location.href = '<?=$this->siteUrl()?>/' + response.redirect;
            },
            error: function () {
                // Handle errors gracefully
                iziToast.error({
                    message: "An error occurred. Please try again.",
                    position: "topRight",
                });
            }
        });
    });
</script>

<!-- Fetch Withdrawals -->
<script>
    // Add an event listener to the select id
    $('#withdrawal').on('change', function () {
        // Get the selected withdrawal
        const selectedStatus = $(this).val();

        // Make an AJAX request to fetch routes for the selected withdrawal status
        $.ajax({
            type: 'GET',
            url: '<?=$this->siteUrl()?>/fetch/withdrawal_routes',
            data: { withdrawal: selectedStatus },
            success: function (response) {
                window.location.href = '<?=$this->siteUrl()?>/' + response.redirect;
            },
            error: function () {
                // Handle errors gracefully
                iziToast.error({
                    message: "An error occurred. Please try again.",
                    position: "topRight",
                });
            }
        });
    });
</script>

<!-- Fetch Converted Amounts -->
<script>
    $(document).ready(function() {
        // Iterate over each item with class "convert"
        $('.convert').each(function() {
            const $box = $(this);
            const abbreviation = $box.find('.abbreviation').val();
            const amount = parseFloat($box.find('.amount').val()) || 0;

            // Reference the converted element using the unique ID
            const convertedElement = $box.find('.converted');
            const balanceElement = $box.find('#balance');

            convertedElement.html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span></span>');

            // AJAX request
            $.ajax({
                url: '<?=$this->siteUrl()?>/user/fetch',
                type: 'GET',
                data: {
                    abbreviation: abbreviation,
                    amount: amount
                },
                success: function (response) {
                    // Clear the "Loading..." message
                    convertedElement.text('');

                    if (response.status === 'error') {
                        // If an error occurred, display the error message
                        convertedElement.text(response.message);
                    } else {
                        // Display the formatted converted amount
                        convertedElement.text(response.converted);
                        balanceElement.val(response.converted);
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

<!-- Copy Referral & Wallet-->
<script>
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

<!-- Upload Profile Picture-->
<script>
    const $upload = $("#upload");

    function changeProfile() {
        $upload.trigger("click");
    }

    // Function to handle file selection
    $upload.change(function() {
        const file = this.files[0];
        const fileName = file.name;
        $("#file_name").val(fileName); // Set the value of hidden input to the file name
        uploadFile(file); // Call the function to upload the file via Ajax
    });

    // Function to upload file via Ajax
    function uploadFile(file) {
        const formData = new FormData();
        formData.append("photoimg", file);

        $.ajax({
            url: "<?=$this->siteUrl()?>/user/upload",
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
                    $("#file-error").text(response.message).addClass("error");
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

<!-- Phone Number Validates -->
<script>
    $(document).ready(function() {
        // Check if the phone input field exists
        const phoneInput = $("#phone");
        if (phoneInput.length > 0) {
            // Initialize the intl-tel-input plugin
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
                // Get the selected country's name
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
                const $phoneError = $("#phone-error");

                if (!isValid) {
                    // Display an error message or provide feedback to the user
                    $phoneError.text("Please enter a valid international phone number.");
                    $phoneError.addClass("error");
                } else {
                    // Clear the error message if the phone number is valid
                    $phoneError.text("");
                }
                // Enable or disable the submitted button based on the validity of the phone number
                $("#phoneBtn").prop("disabled", !isValid);
            }
        }
    });
</script>

<!-- Update Password -->
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
            $("#editPassword").html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Processing...</span>');
            $("#editPassword").prop("disabled", true); // Disable the button

            // Send an Ajax request to the password controller
            $.ajax({
                url: "<?=$this->siteUrl()?>/user/password", // Modify the URL according to your setup
                type: 'POST',
                data: $('#password-form').serialize(),
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
                        // Display an error message or provide feedback to the user
                        $("#password-error").text(response.message).addClass("error");
                    }else if (response.status === "warning") {
                        // Show a success message
                        iziToast.success({
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
                    $("#editPassword").html("Save Changes");
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

<!-- Update Currency -->
<script>
    $(document).ready(function () {
        // Handler for the currencyBtn button click event
        $("#currencyBtn").click(function (event) {
            event.preventDefault(); // Prevent form submission

            // Validate inputs
            let valid = true;

            // currency validation
            const currencyValue = $("#currency").val().trim();
            if (currencyValue === "") {
                $("#currency").addClass("is-invalid");
                valid = false;
            } else {
                $("#currency").removeClass("is-invalid");
            }

            if (!valid) {
                return;
            }

            // Add the spinner to the button
            $("#currencyBtn").html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Processing...</span>');
            $("#currencyBtn").prop("disabled", true); // Disable the button

            // Send an Ajax request to the password controller
            $.ajax({
                url: "<?=$this->siteUrl()?>/user/currency", // Modify the URL according to your setup
                type: 'POST',
                data: $('#currency-form').serialize(),
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
                    $("#currencyBtn").html("Save Changes");
                    $("#currencyBtn").prop("disabled", false);
                },
            });
        });
    });
</script>

<!-- Update Phone -->
<script>
    $(document).ready(function () {
        // Handler for the phoneBtn button click event
        $("#phoneBtn").click(function (event) {
            event.preventDefault(); // Prevent form submission

            // Validate inputs
            let valid = true;

            // phone validation
            const phoneValue = $("#phone").val().trim();
            if (phoneValue === "") {
                $("#phone").addClass("is-invalid");
                valid = false;
            } else {
                $("#phone").removeClass("is-invalid");
            }

            if (!valid) {
                return;
            }

            // Add the spinner to the button
            $("#phoneBtn").html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Processing...</span>');
            $("#phoneBtn").prop("disabled", true); // Disable the button

            // Send an Ajax request to the password controller
            $.ajax({
                url: "<?=$this->siteUrl()?>/user/phone", // Modify the URL according to your setup
                type: 'POST',
                data: $('#phone-form').serialize(),
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
                        // Display an error message or provide feedback to the user
                        $("#phone-error").text(response.message).addClass("error");
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
                    $("#phoneBtn").html("Add phone");
                    $("#phoneBtn").prop("disabled", false);
                },
            });
        });
    });
</script>

<!-- Update 2-factor Authentication Method -->
<script>
    $(document).ready(function () {
        // Handler for the 2faBtn button click event
        $("#2faBtn").click(function (event) {
            event.preventDefault(); // Prevent form submission

            // Validate inputs
            let valid = true;

            // twofactor_status validation
            const twoFaValue = $("#twofactor_status").val().trim();
            if (twoFaValue === "") {
                $("#twofactor_status").addClass("is-invalid");
                valid = false;
            } else {
                $("#twofactor_status").removeClass("is-invalid");
            }

            if (!valid) {
                return;
            }

            // Add the spinner to the button
            $("#2faBtn").html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Processing...</span>');
            $("#2faBtn").prop("disabled", true); // Disable the button

            // Send an Ajax request to the password controller
            $.ajax({
                url: "<?=$this->siteUrl()?>/user/verifications/two-factor", // Modify the URL according to your setup
                type: 'POST',
                data: $('#2fa-form').serialize(),
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
                        // Display an error message or provide feedback to the user
                        $("#2fa-error").text(response.message).addClass("error");
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
                    $("#2faBtn").html("Submit");
                    $("#2faBtn").prop("disabled", false);
                },
            });
        });
    });
</script>

<!-- Update Profile -->
<script>
    $(document).ready(function () {
        // Handler for the profileBtn button click event
        $("#profileBtn").click(function (event) {
            event.preventDefault(); // Prevent form submission

            // Validate inputs
            let valid = true;

            // First name validation
            const firstNameValue = $("#firstname").val().trim();
            if (firstNameValue === "") {
                $("#firstname").addClass("is-invalid");
                valid = false;
            } else {
                $("#firstname").removeClass("is-invalid");
            }

            // Last name validation
            const lastNameValue = $("#lastname").val().trim();
            if (lastNameValue === "") {
                $("#lastname").addClass("is-invalid");
                valid = false;
            } else {
                $("#lastname").removeClass("is-invalid");
            }

            // Address 1 validation
            const address_1Value = $("#address_1").val().trim();
            if (address_1Value === "") {
                $("#address_1").addClass("is-invalid");
                valid = false;
            } else {
                $("#address_1").removeClass("is-invalid");
            }

            // Country validation
            const countryValue = $("#country").val();
            if (countryValue === "") {
                $("#country").addClass("is-invalid");
                valid = false;
            } else {
                $("#country").removeClass("is-invalid");
            }

            // City validation
            const cityValue = $("#city").val().trim();
            if (cityValue === "") {
                $("#city").addClass("is-invalid");
                valid = false;
            } else {
                $("#city").removeClass("is-invalid");
            }

            // State validation
            const stateValue = $("#state").val().trim();
            if (stateValue === "") {
                $("#state").addClass("is-invalid");
                valid = false;
            } else {
                $("#state").removeClass("is-invalid");
            }

            // timezone validation
            const timezoneValue = $("#timezone").val().trim();
            if (timezoneValue === "") {
                $("#timezone").addClass("is-invalid");
                valid = false;
            } else {
                $("#timezone").removeClass("is-invalid");
            }

            if (!valid) {
                return;
            }

            // Add the spinner to the button
            $("#profileBtn").html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Processing...</span>');
            $("#profileBtn").prop("disabled", true); 

            // Send an Ajax request to the edit controller
            $.ajax({
                url: "<?=$this->siteUrl()?>/user/profile", // Modify the URL according to your edit
                type: 'POST',
                data: $('#profile-form').serialize(),
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
                    $("#profileBtn").html("Save Changes");
                    $("#profileBtn").prop("disabled", false);
                },
            });
        });

        // Add event listener for input fields to remove 'is-invalid' class on input
        $("#firstname, #lastname, #address_1, #address_2, #country, #city, #state, #timezone").on("input", function () {
            $(this).removeClass("is-invalid");
        });
    });
</script>

<!-- Add Personal-ID -->
<script>
    $(document).ready(function () {
        // Handler for the edit button click event
        $("#identityBtn").click(function (event) {
            event.preventDefault(); // Prevent form submission

            // Validate inputs
            let valid = true;

            // identity_type validation
            const identity_typeValue = $("#identity_type").val().trim();
            if (identity_typeValue === "") {
                $("#identity_type").addClass("is-invalid");
                valid = false;
            } else {
                $("#identity_type").removeClass("is-invalid");
            }

            // identity_number validation
            const identity_numberValue = $("#identity_number").val().trim();
            if (identity_numberValue === "") {
                $("#identity_number").addClass("is-invalid");
                valid = false;
            } else {
                $("#identity_number").removeClass("is-invalid");
            }

            if (!valid) {
                return;
            }

            // Create a FormData object
            const formData = new FormData($('#identitiy-form')[0]);

            // Add the selected file to the FormData object
            const fileInput = $("#hidden-input")[0];
            if (fileInput.files.length > 0) {
                formData.append('photoimg', fileInput.files[0]);
            }

            // Send an Ajax request to the edit controller
            $.ajax({
                url: "<?=$this->siteUrl()?>/user/verifications/personal-id", // Modify the URL according to your edit
                type: "POST",
                data: formData,
                dataType: "json",
                contentType: false,
                processData: false,
                beforeSend: function () {
                    // Add the spinner to the button
                    $("#identityBtn").html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Processing...</span>');
                    $("#identityBtn").prop("disabled", true); // Disable the button
                },
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
                        // Display an error message or provide feedback to the user
                        $("#identity-error").text(response.message).addClass("error");
                        $("#identity-error").removeClass("d-none");
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
                    $("#identityBtn").html("Verify Identity");
                    $("#identityBtn").prop("disabled", false);
                },
            });
        });
    });
</script>

<!-- Add Personal-Address -->
<script>
    $(document).ready(function () {
        // Handler for the edit button click event
        $("#addressBtn").click(function (event) {
            event.preventDefault(); // Prevent form submission

            // Create a FormData object
            const formData = new FormData($('#address-form')[0]);

            // Add the selected file to the FormData object
            const fileInput = $("#hidden-input")[0];
            if (fileInput.files.length > 0) {
                formData.append('photoimg', fileInput.files[0]);
            }

            // Send an Ajax request to the edit controller
            $.ajax({
                url: "<?=$this->siteUrl()?>/user/verifications/personal-address", // Modify the URL according to your edit
                type: "POST",
                data: formData,
                dataType: "json",
                contentType: false,
                processData: false,
                beforeSend: function () {
                    // Add the spinner to the button
                    $("#addressBtn").html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Processing...</span>');
                    $("#addressBtn").prop("disabled", true); // Disable the button
                },
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
                        // Display an error message or provide feedback to the user
                        $("#address-error").text(response.message).addClass("error");
                        $("#address-error").removeClass("d-none");
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
                    $("#addressBtn").html("Verify Identity");
                    $("#addressBtn").prop("disabled", false);
                },
            });
        });
    });
</script>

<!-- Deposit Method -->
<script>
    $(document).ready(function () {
        // Handler for the depositBtn button click event
        $("#depositBtn").click(function (event) {
            event.preventDefault(); // Prevent form submission

            // Validate inputs
            let valid = true;

            // deposit-amount validation
            const depositAmountValue = $("#deposit-amount").val().trim();
            if (depositAmountValue === "") {
                $("#deposit-amount").addClass("is-invalid");
                valid = false;
            } else {
                $("#deposit-amount").removeClass("is-invalid");
            }

            if (!valid) {
                return;
            }

            // Add the spinner to the button
            $("#depositBtn").html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Processing...</span>');
            $("#depositBtn").prop("disabled", true); // Disable the button

            // Send an Ajax request to the deposit controller
            $.ajax({
                url: "<?=$this->siteUrl()?>/user/deposit", // Modify the URL according to your setup
                type: 'POST',
                data: $('#deposit-form').serialize(),
                dataType: 'json',
                success: function (response) {
                    // Check the response status
                    if (response.status === "success") {
                        window.location.href = '<?=$this->siteUrl()?>/' + response.redirect;
                    } else if (response.status === "error") {
                        // Show an error message
                        iziToast.error({
                            message: response.message,
                            position: 'topRight'
                        });
                    } else if (response.status === "warning") {
                        // Display an error message or provide feedback to the user
                        $("#deposit-error").text(response.message).addClass("error");
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
                    $("#depositBtn").html("Proceed");
                    $("#depositBtn").prop("disabled", false);
                },
            });
        });

        // Add event listener for input fields to remove 'is-invalid' class on input
        $("#deposit-amount").on("input", function () {
            $(this).removeClass("is-invalid");
        });
    });
</script>

<!-- Confirm Deposit -->
<script>
    $(document).ready(function () {
        // Handler for the confirmBtn button click event
        $("#confirmBtn").click(function (event) {
            event.preventDefault(); // Prevent form submission

            // Retrieve the deposit ID from the data attribute
            const depositValue = $(this).data("deposit");

            // Retrieve the method ID from the data attribute
            const methodValue = $(this).data("method");

            // Validate inputs
            let valid = true;

            // payment method validation
            const balanceValue = $("#balance").val().trim();
            if (balanceValue === "") {
                $("#balance").addClass("is-invalid");
                valid = false;
            } else {
                $("#balance").removeClass("is-invalid");
            }

            if (!valid) {
                return;
            }

            // Create a FormData object
            const formData = new FormData($('#confirm-form')[0]);

            // Add the selected file to the FormData object
            const fileInput = $("#hidden-input")[0];
            if (fileInput && fileInput.files && fileInput.files.length > 0) {
                formData.append('photoimg', fileInput.files[0]);
            } else {
                // Append an empty file object to formData
                formData.append('photoimg', new Blob(), '');
            }
            
            // Add the spinner to the button
            $("#confirmBtn").html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Processing...</span>');
            $("#confirmBtn").prop("disabled", true); // Disable the button

            // Send an Ajax request to the confirmation controller
            $.ajax({
                url: "<?=$this->siteUrl()?>/user/confirm/deposit/" + depositValue + '/' + methodValue, // Modify the URL according to your setup
                type: "POST",
                data: formData,
                dataType: "json",
                contentType: false,
                processData: false,
                success: function (response) {
                    // Check the response status
                    if (response.status === "success") {
                        window.location.href = '<?=$this->siteUrl()?>/' + response.redirect;
                    } else if (response.status === "error") {
                        // Show a success message
                        iziToast.error({
                            message: response.message,
                            position: 'topRight'
                        });

                        // Redirect after a delay (optional)
                        setTimeout(function () {
                            window.location.href = '<?=$this->siteUrl()?>/' + response.redirect;
                        }, 3000); // Redirect after 3 seconds
                    } else if (response.status === "warning") {
                        // Display an error message or provide feedback to the user
                        $("#file-error").text(response.message).addClass("error");
                        $("#file-error").removeClass("d-none");
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
                    $("#confirmBtn").html("Confirm &amp; Deposit");
                    $("#confirmBtn").prop("disabled", false);
                },
            });
        });
    });
</script>

<!-- Withdraw Method -->
<script>
    $(document).ready(function () {
        // Handler for the withdrawalBtn button click event
        $("#withdrawalBtn").click(function (event) {
            event.preventDefault(); // Prevent form submission

            // Validate inputs
            let valid = true;

            // withdraw-amount validation
            const withdrawAmountValue = $("#withdraw-amount").val().trim();
            if (withdrawAmountValue === "") {
                $("#withdraw-amount").addClass("is-invalid");
                valid = false;
            } else {
                $("#withdraw-amount").removeClass("is-invalid");
            }

            if (!valid) {
                return;
            }

            // Add the spinner to the button
            $("#withdrawalBtn").html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Processing...</span>');
            $("#withdrawalBtn").prop("disabled", true); // Disable the button

            // Send an Ajax request to the withdrawal controller
            $.ajax({
                url: "<?=$this->siteUrl()?>/user/payout", // Modify the URL according to your setup
                type: 'POST',
                data: $('#withdraw-form').serialize(),
                dataType: 'json',
                success: function (response) {
                    // Check the response status
                    if (response.status === "success") {
                        window.location.href = '<?=$this->siteUrl()?>/' + response.redirect;
                    } else if (response.status === "error") {
                        // Check the error message to determine the type of error
                        if (response.message.includes('KYC')) {
                            // Show an iziToast error notification
                            iziToast.error({
                                message: response.message,
                                position: 'topRight'
                            });

                            // Redirect after a delay (optional)
                            setTimeout(function () {
                                window.location.href = '<?=$this->siteUrl()?>/' + response.redirect;
                            }, 3000); // Redirect after 3 seconds
                        } else {
                            // Show an iziToast error notification
                            iziToast.error({
                                message: response.message,
                                position: 'topRight'
                            });
                        }
                    } else if (response.status === "warning") {
                        // Display an error message or provide feedback to the user
                        $("#withdraw-error").text(response.message).addClass("error");
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
                    $("#withdrawalBtn").html("Proceed");
                    $("#withdrawalBtn").prop("disabled", false);
                },
            });
        });

        // Add event listener for input fields to remove 'is-invalid' class on input
        $("#withdraw-amount").on("input", function () {
            $(this).removeClass("is-invalid");
        });
    });
</script>

<!-- Confirm Withdrawal -->
<script>
    $(document).ready(function () {
        // Handler for the payoutBtn button click event
        $("#payoutBtn").click(function (event) {
            event.preventDefault(); // Prevent form submission

            // Retrieve the withdrawal ID from the data attribute
            const withdrawValue = $(this).data("withdraw");

            // Retrieve the method ID from the data attribute
            const methodValue = $(this).data("method");

            // Validate inputs
            let valid = true;

            // crypto address validation
            const cryptoAddressValue = $("#crypto_address").val().trim();
            if (cryptoAddressValue === "") {
                $("#crypto_address").addClass("is-invalid");
                valid = false;
            } else {
                $("#crypto_address").removeClass("is-invalid");
            }

            if (!valid) {
                return;
            }

            // Add the spinner to the button
            $("#payoutBtn").html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Processing...</span>');
            $("#payoutBtn").prop("disabled", true); // Disable the button

            // Send an Ajax request to the confirmation controller
            $.ajax({
                url: "<?=$this->siteUrl()?>/user/confirm/payout/" + withdrawValue + '/' + methodValue, // Modify the URL according to your setup
                type: 'POST',
                data: $('#payout-form').serialize(),
                dataType: 'json',
                success: function (response) {
                    // Check the response status
                    if (response.status === 'success') {
                        window.location.href = '<?=$this->siteUrl()?>/' + response.redirect;
                    } else if (response.status === 'error') {
                        /// Show a success message
                        iziToast.success({
                            message: response.message,
                            position: 'topRight'
                        });

                        // Redirect after a delay (optional)
                        setTimeout(function () {
                            window.location.href = '<?=$this->siteUrl()?>/' + response.redirect;
                        }, 3000); // Redirect after 3 seconds
                    } else if (response.status === 'warning') {
                        window.location.href = '<?=$this->siteUrl()?>/' + response.redirect;
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
                    $("#payoutBtn").html("Confirm &amp; Withdraw");
                    $("#payoutBtn").prop("disabled", false);
                },
            });
        });

        // Add event listener for input fields to remove 'is-invalid' class on input
        $("#crypto_address").on("input", function () {
            $(this).removeClass("is-invalid");
        });
    });
</script>

<!-- Plan -->
<script>
    $(document).ready(function() {
        // Handler for the setup button click event
        $(".investment-btn").click(function(event) {
            event.preventDefault(); // Prevent form submission

            // Get the form associated with the clicked button
            const form = $(this).closest('form');

            // Add the spinner to the button
            $(this).html('<span class=\"f-14 leading-20 gilroy-regular inv-btn text-white\"><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Processing...</span>');
            $(this).prop("disabled", true); // Disable the button

            // Send an Ajax request to the setup controller
            $.ajax({
                url: '<?=$this->siteUrl()?>/user/plans', // Modify the URL according to your setup
                type: 'POST',
                data: form.serialize(),
                dataType: 'json',
                success: function(response) {
                    // Check the response status
                    if (response.status === 'success') {
                        window.location.href = '<?=$this->siteUrl()?>/' + response.redirect;
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
                    $(".investment-btn").html("<span class=\"f-14 leading-20 gilroy-regular inv-btn text-white\">Invest Now</span>");
                    $(".investment-btn").prop("disabled", false);
                }
            });
        });
    });
</script>

<!-- Schemes -->
<script>
    $(document).ready(function() {
        // Handler for the setup button click event
        $("#schemesBtn").click(function(event) {
            event.preventDefault(); // Prevent form submission

            // Retrieve the project ID from the data attribute
            const planValue = $(this).data("plan");

            // Validate inputs
            let valid = true;

            // custom amount validation
            const customAmountValue = $("#custom-amount").val().trim();
            if (customAmountValue === "") {
                $("#custom-amount").addClass("is-invalid");
                valid = false;
            } else {
                $("#custom-amount").removeClass("is-invalid");
            }

            if (!valid) {
                return;
            }

            // Add the spinner to the button
            $("#schemesBtn").html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Processing...</span>');
            $("#schemesBtn").prop("disabled", true); // Disable the button

            // Send an Ajax request to the setup controller
            $.ajax({
                url: '<?=$this->siteUrl()?>/user/schemes/invest/' + planValue, // Modify the URL according to your setup
                type: 'POST',
                data: $('#schemes-form').serialize(),
                dataType: 'json',
                success: function(response) {
                    // Check the response status
                    if (response.status === "success") {
                        window.location.href = '<?=$this->siteUrl()?>/' + response.redirect;
                    } else if (response.status === "error") {
                        /// Show a success message
                        iziToast.error({
                            message: response.message,
                            position: 'topRight'
                        });

                        // Redirect after a delay (optional)
                        setTimeout(function () {
                            window.location.href = '<?=$this->siteUrl()?>/' + response.redirect;
                        }, 3000); // Redirect after 3 seconds
                    } else if (response.status === "warning") {
                        // Display an error message or provide feedback to the user
                        $("#custom-error").text(response.message).addClass("error");
                    }else if (response.status === "info") {
                        window.location.href = '<?=$this->siteUrl()?>/' + response.redirect;
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
                    $("#schemesBtn").html("Next");
                    $("#schemesBtn").prop("disabled", false);
                }
            });
        });

        // Add event listener for input fields to remove 'is-invalid' class on input
        $("#custom-amount").on("input", function () {
            $(this).removeClass("is-invalid");
        });
    });
</script>

<!-- Checkout -->
<script>
    $(document).ready(function() {
        // Handler for the setup button click event
        $("#checkoutBtn").click(function(event) {
            event.preventDefault(); // Prevent form submission

            // Retrieve the project ID from the data attribute
            const depositValue = $(this).data("deposit");

            // Add the spinner to the button
            $("#checkoutBtn").html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Processing...</span>');
            $("#checkoutBtn").prop("disabled", true); // Disable the button

            // Send an Ajax request to the setup controller
            $.ajax({
                url: '<?=$this->siteUrl()?>/user/checkout/deposit/' + depositValue, // Modify the URL according to your setup
                type: 'POST',
                data: $('#checkout-form').serialize(),
                dataType: 'json',
                success: function(response) {
                    // Check the response status
                    if (response.status === "success") {
                        // Redirect 
                        window.location.href = '<?=$this->siteUrl()?>/' + response.redirect;
                    } else if (response.status === "error") {
                        // Failed, show an iziToast error notification
                        iziToast.error({
                            message: response.message,
                            position: "topRight",
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
                    $("#checkoutBtn").html("Proceed");
                    $("#checkoutBtn").prop("disabled", false);
                }
            });
        });
    });
</script>

<!-- Complete-->
<script>
    $(document).ready(function () {
        // Handler for the completeBtn button click event
        $("#completeBtn").click(function (event) {
            event.preventDefault(); // Prevent form submission

            // Retrieve the deposit ID from the data attribute
            const depositValue = $(this).data("deposit");

            // Retrieve the plan ID from the data attribute
            const planValue = $(this).data("plan");

            // Retrieve the method ID from the data attribute
            const methodValue = $(this).data("method");

            // Validate inputs
            let valid = true;

            // payment method validation
            const balanceValue = $("#balance").val().trim();
            if (balanceValue === "") {
                $("#balance").addClass("is-invalid");
                valid = false;
            } else {
                $("#balance").removeClass("is-invalid");
            }

            if (!valid) {
                return;
            }

            // Create a FormData object
            const formData = new FormData($('#complete-form')[0]);

            // Add the selected file to the FormData object
            const fileInput = $("#hidden-input")[0];
            if (fileInput && fileInput.files && fileInput.files.length > 0) {
                formData.append('photoimg', fileInput.files[0]);
            } else {
                // Append an empty file object to formData
                formData.append('photoimg', new Blob(), '');
            }
            
            // Add the spinner to the button
            $("#completeBtn").html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Processing...</span>');
            $("#completeBtn").prop("disabled", true); // Disable the button

            // Send an Ajax request to the confirmation controller
            $.ajax({
                url: "<?=$this->siteUrl()?>/user/complete/deposit/" + depositValue + '/' + planValue + '/' + methodValue, // Modify the URL according to your setup
                type: "POST",
                data: formData,
                dataType: "json",
                contentType: false,
                processData: false,
                success: function (response) {
                    // Check the response status
                    if (response.status === "success") {
                        window.location.href = '<?=$this->siteUrl()?>/' + response.redirect;
                    } else if (response.status === "error") {
                        // Show a success message
                        iziToast.error({
                            message: response.message,
                            position: 'topRight'
                        });

                        // Redirect after a delay (optional)
                        setTimeout(function () {
                            window.location.href = '<?=$this->siteUrl()?>/' + response.redirect;
                        }, 3000); // Redirect after 3 seconds
                    } else if (response.status === "warning") {
                        // Display an error message or provide feedback to the user
                        $("#complete-error").text(response.message).addClass("error");
                        $("#complete-error").removeClass("d-none");
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
                    $("#completeBtn").html("Confirm &amp; Deposit");
                    $("#completeBtn").prop("disabled", false);
                },
            });
        });
    });
</script>

<!-- Load Withdrawals -->
<script>
    $(document).ready(function() {
        let page = 2; // Initial page number
        const container = $("#loadMoreWithdrawalsContainer");
        const loadMoreWithdrawals = $(".loadMoreWithdrawals");
        const lastPageDiv = $("#WithdrawalsLastpage"); // Get the reference to the last page div

        loadMoreWithdrawals.click(function(e) {
            e.preventDefault();
            const currentPage = $(this).data("page");
            loadMoreWithdrawals.html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Loading...</span>');

            // Send an AJAX request to fetch more content
            $.ajax({
                url: "<?=$this->siteUrl()?>/user/payouts", // Update with the correct URL
                type: "GET",
                data: { page: currentPage },
                success: function(response) {
                    const withdrawals = response.withdrawals;

                    // Construct the HTML markup for each withdrawal item
                    let html = '';
                    for (let i = 0; i < withdrawals.length; i++) {
                        const withdrawal = withdrawals[i];

                        // Retrieve the associated plan information
                        const gateway = getGatewayById(withdrawal.withdraw_code);

                        html += '<div class="transac-parent cursor-pointer">';
                        html += '<div class="d-flex justify-content-between transac-child">';
                        html += '<div class="d-flex w-50">';
                        html += '<div class="deposit-circle d-flex justify-content-center align-items-center">';

                        if (gateway) {
                            html += '<img src="<?= $this->siteUrl() ?>/<?= PUBLIC_PATH ?>/<?= UPLOADS_PATH ?>/withdrawal/' + gateway.image + '" alt=""/>';
                        }

                        html += '</div>';
                        html += '<div class="ml-20 r-ml-8">';

                        if (gateway) {
                            html += '<p class="mb-0 text-dark f-16 gilroy-medium theme-tran">' + gateway.name + "</p>";
                        }

                        html += '<div class="d-flex flex-wrap">';
                        html += '<p class="mb-0 text-gray-100 f-13 leading-17 gilroy-regular tran-title mt-2">Date</p>';
                        html += '<p class="mb-0 text-gray-100 f-13 leading-17 gilroy-regular tran-title mt-2 d-flex justify-content-center align-items-center">';
                        html += '<svg class="mx-2 text-muted-100" width="4" height="4" viewBox="0 0 4 4" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="2" cy="2" r="2" fill="currentColor" /></svg>'  + formatDate(withdrawal.created_at);
                        html += '</p>';

                        html += '</div>';
                        html += '</div>';
                        html += '</div>';

                        html += '<div class="d-flex justify-content-center align-items-center">';
                        html += '<div>';
                        const currency = '<?= e($data['user']['currency']) ?>';
                        html += '<p class="mb-0 gilroy-medium text-gray-100 r-f-12 f-16 ph-20">' + formatCurrency(currency, withdrawal.amount);
                        html += '</p>';

                        if (withdrawal.status == "0") {
                            html += '<p class="text-dark f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">Initiated</p>';
                        }else if (withdrawal.status == "1") {
                            html += '<p class="text-success f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">Completed</p>';
                        }else if (withdrawal.status == "2") {
                            html += '<p class="text-warning f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">Pending</p>';
                        }else if (withdrawal.status == "3") {
                            html += '<p class="text-danger f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">Rejected</p>';
                        }

                        html += '</div>';
                        html += '<div class="cursor-pointer transaction-arrow ml-28 r-ml-12">';
                        html += '<a href="' + (withdrawal.status == 0 ? '<?=$this->siteUrl()?>/user/confirm/payout/' + withdrawal.withdrawId + '/' + withdrawal.withdraw_code : 'javascript:void(0)') + '" class="arrow-hovers">';
                        html += '<svg class="nscaleX-1" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M3.5312 1.52861C3.27085 1.78896 3.27085 2.21107 3.5312 2.47141L7.0598 6.00001L3.5312 9.52861C3.27085 9.78895 3.27085 10.2111 3.5312 10.4714C3.79155 10.7318 4.21366 10.7318 4.47401 10.4714L8.47401 6.47141C8.73436 6.21106 8.73436 5.78895 8.47401 5.52861L4.47401 1.52861C4.21366 1.26826 3.79155 1.26826 3.5312 1.52861Z" fill="currentColor"/></svg>';

                        html += '</a>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                    }

                    // Append the new content to the container
                    container.find(".list-group").append(html);

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
        function getGatewayById(withdraw_code) {
            const gatewaysMap = {};
            const gateways = <?= json_encode($data['withdrawal-gateways']) ?>; // Retrieve the gateway array from PHP

            // Build a map of gateways keyed by their method_code
            gateways.forEach(gateway => {
                gatewaysMap[gateway.withdraw_code] = gateway;
            });

            // Return the gateway if found, otherwise return null
            return gatewaysMap[withdraw_code] || null;
        }

        function formatCurrency(currency, amount) {
            // Parse the amount as float to ensure it's treated as a number
            amount = parseFloat(amount);

            switch(currency) {
                case '€':
                    return '€ ' + amount.toFixed(2);
                case '£':
                    return '£ ' + amount.toFixed(2);
                case '$':
                    return '$ ' + amount.toFixed(2);
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

<!-- Load InitiatedWithdrawals -->
<script>
    $(document).ready(function() {
        let page = 2; // Initial page number
        const container = $("#loadMoreInitiatedWithdrawalsContainer");
        const loadMoreInitiatedWithdrawals = $(".loadMoreInitiatedWithdrawals");
        const lastPageDiv = $("#InitiatedWithdrawalsLastpage"); // Get the reference to the last page div

        loadMoreInitiatedWithdrawals.click(function(e) {
            e.preventDefault();
            const currentPage = $(this).data("page");
            loadMoreInitiatedWithdrawals.html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Loading...</span>');

            // Send an AJAX request to fetch more content
            $.ajax({
                url: "<?=$this->siteUrl()?>/payout/initiated", // Update with the correct URL
                type: "GET",
                data: { page: currentPage },
                success: function(response) {
                    const withdrawals = response.withdrawals;

                    // Construct the HTML markup for each withdrawal item
                    let html = '';
                    for (let i = 0; i < withdrawals.length; i++) {
                        const withdrawal = withdrawals[i];

                        // Retrieve the associated plan information
                        const gateway = getGatewayById(withdrawal.withdraw_code);

                        html += '<div class="transac-parent cursor-pointer">';
                        html += '<div class="d-flex justify-content-between transac-child">';
                        html += '<div class="d-flex w-50">';
                        html += '<div class="deposit-circle d-flex justify-content-center align-items-center">';

                        if (gateway) {
                            html += '<img src="<?= $this->siteUrl() ?>/<?= PUBLIC_PATH ?>/<?= UPLOADS_PATH ?>/withdrawal/' + gateway.image + '" alt=""/>';
                        }

                        html += '</div>';
                        html += '<div class="ml-20 r-ml-8">';

                        if (gateway) {
                            html += '<p class="mb-0 text-dark f-16 gilroy-medium theme-tran">' + gateway.name + "</p>";
                        }

                        html += '<div class="d-flex flex-wrap">';
                        html += '<p class="mb-0 text-gray-100 f-13 leading-17 gilroy-regular tran-title mt-2">Date</p>';
                        html += '<p class="mb-0 text-gray-100 f-13 leading-17 gilroy-regular tran-title mt-2 d-flex justify-content-center align-items-center">';
                        html += '<svg class="mx-2 text-muted-100" width="4" height="4" viewBox="0 0 4 4" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="2" cy="2" r="2" fill="currentColor" /></svg>'  + formatDate(withdrawal.created_at);
                        html += '</p>';

                        html += '</div>';
                        html += '</div>';
                        html += '</div>';

                        html += '<div class="d-flex justify-content-center align-items-center">';
                        html += '<div>';
                        const currency = '<?= e($data['user']['currency']) ?>';
                        html += '<p class="mb-0 gilroy-medium text-gray-100 r-f-12 f-16 ph-20">' + formatCurrency(currency, withdrawal.amount);
                        html += '</p>';

                        if (withdrawal.status == "0") {
                            html += '<p class="text-dark f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">Initiated</p>';
                        }else if (withdrawal.status == "1") {
                            html += '<p class="text-success f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">Completed</p>';
                        }else if (withdrawal.status == "2") {
                            html += '<p class="text-warning f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">Pending</p>';
                        }else if (withdrawal.status == "3") {
                            html += '<p class="text-danger f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">Rejected</p>';
                        }

                        html += '</div>';
                        html += '<div class="cursor-pointer transaction-arrow ml-28 r-ml-12">';
                        html += '<a href="' + (withdrawal.status == 0 ? '<?=$this->siteUrl()?>/user/confirm/payout/' + withdrawal.withdrawId + '/' + withdrawal.withdraw_code : 'javascript:void(0)') + '" class="arrow-hovers">';
                        html += '<svg class="nscaleX-1" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M3.5312 1.52861C3.27085 1.78896 3.27085 2.21107 3.5312 2.47141L7.0598 6.00001L3.5312 9.52861C3.27085 9.78895 3.27085 10.2111 3.5312 10.4714C3.79155 10.7318 4.21366 10.7318 4.47401 10.4714L8.47401 6.47141C8.73436 6.21106 8.73436 5.78895 8.47401 5.52861L4.47401 1.52861C4.21366 1.26826 3.79155 1.26826 3.5312 1.52861Z" fill="currentColor"/></svg>';

                        html += '</a>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                    }

                    // Append the new content to the container
                    container.find(".list-group").append(html);

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
        function getGatewayById(withdraw_code) {
            const gatewaysMap = {};
            const gateways = <?= json_encode($data['withdrawal-gateways']) ?>; // Retrieve the gateway array from PHP

            // Build a map of gateways keyed by their method_code
            gateways.forEach(gateway => {
                gatewaysMap[gateway.withdraw_code] = gateway;
            });

            // Return the gateway if found, otherwise return null
            return gatewaysMap[withdraw_code] || null;
        }

        function formatCurrency(currency, amount) {
            // Parse the amount as float to ensure it's treated as a number
            amount = parseFloat(amount);

            switch(currency) {
                case '€':
                    return '€ ' + amount.toFixed(2);
                case '£':
                    return '£ ' + amount.toFixed(2);
                case '$':
                    return '$ ' + amount.toFixed(2);
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

<!-- Load PendingWithdrawals -->
<script>
    $(document).ready(function() {
        let page = 2; // Initial page number
        const container = $("#loadMorePendingWithdrawalsContainer");
        const loadMorePendingWithdrawals = $(".loadMorePendingWithdrawals");
        const lastPageDiv = $("#PendingWithdrawalsLastpage"); // Get the reference to the last page div

        loadMorePendingWithdrawals.click(function(e) {
            e.preventDefault();
            const currentPage = $(this).data("page");
            loadMorePendingWithdrawals.html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Loading...</span>');

            // Send an AJAX request to fetch more content
            $.ajax({
                url: "<?=$this->siteUrl()?>/payout/pending", // Update with the correct URL
                type: "GET",
                data: { page: currentPage },
                success: function(response) {
                    const withdrawals = response.withdrawals;

                    // Construct the HTML markup for each withdrawal item
                    let html = '';
                    for (let i = 0; i < withdrawals.length; i++) {
                        const withdrawal = withdrawals[i];

                        // Retrieve the associated plan information
                        const gateway = getGatewayById(withdrawal.withdraw_code);

                        html += '<div class="transac-parent cursor-pointer">';
                        html += '<div class="d-flex justify-content-between transac-child">';
                        html += '<div class="d-flex w-50">';
                        html += '<div class="deposit-circle d-flex justify-content-center align-items-center">';

                        if (gateway) {
                            html += '<img src="<?= $this->siteUrl() ?>/<?= PUBLIC_PATH ?>/<?= UPLOADS_PATH ?>/withdrawal/' + gateway.image + '" alt=""/>';
                        }

                        html += '</div>';
                        html += '<div class="ml-20 r-ml-8">';

                        if (gateway) {
                            html += '<p class="mb-0 text-dark f-16 gilroy-medium theme-tran">' + gateway.name + "</p>";
                        }

                        html += '<div class="d-flex flex-wrap">';
                        html += '<p class="mb-0 text-gray-100 f-13 leading-17 gilroy-regular tran-title mt-2">Date</p>';
                        html += '<p class="mb-0 text-gray-100 f-13 leading-17 gilroy-regular tran-title mt-2 d-flex justify-content-center align-items-center">';
                        html += '<svg class="mx-2 text-muted-100" width="4" height="4" viewBox="0 0 4 4" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="2" cy="2" r="2" fill="currentColor" /></svg>'  + formatDate(withdrawal.created_at);
                        html += '</p>';

                        html += '</div>';
                        html += '</div>';
                        html += '</div>';

                        html += '<div class="d-flex justify-content-center align-items-center">';
                        html += '<div>';
                        const currency = '<?= e($data['user']['currency']) ?>';
                        html += '<p class="mb-0 gilroy-medium text-gray-100 r-f-12 f-16 ph-20">' + formatCurrency(currency, withdrawal.amount);
                        html += '</p>';

                        if (withdrawal.status == "0") {
                            html += '<p class="text-dark f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">Initiated</p>';
                        }else if (withdrawal.status == "1") {
                            html += '<p class="text-success f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">Completed</p>';
                        }else if (withdrawal.status == "2") {
                            html += '<p class="text-warning f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">Pending</p>';
                        }else if (withdrawal.status == "3") {
                            html += '<p class="text-danger f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">Rejected</p>';
                        }

                        html += '</div>';
                        html += '<div class="cursor-pointer transaction-arrow ml-28 r-ml-12">';
                        html += '<a href="' + (withdrawal.status == 0 ? '<?=$this->siteUrl()?>/user/confirm/payout/' + withdrawal.withdrawId + '/' + withdrawal.withdraw_code : 'javascript:void(0)') + '" class="arrow-hovers">';
                        html += '<svg class="nscaleX-1" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M3.5312 1.52861C3.27085 1.78896 3.27085 2.21107 3.5312 2.47141L7.0598 6.00001L3.5312 9.52861C3.27085 9.78895 3.27085 10.2111 3.5312 10.4714C3.79155 10.7318 4.21366 10.7318 4.47401 10.4714L8.47401 6.47141C8.73436 6.21106 8.73436 5.78895 8.47401 5.52861L4.47401 1.52861C4.21366 1.26826 3.79155 1.26826 3.5312 1.52861Z" fill="currentColor"/></svg>';

                        html += '</a>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                    }

                    // Append the new content to the container
                    container.find(".list-group").append(html);

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
        function getGatewayById(withdraw_code) {
            const gatewaysMap = {};
            const gateways = <?= json_encode($data['withdrawal-gateways']) ?>; // Retrieve the gateway array from PHP

            // Build a map of gateways keyed by their method_code
            gateways.forEach(gateway => {
                gatewaysMap[gateway.withdraw_code] = gateway;
            });

            // Return the gateway if found, otherwise return null
            return gatewaysMap[withdraw_code] || null;
        }

        function formatCurrency(currency, amount) {
            // Parse the amount as float to ensure it's treated as a number
            amount = parseFloat(amount);

            switch(currency) {
                case '€':
                    return '€ ' + amount.toFixed(2);
                case '£':
                    return '£ ' + amount.toFixed(2);
                case '$':
                    return '$ ' + amount.toFixed(2);
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

<!-- Load CompletedWithdrawals -->
<script>
    $(document).ready(function() {
        let page = 2; // Initial page number
        const container = $("#loadMoreCompletedWithdrawalsContainer");
        const loadMoreCompletedWithdrawals = $(".loadMoreCompletedWithdrawals");
        const lastPageDiv = $("#CompletedWithdrawalsLastpage"); // Get the reference to the last page div

        loadMoreCompletedWithdrawals.click(function(e) {
            e.preventDefault();
            const currentPage = $(this).data("page");
            loadMoreCompletedWithdrawals.html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Loading...</span>');

            // Send an AJAX request to fetch more content
            $.ajax({
                url: "<?=$this->siteUrl()?>/payout/completed", // Update with the correct URL
                type: "GET",
                data: { page: currentPage },
                success: function(response) {
                    const withdrawals = response.withdrawals;

                    // Construct the HTML markup for each withdrawal item
                    let html = '';
                    for (let i = 0; i < withdrawals.length; i++) {
                        const withdrawal = withdrawals[i];

                        // Retrieve the associated plan information
                        const gateway = getGatewayById(withdrawal.withdraw_code);

                        html += '<div class="transac-parent cursor-pointer">';
                        html += '<div class="d-flex justify-content-between transac-child">';
                        html += '<div class="d-flex w-50">';
                        html += '<div class="deposit-circle d-flex justify-content-center align-items-center">';

                        if (gateway) {
                            html += '<img src="<?= $this->siteUrl() ?>/<?= PUBLIC_PATH ?>/<?= UPLOADS_PATH ?>/withdrawal/' + gateway.image + '" alt=""/>';
                        }

                        html += '</div>';
                        html += '<div class="ml-20 r-ml-8">';

                        if (gateway) {
                            html += '<p class="mb-0 text-dark f-16 gilroy-medium theme-tran">' + gateway.name + "</p>";
                        }

                        html += '<div class="d-flex flex-wrap">';
                        html += '<p class="mb-0 text-gray-100 f-13 leading-17 gilroy-regular tran-title mt-2">Date</p>';
                        html += '<p class="mb-0 text-gray-100 f-13 leading-17 gilroy-regular tran-title mt-2 d-flex justify-content-center align-items-center">';
                        html += '<svg class="mx-2 text-muted-100" width="4" height="4" viewBox="0 0 4 4" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="2" cy="2" r="2" fill="currentColor" /></svg>'  + formatDate(withdrawal.created_at);
                        html += '</p>';

                        html += '</div>';
                        html += '</div>';
                        html += '</div>';

                        html += '<div class="d-flex justify-content-center align-items-center">';
                        html += '<div>';
                        const currency = '<?= e($data['user']['currency']) ?>';
                        html += '<p class="mb-0 gilroy-medium text-gray-100 r-f-12 f-16 ph-20">' + formatCurrency(currency, withdrawal.amount);
                        html += '</p>';

                        if (withdrawal.status == "0") {
                            html += '<p class="text-dark f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">Initiated</p>';
                        }else if (withdrawal.status == "1") {
                            html += '<p class="text-success f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">Completed</p>';
                        }else if (withdrawal.status == "2") {
                            html += '<p class="text-warning f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">Pending</p>';
                        }else if (withdrawal.status == "3") {
                            html += '<p class="text-danger f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">Rejected</p>';
                        }

                        html += '</div>';
                        html += '<div class="cursor-pointer transaction-arrow ml-28 r-ml-12">';
                        html += '<a href="' + (withdrawal.status == 0 ? '<?=$this->siteUrl()?>/user/confirm/payout/' + withdrawal.withdrawId + '/' + withdrawal.withdraw_code : 'javascript:void(0)') + '" class="arrow-hovers">';
                        html += '<svg class="nscaleX-1" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M3.5312 1.52861C3.27085 1.78896 3.27085 2.21107 3.5312 2.47141L7.0598 6.00001L3.5312 9.52861C3.27085 9.78895 3.27085 10.2111 3.5312 10.4714C3.79155 10.7318 4.21366 10.7318 4.47401 10.4714L8.47401 6.47141C8.73436 6.21106 8.73436 5.78895 8.47401 5.52861L4.47401 1.52861C4.21366 1.26826 3.79155 1.26826 3.5312 1.52861Z" fill="currentColor"/></svg>';

                        html += '</a>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                    }

                    // Append the new content to the container
                    container.find(".list-group").append(html);

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
        function getGatewayById(withdraw_code) {
            const gatewaysMap = {};
            const gateways = <?= json_encode($data['withdrawal-gateways']) ?>; // Retrieve the gateway array from PHP

            // Build a map of gateways keyed by their method_code
            gateways.forEach(gateway => {
                gatewaysMap[gateway.withdraw_code] = gateway;
            });

            // Return the gateway if found, otherwise return null
            return gatewaysMap[withdraw_code] || null;
        }

        function formatCurrency(currency, amount) {
            // Parse the amount as float to ensure it's treated as a number
            amount = parseFloat(amount);

            switch(currency) {
                case '€':
                    return '€ ' + amount.toFixed(2);
                case '£':
                    return '£ ' + amount.toFixed(2);
                case '$':
                    return '$ ' + amount.toFixed(2);
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

<!-- Load CancelledWithdrawals -->
<script>
    $(document).ready(function() {
        let page = 2; // Initial page number
        const container = $("#loadMoreCancelledWithdrawalsContainer");
        const loadMoreCancelledWithdrawals = $(".loadMoreCancelledWithdrawals");
        const lastPageDiv = $("#CancelledWithdrawalsLastpage"); // Get the reference to the last page div

        loadMoreCancelledWithdrawals.click(function(e) {
            e.preventDefault();
            const currentPage = $(this).data("page");
            loadMoreCancelledWithdrawals.html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Loading...</span>');

            // Send an AJAX request to fetch more content
            $.ajax({
                url: "<?=$this->siteUrl()?>/payout/cancelled", // Update with the correct URL
                type: "GET",
                data: { page: currentPage },
                success: function(response) {
                    const withdrawals = response.withdrawals;

                    // Construct the HTML markup for each withdrawal item
                    let html = '';
                    for (let i = 0; i < withdrawals.length; i++) {
                        const withdrawal = withdrawals[i];

                        // Retrieve the associated plan information
                        const gateway = getGatewayById(withdrawal.withdraw_code);

                        html += '<div class="transac-parent cursor-pointer">';
                        html += '<div class="d-flex justify-content-between transac-child">';
                        html += '<div class="d-flex w-50">';
                        html += '<div class="deposit-circle d-flex justify-content-center align-items-center">';

                        if (gateway) {
                            html += '<img src="<?= $this->siteUrl() ?>/<?= PUBLIC_PATH ?>/<?= UPLOADS_PATH ?>/withdrawal/' + gateway.image + '" alt=""/>';
                        }

                        html += '</div>';
                        html += '<div class="ml-20 r-ml-8">';

                        if (gateway) {
                            html += '<p class="mb-0 text-dark f-16 gilroy-medium theme-tran">' + gateway.name + "</p>";
                        }

                        html += '<div class="d-flex flex-wrap">';
                        html += '<p class="mb-0 text-gray-100 f-13 leading-17 gilroy-regular tran-title mt-2">Date</p>';
                        html += '<p class="mb-0 text-gray-100 f-13 leading-17 gilroy-regular tran-title mt-2 d-flex justify-content-center align-items-center">';
                        html += '<svg class="mx-2 text-muted-100" width="4" height="4" viewBox="0 0 4 4" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="2" cy="2" r="2" fill="currentColor" /></svg>'  + formatDate(withdrawal.created_at);
                        html += '</p>';

                        html += '</div>';
                        html += '</div>';
                        html += '</div>';

                        html += '<div class="d-flex justify-content-center align-items-center">';
                        html += '<div>';
                        const currency = '<?= e($data['user']['currency']) ?>';
                        html += '<p class="mb-0 gilroy-medium text-gray-100 r-f-12 f-16 ph-20">' + formatCurrency(currency, withdrawal.amount);
                        html += '</p>';

                        if (withdrawal.status == "0") {
                            html += '<p class="text-dark f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">Initiated</p>';
                        }else if (withdrawal.status == "1") {
                            html += '<p class="text-success f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">Completed</p>';
                        }else if (withdrawal.status == "2") {
                            html += '<p class="text-warning f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">Pending</p>';
                        }else if (withdrawal.status == "3") {
                            html += '<p class="text-danger f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">Rejected</p>';
                        }

                        html += '</div>';
                        html += '<div class="cursor-pointer transaction-arrow ml-28 r-ml-12">';
                        html += '<a href="' + (withdrawal.status == 0 ? '<?=$this->siteUrl()?>/user/confirm/payout/' + withdrawal.withdrawId + '/' + withdrawal.withdraw_code : 'javascript:void(0)') + '" class="arrow-hovers">';
                        html += '<svg class="nscaleX-1" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M3.5312 1.52861C3.27085 1.78896 3.27085 2.21107 3.5312 2.47141L7.0598 6.00001L3.5312 9.52861C3.27085 9.78895 3.27085 10.2111 3.5312 10.4714C3.79155 10.7318 4.21366 10.7318 4.47401 10.4714L8.47401 6.47141C8.73436 6.21106 8.73436 5.78895 8.47401 5.52861L4.47401 1.52861C4.21366 1.26826 3.79155 1.26826 3.5312 1.52861Z" fill="currentColor"/></svg>';

                        html += '</a>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                    }

                    // Append the new content to the container
                    container.find(".list-group").append(html);

                    // Update the page number
                    page++;

                    // If there is no more content to load, hide the load more button
                    if (withdrawals.length === 0) {
                        loadMoreCancelledWithdrawals.hide();
                        lastPageDiv.removeClass("d-none");
                    } else {
                        loadMoreCancelledWithdrawals.text("Load More"); // Restore the button text
                        loadMoreCancelledWithdrawals.data("page", page); // Update the data-page attribute
                    }
                },
                error: function() {
                    // Handle the error case
                    loadMoreCancelledWithdrawals.text("Load More"); // Restore the button text
                },
            });
        });

        // Custom function to retrieve gateways by ID
        function getGatewayById(withdraw_code) {
            const gatewaysMap = {};
            const gateways = <?= json_encode($data['withdrawal-gateways']) ?>; // Retrieve the gateway array from PHP

            // Build a map of gateways keyed by their method_code
            gateways.forEach(gateway => {
                gatewaysMap[gateway.withdraw_code] = gateway;
            });

            // Return the gateway if found, otherwise return null
            return gatewaysMap[withdraw_code] || null;
        }

        function formatCurrency(currency, amount) {
            // Parse the amount as float to ensure it's treated as a number
            amount = parseFloat(amount);

            switch(currency) {
                case '€':
                    return '€ ' + amount.toFixed(2);
                case '£':
                    return '£ ' + amount.toFixed(2);
                case '$':
                    return '$ ' + amount.toFixed(2);
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

<!-- Load Transactions -->
<script>
    $(document).ready(function() {
        let page = 2; // Initial page number
        const container = $("#loadMoreTransactionsContainer");
        const loadMoreTransactions = $(".loadMoreTransactions");
        const lastPageDiv = $("#TransactionsLastpage"); // Get the reference to the last page div

        loadMoreTransactions.click(function(e) {
            e.preventDefault();
            const currentPage = $(this).data("page");
            loadMoreTransactions.html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Loading...</span>');

            // Send an AJAX request to fetch more content
            $.ajax({
                url: "<?=$this->siteUrl()?>/user/transactions", // Update with the correct URL
                type: "GET",
                data: { page: currentPage },
                success: function(response) {
                    const transactions = response.transactions;

                    // Construct the HTML markup for each transaction item
                    let html = '';
                    for (let i = 0; i < transactions.length; i++) {
                        const transaction = transactions[i];

                        html += '<div class="transac-parent cursor-pointer">';
                        html += '<div class="d-flex justify-content-between transac-child">';
                        html += '<div class="d-flex w-50">';
                        html += '<div class="deposit-circle d-flex justify-content-center align-items-center">';

                        if (transaction.trx_type == "+") {
                            html += '<img src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/frontend/templates/images/cashin.png" alt="Transaction" />';
                        }else if (transaction.trx_type == "-") {
                            html += '<img src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/frontend/templates/images/cashout.png" alt="Transaction" />';
                        }

                        html += '</div>';
                        html += '<div class="ml-20 r-ml-8">';

                        html += '<p class="mb-0 text-dark f-16 gilroy-medium theme-tran">' + transaction.details + "</p>";

                        html += '<div class="d-flex flex-wrap">';
                        html += '<p class="mb-0 text-gray-100 f-13 leading-17 gilroy-regular tran-title mt-2">Date</p>';
                        html += '<p class="mb-0 text-gray-100 f-13 leading-17 gilroy-regular tran-title mt-2 d-flex justify-content-center align-items-center">';
                        html += '<svg class="mx-2 text-muted-100" width="4" height="4" viewBox="0 0 4 4" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="2" cy="2" r="2" fill="currentColor" /></svg>'  + formatDate(transaction.created_at);
                        html += '</p>';

                        html += '</div>';
                        html += '</div>';
                        html += '</div>';

                        html += '<div class="d-flex justify-content-center align-items-center">';
                        html += '<div>';
                        const currency = '<?= e($data['user']['currency']) ?>';
                        html += '<p class="mb-0 gilroy-medium text-gray-100 r-f-12 f-16 ph-20">' + formatCurrency(currency, transaction.amount);
                        html += '</p>';
                        html += '</div>';
                        html += '</div>';

                        html += '</div>';
                        html += '</div>';
                    }

                    // Append the new content to the container
                    container.find(".list-group").append(html);

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
                    return '€ ' + amount.toFixed(2);
                case '£':
                    return '£ ' + amount.toFixed(2);
                case '$':
                    return '$ ' + amount.toFixed(2);
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

<!-- Send Money -->
<script>
    $(document).ready(function () {
        // Handler for the sendMoneyBtn button click event
        $("#sendMoneyBtn").click(function (event) {
            event.preventDefault(); // Prevent form submission

            // Validate inputs
            let valid = true;

            // receiver validation
            const receiverValue = $("#receiver").val().trim();
            if (receiverValue === "") {
                $("#receiver").addClass("is-invalid");
                valid = false;
            } else {
                $("#receiver").removeClass("is-invalid");
            }

            // amount validation
            const amountValue = $("#amount").val().trim();
            if (amountValue === "") {
                $("#amount").addClass("is-invalid");
                valid = false;
            } else {
                $("#amount").removeClass("is-invalid");
            }

            // note validation
            const noteValue = $("#note").val().trim();
            if (noteValue === "") {
                $("#note").addClass("is-invalid");
                valid = false;
            } else {
                $("#note").removeClass("is-invalid");
            }

            if (!valid) {
                return;
            }

            // Add the spinner to the button
            $("#sendMoneyBtn").html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Processing...</span>');
            $("#sendMoneyBtn").prop("disabled", true); // Disable the button

            // Send an Ajax request to the controller
            $.ajax({
                url: "<?=$this->siteUrl()?>/user/send", // Modify the URL according to your setup
                type: 'POST',
                data: $('#send-money-form').serialize(),
                dataType: 'json',
                success: function (response) {
                    // Check the response status
                    if (response.status === "success") {
                        window.location.href = '<?=$this->siteUrl()?>/' + response.redirect;
                    } else if (response.status === "error") {
                        // Show an iziToast error notification
                        iziToast.error({
                            message: response.message,
                            position: 'topRight'
                        });
                    } else if (response.status === "warning") {
                        // Display an error message or provide feedback to the user
                        $("#amount-limit-error").text(response.message).addClass("error");
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
                    $("#sendMoneyBtn").html("Proceed");
                    $("#sendMoneyBtn").prop("disabled", false);
                },
            });
        });

        // Add event listener for input fields to remove 'is-invalid' class on input
        $("#receiver, #amount, #note").on("input", function () {
            $(this).removeClass("is-invalid");
        });
    });
</script>

<!-- Confirm Transfer -->
<script>
    $(document).ready(function () {
        // Handler for the sendMoneyConfirmBtn button click event
        $("#sendMoneyConfirmBtn").click(function (event) {
            event.preventDefault(); // Prevent form submission

            // Retrieve the ID from the data attribute
            const sendValue = $(this).data("send");

            if (!sendValue) {
                console.error("Send ID is not set in the data attribute.");
                return;
            }

            // Add the spinner to the button
            $("#sendMoneyConfirmBtn").html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Processing...</span>');
            $("#sendMoneyConfirmBtn").prop("disabled", true); // Disable the button

            // Send an Ajax request to the confirmation controller
            $.ajax({
                url: "<?=$this->siteUrl()?>/user/send/confirm/" + sendValue, // Modify the URL according to your setup
                type: 'POST',
                data: $('#send-money-confirm-form').serialize(),
                dataType: 'json',
                success: function (response) {
                    // Check the response status
                    if (response.status === "success") {
                        window.location.href = '<?=$this->siteUrl()?>/' + response.redirect;
                    } else if (response.status === "error") {
                        // The edit failed, show an iziToast error notification
                        iziToast.error({
                            message: response.message,
                            position: "topRight",
                        });
                    } else if (response.status === "warning") {
                        // The edit failed, show an iziToast error notification
                        iziToast.error({
                            message: response.message,
                            position: "topRight",
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
                    $("#sendMoneyConfirmBtn").html("Yes, Approve");
                    $("#sendMoneyConfirmBtn").prop("disabled", false);
                },
            });
        });
    });
</script>

<!-- Request Money -->
<script>
    $(document).ready(function () {
        // Handler for the requestMoneyBtn button click event
        $("#requestMoneyBtn").click(function (event) {
            event.preventDefault(); // Prevent form submission

            // Validate inputs
            let valid = true;

            // sender validation
            const senderValue = $("#sender").val().trim();
            if (senderValue === "") {
                $("#sender").addClass("is-invalid");
                valid = false;
            } else {
                $("#sender").removeClass("is-invalid");
            }

            // amount validation
            const amountValue = $("#amount").val().trim();
            if (amountValue === "") {
                $("#amount").addClass("is-invalid");
                valid = false;
            } else {
                $("#amount").removeClass("is-invalid");
            }

            // note validation
            const noteValue = $("#note").val().trim();
            if (noteValue === "") {
                $("#note").addClass("is-invalid");
                valid = false;
            } else {
                $("#note").removeClass("is-invalid");
            }

            if (!valid) {
                return;
            }

            // Add the spinner to the button
            $("#requestMoneyBtn").html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Processing...</span>');
            $("#requestMoneyBtn").prop("disabled", true); // Disable the button

            // Send an Ajax request to the request controller
            $.ajax({
                url: "<?=$this->siteUrl()?>/user/request", // Modify the URL according to your setup
                type: 'POST',
                data: $('#request-money-form').serialize(),
                dataType: 'json',
                success: function (response) {
                    // Check the response status
                    if (response.status === "success") {
                        window.location.href = '<?=$this->siteUrl()?>/' + response.redirect;
                    } else if (response.status === "error") {
                        // Show an iziToast error notification
                        iziToast.error({
                            message: response.message,
                            position: 'topRight'
                        });
                    } else if (response.status === "warning") {
                        // Display an error message or provide feedback to the user
                        $("#sender-error").text(response.message).addClass("error");
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
                    $("#requestMoneyBtn").html("Proceed");
                    $("#requestMoneyBtn").prop("disabled", false);
                },
            });
        });

        // Add event listener for input fields to remove 'is-invalid' class on input
        $("#sender, #amount, #note").on("input", function () {
            $(this).removeClass("is-invalid");
        });
    });
</script>

<!-- Confirm Request -->
<script>
    $(document).ready(function () {
        // Handler for the requestMoneyConfirmBtn button click event
        $("#requestMoneyConfirmBtn").click(function (event) {
            event.preventDefault(); // Prevent form submission

            // Retrieve the request ID from the data attribute
            const requestValue = $(this).data("request");

            if (!requestValue) {
                console.error("Request ID is not set in the data attribute.");
                return;
            }

            // Add the spinner to the button
            $("#requestMoneyConfirmBtn").html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Processing...</span>');
            $("#requestMoneyConfirmBtn").prop("disabled", true); // Disable the button

            // Send an Ajax request to the confirmation controller
            $.ajax({
                url: "<?=$this->siteUrl()?>/user/request/confirm/" + requestValue, // Modify the URL according to your setup
                type: 'POST',
                data: $('#request-money-confirm-form').serialize(),
                dataType: 'json',
                success: function (response) {
                    // Check the response status
                    if (response.status === "success") {
                        window.location.href = '<?=$this->siteUrl()?>/' + response.redirect;
                    } else if (response.status === "error") {
                        // Show an error message
                        iziToast.error({
                            message: response.message,
                            position: 'topRight'
                        });

                        // Redirect after a delay (optional)
                        setTimeout(function () {
                            window.location.href = '<?=$this->siteUrl()?>/' + response.redirect;
                        }, 3000); // Redirect after 3 seconds
                    } else if (response.status === "warning") {
                        /// Show a success message
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
                    $("#requestMoneyConfirmBtn").html("Confirm &amp; Send");
                    $("#requestMoneyConfirmBtn").prop("disabled", false);
                },
            });
        });
    });
</script>

<!-- Contact Us -->
<script>
    $(document).ready(function () {
        // Handler for the contactBtn button click event
        $("#contactBtn").click(function (event) {
            event.preventDefault(); // Prevent form submission

            // Validate inputs
            let valid = true;

            // subject validation
            const subjectNameValue = $("#subject").val().trim();
            if (subjectNameValue === "") {
                $("#subject").addClass("is-invalid");
                valid = false;
            } else {
                $("#subject").removeClass("is-invalid");
            }

            // description validation
            const descriptionNameValue = $("#description").val().trim();
            if (descriptionNameValue === "") {
                // Display an error message or provide feedback to the user
                $("#description-error").text('This field is required').addClass("error");
                valid = false;
            }

            if (!valid) {
                return;
            }

            // Add the spinner to the button
            $("#contactBtn").html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Processing...</span>');
            $("#contactBtn").prop("disabled", true); 

            // Send an Ajax request to the edit controller
            $.ajax({
                url: "<?=$this->siteUrl()?>/user/support", // Modify the URL according to your edit
                type: 'POST',
                data: $('#ticket-create-form').serialize(),
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
                    $("#contactBtn").html("Send Email");
                    $("#contactBtn").prop("disabled", false);
                },
            });
        });

        // Add event listener for input fields to remove 'is-invalid' class on input
        $("#subject, #message").on("input", function () {
            $(this).removeClass("is-invalid");
            $("#description-error").removeClass("error");
            $("#description-error").text("");
        });
    });
</script>

<!-- Delete Account -->
<script>
    $(document).ready(function () {
        // Handler for the delete-modal-yes button click event
        $("#delete-modal-yes").click(function (event) {
            event.preventDefault(); // Prevent form submission

            // Retrieve the user ID from the data attribute
            const userId = $(this).data("user");

            if (!userId) {
                console.error("User ID is not set in the data attribute.");
                return;
            }

            // Add the spinner to the button
            $("#delete-modal-yes").html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Processing...</span>');
            $("#delete-modal-yes").prop("disabled", true); // Disable the button

            // Send an Ajax request to the confirmation controller
            $.ajax({
                url: '<?=$this->siteUrl()?>/requests/delete',
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
                    $("#delete-modal-yes").html("Yes, Delete");
                    $("#delete-modal-yes").prop("disabled", false);
                },
            });
        });
    });
</script>

<!-- Approve Request -->
<script>
    $(document).ready(function () {
        // Handler for the approve-modal-yes button click event
        $("#approve-modal-yes").click(function (event) {
            event.preventDefault(); // Prevent form submission

            // Retrieve the approval ID from the data attribute
            const approveId = $(this).data("approve");

            if (!approveId) {
                console.error("Approve ID is not set in the data attribute.");
                return;
            }

            // Add the spinner to the button
            $("#approve-modal-yes").html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Processing...</span>');
            $("#approve-modal-yes").prop("disabled", true); // Disable the button

            // Send an Ajax request to the confirmation controller
            $.ajax({
                url: '<?=$this->siteUrl()?>/user/request/approve',
                type: 'GET',
                data: 'id=' + approveId,
                dataType: 'json',
                success: function (response) {
                    // Check the response status
                    if (response.status === "success") {
                        window.location.href = '<?=$this->siteUrl()?>/' + response.redirect;
                    } else if (response.status === "error") {
                        // Show an error message
                        iziToast.error({
                            message: response.message,
                            position: 'topRight'
                        });
                    } else if (response.status === "warning") {
                        // Show an error message
                        iziToast.error({
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
                    $("#approve-modal-yes").html("Yes, Approve");
                    $("#approve-modal-yes").prop("disabled", false);
                },
            });
        });
    });
</script>

<!-- Reject Request -->
<script>
    $(document).ready(function () {
        // Handler for the reject-modal-yes button click event
        $("#reject-modal-yes").click(function (event) {
            event.preventDefault(); // Prevent form submission

            // Retrieve the reject ID from the data attribute
            const rejectId = $(this).data("reject");

            if (!rejectId) {
                console.error("Reject ID is not set in the data attribute.");
                return;
            }

            // Add the spinner to the button
            $("#reject-modal-yes").html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Processing...</span>');
            $("#reject-modal-yes").prop("disabled", true); // Disable the button

            // Send an Ajax request to the confirmation controller
            $.ajax({
                url: '<?=$this->siteUrl()?>/user/request/reject',
                type: 'GET',
                data: 'id=' + rejectId,
                dataType: 'json',
                success: function (response) {
                    // Check the response status
                    if (response.status === "success") {
                        window.location.href = '<?=$this->siteUrl()?>/' + response.redirect;
                    } else if (response.status === "error") {
                        // Show an error message
                        iziToast.error({
                            message: response.message,
                            position: 'topRight'
                        });
                    } else if (response.status === "warning") {
                        // Show an error message
                        iziToast.error({
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
                    $("#reject-modal-yes").html("Yes, Approve");
                    $("#reject-modal-yes").prop("disabled", false);
                },
            });
        });
    });
</script>

<!-- Loan - Ajax -->
<script>
    $(document).ready(function () {
        // Handler for the loanBtn button click event
        $("#loanBtn").click(function (event) {
            event.preventDefault(); // Prevent form submission

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

            // loan_remarks validation
            const loan_remarksValue = $("#loan_remarks").val().trim();
            if (loan_remarksValue === "") {
                $("#loan_remarks").addClass("is-invalid");
                valid = false;
            } else {
                $("#loan_remarks").removeClass("is-invalid");
            }

            // loan_term validation
            const loan_termValue = $("#loan_term").val().trim();
            if (loan_termValue === "") {
                $("#loan_term").addClass("is-invalid");
                valid = false;
            } else {
                $("#loan_term").removeClass("is-invalid");
            }

            // repayment_plan validation
            const repayment_planValue = $("#repayment_plan").val().trim();
            if (repayment_planValue === "") {
                $("#repayment_plan").addClass("is-invalid");
                valid = false;
            } else {
                $("#repayment_plan").removeClass("is-invalid");
            }

            // collateral validation
            const collateralValue = $("#collateral").val().trim();
            if (collateralValue === "") {
                $("#collateral").addClass("is-invalid");
                valid = false;
            } else {
                $("#collateral").removeClass("is-invalid");
            }

            if (!valid) {
                return;
            }

            // Add the spinner to the button
            $("#loanBtn").html('<span><span class="spinner spinner-border text-white spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Processing...</span>');
            $("#loanBtn").prop("disabled", true); // Disable the button

            // Create a FormData object
            const formData = new FormData($('#loan-form')[0]);

            // Send an Ajax request to the password controller
            $.ajax({
                url: "<?=$this->siteUrl()?>/user/loan", // Modify the URL according to your setup
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
                    $("#loanBtn").html("Send Request");
                    $("#loanBtn").prop("disabled", false);
                },
            });
        });

        // Add event listener for input fields to remove 'is-invalid' class on input
        $("#amount, #loan_remarks").on("input", function () {
            $(this).removeClass("is-invalid");
        });
    });
</script>