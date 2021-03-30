

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Formulir Pick Up</h1>
    <br>
    <!-- DataTales Example -->

    <div class="wrapper">
        <div class="one">
            <h6> Staff </h6>
            <div class="green-line"></div>
            <div id="staff_info" style="font-size: 14px">
            </div>
            <span class="link pilih-staff"> Pilih Staff Pick Up </span>
            


        </div>
        <div class="two">
            <h6> Status Pick Up </h6>
            <div class="green-line"></div>
            <div class="form-group row">
                <label class="col-sm-12 col-form-label col-form-label-sm">
                    <div class="alert alert-info alert-payment" role="alert">
                        <strong>BARU</strong>
                    </div>
                </label>
            </div>

        </div>
    </div>


    <div class="three" >
        <h6> Detail Order </h6>
        <div id="order-data"></div><br>
        <div id="item-lists"></div>
        <span class="link pilih-order"> Pilih Order </span>

 </div>

    <div class="wrapper" style="margin-top: 10px">
        <div class="one">
            <div class="form-group" >
                <label class="col-form-label">Catatan Pick Up</label>
                <textarea id="catatan_pick_up" name="catatan_pick_up" class="form-control form-active-control"> </textarea>
            </div>
            <br>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label col-form-label-sm">Tgl Pick Up</label>
                <div class="col-sm-9">
                    <input type="date" id="tgl_pick_up" name="tgl_pick_up" class="form-control form-control-sm form-active-control">
                </div>
            </div>
        </div>

        <div class="two">
            <div class="form-group" >
                <label class="col-form-label">Alamat Pick Up</label>
                <textarea id="alamat_pick_up" name="alamat_pick_up" class="form-control form-active-control"> </textarea>
            </div>
            <br>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label col-form-label-sm">No HP</label>
                <div class="col-sm-10">
                    <input type="number" id="no_hp_pick_up" name="no_hp_pick_up" class="form-control form-control-sm form-active-control">
                </div>
            </div>
        </div>

    </div>
    <br>
    <button class="btn btn-primary save" style="width: 100%;  font-size: 14px;">Simpan Pick Up</button>
    <br><br><br><br><br>


    <div class="modal fade" tabindex="-1" role="dialog" id="staff-modal" style="z-index: 5000">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Customer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="staffDataTable" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th style="display: none;"> ID </th>
                                <th> Nama Staff </th>
                                <th> Posisi Staff </th>
                            </tr>
                            </thead>
                            <tbody id="main-content">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="order-modal" style="z-index: 5000">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="orderDataTable" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th> Order </th>
                            </tr>
                            </thead>
                            <tbody id="main-content">

                            </tbody>
                        </table>
                    </div>
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

    .product-item{
        border: 1px solid rgba(20,143,143,0.3);
        padding: 7px;
        margin-top:5px;
    }

    .alert-payment{
        padding: 0.2rem 0.75rem;
        margin: 0;
        text-align: left
    }

    .form-group{
        margin-bottom: 0;
    }

    span{
        font-size: 13px;
    }
</style>

<!-- Page level custom scripts -->

<!-- <script src="<?php echo base_url('assets/js/startbootstrap/demo/datatables-demo.js');?>"></script>-->

<script>

    var selected_order, selected_staff, selected_vendor;


    $('#collapseUser').addClass('show');
    $('#navbar-user').addClass('active');


    $('.save').click(function(e){

        if(confirm("Pastikan semua data sudah benar. Lanjutkan?")){
            $('.loading').css("display", "block");
            $('.Veil-non-hover').fadeIn();

            $.ajax({
                type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                url: admin_url + 'add_pick_up', // the url where we want to POST// our data object
                dataType: 'json',
                data: {
                    id_vendor: selected_vendor,
                    alamat_pick_up: $('#alamat_pick_up').val(),
                    no_hp_pick_up: $('#no_hp_pick_up').val(),
                    id_order_vendor_m: selected_order,
                    tgl_pick_up: $('#tgl_pick_up').val(),
                    catatan_pick_up: $('#catatan_pick_up').val(),
                    id_staff: selected_staff
                },
                success: function (response) {
                    if(response.Status == "OK"){
                        window.location.href = admin_url + 'pick_up_list';
                    } else if(response.Status == "ERROR" ){
                        show_snackbar(response.Message);

                        $('.loading').css("display", "none");
                        $('.Veil-non-hover').fadeOut();
                    }
                }
            })
        }

    })


    $('.pilih-staff').click(function(){
        get_staff();
    })

    $('.pilih-order').click(function(){
        get_order();
    })

    // ======== ONLY SHOW ORDER WITH STATUS 1-ACTIVE ========
    function get_order(){
        $('.loading').css("display", "block");
        $('.Veil-non-hover').fadeIn();

        $('#orderDataTable').DataTable().destroy();
        $('#orderDataTable').DataTable({
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
                url     : admin_url + 'get_order_vendor_m?pick_up=true',
                type    : 'POST'
            },
            createdRow: function ( row, data, index ) {
                // $('td', row).eq(0).css("display", "none");
            },
            columns: [
                {
                    "data": {
                        "id_order_vendor_m":"id_order_vendor_m",
                        "no_order_vendor":"no_order_vendor",
                        "nama_vendor":"nama_vendor",
                        "tgl_order_vendor":"tgl_order_vendor",
                        "grand_total_order":"grand_total_order"
                    },
                    mRender : function(data, type, full) {
                        var temp_date = new Date(data.tgl_order_vendor);

                        html = '<span style="font-size: 12px">'+ (temp_date.getDate() + 1) + '/' + temp_date.getMonth() + '/' + temp_date.getFullYear() +'</span><br>' +
                            '   <strong>'+ data.no_order_vendor +'</strong><br>' +
                            '<span>'+ data.nama_vendor +'</span><br>' +
                            '<span>Total Order: '+ convertToRupiah(data.grand_total_order) +'</span>';
                        return html;
                    }
                }

            ],
            initComplete: function (settings, json) {
                $('#order-modal').modal('toggle');
                $('.loading').css("display", "none");
                $('.Veil-non-hover').fadeOut();
            }
        });
    }


    $('#orderDataTable').on( 'click', 'tbody tr', function () {

        id_order_vendor_m = $('#orderDataTable').DataTable().row( this ).data().id_order_vendor_m;
        id_vendor = $('#orderDataTable').DataTable().row( this ).data().id_vendor;

        no_order = $('#orderDataTable').DataTable().row( this ).data().no_order_vendor;
        tgl_order_vendor = $('#orderDataTable').DataTable().row( this ).data().tgl_order_vendor;
        grand_total_order = $('#orderDataTable').DataTable().row( this ).data().grand_total_order;
        catatan_order = $('#orderDataTable').DataTable().row( this ).data().catatan_order_vendor;
        nama_vendor = $('#orderDataTable').DataTable().row( this ).data().nama_vendor;
        no_hp_vendor = $('#orderDataTable').DataTable().row( this ).data().no_hp_vendor;
        alamat_vendor = $('#orderDataTable').DataTable().row( this ).data().alamat_vendor;


        selected_order = id_order_vendor_m;
        selected_vendor = id_vendor;

        $('#item-lists').html("Memuat...");
        load_items(selected_order);

        $('#no_hp_pick_up').val(no_hp_vendor);
        $('#alamat_pick_up').val(alamat_vendor);

        var temp_date = new Date(tgl_order_vendor);

        html = '<span style="font-size: 12px">'+ (temp_date.getDate() + 1) + '/' + temp_date.getMonth() + '/' + temp_date.getFullYear() +'</span><br>' +
            '   <strong>'+ no_order +'</strong><br>' +
            '<span>'+ nama_vendor +'</span><br>' +
            '<span>Total Order: '+ convertToRupiah(grand_total_order) +'</span>' +
            '<br><span>Catatan Order: ' + catatan_order + '</span>';

        $('#order-data').html(html)
        $('#order-modal').modal('hide');
    });

    function load_items(id_order_vendor_m){
        $.ajax({
            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url         : admin_url + 'get_order_vendor_s', // the url where we want to POST// our data object
            dataType    : 'json',
            data        : {id_order_vendor_m: id_order_vendor_m},
            success     : function(data){
                html_info_items = "";

                data.forEach(function(data){

                    html_info_items += '<div class="product-item">\n' +
                        '                    <table width="100%">\n' +
                        '                        <tr class="no-hover-style">\n' +
                        '                            <td>\n' +
                                    data.nama_product +
                        '            </td>\n' +
                        '                            <td style="text-align: right">\n' +
                        '                                <span style="font-size: 9px;">'+ data.qty_order_vendor +' x '+ convertToRupiah(data.harga_order_vendor) +' </span> <br>\n' +
                        '                                <strong style="font-size: 13px;">'+ convertToRupiah(data.total_order_vendor) +'</strong>\n' +
                        '                            </td>\n' +
                        '                        </tr>\n' +
                        '                    </table>\n' +
                        '                </div>';

                })

                $('#item-lists').html(html_info_items);

            }
        })
    }

    function get_staff(){
        $('.loading').css("display", "block");
        $('.Veil-non-hover').fadeIn();

        $('#staffDataTable').DataTable().destroy();
        $('#staffDataTable').DataTable({
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
                $('#staff-modal').modal('toggle');
                $('.loading').css("display", "none");
                $('.Veil-non-hover').fadeOut();
            }
        });
    }



    $('#staffDataTable').on( 'click', 'tbody tr', function () {
        id_staff = $('#staffDataTable').DataTable().row( this ).data().id_staff;
        no_hp_staff = $('#staffDataTable').DataTable().row( this ).data().no_hp_staff;
        nama_staff = $('#staffDataTable').DataTable().row( this ).data().nama_staff;
        alamat_staff = $('#staffDataTable').DataTable().row( this ).data().alamat_staff;


        html = '<strong>'+ nama_staff +' ('+ no_hp_staff +')</strong><br>' +
            '<span style="font-size: 12px">'+ alamat_staff +'</span>';

        selected_staff = id_staff;

        $('#staff_info').html(html)
        $('#staff-modal').modal('hide');
    });




</script>
