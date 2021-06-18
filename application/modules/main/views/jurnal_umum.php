

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Laporan Transaksi Umum</h1>
    <br>

    <button class="btn btn-primary add" style="width: 300px; font-size: 13px;"> Tambah Data</button>
    <br> <br>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Pengaturan</h6>
        </div>
        <div class="card-body">

            <div class="alert alert-info" role="alert" style="font-size: 12px;">
                Menampilkan pembelian vendor dan penjualan customer yang sudah dibayar saja
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
                    <select id="brand" name="brand" class="form-control form-control-sm" data-live-search="true" style="margin-right: 5px">
                        <?php
                            foreach($brands as $brand){
                                echo "<option value='".$brand->kode_brand."'>".$brand->nama_brand."</option>";
                            }

                        ?>
                    </select>
                    <select id="tipe_pembayaran" name="tipe_pembayaran" class="form-control form-control-sm" data-live-search="true" style="margin-right: 5px">
                        <option value="all">Semua Pembayaran</option>
                        <option value="REK"> Transaksi Rekening </option>
                        <option value="TUNAI"> Transaksi Tunai </option>
                        <option value="FREE"> Free </option>
                    </select>
                    <select id="cash_flow" name="cash_flow" class="form-control form-control-sm" data-live-search="true" style="margin-right: 5px">
                        <option value="all">Semua Arus Kas</option>
                        <option value="IN"> Pemasukan </option>
                        <option value="OUT"> Pengeluaran </option>
                    </select>
                    <button id="save_setting" class="btn btn-primary btn-sm" style="margin-right: 5px">Simpan</button>
                    <button id="export_excel" class="btn btn-warning btn-sm">Export Excel</button>
                </div>
            </form>
        </div>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Jurnal Umum</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr class="no-hover-style">
                        <th style="display: none;"> ID </th>
                        <th> Tanggal </th>
                        <th> Brand </th>
                        <th> Keterangan </th>
                        <th> Debet </th>
                        <th> Kredit </th>
                        <th> Mutasi </th>
                        <th>  </th>
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

<div class="modal fade" tabindex="-1" role="dialog" id="jurnal-umum-modal" style="z-index: 5000">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="jurnal-umum-form">

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label col-form-label-sm">Tanggal</label>
                        <div class="col-sm-9">
                            <input type="date" id="tgl_jurnal_umum" name="tgl_jurnal_umum" class="form-control form-control-sm form-active-control">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label col-form-label-sm">Brand</label>
                        <div class="col-sm-9">
                            <select id="brand_jurnal_umum" name="brand_jurnal_umum" class="form-control form-active-control form-control-sm">
                                <option value=""> -- Pilih Brand -- </option>
                                <option value="NONE"> Tanpa Brand </option>
                                <option value="KA"> Kedai Amarthya </option>
                                <option value="AF"> Amarthya Fashion </option>
                                <option value="AHF"> Amarthya Healthy Food </option>
                                <option value="AH"> Amarthya Herbal </option>
                            </select>
                        </div>
                        <div class="invalid-feedback invalid-brand">Data tidak valid</div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label col-form-label-sm">Tipe</label>
                        <div class="col-sm-9">
                            <select id="tipe_jurnal_umum" name="tipe_jurnal_umum" class="form-control form-active-control form-control-sm">
                                <option value=""> -- Pilih Tipe -- </option>
                                <option value="REK"> Transaksi Rekening </option>
                                <option value="TUNAI"> Transaksi Tunai </option>
                            </select>
                        </div>
                        <div class="invalid-feedback invalid-tipe">Data tidak valid</div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label col-form-label-sm"></label>
                        <div class="col-sm-9">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="cash_flow" id="debet_in" value="debet">
                                <label class="form-check-label" for="inlineRadio1">Debet (In)</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="cash_flow" id="kredit_out" value="kredit">
                                <label class="form-check-label" for="inlineRadio1">Kredit (Out)</label>
                            </div>
                        </div>
                        <div class="invalid-feedback invalid-cashflow">Data tidak valid</div>
                    </div>

                    <div class="form-group">
                        <label class="col-form-label">Keterangan</label>
                        <textarea id="keterangan_jurnal_umum" name="keterangan_jurnal_umum" class="form-control form-active-control"> </textarea>
                        <div class="invalid-feedback invalid-keterangan">Data tidak valid</div>
                    </div>

                    <div class="form-group">
                        <label class="col-form-label">Nominal</label>
                        <input type="number" id="nominal" name="nominal" class="form-control form-active-control">
                        <div class="invalid-feedback invalid-nominal">Data tidak valid</div>
                    </div>

                    <input type="hidden" id="id_jurnal_umum" name="id_jurnal_umum" val="0">
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


<style>
    tr:hover{
        cursor: pointer;
        background: rgba(20,143,143,0.5);
        transition: background-color 0.15s ease-in-out;
    }

</style>

<!-- Page level custom scripts -->

<!-- <script src="<?php echo base_url('assets/js/startbootstrap/demo/datatables-demo.js');?>"></script>-->

<script>

    document.title = "Laporan Transaksi - Amarthya Group";

    $('.delete').click(function(){
        if(confirm("Data akan dihapus permanen. Lanjutkan?")){
            $('.loading').css("display", "block");
            $('.Veil-non-hover').fadeIn();

            $.ajax({
                type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                url: admin_url + 'delete_jurnal_umum', // the url where we want to POST// our data object
                dataType: 'json',
                data: {id_jurnal_umum: $('#id_jurnal_umum').val()},
                success: function (response) {
                    if(response.Status == "OK"){
                        show_snackbar(response.Message);
                        $('#detail-product-modal').modal('hide');
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

    $('.add').click(function (e) {
        e.preventDefault();
        $('.invalid-feedback').css('display', 'none');
        $('#id_jurnal_umum').val(0);
        setTimeout(function() {$('.modal-dialog').scrollTop(0);}, 200);
        $('.modal-button-view-only').css('display', 'none');
        $('#vendor-form').trigger('reset');
        $('.form-active-control').prop('disabled', false);
        $('#jurnal-umum-modal').modal('toggle');
        $('.modal-button-save').css('display', 'block');
        document.getElementById("kredit_out").checked = false;
        document.getElementById("debet_in").checked = false;
    })

    $('.edit').click(function(e){
        $('.invalid-feedback').css('display', 'none');
        setTimeout(function() {$('.modal-dialog').scrollTop(0);}, 200);
        $('.modal-button-save').css('display', 'block');
        $('.modal-button-view-only').css('display', 'none');
        $('.form-active-control').prop('disabled', false);
    })

    $('.save').click(function(e){
        $('.loading').css("display", "block");
        $('.Veil-non-hover').fadeIn();
        $.ajax({
            type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url: admin_url + 'add_jurnal_umum', // the url where we want to POST// our data object
            dataType: 'json',
            data: $('#jurnal-umum-form').serialize(),
            success: function (response) {
                $('.invalid-feedback').css('display', 'none');
                if(response.Status == "OK"){
                    get_data();
                    $('#jurnal-umum-modal').modal('hide');
                } else if(response.Status == "FORMERROR") {
                    response.Error.forEach(function(error){
                        $('.'+ error +'').css('display', 'block');
                    })
                } else if(response.Status == "EXIST") {
                    show_snackbar('Nama Vendor sudah terdaftar');
                } else if(response.Status == "ERROR" ){
                    show_snackbar(response.Message);
                }

                $('.loading').css("display", "none");
                $('.Veil-non-hover').fadeOut();
            }
        })
    })
    

    $('.btn').click(function(e){
        e.preventDefault();
    })

    $('#save_setting').click(function(e){
        e.preventDefault();
        get_data();
    })

    $('#export_excel').click(function(e){
        e.preventDefault();

        start_date = $('#start_date').val()
        end_date = $('#end_date').val()
        brand = $('#brand').val()
        tipe_pembayaran = $('#tipe_pembayaran').val()
        cash_flow = $('#cash_flow').val()

        window.open(admin_url + 'excel_jurnal_umum?start=' + start_date + '&end=' + end_date + '&brand=' + brand + '&tipe=' + tipe_pembayaran + '&flow=' + cash_flow)
    })

    get_data();

    //get all products
    function get_data(){
        $('.loading').css("display", "block");
        $('.Veil-non-hover').fadeIn();

        start_date = $('#start_date').val()
        end_date = $('#end_date').val()
        brand = $('#brand').val()
        tipe_pembayaran = $('#tipe_pembayaran').val()
        cash_flow = $('#cash_flow').val()

        $('#dataTable').DataTable().destroy();
        $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            lengthChange: false,
            pageLength: 20,
            searching: false,
            bInfo: false,
            language: {
                search: ""
            },
            pagingType: "simple",
            ajax: {
                url     : admin_url + 'get_data_laporan?start=' + start_date + '&end=' + end_date + '&brand=' + brand + '&tipe=' + tipe_pembayaran + '&flow=' + cash_flow,
                type    : 'POST',
            },
            createdRow: function ( row, data, index ) {
                $('td', row).eq(0).css("display", "none");
            },
            columns: [
                {"data": "ID"},
                {
                    "data": {"tgl_order":"tgl_order"},
                    mRender : function(data, type, full) {
                        let dateTimeParts= data.tgl_order.split(/[- :]/);
                        dateTimeParts[1]--;
                        const temp_date = new Date(...dateTimeParts);

                        return temp_date.getDate() + '/' + (temp_date.getMonth() + 1) + '/' + temp_date.getFullYear();
                    }
                },
                {
                    "data": {"brand_order":"brand_order"},
                    mRender : function(data, type, full) {
                        if(data.brand_order == "KA"){
                            return "Kedai Amarthya"
                        } else if (data.brand_order == "AF") {
                            return "Amarthya Fashion"
                        } else if (data.brand_order == "AHF") {
                            return "Amarthya Healthy Food"
                        } else if (data.brand_order == "AH") {
                            return "Amarthya Herbal"
                        }

                    }
                },
                {
                    "data": {
                        "no_order":"no_order",
                        "NAMA":"NAMA"
                    },
                    mRender : function(data, type, full) {
                        if(data.NAMA == "empty"){
                            return data.no_order;
                        } else {
                            return data.no_order + "<br><span style='font-size: 11px'>"+ data.NAMA +"</span>";
                        }
                    }
                },
                {
                    "data": {"DEBET":"DEBET"},
                    mRender : function(data, type, full) {
                        return convertToRupiah(data.DEBET)
                    }
                },
                {
                    "data": {"KREDIT":"KREDIT"},
                    mRender : function(data, type, full) {
                        return convertToRupiah(data.KREDIT)
                    }
                },
                {
                    "data": {"MUTASI":"MUTASI"},
                    mRender : function(data, type, full) {
                        return convertToRupiah(data.MUTASI)
                    }
                },
                {
                    "data": {"tipe_order":"tipe_order"},
                    mRender : function(data, type, full) {
                        if(data.tipe_order == "REK"){
                            return "Rek"
                        } else if(data.tipe_order == "TUNAI"){
                            return "Tunai"
                        } else if (data.tipe_order == "FREE"){
                            return "Free"
                        }
                    }
                }
            ],
            initComplete: function (settings, json) {
                $('.loading').css("display", "none");
                $('.Veil-non-hover').fadeOut();
            }
        });
    }

    $('#dataTable').on( 'click', 'tbody tr', function () {

        data = $('#dataTable').DataTable().row( this ).data();

        if(data.TIPE == "ordervendor"){
            window.open(admin_url + 'order_vendor_detail?no=' + data.no_order)
        } else if (data.TIPE == "ordercustomer") {
            window.open(admin_url + 'order_detail?no=' + data.no_order)
        } else if (data.TIPE == 'datajurnal'){

            setTimeout(function() {$('.modal-dialog').scrollTop(0);}, 200);

            $('#id_jurnal_umum').val(htmlDecode(data.ID));
            $('#tgl_jurnal_umum').val(htmlDecode(data.custom_tgl));
            $('#keterangan_jurnal_umum').val(htmlDecode(data.no_order));
            $('#tipe_jurnal_umum').val(htmlDecode(data.tipe_order));
            $('#brand_jurnal_umum').val(htmlDecode(data.brand_order));

            if(data.DEBET != 0){
                document.getElementById("debet_in").checked = true;
                document.getElementById("kredit_out").checked = false;
                $('#nominal').val(data.DEBET);
            } else if(data.KREDIT != 0){
                document.getElementById("kredit_out").checked = true;
                document.getElementById("debet_in").checked = false;
                $('#nominal').val(data.KREDIT);
            }

            $('.form-active-control').prop('disabled', true);

            $('.modal-button-save').css('display', 'none');
            $('.modal-button-view-only').css('display', 'block');
            $('#jurnal-umum-modal').modal('toggle');
        }

    })


</script>
