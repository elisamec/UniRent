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
                  <form action="/UniRent/Student/editProfile" class="form" method="post" id="yourFormId">
                     <div class="parent">
                        <div class="div1"><p>Name: </p></div>
                        <div class="div2">
                           <input class="profile-input" type="text" name="name" id="name" value="{$student->getName()}" required>
                        </div>
                        <div class="div3"><p>Surname: </p></div>
                        <div class="div4">
                           <input class="profile-input" type="text" name="surname" id="surname" value="{$student->getSurname()}" required>
                        </div>
                        <div class="div5"><p>Username: </p></div>
                        <div class="div6">
                           <input class="profile-input" type="text" name="username" id="username" value="{$student->getUsername()}" required>
                        </div>
                        <div class="div7"><p>Email: </p></div>
                        <div class="div8">
                           <input class="profile-input" type="text" name="email" id="email" value="{$student->getUniversityMail()}" required>
                        </div>
                        <div class="div9"><p>Sex: </p></div>
                        <div class="div10">
                        {if $student->getSex() === "F"}
                        <div class="col-sm-6">
                           <input required class="radio" type="radio" name="sex" id="sex" value="F" checked="checked">
                           <label class="customlabel" for="F">Female</label>
                        </div>
                        <div class="col-sm-6">
                        <input required="" class="radio" type="radio" name="sex" id="sex" value="M">
                        <label class="customlabel" for="M">Male</label>
                        </div>
                        {else}
                        <div class="col-sm-6">
                           <input required class="radio" type="radio" name="sex" id="sex" value="F">
                           <label class="customlabel" for="F">Female</label>
                        </div>
                        <div class="col-sm-6">
                        <input required="" class="radio" type="radio" name="sex" id="sex" value="M" checked="checked">
                        <label class="customlabel" for="M">Male</label>
                        </div>
                        {/if}
                        </div>
                        <div class="div11"><p>Course Duration: </p></div>
                        <div class="div12">
                           <input class="profile-input" type="number" name="courseDuration" id="courseDuration" value="{$student->getCourseDuration()}" min="1" max="6" required>
                        </div>
                        <div class="div13"><p>Immatricolation Year: </p></div>
                        <div class="div14">
                           <input class="profile-input" type="number" name="immatricolationYear" id="immatricolationYear" value="{$student->getImmatricolationYear()}" min="2018" max="2099" required>
                        </div>
                        <div class="div15"><p>Date of Birth: </p></div>
                        <div class="div16">
                           <input class="profile-input" type="date" name="birthDate" id="birthDate" required>
                        </div>
                        <div class="div17"><p>Personal Information: </p></div>
                        <div class="div18">
                           {if $student->getSmoker() && $student->getAnimals()}
                           <div class="col-sm-6">
                           <input required class="checkbox" type="checkbox" name="smoker" id="smoker" checked>
                           <label class="customlabel" for="smoker">Smoker</label>
                           </div>
                           <div class="col-sm-6">
                           <input required="" class="checkbox" type="checkbox" name="animals" id="animals" checked>
                           <label class="customlabel" for="animals">Animals</label>
                           </div>
                           {elseif $student->getSmoker() && !$student->getAnimals()}
                           <div class="col-sm-6">
                           <input required class="checkbox" type="checkbox" name="smoker" id="smoker" checked>
                           <label class="customlabel" for="smoker">Smoker</label>
                           </div>
                           <div class="col-sm-6">
                           <input required="" class="checkbox" type="checkbox" name="animals" id="animals">
                           <label class="customlabel" for="animals">Animals</label>
                           </div>
                           {elseif !$student->getSmoker() && $student->getAnimals()}
                           <div class="col-sm-6">
                           <input required class="checkbox" type="checkbox" name="smoker" id="smoker">
                           <label class="customlabel" for="smoker">Smoker</label>
                           </div>
                           <div class="col-sm-6">
                           <input required="" class="checkbox" type="checkbox" name="animals" id="animals" checked>
                           <label class="customlabel" for="animals">Animals</label>
                           </div>
                           {else}
                           <div class="col-sm-6">
                           <input required class="checkbox" type="checkbox" name="smoker" id="smoker">
                           <label class="customlabel" for="smoker">Smoker</label>
                           </div>
                           <div class="col-sm-6">
                           <input required="" class="checkbox" type="checkbox" name="animals" id="animals">
                           <label class="customlabel" for="animals">Animals</label>
                           </div>
                           {/if}
                        </div>
                        <div class="div19"><p>Password: </p></div>
                        <div class="div20">
                           <input class="profile-input" type="password" name="password" id="password">
                        </div>
                     </form>
                     <div class="div21"><p>Delete Profile: </p></div>
                     <div class="div22">
                        <div class="delete_btn"><a href="/UniRent/Student/deleteProfile">Delete</a></div>
                  </div>
               </div>
               <div class="container">
                  <div class="row">
                     <div class="col-md-4">
                     <div  class="find_btn"><a href="/UniRent/Student/profile" id="yourLinkId">Confirm</a></div>
                     </div>
                     <div class="col-md-4">
                     <div  class="find_btn"><a href="/UniRent/Student/profile">Cancel</a></div>
                     </div>
                  </div>
               </div>
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
                        <li><a href="/UniRent/User/about">About Us</a></li>
                        <li><a href="/UniRent/User/contact">Contact Us</a></li>
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
      <script>
                           var birthDateString = '{$student->getBirthDateString()}';
                           
                           if (birthDateString) {
                              var parts = birthDateString.split('/');
                              if (parts.length === 3) {
                                    var formattedDate = parts[2] + '-' + parts[0].padStart(2, '0') + '-' + parts[1].padStart(2, '0');
                                    document.getElementById('birthDate').value = formattedDate;
                              }
                           }
                        </script>
      <script>
      document.getElementById("yourLinkId").onclick = function() {
    document.getElementById("yourFormId").submit();
}
      </script>
   </body>
