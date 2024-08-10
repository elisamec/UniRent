// Function to create and append reviews to the container
function displayAccommodations(accommodations, user) {
    const container = document.getElementById('accommodationContainer');
    let href = '';
    if (user === 'User') {
        href='"#" onclick="showModal(event)"';
    } else if (user === 'Student') {
        href='"/UniRent/Student/accommodation/${accommodation.id}"';
    }

    if (container) {
        if (accommodations.length === 0) {
            container.innerHTML = '<div class="container"><h1 class="noRev">You have no ads yet!</h1></div>';
        } else {
            accommodations.forEach(accommodation => {
                if (accommodation.photo == null) {
                    accommodation.photo = "/UniRent/Smarty/images/noPic.png";
                }
                const accommodationElement = document.createElement('div');
                accommodationElement.className = 'col-lg-4 col-md-6col-lg-4 col-md-6';

                // Insert the names of the elements of the accommodation array
                accommodationElement.innerHTML = `
                    <div class="blog_img">
                        <div class="container_main">
                            <img src="${accommodation.photo}" alt="">
                            <div class="overlay">
                                <div class="text">
                                    <div class="some_text"><a href=`+ href +`>See More</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="image_box">
                        <div class="left_box">
                            <h1 class="road_text">${accommodation.title}</h1>
                            <p>${accommodation.address}</p>
                        </div>
                        <div class="right_box">
                            <div class="rate_text">${accommodation.price} €</div>
                        </div>
                    </div>
                `;

                // Append the created element to the container
                container.appendChild(accommodationElement); // Corrected: accommodationElement instead of reviewElement
            });
        }
    } else {
        console.error("Container not found!"); // Debugging: Error if container is not found
    }
}

// Function to create and append accommodations to the container
function displayAccommodationsOwner(accommodationsActive, accommodationsInactive) {
    const containerOwner = document.getElementById('accommodationContainer');
    let classDisplay = "";
    let imageClass = "";

    if (containerOwner) {

        // Combine both active and inactive accommodations
        const allAccommodations = [...accommodationsActive, ...accommodationsInactive];

        if (allAccommodations.length === 0) {
            containerOwner.innerHTML = '<div class="container"><h1 class="noRev">You have no ads yet!</h1></div>';
        } else {
            allAccommodations.forEach(accomodationOwner => {
                if (accomodationOwner.photo == null) {
                    accomodationOwner.photo = "/UniRent/Smarty/images/noPic.png";
                }

                if (accommodationsActive.includes(accomodationOwner)) {
                        classDisplay = "image_box";
                        imageClass = "blog_img";
                } else {
                        classDisplay = "image_box grey";
                        imageClass = "blog_img grey";
                }

                // Create HTML for each accommodation
                const accommodationElementOwner = document.createElement('div');
                accommodationElementOwner.className = 'col-lg-4 col-md-6';

                accommodationElementOwner.innerHTML = `
                    <div class="${imageClass}">
                        <div class="container_main">
                            <img src="${accomodationOwner.photo}" alt="">
                            <div class="overlay">
                                <div class="text">
                                    <div class="some_text"><a href="/UniRent/Owner/accommodationManagement/${accomodationOwner.id}">See More</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="${classDisplay}">
                        <div class="left_box">
                            <h1 class="road_text">${accomodationOwner.title}</h1>
                            <p>${accomodationOwner.address}</p>
                        </div>
                        <div class="right_box">
                            <div class="rate_text">${accomodationOwner.price} €</div>
                        </div>
                    </div>
                `;

                // Append the created element to the container
                containerOwner.appendChild(accommodationElementOwner);
            });
        }
    } else {
        console.error("Container not found!");
    }
}
if (user === 'Owner') {
    displayAccommodationsOwner(accommodationsActive, accommodationsInactive);
} else {
// Call the function to display reviews
displayAccommodations(accommodations, user);
}