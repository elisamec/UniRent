<!DOCTYPE html>
<html>
   <head>
      <!-- basic -->
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <!-- mobile metas -->
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="viewport" content="initial-scale=1, maximum-scale=1">
      <!-- site metas -->
      <title>UniRent</title>
      <link rel="icon" href="/UniRent/Smarty/images/favicon.png" type="image/png">
      <meta name="keywords" content="">
      <meta name="description" content="">
      <meta name="author" content="">
      <!-- bootstrap css -->
      <link rel="stylesheet" type="text/css" href="/UniRent/Smarty/css/bootstrap.min.css">
      <!-- style css -->
      <link rel="stylesheet" type="text/css" href="/UniRent/Smarty/css/style.css">
      <!-- Responsive-->
      <link rel="stylesheet" href="/UniRent/Smarty/css/responsive.css">
      <!-- fevicon -->
      <link rel="icon" href="/UniRent/Smarty/images/fevicon.png" type="image/gif" />
      <!-- font css -->
      <link href="https://fonts.googleapis.com/css2?family=Sen:wght@400;700;800&display=swap" rel="stylesheet">
      <!-- Scrollbar Custom CSS -->
      <link rel="stylesheet" href="/UniRent/Smarty/css/jquery.mCustomScrollbar.min.css">
      <!-- Tweaks for older IEs-->
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.2/css/fontawesome.min.css">
      <!-- Include Font Awesome -->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
      <link rel="stylesheet" type="text/css" href="/UniRent/Smarty/css/home.css">
      <link\ rel="stylesheet" type="text/css" href="/UniRent/Smarty/css/cookie.css">
      <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    
   </head>
   <body onload="on()">
      <div class="header_section">
        <div class="container-fluid">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
               <a class="navbar-brand"href="/UniRent/Student/home"><img src="/UniRent/Smarty/images/logo.png"></a>
               <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
               <span class="navbar-toggler-icon"></span>
               </button>
               <div class="collapse navbar-collapse" id="navbarSupportedContent">
                  <ul class="navbar-nav ml-auto">
                     <li class="nav-item">
                        <a class="nav-link" href="/UniRent/Student/home">Home</a>
                     </li>
                     <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Reservations</a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                           <a class="dropdown-item" href="#">Accepted</a>
                           <a class="dropdown-item" href="#">Waiting</a>
                        </div>
                     </li>
                     <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Contracts</a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                           <a class="dropdown-item" href="#">Ongoing</a>
                           <a class="dropdown-item" href="#">Past</a>
                           <a class="dropdown-item" href="#">Future</a>
                        </div>
                     </li>
                     <li class="nav-item">
                        <a class="nav-link" href="/UniRent/Student/postedReview">Posted Reviews</a>
                     </li>
                     <li class="nav-item">
                        <a class="nav-link" href = "#">Visits</a>
                     </li>
                  </ul>
                  <form class="form-inline my-2 my-lg-0">
                     <div class="login_bt">
                        <ul>
                           <li><a href="/UniRent/Student/profile"><span class="user_icon"><i class="fa fa-user" aria-hidden="true"></i></span>Profile</a></li>
                        </ul>
                     </div>
                  </form>
               </div>
            </nav>
         </div>
      </div>
    <!-- header section end -->
    <!-- accommodation section start -->
      <div class="container-fluid">
      <div class="row">
         <div class="col-md-12">
            <div class="profile_text">
             <h1 class="accommodation_text">{$accommodation->getTitle()}</h1>
            </div>
         </div>
      </div>
      <div class="accImg">
      <div class="row">
      <div class="col-lg-4 col-md-6col-lg-4 col-md-6">
                     <div class="accom_img">
                     <div class="container_main">
                                 <img src="/UniRent/Smarty/images/img-4.png" id="mainImage"alt="">
                                 <div class="overlay">
                                    <div class="text">
                                       <div class="some_text"><a href="#" id="openSlider">View Pictures</a></div>
                                    </div>
                                 </div>
                              </div>

                     <div class="reserve_btn" id="reserveBtn"><a href="#" >Reserve</a></div>
                      <div class="reserve_btn" id="visitBtn"><a href="#" >Visit</a></div>
                      <div class="ownerSect">
                      <div class="row">
                      <h1 class="titleOwn">Owner:</h1>
                        <div class="userSection">
                                <div class="userIcon">
                                    {if $owner->getPhoto() === null}
                                        <a href="/UniRent/Student/publicProfile/{$owner->getUsername()}"><img src="/UniRent/Smarty/images/ImageIcon.png" class="imageIcon"></a>
                                    {else}
                                    <a href="/UniRent/Student/publicProfile/{$owner->getUsername()}"><img src="{$owner->getPhoto()->getPhoto()}"></a>
                                    {/if}
                                </div>
                                <div class="username"><a href="/UniRent/Student/publicProfile/{$owner->getUsername()}">{$owner->getUsername()}</a></div> <!-- Username of the owner -->
                            </div>
                        </div>
                        </div>
                        <div class="reserve_btn" id="contactBtn"><a href="#" >Owner Contacts</a></div>
                     </div>
                    </div>
                     <div class="col-lg-8 col-md-10 col-lg-8 col-md-10">
                     <div class="Accomcontainer">
                        <h1 class="title">Accommodation Details</h1>
                        <div class="row">
                        {if $accommodation->getPets() && $accommodation->getSmokers()}
                        <div class="allowed"> <img src="/UniRent/Smarty/images/Pets.png" alt="Pets Allowed"> </div>
                        <div class="allowed"> <img id="smoke" src="/UniRent/Smarty/images/Smokers.png" alt="Smokers Allowed"> </div>
                        {elseif $accommodation->getPets()}
                         <div class="allowed"> <img src="/UniRent/Smarty/images/Pets.png" alt="Pets Allowed"> </div>
                        <div class="notallowed"> <img id="smoke" src="/UniRent/Smarty/images/Smokers.png" alt="Smokers Not Allowed"> </div>
                        {elseif $accommodation->getSmokers()}
                         <div class="notallowed"> <img src="/UniRent/Smarty/images/Pets.png" alt="Pets Not Allowed"> </div>
                        <div class="allowed"> <img id="smoke" src="/UniRent/Smarty/images/Smokers.png" alt="Smokers Allowed"> </div>
                        {else}
                         <div class="notallowed"> <img src="/UniRent/Smarty/images/Pets.png" alt="Pets Not Allowed"> </div>
                        <div class="notallowed"> <img id="smoke" src="/UniRent/Smarty/images/Smokers.png" alt="Smokers Not Allowed"> </div>
                        {/if}
                        </div>
                        <h2>Location:</h2>
                        {if $accommodation->getAddress()->getAddressLine1() !== null && $accommodation->getAddress()->getLocality() !== null}
                        <p> {$accommodation->getAddress()->getAddressLine1()}, {$accommodation->getAddress()->getLocality()}</p>
                        {else}
                        <p>Address not available</p>
                        {/if}
                        <h2> Description:</h2>
                        <p>{$accommodation->getDescription()}</p>
                        <h2>Monthly Price: {$accommodation->getPrice()} €</h2>
                        {if $accommodation->getDeposit() !== null}
                        <h2>Deposit: {$accommodation->getDeposit()} €</h2>
                        {/if}
                        <h1 class="title">Current Tenants</h1>
                        <div class="row" id="tenantCont">
                        </div>

                        <div class="row">
                        <h1 class="title"> Reviews</h1>
                        <p>Average Rating: {$accommodation->getAverageRating()}</p>
                        </div>
                         <div id="reviewsContainer">
                   </div>
                     </div>
                     
                  </div>
                  </div>
                    <div class="col-lg-4 col-md-6col-lg-4 col-md-6">
                    
                    </div>
      </div>


      <div id="sliderContainer" class="hidden">
        <div id="slider">
            <span id="closeSlider">&times;</span>
            <div class="slider-content">
                <!-- Images will be inserted here dynamically -->
            </div>
            <a class="prev">&#10094;</a>
            <a class="next">&#10095;</a>
        </div>
    </div>

<div id="contactModal" class="resModal">
  <div class="resModal-content">
  <div class="row">
    <span class="resClose">&times;</span>
    <h2 class="resModal-head">Owner Contacts</h2>
    </div>
    <p>Phone: {$owner->getPhoneNumber()}</p>
    <p>Email: {$owner->getMail()}</p>
  </div>
</div>

<script>
    // Get the modal
    var modal = document.getElementById("contactModal");

    // Get the button that opens the modal
    var btn = document.getElementById("contactBtn");

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("resClose")[0];

    // When the user clicks the button, open the modal 
    btn.onclick = function(event) {
        event.preventDefault();
        modal.style.display = "block";
    }

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>

<div id="reserveModal" class="resModal">
  <div class="resModal-content">
  <div class="row">
    <span class="resClose" id="reserveClose">&times;</span>
    <h2 class="resModal-head">Reserve this accommodation</h2>
    </div>
    <form action="/UniRent/Student/reserveAccommodation" class="form" method="POST" enctype="multipart/form-data">
    <div class="row padding-reserve">
        <p>Period:</p>
        <div class="col-lg-6 select-outline">
            <select name="date" id="date" class="mdb-select md-form md-outline colorful-select dropdown-primary">
                        {if $period === "september"}
                        <option value="september" selected>September to June</option>
                        <option value="october">October to July</option>
                        {else}
                        <option value="september">September to June</option>
                        <option value="october" selected>October to July</option>
                        {/if}
            </select>
         </div>
    </div>
     <div class="btn-cont">
        <button id="reserve" type="submit">Reserve</button>
        <button id="cancelReserve" type="button">Cancel</button>
    </div>
    </form>
  </div>
</div>
</div>
<script>
    // Get the modal
    var modal = document.getElementById("reserveModal");

    // Get the button that opens the modal
    var btn = document.getElementById("reserveBtn");

    // Get the <span> element that closes the modal
    var span = document.getElementById("reserveClose");

    var cancelBtn = document.getElementById("cancelReserve");

    // When the user clicks the button, open the modal 
    btn.onclick = function(event) {
        event.preventDefault();
        modal.style.display = "block";
    }

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }
    cancelBtn.onclick = function() {
        modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>
<div class="resModal" id="visitModal">
  <div class="resModal-content">
    <div class="row">
      <span class="resClose" id="visitClose">&times;</span>
      <h2 class="resModal-head">Visit this accommodation</h2>
      <p> Each visit is scheduled for {$duration} minutes. Please select a day and time for your visit.</p>
    </div>
    <form action="/UniRent/Visit/studentRequest/{$accommodation->getIdAccommodation()}" class="formVisit" method="POST" enctype="multipart/form-data">
      <div class="grid-container">
      <div class="row padding-reserve">
        <p>Day of the week (next week):</p>
        </div>
      <div class="row padding-reserve">
          <select name="day" id="day" class="selectVisit">
          <option value="" selected disabled>Select a day</option>
            <option value="Monday">Monday</option>
            <option value="Tuesday">Tuesday</option>
            <option value="Wednesday">Wednesday</option>
            <option value="Thursday">Thursday</option>
            <option value="Friday">Friday</option>
          </select>
      </div>
      <div class="row padding-reserve">
        <p>Time:</p>
        </div>
        <div class="row padding-reserve">
          <select name="time" id="time"class="selectVisit">
            <option value="" selected disabled>Select the time</option>
            <!-- Time options will be populated here -->
          </select>
          
      </div>
        </div>
      <div class="btn-cont">
        <button id="visit" type="submit">Visit</button>
        <button id="cancelVisit" type="button">Cancel</button>
      </div>
    </form>
  </div>
</div>
<div class="resModal" id="confirmModal" style="display: none;">
  <div class="resModal-content">
    <div class="row">
      <span class="resClose" id="confirmClose">&times;</span>
      <h2 class="resModal-head">Are you sure you want to book another visit?</h2>
      <p>You already have one booked for {$day} at {$time} of the duration of {$duration} minutes.</p>
    </div>
    <div class="btn-cont">
      <button id="confirmBooking" type="button">Continue</button>
      <button id="cancelBooking" type="button">Cancel</button>
    </div>
  </div>
</div>
{literal}
<script>
  // Get the modal elements
  var visitModal = document.getElementById("visitModal");
  var confirmModal = document.getElementById("confirmModal");

  // Get the button that opens the visit modal
  var visitBtn = document.getElementById("visitBtn");

  // JSON data for time slots
  var timeSlots = {/literal}{$timeSlots}{literal};

  // When the user clicks the button to open the visit modal
  visitBtn.onclick = function(event) {
    event.preventDefault();

    // Check if already booked
    if ({/literal}{$booked}{literal}) {
      // Show confirmation modal
      confirmModal.style.display = "block";
    } else {
      // Show visit modal
      visitModal.style.display = "block";
    }
  }

  // Close visit modal
  document.getElementById("visitClose").onclick = function() {
    visitModal.style.display = "none";
  }
  document.getElementById("cancelVisit").onclick = function() {
    visitModal.style.display = "none";
  }

  // Close confirmation modal
  document.getElementById("confirmClose").onclick = function() {
    confirmModal.style.display = "none";
  }

  // Cancel booking action
  document.getElementById("cancelBooking").onclick = function() {
    confirmModal.style.display = "none";
  }

  // Continue booking action
  document.getElementById("confirmBooking").onclick = function() {
    confirmModal.style.display = "none";
    visitModal.style.display = "block"; // Show visit modal after confirmation
  }

  // Populate time slots based on the selected day
  document.getElementById("day").addEventListener("change", function() {
    var selectedDay = this.value;
    var timeSelect = document.getElementById("time");
    
    // Clear previous options
    timeSelect.innerHTML = '<option value="" selected disabled>Select the time</option>';
    // Populate new options
     if (timeSlots[selectedDay] && typeof timeSlots[selectedDay] === 'object') {
    // Iterate over the keys of timeSlots[selectedDay]
    Object.keys(timeSlots[selectedDay]).forEach(function(key) {
      var option = document.createElement("option");
      option.value = timeSlots[selectedDay][key]; // Use the value corresponding to the key
      option.textContent = timeSlots[selectedDay][key]; // Use the same value for textContent
      timeSelect.appendChild(option);
    });
  } else {
    console.error("timeSlots[selectedDay] is not defined or is not an object.");
    // Handle the case where timeSlots[selectedDay] is not as expected (optional)
  }
  });

  // Trigger the change event on page load to populate the initial time slots
  document.getElementById("day").dispatchEvent(new Event('change'));
</script>
{/literal}




<!-- footer section start -->
      <div class="footer_section layout_padding">
         <div class="container">
            <div class="row">
               <div class="col-md-4">
                  <h3 class="footer_text">About Us</h3>
                  <p class="lorem_text">Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web</p>
               </div>
               <hr></hr>
               <div class="col-md-4">
                  <h3 class="footer_text">Useful Links</h3>
                  <div class="footer_menu">
                     <ul>
                        <li><a href="/UniRent/Student/home">Home</a></li>
                        <li><a href="/UniRent/Student/about">About Us</a></li>
                        <li><a href="/UniRent/Student/contact">Contact Us</a></li>
                     </ul>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- footer section end -->
<script src="/UniRent/Smarty/js/jquery.min.js"></script>
      <script src="/UniRent/Smarty/js/popper.min.js"></script>
      <script src="/UniRent/Smarty/js/bootstrap.bundle.min.js"></script>
      <script src="/UniRent/Smarty/js/jquery-3.0.0.min.js"></script>
      <script src="/UniRent/Smarty/js/plugin.js"></script>
      <!-- sidebar -->
      <script src="/UniRent/Smarty/js/jquery.mCustomScrollbar.concat.min.js"></script>
      <script src="/UniRent/Smarty/js/custom.js"></script>


      <div class="modal" id="myModal">
      <div class"container-fluid">
      <div class="card">
         <svg xml:space="preserve" viewBox="0 0 122.88 122.25" y="0px" x="0px" id="cookieSvg" version="1.1"><g><path d="M101.77,49.38c2.09,3.1,4.37,5.11,6.86,5.78c2.45,0.66,5.32,0.06,8.7-2.01c1.36-0.84,3.14-0.41,3.97,0.95 c0.28,0.46,0.42,0.96,0.43,1.47c0.13,1.4,0.21,2.82,0.24,4.26c0.03,1.46,0.02,2.91-0.05,4.35h0v0c0,0.13-0.01,0.26-0.03,0.38 c-0.91,16.72-8.47,31.51-20,41.93c-11.55,10.44-27.06,16.49-43.82,15.69v0.01h0c-0.13,0-0.26-0.01-0.38-0.03 c-16.72-0.91-31.51-8.47-41.93-20C5.31,90.61-0.73,75.1,0.07,58.34H0.07v0c0-0.13,0.01-0.26,0.03-0.38 C1,41.22,8.81,26.35,20.57,15.87C32.34,5.37,48.09-0.73,64.85,0.07V0.07h0c1.6,0,2.89,1.29,2.89,2.89c0,0.4-0.08,0.78-0.23,1.12 c-1.17,3.81-1.25,7.34-0.27,10.14c0.89,2.54,2.7,4.51,5.41,5.52c1.44,0.54,2.2,2.1,1.74,3.55l0.01,0 c-1.83,5.89-1.87,11.08-0.52,15.26c0.82,2.53,2.14,4.69,3.88,6.4c1.74,1.72,3.9,3,6.39,3.78c4.04,1.26,8.94,1.18,14.31-0.55 C99.73,47.78,101.08,48.3,101.77,49.38L101.77,49.38z M59.28,57.86c2.77,0,5.01,2.24,5.01,5.01c0,2.77-2.24,5.01-5.01,5.01 c-2.77,0-5.01-2.24-5.01-5.01C54.27,60.1,56.52,57.86,59.28,57.86L59.28,57.86z M37.56,78.49c3.37,0,6.11,2.73,6.11,6.11 s-2.73,6.11-6.11,6.11s-6.11-2.73-6.11-6.11S34.18,78.49,37.56,78.49L37.56,78.49z M50.72,31.75c2.65,0,4.79,2.14,4.79,4.79 c0,2.65-2.14,4.79-4.79,4.79c-2.65,0-4.79-2.14-4.79-4.79C45.93,33.89,48.08,31.75,50.72,31.75L50.72,31.75z M119.3,32.4 c1.98,0,3.58,1.6,3.58,3.58c0,1.98-1.6,3.58-3.58,3.58s-3.58-1.6-3.58-3.58C115.71,34.01,117.32,32.4,119.3,32.4L119.3,32.4z M93.62,22.91c2.98,0,5.39,2.41,5.39,5.39c0,2.98-2.41,5.39-5.39,5.39c-2.98,0-5.39-2.41-5.39-5.39 C88.23,25.33,90.64,22.91,93.62,22.91L93.62,22.91z M97.79,0.59c3.19,0,5.78,2.59,5.78,5.78c0,3.19-2.59,5.78-5.78,5.78 c-3.19,0-5.78-2.59-5.78-5.78C92.02,3.17,94.6,0.59,97.79,0.59L97.79,0.59z M76.73,80.63c4.43,0,8.03,3.59,8.03,8.03 c0,4.43-3.59,8.03-8.03,8.03s-8.03-3.59-8.03-8.03C68.7,84.22,72.29,80.63,76.73,80.63L76.73,80.63z M31.91,46.78 c4.8,0,8.69,3.89,8.69,8.69c0,4.8-3.89,8.69-8.69,8.69s-8.69-3.89-8.69-8.69C23.22,50.68,27.11,46.78,31.91,46.78L31.91,46.78z M107.13,60.74c-3.39-0.91-6.35-3.14-8.95-6.48c-5.78,1.52-11.16,1.41-15.76-0.02c-3.37-1.05-6.32-2.81-8.71-5.18 c-2.39-2.37-4.21-5.32-5.32-8.75c-1.51-4.66-1.69-10.2-0.18-16.32c-3.1-1.8-5.25-4.53-6.42-7.88c-1.06-3.05-1.28-6.59-0.61-10.35 C47.27,5.95,34.3,11.36,24.41,20.18C13.74,29.69,6.66,43.15,5.84,58.29l0,0.05v0h0l-0.01,0.13v0C5.07,73.72,10.55,87.82,20.02,98.3 c9.44,10.44,22.84,17.29,38,18.1l0.05,0h0v0l0.13,0.01h0c15.24,0.77,29.35-4.71,39.83-14.19c10.44-9.44,17.29-22.84,18.1-38l0-0.05 v0h0l0.01-0.13v0c0.07-1.34,0.09-2.64,0.06-3.91C112.98,61.34,109.96,61.51,107.13,60.74L107.13,60.74z M116.15,64.04L116.15,64.04 L116.15,64.04L116.15,64.04z M58.21,116.42L58.21,116.42L58.21,116.42L58.21,116.42z"></path></g></svg>
         <p class="cookieHeading">We use cookies.</p>
         <p class="cookieDescription">We use cookies to ensure that we give you the best experience on our website. Please Activate Them.</p>
         </div> 
      </div>
      </div>
    <script>
            function on() {
            if (!navigator.cookieEnabled) {
               document.getElementById("myModal").style.display = "flex";
            }
            }
            function off() {
               document.getElementById("myModal").style.display = "none";
               }
         </script>
         <script>
        // Assign the JSON data to a JavaScript variable
        const images = {$imagesJson};
    </script>
    {literal}
   <script>
   document.addEventListener('DOMContentLoaded', () => {
            const openSliderLink = document.getElementById('openSlider');
            const sliderContainer = document.getElementById('sliderContainer');
            const closeSliderBtn = document.getElementById('closeSlider');
            const sliderContent = document.querySelector('.slider-content');
            const mainImage = document.getElementById('mainImage');
            const overlay = document.querySelector('.overlay');
            let currentIndex = 0;

            // Display the first image in the first container if available
            if (images.length > 0) {
                mainImage.src = images[0];
            } else {
                // Hide the overlay if no images are available
                overlay.style.display = 'none';
                // Set the default image
                mainImage.src = '/UniRent/Smarty/images/noPic.png';
            }

            openSliderLink.addEventListener('click', (event) => {
                event.preventDefault();  // Prevent the default anchor behavior
                if (images.length > 0) {
                    sliderContainer.classList.remove('hidden');
                    showImage(currentIndex);
                }
            });

            closeSliderBtn.addEventListener('click', () => {
                sliderContainer.classList.add('hidden');
            });

            document.querySelector('.prev').addEventListener('click', () => {
                currentIndex = (currentIndex > 0) ? currentIndex - 1 : images.length - 1;
                showImage(currentIndex);
            });

            document.querySelector('.next').addEventListener('click', () => {
                currentIndex = (currentIndex < images.length - 1) ? currentIndex + 1 : 0;
                showImage(currentIndex);
            });

            function showImage(index) {
                sliderContent.innerHTML = `<img src="${images[index]}" alt="Image ${index + 1}">`;
            }
        });

</script>
{/literal}
 <script>
    {if isset($reviewsData)}
    const reviews = JSON.parse('{$reviewsData|json_encode|escape:"javascript"}');

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
                container.innerHTML = '<div class="container"><h1 class="noRev">There are no reviews yet!</h1></div>';
            } else {
                reviews.forEach(review => {
                    const reviewElement = document.createElement('div');
                    reviewElement.className = 'review';

                    // Insert the names of the elements of the review array
                    reviewElement.innerHTML = `
                        <h1 class="ReviewTitle">` + review.title + `</h1> <!-- Title of the review -->
                        <div class="row">
                            <div class="userSection">
                                <div class="userIcon">
                                    <a href="/UniRent/Student/publicProfile/` + review.username + `"><img src=` + review.userPicture + ` alt="User Profile Picture"></a>
                                </div>
                                <div class="username"><a href="/UniRent/Student/publicProfile/` + review.username + `">` + review.username + `</a></div> <!-- Username of the reviewer -->
                            </div>
                            <div class="col-md-11">
                                <div class="stars">
                                    ` + generateStars(review.stars) + ` <!-- Star rating -->
                                </div>
                                <p>` + review.content + `</p> <!-- Content of the review -->
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
    {/if}
</script>
{literal}
      <script>
    // Retrieve Smarty variables (assuming they are passed as JSON strings)
    var tenants = {/literal}{$tenantsJson}{literal};
    var numPlaces = {/literal}{$num_places}{literal};

    // Get the container element
    var tenantContainer = document.getElementById('tenantCont');

    // Function to create tenant section
    function createTenantSection(username, expiryDate, profilePic) {
        
        return `
            <div class="col-md-4">
                <div class="userSection">
                    <div class="userIcon">
                        <a href="/UniRent/Owner/publicProfile/${username}"><img src="${profilePic}" alt="User Profile Picture"></a>
                    </div>
                    <div class="username"><a href="/UniRent/Owner/publicProfile/${username}">${username}</a></div>
                    <div class="username">Expiry Date: ${expiryDate}</div>
                </div>
            </div>
        `;
    }

    // Function to create free space section
    function createFreeSpaceSection() {
        return `
            <div class="col-md-4">
                <div class="userSection">
                    <div class="userIcon">
                        <a><img src="/UniRent/Smarty/images/FreeBadge.png" alt="Free Badge"></a>
                    </div>
                    <div class="username"></div>
                </div>
            </div>
        `;
    }

    // Generate tenant sections
    for (var i = 0; i < tenants.length; i++) {
        var tenant = tenants[i];
        tenantContainer.innerHTML += createTenantSection(tenant.username, tenant.expiryDate, tenant.profilePic);
    }

    // Fill remaining places with free space sections
    for (var j = tenants.length; j < numPlaces; j++) {
        tenantContainer.innerHTML += createFreeSpaceSection();
    }
</script>
{/literal}
   </body>
