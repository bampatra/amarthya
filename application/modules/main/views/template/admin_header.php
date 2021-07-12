<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' >
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Amarthya Group</title>
    <link rel="icon" href="<?php echo base_url('assets/images/logo_amarthya.png');?>" type = "image/x-icon">

    <!-- Custom fonts for this template-->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <link href="<?php echo base_url('assets/fontawesome-free/css/all.min.css');?>" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url('assets/css/startbootstrap/sb-admin-2.css?v=1');?>" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" integrity="sha512-aOG0c6nPNzGk+5zjwyJaoRUgCdOrfSDhmMID2u4+OIslr0GjpLKo7Xm0Ao3xmpM4T8AmIouRkqwj1nrdVsLKEQ==" crossorigin="anonymous" />
    <link href=" <?php echo base_url('assets/datatables/dataTables.bootstrap4.min.css');?>" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">


    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo base_url('assets/jquery/jquery.min.js');?>"></script>
    <script src="https://code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
    <script src="https://code.jquery.com/jquery-migrate-3.0.0.min.js"></script>
    <script src="<?php echo base_url('assets/bootstrap/js/bootstrap.bundle.js');?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
    <script src="<?php echo base_url('assets/jquery-easing/jquery.easing.min.js');?>"></script>
    <script src="<?php echo base_url('assets/datatables/jquery.dataTables.min.js');?>"></script>
    <script src="<?php echo base_url('assets/datatables/dataTables.bootstrap4.min.js');?>"></script>


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


</head>



<body id="page-top">



<div class="Veil-non-hover"></div>
<div id="snackbar"></div>
<img class="loading" src="<?php echo base_url('assets/images/load4.gif');?>">



<!-- Page Wrapper -->
<div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav sidebar sidebar-dark accordion toggled" id="accordionSidebar">

        <!-- Sidebar - Brand -->
<!--        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="--><?php //echo base_url('main')?><!--">-->
<!--            <div class="sidebar-brand-text mx-3">YAPN</sup></div>-->
<!--        </a>-->
<!---->

<!--        <hr class="sidebar-divider my-0">-->

        <!-- Nav Item - Dashboard -->
<!--        <li class="nav-item active">-->
<!--            <a class="nav-link" href="--><?php //echo base_url('main')?><!--">-->
<!--                <i class="fas fa-fw fa-tachometer-alt"></i>-->
<!--                <span style="font-size: 12px !important">Dashboard</span></a>-->
<!--        </li>-->
<!---->

        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            Menu
        </div>

        <!-- Nav Item - Pages Collapse Menu -->

        <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url('main')?>">
                <i class="fas fa-fw fa-home"></i>
                <span style="font-size: 11px !important">Home</span>
            </a>
        </li>



        <?php if($this->session->userdata('is_admin') == "1" || $this->session->userdata('is_admin') == "2") { ?>

        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseMaster" aria-expanded="true" aria-controls="collapseMaster">
                <i class="fas fa-fw fa-id-card"></i>
                <span style="font-size: 11px !important">Master Data</span>
            </a>
            <div id="collapseMaster" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Option:</h6>
                    <?php if($this->session->userdata('is_admin') == "1") { ?>
                        <a class="collapse-item" href="<?php echo base_url('main/staff')?>">Staff</a>
                    <?php } ?>
                    <a class="collapse-item" href="<?php echo base_url('main/customer')?>">Customer</a>
                    <a class="collapse-item" href="<?php echo base_url('main/vendor')?>">Vendor</a>
                </div>
            </div>
        </li>

        <?php } ?>

        <?php if($this->session->userdata('is_admin') == "1" || $this->session->userdata('is_admin') == "2" || $this->session->userdata('is_admin') == "4") { ?>

            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseProduct" aria-expanded="true" aria-controls="collapseMaster">
                    <i class="fas fa-fw fa-shopping-bag"></i>
                    <span style="font-size: 11px !important">Product</span>
                </a>
                <div id="collapseProduct" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Option:</h6>
                        <a class="collapse-item" href="<?php echo base_url('main/product')?>">Master Data</a>
                        <?php if($this->session->userdata('is_admin') == "1" || $this->session->userdata('is_admin') == "2") { ?>
                            <a class="collapse-item" href="<?php echo base_url('main/bahan_dasar')?>">Bahan Dasar</a>
                            <a class="collapse-item" href="<?php echo base_url('main/fb_costing')?>">F&B Costing</a>
                            <a class="collapse-item" href="<?php echo base_url('main/fb_menu')?>">F&B Menu</a>
                        <?php } ?>
                    </div>
                </div>
            </li>

        <?php } ?>

        <?php if($this->session->userdata('is_admin') == "3") { ?>

            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLaporan" aria-expanded="true" aria-controls="collapseMaster">
                    <i class="fas fa-fw fa-calculator"></i>
                    <span style="font-size: 11px !important">Laporan</span>
                </a>
                <div id="collapseLaporan" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Option:</h6>
                        <a class="collapse-item" href="<?php echo base_url('main/laporan_delivery')?>">Delivery per staff</a>
                        <a class="collapse-item" href="<?php echo base_url('main/laporan_pick_up')?>">Pick Up per staff</a>
                    </div>
                </div>
            </li>

        <?php } ?>

        <?php if($this->session->userdata('is_admin') == "1") { ?>


        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLaporan" aria-expanded="true" aria-controls="collapseMaster">
                <i class="fas fa-fw fa-calculator"></i>
                <span style="font-size: 11px !important">Laporan</span>
            </a>
            <div id="collapseLaporan" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Option:</h6>
                    <a class="collapse-item" href="<?php echo base_url('main/laporan_transaksi')?>">Transaksi Umum</a>
                    <a class="collapse-item" href="<?php echo base_url('main/laporan_produk')?>">Penjualan Produk</a>
                    <a class="collapse-item" href="<?php echo base_url('main/laporan_sales')?>">Sales per Customer</a>
                    <a class="collapse-item" href="<?php echo base_url('main/laporan_purchase')?>">Purchase per Vendor</a>
                    <a class="collapse-item" href="<?php echo base_url('main/laporan_delivery')?>">Delivery per staff</a>
                    <a class="collapse-item" href="<?php echo base_url('main/laporan_pick_up')?>">Pick Up per staff</a>
                </div>
            </div>
        </li>

            <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url('main/problem_solving')?>">
                    <i class="fas fa-fw fa-exclamation"></i>
                    <span style="font-size: 11px !important">Problem</span>
                </a>
            </li>

        <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url('main/salary_form')?>">
                <i class="fas fa-fw fa-money-bill-wave"></i>
                <span style="font-size: 11px !important">Salary</span>
            </a>
        </li>

        <?php } ?>

        <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url('main/slip_gaji')?>">
                <i class="fas fa-fw fa-money-bill-wave"></i>
                <span style="font-size: 11px !important">Slip Gaji</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url('main/izin')?>">
                <i class="fas fa-fw fa-bed"></i>
                <span style="font-size: 11px !important">Izin</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseEatery" aria-expanded="true" aria-controls="collapseMaster">
                <i class="fas fa-fw fa-cutlery"></i>
                <span style="font-size: 11px !important">Eatery</span>
            </a>
            <div id="collapseEatery" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Option:</h6>
                    <a class="collapse-item" href="<?php echo base_url('main/POS_eatery')?>">POS</a>
                </div>
            </div>
        </li>

        <?php if($this->session->userdata('is_admin') == "1" || $this->session->userdata('is_admin') == "2") { ?>
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseOrder" aria-expanded="true" aria-controls="collapseMaster">
                <i class="fas fa-fw fa-shopping-cart"></i>
                <span style="font-size: 11px !important">Order Cust.</span>
            </a>
            <div id="collapseOrder" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Option:</h6>
                    <a class="collapse-item" href="<?php echo base_url('main/order_form')?>">Form</a>
                    <a class="collapse-item" href="<?php echo base_url('main/order_list')?>">Semua Order</a>
                    <a class="collapse-item" href="<?php echo base_url('main/join_inv_cust')?>">Join Invoice</a>
                </div>
            </div>
        </li>


        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseOrderVendor" aria-expanded="true" aria-controls="collapseMaster">
                <i class="fas fa-fw fa-cart-plus"></i>
                <span style="font-size: 11px !important">Order Vdr.</span>
            </a>
            <div id="collapseOrderVendor" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Option:</h6>
                    <a class="collapse-item" href="<?php echo base_url('main/order_vendor_form')?>">Form</a>
                    <a class="collapse-item" href="<?php echo base_url('main/order_vendor_list')?>">Semua Order</a>
                </div>
            </div>
        </li>

        <?php } ?>

        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseDelivery" aria-expanded="true" aria-controls="collapseMaster">
                <i class="fas fa-fw fa-motorcycle"></i>
                <span style="font-size: 11px !important">Delivery</span>
            </a>
            <div id="collapseDelivery" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Option:</h6>
                    <?php if($this->session->userdata('is_admin') == "1" || $this->session->userdata('is_admin') == "3") { ?>
                    <a class="collapse-item" href="<?php echo base_url('main/delivery_form')?>">Form</a>
                    <?php } ?>
                    <a class="collapse-item" href="<?php echo base_url('main/delivery_list')?>">Semua Delivery</a>
                </div>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePickUp" aria-expanded="true" aria-controls="collapseMaster">
                <i class="fas fa-fw fa-motorcycle"></i>
                <span style="font-size: 11px !important">Pick Up</span>
            </a>
            <div id="collapsePickUp" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Option:</h6>
                    <?php if($this->session->userdata('is_admin') == "1" || $this->session->userdata('is_admin') == "3") { ?>
                    <a class="collapse-item" href="<?php echo base_url('main/pick_up_form')?>">Form</a>
                    <?php } ?>
                    <a class="collapse-item" href="<?php echo base_url('main/pick_up_list')?>">Semua Pick Up</a>
                </div>
            </div>
        </li>


        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            Akun
        </div>

        <!-- Nav Item - Charts -->
        <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url('main/logout')?>">
                <i class="fas fa-fw fa-sign-out-alt"></i>
                <span style="font-size: 12px !important">Logout</span></a>
        </li>


        <!-- Divider -->
        <hr class="sidebar-divider d-none d-md-block">

        <!-- Sidebar Toggler (Sidebar) -->
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>

    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow mobile-only" style="height: 3.5rem !important">

                <!-- Sidebar Toggle (Topbar) -->
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>

            </nav>
            <br>


<!-- Page level custom scripts -->
<script src="<?php echo base_url('assets/chart.js/Chart.js');?>"></script>
<style>
    .one {
        background-color: white;
        float:left;
        margin-right:20px;
        width: 49%;
        border-radius: 5px;
        padding: 15px 30px;
        min-height:200px;
    }
    .two {
        background-color: white;
        overflow:hidden;
        min-height:200px;
        border-radius: 5px;
        padding: 15px 30px;
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

    .add{
        font-size: 13px !important;
    }

    @media screen and (max-width: 500px) {
        .one {
            float: none;
            margin-right: 0;
            width: auto;
            border: 0;
            padding: 15px 20px;
        }

        .two, .three {
            margin-top: 10px;
            padding: 15px 20px;
        }
    }

    .input-rupiah {
        text-align: right;
    }


</style>

<style>

    .link{
        text-decoration: underline;
        color: rgba(20,143,143,1);
        font-size: 12px;
        cursor: pointer;
    }

    .tr-hover:hover{
        cursor: pointer;
        background: #ececec;
    }

    .tr-hover.selected{
        background: #ececec;
    }

    .btn-primary-empty{
        background: white !important;
        color: rgba(20,143,143,1);
        border: 1px solid rgba(20,143,143,1);
        transition: .2s;
        cursor: pointer;
    }

    .btn-primary-empty:hover{
        background: rgba(20,143,143,0.2) !important;
    }

    .btn-primary{
        background: rgba(20,143,143,11) !important;
        color: white;
        border: 1px solid white;
        transition: .2s;
    }

    .btn-primary:hover{
        background: white !important;
        color: rgba(20,143,143,1) !important;
        border: 1px solid rgba(20,143,143,1);
    }

    td, .card-body{
        font-size: 13px !important;
        color: #333333;
    }

    tr.tr-hover th, tr.tr-hover td{
        padding: 0.4rem !important;
    }

    .no-pointer{
        pointer-events: none;
    }

    .no-hover-style{
        background-color: transparent !important;
        cursor: inherit !important;
    }

    .left_side{
        background-color: rgba(183,214,170,1);
        color: rgba(40,77,23,1);
        text-align: center;

    }

    .middle_side{
        text-align: center;
        background-color: rgba(160,198,230,1);
        color: rgba(13,58,100,1);
    }

    .right_side{
        text-align: center;
        background-color: rgba(120,165,174,1);
        color: rgba(37,77,86,1);
    }

    .initial_cell{
        width: 12.5%;
    }

    .secondary_cell{
        width: 25%;
    }

    th{
        color: black;
    }

    .navbar-nav.sidebar{
        background: rgba(20,143,143,1);
    }

    .row_level1{
        background-color: rgba(208,226,242,1);
        text-align: right
    }

    .row_level2{
        background-color: rgba(160,198,230,1);
        text-align: right
    }

    .row_level3{
        background-color: rgba(113,169,218,1);
        text-align: right
    }

    @media (max-width: 576px) {
        .mobile-only {
            display: block;
        }
    }

    @media (min-width: 576px) {
        .mobile-only {
            display: none;
        }

        .neraca-total{
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
        }
    }

    .center{
        display: block;
        margin-left: auto;
        margin-right: auto;
        width: 50%;
    }

    .control-btn{
        font-size: 10px !important;
        border-radius: 0.1rem !important;
        padding: 0.1rem 0.3rem !important;
    }

</style>

<style>
    /* Mobile only */
    @media (max-width: 576px) {
        .desktop-only, .desktop-and-tablet, .desktop-only-tablecell, .desktop-and-tablet-tablecell, .desktop-and-tablet-inlinetable{
            display: none !important;
        }

        .mobile-only{
            display: block;
        }

        .main-carousel-img{
            height: 220px;
        }

        h2{
            font-size: 1.5rem;
        }

        /*h6{*/
        /*    font-size: 0.8rem;*/
        /*}*/

        h5{
            font-size: 0.95rem;
        }

        .card{
            display: block;
        }

        .card-group{
            display: flex;
            margin-bottom: -5px;

        }

        .mobile-full-width{
            width: 100% !important;
        }

        .product-image{
            height: 300px;
            width: 300px;
        }

        .main-section{
            margin-left: 3vw;
            margin-right: 3vw;
        }

        .pop-up-content, .chat-popup, .show-image-popup, .pop-up-review, .show-image-popup-product{
            min-width: 95vw;
        }

        .red-line{
            border-bottom: 2px solid #a50000;
            margin: 10px 0;
            width: 100%;
        }

        .pd-nominal{
            text-align: right;
            width: 65%;
            font-size:13px
        }

        .pd-title{
            text-align: right;
            width: 35%;
            font-size:13px
        }

        .status_order{
        }

        .product-lists{
            padding: 25px 1vw;
        }

        .card-body{
            padding: 0.8rem;
        }

        .link-card{
            padding: 5px;
        }

        .filter-title{
            font-size: 12px;
        }

        .left-td-profile{
            width: 100%
        }

        #catprod-main-content{
            margin-left: 2vw;
        }

        .messages{
            max-width: 80%;
        }

        .msg_sent{
            float: right;
            width: 100%;
        }

        .msg_container_base{
            height: calc(100vh - 115px);
        }


    }


    /* Desktop and Tablet */
    @media (min-width: 768px) {
        .desktop-and-tablet{
            display: block;
        }

        .tablecell{
            display: table-cell;
        }

        .mobile-only, .desktop-only{
            display: none;
        }

        .product-image{
            height: 320px;
            width: 320px;
        }

        .red-line{
            border-bottom: 2px solid #a50000;
            margin: 10px 0;
            width: 70%
        }

        .purchase_detail{
            padding: 0 20px;
        }

        .pd-nominal{
            text-align: right;
            width: 30%;
            font-size:13px
        }

        .pd-title{
            text-align: right;
            width: 70%;
            font-size:13px
        }

        .purchase-border-right{
            border-right: 1px solid lightgrey;
        }

        .desktop-and-tablet-inlinetable{
            display: inline-table;
        }

    }

    @media (max-width: 992px) {
        .product-filters{
            width: 100%;
        }
    }

    /* Desktop only */
    @media (min-width: 992px) {
        .desktop-only, .desktop-and-tablet{
            display: block;
        }

        .desktop-only-tablecell, .desktop-and-tablet-tablecell{
            display: table-cell;
        }

        .tablecell{
            display: table-cell;
        }

        .mobile-only{
            display: none;
        }

        .product-image{
            height: 400px;
            width: 400px;
        }

        .product-filters{
            width: 40%;
        }

    }


</style>
<style>
    .modal-confirm {
        color: #636363;
        width: 400px;
    }
    .modal-confirm .modal-content {
        padding: 20px;
        border-radius: 5px;
        border: none;
        text-align: center;
        font-size: 14px;
    }
    .modal-confirm .modal-header {
        border-bottom: none;
        position: relative;
    }
    .modal-confirm h4 {
        text-align: center;
        font-size: 26px;
        margin: 30px 0 -10px;
    }
    .modal-confirm .close {
        position: absolute;
        top: -5px;
        right: -2px;
    }
    .modal-confirm .modal-body {
        color: #999;
    }
    .modal-confirm .modal-footer {
        border: none;
        text-align: center;
        border-radius: 5px;
        font-size: 13px;
        padding: 10px 15px 25px;
    }
    .modal-confirm .modal-footer a {
        color: #999;
    }
    .modal-confirm .icon-box {
        width: 80px;
        height: 80px;
        margin: 0 auto;
        border-radius: 50%;
        z-index: 9;
        text-align: center;
        border: 3px solid #f15e5e;
    }
    .modal-confirm .icon-box i {
        color: #f15e5e;
        font-size: 46px;
        display: inline-block;
        margin-top: 13px;
    }
    .modal-confirm .btn, .modal-confirm .btn:active {
        color: #fff;
        border-radius: 4px;
        background: #60c7c1;
        text-decoration: none;
        transition: all 0.4s;
        line-height: normal;
        min-width: 120px;
        border: none;
        min-height: 40px;
        border-radius: 3px;
        margin: 0 5px;
    }
    .modal-confirm .btn-secondary {
        background: #c1c1c1;
    }
    .modal-confirm .btn-secondary:hover, .modal-confirm .btn-secondary:focus {
        background: #a8a8a8;
    }
    .modal-confirm .btn-danger {
        background: #f15e5e;
    }
    .modal-confirm .btn-danger:hover, .modal-confirm .btn-danger:focus {
        background: #ee3535;
    }
    .trigger-btn {
        display: inline-block;
        margin: 100px auto;
    }

    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        /* display: none; <- Crashes Chrome on hover */
        -webkit-appearance: none;
        margin: 0; /* <-- Apparently some margin are still there even though it's hidden */
    }

    input[type=number] {
        -moz-appearance:textfield; /* Firefox */
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button
    {
       font-size: 12px;
    }

    .ui-autocomplete {
        position: absolute;
        top: 100%;
        left: 0;
        z-index: 1000;
        display: none;
        float: left;
        min-width: 160px;
        padding: 5px 0;
        margin: 2px 0 0;
        list-style: none;
        font-size: 14px;
        text-align: left;
        background-color: #ffffff;
        border: 1px solid #cccccc;
        border: 1px solid rgba(0, 0, 0, 0.15);
        border-radius: 4px;
        -webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
        background-clip: padding-box;
    }

    .ui-autocomplete > li > div {
        display: block;
        padding: 3px 20px;
        clear: both;
        font-weight: normal;
        line-height: 1.42857143;
        color: #333333;
        white-space: nowrap;
    }

    .ui-state-hover,
    .ui-state-active,
    .ui-state-focus {
        text-decoration: none;
        color: #262626;
        background-color: #f5f5f5;
        cursor: pointer;
    }

    .ui-helper-hidden-accessible {
        border: 0;
        clip: rect(0 0 0 0);
        height: 1px;
        margin: -1px;
        overflow: hidden;
        padding: 0;
        position: absolute;
        width: 1px;
    }

</style>
<script>

    function show_snackbar(message){
        $('#snackbar').html(message);
        $('#snackbar').addClass('show');
        setTimeout(function(){ $('#snackbar').removeClass('show'); }, 3000);
    }

    admin_url = '<?php echo base_url('main/');?>';

    $('.Veil-non-hover').click(function(){
        $(this).fadeOut();
    });


    function convertToRupiah(angka)
    {
        var rupiah = '';
        var angkarev = angka.toString().split('').reverse().join('');
        for(var i = 0; i < angkarev.length; i++) if(i%3 == 0) rupiah += angkarev.substr(i,3)+'.';
        return 'Rp. '+rupiah.split('',rupiah.length-1).reverse().join('');
    }

    function isNumber(evt) {
        var theEvent = evt || window.event;
        var key = theEvent.keyCode || theEvent.which;
        var keyCode = key;
        key = String.fromCharCode(key);
        if (key.length == 0) return;
        var regex = /^[0-9.,\b]+$/;
        if(keyCode == 188 || keyCode == 190){
            return;
        }else{
            if (!regex.test(key)) {
                theEvent.returnValue = false;
                if (theEvent.preventDefault) theEvent.preventDefault();
            }
        }
    }


    function formatDate(date){
        var dd = date.getDate();
        var mm = date.getMonth()+1;
        var yyyy = date.getFullYear();
        if(dd<10) {dd='0'+dd}
        if(mm<10) {mm='0'+mm}
        date = dd+'/'+mm;
        return date
    }

    function LastDays (numDays) {
        var result = [];
        for (var i=numDays; i>0; i--) {
            var d = new Date();
            d.setDate(d.getDate() - i);
            result.push( formatDate(d) )
        }
        return(result);
    }

    function htmlDecode(input){
        var e = document.createElement('textarea');
        e.innerHTML = input;
        // handle case of empty input
        return e.childNodes.length === 0 ? "" : e.childNodes[0].nodeValue;
    }

</script>
<script>


    function datepicker_init(){
        if ($('[type="date"]').prop('type') != 'date' ) {
            $('[type="date"]').datepicker({
                dateFormat: 'yy-mm-dd'
            });
        }
    }

    $(document).ready(function(){
        datepicker_init();
    })




</script>