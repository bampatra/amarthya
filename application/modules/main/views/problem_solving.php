

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
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr class="no-hover-style">
                        <th style="display: none;"> ID </th>
                        <th> Kode Problem </th>
                        <th> Pesanan/Produk </th>
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


<div class="modal fade" tabindex="-1" role="dialog" id="customer-modal" style="z-index: 5000">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Customer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="customer-form">
                    <div class="form-group" >
                        <label class="col-form-label">Nama Customer</label>
                        <input type="text" id="nama_customer"  name="nama_customer" class="form-control form-active-control">
                        <div class="invalid-feedback invalid-namacustomer">Data tidak valid</div>
                    </div>

                    <div class="form-group">
                        <label class="col-form-label">Alamat Delivery</label>
                        <textarea id="alamat_customer" name="alamat_customer" class="form-control form-active-control"> </textarea>
                        <div class="invalid-feedback invalid-alamatcustomer">Data tidak valid</div>
                    </div>

                    <div class="form-group" >
                        <label class="col-form-label">No. HP</label>
                        <input type="number" id="no_hp_customer"  name="no_hp_customer" class="form-control form-active-control">
                        <div class="invalid-feedback invalid-nohp">Data tidak valid</div>
                    </div>

                    <div class="form-group" >
                        <label class="col-form-label">Email</label>
                        <input type="text" id="email_customer"  name="email_customer" class="form-control form-active-control">
                        <div class="invalid-feedback invalid-email">Data tidak valid</div>
                    </div>

                    <div class="form-group">
                        <label class="col-form-label">Catatan</label>
                        <textarea id="catatan_customer" name="catatan_customer" class="form-control form-active-control"> </textarea>
                    </div>


                    <input type="hidden" id="id_customer" name="id_customer" val="0">
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

    document.title = "Problem Solving - Amarthya Group";

    // get_customer();

    //get all products
    function get_customer(){
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
                url     : admin_url + 'get_customer',
                type    : 'POST',
            },
            createdRow: function ( row, data, index ) {
                $('td', row).eq(0).css("display", "none");
            },
            columns: [
                {"data": "id_customer"},
                {"data": "nama_customer"}

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

        id_customer = $('#dataTable').DataTable().row( this ).data().id_customer;

        setTimeout(function() {$('.modal-dialog').scrollTop(0);}, 200);
        $('#id_customer').val(htmlDecode(data.id_customer));
        $('#nama_customer').val(htmlDecode(data.nama_customer));
        $('#alamat_customer').val(htmlDecode(data.alamat_customer));
        $('#no_hp_customer').val(htmlDecode(data.no_hp_customer));
        $('#email_customer').val(htmlDecode(data.email_customer));
        $('#catatan_customer').val(htmlDecode(data.catatan_customer));

        $('.form-active-control').prop('disabled', true);

        $('.modal-button-save').css('display', 'none');
        $('.modal-button-view-only').css('display', 'block');
        $('#customer-modal').modal('toggle');


    });

    $('.add').click(function (e) {
        e.preventDefault();
        $('.invalid-feedback').css('display', 'none');
        $('#id_customer').val(0);
        setTimeout(function() {$('.modal-dialog').scrollTop(0);}, 200);
        $('.modal-button-view-only').css('display', 'none');
        $('#customer-form').trigger('reset');
        $('.form-active-control').prop('disabled', false);
        $('#customer-modal').modal('toggle');
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
            url: admin_url + 'add_customer', // the url where we want to POST// our data object
            dataType: 'json',
            data: $('#customer-form').serialize(),
            success: function (response) {
                $('.invalid-feedback').css('display', 'none');
                if(response.Status == "OK"){
                    get_customer();
                    $('#customer-modal').modal('hide');
                } else if(response.Status == "FORMERROR") {
                    response.Error.forEach(function(error){
                        $('.'+ error +'').css('display', 'block');
                    })
                } else if(response.Status == "EXIST") {
                    show_snackbar('Nama Customer sudah terdaftar');
                } else if(response.Status == "ERROR" ){
                    show_snackbar(response.Message);
                }

                $('.loading').css("display", "none");
                $('.Veil-non-hover').fadeOut();
            }
        })
    })



</script>
