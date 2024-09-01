<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>UniRent Admin - Dashboard</title>
    <link rel="icon" href="/UniRent/Smarty/images/favicon.png" type="image/png">

    <!-- Custom fonts for this template-->
    <link href="AdminAdminVendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
            <div id="content screenSize">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Reports -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-bell fa-fw"></i>
                                <!-- Counter - Reports -->
                                <span class="badge badge-danger badge-counter" id="alertCount"></span>
                            </a>
                            <!-- Dropdown - Reports -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="alertsDropdown">
                            </div>
                        </li>

                        <!-- Nav Item - Support Requests -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-envelope fa-fw"></i>
                                <!-- Counter - Support Requests -->
                                <span class="badge badge-danger badge-counter" id="messageCount"></span>
                            </a>
                            <!-- Dropdown - Support Requests -->
                            <div class="dropdownWidth dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="messagesDropdown">
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
                <!-- Breadcrumb -->
                <div class="path"><p id="breadcrumb"></p></div>
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Welcome, Admin!</h1>
                        
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Number of Accommodation Ads -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Number of Accommodation Ads</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{$stats['n_accommodation']}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fa fa-calendar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Average Student Age -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Average Student Age</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{round($stats['avg_students_age'])}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fa fa-dollar-sign fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Total Contracts Signed This Month -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Total Contracts Signed This Month</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{round($stats['this_month_n_contracts'])}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fa fa-clipboard-list fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Average Contracts Signed per Day -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Average Contracts Signed per Day</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{round($stats['avg_contracts_per_day'])}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fa fa-comments fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row -->

                    <div class="row">

                        <!-- Area Chart -->
                        <div class="col-xl-8 col-lg-7">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Signed Contracts per Day This Month Overview -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Signed Contracts per Day This Month Overview</h6>
                                    
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-area">
                                        <canvas id="myAreaChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pie Chart -->
                        <div class="col-xl-4 col-lg-5">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Kind of Users -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Kind of Users</h6>
                                    
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-pie pt-4 pb-2">
                                        <canvas id="myPieChart"></canvas>
                                    </div>
                                    <div class="mt-4 text-center small">
                                        <span class="mr-2">
                                            <i class="fa fa-circle text-primary"></i> Owners
                                        </span>
                                        <span class="mr-2">
                                            <i class="fa fa-circle text-success"></i> Students
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Content Column -->
                        <div class="col-lg-6 mb-4">

                            <!-- Most Requested Cities -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Most Requested Cities</h6>
                                </div>
                                <div class="card-body">
                                    <div class="chart-bar">
                                        <canvas id="myBarChart"></canvas>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        <div class="col-lg-6 mb-4">
                            <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Banned Users</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Username</th>
                                            <th>Type</th>
                                            <th>Cause (user report)</th>
                                            <th>Banned Since</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Name</th>
                                            <th>Username</th>
                                            <th>Type</th>
                                            <th>Cause (user report)</th>
                                            <th>Banned Since</th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        {foreach $banned as $user}
                                        <tr>
                                            <td>{$user['User']->getName()} {$user['User']->getSurname()}</td>
                                            <td><a href="/UniRent/Admin/profile/{$user['User']->getUsername()}">{$user['User']->getUsername()}</a></td>
                                            <td>{$user['Type']}</td>
                                            <td>{$user['Report']->getDescription()}</td>
                                            <td>{$user['Report']->getBanDate()->format('Y-m-d')}</td>
                                            <td><a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" onclick="removeBanModal({$user['User']->getId()}, {$user['Type']})"> Remove Ban</a></td>
                                        </tr>
                                        {/foreach}
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
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
        <i class="fa fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
    <!-- End of Logout Modal -->
<!-- Remove Ban Modal -->
    <div class="modal fade" id="removeBanModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
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
    <!-- End of Remove Ban Modal -->
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

                <!-- Admin Reply Display (conditionally displayed) -->
                <div id="adminReplyDisplay" style="display: none;">
                    <p><strong>Your Reply:</strong></p>
                    <p id="adminReplyText"></p>
                </div>

                <!-- Additional Fields for 'Registration' Topic (conditionally displayed) -->
                <div class="form-group" id="additionalFieldsContainer" style="display: none;">
                    <form id="additionalFieldsForm" action="/UniRent/Admin/verifyEmail">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
                        <label for="university">University:</label>
                        <input type="text" class="form-control" id="university" name="university" placeholder="Enter university" required>
                        <label for="city">City:</label>
                        <input type="text" class="form-control" id="city" name="city" placeholder="Enter city" required>
                        <input type="hidden" name="requestId" value="">
                        <input type="hidden" name="answare" value="The mail was added to the json">
                        <button type="submit" class="btn btn-primary" id="verifyEmail">Verify Email</button>
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
<!-- Report Detail Modal -->
<div class="modal fade centerSupport" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="reportModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reportModalLabel">Report Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <p><strong>Made:</strong> <span id="reportMade"></span></p>
                <p><strong>Report Content: </strong><span id="reportContent"></span></p>
                <p><strong>Subject type:</strong> <span id="reportType"></span></p>
                <p><strong>Subject:</strong> <span id="reportSubject"></span></p>
                <p><strong>Ban Date:</strong> <span id="reportBanDate"></span></p>
                <input type="hidden" name="reportId" value="">
                <hr>

                <!-- Review Display (conditionally displayed) -->
                <div id="reviewContainer" style="display: none;">
                    <p><strong>Reported Review:</strong></p>
                    <div id="reportedReview"></div>
                    <input type="hidden" name="reviewId" value="">
                    <button type="button" class="btn btn-primary" id="banReview" style="display: none;">Ban Review</button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="deleteReport" style="display: none;">Delete Report</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End of Report Detail Modal -->
<!-- Confirmation Modal -->
 <div class="modal fade " id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationModalLabel">
                    {if $modalMessage === 'success'}
                    Success
                    {else}
                    Error
                    {/if}
                    </h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">S
                {if $modalMessage === 'success'}
                    The operation has been successfully completed.
                    {else}
                    There was an error while processing. Please try again later.
                    {/if}
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal" aria-label="Close">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End of Confirmation Modal -->
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
      </div>
    </div>
    <!-- End of Cookie Modal -->
    <!-- JavaScript variables -->
<script>
    var modalMessage = '{$modalMessage}';
    var chartData ={json_encode($stats['n_contracts_per_day_this_month'])};
    var nOwner={$stats['n_owner']};
    var nStudent={$stats['n_student']};
    var cityList={json_encode($stats['city_list'])};
</script>
<!-- JavaScript files -->
<script src="/UniRent/Smarty/templates/Admin/AdminVendor/jquery/jquery.min.js"></script>
<script src="/UniRent/Smarty/templates/Admin/AdminVendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/UniRent/Smarty/templates/Admin/AdminVendor/jquery-easing/jquery.easing.min.js"></script>
<script src="/UniRent/Smarty/js/sb-admin-2.min.js"></script>
<script src="/UniRent/Smarty/templates/Admin/AdminVendor/chart.js/Chart.min.js"></script>
<script src="/UniRent/Smarty/js/demo/chart-area-demo.js"></script>
<script src="/UniRent/Smarty/js/demo/chart-pie-demo.js"></script>
<script src="/UniRent/Smarty/js/demo/chart-bar-demo.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="/UniRent/Smarty/templates/Admin/AdminVendor/jquery/jquery.min.js"></script>
<script src="/UniRent/Smarty/templates/Admin/AdminVendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/UniRent/Smarty/templates/Admin/AdminVendor/jquery-easing/jquery.easing.min.js"></script>
<script src="/UniRent/Smarty/js/sb-admin-2.min.js"></script>
<script src="/UniRent/Smarty/js/UniRentOriginal/cookie.js"></script>
<script src="/UniRent/Smarty/js/UniRentOriginal/modalHandling.js"></script>
<script src="/UniRent/Smarty/js/UniRentOriginal/adminDropdowns.js"></script>

</body>
</html>