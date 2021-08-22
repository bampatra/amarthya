

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Problem Solving</h1>
    <br>

    <button class="btn btn-primary add" style="background: #a50000; color: white; width: 300px;"> Tambah Problem </button>
    <br> <br>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Problem</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <select id="status_problem" name="status_problem" class="form-control form-control-sm" data-live-search="true" style="width: 30%; float: left">
                    <option value="all">Semua Problem</option>
                    <option value="unsolved"> Belum Ada Solusi </option>
                    <option value="solved"> Selesai </option>
                </select>
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr class="no-hover-style">
                        <th style="display: none;"> ID </th>
                        <th style="width: 15%"> Kode Problem </th>
                        <th style="width: 18%"> Pesanan/Produk </th>
                        <th> Problem </th>
                        <th> Solusi </th>
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


<div class="modal fade" tabindex="-1" role="dialog" id="problemsolving-modal" style="z-index: 5000;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Problem</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="problemsolving-form">

                    <div class="form-group row">
                        <label for="staticEmail" class="col-sm-1 col-form-label">Kode</label>
                        <div class="col-sm-5">
                            <input type="text" readonly class="form-control" id="kode_problem_solving" name="kode_problem_solving">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputAddress">Topik</label>
                        <input type="text" class="form-control form-active-control" id="topik_problem_solving" name="topik_problem_solving">
                        <div class="invalid-feedback invalid-topik">Data tidak valid</div>
                    </div>
                    <div class="form-group">
                        <label for="inputAddress">Pesanan Terkait</label>
                        <input type="text" class="form-control form-active-control" id="no_order_problem_solving" name="no_order_problem_solving">
                        <div class="invalid-feedback invalid-pesanan">Pesanan tidak ditemukan</div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-6 mb-3">
                            <label>Detail Problem</label>
                            <textarea class="form-control form-active-control" id="detail_problem_solving" name="detail_problem_solving" rows="4"></textarea>
                            <div class="invalid-feedback invalid-detail">Data tidak valid</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Solusi</label>
                            <textarea class="form-control form-active-control" id="solusi_problem_solving" name="solusi_problem_solving" rows="4"></textarea>
                        </div>
                    </div>

                    <input type="hidden" id="id_problem_solving" name="id_problem_solving" val="0">
                </form>
            </div>
            <div class="modal-footer">
                <div class="modal-button-view-only">
                    <button type="button" class="btn btn-danger delete">Hapus</button>
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

    document.title = "Problem Solving - Amarthya Group";

    $('#status_problem').change(function(){
        get_problem_solving($(this).val());
    })

    get_problem_solving();

    //get all products
    function get_problem_solving(status = "all"){
        $('.loading').css("display", "block");
        $('.Veil-non-hover').fadeIn();

        $('#dataTable').DataTable().destroy();
        $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            lengthChange: false,
            searching: true,
            bInfo: false,
            language: {
                search: ""
            },
            pagingType: "simple",
            ajax: {
                url     : admin_url + 'get_problem_solving?status=' + status,
                type    : 'POST',
            },
            createdRow: function ( row, data, index ) {
                $('td', row).eq(0).css("display", "none");
            },
            columns: [
                {"data": "id_problem_solving"},
                {
                    "data":{
                        "kode_problem_solving": "kode_problem_solving",
                        "solusi_problem_solving": "solusi_problem_solving"
                    },
                    mRender : function(data, type, full) {
                        if(data.solusi_problem_solving == ""){
                            html = '<div class="alert alert-danger alert-payment" role="alert">\n' +
                                '                            <strong>' + data.kode_problem_solving + '</strong>\n' +
                                '                        </div>';
                        } else {
                            html = data.kode_problem_solving;
                        }

                        return html;
                    }

                },
                {"data": "no_order_problem_solving"},
                {
                    "data": {
                        "topik_problem_solving":"topik_problem_solving",
                        "detail_problem_solving":"detail_problem_solving",
                        "timestamp_create":"timestamp_create",
                        "username_create":"username_create"
                    },
                    mRender : function(data, type, full) {
                        html = '<strong style="font-size: 12px">'+ data.topik_problem_solving +'</strong><br>' +
                            '   <span>'+ data.detail_problem_solving +'</span><br>' +
                            '   <i style="font-size: 9px;">'+ data.timestamp_create +' oleh '+ data.username_create +'</i>';

                        return html;
                    }
                },
                {
                    "data": {
                        "solusi_problem_solving":"solusi_problem_solving",
                        "timestamp_solusi":"timestamp_solusi",
                        "username_solusi":"username_solusi"
                    },
                    mRender : function(data, type, full) {

                        if(data.solusi_problem_solving != ""){
                            html = '   <span>' + data.solusi_problem_solving + '</span><br>' +
                                '   <i style="font-size: 9px;">' + data.timestamp_solusi + ' oleh ' + data.username_solusi + '</i>';
                        } else {
                            html = "";
                        }

                        return html;
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
        $('body').addClass('modal-open');

        data = $('#dataTable').DataTable().row( this ).data()

        setTimeout(function() {$('.modal-dialog').scrollTop(0);}, 200);
        $('#id_problem_solving').val(htmlDecode(data.id_problem_solving));
        $('#no_order_problem_solving').val(htmlDecode(data.no_order_problem_solving));
        $('#kode_problem_solving').val(htmlDecode(data.kode_problem_solving));
        $('#topik_problem_solving').val(htmlDecode(data.topik_problem_solving));
        $('#detail_problem_solving').val(htmlDecode(data.detail_problem_solving));
        $('#solusi_problem_solving').val(htmlDecode(data.solusi_problem_solving));


        $('.form-active-control').prop('disabled', true);

        $('.modal-button-save').css('display', 'none');
        $('.modal-button-view-only').css('display', 'block');
        $('#problemsolving-modal').modal('toggle');


    });

    $('.add').click(function (e) {
        e.preventDefault();
        $('.invalid-feedback').css('display', 'none');
        $('#id_problem_solving').val(0);
        setTimeout(function() {$('.modal-dialog').scrollTop(0);}, 200);
        $('.modal-button-view-only').css('display', 'none');
        $('#problemsolving-form').trigger('reset');
        $('.form-active-control').prop('disabled', false);
        $('#problemsolving-modal').modal('toggle');
        $('.modal-button-save').css('display', 'block');
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
            url: admin_url + 'add_problem_solving', // the url where we want to POST// our data object
            dataType: 'json',
            data: $('#problemsolving-form').serialize(),
            success: function (response) {
                $('.invalid-feedback').css('display', 'none');
                if(response.Status == "OK"){
                    get_problem_solving();
                    $('#problemsolving-modal').modal('hide');
                } else if(response.Status == "FORMERROR") {
                    response.Error.forEach(function(error){
                        $('.'+ error +'').css('display', 'block');
                    })
                } else if(response.Status == "EXIST") {
                    show_snackbar('Nama problemsolving sudah terdaftar');
                } else if(response.Status == "ERROR" ){
                    show_snackbar(response.Message);
                }

                $('.loading').css("display", "none");
                $('.Veil-non-hover').fadeOut();
            }
        })
    })

    $('.delete').click(function(){
        if(confirm("Data akan dihapus permanen. Lanjutkan?")){
            $('.loading').css("display", "block");
            $('.Veil-non-hover').fadeIn();

            $.ajax({
                type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                url: admin_url + 'delete_problem_solving', // the url where we want to POST// our data object
                dataType: 'json',
                data: {id_problem_solving: $('#id_problem_solving').val()},
                success: function (response) {
                    if(response.Status == "OK"){
                        show_snackbar(response.Message);
                        $('#problemsolving-modal').modal('hide');
                        get_problem_solving();
                    } else if(response.Status == "ERROR" ){
                        show_snackbar(response.Message);
                    }

                    $('.loading').css("display", "none");
                    $('.Veil-non-hover').fadeOut();

                }
            })
        }
    })

</script>
