// Function to generate stars based on the rating
function generateStars(stars) {
    let starElements = '';
    for (let i = 0; i < 5; i++) {
        if (i < stars) {
            starElements += '<span class="fa fa-star or"></span>';
        } else {
            starElements += '<span class="fa fa-star"></span>';
        }
    }
    return starElements;
}

// Function to create and append reviews to the container
function displayReviews(reviews) {
    const container = document.getElementById('reviewsContainer');

    if (container) {
        if (reviews.length === 0) {
            container.innerHTML = '<h4 class="noRev">There are no reviews yet!</h4>';
        } else {
            reviews.forEach(review => {
                const reviewElement = document.createElement('div');
                reviewElement.className = 'review';
                /*
                let style;
                if (review.statusReported === 1 || review.statusReported === true) {
                    style = 'style="background-color: #ffcccc;"';
                } else if (review.statusBanned === 1 || review.statusBanned === true) {
                    style = 'style="background-color: #ff9999;"';
                } else {
                    style = '';
                }
                reviewElement.attributes = style;
                */
                // Insert the names of the elements of the review array
                reviewElement.innerHTML = `
                <div class="row">
                    <h1 class="ReviewTitle">` + review.title + `</h1> <!-- Title of the review -->
                </div>
                <div class="row">
                        <div class="userSection">
                            <div class="userIcon">
                            <a href="/UniRent/Admin/profile/` + review.username + `"><img src=` + review.userPicture + ` alt="User Profile Picture"></a>
                        </div>
                        <div class="username"><a href="/UniRent/Admin/profile/` + review.username + `">` + review.username + `</a></div> <!-- Username of the reviewer -->
                    </div>
                        <div class="col-md-11">
                            <div class="stars">
                                ` + generateStars(review.stars) + ` <!-- Star rating -->
                            </div>
                            <p class="reviewContent">` + review.content + `</p> <!-- Content of the review -->
                        </div>
                    </div>
                `;

                container.appendChild(reviewElement);
            });
        }
    } else {
        console.error("Container not found!"); // Debugging: Error if container is not found
    }
}

// Call the function to display reviews
displayReviews(reviews);