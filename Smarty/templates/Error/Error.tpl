<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>UniRent - {$error} Error</title>

    <!-- Custom fonts for this template-->
    <link href="/UniRent/Smarty/templates/Admin/AdminVendor//fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="/UniRent/Smarty/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="/UniRent/Smarty/css/modal.css" rel="stylesheet">

</head>

<body id="page-top" onload="on()">

    <!-- Page Wrapper -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex-error flex-column">

            <!-- Main Content -->
            <div id="content screenSize">

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Error Text -->
                    <div class="text-center">
                        <div class="error mx-auto" data-text="{$error}">{$error}</div>
                        {if $error == 400}
                        <p class="lead text-gray-800 mb-5">Bad Request</p>
                        {else if $error == 600}
                        <h6 class="lead text-gray-800 mb-5">This User Has Been Banned</h6>
                        <p> The report that was sent to the administrator and therefore caused your profile to be banned (only after a thorough investigation had been conducted) is as follows:</p>
                        <p>{$banReason}</p>
                        <p class="text-gray-500 mb-0">Please contact the administrator for more information</p>
                        <form class="flex-form" action="/UniRent/SupportRequest/removeBan/{$username}" method="post">
                            <textarea placeholder="Message" rows="5" id="comment" name="Message" required></textarea>
                            <button type="submit" class="btn btn-primary btn-user send_btn">Send</button>
                        </form>
                        {else if $error == 401}
                        <p class="lead text-gray-800 mb-5">Unauthorized</p>
                        {else if $error == 403}
                        <p class="lead text-gray-800 mb-5">Access Denied</p>
                        {else if $error == 404}
                        <p class="lead text-gray-800 mb-5">Page Not Found</p>
                        {else if $error == 405}
                        <p class="lead text-gray-800 mb-5">Method Not Allowed</p>
                        {else if $error == 408}
                        <p class="lead text-gray-800 mb-5">Request Timeout</p>
                        {else if $error == 409}
                        <p class="lead text-gray-800 mb-5">Conflict</p>
                        {else if $error == 410}
                        <p class="lead text-gray-800 mb-5">Gone</p>
                        {else if $error == 411}
                        <p class="lead text-gray-800 mb-5">Length Required</p>
                        {else if $error == 412}
                        <p class="lead text-gray-800 mb-5">Precondition Failed</p>
                        {else if $error == 413}
                        <p class="lead text-gray-800 mb-5">Payload Too Large</p>
                        {else if $error == 414}
                        <p class="lead text-gray-800 mb-5">URI Too Long</p>
                        {else if $error == 415}
                        <p class="lead text-gray-800 mb-5">Unsupported Media Type</p>
                        {else if $error == 429}
                        <p class="lead text-gray-800 mb-5">Too Many Requests</p>
                        {else if $error == 500}
                        <p class="lead text-gray-800 mb-5">Internal Server Error</p>
                        {else if $error == 501}
                        <p class="lead text-gray-800 mb-5">Not Implemented</p>
                        {else if $error == 502}
                        <p class="lead text-gray-800 mb-5">Bad Gateway</p>
                        {else if $error == 503}
                        <p class="lead text-gray-800 mb-5">Service Unavailable</p>
                        {else if $error == 504}
                        <p class="lead text-gray-800 mb-5">Gateway Timeout</p>
                        {else if $error == 505}
                        <p class="lead text-gray-800 mb-5">HTTP Version Not Supported</p>
                        {else}
                        <p class="lead text-gray-800 mb-5">An error occurred</p>
                        {/if}
                        {if $error != 600}
                        <p class="text-gray-500 mb-0">It looks like you found a glitch in the matrix...</p>
                        {/if}
                        <a href="/UniRent/">&larr; Back to home</a>
                    </div>
                <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; UniRent 2024</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->
                </div>
                <!-- /.container-fluid -->
            
            </div>
            <!-- End of Main Content -->

        </div>
        <!-- End of Content Wrapper -->
        
<div class="resModal" id="successRequestModal">
    <div class="resModal-content">
    <div class="row">
        <span class="resClose" id="closeSpan">&times;</span>
        {if $requestSuccess == 'success'}
        <h3 class="resModal-head">Request Sent Successfully</h3>
        </div>
        <p>Your request has been sent successfully. Please wait for the administrator to examine your request. If the administrator thinks your ban can be removed, your account will be reactivated within 5 days from the request sumbission. Thank you for you patience.</p>
        {else if $requestSuccess == 'error'}
        <h3 class="resModal-head">Error</h3>
        </div>
        <p>There was an error while sending your request. Please try again later.</p>
        {/if}
        <button class="cancelClass" id="closeButton">Understood</button>
    </div>
</div>
    <!-- End of Page Wrapper -->

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

<!-- JavaScript variables -->
<script>
    var requestSuccess = '{$requestSuccess}';
</script>
<!-- JavaScript files -->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="/UniRent/Smarty/js/sb-admin-2.min.js"></script>
<script src="/UniRent/Smarty/js/UniRentOriginal/cookie.js"></script>
<script src="/UniRent/Smarty/js/UniRentOriginal/modalHandling.js"></script>
    
</body>
</html>
</html>
