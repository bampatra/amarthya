

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Daftar Transaksi Eatery</h1>

<!--    <button class="btn btn-primary add" style="background: #a50000; color: white; width: 300px;"> Tambah Order </button>-->
    <br>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Pengaturan</h6>
        </div>
        <div class="card-body">

            <div class="alert alert-info" role="alert" style="font-size: 12px;">
                Menampilkan semua order (termasuk yang belum dibayar)
            </div>

            <form class="form-inline" style="margin-bottom: 5px;">
                <div class="form-group">
                    Dari tanggal
                    <input type="date" id="start_date" name="start_date" style="margin-right: 5px; margin-left: 5px" class="form-control form-control-sm" value="<?php echo date('Y')?>-01-01"> sampai tanggal
                    <input type="date" id="end_date" name="end_date" style="margin-right: 5px; margin-left: 5px" class="form-control form-control-sm" value="<?php echo date('Y-m-d')?>">
                </div>
            </form>
            <form class="form-inline">
                <div class="form-group">
                    <select id="is_paid" name="is_paid" class="form-control form-control-sm form-active-control" data-live-search="true" style="margin-right: 5px">
                        <option value="all">Semua Status</option>
                        <option value="0">Belum Bayar</option>
                        <option value="1">Lunas</option>
                    </select>
                    <select id="metode_pembayaran" name="metode_pembayaran" class="form-control form-control-sm form-active-control" data-live-search="true" style="margin-right: 5px">
                        <option value="all">Semua Metode Pembayaran</option>
                        <?php
                        foreach($metode_pembayaran as $payment){
                            echo "<option value='".$payment->html_id."'>".$payment->nama_metode_pembayaran."</option>";
                        }

                        ?>
                    </select>
                    <select id="jenis_transaksi" name="jenis_transaksi" class="form-control form-active-control form-control-sm" style="margin-right: 5px">
                        <option value="all"> Semua Jenis Transaksi </option>
                        <?php
                        foreach($jenis_transaksi as $jenis){
                            echo "<option value='".$jenis->kode_jenis."'>".$jenis->nama_jenis."</option>";
                        }

                        ?>
                    </select>
                    <button id="save_setting" class="btn btn-primary btn-sm" style="margin-right: 5px">Simpan</button>
                    <button id="export_excel" class="btn btn-warning btn-sm">Export Excel</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Summary</h6>
        </div>
        <div class="card-body">
            <table style="width:100%">

                <tr class="no-hover-style">
                    <td><span style="font-size: 12px"> Grand Total </span></td>
                    <td><span style="font-size: 12px"> Tax </span></td>
                    <td><span style="font-size: 12px"> Service </span></td>
                </tr>
                <tr class="no-hover-style" id="summary-content">
                    <td><h3>Rp. xxx</h3></td>
                    <td><h3>Rp. xxx</h3></td>
                    <td><h3>Rp. xxx</h3></td>
                </tr>

            </table>

        </div>
    </div>


    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Transaksi</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">


                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr class="no-hover-style">
                        <th> Tgl Order </th>
                        <th> No Order </th>
                        <th> Jenis Transaksi </th>
                        <th> Grand Total </th>
                        <th style="width: 10%"> Tax </th>
                        <th> Service </th>
                        <th> Payment </th>
                        <th> Status  </th>
                    </tr>
                    </thead>
                    <tbody id="main-content">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->


<style>
    tr:hover{
        cursor: pointer;
        background: rgba(20,143,143,0.5);
        transition: background-color 0.15s ease-in-out;
    }

    .detail-row{
        display: table;
        width: 100%; /*Optional*/
        table-layout: fixed; /*Optional*/
        border-spacing: 10px; /*Optional*/
    }

    .detail-column{
        display: table-cell;
    }

    .alert-payment{
        padding: 0.2rem 0.75rem;
        margin: 0;
        text-align: right;
        width: fit-content;
        font-size: 11px;
    }

    .product-item{
        border: 1px solid rgba(20,143,143,0.3);
        padding: 7px;
        margin-top:5px;
    }

    span{
        font-size: 13px !important;
    }

</style>

<!-- Page level custom scripts -->

<!-- <script src="<?php echo base_url('assets/js/startbootstrap/demo/datatables-demo.js');?>"></script>-->

<script>

    document.title = "Daftar Transaksi Eatery - Amarthya Group";

    var status = "all", payment = "all", jenis = "all";

    $('#is_paid').change(function(){
        status = $(this).val();
    })

    $('#metode_pembayaran').change(function(){
        payment = $(this).val();
    })

    $('#jenis_transaksi').change(function(){
        jenis = $(this).val();
    })

    $('#save_setting').click(function(e){
        e.preventDefault();
        get_order_m(status, payment, jenis);
    })


    const urlParams = new URLSearchParams(location.search);
    if(urlParams.has('status')){
        get_order_m(urlParams.get('status'));
        $('#is_paid').val(urlParams.get('status'));
        status = urlParams.get('status');
    } else {
        get_order_m();
    }

    function get_order_m(status = "all", payment = "all", jenis = "all"){

        start_date = $('#start_date').val()
        end_date = $('#end_date').val()

        $('.loading').css("display", "block");
        $('.Veil-non-hover').fadeIn();

        get_summary_transaksi();

        $('#dataTable').DataTable().destroy();
        $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            lengthChange: false,
            pageLength: 20,
            searching: true,
            bInfo: false,
            language: {
                search: ""
            },
            pagingType: "simple",
            ajax: {
                url     : admin_url + 'get_order_eatery_m?status=' + status + '&payment=' + payment  + '&jenis=' + jenis + '&start=' + start_date + '&end=' + end_date,
                type    : 'POST',
            },
            createdRow: function ( row, data, index ) {
                // $('td', row).eq(0).css("display", "none");
            },
            columns: [
                {
                    "data": {"tgl_order_eatery": "tgl_order_eatery"},
                    mRender : function(data, type, full) {
                        let dateTimeParts= data.tgl_order.split(/[- :]/);
                        dateTimeParts[1]--;
                        const temp_date = new Date(...dateTimeParts);

                        return (temp_date.getDate() < 10 ? '0' : '') + temp_date.getDate() + '/' +
                            (temp_date.getMonth() < 10 ? '0' : '') + (temp_date.getMonth() + 1) + '/' +
                            temp_date.getFullYear() + " " +
                            (temp_date.getHours() < 10 ? '0' : '') + temp_date.getHours() + ':' +
                            (temp_date.getMinutes() < 10 ? '0' : '') + temp_date.getMinutes() + ':' +
                            (temp_date.getSeconds() < 10 ? '0' : '') + temp_date.getSeconds();

                    }
                },
                {
                    "data": {
                        "no_order_eatery": "no_order_eatery",
                        "jenis_transaksi": "jenis_transaksi",
                        "catatan_informasi": "catatan_informasi"
                    },
                    mRender : function(data, type, full) {
                        if(data.jenis_transaksi == "GoFood" || data.jenis_transaksi == "GrabFood"){
                            return data.catatan_informasi;
                        } else {
                            return data.no_order_eatery;
                        }

                    }
                },
                {"data": "jenis_transaksi"},
                {
                    "data": {"grand_total_order": "grand_total_order"},
                    mRender : function(data, type, full) {
                        return convertToRupiah(data.grand_total_order);

                    }
                },
                {
                    "data": {"tax_order": "tax_order"},
                    mRender : function(data, type, full) {
                        return convertToRupiah(data.tax_order);

                    }
                },
                {
                    "data": {"service_order": "service_order"},
                    mRender : function(data, type, full) {
                        return convertToRupiah(data.service_order);

                    }
                },
                {"data": "nama_metode_pembayaran"},
                {
                    "data": {"is_paid": "is_paid"},
                    mRender : function(data, type, full) {
                        if(data.is_paid == "0"){
                            html = '<div class="alert alert-danger alert-payment" role="alert">\n' +
                                '                            <strong>BELUM BAYAR</strong>\n' +
                                '                        </div>';
                        } else {
                            html = '<div class="alert alert-success alert-payment" role="alert">\n' +
                                '                            <strong>LUNAS</strong>\n' +
                                '                        </div>';
                        }

                        return html;

                    }
                },

            ],
            initComplete: function (settings, json) {
                $('.loading').css("display", "none");
                $('.Veil-non-hover').fadeOut();
            }
        });
    }

    function get_summary_transaksi(){
        $.ajax({
            type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url: admin_url + 'get_summary_transaksi', // the url where we want to POST// our data object
            dataType: 'json',
            data: {
                status: status,
                payment: payment,
                start: $('#start_date').val(),
                end: $('#end_date').val(),
                jenis: jenis
            },
            success: function (response) {
                html = '<td><h3>'+ convertToRupiah(response.data[0].data) +'</h3></td>\n' +
                    '   <td><h3>'+ convertToRupiah(response.data[1].data) +'</h3></td>\n' +
                    '   <td><h3>'+ convertToRupiah(response.data[2].data) +'</h3></td>';

                $('#summary-content').html(html);

            }
        })
    }

    $('#dataTable').on( 'click', 'tbody tr', function () {
        rowData = $('#dataTable').DataTable().row( this ).data();
        window.open(admin_url + 'POS_transaksi_detail?no=' + rowData.no_order_eatery);

    });

    $('#export_excel').click(function(e){
        e.preventDefault();

        // show_snackbar("Fitur belum tersedia");

        start_date = $('#start_date').val()
        end_date = $('#end_date').val()

        window.open(admin_url + 'excel_transaksi_eatery?status=' + status + '&payment=' + payment  + '&jenis=' + jenis + '&start=' + start_date + '&end=' + end_date);
    })




</script>
