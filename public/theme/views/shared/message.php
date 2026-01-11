<?php
defined('FIR') OR exit();
/**
 * The template for displaying the success, info and error messages
 */
?>

<?php if($data['message']['type'] == "success"): ?>
    <script>
        "use strict";
        document.addEventListener('DOMContentLoaded', function() {
            function notify(status, message) {
                iziToast[status]({
                    message: message,
                    position: "topRight",
                });
            }

            // Call the notify() function with the appropriate parameters
            notify('success', '<?php echo $data['message']['content']; ?>');
        });
    </script>
<?php endif ?>   


<?php if($data['message']['type'] == "warning"): ?>
    <script>
        "use strict";
        document.addEventListener('DOMContentLoaded', function() {
            function notify(status, message) {
                iziToast[status]({
                    message: message,
                    position: "topRight",
                });
            }

            // Call the notify() function with the appropriate parameters
            notify('warning', '<?php echo $data['message']['content']; ?>');
        });
    </script> 
<?php endif ?> 


<?php if($data['message']['type'] == "error"): ?>
    <script>
        "use strict";
        document.addEventListener('DOMContentLoaded', function() {
            function notify(status, message) {
                iziToast[status]({
                    message: message,
                    position: "topRight",
                });
            }

            // Call the notify() function with the appropriate parameters
            notify('error', '<?php echo $data['message']['content']; ?>');
        });
    </script>
<?php endif ?>   		  