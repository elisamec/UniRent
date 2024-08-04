<!DOCTYPE html>
<html>
   <head>
      <!-- basic -->
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <!-- mobile metas -->
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="viewport" content="initial-scale=1, eimum-scale=1">
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
                        <a class="nav-link active" href="/UniRent/Student/postedReview">Posted Reviews</a>
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
                                <span class="badge badge-danger badge-counter">{$countReply}</span>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdownWidth dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="messagesDropdown">
                                <h6 class="dropdown-header">
                                    Admin Replies
                                </h6>
                                {foreach from=$replies item=reply}
                                {if $smarty.foreach.replies.iteration <= 4}
                                    <a class="dropdown-item d-flex align-items-center" href="#">
                                    {if ($reply->getStatusRead() == 0 || $reply->getStatusRead() == false)}
                                        <div class="font-weight-bold requestItem" 
                                    {else}
                                        <div class="requestItem"
                                    {/if}
                                        data-toggle="modal" data-target="#replyModal" data-request="{$reply->getMessage()}" data-reply="{$reply->getSupportReply()}" data-topic="{$reply->getTopic()->value}" data-id="{$reply->getId()}">
                                            <div class="text-truncate">{$reply->getSupportReply()}</div>
                                            <div class="smallMessages text-gray-500">{$reply->getTopic()->value}</div>
                                        </div>
                                    </a>
                                {/if}
                                    {/foreach}
                                <a class="dropdown-item text-center smallMessages text-gray-500" href="/UniRent/Student/readMoreSupportReplies">Read More Replies</a>
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
      <div class="container-fluid">
         <div class="Properties_taital_main layout">
         <h1 class="Properties_taital">What you think of others:</h1>
         <hr class="border_main">
         </div>
    <div id="reviewsContainer"></div>

    <div id="confirmModal" class="resModal">
   <div class="resModal-content">
      <span class="resClose">&times;</span>
      <p>Are you sure you want to delete this review??</p>
      <div class="btn-cont">
      <button id="confirmDelete" class="confirmClass">Yes</button>
      <button id="cancelDelete" class="cancelClass">Cancel</button>
      </div>
   </div>
</div>

<div id="editModal" class="resModal">
    <div class="resModal-content">
    <div class="row">
        <span class="resClose" id="editClose">&times;</span>
        <h1  class="resModal-head">Edit Review</h1>
        </div>
        <form id="editReviewForm" action="" method="post">
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
                <input type="radio" id="star1A" name="rate" value="1" />
                <label for="star1A" title="1 star">
                    <svg viewBox="0 0 576 512" height="1em" xmlns="http://www.w3.org/2000/svg" class="star-solid">
                        <path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"></path>
                    </svg>
                </label>
               </div>
            <input type="text" name="title" id="reviewTitle" placeholder="Title" value="" required>
            <textarea name="content" rows="5" id="reviewContent" placeholder="Content" required></textarea>
            <div class="btn-cont">
            <button type="submit" class="edit_btn">Edit</button>
            <button type="button" class="edit_btn" id="CancelBut">Cancel</button>
            </div>
        </form>
    </div>
</div>

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
            container.innerHTML = '<h1 class="noRev">There are no reviews yet!</h1>';
        } else {
            reviews.forEach(review => {
                const reviewElement = document.createElement('div');
                reviewElement.className = 'review';

                let style;
                if (review.userStatus === 'banned') {
                    style = 'class="disabled"';
                } else {
                    style = '';
                }

                let userStyle;
                let hrefLink;
                if (review.type === 'Accommodation') {
                    userStyle = 'class="accomm"';
                    hrefLink = '/UniRent/Student/accommodation/' + review.idRecipient;
                } else {
                    userStyle = 'class="userIcon"';
                    hrefLink = '/UniRent/Student/publicProfile/' + review.username;
                }

                // Apply opacity and warning if reported
                let reportedStyle = '';
                let reportedWarning = '';
                if (review.reported) {
                    reportedStyle = 'style="opacity: 0.5;"';
                    reportedWarning = '<h1 class="reported-warning" style="color: white; background-color: black; border-color: red; border-radius: 1rem; font-weight: bold; margin-left: 40px; margin-top:15px; padding: 20px">Reported</h1>';
                }

                // Insert the names of the elements of the review array
                reviewElement.innerHTML = `
                <div class="row">
                    <h1 class="ReviewTitle"` + reportedStyle + `>` + review.title + `</h1> <!-- Title of the review -->
                    {literal}${reportedWarning}{/literal}
                    <div class="btn-cont2">
                        <button class="delete_button" data-review-id="` + review.id + `">Delete</button>
                        <button class="edit_button" data-review-id="` + review.id + `">Edit</button>
                    </div>
                </div>
                <div class="row">
                    <div class="userSection"` + reportedStyle + `>
                        <p> To: </p>
                        <div ` + userStyle + `>
                            <a href=` + hrefLink + style + `><img src=` + review.userPicture + ` alt="User Profile Picture"></a>
                        </div>
                        <div class="username"><a href=` + hrefLink + style + `>` + review.username + `</a></div> <!-- Username of the reviewer -->
                        <p class="reviewRecipType">` + review.type + `</p>
                    </div>
                    <div class="col-md-10"` + reportedStyle + `>
                        <div class="stars">
                            ` + generateStars(review.stars) + ` <!-- Star rating -->
                        </div>
                        <p>` + review.content + `</p> <!-- Content of the review -->
                    </div>
                </div>
                `;

                container.appendChild(reviewElement);
            });

            // Add event listeners for edit buttons
            const editButtons = document.querySelectorAll('.edit_button');
            editButtons.forEach(button => {
                button.addEventListener('click', (event) => {
                    const reviewId = event.target.getAttribute('data-review-id');
                    const review = reviews.find(r => r.id == reviewId);
                    if (review) {
                        // Populate form fields with review data
                        document.getElementById('editReviewForm').action = '/UniRent/Review/edit/' + review.id;
                        document.getElementById('editReviewForm').method = 'POST';
                        document.getElementById('reviewTitle').value = review.title;
                        document.getElementById('reviewContent').value = review.content;
                        document.querySelector('input[name="rate"][value="' + review.stars + '"]').checked = true;

                        // Display the modal
                        document.getElementById('editModal').style.display = 'grid';
                    }
                });
            });
        }
    } else {
        console.error("Container not found!"); // Debugging: Error if container is not found
    }
}

    document.querySelectorAll('a.disabled').forEach(function(link) {
    link.addEventListener('click', function(event) {
      event.preventDefault();
    });
  });

    // Close modal when clicking on the close button or outside the modal
    const modal = document.getElementById('editModal');
    const closeModal = document.querySelector('#editClose');
    const cancelBut = document.querySelector('#CancelBut');

    cancelBut.onclick = () => {
        modal.style.display = 'none';
    }

    closeModal.onclick = () => {
        modal.style.display = 'none';
    }

    window.onclick = (event) => {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }

    // Call the function to display reviews
    displayReviews(reviews);
    {/if}
</script>

<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        const deleteButtons = document.querySelectorAll('.delete_button');
        const modal = document.getElementById("confirmModal");
        const span = document.querySelector(".resClose");
        const confirmBtn = document.getElementById("confirmDelete");
        const cancelBtn = document.getElementById("cancelDelete");

        deleteButtons.forEach(button => {
            button.addEventListener('click', (event) => {
                event.preventDefault(); // Prevent the default action (navigation)
                const reviewId = event.target.getAttribute('data-review-id');
                modal.style.display = "block";

                // Set the delete action URL with the review ID
                confirmBtn.onclick = function() {
                    window.location.href = "/UniRent/Review/Delete/" + reviewId;
                };
            });
        });

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        };

        // When the user clicks on the cancel button, close the modal
        cancelBtn.onclick = function() {
            modal.style.display = "none";
        };

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        };
    });
</script>
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
   <script src="/UniRent/Smarty/js/cookie.js"></script>
   <!-- Request Detail Modal -->
<div class="resModal" id="replyModal">
      <div class="resModal-content">
         <div class="row">
            <span class="resClose">&times;</span>
            <h2 class="resModal-head">Support Reply Details</h2>
         </div>
                <p><strong>Your Request:</strong><span id="requestContent"></span></p>
                <p><strong>Admin Reply:</strong> <span id="replyContent"></span></p>
                <p><strong>Topic:</strong> <span id="requestTopic"></span></p>
                <hr>
            <div class="btn-cont">
                <button type="button" class="edit_btn" id="closeReply">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End of Request Detail Modal -->
<script>
    $(document).ready(function() {
        $('.requestItem').on('click', function() {
            var content = $(this).data('request');
            var reply = $(this).data('reply');
            var topic = $(this).data('topic');

            // Set modal content
            $('#requestContent').text(content);
            $('#replyContent').text(reply);
            $('#requestTopic').text(topic);
            $('input[name="requestId"]').val($(this).data('id'));
        });

        $('#closeReply').on('click', function() {
            var requestId = $('input[name="requestId"]').val();
            window.location.href = '/UniRent/Student/readSupportReply/' + requestId;
        });
    });
</script>
</body>
