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
function displayReviews(reviews, user) {
    const container = document.getElementById('reviewsContainer');

    if (container) {
        if (reviews.length === 0) {
            container.innerHTML = '<h4 class="noRev">There are no reviews yet!</h4>';
        } else {
            console.log(typeof reviews);
            reviews.forEach(review => {
                const reviewElement = document.createElement('div');
                reviewElement.className = 'review';
                let style = '';
                let styleUser = '';
                let href = '';
                let reportedStyle = '';
                let reportedWarning = '';
                let additionalButtons = '';
                if (user==='Admin') {
                    href='/UniRent/Admin/profile/' + review.username;
                    if (review.statusReported === 1 || review.statusReported === true) {
                        style = 'style="background-color: #ffcccc;"';
                    } else if (review.statusBanned === 1 || review.statusBanned === true) {
                        style = 'style="background-color: #ff9999;"';
                    }
                    reviewElement.attributes = style;
                } else {
                    if (review.userStatus ==='banned') {
                        styleUser = 'class="disabled"';
                    }
                    href = '/UniRent/'+ user +'/publicProfile/' + review.username;
                    if (typeof postedReviews !== 'undefined' && postedReviews) {
                        if (review.reported) {
                            reportedStyle = 'style="opacity: 0.5;"';
                            reportedWarning = '<h1 class="reported-warning" style="color: red; font-weight: bold; margin-left: 10px;">Reported</h1>';
                        }
                        additionalButtons = '<div class="btn-cont2"><button class="delete_button" data-review-id="` + review.id + `">Delete</button><button class="edit_button" data-review-id="` + review.id + `">Edit</button></div>';
                        userSectionHTML = `
                            <p> To: </p>
                            <div ` + userStyle + `>
                                <a href=` + hrefLink + style + `><img src=` + review.userPicture + ` alt="User Profile Picture"></a>
                            </div>
                            <div class="username"><a href=` + hrefLink + style + `>` + review.username + `</a></div> <!-- Username of the reviewer -->
                            <p class="reviewRecipType">` + review.type + `</p>
                        `;
                    } else {
                        additionalButtons = '<div class="btn-cont2"><button class="delete_button" data-review-id="` + review.id + `">Report</button></div>';
                        userSectionHTML = `
                        <div class="userIcon">
                            <a href="` + href + `" `+ styleUser +`><img src=` + review.userPicture + ` alt="User Profile Picture"></a>
                        </div>
                        <div class="username"><a href="` + href + `" `+ styleUser +`>` + review.username + `</a></div> <!-- Username of the reviewer --`;
                    }
                }
                // Insert the names of the elements of the review array
                reviewElement.innerHTML = `
                <div class="row"` + reportedStyle + `>
                    <h1 class="ReviewTitle">` + review.title + `</h1> <!-- Title of the review -->
                    `+ reportedWarning + `
                    ` + additionalButtons +`
                </div>
                <div class="row">
                        <div class="userSection">
                            ` + userSectionHTML + `
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
displayReviews(reviews, user);