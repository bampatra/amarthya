

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Izin</h1>
    <br>

    <button class="btn btn-primary add" style="background: #a50000; color: white; width: 300px;"> Buat Izin </button>
    <br> <br>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Izin</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <select id="status_izin" name="status_izin" class="form-control form-control-sm" data-live-search="true" style="width: 30%; float: left">
                    <option value="all">Semua Izin</option>
                    <option value="0"> Menunggu Persetujuan </option>
                    <option value="1"> Disetujui  </option>
                    <option value="2"> Ditolak </option>
                </select>
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr class="no-hover-style">
                        <th> Staff </th>
                        <th> Tanggal </th>
                        <th> Alasan </th>
                        <th> Status </th>
                        <th></th>
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


<div class="modal fade" tabindex="-1" role="dialog" id="izin-modal" style="z-index: 5000;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Izin</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="izin-form">

                    <div class="form-group">
                        <label> Staff </label>
                        <select id="id_staff" name="id_staff" class="form-control form-control-sm form-active-control" data-live-search="true">
                            <option value="none"> -- Pilih Staff -- </option>
                            <?php foreach ($staffs as $staff) { ?>
                                <option value="<?php echo $staff->id_staff; ?>">
                                    <?php echo $staff->nama_staff; ?>
                                </option>


                            <?php } ?>
                        </select>
                        <div class="invalid-feedback invalid-staff">Data tidak valid</div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-6 mb-3">
                            <label>Tgl Awal Izin</label>
                            <input type="datetime-local" class="form-control form-active-control" id="tgl_start_izin" name="tgl_start_izin">
                            <div class="invalid-feedback invalid-startdate">Data tidak valid</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Tgl Akhir Izin</label>
                            <input type="datetime-local" class="form-control form-active-control" id="tgl_end_izin" name="tgl_end_izin">
                            <div class="invalid-feedback invalid-enddate">Data tidak valid</div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputAddress">Alasan</label>
                        <textarea class="form-control form-active-control" id="alasan_izin" name="alasan_izin" rows="4"></textarea>
                        <div class="invalid-feedback invalid-alasan">Data tidak valid</div>
                    </div>

                    <input type="hidden" id="id_izin" name="id_izin" val="0">
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

<div class="modal fade" tabindex="-1" role="dialog" id="action-modal" style="z-index: 5000;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5 id="action-title"></h5>
                <br>
                <div class="form-group">
                    <label for="inputAddress">Keterangan</label>
                    <textarea class="form-control" id="keterangan_manager" name="keterangan_manager" rows="2"></textarea>
                    <div class="invalid-feedback invalid-alasan">Data tidak valid</div>
                </div>
                <div id="action-button"></div>
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

    .alert{
        padding: 0.3rem 0.5rem !important;
    }

</style>

<!-- Page level custom scripts -->

<!-- <script src="<?php echo base_url('assets/js/startbootstrap/demo/datatables-demo.js');?>"></script>-->

<script>

    document.title = "Izin - Amarthya Group";

    var status = "all";

    $('#status_izin').change(function(){
        status = $(this).val()
        get_izin(status);
    })

    get_izin();

    //get all products
    function get_izin(status = "all"){
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
                url     : admin_url + 'get_izin?status=' + status,
                type    : 'POST',
            },
            createdRow: function ( row, data, index ) {
                // $('td', row).eq(0).css("display", "none");
            },
            columns: [
                {"data": "nama_staff"},
                {
                    "data":{
                        "tgl_start_izin": "tgl_start_izin",
                        "tgl_end_izin": "tgl_end_izin"
                    },
                    mRender : function(data, type, full) {

                        let dateTimePartsStart = data.tgl_start_izin.split(/[- :]/);
                        dateTimePartsStart[1]--;
                        const temp_date_start = new Date(...dateTimePartsStart);

                        start = (temp_date_start.getDate() < 10 ? '0' : '') + temp_date_start.getDate() + '/' +
                            (temp_date_start.getMonth() < 10 ? '0' : '') + (temp_date_start.getMonth() + 1) + '/' +
                            temp_date_start.getFullYear() + " " +
                            (temp_date_start.getHours() < 10 ? '0' : '') + temp_date_start.getHours() + ':' +
                            (temp_date_start.getMinutes() < 10 ? '0' : '') + temp_date_start.getMinutes() + ':' +
                            (temp_date_start.getSeconds() < 10 ? '0' : '') + temp_date_start.getSeconds();

                        let dateTimePartsEnd = data.tgl_end_izin.split(/[- :]/);
                        dateTimePartsEnd[1]--;
                        const temp_date_end = new Date(...dateTimePartsEnd);

                        end = (temp_date_end.getDate() < 10 ? '0' : '') + temp_date_end.getDate() + '/' +
                            (temp_date_end.getMonth() < 10 ? '0' : '') + (temp_date_end.getMonth() + 1) + '/' +
                            temp_date_end.getFullYear() + " " +
                            (temp_date_end.getHours() < 10 ? '0' : '') + temp_date_end.getHours() + ':' +
                            (temp_date_end.getMinutes() < 10 ? '0' : '') + temp_date_end.getMinutes() + ':' +
                            (temp_date_end.getSeconds() < 10 ? '0' : '') + temp_date_end.getSeconds();


                        html = "Dari: " + start + " <br>Sampai: " + end;

                        return html;


                    }

                },
                {"data": "alasan_izin"},
                {
                    "data":{
                        "status_izin": "status_izin",
                        "id_staff_approval": "id_staff_approval"

                    },
                    mRender : function(data, type, full) {
                        if(data.status_izin == "0"){
                            return '<div class="alert alert-warning" role="alert">Menunggu Persetujuan</div>';
                        } else if (data.status_izin == "1") {
                            return '<div class="alert alert-success" role="alert">Disetujui oleh '+ data.id_staff_approval +'</div>';
                        } else if(data.status_izin == "2")
                            return '<div class="alert alert-danger" role="alert">Ditolak oleh '+ data.id_staff_approval +'</div>';
                    }

                },
                {
                    "data": {
                        "id_izin":"id_izin",
                        "status_izin": "status_izin",
                        "keterangan_manager": "keterangan_manager"

                    },
                    mRender : function(data, type, full) {

                        if(data.status_izin == '0' && <?php echo $this->session->userdata('is_admin') ?> == "1"){
                            html = '<button onclick="event.stopPropagation(); setuju('+ data.id_izin +')" class="btn btn-success control-btn"> Setuju </button>' +
                                '<button onclick="event.stopPropagation(); tolak('+ data.id_izin +');" class="btn btn-danger control-btn"> Tolak </button><br>';
                            return html;
                        } else {
                            if(data.keterangan_manager != ""){
                                html = '<strong>Keterangan: </strong><br>' +
                                    '   <span>'+ data.keterangan_manager +'</span>';
                            } else {
                                html = "";
                            }


                            return html;
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

    function setuju(id_izin){
        $('#action-modal').modal('toggle');
        $('#action-title').html("Yakin ingin <bold> menyetujui </bold> ijin ini?");
        $('#action-button').html('<button style="width: 100%" class="btn btn-primary" onclick="action(\'setuju\', '+ id_izin +')">Setuju</button>')
    }

    function tolak(id_izin){
        $('#action-modal').modal('toggle');
        $('#action-title').html("Yakin ingin <bold> menolak </bold> ijin ini?")
        $('#action-button').html('<button style="width: 100%" class="btn btn-danger" onclick="action(\'tolak\', '+ id_izin +')">Tolak</button>')
    }

    function action(action, id_izin){
        $('.loading').css("display", "block");
        $('.Veil-non-hover').fadeIn();
        $.ajax({
            type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url: admin_url + 'action_izin', // the url where we want to POST// our data object
            dataType: 'json',
            data: {id_izin: id_izin, keterangan_manager: $('#keterangan_manager').val(), action: action},
            success: function (response) {
                $('.invalid-feedback').css('display', 'none');
                if(response.Status == "OK"){
                    get_izin(status);
                    $('#action-modal').modal('hide');
                    $('#izin-modal').modal('hide');
                    $('#keterangan_manager').val('')
                } else if(response.Status == "ERROR" ){
                    show_snackbar(response.Message);
                }

                $('.loading').css("display", "none");
                $('.Veil-non-hover').fadeOut();
            }
        })
    }


    $('#dataTable').on( 'click', 'tbody tr', function () {

        data = $('#dataTable').DataTable().row( this ).data()

        if(data.status_izin != "0"){
            return;
        }

        $('body').addClass('modal-open');

        setTimeout(function() {$('.modal-dialog').scrollTop(0);}, 200);
        $('#id_izin').val(htmlDecode(data.id_izin));
        $('#id_staff').val(htmlDecode(data.id_staff));
        $('#tgl_start_izin').val(htmlDecode(data.custom_tgl_start));
        $('#tgl_end_izin').val(htmlDecode(data.custom_tgl_end));
        $('#alasan_izin').val(htmlDecode(data.alasan_izin));


        $('.form-active-control').prop('disabled', true);

        $('.modal-button-save').css('display', 'none');
        $('.modal-button-view-only').css('display', 'block');
        $('#izin-modal').modal('toggle');


    });

    $('.add').click(function (e) {
        e.preventDefault();
        $('.invalid-feedback').css('display', 'none');
        $('#id_izin').val(0);
        setTimeout(function() {$('.modal-dialog').scrollTop(0);}, 200);
        $('.modal-button-view-only').css('display', 'none');
        $('#izin-form').trigger('reset');
        $('.form-active-control').prop('disabled', false);
        $('#izin-modal').modal('toggle');
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
            url: admin_url + 'add_izin', // the url where we want to POST// our data object
            dataType: 'json',
            data: $('#izin-form').serialize(),
            success: function (response) {
                $('.invalid-feedback').css('display', 'none');
                if(response.Status == "OK"){
                    get_izin();
                    $('#izin-modal').modal('hide');
                } else if(response.Status == "FORMERROR") {
                    response.Error.forEach(function(error){
                        $('.'+ error +'').css('display', 'block');
                    })
                } else if(response.Status == "EXIST") {
                    show_snackbar('Nama izin sudah terdaftar');
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
            action('delete', $('#id_izin').val());
        }
    })

</script>
