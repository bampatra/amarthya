

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Lost and Breakage</h1>

<!--    <button class="btn btn-primary add" style="background: #a50000; color: white; width: 300px;"> Tambah Order </button>-->
    <br>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Pengaturan</h6>
        </div>
        <div class="card-body">

            <div class="alert alert-info" role="alert" style="font-size: 12px;">
                Menampilkan kalkulasi semua order (termasuk yang belum dibayar)
            </div>

            <form class="form-inline" style="margin-bottom: 5px;">
                <div class="form-group">
                    Dari tanggal
                    <input type="date" id="start_date" name="start_date" style="margin-right: 5px; margin-left: 5px" class="form-control form-control-sm" value="<?php echo date('Y')?>-01-01"> sampai tanggal
                    <input type="date" id="end_date" name="end_date" style="margin-right: 5px; margin-left: 5px" class="form-control form-control-sm" value="<?php echo date('Y-m-d')?>">
                    <button id="save_setting" class="btn btn-primary btn-sm" style="margin-right: 5px">Simpan</button>
                </div>
            </form>
<!--            <form class="form-inline">-->
<!--                <div class="form-group">-->
<!--                    <select id="is_paid" name="is_paid" class="form-control form-control-sm form-active-control" data-live-search="true" style="margin-right: 5px">-->
<!--                        <option value="all">Semua Status</option>-->
<!--                        <option value="0">Belum Bayar</option>-->
<!--                        <option value="1">Lunas</option>-->
<!--                    </select>-->
<!--                    <select id="metode_pembayaran" name="metode_pembayaran" class="form-control form-control-sm form-active-control" data-live-search="true" style="margin-right: 5px">-->
<!--                        <option value="all">Semua Metode Pembayaran</option>-->
<!--                        --><?php
//                        foreach($metode_pembayaran as $payment){
//                            echo "<option value='".$payment->html_id."'>".$payment->nama_metode_pembayaran."</option>";
//                        }
//
//                        ?>
<!--                    </select>-->
<!--                    <select id="jenis_transaksi" name="jenis_transaksi" class="form-control form-active-control form-control-sm" style="margin-right: 5px">-->
<!--                        <option value="all"> Semua Jenis Transaksi </option>-->
<!--                        --><?php
//                        foreach($jenis_transaksi as $jenis){
//                            echo "<option value='".$jenis->kode_jenis."'>".$jenis->nama_jenis."</option>";
//                        }
//
//                        ?>
<!--                    </select>-->
<!--                    <button id="save_setting" class="btn btn-primary btn-sm" style="margin-right: 5px">Simpan</button>-->
<!--                    <button id="export_excel" class="btn btn-warning btn-sm">Export Excel</button>-->
<!--                </div>-->
<!--            </form>-->
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Summary</h6>
        </div>
        <div class="card-body">
            <table style="width:100%">

                <tr class="no-hover-style">
                    <td><span style="font-size: 12px"> Service </span></td>
                    <td><span style="font-size: 12px"> Uniform </span></td>
                    <td><span style="font-size: 12px"> Lost and Breakage </span></td>
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
            <h6 class="m-0 font-weight-bold text-primary">Data Pengeluaran</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <button class="btn btn-primary add" style="width: 300px; font-size: 13px;"> Tambah Data</button>


                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr class="no-hover-style">
                        <th> Timestamp </th>
                        <th> Tipe </th>
                        <th> Deskripsi </th>
                        <th> Nominal </th>
                    </tr>
                    </thead>
                    <tbody id="main-content">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="lost-breakage-modal" style="z-index: 5000">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="lost-breakage-form">

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label col-form-label-sm">Tanggal</label>
                        <div class="col-sm-9">
                            <input type="date" id="timestamp_lost_and_breakage" name="timestamp_lost_and_breakage" class="form-control form-control-sm form-active-control">
                        </div>
                        <div class="invalid-feedback invalid-tanggal">Data tidak valid</div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label col-form-label-sm">Tipe</label>
                        <div class="col-sm-9">
                            <select id="tipe_lost_and_breakage" name="tipe_lost_and_breakage" class="form-control form-active-control form-control-sm">
                                <option value=""> -- Pilih Tipe -- </option>
                                <option value="LostBreakage"> Lost and Breakage </option>
                                <option value="Uniform"> Uniform </option>
                            </select>
                        </div>
                        <div class="invalid-feedback invalid-tipe">Data tidak valid</div>
                    </div>

                    <div class="form-group">
                        <label class="col-form-label">Deskripsi</label>
                        <textarea id="deskripsi_lost_and_breakage" name="deskripsi_lost_and_breakage" class="form-control form-active-control"> </textarea>
                        <div class="invalid-feedback invalid-deskripsi">Data tidak valid</div>
                    </div>

                    <div class="form-group">
                        <label class="col-form-label">Nominal</label>
                        <input type="number" id="nominal_lost_and_breakage" name="nominal_lost_and_breakage" class="form-control form-active-control">
                        <div class="invalid-feedback invalid-nominal">Data tidak valid</div>
                    </div>

                    <input type="hidden" id="id_lost_and_breakage" name="id_lost_and_breakage" val="0">
                </form>
            </div>
            <div class="modal-footer">
                <div class="modal-button-view-only">
                    <button type="button" class="btn btn-danger delete" data-dismiss="modal">Hapus</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary edit">Edit</button>
                </div>
                <div class="modal-button-save">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary save">Simpan</button>
                </div>

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

    document.title = "Lost and Breakage - Amarthya Group";


    const urlParams = new URLSearchParams(location.search);
    if(urlParams.has('status')){

    }

    $('.save').click(function(e){
        $('.loading').css("display", "block");
        $('.Veil-non-hover').fadeIn();
        $.ajax({
            type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url: admin_url + 'add_lost_and_breakage', // the url where we want to POST// our data object
            dataType: 'json',
            data: $('#lost-breakage-form').serialize(),
            success: function (response) {
                $('.invalid-feedback').css('display', 'none');
                if(response.Status == "OK"){
                    get_data();
                    $('#lost-breakage-modal').modal('hide');
                } else if(response.Status == "FORMERROR") {
                    response.Error.forEach(function(error){
                        $('.'+ error +'').css('display', 'block');
                    })
                } else if(response.Status == "ERROR" ){
                    show_snackbar(response.Message);
                }

                $('.loading').css("display", "none");
                $('.Veil-non-hover').fadeOut();
            }
        })
    })

    get_data();
    get_summary_transaksi();

    function get_data(){

        start_date = $('#start_date').val()
        end_date = $('#end_date').val()

        $('.loading').css("display", "block");
        $('.Veil-non-hover').fadeIn();

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
                url     : admin_url + 'get_lost_and_breakage?start=' + start_date + '&end=' + end_date,
                type    : 'POST',
            },
            createdRow: function ( row, data, index ) {
                // $('td', row).eq(0).css("display", "none");
            },
            columns: [
                {
                    "data": {"timestamp_lost_and_breakage": "timestamp_lost_and_breakage"},
                    mRender : function(data, type, full) {
                        let dateTimeParts= data.timestamp_lost_and_breakage.split(/[- :]/);
                        dateTimeParts[1]--;
                        const temp_date = new Date(...dateTimeParts);

                        return (temp_date.getDate() < 10 ? '0' : '') + temp_date.getDate() + '/' +
                            (temp_date.getMonth() < 10 ? '0' : '') + (temp_date.getMonth() + 1) + '/' +
                            temp_date.getFullYear();

                    }
                },
                {
                    "data": {
                        "tipe_lost_and_breakage": "tipe_lost_and_breakage"
                    },
                    mRender : function(data, type, full) {
                       if(data.tipe_lost_and_breakage == "LostBreakage"){
                           return "Lost and Breakage"
                       } else {
                           return data.tipe_lost_and_breakage
                       }

                    }
                },
                {"data": "deskripsi_lost_and_breakage"},
                {
                    "data": {"nominal_lost_and_breakage": "nominal_lost_and_breakage"},
                    mRender : function(data, type, full) {
                        return convertToRupiah(data.nominal_lost_and_breakage);

                    }
                }

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
                start: $('#start_date').val(),
                end: $('#end_date').val(),
                status: 'all',
                payment: 'all',
                jenis: 'all',
            },
            success: function (data) {

                html = '<td><h3>'+ convertToRupiah(data.service) +'</h3></td>\n' +
                    '   <td><h3>'+ convertToRupiah(data.uniform) +'</h3></td>\n' +
                    '   <td><h3>'+ convertToRupiah(data.lost_and_breakage) +'</h3></td>';

                $('#summary-content').html(html);

            }
        })
    }

    $('#dataTable').on( 'click', 'tbody tr', function () {
        data = $('#dataTable').DataTable().row( this ).data();

        setTimeout(function() {$('.modal-dialog').scrollTop(0);}, 200);

        $('#id_lost_and_breakage').val(htmlDecode(data.id_lost_and_breakage));
        $('#timestamp_lost_and_breakage').val(htmlDecode(data.custom_tgl));
        $('#deskripsi_lost_and_breakage').val(htmlDecode(data.deskripsi_lost_and_breakage));
        $('#tipe_lost_and_breakage').val(htmlDecode(data.tipe_lost_and_breakage));
        $('#nominal_lost_and_breakage').val(htmlDecode(data.nominal_lost_and_breakage));

        $('.form-active-control').prop('disabled', true);

        $('.modal-button-save').css('display', 'none');
        $('.modal-button-view-only').css('display', 'block');
        $('#lost-breakage-modal').modal('toggle');

    });

    $('.add').click(function (e) {
        e.preventDefault();
        $('.invalid-feedback').css('display', 'none');
        $('#id_lost_and_breakage').val(0);
        setTimeout(function() {$('.modal-dialog').scrollTop(0);}, 200);
        $('.modal-button-view-only').css('display', 'none');
        $('#lost-breakage-form').trigger('reset');
        $('.form-active-control').prop('disabled', false);
        $('#lost-breakage-modal').modal('toggle');
        $('.modal-button-save').css('display', 'block');
    })

    $('.edit').click(function(e){
        $('.invalid-feedback').css('display', 'none');
        setTimeout(function() {$('.modal-dialog').scrollTop(0);}, 200);
        $('.modal-button-save').css('display', 'block');
        $('.modal-button-view-only').css('display', 'none');
        $('.form-active-control').prop('disabled', false);
    })

    $('.delete').click(function(){
        if(confirm("Data akan dihapus permanen. Lanjutkan?")){
            $('.loading').css("display", "block");
            $('.Veil-non-hover').fadeIn();

            $.ajax({
                type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                url: admin_url + 'delete_lost_and_breakage', // the url where we want to POST// our data object
                dataType: 'json',
                data: {id_lost_and_breakage: $('#id_lost_and_breakage').val()},
                success: function (response) {
                    if(response.Status == "OK"){
                        show_snackbar(response.Message);
                        $('#lost-breakage-modal').modal('hide');
                        get_data();
                    } else if(response.Status == "ERROR" ){
                        show_snackbar(response.Message);
                    }
                    $('.loading').css("display", "none");
                    $('.Veil-non-hover').fadeOut();

                }
            })
        }
    })

    $('#save_setting').click(function(e){
        e.preventDefault();
        get_data();
    })

    // $('#export_excel').click(function(e){
    //     e.preventDefault();
    //
    //     // show_snackbar("Fitur belum tersedia");
    //
    //     start_date = $('#start_date').val()
    //     end_date = $('#end_date').val()
    //
    //     window.open(admin_url + 'excel_transaksi_eatery?status=' + status + '&payment=' + payment  + '&jenis=' + jenis + '&start=' + start_date + '&end=' + end_date);
    // })




</script>
