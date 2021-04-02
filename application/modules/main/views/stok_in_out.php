

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800" id="nama_product"><?php print_r($product[0]['nama_product']) ?></h1>
    <br>

    <button class="btn btn-primary add" style="background: #a50000; color: white; width: 300px;"> Tambah Data </button>
    <br> <br>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data In Out</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th style="display: none;"> ID </th>
                        <th> Tipe </th>
                        <th> Stok </th>
                        <th> IN </th>
                        <th> OUT </th>
                        <th> EXP </th>
                        <th> Catatan </th>
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

<div class="modal fade" tabindex="-1" role="dialog" id="inout-modal" style="z-index: 5000">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Stok In/Out</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="inout-form">
                    <div class="form-group" >
                        <label class="col-form-label">Stok In/Out</label>
                        <select id="tipe_in_out" name="tipe_in_out" class="form-control form-active-control">
                            <option value="IN"> Stok In </option>
                            <option value="OUT"> Stok Out </option>
                        </select>
                        <div class="invalid-feedback invalid-tipe">Data tidak valid</div>
                    </div>

                    <div class="form-group">
                        <label class="col-form-label">Jumlah Stok</label>
                        <input type="number" id="stok_in_out"  name="stok_in_out" class="form-control form-active-control">
                        <div class="invalid-feedback invalid-stok">Data tidak valid</div>
                    </div>

                    <div class="tgl_stok" id="tgl_stok_in">
                        <div class="form-group">
                            <label class="col-form-label">Tanggal Masuk</label>
                            <input type="date" id="tgl_in" name="tgl_in" class="form-control form-active-control">
                            <div class="invalid-feedback invalid-tanggalin">Data tidak valid</div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label">Tanggal Expired</label>
                            <input type="date" id="tgl_expired" name="tgl_expired" class="form-control form-active-control">
                            <div class="invalid-feedback invalid-tanggalexpired">Data tidak valid</div>
                        </div>
                    </div>
                    <div class="tgl_stok" id="tgl_stok_out">
                        <div class="form-group">
                            <label class="col-form-label">Tanggal Out</label>
                            <input type="date" id="tgl_out" name="tgl_out" class="form-control form-active-control">
                            <div class="invalid-feedback invalid-tanggalout">Data tidak valid</div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-form-label">Catatan</label>
                        <textarea id="catatan_in_out" name="catatan_in_out" class="form-control form-active-control"> </textarea>
                    </div>


                    <input type="hidden" id="id_stok_in_out" name="id_stok_in_out" val="0">
                </form>
            </div>
            <div class="modal-footer">
                <div class="modal-button-view-only">
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

    document.title = "Detail Stok " + $('#nama_product').html() + " - Amarthya Group";

    detail_toggled = false;

    $("#tgl_stok_out").hide();

    $("#tipe_in_out").change(function () {
        var tipe = $( "#tipe_in_out option:selected" ).val();

        $('.tgl_stok').hide();
        if(tipe=="IN") {
            //show 2 form fields here and show div
            $("#tgl_stok_in").show();
        } else {
            $("#tgl_stok_out").show();
        }
    });

    const urlParams = new URLSearchParams(window.location.search);
    var id_product = urlParams.get('product')

    get_in_out();

    function get_in_out(){
        $('.loading').css("display", "block");
        $('.Veil-non-hover').fadeIn();

        $('#dataTable').DataTable().destroy();
        $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            lengthChange: false,
            searching: false,
            bInfo: false,
            pagingType: "simple",
            ajax: {
                url     : admin_url + 'get_stok_in_out',
                data    : {id_product: id_product},
                type    : 'POST',
            },
            createdRow: function ( row, data, index ) {
                $('td', row).eq(0).css("display", "none");
            },
            columns: [
                {"data": "id_stok_in_out"},
                {"data": "tipe_in_out"},
                {"data": "stok_in_out"},
                {
                    "data": {"custom_tgl_in":"custom_tgl_in"},
                    mRender : function(data, type, full) {
                        if(data.custom_tgl_in == '0000-00-00'){
                            return '';
                        } else {
                            return data.custom_tgl_in;
                        }
                    }
                },
                {
                    "data": {"custom_tgl_out":"custom_tgl_out"},
                    mRender : function(data, type, full) {
                        if(data.custom_tgl_out == '0000-00-00'){
                            return '';
                        } else {
                            return data.custom_tgl_out;
                        }
                    }
                },
                {
                    "data": {"custom_tgl_expired":"custom_tgl_expired"},
                    mRender : function(data, type, full) {
                        if(data.custom_tgl_expired == '0000-00-00'){
                            return '';
                        } else {
                            return data.custom_tgl_expired;
                        }
                    }
                },
                {"data": "catatan_in_out"}
            ],
            initComplete: function (settings, json) {
                $('.loading').css("display", "none");
                $('.Veil-non-hover').fadeOut();
            }
        });
    }


    $('#dataTable').on( 'click', 'tbody tr', function () {
        $('body').addClass('modal-open');

        data = $('#dataTable').DataTable().row( this ).data();


        setTimeout(function() {$('.modal-dialog').scrollTop(0);}, 200);

        $('#id_stok_in_out').val(htmlDecode(data.id_stok_in_out));
        $('#tipe_in_out').val(htmlDecode(data.tipe_in_out));
        $('#stok_in_out').val(htmlDecode(data.stok_in_out));
        $('#tgl_in').val(htmlDecode(data.custom_tgl_in));
        $('#tgl_out').val(htmlDecode(data.custom_tgl_out));
        $('#tgl_expired').val(htmlDecode(data.custom_tgl_expired));
        $('#catatan_in_out').val(htmlDecode(data.catatan_in_out));


        $('.tgl_stok').hide();
        if(htmlDecode(data.tipe_in_out) == "IN"){
            $("#tgl_stok_in").show();
        } else {
            $("#tgl_stok_out").show();
        }


        $('.form-active-control').prop('disabled', true);

        $('.modal-button-save').css('display', 'none');
        $('.modal-button-view-only').css('display', 'block');
        $('#inout-modal').modal('toggle');

    });

    $('.add').click(function (e) {
        e.preventDefault();
        $('.invalid-feedback').css('display', 'none');
        $('#id_stok_in_out').val(0);
        setTimeout(function() {$('.modal-dialog').scrollTop(0);}, 200);
        $('.modal-button-view-only').css('display', 'none');
        $('#inout-form').trigger('reset');
        $('.form-active-control').prop('disabled', false);
        $('#inout-modal').modal('toggle');
        $('.modal-button-save').css('display', 'block');
        $('.tgl_stok').hide();
        $("#tgl_stok_in").show();
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
            url: admin_url + 'add_stok_in_out', // the url where we want to POST// our data object
            dataType: 'json',
            data: $('#inout-form').serialize() + '&id_product=' + id_product ,
            success: function (response) {
                $('.invalid-feedback').css('display', 'none');
                if(response.Status == "OK"){
                    get_in_out();
                    $('#inout-modal').modal('hide');
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


</script>
