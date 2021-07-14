

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Bahan Dasar</h1>
    <br>

    <button class="btn btn-primary add" style="background: #a50000; color: white; width: 300px;"> Tambah Bahan Dasar </button>
    <br> <br>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Bahan Dasar</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <form class="form-inline" style="margin-bottom: 3px;">
                    <div class="form-group" style="margin-right: 5px;">
                        <select id="stock_status" name="stock_status" class="form-control form-control-sm form-active-control" data-live-search="true">
                            <option value="all">Semua Stok</option>
                            <option value="more">Stok Tidak Kosong</option>
                            <option value="none">Stok Kosong</option>
                        </select>
                    </div>
                    <button id="export_excel" class="btn btn-warning btn-sm">Export Excel</button>
                </form>

                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr class="no-hover-style">
                        <th style="display: none;"> ID </th>
                        <th> Nama Produk </th>
                        <th> Stok </th>
                        <th> Satuan </th>
                        <th> HP </th>
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


<div class="modal fade" tabindex="-1" role="dialog" id="input-product-modal" style="z-index: 5000">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bahan Dasar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="product-form">

                    <div class="form-row">
                        <div class="col-md-12 mb-3">
                            <label class="col-form-label">Nama Bahan Dasar</label>
                            <input type="text" id="nama_product"  name="nama_product" class="form-control form-active-control">
                            <div class="invalid-feedback invalid-namaproduct">Data tidak valid</div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-6 mb-3">
                            <label class="col-form-label">Satuan</label>
                            <input type="text" id="satuan_product"  name="satuan_product" class="form-control form-active-control">
                            <div class="invalid-feedback invalid-satuanproduct">Data tidak valid</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="col-form-label">HP</label>
                            <input type="number" id="HP_product"  name="HP_product" class="form-control form-active-control">
                            <div class="invalid-feedback invalid-HP">Data tidak valid</div>
                        </div>
                    </div>

                    <input type="hidden" id="id_product" name="id_product" val="0">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-modal" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary save">Simpan</button>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="detail-product-modal" style="z-index: 5000">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="nama_product"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Stok: <span class="stok_product"></span><br>
                <span class="edit link">Edit Produk</span>
                <br><br>
                <table>
                    <tr>
                        <td> HP</td>
                        <td class="HP_product"></td>
                    </tr>
                </table>

                <br>
                <span>Stok In/Out</span><br>
                <a id="detail_stok_in_out" href="" target="_blank" style="font-size: 12px"> Lihat detail Stok In/Out </a>

<!--                Table stok in out -->
                <div class="table-responsive">
                    <table class="table table-bordered" id="inOutDataTable" width="100%" cellspacing="0" style="font-size: 11px">
                        <thead>
                            <tr>
                                <th> Tanggal </th>
                                <th> Tipe </th>
                                <th> Stok </th>
                                <th> Expired </th>
                            </tr>
                        </thead>
                        <tbody id="in-out-content">

                        </tbody>
                    </table>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger delete">Hapus</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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

    var brand = "all", stock_status = "all";

    $('#stock_status').change(function(){
        stock_status = $(this).val();
        get_product(stock_status);
    })

    get_product();

    document.title = "Bahan Dasar - Amarthya Group";

    $('#export_excel').click(function(e){
        e.preventDefault();
        window.open(admin_url + 'excel_product?brand=BAHAN&stock_status=' + stock_status)
    });

    //get all products
    function get_product(stock_status = "all"){
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
                url     : admin_url + 'get_product?brand=BAHAN&stock_status=' + stock_status,
                type    : 'POST',
            },
            createdRow: function ( row, data, index ) {
                $('td', row).eq(0).css("display", "none");
            },
            columns: [
                {"data": "id_product"},
                {"data": "nama_product"},
                {"data": "STOK"},
                {"data": "satuan_product"},
                {
                    "data": {"HP_product":"HP_product"},
                    mRender : function(data, type, full) {
                        return convertToRupiah(data.HP_product)
                    }
                }

            ],
            initComplete: function (settings, json) {
                $('.loading').css("display", "none");
                $('.Veil-non-hover').fadeOut();
            }
        });
    }

    detail_toggled = false;

    $('.delete').click(function(){
        if(confirm("Data akan dihapus permanen. Lanjutkan?")){
            $('.loading').css("display", "block");
            $('.Veil-non-hover').fadeIn();

            $.ajax({
                type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                url: admin_url + 'delete_product', // the url where we want to POST// our data object
                dataType: 'json',
                data: {id_product: $('#id_product').val()},
                success: function (response) {
                    if(response.Status == "OK"){
                        show_snackbar(response.Message);
                        $('#detail-product-modal').modal('hide');
                        get_product();
                    } else if(response.Status == "ERROR" ){
                        show_snackbar(response.Message);
                    }

                    $('.loading').css("display", "none");
                    $('.Veil-non-hover').fadeOut();

                }
            })
        }
    })

    function in_out_init(id_product){

        $('#inOutDataTable').DataTable().destroy();

        $('#inOutDataTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            lengthChange: false,
            searching: false,
            bInfo: false,
            columnDefs: [ {
                targets: [1,2],
                orderable: false
            },
                {
                    targets:0,
                    type:"date-eu"
                }
            ],
            pagingType: "simple",
            ajax: {
                url     : admin_url + 'get_stok_in_out',
                data    : {id_product: id_product},
                type    : 'POST',
            },
            createdRow: function ( row, data, index ) {
                // $('td', row).eq(0).css("display", "none");
            },
            columns: [
                {
                    "data": {
                        "custom_tgl_in":"custom_tgl_in",
                        "custom_tgl_out":"custom_tgl_out",
                        "tipe_in_out":"tipe_in_out"
                    },
                    mRender : function(data, type, full) {
                        if(data.tipe_in_out == "IN"){
                            return data.custom_tgl_in
                        } else {
                            return data.custom_tgl_out
                        }

                    }
                },
                {"data": "tipe_in_out"},
                {"data": "stok_in_out"},
                {
                    "data": {"custom_tgl_expired":"custom_tgl_expired"},
                    mRender : function(data, type, full) {
                        if(data.custom_tgl_expired == '0000-00-00'){
                            return '';
                        } else {
                            return data.custom_tgl_expired;
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
        detail_toggled = false;
        $('body').addClass('modal-open');

        data = $('#dataTable').DataTable().row( this ).data();

        setTimeout(function() {$('.modal-dialog').scrollTop(0);}, 200);
        $('#id_product').val(htmlDecode(data.id_product));
        $('#nama_product').val(htmlDecode(data.nama_product));
        $('#satuan_product').val(htmlDecode(data.satuan_product));
        $('#HP_product').val(htmlDecode(data.HP_product));

        $('.stok_product').html(data.STOK);

        $("#detail_stok_in_out").attr("href", admin_url + 'stok_in_out?product=' + data.id_product)

        $('#detail-product-modal').modal('toggle');

        //inside detail-product-modal
        $('.nama_product').html(htmlDecode(data.nama_product));
        $('.HP_product').html(convertToRupiah(htmlDecode(data.HP_product)));

        in_out_init(data.id_product);

    });

    $('.add').click(function (e) {
        detail_toggled = false;
        e.preventDefault();
        $('.invalid-feedback').css('display', 'none');
        $('#id_product').val(0);
        setTimeout(function() {$('.modal-dialog').scrollTop(0);}, 200);
        $('#product-form').trigger('reset');
        $('#input-product-modal').modal('toggle');
    })

    $('.edit').click(function(e){
        detail_toggled = true;
        $('.invalid-feedback').css('display', 'none');
        setTimeout(function() {$('.modal-dialog').scrollTop(0);}, 200);
        $('#detail-product-modal').modal('hide');


        $('#detail-product-modal').on('hidden.bs.modal', function () {
            if(detail_toggled){
                $('#input-product-modal').modal('toggle');
            }
        })


    })

    // if(detail_toggled){
    //     $('#input-product-modal').on('hidden.bs.modal', function () {
    //         $('#detail-product-modal').modal('toggle');
    //     })
    // }


    $('.save').click(function(e){
        $('.loading').css("display", "block");
        $('.Veil-non-hover').fadeIn();
        $.ajax({
            type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url: admin_url + 'add_product?bahan=true', // the url where we want to POST// our data object
            dataType: 'json',
            data: $('#product-form').serialize(),
            success: function (response) {
                $('.invalid-feedback').css('display', 'none');
                if(response.Status == "OK"){
                    get_product(stock_status);
                    $('#input-product-modal').modal('hide');
                } else if(response.Status == "FORMERROR") {
                    response.Error.forEach(function(error){
                        $('.'+ error +'').css('display', 'block');
                    })
                } else if(response.Status == "EXIST") {
                    show_snackbar('Nama produk sudah terdaftar');
                } else if(response.Status == "ERROR" ){
                    show_snackbar(response.Message);
                }

                $('.loading').css("display", "none");
                $('.Veil-non-hover').fadeOut();
            }
        })
    })



</script>
