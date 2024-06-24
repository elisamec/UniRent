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
                        <a class="nav-link" href="#">Posted Reviews</a>
                     </li>
                     <li class="nav-item">
                        <a class="nav-link" href = "#">Visits</a>
                     </li>
                  </ul>
                  <form class="form-inline my-2 my-lg-0">
                     <div class="login_bt">
                        <ul>
                           <li><a href="/UniRent/Student/profile" class="active"><span class="user_icon"><i class="fa fa-user" aria-hidden="true"></i></span>Profile</a></li>
                        </ul>
                     </div>
                  </form>
               </div>
            </nav>
         </div>
      </div>
      <div class="profile">
         <div class="row">
         <div class="col-md-3">
         <div class="sidebar">
         <div class="col-md-3">
            <div class="sidebar_but active"><a href="/UniRent/Student/profile">Profile</a></div>
            </div>
            <div class="col-md-3">
            <div class="sidebar_but"><a href="#">Settings</a></div>
            </div>
            <div class="col-md-3">
            <div class="sidebar_but"><a href="#">Reviews</a></div>
            </div>
            
            <div class="col-md-3">
            <div class="sidebar_but log"><a href="/UniRent/User/logout">Logout</a></div>
            </div>
         </div>
         </div>
            <div class="col-md-6">
                  <div class="profile_info">
                     <h2 class="profile_head">Hello, {$student->getName()} {$student->getSurname()}</h2>
                     <p>Your Username: {$student->getUsername()}</p>
                     <p>Your Email: {$student->getUniversityMail()}</p>
                     {if $student->getSex() === "M"}
                     <p>Sex: Male</p>
                     {else}
                     <p>Sex: Female</p>
                     {/if}
                     <p>Your course is {$student->getCourseDuration()} years long and you started in {$student->getImmatricolationYear()}.</p>
                     <p>Your birth date is {$student->getBirthDateString()}.</p>
                     
                     {if $student->getSmoker() && $student->getAnimals()}
                     <p>You said you are a smoker and you have animals.</p>
                     {elseif $student->getSmoker() && !$student->getAnimals()}
                     <p>You said you are a smoker, but you don't have animals.</p>
                     {elseif !$student->getSmoker() && $student->getAnimals()}
                     <p>You said you are not a smoker, but you have animals.</p>
                     {else}
                     <p>You said you are not a smoker and you don't have animals.</p>
                     {/if}
                     <div class="col-md-4">
                     <div  class="find_btn" ><a href="/UniRent/Student/editProfile">Edit Profile</a></div>
                     </div>
                  
               </div>
               
            </div>
            <div class="col-md-2">
               <div class="container">
                  <div class="profile_pic">
                  {if $student->getPicture() === null}
                     <img src="/UniRent/Smarty/images/ImageIcon.png" class="imageIcon">
                  {else}
                     <img src="{$student->getPicture()}"">
                  {/if}
                  </div>
                  <form action="/UniRent/Student/changePicture" class="form" method="post">
                     <input class="file-upload" type="file" id="img" name="img" accept="image/*" hidden>
                     <label class="change_btn" for="img">Upload Profile Picture</label>
                  </form>
               </div>
            </div>
         </div>
      </div>

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
<script>
document.getElementById("file").onchange = function() {
    document.getElementById("form").submit();
};
</script>

         <script>
      $(document).ready(function() {

      
      var readURL = function(input) {
         if (input.files && input.files[0]) {
               var reader = new FileReader();

               reader.onload = function (e) {
                  $('.imageIcon').attr('src', e.target.result);
               }
      
               reader.readAsDataURL(input.files[0]);
         }
      }
      

      $(".file-upload").on('change', function(){
         readURL(this);
      });
      
      $(".label-button").on('click', function() {
         $(".file-upload").click();
      });
   });
      </script>
