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
                  <!-- Nav Item - Messages -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-envelope fa-fw"></i>
                                <!-- Counter - Messages -->
                                <span class="badge badge-danger badge-counter" id="messageCount"></span>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdownWidth dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="messagesDropdown">
                                <h6 class="dropdown-header">
                                    Admin Replies
                                </h6>
                                <div id="messageList"></div>
                                
                                <a class="dropdown-item text-center smallMessages text-gray-500" href="/UniRent/SupportRequest/readMoreSupportReplies">Read More Replies</a>
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
        <p id="breadcrumb"></p>
    </div>
      <!-- header section end -->
      <!-- select box section start -->
      <div class="container-fluid">
         <div class="search_box_section">
            <div class="search_box_main padding-reserve">
            <form action="/UniRent/Student/search" method="post" id="yourFormId">
               <h1 class="find_text">Find an Accommodation</h1>
               <div class="row">
               <div class="Findcontainer">
                  <div class="select-outline">
                     <select name="city" id="citySelect" class="nice-select mdb-select md-form md-outline colorful-select dropdown-primary">
                        <option value="" disabled selected>Select a city</option>
                     </select>
                  </div>
               </div>
               </div>
               <div class="row">
               <div class="Findcontainer">
                  <div class="select-outline">
                     <select name="university" id="universitySelect" class="nice-select mdb-select md-form md-outline colorful-select dropdown-primary">
                        <option value="" disabled selected>Select a university</option>
                     </select>
                  </div>
               </div>
               </div>
               <div class="row">
               <div class="Findcontainer">
                  <div class="select-outline">
                     <select name="date" id="date" class="nice-select mdb-select md-form md-outline colorful-select dropdown-primary">
                        <option value="" disabled selected>Select a period</option>
                        <option value="september">September to June</option>
                        <option value="october">October to July</option>
                     </select>
                  </div>
               </div>
               </div>
               <h1 class="find_text padding-reserve">Ratings: </h1>
               
               <div class="Findcontainer">
               <div class="row">
               <h1 class="rating_text">Owner: </h1>
                  <div class="rating">
                     <input type="radio" id="star5O" name="rateO" value="5" />
                     <label for="star5O" title="5 stars">
                        <svg viewBox="0 0 576 512" height="1em" xmlns="http://www.w3.org/2000/svg" class="star-solid">
                              <path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"></path>
                        </svg>
                     </label>
                     <input type="radio" id="star4O" name="rateO" value="4" />
                     <label for="star4O" title="4 stars">
                        <svg viewBox="0 0 576 512" height="1em" xmlns="http://www.w3.org/2000/svg" class="star-solid">
                              <path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"></path>
                        </svg>
                     </label>
                     <input type="radio" id="star3O" name="rateO" value="3" />
                     <label for="star3O" title="3 stars">
                        <svg viewBox="0 0 576 512" height="1em" xmlns="http://www.w3.org/2000/svg" class="star-solid">
                              <path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"></path>
                        </svg>
                     </label>
                     <input type="radio" id="star2O" name="rateO" value="2" />
                     <label for="star2O" title="2 stars">
                        <svg viewBox="0 0 576 512" height="1em" xmlns="http://www.w3.org/2000/svg" class="star-solid">
                              <path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"></path>
                        </svg>
                     </label>
                     <input type="radio" id="star1O" name="rateO" value="1" />
                     <label for="star1O" title="1 star">
                        <svg viewBox="0 0 576 512" height="1em" xmlns="http://www.w3.org/2000/svg" class="star-solid">
                              <path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"></path>
                        </svg>
                     </label>
                     <input type="radio" id="star0O" name="rateO" value="0" hidden checked/>
                  </div>
               <button class="button-spec finalLittle"id="clearRatingO" onclick="clearRatingO()">Clear</button>
               </div>
               </div>
               <div class="Findcontainer">
               <div class="row">
               <h1 class="rating_text">Accommodation: </h1>
                  <div class="rating">
                     <input type="radio" id="star5A" name="rateA" value="5" />
                     <label for="star5A" title="5 stars">
                        <svg viewBox="0 0 576 512" height="1em" xmlns="http://www.w3.org/2000/svg" class="star-solid">
                              <path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"></path>
                        </svg>
                     </label>
                     <input type="radio" id="star4A" name="rateA" value="4" />
                     <label for="star4A" title="4 stars">
                        <svg viewBox="0 0 576 512" height="1em" xmlns="http://www.w3.org/2000/svg" class="star-solid">
                              <path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"></path>
                        </svg>
                     </label>
                     <input type="radio" id="star3A" name="rateA" value="3" />
                     <label for="star3A" title="3 stars">
                        <svg viewBox="0 0 576 512" height="1em" xmlns="http://www.w3.org/2000/svg" class="star-solid">
                              <path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"></path>
                        </svg>
                     </label>
                     <input type="radio" id="star2A" name="rateA" value="2" />
                     <label for="star2A" title="2 stars">
                        <svg viewBox="0 0 576 512" height="1em" xmlns="http://www.w3.org/2000/svg" class="star-solid">
                              <path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"></path>
                        </svg>
                     </label>
                     <input type="radio" id="star1A" name="rateA" value="1" />
                     <label for="star1A" title="1 star">
                        <svg viewBox="0 0 576 512" height="1em" xmlns="http://www.w3.org/2000/svg" class="star-solid">
                              <path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"></path>
                        </svg>
                     </label>
                     <input type="radio" id="star0A" name="rateA" value="0" hidden checked/>
                  </div>
               <button class="button-spec finalLittle"id="clearRatingA" onclick="clearRatingA()">Clear</button>
               </div>
               </div>
               <div class="row">
               <div class="Findcontainer">
               <h1 class="find_text">Price Range:</h1>
               <div class="d-flex">
                  <div class="wrapper">
                     <header>
                     <p>Use slider or enter min and max price</p>
                     </header>
                     <div class="price-input">
                     <div class="field">
                        <span>Min</span>
                        <input type="number" class="input-min" name="min-price" value="0">
                     </div>
                     <div class="separator">-</div>
                     <div class="field">
                        <span>Max</span>
                        <input type="number" class="input-max" name="max-price"value="1000">
                     </div>
                     </div>
                     <div class="slider">
                     <div class="progress"></div>
                     </div>
                     <div class="range-input">
                     <input type="range" class="range-min" min="0" max="1000" value="0" step="50">
                     <input type="range" class="range-max" min="0" max="1000" value="1000" step="50">
                     </div>
                  </div>
               </div>
               </div>
               </div>
               <div class="row">
               <div class="Findcontainer">
                  <div class="select-outline">
                     <div class="find_btn"><button type="submit">Find Now</button></div>
                  </div>
               </div>
               </div>
            </div>
         </div>
         </form>
      </div>
      <!-- feature section start -->
      <div class="Properties_section">
         <div class="Searchcontainer">
            <div class="row">
               <div class="col-sm-12">
                  <div class="Properties_taital_main">
                     <h1 class="Properties_taital">Search Results</h1>
                     <hr class="border_main">
                  </div>
               </div>
            </div>
            <div class="Properties_section_2 screenSize">
               <div class="row" id="resultContainer">
               </div>
            </div>
         </div>
      </div>
      
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
                     </ul>
                  </div>
               </div>
            </div>
         </div>
      </div>
      </div>
      <!-- footer section end -->
      <!-- Javascript files-->
      <script src="/UniRent/Smarty/js/jquery.min.js"></script>
      <script src="/UniRent/Smarty/js/popper.min.js"></script>
      <script src="/UniRent/Smarty/js/bootstrap.bundle.min.js"></script>
      <script src="/UniRent/Smarty/js/jquery-3.0.0.min.js"></script>
      <script src="/UniRent/Smarty/js/plugin.js"></script>
      <!-- sidebar -->
      <script src="/UniRent/Smarty/js/jquery.mCustomScrollbar.concat.min.js"></script>
      <script src="/UniRent/Smarty/js/custom.js"></script>
      <!-- javascript --> 
      <script>
         // Material Select Initialization
         $(document).ready(function() {
         $('.mdb-select').materialSelect();
         $('.select-wrapper.md-form.md-outline input.select-dropdown').bind('focus blur', function () {
         $(this).closest('.select-outline').find('label').toggleClass('active');
         $(this).closest('.select-outline').find('.caret').toggleClass('active');
         });
         });
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
         {literal}
<script>
    const results = {/literal}{$searchResult}{literal};

    // Function to create and append reviews to the container
    function displayResults(results) {
        const container = document.getElementById('resultContainer');

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
                                        <div class="some_text"><a href="/UniRent/Student/accommodation/${result.id}">See More</a></div>
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
    displayResults(results);
</script>
{/literal}
<script>
  document.addEventListener("DOMContentLoaded", function() {
    const rangeInput = document.querySelectorAll(".range-input input"),
          priceInput = document.querySelectorAll(".price-input input"),
          range = document.querySelector(".slider .progress");
    let priceGap = 1000;

    // Initialize default values from Smarty placeholders
    priceInput[0].value = {$minPrice};
    priceInput[1].value = {$maxPrice};
    rangeInput[0].value = {$minPrice};
    rangeInput[1].value = {$maxPrice};
    updateSliderPosition(); // Initial position update

    // Event listeners for range inputs
    rangeInput.forEach((input) => {
      input.addEventListener("input", () => {
        updatePriceInputs();
        updateSliderPosition();
      });
    });

    // Event listeners for price inputs
    priceInput.forEach((input) => {
      input.addEventListener("input", () => {
        updateRangeInputs();
        updateSliderPosition();
      });
    });

    // Function to update price inputs based on range inputs
    function updatePriceInputs() {
      let minVal = parseInt(rangeInput[0].value),
          maxVal = parseInt(rangeInput[1].value);
      priceInput[0].value = minVal;
      priceInput[1].value = maxVal;
    }

    // Function to update range inputs based on price inputs
    function updateRangeInputs() {
      let minPrice = parseInt(priceInput[0].value),
          maxPrice = parseInt(priceInput[1].value);
      rangeInput[0].value = minPrice;
      rangeInput[1].value = maxPrice;
    }

    // Function to update slider position based on range inputs
    function updateSliderPosition() {
      let minVal = parseInt(rangeInput[0].value),
          maxVal = parseInt(rangeInput[1].value);
      range.style.left = (minVal / rangeInput[0].max) * 100 + "%";
      range.style.right = 100 - (maxVal / rangeInput[1].max) * 100 + "%";
    }
  });
</script>
      <script>
    document.addEventListener("DOMContentLoaded", function() {
    const citySelect = document.getElementById("citySelect");
    const uniSelect = document.getElementById("universitySelect");
    const periodSelect = document.getElementById("date");

    // Initialize nice-select on page load
    $('select').niceSelect();

    // Get the default values from Smarty and properly escape them
    const defaultCity = {$selectedCity|json_encode};
    const defaultUniversity = {$selectedUni|json_encode};
    const defaultPeriod = {$selectedDate|json_encode};

    fetch("/UniRent/User/getCities")
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(subjectObject => {
        // Populate city dropdown
        for (let city in subjectObject) {
            let option = new Option(city, city);
            citySelect.add(option);
        }

        // Update nice-select after adding options
        $('select').niceSelect('update');

        // Set default selected option for city dropdown
        if (defaultCity && subjectObject[defaultCity]) {
            citySelect.value = defaultCity;

            // Populate university dropdown based on default city
            subjectObject[defaultCity].forEach(uniName => {
                let option = new Option(uniName, uniName);
                uniSelect.add(option);
            });

            // Update nice-select after adding options
            $('select').niceSelect('update');

            // Set default selected option for university dropdown
            if (defaultUniversity && subjectObject[defaultCity].includes(defaultUniversity)) {
                uniSelect.value = defaultUniversity;
            }
        }

        // Add event listener for city dropdown change
        citySelect.onchange = function() {
            const selectedCity = citySelect.value;

            // Clear the university dropdown
            uniSelect.length = 1;

            if (selectedCity && subjectObject[selectedCity]) {
                // Populate university dropdown
                subjectObject[selectedCity].forEach(uniName => {
                    let option = new Option(uniName, uniName);
                    uniSelect.add(option);
                });

                // Update nice-select after adding options
                $('select').niceSelect('update');

                // Set default selected option for university dropdown if applicable
                if (defaultUniversity && subjectObject[selectedCity].includes(defaultUniversity)) {
                    uniSelect.value = defaultUniversity;
                }
            }
        }

        // Set default selected option for period dropdown if applicable
        if (defaultPeriod) {
            periodSelect.value = defaultPeriod;
        }

        // Update nice-select after setting period
        $('select').niceSelect('update');

    })
    .catch(error => console.error('Error:', error));
});


</script>




    <script>
      function clearRatingO() {
    document.getElementById('star0O').checked = true;
}
function clearRatingA() {
    document.getElementById('star0A').checked = true;
}

// Ensure the DOM is fully loaded before attaching the event
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('clearRatingO').addEventListener('click', clearRatingO);
});
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('clearRatingA').addEventListener('click', clearRatingA);
});
</script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Set default rating values from Smarty placeholders
    const ratingOwner = {$ratingOwner};
    const ratingAccommodation = {$ratingAccommodation};

    // Set default rating for Owner
    if (ratingOwner) {
        document.getElementById('star' + ratingOwner + 'O').checked = true;
    }

    // Set default rating for Student
    if (ratingStudent) {
        document.getElementById('star' + ratingStudent + 'S').checked = true;
    }

    // Set default rating for Accommodation
    if (ratingAccommodation) {
        document.getElementById('star' + ratingAccommodation + 'A').checked = true;
    }

    // Function to clear Owner rating
    window.clearRatingO = function() {
        document.getElementById('star0O').checked = true;
    };

    // Function to clear Accommodation rating
    window.clearRatingA = function() {
        document.getElementById('star0A').checked = true;
    };
});
</script>
      
   <script src="/UniRent/Smarty/js/UniRentOriginal/cookie.js"></script>
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

<!-- Success Modal -->
<div class="resModal" id="successModal">
      <div class="resModal-content">
         <div class="row">
            <span class="resClose" id="successClose">&times;</span>
            <h2 class="resModal-head">
            {if $modalSuccess == 'success'}
            Success
            {else}
            Error
            {/if}
            </h2>
         </div>
         <div class="container cont-padding">
         {if $modalSuccess == 'success'}
            <h4>Operation completed successfully.</h4>
         {else}
            <h4>There was an error while processing. Please try again later.</h4>
         {/if}
                </div>
            <div class="btn-cont">
                <button type="button" class="edit_btn" id="closeSuccess">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End of Success Modal -->
<script src="/UniRent/Smarty/js/UniRentOriginal/supportReplyDropdown.js"></script>
<script>
var modalSuccess= '{$modalSuccess}';
var successModal = document.getElementById("successModal");
var successClose = document.getElementById("successClose");
var closeSuccess = document.getElementById("closeSuccess");
if (modalSuccess !== '') {
    successModal.style.display = "block";
} else {
    successModal.style.display = "none";
}
successClose.onclick = function() {
    successModal.style.display = "none";
    window.location.href = currentPage;
}

closeSuccess.onclick = function() {
    successModal.style.display = "none";
    window.location.href = currentPage;
}

window.onclick = function(event) {
    if (event.target == successModal) {
        successModal.style.display = "none";
        window.location.href = currentPage;
    }
}
</script>
</body>
</html>