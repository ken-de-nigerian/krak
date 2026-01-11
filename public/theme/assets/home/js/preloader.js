document.addEventListener("DOMContentLoaded", function () {
    // Add preloader-active class to prevent scrolling
    document.body.classList.add("preloader-active");

    // Wait until the content is fully loaded
    window.onload = function () {
        const preloader = document.getElementById("preloader");

        // Add a slight delay for a smooth experience
        setTimeout(() => {
            preloader.classList.add("hidden"); // Add fade-out class
            document.body.classList.remove("preloader-active"); // Re-enable scrolling

            // Remove preloader from DOM after the animation ends
            setTimeout(() => {
                preloader.remove();
            }, 800); // Match fade-out duration
        }, 300); // Slight delay to improve UX
    };
});