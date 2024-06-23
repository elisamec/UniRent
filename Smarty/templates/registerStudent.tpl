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
      <link rel="icon" href="/UniRent/Smarty/images/favicon.png" type="image/gif">
      <!-- font css -->
      <link href="https://fonts.googleapis.com/css2?family=Sen:wght@400;700;800&display=swap" rel="stylesheet">
      <!-- Scrollbar Custom CSS -->
      <link rel="stylesheet" href="/UniRent/Smarty/css/jquery.mCustomScrollbar.min.css">
      <!-- Tweaks for older IEs-->
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.2/css/fontawesome.min.css">
      <!-- Include Font Awesome -->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
      <link rel="stylesheet" type="text/css" href="/UniRent/Smarty/css/login.css">
</head>
<body>
         <div class="container-fluid">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
               <a class="navbar-brand"href="/UniRent/User/home"><img src="/UniRent/Smarty/images/logo.png"></a>
               <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
               <span class="navbar-toggler-icon"></span>
               </button>
               <div class="collapse navbar-collapse" id="navbarSupportedContent">
                  <ul class="navbar-nav ml-auto">
                     <li class="nav-item">
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
      <div class="layout_padding">
        <div class="container">
          <div class="Logcontainer">
            <div class="heading">Student Sign Up</div><
            <form action="/UniRent/Student/studentRegistration" class="form" methodmethod="post">
              <input required="" class="input" type="number" name="courseDuration" id="courseDuration" placeholder="Course Duration" min="1" max="6">
              <input required="" class="input" type="number" name="immatricolationYear" id="immatricolationYear" placeholder="Immatricolation Year" min="2018" max="2099">
              <input required="" class="input" type="text" onfocus="(this.type='date')" onblur="(this.type='text')" name="birthDate" id="birthDate" placeholder="Date of Birth">
              <legend class="customlabel">Enter your sex:</legend>
              <div class="row">
              <div class="col-sm-6">
            <input required="" class="radio" type="radio" name="sex" id="sex" value="F" checked="checked">
              <label class="customlabel" for="F">Female</label>
              </div>
               <div class="col-sm-6">
              <input required="" class="radio" type="radio" name="sex" id="sex" value="M">
              <label class="customlabel" for="Male">Male</label>
              </div>
               </div>
               <legend class="customlabel">Personal Information*:</legend>
              <div class="row">
              <div class="col-sm-6">
              <input class="checkbox" type="checkbox" name="smoker" id="smorker">
              <label class="customlabel">smoker</label>
              </div>
               <div class="col-sm-6">
              <input class="checkbox" type="checkbox" name="animals" id="animals">
              <label class="customlabel">animals</label>
              </div>
               </div> 
              <input class="login-button" type="submit" value="Sign Up">
            </form>
            <div class="notes-container">
            <span class="title">*These informations are used to determine your accommodation matches, therefore they will not be shown.</span>
          </div>
        </div>
      </div>
<br></br>

      <!-- banner section end -->
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
                        <li><a href="/UniRent/User/home">Home</a></li>
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Contact Us</a></li>
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
  </body>
  
