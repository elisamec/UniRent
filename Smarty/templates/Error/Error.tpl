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

</head>

<body id="page-top">

    <!-- Page Wrapper -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- 404 Error Text -->
                    <div class="text-center">
                        <div class="error mx-auto" data-text="{$error}">{$error}</div>
                        {if $error == 404}
                        <p class="lead text-gray-800 mb-5">Page Not Found</p>
                        {else if $error == 403}
                        <p class="lead text-gray-800 mb-5">Access Denied</p>
                        {else if $error == 500}
                        <p class="lead text-gray-800 mb-5">Internal Server Error</p>
                        {else}
                        <p class="lead text-gray-800 mb-5">An error occurred</p>
                        {/if}
                        <p class="text-gray-500 mb-0">It looks like you found a glitch in the matrix...</p>
                        <a href="/UniRent/">&larr; Back to home</a>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->


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