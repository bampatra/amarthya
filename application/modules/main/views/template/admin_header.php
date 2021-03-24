<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Amarthya Group</title>
    <link rel="icon" href="<?php echo base_url('assets/images/logo_amarthya.png');?>" type = "image/x-icon">

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


    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo base_url('assets/jquery/jquery.min.js');?>"></script>
    <script src="http://code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
    <script src="<?php echo base_url('assets/js/moment.js');?>"></script>
    <script src="<?php echo base_url('assets/js/cropper.js');?>"></script>
    <script src="<?php echo base_url('assets/bootstrap/js/bootstrap.bundle.js');?>"></script>

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?php echo base_url('assets/jquery-easing/jquery.easing.min.js');?>"></script>


    <!-- Page level plugins -->

    <script src="<?php echo base_url('assets/datatables/jquery.dataTables.min.js');?>"></script>
    <script src="<?php echo base_url('assets/datatables/dataTables.bootstrap4.min.js');?>"></script>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

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
            z-index: 1; /* Add a z-index if needed */
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
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?php echo base_url('admin')?>">
            <div class="sidebar-brand-text mx-3">YAPN</sup></div>
        </a>

        <!-- Divider -->
        <hr class="sidebar-divider my-0">

        <!-- Nav Item - Dashboard -->
        <li class="nav-item active">
            <a class="nav-link" href="<?php echo base_url('admin')?>">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span></a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            Menu
        </div>

        <!-- Nav Item - Pages Collapse Menu -->
<!--        <li class="nav-item">-->
<!--            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseMaster" aria-expanded="true" aria-controls="collapseMaster">-->
<!--                <i class="fas fa-fw fa-key"></i>-->
<!--                <span>Staff</span>-->
<!--            </a>-->
<!--            <div id="collapseMaster" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">-->
<!--                <div class="bg-white py-2 collapse-inner rounded">-->
<!--                    <h6 class="collapse-header">Option:</h6>-->
<!--                    <a class="collapse-item" href="--><?php //echo base_url('main/staff')?><!--">Data Staff</a>-->
<!--                    <a class="collapse-item" href="--><?php //echo base_url('main/staff-input')?><!--">Input Staff</a>-->
<!--                </div>-->
<!--            </div>-->
<!--        </li>-->

        <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url('main/staff')?>">
                <i class="fas fa-fw fa-money-bill-wave"></i>
                <span>Staff</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url('main/customer')?>">
                <i class="fas fa-fw fa-money-bill-wave"></i>
                <span>Customer</span>
            </a>
        </li>


        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            Akun
        </div>

        <!-- Nav Item - Charts -->
        <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url('admin/logout')?>">
                <i class="fas fa-fw fa-sign-out-alt"></i>
                <span>Logout</span></a>
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
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow mobile-only">

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

    @media screen and (max-width: 400px) {
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

</style>

<style>
    /* Mobile only */
    @media (max-width: 576px) {
        .desktop-only, .desktop-and-tablet, .desktop-only-tablecell, .desktop-and-tablet-tablecell, .desktop-and-tablet-inlinetable{
            display: none;
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

    function active_status(status){
        if(status == "0"){
            return 'NON AKTIF';
        } else if (status == "1") {
            return 'AKTIF';
        }
    }

    function convertToRupiah(angka)
    {
        var rupiah = '';
        var angkarev = angka.toString().split('').reverse().join('');
        for(var i = 0; i < angkarev.length; i++) if(i%3 == 0) rupiah += angkarev.substr(i,3)+'.';
        return 'Rp. '+rupiah.split('',rupiah.length-1).reverse().join('')+',00';
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



    function status_pesanan(status, confirm_payment, is_refunded = 0){
        switch(status) {
            case '1':
                if(confirm_payment == 0){
                    return "Diterima - Belum Bayar";
                } else {
                    return "Diterima - Menunggu Pengecekan";
                }

                break;
            case '2':
                return "Diproses";
                break;
            case '3':
                return "Dikirim";
                break;
            case '4':
                return "Selesai";
                break;
            case '5':
                if(is_refunded == 0){
                    return "Dibatalkan";
                } else {
                    return "Dibatalkan - Dana Dikembalikan";
                }
                break;
        }
    }

    function number_format(number, decimals, dec_point, thousands_sep) {
        // *     example: number_format(1234.56, 2, ',', ' ');
        // *     return: '1 234,56'
        number = (number + '').replace('.', '').replace(' ', '');
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? '.' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? ',' : dec_point,
            s = '',
            toFixedFix = function(n, prec) {
                var k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            };
        // Fix for IE parseFloat(0.55).toFixed(0) = 0;
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split(',');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
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
    function statistic(data, with_currency = true, label = ''){
        var ctx = document.getElementById("myAreaChart");
        var myLineChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: LastDays(10),
                datasets: [{
                    label: label,
                    lineTension: 0.3,
                    backgroundColor: "rgba(78, 115, 223, 0.05)",
                    borderColor: "rgba(78, 115, 223, 1)",
                    pointRadius: 3,
                    pointBackgroundColor: "rgba(78, 115, 223, 1)",
                    pointBorderColor: "rgba(78, 115, 223, 1)",
                    pointHoverRadius: 3,
                    pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                    pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                    pointHitRadius: 10,
                    pointBorderWidth: 2,
                    data: data,
                }],
            },
            options: {
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 10,
                        right: 25,
                        top: 25,
                        bottom: 0
                    }
                },
                scales: {
                    xAxes: [{
                        time: {
                            unit: 'date'
                        },
                        gridLines: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            maxTicksLimit: 7
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            maxTicksLimit: 5,
                            beginAtZero: true,
                            padding: 10,
                            callback: function(value, index, values) {
                                if(with_currency){
                                    return 'Rp. ' + number_format(value);
                                } else {
                                    return value;
                                }

                            }
                        },
                        gridLines: {
                            color: "rgb(234, 236, 244)",
                            zeroLineColor: "rgb(234, 236, 244)",
                            drawBorder: false,
                            borderDash: [2],
                            zeroLineBorderDash: [2]
                        }
                    }],
                },
                legend: {
                    display: false
                },
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    titleMarginBottom: 10,
                    titleFontColor: '#6e707e',
                    titleFontSize: 14,
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    intersect: false,
                    mode: 'index',
                    caretPadding: 10,
                    callbacks: {
                        label: function(tooltipItem, chart) {
                            var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                            if(with_currency){
                                return datasetLabel + ' Rp. ' + number_format(tooltipItem.yLabel);
                            } else {
                                return datasetLabel + tooltipItem.yLabel;
                            }

                        }
                    }
                }
            }
        });
    }

    function datatable_init(order_by = 1, scrollX = false){
        //fungsi untuk filtering data berdasarkan tanggal


        var start_date;
        var end_date;
        var DateFilterFunction = (function (oSettings, aData, iDataIndex) {
            var dateStart = parseDateValue(start_date);
            var dateEnd = parseDateValue(end_date);
            //Kolom tanggal yang akan kita gunakan berada dalam urutan 2, karena dihitung mulai dari 0
            //nama depan = 0
            //nama belakang = 1
            //tanggal terdaftar =2
            var evalDate= parseDateValue(aData[1]);
            if ( ( isNaN( dateStart ) && isNaN( dateEnd ) ) ||
                ( isNaN( dateStart ) && evalDate <= dateEnd ) ||
                ( dateStart <= evalDate && isNaN( dateEnd ) ) ||
                ( dateStart <= evalDate && evalDate <= dateEnd ) )
            {
                return true;
            }
            return false;
        });

        // fungsi untuk converting format tanggal dd/mm/yyyy menjadi format tanggal javascript menggunakan zona aktubrowser
        function parseDateValue(rawDate) {
            var dateArray= rawDate.split("/");
            var parsedDate= new Date(dateArray[2], parseInt(dateArray[1])-1, dateArray[0]);  // -1 because months are from 0 to 11
            return parsedDate;
        }

        $( document ).ready(function() {
            //konfigurasi DataTable pada tabel dengan id example dan menambahkan  div class dateseacrhbox dengan dom untuk meletakkan inputan daterangepicker
            var $dTable = $('#dataTable').DataTable({
                "dom": "<'row'<'col-sm-4'l><'col-sm-5' <'datesearchbox'>><'col-sm-3'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                "order": [[ order_by, "desc" ]],
                "scrollX": scrollX
            });

            $(this).val('');
            start_date = '';
            end_date = '';
            $.fn.dataTable.ext.search.splice($.fn.dataTable.ext.search.indexOf(DateFilterFunction, 1));
            $dTable.draw();

            //menambahkan daterangepicker di dalam datatables
            $("div.datesearchbox").html('<div class="input-group"> <div class="input-group-addon"> <i class="glyphicon glyphicon-calendar"></i> </div><input type="text" class="form-control pull-right" id="datesearch" placeholder="Search by date range.."> </div>');

            document.getElementsByClassName("datesearchbox")[0].style.textAlign = "right";

            //konfigurasi daterangepicker pada input dengan id datesearch
            $('#datesearch').daterangepicker({
                autoUpdateInput: false
            });

            //menangani proses saat apply date range
            $('#datesearch').on('apply.daterangepicker', function (ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                start_date = picker.startDate.format('DD/MM/YYYY');
                end_date = picker.endDate.format('DD/MM/YYYY');
                $.fn.dataTableExt.afnFiltering.push(DateFilterFunction);
                $dTable.draw();
            });

            $('#datesearch').on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('');
                start_date = '';
                end_date = '';
                $.fn.dataTable.ext.search.splice($.fn.dataTable.ext.search.indexOf(DateFilterFunction, 1));
                $dTable.draw();
            });
        });
    }


    function convertDate(inputFormat) {
        function pad(s) { return (s < 10) ? '0' + s : s; }
        var d = new Date(inputFormat.replace(/\s/, 'T'))
        return [pad(d.getDate()), pad(d.getMonth()+1), d.getFullYear()].join('/')
    }

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