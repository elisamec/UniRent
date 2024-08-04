<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>UniRent Admin - Profile</title>
    <link rel="icon" href="/UniRent/Smarty/images/favicon.png" type="image/png">

    <!-- Custom fonts for this template-->
    <link href="/UniRent/Smarty/templates/Admin/AdminVendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="/UniRent/Smarty/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="/UniRent/Smarty/css/sb-admin-2.css" rel="stylesheet">

</head>

<body id="page-top" onload="on()">

    <!-- Page Wrapper -->
    <div id="wrapper">


        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <h1 class="h3 mb-0 text-gray-800">User Profile</h1>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Alerts -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-bell fa-fw"></i>
                                <!-- Counter - Alerts -->
                                <span class="badge badge-danger badge-counter">{$countReports}</span>
                            </a>
                            <!-- Dropdown - Alerts -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="alertsDropdown">
                                <h6 class="dropdown-header">
                                    Reports
                                </h6>
                                {foreach from=$reports item=report}
                                {if $smarty.foreach.reports.iteration <= 4}
                                    <a class="dropdown-item d-flex align-items-center" href="#">
                                        <div class="mr-3">
                                            <div class="icon-circle bg-danger">
                                                <i class="fa fa-exclamation-triangle text-white"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="smallMessages text-gray-500">{$report->getMade()->format('F d, Y')}</div>
                                            {if ($report->getBanDate() === null)}
                                                <span class="font-weight-bold">{$report->getDescription()}</span>
                                            {else}
                                                <span>{$report->getDescription()}</span>
                                            {/if}
                                        </div>
                                    </a>
                                {/if}
                                    {/foreach}
                                <a class="dropdown-item text-center smallMessages text-gray-500" href="#">Show All Reports</a>
                            </div>
                        </li>

                        <!-- Nav Item - Messages -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-envelope fa-fw"></i>
                                <!-- Counter - Messages -->
                                <span class="badge badge-danger badge-counter">{$countRequests}</span>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdownWidth dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="messagesDropdown">
                                <h6 class="dropdown-header">
                                    Support Requests
                                </h6>
                                {foreach from=$requests item=request}
                                {if $smarty.foreach.requests.iteration <= 4}
                                    <a class="dropdown-item d-flex align-items-center" href="#">
                                    {if ($request['Request']->getStatus()->value == 0)}
                                        <div class="font-weight-bold requestItem" 
                                    {else}
                                        <div class="requestItem"
                                    {/if}
                                        data-toggle="modal" data-target="#requestModal" data-content="{$request['Request']->getMessage()}" data-author="{$request['author']}" data-topic="{$request['Request']->getTopic()->value}" data-id="{$request['Request']->getId()}" data-show-form={$request['Request']->getStatus()->value == 0}>
                                            <div class="text-truncate">{$request['Request']->getMessage()}</div>
                                            <div class="smallMessages text-gray-500">{$request['author']} · {$request['Request']->getTopic()->value}</div>
                                        </div>
                                    </a>
                                {/if}
                                    {/foreach}
                                <a class="dropdown-item text-center smallMessages text-gray-500" href="/UniRent/Admin/readMoreSupportRequest">Read More Requests</a>
                            </div>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <a class="logoutBtn" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fa fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="path">
        <p id="breadcrumb"></p>
    </div>
                    <div class="profile">
                        <div class="containerProf">
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="profile_info">
                                        <h2 class="profile_head">{$user->getName()} {$user->getSurname()}</h2>
                                        <p>@{$user->getUsername()}</p>
                                        {if {$userType} == 'Owner'}
                                            <p>Phone Number: {$user->getPhoneNumber()}.</p>
                                            <p> Number of Ads: {$user->getNumberOfAds()}.</p>
                                        {else if {$userType} == 'Student'}
                                            {if $user->getSex() === "M"}
                                                <p>Sex: Male</p>
                                            {else}
                                                <p>Sex: Female</p>
                                            {/if}
                                            <p>Age: {$user->getAge()}.</p>
                                        {/if}
                                        <p> Average Rating: {$user->getAverageRating()}.</p>
                                        <div class="col-md-3">
                                        {if $user->getStatus()->value=='banned'}
                                            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" onclick="removeBanModal({$user->getId()}, {$userType})"> Remove Ban</a>
                                        {else}
                                            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" onclick="banModal({$user->getId()}, {$userType})"> Ban</a>
                                        {/if}
                                        </div>
                                </div>
                                </div>
                                <div class="col-md-2">
                                <div class="container">
                                    <div class="profile_pic">
                                    {if $user->getPhoto() === null}
                                        <img src="/UniRent/Smarty/images/ImageIcon.png" class="imageIcon">
                                    {else}
                                        <img src="{$user->getShowPhoto()}" class="imageIcon">
                                    {/if}
                                    </div>
                                    
                                </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="Properties_taital_main layout">
                            <h2 class="profile_head">Reviews</h2>
                            </div>
                        <div id="reviewsContainer"></div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy;  UniRent 2024</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade center" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="/UniRent/Admin/logout">Logout</a>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade center" id="removeBanModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog center" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Remove Ban</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Are you sure you want to remove the ban?</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a id="removeBanButton" class="btn btn-primary" href="" data-dismiss="modal">Remove Ban</a>
                </div>
            </div>
        </div>
    </div>
   <!-- Request Detail Modal -->
<div class="modal fade centerSupport" id="requestModal" tabindex="-1" role="dialog" aria-labelledby="requestModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="requestModalLabel">Support Request Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="requestContent"></p>
                <p><strong>Author:</strong> <span id="requestAuthor"></span></p>
                <p><strong>Topic:</strong> <span id="requestTopic"></span></p>
                <hr>

                <!-- Text Area for Admin Reply (conditionally displayed) -->
                <div class="form-group" id="replyContainer" style="display: none;">
                    <form action="/UniRent/Admin/supportReply" method="post">
                        <label for="adminReply">Your Reply:</label>
                        <textarea class="form-control" id="adminReply" name="answare" rows="5" placeholder="Type your reply here..." required></textarea>
                        <input type="hidden" name="requestId" value="">
                    </form>
                </div>

                <!-- Additional Fields for 'registe' Topic (conditionally displayed) -->
                <div class="form-group" id="additionalFieldsContainer" style="display: none;">
                    <form id="additionalFieldsForm">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
                        <label for="university">University:</label>
                        <input type="text" class="form-control" id="university" name="university" placeholder="Enter university" required>
                        <label for="city">City:</label>
                        <input type="text" class="form-control" id="city" name="city" placeholder="Enter city" required>
                        <input type="hidden" name="requestId" value="">
                        <button type="button" class="btn btn-primary" id="addToJson">Add to JSON</button>
                        <button type="button" class="btn btn-secondary" id="deleteRequest">Delete Request</button>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="submitReply" form="replyForm" style="display: none;">Send Reply</button>
            </div>
        </div>
    </div>
</div>
<!-- End of Request Detail Modal -->


    <script>
        function removeBanModal(userId, type) {
            document.getElementById('removeBanButton').href = '/UniRent/Admin/active/' + type + '/' + userId;
            $('#removeBanModal').modal('show');
        }
    </script>
    <div class="modal fade" id="banModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog center" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Remove Ban</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Are you sure you want to banthis user?</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a id="removeBanButton" class="btn btn-primary" href="" data-dismiss="modal">Ban</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function removeBanModal(userId, type) {
            document.getElementById('removeBanButton').href = '/UniRent/Admin/ban/' + type + '/' + userId;
            $('#removeBanModal').modal('show');
        }
    </script>

    <!-- jQuery library -->
<script src="/UniRent/Smarty/templates/Admin/AdminVendor/jquery/jquery.min.js"></script>
<!-- Bootstrap Bundle with Popper -->
<script src="/UniRent/Smarty/templates/Admin/AdminVendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- jQuery Easing Plugin -->
<script src="/UniRent/Smarty/templates/Admin/AdminVendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages -->
<script src="/UniRent/Smarty/js/sb-admin-2.min.js"></script>


<!-- Optional: jQuery slim version -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>

<!-- Optional: Popper.js and Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
 <!-- Bootstrap core JavaScript-->
    <script src="/UniRent/Smarty/templates/Admin/AdminVendor/jquery/jquery.min.js"></script>
    <script src="/UniRent/Smarty/templates/Admin/AdminVendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="/UniRent/Smarty/templates/Admin/AdminVendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="/UniRent/Smarty/js/sb-admin-2.min.js"></script>

<script src="/UniRent/Smarty/js/cookie.js"></script>
<div class="modal" id="myModal">
      <div class"container-fluid">
      <div class="card">
         <svg xml:space="preserve" viewBox="0 0 122.88 122.25" y="0px" x="0px" id="cookieSvg" version="1.1"><g><path d="M101.77,49.38c2.09,3.1,4.37,5.11,6.86,5.78c2.45,0.66,5.32,0.06,8.7-2.01c1.36-0.84,3.14-0.41,3.97,0.95 c0.28,0.46,0.42,0.96,0.43,1.47c0.13,1.4,0.21,2.82,0.24,4.26c0.03,1.46,0.02,2.91-0.05,4.35h0v0c0,0.13-0.01,0.26-0.03,0.38 c-0.91,16.72-8.47,31.51-20,41.93c-11.55,10.44-27.06,16.49-43.82,15.69v0.01h0c-0.13,0-0.26-0.01-0.38-0.03 c-16.72-0.91-31.51-8.47-41.93-20C5.31,90.61-0.73,75.1,0.07,58.34H0.07v0c0-0.13,0.01-0.26,0.03-0.38 C1,41.22,8.81,26.35,20.57,15.87C32.34,5.37,48.09-0.73,64.85,0.07V0.07h0c1.6,0,2.89,1.29,2.89,2.89c0,0.4-0.08,0.78-0.23,1.12 c-1.17,3.81-1.25,7.34-0.27,10.14c0.89,2.54,2.7,4.51,5.41,5.52c1.44,0.54,2.2,2.1,1.74,3.55l0.01,0 c-1.83,5.89-1.87,11.08-0.52,15.26c0.82,2.53,2.14,4.69,3.88,6.4c1.74,1.72,3.9,3,6.39,3.78c4.04,1.26,8.94,1.18,14.31-0.55 C99.73,47.78,101.08,48.3,101.77,49.38L101.77,49.38z M59.28,57.86c2.77,0,5.01,2.24,5.01,5.01c0,2.77-2.24,5.01-5.01,5.01 c-2.77,0-5.01-2.24-5.01-5.01C54.27,60.1,56.52,57.86,59.28,57.86L59.28,57.86z M37.56,78.49c3.37,0,6.11,2.73,6.11,6.11 s-2.73,6.11-6.11,6.11s-6.11-2.73-6.11-6.11S34.18,78.49,37.56,78.49L37.56,78.49z M50.72,31.75c2.65,0,4.79,2.14,4.79,4.79 c0,2.65-2.14,4.79-4.79,4.79c-2.65,0-4.79-2.14-4.79-4.79C45.93,33.89,48.08,31.75,50.72,31.75L50.72,31.75z M119.3,32.4 c1.98,0,3.58,1.6,3.58,3.58c0,1.98-1.6,3.58-3.58,3.58s-3.58-1.6-3.58-3.58C115.71,34.01,117.32,32.4,119.3,32.4L119.3,32.4z M93.62,22.91c2.98,0,5.39,2.41,5.39,5.39c0,2.98-2.41,5.39-5.39,5.39c-2.98,0-5.39-2.41-5.39-5.39 C88.23,25.33,90.64,22.91,93.62,22.91L93.62,22.91z M97.79,0.59c3.19,0,5.78,2.59,5.78,5.78c0,3.19-2.59,5.78-5.78,5.78 c-3.19,0-5.78-2.59-5.78-5.78C92.02,3.17,94.6,0.59,97.79,0.59L97.79,0.59z M76.73,80.63c4.43,0,8.03,3.59,8.03,8.03 c0,4.43-3.59,8.03-8.03,8.03s-8.03-3.59-8.03-8.03C68.7,84.22,72.29,80.63,76.73,80.63L76.73,80.63z M31.91,46.78 c4.8,0,8.69,3.89,8.69,8.69c0,4.8-3.89,8.69-8.69,8.69s-8.69-3.89-8.69-8.69C23.22,50.68,27.11,46.78,31.91,46.78L31.91,46.78z M107.13,60.74c-3.39-0.91-6.35-3.14-8.95-6.48c-5.78,1.52-11.16,1.41-15.76-0.02c-3.37-1.05-6.32-2.81-8.71-5.18 c-2.39-2.37-4.21-5.32-5.32-8.75c-1.51-4.66-1.69-10.2-0.18-16.32c-3.1-1.8-5.25-4.53-6.42-7.88c-1.06-3.05-1.28-6.59-0.61-10.35 C47.27,5.95,34.3,11.36,24.41,20.18C13.74,29.69,6.66,43.15,5.84,58.29l0,0.05v0h0l-0.01,0.13v0C5.07,73.72,10.55,87.82,20.02,98.3 c9.44,10.44,22.84,17.29,38,18.1l0.05,0h0v0l0.13,0.01h0c15.24,0.77,29.35-4.71,39.83-14.19c10.44-9.44,17.29-22.84,18.1-38l0-0.05 v0h0l0.01-0.13v0c0.07-1.34,0.09-2.64,0.06-3.91C112.98,61.34,109.96,61.51,107.13,60.74L107.13,60.74z M116.15,64.04L116.15,64.04 L116.15,64.04L116.15,64.04z M58.21,116.42L58.21,116.42L58.21,116.42L58.21,116.42z"></path></g></svg>
         <p class="cookieHeading">We use cookies.</p>
         <p class="cookieDescription">We use cookies to ensure that we give you the best experience on our website. Please Activate Them.</p>
         </div> 
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
                container.innerHTML = '<h4 class="noRev">There are no reviews yet!</h4>';
            } else {
                reviews.forEach(review => {
                    const reviewElement = document.createElement('div');
                    reviewElement.className = 'review';
                    /*
                    let style;
                    if (review.statusReported === 1 || review.statusReported === true) {
                        style = 'style="background-color: #ffcccc;"';
                    } else if (review.statusBanned === 1 || review.statusBanned === true) {
                        style = 'style="background-color: #ff9999;"';
                    } else {
                        style = '';
                    }
                    reviewElement.attributes = style;
                    */
                    // Insert the names of the elements of the review array
                    reviewElement.innerHTML = `
                    <div class="row">
                        <h1 class="ReviewTitle">` + review.title + `</h1> <!-- Title of the review -->
                    </div>
                    <div class="row">
                            <div class="userSection">
                                <div class="userIcon">
                                <a href="/UniRent/Admin/profile/` + review.username + `"><img src=` + review.userPicture + ` alt="User Profile Picture"></a>
                            </div>
                            <div class="username"><a href="/UniRent/Admin/profile/` + review.username + `">` + review.username + `</a></div> <!-- Username of the reviewer -->
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
<script>
    $(document).ready(function() {
        $('.requestItem').on('click', function() {
            var content = $(this).data('content');
            var author = $(this).data('author');
            var topic = $(this).data('topic');
            var showForm = $(this).data('show-form') === '1'; // Convert to boolean

            // Set modal content
            $('#requestContent').text(content);
            $('#requestAuthor').text(author);
            $('#requestTopic').text(topic);
            $('input[name="requestId"]').val($(this).data('id'));

            // Show or hide the reply form based on the request status
            if (showForm) {
                $('#replyContainer').show();
                $('#submitReply').show(); // Show the "Send Reply" button
            } else {
                $('#replyContainer').hide();
                $('#submitReply').hide(); // Hide the "Send Reply" button
            }

            // Show or hide additional fields based on the topic
            if (topic === 'register') {
                $('#additionalFieldsContainer').show();
                $('#submitReply').hide(); // Hide the "Send Reply" button if topic is 'registe'
            } else {
                $('#additionalFieldsContainer').hide();
                $('#submitReply').show(); // Show the "Send Reply" button for other topics
            }
        });

        $('#deleteRequest').on('click', function() {
            var requestId = $('input[name="requestId"]').val();
            window.location.href = '/UniRent/Admin/deleteSupportRequest/' + requestId;
        });



        // Handle the 'Add to JSON' button click event
        $('#addToJson').on('click', function() {
            var email = $('#email').val();
            var university = $('#university').val();
            var city = $('#city').val();

            // Example function to handle the collected data
            console.log({
                email: email,
                university: university,
                city: city
            });

            // Optionally, you can send this data to your server or process it further
        });
    });
</script>
</body>

</html>