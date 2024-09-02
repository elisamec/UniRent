
       /**
        * Function to clear the Tenants rating
        */
        function clearRatingT() {
            // Get all star rating inputs
           document.getElementById('star0T').checked = true;
            // Uncheck all star rating inputs
            
        }
        /**
         * Function to clear the Owner rating
         */
        function clearRatingO() {
            document.getElementById('star0O').checked = true;
        }
        /**
         * Function to clear the Accommodation rating
         */
        function clearRatingA() {
            document.getElementById('star0A').checked = true;
        }
        
        // Function to set the rating based on the dynamic value
        function setRating(rating) {
                const starRating = document.getElementById('star' + rating + 'T');
                if (starRating) {
                    starRating.checked = true;
                } else {
                    console.error('Star rating element not found for rating:', rating);
                }
        }
        if (typeof rating !== 'undefined') {
            setRating(rating);
        }
        // Set default rating for Owner
        if (typeof ratingOwner !== 'undefined') {
            document.getElementById('star' + ratingOwner + 'O').checked = true;
        }

        // Set default rating for Accommodation
        if (typeof ratingAccommodation !== 'undefined') {
            document.getElementById('star' + ratingAccommodation + 'A').checked = true;
        }