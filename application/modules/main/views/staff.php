

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Data Staff</h1>
    <br>

    <button class="btn btn-primary add" style="background: #a50000; color: white; width: 300px;"> Tambah Staff </button>
    <br> <br>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Staff</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th style="display: none;"> ID </th>
                        <th> Nama Staff </th>
                        <th> Posisi </th>
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


<div class="modal fade" tabindex="-1" role="dialog" id="staff-modal" style="z-index: 5000">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Staff</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="staff-form">
                    <div class="form-group" >
                        <label class="col-form-label">Nama Staff</label>
                        <input type="text" id="nama_staff"  name="nama_staff" class="form-control form-active-control">
                        <div class="invalid-feedback invalid-namastaff">Data tidak valid</div>
                    </div>

                    <div class="form-group">
                        <label class="col-form-label">Tanggal Lahir</label>
                        <input type="date" id="tgl_lahir_staff" name="tgl_lahir_staff" class="form-control form-active-control">
                        <div class="invalid-feedback invalid-tanggallahir">Data tidak valid</div>
                    </div>

                    <div class="form-group">
                        <label class="col-form-label">Alamat</label>
                        <textarea id="alamat_staff" name="alamat_staff" class="form-control form-active-control"> </textarea>
                        <div class="invalid-feedback invalid-alamat">Data tidak valid</div>
                    </div>

                    <div class="form-group" >
                        <label class="col-form-label">No. HP</label>
                        <input type="text" id="no_hp_staff"  name="no_hp_staff" class="form-control form-active-control">
                        <div class="invalid-feedback invalid-nohp">Data tidak valid</div>
                    </div>

                    <div class="form-group" >
                        <label class="col-form-label">Posisi</label>
                        <select id="id_posisi" name="id_posisi" class="form-control form-active-control selectpicker" data-live-search="true">
                            <option value="none"> -- Pilih Posisi -- </option>
                            <?php foreach ($posisi_list as $posisi) { ?>
                                <option value="<?php echo $posisi->id_posisi; ?>">
                                    <?php echo $posisi->nama_posisi; ?>
                                </option>


                            <?php } ?>
                        </select>
                        <div class="invalid-feedback invalid-posisi">Data tidak valid</div>
                    </div>

                    <div class="form-group" >
                        <label class="col-form-label">Salary per 2 Minggu</label>
                        <input type="number" id="salary"  name="salary" class="form-control form-active-control">
                        <div class="invalid-feedback invalid-salary">Data tidak valid</div>
                    </div>

                    <div class="form-group" >
                        <label class="col-form-label">No. Rekening</label>
                        <input type="number" id="no_rek_staff"  name="no_rek_staff" class="form-control form-active-control">
                        <div class="invalid-feedback invalid-norek">Data tidak valid</div>
                    </div>

                    <div class="form-group" >
                        <label class="col-form-label">Nama Bank</label>
                        <input type="text" id="nama_bank_staff"  name="nama_bank_staff" class="form-control form-active-control">
                        <div class="invalid-feedback invalid-namabank">Data tidak valid</div>
                    </div>

                    <div class="form-group">
                        <label class="col-form-label">Tanggal Bergabung</label>
                        <input type="date" id="tgl_join_staff" name="tgl_join_staff" class="form-control form-active-control">
                        <div class="invalid-feedback invalid-tanggaljoin">Data tidak valid</div>
                    </div>

                    <input type="hidden" id="id_staff" name="id_staff" val="0">
                </form>
            </div>
            <div class="modal-footer">
                <div class="modal-button-view-only">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger delete">Hapus</button>
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

    document.title = "Staff - Amarthya Group";

    get_staff();

    function get_staff(){
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
                url     : admin_url + 'get_staff',
                type    : 'POST',
            },
            createdRow: function ( row, data, index ) {
                $('td', row).eq(0).css("display", "none");
            },
            columns: [
                {"data": "id_staff"},
                {"data": "nama_staff"},
                {"data": "nama_posisi"}

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
        $('#id_staff').val(htmlDecode(data.id_staff));
        $('#nama_staff').val(htmlDecode(data.nama_staff));
        $('#tgl_lahir_staff').val(htmlDecode(data.custom_tgl_lahir));
        $('#alamat_staff').val(htmlDecode(data.alamat_staff));
        $('#no_hp_staff').val(htmlDecode(data.no_hp_staff));
        $('#id_posisi').val(htmlDecode(data.id_posisi));
        $('#salary').val(htmlDecode(data.salary_staff));
        $('#no_rek_staff').val(htmlDecode(data.no_rek_staff));
        $('#nama_bank_staff').val(htmlDecode(data.nama_bank_staff));
        $('#tgl_join_staff').val(htmlDecode(data.custom_tgl_join));


        $('.form-active-control').prop('disabled', true);

        $('.modal-button-save').css('display', 'none');
        $('.modal-button-view-only').css('display', 'block');
        $('#staff-modal').modal('toggle');

        $('.selectpicker').selectpicker('refresh')
        $('.loading').css("display", "none");

    });

    $('.add').click(function (e) {
        e.preventDefault();
        $('.invalid-feedback').css('display', 'none');
        $('#id_staff').val(0);
        $('#id_posisi').val('none');
        setTimeout(function() {$('.modal-dialog').scrollTop(0);}, 200);
        $('.modal-button-view-only').css('display', 'none');
        $('#staff-form').trigger('reset');
        $('.form-active-control').prop('disabled', false);
        $('#staff-modal').modal('toggle');
        $('.modal-button-save').css('display', 'block');
    })

    $('.edit').click(function(e){
        $('.invalid-feedback').css('display', 'none');
        setTimeout(function() {$('.modal-dialog').scrollTop(0);}, 200);
        $('.modal-button-save').css('display', 'block');
        $('.modal-button-view-only').css('display', 'none');
        $('.form-active-control').prop('disabled', false);
        $('.selectpicker').selectpicker('refresh')
    })

    $('.save').click(function(e){
        $('.loading').css("display", "block");
        $('.Veil-non-hover').fadeIn();
        $.ajax({
            type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url: admin_url + 'add_staff', // the url where we want to POST// our data object
            dataType: 'json',
            data: $('#staff-form').serialize(),
            success: function (response) {
                $('.invalid-feedback').css('display', 'none');
                if(response.Status == "OK"){
                    get_staff();
                    $('#staff-modal').modal('hide');
                } else if(response.Status == "FORMERROR") {
                    response.Error.forEach(function(error){
                        $('.'+ error +'').css('display', 'block');
                    })
                } else if(response.Status == "EXIST") {
                    show_snackbar('Nama Staff sudah terdaftar');
                } else if(response.Status == "ERROR" ){
                    show_snackbar(response.Message);
                }

                $('.loading').css("display", "none");
                $('.Veil-non-hover').fadeOut();
            }
        })
    })



</script>
