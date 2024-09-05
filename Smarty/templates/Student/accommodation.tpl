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
                           <a class="dropdown-item" href="/UniRent/Reservation/showStudent/accepted">Accepted</a>
                           <a class="dropdown-item" href="/UniRent/Reservation/showStudent/pending">Pending</a>
                        </div>
                     </li>
                     <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Contracts</a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                           <a class="dropdown-item" href="/UniRent/Contract/showStudent/onGoing">Ongoing</a>
                           <a class="dropdown-item" href="/UniRent/Contract/showStudent/finished">Past</a>
                           <a class="dropdown-item" href="/UniRent/Contract/showStudent/future">Future</a>
                        </div>
                     </li>
                     <li class="nav-item">
                        <a class="nav-link" href="/UniRent/Student/postedReview">Posted Reviews</a>
                     </li>
                     <li class="nav-item">
                        <a class="nav-link" href = "/UniRent/Visit/visits">Visits</a>
                     </li>
                  </ul>
                  <!-- Nav Item - Support Replies -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-envelope fa-fw"></i>
                                <!-- Counter - Support Replies -->
                                <span class="badge badge-danger badge-counter" id="messageCount"></span>
                            </a>
                            <!-- Dropdown - Support Replies -->
                            <div class="dropdownWidth dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="messagesDropdown">
                                <h6 class="dropdown-header">
                                    Admin Replies
                                </h6>
                                <div id="messageList"></div>
                                
                                <a class="dropdown-item text-center smallSupport Replies text-gray-500" href="/UniRent/SupportRequest/readMoreSupportReplies">Read More Replies</a>
                            </div>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>
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
    <div class="path">
        <p id="breadcrumb" data-accommodation-name="{$accommodation->getTitle()}"></p>
    </div>
    <!-- header section end -->
    <!-- accommodation section start -->
      <div class="container-fluid screenSize">
      <div class="row">
         <div class="col-md-12">
            <div class="profile_text">
             <h1 class="accommodation_text">{$accommodation->getTitle()}</h1>
            </div>
         </div>
      </div>
      <div class="accImg">
      <div class="row">
                     <div class="accom_img">
                     <div class="container_main">
                                 <img src="/UniRent/Smarty/images/img-4.png" id="mainImage"alt="">
                                 <div class="overlay">
                                    <div class="text">
                                       <div class="some_text"><a href="#" id="openSlider">View Pictures</a></div>
                                    </div>
                                 </div>
                              </div>
                    {if $leavebleReviews>0}
                    <div class="reserve_btn" id="reviewButton"><a href="#" >Review</a></div>
                    {/if}
                     <div class="reserve_btn" id="reserveBtn"><a href="#" >Reserve</a></div>
                      <div class="reserve_btn" id="visitBtn"><a href="#" >Visit</a></div>
                      <div class="ownerSect">
                      <div class="row">
                      <h1 class="titleOwn">Owner:</h1>
                        <div class="userSection">
                                <div class="userIcon">
                                    {if $owner->getStatusString() === "banned"}
                                        <a href="/UniRent/Student/publicProfile/{$owner->getUsername()}" class="disabled"><img src="/UniRent/Smarty/images/BannedUser.png" class="imageIcon"></a>
                                        {else if $owner->getPhoto() === null}
                                        <a href="/UniRent/Student/publicProfile/{$owner->getUsername()}"><img src="/UniRent/Smarty/images/ImageIcon.png" class="imageIcon"></a>
                                    {else}
                                    {if $owner->getPhoto()->getPhoto() === null}
                                    <a href="/UniRent/Student/publicProfile/{$owner->getUsername()}"><img src="/UniRent/Smarty/images/ImageIcon.png" class="imageIcon"></a>
                                    {else}
                                    <a href="/UniRent/Student/publicProfile/{$owner->getUsername()}"><img src="{$owner->getPhoto()->getPhoto()}"></a>
                                    {/if}
                                    {/if}
                                </div>
                                {if $owner->getStatusString() === "banned"}
                                <div class="username"><a href="/UniRent/Student/publicProfile/{$owner->getUsername()}" class="disabled">{$owner->getUsername()}</a></div> <!-- Username of the owner -->
                                {else}
                                <div class="username"><a href="/UniRent/Student/publicProfile/{$owner->getUsername()}">{$owner->getUsername()}</a></div> <!-- Username of the owner -->
                                {/if}
                            </div>
                        </div>
                        </div>
                        <div class="reserve_btn" id="contactBtn"><a href="#" >Owner Contacts</a></div>
                     </div>
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
                        <h1 class="title1"> Reviews</h1>
                        <p>Average Rating: {$accommodation->getAverageRating()}</p>
                        </div>
                         <div id="reviewsContainer">
                   </div>
                     </div>
                     
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

<!-- Modal for contact information -->
<div id="contactModal" class="resModal">
  <div class="resModal-content">
  <div class="row">
    <span class="resClose" id="contactClose">&times;</span>
    <h2 class="resModal-head">Owner Contacts</h2>
    </div>
    <p>Phone: {$owner->getPhoneNumber()}</p>
    <p>Email: {$owner->getMail()}</p>
  </div>
</div>

<!-- Modal for reservation -->
<div id="reserveModal" class="resModal">
  <div class="resModal-content">
  <div class="row">
    <span class="resClose" id="reserveClose">&times;</span>
    <h2 class="resModal-head">Reserve this accommodation</h2>
    </div>
    <p> Note: You cannot choose the month you are staying because it is decided by the owner of the accommodation.</p>
    <form action="/UniRent/Student/reserveAccommodation/{$accommodation->getIdAccommodation()}" class="form" method="POST" enctype="multipart/form-data">
    <div class="grid-container">
    <div class="row padding-reserve">
        <p>Period:</p>
        </div>
        <div class="row padding-reserve">
            <select name="date" id="date" class="selectPeriod" disabled>
                        {if $period === "september"}
                        <option value="september" selected>September to June</option>
                        <option value="october">October to July</option>
                        {else}
                        <option value="september">September to June</option>
                        <option value="october" selected>October to July</option>
                        {/if}
            </select>
        
    </div>
    <div class="row padding-reserve">
        <p>Year:</p>
        </div>
        <div class="row padding-reserve">
            <select name="year" id="year" class="selectPeriod">
                <option value="" disabled selected>Select a year</option>
            </select>
        </div>
    </div>
     <div class="btn-cont">
        <button id="reserve" class="cancelClass" type="submit">Reserve</button>
        <button id="cancelReserve" class="confirmClass" type="button">Cancel</button>
    </div>
    </form>
  </div>
</div>
</div>
<!-- Modal for not reservable accommodation -->
<div id="notReservableModal" class="resModal">
    <div class="resModal-content">
        <span class="resClose" id="notReservableClose">&times;</span>
        <h2 class="resModal-head">You cannot reserve this accommodation</h2>
        <p>This accommodation does not accept any more reservations.</p>
        <button class="cancelClass" id="understoodReserve">Understood</button>
    </div>
</div>
<!-- Modal for not visitable accommodation -->
<div id="notVisitableModal" class="resModal">
    <div class="resModal-content">
        <span class="resClose" id="notVisitableClose">&times;</span>
        <h2 class="resModal-head">You cannot visit this accommodation</h2>
        <p>This accommodation does not accept any more visit requests.</p>
        <button class="cancelClass" id="understoodVisit">Understood</button>
    </div>
</div>
<!-- Modal for success reservation request -->
<div id="successReserveModal" class="resModal">
    <div class="resModal-content">
        <div class="row">
            <span class="resClose" id="successReserveClose">&times;</span>
            {if $successReserve === "sent"}
            <h2 class="resModal-head">Reservation Sent</h2>
            <div class="col-md-12">
            <p>Your reservation was successfully sent.</p>
            </div>
            {else if $successReserve === "full"}
            <h2 class="resModal-head">No more places available</h2>
            <p>Your reservation could not be sent due to full booking. Please choose a different period.</p>
            {else}
            <h2 class="resModal-head">Reservation Failed</h2>
            <div class="col-md-12">
              <p>Your reservation could not be sent. Please try again later.</p>
            </div>
            {/if}
        </div>
        <div class="btn-cont">
            <button id="closesuccessReserveModal" class="cancelClass" type="button">Close</button>
        </div>
    </div>
</div>

<!-- Modal for visit booking -->
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
            <select name="day" id="day" class="selectVisit" required>
                <option value="" selected disabled>Select a day</option>
                <!-- Day options will be populated here -->
            </select>
        </div>
      <div class="row padding-reserve">
        <p>Time:</p>
        </div>
        <div class="row padding-reserve">
          <select name="time" id="time"class="selectVisit" required>
            <option value="" selected disabled>Select the time</option>
            <!-- Time options will be populated here -->
          </select>
          
      </div>
        </div>
      <div class="btn-cont">
        <button id="visit" class="cancelClass" type="submit">Visit</button>
        <button id="cancelVisit" class="cancelClass" type="button">Cancel</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal for confirmation of booking another visit -->
<div class="resModal" id="confirmModal" style="display: none;">
  <div class="resModal-content">
    <div class="row">
      <span class="resClose" id="confirmClose">&times;</span>
      <h2 class="resModal-head">Are you sure you want to book another visit?</h2>
      <p>You already have one booked for {$day} at {$time} of the duration of {$duration} minutes.</p>
    </div>
    <div class="btn-cont">
      <button id="confirmBooking" class="confirmClass" type="button">Continue</button>
      <button id="cancelBooking" class="cancelClass" type="button">Cancel</button>
    </div>
  </div>
</div>

<!-- Modal for success visit request -->
<div id="successVisitModal" class="resModal">
    <div class="resModal-content">
        <div class="row">
            <span class="resClose" id="successVisitClose">&times;</span>
            {if $successVisit === "true"}
            <div class="col-md-12">
            <h2 class="resModal-head">Visit Request Sent</h2>
            </div>
            <p>Your visit request was successfully sent.</p>
            {else}
            <h2 class="resModal-head">Visit Request Failed to Send</h2>
            <p>Your visit request could not be sent. Please try again later.</p>
            {/if}
        </div>
        <div class="btn-cont">
            <button id="closeSuccessVisitModal" class="cancelClass" type="button">Close</button>
        </div>
    </div>
</div>

<!-- Modal for no possible visit slots -->
<div id="visitEmptyModal" class="resModal">
    <div class="resModal-content">
        <div class="row">
            <span class="resClose" id="visitEmptyClose">&times;</span>
            <h2 class="resModal-head">No possible visit slots</h2>
            <p>There are no available visit slots for this accommodation.</p>
        </div>
        <div class="btn-cont">
        <button id="closeVisitEmptyModal" class= "cancelClass" type="button" >Close</button>
        </div>
    </div>
</div>

<!-- Modal for review -->
<div id="revModal" class="resModal">
    <div class="resModal-content">
      <div class="row">
        <span class="resClose" id="revClose">&times;</span>
        <h1  class="resModal-head">Review</h1>
      </div>
        <form id="ReviewForm" action="/UniRent/Review/addReviewAccommodation/{$accommodation->getIdAccommodation()}" method="POST">
            <div class="rating">
                <input type="radio" id="star5A" name="rate" value="5" />
                <label for="star5A" title="5 stars">
                    <svg viewBox="0 0 576 512" height="1em" xmlns="http://www.w3.org/2000/svg" class="star-solid">
                        <path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"></path>
                    </svg>
                </label>
                <input type="radio" id="star4A" name="rate" value="4" />
                <label for="star4A" title="4 stars">
                    <svg viewBox="0 0 576 512" height="1em" xmlns="http://www.w3.org/2000/svg" class="star-solid">
                        <path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"></path>
                    </svg>
                </label>
                <input type="radio" id="star3A" name="rate" value="3" />
                <label for="star3A" title="3 stars">
                    <svg viewBox="0 0 576 512" height="1em" xmlns="http://www.w3.org/2000/svg" class="star-solid">
                        <path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"></path>
                    </svg>
                </label>
                <input type="radio" id="star2A" name="rate" value="2" />
                <label for="star2A" title="2 stars">
                    <svg viewBox="0 0 576 512" height="1em" xmlns="http://www.w3.org/2000/svg" class="star-solid">
                        <path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"></path>
                    </svg>
                </label>
                <input type="radio" id="star1A" name="rate" value="1" checked/>
                <label for="star1A" title="1 star">
                    <svg viewBox="0 0 576 512" height="1em" xmlns="http://www.w3.org/2000/svg" class="star-solid">
                        <path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"></path>
                    </svg>
                </label>
            </div>
            <input type="text" name="title" id="reviewTitle" placeholder="Title" value="" required>
            <textarea name="content" rows="5" id="reviewContent" placeholder="Content" required></textarea>
            <div class="btn-cont">
            <button type="submit" class="edit_btn">Submit</button>
            <button type="button" class="edit_btn" id="CancelBut">Cancel</button>
            </div>
      </form>
    </div>
</div>

<!-- Cookie Modal -->
<div class="modal" id="myModal">
      <div class"container-fluid">
      <div class="card">
         <svg xml:space="preserve" viewBox="0 0 122.88 122.25" y="0px" x="0px" id="cookieSvg" version="1.1"><g><path d="M101.77,49.38c2.09,3.1,4.37,5.11,6.86,5.78c2.45,0.66,5.32,0.06,8.7-2.01c1.36-0.84,3.14-0.41,3.97,0.95 c0.28,0.46,0.42,0.96,0.43,1.47c0.13,1.4,0.21,2.82,0.24,4.26c0.03,1.46,0.02,2.91-0.05,4.35h0v0c0,0.13-0.01,0.26-0.03,0.38 c-0.91,16.72-8.47,31.51-20,41.93c-11.55,10.44-27.06,16.49-43.82,15.69v0.01h0c-0.13,0-0.26-0.01-0.38-0.03 c-16.72-0.91-31.51-8.47-41.93-20C5.31,90.61-0.73,75.1,0.07,58.34H0.07v0c0-0.13,0.01-0.26,0.03-0.38 C1,41.22,8.81,26.35,20.57,15.87C32.34,5.37,48.09-0.73,64.85,0.07V0.07h0c1.6,0,2.89,1.29,2.89,2.89c0,0.4-0.08,0.78-0.23,1.12 c-1.17,3.81-1.25,7.34-0.27,10.14c0.89,2.54,2.7,4.51,5.41,5.52c1.44,0.54,2.2,2.1,1.74,3.55l0.01,0 c-1.83,5.89-1.87,11.08-0.52,15.26c0.82,2.53,2.14,4.69,3.88,6.4c1.74,1.72,3.9,3,6.39,3.78c4.04,1.26,8.94,1.18,14.31-0.55 C99.73,47.78,101.08,48.3,101.77,49.38L101.77,49.38z M59.28,57.86c2.77,0,5.01,2.24,5.01,5.01c0,2.77-2.24,5.01-5.01,5.01 c-2.77,0-5.01-2.24-5.01-5.01C54.27,60.1,56.52,57.86,59.28,57.86L59.28,57.86z M37.56,78.49c3.37,0,6.11,2.73,6.11,6.11 s-2.73,6.11-6.11,6.11s-6.11-2.73-6.11-6.11S34.18,78.49,37.56,78.49L37.56,78.49z M50.72,31.75c2.65,0,4.79,2.14,4.79,4.79 c0,2.65-2.14,4.79-4.79,4.79c-2.65,0-4.79-2.14-4.79-4.79C45.93,33.89,48.08,31.75,50.72,31.75L50.72,31.75z M119.3,32.4 c1.98,0,3.58,1.6,3.58,3.58c0,1.98-1.6,3.58-3.58,3.58s-3.58-1.6-3.58-3.58C115.71,34.01,117.32,32.4,119.3,32.4L119.3,32.4z M93.62,22.91c2.98,0,5.39,2.41,5.39,5.39c0,2.98-2.41,5.39-5.39,5.39c-2.98,0-5.39-2.41-5.39-5.39 C88.23,25.33,90.64,22.91,93.62,22.91L93.62,22.91z M97.79,0.59c3.19,0,5.78,2.59,5.78,5.78c0,3.19-2.59,5.78-5.78,5.78 c-3.19,0-5.78-2.59-5.78-5.78C92.02,3.17,94.6,0.59,97.79,0.59L97.79,0.59z M76.73,80.63c4.43,0,8.03,3.59,8.03,8.03 c0,4.43-3.59,8.03-8.03,8.03s-8.03-3.59-8.03-8.03C68.7,84.22,72.29,80.63,76.73,80.63L76.73,80.63z M31.91,46.78 c4.8,0,8.69,3.89,8.69,8.69c0,4.8-3.89,8.69-8.69,8.69s-8.69-3.89-8.69-8.69C23.22,50.68,27.11,46.78,31.91,46.78L31.91,46.78z M107.13,60.74c-3.39-0.91-6.35-3.14-8.95-6.48c-5.78,1.52-11.16,1.41-15.76-0.02c-3.37-1.05-6.32-2.81-8.71-5.18 c-2.39-2.37-4.21-5.32-5.32-8.75c-1.51-4.66-1.69-10.2-0.18-16.32c-3.1-1.8-5.25-4.53-6.42-7.88c-1.06-3.05-1.28-6.59-0.61-10.35 C47.27,5.95,34.3,11.36,24.41,20.18C13.74,29.69,6.66,43.15,5.84,58.29l0,0.05v0h0l-0.01,0.13v0C5.07,73.72,10.55,87.82,20.02,98.3 c9.44,10.44,22.84,17.29,38,18.1l0.05,0h0v0l0.13,0.01h0c15.24,0.77,29.35-4.71,39.83-14.19c10.44-9.44,17.29-22.84,18.1-38l0-0.05 v0h0l0.01-0.13v0c0.07-1.34,0.09-2.64,0.06-3.91C112.98,61.34,109.96,61.51,107.13,60.74L107.13,60.74z M116.15,64.04L116.15,64.04 L116.15,64.04L116.15,64.04z M58.21,116.42L58.21,116.42L58.21,116.42L58.21,116.42z"></path></g></svg>
         <p class="cookieHeading">We use cookies.</p>
         <p class="cookieDescription">We use cookies to ensure that we give you the best experience on our website. Please Activate Them.</p>
         </div> 
      </div>
      </div>
      <!-- End of Cookie Modal -->

      <!-- Report Review Modal -->
<div id="reportModalReview" class="resModal">
    <div class="resModal-content">
    <div class="row">
        <span class="resClose" onclick="cancelReportReview()">&times;</span>
        <h2 class="resModal-head">Report Review</h2>
    </div>
        <form id="reportFormReview" action="" class="form" method="POST" enctype="multipart/form-data">
            <label for="reportReasonReview">Reason for report:</label><br>
            <textarea id="reportReasonReview" name="reportReasonReview" rows="4" cols="50" oninput="checkInputReview()"></textarea><br><br>
            <div class="btn-cont">
                <button type="submit" id="confirmReportReview" class="disabled confirmClass" disabled>Submit</button>
                <button type="button" onclick="cancelReportReview()" class="cancelClass">Cancel</button>
            </div>
        </form>
    </div>
</div>
<!-- End of Report Review Modal -->

<!-- Request Detail Modal -->
<div class="resModal" id="replyModal">
      <div class="resModal-content">
         <div class="row">
            <span class="resClose" id="replyClose">&times;</span>
            <h2 class="resModal-head">Support Reply Details</h2>
         </div>
         <div class="container cont-padding">
         <h4>Your Request</h4>
                <p id="requestContent"></p>
                  <hr>
                  <h4>Admin Reply</h4>
                <p id="replyContent"></p>
                  <hr>
                  <h4>Topic</h4>
                <p id="requestTopic"></p>
                <hr>
                </div>
            <div class="btn-cont">
                <button type="button" class="edit_btn" id="closeReply">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End of Request Detail Modal -->

<!-- footer section start -->
      <div class="footer_section">
         <div class="container">
            <div class="row">
               <div class="col-md-4">
                  <h3 class="footer_text">About Us</h3>
                  <p class="lorem_text">Created in 2024, UniRent has revolutionized the way students find their home away from home. Connecting students with trusted landlords, UniRent ensures a seamless rental experience.</p>
               </div>
               <hr></hr>
               <div class="col-md-4">
                  <span class="lorem_text">Copyright &copy; UniRent 2024</span>
               </div>
               <div class="col-md-4">
                  <h3 class="footer_text">Useful Links</h3>
                  <div class="footer_menu">
                     <ul>
                        <li><a href="/UniRent/Student/home">Home</a></li>
                        <li><a href="/UniRent/Student/about">About Us</a></li>
                        <li><a href="/UniRent/Student/contact">Contact Us</a></li>
                        <li><a href="/UniRent/Student/guidelines">Guidelines</a></li>
                     </ul>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- footer section end -->
      <script>
        var defaultYear = {$selectedYear};
        var disabled = '{$disabled}';
        var timeSlots = {$timeSlots};
        var successVisit = '{$successVisit}';
        const images = {$imagesJson};
        const reviews = JSON.parse('{$reviewsData|json_encode|escape:"javascript"}');
        const user = 'Student';
        var tenants = {$tenantsJson};
        var numPlaces = {$num_places};
        var successReserve = '{$successReserve}';
        var booked = '{$booked}';
        var notReservable = '{$disabled}';
        var notVisitable = '{$notVisitable}';
        var accommodationPeriod = {$period} === "september" ? 8 : 9;
      </script>
      <script src="/UniRent/Smarty/js/jquery.min.js"></script>
      <script src="/UniRent/Smarty/js/popper.min.js"></script>
      <script src="/UniRent/Smarty/js/bootstrap.bundle.min.js"></script>
      <script src="/UniRent/Smarty/js/jquery-3.0.0.min.js"></script>
      <script src="/UniRent/Smarty/js/plugin.js"></script>
      <!-- sidebar -->
      <script src="/UniRent/Smarty/js/jquery.mCustomScrollbar.concat.min.js"></script>
      <script src="/UniRent/Smarty/js/custom.js"></script>
      <script src="/UniRent/Smarty/js/UniRentOriginal/cookie.js"></script>
      <script src="/UniRent/Smarty/js/UniRentOriginal/reviews.js"></script>
      <script src="/UniRent/Smarty/js/UniRentOriginal/modalHandling.js"></script>
      <script src="/UniRent/Smarty/js/UniRentOriginal/imagesSlider.js"></script>
      <script src="/UniRent/Smarty/js/UniRentOriginal/accommodationTenants.js"></script>
      <script src="/UniRent/Smarty/js/UniRentOriginal/supportReplyDropdown.js"></script>
</body>
</html>