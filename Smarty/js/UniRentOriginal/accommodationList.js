// Function to create and append reviews to the container
function displayAccommodations(accommodations) {
  const container = document.getElementById("accommodationContainer");
  if (file == "OwnerAds") {
    var emptyContainer = "no ads";
    var expireHTML = "";
  } else if (file == "studentReservations") {
    var emptyContainer = "no reservations";
    var expire = "";

    if (kind == "accepted") {
      var expire = "You have " + accommodation.expires + " left to pay";
    } else {
      var expire =
        "The owner has " +
        accommodation.expires +
        " before it gets automatically accepted";
    }
    var expireHTML = `<p>${accommodation.period}</p>
                      <p>${expire}</p>`;
  }

  if (container) {
    if (accommodations.length === 0) {
      container.innerHTML =
        '<div class="container"><h1 class="noRev">You have ' +
        emptyContainer +
        " yet!</h1></div>";
    } else {
      accommodations.forEach((accommodation) => {
        if(file == "studentReservations") {
          var href = 'href ="/UniRent/Reservation/reservationDetails/' + accommodation.idReservation + '"';
          } else if (file == "OwnerAds") {
            var href = 'href="/UniRent/OwnerAds/accommodation/'+ accommodation.id + '"'; 
          }
        if (accommodation.photo == null) {
          accommodation.photo = "/UniRent/Smarty/images/noPic.png";
        }
        const accommodationElement = document.createElement("div");
        accommodationElement.className = "col-lg-4 col-md-6col-lg-4 col-md-6";

        // Insert the names of the elements of the accommodation array
        accommodationElement.innerHTML = `
                        <div class="blog_img">
                            <div class="container_main">
                                <img src="${accommodation.photo}" alt="">
                                <div class="overlay">
                                    <div class="text">
                                        <div class="some_text"><a ` + href + `>See More</a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="image_box">
                            <div class="left_box">
                                <h1 class="road_text">${accommodation.title}</h1>
                                <p>${accommodation.address}</p>
                                ` + expireHTML + `
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

// Call the function to display reviews
displayAccommodations(accommodations);