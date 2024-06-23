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
   </head>
   <body>
      <div class="header_section">
        <div class="container-fluid">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
               <a class="navbar-brand"href="/UniRent/Student/home"><img src="/UniRent/Smarty/images/logo.png"></a>
               <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
               <span class="navbar-toggler-icon"></span>
               </button>
               <div class="collapse navbar-collapse" id="navbarSupportedContent">
                  <ul class="navbar-nav ml-auto">
                     <li class="nav-item active">
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
                        <a class="nav-link" href="#">Posted Reviews</a>
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
      <!-- select box section start -->
      <div class="container-fluid">
         <div class="search_box_section">
            <div class="search_box_main">
               <h1 class="find_text">Find an Accommodation</h1>
               <div class="row">
               <div class="Findcontainer">
                  <div class="select-outline">
                     <select name="city" id="city" class="mdb-select md-form md-outline colorful-select dropdown-primary">
                        <option value="" disabled selected>Select a city</option>
                        <option value="City 1">City 1</option>
                        <option value="City 2">City 1</option>
                     </select>
                  </div>
               </div>
               </div>
               <div class="row">
               <div class="Findcontainer">
                  <div class="select-outline">
                     <select name="university" id="university" class="mdb-select md-form md-outline colorful-select dropdown-primary">
                        <option value="" disabled selected>Select a university</option>
                     </select>
                  </div>
               </div>
               </div>
               <div class="row">
               <div class="Findcontainer">
                  <div class="select-outline">
                     <select name="date" id="date" class="mdb-select md-form md-outline colorful-select dropdown-primary">
                        <option value="" disabled selected>Select a period</option>
                        <option value="september">September to June</option>
                        <option value="october">October to July</option>
                     </select>
                  </div>
               </div>
               </div>
               <h1 class="find_text">Ratings: </h1>
               
               <div class="Findcontainer">
               <div class="row">
               <h1 class="rating_text">Owner: </h1>
                  <div class="rating">
                     <input type="radio" id="star5O" name="rateO" value="5" />
                     <label for="star5O" title="text"
                        ><svg
                           viewBox="0 0 576 512"
                           height="1em"
                           xmlns="http://www.w3.org/2000/svg"
                           class="star-solid"
                        >
                           <path
                           d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"
                           ></path></svg
                     ></label>
                     <input type="radio" id="star4O" name="rateO" value="4" />
                     <label for="star4O" title="text"
                        ><svg
                           viewBox="0 0 576 512"
                           height="1em"
                           xmlns="http://www.w3.org/2000/svg"
                           class="star-solid"
                        >
                           <path
                           d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"
                           ></path></svg
                     ></label>
                     <input checked="" type="radio" id="star3O" name="rateO" value="3" />
                     <label for="star3O" title="text"
                        ><svg
                           viewBox="0 0 576 512"
                           height="1em"
                           xmlns="http://www.w3.org/2000/svg"
                           class="star-solid"
                        >
                           <path
                           d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"
                           ></path></svg
                     ></label>
                     <input type="radio" id="star2O" name="rateO" value="2" />
                     <label for="star2O" title="text"
                        ><svg
                           viewBox="0 0 576 512"
                           height="1em"
                           xmlns="http://www.w3.org/2000/svg"
                           class="star-solid"
                        >
                           <path
                           d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"
                           ></path></svg
                     ></label>
                     <input type="radio" id="star1O" name="rateO" value="1" />
                     <label for="star1O" title="text"
                        ><svg
                           viewBox="0 0 576 512"
                           height="1em"
                           xmlns="http://www.w3.org/2000/svg"
                           class="star-solid"
                        >
                           <path
                           d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"
                           ></path></svg
                     ></label>
                     </div>
               </div>
               </div>
               <div class="Findcontainer">
               <div class="row">
               <h1 class="rating_text">Student: </h1>
                  <div class="rating">
                     <input type="radio" id="star5S" name="rateS" value="5" />
                     <label for="star5S" title="text"
                        ><svg
                           viewBox="0 0 576 512"
                           height="1em"
                           xmlns="http://www.w3.org/2000/svg"
                           class="star-solid"
                        >
                           <path
                           d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"
                           ></path></svg
                     ></label>
                     <input type="radio" id="star4S" name="rateS" value="4" />
                     <label for="star4S" title="text"
                        ><svg
                           viewBox="0 0 576 512"
                           height="1em"
                           xmlns="http://www.w3.org/2000/svg"
                           class="star-solid"
                        >
                           <path
                           d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"
                           ></path></svg
                     ></label>
                     <input checked="" type="radio" id="star3S" name="rateS" value="3" />
                     <label for="star3S" title="text"
                        ><svg
                           viewBox="0 0 576 512"
                           height="1em"
                           xmlns="http://www.w3.org/2000/svg"
                           class="star-solid"
                        >
                           <path
                           d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"
                           ></path></svg
                     ></label>
                     <input type="radio" id="star2S" name="rateS" value="2" />
                     <label for="star2S" title="text"
                        ><svg
                           viewBox="0 0 576 512"
                           height="1em"
                           xmlns="http://www.w3.org/2000/svg"
                           class="star-solid"
                        >
                           <path
                           d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"
                           ></path></svg
                     ></label>
                     <input type="radio" id="star1S" name="rateS" value="1" />
                     <label for="star1S" title="text"
                        ><svg
                           viewBox="0 0 576 512"
                           height="1em"
                           xmlns="http://www.w3.org/2000/svg"
                           class="star-solid"
                        >
                           <path
                           d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"
                           ></path></svg
                     ></label>
                     </div>
               </div>
               </div>
               <div class="Findcontainer">
               <div class="row">
               <h1 class="rating_text">Accommodation: </h1>
                  <div class="rating">
                     <input type="radio" id="star5A" name="rateA" value="5" />
                     <label for="star5A" title="text"
                        ><svg
                           viewBox="0 0 576 512"
                           height="1em"
                           xmlns="http://www.w3.org/2000/svg"
                           class="star-solid"
                        >
                           <path
                           d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"
                           ></path></svg
                     ></label>
                     <input type="radio" id="star4A" name="rateA" value="4" />
                     <label for="star4A" title="text"
                        ><svg
                           viewBox="0 0 576 512"
                           height="1em"
                           xmlns="http://www.w3.org/2000/svg"
                           class="star-solid"
                        >
                           <path
                           d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"
                           ></path></svg
                     ></label>
                     <input checked="" type="radio" id="star3A" name="rateA" value="3" />
                     <label for="star3A" title="text"
                        ><svg
                           viewBox="0 0 576 512"
                           height="1em"
                           xmlns="http://www.w3.org/2000/svg"
                           class="star-solid"
                        >
                           <path
                           d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"
                           ></path></svg
                     ></label>
                     <input type="radio" id="star2A" name="rateA" value="2" />
                     <label for="star2A" title="text"
                        ><svg
                           viewBox="0 0 576 512"
                           height="1em"
                           xmlns="http://www.w3.org/2000/svg"
                           class="star-solid"
                        >
                           <path
                           d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"
                           ></path></svg
                     ></label>
                     <input type="radio" id="star1A" name="rateA" value="1" />
                     <label for="star1A" title="text"
                        ><svg
                           viewBox="0 0 576 512"
                           height="1em"
                           xmlns="http://www.w3.org/2000/svg"
                           class="star-solid"
                        >
                           <path
                           d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"
                           ></path></svg
                     ></label>
                     </div>
               </div>
               </div>
               <div class="row">
               <div class="Findcontainer">
                  <div class="select-outline">
                     <div class="find_btn"><a href="#">Find Now</a></div>
                  </div>
               </div>
               </div>
            </div>
         </div>
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
            <div class="Properties_section_2">
               <div class="row">
                  <div class="col-lg-4 col-md-6col-lg-4 col-md-6">
                     <div class="blog_img"><img src="/UniRent/Smarty/images/img-4.png"></div>
                     <div class="image_box">
                        <div class="left_box">
                           <h1 class="road_text">2186 Lohariya Road</h1>
                           <div class="area_main">
                              <h3 class="area_text active"><a href="#">Area:<br>240m2</a></h3>
                              <h3 class="area_text"><a href="#">Beds:<br>3</a></h3>
                              <h3 class="area_text"><a href="#">Baths:<br>1</a></h3>
                              <h3 class="area_text"><a href="#">Garages:<br>1</a></h3>
                           </div>
                        </div>
                        <div class="right_box">
                           <div class="rate_text">$14000</div>
                        </div>
                     </div>
                  </div>
                  <div class="col-lg-4 col-md-6">
                     <div class="blog_img"><img src="/UniRent/Smarty/images/img-5.png"></div>
                     <div class="image_box">
                        <div class="left_box">
                           <h1 class="road_text">2186 Lohariya Road</h1>
                           <div class="area_main">
                              <h3 class="area_text active"><a href="#">Area:<br>240m2</a></h3>
                              <h3 class="area_text"><a href="#">Beds:<br>3</a></h3>
                              <h3 class="area_text"><a href="#">Baths:<br>1</a></h3>
                              <h3 class="area_text"><a href="#">Garages:<br>1</a></h3>
                           </div>
                        </div>
                        <div class="right_box">
                           <div class="rate_text">$14000</div>
                        </div>
                     </div>
                  </div>
                  <div class="col-lg-4 col-md-6">
                     <div class="blog_img"><img src="/UniRent/Smarty/images/img-6.png"></div>
                     <div class="image_box">
                        <div class="left_box">
                           <h1 class="road_text">2186 Lohariya Road</h1>
                           <div class="area_main">
                              <h3 class="area_text active"><a href="#">Area:<br>240m2</a></h3>
                              <h3 class="area_text"><a href="#">Beds:<br>3</a></h3>
                              <h3 class="area_text"><a href="#">Baths:<br>1</a></h3>
                              <h3 class="area_text"><a href="#">Garages:<br>1</a></h3>
                           </div>
                        </div>
                        <div class="right_box">
                           <div class="rate_text">$14000</div>
                        </div>
                     </div>
                  </div>
                  <div class="col-lg-4 col-md-6">
                     <div class="blog_img"><img src="/UniRent/Smarty/images/img-7.png"></div>
                     <div class="image_box">
                        <div class="left_box">
                           <h1 class="road_text">2186 Lohariya Road</h1>
                           <div class="area_main">
                              <h3 class="area_text active"><a href="#">Area:<br>240m2</a></h3>
                              <h3 class="area_text"><a href="#">Beds:<br>3</a></h3>
                              <h3 class="area_text"><a href="#">Baths:<br>1</a></h3>
                              <h3 class="area_text"><a href="#">Garages:<br>1</a></h3>
                           </div>
                        </div>
                        <div class="right_box">
                           <div class="rate_text">$14000</div>
                        </div>
                     </div>
                  </div>
                  <div class="col-lg-4 col-md-6">
                     <div class="blog_img"><img src="/UniRent/Smarty/images/img-8.png"></div>
                     <div class="image_box">
                        <div class="left_box">
                           <h1 class="road_text">2186 Lohariya Road</h1>
                           <div class="area_main">
                              <h3 class="area_text active"><a href="#">Area:<br>240m2</a></h3>
                              <h3 class="area_text"><a href="#">Beds:<br>3</a></h3>
                              <h3 class="area_text"><a href="#">Baths:<br>1</a></h3>
                              <h3 class="area_text"><a href="#">Garages:<br>1</a></h3>
                           </div>
                        </div>
                        <div class="right_box">
                           <div class="rate_text">$14000</div>
                        </div>
                     </div>
                  </div>
                  <div class="col-lg-4 col-md-6">
                     <div class="blog_img"><img src="/UniRent/Smarty/images/img-9.png"></div>
                     <div class="image_box">
                        <div class="left_box">
                           <h1 class="road_text">2186 Lohariya Road</h1>
                           <div class="area_main">
                              <h3 class="area_text active"><a href="#">Area:<br>240m2</a></h3>
                              <h3 class="area_text"><a href="#">Beds:<br>3</a></h3>
                              <h3 class="area_text"><a href="#">Baths:<br>1</a></h3>
                              <h3 class="area_text"><a href="#">Garages:<br>1</a></h3>
                           </div>
                        </div>
                        <div class="right_box">
                           <div class="rate_text">$14000</div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      
      <!-- footer section start -->
      <div class="footer_section layout_padding">
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <div class="location_text">
                     <ul>
                        <li>
                           <a href="#"><i class="fa fa-map-marker" aria-hidden="true"></i></a>
                        </li>
                        <li>
                           <a href="#"><i class="fa fa-phone" aria-hidden="true"></i></a>
                        </li>
                        <li>
                           <a href="#"><i class="fa fa-envelope" aria-hidden="true"></i></a>
                        </li>
                     </ul>
                  </div>
               </div>
               <div class="social_icon">
                     <ul>
                        <li><a href="#"><i class="fab fa-facebook" aria-hidden="true"></i></a></li>
                        <li><a href="#"><i class="fab fa-twitter" aria-hidden="true"></i></a></li>
                        <li><a href="#"><i class="fab fa-linkedin" aria-hidden="true"></i></a></li>
                     </ul>
                  </div>
            </div>
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
                        <li class="active"><a href="/UniRent/User/home">Home</a></li>
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Contact Us</a></li>
                     </ul>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- footer section end -->
      <!-- copyright section start -->
      <div class="copyright_section">
         <div class="container">
            <p class="copyright_text">2020 All Rights Reserved. Design by <a href="https://html.design">Free Html Templates</a></p>
         </div>
      </div>
      <!-- copyright section end -->
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
      <script>
         var universities = {
            'City 1': ['Uni 1.1', 'Uni 1.2', 'Uni 1.3'],
            'City 2': ['Uni 2.1', 'Uni 2.2'],
            'City 3': ['Uni 3.1', 'Uni 3.2']
            // Add other cities and universities as needed
        };

        var $universities = $('#university');
        $('#city').change(function() {
            var city = $(this).val();
            var universityList = universities[city] || [];

            var html = $.map(universityList, function(university) {
                return '<option value="' + university + '">' + university + '</option>';
            }).join('');
            $universities.html(html);
        });
      </script>
   </body>
</html>