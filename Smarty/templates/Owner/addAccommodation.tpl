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
      <link rel="stylesheet" type="text/css" href="/UniRent/Smarty/css/cookie.css">
      <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

   </head>
   <body onload="on()">
      <div class="header_section">
        <div class="container-fluid">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
               <a class="navbar-brand"href="/UniRent/owner/home"><img src="/UniRent/Smarty/images/logo.png"></a>
               <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
               <span class="navbar-toggler-icon"></span>
               </button>
               <div class="collapse navbar-collapse" id="navbarSupportedContent">
                  <ul class="navbar-nav ml-auto">
                     <li class="nav-item">
                        <a class="nav-link" href="/UniRent/Owner/home">Home</a>
                     </li>
                     <li class="nav-item">
                        <a class="nav-link" href="#">Reservations</a>
                     </li>
                     <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">My Tenants</a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                           <a class="dropdown-item" href="#">Current</a>
                           <a class="dropdown-item" href="#">Past</a>
                           <a class="dropdown-item" href="#">Future</a>
                        </div>
                     <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Contracts</a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                           <a class="dropdown-item" href="#">Ongoing</a>
                           <a class="dropdown-item" href="#">Past</a>
                           <a class="dropdown-item" href="#">Future</a>
                        </div>
                     </li>
                     <li class="nav-item">
                        <a class="nav-link" href="#">Posted Reviews</a>
                     </li>
                     <li class="nav-item">
                        <a class="nav-link" href = "#">Visits</a>
                     </li>
                  </ul>
                  <form class="form-inline my-2 my-lg-0">
                     <div class="login_bt">
                        <ul>
                           <li><a href="/UniRent/Owner/profile"><span class="user_icon"><i class="fa fa-user" aria-hidden="true"></i></span>Profile</a></li>
                        </ul>
                     </div>
                  </form>
               </div>
            </nav>
         </div>
      </div>
      
      <div class="addAccom">
      <div class="container">
      <div class="Properties_taital_main">
                     <h1 class="Properties_taital">Add Accommodation</h1>
                     <hr class="border_main">
                  </div>
                    <form action="/UniRent/Owner/addAccommodation" class="form" method="post" id="yourFormId">
                        <div id="cssportal-grid">
                           <div id="div1">
                              <div class="pictures">
                                 <input class="file-upload" type="file" id="img" name="img" accept="image/png" multiple hidden>
                                 <label class="picture_btn" for="img">Upload Pictures</label>
                              </div>
                           </div>
                           <div id="div2">
                              <div class="input-group">
                                 <input required="" type="text" name="title" autocomplete="off" class="input-spec">
                                 <label class="user-label">Title</label>
                              </div>
                           </div>
                           <div id="div3">
                              <div class="input-group">
                                 <input required="" type="number" name="price" autocomplete="off" class="input-spec">
                                 <label class="user-label">Price (€)</label>
                              </div>
                           </div>
                           <div id="div4">
                              <div class="input-group">
                                 <input required="" type="number" name="deposit" autocomplete="off" class="input-spec">
                                 <label class="user-label">Deposit (€)</label>
                              </div>
                           </div>
                           <div id="div5">
                              <div class="input-group">
                                 <input id="Date" required="" type="text" name="startDate" autocomplete="off" class="input-spec">
                                 <label class="user-label">Start date</label>
                              </div>
                           </div>
                           <div id="div6">
                              <div class="input-group">
                                 <input id="Date2" required="" type="text" name="endDate" autocomplete="off" class="input-spec">
                                 <label class="user-label">End date</label>
                              </div>
                           </div>
                           <div id="div7">
                              <div class="input-group">
                                 <input required="" type="text" name="address" autocomplete="off" class="input-spec">
                                 <label class="user-label">Address</label>
                              </div>
                           </div>
                           <div id="div8">
                              <div class="button-group">
                                 <button type="button" class="button-spec" onclick="openModal()">
                                 Visit Availability
                                 </button>
                              </div>
                           </div>
                           <div id="div9">
                           <div class="input-group">
                                 <input required="" type="text" name="city" autocomplete="off" class="input-spec">
                                 <label class="user-label">City</label>
                              </div>
                           </div>
                            <div id="div10">
                            <div class="input-group">
                                 <input required="" type="number" name="postalCode" autocomplete="off" class="input-spec">
                                 <label class="user-label">Postal Code</label>
                              </div>
                           </div>
                            <div id="div12"> 
                            <div class="input-group">
    <textarea class="textarea-spec" rows="5" id="comment" name="Message" required></textarea>
    <label class="textarea-label" for="comment">Description</label>
</div>
</div>
                            <div id="div13">
                            <div class="checkbox-container">
                            <label class="checkbox-label">Requirements:</label>
                            <label>
                                 <input type="checkbox" name="men"> Men
                           </label>
                              <label>
                                 <input type="checkbox" name="women" > Women
                              </label>
                              <label>
                                 <input type="checkbox" name="animals"> Animals
                              </label>
                              <label>
                                 <input type="checkbox" name="smokers"> Smokers
                              </label>
                              </div>
                            </div>
                            <div id="div14">
                            <div class="input-group">
    <textarea class="textarea1-spec" rows="5" id="comment" name="Message" placeholder=" "></textarea>
    <label class="textarea-label" for="comment">Notes</label>
</div>

                            </div>
                            <div id="div15">
                              <input type="hidden" id="durationHidden" name="duration">
                              <input type="hidden" id="startTimeHidden" name="startTime">
                              <input type="hidden" id="dayOfWeekHidden" name="dayOfWeek">

                           <div class="button-group">
                              <div class="row">
                                 <div class="col-md-6">
                                  <button class="button-spec final" type="submit">Submit</button>
                                 </div>
                                 <div class="col-md-6">
                                 <button type="button" class="button-spec final" onclick="window.location.href='/UniRent/Owner/home'">Cancel</button>
                                 </div>
                              </div>
                           </div>
                           </div>
                           </div>
                        </div>
                        </form>

<!-- Visit availability pop-up -->
<div id="popup" class="avModal">
  <div class="avModal-content">
    <form id="visitAvailabilityForm">
        <h2 class="avModal-head">Visit Availability</h2>
        <p> Insert the visit duration, the day for which the availability is set, and the start and end time of the availability. Based on the duration, the appointments will be calculated inside the time window you are indicating. </p>
        <div id="availabilityContainer">
            <div class="availability">
                
            </div>
        </div>
        <button type="button" onclick="addAvailability()" class="button-spec little1">+</button>
        <div class="column">
        <button type="submit" class="button-spec final">Conferma</button>
        <button type="button" onclick="closeModal()" class="button-spec final">Annulla</button>
         </div>
    </form>
  </div>
</div>
                    <script>
// Get the modal
var avModal = document.getElementById("popup");
function openModal() {
  avModal.style.display = "block";
}

// Add more availability input fields
function addAvailability() {
    let container = document.getElementById('availabilityContainer');
    let availability = document.createElement('div');
    availability.className = 'availability';
    availability.innerHTML = `
         <button type="button" onclick="removeAvailability(this)" class="button-spec little">-</button>
                <label for="duration">Visit Duration (minutes):</label>
                <input type="number" id="duration" name="duration" title="Please enter a number">
                <label for="dayOfWeek">Weekday:</label>
                <input type="text" pattern="(Monday|Tuesday|Wednesday|Thursday|Friday|Saturday|Sunday)" title="Please enter a valid day of the week">
                <label for="time">Availability start:</label>
                <input type="time" id="start" name="start">
                <label for="time">Availability end:</label>
                <input type="time" id="end" name="end">
                
    `;
    container.appendChild(availability);
}

// Remove an availability input field
function removeAvailability(button) {
    let availability = button.parentNode;
    availability.parentNode.removeChild(availability);
}

// Save visit availability data in sessionStorage when the pop-up form is submitted
let form = document.getElementById('visitAvailabilityForm');
form.addEventListener('submit', function(event) {
    event.preventDefault();

    let availabilities = document.getElementsByClassName('availability');
    let data = [];
    for (let i = 0; i < availabilities.length; i++) {
        let duration = availabilities[i].elements['duration'].value;
        let day = availabilities[i].elements['day'].value;
        let time = availabilities[i].elements['time'].value;
        data.push({duration, day, time});
    }

    sessionStorage.setItem('availabilities', JSON.stringify(data));

    fetch('/your-server-endpoint', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
    })
    .then(response => response.json())
    .then(data => console.log('Success:', data))
    .catch((error) => console.error('Error:', error));

    closeModal();
});
function closeModal() {
    avModal.style.display = "none";
}
</script>

               </div>
               
            </div>
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
                        <li><a href="/UniRent/Owner/home">Home</a></li>
                        <li><a href="/UniRent/Owner/about">About Us</a></li>
                        <li><a href="/UniRent/Owner/contact">Contact Us</a></li>
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

 
</script>
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
document.addEventListener('DOMContentLoaded', function() {
    // Assicurati che gli ID corrispondano ai tuoi elementi HTML
    var smokerCheckbox = document.getElementById('smoker');
    var animalsCheckbox = document.getElementById('animals');
    var hiddenSmoker = document.getElementById('hiddenSmoker');
    var hiddenAnimals = document.getElementById('hiddenAnimals');

    if(smokerCheckbox && hiddenSmoker) {
        smokerCheckbox.onclick = function() {
            hiddenSmoker.value = this.checked ? 'true' : 'false';
        };
    }

    if(animalsCheckbox && hiddenAnimals) {
        animalsCheckbox.onclick = function() {
            hiddenAnimals.value = this.checked ? 'true' : 'false';
        };
    }
});
</script>
<script>
document.querySelector('#Date').addEventListener('focus', function (e) {
    this.type = 'date';
});

document.querySelector('#Date').addEventListener('blur', function (e) {
    this.type = 'text';
});
document.querySelector('#Date2').addEventListener('focus', function (e) {
    this.type = 'date';
});

document.querySelector('#Date2').addEventListener('blur', function (e) {
    this.type = 'text';
});
</script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>



<script>
$(function() {
    var daysOfWeek = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
    $("#dayOfWeek").autocomplete({
        source: daysOfWeek
    });
});
</script>

   </body>
