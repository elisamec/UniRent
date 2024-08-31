
       /**
        * Function to clear the rating for Owner
        */
        function clearRatingA() {
            // Get all star rating inputs
           document.getElementById('star0T').checked = true;
            // Uncheck all star rating inputs
            
        }
        
        // Function to set the rating based on the dynamic value
        function setRating(rating) {
            if (rating) {
                const starRating = document.getElementById('star' + rating + 'T');
                if (starRating) {
                    starRating.checked = true;
                } else {
                    console.error('Star rating element not found for rating:', rating);
                }
            }
        }
        setRating(rating);