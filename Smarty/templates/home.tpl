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
               <a class="navbar-brand"href="/UniRent/User/home"><img src="/UniRent/Smarty/images/logo.png"></a>
               <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
               <span class="navbar-toggler-icon"></span>
               </button>
               <div class="collapse navbar-collapse" id="navbarSupportedContent">
                  <ul class="navbar-nav ml-auto">
                     <li class="nav-item active">
                        <a class="nav-link" href="/UniRent/User/home">Home</a>
                     </li>
                     <li class="nav-item">
                        <a class="nav-link" href="#">About Us</a>
                     </li>
                     <li class="nav-item">
                        <a class="nav-link" href="#">Contact Us</a>
                     </li>
                  </ul>
                  <form class="form-inline my-2 my-lg-0">
                     <div class="login_bt">
                        <ul>
                           <li><a href="/UniRent/User/login"><span class="user_icon"><i class="fa fa-user" aria-hidden="true"></i></span>Login</a></li>
                        </ul>
                     </div>
                  </form>
               </div>
            </nav>
         </div>
      <!-- banner section start --> 
      <div class="banner_section layout_padding">
         <div class="container">
            <div id="carouselExampleSlidesOnly" class="carousel slide" data-ride="carousel">
               <div class="carousel-inner">
                  <div class="carousel-item active">
                     <div class="row">
                        <div class="col-sm-12">
                           <h1 class="banner_taital">Find an Accommodation</h1>
                           <p class="banner_text">Search among thousands of different accommodations the one that works best for you!</p>
         
                        </div>
                     </div>
                  </div>
                  <div class="carousel-item">
                     <div class="row">
                        <div class="col-sm-12">
                           <h1 class="banner_taital">Choose your city</h1>
                           <p class="banner_text">Search basing on your city of interest, to find what's best for you!</p>
                           
                        </div>
                     </div>
                  </div>
                  <div class="carousel-item">
                     <div class="row">
                        <div class="col-sm-12">
                           <h1 class="banner_taital">Made for Students</h1>
                           <p class="banner_text">We help students find accommodations and owners to rent to students only.</p>
                           
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- banner section end -->
      </div>
      <!-- header section end -->
      <!-- select box section start -->
      <div class="container">
         <div class="select_box_section">
            <div class="select_box_main">
               <h1 class="find_text">Find an Accommodation</h1>
               <div class="row">
                  <div class="col-lg-3 select-outline">
                     <select name="city" id="city" class="mdb-select md-form md-outline colorful-select dropdown-primary">
                     <option value="" disabled selected>Select a city</option>
                     <option value="City 1">City 1</option>
                     <option value="City 2">City 1</option>
                     </select>
                     </div>
                     <div class="col-lg-3 select-outline">
                     <select name="university" id="university" class="mdb-select md-form md-outline colorful-select dropdown-primary">
                        <option value="" disabled selected>Select a university</option>
                     </select>
                     </div>
                     <div class="col-lg-3 datepicker" data-mdb-inline="true">
                     <input type="date" class="form-control" id="exampleDatepicker1" data-mdb-toggle="datepicker">
                  </div>
                  <div class="col-lg-3 select-outline">
                     <div class="find_btn"><a href="#">Find Now</a></div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- feature section start -->
      <div class="Properties_section layout_padding">
         <div class="container">
            <div class="row">
               <div class="col-sm-12">
                  <div class="Properties_taital_main">
                     <h1 class="Properties_taital">New Properties In Milan</h1>
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
      <!-- Properties section start -->
      <div class="Properties_section layout_padding">
         <div class="container">
            <div class="row">
               <div class="col-sm-12">
                  <div class="Properties_taital_main">
                     <h1 class="Properties_taital">New Properties In Rome</h1>
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
      <!-- Properties section end -->
      <!-- customers section start -->
      <div class="customer_section layout_padding">
         <div class="container">
            <div class="row">
               <div class="col-sm-12">
                  <div class="customer_taital_main">
                     <h1 class="customer_taital">SATISFIED CLIENT Says</h1>
                     <hr class="customer_border_main">
                  </div>
               </div>
            </div>
         </div>
         <div id="my_slider" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
               <div class="carousel-item active">
                  <div class="customer_section_2">
                     <div class="container">
                        <div class="row">
                           <div class="col-md-12">
                              <div class="box_main">
                                 <div class="customer_main">
                                    <div class="customer_left">
                                       <div class="customer_img"><img src="/UniRent/Smarty/images/customer-img.png"></div>
                                    </div>
                                    <div class="customer_right">
                                       <h3 class="customer_name">DenoMark <span class="quick_icon"><img src="/UniRent/Smarty/images/quick-icon.png"></span></h3>
                                       <p class="enim_text">anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internetanything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet</p>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="carousel-item">
                  <div class="customer_section_2">
                     <div class="container">
                        <div class="row">
                           <div class="col-md-12">
                              <div class="box_main">
                                 <div class="customer_main">
                                    <div class="customer_left">
                                       <div class="customer_img"><img src="/UniRent/Smarty/images/customer-img.png"></div>
                                    </div>
                                    <div class="customer_right">
                                       <h3 class="customer_name">DenoMark <span class="quick_icon"><img src="/UniRent/Smarty/images/quick-icon.png"></span></h3>
                                       <p class="enim_text">anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internetanything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet</p>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="carousel-item">
                  <div class="customer_section_2">
                     <div class="container">
                        <div class="row">
                           <div class="col-md-12">
                              <div class="box_main">
                                 <div class="customer_main">
                                    <div class="customer_left">
                                       <div class="customer_img"><img src="/UniRent/Smarty/images/customer-img.png"></div>
                                    </div>
                                    <div class="customer_right">
                                       <h3 class="customer_name">DenoMark <span class="quick_icon"><img src="/UniRent/Smarty/images/quick-icon.png"></span></h3>
                                       <p class="enim_text">anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internetanything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet</p>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <a class="carousel-control-prev" href="#my_slider" role="button" data-slide="prev">
              <i class="fa fa-arrow-left"></i>
            </a>
            <a class="carousel-control-next" href="#my_slider" role="button" data-slide="next">
              <i class="fa fa-arrow-right"></i>
            </a>
         </div>
      </div>
      <!-- customers section end -->
      <!-- contact section start -->
      <div class="contact_section layout_padding">
         <div class="container">
            <div class="row">
               <div class="col-sm-12">
                  <div class="contact_taital_main">
                     <h1 class="contact_taital">Requeste A Call Back</h1>
                     <hr class="contact_border_main">
                  </div>
               </div>
            </div>
         </div>
         <div class="container-fluid">
            <div class="contact_section_2">
               <div class="row">
                  <div class="col-md-6">
                     <div class="mail_section map_form_container">
                        <form action="">
                        <input type="text" class="mail_text" placeholder="Name" name="Name">
                        <input type="text" class="mail_text" placeholder="Phone Number" name="Phone Number"> 
                        <input type="text" class="mail_text" placeholder="Email" name="Email">
                        <textarea class="massage-bt" placeholder="Massage" rows="5" id="comment" name="Massage"></textarea>
                        <div class="btn_main">
                           <div class="send_bt active"><a href="#">Send Now</a></div>
                           <div class="map_bt"><a href="#" id="showMap">Map</a></div>
                        </div>
                        </form>
                        <div class="map_main map_container">
                           <div class="map-responsive">
                              <iframe src="https://www.google.com/maps/embed/v1/place?key=AIzaSyA0s1a7phLN0iaD6-UE7m4qP-z21pH0eSc&amp;q=Eiffel+Tower+Paris+France" width="600" height="368" frameborder="0" style="border:0; width: 100%;" allowfullscreen=""></iframe>
                               <div class="btn_main">
                                 <div class="map_bt d-flex justify-content-center w-100 map_center"><a href="#" id="showForm">Form</a></div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="contact_img"><img src="/UniRent/Smarty/images/contact-img.png"></div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- contact section end -->
      
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