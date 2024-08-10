function displayResults(results, user) {
    const container = document.getElementById('resultContainer');
    let href = '';
    if (user ==='User') {
        href='"#" onclick="showModal(event)"';
    } else if (user === 'Student') {
        href='"/UniRent/Student/accommodation/${result.id}"';
    }

    if (container) {
        if (results.length === 0) {
            container.innerHTML = '<div class="container"><h1 class="noRev">The search gave no result!</h1></div>';
        } else {
            results.forEach(result => {
                if (result.photo == null) {
                    result.photo = "/UniRent/Smarty/images/noPic.png";
                }
                const resultElement = document.createElement('div');
                resultElement.className = 'col-lg-4 col-md-6col-lg-4 col-md-6';

                // Insert the names of the elements of the result array
                resultElement.innerHTML = `
                    <div class="blog_img">
                        <div class="container_main">
                            <img src="${result.photo}" alt="">
                            <div class="overlay">
                                <div class="text">
                                    <div class="some_text"><a href=` + href + `>See More</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="image_box">
                        <div class="left_box">
                            <h1 class="road_text">${result.title}</h1>
                            <p>${result.address}</p>
                        </div>
                        <div class="right_box">
                            <div class="rate_text">${result.price} €</div>
                        </div>
                    </div>
                `;

                // Append the created element to the container
                container.appendChild(resultElement); // Corrected: resultElement instead of reviewElement
            });
        }
    } else {
        console.error("Container not found!"); // Debugging: Error if container is not found
    }
}

// Call the function to display reviews
displayResults(results, user);