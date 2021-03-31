<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <!-- Custom fonts for this template-->

    <link href="<?php echo base_url('assets/fontawesome-free/css/all.min.css');?>" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?php echo base_url('assets/css/startbootstrap/sb-admin-2.css?v=1');?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/cropper.css');?>" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" integrity="sha512-aOG0c6nPNzGk+5zjwyJaoRUgCdOrfSDhmMID2u4+OIslr0GjpLKo7Xm0Ao3xmpM4T8AmIouRkqwj1nrdVsLKEQ==" crossorigin="anonymous" />


    <link href=" <?php echo base_url('assets/datatables/dataTables.bootstrap4.min.css');?>" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">


    <style>
        td{
            font-size: 14px;
        }

        #snackbar{
            visibility: hidden; /* Hidden by default. Visible on click */
            min-width: 250px; /* Set a default minimum width */
            margin-left: -125px; /* Divide value of min-width by 2 */
            background-color: #333; /* Black background color */
            color: #fff; /* White text color */
            text-align: center; /* Centered text */
            border-radius: 10px; /* Rounded borders */
            padding: 16px; /* Padding */
            position: fixed; /* Sit on top of the screen */
            z-index: 99999999; /* Add a z-index if needed */
            left: 50%; /* Center the snackbar */
            bottom: 100px;
        }

        /* Show the snackbar when clicking on a button (class added with JavaScript) */
        #snackbar.show {
            visibility: visible; /* Show the snackbar */
            /* Add animation: Take 0.5 seconds to fade in and out the snackbar.
            However, delay the fade out process for 2.5 seconds */
            -webkit-animation: fadein 0.5s, fadeout 0.5s 2.5s;
            animation: fadein 0.5s, fadeout 0.5s 2.5s;
        }

        /* Animations to fade the snackbar in and out */
        @-webkit-keyframes fadein {
            from {bottom: 0; opacity: 0;}
            to {bottom: 100px; opacity: 1;}
        }

        @keyframes fadein {
            from {bottom: 0; opacity: 0;}
            to {bottom: 100px; opacity: 1;}
        }

        @-webkit-keyframes fadeout {
            from {bottom: 100px; opacity: 1;}
            to {bottom: 0; opacity: 0;}
        }

        @keyframes fadeout {
            from {bottom: 100px; opacity: 1;}
            to {bottom: 0; opacity: 0;}
        }

        .Veil-non-hover{
            z-index: 4998;
            background-color: rgba(34,25,36,.5);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 1001;
            display: none;
        }

        .loading{
            width: 125px;
            position: fixed;
            z-index: 10000;
            display: none;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
        }

    </style>

    <style>
        .one {
            float:left;
            width: 49%;
        }
        .two {
            overflow:hidden;
        }

        .three {
            background-color: white;
            border-radius: 5px;
            padding: 15px 30px;
            margin-top:10px


        }

        .green-line{
            border-bottom: 2px solid rgba(20,143,143,0.3);
            margin: 10px 0;
            width: 100%
        }
    </style>



</head>



<body id="page-top">


<!-- Page Wrapper -->
<div id="wrapper">
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <div class="wrapper">
                <div style="float:left; width: 49%;">
                    <img src="<?php echo base_url("assets/images/logopdf.jpg")?>" style="length: 384px; width: 188px; float: left;">
                </div>
                <div style="overflow:hidden;">
                    <h1 style="color: black"> Invoice </h1>
                </div>

            </div>

        </div>
    </div>
    <!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->





</body>

</html>