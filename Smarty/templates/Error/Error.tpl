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
    <link href="AdminVendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="/UniRent/Smarty/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="/UniRent/Smarty/css/modal.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

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
                        <form class="flex-form" action="/UniRent/Admin/removeBanRequest/{$username}" method="post">
                            <textarea placeholder="Message" rows="5" id="comment" name="Message"></textarea>
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
        {if $requestSuccess == 'requestSent'}
        <h3 class="resModal-head">Request Sent Successfully</h3>
        </div>
        <p>Your request has been sent successfully. Please wait for the administrator to examine your request. If the administrator thinks your ban can be removed, your account will be reactivated by tomorrow. Thank you for you patience.</p>
        {else if $requestSuccess == 'error'}
        <h3 class="resModal-head">Error</h3>
        </div>
        <p>There was an error while sending your request. Please try again later.</p>
        {/if}
        <button class="cancelClass" id="closeButton">Understood</button>
    </div>
</div>
<script>

    var modal = document.getElementById("successRequestModal");
    var span = document.getElementById("closeSpan");
    var button = document.getElementById("closeButton");

    if ('{$requestSuccess}' !== 'null') {
        modal.style.display = "block";
    } else {
        modal.style.display = "none";
    }
    span.onclick = function() {
        modal.style.display = "none";
    }

    button.onclick = function() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>
    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="/UniRent/Smarty/js/sb-admin-2.min.js"></script>

    <script src="/UniRent/Smarty/js/cookie.js"></script>
</body>

</html>
