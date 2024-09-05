document.addEventListener("DOMContentLoaded", function() {
    // Get the form element
    var form = document.getElementById('myForm');
    if (form) {

        // Add a submit event listener to the form
        form.addEventListener('submit', function(event) {
            // Prevent the default form submission
            event.preventDefault();

            // Perform your actions here
            console.log('Form submit button clicked!');
            
            // Call the function you want to run before submission
            beforeFormSubmit();

            // After your actions are complete, submit the form programmatically
            form.submit();
        });
    }
});

function beforeFormSubmit() {
    // Perform your actions here, e.g., update hidden input, validate data, etc.
    let uploadedImagesData = document.getElementById('uploadedImagesData');
    uploadedImagesData.value = JSON.stringify(imagesData);
    let data = getVisitData();
    let visitAvailabilityData = document.getElementById('visitAvailabilityData');
    visitAvailabilityData.value = JSON.stringify(data);
}